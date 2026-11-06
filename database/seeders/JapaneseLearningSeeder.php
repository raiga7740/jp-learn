<?php

namespace Database\Seeders;

use App\Models\Material;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Database\Seeder;

class JapaneseLearningSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. HIRAGANA DASAR (LENGKAP 46 GOJUON + DAKUON & HANDAKUON) ───────

        $hiragana = Material::updateOrCreate(
            ['slug' => 'hiragana-dasar'],
            [
                'title'       => 'Hiragana Dasar',
                'category'    => 'hiragana',
                'level'       => 'beginner',
                'description' => 'Pelajari seluruh 46 karakter Hiragana dasar (Gojūon) beserta variasi Dakuon & Handakuon untuk membaca teks asli Jepang.',
                'order'       => 1,
                'content'     => [
                    'sections' => [
                        [
                            'title'      => 'Baris A (Vokal Dasar)',
                            'characters' => [
                                ['kana' => 'あ', 'romaji' => 'a'],
                                ['kana' => 'い', 'romaji' => 'i'],
                                ['kana' => 'う', 'romaji' => 'u'],
                                ['kana' => 'え', 'romaji' => 'e'],
                                ['kana' => 'お', 'romaji' => 'o'],
                            ],
                        ],
                        [
                            'title'      => 'Baris Ka',
                            'characters' => [
                                ['kana' => 'か', 'romaji' => 'ka'],
                                ['kana' => 'き', 'romaji' => 'ki'],
                                ['kana' => 'く', 'romaji' => 'ku'],
                                ['kana' => 'け', 'romaji' => 'ke'],
                                ['kana' => 'こ', 'romaji' => 'ko'],
                            ],
                        ],
                        [
                            'title'      => 'Baris Sa',
                            'characters' => [
                                ['kana' => 'さ', 'romaji' => 'sa'],
                                ['kana' => 'し', 'romaji' => 'shi'],
                                ['kana' => 'す', 'romaji' => 'su'],
                                ['kana' => 'せ', 'romaji' => 'se'],
                                ['kana' => 'そ', 'romaji' => 'so'],
                            ],
                        ],
                        [
                            'title'      => 'Baris Ta',
                            'characters' => [
                                ['kana' => 'た', 'romaji' => 'ta'],
                                ['kana' => 'ち', 'romaji' => 'chi'],
                                ['kana' => 'つ', 'romaji' => 'tsu'],
                                ['kana' => 'て', 'romaji' => 'te'],
                                ['kana' => 'と', 'romaji' => 'to'],
                            ],
                        ],
                        [
                            'title'      => 'Baris Na',
                            'characters' => [
                                ['kana' => 'な', 'romaji' => 'na'],
                                ['kana' => 'に', 'romaji' => 'ni'],
                                ['kana' => 'ぬ', 'romaji' => 'nu'],
                                ['kana' => 'ね', 'romaji' => 'ne'],
                                ['kana' => 'の', 'romaji' => 'no'],
                            ],
                        ],
                        [
                            'title'      => 'Baris Ha',
                            'characters' => [
                                ['kana' => 'は', 'romaji' => 'ha'],
                                ['kana' => 'ひ', 'romaji' => 'hi'],
                                ['kana' => 'ふ', 'romaji' => 'fu'],
                                ['kana' => 'へ', 'romaji' => 'he'],
                                ['kana' => 'ほ', 'romaji' => 'ho'],
                            ],
                        ],
                        [
                            'title'      => 'Baris Ma',
                            'characters' => [
                                ['kana' => 'ま', 'romaji' => 'ma'],
                                ['kana' => 'み', 'romaji' => 'mi'],
                                ['kana' => 'む', 'romaji' => 'mu'],
                                ['kana' => 'め', 'romaji' => 'me'],
                                ['kana' => 'も', 'romaji' => 'mo'],
                            ],
                        ],
                        [
                            'title'      => 'Baris Ya',
                            'characters' => [
                                ['kana' => 'や', 'romaji' => 'ya'],
                                ['kana' => 'ゆ', 'romaji' => 'yu'],
                                ['kana' => 'よ', 'romaji' => 'yo'],
                            ],
                        ],
                        [
                            'title'      => 'Baris Ra',
                            'characters' => [
                                ['kana' => 'ら', 'romaji' => 'ra'],
                                ['kana' => 'り', 'romaji' => 'ri'],
                                ['kana' => 'る', 'romaji' => 'ru'],
                                ['kana' => 'れ', 'romaji' => 're'],
                                ['kana' => 'ろ', 'romaji' => 'ro'],
                            ],
                        ],
                        [
                            'title'      => 'Baris Wa & N',
                            'characters' => [
                                ['kana' => 'わ', 'romaji' => 'wa'],
                                ['kana' => 'を', 'romaji' => 'wo (o)'],
                                ['kana' => 'ん', 'romaji' => 'n'],
                            ],
                        ],
                        [
                            'title'      => 'Dakuon — Baris Ga & Za',
                            'characters' => [
                                ['kana' => 'が', 'romaji' => 'ga'],
                                ['kana' => 'ぎ', 'romaji' => 'gi'],
                                ['kana' => 'ぐ', 'romaji' => 'gu'],
                                ['kana' => 'げ', 'romaji' => 'ge'],
                                ['kana' => 'ご', 'romaji' => 'go'],
                                ['kana' => 'ざ', 'romaji' => 'za'],
                                ['kana' => 'じ', 'romaji' => 'ji'],
                                ['kana' => 'ず', 'romaji' => 'zu'],
                                ['kana' => 'ぜ', 'romaji' => 'ze'],
                                ['kana' => 'ぞ', 'romaji' => 'zo'],
                            ],
                        ],
                        [
                            'title'      => 'Dakuon — Baris Da & Ba',
                            'characters' => [
                                ['kana' => 'だ', 'romaji' => 'da'],
                                ['kana' => 'ぢ', 'romaji' => 'ji (dji)'],
                                ['kana' => 'づ', 'romaji' => 'zu (dzu)'],
                                ['kana' => 'で', 'romaji' => 'de'],
                                ['kana' => 'ど', 'romaji' => 'do'],
                                ['kana' => 'ば', 'romaji' => 'ba'],
                                ['kana' => 'び', 'romaji' => 'bi'],
                                ['kana' => 'ぶ', 'romaji' => 'bu'],
                                ['kana' => 'べ', 'romaji' => 'be'],
                                ['kana' => 'ぼ', 'romaji' => 'bo'],
                            ],
                        ],
                        [
                            'title'      => 'Handakuon — Baris Pa',
                            'characters' => [
                                ['kana' => 'ぱ', 'romaji' => 'pa'],
                                ['kana' => 'ぴ', 'romaji' => 'pi'],
                                ['kana' => 'ぷ', 'romaji' => 'pu'],
                                ['kana' => 'ぺ', 'romaji' => 'pe'],
                                ['kana' => 'ぽ', 'romaji' => 'po'],
                            ],
                        ],
                    ],
                ],
            ]
        );

        // ── 2. KATAKANA DASAR (LENGKAP 46 GOJUON + DAKUON & HANDAKUON) ───────

        $katakana = Material::updateOrCreate(
            ['slug' => 'katakana-dasar'],
            [
                'title'       => 'Katakana Dasar',
                'category'    => 'katakana',
                'level'       => 'beginner',
                'description' => 'Pelajari 46 karakter Katakana lengkap beserta Dakuon & Handakuon untuk kata serapan asing dan istilah modern.',
                'order'       => 2,
                'content'     => [
                    'sections' => [
                        [
                            'title'      => 'Baris A (Vokal Dasar)',
                            'characters' => [
                                ['kana' => 'ア', 'romaji' => 'a'],
                                ['kana' => 'イ', 'romaji' => 'i'],
                                ['kana' => 'ウ', 'romaji' => 'u'],
                                ['kana' => 'エ', 'romaji' => 'e'],
                                ['kana' => 'オ', 'romaji' => 'o'],
                            ],
                        ],
                        [
                            'title'      => 'Baris Ka',
                            'characters' => [
                                ['kana' => 'カ', 'romaji' => 'ka'],
                                ['kana' => 'キ', 'romaji' => 'ki'],
                                ['kana' => 'ク', 'romaji' => 'ku'],
                                ['kana' => 'ケ', 'romaji' => 'ke'],
                                ['kana' => 'コ', 'romaji' => 'ko'],
                            ],
                        ],
                        [
                            'title'      => 'Baris Sa',
                            'characters' => [
                                ['kana' => 'サ', 'romaji' => 'sa'],
                                ['kana' => 'シ', 'romaji' => 'shi'],
                                ['kana' => 'ス', 'romaji' => 'su'],
                                ['kana' => 'セ', 'romaji' => 'se'],
                                ['kana' => 'ソ', 'romaji' => 'so'],
                            ],
                        ],
                        [
                            'title'      => 'Baris Ta',
                            'characters' => [
                                ['kana' => 'タ', 'romaji' => 'ta'],
                                ['kana' => 'チ', 'romaji' => 'chi'],
                                ['kana' => 'ツ', 'romaji' => 'tsu'],
                                ['kana' => 'テ', 'romaji' => 'te'],
                                ['kana' => 'ト', 'romaji' => 'to'],
                            ],
                        ],
                        [
                            'title'      => 'Baris Na',
                            'characters' => [
                                ['kana' => 'ナ', 'romaji' => 'na'],
                                ['kana' => 'ニ', 'romaji' => 'ni'],
                                ['kana' => 'ヌ', 'romaji' => 'nu'],
                                ['kana' => 'ネ', 'romaji' => 'ne'],
                                ['kana' => 'ノ', 'romaji' => 'no'],
                            ],
                        ],
                        [
                            'title'      => 'Baris Ha',
                            'characters' => [
                                ['kana' => 'ハ', 'romaji' => 'ha'],
                                ['kana' => 'ヒ', 'romaji' => 'hi'],
                                ['kana' => 'フ', 'romaji' => 'fu'],
                                ['kana' => 'ヘ', 'romaji' => 'he'],
                                ['kana' => 'ホ', 'romaji' => 'ho'],
                            ],
                        ],
                        [
                            'title'      => 'Baris Ma',
                            'characters' => [
                                ['kana' => 'マ', 'romaji' => 'ma'],
                                ['kana' => 'ミ', 'romaji' => 'mi'],
                                ['kana' => 'ム', 'romaji' => 'mu'],
                                ['kana' => 'メ', 'romaji' => 'me'],
                                ['kana' => 'モ', 'romaji' => 'mo'],
                            ],
                        ],
                        [
                            'title'      => 'Baris Ya',
                            'characters' => [
                                ['kana' => 'ヤ', 'romaji' => 'ya'],
                                ['kana' => 'ユ', 'romaji' => 'yu'],
                                ['kana' => 'ヨ', 'romaji' => 'yo'],
                            ],
                        ],
                        [
                            'title'      => 'Baris Ra',
                            'characters' => [
                                ['kana' => 'ラ', 'romaji' => 'ra'],
                                ['kana' => 'リ', 'romaji' => 'ri'],
                                ['kana' => 'ル', 'romaji' => 'ru'],
                                ['kana' => 'レ', 'romaji' => 're'],
                                ['kana' => 'ロ', 'romaji' => 'ro'],
                            ],
                        ],
                        [
                            'title'      => 'Baris Wa & N',
                            'characters' => [
                                ['kana' => 'ワ', 'romaji' => 'wa'],
                                ['kana' => 'ヲ', 'romaji' => 'wo (o)'],
                                ['kana' => 'ン', 'romaji' => 'n'],
                            ],
                        ],
                        [
                            'title'      => 'Dakuon — Baris Ga & Za',
                            'characters' => [
                                ['kana' => 'ガ', 'romaji' => 'ga'],
                                ['kana' => 'ギ', 'romaji' => 'gi'],
                                ['kana' => 'グ', 'romaji' => 'gu'],
                                ['kana' => 'ゲ', 'romaji' => 'ge'],
                                ['kana' => 'ゴ', 'romaji' => 'go'],
                                ['kana' => 'ザ', 'romaji' => 'za'],
                                ['kana' => 'ジ', 'romaji' => 'ji'],
                                ['kana' => 'ズ', 'romaji' => 'zu'],
                                ['kana' => 'ゼ', 'romaji' => 'ze'],
                                ['kana' => 'ゾ', 'romaji' => 'zo'],
                            ],
                        ],
                        [
                            'title'      => 'Dakuon — Baris Da & Ba',
                            'characters' => [
                                ['kana' => 'ダ', 'romaji' => 'da'],
                                ['kana' => 'ヂ', 'romaji' => 'ji (dji)'],
                                ['kana' => 'ヅ', 'romaji' => 'zu (dzu)'],
                                ['kana' => 'デ', 'romaji' => 'de'],
                                ['kana' => 'ド', 'romaji' => 'do'],
                                ['kana' => 'バ', 'romaji' => 'ba'],
                                ['kana' => 'ビ', 'romaji' => 'bi'],
                                ['kana' => 'ブ', 'romaji' => 'bu'],
                                ['kana' => 'ベ', 'romaji' => 'be'],
                                ['kana' => 'ボ', 'romaji' => 'bo'],
                            ],
                        ],
                        [
                            'title'      => 'Handakuon — Baris Pa',
                            'characters' => [
                                ['kana' => 'パ', 'romaji' => 'pa'],
                                ['kana' => 'ピ', 'romaji' => 'pi'],
                                ['kana' => 'プ', 'romaji' => 'pu'],
                                ['kana' => 'ペ', 'romaji' => 'pe'],
                                ['kana' => 'ポ', 'romaji' => 'po'],
                            ],
                        ],
                    ],
                ],
            ]
        );

        // ── 3. KOSAKATA DASAR (36 KOSAKATA TERPILIH N5) ──────────────────────

        $vocab = Material::updateOrCreate(
            ['slug' => 'kosakata-sehari-hari'],
            [
                'title'       => 'Kosakata Sehari-hari',
                'category'    => 'vocabulary',
                'level'       => 'beginner',
                'description' => 'Kumpulan 36 kosakata esensial JLPT N5 terbagi dalam salam, orang, benda sehari-hari, waktu, dan kata kerja.',
                'order'       => 3,
                'content'     => [
                    'words' => [
                        // Salam & Ungkapan
                        ['japanese' => 'おはようございます', 'romaji' => 'ohayou gozaimasu', 'meaning' => 'Selamat pagi (sopan)', 'category' => 'Salam & Ungkapan'],
                        ['japanese' => 'こんにちは',         'romaji' => 'konnichiwa',        'meaning' => 'Selamat siang / Halo',  'category' => 'Salam & Ungkapan'],
                        ['japanese' => 'こんばんは',         'romaji' => 'konbanwa',          'meaning' => 'Selamat malam',         'category' => 'Salam & Ungkapan'],
                        ['japanese' => 'さようなら',         'romaji' => 'sayounara',          'meaning' => 'Selamat tinggal',       'category' => 'Salam & Ungkapan'],
                        ['japanese' => 'ありがとうございます', 'romaji' => 'arigatou gozaimasu', 'meaning' => 'Terima kasih banyak',   'category' => 'Salam & Ungkapan'],
                        ['japanese' => 'すみません',         'romaji' => 'sumimasen',          'meaning' => 'Permisi / Maaf',        'category' => 'Salam & Ungkapan'],
                        ['japanese' => 'ごめんなさい',       'romaji' => 'gomennasai',        'meaning' => 'Mohon maaf',            'category' => 'Salam & Ungkapan'],
                        ['japanese' => 'はい',               'romaji' => 'hai',               'meaning' => 'Ya / Benar',            'category' => 'Salam & Ungkapan'],
                        ['japanese' => 'いいえ',             'romaji' => 'iie',               'meaning' => 'Tidak / Bukan',         'category' => 'Salam & Ungkapan'],
                        ['japanese' => 'どうぞ',             'romaji' => 'douzo',             'meaning' => 'Silakan',               'category' => 'Salam & Ungkapan'],

                        // Orang & Kata Ganti
                        ['japanese' => 'わたし',             'romaji' => 'watashi',           'meaning' => 'Saya / Aku',            'category' => 'Orang & Kata Ganti'],
                        ['japanese' => 'あなた',             'romaji' => 'anata',             'meaning' => 'Anda / Kamu',           'category' => 'Orang & Kata Ganti'],
                        ['japanese' => 'あのひと',           'romaji' => 'ano hito',          'meaning' => 'Orang itu',             'category' => 'Orang & Kata Ganti'],
                        ['japanese' => 'せんせい',           'romaji' => 'sensei',            'meaning' => 'Guru / Pengajar',       'category' => 'Orang & Kata Ganti'],
                        ['japanese' => 'がくせい',           'romaji' => 'gakusei',           'meaning' => 'Pelajar / Mahasiswa',   'category' => 'Orang & Kata Ganti'],
                        ['japanese' => 'ともだち',           'romaji' => 'tomodachi',         'meaning' => 'Teman / Kawan',         'category' => 'Orang & Kata Ganti'],

                        // Benda & Tempat
                        ['japanese' => 'ほん',               'romaji' => 'hon',               'meaning' => 'Buku',                  'category' => 'Benda & Tempat'],
                        ['japanese' => 'くるま',             'romaji' => 'kuruma',            'meaning' => 'Mobil',                 'category' => 'Benda & Tempat'],
                        ['japanese' => 'みず',               'romaji' => 'mizu',              'meaning' => 'Air putih',             'category' => 'Benda & Tempat'],
                        ['japanese' => 'おちゃ',             'romaji' => 'ocha',              'meaning' => 'Teh Jepang',            'category' => 'Benda & Tempat'],
                        ['japanese' => 'ごはん',             'romaji' => 'gohan',             'meaning' => 'Nasi / Makanan',        'category' => 'Benda & Tempat'],
                        ['japanese' => 'うち',               'romaji' => 'uchi',              'meaning' => 'Rumah',                 'category' => 'Benda & Tempat'],
                        ['japanese' => 'がっこう',           'romaji' => 'gakkou',            'meaning' => 'Sekolah',               'category' => 'Benda & Tempat'],
                        ['japanese' => 'えき',               'romaji' => 'eki',               'meaning' => 'Stasiun',               'category' => 'Benda & Tempat'],

                        // Waktu & Hari
                        ['japanese' => 'いま',               'romaji' => 'ima',               'meaning' => 'Sekarang',              'category' => 'Waktu'],
                        ['japanese' => 'きょう',             'romaji' => 'kyou',              'meaning' => 'Hari ini',              'category' => 'Waktu'],
                        ['japanese' => 'あした',             'romaji' => 'ashita',            'meaning' => 'Besok',                 'category' => 'Waktu'],
                        ['japanese' => 'きのう',             'romaji' => 'kinou',             'meaning' => 'Kemarin',               'category' => 'Waktu'],
                        ['japanese' => 'まいにち',           'romaji' => 'mainichi',          'meaning' => 'Setiap hari',           'category' => 'Waktu'],
                        ['japanese' => 'あさ',               'romaji' => 'asa',               'meaning' => 'Pagi hari',             'category' => 'Waktu'],
                        ['japanese' => 'よる',               'romaji' => 'yoru',              'meaning' => 'Malam hari',            'category' => 'Waktu'],

                        // Kata Kerja Dasar
                        ['japanese' => 'たべます',           'romaji' => 'tabemasu',          'meaning' => 'Makan',                 'category' => 'Kata Kerja Dasar'],
                        ['japanese' => 'のみます',           'romaji' => 'nomimasu',          'meaning' => 'Minum',                 'category' => 'Kata Kerja Dasar'],
                        ['japanese' => 'いきます',           'romaji' => 'ikimasu',           'meaning' => 'Pergi',                 'category' => 'Kata Kerja Dasar'],
                        ['japanese' => 'きます',             'romaji' => 'kimasu',            'meaning' => 'Datang',                'category' => 'Kata Kerja Dasar'],
                        ['japanese' => 'べんきょうします',   'romaji' => 'benkyou shimasu',   'meaning' => 'Belajar',               'category' => 'Kata Kerja Dasar'],
                    ],
                ],
            ]
        );

        // ── 4. KANJI N5 (SELURUH KANJI JLPT N5 LENGKAP 105 KARAKTER) ────────

        $kanji = Material::updateOrCreate(
            ['slug' => 'kanji-n5'],
            [
                'title'       => 'Kanji N5 Lengkap',
                'category'    => 'kanji',
                'level'       => 'beginner',
                'description' => 'Seluruh 105 karakter Kanji resmi standar JLPT N5 lengkap dengan On\'yomi, Kun\'yomi, arti bahasa Indonesia, dan kategori tematik.',
                'order'       => 4,
                'content'     => [
                    'kanji' => [
                        // ── 1. Angka & Bilangan (14 Kanji) ──────────────────
                        ['kanji' => '一', 'kun' => 'ひと・つ',          'on' => 'イチ、イツ',     'meaning' => 'Satu',                   'category' => 'Angka & Bilangan'],
                        ['kanji' => '二', 'kun' => 'ふた・つ',          'on' => 'ニ、ジ',         'meaning' => 'Dua',                    'category' => 'Angka & Bilangan'],
                        ['kanji' => '三', 'kun' => 'みっ・つ',          'on' => 'サン',           'meaning' => 'Tiga',                   'category' => 'Angka & Bilangan'],
                        ['kanji' => '四', 'kun' => 'よっ・つ、よん',     'on' => 'シ',             'meaning' => 'Empat',                  'category' => 'Angka & Bilangan'],
                        ['kanji' => '五', 'kun' => 'いつ・つ',          'on' => 'ゴ',             'meaning' => 'Lima',                   'category' => 'Angka & Bilangan'],
                        ['kanji' => '六', 'kun' => 'むっ・つ',          'on' => 'ロク',           'meaning' => 'Enam',                   'category' => 'Angka & Bilangan'],
                        ['kanji' => '七', 'kun' => 'なな・つ',          'on' => 'シチ',           'meaning' => 'Tujuh',                  'category' => 'Angka & Bilangan'],
                        ['kanji' => '八', 'kun' => 'やっ・つ',          'on' => 'ハチ',           'meaning' => 'Delapan',                'category' => 'Angka & Bilangan'],
                        ['kanji' => '九', 'kun' => 'ここの・つ',        'on' => 'キュウ、ク',     'meaning' => 'Sembilan',               'category' => 'Angka & Bilangan'],
                        ['kanji' => '十', 'kun' => 'とお',              'on' => 'ジュウ',         'meaning' => 'Sepuluh',                'category' => 'Angka & Bilangan'],
                        ['kanji' => '百', 'kun' => '—',                 'on' => 'ヒャク',         'meaning' => 'Ratus',                  'category' => 'Angka & Bilangan'],
                        ['kanji' => '千', 'kun' => 'ち',                'on' => 'セン',           'meaning' => 'Ribu',                   'category' => 'Angka & Bilangan'],
                        ['kanji' => '万', 'kun' => '—',                 'on' => 'マン、バン',     'meaning' => 'Puluh Ribu (10.000)',    'category' => 'Angka & Bilangan'],
                        ['kanji' => '円', 'kun' => 'まる・い',          'on' => 'エン',           'meaning' => 'Yen / Lingkaran',        'category' => 'Angka & Bilangan'],

                        // ── 2. Waktu, Hari & Kalender (14 Kanji) ────────────
                        ['kanji' => '日', 'kun' => 'ひ、-び、-か',       'on' => 'ニチ、ジツ',     'meaning' => 'Hari / Matahari',        'category' => 'Waktu & Kalender'],
                        ['kanji' => '月', 'kun' => 'つき',              'on' => 'ゲツ、ガツ',     'meaning' => 'Bulan',                  'category' => 'Waktu & Kalender'],
                        ['kanji' => '年', 'kun' => 'とし',              'on' => 'ネン',           'meaning' => 'Tahun',                  'category' => 'Waktu & Kalender'],
                        ['kanji' => '時', 'kun' => 'とき',              'on' => 'ジ',             'meaning' => 'Jam / Waktu',            'category' => 'Waktu & Kalender'],
                        ['kanji' => '分', 'kun' => 'わ・かる、わ・ける', 'on' => 'ブン、フン、プン', 'meaning' => 'Menit / Membagi',       'category' => 'Waktu & Kalender'],
                        ['kanji' => '半', 'kun' => 'なか・ば',          'on' => 'ハン',           'meaning' => 'Setengah',               'category' => 'Waktu & Kalender'],
                        ['kanji' => '今', 'kun' => 'いま',              'on' => 'コン',           'meaning' => 'Sekarang',               'category' => 'Waktu & Kalender'],
                        ['kanji' => '先', 'kun' => 'さき',              'on' => 'セン',           'meaning' => 'Sebelum / Ujung',        'category' => 'Waktu & Kalender'],
                        ['kanji' => '前', 'kun' => 'まえ',              'on' => 'ゼン',           'meaning' => 'Depan / Sebelum',        'category' => 'Waktu & Kalender'],
                        ['kanji' => '後', 'kun' => 'のち、うし・ろ、あと', 'on' => 'ゴ、コウ',       'meaning' => 'Belakang / Setelah',     'category' => 'Waktu & Kalender'],
                        ['kanji' => '午', 'kun' => '—',                 'on' => 'ゴ',             'meaning' => 'Tengah Hari / Siang',    'category' => 'Waktu & Kalender'],
                        ['kanji' => '間', 'kun' => 'あいだ、ま',        'on' => 'カン、ケン',     'meaning' => 'Antara / Ruang / Waktu', 'category' => 'Waktu & Kalender'],
                        ['kanji' => '毎', 'kun' => '—',                 'on' => 'マイ',           'meaning' => 'Setiap',                 'category' => 'Waktu & Kalender'],
                        ['kanji' => '週', 'kun' => '—',                 'on' => 'シュウ',         'meaning' => 'Minggu / Pekan',         'category' => 'Waktu & Kalender'],

                        // ── 3. Alam & Cuaca (10 Kanji) ──────────────────────
                        ['kanji' => '火', 'kun' => 'ひ',                'on' => 'カ',             'meaning' => 'Api',                    'category' => 'Alam & Cuaca'],
                        ['kanji' => '水', 'kun' => 'みず',              'on' => 'スイ',           'meaning' => 'Air',                    'category' => 'Alam & Cuaca'],
                        ['kanji' => '木', 'kun' => 'き',                'on' => 'モク、ボク',     'meaning' => 'Pohon / Kayu',           'category' => 'Alam & Cuaca'],
                        ['kanji' => '金', 'kun' => 'かね',              'on' => 'キン',           'meaning' => 'Emas / Uang',            'category' => 'Alam & Cuaca'],
                        ['kanji' => '土', 'kun' => 'つち',              'on' => 'ド、ト',         'meaning' => 'Tanah',                  'category' => 'Alam & Cuaca'],
                        ['kanji' => '山', 'kun' => 'やま',              'on' => 'サン',           'meaning' => 'Gunung',                 'category' => 'Alam & Cuaca'],
                        ['kanji' => '川', 'kun' => 'かわ',              'on' => 'セン',           'meaning' => 'Sungai',                 'category' => 'Alam & Cuaca'],
                        ['kanji' => '天', 'kun' => 'あめ、あま',        'on' => 'テン',           'meaning' => 'Langit / Surga',         'category' => 'Alam & Cuaca'],
                        ['kanji' => '気', 'kun' => '—',                 'on' => 'キ',             'meaning' => 'Udara / Hawa / Energi',  'category' => 'Alam & Cuaca'],
                        ['kanji' => '雨', 'kun' => 'あめ',              'on' => 'ウ',             'meaning' => 'Hujan',                  'category' => 'Alam & Cuaca'],

                        // ── 4. Manusia & Anggota Tubuh (13 Kanji) ────────────
                        ['kanji' => '人', 'kun' => 'ひと',              'on' => 'ジン、ニン',     'meaning' => 'Orang / Manusia',        'category' => 'Manusia & Tubuh'],
                        ['kanji' => '男', 'kun' => 'おとこ',            'on' => 'ダン、ナン',     'meaning' => 'Pria / Laki-laki',       'category' => 'Manusia & Tubuh'],
                        ['kanji' => '女', 'kun' => 'おんな',            'on' => 'ジョ',           'meaning' => 'Wanita / Perempuan',     'category' => 'Manusia & Tubuh'],
                        ['kanji' => '子', 'kun' => 'こ',                'on' => 'シ、ス',         'meaning' => 'Anak',                   'category' => 'Manusia & Tubuh'],
                        ['kanji' => '父', 'kun' => 'ちち',              'on' => 'フ',             'meaning' => 'Ayah',                   'category' => 'Manusia & Tubuh'],
                        ['kanji' => '母', 'kun' => 'はは',              'on' => 'ボ',             'meaning' => 'Ibu',                    'category' => 'Manusia & Tubuh'],
                        ['kanji' => '友', 'kun' => 'とも',              'on' => 'ユウ',           'meaning' => 'Teman / Sahabat',        'category' => 'Manusia & Tubuh'],
                        ['kanji' => '目', 'kun' => 'め',                'on' => 'モク',           'meaning' => 'Mata',                   'category' => 'Manusia & Tubuh'],
                        ['kanji' => '口', 'kun' => 'くち',              'on' => 'コウ、ク',       'meaning' => 'Mulut',                  'category' => 'Manusia & Tubuh'],
                        ['kanji' => '耳', 'kun' => 'みみ',              'on' => 'ジ',             'meaning' => 'Telinga',                'category' => 'Manusia & Tubuh'],
                        ['kanji' => '手', 'kun' => 'て',                'on' => 'シュ',           'meaning' => 'Tangan',                 'category' => 'Manusia & Tubuh'],
                        ['kanji' => '足', 'kun' => 'あし、た・りる',    'on' => 'ソク',           'meaning' => 'Kaki / Cukup',           'category' => 'Manusia & Tubuh'],
                        ['kanji' => '名', 'kun' => 'な',                'on' => 'メイ、ミョウ',   'meaning' => 'Nama',                   'category' => 'Manusia & Tubuh'],

                        // ── 5. Arah & Posisi (10 Kanji) ─────────────────────
                        ['kanji' => '上', 'kun' => 'うえ、あ・がる',    'on' => 'ジョウ',         'meaning' => 'Atas / Naik',            'category' => 'Arah & Posisi'],
                        ['kanji' => '下', 'kun' => 'した、さ・がる',    'on' => 'カ、ゲ',         'meaning' => 'Bawah / Turun',          'category' => 'Arah & Posisi'],
                        ['kanji' => '左', 'kun' => 'ひだり',            'on' => 'サ',             'meaning' => 'Kiri',                   'category' => 'Arah & Posisi'],
                        ['kanji' => '右', 'kun' => 'みぎ',              'on' => 'ウ、ユウ',       'meaning' => 'Kanan',                  'category' => 'Arah & Posisi'],
                        ['kanji' => '中', 'kun' => 'なか',              'on' => 'チュウ',         'meaning' => 'Tengah / Dalam',         'category' => 'Arah & Posisi'],
                        ['kanji' => '北', 'kun' => 'きた',              'on' => 'ホク',           'meaning' => 'Utara',                  'category' => 'Arah & Posisi'],
                        ['kanji' => '南', 'kun' => 'みなみ',            'on' => 'ナン',           'meaning' => 'Selatan',                'category' => 'Arah & Posisi'],
                        ['kanji' => '東', 'kun' => 'ひがし',            'on' => 'トウ',           'meaning' => 'Timur',                  'category' => 'Arah & Posisi'],
                        ['kanji' => '西', 'kun' => 'にし',              'on' => 'セイ、サイ',     'meaning' => 'Barat',                  'category' => 'Arah & Posisi'],
                        ['kanji' => '外', 'kun' => 'そと、ほか',        'on' => 'ガイ、ゲ',       'meaning' => 'Luar',                   'category' => 'Arah & Posisi'],

                        // ── 6. Sifat & Ukuran (11 Kanji) ────────────────────
                        ['kanji' => '大', 'kun' => 'おお・きい',        'on' => 'ダイ、タイ',     'meaning' => 'Besar',                  'category' => 'Sifat & Ukuran'],
                        ['kanji' => '小', 'kun' => 'ちい・さい',        'on' => 'ショウ',         'meaning' => 'Kecil',                  'category' => 'Sifat & Ukuran'],
                        ['kanji' => '長', 'kun' => 'なが・い',          'on' => 'チョウ',         'meaning' => 'Panjang / Pimpinan',     'category' => 'Sifat & Ukuran'],
                        ['kanji' => '高', 'kun' => 'たか・い',          'on' => 'コウ',           'meaning' => 'Tinggi / Mahal',         'category' => 'Sifat & Ukuran'],
                        ['kanji' => '安', 'kun' => 'やす・い',          'on' => 'アン',           'meaning' => 'Murah / Tenang',         'category' => 'Sifat & Ukuran'],
                        ['kanji' => '多', 'kun' => 'おお・い',          'on' => 'タ',             'meaning' => 'Banyak',                 'category' => 'Sifat & Ukuran'],
                        ['kanji' => '少', 'kun' => 'すく・ない、すこ・し', 'on' => 'ショウ',       'meaning' => 'Sedikit',                'category' => 'Sifat & Ukuran'],
                        ['kanji' => '新', 'kun' => 'あたら・しい',      'on' => 'シン',           'meaning' => 'Baru',                   'category' => 'Sifat & Ukuran'],
                        ['kanji' => '古', 'kun' => 'ふる・い',          'on' => 'コ',             'meaning' => 'Lama / Kuno',            'category' => 'Sifat & Ukuran'],
                        ['kanji' => '白', 'kun' => 'しろ・い',          'on' => 'ハク',           'meaning' => 'Putih',                  'category' => 'Sifat & Ukuran'],
                        ['kanji' => '赤', 'kun' => 'あか・い',          'on' => 'セキ',           'meaning' => 'Merah',                  'category' => 'Sifat & Ukuran'],

                        // ── 7. Kata Kerja / Aksi (16 Kanji) ─────────────────
                        ['kanji' => '行', 'kun' => 'い・く、おこな・う', 'on' => 'コウ、ギョウ',   'meaning' => 'Pergi / Melakukan',      'category' => 'Kata Kerja / Aksi'],
                        ['kanji' => '来', 'kun' => 'く・る、き・ます',   'on' => 'ライ',           'meaning' => 'Datang',                 'category' => 'Kata Kerja / Aksi'],
                        ['kanji' => '食', 'kun' => 'た・べる',          'on' => 'ショク',         'meaning' => 'Makan',                  'category' => 'Kata Kerja / Aksi'],
                        ['kanji' => '飲', 'kun' => 'の・む',            'on' => 'イン',           'meaning' => 'Minum',                  'category' => 'Kata Kerja / Aksi'],
                        ['kanji' => '見', 'kun' => 'み・る',            'on' => 'ケン',           'meaning' => 'Melihat',                'category' => 'Kata Kerja / Aksi'],
                        ['kanji' => '聞', 'kun' => 'き・く',            'on' => 'ブン、モン',     'meaning' => 'Mendengar / Bertanya',   'category' => 'Kata Kerja / Aksi'],
                        ['kanji' => '読', 'kun' => 'よ・む',            'on' => 'ドク',           'meaning' => 'Membaca',                'category' => 'Kata Kerja / Aksi'],
                        ['kanji' => '書', 'kun' => 'か・く',            'on' => 'ショ',           'meaning' => 'Menulis',                'category' => 'Kata Kerja / Aksi'],
                        ['kanji' => '話', 'kun' => 'はな・す',          'on' => 'ワ',             'meaning' => 'Berbicara / Cerita',     'category' => 'Kata Kerja / Aksi'],
                        ['kanji' => '買', 'kun' => 'か・う',            'on' => 'バイ',           'meaning' => 'Membeli',                'category' => 'Kata Kerja / Aksi'],
                        ['kanji' => '出', 'kun' => 'で・る、だ・す',    'on' => 'シュツ',         'meaning' => 'Keluar / Mengeluarkan',  'category' => 'Kata Kerja / Aksi'],
                        ['kanji' => '入', 'kun' => 'はい・る、い・れる', 'on' => 'ニュウ',         'meaning' => 'Masuk / Memasukkan',     'category' => 'Kata Kerja / Aksi'],
                        ['kanji' => '休', 'kun' => 'やす・む',          'on' => 'キュウ',         'meaning' => 'Istirahat / Libur',      'category' => 'Kata Kerja / Aksi'],
                        ['kanji' => '立', 'kun' => 'た・つ',            'on' => 'リツ',           'meaning' => 'Berdiri',                'category' => 'Kata Kerja / Aksi'],
                        ['kanji' => '会', 'kun' => 'あ・う',            'on' => 'カイ',           'meaning' => 'Bertemu / Rapat',        'category' => 'Kata Kerja / Aksi'],
                        ['kanji' => '生', 'kun' => 'い・きる、う・まれる', 'on' => 'セイ、ショウ', 'meaning' => 'Hidup / Lahir',          'category' => 'Kata Kerja / Aksi'],

                        // ── 8. Kehidupan, Sekolah & Masyarakat (17 Kanji) ───
                        ['kanji' => '本', 'kun' => 'もと',              'on' => 'ホン',           'meaning' => 'Buku / Asal',            'category' => 'Sekolah & Lingkungan'],
                        ['kanji' => '学', 'kun' => 'まな・ぶ',          'on' => 'ガク',           'meaning' => 'Belajar / Ilmu',         'category' => 'Sekolah & Lingkungan'],
                        ['kanji' => '校', 'kun' => '—',                 'on' => 'コウ',           'meaning' => 'Sekolah',                'category' => 'Sekolah & Lingkungan'],
                        ['kanji' => '国', 'kun' => 'くに',              'on' => 'コク',           'meaning' => 'Negara',                 'category' => 'Sekolah & Lingkungan'],
                        ['kanji' => '車', 'kun' => 'くるま',            'on' => 'シャ',           'meaning' => 'Mobil / Kendaraan',      'category' => 'Sekolah & Lingkungan'],
                        ['kanji' => '語', 'kun' => 'かた・る',          'on' => 'ゴ',             'meaning' => 'Bahasa / Kata',          'category' => 'Sekolah & Lingkungan'],
                        ['kanji' => '電', 'kun' => '—',                 'on' => 'デン',           'meaning' => 'Listrik',                'category' => 'Sekolah & Lingkungan'],
                        ['kanji' => '社', 'kun' => 'やしろ',            'on' => 'シャ',           'meaning' => 'Perusahaan / Kuil',      'category' => 'Sekolah & Lingkungan'],
                        ['kanji' => '店', 'kun' => 'みせ',              'on' => 'テン',           'meaning' => 'Toko / Kedai',           'category' => 'Sekolah & Lingkungan'],
                        ['kanji' => '道', 'kun' => 'みち',              'on' => 'ドウ',           'meaning' => 'Jalan / Jalur',          'category' => 'Sekolah & Lingkungan'],
                        ['kanji' => '駅', 'kun' => '—',                 'on' => 'エキ',           'meaning' => 'Stasiun',                'category' => 'Sekolah & Lingkungan'],
                        ['kanji' => '花', 'kun' => 'はな',              'on' => 'カ',             'meaning' => 'Bunga',                  'category' => 'Sekolah & Lingkungan'],
                        ['kanji' => '魚', 'kun' => 'さかな、うお',      'on' => 'ギョ',           'meaning' => 'Ikan',                   'category' => 'Sekolah & Lingkungan'],
                        ['kanji' => '何', 'kun' => 'なに、なん',        'on' => 'カ',             'meaning' => 'Apa',                    'category' => 'Sekolah & Lingkungan'],
                        ['kanji' => '空', 'kun' => 'そら、あ・く',      'on' => 'クウ',           'meaning' => 'Langit / Kosong',        'category' => 'Sekolah & Lingkungan'],
                        ['kanji' => '海', 'kun' => 'うみ',              'on' => 'カイ',           'meaning' => 'Laut',                   'category' => 'Sekolah & Lingkungan'],
                        ['kanji' => '門', 'kun' => 'かど',              'on' => 'モン',           'meaning' => 'Pintu Gerbang',          'category' => 'Sekolah & Lingkungan'],
                    ],
                ],
            ]
        );

        // ── 5. POLA KALIMAT DASAR (10 POLA LENGKAP N5) ───────────────────────

        $grammar = Material::updateOrCreate(
            ['slug' => 'pola-kalimat-dasar'],
            [
                'title'       => 'Pola Kalimat Dasar',
                'category'    => 'grammar',
                'level'       => 'beginner',
                'description' => '10 pola kalimat fundamental bahasa Jepang N5: penanda topik は, bentuk negatif, kalimat tanya, kata tunjuk, partikel の, を, へ, dan で.',
                'order'       => 5,
                'content'     => [
                    'patterns' => [
                        [
                            'pattern'     => 'A は B です',
                            'romaji'      => 'A wa B desu',
                            'meaning'     => 'A adalah B (Kalimat Positif Sopan)',
                            'explanation' => 'Partikel は (dibaca "wa") menandai topik atau subjek utama kalimat. です (desu) adalah kopula penegas bentuk sopan.',
                            'example'     => 'わたしはがくせいです (Watashi wa gakusei desu — Saya adalah pelajar).',
                        ],
                        [
                            'pattern'     => 'A は B では ありません',
                            'romaji'      => 'A wa B dewa arimasen',
                            'meaning'     => 'A bukan B (Kalimat Negatif Sopan)',
                            'explanation' => 'Bentuk sangkalan atau negatif sopan dari です. Dalam ragam percakapan santai sering diucapkan "じゃ ありません" (ja arimasen).',
                            'example'     => 'たなかさんはせんせいではありません (Tanaka-san wa sensei dewa arimasen — Tanaka bukan guru).',
                        ],
                        [
                            'pattern'     => 'A は B ですか',
                            'romaji'      => 'A wa B desu ka',
                            'meaning'     => 'Apakah A adalah B? (Kalimat Tanya)',
                            'explanation' => 'Partikel か (ka) di akhir kalimat berfungsi sebagai kata tanya resmi. Kalimat bahasa Jepang formal tidak memerlukan tanda tanya grafis (?).',
                            'example'     => 'あなたはにほんじんですか (Anata wa nihonjin desu ka — Apakah Anda orang Jepang?).',
                        ],
                        [
                            'pattern'     => 'これ / それ / あれ は ~ です',
                            'romaji'      => 'Kore / Sore / Are wa ~ desu',
                            'meaning'     => 'Ini / Itu / Itu (jauh) adalah ~ (Kata Tunjuk Benda)',
                            'explanation' => 'これ (dekat pembicara), それ (dekat lawan bicara), あれ (jauh dari keduanya). Berdiri sendiri sebagai kata ganti benda.',
                            'example'     => 'これはにほんごのほんです (Kore wa nihongo no hon desu — Ini adalah buku bahasa Jepang).',
                        ],
                        [
                            'pattern'     => 'この / その / あの + Kata Benda',
                            'romaji'      => 'Kono / Sono / Ano + Kata Benda',
                            'meaning'     => 'Kata benda ini / itu / itu (jauh)',
                            'explanation' => 'Berbeda dengan kore/sore/are, kono/sono/ano wajib selalu diikuti langsung oleh kata benda yang diterangkan.',
                            'example'     => 'このくるまはあかいです (Kono kuruma wa akai desu — Mobil ini berwarna merah).',
                        ],
                        [
                            'pattern'     => 'ここ / そこ / あそこ は ~ です',
                            'romaji'      => 'Koko / Soko / Asoko wa ~ desu',
                            'meaning'     => 'Di sini / Di situ / Di sana adalah ~ (Kata Tunjuk Tempat)',
                            'explanation' => 'Digunakan untuk menunjukkan tempat atau ruangan. ここ (tempat pembicara), そこ (tempat lawan bicara), あそこ (tempat jauh).',
                            'example'     => 'ここはきょうしつです (Koko wa kyoushitsu desu — Di sini adalah ruang kelas).',
                        ],
                        [
                            'pattern'     => 'KB1 の KB2',
                            'romaji'      => 'KB1 no KB2',
                            'meaning'     => 'KB2 milik atau penjelas dari KB1 (Partikel Kepemilikan)',
                            'explanation' => 'Partikel の (no) menghubungkan dua kata benda, menyatakan hak milik, kepunyaan, asal, atau klasifikasi.',
                            'example'     => 'これはわたしのノートです (Kore wa watashi no nooto desu — Ini adalah buku catatan milik saya).',
                        ],
                        [
                            'pattern'     => 'Objek を Kata Kerja',
                            'romaji'      => 'Objek o Kata Kerja',
                            'meaning'     => 'Melakukan aktivitas terhadap objek (Partikel Objek Langsung)',
                            'explanation' => 'Partikel を (ditulis "wo", dibaca "o") menandai objek penderita yang langsung dikenai aksi kata kerja transitif.',
                            'example'     => 'パンをたべます (Pan o tabemasu — Makan roti).',
                        ],
                        [
                            'pattern'     => 'Tempat へ / に いきます / きます',
                            'romaji'      => 'Tempat e / ni ikimasu / kimasu',
                            'meaning'     => 'Pergi / datang ke (tempat tujuan)',
                            'explanation' => 'Partikel へ (ditulis "he", dibaca "e") atau に ("ni") menunjukkan arah gerak atau tempat tujuan akhir.',
                            'example'     => 'あした、がっこうへいきます (Ashita, gakkou e ikimasu — Besok, pergi ke sekolah).',
                        ],
                        [
                            'pattern'     => 'Tempat で Kata Kerja',
                            'romaji'      => 'Tempat de Kata Kerja',
                            'meaning'     => 'Melakukan aktivitas di (tempat terjadinya aksi)',
                            'explanation' => 'Partikel で (de) menandai lokasi tempat dilangsungkannya suatu kegiatan atau pekerjaan.',
                            'example'     => 'としょかんでべんきょうします (Toshokan de benkyou shimasu — Belajar di perpustakaan).',
                        ],
                    ],
                ],
            ]
        );

        // ── 6. QUIZZES & LATIHAN (LENGKAP 5 MODUL — 95 SOAL) ─────────────────

        // Quiz 1: Hiragana Lengkap (20 Soal)
        $quiz1 = Quiz::updateOrCreate(
            ['material_id' => $hiragana->id],
            [
                'title'       => 'Quiz Hiragana Lengkap',
                'category'    => 'hiragana',
                'level'       => 'beginner',
                'description' => 'Uji kemampuan kamu mengenali seluruh variasi huruf Hiragana dasar (Gojūon) dan Dakuon/Handakuon.',
                'time_limit'  => 240,
            ]
        );

        $hiraganaQuestions = [
            ['jp' => 'あ', 'correct' => 'a',   'opts' => ['a', 'i', 'u', 'e']],
            ['jp' => 'い', 'correct' => 'i',   'opts' => ['a', 'i', 'o', 'e']],
            ['jp' => 'う', 'correct' => 'u',   'opts' => ['o', 'a', 'u', 'e']],
            ['jp' => 'え', 'correct' => 'e',   'opts' => ['i', 'e', 'o', 'u']],
            ['jp' => 'お', 'correct' => 'o',   'opts' => ['a', 'i', 'e', 'o']],
            ['jp' => 'か', 'correct' => 'ka',  'opts' => ['ki', 'ka', 'ku', 'ko']],
            ['jp' => 'き', 'correct' => 'ki',  'opts' => ['ki', 'ka', 'ke', 'ko']],
            ['jp' => 'く', 'correct' => 'ku',  'opts' => ['ko', 'ka', 'ku', 'ki']],
            ['jp' => 'さ', 'correct' => 'sa',  'opts' => ['shi', 'sa', 'su', 'so']],
            ['jp' => 'し', 'correct' => 'shi', 'opts' => ['si', 'shi', 'su', 'sa']],
            ['jp' => 'す', 'correct' => 'su',  'opts' => ['su', 'so', 'se', 'sa']],
            ['jp' => 'た', 'correct' => 'ta',  'opts' => ['ta', 'chi', 'tsu', 'to']],
            ['jp' => 'ち', 'correct' => 'chi', 'opts' => ['chi', 'ta', 'tsu', 'te']],
            ['jp' => 'つ', 'correct' => 'tsu', 'opts' => ['tsu', 'chi', 'te', 'to']],
            ['jp' => 'な', 'correct' => 'na',  'opts' => ['ne', 'nu', 'na', 'no']],
            ['jp' => 'は', 'correct' => 'ha',  'opts' => ['ha', 'hi', 'fu', 'he']],
            ['jp' => 'ま', 'correct' => 'ma',  'opts' => ['mi', 'mu', 'ma', 'mo']],
            ['jp' => 'ら', 'correct' => 'ra',  'opts' => ['ra', 'ri', 'ru', 're']],
            ['jp' => 'が', 'correct' => 'ga',  'opts' => ['ga', 'gi', 'gu', 'ge']],
            ['jp' => 'ぱ', 'correct' => 'pa',  'opts' => ['pa', 'pi', 'pu', 'pe']],
        ];

        QuizQuestion::where('quiz_id', $quiz1->id)->delete();
        foreach ($hiraganaQuestions as $i => $q) {
            QuizQuestion::create([
                'quiz_id'        => $quiz1->id,
                'type'           => 'multiple_choice',
                'question'       => 'Apa bacaan huruf Hiragana ini?',
                'question_jp'    => $q['jp'],
                'options'        => $q['opts'],
                'correct_answer' => $q['correct'],
                'order'          => $i + 1,
            ]);
        }

        // Quiz 2: Katakana Lengkap (20 Soal)
        $quiz2 = Quiz::updateOrCreate(
            ['material_id' => $katakana->id],
            [
                'title'       => 'Quiz Katakana Lengkap',
                'category'    => 'katakana',
                'level'       => 'beginner',
                'description' => 'Uji pemahaman membaca huruf Katakana dasar (Gojūon) dan kata serapan bahasa asing.',
                'time_limit'  => 240,
            ]
        );

        $katakanaQuestions = [
            ['jp' => 'ア', 'correct' => 'a',   'opts' => ['a', 'i', 'u', 'e']],
            ['jp' => 'イ', 'correct' => 'i',   'opts' => ['a', 'i', 'o', 'e']],
            ['jp' => 'ウ', 'correct' => 'u',   'opts' => ['o', 'a', 'u', 'e']],
            ['jp' => 'エ', 'correct' => 'e',   'opts' => ['i', 'e', 'o', 'u']],
            ['jp' => 'オ', 'correct' => 'o',   'opts' => ['a', 'i', 'e', 'o']],
            ['jp' => 'カ', 'correct' => 'ka',  'opts' => ['ki', 'ka', 'ku', 'ko']],
            ['jp' => 'キ', 'correct' => 'ki',  'opts' => ['ki', 'ka', 'ke', 'ko']],
            ['jp' => 'サ', 'correct' => 'sa',  'opts' => ['shi', 'sa', 'su', 'so']],
            ['jp' => 'シ', 'correct' => 'shi', 'opts' => ['shi', 'tsu', 'so', 'n']],
            ['jp' => 'ス', 'correct' => 'su',  'opts' => ['su', 'se', 'so', 'sa']],
            ['jp' => 'タ', 'correct' => 'ta',  'opts' => ['ta', 'chi', 'tsu', 'te']],
            ['jp' => 'チ', 'correct' => 'chi', 'opts' => ['chi', 'te', 'to', 'ta']],
            ['jp' => 'ツ', 'correct' => 'tsu', 'opts' => ['tsu', 'shi', 'so', 'n']],
            ['jp' => 'ナ', 'correct' => 'na',  'opts' => ['na', 'ni', 'nu', 'ne']],
            ['jp' => 'ハ', 'correct' => 'ha',  'opts' => ['ha', 'hi', 'fu', 'he']],
            ['jp' => 'マ', 'correct' => 'ma',  'opts' => ['ma', 'mi', 'mu', 'me']],
            ['jp' => 'ラ', 'correct' => 'ra',  'opts' => ['ra', 'ri', 'ru', 're']],
            ['jp' => 'ガ', 'correct' => 'ga',  'opts' => ['ga', 'gi', 'gu', 'ge']],
            ['jp' => 'パン', 'correct' => 'Roti', 'opts' => ['Roti', 'Susu', 'Daging', 'Nasi']],
            ['jp' => 'コーヒー', 'correct' => 'Kopi', 'opts' => ['Kopi', 'Teh', 'Jus', 'Cokelat']],
        ];

        QuizQuestion::where('quiz_id', $quiz2->id)->delete();
        foreach ($katakanaQuestions as $i => $q) {
            QuizQuestion::create([
                'quiz_id'        => $quiz2->id,
                'type'           => 'multiple_choice',
                'question'       => 'Apa bacaan / arti huruf Katakana ini?',
                'question_jp'    => $q['jp'],
                'options'        => $q['opts'],
                'correct_answer' => $q['correct'],
                'order'          => $i + 1,
            ]);
        }

        // Quiz 3: Kosakata Sehari-hari (20 Soal)
        $quiz3 = Quiz::updateOrCreate(
            ['material_id' => $vocab->id],
            [
                'title'       => 'Quiz Kosakata Sehari-hari',
                'category'    => 'vocabulary',
                'level'       => 'beginner',
                'description' => 'Latihan komprehensif kosakata JLPT N5: salam, sapaan, orang, benda, waktu, dan kata kerja.',
                'time_limit'  => 300,
            ]
        );

        $vocabQuestions = [
            ['q' => 'Apa arti dari "ありがとう"?', 'correct' => 'Terima kasih', 'opts' => ['Terima kasih', 'Permisi', 'Maaf', 'Selamat pagi']],
            ['q' => 'Bagaimana cara mengucapkan "Selamat pagi (sopan)"?', 'correct' => 'おはようございます', 'opts' => ['おはようございます', 'こんにちは', 'こんばんは', 'さようなら']],
            ['q' => 'Apa arti dari "すみません"?', 'correct' => 'Permisi / Maaf', 'opts' => ['Terima kasih', 'Permisi / Maaf', 'Selamat malam', 'Ya']],
            ['q' => 'Apa arti dari "いいえ"?', 'correct' => 'Tidak / Bukan', 'opts' => ['Ya', 'Tidak / Bukan', 'Mungkin', 'Tolong']],
            ['q' => 'Apa arti dari "こんばんは"?', 'correct' => 'Selamat malam', 'opts' => ['Selamat pagi', 'Selamat siang', 'Selamat malam', 'Selamat tinggal']],
            ['q' => 'Bagaimana cara mengucapkan "Selamat siang / Halo"?', 'correct' => 'こんにちは', 'opts' => ['おはよう', 'こんにちは', 'こんばんは', 'さようなら']],
            ['q' => 'Apa arti dari ungkapan "さようなら"?', 'correct' => 'Selamat tinggal', 'opts' => ['Halo', 'Selamat tinggal', 'Sampai nanti', 'Selamat siang']],
            ['q' => 'Apa arti dari ungkapan "ごめんなさい"?', 'correct' => 'Mohon maaf', 'opts' => ['Terima kasih', 'Mohon maaf', 'Silakan', 'Sama-sama']],
            ['q' => 'Apa arti dari kata ganti "わたし"?', 'correct' => 'Saya / Aku', 'opts' => ['Saya / Aku', 'Kamu', 'Dia', 'Mereka']],
            ['q' => 'Apa arti dari kata "せんせい"?', 'correct' => 'Guru / Pengajar', 'opts' => ['Guru / Pengajar', 'Murid', 'Dokter', 'Karyawan']],
            ['q' => 'Apa arti dari kata "がくせい"?', 'correct' => 'Pelajar / Mahasiswa', 'opts' => ['Guru', 'Pelajar / Mahasiswa', 'Teman', 'Dokter']],
            ['q' => 'Apa arti dari kata "ともだち"?', 'correct' => 'Teman / Kawan', 'opts' => ['Teman / Kawan', 'Keluarga', 'Musuh', 'Tetangga']],
            ['q' => 'Apa arti dari benda "ほん"?', 'correct' => 'Buku', 'opts' => ['Buku', 'Mobil', 'Rumah', 'Stasiun']],
            ['q' => 'Apa arti dari kata "くるま"?', 'correct' => 'Mobil', 'opts' => ['Mobil', 'Sepeda', 'Kereta', 'Pesawat']],
            ['q' => 'Apa arti dari kata "みず"?', 'correct' => 'Air putih', 'opts' => ['Air putih', 'Teh', 'Kopi', 'Susu']],
            ['q' => 'Apa arti dari kata "ごはん"?', 'correct' => 'Nasi / Makanan', 'opts' => ['Nasi / Makanan', 'Roti', 'Buah', 'Air']],
            ['q' => 'Apa arti dari tempat "がっこう"?', 'correct' => 'Sekolah', 'opts' => ['Sekolah', 'Rumah sakit', 'Toko', 'Stasiun']],
            ['q' => 'Apa arti dari keterangan waktu "きょう"?', 'correct' => 'Hari ini', 'opts' => ['Hari ini', 'Besok', 'Kemarin', 'Lusa']],
            ['q' => 'Apa arti dari kata kerja "たべます"?', 'correct' => 'Makan', 'opts' => ['Minum', 'Makan', 'Tidur', 'Pergi']],
            ['q' => 'Apa arti dari kata kerja "のみます"?', 'correct' => 'Minum', 'opts' => ['Makan', 'Minum', 'Belajar', 'Tidur']],
        ];

        QuizQuestion::where('quiz_id', $quiz3->id)->delete();
        foreach ($vocabQuestions as $i => $q) {
            QuizQuestion::create([
                'quiz_id'        => $quiz3->id,
                'type'           => 'multiple_choice',
                'question'       => $q['q'],
                'options'        => $q['opts'],
                'correct_answer' => $q['correct'],
                'order'          => $i + 1,
            ]);
        }

        // Quiz 4: Kanji N5 Lengkap (20 Soal)
        $quiz4 = Quiz::updateOrCreate(
            ['material_id' => $kanji->id],
            [
                'title'       => 'Quiz Kanji N5 Lengkap',
                'category'    => 'kanji',
                'level'       => 'beginner',
                'description' => 'Uji penguasaan kanji N5: angka, waktu/kalender, unsur alam, orang, arah, kata sifat, dan kata kerja.',
                'time_limit'  => 300,
            ]
        );

        $kanjiQuestions = [
            ['jp' => '日', 'correct' => 'Matahari / Hari',       'opts' => ['Matahari / Hari', 'Bulan', 'Api', 'Air']],
            ['jp' => '月', 'correct' => 'Bulan',                 'opts' => ['Emas', 'Bulan', 'Pohon', 'Orang']],
            ['jp' => '火', 'correct' => 'Api',                   'opts' => ['Air', 'Api', 'Tanah', 'Kayu']],
            ['jp' => '水', 'correct' => 'Air',                   'opts' => ['Api', 'Emas', 'Air', 'Bulan']],
            ['jp' => '木', 'correct' => 'Pohon / Kayu',         'opts' => ['Tanah', 'Pohon / Kayu', 'Api', 'Air']],
            ['jp' => '金', 'correct' => 'Emas / Uang',          'opts' => ['Emas / Uang', 'Perak', 'Besi', 'Batu']],
            ['jp' => '土', 'correct' => 'Tanah',                 'opts' => ['Tanah', 'Api', 'Air', 'Gunung']],
            ['jp' => '山', 'correct' => 'Gunung',                'opts' => ['Gunung', 'Sungai', 'Laut', 'Hutan']],
            ['jp' => '川', 'correct' => 'Sungai',                'opts' => ['Sungai', 'Danau', 'Gunung', 'Hujan']],
            ['jp' => '人', 'correct' => 'Orang / Manusia',       'opts' => ['Tanah', 'Orang / Manusia', 'Api', 'Air']],
            ['jp' => '男', 'correct' => 'Pria / Laki-laki',     'opts' => ['Pria / Laki-laki', 'Wanita', 'Anak', 'Teman']],
            ['jp' => '女', 'correct' => 'Wanita / Perempuan',   'opts' => ['Wanita / Perempuan', 'Pria', 'Ibu', 'Saudara']],
            ['jp' => '目', 'correct' => 'Mata',                  'opts' => ['Mata', 'Telinga', 'Mulut', 'Tangan']],
            ['jp' => '口', 'correct' => 'Mulut',                 'opts' => ['Mulut', 'Mata', 'Kaki', 'Hidung']],
            ['jp' => '手', 'correct' => 'Tangan',                'opts' => ['Tangan', 'Kaki', 'Kepala', 'Punggung']],
            ['jp' => '上', 'correct' => 'Atas / Naik',          'opts' => ['Atas / Naik', 'Bawah', 'Kiri', 'Kanan']],
            ['jp' => '下', 'correct' => 'Bawah / Turun',        'opts' => ['Bawah / Turun', 'Atas', 'Tengah', 'Luar']],
            ['jp' => '大', 'correct' => 'Besar',                 'opts' => ['Besar', 'Kecil', 'Tinggi', 'Murah']],
            ['jp' => '小', 'correct' => 'Kecil',                 'opts' => ['Kecil', 'Besar', 'Sedikit', 'Banyak']],
            ['jp' => '食', 'correct' => 'Makan',                 'opts' => ['Makan', 'Minum', 'Pergi', 'Melihat']],
        ];

        QuizQuestion::where('quiz_id', $quiz4->id)->delete();
        foreach ($kanjiQuestions as $i => $q) {
            QuizQuestion::create([
                'quiz_id'        => $quiz4->id,
                'type'           => 'multiple_choice',
                'question'       => 'Apa arti kanji berikut?',
                'question_jp'    => $q['jp'],
                'options'        => $q['opts'],
                'correct_answer' => $q['correct'],
                'order'          => $i + 1,
            ]);
        }

        // Quiz 5: Pola Kalimat & Tata Bahasa N5 (15 Soal)
        $quiz5 = Quiz::updateOrCreate(
            ['material_id' => $grammar->id],
            [
                'title'       => 'Quiz Pola Kalimat & Tata Bahasa N5',
                'category'    => 'grammar',
                'level'       => 'beginner',
                'description' => 'Uji pemahaman pola kalimat dasar N5: partikel は, を, の, へ, で, bentuk negatif, dan kalimat tanya.',
                'time_limit'  => 300,
            ]
        );

        $grammarQuestions = [
            [
                'q'       => 'Partikel penanda topik kalimat dalam "わたし ___ がくせいです" adalah:',
                'correct' => 'は (wa)',
                'opts'    => ['は (wa)', 'が (ga)', 'を (o)', 'に (ni)'],
            ],
            [
                'q'       => 'Bentuk negatif sopan dari "です" (desu) adalah:',
                'correct' => 'ではありません (dewa arimasen)',
                'opts'    => ['ではありません (dewa arimasen)', 'でした (deshita)', 'ます (masu)', 'ません (masen)'],
            ],
            [
                'q'       => 'Partikel untuk mengubah kalimat pernyataan menjadi kalimat tanya di akhir kalimat adalah:',
                'correct' => 'か (ka)',
                'opts'    => ['か (ka)', 'ね (ne)', 'よ (yo)', 'の (no)'],
            ],
            [
                'q'       => 'Kata tunjuk benda yang berada dekat dengan pembicara adalah:',
                'correct' => 'これ (kore)',
                'opts'    => ['これ (kore)', 'それ (sore)', 'あれ (are)', 'どれ (dore)'],
            ],
            [
                'q'       => 'Kata tunjuk benda yang berada dekat dengan lawan bicara adalah:',
                'correct' => 'それ (sore)',
                'opts'    => ['これ (kore)', 'それ (sore)', 'あれ (are)', 'どれ (dore)'],
            ],
            [
                'q'       => 'Kata tunjuk benda yang berada jauh dari pembicara dan lawan bicara adalah:',
                'correct' => 'あれ (are)',
                'opts'    => ['これ (kore)', 'それ (sore)', 'あれ (are)', 'どれ (dore)'],
            ],
            [
                'q'       => 'Kata tunjuk tempat untuk menunjukkan "di sini" (dekat pembicara) adalah:',
                'correct' => 'ここ (koko)',
                'opts'    => ['ここ (koko)', 'そこ (soko)', 'あそこ (asoko)', 'どこ (doko)'],
            ],
            [
                'q'       => 'Partikel yang digunakan untuk menyatakan kepemilikan seperti "buku saya" (わたし ___ ほん) adalah:',
                'correct' => 'の (no)',
                'opts'    => ['の (no)', 'は (wa)', 'を (o)', 'と (to)'],
            ],
            [
                'q'       => 'Partikel penanda objek penderita langsung dalam kalimat "ごはん ___ たべます" adalah:',
                'correct' => 'を (o)',
                'opts'    => ['を (o)', 'が (ga)', 'は (wa)', 'で (de)'],
            ],
            [
                'q'       => 'Partikel penanda tempat tujuan dalam kalimat "がっこう ___ いきます" adalah:',
                'correct' => 'へ (e) / に (ni)',
                'opts'    => ['へ (e) / に (ni)', 'で (de)', 'を (o)', 'から (kara)'],
            ],
            [
                'q'       => 'Partikel penanda tempat terjadinya aktivitas dalam "いえ ___ べんきょうします" adalah:',
                'correct' => 'で (de)',
                'opts'    => ['で (de)', 'に (ni)', 'へ (e)', 'を (o)'],
            ],
            [
                'q'       => 'Terjemahan bahasa Jepang yang tepat untuk "Ini adalah kamus":',
                'correct' => 'これはじしょです',
                'opts'    => ['これはじしょです', 'それはじしょです', 'あれはじしょです', 'どれはじしょですか'],
            ],
            [
                'q'       => 'Terjemahan yang tepat untuk "Saya bukan dokter":',
                'correct' => 'わたしはいしゃではありません',
                'opts'    => ['わたしはいしゃではありません', 'わたしはいしゃです', 'わたしはいしゃでした', 'わたしはいしゃですか'],
            ],
            [
                'q'       => 'Terjemahan yang tepat untuk kalimat tanya "Apakah kamu orang Jepang?":',
                'correct' => 'あなたはにほんじんですか',
                'opts'    => ['あなたはにほんじんですか', 'わたしはにほんじんです', 'かれはにほんじんですか', 'あなたはにほんじんでした'],
            ],
            [
                'q'       => 'Terjemahan bahasa Jepang yang tepat untuk "Minum air":',
                'correct' => 'みずをのみます',
                'opts'    => ['みずをのみます', 'みずがたべます', 'みずへいきます', 'みずでのみます'],
            ],
        ];

        QuizQuestion::where('quiz_id', $quiz5->id)->delete();
        foreach ($grammarQuestions as $i => $q) {
            QuizQuestion::create([
                'quiz_id'        => $quiz5->id,
                'type'           => 'multiple_choice',
                'question'       => $q['q'],
                'options'        => $q['opts'],
                'correct_answer' => $q['correct'],
                'order'          => $i + 1,
            ]);
        }
    }
}
