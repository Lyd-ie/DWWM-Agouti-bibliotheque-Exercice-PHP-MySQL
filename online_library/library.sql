-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 16 mars 2023 à 09:25
-- Version du serveur : 8.0.31
-- Version de PHP : 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `library`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `FullName` varchar(100) DEFAULT NULL,
  `AdminEmail` varchar(120) DEFAULT NULL,
  `UserName` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `updationDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `admin`
--

INSERT INTO `admin` (`id`, `FullName`, `AdminEmail`, `UserName`, `Password`, `updationDate`) VALUES
(1, 'Administrateur', 'admin@gmail.com', 'admin', '$2y$10$fIgZ28bO4RCAr44wkDC2ReMCeciLqS6c6S52TcpmvmG05Ndw.pt4K', '2023-02-24 08:39:30');

-- --------------------------------------------------------

--
-- Structure de la table `tblauthors`
--

DROP TABLE IF EXISTS `tblauthors`;
CREATE TABLE IF NOT EXISTS `tblauthors` (
  `id` int NOT NULL AUTO_INCREMENT,
  `AuthorName` varchar(159) DEFAULT NULL,
  `creationDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tblauthors`
--

INSERT INTO `tblauthors` (`id`, `AuthorName`, `creationDate`, `UpdationDate`) VALUES
(1, 'Guillaume Musso', '2017-07-08 12:49:09', '2021-07-23 08:41:21'),
(2, 'Michel Bussi', '2017-07-08 14:30:23', '2021-07-23 08:43:21'),
(3, 'Marc Levy', '2017-07-08 14:35:08', '2021-07-23 08:43:40'),
(4, 'Françoise Bourdin', '2017-07-08 14:35:21', '2021-07-23 08:44:00'),
(5, 'Gilles Legardinier', '2017-07-08 14:35:36', '2021-07-23 08:44:25'),
(9, 'Agnès Martin', '2017-07-08 15:22:03', '2021-07-23 08:44:50'),
(10, 'Annie Ernaux', '2021-06-23 12:39:10', '2021-07-23 08:46:20');

-- --------------------------------------------------------

--
-- Structure de la table `tblbooks`
--

DROP TABLE IF EXISTS `tblbooks`;
CREATE TABLE IF NOT EXISTS `tblbooks` (
  `id` int NOT NULL AUTO_INCREMENT,
  `BookName` varchar(255) DEFAULT NULL,
  `CatId` int DEFAULT NULL,
  `AuthorId` int DEFAULT NULL,
  `ISBNNumber` int DEFAULT NULL,
  `BookPrice` int DEFAULT NULL,
  `RegDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tblbooks`
--

INSERT INTO `tblbooks` (`id`, `BookName`, `CatId`, `AuthorId`, `ISBNNumber`, `BookPrice`, `RegDate`, `UpdationDate`) VALUES
(1, 'La jeune fille et la nuit', 4, 1, 222333, 21, '2017-07-08 20:04:55', '2021-08-06 15:37:08'),
(3, 'Quelqu\'un de bien', 4, 4, 111123, 6, '2017-07-08 20:17:31', '2021-07-26 09:12:22'),
(4, 'Vingt Mille Lieues sous les Mers', 6, 2, 203735, 12, '2022-02-11 08:25:28', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `tblcategory`
--

DROP TABLE IF EXISTS `tblcategory`;
CREATE TABLE IF NOT EXISTS `tblcategory` (
  `id` int NOT NULL AUTO_INCREMENT,
  `CategoryName` varchar(150) DEFAULT NULL,
  `Status` int DEFAULT NULL,
  `CreationDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdationDate` timestamp NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tblcategory`
--

INSERT INTO `tblcategory` (`id`, `CategoryName`, `Status`, `CreationDate`, `UpdationDate`) VALUES
(5, 'Technologie', 1, '2017-07-04 18:35:39', '2023-03-14 10:19:05'),
(6, 'Science', 1, '2017-07-04 18:35:55', '2021-08-06 15:31:10'),
(7, 'Management', 1, '2017-07-04 18:36:16', '2021-06-23 12:45:41');

