PRAGMA foreign_keys = ON;


CREATE TABLE prefixes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    prefix TEXT NOT NULL,
    nom TEXT NOT NULL
);

CREATE TABLE numero_telephone (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    prefix_id INTEGER NOT NULL,
    numero TEXT NOT NULL,
    solde NUMERIC NOT NULL,
    FOREIGN KEY (prefix_id) REFERENCES prefixes(id)
);

CREATE TABLE frais_retrait (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    montant_min NUMERIC NOT NULL,
    montant_max NUMERIC NOT NULL,
    frais NUMERIC NOT NULL  
);

CREATE TABLE frais_transfert (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    montant_min NUMERIC NOT NULL,
    montant_max NUMERIC NOT NULL,
    frais NUMERIC NOT NULL  
);

CREATE TABLE depot (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    numero_telephone_id INTEGER NOT NULL,
    montant NUMERIC NOT NULL,
    date_depot DATE NOT NULL,
    FOREIGN KEY (numero_telephone_id) REFERENCES numero_telephone(id)
);

CREATE TABLE retrait (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    numero_telephone_id INTEGER NOT NULL,
    montant NUMERIC NOT NULL,
    date_retrait DATE NOT NULL,
    FOREIGN KEY (numero_telephone_id) REFERENCES numero_telephone(id)
);

CREATE TABLE transfert (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    expediteur_id INTEGER NOT NULL,
    destinataire_id INTEGER NOT NULL,
    montant NUMERIC NOT NULL,
    date_transfert DATE NOT NULL,
    FOREIGN KEY(expediteur_id) REFERENCES numero_telephone(id),
    FOREIGN KEY(destinataire_id) REFERENCES numero_telephone(id)
);


-- 1. Vue des comptes clients avec détails
CREATE VIEW v_compte_client AS
SELECT 
    nt.id AS compte_id,
    p.prefix || nt.numero AS telephone,
    nt.solde,
    p.nom AS operateur,
    p.prefix
FROM numero_telephone nt
JOIN prefixes p ON nt.prefix_id = p.id;

-- 2. Vue des gains par type d'opération (frais calculés dynamiquement)
CREATE VIEW v_gains_par_operation AS
SELECT 
    'Retrait' AS type_operation,
    COUNT(r.id) AS nombre_operations,
    SUM(COALESCE(fr.frais, 0)) AS total_gains,
    AVG(COALESCE(fr.frais, 0)) AS gain_moyen,
    MIN(COALESCE(fr.frais, 0)) AS gain_min,
    MAX(COALESCE(fr.frais, 0)) AS gain_max
FROM retrait r
LEFT JOIN frais_retrait fr ON r.montant BETWEEN fr.montant_min AND fr.montant_max
UNION ALL
SELECT 
    'Transfert' AS type_operation,
    COUNT(t.id) AS nombre_operations,
    SUM(COALESCE(ft.frais, 0)) AS total_gains,
    AVG(COALESCE(ft.frais, 0)) AS gain_moyen,
    MIN(COALESCE(ft.frais, 0)) AS gain_min,
    MAX(COALESCE(ft.frais, 0)) AS gain_max
FROM transfert t
LEFT JOIN frais_transfert ft ON t.montant BETWEEN ft.montant_min AND ft.montant_max;

-- 3. Vue des gains journaliers
CREATE VIEW v_gains_journaliers AS
SELECT 
    r.date_retrait AS date_operation,
    'Retrait' AS type,
    COUNT(*) AS nb_operations,
    SUM(r.montant) AS montant_total,
    SUM(COALESCE(fr.frais, 0)) AS frais_percus
FROM retrait r
LEFT JOIN frais_retrait fr ON r.montant BETWEEN fr.montant_min AND fr.montant_max
GROUP BY r.date_retrait
UNION ALL
SELECT 
    t.date_transfert AS date_operation,
    'Transfert' AS type,
    COUNT(*) AS nb_operations,
    SUM(t.montant) AS montant_total,
    SUM(COALESCE(ft.frais, 0)) AS frais_percus
FROM transfert t
LEFT JOIN frais_transfert ft ON t.montant BETWEEN ft.montant_min AND ft.montant_max
GROUP BY t.date_transfert
ORDER BY date_operation DESC;

