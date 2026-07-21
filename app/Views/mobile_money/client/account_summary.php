<article class="card card-highlight">
    <h3>Informations du client</h3>
    <p><strong>Nom :</strong> <?= esc($client['nom'] ?? '') ?></p>
    <p><strong>Téléphone :</strong> <?= esc($client['telephone'] ?? '') ?></p>
    <p><strong>Solde :</strong> <span class="amount"><?= number_format((float) ($client['solde'] ?? 0), 2, ',', ' ') ?> Ar</span></p>
</article>
