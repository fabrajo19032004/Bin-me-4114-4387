<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecopanier – Connexion</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/auth/login.css') ?>">
</head>
      

<body>

<div class="login-wrapper">
    <div class="brand-panel">
        <div class="brand-logo">
            <svg viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="28" cy="28" r="28" fill="rgba(255,255,255,0.08)"/>
                <path d="M14 18h4l5.5 14h9l5.5-14H42" stroke="#6dbf67" stroke-width="2.5" stroke-linecap="round"/>
                <circle cx="22" cy="38" r="2.5" fill="#6dbf67"/>
                <circle cx="34" cy="38" r="2.5" fill="#6dbf67"/>
            </svg>
            <span>Ecopanier</span>
        </div>
        <p class="brand-tagline">Plateforme de gestion integree pour votre activite commerciale</p>
    </div>

    <div class="form-panel">
        <h2>Bon retour</h2>
        <p class="subtitle">Connectez-vous avec votre adresse e-mail professionnelle</p>

        <div class="help-box">
            <strong>Test rapide :</strong><br>
            Opérateur : <strong>admin</strong> / <strong>admin123</strong><br>
            Client : utilisez un numéro de téléphone autorisé comme <strong>0331234567</strong>
        </div>

        <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <?= session()->getFlashdata('error') ?>
        </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            <?= session()->getFlashdata('success') ?>
        </div>
        <?php endif; ?>

        <form action="<?= base_url('auth/login') ?>" method="POST">
            <?= csrf_field() ?>

            <div class="form-group">
                <label>Nom d'utilisateur</label>
                <div class="input-wrap">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    <input type="text" name="username" placeholder="admin ou téléphone" value="<?= old('username') ?>" required autofocus>
                </div>
            </div>

            <div class="form-group">
                <label>Mot de passe</label>
                <div class="input-wrap">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    <input type="password" name="password" placeholder="********" required>
                </div>
            </div>

            <div class="form-footer">
                <a href="<?= base_url('auth/mot-de-passe-oublie') ?>" class="link-forgot">Mot de passe oublie ?</a>
            </div>

            <button type="submit" class="btn-login">Se connecter</button>
        </form>

        <p class="no-account">
            Pas de compte ? <strong>Contactez votre administrateur.</strong>
        </p>
    </div>
</div>

</body>
</html>