-- 4. Vue des gains mensuels
CREATE VIEW v_gains_mensuels AS
SELECT 
    strftime('%Y-%m', r.date_retrait) AS mois,
    'Retrait' AS type,
    COUNT(*) AS nb_operations,
    SUM(COALESCE(fr.frais, 0)) AS total_frais,
    SUM(r.montant) AS volume_transactions
FROM retrait r
LEFT JOIN frais_retrait fr ON r.montant BETWEEN fr.montant_min AND fr.montant_max
GROUP BY strftime('%Y-%m', r.date_retrait)
UNION ALL
SELECT 
    strftime('%Y-%m', t.date_transfert) AS mois,
    'Transfert' AS type,
    COUNT(*) AS nb_operations,
    SUM(COALESCE(ft.frais, 0)) AS total_frais,
    SUM(t.montant) AS volume_transactions
FROM transfert t
LEFT JOIN frais_transfert ft ON t.montant BETWEEN ft.montant_min AND ft.montant_max
GROUP BY strftime('%Y-%m', t.date_transfert)
ORDER BY mois DESC;

-- 5. Vue des transactions par client (détaillée)
CREATE VIEW v_operations_client AS
SELECT 
    nt.id AS client_id,
    p.prefix || nt.numero AS telephone,
    'Dépôt' AS type_operation,
    d.montant,
    d.date_depot AS date_operation,
    0 AS frais,
    '+' || d.montant AS variation,
    nt.solde AS solde_apres
FROM depot d
JOIN numero_telephone nt ON d.numero_telephone_id = nt.id
JOIN prefixes p ON nt.prefix_id = p.id
UNION ALL
SELECT 
    nt.id AS client_id,
    p.prefix || nt.numero AS telephone,
    'Retrait' AS type_operation,
    r.montant,
    r.date_retrait AS date_operation,
    COALESCE(fr.frais, 0) AS frais,
    '-' || (r.montant + COALESCE(fr.frais, 0)) AS variation,
    nt.solde AS solde_apres
FROM retrait r
JOIN numero_telephone nt ON r.numero_telephone_id = nt.id
JOIN prefixes p ON nt.prefix_id = p.id
LEFT JOIN frais_retrait fr ON r.montant BETWEEN fr.montant_min AND fr.montant_max
UNION ALL
SELECT 
    t.expediteur_id AS client_id,
    p.prefix || nt.numero AS telephone,
    'Transfert (émission)' AS type_operation,
    t.montant,
    t.date_transfert AS date_operation,
    COALESCE(ft.frais, 0) AS frais,
    '-' || (t.montant + COALESCE(ft.frais, 0)) AS variation,
    nt.solde AS solde_apres
FROM transfert t
JOIN numero_telephone nt ON t.expediteur_id = nt.id
JOIN prefixes p ON nt.prefix_id = p.id
LEFT JOIN frais_transfert ft ON t.montant BETWEEN ft.montant_min AND ft.montant_max
UNION ALL
SELECT 
    t.destinataire_id AS client_id,
    p.prefix || nt.numero AS telephone,
    'Transfert (réception)' AS type_operation,
    t.montant,
    t.date_transfert AS date_operation,
    0 AS frais,
    '+' || t.montant AS variation,
    nt.solde AS solde_apres
FROM transfert t
JOIN numero_telephone nt ON t.destinataire_id = nt.id
JOIN prefixes p ON nt.prefix_id = p.id
ORDER BY client_id, date_operation DESC;

-- 6. Vue des statistiques globales
CREATE VIEW v_statistiques_globales AS
SELECT 
    (SELECT COUNT(*) FROM numero_telephone) AS total_clients,
    (SELECT SUM(solde) FROM numero_telephone) AS masse_monetaire,
    (SELECT COUNT(*) FROM depot) AS total_depots,
    (SELECT COUNT(*) FROM retrait) AS total_retraits,
    (SELECT COUNT(*) FROM transfert) AS total_transferts,
    (SELECT SUM(montant) FROM depot) AS montant_total_depots,
    (SELECT SUM(montant) FROM retrait) AS montant_total_retraits,
    (SELECT SUM(montant) FROM transfert) AS montant_total_transferts,
    (SELECT SUM(COALESCE(fr.frais, 0)) FROM retrait r LEFT JOIN frais_retrait fr ON r.montant BETWEEN fr.montant_min AND fr.montant_max) AS total_frais_retraits,
    (SELECT SUM(COALESCE(ft.frais, 0)) FROM transfert t LEFT JOIN frais_transfert ft ON t.montant BETWEEN ft.montant_min AND ft.montant_max) AS total_frais_transferts,
    (SELECT SUM(COALESCE(fr.frais, 0)) FROM retrait r LEFT JOIN frais_retrait fr ON r.montant BETWEEN fr.montant_min AND fr.montant_max) + 
    (SELECT SUM(COALESCE(ft.frais, 0)) FROM transfert t LEFT JOIN frais_transfert ft ON t.montant BETWEEN ft.montant_min AND ft.montant_max) AS total_gains;

