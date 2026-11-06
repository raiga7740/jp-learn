<?php

namespace App\Http\Controllers;

use App\Models\JlptExamResult;
use App\Services\JlptExamService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class JlptExamController extends Controller
{
    public function __construct(
        public JlptExamService $examService,
    ) {
    }

    /**
     * Halaman pengantar, instruksi, dan riwayat simulasi ujian JLPT N5.
     */
    public function index(): View
    {
        $user = Auth::user();

        $history = JlptExamResult::query()
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $config = $this->examService->getExamConfig();

        $bestScore = $history->max('score') ?? 0;
        $totalAttempts = $history->count();
        $hasPassed = $history->contains('is_passed', true);

        return view('dashboard.jlpt.index', [
            'user'          => $user,
            'history'       => $history,
            'config'        => $config,
            'bestScore'     => $bestScore,
            'totalAttempts' => $totalAttempts,
            'hasPassed'     => $hasPassed,
        ]);
    }

    /**
     * Halaman pengerjaan simulasi ujian JLPT N5 secara langsung (real-time).
     */
    public function start(): View
    {
        $user = Auth::user();
        $config = $this->examService->getExamConfig();
        $questions = $this->examService->getQuestions();

        return view('dashboard.jlpt.exam', [
            'user'      => $user,
            'config'    => $config,
            'questions' => $questions,
        ]);
    }

    /**
     * Simpan dan evaluasi lembar jawaban ujian siswa.
     */
    public function submit(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $answers = (array) $request->input('answers', []);
        $timeTaken = (int) $request->input('time_taken', 0);

        $result = $this->examService->evaluate($user, $answers, $timeTaken);

        return redirect()
            ->route('jlpt.result', $result->id)
            ->with('success', 'Ujian simulasi JLPT N5 selesai dievaluasi!');
    }

    /**
     * Tampilkan hasil nilai, analisis per seksi, dan pembahasan detail.
     */
    public function result(JlptExamResult $exam): View
    {
        if ($exam->user_id !== Auth::id()) {
            abort(403, 'Akses hasil ujian tidak diizinkan.');
        }

        $questions = $this->examService->getQuestions();
        $config = $this->examService->getExamConfig();

        return view('dashboard.jlpt.result', [
            'user'      => Auth::user(),
            'exam'      => $exam,
            'questions' => $questions,
            'config'    => $config,
        ]);
    }

    /**
     * Tampilkan Sertifikat Kelulusan Resmi Digital yang dapat dicetak/disimpan ke PDF.
     */
    public function certificate(JlptExamResult $exam): View
    {
        if ($exam->user_id !== Auth::id()) {
            abort(403, 'Akses sertifikat tidak diizinkan.');
        }

        if (!$exam->is_passed) {
            abort(403, 'Sertifikat hanya tersedia bagi peserta yang telah lulus ujian.');
        }

        return view('dashboard.jlpt.certificate', [
            'user' => Auth::user(),
            'exam' => $exam,
        ]);
    }
}

