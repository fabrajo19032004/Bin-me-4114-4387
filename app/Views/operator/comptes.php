<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Situation des comptes clients</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="app-shell">
    <?= view('partials/sidebar', ['currentRoute' => service('uri')->getPath()]) ?>
    <main class="main-content">
        <div class="container-fluid py-4">
    <h1>Situation des comptes clients</h1>
    <a href="/operator/comptes" class="btn btn-secondary mb-3">↻ Rafraîchir</a>

    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Téléphone</th>
                        <th>Solde</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($clients)): ?>
                        <tr><td colspan="4" class="text-center">Aucun client enregistré.</td></tr>
                    <?php else: ?>
                        <?php foreach ($clients as $c): ?>
                        <tr>
                            <td><?= esc($c['id']) ?></td>
                            <td><?= esc($c['nom']) ?></td>
                            <td><?= esc($c['telephone']) ?></td>
                            <td><?= number_format($c['solde'], 2) ?> €</td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
        </div>
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>