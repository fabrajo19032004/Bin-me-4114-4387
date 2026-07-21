<section class="card">
    <div class="section-title">
        <h3>Clients</h3>
        <span>Situation des comptes</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Téléphone</th>
                <th>Solde</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clients as $client): ?>
                <tr>
                    <td><?= esc($client['id']) ?></td>
                    <td><?= esc($client['nom'] ?? '') ?></td>
                    <td><?= esc($client['telephone'] ?? '') ?></td>
                    <td><?= number_format((float) ($client['solde'] ?? 0), 2, ',', ' ') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>
