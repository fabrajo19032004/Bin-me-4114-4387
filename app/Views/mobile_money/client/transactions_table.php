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
