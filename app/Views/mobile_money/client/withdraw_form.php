<article class="card">
    <h3>Retrait</h3>
    <form class="form-stack" method="post" action="<?= base_url('mobile-money/withdraw') ?>">
        <input type="text" name="telephone" placeholder="Téléphone" required>
        <input type="number" name="montant" step="0.01" placeholder="Montant" required>

        <div style="margin: 10px 0; color: white;">
            <input type="checkbox" name="inclure_frais" id="inclure_frais" value="1">
            <label for="inclure_frais" style="margin-left: 8px;">
                <strong style="color: black;">☑️ Inclure les frais de retrait</strong><br>
                <span style="font-size: 0.8rem; opacity: 0.7;">
                    Si coché : vous recevez exactement le montant saisi (les frais sont ajoutés au débit).<br>
                    Si décoché : le montant saisi est le total débité (les frais sont déduits du montant reçu).
                </span>
            </label>
        </div>

        <button class="btn btn-primary" type="submit">Effectuer un retrait</button>
    </form>
</article>
