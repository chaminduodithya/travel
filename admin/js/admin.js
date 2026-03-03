/* ═══════════════════════════════════════════════════════════════════════════
   travel. Admin — JavaScript
   ═══════════════════════════════════════════════════════════════════════════ */

'use strict';

/* ── Sidebar toggle ────────────────────────────────────────────────────────── */
(function initSidebar() {
    const sidebar = document.getElementById('sidebar');
    const topbar = document.getElementById('topbar');
    const main = document.getElementById('adminMain');
    const toggleBtn = document.getElementById('sidebarToggle');
    if (!sidebar || !toggleBtn) return;

    const MOBILE_BK = 900;

    function isMobile() { return window.innerWidth <= MOBILE_BK; }

    function collapse() {
        if (isMobile()) {
            sidebar.classList.remove('open');
            sidebar.classList.add('collapsed');
        } else {
            sidebar.classList.add('collapsed');
            topbar?.classList.add('full');
            main?.classList.add('full');
        }
    }

    function expand() {
        if (isMobile()) {
            sidebar.classList.add('open');
            sidebar.classList.remove('collapsed');
        } else {
            sidebar.classList.remove('collapsed');
            topbar?.classList.remove('full');
            main?.classList.remove('full');
        }
    }

    function isCollapsed() {
        return sidebar.classList.contains('collapsed') || !sidebar.classList.contains('open') && isMobile();
    }

    toggleBtn.addEventListener('click', () => {
        if (isMobile()) {
            sidebar.classList.contains('open') ? collapse() : expand();
        } else {
            isCollapsed() ? expand() : collapse();
        }
    });

    // Close sidebar on mobile when clicking outside
    document.addEventListener('click', e => {
        if (isMobile() && sidebar.classList.contains('open')) {
            if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                collapse();
            }
        }
    });

    // On resize, reset to default
    let resizeTimer;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            if (!isMobile()) {
                sidebar.classList.remove('open', 'collapsed');
                topbar?.classList.remove('full');
                main?.classList.remove('full');
            } else {
                sidebar.classList.remove('open');
            }
        }, 200);
    });

    // Mobile: start collapsed
    if (isMobile()) collapse();
})();

/* ── Live Clock in topbar ──────────────────────────────────────────────────── */
(function initClock() {
    const el = document.getElementById('topbarTime');
    if (!el) return;
    function tick() {
        const now = new Date();
        el.textContent = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    }
    tick();
    setInterval(tick, 1000);
})();

/* ── Page title in topbar ──────────────────────────────────────────────────── */
(function setPageTitle() {
    const titleEl = document.getElementById('pageTitle');
    if (!titleEl) return;
    const map = {
        'dashboard.php': 'Dashboard',
        'bookings.php': 'Bookings',
        'packages.php': 'Packages',
    };
    const page = window.location.pathname.split('/').pop();
    if (map[page]) titleEl.textContent = map[page];
})();

/* ── Animated counter for stat numbers ────────────────────────────────────── */
(function animateCounters() {
    const nums = document.querySelectorAll('.stat-num[data-target]');
    if (!nums.length) return;

    const ease = t => t < .5 ? 2 * t * t : -1 + (4 - 2 * t) * t;

    nums.forEach(el => {
        const target = parseInt(el.getAttribute('data-target'), 10);
        if (isNaN(target) || target === 0) return;
        const duration = 1000;
        const start = performance.now();
        function step(now) {
            const elapsed = now - start;
            const progress = Math.min(elapsed / duration, 1);
            el.textContent = Math.round(ease(progress) * target);
            if (progress < 1) requestAnimationFrame(step);
        }
        requestAnimationFrame(step);
    });
})();

/* ── Dismiss alerts ────────────────────────────────────────────────────────── */
document.querySelectorAll('.dismiss-alert').forEach(btn => {
    btn.addEventListener('click', () => {
        const alert = btn.closest('.alert');
        alert.style.opacity = '0';
        alert.style.transform = 'translateY(-6px)';
        alert.style.transition = 'all .25s ease';
        setTimeout(() => alert.remove(), 280);
    });
});

/* ── Auto-dismiss success alerts ──────────────────────────────────────────── */
document.querySelectorAll('.alert-success').forEach(el => {
    setTimeout(() => el.querySelector('.dismiss-alert')?.click(), 4000);
});

/* ── Confirm delete links ──────────────────────────────────────────────────── */
document.querySelectorAll('a[href*="delete="]').forEach(link => {
    if (link.getAttribute('onclick')) return; // already has one
    link.addEventListener('click', e => {
        if (!confirm('Are you sure you want to delete this item? This cannot be undone.')) {
            e.preventDefault();
        }
    });
});

/* ── Modal backdrop close ──────────────────────────────────────────────────── */
document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', e => {
        if (e.target === overlay) overlay.style.display = 'none';
    });
});

/* ── Keyboard: Escape closes modal ────────────────────────────────────────── */
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay').forEach(m => {
            if (m.style.display !== 'none') m.style.display = 'none';
        });
    }
});

/* ── Add subtle row hover highlight ───────────────────────────────────────── */
document.querySelectorAll('.admin-table tbody tr').forEach(row => {
    row.addEventListener('mouseenter', () => row.style.transition = 'background .15s');
});
