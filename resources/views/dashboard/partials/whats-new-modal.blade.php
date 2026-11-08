<!-- Modal Kilat "Apa yang Baru di JP Learn v2.4.0" -->
<div id="whatsNewModal" class="wn-modal-backdrop" aria-hidden="true" style="display: none;">
    <div class="wn-modal-container" role="dialog" aria-modal="true" aria-labelledby="wnModalTitle">
        <!-- Header Modal -->
        <div class="wn-modal-header">
            <div class="wn-header-left">
                <div class="wn-icon-badge">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                </div>
                <div>
                    <div class="wn-version-tag">
                        <span class="wn-tag-pill">Versi v2.4.0</span>
                        <span class="wn-tag-date">8 November 2026</span>
                    </div>
                    <h3 id="wnModalTitle" class="wn-modal-title">Apa yang Baru di JP Learn?</h3>
                </div>
            </div>
            <button type="button" class="wn-close-btn" onclick="closeWhatsNewModal()" aria-label="Tutup jendela">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <!-- Body Modal -->
        <div class="wn-modal-body">
            <div class="wn-intro-box">
                <p>
                    Kini JP Learn semakin lengkap dan mudah dijelajahi! Simak ringkasan pembaruan terkini untuk mendukung kelancaran belajar bahasa Jepang Anda:
                </p>
            </div>

            <div class="wn-feature-list">
                <!-- Item 1: Info & Versi -->
                <div class="wn-feature-card">
                    <div class="wn-card-icon wn-card-icon-red">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="16" x2="12" y2="12"></line>
                            <line x1="12" y1="8" x2="12.01" y2="8"></line>
                        </svg>
                    </div>
                    <div class="wn-card-content">
                        <div class="wn-card-header">
                            <span class="wn-badge-pill wn-badge-new">BARU</span>
                            <strong>Pusat Info Pembaruan & Versi</strong>
                        </div>
                        <p>Halaman khusus dan catatan rilis transparan untuk melihat histori fitur, pembaruan performa, dan rencana roadmap mendatang.</p>
                    </div>
                </div>

                <!-- Item 2: Tour Guide -->
                <div class="wn-feature-card">
                    <div class="wn-card-icon wn-card-icon-blue">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"></polygon>
                        </svg>
                    </div>
                    <div class="wn-card-content">
                        <div class="wn-card-header">
                            <span class="wn-badge-pill wn-badge-new">BARU</span>
                            <strong>Panduan Website Interaktif (Guided Tour)</strong>
                        </div>
                        <p>Jelajahi 14 fitur unggulan JP Learn dengan panduan visual Driver.js langkah demi langkah dari Dashboard hingga Simulasi JLPT.</p>
                    </div>
                </div>

                <!-- Item 3: AI Kenji Sensei -->
                <div class="wn-feature-card">
                    <div class="wn-card-icon wn-card-icon-purple">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 6h16"></path>
                            <path d="M2 9h20"></path>
                            <path d="M6 6v14"></path>
                            <path d="M18 6v14"></path>
                        </svg>
                    </div>
                    <div class="wn-card-content">
                        <div class="wn-card-header">
                            <span class="wn-badge-pill wn-badge-update">PENINGKATAN</span>
                            <strong>Optimasi AI Kenji Sensei & Gemini 3.8</strong>
                        </div>
                        <p>Peningkatan ketahanan koneksi Google Gemini Cloud dan respon cerdas Sensei Engine saat konsultasi grammar dan partikel.</p>
                    </div>
                </div>

                <!-- Item 4: UI Mobile Drawer -->
                <div class="wn-feature-card">
                    <div class="wn-card-icon wn-card-icon-green">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect>
                            <line x1="12" y1="18" x2="12.01" y2="18"></line>
                        </svg>
                    </div>
                    <div class="wn-card-content">
                        <div class="wn-card-header">
                            <span class="wn-badge-pill wn-badge-update">OPTIMASI</span>
                            <strong>Navigasi Mobile Lebih Halus</strong>
                        </div>
                        <p>Sidebar drawer sentuh yang lebih responsif dan perbaikan scroll lock pada dialog pop-up.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Modal -->
        <div class="wn-modal-footer">
            <a href="{{ route('updates.index') }}" class="wn-btn-primary">
                Lihat Catatan Rilis & Roadmap Lengkap
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </a>
            <button type="button" class="wn-btn-secondary" onclick="closeWhatsNewModal()">
                Tutup
            </button>
        </div>
    </div>
</div>

<style>
/* ── Styles Khusus Modal Apa yang Baru ── */
.wn-modal-backdrop {
    position: fixed;
    inset: 0;
    z-index: 9999;
    background: rgba(15, 12, 32, 0.72);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 16px;
    opacity: 0;
    visibility: hidden;
    transition: opacity .25s ease, visibility .25s ease;
}
.wn-modal-backdrop.is-open {
    opacity: 1;
    visibility: visible;
}
.wn-modal-container {
    width: 100%;
    max-width: 580px;
    max-height: 90vh;
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 25px 60px -15px rgba(23, 20, 58, 0.45);
    border: 1px solid #f0d5d0;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    transform: scale(0.95) translateY(10px);
    transition: transform .25s cubic-bezier(0.16, 1, 0.3, 1);
}
.wn-modal-backdrop.is-open .wn-modal-container {
    transform: scale(1) translateY(0);
}

