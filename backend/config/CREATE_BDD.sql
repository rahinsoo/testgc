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
CREATE TABLE USER_
(
    id_user     SMALLINT,
    nom         VARCHAR(100),
    prenom      VARCHAR(100),
    identifiant VARCHAR(100) NOT NULL,
    password    VARCHAR(100) NOT NULL,
    adresse     INT,
    PRIMARY KEY (id_user),
    UNIQUE (identifiant),
    UNIQUE (password)
);

CREATE TABLE USER_ROLE
(
    id_user_role SMALLINT,
    role         VARCHAR(50),
    id_user      SMALLINT NOT NULL,
    PRIMARY KEY (id_user_role),
    UNIQUE (role),
    FOREIGN KEY (id_user) REFERENCES USER_ (id_user)
);

CREATE TABLE ABSENCES
(
    id_absence  INT,
    type        VARCHAR(50),
    commentaire VARCHAR(255),
    date_debut  DATETIME,
    date_fin    DATETIME,
    duree       VARCHAR(50),
    id_user     SMALLINT NOT NULL,
    PRIMARY KEY (id_absence),
    FOREIGN KEY (id_user) REFERENCES USER_ (id_user)
);

CREATE TABLE STATUS
(
    id_status SMALLINT,
    status    VARCHAR(50),
    PRIMARY KEY (id_status)
);

CREATE TABLE FICHE_INFO
(
    id_competence INT,
    nom           VARCHAR(50),
    commentaire   VARCHAR(50),
    PRIMARY KEY (id_competence)
);

CREATE TABLE ACTIVITE
(
    id_projet                 SMALLINT,
    nom                       VARCHAR(50),
    commentaire               VARCHAR(255),
    date_creation             DATE,
    date_fin                  DATE,
    estimation_tps_jour_homme INT,
    id_status                 SMALLINT NOT NULL,
    PRIMARY KEY (id_projet),
    FOREIGN KEY (id_status) REFERENCES STATUS (id_status)
);

CREATE TABLE TACHE
(
    id_tache         SMALLINT,
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
    FOREIGN KEY (id_user) REFERENCES USER_ (id_user),
    FOREIGN KEY (id_status) REFERENCES STATUS (id_status)
);

CREATE TABLE ENTREPRISE
(
    id_entreprise SMALLINT,
    nom           VARCHAR(100),
    numero_SIRET  VARCHAR(100),
    type          VARCHAR(100),
    information   VARCHAR(50),
    is_facturable BOOLEAN,
    adresse       SMALLINT,
    id_projet     SMALLINT,
    PRIMARY KEY (id_entreprise),
    FOREIGN KEY (id_projet) REFERENCES ACTIVITE (id_projet)
);

CREATE TABLE USER_ACTIVITE
(
    id_user   SMALLINT,
    id_projet SMALLINT,
    tjm       VARCHAR(50),
    PRIMARY KEY (id_user, id_projet),
    FOREIGN KEY (id_user) REFERENCES USER_ (id_user),
    FOREIGN KEY (id_projet) REFERENCES ACTIVITE (id_projet)
);

CREATE TABLE USER_FICHE_INFO
(
    id_user       SMALLINT,
    id_competence INT,
    PRIMARY KEY (id_user, id_competence),
    FOREIGN KEY (id_user) REFERENCES USER_ (id_user),
    FOREIGN KEY (id_competence) REFERENCES FICHE_INFO (id_competence)
);

--------------------------
--- création de nouvelles colonnes ---
--------------------------

