<?= $this->extend('mobile_money/layouts/sidebar_layout') ?>

<?= $this->section('content') ?>
<?= view('mobile_money/operator/prefixes_section', ['prefixes' => $prefixes, 'operateurs' => $operateurs]) ?>
<?= $this->endSection() ?>