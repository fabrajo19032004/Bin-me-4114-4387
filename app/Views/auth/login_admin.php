<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion admin</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/auth/login-admin.css') ?>">
</head>
<body>
    <div class="box">
        <h1>Connexion admin</h1>
        <p class="muted">Saisissez vos identifiants opérateur pour accéder au tableau de bord de gestion.</p>
        <?php if (session()->getFlashdata('error')): ?><div class="alert"><?= esc(session()->getFlashdata('error')) ?></div><?php endif; ?>
        <form action="<?= base_url('auth/login/admin') ?>" method="post">
            <?= csrf_field() ?>
            <label>Nom d’utilisateur</label>
            <input type="text" name="username" placeholder="admin" required>
            <label>Mot de passe</label>
            <input type="password" name="password" placeholder="admin123" required>
            <button type="submit">Se connecter</button>
        </form>
        <a class="link" href="<?= base_url('auth/login') ?>">Retour au choix</a>
    </div>
</body>
</html>
