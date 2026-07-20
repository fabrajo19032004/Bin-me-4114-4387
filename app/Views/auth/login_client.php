<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion client</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f7fb; margin: 0; padding: 40px; }
        .box { max-width: 480px; margin: 0 auto; background: #fff; padding: 28px; border-radius: 16px; box-shadow: 0 8px 24px rgba(0,0,0,0.08); }
        h1 { margin-top: 0; }
        .muted { color: #666; }
        .alert { background: #fde8e8; color: #b42318; padding: 10px 12px; border-radius: 8px; margin-bottom: 14px; }
        label { display: block; margin-top: 12px; font-weight: bold; }
        input, select { width: 100%; padding: 10px 12px; margin-top: 6px; border: 1px solid #d0d5dd; border-radius: 8px; }
        button { margin-top: 16px; width: 100%; padding: 12px; background: #2563eb; color: #fff; border: none; border-radius: 8px; cursor: pointer; }
        .link { display: inline-block; margin-top: 14px; color: #2563eb; text-decoration: none; }
    </style>
</head>
<body>
    <div class="box">
        <h1>Connexion client</h1>
        <p class="muted">Choisissez un préfixe autorisé puis saisissez le reste du numéro.</p>
        <?php if (session()->getFlashdata('error')): ?><div class="alert"><?= esc(session()->getFlashdata('error')) ?></div><?php endif; ?>
        <form action="<?= base_url('auth/login/client') ?>" method="post">
            <?= csrf_field() ?>
            <label>Préfixe</label>
            <select name="prefixe" required>
                <option value="">Sélectionnez un préfixe</option>
                <?php foreach ((new \App\Models\PrefixModel())->findAll() as $prefix): ?>
                    <option value="<?= esc($prefix['prefixe']) ?>"><?= esc($prefix['prefixe']) ?></option>
                <?php endforeach; ?>
            </select>
            <label>Reste du numéro</label>
            <input type="text" name="rest_phone" placeholder="2901611" required>
            <button type="submit">Se connecter</button>
        </form>
        <a class="link" href="<?= base_url('auth/login') ?>">Retour au choix</a>
    </div>
</body>
</html>
