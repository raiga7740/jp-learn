<?php

namespace App\Services;

use App\Models\JlptExamResult;
use App\Models\User;

class JlptExamService
{
    public function __construct(
        public GamificationService $gamificationService,
    ) {
    }

    /**
     * Konfigurasi umum simulasi ujian JLPT N5.
     *
     * @return array{title: string, duration_seconds: int, total_questions: int, max_score: int, passing_score: int}
     */
    public function getExamConfig(): array
    {
        return [
            'title'            => 'Simulasi Ujian Resmi JLPT N5 (Mock Exam)',
            'duration_seconds' => 1800, // 30 menit
            'total_questions'  => 30,
            'max_score'        => 180,
            'passing_score'    => 80,
        ];
    }

    /**
     * Seluruh 30 Soal Terkurasi Format Resmi Ujian JLPT N5.
     *
     * @return array<int, array{id: int, section: string, section_label: string, mondai: string, mondai_desc: string, passage: ?string, question: string, options: array<int, string>, correct_answer: int, explanation: string, points: int}>
     */
    public function getQuestions(): array
    {
        return [
            // ══════════════════════════════════════════════════════════════════
            // SEKSI 1: 文字・語彙 (Huruf, Kosakata & Kanji - 12 Soal)
            // ══════════════════════════════════════════════════════════════════

            // Mondai 1: Cara baca Kanji ke Hiragana
            [
                'id'            => 1,
                'section'       => 'vocabulary',
                'section_label' => '文字・語彙 (Huruf & Kosakata)',
                'mondai'        => '問題 1',
                'mondai_desc'   => '＿＿＿の ことばは ひらがなで どう かきますか。いちばん いい ものを ひとつ えらんで ください。',
                'passage'       => null,
                'question'      => 'あした ともだちと <u>学校</u>へ いきます。',
                'options'       => ['がっこう', 'かっこう', 'がこう', 'かっこ'],
                'correct_answer'=> 0,
                'explanation'   => 'Kanji 「学校」 dibaca 「がっこう」 (gakkou) yang berarti "sekolah". Kalimat berarti: "Besok saya pergi ke sekolah bersama teman."',
                'points'        => 6,
            ],
            [
                'id'            => 2,
                'section'       => 'vocabulary',
                'section_label' => '文字・語彙 (Huruf & Kosakata)',
                'mondai'        => '問題 1',
                'mondai_desc'   => '＿＿＿の ことばは ひらがなで どう かきますか。いちばん いい ものを ひとつ えらんで ください。',
                'passage'       => null,
                'question'      => 'きのう <u>新しい</u> くるまを かいました。',
                'options'       => ['ふるい', 'あたらしい', 'あたらし', 'ただしい'],
                'correct_answer'=> 1,
                'explanation'   => 'Kanji 「新しい」 dibaca 「あたらしい」 (atarashii) yang berarti "baru". Kalimat berarti: "Kemarin saya membeli mobil baru."',
                'points'        => 6,
            ],
            [
                'id'            => 3,
                'section'       => 'vocabulary',
                'section_label' => '文字・語彙 (Huruf & Kosakata)',
                'mondai'        => '問題 1',
                'mondai_desc'   => '＿＿＿の ことばは ひらがなで どう かきますか。いちばん いい ものを ひとつ えらんで ください。',
                'passage'       => null,
                'question'      => 'まいにち つめたい <u>水</u>を のみます。',
                'options'       => ['おちゃ', 'さけ', 'みず', 'ゆ'],
                'correct_answer'=> 2,
                'explanation'   => 'Kanji 「水」 dibaca 「みず」 (mizu) yang berarti "air putih dingin". Kalimat berarti: "Setiap hari saya minum air dingin."',
                'points'        => 6,
            ],
            [
                'id'            => 4,
                'section'       => 'vocabulary',
                'section_label' => '文字・語彙 (Huruf & Kosakata)',
                'mondai'        => '問題 1',
                'mondai_desc'   => '＿＿＿の ことばは ひらがなで どう かきますか。いちばん いい ものを ひとつ えらんで ください。',
                'passage'       => null,
                'question'      => 'へやの なかに だれも <u>いません</u>。',
                'options'       => ['いりません', 'いません', 'いきません', 'ありません'],
                'correct_answer'=> 1,
                'explanation'   => 'Kata kerja 「いません」 (imasen) digunakan untuk keberadaan makhluk bernyawa (orang/hewan) bentuk negatif: "Di dalam kamar tidak ada siapa-siapa."',
                'points'        => 6,
            ],

            // Mondai 2: Menuliskan Hiragana ke Kanji yang tepat
            [
                'id'            => 5,
                'section'       => 'vocabulary',
                'section_label' => '文字・語彙 (Huruf & Kosakata)',
                'mondai'        => '問題 2',
                'mondai_desc'   => '＿＿＿の ことばは どう かきますか。いちばん いい ものを ひとつ えらんで ください。',
                'passage'       => null,
                'question'      => 'あさごはんを <u>たべます</u>。',
                'options'       => ['飲みます', '見ます', '食べます', '行きます'],
                'correct_answer'=> 2,
                'explanation'   => '「たべます」 ditulis dengan kanji 「食べます」 (makan). Kalimat berarti: "Saya sarapan / makan pagi."',
                'points'        => 6,
            ],
            [
                'id'            => 6,
                'section'       => 'vocabulary',
                'section_label' => '文字・語彙 (Huruf & Kosakata)',
                'mondai'        => '問題 2',
                'mondai_desc'   => '＿＿＿の ことばは どう かきますか。いちばん いい ものを ひとつ えらんで ください。',
                'passage'       => null,
                'question'      => 'わたしの たんじょうびは <u>ごがつ</u> むいか です。',
                'options'       => ['五月', '四月', '三月', '六月'],
                'correct_answer'=> 0,
                'explanation'   => '「ごがつ」 adalah bulan kelima (Mei) yang ditulis dengan kanji 「五月」 (angka 5 + bulan).',
                'points'        => 6,
            ],
            [
                'id'            => 7,
                'section'       => 'vocabulary',
                'section_label' => '文字・語彙 (Huruf & Kosakata)',
                'mondai'        => '問題 2',
                'mondai_desc'   => '＿＿＿の ことばは どう かきますか。いちばん いい ものを ひとつ えらんで ください。',
                'passage'       => null,
                'question'      => 'としょかんで <u>ほん</u>を かりましました。',
                'options'       => ['木', '本', '体', '休'],
                'correct_answer'=> 1,
                'explanation'   => '「ほん」 (buku) ditulis dengan kanji 「本」.',
                'points'        => 6,
            ],
            [
                'id'            => 8,
                'section'       => 'vocabulary',
                'section_label' => '文字・語彙 (Huruf & Kosakata)',
                'mondai'        => '問題 2',
                'mondai_desc'   => '＿＿＿の ことばは どう かきますか。いちばん いい ものを ひとつ えらんで ください。',
                'passage'       => null,
                'question'      => 'あそこの <u>みせ</u>で パンを かいました。',
                'options'       => ['店', '屋', '所', '町'],
                'correct_answer'=> 0,
                'explanation'   => '「みせ」 (toko) ditulis dengan kanji 「店」.',
                'points'        => 6,
            ],

            // Mondai 3: Kosakata kontekstual dalam kalimat
            [
                'id'            => 9,
                'section'       => 'vocabulary',
                'section_label' => '文字・語彙 (Huruf & Kosakata)',
                'mondai'        => '問題 3',
                'mondai_desc'   => '（ ）に なにを いれますか。いちばん いい ものを ひとつ えらんで ください。',
                'passage'       => null,
                'question'      => 'あさ 起きたとき、かぞくに 「（ ）」 と いいます。',
                'options'       => ['こんばんは', 'さようなら', 'おはようございます', 'いただきます'],
                'correct_answer'=> 2,
                'explanation'   => 'Saat bangun di pagi hari, salam yang diucapkan kepada keluarga adalah 「おはようございます」 (Selamat pagi).',
                'points'        => 6,
            ],
            [
                'id'            => 10,
                'section'       => 'vocabulary',
                'section_label' => '文字・語彙 (Huruf & Kosakata)',
                'mondai'        => '問題 3',
                'mondai_desc'   => '（ ）に なにを いれますか。いちばん いい ものを ひとつ えらんで ください。',
                'passage'       => null,
                'question'      => 'この りょうりは とても （ ）ですから、たくさん たべました。',
                'options'       => ['からい', 'おいしい', 'まずい', 'あぶない'],
                'correct_answer'=> 1,
                'explanation'   => 'Karena makanannya sangat enak (おいしい - oishii), saya makan banyak.',
                'points'        => 6,
            ],
            [
                'id'            => 11,
                'section'       => 'vocabulary',
                'section_label' => '文字・語彙 (Huruf & Kosakata)',
                'mondai'        => '問題 3',
                'mondai_desc'   => '（ ）に なにを いれますか。いちばん いい ものを ひとつ えらんで ください。',
                'passage'       => null,
                'question'      => 'にもつが おもいですから、タクシーに （ ）。',
                'options'       => ['のりました', 'あがりました', 'とまりました', 'おりました'],
                'correct_answer'=> 0,
                'explanation'   => 'Untuk naik kendaraan digunakan kata kerja 「のります」 (partikel に + のる). Bentuk lampau: 「のりました」 (naik taksi).',
                'points'        => 6,
            ],
            [
                'id'            => 12,
                'section'       => 'vocabulary',
                'section_label' => '文字・語彙 (Huruf & Kosakata)',
                'mondai'        => '問題 3',
                'mondai_desc'   => '（ ）に なにを いれますか。いちばん いい ものを ひとつ えらんで ください。',
                'passage'       => null,
                'question'      => 'わたしの へやは あまり （ ）ないです。',
                'options'       => ['ひろく', 'ひろい', 'ひろ', 'ひろくて'],
                'correct_answer'=> 0,
                'explanation'   => 'Bentuk negatif dari kata sifat-i 「ひろい」 (luas) adalah mengganti -i menjadi -ku nai: 「ひろくない」. Kalimat: "Kamar saya tidak begitu luas."',
                'points'        => 6,
            ],

            // ══════════════════════════════════════════════════════════════════
            // SEKSI 2: 文法 (Tata Bahasa / Grammar - 12 Soal)
            // ══════════════════════════════════════════════════════════════════

            // Mondai 4: Partikel (Joshi)
            [
                'id'            => 13,
                'section'       => 'grammar',
                'section_label' => '文法 (Tata Bahasa)',
                'mondai'        => '問題 4',
                'mondai_desc'   => '（ ）に なにを いれますか。いちばん いい ものを ひとつ えらんで ください。',
                'passage'       => null,
                'question'      => 'わたしは レストラン（ ） ひるごはんを たべました。',
                'options'       => ['に', 'で', 'へ', 'を'],
                'correct_answer'=> 1,
                'explanation'   => 'Partikel 「で」 (de) digunakan untuk menandai tempat berlangsungnya suatu aktivitas/kegiatan. "Makan siang di restoran" -> レストランで たべました。',
                'points'        => 6,
            ],
            [
                'id'            => 14,
                'section'       => 'grammar',
                'section_label' => '文法 (Tata Bahasa)',
                'mondai'        => '問題 4',
                'mondai_desc'   => '（ ）に なにを いれますか。いちばん いい ものを ひとつ えらんで ください。',
                'passage'       => null,
                'question'      => 'あした ともだち（ ） えいがを 見に いきます。',
                'options'       => ['と', 'に', 'も', 'で'],
                'correct_answer'=> 0,
                'explanation'   => 'Partikel 「と」 (to) digunakan untuk menyatakan "bersama dengan". "Pergi menonton bioskop bersama teman" -> ともだちと いきます。',
                'points'        => 6,
            ],
            [
                'id'            => 15,
                'section'       => 'grammar',
                'section_label' => '文法 (Tata Bahasa)',
                'mondai'        => '問題 4',
                'mondai_desc'   => '（ ）に なにを いれますか。いちばん いい ものを ひとつ えらんで ください。',
                'passage'       => null,
                'question'      => '毎朝 ７時（ ） おきます。',
                'options'       => ['を', 'に', 'で', 'が'],
                'correct_answer'=> 1,
                'explanation'   => 'Partikel 「に」 (ni) digunakan untuk menandai waktu spesifik dilakukannya suatu tindakan: ７時に おきます (bangun pada jam 7).',
                'points'        => 6,
            ],
            [
                'id'            => 16,
                'section'       => 'grammar',
                'section_label' => '文法 (Tata Bahasa)',
                'mondai'        => '問題 4',
                'mondai_desc'   => '（ ）に なにを いれますか。いちばん いい ものを ひとつ えらんで ください。',
                'passage'       => null,
                'question'      => 'これは にほんご（ ） かいて ください。',
                'options'       => ['で', 'に', 'を', 'から'],
                'correct_answer'=> 0,
                'explanation'   => 'Partikel 「で」 (de) digunakan untuk menyatakan sarana/alat/bahasa yang digunakan: にほんごで (dalam bahasa Jepang).',
                'points'        => 6,
            ],
            [
                'id'            => 17,
                'section'       => 'grammar',
                'section_label' => '文法 (Tata Bahasa)',
                'mondai'        => '問題 4',
                'mondai_desc'   => '（ ）に なにを いれますか。いちばん いい ものを ひとつ えらんで ください。',
                'passage'       => null,
                'question'      => 'いえ（ ） えきまで あるいて １０ぷん かかります。',
                'options'       => ['まで', 'から', 'に', 'で'],
                'correct_answer'=> 1,
                'explanation'   => 'Pola 「〜から 〜まで」 (kara ... made) berarti "dari ... sampai ...". いえから えきまで = dari rumah sampai stasiun.',
                'points'        => 6,
            ],

            // Mondai 5: Konjugasi kata kerja & pola kalimat
            [
                'id'            => 18,
                'section'       => 'grammar',
                'section_label' => '文法 (Tata Bahasa)',
                'mondai'        => '問題 5',
                'mondai_desc'   => '（ ）に なにを いれますか。いちばん いい ものを ひとつ えらんで ください。',
                'passage'       => null,
                'question'      => 'すみませんが、ちょっと 手伝って （ ）。',
                'options'       => ['ください', 'します', 'あげます', 'いきます'],
                'correct_answer'=> 0,
                'explanation'   => 'Pola 「〜てください」 (~te kudasai) digunakan untuk memohon / meminta tolong secara sopan: 手伝ってください = tolong bantu saya.',
                'points'        => 6,
            ],
            [
                'id'            => 19,
                'section'       => 'grammar',
                'section_label' => '文法 (Tata Bahasa)',
                'mondai'        => '問題 5',
                'mondai_desc'   => '（ ）に なにを いれますか。いちばん いい ものを ひとつ えらんで ください。',
                'passage'       => null,
                'question'      => 'いま、にほんごを べんきょうして （ ）。',
                'options'       => ['あります', 'います', 'おきます', 'みます'],
                'correct_answer'=> 1,
                'explanation'   => 'Pola 「〜ています」 (~te imasu) menyatakan aktivitas yang sedang berlangsung (present continuous): べんきょうしています = sedang belajar.',
                'points'        => 6,
            ],
            [
                'id'            => 20,
                'section'       => 'grammar',
                'section_label' => '文法 (Tata Bahasa)',
                'mondai'        => '問題 5',
                'mondai_desc'   => '（ ）に なにを いれますか。いちばん いい ものを ひとつ えらんで ください。',
                'passage'       => null,
                'question'      => 'この 本を もう （ ）ましたか。―― はい、よみました。',
                'options'       => ['よみ', 'よむ', 'よんで', 'よんだ'],
                'correct_answer'=> 0,
                'explanation'   => 'Pola sopan lampau: stem kata kerja (Masu-stem) + ました: よみ + ました = よみました (sudah membaca).',
                'points'        => 6,
            ],
            [
                'id'            => 21,
                'section'       => 'grammar',
                'section_label' => '文法 (Tata Bahasa)',
                'mondai'        => '問題 5',
                'mondai_desc'   => '（ ）に なにを いれますか。いちばん いい ものを ひとつ えらんで ください。',
                'passage'       => null,
                'question'      => 'あめが ふって いますから、出かけ（ ） ほうがいいてす。',
                'options'       => ['ない', 'ないの', 'ないで', 'なかった'],
                'correct_answer'=> 0,
                'explanation'   => 'Pola saran bentuk negatif: 「V-nai + ほうがいいてす」 (lebih baik tidak...): 出かけない ほうがいいです = lebih baik tidak pergi keluar.',
                'points'        => 6,
            ],

            // Mondai 6: Susunan Kalimat Berbintang (★)
            [
                'id'            => 22,
                'section'       => 'grammar',
                'section_label' => '文法 (Tata Bahasa)',
                'mondai'        => '問題 6',
                'mondai_desc'   => '★に はいる ものは どれですか。いちばん いい ものを ひとつ えらんで ください。',
                'passage'       => null,
                'question'      => 'きのう わたしは ____  ____  _★_  ____ かいました。<br><small style="color:var(--muted)">Pilihan kata: 1:パンを　2:スーパーで　3:おいしい　4:あたらしい</small>',
                'options'       => ['パンを', 'スーパーで', 'おいしい', 'あたらしい'],
                'correct_answer'=> 2,
                'explanation'   => 'Urutan kalimat yang tepat: 「きのう わたしは [2:スーパーで] [4:あたらしい] [★ 3:おいしい] [1:パンを] かいました。」 Jadi di posisi bintang (★) adalah nomor 3 (おいしい).',
                'points'        => 6,
            ],
            [
                'id'            => 23,
                'section'       => 'grammar',
                'section_label' => '文法 (Tata Bahasa)',
                'mondai'        => '問題 6',
                'mondai_desc'   => '★に はいる ものは どれですか。いちばん いい ものを ひとつ えらんで ください。',
                'passage'       => null,
                'question'      => '田中さんは ____  ____  _★_  ____ ひとです。<br><small style="color:var(--muted)">Pilihan kata: 1:とても　2:親切で　3:あたまが　4:いい</small>',
                'options'       => ['とても', '親切で', 'あたまが', 'いい'],
                'correct_answer'=> 2,
                'explanation'   => 'Urutan yang tepat: 「田中さんは [1:とても] [2:親切で] [★ 3:あたまが] [4:いい] ひとです。」 (Tanaka-san adalah orang yang sangat ramah dan pintar). Di posisi bintang (★) adalah 3 (あたまが).',
                'points'        => 6,
            ],
            [
                'id'            => 24,
                'section'       => 'grammar',
                'section_label' => '文法 (Tata Bahasa)',
                'mondai'        => '問題 6',
                'mondai_desc'   => '★に はいる ものは どれですか。いちばん いい ものを ひとつ えらんで ください。',
                'passage'       => null,
                'question'      => '日曜日、____  ____  _★_  ____ 行きました。<br><small style="color:var(--muted)">Pilihan kata: 1:見に　2:映画を　3:友達と　4:映画館へ</small>',
                'options'       => ['見に', '映画を', '友達と', '映画館へ'],
                'correct_answer'=> 0,
                'explanation'   => 'Urutan yang tepat: 「日曜日、[3:友達と] [4:映画館へ] [2:映画を] [★ 1:見に] 行きました。」 (Hari Minggu, pergi ke bioskop untuk menonton film bersama teman). Posisi bintang (★) diisi nomor 1 (見に).',
                'points'        => 6,
            ],

            // ══════════════════════════════════════════════════════════════════
            // SEKSI 3: 読解 (Pemahaman Bacaan / Reading - 6 Soal)
            // ══════════════════════════════════════════════════════════════════

            // Teks 1: Memo / Pesan Singkat
            [
                'id'            => 25,
                'section'       => 'reading',
                'section_label' => '読解 (Pemahaman Bacaan)',
                'mondai'        => '問題 7',
                'mondai_desc'   => 'つぎの ぶんを よんで、しつもんに こたえて ください。こたえは ひとつ えらんで ください。',
                'passage'       => "【メモ】\nやまださんへ\n\nきょうの 午後 ３時に たなかさんから でんわが ありました。\nあしたの 会議の じかんは １０時半から １１時に かわりました。\nばしょは ３かいの だいいち かいぎしつ です。\nよろしく おねがいします。\n\nさとう より",
                'question'      => 'あしたの 会議は なんじから 始まりますか。',
                'options'       => ['３時から', '１０時から', '１０時半から', '１１時から'],
                'correct_answer'=> 2,
                'explanation'   => 'Dalam memo tertulis: 「あしたの 会議の じかんは １０時半から １１時に かわりました」 (Waktu rapat besok berubah dari jam 10.30 menjadi jam 11.00). Maka rapat dimulai jam 10.30 (１０時半から).',
                'points'        => 6,
            ],
            [
                'id'            => 26,
                'section'       => 'reading',
                'section_label' => '読解 (Pemahaman Bacaan)',
                'mondai'        => '問題 7',
                'mondai_desc'   => 'つぎの ぶんを よんで、しつもんに こたえて ください。こたえは ひとつ えらんで ください。',
                'passage'       => "【メモ】\nやまださんへ\n\nきょうの 午後 ３時に たなかさんから でんわが ありました。\nあしたの 会議の じかんは １０時半から １１時に かわりました。\nばしょは ３かいの だいいち かいぎしつ です。\nよろしく おねがいします。\n\nさとう より",
                'question'      => 'この メモを かいた ひとは だれですか。',
                'options'       => ['やまださん', 'たなかさん', 'さとうさん', 'かいぎしつの ひと'],
                'correct_answer'=> 2,
                'explanation'   => 'Bagian akhir memo tertulis 「さとう より」 (Dari Sato). Jadi memo ditulis oleh Sato-san.',
                'points'        => 6,
            ],

            // Teks 2: Bacaan Cerita Pendek
            [
                'id'            => 27,
                'section'       => 'reading',
                'section_label' => '読解 (Pemahaman Bacaan)',
                'mondai'        => '問題 8',
                'mondai_desc'   => 'つぎの ぶんを よんで、しつもんに こたえて ください。こたえは ひとつ えらんで ください。',
                'passage'       => "わたしは 日本の アニメが だいすきですから、去年の １０月から 日本語の べんきょうを はじめました。\n学校の 授業は げつようびから きんようびまで 毎日 ４時間 あります。\nはじめは かんじが むずかしかったですが、いまは ７０こ ぐらい かくことが できます。\nらいねんの なつ、ともだちと いっしょに 日本へ りょこうに いく よていです。\n日本で たくさん 日本語を はなしたいです。",
                'question'      => 'この ひとは いつから 日本語の べんきょうを はじめましたか。',
                'options'       => ['今年の １０月', '去年の １０月', 'らいねんの なつ', '先週の げつようび'],
                'correct_answer'=> 1,
                'explanation'   => 'Di kalimat pertama tertulis: 「去年の １０月から 日本語の べんきょうを はじめました」 (Memulai belajar bahasa Jepang sejak Oktober tahun lalu).',
                'points'        => 6,
            ],
            [
                'id'            => 28,
                'section'       => 'reading',
                'section_label' => '読解 (Pemahaman Bacaan)',
                'mondai'        => '問題 8',
                'mondai_desc'   => 'つぎの ぶんを よんで、しつもんに こたえて ください。こたえは ひとつ えらんで ください。',
                'passage'       => "わたしは 日本の アニメが だいすきですから、去年の １０月から 日本語の べんきょうを はじめました。\n学校の 授業は げつようびから きんようびまで 毎日 ４時間 あります。\nはじめは かんじが むずかしかったですが、いまは ７０こ ぐらい かくことが できます。\nらいねんの なつ、ともだちと いっしょに 日本へ りょこうに いく よていです。\n日本で たくさん 日本語を はなしたいです。",
                'question'      => 'いま この ひとは 漢字を どのくらい かくことが できますか。',
                'options'       => ['ぜんぜん かくことが できない', '４こ ぐらい', '１０こ ぐらい', '７０こ ぐらい'],
                'correct_answer'=> 3,
                'explanation'   => 'Pada teks tertulis: 「いまは ７０こ ぐらい かくことが できます」 (Sekarang bisa menulis sekitar 70 kanji).',
                'points'        => 6,
            ],
            [
                'id'            => 29,
                'section'       => 'reading',
                'section_label' => '読解 (Pemahaman Bacaan)',
                'mondai'        => '問題 8',
                'mondai_desc'   => 'つぎの ぶんを よんで、しつもんに こたえて ください。こたえは ひとつ えらんで ください。',
                'passage'       => "わたしは 日本の アニメが だいすきですから、去年の １０月から 日本語の べんきょうを はじめました。\n学校の 授業は げつようびから きんようびまで 毎日 ４時間 あります。\nはじめは かんじが むずかしかったですが、いまは ７０こ ぐらい かくことが できます。\nらいねんの なつ、ともだちと いっしょに 日本へ りょこうに いく よていです。\n日本で たくさん 日本語を はなしたいです。",
                'question'      => 'この ぶんの ないようと あって いる ものは どれですか。',
                'options'       => [
                    'この ひとは 日本のアニメが きらいです。',
                    'らいねんの なつ、ひとりで 日本へ 行きます。',
                    'らいねんの なつ、日本へ りょこうに 行く よていです。',
                    '学校の 授業は どようびも あります。'
                ],
                'correct_answer'=> 2,
                'explanation'   => 'Pernyataan yang sesuai dengan isi bacaan adalah nomor 3: Musim panas tahun depan ia berencana pergi liburan ke Jepang (らいねんの なつ、ともだちと いっしょに 日本へ りょこうに いく よていです).',
                'points'        => 6,
            ],

            // Teks 3: Informasi Pengumuman / Jadwal
            [
                'id'            => 30,
                'section'       => 'reading',
                'section_label' => '読解 (Pemahaman Bacaan)',
                'mondai'        => '問題 8',
                'mondai_desc'   => 'つぎの あんないを よんで、しつもんに こたえて ください。こたえは ひとつ えらんで ください。',
                'passage'       => "【さくら 図書館の ごあんない】\n・あいている じかん：午前 ９時 〜 午後 ７時\n・やすみの ひ：まいしゅう げつようび（祝日の ときは あいて います）\n・本を かりられる 数：ひとり ５さつ まで\n・かりられる 期間：２しゅうかん",
                'question'      => 'さくら 図書館で 本を なんさつまで かりることが できますか。',
                'options'       => ['２さつ まで', '５さつ まで', '７さつ まで', '９さつ まで'],
                'correct_answer'=> 1,
                'explanation'   => 'Dalam pengumuman tertulis: 「本を かりられる 数：ひとり ５さつ まで」 (Jumlah buku yang bisa dipinjam: sampai 5 buku per orang).',
                'points'        => 6,
            ],
        ];
    }

