CREATE DATABASE regime_app;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS recharge_demande;
DROP TABLE IF EXISTS achat_regime;
DROP TABLE IF EXISTS suggestion_sport;
DROP TABLE IF EXISTS code;
DROP TABLE IF EXISTS activite_sportive;
DROP TABLE IF EXISTS regime;
DROP TABLE IF EXISTS objectif;
DROP TABLE IF EXISTS administrateur;
DROP TABLE IF EXISTS utilisateur;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE utilisateur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    email VARCHAR(80) NOT NULL UNIQUE,
    genre VARCHAR(20) NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    taille DECIMAL(5,2) NULL,
    poids DECIMAL(5,2) NULL,
    porte_monnaie DECIMAL(10,2) NOT NULL DEFAULT 0,
    est_gold TINYINT(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE administrateur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    email VARCHAR(80) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE objectif (
    id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE regime (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_objectif INT NOT NULL,
    nom VARCHAR(50) NOT NULL,
    prix_journalier DECIMAL(10,2) NOT NULL,
    poids_par_jour DECIMAL(5,3) NOT NULL,
    pourcentage_viande DECIMAL(5,2) NOT NULL DEFAULT 0,
    pourcentage_poisson DECIMAL(5,2) NOT NULL DEFAULT 0,
    pourcentage_volaille DECIMAL(5,2) NOT NULL DEFAULT 0,
    CONSTRAINT fk_regime_objectif FOREIGN KEY (id_objectif) REFERENCES objectif(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE activite_sportive (
    id INT AUTO_INCREMENT PRIMARY KEY,
    designation VARCHAR(50) NOT NULL,
    calories_moyennes_heure INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE suggestion_sport (
    id_regime INT NOT NULL,
    id_activite INT NOT NULL,
    duree_minutes_jour INT NOT NULL,
    PRIMARY KEY (id_regime, id_activite),
    CONSTRAINT fk_suggestion_regime FOREIGN KEY (id_regime) REFERENCES regime(id) ON DELETE CASCADE,
    CONSTRAINT fk_suggestion_activite FOREIGN KEY (id_activite) REFERENCES activite_sportive(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE code (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_code VARCHAR(30) NOT NULL UNIQUE,
    montant DECIMAL(10,2) NOT NULL,
    est_utilise TINYINT(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE recharge_demande (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT NOT NULL,
    id_code INT NOT NULL,
    statut ENUM('en_attente', 'valide', 'refuse') NOT NULL DEFAULT 'en_attente',
    date_demande DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_validation DATETIME NULL,
    CONSTRAINT fk_recharge_utilisateur FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id) ON DELETE CASCADE,
    CONSTRAINT fk_recharge_code FOREIGN KEY (id_code) REFERENCES code(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE achat_regime (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT NOT NULL,
    id_regime INT NOT NULL,
    date_debut DATE NOT NULL,
    duree_jours INT NOT NULL,
    montant_total DECIMAL(10,2) NOT NULL,
    CONSTRAINT fk_achat_utilisateur FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id) ON DELETE CASCADE,
    CONSTRAINT fk_achat_regime FOREIGN KEY (id_regime) REFERENCES regime(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO administrateur (nom, email, mot_de_passe) VALUES
('Admin', 'admin@regime.local', '$2y$10$GCZmTZfRgKEQ1I2RqzChKet05K8yoD4l.Fh5FhC.M4.w/cmEzXTW6');

INSERT INTO objectif (libelle) VALUES
('Augmenter son poids'),
('Reduire son poids'),
('Atteindre son IMC ideal');

INSERT INTO utilisateur (nom, email, genre, mot_de_passe, taille, poids, porte_monnaie, est_gold) VALUES
('Jean', 'jean@mail.com', 'Homme', '$2y$10$nI390pxNsDxTNeVX4WBjF.RFAa4//H0TvJjuaTd8u0iEnOgBlaO/e', 180, 75, 80000, 0),
('Liana', 'liana@mail.com', 'Femme', '$2y$10$nI390pxNsDxTNeVX4WBjF.RFAa4//H0TvJjuaTd8u0iEnOgBlaO/e', 165, 55, 60000, 1),
('Mickael', 'mike@mail.com', 'Homme', '$2y$10$nI390pxNsDxTNeVX4WBjF.RFAa4//H0TvJjuaTd8u0iEnOgBlaO/e', 175, 90, 45000, 0),
('Sarah', 'sarah@mail.com', 'Femme', '$2y$10$nI390pxNsDxTNeVX4WBjF.RFAa4//H0TvJjuaTd8u0iEnOgBlaO/e', 160, 68, 70000, 0),
('Toky', 'toky@mail.com', 'Homme', '$2y$10$nI390pxNsDxTNeVX4WBjF.RFAa4//H0TvJjuaTd8u0iEnOgBlaO/e', 170, 60, 30000, 0);

INSERT INTO regime (id_objectif, nom, prix_journalier, poids_par_jour, pourcentage_viande, pourcentage_poisson, pourcentage_volaille) VALUES
(1, 'Mass Gain Pro', 15000, 0.200, 40, 30, 30),
(2, 'Legerete Plus', 12000, -0.150, 10, 60, 30),
(3, 'Equilibre Bio', 10000, 0.010, 20, 40, 40),
(2, 'Ultra Keto', 18000, -0.300, 50, 25, 25),
(1, 'Hyper Proteine', 16000, 0.250, 60, 20, 20);

INSERT INTO activite_sportive (designation, calories_moyennes_heure) VALUES
('Course a pied', 600),
('Natation', 500),
('Musculation', 400),
('Yoga', 200),
('Cyclisme', 450);

INSERT INTO suggestion_sport (id_regime, id_activite, duree_minutes_jour) VALUES
(1, 3, 45),
(2, 1, 35),
(3, 4, 30),
(4, 5, 50),
(5, 3, 60);

INSERT INTO code (numero_code, montant) VALUES
('CODE01', 5000), ('CODE02', 10000), ('CODE03', 20000), ('CODE04', 50000), ('CODE05', 5000),
('CODE06', 10000), ('CODE07', 20000), ('CODE08', 50000), ('CODE09', 5000), ('CODE10', 10000),
('CODE11', 20000), ('CODE12', 50000), ('CODE13', 5000), ('CODE14', 10000), ('CODE15', 20000);
