<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat Kelulusan JLPT N5 — {{ $user->name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800;900&family=Inter:wght@400;500;600;700;800;900&family=Noto+Serif+JP:wght@600;700;900&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            background: #2a2238;
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 30px 16px;
            color: #1a0e1c;
        }

        /* ── Top Floating Action Bar ── */
        .cert-nav-bar {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
        }
        .btn-cert-nav {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 12px;
            font-size: 13.5px;
            font-weight: 800;
            cursor: pointer;
            text-decoration: none;
            transition: .2s;
            border: none;
        }
        .btn-print {
            background: linear-gradient(135deg, #c4102e, #e8234f);
            color: #fff;
            box-shadow: 0 6px 18px -4px rgba(196, 16, 46, .6);
        }
        .btn-print:hover { transform: translateY(-2px); }
        .btn-back {
            background: rgba(255, 255, 255, .15);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, .25);
            backdrop-filter: blur(8px);
        }
        .btn-back:hover { background: rgba(255, 255, 255, .25); }

        /* ── Certificate Canvas Sheet ── */
        .cert-sheet {
            width: 100%;
            max-width: 980px;
            aspect-ratio: 1.414 / 1; /* A4 Landscape proportion */
            background: #fffdf9;
            border-radius: 12px;
            padding: 36px 42px;
            position: relative;
            box-shadow: 0 30px 80px -20px rgba(0, 0, 0, .5);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
        }

        /* Outer & Inner Ornate Borders */
        .cert-border-outer {
            position: absolute;
            inset: 14px;
            border: 3px double #b8860b;
            border-radius: 8px;
            pointer-events: none;
        }
        .cert-border-inner {
            position: absolute;
            inset: 22px;
            border: 1px solid rgba(184, 134, 11, .35);
            border-radius: 6px;
            pointer-events: none;
        }

        /* Corner Decorative Elements */
        .corner-deco {
            position: absolute;
            width: 44px;
            height: 44px;
            border: 3px solid #b8860b;
            pointer-events: none;
        }
        .corner-tl { top: 22px; left: 22px; border-right: none; border-bottom: none; }
        .corner-tr { top: 22px; right: 22px; border-left: none; border-bottom: none; }
        .corner-bl { bottom: 22px; left: 22px; border-right: none; border-top: none; }
        .corner-br { bottom: 22px; right: 22px; border-left: none; border-top: none; }

        /* Watermark Background */
        .cert-watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-family: 'Noto Serif JP', serif;
            font-size: 190px;
            font-weight: 900;
            color: rgba(184, 134, 11, .035);
            pointer-events: none;
            user-select: none;
            line-height: 1;
        }

        /* Header */
        .cert-header {
            text-align: center;
            position: relative;
            z-index: 1;
            margin-top: 10px;
        }
        .cert-org-badge {
            font-family: 'Cinzel', serif;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: .22em;
            color: #8c6d1f;
            text-transform: uppercase;
            margin-bottom: 6px;
        }
        .cert-title-ja {
            font-family: 'Noto Serif JP', serif;
            font-size: 32px;
            font-weight: 900;
            color: #1a0a0e;
            letter-spacing: .08em;
            margin-bottom: 4px;
        }
        .cert-title-id {
            font-family: 'Cinzel', serif;
            font-size: 14px;
            font-weight: 800;
            color: #c4102e;
            letter-spacing: .15em;
            text-transform: uppercase;
        }

        /* Body Section */
        .cert-body {
            text-align: center;
            position: relative;
            z-index: 1;
            margin: 16px 0;
        }
        .cert-statement {
            font-size: 13.5px;
            color: #5c4e56;
            margin-bottom: 8px;
        }
        .cert-recipient-name {
            font-family: 'Cinzel', 'Noto Serif JP', serif;
            font-size: 36px;
            font-weight: 900;
            color: #181335;
            padding: 4px 20px 8px;
            border-bottom: 2px solid #b8860b;
            display: inline-block;
            margin-bottom: 12px;
            letter-spacing: .02em;
        }
        .cert-desc {
            font-size: 13px;
            color: #4a3843;
            max-width: 680px;
            margin: 0 auto 16px;
            line-height: 1.6;
        }

        /* Score Summary Box */
        .cert-score-summary {
            display: inline-flex;
            align-items: center;
            gap: 24px;
            background: #fff8eb;
            border: 1px solid #ebd49b;
            padding: 10px 24px;
            border-radius: 10px;
            margin-bottom: 8px;
        }
        .cert-score-col {
            text-align: center;
        }
        .cert-score-col strong {
            display: block;
            font-size: 16px;
            font-weight: 900;
            color: #1a0a0e;
        }
        .cert-score-col span {
            font-size: 10.5px;
            color: #7d6527;
            font-weight: 700;
            text-transform: uppercase;
        }

        /* Footer & Signatures */
        .cert-footer {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            position: relative;
            z-index: 1;
            padding: 0 20px 10px;
        }
        .cert-footer-meta {
            text-align: left;
            font-size: 11.5px;
            color: #7a6a74;
            line-height: 1.6;
        }
        .cert-footer-meta strong {
            color: #1a0a0e;
        }

        /* Official Japanese Inkan Stamp */
        .cert-seal-wrap {
            display: flex;
            align-items: center;
            gap: 24px;
        }
        .cert-seal {
            width: 76px;
            height: 76px;
            border: 3.5px solid #c4102e;
            border-radius: 8px;
            color: #c4102e;
            display: grid;
            place-items: center;
            font-family: 'Noto Serif JP', serif;
            font-weight: 900;
            font-size: 13.5px;
            line-height: 1.15;
            text-align: center;
            transform: rotate(-5deg);
            background: rgba(196, 16, 46, .03);
            box-shadow: inset 0 0 0 1.5px #c4102e;
        }

        .cert-sig-block {
            text-align: center;
            width: 170px;
        }
        .cert-sig-line {
            width: 100%;
            height: 1px;
            background: #1a0e1c;
            margin-bottom: 6px;
        }
        .cert-sig-title {
            font-size: 11px;
            font-weight: 800;
            color: #1a0e1c;
            text-transform: uppercase;
        }
        .cert-sig-sub {
            font-size: 9.5px;
            color: #7a6a74;
        }

        /* ── Print Specific Styles ── */
        @media print {
            body {
                background: #fff !important;
                padding: 0 !important;
                display: block !important;
            }
            .cert-nav-bar { display: none !important; }
            .cert-sheet {
                box-shadow: none !important;
                width: 100% !important;
                max-width: none !important;
                aspect-ratio: auto !important;
                height: 100vh !important;
                border-radius: 0 !important;
                padding: 30px 40px !important;
                page-break-inside: avoid;
            }
            @page {
                size: A4 landscape;
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Top Floating Action Bar -->
    <div class="cert-nav-bar">
        <button type="button" class="btn-cert-nav btn-print" onclick="window.print()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="6 9 6 2 18 2 18 9"></polyline>
                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                <rect x="6" y="14" width="12" height="8"></rect>
            </svg>
            Cetak / Simpan PDF
        </button>

        <a href="{{ route('jlpt.result', $exam->id) }}" class="btn-cert-nav btn-back">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Kembali ke Hasil Ujian
        </a>
    </div>

    <!-- Certificate Sheet -->
    <div class="cert-sheet">
        <!-- Borders & Corners -->
        <div class="cert-border-outer"></div>
        <div class="cert-border-inner"></div>
        <div class="corner-deco corner-tl"></div>
        <div class="corner-deco corner-tr"></div>
        <div class="corner-deco corner-bl"></div>
        <div class="corner-deco corner-br"></div>
        <div class="cert-watermark">合格</div>

        <!-- Header -->
        <div class="cert-header">
            <div class="cert-org-badge">JP LEARN · JAPANESE ACADEMY</div>
            <h1 class="cert-title-ja">日本語能力試験 模擬試験 合格証書</h1>
            <div class="cert-title-id">Sertifikat Kelulusan Resmi Simulasi JLPT N5</div>
        </div>

        <!-- Body -->
        <div class="cert-body">
            <p class="cert-statement">Dengan ini menyatakan bahwa siswa di bawah ini:</p>
            <div class="cert-recipient-name">{{ $user->name }}</div>
            <p class="cert-desc">
                Telah berhasil menyelesaikan dan memenuhi seluruh standar penilaian kelulusan resmi dalam <strong>Simulasi Ujian Kemampuan Bahasa Jepang (JLPT) Tingkat N5</strong> yang diselenggarakan oleh platform pembelajaran digital JP Learn.
            </p>

            <!-- Score Summary Box -->
            <div class="cert-score-summary">
                <div class="cert-score-col">
                    <strong>{{ $exam->score }} / 180</strong>
                    <span>Total Skor</span>
                </div>
                <div style="width:1px; height:24px; background:#d4be88;"></div>
                <div class="cert-score-col">
                    <strong>Grade {{ $exam->grade }}</strong>
                    <span>Predikat</span>
                </div>
                <div style="width:1px; height:24px; background:#d4be88;"></div>
                <div class="cert-score-col">
                    <strong>{{ $exam->score_vocabulary }}/72</strong>
                    <span>Huruf & Kosakata</span>
                </div>
                <div style="width:1px; height:24px; background:#d4be88;"></div>
                <div class="cert-score-col">
                    <strong>{{ $exam->score_grammar }}/72</strong>
                    <span>Tata Bahasa</span>
                </div>
                <div style="width:1px; height:24px; background:#d4be88;"></div>
                <div class="cert-score-col">
                    <strong>{{ $exam->score_reading }}/36</strong>
                    <span>Bacaan (Dokkai)</span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="cert-footer">
            <div class="cert-footer-meta">
                <div>No. Sertifikat: <strong>{{ $exam->certificate_no }}</strong></div>
                <div>Tanggal Ujian: <strong>{{ $exam->created_at->format('d F Y') }}</strong></div>
                <div>Status: <strong style="color:#1b5e20;">LULUS RESMI (合格 - GOUKAKU)</strong></div>
            </div>

            <div class="cert-seal-wrap">
                <!-- Japanese Red Inkan Seal -->
                <div class="cert-seal">
                    日本<br>合格証<br>之印
                </div>

                <div class="cert-sig-block">
                    <div style="font-family:'Cinzel', serif; font-size:15px; font-weight:900; color:#c4102e; margin-bottom:4px;">
                        JP Learn Team
                    </div>
                    <div class="cert-sig-line"></div>
                    <div class="cert-sig-title">Direktur Akademik</div>
                    <div class="cert-sig-sub">JP Learn Japan Academic Center</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

