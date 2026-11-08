<style>
/* ── JLPT Exam Confirmation Modal ── */
.exam-modal-overlay {
    position: fixed;
    inset: 0;
    z-index: 99999;
    background: rgba(14, 10, 26, 0.78);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
    transition: opacity .25s ease, visibility .25s ease;
}

.exam-modal-overlay.show {
    opacity: 1;
    visibility: visible;
    pointer-events: auto;
}

.exam-modal-card {
    position: relative;
    width: 100%;
    max-width: 480px;
    background: linear-gradient(155deg, #1a1438 0%, #281938 100%);
    border-radius: 24px;
    padding: 32px 28px 28px;
    color: #fff;
    box-shadow: 0 28px 65px -15px rgba(0, 0, 0, .65), 0 0 0 1px rgba(255, 255, 255, .1);
    transform: scale(0.92) translateY(16px);
    transition: transform .28s cubic-bezier(0.34, 1.56, 0.64, 1);
    overflow: hidden;
}

.exam-modal-overlay.show .exam-modal-card {
    transform: scale(1) translateY(0);
}

/* Ambient glow & watermark */
.exam-modal-glow {
    position: absolute;
    top: -60px;
    left: 50%;
    transform: translateX(-50%);
    width: 260px;
    height: 180px;
    background: radial-gradient(circle, rgba(232, 35, 79, .35) 0%, rgba(232, 35, 79, 0) 70%);
    pointer-events: none;
    z-index: 0;
}

.exam-modal-kanji-bg {
    position: absolute;
    right: 18px;
    bottom: -16px;
    font-family: 'Noto Sans JP', sans-serif;
    font-size: 110px;
    font-weight: 900;
    color: rgba(255, 255, 255, .03);
    pointer-events: none;
    user-select: none;
    line-height: 1;
    z-index: 0;
}

/* Close button */
.exam-modal-close {
    position: absolute;
    top: 18px;
    right: 18px;
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: rgba(255, 255, 255, .08);
    border: 1px solid rgba(255, 255, 255, .12);
    color: rgba(255, 255, 255, .7);
    display: grid;
    place-items: center;
    cursor: pointer;
    transition: .2s;
    z-index: 2;
}

.exam-modal-close:hover {
    background: rgba(255, 255, 255, .18);
    color: #fff;
    transform: rotate(90deg);
}

/* Icon Wrap with pulse effect */
.exam-modal-icon-wrap {
    position: relative;
    width: 68px;
    height: 68px;
    margin: 0 auto 18px;
    display: grid;
    place-items: center;
    z-index: 1;
}

.exam-modal-icon-pulse {
    position: absolute;
    inset: -6px;
    border-radius: 50%;
    background: rgba(232, 35, 79, .25);
    animation: examPulseGlow 2.4s infinite ease-in-out;
}

@keyframes examPulseGlow {
    0%, 100% { transform: scale(0.95); opacity: 0.5; }
    50% { transform: scale(1.15); opacity: 0.15; }
}

.exam-modal-icon {
    width: 64px;
    height: 64px;
    border-radius: 20px;
    background: linear-gradient(135deg, rgba(232, 35, 79, .35), rgba(196, 16, 46, .2));
    border: 1.5px solid rgba(232, 35, 79, .45);
    color: #ff5e7e;
    display: grid;
    place-items: center;
    box-shadow: 0 10px 24px -6px rgba(232, 35, 79, .5);
}

/* Header text */
.exam-modal-header {
    text-align: center;
    margin-bottom: 22px;
    position: relative;
    z-index: 1;
}

.exam-modal-subheading {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: #ffd8a8;
    background: rgba(255, 255, 255, .1);
    padding: 3px 12px;
    border-radius: 999px;
    margin-bottom: 10px;
}

.exam-modal-title {
    font-size: 21px;
    font-weight: 800;
    color: #ffffff;
    line-height: 1.3;
    margin-bottom: 8px;
    letter-spacing: -.01em;
}

.exam-modal-desc {
    font-size: 13.5px;
    color: rgba(255, 255, 255, .75);
    line-height: 1.5;
    padding: 0 8px;
}

/* Info grid */
.exam-modal-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
    margin-bottom: 18px;
    position: relative;
    z-index: 1;
}

.exam-modal-item {
    background: rgba(255, 255, 255, .05);
    border: 1px solid rgba(255, 255, 255, .08);
    border-radius: 14px;
    padding: 12px 14px;
    display: flex;
    align-items: center;
    gap: 11px;
    transition: .2s;
}

.exam-modal-item:hover {
    background: rgba(255, 255, 255, .08);
    border-color: rgba(255, 255, 255, .15);
}

.exam-item-icon {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    display: grid;
    place-items: center;
    flex-shrink: 0;
}

.exam-item-icon.timer {
    background: rgba(232, 35, 79, .2);
    color: #ff6b8b;
}

.exam-item-icon.questions {
    background: rgba(59, 130, 246, .2);
    color: #60a5fa;
}

.exam-item-icon.pass {
    background: rgba(34, 197, 94, .2);
    color: #4ade80;
}

.exam-item-icon.cert {
    background: rgba(234, 179, 8, .2);
    color: #facc15;
}

.exam-item-text {
    display: flex;
    flex-direction: column;
    min-width: 0;
}

.exam-item-text .label {
    font-size: 11px;
    color: rgba(255, 255, 255, .55);
    font-weight: 500;
    line-height: 1.2;
    margin-bottom: 2px;
}

