<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecopanier – Connexion</title>
    <link rel="stylesheet" href="<?= base_url('css/magasinier/mag-layout.css') ?>">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Outfit', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: url('/images/bg-ecopanier.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            z-index: -1;
        }

        .login-wrapper {
            display: flex;
            width: 900px;
            max-width: 95vw;
            min-height: 520px;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.4);
            border: 1px solid rgba(255,255,255,0.08);
        }

        .brand-panel {
            background: rgba(0,0,0,0.8);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 48px 40px;
            color: #fff;
            position: relative;
            overflow: hidden;
            border-right: 1px solid rgba(255,255,255,0.06);
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 32px;
        }
        .brand-logo svg { width: 56px; height: 56px; }
        .brand-logo span { font-size: 2rem; font-weight: 700; letter-spacing: -0.5px; }
        .brand-tagline {
            font-size: 0.95rem;
            color: rgba(255,255,255,0.5);
            text-align: center;
            line-height: 1.6;
            max-width: 220px;
        }

        .form-panel {
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            flex: 1.2;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 56px 48px;
        }

        .form-panel h2 {
            font-size: 1.6rem;
            color: #fff;
            font-weight: 700;
            margin-bottom: 6px;
        }
        .form-panel p.subtitle {
            color: rgba(255,255,255,0.5);
            font-size: 0.9rem;
            margin-bottom: 36px;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 0.88rem;
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1px solid rgba(255,255,255,0.1);
        }
        .alert-error {
            background: rgba(231,76,60,0.15);
            color: #e74c3c;
        }
        .alert-success {
            background: rgba(46,204,113,0.15);
            color: #2ecc71;
        }

        .form-group { margin-bottom: 20px; }
        .form-group label {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            color: rgba(255,255,255,0.5);
            margin-bottom: 7px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .input-wrap { position: relative; }
        .input-wrap svg {
            position: absolute;
            left: 14px; top: 50%;
            transform: translateY(-50%);
            color: #000;
            width: 18px; height: 18px;
        }
        .form-group input {
            width: 100%;
            padding: 13px 14px 13px 42px;
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 10px;
            font-size: 0.95rem;
            color: #fff;
            background: rgba(255,255,255,0.15);
            transition: border-color 0.2s;
            outline: none;
        }
        .form-group input:focus {
            border-color: rgba(255,255,255,0.4);
            background: rgba(255,255,255,0.12);
        }
        .form-group input::placeholder {
            color: rgba(255,255,255,0.3);
        }

        .form-footer {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            margin-bottom: 28px;
        }
        .link-forgot {
            font-size: 0.84rem;
            color: rgba(255,255,255,0.5);
            text-decoration: none;
            font-weight: 500;
        }
        .link-forgot:hover { color: #fff; }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: #fff;
            color: #000;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-login:hover { background: rgba(255,255,255,0.85); }

        .no-account {
            text-align: center;
            margin-top: 24px;
            font-size: 0.84rem;
            color: rgba(255,255,255,0.3);
        }
        .no-account strong { color: rgba(255,255,255,0.5); }

        @media (max-width: 640px) {
            .brand-panel { display: none; }
            .form-panel { padding: 40px 28px; }
        }
    </style>
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