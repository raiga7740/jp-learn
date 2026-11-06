<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WritingPracticeController extends Controller
{
    /**
     * Tampilkan halaman latihan menulis huruf interaktif (Hiragana, Katakana, dan Kanji).
     */
    public function index(Request $request): View
    {
        $user = Auth::user();

        // 1. Ambil karakter Hiragana
        $hiraganaMat   = Material::where('slug', 'hiragana-dasar')->first();
        $hiraganaChars = [];

        if ($hiraganaMat && isset($hiraganaMat->content['sections']) && is_array($hiraganaMat->content['sections'])) {
            foreach ($hiraganaMat->content['sections'] as $sec) {
                $secTitle = $sec['title'] ?? 'Hiragana';
                foreach ($sec['characters'] ?? [] as $c) {
                    $hiraganaChars[] = [
                        'char'     => $c['kana'],
                        'romaji'   => $c['romaji'],
                        'group'    => $secTitle,
                        'type'     => 'hiragana',
                        'meaning'  => 'Bunyi Romaji: ' . $c['romaji'],
                    ];
                }
            }
        }

        // 2. Ambil karakter Katakana
        $katakanaMat   = Material::where('slug', 'katakana-dasar')->first();
        $katakanaChars = [];

        if ($katakanaMat && isset($katakanaMat->content['sections']) && is_array($katakanaMat->content['sections'])) {
            foreach ($katakanaMat->content['sections'] as $sec) {
                $secTitle = $sec['title'] ?? 'Katakana';
                foreach ($sec['characters'] ?? [] as $c) {
                    $katakanaChars[] = [
                        'char'     => $c['kana'],
                        'romaji'   => $c['romaji'],
                        'group'    => $secTitle,
                        'type'     => 'katakana',
                        'meaning'  => 'Bunyi Romaji: ' . $c['romaji'],
                    ];
                }
            }
        }

        // 3. Ambil karakter Kanji N5
        $kanjiMat   = Material::where('slug', 'kanji-n5')->first();
        $kanjiChars = [];

        if ($kanjiMat && isset($kanjiMat->content['kanji']) && is_array($kanjiMat->content['kanji'])) {
            foreach ($kanjiMat->content['kanji'] as $k) {
                $kanjiChars[] = [
                    'char'     => $k['kanji'],
                    'romaji'   => 'Kun: ' . ($k['kun'] ?? '-') . ' • On: ' . ($k['on'] ?? '-'),
                    'group'    => $k['category'] ?? 'Kanji N5',
                    'type'     => 'kanji',
                    'meaning'  => 'Arti: ' . ($k['meaning'] ?? '-'),
                ];
            }
        }

        return view('dashboard.writing.index', [
            'user'          => $user,
            'hiraganaChars' => $hiraganaChars,
            'katakanaChars' => $katakanaChars,
            'kanjiChars'    => $kanjiChars,
        ]);
    }
}

