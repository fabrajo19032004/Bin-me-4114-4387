<section class="card">
    <div class="section-title">
        <h3>Montants à reverser aux autres opérateurs</h3>
        <span>Commissions prélevées sur les transferts externes</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>Opérateur</th>
                <th>Commission totale</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $commissionsParOperateur = [];
            $prefixesByCode = [];
            foreach ($prefixes as $prefix) {
                $prefixesByCode[$prefix['prefixe']] = $prefix;
            }

            foreach ($transactions as $t) {
                if ($t['est_vers_autre_operateur'] ?? 0) {
                    $prefixCode = substr($t['destinataire_telephone'] ?? '', 0, 3);
                    $prefixInfo = $prefixesByCode[$prefixCode] ?? null;
                    if ($prefixInfo) {
                        $opId = $prefixInfo['operateur_id'];
                        if (!isset($commissionsParOperateur[$opId])) {
                            $commissionsParOperateur[$opId] = 0;
                        }
                        $commissionsParOperateur[$opId] += ($t['frais'] ?? 0) - ($t['frais_base'] ?? 0);
                    }
                }
            }
            ?>
            <?php foreach ($commissionsParOperateur as $opId => $total): ?>
                <tr>
                    <td><?= esc($operateurs[$opId]['nom'] ?? 'Inconnu') ?></td>
                    <td><?= number_format($total, 2) ?> €</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>
