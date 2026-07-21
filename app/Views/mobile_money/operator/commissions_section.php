<section class="card">
    <div class="section-title">
        <h3>Commissions pour les autres opérateurs</h3>
        <span>Définissez le pourcentage de commission supplémentaire</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>Opérateur</th>
                <th>Commission actuelle (%)</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($operateurs as $op): ?>
                <tr>
                    <td><?= esc($op['nom']) ?></td>
                    <td><?= number_format($op['commission_pourcentage'] ?? 0, 2) ?>%</td>
                    <td>
                        <form method="post" action="<?= base_url('mobile-money/operator/commissions') ?>" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $op['id'] ?>">
                            <input type="number" step="0.01" name="commission_pourcentage" value="<?= esc($op['commission_pourcentage'] ?? 0) ?>" style="width:80px;">
                            <button class="btn btn-sm btn-primary" type="submit">Mettre à jour</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>
