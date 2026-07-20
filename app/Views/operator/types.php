<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des types d'opérations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Gestion des types d'opérations</h1>
    <a href="/operator/types" class="btn btn-secondary mb-3">↻ Rafraîchir</a>

    <!-- Messages flash -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <!-- Formulaire d'ajout -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="post" action="<?= base_url('operator/ajouter-type') ?>">
                <?= csrf_field() ?>
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="nom" class="form-control" placeholder="Nom (ex: Depot)" required>
                    </div>
                    <div class="col-md-5">
                        <input type="text" name="description" class="form-control" placeholder="Description (optionnelle)">
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

    <!-- Liste des types -->
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Description</th>
                <th>Date création</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($types)): ?>
                <tr><td colspan="5" class="text-center">Aucun type enregistré.</td></tr>
            <?php else: ?>
                <?php foreach ($types as $t): ?>
                <tr>
                    <td><?= esc($t['id']) ?></td>
                    <td><?= esc($t['nom']) ?></td>
                    <td><?= esc($t['description']) ?></td>
                    <td><?= esc($t['created_at']) ?></td>
                    <td>
                        <a href="<?= base_url('operator/modifier-type/' . $t['id']) ?>" class="btn btn-warning btn-sm">Modifier</a>
                        <a href="<?= base_url('operator/supprimer-type/' . $t['id']) ?>" 
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Supprimer ce type ?')">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>