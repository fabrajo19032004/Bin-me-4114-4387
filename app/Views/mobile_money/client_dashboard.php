<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord client</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/mobile-money.css') ?>">
</head>

<body>
    <div class="page-shell">
        <header class="topbar">
            <div>
                <p class="eyebrow">Mobile Money</p>
                <h1>Tableau de bord client</h1>
            </div>
            <a class="btn btn-ghost" href="<?= base_url('mobile-money/logout') ?>">Déconnexion</a>
        </header>

        <?php if (session()->getFlashdata('success')): ?><div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div><?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?><div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div><?php endif; ?>

        <section class="grid">
            <article class="card card-highlight">
                <h3>Informations du client</h3>
                <p><strong>Nom :</strong> <?= esc($client['nom'] ?? '') ?></p>
                <p><strong>Téléphone :</strong> <?= esc($client['telephone'] ?? '') ?></p>
                <p><strong>Solde :</strong> <span class="amount"><?= number_format((float) ($client['solde'] ?? 0), 2, ',', ' ') ?> Ar</span></p>
            </article>

            <article class="card">
                <h3>Dépôt</h3>
                <form class="form-stack" method="post" action="<?= base_url('mobile-money/deposit') ?>">
                    <input type="text" name="telephone" placeholder="Téléphone" required>
                    <input type="number" name="montant" step="0.01" placeholder="Montant" required>
                    <button class="btn btn-primary" type="submit">Effectuer un dépôt</button>
                </form>
            </article>

            <article class="card">
                <h3>Retrait</h3>
                <form class="form-stack" method="post" action="<?= base_url('mobile-money/withdraw') ?>">
                    <input type="text" name="telephone" placeholder="Téléphone" required>
                    <input type="number" name="montant" step="0.01" placeholder="Montant" required>

                    <!-- Option inclure les frais avec label -->
                    <div style="margin: 10px 0; color: white;">
                        <input type="checkbox" name="inclure_frais" id="inclure_frais" value="1">
                        <label for="inclure_frais" style="margin-left: 8px;">
                            <strong style="color: black;">☑️ Inclure les frais de retrait</strong><br>
                            <span style="font-size: 0.8rem; opacity: 0.7;">
                                Si coché : vous recevez exactement le montant saisi (les frais sont ajoutés au débit).<br>
                                Si décoché : le montant saisi est le total débité (les frais sont déduits du montant reçu).
                            </span>
                        </label>
                    </div>

                    <button class="btn btn-primary" type="submit">Effectuer un retrait</button>
                </form>
            </article>

            <article class="card">
                <h3>Transfert multiple</h3>
                <p style="color: rgba(255,255,255,0.6); font-size: 0.85rem; margin-bottom: 10px;">
                    Envoyez le même montant à plusieurs destinataires en une seule opération.<br>
                    <em style="font-size: 0.8rem;">⚠️ Uniquement pour les numéros locaux (même opérateur).</em>
                </p>
                <form class="form-stack" method="post" action="<?= base_url('mobile-money/transfer-multiple') ?>">
                    <textarea name="numeros" rows="4" placeholder="0331234567&#10;0347654321" required></textarea>
                    <input type="number" name="montant_total" step="0.01" placeholder="Montant total à envoyer" required>
                    <small style="color: rgba(255,255,255,0.5); font-size: 0.8rem;">
                        Le montant total sera divisé équitablement entre les destinataires.
                    </small>
                    <button class="btn btn-primary" type="submit" style="margin-top: 10px;">Envoyer à tous</button>
                </form>
            </article>
            <article class="card">
                <h3>Transfert simple</h3>
                <form class="form-stack" method="post" action="<?= base_url('mobile-money/transfer') ?>">
                    <input type="text" name="sender_telephone" placeholder="Votre téléphone" required>
                    <input type="text" name="recipient_telephone" placeholder="Téléphone du destinataire" required>
                    <input type="number" name="montant" step="0.01" placeholder="Montant" required>
                    <button class="btn btn-primary" type="submit">Envoyer</button>
                </form>
            </article>
        </section>

        <section class="card">
            <div class="section-title">
                <h3>Historique des opérations</h3>
                <span>Vos dernières transactions</span>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Montant</th>
                        <th>Frais</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $t): ?>
                        <tr>
                            <td><?= esc($t['date_transaction'] ?? '') ?></td>
                            <td><?= esc($t['type_nom'] ?? '') ?></td>
                            <td><?= number_format((float) ($t['montant'] ?? 0), 2, ',', ' ') ?></td>
                            <td><?= number_format((float) ($t['frais'] ?? 0), 2, ',', ' ') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </div>
</body>

</html>