-- 7. Vue des clients les plus actifs
CREATE VIEW v_clients_actifs AS
SELECT 
    nt.id AS client_id,
    p.prefix || nt.numero AS telephone,
    nt.solde,
    (SELECT COUNT(*) FROM retrait r WHERE r.numero_telephone_id = nt.id) +
    (SELECT COUNT(*) FROM transfert t WHERE t.expediteur_id = nt.id OR t.destinataire_id = nt.id) AS nb_transactions,
    (SELECT SUM(montant) FROM retrait r WHERE r.numero_telephone_id = nt.id) +
    (SELECT SUM(montant) FROM transfert t WHERE t.expediteur_id = nt.id) AS volume_transactions_emises
FROM numero_telephone nt
JOIN prefixes p ON nt.prefix_id = p.id
ORDER BY nb_transactions DESC;

-- 8. Vue des frais par tranche
CREATE VIEW v_frais_tranches AS
SELECT 
    'Retrait' AS type_operation,
    montant_min,
    montant_max,
    frais,
    (montant_max - montant_min) AS amplitude
FROM frais_retrait
UNION ALL
SELECT 
    'Transfert' AS type_operation,
    montant_min,
    montant_max,
    frais,
    (montant_max - montant_min) AS amplitude
FROM frais_transfert
ORDER BY type_operation, montant_min;

-- 9. Vue du solde moyen par opérateur
CREATE VIEW v_solde_moyen_operateur AS
SELECT 
    p.nom AS operateur,
    p.prefix,
    COUNT(nt.id) AS nb_clients,
    AVG(nt.solde) AS solde_moyen,
    MIN(nt.solde) AS solde_min,
    MAX(nt.solde) AS solde_max,
    SUM(nt.solde) AS solde_total
FROM prefixes p
JOIN numero_telephone nt ON p.id = nt.prefix_id
GROUP BY p.id;

-- 10. Vue de répartition des gains par tranche de montant
CREATE VIEW v_repartition_gains AS
SELECT 
    'Retrait' AS type_operation,
    CASE 
        WHEN r.montant < 1000 THEN 'Moins de 1000'
        WHEN r.montant BETWEEN 1000 AND 5000 THEN '1000 - 5000'
        WHEN r.montant BETWEEN 5001 AND 10000 THEN '5001 - 10000'
        WHEN r.montant BETWEEN 10001 AND 50000 THEN '10001 - 50000'
        ELSE 'Plus de 50000'
    END AS tranche_montant,
    COUNT(*) AS nombre_operations,
    SUM(COALESCE(fr.frais, 0)) AS gains_totaux,
    AVG(COALESCE(fr.frais, 0)) AS gain_moyen
FROM retrait r
LEFT JOIN frais_retrait fr ON r.montant BETWEEN fr.montant_min AND fr.montant_max
GROUP BY tranche_montant
UNION ALL
SELECT 
    'Transfert' AS type_operation,
    CASE 
        WHEN t.montant < 1000 THEN 'Moins de 1000'
        WHEN t.montant BETWEEN 1000 AND 5000 THEN '1000 - 5000'
        WHEN t.montant BETWEEN 5001 AND 10000 THEN '5001 - 10000'
        WHEN t.montant BETWEEN 10001 AND 50000 THEN '10001 - 50000'
        ELSE 'Plus de 50000'
    END AS tranche_montant,
    COUNT(*) AS nombre_operations,
    SUM(COALESCE(ft.frais, 0)) AS gains_totaux,
    AVG(COALESCE(ft.frais, 0)) AS gain_moyen
FROM transfert t
LEFT JOIN frais_transfert ft ON t.montant BETWEEN ft.montant_min AND ft.montant_max
GROUP BY tranche_montant
ORDER BY type_operation, tranche_montant;

