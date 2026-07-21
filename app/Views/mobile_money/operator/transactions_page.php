<?= $this->extend('mobile_money/layouts/sidebar_layout') ?>

<?= $this->section('content') ?>
<?= view('mobile_money/operator/transactions_section', ['transactions' => $transactions]) ?>
<?= $this->endSection() ?>