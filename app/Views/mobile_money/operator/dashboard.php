<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord opérateur</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/mobile-money.css') ?>">
</head>

<body>
    <div class="page-shell">
        <header class="topbar">
            <div>
                <p class="eyebrow">Mobile Money</p>
                <h1>Tableau de bord opérateur</h1>
            </div>
            <a class="btn btn-ghost" href="<?= base_url('mobile-money/logout') ?>">Déconnexion</a>
        </header>

        <?php if (session()->getFlashdata('success')): ?><div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div><?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?><div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div><?php endif; ?>

        <?= view('mobile_money/operator/prefixes_section', ['prefixes' => $prefixes, 'operateurs' => $operateurs]) ?>
        <?= view('mobile_money/operator/commissions_section', ['operateurs' => $operateurs]) ?>
        <?= view('mobile_money/operator/operations_section', ['operations' => $operations]) ?>
        <?= view('mobile_money/operator/fees_section', ['fees' => $fees]) ?>
        <?= view('mobile_money/operator/clients_section', ['clients' => $clients]) ?>
        <?= view('mobile_money/operator/commissions_to_reverse_section', ['transactions' => $transactions, 'operateurs' => $operateurs, 'prefixes' => $prefixes]) ?>
        <?= view('mobile_money/operator/transactions_section', ['transactions' => $transactions]) ?>
        <?= view('mobile_money/operator/gains_section', ['transactions' => $transactions, 'typeTransfertId' => $typeTransfertId]) ?>
    </div>
</body>

</html>
