<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Current Application Version & Release Metadata
    |--------------------------------------------------------------------------
    |
    | Menyimpan versi aplikasi terkini, tanggal rilis, nama build, dan status sistem.
    |
    */

    'current_version' => 'v2.4.0',
    'version_name'    => 'Sakura Milestone & Interactive Guided Tour',
    'release_date'    => '8 November 2026',
    'environment'     => env('APP_ENV', 'production'),
    'system_status'   => [
        'status'       => 'operational',
        'label'        => 'Semua Sistem Operasional',
        'last_checked' => '8 November 2026, 22:50 WIB',
        'ai_engine'    => 'Gemini 3.8 Flash & Sensei Engine Online',
        'database'     => 'Optimal',
    ],

    /*
    |--------------------------------------------------------------------------
    | Active Global Announcement / Broadcast Info
    |--------------------------------------------------------------------------
    |
    | Pengumuman penting yang ditampilkan di halaman pembaruan dan dashboard.
    |
    */

    'announcements' => [
        [
            'id'          => 'ann-2026-11-08',
            'type'        => 'info',
            'badge'       => 'Pembaruan v2.4.0',
            'title'       => 'Fitur Catatan Rilis & Panduan Interaktif Onboarding Resmi Dirilis!',
            'content'     => 'Kini Anda dapat memantau setiap inovasi dan fitur baru JP Learn di halaman Info Pembaruan & Versi. Jangan lewatkan juga fitur Panduan Tour untuk mengenal seluruh fitur platform.',
            'date'        => '8 November 2026',
            'is_active'   => true,
            'is_pinned'   => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Complete Version & Release History (Changelog)
    |--------------------------------------------------------------------------
    |
    | Riwayat rilis berurutan dari versi terbaru hingga rilis awal platform.
    |
    */

    'versions' => [
        [
            'version'      => 'v2.4.0',
            'codename'     => 'Sakura Milestone',
            'title'        => 'Pusat Info & Versi Sistem, Guided Tour Interaktif, dan Peningkatan AI Sensei',
            'release_date' => '8 November 2026',
            'badge'        => 'Rilis Terkini',
            'type'         => 'Mayor',
            'summary'      => 'Pembaruan v2.4.0 menghadirkan fitur transparansi Catatan Rilis & Versi untuk melihat setiap inovasi pada platform, panduan interaktif Onboarding Guided Tour (Driver.js) untuk memandu fitur website langkah demi langkah, serta penyempurnaan respons asisten AI Kenji Sensei.',
            'highlights'   => [
                'Pusat Catatan Rilis & Versi',
                'Guided Tour Interaktif 14 Modul',
                'Modal Kilat "Apa yang Baru?"',
                'Peningkatan AI Kenji Sensei',
            ],
            'changes'      => [
                'fitur_baru' => [
                    [
                        'title' => 'Pusat Info Pembaruan & Catatan Versi (Changelog)',
                        'desc'  => 'Halaman lengkap dan interaktif (/pembaruan) untuk melihat riwayat rilis, filter kategori fitur, ringkasan inovasi, status sistem, dan peta jalan (roadmap) fitur masa depan.',
                    ],
                    [
                        'title' => 'Modal Kilat "Apa yang Baru?"',
                        'desc'  => 'Popup instan dari topbar atau sidebar untuk melihat sorotan fitur baru di versi terkini tanpa harus berpindah halaman.',
                    ],
                    [
                        'title' => 'Interactive Onboarding Guided Tour (Driver.js)',
                        'desc'  => 'Tur interaktif visual terpandu langkah demi langkah yang mengenalkan 14 modul utama JP Learn kepada pengguna baru maupun lama.',
                    ],
                ],
                'peningkatan' => [
                    [
                        'title' => 'Stabilitas & Respon Kenji Sensei (AI Tanya Sensei)',
                        'desc'  => 'Peningkatan ketahanan koneksi Google Gemini 3.8 Flash dan transisi mulus ke Sensei Engine lokal saat kuota terbatas.',
                    ],
                    [
                        'title' => 'Navigasi Sidebar Mobile Lebih Responsif',
                        'desc'  => 'Drawer navigasi sentuh pada smartphone dan tablet kini lebih mulus dengan penataan grup menu yang ergonomis.',
                    ],
                ],
                'perbaikan' => [
                    [
                        'title' => 'Perbaikan Scroll Lock pada Modal Pop-up',
                        'desc'  => 'Memperbaiki perilaku scroll background saat modal konfirmasi dan dialog aktif pada peramban mobile.',
                    ],
                    [
                        'title' => 'Penyempurnaan Styling & Kontras Teks',
                        'desc'  => 'Penyesuaian kontras warna pada badge gamifikasi, streak tracker, dan ikon navigasi.',
                    ],
                ],
            ],
        ],

        [
            'version'      => 'v2.3.0',
            'codename'     => 'Fuji Exam & Vocab Book',
            'title'        => 'Simulasi Resmi Ujian JLPT N5, Sertifikat Digital, & Kamus Favorit',
            'release_date' => '7 November 2026',
            'badge'        => 'Fitur Besar',
            'type'         => 'Mayor',
            'summary'      => 'Rilis besar yang menghadirkan pengalaman ujian bahasa Jepang standar JLPT N5 sesungguhnya, penerbitan sertifikat digital otomatis ber-ID unik, dan modul kamus mini dengan penyimpanan kosakata favorit pengguna.',
            'highlights'   => [
                'Simulasi Lengkap JLPT N5',
                'Sertifikat Digital Otomatis',
                'Kamus Mini & Bookmark Kosakata',
                'Audio Choukai (Mendengarkan)',
            ],
            'changes'      => [
                'fitur_baru' => [
                    [
                        'title' => 'Simulasi Ujian Resmi JLPT N5',
                        'desc'  => 'Format ujian 3 sesi berstandar resmi (Moji/Goi, Bunpou/Dokkai, Choukai) dilengkapi penghitung waktu mundur otomatis dan penilaian standar kelulusan.',
                    ],
                    [
                        'title' => 'Penerbitan Sertifikat Kelulusan Resmi JP Learn',
                        'desc'  => 'Sertifikat digital elegan dengan nomor ID unik, tanggal ujian, skor terperinci, dan tombol cetak/unduh langsung.',
                    ],
                    [
                        'title' => 'Kamus Mini & Buku Kosakata Favorit',
                        'desc'  => 'Pencarian cepat ribuan kosakata Jepang dengan arti, romaji, partikel, penambahan kosakata buatan sendiri, dan mode review flashcard kamus.',
                    ],
                ],
                'peningkatan' => [
                    [
                        'title' => 'Integrasi Pemutar Audio Choukai',
                        'desc'  => 'Pemutar audio soal mendengarkan dengan tombol replay dan kontrol audio yang lancar di semua peramban.',
                    ],
                    [
                        'title' => 'Sistem Gamifikasi Ujian',
                        'desc'  => 'Pemberian hadiah bonus XP tinggi saat berhasil menyelesaikan simulasi ujian JLPT.',
                    ],
                ],
                'perbaikan' => [
                    [
                        'title' => 'Sinkronisasi Waktu Sesi Ujian',
                        'desc'  => 'Memastikan waktu ujian tidak ter-reset jika halaman tidak sengaja terefresh oleh pengguna.',
                    ],
                ],
            ],
        ],

        [
            'version'      => 'v2.2.0',
            'codename'     => 'Matsuri Gamification',
            'title'        => 'Tantangan Harian (Daily Challenge) & Game Susun Kalimat (Sentence Builder)',
            'release_date' => '5 November 2026',
            'badge'        => 'Gamifikasi',
            'type'         => 'Minor',
            'summary'      => 'Penambahan modul gamifikasi interaktif untuk mempertahankan konsistensi belajar harian melalui Tantangan Harian berhadiah streak serta Game Susun Kalimat SOV Jepang.',
            'highlights'   => [
                'Game Susun Kalimat (SOV)',
                'Tantangan Harian Dinamis',
                'Mini Game Speed Match',
                'Streak Booster & XP Reward',
            ],
            'changes'      => [
                'fitur_baru' => [
                    [
                        'title' => 'Game Susun Kalimat (Sentence Builder)',
                        'desc'  => 'Game menyusun kata menjadi pola kalimat Jepang dengan ubin sentuh interaktif (tap-to-place), ubin pengecoh, dan pengucapan native Web Speech API.',
                    ],
                    [
                        'title' => 'Tantangan Harian (Daily Challenge)',
                        'desc'  => '3 Misi kilat harian (Kosakata, Kanji N5, Tata Bahasa) dengan tema mingguan berotasi otomatis setiap tengah malam dan bonus +30 XP.',
                    ],
                    [
                        'title' => 'Speed Match Mini Game',
                        'desc'  => 'Permainan berpacu dengan waktu mencocokkan kata Jepang dan artinya untuk melatih refleks membaca cepat.',
                    ],
                ],
                'peningkatan' => [
                    [
                        'title' => 'Pembahasan Detail Kuis & Latihan',
                        'desc'  => 'Setiap jawaban kuis kini menyertakan penjelasan tata bahasa dan alasan jawaban yang benar.',
                    ],
                ],
                'perbaikan' => [
                    [
                        'title' => 'Perhitungan Streak Harian',
                        'desc'  => 'Memperbaiki logika deteksi pergantian hari pada zona waktu lokal pengguna.',
                    ],
                ],
            ],
        ],

        [
            'version'      => 'v2.1.0',
            'codename'     => 'Kenji Intelligence',
            'title'        => 'Asisten AI Tanya Sensei (Kenji Sensei) dengan Dual Engine Cerdas',
            'release_date' => '2 November 2026',
            'badge'        => 'AI Engine',
            'type'         => 'Minor',
            'summary'      => 'Peluncuran asisten belajar AI personal (Kenji Sensei) dengan teknologi Dual Engine yang memadukan Google Gemini Cloud dan Sensei Engine lokal bawaan aplikasi.',
            'highlights'   => [
                'Kenji Sensei AI Chatbot',
                'Dual Engine Switcher',
                'Koreksi Kalimat & Partikel',
                'Kunci Mandiri LocalStorage',
            ],
            'changes'      => [
                'fitur_baru' => [
                    [
                        'title' => 'Tanya Sensei (Kenji Sensei AI)',
                        'desc'  => 'Tempat bertanya seputar tata bahasa, kosakata, kanji, dan budaya Jepang dengan penjelasan santun dan ramah.',
                    ],
                    [
                        'title' => 'Dual Engine Switcher',
                        'desc'  => 'Fleksibilitas memilih antara mode Google Gemini AI untuk analisis mendalam atau Sensei Engine bawaan untuk respon instan tanpa kuota.',
                    ],
                    [
                        'title' => 'Fitur Masukkan API Key Pribadi',
                        'desc'  => 'Pengguna dapat memasukkan Google Gemini API Key sendiri yang tersimpan secara aman di browser tanpa dipublikasikan ke server.',
                    ],
                ],
                'peningkatan' => [
                    [
                        'title' => 'Pendeteksi Partikel Otomatis',
                        'desc'  => 'Kenji Sensei mampu mengidentifikasi kesalahan penggunaan partikel wa, ga, ni, de, dan o.',
                    ],
                ],
                'perbaikan' => [
                    [
                        'title' => 'Penanganan Error API Google Cloud',
                        'desc'  => 'Penambahan auto-fallback cerdas ke engine internal ketika kuota API pihak ketiga habis.',
                    ],
                ],
            ],
        ],

        [
            'version'      => 'v2.0.0',
            'codename'     => 'Torii Overhaul',
            'title'        => 'Perombakan Antarmuka Modern & Sistem Gamifikasi Belajar',
            'release_date' => '31 Oktober 2026',
            'badge'        => 'Milestone',
            'type'         => 'Mayor',
            'summary'      => 'Pembaruan menyeluruh pada tampilan antarmuka bernuansa Cherry Red & Deep Navy, sistem gamifikasi XP, Level Belajar, Papan Peringkat (Leaderboard), dan Flashcard SRS adaptif.',
            'highlights'   => [
                'Desain Baru Cherry & Navy',
                'Papan Peringkat (Leaderboard)',
                'Activity Heatmap 365 Hari',
                'Flashcard Spaced Repetition',
                'Canvas Latihan Menulis',
            ],
            'changes'      => [
                'fitur_baru' => [
                    [
                        'title' => 'Sistem XP, Level & Papan Peringkat',
                        'desc'  => 'Mekanisme reward XP dari setiap materi, kuis, dan latihan untuk meningkatkan level pembelajar dan bersaing di leaderboard komunitas.',
                    ],
                    [
                        'title' => 'Activity Heatmap (Peta Aktivitas Belajar)',
                        'desc'  => 'Visualisasi kotak kontribusi belajar harian yang merekam komitmen pengguna sepanjang tahun.',
                    ],
                    [
                        'title' => 'Latihan Menulis Huruf (Interactive Canvas)',
                        'desc'  => 'Kanvas goresan interaktif untuk berlatih menulis huruf Hiragana dan Katakana dengan urutan goresan (stroke order).',
                    ],
                    [
                        'title' => 'Flashcard Adaptif Spaced Repetition (SRS)',
                        'desc'  => 'Kartu bolak-balik dengan pelacak tingkat penguasaan kata (Belum, Sedang, Hafal).',
                    ],
                ],
                'peningkatan' => [
                    [
                        'title' => 'Modul Percakapan Situasional',
                        'desc'  => 'Dialog audio bertema kehidupan sehari-hari (di stasiun, memesan makanan, perkenalan diri).',
                    ],
                ],
                'perbaikan' => [
                    [
                        'title' => 'Optimasi Performa Query Database',
                        'desc'  => 'Pengurangan waktu muat halaman materi dan dashboard dengan pengindeksan data yang lebih efisien.',
                    ],
                ],
            ],
        ],

        [
            'version'      => 'v1.0.0',
            'codename'     => 'Hajimari (Permulaan)',
            'title'        => 'Peluncuran Perdana Platform JP Learn',
            'release_date' => '15 Oktober 2026',
            'badge'        => 'Inisial',
            'type'         => 'Rilis Perdana',
            'summary'      => 'Peluncuran resmi platform JP Learn untuk membantu pembelajar pemula menguasai dasar bahasa Jepang melalui materi Hiragana, Katakana, Kanji dasar, dan kuis pilihan ganda.',
            'highlights'   => [
                '46 Hiragana & 46 Katakana Lengkap',
                'Modul Kanji N5 Dasar',
                'Kuis Interaktif Per Bab',
                'Sistem Akun & Autentikasi',
            ],
            'changes'      => [
                'fitur_baru' => [
                    [
                        'title' => 'Kurikulum Huruf Kana & Kanji N5',
                        'desc'  => 'Tabel huruf lengkap dengan audio pengucapan, cara baca romaji, dan contoh kosakata.',
                    ],
                    [
                        'title' => 'Kuis Interaktif Berbatas Waktu',
                        'desc'  => 'Evaluasi hasil belajar per bab dengan kalkulasi skor dan kunci jawaban.',
                    ],
                    [
                        'title' => 'Landing Page & Desain Responsif',
                        'desc'  => 'Halaman beranda modern dengan penjelasan metode belajar dan demo flashcard langsung.',
                    ],
                ],
                'peningkatan' => [],
                'perbaikan'   => [],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Upcoming Roadmap (Fitur yang Sedang & Akan Dikembangkan)
    |--------------------------------------------------------------------------
    |
    | Transparansi rencana pengembangan fitur selanjutnya kepada para pengguna.
    |
    */

    'roadmap' => [
        [
            'status'      => 'in_progress',
            'status_label'=> 'Sedang Dikerjakan',
            'badge_class' => 'bg-amber-100 text-amber-800 border-amber-300',
            'color'       => '#d97706',
            'icon'        => 'mic',
            'title'       => 'AI Pronunciation Coach (Pengenal Suara & Latihan Pelafalan)',
            'desc'        => 'Latihan berbicara bahasa Jepang dengan deteksi suara cerdas (Speech-to-Text). Pengguna dapat merekam pelafalan kata/kalimat dan Kenji Sensei akan memberikan penilaian ketepatan intonasi dan artikulasi.',
            'target'      => 'Target Rilis: Akhir November 2026',
            'phase'       => 'Tahap Integrasi Audio & Model Speech',
        ],
        [
            'status'      => 'planned',
            'status_label'=> 'Direncanakan',
            'badge_class' => 'bg-blue-100 text-blue-800 border-blue-300',
            'color'       => '#2563eb',
            'icon'        => 'book-open',
            'title'       => 'Persiapan Materi & Simulasi Ujian JLPT N4',
            'desc'        => 'Ekspansi kurikulum dan bank soal ke tingkat menengah (JLPT N4), meliputi pola kalimat pengandaian (~たら, ~ば), bentuk pasif/kausatif, serta tambahan 180+ Kanji N4 baru.',
            'target'      => 'Target Rilis: Desember 2026',
            'phase'       => 'Penyusunan Kurikulum & Bank Soal',
        ],
        [
            'status'      => 'planned',
            'status_label'=> 'Direncanakan',
            'badge_class' => 'bg-purple-100 text-purple-800 border-purple-300',
            'color'       => '#7c3aed',
            'icon'        => 'moon',
            'title'       => 'Mode Gelap Eksklusif (Midnight Tokyo Dark Theme)',
            'desc'        => 'Pilihan tampilan mode gelap bertema malam kota Tokyo dengan palet Deep Indigo & Neon Red untuk kenyamanan belajar di malam hari dan efisiensi baterai perangkat.',
            'target'      => 'Target Rilis: Q1 2027',
            'phase'       => 'Desain UI / Prototipe Token Warna',
        ],
        [
            'status'      => 'concept',
            'status_label'=> 'Konsep Komunitas',
            'badge_class' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'color'       => '#059669',
            'icon'        => 'users',
            'title'       => 'Klub Belajar & Forum Tanya Jawab Komunitas',
            'desc'        => 'Ruang diskusi sesama pembelajar untuk saling berbagi tips belajar, berbagi trik menghafal kanji, dan membuat grup belajar bersama dengan leaderboard kelompok.',
            'target'      => 'Target Rilis: Q1 2027',
            'phase'       => 'Eksplorasi Konsep & Arsitektur Moderasi',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Frequently Asked Questions (FAQ) Seputar Pembaruan
    |--------------------------------------------------------------------------
    */

    'faq' => [
        [
            'question' => 'Bagaimana cara mendapatkan pembaruan fitur terbaru di JP Learn?',
            'answer'   => 'Seluruh pembaruan JP Learn berjalan otomatis di sisi web dan server cloud. Anda tidak perlu mengunduh installer apa pun. Cukup refresh atau buka website JP Learn, dan versi terbaru siap digunakan langsung!',
        ],
        [
            'question' => 'Apakah fitur-fitur baru dan pembaruan dikenakan biaya tambahan?',
            'answer'   => 'Tidak. Semua fitur baru, simulasi ujian JLPT N5, kamus mini, tantangan harian, serta pembaruan asisten AI dapat dinikmati secara gratis oleh seluruh anggota komunitas JP Learn.',
        ],
        [
            'question' => 'Apakah progres belajar saya tetap aman saat versi website diperbarui?',
            'answer'   => 'Sangat aman! Seluruh poin XP, level belajar, streak harian, riwayat kuis, kartu flashcard tersimpan, serta hasil simulasi ujian Anda tersimpan di database kami dan tidak akan terhapus saat pembaruan sistem.',
        ],
        [
            'question' => 'Bagaimana cara mengusulkan ide atau berdiskusi seputar fitur baru?',
            'answer'   => 'Kami sangat mengapresiasi ide dan masukan dari para pembelajar! Anda dapat menghubungi tim pengembang melalui kanal kontak resmi JP Learn atau berdiskusi langsung di komunitas belajar.',
        ],
    ],

];

