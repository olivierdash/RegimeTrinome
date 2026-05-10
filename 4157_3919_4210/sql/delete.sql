-- Désactive les contraintes pour pouvoir vider les tables liées
SET FOREIGN_KEY_CHECKS = 0;

TRUNCATE TABLE achat_regime;
TRUNCATE TABLE recharge_demande;
TRUNCATE TABLE suggestion_sport;
TRUNCATE TABLE code;
TRUNCATE TABLE activite_sportive;
TRUNCATE TABLE regime;
TRUNCATE TABLE objectif;
TRUNCATE TABLE administrateur;
TRUNCATE TABLE utilisateur;

-- Réactive les contraintes
SET FOREIGN_KEY_CHECKS = 1;
