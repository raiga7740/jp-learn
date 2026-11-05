@extends('dashboard.layout')

@php($pageTitle = 'Materi Belajar')
@php($pageSubtitle = 'Pilih materi dan belajar secara terstruktur sesuai ritmemu.')

@section('styles')
    .material-list {
        display: grid;
        gap: 14px;
        padding: 16px;
    }
    .material {
        display: grid;
        grid-template-columns: 56px 1fr minmax(140px, 200px);
        align-items: center;
        gap: 18px;
        padding: 18px 20px;
        border: 1px solid var(--line);
        border-radius: 16px;
        background: #fffafa;
        transition: all .2s ease;
    }
    .material:hover {
        border-color: rgba(196,16,46,.3);
        background: #fff;
        box-shadow: 0 10px 24px -12px rgba(180,10,40,.2);
        transform: translateY(-1px);
    }
    .material-icon {
        display: grid;
        width: 56px;
        height: 56px;
        place-items: center;
        border-radius: 14px;
        background: var(--red-soft);
        color: var(--red);
        font: 900 26px 'Noto Sans JP', sans-serif;
        flex-shrink: 0;
        box-shadow: 0 4px 12px -4px rgba(196,16,46,.25);
    }
    .material:nth-child(2n) .material-icon {
        background: var(--blue-soft);
        color: var(--blue);
        box-shadow: 0 4px 12px -4px rgba(45,58,140,.25);
    }
    .material:nth-child(3n) .material-icon {
        background: var(--gold-soft);
        color: var(--gold);
        box-shadow: 0 4px 12px -4px rgba(200,128,10,.25);
    }
    .material-body {
        min-width: 0;
    }
    .material-title-row {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 4px;
    }
    .material h3 {
        font-size: 16px;
        font-weight: 800;
        color: var(--ink);
    }
    .material p {
        color: var(--muted);
        font-size: 13px;
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .material-meta {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 8px;
        flex-wrap: wrap;
    }
    .material-meta .badge {
        font-size: 10px;
    }
    .material-progress {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 8px;
    }
    .material-progress-text {
        font-size: 11.5px;
        font-weight: 700;
        color: var(--muted);
    }
    .material-progress .progress-track {
        width: 100%;
        max-width: 160px;
    }
    .material-progress .button {
        margin-top: 4px;
    }

    @media (max-width: 768px) {
        .material {
            grid-template-columns: 48px 1fr;
            gap: 14px;
            padding: 16px;
        }
        .material-icon {
            width: 48px;
            height: 48px;
            font-size: 22px;
        }
        .material-progress {
            grid-column: 1 / -1;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
            padding-top: 12px;
            border-top: 1px dashed var(--line);
            margin-top: 4px;
        }
        .material-progress-bar-wrap {
            flex: 1;
            max-width: 180px;
        }
        .material-progress .progress-track {
            max-width: 100%;
        }
        .material-progress .button {
            margin-top: 0;
            padding: 8px 14px;
            font-size: 12px;
        }
    }

    @media (max-width: 480px) {
        .material-list {
            padding: 12px;
            gap: 12px;
        }
        .material {
            padding: 14px;
        }
        .material-progress {
            flex-direction: column;
            align-items: stretch;
            gap: 10px;
        }
        .material-progress-bar-wrap {
            max-width: 100%;
        }
        .material-progress .button {
            width: 100%;
        }
    }
@endsection

@section('content')
    <section class="hero">
        <span class="eyebrow">Belajar · 学習</span>
        <h2>Materi Bahasa Jepang</h2>
        <p>{{ $completedCount }} dari {{ $totalMaterials }} materi telah selesai. Pilih materi untuk mulai belajar atau ulangi materi yang telah kamu pelajari.</p>
    </section>

    <section class="section">
        <div class="section-head">
            <div>
                <h2>Daftar Materi</h2>
                <p>{{ $totalMaterials }} materi tersedia untuk dipelajari</p>
            </div>
            <span class="badge {{ $overallProgress === 100 ? 'green' : '' }}">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                {{ $overallProgress }}% selesai
            </span>
        </div>

        @if($materials->isEmpty())
            <div class="empty">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--muted)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom:12px">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                </svg>
                <strong>Belum ada materi</strong>
                Materi akan muncul di halaman ini ketika tersedia.
            </div>
        @else
            <div class="material-list">
                @foreach($materials as $material)
                    @php($progress = $progresses->get($material->id))
                    <article class="material">
                        <div class="material-icon">
                            {{ match($material->category) {
                                'hiragana' => 'あ',
                                'katakana' => 'ア',
                                'kanji' => '漢',
                                'vocabulary' => '語',
                                'grammar' => '文',
                                default => '学'
                            } }}
                        </div>

                        <div class="material-body">
                            <div class="material-title-row">
                                <span class="badge">{{ ucfirst($material->category) }}</span>
                                <span class="badge blue">{{ strtoupper($material->level) }}</span>
                                @if($progress?->completed)
                                    <span class="badge green">
                                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                        Selesai
                                    </span>
                                @endif
                            </div>

                            <h3>{{ $material->title }}</h3>
                            <p>{{ $material->description }}</p>
                        </div>

                        <div class="material-progress">
                            <div class="material-progress-bar-wrap">
                                <span class="material-progress-text">{{ $progress?->progress_percent ?? 0 }}% dipelajari</span>
                                <div class="progress-track">
                                    <div class="progress-fill" style="width: {{ $progress?->progress_percent ?? 0 }}%"></div>
                                </div>
                            </div>
                            <a href="{{ route('material.show', $material) }}" class="button {{ $progress?->completed ? 'ghost' : '' }}">
                                {{ $progress?->completed ? 'Ulangi' : 'Belajar' }}
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <polyline points="12 5 19 12 12 19"></polyline>
                                </svg>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
@endsection