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
