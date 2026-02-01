CREATE TABLE Specialite (
    ID_specialite CHAR(5),
    Nom VARCHAR(50) NOT NULL,
    CONSTRAINT pk_specialite PRIMARY KEY (ID_specialite)
);
CREATE TABLE Atelier (
    ID_atelier CHAR(5),
    Nom_A VARCHAR(50),
    ID_specialite CHAR(5) NOT NULL,
    CONSTRAINT pk_atelier PRIMARY KEY (ID_atelier),
    foreign key (ID_specialite) references Specialite(ID_specialite) ON DELETE CASCADE
);
CREATE TABLE Matieres_premieres (
    ID_mp CHAR(5),
    source VARCHAR(50) NOT NULL,
    nom VARCHAR(50) NOT NULL,
    CONSTRAINT pk_Mp PRIMARY KEY (ID_mp)
);
CREATE TABLE Sous_traitant (
    ID_st CHAR(5) ,
    adresse VARCHAR(100) NOT NULL,
     CONSTRAINT pk_st PRIMARY KEY (ID_st)
);
CREATE TABLE Entrepot (
    Id_entrepot CHAR(5),
    nom VARCHAR(50) NOT NULL,
    Region VARCHAR(50) NOT NULL,
    CONSTRAINT pk_entp PRIMARY KEY (Id_entrepot)
);
CREATE TABLE Intermittent (
    Id_intermittent CHAR(5),
    Prenom VARCHAR(50) NOT NULL,
    Nom VARCHAR(50) NOT NULL,
    Adresse VARCHAR(100) NOT NULL,
    CONSTRAINT pk_intmt PRIMARY KEY (Id_intermittent)
);
CREATE TABLE Flotte (
    ID_flotte CHAR(5),
    Nom VARCHAR(50) NOT NULL,
    CONSTRAINT pk_flotte PRIMARY KEY (ID_flotte)
);
CREATE TABLE Equipe (
    ID_equipe CHAR(5),
    Nom VARCHAR(50),
    ID_flotte CHAR(5),
    Id_entrepot CHAR(5),
    ID_atelier CHAR(5),
    CONSTRAINT pk_equipe PRIMARY KEY (ID_equipe),
    FOREIGN KEY (ID_flotte) REFERENCES Flotte(ID_flotte) ON DELETE CASCADE,
    FOREIGN KEY (Id_entrepot) REFERENCES Entrepot(Id_entrepot) ON DELETE CASCADE ,
    FOREIGN KEY (ID_atelier) REFERENCES Atelier(ID_atelier) ON DELETE CASCADE
);
CREATE TABLE Traineau (
    ID_traineau CHAR(5),
    capac_tr float CHECK (capac_tr > 0) NOT NULL,
    ID_flotte CHAR(5),
    CONSTRAINT pk_traineau PRIMARY KEY (ID_traineau),
    FOREIGN KEY (ID_flotte) REFERENCES Flotte(ID_flotte) ON DELETE CASCADE
);
CREATE TABLE Elfes (
    ID_elfe CHAR(5),
    Nom VARCHAR(50),
    dirige CHAR(1) CHECK (dirige IN ('0', '1')) NOT NULL,
    ID_equipe CHAR(5),
    CONSTRAINT pk_elfe PRIMARY KEY (ID_elfe),
    FOREIGN KEY (ID_equipe) REFERENCES Equipe(ID_equipe) ON DELETE CASCADE
);
CREATE TABLE Renne (
    Puce CHAR(5),
    Nom VARCHAR(50),
    couleur_nez VARCHAR(50) NOT NULL,
    position_re VARCHAR(50),
    capacite float CHECK (capacite > 0) NOT NULL,
    ID_traineau CHAR(5),
    CONSTRAINT pk_renne PRIMARY KEY (Puce),
    FOREIGN KEY (ID_traineau) REFERENCES Traineau(ID_traineau) ON DELETE CASCADE
);
CREATE TABLE Tournee (
    Id_tournee CHAR(5),
    Num_tournee VARCHAR(50),
    date_tr date NOT NULL,
    destination VARCHAR(50) NOT NULL,
    ID_traineau CHAR(5),
    CONSTRAINT pk_tournee PRIMARY KEY (Id_tournee),
    FOREIGN KEY (ID_traineau) REFERENCES Traineau(ID_traineau) ON DELETE CASCADE
);
CREATE TABLE Enfants (
    ID_enfants CHAR(5),
    Nom VARCHAR(50) NOT NULL,
    Prenom VARCHAR(50) NOT NULL,
    Adresse VARCHAR(100) NOT NULL,
    Id_tournee CHAR(5),
    CONSTRAINT pk_enfants PRIMARY KEY (ID_enfants),
    FOREIGN KEY (Id_tournee) REFERENCES Tournee(Id_tournee) ON DELETE CASCADE
);
CREATE TABLE liste_jouet (
    ID_liste CHAR(5),
    poids float CHECK (poids > 0) NOT NULL,
    ID_traineau CHAR(5) NOT NULL,
    ID_enfants CHAR(5) NOT NULL,
    CONSTRAINT pk_liste PRIMARY KEY (ID_liste),
    FOREIGN KEY (ID_traineau) REFERENCES Traineau(ID_traineau) ON DELETE CASCADE,
    FOREIGN KEY (ID_enfants) REFERENCES Enfants(ID_enfants) ON DELETE CASCADE
);
CREATE TABLE jouet (
    ID_jouet CHAR(5),
    Type_j VARCHAR(50) NOT NULL,
    CONSTRAINT pk_jouet PRIMARY KEY (ID_jouet)
);
CREATE TABLE remplace (
    ID_elfe_remplace_ CHAR(5) ,
    ID_elfe_est_remplace_ CHAR(5) ,
    PRIMARY KEY (ID_elfe_remplace_, ID_elfe_est_remplace_),
    FOREIGN KEY (ID_elfe_remplace_) REFERENCES Elfes(ID_elfe) ON DELETE CASCADE,
    FOREIGN KEY (ID_elfe_est_remplace_) REFERENCES Elfes(ID_elfe) ON DELETE CASCADE
);
CREATE TABLE commande (
    ID_enfants CHAR(5) ,
    ID_jouet CHAR(5) ,
    PRIMARY KEY (ID_enfants, ID_jouet),
    FOREIGN KEY (ID_enfants) REFERENCES Enfants(ID_enfants) ON DELETE CASCADE,
    FOREIGN KEY (ID_jouet) REFERENCES Jouet(ID_jouet) ON DELETE CASCADE
);
CREATE TABLE substitut (
    ID_jouet_remplace_ CHAR(5) ,
    ID_jouet_est_remplace CHAR(5) ,
    PRIMARY KEY (ID_jouet_remplace_, ID_jouet_est_remplace),
    FOREIGN KEY (ID_jouet_remplace_) REFERENCES Jouet(ID_jouet) ON DELETE CASCADE,
    FOREIGN KEY (ID_jouet_est_remplace) REFERENCES Jouet(ID_jouet) ON DELETE CASCADE
);
CREATE TABLE constituer_par (
    ID_mp CHAR(5),
    ID_jouet CHAR(5) ,
    PRIMARY KEY (ID_mp, ID_jouet),
    FOREIGN KEY (ID_mp) REFERENCES Matieres_premieres(ID_mp) ON DELETE CASCADE,
    FOREIGN KEY (ID_jouet) REFERENCES Jouet(ID_jouet) ON DELETE CASCADE
);
CREATE TABLE participe (
    Id_tournee CHAR(5) ,
    Id_intermittent CHAR(5),
    PRIMARY KEY (Id_tournee, Id_intermittent),
    FOREIGN KEY (Id_tournee) REFERENCES Tournee(Id_tournee) ON DELETE CASCADE,
    FOREIGN KEY (Id_intermittent) REFERENCES Intermittent(Id_intermittent) ON DELETE CASCADE
);
CREATE TABLE produit (
    ID_jouet CHAR(5) ,
    ID_st CHAR(5) ,
    date_pass date NOT NULL,
    PRIMARY KEY (ID_jouet, ID_st),
    FOREIGN KEY (ID_jouet) REFERENCES Jouet(ID_jouet) ON DELETE CASCADE ,
    FOREIGN KEY (ID_st) REFERENCES Sous_traitant(ID_st) ON DELETE CASCADE
);
CREATE TABLE fabrique (
    ID_atelier CHAR(5) ,
    ID_jouet CHAR(5) ,
    date_passage date NOT NULL,
    PRIMARY KEY (ID_atelier, ID_jouet),
    FOREIGN KEY (ID_atelier) REFERENCES Atelier(ID_atelier) ON DELETE CASCADE,
    FOREIGN KEY (ID_jouet) REFERENCES Jouet(ID_jouet) ON DELETE CASCADE
);
CREATE TABLE passe_par (
    Id_entrepot CHAR(5) ,
    Id_tournee CHAR(5) ,
    PRIMARY KEY (Id_entrepot, Id_tournee),
    FOREIGN KEY (Id_entrepot) REFERENCES Entrepot(Id_entrepot) ON DELETE CASCADE,
    FOREIGN KEY (Id_tournee) REFERENCES Tournee(Id_tournee) ON DELETE CASCADE
);
CREATE TABLE commander (
    ID_atelier CHAR(5) ,
    ID_mp CHAR(5) ,
    date_a date NOT NULL,
    PRIMARY KEY (ID_atelier, ID_mp),
    FOREIGN KEY (ID_atelier) REFERENCES Atelier(ID_atelier) ON DELETE CASCADE,
    FOREIGN KEY (ID_mp) REFERENCES Matieres_premieres(ID_mp) ON DELETE CASCADE
);
CREATE TABLE contient (
    ID_jouet CHAR(5) ,
    ID_liste CHAR(5) ,
    PRIMARY KEY (ID_jouet, ID_liste),
    FOREIGN KEY (ID_jouet) REFERENCES Jouet(ID_jouet) ON DELETE CASCADE,
    FOREIGN KEY (ID_liste) REFERENCES liste_jouet(ID_liste) ON DELETE CASCADE
);
create table possede (
ID_elfe char(5) ,
ID_specialite char(5) ,
type_s varchar(50),
PRIMARY KEY (ID_elfe,ID_specialite),
foreign key (ID_elfe) references Elfes(ID_elfe) ON DELETE CASCADE,
foreign key (ID_specialite) references specialite(ID_specialite) ON DELETE CASCADE,
CONSTRAINT type_s CHECK (type_s IN ('Principale', 'Secondaire'))
);

