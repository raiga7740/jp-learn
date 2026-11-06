@extends('dashboard.layout')

@php
    $pageTitle = 'Simulasi Ujian JLPT N5';
    $pageSubtitle = 'Uji kesiapan resmi standar Japanese Language Proficiency Test tingkat dasar (N5).';
@endphp

@section('styles')
    .jlpt-page {
        max-width: 1040px;
        margin: 0 auto;
    }

    /* ── Hero Exam Card ── */
    .jlpt-hero {
        background: linear-gradient(135deg, #181335 0%, #2f1737 100%);
        border-radius: 22px;
        padding: 36px 36px 32px;
        color: #fff;
        margin-bottom: 28px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 45px -14px rgba(24, 19, 53, .45);
    }
    .jlpt-hero::after {
        content: 'JLPT N5';
        position: absolute;
        right: 28px;
        bottom: -20px;
        font-family: 'Inter', sans-serif;
        font-size: 110px;
        font-weight: 900;
        color: rgba(255, 255, 255, .03);
        pointer-events: none;
        line-height: 1;
    }
    .jlpt-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 14px;
        border-radius: 999px;
        background: rgba(255, 255, 255, .15);
        color: #ffd8a8;
        font-size: 11.5px;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
        margin-bottom: 14px;
        backdrop-filter: blur(8px);
    }
    .jlpt-hero-title {
        font-size: 28px;
        font-weight: 900;
        color: #fff;
        margin-bottom: 10px;
        line-height: 1.25;
    }
    .jlpt-hero-desc {
        color: rgba(255, 255, 255, .8);
        font-size: 14.5px;
        max-width: 640px;
        line-height: 1.6;
        margin-bottom: 24px;
    }
    .jlpt-hero-meta {
        display: flex;
        align-items: center;
        gap: 24px;
        margin-bottom: 26px;
        flex-wrap: wrap;
    }
    .jlpt-meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13.5px;
        color: rgba(255, 255, 255, .9);
        font-weight: 600;
    }
    .jlpt-meta-item svg {
        color: #ff6b8a;
    }

    .btn-start-exam {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 14px 28px;
        border-radius: 14px;
        background: linear-gradient(135deg, var(--red-2), var(--red));
        color: #fff;
        font-size: 15px;
        font-weight: 900;
        text-decoration: none;
        box-shadow: 0 8px 24px -6px rgba(232, 35, 79, .6);
        transition: all .2s ease;
    }
    .btn-start-exam:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 28px -4px rgba(232, 35, 79, .75);
    }

    /* ── Overview Stats ── */
    .jlpt-stat-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 30px;
    }
    .jlpt-stat-card {
        background: var(--card);
        border: 1px solid var(--line);
        border-radius: 18px;
        padding: 20px 22px;
        box-shadow: var(--shadow-sm);
    }
    .jlpt-stat-card strong {
        display: block;
        font-size: 26px;
        font-weight: 900;
        color: var(--ink);
        margin-bottom: 4px;
    }
    .jlpt-stat-card span {
        font-size: 12.5px;
        color: var(--muted);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    /* ── Exam Rules & Sections ── */
    .jlpt-info-grid {
        display: grid;
        grid-template-columns: 1.2fr 1fr;
        gap: 20px;
        margin-bottom: 32px;
    }
    .jlpt-panel {
        background: var(--card);
        border: 1px solid var(--line);
        border-radius: 20px;
        padding: 24px 26px;
        box-shadow: var(--shadow-sm);
    }
    .jlpt-panel-title {
        font-size: 16.5px;
        font-weight: 800;
        color: var(--ink);
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .rule-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 14px;
        font-size: 13.5px;
        color: #443540;
        line-height: 1.5;
    }
    .rule-icon {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: var(--red-soft);
        color: var(--red);
        display: grid;
        place-items: center;
        font-size: 12px;
        font-weight: 800;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .section-breakdown-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 14px;
        border-radius: 12px;
        background: var(--bg);
        margin-bottom: 10px;
        font-size: 13px;
        border: 1px solid var(--line);
    }
    .section-breakdown-item strong {
        color: var(--ink);
        display: block;
        font-size: 13.5px;
    }
    .section-breakdown-item span {
        color: var(--muted);
        font-size: 11.5px;
    }
    .section-score-badge {
        padding: 4px 10px;
        border-radius: 8px;
        background: #fff;
        border: 1px solid var(--line);
        font-size: 12px;
        font-weight: 800;
        color: var(--navy);
    }

    /* ── History Table ── */
    .jlpt-history-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13.5px;
    }
    .jlpt-history-table th {
        text-align: left;
        padding: 12px 16px;
        color: var(--muted);
        font-size: 11.5px;
        font-weight: 800;
        text-transform: uppercase;
        border-bottom: 2px solid var(--line);
    }
    .jlpt-history-table td {
        padding: 14px 16px;
        border-bottom: 1px solid var(--line);
        color: var(--ink);
        font-weight: 600;
    }
    .jlpt-history-table tr:last-child td {
        border-bottom: none;
    }
    .badge-passed {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 10px;
        border-radius: 999px;
        background: #d3f9d8;
        color: #2b8a3e;
        font-weight: 800;
        font-size: 11.5px;
    }
    .badge-failed {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 10px;
        border-radius: 999px;
        background: #ffe3e3;
        color: #c92a2a;
        font-weight: 800;
        font-size: 11.5px;
    }

    @media (max-width: 768px) {
        .jlpt-stat-grid { grid-template-columns: 1fr; }
        .jlpt-info-grid { grid-template-columns: 1fr; }
        .jlpt-hero { padding: 26px 22px; }
        .jlpt-hero-title { font-size: 22px; }
    }
