-- phpMyAdmin SQL Dump
-- version 3.3.2deb1ubuntu1
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Mer 12 Septembre 2012 à 21:35
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
-- Contenu de la table `tax`
--

INSERT INTO `tax` (`id`, `ratio`, `name`, `description`) VALUES
(1, '19.60', 'GÃ©nÃ©raliste', 'Les produits dans le cas gÃ©nÃ©ral.'),
(2, '0.00', 'CrÃ©ateurs indÃ©pendants', 'Je modifie ma description...'),
(3, '5.50', 'Restauration', 'La restauration sauf boissons.'),
(4, '33.33', 'Bidon', 'Ã‡a c''est pour tester le tout, modification comprise.');

--
-- Contenu de la table `category`
--

INSERT INTO `category` (`ref`, `name`, `desc`, `category_ref`) VALUES
('cat1', 'CatÃ©gorie 1', 'CatÃ©gorie vide.', NULL),
('cat2', 'CatÃ©gorie 2', 'CatÃ©gorie avec une sous-catÃ©gorie.COUCOU', NULL),
('cat3', 'CatÃ©gorie 3', 'Sous-catÃ©gorie de CatÃ©gorie 2.', 'cat2'),
('cat4', 'CatÃ©gorie 4', 'PremiÃ¨re tentative de crÃ©ation', NULL),
('cat5', 'CatÃ©gorie 5', 'Sous-catÃ©gorie crÃ©Ã©e via l''IHM.', 'cat4'),
('cat6', 'CatÃ©gorie 6', 'C''est pour tester oÃ¹ j''en suis.', 'cat4');

--
-- Contenu de la table `article`
--

INSERT INTO `article` (`ref`, `name`, `description`, `tax_id`, `priceht`, `stocked`, `qty`, `unit`) VALUES
('art1', 'Article 1', 'Le premier article', 1, '99.00', 0, NULL, NULL),
('art2', 'Article 2', '', 2, '88.00', 1, NULL, ''),
('dddd', 'fffffff', '(r.a.s.)', 2, '2.00', 0, NULL, NULL),
('kjhfg', 'dfgbfdq', 'qsdf', 4, '1.00', 0, NULL, NULL),
('kjhfgrezrezer', 'dfgbfdqzerzer', 'qsdf', 4, '1.00', 0, NULL, NULL),
('lkjgyu', 'uyukjch', 'qsdf', 4, '1.00', 0, NULL, NULL),
('mjlklmfkj', 'mlhgkdgmj', 'qsdf', 4, '1.00', 0, NULL, NULL),
('qsdf', 'qdsf', 'qsdf', 4, '1.00', 0, NULL, NULL),
('tata', 'titi', '(r.a.s.)', 4, '0.00', 0, NULL, NULL),
('xxx', 'xxx', '(r.a.s.)', 4, '1.00', 0, NULL, NULL),
('yyy', 'yyy', '(r.a.s.)', 4, '1.00', 0, NULL, NULL);

--
-- Contenu de la table `categoryarticle`
--

INSERT INTO `categoryarticle` (`article_ref`, `category_ref`) VALUES
('art1', 'cat1'),
('art1', 'cat4'),
('art2', 'cat2'),
('dddd', 'cat2'),
('dddd', 'cat4'),
('tata', 'cat1'),
('tata', 'cat3'),
('tata', 'cat5'),
('xxx', 'cat1'),
('yyy', 'cat1');

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
-- Contenu de la table `promo`
--

INSERT INTO `promo` (`id`, `ratio`, `name`, `description`) VALUES
(1, '-30.00', 'Soldes de printemps', 'Un petit tiers de rÃ©duc...'),
(3, '-50.00', 'Soldes d''automne', 'MoitiÃ© prix.');

--
-- Contenu de la table `promoarticle`
--

INSERT INTO `promoarticle` (`article_ref`, `promo_id`) VALUES
('dddd', 3),
('tata', 3);

--
-- Contenu de la table `provider`
--

INSERT INTO `provider` (`id`, `name`, `info`, `comment`) VALUES
(1, 'Les lampes modernes', 'Trop top !!', 'TrÃ´ le staÃ¯lle'),
(2, 'les lampes ringardes', 'Du blabla', 'Du froufrou');

--
-- Contenu de la table `stocktrail`
--



