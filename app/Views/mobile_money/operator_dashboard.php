<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord opérateur</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .card { border: 1px solid #ddd; padding: 16px; border-radius: 10px; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .btn { display: inline-block; margin-bottom: 10px; padding: 8px 12px; text-decoration: none; background: #198754; color: white; border-radius: 6px; }
    </style>
</head>
<body>
<h2>Tableau de bord opérateur</h2>
<a class="btn" href="<?= base_url('mobile-money/logout') ?>">Déconnexion</a>
<p>Cette vue est réservée à l’opérateur : vous gérez les préfixes, les opérations et les barèmes.</p>
<div class="card">
    <h3>Préfixes autorisés</h3>
    <form method="post" action="<?= base_url('mobile-money/operator/prefixes') ?>">
        <input type="text" name="prefixe" placeholder="Ex: 033" required>
        <button type="submit">Ajouter</button>
    </form>
    <ul>
        <?php foreach ($prefixes as $prefix): ?>
            <li><?= esc($prefix['prefixe']) ?> <a href="<?= base_url('mobile-money/operator/prefixes/delete/' . $prefix['id']) ?>">Supprimer</a></li>
        <?php endforeach; ?>
    </ul>
</div>
<div class="card">
    <h3>Types d’opérations</h3>
    <form method="post" action="<?= base_url('mobile-money/operator/operations') ?>">
        <input type="text" name="nom" placeholder="Ex: Depot" required>
        <button type="submit">Ajouter</button>
    </form>
    <ul>
        <?php foreach ($operations as $operation): ?>
            <li><?= esc($operation['nom']) ?> <a href="<?= base_url('mobile-money/operator/operations/delete/' . $operation['id']) ?>">Supprimer</a></li>
        <?php endforeach; ?>
    </ul>
</div>
<div class="card">
    <h3>Barèmes de frais</h3>
    <form method="post" action="<?= base_url('mobile-money/operator/fees') ?>">
        <input type="number" name="type_operation_id" placeholder="ID type" required>
        <input type="number" name="montant_min" step="0.01" placeholder="Montant min" required>
        <input type="number" name="montant_max" step="0.01" placeholder="Montant max" required>
        <input type="number" name="frais" step="0.01" placeholder="Frais" required>
        <button type="submit">Ajouter</button>
    </form>
    <ul>
        <?php foreach ($fees as $fee): ?>
            <li>Type <?= esc($fee['type_operation_id']) ?> : <?= esc($fee['montant_min']) ?> → <?= esc($fee['montant_max']) ?> : <?= esc($fee['frais']) ?> <a href="<?= base_url('mobile-money/operator/fees/delete/' . $fee['id']) ?>">Supprimer</a></li>
        <?php endforeach; ?>
    </ul>
</div>
<div class="card">
    <h3>Clients</h3>
    <table>
        <tr><th>ID</th><th>Nom</th><th>Téléphone</th><th>Solde</th></tr>
        <?php foreach ($clients as $client): ?>
            <tr>
                <td><?= esc($client['id']) ?></td>
                <td><?= esc($client['nom'] ?? '') ?></td>
                <td><?= esc($client['telephone'] ?? '') ?></td>
                <td><?= number_format((float) ($client['solde'] ?? 0), 2, ',', ' ') ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
<div class="card">
    <h3>Transactions</h3>
    <table>
        <tr><th>ID</th><th>Type</th><th>Montant</th><th>Frais</th><th>Date</th></tr>
        <?php foreach ($transactions as $t): ?>
            <tr>
                <td><?= esc($t['id']) ?></td>
                <td><?= esc($t['type_operation_id']) ?></td>
                <td><?= number_format((float) ($t['montant'] ?? 0), 2, ',', ' ') ?></td>
                <td><?= number_format((float) ($t['frais'] ?? 0), 2, ',', ' ') ?></td>
                <td><?= esc($t['date_transaction'] ?? '') ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>
