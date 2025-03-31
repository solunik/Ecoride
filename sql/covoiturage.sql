-- Création de la base de données
CREATE DATABASE IF NOT EXISTS covoiturage;
USE covoiturage;

-- Table avis
CREATE TABLE IF NOT EXISTS avis (
  avis_id INT(11) NOT NULL AUTO_INCREMENT,
  commentaire VARCHAR(255) DEFAULT NULL,
  note INT(11) DEFAULT NULL,
  statut VARCHAR(50) DEFAULT NULL,
  utilisateur_id INT(11) DEFAULT NULL,
  PRIMARY KEY (avis_id),
  FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(utilisateur_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertion de données dans avis
INSERT INTO avis (commentaire, note, statut, utilisateur_id) VALUES
('grand pilote Mario Kart', 4, NULL, 18),
('cool', 5, NULL, 18);

-- Table configuration
CREATE TABLE IF NOT EXISTS configuration (
  id_configuration INT(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (id_configuration)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertion de données dans configuration
INSERT INTO configuration () VALUES (NULL);

-- Table covoiturage
CREATE TABLE IF NOT EXISTS covoiturage (
  covoiturage_id INT(11) NOT NULL AUTO_INCREMENT,
  date_depart DATE DEFAULT NULL,
  heure_depart TIME DEFAULT NULL,
  lieu_depart VARCHAR(50) DEFAULT NULL,
  date_arrive DATE DEFAULT NULL,
  heure_arrivee TIME DEFAULT NULL,
  lieu_arrivee VARCHAR(50) DEFAULT NULL,
  statut VARCHAR(50) DEFAULT NULL,
  nb_place INT(11) DEFAULT NULL,
  prix_personne FLOAT DEFAULT NULL,
  voiture_id INT(11) NOT NULL,
  conducteur_id INT(11) DEFAULT NULL,
  PRIMARY KEY (covoiturage_id),
  FOREIGN KEY (voiture_id) REFERENCES voiture(voiture_id),
  FOREIGN KEY (conducteur_id) REFERENCES utilisateur(utilisateur_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertion de données dans covoiturage
INSERT INTO covoiturage (date_depart, heure_depart, lieu_depart, date_arrive, heure_arrivee, lieu_arrivee, statut, nb_place, prix_personne, voiture_id, conducteur_id) VALUES
('2025-12-31', '12:00:00', 'Paris', '2025-12-31', '13:00:00', 'Gagny', 'go', 3, 3, 5, 18),
('2025-12-31', '16:00:00', 'Paris', '2025-12-31', '17:00:00', 'Gagny', 'go', 3, 2, 4, 16);

-- Table marque
CREATE TABLE IF NOT EXISTS marque (
  marque_id INT(11) NOT NULL AUTO_INCREMENT,
  libelle VARCHAR(50) NOT NULL,
  PRIMARY KEY (marque_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertion de données dans marque
INSERT INTO marque (libelle) VALUES
('renault'),
('audi'),
('bmw');

-- Table parametre
CREATE TABLE IF NOT EXISTS parametre (
  parametre_id INT(11) NOT NULL AUTO_INCREMENT,
  propriete VARCHAR(50) DEFAULT NULL,
  valeur VARCHAR(50) DEFAULT NULL,
  id_configuration INT(11) DEFAULT NULL,
  PRIMARY KEY (parametre_id),
  FOREIGN KEY (id_configuration) REFERENCES configuration(id_configuration)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertion de données dans parametre
INSERT INTO parametre (propriete, valeur, id_configuration) VALUES
('Ecoride', 'CovoiturageApp', 1);

-- Table role
CREATE TABLE IF NOT EXISTS role (
  role_id INT(11) NOT NULL AUTO_INCREMENT,
  libelle VARCHAR(50) NOT NULL,
  PRIMARY KEY (role_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertion de données dans role
INSERT INTO role (libelle) VALUES
('chauffeur'),
('passager'),
('administrateur'),
('employé');

-- Table utilisateur
CREATE TABLE IF NOT EXISTS utilisateur (
  utilisateur_id INT(11) NOT NULL AUTO_INCREMENT,
  nom VARCHAR(50) DEFAULT NULL,
  prenom VARCHAR(50) DEFAULT NULL,
  email VARCHAR(50) DEFAULT NULL,
  password VARCHAR(255) DEFAULT NULL,
  telephone INT(11) DEFAULT NULL,
  adresse VARCHAR(50) DEFAULT NULL,
  date_naissance DATE DEFAULT NULL,
  photo BLOB DEFAULT NULL,
  pseudo VARCHAR(50) DEFAULT NULL,
  credit INT(11) DEFAULT 20,
  PRIMARY KEY (utilisateur_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertion de données dans utilisateur
INSERT INTO utilisateur (nom, prenom, email, password, pseudo, credit) VALUES
('HAMMOUMI', 'Sofiène', 'hammoumi.a@hotmail.fr', '$2y$10$jBmq9L6uN5GUXvtBoI.XDe57Il.VCb9TIIYoRMp9lOqf8pjrVZxsW', 'SoluniK', 20),
('ast', 'momo', 'momo@hotmail.fr', '$2y$10$qAuo6LhC7UX.hnt2aeMXbejUlZ8vUV4IrSesUR20eoz0VSyqKH3ea', 'momo', 20),
('Utilisation', 'Manuel', 'manuel@utilisation.fr', '$2y$10$DBuTl8T2pBrnmQC87/Wo7eJQKx3juSpzfGgRb9uSlhNE/unNBJc6q', 'ManuelU', 20);

-- Table utilisateur_covoiturage
CREATE TABLE IF NOT EXISTS utilisateur_covoiturage (
  utilisateur_id INT(11) NOT NULL,
  covoiturage_id INT(11) NOT NULL,
  PRIMARY KEY (utilisateur_id, covoiturage_id),
  FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(utilisateur_id),
  FOREIGN KEY (covoiturage_id) REFERENCES covoiturage(covoiturage_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertion de données dans utilisateur_covoiturage
INSERT INTO utilisateur_covoiturage (utilisateur_id, covoiturage_id) VALUES
(16, 4),
(18, 3);

-- Table utilisateur_role
CREATE TABLE IF NOT EXISTS utilisateur_role (
  utilisateur_id INT(11) NOT NULL,
  role_id INT(11) NOT NULL,
  PRIMARY KEY (utilisateur_id, role_id),
  FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(utilisateur_id),
  FOREIGN KEY (role_id) REFERENCES role(role_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertion de données dans utilisateur_role
INSERT INTO utilisateur_role (utilisateur_id, role_id) VALUES
(18, 1);

-- Table voiture
CREATE TABLE IF NOT EXISTS voiture (
  voiture_id INT(11) NOT NULL AUTO_INCREMENT,
  modele VARCHAR(50) DEFAULT NULL,
  immatriculation VARCHAR(50) DEFAULT NULL,
  energie VARCHAR(50) DEFAULT NULL,
  couleur VARCHAR(50) DEFAULT NULL,
  date_premiere_immatriculation VARCHAR(50) DEFAULT NULL,
  utilisateur_id INT(11) DEFAULT NULL,
  marque_id INT(11) DEFAULT NULL,
  PRIMARY KEY (voiture_id),
  FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(utilisateur_id),
  FOREIGN KEY (marque_id) REFERENCES marque(marque_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertion de données dans voiture
INSERT INTO voiture (modele, immatriculation, energie, couleur, date_premiere_immatriculation, utilisateur_id, marque_id) VALUES
('megane', 'dd-800-dz', 'diesel', 'gris', '10-12-2014', 16, 1),
('x3', 'ma-212-mo', 'électrique', 'noir', '10-12-2022', 18, 2);
