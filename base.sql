-- ============================================================
-- Base de données Mobile Money – Version 2 (avec extensions)
-- ============================================================

-- Supprimer les tables si elles existent (pour une réinitialisation propre)
DROP TABLE IF EXISTS transactions;
DROP TABLE IF EXISTS baremes_frais;
DROP TABLE IF EXISTS types_operations;
DROP TABLE IF EXISTS prefixes;
DROP TABLE IF EXISTS operateurs;
DROP TABLE IF EXISTS clients;
DROP TABLE IF EXISTS users;

-- ============================================================
-- Tables
-- ============================================================

-- Utilisateurs
CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    password TEXT,
    role TEXT NOT NULL,
    client_id INTEGER,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id)
);

-- Opérateurs (avec commission pour les transferts externes)
CREATE TABLE operateurs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    commission_pourcentage REAL DEFAULT 0   -- commission appliquée sur les transferts vers cet opérateur
);

-- Préfixes (avec indicateur local/externe)
CREATE TABLE prefixes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    prefixe TEXT NOT NULL UNIQUE,
    operateur_id INTEGER NOT NULL,
    est_local INTEGER DEFAULT 1,            -- 1 = local, 0 = externe
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (operateur_id) REFERENCES operateurs(id)
);

-- Clients
CREATE TABLE clients (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT,
    telephone TEXT NOT NULL UNIQUE,
    solde REAL NOT NULL DEFAULT 0,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Types d'opérations
CREATE TABLE types_operations (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL UNIQUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Barèmes de frais (avec montant_max NULL autorisé pour l'infini)
CREATE TABLE baremes_frais (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    type_operation_id INTEGER NOT NULL,
    montant_min REAL NOT NULL,
    montant_max REAL,   -- NULL = infini
    frais REAL NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (type_operation_id) REFERENCES types_operations(id)
);

-- Transactions (avec champs pour la Version 2)
CREATE TABLE transactions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    type_operation_id INTEGER NOT NULL,
    expediteur_id INTEGER,
    destinataire_id INTEGER,
    montant REAL NOT NULL,
    frais REAL NOT NULL DEFAULT 0,              -- total des frais (base + commission éventuelle)
    commission REAL DEFAULT 0,                  -- part de la commission externe (0 si interne)
    est_vers_autre_operateur INTEGER DEFAULT 0, -- 1 si transfert externe, 0 sinon
    date_transaction DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (type_operation_id) REFERENCES types_operations(id),
    FOREIGN KEY (expediteur_id) REFERENCES clients(id),
    FOREIGN KEY (destinataire_id) REFERENCES clients(id)
);

-- ============================================================
-- Vues
-- ============================================================

-- Vue : situation des clients
CREATE VIEW vue_situation_clients AS
SELECT id, nom, telephone, solde FROM clients;

-- Vue : gains par type (interne / externe) – pour l'opérateur
CREATE VIEW vue_gains_operateur AS
SELECT
    CASE WHEN est_vers_autre_operateur = 0 THEN 'Interne' ELSE 'Externe' END AS type_transfert,
    SUM(frais) AS total_frais
FROM transactions
WHERE type_operation_id = (SELECT id FROM types_operations WHERE nom = 'Transfert')
GROUP BY est_vers_autre_operateur;

-- ============================================================
-- Données initiales
-- ============================================================

-- Utilisateur admin
INSERT OR IGNORE INTO users(username, password, role)
VALUES ('admin', 'admin123', 'OPERATEUR');

-- Opérateurs
INSERT OR IGNORE INTO operateurs(nom, commission_pourcentage)
VALUES ('Mobile Money', 0);          -- opérateur local

INSERT OR IGNORE INTO operateurs(nom, commission_pourcentage)
VALUES ('Autre Opérateur', 10);      -- opérateur externe avec 10% de commission

-- Préfixes
-- Locaux
INSERT OR IGNORE INTO prefixes(prefixe, operateur_id, est_local)
VALUES ('033', 1, 1),
       ('037', 1, 1);

-- Externes (pour l'opérateur 2)
INSERT OR IGNORE INTO prefixes(prefixe, operateur_id, est_local)
VALUES ('032', 2, 0),
       ('034', 2, 0);

-- Types d'opérations
INSERT OR IGNORE INTO types_operations(nom)
VALUES ('Depot'),
       ('Retrait'),
       ('Transfert');

-- Clients (un client local de démonstration)
INSERT OR IGNORE INTO clients(nom, telephone, solde)
VALUES ('Jean Dupont', '0331234567', 15000);

-- Client externe pour tester les transferts externes
INSERT OR IGNORE INTO clients(nom, telephone, solde)
VALUES ('Marie Externe', '0321234567', 5000);

-- Barèmes de frais
-- Dépôt : frais 0
INSERT OR IGNORE INTO baremes_frais(type_operation_id, montant_min, montant_max, frais)
VALUES (1, 0, NULL, 0);

-- Retrait
INSERT OR IGNORE INTO baremes_frais(type_operation_id, montant_min, montant_max, frais)
VALUES (2, 0, 50000, 500),
       (2, 50001, 100000, 1000),
       (2, 100001, NULL, 2000);

-- Transfert
INSERT OR IGNORE INTO baremes_frais(type_operation_id, montant_min, montant_max, frais)
VALUES (3, 0, 50000, 300),
       (3, 50001, 100000, 700),
       (3, 100001, NULL, 1500);

-- Transaction de test (dépôt initial)
INSERT OR IGNORE INTO transactions(type_operation_id, expediteur_id, destinataire_id, montant, frais, commission, est_vers_autre_operateur)
VALUES (1, NULL, 1, 50000, 0, 0, 0);