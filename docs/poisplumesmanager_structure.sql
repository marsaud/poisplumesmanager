-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mer 16 Janvier 2013 à 21:22
-- Version du serveur: 5.5.24-log
-- Version de PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `poisplumesmanager2`
--

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

CREATE TABLE IF NOT EXISTS `article` (
  `ref` varchar(45) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `tax_id` int(11) NOT NULL,
  `priceht` decimal(10,2) NOT NULL,
  `stocked` tinyint(1) NOT NULL,
  `qty` decimal(10,4) DEFAULT NULL,
  `unit` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`ref`),
  UNIQUE KEY `name_UNIQUE` (`name`),
  KEY `fk_article_tva1` (`tax_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `articleprovider`
--

CREATE TABLE IF NOT EXISTS `articleprovider` (
  `provider_id` int(11) NOT NULL,
  `article_ref` varchar(45) NOT NULL,
  PRIMARY KEY (`provider_id`,`article_ref`),
  KEY `fk_articleprovider_article1` (`article_ref`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `carttrailer`
--

CREATE TABLE IF NOT EXISTS `carttrailer` (
  `hash` varchar(32) NOT NULL,
  `cart` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `payed` tinyint(1) NOT NULL,
  `payment_date` timestamp NULL DEFAULT NULL,
  `cancelled` tinyint(1) NOT NULL,
  `cancel_date` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`hash`),
  UNIQUE KEY `hash_3` (`hash`),
  KEY `hash` (`hash`),
  KEY `hash_2` (`hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `ref` varchar(45) NOT NULL,
  `name` varchar(255) NOT NULL,
  `desc` text,
  `category_ref` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`ref`),
  UNIQUE KEY `name_UNIQUE` (`name`),
  KEY `fk_category_category1` (`category_ref`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `categoryarticle`
--

CREATE TABLE IF NOT EXISTS `categoryarticle` (
  `article_ref` varchar(45) NOT NULL,
  `category_ref` varchar(45) NOT NULL,
  PRIMARY KEY (`article_ref`,`category_ref`),
  KEY `fk_categoryarticle_category1` (`category_ref`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `operationlines`
--

CREATE TABLE IF NOT EXISTS `operationlines` (
  `hash` varchar(32) NOT NULL,
  `reference` varchar(45) NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `raw_price` decimal(10,2) NOT NULL,
  `tax_amount` decimal(10,2) NOT NULL,
  `sale_price` float(10,2) NOT NULL,
  `final_price` float(10,2) NOT NULL,
  `tax_id` int(11) DEFAULT NULL,
  `tax_ratio` decimal(10,2) DEFAULT NULL,
  `promo_id` int(11) DEFAULT NULL,
  `promo_ratio` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `operationstrail`
--

CREATE TABLE IF NOT EXISTS `operationstrail` (
  `hash` varchar(32) NOT NULL,
  `total_raw_price` decimal(10,2) NOT NULL,
  `total_tax` decimal(10,2) NOT NULL,
  `total_sale_price` decimal(10,2) NOT NULL,
  `cb` decimal(10,2) NOT NULL,
  `chq` decimal(10,2) NOT NULL,
  `chr` decimal(10,2) NOT NULL,
  `mon` decimal(10,2) NOT NULL,
  PRIMARY KEY (`hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `promo`
--

CREATE TABLE IF NOT EXISTS `promo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ratio` decimal(10,2) NOT NULL,
  `name` varchar(45) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_uniq` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Structure de la table `promoarticle`
--

CREATE TABLE IF NOT EXISTS `promoarticle` (
  `article_ref` varchar(45) NOT NULL,
  `promo_id` int(11) NOT NULL,
  PRIMARY KEY (`article_ref`,`promo_id`),
  KEY `fk_promoarticle_promo1` (`promo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `provider`
--

CREATE TABLE IF NOT EXISTS `provider` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `info` longtext,
  `comment` longtext,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Structure de la table `purchase`
--

CREATE TABLE IF NOT EXISTS `purchase` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `item` text NOT NULL,
  `priceht` float(10,2) NOT NULL,
  `pricettc` float(10,2) NOT NULL,
  `tax` float(10,2) NOT NULL,
  `paymode` enum('cb','chq','mon') NOT NULL,
  `offmargin` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `reporttrailview`
--
CREATE TABLE IF NOT EXISTS `reporttrailview` (
`hash` varchar(32)
,`pay_date` timestamp
,`total` decimal(10,2)
,`cb` decimal(10,2)
,`chq` decimal(10,2)
,`chr` decimal(10,2)
,`mon` decimal(10,2)
);
-- --------------------------------------------------------

--
-- Structure de la table `stocktrail`
--

CREATE TABLE IF NOT EXISTS `stocktrail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `articleref` varchar(45) NOT NULL,
  `articlename` varchar(255) NOT NULL,
  `previous` decimal(10,4) NOT NULL,
  `modif` decimal(10,4) NOT NULL,
  `new` decimal(10,4) NOT NULL,
  `unit` varchar(45) DEFAULT NULL,
  `date` datetime NOT NULL,
  `user` varchar(45) NOT NULL,
  `comment` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Structure de la table `tax`
--

CREATE TABLE IF NOT EXISTS `tax` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ratio` decimal(10,2) NOT NULL,
  `name` varchar(45) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Structure de la vue `reporttrailview`
--
DROP TABLE IF EXISTS `reporttrailview`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `reporttrailview` AS select `c`.`hash` AS `hash`,`c`.`payment_date` AS `pay_date`,`o`.`total_sale_price` AS `total`,`o`.`cb` AS `cb`,`o`.`chq` AS `chq`,`o`.`chr` AS `chr`,`o`.`mon` AS `mon` from (`carttrailer` `c` join `operationstrail` `o` on((`c`.`hash` = `o`.`hash`))) where (`c`.`payed` = 1);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `article_ibfk_1` FOREIGN KEY (`tax_id`) REFERENCES `tax` (`id`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `articleprovider`
--
ALTER TABLE `articleprovider`
  ADD CONSTRAINT `articleprovider_ibfk_1` FOREIGN KEY (`article_ref`) REFERENCES `article` (`ref`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_articleprovider_provider1` FOREIGN KEY (`provider_id`) REFERENCES `provider` (`id`);

--
-- Contraintes pour la table `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `fk_category_category1` FOREIGN KEY (`category_ref`) REFERENCES `category` (`ref`);

--
-- Contraintes pour la table `categoryarticle`
--
ALTER TABLE `categoryarticle`
  ADD CONSTRAINT `categoryarticle_ibfk_1` FOREIGN KEY (`article_ref`) REFERENCES `article` (`ref`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_categoryarticle_category1` FOREIGN KEY (`category_ref`) REFERENCES `category` (`ref`);

--
-- Contraintes pour la table `promoarticle`
--
ALTER TABLE `promoarticle`
  ADD CONSTRAINT `fk_promoarticle_article1` FOREIGN KEY (`article_ref`) REFERENCES `article` (`ref`),
  ADD CONSTRAINT `fk_promoarticle_promo1` FOREIGN KEY (`promo_id`) REFERENCES `promo` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
