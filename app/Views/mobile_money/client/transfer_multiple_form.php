<article class="card">
    <h3>Transfert multiple</h3>
    <p style="color: rgba(255,255,255,0.6); font-size: 0.85rem; margin-bottom: 10px;">
        Envoyez le même montant à plusieurs destinataires en une seule opération.<br>
        <em style="font-size: 0.8rem;">⚠️ Uniquement pour les numéros locaux (même opérateur).</em>
    </p>
    <form class="form-stack" method="post" action="<?= base_url('mobile-money/transfer-multiple') ?>">
        <textarea name="numeros" rows="4" placeholder="0331234567&#10;0347654321" required></textarea>
        <input type="number" name="montant_total" step="0.01" placeholder="Montant total à envoyer" required>
        <small style="color: rgba(255,255,255,0.5); font-size: 0.8rem;">
            Le montant total sera divisé équitablement entre les destinataires.
        </small>
        <button class="btn btn-primary" type="submit" style="margin-top: 10px;">Envoyer à tous</button>
    </form>
</article>
