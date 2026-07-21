<section class="card">
    <div class="section-title">
        <h3>Situation des gains (frais perçus)</h3>
        <span>Détail par type de transfert</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>Type</th>
                <th>Total des frais</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $gainsInterne = 0;
            $gainsExterne = 0;
            foreach ($transactions as $t) {
                if ($t['type_operation_id'] == $typeTransfertId) {
                    if ($t['est_vers_autre_operateur'] ?? 0) {
                        $gainsExterne += $t['frais'];
                    } else {
                        $gainsInterne += $t['frais'];
                    }
                }
            }
            ?>
            <tr>
                <td>Interne (frais de base)</td>
                <td><?= number_format($gainsInterne, 2) ?> €</td>
            </tr>
            <tr>
                <td>Externe (frais de base + commissions)</td>
                <td><?= number_format($gainsExterne, 2) ?> €</td>
            </tr>
        </tbody>
    </table>
</section>
