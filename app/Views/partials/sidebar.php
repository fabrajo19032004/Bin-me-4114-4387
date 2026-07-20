<?php
$currentRoute = $currentRoute ?? service('uri')->getPath();
$active = static function (string $route) use ($currentRoute): bool {
    return str_contains($currentRoute, $route);
};
?>

<style>
    .app-shell {
        display: flex;
        min-height: 100vh;
        background: #f4f7fb;
    }

    .sidebar {
        width: 260px;
        background: linear-gradient(180deg, #0f172a 0%, #111827 100%);
        color: #fff;
        padding: 24px 18px;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .sidebar.collapsed {
        width: 86px;
    }

    .sidebar-logo {
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 700;
        font-size: 1.1rem;
        letter-spacing: .02em;
    }

    .sidebar-logo svg {
        width: 20px;
        height: 20px;
        color: #fff;
    }

    .sidebar-nav {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .sidebar-nav a {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        border-radius: 10px;
        color: #cbd5e1;
        text-decoration: none;
        transition: all .2s ease;
    }

    .sidebar-nav a:hover,
    .sidebar-nav a.active {
        background: rgba(255,255,255,.12);
        color: #fff;
    }

    .sidebar .sidebar-footer {
        margin-top: auto;
        padding-top: 16px;
        border-top: 1px solid rgba(255,255,255,.12);
    }

    .main-content {
        flex: 1;
        padding: 20px;
    }

    .sidebar .nav-text {
        white-space: nowrap;
    }

    .sidebar.collapsed .nav-text,
    .sidebar.collapsed .sidebar-logo span {
        display: none;
    }
</style>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10" />
            <path d="M12 6v6l4 2" />
        </svg>
        <span>Ecopanier</span>
    </div>

    <nav class="sidebar-nav">
        <a href="<?= base_url('operator/prefixes') ?>" class="<?= $active('operator/prefixes') ? 'active' : '' ?>">
            <span>🔢</span>
            <span class="nav-text">Préfixes</span>
        </a>
        <a href="<?= base_url('operator/types') ?>" class="<?= $active('operator/types') ? 'active' : '' ?>">
            <span>🧾</span>
            <span class="nav-text">Types</span>
        </a>
        <a href="<?= base_url('operator/baremes') ?>" class="<?= $active('operator/baremes') ? 'active' : '' ?>">
            <span>📊</span>
            <span class="nav-text">Barèmes</span>
        </a>
        <a href="<?= base_url('operator/gains') ?>" class="<?= $active('operator/gains') ? 'active' : '' ?>">
            <span>💰</span>
            <span class="nav-text">Gains</span>
        </a>
        <a href="<?= base_url('operator/comptes') ?>" class="<?= $active('operator/comptes') ? 'active' : '' ?>">
            <span>👥</span>
            <span class="nav-text">Comptes</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <a href="<?= base_url('auth/logout') ?>" class="<?= $active('auth/logout') ? 'active' : '' ?>">
            <span>↩</span>
            <span class="nav-text">Déconnexion</span>
        </a>
    </div>
</aside>