<?= $this->extend('mobile_money/layouts/sidebar_layout') ?>

<?= $this->section('content') ?>
<?= view('mobile_money/client/transactions_table', ['transactions' => $transactions]) ?>
<?= $this->endSection() ?>