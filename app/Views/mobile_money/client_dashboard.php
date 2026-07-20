<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord client</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .card { border: 1px solid #ddd; padding: 16px; border-radius: 10px; margin-bottom: 16px; }
        .grid { display: grid; gap: 16px; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .btn { display: inline-block; margin-top: 10px; padding: 8px 12px; text-decoration: none; background: #0d6efd; color: white; border-radius: 6px; }
    </style>
</head>
<body>
<h2>Tableau de bord client</h2>
<a class="btn" href="<?= base_url('mobile-money/logout') ?>">Déconnexion</a>
<?php if (session()->getFlashdata('success')): ?><p style="color:green"><?= esc(session()->getFlashdata('success')) ?></p><?php endif; ?>
<?php if (session()->getFlashdata('error')): ?><p style="color:red"><?= esc(session()->getFlashdata('error')) ?></p><?php endif; ?>
<div class="grid">
    <div class="card">
        <h3>Informations du client</h3>
        <p><strong>Nom :</strong> <?= esc($client['nom'] ?? '') ?></p>
        <p><strong>Téléphone :</strong> <?= esc($client['telephone'] ?? '') ?></p>
        <p><strong>Solde :</strong> <?= number_format((float) ($client['solde'] ?? 0), 2, ',', ' ') ?> Ar</p>
    </div>
    <div class="card">
        <h3>Dépôt</h3>
        <form method="post" action="<?= base_url('mobile-money/deposit') ?>">
            <input type="text" name="telephone" placeholder="Téléphone" required><br><br>
            <input type="number" name="montant" step="0.01" placeholder="Montant" required><br><br>
            <button type="submit">Effectuer un dépôt</button>
        </form>
    </div>
    <div class="card">
        <h3>Retrait</h3>
        <form method="post" action="<?= base_url('mobile-money/withdraw') ?>">
            <input type="text" name="telephone" placeholder="Téléphone" required><br><br>
            <input type="number" name="montant" step="0.01" placeholder="Montant" required><br><br>
            <button type="submit">Effectuer un retrait</button>
        </form>
    </div>
    <div class="card">
        <h3>Transfert</h3>
        <form method="post" action="<?= base_url('mobile-money/transfer') ?>">
            <input type="text" name="sender_telephone" placeholder="Votre téléphone" required><br><br>
            <input type="text" name="recipient_telephone" placeholder="Téléphone du destinataire" required><br><br>
            <input type="number" name="montant" step="0.01" placeholder="Montant" required><br><br>
            <button type="submit">Envoyer</button>
        </form>
    </div>
</div>
<div class="card">
    <h3>Historique</h3>
    <table>
        <tr><th>Date</th><th>Type</th><th>Montant</th><th>Frais</th></tr>
        <?php foreach ($transactions as $t): ?>
            <tr>
                <td><?= esc($t['date_transaction'] ?? '') ?></td>
                <td><?= esc($t['type_operation_id'] ?? '') ?></td>
                <td><?= number_format((float) ($t['montant'] ?? 0), 2, ',', ' ') ?></td>
                <td><?= number_format((float) ($t['frais'] ?? 0), 2, ',', ' ') ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>
