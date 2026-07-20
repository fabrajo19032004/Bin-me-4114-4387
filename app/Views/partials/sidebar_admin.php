<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <div class="nav-icon-circle" style="background:#fff;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2">
                <circle cx="12" cy="12" r="10" />
                <path d="M12 6v6l4 2" />
            </svg>
        </div>
        <span>Ecopanier</span>
    </div>

    <nav>
        <!-- Principal -->
        <a href="<?= base_url('admin/dashboard') ?>" class="nav-item <?= str_contains(current_url(), 'admin/dashboard') ? 'active' : '' ?>">
            <div class="nav-icon-circle">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <rect x="3" y="3" width="7" height="7" />
                    <rect x="14" y="3" width="7" height="7" />
                    <rect x="14" y="14" width="7" height="7" />
                    <rect x="3" y="14" width="7" height="7" />
                </svg>
            </div>
            <span class="nav-text">Dashboard</span>
        </a>

        <!-- Utilisateurs -->
        <a href="<?= base_url('admin/utilisateurs') ?>" class="nav-item <?= str_contains(current_url(), 'admin/utilisateurs') ? 'active' : '' ?>">
            <div class="nav-icon-circle">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                </svg>
            </div>
            <span class="nav-text">Utilisateurs</span>
        </a>

        <!-- Stock & Produits -->
        <a href="<?= base_url('admin/stocks') ?>" class="nav-item <?= str_contains(current_url(), 'admin/stocks') ? 'active' : '' ?>">
            <div class="nav-icon-circle">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <rect x="2" y="7" width="20" height="14" rx="2" />
                    <path d="M16 7V5a2 2 0 0 0-4 0v2" />
                </svg>
            </div>
            <span class="nav-text">Etat des stocks</span>
        </a>
        <a href="<?= base_url('admin/categories') ?>" class="nav-item <?= str_contains(current_url(), 'admin/categories') ? 'active' : '' ?>">
            <div class="nav-icon-circle">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z" />
                </svg>
            </div>
            <span class="nav-text">Categories</span>
        </a>
        <a href="<?= base_url('admin/produits') ?>" class="nav-item <?= str_contains(current_url(), 'admin/produits') ? 'active' : '' ?>">
            <div class="nav-icon-circle">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                </svg>
            </div>
            <span class="nav-text">Produits</span>
        </a>
        <a href="<?= base_url('admin/entrees_stock') ?>" class="nav-item <?= str_contains(current_url(), 'entrees-stock') ? 'active' : '' ?>">
            <div class="nav-icon-circle">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <polyline points="17 1 21 5 17 9" />
                    <path d="M3 11V9a4 4 0 0 1 4-4h14" />
                </svg>
            </div>
            <span class="nav-text">Entrees</span>
        </a>
        <a href="<?= base_url('admin/sorties_stock') ?>" class="nav-item <?= str_contains(current_url(), 'sorties-stock') ? 'active' : '' ?>">
            <div class="nav-icon-circle">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <polyline points="7 23 3 19 7 15" />
                    <path d="M21 13v2a4 4 0 0 1-4 4H3" />
                </svg>
            </div>
            <span class="nav-text">Sorties</span>
        </a>
        <a href="<?= base_url('admin/historique') ?>" class="nav-item <?= str_contains(current_url(), 'admin/historique') ? 'active' : '' ?>">
            <div class="nav-icon-circle">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <circle cx="12" cy="12" r="10" />
                    <polyline points="12 6 12 12 16 14" />
                </svg>
            </div>
            <span class="nav-text">Historique</span>
        </a>

        <!-- Commercial -->
        <a href="<?= base_url('admin/clients') ?>" class="nav-item <?= str_contains(current_url(), 'crm/clients') ? 'active' : '' ?>">
            <div class="nav-icon-circle">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                </svg>
            </div>
            <span class="nav-text">Clients</span>
        </a>
        <a href="<?= base_url('admin/reservations') ?>" class="nav-item <?= str_contains(current_url(), 'reservations') && !str_contains(current_url(), 'livreur') ? 'active' : '' ?>">
            <div class="nav-icon-circle">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <rect x="3" y="4" width="18" height="18" rx="2" />
                </svg>
            </div>
            <span class="nav-text">Reservations</span>
        </a>
        <a href="<?= base_url('admin/paiements') ?>" class="nav-item <?= str_contains(current_url(), 'paiements') ? 'active' : '' ?>">
            <div class="nav-icon-circle">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <rect x="1" y="4" width="22" height="16" rx="2" />
                </svg>
            </div>
            <span class="nav-text">Paiements</span>
        </a>

        <!-- Packs & Depenses -->
        <a href="<?= base_url('agent/packs') ?>" class="nav-item <?= str_contains(current_url(), '/packs') && !str_contains(current_url(), 'admin/packs') ? 'active' : '' ?>">
            <div class="nav-icon-circle">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M12 2l9 4.9V17L12 22 3 17V6.9L12 2z" />
                </svg>
            </div>
            <span class="nav-text">Packs</span>
        </a>
        <a href="<?= base_url('admin/depenses') ?>" class="nav-item <?= str_contains(current_url(), 'depenses') ? 'active' : '' ?>">
            <div class="nav-icon-circle">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <line x1="12" y1="1" x2="12" y2="23" />
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                </svg>
            </div>
            <span class="nav-text">Depenses</span>
        </a>

        <!-- Livreurs -->
        <a href="<?= base_url('livreur/reservations') ?>" class="nav-item <?= str_contains(current_url(), 'livreur/') ? 'active' : '' ?>">
            <div class="nav-icon-circle">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <rect x="1" y="3" width="15" height="13" />
                    <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
                </svg>
            </div>
            <span class="nav-text">Livraisons</span>
        </a>
    </nav>

    <div class="sidebar-toggle">
        <button class="toggle-btn" onclick="toggleSidebar()" title="Reduire le menu">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="m16 12c0 .552-.448 1-1 1s-1-.448-1-1 .448-1 1-1 1 .448 1 1zm-6 0c0 .552.448 1 1 1s1-.448 1-1-.448-1-1-1-1 .448-1 1zm9-1c-.552 0-1 .448-1 1s.448 1 1 1 1-.448 1-1-.448-1-1-1zm4 0c-.552 0-1 .448-1 1s.448 1 1 1 1-.448 1-1-.448-1-1-1zm-21.268 2.768c-.472-.473-.732-1.1-.732-1.768s.26-1.295.732-1.768l8.379-8.378c.195-.195.195-.512 0-.707s-.512-.195-.707 0l-8.379 8.378c-.661.661-1.025 1.54-1.025 2.475s.364 1.813 1.025 2.475l8.378 8.379c.195.195.512.195.707 0s.195-.512 0-.707zm11.25-9.993c-.195-.195-.512-.195-.707 0l-6.596 6.596c-.898.898-.898 2.36 0 3.258l6.596 6.596c.195.195.512.195.707 0s.195-.512 0-.707l-6.596-6.596c-.508-.508-.508-1.335 0-1.844l6.596-6.596c.195-.195.195-.512 0-.707z" />
            </svg>
        </button>
    </div>

    <div class="sidebar-footer">
        <a href="<?= base_url('auth/logout') ?>">
            <div class="nav-icon-circle" style="width:30px;height:30px;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                    <polyline points="16 17 21 12 16 7" />
                    <line x1="21" y1="12" x2="9" y2="12" />
                </svg>
            </div>
            <span>Deconnexion</span>
        </a>
    </div>
</aside>

<script>
    function toggleSidebar() {
        const s = document.getElementById('sidebar');
        s.classList.toggle('collapsed');
        localStorage.setItem('sidebarCollapsed', s.classList.contains('collapsed'));
    }
    document.addEventListener('DOMContentLoaded', function() {
        if (localStorage.getItem('sidebarCollapsed') === 'true') {
            document.getElementById('sidebar').classList.add('collapsed');
        }
    });
</script>