@extends('dashboard.layout')

@section('content')
@php
    $pageTitle = 'Tanya Sensei (AI Assistant)';
    $pageSubtitle = 'Konsultasi tata bahasa, kosakata, kanji, dan koreksi kalimat bahasa Jepang dengan asisten virtual.';
@endphp

<style>
    /* ══════════════════════════════════════════════
       TANYA SENSEI CHAT INTERFACE
    ══════════════════════════════════════════════ */
    .sensei-wrap {
        display: flex;
        flex-direction: column;
        height: calc(100vh - 170px);
        min-height: 600px;
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, .04);
        overflow: hidden;
        position: relative;
    }

    /* ── Top Header Bar ── */
    .sensei-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 22px;
        background: linear-gradient(135deg, #181926 0%, #2b1f28 100%);
        color: #fff;
        border-bottom: 1px solid rgba(255, 255, 255, .1);
        flex-shrink: 0;
    }

    .sensei-profile {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .sensei-avatar-box {
        position: relative;
        width: 44px;
        height: 44px;
        border-radius: 14px;
        background: linear-gradient(135deg, #c4102e, #ff4d6d);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        box-shadow: 0 4px 12px rgba(196, 16, 46, .35);
        flex-shrink: 0;
    }

    .sensei-online-dot {
        position: absolute;
        bottom: -2px;
        right: -2px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #10b981;
        border: 2px solid #181926;
    }

    .sensei-name-title {
        font-size: 15px;
        font-weight: 800;
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0;
        color: #fff;
    }

    .sensei-badge {
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .06em;
        padding: 2px 7px;
        border-radius: 6px;
        background: rgba(255, 255, 255, .15);
        color: #ffcad4;
    }

    .sensei-status-text {
        font-size: 11.5px;
        color: rgba(255, 255, 255, .65);
        margin-top: 1px;
    }

    .sensei-header-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-header-action {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 12px;
        border-radius: 10px;
        background: rgba(255, 255, 255, .1);
        border: 1px solid rgba(255, 255, 255, .15);
        color: #fff;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: .2s ease;
    }

    .btn-header-action:hover {
        background: rgba(255, 255, 255, .2);
        transform: translateY(-1px);
    }

    /* ── Chat Messages Body ── */
    .sensei-messages-body {
        flex: 1;
        overflow-y: auto;
        padding: 24px;
        display: flex;
        flex-direction: column;
        gap: 18px;
        background: #faf7f5;
        scroll-behavior: smooth;
    }

    /* ── Empty Welcome State ── */
    .sensei-welcome-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 18px;
        padding: 24px;
        text-align: center;
        max-width: 680px;
        margin: 10px auto 0 auto;
        box-shadow: 0 2px 12px rgba(0, 0, 0, .03);
    }

    .welcome-icon-box {
        width: 56px;
        height: 56px;
        border-radius: 18px;
        background: #fff1f2;
        color: var(--red);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 14px;
    }

    .welcome-title {
        font-size: 18px;
        font-weight: 800;
        color: var(--text);
        margin: 0 0 6px 0;
    }

    .welcome-desc {
        font-size: 13.5px;
        color: var(--muted);
        line-height: 1.6;
        margin: 0 0 20px 0;
    }

    .suggestions-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        text-align: left;
    }

    .btn-suggestion {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        border-radius: 12px;
        background: #fffafa;
        border: 1px solid #ffe3e8;
        color: var(--text);
        font-size: 12.5px;
        font-weight: 600;
        cursor: pointer;
        transition: .2s ease;
    }

    .btn-suggestion:hover {
        background: #ffeef2;
        border-color: #ffccd5;
        transform: translateY(-1px);
        color: var(--red);
    }

    .btn-suggestion svg {
        color: var(--red);
        flex-shrink: 0;
    }

    /* ── Chat Message Rows ── */
    .chat-row {
        display: flex;
        gap: 12px;
        max-width: 85%;
        animation: chatFadeIn .25s ease-out;
    }

    @keyframes chatFadeIn {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .chat-row.user {
        align-self: flex-end;
        flex-direction: row-reverse;
    }

    .chat-row.sensei {
        align-self: flex-start;
    }

    .msg-avatar {
        width: 36px;
        height: 36px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 14px;
        font-weight: 800;
    }

    .chat-row.user .msg-avatar {
        background: linear-gradient(135deg, #2b1f28, #181926);
        color: #fff;
    }

    .chat-row.sensei .msg-avatar {
        background: linear-gradient(135deg, #c4102e, #ff4d6d);
        color: #fff;
        box-shadow: 0 2px 8px rgba(196, 16, 46, .25);
    }

    .msg-bubble-wrap {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .msg-meta {
        font-size: 11px;
        font-weight: 700;
        color: var(--muted);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .chat-row.user .msg-meta {
        justify-content: flex-end;
    }

    .msg-engine-tag {
        font-size: 9.5px;
        padding: 2px 7px;
        border-radius: 5px;
        text-transform: uppercase;
        font-weight: 800;
        letter-spacing: .03em;
        background: #f1eae6;
        color: #8c7676;
    }

    .msg-engine-tag.gemini {
        background: #e6fcf5;
        color: #087f5b;
        border: 1px solid #b2f2bb;
    }

    .msg-engine-tag.builtin {
        background: #f8f0fc;
        color: #7950f2;
        border: 1px solid #eebefa;
    }

    .msg-bubble {
        padding: 14px 18px;
        border-radius: 18px;
        font-size: 14px;
        line-height: 1.65;
        word-break: break-word;
    }

    .chat-row.user .msg-bubble {
        background: #2b1f28;
        color: #fff;
        border-top-right-radius: 4px;
    }

    .chat-row.sensei .msg-bubble {
        background: #fff;
        color: var(--text);
        border: 1px solid var(--border);
        border-top-left-radius: 4px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .03);
    }

    /* Markdown inside bubble */
    .msg-bubble h1, .msg-bubble h2, .msg-bubble h3 {
        margin: 8px 0 6px 0;
        font-size: 15px;
        font-weight: 800;
        color: var(--red);
    }
    .msg-bubble p {
        margin: 0 0 8px 0;
    }
    .msg-bubble p:last-child {
        margin-bottom: 0;
    }
    .msg-bubble ul, .msg-bubble ol {
        margin: 6px 0 10px 20px;
        padding: 0;
    }
    .msg-bubble li {
        margin-bottom: 4px;
    }
    .msg-bubble blockquote {
        margin: 8px 0;
        padding: 8px 14px;
        background: #fff8f8;
        border-left: 3px solid var(--red);
        border-radius: 4px;
        font-size: 13.5px;
    }
    .msg-bubble code {
        background: #f3ece8;
        padding: 2px 6px;
        border-radius: 5px;
        font-size: 12.5px;
        font-family: inherit;
        font-weight: 700;
        color: #c4102e;
    }

    /* Action bar on Sensei message */
    .msg-actions {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 4px;
    }

    .btn-msg-tool {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 8px;
        background: #fff;
        border: 1px solid var(--border);
        color: var(--muted);
        font-size: 11.5px;
        font-weight: 600;
        cursor: pointer;
        transition: .15s ease;
    }

    .btn-msg-tool:hover {
        background: #fff1f2;
        border-color: #ffccd5;
        color: var(--red);
    }

    .btn-msg-tool.speaking {
        background: var(--red);
        color: #fff;
        border-color: var(--red);
    }

    /* ── Typing Indicator ── */
    .typing-indicator {
        display: none;
        align-items: center;
        gap: 4px;
        padding: 12px 18px;
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 18px;
        border-top-left-radius: 4px;
        width: fit-content;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .03);
    }

    .typing-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: var(--red);
        animation: typingBounce 1.2s infinite ease-in-out;
    }

    .typing-dot:nth-child(2) { animation-delay: .2s; }
    .typing-dot:nth-child(3) { animation-delay: .4s; }

    @keyframes typingBounce {
        0%, 80%, 100% { transform: translateY(0); opacity: .4; }
        40% { transform: translateY(-5px); opacity: 1; }
    }

    /* ── Bottom Input Area ── */
    .sensei-input-box {
        padding: 14px 20px;
        background: #fff;
        border-top: 1px solid var(--border);
        display: flex;
        flex-direction: column;
        gap: 10px;
        flex-shrink: 0;
    }

    .quick-chips-row {
        display: flex;
        align-items: center;
        gap: 8px;
        overflow-x: auto;
        padding-bottom: 4px;
        scrollbar-width: none;
    }

    .quick-chips-row::-webkit-scrollbar { display: none; }

    .chip-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 999px;
        background: #fbf5f7;
        border: 1px solid #f2dfe4;
        color: var(--text);
        font-size: 11.5px;
        font-weight: 700;
        white-space: nowrap;
        cursor: pointer;
        transition: .15s ease;
    }

    .chip-item:hover {
        background: #ffe3e8;
        border-color: #ffb8c5;
        color: var(--red);
    }

    .input-form-row {
        display: flex;
        align-items: flex-end;
        gap: 10px;
    }

    .chat-textarea {
        flex: 1;
        min-height: 44px;
        max-height: 120px;
        padding: 11px 16px;
        border-radius: 14px;
        border: 1.5px solid var(--border);
        background: #faf7f5;
        font-size: 14px;
        font-family: inherit;
        color: var(--text);
        resize: none;
        outline: none;
        line-height: 1.4;
        transition: .2s ease;
    }

    .chat-textarea:focus {
        border-color: var(--red);
        background: #fff;
        box-shadow: 0 0 0 3px rgba(196, 16, 46, .1);
    }

    .btn-send-chat {
        width: 44px;
        height: 44px;
        border-radius: 13px;
        background: var(--red);
        color: #fff;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: .2s ease;
        flex-shrink: 0;
    }

    .btn-send-chat:hover:not(:disabled) {
        background: var(--red-hover, #a00d25);
        transform: scale(1.04);
    }

    .btn-send-chat:disabled {
        opacity: .5;
        cursor: not-allowed;
    }

    /* ── Modal Settings ── */
    .sensei-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, .5);
        backdrop-filter: blur(4px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        padding: 20px;
    }

    .sensei-modal-overlay.show {
        display: flex;
    }

    .sensei-modal-card {
        background: #fff;
        border-radius: 20px;
        width: 100%;
        max-width: 480px;
        padding: 26px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, .2);
        animation: modalScale .2s ease-out;
    }

    @keyframes modalScale {
        from { transform: scale(.95); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    .modal-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }

    .modal-head h3 {
        margin: 0;
        font-size: 17px;
        font-weight: 800;
        color: var(--text);
    }

    .btn-modal-close {
        background: none;
        border: none;
        color: var(--muted);
        cursor: pointer;
        padding: 4px;
        border-radius: 8px;
    }

    .btn-modal-close:hover {
        background: #f1eae6;
        color: var(--text);
    }

    @media (max-width: 768px) {
        .sensei-wrap {
            height: calc(100vh - 140px);
            border-radius: 14px;
        }
        .suggestions-grid {
            grid-template-columns: 1fr;
        }
        .chat-row {
            max-width: 95%;
        }
        .btn-header-action span {
            display: none;
        }
    }
</style>

<div class="content-body" style="padding-bottom: 20px;">
    <!-- Chat Container -->
    <div class="sensei-wrap">
        <!-- 1. Header Bar -->
        <div class="sensei-header">
            <div class="sensei-profile">
                <div class="sensei-avatar-box">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                        <!-- Torii Gate / Sensei Icon -->
                        <path d="M4 6h16"></path>
                        <path d="M2 9h20"></path>
                        <path d="M6 6v14"></path>
                        <path d="M18 6v14"></path>
                    </svg>
                    <span class="sensei-online-dot"></span>
                </div>
                <div>
                    <h2 class="sensei-name-title">
                        <span>Kenji Sensei</span>
                        <span class="sensei-badge">AI Assistant</span>
                    </h2>
                    <div class="sensei-status-text">Online &bull; Siap membimbing belajar bahasa Jepang</div>
                </div>
            </div>

            <div class="sensei-header-actions">
                <button type="button" class="btn-header-action" id="btnOpenSettings" title="Pengaturan Model / API Key">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="3"></circle>
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                    </svg>
                    <span>Pengaturan API</span>
                </button>
                <button type="button" class="btn-header-action" id="btnClearChat" title="Hapus riwayat percakapan">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                    <span>Bersihkan</span>
                </button>
            </div>
        </div>

        <!-- 2. Chat Message Stream -->
        <div class="sensei-messages-body" id="messagesContainer">
            <!-- Welcome State Card (Ditampilkan saat belum ada riwayat chat) -->
            <div class="sensei-welcome-card" id="welcomeCard">
                <div class="welcome-icon-box">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                </div>
                <h3 class="welcome-title">Konnichiwa, {{ $user->name ?? 'Teman Belajar' }}!</h3>
                <p class="welcome-desc">
                    Selamat datang di ruang belajar <strong>Tanya Sensei</strong>. Tanyakan apa saja seputar tata bahasa, partikel, kanji, kosakata, atau minta Kenji Sensei mengoreksi kalimat bahasa Jepangmu!
                </p>

                <div class="suggestions-grid">
                    @foreach($promptSuggestions as $suggestion)
                    <button type="button" class="btn-suggestion" data-prompt="{{ $suggestion['prompt'] }}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg>
                        <span>{{ $suggestion['title'] }}</span>
                    </button>
                    @endforeach
                </div>
            </div>

            <!-- Typing indicator -->
            <div class="chat-row sensei" id="typingRow" style="display:none;">
                <div class="msg-avatar">KS</div>
                <div class="typing-indicator" style="display:flex;">
                    <span class="typing-dot"></span>
                    <span class="typing-dot"></span>
                    <span class="typing-dot"></span>
                </div>
            </div>
        </div>

        <!-- 3. Bottom Input Bar -->
        <div class="sensei-input-box">
            <!-- Quick Chips -->
            <div class="quick-chips-row">
                <button type="button" class="chip-item" data-fill="Apa perbedaan partikel は (wa) dan が (ga)?">
                    Beda は & が
                </button>
                <button type="button" class="chip-item" data-fill="Tolong koreksi kalimat bahasa Jepang saya: ">
                    Koreksi Kalimat
                </button>
                <button type="button" class="chip-item" data-fill="Bagaimana rumus mengubah kata kerja ke bentuk ~TE (~て)?">
                    Bentuk ~TE
                </button>
                <button type="button" class="chip-item" data-fill="Bagaimana cara membaca jam dan menit dalam bahasa Jepang?">
                    Membaca Waktu / Jam
                </button>
                <button type="button" class="chip-item" data-fill="Berikan 5 kosakata penting JLPT N5 beserta contoh kalimatnya!">
                    Kosakata JLPT N5
                </button>
            </div>

            <!-- Text Input Form -->
            <form id="chatForm" class="input-form-row">
                <textarea
                    id="chatInput"
                    class="chat-textarea"
                    placeholder="Ketik pertanyaan atau kalimat bahasa Jepangmu di sini... (Enter untuk kirim, Shift+Enter untuk baris baru)"
                    rows="1"></textarea>
                <button type="submit" class="btn-send-chat" id="btnSend" title="Kirim Pertanyaan">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="22" y1="2" x2="11" y2="13"></line>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════
     MODAL PENGATURAN API KEY (GEMINI)
══════════════════════════════════════════════ -->
<div class="sensei-modal-overlay" id="settingsModal">
    <div class="sensei-modal-card">
        <div class="modal-head">
            <h3>Pengaturan AI Model</h3>
            <button type="button" class="btn-modal-close" id="btnCloseModal">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <p style="font-size:13px; color:var(--muted); line-height:1.5; margin:0 0 16px 0;">
            Secara default, Kenji Sensei sudah dibekali <strong>Smart Built-in Engine</strong> yang aktif otomatis. Untuk jawaban yang jauh lebih mendalam, kamu bisa memasukkan <strong>Google Gemini API Key gratis</strong>.
        </p>

        <div style="margin-bottom:16px;">
            <label style="display:block; font-size:12px; font-weight:800; color:var(--text); margin-bottom:6px;">
                Google Gemini API Key (Opsional):
            </label>
            <input
                type="password"
                id="apiKeyInput"
                placeholder="AIzaSy..."
                style="width:100%; padding:10px 14px; border-radius:10px; border:1px solid var(--border); font-size:13px; outline:none; font-family:monospace; box-sizing:border-box;">
            <span style="display:block; font-size:11.5px; color:var(--muted); margin-top:5px;">
                Kunci ini disimpan aman di browser lokalmu (localStorage) dan tidak dibagikan ke orang lain. Dapatkan gratis di <a href="https://aistudio.google.com/" target="_blank" style="color:var(--red); font-weight:700; text-decoration:underline;">Google AI Studio</a>.
            </span>
        </div>

        <div id="apiKeyTestStatus" style="display:none; padding:10px 14px; border-radius:10px; font-size:12.5px; margin-bottom:14px; line-height:1.4;"></div>

        <div style="display:flex; justify-content:space-between; align-items:center; gap:8px;">
            <button type="button" class="btn-header-action" id="btnTestApiKey" style="background:#e6fcf5; color:#087f5b; border-color:#b2f2bb;">
                Uji Koneksi API
            </button>
            <div style="display:flex; gap:8px;">
                <button type="button" class="btn-header-action" id="btnClearApiKey" style="background:#f1eae6; color:var(--text); border-color:#e2d9d4;">
                    Hapus
                </button>
                <button type="button" class="btn-header-action" id="btnSaveApiKey" style="background:var(--red); color:#fff; border-color:var(--red);">
                    Simpan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    'use strict';

    const messagesContainer = document.getElementById('messagesContainer');
    const welcomeCard = document.getElementById('welcomeCard');
    const typingRow = document.getElementById('typingRow');
    const chatForm = document.getElementById('chatForm');
    const chatInput = document.getElementById('chatInput');
    const btnSend = document.getElementById('btnSend');
    const btnClearChat = document.getElementById('btnClearChat');
    const btnOpenSettings = document.getElementById('btnOpenSettings');
    const btnCloseModal = document.getElementById('btnCloseModal');
    const settingsModal = document.getElementById('settingsModal');
    const apiKeyInput = document.getElementById('apiKeyInput');
    const btnSaveApiKey = document.getElementById('btnSaveApiKey');
    const btnClearApiKey = document.getElementById('btnClearApiKey');
    const btnTestApiKey = document.getElementById('btnTestApiKey');
    const apiKeyTestStatus = document.getElementById('apiKeyTestStatus');

    const STORAGE_CHAT_KEY = 'jplearn_sensei_history_v1';
    const STORAGE_API_KEY = 'jplearn_gemini_api_key';

    let conversationHistory = []; // { role: 'user' | 'sensei', text: string, engine?: string, snippets?: string[] }

    // ── Load Saved API Key & History ──
    function init() {
        // Load API Key
        const savedKey = localStorage.getItem(STORAGE_API_KEY) || '';
        if (apiKeyInput) apiKeyInput.value = savedKey;

        // Load Chat History
        try {
            const raw = localStorage.getItem(STORAGE_CHAT_KEY);
            if (raw) {
                conversationHistory = JSON.parse(raw);
            }
        } catch (e) {
            conversationHistory = [];
        }

        renderMessages();
    }

    // ── Render Chat Stream ──
    function renderMessages() {
        // Clear all message bubbles except welcomeCard and typingRow
        const existingBubbles = messagesContainer.querySelectorAll('.chat-bubble-rendered');
        existingBubbles.forEach(el => el.remove());

        if (conversationHistory.length === 0) {
            if (welcomeCard) welcomeCard.style.display = 'block';
        } else {
            if (welcomeCard) welcomeCard.style.display = 'none';

            conversationHistory.forEach((msg, idx) => {
                const row = createMessageElement(msg, idx);
                messagesContainer.insertBefore(row, typingRow);
            });

            scrollToBottom();
        }
    }

    // ── Create Message DOM Element ──
    function createMessageElement(msg, idx) {
        const row = document.createElement('div');
        row.className = `chat-row ${msg.role} chat-bubble-rendered`;

        const avatar = document.createElement('div');
        avatar.className = 'msg-avatar';
        avatar.textContent = msg.role === 'user' ? 'YOU' : 'KS';

        const wrap = document.createElement('div');
        wrap.className = 'msg-bubble-wrap';

        const meta = document.createElement('div');
        meta.className = 'msg-meta';

        if (msg.role === 'sensei') {
            const nameSpan = document.createElement('span');
            nameSpan.textContent = 'Kenji Sensei';
            meta.appendChild(nameSpan);

            const engineTag = document.createElement('span');
            engineTag.className = `msg-engine-tag ${msg.engine === 'gemini' ? 'gemini' : 'builtin'}`;
            engineTag.textContent = msg.engine === 'gemini' ? 'AI Gemini' : 'Sensei Engine';
            meta.appendChild(engineTag);
        } else {
            const youSpan = document.createElement('span');
            youSpan.textContent = 'Kamu';
            meta.appendChild(youSpan);
        }

        const bubble = document.createElement('div');
        bubble.className = 'msg-bubble';
        bubble.innerHTML = formatMarkdown(msg.text);

        wrap.appendChild(meta);
        wrap.appendChild(bubble);

        // Action tools for Sensei replies (Audio TTS & Copy)
        if (msg.role === 'sensei') {
            const actions = document.createElement('div');
            actions.className = 'msg-actions';

            // Tombol Audio Pelafalan (TTS)
            const btnSpeak = document.createElement('button');
            btnSpeak.type = 'button';
            btnSpeak.className = 'btn-msg-tool';
            btnSpeak.innerHTML = `
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
                    <path d="M15.54 8.46a5 5 0 0 1 0 7.07"></path>
                    <path d="M19.07 4.93a10 10 0 0 1 0 14.14"></path>
                </svg>
                <span>Dengarkan</span>
            `;
            btnSpeak.addEventListener('click', () => {
                speakJapaneseText(msg.text, btnSpeak);
            });
            actions.appendChild(btnSpeak);

            // Tombol Salin Teks
            const btnCopy = document.createElement('button');
            btnCopy.type = 'button';
            btnCopy.className = 'btn-msg-tool';
            btnCopy.innerHTML = `
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                </svg>
                <span>Salin</span>
            `;
            btnCopy.addEventListener('click', () => {
                navigator.clipboard.writeText(msg.text).then(() => {
                    const original = btnCopy.innerHTML;
                    btnCopy.innerHTML = `<span>Tersalin!</span>`;
                    setTimeout(() => { btnCopy.innerHTML = original; }, 1500);
                });
            });
            actions.appendChild(btnCopy);

            wrap.appendChild(actions);
        }

        row.appendChild(avatar);
        row.appendChild(wrap);

        return row;
    }

    // ── Simple Markdown Formatter ──
    function formatMarkdown(text) {
        if (!text) return '';

        let escaped = text
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');

        // Headers
        escaped = escaped.replace(/^### (.*$)/gim, '<h3>$1</h3>');
        escaped = escaped.replace(/^## (.*$)/gim, '<h2>$1</h2>');
        escaped = escaped.replace(/^# (.*$)/gim, '<h1>$1</h1>');

        // Bold & Italic
        escaped = escaped.replace(/\*\*(.*?)\*\*/gim, '<strong>$1</strong>');
        escaped = escaped.replace(/\*(.*?)\*/gim, '<em>$1</em>');

        // Blockquotes
        escaped = escaped.replace(/^\> (.*$)/gim, '<blockquote>$1</blockquote>');

        // Inline Code
        escaped = escaped.replace(/`([^`]+)`/gim, '<code>$1</code>');

        // Bullet lists
        escaped = escaped.replace(/^\- (.*$)/gim, '<li>$1</li>');
        escaped = escaped.replace(/(<li>.*<\/li>)/s, '<ul>$1</ul>');

        // Line breaks
        escaped = escaped.replace(/\n/g, '<br>');

        return escaped;
    }

    // ── Text-To-Speech (Web Speech API) ──
    function speakJapaneseText(text, btnElement) {
        if (!('speechSynthesis' in window)) {
            alert('Browser kamu belum mendukung audio pelafalan Web Speech API.');
            return;
        }

        window.speechSynthesis.cancel();

        // Cari penggalan huruf Jepang jika ada, jika tidak baca seluruh teks
        const jpMatches = text.match(/[\u3040-\u309F\u30A0-\u30FF\u4E00-\u9FAF\u3000-\u303F]+/g);
        const textToSpeak = jpMatches ? jpMatches.join('。 ') : text;

        const utterance = new SpeechSynthesisUtterance(textToSpeak);
        utterance.lang = 'ja-JP';
        utterance.rate = 0.88; // Sedikit lebih lambat agar jelas untuk pemula

        // Coba temukan voice native ja-JP
        const voices = window.speechSynthesis.getVoices();
        const jaVoice = voices.find(v => v.lang === 'ja-JP' || v.lang.startsWith('ja'));
        if (jaVoice) {
            utterance.voice = jaVoice;
        }

        if (btnElement) btnElement.classList.add('speaking');

        utterance.onend = () => {
            if (btnElement) btnElement.classList.remove('speaking');
        };

        utterance.onerror = () => {
            if (btnElement) btnElement.classList.remove('speaking');
        };

        window.speechSynthesis.speak(utterance);
    }

    function scrollToBottom() {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // ── Handle Send Message ──
    async function sendMessage(text) {
        const query = text.trim();
        if (!query) return;

        // Hide welcome card immediately
        if (welcomeCard) welcomeCard.style.display = 'none';

        // 1. Tambah Pesan User ke Riwayat
        const userMsg = { role: 'user', text: query };
        conversationHistory.push(userMsg);

        const userRow = createMessageElement(userMsg, conversationHistory.length - 1);
        messagesContainer.insertBefore(userRow, typingRow);

        chatInput.value = '';
        chatInput.style.height = '44px';
        btnSend.disabled = true;
        scrollToBottom();

        // Show typing indicator
        typingRow.style.display = 'flex';
        scrollToBottom();

        const userKey = localStorage.getItem(STORAGE_API_KEY) || '';

        try {
            const response = await fetch("{{ route('sensei.chat') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    message: query,
                    api_key: userKey,
                    history: conversationHistory.slice(-8)
                })
            });

            const data = await response.json();

            // Sembunyikan typing indicator
            typingRow.style.display = 'none';

            if (data.status === 'success') {
                const senseiMsg = {
                    role: 'sensei',
                    text: data.reply,
                    engine: data.engine,
                    snippets: data.japanese_snippets || []
                };
                conversationHistory.push(senseiMsg);

                const senseiRow = createMessageElement(senseiMsg, conversationHistory.length - 1);
                messagesContainer.insertBefore(senseiRow, typingRow);

                // Simpan ke LocalStorage
                localStorage.setItem(STORAGE_CHAT_KEY, JSON.stringify(conversationHistory));

                // Beri Gamification XP untuk bertanya ke Sensei
                if (typeof window.reportActivity === 'function') {
                    window.reportActivity('tanya_sensei_ask');
                }
            } else {
                throw new Error('Gagal mendapatkan respon dari server.');
            }
        } catch (err) {
            typingRow.style.display = 'none';

            const errorMsg = {
                role: 'sensei',
                text: 'Gomen ne! (ごめんなさい！ Maaf!) Terjadi kendala saat menghubungkan ke server. Kenji Sensei tetap siap membimbingmu, silakan coba kirim kembali pertanyaanmu ya!',
                engine: 'builtin'
            };
            conversationHistory.push(errorMsg);

            const errRow = createMessageElement(errorMsg, conversationHistory.length - 1);
            messagesContainer.insertBefore(errRow, typingRow);
        } finally {
            btnSend.disabled = false;
            scrollToBottom();
            chatInput.focus();
        }
    }

    // ── Form Submissions & Textarea Keydown ──
    chatForm.addEventListener('submit', (e) => {
        e.preventDefault();
        sendMessage(chatInput.value);
    });

    chatInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage(chatInput.value);
        }
    });

    // Auto resize textarea
    chatInput.addEventListener('input', () => {
        chatInput.style.height = 'auto';
        chatInput.style.height = Math.min(chatInput.scrollHeight, 120) + 'px';
    });

    // ── Suggestion Card & Quick Chips Click Handlers ──
    document.querySelectorAll('.btn-suggestion').forEach(btn => {
        btn.addEventListener('click', () => {
            const prompt = btn.dataset.prompt;
            sendMessage(prompt);
        });
    });

    document.querySelectorAll('.chip-item').forEach(chip => {
        chip.addEventListener('click', () => {
            const fillText = chip.dataset.fill;
            chatInput.value = fillText;
            chatInput.focus();
            chatInput.style.height = 'auto';
            chatInput.style.height = Math.min(chatInput.scrollHeight, 120) + 'px';
        });
    });

    // ── Clear Chat History ──
    btnClearChat.addEventListener('click', () => {
        if (confirm('Yakin ingin membersihkan riwayat percakapan dengan Kenji Sensei?')) {
            conversationHistory = [];
            localStorage.removeItem(STORAGE_CHAT_KEY);
            renderMessages();
        }
    });

    // ── Settings Modal Handlers ──
    btnOpenSettings.addEventListener('click', () => {
        settingsModal.classList.add('show');
    });

    btnCloseModal.addEventListener('click', () => {
        settingsModal.classList.remove('show');
    });

    settingsModal.addEventListener('click', (e) => {
        if (e.target === settingsModal) {
            settingsModal.classList.remove('show');
        }
    });

    btnSaveApiKey.addEventListener('click', () => {
        const key = apiKeyInput.value.trim();
        localStorage.setItem(STORAGE_API_KEY, key);
        settingsModal.classList.remove('show');
        alert(key ? 'Google Gemini API Key berhasil disimpan!' : 'Pengaturan disimpan: menggunakan Smart Built-in Engine.');
    });

    btnTestApiKey.addEventListener('click', async () => {
        const key = apiKeyInput.value.trim();
        if (!key) {
            apiKeyTestStatus.style.display = 'block';
            apiKeyTestStatus.style.background = '#fff5f5';
            apiKeyTestStatus.style.color = '#c92a2a';
            apiKeyTestStatus.style.border = '1px solid #ffc9c9';
            apiKeyTestStatus.textContent = 'Silakan masukkan API Key terlebih dahulu sebelum menguji.';
            return;
        }

        btnTestApiKey.disabled = true;
        btnTestApiKey.textContent = 'Menguji...';
        apiKeyTestStatus.style.display = 'block';
        apiKeyTestStatus.style.background = '#f8f9fa';
        apiKeyTestStatus.style.color = '#495057';
        apiKeyTestStatus.style.border = '1px solid #dee2e6';
        apiKeyTestStatus.textContent = 'Menghubungkan ke Google Gemini API...';

        try {
            const res = await fetch("{{ route('sensei.test-key') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ api_key: key })
            });
            const data = await res.json();

            if (data.valid) {
                apiKeyTestStatus.style.background = '#e6fcf5';
                apiKeyTestStatus.style.color = '#087f5b';
                apiKeyTestStatus.style.border = '1px solid #b2f2bb';
                apiKeyTestStatus.innerHTML = `<strong>Koneksi Berhasil!</strong> ${data.message}`;
            } else {
                apiKeyTestStatus.style.background = '#fff5f5';
                apiKeyTestStatus.style.color = '#c92a2a';
                apiKeyTestStatus.style.border = '1px solid #ffc9c9';
                apiKeyTestStatus.innerHTML = `<strong>Koneksi Gagal:</strong> ${data.message}`;
            }
        } catch (e) {
            apiKeyTestStatus.style.background = '#fff5f5';
            apiKeyTestStatus.style.color = '#c92a2a';
            apiKeyTestStatus.style.border = '1px solid #ffc9c9';
            apiKeyTestStatus.textContent = 'Gagal menghubungi server lokal: ' + e.message;
        } finally {
            btnTestApiKey.disabled = false;
            btnTestApiKey.textContent = 'Uji Koneksi API';
        }
    });

    btnClearApiKey.addEventListener('click', () => {
        apiKeyInput.value = '';
        localStorage.removeItem(STORAGE_API_KEY);
        alert('API Key berhasil dihapus. Kenji Sensei akan menggunakan Smart Built-in Engine.');
    });

    // ── Run Init ──
    init();
})();
</script>
@endsection

