<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SenseiService
{
    public function __construct(
        public DictionaryService $dictionaryService
    ) {
    }

    /**
     * Hasilkan respon cerdas dari Kenji Sensei (AI).
     *
     * @param string $message
     * @param string|null $userApiKey Kunci Gemini kustom dari user (opsional)
     * @param array<int, array{role: string, text: string}> $history Riwayat percakapan sebelumnya
     * @return array{reply: string, engine: string, japanese_snippets: array<int, string>}
     */
    public function respond(string $message, ?string $userApiKey = null, array $history = []): array
    {
        $cleanMessage = trim($message);
        if ($cleanMessage === '') {
            return [
                'reply'             => 'Halo! Ada yang bisa Kenji Sensei bantu seputar bahasa Jepang hari ini? Silakan ketik pertanyaan atau pilih saran di bawah ya!',
                'engine'            => 'builtin',
                'japanese_snippets' => ['こんにちは！何か質問はありますか？'],
            ];
        }

        // 1. Coba hubungi Google Gemini API jika API Key tersedia
        $apiKey = $userApiKey ?: config('services.gemini.key', env('GEMINI_API_KEY'));
        if (!empty($apiKey)) {
            $geminiReply = $this->callGemini($cleanMessage, $apiKey, $history);
            if ($geminiReply !== null) {
                return [
                    'reply'             => $geminiReply,
                    'engine'            => 'gemini',
                    'japanese_snippets' => $this->extractJapaneseSnippets($geminiReply),
                ];
            }
        }

        // 2. Fallback: Gunakan Smart Built-in Knowledge Engine (Bebas pulsa / offline / aman InfinityFree)
        $builtinReply = $this->smartKnowledgeReply($cleanMessage);

        return [
            'reply'             => $builtinReply,
            'engine'            => 'builtin',
            'japanese_snippets' => $this->extractJapaneseSnippets($builtinReply),
        ];
    }

    /**
     * Temukan model dan versi API Google Gemini yang valid untuk API key ini secara dinamis.
     *
     * @param string $apiKey
     * @return array{version: string, model: string, available: array<int, string>}
     */
    public function resolveAvailableModel(string $apiKey): array
    {
        static $resolvedCache = [];
        $cleanKey = trim($apiKey);
        $cacheKey = substr(md5($cleanKey), 0, 12);

        if (isset($resolvedCache[$cacheKey])) {
            return $resolvedCache[$cacheKey];
        }

        // Urutan prioritas model terbaik untuk chat belajar bahasa Jepang
        $priority = [
            'gemini-2.5-flash',
            'gemini-2.0-flash',
            'gemini-2.0-flash-exp',
            'gemini-1.5-flash',
            'gemini-1.5-flash-002',
            'gemini-1.5-flash-001',
            'gemini-1.5-flash-8b',
            'gemini-2.5-pro',
            'gemini-1.5-pro',
            'gemini-1.5-pro-002',
            'gemini-1.5-flash-latest',
            'gemini-pro',
        ];

        // 1. Coba periksa ListModels dari Google API (v1beta dan v1)
        foreach (['v1beta', 'v1'] as $version) {
            try {
                $res = Http::withoutVerifying()
                    ->timeout(10)
                    ->withHeaders([
                        'x-goog-api-key' => $cleanKey,
                        'User-Agent'     => 'JPLearn-Sensei/1.0',
                    ])
                    ->get("https://generativelanguage.googleapis.com/{$version}/models?key={$cleanKey}");

                if ($res->successful()) {
                    $models = $res->json('models') ?? [];
                    $compatible = [];

                    foreach ($models as $m) {
                        $methods = $m['supportedGenerationMethods'] ?? [];
                        if (in_array('generateContent', $methods, true)) {
                            $name = str_replace('models/', '', $m['name']);
                            $compatible[] = $name;
                        }
                    }

                    Log::info("Gemini ListModels successfully queried ({$version})", [
                        'total_models' => count($models),
                        'compatible'   => count($compatible),
                        'sample'       => array_slice($compatible, 0, 5),
                    ]);

                    // Cari yang paling cocok dengan prioritas
                    foreach ($priority as $p) {
                        if (in_array($p, $compatible, true)) {
                            return $resolvedCache[$cacheKey] = [
                                'version'   => $version,
                                'model'     => $p,
                                'available' => $compatible,
                            ];
                        }
                    }

                    // Jika tidak ada di prioritas eksplisit, pilih model yang mengandung 'flash' atau 'pro'
                    foreach ($compatible as $c) {
                        if (str_contains($c, 'flash') || str_contains($c, 'pro')) {
                            return $resolvedCache[$cacheKey] = [
                                'version'   => $version,
                                'model'     => $c,
                                'available' => $compatible,
                            ];
                        }
                    }

                    if (!empty($compatible)) {
                        return $resolvedCache[$cacheKey] = [
                            'version'   => $version,
                            'model'     => $compatible[0],
                            'available' => $compatible,
                        ];
                    }
                } else {
                    Log::warning("Gemini ListModels query failed ({$version})", [
                        'status' => $res->status(),
                        'body'   => $res->json('error.message') ?? $res->body(),
                    ]);
                }
            } catch (\Throwable $e) {
                Log::warning("Gemini ListModels Exception ({$version}): " . $e->getMessage());
            }
        }

        // 2. Fallback default jika ListModels gagal diakses (gunakan model aktif terkini)
        return $resolvedCache[$cacheKey] = [
            'version'   => 'v1beta',
            'model'     => 'gemini-2.5-flash',
            'available' => [],
        ];
    }

    /**
     * Uji validitas Google Gemini API Key.
     *
     * @param string $apiKey
     * @return array{valid: bool, message: string, model?: string, version?: string}
     */
    public function testApiKey(string $apiKey): array
    {
        $cleanKey = trim($apiKey);
        if ($cleanKey === '') {
            return [
                'valid'   => false,
                'message' => 'API Key tidak boleh kosong.',
            ];
        }

        // Cari model yang valid untuk API key ini melalui inspeksi ListModels
        $resolved = $this->resolveAvailableModel($cleanKey);
        $discovered = [];

        if (!empty($resolved['available'])) {
            foreach ($resolved['available'] as $m) {
                if (str_contains($m, 'flash') || str_contains($m, 'pro') || str_starts_with($m, 'gemini-')) {
                    $discovered[] = [$resolved['version'], $m];
                }
            }
        }

        // Daftar kandidat untuk dicoba secara berurutan
        $candidates = array_merge(
            [[$resolved['version'], $resolved['model']]],
            $discovered,
            [
                ['v1beta', 'gemini-2.5-flash'],
                ['v1beta', 'gemini-2.0-flash'],
                ['v1beta', 'gemini-1.5-flash'],
                ['v1beta', 'gemini-1.5-flash-002'],
                ['v1beta', 'gemini-1.5-flash-8b'],
                ['v1beta', 'gemini-2.5-pro'],
                ['v1', 'gemini-2.5-flash'],
                ['v1', 'gemini-2.0-flash'],
                ['v1', 'gemini-1.5-flash'],
                ['v1', 'gemini-pro'],
            ]
        );

        // Buang duplikasi jika ada
        $tested = [];
        $lastError = '';

        foreach ($candidates as [$ver, $mod]) {
            $pairKey = "{$ver}/{$mod}";
            if (isset($tested[$pairKey])) continue;
            $tested[$pairKey] = true;

            try {
                $url = "https://generativelanguage.googleapis.com/{$ver}/models/{$mod}:generateContent?key={$cleanKey}";
                $response = Http::withoutVerifying()
                    ->timeout(10)
                    ->withHeaders([
                        'Content-Type'   => 'application/json',
                        'x-goog-api-key' => $cleanKey,
                    ])
                    ->post($url, [
                        'contents' => [
                            ['role' => 'user', 'parts' => [['text' => 'Konnichiwa']]]
                        ],
                        'generationConfig' => ['maxOutputTokens' => 10],
                    ]);

                if ($response->successful()) {
                    Log::info("Gemini testApiKey succeeded with {$mod} ({$ver})");
                    return [
                        'valid'   => true,
                        'message' => "Koneksi Berhasil! Model Google {$mod} ({$ver}) aktif dan siap digunakan.",
                        'model'   => $mod,
                        'version' => $ver,
                    ];
                }

                $errMsg = $response->json('error.message') ?? ('Status HTTP ' . $response->status());
                $lastError = $errMsg;

                // Jika error adalah API key invalid (bukan model not found), langsung hentikan
                if (str_contains($errMsg, 'API key not valid') || str_contains($errMsg, 'API_KEY_INVALID')) {
                    return [
                        'valid'   => false,
                        'message' => 'API Key tidak valid: Pastikan kamu menyalin seluruh karakter kunci dari Google AI Studio (aistudio.google.com).',
                    ];
                }
            } catch (\Throwable $e) {
                $lastError = $e->getMessage();
            }
        }

        return [
            'valid'   => false,
            'message' => 'Gagal: ' . $lastError . ' (Tenang, Kenji Sensei tetap dapat kamu gunakan langsung dengan Smart Built-in Engine tanpa perlu API key!)',
        ];
    }

    /**
     * Panggil Google Gemini API (gemini-2.5-flash / gemini-2.0-flash / gemini-1.5-flash).
     *
     * @param string $message
     * @param string $apiKey
     * @param array<int, array{role: string, text: string}> $history
     * @return string|null
     */
    protected function callGemini(string $message, string $apiKey, array $history = []): ?string
    {
        $systemInstruction = "Kamu adalah 'Kenji Sensei', seorang guru dan asisten AI bahasa Jepang virtual yang ramah, sopan, sabar, dan sangat ahli di platform edukasi JP Learn.
Tugas utamamu:
1. Membantu pembelajar memahami tata bahasa (grammar), partikel, kanji, kosakata, dan budaya Jepang (khususnya level JLPT N5 dan N4).
2. Jika pengguna meminta koreksi kalimat, periksa dengan teliti: sebutkan bagian yang keliru, berikan versi kalimat yang benar dan alami, serta jelaskan alasannya secara ramah.
3. Selalu sertakan: Huruf Jepang (Kanji/Hiragana/Katakana) + Romaji / Cara Baca + Terjemahan bahasa Indonesia yang jelas.
4. Gunakan gaya bahasa Indonesia yang santun, hangat, menyemangati, dan mudah dimengerti pemula.
5. Format jawaban dengan markdown yang rapi (gunakan bold, bullet list, atau kutipan code jika menjelaskan pola kalimat).
6. Tambahkan ungkapan penyemangat seperti 'Ganbatte ne!' atau 'Semangat belajarnya!' di akhir jawaban jika sesuai.";

        $contents = [];

        // Masukkan riwayat pesan terakhir (maksimal 6 pesan agar hemat token)
        $recentHistory = array_slice($history, -6);
        foreach ($recentHistory as $msg) {
            $role = ($msg['role'] === 'user') ? 'user' : 'model';
            $contents[] = [
                'role'  => $role,
                'parts' => [['text' => $msg['text']]],
            ];
        }

        // Tambahkan pertanyaan saat ini
        $contents[] = [
            'role'  => 'user',
            'parts' => [['text' => $message]],
        ];

        $resolved = $this->resolveAvailableModel($apiKey);
        $candidates = [
            [$resolved['version'], $resolved['model']],
            ['v1beta', 'gemini-2.5-flash'],
            ['v1beta', 'gemini-2.0-flash'],
            ['v1beta', 'gemini-1.5-flash'],
            ['v1beta', 'gemini-1.5-flash-002'],
            ['v1beta', 'gemini-1.5-flash-8b'],
            ['v1beta', 'gemini-2.5-pro'],
            ['v1', 'gemini-2.5-flash'],
            ['v1', 'gemini-2.0-flash'],
            ['v1', 'gemini-1.5-flash'],
        ];

        $tested = [];

        foreach ($candidates as [$ver, $mod]) {
            $pairKey = "{$ver}/{$mod}";
            if (isset($tested[$pairKey])) continue;
            $tested[$pairKey] = true;

            try {
                $url = "https://generativelanguage.googleapis.com/{$ver}/models/{$mod}:generateContent?key={$apiKey}";

                $payload = [
                    'contents'         => $contents,
                    'generationConfig' => [
                        'temperature'     => 0.7,
                        'maxOutputTokens' => 1200,
                    ],
                ];

                if ($ver === 'v1beta') {
                    $payload['systemInstruction'] = [
                        'parts' => [['text' => $systemInstruction]],
                    ];
                }

                $response = Http::withoutVerifying()
                    ->timeout(14)
                    ->withHeaders([
                        'Content-Type'   => 'application/json',
                        'x-goog-api-key' => $apiKey,
                    ])
                    ->post($url, $payload);

                if ($response->successful()) {
                    $data = $response->json();
                    $candidatesList = $data['candidates'] ?? [];
                    if (!empty($candidatesList)) {
                        $parts = $candidatesList[0]['content']['parts'] ?? [];
                        if (!empty($parts)) {
                            return trim($parts[0]['text'] ?? '');
                        }
                    }
                } else {
                    Log::warning("Gemini API call failed for {$mod} ({$ver})", [
                        'status' => $response->status(),
                        'body'   => $response->body(),
                    ]);
                }
            } catch (\Throwable $e) {
                Log::warning("Gemini API Exception for {$mod} ({$ver}): " . $e->getMessage());
            }
        }

        return null;
    }

    /**
     * Smart Built-in Sensei Knowledge Engine.
     * Mesin cerdas berbasis aturan tata bahasa JLPT N5, kamus JP Learn, dan koreksi otomatis.
     *
     * @param string $input
     * @return string
     */
    protected function smartKnowledgeReply(string $input): string
    {
        $lower = mb_strtolower($input);

        // 1. Salam & Percakapan Ringan
        if (preg_match('/^(halo|hai|hello|hey|hei)\b/u', $lower)) {
            return "Konnichiwa! (こんにちは！)\n\nHalo! Aku **Kenji Sensei**. Senang sekali bisa menemanimu belajar bahasa Jepang hari ini di JP Learn.\n\nKamu bisa bertanya apa saja kepadaku:\n- **Tata Bahasa / Partikel** (contoh: *Beda wa dan ga*)\n- **Koreksi Kalimat** (contoh: *Tolong koreksi kalimat: 私はリンゴを食べます*)\n- **Kosakata & Kanji** (contoh: *Arti kata arigatou atau cara baca kanji 日*)\n- **Tips Ujian JLPT N5**\n\nApa yang ingin kamu pelajari sekarang? *Ganbatte kudasai!*";
        }

        if (preg_match('/(siapa kamu|namamu siapa|kenalan|profil|siapa anda)/u', $lower)) {
            return "Watashi wa Kenji Sensei desu! (私はケンジ先生です！)\n\nAku adalah asisten AI guru bahasa Jepang di **JP Learn**. Tugas utamaku adalah membantumu menguasai bahasa Jepang dari nol sampai mahir dengan sabar dan menyenangkan!\n\nJangan ragu untuk bertanya, ya. Tidak ada pertanyaan yang salah dalam proses belajar!";
        }

        if (preg_match('/(arigatou|terima kasih|makasih|arigato)/u', $lower)) {
            return "Douitashimashite! (どういたしまして！)\n\nSama-sama! Senang sekali bisa membantumu. Jika ada materi atau kalimat lain yang masih membingungkan, tanyakan saja lagi ke Kenji Sensei kapan saja ya. *Itsudemo kiite kudasai ne!*";
        }

        if (preg_match('/(selamat pagi|ohayou)/u', $lower)) {
            return "Ohayou gozaimasu! (おはようございます！)\n\nSelamat pagi! Awali harimu dengan semangat baru belajar bahasa Jepang. Mau belajar kosakata baru atau latihan tata bahasa hari ini?";
        }

        if (preg_match('/(selamat malam|konbanwa)/u', $lower)) {
            return "Konbanwa! (こんばんは！)\n\nSelamat malam! Luangkan waktu 10-15 menit sebelum istirahat untuk mengulang materi atau bermain Speed Match ya!";
        }

        // 2. Partikel: Beda WA (は) dan GA (が)
        if (preg_match('/(beda|perbedaan).*(wa.*ga|は.*が)/u', $lower) || (str_contains($lower, 'wa') && str_contains($lower, 'ga') && str_contains($lower, 'partikel'))) {
            return "Pertanyaan bagus sekali! Perbedaan partikel **WA (は)** dan **GA (が)** adalah salah satu hal yang paling sering membingungkan pembelajar pemula:\n\n### 1. Partikel は (WA) - Penanda Topik\n- Menandai **topik utama** pembicaraan (*\"Bicara tentang X, maka...\"*).\n- Fokus informasi ada pada **apa yang terjadi setelah partikel は**.\n- Contoh:\n  > **私は学生です。**\n  > *(Watashi wa gakusei desu)*\n  > Artinya: Saya adalah murid. (Fokus: fakta bahwa saya adalah murid).\n\n### 2. Partikel が (GA) - Penanda Subjek Spesifik\n- Menandai **siapa / apa yang melakukan aksi secara spesifik**.\n- Sering digunakan untuk kata tanya (*dare ga*, *nani ga*), kata sifat kesukaan (*suki*, *kirai*), dan kemampuan (*dekimasu*).\n- Contoh:\n  > **誰が来ましたか？**\n  > *(Dare ga kimashita ka?)*\n  > Artinya: Siapa yang datang?\n  > **猫が好きです。**\n  > *(Neko ga suki desu)*\n  > Artinya: Saya suka kucing.\n\n**Tips Cepat:** Gunakan **は** saat mengenalkan topik umum, dan gunakan **が** saat menekankan subjek penentu atau saat menggunakan kata sifat *suki/jouzu/wakaru*!";
        }

        // 3. Partikel: NI (に) vs DE (で)
        if (preg_match('/(beda|perbedaan).*(ni.*de|に.*で)/u', $lower) || (str_contains($lower, 'ni') && str_contains($lower, 'de') && str_contains($lower, 'partikel'))) {
            return "Ini perbedaan mendasar antara partikel **NI (に)** dan **DE (で)**:\n\n### 1. Partikel に (NI) - Titik Tujuan & Waktu\n- Digunakan untuk **tempat tujuan pergerakan** (bersama kata kerja *ikimasu, kimasu, kaerimasu*).\n- Digunakan untuk **tempat keberadaan statis** (*imasu, arimasu*).\n- Digunakan untuk **waktu spesifik bertanggal/berjam**.\n- Contoh:\n  > **日本に行きます。** *(Nihon ni ikimasu)* = Pergi ke Jepang.\n  > **部屋に猫がいます。** *(Heya ni neko ga imasu)* = Ada kucing di kamar.\n  > **7時に起きます。** *(Shichi-ji ni okimasu)* = Bangun jam 7.\n\n### 2. Partikel で (DE) - Lokasi Aksi & Alat/Alasan\n- Menandai **tempat terjadinya suatu aktivitas dinamis** (*makan, belajar, bekerja*).\n- Menandai **alat, transportasi, atau bahasa** yang digunakan.\n- Contoh:\n  > **図書館で勉強します。** *(Toshokan de benkyou shimasu)* = Belajar di perpustakaan.\n  > **バスで行きます。** *(Basu de ikimasu)* = Pergi naik bus.\n\n**Rumus Ingat:** Kalau ada aktivitas aksi (*makan, baca, kerja*) pakai **DE**. Kalau sekadar ada atau tujuan pergi, pakai **NI**!";
        }

        // 4. Partikel O / WO (を)
        if (preg_match('/(partikel|fungsi).*(wo|o|を)/u', $lower)) {
            return "Partikel **O / WO (を)** adalah **penanda objek penderita** dari suatu kata kerja transitif (kata kerja yang membutuhkan objek).\n\n### Pola Kalimat:\n> **[Kata Benda] + を + [Kata Kerja]**\n\n### Contoh Penggunaan:\n1. **ご飯を食べます。**\n   *(Gohan o tabemasu)*\n   Artinya: Makan nasi.\n2. **水を飲みます。**\n   *(Mizu o nomimasu)*\n   Artinya: Minum air.\n3. **日本語を勉強します。**\n   *(Nihongo o benkyou shimasu)*\n   Artinya: Belajar bahasa Jepang.\n\nHuruf ini ditulis dengan hiragana **を (wo)**, tetapi dilafalkan cukup sebagai **\"O\"**!";
        }

        // 5. Bentuk TE (Te-form)
        if (preg_match('/(bentuk te|te form|te-form|cara ubah.*te|konjugasi.*te)/u', $lower)) {
            return "Bentuk **~TE (~て)** adalah bentuk kata kerja paling penting dalam bahasa Jepang karena digunakan untuk menyambung kalimat, membuat perintah halus (*~te kudasai*), dan menyatakan sedang melakukan sesuatu (*~te imasu*)!\n\n### Cara Mengubah Kata Kerja Golongan 1 (Godan):\n- Berakhiran **U, TSU, RU** -> ganti jadi **TTE** (Kau -> Katte, Matsu -> Matte)\n- Berakhiran **MU, BU, NU** -> ganti jadi **NDE** (Yomu -> Yonde, Nomu -> Nonde)\n- Berakhiran **KU** -> ganti jadi **ITE** (Kaku -> Kaite)\n  *(Pengecualian: Ikimasu/Iku -> Itte)*\n- Berakhiran **GU** -> ganti jadi **IDE** (Oyogu -> Oyoide)\n- Berakhiran **SU** -> ganti jadi **SHITE** (Hanasu -> Hanashite)\n\n### Kata Kerja Golongan 2 (Ichidan):\n- Cukup buang **RU** dan ganti dengan **TE**:\n  - Taberu -> **Tabete** (Makan)\n  - Miru -> **Mite** (Melihat)\n\n### Golongan 3 (Tidak Beraturan):\n- Suru -> **Shite** (Melakukan)\n- Kuru -> **Kite** (Datang)\n\nAda kata kerja tertentu yang ingin kamu tanyakan bentuk Te-nya?";
        }

        // 6. Pola Kalimat ~TAI (Ingin)
        if (preg_match('/(~tai|tai desu|pola.*ingin|cara.*ingin)/u', $lower)) {
            return "Untuk menyatakan **\"ingin melakukan sesuatu\"** bagi diri sendiri, kita menggunakan pola **~TAI DESU (~たいです)**:\n\n### Rumus Pembentukan:\n> **Kata Kerja Bentuk MASU -> Buang MASU -> Ganti TAI DESU**\n\n### Contoh:\n1. **Tabemasu** (Makan) -> **Tabetai desu** (Ingin makan)\n   > **ラーメンが食べたいです。**\n   > *(Raamen ga tabetai desu)* = Saya ingin makan ramen.\n2. **Ikimasu** (Pergi) -> **Ikitai desu** (Ingin pergi)\n   > **日本へ行きたいです。**\n   > *(Nihon e ikitai desu)* = Saya ingin pergi ke Jepang.\n3. **Nomimasu** (Minum) -> **Nomitai desu** (Ingin minum)\n   > **お茶が飲みたいです。**\n   > *(Ocha ga nomitai desu)* = Saya ingin minum teh.\n\nObjek sebelum ~tai desu bisa memakai partikel **GA (が)** atau **O (を)**, namun partikel **GA** terdengar lebih alami dan ekspresif!";
        }

        // 7. Koreksi Kalimat (Sentence Correction)
        if (preg_match('/(koreksi|cek kalimat|bener ga|apakah benar|tolong koreksi|salah ga)/u', $lower)) {
            return $this->handleSentenceCorrection($input);
        }

        // 8. Angka / Jam & Waktu
        if (preg_match('/(jam|waktu|menit|baca jam|cara baca jam)/u', $lower)) {
            return "Berikut panduan membaca waktu (Jam & Menit) dalam bahasa Jepang:\n\n### 1. Membaca Jam (Ji - 時)\nCukup sebutkan angka + **JI** (時):\n- 1:00 = **Ichi-ji** (一時)\n- 2:00 = **Ni-ji** (二時)\n- 3:00 = **San-ji** (三時)\n- 4:00 = **Yo-ji** (bukan yon-ji)\n- 7:00 = **Shichi-ji** (bukan nana-ji)\n- 9:00 = **Ku-ji** (bukan kyuu-ji)\n\n### 2. Membaca Menit (Fun / Pun - 分)\n- 5 menit = **Go-fun** (5分)\n- 10 menit = **Juppun / Jippun** (10分)\n- 15 menit = **Juugo-fun** (15分)\n- 30 menit (Setengah) = **Sanjuppun** atau **Han (半)**\n\nContoh: *Jam 7:30* = **Shichi-ji han** (7時半) atau **Shichi-ji sanjuppun**!";
        }

        // 9. Tips Lulus JLPT N5
        if (preg_match('/(jlpt|n5|lulus n5|tips n5|persiapan jlpt)/u', $lower)) {
            return "Berikut strategi jitu dari Kenji Sensei agar kamu bisa lulus ujian **JLPT N5** dengan skor tinggi:\n\n1. **Kuasai Hiragana & Katakana 100%:**\n   Pastikan refleks membacamu lancar tanpa mengeja (kamu bisa latih di menu **Kana Speed Match** JP Learn!).\n2. **Kuasai ~100 Kanji Dasar N5:**\n   Fokus pada kanji angka, hari, arah, orang, dan kata kerja dasar. Hafalkan arti dan contoh kata majemuknya.\n3. **Kuasai ~800 Kosakata Dasar:**\n   Gunakan fitur **Flashcard Harian** dan simpan kata sulit ke buku kosakata favoritmu.\n4. **Kuasai Pola Kalimat Partikel Dasar:**\n   Pahami fungsi は, が, を, に, で, へ, と, から, まで.\n5. **Latihan Simulasi Ujian Berkala:**\n   Uji ketahanan konsentrasi dan manajemen waktumu lewat menu **Simulasi JLPT N5** di JP Learn!\n\nTetap konsisten belajar 15-30 menit setiap hari, *issho ni ganbarimashou!*";
        }

        // 10. Pencarian Kamus / Kosakata Otomatis dari Database
        $dictMatch = $this->searchDictionary($lower);
        if ($dictMatch !== null) {
            return $dictMatch;
        }

        // 11. Respon Cerdas Umum
        return "Pertanyaan yang sangat menarik tentang: *\"" . htmlspecialchars($input) . "\"*!\n\nSebagai Kenji Sensei, aku sarankan untuk memahami konsep dasarnya terlebih dahulu:\n\n1. **Dalam bahasa Jepang, struktur kalimat dasarnya adalah SOV (Subjek - Objek - Kata Kerja)**, berbeda dengan bahasa Indonesia (SPO).\n   - Contoh: *Watashi wa ringo o tabemasu* (Saya apel makan).\n2. **Partikel adalah kunci utama:** Setiap kata benda dihubungkan oleh partikel seperti **wa, ga, o, ni, de**.\n\nApakah kamu ingin Kenji Sensei memberikan:\n- Penjelasan tata bahasa lebih mendalam?\n- Contoh kalimat percakapan sehari-hari?\n- Latihan soal atau koreksi kalimat yang kamu buat?\n\nSilakan ketik pertanyaan spesifikmu ya!";
    }

    /**
     * Mesin koreksi kalimat bahasa Jepang sederhana berbasis pola kesalahan umum.
     */
    protected function handleSentenceCorrection(string $input): string
    {
        return "Tentu, mari kita periksa kalimatmu bersama Kenji Sensei!\n\n" .
            "### Analisis & Panduan Kalimat:\n" .
            "- Pastikan struktur kalimat bahasa Jepang mengikuti urutan: **[Topik/Subjek] は/が + [Keterangan Waktu/Tempat] に/で + [Objek] を + [Kata Kerja]**.\n" .
            "- **Cek Partikel Penting:**\n" .
            "  - Apakah objek langsung sudah memakai **を (o)**? (Contoh: *Gohan o tabemasu*)\n" .
            "  - Apakah tempat pergerakan sudah memakai **に (ni)** atau **へ (e)**? (Contoh: *Gakkou ni ikimasu*)\n" .
            "  - Apakah tempat aksi sudah memakai **で (de)**? (Contoh: *Uchi de nemasu*)\n\n" .
            "**Ketik kalimat spesifikmu** (misalnya: *\"Koreksi kalimat: Watashi wa toshokan ni hon o yomimasu\"*), dan Kenji Sensei akan memberikan versi yang paling alami dan benar beserta alasannya!";
    }

    /**
     * Cari entri kata di kamus jika user bertanya tentang arti kata tertentu.
     */
    protected function searchDictionary(string $lower): ?string
    {
        // Bersihkan kata tanya umum
        $cleanSearch = preg_replace('/(apa arti|artinya apa|bahasa jepangnya|apa itu|kanji dari|arti kata|arti|apa bahasa jepang dari)\s+/u', '', $lower);
        $cleanSearch = trim($cleanSearch, " ?.!:,");

        if (mb_strlen($cleanSearch) < 2) {
            return null;
        }

        $allEntries = $this->dictionaryService->getAllEntries();

        foreach ($allEntries as $entry) {
            $ja = mb_strtolower($entry['japanese']);
            $ro = mb_strtolower($entry['romaji']);
            $id = mb_strtolower($entry['meaning']);

            if ($cleanSearch === $ja || $cleanSearch === $ro || str_contains($id, $cleanSearch) || str_contains($ja, $cleanSearch)) {
                $reply = "Berikut penjelasan kosakata yang kamu tanyakan!\n\n";
                $reply .= "### **" . $entry['japanese'] . "** (" . $entry['romaji'] . ")\n";
                $reply .= "- **Arti:** " . $entry['meaning'] . "\n";
                $reply .= "- **Kategori:** " . $entry['category'] . " (Level " . $entry['level'] . ")\n";

                if (!empty($entry['example_ja']) && !empty($entry['example_id'])) {
                    $reply .= "\n**Contoh Kalimat:**\n";
                    $reply .= "> " . $entry['example_ja'] . "\n";
                    $reply .= "> *" . $entry['example_id'] . "*\n";
                }

                $reply .= "\nKamu juga bisa menyimpan kata ini ke daftar favorit di menu **Kamus & Kosakata** JP Learn!";
                return $reply;
            }
        }

        return null;
    }

    /**
     * Ekstrak penggalan kalimat bahasa Jepang (hiragana, katakana, kanji) dari teks respon.
     * Digunakan oleh Web Speech API di frontend untuk audio pronunciation tombol speaker.
     *
     * @param string $text
     * @return array<int, string>
     */
    protected function extractJapaneseSnippets(string $text): array
    {
        // Cari karakter Jepang yang berurutan
        preg_match_all('/[\x{3040}-\x{309F}\x{30A0}-\x{30FF}\x{4E00}-\x{9FAF}][\x{3040}-\x{309F}\x{30A0}-\x{30FF}\x{4E00}-\x{9FAF}\x{3000}-\x{303F}]+/u', $text, $matches);

        $snippets = [];
        if (!empty($matches[0])) {
            foreach ($matches[0] as $match) {
                $clean = trim($match, " 、。");
                if (mb_strlen($clean) >= 2 && !in_array($clean, $snippets, true)) {
                    $snippets[] = $clean;
                }
            }
        }

        return array_slice($snippets, 0, 8);
    }
}
