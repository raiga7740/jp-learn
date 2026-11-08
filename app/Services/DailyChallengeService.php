<?php

namespace App\Services;

use App\Models\DailyActivity;
use App\Models\User;
use Carbon\Carbon;

class DailyChallengeService
{
    /**
     * Tema harian berdasarkan hari dalam seminggu (1 = Senin ... 7 = Minggu).
     */
    public const WEEKLY_THEMES = [
        1 => [
            'theme'       => 'Aktivitas & Rutinitas Pekanan',
            'subtitle'    => 'Buka pekan baru dengan mengasah kosakata kegiatan harian dan partikel waktu.',
            'focus'       => 'Kosakata Kerja & Partikel に / で',
        ],
        2 => [
            'theme'       => 'Fokus Partikel & Posisi Objek',
            'subtitle'    => 'Latih ketelitian membedakan partikel penanda objek, tempat, dan alat.',
            'focus'       => 'Partikel を, で, dan に',
        ],
        3 => [
            'theme'       => 'Kanji Alam, Elemen & Kalender',
            'subtitle'    => 'Perkuat pemahaman goresan dan cara baca Kanji N5 yang sering muncul.',
            'focus'       => 'Onyomi & Kunyomi Kanji Dasar',
        ],
        4 => [
            'theme'       => 'Kata Sifat & Ekspresi Keadaan',
            'subtitle'    => 'Kuasai perubahan bentuk kata sifat -i dan kata sifat -na dalam kalimat.',
            'focus'       => 'Kata Sifat い & な',
        ],
        5 => [
            'theme'       => 'Ungkapan Santun & Ajakan',
            'subtitle'    => 'Siap menyambut akhir pekan dengan pola kalimat percakapan sehari-hari.',
            'focus'       => 'Bentuk ~ましょう dan ~てください',
        ],
        6 => [
            'theme'       => 'Kosakata Tempat & Arah Perpindahan',
            'subtitle'    => 'Eksplorasi kosakata tempat umum dan partikel arah perjalanan.',
            'focus'       => 'Tempat, Arah へ/に行きます, dan Transportasi',
        ],
        7 => [
            'theme'       => 'Evaluasi Pekanan & Refleks Cepat',
            'subtitle'    => 'Tuntaskan akhir pekan dengan kombinasi soal kilat untuk mengamankan streak.',
            'focus'       => 'Review Komprehensif JLPT N5',
        ],
    ];

