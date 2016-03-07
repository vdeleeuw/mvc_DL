-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le : Dim 29 Mars 2015 à 20:44
-- Version du serveur: 5.5.41
-- Version de PHP: 5.3.10-1ubuntu3.16

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `jdvdbd`
--

-- --------------------------------------------------------

--
-- Structure de la table `command`
--

DROP TABLE IF EXISTS `command`;
CREATE TABLE IF NOT EXISTS `command` (
  `co_id` int(11) NOT NULL AUTO_INCREMENT,
  `co_date` date NOT NULL,
  `co_cu_id` int(11) NOT NULL,
  `co_total_price` float NOT NULL,
  `co_nb_cmdlines` int(11) NOT NULL,
  PRIMARY KEY (`co_id`),
  KEY `co_date` (`co_date`,`co_cu_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `command`
--

INSERT INTO `command` (`co_id`, `co_date`, `co_cu_id`, `co_total_price`, `co_nb_cmdlines`) VALUES
(1, '2010-01-01', 2, 140, 3);

-- --------------------------------------------------------

--
-- Structure de la table `commprod`
--

DROP TABLE IF EXISTS `commprod`;
CREATE TABLE IF NOT EXISTS `commprod` (
  `cp_id` int(11) NOT NULL AUTO_INCREMENT,
  `cp_co_id` int(11) NOT NULL,
  `cp_pr_id` int(11) NOT NULL,
  `cp_qty` int(11) NOT NULL,
  PRIMARY KEY (`cp_id`),
  KEY `cp_co_id` (`cp_co_id`,`cp_pr_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `commprod`
--

INSERT INTO `commprod` (`cp_id`, `cp_co_id`, `cp_pr_id`, `cp_qty`) VALUES
(1, 1, 1, 1),
(2, 1, 2, 2),
(3, 1, 3, 3);

-- --------------------------------------------------------

--
-- Structure de la table `customer`
--

DROP TABLE IF EXISTS `customer`;
CREATE TABLE IF NOT EXISTS `customer` (
  `cu_id` int(11) NOT NULL AUTO_INCREMENT,
  `cu_first_name` varchar(30) NOT NULL,
  `cu_last_name` varchar(30) NOT NULL,
  `cu_email` varchar(60) NOT NULL,
  `cu_password` varchar(32) NOT NULL,
  `cu_level` int(11) NOT NULL,
  PRIMARY KEY (`cu_id`),
  KEY `cu_last_name` (`cu_last_name`,`cu_email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `customer`
--

INSERT INTO `customer` (`cu_id`, `cu_first_name`, `cu_last_name`, `cu_email`, `cu_password`, `cu_level`) VALUES
(1, 'admin', 'istrator', 'admin@commands.fr', '21232f297a57a5a743894a0e4a801fc3', 4),
(2, 'simple', 'user', 'user@commands.fr', 'ee11cbb19052e40b07aac0ca060c23ee', 1);

-- --------------------------------------------------------

--
-- Structure de la table `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `pr_id` int(11) NOT NULL AUTO_INCREMENT,
  `pr_label` varchar(80) NOT NULL,
  `pr_stock_qty` int(11) NOT NULL,
  `pr_unit_price` float NOT NULL,
  PRIMARY KEY (`pr_id`),
  KEY `pr_label` (`pr_label`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `product`
--

INSERT INTO `product` (`pr_id`, `pr_label`, `pr_stock_qty`, `pr_unit_price`) VALUES
(1, 'product 1', 10, 10),
(2, 'product 2', 20, 20),
(3, 'product 3', 30, 30),
(4, 'Product 4', 40, 40);
