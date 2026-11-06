<?php

namespace App\Services;

use App\Models\Material;

class DictionaryService
{
    /**
     * Ambil seluruh kata dalam kamus (kombinasi materi database & katalog JLPT N5 lengkap).
     *
     * @return array<int, array{word_key: string, japanese: string, reading: string, romaji: string, meaning: string, category: string, level: string, example_ja: ?string, example_id: ?string}>
     */
    public function getAllEntries(): array
    {
        $entries = [];
        $seenKeys = [];

        // 1. Ambil kata dari material vocabulary & kanji yang ada di database
        $materials = Material::query()
            ->whereIn('category', ['vocabulary', 'kanji'])
            ->where('is_active', true)
            ->get();

        foreach ($materials as $material) {
            $content = $material->content ?? [];

            // Vocabulary words
            if (isset($content['words']) && is_array($content['words'])) {
                foreach ($content['words'] as $w) {
                    $key = 'dict_' . md5($w['japanese'] . ($w['romaji'] ?? ''));
                    if (!isset($seenKeys[$key])) {
                        $seenKeys[$key] = true;
                        $entries[] = [
                            'word_key'   => $key,
                            'japanese'   => $w['japanese'],
                            'reading'    => $w['japanese'],
                            'romaji'     => $w['romaji'] ?? '',
                            'meaning'    => $w['meaning'] ?? '',
                            'category'   => $w['category'] ?? 'Kosakata Sehari-hari',
                            'level'      => 'N5',
                            'example_ja' => $this->getAutoExampleJa($w['japanese']),
                            'example_id' => $this->getAutoExampleId($w['japanese'], $w['meaning'] ?? ''),
                        ];
                    }
                }
            }

            // Kanji words
            if (isset($content['kanji']) && is_array($content['kanji'])) {
                foreach ($content['kanji'] as $k) {
                    $key = 'dict_kanji_' . md5($k['kanji']);
                    if (!isset($seenKeys[$key])) {
                        $seenKeys[$key] = true;
                        $entries[] = [
                            'word_key'   => $key,
                            'japanese'   => $k['kanji'],
                            'reading'    => $k['kun'] ?? ($k['on'] ?? ''),
                            'romaji'     => 'Kun: ' . ($k['kun'] ?? '-') . ' | On: ' . ($k['on'] ?? '-'),
                            'meaning'    => $k['meaning'] ?? '',
                            'category'   => 'Kanji N5',
                            'level'      => 'N5',
                            'example_ja' => null,
                            'example_id' => null,
                        ];
                    }
                }
            }
        }

        // 2. Gabungkan dengan katalog esensial tambahan
        foreach ($this->getCuratedEntries() as $entry) {
            $key = 'dict_' . md5($entry['japanese'] . $entry['romaji']);
            if (!isset($seenKeys[$key])) {
                $seenKeys[$key] = true;
                $entries[] = array_merge($entry, ['word_key' => $key]);
            }
        }

        return $entries;
    }

    /**
     * Daftar kategori untuk filter.
     *
     * @return array<int, string>
     */
    public function getCategories(): array
    {
        return [
            'Semua',
            'Salam & Ungkapan',
            'Kata Kerja Dasar',
            'Kata Sifat-i',
            'Kata Sifat-na',
            'Benda & Kehidupan',
            'Waktu & Hari',
            'Makanan & Minuman',
            'Tempat & Arah',
            'Keluarga & Orang',
            'Angka & Bilangan',
            'Kanji N5',
        ];
    }

