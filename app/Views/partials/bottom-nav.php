<!-- ===== NAVIGATION FLOTTANTE EN BAS ===== -->
<nav class="bottom-nav" role="navigation" aria-label="Navigation principale">
    <div class="slider"></div>

    <a href="<?= base_url('agent/dashboard') ?>" class="nav-item <?= isset($activeNav) && $activeNav === 'dashboard' ? 'active' : '' ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="3" width="7" height="7" rx="1" />
            <rect x="14" y="3" width="7" height="7" rx="1" />
            <rect x="14" y="14" width="7" height="7" rx="1" />
            <rect x="3" y="14" width="7" height="7" rx="1" />
        </svg>
        <span class="nav-label">Dashboard</span>
    </a>

    <a href="<?= base_url('crm/clients') ?>" class="nav-item <?= isset($activeNav) && $activeNav === 'clients' ? 'active' : '' ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
            <circle cx="9" cy="7" r="4" />
            <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
        </svg>
        <span class="nav-label">Clients</span>
    </a>

    <a href="<?= base_url('admin/stocks') ?>" class="nav-item <?= isset($activeNav) && $activeNav === 'stocks' ? 'active' : '' ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="2" y="7" width="20" height="14" rx="2" />
            <path d="M16 7V5a2 2 0 0 0-4 0v2" />
        </svg>
        <span class="nav-label">Stocks</span>
    </a>

    <a href="<?= base_url('agent/packs') ?>" class="nav-item <?= isset($activeNav) && $activeNav === 'packs' ? 'active' : '' ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M12 2l9 4.9V17L12 22 3 17V6.9L12 2z" />
            <polyline points="12 22 12 12" />
            <polyline points="3 6.9 12 12 21 6.9" />
        </svg>
        <span class="nav-label">Packs</span>
    </a>

    <a href="<?= base_url('agent/reservations') ?>" class="nav-item <?= isset($activeNav) && $activeNav === 'reservations' ? 'active' : '' ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
            <line x1="16" y1="2" x2="16" y2="6" />
            <line x1="8" y1="2" x2="8" y2="6" />
            <line x1="3" y1="10" x2="21" y2="10" />
        </svg>
        <span class="nav-label">Réservations</span>
    </a>

    <a href="<?= base_url('agent/paiements') ?>" class="nav-item <?= isset($activeNav) && $activeNav === 'paiements' ? 'active' : '' ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="1" y="4" width="22" height="16" rx="2" ry="2" />
            <line x1="1" y1="10" x2="23" y2="10" />
        </svg>
        <span class="nav-label">Paiements</span>
    </a>

    <a href="<?= base_url('agent/packs/archives') ?>" class="nav-item <?= isset($activeNav) && $activeNav === 'archives' ? 'active' : '' ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="4" width="18" height="4" rx="1" />
            <path d="M5 8v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8" />
            <path d="M10 13h4" />
        </svg>
        <span class="nav-label">Archives</span>
    </a>
</nav>