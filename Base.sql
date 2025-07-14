create database Base;
use Base;
create table Cat_Membres(
    int  id_Membre,
    varchar(150) Nom,
    date date_de_naissance,
    varchar(50) genre,
    varchar(100) email,
    varchar(50) mdp,
    varchar(50)image_profil
)
create table Cat_categorie_objet(
    int id_categorie,
    varchar(150) nom_categorie

)
create table Cat_objet(
    int id_objet,
    varchar(150) nom_objet,
    int id_categorie,
    int id_membre
)
create table Cat_images_objet(
    int id_image,
    int id_objet,
    varchar(150) nom_image

)
create table Cat_emprunt(
    int id_emprunt,
    int id_objet,
    int id_membre,
    date date_emprunt,
    date date_retour
)
INSERT INTO Cat_categorie_objet (nom_categorie) VALUES
('Esthétique'),
('Bricolage'),
('Mécanique'),
('Cuisine');
INSERT INTO Cat_Membres (nom, date_naissance, genre, email, ville, mdp, image_profil) VALUES
('Alice', '2000-01-15', 'F', 'alice@example.com', 'Antananarivo', '123456', 'alice.jpg'),
('Bob', '1999-06-20', 'H', 'bob@example.com', 'Fianarantsoa', '123456', 'bob.jpg'),
('Clara', '2002-03-10', 'F', 'clara@example.com', 'Toamasina', '123456', 'clara.jpg'),
('David', '1998-12-05', 'H', 'david@example.com', 'Tuléar', '123456', 'david.jpg');
-- Objets d'Alice (id_membre = 1)
INSERT INTO Cat_objet (nom_objet, id_categorie, id_membre) VALUES
('Sèche-cheveux', 1, 1),
('Peigne électrique', 1, 1),
('Trousse de maquillage', 1, 1),
('Tournevis', 2, 1),
('Perceuse', 2, 1),
('Pistolet à colle', 2, 1),
('Clé à molette', 3, 1),
('Pompe à vélo', 3, 1),
('Mixeur', 4, 1),
('Fouet électrique', 4, 1);

-- Objets de Bob (id_membre = 2)
INSERT INTO Cat_objet (nom_objet, id_categorie, id_membre) VALUES
('Rasoir électrique', 1, 2),
('Lisseur', 1, 2),
('Lampe frontale', 2, 2),
('Scie sauteuse', 2, 2),
('Cric hydraulique', 3, 2),
('Manomètre', 3, 2),
('Casserole', 4, 2),
('Cocotte-minute', 4, 2),
('Presse-agrumes', 4, 2),
('Thermomètre de cuisson', 4, 2);

-- Objets de Clara (id_membre = 3)
INSERT INTO Cat_objet (nom_objet, id_categorie, id_membre) VALUES
('Epilateur', 1, 3),
('Brosse chauffante', 1, 3),
('Ciseaux', 2, 3),
('Marteau', 2, 3),
('Clé anglaise', 3, 3),
('Pompe à main', 3, 3),
('Grille-pain', 4, 3),
('Micro-ondes', 4, 3),
('Mixeur plongeant', 4, 3),
('Blender', 4, 3);

-- Objets de David (id_membre = 4)
INSERT INTO Cat_objet (nom_objet, id_categorie, id_membre) VALUES
('Crème visage', 1, 4),
('Kit manucure', 1, 4),
('Perceuse-visseuse', 2, 4),
('Pince multiprise', 2, 4),
('Pied-de-biche', 3, 4),
('Chalumeau', 3, 4),
('Robot pâtissier', 4, 4),
('Balance électronique', 4, 4),
('Poêle antiadhésive', 4, 4),
('Batteur électrique', 4, 4);
INSERT INTO Cat_emprunt (id_objet, id_membre, date_emprunt, date_retour) VALUES
(5, 2, '2024-07-01', '2024-07-03'),
(8, 3, '2024-07-02', NULL),
(11, 1, '2024-07-05', '2024-07-08'),
(17, 4, '2024-07-06', '2024-07-09'),
(22, 1, '2024-07-07', NULL),
(28, 2, '2024-07-09', '2024-07-10'),
(33, 4, '2024-07-10', NULL),
(36, 3, '2024-07-11', '2024-07-13'),
(38, 2, '2024-07-12', NULL),
(40, 1, '2024-07-13', NULL);
