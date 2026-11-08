<?php

namespace App\Http\Controllers;

use App\Models\DailyActivity;
use App\Models\User;
use App\Services\GamificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeaderboardController extends Controller
{
    /**
     * Jumlah maksimal pengguna yang ditampilkan di papan peringkat (Top 50).
     */
    public const LEADERBOARD_LIMIT = 50;

    public function __construct(
        public GamificationService $gamification,
    ) {}

    /**
     * Tampilkan halaman Papan Peringkat (Weekly XP, Streak Juara, & Sepanjang Masa).
     */
    public function index(Request $request): View
    {
        /** @var User $currentUser */
        $currentUser = $request->user();
        $startOfWeek = Carbon::now(GamificationService::TIMEZONE)->subDays(6)->toDateString();

        // 1. Leaderboard XP Pekan Ini (7 hari terakhir dari daily_activities)
        $weeklyActivities = DailyActivity::query()
            ->whereDate('activity_date', '>=', $startOfWeek)
            ->whereHas('user')
            ->selectRaw('user_id, SUM(xp_earned) as weekly_xp')
            ->groupBy('user_id')
            ->orderByDesc('weekly_xp')
            ->with('user:id,name,xp,current_streak')
            ->take(self::LEADERBOARD_LIMIT)
            ->get();

        $weeklyLeaderboard = $weeklyActivities->map(function ($act, $index) use ($currentUser) {
            $user = $act->user;
            $xp = (int) $act->weekly_xp;
            $levelInfo = $this->gamification->levelInfo((int) ($user->xp ?? 0));

            return [
                'rank' => $index + 1,
                'user_id' => $user->id,
                'name' => $user->name,
                'score' => $xp,
                'score_label' => number_format($xp) . ' XP',
                'level' => $levelInfo['level'],
                'title' => $levelInfo['title'],
                'streak' => (int) ($user->current_streak ?? 0),
                'is_current_user' => ($user->id === $currentUser->id),
            ];
        });

        // Hitung skor mingguan & ranking user yang sedang login
        $currentUserWeeklyXp = (int) DailyActivity::query()
            ->where('user_id', $currentUser->id)
            ->whereDate('activity_date', '>=', $startOfWeek)
            ->sum('xp_earned');

        $usersWithMoreWeeklyXp = DailyActivity::query()
            ->whereDate('activity_date', '>=', $startOfWeek)
            ->whereHas('user')
            ->selectRaw('user_id, SUM(xp_earned) as total')
            ->groupBy('user_id')
            ->havingRaw('SUM(xp_earned) > ?', [$currentUserWeeklyXp])
            ->get()
            ->count();

        $currentUserWeeklyRank = $usersWithMoreWeeklyXp + 1;

        // 2. Leaderboard Rantai Belajar (Streak Juara)
        $streakUsers = User::query()
            ->orderByDesc('current_streak')
            ->orderByDesc('xp')
            ->take(self::LEADERBOARD_LIMIT)
            ->get();

        $streakLeaderboard = $streakUsers->map(function ($u, $index) use ($currentUser) {
            $levelInfo = $this->gamification->levelInfo((int) ($u->xp ?? 0));

            return [
                'rank' => $index + 1,
                'user_id' => $u->id,
                'name' => $u->name,
                'score' => (int) ($u->current_streak ?? 0),
                'score_label' => (int) ($u->current_streak ?? 0) . ' Hari',
                'level' => $levelInfo['level'],
                'title' => $levelInfo['title'],
                'streak' => (int) ($u->current_streak ?? 0),
                'is_current_user' => ($u->id === $currentUser->id),
            ];
        });

        $currentUserStreak = (int) ($currentUser->current_streak ?? 0);
        $currentUserStreakRank = User::query()
            ->where('current_streak', '>', $currentUserStreak)
            ->count() + 1;

        // 3. Hall of Fame / Sepanjang Masa (Total XP Kumulatif)
        $allTimeUsers = User::query()
            ->orderByDesc('xp')
            ->orderByDesc('current_streak')
            ->take(self::LEADERBOARD_LIMIT)
            ->get();

        $allTimeLeaderboard = $allTimeUsers->map(function ($u, $index) use ($currentUser) {
            $levelInfo = $this->gamification->levelInfo((int) ($u->xp ?? 0));

            return [
                'rank' => $index + 1,
                'user_id' => $u->id,
                'name' => $u->name,
                'score' => (int) ($u->xp ?? 0),
                'score_label' => number_format((int) ($u->xp ?? 0)) . ' XP',
                'level' => $levelInfo['level'],
                'title' => $levelInfo['title'],
                'streak' => (int) ($u->current_streak ?? 0),
                'is_current_user' => ($u->id === $currentUser->id),
            ];
        });

        $currentUserXp = (int) ($currentUser->xp ?? 0);
        $currentUserAllTimeRank = User::query()
            ->where('xp', '>', $currentUserXp)
            ->count() + 1;

        $userLevelInfo = $this->gamification->levelInfo($currentUserXp);

        $currentUserStats = [
            'weekly' => [
                'rank' => $currentUserWeeklyRank,
                'score' => $currentUserWeeklyXp,
                'score_label' => number_format($currentUserWeeklyXp) . ' XP',
                'in_top_10' => $weeklyLeaderboard->contains('user_id', $currentUser->id),
            ],
            'streak' => [
                'rank' => $currentUserStreakRank,
                'score' => $currentUserStreak,
                'score_label' => $currentUserStreak . ' Hari',
                'in_top_10' => $streakLeaderboard->contains('user_id', $currentUser->id),
            ],
            'all_time' => [
                'rank' => $currentUserAllTimeRank,
                'score' => $currentUserXp,
                'score_label' => number_format($currentUserXp) . ' XP',
                'in_top_10' => $allTimeLeaderboard->contains('user_id', $currentUser->id),
            ],
            'level' => $userLevelInfo['level'],
            'title' => $userLevelInfo['title'],
        ];

        return view('dashboard.leaderboard.index', [
            'weeklyLeaderboard' => $weeklyLeaderboard,
            'streakLeaderboard' => $streakLeaderboard,
            'allTimeLeaderboard' => $allTimeLeaderboard,
            'currentUserStats' => $currentUserStats,
        ]);
    }
}
