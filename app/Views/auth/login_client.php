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
        <p class="muted">Choisissez un préfixe autorisé puis saisissez le reste du numéro.</p>
        <?php if (session()->getFlashdata('error')): ?><div class="alert"><?= esc(session()->getFlashdata('error')) ?></div><?php endif; ?>
        <form action="<?= base_url('auth/login/client') ?>" method="post">
            <?= csrf_field() ?>
            <div class="phone-row">
                <div class="phone-field">
                    <label>Préfixe</label>
                    <select name="prefixe" required>
                        <option value="">Sélectionnez</option>
                        <?php foreach ((new \App\Models\PrefixModel())->findAll() as $prefix): ?>
                            <option value="<?= esc($prefix['prefixe']) ?>"><?= esc($prefix['prefixe']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="phone-field phone-field--grow">
                    <label>Reste du numéro</label>
                    <input type="text" name="rest_phone" placeholder="2901611" required>
                </div>
            </div>
            <button type="submit">Se connecter</button>
        </form>
        <a class="link" href="<?= base_url('auth/login') ?>">Retour au choix</a>
    </div>
</body>
</html>
