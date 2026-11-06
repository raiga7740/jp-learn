@php
    $streakAlive = $gami['streak'] > 0;
    $streakHint = match (true) {
        ! $streakAlive => 'Belajar hari ini untuk memulai streak barumu.',
        ! $gami['active_today'] => 'Belajar hari ini agar streak ' . $gami['streak'] . ' harimu tidak putus!',
        default => 'Hebat! Streak hari ini sudah aman.',
    };
@endphp

<section class="gami-card" aria-label="Streak dan XP belajar">
    <div class="gami-panel">
        <div class="gami-panel-title">Level &amp; Pengalaman</div>

        <div class="gami-level-row">
            <div class="gami-level-badge">{{ $gami['level'] }}</div>
            <div>
                <div class="gami-level-name">{{ $gami['title'] }}</div>
                <div class="gami-level-xp">{{ number_format($gami['xp']) }} XP total</div>
            </div>
        </div>

        <div class="progress-track">
            <div class="progress-fill" style="width: {{ $gami['level_progress'] }}%"></div>
        </div>
        <div class="gami-xp-meta">
            @if ($gami['next_level_xp'] !== null)
                <span>{{ number_format($gami['xp'] - $gami['level_floor']) }} / {{ number_format($gami['next_level_xp'] - $gami['level_floor']) }} XP</span>
                <span>{{ number_format($gami['xp_to_next']) }} XP lagi ke Level {{ $gami['level'] + 1 }}</span>
            @else
                <span>Level tertinggi tercapai</span>
                <span>{{ number_format($gami['xp']) }} XP</span>
            @endif
        </div>

        <div class="gami-meta-row">
            <span>XP hari ini: <strong>+{{ $gami['today_xp'] }}</strong></span>
        </div>
    </div>

    <div class="gami-panel">
        <div class="gami-panel-title">Streak Belajar</div>

        <div class="gami-streak-head">
            <div class="gami-flame {{ $streakAlive ? 'on' : '' }}">
                <svg width="30" height="30" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2s5 4.5 5 10a5 5 0 0 1-10 0c0-2 1-3.5 2-4.5.3 1.6 1.2 2.4 2 2.5C11 7 10 4 12 2z"/></svg>
            </div>
            <div>
                <div class="gami-streak-number">{{ $gami['streak'] }}<small>hari berturut-turut</small></div>
                <div class="gami-streak-hint">{{ $streakHint }}</div>
            </div>
        </div>

        <div class="week-strip">
            @foreach ($gami['week'] as $day)
                <div class="week-day">
                    {{ $day['label'] }}
                    <div class="week-dot {{ $day['active'] ? 'active' : '' }} {{ $day['is_today'] ? 'today' : '' }}">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="gami-meta-row">
            <span>Streak terpanjang: <strong>{{ $gami['longest_streak'] }} hari</strong></span>
        </div>
    </div>
</section>