create table lutins(
ID_LUTIN varchar(50) primary key,
MOT_DE_PASSE varchar(500)
);

create table mdp(
mot_de_passe varchar(2000) primary key
);




--la table specialite--
INSERT INTO Specialite (ID_specialite, Nom) VALUES('SP001', 'menuiserie');
INSERT INTO Specialite (ID_specialite, Nom) VALUES('SP002', 'bricolage');
INSERT INTO Specialite (ID_specialite, Nom) VALUES('SP003', 'emballage');
INSERT INTO Specialite (ID_specialite, Nom) VALUES('SP004', 'logistique');
INSERT INTO Specialite (ID_specialite, Nom) VALUES('SP005', 'entretien');

--la table atelier --
INSERT INTO Atelier (ID_atelier, Nom_A, ID_specialite) VALUES('AT001', 'Atelier du Bois', 'SP001');
INSERT INTO Atelier (ID_atelier, Nom_A, ID_specialite) VALUES('AT002', 'Atelier du Materiel', 'SP002');
INSERT INTO Atelier (ID_atelier, Nom_A, ID_specialite) VALUES('AT003', 'Atelier d''embalage', 'SP003');
INSERT INTO Atelier (ID_atelier, Nom_A, ID_specialite) VALUES('AT004', 'Atelier logistique', 'SP004');
INSERT INTO Atelier (ID_atelier, Nom_A, ID_specialite) VALUES('AT005', 'Atelier d''entretien', 'SP005');

