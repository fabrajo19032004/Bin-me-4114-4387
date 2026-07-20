<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord opérateur</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/mobile-money.css') ?>">
</head>
<body>
<div class="page-shell">
    <header class="topbar">
        <div>
            <p class="eyebrow">Mobile Money</p>
            <h1>Tableau de bord opérateur</h1>
        </div>
        <a class="btn btn-ghost" href="<?= base_url('mobile-money/logout') ?>">Déconnexion</a>
    </header>

    <?php if (session()->getFlashdata('success')): ?><div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div><?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?><div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div><?php endif; ?>

    <section class="card">
        <div class="section-title">
            <h3>Préfixes autorisés</h3>
            <span>Gérez les numéros acceptés</span>
        </div>
        <form class="form-inline" method="post" action="<?= base_url('mobile-money/operator/prefixes') ?>">
            <input type="text" name="prefixe" placeholder="Ex: 033" required>
            <button class="btn btn-primary" type="submit">Ajouter</button>
        </form>
        <ul class="list-stack">
            <?php foreach ($prefixes as $prefix): ?>
                <li><?= esc($prefix['prefixe']) ?> <a href="<?= base_url('mobile-money/operator/prefixes/delete/' . $prefix['id']) ?>">Supprimer</a></li>
            <?php endforeach; ?>
        </ul>
    </section>

    <section class="card">
        <div class="section-title">
            <h3>Types d’opérations</h3>
            <span>Définissez les opérations disponibles</span>
        </div>
        <form class="form-inline" method="post" action="<?= base_url('mobile-money/operator/operations') ?>">
            <input type="text" name="nom" placeholder="Ex: Depot" required>
            <button class="btn btn-primary" type="submit">Ajouter</button>
        </form>
        <ul class="list-stack">
            <?php foreach ($operations as $operation): ?>
                <li><?= esc($operation['nom']) ?> <a href="<?= base_url('mobile-money/operator/operations/delete/' . $operation['id']) ?>">Supprimer</a></li>
            <?php endforeach; ?>
        </ul>
    </section>

    <section class="card">
        <div class="section-title">
            <h3>Barèmes de frais</h3>
            <span>Ajoutez les tranches de frais</span>
        </div>
        <form class="form-inline form-grid" method="post" action="<?= base_url('mobile-money/operator/fees') ?>">
            <input type="number" name="type_operation_id" placeholder="ID type" required>
            <input type="number" name="montant_min" step="0.01" placeholder="Montant min" required>
            <input type="number" name="montant_max" step="0.01" placeholder="Montant max" required>
            <input type="number" name="frais" step="0.01" placeholder="Frais" required>
            <button class="btn btn-primary" type="submit">Ajouter</button>
        </form>
        <ul class="list-stack">
            <?php foreach ($fees as $fee): ?>
                <li>Type <?= esc($fee['type_operation_id']) ?> : <?= esc($fee['montant_min']) ?> → <?= esc($fee['montant_max']) ?> : <?= esc($fee['frais']) ?> <a href="<?= base_url('mobile-money/operator/fees/delete/' . $fee['id']) ?>">Supprimer</a></li>
            <?php endforeach; ?>
        </ul>
    </section>

    <section class="card">
        <div class="section-title">
            <h3>Clients</h3>
            <span>Situation des comptes</span>
        </div>
        <table>
            <thead><tr><th>ID</th><th>Nom</th><th>Téléphone</th><th>Solde</th></tr></thead>
            <tbody>
                <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><?= esc($client['id']) ?></td>
                        <td><?= esc($client['nom'] ?? '') ?></td>
                        <td><?= esc($client['telephone'] ?? '') ?></td>
                        <td><?= number_format((float) ($client['solde'] ?? 0), 2, ',', ' ') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <section class="card">
        <div class="section-title">
            <h3>Transactions</h3>
            <span>Historique global</span>
        </div>
        <table>
            <thead><tr><th>ID</th><th>Type</th><th>Montant</th><th>Frais</th><th>Date</th></tr></thead>
            <tbody>
                <?php foreach ($transactions as $t): ?>
                    <tr>
                        <td><?= esc($t['id']) ?></td>
                        <td><?= esc($t['type_operation_id']) ?></td>
                        <td><?= number_format((float) ($t['montant'] ?? 0), 2, ',', ' ') ?></td>
                        <td><?= number_format((float) ($t['frais'] ?? 0), 2, ',', ' ') ?></td>
                        <td><?= esc($t['date_transaction'] ?? '') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</div>
</body>
</html>
