DROP
DATABASE IF EXISTS data_punch;

----------------------------------
--- création de Base de donnée ---
----------------------------------

CREATE
DATABASE data_punch;
USE
data_punch;

-------------------------
--- création de table ---
-------------------------
CREATE TABLE ENTREPRISE
(
    id_entreprise SMALLINT AUTO_INCREMENT,
    nom           VARCHAR(100),
    numero_SIRET  BIGINT,
    type          VARCHAR(100),
    information   VARCHAR(50),
    is_facturable BOOLEAN,
    adresse       VARCHAR(250),
    PRIMARY KEY (id_entreprise)
);

CREATE TABLE user_role
(
    id_user_role SMALLINT AUTO_INCREMENT,
    role         VARCHAR(50),
    PRIMARY KEY (id_user_role),
    UNIQUE (role)
);

CREATE TABLE STATUS
(
    id_status SMALLINT AUTO_INCREMENT,
    status    VARCHAR(50),
    PRIMARY KEY (id_status)
);

CREATE TABLE FICHE_INFO
(
    id_competence INT AUTO_INCREMENT,
    nom           VARCHAR(50),
    commentaire   VARCHAR(50),
    PRIMARY KEY (id_competence)
);

CREATE TABLE utilisateur
(
    id_user      SMALLINT AUTO_INCREMENT,
    nom          VARCHAR(100),
    prenom       VARCHAR(100),
    identifiant  VARCHAR(100) NOT NULL,
    password     VARCHAR(100) NOT NULL,
    adresse      INT,
    id_user_role SMALLINT     NOT NULL,
    PRIMARY KEY (id_user),
    UNIQUE (identifiant),
    UNIQUE (password),
    FOREIGN KEY (id_user_role) REFERENCES user_role (id_user_role)
);

CREATE TABLE ACTIVITE
(
    id_projet                 SMALLINT AUTO_INCREMENT,
    nom                       VARCHAR(50),
    commentaire               VARCHAR(255),
    date_creation             DATE,
    date_fin                  DATE,
    estimation_tps_jour_homme INT,
    id_entreprise             SMALLINT NOT NULL,
    id_status                 SMALLINT NOT NULL,
    PRIMARY KEY (id_projet),
    FOREIGN KEY (id_entreprise) REFERENCES ENTREPRISE (id_entreprise),
    FOREIGN KEY (id_status) REFERENCES STATUS (id_status)
);

CREATE TABLE TACHE
(
    id_tache         SMALLINT AUTO_INCREMENT,
    description      VARCHAR(50),
    commentaire      VARCHAR(255),
    duree_jour_homme DECIMAL(15, 1),
    date_debut       DATETIME,
    date_fin         DATETIME,
    id_projet        SMALLINT NOT NULL,
    id_user          SMALLINT NOT NULL,
    id_status        SMALLINT NOT NULL,
    PRIMARY KEY (id_tache),
    FOREIGN KEY (id_projet) REFERENCES ACTIVITE (id_projet),
    FOREIGN KEY (id_user) REFERENCES utilisateur (id_user),
    FOREIGN KEY (id_status) REFERENCES STATUS (id_status)
);

CREATE TABLE ABSENCES
(
    id_absence  INT AUTO_INCREMENT,
    type        VARCHAR(50),
    commentaire VARCHAR(255),
    date_debut  DATETIME,
    date_fin    DATETIME,
    duree       VARCHAR(50),
    id_user     SMALLINT NOT NULL,
    PRIMARY KEY (id_absence),
    FOREIGN KEY (id_user) REFERENCES utilisateur (id_user)
);

CREATE TABLE USER_ACTIVITE
(
    id_user   SMALLINT AUTO_INCREMENT,
    id_projet SMALLINT,
    tjm       VARCHAR(50),
    PRIMARY KEY (id_user, id_projet),
    FOREIGN KEY (id_user) REFERENCES utilisateur (id_user),
    FOREIGN KEY (id_projet) REFERENCES ACTIVITE (id_projet)
);

CREATE TABLE USER_FICHE_INFO
(
    id_user       SMALLINT AUTO_INCREMENT,
    id_competence INT,
    PRIMARY KEY (id_user, id_competence),
    FOREIGN KEY (id_user) REFERENCES utilisateur (id_user),
    FOREIGN KEY (id_competence) REFERENCES FICHE_INFO (id_competence)
);


--------------------------
--- création de nouvelles colonnes ---
--------------------------

INSERT INTO `user_role` (`id_user_role`, `role`)
VALUES (NULL, 'administrateur'),
       (NULL, 'collaborateur'),
       (NULL, 'recruteur'),
       (NULL, 'directeur'),
       (NULL, 'compta');

INSERT INTO `utilisateur` (`id_user`, `nom`, `prenom`, `identifiant`, `password`, `adresse`, `id_user_role`)
VALUES (NULL, 'admin', 'admin', 'admin', 'admin', NULL, '1'),
       (NULL, 'Estanove', 'Xavier', 'estxav', 'estxav', NULL, '2'),
       (NULL, 'Henin', 'Laetitia', 'leahen', 'leahen', NULL, '4'),
       (NULL, 'Caucat', 'Mattéo', 'matcau', 'matcau', NULL, '3'),
       (NULL, 'Compta', 'Compta', 'compta', 'compta', NULL, '5');

INSERT INTO `ENTREPRISE` (`id_entreprise`, `nom`, `numero_SIRET`, `type`, `information`, `is_facturable`, `adresse`)
VALUES (NULL, 'CONSERVATOIRE NATIONAL DES ARTS ET METIERS', '19753471200017', '8542Z - Enseignement supérieur', 'Formation', NULL,
        '292 RUE SAINT-MARTIN, 75003 PARIS'),
       (NULL, 'DIGINAMIC', '81824197800050', 'Formation continue d’adultes', 'Informatique', NULL,
        'PARC MEDITERRANEE 40 RUE LOUIS LEPINE 34470 PEROLS');


