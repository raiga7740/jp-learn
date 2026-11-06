<section class="section" aria-label="Heatmap aktivitas belajar" style="margin-bottom: 26px;">
    <div class="section-head">
        <div>
            <h2>Aktivitas Belajar</h2>
            <p>{{ $heatmap['active_days'] }} hari aktif dan {{ number_format($heatmap['total_xp']) }} XP dalam 16 minggu terakhir.</p>
        </div>
    </div>

    <div style="padding: 4px 20px 22px;">
        <div class="heatmap-scroll">
            <div class="heatmap">
                @foreach ($heatmap['weeks'] as $week)
                    <div class="heat-col">
                        <div class="heat-month">{{ $week['label'] }}</div>
                        @foreach ($week['days'] as $day)
                            <div class="heat-cell lv{{ $day['intensity'] }} {{ $day['future'] ? 'future' : '' }}"
                                 title="{{ $day['date'] }} · {{ $day['xp'] }} XP"></div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>

        <div class="heat-legend">
            <span>Sedikit</span>
            <div class="heat-cell"></div>
            <div class="heat-cell lv1"></div>
            <div class="heat-cell lv2"></div>
            <div class="heat-cell lv3"></div>
            <div class="heat-cell lv4"></div>
            <span>Banyak</span>
        </div>
    </div>
</section>
