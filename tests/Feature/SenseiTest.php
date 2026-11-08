<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SenseiTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_tanya_sensei(): void
    {
        $this->get(route('sensei.index'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_tanya_sensei_page(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('sensei.index'))
            ->assertOk()
            ->assertSee('Kenji Sensei')
            ->assertSee('Tanya Sensei')
            ->assertSee('Pengaturan API');
    }

    public function test_authenticated_user_can_send_message_and_receive_reply(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson(route('sensei.chat'), [
                'message' => 'Apa beda partikel wa dan ga?',
            ]);

        $response->assertOk()
            ->assertJsonStructure([
                'status',
                'reply',
                'engine',
                'japanese_snippets',
            ])
            ->assertJson([
                'status' => 'success',
            ]);

        $this->assertStringContainsString('Partikel は (WA)', $response->json('reply'));
    }

    public function test_chat_requires_message(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->postJson(route('sensei.chat'), [
                'message' => '',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['message']);
    }
}

