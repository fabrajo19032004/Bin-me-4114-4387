<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choix de connexion</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/auth/login-choice.css') ?>">
</head>
<body>
    <div class="panel">
        <h1>Choisissez votre espace de connexion</h1>
        <p class="muted">Accédez soit à l’espace opérateur, soit à l’espace client selon votre profil.</p>
        <div class="grid">
            <div class="card">
                <h2>Admin / Opérateur</h2>
                <p class="muted">Pour gérer les préfixes, les opérations, les frais et consulter la situation générale.</p>
                <a class="btn" href="<?= base_url('auth/login/admin') ?>">Connexion admin</a>
            </div>
            <div class="card">
                <h2>Client</h2>
                <p class="muted">Pour consulter votre solde, faire un dépôt, un retrait ou un transfert.</p>
                <a class="btn" href="<?= base_url('auth/login/client') ?>">Connexion client</a>
            </div>
        </div>
    </div>
</body>
</html>
