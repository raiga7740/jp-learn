<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SpeedMatchController extends Controller
{
    /**
     * Tampilkan arena permainan Kana Speed Match.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();

        // 1. Ambil data Hiragana dari materi atau fallback
        $hiraganaMat = Material::where('slug', 'hiragana-dasar')->first();
        $hiraganaList = [];

        if ($hiraganaMat && isset($hiraganaMat->content['sections']) && is_array($hiraganaMat->content['sections'])) {
            foreach ($hiraganaMat->content['sections'] as $sec) {
                foreach ($sec['characters'] ?? [] as $c) {
                    if (!empty($c['kana']) && !empty($c['romaji'])) {
                        $hiraganaList[] = [
                            'char'   => $c['kana'],
                            'answer' => strtolower($c['romaji']),
                            'type'   => 'Hiragana',
                            'hint'   => $c['romaji'],
                        ];
                    }
                }
            }
        }

        // Fallback default Hiragana 46 Gojūon jika materi belum diisi
        if (empty($hiraganaList)) {
            $defaultHira = [
                ['char' => 'あ', 'answer' => 'a'], ['char' => 'い', 'answer' => 'i'], ['char' => 'う', 'answer' => 'u'], ['char' => 'え', 'answer' => 'e'], ['char' => 'お', 'answer' => 'o'],
                ['char' => 'か', 'answer' => 'ka'], ['char' => 'き', 'answer' => 'ki'], ['char' => 'く', 'answer' => 'ku'], ['char' => 'け', 'answer' => 'ke'], ['char' => 'こ', 'answer' => 'ko'],
                ['char' => 'さ', 'answer' => 'sa'], ['char' => 'し', 'answer' => 'shi'], ['char' => 'す', 'answer' => 'su'], ['char' => 'せ', 'answer' => 'se'], ['char' => 'そ', 'answer' => 'so'],
                ['char' => 'た', 'answer' => 'ta'], ['char' => 'ち', 'answer' => 'chi'], ['char' => 'つ', 'answer' => 'tsu'], ['char' => 'て', 'answer' => 'te'], ['char' => 'と', 'answer' => 'to'],
                ['char' => 'な', 'answer' => 'na'], ['char' => 'に', 'answer' => 'ni'], ['char' => 'ぬ', 'answer' => 'nu'], ['char' => 'ね', 'answer' => 'ne'], ['char' => 'の', 'answer' => 'no'],
                ['char' => 'は', 'answer' => 'ha'], ['char' => 'ひ', 'answer' => 'hi'], ['char' => 'ふ', 'answer' => 'fu'], ['char' => 'へ', 'answer' => 'he'], ['char' => 'ほ', 'answer' => 'ho'],
                ['char' => 'ま', 'answer' => 'ma'], ['char' => 'み', 'answer' => 'mi'], ['char' => 'む', 'answer' => 'mu'], ['char' => 'め', 'answer' => 'me'], ['char' => 'も', 'answer' => 'mo'],
                ['char' => 'や', 'answer' => 'ya'], ['char' => 'ゆ', 'answer' => 'yu'], ['char' => 'よ', 'answer' => 'yo'],
                ['char' => 'ら', 'answer' => 'ra'], ['char' => 'り', 'answer' => 'ri'], ['char' => 'る', 'answer' => 'ru'], ['char' => 'れ', 'answer' => 're'], ['char' => 'ろ', 'answer' => 'ro'],
                ['char' => 'わ', 'answer' => 'wa'], ['char' => 'を', 'answer' => 'wo'], ['char' => 'ん', 'answer' => 'n'],
                ['char' => 'が', 'answer' => 'ga'], ['char' => 'ぎ', 'answer' => 'gi'], ['char' => 'ぐ', 'answer' => 'gu'], ['char' => 'げ', 'answer' => 'ge'], ['char' => 'ご', 'answer' => 'go'],
                ['char' => 'ざ', 'answer' => 'za'], ['char' => 'じ', 'answer' => 'ji'], ['char' => 'ず', 'answer' => 'zu'], ['char' => 'ぜ', 'answer' => 'ze'], ['char' => 'ぞ', 'answer' => 'zo'],
                ['char' => 'だ', 'answer' => 'da'], ['char' => 'ぢ', 'answer' => 'ji'], ['char' => 'づ', 'answer' => 'zu'], ['char' => 'で', 'answer' => 'de'], ['char' => 'ど', 'answer' => 'do'],
                ['char' => 'ば', 'answer' => 'ba'], ['char' => 'び', 'answer' => 'bi'], ['char' => 'ぶ', 'answer' => 'bu'], ['char' => 'べ', 'answer' => 'be'], ['char' => 'ぼ', 'answer' => 'bo'],
                ['char' => 'ぱ', 'answer' => 'pa'], ['char' => 'ぴ', 'answer' => 'pi'], ['char' => 'ぷ', 'answer' => 'pu'], ['char' => 'ぺ', 'answer' => 'pe'], ['char' => 'ぽ', 'answer' => 'po'],
            ];
            foreach ($defaultHira as $item) {
                $hiraganaList[] = [
                    'char'   => $item['char'],
                    'answer' => $item['answer'],
                    'type'   => 'Hiragana',
                    'hint'   => $item['answer'],
                ];
            }
        }

        // 2. Ambil data Katakana dari materi atau fallback
        $katakanaMat = Material::where('slug', 'katakana-dasar')->first();
        $katakanaList = [];

        if ($katakanaMat && isset($katakanaMat->content['sections']) && is_array($katakanaMat->content['sections'])) {
            foreach ($katakanaMat->content['sections'] as $sec) {
                foreach ($sec['characters'] ?? [] as $c) {
                    if (!empty($c['kana']) && !empty($c['romaji'])) {
                        $katakanaList[] = [
                            'char'   => $c['kana'],
                            'answer' => strtolower($c['romaji']),
                            'type'   => 'Katakana',
                            'hint'   => $c['romaji'],
                        ];
                    }
                }
            }
        }

        if (empty($katakanaList)) {
            $defaultKata = [
                ['char' => 'ア', 'answer' => 'a'], ['char' => 'イ', 'answer' => 'i'], ['char' => 'ウ', 'answer' => 'u'], ['char' => 'エ', 'answer' => 'e'], ['char' => 'オ', 'answer' => 'o'],
                ['char' => 'カ', 'answer' => 'ka'], ['char' => 'キ', 'answer' => 'ki'], ['char' => 'ク', 'answer' => 'ku'], ['char' => 'ケ', 'answer' => 'ke'], ['char' => 'コ', 'answer' => 'ko'],
                ['char' => 'サ', 'answer' => 'sa'], ['char' => 'シ', 'answer' => 'shi'], ['char' => 'ス', 'answer' => 'su'], ['char' => 'セ', 'answer' => 'se'], ['char' => 'ソ', 'answer' => 'so'],
                ['char' => 'タ', 'answer' => 'ta'], ['char' => 'チ', 'answer' => 'chi'], ['char' => 'ツ', 'answer' => 'tsu'], ['char' => 'テ', 'answer' => 'te'], ['char' => 'ト', 'answer' => 'to'],
                ['char' => 'ナ', 'answer' => 'na'], ['char' => 'ニ', 'answer' => 'ni'], ['char' => 'ヌ', 'answer' => 'nu'], ['char' => 'ネ', 'answer' => 'ne'], ['char' => 'ノ', 'answer' => 'no'],
                ['char' => 'ハ', 'answer' => 'ha'], ['char' => 'ヒ', 'answer' => 'hi'], ['char' => 'フ', 'answer' => 'fu'], ['char' => 'ヘ', 'answer' => 'he'], ['char' => 'ホ', 'answer' => 'ho'],
                ['char' => 'マ', 'answer' => 'ma'], ['char' => 'ミ', 'answer' => 'mi'], ['char' => 'ム', 'answer' => 'mu'], ['char' => 'メ', 'answer' => 'me'], ['char' => 'モ', 'answer' => 'mo'],
                ['char' => 'ヤ', 'answer' => 'ya'], ['char' => 'ユ', 'answer' => 'yu'], ['char' => 'ヨ', 'answer' => 'yo'],
                ['char' => 'ラ', 'answer' => 'ra'], ['char' => 'リ', 'answer' => 'ri'], ['char' => 'ル', 'answer' => 'ru'], ['char' => 'レ', 'answer' => 're'], ['char' => 'ロ', 'answer' => 'ro'],
                ['char' => 'ワ', 'answer' => 'wa'], ['char' => 'ヲ', 'answer' => 'wo'], ['char' => 'ン', 'answer' => 'n'],
                ['char' => 'ガ', 'answer' => 'ga'], ['char' => 'ギ', 'answer' => 'gi'], ['char' => 'グ', 'answer' => 'gu'], ['char' => 'ゲ', 'answer' => 'ge'], ['char' => 'ゴ', 'answer' => 'go'],
                ['char' => 'ザ', 'answer' => 'za'], ['char' => 'ジ', 'answer' => 'ji'], ['char' => 'ズ', 'answer' => 'zu'], ['char' => 'ゼ', 'answer' => 'ze'], ['char' => 'ゾ', 'answer' => 'zo'],
                ['char' => 'ダ', 'answer' => 'da'], ['char' => 'ヂ', 'answer' => 'ji'], ['char' => 'ヅ', 'answer' => 'zu'], ['char' => 'デ', 'answer' => 'de'], ['char' => 'ド', 'answer' => 'do'],
                ['char' => 'バ', 'answer' => 'ba'], ['char' => 'ビ', 'answer' => 'bi'], ['char' => 'ブ', 'answer' => 'bu'], ['char' => 'ベ', 'answer' => 'be'], ['char' => 'ボ', 'answer' => 'bo'],
                ['char' => 'パ', 'answer' => 'pa'], ['char' => 'ピ', 'answer' => 'pi'], ['char' => 'プ', 'answer' => 'pu'], ['char' => 'ペ', 'answer' => 'pe'], ['char' => 'ポ', 'answer' => 'po'],
            ];
            foreach ($defaultKata as $item) {
                $katakanaList[] = [
                    'char'   => $item['char'],
                    'answer' => $item['answer'],
                    'type'   => 'Katakana',
                    'hint'   => $item['answer'],
                ];
            }
        }

        // 3. Ambil data Kanji N5 (Kanji vs Arti Bahasa Indonesia)
        $kanjiMat = Material::where('slug', 'kanji-n5')->first();
        $kanjiList = [];

        if ($kanjiMat && isset($kanjiMat->content['kanji']) && is_array($kanjiMat->content['kanji'])) {
            foreach ($kanjiMat->content['kanji'] as $k) {
                if (!empty($k['kanji']) && !empty($k['meaning'])) {
                    $kanjiList[] = [
                        'char'   => $k['kanji'],
                        'answer' => $k['meaning'],
                        'type'   => 'Kanji N5',
                        'hint'   => 'Kun: ' . ($k['kun'] ?? '-') . ' • On: ' . ($k['on'] ?? '-'),
                    ];
                }
            }
        }

        if (empty($kanjiList)) {
            $defaultKanji = [
                ['char' => '一', 'answer' => 'satu'], ['char' => '二', 'answer' => 'dua'], ['char' => '三', 'answer' => 'tiga'],
                ['char' => '四', 'answer' => 'empat'], ['char' => '五', 'answer' => 'lima'], ['char' => '六', 'answer' => 'enam'],
                ['char' => '七', 'answer' => 'tujuh'], ['char' => '八', 'answer' => 'delapan'], ['char' => '九', 'answer' => 'sembilan'],
                ['char' => '十', 'answer' => 'sepuluh'], ['char' => '百', 'answer' => 'ratus'], ['char' => '千', 'answer' => 'ribu'],
                ['char' => '万', 'answer' => 'puluh ribu'], ['char' => '円', 'answer' => 'yen / uang'], ['char' => '日', 'answer' => 'matahari / hari'],
                ['char' => '月', 'answer' => 'bulan'], ['char' => '火', 'answer' => 'api'], ['char' => '水', 'answer' => 'air'],
                ['char' => '木', 'answer' => 'pohon / kayu'], ['char' => '金', 'answer' => 'emas / uang'], ['char' => '土', 'answer' => 'tanah'],
                ['char' => '山', 'answer' => 'gunung'], ['char' => '川', 'answer' => 'sungai'], ['char' => '人', 'answer' => 'orang'],
                ['char' => '男', 'answer' => 'laki-laki'], ['char' => '女', 'answer' => 'perempuan'], ['char' => '子', 'answer' => 'anak'],
                ['char' => '父', 'answer' => 'ayah'], ['char' => '母', 'answer' => 'ibu'], ['char' => '目', 'answer' => 'mata'],
                ['char' => '口', 'answer' => 'mulut'], ['char' => '手', 'answer' => 'tangan'], ['char' => '足', 'answer' => 'kaki'],
                ['char' => '上', 'answer' => 'atas'], ['char' => '下', 'answer' => 'bawah'], ['char' => '左', 'answer' => 'kiri'],
                ['char' => '右', 'answer' => 'kanan'], ['char' => '中', 'answer' => 'tengah / dalam'], ['char' => '大', 'answer' => 'besar'],
                ['char' => '小', 'answer' => 'kecil'], ['char' => '長', 'answer' => 'panjang'], ['char' => '白', 'answer' => 'putih'],
                ['char' => '赤', 'answer' => 'merah'], ['char' => '雨', 'answer' => 'hujan'], ['char' => '電', 'answer' => 'listrik'],
            ];
            foreach ($defaultKanji as $item) {
                $kanjiList[] = [
                    'char'   => $item['char'],
                    'answer' => $item['answer'],
                    'type'   => 'Kanji N5',
                    'hint'   => 'Arti: ' . $item['answer'],
                ];
            }
        }

        // 4. Mode Campuran Kana (Hiragana & Katakana)
        $mixedKanaList = array_merge($hiraganaList, $katakanaList);

        return view('dashboard.speed-match.index', [
            'user'          => $user,
            'hiraganaList'  => $hiraganaList,
            'katakanaList'  => $katakanaList,
            'kanjiList'     => $kanjiList,
            'mixedKanaList' => $mixedKanaList,
        ]);
    }
}

