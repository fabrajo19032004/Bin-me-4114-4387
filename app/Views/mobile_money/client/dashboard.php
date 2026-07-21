<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord client</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/mobile-money.css') ?>">
</head>

<body>
    <div class="page-shell">
        <header class="topbar">
            <div>
                <p class="eyebrow">Mobile Money</p>
                <h1>Tableau de bord client</h1>
            </div>
            <a class="btn btn-ghost" href="<?= base_url('mobile-money/logout') ?>">Déconnexion</a>
        </header>

        <?php if (session()->getFlashdata('success')): ?><div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div><?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?><div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div><?php endif; ?>

        <section class="grid">
            <?= view('mobile_money/client/account_summary', ['client' => $client]) ?>
            <?= view('mobile_money/client/deposit_form', ['client' => $client]) ?>
            <?= view('mobile_money/client/withdraw_form', ['client' => $client]) ?>
            <?= view('mobile_money/client/transfer_multiple_form', ['client' => $client]) ?>
            <?= view('mobile_money/client/transfer_simple_form', ['client' => $client]) ?>
        </section>

        <?= view('mobile_money/client/transactions_table', ['transactions' => $transactions]) ?>
    </div>
</body>

</html>
