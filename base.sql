sqlite3 mobile_money.db

--Utilisateurs 
    CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    password TEXT,
    role TEXT NOT NULL, 
    client_id INTEGER,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (client_id) REFERENCES clients(id)
);

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


--Données

    --users
    INSERT OR IGNORE INTO users(username, password, role)
    VALUES ('admin', 'admin123', 'OPERATEUR');

    --operateurs
    INSERT OR IGNORE INTO operateurs(nom)
    VALUES ('Mobile Money');

    --prefixes
    INSERT OR IGNORE INTO prefixes(prefixe, operateur_id)
    VALUES
    ('033',1),
    ('037',1);

    --types_operations
    INSERT OR IGNORE INTO types_operations(nom)
    VALUES
    ('Depot'),
    ('Retrait'),
    ('Transfert');

    --clients
    INSERT OR IGNORE INTO clients(nom, telephone, solde)
    VALUES ('Client Démo', '0331234567', 50000);

    --transactions
    INSERT OR IGNORE INTO transactions(type_operation_id, expediteur_id, destinataire_id, montant, frais)
    VALUES (1, NULL, 1, 50000, 0);

--Exemples de Barèmes Frais
    --Dépot
       INSERT OR IGNORE INTO baremes_frais(type_operation_id,montant_min,montant_max,frais)
        VALUES
        (1,0,999999999,0);

    --Retrait
        INSERT OR IGNORE INTO baremes_frais(type_operation_id,montant_min,montant_max,frais)
        VALUES
        (2,0,50000,500),
        (2,50001,100000,1000),
        (2,100001,500000,2000);

    --Transfert
       INSERT OR IGNORE INTO baremes_frais(type_operation_id,montant_min,montant_max,frais)
        VALUES
        (3,0,50000,300),
        (3,50001,100000,700),
        (3,100001,500000,1500);