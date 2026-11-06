@extends('dashboard.layout')

@php($pageTitle = 'Progres Belajar')
@php($pageSubtitle = 'Pantau perkembangan materi dan riwayat hasil latihanmu.')

@section('styles')
    .progress-hero-card {
        display: flex;
        align-items: center;
        gap: 24px;
        margin-bottom: 26px;
        padding: 26px 30px;
        border: 1px solid var(--line);
        border-radius: var(--radius);
        background: linear-gradient(135deg, #ffffff, #fff2f5);
        box-shadow: var(--shadow);
    }
    .progress-big-number {
        min-width: 110px;
        color: var(--red);
        font-size: 46px;
        font-weight: 900;
        letter-spacing: -.06em;
        line-height: 1;
    }
    .progress-info {
        flex: 1;
        min-width: 0;
    }
    .progress-info h2 {
        font-size: 17px;
        font-weight: 800;
        color: var(--ink);
    }
    .progress-info p {
        margin: 4px 0 14px;
        color: var(--muted);
        font-size: 13px;
    }
    .progress-info .progress-track {
        height: 10px;
    }
    .progress-hero-card .button {
        flex-shrink: 0;
    }

    .material-progress-list {
        padding: 8px 24px;
    }
    .progress-row {
        display: grid;
        grid-template-columns: 1fr 180px 60px;
        align-items: center;
        gap: 18px;
        padding: 16px 0;
        border-bottom: 1px solid var(--line);
    }
    .progress-row:last-child {
        border-bottom: 0;
    }
    .progress-row-info {
        min-width: 0;
    }
    .progress-row-title {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 3px;
    }
    .progress-row h3 {
        font-size: 14.5px;
        font-weight: 700;
        color: var(--ink);
    }
    .progress-row p {
        color: var(--muted);
        font-size: 12px;
    }
    .percent {
        color: var(--red);
        font-size: 13px;
        font-weight: 800;
        text-align: right;
    }

    .table-responsive {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    .results {
        width: 100%;
        border-collapse: collapse;
        min-width: 600px;
    }
    .results th, .results td {
        padding: 14px 22px;
        border-bottom: 1px solid var(--line);
        text-align: left;
    }
    .results th {
        color: var(--muted);
        background: #fff6f4;
        font-size: 10.5px;
        font-weight: 800;
        letter-spacing: .1em;
        text-transform: uppercase;
    }
    .results td {
        font-size: 13px;
        color: var(--ink);
    }
    .results tr:last-child td {
        border-bottom: none;
    }
    .results tr:hover td {
        background: rgba(255,248,246,.5);
    }
    .score-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 10px;
        border-radius: 999px;
        font-weight: 800;
        font-size: 12px;
    }
    .score-badge.high {
        background: var(--green-soft);
        color: var(--green);
    }
    .score-badge.mid {
        background: var(--gold-soft);
        color: var(--gold);
    }
    .score-badge.low {
        background: var(--red-soft);
        color: var(--red);
    }

    @media (max-width: 768px) {
        .progress-hero-card {
            flex-direction: column;
            align-items: flex-start;
            padding: 20px;
            gap: 16px;
        }
        .progress-hero-card .button {
            width: 100%;
        }
        .progress-row {
            grid-template-columns: 1fr auto;
            gap: 12px;
            padding: 14px 0;
        }
        .progress-row .progress-track {
            grid-column: 1 / -1;
            order: 3;
            margin-top: 2px;
        }
        .material-progress-list {
            padding: 4px 16px;
        }
    }
@endsection

@section('content')
    <section class="progress-hero-card">
        <div class="progress-big-number">{{ $overallProgress }}%</div>
        <div class="progress-info">
            <h2>Progres Belajar Keseluruhan</h2>
            <p>{{ $completedCount }} dari {{ $totalMaterials }} materi telah berhasil kamu selesaikan.</p>
            <div class="progress-track">
                <div class="progress-fill" style="width: {{ $overallProgress }}%"></div>
            </div>
        </div>
        <a class="button" href="{{ route('dashboard.materials') }}">
            Lanjut Belajar
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
            </svg>
        </a>
    </section>

    <section class="stats" aria-label="Statistik latihan">
        <div class="stat">
            <span class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
            </span>
            <div>
                <strong>{{ $completedCount }}</strong>
                <span>Materi selesai</span>
            </div>
        </div>

        <div class="stat">
            <span class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
            </span>
            <div>
                <strong>{{ max(0, $totalMaterials - $completedCount) }}</strong>
                <span>Materi tersisa</span>
            </div>
        </div>

        <div class="stat">
            <span class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
            </span>
            <div>
                <strong>{{ $totalQuizzesTaken }}</strong>
                <span>Quiz dikerjakan</span>
            </div>
        </div>

        <div class="stat">
            <span class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                </svg>
            </span>
            <div>
                <strong>{{ $avgScore }}%</strong>
                <span>Rata-rata skor</span>
            </div>
        </div>
    </section>

    @include('dashboard.partials.gamification-card')

    @include('dashboard.partials.activity-heatmap')

    <section class="section" style="margin-bottom: 26px">
        <div class="section-head">
            <div>
                <h2>Progres per Materi</h2>
                <p>Status pembelajaran pada setiap modul</p>
            </div>
        </div>

        <div class="material-progress-list">
            @forelse($materials as $material)
                @php($progress = $progresses->get($material->id))
                <article class="progress-row">
                    <div class="progress-row-info">
                        <div class="progress-row-title">
                            <h3>{{ $material->title }}</h3>
                            @if($progress?->completed)
                                <span class="badge green">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    Selesai
                                </span>
                            @endif
                        </div>
                        <p>{{ $progress?->completed ? 'Materi telah selesai dipelajari' : 'Belum selesai dipelajari' }}</p>
                    </div>

                    <div class="progress-track">
                        <div class="progress-fill" style="width: {{ $progress?->progress_percent ?? 0 }}%"></div>
                    </div>

                    <span class="percent">{{ $progress?->progress_percent ?? 0 }}%</span>
                </article>
            @empty
                <div class="empty">
                    <strong>Belum ada materi</strong>
                    Modul materi pembelajaran akan muncul di sini.
                </div>
            @endforelse
        </div>
    </section>

    <section class="section">
        <div class="section-head">
            <div>
                <h2>Riwayat Hasil Quiz</h2>
                <p>10 riwayat skor dan latihan terbaru</p>
            </div>
            <a class="button ghost" href="{{ route('dashboard.quizzes') }}">
                Kerjakan Quiz
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </a>
        </div>

        @if($recentResults->isEmpty())
            <div class="empty">
                <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="var(--muted)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom:12px">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                <strong>Belum ada hasil quiz</strong>
                Mulai latihan untuk mencatat riwayat hasil dan perkembangan skor kamu di sini.
            </div>
        @else
            <div class="table-responsive">
                <table class="results">
                    <thead>
                        <tr>
                            <th>Quiz</th>
                            <th>Skor</th>
                            <th>Benar / Total</th>
                            <th>Waktu Pengerjaan</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentResults as $result)
                            @php($scoreClass = $result->score >= 80 ? 'high' : ($result->score >= 60 ? 'mid' : 'low'))
                            <tr>
                                <td>
                                    <strong>{{ $result->quiz?->title ?? 'Quiz' }}</strong>
                                </td>
                                <td>
                                    <span class="score-badge {{ $scoreClass }}">
                                        {{ $result->score }}%
                                    </span>
                                </td>
                                <td>{{ $result->correct }} / {{ $result->total }} Soal</td>
                                <td>{{ intdiv($result->time_taken, 60) }}m {{ $result->time_taken % 60 }}d</td>
                                <td>{{ $result->created_at->locale('id')->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
@endsection