    /**
     * Bank Soal Kategori 1: Kosakata / Moji (Vocabulary & Readings).
     */
    private const VOCAB_POOL = [
        [
            'id'          => 'voc_01',
            'type'        => 'vocab',
            'type_label'  => 'Kosakata N5',
            'badge'       => 'Misi 1 · Kosakata',
            'prompt'      => 'Pilihlah arti bahasa Indonesia yang tepat untuk kata berikut:',
            'target_word' => 'としょかん (図書館)',
            'romaji'      => 'Toshokan',
            'audio_text'  => 'としょかん',
            'options'     => ['Perpustakaan', 'Rumah Sakit', 'Stasiun Kereta', 'Kantor Pos'],
            'correct'     => 0,
            'explanation' => '「としょかん / 図書館」berarti perpustakaan. Rumah sakit adalah びょういん (byouin), stasiun adalah えき (eki), dan kantor pos adalah ゆうびんきょく (yuubinkyoku).',
        ],
        [
            'id'          => 'voc_02',
            'type'        => 'vocab',
            'type_label'  => 'Kosakata N5',
            'badge'       => 'Misi 1 · Kosakata',
            'prompt'      => 'Pilihlah cara baca (furigana/hiragana) yang benar untuk kata「先生」:',
            'target_word' => '先生 (Guru / Pengajar)',
            'romaji'      => 'Sensei',
            'audio_text'  => 'せんせい',
            'options'     => ['がくせい', 'せんせい', 'いしゃ', 'かいしゃいん'],
            'correct'     => 1,
            'explanation' => '「先生」dibaca「せんせい (sensei)」yang berarti guru/pengajar. がくせい (gakusei) = murid, いしゃ (isha) = dokter, かいしゃいん (kaishain) = pegawai kantor.',
        ],
        [
            'id'          => 'voc_03',
            'type'        => 'vocab',
            'type_label'  => 'Kosakata N5',
            'badge'       => 'Misi 1 · Kosakata',
            'prompt'      => 'Bagaimanakah menyebut "Kemarin" dalam bahasa Jepang?',
            'target_word' => 'Kemarin (Yesterday)',
            'romaji'      => 'Kinou',
            'audio_text'  => 'きのう',
            'options'     => ['きょう (Kyou)', 'きのう (Kinou)', 'あした (Ashita)', 'あさって (Asatte)'],
            'correct'     => 1,
            'explanation' => '「きのう (Kinou)」adalah kemarin. きょう (Kyou) = hari ini, あした (Ashita) = besok, あさって (Asatte) = lusa.',
        ],
        [
            'id'          => 'voc_04',
            'type'        => 'vocab',
            'type_label'  => 'Kosakata N5',
            'badge'       => 'Misi 1 · Kosakata',
            'prompt'      => 'Pilihlah arti kata dari kata kerja「たべます (食べます)」:',
            'target_word' => 'たべます (食べます)',
            'romaji'      => 'Tabemasu',
            'audio_text'  => 'たべます',
            'options'     => ['Minum', 'Makan', 'Melihat', 'Mendengar'],
            'correct'     => 1,
            'explanation' => '「たべます (Tabemasu)」berarti makan. Minum adalah のみます (nomimasu), melihat adalah みます (mimasu), dan mendengar adalah ききます (kikimasu).',
        ],
        [
            'id'          => 'voc_05',
            'type'        => 'vocab',
            'type_label'  => 'Kosakata N5',
            'badge'       => 'Misi 1 · Kosakata',
            'prompt'      => 'Pilihlah kata bahasa Jepang yang berarti "Mobil":',
            'target_word' => 'Mobil (Car)',
            'romaji'      => 'Kuruma',
            'audio_text'  => 'くるま',
            'options'     => ['でんしゃ (Densha)', 'くるま (Kuruma)', 'じてんしゃ (Jitensha)', 'ひこうき (Hikouki)'],
            'correct'     => 1,
            'explanation' => '「くるま (Kuruma)」adalah mobil. でんしゃ (Densha) = kereta listrik, じてんしゃ (Jitensha) = sepeda, ひこうき (Hikouki) = pesawat.',
        ],
        [
            'id'          => 'voc_06',
            'type'        => 'vocab',
            'type_label'  => 'Kosakata N5',
            'badge'       => 'Misi 1 · Kosakata',
            'prompt'      => 'Lawan kata dari「おおきい (Besar)」adalah:',
            'target_word' => 'おおきい (大きい) <-> ?',
            'romaji'      => 'Ookii <-> Chiisai',
            'audio_text'  => 'ちいさい',
            'options'     => ['たかい (Takai)', 'ちいさい (Chiisai)', 'あたらしい (Atarashii)', 'やすい (Yasui)'],
            'correct'     => 1,
            'explanation' => 'Lawan kata dari おおきい (Ookii / besar) adalah「ちいさい (Chiisai / kecil)」. たかい = tinggi/mahal, やすい = murah, あたらしい = baru.',
        ],
        [
            'id'          => 'voc_07',
            'type'        => 'vocab',
            'type_label'  => 'Kosakata N5',
            'badge'       => 'Misi 1 · Kosakata',
            'prompt'      => 'Pilihlah kata yang tepat untuk menyebut "Air Minum":',
            'target_word' => 'Air (Water)',
            'romaji'      => 'Mizu',
            'audio_text'  => 'みず',
            'options'     => ['おちゃ (Ocha)', 'みず (Mizu)', 'ぎゅうにゅう (Gyuunyuu)', 'おゆ (Oyu)'],
            'correct'     => 1,
            'explanation' => '「みず (Mizu)」adalah air (dingin/suhu ruang). おちゃ = teh hijau, ぎゅうにゅう = susu sapi, おゆ = air panas.',
        ],
        [
            'id'          => 'voc_08',
            'type'        => 'vocab',
            'type_label'  => 'Kosakata N5',
            'badge'       => 'Misi 1 · Kosakata',
            'prompt'      => 'Ungkapan salam yang digunakan saat hendak tidur di malam hari adalah:',
            'target_word' => 'Selamat Tidur / Selamat Istirahat',
            'romaji'      => 'Oyasuminasai',
            'audio_text'  => 'おやすみなさい',
            'options'     => ['おはようございます', 'こんばんは', 'おやすみなさい', 'さようなら'],
            'correct'     => 2,
            'explanation' => '「おやすみなさい (Oyasuminasai)」diucapkan sebelum tidur. おはようございます = selamat pagi, こんばんは = selamat malam saat bertemu, さようなら = selamat tinggal.',
        ],
        [
            'id'          => 'voc_09',
            'type'        => 'vocab',
            'type_label'  => 'Kosakata N5',
            'badge'       => 'Misi 1 · Kosakata',
            'prompt'      => 'Pilihlah arti kata dari「つくえ (机)」:',
            'target_word' => 'つくえ (机)',
            'romaji'      => 'Tsukue',
            'audio_text'  => 'つくえ',
            'options'     => ['Kursi', 'Meja', 'Pintu', 'Jendela'],
            'correct'     => 1,
            'explanation' => '「つくえ (Tsukue)」adalah meja belajar/kantor. Kursi = いす (isu), pintu = ドア (doa), jendela = まど (mado).',
        ],
        [
            'id'          => 'voc_10',
            'type'        => 'vocab',
            'type_label'  => 'Kosakata N5',
            'badge'       => 'Misi 1 · Kosakata',
            'prompt'      => 'Kata tanya bahasa Jepang yang berarti "Kapan" adalah:',
            'target_word' => 'Kapan (When)',
            'romaji'      => 'Itsu',
            'audio_text'  => 'いつ',
            'options'     => ['だれ (Dare)', 'どこ (Doko)', 'いつ (Itsu)', 'どうして (Doushite)'],
            'correct'     => 2,
            'explanation' => '「いつ (Itsu)」berarti kapan. だれ = siapa, どこ = di mana, どうして = mengapa/kenapa.',
        ],
        [
            'id'          => 'voc_11',
            'type'        => 'vocab',
            'type_label'  => 'Kosakata N5',
            'badge'       => 'Misi 1 · Kosakata',
            'prompt'      => 'Pilihlah arti dari kata sifat「あつい (暑い)」:',
            'target_word' => 'あつい (暑い)',
            'romaji'      => 'Atsui',
            'audio_text'  => 'あつい',
            'options'     => ['Dingin (cuaca)', 'Panas (cuaca)', 'Hangat', 'Sejuk'],
            'correct'     => 1,
            'explanation' => '「あつい (暑い)」berarti panas (cuaca). Dingin (cuaca) adalah さむい (samui), hangat adalah あたたかい (atatakai), sejuk adalah すずしい (suzushii).',
        ],
        [
            'id'          => 'voc_12',
            'type'        => 'vocab',
            'type_label'  => 'Kosakata N5',
            'badge'       => 'Misi 1 · Kosakata',
            'prompt'      => 'Pilihlah arti dari kata「ともだち (友達)」:',
            'target_word' => 'ともだち (友達)',
            'romaji'      => 'Tomodachi',
            'audio_text'  => 'ともだち',
            'options'     => ['Keluarga', 'Teman / Kawan', 'Tetangga', 'Saudara'],
            'correct'     => 1,
            'explanation' => '「ともだち (Tomodachi)」berarti teman/sahabat. Keluarga adalah かぞく (kazoku), tetangga adalah となり (tonari / orang sebelah).',
        ],
        [
            'id'          => 'voc_13',
            'type'        => 'vocab',
            'type_label'  => 'Kosakata N5',
            'badge'       => 'Misi 1 · Kosakata',
            'prompt'      => 'Angka "7" dalam bahasa Jepang dapat diucapkan sebagai:',
            'target_word' => 'Angka 7',
            'romaji'      => 'Nana / Shichi',
            'audio_text'  => 'なな',
            'options'     => ['ろく (Roku)', 'なな (Nana)', 'はち (Hachi)', 'きゅう (Kyuu)'],
            'correct'     => 1,
            'explanation' => 'Angka 7 diucapkan「なな (Nana)」atau「しち (Shichi)」. ろく = 6, はち = 8, きゅう = 9.',
        ],
        [
            'id'          => 'voc_14',
            'type'        => 'vocab',
            'type_label'  => 'Kosakata N5',
            'badge'       => 'Misi 1 · Kosakata',
            'prompt'      => 'Pilihlah kata yang berarti "Kamar / Ruangan":',
            'target_word' => 'Kamar / Ruangan (Room)',
            'romaji'      => 'Heya',
            'audio_text'  => 'へや',
            'options'     => ['いえ (Ie)', 'へや (Heya)', 'にわ (Niwa)', 'だいどころ (Daidokoro)'],
            'correct'     => 1,
            'explanation' => '「へや (Heya)」adalah kamar/ruangan. いえ = rumah, にわ = halaman/taman, だいどころ = dapur.',
        ],
        [
            'id'          => 'voc_15',
            'type'        => 'vocab',
            'type_label'  => 'Kosakata N5',
            'badge'       => 'Misi 1 · Kosakata',
            'prompt'      => 'Pilihlah arti dari kata kerja「かいます (買います)」:',
            'target_word' => 'かいます (買います)',
            'romaji'      => 'Kaimasu',
            'audio_text'  => 'かいます',
            'options'     => ['Menjual', 'Membeli', 'Menulis', 'Mendengar'],
            'correct'     => 1,
            'explanation' => '「かいます (買います)」berarti membeli. Menjual adalah うります (urimasu), menulis adalah かきます (kakimasu).',
        ],
    ];

