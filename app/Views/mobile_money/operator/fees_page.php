<?= $this->extend('mobile_money/layouts/sidebar_layout') ?>

<?= $this->section('content') ?>
<?= view('mobile_money/operator/fees_section', ['fees' => $fees]) ?>
<?= $this->endSection() ?>