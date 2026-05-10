-- Objectifs
INSERT INTO objectif (libelle) VALUES ('Augmenter son poids'), ('Réduire son poids'), ('Atteindre son IMC idéal');

-- 5 Utilisateurs
INSERT INTO utilisateur (nom, email, genre, mot_de_passe, taille, poids) VALUES 
('Jean', 'jean@mail.com', 'Homme', '123', 180, 75),
('Liana', 'liana@mail.com', 'Femme', '123', 165, 55),
('Mickael', 'mike@mail.com', 'Homme', '123', 175, 90),
('Sarah', 'sarah@mail.com', 'Femme', '123', 160, 68),
('Toky', 'toky@mail.com', 'Homme', '123', 170, 60);

-- 5 Régimes avec composition %
INSERT INTO regime (id_objectif, nom, prix_journalier, poids_par_jour, pourcentage_viande, pourcentage_poisson, pourcentage_volaille) VALUES
(1, 'Mass Gain Pro', 15000, 0.200, 40, 30, 30),
(2, 'Légèreté Plus', 12000, -0.150, 10, 60, 30),
(3, 'Équilibre Bio', 10000, 0.010, 20, 40, 40),
(2, 'Ultra Keto', 18000, -0.300, 50, 25, 25),
(1, 'Hyper Protéiné', 16000, 0.250, 60, 20, 20);

-- 15 Codes
INSERT INTO code (numero_code, montant) VALUES 
('CODE01', 5000), ('CODE02', 10000), ('CODE03', 20000), ('CODE04', 50000), ('CODE05', 5000),
('CODE06', 10000), ('CODE07', 20000), ('CODE08', 50000), ('CODE09', 5000), ('CODE10', 10000),
('CODE11', 20000), ('CODE12', 50000), ('CODE13', 5000), ('CODE14', 10000), ('CODE15', 20000);

-- 5 Activités Sportives
INSERT INTO activite_sportive (designation, calories_moyennes_heure) VALUES
('Course à pied', 600), ('Natation', 500), ('Musculation', 400), ('Yoga', 200), ('Cyclisme', 450);