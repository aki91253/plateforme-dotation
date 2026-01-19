-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : lun. 19 jan. 2026 à 08:56
-- Version du serveur : 5.7.24
-- Version de PHP : 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `canope-reseau`
--

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(1, 'ECOLES - Cycle 1'),
(2, 'ECOLES - Cycle 2\r\n'),
(3, 'ECOLES - Cycle 3'),
(4, 'COLLEGES'),
(5, 'LYCEES'),
(6, 'UNIVERSITE'),
(7, 'TOUT PUBLIC'),
(8, 'PETITE ENFANCE');

-- --------------------------------------------------------

--
-- Structure de la table `discipline`
--

CREATE TABLE `discipline` (
  `id` int(11) NOT NULL,
  `libelle` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `discipline`
--

INSERT INTO `discipline` (`id`, `libelle`) VALUES
(1, 'littérature / poésie'),
(2, 'histoire / géographie'),
(3, 'santé'),
(4, 'science'),
(5, 'mathématique'),
(6, 'éducation civique'),
(7, 'langue et culture corse'),
(8, 'musique'),
(9, 'méthode d\'apprentissage'),
(10, 'outils d\'apprentissage'),
(11, 'interdisciplinaire');

-- --------------------------------------------------------

--
-- Structure de la table `historique_etat`
--

CREATE TABLE `historique_etat` (
  `id` int(10) UNSIGNED NOT NULL,
  `request_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `changed_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `commentaire` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `historique_etat`
--

INSERT INTO `historique_etat` (`id`, `request_id`, `status_id`, `changed_at`, `commentaire`) VALUES
(1, 4, 2, '2026-01-12 15:45:38', NULL),
(2, 4, 3, '2026-01-12 16:34:40', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `langue_product`
--

CREATE TABLE `langue_product` (
  `id` int(11) NOT NULL,
  `langue` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `langue_product`
--

INSERT INTO `langue_product` (`id`, `langue`) VALUES
(3, 'anglais'),
(4, 'bilingue'),
(2, 'corse'),
(1, 'français');

-- --------------------------------------------------------

--
-- Structure de la table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `reference` varchar(100) DEFAULT NULL,
  `description` text,
  `collection` varchar(50) NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `langue_id` int(11) DEFAULT NULL,
  `id_ressource` int(11) NOT NULL,
  `discipline_id` int(11) DEFAULT NULL,
  `stock` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `product`
--

INSERT INTO `product` (`id`, `category_id`, `name`, `reference`, `description`, `collection`, `is_active`, `langue_id`, `id_ressource`, `discipline_id`, `stock`) VALUES
(1, 1, 'Marcu et Fiffina Aio in Scola', 'MF-SCOLA', 'Album jeunesse en langue corse - À l\'école', 'Marcu è Fiffina', 1, 2, 1, 1, 0),
(2, 1, 'Marcu et Fiffina in Furesta', 'MF-FURESTA', 'Album jeunesse en langue corse - Dans la forêt', 'Marcu è Fiffina', 1, 2, 1, 1, 0),
(3, 1, 'Marcu et Fiffina Tanti Culori', 'MF-CULORI', 'Album jeunesse en langue corse - Tant de couleurs', 'Marcu è Fiffina', 1, 2, 1, 1, 0),
(4, 1, 'Marcu et Fiffina U Mari', 'MF-MARI', 'Album jeunesse en langue corse - La mer', 'Marcu è Fiffina', 1, 2, 1, 1, 0),
(5, 1, 'Marcu et Fiffina Addiu a Pannedda', 'MF-PANNEDDA', 'Album jeunesse en langue corse - Adieu à Pannedda', 'Marcu è Fiffina', 1, 2, 1, 1, 0),
(6, 1, 'Marcu et Fiffina Hè Natale', 'MF-NATALE', 'Album jeunesse en langue corse - C\'est Noël', 'Marcu è Fiffina', 1, 2, 1, 1, 0),
(7, 1, 'Marcu et Fiffina I Frutti', 'MF-FRUTTI', 'Album jeunesse en langue corse - Les fruits', 'Marcu è Fiffina', 1, 2, 1, 1, 0),
(8, 1, 'Marcu et Fiffina Sant\'Andria', 'MF-ANDRIA', 'Album jeunesse en langue corse - Saint André', 'Marcu è Fiffina', 1, 2, 1, 1, 0),
(9, 1, 'Colomba', 'UNI-COLOMBA', 'Édition bilingue français-corse de Colomba', '', 1, 4, 1, 1, 0),
(10, 1, 'Filidatu e Filimonda', 'UNI-FILIDATU', 'Ouvrage littéraire en langue corse', '', 1, 2, 1, 1, 0),
(11, 1, 'Galeotta', 'UNI-GALEOTTA', 'Roman en langue corse - Quantité limitée', '', 1, 2, 1, 1, 0),
(12, 1, 'U Pane Azimu', 'UNI-PANE', 'Ouvrage littéraire en langue corse', '', 1, 2, 1, 1, 0),
(13, 1, 'U Cunghjugarellu', 'UNI-CONJUG', 'La conjugaison corse - Guide pratique', '', 1, 2, 1, 1, 0),
(14, 7, '50 DOCS GEOGRAPHIE DE LA CORSE EN CORSE', 'HG-CORSE-50', 'Documents pédagogiques de géographie de la Corse en langue corse', '', 1, 2, 1, 2, 10),
(15, 7, '50 DOCS GEOGRAPHIE DE LA CORSE EN FRANCAIS', 'HG-FR-50', 'Documents pédagogiques de géographie de la Corse en français', '', 1, 1, 1, 2, 10),
(16, 7, '50 DOCS HISTOIRE DE LA CORSE EN FRANCAIS', 'HIST-FR-50', 'Documents pédagogiques sur l’histoire de la Corse', '', 1, 1, 1, 2, 10),
(17, 7, '50 DOCS SVT DE LA CORSE EN FRANCAIS', 'SVT-CORSE-50', 'Documents pédagogiques de sciences de la vie et de la terre', '', 1, 1, 1, 4, 10),
(18, 2, 'A CONTI FATTI FUGLIALE D\'ASIRCIZII CE1', 'MATH-A-CONTI-CE1', 'Fichier d’exercices de mathématiques en langue corse', '', 1, 2, 1, 5, 10),
(19, 2, 'A CONTI FATTI FUGLIALE D\'ASIRCIZII CP', 'MATH-A-CONTI-CP', 'Fichier d’exercices de mathématiques en langue corse', '', 1, 2, 1, 5, 10),
(20, 2, 'A CONTI FATTI MANUALE CE2', 'MATH-A-CONTI-CE2', 'Manuel de mathématiques en langue corse', '', 1, 2, 1, 5, 10),
(21, 2, 'A CONTI FATTI MANUALE CM1', 'MATH-A-CONTI-CM1', 'Manuel de mathématiques en langue corse', '', 1, 2, 1, 5, 10),
(22, 2, 'A CONTI FATTI MANUALE CM2', 'MATH-A-CONTI-CM2', 'Manuel de mathématiques en langue corse', '', 1, 2, 1, 5, 10),
(23, 8, 'A FOLA DI TOPA PINNUTELLA', 'LANG-FOLA', 'Album jeunesse en langue corse', '', 1, 2, 1, 7, 10),
(24, 8, 'A MAGIA DI NATALE', 'LANG-NATALE', 'Album jeunesse en langue corse sur le thème de Noël', '', 1, 2, 1, 7, 10),
(25, 7, 'A PICCULA MELA', 'LANG-MELA', 'Conte en langue corse', '', 1, 2, 1, 7, 10),
(26, 2, 'FILIDATU E FILIMONDA', 'LANG-FILIDATU', 'Œuvre littéraire en langue corse', '', 1, 2, 1, 7, 10),
(27, 2, 'CUSI BABBU M\'HA DETTU', 'LANG-BABBU', 'Conte traditionnel en langue corse', '', 1, 2, 1, 7, 10),
(28, 2, 'DI ÈVEDE IN PUEMI', 'LANG-PUEMI', 'Recueil de poèmes en langue corse', '', 1, 2, 1, 7, 10),
(29, 7, 'MISSIONI ALTA ROCCA', 'INTER-MISSIONI', 'Ressource pédagogique interdisciplinaire', '', 1, 2, 1, 11, 10),
(30, 7, 'RACONTI E FOLE DI L\'ISULA PERSA', 'INTER-RACONTI', 'Contes et récits traditionnels corses', '', 1, 2, 1, 11, 10);

-- --------------------------------------------------------

--
-- Structure de la table `product_image`
--

CREATE TABLE `product_image` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `url` varchar(500) NOT NULL,
  `alt_text` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `product_image`
--

INSERT INTO `product_image` (`id`, `product_id`, `url`, `alt_text`) VALUES
(1, 1, 'assets/img/marcu-et-fiffina-aio-in-scuola.png', 'Marcu et Fiffina Aio in Scola'),
(2, 2, 'assets/img/marcu-et-fiffina-in-furesta.png', 'Marcu et Fiffina in Furesta'),
(3, 3, 'assets/img/marcu-et-fiffina-tanti-colori.png', 'Marcu et Fiffina Tanti Culori'),
(4, 4, 'assets/img/marcu-et-fiffina-a-u-mari.png', 'Marcu et Fiffina U Mari'),
(5, 5, 'assets/img/marcu-et-fiffina-addiu-a-pannedda.png', 'Marcu et Fiffina Addiu a Pannedda'),
(6, 6, 'assets/img/marcu-et-fiffina-he-natale.png', 'Marcu et Fiffina Hè Natale'),
(7, 7, 'assets/img/marcu-et-fiffina-i-frutti.png', 'Marcu et Fiffina I Frutti'),
(8, 8, 'assets/img/marcu-et-fiffina-santandria.png', 'Marcu et Fiffina Sant\'Andria'),
(9, 9, 'assets/img/colomba.png', 'Colomba'),
(10, 10, 'assets/img/FILIDATU E FILIMONDA.png', 'Filidatu e Filimonda'),
(11, 11, 'assets/img/a-galeotta.png', 'Galeotta'),
(12, 12, 'assets/img/u-pane-azimu.png', 'U Pane Azimu'),
(13, 13, 'assets/img/u cunghjugarellu.png', 'U Cunghjugarellu');

-- --------------------------------------------------------

--
-- Structure de la table `request`
--

CREATE TABLE `request` (
  `id` int(11) NOT NULL,
  `token` varchar(50) NOT NULL,
  `product_id` int(11) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `establishment_name` varchar(255) NOT NULL,
  `establishment_address` varchar(255) DEFAULT NULL,
  `establishment_postal` varchar(10) DEFAULT NULL,
  `establishment_city` varchar(100) DEFAULT NULL,
  `request_date` date NOT NULL,
  `comment` text,
  `responsible_id` int(11) DEFAULT NULL,
  `status_id` int(11) NOT NULL,
  `id_responsable` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `request`
--

INSERT INTO `request` (`id`, `token`, `product_id`, `last_name`, `first_name`, `email`, `phone`, `establishment_name`, `establishment_address`, `establishment_postal`, `establishment_city`, `request_date`, `comment`, `responsible_id`, `status_id`, `id_responsable`) VALUES
(1, 'DEM-2026-0001', 1, 'Dupont', 'Marie', 'marie.dupont@example.fr', '0601020304', 'Lycée Jean Moulin', '10 rue des Écoles', '75015', 'Paris', '2026-01-06', 'Dotation pour projet pédagogique', 1, 1, 1),
(2, 'DEM-2026-0002', 2, 'Martin', 'Paul', 'paul.martin@example.fr', '0605060708', 'Collège Victor Hugo', '5 avenue de la République', '93200', 'Saint-Denis', '2026-01-06', 'Réassort après changement d’effectif', 2, 1, 1),
(3, 'azertyuiop', 6, 'Jean-Mathieu ', 'Lerda', 'jm@gmail.com', '06079779923', 'Lycée Leatitia Bonaparte ', 'Cours napoléon', '20090', 'Ajaccio', '2026-01-09', 'URGENT ! ', 1, 1, 1),
(4, 'b74073c4038906b37a3c30ef742f65eb', 9, 'azert', 'azerty', 'redface@gmail.com', '0645455334', 'Leatitia bonaparte', '', '', '', '2026-01-12', '', NULL, 3, 1),
(5, '75a8f2bb469e39663862bb1d7852c329', 11, 'Test2', 'test', 'test@test.fr', '0755743534', 'ecole de test', '', '', '', '2026-01-12', '', NULL, 1, 1),
(6, 'e196d1b480d51b5a8147e1306bc05ba7', 11, 'qsdqsdqsd', 'qsdqsdqsd', 'claire.durand@exemple.fr', '03124578', 'qsdqsdqsd', '', '', '', '2026-01-16', 'qsdqsdqsd', NULL, 1, 1);

--
-- Déclencheurs `request`
--
DELIMITER $$
CREATE TRIGGER `trg_request_after_update` AFTER UPDATE ON `request` FOR EACH ROW BEGIN
    IF OLD.status_id <> NEW.status_id THEN
        INSERT INTO historique_etat (request_id, status_id)
        VALUES (NEW.id, NEW.status_id);
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `request_line`
--

CREATE TABLE `request_line` (
  `id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `comment` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `request_line`
--

INSERT INTO `request_line` (`id`, `request_id`, `product_id`, `quantity`, `comment`) VALUES
(1, 1, 1, 10, 'Pour une classe de première'),
(2, 1, 3, 5, 'Pour atelier'),
(3, 2, 2, 8, 'Projet interdisciplinaire'),
(4, 4, 9, 1, 'Colomba'),
(5, 4, 10, 1, 'Filidatu e Filimonda'),
(6, 4, 11, 1, 'Galeotta'),
(7, 5, 11, 1, 'Galeotta'),
(8, 5, 10, 1, 'Filidatu e Filimonda'),
(9, 5, 9, 1, 'Colomba'),
(10, 6, 11, 1, 'Galeotta'),
(11, 6, 10, 1, 'Filidatu e Filimonda'),
(12, 6, 9, 1, 'Colomba');

-- --------------------------------------------------------

--
-- Structure de la table `responsible`
--

CREATE TABLE `responsible` (
  `id` int(11) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `job_title` varchar(150) DEFAULT NULL,
  `email_pro` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `responsible`
--

INSERT INTO `responsible` (`id`, `last_name`, `first_name`, `job_title`, `email_pro`) VALUES
(1, 'Durand', 'Claire', 'Chargée de dotations', 'claire.durand@exemple.fr'),
(2, 'Leroy', 'Thomas', 'Responsable logistique', 'thomas.leroy@exemple.fr');

-- --------------------------------------------------------

--
-- Structure de la table `stock`
--

CREATE TABLE `stock` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `stock`
--

INSERT INTO `stock` (`id`, `product_id`, `quantity`) VALUES
(1, 1, 100),
(2, 2, 100),
(3, 3, 50),
(4, 4, 100),
(5, 5, 50),
(6, 6, 50),
(7, 7, 0),
(8, 8, 50),
(9, 9, 0),
(10, 10, 100),
(11, 11, 25),
(12, 12, 100),
(13, 13, 100);

-- --------------------------------------------------------

--
-- Structure de la table `type_ressource`
--

CREATE TABLE `type_ressource` (
  `id` int(11) NOT NULL,
  `libelle` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `type_ressource`
--

INSERT INTO `type_ressource` (`id`, `libelle`) VALUES
(3, 'audio'),
(4, 'kit pédagogique'),
(1, 'ouvrage'),
(5, 'site'),
(2, 'vidéo');

-- --------------------------------------------------------

--
-- Structure de la table `type_status`
--

CREATE TABLE `type_status` (
  `id` int(11) NOT NULL,
  `libelle` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `type_status`
--

INSERT INTO `type_status` (`id`, `libelle`) VALUES
(1, 'En attente'),
(2, 'Vérifiée'),
(3, 'Approuvée '),
(4, 'Envoyée'),
(5, 'Livrée'),
(6, 'Refusée ');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fonction` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `is_active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `fonction`, `created_at`, `is_active`) VALUES
(1, 'redface@gmail.com', 'redface', 'redworld', '2026-01-07 15:15:25', 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `discipline`
--
ALTER TABLE `discipline`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `historique_etat`
--
ALTER TABLE `historique_etat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_historique_request` (`request_id`),
  ADD KEY `fk_historique_status` (`status_id`);

--
-- Index pour la table `langue_product`
--
ALTER TABLE `langue_product`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `langue` (`langue`);

--
-- Index pour la table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_product_category` (`category_id`),
  ADD KEY `fk_product_langue` (`langue_id`),
  ADD KEY `fk_product_type_ressource` (`id_ressource`),
  ADD KEY `fk_product_discipline` (`discipline_id`);

--
-- Index pour la table `product_image`
--
ALTER TABLE `product_image`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_product_image_product` (`product_id`);

--
-- Index pour la table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `request_number` (`token`),
  ADD KEY `fk_request_product` (`product_id`),
  ADD KEY `fk_request_responsible` (`responsible_id`),
  ADD KEY `fk_request_status` (`status_id`),
  ADD KEY `fk_responsable_demande` (`id_responsable`);

--
-- Index pour la table `request_line`
--
ALTER TABLE `request_line`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_request_line_request` (`request_id`),
  ADD KEY `fk_request_line_product` (`product_id`);

--
-- Index pour la table `responsible`
--
ALTER TABLE `responsible`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_stock_product` (`product_id`);

--
-- Index pour la table `type_ressource`
--
ALTER TABLE `type_ressource`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `libelle` (`libelle`);

--
-- Index pour la table `type_status`
--
ALTER TABLE `type_status`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `discipline`
--
ALTER TABLE `discipline`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `historique_etat`
--
ALTER TABLE `historique_etat`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `langue_product`
--
ALTER TABLE `langue_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT pour la table `product_image`
--
ALTER TABLE `product_image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `request`
--
ALTER TABLE `request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `request_line`
--
ALTER TABLE `request_line`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `responsible`
--
ALTER TABLE `responsible`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `stock`
--
ALTER TABLE `stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `type_ressource`
--
ALTER TABLE `type_ressource`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `type_status`
--
ALTER TABLE `type_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `historique_etat`
--
ALTER TABLE `historique_etat`
  ADD CONSTRAINT `fk_historique_request` FOREIGN KEY (`request_id`) REFERENCES `request` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_historique_status` FOREIGN KEY (`status_id`) REFERENCES `type_status` (`id`);

--
-- Contraintes pour la table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `fk_product_category` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_product_discipline` FOREIGN KEY (`discipline_id`) REFERENCES `discipline` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_product_langue` FOREIGN KEY (`langue_id`) REFERENCES `langue_product` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_product_type_ressource` FOREIGN KEY (`id_ressource`) REFERENCES `type_ressource` (`id`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `product_image`
--
ALTER TABLE `product_image`
  ADD CONSTRAINT `fk_product_image_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `request`
--
ALTER TABLE `request`
  ADD CONSTRAINT `fk_request_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `fk_request_responsible` FOREIGN KEY (`responsible_id`) REFERENCES `responsible` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_request_status` FOREIGN KEY (`status_id`) REFERENCES `type_status` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_responsable_demande` FOREIGN KEY (`id_responsable`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `request_line`
--
ALTER TABLE `request_line`
  ADD CONSTRAINT `fk_request_line_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `fk_request_line_request` FOREIGN KEY (`request_id`) REFERENCES `request` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `fk_stock_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