    /**
     * Bank Soal Kategori 2: Kanji N5 (Meaning, Readings, Compounds).
     */
    private const KANJI_POOL = [
        [
            'id'          => 'kan_01',
            'type'        => 'kanji',
            'type_label'  => 'Kanji N5',
            'badge'       => 'Misi 2 · Kanji',
            'prompt'      => 'Pilihlah arti dari karakter kanji berikut:',
            'target_word' => '水',
            'romaji'      => 'Mizu / SUI',
            'audio_text'  => 'みず',
            'options'     => ['Api', 'Air', 'Kayu / Pohon', 'Tanah'],
            'correct'     => 1,
            'explanation' => 'Kanji「水」berarti air (Kunyomi: みず / mizu, Onyomi: スイ / sui). Api = 火, kayu = 木, tanah = 土.',
        ],
        [
            'id'          => 'kan_02',
            'type'        => 'kanji',
            'type_label'  => 'Kanji N5',
            'badge'       => 'Misi 2 · Kanji',
            'prompt'      => 'Bagaimanakah cara baca kanji「日」dalam kata「日曜日」?',
            'target_word' => '日曜日 (Hari Minggu)',
            'romaji'      => 'Nichiyoubi',
            'audio_text'  => 'にちようび',
            'options'     => ['にちようび', 'げつようび', 'かようび', 'すいようび'],
            'correct'     => 0,
            'explanation' => '「日曜日」dibaca「にちようび (Nichiyoubi)」yang berarti hari Minggu. Kanji「日」di awal berbunyi にち (nichi) dan di akhir berbunyi び (bi).',
        ],
        [
            'id'          => 'kan_03',
            'type'        => 'kanji',
            'type_label'  => 'Kanji N5',
            'badge'       => 'Misi 2 · Kanji',
            'prompt'      => 'Pilihlah arti dari kanji「山」:',
            'target_word' => '山',
            'romaji'      => 'Yama / SAN',
            'audio_text'  => 'やま',
            'options'     => ['Sungai', 'Gunung', 'Batu', 'Hutan'],
            'correct'     => 1,
            'explanation' => 'Kanji「山」berarti gunung (Kunyomi: やま / yama, Onyomi: サン / san seperti 富士山 / Fujisan). Sungai adalah 川 (kawa).',
        ],
        [
            'id'          => 'kan_04',
            'type'        => 'kanji',
            'type_label'  => 'Kanji N5',
            'badge'       => 'Misi 2 · Kanji',
            'prompt'      => 'Pilihlah kanji yang tepat untuk kata "Orang" (Hito):',
            'target_word' => 'Orang (Hito)',
            'romaji'      => 'Hito / JIN / NIN',
            'audio_text'  => 'ひと',
            'options'     => ['大', '人', '入', '八'],
            'correct'     => 1,
            'explanation' => 'Kanji untuk orang adalah「人 (Hito / JIN / NIN)」. Jangan tertukar dengan 入 (masuk / iru) atau 八 (delapan / hachi).',
        ],
        [
            'id'          => 'kan_05',
            'type'        => 'kanji',
            'type_label'  => 'Kanji N5',
            'badge'       => 'Misi 2 · Kanji',
            'prompt'      => 'Pilihlah cara baca kanji gabungan「日本」:',
            'target_word' => '日本 (Jepang)',
            'romaji'      => 'Nihon / Nippon',
            'audio_text'  => 'にほん',
            'options'     => ['にほん (Nihon)', 'にちほん (Nichihon)', 'ひもと (Himoto)', 'にちぼん (Nichibon)'],
            'correct'     => 0,
            'explanation' => '「日本」dibaca「にほん (Nihon)」atau「にっぽん (Nippon)」yang secara harfiah berarti asal mula matahari (Negeri Sakura / Jepang).',
        ],
        [
            'id'          => 'kan_06',
            'type'        => 'kanji',
            'type_label'  => 'Kanji N5',
            'badge'       => 'Misi 2 · Kanji',
            'prompt'      => 'Pilihlah arti dari kanji「食」dalam kata kerja「食べる」:',
            'target_word' => '食 (食べる)',
            'romaji'      => 'Taberu / SHOKU',
            'audio_text'  => 'たべる',
            'options'     => ['Tidur', 'Makan / Makanan', 'Minum', 'Membeli'],
            'correct'     => 1,
            'explanation' => 'Kanji「食」berarti makan atau makanan (Kunyomi: た・べる / taberu, Onyomi: ショク / shoku seperti dalam 食堂 / shokudou = kantin).',
        ],
        [
            'id'          => 'kan_07',
            'type'        => 'kanji',
            'type_label'  => 'Kanji N5',
            'badge'       => 'Misi 2 · Kanji',
            'prompt'      => 'Pilihlah kanji yang berarti "Bulan" atau "Hari Senin":',
            'target_word' => 'Bulan (Moon / Month)',
            'romaji'      => 'Tsuki / GETSU / GATSU',
            'audio_text'  => 'つき',
            'options'     => ['日', '月', '木', '金'],
            'correct'     => 1,
            'explanation' => 'Kanji「月」berarti bulan (Kunyomi: つき / tsuki, Onyomi: ゲツ / getsu). 日 = matahari, 木 = pohon/kayu, 金 = emas/uang.',
        ],
        [
            'id'          => 'kan_08',
            'type'        => 'kanji',
            'type_label'  => 'Kanji N5',
            'badge'       => 'Misi 2 · Kanji',
            'prompt'      => 'Pilihlah cara baca kanji dari kata「車」:',
            'target_word' => '車',
            'romaji'      => 'Kuruma / SHA',
            'audio_text'  => 'くるま',
            'options'     => ['くるま (Kuruma)', 'ふね (Fune)', 'でんしゃ (Densha)', 'みち (Michi)'],
            'correct'     => 0,
            'explanation' => 'Kanji「車」dibaca「くるま (Kuruma)」yang berarti mobil atau kendaraan beroda (Onyomi: シャ / sha seperti 電車 / densha = kereta).',
        ],
        [
            'id'          => 'kan_09',
            'type'        => 'kanji',
            'type_label'  => 'Kanji N5',
            'badge'       => 'Misi 2 · Kanji',
            'prompt'      => 'Pilihlah arti dari pasangan kanji berlawanan「上」dan「下」:',
            'target_word' => '上 & 下',
            'romaji'      => 'Ue & Shita',
            'audio_text'  => 'うえ と した',
            'options'     => ['Kiri & Kanan', 'Atas & Bawah', 'Depan & Belakang', 'Dalam & Luar'],
            'correct'     => 1,
            'explanation' => '「上 (うえ / Ue)」berarti atas, sedangkan「下 (した / Shita)」berarti bawah.',
        ],
        [
            'id'          => 'kan_10',
            'type'        => 'kanji',
            'type_label'  => 'Kanji N5',
            'badge'       => 'Misi 2 · Kanji',
            'prompt'      => 'Pilihlah arti dari kanji「目」:',
            'target_word' => '目',
            'romaji'      => 'Me / MOKU',
            'audio_text'  => 'め',
            'options'     => ['Mulut', 'Mata', 'Telinga', 'Tangan'],
            'correct'     => 1,
            'explanation' => 'Kanji「目 (め / Me)」berarti mata. Mulut adalah 口 (kuchi), telinga adalah 耳 (mimi), dan tangan adalah 手 (te).',
        ],
        [
            'id'          => 'kan_11',
            'type'        => 'kanji',
            'type_label'  => 'Kanji N5',
            'badge'       => 'Misi 2 · Kanji',
            'prompt'      => 'Pilihlah cara baca kanji dari gabungan「大学」:',
            'target_word' => '大学 (Universitas)',
            'romaji'      => 'Daigaku',
            'audio_text'  => 'だいがく',
            'options'     => ['たいがく', 'だいがく', 'おおがく', 'だいがっこ'],
            'correct'     => 1,
            'explanation' => 'Kanji 大 (besar) + 学 (belajar) membentuk kata「大学 / だいがく (Daigaku)」yang berarti universitas / perguruan tinggi.',
        ],
        [
            'id'          => 'kan_12',
            'type'        => 'kanji',
            'type_label'  => 'Kanji N5',
            'badge'       => 'Misi 2 · Kanji',
            'prompt'      => 'Kanji「休」terbentuk dari radikal orang (人) bersandar di pohon (木). Artinya adalah:',
            'target_word' => '休 (休み / 休みます)',
            'romaji'      => 'Yasumi / KYUU',
            'audio_text'  => 'やすみ',
            'options'     => ['Bekerja', 'Istirahat / Libur', 'Berlari', 'Belajar'],
            'correct'     => 1,
            'explanation' => 'Kanji「休」berarti istirahat atau libur (Kunyomi: やす・む / yasumu, やす・み / yasumi).',
        ],
        [
            'id'          => 'kan_13',
            'type'        => 'kanji',
            'type_label'  => 'Kanji N5',
            'badge'       => 'Misi 2 · Kanji',
            'prompt'      => 'Pilihlah kanji yang berarti "Buku" atau "Asal":',
            'target_word' => 'Buku (Hon)',
            'romaji'      => 'Hon / BON / PON',
            'audio_text'  => 'ほん',
            'options'     => ['体', '本', '木', '休'],
            'correct'     => 1,
            'explanation' => 'Kanji「本 (ほん / Hon)」berarti buku atau asal mula. 体 = tubuh, 木 = pohon.',
        ],
        [
            'id'          => 'kan_14',
            'type'        => 'kanji',
            'type_label'  => 'Kanji N5',
            'badge'       => 'Misi 2 · Kanji',
            'prompt'      => 'Pilihlah cara baca kanji dari kata「行く」:',
            'target_word' => '行く (Pergi)',
            'romaji'      => 'Iku',
            'audio_text'  => 'いく',
            'options'     => ['くる', 'いく', 'みる', 'かえる'],
            'correct'     => 1,
            'explanation' => 'Kanji「行く」dibaca「いく (Iku)」yang berarti pergi. くる (kuru) = datang, かえる (kaeru) = pulang.',
        ],
        [
            'id'          => 'kan_15',
            'type'        => 'kanji',
            'type_label'  => 'Kanji N5',
            'badge'       => 'Misi 2 · Kanji',
            'prompt'      => 'Pilihlah arti dari kanji「川」:',
            'target_word' => '川',
            'romaji'      => 'Kawa / GAWA',
            'audio_text'  => 'かわ',
            'options'     => ['Laut', 'Sungai', 'Danau', 'Gunung'],
            'correct'     => 1,
            'explanation' => 'Kanji「川 (かわ / Kawa)」menggambarkan aliran air yang berarti sungai. Laut adalah 海 (umi), gunung adalah 山 (yama).',
        ],
    ];

