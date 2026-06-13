<?php
$role = session()->get('role');
$nama = session()->get('nama_lengkap') ?? 'User';
$initial = strtoupper(substr($nama, 0, 1));
$defaultState = (isset($_COOKIE['sidebar_state']) && in_array($_COOKIE['sidebar_state'], ['expanded','mini','hidden'])) ? $_COOKIE['sidebar_state'] : 'mini';
if (isset($_COOKIE['sidebar_overridden'])) $defaultState = 'mini';
$isMobile = preg_match('/Mobile|Android|iPhone|iPad/i', $_SERVER['HTTP_USER_AGENT'] ?? '');
$initOffset = ($defaultState === 'mini' && !$isMobile) ? 64 : 0;
?>
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="hamburger-btn <?= ($defaultState === 'hidden') ? 'visible' : '' ?>" id="hamburgerBtn">
    <i class="fas fa-bars"></i>
</div>

<div class="sidebar <?= $defaultState ?>" id="sidebar" data-state="<?= $defaultState ?>">

    <div class="sidebar-brand">
        <div class="brand-icon">
            <i class="fas fa-clipboard-list"></i>
        </div>
        <div class="brand-text">
            <span class="brand-name">SIMKIN</span>
            <span class="brand-sub">UIN Salatiga</span>
        </div>
    </div>

    <div class="sidebar-user">
        <div class="avatar"><?= $initial ?></div>
        <div class="user-info">
            <span class="user-name"><?= esc($nama) ?></span>
            <span class="user-role"><?= esc($role) ?></span>
        </div>
    </div>

    <div class="sidebar-toggle" id="sidebarToggle">
        <i class="fas fa-chevron-left" id="toggleIcon"></i>
    </div>

    <ul class="sidebar-nav">

        <li class="nav-section">Utama</li>

        <li class="nav-item">
            <a href="<?= base_url('/dashboard') ?>" class="nav-link <?= ($active_menu == 'dashboard') ? 'active' : '' ?>">
                <i class="fas fa-tachometer-alt"></i>
                <span class="nav-label">Dashboard</span>
            </a>
        </li>

        <?php if (in_array($role, ['admin_perencana', 'super_admin'])): ?>
            <li class="nav-item">
                <a href="<?= base_url('/periode') ?>" class="nav-link <?= ($active_menu == 'periode') ? 'active' : '' ?>">
                    <i class="fas fa-calendar-alt"></i>
                    <span class="nav-label">Periode</span>
                </a>
            </li>
        <?php endif; ?>

        <li class="nav-section">Kinerja</li>

        <li class="nav-item">
            <a href="<?= base_url('/skp') ?>" class="nav-link <?= ($active_menu == 'skp') ? 'active' : '' ?>">
                <i class="fas fa-file-alt"></i>
                <span class="nav-label">SKP / RHK</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?= base_url('/realisasi') ?>" class="nav-link <?= ($active_menu == 'realisasi') ? 'active' : '' ?>">
                <i class="fas fa-chart-line"></i>
                <span class="nav-label">Realisasi</span>
            </a>
        </li>

        <?php if (in_array($role, ['rektor', 'dekan', 'kaprodi', 'super_admin'])): ?>
            <li class="nav-section">Persetujuan</li>

            <li class="nav-item">
                <a href="<?= base_url('/approval/skp') ?>" class="nav-link <?= ($active_menu == 'approval_skp') ? 'active' : '' ?>">
                    <i class="fas fa-check-double"></i>
                    <span class="nav-label">Persetujuan SKP</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="<?= base_url('/approval/realisasi') ?>" class="nav-link <?= ($active_menu == 'approval_realisasi') ? 'active' : '' ?>">
                    <i class="fas fa-clipboard-check"></i>
                    <span class="nav-label">Persetujuan Realisasi</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="<?= base_url('/penilaian') ?>" class="nav-link <?= ($active_menu == 'penilaian') ? 'active' : '' ?>">
                    <i class="fas fa-star"></i>
                    <span class="nav-label">Penilaian SKP</span>
                </a>
            </li>
        <?php endif; ?>

        <li class="nav-section">Laporan</li>

        <li class="nav-item">
            <a href="<?= base_url('/laporan/skp') ?>" class="nav-link <?= ($active_menu == 'laporan_skp') ? 'active' : '' ?>">
                <i class="fas fa-file-alt"></i>
                <span class="nav-label">Laporan SKP</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?= base_url('/laporan/realisasi') ?>" class="nav-link <?= ($active_menu == 'laporan_realisasi') ? 'active' : '' ?>">
                <i class="fas fa-chart-bar"></i>
                <span class="nav-label">Laporan Realisasi</span>
            </a>
        </li>

        <li class="nav-section">Pengaturan</li>

        <li class="nav-item">
            <a href="<?= base_url('/profil') ?>" class="nav-link <?= ($active_menu == 'profil') ? 'active' : '' ?>">
                <i class="fas fa-user"></i>
                <span class="nav-label">Profil</span>
            </a>
        </li>

        <?php if ($role == 'super_admin'): ?>
            <li class="nav-item">
                <a href="<?= base_url('/user') ?>" class="nav-link <?= ($active_menu == 'user') ? 'active' : '' ?>">
                    <i class="fas fa-users"></i>
                    <span class="nav-label">Manajemen User</span>
                </a>
            </li>
        <?php endif; ?>

        <li class="nav-spacer"></li>

        <li class="nav-item">
            <a href="<?= base_url('/logout') ?>" class="nav-link logout-link">
                <i class="fas fa-sign-out-alt"></i>
                <span class="nav-label">Logout</span>
            </a>
        </li>

    </ul>

    <div class="sidebar-footer">
        <div class="sidebar-version">SIMKIN v1.0</div>
    </div>

