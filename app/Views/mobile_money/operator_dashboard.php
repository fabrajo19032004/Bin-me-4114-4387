<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord opérateur</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/mobile-money.css') ?>">
</head>

<body>
    <div class="page-shell">
        <header class="topbar">
            <div>
                <p class="eyebrow">Mobile Money</p>
                <h1>Tableau de bord opérateur</h1>
            </div>
            <a class="btn btn-ghost" href="<?= base_url('mobile-money/logout') ?>">Déconnexion</a>
        </header>

        <?php if (session()->getFlashdata('success')): ?><div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div><?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?><div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div><?php endif; ?>

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

        <section class="card">
            <div class="section-title">
                <h3>Clients</h3>
                <span>Situation des comptes</span>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Téléphone</th>
                        <th>Solde</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clients as $client): ?>
                        <tr>
                            <td><?= esc($client['id']) ?></td>
                            <td><?= esc($client['nom'] ?? '') ?></td>
                            <td><?= esc($client['telephone'] ?? '') ?></td>
                            <td><?= number_format((float) ($client['solde'] ?? 0), 2, ',', ' ') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

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
                    foreach ($transactions as $t) {
                        if ($t['est_vers_autre_operateur'] ?? 0) {
                            // Récupérer l'opérateur du destinataire
                            // Pour simplifier, on utilise le préfixe du destinataire
                            $prefixInfo = $prefixModel->where('prefixe', substr($t['destinataire_telephone'] ?? '', 0, 3))->first();
                            if ($prefixInfo) {
                                $opId = $prefixInfo['operateur_id'];
                                if (!isset($commissionsParOperateur[$opId])) {
                                    $commissionsParOperateur[$opId] = 0;
                                }
                                // La commission est stockée dans un champ séparé ou déduite
                                // Pour l'instant, on la calcule à partir du frais total - frais de base
                                // Mais pour plus de précision, il faudrait un champ commission séparé dans transactions
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

        <section class="card">
            <div class="section-title">
                <h3>Transactions</h3>
                <span>Historique global</span>
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
    </div>
</body>

</html>