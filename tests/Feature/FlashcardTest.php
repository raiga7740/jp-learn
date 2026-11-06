<?php

namespace Tests\Feature;

use App\Models\Material;
use App\Models\User;
use Database\Seeders\JapaneseLearningSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FlashcardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(JapaneseLearningSeeder::class);
    }

    public function test_guest_is_redirected_to_login_from_flashcards(): void
    {
        $this->get(route('flashcards.index'))->assertRedirect(route('login'));

        $material = Material::first();
        $this->get(route('flashcards.show', $material->slug))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_flashcard_deck_hub(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('flashcards.index'));

        $response->assertOk();
        $response->assertSee('Mode Belajar Flashcard');
        $response->assertSee('Pilih Deck Flashcard');
        $response->assertSee('Hiragana Dasar');
        $response->assertSee('Katakana Dasar');
        $response->assertSee('Kanji Dasar (N5)');
        $response->assertSee('Kosakata Sehari-hari (N5)');
        $response->assertSee('Pola Kalimat Dasar (N5)');
    }

    public function test_authenticated_user_can_open_flashcard_player_for_each_deck(): void
    {
        $user = User::factory()->create();
        $materials = Material::all();

        $this->assertNotEmpty($materials);

        foreach ($materials as $material) {
            $response = $this->actingAs($user)->get(route('flashcards.show', $material->slug));

            $response->assertOk();
            $response->assertSee($material->title);
            $response->assertSee('Balik Kartu (Spasi)');
            $response->assertSee('Sudah Hafal');
            $response->assertSee('Belum Hafal');
            $response->assertViewHas('cards');

            $cards = $response->viewData('cards');
            $this->assertIsArray($cards);
            $this->assertNotEmpty($cards, "Material {$material->slug} should produce non-empty flashcards.");

            // Check card structure
            $firstCard = $cards[0];
            $this->assertArrayHasKey('front', $firstCard);
            $this->assertArrayHasKey('back_main', $firstCard);
            $this->assertArrayHasKey('audio', $firstCard);
        }
    }
}

