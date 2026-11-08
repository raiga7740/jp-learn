# JP Learn - Interactive Japanese Learning Platform

<p align="center">
  <strong>Platform Pembelajaran Bahasa Jepang Interaktif Modern Berbasis Web</strong><br>
  Dilengkapi Asisten AI (Kenji Sensei), Gamifikasi (XP, Streak, Level), Simulasi Ujian JLPT N5, Flashcard, dan Latihan Menulis Kana.
</p>

---

## Daftar Isi

- [Tentang JP Learn](#tentang-jp-learn)
- [Fitur Utama](#fitur-utama)
- [Teknologi yang Digunakan](#teknologi-yang-digunakan)
- [Arsitektur AI Tanya Sensei](#arsitektur-ai-tanya-sensei)
- [Persyaratan Sistem](#persyaratan-sistem)
- [Panduan Instalasi Lokal](#panduan-instalasi-lokal)
- [Konfigurasi Environment (.env)](#konfigurasi-environment-env)
- [Panduan Deployment (InfinityFree / Shared Hosting)](#panduan-deployment-infinityfree--shared-hosting)
- [Struktur Direktori Proyek](#struktur-direktori-proyek)
- [Kontribusi & Lisensi](#kontribusi--lisensi)

---

## Tentang JP Learn

**JP Learn** adalah aplikasi web pembelajaran bahasa Jepang yang dirancang khusus untuk pembelajar tingkat pemula hingga persiapan **JLPT N5**. Aplikasi ini menggabungkan kurikulum terstruktur dengan elemen gamifikasi dan kecerdasan buatan (AI) untuk memberikan pengalaman belajar yang menyenangkan, interaktif, dan terarah.

---

## Fitur Utama

### 1. Tanya Sensei (AI Assistant - Kenji Sensei)
* **Dual Engine Switcher:** Pengguna dapat memilih bebas antara **AI Gemini** (Google Cloud) untuk analisis mendalam atau **Sensei Engine** (mesin bawaan internal) untuk respon instan tanpa kuota API.
* **Auto Model Discovery:** Kompatibel dengan model Google Gemini terbaru (`gemini-flash-lite-latest`, `gemini-3.8-flash`) dengan *auto-fallback* jika terjadi limitasi kuota.
* **Koreksi Kalimat Otomatis:** Memeriksa susunan kalimat, partikel (*wa, ga, ni, de*), kanji, serta memberikan romaji dan penjelasan dalam bahasa Indonesia santun.
* **Keamanan Kunci Mandiri:** Pengguna dapat memasukkan Google Gemini API Key milik sendiri secara aman di *localStorage* browser.

### 2. Modul Pembelajaran Interaktif
* **Materi Terstruktur:** Pembelajaran huruf Hiragana, Katakana, Kanji N5, Pola Kalimat/Grammar, dan Percakapan Tematik sehari-hari.
* **Flashcard Interaktif:** Kartu bolak-balik (Kanji, Romaji, Arti, Contoh Kalimat) dengan sistem pelacak status hafalan.
* **Game Susun Kalimat (Sentence Builder):** Game interaktif menyusun pola kalimat Jepang (SOV / Topik-Waktu-Objek-Predikat) dengan ubin sentuh (*tap-to-place*), ubin pengecoh (*distractor tiles*), pengucapan audio otomatis (Web Speech API), penjelasan tata bahasa, kombo streak, dan hadiah XP.
* **Latihan Menulis Kana:** Canvas interaktif untuk melatih goresan (*stroke order*) huruf Hiragana dan Katakana secara visual.
* **Speed Match Game:** Permainan mencocokkan kata Jepang dengan arti bahasa Indonesia berbasis batas waktu dan *leaderboard* skor.

### 3. Evaluasi & Ujian
* **Quiz & Latihan Soal:** Evaluasi materi per bab dengan timer, pembahasan jawaban, dan penambahan XP.
* **Simulasi Ujian JLPT N5:** Format ujian simulasi standar mencakup sesi *Moji/Goi* (Kosakata), *Bunpou/Dokkai* (Tata Bahasa/Bacaan), dan *Choukai* (Mendengarkan) dengan scoring otomatis.

### 4. Kamus & Kosakata Terintegrasi
* Pencarian cepat berdasarkan Kanji, Romaji, maupun Bahasa Indonesia.
* Dilengkapi label part-of-speech, romaji cara baca, dan contoh kalimat aplikatif.

### 5. Gamifikasi & Progres Belajar
* **Sistem XP & Level:** Naikkan level dari *Pemula (N5)* hingga level mahir dengan menyelesaikan aktivitas belajar.
* **Streak Tracker:** Melacak konsistensi belajar harian.
* **Badge Pencapaian:** Penghargaan visual atas pencapaian target belajar tertentu.

### 6. Desain UI/UX Modern & Responsif
* Tampilan responsif di desktop, tablet, dan smartphone.
* Sidebar scrollable dengan drawer navigasi sentuh untuk perangkat mobile.
* Modal konfirmasi dan notifikasi toast custom elegan khas JP Learn.

---

## Teknologi yang Digunakan

* **Backend Framework:** Laravel 11 (PHP 8.3)
* **Database:** MySQL / MariaDB (kompatibel SQLite untuk testing)
* **Frontend:** Blade Templating, Vanilla JavaScript (ES6+), Modern CSS3 (CSS Variables, Flexbox, CSS Grid)
* **AI Service:** Google Gemini Generative Language API (`v1beta` & `v1`)
* **Icons:** Inline SVG murni (ringan, tajam di semua resolusi, zero-dependency)

---

## Arsitektur AI Tanya Sensei

```
                             [ Pengguna Tanya Sensei ]
                                        │
                                        ▼
                         [ Pemilih Mode Mesin (UI) ]
                                   ┌────┴────┐
                                   │         │
                 [ Mode: AI Gemini ]         [ Mode: Sensei Engine ]
                         │                             │
                         ▼                             │
            Google Gemini API (Cloud)                  │
     (gemini-flash-lite-latest / gemini-3.8)           │
                         │                             │
               (Gagal / Kuota Habis?)                  │
                         │ (Fallback Otomatis)         │
                         └──────────────► ┌────────────┘
                                          ▼
                             [ Smart Built-in Engine ]
                            (Aturan Tata Bahasa N5 & Kamus)
                                          │
                                          ▼
                               [ Jawaban Kenji Sensei ]
```

---

## Persyaratan Sistem

Untuk menjalankan proyek ini di lingkungan lokal, pastikan perangkat telah terpasang:
* **PHP:** Versi 8.2 atau 8.3 (dengan ekstensi: `openssl`, `pdo`, `mbstring`, `tokenizer`, `xml`, `ctype`, `json`, `curl`)
* **Composer:** Versi 2.x
* **Database Server:** MySQL 8.0+ / MariaDB 10.4+
* **Web Server:** Apache / Nginx (atau bawaan `php artisan serve`)

---

## Panduan Instalasi Lokal

1. **Clone Repositori:**
   ```bash
   git clone https://github.com/username/jp-learn.git
   cd jp-learn
   ```

2. **Pasang Dependensi PHP:**
   ```bash
   composer install
   ```

3. **Duplikasi File Environment:**
   ```bash
   cp .env.example .env
   ```

4. **Generate Application Key:**
   ```bash
   php artisan key:generate
   ```

5. **Konfigurasi Database di `.env`:**
   Sesuaikan baris koneksi database:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=belajar_jepang
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. **Jalankan Migrasi & Seeder:**
   ```bash
   php artisan migrate --seed
   ```
   *(Opsional: Jika ada data kuis tambahan, impor file `update_quizzes.sql` melalui phpMyAdmin).*

7. **Jalankan Server Lokal:**
   ```bash
   php artisan serve
   ```
   Buka browser dan akses aplikasi di: `http://127.0.0.1:8000`

---

## Konfigurasi Environment (.env)

| Variabel | Keterangan | Nilai Default / Contoh |
| :--- | :--- | :--- |
| `APP_NAME` | Nama aplikasi | `JPLearn` |
| `APP_ENV` | Mode lingkungan | `local` / `production` |
| `APP_DEBUG` | Mode debug error | `true` (lokal) / `false` (hosting) |
| `APP_URL` | URL domain aplikasi | `http://127.0.0.1:8000` |
| `GEMINI_API_KEY` | *(Opsional)* Kunci default server untuk Gemini AI | `AIzaSy...` |

> **Catatan Penggunaan API Key:** Tanya Sensei mendukung input API key mandiri oleh setiap pengguna melalui menu **Pengaturan API** di dalam aplikasi, sehingga memasukkan `GEMINI_API_KEY` di file `.env` server bersifat opsional.

---

## Panduan Deployment (InfinityFree / Shared Hosting)

1. **Persiapan Berkas:**
   * Gunakan file arsip pembaruan `update-jplearn.zip` yang mencakup folder `app/`, `resources/`, `routes/`, dan `public/`.
2. **Struktur Direktori Hosting:**
   * Pastikan file `public/index.php` dan aset CSS/JS dapat diakses melalui root domain publik (folder `htdocs`).
   * Arahkan path `require __DIR__ . '/../vendor/autoload.php'` dan `bootstrap/app.php` sesuai dengan lokasi root Laravel kamu.
3. **Database:**
   * Buat database baru di cPanel / InfinityFree MySQL Management.
   * Ekspor database lokal kamu dan impor ke phpMyAdmin hosting.
   * Sesuaikan `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` di file `.env` hosting.
4. **Optimasi Cache:**
   * Di terminal SSH (jika tersedia) atau via route:
     ```bash
     php artisan config:cache
     php artisan route:cache
     php artisan view:cache
     ```

---

## Struktur Direktori Proyek

```
jp-learn/
├── app/
│   ├── Http/Controllers/        # Controller (Sensei, Flashcard, Quiz, JLPT, dll)
│   ├── Models/                 # Eloquent Model (User, Quiz, Kanji, Vocabulary)
│   └── Services/               # Logika Bisnis (SenseiService, GamificationService)
├── config/                     # Berkas konfigurasi aplikasi Laravel
├── database/
│   ├── migrations/             # Struktur skema tabel database
│   └── seeders/                # Data awal materi, kosakata, dan kuis
├── public/                     # Aset publik (CSS, JS, Gambar, Web Entry Point)
├── resources/
│   └── views/                  # Tampilan Blade (Dashboard, Sensei, Auth, dll)
├── routes/
│   └── web.php                 # Rute utama aplikasi
├── storage/                    # Log sistem, sesi, dan cache berkas
└── README.md                   # Dokumentasi proyek
```

---

## Kontribusi & Lisensi

Proyek ini dikembangkan untuk tujuan edukasi dan peningkatan literasi bahasa Jepang.

- **Pengembang:** Tim JP Learn
- **Lisensi:** Open-source di bawah lisensi [MIT License](LICENSE).
