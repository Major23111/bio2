<?php

namespace App\Services\Quize;

use App\Models\Quize\QuizeQuestion;
use App\Models\Quize\QuizeResponse;
use App\Models\Quize\QuizeResponseAnswer;
use App\Services\Coupon\CouponService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class QuizeService
{
    protected const REWARD_COUPON_CODE = 'BIOGENIX15';

    public function __construct(
        protected CouponService $couponService,
    ) {
    }

    // This prepares the diagnostic quiz page data used by the guest quiz page.
    public function quizePageData(): array
    {
        return [
            'quizQuestions' => $this->quizQuestions(),
        ];
    }

    // This loads the initial common questions for the quiz page.
    public function quizQuestions(): Collection
    {
        $quizQuestions = QuizeQuestion::query()
            ->where('user_type', 'common')
            ->with(['answerOptions' => function ($query): void {
                $query->orderBy('display_order')->orderBy('id');
            }])
            ->orderBy('display_order')
            ->orderBy('id')
            ->get();

        if ($quizQuestions->isEmpty()) {
            Log::warning('Common diagnostic quiz questions are not configured.');
        }

        return $quizQuestions;
    }

    // This loads quiz questions specific to the selected user type.
    public function quizQuestionsByUserType(string $selectedUserType): Collection
    {
        $userTypeList = ['common', $selectedUserType];

        $quizQuestions = QuizeQuestion::query()
            ->whereIn('user_type', $userTypeList)
            ->with(['answerOptions' => function ($query): void {
                $query->orderBy('display_order')->orderBy('id');
            }])
            ->orderBy('display_order')
            ->orderBy('id')
            ->get();

        if ($quizQuestions->isEmpty()) {
            Log::warning('User-specific diagnostic quiz questions are not configured.', [
                'user_type' => $selectedUserType,
            ]);
        }

        return $quizQuestions;
    }

    // This validates the selected answers, calculates the result, and saves the participant response.
    public function createQuizResponse(array $validatedInput): array
    {
        // Get user selected type from input.
        $selectedUserType = (string) $validatedInput['user_type'];

        // Load quiz questions for this user type.
        $quizQuestions = $this->quizQuestionsByUserType($selectedUserType);

        if ($quizQuestions->isEmpty()) {
            throw ValidationException::withMessages([
                'quiz' => 'Quiz questions are not available for your selection. Please try again.',
            ]);
        }

        // Step 1: normalize the selected answers once so the remaining checks use one stable structure.
        $selectedAnswers = $this->normalizeSelectedAnswers($validatedInput['selected_answers'] ?? []);

        // Step 2: make sure every configured quiz question has one selected answer.
        $this->ensureAllQuestionsAreAnswered($quizQuestions, $selectedAnswers);

        // Step 3: compare the selected options with the correct options and calculate the final score.
        $scoreDetails = $this->evaluateSelectedAnswers($quizQuestions, $selectedAnswers);

        // Step 4: load the configured reward coupon code that should be shown on the result screen.
        $rewardCouponCode = $this->resolveRewardCouponCode();

        // Step 5: save the response header and the selected answer rows together.
        $savedResponse = DB::transaction(function () use ($validatedInput, $scoreDetails, $rewardCouponCode, $selectedUserType): QuizeResponse {
            $quizeResponse = QuizeResponse::query()->create([
                'user_type' => $selectedUserType,
                'participant_first_name' => trim((string) $validatedInput['participant_first_name']),
                'participant_last_name' => filled($validatedInput['participant_last_name'] ?? null)
                    ? trim((string) $validatedInput['participant_last_name'])
                    : null,
                'participant_email' => strtolower(trim((string) $validatedInput['participant_email'])),
                'total_questions' => $scoreDetails['total_questions'],
                'total_correct_answers' => $scoreDetails['total_correct_answers'],
                'score_percentage' => $scoreDetails['score_percentage'],
                'reward_coupon_code' => $rewardCouponCode,
                'submitted_at' => now(),
            ]);

            foreach ($scoreDetails['question_results'] as $questionResult) {
                QuizeResponseAnswer::query()->create([
                    'response_id' => (int) $quizeResponse->id,
                    'question_id' => (int) $questionResult['question_id'],
                    'selected_option_id' => (int) $questionResult['selected_option_id'],
                    'is_correct_answer' => (bool) $questionResult['is_correct_answer'],
                ]);
            }

            return $quizeResponse;
        });

        $resultSummary = $this->buildResultSummary($scoreDetails['score_percentage']);
        $performanceBreakdown = $this->buildPerformanceBreakdown($scoreDetails['question_results']);

        Log::info('Diagnostic quiz response saved successfully.', [
            'diagnostic_quiz_response_id' => $savedResponse->id,
            'user_type' => $selectedUserType,
            'participant_email' => $savedResponse->participant_email,
            'score_percentage' => $savedResponse->score_percentage,
        ]);

        return [
            'response_id' => (int) $savedResponse->id,
            'user_type' => $selectedUserType,
            'total_questions' => $scoreDetails['total_questions'],
            'total_correct_answers' => $scoreDetails['total_correct_answers'],
            'score_percentage' => $scoreDetails['score_percentage'],
            'reward_coupon_code' => $rewardCouponCode,
            'result_title_html' => $resultSummary['result_title_html'],
            'result_description' => $resultSummary['result_description'],
            'performance_breakdown' => $performanceBreakdown,
        ];
    }

    // This converts the incoming answer payload into one integer-keyed map.
    protected function normalizeSelectedAnswers(array $selectedAnswers): array
    {
        return collect($selectedAnswers)
            ->mapWithKeys(function ($selectedOptionId, $questionId): array {
                $normalizedQuestionId = (int) $questionId;
                $normalizedSelectedOptionId = (int) $selectedOptionId;

                return [$normalizedQuestionId => $normalizedSelectedOptionId];
            })
            ->filter(function (int $selectedOptionId, int $questionId): bool {
                return $questionId > 0 && $selectedOptionId > 0;
            })
            ->all();
    }

    // This makes sure the participant has answered every configured question before saving the response.
    protected function ensureAllQuestionsAreAnswered(Collection $quizQuestions, array $selectedAnswers): void
    {
        foreach ($quizQuestions as $quizQuestion) {
            if (! array_key_exists((int) $quizQuestion->id, $selectedAnswers)) {
                throw ValidationException::withMessages([
                    'selected_answers' => 'Please answer all quiz questions before submitting.',
                ]);
            }
        }
    }

    // This checks each selected option and calculates the overall quiz score.
    protected function evaluateSelectedAnswers(Collection $quizQuestions, array $selectedAnswers): array
    {
        $questionResults = [];
        $totalCorrectAnswers = 0;
        $totalQuestions = $quizQuestions->count();

        foreach ($quizQuestions as $quizQuestion) {
            $selectedOptionId = (int) $selectedAnswers[(int) $quizQuestion->id];
            $selectedOption = $quizQuestion->answerOptions->firstWhere('id', $selectedOptionId);

            if (! $selectedOption) {
                throw ValidationException::withMessages([
                    'selected_answers' => 'One or more selected answers are not valid for this quiz.',
                ]);
            }

            $isCorrectAnswer = (bool) $selectedOption->is_correct_answer;

            if ($isCorrectAnswer) {
                $totalCorrectAnswers++;
            }

            $questionResults[] = [
                'question_id' => (int) $quizQuestion->id,
                'display_order' => (int) $quizQuestion->display_order,
                'selected_option_id' => (int) $selectedOption->id,
                'is_correct_answer' => $isCorrectAnswer,
            ];
        }

        $scorePercentage = $totalQuestions > 0
            ? (int) round(($totalCorrectAnswers / $totalQuestions) * 100)
            : 0;

        return [
            'total_questions' => $totalQuestions,
            'total_correct_answers' => $totalCorrectAnswers,
            'score_percentage' => $scorePercentage,
            'question_results' => $questionResults,
        ];
    }

    // This loads the active reward coupon code used by the quiz result page.
    protected function resolveRewardCouponCode(): string
    {
        // Step 1: load the active reward coupon code from the shared coupon service.
        $rewardCouponCode = $this->couponService->readActiveCouponCode(self::REWARD_COUPON_CODE);

        // Step 2: stop the flow when the reward coupon is not active.
        if (! $rewardCouponCode) {
            Log::warning('Diagnostic quiz reward coupon is not configured as active.', [
                'coupon_code' => self::REWARD_COUPON_CODE,
            ]);

            throw ValidationException::withMessages([
                'quiz' => 'Quiz reward is not available right now. Please try again later.',
            ]);
        }

        return $rewardCouponCode;
    }

    // This returns the result title and description shown on the final score screen.
    protected function buildResultSummary(int $scorePercentage): array
    {
        if ($scorePercentage >= 85) {
            return [
                'result_title_html' => 'Advanced<br>Proficiency Level<br>Attained.',
                'result_description' => 'Your technical precision in diagnostic protocols demonstrates exceptional mastery of Biogenix standards and laboratory compliance.',
            ];
        }

        if ($scorePercentage >= 60) {
            return [
                'result_title_html' => 'Strong<br>Clinical Readiness<br>Confirmed.',
                'result_description' => 'Your responses show a solid understanding of diagnostic workflows with a few areas that can be improved further.',
            ];
        }

        return [
            'result_title_html' => 'Growth Opportunity Identified.',
            'result_description' => 'Your responses highlight useful improvement areas across kit selection, compliance, and preparation workflows.',
        ];
    }

    // This prepares the three result bars shown on the final score card.
    protected function buildPerformanceBreakdown(array $questionResults): array
    {
        $questionResultsByOrder = collect($questionResults)->keyBy('display_order');

        $kitSelectionAndStoragePercentage = (int) round(collect([
            (bool) ($questionResultsByOrder[1]['is_correct_answer'] ?? false),
            (bool) ($questionResultsByOrder[2]['is_correct_answer'] ?? false),
        ])->filter()->count() / 2 * 100);

        $complianceStandardsPercentage = (bool) ($questionResultsByOrder[3]['is_correct_answer'] ?? false) ? 100 : 0;
        $samplePreparationPercentage = (bool) ($questionResultsByOrder[4]['is_correct_answer'] ?? false) ? 100 : 0;

        return [
            [
                'label' => 'Kit Selection & Storage',
                'percentage' => $kitSelectionAndStoragePercentage,
            ],
            [
                'label' => 'Compliance Standards',
                'percentage' => $complianceStandardsPercentage,
            ],
            [
                'label' => 'Sample Preparation',
                'percentage' => $samplePreparationPercentage,
            ],
        ];
    }
}