-- la table matiere premiere--
INSERT INTO Matieres_premieres (ID_mp, source, nom) VALUES('MP001', 'Amazon', 'Bois de sapin');
INSERT INTO Matieres_premieres (ID_mp, source, nom) VALUES('MP002', 'Amazon', 'Acier');
INSERT INTO Matieres_premieres (ID_mp, source, nom) VALUES('MP003', 'Amazon', 'Coton');
INSERT INTO Matieres_premieres (ID_mp, source, nom) VALUES('MP004', 'Amazon', 'Circuits imprimes');
INSERT INTO Matieres_premieres (ID_mp, source, nom) VALUES('MP005', 'Amazon', 'Plastique recycle');

--la table sous traitant --
INSERT INTO Sous_traitant (ID_st, adresse) VALUES('ST001', '123 Rue des Cadeaux');
INSERT INTO Sous_traitant (ID_st, adresse) VALUES('ST002', '456 Avenue du Jouet');
INSERT INTO Sous_traitant (ID_st, adresse) VALUES('ST003', '789 Chemin des Lutins');

--la table entrepot--
INSERT INTO Entrepot (Id_entrepot, nom, Region) VALUES('ET001', 'Entrepôt Europe Est', 'Europe Est');
INSERT INTO Entrepot (Id_entrepot, nom, Region) VALUES('ET002', 'Entrepôt Chine', 'Chine');
INSERT INTO Entrepot (Id_entrepot, nom, Region) VALUES('ET003', 'Entrepôt E', 'Grand Est');
INSERT INTO Entrepot (Id_entrepot, nom, Region) VALUES('ET004', 'Entrepôt Amerique N', 'Amerique du nord');
INSERT INTO Entrepot (Id_entrepot, nom, Region) VALUES('ET005', 'Entrepôt Amerqiue S', 'Amerique du sud');
INSERT INTO Entrepot (Id_entrepot, nom, Region) VALUES('ET006', 'Entrepôt Afrique', 'Afrique');
INSERT INTO Entrepot (Id_entrepot, nom, Region) VALUES('ET007', 'Entrepôt Europe', 'Europe');
INSERT INTO Entrepot (Id_entrepot, nom, Region) VALUES('ET008', 'Entrepôt Australie', 'Australie');
INSERT INTO Entrepot (Id_entrepot, nom, Region) VALUES('ET009', 'Entrepôt Asie', 'Asie');
INSERT INTO Entrepot (Id_entrepot, nom, Region) VALUES('ET010', 'Entrepôt Moyen orient', 'Moyen orient');
INSERT INTO Entrepot (Id_entrepot, nom, Region) VALUES('ET011', 'Entrepôt Afrique S', 'Afrique centrale');
INSERT INTO Entrepot (Id_entrepot, nom, Region) VALUES('ET012', 'Entrepôt Pole nord', 'Pole nord');

