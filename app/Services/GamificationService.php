<?php

namespace App\Services;

use App\Models\DailyActivity;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class GamificationService
{
    /**
     * Zona waktu acuan pergantian hari untuk streak (bukan UTC bawaan aplikasi).
     */
    public const TIMEZONE = 'Asia/Jakarta';

    /**
     * Aksi yang memberi XP beserta batas harian agar tidak bisa di-spam.
     *
     * @var array<string, array{xp: int, daily_limit: int, label: string}>
     */
    public const ACTIONS = [
        'material_complete'     => ['xp' => 30, 'daily_limit' => 5,  'label' => 'Menyelesaikan materi'],
        'quiz_complete'         => ['xp' => 10, 'daily_limit' => 10, 'label' => 'Menyelesaikan quiz'],
        'flashcard_session'     => ['xp' => 15, 'daily_limit' => 5,  'label' => 'Sesi flashcard selesai'],
        'writing_practice'      => ['xp' => 5,  'daily_limit' => 10, 'label' => 'Latihan menulis huruf'],
        'conversation_complete' => ['xp' => 15, 'daily_limit' => 4,  'label' => 'Percakapan selesai'],
        'vocab_saved'           => ['xp' => 5,  'daily_limit' => 10, 'label' => 'Menyimpan kosakata favorit'],
        'jlpt_mock_complete'    => ['xp' => 50, 'daily_limit' => 2,  'label' => 'Menyelesaikan Simulasi Ujian JLPT N5'],
        'speed_match_complete'       => ['xp' => 15, 'daily_limit' => 10, 'label' => 'Bermain Kana Speed Match'],
        'sentence_builder_complete'  => ['xp' => 20, 'daily_limit' => 8,  'label' => 'Menyelesaikan Game Susun Kalimat'],
        'tanya_sensei_ask'           => ['xp' => 10, 'daily_limit' => 5,  'label' => 'Bertanya pada Tanya Sensei AI'],
    ];

    /**
     * Aksi yang boleh dilaporkan oleh browser (sisanya dihitung di server).
     *
     * @var array<int, string>
     */
    public const CLIENT_ACTIONS = ['flashcard_session', 'writing_practice', 'conversation_complete', 'vocab_saved', 'speed_match_complete', 'sentence_builder_complete', 'tanya_sensei_ask'];

    /**
     * Batas minimal XP per level.
     *
     * @var array<int, int>
     */
    private const LEVEL_THRESHOLDS = [
        1 => 0,
        2 => 100,
        3 => 250,
        4 => 500,
        5 => 900,
        6 => 1400,
        7 => 2000,
        8 => 2800,
        9 => 3800,
        10 => 5000,
    ];

    /**
     * Beri XP untuk sebuah aksi sekaligus mencatat aktivitas hari ini (streak & heatmap).
     *
     * @return array{action: string, label: string, xp_gained: int, total_xp: int, streak: int, streak_extended: bool, level: int, title: string, leveled_up: bool, limit_reached: bool}
     */
    public function award(User $user, string $action, ?int $xp = null): array
    {
        $config = self::ACTIONS[$action] ?? null;

        if ($config === null) {
            throw new InvalidArgumentException("Aksi XP tidak dikenal: {$action}");
        }

        $baseXp = max(0, $xp ?? $config['xp']);

        return DB::transaction(function () use ($user, $action, $config, $baseXp): array {
            $lockedUser = User::query()->lockForUpdate()->findOrFail($user->getKey());
            $today = $this->todayString();

            $activity = DailyActivity::query()->lockForUpdate()->firstOrCreate(
                ['user_id' => $lockedUser->id, 'activity_date' => $today],
                ['xp_earned' => 0, 'actions' => []],
            );

            $counts = $activity->actions ?? [];
            $doneToday = (int) ($counts[$action] ?? 0);
            $limitReached = $doneToday >= $config['daily_limit'];
            $gained = $limitReached ? 0 : $baseXp;

            $levelBefore = $this->levelInfo($lockedUser->xp)['level'];
            $streakExtended = $this->registerStreak($lockedUser, $today);

            $lockedUser->xp += $gained;
            $lockedUser->save();

            if (! $limitReached) {
                $counts[$action] = $doneToday + 1;
                $activity->xp_earned += $gained;
                $activity->actions = $counts;
                $activity->save();
            }

            $levelAfter = $this->levelInfo($lockedUser->xp);

            return [
                'action'          => $action,
                'label'           => $config['label'],
                'xp_gained'       => $gained,
                'total_xp'        => $lockedUser->xp,
                'streak'          => $lockedUser->current_streak,
                'streak_extended' => $streakExtended,
                'level'           => $levelAfter['level'],
                'title'           => $levelAfter['title'],
                'leveled_up'      => $levelAfter['level'] > $levelBefore,
                'limit_reached'   => $limitReached,
            ];
        });
    }

    /**
     * Ringkasan XP, level, streak, dan aktivitas 7 hari terakhir.
     *
     * @return array<string, mixed>
     */
    public function stats(User $user): array
    {
        $today = $this->todayString();
        $yesterday = $this->now()->subDay()->toDateString();
        $last = $user->last_activity_date?->toDateString();

        $streakIsAlive = $last === $today || $last === $yesterday;
        $weekStart = $this->now()->subDays(6)->toDateString();

        $recent = DailyActivity::query()
            ->where('user_id', $user->id)
            ->whereDate('activity_date', '>=', $weekStart)
            ->get()
            ->keyBy(fn (DailyActivity $row): string => $row->activity_date->toDateString());

        $dayLabels = [1 => 'Sen', 2 => 'Sel', 3 => 'Rab', 4 => 'Kam', 5 => 'Jum', 6 => 'Sab', 7 => 'Min'];
        $week = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = $this->now()->subDays($i);
            $dateString = $date->toDateString();
            $week[] = [
                'label'    => $dayLabels[$date->isoWeekday()],
                'date'     => $dateString,
                'active'   => $recent->has($dateString),
                'is_today' => $dateString === $today,
            ];
        }

        return [
            ...$this->levelInfo($user->xp),
            'xp'             => $user->xp,
            'streak'         => $streakIsAlive ? $user->current_streak : 0,
            'longest_streak' => max($user->longest_streak, $streakIsAlive ? $user->current_streak : 0),
            'active_today'   => $last === $today,
            'today_xp'       => (int) ($recent->get($today)?->xp_earned ?? 0),
            'week'           => $week,
        ];
    }

    /**
     * Data heatmap aktivitas belajar (kolom per minggu, Senin–Minggu).
     *
     * @return array{weeks: array<int, array{label: string|null, days: array<int, array<string, mixed>>}>, active_days: int, total_xp: int}
     */
    public function heatmap(User $user, int $weeks = 16): array
    {
        $today = $this->now()->startOfDay();
        $start = $today->copy()->startOfWeek(Carbon::MONDAY)->subWeeks($weeks - 1);

        $rows = DailyActivity::query()
            ->where('user_id', $user->id)
            ->whereDate('activity_date', '>=', $start->toDateString())
            ->get()
            ->keyBy(fn (DailyActivity $row): string => $row->activity_date->toDateString());

        $columns = [];
        $activeDays = 0;
        $totalXp = 0;
        $previousMonth = null;

        for ($week = 0; $week < $weeks; $week++) {
            $days = [];
            $weekStart = $start->copy()->addWeeks($week);

            for ($day = 0; $day < 7; $day++) {
                $date = $weekStart->copy()->addDays($day);
                $dateString = $date->toDateString();
                $xp = (int) ($rows->get($dateString)?->xp_earned ?? 0);
                $isFuture = $date->greaterThan($today);

                if (! $isFuture && $rows->has($dateString)) {
                    $activeDays++;
                    $totalXp += $xp;
                }

                $days[] = [
                    'date'      => $dateString,
                    'xp'        => $xp,
                    'intensity' => $isFuture ? 0 : $this->intensity($xp, $rows->has($dateString)),
                    'future'    => $isFuture,
                ];
            }

            $month = $weekStart->locale('id')->translatedFormat('M');
            $columns[] = [
                'label' => $month !== $previousMonth ? $month : null,
                'days'  => $days,
            ];
            $previousMonth = $month;
        }

        return ['weeks' => $columns, 'active_days' => $activeDays, 'total_xp' => $totalXp];
    }

    /**
     * Hitung level, gelar, dan progres menuju level berikutnya dari total XP.
     *
     * @return array{level: int, title: string, level_floor: int, next_level_xp: int|null, xp_to_next: int, level_progress: int}
     */
    public function levelInfo(int $xp): array
    {
        $level = 1;

        foreach (self::LEVEL_THRESHOLDS as $candidate => $threshold) {
            if ($xp >= $threshold) {
                $level = $candidate;
            }
        }

        $floor = self::LEVEL_THRESHOLDS[$level];
        $next = self::LEVEL_THRESHOLDS[$level + 1] ?? null;

        return [
            'level'          => $level,
            'title'          => $this->titleForLevel($level),
            'level_floor'    => $floor,
            'next_level_xp'  => $next,
            'xp_to_next'     => $next === null ? 0 : $next - $xp,
            'level_progress' => $next === null ? 100 : (int) floor((($xp - $floor) / ($next - $floor)) * 100),
        ];
    }

    private function titleForLevel(int $level): string
    {
        return match (true) {
            $level >= 10 => 'Meijin (名人) · Master',
            $level >= 7  => 'Sensei (先生) · Mahir',
            $level >= 4  => 'Senpai (先輩) · Menengah',
            default      => 'Kouhai (後輩) · Pemula',
        };
    }

    private function intensity(int $xp, bool $hasActivity): int
    {
        if (! $hasActivity) {
            return 0;
        }

        return match (true) {
            $xp > 100 => 4,
            $xp > 50  => 3,
            $xp > 20  => 2,
            default   => 1,
        };
    }

    /**
     * Perbarui streak harian. Mengembalikan true bila hari ini baru pertama kali tercatat.
     */
    private function registerStreak(User $user, string $today): bool
    {
        $last = $user->last_activity_date?->toDateString();

        if ($last === $today) {
            return false;
        }

        $yesterday = Carbon::parse($today, self::TIMEZONE)->subDay()->toDateString();

        $user->current_streak = $last === $yesterday ? $user->current_streak + 1 : 1;
        $user->longest_streak = max($user->longest_streak, $user->current_streak);
        $user->last_activity_date = $today;

        return true;
    }

    private function now(): Carbon
    {
        return Carbon::now(self::TIMEZONE);
    }

    private function todayString(): string
    {
        return $this->now()->toDateString();
    }
}

