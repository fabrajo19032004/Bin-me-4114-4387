<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des barèmes de frais</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="app-shell">
    <?= view('partials/sidebar', ['currentRoute' => service('uri')->getPath()]) ?>
    <main class="main-content">
        <div class="container-fluid py-4">
    <h1>Gestion des barèmes de frais</h1>
    <a href="/operator/baremes" class="btn btn-secondary mb-3">↻ Rafraîchir</a>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <!-- Formulaire d'ajout -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="post" action="<?= base_url('operator/ajouter-bareme') ?>">
                <?= csrf_field() ?>
                <div class="row g-3">
                    <div class="col-md-3">
                        <select name="type_operation_id" class="form-select" required>
                            <option value="">-- Type --</option>
                            <?php foreach ($types as $type): ?>
                                <option value="<?= $type['id'] ?>"><?= esc($type['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="number" step="0.01" name="montant_min" class="form-control" placeholder="Min" required>
                    </div>
                    <div class="col-md-2">
                        <input type="number" step="0.01" name="montant_max" class="form-control" placeholder="Max (vide = infini)">
                    </div>
                    <div class="col-md-2">
                        <input type="number" step="0.01" name="frais" class="form-control" placeholder="Frais" required>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                    </div>
                </div>
                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="text-danger mt-2">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <small><?= esc($error) ?></small><br>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Liste des barèmes -->
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Type</th>
                <th>Montant min</th>
                <th>Montant max</th>
                <th>Frais</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($baremes)): ?>
                <tr><td colspan="6" class="text-center">Aucun barème enregistré.</td></tr>
            <?php else: ?>
                <?php foreach ($baremes as $b): ?>
                <tr>
                    <td><?= esc($b['id']) ?></td>
                    <td><?= esc($b['type_nom']) ?></td>
                    <td><?= esc($b['montant_min']) ?></td>
                    <td><?= $b['montant_max'] ? esc($b['montant_max']) : '∞' ?></td>
                    <td><?= esc($b['frais']) ?></td>
                    <td>
                        <a href="<?= base_url('operator/supprimer-bareme/' . $b['id']) ?>" 
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Supprimer ce barème ?')">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
        </div>
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>