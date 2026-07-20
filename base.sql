-- Réinitialisation
DROP TABLE IF EXISTS transactions;
DROP TABLE IF EXISTS baremes_frais;
DROP TABLE IF EXISTS types_operations;
DROP TABLE IF EXISTS prefixes;
DROP TABLE IF EXISTS operateurs;
DROP TABLE IF EXISTS clients;
DROP TABLE IF EXISTS users;

-- Structure
CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    password TEXT,
    role TEXT NOT NULL,
    client_id INTEGER,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id)
);

CREATE TABLE operateurs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE prefixes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    prefixe TEXT NOT NULL UNIQUE,
    operateur_id INTEGER NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (operateur_id) REFERENCES operateurs(id)
);

CREATE TABLE clients (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT,
    telephone TEXT NOT NULL UNIQUE,
    solde REAL NOT NULL DEFAULT 0,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE types_operations (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL UNIQUE,
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE baremes_frais (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    type_operation_id INTEGER NOT NULL,
    montant_min REAL NOT NULL,
    montant_max REAL,  -- NULL = infini
    frais REAL NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(type_operation_id) REFERENCES types_operations(id)
);

CREATE TABLE transactions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    type_operation_id INTEGER NOT NULL,
    expediteur_id INTEGER,
    destinataire_id INTEGER,
    montant REAL NOT NULL,
    frais REAL NOT NULL DEFAULT 0,
    date_transaction DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(type_operation_id) REFERENCES types_operations(id),
    FOREIGN KEY(expediteur_id) REFERENCES clients(id),
    FOREIGN KEY(destinataire_id) REFERENCES clients(id)
);

-- Vues
CREATE VIEW vue_situation_clients AS
SELECT id, nom, telephone, solde FROM clients;

CREATE VIEW vue_gains_operateur AS
SELECT
    t.nom AS type_operation,
    SUM(tr.frais) AS total_frais
FROM transactions tr
JOIN types_operations t ON t.id = tr.type_operation_id
WHERE tr.frais > 0
GROUP BY t.nom;

-- Données initiales
INSERT INTO users(username, password, role) VALUES ('admin', 'admin123', 'OPERATEUR');
INSERT INTO operateurs(nom) VALUES ('Mobile Money');

INSERT INTO prefixes(prefixe, operateur_id) VALUES ('033', 1), ('037', 1);

INSERT INTO types_operations(nom, description) VALUES
    ('Depot', 'Dépôt d''argent sur le compte'),
    ('Retrait', 'Retrait d''argent du compte'),
    ('Transfert', 'Transfert d''argent entre comptes');

-- Barèmes
INSERT INTO baremes_frais(type_operation_id, montant_min, montant_max, frais) VALUES
    -- Dépôt : frais 0
    (1, 0, NULL, 0),
    -- Retrait
    (2, 0, 50000, 500),
    (2, 50001, 100000, 1000),
    (2, 100001, NULL, 2000),
    -- Transfert
    (3, 0, 50000, 300),
    (3, 50001, 100000, 700),
    (3, 100001, NULL, 1500);

-- Clients de test (pour commencer)
INSERT INTO clients(nom, telephone, solde) VALUES
    ('Jean Dupont', '0331234567', 15000),
    ('Marie Martin', '0377654321', 25000);