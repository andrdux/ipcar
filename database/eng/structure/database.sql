CREATE DATABASE  IF NOT EXISTS `ipcar` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `ipcar`;
-- MySQL dump 10.13  Distrib 5.6.13, for Win32 (x86)
--
-- Host: localhost    Database: ipcar
-- ------------------------------------------------------
-- Server version	5.6.17

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `auto`
--

DROP TABLE IF EXISTS `auto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_mark` int(11) NOT NULL,
  `id_model` int(11) NOT NULL,
  `modification` varchar(55) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `autostate_id` int(11) NOT NULL,
  `carcase_id` int(11) NOT NULL,
  `color_id` int(11) NOT NULL,
  `fuel_id` int(11) NOT NULL,
  `fuelsupply_id` int(11) NOT NULL,
  `kpp_id` int(11) NOT NULL,
  `transmission_id` int(11) NOT NULL,
  `country_id` char(3) NOT NULL,
  `region_id` char(20) NOT NULL,
  `city_id` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `mileage` int(11) NOT NULL,
  `volume` int(11) NOT NULL,
  `power` int(11) DEFAULT NULL,
  `consumption` int(11) DEFAULT NULL,
  `acceleration` int(11) DEFAULT NULL,
  `cylinders` varchar(55) DEFAULT NULL,
  `description` text,
  `exchange` tinyint(1) NOT NULL,
  `tuning` tinyint(1) NOT NULL,
  `tuningdesc` text,
  `notcustoms` tinyint(1) NOT NULL,
  `urgent` tinyint(1) NOT NULL,
  `sold` tinyint(1) NOT NULL,
  `updated` datetime NOT NULL,
  `approved` tinyint(1) NOT NULL,
  `premiumstatus` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_mark` (`id_mark`),
  KEY `id_model` (`id_model`),
  KEY `user_id` (`user_id`),
  KEY `autostate_id` (`autostate_id`),
  KEY `carcase_id` (`carcase_id`),
  KEY `color_id` (`color_id`),
  KEY `fuel_id` (`fuel_id`),
  KEY `fuelsupply_id` (`fuelsupply_id`),
  KEY `kpp_id` (`kpp_id`),
  KEY `transmission_id` (`transmission_id`),
  KEY `city_id` (`city_id`),
  KEY `price` (`price`),
  KEY `year` (`year`),
  KEY `volume` (`volume`),
  KEY `country_id` (`country_id`),
  KEY `region_id` (`region_id`),
  KEY `updated` (`updated`),
  KEY `premiumstatus` (`premiumstatus`)
) ENGINE=MyISAM AUTO_INCREMENT=1149 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `auto_equipment`
--

DROP TABLE IF EXISTS `auto_equipment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auto_equipment` (
  `auto_id` int(11) NOT NULL,
  `equipment_id` int(11) NOT NULL,
  UNIQUE KEY `auto_equipment_id` (`auto_id`,`equipment_id`),
  KEY `auto_id` (`auto_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `automark`
--

DROP TABLE IF EXISTS `automark`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `automark` (
  `id_mark` int(11) NOT NULL DEFAULT '0',
  `mark_name` varchar(100) NOT NULL,
  `mark_logo` text,
  `name` text NOT NULL,
  `adress` text NOT NULL,
  `info` text NOT NULL,
  PRIMARY KEY (`id_mark`),
  UNIQUE KEY `mark_name` (`mark_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `automodel`
--

DROP TABLE IF EXISTS `automodel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `automodel` (
  `id_model` int(11) NOT NULL DEFAULT '0',
  `id_mark` int(11) NOT NULL DEFAULT '0',
  `model_name` varchar(100) NOT NULL,
  `info` text,
  PRIMARY KEY (`id_model`),
  KEY `model_name` (`model_name`),
  KEY `id_mark` (`id_mark`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `automodif`
--

DROP TABLE IF EXISTS `automodif`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `automodif` (
  `id_modif` int(11) NOT NULL DEFAULT '0',
  `id_model` int(11) NOT NULL DEFAULT '0',
  `id_photo` int(11) NOT NULL DEFAULT '0',
  `id_mark` int(11) NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL,
  `details` text NOT NULL,
  `volume` varchar(5) NOT NULL DEFAULT '',
  `nmbrdoors` text NOT NULL,
  `power` int(11) DEFAULT NULL,
  `tank` text NOT NULL,
  `time` text NOT NULL,
  `maxspeed` int(11) DEFAULT NULL,
  `start` text NOT NULL,
  `stop` text NOT NULL,
  PRIMARY KEY (`id_modif`),
  KEY `id_model` (`id_model`),
  KEY `id_mark` (`id_mark`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `autostate`
--

DROP TABLE IF EXISTS `autostate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `autostate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `name` varchar(55) NOT NULL,
  `index` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `displayindex` (`index`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `carcase`
--

DROP TABLE IF EXISTS `carcase`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `carcase` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `name` varchar(55) NOT NULL,
  `index` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `displayindex` (`index`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `color`
--

DROP TABLE IF EXISTS `color`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `color` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(55) NOT NULL,
  `index` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `displayindex` (`index`),
  KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `equipment`
--

DROP TABLE IF EXISTS `equipment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `equipment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(55) NOT NULL,
  `index` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `displayindex` (`index`)
) ENGINE=MyISAM AUTO_INCREMENT=53 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `fuel`
--

DROP TABLE IF EXISTS `fuel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fuel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id1` int(11) NOT NULL,
  `parent_id2` int(11) NOT NULL,
  `name` varchar(55) NOT NULL,
  `index` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `displayindex` (`index`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `fuelsupply`
--

DROP TABLE IF EXISTS `fuelsupply`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fuelsupply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `name` varchar(55) NOT NULL,
  `index` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `displayindex` (`index`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `kpp`
--

DROP TABLE IF EXISTS `kpp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kpp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `name` varchar(55) NOT NULL,
  `index` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `displayindex` (`index`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `photo`
--

DROP TABLE IF EXISTS `photo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `photo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `auto_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `nameoriginal` varchar(100) NOT NULL,
  `pathtodir` varchar(255) NOT NULL,
  `index` int(11) NOT NULL,
  `default` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `auto_id` (`auto_id`),
  KEY `display_index` (`index`)
) ENGINE=MyISAM AUTO_INCREMENT=4017 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `transmission`
--

DROP TABLE IF EXISTS `transmission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transmission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(55) NOT NULL,
  `index` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `displayindex` (`index`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fio` varchar(100) NOT NULL,
  `email` varchar(55) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone1` varchar(55) NOT NULL,
  `phone2` varchar(55) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `role` int(11) NOT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `email_password` (`email`,`password`)
) ENGINE=MyISAM AUTO_INCREMENT=1092 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `country`
--

DROP TABLE IF EXISTS `country`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `country` (
  `code` char(3) NOT NULL,
  `name` varchar(55) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`code`),
  UNIQUE KEY `name` (`name`),
  KEY `active` (`active`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `city`
--

DROP TABLE IF EXISTS `city`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `city` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `countrycode` char(3) NOT NULL,
  `district` char(20) NOT NULL,
  `name` varchar(55) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `region_id` (`district`),
  KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=4080 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Dumping events for database 'ipcar'
--

--
-- Dumping routines for database 'ipcar'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-06-17  2:02:02
