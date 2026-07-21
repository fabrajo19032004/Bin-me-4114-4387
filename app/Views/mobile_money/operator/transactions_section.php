<section class="card">
    <div class="section-title">
        <h3><?= esc($sectionTitle ?? 'Transactions') ?></h3>
        <span><?= esc($sectionSubtitle ?? 'Historique global') ?></span>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Type</th>
                <th>Montant</th>
                <th>Frais</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($transactions as $t): ?>
                <tr>
                    <td><?= esc($t['id']) ?></td>
                    <td><?= esc($t['type_operation_id']) ?></td>
                    <td><?= number_format((float) ($t['montant'] ?? 0), 2, ',', ' ') ?></td>
                    <td><?= number_format((float) ($t['frais'] ?? 0), 2, ',', ' ') ?></td>
                    <td><?= esc($t['date_transaction'] ?? '') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>