    /**
     * Cari entri berdasarkan kata kunci dan kategori.
     *
     * @return array<int, array{word_key: string, japanese: string, reading: string, romaji: string, meaning: string, category: string, level: string, example_ja: ?string, example_id: ?string}>
     */
    public function search(?string $query = null, ?string $category = null): array
    {
        $all = $this->getAllEntries();

        if (empty($query) && (empty($category) || $category === 'Semua')) {
            return $all;
        }

        $needle = $query ? mb_strtolower(trim($query)) : null;

        return array_values(array_filter($all, function (array $item) use ($needle, $category): bool {
            // Filter kategori
            if ($category && $category !== 'Semua') {
                if (stripos($item['category'], $category) === false && stripos($category, $item['category']) === false) {
                    return false;
                }
            }

            // Filter keyword
            if ($needle !== null && $needle !== '') {
                $matchJa = mb_stripos($item['japanese'], $needle) !== false;
                $matchReading = mb_stripos($item['reading'], $needle) !== false;
                $matchRo = stripos($item['romaji'], $needle) !== false;
                $matchMeaning = stripos($item['meaning'], $needle) !== false;

                return $matchJa || $matchReading || $matchRo || $matchMeaning;
            }

            return true;
        }));
    }

    /**
     * Contoh kalimat otomatis untuk kosakata standar.
     */
    private function getAutoExampleJa(string $japanese): ?string
    {
        return match ($japanese) {
            'たべます' => 'わたしは ごはんを たべます。',
            'のみます' => 'つめたい みずを のみます。',
            'いきます' => 'あした がっこうへ いきます。',
            'きます'   => 'ともだちが うちへ きます。',
            'ほん'     => 'にほんごの ほんを よみます。',
            'みず'     => 'みずを ください。',
            'くるま'   => 'あかい くるまが あります。',
            'せんせい' => 'せんせいに あいさつします。',
            'がくせい' => 'わたしは がくせいです。',
            'うち'     => 'いま うちに かえります。',
            'えき'     => 'とうきょうえきで あいましょう。',
            default    => null,
        };
    }

    /**
     * Arti contoh kalimat otomatis.
     */
    private function getAutoExampleId(string $japanese, string $meaning): ?string
    {
        return match ($japanese) {
            'たべます' => 'Saya makan nasi.',
            'のみます' => 'Saya minum air dingin.',
            'いきます' => 'Besok saya pergi ke sekolah.',
            'きます'   => 'Teman datang ke rumah saya.',
            'ほん'     => 'Saya membaca buku bahasa Jepang.',
            'みず'     => 'Tolong beri saya air.',
            'くるま'   => 'Ada mobil berwarna merah.',
            'せんせい' => 'Memberi salam kepada guru.',
            'がくせい' => 'Saya adalah seorang pelajar/mahasiswa.',
            'うち'     => 'Sekarang saya pulang ke rumah.',
            'えき'     => 'Ayo bertemu di stasiun Tokyo.',
            default    => null,
        };
    }

