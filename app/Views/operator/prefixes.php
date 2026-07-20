<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des préfixes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="app-shell">
    <?= view('partials/sidebar', ['currentRoute' => service('uri')->getPath()]) ?>
    <main class="main-content">
        <div class="container-fluid py-4">
    <h1>Gestion des préfixes</h1>
    <a href="/operator/prefixes" class="btn btn-secondary mb-3">↻ Rafraîchir</a>

    <!-- Affichage des messages flash -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <!-- Formulaire d'ajout -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="post" action="<?= base_url('operator/ajouter-prefixe') ?>">
                <?= csrf_field() ?>
                <div class="row g-3">
                    <div class="col-md-6">
                        <input type="text" name="prefixe" class="form-control" placeholder="Ex: 033" required>
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

    <!-- Liste des préfixes -->
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Préfixe</th>
                <th>Date de création</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($prefixes)): ?>
                <tr><td colspan="4" class="text-center">Aucun préfixe enregistré.</td></tr>
            <?php else: ?>
                <?php foreach ($prefixes as $p): ?>
                <tr>
                    <td><?= esc($p['id']) ?></td>
                    <td><?= esc($p['prefixe']) ?></td>
                    <td><?= esc($p['created_at']) ?></td>
                    <td>
                        <a href="<?= base_url('operator/supprimer-prefixe/' . $p['id']) ?>" 
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Supprimer ce préfixe ?')">Supprimer</a>
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