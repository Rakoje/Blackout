-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3308
-- Generation Time: Jun 06, 2021 at 07:47 PM
-- Server version: 5.7.31
-- PHP Version: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blackout`
--

-- --------------------------------------------------------

--
-- Table structure for table `administrator`
--

DROP TABLE IF EXISTS `administrator`;
CREATE TABLE IF NOT EXISTS `administrator` (
  `IdU` int(11) NOT NULL,
  `Description` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`IdU`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `administrator`
--

INSERT INTO `administrator` (`IdU`, `Description`) VALUES
(2, 'Page Owner'),
(4, 'Page Owner');

-- --------------------------------------------------------

--
-- Table structure for table `bartender`
--

DROP TABLE IF EXISTS `bartender`;
CREATE TABLE IF NOT EXISTS `bartender` (
  `Rating` decimal(10,2) NOT NULL,
  `IdU` int(11) NOT NULL,
  PRIMARY KEY (`IdU`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `bartender`
--

INSERT INTO `bartender` (`Rating`, `IdU`) VALUES
('5.00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

DROP TABLE IF EXISTS `comment`;
CREATE TABLE IF NOT EXISTS `comment` (
  `IdC` int(11) NOT NULL AUTO_INCREMENT,
  `Content` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `IdU` int(11) NOT NULL,
  `IdR` int(11) NOT NULL,
  `CreationDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`IdC`),
  KEY `R_5` (`IdU`),
  KEY `R_6` (`IdR`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`IdC`, `Content`, `IdU`, `IdR`, `CreationDate`) VALUES
(1, 'Aaaaaaa', 4, 1, '2021-06-03 22:07:39'),
(2, 'Aaaaaaaaaaaaa', 3, 1, '2021-06-03 22:07:39'),
(3, 'BBBBBBBBBB', 2, 1, '2021-06-03 22:09:08'),
(4, 'Asasasasasa', 5, 1, '2021-06-03 22:09:08'),
(6, 'asdasdasdas', 2, 1, '2021-06-04 00:38:28');

-- --------------------------------------------------------

--
-- Table structure for table `genericuser`
--