--la table intermittentnt--
INSERT INTO Intermittent (Id_intermittent, Prenom, Nom, Adresse) VALUES('IT001', 'Paul', 'Petitlutin', '1 Rue Magique');
INSERT INTO Intermittent (Id_intermittent, Prenom, Nom, Adresse) VALUES('IT002', 'Sophie', 'Grandeoreille', '2 Avenue Feerique');
INSERT INTO Intermittent (Id_intermittent, Prenom, Nom, Adresse) VALUES('IT003', 'Leo', 'Rapidlutin', '3 Sentier des etoiles');

--la table flotte--
INSERT INTO Flotte (ID_flotte, Nom) VALUES('F0001', 'Flotte Alasca');

--la table equipe--
INSERT INTO Equipe (ID_equipe, Nom, ID_flotte, Id_entrepot, ID_atelier) VALUES('EQ001', 'Equipe Alpha', 'F0001', 'ET001', 'AT001');
INSERT INTO Equipe (ID_equipe, Nom, ID_flotte, Id_entrepot, ID_atelier) VALUES('EQ002', 'Equipe Beta', 'F0001', 'ET002', 'AT002');
INSERT INTO Equipe (ID_equipe, Nom, ID_flotte, Id_entrepot, ID_atelier) VALUES('EQ003', 'Equipe Gamma', 'F0001', 'ET003', 'AT003');

