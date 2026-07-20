// public/js/crm/profile-modal.js

function openModalProfil(id, nom, telephone, email, adresse, region, district, statut, date) {
    // Déterminer la classe CSS du badge en fonction du statut
    var badgeClass = '';
    if (statut === 'actif') badgeClass = 'crm-badge-actif';
    else if (statut === 'inactif') badgeClass = 'crm-badge-inactif';
    else if (statut === 'fidele') badgeClass = 'crm-badge-fidele';
    else badgeClass = 'crm-badge-nouveau';

    // Génération du HTML du profil
    var html = `
        <div class="crm-profile-layout">
            <!-- COLONNE GAUCHE -->
            <div class="crm-left-col">
                <!-- Carte infos générales -->
                <div class="crm-card">
                    <div class="crm-profile-header">
                        <div class="crm-profile-avatar">${nom.charAt(0).toUpperCase()}</div>
                        <div class="crm-profile-name">${nom}</div>
                        <div class="crm-profile-badge">
                            <span class="crm-badge ${badgeClass}">${statut.charAt(0).toUpperCase() + statut.slice(1)}</span>
                        </div>
                    </div>
                    <div class="crm-details-list">
                        <div class="crm-details-item">
                            <span class="crm-details-label">Téléphone</span>
                            <span class="crm-details-value">${telephone || 'Non renseigné'}</span>
                        </div>
                        <div class="crm-details-item">
                            <span class="crm-details-label">Email</span>
                            <span class="crm-details-value">${email || 'Non renseigné'}</span>
                        </div>
                        <div class="crm-details-item">
                            <span class="crm-details-label">Adresse</span>
                            <span class="crm-details-value">${adresse || 'Non renseignée'}</span>
                        </div>
                        <div class="crm-details-item">
                            <span class="crm-details-label">Région / District</span>
                            <span class="crm-details-value">${region || '—'} / ${district || '—'}</span>
                        </div>
                        <div class="crm-details-item">
                            <span class="crm-details-label">Date Création</span>
                            <span class="crm-details-value">${date || '—'}</span>
                        </div>
                    </div>
                </div>

                <!-- Carte Fidélité -->
                <div class="crm-card crm-loyalty-card">
                    <div class="crm-details-label" style="margin-bottom: 8px;">Statut Fidélisation</div>
                    <div class="crm-loyalty-status">Nouveau</div>
                    <div class="crm-details-list" style="gap: 10px;">
                        <div class="crm-details-item">
                            <span class="crm-details-label">Nombre de réservations</span>
                            <span class="crm-loyalty-metric">0</span>
                        </div>
                        <div class="crm-details-item">
                            <span class="crm-details-label">Montant total d'achat</span>
                            <span class="crm-loyalty-metric">0,00 Ar</span>
                        </div>
                        <div class="crm-details-item" style="border-top: 1px solid rgba(255,255,255,0.15); padding-top: 10px;">
                            <span class="crm-details-label">Réduction applicable</span>
                            <span class="crm-loyalty-metric" style="color: #6dbf67; font-size: 1.2rem; font-weight: 700;">-0%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- COLONNE DROITE -->
            <div class="crm-right-col">
                <!-- Réservations -->
                <div class="crm-card" style="padding: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                        <h3 style="font-size: 1rem; color: var(--crm-primary); margin:0;">Historique des Réservations</h3>
                        ${statut === 'actif' || statut === 'fidele' ? `<a href="<?= base_url('agent/reservations') ?>?client_id=${id}" class="btn-new-res">Nouvelle Réservation</a>` : ''}
                    </div>
                    <table class="crm-table">
                        <thead><tr><th>N° Réservation</th><th>Statut</th><th>Date Expiration</th><th>Création</th></tr></thead>
                        <tbody><tr><td colspan="4" style="text-align: center; color: var(--crm-text-muted); padding: 15px;">Aucune réservation trouvée.</td></tr></tbody>
                    </table>
                </div>

                <!-- Paiements -->
                <div class="crm-card" style="padding: 20px; margin-top: 20px;">
                    <h3 style="font-size: 1rem; color: var(--crm-primary); margin:0 0 12px 0;">Historique des Paiements</h3>
                    <table class="crm-table">
                        <thead><tr><th>Réservation</th><th>Montant</th><th>Type</th><th>Référence</th><th>Statut</th></tr></thead>
                        <tbody><tr><td colspan="5" style="text-align: center; color: var(--crm-text-muted); padding: 15px;">Aucun paiement trouvé.</td></tr></tbody>
                    </table>
                </div>

                <!-- Formulaire d'interaction -->
                <div class="crm-card" style="padding: 20px; margin-top: 20px;">
                    <h3 style="font-size: 1rem; color: var(--crm-primary); margin:0 0 15px 0;">Ajouter une interaction</h3>
                    <form action="<?= base_url('crm/clients/interaction/') ?>${id}" method="POST" style="display: flex; flex-direction: column; gap: 15px;">
                        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <div>
                                <label style="font-size: 0.8rem; font-weight: 600; color: var(--crm-primary);">Type</label>
                                <select name="type" class="crm-form-control" required>
                                    <option value="Téléphone">Téléphone</option>
                                    <option value="Email">Email</option>
                                    <option value="Rendez-vous">Rendez-vous</option>
                                    <option value="Autre">Autre</option>
                                </select>
                            </div>
                            <div>
                                <label style="font-size: 0.8rem; font-weight: 600; color: var(--crm-primary);">Date & Heure</label>
                                <input type="datetime-local" name="date" class="crm-form-control" value="<?= date('Y-m-d\TH:i') ?>" required>
                            </div>
                        </div>
                        <div>
                            <label style="font-size: 0.8rem; font-weight: 600; color: var(--crm-primary);">Description</label>
                            <textarea name="description" class="crm-form-control" style="min-height: 60px;" placeholder="Détails de l'échange..." required></textarea>
                        </div>
                        <div style="display: flex; justify-content: flex-end;">
                            <button type="submit" class="crm-btn crm-btn-success">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    `;

    document.getElementById('modalProfilBody').innerHTML = html;
    document.getElementById('modalProfilClient').classList.add('active');
    document.body.style.overflow = 'hidden';

    return false;
}

function closeModalProfil() {
    document.getElementById('modalProfilClient').classList.remove('active');
    document.body.style.overflow = '';
}