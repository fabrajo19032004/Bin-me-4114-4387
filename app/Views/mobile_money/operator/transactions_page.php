<?= $this->extend('mobile_money/layouts/sidebar_layout') ?>

<?= $this->section('content') ?>
<?= view('mobile_money/operator/gains_section', ['transactions' => $transactions, 'typeTransfertId' => $typeTransfertId ?? null]) ?>
<?= view('mobile_money/operator/transactions_section', ['transactions' => $transactions, 'sectionTitle' => $sectionTitle ?? 'Transactions', 'sectionSubtitle' => $sectionSubtitle ?? 'Historique global']) ?>
<?= $this->endSection() ?>