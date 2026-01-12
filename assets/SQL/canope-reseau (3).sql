-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : mer. 07 jan. 2026 à 10:34
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
(1, 'ECOLES'),
(2, 'COLLEGES'),
(3, 'LYCEES'),
(4, 'UNIVERSITE'),
(5, 'TOUT PUBLIC'),
(6, 'PETITE ENFANCE');

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
  `list_price` decimal(10,2) DEFAULT NULL,
  `is_published` tinyint(1) DEFAULT '1',
  `is_active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `product`
--

INSERT INTO `product` (`id`, `category_id`, `name`, `reference`, `description`, `list_price`, `is_published`, `is_active`) VALUES
(1, 1, 'Marcu et Fiffina Aio in Scola', 'MF-SCOLA', 'Album jeunesse en langue corse - À l\'école', '0.00', 1, 1),
(2, 1, 'Marcu et Fiffina in Furesta', 'MF-FURESTA', 'Album jeunesse en langue corse - Dans la forêt', '0.00', 1, 1),
(3, 1, 'Marcu et Fiffina Tanti Culori', 'MF-CULORI', 'Album jeunesse en langue corse - Tant de couleurs', '0.00', 1, 1),
(4, 1, 'Marcu et Fiffina U Mari', 'MF-MARI', 'Album jeunesse en langue corse - La mer', '0.00', 1, 1),
(5, 1, 'Marcu et Fiffina Addiu a Pannedda', 'MF-PANNEDDA', 'Album jeunesse en langue corse - Adieu à Pannedda', '0.00', 1, 1),
(6, 1, 'Marcu et Fiffina Hè Natale', 'MF-NATALE', 'Album jeunesse en langue corse - C\'est Noël', '0.00', 1, 1),
(7, 1, 'Marcu et Fiffina I Frutti', 'MF-FRUTTI', 'Album jeunesse en langue corse - Les fruits', '0.00', 1, 1),
(8, 1, 'Marcu et Fiffina Sant\'Andria', 'MF-ANDRIA', 'Album jeunesse en langue corse - Saint André', '0.00', 1, 1),
(9, 4, 'Colomba', 'UNI-COLOMBA', 'Édition bilingue français-corse de Colomba', '0.00', 1, 1),
(10, 4, 'Filidatu e Filimonda', 'UNI-FILIDATU', 'Ouvrage littéraire en langue corse', '0.00', 1, 1),
(11, 4, 'Galeotta', 'UNI-GALEOTTA', 'Roman en langue corse', '0.00', 1, 1),
(12, 4, 'U Pane Azimu', 'UNI-PANE', 'Ouvrage littéraire en langue corse', '0.00', 1, 1),
(13, 4, 'U Cunghjugarellu', 'UNI-CONJUG', 'La conjugaison corse - Guide pratique', '0.00', 1, 1);

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
  `request_type` varchar(30) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'EN_COURS',
  `comment` text,
  `responsible_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `request`
--

INSERT INTO `request` (`id`, `request_number`, `product_id`, `last_name`, `first_name`, `email`, `phone`, `establishment_name`, `establishment_address`, `establishment_postal`, `establishment_city`, `request_date`, `request_type`, `status`, `comment`, `responsible_id`) VALUES
(1, 'DEM-2026-0001', 1, 'Dupont', 'Marie', 'marie.dupont@example.fr', '0601020304', 'Lycée Jean Moulin', '10 rue des Écoles', '75015', 'Paris', '2026-01-06', 'RECEVOIR', 'EN_COURS', 'Dotation pour projet pédagogique', 1),
(2, 'DEM-2026-0002', 2, 'Martin', 'Paul', 'paul.martin@example.fr', '0605060708', 'Collège Victor Hugo', '5 avenue de la République', '93200', 'Saint-Denis', '2026-01-06', 'REASSORT', 'TRAITEE', 'Réassort après changement d’effectif', 2);

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
(3, 2, 2, 8, 'Projet interdisciplinaire');

-- --------------------------------------------------------

--
-- Structure de la table `responsible`
--

CREATE TABLE `responsible` (
  `id` int(11) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `job_title` varchar(150) DEFAULT NULL,
  `email_pro` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `responsible`
--

INSERT INTO `responsible` (`id`, `last_name`, `first_name`, `job_title`, `email_pro`, `password`) VALUES
(1, 'Durand', 'Claire', 'Chargée de dotations', 'claire.durand@exemple.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(2, 'Leroy', 'Thomas', 'Responsable logistique', 'thomas.leroy@exemple.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(3, 'Admin', 'Canopé', 'Administrateur', 'admin@canope.fr', '$2y$10$1G/4YWSzOg0zYfYiNAR8oezt96hs.Lozvdu5RQke0zN6lGw9v.Uay');

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
(7, 7, 20),
(8, 8, 50),
(9, 9, 100),
(10, 10, 100),
(11, 11, 25),
(12, 12, 100),
(13, 13, 100);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `etablissement` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `is_active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `etablissement`, `created_at`, `is_active`) VALUES
(1, 'soracekevin09@gmail.com', '$2y$10$PIyjEb4C3jyrJOq4TLM.W.wcCopg42h6c6QLmhJYRDJJvYrFTBYCG', 'lycée laetitia', '2026-01-07 09:48:23', 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_product_category` (`category_id`);

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
  ADD UNIQUE KEY `request_number` (`request_number`),
  ADD KEY `fk_request_product` (`product_id`),
  ADD KEY `fk_request_responsible` (`responsible_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `product_image`
--
ALTER TABLE `product_image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `request`
--
ALTER TABLE `request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `request_line`
--
ALTER TABLE `request_line`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `responsible`
--
ALTER TABLE `responsible`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `stock`
--
ALTER TABLE `stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `fk_product_category` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE SET NULL;

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
  ADD CONSTRAINT `fk_request_responsible` FOREIGN KEY (`responsible_id`) REFERENCES `responsible` (`id`) ON DELETE SET NULL;

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
