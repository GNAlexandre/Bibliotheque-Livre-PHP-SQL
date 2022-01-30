-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : ven. 19 nov. 2021 à 09:04
-- Version du serveur : 5.7.33
-- Version de PHP : 7.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `base_de_donnee_projet_php`
--

-- --------------------------------------------------------

--
-- Structure de la table `attribuer`
--

CREATE TABLE `attribuer` (
  `id_livre_ext` int(11) NOT NULL,
  `id_genre_ext` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `attribuer`
--

INSERT INTO `attribuer` (`id_livre_ext`, `id_genre_ext`) VALUES
(6, 1),
(6, 3),
(6, 5),
(7, 1),
(7, 3),
(7, 5),
(8, 1),
(8, 3),
(8, 5),
(9, 1),
(9, 4),
(9, 6),
(10, 4),
(10, 10);

-- --------------------------------------------------------

--
-- Structure de la table `auteur`
--

CREATE TABLE `auteur` (
  `id_auteur` int(11) NOT NULL,
  `aut_nom` varchar(15) NOT NULL,
  `aut_prenom` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `auteur`
--

INSERT INTO `auteur` (`id_auteur`, `aut_nom`, `aut_prenom`) VALUES
(1, 'Nihei ', 'Tsutomu'),
(3, 'Yoko', 'Taro'),
(4, 'Kumo', 'Kagyu'),
(5, 'Bob', 'Lennon'),
(6, 'Fanta', 'Blabla'),
(8, 'Thp', 'Bla'),
(9, 'Elliot', 'Garnier'),
(10, 'Julian', 'Wickeur');

-- --------------------------------------------------------

--
-- Structure de la table `genre`
--

CREATE TABLE `genre` (
  `id_genre` int(11) NOT NULL,
  `nom_genre` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `genre`
--

INSERT INTO `genre` (`id_genre`, `nom_genre`) VALUES
(1, 'combat'),
(2, 'histoire'),
(3, 'aventure'),
(4, 'phylosophique'),
(5, 'fantasy'),
(6, 'science-fiction'),
(7, 'horreur'),
(8, 'romance'),
(9, 'surnaturel'),
(10, 'comedie');

-- --------------------------------------------------------

--
-- Structure de la table `livre`
--

CREATE TABLE `livre` (
  `id_livre` int(11) NOT NULL,
  `id_uti_ext` int(11) DEFAULT NULL,
  `id_auteur_ext` int(11) NOT NULL,
  `liv_titre` varchar(255) NOT NULL,
  `liv_libellé` varchar(255) DEFAULT NULL,
  `liv_annee_parution` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `livre`
--

INSERT INTO `livre` (`id_livre`, `id_uti_ext`, `id_auteur_ext`, `liv_titre`, `liv_libellé`, `liv_annee_parution`) VALUES
(2, NULL, 1, 'Knights Of Sidonia', NULL, 2009),
(3, NULL, 1, 'Blame', NULL, 2003),
(4, NULL, 1, 'Aposimz', NULL, 2018),
(5, NULL, 3, 'Nier Replicant', 'Préquelle Nier Automata', 2021),
(6, NULL, 4, 'Goblin Slayer Tome 1', 'Je ne suis pas un héros qui sauve le monde mais un simple chasseur de gobelin', 2018),
(7, NULL, 4, 'Goblin Slayer Last Year One Tome 1', 'Prequel  Goblin Slayer ', 2021),
(8, NULL, 4, 'Goblin Slayer Last Year One Tome 2', 'Prequel  Goblin Slayer ', 2021),
(9, NULL, 3, 'Nier Automata', 'Guerre Post Apocalyptique', 2017),
(10, 14, 10, 'La Politique pour les nuls', 'Livre de Donald Trump', 2000);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `uti_id` int(11) NOT NULL,
  `uti_pseudo` varchar(30) NOT NULL,
  `uti_password` varchar(255) NOT NULL,
  `uti_adress_mail` varchar(50) NOT NULL,
  `uti_droit_access` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`uti_id`, `uti_pseudo`, `uti_password`, `uti_adress_mail`, `uti_droit_access`) VALUES
(14, 'AngeloidsGN', '$2y$10$xhaA9SR17tgzjG.1p04CD.TGN3hVKsrVQtQsJF3jdQzEe5uOv.fHu', 'Deus@lacatholille.fr', 'admin'),
(15, 'Thomas', '$2y$10$0DdpA3rrUAjaNOvy9he4x.byvSTyNndoO1N7qT9D/g0IAPu79Nymu', 'thomas.lyautay@lacatholille.fr', 'utilisateur'),
(17, 'Julian', '$2y$10$fD1UoZmfGgYPRgTO2qONJ.Y3LnmcMdDUW9ZKNU8KgQ6kYd7ygFNnO', 'julian.wickeur@lacatholille.fr', 'utilisateur');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `attribuer`
--
ALTER TABLE `attribuer`
  ADD KEY `Genre_ID` (`id_genre_ext`),
  ADD KEY `Livre_ID` (`id_livre_ext`);

--
-- Index pour la table `auteur`
--
ALTER TABLE `auteur`
  ADD PRIMARY KEY (`id_auteur`);

--
-- Index pour la table `genre`
--
ALTER TABLE `genre`
  ADD UNIQUE KEY `id_genre` (`id_genre`);

--
-- Index pour la table `livre`
--
ALTER TABLE `livre`
  ADD PRIMARY KEY (`id_livre`),
  ADD UNIQUE KEY `liv_titre` (`liv_titre`),
  ADD KEY `Utilisateur_ID` (`id_uti_ext`),
  ADD KEY `Auteur_ID` (`id_auteur_ext`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`uti_id`),
  ADD UNIQUE KEY `uti_pseudo` (`uti_pseudo`),
  ADD UNIQUE KEY `uti_adress_mail` (`uti_adress_mail`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `auteur`
--
ALTER TABLE `auteur`
  MODIFY `id_auteur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `genre`
--
ALTER TABLE `genre`
  MODIFY `id_genre` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `livre`
--
ALTER TABLE `livre`
  MODIFY `id_livre` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `uti_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `attribuer`
--
ALTER TABLE `attribuer`
  ADD CONSTRAINT `Genre_ID` FOREIGN KEY (`id_genre_ext`) REFERENCES `genre` (`id_genre`),
  ADD CONSTRAINT `Livre_ID` FOREIGN KEY (`id_livre_ext`) REFERENCES `livre` (`id_livre`);

--
-- Contraintes pour la table `livre`
--
ALTER TABLE `livre`
  ADD CONSTRAINT `Auteur_ID` FOREIGN KEY (`id_auteur_ext`) REFERENCES `auteur` (`id_auteur`),
  ADD CONSTRAINT `Utilisateur_ID` FOREIGN KEY (`id_uti_ext`) REFERENCES `utilisateur` (`uti_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
