<article class="card">
    <h3>Transfert simple</h3>
    <form class="form-stack" method="post" action="<?= base_url('mobile-money/transfer') ?>">
        <input type="text" name="sender_telephone" placeholder="Votre téléphone" required>
        <input type="text" name="recipient_telephone" placeholder="Téléphone du destinataire" required>
        <input type="number" name="montant" step="0.01" placeholder="Montant" required>
        <button class="btn btn-primary" type="submit">Envoyer</button>
    </form>
</article>