--la table traineau--
INSERT INTO Traineau (ID_traineau, capac_tr, ID_flotte) VALUES('TR001', '850', 'F0001');
INSERT INTO Traineau (ID_traineau, capac_tr, ID_flotte) VALUES('TR002', '300', 'F0001');
INSERT INTO Traineau (ID_traineau, capac_tr, ID_flotte) VALUES('TR003', '700', 'F0001');
INSERT INTO Traineau (ID_traineau, capac_tr, ID_flotte) VALUES('TR004', '800', 'F0001');
INSERT INTO Traineau (ID_traineau, capac_tr, ID_flotte) VALUES('TR005', '780', 'F0001');
INSERT INTO Traineau (ID_traineau, capac_tr, ID_flotte) VALUES('TR006', '900', 'F0001');
INSERT INTO Traineau (ID_traineau, capac_tr, ID_flotte) VALUES('TR007', '650', 'F0001');
INSERT INTO Traineau (ID_traineau, capac_tr, ID_flotte) VALUES('TR008', '390', 'F0001');
INSERT INTO Traineau (ID_traineau, capac_tr, ID_flotte) VALUES('TR009', '725', 'F0001');
INSERT INTO Traineau (ID_traineau, capac_tr, ID_flotte) VALUES('TR010', '840', 'F0001');
INSERT INTO Traineau (ID_traineau, capac_tr, ID_flotte) VALUES('TR011', '521', 'F0001');
INSERT INTO Traineau (ID_traineau, capac_tr, ID_flotte) VALUES('TR012', '412', 'F0001');
--la table Elfes--
INSERT INTO Elfes (ID_elfe, Nom, dirige, ID_equipe) VALUES ('EL001', 'Martin','0', 'EQ001');
INSERT INTO Elfes (ID_elfe, Nom, dirige, ID_equipe) VALUES ('EL002', 'Paul','1', 'EQ002');
INSERT INTO Elfes (ID_elfe, Nom, dirige, ID_equipe) VALUES ('EL003', 'Arthur','0', 'EQ003');
INSERT INTO Elfes (ID_elfe, Nom, dirige, ID_equipe) VALUES ('EL004', 'Alain','0', 'EQ002');
INSERT INTO Elfes (ID_elfe, Nom, dirige, ID_equipe) VALUES ('EL005', 'David','0', 'EQ002');
INSERT INTO Elfes (ID_elfe, Nom, dirige, ID_equipe) VALUES ('EL006', 'Charle','1', 'EQ003');
INSERT INTO Elfes (ID_elfe, Nom, dirige, ID_equipe) VALUES ('EL007', 'Matys','1', 'EQ001');
INSERT INTO Elfes (ID_elfe, Nom, dirige, ID_equipe) VALUES ('EL008', 'Julien','0', 'EQ001');
INSERT INTO Elfes (ID_elfe, Nom, dirige, ID_equipe) VALUES ('EL009', 'Emilien','0', 'EQ002');
INSERT INTO Elfes (ID_elfe, Nom, dirige, ID_equipe) VALUES ('EL010', 'Prospet','0', 'EQ001');
INSERT INTO Elfes (ID_elfe, Nom, dirige, ID_equipe) VALUES ('EL011', 'Camile','0', 'EQ003');