    /**
     * Evaluasi jawaban siswa, hitung skor, dan simpan hasil ujian ke database.
     *
     * @param array<int|string, mixed> $userAnswers
     */
    public function evaluate(User $user, array $userAnswers, int $timeTaken): JlptExamResult
    {
        $questions = $this->getQuestions();

        $correctCount = 0;
        $scoreVocab   = 0;
        $scoreGrammar = 0;
        $scoreReading = 0;

        $detailedAnswers = [];

        foreach ($questions as $q) {
            $qId = (int) $q['id'];
            $submitted = isset($userAnswers[$qId]) ? (int) $userAnswers[$qId] : null;
            $isCorrect = ($submitted !== null && $submitted === $q['correct_answer']);

            if ($isCorrect) {
                $correctCount++;
                if ($q['section'] === 'vocabulary') {
                    $scoreVocab += $q['points'];
                } elseif ($q['section'] === 'grammar') {
                    $scoreGrammar += $q['points'];
                } elseif ($q['section'] === 'reading') {
                    $scoreReading += $q['points'];
                }
            }

            $detailedAnswers[$qId] = [
                'question_id'    => $qId,
                'submitted'      => $submitted,
                'correct_answer' => $q['correct_answer'],
                'is_correct'     => $isCorrect,
                'section'        => $q['section'],
            ];
        }

        $totalScore = $scoreVocab + $scoreGrammar + $scoreReading;

        // Standar kelulusan JLPT N5: minimal 80/180 dan minimal ambang batas seksi
        $isPassed = ($totalScore >= 80 && $scoreVocab >= 19 && $scoreGrammar >= 19 && $scoreReading >= 12);

        $grade = match (true) {
            $totalScore >= 150 => 'A',
            $totalScore >= 120 => 'B',
            $totalScore >= 80  => 'C',
            default            => 'D',
        };

        $certificateNo = 'JPL-N5-' . date('Ym') . '-' . strtoupper(substr(md5($user->id . microtime()), 0, 6));

        $result = JlptExamResult::create([
            'user_id'          => $user->id,
            'certificate_no'   => $certificateNo,
            'score'            => $totalScore,
            'score_vocabulary' => $scoreVocab,
            'score_grammar'    => $scoreGrammar,
            'score_reading'    => $scoreReading,
            'total_questions'  => count($questions),
            'correct_answers'  => $correctCount,
            'is_passed'        => $isPassed,
            'grade'            => $grade,
            'time_taken'       => $timeTaken,
            'answers'          => $detailedAnswers,
        ]);

        // Berikan XP reward atas usaha penyelesaian simulasi JLPT N5
        try {
            $this->gamificationService->award($user, 'jlpt_mock_complete');
        } catch (\Throwable) {
            // Abaikan jika batas harian tercapai
        }

        return $result;
    }
}

