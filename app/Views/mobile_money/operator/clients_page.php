<?= $this->extend('mobile_money/layouts/sidebar_layout') ?>

<?= $this->section('content') ?>
<?= view('mobile_money/operator/clients_section', ['clients' => $clients]) ?>
<?= $this->endSection() ?>