--la table renne--
INSERT INTO Renne (Puce, Nom, couleur_nez, position_re, capacite, ID_traineau) VALUES('R0001', 'Rudolph', 'Rouge', 'Avant', 149, 'TR001');
INSERT INTO Renne (Puce, Nom, couleur_nez, position_re, capacite, ID_traineau) VALUES('R0002', 'Dancer', 'Bleu', 'Avant_gauche', 155, 'TR001');
INSERT INTO Renne (Puce, Nom, couleur_nez, position_re, capacite, ID_traineau) VALUES('R0003', 'Prancer', 'Vert', 'Milieu_droit', 158, 'TR001');
INSERT INTO Renne (Puce, Nom, couleur_nez, position_re, capacite, ID_traineau) VALUES('R0004', 'Vixen', 'Jaune', 'Milieu_gauche', 150, 'TR001');
INSERT INTO Renne (Puce, Nom, couleur_nez, position_re, capacite, ID_traineau) VALUES('R0005', 'Cupidon', 'Rose', 'Arriere_gauche', 165, 'TR001');
INSERT INTO Renne (Puce, Nom, couleur_nez, position_re, capacite, ID_traineau) VALUES('R0006', 'Comet', 'Blanc', 'Arriere_droit', 155, 'TR001');
INSERT INTO Renne (Puce, Nom, couleur_nez, position_re, capacite, ID_traineau) VALUES('R0007', 'Dasher', 'Marron', 'Avant_droit', 157, 'TR001');
INSERT INTO Renne (Puce, Nom, couleur_nez, position_re, capacite, ID_traineau) VALUES('R0008', 'Rudolph', 'Noir', 'Dernier', 170, 'TR001');

INSERT INTO Renne (Puce, Nom, couleur_nez, position_re, capacite, ID_traineau) VALUES('R0009', '', 'Rouge', 'Avant', 200, 'TR002');
INSERT INTO Renne (Puce, Nom, couleur_nez, position_re, capacite, ID_traineau) VALUES('R0010', 'Dancer', 'Bleu', 'Avant_gauche', 155, 'TR002');
INSERT INTO Renne (Puce, Nom, couleur_nez, position_re, capacite, ID_traineau) VALUES('R0011', 'Prancer', 'Vert', 'Milieu_droit', 158, 'TR002');
INSERT INTO Renne (Puce, Nom, couleur_nez, position_re, capacite, ID_traineau) VALUES('R0012', 'Vixen', 'Jaune', 'Milieu_gauche', 150, 'TR002');
INSERT INTO Renne (Puce, Nom, couleur_nez, position_re, capacite, ID_traineau) VALUES('R0013', 'Cupidon', 'Rose', 'Arriere_gauche', 165, 'TR002');
INSERT INTO Renne (Puce, Nom, couleur_nez, position_re, capacite, ID_traineau) VALUES('R0014', 'Comet', 'Blanc', 'Arriere_droit', 155, 'TR002');
INSERT INTO Renne (Puce, Nom, couleur_nez, position_re, capacite, ID_traineau) VALUES('R0015', 'Dasher', 'Marron', 'Avant_droit', 157, 'TR002');
INSERT INTO Renne (Puce, Nom, couleur_nez, position_re, capacite, ID_traineau) VALUES('R0016', 'Rudolph', 'Noir', 'Dernier', 170, 'TR002');


INSERT INTO Tournee (Id_tournee, Num_tournee, date_tr, destination, ID_traineau) VALUES('T0001', 'T2025-001', to_date('2025-12-22','YYYY-MM-DD'), 'New York', 'TR001');
INSERT INTO Tournee (Id_tournee, Num_tournee, date_tr, destination, ID_traineau) VALUES('T0002', 'T2025-002', to_date('2025-12-23','YYYY-MM-DD'), 'Paris', 'TR002');
INSERT INTO Tournee (Id_tournee, Num_tournee, date_tr, destination, ID_traineau) VALUES('T0003', 'T2025-003', to_date('2025-12-21','YYYY-MM-DD'), 'Tokyo', 'TR003');

