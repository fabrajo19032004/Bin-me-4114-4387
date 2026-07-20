<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Situation des gains</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Situation des gains (frais perçus)</h1>
    <a href="/operator/gains" class="btn btn-secondary mb-3">↻ Rafraîchir</a>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Type d'opération</th>
                        <th>Total des frais perçus</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($gains)): ?>
                        <tr><td colspan="2" class="text-center">Aucune donnée.</td></tr>
                    <?php else: ?>
                        <?php foreach ($gains as $g): ?>
                        <tr>
                            <td><?= esc($g['type_operation']) ?></td>
                            <td><?= number_format($g['total_frais'], 2) ?> €</td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>