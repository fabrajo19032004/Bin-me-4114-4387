sqlite3 mobile_money.db

--Opérateur
CREATE TABLE operateurs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL
);

--Préfixes
CREATE TABLE prefixes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    prefixe TEXT NOT NULL UNIQUE,
    operateur_id INTEGER NOT NULL,
    FOREIGN KEY (operateur_id) REFERENCES operateurs(id)
);


--Clients
CREATE TABLE clients (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT,
    telephone TEXT NOT NULL UNIQUE,
    solde REAL NOT NULL DEFAULT 0,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
);

--Types d'operations
CREATE TABLE types_operations (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL UNIQUE
);

--Barèmes Frais
CREATE TABLE baremes_frais (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    type_operation_id INTEGER NOT NULL,
    montant_min REAL NOT NULL,
    montant_max REAL NOT NULL,
    frais REAL NOT NULL,
    FOREIGN KEY(type_operation_id)
        REFERENCES types_operations(id)
);


--Transactions
CREATE TABLE transactions (

    id INTEGER PRIMARY KEY AUTOINCREMENT,

    type_operation_id INTEGER NOT NULL,

    expediteur_id INTEGER,

    destinataire_id INTEGER,

    montant REAL NOT NULL,

    frais REAL NOT NULL DEFAULT 0,

    date_transaction DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY(type_operation_id)
        REFERENCES types_operations(id),

    FOREIGN KEY(expediteur_id)
        REFERENCES clients(id),

    FOREIGN KEY(destinataire_id)
        REFERENCES clients(id)
);


-- Vues pour l'ensemble
CREATE VIEW vue_situation_clients AS

SELECT

    id,
    nom,
    telephone,
    solde

FROM clients;


CREATE VIEW vue_gains_operateur AS

SELECT

    SUM(frais) AS gain_total

FROM transactions

WHERE frais > 0;