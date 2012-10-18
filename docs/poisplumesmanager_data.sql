-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Jeu 18 Octobre 2012 à 22:05
-- Version du serveur: 5.5.24-log
-- Version de PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `poisplumesmanager`
--

--
-- Contenu de la table `tax`
--

INSERT INTO `tax` (`id`, `ratio`, `name`, `description`) VALUES
(6, '19.60', 'GÃ©nÃ©rale', '(r.a.s.)');

--
-- Contenu de la table `article`
--

INSERT INTO `article` (`ref`, `name`, `description`, `tax_id`, `priceht`, `stocked`, `qty`, `unit`) VALUES
('four', 'Four', '(r.a.s.)', 6, '4.00', 0, '0.00', NULL),
('one', 'One', '(r.a.s.)', 6, '1.00', 0, '0.00', NULL),
('three', 'Three', '(r.a.s.)', 6, '3.00', 0, '0.00', NULL),
('two', 'Two', '(r.a.s.)', 6, '2.00', 0, '0.00', NULL);

--
-- Contenu de la table `articleprovider`
--

INSERT INTO `articleprovider` (`provider_id`, `article_ref`) VALUES
(4, 'one');

--
-- Contenu de la table `carttrailer`
--

INSERT INTO `carttrailer` (`hash`, `cart`, `date`, `payed`, `payment_date`, `cancelled`, `cancel_date`) VALUES
('4cd52b330fa1536b5519e717acf00dd5', 'a:1:{s:4:"four";O:7:"Article":11:{s:9:"reference";s:4:"four";s:4:"name";s:4:"Four";s:11:"description";s:8:"(r.a.s.)";s:5:"price";s:4:"4.00";s:3:"tax";O:3:"Tax":4:{s:2:"id";s:1:"6";s:5:"ratio";s:5:"19.60";s:4:"name";s:10:"GÃ©nÃ©rale";s:11:"description";s:8:"(r.a.s.)";}s:5:"stock";s:1:"0";s:10:"categories";a:1:{i:0;O:8:"Category":4:{s:9:"reference";s:1:"b";s:4:"name";s:5:"Top B";s:11:"description";s:8:"(r.a.s.)";s:17:"\0*\0_subCategories";C:11:"ArrayObject":383:{x:i:0;a:2:{s:2:"b1";O:8:"Category":4:{s:9:"reference";s:2:"b1";s:4:"name";s:6:"Sub B1";s:11:"description";s:8:"(r.a.s.)";s:17:"\0*\0_subCategories";C:11:"ArrayObject":21:{x:i:0;a:0:{};m:a:0:{}}}s:2:"b2";O:8:"Category":4:{s:9:"reference";s:2:"b2";s:4:"name";s:6:"Sub B2";s:11:"description";s:8:"(r.a.s.)";s:17:"\0*\0_subCategories";C:11:"ArrayObject":21:{x:i:0;a:0:{};m:a:0:{}}}};m:a:0:{}}}}s:10:"\0*\0_promos";O:25:"ArticlePromotionContainer":1:{s:34:"\0ArticlePromotionContainer\0_promos";a:0:{}}s:8:"quantity";s:1:"1";s:4:"unit";N;s:8:"provider";N;}}', '2011-10-18 20:43:00', 1, '2011-10-18 18:43:00', 0, '0000-00-00 00:00:00'),
('8d08b1fbb4ae93bba9a0fd555bc03f7b', 'a:1:{s:3:"two";O:7:"Article":11:{s:9:"reference";s:3:"two";s:4:"name";s:3:"Two";s:11:"description";s:8:"(r.a.s.)";s:5:"price";s:4:"2.00";s:3:"tax";O:3:"Tax":4:{s:2:"id";s:1:"6";s:5:"ratio";s:5:"19.60";s:4:"name";s:10:"GÃ©nÃ©rale";s:11:"description";s:8:"(r.a.s.)";}s:5:"stock";s:1:"0";s:10:"categories";a:1:{i:0;O:8:"Category":4:{s:9:"reference";s:2:"a1";s:4:"name";s:6:"Sub A1";s:11:"description";s:8:"(r.a.s.)";s:17:"\0*\0_subCategories";C:11:"ArrayObject":21:{x:i:0;a:0:{};m:a:0:{}}}}s:10:"\0*\0_promos";O:25:"ArticlePromotionContainer":1:{s:34:"\0ArticlePromotionContainer\0_promos";a:0:{}}s:8:"quantity";s:1:"1";s:4:"unit";N;s:8:"provider";N;}}', '2012-10-18 20:42:04', 1, '2012-10-18 18:42:04', 0, '0000-00-00 00:00:00'),
('bff35b16a0d2ed160406a5e1d3415822', 'a:1:{s:3:"one";O:7:"Article":11:{s:9:"reference";s:3:"one";s:4:"name";s:3:"One";s:11:"description";s:8:"(r.a.s.)";s:5:"price";s:4:"1.00";s:3:"tax";O:3:"Tax":4:{s:2:"id";s:1:"6";s:5:"ratio";s:5:"19.60";s:4:"name";s:10:"GÃ©nÃ©rale";s:11:"description";s:8:"(r.a.s.)";}s:5:"stock";s:1:"0";s:10:"categories";a:1:{i:0;O:8:"Category":4:{s:9:"reference";s:1:"a";s:4:"name";s:5:"Top A";s:11:"description";s:8:"(r.a.s.)";s:17:"\0*\0_subCategories";C:11:"ArrayObject":383:{x:i:0;a:2:{s:2:"a1";O:8:"Category":4:{s:9:"reference";s:2:"a1";s:4:"name";s:6:"Sub A1";s:11:"description";s:8:"(r.a.s.)";s:17:"\0*\0_subCategories";C:11:"ArrayObject":21:{x:i:0;a:0:{};m:a:0:{}}}s:2:"a2";O:8:"Category":4:{s:9:"reference";s:2:"a2";s:4:"name";s:6:"Sub A2";s:11:"description";s:8:"(r.a.s.)";s:17:"\0*\0_subCategories";C:11:"ArrayObject":21:{x:i:0;a:0:{};m:a:0:{}}}};m:a:0:{}}}}s:10:"\0*\0_promos";O:25:"ArticlePromotionContainer":1:{s:34:"\0ArticlePromotionContainer\0_promos";a:0:{}}s:8:"quantity";s:1:"1";s:4:"unit";N;s:8:"provider";O:8:"Provider":4:{s:2:"id";s:1:"4";s:4:"name";s:10:"Provider 1";s:4:"info";s:8:"(r.a.s.)";s:7:"comment";s:8:"(r.a.s.)";}}}', '2012-10-15 20:41:49', 1, '2012-10-15 18:41:49', 0, '0000-00-00 00:00:00'),
('daaa19ec942c9924f7bd9053cd9258e3', 'a:1:{s:5:"three";O:7:"Article":11:{s:9:"reference";s:5:"three";s:4:"name";s:5:"Three";s:11:"description";s:8:"(r.a.s.)";s:5:"price";s:4:"3.00";s:3:"tax";O:3:"Tax":4:{s:2:"id";s:1:"6";s:5:"ratio";s:5:"19.60";s:4:"name";s:10:"GÃ©nÃ©rale";s:11:"description";s:8:"(r.a.s.)";}s:5:"stock";s:1:"0";s:10:"categories";a:1:{i:0;O:8:"Category":4:{s:9:"reference";s:2:"a2";s:4:"name";s:6:"Sub A2";s:11:"description";s:8:"(r.a.s.)";s:17:"\0*\0_subCategories";C:11:"ArrayObject":21:{x:i:0;a:0:{};m:a:0:{}}}}s:10:"\0*\0_promos";O:25:"ArticlePromotionContainer":1:{s:34:"\0ArticlePromotionContainer\0_promos";a:0:{}}s:8:"quantity";s:1:"1";s:4:"unit";N;s:8:"provider";N;}}', '2012-10-08 20:42:35', 1, '2012-10-08 18:42:35', 0, '0000-00-00 00:00:00');

