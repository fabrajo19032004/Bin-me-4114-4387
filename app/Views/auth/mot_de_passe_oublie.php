<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecopanier – Mot de passe oublié</title>
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
            text-align: center;
        }
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
        h2 { font-size: 1.5rem; color: #1a2e1a; margin-bottom: 10px; font-weight: 700; }
        p.desc {
            color: #6b7c6b;
            font-size: 0.9rem;
            line-height: 1.6;
            margin-bottom: 32px;
        }
        .info-box {
            background: #f0f7f0;
            border: 1px solid #c5e0c5;
            border-radius: 12px;
            padding: 18px 20px;
            margin-bottom: 28px;
            text-align: left;
        }
        .info-box p {
            font-size: 0.88rem;
            color: #2d6a2d;
            line-height: 1.6;
        }
        .info-box strong { display: block; margin-bottom: 4px; font-size: 0.9rem; }

        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 0.88rem;
            text-align: left;
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

        textarea {
            width: 100%;
            padding: 13px 14px;
            border: 1.5px solid #dde8dd;
            border-radius: 10px;
            font-size: 0.9rem;
            color: #1a2e1a;
            background: #f9fbf9;
            outline: none;
            resize: vertical;
            min-height: 90px;
            font-family: inherit;
            transition: border-color 0.2s;
        }
        textarea:focus { border-color: #4a8a4a; background: #fff; }

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
            margin-bottom: 16px;
        }
        .btn-submit:hover { background: #2d4d2d; }

        .btn-back {
            display: inline-block;
            color: #4a8a4a;
            font-size: 0.88rem;
            font-weight: 500;
            text-decoration: none;
        }
        .btn-back:hover { text-decoration: underline; }
    </style>
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

    <a href="<?= base_url('login') ?>" class="btn-back">← Retour à la connexion</a>
</div>

</body>
</html>
