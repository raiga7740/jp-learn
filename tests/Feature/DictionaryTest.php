<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserVocabulary;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DictionaryTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(): User
    {
        return User::create([
            'name'     => 'Pelajar Kamus',
            'email'    => 'pelajar-' . uniqid() . '@example.test',
            'password' => 'rahasia-123',
        ]);
    }

    public function test_guest_is_redirected_to_login_from_dictionary(): void
    {
        $this->get(route('dictionary.index'))->assertRedirect(route('login'));
        $this->get(route('dictionary.flashcards'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_dictionary(): void
    {
        $user = $this->makeUser();

        $response = $this->actingAs($user)->get(route('dictionary.index'));

        $response->assertOk();
        $response->assertSee('Kamus & Buku Kosakata');
        $response->assertSee('Kamus Lengkap');
        $response->assertSee('Buku Kosakata Favorit');
    }

    public function test_user_can_bookmark_and_unbookmark_vocabulary(): void
    {
        $user = $this->makeUser();

        // 1. Bookmark ON
        $response = $this->actingAs($user)->postJson(route('dictionary.bookmark'), [
            'word_key'   => 'dict_test_1',
            'japanese'   => '食べる',
            'reading'    => 'たべる',
            'romaji'     => 'taberu',
            'meaning'    => 'Makan',
            'category'   => 'Kata Kerja Dasar',
            'example_ja' => 'ごはんを たべます。',
            'example_id' => 'Makan nasi.',
        ]);

        $response->assertOk();
        $response->assertJson([
            'status'      => 'saved',
            'is_favorite' => true,
        ]);

        $this->assertDatabaseHas('user_vocabularies', [
            'user_id'  => $user->id,
            'word_key' => 'dict_test_1',
            'japanese' => '食べる',
        ]);

        // 2. Bookmark OFF (Toggle)
        $responseOff = $this->actingAs($user)->postJson(route('dictionary.bookmark'), [
            'word_key'   => 'dict_test_1',
            'japanese'   => '食べる',
            'reading'    => 'たべる',
            'romaji'     => 'taberu',
            'meaning'    => 'Makan',
        ]);

        $responseOff->assertOk();
        $responseOff->assertJson([
            'status'      => 'removed',
            'is_favorite' => false,
        ]);

        $this->assertDatabaseMissing('user_vocabularies', [
            'user_id'  => $user->id,
            'word_key' => 'dict_test_1',
        ]);
    }

    public function test_user_can_add_custom_word_and_update_notes(): void
    {
        $user = $this->makeUser();

        // Tambah kata custom
        $response = $this->actingAs($user)->postJson(route('dictionary.custom-word'), [
            'japanese'   => '本 (ほん)',
            'reading'    => 'ほん',
            'romaji'     => 'hon',
            'meaning'    => 'Buku',
            'category'   => 'Benda & Kehidupan',
            'notes'      => 'Tips: mirip kata bone tapi diganti hon.',
        ]);

        $response->assertOk();
        $response->assertJson(['status' => 'ok']);

        $vocab = UserVocabulary::where('user_id', $user->id)->first();
        $this->assertNotNull($vocab);
        $this->assertSame('本 (ほん)', $vocab->japanese);

        // Update notes
        $updateResp = $this->actingAs($user)->postJson(route('dictionary.update-notes', $vocab->id), [
            'notes' => 'Catatan baru diperbarui.',
        ]);

        $updateResp->assertOk();
        $this->assertSame('Catatan baru diperbarui.', $vocab->fresh()->notes);
    }

    public function test_user_can_open_favorite_flashcards(): void
    {
        $user = $this->makeUser();

        UserVocabulary::create([
            'user_id'    => $user->id,
            'word_key'   => 'custom_flashcard_test',
            'japanese'   => '水 (みず)',
            'reading'    => 'みず',
            'romaji'     => 'mizu',
            'meaning'    => 'Air putih',
            'category'   => 'Makanan & Minuman',
            'is_favorite'=> true,
        ]);

        $response = $this->actingAs($user)->get(route('dictionary.flashcards'));

        $response->assertOk();
        $response->assertSee('Flashcard Kosakata Favorit');
        $response->assertSee('水 (みず)');
        $response->assertSee('Air putih');
    }
}