--
-- Contenu de la table `category`
--

INSERT INTO `category` (`ref`, `name`, `desc`, `category_ref`) VALUES
('a', 'Top A', '(r.a.s.)', NULL),
('a1', 'Sub A1', '(r.a.s.)', 'a'),
('a2', 'Sub A2', '(r.a.s.)', 'a'),
('b', 'Top B', '(r.a.s.)', NULL),
('b1', 'Sub B1', '(r.a.s.)', 'b'),
('b2', 'Sub B2', '(r.a.s.)', 'b');

--
-- Contenu de la table `categoryarticle`
--

INSERT INTO `categoryarticle` (`article_ref`, `category_ref`) VALUES
('one', 'a'),
('two', 'a1'),
('three', 'a2'),
('four', 'b');

--
-- Contenu de la table `operationlines`
--

INSERT INTO `operationlines` (`hash`, `reference`, `quantity`, `raw_price`, `tax_amount`, `sale_price`, `final_price`, `tax_id`, `tax_ratio`, `promo_id`, `promo_ratio`) VALUES
('bff35b16a0d2ed160406a5e1d3415822', 'one', '1.00', '0.84', '0.16', 1.00, 1.00, 6, '19.60', NULL, NULL),
('8d08b1fbb4ae93bba9a0fd555bc03f7b', 'two', '1.00', '1.67', '0.33', 2.00, 2.00, 6, '19.60', NULL, NULL),
('daaa19ec942c9924f7bd9053cd9258e3', 'three', '1.00', '2.51', '0.49', 3.00, 3.00, 6, '19.60', NULL, NULL),
('4cd52b330fa1536b5519e717acf00dd5', 'four', '1.00', '3.34', '0.66', 4.00, 4.00, 6, '19.60', NULL, NULL);

--
-- Contenu de la table `operationstrail`
--

INSERT INTO `operationstrail` (`hash`, `total_raw_price`, `total_tax`, `total_sale_price`, `cb`, `chq`, `chr`, `mon`) VALUES
('4cd52b330fa1536b5519e717acf00dd5', '0.00', '0.00', '4.00', '4.00', '0.00', '0.00', '0.00'),
('8d08b1fbb4ae93bba9a0fd555bc03f7b', '0.00', '0.00', '2.00', '2.00', '0.00', '0.00', '0.00'),
('bff35b16a0d2ed160406a5e1d3415822', '0.00', '0.00', '1.00', '0.00', '0.00', '0.00', '1.00'),
('daaa19ec942c9924f7bd9053cd9258e3', '0.00', '0.00', '3.00', '3.00', '0.00', '0.00', '0.00');

--
-- Contenu de la table `provider`
--

INSERT INTO `provider` (`id`, `name`, `info`, `comment`) VALUES
(4, 'Provider 1', '(r.a.s.)', '(r.a.s.)');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
