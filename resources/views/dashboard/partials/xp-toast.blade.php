<style>
    .xp-toast-stack {
        position: fixed;
        right: 22px;
        bottom: 22px;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 10px;
        pointer-events: none;
    }
    .xp-toast {
        min-width: 240px;
        max-width: 320px;
        padding: 14px 16px;
        border-radius: 16px;
        background: linear-gradient(135deg, #1a1635, #2a1d4a);
        color: #fff;
        box-shadow: 0 18px 40px -14px rgba(20, 10, 50, .6);
        border: 1px solid rgba(255, 255, 255, .12);
        font-family: inherit;
        animation: xpToastIn .4s cubic-bezier(.2, .85, .4, 1.15);
    }
    .xp-toast.leaving {
        animation: xpToastOut .35s ease forwards;
    }
    @keyframes xpToastIn {
        from { opacity: 0; transform: translateY(14px) scale(.96); }
        to { opacity: 1; transform: none; }
    }
    @keyframes xpToastOut {
        to { opacity: 0; transform: translateY(10px) scale(.96); }
    }
    .xp-toast-main {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .xp-toast-badge {
        display: grid;
        place-items: center;
        min-width: 46px;
        height: 34px;
        padding: 0 10px;
        border-radius: 10px;
        background: linear-gradient(135deg, #ffb703, #fb8500);
        color: #3b1f00;
        font-size: 14px;
        font-weight: 900;
        letter-spacing: .02em;
    }
    .xp-toast-title {
        font-size: 13px;
        font-weight: 800;
        line-height: 1.3;
    }
    .xp-toast-extra {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid rgba(255, 255, 255, .14);
        font-size: 12px;
        font-weight: 700;
        color: #ffd8a8;
    }
    .xp-toast-extra.level { color: #b9f6ca; }
    .xp-toast-extra svg { flex-shrink: 0; }
    @media (max-width: 560px) {
        .xp-toast-stack { right: 12px; left: 12px; bottom: 12px; }
        .xp-toast { max-width: none; }
    }
</style>

<div class="xp-toast-stack" id="xpToastStack" aria-live="polite"></div>

<script>
    (function () {
        const stack = document.getElementById('xpToastStack');
        const csrfToken = @json(csrf_token());
        const activityUrl = @json(route('activity.store'));

        const flameIcon = '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2s5 4.5 5 10a5 5 0 0 1-10 0c0-2 1-3.5 2-4.5.3 1.6 1.2 2.4 2 2.5C11 7 10 4 12 2z"/></svg>';
        const levelIcon = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><polyline points="17 11 12 6 7 11"/><polyline points="17 18 12 13 7 18"/></svg>';

        function extraRow(iconHtml, text, extraClass) {
            const row = document.createElement('div');
            row.className = 'xp-toast-extra' + (extraClass ? ' ' + extraClass : '');
            row.insertAdjacentHTML('beforeend', iconHtml);
            const label = document.createElement('span');
            label.textContent = text;
            row.appendChild(label);
            return row;
        }

        window.showXpToast = function (reward) {
            if (!reward || !stack) return;
            if (!reward.xp_gained && !reward.streak_extended) return;

            const toast = document.createElement('div');
            toast.className = 'xp-toast';

            const main = document.createElement('div');
            main.className = 'xp-toast-main';

            const badge = document.createElement('div');
            badge.className = 'xp-toast-badge';
            badge.textContent = '+' + reward.xp_gained + ' XP';

            const title = document.createElement('div');
            title.className = 'xp-toast-title';
            title.textContent = reward.xp_gained ? reward.label : 'Batas XP harian untuk aktivitas ini tercapai';

            main.appendChild(badge);
            main.appendChild(title);
            toast.appendChild(main);

            if (reward.streak_extended) {
                toast.appendChild(extraRow(flameIcon, 'Streak ' + reward.streak + ' hari berturut-turut!'));
            }
            if (reward.leveled_up) {
                toast.appendChild(extraRow(levelIcon, 'Naik ke Level ' + reward.level + ' · ' + reward.title, 'level'));
            }

            stack.appendChild(toast);
            setTimeout(function () {
                toast.classList.add('leaving');
                setTimeout(function () { toast.remove(); }, 380);
            }, reward.leveled_up ? 6500 : 4500);
        };

        window.reportActivity = function (action) {
            return fetch(activityUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ action: action })
            })
                .then(function (response) { return response.ok ? response.json() : null; })
                .then(function (reward) {
                    if (reward) window.showXpToast(reward);
                    return reward;
                })
                .catch(function () { return null; });
        };

        @if (session('xp_gain'))
            window.showXpToast(@json(session('xp_gain')));
        @endif
    })();
</script>