    /**
     * Bank Soal Kategori 3: Tata Bahasa & Partikel (Grammar Patterns & Particles).
     */
    private const GRAMMAR_POOL = [
        [
            'id'          => 'gra_01',
            'type'        => 'grammar',
            'type_label'  => 'Partikel & Tata Bahasa',
            'badge'       => 'Misi 3 · Tata Bahasa',
            'prompt'      => 'Lengkapilah kalimat rumpang berikut dengan partikel yang tepat:',
            'target_word' => 'わたしは パン [ ? ] たべます。',
            'romaji'      => 'Watashi wa pan [ ? ] tabemasu.',
            'audio_text'  => 'わたしはパンをたべます',
            'options'     => ['は (wa)', 'を (o)', 'に (ni)', 'で (de)'],
            'correct'     => 1,
            'explanation' => 'Partikel「を (o)」digunakan sebagai penanda objek penderita dari kata kerja transitif (makan apa? -> makan roti: パンを食べます).',
        ],
        [
            'id'          => 'gra_02',
            'type'        => 'grammar',
            'type_label'  => 'Partikel & Tata Bahasa',
            'badge'       => 'Misi 3 · Tata Bahasa',
            'prompt'      => 'Pilihlah partikel penanda tempat dilakukannya suatu aktivitas aksi:',
            'target_word' => 'としょかんで 本を よみます。',
            'romaji'      => 'Toshokan [ ? ] hon o yomimasu.',
            'audio_text'  => 'としょかんでほんをよみます',
            'options'     => ['に (ni)', 'で (de)', 'へ (e)', 'を (o)'],
            'correct'     => 1,
            'explanation' => 'Partikel「で (de)」digunakan untuk menandai tempat terjadinya aktivitas aktif (membaca buku di perpustakaan = 図書館で本を読みます). Partikel に untuk keberadaan pasif (います/あります).',
        ],
        [
            'id'          => 'gra_03',
            'type'        => 'grammar',
            'type_label'  => 'Partikel & Tata Bahasa',
            'badge'       => 'Misi 3 · Tata Bahasa',
            'prompt'      => 'Pilihlah partikel penanda waktu tertentu saat kegiatan dilakukan:',
            'target_word' => '毎朝 6時 [ ? ] おきます。',
            'romaji'      => 'Maiasa roku-ji [ ? ] okimasu.',
            'audio_text'  => 'まいあさろくじにおきます',
            'options'     => ['に (ni)', 'で (de)', 'を (o)', 'と (to)'],
            'correct'     => 0,
            'explanation' => 'Partikel「に (ni)」menempel pada waktu spesifik yang memuat angka (seperti jam, tanggal, bulan, atau hari) untuk menunjukkan titik waktu aksi dilakukan (6時に起きます = bangun pada jam 6).',
        ],
        [
            'id'          => 'gra_04',
            'type'        => 'grammar',
            'type_label'  => 'Partikel & Tata Bahasa',
            'badge'       => 'Misi 3 · Tata Bahasa',
            'prompt'      => 'Bentuk lampau sopan (past tense) dari kalimat「たべます (Makan)」adalah:',
            'target_word' => 'たべます (Sekarang/Akan) -> Lampau?',
            'romaji'      => 'Tabemasu -> Tabemashita',
            'audio_text'  => 'たべました',
            'options'     => ['たべません', 'たべました', 'たべたいです', 'たべませんでした'],
            'correct'     => 1,
            'explanation' => 'Bentuk lampau positif dari akhiran ~ます adalah「~ました (~mashita)」. Jadi たべます menjadi「たべました (sudah makan)」. たべませんでした = lampau negatif (tidak makan).',
        ],
        [
            'id'          => 'gra_05',
            'type'        => 'grammar',
            'type_label'  => 'Partikel & Tata Bahasa',
            'badge'       => 'Misi 3 · Tata Bahasa',
            'prompt'      => 'Lengkapilah kalimat permohonan "Tolong dengarkan" dalam bahasa Jepang:',
            'target_word' => 'CDを きいて [ ? ]。',
            'romaji'      => 'CD o kiite [ ? ].',
            'audio_text'  => 'しーでぃーをきいてください',
            'options'     => ['ください (kudasai)', 'います (imasu)', 'もいいです (mo ii desu)', 'から (kara)'],
            'correct'     => 0,
            'explanation' => 'Pola「Kata Kerja Bentuk-Te + ください (Kudasai)」digunakan untuk meminta tolong atau instruksi sopan (きいてください = tolong dengarkan).',
        ],
        [
            'id'          => 'gra_06',
            'type'        => 'grammar',
            'type_label'  => 'Partikel & Tata Bahasa',
            'badge'       => 'Misi 3 · Tata Bahasa',
            'prompt'      => 'Pilihlah partikel penanda alat atau moda transportasi yang digunakan:',
            'target_word' => 'バス [ ? ] 学校へ 行きます。',
            'romaji'      => 'Basu [ ? ] gakkou e ikimasu.',
            'audio_text'  => 'ばすでがっこうへいきます',
            'options'     => ['に (ni)', 'で (de)', 'と (to)', 'を (o)'],
            'correct'     => 1,
            'explanation' => 'Partikel「で (de)」selain penanda tempat aksi juga berfungsi sebagai penanda alat / moda transportasi (バスで行きます = pergi naik bus; はしで食べます = makan menggunakan sumpit).',
        ],
        [
            'id'          => 'gra_07',
            'type'        => 'grammar',
            'type_label'  => 'Partikel & Tata Bahasa',
            'badge'       => 'Misi 3 · Tata Bahasa',
            'prompt'      => 'Pola kalimat untuk menyatakan ajakan "Ayo makan bersama" adalah:',
            'target_word' => 'いっしょに たべ [ ? ]。',
            'romaji'      => 'Issho ni tabe [ ? ].',
            'audio_text'  => 'いっしょにたべましょう',
            'options'     => ['ます (masu)', 'ましょう (mashou)', 'ません (masen)', 'たいです (tai desu)'],
            'correct'     => 1,
            'explanation' => 'Pola「~ましょう (~mashou)」adalah bentuk ajakan antusias ("Ayo kita..."). Jadi「いっしょに食べましょう」berarti "Ayo makan bersama!".',
        ],
        [
            'id'          => 'gra_08',
            'type'        => 'grammar',
            'type_label'  => 'Partikel & Tata Bahasa',
            'badge'       => 'Misi 3 · Tata Bahasa',
            'prompt'      => 'Partikel yang digunakan untuk menyatakan arti "Dan" atau "Bersama dengan" adalah:',
            'target_word' => 'ともだち [ ? ] えいがを 見ました。',
            'romaji'      => 'Tomodachi [ ? ] eiga o mimashita.',
            'audio_text'  => 'ともだちとえいがをみました',
            'options'     => ['と (to)', 'に (ni)', 'で (de)', 'も (mo)'],
            'correct'     => 0,
            'explanation' => 'Partikel「と (to)」berfungsi menghubungkan benda ("dan") atau menandai rekan melakukan sesuatu ("bersama"): 友達と = bersama teman.',
        ],
        [
            'id'          => 'gra_09',
            'type'        => 'grammar',
            'type_label'  => 'Partikel & Tata Bahasa',
            'badge'       => 'Misi 3 · Tata Bahasa',
            'prompt'      => 'Lengkapilah kalimat kepemilikan "Ini adalah buku milik saya":',
            'target_word' => 'これは わたし [ ? ] 本です。',
            'romaji'      => 'Kore wa watashi [ ? ] hon desu.',
            'audio_text'  => 'これはわたしのほんです',
            'options'     => ['の (no)', 'は (wa)', 'が (ga)', 'を (o)'],
            'correct'     => 0,
            'explanation' => 'Partikel「の (no)」berfungsi sebagai penanda kepemilikan atau modifikasi kata benda (わたしの本 = buku saya / buku milik saya).',
        ],
        [
            'id'          => 'gra_10',
            'type'        => 'grammar',
            'type_label'  => 'Partikel & Tata Bahasa',
            'badge'       => 'Misi 3 · Tata Bahasa',
            'prompt'      => 'Partikel yang berarti "Juga" (menggantikan は atau を) adalah:',
            'target_word' => '田中さんも 日本人です。(Tanaka-san [ ? ] orang Jepang)',
            'romaji'      => 'Tanaka-san mo Nihonjin desu.',
            'audio_text'  => 'たなかさんもにほんじんです',
            'options'     => ['は (wa)', 'も (mo)', 'が (ga)', 'と (to)'],
            'correct'     => 1,
            'explanation' => 'Partikel「も (mo)」berarti "juga" atau "pun". Digunakan saat predikatnya sama dengan hal yang disebutkan sebelumnya.',
        ],
        [
            'id'          => 'gra_11',
            'type'        => 'grammar',
            'type_label'  => 'Partikel & Tata Bahasa',
            'badge'       => 'Misi 3 · Tata Bahasa',
            'prompt'      => 'Pilihlah bentuk negatif dari kata sifat-i「あつい (Panas)」:',
            'target_word' => 'あつい (Panas) -> Tidak Panas?',
            'romaji'      => 'Atsui -> Atsukunai',
            'audio_text'  => 'あつくないです',
            'options'     => ['あつくないです', 'あついです', 'あつくありませんでした', 'あつかったです'],
            'correct'     => 0,
            'explanation' => 'Kata sifat berakhiran -i diubah ke bentuk negatif dengan mencoret huruf ~い lalu diganti「~くない (~kunai)」. Jadi あつい ->「あつくない (atsukunai)」.',
        ],
        [
            'id'          => 'gra_12',
            'type'        => 'grammar',
            'type_label'  => 'Partikel & Tata Bahasa',
            'badge'       => 'Misi 3 · Tata Bahasa',
            'prompt'      => 'Untuk menyatakan keberadaan benda mati (buku, meja, uang), kata kerja yang digunakan adalah:',
            'target_word' => 'あそこに つくえが [ ? ]。',
            'romaji'      => 'Asoko ni tsukue ga [ ? ].',
            'audio_text'  => 'あそこにつくえがあります',
            'options'     => ['います (imasu)', 'あります (arimasu)', 'します (shimasu)', 'いきます (ikimasu)'],
            'correct'     => 1,
            'explanation' => 'Untuk benda mati atau tanaman digunakan「あります (arimasu)」. Kata「います (imasu)」khusus untuk makhluk hidup bernyawa (manusia dan hewan).',
        ],
        [
            'id'          => 'gra_13',
            'type'        => 'grammar',
            'type_label'  => 'Partikel & Tata Bahasa',
            'badge'       => 'Misi 3 · Tata Bahasa',
            'prompt'      => 'Pola kalimat untuk mengungkapkan keinginan diri sendiri "Saya ingin minum air" adalah:',
            'target_word' => '水が のみ [ ? ]。',
            'romaji'      => 'Mizu ga nomi [ ? ].',
            'audio_text'  => 'みずがのみたいです',
            'options'     => ['たいです (tai desu)', 'ました (mashita)', 'ません (masen)', 'てください (te kudasai)'],
            'correct'     => 0,
            'explanation' => 'Pola「Kata Kerja Stem-Masu + たいです (tai desu)」digunakan untuk menyatakan keinginan orang pertama ("ingin melakukan..."). のみます ->「のみたいです (ingin minum)」.',
        ],
        [
            'id'          => 'gra_14',
            'type'        => 'grammar',
            'type_label'  => 'Partikel & Tata Bahasa',
            'badge'       => 'Misi 3 · Tata Bahasa',
            'prompt'      => 'Lengkapilah pasangan partikel untuk rentang waktu "Dari jam 9 sampai jam 5":',
            'target_word' => '9時 [ ? ] 5時 [ ? ] はたらきます。',
            'romaji'      => '9-ji [ ? ] 5-ji [ ? ] hatarakimasu.',
            'audio_text'  => 'くじからごじまではたらきます',
            'options'     => ['から ... まで', 'に ... で', 'と ... を', 'へ ... に'],
            'correct'     => 0,
            'explanation' => 'Pasangan partikel「から (kara = dari)」dan「まで (made = sampai)」menunjukkan batas awal dan batas akhir waktu atau tempat.',
        ],
        [
            'id'          => 'gra_15',
            'type'        => 'grammar',
            'type_label'  => 'Partikel & Tata Bahasa',
            'badge'       => 'Misi 3 · Tata Bahasa',
            'prompt'      => 'Pilihlah bentuk izin sopan "Bolehkah saya mengambil foto di sini?":',
            'target_word' => 'しゃしんを とって [ ? ]。',
            'romaji'      => 'Shashin o totte [ ? ].',
            'audio_text'  => 'しゃしんをとってもいいですか',
            'options'     => ['もいいですか (mo ii desu ka)', 'ください (kudasai)', 'はいけません (wa ikemasen)', 'たいです (tai desu)'],
            'correct'     => 0,
            'explanation' => 'Pola「Kata Kerja Bentuk-Te + もいいですか (mo ii desu ka)」digunakan untuk meminta izin ("Bolehkah saya...?").',
        ],
    ];

