<?php

namespace App\Http\Controllers;

use App\Services\DailyChallengeService;
use App\Services\GamificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DailyChallengeController extends Controller
{
    public function __construct(
        private DailyChallengeService $dailyChallengeService,
        private GamificationService $gamification
    ) {
    }

    /**
     * Tampilkan arena Tantangan Harian (Daily Challenge & Streak Booster).
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $challengeData = $this->dailyChallengeService->getChallengesForDate();
        $dailyStatus   = $this->dailyChallengeService->checkDailyStatus($user);
        $gami          = $this->gamification->stats($user);

        return view('dashboard.daily-challenge.index', [
            'user'          => $user,
            'challengeData' => $challengeData,
            'dailyStatus'   => $dailyStatus,
            'gami'          => $gami,
        ]);
    }

    /**
     * Simpan penyelesaian Tantangan Harian & klaim hadiah XP + Streak.
     */
    public function complete(Request $request): JsonResponse
    {
        $user = Auth::user();
        $award = $this->gamification->award($user, 'daily_challenge_complete', 30);

        return response()->json([
            'success'         => true,
            'message'         => 'Selamat! Kamu telah menyelesaikan Tantangan Harian hari ini dan mengamankan streak belajarmu.',
            'xp_gained'       => $award['xp_gained'],
            'total_xp'        => $award['total_xp'],
            'streak'          => $award['streak'],
            'streak_extended' => $award['streak_extended'],
            'level'           => $award['level'],
            'title'           => $award['title'],
            'leveled_up'      => $award['leveled_up'],
            'limit_reached'   => $award['limit_reached'],
        ]);
    }
}

