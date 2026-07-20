<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecopanier – Nouveau mot de passe</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/auth/changer-mot-de-passe.css') ?>">

</head>
<body>

<div class="card">

    <div class="steps">
        <div class="step">
            <div class="step-circle done">✓</div>
            <span class="step-label">Mot de passe temporaire</span>
        </div>
        <div class="step-line"></div>
        <div class="step">
            <div class="step-circle active">2</div>
            <span class="step-label">Nouveau mot de passe</span>
        </div>
    </div>

    <div class="icon-wrap">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            <circle cx="12" cy="16" r="1.5" fill="currentColor" stroke="none"/>
        </svg>
    </div>

    <h2>Créez votre nouveau mot de passe</h2>
    <p class="desc">Votre mot de passe temporaire a été validé. Définissez maintenant votre mot de passe définitif.</p>

    <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-error"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <form action="<?= base_url('auth/changer-mot-de-passe/nouveau') ?>" method="POST">
        <?= csrf_field() ?>

        <div class="form-group">
            <label>Nouveau mot de passe</label>
            <div class="input-wrap">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                <input type="password" name="nouveau_mot_de_passe" placeholder="••••••••" required minlength="6" autofocus>
            </div>
            <p class="hint">Au moins 6 caractères.</p>
        </div>

        <div class="form-group">
            <label>Confirmer le mot de passe</label>
            <div class="input-wrap">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                <input type="password" name="confirmation_mot_de_passe" placeholder="••••••••" required minlength="6">
            </div>
        </div>

        <button type="submit" class="btn-submit">Valider le nouveau mot de passe</button>
    </form>
</div>

</body>
</html>
