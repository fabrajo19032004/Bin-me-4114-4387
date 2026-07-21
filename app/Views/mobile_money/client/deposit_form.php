<article class="card">
    <h3>Dépôt</h3>
    <form class="form-stack" method="post" action="<?= base_url('mobile-money/deposit') ?>">
        <input type="text" name="telephone" placeholder="Téléphone" required>
        <input type="number" name="montant" step="0.01" placeholder="Montant" required>
        <button class="btn btn-primary" type="submit">Effectuer un dépôt</button>
    </form>
</article>