    /**
     * Dapatkan paket tantangan harian untuk tanggal tertentu (default: hari ini di WIB).
     *
     * @param string|null $dateString Format 'YYYY-MM-DD'
     * @return array<string, mixed>
     */
    public function getChallengesForDate(?string $dateString = null): array
    {
        $timezone = GamificationService::TIMEZONE;
        $carbonDate = $dateString
            ? Carbon::parse($dateString, $timezone)
            : Carbon::now($timezone);

        $dateKey = $carbonDate->toDateString();
        $dayOfWeek = $carbonDate->isoWeekday(); // 1 = Senin ... 7 = Minggu
        $dayOfYear = $carbonDate->dayOfYear;
        $year = $carbonDate->year;

        // Formula seed deterministik per hari
        $seed = ($year * 1000) + $dayOfYear;

        // Pilih soal 1 (Kosakata)
        $vocabCount = count(self::VOCAB_POOL);
        $vocabIndex = ($seed * 7 + 3) % $vocabCount;
        $vocabQuestion = self::VOCAB_POOL[$vocabIndex];

        // Pilih soal 2 (Kanji)
        $kanjiCount = count(self::KANJI_POOL);
        $kanjiIndex = ($seed * 13 + 5) % $kanjiCount;
        $kanjiQuestion = self::KANJI_POOL[$kanjiIndex];

        // Pilih soal 3 (Tata Bahasa)
        $grammarCount = count(self::GRAMMAR_POOL);
        $grammarIndex = ($seed * 19 + 11) % $grammarCount;
        $grammarQuestion = self::GRAMMAR_POOL[$grammarIndex];

        $missions = [
            array_merge($vocabQuestion, ['mission_number' => 1]),
            array_merge($kanjiQuestion, ['mission_number' => 2]),
            array_merge($grammarQuestion, ['mission_number' => 3]),
        ];

        // Tema harian
        $themeInfo = self::WEEKLY_THEMES[$dayOfWeek] ?? self::WEEKLY_THEMES[7];

        // Format tanggal bahasa Indonesia
        $formattedDate = $this->formatIndonesianDate($carbonDate);

        // Hitung sisa waktu menuju jam 00:00 WIB berikutnya (reset)
        $nextMidnight = $carbonDate->copy()->endOfDay();
        $secondsToReset = (int) max(0, Carbon::now($timezone)->diffInSeconds($nextMidnight, false));

        return [
            'date'              => $dateKey,
            'date_formatted'    => $formattedDate,
            'day_name'          => $this->getIndonesianDayName($dayOfWeek),
            'theme'             => $themeInfo['theme'],
            'subtitle'          => $themeInfo['subtitle'],
            'focus'             => $themeInfo['focus'],
            'missions'          => $missions,
            'total_missions'    => 3,
            'bonus_xp'          => 30,
            'seconds_to_reset'  => $secondsToReset,
        ];
    }