.exam-item-text strong {
    font-size: 13px;
    font-weight: 700;
    color: #fff;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Alert banner */
.exam-modal-alert {
    background: rgba(232, 35, 79, .14);
    border: 1px solid rgba(232, 35, 79, .3);
    border-radius: 12px;
    padding: 11px 14px;
    display: flex;
    align-items: flex-start;
    gap: 10px;
    font-size: 12.5px;
    color: #ffcad4;
    line-height: 1.45;
    margin-bottom: 24px;
    position: relative;
    z-index: 1;
}

.exam-modal-alert svg {
    color: #ff4d6d;
    flex-shrink: 0;
    margin-top: 2px;
}

.exam-modal-alert strong {
    color: #fff;
    font-weight: 700;
}

/* Actions */
.exam-modal-actions {
    display: flex;
    align-items: center;
    gap: 12px;
    position: relative;
    z-index: 1;
}

.btn-modal-cancel {
    flex: 1;
    height: 46px;
    border-radius: 12px;
    background: rgba(255, 255, 255, .08);
    border: 1px solid rgba(255, 255, 255, .12);
    color: rgba(255, 255, 255, .8);
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    transition: .2s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-modal-cancel:hover {
    background: rgba(255, 255, 255, .15);
    color: #fff;
}

.btn-modal-confirm {
    flex: 1.5;
    height: 46px;
    border-radius: 12px;
    background: linear-gradient(135deg, #e8234f 0%, #c4102e 100%);
    border: none;
    color: #fff;
    font-size: 14px;
    font-weight: 800;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    box-shadow: 0 8px 24px -4px rgba(232, 35, 79, .55);
    cursor: pointer;
    transition: .2s cubic-bezier(.4, 0, .2, 1);
}

.btn-modal-confirm:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 30px -4px rgba(232, 35, 79, .75);
    color: #fff;
}

.btn-modal-confirm:active {
    transform: translateY(0);
}

@media (max-width: 480px) {
    .exam-modal-card {
        padding: 24px 20px 20px;
        border-radius: 20px;
    }
    .exam-modal-grid {
        grid-template-columns: 1fr;
        gap: 8px;
    }
    .exam-modal-title {
        font-size: 19px;
    }
    .exam-modal-actions {
        flex-direction: column-reverse;
        gap: 8px;
    }
    .btn-modal-cancel, .btn-modal-confirm {
        width: 100%;
        flex: none;
    }
}
</style>

<!-- Modal HTML -->
<div class="exam-modal-overlay" id="examStartModal" aria-hidden="true" role="dialog" aria-modal="true">
    <div class="exam-modal-card">
        <div class="exam-modal-glow"></div>
        <div class="exam-modal-kanji-bg">試験</div>

        <button type="button" class="exam-modal-close" id="btnExamModalClose" aria-label="Tutup popup">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>

        <div class="exam-modal-icon-wrap">
            <div class="exam-modal-icon-pulse"></div>
            <div class="exam-modal-icon">
                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
            </div>
        </div>

        <div class="exam-modal-header">
            <div class="exam-modal-subheading">SIMULASI UJIAN JLPT N5</div>
            <h3 class="exam-modal-title">Siap Memulai Ujian Sekarang?</h3>
            <p class="exam-modal-desc">
                Pastikan kamu berada di tempat yang kondusif dan siap fokus selama 30 menit ke depan.
            </p>
        </div>

        <div class="exam-modal-grid">
            <div class="exam-modal-item">
                <div class="exam-item-icon timer">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 15 15"></polyline>
                    </svg>
                </div>
                <div class="exam-item-text">
                    <span class="label">Durasi Ujian</span>
                    <strong>30 Menit</strong>
                </div>
            </div>

            <div class="exam-modal-item">
                <div class="exam-item-icon questions">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                    </svg>
                </div>
                <div class="exam-item-text">
                    <span class="label">Jumlah Soal</span>
                    <strong>30 Soal (180 Poin)</strong>
                </div>
            </div>

            <div class="exam-modal-item">
                <div class="exam-item-icon pass">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                </div>
                <div class="exam-item-text">
                    <span class="label">Target Kelulusan</span>
                    <strong>Min. 80 Poin</strong>
                </div>
            </div>

            <div class="exam-modal-item">
                <div class="exam-item-icon cert">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="8" r="7"></circle>
                        <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
                    </svg>
                </div>
                <div class="exam-item-text">
                    <span class="label">Apresiasi</span>
                    <strong>Sertifikat Digital</strong>
                </div>
            </div>
        </div>

        <div class="exam-modal-alert">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <span>Waktu 30 menit akan <strong>langsung berjalan</strong> dan tidak dapat dijeda (*pause*) setelah kamu menekan tombol Mulai.</span>
        </div>

        <div class="exam-modal-actions">
            <button type="button" class="btn-modal-cancel" id="btnExamModalCancel">
                Batal
            </button>
            <a href="{{ route('jlpt.start') }}" class="btn-modal-confirm" id="btnExamModalConfirm">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="5 3 19 12 5 21 5 3"></polygon>
                </svg>
                <span>Mulai Sekarang</span>
            </a>
        </div>
    </div>
</div>

<script>
(() => {
    const modal = document.getElementById('examStartModal');
    if (!modal) return;

    const btnClose = document.getElementById('btnExamModalClose');
    const btnCancel = document.getElementById('btnExamModalCancel');
    const openBtns = document.querySelectorAll('.js-open-exam-modal');

    function openModal(e) {
        if (e) e.preventDefault();
        modal.classList.add('show');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.classList.remove('show');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }

    openBtns.forEach(btn => {
        btn.addEventListener('click', openModal);
    });

    if (btnClose) btnClose.addEventListener('click', closeModal);
    if (btnCancel) btnCancel.addEventListener('click', closeModal);

    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeModal();
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal.classList.contains('show')) {
            closeModal();
        }
    });
})();
</script>

