PRAGMA foreign_keys = ON;

CREATE TABLE prefixes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    prefix TEXT NOT NULL,
    nom TEXT NOT NULL
);

CREATE TABLE numero_telephone (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    prefix_id INTEGER NOT NULL,
    number TEXT NOT NULL,
    solde NUMERIC NOT NULL,
    FOREIGN KEY (prefix_id) REFERENCES prefixes(id)
);

CREATE TABLE frais_retrait (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    montant_min NUMERIC NOT NULL,
    montant_max NUMERIC NOT NULL
);

CREATE TABLE frais_transfert (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    montant_min NUMERIC NOT NULL,
    montant_max NUMERIC NOT NULL
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