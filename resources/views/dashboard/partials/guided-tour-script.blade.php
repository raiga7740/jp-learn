<script>
/**
 * JP Learn - Interactive Onboarding Guided Tour (Embedded Engine)
 * Powered by Driver.js with Complete 14-Feature Roadmap & Resilient Fallback Engine
 */
(function () {
    'use strict';

    const STORAGE_KEY_ACTIVE = 'jp_tour_active';
    const STORAGE_KEY_STAGE  = 'jp_tour_stage';

    // Global configuration passed from Blade layout
    const config = window.JpTourConfig || {
        routes: {
            dashboard: '/dashboard',
            materials: '/materi',
            flashcards: '/flashcards',
            writing: '/writing',
            conversation: '/conversations',
            sensei: '/sensei',
            dictionary: '/dictionary',
            quizzes: '/quizzes',
            dailyChallenge: '/daily-challenge',
            speedMatch: '/speed-match',
            sentenceBuilder: '/sentence-builder',
            jlpt: '/jlpt',
            progress: '/progress',
            leaderboard: '/leaderboard',
            complete: '/onboarding/selesai',
            reset: '/onboarding/reset',
        },
        hasCompleted: true,
        csrfToken: '',
    };

    let activeDriverInstance = null;
    let isTourCompleted = false;

    /**
     * Send AJAX signal to mark onboarding as completed or dismissed in database.
     */
    function recordOnboardingComplete() {
        if (!config.routes.complete) return;

        const csrf = config.csrfToken || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        fetch(config.routes.complete, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf || '',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ _token: csrf }),
        }).catch(function (err) {
            console.warn('Failed to record tour completion:', err);
        });
    }

    /**
     * Show clean celebration toast upon tour completion.
     */
    function showCompletionToast() {
        const toast = document.createElement('div');
        toast.className = 'jp-tour-toast';
        toast.innerHTML = `
            <div class="jp-tour-toast-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>
            <div class="jp-tour-toast-content">
                <strong>Panduan Selesai!</strong>
                <span>Selamat belajar dan semoga sukses menguasai bahasa Jepang!</span>
            </div>
        `;
        document.body.appendChild(toast);
        setTimeout(function () {
            toast.classList.add('visible');
        }, 50);

        setTimeout(function () {
            toast.classList.remove('visible');
            setTimeout(function () {
                if (toast.parentNode) toast.parentNode.removeChild(toast);
            }, 300);
        }, 4500);
    }

    /**
     * Terminate the tour cleanly, clear storage, and mark DB status.
     */
    function finishTour(isCompleted) {
        sessionStorage.removeItem(STORAGE_KEY_ACTIVE);
        sessionStorage.removeItem(STORAGE_KEY_STAGE);
        try {
            localStorage.setItem('jp_tour_dismissed', '1');
        } catch (e) {}

        // Always close mobile sidebar if opened during tour
        document.body.classList.remove('sidebar-open');

        if (activeDriverInstance && typeof activeDriverInstance.destroy === 'function') {
            try {
                activeDriverInstance.destroy();
            } catch (e) {}
            activeDriverInstance = null;
        }

        // Close any fallback overlay
        closeFallbackModal();

        recordOnboardingComplete();

        if (isCompleted) {
            showCompletionToast();
        }
    }

    /**
     * Dismiss and skip the onboarding modal directly.
     */
    window.skipOnboardingTour = function () {
        try {
            localStorage.setItem('jp_tour_dismissed', '1');
        } catch (e) {}
        const modal = document.getElementById('jp-onboarding-modal-backdrop');
        if (modal) {
            modal.style.transition = 'opacity .25s ease';
            modal.style.opacity = '0';
            setTimeout(function () {
                if (modal.parentNode) modal.parentNode.removeChild(modal);
            }, 250);
        }
        finishTour(false);
    };

    /**
     * Build the 14 interactive steps corresponding to the sidebar sequence.
     */
    function getTourSteps() {
        return [
            {
                element: '[data-tour="nav-dashboard"]',
                popover: {
                    title: '1. Dashboard Belajar',
                    description: 'Pusat kendali perjalanan belajarmu. Pantau progres materi, perolehan skor XP, status streak belajar harian, dan navigasi cepat ke seluruh modul belajar bahasa Jepang.<div style="margin-top:10px;"><a href="' + (config.routes.dashboard || '/dashboard') + '" class="driver-page-link" onclick="window.skipOnboardingTour()">Buka Halaman &rarr;</a></div>',
                    side: 'right',
                    align: 'start',
                },
            },
            {
                element: '[data-tour="nav-materials"]',
                popover: {
                    title: '2. Materi Terstruktur',
                    description: 'Kurikulum pembelajaran bertingkat dari aksara dasar Hiragana & Katakana, kosakata esensial, hingga kaidah tata bahasa (Grammar) JLPT N5 lengkap dengan audio penutur asli.<div style="margin-top:10px;"><a href="' + (config.routes.materials || '/materi') + '" class="driver-page-link" onclick="window.skipOnboardingTour()">Buka Halaman &rarr;</a></div>',
                    side: 'right',
                    align: 'start',
                },
            },
            {
                element: '[data-tour="nav-flashcards"]',
                popover: {
                    title: '3. Flashcard Berkala (SRS)',
                    description: 'Perkuat daya ingat jangka panjang dengan metode ilmiah Spaced Repetition System (SRS). Kartu kosakata dijadwalkan otomatis sebelum memori otakmu melupakannya.<div style="margin-top:10px;"><a href="' + (config.routes.flashcards || '/flashcards') + '" class="driver-page-link" onclick="window.skipOnboardingTour()">Buka Halaman &rarr;</a></div>',
                    side: 'right',
                    align: 'start',
                },
            },
            {
                element: '[data-tour="nav-writing"]',
                popover: {
                    title: '4. Latihan Menulis (Stroke Order)',
                    description: 'Latih memori motorik menulis huruf Hiragana, Katakana, dan Kanji di atas kanvas digital interaktif lengkap dengan panduan urutan guratan garis yang tepat.<div style="margin-top:10px;"><a href="' + (config.routes.writing || '/writing') + '" class="driver-page-link" onclick="window.skipOnboardingTour()">Buka Halaman &rarr;</a></div>',
                    side: 'right',
                    align: 'start',
                },
            },
            {
                element: '[data-tour="nav-conversation"]',
                popover: {
                    title: '5. Latihan Percakapan',
                    description: 'Simulasi dialog percakapan bahasa Jepang sehari-hari (di restoran, bandara, stasiun, salam) dilengkapi rekaman audio native speaker untuk melatih kepekaan telinga.<div style="margin-top:10px;"><a href="' + (config.routes.conversation || '/conversations') + '" class="driver-page-link" onclick="window.skipOnboardingTour()">Buka Halaman &rarr;</a></div>',
                    side: 'right',
                    align: 'start',
                },
            },
            {
                element: '[data-tour="nav-sensei"]',
                popover: {
                    title: '6. Tanya Sensei (AI Asisten 24/7)',
                    description: 'Guru privat virtual cerdas berbasis AI yang siap menjawab pertanyaan tata bahasa, membedah partikel rumit, atau membantu latihan terjemahan kapan pun secara instan.<div style="margin-top:10px;"><a href="' + (config.routes.sensei || '/sensei') + '" class="driver-page-link" onclick="window.skipOnboardingTour()">Buka Halaman &rarr;</a></div>',
                    side: 'right',
                    align: 'start',
                },
            },
            {
                element: '[data-tour="nav-dictionary"]',
                popover: {
                    title: '7. Kamus & Kosakata',
                    description: 'Cari ribuan entri kosakata bahasa Jepang, kanji, romaji, serta arti bahasa Indonesia. Simpan kata-kata favorit ke daftar hafalan pribadimu.<div style="margin-top:10px;"><a href="' + (config.routes.dictionary || '/dictionary') + '" class="driver-page-link" onclick="window.skipOnboardingTour()">Buka Halaman &rarr;</a></div>',
                    side: 'right',
                    align: 'start',
                },
            },
            {
                element: '[data-tour="nav-quizzes"]',
                popover: {
                    title: '8. Quiz & Latihan Soal',
                    description: 'Uji dan ukur pemahaman materi yang baru dipelajari melalui latihan interaktif dengan skor instan, kunci jawaban, dan pembahasan mendalam.<div style="margin-top:10px;"><a href="' + (config.routes.quizzes || '/quizzes') + '" class="driver-page-link" onclick="window.skipOnboardingTour()">Buka Halaman &rarr;</a></div>',
                    side: 'right',
                    align: 'start',
                },
            },
            {
                element: '[data-tour="nav-daily-challenge"]',
                popover: {
                    title: '9. Tantangan Harian',
                    description: 'Selesaikan 3 misi kilat harian untuk menjaga konsistensi belajar, merawat api streak belajarmu agar tidak padam, dan klaim bonus +30 XP setiap hari.<div style="margin-top:10px;"><a href="' + (config.routes.dailyChallenge || '/daily-challenge') + '" class="driver-page-link" onclick="window.skipOnboardingTour()">Buka Halaman &rarr;</a></div>',
                    side: 'right',
                    align: 'start',
                },
            },
            {
                element: '[data-tour="nav-speed-match"]',
                popover: {
                    title: '10. Speed Match (Adu Cepat)',
                    description: 'Game adu cepat mencocokkan kosakata Jepang dan artinya berpacu dengan batas waktu. Asah refleks memori otakmu dengan cara yang seru dan menyenangkan!<div style="margin-top:10px;"><a href="' + (config.routes.speedMatch || '/speed-match') + '" class="driver-page-link" onclick="window.skipOnboardingTour()">Buka Halaman &rarr;</a></div>',
                    side: 'right',
                    align: 'start',
                },
            },
            {
                element: '[data-tour="nav-sentence-builder"]',
                popover: {
                    title: '11. Susun Kalimat Interaktif',
                    description: 'Tantangan menyusun balok kata dan partikel (wa, ga, o, ni, de) menjadi kalimat bahasa Jepang yang alami, runtut, dan sesuai tata bahasa baku.<div style="margin-top:10px;"><a href="' + (config.routes.sentenceBuilder || '/sentence-builder') + '" class="driver-page-link" onclick="window.skipOnboardingTour()">Buka Halaman &rarr;</a></div>',
                    side: 'right',
                    align: 'start',
                },
            },
            {
                element: '[data-tour="nav-jlpt"]',
                popover: {
                    title: '12. Simulasi JLPT N5',
                    description: 'Simulasi ujian sertifikasi bahasa Jepang dengan timer nyata dan format standar resmi JLPT (Kosakata, Grammar, Membaca, Mendengar) serta sertifikat kelulusan digital.<div style="margin-top:10px;"><a href="' + (config.routes.jlpt || '/jlpt') + '" class="driver-page-link" onclick="window.skipOnboardingTour()">Buka Halaman &rarr;</a></div>',
                    side: 'right',
                    align: 'start',
                },
            },
            {
                element: '[data-tour="nav-progress"]',
                popover: {
                    title: '13. Analisis Progres Belajar',
                    description: 'Pantau pertumbuhan belajarmu secara mendalam melalui heatmap aktivitas belajar harian, grafik akumulasi XP, dan statistik penguasaan kosakata.<div style="margin-top:10px;"><a href="' + (config.routes.progress || '/progress') + '" class="driver-page-link" onclick="window.skipOnboardingTour()">Buka Halaman &rarr;</a></div>',
                    side: 'right',
                    align: 'start',
                },
            },
            {
                element: '[data-tour="nav-leaderboard"]',
                popover: {
                    title: '14. Papan Peringkat (Leaderboard)',
                    description: 'Bersaing secara sehat dengan sesama pembelajar bahasa Jepang di JP Learn. Kumpulkan XP mingguan dan raih posisi podium 1, 2, atau 3!<div style="margin-top:10px;"><a href="' + (config.routes.leaderboard || '/leaderboard') + '" class="driver-page-link" onclick="window.skipOnboardingTour()">Buka Halaman &rarr;</a></div>',
                    side: 'right',
                    align: 'start',
                    doneBtnText: 'Selesai',
                },
            },
        ];
    }

    /**
     * Start or restart the interactive tour.
     */
    window.startGuidedTour = function (forceRestart) {
        const modal = document.getElementById('jp-onboarding-modal-backdrop');
        if (modal && modal.parentNode) {
            modal.parentNode.removeChild(modal);
        }

        if (forceRestart) {
            try {
                localStorage.removeItem('jp_tour_dismissed');
            } catch (e) {}
        }

        sessionStorage.setItem(STORAGE_KEY_ACTIVE, '1');
        isTourCompleted = false;

        const currentPath = window.location.pathname;
        const isDashboard = currentPath.endsWith('/dashboard') || currentPath === '/dashboard' || currentPath.endsWith('/dashboard/');

        // If restarting tour while not on dashboard, redirect to dashboard first
        if (forceRestart && !isDashboard) {
            sessionStorage.setItem(STORAGE_KEY_STAGE, 'roadmap');
            window.location.href = config.routes.dashboard;
            return;
        }

        runCompleteRoadmapTour();
    };

    /**
     * Helper to retrieve Driver.js constructor
     */
    function getDriverConstructor() {
        if (typeof window.driver === 'function') {
            return window.driver;
        }
        if (window.driver && typeof window.driver.js === 'object' && typeof window.driver.js.driver === 'function') {
            return window.driver.js.driver;
        }
        return null;
    }

    /**
     * ── Complete 14-Feature Roadmap Tour Execution ──
     */
    function runCompleteRoadmapTour() {
        const steps = getTourSteps();

        // Filter valid steps present in current DOM
        const validSteps = steps.filter(function (s) {
            return !s.element || document.querySelector(s.element);
        });

        if (validSteps.length === 0) {
            finishTour(true);
            return;
        }

        const DriverClass = getDriverConstructor();

        if (DriverClass) {
            try {
                activeDriverInstance = DriverClass({
                    showProgress: true,
                    animate: true,
                    allowClose: true,
                    overlayColor: 'rgba(12, 10, 22, 0.78)',
                    stagePadding: 6,
                    stageRadius: 12,
                    popoverClass: 'driverjs-theme',
                    nextBtnText: 'Lanjut',
                    prevBtnText: 'Kembali',
                    doneBtnText: 'Selesai',
                    progressText: '@{{current}} dari @{{total}}',
                    steps: validSteps,
                    onHighlightStarted: function (element, step, options) {
                        // Open mobile sidebar drawer if on small screens
                        if (window.innerWidth <= 960) {
                            document.body.classList.add('sidebar-open');
                        }
                        // Smoothly scroll the highlighted sidebar link into view
                        if (element && typeof element.scrollIntoView === 'function') {
                            element.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    },
                    onDeselected: function () {
                        // Keep drawer behavior consistent
                    },
                    onDestroyStarted: function () {
                        if (window.innerWidth <= 960) {
                            document.body.classList.remove('sidebar-open');
                        }
                        if (!isTourCompleted) {
                            finishTour(false);
                        }
                    },
                });

                // Set up completion interceptor
                activeDriverInstance.drive();

                // Intercept the final Done button click to trigger completion toast
                const checkDoneTimer = setInterval(function () {
                    const doneBtn = document.querySelector('.driver-popover-done-btn');
                    if (doneBtn && !doneBtn._jpAttached) {
                        doneBtn._jpAttached = true;
                        doneBtn.addEventListener('click', function () {
                            isTourCompleted = true;
                            setTimeout(function () {
                                finishTour(true);
                            }, 100);
                        });
                    }
                }, 300);

                setTimeout(function () {
                    clearInterval(checkDoneTimer);
                }, 300000); // safety 5 minutes
                return;
            } catch (err) {
                console.warn('Driver.js error, falling back to native tour modal:', err);
            }
        }

        // Resilient Fallback Engine if Driver.js is unavailable
        runNativeFallbackTour(validSteps);
    }

    /**
     * ── Resilient Native Fallback Engine ──
     * Provides high-quality onboarding even if Driver.js fails to load
     */
    let fallbackIndex = 0;
    let fallbackSteps = [];

    function runNativeFallbackTour(steps) {
        fallbackSteps = steps;
        fallbackIndex = 0;
        showFallbackStep();
    }

    function showFallbackStep() {
        closeFallbackModal();

        if (fallbackIndex >= fallbackSteps.length) {
            isTourCompleted = true;
            finishTour(true);
            return;
        }
        if (fallbackIndex < 0) fallbackIndex = 0;

        const step = fallbackSteps[fallbackIndex];
        const targetEl = step.element ? document.querySelector(step.element) : null;

        if (targetEl) {
            targetEl.classList.add('jp-tour-highlight-target');
            if (window.innerWidth <= 960) {
                document.body.classList.add('sidebar-open');
            }
            targetEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        const popover = document.createElement('div');
        popover.id = 'jp-native-tour-popover';
        popover.className = 'driver-popover driverjs-theme';

        const isLast = (fallbackIndex === fallbackSteps.length - 1);
        const nextLabel = isLast ? 'Selesai' : 'Lanjut';

        popover.innerHTML = `
            <div class="driver-popover-title">
                <span>${step.popover?.title || 'Panduan Fitur'}</span>
                <button type="button" class="driver-popover-close-btn" onclick="skipOnboardingTour()" aria-label="Tutup">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <div class="driver-popover-description">
                ${step.popover?.description || ''}
            </div>
            <div class="driver-popover-footer">
                <span class="driver-popover-progress-text">${fallbackIndex + 1} dari ${fallbackSteps.length}</span>
                <div class="driver-popover-btn-group">
                    ${fallbackIndex > 0 ? '<button type="button" class="driver-popover-prev-btn" id="jp-fb-prev">Kembali</button>' : ''}
                    <button type="button" class="driver-popover-next-btn" id="jp-fb-next">${nextLabel}</button>
                </div>
            </div>
        `;

        document.body.appendChild(popover);

        if (targetEl) {
            const rect = targetEl.getBoundingClientRect();
            const popRect = popover.getBoundingClientRect();
            let top = window.scrollY + rect.top;
            let left = window.scrollX + rect.right + 14;

            if (left + popRect.width > window.innerWidth) {
                left = window.innerWidth - popRect.width - 16;
            }
            if (left < 16) left = 16;

            popover.style.position = 'absolute';
            popover.style.top = top + 'px';
            popover.style.left = left + 'px';
            popover.style.zIndex = '100002';
        } else {
            popover.style.position = 'fixed';
            popover.style.top = '50%';
            popover.style.left = '50%';
            popover.style.transform = 'translate(-50%, -50%)';
            popover.style.zIndex = '100002';
        }

        const prevBtn = document.getElementById('jp-fb-prev');
        if (prevBtn) {
            prevBtn.onclick = function () {
                fallbackIndex--;
                showFallbackStep();
            };
        }

        const nextBtn = document.getElementById('jp-fb-next');
        if (nextBtn) {
            nextBtn.onclick = function () {
                if (isLast) {
                    isTourCompleted = true;
                    finishTour(true);
                } else {
                    fallbackIndex++;
                    showFallbackStep();
                }
            };
        }
    }

    function closeFallbackModal() {
        const existing = document.getElementById('jp-native-tour-popover');
        if (existing && existing.parentNode) {
            existing.parentNode.removeChild(existing);
        }
        document.querySelectorAll('.jp-tour-highlight-target').forEach(function (el) {
            el.classList.remove('jp-tour-highlight-target');
        });
        document.body.classList.remove('sidebar-open');
    }

    /**
     * ── Auto-Resume on Page Load ──
     */
    document.addEventListener('DOMContentLoaded', function () {
        const isTourActive = sessionStorage.getItem(STORAGE_KEY_ACTIVE) === '1';

        // Check for manual trigger URL parameter ?tour=start
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('tour') === 'start') {
            window.startGuidedTour(true);
            return;
        }

        // Auto-resume if active
        if (isTourActive) {
            const modal = document.getElementById('jp-onboarding-modal-backdrop');
            if (modal && modal.parentNode) modal.parentNode.removeChild(modal);
            setTimeout(runCompleteRoadmapTour, 400);
        }
    });

})();
</script>

