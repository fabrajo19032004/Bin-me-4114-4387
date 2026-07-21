-- Ajouter les colonnes
ALTER TABLE prefixes ADD COLUMN est_local INTEGER DEFAULT 1;
ALTER TABLE operateurs ADD COLUMN commission_pourcentage REAL DEFAULT 0;
ALTER TABLE transactions ADD COLUMN est_vers_autre_operateur INTEGER DEFAULT 0;
ALTER TABLE transactions ADD COLUMN commission REAL DEFAULT 0;


ALTER TABLE clients ADD COLUMN pourcentage_epargne REAL DEFAULT 0;



-- Mettre à jour les préfixes existants
UPDATE prefixes SET est_local = 1 WHERE operateur_id = 1;

-- (Optionnel) Ajouter un opérateur externe pour les tests
INSERT OR IGNORE INTO operateurs (nom, commission_pourcentage) VALUES ('Autre Opérateur', 10.0);
INSERT OR IGNORE INTO prefixes (prefixe, operateur_id, est_local) VALUES ('032', 2, 0);
INSERT OR IGNORE INTO prefixes (prefixe, operateur_id, est_local) VALUES ('034', 2, 0);


--Alea1
ALTER TABLE transactions ADD COLUMN reduction REAL DEFAULT 0;
ALTER TABLE operateurs ADD COLUMN reductions_pourcentage REAL DEFAULT 0;

INSERT OR IGNORE INTO operateurs (nom, reductions_pourcentage) VALUES ('Autre Opérateur', 20.0);