@endsection

@section('content')
<div class="jlpt-page">
    <!-- Hero Exam Banner -->
    <div class="jlpt-hero">
        <div class="jlpt-hero-badge">
            <svg width="16" height="12" viewBox="0 0 24 16" style="border-radius:2px; box-shadow:0 0 1px rgba(0,0,0,.3); display:inline-block; vertical-align:middle; background:#fff;">
                <circle cx="12" cy="8" r="5" fill="#bc002d"></circle>
            </svg>
            日本語能力試験 N5 模擬試験
        </div>
        <h1 class="jlpt-hero-title">Simulasi Ujian Resmi JLPT N5</h1>
        <p class="jlpt-hero-desc">
            Uji kemampuan bahasa Jepangmu dengan simulasi ujian terstandarisasi JLPT N5. Mencakup 30 soal terpilih (*Moji, Goi, Kanji, Bunpou, Dokkai*) dengan batasan waktu 30 menit dan sertifikat kelulusan resmi!
        </p>

        <div class="jlpt-hero-meta">
            <div class="jlpt-meta-item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                <span>Durasi: 30 Menit (Waktu Nyata)</span>
            </div>
            <div class="jlpt-meta-item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                </svg>
                <span>30 Soal Terkurasi (180 Poin)</span>
            </div>
            <div class="jlpt-meta-item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                </svg>
                <span>Standar Kelulusan: Min 80 / 180</span>
            </div>
        </div>

        <div>
            <a href="{{ route('jlpt.start') }}" class="btn-start-exam" onclick="return confirm('Mulai simulasi ujian sekarang? Waktu 30 menit akan langsung berjalan.')">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="5 3 19 12 5 21 5 3"></polygon>
                </svg>
                Mulai Ujian Sekarang
            </a>
        </div>
    </div>

    <!-- Overview Stats -->
    <div class="jlpt-stat-grid">
        <div class="jlpt-stat-card">
            <strong>{{ $totalAttempts }}x</strong>
            <span>Total Percobaan Ujian</span>
        </div>
        <div class="jlpt-stat-card">
            <strong style="color:var(--red);">{{ $bestScore }} / 180</strong>
            <span>Skor Tertinggi Kamu</span>
        </div>
        <div class="jlpt-stat-card">
            @if($hasPassed)
                <strong style="color:#2b8a3e;">合格 (LULUS)</strong>
                <span>Status Kelulusan Terbaik</span>
            @else
                <strong style="color:var(--muted);">Belum Lulus</strong>
                <span>Butuh Minimal 80 Poin</span>
            @endif
        </div>
    </div>

    <!-- Exam Info & Sections Breakdown -->
    <div class="jlpt-info-grid">
        <!-- Rules -->
        <div class="jlpt-panel">
            <h2 class="jlpt-panel-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color:var(--red);">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                Petunjuk Pengerjaan Ujian
            </h2>

            <div class="rule-item">
                <div class="rule-icon">1</div>
                <div>
                    <strong>Waktu Pengerjaan 30 Menit</strong>: Waktu berjalan mundur otomatis begitu tombol mulai ditekan. Jika waktu habis, jawaban otomatis tersimpan.
                </div>
            </div>

            <div class="rule-item">
                <div class="rule-icon">2</div>
                <div>
                    <strong>Lembar Jawaban Digital</strong>: Gunakan palet nomor soal di samping untuk melompat antar soal dan menandai soal yang masih kamu ragukan (*flag*).
                </div>
            </div>

            <div class="rule-item">
                <div class="rule-icon">3</div>
                <div>
                    <strong>Sertifikat Digital Resmi</strong>: Jika meraih skor kelulusan minimal 80/180 dengan ambang seksi terpenuhi, kamu akan memperoleh sertifikat kelulusan digital yang dapat dicetak/disimpan.
                </div>
            </div>

            <div class="rule-item">
                <div class="rule-icon">4</div>
                <div>
                    <strong>Reward +50 XP</strong>: Menyelesaikan simulasi ujian akan menghadiahkan poin XP besar untuk mempercepat kenaikan level belajarmu!
                </div>
            </div>
        </div>

        <!-- Section Breakdown -->
        <div class="jlpt-panel">
            <h2 class="jlpt-panel-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color:var(--blue);">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                </svg>
                Komposisi Soal & Seksi Ujian
            </h2>

            <div class="section-breakdown-item">
                <div>
                    <strong>文字・語彙 (Huruf, Kosakata & Kanji)</strong>
                    <span>Cara baca kanji, kanji hiragana, dan kosakata</span>
                </div>
                <div class="section-score-badge">12 Soal · 72 Poin</div>
            </div>

            <div class="section-breakdown-item">
                <div>
                    <strong>文法 (Tata Bahasa / Bunpou)</strong>
                    <span>Partikel (joshi), bentuk kata kerja, kalimat bintang</span>
                </div>
                <div class="section-score-badge">12 Soal · 72 Poin</div>
            </div>

            <div class="section-breakdown-item">
                <div>
                    <strong>読解 (Pemahaman Bacaan / Dokkai)</strong>
                    <span>Pesan memo dan bacaan teks pendek cerita</span>
                </div>
                <div class="section-score-badge">6 Soal · 36 Poin</div>
            </div>

            <div style="display:flex; align-items:flex-start; gap:8px; margin-top:14px; padding:12px; border-radius:12px; background:var(--gold-soft); border:1px solid #ffd8a8; font-size:12.5px; color:#7c4e09;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; margin-top:2px;">
                    <path d="M9 18h6"></path>
                    <path d="M10 22h4"></path>
                    <path d="M15.09 14c.18-.98.65-1.74 1.41-2.5A4.65 4.65 0 0 0 18 8 6 6 0 0 0 6 8c0 1 .23 2.23 1.5 3.5A4.61 4.61 0 0 1 8.91 14"></path>
                </svg>
                <span><strong>Tips Lulus:</strong> Usahakan menjawab seluruh pertanyaan tanpa mengosongkannya karena tidak ada sistem minus untuk jawaban salah.</span>
            </div>
        </div>
    </div>

    <!-- History Table -->
    <div class="jlpt-panel">
        <h2 class="jlpt-panel-title">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color:#0ca678;">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
            Riwayat Simulasi Ujian Kamu
        </h2>

        @if($history->isEmpty())
            <div style="text-align:center; padding:32px 16px; color:var(--muted);">
                <p style="margin-bottom:12px;">Kamu belum pernah mengerjakan simulasi ujian JLPT N5.</p>
                <a href="{{ route('jlpt.start') }}" class="button" onclick="return confirm('Mulai simulasi ujian sekarang?')">
                    Coba Ujian Pertama
                </a>
            </div>
        @else
            <div style="overflow-x:auto;">
                <table class="jlpt-history-table">
                    <thead>
                        <tr>
                            <th>No. Sertifikat</th>
                            <th>Tanggal Ujian</th>
                            <th>Skor Total</th>
                            <th>Grade</th>
                            <th>Waktu Pengerjaan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($history as $item)
                            <tr>
                                <td><code>{{ $item->certificate_no }}</code></td>
                                <td>{{ $item->created_at->format('d M Y, H:i') }}</td>
                                <td><strong>{{ $item->score }}</strong> / 180</td>
                                <td><span class="badge blue">{{ $item->grade }}</span></td>
                                <td>{{ gmdate('i:s', $item->time_taken) }}</td>
                                <td>
                                    @if($item->is_passed)
                                        <span class="badge-passed" style="display:inline-flex; align-items:center; gap:5px;">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                            LULUS (合格)
                                        </span>
                                    @else
                                        <span class="badge-failed" style="display:inline-flex; align-items:center; gap:5px;">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                            BELUM LULUS
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div style="display:flex; gap:8px;">
                                        <a href="{{ route('jlpt.result', $item->id) }}" class="button ghost" style="padding:4px 10px; font-size:12px;">
                                            Hasil & Review
                                        </a>
                                        @if($item->is_passed)
                                            <a href="{{ route('jlpt.certificate', $item->id) }}" class="button" style="display:inline-flex; align-items:center; gap:5px; padding:4px 10px; font-size:12px; background:var(--green);">
                                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <circle cx="12" cy="8" r="7"></circle>
                                                    <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
                                                </svg>
                                                Sertifikat
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection

