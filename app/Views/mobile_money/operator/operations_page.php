<?= $this->extend('mobile_money/layouts/sidebar_layout') ?>

<?= $this->section('content') ?>
<?= view('mobile_money/operator/operations_section', ['operations' => $operations]) ?>
<?= $this->endSection() ?>