</div>

<style>
:root {
    --sidebar-bg: #111827;
    --sidebar-hover: #1e293b;
    --sidebar-active: #1e3a5f;
    --sidebar-accent: #2563eb;
    --sidebar-text: #94a3b8;
    --sidebar-text-active: #ffffff;
    --sidebar-width: 260px;
    --sidebar-mini-width: 64px;
    --sidebar-brand-height: 64px;
    --sidebar-transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    --sidebar-offset: <?= $initOffset ?>px;
}

/* Adjust content to account for sidebar */
.content-wrapper {
    margin-left: var(--sidebar-offset);
    transition: margin-left var(--sidebar-transition);
}
@media (max-width: 768px) {
    .content-wrapper {
        margin-left: 0 !important;
    }
}

/* ── Hamburger Button ── */
.hamburger-btn {
    position: fixed;
    top: 12px;
    left: 14px;
    z-index: 1060;
    width: 36px;
    height: 36px;
    display: none;
    align-items: center;
    justify-content: center;
    background: #1e293b;
    color: #e2e8f0;
    border-radius: 8px;
    cursor: pointer;
    font-size: 16px;
    transition: var(--sidebar-transition);
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}
.hamburger-btn:hover {
    background: #334155;
    color: #ffffff;
}
.hamburger-btn.visible {
    display: flex;
}

/* ── Backdrop Overlay ── */
.sidebar-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1045;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s ease;
}
.sidebar-overlay.show {
    opacity: 1;
    pointer-events: auto;
}

/* ── Sidebar Base ── */
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    width: var(--sidebar-width);
    background: var(--sidebar-bg);
    z-index: 1050;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    transition: transform var(--sidebar-transition), width var(--sidebar-transition);
    transform: translateX(0);
    box-shadow: 2px 0 12px rgba(0,0,0,0.15);
}

.sidebar::-webkit-scrollbar { width: 4px; }
.sidebar::-webkit-scrollbar-track { background: transparent; }
.sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.08); border-radius: 4px; }

/* ── Mini Mode ── */
.sidebar.mini {
    width: var(--sidebar-mini-width);
    transform: translateX(0);
}
.sidebar.mini .sidebar-brand .brand-text,
.sidebar.mini .sidebar-user .user-info,
.sidebar.mini .nav-label,
.sidebar.mini .nav-section,
.sidebar.mini .sidebar-footer,
.sidebar.mini .sidebar-version {
    opacity: 0;
    pointer-events: none;
    width: 0;
    overflow: hidden;
}
.sidebar.mini .nav-link {
    justify-content: center;
    padding: 10px 0;
    gap: 0;
}
.sidebar.mini .nav-link i {
    margin: 0;
    font-size: 18px;
}
.sidebar.mini .sidebar-brand {
    justify-content: center;
    padding: 14px 0;
}
.sidebar.mini .sidebar-user {
    justify-content: center;
    padding: 12px 0;
}
.sidebar.mini .sidebar-user .avatar {
    margin: 0;
}
.sidebar.mini .sidebar-toggle {
    right: -14px;
}
.sidebar.mini .sidebar-toggle i {
    transform: rotate(180deg);
}

/* ── Mini Hover Expand ── */
.sidebar.mini:hover {
    width: var(--sidebar-width);
    box-shadow: 4px 0 20px rgba(0,0,0,0.25);
}
.sidebar.mini:hover .sidebar-brand .brand-text,
.sidebar.mini:hover .sidebar-user .user-info,
.sidebar.mini:hover .nav-label,
.sidebar.mini:hover .nav-section,
.sidebar.mini:hover .sidebar-footer,
.sidebar.mini:hover .sidebar-version {
    opacity: 1;
    pointer-events: auto;
    width: auto;
    overflow: visible;
}
.sidebar.mini:hover .nav-link {
    justify-content: flex-start;
    padding: 10px 14px;
    gap: 12px;
}
.sidebar.mini:hover .nav-link i {
    font-size: 15px;
    margin: 0;
}
.sidebar.mini:hover .sidebar-brand {
    justify-content: flex-start;
    padding: 18px 20px;
}
.sidebar.mini:hover .sidebar-user {
    justify-content: flex-start;
    padding: 14px 20px;
}
.sidebar.mini:hover .sidebar-toggle {
    right: 0;
}
.sidebar.mini:hover .sidebar-toggle i {
    transform: rotate(0deg);
}

