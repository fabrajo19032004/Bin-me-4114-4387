<section class="card">
    <div class="section-title">
        <h3>Préfixes autorisés</h3>
        <span>Gérez les numéros acceptés (locaux ou autres opérateurs)</span>
    </div>
    <form class="form-inline" method="post" action="<?= base_url('mobile-money/operator/prefixes') ?>">
        <input type="text" name="prefixe" placeholder="Ex: 033" required>
        <select name="operateur_id" required>
            <option value="">-- Opérateur --</option>
            <?php foreach ($operateurs as $op): ?>
                <option value="<?= $op['id'] ?>"><?= esc($op['nom']) ?></option>
            <?php endforeach; ?>
        </select>
        <select name="est_local" required>
            <option value="1">Local</option>
            <option value="0">Autre opérateur</option>
        </select>
        <button class="btn btn-primary" type="submit">Ajouter</button>
    </form>
    <ul class="list-stack">
        <?php foreach ($prefixes as $prefix): ?>
            <li><?= esc($prefix['prefixe']) ?>
                (<?= $prefix['est_local'] ? 'Local' : 'Externe' ?>)
                <a href="<?= base_url('mobile-money/operator/prefixes/delete/' . $prefix['id']) ?>">Supprimer</a>
            </li>
        <?php endforeach; ?>
    </ul>
</section>
