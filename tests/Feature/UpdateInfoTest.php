<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateInfoTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_access_updates_page(): void
    {
        $response = $this->get(route('updates.index'));

        $response->assertOk()
            ->assertSee('Info Pembaruan & Versi')
            ->assertSee('v2.4.0')
            ->assertSee('Pusat Catatan Rilis & Versi')
            ->assertSee('Roadmap Masa Depan');
    }

    public function test_authenticated_user_can_access_updates_page_with_dashboard_layout(): void
    {
        $user = User::factory()->create([
            'name' => 'Akira Tanaka',
        ]);

        $response = $this->actingAs($user)->get(route('updates.index'));

        $response->assertOk()
            ->assertSee('Akira Tanaka')
            ->assertSee('Info & Versi')
            ->assertSee('v2.4.0')
            ->assertSee('Simulasi Resmi Ujian JLPT N5');
    }

    public function test_updates_page_filters_by_category(): void
    {
        $response = $this->get(route('updates.index', ['kategori' => 'features']));

        $response->assertOk()
            ->assertSee('Fitur Baru Ditambahkan');
    }

    public function test_updates_page_searches_by_keyword(): void
    {
        $response = $this->get(route('updates.index', ['q' => 'JLPT']));

        $response->assertOk()
            ->assertSee('JLPT N5');
    }

    public function test_latest_release_api_returns_valid_json(): void
    {
        $response = $this->get(route('updates.latest'));

        $response->assertOk()
            ->assertJsonStructure([
                'status',
                'version',
                'name',
                'date',
                'latest' => [
                    'version',
                    'title',
                    'release_date',
                    'highlights',
                    'changes',
                ],
            ])
            ->assertJson([
                'status'  => 'success',
                'version' => 'v2.4.0',
            ]);
    }

    public function test_dashboard_renders_update_version_links_in_topbar_and_sidebar(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk()
            ->assertSee('Info & Versi')
            ->assertSee('v2.4.0')
            ->assertSee('openWhatsNewModal')
            ->assertSee(route('updates.index'));
    }

    public function test_landing_page_renders_update_version_links(): void
    {
        $response = $this->get(route('landing'));

        $response->assertOk()
            ->assertSee(route('updates.index'))
            ->assertSee('v2.4');
    }
}

