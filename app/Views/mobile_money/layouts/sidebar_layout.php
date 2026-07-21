<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Mobile Money') ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/css/mobile-money.css') ?>">
</head>
<body>
    <div class="page-shell page-with-sidebar">
        <aside class="sidebar">
            <div class="sidebar-brand">
                <p class="eyebrow">Mobile Money</p>
                <h2><?= esc($roleLabel ?? 'Tableau de bord') ?></h2>
            </div>
            <nav class="sidebar-nav">
                <?php if (($role ?? '') === 'client'): ?>
                    <a class="sidebar-link <?= ($page ?? '') === 'dashboard' ? 'active' : '' ?>" href="<?= base_url('mobile-money/client') ?>">Accueil</a>
                    <a class="sidebar-link <?= ($page ?? '') === 'history' ? 'active' : '' ?>" href="<?= base_url('mobile-money/client/history') ?>">Historique</a>
                    <a class="sidebar-link <?= ($page ?? '') === 'transfers' ? 'active' : '' ?>" href="<?= base_url('mobile-money/client/transfers') ?>">Transferts</a>
                <?php else: ?>
                    <a class="sidebar-link <?= ($page ?? '') === 'dashboard' ? 'active' : '' ?>" href="<?= base_url('mobile-money/operator') ?>">Vue d’ensemble</a>
                    <a class="sidebar-link <?= ($page ?? '') === 'prefixes' ? 'active' : '' ?>" href="<?= base_url('mobile-money/operator/prefixes') ?>">Préfixes</a>
                    <a class="sidebar-link <?= ($page ?? '') === 'operations' ? 'active' : '' ?>" href="<?= base_url('mobile-money/operator/operations') ?>">Opérations</a>
                    <a class="sidebar-link <?= ($page ?? '') === 'fees' ? 'active' : '' ?>" href="<?= base_url('mobile-money/operator/fees') ?>">Frais</a>
                    <a class="sidebar-link <?= ($page ?? '') === 'clients' ? 'active' : '' ?>" href="<?= base_url('mobile-money/operator/clients') ?>">Clients</a>
                    <a class="sidebar-link <?= ($page ?? '') === 'transactions' ? 'active' : '' ?>" href="<?= base_url('mobile-money/operator/transactions') ?>">Transactions</a>
                <?php endif; ?>
            </nav>
            <a class="btn btn-ghost sidebar-logout" href="<?= base_url('mobile-money/logout') ?>">Déconnexion</a>
        </aside>

        <main class="content-panel">
            <header class="topbar">
                <div>
                    <p class="eyebrow">Mobile Money</p>
                    <h1><?= esc($title ?? 'Tableau de bord') ?></h1>
                </div>
            </header>

            <?php if (session()->getFlashdata('success')): ?><div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div><?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?><div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div><?php endif; ?>

            <?= $this->renderSection('content') ?>
        </main>
    </div>
</body>
</html>