--la table enfant --
INSERT INTO Enfants (ID_enfants, Nom, Prenom, Adresse, Id_tournee) VALUES('EN001', 'Dupont', 'Paul', '10 Rue des Cadeaux', 'T0001');
INSERT INTO Enfants (ID_enfants, Nom, Prenom, Adresse, Id_tournee) VALUES('EN002', 'Martin', 'Lucas', '5 Avenue du Pôle', 'T0002');
INSERT INTO Enfants (ID_enfants, Nom, Prenom, Adresse, Id_tournee) VALUES('EN003', 'Lemoine', 'Sophie', '8 Rue des Rêves', 'T0003');
INSERT INTO Enfants (ID_enfants, Nom, Prenom, Adresse, Id_tournee) VALUES('EN004', 'Dupont', 'Philipe', '13 Rue des Cadeaux', 'T0001');
INSERT INTO Enfants (ID_enfants, Nom, Prenom, Adresse, Id_tournee) VALUES('EN005', 'Manuelle', 'Lucas', '15 Avenue du Pôle', 'T0002');
INSERT INTO Enfants (ID_enfants, Nom, Prenom, Adresse, Id_tournee) VALUES('EN006', 'Martin', 'Martin', '20 Rue des Rêves', 'T0003');

--la table jouet--
INSERT INTO Jouet (ID_jouet, Type_j) VALUES('J0001', 'Sport');
INSERT INTO Jouet (ID_jouet, Type_j) VALUES('J0002', 'Voiture');
INSERT INTO Jouet (ID_jouet, Type_j) VALUES('J0003', 'Poupee');
INSERT INTO Jouet (ID_jouet, Type_j) VALUES('J0004', 'Sport');
INSERT INTO Jouet (ID_jouet, Type_j) VALUES('J0005', 'Voiture');
INSERT INTO Jouet (ID_jouet, Type_j) VALUES('J0006', 'Poupee');
INSERT INTO Jouet (ID_jouet, Type_j) VALUES('J0007', 'Sport');
INSERT INTO Jouet (ID_jouet, Type_j) VALUES('J0008', 'Voiture');
INSERT INTO Jouet (ID_jouet, Type_j) VALUES('J0009', 'Poupee');
INSERT INTO Jouet (ID_jouet, Type_j) VALUES('J0010', 'Sport');
INSERT INTO Jouet (ID_jouet, Type_j) VALUES('J0011', 'Voiture');
INSERT INTO Jouet (ID_jouet, Type_j) VALUES('J0012', 'Poupee');

--la table possede--
INSERT INTO Possede (ID_elfe, ID_specialite, type_s) VALUES('EL001', 'SP001', 'Principale');
INSERT INTO Possede (ID_elfe, ID_specialite, type_s) VALUES('EL002', 'SP002', 'Secondaire');
INSERT INTO Possede (ID_elfe, ID_specialite, type_s) VALUES('EL003', 'SP003', 'Principale');
INSERT INTO Possede (ID_elfe, ID_specialite, type_s) VALUES('EL003', 'SP004', 'Secondaire');

--la table remplace --
INSERT INTO Remplace (ID_elfe_remplace_, ID_elfe_est_remplace_) VALUES('EL002', 'EL001');
INSERT INTO Remplace (ID_elfe_remplace_, ID_elfe_est_remplace_) VALUES('EL003', 'EL002');

--la table commande --
INSERT INTO Commande (ID_enfants, ID_jouet) VALUES('EN001', 'J0008');
INSERT INTO Commande (ID_enfants, ID_jouet) VALUES('EN002', 'J0002');
INSERT INTO Commande (ID_enfants, ID_jouet) VALUES('EN003', 'J0003');
INSERT INTO Commande (ID_enfants, ID_jouet) VALUES('EN004', 'J0008');
INSERT INTO Commande (ID_enfants, ID_jouet) VALUES('EN005', 'J0005');
INSERT INTO Commande (ID_enfants, ID_jouet) VALUES('EN006', 'J0005');
INSERT INTO Commande (ID_enfants, ID_jouet) VALUES('EN005', 'J0008');
INSERT INTO Commande (ID_enfants, ID_jouet) VALUES('EN006', 'J0002');
INSERT INTO Commande (ID_enfants, ID_jouet) VALUES('EN005', 'J0012');

--la table substitut --
INSERT INTO Substitut (ID_jouet_remplace_, ID_jouet_est_remplace) VALUES('J0002', 'J0001');
INSERT INTO Substitut (ID_jouet_remplace_, ID_jouet_est_remplace) VALUES('J0003', 'J0002');