-- --------------------------------------------------------

--
-- Structure de la table `tblissuedbookdetails`
--

DROP TABLE IF EXISTS `tblissuedbookdetails`;
CREATE TABLE IF NOT EXISTS `tblissuedbookdetails` (
  `id` int NOT NULL AUTO_INCREMENT,
  `BookId` int DEFAULT NULL,
  `ReaderID` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `IssuesDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ReturnDate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `ReturnStatus` int DEFAULT NULL,
  `fine` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tblissuedbookdetails`
--

INSERT INTO `tblissuedbookdetails` (`id`, `BookId`, `ReaderID`, `IssuesDate`, `ReturnDate`, `ReturnStatus`, `fine`) VALUES
(1, 1, 'SID002', '2017-07-15 06:09:47', '2017-07-15 11:15:20', 1, 0),
(2, 1, 'SID002', '2017-07-15 06:12:27', '2017-07-15 11:15:23', 1, 5),
(3, 3, 'SID002', '2017-07-15 06:13:40', NULL, 0, NULL),
(4, 3, 'SID002', '2017-07-15 06:23:23', '2017-07-15 11:22:29', 1, 2),
(5, 1, 'SID009', '2017-07-15 10:59:26', NULL, 0, NULL),
(6, 3, 'SID011', '2017-07-15 18:02:55', NULL, 0, NULL),
(7, 1, 'SID011', '2021-07-16 13:59:23', NULL, 0, NULL),
(8, 1, 'SID010', '2021-07-20 08:41:34', NULL, 0, NULL),
(9, 3, 'SID012', '2021-07-20 08:44:53', NULL, 0, NULL),
(10, 1, 'SID012', '2021-07-20 08:47:07', NULL, 0, NULL),
(11, 222334, 'SID009', '2021-07-20 08:51:15', NULL, 0, NULL),
(12, 222334, 'SID009', '2021-07-20 09:53:27', NULL, 0, NULL),
(13, 222334, 'SID014', '2021-07-21 14:49:46', '2023-03-14 13:03:07', 1, NULL),
(14, 222334, 'SID017', '2021-07-29 14:14:15', '2023-03-14 13:03:14', 1, NULL),
(15, 111123, 'SID025', '2021-07-30 07:40:06', NULL, 0, NULL),
(16, 203735, 'SID025', '2021-08-06 15:20:20', '2023-03-14 10:22:43', 1, NULL),
(17, 222333, 'SID025', '2021-08-06 15:22:22', NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `tblreaders`
--

DROP TABLE IF EXISTS `tblreaders`;
CREATE TABLE IF NOT EXISTS `tblreaders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ReaderId` varchar(100) DEFAULT NULL,
  `FullName` varchar(120) DEFAULT NULL,
  `EmailId` varchar(120) DEFAULT NULL,
  `MobileNumber` char(11) DEFAULT NULL,
  `Password` varchar(120) DEFAULT NULL,
  `Status` int DEFAULT NULL,
  `RegDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdateDate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `tblreaders`
--

INSERT INTO `tblreaders` (`id`, `ReaderId`, `FullName`, `EmailId`, `MobileNumber`, `Password`, `Status`, `RegDate`, `UpdateDate`) VALUES
(11, 'SID025', 'test', 'test@gmail.com', '0606060606', '$2y$10$4G/Vo7rhWFax7wBhkOA3vuFE74cCpfD4QqkzH2ZkKBkfqGB88zj4S', 1, '2023-02-24 08:29:33', '2023-03-15 14:41:42'),
(69, 'SID029', 'Lydie', 'lydie@toto.fr', '607080910', '$2y$10$33An0K/mwPhWofJg2PqDouZGv52itbe2lIPf9a1UW/P3vaglqME7i', 1, '2023-03-13 12:31:56', '2023-03-15 12:23:54');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
