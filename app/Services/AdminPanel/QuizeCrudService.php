<?php

namespace App\Services\AdminPanel;

use App\Models\Quize\QuizeAnswerOption;
use App\Models\Quize\QuizeQuestion;
use App\Models\Quize\QuizeResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class QuizeCrudService
{
    // Load the KPI stats shown on the index page header cards.
    public function getQuizeIndexStats(): array
    {
        // Count total quiz responses (leads).
        $totalLeads = QuizeResponse::query()->count();

        // Count leads by user type for segment distribution.
        $b2bLeadCount = QuizeResponse::query()->where('user_type', 'b2b')->count();
        $b2cLeadCount = QuizeResponse::query()->where('user_type', 'b2c')->count();

        // Calculate percentages safely — avoid division by zero.
        $b2bPercent = $totalLeads > 0 ? round(($b2bLeadCount / $totalLeads) * 100) : 0;
        $b2cPercent = $totalLeads > 0 ? round(($b2cLeadCount / $totalLeads) * 100) : 0;

        return [
            'total_leads' => $totalLeads,
            'b2b_percent' => $b2bPercent,
            'b2c_percent' => $b2cPercent,
        ];
    }

    // Load the paginated lead activity feed shown in the index table.
    public function getLeadActivityFeed(int $perPage = 25): \Illuminate\Pagination\LengthAwarePaginator
    {
        // Load responses with eager pagination — no N+1.
        $leadPage = QuizeResponse::query()
            ->orderByDesc('submitted_at')
            ->paginate($perPage);

        return $leadPage;
    }

    // Load questions grouped by user_type for the create/manage page.
    public function getQuestionsByUserType(): array
    {
        // Load all questions with their answer options in one query.
        $allQuestions = QuizeQuestion::query()
            ->with(['answerOptions' => function ($query) {
                $query->orderBy('display_order');
            }])
            ->orderBy('user_type')
            ->orderBy('display_order')
            ->get();

        // Group the loaded questions by user_type.
        $commonQuestions = $allQuestions->where('user_type', 'common')->values();
        $b2bQuestions    = $allQuestions->where('user_type', 'b2b')->values();
        $b2cQuestions    = $allQuestions->where('user_type', 'b2c')->values();

        return [
            'common'         => $commonQuestions,
            'b2b'            => $b2bQuestions,
            'b2c'            => $b2cQuestions,
            'total_count'    => $allQuestions->count(),
            'common_count'   => $commonQuestions->count(),
            'b2b_count'      => $b2bQuestions->count(),
            'b2c_count'      => $b2cQuestions->count(),
        ];
    }

    // Save a new quiz question along with its four answer options.
    public function saveQuizeQuestion(array $questionData): int
    {
        return DB::transaction(function () use ($questionData) {
            // Determine the next display_order for this user_type.
            $nextDisplayOrder = QuizeQuestion::query()
                ->where('user_type', $questionData['user_type'])
                ->max('display_order');

            $nextDisplayOrder = $nextDisplayOrder ? ($nextDisplayOrder + 1) : 1;

            // Create the question record.
            $newQuestion = QuizeQuestion::create([
                'user_type'     => $questionData['user_type'],
                'phase_title'   => $questionData['phase_title'] ?? '',
                'question_text' => $questionData['question_text'],
                'display_order' => $nextDisplayOrder,
                'is_active'     => true,
            ]);

            // Save each answer option row one at a time.
            $optionLabels = ['A', 'B', 'C', 'D'];
            $sortOrder    = 1;

            foreach ($optionLabels as $label) {
                $optionTextKey   = 'option_' . strtolower($label);
                $targetFlowKey   = 'target_flow_' . strtolower($label);
                $isCorrectKey    = 'correct_option';

                $optionText  = trim($questionData[$optionTextKey] ?? '');
                $targetFlow  = $questionData[$targetFlowKey] ?? 'common';
                $isCorrect   = ($questionData[$isCorrectKey] ?? '') === $label;

                // Skip blank options — do not save empty rows.
                if ($optionText === '') {
                    $sortOrder++;
                    continue;
                }

                QuizeAnswerOption::create([
                    'question_id'      => $newQuestion->id,
                    'option_label'     => $label,
                    'option_text'      => $optionText,
                    'is_correct_answer' => $isCorrect,
                    'display_order'    => $sortOrder,
                    'target_flow'      => $targetFlow,
                ]);

                $sortOrder++;
            }

            return $newQuestion->id;
        });
    }

    // Toggle the active/disabled status of a question.
    public function toggleQuestionActiveStatus(int $questionId): bool
    {
        // Load the question by id.
        $question = QuizeQuestion::query()->find($questionId);

        if (! $question) {
            throw ValidationException::withMessages([
                'question_id' => 'Question not found.',
            ]);
        }

        // Flip the current active status.
        $newStatus = ! $question->is_active;

        $question->update(['is_active' => $newStatus]);

        return $newStatus;
    }

    // Delete a question and its answer options (cascade handles options).
    public function deleteQuizeQuestion(int $questionId): void
    {
        $question = QuizeQuestion::query()->find($questionId);

        if (! $question) {
            throw ValidationException::withMessages([
                'question_id' => 'Question not found.',
            ]);
        }

        // Deletion cascades to answer options via FK constraint.
        $question->delete();
    }
}
