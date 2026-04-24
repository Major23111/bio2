<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Services\AdminPanel\QuizeCrudService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class QuizeCrudController extends Controller
{
    public function __construct(protected QuizeCrudService $quizeCrudService)
    {
    }

    // Load the quiz management index page with lead stats and activity feed.
    public function index(): View
    {
        // Load KPI stats for the header cards.
        $quizeStats = $this->quizeCrudService->getQuizeIndexStats();

        // Load the paginated lead activity feed for the table.
        $leadFeed = $this->quizeCrudService->getLeadActivityFeed(25);

        return view('admin.quiz.index', [
            'quizeStats' => $quizeStats,
            'leadFeed'   => $leadFeed,
        ]);
    }

    // Load the question creation and management page.
    public function create(): View
    {
        // Load all questions grouped by user type.
        $questionGroups = $this->quizeCrudService->getQuestionsByUserType();

        return view('admin.quiz.create', [
            'questionGroups' => $questionGroups,
        ]);
    }

    // Save a new quiz question submitted from the create form.
    public function storeQuestion(Request $request): RedirectResponse
    {
        try {
            // Validate the required question fields.
            $validatedData = $request->validate([
                'user_type'     => 'required|in:common,b2b,b2c',
                'question_text' => 'required|string|max:1000',
                'phase_title'   => 'nullable|string|max:150',
                'option_a'      => 'required|string|max:255',
                'option_b'      => 'required|string|max:255',
                'option_c'      => 'nullable|string|max:255',
                'option_d'      => 'nullable|string|max:255',
                'correct_option' => 'required|in:A,B,C,D',
                'target_flow_a' => 'nullable|string|max:30',
                'target_flow_b' => 'nullable|string|max:30',
                'target_flow_c' => 'nullable|string|max:30',
                'target_flow_d' => 'nullable|string|max:30',
            ]);

            // Save the question and its options through the service.
            $this->quizeCrudService->saveQuizeQuestion($validatedData);

            $response = redirect()->route('admin.quiz.create')
                ->with('success', 'Question saved successfully.');
        } catch (Throwable $exception) {
            $response = redirect()->back()
                ->withInput()
                ->with('error', 'Unable to save question. Please try again.');
        }

        return $response;
    }

    // Toggle active/disabled status of a question — called via POST from the toggle button.
    public function toggleQuestionStatus(Request $request, int $questionId): JsonResponse
    {
        try {
            $newStatus = $this->quizeCrudService->toggleQuestionActiveStatus($questionId);

            $response = response()->json([
                'success'    => true,
                'is_active'  => $newStatus,
            ]);
        } catch (Throwable $exception) {
            $response = response()->json([
                'success' => false,
                'message' => 'Unable to update question status.',
            ], 500);
        }

        return $response;
    }

    // Delete a question — called via DELETE from the delete button.
    public function destroyQuestion(int $questionId): RedirectResponse
    {
        try {
            $this->quizeCrudService->deleteQuizeQuestion($questionId);

            $response = redirect()->route('admin.quiz.create')
                ->with('success', 'Question deleted successfully.');
        } catch (Throwable $exception) {
            $response = redirect()->back()
                ->with('error', 'Unable to delete question.');
        }

        return $response;
    }
}