-- 11. Vue de l'historique complet client (côté client)
CREATE VIEW v_historique_client AS
SELECT 
    nt.id AS client_id,
    p.prefix || nt.numero AS telephone,
    'Dépôt' AS operation,
    d.montant,
    d.date_depot AS date_operation,
    '+' || d.montant AS variation,
    nt.solde AS solde_apres,
    0 AS frais_appliques
FROM depot d
JOIN numero_telephone nt ON d.numero_telephone_id = nt.id
JOIN prefixes p ON nt.prefix_id = p.id
UNION ALL
SELECT 
    nt.id AS client_id,
    p.prefix || nt.numero AS telephone,
    'Retrait' AS operation,
    r.montant,
    r.date_retrait AS date_operation,
    '-' || (r.montant + COALESCE(fr.frais, 0)) AS variation,
    nt.solde AS solde_apres,
    COALESCE(fr.frais, 0) AS frais_appliques
FROM retrait r
JOIN numero_telephone nt ON r.numero_telephone_id = nt.id
JOIN prefixes p ON nt.prefix_id = p.id
LEFT JOIN frais_retrait fr ON r.montant BETWEEN fr.montant_min AND fr.montant_max
UNION ALL
SELECT 
    t.expediteur_id AS client_id,
    p.prefix || nt.numero AS telephone,
    'Transfert envoyé' AS operation,
    t.montant,
    t.date_transfert AS date_operation,
    '-' || (t.montant + COALESCE(ft.frais, 0)) AS variation,
    nt.solde AS solde_apres,
    COALESCE(ft.frais, 0) AS frais_appliques
FROM transfert t
JOIN numero_telephone nt ON t.expediteur_id = nt.id
JOIN prefixes p ON nt.prefix_id = p.id
LEFT JOIN frais_transfert ft ON t.montant BETWEEN ft.montant_min AND ft.montant_max
UNION ALL
SELECT 
    t.destinataire_id AS client_id,
    p.prefix || nt.numero AS telephone,
    'Transfert reçu' AS operation,
    t.montant,
    t.date_transfert AS date_operation,
    '+' || t.montant AS variation,
    nt.solde AS solde_apres,
    0 AS frais_appliques
FROM transfert t
JOIN numero_telephone nt ON t.destinataire_id = nt.id
JOIN prefixes p ON nt.prefix_id = p.id
ORDER BY date_operation DESC;

-- 12. Vue pour l'authentification rapide (login avec numéro)
CREATE VIEW v_login_rapide AS
SELECT 
    nt.id,
    nt.prefix_id,
    nt.numero,
    p.prefix || nt.numero AS telephone_complet,
    nt.solde,
    p.nom AS operateur
FROM numero_telephone nt
JOIN prefixes p ON nt.prefix_id = p.id;

-- 13. Vue du résumé client (dashboard client)
CREATE VIEW v_resume_client AS
SELECT 
    nt.id AS client_id,
    p.prefix || nt.numero AS telephone,
    nt.solde,
    (SELECT COUNT(*) FROM depot d WHERE d.numero_telephone_id = nt.id) AS nb_depots,
    (SELECT COUNT(*) FROM retrait r WHERE r.numero_telephone_id = nt.id) AS nb_retraits,
    (SELECT COUNT(*) FROM transfert t WHERE t.expediteur_id = nt.id) AS nb_transferts_emis,
    (SELECT COUNT(*) FROM transfert t WHERE t.destinataire_id = nt.id) AS nb_transferts_recus,
    (SELECT SUM(montant) FROM depot d WHERE d.numero_telephone_id = nt.id) AS total_depose,
    (SELECT SUM(montant) FROM retrait r WHERE r.numero_telephone_id = nt.id) AS total_retire,
    (SELECT SUM(montant) FROM transfert t WHERE t.expediteur_id = nt.id) AS total_transfere,
    (SELECT SUM(montant) FROM transfert t WHERE t.destinataire_id = nt.id) AS total_recu,
    (SELECT SUM(COALESCE(fr.frais, 0)) FROM retrait r LEFT JOIN frais_retrait fr ON r.montant BETWEEN fr.montant_min AND fr.montant_max WHERE r.numero_telephone_id = nt.id) +
    (SELECT SUM(COALESCE(ft.frais, 0)) FROM transfert t LEFT JOIN frais_transfert ft ON t.montant BETWEEN ft.montant_min AND ft.montant_max WHERE t.expediteur_id = nt.id) AS total_frais_payes
