<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Quiz;
use App\Models\QuizResult;
use App\Models\User;
use App\Models\UserProgress;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        return view('dashboard.index', [
            'user' => $user,
            ...$this->learningSummary($user),
        ]);
    }

    public function materials(): View
    {
        $user = Auth::user();
        $materials = Material::query()
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
        $progresses = UserProgress::query()
            ->where('user_id', $user->id)
            ->get()
            ->keyBy('material_id');

        return view('dashboard.materials', [
            'user' => $user,
            'materials' => $materials,
            'progresses' => $progresses,
            ...$this->learningSummary($user, $materials->count(), $progresses->where('completed', true)->count()),
        ]);
    }

    public function quizzes(): View
    {
        $user = Auth::user();
        $quizzes = Quiz::query()
            ->where('is_active', true)
            ->with('material:id,title')
            ->withCount('questions')
            ->latest()
            ->get();

        return view('dashboard.quizzes', [
            'user' => $user,
            'quizzes' => $quizzes,
            ...$this->learningSummary($user),
        ]);
    }

    public function progress(): View
    {
        $user = Auth::user();
        $materials = Material::query()
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
        $progresses = UserProgress::query()
            ->where('user_id', $user->id)
            ->get()
            ->keyBy('material_id');
        $recentResults = QuizResult::query()
            ->where('user_id', $user->id)
            ->with('quiz:id,title')
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.progress', [
            'user' => $user,
            'materials' => $materials,
            'progresses' => $progresses,
            'recentResults' => $recentResults,
            ...$this->learningSummary($user, $materials->count(), $progresses->where('completed', true)->count()),
        ]);
    }

    // ── Material ─────────────────────────────────────────────────────────────

    public function material(Material $material): View
    {
        $user = Auth::user();

        // Track access
        $progress = UserProgress::firstOrCreate(
            ['user_id' => $user->id, 'material_id' => $material->id],
            ['progress_percent' => 0, 'completed' => false]
        );
        $progress->update(['last_accessed_at' => now()]);

        $quiz = Quiz::where('material_id', $material->id)->where('is_active', true)->first();

        return view('dashboard.material', compact('material', 'progress', 'quiz'));
    }

    public function completeMaterial(Material $material): RedirectResponse
    {
        $user = Auth::user();

        UserProgress::updateOrCreate(
            ['user_id' => $user->id, 'material_id' => $material->id],
            ['completed' => true, 'progress_percent' => 100, 'last_accessed_at' => now()]
        );

        return redirect()->route('dashboard.materials')->with('success', "Materi \"{$material->title}\" selesai!");
    }

    // ── Quiz ──────────────────────────────────────────────────────────────────

    public function quiz(Quiz $quiz): View
    {
        $quiz->load('questions');
        return view('dashboard.quiz', compact('quiz'));
    }

    public function submitQuiz(Request $request, Quiz $quiz): RedirectResponse
    {
        $quiz->load('questions');
        $answers = $request->input('answers', []);

        $correct = 0;
        $review = [];

        foreach ($quiz->questions as $q) {
            $userAnswer = $answers[$q->id] ?? null;
            $isCorrect  = ($userAnswer !== null && (string) $userAnswer === (string) $q->correct_answer);

            if ($isCorrect) {
                $correct++;
            }

            $review[] = [
                'id'             => $q->id,
                'order'          => $q->order,
                'question'       => $q->question,
                'question_jp'    => $q->question_jp,
                'options'        => $q->options,
                'correct_answer' => $q->correct_answer,
                'user_answer'    => $userAnswer,
                'is_correct'     => $isCorrect,
                'romaji'         => $q->romaji,
                'meaning'        => $q->meaning,
            ];
        }

        $total     = $quiz->questions->count();
        $score     = $total > 0 ? (int) round(($correct / $total) * 100) : 0;
        $timeTaken = (int) $request->input('time_taken', 0);

        $result = QuizResult::create([
            'user_id'    => Auth::id(),
            'quiz_id'    => $quiz->id,
            'score'      => $score,
            'correct'    => $correct,
            'total'      => $total,
            'time_taken' => $timeTaken,
            'answers'    => $answers,
        ]);

        return redirect()->route('quiz.result', [
            'quiz'   => $quiz->id,
            'result' => $result->id,
        ])->with('quiz_review', $review);
    }

    public function quizResult(Quiz $quiz, Request $request): View
    {
        $quiz->load('questions');
        $resultId = $request->query('result');

        $result = null;
        if ($resultId) {
            $result = QuizResult::where('id', $resultId)
                ->where('user_id', Auth::id())
                ->first();
        }

        if (! $result) {
            $result = QuizResult::where('quiz_id', $quiz->id)
                ->where('user_id', Auth::id())
                ->latest()
                ->first();
        }

        $score   = $result ? $result->score : (int) $request->query('score', 0);
        $correct = $result ? $result->correct : (int) $request->query('correct', 0);
        $total   = $result ? $result->total : (int) $request->query('total', $quiz->questions->count());

        $review = session('quiz_review');

        if (! $review && $result && is_array($result->answers)) {
            $savedAnswers = $result->answers;
            $review = [];
            foreach ($quiz->questions as $q) {
                $userAnswer = $savedAnswers[$q->id] ?? null;
                $isCorrect  = ($userAnswer !== null && (string) $userAnswer === (string) $q->correct_answer);
                $review[] = [
                    'id'             => $q->id,
                    'order'          => $q->order,
                    'question'       => $q->question,
                    'question_jp'    => $q->question_jp,
                    'options'        => $q->options,
                    'correct_answer' => $q->correct_answer,
                    'user_answer'    => $userAnswer,
                    'is_correct'     => $isCorrect,
                    'romaji'         => $q->romaji,
                    'meaning'        => $q->meaning,
                ];
            }
        }

        return view('dashboard.quiz-result', [
            'quiz'       => $quiz,
            'score'      => $score,
            'correct'    => $correct,
            'total'      => $total,
            'review'     => $review ?? [],
            'quizResult' => $result,
        ]);
    }

    /**
     * @return array{totalMaterials: int, completedCount: int, overallProgress: int, totalQuizzesTaken: int, avgScore: int}
     */
    private function learningSummary(User $user, ?int $totalMaterials = null, ?int $completedCount = null): array
    {
        $totalMaterials ??= Material::query()->where('is_active', true)->count();
        $completedCount ??= UserProgress::query()
            ->where('user_id', $user->id)
            ->where('completed', true)
            ->count();
        $quizStats = QuizResult::query()
            ->where('user_id', $user->id)
            ->selectRaw('COUNT(*) as total_quizzes_taken, COALESCE(ROUND(AVG(score)), 0) as avg_score')
            ->first();

        return [
            'totalMaterials' => $totalMaterials,
            'completedCount' => $completedCount,
            'overallProgress' => $totalMaterials > 0 ? (int) round(($completedCount / $totalMaterials) * 100) : 0,
            'totalQuizzesTaken' => (int) $quizStats->total_quizzes_taken,
            'avgScore' => (int) $quizStats->avg_score,
        ];
    }
}
