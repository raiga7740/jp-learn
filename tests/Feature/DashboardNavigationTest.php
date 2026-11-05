<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardNavigationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_learning_pages(): void
    {
        $this->get(route('dashboard.materials'))->assertRedirect(route('login'));
        $this->get(route('dashboard.quizzes'))->assertRedirect(route('login'));
        $this->get(route('dashboard.progress'))->assertRedirect(route('login'));
    }

    public function test_dashboard_renders_links_to_separate_learning_pages(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Materi Belajar')
            ->assertSee('mobile-nav', false)
            ->assertSee(route('dashboard.materials'), false)
            ->assertSee(route('dashboard.quizzes'), false)
            ->assertSee(route('dashboard.progress'), false);
    }

    public function test_authenticated_user_can_open_each_learning_page(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('dashboard.materials'))->assertOk()->assertSee('Daftar Materi');
        $this->actingAs($user)->get(route('dashboard.quizzes'))->assertOk()->assertSee('Quiz Tersedia');
        $this->actingAs($user)->get(route('dashboard.progress'))->assertOk()->assertSee('Progres per Materi');
    }
}