FROM numero_telephone nt
JOIN prefixes p ON nt.prefix_id = p.id;

-- 14. Vue des anomalies
CREATE VIEW v_anomalies AS
SELECT 
    'Solde négatif' AS type_anomalie,
    nt.id AS client_id,
    p.prefix || nt.numero AS telephone,
    nt.solde AS valeur,
    'Attention : solde client négatif' AS description
FROM numero_telephone nt
JOIN prefixes p ON nt.prefix_id = p.id
WHERE nt.solde < 0
UNION ALL
SELECT 
    'Retrait sans tranche de frais' AS type_anomalie,
    r.numero_telephone_id AS client_id,
    p.prefix || nt.numero AS telephone,
    r.montant AS valeur,
    'Aucune tranche de frais ne correspond à ce montant' AS description
FROM retrait r
JOIN numero_telephone nt ON r.numero_telephone_id = nt.id
JOIN prefixes p ON nt.prefix_id = p.id
WHERE NOT EXISTS (
    SELECT 1 FROM frais_retrait fr 
    WHERE r.montant BETWEEN fr.montant_min AND fr.montant_max
)
UNION ALL
SELECT 
    'Transfert sans tranche de frais' AS type_anomalie,
    t.expediteur_id AS client_id,
    p.prefix || nt.numero AS telephone,
    t.montant AS valeur,
    'Aucune tranche de frais ne correspond à ce montant' AS description
FROM transfert t
JOIN numero_telephone nt ON t.expediteur_id = nt.id
JOIN prefixes p ON nt.prefix_id = p.id
WHERE NOT EXISTS (
    SELECT 1 FROM frais_transfert ft 
    WHERE t.montant BETWEEN ft.montant_min AND ft.montant_max
);

-- 15. Vue pour le reporting quotidien
CREATE VIEW v_reporting_quotidien AS
SELECT 
    date('now') AS jour,
    (SELECT COUNT(*) FROM depot WHERE date_depot = date('now')) AS depots_auj,
    (SELECT SUM(montant) FROM depot WHERE date_depot = date('now')) AS montant_depots_auj,
    (SELECT COUNT(*) FROM retrait WHERE date_retrait = date('now')) AS retraits_auj,
    (SELECT SUM(montant) FROM retrait WHERE date_retrait = date('now')) AS montant_retraits_auj,
    (SELECT SUM(COALESCE(fr.frais, 0)) FROM retrait r LEFT JOIN frais_retrait fr ON r.montant BETWEEN fr.montant_min AND fr.montant_max WHERE r.date_retrait = date('now')) AS frais_retraits_auj,
    (SELECT COUNT(*) FROM transfert WHERE date_transfert = date('now')) AS transferts_auj,
    (SELECT SUM(montant) FROM transfert WHERE date_transfert = date('now')) AS montant_transferts_auj,
    (SELECT SUM(COALESCE(ft.frais, 0)) FROM transfert t LEFT JOIN frais_transfert ft ON t.montant BETWEEN ft.montant_min AND ft.montant_max WHERE t.date_transfert = date('now')) AS frais_transferts_auj;

-- 16. Vue de performance par opérateur
CREATE VIEW v_performance_operateur AS
SELECT 
    p.nom AS operateur,
    COUNT(DISTINCT nt.id) AS nb_clients,
    COUNT(r.id) AS nb_retraits,
    SUM(r.montant) AS volume_retraits,
    SUM(COALESCE(fr.frais, 0)) AS gains_retraits,
    COUNT(t.id) AS nb_transferts,
    SUM(t.montant) AS volume_transferts,
    SUM(COALESCE(ft.frais, 0)) AS gains_transferts,
    SUM(COALESCE(fr.frais, 0)) + SUM(COALESCE(ft.frais, 0)) AS gains_totaux
FROM prefixes p
LEFT JOIN numero_telephone nt ON p.id = nt.prefix_id
LEFT JOIN retrait r ON nt.id = r.numero_telephone_id
LEFT JOIN frais_retrait fr ON r.montant BETWEEN fr.montant_min AND fr.montant_max
LEFT JOIN transfert t ON nt.id = t.expediteur_id OR nt.id = t.destinataire_id
LEFT JOIN frais_transfert ft ON t.montant BETWEEN ft.montant_min AND ft.montant_max
GROUP BY p.id;