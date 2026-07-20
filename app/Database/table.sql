CREATE DATABASE IF NOT EXISTS eco_panier_S4_2026;
USE eco_panier_S4_2026;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    mot_de_passe_temp VARCHAR(255) DEFAULT NULL,
    mdp_temp_expire_at DATETIME DEFAULT NULL,
    demande_reinit TINYINT(1) NOT NULL DEFAULT 0,
    role ENUM('admin', 'agent_commercial', 'magasinier', 'livreur') NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME DEFAULT NULL
);

CREATE TABLE packs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    categorie_produit VARCHAR(100),
    categorie_pack VARCHAR(50),
    date_creation DATE,
    description TEXT,
    prix_vente DECIMAL(10,2) NOT NULL,
    cout_revient DECIMAL(10,2) NOT NULL,
    statut ENUM('actif', 'inactif') DEFAULT 'actif'
);

CREATE TABLE clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    telephone VARCHAR(20),
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE produits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    categorie VARCHAR(100),
    quantite INT DEFAULT 0,
    prix_achat DECIMAL(10,2) NOT NULL,
    statut ENUM('disponible', 'indisponible') DEFAULT 'disponible'
);

CREATE TABLE pack_produits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pack_id INT NOT NULL,
    produit_id INT NOT NULL,
    quantite INT NOT NULL,

    FOREIGN KEY (pack_id) REFERENCES packs(id)
        ON DELETE CASCADE,
    FOREIGN KEY (produit_id) REFERENCES produits(id)
        ON DELETE CASCADE
);

CREATE TABLE reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    pack_id INT NOT NULL,
    statut ENUM('en_attente', 'confirmee', 'annulee', 'expiree')
        DEFAULT 'en_attente',
    acompte DECIMAL(10,2) DEFAULT 0,
    solde DECIMAL(10,2) DEFAULT 0,
    code_validation VARCHAR(100),
    date_expiration DATETIME,

    FOREIGN KEY (client_id) REFERENCES clients(id),
    FOREIGN KEY (pack_id) REFERENCES packs(id)
);

CREATE TABLE paiements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id INT NOT NULL,
    montant DECIMAL(10,2) NOT NULL,
    type ENUM('acompte', 'solde') NOT NULL,
    reference VARCHAR(100),
    statut ENUM('en_attente', 'valide', 'refuse')
        DEFAULT 'en_attente',

    FOREIGN KEY (reservation_id) REFERENCES reservations(id)
        ON DELETE CASCADE
);

CREATE TABLE recuperations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id INT NOT NULL,
    statut ENUM('non_recupere', 'recupere')
        DEFAULT 'non_recupere',
    date_remise DATETIME,

    FOREIGN KEY (reservation_id) REFERENCES reservations(id)
        ON DELETE CASCADE
);

CREATE TABLE achats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fournisseur VARCHAR(150) NOT NULL,
    produit_id INT NOT NULL,
    quantite INT NOT NULL,
    prix_total DECIMAL(10,2) NOT NULL,
    date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (produit_id) REFERENCES produits(id)
);

-- Compte de test (mot de passe haché avec password_hash() / PASSWORD_DEFAULT)
-- admin@eco.mg / admin123
-- test@eco.mg  / test123

