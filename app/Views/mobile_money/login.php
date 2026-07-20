<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobile Money - Connexion</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f7fb; margin: 0; padding: 40px; }
        .box { max-width: 420px; margin: 60px auto; background: white; padding: 24px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        h2 { margin-top: 0; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input { width: 100%; padding: 10px; margin-top: 6px; border: 1px solid #ddd; border-radius: 8px; }
        button { margin-top: 16px; width: 100%; padding: 10px; border: none; border-radius: 8px; background: #0d6efd; color: white; cursor: pointer; }
        .alert { padding: 10px; border-radius: 8px; margin-bottom: 12px; background: #ffe7e7; color: #b00020; }
        .success { background: #e6f8ee; color: #137333; }
    </style>
</head>
<body>
<div class="box">
    <h2>Connexion Mobile Money</h2>
    <?php if (session()->getFlashdata('error')): ?><div class="alert"><?= esc(session()->getFlashdata('error')) ?></div><?php endif; ?>
    <?php if (session()->getFlashdata('success')): ?><div class="alert success"><?= esc(session()->getFlashdata('success')) ?></div><?php endif; ?>
    <form method="post" action="<?= base_url('mobile-money/login') ?>">
        <label>Nom d'utilisateur</label>
        <input type="text" name="username" required>
        <label>Mot de passe</label>
        <input type="password" name="password" required>
        <button type="submit">Se connecter</button>
    </form>
</div>
</body>
</html>