    /**
     * Periksa status tantangan harian pengguna untuk tanggal tertentu.
     */
    public function checkDailyStatus(User $user, ?string $dateString = null): array
    {
        $timezone = GamificationService::TIMEZONE;
        $today = $dateString ?? Carbon::now($timezone)->toDateString();

        $activity = DailyActivity::query()
            ->where('user_id', $user->id)
            ->whereDate('activity_date', $today)
            ->first();

        $actions = $activity?->actions ?? [];
        $isCompleted = (int) ($actions['daily_challenge_complete'] ?? 0) > 0;

        return [
            'date'         => $today,
            'is_completed' => $isCompleted,
            'xp_earned'    => $isCompleted ? 30 : 0,
            'user_streak'  => $user->current_streak,
        ];
    }

    /**
     * Format tanggal Indonesia tanpa ketergantungan library eksternal.
     */
    private function formatIndonesianDate(Carbon $date): string
    {
        $days = [
            1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis',
            5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu',
        ];

        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        $dayName = $days[$date->isoWeekday()] ?? 'Hari Ini';
        $dayNum = $date->day;
        $monthName = $months[$date->month] ?? '';
        $year = $date->year;

        return "{$dayName}, {$dayNum} {$monthName} {$year}";
    }

    /**
     * Dapatkan nama hari bahasa Indonesia.
     */
    private function getIndonesianDayName(int $dayOfWeek): string
    {
        $days = [
            1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis',
            5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu',
        ];

        return $days[$dayOfWeek] ?? 'Hari Ini';
    }
}

