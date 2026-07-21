<?= $this->extend('mobile_money/layouts/sidebar_layout') ?>

<?= $this->section('content') ?>
<section class="grid">
    <?= view('mobile_money/client/account_summary', ['client' => $client]) ?>
    <?= view('mobile_money/client/deposit_form', ['client' => $client]) ?>
    <?= view('mobile_money/client/withdraw_form', ['client' => $client]) ?>
</section>
<?= $this->endSection() ?>