    /**
     * Katalog kosakata kurasi esensial JLPT N5.
     *
     * @return array<int, array{japanese: string, reading: string, romaji: string, meaning: string, category: string, level: string, example_ja: ?string, example_id: ?string}>
     */
    private function getCuratedEntries(): array
    {
        return [
            // ── Kata Kerja Dasar (Verbs) ─────────────────────────
            [
                'japanese'   => '見る (みます)',
                'reading'    => 'みます',
                'romaji'     => 'mimasu / miru',
                'meaning'    => 'Melihat / Menonton',
                'category'   => 'Kata Kerja Dasar',
                'level'      => 'N5',
                'example_ja' => 'テレビを みます。',
                'example_id' => 'Menonton televisi.',
            ],
            [
                'japanese'   => '聞く (ききます)',
                'reading'    => 'ききます',
                'romaji'     => 'kikimasu / kiku',
                'meaning'    => 'Mendengar / Bertanya',
                'category'   => 'Kata Kerja Dasar',
                'level'      => 'N5',
                'example_ja' => 'おんがくを ききます。',
                'example_id' => 'Mendengarkan musik.',
            ],
            [
                'japanese'   => '書く (かきます)',
                'reading'    => 'かきます',
                'romaji'     => 'kakimasu / kaku',
                'meaning'    => 'Menulis / Menggambar',
                'category'   => 'Kata Kerja Dasar',
                'level'      => 'N5',
                'example_ja' => 'てがみを かきます。',
                'example_id' => 'Menulis surat.',
            ],
            [
                'japanese'   => '読む (よみます)',
                'reading'    => 'よみます',
                'romaji'     => 'yomimasu / yomu',
                'meaning'    => 'Membaca',
                'category'   => 'Kata Kerja Dasar',
                'level'      => 'N5',
                'example_ja' => 'しんぶんを よみます。',
                'example_id' => 'Membaca koran.',
            ],
            [
                'japanese'   => '買う (かいます)',
                'reading'    => 'かいます',
                'romaji'     => 'kaimasu / kau',
                'meaning'    => 'Membeli',
                'category'   => 'Kata Kerja Dasar',
                'level'      => 'N5',
                'example_ja' => 'パンを かいます。',
                'example_id' => 'Membeli roti.',
            ],
            [
                'japanese'   => '話す (はなします)',
                'reading'    => 'はなします',
                'romaji'     => 'hanashimasu / hanasu',
                'meaning'    => 'Berbicara / Mengobrol',
                'category'   => 'Kata Kerja Dasar',
                'level'      => 'N5',
                'example_ja' => 'にほんごで はなします。',
                'example_id' => 'Berbicara dalam bahasa Jepang.',
            ],
            [
                'japanese'   => '寝る (ねます)',
                'reading'    => 'ねます',
                'romaji'     => 'nemasu / neru',
                'meaning'    => 'Tidur',
                'category'   => 'Kata Kerja Dasar',
                'level'      => 'N5',
                'example_ja' => 'よる じゅうにじに ねます。',
                'example_id' => 'Tidur pada jam 12 malam.',
            ],
            [
                'japanese'   => '起きる (おきます)',
                'reading'    => 'おきます',
                'romaji'     => 'okimasu / okiru',
                'meaning'    => 'Bangun tidur',
                'category'   => 'Kata Kerja Dasar',
                'level'      => 'N5',
                'example_ja' => 'あさ ろくじに おきます。',
                'example_id' => 'Bangun tidur pada jam 6 pagi.',
            ],
            [
                'japanese'   => '帰る (かえります)',
                'reading'    => 'かえります',
                'romaji'     => 'kaerimasu / kaeru',
                'meaning'    => 'Pulang',
                'category'   => 'Kata Kerja Dasar',
                'level'      => 'N5',
                'example_ja' => 'うちに かえります。',
                'example_id' => 'Pulang ke rumah.',
            ],
            [
                'japanese'   => '会う (あいます)',
                'reading'    => 'あいます',
                'romaji'     => 'aimasu / au',
                'meaning'    => 'Bertemu',
                'category'   => 'Kata Kerja Dasar',
                'level'      => 'N5',
                'example_ja' => 'ともだちに あいます。',
                'example_id' => 'Bertemu dengan kawan.',
            ],
            [
                'japanese'   => '教える (おしえます)',
                'reading'    => 'おしえます',
                'romaji'     => 'oshiemasu / oshieru',
                'meaning'    => 'Mengajar / Memberitahu',
                'category'   => 'Kata Kerja Dasar',
                'level'      => 'N5',
                'example_ja' => 'えいごを おしえます。',
                'example_id' => 'Mengajar bahasa Inggris.',
            ],
            [
                'japanese'   => '習う (ならいます)',
                'reading'    => 'ならいます',
                'romaji'     => 'naraimasu / narau',
                'meaning'    => 'Belajar (dari orang lain)',
                'category'   => 'Kata Kerja Dasar',
                'level'      => 'N5',
                'example_ja' => 'ピアノを ならいます。',
                'example_id' => 'Belajar bermain piano.',
            ],

            // ── Kata Sifat-i (i-adjectives) ────────────────────────
            [
                'japanese'   => '大きい (おおきい)',
                'reading'    => 'おおきい',
                'romaji'     => 'ookii',
                'meaning'    => 'Besar',
                'category'   => 'Kata Sifat-i',
                'level'      => 'N5',
                'example_ja' => 'おおきい いえです。',
                'example_id' => 'Rumah yang besar.',
            ],
            [
                'japanese'   => '小さい (ちいさい)',
                'reading'    => 'ちいさい',
                'romaji'     => 'chiisai',
                'meaning'    => 'Kecil',
                'category'   => 'Kata Sifat-i',
                'level'      => 'N5',
                'example_ja' => 'ちいさい ねこです。',
                'example_id' => 'Kucing yang kecil.',
            ],
            [
                'japanese'   => '新しい (あたらしい)',
                'reading'    => 'あたらしい',
                'romaji'     => 'atarashii',
                'meaning'    => 'Baru',
                'category'   => 'Kata Sifat-i',
                'level'      => 'N5',
                'example_ja' => 'あたらしい くるまを かいました。',
                'example_id' => 'Membeli mobil baru.',
            ],
            [
                'japanese'   => '古い (ふるい)',
                'reading'    => 'ふるい',
                'romaji'     => 'furui',
                'meaning'    => 'Lama / Kuno',
                'category'   => 'Kata Sifat-i',
                'level'      => 'N5',
                'example_ja' => 'これは ふるい ほん です。',
                'example_id' => 'Ini adalah buku lama.',
            ],
            [
                'japanese'   => '良い (いい / よい)',
                'reading'    => 'いい / よい',
                'romaji'     => 'ii / yoi',
                'meaning'    => 'Bagus / Baik',
                'category'   => 'Kata Sifat-i',
                'level'      => 'N5',
                'example_ja' => 'きょうは てんきが いいです。',
                'example_id' => 'Hari ini cuacanya bagus.',
            ],
            [
                'japanese'   => '悪い (わるい)',
                'reading'    => 'わるい',
                'romaji'     => 'warui',
                'meaning'    => 'Buruk / Jelek',
                'category'   => 'Kata Sifat-i',
                'level'      => 'N5',
                'example_ja' => 'きぶんが わるいです。',
                'example_id' => 'Merasa kurang enak badan.',
            ],
            [
                'japanese'   => '高い (たかい)',
                'reading'    => 'たかい',
                'romaji'     => 'takai',
                'meaning'    => 'Tinggi / Mahal',
                'category'   => 'Kata Sifat-i',
                'level'      => 'N5',
                'example_ja' => 'この 時計は たかいです。',
                'example_id' => 'Jam ini mahal.',
            ],
            [
                'japanese'   => '安い (やすい)',
                'reading'    => 'やすい',
                'romaji'     => 'yasui',
                'meaning'    => 'Murah',
                'category'   => 'Kata Sifat-i',
                'level'      => 'N5',
                'example_ja' => 'この レストランは やすいです。',
                'example_id' => 'Restoran ini murah.',
            ],
            [
                'japanese'   => '美味しい (おいしい)',
                'reading'    => 'おいしい',
                'romaji'     => 'oishii',
                'meaning'    => 'Enak / Lezat',
                'category'   => 'Kata Sifat-i',
                'level'      => 'N5',
                'example_ja' => 'ラーメンは とても おいしいです。',
                'example_id' => 'Ramen ini sangat lezat.',
            ],
            [
                'japanese'   => '面白い (おもしろい)',
                'reading'    => 'おもしろい',
                'romaji'     => 'omoshiroi',
                'meaning'    => 'Menarik / Lucu',
                'category'   => 'Kata Sifat-i',
                'level'      => 'N5',
                'example_ja' => 'にほんの アニメは おもしろいです。',
                'example_id' => 'Anime Jepang sangat menarik.',
            ],
            [
                'japanese'   => '暑い (あつい)',
                'reading'    => 'あつい',
                'romaji'     => 'atsui',
                'meaning'    => 'Panas (cuaca/udara)',
                'category'   => 'Kata Sifat-i',
                'level'      => 'N5',
                'example_ja' => 'きょうは とても あついです。',
                'example_id' => 'Hari ini sangat panas.',
            ],
            [
                'japanese'   => '寒い (さむい)',
                'reading'    => 'さむい',
                'romaji'     => 'samui',
                'meaning'    => 'Dingin (cuaca/udara)',
                'category'   => 'Kata Sifat-i',
                'level'      => 'N5',
                'example_ja' => 'ふゆは さむいです。',
                'example_id' => 'Musim dingin terasa dingin.',
            ],

            // ── Kata Sifat-na (na-adjectives) ───────────────────────
            [
                'japanese'   => '好き (すき)',
                'reading'    => 'すき',
                'romaji'     => 'suki',
                'meaning'    => 'Suka / Gemar',
                'category'   => 'Kata Sifat-na',
                'level'      => 'N5',
                'example_ja' => 'わたしは おちゃが すきです。',
                'example_id' => 'Saya suka teh Jepang.',
            ],
            [
                'japanese'   => '嫌い (きらい)',
                'reading'    => 'きらい',
                'romaji'     => 'kirai',
                'meaning'    => 'Benci / Tidak suka',
                'category'   => 'Kata Sifat-na',
                'level'      => 'N5',
                'example_ja' => 'からい たべものが きらいです。',
                'example_id' => 'Saya tidak suka makanan pedas.',
            ],
            [
                'japanese'   => '上手 (じょうず)',
                'reading'    => 'じょうず',
                'romaji'     => 'jouzu',
                'meaning'    => 'Pandai / Mahir',
                'category'   => 'Kata Sifat-na',
                'level'      => 'N5',
                'example_ja' => '日本語が じょうずですね。',
                'example_id' => 'Bahasa Jepang Anda pintar sekali ya.',
            ],
            [
                'japanese'   => '下手 (へた)',
                'reading'    => 'へた',
                'romaji'     => 'heta',
                'meaning'    => 'Kurang mahir / Tidak bisa',
                'category'   => 'Kata Sifat-na',
                'level'      => 'N5',
                'example_ja' => 'うたが へたです。',
                'example_id' => 'Saya kurang mahir bernyanyi.',
            ],
            [
                'japanese'   => '静か (しずか)',
                'reading'    => 'しずか',
                'romaji'     => 'shizuka',
                'meaning'    => 'Tenang / Sunyi',
                'category'   => 'Kata Sifat-na',
                'level'      => 'N5',
                'example_ja' => 'としょかんは しずかです。',
                'example_id' => 'Perpustakaan itu tenang.',
            ],
            [
                'japanese'   => '賑やか (にぎやか)',
                'reading'    => 'にぎやか',
                'romaji'     => 'nigiyaka',
                'meaning'    => 'Ramai / Meriah',
                'category'   => 'Kata Sifat-na',
                'level'      => 'N5',
                'example_ja' => 'しんじゅくは にぎやかな まちです。',
                'example_id' => 'Shinjuku adalah kota yang ramai.',
            ],
            [
                'japanese'   => '便利 (べんり)',
                'reading'    => 'べんり',
                'romaji'     => 'benri',
                'meaning'    => 'Praktis / Nyaman',
                'category'   => 'Kata Sifat-na',
                'level'      => 'N5',
                'example_ja' => 'ちかてつは とても べんりです。',
                'example_id' => 'Kereta bawah tanah sangat praktis.',
            ],
            [
                'japanese'   => '元気 (げんき)',
                'reading'    => 'げんき',
                'romaji'     => 'genki',
                'meaning'    => 'Sehat / Bersemangat',
                'category'   => 'Kata Sifat-na',
                'level'      => 'N5',
                'example_ja' => 'おげんきですか。',
                'example_id' => 'Apakah Anda sehat?',
            ],

            // ── Benda & Kehidupan (Objects & Daily Life) ───────────
            [
                'japanese'   => '電話 (でんわ)',
                'reading'    => 'でんわ',
                'romaji'     => 'denwa',
                'meaning'    => 'Telepon / HP',
                'category'   => 'Benda & Kehidupan',
                'level'      => 'N5',
                'example_ja' => 'ともだちに でんわを かけます。',
                'example_id' => 'Menelepon teman.',
            ],
            [
                'japanese'   => '時計 (とけい)',
                'reading'    => 'とけい',
                'romaji'     => 'tokei',
                'meaning'    => 'Jam / Jam tangan',
                'category'   => 'Benda & Kehidupan',
                'level'      => 'N5',
                'example_ja' => 'いま なんじの とけいですか。',
                'example_id' => 'Sekarang jam berapa di arloji itu?',
            ],
            [
                'japanese'   => '鞄 (かばん)',
                'reading'    => 'かばん',
                'romaji'     => 'kaban',
                'meaning'    => 'Tas',
                'category'   => 'Benda & Kehidupan',
                'level'      => 'N5',
                'example_ja' => 'くろい かばんを もっています。',
                'example_id' => 'Membawa tas berwarna hitam.',
            ],
            [
                'japanese'   => '傘 (かさ)',
                'reading'    => 'かさ',
                'romaji'     => 'kasa',
                'meaning'    => 'Payung',
                'category'   => 'Benda & Kehidupan',
                'level'      => 'N5',
                'example_ja' => 'あめですから かさを さします。',
                'example_id' => 'Karena hujan, saya memakai payung.',
            ],
            [
                'japanese'   => 'お金 (おかね)',
                'reading'    => 'おかね',
                'romaji'     => 'okane',
                'meaning'    => 'Uang',
                'category'   => 'Benda & Kehidupan',
                'level'      => 'N5',
                'example_ja' => 'おかねが ありません。',
                'example_id' => 'Tidak punya uang.',
            ],
            [
                'japanese'   => '辞書 (じしょ)',
                'reading'    => 'じしょ',
                'romaji'     => 'jisho',
                'meaning'    => 'Kamus',
                'category'   => 'Benda & Kehidupan',
                'level'      => 'N5',
                'example_ja' => 'にほんごの じしょを つかいます。',
                'example_id' => 'Menggunakan kamus bahasa Jepang.',
            ],
            [
                'japanese'   => '手紙 (てがみ)',
                'reading'    => 'てがみ',
                'romaji'     => 'tegami',
                'meaning'    => 'Surat',
                'category'   => 'Benda & Kehidupan',
                'level'      => 'N5',
                'example_ja' => 'かぞくに てがみを おくります。',
                'example_id' => 'Mengirim surat ke keluarga.',
            ],

            // ── Tempat & Arah (Places & Directions) ───────────────
            [
                'japanese'   => '病院 (びょういん)',
                'reading'    => 'びょういん',
                'romaji'     => 'byouin',
                'meaning'    => 'Rumah Sakit',
                'category'   => 'Tempat & Arah',
                'level'      => 'N5',
                'example_ja' => 'びょういんへ いきます。',
                'example_id' => 'Pergi ke rumah sakit.',
            ],
            [
                'japanese'   => '銀行 (ぎんこう)',
                'reading'    => 'ぎんこう',
                'romaji'     => 'ginkou',
                'meaning'    => 'Bank',
                'category'   => 'Tempat & Arah',
                'level'      => 'N5',
                'example_ja' => 'ぎんこうで おかねを だします。',
                'example_id' => 'Mengambil uang di bank.',
            ],
            [
                'japanese'   => '郵便局 (ゆうびんきょく)',
                'reading'    => 'ゆうびんきょく',
                'romaji'     => 'yuubinkyoku',
                'meaning'    => 'Kantor Pos',
                'category'   => 'Tempat & Arah',
                'level'      => 'N5',
                'example_ja' => 'ゆうびんきょくで きってを かいます。',
                'example_id' => 'Membeli perangko di kantor pos.',
            ],
            [
                'japanese'   => '部屋 (へや)',
                'reading'    => 'へや',
                'romaji'     => 'heya',
                'meaning'    => 'Kamar / Ruangan',
                'category'   => 'Tempat & Arah',
                'level'      => 'N5',
                'example_ja' => 'わたしの へやは ひろいです。',
                'example_id' => 'Kamar saya luas.',
            ],
            [
                'japanese'   => 'トイレ (お手洗い)',
                'reading'    => 'トイレ / おてあらい',
                'romaji'     => 'toire / otearai',
                'meaning'    => 'Toilet / Kamar Kecil',
                'category'   => 'Tempat & Arah',
                'level'      => 'N5',
                'example_ja' => 'トイレは どこですか。',
                'example_id' => 'Di mana letak toilet?',
            ],

            // ── Keluarga & Orang (Family & People) ────────────────
            [
                'japanese'   => '家族 (かぞく)',
                'reading'    => 'かぞく',
                'romaji'     => 'kazoku',
                'meaning'    => 'Keluarga',
                'category'   => 'Keluarga & Orang',
                'level'      => 'N5',
                'example_ja' => 'わたしの かぞくは よにんです。',
                'example_id' => 'Keluarga saya ada 4 orang.',
            ],
            [
                'japanese'   => '父 (ちち / お父さん)',
                'reading'    => 'ちち / おとうさん',
                'romaji'     => 'chichi / otousan',
                'meaning'    => 'Ayah / Bapak',
                'category'   => 'Keluarga & Orang',
                'level'      => 'N5',
                'example_ja' => 'ちちは かいしゃいんです。',
                'example_id' => 'Ayah saya seorang pegawai perusahaan.',
            ],
            [
                'japanese'   => '母 (はは / お母さん)',
                'reading'    => 'はは / おかあさん',
                'romaji'     => 'haha / okaasan',
                'meaning'    => 'Ibu / Bunda',
                'category'   => 'Keluarga & Orang',
                'level'      => 'N5',
                'example_ja' => 'ははの りょうりは おいしいです。',
                'example_id' => 'Masakan ibu sangat enak.',
            ],
            [
                'japanese'   => '子供 (こども)',
                'reading'    => 'こども',
                'romaji'     => 'kodomo',
                'meaning'    => 'Anak / Anak-anak',
                'category'   => 'Keluarga & Orang',
                'level'      => 'N5',
                'example_ja' => 'こうえんで こどもが あそびます。',
                'example_id' => 'Anak-anak bermain di taman.',
            ],

            // ── Makanan & Minuman (Food & Drinks) ─────────────────
            [
                'japanese'   => '肉 (にく)',
                'reading'    => 'にく',
                'romaji'     => 'niku',
                'meaning'    => 'Daging',
                'category'   => 'Makanan & Minuman',
                'level'      => 'N5',
                'example_ja' => 'ぎゅうにくを たべます。',
                'example_id' => 'Makan daging sapi.',
            ],
            [
                'japanese'   => '魚 (さかな)',
                'reading'    => 'さかな',
                'romaji'     => 'sakana',
                'meaning'    => 'Ikan',
                'category'   => 'Makanan & Minuman',
                'level'      => 'N5',
                'example_ja' => 'さかなが すきです。',
                'example_id' => 'Saya suka ikan.',
            ],
            [
                'japanese'   => '野菜 (やさい)',
                'reading'    => 'やさい',
                'romaji'     => 'yasai',
                'meaning'    => 'Sayuran',
                'category'   => 'Makanan & Minuman',
                'level'      => 'N5',
                'example_ja' => 'まいにち やさいを たべます。',
                'example_id' => 'Setiap hari makan sayur.',
            ],
            [
                'japanese'   => '果物 (くだもの)',
                'reading'    => 'くだもの',
                'romaji'     => 'kudamono',
                'meaning'    => 'Buah-buahan',
                'category'   => 'Makanan & Minuman',
                'level'      => 'N5',
                'example_ja' => 'くだものを かいました。',
                'example_id' => 'Saya membeli buah-buahan.',
            ],
            [
                'japanese'   => 'お茶 (おちゃ)',
                'reading'    => 'おちゃ',
                'romaji'     => 'ocha',
                'meaning'    => 'Teh Jepang',
                'category'   => 'Makanan & Minuman',
                'level'      => 'N5',
                'example_ja' => 'あたたかい おちゃを のみます。',
                'example_id' => 'Minum teh hangat.',
            ],
            [
                'japanese'   => 'コーヒー',
                'reading'    => 'コーヒー',
                'romaji'     => 'koohii',
                'meaning'    => 'Kopi',
                'category'   => 'Makanan & Minuman',
                'level'      => 'N5',
                'example_ja' => 'あさ コーヒーを のみます。',
                'example_id' => 'Pagi hari saya minum kopi.',
            ],
        ];
    }
}