/* ── Hidden Mode ── */
.sidebar.hidden {
    width: var(--sidebar-width);
    transform: translateX(-100%);
    box-shadow: none;
}
.sidebar.hidden + .hamburger-btn {
    display: flex;
}

/* ── Brand ── */
.sidebar-brand {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 18px 20px;
    min-height: var(--sidebar-brand-height);
    border-bottom: 1px solid rgba(255,255,255,0.06);
    flex-shrink: 0;
    transition: padding 0.3s ease;
}
.brand-icon {
    width: 38px;
    height: 38px;
    background: linear-gradient(135deg, #1e3a5f, #2563eb);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(37,99,235,0.3);
}
.brand-icon i { font-size: 18px; color: #fff; }
.brand-text { display: flex; flex-direction: column; transition: opacity 0.2s ease; }
.brand-name { font-size: 16px; font-weight: 700; color: #fff; line-height: 1.2; }
.brand-sub { font-size: 10px; color: #64748b; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; }

/* ── Toggle Button ── */
.sidebar-toggle {
    position: absolute;
    top: 50%;
    right: 0;
    transform: translateY(-50%);
    width: 20px;
    height: 36px;
    background: #1e293b;
    border: 1px solid rgba(255,255,255,0.08);
    border-right: none;
    border-radius: 6px 0 0 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 10;
    transition: right 0.3s ease;
    color: #64748b;
    font-size: 11px;
}
.sidebar-toggle:hover {
    background: #334155;
    color: #e2e8f0;
}

/* ── User ── */
.sidebar-user {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px 20px;
    border-bottom: 1px solid rgba(255,255,255,0.06);
    flex-shrink: 0;
    transition: padding 0.3s ease;
}
.avatar {
    width: 34px; height: 34px; border-radius: 10px;
    background: linear-gradient(135deg, #1e3a5f, #334155);
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; font-weight: 700; color: #fff; flex-shrink: 0;
}
.user-info { display: flex; flex-direction: column; overflow: hidden; transition: opacity 0.2s ease; }
.user-name { font-size: 13px; font-weight: 600; color: #f1f5f9; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.user-role { font-size: 11px; color: #64748b; text-transform: capitalize; }

/* ── Nav ── */
.sidebar-nav {
    padding: 8px 10px;
    flex: 1;
    display: flex;
    flex-direction: column;
    overflow-y: auto;
    overflow-x: hidden;
}
.nav-section {
    font-size: 10px; font-weight: 700; text-transform: uppercase;
    letter-spacing: 1.2px; color: #475569;
    padding: 16px 12px 6px;
    white-space: nowrap;
    overflow: hidden;
    transition: opacity 0.2s ease;
}
.nav-item { list-style: none; margin: 1px 0; flex-shrink: 0; }
.nav-link {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 14px;
    color: var(--sidebar-text);
    text-decoration: none;
    border-radius: 8px;
    font-size: 13.5px;
    font-weight: 500;
    transition: all 0.2s ease, padding 0.3s ease, gap 0.3s ease;
    white-space: nowrap;
    overflow: hidden;
}
.nav-link i {
    width: 18px; text-align: center; font-size: 15px; flex-shrink: 0;
    transition: font-size 0.3s ease;
}
.nav-link .nav-label { flex: 1; transition: opacity 0.2s ease; }
.nav-link:hover { background: var(--sidebar-hover); color: #e2e8f0; }
.nav-link:hover i { color: #60a5fa; }
.nav-link.active { background: var(--sidebar-active); color: var(--sidebar-text-active); box-shadow: inset 3px 0 0 var(--sidebar-accent); }
.nav-link.active i { color: var(--sidebar-accent); }
.nav-link.logout-link { color: #f87171; }
.nav-link.logout-link:hover { background: rgba(248,113,113,0.1); color: #ef4444; }
.nav-link.logout-link:hover i { color: #ef4444; }
.nav-spacer { flex: 1; }

/* ── Footer ── */
.sidebar-footer {
    padding: 12px 16px;
    border-top: 1px solid rgba(255,255,255,0.06);
    flex-shrink: 0;
    transition: opacity 0.2s ease;
}
.sidebar-version { font-size: 10px; color: #475569; text-align: center; }

/* ── Responsive ── */
@media (max-width: 768px) {
    .sidebar:not(.hidden) + .sidebar-overlay {
        opacity: 1;
        pointer-events: auto;
    }
    .sidebar.expanded,
    .sidebar.mini {
        transform: translateX(0);
        width: var(--sidebar-width);
    }
    .sidebar.mini:hover {
        width: var(--sidebar-width);
    }
    .sidebar.mini .sidebar-brand .brand-text,
    .sidebar.mini .sidebar-user .user-info,
    .sidebar.mini .nav-label,
    .sidebar.mini .nav-section,
    .sidebar.mini .sidebar-footer {
        opacity: 1;
        pointer-events: auto;
        width: auto;
        overflow: visible;
    }
    .sidebar.mini .nav-link {
        justify-content: flex-start;
        padding: 10px 14px;
        gap: 12px;
    }
    .sidebar.mini .nav-link i { font-size: 15px; }
    .sidebar.mini .sidebar-brand { justify-content: flex-start; padding: 18px 20px; }
    .sidebar.mini .sidebar-user { justify-content: flex-start; padding: 14px 20px; }
    .sidebar.mini .sidebar-toggle { right: 0; }
    .sidebar.mini .sidebar-toggle i { transform: rotate(0deg); }
    .sidebar.mini .sidebar-user .avatar { margin: 0; }
}
</style>

<script>
(function() {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');
    const toggleIcon = document.getElementById('toggleIcon');
    const hamburger = document.getElementById('hamburgerBtn');
    const overlay = document.getElementById('sidebarOverlay');
    const isMobile = window.innerWidth <= 768;

    function getDefaultState() {
        const saved = getCookie('sidebar_state');
        if (saved && ['expanded','mini','hidden'].includes(saved)) return saved;
        return isMobile ? 'hidden' : 'mini';
    }

    function setState(state) {
        sidebar.className = 'sidebar ' + state;
        sidebar.dataset.state = state;
        setCookie('sidebar_state', state, 365);
        updateUI(state);
        updateContentOffset(state);
    }

    function updateUI(state) {
        if (state === 'expanded') {
            toggleIcon.className = 'fas fa-chevron-left';
            hamburger.classList.remove('visible');
            overlay.classList.remove('show');
        } else if (state === 'mini') {
            toggleIcon.className = 'fas fa-chevron-right';
            hamburger.classList.remove('visible');
            if (isMobile) overlay.classList.add('show');
            else overlay.classList.remove('show');
        } else {
            toggleIcon.className = 'fas fa-chevron-right';
            hamburger.classList.add('visible');
            overlay.classList.remove('show');
        }
    }

    function updateContentOffset(state) {
        const offset = (state === 'mini' && !isMobile) ? 64 : 0;
        document.documentElement.style.setProperty('--sidebar-offset', offset + 'px');
    }

    function nextState(state) {
        const map = { expanded: 'mini', mini: 'hidden', hidden: 'expanded' };
        return map[state] || 'mini';
    }

    toggleBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        const current = sidebar.dataset.state || 'mini';
        setState(nextState(current));
    });

    hamburger.addEventListener('click', function() {
        setState('expanded');
    });

    overlay.addEventListener('click', function() {
        if (isMobile) setState('hidden');
        else setState('mini');
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const state = sidebar.dataset.state;
            if (state === 'expanded') setState(isMobile ? 'hidden' : 'mini');
            else if (state === 'mini' && isMobile) setState('hidden');
        }
    });

    // Close on click outside (desktop only)
    document.addEventListener('click', function(e) {
        if (isMobile) return;
        const state = sidebar.dataset.state;
        if (state !== 'expanded') return;
        const target = e.target;
        if (!sidebar.contains(target) && !hamburger.contains(target)) {
            setState('mini');
        }
    });

    // Init
    const initialState = getDefaultState();
    setState(initialState);

    // Mini mode hover expand
    if (!isMobile) {
        let hoverTimer = null;
        sidebar.addEventListener('mouseenter', function() {
            if (sidebar.dataset.state === 'mini') {
                sidebar.classList.add('hover-expand');
            }
        });
        sidebar.addEventListener('mouseleave', function() {
            if (sidebar.dataset.state === 'mini') {
                sidebar.classList.remove('hover-expand');
            }
        });
    }

    // Responsive resize
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            const mobile = window.innerWidth <= 768;
            if (mobile !== isMobile) {
                location.reload();
            }
        }, 300);
    });

    function setCookie(name, value, days) {
        const d = new Date();
        d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000));
        document.cookie = name + '=' + value + ';expires=' + d.toUTCString() + ';path=/';
    }
    function getCookie(name) {
        const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
        return match ? match[2] : null;
    }
})();
</script>
