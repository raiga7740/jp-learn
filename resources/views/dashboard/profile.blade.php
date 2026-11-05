@extends('dashboard.layout')

@php($pageTitle    = 'Edit Profil')
@php($pageSubtitle = 'Kelola informasi akun dan keamanan akunmu.')

@section('styles')
    /* ── Profile page styles (NO sticky overlap) ── */
    .profile-container {
        max-width: 1040px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    /* ── Profile Header Banner ── */
    .profile-banner {
        background: var(--card);
        border: 1.5px solid var(--line);
        border-radius: var(--radius);
        padding: 28px 32px;
        box-shadow: var(--shadow-sm);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        position: relative;
        overflow: hidden;
    }
    .profile-banner::after {
        content: 'あ';
        position: absolute;
        right: 20px;
        bottom: -25px;
        font: 900 130px 'Noto Sans JP', sans-serif;
        color: rgba(196,16,46,.04);
        pointer-events: none;
        line-height: 1;
    }
    .profile-banner-user {
        display: flex;
        align-items: center;
        gap: 22px;
        min-width: 0;
    }
    .profile-banner-avatar {
        width: 82px;
        height: 82px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--red-2), var(--red));
        color: #fff;
        font-size: 34px;
        font-weight: 800;
        display: grid;
        place-items: center;
        letter-spacing: -.03em;
        flex-shrink: 0;
        box-shadow: 0 10px 24px -6px rgba(196,16,46,.5);
        border: 4px solid #fff;
        outline: 1.5px solid var(--line);
    }
    .profile-banner-info {
        min-width: 0;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .profile-banner-name {
        font-size: 22px;
        font-weight: 800;
        color: var(--ink);
        letter-spacing: -.02em;
        line-height: 1.2;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .profile-banner-email {
        font-size: 13px;
        color: var(--muted);
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .profile-banner-tags {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        margin-top: 2px;
    }
    .profile-tag {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 11.5px;
        font-weight: 700;
        letter-spacing: .02em;
    }
    .profile-tag.red {
        background: var(--red-soft);
        color: var(--red);
    }
    .profile-tag.green {
        background: var(--green-soft);
        color: var(--green);
    }

    .profile-banner-meta {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 8px;
        flex-shrink: 0;
    }
    .profile-meta-item {
        font-size: 12px;
        color: var(--muted);
        display: flex;
        align-items: center;
        gap: 7px;
    }
    .profile-meta-item strong {
        color: var(--ink);
        font-weight: 600;
    }

    /* ── Two Columns Forms Grid ── */
    .profile-grid-forms {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        align-items: stretch;
    }

    /* ── Form Card ── */
    .form-card {
        background: var(--card);
        border: 1.5px solid var(--line);
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    .form-card-head {
        padding: 20px 24px 18px;
        border-bottom: 1.5px solid var(--line);
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .form-card-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: grid;
        place-items: center;
        flex-shrink: 0;
    }
    .form-card-icon.red {
        background: var(--red-soft);
        color: var(--red);
    }
    .form-card-icon.navy {
        background: var(--blue-soft);
        color: var(--blue);
    }
    .form-card-title {
        font-size: 15.5px;
        font-weight: 800;
        color: var(--ink);
        line-height: 1.25;
        letter-spacing: -.01em;
    }
    .form-card-subtitle {
        font-size: 12px;
        color: var(--muted);
        margin-top: 2px;
        line-height: 1.4;
    }

    .form-card form {
        display: flex;
        flex-direction: column;
        flex: 1;
    }
    .form-card-body {
        padding: 24px;
        display: flex;
        flex-direction: column;
        gap: 18px;
        flex: 1;
    }

    /* ── Form Fields ── */
    .field {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .field label {
        font-size: 12.5px;
        font-weight: 600;
        color: var(--ink);
        letter-spacing: .01em;
    }
    .field label .required {
        color: var(--red);
        margin-left: 2px;
    }
    .input-wrap {
        position: relative;
    }
    .input-wrap svg {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--muted);
        pointer-events: none;
    }
    .field input[type="text"],
    .field input[type="email"],
    .field input[type="password"] {
        width: 100%;
        height: 44px;
        padding: 0 14px 0 42px;
        border: 1.5px solid var(--line);
        border-radius: 12px;
        background: var(--bg);
        color: var(--ink);
        font: 14px/1 'Inter', sans-serif;
        transition: border-color .2s, box-shadow .2s, background .2s;
        outline: none;
    }
    .field input[type="text"]:focus,
    .field input[type="email"]:focus,
    .field input[type="password"]:focus {
        border-color: var(--red-2);
        box-shadow: 0 0 0 3px rgba(232,35,79,.12);
        background: #fff;
    }
    .field input.is-error {
        border-color: var(--red);
        background: #fff8f8;
    }
    .field input:disabled,
    .field input[readonly] {
        background: #f8f6f6;
        color: #7b6d72;
        cursor: not-allowed;
    }
    .field-hint {
        font-size: 11.5px;
        color: var(--muted);
        margin-top: 2px;
        line-height: 1.35;
    }
    .field-error {
        font-size: 11.5px;
        color: var(--red);
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 4px;
        margin-top: 3px;
    }

    /* ── Password strength bar ── */
    .pwd-strength {
        display: flex;
        gap: 5px;
        margin-top: 5px;
    }
    .pwd-bar {
        height: 4px;
        flex: 1;
        border-radius: 4px;
        background: var(--line);
        transition: background .3s ease;
    }

    /* ── Card Footer ── */
    .form-card-foot {
        padding: 16px 24px;
        border-top: 1.5px solid var(--line);
        background: #fffafa;
        display: flex;
        align-items: center;
        justify-content: flex-end;
    }
    .btn-save {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 0 22px;
        height: 42px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--red-2), var(--red));
        color: #fff;
        font: 600 13px 'Inter', sans-serif;
        border: none;
        cursor: pointer;
        transition: opacity .2s, transform .15s;
        box-shadow: 0 6px 16px -4px rgba(196,16,46,.4);
    }
    .btn-save:hover { opacity: .92; transform: translateY(-1px); }
    .btn-save:active { opacity: 1; transform: none; }
    .btn-save svg { flex-shrink: 0; }

    /* ── Flash error banner ── */
    .form-alert-error {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 11px 14px;
        border-radius: 11px;
        font-size: 12.5px;
        font-weight: 500;
        background: #fff0f2;
        color: var(--red);
        border: 1px solid #f9c0cb;
    }
    .form-alert-error svg { flex-shrink: 0; margin-top: 1px; }

    /* ── Danger Zone ── */
    .danger-card {
        background: #fffdfd;
        border: 1.5px solid #f9ccd3;
        border-radius: var(--radius);
        padding: 22px 28px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        box-shadow: var(--shadow-sm);
    }
    .danger-card-info {
        flex: 1;
        min-width: 0;
    }
    .danger-card-title {
        font-size: 14.5px;
        font-weight: 700;
        color: var(--red);
        margin-bottom: 3px;
    }
    .danger-card-desc {
        font-size: 12.5px;
        color: var(--muted);
        line-height: 1.4;
    }
    .btn-outline-danger {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 0 18px;
        height: 40px;
        border-radius: 11px;
        background: #fff;
        color: var(--red);
        font: 600 12.5px 'Inter', sans-serif;
        border: 1.5px solid var(--red);
        cursor: pointer;
        transition: all .2s;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .btn-outline-danger:hover {
        background: var(--red);
        color: #fff;
        box-shadow: 0 4px 14px -4px rgba(196,16,46,.4);
    }

    /* ── Responsive ── */
    @media (max-width: 880px) {
        .profile-banner {
            flex-direction: column;
            align-items: flex-start;
            padding: 22px 20px;
            gap: 18px;
        }
        .profile-banner-meta {
            align-items: flex-start;
            border-top: 1px solid var(--line);
            padding-top: 14px;
            width: 100%;
        }
        .profile-grid-forms {
            grid-template-columns: 1fr;
            gap: 20px;
        }
    }
    @media (max-width: 600px) {
        .profile-banner-user {
            flex-direction: column;
            align-items: center;
            text-align: center;
            width: 100%;
        }
        .profile-banner-avatar {
            width: 72px;
            height: 72px;
            font-size: 28px;
        }
        .profile-banner-tags {
            justify-content: center;
        }
        .profile-banner-email {
            justify-content: center;
        }
        .form-card-head { padding: 16px 18px; }
        .form-card-body { padding: 18px; gap: 14px; }
        .form-card-foot { padding: 14px 18px; }
        .danger-card {
            flex-direction: column;
            align-items: stretch;
            padding: 18px;
            text-align: center;
        }
        .danger-card-info { margin-bottom: 8px; }
        .btn-outline-danger { justify-content: center; width: 100%; }
        .btn-save { width: 100%; justify-content: center; }
    }
@endsection

@section('content')
<div class="profile-container">

    {{-- ── Header Banner ── --}}
    <div class="profile-banner">
        <div class="profile-banner-user">
            <div class="profile-banner-avatar">{{ strtoupper(mb_substr($user->name, 0, 1)) }}</div>
            <div class="profile-banner-info">
                <div class="profile-banner-name">{{ $user->name }}</div>
                <div class="profile-banner-email">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                    {{ $user->email }}
                </div>
                <div class="profile-banner-tags">
                    <span class="profile-tag red">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                        </svg>
                        Pemula (N5)
                    </span>
                    <span class="profile-tag green">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        Akun Aktif
                    </span>
                </div>
            </div>
        </div>

        <div class="profile-banner-meta">
            <div class="profile-meta-item">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                Bergabung: <strong>{{ $user->created_at ? $user->created_at->locale('id')->translatedFormat('F Y') : 'Baru' }}</strong>
            </div>
            <div class="profile-meta-item">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                Terakhir login: <strong>Hari ini</strong>
            </div>
        </div>
    </div>

    {{-- ── Two Column Forms Grid ── --}}
    <div class="profile-grid-forms">

        {{-- ── Form 1: Informasi Pribadi ── --}}
        <div class="form-card">
            <div class="form-card-head">
                <div class="form-card-icon red">
                    <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </div>
                <div>
                    <div class="form-card-title">Informasi Pribadi</div>
                    <div class="form-card-subtitle">Perbarui nama tampilan dan alamat emailmu.</div>
                </div>
            </div>

            <form method="POST" action="{{ route('profile.update-info') }}">
                @csrf
                @method('PUT')

                <div class="form-card-body">
                    @if (isset($errors) && ($errors->has('name') || $errors->has('email')))
                    <div class="form-alert-error">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <span>Periksa kembali data yang kamu masukkan.</span>
                    </div>
                    @endif

                    <div class="field">
                        <label for="name">Nama Lengkap <span class="required">*</span></label>
                        <div class="input-wrap">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name', $user->name) }}"
                                placeholder="Masukkan nama lengkap"
                                autocomplete="name"
                                class="{{ ($errors ?? null)?->has('name') ? 'is-error' : '' }}"
                                required
                            >
                        </div>
                        @error('name')
                        <span class="field-error">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                            {{ $message }}
                        </span>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="email">Alamat Email <span class="required">*</span></label>
                        <div class="input-wrap">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email', $user->email) }}"
                                placeholder="nama@email.com"
                                autocomplete="email"
                                class="{{ ($errors ?? null)?->has('email') ? 'is-error' : '' }}"
                                required
                            >
                        </div>
                        @error('email')
                        <span class="field-error">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                            {{ $message }}
                        </span>
                        @enderror
                    </div>

                    <div class="field">
                        <label>Tingkat Pembelajaran</label>
                        <div class="input-wrap">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                            </svg>
                            <input type="text" value="Pemula (JLPT N5) — Belajar Huruf & Kosakata Dasar" disabled readonly>
                        </div>
                        <span class="field-hint">Tingkat akun disesuaikan secara otomatis berdasarkan progres belajar.</span>
                    </div>
                </div>

                <div class="form-card-foot">
                    <button type="submit" class="btn-save">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                            <polyline points="7 3 7 8 15 8"></polyline>
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        {{-- ── Form 2: Ubah Kata Sandi ── --}}
        <div class="form-card">
            <div class="form-card-head">
                <div class="form-card-icon navy">
                    <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                </div>
                <div>
                    <div class="form-card-title">Ubah Kata Sandi</div>
                    <div class="form-card-subtitle">Pastikan akun tetap aman dengan kombinasi unik.</div>
                </div>
            </div>

            <form method="POST" action="{{ route('profile.update-password') }}">
                @csrf
                @method('PUT')

                <div class="form-card-body">
                    @if (isset($errors) && ($errors->has('current_password') || $errors->has('password')))
                    <div class="form-alert-error">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <span>Ada kendala pada kata sandi yang kamu masukkan.</span>
                    </div>
                    @endif

                    <div class="field">
                        <label for="current_password">Kata Sandi Saat Ini <span class="required">*</span></label>
                        <div class="input-wrap">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                            <input
                                type="password"
                                id="current_password"
                                name="current_password"
                                placeholder="Masukkan kata sandi saat ini"
                                autocomplete="current-password"
                                class="{{ ($errors ?? null)?->has('current_password') ? 'is-error' : '' }}"
                                required
                            >
                        </div>
                        @error('current_password')
                        <span class="field-error">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                            {{ $message }}
                        </span>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="password">Kata Sandi Baru <span class="required">*</span></label>
                        <div class="input-wrap">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                            </svg>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Minimal 8 karakter"
                                autocomplete="new-password"
                                class="{{ ($errors ?? null)?->has('password') ? 'is-error' : '' }}"
                                required
                                oninput="updatePwdStrength(this.value)"
                            >
                        </div>
                        <div class="pwd-strength" aria-hidden="true">
                            <div class="pwd-bar" id="bar1"></div>
                            <div class="pwd-bar" id="bar2"></div>
                            <div class="pwd-bar" id="bar3"></div>
                            <div class="pwd-bar" id="bar4"></div>
                        </div>
                        @error('password')
                        <span class="field-error">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                            {{ $message }}
                        </span>
                        @else
                        <span class="field-hint">Gunakan kombinasi minimal 8 karakter dengan huruf dan angka.</span>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="password_confirmation">Konfirmasi Kata Sandi Baru <span class="required">*</span></label>
                        <div class="input-wrap">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                            </svg>
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                placeholder="Ulangi kata sandi baru"
                                autocomplete="new-password"
                                required
                            >
                        </div>
                    </div>
                </div>

                <div class="form-card-foot">
                    <button type="submit" class="btn-save">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                        Perbarui Kata Sandi
                    </button>
                </div>
            </form>
        </div>

    </div>{{-- .profile-grid-forms --}}

    {{-- ── Danger Zone Card ── --}}
    <div class="danger-card">
        <div class="danger-card-info">
            <div class="danger-card-title">Keluar dari Sesi Akun</div>
            <div class="danger-card-desc">Akhiri sesi aktif pada peramban ini. Kamu bisa masuk kembali kapan saja dengan email dan kata sandimu.</div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-outline-danger">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
                Keluar dari Akun
            </button>
        </form>
    </div>

</div>{{-- .profile-container --}}

<script>
    function updatePwdStrength(val) {
        const bars = [
            document.getElementById('bar1'),
            document.getElementById('bar2'),
            document.getElementById('bar3'),
            document.getElementById('bar4')
        ];
        const colors = ['#e8234f', '#f59e0b', '#3b82f6', '#10b981'];
        let score = 0;
        if (val.length >= 8) score++;
        if (/[A-Z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;

        bars.forEach(function(bar, idx) {
            if (bar) {
                bar.style.background = idx < score ? colors[score - 1] : 'var(--line)';
            }
        });
    }
</script>
@endsection
