-- phpMyAdmin SQL Dump
-- version 3.3.2deb1ubuntu1
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Ven 03 Août 2012 à 21:03
-- Version du serveur: 5.1.63
-- Version de PHP: 5.3.2-1ubuntu4.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `poisplumesmanager`
--

--
-- Contenu de la table `articleprovider`
--


--
-- Contenu de la table `category`
--

INSERT INTO `category` (`ref`, `name`, `desc`, `category_ref`) VALUES
('cat1', 'CatÃ©gorie 1', 'CatÃ©gorie vide.', NULL),
('cat2', 'CatÃ©gorie 2', 'CatÃ©gorie avec une sous-catÃ©gorie.', NULL),
('cat3', 'CatÃ©gorie 3', 'Sous-catÃ©gorie de CatÃ©gorie 2.', 'cat2'),
('cat4', 'CatÃ©gorie 4', 'PremiÃ¨re tentative de crÃ©ation', NULL),
('cat5', 'CatÃ©gorie 5', 'Sous-catÃ©gorie crÃ©Ã©e via l''IHM.', 'cat4'),
('cat6', 'CatÃ©gorie 6', 'C''est pour tester oÃ¹ j''en suis.', 'cat4');

--
-- Contenu de la table `categoryarticle`
--


--
-- Contenu de la table `combo`
--


--
-- Contenu de la table `comboarticle`
--


--
-- Contenu de la table `operations`
--


--
-- Contenu de la table `operationstrail`
--


--
-- Contenu de la table `payment`
--


--
-- Contenu de la table `provider`
--

INSERT INTO `provider` (`id`, `name`, `info`, `comment`) VALUES
(1, 'Les lampes modernes', 'Trop top !!', 'TrÃ´ le staÃ¯lle'),
(2, 'les lampes ringardes', 'Du blabla', 'Du froufrou');

--
-- Contenu de la table `stock`
--


--
-- Contenu de la table `stocktrail`
--


--
-- Contenu de la table `tax`
--

INSERT INTO `tax` (`id`, `ratio`, `name`, `description`) VALUES
(1, '19.60', 'GÃ©nÃ©raliste', 'Les produits dans le cas gÃ©nÃ©ral.'),
(2, '0.00', 'CrÃ©ateurs indÃ©pendants', 'Je modifie ma description...'),
(3, '5.50', 'Restauration', 'La restauration sauf boissons.'),
(4, '33.33', 'Bidon', 'Ã‡a c''est pour tester le tout, modification comprise.');

--
-- Contenu de la table `article`
--

INSERT INTO `article` (`ref`, `name`, `description`, `tax_id`, `priceht`, `stocked`) VALUES
('art1', 'Article 1', 'Le premier article', 1, '99.00', 0),
('art2', 'Article 2', 'Le deuxième article.', 2, '88.00', 1);
