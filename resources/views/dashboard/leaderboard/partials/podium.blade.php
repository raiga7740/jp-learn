@php
    $first = $items->firstWhere('rank', 1);
    $second = $items->firstWhere('rank', 2);
    $third = $items->firstWhere('rank', 3);
@endphp

<div class="podium-container">
    <div class="podium-grid">
        <!-- Rank 2: Silver (Left) -->
        <div class="podium-step rank-2">
            @if($second)
                <div class="podium-avatar-wrap">
                    <div class="podium-avatar" style="background: linear-gradient(135deg, #64748b 0%, #475569 100%);">
                        {{ mb_substr($second['name'], 0, 1) }}
                    </div>
                    <span class="podium-badge-rank">2</span>
                </div>
                <div class="podium-name" title="{{ $second['name'] }}">
                    {{ $second['name'] }}
                </div>
                <div class="podium-sub">
                    Lv. {{ $second['level'] }} · {{ Str::before($second['title'], '·') }}
                </div>
                <div class="pedestal-base">
                    <span class="pedestal-number">2</span>
                    <span class="pedestal-score">{{ $second['score_label'] }}</span>
                </div>
            @else
                <div class="podium-avatar-wrap" style="opacity: .4;">
                    <div class="podium-avatar" style="background: #94a3b8; border-style: dashed;">-</div>
                </div>
                <div class="podium-name" style="opacity: .4;">Kosong</div>
                <div class="pedestal-base" style="opacity: .4;">
                    <span class="pedestal-number">2</span>
                </div>
            @endif
        </div>

        <!-- Rank 1: Gold (Center) -->
        <div class="podium-step rank-1">
            @if($first)
                <div class="podium-avatar-wrap">
                    <!-- Crown SVG -->
                    <svg class="podium-crown" width="30" height="30" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M2 19h20v2H2v-2zm1.5-4.5L5 8l4.5 4L12 4l2.5 8 4.5-4 1.5 6.5H3.5z"></path>
                    </svg>
                    <div class="podium-avatar" style="background: linear-gradient(135deg, #f59e0b 0%, #b45309 100%);">
                        {{ mb_substr($first['name'], 0, 1) }}
                    </div>
                    <span class="podium-badge-rank">1</span>
                </div>
                <div class="podium-name" title="{{ $first['name'] }}">
                    {{ $first['name'] }}
                </div>
                <div class="podium-sub">
                    Lv. {{ $first['level'] }} · {{ Str::before($first['title'], '·') }}
                </div>
                <div class="pedestal-base">
                    <span class="pedestal-number">1</span>
                    <span class="pedestal-score">{{ $first['score_label'] }}</span>
                </div>
            @else
                <div class="podium-avatar-wrap" style="opacity: .4;">
                    <div class="podium-avatar" style="background: #f59e0b; border-style: dashed;">-</div>
                </div>
                <div class="podium-name" style="opacity: .4;">Kosong</div>
                <div class="pedestal-base" style="opacity: .4;">
                    <span class="pedestal-number">1</span>
                </div>
            @endif
        </div>

        <!-- Rank 3: Bronze (Right) -->
        <div class="podium-step rank-3">
            @if($third)
                <div class="podium-avatar-wrap">
                    <div class="podium-avatar" style="background: linear-gradient(135deg, #b45309 0%, #78350f 100%);">
                        {{ mb_substr($third['name'], 0, 1) }}
                    </div>
                    <span class="podium-badge-rank">3</span>
                </div>
                <div class="podium-name" title="{{ $third['name'] }}">
                    {{ $third['name'] }}
                </div>
                <div class="podium-sub">
                    Lv. {{ $third['level'] }} · {{ Str::before($third['title'], '·') }}
                </div>
                <div class="pedestal-base">
                    <span class="pedestal-number">3</span>
                    <span class="pedestal-score">{{ $third['score_label'] }}</span>
                </div>
            @else
                <div class="podium-avatar-wrap" style="opacity: .4;">
                    <div class="podium-avatar" style="background: #b45309; border-style: dashed;">-</div>
                </div>
                <div class="podium-name" style="opacity: .4;">Kosong</div>
                <div class="pedestal-base" style="opacity: .4;">
                    <span class="pedestal-number">3</span>
                </div>
            @endif
        </div>
    </div>
</div>

