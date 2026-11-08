@if(Auth::check() && session('is_new_registration') && !(Auth::user()->has_completed_onboarding ?? false) && request()->routeIs('dashboard'))
<div id="jp-onboarding-modal-backdrop" class="onboarding-modal-backdrop" role="dialog" aria-modal="true" aria-labelledby="onboarding-title">
    <script>
        if (localStorage.getItem('jp_tour_dismissed') === '1' || sessionStorage.getItem('jp_tour_active') === '1') {
            const m = document.getElementById('jp-onboarding-modal-backdrop');
            if (m) m.style.display = 'none';
        }
    </script>
    <div class="onboarding-modal-card">
        <!-- Close (X) button -->
        <button type="button" class="onboarding-modal-close" onclick="skipOnboardingTour()" aria-label="Tutup dan lewati">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>

        <div class="onboarding-modal-header">
            <div class="onboarding-modal-icon">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"></polygon>
                </svg>
            </div>
            <span class="onboarding-eyebrow">いらっしゃいませ · Panduan Awal</span>
            <h2 id="onboarding-title" class="onboarding-modal-title">Selamat Datang di JP Learn!</h2>
            <p class="onboarding-modal-desc">
                Yuk, ikuti tur interaktif untuk mengenal 14 fitur belajar bahasa Jepang lengkapmu agar proses belajarmu lebih terarah dan maksimal.
            </p>
        </div>

        <div class="onboarding-features-list">
            <div class="onboarding-feat-item">
                <div class="onboarding-feat-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                </div>
                <div class="onboarding-feat-text">
                    <strong>1. Kurikulum Materi Terstruktur</strong>
                    <span>Pelajari aksara Hiragana, Katakana, dan tata bahasa N5 secara bertahap.</span>
                </div>
            </div>

            <div class="onboarding-feat-item">
                <div class="onboarding-feat-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                    </svg>
                </div>
                <div class="onboarding-feat-text">
                    <strong>2. Flashcard Spaced Repetition (SRS)</strong>
                    <span>Kunci kosakata ke memori jangka panjang dengan metode pengulangan ilmiah berkala.</span>
                </div>
            </div>

            <div class="onboarding-feat-item">
                <div class="onboarding-feat-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="9"></circle>
                        <circle cx="12" cy="12" r="5"></circle>
                        <circle cx="12" cy="12" r="1.5" fill="currentColor"></circle>
                    </svg>
                </div>
                <div class="onboarding-feat-text">
                    <strong>3. Tantangan Harian & Papan Peringkat</strong>
                    <span>Kumpulkan XP setiap hari, raih posisi puncak, dan bangun rantai streak belajarmu.</span>
                </div>
            </div>
        </div>

        <div class="onboarding-modal-actions">
            <button type="button" class="btn-onboarding-start" onclick="startGuidedTour(false)">
                <span>Mulai Panduan</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </button>
            <button type="button" class="btn-onboarding-skip" onclick="skipOnboardingTour()">
                Lewati untuk Sekarang
            </button>
        </div>

        <div class="onboarding-modal-hint">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="16" x2="12" y2="12"></line>
                <line x1="12" y1="8" x2="12.01" y2="8"></line>
            </svg>
            Kamu dapat mengulang panduan ini kapan saja dari tombol Panduan di navbar atas.
        </div>
    </div>
</div>
@endif

