<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ConversationController extends Controller
{
    /**
     * Tampilkan katalog skenario percakapan situasional.
     */
    public function index(): View
    {
        $user = Auth::user();
        $scenarios = $this->getScenarios();

        return view('dashboard.conversations.index', [
            'user'      => $user,
            'scenarios' => $scenarios,
        ]);
    }

    /**
     * Tampilkan sesi simulasi percakapan interaktif (Roleplay Chat).
     */
    public function show(string $slug): View
    {
        $user = Auth::user();
        $scenarios = $this->getScenarios();

        $scenario = collect($scenarios)->firstWhere('slug', $slug);

        if (!$scenario) {
            throw new NotFoundHttpException('Skenario percakapan tidak ditemukan.');
        }

        return view('dashboard.conversations.show', [
            'user'     => $user,
            'scenario' => $scenario,
        ]);
    }

    /**
     * Koleksi dataset skenario percakapan situasional bahasa Jepang N5.
     *
     * @return array<int, array<string, mixed>>
     */
    private function getScenarios(): array
    {
        return [
            [
                'slug'           => 'perkenalan-diri',
                'title'          => 'Perkenalan Diri (自己紹介)',
                'japanese_title' => 'はじめまして！自己紹介',
                'category'       => 'Sosial & Sehari-hari',
                'level'          => 'N5',
                'icon'           => 'users',
                'accent_color'   => '#c4102e',
                'description'    => 'Pelajari cara menyapa, memperkenalkan nama, asal negara, dan ungkapan kesopanan saat pertama kali bertemu orang Jepang.',
                'speakers'       => [
                    'left'  => ['name' => 'Tanaka (田中)', 'role' => 'Warga Lokal Jepang', 'avatar' => '田', 'bg' => '#fde8ec', 'color' => '#c4102e'],
                    'right' => ['name' => 'Budi (ブディ)', 'role' => 'Murid dari Indonesia', 'avatar' => 'ブ', 'bg' => '#e8f0fe', 'color' => '#1a73e8'],
                ],
                'key_phrases'    => [
                    ['japanese' => '初めまして', 'romaji' => 'Hajimemashite', 'meaning' => 'Senang bertemu dengan Anda (salam pertama kali)'],
                    ['japanese' => '〜と申します / 〜です', 'romaji' => '... to moushimasu / ... desu', 'meaning' => 'Nama saya adalah ...'],
                    ['japanese' => '〜から来ました', 'romaji' => '... kara kimashita', 'meaning' => 'Saya berasal dari ...'],
                    ['japanese' => 'どうぞよろしくお願いします', 'romaji' => 'Douzo yoroshiku onegaishimasu', 'meaning' => 'Mohon bimbingan dan bantuannya'],
                ],
                'dialogues'      => [
                    [
                        'id'             => 1,
                        'speaker'        => 'left',
                        'character_name' => 'Tanaka',
                        'japanese'       => 'こんにちは！初めまして、田中と申します。',
                        'romaji'         => 'Konnichiwa! Hajimemashite, Tanaka to moushimasu.',
                        'indonesian'     => 'Halo! Salam kenal, nama saya Tanaka.',
                        'audio'          => 'こんにちは！初めまして、田中と申します。',
                    ],
                    [
                        'id'             => 2,
                        'speaker'        => 'right',
                        'character_name' => 'Budi',
                        'japanese'       => '初めまして、田中さん！私はブディです。インドネシアから来ました。',
                        'romaji'         => 'Hajimemashite, Tanaka-san! Watashi wa Budi desu. Indoneshia kara kimashita.',
                        'indonesian'     => 'Salam kenal juga, Tanaka-san! Saya Budi. Saya datang dari Indonesia.',
                        'audio'          => '初めまして、田中さん！私はブディです。インドネシアから来ました。',
                        'quiz'           => [
                            'prompt'      => 'Bagaimana cara Budi membalas salam kenal pertama kali dari Tanaka-san?',
                            'options'     => [
                                ['text' => '初めまして、私はブディです。', 'romaji' => 'Hajimemashite, watashi wa Budi desu.', 'is_correct' => true, 'explanation' => 'Tepat! "Hajimemashite" digunakan saat pertama kali berkenalan.'],
                                ['text' => 'さようなら、ブディです。', 'romaji' => 'Sayounara, Budi desu.', 'is_correct' => false, 'explanation' => '"Sayounara" berarti selamat tinggal, bukan salam perkenalan.'],
                                ['text' => 'おやすみなさい、ブディです。', 'romaji' => 'Oyasuminasai, Budi desu.', 'is_correct' => false, 'explanation' => '"Oyasuminasai" berarti selamat tidur/malam.'],
                            ],
                        ],
                    ],
                    [
                        'id'             => 3,
                        'speaker'        => 'left',
                        'character_name' => 'Tanaka',
                        'japanese'       => 'インドネシアですか！日本語がとても上手ですね。',
                        'romaji'         => 'Indoneshia desu ka! Nihongo ga totemo jouzu desu ne.',
                        'indonesian'     => 'Dari Indonesia ya! Bahasa Jepang Anda sangat pandai ya.',
                        'audio'          => 'インドネシアですか！日本語がとても上手ですね。',
                    ],
                    [
                        'id'             => 4,
                        'speaker'        => 'right',
                        'character_name' => 'Budi',
                        'japanese'       => 'いいえ、まだまだです。今勉強しています。',
                        'romaji'         => 'Iie, madamada desu. Ima benkyou shite imasu.',
                        'indonesian'     => 'Tidak, masih belum pandai. Sekarang saya masih terus belajar.',
                        'audio'          => 'いいえ、まだまだです。今勉強しています。',
                    ],
                    [
                        'id'             => 5,
                        'speaker'        => 'left',
                        'character_name' => 'Tanaka',
                        'japanese'       => '素晴らしいですね。これからよろしくお願いします！',
                        'romaji'         => 'Subarashii desu ne. Kore kara yoroshiku onegaishimasu!',
                        'indonesian'     => 'Luar biasa sekali. Mulai sekarang mohon bantuannya ya!',
                        'audio'          => '素晴らしいですね。これからよろしくお願いします！',
                    ],
                    [
                        'id'             => 6,
                        'speaker'        => 'right',
                        'character_name' => 'Budi',
                        'japanese'       => 'こちらこそ、どうぞよろしくお願いします！',
                        'romaji'         => 'Kochira koso, douzo yoroshiku onegaishimasu!',
                        'indonesian'     => 'Saya juga yang harus berterima kasih, mohon bantuannya!',
                        'audio'          => 'こちらこそ、どうぞよろしくお願いします！',
                    ],
                ],
            ],

            [
                'slug'           => 'restoran-jepang',
                'title'          => 'Memesan Makanan di Restoran (注文)',
                'japanese_title' => 'レストランで注文する',
                'category'       => 'Kuliner & Restoran',
                'level'          => 'N5',
                'icon'           => 'ramen',
                'accent_color'   => '#e65100',
                'description'    => 'Panduan memesan menu, menanyakan rekomendasi koki, memesan minuman, dan meminta tagihan saat makan di restoran Jepang.',
                'speakers'       => [
                    'left'  => ['name' => 'Pelayan (店員)', 'role' => 'Staf Restoran Ramen', 'avatar' => '店', 'bg' => '#fff3e0', 'color' => '#e65100'],
                    'right' => ['name' => 'Pelanggan (客)', 'role' => 'Pengunjung Restoran', 'avatar' => '客', 'bg' => '#e8f5e9', 'color' => '#2e7d32'],
                ],
                'key_phrases'    => [
                    ['japanese' => 'いらっしゃいませ', 'romaji' => 'Irasshaimase', 'meaning' => 'Selamat datang! (sambutan toko)'],
                    ['japanese' => 'メニューをください', 'romaji' => 'Menyuu o kudasai', 'meaning' => 'Tolong berikan buku menu'],
                    ['japanese' => '〜を一つお願いします', 'romaji' => '... o hitotsu onegaishimasu', 'meaning' => 'Tolong satu porsi ...'],
                    ['japanese' => 'お会計をお願いします', 'romaji' => 'Okaikei o onegaishimasu', 'meaning' => 'Tolong tagihannya / bon pembayaran'],
                ],
                'dialogues'      => [
                    [
                        'id'             => 1,
                        'speaker'        => 'left',
                        'character_name' => 'Pelayan',
                        'japanese'       => 'いらっしゃいませ！何名様ですか？',
                        'romaji'         => 'Irasshaimase! Nan-mei sama desu ka?',
                        'indonesian'     => 'Selamat datang! Untuk berapa orang?',
                        'audio'          => 'いらっしゃいませ！何名様ですか？',
                    ],
                    [
                        'id'             => 2,
                        'speaker'        => 'right',
                        'character_name' => 'Pelanggan',
                        'japanese'       => '一人です。こちらの席でいいですか？',
                        'romaji'         => 'Hitori desu. Kochira no seki de ii desu ka?',
                        'indonesian'     => 'Satu orang saja. Boleh duduk di kursi ini?',
                        'audio'          => '一人です。こちらの席でいいですか？',
                    ],
                    [
                        'id'             => 3,
                        'speaker'        => 'left',
                        'character_name' => 'Pelayan',
                        'japanese'       => 'はい、どうぞ！ご注文が決まりましたらお呼びください。',
                        'romaji'         => 'Hai, douzo! Gochuumon ga kimarimashitara oyobi kudasai.',
                        'indonesian'     => 'Ya, silakan! Jika sudah memutuskan pesanan, silakan panggil saya.',
                        'audio'          => 'はい、どうぞ！ご注文が決まりましたらお呼びください。',
                    ],
                    [
                        'id'             => 4,
                        'speaker'        => 'right',
                        'character_name' => 'Pelanggan',
                        'japanese'       => 'すみません！しょうゆラーメンを一つと、お茶をください。',
                        'romaji'         => 'Sumimasen! Shouyu raamen o hitotsu to, ocha o kudasai.',
                        'indonesian'     => 'Permisi! Tolong satu ramen shoyu dan teh hijau satu.',
                        'audio'          => 'すみません！しょうゆラーメンを一つと、お茶をください。',
                        'quiz'           => [
                            'prompt'      => 'Bagaimana cara meminta satu porsi ramen dengan sopan?',
                            'options'     => [
                                ['text' => 'ラーメンを一つお願いします。', 'romaji' => 'Raamen o hitotsu onegaishimasu.', 'is_correct' => true, 'explanation' => 'Benar! "Hitotsu" adalah hitungan untuk satu barang, diakhiri "onegaishimasu".'],
                                ['text' => 'ラーメンはだめです。', 'romaji' => 'Raamen wa dame desu.', 'is_correct' => false, 'explanation' => '"Dame desu" berarti tidak boleh/buruk.'],
                                ['text' => 'ラーメンを食べませんでした。', 'romaji' => 'Raamen o tabemasen deshita.', 'is_correct' => false, 'explanation' => 'Ini bentuk lampau "tidak makan ramen".'],
                            ],
                        ],
                    ],
                    [
                        'id'             => 5,
                        'speaker'        => 'left',
                        'character_name' => 'Pelayan',
                        'japanese'       => 'かしこまりました。少々お待ちください。',
                        'romaji'         => 'Kashikomarimashita. Shou-shou omachi kudasai.',
                        'indonesian'     => 'Baik, dipahami. Mohon tunggu sebentar.',
                        'audio'          => 'かしこまりました。少々お待ちください。',
                    ],
                    [
                        'id'             => 6,
                        'speaker'        => 'right',
                        'character_name' => 'Pelanggan',
                        'japanese'       => 'ごちそうさまでした！お会計をお願いします。',
                        'romaji'         => 'Gochisousama deshita! Okaikei o onegaishimasu.',
                        'indonesian'     => 'Terima kasih atas hidangannya! Tolong minta bon tagihan pembayarannya.',
                        'audio'          => 'ごちそうさまでした！お会計をお願いします。',
                    ],
                ],
            ],

            [
                'slug'           => 'konbini',
                'title'          => 'Belanja di Minimarket / Konbini (コンビニ)',
                'japanese_title' => 'コンビニでお買い物',
                'category'       => 'Belanja & Belanjaan',
                'level'          => 'N5',
                'icon'           => 'store',
                'accent_color'   => '#0288d1',
                'description'    => 'Situasi paling sering ditemui di Jepang: kasir menanyakan pemanasan bento, kantong plastik belanja, sumpit/sendok, dan cara pembayaran.',
                'speakers'       => [
                    'left'  => ['name' => 'Kasir (店員)', 'role' => 'Kasir Konbini', 'avatar' => '店', 'bg' => '#e1f5fe', 'color' => '#0288d1'],
                    'right' => ['name' => 'Pembeli (客)', 'role' => 'Pelanggan Konbini', 'avatar' => '客', 'bg' => '#f3e5f5', 'color' => '#7b1fa2'],
                ],
                'key_phrases'    => [
                    ['japanese' => 'お弁当温めますか？', 'romaji' => 'Obentou atatamemasu ka?', 'meaning' => 'Apakah bentonya ingin dipanaskan di microwave?'],
                    ['japanese' => '袋はご利用ですか？', 'romaji' => 'Fukuro wa go-riyou desu ka?', 'meaning' => 'Apakah membutuhkan kantong plastik?'],
                    ['japanese' => '大丈夫です / お願いします', 'romaji' => 'Daijoubu desu / Onegaishimasu', 'meaning' => 'Tidak perlu / Tolong dipanaskan/diberi kantong'],
                    ['japanese' => 'Suica / カードで払います', 'romaji' => 'Suica / Kaado de haraimasu', 'meaning' => 'Saya bayar dengan kartu Suica / kartu kredit'],
                ],
                'dialogues'      => [
                    [
                        'id'             => 1,
                        'speaker'        => 'left',
                        'character_name' => 'Kasir',
                        'japanese'       => 'いらっしゃいませ。お弁当温めますか？',
                        'romaji'         => 'Irasshaimase. Obentou atatamemasu ka?',
                        'indonesian'     => 'Selamat datang. Apakah bentonya ingin dihangatkan?',
                        'audio'          => 'いらっしゃいませ。お弁当温めますか？',
                    ],
                    [
                        'id'             => 2,
                        'speaker'        => 'right',
                        'character_name' => 'Pembeli',
                        'japanese'       => 'はい、お願いします！',
                        'romaji'         => 'Hai, onegaishimasu!',
                        'indonesian'     => 'Ya, tolong dipanaskan!',
                        'audio'          => 'はい、お願いします！',
                    ],
                    [
                        'id'             => 3,
                        'speaker'        => 'left',
                        'character_name' => 'Kasir',
                        'japanese'       => 'かしこまりました。レジ袋はご利用ですか？',
                        'romaji'         => 'Kashikomarimashita. Reji-bukuro wa go-riyou desu ka?',
                        'indonesian'     => 'Baik dipahami. Apakah Anda membutuhkan kantong plastik belanja?',
                        'audio'          => 'かしこまりました。レジ袋はご利用ですか？',
                    ],
                    [
                        'id'             => 4,
                        'speaker'        => 'right',
                        'character_name' => 'Pembeli',
                        'japanese'       => 'いいえ、袋は結構です。カバンに入れます。',
                        'romaji'         => 'Iie, fukuro wa kekkou desu. Kaban ni iremasu.',
                        'indonesian'     => 'Tidak, kantongnya tidak perlu. Saya masukkan ke tas saja.',
                        'audio'          => 'いいえ、袋は結構です。カバンに入れます。',
                        'quiz'           => [
                            'prompt'      => 'Jika Anda tidak membutuhkan kantong plastik di minimarket, apa yang sebaiknya diucapkan?',
                            'options'     => [
                                ['text' => 'いいえ、大丈夫です（結構です）。', 'romaji' => 'Iie, daijoubu desu (kekkou desu).', 'is_correct' => true, 'explanation' => 'Tepat sekali! "Daijoubu desu" atau "Kekkou desu" cara sopan menolak kantong plastik.'],
                                ['text' => '袋をください。', 'romaji' => 'Fukuro o kudasai.', 'is_correct' => false, 'explanation' => 'Ini artinya "tolong berikan kantong".'],
                                ['text' => '美味しくないです。', 'romaji' => 'Oishikunai desu.', 'is_correct' => false, 'explanation' => 'Ini artinya "tidak enak".'],
                            ],
                        ],
                    ],
                    [
                        'id'             => 5,
                        'speaker'        => 'left',
                        'character_name' => 'Kasir',
                        'japanese'       => '合計で750円になります。お支払いはどうされますか？',
                        'romaji'         => 'Goukei de nanahyaku-gojuu en ni narimasu. O-shiharai wa dou saremasu ka?',
                        'indonesian'     => 'Totalnya jadi 750 yen. Pembayarannya ingin menggunakan apa?',
                        'audio'          => '合計で750円になります。お支払いはどうされますか？',
                    ],
                    [
                        'id'             => 6,
                        'speaker'        => 'right',
                        'character_name' => 'Pembeli',
                        'japanese'       => 'Suicaでお願いします。',
                        'romaji'         => 'Suica de onegaishimasu.',
                        'indonesian'     => 'Tolong pakai kartu Suica.',
                        'audio'          => 'Suicaでお願いします。',
                    ],
                ],
            ],

            [
                'slug'           => 'tanya-arah',
                'title'          => 'Menanyakan Arah & Stasiun Kereta (道案内)',
                'japanese_title' => '駅への行き方を尋ねる',
                'category'       => 'Perjalanan & Transportasi',
                'level'          => 'N5',
                'icon'           => 'map',
                'accent_color'   => '#2e7d32',
                'description'    => 'Keterampilan bertanya stasiun terdekat, memahami petunjuk belok kiri/kanan, lurus terus, dan mengucapkan terima kasih.',
                'speakers'       => [
                    'left'  => ['name' => 'Pejalan Kaki (通行人)', 'role' => 'Warga Lokal', 'avatar' => '通', 'bg' => '#e8f5e9', 'color' => '#2e7d32'],
                    'right' => ['name' => 'Turis (旅行者)', 'role' => 'Wisatawan Asing', 'avatar' => '旅', 'bg' => '#fbe9e7', 'color' => '#d84315'],
                ],
                'key_phrases'    => [
                    ['japanese' => '〜はどこですか？', 'romaji' => '... wa doko desu ka?', 'meaning' => 'Di manakah ... berada?'],
                    ['japanese' => 'まっすぐ行ってください', 'romaji' => 'Massugu itte kudasai', 'meaning' => 'Silakan jalan lurus terus'],
                    ['japanese' => '右 / 左に曲がります', 'romaji' => 'Migi / Hidari ni magarimasu', 'meaning' => 'Belok ke kanan / ke kiri'],
                    ['japanese' => 'どうもありがとうございます', 'romaji' => 'Doumo arigatou gozaimasu', 'meaning' => 'Terima kasih banyak'],
                ],
                'dialogues'      => [
                    [
                        'id'             => 1,
                        'speaker'        => 'right',
                        'character_name' => 'Turis',
                        'japanese'       => 'すみません、新宿駅はどこですか？',
                        'romaji'         => 'Sumimasen, Shinjuku-eki wa doko desu ka?',
                        'indonesian'     => 'Permisi, di manakah Stasiun Shinjuku?',
                        'audio'          => 'すみません、新宿駅はどこですか？',
                    ],
                    [
                        'id'             => 2,
                        'speaker'        => 'left',
                        'character_name' => 'Pejalan Kaki',
                        'japanese'       => 'あ、新宿駅ですね。この道をまっすぐ行ってください。',
                        'romaji'         => 'A, Shinjuku-eki desu ne. Kono michi o massugu itte kudasai.',
                        'indonesian'     => 'Ah, Stasiun Shinjuku ya. Silakan jalan lurus terus di jalan ini.',
                        'audio'          => 'あ、新宿駅ですね。この道をまっすぐ行ってください。',
                    ],
                    [
                        'id'             => 3,
                        'speaker'        => 'right',
                        'character_name' => 'Turis',
                        'japanese'       => 'まっすぐですね。遠いですか？',
                        'romaji'         => 'Massugu desu ne. Tooi desu ka?',
                        'indonesian'     => 'Lurus terus ya. Apakah jauh?',
                        'audio'          => 'まっすぐですね。遠いですか？',
                    ],
                    [
                        'id'             => 4,
                        'speaker'        => 'left',
                        'character_name' => 'Pejalan Kaki',
                        'japanese'       => 'いいえ、遠くないですよ。コンビニの角を右に曲がると、すぐに見えます。歩いて5分くらいです。',
                        'romaji'         => 'Iie, tookunai desu yo. Konbini no kado o migi ni magaru to, sugu ni miemasu. Aruite go-fun kurai desu.',
                        'indonesian'     => 'Tidak, tidak jauh kok. Belok ke kanan di sudut minimarket, langsung kelihatan. Kira-kira 5 menit jalan kaki.',
                        'audio'          => 'いいえ、遠くないですよ。コンビニの角を右に曲がると、すぐに見えます。歩いて5分くらいです。',
                        'quiz'           => [
                            'prompt'      => 'Berdasarkan petunjuk arah, apa yang harus dilakukan di sudut minimarket (コンビニの角)?',
                            'options'     => [
                                ['text' => '右に曲がります（Belok kanan）', 'romaji' => 'Migi ni magarimasu', 'is_correct' => true, 'explanation' => 'Tepat! "Migi" artinya kanan, jadi belok kanan di sudut konbini.'],
                                ['text' => '左に曲がります（Belok kiri）', 'romaji' => 'Hidari ni magarimasu', 'is_correct' => false, 'explanation' => '"Hidari" artinya kiri, petunjuk aslinya adalah "Migi" (kanan).'],
                                ['text' => '止まります（Berhenti）', 'romaji' => 'Tomarimasu', 'is_correct' => false, 'explanation' => '"Tomarimasu" artinya berhenti.'],
                            ],
                        ],
                    ],
                    [
                        'id'             => 5,
                        'speaker'        => 'right',
                        'character_name' => 'Turis',
                        'japanese'       => '分かりました！親切に教えてくれて、どうもありがとうございます！',
                        'romaji'         => 'Wakarimashita! Shinsetsu ni oshiete kurete, doumo arigatou gozaimasu!',
                        'indonesian'     => 'Saya mengerti! Terima kasih banyak sudah berbaik hati memberi tahu arahnya!',
                        'audio'          => '分かりました！親切に教えてくれて、どうもありがとうございます！',
                    ],
                    [
                        'id'             => 6,
                        'speaker'        => 'left',
                        'character_name' => 'Pejalan Kaki',
                        'japanese'       => 'いいえ、どういたしまして。気をつけて行ってらっしゃい！',
                        'romaji'         => 'Iie, douitashimashite. Ki o tsukete itterasshai!',
                        'indonesian'     => 'Sama-sama. Hati-hati di jalan ya!',
                        'audio'          => 'いいえ、どういたしまして。気をつけて行ってらっしゃい！',
                    ],
                ],
            ],
        ];
    }
}

