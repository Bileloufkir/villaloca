-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  mer. 27 nov. 2019 à 15:46
-- Version du serveur :  5.7.26
-- Version de PHP :  7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `villaloca`
--

-- --------------------------------------------------------

--
-- Structure de la table `agence`
--

DROP TABLE IF EXISTS `agence`;
CREATE TABLE IF NOT EXISTS `agence` (
  `id_agence` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(200) NOT NULL,
  `adresse` varchar(50) NOT NULL,
  `ville` varchar(50) NOT NULL,
  `code_postal` int(3) NOT NULL,
  `description` text NOT NULL,
  `photo` varchar(200) NOT NULL,
  PRIMARY KEY (`id_agence`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `agence`
--

INSERT INTO `agence` (`id_agence`, `titre`, `adresse`, `ville`, `code_postal`, `description`, `photo`) VALUES
(1, 'Agence de Paris', '300 boulevard de vaugirard', 'Paris', 75015, 'Notre agence de Paris est ouvert de 09h a 18h tout les jours. ', 'Paris_Tour-Eiffel.jpg'),
(2, 'Agence de Lyon', '4 rue sainte catherine ', 'Lyon', 69003, 'Notre agence de Lyon est ouvert de 09h a 18h tout les jours. ', 'Agence Lyon_agence_lyon.jpg'),
(3, 'Agence de Bordeaux', '10 Place de l&#039;hotel de ville ', 'Bordeaux', 77005, 'Notre agence de Bordeaux est ouvert de 09h a 18h tout les jours. ', 'bordeau_agence_bordeaux.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

DROP TABLE IF EXISTS `commande`;
CREATE TABLE IF NOT EXISTS `commande` (
  `id_commande` int(11) NOT NULL AUTO_INCREMENT,
  `id_membre` int(3) NOT NULL,
  `id_vehicule` int(3) NOT NULL,
  `id_agence` int(3) NOT NULL,
  `date_heure_depart` datetime NOT NULL,
  `date_heure_fin` datetime NOT NULL,
  `prix_total` int(3) NOT NULL,
  `date_enregistrement` datetime NOT NULL,
  PRIMARY KEY (`id_commande`),
  KEY `id_membre` (`id_membre`),
  KEY `id_vehicule` (`id_vehicule`),
  KEY `id_agence` (`id_agence`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `membre`
--

DROP TABLE IF EXISTS `membre`;
CREATE TABLE IF NOT EXISTS `membre` (
  `id_membre` int(11) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(20) NOT NULL,
  `mdp` varchar(60) NOT NULL,
  `nom` varchar(20) NOT NULL,
  `prenom` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `civilite` enum('m','f') NOT NULL,
  `statut` int(3) NOT NULL,
  `date_enregistrement` datetime NOT NULL,
  PRIMARY KEY (`id_membre`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `membre`
--

INSERT INTO `membre` (`id_membre`, `pseudo`, `mdp`, `nom`, `prenom`, `email`, `civilite`, `statut`, `date_enregistrement`) VALUES
(4, 'Bilel', '5c0576dc596b1ba2f9b13847ddb4214c', 'oufkir', 'bilel', 'biilel_95@hotmail.fr', 'm', 1, '2019-05-21 14:00:21'),
(5, 'damien', '8e219c916a8e3fa7dabb96986df14e08', 'daheb', 'damien', 'damien_daheb@hotmail.fr', 'm', 0, '2019-05-22 12:18:22');

-- --------------------------------------------------------

--
-- Structure de la table `villa`
--

DROP TABLE IF EXISTS `villa`;
CREATE TABLE IF NOT EXISTS `villa` (
  `id_villa` int(11) NOT NULL AUTO_INCREMENT,
  `id_agence` int(3) NOT NULL,
  `titre` varchar(200) NOT NULL,
  `marque` varchar(50) NOT NULL,
  `modele` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `photo` varchar(200) NOT NULL,
  `prix_journalier` int(3) NOT NULL,
  PRIMARY KEY (`id_villa`),
  KEY `id_agence` (`id_agence`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `villa`
--

INSERT INTO `villa` (`id_villa`, `id_agence`, `titre`, `marque`, `modele`, `description`, `photo`, `prix_journalier`) VALUES
(22, 1, 'Villa Bali ', 'ss', 's', 's', 'Villa Bali _agadir-villa-onarae-109870045cb088662413b9.26563696.848.jpg', 300),
(23, 1, 'Villa Rouge ', 'Marrakech', 's', 's', 'Villa Rouge _marrakech-villa-rouge-royal-palm-16061053915ccc67c57df1f1.07337127.848.jpg', 300),
(24, 1, 'Villa Suisse', 'Suisse', 's', 's', 'Villa Suisse_alpes-suisses-chalet-rustic-6749219715cf671abb4f7f2.81741444.848.jpg', 100),
(25, 1, 'Villa île Maurice', 'île Maurice ', 's', 's', 'Villa île Maurice_le-cap-villa-sondag-i-15991478725c49878b85e485.73131502.848.jpg', 300),
(26, 1, 'Ryad Essaouira', 'Essaouira', 's', 's', 'Ryad Essaouira_marrakech-dar-cheref-5403270485ada0d13ee4718.03885986.848.jpg', 200),
(27, 1, 'Ryad Marrakech', 'Marrakech', 'sss', 's', 'Ryad Marrakech_marrakech-dar-baraka-karam-6435225025c93555e7370d0.19247471.848.jpg', 300),
(28, 1, 'Chalet Japon', 'Japon', 's', 'ss', 'Chalet Japon_niseko-chalet-birchwood-21446033605d6d1df8f414f2.49675604.848.jpg', 250),
(29, 1, 'Villa Agadir', 'Agadir', 's', 's', 'Villa Agadir_agadir-villa-onarae-109870045cb088662413b9.26563696.848.jpg', 150),
(30, 1, 'Villa Phuket', 'Thailande', 'd', 'd', 'Villa Phuket_phuket-villa-baan-bon-khao-463181967591bf7fb20e045.37427376.848.jpg', 148),
(31, 1, 'Villa Thailande', 'Thailande', 's', 's', 'Villa Thailande_Bahia_Casa_Txai_979161993541bfc6ea5c6f7.49797377.848.jpg', 250),
(32, 1, 'Villa Laos', 'Laos', 's', 's', 'Villa Laos_baa-atoll-amilla-villa-estate-8475881005812fb8b04ac27.71570053.848.jpg', 200);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `commande_ibfk_1` FOREIGN KEY (`id_vehicule`) REFERENCES `villa` (`id_villa`),
  ADD CONSTRAINT `commande_ibfk_2` FOREIGN KEY (`id_membre`) REFERENCES `membre` (`id_membre`),
  ADD CONSTRAINT `commande_ibfk_3` FOREIGN KEY (`id_agence`) REFERENCES `agence` (`id_agence`);

--
-- Contraintes pour la table `villa`
--
ALTER TABLE `villa`
  ADD CONSTRAINT `villa_ibfk_1` FOREIGN KEY (`id_agence`) REFERENCES `agence` (`id_agence`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
