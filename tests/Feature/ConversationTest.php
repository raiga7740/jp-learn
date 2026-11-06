<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\JapaneseLearningSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConversationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(JapaneseLearningSeeder::class);
    }

    public function test_guest_is_redirected_to_login_from_conversations(): void
    {
        $this->get(route('conversation.index'))->assertRedirect(route('login'));
        $this->get(route('conversation.show', 'perkenalan-diri'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_conversation_topics(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('conversation.index'));

        $response->assertOk();
        $response->assertSee('Percakapan Situasional');
        $response->assertSee('Perkenalan Diri');
        $response->assertSee('Memesan Makanan di Restoran');
        $response->assertSee('Belanja di Minimarket');
        $response->assertSee('Menanyakan Arah & Stasiun Kereta');
        $response->assertViewHas('scenarios');
    }

    public function test_authenticated_user_can_open_conversation_simulation(): void
    {
        $user = User::factory()->create();

        $scenarios = ['perkenalan-diri', 'restoran-jepang', 'konbini', 'tanya-arah'];

        foreach ($scenarios as $slug) {
            $response = $this->actingAs($user)->get(route('conversation.show', $slug));

            $response->assertOk();
            $response->assertSee('chatBox');
            $response->assertSee('bubble-japanese');
            $response->assertViewHas('scenario');

            $data = $response->viewData('scenario');
            $this->assertEquals($slug, $data['slug']);
            $this->assertNotEmpty($data['dialogues']);
        }
    }
}

