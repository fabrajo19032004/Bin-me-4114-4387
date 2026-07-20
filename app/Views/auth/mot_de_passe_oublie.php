<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecopanier – Mot de passe oublié</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/auth/mot-de-passe-oublie.css') ?>">
</head>     
<body>

<div class="card">
    <div class="icon-wrap">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
        </svg>
    </div>
    <h2>Mot de passe oublié ?</h2>
    <p class="desc">Indiquez votre e-mail et expliquez votre situation. L'administrateur vous recontactera pour réinitialiser votre accès.</p>

    <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-error"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="info-box">
        <p><strong>ℹ️ Comment ça fonctionne ?</strong>
        Votre demande sera transmise à l'administrateur système. Il traitera votre demande et vous fournira un nouveau mot de passe dans les plus brefs délais.</p>
    </div>

    <form action="<?= base_url('auth/mot-de-passe-oublie') ?>" method="POST" text-align="left">
        <?= csrf_field() ?>

        <div class="form-group">
            <label>Votre adresse e-mail</label>
            <div class="input-wrap">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                <input type="email" name="email" placeholder="votre@email.com" value="<?= old('email') ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label>Message pour l'administrateur</label>
            <textarea name="message" placeholder="Expliquez brièvement votre problème d'accès..."><?= old('message') ?></textarea>
        </div>

        <button type="submit" class="btn-submit">Envoyer la demande</button>
    </form>

    <a href="<?= base_url('auth/login') ?>" class="btn-back">← Retour à la connexion</a>
</div>

</body>
</html>