.wn-modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    padding: 20px 24px;
    background: linear-gradient(135deg, #fff7f8, #ffffff);
    border-bottom: 1px solid #f2e2df;
}
.wn-header-left {
    display: flex;
    align-items: center;
    gap: 14px;
}
.wn-icon-badge {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: linear-gradient(135deg, #c4102e, #e8234f);
    color: #ffffff;
    display: grid;
    place-items: center;
    box-shadow: 0 8px 18px -4px rgba(196, 16, 46, 0.5);
    flex-shrink: 0;
}
.wn-version-tag {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 4px;
}
.wn-tag-pill {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 999px;
    background: #fde8ec;
    color: #c4102e;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: .02em;
}
.wn-tag-date {
    font-size: 11.5px;
    color: #8c727b;
    font-weight: 500;
}
.wn-modal-title {
    font-size: 18px;
    font-weight: 800;
    color: #1a0a0e;
    letter-spacing: -.02em;
    margin: 0;
}
.wn-close-btn {
    width: 34px;
    height: 34px;
    border-radius: 9px;
    border: 1px solid #eddcd9;
    background: #ffffff;
    color: #7a5864;
    cursor: pointer;
    display: grid;
    place-items: center;
    transition: all .2s;
    flex-shrink: 0;
}
.wn-close-btn:hover {
    background: #fde8ec;
    color: #c4102e;
    border-color: #f7b8c4;
}

.wn-modal-body {
    padding: 22px 24px;
    overflow-y: auto;
    max-height: calc(90vh - 160px);
}
.wn-intro-box {
    margin-bottom: 18px;
    padding: 12px 16px;
    border-radius: 12px;
    background: #fff8f6;
    border-left: 3.5px solid #c4102e;
}
.wn-intro-box p {
    font-size: 13px;
    color: #55333f;
    line-height: 1.55;
    margin: 0;
}

.wn-feature-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.wn-feature-card {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    padding: 12px 14px;
    border-radius: 12px;
    background: #ffffff;
    border: 1px solid #f0e2df;
    transition: all .2s;
}
.wn-feature-card:hover {
    border-color: #e2b6bf;
    background: #fffbfa;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px -4px rgba(60, 20, 40, 0.08);
}
.wn-card-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: grid;
    place-items: center;
    flex-shrink: 0;
}
.wn-card-icon-red    { background: #fde8ec; color: #c4102e; }
.wn-card-icon-blue   { background: #e8ecf8; color: #2d3a8c; }
.wn-card-icon-purple { background: #f3e8ff; color: #7c3aed; }
.wn-card-icon-green  { background: #e6f4ee; color: #1f5b45; }

.wn-card-content { min-width: 0; flex: 1; }
.wn-card-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 4px;
    flex-wrap: wrap;
}
.wn-badge-pill {
    font-size: 9.5px;
    font-weight: 800;
    letter-spacing: .08em;
    text-transform: uppercase;
    padding: 2px 6px;
    border-radius: 6px;
    line-height: 1;
}
.wn-badge-new    { background: #e6f4ee; color: #1f5b45; border: 1px solid #b8e0ce; }
.wn-badge-update { background: #e8ecf8; color: #2d3a8c; border: 1px solid #c2cdf0; }
.wn-card-header strong {
    font-size: 13px;
    color: #1a0a0e;
    font-weight: 700;
}
.wn-card-content p {
    font-size: 12px;
    color: #6d545e;
    line-height: 1.5;
    margin: 0;
}

.wn-modal-footer {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 12px;
    padding: 16px 24px;
    background: #faf6f5;
    border-top: 1px solid #f0e0dc;
    flex-wrap: wrap;
}
.wn-btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 9px 18px;
    border-radius: 10px;
    background: linear-gradient(135deg, #c4102e, #e8234f);
    color: #ffffff;
    font-size: 12.5px;
    font-weight: 700;
    text-decoration: none;
    box-shadow: 0 4px 12px -2px rgba(196, 16, 46, 0.45);
    transition: all .2s;
    border: none;
    cursor: pointer;
}
.wn-btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 18px -4px rgba(196, 16, 46, 0.6);
    color: #ffffff;
}
.wn-btn-secondary {
    padding: 9px 16px;
    border-radius: 10px;
    background: #ffffff;
    border: 1px solid #dec8c4;
    color: #63434f;
    font-size: 12.5px;
    font-weight: 600;
    cursor: pointer;
    transition: all .2s;
}
.wn-btn-secondary:hover {
    border-color: #c4102e;
    color: #c4102e;
    background: #fff8f6;
}

@media (max-width: 600px) {
    .wn-modal-container { max-height: 94vh; }
    .wn-modal-header { padding: 16px 18px; }
    .wn-modal-body { padding: 16px 18px; }
    .wn-modal-footer { padding: 14px 18px; flex-direction: column-reverse; }
    .wn-btn-primary, .wn-btn-secondary { width: 100%; justify-content: center; text-align: center; }
}
</style>

<script>
/**
 * Kontroler Modal "Apa yang Baru?"
 */
function openWhatsNewModal() {
    const modal = document.getElementById('whatsNewModal');
    if (!modal) return;
    modal.style.display = 'flex';
    // Trigger CSS opacity transition
    setTimeout(() => {
        modal.classList.add('is-open');
    }, 10);
    document.body.style.overflow = 'hidden';
}

function closeWhatsNewModal() {
    const modal = document.getElementById('whatsNewModal');
    if (!modal) return;
    modal.classList.remove('is-open');
    setTimeout(() => {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }, 250);
}

// Tutup saat klik di backdrop luar modal
document.addEventListener('click', function(e) {
    const modal = document.getElementById('whatsNewModal');
    if (modal && e.target === modal) {
        closeWhatsNewModal();
    }
});

// Tutup saat tekan tombol ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('whatsNewModal');
        if (modal && modal.classList.contains('is-open')) {
            closeWhatsNewModal();
        }
    }
});
</script>

