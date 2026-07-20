<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecopanier – Nouveau mot de passe</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f0f4f0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            background: #fff;
            border-radius: 20px;
            padding: 52px 48px;
            width: 460px;
            max-width: 95vw;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
        }

        .steps {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 28px;
        }
        .step {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .step-circle {
            width: 28px; height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 700;
        }
        .step-circle.done { background: #1a2e1a; color: #fff; }
        .step-circle.active { background: #6dbf67; color: #fff; }
        .step-label { font-size: 0.78rem; color: #6b7c6b; font-weight: 600; }
        .step-line { width: 32px; height: 2px; background: #dde8dd; }

        .icon-wrap {
            width: 72px; height: 72px;
            background: #f0f7f0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
        }
        .icon-wrap svg { width: 34px; height: 34px; color: #4a8a4a; }

        h2 { font-size: 1.5rem; color: #1a2e1a; margin-bottom: 10px; font-weight: 700; text-align: center; }
        p.desc {
            color: #6b7c6b;
            font-size: 0.9rem;
            line-height: 1.6;
            margin-bottom: 28px;
            text-align: center;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 0.88rem;
        }
        .alert-success { background: #f0fff4; border: 1px solid #b2dfdb; color: #1a6b3a; }
        .alert-error { background: #fff0f0; border: 1px solid #ffc5c5; color: #c0392b; }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        .form-group label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: #3a4a3a;
            margin-bottom: 7px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .input-wrap { position: relative; }
        .input-wrap svg {
            position: absolute; left: 14px; top: 50%;
            transform: translateY(-50%);
            color: #9aab9a; width: 18px; height: 18px;
        }
        input {
            width: 100%;
            padding: 13px 14px 13px 42px;
            border: 1.5px solid #dde8dd;
            border-radius: 10px;
            font-size: 0.95rem;
            color: #1a2e1a;
            background: #f9fbf9;
            outline: none;
            transition: border-color 0.2s;
        }
        input:focus { border-color: #4a8a4a; background: #fff; }

        .hint {
            font-size: 0.76rem;
            color: #9aab9a;
            margin-top: 6px;
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: #1a2e1a;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-submit:hover { background: #2d4d2d; }
    </style>
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
