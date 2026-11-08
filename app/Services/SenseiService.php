<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
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
    public function respond(
        string $message,
        ?string $userApiKey = null,
        array $history = [],
        ?string $preferredModel = null,
        ?string $preferredVersion = null,
        string $engine = 'auto'
    ): array {
        $cleanMessage = trim($message);
        if ($cleanMessage === '') {
            return [
                'reply'             => 'Halo! Ada yang bisa Kenji Sensei bantu seputar bahasa Jepang hari ini? Silakan ketik pertanyaan atau pilih saran di bawah ya!',
                'engine'            => 'builtin',
                'japanese_snippets' => ['こんにちは！何か質問はありますか？'],
            ];
        }

        // 1. Jika pengguna secara eksplisit memilih Sensei Engine (Bawaan internal & instan)
        if ($engine === 'builtin') {
            $builtinReply = $this->smartKnowledgeReply($cleanMessage);
            return [
                'reply'             => $builtinReply,
                'engine'            => 'builtin',
                'japanese_snippets' => $this->extractJapaneseSnippets($builtinReply),
            ];
        }

        // 2. Hubungi Google Gemini API jika mode 'gemini' atau 'auto'
        $apiKey = $userApiKey ?: config('services.gemini.key', env('GEMINI_API_KEY'));
        if (!empty($apiKey)) {
            $geminiReply = $this->callGemini($cleanMessage, $apiKey, $history, $preferredModel, $preferredVersion);
            if ($geminiReply !== null) {
                return [
                    'reply'             => $geminiReply,
                    'engine'            => 'gemini',
                    'japanese_snippets' => $this->extractJapaneseSnippets($geminiReply),
                ];
            }
        } elseif ($engine === 'gemini') {
            // Mode Gemini dipilih tapi API key kosong
            return [
                'reply'             => "Kamu sedang berada dalam mode **AI Gemini**, namun Google Gemini API Key belum terpasang di browser ini.\n\nSilakan klik tombol **Pengaturan API** di kanan atas untuk memasukkan API Key gratis milikmu, atau alihkan tombol mode ke **Sensei Engine** untuk mulai berdiskusi secara instan tanpa API key!",
                'engine'            => 'builtin',
                'japanese_snippets' => [],
            ];
        }

        // 3. Fallback: Gunakan Smart Built-in Knowledge Engine (Bebas pulsa / offline / aman InfinityFree)
        $builtinReply = $this->smartKnowledgeReply($cleanMessage);
        $note = ($engine === 'gemini') ? "\n\n*(Catatan: Mode AI Gemini sedang tidak dapat dijangkau atau kuota API habis, Kenji Sensei otomatis menjawab dengan Sensei Engine)*" : "";

        return [
            'reply'             => $builtinReply . $note,
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

        // Periksa persistent cache untuk model yang sebelumnya sudah terbukti sukses
        $saved = Cache::get('gemini_model_' . $cacheKey);
        if (is_array($saved) && !empty($saved['model']) && !empty($saved['version'])) {
            return $resolvedCache[$cacheKey] = [
                'version'   => $saved['version'],
                'model'     => $saved['model'],
                'available' => [$saved['model']],
            ];
        }

        // Urutan prioritas model terbaik Google Gemini (didahulukan model aktif 2026 yang kompatibel free tier)
        $priority = [
            'gemini-flash-lite-latest',
            'gemini-3.8-flash',
            'gemini-2.5-flash-lite',
            'gemini-flash-latest',
            'gemini-2.5-flash',
            'gemini-2.0-flash',
            'gemini-2.0-flash-exp',
            'gemini-1.5-flash',
            'gemini-1.5-flash-002',
            'gemini-1.5-flash-001',
            'gemini-1.5-flash-8b',
            'gemini-3.1-pro-preview',
            'gemini-pro-latest',
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

        // 2. Fallback default jika ListModels gagal diakses
        return $resolvedCache[$cacheKey] = [
            'version'   => 'v1beta',
            'model'     => 'gemini-flash-lite-latest',
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

        $cacheKey = substr(md5($cleanKey), 0, 12);

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

        // Daftar kandidat untuk dicoba secara berurutan, dahulukan model yang didukung free tier terkini
        $candidates = array_merge(
            [
                ['v1beta', 'gemini-flash-lite-latest'],
                ['v1beta', 'gemini-3.8-flash'],
                ['v1beta', 'gemini-2.5-flash-lite'],
                [$resolved['version'], $resolved['model']],
            ],
            $discovered,
            [
                ['v1beta', 'gemini-flash-latest'],
                ['v1beta', 'gemini-2.5-flash'],
                ['v1beta', 'gemini-2.0-flash'],
                ['v1beta', 'gemini-1.5-flash'],
                ['v1beta', 'gemini-1.5-flash-002'],
                ['v1beta', 'gemini-1.5-flash-8b'],
                ['v1beta', 'gemini-2.5-pro'],
                ['v1', 'gemini-flash-lite-latest'],
                ['v1', 'gemini-3.8-flash'],
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
                    Cache::put('gemini_model_' . $cacheKey, [
                        'version' => $ver,
                        'model'   => $mod,
                    ], now()->addDays(7));

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
     * Panggil Google Gemini API secara andal dengan auto-fallback model.
     *
     * @param string $message
     * @param string $apiKey
     * @param array<int, array{role: string, text: string}> $history
     * @param string|null $preferredModel
     * @param string|null $preferredVersion
     * @return string|null
     */
    protected function callGemini(
        string $message,
        string $apiKey,
        array $history = [],
        ?string $preferredModel = null,
        ?string $preferredVersion = null
    ): ?string {
        $cleanKey = trim($apiKey);
        if ($cleanKey === '') {
            return null;
        }

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

        $cacheKey = substr(md5($cleanKey), 0, 12);
        $cached = Cache::get('gemini_model_' . $cacheKey);

        // Kumpulkan kandidat model
        $leadCandidates = [];

        // 1. Model yang diteruskan dari frontend (terverifikasi saat uji koneksi)
        if (!empty($preferredModel)) {
            $leadCandidates[] = [!empty($preferredVersion) ? $preferredVersion : 'v1beta', $preferredModel];
        }

        // 2. Model yang tersimpan di cache backend
        if (is_array($cached) && !empty($cached['model'])) {
            $leadCandidates[] = [$cached['version'] ?? 'v1beta', $cached['model']];
        }

        // 3. Model modern bawaan 2026 yang aktif untuk free tier
        $leadCandidates[] = ['v1beta', 'gemini-flash-lite-latest'];
        $leadCandidates[] = ['v1beta', 'gemini-3.8-flash'];
        $leadCandidates[] = ['v1beta', 'gemini-2.5-flash-lite'];

        // 4. Model dari ListModels
        $resolved = $this->resolveAvailableModel($cleanKey);
        $leadCandidates[] = [$resolved['version'], $resolved['model']];

        $discovered = [];
        if (!empty($resolved['available'])) {
            foreach ($resolved['available'] as $m) {
                if (str_contains($m, 'flash') || str_contains($m, 'pro') || str_starts_with($m, 'gemini-')) {
                    $discovered[] = [$resolved['version'], $m];
                }
            }
        }

        $fallbackCandidates = [
            ['v1beta', 'gemini-flash-latest'],
            ['v1beta', 'gemini-2.5-flash'],
            ['v1beta', 'gemini-2.0-flash'],
            ['v1beta', 'gemini-1.5-flash'],
            ['v1beta', 'gemini-1.5-flash-002'],
            ['v1beta', 'gemini-1.5-flash-8b'],
            ['v1beta', 'gemini-2.5-pro'],
            ['v1', 'gemini-flash-lite-latest'],
            ['v1', 'gemini-3.8-flash'],
            ['v1', 'gemini-2.5-flash'],
            ['v1', 'gemini-2.0-flash'],
            ['v1', 'gemini-1.5-flash'],
        ];

        $candidates = array_merge($leadCandidates, $discovered, $fallbackCandidates);
        $tested = [];

        foreach ($candidates as [$ver, $mod]) {
            $pairKey = "{$ver}/{$mod}";
            if (isset($tested[$pairKey])) continue;
            $tested[$pairKey] = true;

            try {
                $url = "https://generativelanguage.googleapis.com/{$ver}/models/{$mod}:generateContent?key={$cleanKey}";

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
                    ->timeout(15)
                    ->withHeaders([
                        'Content-Type'   => 'application/json',
                        'x-goog-api-key' => $cleanKey,
                    ])
                    ->post($url, $payload);

                // Jika gagal karena systemInstruction tidak didukung model, coba kirim tanpa systemInstruction
                if (!$response->successful() && isset($payload['systemInstruction'])) {
                    $err = $response->json('error.message') ?? '';
                    if (str_contains($err, 'systemInstruction') || str_contains($err, 'system_instruction')) {
                        unset($payload['systemInstruction']);
                        $fallbackContents = $contents;
                        $fallbackContents[count($fallbackContents) - 1]['parts'][0]['text'] =
                            "[Instruksi: {$systemInstruction}]\n\n" . $message;
                        $payload['contents'] = $fallbackContents;

                        $response = Http::withoutVerifying()
                            ->timeout(15)
                            ->withHeaders([
                                'Content-Type'   => 'application/json',
                                'x-goog-api-key' => $cleanKey,
                            ])
                            ->post($url, $payload);
                    }
                }

                if ($response->successful()) {
                    $data = $response->json();
                    $candidatesList = $data['candidates'] ?? [];
                    if (!empty($candidatesList)) {
                        $parts = $candidatesList[0]['content']['parts'] ?? [];
                        if (!empty($parts)) {
                            $replyText = trim($parts[0]['text'] ?? '');
                            if ($replyText !== '') {
                                Log::info("Gemini callGemini succeeded using {$mod} ({$ver})");
                                Cache::put('gemini_model_' . $cacheKey, [
                                    'version' => $ver,
                                    'model'   => $mod,
                                ], now()->addDays(7));
                                return $replyText;
                            }
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
     * Mesin cerdas berbasis tata bahasa JLPT N5/N4, kamus komprehensif, dan koreksi otomatis.
     *
     * @param string $input
     * @return string
     */
    protected function smartKnowledgeReply(string $input): string
    {
        $lower = mb_strtolower(trim($input));

        // 1. Salam, Ucapan, & Budaya Sehari-hari (Aisatsu)
        if ($res = $this->handleGreetingsAndSocial($lower)) {
            return $res;
        }

        // 2. Koreksi Kalimat (Sentence Correction)
        if (preg_match('/(koreksi|cek kalimat|bener ga|apakah benar|tolong koreksi|salah ga|kalimat ini benar|cek grammar)/u', $lower)) {
            return $this->handleSentenceCorrection($input);
        }

        // 3. Partikel Tata Bahasa (Joshi)
        if ($res = $this->handleGrammarParticles($lower)) {
            return $res;
        }

        // 4. Konjugasi Kata Kerja (Bentuk Te, Nai, Ta, Kamus, Potensial, dll)
        if ($res = $this->handleVerbConjugations($lower)) {
            return $res;
        }

        // 5. Pola Kalimat Esensial (~tai, ~te kudasai, ~te mo ii, ~koto ga dekimasu, dll)
        if ($res = $this->handleGrammarPatterns($lower)) {
            return $res;
        }

        // 6. Kata Sifat (I-keiyoushi & Na-keiyoushi)
        if ($res = $this->handleAdjectives($lower)) {
            return $res;
        }

        // 7. Waktu, Jam, Menit, Hari, Tanggal, & Kalender
        if ($res = $this->handleTimeAndCalendar($lower)) {
            return $res;
        }

        // 8. Kata Tunjuk Kosoado & Kata Tanya (5W1H)
        if ($res = $this->handleKosoadoAndQuestions($lower)) {
            return $res;
        }

        // 9. Sebutan Keluarga (Kazoku)
        if ($res = $this->handleFamilyTerms($lower)) {
            return $res;
        }

        // 10. Tips Belajar & Panduan Ujian JLPT N5/N4
        if ($res = $this->handleJLPTAndStudyTips($lower)) {
            return $res;
        }

        // 11. Pencarian Kamus / Kosakata Otomatis
        if ($res = $this->searchDictionary($lower)) {
            return $res;
        }

        // 12. Fallback Penjelasan Konseptual Cerdas
        return $this->handleGeneralEducationalFallback($input);
    }

    /**
     * 1. Penanganan Salam, Etika, & Ucapan Sehari-hari (Aisatsu)
     */
    protected function handleGreetingsAndSocial(string $lower): ?string
    {
        if (preg_match('/^(halo|hai|hello|hey|hei)\b/u', $lower)) {
            return "Konnichiwa! (こんにちは！)\n\nHalo! Aku **Kenji Sensei**. Senang sekali bisa menemanimu belajar bahasa Jepang hari ini di JP Learn.\n\nKamu bisa bertanya apa saja kepadaku:\n- **Tata Bahasa & Partikel** (contoh: *Beda wa dan ga, fungsi partikel ni vs de*)\n- **Konjugasi Kata Kerja** (contoh: *Cara ubah bentuk ~TE, ~NAI, atau bentuk Kamus*)\n- **Koreksi Kalimat** (contoh: *Tolong koreksi kalimat: 私は図書館で本を読みます*)\n- **Kosakata & Kanji** (contoh: *Arti kata arigatou atau bahasa Jepangnya makan*)\n- **Tips & Strategi Lolos JLPT N5**\n\nApa yang ingin kamu pelajari sekarang? *Ganbatte kudasai!*";
        }

        if (preg_match('/(siapa kamu|namamu siapa|kenalan|profil|siapa anda)/u', $lower)) {
            return "Watashi wa Kenji Sensei desu! (私はケンジ先生です！)\n\nAku adalah asisten AI guru bahasa Jepang di platform **JP Learn**. Tugas utamaku adalah membimbing pembelajar bahasa Jepang, terutama tingkat pemula dan persiapan ujian JLPT N5/N4, dengan penjelasan yang ramah, santun, dan mudah dipahami.\n\nJangan pernah ragu untuk bertanya ya, tidak ada pertanyaan yang keliru dalam belajar!";
        }

        if (preg_match('/(arigatou|terima kasih|makasih|arigato)/u', $lower)) {
            return "Douitashimashite! (どういたしまして！)\n\nSama-sama! Senang sekali bisa membantumu memahami materi ini. Jika ada kalimat, kosakata, atau pola tata bahasa lain yang masih membingungkan, tanyakan saja lagi kapan pun ya. *Itsudemo kiite kudasai ne!*";
        }

        if (preg_match('/(selamat pagi|ohayou)/u', $lower)) {
            return "Ohayou gozaimasu! (おはようございます！)\n\nSelamat pagi! Awali harimu dengan semangat baru belajar bahasa Jepang. Untuk teman dekat atau keluarga cukup ucapkan *Ohayou*, namun untuk situasi sopan gunakan selalu *Ohayou gozaimasu*. Mau belajar materi apa pagi ini?";
        }

        if (preg_match('/(selamat malam|konbanwa)/u', $lower)) {
            return "Konbanwa! (こんばんは！)\n\nSelamat malam! Luangkan waktu 10-15 menit sebelum beristirahat untuk mengulang hafalan flashcard atau menguji pemahaman di kuis JP Learn ya!";
        }

        if (preg_match('/(selamat tidur|oyasumi)/u', $lower)) {
            return "Oyasumi nasai! (おやすみなさい！)\n\nSelamat tidur dan istirahat! Kepada teman sebaya atau yang lebih muda cukup katakan *Oyasumi*, sedangkan bentuk sopannya adalah *Oyasumi nasai*. Semoga mimpi indah dan besok kembali bersemangat belajar! *Mata ashita ne!*";
        }

        if (preg_match('/(itadakimasu|gochisousama|ucapan makan)/u', $lower)) {
            return "Ungkapan makan dalam budaya Jepang memiliki makna filosofis yang sangat mendalam:\n\n### 1. Itadakimasu (いただきます)\n- **Kapan diucapkan:** Tepat sebelum mulai makan, sambil merapatkan kedua telapak tangan di depan dada.\n- **Makna:** Secara harfiah berasal dari kata *itadaku* (menerima dengan hormat). Maknanya adalah ungkapan syukur atas pengorbanan nyawa bahan makanan (hewan, tumbuhan) dan rasa terima kasih kepada petani serta koki yang memasak.\n\n### 2. Gochisousama deshita (ごちそうさまでした)\n- **Kapan diucapkan:** Tepat setelah selesai makan.\n- **Makna:** Mengapresiasi hidangan yang telah dinikmati dan berterima kasih atas jamuan makanan tersebut.\n\n*Tips:* Di restoran kasual Jepang, mengucapkan *Gochisousama deshita* kepada koki saat beranjak keluar adalah bentuk etika yang sangat disukai!";
        }

        if (preg_match('/(ittekimasu|itterasshai|tadaima|okaeri|ucapan rumah)/u', $lower)) {
            return "Berikut pasangan ungkapan etika saat keluar dan pulang ke rumah di Jepang:\n\n### Saat Meninggalkan Rumah:\n1. **Ittekimasu! (行ってきます！)**\n   - Diucapkan oleh orang yang mau berangkat (*\"Saya pergi dulu!\"* - harfiah: saya pergi dan akan kembali).\n2. **Itterasshai! (いってらっしゃい！)**\n   - Jawaban dari orang yang tinggal di rumah (*\"Hati-hati di jalan! / Selamat jalan!\"*).\n\n### Saat Pulang ke Rumah:\n1. **Tadaima! (ただいま！)**\n   - Diucapkan saat baru tiba di rumah (*\"Saya pulang!\"*).\n2. **Okaerinasai! (おかえりなさい！)**\n   - Jawaban ramah orang rumah (*\"Selamat datang kembali!\"*). Untuk teman/keluarga dekat cukup ucapkan *Okaeri!*";
        }

        if (preg_match('/(otsukaresama|otsukare)/u', $lower)) {
            return "Otsukaresama deshita! (お疲れ様でした！)\n\nIni adalah salah satu ungkapan paling populer di Jepang! Digunakan untuk:\n1. Mengapresiasi kerja keras rekan kerja atau teman setelah menyelesaikan suatu tugas, pekerjaan kantor, atau kelas belajar.\n2. Salam perpisahan saat jam pulang kantor kepada atasan maupun rekan sejawat.\n3. Kepada teman dekat cukup katakan *Otsukare!*";
        }

        if (preg_match('/(beda.*(sumimasen.*gomen|gomen.*sumimasen)|shitsurei)/u', $lower)) {
            return "Berikut perbedaan kata maaf dan permisi dalam bahasa Jepang:\n\n1. **Sumimasen (すみません):**\n   - Bersifat multifungsi: bisa berarti *\"Permisi\"* (memanggil pelayan di restoran), *\"Maaf\"* ringan (tersenggol di kereta), dan *\"Terima kasih\"* atas bantuan orang asing.\n\n2. **Gomen nasai (ごめんなさい):**\n   - Permintaan maaf yang tulus dan berfokus pada rasa bersalah atas suatu kesalahan personal. Lebih cocok untuk keluarga, sahabat, atau teman sebaya.\n\n3. **Shitsurei shimasu (失礼します):**\n   - Digunakan dalam situasi formal bisnis/sekolah saat permisi masuk atau keluar dari ruangan atasan/guru (*\"Permisi atas kelancangan saya\"*).";
        }

        if (preg_match('/(sayounara|sayonara|jaa mata|mata ashita|selamat tinggal)/u', $lower)) {
            return "Ungkapan perpisahan dalam bahasa Jepang memiliki nuansa yang berbeda:\n\n1. **Jaa mata ne! (じゃあ、またね！)** atau **Mata ashita! (また明日！)**\n   - *\"Sampai jumpa!\"* atau *\"Sampai jumpa besok!\"*. Ini adalah ungkapan yang paling alami dan umum digunakan sehari-hari kepada teman atau rekan kerja.\n\n2. **Sayounara (さようなら):**\n   - Berhati-hatilah menggunakannya! *Sayounara* memiliki nuansa perpisahan untuk waktu yang sangat lama atau selamanya (seperti perpisahan kelulusan sekolah atau putus cinta). Hanya gunakan kepada guru jika pulang sekolah, tetapi hindari kepada teman sehari-hari.";
        }

        if (preg_match('/(hajimemashite|yoroshiku|perkenalan|jikoshoukai)/u', $lower)) {
            return "Berikut rumus perkenalan diri (*Jikoshoukai - 自己紹介*) yang sopan dan alami:\n\n### Contoh Teks Perkenalan:\n> **初めまして。**\n> *(Hajimemashite)* = Salam kenal.\n> **[Nama Kamu] と申します / です。**\n> *([Nama] to moushimasu / desu)* = Nama saya [Nama Kamu].\n> **インドネシアから来ました。**\n> *(Indoneshia kara kimashita)* = Saya berasal dari Indonesia.\n> **どうぞよろしくお願いします。**\n> *(Douzo yoroshiku onegaishimasu)* = Mohon bimbingan dan kerja samanya.\n\n*Tips:* Ucapkan sambil sedikit membungkukkan badan (*ojigi*) dengan sopan!";
        }

        if (preg_match('/(apa kabar|ogenki|genki desu ka)/u', $lower)) {
            return "Ogenki desu ka? (お元気ですか？)\n\nArtinya: *\"Apakah kamu sehat / apa kabar?\"*\n\n### Cara Menjawabnya:\n1. Jika sehat & bersemangat:\n   > **はい、元気です！おかげさまで。**\n   > *(Hai, genki desu! Okagesama de)* = Ya, saya sehat! Berkat doa Anda.\n2. Jika biasa saja:\n   > **まあまあです。**\n   > *(Maa-maa desu)* = Lumayan / biasa saja.";
        }

        return null;
    }

    /**
     * 2. Penanganan Partikel Tata Bahasa Lengkap (Joshi)
     */
    protected function handleGrammarParticles(string $lower): ?string
    {
        // WA vs GA
        if (preg_match('/(beda|perbedaan).*(wa.*ga|は.*が)/u', $lower) || (str_contains($lower, 'wa') && str_contains($lower, 'ga') && str_contains($lower, 'partikel'))) {
            return "Pertanyaan bagus sekali! Perbedaan partikel **WA (は)** dan **GA (が)** adalah salah satu hal yang paling sering membingungkan pembelajar pemula:\n\n### 1. Partikel は (WA) - Penanda Topik\n- Menandai **topik utama** pembicaraan (*\"Bicara tentang X, maka...\"*).\n- Fokus informasi ada pada **apa yang terjadi setelah partikel は**.\n- Contoh:\n  > **私は学生です。**\n  > *(Watashi wa gakusei desu)*\n  > Artinya: Saya adalah murid. (Fokus: fakta bahwa saya adalah murid).\n\n### 2. Partikel が (GA) - Penanda Subjek Spesifik\n- Menandai **siapa / apa yang melakukan aksi secara spesifik**.\n- Sering digunakan untuk kata tanya (*dare ga*, *nani ga*), kata sifat kesukaan (*suki*, *kirai*), dan kemampuan (*dekimasu*).\n- Contoh:\n  > **誰が来ましたか？**\n  > *(Dare ga kimashita ka?)* = Siapa yang datang?\n  > **猫が好きです。**\n  > *(Neko ga suki desu)* = Saya suka kucing.\n\n**Tips Cepat:** Gunakan **は** saat mengenalkan topik umum, dan gunakan **が** saat menekankan subjek penentu atau saat menggunakan kata sifat *suki/jouzu/wakaru*!";
        }

        // NI vs DE
        if (preg_match('/(beda|perbedaan).*(ni.*de|に.*で)/u', $lower) || (str_contains($lower, 'ni') && str_contains($lower, 'de') && str_contains($lower, 'partikel'))) {
            return "Ini perbedaan mendasar antara partikel **NI (に)** dan **DE (で)**:\n\n### 1. Partikel に (NI) - Titik Tujuan & Waktu\n- Digunakan untuk **tempat tujuan pergerakan** (bersama kata kerja *ikimasu, kimasu, kaerimasu*).\n- Digunakan untuk **tempat keberadaan statis** (*imasu, arimasu*).\n- Digunakan untuk **waktu spesifik bertanggal/berjam**.\n- Contoh:\n  > **日本に行きます。** *(Nihon ni ikimasu)* = Pergi ke Jepang.\n  > **部屋に猫がいます。** *(Heya ni neko ga imasu)* = Ada kucing di kamar.\n  > **7時に起きます。** *(Shichi-ji ni okimasu)* = Bangun jam 7.\n\n### 2. Partikel で (DE) - Lokasi Aksi & Alat/Alasan\n- Menandai **tempat terjadinya suatu aktivitas dinamis** (*makan, belajar, bekerja*).\n- Menandai **alat, transportasi, atau bahasa** yang digunakan.\n- Contoh:\n  > **図書館で勉強します。** *(Toshokan de benkyou shimasu)* = Belajar di perpustakaan.\n  > **バスで行きます。** *(Basu de ikimasu)* = Pergi naik bus.\n\n**Rumus Ingat:** Kalau ada aktivitas aksi (*makan, baca, kerja*) pakai **DE**. Kalau sekadar ada benda/orang atau tujuan pergi, pakai **NI**!";
        }

        // O / WO
        if (preg_match('/(partikel|fungsi).*(wo|o\b|を)/u', $lower) && !str_contains($lower, 'wa')) {
            return "Partikel **O / WO (を)** adalah **penanda objek penderita** dari suatu kata kerja transitif (kata kerja yang membutuhkan objek).\n\n### Pola Kalimat:\n> **[Kata Benda] + を + [Kata Kerja]**\n\n### Contoh Penggunaan:\n1. **ご飯を食べます。**\n   *(Gohan o tabemasu)* = Makan nasi.\n2. **水を飲みます。**\n   *(Mizu o nomimasu)* = Minum air.\n3. **日本語を勉強します。**\n   *(Nihongo o benkyou shimasu)* = Belajar bahasa Jepang.\n\nHuruf ini ditulis dengan hiragana **を (wo)**, tetapi dilafalkan cukup sebagai **\"O\"**!";
        }

        // KARA & MADE
        if (preg_match('/(kara|made|から|まで|dari.*sampai)/u', $lower) && str_contains($lower, 'partikel')) {
            return "Pasangan partikel **KARA (から)** dan **MADE (まで)** digunakan untuk menyatakan batas awal dan batas akhir (waktu maupun tempat):\n\n### Rumus:\n> **[Titik Awal] + から + [Titik Akhir] + まで**\n\n### Contoh Waktu:\n> **9時から5時まで働きます。**\n> *(Ku-ji kara go-ji made hatarakimasu)*\n> Artinya: Bekerja dari jam 9 sampai jam 5.\n\n### Contoh Tempat:\n> **東京から大阪まで新幹線で行きます。**\n> *(Toukyou kara Oosaka made shinkansen de ikimasu)*\n> Artinya: Pergi dari Tokyo sampai Osaka naik Shinkansen.\n\n*Catatan Tambahan:* Kata **KARA** setelah kalimat penjelas juga bisa berarti **\"karena / sebab\"** (Contoh: *Ame desu kara, ikimasen* = Karena hujan, saya tidak pergi).";
        }

        // TO vs YA vs TOKA
        if (preg_match('/(beda|perbedaan).*(to.*ya|ya.*to|と.*や)/u', $lower) || (str_contains($lower, 'partikel') && (str_contains($lower, 'dan') || str_contains($lower, 'to') && str_contains($lower, 'ya')))) {
            return "Ketiga partikel ini sama-sama berarti **\"dan\"**, tetapi konteks penggunaannya berbeda:\n\n1. **Partikel と (TO) - Daftar Pasti & Lengkap:**\n   - Digunakan saat menyebutkan **semua barang tanpa ada yang tertinggal**.\n   - Contoh: *Ringo to mikan o kaimashita* = Saya membeli apel dan jeruk (hanya dua itu saja yang dibeli).\n\n2. **Partikel や (YA) - Daftar Sebagian (Ada yang lain):**\n   - Digunakan saat menyebutkan beberapa contoh perwakilan, dan masih ada benda lain yang tidak disebutkan.\n   - Sering dipadukan dengan kata **nado (など = dll)**.\n   - Contoh: *Hon ya pen nado ga arimasu* = Ada buku, pena, dan lain-lain.\n\n3. **Partikel とか (TOKA) - Bahasa Percakapan Kasual:**\n   - Mirip dengan *ya*, tetapi bernuansa lebih santai saat mengobrol lisan sehari-hari.";
        }

        // HE vs NI
        if (preg_match('/(beda|perbedaan).*(he.*ni|e.*ni|へ.*に)/u', $lower) || (str_contains($lower, 'partikel') && str_contains($lower, 'arah'))) {
            return "Perbedaan partikel **E / HE (へ)** dan **NI (に)** saat menyatakan tujuan pergerakan:\n\n1. **Partikel へ (E):**\n   - Menekankan **arah perjalanan** (*heading towards*).\n   - Contoh: *Nihon e ikimasu* (Pergi mengarah ke Jepang).\n   - Ditulis dengan huruf hiragana **へ (he)**, tetapi dilafalkan sebagai **\"E\"**.\n\n2. **Partikel に (NI):**\n   - Menekankan **titik akhir kedatangan / tujuan pasti** (*arriving at*).\n   - Contoh: *Nihon ni ikimasu* (Pergi sampai tiba di Jepang).\n\nDalam percakapan praktis sehari-hari untuk kata kerja *ikimasu, kimasu, kaerimasu*, kedua partikel ini sering dapat saling menggantikan!";
        }

        // MO
        if (preg_match('/(partikel|fungsi).*(mo\b|も)/u', $lower) && (str_contains($lower, 'juga') || str_contains($lower, 'arti') || str_contains($lower, 'fungsi'))) {
            return "Partikel **MO (も)** memiliki arti **\"juga\"** atau **\"pun\"**:\n\n### Aturan Penggunaan:\n- Partikel **MO** menggantikan langsung partikel **WA (は)**, **GA (が)**, dan **O (を)**.\n- Contoh:\n  1. **田中さんは日本人です。佐藤さんも日本人です。**\n     *(Tanaka-san wa nihonjin desu. Satou-san mo nihonjin desu)*\n     Artinya: Tuan Tanaka orang Jepang. Tuan Satou **juga** orang Jepang.\n  2. **パンを食べました。ご飯も食べました。**\n     *(Pan o tabemashita. Gohan mo tabemashita)*\n     Artinya: Makan roti. Makan nasi **juga**.\n\nJika dipadukan dengan kata tanya negatif (*doko mo ikimasen*), artinya menjadi **\"tidak pergi ke mana pun\"**!";
        }

        // NO
        if (preg_match('/(partikel|fungsi).*(no\b|の)/u', $lower) && (str_contains($lower, 'punya') || str_contains($lower, 'milik') || str_contains($lower, 'fungsi') || str_contains($lower, 'arti'))) {
            return "Partikel **NO (の)** berfungsi sebagai penghubung dua kata benda:\n\n1. **Menyatakan Kepemilikan (Posesif):**\n   > **私の本** *(Watashi no hon)* = Buku saya.\n   > **先生の車** *(Sensei no kuruma)* = Mobil guru.\n\n2. **Menyatakan Asal / Instansi:**\n   > **インドネシアの友達** *(Indoneshia no tomodachi)* = Teman dari Indonesia.\n   > **トヨタの社員** *(Toyota no shain)* = Karyawan perusahaan Toyota.\n\n3. **Menyatakan Bahan atau Jenis:**\n   > **日本語の本** *(Nihongo no hon)* = Buku bahasa Jepang.";
        }

        // NE vs YO
        if (preg_match('/(beda|perbedaan).*(ne.*yo|yo.*ne|ね.*よ)/u', $lower) || (str_contains($lower, 'partikel') && (str_contains($lower, 'ne') || str_contains($lower, 'yo')))) {
            return "Partikel akhiran kalimat **NE (ね)** dan **YO (よ)** memberikan nuansa rasa bahasa:\n\n1. **Partikel ね (NE) - Meminta Persetujuan (*\"Ya kan?\"*):**\n   - Digunakan saat pembicara mengira lawan bicara juga sepakat atau mengetahui hal yang sama.\n   - Contoh: **今日はいい天気ですね。** *(Kyou wa ii tenki desu ne)* = Hari ini cuacanya bagus ya.\n\n2. **Partikel よ (YO) - Memberi Tahu Hal Baru (*\"Lho! / Lho ya!\"*):**\n   - Digunakan saat pembicara memberikan informasi yang belum diketahui oleh lawan bicara.\n   - Contoh: **このラーメンはおいしいですよ！** *(Kono raamen wa oishii desu yo!)* = Ramen ini enak banget lho!\n\nKombinasi **よね (yo ne)** berarti pembicara memberi tahu informasi sekaligus meminta penegasan (*\"Iya kan?\"*)!";
        }

        // YORI vs HOU GA
        if (preg_match('/(yori|hou ga|perbandingan|lebih daripada)/u', $lower) && str_contains($lower, 'kalimat')) {
            return "Untuk membuat kalimat **perbandingan (komparasi)** dalam bahasa Jepang, kita menggunakan pola **HOU GA** dan **YORI**:\n\n### Rumus:\n> **[Benda A] のほうが [Benda B] より [Kata Sifat] です**\n*(Benda A lebih [sifat] daripada Benda B)*\n\n### Contoh:\n> **電車の人車のほうが車より速いです。**\n> *(Densha no hou ga kuruma yori hayai desu)*\n> Artinya: Kereta lebih cepat daripada mobil.\n\n> **日本語のほうが英語より面白いです。**\n> *(Nihongo no hou ga eigo yori omoshiroi desu)*\n> Artinya: Bahasa Jepang lebih menarik daripada bahasa Inggris.";
        }

        // Rangkuman Partikel Dasar
        if (preg_match('/(rangkum.*partikel|sebutkan.*partikel|daftar partikel|macam.*partikel)/u', $lower)) {
            return "Berikut ringkasan cepat 9 partikel dasar wajib JLPT N5:\n\n1. **は (wa):** Penanda topik utama kalimat.\n2. **が (ga):** Penanda subjek spesifik, kata tanya, kesukaan (*suki*), dan kemampuan.\n3. **を (o):** Penanda objek penderita langsung (*makan nasi, baca buku*).\n4. **に (ni):** Titik tujuan pergerakan, letak keberadaan statis, waktu bertanggal/berjam.\n5. **で (de):** Tempat berlangsungnya aksi aktif, alat transportasi, atau bahasa.\n6. **へ (e):** Arah pergerakan tujuan (*menuju ke*).\n7. **と (to):** Menyatakan \"dan\" (daftar lengkap) atau \"bersama dengan orang lain\".\n8. **から / まで (kara / made):** Dari ... sampai ...\n9. **も (mo):** Menyatakan \"juga / pun\".";
        }

        return null;
    }

    /**
     * 3. Penanganan Konjugasi Kata Kerja Lengkap (Doushi)
     */
    protected function handleVerbConjugations(string $lower): ?string
    {
        // 3 Golongan Kata Kerja
        if (preg_match('/(golongan kata kerja|kelompok kata kerja|godan|ichidan|irreguler)/u', $lower)) {
            return "Dalam bahasa Jepang, kata kerja dibagi menjadi **3 Golongan besar**:\n\n### 1. Golongan 1 (Godan Doushi - 五段動詞)\n- Kata kerja yang berakhiran vokal ~U (seperti: *kaku, nomu, hanasu, kau, matsu, oyogu*).\n- Kata kerja berakhiran ~RU yang sebelum huruf RU adalah vokal A, O, atau U (*wakaru, toru*).\n\n### 2. Golongan 2 (Ichidan Doushi - 一段動詞)\n- Kata kerja berakhiran **~RU** di mana huruf persis sebelum RU berbunyi vokal **I** atau **E**.\n- Contoh: *Taberu* (E-ru), *Miru* (I-ru), *Neru* (E-ru), *Okiru* (I-ru).\n\n### 3. Golongan 3 (Fukisoku Doushi - 不規則動詞 / Tidak Beraturan)\n- Hanya ada **2 kata kerja saja** di seluruh bahasa Jepang:\n  1. **する (Suru)** = Melakukan\n  2. **来る (Kuru)** = Datang\n\nPembagian golongan ini menentukan rumus perubahan ke bentuk ~TE, ~NAI, dan ~TA!";
        }

        // Bentuk TE
        if (preg_match('/(bentuk te|te form|te-form|cara ubah.*te|konjugasi.*te)/u', $lower)) {
            return "Bentuk **~TE (~て)** adalah bentuk kata kerja paling penting dalam bahasa Jepang karena digunakan untuk menyambung kalimat, membuat perintah halus (*~te kudasai*), dan menyatakan sedang melakukan sesuatu (*~te imasu*)!\n\n### Cara Mengubah Kata Kerja Golongan 1 (Godan):\n- Berakhiran **U, TSU, RU** -> ganti jadi **TTE** (Kau -> Katte, Matsu -> Matte, Toru -> Totte)\n- Berakhiran **MU, BU, NU** -> ganti jadi **NDE** (Yomu -> Yonde, Asobu -> Asonde, Shinu -> Shinde)\n- Berakhiran **KU** -> ganti jadi **ITE** (Kaku -> Kaite) *(Pengecualian: Iku -> Itte)*\n- Berakhiran **GU** -> ganti jadi **IDE** (Oyogu -> Oyoide)\n- Berakhiran **SU** -> ganti jadi **SHITE** (Hanasu -> Hanashite)\n\n### Kata Kerja Golongan 2 (Ichidan):\n- Cukup buang akhiran **RU** dan ganti dengan **TE**:\n  - Taberu -> **Tabete** (Makan)\n  - Miru -> **Mite** (Melihat)\n  - Okiru -> **Okite** (Bangun)\n\n### Golongan 3 (Tidak Beraturan):\n- Suru -> **Shite**\n- Kuru -> **Kite**";
        }

        // Bentuk NAI (Negatif Kasual)
        if (preg_match('/(bentuk nai|nai form|nai-form|cara ubah.*nai|bentuk negatif)/u', $lower)) {
            return "Bentuk **~NAI (~ない)** adalah bentuk negatif kasual (informal) kata kerja. Digunakan juga untuk rumus larangan halus (*~naide kudasai*) dan keharusan (*~nakereba narimasen*)!\n\n### Rumus Pembentukan:\n1. **Golongan 1 (Godan):**\n   - Ubah vokal akhiran **U** menjadi baris **A** lalu tambahkan **NAI**:\n     - Kaku -> Kaka**nai** (Tidak menulis)\n     - Nomu -> Noma**nai** (Tidak minum)\n     - Hanasu -> Hanasa**nai** (Tidak berbicara)\n     - *Pengecualian akhiran U tunggal:* Kau -> Ka**wanai** (bukan kaanai).\n2. **Golongan 2 (Ichidan):**\n   - Cukup buang **RU** lalu tambahkan **NAI**:\n     - Taberu -> Tabe**nai** (Tidak makan)\n     - Miru -> Mi**nai** (Tidak melihat)\n3. **Golongan 3 (Tidak Beraturan):**\n   - Suru -> **Shinai** (Tidak melakukan)\n   - Kuru -> **Konai** (Tidak datang)\n\n*Pengecualian Unik:* Kata *Arimasu* bentuk negatif kasualnya adalah **NAI (ない)** saja!";
        }

        // Bentuk TA (Lampau Kasual)
        if (preg_match('/(bentuk ta|ta form|ta-form|cara ubah.*ta|lampau kasual)/u', $lower)) {
            return "Kabar gembira! Rumus mengubah kata kerja ke **Bentuk ~TA (~た)** persis 100% sama dengan rumus **Bentuk ~TE**, cukup ganti huruf **TE** menjadi **TA** dan **DE** menjadi **DA**:\n\n### Contoh Perubahan:\n- Tabete -> **Tabeta** (Sudah makan)\n- Nonde -> **Nonda** (Sudah minum)\n- Kaite -> **Kaita** (Sudah menulis)\n- Itte -> **Itta** (Sudah pergi)\n- Shite -> **Shita** (Sudah melakukan)\n\nBentuk ~TA ini digunakan saat berbicara santai dengan teman, atau untuk pola pengalaman: **~ta koto ga arimasu** (Pernah melakukan sesuatu)!";
        }

        // Bentuk Kamus / Jishokei
        if (preg_match('/(bentuk kamus|jishokei|plain form|kamus form)/u', $lower)) {
            return "Bentuk **Kamus (Jishokei - 辞書形)** adalah bentuk dasar kata kerja yang tercantum di dalam kamus sebelum dikonjugasikan ke bentuk sopan (~masu).\n\n### Ciri Khas Bentuk Kamus:\n- Seluruh kata kerja bentuk kamus selalu berakhiran bunyi vokal **U** (u, ku, su, tsu, nu, fu, mu, yu, ru).\n- Digunakan dalam situasi berbicara kasual dengan teman sebaya.\n- Digunakan sebelum rumus-rumus tata bahasa JLPT N5, contohnya:\n  1. **[Bentuk Kamus] + ことができます** *(koto ga dekimasu)* = Bisa / mampu melakukan.\n  2. **[Bentuk Kamus] + 前に** *(mae ni)* = Sebelum melakukan.";
        }

        // Bentuk Potensial (Bisa)
        if (preg_match('/(bentuk potensial|kanoukei|cara ubah.*bisa|konjugasi.*bisa)/u', $lower)) {
            return "Bentuk **Potensial (Kanoukei - 可能形)** digunakan untuk menyatakan kemampuan (*\"bisa / sanggup melakukan sesuatu\"*):\n\n### Rumus Pembentukan:\n1. **Golongan 1:** Ubah vokal akhiran **U** menjadi **E + RU**\n   - Iku -> **Ikeru / Ikemasu** (Bisa pergi)\n   - Nomu -> **Nomeru / Nomemasu** (Bisa minum)\n   - Kaku -> **Kakeru / Kakemasu** (Bisa menulis)\n2. **Golongan 2:** Buang **RU** ganti **RARERU / RAREMASU**\n   - Taberu -> **Taberareru / Taberaremasu** (Bisa makan)\n3. **Golongan 3:**\n   - Suru -> **Dekiru / Dekimasu**\n   - Kuru -> **Korareru / Koraremasu**\n\n*Penting:* Pada bentuk potensial, partikel penanda objek **を (o)** sering digantikan oleh **が (ga)** (Contoh: *Nihongo ga hanasemasu* = Bisa bicara bahasa Jepang)!";
        }

        return null;
    }

    /**
     * 4. Penanganan Pola Kalimat Esensial (Bunpou N5)
     */
    protected function handleGrammarPatterns(string $lower): ?string
    {
        // ~TAI (Ingin)
        if (preg_match('/(~tai|tai desu|pola.*ingin|cara.*ingin)/u', $lower)) {
            return "Untuk menyatakan **\"ingin melakukan sesuatu\"** bagi diri sendiri, kita menggunakan pola **~TAI DESU (~たいです)**:\n\n### Rumus Pembentukan:\n> **Kata Kerja Bentuk MASU -> Buang MASU -> Ganti TAI DESU**\n\n### Contoh:\n1. **Tabemasu** (Makan) -> **Tabetai desu** (Ingin makan)\n   > **ラーメンが食べたいです。**\n   > *(Raamen ga tabetai desu)* = Saya ingin makan ramen.\n2. **Ikimasu** (Pergi) -> **Ikitai desu** (Ingin pergi)\n   > **日本へ行きたいです。**\n   > *(Nihon e ikitai desu)* = Saya ingin pergi ke Jepang.\n\nObjek sebelum ~tai desu bisa memakai partikel **GA (が)** atau **O (を)**, namun partikel **GA** terdengar lebih alami dan ekspresif!";
        }

        // ~TE KUDASAI (Tolong)
        if (preg_match('/(~te kudasai|te kudasai|pola.*tolong|cara minta tolong)/u', $lower)) {
            return "Pola **~TE KUDASAI (~てください)** digunakan untuk meminta bantuan atau memberi instruksi sopan (*\"Tolong / Silakan lakukan...\"*):\n\n### Rumus:\n> **Kata Kerja Bentuk TE + ください (kudasai)**\n\n### Contoh Populer:\n1. **ちょっと待ってください。**\n   *(Chotto matte kudasai)* = Tolong tunggu sebentar.\n2. **日本語で話してください。**\n   *(Nihongo de hanashite kudasai)* = Tolong berbicara dalam bahasa Jepang.\n3. **ここに名前を書いてください。**\n   *(Koko ni namae o kaite kudasai)* = Tolong tulis nama di sini.";
        }

        // ~TE MO II DESU KA (Bolehkah) & ~TE WA IKEMASEN (Larangan)
        if (preg_match('/(te mo ii|te wa ikemasen|bolehkah|izin|larangan)/u', $lower)) {
            return "Berikut pola izin dan larangan dalam bahasa Jepang:\n\n### 1. Meminta Izin: ~TE MO II DESU KA (~てもいいですか)\n- Arti: *\"Bolehkah saya melakukan...?\"*\n- Contoh:\n  > **ここで写真を撮ってもいいですか？**\n  > *(Koko de shashin o totte mo ii desu ka?)*\n  > = Bolehkah mengambil foto di sini?\n  > Jawaban Boleh: *Hai, ii desu yo.* (Ya, silakan).\n\n### 2. Larangan Tegas: ~TE WA IKEMASEN (~てはいけません)\n- Arti: *\"Tidak boleh / Dilarang melakukan...!\"*\n- Contoh:\n  > **ここでタバコを吸ってはいけません。**\n  > *(Koko de tabako o sutte wa ikemasen)*\n  > = Dilarang merokok di sini!";
        }

        // ~TE IMASU (Sedang)
        if (preg_match('/(~te imasu|te imasu|teiru|sedang melakukan)/u', $lower)) {
            return "Pola **~TE IMASU (~ています)** memiliki dua fungsi utama:\n\n1. **Menyatakan Aksi yang Sedang Berlangsung (*Present Continuous*):**\n   > **今、ご飯を食べています。**\n   > *(Ima, gohan o tabete imasu)* = Sekarang sedang makan nasi.\n   > **雨が降っています。**\n   > *(Ame ga futte imasu)* = Hujan sedang turun.\n\n2. **Menyatakan Status Kondisi yang Masih Bertahan:**\n   > **結婚しています。** *(Kekkon shite imasu)* = Sudah menikah.\n   > **東京に住んでいます。** *(Toukyou ni sunde imasu)* = Tinggal di Tokyo.\n   > **知っています。** *(Shitte imasu)* = Tahu / kenal.";
        }

        // ~KOTO GA DEKIMASU (Bisa / Mampu)
        if (preg_match('/(koto ga dekimasu|bisa melakukan|mampu)/u', $lower)) {
            return "Pola **~KOTO GA DEKIMASU (~ことができます)** digunakan untuk menyatakan kemampuan secara formal:\n\n### Rumus:\n> **Kata Kerja Bentuk Kamus + ことができます**\n\n### Contoh:\n1. **泳ぐことができます。**\n   *(Oyogu koto ga dekimasu)* = Saya bisa berenang.\n2. **漢字を読むことができます。**\n   *(Kanji o yomu koto ga dekimasu)* = Bisa membaca kanji.\n3. **ここでカードを使うことができますか？**\n   *(Koko de kaado o tsukau koto ga dekimasu ka?)* = Apakah di sini bisa menggunakan kartu?";
        }

        // ~TA KOTO GA ARIMASU (Pernah)
        if (preg_match('/(ta koto ga arimasu|pernah|pengalaman)/u', $lower)) {
            return "Pola **~TA KOTO GA ARIMASU (~たことがあります)** digunakan untuk menceritakan pengalaman lampau (*\"Pernah melakukan sesuatu\"*):\n\n### Rumus:\n> **Kata Kerja Bentuk TA (Lampau) + ことがあります**\n\n### Contoh:\n1. **富士山に登ったことがあります。**\n   *(Fujisan ni nobotta koto ga arimasu)* = Saya pernah mendaki Gunung Fuji.\n2. **納豆を食べたことがありますか？**\n   *(Nattou o tabeta koto ga arimasu ka?)* = Apakah kamu pernah makan natto?\n   - Jika belum pernah: **いいえ、一度もありません。** *(Iie, ichido mo arimasen)* = Belum pernah sama sekali.";
        }

        // ~MAE NI vs ~ATO DE
        if (preg_match('/(mae ni|ato de|sebelum.*setelah|setelah.*sebelum)/u', $lower)) {
            return "Berikut pola urutan waktu **Sebelum** dan **Sesudah**:\n\n1. **Sebelum: [Kata Kerja Bentuk Kamus] + 前に (mae ni)**\n   > **寝る前に、歯を磨きます。**\n   > *(Neru mae ni, ha o migakimasu)*\n   > = Sebelum tidur, saya menggosok gigi.\n\n2. **Sesudah: [Kata Kerja Bentuk TA] + 後で (ato de)**\n   > **ご飯を食べた後で、薬を飲みます。**\n   > *(Gohan o tabeta ato de, kusuri o nomimasu)*\n   > = Setelah makan nasi, saya minum obat.";
        }

        // ~SUGIRU (Terlalu)
        if (preg_match('/(sugiru|sugimasu|terlalu banyak|terlalu)/u', $lower)) {
            return "Pola **~SUGIRU (~すぎる / ~すぎます)** digunakan untuk menyatakan sesuatu yang berlebihan (*\"Terlalu...\"*):\n\n1. **Kata Kerja:** Bentuk Masu (buang masu) + すぎます\n   - Tabemasu -> **Tabesugimashita** (Terlalu banyak makan / kekenyangan)\n   - Nomimasu -> **Nomisugimashita** (Terlalu banyak minum)\n2. **Kata Sifat -I:** Buang akhiran -I + すぎます\n   - Takai -> **Takasugimasu** (Terlalu mahal)\n   - Karai -> **Karasugimasu** (Terlalu pedas)";
        }

        // ~HOU GA II DESU (Sebaiknya)
        if (preg_match('/(hou ga ii|sebaiknya|saran)/u', $lower)) {
            return "Pola **~HOU GA II DESU (~ほうがいいです)** digunakan untuk memberi saran positif atau anjuran baik kepada seseorang:\n\n### Rumus:\n- Saran Positif: **Kata Kerja Bentuk TA + ほうがいいです**\n  > **早く寝たほうがいいですよ。** *(Hayaku neta hou ga ii desu yo)* = Sebaiknya tidur lebih awal ya.\n  > **病院へ行ったほうがいいです。** *(Byouin e itta hou ga ii desu)* = Sebaiknya pergi ke rumah sakit.\n- Saran Negatif: **Kata Kerja Bentuk NAI + ほうがいいです**\n  > **無理をしないほうがいいです。** *(Muri o shinai hou ga ii desu)* = Sebaiknya jangan memaksakan diri.";
        }

        // ~NI IKIMASU (Pergi untuk...)
        if (preg_match('/(ni ikimasu|tujuan pergi|pergi untuk)/u', $lower)) {
            return "Pola **~NI IKIMASU (~に行きます)** digunakan untuk menyatakan pergi dengan maksud/tujuan melakukan suatu aktivitas:\n\n### Rumus:\n> **[Kata Kerja Bentuk MASU (Buang MASU)] + に行きます**\n\n### Contoh:\n1. **日本へ日本語を勉強しに行きます。**\n   *(Nihon e nihongo o benkyou shi ni ikimasu)* = Pergi ke Jepang untuk belajar bahasa Jepang.\n2. **デパートへ買い物に行きます。**\n   *(Depaato e kaimono ni ikimasu)* = Pergi ke department store untuk berbelanja.\n3. **映画を見に行きます。**\n   *(Eiga o mi ni ikimasu)* = Pergi untuk menonton film.";
        }

        return null;
    }

    /**
     * 5. Penanganan Kata Sifat (I-keiyoushi & Na-keiyoushi)
     */
    protected function handleAdjectives(string $lower): ?string
    {
        if (preg_match('/(kata sifat|i-keiyoushi|na-keiyoushi|keiyoushi)/u', $lower) || (str_contains($lower, 'sifat') && (str_contains($lower, 'beda') || str_contains($lower, 'ubah')))) {
            return "Dalam bahasa Jepang, kata sifat dibagi menjadi dua golongan dengan aturan perubahan yang berbeda:\n\n### 1. Kata Sifat -I (I-keiyoushi - い形容詞)\n- Berakhiran huruf **い (i)** murni (contoh: *takai, samui, atsui, oishii*).\n- **Aturan Konjugasi:**\n  - Positif Sekarang: **Takai desu** (Mahal)\n  - Negatif Sekarang: **Takaku nai desu** (buang i ganti *kunai*)\n  - Lampau: **Takakatta desu** (buang i ganti *katta*)\n  - Lampau Negatif: **Takaku nakatta desu**\n- *Pengecualian Istimewa:* Kata **いい (Ii / Bagus)** berubah menjadi: **Yoku nai** (tidak bagus), **Yokatta** (tadi bagus), **Yoku nakatta**.\n\n### 2. Kata Sifat -NA (Na-keiyoushi - な形容詞)\n- Tidak berakhiran i (contoh: *shizuka, benri, kirei, yuumei*). *(Catatan: kirei dan yuumei berakhiran bunyi e-i tetapi termasuk kata sifat -NA!)*\n- **Aturan Konjugasi:**\n  - Positif Sekarang: **Shizuka desu** (Tenang)\n  - Sebelum Kata Benda: **Shizuka na heya** (Kamar yang tenang)\n  - Negatif Sekarang: **Shizuka dewa arimasen / ja arimasen**\n  - Lampau: **Shizuka deshita**\n\n### Menggabungkan 2 Kata Sifat:\n- Kata sifat -I: ganti i jadi **-kute** (*Oishikute yasui* = Enak dan murah)\n- Kata sifat -NA: tambah **-de** (*Kirei de shizuka* = Cantik dan tenang)";
        }

        return null;
    }

    /**
     * 6. Penanganan Waktu, Jam, Menit, Hari, Tanggal, & Kalender
     */
    protected function handleTimeAndCalendar(string $lower): ?string
    {
        // Jam & Menit
        if (preg_match('/(jam|waktu|menit|baca jam|cara baca jam)/u', $lower)) {
            return "Berikut panduan membaca waktu (Jam & Menit) dalam bahasa Jepang:\n\n### 1. Membaca Jam (Ji - 時)\nCukup sebutkan angka + **JI** (時):\n- 1:00 = **Ichi-ji** (1時)\n- 2:00 = **Ni-ji** (2時)\n- 3:00 = **San-ji** (3時)\n- 4:00 = **Yo-ji** (bukan yon-ji)\n- 7:00 = **Shichi-ji** (bukan nana-ji)\n- 9:00 = **Ku-ji** (bukan kyuu-ji)\n\n### 2. Membaca Menit (Fun / Pun - 分)\n- 1 menit = **Ippun** (1分)\n- 2 menit = **Ni-fun** (2分)\n- 3 menit = **San-pun** (3分)\n- 5 menit = **Go-fun** (5分)\n- 10 menit = **Juppun / Jippun** (10分)\n- 15 menit = **Juugo-fun** (15分)\n- 30 menit (Setengah) = **Sanjuppun** atau **Han (半)**\n\nContoh: *Jam 7:30* = **Shichi-ji han** (7時半)!";
        }

        // Hari dalam seminggu
        if (preg_match('/(nama hari|hari senin|hari selasa|nama-nama hari|youbi)/u', $lower)) {
            return "Nama 7 hari dalam sepekan dalam bahasa Jepang dihubungkan dengan elemen alam dan tata surya:\n\n1. **Senin:** 月曜日 *(Getsuyoubi)* - Elemen Bulan\n2. **Selasa:** 火曜日 *(Kayoubi)* - Elemen Api\n3. **Rabu:** 水曜日 *(Suiyoubi)* - Elemen Air\n4. **Kamis:** 木曜日 *(Mokuyoubi)* - Elemen Kayu/Pohon\n5. **Jumat:** 金曜日 *(Kinyoubi)* - Elemen Logam/Emas\n6. **Sabtu:** 土曜日 *(Doyoubi)* - Elemen Tanah/Bumi\n7. **Minggu:** 日曜日 *(Nichiyoubi)* - Elemen Matahari\n\n*Kata Tanya:* **何曜日ですか？** *(Nan-youbi desu ka?)* = Hari apa?";
        }

        // Tanggal 1-31
        if (preg_match('/(tanggal|tanggal 1|baca tanggal|kalender jepang)/u', $lower)) {
            return "Membaca tanggal 1 sampai 10 di Jepang memiliki sebutan khusus (*wago*) yang unik dan wajib dihafal:\n\n- Tanggal 1: **Tsuitachi** (1日)\n- Tanggal 2: **Futsuka** (2日)\n- Tanggal 3: **Mikka** (3日)\n- Tanggal 4: **Yokka** (4日)\n- Tanggal 5: **Itsuka** (5日)\n- Tanggal 6: **Muika** (6日)\n- Tanggal 7: **Nanoka** (7日)\n- Tanggal 8: **Youka** (8日)\n- Tanggal 9: **Kokonoka** (9日)\n- Tanggal 10: **Tooka** (10日)\n\n**Tanggal Unik Lainnya:**\n- Tanggal 14: **Juuyokka**\n- Tanggal 20: **Hatsuka** (bukan ni-juu-nichi)\n- Tanggal 24: **Nijuuyokka**\n\nTanggal lainnya cukup sebutkan angka biasa + **NICHI** (misal: Tanggal 15 = *Juugo-nichi*).";
        }

        // Keterangan waktu (Kemarin, Hari Ini, Besok)
        if (preg_match('/(kinou|kyou|ashita|kemarin|hari ini|besok|lusa)/u', $lower)) {
            return "Berikut kosakata penunjuk waktu penting sehari-hari:\n\n- **Kemarin lusa:** 一昨日 *(Ototoi)*\n- **Kemarin:** 昨日 *(Kinou)*\n- **Hari ini:** 今日 *(Kyou)*\n- **Besok:** 明日 *(Ashita)*\n- **Lusa:** 明後日 *(Asatte)*\n- **Minggu lalu / ini / depan:** 先週 *(Senshuu)* / 今週 *(Konshuu)* / 来週 *(Raishuu)*\n- **Bulan lalu / ini / depan:** 先月 *(Sengetsu)* / 今月 *(Kongetsu)* / 来月 *(Raigetsu)*\n- **Tahun lalu / ini / depan:** 去年 *(Kyonen)* / 今年 *(Kotoshi)* / 来年 *(Rainen)*";
        }

        return null;
    }

    /**
     * 7. Penanganan Kosoado & Kata Tanya (5W1H)
     */
    protected function handleKosoadoAndQuestions(string $lower): ?string
    {
        // Kosoado Kotoba
        if (preg_match('/(kosoado|kore.*sore|kono.*sono|koko.*soko)/u', $lower)) {
            return "Konsep **KO-SO-A-DO** adalah sistem penunjuk jarak dalam bahasa Jepang:\n\n| Kategori | KO (Dekat Pembicara) | SO (Dekat Lawan Bicara) | A (Jauh dari Keduanya) | DO (Kata Tanya) |\n| :--- | :--- | :--- | :--- | :--- |\n| **Benda Mandiri** | **Kore** (Ini) | **Sore** (Itu) | **Are** (Itu jauh) | **Dore** (Yang mana?) |\n| **+ Kata Benda** | **Kono [Benda]** | **Sono [Benda]** | **Ano [Benda]** | **Dono [Benda]** |\n| **Tempat** | **Koko** (Di sini) | **Soko** (Di situ) | **Asoko** (Di sana) | **Doko** (Di mana?) |\n| **Arah / Sopan** | **Kochira** | **Sochira** | **Achira** | **Dochira** |\n\n*Contoh:* **これは何ですか？** *(Kore wa nan desu ka?)* = Ini apa?";
        }

        // 5W1H Kata Tanya
        if (preg_match('/(kata tanya|5w1h|nani|dare|doko|itsu|doushite|ikura)/u', $lower)) {
            return "Berikut daftar lengkap kata tanya (*Gimonshi - 疑問詞*) dalam bahasa Jepang:\n\n1. **Apa:** 何 *(Nani / Nan)*\n2. **Siapa:** 誰 *(Dare)* / Bentuk sopan: どなた *(Donata)*\n3. **Di mana / Ke mana:** どこ *(Doko)* / Bentuk sopan: どちら *(Dochira)*\n4. **Kapan:** いつ *(Itsu)*\n5. **Kenapa / Mengapa:** どうして *(Doushite)* atau なぜ *(Naze)*\n6. **Bagaimana:** どう *(Dou)* / Bentuk sopan: いかが *(Ikaga)*\n7. **Berapa Harganya:** いくら *(Ikura)*\n8. **Berapa Buah / Umur:** いくつ *(Ikutsu)* atau 何歳 *(Nan-sai)*\n\n*Contoh:* **これはいくらですか？** *(Kore wa ikura desu ka?)* = Berapa harga barang ini?";
        }

        return null;
    }

    /**
     * 8. Penanganan Sebutan Anggota Keluarga (Kazoku)
     */
    protected function handleFamilyTerms(string $lower): ?string
    {
        if (preg_match('/(keluarga|sebutan keluarga|kazoku|ayah|ibu|kakak|adik)/u', $lower)) {
            return "Dalam bahasa Jepang, terdapat perbedaan penting saat menyebut **keluarga sendiri** dan **keluarga orang lain** (sebagai bentuk kesopanan/etika):\n\n| Anggota Keluarga | Keluarga Sendiri (Rendah Hati) | Keluarga Orang Lain (Hormat) |\n| :--- | :--- | :--- |\n| **Ayah** | 父 *(Chichi)* | お父さん *(Otousan)* |\n| **Ibu** | 母 *(Haha)* | お母さん *(Okaasan)* |\n| **Kakak Laki-laki** | 兄 *(Ani)* | お兄さん *(Oniisan)* |\n| **Kakak Perempuan** | 姉 *(Ane)* | お姉さん *(Oneesan)* |\n| **Adik Laki-laki** | 弟 *(Otouto)* | 弟さん *(Otoutosan)* |\n| **Adik Perempuan** | 妹 *(Imouto)* | 妹さん *(Imoutosan)* |\n| **Kakek** | 祖父 *(Sofu)* | おじいさん *(Ojiisan)* |\n| **Nenek** | 祖母 *(Sobo)* | おばあさん *(Obaasan)* |\n| **Suami** | 夫 *(Otto)* / 主人 *(Shujin)* | ご主人 *(Goshujin)* |\n| **Istri** | 妻 *(Tsuma)* / 家内 *(Kanai)* | 奥さん *(Okusan)* |\n\n*Aturan Emas:* Jangan pernah menyebut ayah sendiri dengan kata *Otousan* kepada atasan atau orang luar; gunakan *Chichi*!";
        }

        return null;
    }

    /**
     * 9. Tips Belajar & Panduan Ujian JLPT N5/N4
     */
    protected function handleJLPTAndStudyTips(string $lower): ?string
    {
        if (preg_match('/(jlpt|n5|lulus n5|tips n5|persiapan jlpt|urutan belajar|mulai dari mana)/u', $lower)) {
            return "Berikut strategi jitu dari Kenji Sensei agar kamu bisa menguasai bahasa Jepang dari nol dan lulus ujian **JLPT N5** dengan skor tinggi:\n\n### Tahap 1: Fondasi Huruf (Minggu 1-2)\n- Kuasai **Hiragana** (46 huruf) dan **Katakana** (46 huruf) sampai refleks tanpa mengeja.\n- Latih refleks membaca cepat di menu **Speed Match** JP Learn!\n\n### Tahap 2: Kosakata & Kanji Dasar (Minggu 3-6)\n- Kuasai **~100 Kanji Dasar N5** (angka, hari, orang, arah, kata kerja dasar).\n- Hafalkan **~800 Kosakata N5** menggunakan menu **Flashcard Interaktif** setiap hari 15-20 menit.\n\n### Tahap 3: Pola Kalimat & Partikel (Minggu 7-10)\n- Pahami perbedaan partikel esensial: **wa, ga, o, ni, de, he, to, kara, made**.\n- Kuasai konjugasi kata kerja bentuk **~TE, ~NAI, ~TA**, dan pola kalimat dasar *~koto ga dekimasu, ~tai desu*.\n\n### Tahap 4: Latihan Soal & Simulasi Ujian (Minggu 11-12)\n- Ikuti **Simulasi Ujian JLPT N5** di JP Learn untuk mengasah kecepatan membaca bacaan (*dokkai*) dan konsentrasi mendengar percakapan (*choukai*).\n\n*Kunci Utama:* Konsistensi 20 menit per hari jauh lebih efektif daripada belajar 5 jam sekali seminggu. *Issho ni ganbarimashou!*";
        }

        return null;
    }

    /**
     * 10. Mesin Koreksi Kalimat Cerdas (Smart Sentence Corrector)
     */
    protected function handleSentenceCorrection(string $input): string
    {
        $lower = mb_strtolower($input);

        // Kasus 1: Kesalahan Toshokan ni hon o yomimasu (ni -> de)
        if (str_contains($lower, 'toshokan') && str_contains($lower, 'hon') && (str_contains($lower, 'ni') || str_contains($lower, 'de'))) {
            return "Mari kita koreksi kalimatmu bersama Kenji Sensei!\n\n" .
                "### Kalimat Asli:\n" .
                "> 私は図書館**に**本を読みます。(Watashi wa toshokan **ni** hon o yomimasu)\n\n" .
                "### Versi yang Benar:\n" .
                "> **私は図書館で本を読みます。**\n" .
                "> *(Watashi wa toshokan **de** hon o yomimasu)*\n" .
                "> Artinya: Saya membaca buku di perpustakaan.\n\n" .
                "### Penjelasan Kenji Sensei:\n" .
                "- Membaca buku (*hon o yomimasu*) adalah **kegiatan aksi dinamis**.\n" .
                "- Tempat terjadinya aktivitas aksi **wajib menggunakan partikel で (de)**, bukan に (ni).\n" .
                "- Partikel に (ni) hanya digunakan jika sekadar menyatakan keberadaan statis (*Toshokan ni hon ga arimasu*) atau tujuan pergi (*Toshokan ni ikimasu*).";
        }

        // Kasus 2: Kesalahan tenses waktu lampau (Kinou ... mimasu/tabemasu)
        if (str_contains($lower, 'kinou') && (str_contains($lower, 'mimasu') || str_contains($lower, 'tabemasu') || str_contains($lower, 'ikimasu'))) {
            return "Bagus sekali sudah mencoba membuat kalimat! Namun ada koreksi pada bentuk waktu (*tenses*):\n\n" .
                "### Analisis Kesalahan:\n" .
                "- Kamu menggunakan kata keterangan waktu **昨日 (Kinou)** yang berarti **Kemarin (Masa Lampau)**.\n" .
                "- Oleh karena itu, kata kerjanya tidak boleh menggunakan bentuk sekarang (~masu), melainkan harus diubah ke **bentuk lampau (~mashita)**.\n\n" .
                "### Versi Kalimat yang Tepat:\n" .
                "- Jika menonton film: **昨日映画を見ました。** *(Kinou eiga o mimashita)* = Kemarin saya menonton film.\n" .
                "- Jika makan: **昨日ラーメンを食べました。** *(Kinou raamen o tabemashita)* = Kemarin saya makan ramen.\n" .
                "- Jika pergi: **昨日学校へ行きました。** *(Kinou gakkou e ikimashita)* = Kemarin saya pergi ke sekolah.";
        }

        // Kasus 3: Kesalahan partikel pergi (Nihon de ikimasu -> Nihon ni/e ikimasu)
        if (str_contains($lower, 'de ikimasu') || str_contains($lower, 'de ikimashita')) {
            return "Perhatian pada partikel tujuan pergerakan:\n\n" .
                "### Analisis Kalimat:\n" .
                "- Untuk kata kerja pergerakan seperti **行きます (ikimasu = pergi)**, tempat tujuan kedatangan **tidak boleh memakai partikel で (de)**.\n" .
                "- Gunakan partikel **に (ni)** atau **へ (e)**.\n\n" .
                "### Contoh yang Benar:\n" .
                "> **日本に行きます / 日本へ行きます。**\n" .
                "> *(Nihon ni ikimasu / Nihon e ikimasu)* = Pergi ke Jepang.\n\n" .
                "*(Catatan: Partikel で hanya boleh digunakan jika menyatakan kendaraan, contohnya: バスで行きます = Pergi naik bus).*";
        }

        // Kasus 4: Kesalahan objek makan (Gohan ga tabemasu -> Gohan o tabemasu)
        if (str_contains($lower, 'ga tabemasu') || str_contains($lower, 'ga nomimasu')) {
            return "Koreksi partikel objek makanan/minuman:\n\n" .
                "### Analisis:\n" .
                "- Makanan (*gohan*) atau minuman (*mizu*) adalah **objek penderita** langsung dari kata kerja *tabemasu* / *nomimasu*.\n" .
                "- Objek langsung wajib dihubungkan dengan partikel **を (o)**, bukan が (ga).\n\n" .
                "### Versi Benar:\n" .
                "> **ご飯を食べます。** *(Gohan o tabemasu)* = Makan nasi.\n" .
                "> **水を飲みます。** *(Mizu o nomimasu)* = Minum air.";
        }

        // Kasus 5: Bertemu teman (Tomodachi o aimasu -> Tomodachi ni aimasu)
        if (str_contains($lower, 'aimasu') && str_contains($lower, ' o ') || str_contains($lower, 'wo aimasu')) {
            return "Koreksi penting pada kata kerja **会います (Aimasu = Bertemu)**:\n\n" .
                "### Analisis:\n" .
                "- Dalam bahasa Jepang, kata kerja *aimasu* adalah perkecualian umum: orang yang ditemui **tidak menggunakan partikel を (o)**, melainkan **に (ni)**.\n\n" .
                "### Versi Benar:\n" .
                "> **友達に会います。** *(Tomodachi ni aimasu)* = Bertemu dengan teman.\n" .
                "> **先生に会いました。** *(Sensei ni aimashita)* = Bertemu dengan guru.";
        }

        // Panduan Umum Koreksi Kalimat
        return "Tentu, mari kita periksa kalimatmu bersama Kenji Sensei!\n\n" .
            "### Rumus Utama Struktur Kalimat Bahasa Jepang:\n" .
            "> **[Subjek/Topik] は/が + [Keterangan Waktu/Tempat] に/で + [Objek] を + [Kata Kerja Predikat]**\n\n" .
            "### Checklist 4 Hal Penting:\n" .
            "1. **Objek Aksi:** Sudahkah objek makanan/benda menggunakan partikel **を (o)**? *(Contoh: Hon o yomimasu)*\n" .
            "2. **Tempat Aksi vs Tempat Tujuan:** Tempat beraktivitas gunakan **で (de)**, sedangkan tujuan pergi gunakan **に (ni) / へ (e)**.\n" .
            "3. **Tenses Waktu:** Jika ada kata *kinou* (kemarin), pastikan predikat berakhiran lampau **~mashita**.\n" .
            "4. **Urutan Kata (SOV):** Kata kerja selalu berada di **paling akhir kalimat**.\n\n" .
            "Coba ketikkan kalimat lengkapmu (misal: *\"Koreksi kalimat: Watashi wa kyou toshokan de benkyou shimasu\"*), dan Kenji Sensei siap membedahnya untukmu!";
    }

    /**
     * 11. Pencarian Kamus / Kosakata Bilingual Cerdas (Database + Built-in Catalog)
     */
    protected function searchDictionary(string $lower): ?string
    {
        // 1. Bersihkan kata tanya umum
        $cleanSearch = preg_replace('/(apa arti|artinya apa|arti kata|arti dari|arti|apa itu|bahasa jepangnya|bahasa jepang dari|apa bahasa jepangnya|terjemahkan|terjemahan|kanji dari|cara baca)\s+/u', '', $lower);
        $cleanSearch = trim($cleanSearch, " ?.!:,");

        if (mb_strlen($cleanSearch) < 2) {
            return null;
        }

        // 2. Cek Katalog Kosakata Cepat Bawaan (Offline Catalog)
        $quickCatalog = [
            'tabemasu' => ['ja' => '食べます', 'ro' => 'tabemasu', 'id' => 'Makan', 'cat' => 'Kata Kerja (Golongan 2)', 'ex_ja' => '毎朝パンを食べます。', 'ex_id' => 'Setiap pagi makan roti.'],
            'taberu' => ['ja' => '食べる', 'ro' => 'taberu', 'id' => 'Makan (Bentuk Kamus)', 'cat' => 'Kata Kerja (Golongan 2)', 'ex_ja' => 'りんごを食べる。', 'ex_id' => 'Makan apel.'],
            'nomimasu' => ['ja' => '飲みます', 'ro' => 'nomimasu', 'id' => 'Minum', 'cat' => 'Kata Kerja (Golongan 1)', 'ex_ja' => '水を飲みます。', 'ex_id' => 'Minum air.'],
            'nomu' => ['ja' => '飲む', 'ro' => 'nomu', 'id' => 'Minum (Bentuk Kamus)', 'cat' => 'Kata Kerja (Golongan 1)', 'ex_ja' => 'お茶を飲む。', 'ex_id' => 'Minum teh.'],
            'ikimasu' => ['ja' => '行きます', 'ro' => 'ikimasu', 'id' => 'Pergi', 'cat' => 'Kata Kerja (Golongan 1)', 'ex_ja' => '学校へ行きます。', 'ex_id' => 'Pergi ke sekolah.'],
            'iku' => ['ja' => '行く', 'ro' => 'iku', 'id' => 'Pergi (Bentuk Kamus)', 'cat' => 'Kata Kerja (Golongan 1)', 'ex_ja' => '日本へ行く。', 'ex_id' => 'Pergi ke Jepang.'],
            'kimasu' => ['ja' => '来ます', 'ro' => 'kimasu', 'id' => 'Datang', 'cat' => 'Kata Kerja (Golongan 3)', 'ex_ja' => '友達が来ます。', 'ex_id' => 'Teman datang.'],
            'kuru' => ['ja' => '来る', 'ro' => 'kuru', 'id' => 'Datang (Bentuk Kamus)', 'cat' => 'Kata Kerja (Golongan 3)', 'ex_ja' => 'バスが来る。', 'ex_id' => 'Bus datang.'],
            'mimasu' => ['ja' => '見ます', 'ro' => 'mimasu', 'id' => 'Melihat / Menonton', 'cat' => 'Kata Kerja (Golongan 2)', 'ex_ja' => 'テレビを見ます。', 'ex_id' => 'Menonton televisi.'],
            'miru' => ['ja' => '見る', 'ro' => 'miru', 'id' => 'Melihat / Menonton', 'cat' => 'Kata Kerja (Golongan 2)', 'ex_ja' => '映画を見る。', 'ex_id' => 'Menonton film.'],
            'kakimasu' => ['ja' => '書きます', 'ro' => 'kakimasu', 'id' => 'Menulis', 'cat' => 'Kata Kerja (Golongan 1)', 'ex_ja' => '手紙を書きます。', 'ex_id' => 'Menulis surat.'],
            'kaku' => ['ja' => '書く', 'ro' => 'kaku', 'id' => 'Menulis (Bentuk Kamus)', 'cat' => 'Kata Kerja (Golongan 1)', 'ex_ja' => '日記を書く。', 'ex_id' => 'Menulis buku harian.'],
            'kikimasu' => ['ja' => '聞きます', 'ro' => 'kikimasu', 'id' => 'Mendengar / Bertanya', 'cat' => 'Kata Kerja (Golongan 1)', 'ex_ja' => '音楽を聞きます。', 'ex_id' => 'Mendengarkan musik.'],
            'hanashimasu' => ['ja' => '話します', 'ro' => 'hanashimasu', 'id' => 'Berbicara', 'cat' => 'Kata Kerja (Golongan 1)', 'ex_ja' => '日本語で話します。', 'ex_id' => 'Berbicara dalam bahasa Jepang.'],
            'kaimasu' => ['ja' => '買います', 'ro' => 'kaimasu', 'id' => 'Membeli', 'cat' => 'Kata Kerja (Golongan 1)', 'ex_ja' => 'パンを買います。', 'ex_id' => 'Membeli roti.'],
            'machimasu' => ['ja' => '待ちます', 'ro' => 'machimasu', 'id' => 'Menunggu', 'cat' => 'Kata Kerja (Golongan 1)', 'ex_ja' => '友達を待ちます。', 'ex_id' => 'Menunggu teman.'],
            'sensei' => ['ja' => '先生', 'ro' => 'sensei', 'id' => 'Guru / Dosen / Dokter', 'cat' => 'Kata Benda', 'ex_ja' => 'ケンジ先生は優しいです。', 'ex_id' => 'Kenji Sensei orangnya ramah.'],
            'gakusei' => ['ja' => '学生', 'ro' => 'gakusei', 'id' => 'Murid / Pelajar / Mahasiswa', 'cat' => 'Kata Benda', 'ex_ja' => '私は日本語の学生です。', 'ex_id' => 'Saya murid bahasa Jepang.'],
            'tomodachi' => ['ja' => '友達', 'ro' => 'tomodachi', 'id' => 'Teman / Sahabat', 'cat' => 'Kata Benda', 'ex_ja' => '友達と遊びます。', 'ex_id' => 'Bermain bersama teman.'],
            'hon' => ['ja' => '本', 'ro' => 'hon', 'id' => 'Buku', 'cat' => 'Kata Benda', 'ex_ja' => '日本語の本を読みます。', 'ex_id' => 'Membaca buku bahasa Jepang.'],
            'kuruma' => ['ja' => '車', 'ro' => 'kuruma', 'id' => 'Mobil', 'cat' => 'Kata Benda', 'ex_ja' => '新しい車を買いました。', 'ex_id' => 'Membeli mobil baru.'],
            'densha' => ['ja' => '電車', 'ro' => 'densha', 'id' => 'Kereta Listrik', 'cat' => 'Kata Benda', 'ex_ja' => '電車で会社へ行きます。', 'ex_id' => 'Pergi ke kantor naik kereta.'],
            'mizu' => ['ja' => '水', 'ro' => 'mizu', 'id' => 'Air (Air Dingin/Biasa)', 'cat' => 'Kata Benda', 'ex_ja' => '冷たい水を飲みます。', 'ex_id' => 'Minum air dingin.'],
            'ocha' => ['ja' => 'お茶', 'ro' => 'ocha', 'id' => 'Teh Jepang (Green Tea)', 'cat' => 'Kata Benda', 'ex_ja' => 'お茶をどうぞ。', 'ex_id' => 'Silakan minum tehnya.'],
            'gohan' => ['ja' => 'ご飯', 'ro' => 'gohan', 'id' => 'Nasi / Makanan', 'cat' => 'Kata Benda', 'ex_ja' => '昼ご飯を食べましたか？', 'ex_id' => 'Apakah sudah makan siang?'],
            'sakana' => ['ja' => '魚', 'ro' => 'sakana', 'id' => 'Ikan', 'cat' => 'Kata Benda', 'ex_ja' => '魚が好きです。', 'ex_id' => 'Saya suka ikan.'],
            'niku' => ['ja' => '肉', 'ro' => 'niku', 'id' => 'Daging', 'cat' => 'Kata Benda', 'ex_ja' => '牛肉を食べます。', 'ex_id' => 'Makan daging sapi.'],
            'neko' => ['ja' => '猫', 'ro' => 'neko', 'id' => 'Kucing', 'cat' => 'Kata Benda', 'ex_ja' => '可愛い猫がいます。', 'ex_id' => 'Ada kucing yang lucu.'],
            'inu' => ['ja' => '犬', 'ro' => 'inu', 'id' => 'Anjing', 'cat' => 'Kata Benda', 'ex_ja' => '犬と散歩します。', 'ex_id' => 'Jalan-jalan bersama anjing.'],
            'oishii' => ['ja' => '美味しい', 'ro' => 'oishii', 'id' => 'Enak / Lezat', 'cat' => 'Kata Sifat -I', 'ex_ja' => 'このラーメンは美味しいです。', 'ex_id' => 'Ramen ini enak.'],
            'takai' => ['ja' => '高い', 'ro' => 'takai', 'id' => 'Tinggi / Mahal', 'cat' => 'Kata Sifat -I', 'ex_ja' => 'この時計は高いです。', 'ex_id' => 'Jam tangan ini mahal.'],
            'yasui' => ['ja' => '安い', 'ro' => 'yasui', 'id' => 'Murah', 'cat' => 'Kata Sifat -I', 'ex_ja' => 'この店は安いです。', 'ex_id' => 'Toko ini murah.'],
            'samui' => ['ja' => '寒い', 'ro' => 'samui', 'id' => 'Dingin (Suhu Udara)', 'cat' => 'Kata Sifat -I', 'ex_ja' => '今日はとても寒いです。', 'ex_id' => 'Hari ini sangat dingin.'],
            'atsui' => ['ja' => '暑い', 'ro' => 'atsui', 'id' => 'Panas (Suhu Cuaca)', 'cat' => 'Kata Sifat -I', 'ex_ja' => '夏は暑いです。', 'ex_id' => 'Musim panas itu panas.'],
            'kirei' => ['ja' => '綺麗', 'ro' => 'kirei', 'id' => 'Cantik / Indah / Bersih', 'cat' => 'Kata Sifat -NA', 'ex_ja' => '富士山は綺麗です。', 'ex_id' => 'Gunung Fuji itu indah.'],
            'shizuka' => ['ja' => '静か', 'ro' => 'shizuka', 'id' => 'Tenang / Sunyi', 'cat' => 'Kata Sifat -NA', 'ex_ja' => 'この図書館は静かです。', 'ex_id' => 'Perpustakaan ini tenang.'],
            'benri' => ['ja' => '便利', 'ro' => 'benri', 'id' => 'Praktis / Nyaman', 'cat' => 'Kata Sifat -NA', 'ex_ja' => '地下鉄はとても便利です。', 'ex_id' => 'Kereta bawah tanah sangat praktis.'],
            'suki' => ['ja' => '好き', 'ro' => 'suki', 'id' => 'Suka / Gemar', 'cat' => 'Kata Sifat -NA', 'ex_ja' => '日本のアニメが好きです。', 'ex_id' => 'Saya suka anime Jepang.'],
            'kirai' => ['ja' => '嫌い', 'ro' => 'kirai', 'id' => 'Tidak Suka / Benci', 'cat' => 'Kata Sifat -NA', 'ex_ja' => '辛い食べ物が嫌いです。', 'ex_id' => 'Saya tidak suka makanan pedas.'],
            'makan' => ['ja' => '食べます / 食べる', 'ro' => 'tabemasu / taberu', 'id' => 'Makan', 'cat' => 'Kata Kerja (Golongan 2)', 'ex_ja' => 'ご飯を食べます。', 'ex_id' => 'Makan nasi.'],
            'minum' => ['ja' => '飲みます / 飲む', 'ro' => 'nomimasu / nomu', 'id' => 'Minum', 'cat' => 'Kata Kerja (Golongan 1)', 'ex_ja' => '水を飲みます。', 'ex_id' => 'Minum air.'],
            'pergi' => ['ja' => '行きます / 行く', 'ro' => 'ikimasu / iku', 'id' => 'Pergi', 'cat' => 'Kata Kerja (Golongan 1)', 'ex_ja' => '日本へ行きます。', 'ex_id' => 'Pergi ke Jepang.'],
            'datang' => ['ja' => '来ます / 来る', 'ro' => 'kimasu / kuru', 'id' => 'Datang', 'cat' => 'Kata Kerja (Golongan 3)', 'ex_ja' => '友達が来ます。', 'ex_id' => 'Teman datang.'],
            'buku' => ['ja' => '本', 'ro' => 'hon', 'id' => 'Buku', 'cat' => 'Kata Benda', 'ex_ja' => '本を読みます。', 'ex_id' => 'Membaca buku.'],
            'kucing' => ['ja' => '猫', 'ro' => 'neko', 'id' => 'Kucing', 'cat' => 'Kata Benda', 'ex_ja' => '猫が好きです。', 'ex_id' => 'Saya suka kucing.'],
            'anjing' => ['ja' => '犬', 'ro' => 'inu', 'id' => 'Anjing', 'cat' => 'Kata Benda', 'ex_ja' => '犬が走っています。', 'ex_id' => 'Anjing sedang berlari.'],
            'guru' => ['ja' => '先生', 'ro' => 'sensei', 'id' => 'Guru', 'cat' => 'Kata Benda', 'ex_ja' => 'ケンジ先生です。', 'ex_id' => 'Ini Kenji Sensei.'],
            'terima kasih' => ['ja' => 'ありがとう / ありがとうございます', 'ro' => 'arigatou / arigatou gozaimasu', 'id' => 'Terima kasih', 'cat' => 'Ungkapan', 'ex_ja' => 'どうもありがとうございます。', 'ex_id' => 'Terima kasih banyak.'],
        ];

        if (isset($quickCatalog[$cleanSearch])) {
            $item = $quickCatalog[$cleanSearch];
            $res = "Berikut penjelasan kosakata yang kamu tanyakan!\n\n";
            $res .= "### **" . $item['ja'] . "** (" . $item['ro'] . ")\n";
            $res .= "- **Arti:** " . $item['id'] . "\n";
            $res .= "- **Kategori:** " . $item['cat'] . " (Level JLPT N5)\n";
            $res .= "\n**Contoh Kalimat:**\n";
            $res .= "> " . $item['ex_ja'] . "\n";
            $res .= "> *" . $item['ex_id'] . "*\n";
            $res .= "\n*Tips:* Kamu bisa melatih hafalan kosakata ini di menu **Flashcard** atau **Kamus & Kosakata** JP Learn!";
            return $res;
        }

        // 3. Cari di Database DictionaryService
        try {
            $allEntries = $this->dictionaryService->getAllEntries();
            foreach ($allEntries as $entry) {
                $ja = mb_strtolower($entry['japanese']);
                $ro = mb_strtolower($entry['romaji']);
                $id = mb_strtolower($entry['meaning']);

                if ($cleanSearch === $ja || $cleanSearch === $ro || $cleanSearch === $id || str_contains($id, $cleanSearch) || str_contains($ja, $cleanSearch)) {
                    $reply = "Berikut penjelasan kosakata dari kamus JP Learn!\n\n";
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
        } catch (\Throwable $e) {
            // Abaikan jika database dictionary sedang tidak dapat diakses
        }

        return null;
    }

    /**
     * 12. Fallback Penjelasan Konseptual Cerdas
     */
    protected function handleGeneralEducationalFallback(string $input): string
    {
        return "Pertanyaan yang sangat menarik seputar: *\"" . htmlspecialchars($input) . "\"*!\n\n" .
            "Sebagai **Kenji Sensei**, aku siap membantumu menguasai konsep dasar bahasa Jepang dengan mudah:\n\n" .
            "### 3 Pilar Utama Bahasa Jepang:\n" .
            "1. **Struktur Kalimat SOV (Subjek - Objek - Kata Kerja):**\n" .
            "   - Dalam bahasa Jepang, kata kerja selalu ditaruh di **paling akhir kalimat**.\n" .
            "   - Contoh: *Watashi wa ringo o tabemasu* (Saya - apel - makan).\n" .
            "2. **Partikel adalah Jantung Kalimat:**\n" .
            "   - Gunakan **は (wa)** untuk topik umum, **が (ga)** untuk subjek penentu, **を (o)** untuk objek penderita, dan **で (de)** untuk tempat aksi.\n" .
            "3. **Sistem Tiga Huruf:**\n" .
            "   - **Hiragana** untuk kata asli dan tata bahasa, **Katakana** untuk kata serapan asing, dan **Kanji** untuk menyampaikan makna ideografis.\n\n" .
            "### Contoh Pertanyaan Populer yang Bisa Kamu Tanyakan Langsung:\n" .
            "- *\"Apa bedanya partikel wa dan ga?\"*\n" .
            "- *\"Bagaimana rumus mengubah kata kerja ke bentuk ~TE?\"*\n" .
            "- *\"Tolong koreksi kalimat: 私は図書館に本を読みます\"*\n" .
            "- *\"Bagaimana cara membaca jam dan menit?\"*\n" .
            "- *\"Apa arti dari gochisousama deshita?\"*\n\n" .
            "Silakan ketik pertanyaan spesifik yang ingin kamu ketahui ya! *Ganbarimashou!*";
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
