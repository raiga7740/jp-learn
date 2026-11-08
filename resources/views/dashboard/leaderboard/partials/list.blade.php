@php
    $rest = $items->slice(3);
    $avatarGradients = [
        'linear-gradient(135deg, #0284c7 0%, #0369a1 100%)',
        'linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%)',
        'linear-gradient(135deg, #059669 0%, #047857 100%)',
        'linear-gradient(135deg, #d97706 0%, #b45309 100%)',
        'linear-gradient(135deg, #db2777 0%, #be185d 100%)',
        'linear-gradient(135deg, #475569 0%, #334155 100%)',
        'linear-gradient(135deg, #dc2626 0%, #b91c1c 100%)',
    ];
@endphp

@if($rest->isNotEmpty())
    <div class="lb-card">
        <div class="lb-card-header">
            <span>Peringkat 4 - {{ $items->count() }} (Top 50)</span>
            <span>Skor {{ $unit }}</span>
        </div>

        @foreach($rest as $idx => $item)
            @php
                $grad = $avatarGradients[$idx % count($avatarGradients)];
            @endphp
            <div class="lb-row {{ $item['is_current_user'] ? 'is-self' : '' }}">
                <div class="lb-row-rank">
                    <span class="rank-number {{ $item['is_current_user'] ? 'highlight' : '' }}">#{{ $item['rank'] }}</span>
                </div>
                <div class="lb-row-avatar">
                    <div class="avatar-initial" style="background: {{ $grad }};">
                        {{ mb_substr($item['name'], 0, 1) }}
                    </div>
                </div>
                <div class="lb-row-info">
                    <div class="lb-row-name">
                        <span>{{ $item['name'] }}</span>
                        @if($item['is_current_user'])
                            <span class="badge-self">Kamu</span>
                        @endif
                    </div>
                    <div class="lb-row-sub">
                        <span class="lb-lvl">Lv. {{ $item['level'] }}</span>
                        <span class="lb-dot">·</span>
                        <span>{{ Str::before($item['title'], '·') }}</span>
                        @if($item['streak'] > 0)
                            <span class="lb-dot">·</span>
                            <span class="lb-streak" title="Rantai streak aktif">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"></path>
                                </svg>
                                {{ $item['streak'] }} hari
                            </span>
                        @endif
                    </div>
                </div>
                <div class="lb-row-score">
                    <span class="score-val">{{ $item['score_label'] }}</span>
                </div>
            </div>
        @endforeach
    </div>
@endif

<!-- Pinned Current User Card (if user is outside Top 10) -->
@if(!$currentUserStat['in_top_10'])
    <div class="lb-self-pinned">
        <div class="lb-self-pinned-badge">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <polygon points="12 8 8 12 12 16 12 8"></polygon>
                <line x1="16" y1="12" x2="12" y2="12"></line>
            </svg>
            Posisi Anda Saat Ini
        </div>
        <div class="lb-row is-self" style="margin: 0; background: transparent; border: none; padding: 4px 0;">
            <div class="lb-row-rank">
                <span class="rank-number highlight">#{{ $currentUserStat['rank'] }}</span>
            </div>
            <div class="lb-row-avatar">
                <div class="avatar-initial" style="background: linear-gradient(135deg, #e8234f 0%, #a00d25 100%);">
                    {{ mb_substr(auth()->user()->name, 0, 1) }}
                </div>
            </div>
            <div class="lb-row-info">
                <div class="lb-row-name">
                    <span>{{ auth()->user()->name }}</span>
                    <span class="badge-self">Kamu</span>
                </div>
                <div class="lb-row-sub">
                    <span class="lb-lvl">Lv. {{ $userStats['level'] }}</span>
                    <span class="lb-dot">·</span>
                    <span>{{ Str::before($userStats['title'], '·') }}</span>
                    @if(auth()->user()->current_streak > 0)
                        <span class="lb-dot">·</span>
                        <span class="lb-streak">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"></path>
                            </svg>
                            {{ auth()->user()->current_streak }} hari
                        </span>
                    @endif
                </div>
            </div>
            <div class="lb-row-score">
                <span class="score-val">{{ $currentUserStat['score_label'] }}</span>
            </div>
        </div>
        <div class="lb-self-pinned-hint">
            Tingkatkan konsistensi belajarmu dengan mengerjakan latihan, flashcard, dan tantangan harian untuk masuk ke jajaran Top 50!
        </div>
    </div>
@endif