DROP TABLE IF EXISTS `genericuser`;
CREATE TABLE IF NOT EXISTS `genericuser` (
  `IdU` int(11) NOT NULL,
  PRIMARY KEY (`IdU`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `genericuser`
--

INSERT INTO `genericuser` (`IdU`) VALUES
(3),
(5);

-- --------------------------------------------------------

--
-- Table structure for table `hasrated`
--

DROP TABLE IF EXISTS `hasrated`;
CREATE TABLE IF NOT EXISTS `hasrated` (
  `IdU` int(11) NOT NULL,
  `IdR` int(11) NOT NULL,
  `Rating` int(11) NOT NULL,
  PRIMARY KEY (`IdU`,`IdR`),
  KEY `IdR` (`IdR`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `hasrated`
--

INSERT INTO `hasrated` (`IdU`, `IdR`, `Rating`) VALUES
(2, 1, 3),
(3, 1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `ingredient`
--

DROP TABLE IF EXISTS `ingredient`;
CREATE TABLE IF NOT EXISTS `ingredient` (
  `IdI` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `IdReq` int(11) DEFAULT NULL,
  PRIMARY KEY (`IdI`),
  KEY `R_16` (`IdReq`)
) ENGINE=MyISAM AUTO_INCREMENT=54 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ingredient`
--

INSERT INTO `ingredient` (`IdI`, `Name`, `IdReq`) VALUES
(1, 'Sloe gin', NULL),
(2, 'Extra-dry vermouth', NULL),
(3, 'Campari', NULL),
(4, 'Orange', NULL),
(5, 'Thyme', NULL),
(6, 'Vodka', NULL),
(7, 'Lemon juice', NULL),
(8, 'Sugar', NULL),
(9, 'Tequila', NULL),
(10, 'Mint leaves', NULL),
(11, 'Lime juice', NULL),
(12, 'Bacardi Superior rum', NULL),
(13, 'Ice', NULL),
(14, 'Soda water', NULL),
(15, 'Lemons', NULL),
(16, 'Angostura bitters', NULL),
(17, 'Maple syrup', NULL),
(18, 'Cranberry juice', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ismadeof`
--

DROP TABLE IF EXISTS `ismadeof`;
CREATE TABLE IF NOT EXISTS `ismadeof` (
  `IdI` int(11) NOT NULL,
  `IdR` int(11) NOT NULL,
  `Quantity` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`IdI`,`IdR`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ismadeof`
--

INSERT INTO `ismadeof` (`IdI`, `IdR`, `Quantity`) VALUES
(5, 1, NULL),
(4, 1, NULL),
(3, 1, '100ml'),
(2, 1, '100ml'),
(1, 1, '100ml'),
(8, 2, '2tsp'),
(10, 2, '10'),
(11, 2, '15ml'),
(12, 2, '50ml'),
(13, 2, NULL),
(14, 2, NULL),
(15, 3, '2'),
(6, 3, '300ml'),
(18, 3, '150ml'),
(16, 3, '8 drops'),
(17, 3, '3 tbsp'),
(13, 3, NULL),
(18, 18, '5'),
(12, 18, '5'),
(18, 21, '5'),
(2, 21, '5'),
(12, 22, '5');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

DROP TABLE IF EXISTS `message`;
CREATE TABLE IF NOT EXISTS `message` (
  `IdM` int(11) NOT NULL AUTO_INCREMENT,
  `Content` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `IdSender` int(11) NOT NULL,
  `IdReciever` int(11) NOT NULL,
  `IdReq` int(11) DEFAULT NULL,
  `CreationDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`IdM`),
  KEY `R_9` (`IdSender`),
  KEY `R_10` (`IdReciever`),
  KEY `R_15` (`IdReq`)
) ENGINE=MyISAM AUTO_INCREMENT=265 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`IdM`, `Content`, `IdSender`, `IdReciever`, `IdReq`, `CreationDate`) VALUES
(1, 'This request for a new store for maximaxi12345 has already been approved by dusan123', 6, 2, NULL, '2021-06-03 01:09:49'),
(2, 'This request for a new store for maximaxi12345 has already been approved by dusan123', 6, 4, NULL, '2021-06-03 01:09:49'),
(3, 'Welcome to our web page maximaxi12345', 2, 6, NULL, '2021-06-03 01:13:03');

-- --------------------------------------------------------

--
-- Table structure for table `possesses`
--

DROP TABLE IF EXISTS `possesses`;
CREATE TABLE IF NOT EXISTS `possesses` (
  `IdI` int(11) NOT NULL,
  `IdU` int(11) NOT NULL,
  `Price` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`IdI`,`IdU`),
  KEY `R_8` (`IdU`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `possesses`
--

INSERT INTO `possesses` (`IdI`, `IdU`, `Price`) VALUES
(15, 3, NULL),
(18, 3, NULL),
(3, 1, NULL),
(1, 1, NULL),
(4, 1, NULL),
(3, 2, NULL),
(2, 3, NULL),
(11, 3, NULL),
(4, 3, NULL),
(9, 3, NULL),
(16, 3, NULL),
(12, 2, NULL),
(2, 2, NULL),
(13, 2, NULL),
(17, 2, NULL),
(3, 3, NULL),
(15, 2, NULL),
(11, 2, NULL),
(7, 2, NULL),
(14, 2, NULL),
(4, 2, NULL),
(10, 2, NULL),
(5, 2, NULL),
(6, 2, NULL),
(1, 2, NULL),
(12, 1, NULL),
(18, 1, NULL),
(2, 1, NULL),
(13, 1, NULL),
(7, 1, NULL),
(11, 1, NULL),
(17, 1, NULL),
(10, 1, NULL),
(14, 1, NULL),
(8, 1, NULL),
(5, 1, NULL),
(8, 2, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `recipe`
--

DROP TABLE IF EXISTS `recipe`;
CREATE TABLE IF NOT EXISTS `recipe` (
  `IdR` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `Description` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `NumOfServings` int(11) NOT NULL,
  `PrepTime` int(11) NOT NULL,
  `Strength` int(11) NOT NULL,
  `Method` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `IdU` int(11) NOT NULL,
  `CreationDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `NumberOfRatings` int(11) NOT NULL DEFAULT '0',
  `Rating` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`IdR`),
  KEY `R_4` (`IdU`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `recipe`
--

INSERT INTO `recipe` (`IdR`, `Name`, `Description`, `NumOfServings`, `PrepTime`, `Strength`, `Method`, `IdU`, `CreationDate`, `NumberOfRatings`, `Rating`) VALUES
(1, 'Sloe gin Negroni', 'Whether you\'re after a gift or simply looking to treat yourself this year, make the most of a special bottle of sloe gin by knocking up this astringent and lip-smacking sloe gin Negroni', 4, 5, 6, '1. Pour 100ml each sloe gin, extra-dry vermouth and Campari into a jug with plenty of ice cubes and a sprig of thyme; mix well until the jug feels cold.\r\n</br>2. Strain into 4 tumblers filled with ice. Garnish with thin slices of blood orange and thyme sprigs.', 1, '2021-05-26 23:41:26', 2, 4),
(2, 'Mojito', 'If you are looking for a classic mojito recipe with no frills, then you will love this refreshingly drink with just the bare essentials.', 1, 10, 4, '1. Tear 10 mint leaves in half and drop them into a highball glass.</br>\r\n2. Add the sugar and lime juice and half fill the glass with crushed ice. Stir well.</br>\r\n3. Add the rum and top up with soda water. Stir with a bar spoon. Add a little more crushed ice and garnish with mint leaves.</br>\r\nTip: Take a tip from the professionals and squeeze the mint leaves before using, to release their oils. Then rub them around the rim of the glass for an extra hit of minty aroma and flavour.', 1, '2021-05-27 23:41:26', 0, 0),
(3, 'Blush martini', 'This pretty, pink martini is sure to make your party guests blush! Made with lemon and cranberry juice, maple syrup, vodka and Angostura bitters, this punchy martini is sure to get the party started', 8, 10, 3, '1. Take the lemons and, using a vegetable peeler, peel off eight thick strips of skin. Use a knife to remove white pith from the strips, then set aside.</br>\r\n2. Halve both lemons and squeeze out the juice. In a jug, combine the lemon juice, vodka, cranberry juice, maple syrup, Angostura bitters and ice cubes (if serving straight away). Stir well until combined.</br>\r\n3. If serving immediately, strain into chilled Martini glasses garnished with strips of lemon zest, or keep chilled if serving later.', 1, '2021-05-28 03:43:52', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

DROP TABLE IF EXISTS `request`;
CREATE TABLE IF NOT EXISTS `request` (
  `IdReq` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `IdU` int(11) DEFAULT NULL,
  `Description` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Approved` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`IdReq`),
  KEY `R_14` (`IdU`)
) ENGINE=MyISAM AUTO_INCREMENT=50 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `request`
--

INSERT INTO `request` (`IdReq`, `Name`, `IdU`, `Description`, `Approved`) VALUES
(2, 'vlada44', 4, 'asassssssss', 1),
(1, 'dusan123', 2, 'aaaaaaa', 1),
(3, 'maximaxi12345', 42, 'Opisopis', 1);

-- --------------------------------------------------------

--
-- Table structure for table `store`
--

DROP TABLE IF EXISTS `store`;
CREATE TABLE IF NOT EXISTS `store` (
  `Owner` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `OpeningHours` time NOT NULL,
  `ClosingHours` time NOT NULL,
  `IdU` int(11) NOT NULL,
  `Description` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `Phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`IdU`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `store`
--

INSERT INTO `store` (`Owner`, `OpeningHours`, `ClosingHours`, `IdU`, `Description`, `Phone`) VALUES
('Dusan', '08:00:00', '23:00:00', 6, 'Opisopis', '061616161');

-- --------------------------------------------------------

--
-- Table structure for table `usertemplate`
--

DROP TABLE IF EXISTS `usertemplate`;
CREATE TABLE IF NOT EXISTS `usertemplate` (
  `IdU` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `Pass` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `E_mail` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `Name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `Surname` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Address` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`IdU`)
) ENGINE=MyISAM AUTO_INCREMENT=49 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `usertemplate`
--

INSERT INTO `usertemplate` (`IdU`, `Username`, `Pass`, `E_mail`, `Name`, `Surname`, `Address`) VALUES
(1, 'andrijar', 'sifra12345', 'rakojevic@gmail.com', 'Andrija', 'Rakojevic', 'Ruzveltova 11'),
(2, 'dusan123', 'sifra12345', 'dusanterzic44@yahoo.com', 'Dusan', 'Terzic', 'Despota Stefana 67'),
(3, 'anastasija123', 'sifra12345', 'tomic_anastasija@gmail.com', 'Anastasija', 'Tomic', 'Jurija Gagarina 192'),
(4, 'vlada44', 'sifra12345', 'vladimirs44@hotmail.com', 'Vladimir', 'Smiljanic', 'Filipa Visnjica 103'),
(5, 'teacup', 'sifra12345', 'teodora_perovic@gmail.com', 'Teodora', 'Perovic', 'Bezanijska bb'),
(6, 'maximaxi12345', 'sifra12345', 'maxi@maxi.com', 'Maxi', NULL, 'Vojvode Savatija 4');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
