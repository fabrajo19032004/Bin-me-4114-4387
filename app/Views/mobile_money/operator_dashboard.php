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
