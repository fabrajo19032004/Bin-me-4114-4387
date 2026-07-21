<?= $this->extend('mobile_money/layouts/sidebar_layout') ?>

<?= $this->section('content') ?>
<section class="grid">
    <?= view('mobile_money/client/transfer_multiple_form', ['client' => $client]) ?>
    <?= view('mobile_money/client/transfer_simple_form', ['client' => $client]) ?>
</section>
<?= $this->endSection() ?>