INSERT INTO users (nom, email, mot_de_passe, role) VALUES
    ('Admin Test', 'admin@eco.mg', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
    ('Utilisateur Test', 'test@eco.mg', '$2y$10$6YiVJdX5JqRqJqZq6Yq6XuXOzF5Z2xY5XH9rT5z6i6A6O6U6w6U6u', 'agent_commercial');


-- Créer un trigger qui s'exécute chaque jour à minuit
-- Note : MySQL ne permet pas les triggers programmés directement.
-- On va utiliser un EVENT (programmateur MySQL)

-- 1. Activer l'event scheduler
SET GLOBAL event_scheduler = ON;

-- 2. Créer l'event qui vérifie les dépenses récurrentes chaque jour à minuit
CREATE EVENT IF NOT EXISTS event_generate_recurring_transactions
ON SCHEDULE EVERY 1 DAY
STARTS '2026-01-01 00:00:00'
DO
BEGIN
    -- Insérer les transactions pour les dépenses récurrentes dont le jour d'échéance est aujourd'hui
    INSERT INTO `transaction` (id_depense, montant, date_transaction, statut_validation, source_type)
    SELECT 
        d.id_depense,
        d.montant_defaut AS montant,
        CURDATE() AS date_transaction,
        'AUTO_VALIDEE' AS statut_validation,
        'RECURRENCE_AUTO' AS source_type
    FROM `depense` d
    WHERE 
        d.est_recurrent = 1 
        AND d.jour_echeance = DAY(CURDATE())
        AND d.deleted_at IS NULL
        -- Éviter les doublons pour le même jour
        AND NOT EXISTS (
            SELECT 1 FROM `transaction` t 
            WHERE t.id_depense = d.id_depense 
            AND t.date_transaction = CURDATE()
        );
END;
ALTER TABLE packs
    ADD COLUMN type ENUM('personnalized', 'ordinary') DEFAULT 'ordinary' AFTER statut,
    ADD COLUMN created_at DATETIME NULL AFTER type,
    ADD COLUMN updated_at DATETIME NULL AFTER created_at,
    ADD COLUMN deleted_at DATETIME NULL AFTER updated_at;

INSERT INTO produit (nom, categorie, quantite, prix_achat, statut) VALUES
    ('Produit A', 'Catégorie 1', 100, 1000.00, 'disponible'),
    ('Produit B', 'Catégorie 2', 50, 2000.00, 'disponible'),
    ('Produit C', 'Catégorie 1', 200, 500.00, 'indisponible');

INSERT INTO produit (nom, categorie, quantite, prix_achat, statut) VALUES
    ('Riz blanc 5kg', 'Céréales', 200, 12500.00, 'disponible'),
    ('Riz blanc 1kg', 'Céréales', 300, 2800.00, 'disponible'),
    ('Huile végétale 1L', 'Huiles', 150, 8500.00, 'disponible'),
    ('Huile végétale 5L', 'Huiles', 80, 38000.00, 'disponible'),
    ('Sucre en poudre 1kg', 'Sucreries', 180, 4500.00, 'disponible'),
    ('Sucre en morceaux 1kg', 'Sucreries', 120, 5200.00, 'disponible'),
    ('Farine de blé 2kg', 'Céréales', 120, 6500.00, 'disponible'),
    ('Farine de maïs 1kg', 'Céréales', 100, 3500.00, 'disponible'),
    ('Pâtes alimentaires 500g', 'Pâtes', 250, 3200.00, 'disponible'),
    ('Pâtes alimentaires 1kg', 'Pâtes', 180, 5800.00, 'disponible'),
    ('Lait en poudre 400g', 'Produits laitiers', 90, 7500.00, 'disponible'),
    ('Lait concentré sucré 397g', 'Produits laitiers', 70, 4500.00, 'disponible'),
    ('Beurre 250g', 'Produits laitiers', 60, 6500.00, 'disponible'),
    ('Fromage 200g', 'Produits laitiers', 45, 8000.00, 'disponible'),
    ('Œufs (boîte de 30)', 'Œufs', 40, 15000.00, 'disponible'),
    ('Œufs (boîte de 12)', 'Œufs', 80, 6800.00, 'disponible'),
    ('Pommes de terre 5kg', 'Légumes', 100, 12000.00, 'disponible'),
    ('Oignons 5kg', 'Légumes', 90, 10000.00, 'disponible'),
    ('Ail 1kg', 'Légumes', 60, 8000.00, 'disponible'),
    ('Tomates concentrées 70g', 'Conserves', 200, 2200.00, 'disponible'),
    ('Petits pois en boîte 400g', 'Conserves', 150, 3500.00, 'disponible'),
    ('Haricots verts en boîte 400g', 'Conserves', 130, 3800.00, 'disponible'),
    ('Miel 500g', 'Sucreries', 50, 12000.00, 'disponible'),
    ('Confiture 350g', 'Sucreries', 70, 5500.00, 'disponible'),
    ('Biscuits 200g', 'Snacks', 160, 2500.00, 'disponible'),
    ('Café moulu 250g', 'Boissons', 80, 9000.00, 'disponible'),
    ('Thé en sachets 25 sachets', 'Boissons', 100, 3500.00, 'disponible'),
    ('Eau minérale 1.5L', 'Boissons', 120, 1500.00, 'disponible'),
    ('Jus de fruit 1L', 'Boissons', 90, 4200.00, 'disponible'),
    ('Soda 33cl (canette)', 'Boissons', 200, 2500.00, 'disponible');

-- Produits en rupture de stock
INSERT INTO produits (nom, categorie, quantite, prix_achat, statut) VALUES
    ('Riz parfumé 5kg', 'Céréales', 0, 14500.00, 'indisponible'),
    ('Lentilles 1kg', 'Légumineuses', 0, 4800.00, 'indisponible'),
    ('Poisson en boîte 150g', 'Conserves', 0, 6500.00, 'indisponible');
