<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\JapaneseLearningSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WritingPracticeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(JapaneseLearningSeeder::class);
    }

    public function test_guest_is_redirected_to_login_from_writing_practice(): void
    {
        $this->get(route('writing.index'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_writing_practice_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('writing.index'));

        $response->assertOk();
        $response->assertSee('Latihan Menulis Huruf');
        $response->assertSee('drawingCanvas');
        $response->assertSee('charWatermark');
        $response->assertViewHas('hiraganaChars');
        $response->assertViewHas('katakanaChars');
        $response->assertViewHas('kanjiChars');

        $hiragana = $response->viewData('hiraganaChars');
        $katakana = $response->viewData('katakanaChars');
        $kanji    = $response->viewData('kanjiChars');

        $this->assertNotEmpty($hiragana);
        $this->assertNotEmpty($katakana);
        $this->assertNotEmpty($kanji);

        $this->assertArrayHasKey('char', $hiragana[0]);
        $this->assertArrayHasKey('romaji', $hiragana[0]);
    }
}

