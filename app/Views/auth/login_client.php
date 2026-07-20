<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion client</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/auth/login-client.css') ?>">
</head>
<body>
    <div class="box">
        <h1>Connexion client</h1>
        <p class="muted">Saisissez votre numéro de téléphone autorisé pour accéder à votre espace client.</p>
        <?php if (session()->getFlashdata('error')): ?><div class="alert"><?= esc(session()->getFlashdata('error')) ?></div><?php endif; ?>
        <form action="<?= base_url('auth/login/client') ?>" method="post">
            <?= csrf_field() ?>
            <label>Numéro de téléphone</label>
            <input type="text" name="username" placeholder="0331234567" required>
            <label>Mot de passe</label>
            <input type="password" name="password" placeholder="Votre mot de passe" required>
            <button type="submit">Se connecter</button>
        </form>
        <a class="link" href="<?= base_url('auth/login') ?>">Retour au choix</a>
    </div>
</body>
</html>
