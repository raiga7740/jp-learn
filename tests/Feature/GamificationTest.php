<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\GamificationService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GamificationTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    private function makeUser(): User
    {
        return User::create([
            'name' => 'Pelajar',
            'email' => 'pelajar-' . uniqid() . '@example.test',
            'password' => 'rahasia-123',
        ]);
    }

    public function test_first_activity_starts_streak_and_awards_xp(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-11-10 08:00:00', GamificationService::TIMEZONE));
        $user = $this->makeUser();

        $result = app(GamificationService::class)->award($user, 'flashcard_session');

        $this->assertSame(15, $result['xp_gained']);
        $this->assertSame(1, $result['streak']);
        $this->assertTrue($result['streak_extended']);
    }

    public function test_consecutive_days_extend_streak_and_gap_resets_it(): void
    {
        $service = app(GamificationService::class);
        $user = $this->makeUser();

        Carbon::setTestNow(Carbon::parse('2026-11-10 08:00:00', GamificationService::TIMEZONE));
        $service->award($user, 'writing_practice');

        Carbon::setTestNow(Carbon::parse('2026-11-11 08:00:00', GamificationService::TIMEZONE));
        $this->assertSame(2, $service->award($user, 'writing_practice')['streak']);

        Carbon::setTestNow(Carbon::parse('2026-11-14 08:00:00', GamificationService::TIMEZONE));
        $this->assertSame(0, $service->stats($user->fresh())['streak']);
        $this->assertSame(1, $service->award($user, 'writing_practice')['streak']);
        $this->assertSame(2, $user->fresh()->longest_streak);
    }

    public function test_daily_limit_stops_xp_but_keeps_activity(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-11-10 08:00:00', GamificationService::TIMEZONE));
        $service = app(GamificationService::class);
        $user = $this->makeUser();

        $total = 0;
        for ($i = 0; $i < 12; $i++) {
            $total += $service->award($user, 'writing_practice')['xp_gained'];
        }

        $this->assertSame(50, $total);
    }

    public function test_activity_endpoint_only_accepts_client_actions(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)
            ->postJson(route('activity.store'), ['action' => 'flashcard_session'])
            ->assertOk()
            ->assertJsonPath('xp_gained', 15);

        $this->actingAs($user)
            ->postJson(route('activity.store'), ['action' => 'quiz_complete'])
            ->assertStatus(422);
    }

    public function test_dashboard_and_progress_show_streak_and_heatmap(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)->get(route('dashboard'))
            ->assertOk()
            ->assertSee('gami-card', false);

        $this->actingAs($user)->get(route('dashboard.progress'))
            ->assertOk()
            ->assertSee('Aktivitas Belajar');
    }
}

