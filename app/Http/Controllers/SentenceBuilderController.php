<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SentenceBuilderController extends Controller
{
    /**
     * Tampilkan arena permainan Game Susun Kalimat (Sentence Builder).
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $challenges = $this->getSentenceChallenges();

        return view('dashboard.sentence-builder.index', [
            'user'       => $user,
            'challenges' => $challenges,
        ]);
    }

    /**
     * Daftar tantangan susun kalimat kurikulum JLPT N5 terstruktur.
     *
     * @return array<int, array{id: int, level: string, level_label: string, category: string, indonesian: string, japanese_full: string, romaji: string, correct_tiles: array<int, string>, distractor_tiles: array<int, string>, note: string}>
     */
    public function getSentenceChallenges(): array
    {
        return [
            // ── LEVEL 1: PEMULA (Pola Kalimat Dasar & Partikel は, です, の, が) ──
            [
                'id'               => 1,
                'level'            => 'pemula',
                'level_label'      => 'Level 1: Pemula',
                'category'         => 'Pola SOV & Partikel は / です',
                'indonesian'       => 'Saya adalah seorang murid.',
                'japanese_full'    => '私は学生です。',
                'romaji'           => 'Watashi wa gakusei desu.',
                'correct_tiles'    => ['私は', '学生', 'です'],
                'distractor_tiles' => ['私が', 'でした'],
                'note'             => 'Partikel は (wa) menandai topik pembicaraan (Saya), dan です (desu) adalah penutup kalimat sopan.',
            ],
            [
                'id'               => 2,
                'level'            => 'pemula',
                'level_label'      => 'Level 1: Pemula',
                'category'         => 'Kata Tunjuk & Partikel の (Kepemilikan)',
                'indonesian'       => 'Ini adalah buku bahasa Jepang.',
                'japanese_full'    => 'これは日本語の本です。',
                'romaji'           => 'Kore wa nihongo no hon desu.',
                'correct_tiles'    => ['これは', '日本語の', '本', 'です'],
                'distractor_tiles' => ['それは', '日本語を'],
                'note'             => 'これ (Kore) menunjuk benda di dekat pembicara, dan partikel の (no) menghubungkan bahasa Jepang dan buku.',
            ],
            [
                'id'               => 3,
                'level'            => 'pemula',
                'level_label'      => 'Level 1: Pemula',
                'category'         => 'Kewarganegaraan & Asal',
                'indonesian'       => 'Tuan Tanaka adalah orang Jepang.',
                'japanese_full'    => '田中さんは日本人です。',
                'romaji'           => 'Tanaka-san wa nihonjin desu.',
                'correct_tiles'    => ['田中さんは', '日本人', 'です'],
                'distractor_tiles' => ['田中さんの', '日本人に'],
                'note'             => 'Akhiran ~人 (~jin) setelah nama negara menyatakan kewarganegaraan.',
            ],
            [
                'id'               => 4,
                'level'            => 'pemula',
                'level_label'      => 'Level 1: Pemula',
                'category'         => 'Tempat & Keberadaan',
                'indonesian'       => 'Di sini adalah perpustakaan.',
                'japanese_full'    => 'ここは図書館です。',
                'romaji'           => 'Koko wa toshokan desu.',
                'correct_tiles'    => ['ここは', '図書館', 'です'],
                'distractor_tiles' => ['そこに', '図書館で'],
                'note'             => 'ここ (Koko) menunjuk letak tempat yang dekat dengan pembicara.',
            ],
            [
                'id'               => 5,
                'level'            => 'pemula',
                'level_label'      => 'Level 1: Pemula',
                'category'         => 'Kesukaan (Partikel が & 好き)',
                'indonesian'       => 'Saya suka kucing.',
                'japanese_full'    => '私は猫が好きです。',
                'romaji'           => 'Watashi wa neko ga suki desu.',
                'correct_tiles'    => ['私は', '猫が', '好き', 'です'],
                'distractor_tiles' => ['猫を', '嫌い'],
                'note'             => 'Kata sifat 好き (suki = suka) wajib berpasangan dengan partikel が (ga) untuk benda yang disukai.',
            ],
            [
                'id'               => 6,
                'level'            => 'pemula',
                'level_label'      => 'Level 1: Pemula',
                'category'         => 'Pertanyaan & Kata Tanya',
                'indonesian'       => 'Orang itu siapa?',
                'japanese_full'    => 'あの方は誰ですか。',
                'romaji'           => 'Ano kata wa dare desu ka.',
                'correct_tiles'    => ['あの方は', '誰', 'ですか'],
                'distractor_tiles' => ['この人は', '何'],
                'note'             => 'あの方 (Ano kata) adalah bentuk sopan dari "orang itu", dan 誰 (dare) berarti "siapa".',
            ],

            // ── LEVEL 2: MENENGAH (Partikel Aksi で, Tempat/Tujuan に/へ, Objek を, Waktu) ──
            [
                'id'               => 7,
                'level'            => 'menengah',
                'level_label'      => 'Level 2: Menengah',
                'category'         => 'Aktivitas Tempat (Partikel で & を)',
                'indonesian'       => 'Saya membaca buku di perpustakaan.',
                'japanese_full'    => '私は図書館で本を読みます。',
                'romaji'           => 'Watashi wa toshokan de hon o yomimasu.',
                'correct_tiles'    => ['私は', '図書館で', '本を', '読みます'],
                'distractor_tiles' => ['図書館に', '本が'],
                'note'             => 'Membaca buku adalah aksi dinamis, sehingga tempat perpustakaan wajib memakai partikel で (de), bukan に (ni)!',
            ],
            [
                'id'               => 8,
                'level'            => 'menengah',
                'level_label'      => 'Level 2: Menengah',
                'category'         => 'Waktu & Jam (Partikel に)',
                'indonesian'       => 'Saya bangun pada jam 7 pagi.',
                'japanese_full'    => '私は朝7時に起きます。',
                'romaji'           => 'Watashi wa asa shichi-ji ni okimasu.',
                'correct_tiles'    => ['私は', '朝7時に', '起きます'],
                'distractor_tiles' => ['朝7時で', '寝ます'],
                'note'             => 'Waktu spesifik yang memiliki angka/jam menggunakan partikel に (ni).',
            ],
            [
                'id'               => 9,
                'level'            => 'menengah',
                'level_label'      => 'Level 2: Menengah',
                'category'         => 'Transportasi & Arah Tujuan (で & へ)',
                'indonesian'       => 'Saya pergi ke sekolah naik bus.',
                'japanese_full'    => '私はバスで学校へ行きます。',
                'romaji'           => 'Watashi wa basu de gakkou e ikimasu.',
                'correct_tiles'    => ['私は', 'バスで', '学校へ', '行きます'],
                'distractor_tiles' => ['バスを', '学校で'],
                'note'             => 'Alat transportasi memakai partikel で (de), sedangkan arah tujuan memakai へ (e) atau に (ni).',
            ],
            [
                'id'               => 10,
                'level'            => 'menengah',
                'level_label'      => 'Level 2: Menengah',
                'category'         => 'Bersama Teman (Partikel と)',
                'indonesian'       => 'Saya minum kopi bersama teman di kafe.',
                'japanese_full'    => '喫茶店で友達とコーヒーを飲みます。',
                'romaji'           => 'Kissa-ten de tomodachi to kouhii o nomimasu.',
                'correct_tiles'    => ['喫茶店で', '友達と', 'コーヒーを', '飲みます'],
                'distractor_tiles' => ['喫茶店に', '友達に'],
                'note'             => 'Partikel と (to) setelah nama orang berarti "bersama dengan".',
            ],
            [
                'id'               => 11,
                'level'            => 'menengah',
                'level_label'      => 'Level 2: Menengah',
                'category'         => 'Batas Waktu (から & まで)',
                'indonesian'       => 'Saya bekerja dari jam 9 sampai jam 5.',
                'japanese_full'    => '9時から5時まで働きます。',
                'romaji'           => 'Ku-ji kara go-ji made hatarakimasu.',
                'correct_tiles'    => ['9時から', '5時まで', '働きます'],
                'distractor_tiles' => ['9時まで', '5時から'],
                'note'             => 'から (kara) = dari titik awal, dan まで (made) = sampai titik akhir.',
            ],
            [
                'id'               => 12,
                'level'            => 'menengah',
                'level_label'      => 'Level 2: Menengah',
                'category'         => 'Bertemu Orang (Partikel に & Lampau)',
                'indonesian'       => 'Kemarin saya bertemu teman di taman.',
                'japanese_full'    => '昨日公園で友達に会いました。',
                'romaji'           => 'Kinou kouen de tomodachi ni aimashita.',
                'correct_tiles'    => ['昨日', '公園で', '友達に', '会いました'],
                'distractor_tiles' => ['友達を', '会います'],
                'note'             => 'Kata kerja 会います (aimasu = bertemu) orang yang ditemui wajib menggunakan partikel に (ni), bukan を (o)!',
            ],

            // ── LEVEL 3: MAHIR N5 (Bentuk ~TE, ~TAI, Kemampuan, Pengalaman & Saran) ──
            [
                'id'               => 13,
                'level'            => 'mahir',
                'level_label'      => 'Level 3: Mahir N5',
                'category'         => 'Keinginan (~たいです)',
                'indonesian'       => 'Saya ingin pergi ke Jepang.',
                'japanese_full'    => '私は日本へ行きたいです。',
                'romaji'           => 'Watashi wa nihon e ikitai desu.',
                'correct_tiles'    => ['私は', '日本へ', '行きたい', 'です'],
                'distractor_tiles' => ['日本で', '行きます'],
                'note'             => 'Pola ~たいです (~tai desu) dibentuk dari kata kerja bentuk masu (ikimasu -> ikitai desu).',
            ],
            [
                'id'               => 14,
                'level'            => 'mahir',
                'level_label'      => 'Level 3: Mahir N5',
                'category'         => 'Permohonan Sopan (~てください)',
                'indonesian'       => 'Tolong tunggu sebentar di sini.',
                'japanese_full'    => 'ここでちょっと待ってください。',
                'romaji'           => 'Koko de chotto matte kudasai.',
                'correct_tiles'    => ['ここで', 'ちょっと', '待って', 'ください'],
                'distractor_tiles' => ['ここに', '待ちます'],
                'note'             => 'Kata kerja 待ちます (machimasu) diubah ke bentuk TE menjadi 待って (matte), lalu ditambah ください.',
            ],
            [
                'id'               => 15,
                'level'            => 'mahir',
                'level_label'      => 'Level 3: Mahir N5',
                'category'         => 'Sedang Melakukan (~ています)',
                'indonesian'       => 'Sekarang saya sedang makan nasi.',
                'japanese_full'    => '今ご飯を食べています。',
                'romaji'           => 'Ima gohan o tabete imasu.',
                'correct_tiles'    => ['今', 'ご飯を', '食べて', 'います'],
                'distractor_tiles' => ['ご飯が', '食べます'],
                'note'             => 'Bentuk ~ています (~te imasu) menyatakan aksi yang sedang berlangsung pada saat ini.',
            ],
            [
                'id'               => 16,
                'level'            => 'mahir',
                'level_label'      => 'Level 3: Mahir N5',
                'category'         => 'Kemampuan (ことができます)',
                'indonesian'       => 'Saya bisa membaca huruf kanji.',
                'japanese_full'    => '私は漢字を読むことができます。',
                'romaji'           => 'Watashi wa kanji o yomu koto ga dekimasu.',
                'correct_tiles'    => ['私は', '漢字を', '読むことが', 'できます'],
                'distractor_tiles' => ['漢字が', '読みます'],
                'note'             => 'Bentuk kamus 読む (yomu) + ことができます menyatakan kemampuan formal.',
            ],
            [
                'id'               => 17,
                'level'            => 'mahir',
                'level_label'      => 'Level 3: Mahir N5',
                'category'         => 'Pengalaman Lampau (~たことがあります)',
                'indonesian'       => 'Saya pernah mendaki Gunung Fuji.',
                'japanese_full'    => '富士山に登ったことがあります。',
                'romaji'           => 'Fujisan ni nobotta koto ga arimasu.',
                'correct_tiles'    => ['富士山に', '登ったことが', 'あります'],
                'distractor_tiles' => ['富士山で', '登ります'],
                'note'             => 'Kata kerja bentuk lampau 登った (nobotta) + ことがあります menyatakan pengalaman pernah melakukan.',
            ],
            [
                'id'               => 18,
                'level'            => 'mahir',
                'level_label'      => 'Level 3: Mahir N5',
                'category'         => 'Saran & Anjuran (~ほうがいいです)',
                'indonesian'       => 'Sebaiknya tidur lebih awal ya.',
                'japanese_full'    => '早く寝たほうがいいですよ。',
                'romaji'           => 'Hayaku neta hou ga ii desu yo.',
                'correct_tiles'    => ['早く', '寝たほうが', 'いいです', 'よ'],
                'distractor_tiles' => ['寝るほうが', 'ね'],
                'note'             => 'Bentuk lampau 寝た (neta) + ほうがいいです adalah pola standar memberi anjuran atau saran positif.',
            ],
        ];
    }
}