--la table Constituer_par--
INSERT INTO Constituer_par (ID_mp, ID_jouet) VALUES('MP001', 'J0001');
INSERT INTO Constituer_par (ID_mp, ID_jouet) VALUES('MP002', 'J0002');
INSERT INTO Constituer_par (ID_mp, ID_jouet) VALUES('MP003', 'J0003');
INSERT INTO Constituer_par (ID_mp, ID_jouet) VALUES('MP002', 'J0001');
INSERT INTO Constituer_par (ID_mp, ID_jouet) VALUES('MP003', 'J0001');
INSERT INTO Constituer_par (ID_mp, ID_jouet) VALUES('MP004', 'J0001');
INSERT INTO Constituer_par (ID_mp, ID_jouet) VALUES('MP005', 'J0001');

--la table  participe --
INSERT INTO Participe (Id_tournee, Id_intermittent) VALUES('T0001', 'IT001');
INSERT INTO Participe (Id_tournee, Id_intermittent) VALUES('T0002', 'IT002');
INSERT INTO Participe (Id_tournee, Id_intermittent) VALUES('T0003', 'IT003');

--la table produit--
INSERT INTO Produit (ID_jouet, ID_st, date_pass) VALUES('J0011', 'ST001', to_date('2025-10-01','YYYY-MM-DD'));
INSERT INTO Produit (ID_jouet, ID_st, date_pass) VALUES('J0012', 'ST002', to_date('2025-10-02','YYYY-MM-DD'));
INSERT INTO Produit (ID_jouet, ID_st, date_pass) VALUES('J0010', 'ST003', to_date('2025-10-03','YYYY-MM-DD'));

--la table fabrique--
INSERT INTO Fabrique (ID_atelier, ID_jouet, date_passage) VALUES('AT001', 'J0001', to_date('2025-11-15','YYYY-MM-DD'));
INSERT INTO Fabrique (ID_atelier, ID_jouet, date_passage) VALUES('AT002', 'J0002', to_date('2025-11-16','YYYY-MM-DD'));
INSERT INTO Fabrique (ID_atelier, ID_jouet, date_passage) VALUES('AT003', 'J0003', to_date('2025-11-17','YYYY-MM-DD'));
INSERT INTO Fabrique (ID_atelier, ID_jouet, date_passage) VALUES('AT003', 'J0004', to_date('2025-11-17','YYYY-MM-DD'));
--la table passe_par--
INSERT INTO Passe_par (Id_entrepot, Id_tournee) VALUES('ET001', 'T0001');
INSERT INTO Passe_par (Id_entrepot, Id_tournee) VALUES('ET002', 'T0002');
INSERT INTO Passe_par (Id_entrepot, Id_tournee) VALUES('ET003', 'T0003');

--la table commander--
INSERT INTO commander (ID_atelier, ID_mp, date_a) VALUES('AT001', 'MP001', to_date('2025-09-20','YYYY-MM-DD'));
INSERT INTO commander (ID_atelier, ID_mp, date_a) VALUES('AT002', 'MP002', to_date('2025-09-21','YYYY-MM-DD'));
INSERT INTO commander (ID_atelier, ID_mp, date_a) VALUES('AT003', 'MP003', to_date('2025-09-22','YYYY-MM-DD'));

-- la table liste_jouet --
INSERT INTO liste_jouet (ID_liste, poids, ID_traineau, ID_enfants) VALUES('LS001', 4.3 , 'TR001', 'EN001' );
INSERT INTO liste_jouet (ID_liste, poids, ID_traineau, ID_enfants) VALUES('LS002', 3.7 , 'TR001', 'EN002' );
INSERT INTO liste_jouet (ID_liste, poids, ID_traineau, ID_enfants) VALUES('LS003', 3.9 , 'TR002', 'EN003' );
-- la table Contient --
INSERT INTO Contient (ID_jouet, ID_liste) VALUES('J0001', 'LS001');
INSERT INTO Contient (ID_jouet, ID_liste) VALUES('J0002', 'LS002');
INSERT INTO Contient (ID_jouet, ID_liste) VALUES('J0003', 'LS003');


