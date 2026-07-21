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
