CREATE DATABASE  IF NOT EXISTS `itop_2_4_1` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;
USE `itop_2_4_1`;
-- MySQL dump 10.13  Distrib 5.7.21, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: itop_2_4_1
-- ------------------------------------------------------
-- Server version	5.7.21-log

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
-- Table structure for table `applicationsolution`
--

DROP TABLE IF EXISTS `applicationsolution`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `applicationsolution` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` enum('active','inactive') COLLATE utf8_unicode_ci DEFAULT 'active',
  `redundancy` varchar(20) COLLATE utf8_unicode_ci DEFAULT 'disabled',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `applicationsolution`
--

LOCK TABLES `applicationsolution` WRITE;
/*!40000 ALTER TABLE `applicationsolution` DISABLE KEYS */;
/*!40000 ALTER TABLE `applicationsolution` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attachment`
--

DROP TABLE IF EXISTS `attachment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attachment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `expire` datetime DEFAULT NULL,
  `temp_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `item_class` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `item_id` int(11) DEFAULT '0',
  `item_org_id` int(11) DEFAULT '0',
  `contents_data` longblob,
  `contents_mimetype` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contents_filename` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `temp_id` (`temp_id`),
  KEY `item_class_item_id` (`item_class`,`item_id`),
  KEY `item_org_id` (`item_org_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attachment`
--

LOCK TABLES `attachment` WRITE;
/*!40000 ALTER TABLE `attachment` DISABLE KEYS */;
/*!40000 ALTER TABLE `attachment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `brand`
--

DROP TABLE IF EXISTS `brand`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brand` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `brand`
--

LOCK TABLES `brand` WRITE;
/*!40000 ALTER TABLE `brand` DISABLE KEYS */;
/*!40000 ALTER TABLE `brand` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `businessprocess`
--

DROP TABLE IF EXISTS `businessprocess`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `businessprocess` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` enum('active','inactive') COLLATE utf8_unicode_ci DEFAULT 'active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `businessprocess`
--

LOCK TABLES `businessprocess` WRITE;
/*!40000 ALTER TABLE `businessprocess` DISABLE KEYS */;
/*!40000 ALTER TABLE `businessprocess` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `change`
--

DROP TABLE IF EXISTS `change`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `change` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` enum('approved','assigned','closed','implemented','monitored','new','notapproved','plannedscheduled','rejected','validated') COLLATE utf8_unicode_ci DEFAULT 'new',
  `reason` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `requestor_id` int(11) DEFAULT '0',
  `creation_date` datetime DEFAULT NULL,
  `impact` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `supervisor_group_id` int(11) DEFAULT '0',
  `supervisor_id` int(11) DEFAULT '0',
  `manager_group_id` int(11) DEFAULT '0',
  `manager_id` int(11) DEFAULT '0',
  `outage` enum('no','yes') COLLATE utf8_unicode_ci DEFAULT 'no',
  `fallback` text COLLATE utf8_unicode_ci,
  `parent_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `requestor_id` (`requestor_id`),
  KEY `supervisor_group_id` (`supervisor_group_id`),
  KEY `supervisor_id` (`supervisor_id`),
  KEY `manager_group_id` (`manager_group_id`),
  KEY `manager_id` (`manager_id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `change`
--

LOCK TABLES `change` WRITE;
/*!40000 ALTER TABLE `change` DISABLE KEYS */;
/*!40000 ALTER TABLE `change` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `change_approved`
--

DROP TABLE IF EXISTS `change_approved`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `change_approved` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `approval_date` datetime DEFAULT NULL,
  `approval_comment` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `change_approved`
--

LOCK TABLES `change_approved` WRITE;
/*!40000 ALTER TABLE `change_approved` DISABLE KEYS */;
/*!40000 ALTER TABLE `change_approved` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `change_emergency`
--

DROP TABLE IF EXISTS `change_emergency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `change_emergency` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `change_emergency`
--

LOCK TABLES `change_emergency` WRITE;
/*!40000 ALTER TABLE `change_emergency` DISABLE KEYS */;
/*!40000 ALTER TABLE `change_emergency` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `change_normal`
--

DROP TABLE IF EXISTS `change_normal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `change_normal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `acceptance_date` datetime DEFAULT NULL,
  `acceptance_comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `change_normal`
--

LOCK TABLES `change_normal` WRITE;
/*!40000 ALTER TABLE `change_normal` DISABLE KEYS */;
/*!40000 ALTER TABLE `change_normal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `change_routine`
--

DROP TABLE IF EXISTS `change_routine`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `change_routine` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `change_routine`
--

LOCK TABLES `change_routine` WRITE;
/*!40000 ALTER TABLE `change_routine` DISABLE KEYS */;
/*!40000 ALTER TABLE `change_routine` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `connectableci`
--

DROP TABLE IF EXISTS `connectableci`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `connectableci` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `connectableci`
--

LOCK TABLES `connectableci` WRITE;
/*!40000 ALTER TABLE `connectableci` DISABLE KEYS */;
/*!40000 ALTER TABLE `connectableci` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact`
--

DROP TABLE IF EXISTS `contact`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `status` enum('active','inactive') COLLATE utf8_unicode_ci DEFAULT 'active',
  `org_id` int(11) DEFAULT '0',
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `notify` enum('no','yes') COLLATE utf8_unicode_ci DEFAULT 'yes',
  `function` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `finalclass` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'Contact',
  `obsolescence_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `org_id` (`org_id`),
  KEY `finalclass` (`finalclass`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact`
--

LOCK TABLES `contact` WRITE;
/*!40000 ALTER TABLE `contact` DISABLE KEYS */;
INSERT INTO `contact` VALUES (1,'My last name','active',1,'my.email@foo.org','+00 000 000 000','yes','','Person',NULL);
/*!40000 ALTER TABLE `contact` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contacttype`
--

DROP TABLE IF EXISTS `contacttype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contacttype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contacttype`
--

LOCK TABLES `contacttype` WRITE;
/*!40000 ALTER TABLE `contacttype` DISABLE KEYS */;
/*!40000 ALTER TABLE `contacttype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contract`
--

DROP TABLE IF EXISTS `contract`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contract` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `org_id` int(11) DEFAULT '0',
  `description` text COLLATE utf8_unicode_ci,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `cost` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `cost_currency` enum('dollars','euros') COLLATE utf8_unicode_ci DEFAULT NULL,
  `contracttype_id` int(11) DEFAULT '0',
  `billing_frequency` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `cost_unit` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `provider_id` int(11) DEFAULT '0',
  `status` enum('implementation','obsolete','production') COLLATE utf8_unicode_ci DEFAULT NULL,
  `finalclass` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'Contract',
  PRIMARY KEY (`id`),
  KEY `org_id` (`org_id`),
  KEY `contracttype_id` (`contracttype_id`),
  KEY `provider_id` (`provider_id`),
  KEY `finalclass` (`finalclass`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contract`
--

LOCK TABLES `contract` WRITE;
/*!40000 ALTER TABLE `contract` DISABLE KEYS */;
/*!40000 ALTER TABLE `contract` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contracttype`
--

DROP TABLE IF EXISTS `contracttype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contracttype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contracttype`
--

LOCK TABLES `contracttype` WRITE;
/*!40000 ALTER TABLE `contracttype` DISABLE KEYS */;
/*!40000 ALTER TABLE `contracttype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customercontract`
--

DROP TABLE IF EXISTS `customercontract`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customercontract` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customercontract`
--

LOCK TABLES `customercontract` WRITE;
/*!40000 ALTER TABLE `customercontract` DISABLE KEYS */;
/*!40000 ALTER TABLE `customercontract` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `databaseschema`
--

DROP TABLE IF EXISTS `databaseschema`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `databaseschema` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dbserver_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `dbserver_id` (`dbserver_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `databaseschema`
--

LOCK TABLES `databaseschema` WRITE;
/*!40000 ALTER TABLE `databaseschema` DISABLE KEYS */;
/*!40000 ALTER TABLE `databaseschema` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `datacenterdevice`
--

DROP TABLE IF EXISTS `datacenterdevice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `datacenterdevice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rack_id` int(11) DEFAULT '0',
  `enclosure_id` int(11) DEFAULT '0',
  `nb_u` int(11) DEFAULT NULL,
  `managementip` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `powera_id` int(11) DEFAULT '0',
  `powerB_id` int(11) DEFAULT '0',
  `redundancy` varchar(20) COLLATE utf8_unicode_ci DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `rack_id` (`rack_id`),
  KEY `enclosure_id` (`enclosure_id`),
  KEY `powera_id` (`powera_id`),
  KEY `powerB_id` (`powerB_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `datacenterdevice`
--

LOCK TABLES `datacenterdevice` WRITE;
/*!40000 ALTER TABLE `datacenterdevice` DISABLE KEYS */;
/*!40000 ALTER TABLE `datacenterdevice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dbserver`
--

DROP TABLE IF EXISTS `dbserver`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dbserver` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dbserver`
--

LOCK TABLES `dbserver` WRITE;
/*!40000 ALTER TABLE `dbserver` DISABLE KEYS */;
/*!40000 ALTER TABLE `dbserver` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `deliverymodel`
--

DROP TABLE IF EXISTS `deliverymodel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `deliverymodel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `org_id` int(11) DEFAULT '0',
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `org_id` (`org_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `deliverymodel`
--

LOCK TABLES `deliverymodel` WRITE;
/*!40000 ALTER TABLE `deliverymodel` DISABLE KEYS */;
/*!40000 ALTER TABLE `deliverymodel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `document`
--

DROP TABLE IF EXISTS `document`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `document` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `org_id` int(11) DEFAULT '0',
  `documenttype_id` int(11) DEFAULT '0',
  `version` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `description` text COLLATE utf8_unicode_ci,
  `status` enum('draft','obsolete','published') COLLATE utf8_unicode_ci DEFAULT NULL,
  `finalclass` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'Document',
  `obsolescence_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `org_id` (`org_id`),
  KEY `documenttype_id` (`documenttype_id`),
  KEY `finalclass` (`finalclass`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `document`
--

LOCK TABLES `document` WRITE;
/*!40000 ALTER TABLE `document` DISABLE KEYS */;
/*!40000 ALTER TABLE `document` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documentfile`
--

DROP TABLE IF EXISTS `documentfile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `documentfile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_data` longblob,
  `file_mimetype` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `file_filename` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documentfile`
--

LOCK TABLES `documentfile` WRITE;
/*!40000 ALTER TABLE `documentfile` DISABLE KEYS */;
/*!40000 ALTER TABLE `documentfile` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documentnote`
--

DROP TABLE IF EXISTS `documentnote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `documentnote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documentnote`
--

LOCK TABLES `documentnote` WRITE;
/*!40000 ALTER TABLE `documentnote` DISABLE KEYS */;
/*!40000 ALTER TABLE `documentnote` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documenttype`
--

DROP TABLE IF EXISTS `documenttype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `documenttype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documenttype`
--

LOCK TABLES `documenttype` WRITE;
/*!40000 ALTER TABLE `documenttype` DISABLE KEYS */;
/*!40000 ALTER TABLE `documenttype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documentweb`
--

DROP TABLE IF EXISTS `documentweb`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `documentweb` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(2048) COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documentweb`
--

LOCK TABLES `documentweb` WRITE;
/*!40000 ALTER TABLE `documentweb` DISABLE KEYS */;
/*!40000 ALTER TABLE `documentweb` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `enclosure`
--

DROP TABLE IF EXISTS `enclosure`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `enclosure` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rack_id` int(11) DEFAULT '0',
  `nb_u` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rack_id` (`rack_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `enclosure`
--

LOCK TABLES `enclosure` WRITE;
/*!40000 ALTER TABLE `enclosure` DISABLE KEYS */;
/*!40000 ALTER TABLE `enclosure` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faq`
--

DROP TABLE IF EXISTS `faq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `faq` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `summary` text COLLATE utf8_unicode_ci,
  `description` longtext COLLATE utf8_unicode_ci,
  `category_id` int(11) DEFAULT '0',
  `error_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `key_words` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faq`
--

LOCK TABLES `faq` WRITE;
/*!40000 ALTER TABLE `faq` DISABLE KEYS */;
/*!40000 ALTER TABLE `faq` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faqcategory`
--

DROP TABLE IF EXISTS `faqcategory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `faqcategory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nam` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faqcategory`
--

LOCK TABLES `faqcategory` WRITE;
/*!40000 ALTER TABLE `faqcategory` DISABLE KEYS */;
/*!40000 ALTER TABLE `faqcategory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `farm`
--

DROP TABLE IF EXISTS `farm`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `farm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `redundancy` varchar(20) COLLATE utf8_unicode_ci DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `farm`
--

LOCK TABLES `farm` WRITE;
/*!40000 ALTER TABLE `farm` DISABLE KEYS */;
/*!40000 ALTER TABLE `farm` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fiberchannelinterface`
--

DROP TABLE IF EXISTS `fiberchannelinterface`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fiberchannelinterface` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `speed` decimal(6,2) DEFAULT NULL,
  `topology` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `wwn` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `datacenterdevice_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `datacenterdevice_id` (`datacenterdevice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fiberchannelinterface`
--

LOCK TABLES `fiberchannelinterface` WRITE;
/*!40000 ALTER TABLE `fiberchannelinterface` DISABLE KEYS */;
/*!40000 ALTER TABLE `fiberchannelinterface` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `functionalci`
--

DROP TABLE IF EXISTS `functionalci`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `functionalci` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `description` text COLLATE utf8_unicode_ci,
  `org_id` int(11) DEFAULT '0',
  `business_criticity` enum('high','low','medium') COLLATE utf8_unicode_ci DEFAULT 'low',
  `move2production` date DEFAULT NULL,
  `finalclass` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'FunctionalCI',
  `obsolescence_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `org_id` (`org_id`),
  KEY `finalclass` (`finalclass`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `functionalci`
--

LOCK TABLES `functionalci` WRITE;
/*!40000 ALTER TABLE `functionalci` DISABLE KEYS */;
/*!40000 ALTER TABLE `functionalci` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `group`
--

DROP TABLE IF EXISTS `group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `status` enum('implementation','obsolete','production') COLLATE utf8_unicode_ci DEFAULT 'implementation',
  `org_id` int(11) DEFAULT '0',
  `description` text COLLATE utf8_unicode_ci,
  `type` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `parent_id` int(11) DEFAULT '0',
  `parent_id_left` int(11) DEFAULT '0',
  `parent_id_right` int(11) DEFAULT '0',
  `obsolescence_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `org_id` (`org_id`),
  KEY `parent_id` (`parent_id`),
  KEY `parent_id_left` (`parent_id_left`),
  KEY `parent_id_right` (`parent_id_right`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `group`
--

LOCK TABLES `group` WRITE;
/*!40000 ALTER TABLE `group` DISABLE KEYS */;
/*!40000 ALTER TABLE `group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hypervisor`
--

DROP TABLE IF EXISTS `hypervisor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hypervisor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `farm_id` int(11) DEFAULT '0',
  `server_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `farm_id` (`farm_id`),
  KEY `server_id` (`server_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hypervisor`
--

LOCK TABLES `hypervisor` WRITE;
/*!40000 ALTER TABLE `hypervisor` DISABLE KEYS */;
/*!40000 ALTER TABLE `hypervisor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inline_image`
--

DROP TABLE IF EXISTS `inline_image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inline_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `expire` datetime DEFAULT NULL,
  `temp_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `item_class` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `item_id` int(11) DEFAULT '0',
  `item_org_id` int(11) DEFAULT '0',
  `contents_data` longblob,
  `contents_mimetype` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contents_filename` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `secret` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `temp_id` (`temp_id`),
  KEY `item_class_item_id` (`item_class`,`item_id`),
  KEY `item_org_id` (`item_org_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inline_image`
--

LOCK TABLES `inline_image` WRITE;
/*!40000 ALTER TABLE `inline_image` DISABLE KEYS */;
/*!40000 ALTER TABLE `inline_image` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `iosversion`
--

DROP TABLE IF EXISTS `iosversion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `iosversion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `brand_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `brand_id` (`brand_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `iosversion`
--

LOCK TABLES `iosversion` WRITE;
/*!40000 ALTER TABLE `iosversion` DISABLE KEYS */;
/*!40000 ALTER TABLE `iosversion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ipinterface`
--

DROP TABLE IF EXISTS `ipinterface`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ipinterface` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ipaddress` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `macaddress` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `comment` text COLLATE utf8_unicode_ci,
  `ipgateway` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `ipmask` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `speed` decimal(12,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ipinterface`
--

LOCK TABLES `ipinterface` WRITE;
/*!40000 ALTER TABLE `ipinterface` DISABLE KEYS */;
/*!40000 ALTER TABLE `ipinterface` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ipphone`
--

DROP TABLE IF EXISTS `ipphone`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ipphone` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ipphone`
--

LOCK TABLES `ipphone` WRITE;
/*!40000 ALTER TABLE `ipphone` DISABLE KEYS */;
/*!40000 ALTER TABLE `ipphone` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `knownerror`
--

DROP TABLE IF EXISTS `knownerror`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `knownerror` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `cust_id` int(11) DEFAULT '0',
  `problem_id` int(11) DEFAULT '0',
  `symptom` text COLLATE utf8_unicode_ci,
  `rootcause` text COLLATE utf8_unicode_ci,
  `workaround` text COLLATE utf8_unicode_ci,
  `solution` text COLLATE utf8_unicode_ci,
  `error_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `domain` enum('Application','Desktop','Network','Server') COLLATE utf8_unicode_ci DEFAULT 'Application',
  `vendor` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `model` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `version` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `cust_id` (`cust_id`),
  KEY `problem_id` (`problem_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `knownerror`
--

LOCK TABLES `knownerror` WRITE;
/*!40000 ALTER TABLE `knownerror` DISABLE KEYS */;
/*!40000 ALTER TABLE `knownerror` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `licence`
--

DROP TABLE IF EXISTS `licence`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `licence` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `org_id` int(11) DEFAULT '0',
  `usage_limit` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `description` text COLLATE utf8_unicode_ci,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `licence_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `perpetual` enum('no','yes') COLLATE utf8_unicode_ci DEFAULT 'no',
  `finalclass` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'Licence',
  `obsolescence_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `org_id` (`org_id`),
  KEY `finalclass` (`finalclass`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `licence`
--

LOCK TABLES `licence` WRITE;
/*!40000 ALTER TABLE `licence` DISABLE KEYS */;
/*!40000 ALTER TABLE `licence` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lnkapplicationsolutiontobusinessprocess`
--

DROP TABLE IF EXISTS `lnkapplicationsolutiontobusinessprocess`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lnkapplicationsolutiontobusinessprocess` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `businessprocess_id` int(11) DEFAULT '0',
  `applicationsolution_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `businessprocess_id` (`businessprocess_id`),
  KEY `applicationsolution_id` (`applicationsolution_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lnkapplicationsolutiontobusinessprocess`
--

LOCK TABLES `lnkapplicationsolutiontobusinessprocess` WRITE;
/*!40000 ALTER TABLE `lnkapplicationsolutiontobusinessprocess` DISABLE KEYS */;
/*!40000 ALTER TABLE `lnkapplicationsolutiontobusinessprocess` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lnkapplicationsolutiontofunctionalci`
--

DROP TABLE IF EXISTS `lnkapplicationsolutiontofunctionalci`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lnkapplicationsolutiontofunctionalci` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `applicationsolution_id` int(11) DEFAULT '0',
  `functionalci_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `applicationsolution_id` (`applicationsolution_id`),
  KEY `functionalci_id` (`functionalci_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lnkapplicationsolutiontofunctionalci`
--

LOCK TABLES `lnkapplicationsolutiontofunctionalci` WRITE;
/*!40000 ALTER TABLE `lnkapplicationsolutiontofunctionalci` DISABLE KEYS */;
/*!40000 ALTER TABLE `lnkapplicationsolutiontofunctionalci` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lnkconnectablecitonetworkdevice`
--

DROP TABLE IF EXISTS `lnkconnectablecitonetworkdevice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lnkconnectablecitonetworkdevice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `networkdevice_id` int(11) DEFAULT '0',
  `connectableci_id` int(11) DEFAULT '0',
  `network_port` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `device_port` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `type` enum('downlink','uplink') COLLATE utf8_unicode_ci DEFAULT 'downlink',
  PRIMARY KEY (`id`),
  KEY `networkdevice_id` (`networkdevice_id`),
  KEY `connectableci_id` (`connectableci_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lnkconnectablecitonetworkdevice`
--

LOCK TABLES `lnkconnectablecitonetworkdevice` WRITE;
/*!40000 ALTER TABLE `lnkconnectablecitonetworkdevice` DISABLE KEYS */;
/*!40000 ALTER TABLE `lnkconnectablecitonetworkdevice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lnkcontacttocontract`
--

DROP TABLE IF EXISTS `lnkcontacttocontract`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lnkcontacttocontract` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_id` int(11) DEFAULT '0',
  `contact_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `contract_id` (`contract_id`),
  KEY `contact_id` (`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lnkcontacttocontract`
--

LOCK TABLES `lnkcontacttocontract` WRITE;
/*!40000 ALTER TABLE `lnkcontacttocontract` DISABLE KEYS */;
/*!40000 ALTER TABLE `lnkcontacttocontract` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lnkcontacttofunctionalci`
--

DROP TABLE IF EXISTS `lnkcontacttofunctionalci`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lnkcontacttofunctionalci` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `functionalci_id` int(11) DEFAULT '0',
  `contact_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `functionalci_id` (`functionalci_id`),
  KEY `contact_id` (`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lnkcontacttofunctionalci`
--

LOCK TABLES `lnkcontacttofunctionalci` WRITE;
/*!40000 ALTER TABLE `lnkcontacttofunctionalci` DISABLE KEYS */;
/*!40000 ALTER TABLE `lnkcontacttofunctionalci` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lnkcontacttoservice`
--

DROP TABLE IF EXISTS `lnkcontacttoservice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lnkcontacttoservice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service_id` int(11) DEFAULT '0',
  `contact_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `service_id` (`service_id`),
  KEY `contact_id` (`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lnkcontacttoservice`
--

LOCK TABLES `lnkcontacttoservice` WRITE;
/*!40000 ALTER TABLE `lnkcontacttoservice` DISABLE KEYS */;
/*!40000 ALTER TABLE `lnkcontacttoservice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lnkcontacttoticket`
--

DROP TABLE IF EXISTS `lnkcontacttoticket`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lnkcontacttoticket` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_id` int(11) DEFAULT '0',
  `contact_id` int(11) DEFAULT '0',
  `role` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `impact_code` enum('computed','do_not_notify','manual') COLLATE utf8_unicode_ci DEFAULT 'manual',
  PRIMARY KEY (`id`),
  KEY `ticket_id` (`ticket_id`),
  KEY `contact_id` (`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lnkcontacttoticket`
--

LOCK TABLES `lnkcontacttoticket` WRITE;
/*!40000 ALTER TABLE `lnkcontacttoticket` DISABLE KEYS */;
/*!40000 ALTER TABLE `lnkcontacttoticket` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lnkcontracttodocument`
--

DROP TABLE IF EXISTS `lnkcontracttodocument`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lnkcontracttodocument` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contract_id` int(11) DEFAULT '0',
  `document_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `contract_id` (`contract_id`),
  KEY `document_id` (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lnkcontracttodocument`
--

LOCK TABLES `lnkcontracttodocument` WRITE;
/*!40000 ALTER TABLE `lnkcontracttodocument` DISABLE KEYS */;
/*!40000 ALTER TABLE `lnkcontracttodocument` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lnkcustomercontracttofunctionalci`
--

DROP TABLE IF EXISTS `lnkcustomercontracttofunctionalci`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lnkcustomercontracttofunctionalci` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customercontract_id` int(11) DEFAULT '0',
  `functionalci_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `customercontract_id` (`customercontract_id`),
  KEY `functionalci_id` (`functionalci_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lnkcustomercontracttofunctionalci`
--

LOCK TABLES `lnkcustomercontracttofunctionalci` WRITE;
/*!40000 ALTER TABLE `lnkcustomercontracttofunctionalci` DISABLE KEYS */;
/*!40000 ALTER TABLE `lnkcustomercontracttofunctionalci` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lnkcustomercontracttoprovidercontract`
--

DROP TABLE IF EXISTS `lnkcustomercontracttoprovidercontract`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lnkcustomercontracttoprovidercontract` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customercontract_id` int(11) DEFAULT '0',
  `providercontract_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `customercontract_id` (`customercontract_id`),
  KEY `providercontract_id` (`providercontract_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lnkcustomercontracttoprovidercontract`
--

LOCK TABLES `lnkcustomercontracttoprovidercontract` WRITE;
/*!40000 ALTER TABLE `lnkcustomercontracttoprovidercontract` DISABLE KEYS */;
/*!40000 ALTER TABLE `lnkcustomercontracttoprovidercontract` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lnkcustomercontracttoservice`
--

DROP TABLE IF EXISTS `lnkcustomercontracttoservice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lnkcustomercontracttoservice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customercontract_id` int(11) DEFAULT '0',
  `service_id` int(11) DEFAULT '0',
  `sla_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `customercontract_id` (`customercontract_id`),
  KEY `service_id` (`service_id`),
  KEY `sla_id` (`sla_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lnkcustomercontracttoservice`
--

LOCK TABLES `lnkcustomercontracttoservice` WRITE;
/*!40000 ALTER TABLE `lnkcustomercontracttoservice` DISABLE KEYS */;
/*!40000 ALTER TABLE `lnkcustomercontracttoservice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lnkdatacenterdevicetosan`
--

DROP TABLE IF EXISTS `lnkdatacenterdevicetosan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lnkdatacenterdevicetosan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `san_id` int(11) DEFAULT '0',
  `datacenterdevice_id` int(11) DEFAULT '0',
  `san_port` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `datacenterdevice_port` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `san_id` (`san_id`),
  KEY `datacenterdevice_id` (`datacenterdevice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lnkdatacenterdevicetosan`
--

LOCK TABLES `lnkdatacenterdevicetosan` WRITE;
/*!40000 ALTER TABLE `lnkdatacenterdevicetosan` DISABLE KEYS */;
/*!40000 ALTER TABLE `lnkdatacenterdevicetosan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lnkdeliverymodeltocontact`
--

DROP TABLE IF EXISTS `lnkdeliverymodeltocontact`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lnkdeliverymodeltocontact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deliverymodel_id` int(11) DEFAULT '0',
  `contact_id` int(11) DEFAULT '0',
  `role_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `deliverymodel_id` (`deliverymodel_id`),
  KEY `contact_id` (`contact_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lnkdeliverymodeltocontact`
--

LOCK TABLES `lnkdeliverymodeltocontact` WRITE;
/*!40000 ALTER TABLE `lnkdeliverymodeltocontact` DISABLE KEYS */;
/*!40000 ALTER TABLE `lnkdeliverymodeltocontact` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lnkdocumenttoerror`
--

DROP TABLE IF EXISTS `lnkdocumenttoerror`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lnkdocumenttoerror` (
  `link_id` int(11) NOT NULL AUTO_INCREMENT,
  `document_id` int(11) DEFAULT '0',
  `error_id` int(11) DEFAULT '0',
  `link_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`link_id`),
  KEY `document_id` (`document_id`),
  KEY `error_id` (`error_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lnkdocumenttoerror`
--

LOCK TABLES `lnkdocumenttoerror` WRITE;
/*!40000 ALTER TABLE `lnkdocumenttoerror` DISABLE KEYS */;
/*!40000 ALTER TABLE `lnkdocumenttoerror` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lnkdocumenttofunctionalci`
--

DROP TABLE IF EXISTS `lnkdocumenttofunctionalci`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lnkdocumenttofunctionalci` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `functionalci_id` int(11) DEFAULT '0',
  `document_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `functionalci_id` (`functionalci_id`),
  KEY `document_id` (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lnkdocumenttofunctionalci`
--

LOCK TABLES `lnkdocumenttofunctionalci` WRITE;
/*!40000 ALTER TABLE `lnkdocumenttofunctionalci` DISABLE KEYS */;
/*!40000 ALTER TABLE `lnkdocumenttofunctionalci` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lnkdocumenttolicence`
--

DROP TABLE IF EXISTS `lnkdocumenttolicence`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lnkdocumenttolicence` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `licence_id` int(11) DEFAULT '0',
  `document_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `licence_id` (`licence_id`),
  KEY `document_id` (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lnkdocumenttolicence`
--

LOCK TABLES `lnkdocumenttolicence` WRITE;
/*!40000 ALTER TABLE `lnkdocumenttolicence` DISABLE KEYS */;
/*!40000 ALTER TABLE `lnkdocumenttolicence` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lnkdocumenttopatch`
--

DROP TABLE IF EXISTS `lnkdocumenttopatch`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lnkdocumenttopatch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patch_id` int(11) DEFAULT '0',
  `document_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `patch_id` (`patch_id`),
  KEY `document_id` (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lnkdocumenttopatch`
--

LOCK TABLES `lnkdocumenttopatch` WRITE;
/*!40000 ALTER TABLE `lnkdocumenttopatch` DISABLE KEYS */;
/*!40000 ALTER TABLE `lnkdocumenttopatch` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lnkdocumenttoservice`
--

DROP TABLE IF EXISTS `lnkdocumenttoservice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lnkdocumenttoservice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service_id` int(11) DEFAULT '0',
  `document_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `service_id` (`service_id`),
  KEY `document_id` (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lnkdocumenttoservice`
--

LOCK TABLES `lnkdocumenttoservice` WRITE;
/*!40000 ALTER TABLE `lnkdocumenttoservice` DISABLE KEYS */;
/*!40000 ALTER TABLE `lnkdocumenttoservice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lnkdocumenttosoftware`
--

DROP TABLE IF EXISTS `lnkdocumenttosoftware`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lnkdocumenttosoftware` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `software_id` int(11) DEFAULT '0',
  `document_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `software_id` (`software_id`),
  KEY `document_id` (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lnkdocumenttosoftware`
--

LOCK TABLES `lnkdocumenttosoftware` WRITE;
/*!40000 ALTER TABLE `lnkdocumenttosoftware` DISABLE KEYS */;
/*!40000 ALTER TABLE `lnkdocumenttosoftware` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lnkerrortofunctionalci`
--

DROP TABLE IF EXISTS `lnkerrortofunctionalci`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lnkerrortofunctionalci` (
  `link_id` int(11) NOT NULL AUTO_INCREMENT,
  `functionalci_id` int(11) DEFAULT '0',
  `error_id` int(11) DEFAULT '0',
  `dummy` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`link_id`),
  KEY `functionalci_id` (`functionalci_id`),
  KEY `error_id` (`error_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lnkerrortofunctionalci`
--

LOCK TABLES `lnkerrortofunctionalci` WRITE;
/*!40000 ALTER TABLE `lnkerrortofunctionalci` DISABLE KEYS */;
/*!40000 ALTER TABLE `lnkerrortofunctionalci` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lnkfunctionalcitoospatch`
--

DROP TABLE IF EXISTS `lnkfunctionalcitoospatch`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lnkfunctionalcitoospatch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ospatch_id` int(11) DEFAULT '0',
  `functionalci_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ospatch_id` (`ospatch_id`),
  KEY `functionalci_id` (`functionalci_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lnkfunctionalcitoospatch`
--

LOCK TABLES `lnkfunctionalcitoospatch` WRITE;
/*!40000 ALTER TABLE `lnkfunctionalcitoospatch` DISABLE KEYS */;
/*!40000 ALTER TABLE `lnkfunctionalcitoospatch` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lnkfunctionalcitoprovidercontract`
--

DROP TABLE IF EXISTS `lnkfunctionalcitoprovidercontract`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lnkfunctionalcitoprovidercontract` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `providercontract_id` int(11) DEFAULT '0',
  `functionalci_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `providercontract_id` (`providercontract_id`),
  KEY `functionalci_id` (`functionalci_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lnkfunctionalcitoprovidercontract`
--

LOCK TABLES `lnkfunctionalcitoprovidercontract` WRITE;
/*!40000 ALTER TABLE `lnkfunctionalcitoprovidercontract` DISABLE KEYS */;
/*!40000 ALTER TABLE `lnkfunctionalcitoprovidercontract` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lnkfunctionalcitoticket`
--

DROP TABLE IF EXISTS `lnkfunctionalcitoticket`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lnkfunctionalcitoticket` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_id` int(11) DEFAULT '0',
  `functionalci_id` int(11) DEFAULT '0',
  `impact` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `impact_code` enum('computed','manual','not_impacted') COLLATE utf8_unicode_ci DEFAULT 'manual',
  PRIMARY KEY (`id`),
  KEY `ticket_id` (`ticket_id`),
  KEY `functionalci_id` (`functionalci_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lnkfunctionalcitoticket`
--

LOCK TABLES `lnkfunctionalcitoticket` WRITE;
/*!40000 ALTER TABLE `lnkfunctionalcitoticket` DISABLE KEYS */;
/*!40000 ALTER TABLE `lnkfunctionalcitoticket` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lnkgrouptoci`
--

DROP TABLE IF EXISTS `lnkgrouptoci`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lnkgrouptoci` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) DEFAULT '0',
  `ci_id` int(11) DEFAULT '0',
  `reason` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`),
  KEY `ci_id` (`ci_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lnkgrouptoci`
--

LOCK TABLES `lnkgrouptoci` WRITE;
/*!40000 ALTER TABLE `lnkgrouptoci` DISABLE KEYS */;
/*!40000 ALTER TABLE `lnkgrouptoci` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lnkpersontoteam`
--

DROP TABLE IF EXISTS `lnkpersontoteam`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lnkpersontoteam` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `team_id` int(11) DEFAULT '0',
  `person_id` int(11) DEFAULT '0',
  `role_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `team_id` (`team_id`),
  KEY `person_id` (`person_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lnkpersontoteam`
--

LOCK TABLES `lnkpersontoteam` WRITE;
/*!40000 ALTER TABLE `lnkpersontoteam` DISABLE KEYS */;
/*!40000 ALTER TABLE `lnkpersontoteam` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lnkphysicalinterfacetovlan`
--

DROP TABLE IF EXISTS `lnkphysicalinterfacetovlan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lnkphysicalinterfacetovlan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `physicalinterface_id` int(11) DEFAULT '0',
  `vlan_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `physicalinterface_id` (`physicalinterface_id`),
  KEY `vlan_id` (`vlan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lnkphysicalinterfacetovlan`
--

LOCK TABLES `lnkphysicalinterfacetovlan` WRITE;
/*!40000 ALTER TABLE `lnkphysicalinterfacetovlan` DISABLE KEYS */;
/*!40000 ALTER TABLE `lnkphysicalinterfacetovlan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lnkservertovolume`
--

DROP TABLE IF EXISTS `lnkservertovolume`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lnkservertovolume` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `volume_id` int(11) DEFAULT '0',
  `server_id` int(11) DEFAULT '0',
  `size_used` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `volume_id` (`volume_id`),
  KEY `server_id` (`server_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lnkservertovolume`
--

LOCK TABLES `lnkservertovolume` WRITE;
/*!40000 ALTER TABLE `lnkservertovolume` DISABLE KEYS */;
/*!40000 ALTER TABLE `lnkservertovolume` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lnkslatoslt`
--

DROP TABLE IF EXISTS `lnkslatoslt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lnkslatoslt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sla_id` int(11) DEFAULT '0',
  `slt_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `sla_id` (`sla_id`),
  KEY `slt_id` (`slt_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lnkslatoslt`
--

LOCK TABLES `lnkslatoslt` WRITE;
/*!40000 ALTER TABLE `lnkslatoslt` DISABLE KEYS */;
/*!40000 ALTER TABLE `lnkslatoslt` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lnksoftwareinstancetosoftwarepatch`
--

DROP TABLE IF EXISTS `lnksoftwareinstancetosoftwarepatch`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lnksoftwareinstancetosoftwarepatch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `softwarepatch_id` int(11) DEFAULT '0',
  `softwareinstance_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `softwarepatch_id` (`softwarepatch_id`),
  KEY `softwareinstance_id` (`softwareinstance_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lnksoftwareinstancetosoftwarepatch`
--

LOCK TABLES `lnksoftwareinstancetosoftwarepatch` WRITE;
/*!40000 ALTER TABLE `lnksoftwareinstancetosoftwarepatch` DISABLE KEYS */;
/*!40000 ALTER TABLE `lnksoftwareinstancetosoftwarepatch` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lnksubnettovlan`
--

DROP TABLE IF EXISTS `lnksubnettovlan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lnksubnettovlan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subnet_id` int(11) DEFAULT '0',
  `vlan_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `subnet_id` (`subnet_id`),
  KEY `vlan_id` (`vlan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lnksubnettovlan`
--

LOCK TABLES `lnksubnettovlan` WRITE;
/*!40000 ALTER TABLE `lnksubnettovlan` DISABLE KEYS */;
/*!40000 ALTER TABLE `lnksubnettovlan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lnkvirtualdevicetovolume`
--

DROP TABLE IF EXISTS `lnkvirtualdevicetovolume`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lnkvirtualdevicetovolume` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `volume_id` int(11) DEFAULT '0',
  `virtualdevice_id` int(11) DEFAULT '0',
  `size_used` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `volume_id` (`volume_id`),
  KEY `virtualdevice_id` (`virtualdevice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lnkvirtualdevicetovolume`
--

LOCK TABLES `lnkvirtualdevicetovolume` WRITE;
/*!40000 ALTER TABLE `lnkvirtualdevicetovolume` DISABLE KEYS */;
/*!40000 ALTER TABLE `lnkvirtualdevicetovolume` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `location`
--

DROP TABLE IF EXISTS `location`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `location` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `status` enum('active','inactive') COLLATE utf8_unicode_ci DEFAULT 'active',
  `org_id` int(11) DEFAULT '0',
  `address` text COLLATE utf8_unicode_ci,
  `postal_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `city` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `country` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `obsolescence_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `org_id` (`org_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `location`
--

LOCK TABLES `location` WRITE;
/*!40000 ALTER TABLE `location` DISABLE KEYS */;
/*!40000 ALTER TABLE `location` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logicalinterface`
--

DROP TABLE IF EXISTS `logicalinterface`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logicalinterface` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `virtualmachine_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `virtualmachine_id` (`virtualmachine_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logicalinterface`
--

LOCK TABLES `logicalinterface` WRITE;
/*!40000 ALTER TABLE `logicalinterface` DISABLE KEYS */;
/*!40000 ALTER TABLE `logicalinterface` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logicalvolume`
--

DROP TABLE IF EXISTS `logicalvolume`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logicalvolume` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `lun_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `description` text COLLATE utf8_unicode_ci,
  `raid_level` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `size` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `storagesystem_id` int(11) DEFAULT '0',
  `obsolescence_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `storagesystem_id` (`storagesystem_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logicalvolume`
--

LOCK TABLES `logicalvolume` WRITE;
/*!40000 ALTER TABLE `logicalvolume` DISABLE KEYS */;
/*!40000 ALTER TABLE `logicalvolume` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `middleware`
--

DROP TABLE IF EXISTS `middleware`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `middleware` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `middleware`
--

LOCK TABLES `middleware` WRITE;
/*!40000 ALTER TABLE `middleware` DISABLE KEYS */;
/*!40000 ALTER TABLE `middleware` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `middlewareinstance`
--

DROP TABLE IF EXISTS `middlewareinstance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `middlewareinstance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `middleware_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `middleware_id` (`middleware_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `middlewareinstance`
--

LOCK TABLES `middlewareinstance` WRITE;
/*!40000 ALTER TABLE `middlewareinstance` DISABLE KEYS */;
/*!40000 ALTER TABLE `middlewareinstance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mobilephone`
--

DROP TABLE IF EXISTS `mobilephone`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mobilephone` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `imei` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `hw_pin` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mobilephone`
--

LOCK TABLES `mobilephone` WRITE;
/*!40000 ALTER TABLE `mobilephone` DISABLE KEYS */;
/*!40000 ALTER TABLE `mobilephone` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model`
--

DROP TABLE IF EXISTS `model`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `model` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `brand_id` int(11) DEFAULT '0',
  `type` enum('DiskArray','Enclosure','IPPhone','MobilePhone','NAS','NetworkDevice','PC','PDU','Peripheral','Phone','PowerSource','Printer','Rack','SANSwitch','Server','StorageSystem','Tablet','TapeLibrary') COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `brand_id` (`brand_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model`
--

LOCK TABLES `model` WRITE;
/*!40000 ALTER TABLE `model` DISABLE KEYS */;
/*!40000 ALTER TABLE `model` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `nas`
--

DROP TABLE IF EXISTS `nas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `nas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nas`
--

LOCK TABLES `nas` WRITE;
/*!40000 ALTER TABLE `nas` DISABLE KEYS */;
/*!40000 ALTER TABLE `nas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `nasfilesystem`
--

DROP TABLE IF EXISTS `nasfilesystem`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `nasfilesystem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `description` text COLLATE utf8_unicode_ci,
  `raid_level` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `size` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `nas_id` int(11) DEFAULT '0',
  `obsolescence_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nas_id` (`nas_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nasfilesystem`
--

LOCK TABLES `nasfilesystem` WRITE;
/*!40000 ALTER TABLE `nasfilesystem` DISABLE KEYS */;
/*!40000 ALTER TABLE `nasfilesystem` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `networkdevice`
--

DROP TABLE IF EXISTS `networkdevice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `networkdevice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `networkdevicetype_id` int(11) DEFAULT '0',
  `iosversion_id` int(11) DEFAULT '0',
  `ram` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `networkdevicetype_id` (`networkdevicetype_id`),
  KEY `iosversion_id` (`iosversion_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `networkdevice`
--

LOCK TABLES `networkdevice` WRITE;
/*!40000 ALTER TABLE `networkdevice` DISABLE KEYS */;
/*!40000 ALTER TABLE `networkdevice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `networkdevicetype`
--

DROP TABLE IF EXISTS `networkdevicetype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `networkdevicetype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `networkdevicetype`
--

LOCK TABLES `networkdevicetype` WRITE;
/*!40000 ALTER TABLE `networkdevicetype` DISABLE KEYS */;
/*!40000 ALTER TABLE `networkdevicetype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `networkinterface`
--

DROP TABLE IF EXISTS `networkinterface`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `networkinterface` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `finalclass` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'NetworkInterface',
  `obsolescence_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `finalclass` (`finalclass`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `networkinterface`
--

LOCK TABLES `networkinterface` WRITE;
/*!40000 ALTER TABLE `networkinterface` DISABLE KEYS */;
/*!40000 ALTER TABLE `networkinterface` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `organization`
--

DROP TABLE IF EXISTS `organization`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `organization` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `code` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `status` enum('active','inactive') COLLATE utf8_unicode_ci DEFAULT 'active',
  `parent_id` int(11) DEFAULT '0',
  `parent_id_left` int(11) DEFAULT '0',
  `parent_id_right` int(11) DEFAULT '0',
  `deliverymodel_id` int(11) DEFAULT '0',
  `obsolescence_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `parent_id_left` (`parent_id_left`),
  KEY `parent_id_right` (`parent_id_right`),
  KEY `deliverymodel_id` (`deliverymodel_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `organization`
--

LOCK TABLES `organization` WRITE;
/*!40000 ALTER TABLE `organization` DISABLE KEYS */;
INSERT INTO `organization` VALUES (1,'My Company/Department','SOMECODE','active',0,1,2,0,NULL);
/*!40000 ALTER TABLE `organization` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `osfamily`
--

DROP TABLE IF EXISTS `osfamily`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `osfamily` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `osfamily`
--

LOCK TABLES `osfamily` WRITE;
/*!40000 ALTER TABLE `osfamily` DISABLE KEYS */;
/*!40000 ALTER TABLE `osfamily` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oslicence`
--

DROP TABLE IF EXISTS `oslicence`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `oslicence` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `osversion_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `osversion_id` (`osversion_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oslicence`
--

LOCK TABLES `oslicence` WRITE;
/*!40000 ALTER TABLE `oslicence` DISABLE KEYS */;
/*!40000 ALTER TABLE `oslicence` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ospatch`
--

DROP TABLE IF EXISTS `ospatch`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospatch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `osversion_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `osversion_id` (`osversion_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ospatch`
--

LOCK TABLES `ospatch` WRITE;
/*!40000 ALTER TABLE `ospatch` DISABLE KEYS */;
/*!40000 ALTER TABLE `ospatch` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `osversion`
--

DROP TABLE IF EXISTS `osversion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `osversion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `osfamily_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `osfamily_id` (`osfamily_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `osversion`
--

LOCK TABLES `osversion` WRITE;
/*!40000 ALTER TABLE `osversion` DISABLE KEYS */;
/*!40000 ALTER TABLE `osversion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `othersoftware`
--

DROP TABLE IF EXISTS `othersoftware`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `othersoftware` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `othersoftware`
--

LOCK TABLES `othersoftware` WRITE;
/*!40000 ALTER TABLE `othersoftware` DISABLE KEYS */;
/*!40000 ALTER TABLE `othersoftware` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patch`
--

DROP TABLE IF EXISTS `patch`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `description` text COLLATE utf8_unicode_ci,
  `finalclass` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'Patch',
  PRIMARY KEY (`id`),
  KEY `finalclass` (`finalclass`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patch`
--

LOCK TABLES `patch` WRITE;
/*!40000 ALTER TABLE `patch` DISABLE KEYS */;
/*!40000 ALTER TABLE `patch` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pc`
--

DROP TABLE IF EXISTS `pc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `osfamily_id` int(11) DEFAULT '0',
  `osversion_id` int(11) DEFAULT '0',
  `cpu` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `ram` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `type` enum('desktop','laptop') COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `osfamily_id` (`osfamily_id`),
  KEY `osversion_id` (`osversion_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pc`
--

LOCK TABLES `pc` WRITE;
/*!40000 ALTER TABLE `pc` DISABLE KEYS */;
/*!40000 ALTER TABLE `pc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pcsoftware`
--

DROP TABLE IF EXISTS `pcsoftware`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pcsoftware` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pcsoftware`
--

LOCK TABLES `pcsoftware` WRITE;
/*!40000 ALTER TABLE `pcsoftware` DISABLE KEYS */;
/*!40000 ALTER TABLE `pcsoftware` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pdu`
--

DROP TABLE IF EXISTS `pdu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pdu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rack_id` int(11) DEFAULT '0',
  `powerstart_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `rack_id` (`rack_id`),
  KEY `powerstart_id` (`powerstart_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pdu`
--

LOCK TABLES `pdu` WRITE;
/*!40000 ALTER TABLE `pdu` DISABLE KEYS */;
/*!40000 ALTER TABLE `pdu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `peripheral`
--

DROP TABLE IF EXISTS `peripheral`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `peripheral` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `peripheral`
--

LOCK TABLES `peripheral` WRITE;
/*!40000 ALTER TABLE `peripheral` DISABLE KEYS */;
/*!40000 ALTER TABLE `peripheral` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `person`
--

DROP TABLE IF EXISTS `person`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `person` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `picture_data` longblob,
  `picture_mimetype` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `picture_filename` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `employee_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `mobile_phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `location_id` int(11) DEFAULT '0',
  `manager_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `location_id` (`location_id`),
  KEY `manager_id` (`manager_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `person`
--

LOCK TABLES `person` WRITE;
/*!40000 ALTER TABLE `person` DISABLE KEYS */;
INSERT INTO `person` VALUES (1,'','','','My first name','','',0,0);
/*!40000 ALTER TABLE `person` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `phone`
--

DROP TABLE IF EXISTS `phone`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phone` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `phone`
--

LOCK TABLES `phone` WRITE;
/*!40000 ALTER TABLE `phone` DISABLE KEYS */;
/*!40000 ALTER TABLE `phone` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `physicaldevice`
--

DROP TABLE IF EXISTS `physicaldevice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `physicaldevice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `serialnumber` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `location_id` int(11) DEFAULT '0',
  `status` enum('implementation','obsolete','production','stock') COLLATE utf8_unicode_ci DEFAULT 'production',
  `brand_id` int(11) DEFAULT '0',
  `model_id` int(11) DEFAULT '0',
  `asset_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `purchase_date` date DEFAULT NULL,
  `end_of_warranty` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `location_id` (`location_id`),
  KEY `brand_id` (`brand_id`),
  KEY `model_id` (`model_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `physicaldevice`
--

LOCK TABLES `physicaldevice` WRITE;
/*!40000 ALTER TABLE `physicaldevice` DISABLE KEYS */;
/*!40000 ALTER TABLE `physicaldevice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `physicalinterface`
--

DROP TABLE IF EXISTS `physicalinterface`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `physicalinterface` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `connectableci_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `connectableci_id` (`connectableci_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `physicalinterface`
--

LOCK TABLES `physicalinterface` WRITE;
/*!40000 ALTER TABLE `physicalinterface` DISABLE KEYS */;
/*!40000 ALTER TABLE `physicalinterface` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `powerconnection`
--

DROP TABLE IF EXISTS `powerconnection`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `powerconnection` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `powerconnection`
--

LOCK TABLES `powerconnection` WRITE;
/*!40000 ALTER TABLE `powerconnection` DISABLE KEYS */;
/*!40000 ALTER TABLE `powerconnection` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `powersource`
--

DROP TABLE IF EXISTS `powersource`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `powersource` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `powersource`
--

LOCK TABLES `powersource` WRITE;
/*!40000 ALTER TABLE `powersource` DISABLE KEYS */;
/*!40000 ALTER TABLE `powersource` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `printer`
--

DROP TABLE IF EXISTS `printer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `printer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `printer`
--

LOCK TABLES `printer` WRITE;
/*!40000 ALTER TABLE `printer` DISABLE KEYS */;
/*!40000 ALTER TABLE `printer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_action`
--

DROP TABLE IF EXISTS `priv_action`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_action` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `status` enum('test','enabled','disabled') COLLATE utf8_unicode_ci DEFAULT 'test',
  `realclass` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'Action',
  PRIMARY KEY (`id`),
  KEY `realclass` (`realclass`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_action`
--

LOCK TABLES `priv_action` WRITE;
/*!40000 ALTER TABLE `priv_action` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_action` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_action_email`
--

DROP TABLE IF EXISTS `priv_action_email`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_action_email` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `test_recipient` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `from` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `reply_to` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `to` text COLLATE utf8_unicode_ci,
  `cc` text COLLATE utf8_unicode_ci,
  `bcc` text COLLATE utf8_unicode_ci,
  `subject` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `body` text COLLATE utf8_unicode_ci,
  `importance` enum('high','low','normal') COLLATE utf8_unicode_ci DEFAULT 'normal',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_action_email`
--

LOCK TABLES `priv_action_email` WRITE;
/*!40000 ALTER TABLE `priv_action_email` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_action_email` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_action_notification`
--

DROP TABLE IF EXISTS `priv_action_notification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_action_notification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_action_notification`
--

LOCK TABLES `priv_action_notification` WRITE;
/*!40000 ALTER TABLE `priv_action_notification` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_action_notification` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_app_dashboards`
--

DROP TABLE IF EXISTS `priv_app_dashboards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_app_dashboards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT '0',
  `menu_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `contents` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_app_dashboards`
--

LOCK TABLES `priv_app_dashboards` WRITE;
/*!40000 ALTER TABLE `priv_app_dashboards` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_app_dashboards` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_app_preferences`
--

DROP TABLE IF EXISTS `priv_app_preferences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_app_preferences` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) DEFAULT '0',
  `preferences` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_app_preferences`
--

LOCK TABLES `priv_app_preferences` WRITE;
/*!40000 ALTER TABLE `priv_app_preferences` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_app_preferences` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_async_send_email`
--

DROP TABLE IF EXISTS `priv_async_send_email`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_async_send_email` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `version` int(11) DEFAULT '1',
  `to` text COLLATE utf8_unicode_ci,
  `subject` text COLLATE utf8_unicode_ci,
  `message` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_async_send_email`
--

LOCK TABLES `priv_async_send_email` WRITE;
/*!40000 ALTER TABLE `priv_async_send_email` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_async_send_email` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_async_task`
--

DROP TABLE IF EXISTS `priv_async_task`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_async_task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` enum('error','idle','planned','running') COLLATE utf8_unicode_ci DEFAULT 'planned',
  `created` datetime DEFAULT NULL,
  `started` datetime DEFAULT NULL,
  `planned` datetime DEFAULT NULL,
  `event_id` int(11) DEFAULT '0',
  `remaining_retries` int(11) DEFAULT '0',
  `last_error_code` int(11) DEFAULT '0',
  `last_error` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `last_attempt` datetime DEFAULT NULL,
  `realclass` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'AsyncTask',
  PRIMARY KEY (`id`),
  KEY `event_id` (`event_id`),
  KEY `realclass` (`realclass`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_async_task`
--

LOCK TABLES `priv_async_task` WRITE;
/*!40000 ALTER TABLE `priv_async_task` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_async_task` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_auditcategory`
--

DROP TABLE IF EXISTS `priv_auditcategory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_auditcategory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `definition_set` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_auditcategory`
--

LOCK TABLES `priv_auditcategory` WRITE;
/*!40000 ALTER TABLE `priv_auditcategory` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_auditcategory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_auditrule`
--

DROP TABLE IF EXISTS `priv_auditrule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_auditrule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `query` text COLLATE utf8_unicode_ci,
  `valid_flag` enum('false','true') COLLATE utf8_unicode_ci DEFAULT 'true',
  `category_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_auditrule`
--

LOCK TABLES `priv_auditrule` WRITE;
/*!40000 ALTER TABLE `priv_auditrule` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_auditrule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_backgroundtask`
--

DROP TABLE IF EXISTS `priv_backgroundtask`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_backgroundtask` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `first_run_date` datetime DEFAULT NULL,
  `latest_run_date` datetime DEFAULT NULL,
  `next_run_date` datetime DEFAULT NULL,
  `total_exec_count` int(11) DEFAULT '0',
  `latest_run_duration` decimal(8,3) DEFAULT '0.000',
  `min_run_duration` decimal(8,3) DEFAULT '0.000',
  `max_run_duration` decimal(8,3) DEFAULT '0.000',
  `average_run_duration` decimal(8,3) DEFAULT '0.000',
  `running` tinyint(1) DEFAULT '0',
  `status` enum('active','paused') COLLATE utf8_unicode_ci DEFAULT 'active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_backgroundtask`
--

LOCK TABLES `priv_backgroundtask` WRITE;
/*!40000 ALTER TABLE `priv_backgroundtask` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_backgroundtask` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_bulk_export_result`
--

DROP TABLE IF EXISTS `priv_bulk_export_result`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_bulk_export_result` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` datetime DEFAULT NULL,
  `user_id` int(11) DEFAULT '0',
  `chunk_size` int(11) DEFAULT '0',
  `format` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `temp_file_path` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `search` longtext COLLATE utf8_unicode_ci,
  `status_info` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_bulk_export_result`
--

LOCK TABLES `priv_bulk_export_result` WRITE;
/*!40000 ALTER TABLE `priv_bulk_export_result` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_bulk_export_result` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_change`
--

DROP TABLE IF EXISTS `priv_change`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_change` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `userinfo` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `origin` enum('csv-import.php','csv-interactive','custom-extension','email-processing','interactive','synchro-data-source','webservice-rest','webservice-soap') COLLATE utf8_unicode_ci DEFAULT 'interactive',
  PRIMARY KEY (`id`),
  KEY `origin` (`origin`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_change`
--

LOCK TABLES `priv_change` WRITE;
/*!40000 ALTER TABLE `priv_change` DISABLE KEYS */;
INSERT INTO `priv_change` VALUES (1,'2018-04-28 11:56:53','','interactive'),(2,'2018-04-28 11:56:58','Initialization','interactive'),(3,'2018-04-28 11:56:59','','interactive');
/*!40000 ALTER TABLE `priv_change` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_changeop`
--

DROP TABLE IF EXISTS `priv_changeop`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_changeop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `changeid` int(11) DEFAULT '0',
  `objclass` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `objkey` int(11) DEFAULT '0',
  `optype` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'CMDBChangeOp',
  PRIMARY KEY (`id`),
  KEY `changeid` (`changeid`),
  KEY `optype` (`optype`),
  KEY `objclass_objkey` (`objclass`,`objkey`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_changeop`
--

LOCK TABLES `priv_changeop` WRITE;
/*!40000 ALTER TABLE `priv_changeop` DISABLE KEYS */;
INSERT INTO `priv_changeop` VALUES (1,1,'URP_Profiles',1,'CMDBChangeOpCreate'),(2,1,'URP_Profiles',3,'CMDBChangeOpCreate'),(3,1,'URP_Profiles',4,'CMDBChangeOpCreate'),(4,1,'URP_Profiles',5,'CMDBChangeOpCreate'),(5,1,'URP_Profiles',6,'CMDBChangeOpCreate'),(6,1,'URP_Profiles',7,'CMDBChangeOpCreate'),(7,1,'URP_Profiles',8,'CMDBChangeOpCreate'),(8,1,'URP_Profiles',9,'CMDBChangeOpCreate'),(9,1,'URP_Profiles',10,'CMDBChangeOpCreate'),(10,1,'URP_Profiles',11,'CMDBChangeOpCreate'),(11,1,'URP_Profiles',2,'CMDBChangeOpCreate'),(12,1,'URP_Profiles',12,'CMDBChangeOpCreate'),(13,1,'Organization',1,'CMDBChangeOpCreate'),(14,1,'Person',1,'CMDBChangeOpCreate'),(16,1,'URP_Profiles',1,'CMDBChangeOpSetAttributeLinksAddRemove'),(17,1,'URP_UserProfile',1,'CMDBChangeOpCreate'),(18,1,'UserLocal',1,'CMDBChangeOpCreate'),(19,3,'ModuleInstallation',1,'CMDBChangeOpCreate'),(20,3,'ModuleInstallation',2,'CMDBChangeOpCreate'),(21,3,'ModuleInstallation',3,'CMDBChangeOpCreate'),(22,3,'ModuleInstallation',4,'CMDBChangeOpCreate'),(23,3,'ModuleInstallation',5,'CMDBChangeOpCreate'),(24,3,'ModuleInstallation',6,'CMDBChangeOpCreate'),(25,3,'ModuleInstallation',7,'CMDBChangeOpCreate'),(26,3,'ModuleInstallation',8,'CMDBChangeOpCreate'),(27,3,'ModuleInstallation',9,'CMDBChangeOpCreate'),(28,3,'ModuleInstallation',10,'CMDBChangeOpCreate'),(29,3,'ModuleInstallation',11,'CMDBChangeOpCreate'),(30,3,'ModuleInstallation',12,'CMDBChangeOpCreate'),(31,3,'ModuleInstallation',13,'CMDBChangeOpCreate'),(32,3,'ModuleInstallation',14,'CMDBChangeOpCreate'),(33,3,'ModuleInstallation',15,'CMDBChangeOpCreate'),(34,3,'ModuleInstallation',16,'CMDBChangeOpCreate'),(35,3,'ModuleInstallation',17,'CMDBChangeOpCreate'),(36,3,'ModuleInstallation',18,'CMDBChangeOpCreate'),(37,3,'ModuleInstallation',19,'CMDBChangeOpCreate'),(38,3,'ModuleInstallation',20,'CMDBChangeOpCreate'),(39,3,'ModuleInstallation',21,'CMDBChangeOpCreate'),(40,3,'ModuleInstallation',22,'CMDBChangeOpCreate'),(41,3,'ModuleInstallation',23,'CMDBChangeOpCreate'),(42,3,'ModuleInstallation',24,'CMDBChangeOpCreate'),(43,3,'ModuleInstallation',25,'CMDBChangeOpCreate'),(44,3,'ModuleInstallation',26,'CMDBChangeOpCreate'),(45,3,'ModuleInstallation',27,'CMDBChangeOpCreate'),(46,3,'ExtensionInstallation',1,'CMDBChangeOpCreate'),(47,3,'ExtensionInstallation',2,'CMDBChangeOpCreate'),(48,3,'ExtensionInstallation',3,'CMDBChangeOpCreate'),(49,3,'ExtensionInstallation',4,'CMDBChangeOpCreate'),(50,3,'ExtensionInstallation',5,'CMDBChangeOpCreate'),(51,3,'ExtensionInstallation',6,'CMDBChangeOpCreate'),(52,3,'ExtensionInstallation',7,'CMDBChangeOpCreate'),(53,3,'ExtensionInstallation',8,'CMDBChangeOpCreate'),(54,3,'ExtensionInstallation',9,'CMDBChangeOpCreate'),(55,3,'ExtensionInstallation',10,'CMDBChangeOpCreate'),(56,3,'ExtensionInstallation',11,'CMDBChangeOpCreate'),(57,3,'ExtensionInstallation',12,'CMDBChangeOpCreate'),(58,3,'ExtensionInstallation',13,'CMDBChangeOpCreate');
/*!40000 ALTER TABLE `priv_changeop` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_changeop_attachment_added`
--

DROP TABLE IF EXISTS `priv_changeop_attachment_added`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_changeop_attachment_added` (
  `id` int(11) NOT NULL,
  `attachment_id` int(11) DEFAULT '0',
  `filename` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `attachment_id` (`attachment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_changeop_attachment_added`
--

LOCK TABLES `priv_changeop_attachment_added` WRITE;
/*!40000 ALTER TABLE `priv_changeop_attachment_added` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_changeop_attachment_added` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_changeop_attachment_removed`
--

DROP TABLE IF EXISTS `priv_changeop_attachment_removed`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_changeop_attachment_removed` (
  `id` int(11) NOT NULL,
  `filename` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_changeop_attachment_removed`
--

LOCK TABLES `priv_changeop_attachment_removed` WRITE;
/*!40000 ALTER TABLE `priv_changeop_attachment_removed` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_changeop_attachment_removed` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_changeop_create`
--

DROP TABLE IF EXISTS `priv_changeop_create`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_changeop_create` (
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_changeop_create`
--

LOCK TABLES `priv_changeop_create` WRITE;
/*!40000 ALTER TABLE `priv_changeop_create` DISABLE KEYS */;
INSERT INTO `priv_changeop_create` VALUES (1),(2),(3),(4),(5),(6),(7),(8),(9),(10),(11),(12),(13),(14),(17),(18),(19),(20),(21),(22),(23),(24),(25),(26),(27),(28),(29),(30),(31),(32),(33),(34),(35),(36),(37),(38),(39),(40),(41),(42),(43),(44),(45),(46),(47),(48),(49),(50),(51),(52),(53),(54),(55),(56),(57),(58);
/*!40000 ALTER TABLE `priv_changeop_create` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_changeop_delete`
--

DROP TABLE IF EXISTS `priv_changeop_delete`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_changeop_delete` (
  `id` int(11) NOT NULL,
  `fclass` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `fname` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_changeop_delete`
--

LOCK TABLES `priv_changeop_delete` WRITE;
/*!40000 ALTER TABLE `priv_changeop_delete` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_changeop_delete` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_changeop_links`
--

DROP TABLE IF EXISTS `priv_changeop_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_changeop_links` (
  `id` int(11) NOT NULL,
  `item_class` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `item_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_changeop_links`
--

LOCK TABLES `priv_changeop_links` WRITE;
/*!40000 ALTER TABLE `priv_changeop_links` DISABLE KEYS */;
INSERT INTO `priv_changeop_links` VALUES (16,'User',1);
/*!40000 ALTER TABLE `priv_changeop_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_changeop_links_addremove`
--

DROP TABLE IF EXISTS `priv_changeop_links_addremove`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_changeop_links_addremove` (
  `id` int(11) NOT NULL,
  `type` enum('added','removed') COLLATE utf8_unicode_ci DEFAULT 'added',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_changeop_links_addremove`
--

LOCK TABLES `priv_changeop_links_addremove` WRITE;
/*!40000 ALTER TABLE `priv_changeop_links_addremove` DISABLE KEYS */;
INSERT INTO `priv_changeop_links_addremove` VALUES (16,'added');
/*!40000 ALTER TABLE `priv_changeop_links_addremove` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_changeop_links_tune`
--

DROP TABLE IF EXISTS `priv_changeop_links_tune`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_changeop_links_tune` (
  `id` int(11) NOT NULL,
  `link_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_changeop_links_tune`
--

LOCK TABLES `priv_changeop_links_tune` WRITE;
/*!40000 ALTER TABLE `priv_changeop_links_tune` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_changeop_links_tune` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_changeop_plugin`
--

DROP TABLE IF EXISTS `priv_changeop_plugin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_changeop_plugin` (
  `id` int(11) NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_changeop_plugin`
--

LOCK TABLES `priv_changeop_plugin` WRITE;
/*!40000 ALTER TABLE `priv_changeop_plugin` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_changeop_plugin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_changeop_setatt`
--

DROP TABLE IF EXISTS `priv_changeop_setatt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_changeop_setatt` (
  `id` int(11) NOT NULL,
  `attcode` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_changeop_setatt`
--

LOCK TABLES `priv_changeop_setatt` WRITE;
/*!40000 ALTER TABLE `priv_changeop_setatt` DISABLE KEYS */;
INSERT INTO `priv_changeop_setatt` VALUES (16,'user_list');
/*!40000 ALTER TABLE `priv_changeop_setatt` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_changeop_setatt_custfields`
--

DROP TABLE IF EXISTS `priv_changeop_setatt_custfields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_changeop_setatt_custfields` (
  `id` int(11) NOT NULL,
  `prevdata` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_changeop_setatt_custfields`
--

LOCK TABLES `priv_changeop_setatt_custfields` WRITE;
/*!40000 ALTER TABLE `priv_changeop_setatt_custfields` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_changeop_setatt_custfields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_changeop_setatt_data`
--

DROP TABLE IF EXISTS `priv_changeop_setatt_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_changeop_setatt_data` (
  `id` int(11) NOT NULL,
  `prevdata_data` longblob,
  `prevdata_mimetype` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `prevdata_filename` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_changeop_setatt_data`
--

LOCK TABLES `priv_changeop_setatt_data` WRITE;
/*!40000 ALTER TABLE `priv_changeop_setatt_data` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_changeop_setatt_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_changeop_setatt_encrypted`
--

DROP TABLE IF EXISTS `priv_changeop_setatt_encrypted`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_changeop_setatt_encrypted` (
  `id` int(11) NOT NULL,
  `data` tinyblob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_changeop_setatt_encrypted`
--

LOCK TABLES `priv_changeop_setatt_encrypted` WRITE;
/*!40000 ALTER TABLE `priv_changeop_setatt_encrypted` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_changeop_setatt_encrypted` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_changeop_setatt_html`
--

DROP TABLE IF EXISTS `priv_changeop_setatt_html`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_changeop_setatt_html` (
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_changeop_setatt_html`
--

LOCK TABLES `priv_changeop_setatt_html` WRITE;
/*!40000 ALTER TABLE `priv_changeop_setatt_html` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_changeop_setatt_html` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_changeop_setatt_log`
--

DROP TABLE IF EXISTS `priv_changeop_setatt_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_changeop_setatt_log` (
  `id` int(11) NOT NULL,
  `lastentry` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_changeop_setatt_log`
--

LOCK TABLES `priv_changeop_setatt_log` WRITE;
/*!40000 ALTER TABLE `priv_changeop_setatt_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_changeop_setatt_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_changeop_setatt_longtext`
--

DROP TABLE IF EXISTS `priv_changeop_setatt_longtext`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_changeop_setatt_longtext` (
  `id` int(11) NOT NULL,
  `prevdata` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_changeop_setatt_longtext`
--

LOCK TABLES `priv_changeop_setatt_longtext` WRITE;
/*!40000 ALTER TABLE `priv_changeop_setatt_longtext` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_changeop_setatt_longtext` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_changeop_setatt_pwd`
--

DROP TABLE IF EXISTS `priv_changeop_setatt_pwd`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_changeop_setatt_pwd` (
  `id` int(11) NOT NULL,
  `prev_pwd_hash` tinyblob,
  `prev_pwd_salt` tinyblob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_changeop_setatt_pwd`
--

LOCK TABLES `priv_changeop_setatt_pwd` WRITE;
/*!40000 ALTER TABLE `priv_changeop_setatt_pwd` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_changeop_setatt_pwd` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_changeop_setatt_scalar`
--

DROP TABLE IF EXISTS `priv_changeop_setatt_scalar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_changeop_setatt_scalar` (
  `id` int(11) NOT NULL,
  `oldvalue` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `newvalue` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_changeop_setatt_scalar`
--

LOCK TABLES `priv_changeop_setatt_scalar` WRITE;
/*!40000 ALTER TABLE `priv_changeop_setatt_scalar` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_changeop_setatt_scalar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_changeop_setatt_text`
--

DROP TABLE IF EXISTS `priv_changeop_setatt_text`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_changeop_setatt_text` (
  `id` int(11) NOT NULL,
  `prevdata` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_changeop_setatt_text`
--

LOCK TABLES `priv_changeop_setatt_text` WRITE;
/*!40000 ALTER TABLE `priv_changeop_setatt_text` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_changeop_setatt_text` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_changeop_setatt_url`
--

DROP TABLE IF EXISTS `priv_changeop_setatt_url`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_changeop_setatt_url` (
  `id` int(11) NOT NULL,
  `oldvalue` varchar(2048) COLLATE utf8_unicode_ci DEFAULT '',
  `newvalue` varchar(2048) COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_changeop_setatt_url`
--

LOCK TABLES `priv_changeop_setatt_url` WRITE;
/*!40000 ALTER TABLE `priv_changeop_setatt_url` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_changeop_setatt_url` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_db_properties`
--

DROP TABLE IF EXISTS `priv_db_properties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_db_properties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `value` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `change_date` datetime DEFAULT NULL,
  `change_comment` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_db_properties`
--

LOCK TABLES `priv_db_properties` WRITE;
/*!40000 ALTER TABLE `priv_db_properties` DISABLE KEYS */;
INSERT INTO `priv_db_properties` VALUES (1,'database_uuid','Unique ID of this iTop Database','{66FF4B19-0DAE-0514-424F-24A23B9EEF9D}','2018-04-28 11:56:52','Installation/upgrade of iTop');
/*!40000 ALTER TABLE `priv_db_properties` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_event`
--

DROP TABLE IF EXISTS `priv_event`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` text COLLATE utf8_unicode_ci,
  `date` datetime DEFAULT NULL,
  `userinfo` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `realclass` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'Event',
  PRIMARY KEY (`id`),
  KEY `realclass` (`realclass`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_event`
--

LOCK TABLES `priv_event` WRITE;
/*!40000 ALTER TABLE `priv_event` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_event` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_event_email`
--

DROP TABLE IF EXISTS `priv_event_email`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_event_email` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `to` text COLLATE utf8_unicode_ci,
  `cc` text COLLATE utf8_unicode_ci,
  `bcc` text COLLATE utf8_unicode_ci,
  `from` text COLLATE utf8_unicode_ci,
  `subject` text COLLATE utf8_unicode_ci,
  `body` longtext COLLATE utf8_unicode_ci,
  `attachments` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_event_email`
--

LOCK TABLES `priv_event_email` WRITE;
/*!40000 ALTER TABLE `priv_event_email` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_event_email` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_event_issue`
--

DROP TABLE IF EXISTS `priv_event_issue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_event_issue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `issue` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `impact` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `page` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `arguments_post` longtext COLLATE utf8_unicode_ci,
  `arguments_get` longtext COLLATE utf8_unicode_ci,
  `callstack` longtext COLLATE utf8_unicode_ci,
  `data` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_event_issue`
--

LOCK TABLES `priv_event_issue` WRITE;
/*!40000 ALTER TABLE `priv_event_issue` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_event_issue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_event_loginusage`
--

DROP TABLE IF EXISTS `priv_event_loginusage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_event_loginusage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_event_loginusage`
--

LOCK TABLES `priv_event_loginusage` WRITE;
/*!40000 ALTER TABLE `priv_event_loginusage` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_event_loginusage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_event_notification`
--

DROP TABLE IF EXISTS `priv_event_notification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_event_notification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger_id` int(11) DEFAULT '0',
  `action_id` int(11) DEFAULT '0',
  `object_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `trigger_id` (`trigger_id`),
  KEY `action_id` (`action_id`),
  KEY `object_id` (`object_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_event_notification`
--

LOCK TABLES `priv_event_notification` WRITE;
/*!40000 ALTER TABLE `priv_event_notification` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_event_notification` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_event_onobject`
--

DROP TABLE IF EXISTS `priv_event_onobject`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_event_onobject` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `obj_class` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `obj_key` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_event_onobject`
--

LOCK TABLES `priv_event_onobject` WRITE;
/*!40000 ALTER TABLE `priv_event_onobject` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_event_onobject` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_event_restservice`
--

DROP TABLE IF EXISTS `priv_event_restservice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_event_restservice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `operation` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `version` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `json_input` text COLLATE utf8_unicode_ci,
  `code` int(11) DEFAULT '0',
  `json_output` text COLLATE utf8_unicode_ci,
  `provider` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_event_restservice`
--

LOCK TABLES `priv_event_restservice` WRITE;
/*!40000 ALTER TABLE `priv_event_restservice` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_event_restservice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_event_webservice`
--

DROP TABLE IF EXISTS `priv_event_webservice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_event_webservice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `verb` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `result` tinyint(1) DEFAULT '0',
  `log_info` text COLLATE utf8_unicode_ci,
  `log_warning` text COLLATE utf8_unicode_ci,
  `log_error` text COLLATE utf8_unicode_ci,
  `data` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_event_webservice`
--

LOCK TABLES `priv_event_webservice` WRITE;
/*!40000 ALTER TABLE `priv_event_webservice` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_event_webservice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_extension_install`
--

DROP TABLE IF EXISTS `priv_extension_install`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_extension_install` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `label` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `version` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `source` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `installed` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_extension_install`
--

LOCK TABLES `priv_extension_install` WRITE;
/*!40000 ALTER TABLE `priv_extension_install` DISABLE KEYS */;
INSERT INTO `priv_extension_install` VALUES (1,'itop-config-mgmt-core','Configuration Management Core','2.4.1','datamodels','2018-04-28 11:56:58'),(2,'itop-config-mgmt-datacenter','Data Center Devices','2.4.1','datamodels','2018-04-28 11:56:58'),(3,'itop-config-mgmt-end-user','End-User Devices','2.4.1','datamodels','2018-04-28 11:56:58'),(4,'itop-config-mgmt-storage','Storage Devices','2.4.1','datamodels','2018-04-28 11:56:58'),(5,'itop-config-mgmt-virtualization','Virtualization','2.4.1','datamodels','2018-04-28 11:56:58'),(6,'itop-service-mgmt-service-provider','Service Management for Service Providers','2.4.1','datamodels','2018-04-28 11:56:58'),(7,'itop-ticket-mgmt-itil-user-request','User Request Management','2.4.1','datamodels','2018-04-28 11:56:58'),(8,'itop-ticket-mgmt-itil-incident','Incident Management','2.4.1','datamodels','2018-04-28 11:56:58'),(9,'itop-ticket-mgmt-itil-enhanced-portal','Enhanced Customer Portal','2.4.1','datamodels','2018-04-28 11:56:58'),(10,'itop-ticket-mgmt-itil','ITIL Compliant Tickets Management','2.4.1','datamodels','2018-04-28 11:56:58'),(11,'itop-change-mgmt-itil','ITIL Change Management','2.4.1','datamodels','2018-04-28 11:56:58'),(12,'itop-kown-error-mgmt','Known Errors Management','2.4.1','datamodels','2018-04-28 11:56:58'),(13,'itop-problem-mgmt','Problem Management','2.4.1','datamodels','2018-04-28 11:56:58');
/*!40000 ALTER TABLE `priv_extension_install` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_internaluser`
--

DROP TABLE IF EXISTS `priv_internaluser`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_internaluser` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reset_pwd_token_hash` tinyblob,
  `reset_pwd_token_salt` tinyblob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_internaluser`
--

LOCK TABLES `priv_internaluser` WRITE;
/*!40000 ALTER TABLE `priv_internaluser` DISABLE KEYS */;
INSERT INTO `priv_internaluser` VALUES (1,'','');
/*!40000 ALTER TABLE `priv_internaluser` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_link_action_trigger`
--

DROP TABLE IF EXISTS `priv_link_action_trigger`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_link_action_trigger` (
  `link_id` int(11) NOT NULL AUTO_INCREMENT,
  `action_id` int(11) DEFAULT '0',
  `trigger_id` int(11) DEFAULT '0',
  `order` int(11) DEFAULT '0',
  PRIMARY KEY (`link_id`),
  KEY `action_id` (`action_id`),
  KEY `trigger_id` (`trigger_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_link_action_trigger`
--

LOCK TABLES `priv_link_action_trigger` WRITE;
/*!40000 ALTER TABLE `priv_link_action_trigger` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_link_action_trigger` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_module_install`
--

DROP TABLE IF EXISTS `priv_module_install`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_module_install` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `version` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `installed` datetime DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  `parent_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_module_install`
--

LOCK TABLES `priv_module_install` WRITE;
/*!40000 ALTER TABLE `priv_module_install` DISABLE KEYS */;
INSERT INTO `priv_module_install` VALUES (1,'datamodel','2.4.0','2018-04-28 11:56:58','{\"source_dir\":\"datamodels\\/2.x\\/\"}',0),(2,'iTop','2.4.1.3714','2018-04-28 11:56:58','Done by the setup program\nBuilt on 2018-02-13 14:29:20',0),(3,'authent-external','2.4.0','2018-04-28 11:56:58','Done by the setup program\nOptional\nVisible (during the setup)',2),(4,'authent-local','2.4.0','2018-04-28 11:56:58','Done by the setup program\nMandatory\nVisible (during the setup)',2),(5,'itop-backup','2.4.0','2018-04-28 11:56:58','Done by the setup program\nMandatory\nHidden (selected automatically)',2),(6,'itop-config','2.4.0','2018-04-28 11:56:58','Done by the setup program\nMandatory\nHidden (selected automatically)',2),(7,'itop-profiles-itil','2.4.0','2018-04-28 11:56:58','Done by the setup program\nMandatory\nHidden (selected automatically)',2),(8,'itop-sla-computation','1.0.0','2018-04-28 11:56:58','Done by the setup program\nMandatory\nHidden (selected automatically)',2),(9,'itop-tickets','2.4.0','2018-04-28 11:56:58','Done by the setup program\nMandatory\nHidden (selected automatically)\nDepends on module: itop-config-mgmt/2.4.0',2),(10,'itop-welcome-itil','2.4.0','2018-04-28 11:56:58','Done by the setup program\nMandatory\nHidden (selected automatically)',2),(11,'itop-config-mgmt','2.4.0','2018-04-28 11:56:58','Done by the setup program\nMandatory\nVisible (during the setup)',2),(12,'itop-attachments','2.4.0','2018-04-28 11:56:58','Done by the setup program\nOptional\nVisible (during the setup)',2),(13,'itop-hub-connector','2.4.1','2018-04-28 11:56:58','Done by the setup program\nOptional\nVisible (during the setup)\nDepends on module: itop-config-mgmt/2.4.0',2),(14,'itop-datacenter-mgmt','2.4.0','2018-04-28 11:56:58','Done by the setup program\nOptional\nVisible (during the setup)\nDepends on module: itop-config-mgmt/2.2.0',2),(15,'itop-endusers-devices','2.4.0','2018-04-28 11:56:58','Done by the setup program\nOptional\nVisible (during the setup)\nDepends on module: itop-config-mgmt/2.2.0',2),(16,'itop-storage-mgmt','2.4.0','2018-04-28 11:56:58','Done by the setup program\nOptional\nVisible (during the setup)\nDepends on module: itop-config-mgmt/2.4.0',2),(17,'itop-virtualization-mgmt','2.4.0','2018-04-28 11:56:58','Done by the setup program\nOptional\nVisible (during the setup)\nDepends on module: itop-config-mgmt/2.4.0',2),(18,'itop-bridge-virtualization-storage','2.4.0','2018-04-28 11:56:58','Done by the setup program\nOptional\nHidden (selected automatically)\nDepends on module: itop-storage-mgmt/2.2.0\nDepends on module: itop-virtualization-mgmt/2.2.0',2),(19,'itop-service-mgmt-provider','2.4.0','2018-04-28 11:56:58','Done by the setup program\nOptional\nVisible (during the setup)\nDepends on module: itop-config-mgmt/2.2.0\nDepends on module: itop-tickets/2.0.0',2),(20,'itop-request-mgmt-itil','2.4.0','2018-04-28 11:56:58','Done by the setup program\nOptional\nVisible (during the setup)\nDepends on module: itop-config-mgmt/2.4.0\nDepends on module: itop-tickets/2.4.0',2),(21,'itop-incident-mgmt-itil','2.4.0','2018-04-28 11:56:58','Done by the setup program\nOptional\nVisible (during the setup)\nDepends on module: itop-config-mgmt/2.4.0\nDepends on module: itop-tickets/2.4.0\nDepends on module: itop-profiles-itil/2.3.0',2),(22,'itop-portal','2.4.0','2018-04-28 11:56:58','Done by the setup program\nOptional\nVisible (during the setup)\nDepends on module: itop-portal-base/1.0.0',2),(23,'itop-portal-base','2.4.0','2018-04-28 11:56:58','Done by the setup program\nOptional\nVisible (during the setup)',2),(24,'itop-full-itil','2.4.0','2018-04-28 11:56:58','Done by the setup program\nOptional\nHidden (selected automatically)\nDepends on module: itop-request-mgmt-itil/2.3.0\nDepends on module: itop-incident-mgmt-itil/2.3.0',2),(25,'itop-change-mgmt-itil','2.4.0','2018-04-28 11:56:58','Done by the setup program\nOptional\nVisible (during the setup)\nDepends on module: itop-config-mgmt/2.2.0\nDepends on module: itop-tickets/2.0.0',2),(26,'itop-knownerror-mgmt','2.4.0','2018-04-28 11:56:58','Done by the setup program\nOptional\nVisible (during the setup)\nDepends on module: itop-config-mgmt/2.2.0\nDepends on module: itop-tickets/2.3.0',2),(27,'itop-problem-mgmt','2.4.0','2018-04-28 11:56:58','Done by the setup program\nOptional\nVisible (during the setup)\nDepends on module: itop-config-mgmt/2.2.0\nDepends on module: itop-tickets/2.0.0',2);
/*!40000 ALTER TABLE `priv_module_install` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_ownership_token`
--

DROP TABLE IF EXISTS `priv_ownership_token`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_ownership_token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `acquired` datetime DEFAULT NULL,
  `last_seen` datetime DEFAULT NULL,
  `obj_class` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `obj_key` int(11) DEFAULT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `user_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_ownership_token`
--

LOCK TABLES `priv_ownership_token` WRITE;
/*!40000 ALTER TABLE `priv_ownership_token` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_ownership_token` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_query`
--

DROP TABLE IF EXISTS `priv_query`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_query` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `description` text COLLATE utf8_unicode_ci,
  `fields` text COLLATE utf8_unicode_ci,
  `realclass` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'Query',
  PRIMARY KEY (`id`),
  KEY `realclass` (`realclass`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_query`
--

LOCK TABLES `priv_query` WRITE;
/*!40000 ALTER TABLE `priv_query` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_query` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_query_oql`
--

DROP TABLE IF EXISTS `priv_query_oql`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_query_oql` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oql` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_query_oql`
--

LOCK TABLES `priv_query_oql` WRITE;
/*!40000 ALTER TABLE `priv_query_oql` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_query_oql` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_shortcut`
--

DROP TABLE IF EXISTS `priv_shortcut`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_shortcut` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `context` text COLLATE utf8_unicode_ci,
  `realclass` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'Shortcut',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `realclass` (`realclass`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_shortcut`
--

LOCK TABLES `priv_shortcut` WRITE;
/*!40000 ALTER TABLE `priv_shortcut` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_shortcut` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_shortcut_oql`
--

DROP TABLE IF EXISTS `priv_shortcut_oql`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_shortcut_oql` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oql` text COLLATE utf8_unicode_ci,
  `auto_reload` enum('custom','none') COLLATE utf8_unicode_ci DEFAULT 'none',
  `auto_reload_sec` int(11) DEFAULT '60',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_shortcut_oql`
--

LOCK TABLES `priv_shortcut_oql` WRITE;
/*!40000 ALTER TABLE `priv_shortcut_oql` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_shortcut_oql` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_sync_att`
--

DROP TABLE IF EXISTS `priv_sync_att`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_sync_att` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sync_source_id` int(11) DEFAULT '0',
  `attcode` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `update` tinyint(1) DEFAULT '1',
  `reconcile` tinyint(1) DEFAULT '0',
  `update_policy` enum('master_locked','master_unlocked','write_if_empty') COLLATE utf8_unicode_ci DEFAULT 'master_locked',
  `finalclass` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'SynchroAttribute',
  PRIMARY KEY (`id`),
  KEY `sync_source_id` (`sync_source_id`),
  KEY `finalclass` (`finalclass`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_sync_att`
--

LOCK TABLES `priv_sync_att` WRITE;
/*!40000 ALTER TABLE `priv_sync_att` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_sync_att` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_sync_att_extkey`
--

DROP TABLE IF EXISTS `priv_sync_att_extkey`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_sync_att_extkey` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reconciliation_attcode` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_sync_att_extkey`
--

LOCK TABLES `priv_sync_att_extkey` WRITE;
/*!40000 ALTER TABLE `priv_sync_att_extkey` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_sync_att_extkey` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_sync_att_linkset`
--

DROP TABLE IF EXISTS `priv_sync_att_linkset`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_sync_att_linkset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `row_separator` varchar(255) COLLATE utf8_unicode_ci DEFAULT '|',
  `attribute_separator` varchar(255) COLLATE utf8_unicode_ci DEFAULT ';',
  `value_separator` varchar(255) COLLATE utf8_unicode_ci DEFAULT ':',
  `attribute_qualifier` varchar(255) COLLATE utf8_unicode_ci DEFAULT '''',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_sync_att_linkset`
--

LOCK TABLES `priv_sync_att_linkset` WRITE;
/*!40000 ALTER TABLE `priv_sync_att_linkset` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_sync_att_linkset` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_sync_datasource`
--

DROP TABLE IF EXISTS `priv_sync_datasource`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_sync_datasource` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `description` text COLLATE utf8_unicode_ci,
  `status` enum('implementation','obsolete','production') COLLATE utf8_unicode_ci DEFAULT 'implementation',
  `user_id` int(11) DEFAULT '0',
  `notify_contact_id` int(11) DEFAULT '0',
  `scope_class` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'lnkTriggerAction',
  `database_table_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `scope_restriction` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `full_load_periodicity` int(11) unsigned DEFAULT NULL,
  `reconciliation_policy` enum('use_attributes','use_primary_key') COLLATE utf8_unicode_ci DEFAULT 'use_attributes',
  `action_on_zero` enum('create','error') COLLATE utf8_unicode_ci DEFAULT 'create',
  `action_on_one` enum('error','update') COLLATE utf8_unicode_ci DEFAULT 'update',
  `action_on_multiple` enum('create','error','take_first') COLLATE utf8_unicode_ci DEFAULT 'error',
  `delete_policy` enum('delete','ignore','update','update_then_delete') COLLATE utf8_unicode_ci DEFAULT 'ignore',
  `delete_policy_update` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `delete_policy_retention` int(11) unsigned DEFAULT NULL,
  `user_delete_policy` enum('administrators','everybody','nobody') COLLATE utf8_unicode_ci DEFAULT 'nobody',
  `url_icon` varchar(2048) COLLATE utf8_unicode_ci DEFAULT '',
  `url_application` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `notify_contact_id` (`notify_contact_id`),
  KEY `scope_class` (`scope_class`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_sync_datasource`
--

LOCK TABLES `priv_sync_datasource` WRITE;
/*!40000 ALTER TABLE `priv_sync_datasource` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_sync_datasource` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_sync_log`
--

DROP TABLE IF EXISTS `priv_sync_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_sync_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sync_source_id` int(11) DEFAULT '0',
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `status` enum('completed','error','running') COLLATE utf8_unicode_ci DEFAULT 'running',
  `status_curr_job` int(11) DEFAULT '0',
  `status_curr_pos` int(11) DEFAULT '0',
  `stats_nb_replica_seen` int(11) DEFAULT '0',
  `stats_nb_replica_total` int(11) DEFAULT '0',
  `stats_nb_obj_deleted` int(11) DEFAULT '0',
  `stats_deleted_errors` int(11) DEFAULT '0',
  `stats_nb_obj_obsoleted` int(11) DEFAULT '0',
  `stats_nb_obj_obsoleted_errors` int(11) DEFAULT '0',
  `stats_nb_obj_created` int(11) DEFAULT '0',
  `stats_nb_obj_created_errors` int(11) DEFAULT '0',
  `stats_nb_obj_created_warnings` int(11) DEFAULT '0',
  `stats_nb_obj_updated` int(11) DEFAULT '0',
  `stats_nb_obj_updated_errors` int(11) DEFAULT '0',
  `stats_nb_obj_updated_warnings` int(11) DEFAULT '0',
  `stats_nb_obj_unchanged_warnings` int(11) DEFAULT '0',
  `stats_nb_replica_reconciled_errors` int(11) DEFAULT '0',
  `stats_nb_replica_disappeared_no_action` int(11) DEFAULT '0',
  `stats_nb_obj_new_updated` int(11) DEFAULT '0',
  `stats_nb_obj_new_updated_warnings` int(11) DEFAULT '0',
  `stats_nb_obj_new_unchanged` int(11) DEFAULT '0',
  `stats_nb_obj_new_unchanged_warnings` int(11) DEFAULT '0',
  `last_error` text COLLATE utf8_unicode_ci,
  `traces` longtext COLLATE utf8_unicode_ci,
  `memory_usage_peak` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `sync_source_id` (`sync_source_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_sync_log`
--

LOCK TABLES `priv_sync_log` WRITE;
/*!40000 ALTER TABLE `priv_sync_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_sync_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_sync_replica`
--

DROP TABLE IF EXISTS `priv_sync_replica`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_sync_replica` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sync_source_id` int(11) DEFAULT '0',
  `dest_id` int(11) DEFAULT '0',
  `dest_class` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'Organization',
  `status_last_seen` datetime DEFAULT NULL,
  `status` enum('modified','new','obsolete','orphan','synchronized') COLLATE utf8_unicode_ci DEFAULT 'new',
  `status_dest_creator` tinyint(1) DEFAULT '0',
  `status_last_error` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `status_last_warning` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `info_creation_date` datetime DEFAULT NULL,
  `info_last_modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sync_source_id` (`sync_source_id`),
  KEY `dest_class` (`dest_class`),
  KEY `dest_class_dest_id` (`dest_class`,`dest_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_sync_replica`
--

LOCK TABLES `priv_sync_replica` WRITE;
/*!40000 ALTER TABLE `priv_sync_replica` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_sync_replica` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_trigger`
--

DROP TABLE IF EXISTS `priv_trigger`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_trigger` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `realclass` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'Trigger',
  PRIMARY KEY (`id`),
  KEY `realclass` (`realclass`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_trigger`
--

LOCK TABLES `priv_trigger` WRITE;
/*!40000 ALTER TABLE `priv_trigger` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_trigger` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_trigger_onobjcreate`
--

DROP TABLE IF EXISTS `priv_trigger_onobjcreate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_trigger_onobjcreate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_trigger_onobjcreate`
--

LOCK TABLES `priv_trigger_onobjcreate` WRITE;
/*!40000 ALTER TABLE `priv_trigger_onobjcreate` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_trigger_onobjcreate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_trigger_onobject`
--

DROP TABLE IF EXISTS `priv_trigger_onobject`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_trigger_onobject` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `target_class` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'ApplicationSolution',
  `filter` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `target_class` (`target_class`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_trigger_onobject`
--

LOCK TABLES `priv_trigger_onobject` WRITE;
/*!40000 ALTER TABLE `priv_trigger_onobject` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_trigger_onobject` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_trigger_onportalupdate`
--

DROP TABLE IF EXISTS `priv_trigger_onportalupdate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_trigger_onportalupdate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_trigger_onportalupdate`
--

LOCK TABLES `priv_trigger_onportalupdate` WRITE;
/*!40000 ALTER TABLE `priv_trigger_onportalupdate` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_trigger_onportalupdate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_trigger_onstatechange`
--

DROP TABLE IF EXISTS `priv_trigger_onstatechange`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_trigger_onstatechange` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `state` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_trigger_onstatechange`
--

LOCK TABLES `priv_trigger_onstatechange` WRITE;
/*!40000 ALTER TABLE `priv_trigger_onstatechange` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_trigger_onstatechange` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_trigger_onstateenter`
--

DROP TABLE IF EXISTS `priv_trigger_onstateenter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_trigger_onstateenter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_trigger_onstateenter`
--

LOCK TABLES `priv_trigger_onstateenter` WRITE;
/*!40000 ALTER TABLE `priv_trigger_onstateenter` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_trigger_onstateenter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_trigger_onstateleave`
--

DROP TABLE IF EXISTS `priv_trigger_onstateleave`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_trigger_onstateleave` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_trigger_onstateleave`
--

LOCK TABLES `priv_trigger_onstateleave` WRITE;
/*!40000 ALTER TABLE `priv_trigger_onstateleave` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_trigger_onstateleave` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_trigger_threshold`
--

DROP TABLE IF EXISTS `priv_trigger_threshold`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_trigger_threshold` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stop_watch_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `threshold_index` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_trigger_threshold`
--

LOCK TABLES `priv_trigger_threshold` WRITE;
/*!40000 ALTER TABLE `priv_trigger_threshold` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_trigger_threshold` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_urp_profiles`
--

DROP TABLE IF EXISTS `priv_urp_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_urp_profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_urp_profiles`
--

LOCK TABLES `priv_urp_profiles` WRITE;
/*!40000 ALTER TABLE `priv_urp_profiles` DISABLE KEYS */;
INSERT INTO `priv_urp_profiles` VALUES (1,'Administrator','Has the rights on everything (bypassing any control)'),(2,'Portal user','Has the rights to access to the user portal. People having this profile will not be allowed to access the standard application, they will be automatically redirected to the user portal.'),(3,'Configuration Manager','Person in charge of the documentation of the managed CIs'),(4,'Service Desk Agent','Person in charge of creating incident reports'),(5,'Support Agent','Person analyzing and solving the current incidents'),(6,'Problem Manager','Person analyzing and solving the current problems'),(7,'Change Implementor','Person executing the changes'),(8,'Change Supervisor','Person responsible for the overall change execution'),(9,'Change Approver','Person who could be impacted by some changes'),(10,'Service Manager','Person responsible for the service delivered to the [internal] customer'),(11,'Document author','Any person who could contribute to documentation'),(12,'Portal power user','Users having this profile will have the rights to see all the tickets for a customer in the portal. Must be used in conjunction with other profiles (e.g. Portal User).');
/*!40000 ALTER TABLE `priv_urp_profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_urp_userorg`
--

DROP TABLE IF EXISTS `priv_urp_userorg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_urp_userorg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) DEFAULT '0',
  `allowed_org_id` int(11) DEFAULT '0',
  `reason` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `allowed_org_id` (`allowed_org_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_urp_userorg`
--

LOCK TABLES `priv_urp_userorg` WRITE;
/*!40000 ALTER TABLE `priv_urp_userorg` DISABLE KEYS */;
/*!40000 ALTER TABLE `priv_urp_userorg` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_urp_userprofile`
--

DROP TABLE IF EXISTS `priv_urp_userprofile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_urp_userprofile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) DEFAULT '0',
  `profileid` int(11) DEFAULT '0',
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `profileid` (`profileid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_urp_userprofile`
--

LOCK TABLES `priv_urp_userprofile` WRITE;
/*!40000 ALTER TABLE `priv_urp_userprofile` DISABLE KEYS */;
INSERT INTO `priv_urp_userprofile` VALUES (1,1,1,'By definition, the administrator must have the administrator profile');
/*!40000 ALTER TABLE `priv_urp_userprofile` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_user`
--

DROP TABLE IF EXISTS `priv_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contactid` int(11) DEFAULT '0',
  `login` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `language` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'EN US',
  `status` enum('disabled','enabled') COLLATE utf8_unicode_ci DEFAULT 'enabled',
  `finalclass` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'User',
  PRIMARY KEY (`id`),
  KEY `contactid` (`contactid`),
  KEY `language` (`language`),
  KEY `finalclass` (`finalclass`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_user`
--

LOCK TABLES `priv_user` WRITE;
/*!40000 ALTER TABLE `priv_user` DISABLE KEYS */;
INSERT INTO `priv_user` VALUES (1,1,'admin','EN US','enabled','UserLocal');
/*!40000 ALTER TABLE `priv_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `priv_user_local`
--

DROP TABLE IF EXISTS `priv_user_local`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `priv_user_local` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `password_hash` tinyblob,
  `password_salt` tinyblob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `priv_user_local`
--

LOCK TABLES `priv_user_local` WRITE;
/*!40000 ALTER TABLE `priv_user_local` DISABLE KEYS */;
INSERT INTO `priv_user_local` VALUES (1,'79511c62d8e9e2361e291cea2a7304c73fe7aff08337b718886f0226b0a3fd0f','fa058bb7869dfd72');
/*!40000 ALTER TABLE `priv_user_local` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `providercontract`
--

DROP TABLE IF EXISTS `providercontract`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `providercontract` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sla` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `coverage` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `providercontract`
--

LOCK TABLES `providercontract` WRITE;
/*!40000 ALTER TABLE `providercontract` DISABLE KEYS */;
/*!40000 ALTER TABLE `providercontract` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rack`
--

DROP TABLE IF EXISTS `rack`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rack` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nb_u` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rack`
--

LOCK TABLES `rack` WRITE;
/*!40000 ALTER TABLE `rack` DISABLE KEYS */;
/*!40000 ALTER TABLE `rack` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sanswitch`
--

DROP TABLE IF EXISTS `sanswitch`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sanswitch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sanswitch`
--

LOCK TABLES `sanswitch` WRITE;
/*!40000 ALTER TABLE `sanswitch` DISABLE KEYS */;
/*!40000 ALTER TABLE `sanswitch` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `server`
--

DROP TABLE IF EXISTS `server`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `server` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `osfamily_id` int(11) DEFAULT '0',
  `osversion_id` int(11) DEFAULT '0',
  `oslicence_id` int(11) DEFAULT '0',
  `cpu` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `ram` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `osfamily_id` (`osfamily_id`),
  KEY `osversion_id` (`osversion_id`),
  KEY `oslicence_id` (`oslicence_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `server`
--

LOCK TABLES `server` WRITE;
/*!40000 ALTER TABLE `server` DISABLE KEYS */;
/*!40000 ALTER TABLE `server` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service`
--

DROP TABLE IF EXISTS `service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `service` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `org_id` int(11) DEFAULT '0',
  `servicefamily_id` int(11) DEFAULT '0',
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `status` enum('implementation','obsolete','production') COLLATE utf8_unicode_ci DEFAULT NULL,
  `icon_data` longblob,
  `icon_mimetype` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `icon_filename` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `org_id` (`org_id`),
  KEY `servicefamily_id` (`servicefamily_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service`
--

LOCK TABLES `service` WRITE;
/*!40000 ALTER TABLE `service` DISABLE KEYS */;
/*!40000 ALTER TABLE `service` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `servicefamilly`
--

DROP TABLE IF EXISTS `servicefamilly`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `servicefamilly` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `icon_data` longblob,
  `icon_mimetype` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `icon_filename` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `servicefamilly`
--

LOCK TABLES `servicefamilly` WRITE;
/*!40000 ALTER TABLE `servicefamilly` DISABLE KEYS */;
/*!40000 ALTER TABLE `servicefamilly` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `servicesubcategory`
--

DROP TABLE IF EXISTS `servicesubcategory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `servicesubcategory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `description` text COLLATE utf8_unicode_ci,
  `service_id` int(11) DEFAULT '0',
  `request_type` enum('incident','service_request') COLLATE utf8_unicode_ci DEFAULT 'incident',
  `status` enum('implementation','obsolete','production') COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `service_id` (`service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `servicesubcategory`
--

LOCK TABLES `servicesubcategory` WRITE;
/*!40000 ALTER TABLE `servicesubcategory` DISABLE KEYS */;
/*!40000 ALTER TABLE `servicesubcategory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sla`
--

DROP TABLE IF EXISTS `sla`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sla` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `description` text COLLATE utf8_unicode_ci,
  `org_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `org_id` (`org_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sla`
--

LOCK TABLES `sla` WRITE;
/*!40000 ALTER TABLE `sla` DISABLE KEYS */;
/*!40000 ALTER TABLE `sla` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `slt`
--

DROP TABLE IF EXISTS `slt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `slt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `priority` enum('1','2','3','4') COLLATE utf8_unicode_ci DEFAULT NULL,
  `request_type` enum('incident','service_request') COLLATE utf8_unicode_ci DEFAULT NULL,
  `metric` enum('tto','ttr') COLLATE utf8_unicode_ci DEFAULT NULL,
  `value` int(11) DEFAULT NULL,
  `unit` enum('hours','minutes') COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `slt`
--

LOCK TABLES `slt` WRITE;
/*!40000 ALTER TABLE `slt` DISABLE KEYS */;
/*!40000 ALTER TABLE `slt` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `software`
--

DROP TABLE IF EXISTS `software`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `software` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `vendor` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `version` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `type` enum('DBServer','Middleware','OtherSoftware','PCSoftware','WebServer') COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `software`
--

LOCK TABLES `software` WRITE;
/*!40000 ALTER TABLE `software` DISABLE KEYS */;
/*!40000 ALTER TABLE `software` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `softwareinstance`
--

DROP TABLE IF EXISTS `softwareinstance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `softwareinstance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `functionalci_id` int(11) DEFAULT '0',
  `software_id` int(11) DEFAULT '0',
  `softwarelicence_id` int(11) DEFAULT '0',
  `path` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `status` enum('active','inactive') COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `functionalci_id` (`functionalci_id`),
  KEY `software_id` (`software_id`),
  KEY `softwarelicence_id` (`softwarelicence_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `softwareinstance`
--

LOCK TABLES `softwareinstance` WRITE;
/*!40000 ALTER TABLE `softwareinstance` DISABLE KEYS */;
/*!40000 ALTER TABLE `softwareinstance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `softwarelicence`
--

DROP TABLE IF EXISTS `softwarelicence`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `softwarelicence` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `software_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `software_id` (`software_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `softwarelicence`
--

LOCK TABLES `softwarelicence` WRITE;
/*!40000 ALTER TABLE `softwarelicence` DISABLE KEYS */;
/*!40000 ALTER TABLE `softwarelicence` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `softwarepatch`
--

DROP TABLE IF EXISTS `softwarepatch`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `softwarepatch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `software_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `software_id` (`software_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `softwarepatch`
--

LOCK TABLES `softwarepatch` WRITE;
/*!40000 ALTER TABLE `softwarepatch` DISABLE KEYS */;
/*!40000 ALTER TABLE `softwarepatch` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `storagesystem`
--

DROP TABLE IF EXISTS `storagesystem`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `storagesystem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `storagesystem`
--

LOCK TABLES `storagesystem` WRITE;
/*!40000 ALTER TABLE `storagesystem` DISABLE KEYS */;
/*!40000 ALTER TABLE `storagesystem` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subnet`
--

DROP TABLE IF EXISTS `subnet`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subnet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` text COLLATE utf8_unicode_ci,
  `subnet_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `org_id` int(11) DEFAULT '0',
  `ip` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `ip_mask` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `org_id` (`org_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subnet`
--

LOCK TABLES `subnet` WRITE;
/*!40000 ALTER TABLE `subnet` DISABLE KEYS */;
/*!40000 ALTER TABLE `subnet` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tablet`
--

DROP TABLE IF EXISTS `tablet`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tablet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tablet`
--

LOCK TABLES `tablet` WRITE;
/*!40000 ALTER TABLE `tablet` DISABLE KEYS */;
/*!40000 ALTER TABLE `tablet` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tape`
--

DROP TABLE IF EXISTS `tape`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tape` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `description` text COLLATE utf8_unicode_ci,
  `size` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `tapelibrary_id` int(11) DEFAULT '0',
  `obsolescence_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tapelibrary_id` (`tapelibrary_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tape`
--

LOCK TABLES `tape` WRITE;
/*!40000 ALTER TABLE `tape` DISABLE KEYS */;
/*!40000 ALTER TABLE `tape` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tapelibrary`
--

DROP TABLE IF EXISTS `tapelibrary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tapelibrary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tapelibrary`
--

LOCK TABLES `tapelibrary` WRITE;
/*!40000 ALTER TABLE `tapelibrary` DISABLE KEYS */;
/*!40000 ALTER TABLE `tapelibrary` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `team`
--

DROP TABLE IF EXISTS `team`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `team` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `team`
--

LOCK TABLES `team` WRITE;
/*!40000 ALTER TABLE `team` DISABLE KEYS */;
/*!40000 ALTER TABLE `team` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `telephonyci`
--

DROP TABLE IF EXISTS `telephonyci`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `telephonyci` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `phonenumber` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `telephonyci`
--

LOCK TABLES `telephonyci` WRITE;
/*!40000 ALTER TABLE `telephonyci` DISABLE KEYS */;
/*!40000 ALTER TABLE `telephonyci` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ticket`
--

DROP TABLE IF EXISTS `ticket`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ticket` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `operational_status` enum('closed','ongoing','resolved') COLLATE utf8_unicode_ci DEFAULT 'ongoing',
  `ref` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `org_id` int(11) DEFAULT '0',
  `caller_id` int(11) DEFAULT '0',
  `team_id` int(11) DEFAULT '0',
  `agent_id` int(11) DEFAULT '0',
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `description` text COLLATE utf8_unicode_ci,
  `description_format` enum('text','html') COLLATE utf8_unicode_ci DEFAULT 'text',
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `last_update` datetime DEFAULT NULL,
  `close_date` datetime DEFAULT NULL,
  `private_log` longtext COLLATE utf8_unicode_ci,
  `private_log_index` blob,
  `finalclass` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'Ticket',
  `obsolescence_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `operational_status` (`operational_status`),
  KEY `org_id` (`org_id`),
  KEY `caller_id` (`caller_id`),
  KEY `team_id` (`team_id`),
  KEY `agent_id` (`agent_id`),
  KEY `finalclass` (`finalclass`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ticket`
--

LOCK TABLES `ticket` WRITE;
/*!40000 ALTER TABLE `ticket` DISABLE KEYS */;
/*!40000 ALTER TABLE `ticket` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ticket_incident`
--

DROP TABLE IF EXISTS `ticket_incident`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ticket_incident` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` enum('assigned','closed','escalated_tto','escalated_ttr','new','pending','resolved') COLLATE utf8_unicode_ci DEFAULT 'new',
  `impact` enum('1','2','3') COLLATE utf8_unicode_ci DEFAULT '1',
  `priority` enum('1','2','3','4') COLLATE utf8_unicode_ci DEFAULT '4',
  `urgency` enum('1','2','3','4') COLLATE utf8_unicode_ci DEFAULT '4',
  `origin` enum('mail','monitoring','phone','portal') COLLATE utf8_unicode_ci DEFAULT 'phone',
  `service_id` int(11) DEFAULT '0',
  `servicesubcategory_id` int(11) DEFAULT '0',
  `escalation_flag` enum('no','yes') COLLATE utf8_unicode_ci DEFAULT 'no',
  `escalation_reason` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `assignment_date` datetime DEFAULT NULL,
  `resolution_date` datetime DEFAULT NULL,
  `last_pending_date` datetime DEFAULT NULL,
  `cumulatedpending_timespent` int(11) unsigned DEFAULT NULL,
  `cumulatedpending_started` datetime DEFAULT NULL,
  `cumulatedpending_laststart` datetime DEFAULT NULL,
  `cumulatedpending_stopped` datetime DEFAULT NULL,
  `tto_timespent` int(11) unsigned DEFAULT NULL,
  `tto_started` datetime DEFAULT NULL,
  `tto_laststart` datetime DEFAULT NULL,
  `tto_stopped` datetime DEFAULT NULL,
  `tto_75_deadline` datetime DEFAULT NULL,
  `tto_75_passed` tinyint(1) unsigned DEFAULT NULL,
  `tto_75_triggered` tinyint(1) DEFAULT NULL,
  `tto_75_overrun` int(11) unsigned DEFAULT NULL,
  `tto_100_deadline` datetime DEFAULT NULL,
  `tto_100_passed` tinyint(1) unsigned DEFAULT NULL,
  `tto_100_triggered` tinyint(1) DEFAULT NULL,
  `tto_100_overrun` int(11) unsigned DEFAULT NULL,
  `ttr_timespent` int(11) unsigned DEFAULT NULL,
  `ttr_started` datetime DEFAULT NULL,
  `ttr_laststart` datetime DEFAULT NULL,
  `ttr_stopped` datetime DEFAULT NULL,
  `ttr_75_deadline` datetime DEFAULT NULL,
  `ttr_75_passed` tinyint(1) unsigned DEFAULT NULL,
  `ttr_75_triggered` tinyint(1) DEFAULT NULL,
  `ttr_75_overrun` int(11) unsigned DEFAULT NULL,
  `ttr_100_deadline` datetime DEFAULT NULL,
  `ttr_100_passed` tinyint(1) unsigned DEFAULT NULL,
  `ttr_100_triggered` tinyint(1) DEFAULT NULL,
  `ttr_100_overrun` int(11) unsigned DEFAULT NULL,
  `time_spent` int(11) unsigned DEFAULT NULL,
  `resolution_code` enum('assistance','bug fixed','hardware repair','other','software patch','system update','training') COLLATE utf8_unicode_ci DEFAULT 'assistance',
  `solution` text COLLATE utf8_unicode_ci,
  `pending_reason` text COLLATE utf8_unicode_ci,
  `parent_incident_id` int(11) DEFAULT '0',
  `parent_problem_id` int(11) DEFAULT '0',
  `parent_change_id` int(11) DEFAULT '0',
  `public_log` longtext COLLATE utf8_unicode_ci,
  `public_log_index` blob,
  `user_satisfaction` enum('1','2','3','4') COLLATE utf8_unicode_ci DEFAULT '1',
  `user_commment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `service_id` (`service_id`),
  KEY `servicesubcategory_id` (`servicesubcategory_id`),
  KEY `parent_incident_id` (`parent_incident_id`),
  KEY `parent_problem_id` (`parent_problem_id`),
  KEY `parent_change_id` (`parent_change_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ticket_incident`
--

LOCK TABLES `ticket_incident` WRITE;
/*!40000 ALTER TABLE `ticket_incident` DISABLE KEYS */;
/*!40000 ALTER TABLE `ticket_incident` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ticket_problem`
--

DROP TABLE IF EXISTS `ticket_problem`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ticket_problem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` enum('assigned','closed','new','resolved') COLLATE utf8_unicode_ci DEFAULT 'new',
  `service_id` int(11) DEFAULT '0',
  `servicesubcategory_id` int(11) DEFAULT '0',
  `product` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `impact` enum('1','2','3') COLLATE utf8_unicode_ci DEFAULT '1',
  `urgency` enum('1','2','3','4') COLLATE utf8_unicode_ci DEFAULT '1',
  `priority` enum('1','2','3','4') COLLATE utf8_unicode_ci DEFAULT '1',
  `related_change_id` int(11) DEFAULT '0',
  `assignment_date` datetime DEFAULT NULL,
  `resolution_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `service_id` (`service_id`),
  KEY `servicesubcategory_id` (`servicesubcategory_id`),
  KEY `related_change_id` (`related_change_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ticket_problem`
--

LOCK TABLES `ticket_problem` WRITE;
/*!40000 ALTER TABLE `ticket_problem` DISABLE KEYS */;
/*!40000 ALTER TABLE `ticket_problem` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ticket_request`
--

DROP TABLE IF EXISTS `ticket_request`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ticket_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` enum('approved','assigned','closed','escalated_tto','escalated_ttr','new','pending','rejected','resolved','waiting_for_approval') COLLATE utf8_unicode_ci DEFAULT 'new',
  `request_type` enum('service_request') COLLATE utf8_unicode_ci DEFAULT 'service_request',
  `impact` enum('1','2','3') COLLATE utf8_unicode_ci DEFAULT '1',
  `priority` enum('1','2','3','4') COLLATE utf8_unicode_ci DEFAULT '4',
  `urgency` enum('1','2','3','4') COLLATE utf8_unicode_ci DEFAULT '4',
  `origin` enum('mail','phone','portal') COLLATE utf8_unicode_ci DEFAULT 'phone',
  `approver_id` int(11) DEFAULT '0',
  `service_id` int(11) DEFAULT '0',
  `servicesubcategory_id` int(11) DEFAULT '0',
  `escalation_flag` enum('no','yes') COLLATE utf8_unicode_ci DEFAULT 'no',
  `escalation_reason` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `assignment_date` datetime DEFAULT NULL,
  `resolution_date` datetime DEFAULT NULL,
  `last_pending_date` datetime DEFAULT NULL,
  `cumulatedpending_timespent` int(11) unsigned DEFAULT NULL,
  `cumulatedpending_started` datetime DEFAULT NULL,
  `cumulatedpending_laststart` datetime DEFAULT NULL,
  `cumulatedpending_stopped` datetime DEFAULT NULL,
  `tto_timespent` int(11) unsigned DEFAULT NULL,
  `tto_started` datetime DEFAULT NULL,
  `tto_laststart` datetime DEFAULT NULL,
  `tto_stopped` datetime DEFAULT NULL,
  `tto_75_deadline` datetime DEFAULT NULL,
  `tto_75_passed` tinyint(1) unsigned DEFAULT NULL,
  `tto_75_triggered` tinyint(1) DEFAULT NULL,
  `tto_75_overrun` int(11) unsigned DEFAULT NULL,
  `tto_100_deadline` datetime DEFAULT NULL,
  `tto_100_passed` tinyint(1) unsigned DEFAULT NULL,
  `tto_100_triggered` tinyint(1) DEFAULT NULL,
  `tto_100_overrun` int(11) unsigned DEFAULT NULL,
  `ttr_timespent` int(11) unsigned DEFAULT NULL,
  `ttr_started` datetime DEFAULT NULL,
  `ttr_laststart` datetime DEFAULT NULL,
  `ttr_stopped` datetime DEFAULT NULL,
  `ttr_75_deadline` datetime DEFAULT NULL,
  `ttr_75_passed` tinyint(1) unsigned DEFAULT NULL,
  `ttr_75_triggered` tinyint(1) DEFAULT NULL,
  `ttr_75_overrun` int(11) unsigned DEFAULT NULL,
  `ttr_100_deadline` datetime DEFAULT NULL,
  `ttr_100_passed` tinyint(1) unsigned DEFAULT NULL,
  `ttr_100_triggered` tinyint(1) DEFAULT NULL,
  `ttr_100_overrun` int(11) unsigned DEFAULT NULL,
  `time_spent` int(11) unsigned DEFAULT NULL,
  `resolution_code` enum('assistance','bug fixed','hardware repair','other','software patch','system update','training') COLLATE utf8_unicode_ci DEFAULT 'assistance',
  `solution` text COLLATE utf8_unicode_ci,
  `pending_reason` text COLLATE utf8_unicode_ci,
  `parent_request_id` int(11) DEFAULT '0',
  `parent_incident_id` int(11) DEFAULT '0',
  `parent_problem_id` int(11) DEFAULT '0',
  `parent_change_id` int(11) DEFAULT '0',
  `public_log` longtext COLLATE utf8_unicode_ci,
  `public_log_index` blob,
  `user_satisfaction` enum('1','2','3','4') COLLATE utf8_unicode_ci DEFAULT '1',
  `user_commment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `approver_id` (`approver_id`),
  KEY `service_id` (`service_id`),
  KEY `servicesubcategory_id` (`servicesubcategory_id`),
  KEY `parent_request_id` (`parent_request_id`),
  KEY `parent_incident_id` (`parent_incident_id`),
  KEY `parent_problem_id` (`parent_problem_id`),
  KEY `parent_change_id` (`parent_change_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ticket_request`
--

LOCK TABLES `ticket_request` WRITE;
/*!40000 ALTER TABLE `ticket_request` DISABLE KEYS */;
/*!40000 ALTER TABLE `ticket_request` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `typology`
--

DROP TABLE IF EXISTS `typology`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `typology` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `finalclass` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'Typology',
  PRIMARY KEY (`id`),
  KEY `finalclass` (`finalclass`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `typology`
--

LOCK TABLES `typology` WRITE;
/*!40000 ALTER TABLE `typology` DISABLE KEYS */;
/*!40000 ALTER TABLE `typology` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `view_applicationsolution`
--

DROP TABLE IF EXISTS `view_applicationsolution`;
/*!50001 DROP VIEW IF EXISTS `view_applicationsolution`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_applicationsolution` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `description`,
 1 AS `org_id`,
 1 AS `organization_name`,
 1 AS `business_criticity`,
 1 AS `move2production`,
 1 AS `status`,
 1 AS `redundancy`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_approvedchange`
--

DROP TABLE IF EXISTS `view_approvedchange`;
/*!50001 DROP VIEW IF EXISTS `view_approvedchange`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_approvedchange` AS SELECT 
 1 AS `id`,
 1 AS `operational_status`,
 1 AS `ref`,
 1 AS `org_id`,
 1 AS `org_name`,
 1 AS `caller_id`,
 1 AS `caller_name`,
 1 AS `team_id`,
 1 AS `team_name`,
 1 AS `agent_id`,
 1 AS `agent_name`,
 1 AS `title`,
 1 AS `description`,
 1 AS `description_format`,
 1 AS `start_date`,
 1 AS `end_date`,
 1 AS `last_update`,
 1 AS `close_date`,
 1 AS `private_log`,
 1 AS `private_log_index`,
 1 AS `status`,
 1 AS `reason`,
 1 AS `requestor_id`,
 1 AS `requestor_email`,
 1 AS `creation_date`,
 1 AS `impact`,
 1 AS `supervisor_group_id`,
 1 AS `supervisor_group_name`,
 1 AS `supervisor_id`,
 1 AS `supervisor_email`,
 1 AS `manager_group_id`,
 1 AS `manager_group_name`,
 1 AS `manager_id`,
 1 AS `manager_email`,
 1 AS `outage`,
 1 AS `fallback`,
 1 AS `parent_id`,
 1 AS `parent_name`,
 1 AS `approval_date`,
 1 AS `approval_comment`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `caller_id_friendlyname`,
 1 AS `caller_id_obsolescence_flag`,
 1 AS `team_id_friendlyname`,
 1 AS `team_id_obsolescence_flag`,
 1 AS `agent_id_friendlyname`,
 1 AS `agent_id_obsolescence_flag`,
 1 AS `requestor_id_friendlyname`,
 1 AS `requestor_id_obsolescence_flag`,
 1 AS `supervisor_group_id_friendlyname`,
 1 AS `supervisor_group_id_obsolescence_flag`,
 1 AS `supervisor_id_friendlyname`,
 1 AS `supervisor_id_obsolescence_flag`,
 1 AS `manager_group_id_friendlyname`,
 1 AS `manager_group_id_obsolescence_flag`,
 1 AS `manager_id_friendlyname`,
 1 AS `manager_id_obsolescence_flag`,
 1 AS `parent_id_friendlyname`,
 1 AS `parent_id_finalclass_recall`,
 1 AS `parent_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_attachment`
--

DROP TABLE IF EXISTS `view_attachment`;
/*!50001 DROP VIEW IF EXISTS `view_attachment`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_attachment` AS SELECT 
 1 AS `id`,
 1 AS `expire`,
 1 AS `temp_id`,
 1 AS `item_class`,
 1 AS `item_id`,
 1 AS `item_org_id`,
 1 AS `contents`,
 1 AS `contents_data`,
 1 AS `contents_filename`,
 1 AS `friendlyname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_brand`
--

DROP TABLE IF EXISTS `view_brand`;
/*!50001 DROP VIEW IF EXISTS `view_brand`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_brand` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `finalclass`,
 1 AS `friendlyname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_businessprocess`
--

DROP TABLE IF EXISTS `view_businessprocess`;
/*!50001 DROP VIEW IF EXISTS `view_businessprocess`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_businessprocess` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `description`,
 1 AS `org_id`,
 1 AS `organization_name`,
 1 AS `business_criticity`,
 1 AS `move2production`,
 1 AS `status`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_change`
--

DROP TABLE IF EXISTS `view_change`;
/*!50001 DROP VIEW IF EXISTS `view_change`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_change` AS SELECT 
 1 AS `id`,
 1 AS `operational_status`,
 1 AS `ref`,
 1 AS `org_id`,
 1 AS `org_name`,
 1 AS `caller_id`,
 1 AS `caller_name`,
 1 AS `team_id`,
 1 AS `team_name`,
 1 AS `agent_id`,
 1 AS `agent_name`,
 1 AS `title`,
 1 AS `description`,
 1 AS `description_format`,
 1 AS `start_date`,
 1 AS `end_date`,
 1 AS `last_update`,
 1 AS `close_date`,
 1 AS `private_log`,
 1 AS `private_log_index`,
 1 AS `status`,
 1 AS `reason`,
 1 AS `requestor_id`,
 1 AS `requestor_email`,
 1 AS `creation_date`,
 1 AS `impact`,
 1 AS `supervisor_group_id`,
 1 AS `supervisor_group_name`,
 1 AS `supervisor_id`,
 1 AS `supervisor_email`,
 1 AS `manager_group_id`,
 1 AS `manager_group_name`,
 1 AS `manager_id`,
 1 AS `manager_email`,
 1 AS `outage`,
 1 AS `fallback`,
 1 AS `parent_id`,
 1 AS `parent_name`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `caller_id_friendlyname`,
 1 AS `caller_id_obsolescence_flag`,
 1 AS `team_id_friendlyname`,
 1 AS `team_id_obsolescence_flag`,
 1 AS `agent_id_friendlyname`,
 1 AS `agent_id_obsolescence_flag`,
 1 AS `requestor_id_friendlyname`,
 1 AS `requestor_id_obsolescence_flag`,
 1 AS `supervisor_group_id_friendlyname`,
 1 AS `supervisor_group_id_obsolescence_flag`,
 1 AS `supervisor_id_friendlyname`,
 1 AS `supervisor_id_obsolescence_flag`,
 1 AS `manager_group_id_friendlyname`,
 1 AS `manager_group_id_obsolescence_flag`,
 1 AS `manager_id_friendlyname`,
 1 AS `manager_id_obsolescence_flag`,
 1 AS `parent_id_friendlyname`,
 1 AS `parent_id_finalclass_recall`,
 1 AS `parent_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_connectableci`
--

DROP TABLE IF EXISTS `view_connectableci`;
/*!50001 DROP VIEW IF EXISTS `view_connectableci`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_connectableci` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `description`,
 1 AS `org_id`,
 1 AS `organization_name`,
 1 AS `business_criticity`,
 1 AS `move2production`,
 1 AS `serialnumber`,
 1 AS `location_id`,
 1 AS `location_name`,
 1 AS `status`,
 1 AS `brand_id`,
 1 AS `brand_name`,
 1 AS `model_id`,
 1 AS `model_name`,
 1 AS `asset_number`,
 1 AS `purchase_date`,
 1 AS `end_of_warranty`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `location_id_friendlyname`,
 1 AS `location_id_obsolescence_flag`,
 1 AS `brand_id_friendlyname`,
 1 AS `model_id_friendlyname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_contact`
--

DROP TABLE IF EXISTS `view_contact`;
/*!50001 DROP VIEW IF EXISTS `view_contact`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_contact` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `status`,
 1 AS `org_id`,
 1 AS `org_name`,
 1 AS `email`,
 1 AS `phone`,
 1 AS `notify`,
 1 AS `function`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_contacttype`
--

DROP TABLE IF EXISTS `view_contacttype`;
/*!50001 DROP VIEW IF EXISTS `view_contacttype`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_contacttype` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `finalclass`,
 1 AS `friendlyname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_contract`
--

DROP TABLE IF EXISTS `view_contract`;
/*!50001 DROP VIEW IF EXISTS `view_contract`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_contract` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `org_id`,
 1 AS `organization_name`,
 1 AS `description`,
 1 AS `start_date`,
 1 AS `end_date`,
 1 AS `cost`,
 1 AS `cost_currency`,
 1 AS `contracttype_id`,
 1 AS `contracttype_name`,
 1 AS `billing_frequency`,
 1 AS `cost_unit`,
 1 AS `provider_id`,
 1 AS `provider_name`,
 1 AS `status`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `contracttype_id_friendlyname`,
 1 AS `provider_id_friendlyname`,
 1 AS `provider_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_contracttype`
--

DROP TABLE IF EXISTS `view_contracttype`;
/*!50001 DROP VIEW IF EXISTS `view_contracttype`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_contracttype` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `finalclass`,
 1 AS `friendlyname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_customercontract`
--

DROP TABLE IF EXISTS `view_customercontract`;
/*!50001 DROP VIEW IF EXISTS `view_customercontract`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_customercontract` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `org_id`,
 1 AS `organization_name`,
 1 AS `description`,
 1 AS `start_date`,
 1 AS `end_date`,
 1 AS `cost`,
 1 AS `cost_currency`,
 1 AS `contracttype_id`,
 1 AS `contracttype_name`,
 1 AS `billing_frequency`,
 1 AS `cost_unit`,
 1 AS `provider_id`,
 1 AS `provider_name`,
 1 AS `status`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `contracttype_id_friendlyname`,
 1 AS `provider_id_friendlyname`,
 1 AS `provider_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_databaseschema`
--

DROP TABLE IF EXISTS `view_databaseschema`;
/*!50001 DROP VIEW IF EXISTS `view_databaseschema`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_databaseschema` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `description`,
 1 AS `org_id`,
 1 AS `organization_name`,
 1 AS `business_criticity`,
 1 AS `move2production`,
 1 AS `dbserver_id`,
 1 AS `dbserver_name`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `dbserver_id_friendlyname`,
 1 AS `dbserver_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_datacenterdevice`
--

DROP TABLE IF EXISTS `view_datacenterdevice`;
/*!50001 DROP VIEW IF EXISTS `view_datacenterdevice`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_datacenterdevice` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `description`,
 1 AS `org_id`,
 1 AS `organization_name`,
 1 AS `business_criticity`,
 1 AS `move2production`,
 1 AS `serialnumber`,
 1 AS `location_id`,
 1 AS `location_name`,
 1 AS `status`,
 1 AS `brand_id`,
 1 AS `brand_name`,
 1 AS `model_id`,
 1 AS `model_name`,
 1 AS `asset_number`,
 1 AS `purchase_date`,
 1 AS `end_of_warranty`,
 1 AS `rack_id`,
 1 AS `rack_name`,
 1 AS `enclosure_id`,
 1 AS `enclosure_name`,
 1 AS `nb_u`,
 1 AS `managementip`,
 1 AS `powerA_id`,
 1 AS `powerA_name`,
 1 AS `powerB_id`,
 1 AS `powerB_name`,
 1 AS `redundancy`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `location_id_friendlyname`,
 1 AS `location_id_obsolescence_flag`,
 1 AS `brand_id_friendlyname`,
 1 AS `model_id_friendlyname`,
 1 AS `rack_id_friendlyname`,
 1 AS `rack_id_obsolescence_flag`,
 1 AS `enclosure_id_friendlyname`,
 1 AS `enclosure_id_obsolescence_flag`,
 1 AS `powerA_id_friendlyname`,
 1 AS `powerA_id_finalclass_recall`,
 1 AS `powerA_id_obsolescence_flag`,
 1 AS `powerB_id_friendlyname`,
 1 AS `powerB_id_finalclass_recall`,
 1 AS `powerB_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_dbserver`
--

DROP TABLE IF EXISTS `view_dbserver`;
/*!50001 DROP VIEW IF EXISTS `view_dbserver`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_dbserver` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `description`,
 1 AS `org_id`,
 1 AS `organization_name`,
 1 AS `business_criticity`,
 1 AS `move2production`,
 1 AS `system_id`,
 1 AS `system_name`,
 1 AS `software_id`,
 1 AS `software_name`,
 1 AS `softwarelicence_id`,
 1 AS `softwarelicence_name`,
 1 AS `path`,
 1 AS `status`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `system_id_friendlyname`,
 1 AS `system_id_finalclass_recall`,
 1 AS `system_id_obsolescence_flag`,
 1 AS `software_id_friendlyname`,
 1 AS `softwarelicence_id_friendlyname`,
 1 AS `softwarelicence_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_deliverymodel`
--

DROP TABLE IF EXISTS `view_deliverymodel`;
/*!50001 DROP VIEW IF EXISTS `view_deliverymodel`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_deliverymodel` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `org_id`,
 1 AS `organization_name`,
 1 AS `description`,
 1 AS `friendlyname`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_document`
--

DROP TABLE IF EXISTS `view_document`;
/*!50001 DROP VIEW IF EXISTS `view_document`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_document` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `org_id`,
 1 AS `org_name`,
 1 AS `documenttype_id`,
 1 AS `documenttype_name`,
 1 AS `version`,
 1 AS `description`,
 1 AS `status`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `documenttype_id_friendlyname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_documentfile`
--

DROP TABLE IF EXISTS `view_documentfile`;
/*!50001 DROP VIEW IF EXISTS `view_documentfile`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_documentfile` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `org_id`,
 1 AS `org_name`,
 1 AS `documenttype_id`,
 1 AS `documenttype_name`,
 1 AS `version`,
 1 AS `description`,
 1 AS `status`,
 1 AS `file`,
 1 AS `file_data`,
 1 AS `file_filename`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `documenttype_id_friendlyname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_documentnote`
--

DROP TABLE IF EXISTS `view_documentnote`;
/*!50001 DROP VIEW IF EXISTS `view_documentnote`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_documentnote` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `org_id`,
 1 AS `org_name`,
 1 AS `documenttype_id`,
 1 AS `documenttype_name`,
 1 AS `version`,
 1 AS `description`,
 1 AS `status`,
 1 AS `text`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `documenttype_id_friendlyname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_documenttype`
--

DROP TABLE IF EXISTS `view_documenttype`;
/*!50001 DROP VIEW IF EXISTS `view_documenttype`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_documenttype` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `finalclass`,
 1 AS `friendlyname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_documentweb`
--

DROP TABLE IF EXISTS `view_documentweb`;
/*!50001 DROP VIEW IF EXISTS `view_documentweb`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_documentweb` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `org_id`,
 1 AS `org_name`,
 1 AS `documenttype_id`,
 1 AS `documenttype_name`,
 1 AS `version`,
 1 AS `description`,
 1 AS `status`,
 1 AS `url`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `documenttype_id_friendlyname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_emergencychange`
--

DROP TABLE IF EXISTS `view_emergencychange`;
/*!50001 DROP VIEW IF EXISTS `view_emergencychange`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_emergencychange` AS SELECT 
 1 AS `id`,
 1 AS `operational_status`,
 1 AS `ref`,
 1 AS `org_id`,
 1 AS `org_name`,
 1 AS `caller_id`,
 1 AS `caller_name`,
 1 AS `team_id`,
 1 AS `team_name`,
 1 AS `agent_id`,
 1 AS `agent_name`,
 1 AS `title`,
 1 AS `description`,
 1 AS `description_format`,
 1 AS `start_date`,
 1 AS `end_date`,
 1 AS `last_update`,
 1 AS `close_date`,
 1 AS `private_log`,
 1 AS `private_log_index`,
 1 AS `status`,
 1 AS `reason`,
 1 AS `requestor_id`,
 1 AS `requestor_email`,
 1 AS `creation_date`,
 1 AS `impact`,
 1 AS `supervisor_group_id`,
 1 AS `supervisor_group_name`,
 1 AS `supervisor_id`,
 1 AS `supervisor_email`,
 1 AS `manager_group_id`,
 1 AS `manager_group_name`,
 1 AS `manager_id`,
 1 AS `manager_email`,
 1 AS `outage`,
 1 AS `fallback`,
 1 AS `parent_id`,
 1 AS `parent_name`,
 1 AS `approval_date`,
 1 AS `approval_comment`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `caller_id_friendlyname`,
 1 AS `caller_id_obsolescence_flag`,
 1 AS `team_id_friendlyname`,
 1 AS `team_id_obsolescence_flag`,
 1 AS `agent_id_friendlyname`,
 1 AS `agent_id_obsolescence_flag`,
 1 AS `requestor_id_friendlyname`,
 1 AS `requestor_id_obsolescence_flag`,
 1 AS `supervisor_group_id_friendlyname`,
 1 AS `supervisor_group_id_obsolescence_flag`,
 1 AS `supervisor_id_friendlyname`,
 1 AS `supervisor_id_obsolescence_flag`,
 1 AS `manager_group_id_friendlyname`,
 1 AS `manager_group_id_obsolescence_flag`,
 1 AS `manager_id_friendlyname`,
 1 AS `manager_id_obsolescence_flag`,
 1 AS `parent_id_friendlyname`,
 1 AS `parent_id_finalclass_recall`,
 1 AS `parent_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_enclosure`
--

DROP TABLE IF EXISTS `view_enclosure`;
/*!50001 DROP VIEW IF EXISTS `view_enclosure`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_enclosure` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `description`,
 1 AS `org_id`,
 1 AS `organization_name`,
 1 AS `business_criticity`,
 1 AS `move2production`,
 1 AS `serialnumber`,
 1 AS `location_id`,
 1 AS `location_name`,
 1 AS `status`,
 1 AS `brand_id`,
 1 AS `brand_name`,
 1 AS `model_id`,
 1 AS `model_name`,
 1 AS `asset_number`,
 1 AS `purchase_date`,
 1 AS `end_of_warranty`,
 1 AS `rack_id`,
 1 AS `rack_name`,
 1 AS `nb_u`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `location_id_friendlyname`,
 1 AS `location_id_obsolescence_flag`,
 1 AS `brand_id_friendlyname`,
 1 AS `model_id_friendlyname`,
 1 AS `rack_id_friendlyname`,
 1 AS `rack_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_faq`
--

DROP TABLE IF EXISTS `view_faq`;
/*!50001 DROP VIEW IF EXISTS `view_faq`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_faq` AS SELECT 
 1 AS `id`,
 1 AS `title`,
 1 AS `summary`,
 1 AS `description`,
 1 AS `category_id`,
 1 AS `category_name`,
 1 AS `error_code`,
 1 AS `key_words`,
 1 AS `friendlyname`,
 1 AS `category_id_friendlyname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_faqcategory`
--

DROP TABLE IF EXISTS `view_faqcategory`;
/*!50001 DROP VIEW IF EXISTS `view_faqcategory`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_faqcategory` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `friendlyname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_farm`
--

DROP TABLE IF EXISTS `view_farm`;
/*!50001 DROP VIEW IF EXISTS `view_farm`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_farm` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `description`,
 1 AS `org_id`,
 1 AS `organization_name`,
 1 AS `business_criticity`,
 1 AS `move2production`,
 1 AS `status`,
 1 AS `redundancy`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_fiberchannelinterface`
--

DROP TABLE IF EXISTS `view_fiberchannelinterface`;
/*!50001 DROP VIEW IF EXISTS `view_fiberchannelinterface`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_fiberchannelinterface` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `speed`,
 1 AS `topology`,
 1 AS `wwn`,
 1 AS `datacenterdevice_id`,
 1 AS `datacenterdevice_name`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `datacenterdevice_id_friendlyname`,
 1 AS `datacenterdevice_id_finalclass_recall`,
 1 AS `datacenterdevice_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_functionalci`
--

DROP TABLE IF EXISTS `view_functionalci`;
/*!50001 DROP VIEW IF EXISTS `view_functionalci`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_functionalci` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `description`,
 1 AS `org_id`,
 1 AS `organization_name`,
 1 AS `business_criticity`,
 1 AS `move2production`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_group`
--

DROP TABLE IF EXISTS `view_group`;
/*!50001 DROP VIEW IF EXISTS `view_group`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_group` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `status`,
 1 AS `org_id`,
 1 AS `owner_name`,
 1 AS `description`,
 1 AS `type`,
 1 AS `parent_id`,
 1 AS `parent_name`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `parent_id_friendlyname`,
 1 AS `parent_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_hypervisor`
--

DROP TABLE IF EXISTS `view_hypervisor`;
/*!50001 DROP VIEW IF EXISTS `view_hypervisor`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_hypervisor` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `description`,
 1 AS `org_id`,
 1 AS `organization_name`,
 1 AS `business_criticity`,
 1 AS `move2production`,
 1 AS `status`,
 1 AS `farm_id`,
 1 AS `farm_name`,
 1 AS `server_id`,
 1 AS `server_name`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `farm_id_friendlyname`,
 1 AS `farm_id_obsolescence_flag`,
 1 AS `server_id_friendlyname`,
 1 AS `server_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_incident`
--

DROP TABLE IF EXISTS `view_incident`;
/*!50001 DROP VIEW IF EXISTS `view_incident`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_incident` AS SELECT 
 1 AS `id`,
 1 AS `operational_status`,
 1 AS `ref`,
 1 AS `org_id`,
 1 AS `org_name`,
 1 AS `caller_id`,
 1 AS `caller_name`,
 1 AS `team_id`,
 1 AS `team_name`,
 1 AS `agent_id`,
 1 AS `agent_name`,
 1 AS `title`,
 1 AS `description`,
 1 AS `description_format`,
 1 AS `start_date`,
 1 AS `end_date`,
 1 AS `last_update`,
 1 AS `close_date`,
 1 AS `private_log`,
 1 AS `private_log_index`,
 1 AS `status`,
 1 AS `impact`,
 1 AS `priority`,
 1 AS `urgency`,
 1 AS `origin`,
 1 AS `service_id`,
 1 AS `service_name`,
 1 AS `servicesubcategory_id`,
 1 AS `servicesubcategory_name`,
 1 AS `escalation_flag`,
 1 AS `escalation_reason`,
 1 AS `assignment_date`,
 1 AS `resolution_date`,
 1 AS `last_pending_date`,
 1 AS `cumulatedpending`,
 1 AS `cumulatedpending_started`,
 1 AS `cumulatedpending_laststart`,
 1 AS `cumulatedpending_stopped`,
 1 AS `tto`,
 1 AS `tto_started`,
 1 AS `tto_laststart`,
 1 AS `tto_stopped`,
 1 AS `tto_75_deadline`,
 1 AS `tto_75_passed`,
 1 AS `tto_75_triggered`,
 1 AS `tto_75_overrun`,
 1 AS `tto_100_deadline`,
 1 AS `tto_100_passed`,
 1 AS `tto_100_triggered`,
 1 AS `tto_100_overrun`,
 1 AS `ttr`,
 1 AS `ttr_started`,
 1 AS `ttr_laststart`,
 1 AS `ttr_stopped`,
 1 AS `ttr_75_deadline`,
 1 AS `ttr_75_passed`,
 1 AS `ttr_75_triggered`,
 1 AS `ttr_75_overrun`,
 1 AS `ttr_100_deadline`,
 1 AS `ttr_100_passed`,
 1 AS `ttr_100_triggered`,
 1 AS `ttr_100_overrun`,
 1 AS `tto_escalation_deadline`,
 1 AS `sla_tto_passed`,
 1 AS `sla_tto_over`,
 1 AS `ttr_escalation_deadline`,
 1 AS `sla_ttr_passed`,
 1 AS `sla_ttr_over`,
 1 AS `time_spent`,
 1 AS `resolution_code`,
 1 AS `solution`,
 1 AS `pending_reason`,
 1 AS `parent_incident_id`,
 1 AS `parent_incident_ref`,
 1 AS `parent_problem_id`,
 1 AS `parent_problem_ref`,
 1 AS `parent_change_id`,
 1 AS `parent_change_ref`,
 1 AS `public_log`,
 1 AS `public_log_index`,
 1 AS `user_satisfaction`,
 1 AS `user_comment`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `caller_id_friendlyname`,
 1 AS `caller_id_obsolescence_flag`,
 1 AS `team_id_friendlyname`,
 1 AS `team_id_obsolescence_flag`,
 1 AS `agent_id_friendlyname`,
 1 AS `agent_id_obsolescence_flag`,
 1 AS `service_id_friendlyname`,
 1 AS `servicesubcategory_id_friendlyname`,
 1 AS `parent_incident_id_friendlyname`,
 1 AS `parent_incident_id_obsolescence_flag`,
 1 AS `parent_problem_id_friendlyname`,
 1 AS `parent_problem_id_obsolescence_flag`,
 1 AS `parent_change_id_friendlyname`,
 1 AS `parent_change_id_finalclass_recall`,
 1 AS `parent_change_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_iosversion`
--

DROP TABLE IF EXISTS `view_iosversion`;
/*!50001 DROP VIEW IF EXISTS `view_iosversion`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_iosversion` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `brand_id`,
 1 AS `brand_name`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `brand_id_friendlyname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_ipinterface`
--

DROP TABLE IF EXISTS `view_ipinterface`;
/*!50001 DROP VIEW IF EXISTS `view_ipinterface`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_ipinterface` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `ipaddress`,
 1 AS `macaddress`,
 1 AS `comment`,
 1 AS `ipgateway`,
 1 AS `ipmask`,
 1 AS `speed`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_ipphone`
--

DROP TABLE IF EXISTS `view_ipphone`;
/*!50001 DROP VIEW IF EXISTS `view_ipphone`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_ipphone` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `description`,
 1 AS `org_id`,
 1 AS `organization_name`,
 1 AS `business_criticity`,
 1 AS `move2production`,
 1 AS `serialnumber`,
 1 AS `location_id`,
 1 AS `location_name`,
 1 AS `status`,
 1 AS `brand_id`,
 1 AS `brand_name`,
 1 AS `model_id`,
 1 AS `model_name`,
 1 AS `asset_number`,
 1 AS `purchase_date`,
 1 AS `end_of_warranty`,
 1 AS `phonenumber`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `location_id_friendlyname`,
 1 AS `location_id_obsolescence_flag`,
 1 AS `brand_id_friendlyname`,
 1 AS `model_id_friendlyname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_knownerror`
--

DROP TABLE IF EXISTS `view_knownerror`;
/*!50001 DROP VIEW IF EXISTS `view_knownerror`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_knownerror` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `org_id`,
 1 AS `cust_name`,
 1 AS `problem_id`,
 1 AS `problem_ref`,
 1 AS `symptom`,
 1 AS `root_cause`,
 1 AS `workaround`,
 1 AS `solution`,
 1 AS `error_code`,
 1 AS `domain`,
 1 AS `vendor`,
 1 AS `model`,
 1 AS `version`,
 1 AS `friendlyname`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `problem_id_friendlyname`,
 1 AS `problem_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_licence`
--

DROP TABLE IF EXISTS `view_licence`;
/*!50001 DROP VIEW IF EXISTS `view_licence`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_licence` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `org_id`,
 1 AS `organization_name`,
 1 AS `usage_limit`,
 1 AS `description`,
 1 AS `start_date`,
 1 AS `end_date`,
 1 AS `licence_key`,
 1 AS `perpetual`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_lnkapplicationsolutiontobusinessprocess`
--

DROP TABLE IF EXISTS `view_lnkapplicationsolutiontobusinessprocess`;
/*!50001 DROP VIEW IF EXISTS `view_lnkapplicationsolutiontobusinessprocess`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_lnkapplicationsolutiontobusinessprocess` AS SELECT 
 1 AS `id`,
 1 AS `businessprocess_id`,
 1 AS `businessprocess_name`,
 1 AS `applicationsolution_id`,
 1 AS `applicationsolution_name`,
 1 AS `friendlyname`,
 1 AS `businessprocess_id_friendlyname`,
 1 AS `businessprocess_id_obsolescence_flag`,
 1 AS `applicationsolution_id_friendlyname`,
 1 AS `applicationsolution_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_lnkapplicationsolutiontofunctionalci`
--

DROP TABLE IF EXISTS `view_lnkapplicationsolutiontofunctionalci`;
/*!50001 DROP VIEW IF EXISTS `view_lnkapplicationsolutiontofunctionalci`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_lnkapplicationsolutiontofunctionalci` AS SELECT 
 1 AS `id`,
 1 AS `applicationsolution_id`,
 1 AS `applicationsolution_name`,
 1 AS `functionalci_id`,
 1 AS `functionalci_name`,
 1 AS `friendlyname`,
 1 AS `applicationsolution_id_friendlyname`,
 1 AS `applicationsolution_id_obsolescence_flag`,
 1 AS `functionalci_id_friendlyname`,
 1 AS `functionalci_id_finalclass_recall`,
 1 AS `functionalci_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_lnkconnectablecitonetworkdevice`
--

DROP TABLE IF EXISTS `view_lnkconnectablecitonetworkdevice`;
/*!50001 DROP VIEW IF EXISTS `view_lnkconnectablecitonetworkdevice`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_lnkconnectablecitonetworkdevice` AS SELECT 
 1 AS `id`,
 1 AS `networkdevice_id`,
 1 AS `networkdevice_name`,
 1 AS `connectableci_id`,
 1 AS `connectableci_name`,
 1 AS `network_port`,
 1 AS `device_port`,
 1 AS `connection_type`,
 1 AS `friendlyname`,
 1 AS `networkdevice_id_friendlyname`,
 1 AS `networkdevice_id_obsolescence_flag`,
 1 AS `connectableci_id_friendlyname`,
 1 AS `connectableci_id_finalclass_recall`,
 1 AS `connectableci_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_lnkcontacttocontract`
--

DROP TABLE IF EXISTS `view_lnkcontacttocontract`;
/*!50001 DROP VIEW IF EXISTS `view_lnkcontacttocontract`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_lnkcontacttocontract` AS SELECT 
 1 AS `id`,
 1 AS `contract_id`,
 1 AS `contract_name`,
 1 AS `contact_id`,
 1 AS `contact_name`,
 1 AS `friendlyname`,
 1 AS `contract_id_friendlyname`,
 1 AS `contract_id_finalclass_recall`,
 1 AS `contact_id_friendlyname`,
 1 AS `contact_id_finalclass_recall`,
 1 AS `contact_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_lnkcontacttofunctionalci`
--

DROP TABLE IF EXISTS `view_lnkcontacttofunctionalci`;
/*!50001 DROP VIEW IF EXISTS `view_lnkcontacttofunctionalci`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_lnkcontacttofunctionalci` AS SELECT 
 1 AS `id`,
 1 AS `functionalci_id`,
 1 AS `functionalci_name`,
 1 AS `contact_id`,
 1 AS `contact_name`,
 1 AS `friendlyname`,
 1 AS `functionalci_id_friendlyname`,
 1 AS `functionalci_id_finalclass_recall`,
 1 AS `functionalci_id_obsolescence_flag`,
 1 AS `contact_id_friendlyname`,
 1 AS `contact_id_finalclass_recall`,
 1 AS `contact_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_lnkcontacttoservice`
--

DROP TABLE IF EXISTS `view_lnkcontacttoservice`;
/*!50001 DROP VIEW IF EXISTS `view_lnkcontacttoservice`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_lnkcontacttoservice` AS SELECT 
 1 AS `id`,
 1 AS `service_id`,
 1 AS `service_name`,
 1 AS `contact_id`,
 1 AS `contact_name`,
 1 AS `friendlyname`,
 1 AS `service_id_friendlyname`,
 1 AS `contact_id_friendlyname`,
 1 AS `contact_id_finalclass_recall`,
 1 AS `contact_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_lnkcontacttoticket`
--

DROP TABLE IF EXISTS `view_lnkcontacttoticket`;
/*!50001 DROP VIEW IF EXISTS `view_lnkcontacttoticket`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_lnkcontacttoticket` AS SELECT 
 1 AS `id`,
 1 AS `ticket_id`,
 1 AS `ticket_ref`,
 1 AS `contact_id`,
 1 AS `contact_email`,
 1 AS `role`,
 1 AS `role_code`,
 1 AS `friendlyname`,
 1 AS `ticket_id_friendlyname`,
 1 AS `ticket_id_finalclass_recall`,
 1 AS `ticket_id_obsolescence_flag`,
 1 AS `contact_id_friendlyname`,
 1 AS `contact_id_finalclass_recall`,
 1 AS `contact_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_lnkcontracttodocument`
--

DROP TABLE IF EXISTS `view_lnkcontracttodocument`;
/*!50001 DROP VIEW IF EXISTS `view_lnkcontracttodocument`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_lnkcontracttodocument` AS SELECT 
 1 AS `id`,
 1 AS `contract_id`,
 1 AS `contract_name`,
 1 AS `document_id`,
 1 AS `document_name`,
 1 AS `friendlyname`,
 1 AS `contract_id_friendlyname`,
 1 AS `contract_id_finalclass_recall`,
 1 AS `document_id_friendlyname`,
 1 AS `document_id_finalclass_recall`,
 1 AS `document_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_lnkcustomercontracttofunctionalci`
--

DROP TABLE IF EXISTS `view_lnkcustomercontracttofunctionalci`;
/*!50001 DROP VIEW IF EXISTS `view_lnkcustomercontracttofunctionalci`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_lnkcustomercontracttofunctionalci` AS SELECT 
 1 AS `id`,
 1 AS `customercontract_id`,
 1 AS `customercontract_name`,
 1 AS `functionalci_id`,
 1 AS `functionalci_name`,
 1 AS `friendlyname`,
 1 AS `customercontract_id_friendlyname`,
 1 AS `functionalci_id_friendlyname`,
 1 AS `functionalci_id_finalclass_recall`,
 1 AS `functionalci_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_lnkcustomercontracttoprovidercontract`
--

DROP TABLE IF EXISTS `view_lnkcustomercontracttoprovidercontract`;
/*!50001 DROP VIEW IF EXISTS `view_lnkcustomercontracttoprovidercontract`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_lnkcustomercontracttoprovidercontract` AS SELECT 
 1 AS `id`,
 1 AS `customercontract_id`,
 1 AS `customercontract_name`,
 1 AS `providercontract_id`,
 1 AS `providercontract_name`,
 1 AS `friendlyname`,
 1 AS `customercontract_id_friendlyname`,
 1 AS `providercontract_id_friendlyname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_lnkcustomercontracttoservice`
--

DROP TABLE IF EXISTS `view_lnkcustomercontracttoservice`;
/*!50001 DROP VIEW IF EXISTS `view_lnkcustomercontracttoservice`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_lnkcustomercontracttoservice` AS SELECT 
 1 AS `id`,
 1 AS `customercontract_id`,
 1 AS `customercontract_name`,
 1 AS `service_id`,
 1 AS `service_name`,
 1 AS `sla_id`,
 1 AS `sla_name`,
 1 AS `friendlyname`,
 1 AS `customercontract_id_friendlyname`,
 1 AS `service_id_friendlyname`,
 1 AS `sla_id_friendlyname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_lnkdeliverymodeltocontact`
--

DROP TABLE IF EXISTS `view_lnkdeliverymodeltocontact`;
/*!50001 DROP VIEW IF EXISTS `view_lnkdeliverymodeltocontact`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_lnkdeliverymodeltocontact` AS SELECT 
 1 AS `id`,
 1 AS `deliverymodel_id`,
 1 AS `deliverymodel_name`,
 1 AS `contact_id`,
 1 AS `contact_name`,
 1 AS `role_id`,
 1 AS `role_name`,
 1 AS `friendlyname`,
 1 AS `deliverymodel_id_friendlyname`,
 1 AS `contact_id_friendlyname`,
 1 AS `contact_id_finalclass_recall`,
 1 AS `contact_id_obsolescence_flag`,
 1 AS `role_id_friendlyname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_lnkdocumenttoerror`
--

DROP TABLE IF EXISTS `view_lnkdocumenttoerror`;
/*!50001 DROP VIEW IF EXISTS `view_lnkdocumenttoerror`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_lnkdocumenttoerror` AS SELECT 
 1 AS `id`,
 1 AS `document_id`,
 1 AS `document_name`,
 1 AS `error_id`,
 1 AS `error_name`,
 1 AS `link_type`,
 1 AS `friendlyname`,
 1 AS `document_id_friendlyname`,
 1 AS `document_id_finalclass_recall`,
 1 AS `document_id_obsolescence_flag`,
 1 AS `error_id_friendlyname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_lnkdocumenttofunctionalci`
--

DROP TABLE IF EXISTS `view_lnkdocumenttofunctionalci`;
/*!50001 DROP VIEW IF EXISTS `view_lnkdocumenttofunctionalci`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_lnkdocumenttofunctionalci` AS SELECT 
 1 AS `id`,
 1 AS `functionalci_id`,
 1 AS `functionalci_name`,
 1 AS `document_id`,
 1 AS `document_name`,
 1 AS `friendlyname`,
 1 AS `functionalci_id_friendlyname`,
 1 AS `functionalci_id_finalclass_recall`,
 1 AS `functionalci_id_obsolescence_flag`,
 1 AS `document_id_friendlyname`,
 1 AS `document_id_finalclass_recall`,
 1 AS `document_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_lnkdocumenttolicence`
--

DROP TABLE IF EXISTS `view_lnkdocumenttolicence`;
/*!50001 DROP VIEW IF EXISTS `view_lnkdocumenttolicence`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_lnkdocumenttolicence` AS SELECT 
 1 AS `id`,
 1 AS `licence_id`,
 1 AS `licence_name`,
 1 AS `document_id`,
 1 AS `document_name`,
 1 AS `friendlyname`,
 1 AS `licence_id_friendlyname`,
 1 AS `licence_id_finalclass_recall`,
 1 AS `licence_id_obsolescence_flag`,
 1 AS `document_id_friendlyname`,
 1 AS `document_id_finalclass_recall`,
 1 AS `document_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_lnkdocumenttopatch`
--

DROP TABLE IF EXISTS `view_lnkdocumenttopatch`;
/*!50001 DROP VIEW IF EXISTS `view_lnkdocumenttopatch`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_lnkdocumenttopatch` AS SELECT 
 1 AS `id`,
 1 AS `patch_id`,
 1 AS `patch_name`,
 1 AS `document_id`,
 1 AS `document_name`,
 1 AS `friendlyname`,
 1 AS `patch_id_friendlyname`,
 1 AS `patch_id_finalclass_recall`,
 1 AS `document_id_friendlyname`,
 1 AS `document_id_finalclass_recall`,
 1 AS `document_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_lnkdocumenttoservice`
--

DROP TABLE IF EXISTS `view_lnkdocumenttoservice`;
/*!50001 DROP VIEW IF EXISTS `view_lnkdocumenttoservice`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_lnkdocumenttoservice` AS SELECT 
 1 AS `id`,
 1 AS `service_id`,
 1 AS `service_name`,
 1 AS `document_id`,
 1 AS `document_name`,
 1 AS `friendlyname`,
 1 AS `service_id_friendlyname`,
 1 AS `document_id_friendlyname`,
 1 AS `document_id_finalclass_recall`,
 1 AS `document_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_lnkdocumenttosoftware`
--

DROP TABLE IF EXISTS `view_lnkdocumenttosoftware`;
/*!50001 DROP VIEW IF EXISTS `view_lnkdocumenttosoftware`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_lnkdocumenttosoftware` AS SELECT 
 1 AS `id`,
 1 AS `software_id`,
 1 AS `software_name`,
 1 AS `document_id`,
 1 AS `document_name`,
 1 AS `friendlyname`,
 1 AS `software_id_friendlyname`,
 1 AS `document_id_friendlyname`,
 1 AS `document_id_finalclass_recall`,
 1 AS `document_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_lnkerrortofunctionalci`
--

DROP TABLE IF EXISTS `view_lnkerrortofunctionalci`;
/*!50001 DROP VIEW IF EXISTS `view_lnkerrortofunctionalci`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_lnkerrortofunctionalci` AS SELECT 
 1 AS `id`,
 1 AS `functionalci_id`,
 1 AS `functionalci_name`,
 1 AS `error_id`,
 1 AS `error_name`,
 1 AS `reason`,
 1 AS `friendlyname`,
 1 AS `functionalci_id_friendlyname`,
 1 AS `functionalci_id_finalclass_recall`,
 1 AS `functionalci_id_obsolescence_flag`,
 1 AS `error_id_friendlyname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_lnkfunctionalcitoospatch`
--

DROP TABLE IF EXISTS `view_lnkfunctionalcitoospatch`;
/*!50001 DROP VIEW IF EXISTS `view_lnkfunctionalcitoospatch`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_lnkfunctionalcitoospatch` AS SELECT 
 1 AS `id`,
 1 AS `ospatch_id`,
 1 AS `ospatch_name`,
 1 AS `functionalci_id`,
 1 AS `functionalci_name`,
 1 AS `friendlyname`,
 1 AS `ospatch_id_friendlyname`,
 1 AS `functionalci_id_friendlyname`,
 1 AS `functionalci_id_finalclass_recall`,
 1 AS `functionalci_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_lnkfunctionalcitoprovidercontract`
--

DROP TABLE IF EXISTS `view_lnkfunctionalcitoprovidercontract`;
/*!50001 DROP VIEW IF EXISTS `view_lnkfunctionalcitoprovidercontract`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_lnkfunctionalcitoprovidercontract` AS SELECT 
 1 AS `id`,
 1 AS `providercontract_id`,
 1 AS `providercontract_name`,
 1 AS `functionalci_id`,
 1 AS `functionalci_name`,
 1 AS `friendlyname`,
 1 AS `providercontract_id_friendlyname`,
 1 AS `functionalci_id_friendlyname`,
 1 AS `functionalci_id_finalclass_recall`,
 1 AS `functionalci_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_lnkfunctionalcitoticket`
--

DROP TABLE IF EXISTS `view_lnkfunctionalcitoticket`;
/*!50001 DROP VIEW IF EXISTS `view_lnkfunctionalcitoticket`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_lnkfunctionalcitoticket` AS SELECT 
 1 AS `id`,
 1 AS `ticket_id`,
 1 AS `ticket_ref`,
 1 AS `ticket_title`,
 1 AS `functionalci_id`,
 1 AS `functionalci_name`,
 1 AS `impact`,
 1 AS `impact_code`,
 1 AS `friendlyname`,
 1 AS `ticket_id_friendlyname`,
 1 AS `ticket_id_finalclass_recall`,
 1 AS `ticket_id_obsolescence_flag`,
 1 AS `functionalci_id_friendlyname`,
 1 AS `functionalci_id_finalclass_recall`,
 1 AS `functionalci_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_lnkgrouptoci`
--

DROP TABLE IF EXISTS `view_lnkgrouptoci`;
/*!50001 DROP VIEW IF EXISTS `view_lnkgrouptoci`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_lnkgrouptoci` AS SELECT 
 1 AS `id`,
 1 AS `group_id`,
 1 AS `group_name`,
 1 AS `ci_id`,
 1 AS `ci_name`,
 1 AS `reason`,
 1 AS `friendlyname`,
 1 AS `group_id_friendlyname`,
 1 AS `group_id_obsolescence_flag`,
 1 AS `ci_id_friendlyname`,
 1 AS `ci_id_finalclass_recall`,
 1 AS `ci_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_lnkpersontoteam`
--

DROP TABLE IF EXISTS `view_lnkpersontoteam`;
/*!50001 DROP VIEW IF EXISTS `view_lnkpersontoteam`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_lnkpersontoteam` AS SELECT 
 1 AS `id`,
 1 AS `team_id`,
 1 AS `team_name`,
 1 AS `person_id`,
 1 AS `person_name`,
 1 AS `role_id`,
 1 AS `role_name`,
 1 AS `friendlyname`,
 1 AS `team_id_friendlyname`,
 1 AS `team_id_obsolescence_flag`,
 1 AS `person_id_friendlyname`,
 1 AS `person_id_obsolescence_flag`,
 1 AS `role_id_friendlyname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_lnkphysicalinterfacetovlan`
--

DROP TABLE IF EXISTS `view_lnkphysicalinterfacetovlan`;
/*!50001 DROP VIEW IF EXISTS `view_lnkphysicalinterfacetovlan`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_lnkphysicalinterfacetovlan` AS SELECT 
 1 AS `id`,
 1 AS `physicalinterface_id`,
 1 AS `physicalinterface_name`,
 1 AS `physicalinterface_device_id`,
 1 AS `physicalinterface_device_name`,
 1 AS `vlan_id`,
 1 AS `vlan_tag`,
 1 AS `friendlyname`,
 1 AS `physicalinterface_id_friendlyname`,
 1 AS `physicalinterface_id_obsolescence_flag`,
 1 AS `physicalinterface_device_id_friendlyname`,
 1 AS `physicalinterface_device_id_obsolescence_flag`,
 1 AS `vlan_id_friendlyname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_lnksantodatacenterdevice`
--

DROP TABLE IF EXISTS `view_lnksantodatacenterdevice`;
/*!50001 DROP VIEW IF EXISTS `view_lnksantodatacenterdevice`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_lnksantodatacenterdevice` AS SELECT 
 1 AS `id`,
 1 AS `san_id`,
 1 AS `san_name`,
 1 AS `datacenterdevice_id`,
 1 AS `datacenterdevice_name`,
 1 AS `san_port`,
 1 AS `datacenterdevice_port`,
 1 AS `friendlyname`,
 1 AS `san_id_friendlyname`,
 1 AS `san_id_obsolescence_flag`,
 1 AS `datacenterdevice_id_friendlyname`,
 1 AS `datacenterdevice_id_finalclass_recall`,
 1 AS `datacenterdevice_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_lnkservertovolume`
--

DROP TABLE IF EXISTS `view_lnkservertovolume`;
/*!50001 DROP VIEW IF EXISTS `view_lnkservertovolume`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_lnkservertovolume` AS SELECT 
 1 AS `id`,
 1 AS `volume_id`,
 1 AS `volume_name`,
 1 AS `server_id`,
 1 AS `server_name`,
 1 AS `size_used`,
 1 AS `friendlyname`,
 1 AS `volume_id_friendlyname`,
 1 AS `volume_id_obsolescence_flag`,
 1 AS `server_id_friendlyname`,
 1 AS `server_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_lnkslatoslt`
--

DROP TABLE IF EXISTS `view_lnkslatoslt`;
/*!50001 DROP VIEW IF EXISTS `view_lnkslatoslt`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_lnkslatoslt` AS SELECT 
 1 AS `id`,
 1 AS `sla_id`,
 1 AS `sla_name`,
 1 AS `slt_id`,
 1 AS `slt_name`,
 1 AS `slt_metric`,
 1 AS `slt_request_type`,
 1 AS `slt_ticket_priority`,
 1 AS `slt_value`,
 1 AS `slt_value_unit`,
 1 AS `friendlyname`,
 1 AS `sla_id_friendlyname`,
 1 AS `slt_id_friendlyname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_lnksoftwareinstancetosoftwarepatch`
--

DROP TABLE IF EXISTS `view_lnksoftwareinstancetosoftwarepatch`;
/*!50001 DROP VIEW IF EXISTS `view_lnksoftwareinstancetosoftwarepatch`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_lnksoftwareinstancetosoftwarepatch` AS SELECT 
 1 AS `id`,
 1 AS `softwarepatch_id`,
 1 AS `softwarepatch_name`,
 1 AS `softwareinstance_id`,
 1 AS `softwareinstance_name`,
 1 AS `friendlyname`,
 1 AS `softwarepatch_id_friendlyname`,
 1 AS `softwareinstance_id_friendlyname`,
 1 AS `softwareinstance_id_finalclass_recall`,
 1 AS `softwareinstance_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_lnksubnettovlan`
--

DROP TABLE IF EXISTS `view_lnksubnettovlan`;
/*!50001 DROP VIEW IF EXISTS `view_lnksubnettovlan`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_lnksubnettovlan` AS SELECT 
 1 AS `id`,
 1 AS `subnet_id`,
 1 AS `subnet_ip`,
 1 AS `subnet_name`,
 1 AS `vlan_id`,
 1 AS `vlan_tag`,
 1 AS `friendlyname`,
 1 AS `subnet_id_friendlyname`,
 1 AS `vlan_id_friendlyname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_lnkvirtualdevicetovolume`
--

DROP TABLE IF EXISTS `view_lnkvirtualdevicetovolume`;
/*!50001 DROP VIEW IF EXISTS `view_lnkvirtualdevicetovolume`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_lnkvirtualdevicetovolume` AS SELECT 
 1 AS `id`,
 1 AS `volume_id`,
 1 AS `volume_name`,
 1 AS `virtualdevice_id`,
 1 AS `virtualdevice_name`,
 1 AS `size_used`,
 1 AS `friendlyname`,
 1 AS `volume_id_friendlyname`,
 1 AS `volume_id_obsolescence_flag`,
 1 AS `virtualdevice_id_friendlyname`,
 1 AS `virtualdevice_id_finalclass_recall`,
 1 AS `virtualdevice_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_location`
--

DROP TABLE IF EXISTS `view_location`;
/*!50001 DROP VIEW IF EXISTS `view_location`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_location` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `status`,
 1 AS `org_id`,
 1 AS `org_name`,
 1 AS `address`,
 1 AS `postal_code`,
 1 AS `city`,
 1 AS `country`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_logicalinterface`
--

DROP TABLE IF EXISTS `view_logicalinterface`;
/*!50001 DROP VIEW IF EXISTS `view_logicalinterface`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_logicalinterface` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `ipaddress`,
 1 AS `macaddress`,
 1 AS `comment`,
 1 AS `ipgateway`,
 1 AS `ipmask`,
 1 AS `speed`,
 1 AS `virtualmachine_id`,
 1 AS `virtualmachine_name`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `virtualmachine_id_friendlyname`,
 1 AS `virtualmachine_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_logicalvolume`
--

DROP TABLE IF EXISTS `view_logicalvolume`;
/*!50001 DROP VIEW IF EXISTS `view_logicalvolume`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_logicalvolume` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `lun_id`,
 1 AS `description`,
 1 AS `raid_level`,
 1 AS `size`,
 1 AS `storagesystem_id`,
 1 AS `storagesystem_name`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `storagesystem_id_friendlyname`,
 1 AS `storagesystem_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_middleware`
--

DROP TABLE IF EXISTS `view_middleware`;
/*!50001 DROP VIEW IF EXISTS `view_middleware`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_middleware` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `description`,
 1 AS `org_id`,
 1 AS `organization_name`,
 1 AS `business_criticity`,
 1 AS `move2production`,
 1 AS `system_id`,
 1 AS `system_name`,
 1 AS `software_id`,
 1 AS `software_name`,
 1 AS `softwarelicence_id`,
 1 AS `softwarelicence_name`,
 1 AS `path`,
 1 AS `status`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `system_id_friendlyname`,
 1 AS `system_id_finalclass_recall`,
 1 AS `system_id_obsolescence_flag`,
 1 AS `software_id_friendlyname`,
 1 AS `softwarelicence_id_friendlyname`,
 1 AS `softwarelicence_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_middlewareinstance`
--

DROP TABLE IF EXISTS `view_middlewareinstance`;
/*!50001 DROP VIEW IF EXISTS `view_middlewareinstance`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_middlewareinstance` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `description`,
 1 AS `org_id`,
 1 AS `organization_name`,
 1 AS `business_criticity`,
 1 AS `move2production`,
 1 AS `middleware_id`,
 1 AS `middleware_name`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `middleware_id_friendlyname`,
 1 AS `middleware_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_mobilephone`
--

DROP TABLE IF EXISTS `view_mobilephone`;
/*!50001 DROP VIEW IF EXISTS `view_mobilephone`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_mobilephone` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `description`,
 1 AS `org_id`,
 1 AS `organization_name`,
 1 AS `business_criticity`,
 1 AS `move2production`,
 1 AS `serialnumber`,
 1 AS `location_id`,
 1 AS `location_name`,
 1 AS `status`,
 1 AS `brand_id`,
 1 AS `brand_name`,
 1 AS `model_id`,
 1 AS `model_name`,
 1 AS `asset_number`,
 1 AS `purchase_date`,
 1 AS `end_of_warranty`,
 1 AS `phonenumber`,
 1 AS `imei`,
 1 AS `hw_pin`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `location_id_friendlyname`,
 1 AS `location_id_obsolescence_flag`,
 1 AS `brand_id_friendlyname`,
 1 AS `model_id_friendlyname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_model`
--

DROP TABLE IF EXISTS `view_model`;
/*!50001 DROP VIEW IF EXISTS `view_model`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_model` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `brand_id`,
 1 AS `brand_name`,
 1 AS `type`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `brand_id_friendlyname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_nas`
--

DROP TABLE IF EXISTS `view_nas`;
/*!50001 DROP VIEW IF EXISTS `view_nas`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_nas` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `description`,
 1 AS `org_id`,
 1 AS `organization_name`,
 1 AS `business_criticity`,
 1 AS `move2production`,
 1 AS `serialnumber`,
 1 AS `location_id`,
 1 AS `location_name`,
 1 AS `status`,
 1 AS `brand_id`,
 1 AS `brand_name`,
 1 AS `model_id`,
 1 AS `model_name`,
 1 AS `asset_number`,
 1 AS `purchase_date`,
 1 AS `end_of_warranty`,
 1 AS `rack_id`,
 1 AS `rack_name`,
 1 AS `enclosure_id`,
 1 AS `enclosure_name`,
 1 AS `nb_u`,
 1 AS `managementip`,
 1 AS `powerA_id`,
 1 AS `powerA_name`,
 1 AS `powerB_id`,
 1 AS `powerB_name`,
 1 AS `redundancy`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `location_id_friendlyname`,
 1 AS `location_id_obsolescence_flag`,
 1 AS `brand_id_friendlyname`,
 1 AS `model_id_friendlyname`,
 1 AS `rack_id_friendlyname`,
 1 AS `rack_id_obsolescence_flag`,
 1 AS `enclosure_id_friendlyname`,
 1 AS `enclosure_id_obsolescence_flag`,
 1 AS `powerA_id_friendlyname`,
 1 AS `powerA_id_finalclass_recall`,
 1 AS `powerA_id_obsolescence_flag`,
 1 AS `powerB_id_friendlyname`,
 1 AS `powerB_id_finalclass_recall`,
 1 AS `powerB_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_nasfilesystem`
--

DROP TABLE IF EXISTS `view_nasfilesystem`;
/*!50001 DROP VIEW IF EXISTS `view_nasfilesystem`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_nasfilesystem` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `description`,
 1 AS `raid_level`,
 1 AS `size`,
 1 AS `nas_id`,
 1 AS `nas_name`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `nas_id_friendlyname`,
 1 AS `nas_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_networkdevice`
--

DROP TABLE IF EXISTS `view_networkdevice`;
/*!50001 DROP VIEW IF EXISTS `view_networkdevice`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_networkdevice` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `description`,
 1 AS `org_id`,
 1 AS `organization_name`,
 1 AS `business_criticity`,
 1 AS `move2production`,
 1 AS `serialnumber`,
 1 AS `location_id`,
 1 AS `location_name`,
 1 AS `status`,
 1 AS `brand_id`,
 1 AS `brand_name`,
 1 AS `model_id`,
 1 AS `model_name`,
 1 AS `asset_number`,
 1 AS `purchase_date`,
 1 AS `end_of_warranty`,
 1 AS `rack_id`,
 1 AS `rack_name`,
 1 AS `enclosure_id`,
 1 AS `enclosure_name`,
 1 AS `nb_u`,
 1 AS `managementip`,
 1 AS `powerA_id`,
 1 AS `powerA_name`,
 1 AS `powerB_id`,
 1 AS `powerB_name`,
 1 AS `redundancy`,
 1 AS `networkdevicetype_id`,
 1 AS `networkdevicetype_name`,
 1 AS `iosversion_id`,
 1 AS `iosversion_name`,
 1 AS `ram`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `location_id_friendlyname`,
 1 AS `location_id_obsolescence_flag`,
 1 AS `brand_id_friendlyname`,
 1 AS `model_id_friendlyname`,
 1 AS `rack_id_friendlyname`,
 1 AS `rack_id_obsolescence_flag`,
 1 AS `enclosure_id_friendlyname`,
 1 AS `enclosure_id_obsolescence_flag`,
 1 AS `powerA_id_friendlyname`,
 1 AS `powerA_id_finalclass_recall`,
 1 AS `powerA_id_obsolescence_flag`,
 1 AS `powerB_id_friendlyname`,
 1 AS `powerB_id_finalclass_recall`,
 1 AS `powerB_id_obsolescence_flag`,
 1 AS `networkdevicetype_id_friendlyname`,
 1 AS `iosversion_id_friendlyname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_networkdevicetype`
--

DROP TABLE IF EXISTS `view_networkdevicetype`;
/*!50001 DROP VIEW IF EXISTS `view_networkdevicetype`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_networkdevicetype` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `finalclass`,
 1 AS `friendlyname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_networkinterface`
--

DROP TABLE IF EXISTS `view_networkinterface`;
/*!50001 DROP VIEW IF EXISTS `view_networkinterface`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_networkinterface` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_normalchange`
--

DROP TABLE IF EXISTS `view_normalchange`;
/*!50001 DROP VIEW IF EXISTS `view_normalchange`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_normalchange` AS SELECT 
 1 AS `id`,
 1 AS `operational_status`,
 1 AS `ref`,
 1 AS `org_id`,
 1 AS `org_name`,
 1 AS `caller_id`,
 1 AS `caller_name`,
 1 AS `team_id`,
 1 AS `team_name`,
 1 AS `agent_id`,
 1 AS `agent_name`,
 1 AS `title`,
 1 AS `description`,
 1 AS `description_format`,
 1 AS `start_date`,
 1 AS `end_date`,
 1 AS `last_update`,
 1 AS `close_date`,
 1 AS `private_log`,
 1 AS `private_log_index`,
 1 AS `status`,
 1 AS `reason`,
 1 AS `requestor_id`,
 1 AS `requestor_email`,
 1 AS `creation_date`,
 1 AS `impact`,
 1 AS `supervisor_group_id`,
 1 AS `supervisor_group_name`,
 1 AS `supervisor_id`,
 1 AS `supervisor_email`,
 1 AS `manager_group_id`,
 1 AS `manager_group_name`,
 1 AS `manager_id`,
 1 AS `manager_email`,
 1 AS `outage`,
 1 AS `fallback`,
 1 AS `parent_id`,
 1 AS `parent_name`,
 1 AS `approval_date`,
 1 AS `approval_comment`,
 1 AS `acceptance_date`,
 1 AS `acceptance_comment`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `caller_id_friendlyname`,
 1 AS `caller_id_obsolescence_flag`,
 1 AS `team_id_friendlyname`,
 1 AS `team_id_obsolescence_flag`,
 1 AS `agent_id_friendlyname`,
 1 AS `agent_id_obsolescence_flag`,
 1 AS `requestor_id_friendlyname`,
 1 AS `requestor_id_obsolescence_flag`,
 1 AS `supervisor_group_id_friendlyname`,
 1 AS `supervisor_group_id_obsolescence_flag`,
 1 AS `supervisor_id_friendlyname`,
 1 AS `supervisor_id_obsolescence_flag`,
 1 AS `manager_group_id_friendlyname`,
 1 AS `manager_group_id_obsolescence_flag`,
 1 AS `manager_id_friendlyname`,
 1 AS `manager_id_obsolescence_flag`,
 1 AS `parent_id_friendlyname`,
 1 AS `parent_id_finalclass_recall`,
 1 AS `parent_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_organization`
--

DROP TABLE IF EXISTS `view_organization`;
/*!50001 DROP VIEW IF EXISTS `view_organization`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_organization` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `code`,
 1 AS `status`,
 1 AS `parent_id`,
 1 AS `parent_name`,
 1 AS `deliverymodel_id`,
 1 AS `deliverymodel_name`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `parent_id_friendlyname`,
 1 AS `parent_id_obsolescence_flag`,
 1 AS `deliverymodel_id_friendlyname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_osfamily`
--

DROP TABLE IF EXISTS `view_osfamily`;
/*!50001 DROP VIEW IF EXISTS `view_osfamily`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_osfamily` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `finalclass`,
 1 AS `friendlyname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_oslicence`
--

DROP TABLE IF EXISTS `view_oslicence`;
/*!50001 DROP VIEW IF EXISTS `view_oslicence`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_oslicence` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `org_id`,
 1 AS `organization_name`,
 1 AS `usage_limit`,
 1 AS `description`,
 1 AS `start_date`,
 1 AS `end_date`,
 1 AS `licence_key`,
 1 AS `perpetual`,
 1 AS `osversion_id`,
 1 AS `osversion_name`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `osversion_id_friendlyname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_ospatch`
--

DROP TABLE IF EXISTS `view_ospatch`;
/*!50001 DROP VIEW IF EXISTS `view_ospatch`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_ospatch` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `description`,
 1 AS `osversion_id`,
 1 AS `osversion_name`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `osversion_id_friendlyname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_osversion`
--

DROP TABLE IF EXISTS `view_osversion`;
/*!50001 DROP VIEW IF EXISTS `view_osversion`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_osversion` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `osfamily_id`,
 1 AS `osfamily_name`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `osfamily_id_friendlyname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_othersoftware`
--

DROP TABLE IF EXISTS `view_othersoftware`;
/*!50001 DROP VIEW IF EXISTS `view_othersoftware`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_othersoftware` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `description`,
 1 AS `org_id`,
 1 AS `organization_name`,
 1 AS `business_criticity`,
 1 AS `move2production`,
 1 AS `system_id`,
 1 AS `system_name`,
 1 AS `software_id`,
 1 AS `software_name`,
 1 AS `softwarelicence_id`,
 1 AS `softwarelicence_name`,
 1 AS `path`,
 1 AS `status`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `system_id_friendlyname`,
 1 AS `system_id_finalclass_recall`,
 1 AS `system_id_obsolescence_flag`,
 1 AS `software_id_friendlyname`,
 1 AS `softwarelicence_id_friendlyname`,
 1 AS `softwarelicence_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_patch`
--

DROP TABLE IF EXISTS `view_patch`;
/*!50001 DROP VIEW IF EXISTS `view_patch`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_patch` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `description`,
 1 AS `finalclass`,
 1 AS `friendlyname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_pc`
--

DROP TABLE IF EXISTS `view_pc`;
/*!50001 DROP VIEW IF EXISTS `view_pc`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_pc` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `description`,
 1 AS `org_id`,
 1 AS `organization_name`,
 1 AS `business_criticity`,
 1 AS `move2production`,
 1 AS `serialnumber`,
 1 AS `location_id`,
 1 AS `location_name`,
 1 AS `status`,
 1 AS `brand_id`,
 1 AS `brand_name`,
 1 AS `model_id`,
 1 AS `model_name`,
 1 AS `asset_number`,
 1 AS `purchase_date`,
 1 AS `end_of_warranty`,
 1 AS `osfamily_id`,
 1 AS `osfamily_name`,
 1 AS `osversion_id`,
 1 AS `osversion_name`,
 1 AS `cpu`,
 1 AS `ram`,
 1 AS `type`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `location_id_friendlyname`,
 1 AS `location_id_obsolescence_flag`,
 1 AS `brand_id_friendlyname`,
 1 AS `model_id_friendlyname`,
 1 AS `osfamily_id_friendlyname`,
 1 AS `osversion_id_friendlyname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_pcsoftware`
--

DROP TABLE IF EXISTS `view_pcsoftware`;
/*!50001 DROP VIEW IF EXISTS `view_pcsoftware`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_pcsoftware` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `description`,
 1 AS `org_id`,
 1 AS `organization_name`,
 1 AS `business_criticity`,
 1 AS `move2production`,
 1 AS `system_id`,
 1 AS `system_name`,
 1 AS `software_id`,
 1 AS `software_name`,
 1 AS `softwarelicence_id`,
 1 AS `softwarelicence_name`,
 1 AS `path`,
 1 AS `status`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `system_id_friendlyname`,
 1 AS `system_id_finalclass_recall`,
 1 AS `system_id_obsolescence_flag`,
 1 AS `software_id_friendlyname`,
 1 AS `softwarelicence_id_friendlyname`,
 1 AS `softwarelicence_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_pdu`
--

DROP TABLE IF EXISTS `view_pdu`;
/*!50001 DROP VIEW IF EXISTS `view_pdu`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_pdu` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `description`,
 1 AS `org_id`,
 1 AS `organization_name`,
 1 AS `business_criticity`,
 1 AS `move2production`,
 1 AS `serialnumber`,
 1 AS `location_id`,
 1 AS `location_name`,
 1 AS `status`,
 1 AS `brand_id`,
 1 AS `brand_name`,
 1 AS `model_id`,
 1 AS `model_name`,
 1 AS `asset_number`,
 1 AS `purchase_date`,
 1 AS `end_of_warranty`,
 1 AS `rack_id`,
 1 AS `rack_name`,
 1 AS `powerstart_id`,
 1 AS `powerstart_name`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `location_id_friendlyname`,
 1 AS `location_id_obsolescence_flag`,
 1 AS `brand_id_friendlyname`,
 1 AS `model_id_friendlyname`,
 1 AS `rack_id_friendlyname`,
 1 AS `rack_id_obsolescence_flag`,
 1 AS `powerstart_id_friendlyname`,
 1 AS `powerstart_id_finalclass_recall`,
 1 AS `powerstart_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_peripheral`
--

DROP TABLE IF EXISTS `view_peripheral`;
/*!50001 DROP VIEW IF EXISTS `view_peripheral`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_peripheral` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `description`,
 1 AS `org_id`,
 1 AS `organization_name`,
 1 AS `business_criticity`,
 1 AS `move2production`,
 1 AS `serialnumber`,
 1 AS `location_id`,
 1 AS `location_name`,
 1 AS `status`,
 1 AS `brand_id`,
 1 AS `brand_name`,
 1 AS `model_id`,
 1 AS `model_name`,
 1 AS `asset_number`,
 1 AS `purchase_date`,
 1 AS `end_of_warranty`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `location_id_friendlyname`,
 1 AS `location_id_obsolescence_flag`,
 1 AS `brand_id_friendlyname`,
 1 AS `model_id_friendlyname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_person`
--

DROP TABLE IF EXISTS `view_person`;
/*!50001 DROP VIEW IF EXISTS `view_person`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_person` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `status`,
 1 AS `org_id`,
 1 AS `org_name`,
 1 AS `email`,
 1 AS `phone`,
 1 AS `notify`,
 1 AS `function`,
 1 AS `picture`,
 1 AS `picture_data`,
 1 AS `picture_filename`,
 1 AS `first_name`,
 1 AS `employee_number`,
 1 AS `mobile_phone`,
 1 AS `location_id`,
 1 AS `location_name`,
 1 AS `manager_id`,
 1 AS `manager_name`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `location_id_friendlyname`,
 1 AS `location_id_obsolescence_flag`,
 1 AS `manager_id_friendlyname`,
 1 AS `manager_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_phone`
--

DROP TABLE IF EXISTS `view_phone`;
/*!50001 DROP VIEW IF EXISTS `view_phone`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_phone` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `description`,
 1 AS `org_id`,
 1 AS `organization_name`,
 1 AS `business_criticity`,
 1 AS `move2production`,
 1 AS `serialnumber`,
 1 AS `location_id`,
 1 AS `location_name`,
 1 AS `status`,
 1 AS `brand_id`,
 1 AS `brand_name`,
 1 AS `model_id`,
 1 AS `model_name`,
 1 AS `asset_number`,
 1 AS `purchase_date`,
 1 AS `end_of_warranty`,
 1 AS `phonenumber`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `location_id_friendlyname`,
 1 AS `location_id_obsolescence_flag`,
 1 AS `brand_id_friendlyname`,
 1 AS `model_id_friendlyname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_physicaldevice`
--

DROP TABLE IF EXISTS `view_physicaldevice`;
/*!50001 DROP VIEW IF EXISTS `view_physicaldevice`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_physicaldevice` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `description`,
 1 AS `org_id`,
 1 AS `organization_name`,
 1 AS `business_criticity`,
 1 AS `move2production`,
 1 AS `serialnumber`,
 1 AS `location_id`,
 1 AS `location_name`,
 1 AS `status`,
 1 AS `brand_id`,
 1 AS `brand_name`,
 1 AS `model_id`,
 1 AS `model_name`,
 1 AS `asset_number`,
 1 AS `purchase_date`,
 1 AS `end_of_warranty`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `location_id_friendlyname`,
 1 AS `location_id_obsolescence_flag`,
 1 AS `brand_id_friendlyname`,
 1 AS `model_id_friendlyname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_physicalinterface`
--

DROP TABLE IF EXISTS `view_physicalinterface`;
/*!50001 DROP VIEW IF EXISTS `view_physicalinterface`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_physicalinterface` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `ipaddress`,
 1 AS `macaddress`,
 1 AS `comment`,
 1 AS `ipgateway`,
 1 AS `ipmask`,
 1 AS `speed`,
 1 AS `connectableci_id`,
 1 AS `connectableci_name`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `connectableci_id_friendlyname`,
 1 AS `connectableci_id_finalclass_recall`,
 1 AS `connectableci_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_powerconnection`
--

DROP TABLE IF EXISTS `view_powerconnection`;
/*!50001 DROP VIEW IF EXISTS `view_powerconnection`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_powerconnection` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `description`,
 1 AS `org_id`,
 1 AS `organization_name`,
 1 AS `business_criticity`,
 1 AS `move2production`,
 1 AS `serialnumber`,
 1 AS `location_id`,
 1 AS `location_name`,
 1 AS `status`,
 1 AS `brand_id`,
 1 AS `brand_name`,
 1 AS `model_id`,
 1 AS `model_name`,
 1 AS `asset_number`,
 1 AS `purchase_date`,
 1 AS `end_of_warranty`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `location_id_friendlyname`,
 1 AS `location_id_obsolescence_flag`,
 1 AS `brand_id_friendlyname`,
 1 AS `model_id_friendlyname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_powersource`
--

DROP TABLE IF EXISTS `view_powersource`;
/*!50001 DROP VIEW IF EXISTS `view_powersource`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_powersource` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `description`,
 1 AS `org_id`,
 1 AS `organization_name`,
 1 AS `business_criticity`,
 1 AS `move2production`,
 1 AS `serialnumber`,
 1 AS `location_id`,
 1 AS `location_name`,
 1 AS `status`,
 1 AS `brand_id`,
 1 AS `brand_name`,
 1 AS `model_id`,
 1 AS `model_name`,
 1 AS `asset_number`,
 1 AS `purchase_date`,
 1 AS `end_of_warranty`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `location_id_friendlyname`,
 1 AS `location_id_obsolescence_flag`,
 1 AS `brand_id_friendlyname`,
 1 AS `model_id_friendlyname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_printer`
--

DROP TABLE IF EXISTS `view_printer`;
/*!50001 DROP VIEW IF EXISTS `view_printer`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_printer` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `description`,
 1 AS `org_id`,
 1 AS `organization_name`,
 1 AS `business_criticity`,
 1 AS `move2production`,
 1 AS `serialnumber`,
 1 AS `location_id`,
 1 AS `location_name`,
 1 AS `status`,
 1 AS `brand_id`,
 1 AS `brand_name`,
 1 AS `model_id`,
 1 AS `model_name`,
 1 AS `asset_number`,
 1 AS `purchase_date`,
 1 AS `end_of_warranty`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `location_id_friendlyname`,
 1 AS `location_id_obsolescence_flag`,
 1 AS `brand_id_friendlyname`,
 1 AS `model_id_friendlyname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_problem`
--

DROP TABLE IF EXISTS `view_problem`;
/*!50001 DROP VIEW IF EXISTS `view_problem`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_problem` AS SELECT 
 1 AS `id`,
 1 AS `operational_status`,
 1 AS `ref`,
 1 AS `org_id`,
 1 AS `org_name`,
 1 AS `caller_id`,
 1 AS `caller_name`,
 1 AS `team_id`,
 1 AS `team_name`,
 1 AS `agent_id`,
 1 AS `agent_name`,
 1 AS `title`,
 1 AS `description`,
 1 AS `description_format`,
 1 AS `start_date`,
 1 AS `end_date`,
 1 AS `last_update`,
 1 AS `close_date`,
 1 AS `private_log`,
 1 AS `private_log_index`,
 1 AS `status`,
 1 AS `service_id`,
 1 AS `service_name`,
 1 AS `servicesubcategory_id`,
 1 AS `servicesubcategory_name`,
 1 AS `product`,
 1 AS `impact`,
 1 AS `urgency`,
 1 AS `priority`,
 1 AS `related_change_id`,
 1 AS `related_change_ref`,
 1 AS `assignment_date`,
 1 AS `resolution_date`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `caller_id_friendlyname`,
 1 AS `caller_id_obsolescence_flag`,
 1 AS `team_id_friendlyname`,
 1 AS `team_id_obsolescence_flag`,
 1 AS `agent_id_friendlyname`,
 1 AS `agent_id_obsolescence_flag`,
 1 AS `service_id_friendlyname`,
 1 AS `servicesubcategory_id_friendlyname`,
 1 AS `related_change_id_friendlyname`,
 1 AS `related_change_id_finalclass_recall`,
 1 AS `related_change_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_providercontract`
--

DROP TABLE IF EXISTS `view_providercontract`;
/*!50001 DROP VIEW IF EXISTS `view_providercontract`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_providercontract` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `org_id`,
 1 AS `organization_name`,
 1 AS `description`,
 1 AS `start_date`,
 1 AS `end_date`,
 1 AS `cost`,
 1 AS `cost_currency`,
 1 AS `contracttype_id`,
 1 AS `contracttype_name`,
 1 AS `billing_frequency`,
 1 AS `cost_unit`,
 1 AS `provider_id`,
 1 AS `provider_name`,
 1 AS `status`,
 1 AS `sla`,
 1 AS `coverage`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `contracttype_id_friendlyname`,
 1 AS `provider_id_friendlyname`,
 1 AS `provider_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_rack`
--

DROP TABLE IF EXISTS `view_rack`;
/*!50001 DROP VIEW IF EXISTS `view_rack`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_rack` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `description`,
 1 AS `org_id`,
 1 AS `organization_name`,
 1 AS `business_criticity`,
 1 AS `move2production`,
 1 AS `serialnumber`,
 1 AS `location_id`,
 1 AS `location_name`,
 1 AS `status`,
 1 AS `brand_id`,
 1 AS `brand_name`,
 1 AS `model_id`,
 1 AS `model_name`,
 1 AS `asset_number`,
 1 AS `purchase_date`,
 1 AS `end_of_warranty`,
 1 AS `nb_u`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `location_id_friendlyname`,
 1 AS `location_id_obsolescence_flag`,
 1 AS `brand_id_friendlyname`,
 1 AS `model_id_friendlyname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_routinechange`
--

DROP TABLE IF EXISTS `view_routinechange`;
/*!50001 DROP VIEW IF EXISTS `view_routinechange`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_routinechange` AS SELECT 
 1 AS `id`,
 1 AS `operational_status`,
 1 AS `ref`,
 1 AS `org_id`,
 1 AS `org_name`,
 1 AS `caller_id`,
 1 AS `caller_name`,
 1 AS `team_id`,
 1 AS `team_name`,
 1 AS `agent_id`,
 1 AS `agent_name`,
 1 AS `title`,
 1 AS `description`,
 1 AS `description_format`,
 1 AS `start_date`,
 1 AS `end_date`,
 1 AS `last_update`,
 1 AS `close_date`,
 1 AS `private_log`,
 1 AS `private_log_index`,
 1 AS `status`,
 1 AS `reason`,
 1 AS `requestor_id`,
 1 AS `requestor_email`,
 1 AS `creation_date`,
 1 AS `impact`,
 1 AS `supervisor_group_id`,
 1 AS `supervisor_group_name`,
 1 AS `supervisor_id`,
 1 AS `supervisor_email`,
 1 AS `manager_group_id`,
 1 AS `manager_group_name`,
 1 AS `manager_id`,
 1 AS `manager_email`,
 1 AS `outage`,
 1 AS `fallback`,
 1 AS `parent_id`,
 1 AS `parent_name`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `caller_id_friendlyname`,
 1 AS `caller_id_obsolescence_flag`,
 1 AS `team_id_friendlyname`,
 1 AS `team_id_obsolescence_flag`,
 1 AS `agent_id_friendlyname`,
 1 AS `agent_id_obsolescence_flag`,
 1 AS `requestor_id_friendlyname`,
 1 AS `requestor_id_obsolescence_flag`,
 1 AS `supervisor_group_id_friendlyname`,
 1 AS `supervisor_group_id_obsolescence_flag`,
 1 AS `supervisor_id_friendlyname`,
 1 AS `supervisor_id_obsolescence_flag`,
 1 AS `manager_group_id_friendlyname`,
 1 AS `manager_group_id_obsolescence_flag`,
 1 AS `manager_id_friendlyname`,
 1 AS `manager_id_obsolescence_flag`,
 1 AS `parent_id_friendlyname`,
 1 AS `parent_id_finalclass_recall`,
 1 AS `parent_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_sanswitch`
--

DROP TABLE IF EXISTS `view_sanswitch`;
/*!50001 DROP VIEW IF EXISTS `view_sanswitch`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_sanswitch` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `description`,
 1 AS `org_id`,
 1 AS `organization_name`,
 1 AS `business_criticity`,
 1 AS `move2production`,
 1 AS `serialnumber`,
 1 AS `location_id`,
 1 AS `location_name`,
 1 AS `status`,
 1 AS `brand_id`,
 1 AS `brand_name`,
 1 AS `model_id`,
 1 AS `model_name`,
 1 AS `asset_number`,
 1 AS `purchase_date`,
 1 AS `end_of_warranty`,
 1 AS `rack_id`,
 1 AS `rack_name`,
 1 AS `enclosure_id`,
 1 AS `enclosure_name`,
 1 AS `nb_u`,
 1 AS `managementip`,
 1 AS `powerA_id`,
 1 AS `powerA_name`,
 1 AS `powerB_id`,
 1 AS `powerB_name`,
 1 AS `redundancy`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `location_id_friendlyname`,
 1 AS `location_id_obsolescence_flag`,
 1 AS `brand_id_friendlyname`,
 1 AS `model_id_friendlyname`,
 1 AS `rack_id_friendlyname`,
 1 AS `rack_id_obsolescence_flag`,
 1 AS `enclosure_id_friendlyname`,
 1 AS `enclosure_id_obsolescence_flag`,
 1 AS `powerA_id_friendlyname`,
 1 AS `powerA_id_finalclass_recall`,
 1 AS `powerA_id_obsolescence_flag`,
 1 AS `powerB_id_friendlyname`,
 1 AS `powerB_id_finalclass_recall`,
 1 AS `powerB_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_server`
--

DROP TABLE IF EXISTS `view_server`;
/*!50001 DROP VIEW IF EXISTS `view_server`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_server` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `description`,
 1 AS `org_id`,
 1 AS `organization_name`,
 1 AS `business_criticity`,
 1 AS `move2production`,
 1 AS `serialnumber`,
 1 AS `location_id`,
 1 AS `location_name`,
 1 AS `status`,
 1 AS `brand_id`,
 1 AS `brand_name`,
 1 AS `model_id`,
 1 AS `model_name`,
 1 AS `asset_number`,
 1 AS `purchase_date`,
 1 AS `end_of_warranty`,
 1 AS `rack_id`,
 1 AS `rack_name`,
 1 AS `enclosure_id`,
 1 AS `enclosure_name`,
 1 AS `nb_u`,
 1 AS `managementip`,
 1 AS `powerA_id`,
 1 AS `powerA_name`,
 1 AS `powerB_id`,
 1 AS `powerB_name`,
 1 AS `redundancy`,
 1 AS `osfamily_id`,
 1 AS `osfamily_name`,
 1 AS `osversion_id`,
 1 AS `osversion_name`,
 1 AS `oslicence_id`,
 1 AS `oslicence_name`,
 1 AS `cpu`,
 1 AS `ram`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `location_id_friendlyname`,
 1 AS `location_id_obsolescence_flag`,
 1 AS `brand_id_friendlyname`,
 1 AS `model_id_friendlyname`,
 1 AS `rack_id_friendlyname`,
 1 AS `rack_id_obsolescence_flag`,
 1 AS `enclosure_id_friendlyname`,
 1 AS `enclosure_id_obsolescence_flag`,
 1 AS `powerA_id_friendlyname`,
 1 AS `powerA_id_finalclass_recall`,
 1 AS `powerA_id_obsolescence_flag`,
 1 AS `powerB_id_friendlyname`,
 1 AS `powerB_id_finalclass_recall`,
 1 AS `powerB_id_obsolescence_flag`,
 1 AS `osfamily_id_friendlyname`,
 1 AS `osversion_id_friendlyname`,
 1 AS `oslicence_id_friendlyname`,
 1 AS `oslicence_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_service`
--

DROP TABLE IF EXISTS `view_service`;
/*!50001 DROP VIEW IF EXISTS `view_service`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_service` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `org_id`,
 1 AS `organization_name`,
 1 AS `servicefamily_id`,
 1 AS `servicefamily_name`,
 1 AS `description`,
 1 AS `status`,
 1 AS `icon`,
 1 AS `icon_data`,
 1 AS `icon_filename`,
 1 AS `friendlyname`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `servicefamily_id_friendlyname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_servicefamily`
--

DROP TABLE IF EXISTS `view_servicefamily`;
/*!50001 DROP VIEW IF EXISTS `view_servicefamily`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_servicefamily` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `icon`,
 1 AS `icon_data`,
 1 AS `icon_filename`,
 1 AS `friendlyname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_servicesubcategory`
--

DROP TABLE IF EXISTS `view_servicesubcategory`;
/*!50001 DROP VIEW IF EXISTS `view_servicesubcategory`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_servicesubcategory` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `description`,
 1 AS `service_id`,
 1 AS `service_org_id`,
 1 AS `service_name`,
 1 AS `service_provider`,
 1 AS `request_type`,
 1 AS `status`,
 1 AS `friendlyname`,
 1 AS `service_id_friendlyname`,
 1 AS `service_org_id_friendlyname`,
 1 AS `service_org_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_sla`
--

DROP TABLE IF EXISTS `view_sla`;
/*!50001 DROP VIEW IF EXISTS `view_sla`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_sla` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `description`,
 1 AS `org_id`,
 1 AS `organization_name`,
 1 AS `friendlyname`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_slt`
--

DROP TABLE IF EXISTS `view_slt`;
/*!50001 DROP VIEW IF EXISTS `view_slt`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_slt` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `priority`,
 1 AS `request_type`,
 1 AS `metric`,
 1 AS `value`,
 1 AS `unit`,
 1 AS `friendlyname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_software`
--

DROP TABLE IF EXISTS `view_software`;
/*!50001 DROP VIEW IF EXISTS `view_software`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_software` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `vendor`,
 1 AS `version`,
 1 AS `type`,
 1 AS `friendlyname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_softwareinstance`
--

DROP TABLE IF EXISTS `view_softwareinstance`;
/*!50001 DROP VIEW IF EXISTS `view_softwareinstance`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_softwareinstance` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `description`,
 1 AS `org_id`,
 1 AS `organization_name`,
 1 AS `business_criticity`,
 1 AS `move2production`,
 1 AS `system_id`,
 1 AS `system_name`,
 1 AS `software_id`,
 1 AS `software_name`,
 1 AS `softwarelicence_id`,
 1 AS `softwarelicence_name`,
 1 AS `path`,
 1 AS `status`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `system_id_friendlyname`,
 1 AS `system_id_finalclass_recall`,
 1 AS `system_id_obsolescence_flag`,
 1 AS `software_id_friendlyname`,
 1 AS `softwarelicence_id_friendlyname`,
 1 AS `softwarelicence_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_softwarelicence`
--

DROP TABLE IF EXISTS `view_softwarelicence`;
/*!50001 DROP VIEW IF EXISTS `view_softwarelicence`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_softwarelicence` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `org_id`,
 1 AS `organization_name`,
 1 AS `usage_limit`,
 1 AS `description`,
 1 AS `start_date`,
 1 AS `end_date`,
 1 AS `licence_key`,
 1 AS `perpetual`,
 1 AS `software_id`,
 1 AS `software_name`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `software_id_friendlyname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_softwarepatch`
--

DROP TABLE IF EXISTS `view_softwarepatch`;
/*!50001 DROP VIEW IF EXISTS `view_softwarepatch`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_softwarepatch` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `description`,
 1 AS `software_id`,
 1 AS `software_name`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `software_id_friendlyname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_storagesystem`
--

DROP TABLE IF EXISTS `view_storagesystem`;
/*!50001 DROP VIEW IF EXISTS `view_storagesystem`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_storagesystem` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `description`,
 1 AS `org_id`,
 1 AS `organization_name`,
 1 AS `business_criticity`,
 1 AS `move2production`,
 1 AS `serialnumber`,
 1 AS `location_id`,
 1 AS `location_name`,
 1 AS `status`,
 1 AS `brand_id`,
 1 AS `brand_name`,
 1 AS `model_id`,
 1 AS `model_name`,
 1 AS `asset_number`,
 1 AS `purchase_date`,
 1 AS `end_of_warranty`,
 1 AS `rack_id`,
 1 AS `rack_name`,
 1 AS `enclosure_id`,
 1 AS `enclosure_name`,
 1 AS `nb_u`,
 1 AS `managementip`,
 1 AS `powerA_id`,
 1 AS `powerA_name`,
 1 AS `powerB_id`,
 1 AS `powerB_name`,
 1 AS `redundancy`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `location_id_friendlyname`,
 1 AS `location_id_obsolescence_flag`,
 1 AS `brand_id_friendlyname`,
 1 AS `model_id_friendlyname`,
 1 AS `rack_id_friendlyname`,
 1 AS `rack_id_obsolescence_flag`,
 1 AS `enclosure_id_friendlyname`,
 1 AS `enclosure_id_obsolescence_flag`,
 1 AS `powerA_id_friendlyname`,
 1 AS `powerA_id_finalclass_recall`,
 1 AS `powerA_id_obsolescence_flag`,
 1 AS `powerB_id_friendlyname`,
 1 AS `powerB_id_finalclass_recall`,
 1 AS `powerB_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_subnet`
--

DROP TABLE IF EXISTS `view_subnet`;
/*!50001 DROP VIEW IF EXISTS `view_subnet`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_subnet` AS SELECT 
 1 AS `id`,
 1 AS `description`,
 1 AS `subnet_name`,
 1 AS `org_id`,
 1 AS `org_name`,
 1 AS `ip`,
 1 AS `ip_mask`,
 1 AS `friendlyname`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_tablet`
--

DROP TABLE IF EXISTS `view_tablet`;
/*!50001 DROP VIEW IF EXISTS `view_tablet`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_tablet` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `description`,
 1 AS `org_id`,
 1 AS `organization_name`,
 1 AS `business_criticity`,
 1 AS `move2production`,
 1 AS `serialnumber`,
 1 AS `location_id`,
 1 AS `location_name`,
 1 AS `status`,
 1 AS `brand_id`,
 1 AS `brand_name`,
 1 AS `model_id`,
 1 AS `model_name`,
 1 AS `asset_number`,
 1 AS `purchase_date`,
 1 AS `end_of_warranty`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `location_id_friendlyname`,
 1 AS `location_id_obsolescence_flag`,
 1 AS `brand_id_friendlyname`,
 1 AS `model_id_friendlyname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_tape`
--

DROP TABLE IF EXISTS `view_tape`;
/*!50001 DROP VIEW IF EXISTS `view_tape`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_tape` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `description`,
 1 AS `size`,
 1 AS `tapelibrary_id`,
 1 AS `tapelibrary_name`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `tapelibrary_id_friendlyname`,
 1 AS `tapelibrary_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_tapelibrary`
--

DROP TABLE IF EXISTS `view_tapelibrary`;
/*!50001 DROP VIEW IF EXISTS `view_tapelibrary`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_tapelibrary` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `description`,
 1 AS `org_id`,
 1 AS `organization_name`,
 1 AS `business_criticity`,
 1 AS `move2production`,
 1 AS `serialnumber`,
 1 AS `location_id`,
 1 AS `location_name`,
 1 AS `status`,
 1 AS `brand_id`,
 1 AS `brand_name`,
 1 AS `model_id`,
 1 AS `model_name`,
 1 AS `asset_number`,
 1 AS `purchase_date`,
 1 AS `end_of_warranty`,
 1 AS `rack_id`,
 1 AS `rack_name`,
 1 AS `enclosure_id`,
 1 AS `enclosure_name`,
 1 AS `nb_u`,
 1 AS `managementip`,
 1 AS `powerA_id`,
 1 AS `powerA_name`,
 1 AS `powerB_id`,
 1 AS `powerB_name`,
 1 AS `redundancy`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `location_id_friendlyname`,
 1 AS `location_id_obsolescence_flag`,
 1 AS `brand_id_friendlyname`,
 1 AS `model_id_friendlyname`,
 1 AS `rack_id_friendlyname`,
 1 AS `rack_id_obsolescence_flag`,
 1 AS `enclosure_id_friendlyname`,
 1 AS `enclosure_id_obsolescence_flag`,
 1 AS `powerA_id_friendlyname`,
 1 AS `powerA_id_finalclass_recall`,
 1 AS `powerA_id_obsolescence_flag`,
 1 AS `powerB_id_friendlyname`,
 1 AS `powerB_id_finalclass_recall`,
 1 AS `powerB_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_team`
--

DROP TABLE IF EXISTS `view_team`;
/*!50001 DROP VIEW IF EXISTS `view_team`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_team` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `status`,
 1 AS `org_id`,
 1 AS `org_name`,
 1 AS `email`,
 1 AS `phone`,
 1 AS `notify`,
 1 AS `function`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_telephonyci`
--

DROP TABLE IF EXISTS `view_telephonyci`;
/*!50001 DROP VIEW IF EXISTS `view_telephonyci`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_telephonyci` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `description`,
 1 AS `org_id`,
 1 AS `organization_name`,
 1 AS `business_criticity`,
 1 AS `move2production`,
 1 AS `serialnumber`,
 1 AS `location_id`,
 1 AS `location_name`,
 1 AS `status`,
 1 AS `brand_id`,
 1 AS `brand_name`,
 1 AS `model_id`,
 1 AS `model_name`,
 1 AS `asset_number`,
 1 AS `purchase_date`,
 1 AS `end_of_warranty`,
 1 AS `phonenumber`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `location_id_friendlyname`,
 1 AS `location_id_obsolescence_flag`,
 1 AS `brand_id_friendlyname`,
 1 AS `model_id_friendlyname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_ticket`
--

DROP TABLE IF EXISTS `view_ticket`;
/*!50001 DROP VIEW IF EXISTS `view_ticket`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_ticket` AS SELECT 
 1 AS `id`,
 1 AS `operational_status`,
 1 AS `ref`,
 1 AS `org_id`,
 1 AS `org_name`,
 1 AS `caller_id`,
 1 AS `caller_name`,
 1 AS `team_id`,
 1 AS `team_name`,
 1 AS `agent_id`,
 1 AS `agent_name`,
 1 AS `title`,
 1 AS `description`,
 1 AS `description_format`,
 1 AS `start_date`,
 1 AS `end_date`,
 1 AS `last_update`,
 1 AS `close_date`,
 1 AS `private_log`,
 1 AS `private_log_index`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `caller_id_friendlyname`,
 1 AS `caller_id_obsolescence_flag`,
 1 AS `team_id_friendlyname`,
 1 AS `team_id_obsolescence_flag`,
 1 AS `agent_id_friendlyname`,
 1 AS `agent_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_typology`
--

DROP TABLE IF EXISTS `view_typology`;
/*!50001 DROP VIEW IF EXISTS `view_typology`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_typology` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `finalclass`,
 1 AS `friendlyname`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_userrequest`
--

DROP TABLE IF EXISTS `view_userrequest`;
/*!50001 DROP VIEW IF EXISTS `view_userrequest`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_userrequest` AS SELECT 
 1 AS `id`,
 1 AS `operational_status`,
 1 AS `ref`,
 1 AS `org_id`,
 1 AS `org_name`,
 1 AS `caller_id`,
 1 AS `caller_name`,
 1 AS `team_id`,
 1 AS `team_name`,
 1 AS `agent_id`,
 1 AS `agent_name`,
 1 AS `title`,
 1 AS `description`,
 1 AS `description_format`,
 1 AS `start_date`,
 1 AS `end_date`,
 1 AS `last_update`,
 1 AS `close_date`,
 1 AS `private_log`,
 1 AS `private_log_index`,
 1 AS `status`,
 1 AS `request_type`,
 1 AS `impact`,
 1 AS `priority`,
 1 AS `urgency`,
 1 AS `origin`,
 1 AS `approver_id`,
 1 AS `approver_email`,
 1 AS `service_id`,
 1 AS `service_name`,
 1 AS `servicesubcategory_id`,
 1 AS `servicesubcategory_name`,
 1 AS `escalation_flag`,
 1 AS `escalation_reason`,
 1 AS `assignment_date`,
 1 AS `resolution_date`,
 1 AS `last_pending_date`,
 1 AS `cumulatedpending`,
 1 AS `cumulatedpending_started`,
 1 AS `cumulatedpending_laststart`,
 1 AS `cumulatedpending_stopped`,
 1 AS `tto`,
 1 AS `tto_started`,
 1 AS `tto_laststart`,
 1 AS `tto_stopped`,
 1 AS `tto_75_deadline`,
 1 AS `tto_75_passed`,
 1 AS `tto_75_triggered`,
 1 AS `tto_75_overrun`,
 1 AS `tto_100_deadline`,
 1 AS `tto_100_passed`,
 1 AS `tto_100_triggered`,
 1 AS `tto_100_overrun`,
 1 AS `ttr`,
 1 AS `ttr_started`,
 1 AS `ttr_laststart`,
 1 AS `ttr_stopped`,
 1 AS `ttr_75_deadline`,
 1 AS `ttr_75_passed`,
 1 AS `ttr_75_triggered`,
 1 AS `ttr_75_overrun`,
 1 AS `ttr_100_deadline`,
 1 AS `ttr_100_passed`,
 1 AS `ttr_100_triggered`,
 1 AS `ttr_100_overrun`,
 1 AS `tto_escalation_deadline`,
 1 AS `sla_tto_passed`,
 1 AS `sla_tto_over`,
 1 AS `ttr_escalation_deadline`,
 1 AS `sla_ttr_passed`,
 1 AS `sla_ttr_over`,
 1 AS `time_spent`,
 1 AS `resolution_code`,
 1 AS `solution`,
 1 AS `pending_reason`,
 1 AS `parent_request_id`,
 1 AS `parent_request_ref`,
 1 AS `parent_incident_id`,
 1 AS `parent_incident_ref`,
 1 AS `parent_problem_id`,
 1 AS `parent_problem_ref`,
 1 AS `parent_change_id`,
 1 AS `parent_change_ref`,
 1 AS `public_log`,
 1 AS `public_log_index`,
 1 AS `user_satisfaction`,
 1 AS `user_comment`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `caller_id_friendlyname`,
 1 AS `caller_id_obsolescence_flag`,
 1 AS `team_id_friendlyname`,
 1 AS `team_id_obsolescence_flag`,
 1 AS `agent_id_friendlyname`,
 1 AS `agent_id_obsolescence_flag`,
 1 AS `approver_id_friendlyname`,
 1 AS `approver_id_obsolescence_flag`,
 1 AS `service_id_friendlyname`,
 1 AS `servicesubcategory_id_friendlyname`,
 1 AS `parent_request_id_friendlyname`,
 1 AS `parent_request_id_obsolescence_flag`,
 1 AS `parent_incident_id_friendlyname`,
 1 AS `parent_incident_id_obsolescence_flag`,
 1 AS `parent_problem_id_friendlyname`,
 1 AS `parent_problem_id_obsolescence_flag`,
 1 AS `parent_change_id_friendlyname`,
 1 AS `parent_change_id_finalclass_recall`,
 1 AS `parent_change_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_virtualdevice`
--

DROP TABLE IF EXISTS `view_virtualdevice`;
/*!50001 DROP VIEW IF EXISTS `view_virtualdevice`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_virtualdevice` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `description`,
 1 AS `org_id`,
 1 AS `organization_name`,
 1 AS `business_criticity`,
 1 AS `move2production`,
 1 AS `status`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_virtualhost`
--

DROP TABLE IF EXISTS `view_virtualhost`;
/*!50001 DROP VIEW IF EXISTS `view_virtualhost`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_virtualhost` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `description`,
 1 AS `org_id`,
 1 AS `organization_name`,
 1 AS `business_criticity`,
 1 AS `move2production`,
 1 AS `status`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_virtualmachine`
--

DROP TABLE IF EXISTS `view_virtualmachine`;
/*!50001 DROP VIEW IF EXISTS `view_virtualmachine`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_virtualmachine` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `description`,
 1 AS `org_id`,
 1 AS `organization_name`,
 1 AS `business_criticity`,
 1 AS `move2production`,
 1 AS `status`,
 1 AS `virtualhost_id`,
 1 AS `virtualhost_name`,
 1 AS `osfamily_id`,
 1 AS `osfamily_name`,
 1 AS `osversion_id`,
 1 AS `osversion_name`,
 1 AS `oslicence_id`,
 1 AS `oslicence_name`,
 1 AS `cpu`,
 1 AS `ram`,
 1 AS `managementip`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `virtualhost_id_friendlyname`,
 1 AS `virtualhost_id_finalclass_recall`,
 1 AS `virtualhost_id_obsolescence_flag`,
 1 AS `osfamily_id_friendlyname`,
 1 AS `osversion_id_friendlyname`,
 1 AS `oslicence_id_friendlyname`,
 1 AS `oslicence_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_vlan`
--

DROP TABLE IF EXISTS `view_vlan`;
/*!50001 DROP VIEW IF EXISTS `view_vlan`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_vlan` AS SELECT 
 1 AS `id`,
 1 AS `vlan_tag`,
 1 AS `description`,
 1 AS `org_id`,
 1 AS `org_name`,
 1 AS `friendlyname`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_webapplication`
--

DROP TABLE IF EXISTS `view_webapplication`;
/*!50001 DROP VIEW IF EXISTS `view_webapplication`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_webapplication` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `description`,
 1 AS `org_id`,
 1 AS `organization_name`,
 1 AS `business_criticity`,
 1 AS `move2production`,
 1 AS `webserver_id`,
 1 AS `webserver_name`,
 1 AS `url`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `webserver_id_friendlyname`,
 1 AS `webserver_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_webserver`
--

DROP TABLE IF EXISTS `view_webserver`;
/*!50001 DROP VIEW IF EXISTS `view_webserver`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_webserver` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `description`,
 1 AS `org_id`,
 1 AS `organization_name`,
 1 AS `business_criticity`,
 1 AS `move2production`,
 1 AS `system_id`,
 1 AS `system_name`,
 1 AS `software_id`,
 1 AS `software_name`,
 1 AS `softwarelicence_id`,
 1 AS `softwarelicence_name`,
 1 AS `path`,
 1 AS `status`,
 1 AS `finalclass`,
 1 AS `friendlyname`,
 1 AS `obsolescence_flag`,
 1 AS `obsolescence_date`,
 1 AS `org_id_friendlyname`,
 1 AS `org_id_obsolescence_flag`,
 1 AS `system_id_friendlyname`,
 1 AS `system_id_finalclass_recall`,
 1 AS `system_id_obsolescence_flag`,
 1 AS `software_id_friendlyname`,
 1 AS `softwarelicence_id_friendlyname`,
 1 AS `softwarelicence_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `view_workorder`
--

DROP TABLE IF EXISTS `view_workorder`;
/*!50001 DROP VIEW IF EXISTS `view_workorder`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `view_workorder` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `status`,
 1 AS `description`,
 1 AS `ticket_id`,
 1 AS `ticket_ref`,
 1 AS `team_id`,
 1 AS `team_name`,
 1 AS `agent_id`,
 1 AS `agent_email`,
 1 AS `start_date`,
 1 AS `end_date`,
 1 AS `log`,
 1 AS `log_index`,
 1 AS `friendlyname`,
 1 AS `ticket_id_friendlyname`,
 1 AS `ticket_id_finalclass_recall`,
 1 AS `ticket_id_obsolescence_flag`,
 1 AS `team_id_friendlyname`,
 1 AS `team_id_obsolescence_flag`,
 1 AS `agent_id_friendlyname`,
 1 AS `agent_id_obsolescence_flag`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `virtualdevice`
--

DROP TABLE IF EXISTS `virtualdevice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `virtualdevice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` enum('implementation','obsolete','production','stock') COLLATE utf8_unicode_ci DEFAULT 'production',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `virtualdevice`
--

LOCK TABLES `virtualdevice` WRITE;
/*!40000 ALTER TABLE `virtualdevice` DISABLE KEYS */;
/*!40000 ALTER TABLE `virtualdevice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `virtualhost`
--

DROP TABLE IF EXISTS `virtualhost`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `virtualhost` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `virtualhost`
--

LOCK TABLES `virtualhost` WRITE;
/*!40000 ALTER TABLE `virtualhost` DISABLE KEYS */;
/*!40000 ALTER TABLE `virtualhost` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `virtualmachine`
--

DROP TABLE IF EXISTS `virtualmachine`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `virtualmachine` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `virtualhost_id` int(11) DEFAULT '0',
  `osfamily_id` int(11) DEFAULT '0',
  `osversion_id` int(11) DEFAULT '0',
  `oslicence_id` int(11) DEFAULT '0',
  `cpu` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `ram` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `managementip` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `virtualhost_id` (`virtualhost_id`),
  KEY `osfamily_id` (`osfamily_id`),
  KEY `osversion_id` (`osversion_id`),
  KEY `oslicence_id` (`oslicence_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `virtualmachine`
--

LOCK TABLES `virtualmachine` WRITE;
/*!40000 ALTER TABLE `virtualmachine` DISABLE KEYS */;
/*!40000 ALTER TABLE `virtualmachine` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vlan`
--

DROP TABLE IF EXISTS `vlan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vlan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vlan_tag` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `description` text COLLATE utf8_unicode_ci,
  `org_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `org_id` (`org_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vlan`
--

LOCK TABLES `vlan` WRITE;
/*!40000 ALTER TABLE `vlan` DISABLE KEYS */;
/*!40000 ALTER TABLE `vlan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `webapplication`
--

DROP TABLE IF EXISTS `webapplication`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `webapplication` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `webserver_id` int(11) DEFAULT '0',
  `url` varchar(2048) COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `webserver_id` (`webserver_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `webapplication`
--

LOCK TABLES `webapplication` WRITE;
/*!40000 ALTER TABLE `webapplication` DISABLE KEYS */;
/*!40000 ALTER TABLE `webapplication` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `webserver`
--

DROP TABLE IF EXISTS `webserver`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `webserver` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `webserver`
--

LOCK TABLES `webserver` WRITE;
/*!40000 ALTER TABLE `webserver` DISABLE KEYS */;
/*!40000 ALTER TABLE `webserver` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `workorder`
--

DROP TABLE IF EXISTS `workorder`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `workorder` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `status` enum('closed','open') COLLATE utf8_unicode_ci DEFAULT 'open',
  `description` text COLLATE utf8_unicode_ci,
  `ticket_id` int(11) DEFAULT '0',
  `team_id` int(11) DEFAULT '0',
  `owner_id` int(11) DEFAULT '0',
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `log` longtext COLLATE utf8_unicode_ci,
  `log_index` blob,
  PRIMARY KEY (`id`),
  KEY `ticket_id` (`ticket_id`),
  KEY `team_id` (`team_id`),
  KEY `owner_id` (`owner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `workorder`
--

LOCK TABLES `workorder` WRITE;
/*!40000 ALTER TABLE `workorder` DISABLE KEYS */;
/*!40000 ALTER TABLE `workorder` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'itop_2_4_1'
--

--
-- Final view structure for view `view_applicationsolution`
--

/*!50001 DROP VIEW IF EXISTS `view_applicationsolution`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_applicationsolution` AS select distinct `_functionalci`.`id` AS `id`,`_functionalci`.`name` AS `name`,`_functionalci`.`description` AS `description`,`_functionalci`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `organization_name`,`_functionalci`.`business_criticity` AS `business_criticity`,`_functionalci`.`move2production` AS `move2production`,`_applicationsolution`.`status` AS `status`,`_applicationsolution`.`redundancy` AS `redundancy`,`_functionalci`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_functionalci`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce((`_applicationsolution`.`status` = 'inactive'),0) AS `obsolescence_flag`,`_functionalci`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag` from ((`functionalci` `_functionalci` join `organization` `organization_org_id_organization` on((`_functionalci`.`org_id` = `organization_org_id_organization`.`id`))) join `applicationsolution` `_applicationsolution` on((`_functionalci`.`id` = `_applicationsolution`.`id`))) where coalesce((`_functionalci`.`finalclass` = 'ApplicationSolution'),1) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_approvedchange`
--

/*!50001 DROP VIEW IF EXISTS `view_approvedchange`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_approvedchange` AS select distinct `_ticket`.`id` AS `id`,`_ticket`.`operational_status` AS `operational_status`,`_ticket`.`ref` AS `ref`,`_ticket`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `org_name`,`_ticket`.`caller_id` AS `caller_id`,`person_caller_id_contact`.`name` AS `caller_name`,`_ticket`.`team_id` AS `team_id`,`team_team_id_contact`.`email` AS `team_name`,`_ticket`.`agent_id` AS `agent_id`,`person_agent_id_contact`.`name` AS `agent_name`,`_ticket`.`title` AS `title`,`_ticket`.`description` AS `description`,`_ticket`.`description_format` AS `description_format`,`_ticket`.`start_date` AS `start_date`,`_ticket`.`end_date` AS `end_date`,`_ticket`.`last_update` AS `last_update`,`_ticket`.`close_date` AS `close_date`,`_ticket`.`private_log` AS `private_log`,`_ticket`.`private_log_index` AS `private_log_index`,`_change`.`status` AS `status`,`_change`.`reason` AS `reason`,`_change`.`requestor_id` AS `requestor_id`,`person_requestor_id_contact`.`email` AS `requestor_email`,`_change`.`creation_date` AS `creation_date`,`_change`.`impact` AS `impact`,`_change`.`supervisor_group_id` AS `supervisor_group_id`,`team_supervisor_group_id_contact`.`name` AS `supervisor_group_name`,`_change`.`supervisor_id` AS `supervisor_id`,`person_supervisor_id_contact`.`email` AS `supervisor_email`,`_change`.`manager_group_id` AS `manager_group_id`,`team_manager_group_id_contact`.`name` AS `manager_group_name`,`_change`.`manager_id` AS `manager_id`,`person_manager_id_contact`.`email` AS `manager_email`,`_change`.`outage` AS `outage`,`_change`.`fallback` AS `fallback`,`_change`.`parent_id` AS `parent_id`,`change_parent_id_ticket`.`ref` AS `parent_name`,`_change_approved`.`approval_date` AS `approval_date`,`_change_approved`.`approval_comment` AS `approval_comment`,`_ticket`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_ticket`.`ref`,'')) as char charset utf8) AS `friendlyname`,coalesce(((`_ticket`.`operational_status` = 'closed') and ((`_ticket`.`close_date` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00')) or (isnull(`_ticket`.`close_date`) and (`_ticket`.`last_update` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00'))))),0) AS `obsolescence_flag`,`_ticket`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,cast(concat(coalesce(`person_caller_id_person`.`first_name`,''),coalesce(' ',''),coalesce(`person_caller_id_contact`.`name`,'')) as char charset utf8) AS `caller_id_friendlyname`,coalesce((`person_caller_id_contact`.`status` = 'inactive'),0) AS `caller_id_obsolescence_flag`,cast(concat(coalesce(`team_team_id_contact`.`name`,'')) as char charset utf8) AS `team_id_friendlyname`,coalesce((`team_team_id_contact`.`status` = 'inactive'),0) AS `team_id_obsolescence_flag`,cast(concat(coalesce(`person_agent_id_person`.`first_name`,''),coalesce(' ',''),coalesce(`person_agent_id_contact`.`name`,'')) as char charset utf8) AS `agent_id_friendlyname`,coalesce((`person_agent_id_contact`.`status` = 'inactive'),0) AS `agent_id_obsolescence_flag`,cast(concat(coalesce(`person_requestor_id_person`.`first_name`,''),coalesce(' ',''),coalesce(`person_requestor_id_contact`.`name`,'')) as char charset utf8) AS `requestor_id_friendlyname`,coalesce((`person_requestor_id_contact`.`status` = 'inactive'),0) AS `requestor_id_obsolescence_flag`,cast(concat(coalesce(`team_supervisor_group_id_contact`.`name`,'')) as char charset utf8) AS `supervisor_group_id_friendlyname`,coalesce((`team_supervisor_group_id_contact`.`status` = 'inactive'),0) AS `supervisor_group_id_obsolescence_flag`,cast(concat(coalesce(`person_supervisor_id_person`.`first_name`,''),coalesce(' ',''),coalesce(`person_supervisor_id_contact`.`name`,'')) as char charset utf8) AS `supervisor_id_friendlyname`,coalesce((`person_supervisor_id_contact`.`status` = 'inactive'),0) AS `supervisor_id_obsolescence_flag`,cast(concat(coalesce(`team_manager_group_id_contact`.`name`,'')) as char charset utf8) AS `manager_group_id_friendlyname`,coalesce((`team_manager_group_id_contact`.`status` = 'inactive'),0) AS `manager_group_id_obsolescence_flag`,cast(concat(coalesce(`person_manager_id_person`.`first_name`,''),coalesce(' ',''),coalesce(`person_manager_id_contact`.`name`,'')) as char charset utf8) AS `manager_id_friendlyname`,coalesce((`person_manager_id_contact`.`status` = 'inactive'),0) AS `manager_id_obsolescence_flag`,cast(concat(coalesce(`change_parent_id_ticket`.`ref`,'')) as char charset utf8) AS `parent_id_friendlyname`,`change_parent_id_ticket`.`finalclass` AS `parent_id_finalclass_recall`,coalesce(((`change_parent_id_ticket`.`operational_status` = 'closed') and ((`change_parent_id_ticket`.`close_date` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00')) or (isnull(`change_parent_id_ticket`.`close_date`) and (`change_parent_id_ticket`.`last_update` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00'))))),0) AS `parent_id_obsolescence_flag` from ((((((`ticket` `_ticket` join `organization` `organization_org_id_organization` on((`_ticket`.`org_id` = `organization_org_id_organization`.`id`))) left join (`contact` `person_caller_id_contact` join `person` `person_caller_id_person` on((`person_caller_id_contact`.`id` = `person_caller_id_person`.`id`))) on((`_ticket`.`caller_id` = `person_caller_id_contact`.`id`))) left join `contact` `team_team_id_contact` on((`_ticket`.`team_id` = `team_team_id_contact`.`id`))) left join (`contact` `person_agent_id_contact` join `person` `person_agent_id_person` on((`person_agent_id_contact`.`id` = `person_agent_id_person`.`id`))) on((`_ticket`.`agent_id` = `person_agent_id_contact`.`id`))) join ((((((`change` `_change` left join (`contact` `person_requestor_id_contact` join `person` `person_requestor_id_person` on((`person_requestor_id_contact`.`id` = `person_requestor_id_person`.`id`))) on((`_change`.`requestor_id` = `person_requestor_id_contact`.`id`))) left join `contact` `team_supervisor_group_id_contact` on((`_change`.`supervisor_group_id` = `team_supervisor_group_id_contact`.`id`))) left join (`contact` `person_supervisor_id_contact` join `person` `person_supervisor_id_person` on((`person_supervisor_id_contact`.`id` = `person_supervisor_id_person`.`id`))) on((`_change`.`supervisor_id` = `person_supervisor_id_contact`.`id`))) left join `contact` `team_manager_group_id_contact` on((`_change`.`manager_group_id` = `team_manager_group_id_contact`.`id`))) left join (`contact` `person_manager_id_contact` join `person` `person_manager_id_person` on((`person_manager_id_contact`.`id` = `person_manager_id_person`.`id`))) on((`_change`.`manager_id` = `person_manager_id_contact`.`id`))) left join `ticket` `change_parent_id_ticket` on((`_change`.`parent_id` = `change_parent_id_ticket`.`id`))) on((`_ticket`.`id` = `_change`.`id`))) join `change_approved` `_change_approved` on((`_ticket`.`id` = `_change_approved`.`id`))) where (coalesce((`person_caller_id_contact`.`finalclass` = 'Person'),1) and coalesce((`team_team_id_contact`.`finalclass` = 'Team'),1) and coalesce((`person_agent_id_contact`.`finalclass` = 'Person'),1) and coalesce((`_ticket`.`finalclass` in ('NormalChange','EmergencyChange','ApprovedChange')),1) and coalesce((`person_requestor_id_contact`.`finalclass` = 'Person'),1) and coalesce((`team_supervisor_group_id_contact`.`finalclass` = 'Team'),1) and coalesce((`person_supervisor_id_contact`.`finalclass` = 'Person'),1) and coalesce((`team_manager_group_id_contact`.`finalclass` = 'Team'),1) and coalesce((`person_manager_id_contact`.`finalclass` = 'Person'),1) and coalesce((`change_parent_id_ticket`.`finalclass` in ('RoutineChange','ApprovedChange','NormalChange','EmergencyChange','Change')),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_attachment`
--

/*!50001 DROP VIEW IF EXISTS `view_attachment`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_attachment` AS select distinct `_attachment`.`id` AS `id`,`_attachment`.`expire` AS `expire`,`_attachment`.`temp_id` AS `temp_id`,`_attachment`.`item_class` AS `item_class`,`_attachment`.`item_id` AS `item_id`,`_attachment`.`item_org_id` AS `item_org_id`,`_attachment`.`contents_mimetype` AS `contents`,`_attachment`.`contents_data` AS `contents_data`,`_attachment`.`contents_filename` AS `contents_filename`,cast(concat(coalesce(`_attachment`.`item_class`,''),coalesce(' ',''),coalesce(`_attachment`.`temp_id`,'')) as char charset utf8) AS `friendlyname` from `attachment` `_attachment` where 1 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_brand`
--

/*!50001 DROP VIEW IF EXISTS `view_brand`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_brand` AS select distinct `_typology`.`id` AS `id`,`_typology`.`name` AS `name`,`_typology`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_typology`.`name`,'')) as char charset utf8) AS `friendlyname` from `typology` `_typology` where coalesce((`_typology`.`finalclass` = 'Brand'),1) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_businessprocess`
--

/*!50001 DROP VIEW IF EXISTS `view_businessprocess`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_businessprocess` AS select distinct `_functionalci`.`id` AS `id`,`_functionalci`.`name` AS `name`,`_functionalci`.`description` AS `description`,`_functionalci`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `organization_name`,`_functionalci`.`business_criticity` AS `business_criticity`,`_functionalci`.`move2production` AS `move2production`,`_businessprocess`.`status` AS `status`,`_functionalci`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_functionalci`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce((`_businessprocess`.`status` = 'inactive'),0) AS `obsolescence_flag`,`_functionalci`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag` from ((`functionalci` `_functionalci` join `organization` `organization_org_id_organization` on((`_functionalci`.`org_id` = `organization_org_id_organization`.`id`))) join `businessprocess` `_businessprocess` on((`_functionalci`.`id` = `_businessprocess`.`id`))) where coalesce((`_functionalci`.`finalclass` = 'BusinessProcess'),1) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_change`
--

/*!50001 DROP VIEW IF EXISTS `view_change`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_change` AS select distinct `_ticket`.`id` AS `id`,`_ticket`.`operational_status` AS `operational_status`,`_ticket`.`ref` AS `ref`,`_ticket`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `org_name`,`_ticket`.`caller_id` AS `caller_id`,`person_caller_id_contact`.`name` AS `caller_name`,`_ticket`.`team_id` AS `team_id`,`team_team_id_contact`.`email` AS `team_name`,`_ticket`.`agent_id` AS `agent_id`,`person_agent_id_contact`.`name` AS `agent_name`,`_ticket`.`title` AS `title`,`_ticket`.`description` AS `description`,`_ticket`.`description_format` AS `description_format`,`_ticket`.`start_date` AS `start_date`,`_ticket`.`end_date` AS `end_date`,`_ticket`.`last_update` AS `last_update`,`_ticket`.`close_date` AS `close_date`,`_ticket`.`private_log` AS `private_log`,`_ticket`.`private_log_index` AS `private_log_index`,`_change`.`status` AS `status`,`_change`.`reason` AS `reason`,`_change`.`requestor_id` AS `requestor_id`,`person_requestor_id_contact`.`email` AS `requestor_email`,`_change`.`creation_date` AS `creation_date`,`_change`.`impact` AS `impact`,`_change`.`supervisor_group_id` AS `supervisor_group_id`,`team_supervisor_group_id_contact`.`name` AS `supervisor_group_name`,`_change`.`supervisor_id` AS `supervisor_id`,`person_supervisor_id_contact`.`email` AS `supervisor_email`,`_change`.`manager_group_id` AS `manager_group_id`,`team_manager_group_id_contact`.`name` AS `manager_group_name`,`_change`.`manager_id` AS `manager_id`,`person_manager_id_contact`.`email` AS `manager_email`,`_change`.`outage` AS `outage`,`_change`.`fallback` AS `fallback`,`_change`.`parent_id` AS `parent_id`,`change_parent_id_ticket`.`ref` AS `parent_name`,`_ticket`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_ticket`.`ref`,'')) as char charset utf8) AS `friendlyname`,coalesce(((`_ticket`.`operational_status` = 'closed') and ((`_ticket`.`close_date` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00')) or (isnull(`_ticket`.`close_date`) and (`_ticket`.`last_update` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00'))))),0) AS `obsolescence_flag`,`_ticket`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,cast(concat(coalesce(`person_caller_id_person`.`first_name`,''),coalesce(' ',''),coalesce(`person_caller_id_contact`.`name`,'')) as char charset utf8) AS `caller_id_friendlyname`,coalesce((`person_caller_id_contact`.`status` = 'inactive'),0) AS `caller_id_obsolescence_flag`,cast(concat(coalesce(`team_team_id_contact`.`name`,'')) as char charset utf8) AS `team_id_friendlyname`,coalesce((`team_team_id_contact`.`status` = 'inactive'),0) AS `team_id_obsolescence_flag`,cast(concat(coalesce(`person_agent_id_person`.`first_name`,''),coalesce(' ',''),coalesce(`person_agent_id_contact`.`name`,'')) as char charset utf8) AS `agent_id_friendlyname`,coalesce((`person_agent_id_contact`.`status` = 'inactive'),0) AS `agent_id_obsolescence_flag`,cast(concat(coalesce(`person_requestor_id_person`.`first_name`,''),coalesce(' ',''),coalesce(`person_requestor_id_contact`.`name`,'')) as char charset utf8) AS `requestor_id_friendlyname`,coalesce((`person_requestor_id_contact`.`status` = 'inactive'),0) AS `requestor_id_obsolescence_flag`,cast(concat(coalesce(`team_supervisor_group_id_contact`.`name`,'')) as char charset utf8) AS `supervisor_group_id_friendlyname`,coalesce((`team_supervisor_group_id_contact`.`status` = 'inactive'),0) AS `supervisor_group_id_obsolescence_flag`,cast(concat(coalesce(`person_supervisor_id_person`.`first_name`,''),coalesce(' ',''),coalesce(`person_supervisor_id_contact`.`name`,'')) as char charset utf8) AS `supervisor_id_friendlyname`,coalesce((`person_supervisor_id_contact`.`status` = 'inactive'),0) AS `supervisor_id_obsolescence_flag`,cast(concat(coalesce(`team_manager_group_id_contact`.`name`,'')) as char charset utf8) AS `manager_group_id_friendlyname`,coalesce((`team_manager_group_id_contact`.`status` = 'inactive'),0) AS `manager_group_id_obsolescence_flag`,cast(concat(coalesce(`person_manager_id_person`.`first_name`,''),coalesce(' ',''),coalesce(`person_manager_id_contact`.`name`,'')) as char charset utf8) AS `manager_id_friendlyname`,coalesce((`person_manager_id_contact`.`status` = 'inactive'),0) AS `manager_id_obsolescence_flag`,cast(concat(coalesce(`change_parent_id_ticket`.`ref`,'')) as char charset utf8) AS `parent_id_friendlyname`,`change_parent_id_ticket`.`finalclass` AS `parent_id_finalclass_recall`,coalesce(((`change_parent_id_ticket`.`operational_status` = 'closed') and ((`change_parent_id_ticket`.`close_date` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00')) or (isnull(`change_parent_id_ticket`.`close_date`) and (`change_parent_id_ticket`.`last_update` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00'))))),0) AS `parent_id_obsolescence_flag` from (((((`ticket` `_ticket` join `organization` `organization_org_id_organization` on((`_ticket`.`org_id` = `organization_org_id_organization`.`id`))) left join (`contact` `person_caller_id_contact` join `person` `person_caller_id_person` on((`person_caller_id_contact`.`id` = `person_caller_id_person`.`id`))) on((`_ticket`.`caller_id` = `person_caller_id_contact`.`id`))) left join `contact` `team_team_id_contact` on((`_ticket`.`team_id` = `team_team_id_contact`.`id`))) left join (`contact` `person_agent_id_contact` join `person` `person_agent_id_person` on((`person_agent_id_contact`.`id` = `person_agent_id_person`.`id`))) on((`_ticket`.`agent_id` = `person_agent_id_contact`.`id`))) join ((((((`change` `_change` left join (`contact` `person_requestor_id_contact` join `person` `person_requestor_id_person` on((`person_requestor_id_contact`.`id` = `person_requestor_id_person`.`id`))) on((`_change`.`requestor_id` = `person_requestor_id_contact`.`id`))) left join `contact` `team_supervisor_group_id_contact` on((`_change`.`supervisor_group_id` = `team_supervisor_group_id_contact`.`id`))) left join (`contact` `person_supervisor_id_contact` join `person` `person_supervisor_id_person` on((`person_supervisor_id_contact`.`id` = `person_supervisor_id_person`.`id`))) on((`_change`.`supervisor_id` = `person_supervisor_id_contact`.`id`))) left join `contact` `team_manager_group_id_contact` on((`_change`.`manager_group_id` = `team_manager_group_id_contact`.`id`))) left join (`contact` `person_manager_id_contact` join `person` `person_manager_id_person` on((`person_manager_id_contact`.`id` = `person_manager_id_person`.`id`))) on((`_change`.`manager_id` = `person_manager_id_contact`.`id`))) left join `ticket` `change_parent_id_ticket` on((`_change`.`parent_id` = `change_parent_id_ticket`.`id`))) on((`_ticket`.`id` = `_change`.`id`))) where (coalesce((`person_caller_id_contact`.`finalclass` = 'Person'),1) and coalesce((`team_team_id_contact`.`finalclass` = 'Team'),1) and coalesce((`person_agent_id_contact`.`finalclass` = 'Person'),1) and coalesce((`_ticket`.`finalclass` in ('RoutineChange','ApprovedChange','NormalChange','EmergencyChange','Change')),1) and coalesce((`person_requestor_id_contact`.`finalclass` = 'Person'),1) and coalesce((`team_supervisor_group_id_contact`.`finalclass` = 'Team'),1) and coalesce((`person_supervisor_id_contact`.`finalclass` = 'Person'),1) and coalesce((`team_manager_group_id_contact`.`finalclass` = 'Team'),1) and coalesce((`person_manager_id_contact`.`finalclass` = 'Person'),1) and coalesce((`change_parent_id_ticket`.`finalclass` in ('RoutineChange','ApprovedChange','NormalChange','EmergencyChange','Change')),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_connectableci`
--

/*!50001 DROP VIEW IF EXISTS `view_connectableci`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_connectableci` AS select distinct `_functionalci`.`id` AS `id`,`_functionalci`.`name` AS `name`,`_functionalci`.`description` AS `description`,`_functionalci`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `organization_name`,`_functionalci`.`business_criticity` AS `business_criticity`,`_functionalci`.`move2production` AS `move2production`,`_physicaldevice`.`serialnumber` AS `serialnumber`,`_physicaldevice`.`location_id` AS `location_id`,`location_location_id_location`.`name` AS `location_name`,`_physicaldevice`.`status` AS `status`,`_physicaldevice`.`brand_id` AS `brand_id`,`brand_brand_id_typology`.`name` AS `brand_name`,`_physicaldevice`.`model_id` AS `model_id`,`model_model_id_typology`.`name` AS `model_name`,`_physicaldevice`.`asset_number` AS `asset_number`,`_physicaldevice`.`purchase_date` AS `purchase_date`,`_physicaldevice`.`end_of_warranty` AS `end_of_warranty`,`_functionalci`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_functionalci`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce((`_physicaldevice`.`status` = 'obsolete'),0) AS `obsolescence_flag`,`_functionalci`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,cast(concat(coalesce(`location_location_id_location`.`name`,'')) as char charset utf8) AS `location_id_friendlyname`,coalesce((`location_location_id_location`.`status` = 'inactive'),0) AS `location_id_obsolescence_flag`,cast(concat(coalesce(`brand_brand_id_typology`.`name`,'')) as char charset utf8) AS `brand_id_friendlyname`,cast(concat(coalesce(`model_model_id_typology`.`name`,'')) as char charset utf8) AS `model_id_friendlyname` from ((`functionalci` `_functionalci` join `organization` `organization_org_id_organization` on((`_functionalci`.`org_id` = `organization_org_id_organization`.`id`))) join (((`physicaldevice` `_physicaldevice` left join `location` `location_location_id_location` on((`_physicaldevice`.`location_id` = `location_location_id_location`.`id`))) left join `typology` `brand_brand_id_typology` on((`_physicaldevice`.`brand_id` = `brand_brand_id_typology`.`id`))) left join `typology` `model_model_id_typology` on((`_physicaldevice`.`model_id` = `model_model_id_typology`.`id`))) on((`_functionalci`.`id` = `_physicaldevice`.`id`))) where (coalesce((`_functionalci`.`finalclass` in ('DatacenterDevice','NetworkDevice','Server','PC','Printer','StorageSystem','SANSwitch','TapeLibrary','NAS','ConnectableCI')),1) and coalesce((`brand_brand_id_typology`.`finalclass` = 'Brand'),1) and coalesce((`model_model_id_typology`.`finalclass` = 'Model'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_contact`
--

/*!50001 DROP VIEW IF EXISTS `view_contact`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_contact` AS select distinct `_contact`.`id` AS `id`,`_contact`.`name` AS `name`,`_contact`.`status` AS `status`,`_contact`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `org_name`,`_contact`.`email` AS `email`,`_contact`.`phone` AS `phone`,`_contact`.`notify` AS `notify`,`_contact`.`function` AS `function`,`_contact`.`finalclass` AS `finalclass`,if((`_contact`.`finalclass` in ('Team','Contact')),cast(concat(coalesce(`_contact`.`name`,'')) as char charset utf8),cast(concat(coalesce(`_fn_person_person`.`first_name`,''),coalesce(' ',''),coalesce(`_contact`.`name`,'')) as char charset utf8)) AS `friendlyname`,coalesce((`_contact`.`status` = 'inactive'),0) AS `obsolescence_flag`,`_contact`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag` from ((`contact` `_contact` join `organization` `organization_org_id_organization` on((`_contact`.`org_id` = `organization_org_id_organization`.`id`))) left join `person` `_fn_person_person` on((`_contact`.`id` = `_fn_person_person`.`id`))) where 1 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_contacttype`
--

/*!50001 DROP VIEW IF EXISTS `view_contacttype`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_contacttype` AS select distinct `_typology`.`id` AS `id`,`_typology`.`name` AS `name`,`_typology`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_typology`.`name`,'')) as char charset utf8) AS `friendlyname` from `typology` `_typology` where coalesce((`_typology`.`finalclass` = 'ContactType'),1) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_contract`
--

/*!50001 DROP VIEW IF EXISTS `view_contract`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_contract` AS select distinct `_contract`.`id` AS `id`,`_contract`.`name` AS `name`,`_contract`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `organization_name`,`_contract`.`description` AS `description`,`_contract`.`start_date` AS `start_date`,`_contract`.`end_date` AS `end_date`,`_contract`.`cost` AS `cost`,`_contract`.`cost_currency` AS `cost_currency`,`_contract`.`contracttype_id` AS `contracttype_id`,`contracttype_contracttype_id_typology`.`name` AS `contracttype_name`,`_contract`.`billing_frequency` AS `billing_frequency`,`_contract`.`cost_unit` AS `cost_unit`,`_contract`.`provider_id` AS `provider_id`,`organization_provider_id_organization`.`name` AS `provider_name`,`_contract`.`status` AS `status`,`_contract`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_contract`.`name`,'')) as char charset utf8) AS `friendlyname`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,cast(concat(coalesce(`contracttype_contracttype_id_typology`.`name`,'')) as char charset utf8) AS `contracttype_id_friendlyname`,cast(concat(coalesce(`organization_provider_id_organization`.`name`,'')) as char charset utf8) AS `provider_id_friendlyname`,coalesce((`organization_provider_id_organization`.`status` = 'inactive'),0) AS `provider_id_obsolescence_flag` from (((`contract` `_contract` join `organization` `organization_org_id_organization` on((`_contract`.`org_id` = `organization_org_id_organization`.`id`))) left join `typology` `contracttype_contracttype_id_typology` on((`_contract`.`contracttype_id` = `contracttype_contracttype_id_typology`.`id`))) join `organization` `organization_provider_id_organization` on((`_contract`.`provider_id` = `organization_provider_id_organization`.`id`))) where coalesce((`contracttype_contracttype_id_typology`.`finalclass` = 'ContractType'),1) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_contracttype`
--

/*!50001 DROP VIEW IF EXISTS `view_contracttype`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_contracttype` AS select distinct `_typology`.`id` AS `id`,`_typology`.`name` AS `name`,`_typology`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_typology`.`name`,'')) as char charset utf8) AS `friendlyname` from `typology` `_typology` where coalesce((`_typology`.`finalclass` = 'ContractType'),1) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_customercontract`
--

/*!50001 DROP VIEW IF EXISTS `view_customercontract`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_customercontract` AS select distinct `_contract`.`id` AS `id`,`_contract`.`name` AS `name`,`_contract`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `organization_name`,`_contract`.`description` AS `description`,`_contract`.`start_date` AS `start_date`,`_contract`.`end_date` AS `end_date`,`_contract`.`cost` AS `cost`,`_contract`.`cost_currency` AS `cost_currency`,`_contract`.`contracttype_id` AS `contracttype_id`,`contracttype_contracttype_id_typology`.`name` AS `contracttype_name`,`_contract`.`billing_frequency` AS `billing_frequency`,`_contract`.`cost_unit` AS `cost_unit`,`_contract`.`provider_id` AS `provider_id`,`organization_provider_id_organization`.`name` AS `provider_name`,`_contract`.`status` AS `status`,`_contract`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_contract`.`name`,'')) as char charset utf8) AS `friendlyname`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,cast(concat(coalesce(`contracttype_contracttype_id_typology`.`name`,'')) as char charset utf8) AS `contracttype_id_friendlyname`,cast(concat(coalesce(`organization_provider_id_organization`.`name`,'')) as char charset utf8) AS `provider_id_friendlyname`,coalesce((`organization_provider_id_organization`.`status` = 'inactive'),0) AS `provider_id_obsolescence_flag` from (((`contract` `_contract` join `organization` `organization_org_id_organization` on((`_contract`.`org_id` = `organization_org_id_organization`.`id`))) left join `typology` `contracttype_contracttype_id_typology` on((`_contract`.`contracttype_id` = `contracttype_contracttype_id_typology`.`id`))) join `organization` `organization_provider_id_organization` on((`_contract`.`provider_id` = `organization_provider_id_organization`.`id`))) where (coalesce((`contracttype_contracttype_id_typology`.`finalclass` = 'ContractType'),1) and coalesce((`_contract`.`finalclass` = 'CustomerContract'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_databaseschema`
--

/*!50001 DROP VIEW IF EXISTS `view_databaseschema`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_databaseschema` AS select distinct `_functionalci`.`id` AS `id`,`_functionalci`.`name` AS `name`,`_functionalci`.`description` AS `description`,`_functionalci`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `organization_name`,`_functionalci`.`business_criticity` AS `business_criticity`,`_functionalci`.`move2production` AS `move2production`,`_databaseschema`.`dbserver_id` AS `dbserver_id`,`dbserver_dbserver_id_functionalci`.`name` AS `dbserver_name`,`_functionalci`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_functionalci`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce(coalesce((`dbserver_dbserver_id_softwareinstance`.`status` = 'inactive'),0),0) AS `obsolescence_flag`,`_functionalci`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,cast(concat(coalesce(`dbserver_dbserver_id_functionalci`.`name`,''),coalesce(' ',''),coalesce(`functionalci_system_id_functionalci`.`name`,'')) as char charset utf8) AS `dbserver_id_friendlyname`,coalesce((`dbserver_dbserver_id_softwareinstance`.`status` = 'inactive'),0) AS `dbserver_id_obsolescence_flag` from ((`functionalci` `_functionalci` join `organization` `organization_org_id_organization` on((`_functionalci`.`org_id` = `organization_org_id_organization`.`id`))) join (`databaseschema` `_databaseschema` join (`functionalci` `dbserver_dbserver_id_functionalci` join (`softwareinstance` `dbserver_dbserver_id_softwareinstance` join `functionalci` `functionalci_system_id_functionalci` on((`dbserver_dbserver_id_softwareinstance`.`functionalci_id` = `functionalci_system_id_functionalci`.`id`))) on((`dbserver_dbserver_id_functionalci`.`id` = `dbserver_dbserver_id_softwareinstance`.`id`))) on((`_databaseschema`.`dbserver_id` = `dbserver_dbserver_id_functionalci`.`id`))) on((`_functionalci`.`id` = `_databaseschema`.`id`))) where (coalesce((`_functionalci`.`finalclass` = 'DatabaseSchema'),1) and coalesce((`dbserver_dbserver_id_functionalci`.`finalclass` = 'DBServer'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_datacenterdevice`
--

/*!50001 DROP VIEW IF EXISTS `view_datacenterdevice`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_datacenterdevice` AS select distinct `_functionalci`.`id` AS `id`,`_functionalci`.`name` AS `name`,`_functionalci`.`description` AS `description`,`_functionalci`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `organization_name`,`_functionalci`.`business_criticity` AS `business_criticity`,`_functionalci`.`move2production` AS `move2production`,`_physicaldevice`.`serialnumber` AS `serialnumber`,`_physicaldevice`.`location_id` AS `location_id`,`location_location_id_location`.`name` AS `location_name`,`_physicaldevice`.`status` AS `status`,`_physicaldevice`.`brand_id` AS `brand_id`,`brand_brand_id_typology`.`name` AS `brand_name`,`_physicaldevice`.`model_id` AS `model_id`,`model_model_id_typology`.`name` AS `model_name`,`_physicaldevice`.`asset_number` AS `asset_number`,`_physicaldevice`.`purchase_date` AS `purchase_date`,`_physicaldevice`.`end_of_warranty` AS `end_of_warranty`,`_datacenterdevice`.`rack_id` AS `rack_id`,`rack_rack_id_functionalci`.`name` AS `rack_name`,`_datacenterdevice`.`enclosure_id` AS `enclosure_id`,`enclosure_enclosure_id_functionalci`.`name` AS `enclosure_name`,`_datacenterdevice`.`nb_u` AS `nb_u`,`_datacenterdevice`.`managementip` AS `managementip`,`_datacenterdevice`.`powera_id` AS `powerA_id`,`powerconnection_powera_id_functionalci`.`name` AS `powerA_name`,`_datacenterdevice`.`powerB_id` AS `powerB_id`,`powerconnection_powerb_id_functionalci`.`name` AS `powerB_name`,`_datacenterdevice`.`redundancy` AS `redundancy`,`_functionalci`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_functionalci`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce((`_physicaldevice`.`status` = 'obsolete'),0) AS `obsolescence_flag`,`_functionalci`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,cast(concat(coalesce(`location_location_id_location`.`name`,'')) as char charset utf8) AS `location_id_friendlyname`,coalesce((`location_location_id_location`.`status` = 'inactive'),0) AS `location_id_obsolescence_flag`,cast(concat(coalesce(`brand_brand_id_typology`.`name`,'')) as char charset utf8) AS `brand_id_friendlyname`,cast(concat(coalesce(`model_model_id_typology`.`name`,'')) as char charset utf8) AS `model_id_friendlyname`,cast(concat(coalesce(`rack_rack_id_functionalci`.`name`,'')) as char charset utf8) AS `rack_id_friendlyname`,coalesce((`rack_rack_id_physicaldevice`.`status` = 'obsolete'),0) AS `rack_id_obsolescence_flag`,cast(concat(coalesce(`enclosure_enclosure_id_functionalci`.`name`,'')) as char charset utf8) AS `enclosure_id_friendlyname`,coalesce((`enclosure_enclosure_id_physicaldevice`.`status` = 'obsolete'),0) AS `enclosure_id_obsolescence_flag`,cast(concat(coalesce(`powerconnection_powera_id_functionalci`.`name`,'')) as char charset utf8) AS `powerA_id_friendlyname`,`powerconnection_powera_id_functionalci`.`finalclass` AS `powerA_id_finalclass_recall`,coalesce((`powerconnection_powera_id_physicaldevice`.`status` = 'obsolete'),0) AS `powerA_id_obsolescence_flag`,cast(concat(coalesce(`powerconnection_powerb_id_functionalci`.`name`,'')) as char charset utf8) AS `powerB_id_friendlyname`,`powerconnection_powerb_id_functionalci`.`finalclass` AS `powerB_id_finalclass_recall`,coalesce((`powerconnection_powerb_id_physicaldevice`.`status` = 'obsolete'),0) AS `powerB_id_obsolescence_flag` from (((`functionalci` `_functionalci` join `organization` `organization_org_id_organization` on((`_functionalci`.`org_id` = `organization_org_id_organization`.`id`))) join (((`physicaldevice` `_physicaldevice` left join `location` `location_location_id_location` on((`_physicaldevice`.`location_id` = `location_location_id_location`.`id`))) left join `typology` `brand_brand_id_typology` on((`_physicaldevice`.`brand_id` = `brand_brand_id_typology`.`id`))) left join `typology` `model_model_id_typology` on((`_physicaldevice`.`model_id` = `model_model_id_typology`.`id`))) on((`_functionalci`.`id` = `_physicaldevice`.`id`))) join ((((`datacenterdevice` `_datacenterdevice` left join (`functionalci` `rack_rack_id_functionalci` join `physicaldevice` `rack_rack_id_physicaldevice` on((`rack_rack_id_functionalci`.`id` = `rack_rack_id_physicaldevice`.`id`))) on((`_datacenterdevice`.`rack_id` = `rack_rack_id_functionalci`.`id`))) left join (`functionalci` `enclosure_enclosure_id_functionalci` join `physicaldevice` `enclosure_enclosure_id_physicaldevice` on((`enclosure_enclosure_id_functionalci`.`id` = `enclosure_enclosure_id_physicaldevice`.`id`))) on((`_datacenterdevice`.`enclosure_id` = `enclosure_enclosure_id_functionalci`.`id`))) left join (`functionalci` `powerconnection_powera_id_functionalci` join `physicaldevice` `powerconnection_powera_id_physicaldevice` on((`powerconnection_powera_id_functionalci`.`id` = `powerconnection_powera_id_physicaldevice`.`id`))) on((`_datacenterdevice`.`powera_id` = `powerconnection_powera_id_functionalci`.`id`))) left join (`functionalci` `powerconnection_powerb_id_functionalci` join `physicaldevice` `powerconnection_powerb_id_physicaldevice` on((`powerconnection_powerb_id_functionalci`.`id` = `powerconnection_powerb_id_physicaldevice`.`id`))) on((`_datacenterdevice`.`powerB_id` = `powerconnection_powerb_id_functionalci`.`id`))) on((`_functionalci`.`id` = `_datacenterdevice`.`id`))) where (coalesce((`_functionalci`.`finalclass` in ('NetworkDevice','Server','StorageSystem','SANSwitch','TapeLibrary','NAS','DatacenterDevice')),1) and coalesce((`brand_brand_id_typology`.`finalclass` = 'Brand'),1) and coalesce((`model_model_id_typology`.`finalclass` = 'Model'),1) and coalesce((`rack_rack_id_functionalci`.`finalclass` = 'Rack'),1) and coalesce((`enclosure_enclosure_id_functionalci`.`finalclass` = 'Enclosure'),1) and coalesce((`powerconnection_powera_id_functionalci`.`finalclass` in ('PowerSource','PDU','PowerConnection')),1) and coalesce((`powerconnection_powerb_id_functionalci`.`finalclass` in ('PowerSource','PDU','PowerConnection')),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_dbserver`
--

/*!50001 DROP VIEW IF EXISTS `view_dbserver`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_dbserver` AS select distinct `_functionalci`.`id` AS `id`,`_functionalci`.`name` AS `name`,`_functionalci`.`description` AS `description`,`_functionalci`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `organization_name`,`_functionalci`.`business_criticity` AS `business_criticity`,`_functionalci`.`move2production` AS `move2production`,`_softwareinstance`.`functionalci_id` AS `system_id`,`functionalci_system_id_functionalci`.`name` AS `system_name`,`_softwareinstance`.`software_id` AS `software_id`,`software_software_id_software`.`name` AS `software_name`,`_softwareinstance`.`softwarelicence_id` AS `softwarelicence_id`,`softwarelicence_softwarelicence_id_licence`.`name` AS `softwarelicence_name`,`_softwareinstance`.`path` AS `path`,`_softwareinstance`.`status` AS `status`,`_functionalci`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_functionalci`.`name`,''),coalesce(' ',''),coalesce(`functionalci_system_id_functionalci`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce((`_softwareinstance`.`status` = 'inactive'),0) AS `obsolescence_flag`,`_functionalci`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,if((`functionalci_system_id_functionalci`.`finalclass` in ('Middleware','DBServer','WebServer','PCSoftware','OtherSoftware')),cast(concat(coalesce(`functionalci_system_id_functionalci`.`name`,''),coalesce(' ',''),coalesce(`functionalci_system_id1_functionalci`.`name`,'')) as char charset utf8),cast(concat(coalesce(`functionalci_system_id_functionalci`.`name`,'')) as char charset utf8)) AS `system_id_friendlyname`,`functionalci_system_id_functionalci`.`finalclass` AS `system_id_finalclass_recall`,if((`functionalci_system_id_functionalci`.`finalclass` = 'FunctionalCI'),coalesce(0,0),if((`functionalci_system_id_functionalci`.`finalclass` in ('Hypervisor','Farm','VirtualMachine')),coalesce((`functionalci_system_id_fn_virtualdevice_virtualdevice`.`status` = 'obsolete'),0),if((`functionalci_system_id_functionalci`.`finalclass` = 'WebApplication'),coalesce(coalesce((`webserver_webserver_id_softwareinstance`.`status` = 'inactive'),0),0),if((`functionalci_system_id_functionalci`.`finalclass` = 'DatabaseSchema'),coalesce(coalesce((`dbserver_dbserver_id_softwareinstance`.`status` = 'inactive'),0),0),if((`functionalci_system_id_functionalci`.`finalclass` = 'MiddlewareInstance'),coalesce(coalesce((`middleware_middleware_id_softwareinstance`.`status` = 'inactive'),0),0),if((`functionalci_system_id_functionalci`.`finalclass` in ('Middleware','DBServer','WebServer','PCSoftware','OtherSoftware')),coalesce((`functionalci_system_id_fn_softwareinstance_softwareinstance`.`status` = 'inactive'),0),if((`functionalci_system_id_functionalci`.`finalclass` = 'BusinessProcess'),coalesce((`functionalci_system_id_fn_businessprocess_businessprocess`.`status` = 'inactive'),0),if((`functionalci_system_id_functionalci`.`finalclass` = 'ApplicationSolution'),coalesce((`functionalci_system_id_fn_applicationsolution_applicationsolution`.`status` = 'inactive'),0),coalesce((`functionalci_system_id_fn_physicaldevice_physicaldevice`.`status` = 'obsolete'),0))))))))) AS `system_id_obsolescence_flag`,cast(concat(coalesce(`software_software_id_software`.`name`,''),coalesce(' ',''),coalesce(`software_software_id_software`.`version`,'')) as char charset utf8) AS `software_id_friendlyname`,cast(concat(coalesce(`softwarelicence_softwarelicence_id_licence`.`name`,'')) as char charset utf8) AS `softwarelicence_id_friendlyname`,coalesce(((`softwarelicence_softwarelicence_id_licence`.`perpetual` = 'no') and (isnull(`softwarelicence_softwarelicence_id_licence`.`end_date`) = 0) and (`softwarelicence_softwarelicence_id_licence`.`end_date` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00'))),0) AS `softwarelicence_id_obsolescence_flag` from ((`functionalci` `_functionalci` join `organization` `organization_org_id_organization` on((`_functionalci`.`org_id` = `organization_org_id_organization`.`id`))) join (((`softwareinstance` `_softwareinstance` join ((((((((`functionalci` `functionalci_system_id_functionalci` left join (`softwareinstance` `functionalci_system_id_fn_softwareinstance_softwareinstance` join `functionalci` `functionalci_system_id1_functionalci` on((`functionalci_system_id_fn_softwareinstance_softwareinstance`.`functionalci_id` = `functionalci_system_id1_functionalci`.`id`))) on((`functionalci_system_id_functionalci`.`id` = `functionalci_system_id_fn_softwareinstance_softwareinstance`.`id`))) left join `virtualdevice` `functionalci_system_id_fn_virtualdevice_virtualdevice` on((`functionalci_system_id_functionalci`.`id` = `functionalci_system_id_fn_virtualdevice_virtualdevice`.`id`))) left join (`webapplication` `functionalci_system_id_fn_webapplication_webapplication` join (`functionalci` `webserver_webserver_id_functionalci` join `softwareinstance` `webserver_webserver_id_softwareinstance` on((`webserver_webserver_id_functionalci`.`id` = `webserver_webserver_id_softwareinstance`.`id`))) on((`functionalci_system_id_fn_webapplication_webapplication`.`webserver_id` = `webserver_webserver_id_functionalci`.`id`))) on((`functionalci_system_id_functionalci`.`id` = `functionalci_system_id_fn_webapplication_webapplication`.`id`))) left join (`databaseschema` `functionalci_system_id_fn_databaseschema_databaseschema` join (`functionalci` `dbserver_dbserver_id_functionalci` join `softwareinstance` `dbserver_dbserver_id_softwareinstance` on((`dbserver_dbserver_id_functionalci`.`id` = `dbserver_dbserver_id_softwareinstance`.`id`))) on((`functionalci_system_id_fn_databaseschema_databaseschema`.`dbserver_id` = `dbserver_dbserver_id_functionalci`.`id`))) on((`functionalci_system_id_functionalci`.`id` = `functionalci_system_id_fn_databaseschema_databaseschema`.`id`))) left join (`middlewareinstance` `functionalci_system_id_fn_middlewareinstance_middlewareinstance` join (`functionalci` `middleware_middleware_id_functionalci` join `softwareinstance` `middleware_middleware_id_softwareinstance` on((`middleware_middleware_id_functionalci`.`id` = `middleware_middleware_id_softwareinstance`.`id`))) on((`functionalci_system_id_fn_middlewareinstance_middlewareinstance`.`middleware_id` = `middleware_middleware_id_functionalci`.`id`))) on((`functionalci_system_id_functionalci`.`id` = `functionalci_system_id_fn_middlewareinstance_middlewareinstance`.`id`))) left join `businessprocess` `functionalci_system_id_fn_businessprocess_businessprocess` on((`functionalci_system_id_functionalci`.`id` = `functionalci_system_id_fn_businessprocess_businessprocess`.`id`))) left join `applicationsolution` `functionalci_system_id_fn_applicationsolution_applicationsolution` on((`functionalci_system_id_functionalci`.`id` = `functionalci_system_id_fn_applicationsolution_applicationsolution`.`id`))) left join `physicaldevice` `functionalci_system_id_fn_physicaldevice_physicaldevice` on((`functionalci_system_id_functionalci`.`id` = `functionalci_system_id_fn_physicaldevice_physicaldevice`.`id`))) on((`_softwareinstance`.`functionalci_id` = `functionalci_system_id_functionalci`.`id`))) left join `software` `software_software_id_software` on((`_softwareinstance`.`software_id` = `software_software_id_software`.`id`))) left join `licence` `softwarelicence_softwarelicence_id_licence` on((`_softwareinstance`.`softwarelicence_id` = `softwarelicence_softwarelicence_id_licence`.`id`))) on((`_functionalci`.`id` = `_softwareinstance`.`id`))) where (coalesce((`_functionalci`.`finalclass` = 'DBServer'),1) and coalesce((`webserver_webserver_id_functionalci`.`finalclass` = 'WebServer'),1) and coalesce((`dbserver_dbserver_id_functionalci`.`finalclass` = 'DBServer'),1) and coalesce((`middleware_middleware_id_functionalci`.`finalclass` = 'Middleware'),1) and coalesce((`softwarelicence_softwarelicence_id_licence`.`finalclass` = 'SoftwareLicence'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_deliverymodel`
--

/*!50001 DROP VIEW IF EXISTS `view_deliverymodel`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_deliverymodel` AS select distinct `_deliverymodel`.`id` AS `id`,`_deliverymodel`.`name` AS `name`,`_deliverymodel`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `organization_name`,`_deliverymodel`.`description` AS `description`,cast(concat(coalesce(`_deliverymodel`.`name`,'')) as char charset utf8) AS `friendlyname`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag` from (`deliverymodel` `_deliverymodel` join `organization` `organization_org_id_organization` on((`_deliverymodel`.`org_id` = `organization_org_id_organization`.`id`))) where 1 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_document`
--

/*!50001 DROP VIEW IF EXISTS `view_document`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_document` AS select distinct `_document`.`id` AS `id`,`_document`.`name` AS `name`,`_document`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `org_name`,`_document`.`documenttype_id` AS `documenttype_id`,`documenttype_documenttype_id_typology`.`name` AS `documenttype_name`,`_document`.`version` AS `version`,`_document`.`description` AS `description`,`_document`.`status` AS `status`,`_document`.`finalclass` AS `finalclass`,if((`_document`.`finalclass` = 'Document'),cast(concat(coalesce('Document','')) as char charset utf8),cast(concat(coalesce(`_document`.`name`,'')) as char charset utf8)) AS `friendlyname`,coalesce((`_document`.`status` = 'obsolete'),0) AS `obsolescence_flag`,`_document`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,cast(concat(coalesce(`documenttype_documenttype_id_typology`.`name`,'')) as char charset utf8) AS `documenttype_id_friendlyname` from ((`document` `_document` join `organization` `organization_org_id_organization` on((`_document`.`org_id` = `organization_org_id_organization`.`id`))) left join `typology` `documenttype_documenttype_id_typology` on((`_document`.`documenttype_id` = `documenttype_documenttype_id_typology`.`id`))) where coalesce((`documenttype_documenttype_id_typology`.`finalclass` = 'DocumentType'),1) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_documentfile`
--

/*!50001 DROP VIEW IF EXISTS `view_documentfile`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_documentfile` AS select distinct `_document`.`id` AS `id`,`_document`.`name` AS `name`,`_document`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `org_name`,`_document`.`documenttype_id` AS `documenttype_id`,`documenttype_documenttype_id_typology`.`name` AS `documenttype_name`,`_document`.`version` AS `version`,`_document`.`description` AS `description`,`_document`.`status` AS `status`,`_documentfile`.`file_mimetype` AS `file`,`_documentfile`.`file_data` AS `file_data`,`_documentfile`.`file_filename` AS `file_filename`,`_document`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_document`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce((`_document`.`status` = 'obsolete'),0) AS `obsolescence_flag`,`_document`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,cast(concat(coalesce(`documenttype_documenttype_id_typology`.`name`,'')) as char charset utf8) AS `documenttype_id_friendlyname` from (((`document` `_document` join `organization` `organization_org_id_organization` on((`_document`.`org_id` = `organization_org_id_organization`.`id`))) left join `typology` `documenttype_documenttype_id_typology` on((`_document`.`documenttype_id` = `documenttype_documenttype_id_typology`.`id`))) join `documentfile` `_documentfile` on((`_document`.`id` = `_documentfile`.`id`))) where (coalesce((`documenttype_documenttype_id_typology`.`finalclass` = 'DocumentType'),1) and coalesce((`_document`.`finalclass` = 'DocumentFile'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_documentnote`
--

/*!50001 DROP VIEW IF EXISTS `view_documentnote`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_documentnote` AS select distinct `_document`.`id` AS `id`,`_document`.`name` AS `name`,`_document`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `org_name`,`_document`.`documenttype_id` AS `documenttype_id`,`documenttype_documenttype_id_typology`.`name` AS `documenttype_name`,`_document`.`version` AS `version`,`_document`.`description` AS `description`,`_document`.`status` AS `status`,`_documentnote`.`text` AS `text`,`_document`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_document`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce((`_document`.`status` = 'obsolete'),0) AS `obsolescence_flag`,`_document`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,cast(concat(coalesce(`documenttype_documenttype_id_typology`.`name`,'')) as char charset utf8) AS `documenttype_id_friendlyname` from (((`document` `_document` join `organization` `organization_org_id_organization` on((`_document`.`org_id` = `organization_org_id_organization`.`id`))) left join `typology` `documenttype_documenttype_id_typology` on((`_document`.`documenttype_id` = `documenttype_documenttype_id_typology`.`id`))) join `documentnote` `_documentnote` on((`_document`.`id` = `_documentnote`.`id`))) where (coalesce((`documenttype_documenttype_id_typology`.`finalclass` = 'DocumentType'),1) and coalesce((`_document`.`finalclass` = 'DocumentNote'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_documenttype`
--

/*!50001 DROP VIEW IF EXISTS `view_documenttype`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_documenttype` AS select distinct `_typology`.`id` AS `id`,`_typology`.`name` AS `name`,`_typology`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_typology`.`name`,'')) as char charset utf8) AS `friendlyname` from `typology` `_typology` where coalesce((`_typology`.`finalclass` = 'DocumentType'),1) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_documentweb`
--

/*!50001 DROP VIEW IF EXISTS `view_documentweb`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_documentweb` AS select distinct `_document`.`id` AS `id`,`_document`.`name` AS `name`,`_document`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `org_name`,`_document`.`documenttype_id` AS `documenttype_id`,`documenttype_documenttype_id_typology`.`name` AS `documenttype_name`,`_document`.`version` AS `version`,`_document`.`description` AS `description`,`_document`.`status` AS `status`,`_documentweb`.`url` AS `url`,`_document`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_document`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce((`_document`.`status` = 'obsolete'),0) AS `obsolescence_flag`,`_document`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,cast(concat(coalesce(`documenttype_documenttype_id_typology`.`name`,'')) as char charset utf8) AS `documenttype_id_friendlyname` from (((`document` `_document` join `organization` `organization_org_id_organization` on((`_document`.`org_id` = `organization_org_id_organization`.`id`))) left join `typology` `documenttype_documenttype_id_typology` on((`_document`.`documenttype_id` = `documenttype_documenttype_id_typology`.`id`))) join `documentweb` `_documentweb` on((`_document`.`id` = `_documentweb`.`id`))) where (coalesce((`documenttype_documenttype_id_typology`.`finalclass` = 'DocumentType'),1) and coalesce((`_document`.`finalclass` = 'DocumentWeb'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_emergencychange`
--

/*!50001 DROP VIEW IF EXISTS `view_emergencychange`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_emergencychange` AS select distinct `_ticket`.`id` AS `id`,`_ticket`.`operational_status` AS `operational_status`,`_ticket`.`ref` AS `ref`,`_ticket`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `org_name`,`_ticket`.`caller_id` AS `caller_id`,`person_caller_id_contact`.`name` AS `caller_name`,`_ticket`.`team_id` AS `team_id`,`team_team_id_contact`.`email` AS `team_name`,`_ticket`.`agent_id` AS `agent_id`,`person_agent_id_contact`.`name` AS `agent_name`,`_ticket`.`title` AS `title`,`_ticket`.`description` AS `description`,`_ticket`.`description_format` AS `description_format`,`_ticket`.`start_date` AS `start_date`,`_ticket`.`end_date` AS `end_date`,`_ticket`.`last_update` AS `last_update`,`_ticket`.`close_date` AS `close_date`,`_ticket`.`private_log` AS `private_log`,`_ticket`.`private_log_index` AS `private_log_index`,`_change`.`status` AS `status`,`_change`.`reason` AS `reason`,`_change`.`requestor_id` AS `requestor_id`,`person_requestor_id_contact`.`email` AS `requestor_email`,`_change`.`creation_date` AS `creation_date`,`_change`.`impact` AS `impact`,`_change`.`supervisor_group_id` AS `supervisor_group_id`,`team_supervisor_group_id_contact`.`name` AS `supervisor_group_name`,`_change`.`supervisor_id` AS `supervisor_id`,`person_supervisor_id_contact`.`email` AS `supervisor_email`,`_change`.`manager_group_id` AS `manager_group_id`,`team_manager_group_id_contact`.`name` AS `manager_group_name`,`_change`.`manager_id` AS `manager_id`,`person_manager_id_contact`.`email` AS `manager_email`,`_change`.`outage` AS `outage`,`_change`.`fallback` AS `fallback`,`_change`.`parent_id` AS `parent_id`,`change_parent_id_ticket`.`ref` AS `parent_name`,`_change_approved`.`approval_date` AS `approval_date`,`_change_approved`.`approval_comment` AS `approval_comment`,`_ticket`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_ticket`.`ref`,'')) as char charset utf8) AS `friendlyname`,coalesce(((`_ticket`.`operational_status` = 'closed') and ((`_ticket`.`close_date` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00')) or (isnull(`_ticket`.`close_date`) and (`_ticket`.`last_update` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00'))))),0) AS `obsolescence_flag`,`_ticket`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,cast(concat(coalesce(`person_caller_id_person`.`first_name`,''),coalesce(' ',''),coalesce(`person_caller_id_contact`.`name`,'')) as char charset utf8) AS `caller_id_friendlyname`,coalesce((`person_caller_id_contact`.`status` = 'inactive'),0) AS `caller_id_obsolescence_flag`,cast(concat(coalesce(`team_team_id_contact`.`name`,'')) as char charset utf8) AS `team_id_friendlyname`,coalesce((`team_team_id_contact`.`status` = 'inactive'),0) AS `team_id_obsolescence_flag`,cast(concat(coalesce(`person_agent_id_person`.`first_name`,''),coalesce(' ',''),coalesce(`person_agent_id_contact`.`name`,'')) as char charset utf8) AS `agent_id_friendlyname`,coalesce((`person_agent_id_contact`.`status` = 'inactive'),0) AS `agent_id_obsolescence_flag`,cast(concat(coalesce(`person_requestor_id_person`.`first_name`,''),coalesce(' ',''),coalesce(`person_requestor_id_contact`.`name`,'')) as char charset utf8) AS `requestor_id_friendlyname`,coalesce((`person_requestor_id_contact`.`status` = 'inactive'),0) AS `requestor_id_obsolescence_flag`,cast(concat(coalesce(`team_supervisor_group_id_contact`.`name`,'')) as char charset utf8) AS `supervisor_group_id_friendlyname`,coalesce((`team_supervisor_group_id_contact`.`status` = 'inactive'),0) AS `supervisor_group_id_obsolescence_flag`,cast(concat(coalesce(`person_supervisor_id_person`.`first_name`,''),coalesce(' ',''),coalesce(`person_supervisor_id_contact`.`name`,'')) as char charset utf8) AS `supervisor_id_friendlyname`,coalesce((`person_supervisor_id_contact`.`status` = 'inactive'),0) AS `supervisor_id_obsolescence_flag`,cast(concat(coalesce(`team_manager_group_id_contact`.`name`,'')) as char charset utf8) AS `manager_group_id_friendlyname`,coalesce((`team_manager_group_id_contact`.`status` = 'inactive'),0) AS `manager_group_id_obsolescence_flag`,cast(concat(coalesce(`person_manager_id_person`.`first_name`,''),coalesce(' ',''),coalesce(`person_manager_id_contact`.`name`,'')) as char charset utf8) AS `manager_id_friendlyname`,coalesce((`person_manager_id_contact`.`status` = 'inactive'),0) AS `manager_id_obsolescence_flag`,cast(concat(coalesce(`change_parent_id_ticket`.`ref`,'')) as char charset utf8) AS `parent_id_friendlyname`,`change_parent_id_ticket`.`finalclass` AS `parent_id_finalclass_recall`,coalesce(((`change_parent_id_ticket`.`operational_status` = 'closed') and ((`change_parent_id_ticket`.`close_date` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00')) or (isnull(`change_parent_id_ticket`.`close_date`) and (`change_parent_id_ticket`.`last_update` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00'))))),0) AS `parent_id_obsolescence_flag` from ((((((`ticket` `_ticket` join `organization` `organization_org_id_organization` on((`_ticket`.`org_id` = `organization_org_id_organization`.`id`))) left join (`contact` `person_caller_id_contact` join `person` `person_caller_id_person` on((`person_caller_id_contact`.`id` = `person_caller_id_person`.`id`))) on((`_ticket`.`caller_id` = `person_caller_id_contact`.`id`))) left join `contact` `team_team_id_contact` on((`_ticket`.`team_id` = `team_team_id_contact`.`id`))) left join (`contact` `person_agent_id_contact` join `person` `person_agent_id_person` on((`person_agent_id_contact`.`id` = `person_agent_id_person`.`id`))) on((`_ticket`.`agent_id` = `person_agent_id_contact`.`id`))) join ((((((`change` `_change` left join (`contact` `person_requestor_id_contact` join `person` `person_requestor_id_person` on((`person_requestor_id_contact`.`id` = `person_requestor_id_person`.`id`))) on((`_change`.`requestor_id` = `person_requestor_id_contact`.`id`))) left join `contact` `team_supervisor_group_id_contact` on((`_change`.`supervisor_group_id` = `team_supervisor_group_id_contact`.`id`))) left join (`contact` `person_supervisor_id_contact` join `person` `person_supervisor_id_person` on((`person_supervisor_id_contact`.`id` = `person_supervisor_id_person`.`id`))) on((`_change`.`supervisor_id` = `person_supervisor_id_contact`.`id`))) left join `contact` `team_manager_group_id_contact` on((`_change`.`manager_group_id` = `team_manager_group_id_contact`.`id`))) left join (`contact` `person_manager_id_contact` join `person` `person_manager_id_person` on((`person_manager_id_contact`.`id` = `person_manager_id_person`.`id`))) on((`_change`.`manager_id` = `person_manager_id_contact`.`id`))) left join `ticket` `change_parent_id_ticket` on((`_change`.`parent_id` = `change_parent_id_ticket`.`id`))) on((`_ticket`.`id` = `_change`.`id`))) join `change_approved` `_change_approved` on((`_ticket`.`id` = `_change_approved`.`id`))) where (coalesce((`person_caller_id_contact`.`finalclass` = 'Person'),1) and coalesce((`team_team_id_contact`.`finalclass` = 'Team'),1) and coalesce((`person_agent_id_contact`.`finalclass` = 'Person'),1) and coalesce((`_ticket`.`finalclass` = 'EmergencyChange'),1) and coalesce((`person_requestor_id_contact`.`finalclass` = 'Person'),1) and coalesce((`team_supervisor_group_id_contact`.`finalclass` = 'Team'),1) and coalesce((`person_supervisor_id_contact`.`finalclass` = 'Person'),1) and coalesce((`team_manager_group_id_contact`.`finalclass` = 'Team'),1) and coalesce((`person_manager_id_contact`.`finalclass` = 'Person'),1) and coalesce((`change_parent_id_ticket`.`finalclass` in ('RoutineChange','ApprovedChange','NormalChange','EmergencyChange','Change')),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_enclosure`
--

/*!50001 DROP VIEW IF EXISTS `view_enclosure`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_enclosure` AS select distinct `_functionalci`.`id` AS `id`,`_functionalci`.`name` AS `name`,`_functionalci`.`description` AS `description`,`_functionalci`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `organization_name`,`_functionalci`.`business_criticity` AS `business_criticity`,`_functionalci`.`move2production` AS `move2production`,`_physicaldevice`.`serialnumber` AS `serialnumber`,`_physicaldevice`.`location_id` AS `location_id`,`location_location_id_location`.`name` AS `location_name`,`_physicaldevice`.`status` AS `status`,`_physicaldevice`.`brand_id` AS `brand_id`,`brand_brand_id_typology`.`name` AS `brand_name`,`_physicaldevice`.`model_id` AS `model_id`,`model_model_id_typology`.`name` AS `model_name`,`_physicaldevice`.`asset_number` AS `asset_number`,`_physicaldevice`.`purchase_date` AS `purchase_date`,`_physicaldevice`.`end_of_warranty` AS `end_of_warranty`,`_enclosure`.`rack_id` AS `rack_id`,`rack_rack_id_functionalci`.`name` AS `rack_name`,`_enclosure`.`nb_u` AS `nb_u`,`_functionalci`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_functionalci`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce((`_physicaldevice`.`status` = 'obsolete'),0) AS `obsolescence_flag`,`_functionalci`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,cast(concat(coalesce(`location_location_id_location`.`name`,'')) as char charset utf8) AS `location_id_friendlyname`,coalesce((`location_location_id_location`.`status` = 'inactive'),0) AS `location_id_obsolescence_flag`,cast(concat(coalesce(`brand_brand_id_typology`.`name`,'')) as char charset utf8) AS `brand_id_friendlyname`,cast(concat(coalesce(`model_model_id_typology`.`name`,'')) as char charset utf8) AS `model_id_friendlyname`,cast(concat(coalesce(`rack_rack_id_functionalci`.`name`,'')) as char charset utf8) AS `rack_id_friendlyname`,coalesce((`rack_rack_id_physicaldevice`.`status` = 'obsolete'),0) AS `rack_id_obsolescence_flag` from (((`functionalci` `_functionalci` join `organization` `organization_org_id_organization` on((`_functionalci`.`org_id` = `organization_org_id_organization`.`id`))) join (((`physicaldevice` `_physicaldevice` left join `location` `location_location_id_location` on((`_physicaldevice`.`location_id` = `location_location_id_location`.`id`))) left join `typology` `brand_brand_id_typology` on((`_physicaldevice`.`brand_id` = `brand_brand_id_typology`.`id`))) left join `typology` `model_model_id_typology` on((`_physicaldevice`.`model_id` = `model_model_id_typology`.`id`))) on((`_functionalci`.`id` = `_physicaldevice`.`id`))) join (`enclosure` `_enclosure` join (`functionalci` `rack_rack_id_functionalci` join `physicaldevice` `rack_rack_id_physicaldevice` on((`rack_rack_id_functionalci`.`id` = `rack_rack_id_physicaldevice`.`id`))) on((`_enclosure`.`rack_id` = `rack_rack_id_functionalci`.`id`))) on((`_functionalci`.`id` = `_enclosure`.`id`))) where (coalesce((`_functionalci`.`finalclass` = 'Enclosure'),1) and coalesce((`brand_brand_id_typology`.`finalclass` = 'Brand'),1) and coalesce((`model_model_id_typology`.`finalclass` = 'Model'),1) and coalesce((`rack_rack_id_functionalci`.`finalclass` = 'Rack'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_faq`
--

/*!50001 DROP VIEW IF EXISTS `view_faq`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_faq` AS select distinct `_faq`.`id` AS `id`,`_faq`.`title` AS `title`,`_faq`.`summary` AS `summary`,`_faq`.`description` AS `description`,`_faq`.`category_id` AS `category_id`,`faqcategory_category_id_faqcategory`.`nam` AS `category_name`,`_faq`.`error_code` AS `error_code`,`_faq`.`key_words` AS `key_words`,cast(concat(coalesce(`_faq`.`title`,'')) as char charset utf8) AS `friendlyname`,cast(concat(coalesce(`faqcategory_category_id_faqcategory`.`nam`,'')) as char charset utf8) AS `category_id_friendlyname` from (`faq` `_faq` join `faqcategory` `faqcategory_category_id_faqcategory` on((`_faq`.`category_id` = `faqcategory_category_id_faqcategory`.`id`))) where 1 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_faqcategory`
--

/*!50001 DROP VIEW IF EXISTS `view_faqcategory`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_faqcategory` AS select distinct `_faqcategory`.`id` AS `id`,`_faqcategory`.`nam` AS `name`,cast(concat(coalesce(`_faqcategory`.`nam`,'')) as char charset utf8) AS `friendlyname` from `faqcategory` `_faqcategory` where 1 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_farm`
--

/*!50001 DROP VIEW IF EXISTS `view_farm`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_farm` AS select distinct `_functionalci`.`id` AS `id`,`_functionalci`.`name` AS `name`,`_functionalci`.`description` AS `description`,`_functionalci`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `organization_name`,`_functionalci`.`business_criticity` AS `business_criticity`,`_functionalci`.`move2production` AS `move2production`,`_virtualdevice`.`status` AS `status`,`_farm`.`redundancy` AS `redundancy`,`_functionalci`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_functionalci`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce((`_virtualdevice`.`status` = 'obsolete'),0) AS `obsolescence_flag`,`_functionalci`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag` from (((`functionalci` `_functionalci` join `organization` `organization_org_id_organization` on((`_functionalci`.`org_id` = `organization_org_id_organization`.`id`))) join `virtualdevice` `_virtualdevice` on((`_functionalci`.`id` = `_virtualdevice`.`id`))) join `farm` `_farm` on((`_functionalci`.`id` = `_farm`.`id`))) where coalesce((`_functionalci`.`finalclass` = 'Farm'),1) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_fiberchannelinterface`
--

/*!50001 DROP VIEW IF EXISTS `view_fiberchannelinterface`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_fiberchannelinterface` AS select distinct `_networkinterface`.`id` AS `id`,`_networkinterface`.`name` AS `name`,`_fiberchannelinterface`.`speed` AS `speed`,`_fiberchannelinterface`.`topology` AS `topology`,`_fiberchannelinterface`.`wwn` AS `wwn`,`_fiberchannelinterface`.`datacenterdevice_id` AS `datacenterdevice_id`,`datacenterdevice_datacenterdevice_id_functionalci`.`name` AS `datacenterdevice_name`,`_networkinterface`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_networkinterface`.`name`,''),coalesce(' ',''),coalesce(`datacenterdevice_datacenterdevice_id_functionalci`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce(coalesce((`datacenterdevice_datacenterdevice_id_physicaldevice`.`status` = 'obsolete'),0),0) AS `obsolescence_flag`,`_networkinterface`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`datacenterdevice_datacenterdevice_id_functionalci`.`name`,'')) as char charset utf8) AS `datacenterdevice_id_friendlyname`,`datacenterdevice_datacenterdevice_id_functionalci`.`finalclass` AS `datacenterdevice_id_finalclass_recall`,coalesce((`datacenterdevice_datacenterdevice_id_physicaldevice`.`status` = 'obsolete'),0) AS `datacenterdevice_id_obsolescence_flag` from (`networkinterface` `_networkinterface` join (`fiberchannelinterface` `_fiberchannelinterface` join (`functionalci` `datacenterdevice_datacenterdevice_id_functionalci` join `physicaldevice` `datacenterdevice_datacenterdevice_id_physicaldevice` on((`datacenterdevice_datacenterdevice_id_functionalci`.`id` = `datacenterdevice_datacenterdevice_id_physicaldevice`.`id`))) on((`_fiberchannelinterface`.`datacenterdevice_id` = `datacenterdevice_datacenterdevice_id_functionalci`.`id`))) on((`_networkinterface`.`id` = `_fiberchannelinterface`.`id`))) where (coalesce((`_networkinterface`.`finalclass` = 'FiberChannelInterface'),1) and coalesce((`datacenterdevice_datacenterdevice_id_functionalci`.`finalclass` in ('NetworkDevice','Server','StorageSystem','SANSwitch','TapeLibrary','NAS','DatacenterDevice')),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_functionalci`
--

/*!50001 DROP VIEW IF EXISTS `view_functionalci`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_functionalci` AS select distinct `_functionalci`.`id` AS `id`,`_functionalci`.`name` AS `name`,`_functionalci`.`description` AS `description`,`_functionalci`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `organization_name`,`_functionalci`.`business_criticity` AS `business_criticity`,`_functionalci`.`move2production` AS `move2production`,`_functionalci`.`finalclass` AS `finalclass`,if((`_functionalci`.`finalclass` in ('Middleware','DBServer','WebServer','PCSoftware','OtherSoftware')),cast(concat(coalesce(`_functionalci`.`name`,''),coalesce(' ',''),coalesce(`functionalci_system_id_functionalci`.`name`,'')) as char charset utf8),cast(concat(coalesce(`_functionalci`.`name`,'')) as char charset utf8)) AS `friendlyname`,if((`_functionalci`.`finalclass` = 'FunctionalCI'),coalesce(0,0),if((`_functionalci`.`finalclass` in ('Hypervisor','Farm','VirtualMachine')),coalesce((`_fn_virtualdevice_virtualdevice`.`status` = 'obsolete'),0),if((`_functionalci`.`finalclass` = 'WebApplication'),coalesce(coalesce((`webserver_webserver_id_softwareinstance`.`status` = 'inactive'),0),0),if((`_functionalci`.`finalclass` = 'DatabaseSchema'),coalesce(coalesce((`dbserver_dbserver_id_softwareinstance`.`status` = 'inactive'),0),0),if((`_functionalci`.`finalclass` = 'MiddlewareInstance'),coalesce(coalesce((`middleware_middleware_id_softwareinstance`.`status` = 'inactive'),0),0),if((`_functionalci`.`finalclass` in ('Middleware','DBServer','WebServer','PCSoftware','OtherSoftware')),coalesce((`_fn_softwareinstance_softwareinstance`.`status` = 'inactive'),0),if((`_functionalci`.`finalclass` = 'BusinessProcess'),coalesce((`_fn_businessprocess_businessprocess`.`status` = 'inactive'),0),if((`_functionalci`.`finalclass` = 'ApplicationSolution'),coalesce((`_fn_applicationsolution_applicationsolution`.`status` = 'inactive'),0),coalesce((`_fn_physicaldevice_physicaldevice`.`status` = 'obsolete'),0))))))))) AS `obsolescence_flag`,`_functionalci`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag` from (((((((((`functionalci` `_functionalci` join `organization` `organization_org_id_organization` on((`_functionalci`.`org_id` = `organization_org_id_organization`.`id`))) left join (`softwareinstance` `_fn_softwareinstance_softwareinstance` join `functionalci` `functionalci_system_id_functionalci` on((`_fn_softwareinstance_softwareinstance`.`functionalci_id` = `functionalci_system_id_functionalci`.`id`))) on((`_functionalci`.`id` = `_fn_softwareinstance_softwareinstance`.`id`))) left join `virtualdevice` `_fn_virtualdevice_virtualdevice` on((`_functionalci`.`id` = `_fn_virtualdevice_virtualdevice`.`id`))) left join (`webapplication` `_fn_webapplication_webapplication` join (`functionalci` `webserver_webserver_id_functionalci` join `softwareinstance` `webserver_webserver_id_softwareinstance` on((`webserver_webserver_id_functionalci`.`id` = `webserver_webserver_id_softwareinstance`.`id`))) on((`_fn_webapplication_webapplication`.`webserver_id` = `webserver_webserver_id_functionalci`.`id`))) on((`_functionalci`.`id` = `_fn_webapplication_webapplication`.`id`))) left join (`databaseschema` `_fn_databaseschema_databaseschema` join (`functionalci` `dbserver_dbserver_id_functionalci` join `softwareinstance` `dbserver_dbserver_id_softwareinstance` on((`dbserver_dbserver_id_functionalci`.`id` = `dbserver_dbserver_id_softwareinstance`.`id`))) on((`_fn_databaseschema_databaseschema`.`dbserver_id` = `dbserver_dbserver_id_functionalci`.`id`))) on((`_functionalci`.`id` = `_fn_databaseschema_databaseschema`.`id`))) left join (`middlewareinstance` `_fn_middlewareinstance_middlewareinstance` join (`functionalci` `middleware_middleware_id_functionalci` join `softwareinstance` `middleware_middleware_id_softwareinstance` on((`middleware_middleware_id_functionalci`.`id` = `middleware_middleware_id_softwareinstance`.`id`))) on((`_fn_middlewareinstance_middlewareinstance`.`middleware_id` = `middleware_middleware_id_functionalci`.`id`))) on((`_functionalci`.`id` = `_fn_middlewareinstance_middlewareinstance`.`id`))) left join `businessprocess` `_fn_businessprocess_businessprocess` on((`_functionalci`.`id` = `_fn_businessprocess_businessprocess`.`id`))) left join `applicationsolution` `_fn_applicationsolution_applicationsolution` on((`_functionalci`.`id` = `_fn_applicationsolution_applicationsolution`.`id`))) left join `physicaldevice` `_fn_physicaldevice_physicaldevice` on((`_functionalci`.`id` = `_fn_physicaldevice_physicaldevice`.`id`))) where (coalesce((`webserver_webserver_id_functionalci`.`finalclass` = 'WebServer'),1) and coalesce((`dbserver_dbserver_id_functionalci`.`finalclass` = 'DBServer'),1) and coalesce((`middleware_middleware_id_functionalci`.`finalclass` = 'Middleware'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_group`
--

/*!50001 DROP VIEW IF EXISTS `view_group`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_group` AS select distinct `_group`.`id` AS `id`,`_group`.`name` AS `name`,`_group`.`status` AS `status`,`_group`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `owner_name`,`_group`.`description` AS `description`,`_group`.`type` AS `type`,`_group`.`parent_id` AS `parent_id`,`group_parent_id_group`.`name` AS `parent_name`,cast(concat(coalesce(`_group`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce((`_group`.`status` = 'obsolete'),0) AS `obsolescence_flag`,`_group`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,cast(concat(coalesce(`group_parent_id_group`.`name`,'')) as char charset utf8) AS `parent_id_friendlyname`,coalesce((`group_parent_id_group`.`status` = 'obsolete'),0) AS `parent_id_obsolescence_flag` from ((`group` `_group` join `organization` `organization_org_id_organization` on((`_group`.`org_id` = `organization_org_id_organization`.`id`))) left join `group` `group_parent_id_group` on((`_group`.`parent_id` = `group_parent_id_group`.`id`))) where 1 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_hypervisor`
--

/*!50001 DROP VIEW IF EXISTS `view_hypervisor`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_hypervisor` AS select distinct `_functionalci`.`id` AS `id`,`_functionalci`.`name` AS `name`,`_functionalci`.`description` AS `description`,`_functionalci`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `organization_name`,`_functionalci`.`business_criticity` AS `business_criticity`,`_functionalci`.`move2production` AS `move2production`,`_virtualdevice`.`status` AS `status`,`_hypervisor`.`farm_id` AS `farm_id`,`farm_farm_id_functionalci`.`name` AS `farm_name`,`_hypervisor`.`server_id` AS `server_id`,`server_server_id_functionalci`.`name` AS `server_name`,`_functionalci`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_functionalci`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce((`_virtualdevice`.`status` = 'obsolete'),0) AS `obsolescence_flag`,`_functionalci`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,cast(concat(coalesce(`farm_farm_id_functionalci`.`name`,'')) as char charset utf8) AS `farm_id_friendlyname`,coalesce((`farm_farm_id_virtualdevice`.`status` = 'obsolete'),0) AS `farm_id_obsolescence_flag`,cast(concat(coalesce(`server_server_id_functionalci`.`name`,'')) as char charset utf8) AS `server_id_friendlyname`,coalesce((`server_server_id_physicaldevice`.`status` = 'obsolete'),0) AS `server_id_obsolescence_flag` from (((`functionalci` `_functionalci` join `organization` `organization_org_id_organization` on((`_functionalci`.`org_id` = `organization_org_id_organization`.`id`))) join `virtualdevice` `_virtualdevice` on((`_functionalci`.`id` = `_virtualdevice`.`id`))) join ((`hypervisor` `_hypervisor` left join (`functionalci` `farm_farm_id_functionalci` join `virtualdevice` `farm_farm_id_virtualdevice` on((`farm_farm_id_functionalci`.`id` = `farm_farm_id_virtualdevice`.`id`))) on((`_hypervisor`.`farm_id` = `farm_farm_id_functionalci`.`id`))) left join (`functionalci` `server_server_id_functionalci` join `physicaldevice` `server_server_id_physicaldevice` on((`server_server_id_functionalci`.`id` = `server_server_id_physicaldevice`.`id`))) on((`_hypervisor`.`server_id` = `server_server_id_functionalci`.`id`))) on((`_functionalci`.`id` = `_hypervisor`.`id`))) where (coalesce((`_functionalci`.`finalclass` = 'Hypervisor'),1) and coalesce((`farm_farm_id_functionalci`.`finalclass` = 'Farm'),1) and coalesce((`server_server_id_functionalci`.`finalclass` = 'Server'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_incident`
--

/*!50001 DROP VIEW IF EXISTS `view_incident`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_incident` AS select distinct `_ticket`.`id` AS `id`,`_ticket`.`operational_status` AS `operational_status`,`_ticket`.`ref` AS `ref`,`_ticket`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `org_name`,`_ticket`.`caller_id` AS `caller_id`,`person_caller_id_contact`.`name` AS `caller_name`,`_ticket`.`team_id` AS `team_id`,`team_team_id_contact`.`email` AS `team_name`,`_ticket`.`agent_id` AS `agent_id`,`person_agent_id_contact`.`name` AS `agent_name`,`_ticket`.`title` AS `title`,`_ticket`.`description` AS `description`,`_ticket`.`description_format` AS `description_format`,`_ticket`.`start_date` AS `start_date`,`_ticket`.`end_date` AS `end_date`,`_ticket`.`last_update` AS `last_update`,`_ticket`.`close_date` AS `close_date`,`_ticket`.`private_log` AS `private_log`,`_ticket`.`private_log_index` AS `private_log_index`,`_ticket_incident`.`status` AS `status`,`_ticket_incident`.`impact` AS `impact`,`_ticket_incident`.`priority` AS `priority`,`_ticket_incident`.`urgency` AS `urgency`,`_ticket_incident`.`origin` AS `origin`,`_ticket_incident`.`service_id` AS `service_id`,`service_service_id_service`.`name` AS `service_name`,`_ticket_incident`.`servicesubcategory_id` AS `servicesubcategory_id`,`servicesubcategory_servicesubcategory_id_servicesubcategory`.`name` AS `servicesubcategory_name`,`_ticket_incident`.`escalation_flag` AS `escalation_flag`,`_ticket_incident`.`escalation_reason` AS `escalation_reason`,`_ticket_incident`.`assignment_date` AS `assignment_date`,`_ticket_incident`.`resolution_date` AS `resolution_date`,`_ticket_incident`.`last_pending_date` AS `last_pending_date`,`_ticket_incident`.`cumulatedpending_timespent` AS `cumulatedpending`,`_ticket_incident`.`cumulatedpending_started` AS `cumulatedpending_started`,`_ticket_incident`.`cumulatedpending_laststart` AS `cumulatedpending_laststart`,`_ticket_incident`.`cumulatedpending_stopped` AS `cumulatedpending_stopped`,`_ticket_incident`.`tto_timespent` AS `tto`,`_ticket_incident`.`tto_started` AS `tto_started`,`_ticket_incident`.`tto_laststart` AS `tto_laststart`,`_ticket_incident`.`tto_stopped` AS `tto_stopped`,`_ticket_incident`.`tto_75_deadline` AS `tto_75_deadline`,`_ticket_incident`.`tto_75_passed` AS `tto_75_passed`,`_ticket_incident`.`tto_75_triggered` AS `tto_75_triggered`,`_ticket_incident`.`tto_75_overrun` AS `tto_75_overrun`,`_ticket_incident`.`tto_100_deadline` AS `tto_100_deadline`,`_ticket_incident`.`tto_100_passed` AS `tto_100_passed`,`_ticket_incident`.`tto_100_triggered` AS `tto_100_triggered`,`_ticket_incident`.`tto_100_overrun` AS `tto_100_overrun`,`_ticket_incident`.`ttr_timespent` AS `ttr`,`_ticket_incident`.`ttr_started` AS `ttr_started`,`_ticket_incident`.`ttr_laststart` AS `ttr_laststart`,`_ticket_incident`.`ttr_stopped` AS `ttr_stopped`,`_ticket_incident`.`ttr_75_deadline` AS `ttr_75_deadline`,`_ticket_incident`.`ttr_75_passed` AS `ttr_75_passed`,`_ticket_incident`.`ttr_75_triggered` AS `ttr_75_triggered`,`_ticket_incident`.`ttr_75_overrun` AS `ttr_75_overrun`,`_ticket_incident`.`ttr_100_deadline` AS `ttr_100_deadline`,`_ticket_incident`.`ttr_100_passed` AS `ttr_100_passed`,`_ticket_incident`.`ttr_100_triggered` AS `ttr_100_triggered`,`_ticket_incident`.`ttr_100_overrun` AS `ttr_100_overrun`,`_ticket_incident`.`tto_100_deadline` AS `tto_escalation_deadline`,`_ticket_incident`.`tto_100_passed` AS `sla_tto_passed`,`_ticket_incident`.`tto_100_overrun` AS `sla_tto_over`,`_ticket_incident`.`ttr_100_deadline` AS `ttr_escalation_deadline`,`_ticket_incident`.`ttr_100_passed` AS `sla_ttr_passed`,`_ticket_incident`.`ttr_100_overrun` AS `sla_ttr_over`,`_ticket_incident`.`time_spent` AS `time_spent`,`_ticket_incident`.`resolution_code` AS `resolution_code`,`_ticket_incident`.`solution` AS `solution`,`_ticket_incident`.`pending_reason` AS `pending_reason`,`_ticket_incident`.`parent_incident_id` AS `parent_incident_id`,`incident_parent_incident_id_ticket`.`ref` AS `parent_incident_ref`,`_ticket_incident`.`parent_problem_id` AS `parent_problem_id`,`problem_parent_problem_id_ticket`.`ref` AS `parent_problem_ref`,`_ticket_incident`.`parent_change_id` AS `parent_change_id`,`change_parent_change_id_ticket`.`ref` AS `parent_change_ref`,`_ticket_incident`.`public_log` AS `public_log`,`_ticket_incident`.`public_log_index` AS `public_log_index`,`_ticket_incident`.`user_satisfaction` AS `user_satisfaction`,`_ticket_incident`.`user_commment` AS `user_comment`,`_ticket`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_ticket`.`ref`,'')) as char charset utf8) AS `friendlyname`,coalesce(((`_ticket`.`operational_status` = 'closed') and ((`_ticket`.`close_date` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00')) or (isnull(`_ticket`.`close_date`) and (`_ticket`.`last_update` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00'))))),0) AS `obsolescence_flag`,`_ticket`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,cast(concat(coalesce(`person_caller_id_person`.`first_name`,''),coalesce(' ',''),coalesce(`person_caller_id_contact`.`name`,'')) as char charset utf8) AS `caller_id_friendlyname`,coalesce((`person_caller_id_contact`.`status` = 'inactive'),0) AS `caller_id_obsolescence_flag`,cast(concat(coalesce(`team_team_id_contact`.`name`,'')) as char charset utf8) AS `team_id_friendlyname`,coalesce((`team_team_id_contact`.`status` = 'inactive'),0) AS `team_id_obsolescence_flag`,cast(concat(coalesce(`person_agent_id_person`.`first_name`,''),coalesce(' ',''),coalesce(`person_agent_id_contact`.`name`,'')) as char charset utf8) AS `agent_id_friendlyname`,coalesce((`person_agent_id_contact`.`status` = 'inactive'),0) AS `agent_id_obsolescence_flag`,cast(concat(coalesce(`service_service_id_service`.`name`,'')) as char charset utf8) AS `service_id_friendlyname`,cast(concat(coalesce(`servicesubcategory_servicesubcategory_id_servicesubcategory`.`name`,'')) as char charset utf8) AS `servicesubcategory_id_friendlyname`,cast(concat(coalesce(`incident_parent_incident_id_ticket`.`ref`,'')) as char charset utf8) AS `parent_incident_id_friendlyname`,coalesce(((`incident_parent_incident_id_ticket`.`operational_status` = 'closed') and ((`incident_parent_incident_id_ticket`.`close_date` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00')) or (isnull(`incident_parent_incident_id_ticket`.`close_date`) and (`incident_parent_incident_id_ticket`.`last_update` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00'))))),0) AS `parent_incident_id_obsolescence_flag`,cast(concat(coalesce(`problem_parent_problem_id_ticket`.`ref`,'')) as char charset utf8) AS `parent_problem_id_friendlyname`,coalesce(((`problem_parent_problem_id_ticket`.`operational_status` = 'closed') and ((`problem_parent_problem_id_ticket`.`close_date` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00')) or (isnull(`problem_parent_problem_id_ticket`.`close_date`) and (`problem_parent_problem_id_ticket`.`last_update` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00'))))),0) AS `parent_problem_id_obsolescence_flag`,cast(concat(coalesce(`change_parent_change_id_ticket`.`ref`,'')) as char charset utf8) AS `parent_change_id_friendlyname`,`change_parent_change_id_ticket`.`finalclass` AS `parent_change_id_finalclass_recall`,coalesce(((`change_parent_change_id_ticket`.`operational_status` = 'closed') and ((`change_parent_change_id_ticket`.`close_date` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00')) or (isnull(`change_parent_change_id_ticket`.`close_date`) and (`change_parent_change_id_ticket`.`last_update` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00'))))),0) AS `parent_change_id_obsolescence_flag` from (((((`ticket` `_ticket` join `organization` `organization_org_id_organization` on((`_ticket`.`org_id` = `organization_org_id_organization`.`id`))) left join (`contact` `person_caller_id_contact` join `person` `person_caller_id_person` on((`person_caller_id_contact`.`id` = `person_caller_id_person`.`id`))) on((`_ticket`.`caller_id` = `person_caller_id_contact`.`id`))) left join `contact` `team_team_id_contact` on((`_ticket`.`team_id` = `team_team_id_contact`.`id`))) left join (`contact` `person_agent_id_contact` join `person` `person_agent_id_person` on((`person_agent_id_contact`.`id` = `person_agent_id_person`.`id`))) on((`_ticket`.`agent_id` = `person_agent_id_contact`.`id`))) join (((((`ticket_incident` `_ticket_incident` left join `service` `service_service_id_service` on((`_ticket_incident`.`service_id` = `service_service_id_service`.`id`))) left join `servicesubcategory` `servicesubcategory_servicesubcategory_id_servicesubcategory` on((`_ticket_incident`.`servicesubcategory_id` = `servicesubcategory_servicesubcategory_id_servicesubcategory`.`id`))) left join `ticket` `incident_parent_incident_id_ticket` on((`_ticket_incident`.`parent_incident_id` = `incident_parent_incident_id_ticket`.`id`))) left join `ticket` `problem_parent_problem_id_ticket` on((`_ticket_incident`.`parent_problem_id` = `problem_parent_problem_id_ticket`.`id`))) left join `ticket` `change_parent_change_id_ticket` on((`_ticket_incident`.`parent_change_id` = `change_parent_change_id_ticket`.`id`))) on((`_ticket`.`id` = `_ticket_incident`.`id`))) where (coalesce((`person_caller_id_contact`.`finalclass` = 'Person'),1) and coalesce((`team_team_id_contact`.`finalclass` = 'Team'),1) and coalesce((`person_agent_id_contact`.`finalclass` = 'Person'),1) and coalesce((`_ticket`.`finalclass` = 'Incident'),1) and coalesce((`incident_parent_incident_id_ticket`.`finalclass` = 'Incident'),1) and coalesce((`problem_parent_problem_id_ticket`.`finalclass` = 'Problem'),1) and coalesce((`change_parent_change_id_ticket`.`finalclass` in ('RoutineChange','ApprovedChange','NormalChange','EmergencyChange','Change')),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_iosversion`
--

/*!50001 DROP VIEW IF EXISTS `view_iosversion`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_iosversion` AS select distinct `_typology`.`id` AS `id`,`_typology`.`name` AS `name`,`_iosversion`.`brand_id` AS `brand_id`,`brand_brand_id_typology`.`name` AS `brand_name`,`_typology`.`finalclass` AS `finalclass`,cast(concat(coalesce(`brand_brand_id_typology`.`name`,''),coalesce(' ',''),coalesce(`_typology`.`name`,'')) as char charset utf8) AS `friendlyname`,cast(concat(coalesce(`brand_brand_id_typology`.`name`,'')) as char charset utf8) AS `brand_id_friendlyname` from (`typology` `_typology` join (`iosversion` `_iosversion` join `typology` `brand_brand_id_typology` on((`_iosversion`.`brand_id` = `brand_brand_id_typology`.`id`))) on((`_typology`.`id` = `_iosversion`.`id`))) where (coalesce((`_typology`.`finalclass` = 'IOSVersion'),1) and coalesce((`brand_brand_id_typology`.`finalclass` = 'Brand'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_ipinterface`
--

/*!50001 DROP VIEW IF EXISTS `view_ipinterface`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_ipinterface` AS select distinct `_networkinterface`.`id` AS `id`,`_networkinterface`.`name` AS `name`,`_ipinterface`.`ipaddress` AS `ipaddress`,`_ipinterface`.`macaddress` AS `macaddress`,`_ipinterface`.`comment` AS `comment`,`_ipinterface`.`ipgateway` AS `ipgateway`,`_ipinterface`.`ipmask` AS `ipmask`,`_ipinterface`.`speed` AS `speed`,`_networkinterface`.`finalclass` AS `finalclass`,if((`_networkinterface`.`finalclass` = 'IPInterface'),cast(concat(coalesce(`_networkinterface`.`name`,'')) as char charset utf8),if((`_networkinterface`.`finalclass` = 'LogicalInterface'),cast(concat(coalesce(`_networkinterface`.`name`,''),coalesce(' ',''),coalesce(`virtualmachine_virtualmachine_id_functionalci`.`name`,'')) as char charset utf8),cast(concat(coalesce(`_networkinterface`.`name`,''),coalesce(' ',''),coalesce(`connectableci_connectableci_id_functionalci`.`name`,'')) as char charset utf8))) AS `friendlyname`,if((`_networkinterface`.`finalclass` = 'IPInterface'),coalesce(0,0),if((`_networkinterface`.`finalclass` = 'LogicalInterface'),coalesce(coalesce((`virtualmachine_virtualmachine_id_virtualdevice`.`status` = 'obsolete'),0),0),coalesce(coalesce((`connectableci_connectableci_id_physicaldevice`.`status` = 'obsolete'),0),0))) AS `obsolescence_flag`,`_networkinterface`.`obsolescence_date` AS `obsolescence_date` from (((`networkinterface` `_networkinterface` join `ipinterface` `_ipinterface` on((`_networkinterface`.`id` = `_ipinterface`.`id`))) left join (`logicalinterface` `_fn_logicalinterface_logicalinterface` join (`functionalci` `virtualmachine_virtualmachine_id_functionalci` join `virtualdevice` `virtualmachine_virtualmachine_id_virtualdevice` on((`virtualmachine_virtualmachine_id_functionalci`.`id` = `virtualmachine_virtualmachine_id_virtualdevice`.`id`))) on((`_fn_logicalinterface_logicalinterface`.`virtualmachine_id` = `virtualmachine_virtualmachine_id_functionalci`.`id`))) on((`_networkinterface`.`id` = `_fn_logicalinterface_logicalinterface`.`id`))) left join (`physicalinterface` `_fn_physicalinterface_physicalinterface` join (`functionalci` `connectableci_connectableci_id_functionalci` join `physicaldevice` `connectableci_connectableci_id_physicaldevice` on((`connectableci_connectableci_id_functionalci`.`id` = `connectableci_connectableci_id_physicaldevice`.`id`))) on((`_fn_physicalinterface_physicalinterface`.`connectableci_id` = `connectableci_connectableci_id_functionalci`.`id`))) on((`_networkinterface`.`id` = `_fn_physicalinterface_physicalinterface`.`id`))) where (coalesce((`_networkinterface`.`finalclass` in ('PhysicalInterface','LogicalInterface','IPInterface')),1) and coalesce((`virtualmachine_virtualmachine_id_functionalci`.`finalclass` = 'VirtualMachine'),1) and coalesce((`connectableci_connectableci_id_functionalci`.`finalclass` in ('DatacenterDevice','NetworkDevice','Server','PC','Printer','StorageSystem','SANSwitch','TapeLibrary','NAS','ConnectableCI')),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_ipphone`
--

/*!50001 DROP VIEW IF EXISTS `view_ipphone`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_ipphone` AS select distinct `_functionalci`.`id` AS `id`,`_functionalci`.`name` AS `name`,`_functionalci`.`description` AS `description`,`_functionalci`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `organization_name`,`_functionalci`.`business_criticity` AS `business_criticity`,`_functionalci`.`move2production` AS `move2production`,`_physicaldevice`.`serialnumber` AS `serialnumber`,`_physicaldevice`.`location_id` AS `location_id`,`location_location_id_location`.`name` AS `location_name`,`_physicaldevice`.`status` AS `status`,`_physicaldevice`.`brand_id` AS `brand_id`,`brand_brand_id_typology`.`name` AS `brand_name`,`_physicaldevice`.`model_id` AS `model_id`,`model_model_id_typology`.`name` AS `model_name`,`_physicaldevice`.`asset_number` AS `asset_number`,`_physicaldevice`.`purchase_date` AS `purchase_date`,`_physicaldevice`.`end_of_warranty` AS `end_of_warranty`,`_telephonyci`.`phonenumber` AS `phonenumber`,`_functionalci`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_functionalci`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce((`_physicaldevice`.`status` = 'obsolete'),0) AS `obsolescence_flag`,`_functionalci`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,cast(concat(coalesce(`location_location_id_location`.`name`,'')) as char charset utf8) AS `location_id_friendlyname`,coalesce((`location_location_id_location`.`status` = 'inactive'),0) AS `location_id_obsolescence_flag`,cast(concat(coalesce(`brand_brand_id_typology`.`name`,'')) as char charset utf8) AS `brand_id_friendlyname`,cast(concat(coalesce(`model_model_id_typology`.`name`,'')) as char charset utf8) AS `model_id_friendlyname` from (((`functionalci` `_functionalci` join `organization` `organization_org_id_organization` on((`_functionalci`.`org_id` = `organization_org_id_organization`.`id`))) join (((`physicaldevice` `_physicaldevice` left join `location` `location_location_id_location` on((`_physicaldevice`.`location_id` = `location_location_id_location`.`id`))) left join `typology` `brand_brand_id_typology` on((`_physicaldevice`.`brand_id` = `brand_brand_id_typology`.`id`))) left join `typology` `model_model_id_typology` on((`_physicaldevice`.`model_id` = `model_model_id_typology`.`id`))) on((`_functionalci`.`id` = `_physicaldevice`.`id`))) join `telephonyci` `_telephonyci` on((`_functionalci`.`id` = `_telephonyci`.`id`))) where (coalesce((`_functionalci`.`finalclass` = 'IPPhone'),1) and coalesce((`brand_brand_id_typology`.`finalclass` = 'Brand'),1) and coalesce((`model_model_id_typology`.`finalclass` = 'Model'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_knownerror`
--

/*!50001 DROP VIEW IF EXISTS `view_knownerror`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_knownerror` AS select distinct `_knownerror`.`id` AS `id`,`_knownerror`.`name` AS `name`,`_knownerror`.`cust_id` AS `org_id`,`organization_org_id_organization`.`name` AS `cust_name`,`_knownerror`.`problem_id` AS `problem_id`,`problem_problem_id_ticket`.`ref` AS `problem_ref`,`_knownerror`.`symptom` AS `symptom`,`_knownerror`.`rootcause` AS `root_cause`,`_knownerror`.`workaround` AS `workaround`,`_knownerror`.`solution` AS `solution`,`_knownerror`.`error_code` AS `error_code`,`_knownerror`.`domain` AS `domain`,`_knownerror`.`vendor` AS `vendor`,`_knownerror`.`model` AS `model`,`_knownerror`.`version` AS `version`,cast(concat(coalesce(`_knownerror`.`name`,'')) as char charset utf8) AS `friendlyname`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,cast(concat(coalesce(`problem_problem_id_ticket`.`ref`,'')) as char charset utf8) AS `problem_id_friendlyname`,coalesce(((`problem_problem_id_ticket`.`operational_status` = 'closed') and ((`problem_problem_id_ticket`.`close_date` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00')) or (isnull(`problem_problem_id_ticket`.`close_date`) and (`problem_problem_id_ticket`.`last_update` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00'))))),0) AS `problem_id_obsolescence_flag` from ((`knownerror` `_knownerror` join `organization` `organization_org_id_organization` on((`_knownerror`.`cust_id` = `organization_org_id_organization`.`id`))) left join `ticket` `problem_problem_id_ticket` on((`_knownerror`.`problem_id` = `problem_problem_id_ticket`.`id`))) where coalesce((`problem_problem_id_ticket`.`finalclass` = 'Problem'),1) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_licence`
--

/*!50001 DROP VIEW IF EXISTS `view_licence`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_licence` AS select distinct `_licence`.`id` AS `id`,`_licence`.`name` AS `name`,`_licence`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `organization_name`,`_licence`.`usage_limit` AS `usage_limit`,`_licence`.`description` AS `description`,`_licence`.`start_date` AS `start_date`,`_licence`.`end_date` AS `end_date`,`_licence`.`licence_key` AS `licence_key`,`_licence`.`perpetual` AS `perpetual`,`_licence`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_licence`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce(((`_licence`.`perpetual` = 'no') and (isnull(`_licence`.`end_date`) = 0) and (`_licence`.`end_date` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00'))),0) AS `obsolescence_flag`,`_licence`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag` from (`licence` `_licence` join `organization` `organization_org_id_organization` on((`_licence`.`org_id` = `organization_org_id_organization`.`id`))) where 1 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_lnkapplicationsolutiontobusinessprocess`
--

/*!50001 DROP VIEW IF EXISTS `view_lnkapplicationsolutiontobusinessprocess`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_lnkapplicationsolutiontobusinessprocess` AS select distinct `_lnkapplicationsolutiontobusinessprocess`.`id` AS `id`,`_lnkapplicationsolutiontobusinessprocess`.`businessprocess_id` AS `businessprocess_id`,`businessprocess_businessprocess_id_functionalci`.`name` AS `businessprocess_name`,`_lnkapplicationsolutiontobusinessprocess`.`applicationsolution_id` AS `applicationsolution_id`,`applicationsolution_applicationsolution_id_functionalci`.`name` AS `applicationsolution_name`,cast(concat(coalesce(`_lnkapplicationsolutiontobusinessprocess`.`businessprocess_id`,''),coalesce(' ',''),coalesce(`_lnkapplicationsolutiontobusinessprocess`.`applicationsolution_id`,'')) as char charset utf8) AS `friendlyname`,cast(concat(coalesce(`businessprocess_businessprocess_id_functionalci`.`name`,'')) as char charset utf8) AS `businessprocess_id_friendlyname`,coalesce((`businessprocess_businessprocess_id_businessprocess`.`status` = 'inactive'),0) AS `businessprocess_id_obsolescence_flag`,cast(concat(coalesce(`applicationsolution_applicationsolution_id_functionalci`.`name`,'')) as char charset utf8) AS `applicationsolution_id_friendlyname`,coalesce((`applicationsolution_applicationsolution_id_applicationsolution`.`status` = 'inactive'),0) AS `applicationsolution_id_obsolescence_flag` from ((`lnkapplicationsolutiontobusinessprocess` `_lnkapplicationsolutiontobusinessprocess` join (`functionalci` `businessprocess_businessprocess_id_functionalci` join `businessprocess` `businessprocess_businessprocess_id_businessprocess` on((`businessprocess_businessprocess_id_functionalci`.`id` = `businessprocess_businessprocess_id_businessprocess`.`id`))) on((`_lnkapplicationsolutiontobusinessprocess`.`businessprocess_id` = `businessprocess_businessprocess_id_functionalci`.`id`))) join (`functionalci` `applicationsolution_applicationsolution_id_functionalci` join `applicationsolution` `applicationsolution_applicationsolution_id_applicationsolution` on((`applicationsolution_applicationsolution_id_functionalci`.`id` = `applicationsolution_applicationsolution_id_applicationsolution`.`id`))) on((`_lnkapplicationsolutiontobusinessprocess`.`applicationsolution_id` = `applicationsolution_applicationsolution_id_functionalci`.`id`))) where (coalesce((`businessprocess_businessprocess_id_functionalci`.`finalclass` = 'BusinessProcess'),1) and coalesce((`applicationsolution_applicationsolution_id_functionalci`.`finalclass` = 'ApplicationSolution'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_lnkapplicationsolutiontofunctionalci`
--

/*!50001 DROP VIEW IF EXISTS `view_lnkapplicationsolutiontofunctionalci`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_lnkapplicationsolutiontofunctionalci` AS select distinct `_lnkapplicationsolutiontofunctionalci`.`id` AS `id`,`_lnkapplicationsolutiontofunctionalci`.`applicationsolution_id` AS `applicationsolution_id`,`applicationsolution_applicationsolution_id_functionalci`.`name` AS `applicationsolution_name`,`_lnkapplicationsolutiontofunctionalci`.`functionalci_id` AS `functionalci_id`,`functionalci_functionalci_id_functionalci`.`name` AS `functionalci_name`,cast(concat(coalesce(`_lnkapplicationsolutiontofunctionalci`.`applicationsolution_id`,''),coalesce(' ',''),coalesce(`_lnkapplicationsolutiontofunctionalci`.`functionalci_id`,'')) as char charset utf8) AS `friendlyname`,cast(concat(coalesce(`applicationsolution_applicationsolution_id_functionalci`.`name`,'')) as char charset utf8) AS `applicationsolution_id_friendlyname`,coalesce((`applicationsolution_applicationsolution_id_applicationsolution`.`status` = 'inactive'),0) AS `applicationsolution_id_obsolescence_flag`,if((`functionalci_functionalci_id_functionalci`.`finalclass` in ('Middleware','DBServer','WebServer','PCSoftware','OtherSoftware')),cast(concat(coalesce(`functionalci_functionalci_id_functionalci`.`name`,''),coalesce(' ',''),coalesce(`functionalci_system_id_functionalci`.`name`,'')) as char charset utf8),cast(concat(coalesce(`functionalci_functionalci_id_functionalci`.`name`,'')) as char charset utf8)) AS `functionalci_id_friendlyname`,`functionalci_functionalci_id_functionalci`.`finalclass` AS `functionalci_id_finalclass_recall`,if((`functionalci_functionalci_id_functionalci`.`finalclass` = 'FunctionalCI'),coalesce(0,0),if((`functionalci_functionalci_id_functionalci`.`finalclass` in ('Hypervisor','Farm','VirtualMachine')),coalesce((`functionalci_functionalci_id_fn_virtualdevice_virtualdevice`.`status` = 'obsolete'),0),if((`functionalci_functionalci_id_functionalci`.`finalclass` = 'WebApplication'),coalesce(coalesce((`webserver_webserver_id_softwareinstance`.`status` = 'inactive'),0),0),if((`functionalci_functionalci_id_functionalci`.`finalclass` = 'DatabaseSchema'),coalesce(coalesce((`dbserver_dbserver_id_softwareinstance`.`status` = 'inactive'),0),0),if((`functionalci_functionalci_id_functionalci`.`finalclass` = 'MiddlewareInstance'),coalesce(coalesce((`middleware_middleware_id_softwareinstance`.`status` = 'inactive'),0),0),if((`functionalci_functionalci_id_functionalci`.`finalclass` in ('Middleware','DBServer','WebServer','PCSoftware','OtherSoftware')),coalesce((`functionalci_functionalci_id_fn_softwareinstance_softwareinstance`.`status` = 'inactive'),0),if((`functionalci_functionalci_id_functionalci`.`finalclass` = 'BusinessProcess'),coalesce((`functionalci_functionalci_id_fn_businessprocess_businessprocess`.`status` = 'inactive'),0),if((`functionalci_functionalci_id_functionalci`.`finalclass` = 'ApplicationSolution'),coalesce((`functionalci_functionalci_id_fn_applicationsolution_applicationsolution`.`status` = 'inactive'),0),coalesce((`functionalci_functionalci_id_fn_physicaldevice_physicaldevice`.`status` = 'obsolete'),0))))))))) AS `functionalci_id_obsolescence_flag` from ((`lnkapplicationsolutiontofunctionalci` `_lnkapplicationsolutiontofunctionalci` join (`functionalci` `applicationsolution_applicationsolution_id_functionalci` join `applicationsolution` `applicationsolution_applicationsolution_id_applicationsolution` on((`applicationsolution_applicationsolution_id_functionalci`.`id` = `applicationsolution_applicationsolution_id_applicationsolution`.`id`))) on((`_lnkapplicationsolutiontofunctionalci`.`applicationsolution_id` = `applicationsolution_applicationsolution_id_functionalci`.`id`))) join ((((((((`functionalci` `functionalci_functionalci_id_functionalci` left join (`softwareinstance` `functionalci_functionalci_id_fn_softwareinstance_softwareinstance` join `functionalci` `functionalci_system_id_functionalci` on((`functionalci_functionalci_id_fn_softwareinstance_softwareinstance`.`functionalci_id` = `functionalci_system_id_functionalci`.`id`))) on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_softwareinstance_softwareinstance`.`id`))) left join `virtualdevice` `functionalci_functionalci_id_fn_virtualdevice_virtualdevice` on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_virtualdevice_virtualdevice`.`id`))) left join (`webapplication` `functionalci_functionalci_id_fn_webapplication_webapplication` join (`functionalci` `webserver_webserver_id_functionalci` join `softwareinstance` `webserver_webserver_id_softwareinstance` on((`webserver_webserver_id_functionalci`.`id` = `webserver_webserver_id_softwareinstance`.`id`))) on((`functionalci_functionalci_id_fn_webapplication_webapplication`.`webserver_id` = `webserver_webserver_id_functionalci`.`id`))) on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_webapplication_webapplication`.`id`))) left join (`databaseschema` `functionalci_functionalci_id_fn_databaseschema_databaseschema` join (`functionalci` `dbserver_dbserver_id_functionalci` join `softwareinstance` `dbserver_dbserver_id_softwareinstance` on((`dbserver_dbserver_id_functionalci`.`id` = `dbserver_dbserver_id_softwareinstance`.`id`))) on((`functionalci_functionalci_id_fn_databaseschema_databaseschema`.`dbserver_id` = `dbserver_dbserver_id_functionalci`.`id`))) on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_databaseschema_databaseschema`.`id`))) left join (`middlewareinstance` `functionalci_functionalci_id_fn_middlewareinstance_middlewareinstance` join (`functionalci` `middleware_middleware_id_functionalci` join `softwareinstance` `middleware_middleware_id_softwareinstance` on((`middleware_middleware_id_functionalci`.`id` = `middleware_middleware_id_softwareinstance`.`id`))) on((`functionalci_functionalci_id_fn_middlewareinstance_middlewareinstance`.`middleware_id` = `middleware_middleware_id_functionalci`.`id`))) on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_middlewareinstance_middlewareinstance`.`id`))) left join `businessprocess` `functionalci_functionalci_id_fn_businessprocess_businessprocess` on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_businessprocess_businessprocess`.`id`))) left join `applicationsolution` `functionalci_functionalci_id_fn_applicationsolution_applicationsolution` on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_applicationsolution_applicationsolution`.`id`))) left join `physicaldevice` `functionalci_functionalci_id_fn_physicaldevice_physicaldevice` on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_physicaldevice_physicaldevice`.`id`))) on((`_lnkapplicationsolutiontofunctionalci`.`functionalci_id` = `functionalci_functionalci_id_functionalci`.`id`))) where (coalesce((`applicationsolution_applicationsolution_id_functionalci`.`finalclass` = 'ApplicationSolution'),1) and coalesce((`webserver_webserver_id_functionalci`.`finalclass` = 'WebServer'),1) and coalesce((`dbserver_dbserver_id_functionalci`.`finalclass` = 'DBServer'),1) and coalesce((`middleware_middleware_id_functionalci`.`finalclass` = 'Middleware'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_lnkconnectablecitonetworkdevice`
--

/*!50001 DROP VIEW IF EXISTS `view_lnkconnectablecitonetworkdevice`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_lnkconnectablecitonetworkdevice` AS select distinct `_lnkconnectablecitonetworkdevice`.`id` AS `id`,`_lnkconnectablecitonetworkdevice`.`networkdevice_id` AS `networkdevice_id`,`networkdevice_networkdevice_id_functionalci`.`name` AS `networkdevice_name`,`_lnkconnectablecitonetworkdevice`.`connectableci_id` AS `connectableci_id`,`connectableci_connectableci_id_functionalci`.`name` AS `connectableci_name`,`_lnkconnectablecitonetworkdevice`.`network_port` AS `network_port`,`_lnkconnectablecitonetworkdevice`.`device_port` AS `device_port`,`_lnkconnectablecitonetworkdevice`.`type` AS `connection_type`,cast(concat(coalesce(`_lnkconnectablecitonetworkdevice`.`networkdevice_id`,''),coalesce(' ',''),coalesce(`_lnkconnectablecitonetworkdevice`.`connectableci_id`,'')) as char charset utf8) AS `friendlyname`,cast(concat(coalesce(`networkdevice_networkdevice_id_functionalci`.`name`,'')) as char charset utf8) AS `networkdevice_id_friendlyname`,coalesce((`networkdevice_networkdevice_id_physicaldevice`.`status` = 'obsolete'),0) AS `networkdevice_id_obsolescence_flag`,cast(concat(coalesce(`connectableci_connectableci_id_functionalci`.`name`,'')) as char charset utf8) AS `connectableci_id_friendlyname`,`connectableci_connectableci_id_functionalci`.`finalclass` AS `connectableci_id_finalclass_recall`,coalesce((`connectableci_connectableci_id_physicaldevice`.`status` = 'obsolete'),0) AS `connectableci_id_obsolescence_flag` from ((`lnkconnectablecitonetworkdevice` `_lnkconnectablecitonetworkdevice` join (`functionalci` `networkdevice_networkdevice_id_functionalci` join `physicaldevice` `networkdevice_networkdevice_id_physicaldevice` on((`networkdevice_networkdevice_id_functionalci`.`id` = `networkdevice_networkdevice_id_physicaldevice`.`id`))) on((`_lnkconnectablecitonetworkdevice`.`networkdevice_id` = `networkdevice_networkdevice_id_functionalci`.`id`))) join (`functionalci` `connectableci_connectableci_id_functionalci` join `physicaldevice` `connectableci_connectableci_id_physicaldevice` on((`connectableci_connectableci_id_functionalci`.`id` = `connectableci_connectableci_id_physicaldevice`.`id`))) on((`_lnkconnectablecitonetworkdevice`.`connectableci_id` = `connectableci_connectableci_id_functionalci`.`id`))) where (coalesce((`networkdevice_networkdevice_id_functionalci`.`finalclass` = 'NetworkDevice'),1) and coalesce((`connectableci_connectableci_id_functionalci`.`finalclass` in ('DatacenterDevice','NetworkDevice','Server','PC','Printer','StorageSystem','SANSwitch','TapeLibrary','NAS','ConnectableCI')),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_lnkcontacttocontract`
--

/*!50001 DROP VIEW IF EXISTS `view_lnkcontacttocontract`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_lnkcontacttocontract` AS select distinct `_lnkcontacttocontract`.`id` AS `id`,`_lnkcontacttocontract`.`contract_id` AS `contract_id`,`contract_contract_id_contract`.`name` AS `contract_name`,`_lnkcontacttocontract`.`contact_id` AS `contact_id`,`contact_contact_id_contact`.`name` AS `contact_name`,cast(concat(coalesce(`_lnkcontacttocontract`.`contract_id`,''),coalesce(' ',''),coalesce(`_lnkcontacttocontract`.`contact_id`,'')) as char charset utf8) AS `friendlyname`,cast(concat(coalesce(`contract_contract_id_contract`.`name`,'')) as char charset utf8) AS `contract_id_friendlyname`,`contract_contract_id_contract`.`finalclass` AS `contract_id_finalclass_recall`,if((`contact_contact_id_contact`.`finalclass` in ('Team','Contact')),cast(concat(coalesce(`contact_contact_id_contact`.`name`,'')) as char charset utf8),cast(concat(coalesce(`contact_contact_id_fn_person_person`.`first_name`,''),coalesce(' ',''),coalesce(`contact_contact_id_contact`.`name`,'')) as char charset utf8)) AS `contact_id_friendlyname`,`contact_contact_id_contact`.`finalclass` AS `contact_id_finalclass_recall`,coalesce((`contact_contact_id_contact`.`status` = 'inactive'),0) AS `contact_id_obsolescence_flag` from ((`lnkcontacttocontract` `_lnkcontacttocontract` join `contract` `contract_contract_id_contract` on((`_lnkcontacttocontract`.`contract_id` = `contract_contract_id_contract`.`id`))) join (`contact` `contact_contact_id_contact` left join `person` `contact_contact_id_fn_person_person` on((`contact_contact_id_contact`.`id` = `contact_contact_id_fn_person_person`.`id`))) on((`_lnkcontacttocontract`.`contact_id` = `contact_contact_id_contact`.`id`))) where 1 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_lnkcontacttofunctionalci`
--

/*!50001 DROP VIEW IF EXISTS `view_lnkcontacttofunctionalci`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_lnkcontacttofunctionalci` AS select distinct `_lnkcontacttofunctionalci`.`id` AS `id`,`_lnkcontacttofunctionalci`.`functionalci_id` AS `functionalci_id`,`functionalci_functionalci_id_functionalci`.`name` AS `functionalci_name`,`_lnkcontacttofunctionalci`.`contact_id` AS `contact_id`,`contact_contact_id_contact`.`name` AS `contact_name`,cast(concat(coalesce(`_lnkcontacttofunctionalci`.`functionalci_id`,''),coalesce(' ',''),coalesce(`_lnkcontacttofunctionalci`.`contact_id`,'')) as char charset utf8) AS `friendlyname`,if((`functionalci_functionalci_id_functionalci`.`finalclass` in ('Middleware','DBServer','WebServer','PCSoftware','OtherSoftware')),cast(concat(coalesce(`functionalci_functionalci_id_functionalci`.`name`,''),coalesce(' ',''),coalesce(`functionalci_system_id_functionalci`.`name`,'')) as char charset utf8),cast(concat(coalesce(`functionalci_functionalci_id_functionalci`.`name`,'')) as char charset utf8)) AS `functionalci_id_friendlyname`,`functionalci_functionalci_id_functionalci`.`finalclass` AS `functionalci_id_finalclass_recall`,if((`functionalci_functionalci_id_functionalci`.`finalclass` = 'FunctionalCI'),coalesce(0,0),if((`functionalci_functionalci_id_functionalci`.`finalclass` in ('Hypervisor','Farm','VirtualMachine')),coalesce((`functionalci_functionalci_id_fn_virtualdevice_virtualdevice`.`status` = 'obsolete'),0),if((`functionalci_functionalci_id_functionalci`.`finalclass` = 'WebApplication'),coalesce(coalesce((`webserver_webserver_id_softwareinstance`.`status` = 'inactive'),0),0),if((`functionalci_functionalci_id_functionalci`.`finalclass` = 'DatabaseSchema'),coalesce(coalesce((`dbserver_dbserver_id_softwareinstance`.`status` = 'inactive'),0),0),if((`functionalci_functionalci_id_functionalci`.`finalclass` = 'MiddlewareInstance'),coalesce(coalesce((`middleware_middleware_id_softwareinstance`.`status` = 'inactive'),0),0),if((`functionalci_functionalci_id_functionalci`.`finalclass` in ('Middleware','DBServer','WebServer','PCSoftware','OtherSoftware')),coalesce((`functionalci_functionalci_id_fn_softwareinstance_softwareinstance`.`status` = 'inactive'),0),if((`functionalci_functionalci_id_functionalci`.`finalclass` = 'BusinessProcess'),coalesce((`functionalci_functionalci_id_fn_businessprocess_businessprocess`.`status` = 'inactive'),0),if((`functionalci_functionalci_id_functionalci`.`finalclass` = 'ApplicationSolution'),coalesce((`functionalci_functionalci_id_fn_applicationsolution_applicationsolution`.`status` = 'inactive'),0),coalesce((`functionalci_functionalci_id_fn_physicaldevice_physicaldevice`.`status` = 'obsolete'),0))))))))) AS `functionalci_id_obsolescence_flag`,if((`contact_contact_id_contact`.`finalclass` in ('Team','Contact')),cast(concat(coalesce(`contact_contact_id_contact`.`name`,'')) as char charset utf8),cast(concat(coalesce(`contact_contact_id_fn_person_person`.`first_name`,''),coalesce(' ',''),coalesce(`contact_contact_id_contact`.`name`,'')) as char charset utf8)) AS `contact_id_friendlyname`,`contact_contact_id_contact`.`finalclass` AS `contact_id_finalclass_recall`,coalesce((`contact_contact_id_contact`.`status` = 'inactive'),0) AS `contact_id_obsolescence_flag` from ((`lnkcontacttofunctionalci` `_lnkcontacttofunctionalci` join ((((((((`functionalci` `functionalci_functionalci_id_functionalci` left join (`softwareinstance` `functionalci_functionalci_id_fn_softwareinstance_softwareinstance` join `functionalci` `functionalci_system_id_functionalci` on((`functionalci_functionalci_id_fn_softwareinstance_softwareinstance`.`functionalci_id` = `functionalci_system_id_functionalci`.`id`))) on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_softwareinstance_softwareinstance`.`id`))) left join `virtualdevice` `functionalci_functionalci_id_fn_virtualdevice_virtualdevice` on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_virtualdevice_virtualdevice`.`id`))) left join (`webapplication` `functionalci_functionalci_id_fn_webapplication_webapplication` join (`functionalci` `webserver_webserver_id_functionalci` join `softwareinstance` `webserver_webserver_id_softwareinstance` on((`webserver_webserver_id_functionalci`.`id` = `webserver_webserver_id_softwareinstance`.`id`))) on((`functionalci_functionalci_id_fn_webapplication_webapplication`.`webserver_id` = `webserver_webserver_id_functionalci`.`id`))) on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_webapplication_webapplication`.`id`))) left join (`databaseschema` `functionalci_functionalci_id_fn_databaseschema_databaseschema` join (`functionalci` `dbserver_dbserver_id_functionalci` join `softwareinstance` `dbserver_dbserver_id_softwareinstance` on((`dbserver_dbserver_id_functionalci`.`id` = `dbserver_dbserver_id_softwareinstance`.`id`))) on((`functionalci_functionalci_id_fn_databaseschema_databaseschema`.`dbserver_id` = `dbserver_dbserver_id_functionalci`.`id`))) on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_databaseschema_databaseschema`.`id`))) left join (`middlewareinstance` `functionalci_functionalci_id_fn_middlewareinstance_middlewareinstance` join (`functionalci` `middleware_middleware_id_functionalci` join `softwareinstance` `middleware_middleware_id_softwareinstance` on((`middleware_middleware_id_functionalci`.`id` = `middleware_middleware_id_softwareinstance`.`id`))) on((`functionalci_functionalci_id_fn_middlewareinstance_middlewareinstance`.`middleware_id` = `middleware_middleware_id_functionalci`.`id`))) on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_middlewareinstance_middlewareinstance`.`id`))) left join `businessprocess` `functionalci_functionalci_id_fn_businessprocess_businessprocess` on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_businessprocess_businessprocess`.`id`))) left join `applicationsolution` `functionalci_functionalci_id_fn_applicationsolution_applicationsolution` on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_applicationsolution_applicationsolution`.`id`))) left join `physicaldevice` `functionalci_functionalci_id_fn_physicaldevice_physicaldevice` on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_physicaldevice_physicaldevice`.`id`))) on((`_lnkcontacttofunctionalci`.`functionalci_id` = `functionalci_functionalci_id_functionalci`.`id`))) join (`contact` `contact_contact_id_contact` left join `person` `contact_contact_id_fn_person_person` on((`contact_contact_id_contact`.`id` = `contact_contact_id_fn_person_person`.`id`))) on((`_lnkcontacttofunctionalci`.`contact_id` = `contact_contact_id_contact`.`id`))) where (coalesce((`webserver_webserver_id_functionalci`.`finalclass` = 'WebServer'),1) and coalesce((`dbserver_dbserver_id_functionalci`.`finalclass` = 'DBServer'),1) and coalesce((`middleware_middleware_id_functionalci`.`finalclass` = 'Middleware'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_lnkcontacttoservice`
--

/*!50001 DROP VIEW IF EXISTS `view_lnkcontacttoservice`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_lnkcontacttoservice` AS select distinct `_lnkcontacttoservice`.`id` AS `id`,`_lnkcontacttoservice`.`service_id` AS `service_id`,`service_service_id_service`.`name` AS `service_name`,`_lnkcontacttoservice`.`contact_id` AS `contact_id`,`contact_contact_id_contact`.`name` AS `contact_name`,cast(concat(coalesce(`_lnkcontacttoservice`.`service_id`,''),coalesce(' ',''),coalesce(`_lnkcontacttoservice`.`contact_id`,'')) as char charset utf8) AS `friendlyname`,cast(concat(coalesce(`service_service_id_service`.`name`,'')) as char charset utf8) AS `service_id_friendlyname`,if((`contact_contact_id_contact`.`finalclass` in ('Team','Contact')),cast(concat(coalesce(`contact_contact_id_contact`.`name`,'')) as char charset utf8),cast(concat(coalesce(`contact_contact_id_fn_person_person`.`first_name`,''),coalesce(' ',''),coalesce(`contact_contact_id_contact`.`name`,'')) as char charset utf8)) AS `contact_id_friendlyname`,`contact_contact_id_contact`.`finalclass` AS `contact_id_finalclass_recall`,coalesce((`contact_contact_id_contact`.`status` = 'inactive'),0) AS `contact_id_obsolescence_flag` from ((`lnkcontacttoservice` `_lnkcontacttoservice` join `service` `service_service_id_service` on((`_lnkcontacttoservice`.`service_id` = `service_service_id_service`.`id`))) join (`contact` `contact_contact_id_contact` left join `person` `contact_contact_id_fn_person_person` on((`contact_contact_id_contact`.`id` = `contact_contact_id_fn_person_person`.`id`))) on((`_lnkcontacttoservice`.`contact_id` = `contact_contact_id_contact`.`id`))) where 1 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_lnkcontacttoticket`
--

/*!50001 DROP VIEW IF EXISTS `view_lnkcontacttoticket`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_lnkcontacttoticket` AS select distinct `_lnkcontacttoticket`.`id` AS `id`,`_lnkcontacttoticket`.`ticket_id` AS `ticket_id`,`ticket_ticket_id_ticket`.`ref` AS `ticket_ref`,`_lnkcontacttoticket`.`contact_id` AS `contact_id`,`contact_contact_id_contact`.`email` AS `contact_email`,`_lnkcontacttoticket`.`role` AS `role`,`_lnkcontacttoticket`.`impact_code` AS `role_code`,cast(concat(coalesce(`_lnkcontacttoticket`.`ticket_id`,''),coalesce(' ',''),coalesce(`_lnkcontacttoticket`.`contact_id`,'')) as char charset utf8) AS `friendlyname`,cast(concat(coalesce(`ticket_ticket_id_ticket`.`ref`,'')) as char charset utf8) AS `ticket_id_friendlyname`,`ticket_ticket_id_ticket`.`finalclass` AS `ticket_id_finalclass_recall`,coalesce(((`ticket_ticket_id_ticket`.`operational_status` = 'closed') and ((`ticket_ticket_id_ticket`.`close_date` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00')) or (isnull(`ticket_ticket_id_ticket`.`close_date`) and (`ticket_ticket_id_ticket`.`last_update` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00'))))),0) AS `ticket_id_obsolescence_flag`,if((`contact_contact_id_contact`.`finalclass` in ('Team','Contact')),cast(concat(coalesce(`contact_contact_id_contact`.`name`,'')) as char charset utf8),cast(concat(coalesce(`contact_contact_id_fn_person_person`.`first_name`,''),coalesce(' ',''),coalesce(`contact_contact_id_contact`.`name`,'')) as char charset utf8)) AS `contact_id_friendlyname`,`contact_contact_id_contact`.`finalclass` AS `contact_id_finalclass_recall`,coalesce((`contact_contact_id_contact`.`status` = 'inactive'),0) AS `contact_id_obsolescence_flag` from ((`lnkcontacttoticket` `_lnkcontacttoticket` join `ticket` `ticket_ticket_id_ticket` on((`_lnkcontacttoticket`.`ticket_id` = `ticket_ticket_id_ticket`.`id`))) join (`contact` `contact_contact_id_contact` left join `person` `contact_contact_id_fn_person_person` on((`contact_contact_id_contact`.`id` = `contact_contact_id_fn_person_person`.`id`))) on((`_lnkcontacttoticket`.`contact_id` = `contact_contact_id_contact`.`id`))) where 1 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_lnkcontracttodocument`
--

/*!50001 DROP VIEW IF EXISTS `view_lnkcontracttodocument`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_lnkcontracttodocument` AS select distinct `_lnkcontracttodocument`.`id` AS `id`,`_lnkcontracttodocument`.`contract_id` AS `contract_id`,`contract_contract_id_contract`.`name` AS `contract_name`,`_lnkcontracttodocument`.`document_id` AS `document_id`,`document_document_id_document`.`name` AS `document_name`,cast(concat(coalesce(`_lnkcontracttodocument`.`contract_id`,''),coalesce(' ',''),coalesce(`_lnkcontracttodocument`.`document_id`,'')) as char charset utf8) AS `friendlyname`,cast(concat(coalesce(`contract_contract_id_contract`.`name`,'')) as char charset utf8) AS `contract_id_friendlyname`,`contract_contract_id_contract`.`finalclass` AS `contract_id_finalclass_recall`,if((`document_document_id_document`.`finalclass` = 'Document'),cast(concat(coalesce('Document','')) as char charset utf8),cast(concat(coalesce(`document_document_id_document`.`name`,'')) as char charset utf8)) AS `document_id_friendlyname`,`document_document_id_document`.`finalclass` AS `document_id_finalclass_recall`,coalesce((`document_document_id_document`.`status` = 'obsolete'),0) AS `document_id_obsolescence_flag` from ((`lnkcontracttodocument` `_lnkcontracttodocument` join `contract` `contract_contract_id_contract` on((`_lnkcontracttodocument`.`contract_id` = `contract_contract_id_contract`.`id`))) join `document` `document_document_id_document` on((`_lnkcontracttodocument`.`document_id` = `document_document_id_document`.`id`))) where 1 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_lnkcustomercontracttofunctionalci`
--

/*!50001 DROP VIEW IF EXISTS `view_lnkcustomercontracttofunctionalci`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_lnkcustomercontracttofunctionalci` AS select distinct `_lnkcustomercontracttofunctionalci`.`id` AS `id`,`_lnkcustomercontracttofunctionalci`.`customercontract_id` AS `customercontract_id`,`customercontract_customercontract_id_contract`.`name` AS `customercontract_name`,`_lnkcustomercontracttofunctionalci`.`functionalci_id` AS `functionalci_id`,`functionalci_functionalci_id_functionalci`.`name` AS `functionalci_name`,cast(concat(coalesce(`_lnkcustomercontracttofunctionalci`.`customercontract_id`,''),coalesce(' ',''),coalesce(`_lnkcustomercontracttofunctionalci`.`functionalci_id`,'')) as char charset utf8) AS `friendlyname`,cast(concat(coalesce(`customercontract_customercontract_id_contract`.`name`,'')) as char charset utf8) AS `customercontract_id_friendlyname`,if((`functionalci_functionalci_id_functionalci`.`finalclass` in ('Middleware','DBServer','WebServer','PCSoftware','OtherSoftware')),cast(concat(coalesce(`functionalci_functionalci_id_functionalci`.`name`,''),coalesce(' ',''),coalesce(`functionalci_system_id_functionalci`.`name`,'')) as char charset utf8),cast(concat(coalesce(`functionalci_functionalci_id_functionalci`.`name`,'')) as char charset utf8)) AS `functionalci_id_friendlyname`,`functionalci_functionalci_id_functionalci`.`finalclass` AS `functionalci_id_finalclass_recall`,if((`functionalci_functionalci_id_functionalci`.`finalclass` = 'FunctionalCI'),coalesce(0,0),if((`functionalci_functionalci_id_functionalci`.`finalclass` in ('Hypervisor','Farm','VirtualMachine')),coalesce((`functionalci_functionalci_id_fn_virtualdevice_virtualdevice`.`status` = 'obsolete'),0),if((`functionalci_functionalci_id_functionalci`.`finalclass` = 'WebApplication'),coalesce(coalesce((`webserver_webserver_id_softwareinstance`.`status` = 'inactive'),0),0),if((`functionalci_functionalci_id_functionalci`.`finalclass` = 'DatabaseSchema'),coalesce(coalesce((`dbserver_dbserver_id_softwareinstance`.`status` = 'inactive'),0),0),if((`functionalci_functionalci_id_functionalci`.`finalclass` = 'MiddlewareInstance'),coalesce(coalesce((`middleware_middleware_id_softwareinstance`.`status` = 'inactive'),0),0),if((`functionalci_functionalci_id_functionalci`.`finalclass` in ('Middleware','DBServer','WebServer','PCSoftware','OtherSoftware')),coalesce((`functionalci_functionalci_id_fn_softwareinstance_softwareinstance`.`status` = 'inactive'),0),if((`functionalci_functionalci_id_functionalci`.`finalclass` = 'BusinessProcess'),coalesce((`functionalci_functionalci_id_fn_businessprocess_businessprocess`.`status` = 'inactive'),0),if((`functionalci_functionalci_id_functionalci`.`finalclass` = 'ApplicationSolution'),coalesce((`functionalci_functionalci_id_fn_applicationsolution_applicationsolution`.`status` = 'inactive'),0),coalesce((`functionalci_functionalci_id_fn_physicaldevice_physicaldevice`.`status` = 'obsolete'),0))))))))) AS `functionalci_id_obsolescence_flag` from ((`lnkcustomercontracttofunctionalci` `_lnkcustomercontracttofunctionalci` join `contract` `customercontract_customercontract_id_contract` on((`_lnkcustomercontracttofunctionalci`.`customercontract_id` = `customercontract_customercontract_id_contract`.`id`))) join ((((((((`functionalci` `functionalci_functionalci_id_functionalci` left join (`softwareinstance` `functionalci_functionalci_id_fn_softwareinstance_softwareinstance` join `functionalci` `functionalci_system_id_functionalci` on((`functionalci_functionalci_id_fn_softwareinstance_softwareinstance`.`functionalci_id` = `functionalci_system_id_functionalci`.`id`))) on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_softwareinstance_softwareinstance`.`id`))) left join `virtualdevice` `functionalci_functionalci_id_fn_virtualdevice_virtualdevice` on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_virtualdevice_virtualdevice`.`id`))) left join (`webapplication` `functionalci_functionalci_id_fn_webapplication_webapplication` join (`functionalci` `webserver_webserver_id_functionalci` join `softwareinstance` `webserver_webserver_id_softwareinstance` on((`webserver_webserver_id_functionalci`.`id` = `webserver_webserver_id_softwareinstance`.`id`))) on((`functionalci_functionalci_id_fn_webapplication_webapplication`.`webserver_id` = `webserver_webserver_id_functionalci`.`id`))) on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_webapplication_webapplication`.`id`))) left join (`databaseschema` `functionalci_functionalci_id_fn_databaseschema_databaseschema` join (`functionalci` `dbserver_dbserver_id_functionalci` join `softwareinstance` `dbserver_dbserver_id_softwareinstance` on((`dbserver_dbserver_id_functionalci`.`id` = `dbserver_dbserver_id_softwareinstance`.`id`))) on((`functionalci_functionalci_id_fn_databaseschema_databaseschema`.`dbserver_id` = `dbserver_dbserver_id_functionalci`.`id`))) on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_databaseschema_databaseschema`.`id`))) left join (`middlewareinstance` `functionalci_functionalci_id_fn_middlewareinstance_middlewareinstance` join (`functionalci` `middleware_middleware_id_functionalci` join `softwareinstance` `middleware_middleware_id_softwareinstance` on((`middleware_middleware_id_functionalci`.`id` = `middleware_middleware_id_softwareinstance`.`id`))) on((`functionalci_functionalci_id_fn_middlewareinstance_middlewareinstance`.`middleware_id` = `middleware_middleware_id_functionalci`.`id`))) on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_middlewareinstance_middlewareinstance`.`id`))) left join `businessprocess` `functionalci_functionalci_id_fn_businessprocess_businessprocess` on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_businessprocess_businessprocess`.`id`))) left join `applicationsolution` `functionalci_functionalci_id_fn_applicationsolution_applicationsolution` on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_applicationsolution_applicationsolution`.`id`))) left join `physicaldevice` `functionalci_functionalci_id_fn_physicaldevice_physicaldevice` on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_physicaldevice_physicaldevice`.`id`))) on((`_lnkcustomercontracttofunctionalci`.`functionalci_id` = `functionalci_functionalci_id_functionalci`.`id`))) where (coalesce((`customercontract_customercontract_id_contract`.`finalclass` = 'CustomerContract'),1) and coalesce((`webserver_webserver_id_functionalci`.`finalclass` = 'WebServer'),1) and coalesce((`dbserver_dbserver_id_functionalci`.`finalclass` = 'DBServer'),1) and coalesce((`middleware_middleware_id_functionalci`.`finalclass` = 'Middleware'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_lnkcustomercontracttoprovidercontract`
--

/*!50001 DROP VIEW IF EXISTS `view_lnkcustomercontracttoprovidercontract`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_lnkcustomercontracttoprovidercontract` AS select distinct `_lnkcustomercontracttoprovidercontract`.`id` AS `id`,`_lnkcustomercontracttoprovidercontract`.`customercontract_id` AS `customercontract_id`,`customercontract_customercontract_id_contract`.`name` AS `customercontract_name`,`_lnkcustomercontracttoprovidercontract`.`providercontract_id` AS `providercontract_id`,`providercontract_providercontract_id_contract`.`name` AS `providercontract_name`,cast(concat(coalesce(`_lnkcustomercontracttoprovidercontract`.`customercontract_id`,''),coalesce(' ',''),coalesce(`_lnkcustomercontracttoprovidercontract`.`providercontract_id`,'')) as char charset utf8) AS `friendlyname`,cast(concat(coalesce(`customercontract_customercontract_id_contract`.`name`,'')) as char charset utf8) AS `customercontract_id_friendlyname`,cast(concat(coalesce(`providercontract_providercontract_id_contract`.`name`,'')) as char charset utf8) AS `providercontract_id_friendlyname` from ((`lnkcustomercontracttoprovidercontract` `_lnkcustomercontracttoprovidercontract` join `contract` `customercontract_customercontract_id_contract` on((`_lnkcustomercontracttoprovidercontract`.`customercontract_id` = `customercontract_customercontract_id_contract`.`id`))) join `contract` `providercontract_providercontract_id_contract` on((`_lnkcustomercontracttoprovidercontract`.`providercontract_id` = `providercontract_providercontract_id_contract`.`id`))) where (coalesce((`customercontract_customercontract_id_contract`.`finalclass` = 'CustomerContract'),1) and coalesce((`providercontract_providercontract_id_contract`.`finalclass` = 'ProviderContract'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_lnkcustomercontracttoservice`
--

/*!50001 DROP VIEW IF EXISTS `view_lnkcustomercontracttoservice`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_lnkcustomercontracttoservice` AS select distinct `_lnkcustomercontracttoservice`.`id` AS `id`,`_lnkcustomercontracttoservice`.`customercontract_id` AS `customercontract_id`,`customercontract_customercontract_id_contract`.`name` AS `customercontract_name`,`_lnkcustomercontracttoservice`.`service_id` AS `service_id`,`service_service_id_service`.`name` AS `service_name`,`_lnkcustomercontracttoservice`.`sla_id` AS `sla_id`,`sla_sla_id_sla`.`name` AS `sla_name`,cast(concat(coalesce(`_lnkcustomercontracttoservice`.`customercontract_id`,''),coalesce(' ',''),coalesce(`_lnkcustomercontracttoservice`.`service_id`,'')) as char charset utf8) AS `friendlyname`,cast(concat(coalesce(`customercontract_customercontract_id_contract`.`name`,'')) as char charset utf8) AS `customercontract_id_friendlyname`,cast(concat(coalesce(`service_service_id_service`.`name`,'')) as char charset utf8) AS `service_id_friendlyname`,cast(concat(coalesce(`sla_sla_id_sla`.`name`,'')) as char charset utf8) AS `sla_id_friendlyname` from (((`lnkcustomercontracttoservice` `_lnkcustomercontracttoservice` join `contract` `customercontract_customercontract_id_contract` on((`_lnkcustomercontracttoservice`.`customercontract_id` = `customercontract_customercontract_id_contract`.`id`))) join `service` `service_service_id_service` on((`_lnkcustomercontracttoservice`.`service_id` = `service_service_id_service`.`id`))) left join `sla` `sla_sla_id_sla` on((`_lnkcustomercontracttoservice`.`sla_id` = `sla_sla_id_sla`.`id`))) where coalesce((`customercontract_customercontract_id_contract`.`finalclass` = 'CustomerContract'),1) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_lnkdeliverymodeltocontact`
--

/*!50001 DROP VIEW IF EXISTS `view_lnkdeliverymodeltocontact`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_lnkdeliverymodeltocontact` AS select distinct `_lnkdeliverymodeltocontact`.`id` AS `id`,`_lnkdeliverymodeltocontact`.`deliverymodel_id` AS `deliverymodel_id`,`deliverymodel_deliverymodel_id_deliverymodel`.`name` AS `deliverymodel_name`,`_lnkdeliverymodeltocontact`.`contact_id` AS `contact_id`,`contact_contact_id_contact`.`name` AS `contact_name`,`_lnkdeliverymodeltocontact`.`role_id` AS `role_id`,`contacttype_role_id_typology`.`name` AS `role_name`,cast(concat(coalesce(`_lnkdeliverymodeltocontact`.`deliverymodel_id`,''),coalesce(' ',''),coalesce(`_lnkdeliverymodeltocontact`.`contact_id`,'')) as char charset utf8) AS `friendlyname`,cast(concat(coalesce(`deliverymodel_deliverymodel_id_deliverymodel`.`name`,'')) as char charset utf8) AS `deliverymodel_id_friendlyname`,if((`contact_contact_id_contact`.`finalclass` in ('Team','Contact')),cast(concat(coalesce(`contact_contact_id_contact`.`name`,'')) as char charset utf8),cast(concat(coalesce(`contact_contact_id_fn_person_person`.`first_name`,''),coalesce(' ',''),coalesce(`contact_contact_id_contact`.`name`,'')) as char charset utf8)) AS `contact_id_friendlyname`,`contact_contact_id_contact`.`finalclass` AS `contact_id_finalclass_recall`,coalesce((`contact_contact_id_contact`.`status` = 'inactive'),0) AS `contact_id_obsolescence_flag`,cast(concat(coalesce(`contacttype_role_id_typology`.`name`,'')) as char charset utf8) AS `role_id_friendlyname` from (((`lnkdeliverymodeltocontact` `_lnkdeliverymodeltocontact` join `deliverymodel` `deliverymodel_deliverymodel_id_deliverymodel` on((`_lnkdeliverymodeltocontact`.`deliverymodel_id` = `deliverymodel_deliverymodel_id_deliverymodel`.`id`))) join (`contact` `contact_contact_id_contact` left join `person` `contact_contact_id_fn_person_person` on((`contact_contact_id_contact`.`id` = `contact_contact_id_fn_person_person`.`id`))) on((`_lnkdeliverymodeltocontact`.`contact_id` = `contact_contact_id_contact`.`id`))) left join `typology` `contacttype_role_id_typology` on((`_lnkdeliverymodeltocontact`.`role_id` = `contacttype_role_id_typology`.`id`))) where coalesce((`contacttype_role_id_typology`.`finalclass` = 'ContactType'),1) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_lnkdocumenttoerror`
--

/*!50001 DROP VIEW IF EXISTS `view_lnkdocumenttoerror`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_lnkdocumenttoerror` AS select distinct `_lnkdocumenttoerror`.`link_id` AS `id`,`_lnkdocumenttoerror`.`document_id` AS `document_id`,`document_document_id_document`.`name` AS `document_name`,`_lnkdocumenttoerror`.`error_id` AS `error_id`,`knownerror_error_id_knownerror`.`name` AS `error_name`,`_lnkdocumenttoerror`.`link_type` AS `link_type`,cast(concat(coalesce(`_lnkdocumenttoerror`.`link_type`,'')) as char charset utf8) AS `friendlyname`,if((`document_document_id_document`.`finalclass` = 'Document'),cast(concat(coalesce('Document','')) as char charset utf8),cast(concat(coalesce(`document_document_id_document`.`name`,'')) as char charset utf8)) AS `document_id_friendlyname`,`document_document_id_document`.`finalclass` AS `document_id_finalclass_recall`,coalesce((`document_document_id_document`.`status` = 'obsolete'),0) AS `document_id_obsolescence_flag`,cast(concat(coalesce(`knownerror_error_id_knownerror`.`name`,'')) as char charset utf8) AS `error_id_friendlyname` from ((`lnkdocumenttoerror` `_lnkdocumenttoerror` join `document` `document_document_id_document` on((`_lnkdocumenttoerror`.`document_id` = `document_document_id_document`.`id`))) join `knownerror` `knownerror_error_id_knownerror` on((`_lnkdocumenttoerror`.`error_id` = `knownerror_error_id_knownerror`.`id`))) where 1 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_lnkdocumenttofunctionalci`
--

/*!50001 DROP VIEW IF EXISTS `view_lnkdocumenttofunctionalci`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_lnkdocumenttofunctionalci` AS select distinct `_lnkdocumenttofunctionalci`.`id` AS `id`,`_lnkdocumenttofunctionalci`.`functionalci_id` AS `functionalci_id`,`functionalci_functionalci_id_functionalci`.`name` AS `functionalci_name`,`_lnkdocumenttofunctionalci`.`document_id` AS `document_id`,`document_document_id_document`.`name` AS `document_name`,cast(concat(coalesce(`_lnkdocumenttofunctionalci`.`functionalci_id`,''),coalesce(' ',''),coalesce(`_lnkdocumenttofunctionalci`.`document_id`,'')) as char charset utf8) AS `friendlyname`,if((`functionalci_functionalci_id_functionalci`.`finalclass` in ('Middleware','DBServer','WebServer','PCSoftware','OtherSoftware')),cast(concat(coalesce(`functionalci_functionalci_id_functionalci`.`name`,''),coalesce(' ',''),coalesce(`functionalci_system_id_functionalci`.`name`,'')) as char charset utf8),cast(concat(coalesce(`functionalci_functionalci_id_functionalci`.`name`,'')) as char charset utf8)) AS `functionalci_id_friendlyname`,`functionalci_functionalci_id_functionalci`.`finalclass` AS `functionalci_id_finalclass_recall`,if((`functionalci_functionalci_id_functionalci`.`finalclass` = 'FunctionalCI'),coalesce(0,0),if((`functionalci_functionalci_id_functionalci`.`finalclass` in ('Hypervisor','Farm','VirtualMachine')),coalesce((`functionalci_functionalci_id_fn_virtualdevice_virtualdevice`.`status` = 'obsolete'),0),if((`functionalci_functionalci_id_functionalci`.`finalclass` = 'WebApplication'),coalesce(coalesce((`webserver_webserver_id_softwareinstance`.`status` = 'inactive'),0),0),if((`functionalci_functionalci_id_functionalci`.`finalclass` = 'DatabaseSchema'),coalesce(coalesce((`dbserver_dbserver_id_softwareinstance`.`status` = 'inactive'),0),0),if((`functionalci_functionalci_id_functionalci`.`finalclass` = 'MiddlewareInstance'),coalesce(coalesce((`middleware_middleware_id_softwareinstance`.`status` = 'inactive'),0),0),if((`functionalci_functionalci_id_functionalci`.`finalclass` in ('Middleware','DBServer','WebServer','PCSoftware','OtherSoftware')),coalesce((`functionalci_functionalci_id_fn_softwareinstance_softwareinstance`.`status` = 'inactive'),0),if((`functionalci_functionalci_id_functionalci`.`finalclass` = 'BusinessProcess'),coalesce((`functionalci_functionalci_id_fn_businessprocess_businessprocess`.`status` = 'inactive'),0),if((`functionalci_functionalci_id_functionalci`.`finalclass` = 'ApplicationSolution'),coalesce((`functionalci_functionalci_id_fn_applicationsolution_applicationsolution`.`status` = 'inactive'),0),coalesce((`functionalci_functionalci_id_fn_physicaldevice_physicaldevice`.`status` = 'obsolete'),0))))))))) AS `functionalci_id_obsolescence_flag`,if((`document_document_id_document`.`finalclass` = 'Document'),cast(concat(coalesce('Document','')) as char charset utf8),cast(concat(coalesce(`document_document_id_document`.`name`,'')) as char charset utf8)) AS `document_id_friendlyname`,`document_document_id_document`.`finalclass` AS `document_id_finalclass_recall`,coalesce((`document_document_id_document`.`status` = 'obsolete'),0) AS `document_id_obsolescence_flag` from ((`lnkdocumenttofunctionalci` `_lnkdocumenttofunctionalci` join ((((((((`functionalci` `functionalci_functionalci_id_functionalci` left join (`softwareinstance` `functionalci_functionalci_id_fn_softwareinstance_softwareinstance` join `functionalci` `functionalci_system_id_functionalci` on((`functionalci_functionalci_id_fn_softwareinstance_softwareinstance`.`functionalci_id` = `functionalci_system_id_functionalci`.`id`))) on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_softwareinstance_softwareinstance`.`id`))) left join `virtualdevice` `functionalci_functionalci_id_fn_virtualdevice_virtualdevice` on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_virtualdevice_virtualdevice`.`id`))) left join (`webapplication` `functionalci_functionalci_id_fn_webapplication_webapplication` join (`functionalci` `webserver_webserver_id_functionalci` join `softwareinstance` `webserver_webserver_id_softwareinstance` on((`webserver_webserver_id_functionalci`.`id` = `webserver_webserver_id_softwareinstance`.`id`))) on((`functionalci_functionalci_id_fn_webapplication_webapplication`.`webserver_id` = `webserver_webserver_id_functionalci`.`id`))) on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_webapplication_webapplication`.`id`))) left join (`databaseschema` `functionalci_functionalci_id_fn_databaseschema_databaseschema` join (`functionalci` `dbserver_dbserver_id_functionalci` join `softwareinstance` `dbserver_dbserver_id_softwareinstance` on((`dbserver_dbserver_id_functionalci`.`id` = `dbserver_dbserver_id_softwareinstance`.`id`))) on((`functionalci_functionalci_id_fn_databaseschema_databaseschema`.`dbserver_id` = `dbserver_dbserver_id_functionalci`.`id`))) on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_databaseschema_databaseschema`.`id`))) left join (`middlewareinstance` `functionalci_functionalci_id_fn_middlewareinstance_middlewareinstance` join (`functionalci` `middleware_middleware_id_functionalci` join `softwareinstance` `middleware_middleware_id_softwareinstance` on((`middleware_middleware_id_functionalci`.`id` = `middleware_middleware_id_softwareinstance`.`id`))) on((`functionalci_functionalci_id_fn_middlewareinstance_middlewareinstance`.`middleware_id` = `middleware_middleware_id_functionalci`.`id`))) on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_middlewareinstance_middlewareinstance`.`id`))) left join `businessprocess` `functionalci_functionalci_id_fn_businessprocess_businessprocess` on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_businessprocess_businessprocess`.`id`))) left join `applicationsolution` `functionalci_functionalci_id_fn_applicationsolution_applicationsolution` on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_applicationsolution_applicationsolution`.`id`))) left join `physicaldevice` `functionalci_functionalci_id_fn_physicaldevice_physicaldevice` on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_physicaldevice_physicaldevice`.`id`))) on((`_lnkdocumenttofunctionalci`.`functionalci_id` = `functionalci_functionalci_id_functionalci`.`id`))) join `document` `document_document_id_document` on((`_lnkdocumenttofunctionalci`.`document_id` = `document_document_id_document`.`id`))) where (coalesce((`webserver_webserver_id_functionalci`.`finalclass` = 'WebServer'),1) and coalesce((`dbserver_dbserver_id_functionalci`.`finalclass` = 'DBServer'),1) and coalesce((`middleware_middleware_id_functionalci`.`finalclass` = 'Middleware'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_lnkdocumenttolicence`
--

/*!50001 DROP VIEW IF EXISTS `view_lnkdocumenttolicence`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_lnkdocumenttolicence` AS select distinct `_lnkdocumenttolicence`.`id` AS `id`,`_lnkdocumenttolicence`.`licence_id` AS `licence_id`,`licence_licence_id_licence`.`name` AS `licence_name`,`_lnkdocumenttolicence`.`document_id` AS `document_id`,`document_document_id_document`.`name` AS `document_name`,cast(concat(coalesce(`_lnkdocumenttolicence`.`licence_id`,''),coalesce(' ',''),coalesce(`_lnkdocumenttolicence`.`document_id`,'')) as char charset utf8) AS `friendlyname`,cast(concat(coalesce(`licence_licence_id_licence`.`name`,'')) as char charset utf8) AS `licence_id_friendlyname`,`licence_licence_id_licence`.`finalclass` AS `licence_id_finalclass_recall`,coalesce(((`licence_licence_id_licence`.`perpetual` = 'no') and (isnull(`licence_licence_id_licence`.`end_date`) = 0) and (`licence_licence_id_licence`.`end_date` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00'))),0) AS `licence_id_obsolescence_flag`,if((`document_document_id_document`.`finalclass` = 'Document'),cast(concat(coalesce('Document','')) as char charset utf8),cast(concat(coalesce(`document_document_id_document`.`name`,'')) as char charset utf8)) AS `document_id_friendlyname`,`document_document_id_document`.`finalclass` AS `document_id_finalclass_recall`,coalesce((`document_document_id_document`.`status` = 'obsolete'),0) AS `document_id_obsolescence_flag` from ((`lnkdocumenttolicence` `_lnkdocumenttolicence` join `licence` `licence_licence_id_licence` on((`_lnkdocumenttolicence`.`licence_id` = `licence_licence_id_licence`.`id`))) join `document` `document_document_id_document` on((`_lnkdocumenttolicence`.`document_id` = `document_document_id_document`.`id`))) where 1 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_lnkdocumenttopatch`
--

/*!50001 DROP VIEW IF EXISTS `view_lnkdocumenttopatch`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_lnkdocumenttopatch` AS select distinct `_lnkdocumenttopatch`.`id` AS `id`,`_lnkdocumenttopatch`.`patch_id` AS `patch_id`,`patch_patch_id_patch`.`name` AS `patch_name`,`_lnkdocumenttopatch`.`document_id` AS `document_id`,`document_document_id_document`.`name` AS `document_name`,cast(concat(coalesce(`_lnkdocumenttopatch`.`patch_id`,''),coalesce(' ',''),coalesce(`_lnkdocumenttopatch`.`document_id`,'')) as char charset utf8) AS `friendlyname`,cast(concat(coalesce(`patch_patch_id_patch`.`name`,'')) as char charset utf8) AS `patch_id_friendlyname`,`patch_patch_id_patch`.`finalclass` AS `patch_id_finalclass_recall`,if((`document_document_id_document`.`finalclass` = 'Document'),cast(concat(coalesce('Document','')) as char charset utf8),cast(concat(coalesce(`document_document_id_document`.`name`,'')) as char charset utf8)) AS `document_id_friendlyname`,`document_document_id_document`.`finalclass` AS `document_id_finalclass_recall`,coalesce((`document_document_id_document`.`status` = 'obsolete'),0) AS `document_id_obsolescence_flag` from ((`lnkdocumenttopatch` `_lnkdocumenttopatch` join `patch` `patch_patch_id_patch` on((`_lnkdocumenttopatch`.`patch_id` = `patch_patch_id_patch`.`id`))) join `document` `document_document_id_document` on((`_lnkdocumenttopatch`.`document_id` = `document_document_id_document`.`id`))) where 1 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_lnkdocumenttoservice`
--

/*!50001 DROP VIEW IF EXISTS `view_lnkdocumenttoservice`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_lnkdocumenttoservice` AS select distinct `_lnkdocumenttoservice`.`id` AS `id`,`_lnkdocumenttoservice`.`service_id` AS `service_id`,`service_service_id_service`.`name` AS `service_name`,`_lnkdocumenttoservice`.`document_id` AS `document_id`,`document_document_id_document`.`name` AS `document_name`,cast(concat(coalesce(`_lnkdocumenttoservice`.`service_id`,''),coalesce(' ',''),coalesce(`_lnkdocumenttoservice`.`document_id`,'')) as char charset utf8) AS `friendlyname`,cast(concat(coalesce(`service_service_id_service`.`name`,'')) as char charset utf8) AS `service_id_friendlyname`,if((`document_document_id_document`.`finalclass` = 'Document'),cast(concat(coalesce('Document','')) as char charset utf8),cast(concat(coalesce(`document_document_id_document`.`name`,'')) as char charset utf8)) AS `document_id_friendlyname`,`document_document_id_document`.`finalclass` AS `document_id_finalclass_recall`,coalesce((`document_document_id_document`.`status` = 'obsolete'),0) AS `document_id_obsolescence_flag` from ((`lnkdocumenttoservice` `_lnkdocumenttoservice` join `service` `service_service_id_service` on((`_lnkdocumenttoservice`.`service_id` = `service_service_id_service`.`id`))) join `document` `document_document_id_document` on((`_lnkdocumenttoservice`.`document_id` = `document_document_id_document`.`id`))) where 1 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_lnkdocumenttosoftware`
--

/*!50001 DROP VIEW IF EXISTS `view_lnkdocumenttosoftware`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_lnkdocumenttosoftware` AS select distinct `_lnkdocumenttosoftware`.`id` AS `id`,`_lnkdocumenttosoftware`.`software_id` AS `software_id`,`software_software_id_software`.`name` AS `software_name`,`_lnkdocumenttosoftware`.`document_id` AS `document_id`,`document_document_id_document`.`name` AS `document_name`,cast(concat(coalesce(`_lnkdocumenttosoftware`.`software_id`,''),coalesce(' ',''),coalesce(`_lnkdocumenttosoftware`.`document_id`,'')) as char charset utf8) AS `friendlyname`,cast(concat(coalesce(`software_software_id_software`.`name`,''),coalesce(' ',''),coalesce(`software_software_id_software`.`version`,'')) as char charset utf8) AS `software_id_friendlyname`,if((`document_document_id_document`.`finalclass` = 'Document'),cast(concat(coalesce('Document','')) as char charset utf8),cast(concat(coalesce(`document_document_id_document`.`name`,'')) as char charset utf8)) AS `document_id_friendlyname`,`document_document_id_document`.`finalclass` AS `document_id_finalclass_recall`,coalesce((`document_document_id_document`.`status` = 'obsolete'),0) AS `document_id_obsolescence_flag` from ((`lnkdocumenttosoftware` `_lnkdocumenttosoftware` join `software` `software_software_id_software` on((`_lnkdocumenttosoftware`.`software_id` = `software_software_id_software`.`id`))) join `document` `document_document_id_document` on((`_lnkdocumenttosoftware`.`document_id` = `document_document_id_document`.`id`))) where 1 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_lnkerrortofunctionalci`
--

/*!50001 DROP VIEW IF EXISTS `view_lnkerrortofunctionalci`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_lnkerrortofunctionalci` AS select distinct `_lnkerrortofunctionalci`.`link_id` AS `id`,`_lnkerrortofunctionalci`.`functionalci_id` AS `functionalci_id`,`functionalci_functionalci_id_functionalci`.`name` AS `functionalci_name`,`_lnkerrortofunctionalci`.`error_id` AS `error_id`,`knownerror_error_id_knownerror`.`name` AS `error_name`,`_lnkerrortofunctionalci`.`dummy` AS `reason`,cast(concat(coalesce('lnkErrorToFunctionalCI','')) as char charset utf8) AS `friendlyname`,if((`functionalci_functionalci_id_functionalci`.`finalclass` in ('Middleware','DBServer','WebServer','PCSoftware','OtherSoftware')),cast(concat(coalesce(`functionalci_functionalci_id_functionalci`.`name`,''),coalesce(' ',''),coalesce(`functionalci_system_id_functionalci`.`name`,'')) as char charset utf8),cast(concat(coalesce(`functionalci_functionalci_id_functionalci`.`name`,'')) as char charset utf8)) AS `functionalci_id_friendlyname`,`functionalci_functionalci_id_functionalci`.`finalclass` AS `functionalci_id_finalclass_recall`,if((`functionalci_functionalci_id_functionalci`.`finalclass` = 'FunctionalCI'),coalesce(0,0),if((`functionalci_functionalci_id_functionalci`.`finalclass` in ('Hypervisor','Farm','VirtualMachine')),coalesce((`functionalci_functionalci_id_fn_virtualdevice_virtualdevice`.`status` = 'obsolete'),0),if((`functionalci_functionalci_id_functionalci`.`finalclass` = 'WebApplication'),coalesce(coalesce((`webserver_webserver_id_softwareinstance`.`status` = 'inactive'),0),0),if((`functionalci_functionalci_id_functionalci`.`finalclass` = 'DatabaseSchema'),coalesce(coalesce((`dbserver_dbserver_id_softwareinstance`.`status` = 'inactive'),0),0),if((`functionalci_functionalci_id_functionalci`.`finalclass` = 'MiddlewareInstance'),coalesce(coalesce((`middleware_middleware_id_softwareinstance`.`status` = 'inactive'),0),0),if((`functionalci_functionalci_id_functionalci`.`finalclass` in ('Middleware','DBServer','WebServer','PCSoftware','OtherSoftware')),coalesce((`functionalci_functionalci_id_fn_softwareinstance_softwareinstance`.`status` = 'inactive'),0),if((`functionalci_functionalci_id_functionalci`.`finalclass` = 'BusinessProcess'),coalesce((`functionalci_functionalci_id_fn_businessprocess_businessprocess`.`status` = 'inactive'),0),if((`functionalci_functionalci_id_functionalci`.`finalclass` = 'ApplicationSolution'),coalesce((`functionalci_functionalci_id_fn_applicationsolution_applicationsolution`.`status` = 'inactive'),0),coalesce((`functionalci_functionalci_id_fn_physicaldevice_physicaldevice`.`status` = 'obsolete'),0))))))))) AS `functionalci_id_obsolescence_flag`,cast(concat(coalesce(`knownerror_error_id_knownerror`.`name`,'')) as char charset utf8) AS `error_id_friendlyname` from ((`lnkerrortofunctionalci` `_lnkerrortofunctionalci` join ((((((((`functionalci` `functionalci_functionalci_id_functionalci` left join (`softwareinstance` `functionalci_functionalci_id_fn_softwareinstance_softwareinstance` join `functionalci` `functionalci_system_id_functionalci` on((`functionalci_functionalci_id_fn_softwareinstance_softwareinstance`.`functionalci_id` = `functionalci_system_id_functionalci`.`id`))) on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_softwareinstance_softwareinstance`.`id`))) left join `virtualdevice` `functionalci_functionalci_id_fn_virtualdevice_virtualdevice` on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_virtualdevice_virtualdevice`.`id`))) left join (`webapplication` `functionalci_functionalci_id_fn_webapplication_webapplication` join (`functionalci` `webserver_webserver_id_functionalci` join `softwareinstance` `webserver_webserver_id_softwareinstance` on((`webserver_webserver_id_functionalci`.`id` = `webserver_webserver_id_softwareinstance`.`id`))) on((`functionalci_functionalci_id_fn_webapplication_webapplication`.`webserver_id` = `webserver_webserver_id_functionalci`.`id`))) on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_webapplication_webapplication`.`id`))) left join (`databaseschema` `functionalci_functionalci_id_fn_databaseschema_databaseschema` join (`functionalci` `dbserver_dbserver_id_functionalci` join `softwareinstance` `dbserver_dbserver_id_softwareinstance` on((`dbserver_dbserver_id_functionalci`.`id` = `dbserver_dbserver_id_softwareinstance`.`id`))) on((`functionalci_functionalci_id_fn_databaseschema_databaseschema`.`dbserver_id` = `dbserver_dbserver_id_functionalci`.`id`))) on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_databaseschema_databaseschema`.`id`))) left join (`middlewareinstance` `functionalci_functionalci_id_fn_middlewareinstance_middlewareinstance` join (`functionalci` `middleware_middleware_id_functionalci` join `softwareinstance` `middleware_middleware_id_softwareinstance` on((`middleware_middleware_id_functionalci`.`id` = `middleware_middleware_id_softwareinstance`.`id`))) on((`functionalci_functionalci_id_fn_middlewareinstance_middlewareinstance`.`middleware_id` = `middleware_middleware_id_functionalci`.`id`))) on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_middlewareinstance_middlewareinstance`.`id`))) left join `businessprocess` `functionalci_functionalci_id_fn_businessprocess_businessprocess` on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_businessprocess_businessprocess`.`id`))) left join `applicationsolution` `functionalci_functionalci_id_fn_applicationsolution_applicationsolution` on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_applicationsolution_applicationsolution`.`id`))) left join `physicaldevice` `functionalci_functionalci_id_fn_physicaldevice_physicaldevice` on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_physicaldevice_physicaldevice`.`id`))) on((`_lnkerrortofunctionalci`.`functionalci_id` = `functionalci_functionalci_id_functionalci`.`id`))) join `knownerror` `knownerror_error_id_knownerror` on((`_lnkerrortofunctionalci`.`error_id` = `knownerror_error_id_knownerror`.`id`))) where (coalesce((`webserver_webserver_id_functionalci`.`finalclass` = 'WebServer'),1) and coalesce((`dbserver_dbserver_id_functionalci`.`finalclass` = 'DBServer'),1) and coalesce((`middleware_middleware_id_functionalci`.`finalclass` = 'Middleware'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_lnkfunctionalcitoospatch`
--

/*!50001 DROP VIEW IF EXISTS `view_lnkfunctionalcitoospatch`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_lnkfunctionalcitoospatch` AS select distinct `_lnkfunctionalcitoospatch`.`id` AS `id`,`_lnkfunctionalcitoospatch`.`ospatch_id` AS `ospatch_id`,`ospatch_ospatch_id_patch`.`name` AS `ospatch_name`,`_lnkfunctionalcitoospatch`.`functionalci_id` AS `functionalci_id`,`functionalci_functionalci_id_functionalci`.`name` AS `functionalci_name`,cast(concat(coalesce(`_lnkfunctionalcitoospatch`.`ospatch_id`,''),coalesce(' ',''),coalesce(`_lnkfunctionalcitoospatch`.`functionalci_id`,'')) as char charset utf8) AS `friendlyname`,cast(concat(coalesce(`ospatch_ospatch_id_patch`.`name`,'')) as char charset utf8) AS `ospatch_id_friendlyname`,if((`functionalci_functionalci_id_functionalci`.`finalclass` in ('Middleware','DBServer','WebServer','PCSoftware','OtherSoftware')),cast(concat(coalesce(`functionalci_functionalci_id_functionalci`.`name`,''),coalesce(' ',''),coalesce(`functionalci_system_id_functionalci`.`name`,'')) as char charset utf8),cast(concat(coalesce(`functionalci_functionalci_id_functionalci`.`name`,'')) as char charset utf8)) AS `functionalci_id_friendlyname`,`functionalci_functionalci_id_functionalci`.`finalclass` AS `functionalci_id_finalclass_recall`,if((`functionalci_functionalci_id_functionalci`.`finalclass` = 'FunctionalCI'),coalesce(0,0),if((`functionalci_functionalci_id_functionalci`.`finalclass` in ('Hypervisor','Farm','VirtualMachine')),coalesce((`functionalci_functionalci_id_fn_virtualdevice_virtualdevice`.`status` = 'obsolete'),0),if((`functionalci_functionalci_id_functionalci`.`finalclass` = 'WebApplication'),coalesce(coalesce((`webserver_webserver_id_softwareinstance`.`status` = 'inactive'),0),0),if((`functionalci_functionalci_id_functionalci`.`finalclass` = 'DatabaseSchema'),coalesce(coalesce((`dbserver_dbserver_id_softwareinstance`.`status` = 'inactive'),0),0),if((`functionalci_functionalci_id_functionalci`.`finalclass` = 'MiddlewareInstance'),coalesce(coalesce((`middleware_middleware_id_softwareinstance`.`status` = 'inactive'),0),0),if((`functionalci_functionalci_id_functionalci`.`finalclass` in ('Middleware','DBServer','WebServer','PCSoftware','OtherSoftware')),coalesce((`functionalci_functionalci_id_fn_softwareinstance_softwareinstance`.`status` = 'inactive'),0),if((`functionalci_functionalci_id_functionalci`.`finalclass` = 'BusinessProcess'),coalesce((`functionalci_functionalci_id_fn_businessprocess_businessprocess`.`status` = 'inactive'),0),if((`functionalci_functionalci_id_functionalci`.`finalclass` = 'ApplicationSolution'),coalesce((`functionalci_functionalci_id_fn_applicationsolution_applicationsolution`.`status` = 'inactive'),0),coalesce((`functionalci_functionalci_id_fn_physicaldevice_physicaldevice`.`status` = 'obsolete'),0))))))))) AS `functionalci_id_obsolescence_flag` from ((`lnkfunctionalcitoospatch` `_lnkfunctionalcitoospatch` join `patch` `ospatch_ospatch_id_patch` on((`_lnkfunctionalcitoospatch`.`ospatch_id` = `ospatch_ospatch_id_patch`.`id`))) join ((((((((`functionalci` `functionalci_functionalci_id_functionalci` left join (`softwareinstance` `functionalci_functionalci_id_fn_softwareinstance_softwareinstance` join `functionalci` `functionalci_system_id_functionalci` on((`functionalci_functionalci_id_fn_softwareinstance_softwareinstance`.`functionalci_id` = `functionalci_system_id_functionalci`.`id`))) on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_softwareinstance_softwareinstance`.`id`))) left join `virtualdevice` `functionalci_functionalci_id_fn_virtualdevice_virtualdevice` on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_virtualdevice_virtualdevice`.`id`))) left join (`webapplication` `functionalci_functionalci_id_fn_webapplication_webapplication` join (`functionalci` `webserver_webserver_id_functionalci` join `softwareinstance` `webserver_webserver_id_softwareinstance` on((`webserver_webserver_id_functionalci`.`id` = `webserver_webserver_id_softwareinstance`.`id`))) on((`functionalci_functionalci_id_fn_webapplication_webapplication`.`webserver_id` = `webserver_webserver_id_functionalci`.`id`))) on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_webapplication_webapplication`.`id`))) left join (`databaseschema` `functionalci_functionalci_id_fn_databaseschema_databaseschema` join (`functionalci` `dbserver_dbserver_id_functionalci` join `softwareinstance` `dbserver_dbserver_id_softwareinstance` on((`dbserver_dbserver_id_functionalci`.`id` = `dbserver_dbserver_id_softwareinstance`.`id`))) on((`functionalci_functionalci_id_fn_databaseschema_databaseschema`.`dbserver_id` = `dbserver_dbserver_id_functionalci`.`id`))) on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_databaseschema_databaseschema`.`id`))) left join (`middlewareinstance` `functionalci_functionalci_id_fn_middlewareinstance_middlewareinstance` join (`functionalci` `middleware_middleware_id_functionalci` join `softwareinstance` `middleware_middleware_id_softwareinstance` on((`middleware_middleware_id_functionalci`.`id` = `middleware_middleware_id_softwareinstance`.`id`))) on((`functionalci_functionalci_id_fn_middlewareinstance_middlewareinstance`.`middleware_id` = `middleware_middleware_id_functionalci`.`id`))) on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_middlewareinstance_middlewareinstance`.`id`))) left join `businessprocess` `functionalci_functionalci_id_fn_businessprocess_businessprocess` on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_businessprocess_businessprocess`.`id`))) left join `applicationsolution` `functionalci_functionalci_id_fn_applicationsolution_applicationsolution` on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_applicationsolution_applicationsolution`.`id`))) left join `physicaldevice` `functionalci_functionalci_id_fn_physicaldevice_physicaldevice` on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_physicaldevice_physicaldevice`.`id`))) on((`_lnkfunctionalcitoospatch`.`functionalci_id` = `functionalci_functionalci_id_functionalci`.`id`))) where (coalesce((`ospatch_ospatch_id_patch`.`finalclass` = 'OSPatch'),1) and coalesce((`webserver_webserver_id_functionalci`.`finalclass` = 'WebServer'),1) and coalesce((`dbserver_dbserver_id_functionalci`.`finalclass` = 'DBServer'),1) and coalesce((`middleware_middleware_id_functionalci`.`finalclass` = 'Middleware'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_lnkfunctionalcitoprovidercontract`
--

/*!50001 DROP VIEW IF EXISTS `view_lnkfunctionalcitoprovidercontract`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_lnkfunctionalcitoprovidercontract` AS select distinct `_lnkfunctionalcitoprovidercontract`.`id` AS `id`,`_lnkfunctionalcitoprovidercontract`.`providercontract_id` AS `providercontract_id`,`providercontract_providercontract_id_contract`.`name` AS `providercontract_name`,`_lnkfunctionalcitoprovidercontract`.`functionalci_id` AS `functionalci_id`,`functionalci_functionalci_id_functionalci`.`name` AS `functionalci_name`,cast(concat(coalesce(`_lnkfunctionalcitoprovidercontract`.`providercontract_id`,''),coalesce(' ',''),coalesce(`_lnkfunctionalcitoprovidercontract`.`functionalci_id`,'')) as char charset utf8) AS `friendlyname`,cast(concat(coalesce(`providercontract_providercontract_id_contract`.`name`,'')) as char charset utf8) AS `providercontract_id_friendlyname`,if((`functionalci_functionalci_id_functionalci`.`finalclass` in ('Middleware','DBServer','WebServer','PCSoftware','OtherSoftware')),cast(concat(coalesce(`functionalci_functionalci_id_functionalci`.`name`,''),coalesce(' ',''),coalesce(`functionalci_system_id_functionalci`.`name`,'')) as char charset utf8),cast(concat(coalesce(`functionalci_functionalci_id_functionalci`.`name`,'')) as char charset utf8)) AS `functionalci_id_friendlyname`,`functionalci_functionalci_id_functionalci`.`finalclass` AS `functionalci_id_finalclass_recall`,if((`functionalci_functionalci_id_functionalci`.`finalclass` = 'FunctionalCI'),coalesce(0,0),if((`functionalci_functionalci_id_functionalci`.`finalclass` in ('Hypervisor','Farm','VirtualMachine')),coalesce((`functionalci_functionalci_id_fn_virtualdevice_virtualdevice`.`status` = 'obsolete'),0),if((`functionalci_functionalci_id_functionalci`.`finalclass` = 'WebApplication'),coalesce(coalesce((`webserver_webserver_id_softwareinstance`.`status` = 'inactive'),0),0),if((`functionalci_functionalci_id_functionalci`.`finalclass` = 'DatabaseSchema'),coalesce(coalesce((`dbserver_dbserver_id_softwareinstance`.`status` = 'inactive'),0),0),if((`functionalci_functionalci_id_functionalci`.`finalclass` = 'MiddlewareInstance'),coalesce(coalesce((`middleware_middleware_id_softwareinstance`.`status` = 'inactive'),0),0),if((`functionalci_functionalci_id_functionalci`.`finalclass` in ('Middleware','DBServer','WebServer','PCSoftware','OtherSoftware')),coalesce((`functionalci_functionalci_id_fn_softwareinstance_softwareinstance`.`status` = 'inactive'),0),if((`functionalci_functionalci_id_functionalci`.`finalclass` = 'BusinessProcess'),coalesce((`functionalci_functionalci_id_fn_businessprocess_businessprocess`.`status` = 'inactive'),0),if((`functionalci_functionalci_id_functionalci`.`finalclass` = 'ApplicationSolution'),coalesce((`functionalci_functionalci_id_fn_applicationsolution_applicationsolution`.`status` = 'inactive'),0),coalesce((`functionalci_functionalci_id_fn_physicaldevice_physicaldevice`.`status` = 'obsolete'),0))))))))) AS `functionalci_id_obsolescence_flag` from ((`lnkfunctionalcitoprovidercontract` `_lnkfunctionalcitoprovidercontract` join `contract` `providercontract_providercontract_id_contract` on((`_lnkfunctionalcitoprovidercontract`.`providercontract_id` = `providercontract_providercontract_id_contract`.`id`))) join ((((((((`functionalci` `functionalci_functionalci_id_functionalci` left join (`softwareinstance` `functionalci_functionalci_id_fn_softwareinstance_softwareinstance` join `functionalci` `functionalci_system_id_functionalci` on((`functionalci_functionalci_id_fn_softwareinstance_softwareinstance`.`functionalci_id` = `functionalci_system_id_functionalci`.`id`))) on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_softwareinstance_softwareinstance`.`id`))) left join `virtualdevice` `functionalci_functionalci_id_fn_virtualdevice_virtualdevice` on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_virtualdevice_virtualdevice`.`id`))) left join (`webapplication` `functionalci_functionalci_id_fn_webapplication_webapplication` join (`functionalci` `webserver_webserver_id_functionalci` join `softwareinstance` `webserver_webserver_id_softwareinstance` on((`webserver_webserver_id_functionalci`.`id` = `webserver_webserver_id_softwareinstance`.`id`))) on((`functionalci_functionalci_id_fn_webapplication_webapplication`.`webserver_id` = `webserver_webserver_id_functionalci`.`id`))) on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_webapplication_webapplication`.`id`))) left join (`databaseschema` `functionalci_functionalci_id_fn_databaseschema_databaseschema` join (`functionalci` `dbserver_dbserver_id_functionalci` join `softwareinstance` `dbserver_dbserver_id_softwareinstance` on((`dbserver_dbserver_id_functionalci`.`id` = `dbserver_dbserver_id_softwareinstance`.`id`))) on((`functionalci_functionalci_id_fn_databaseschema_databaseschema`.`dbserver_id` = `dbserver_dbserver_id_functionalci`.`id`))) on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_databaseschema_databaseschema`.`id`))) left join (`middlewareinstance` `functionalci_functionalci_id_fn_middlewareinstance_middlewareinstance` join (`functionalci` `middleware_middleware_id_functionalci` join `softwareinstance` `middleware_middleware_id_softwareinstance` on((`middleware_middleware_id_functionalci`.`id` = `middleware_middleware_id_softwareinstance`.`id`))) on((`functionalci_functionalci_id_fn_middlewareinstance_middlewareinstance`.`middleware_id` = `middleware_middleware_id_functionalci`.`id`))) on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_middlewareinstance_middlewareinstance`.`id`))) left join `businessprocess` `functionalci_functionalci_id_fn_businessprocess_businessprocess` on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_businessprocess_businessprocess`.`id`))) left join `applicationsolution` `functionalci_functionalci_id_fn_applicationsolution_applicationsolution` on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_applicationsolution_applicationsolution`.`id`))) left join `physicaldevice` `functionalci_functionalci_id_fn_physicaldevice_physicaldevice` on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_physicaldevice_physicaldevice`.`id`))) on((`_lnkfunctionalcitoprovidercontract`.`functionalci_id` = `functionalci_functionalci_id_functionalci`.`id`))) where (coalesce((`providercontract_providercontract_id_contract`.`finalclass` = 'ProviderContract'),1) and coalesce((`webserver_webserver_id_functionalci`.`finalclass` = 'WebServer'),1) and coalesce((`dbserver_dbserver_id_functionalci`.`finalclass` = 'DBServer'),1) and coalesce((`middleware_middleware_id_functionalci`.`finalclass` = 'Middleware'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_lnkfunctionalcitoticket`
--

/*!50001 DROP VIEW IF EXISTS `view_lnkfunctionalcitoticket`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_lnkfunctionalcitoticket` AS select distinct `_lnkfunctionalcitoticket`.`id` AS `id`,`_lnkfunctionalcitoticket`.`ticket_id` AS `ticket_id`,`ticket_ticket_id_ticket`.`ref` AS `ticket_ref`,`ticket_ticket_id_ticket`.`title` AS `ticket_title`,`_lnkfunctionalcitoticket`.`functionalci_id` AS `functionalci_id`,`functionalci_functionalci_id_functionalci`.`name` AS `functionalci_name`,`_lnkfunctionalcitoticket`.`impact` AS `impact`,`_lnkfunctionalcitoticket`.`impact_code` AS `impact_code`,cast(concat(coalesce(`_lnkfunctionalcitoticket`.`ticket_id`,''),coalesce(' ',''),coalesce(`_lnkfunctionalcitoticket`.`functionalci_id`,'')) as char charset utf8) AS `friendlyname`,cast(concat(coalesce(`ticket_ticket_id_ticket`.`ref`,'')) as char charset utf8) AS `ticket_id_friendlyname`,`ticket_ticket_id_ticket`.`finalclass` AS `ticket_id_finalclass_recall`,coalesce(((`ticket_ticket_id_ticket`.`operational_status` = 'closed') and ((`ticket_ticket_id_ticket`.`close_date` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00')) or (isnull(`ticket_ticket_id_ticket`.`close_date`) and (`ticket_ticket_id_ticket`.`last_update` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00'))))),0) AS `ticket_id_obsolescence_flag`,if((`functionalci_functionalci_id_functionalci`.`finalclass` in ('Middleware','DBServer','WebServer','PCSoftware','OtherSoftware')),cast(concat(coalesce(`functionalci_functionalci_id_functionalci`.`name`,''),coalesce(' ',''),coalesce(`functionalci_system_id_functionalci`.`name`,'')) as char charset utf8),cast(concat(coalesce(`functionalci_functionalci_id_functionalci`.`name`,'')) as char charset utf8)) AS `functionalci_id_friendlyname`,`functionalci_functionalci_id_functionalci`.`finalclass` AS `functionalci_id_finalclass_recall`,if((`functionalci_functionalci_id_functionalci`.`finalclass` = 'FunctionalCI'),coalesce(0,0),if((`functionalci_functionalci_id_functionalci`.`finalclass` in ('Hypervisor','Farm','VirtualMachine')),coalesce((`functionalci_functionalci_id_fn_virtualdevice_virtualdevice`.`status` = 'obsolete'),0),if((`functionalci_functionalci_id_functionalci`.`finalclass` = 'WebApplication'),coalesce(coalesce((`webserver_webserver_id_softwareinstance`.`status` = 'inactive'),0),0),if((`functionalci_functionalci_id_functionalci`.`finalclass` = 'DatabaseSchema'),coalesce(coalesce((`dbserver_dbserver_id_softwareinstance`.`status` = 'inactive'),0),0),if((`functionalci_functionalci_id_functionalci`.`finalclass` = 'MiddlewareInstance'),coalesce(coalesce((`middleware_middleware_id_softwareinstance`.`status` = 'inactive'),0),0),if((`functionalci_functionalci_id_functionalci`.`finalclass` in ('Middleware','DBServer','WebServer','PCSoftware','OtherSoftware')),coalesce((`functionalci_functionalci_id_fn_softwareinstance_softwareinstance`.`status` = 'inactive'),0),if((`functionalci_functionalci_id_functionalci`.`finalclass` = 'BusinessProcess'),coalesce((`functionalci_functionalci_id_fn_businessprocess_businessprocess`.`status` = 'inactive'),0),if((`functionalci_functionalci_id_functionalci`.`finalclass` = 'ApplicationSolution'),coalesce((`functionalci_functionalci_id_fn_applicationsolution_applicationsolution`.`status` = 'inactive'),0),coalesce((`functionalci_functionalci_id_fn_physicaldevice_physicaldevice`.`status` = 'obsolete'),0))))))))) AS `functionalci_id_obsolescence_flag` from ((`lnkfunctionalcitoticket` `_lnkfunctionalcitoticket` join `ticket` `ticket_ticket_id_ticket` on((`_lnkfunctionalcitoticket`.`ticket_id` = `ticket_ticket_id_ticket`.`id`))) join ((((((((`functionalci` `functionalci_functionalci_id_functionalci` left join (`softwareinstance` `functionalci_functionalci_id_fn_softwareinstance_softwareinstance` join `functionalci` `functionalci_system_id_functionalci` on((`functionalci_functionalci_id_fn_softwareinstance_softwareinstance`.`functionalci_id` = `functionalci_system_id_functionalci`.`id`))) on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_softwareinstance_softwareinstance`.`id`))) left join `virtualdevice` `functionalci_functionalci_id_fn_virtualdevice_virtualdevice` on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_virtualdevice_virtualdevice`.`id`))) left join (`webapplication` `functionalci_functionalci_id_fn_webapplication_webapplication` join (`functionalci` `webserver_webserver_id_functionalci` join `softwareinstance` `webserver_webserver_id_softwareinstance` on((`webserver_webserver_id_functionalci`.`id` = `webserver_webserver_id_softwareinstance`.`id`))) on((`functionalci_functionalci_id_fn_webapplication_webapplication`.`webserver_id` = `webserver_webserver_id_functionalci`.`id`))) on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_webapplication_webapplication`.`id`))) left join (`databaseschema` `functionalci_functionalci_id_fn_databaseschema_databaseschema` join (`functionalci` `dbserver_dbserver_id_functionalci` join `softwareinstance` `dbserver_dbserver_id_softwareinstance` on((`dbserver_dbserver_id_functionalci`.`id` = `dbserver_dbserver_id_softwareinstance`.`id`))) on((`functionalci_functionalci_id_fn_databaseschema_databaseschema`.`dbserver_id` = `dbserver_dbserver_id_functionalci`.`id`))) on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_databaseschema_databaseschema`.`id`))) left join (`middlewareinstance` `functionalci_functionalci_id_fn_middlewareinstance_middlewareinstance` join (`functionalci` `middleware_middleware_id_functionalci` join `softwareinstance` `middleware_middleware_id_softwareinstance` on((`middleware_middleware_id_functionalci`.`id` = `middleware_middleware_id_softwareinstance`.`id`))) on((`functionalci_functionalci_id_fn_middlewareinstance_middlewareinstance`.`middleware_id` = `middleware_middleware_id_functionalci`.`id`))) on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_middlewareinstance_middlewareinstance`.`id`))) left join `businessprocess` `functionalci_functionalci_id_fn_businessprocess_businessprocess` on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_businessprocess_businessprocess`.`id`))) left join `applicationsolution` `functionalci_functionalci_id_fn_applicationsolution_applicationsolution` on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_applicationsolution_applicationsolution`.`id`))) left join `physicaldevice` `functionalci_functionalci_id_fn_physicaldevice_physicaldevice` on((`functionalci_functionalci_id_functionalci`.`id` = `functionalci_functionalci_id_fn_physicaldevice_physicaldevice`.`id`))) on((`_lnkfunctionalcitoticket`.`functionalci_id` = `functionalci_functionalci_id_functionalci`.`id`))) where (coalesce((`webserver_webserver_id_functionalci`.`finalclass` = 'WebServer'),1) and coalesce((`dbserver_dbserver_id_functionalci`.`finalclass` = 'DBServer'),1) and coalesce((`middleware_middleware_id_functionalci`.`finalclass` = 'Middleware'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_lnkgrouptoci`
--

/*!50001 DROP VIEW IF EXISTS `view_lnkgrouptoci`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_lnkgrouptoci` AS select distinct `_lnkgrouptoci`.`id` AS `id`,`_lnkgrouptoci`.`group_id` AS `group_id`,`group_group_id_group`.`name` AS `group_name`,`_lnkgrouptoci`.`ci_id` AS `ci_id`,`functionalci_ci_id_functionalci`.`name` AS `ci_name`,`_lnkgrouptoci`.`reason` AS `reason`,cast(concat(coalesce(`_lnkgrouptoci`.`group_id`,'')) as char charset utf8) AS `friendlyname`,cast(concat(coalesce(`group_group_id_group`.`name`,'')) as char charset utf8) AS `group_id_friendlyname`,coalesce((`group_group_id_group`.`status` = 'obsolete'),0) AS `group_id_obsolescence_flag`,if((`functionalci_ci_id_functionalci`.`finalclass` in ('Middleware','DBServer','WebServer','PCSoftware','OtherSoftware')),cast(concat(coalesce(`functionalci_ci_id_functionalci`.`name`,''),coalesce(' ',''),coalesce(`functionalci_system_id_functionalci`.`name`,'')) as char charset utf8),cast(concat(coalesce(`functionalci_ci_id_functionalci`.`name`,'')) as char charset utf8)) AS `ci_id_friendlyname`,`functionalci_ci_id_functionalci`.`finalclass` AS `ci_id_finalclass_recall`,if((`functionalci_ci_id_functionalci`.`finalclass` = 'FunctionalCI'),coalesce(0,0),if((`functionalci_ci_id_functionalci`.`finalclass` in ('Hypervisor','Farm','VirtualMachine')),coalesce((`functionalci_ci_id_fn_virtualdevice_virtualdevice`.`status` = 'obsolete'),0),if((`functionalci_ci_id_functionalci`.`finalclass` = 'WebApplication'),coalesce(coalesce((`webserver_webserver_id_softwareinstance`.`status` = 'inactive'),0),0),if((`functionalci_ci_id_functionalci`.`finalclass` = 'DatabaseSchema'),coalesce(coalesce((`dbserver_dbserver_id_softwareinstance`.`status` = 'inactive'),0),0),if((`functionalci_ci_id_functionalci`.`finalclass` = 'MiddlewareInstance'),coalesce(coalesce((`middleware_middleware_id_softwareinstance`.`status` = 'inactive'),0),0),if((`functionalci_ci_id_functionalci`.`finalclass` in ('Middleware','DBServer','WebServer','PCSoftware','OtherSoftware')),coalesce((`functionalci_ci_id_fn_softwareinstance_softwareinstance`.`status` = 'inactive'),0),if((`functionalci_ci_id_functionalci`.`finalclass` = 'BusinessProcess'),coalesce((`functionalci_ci_id_fn_businessprocess_businessprocess`.`status` = 'inactive'),0),if((`functionalci_ci_id_functionalci`.`finalclass` = 'ApplicationSolution'),coalesce((`functionalci_ci_id_fn_applicationsolution_applicationsolution`.`status` = 'inactive'),0),coalesce((`functionalci_ci_id_fn_physicaldevice_physicaldevice`.`status` = 'obsolete'),0))))))))) AS `ci_id_obsolescence_flag` from ((`lnkgrouptoci` `_lnkgrouptoci` join `group` `group_group_id_group` on((`_lnkgrouptoci`.`group_id` = `group_group_id_group`.`id`))) join ((((((((`functionalci` `functionalci_ci_id_functionalci` left join (`softwareinstance` `functionalci_ci_id_fn_softwareinstance_softwareinstance` join `functionalci` `functionalci_system_id_functionalci` on((`functionalci_ci_id_fn_softwareinstance_softwareinstance`.`functionalci_id` = `functionalci_system_id_functionalci`.`id`))) on((`functionalci_ci_id_functionalci`.`id` = `functionalci_ci_id_fn_softwareinstance_softwareinstance`.`id`))) left join `virtualdevice` `functionalci_ci_id_fn_virtualdevice_virtualdevice` on((`functionalci_ci_id_functionalci`.`id` = `functionalci_ci_id_fn_virtualdevice_virtualdevice`.`id`))) left join (`webapplication` `functionalci_ci_id_fn_webapplication_webapplication` join (`functionalci` `webserver_webserver_id_functionalci` join `softwareinstance` `webserver_webserver_id_softwareinstance` on((`webserver_webserver_id_functionalci`.`id` = `webserver_webserver_id_softwareinstance`.`id`))) on((`functionalci_ci_id_fn_webapplication_webapplication`.`webserver_id` = `webserver_webserver_id_functionalci`.`id`))) on((`functionalci_ci_id_functionalci`.`id` = `functionalci_ci_id_fn_webapplication_webapplication`.`id`))) left join (`databaseschema` `functionalci_ci_id_fn_databaseschema_databaseschema` join (`functionalci` `dbserver_dbserver_id_functionalci` join `softwareinstance` `dbserver_dbserver_id_softwareinstance` on((`dbserver_dbserver_id_functionalci`.`id` = `dbserver_dbserver_id_softwareinstance`.`id`))) on((`functionalci_ci_id_fn_databaseschema_databaseschema`.`dbserver_id` = `dbserver_dbserver_id_functionalci`.`id`))) on((`functionalci_ci_id_functionalci`.`id` = `functionalci_ci_id_fn_databaseschema_databaseschema`.`id`))) left join (`middlewareinstance` `functionalci_ci_id_fn_middlewareinstance_middlewareinstance` join (`functionalci` `middleware_middleware_id_functionalci` join `softwareinstance` `middleware_middleware_id_softwareinstance` on((`middleware_middleware_id_functionalci`.`id` = `middleware_middleware_id_softwareinstance`.`id`))) on((`functionalci_ci_id_fn_middlewareinstance_middlewareinstance`.`middleware_id` = `middleware_middleware_id_functionalci`.`id`))) on((`functionalci_ci_id_functionalci`.`id` = `functionalci_ci_id_fn_middlewareinstance_middlewareinstance`.`id`))) left join `businessprocess` `functionalci_ci_id_fn_businessprocess_businessprocess` on((`functionalci_ci_id_functionalci`.`id` = `functionalci_ci_id_fn_businessprocess_businessprocess`.`id`))) left join `applicationsolution` `functionalci_ci_id_fn_applicationsolution_applicationsolution` on((`functionalci_ci_id_functionalci`.`id` = `functionalci_ci_id_fn_applicationsolution_applicationsolution`.`id`))) left join `physicaldevice` `functionalci_ci_id_fn_physicaldevice_physicaldevice` on((`functionalci_ci_id_functionalci`.`id` = `functionalci_ci_id_fn_physicaldevice_physicaldevice`.`id`))) on((`_lnkgrouptoci`.`ci_id` = `functionalci_ci_id_functionalci`.`id`))) where (coalesce((`webserver_webserver_id_functionalci`.`finalclass` = 'WebServer'),1) and coalesce((`dbserver_dbserver_id_functionalci`.`finalclass` = 'DBServer'),1) and coalesce((`middleware_middleware_id_functionalci`.`finalclass` = 'Middleware'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_lnkpersontoteam`
--

/*!50001 DROP VIEW IF EXISTS `view_lnkpersontoteam`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_lnkpersontoteam` AS select distinct `_lnkpersontoteam`.`id` AS `id`,`_lnkpersontoteam`.`team_id` AS `team_id`,`team_team_id_contact`.`name` AS `team_name`,`_lnkpersontoteam`.`person_id` AS `person_id`,`person_person_id_contact`.`name` AS `person_name`,`_lnkpersontoteam`.`role_id` AS `role_id`,`contacttype_role_id_typology`.`name` AS `role_name`,cast(concat(coalesce(`_lnkpersontoteam`.`team_id`,''),coalesce(' ',''),coalesce(`_lnkpersontoteam`.`person_id`,'')) as char charset utf8) AS `friendlyname`,cast(concat(coalesce(`team_team_id_contact`.`name`,'')) as char charset utf8) AS `team_id_friendlyname`,coalesce((`team_team_id_contact`.`status` = 'inactive'),0) AS `team_id_obsolescence_flag`,cast(concat(coalesce(`person_person_id_person`.`first_name`,''),coalesce(' ',''),coalesce(`person_person_id_contact`.`name`,'')) as char charset utf8) AS `person_id_friendlyname`,coalesce((`person_person_id_contact`.`status` = 'inactive'),0) AS `person_id_obsolescence_flag`,cast(concat(coalesce(`contacttype_role_id_typology`.`name`,'')) as char charset utf8) AS `role_id_friendlyname` from (((`lnkpersontoteam` `_lnkpersontoteam` join `contact` `team_team_id_contact` on((`_lnkpersontoteam`.`team_id` = `team_team_id_contact`.`id`))) join (`contact` `person_person_id_contact` join `person` `person_person_id_person` on((`person_person_id_contact`.`id` = `person_person_id_person`.`id`))) on((`_lnkpersontoteam`.`person_id` = `person_person_id_contact`.`id`))) left join `typology` `contacttype_role_id_typology` on((`_lnkpersontoteam`.`role_id` = `contacttype_role_id_typology`.`id`))) where (coalesce((`team_team_id_contact`.`finalclass` = 'Team'),1) and coalesce((`person_person_id_contact`.`finalclass` = 'Person'),1) and coalesce((`contacttype_role_id_typology`.`finalclass` = 'ContactType'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_lnkphysicalinterfacetovlan`
--

/*!50001 DROP VIEW IF EXISTS `view_lnkphysicalinterfacetovlan`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_lnkphysicalinterfacetovlan` AS select distinct `_lnkphysicalinterfacetovlan`.`id` AS `id`,`_lnkphysicalinterfacetovlan`.`physicalinterface_id` AS `physicalinterface_id`,`physicalinterface_physicalinterface_id_networkinterface`.`name` AS `physicalinterface_name`,`physicalinterface_physicalinterface_id_physicalinterface`.`connectableci_id` AS `physicalinterface_device_id`,`connectableci_connectableci_id_functionalci`.`name` AS `physicalinterface_device_name`,`_lnkphysicalinterfacetovlan`.`vlan_id` AS `vlan_id`,`vlan_vlan_id_vlan`.`vlan_tag` AS `vlan_tag`,cast(concat(coalesce(`_lnkphysicalinterfacetovlan`.`physicalinterface_id`,''),coalesce(' ',''),coalesce(`_lnkphysicalinterfacetovlan`.`vlan_id`,'')) as char charset utf8) AS `friendlyname`,cast(concat(coalesce(`physicalinterface_physicalinterface_id_networkinterface`.`name`,''),coalesce(' ',''),coalesce(`connectableci_connectableci_id_functionalci`.`name`,'')) as char charset utf8) AS `physicalinterface_id_friendlyname`,coalesce(coalesce((`connectableci_connectableci_id_physicaldevice`.`status` = 'obsolete'),0),0) AS `physicalinterface_id_obsolescence_flag`,cast(concat(coalesce(`connectableci_connectableci_id_functionalci`.`name`,'')) as char charset utf8) AS `physicalinterface_device_id_friendlyname`,coalesce((`connectableci_connectableci_id_physicaldevice`.`status` = 'obsolete'),0) AS `physicalinterface_device_id_obsolescence_flag`,cast(concat(coalesce(`vlan_vlan_id_vlan`.`vlan_tag`,'')) as char charset utf8) AS `vlan_id_friendlyname` from ((`lnkphysicalinterfacetovlan` `_lnkphysicalinterfacetovlan` join (`networkinterface` `physicalinterface_physicalinterface_id_networkinterface` join (`physicalinterface` `physicalinterface_physicalinterface_id_physicalinterface` join (`functionalci` `connectableci_connectableci_id_functionalci` join `physicaldevice` `connectableci_connectableci_id_physicaldevice` on((`connectableci_connectableci_id_functionalci`.`id` = `connectableci_connectableci_id_physicaldevice`.`id`))) on((`physicalinterface_physicalinterface_id_physicalinterface`.`connectableci_id` = `connectableci_connectableci_id_functionalci`.`id`))) on((`physicalinterface_physicalinterface_id_networkinterface`.`id` = `physicalinterface_physicalinterface_id_physicalinterface`.`id`))) on((`_lnkphysicalinterfacetovlan`.`physicalinterface_id` = `physicalinterface_physicalinterface_id_networkinterface`.`id`))) join `vlan` `vlan_vlan_id_vlan` on((`_lnkphysicalinterfacetovlan`.`vlan_id` = `vlan_vlan_id_vlan`.`id`))) where (coalesce((`physicalinterface_physicalinterface_id_networkinterface`.`finalclass` = 'PhysicalInterface'),1) and coalesce((`connectableci_connectableci_id_functionalci`.`finalclass` in ('DatacenterDevice','NetworkDevice','Server','PC','Printer','StorageSystem','SANSwitch','TapeLibrary','NAS','ConnectableCI')),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_lnksantodatacenterdevice`
--

/*!50001 DROP VIEW IF EXISTS `view_lnksantodatacenterdevice`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_lnksantodatacenterdevice` AS select distinct `_lnkdatacenterdevicetosan`.`id` AS `id`,`_lnkdatacenterdevicetosan`.`san_id` AS `san_id`,`sanswitch_san_id_functionalci`.`name` AS `san_name`,`_lnkdatacenterdevicetosan`.`datacenterdevice_id` AS `datacenterdevice_id`,`datacenterdevice_datacenterdevice_id_functionalci`.`name` AS `datacenterdevice_name`,`_lnkdatacenterdevicetosan`.`san_port` AS `san_port`,`_lnkdatacenterdevicetosan`.`datacenterdevice_port` AS `datacenterdevice_port`,cast(concat(coalesce(`_lnkdatacenterdevicetosan`.`san_id`,'')) as char charset utf8) AS `friendlyname`,cast(concat(coalesce(`sanswitch_san_id_functionalci`.`name`,'')) as char charset utf8) AS `san_id_friendlyname`,coalesce((`sanswitch_san_id_physicaldevice`.`status` = 'obsolete'),0) AS `san_id_obsolescence_flag`,cast(concat(coalesce(`datacenterdevice_datacenterdevice_id_functionalci`.`name`,'')) as char charset utf8) AS `datacenterdevice_id_friendlyname`,`datacenterdevice_datacenterdevice_id_functionalci`.`finalclass` AS `datacenterdevice_id_finalclass_recall`,coalesce((`datacenterdevice_datacenterdevice_id_physicaldevice`.`status` = 'obsolete'),0) AS `datacenterdevice_id_obsolescence_flag` from ((`lnkdatacenterdevicetosan` `_lnkdatacenterdevicetosan` join (`functionalci` `sanswitch_san_id_functionalci` join `physicaldevice` `sanswitch_san_id_physicaldevice` on((`sanswitch_san_id_functionalci`.`id` = `sanswitch_san_id_physicaldevice`.`id`))) on((`_lnkdatacenterdevicetosan`.`san_id` = `sanswitch_san_id_functionalci`.`id`))) join (`functionalci` `datacenterdevice_datacenterdevice_id_functionalci` join `physicaldevice` `datacenterdevice_datacenterdevice_id_physicaldevice` on((`datacenterdevice_datacenterdevice_id_functionalci`.`id` = `datacenterdevice_datacenterdevice_id_physicaldevice`.`id`))) on((`_lnkdatacenterdevicetosan`.`datacenterdevice_id` = `datacenterdevice_datacenterdevice_id_functionalci`.`id`))) where (coalesce((`sanswitch_san_id_functionalci`.`finalclass` = 'SANSwitch'),1) and coalesce((`datacenterdevice_datacenterdevice_id_functionalci`.`finalclass` in ('NetworkDevice','Server','StorageSystem','SANSwitch','TapeLibrary','NAS','DatacenterDevice')),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_lnkservertovolume`
--

/*!50001 DROP VIEW IF EXISTS `view_lnkservertovolume`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_lnkservertovolume` AS select distinct `_lnkservertovolume`.`id` AS `id`,`_lnkservertovolume`.`volume_id` AS `volume_id`,`logicalvolume_volume_id_logicalvolume`.`name` AS `volume_name`,`_lnkservertovolume`.`server_id` AS `server_id`,`server_server_id_functionalci`.`name` AS `server_name`,`_lnkservertovolume`.`size_used` AS `size_used`,cast(concat(coalesce(`_lnkservertovolume`.`volume_id`,'')) as char charset utf8) AS `friendlyname`,cast(concat(coalesce(`storagesystem_storagesystem_id_functionalci`.`name`,''),coalesce(' ',''),coalesce(`logicalvolume_volume_id_logicalvolume`.`name`,'')) as char charset utf8) AS `volume_id_friendlyname`,coalesce(coalesce((`storagesystem_storagesystem_id_physicaldevice`.`status` = 'obsolete'),0),0) AS `volume_id_obsolescence_flag`,cast(concat(coalesce(`server_server_id_functionalci`.`name`,'')) as char charset utf8) AS `server_id_friendlyname`,coalesce((`server_server_id_physicaldevice`.`status` = 'obsolete'),0) AS `server_id_obsolescence_flag` from ((`lnkservertovolume` `_lnkservertovolume` join (`logicalvolume` `logicalvolume_volume_id_logicalvolume` join (`functionalci` `storagesystem_storagesystem_id_functionalci` join `physicaldevice` `storagesystem_storagesystem_id_physicaldevice` on((`storagesystem_storagesystem_id_functionalci`.`id` = `storagesystem_storagesystem_id_physicaldevice`.`id`))) on((`logicalvolume_volume_id_logicalvolume`.`storagesystem_id` = `storagesystem_storagesystem_id_functionalci`.`id`))) on((`_lnkservertovolume`.`volume_id` = `logicalvolume_volume_id_logicalvolume`.`id`))) join (`functionalci` `server_server_id_functionalci` join `physicaldevice` `server_server_id_physicaldevice` on((`server_server_id_functionalci`.`id` = `server_server_id_physicaldevice`.`id`))) on((`_lnkservertovolume`.`server_id` = `server_server_id_functionalci`.`id`))) where (coalesce((`storagesystem_storagesystem_id_functionalci`.`finalclass` = 'StorageSystem'),1) and coalesce((`server_server_id_functionalci`.`finalclass` = 'Server'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_lnkslatoslt`
--

/*!50001 DROP VIEW IF EXISTS `view_lnkslatoslt`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_lnkslatoslt` AS select distinct `_lnkslatoslt`.`id` AS `id`,`_lnkslatoslt`.`sla_id` AS `sla_id`,`sla_sla_id_sla`.`name` AS `sla_name`,`_lnkslatoslt`.`slt_id` AS `slt_id`,`slt_slt_id_slt`.`name` AS `slt_name`,`slt_slt_id_slt`.`metric` AS `slt_metric`,`slt_slt_id_slt`.`request_type` AS `slt_request_type`,`slt_slt_id_slt`.`priority` AS `slt_ticket_priority`,`slt_slt_id_slt`.`value` AS `slt_value`,`slt_slt_id_slt`.`unit` AS `slt_value_unit`,cast(concat(coalesce(`_lnkslatoslt`.`sla_id`,''),coalesce(' ',''),coalesce(`_lnkslatoslt`.`slt_id`,'')) as char charset utf8) AS `friendlyname`,cast(concat(coalesce(`sla_sla_id_sla`.`name`,'')) as char charset utf8) AS `sla_id_friendlyname`,cast(concat(coalesce(`slt_slt_id_slt`.`name`,'')) as char charset utf8) AS `slt_id_friendlyname` from ((`lnkslatoslt` `_lnkslatoslt` join `sla` `sla_sla_id_sla` on((`_lnkslatoslt`.`sla_id` = `sla_sla_id_sla`.`id`))) join `slt` `slt_slt_id_slt` on((`_lnkslatoslt`.`slt_id` = `slt_slt_id_slt`.`id`))) where 1 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_lnksoftwareinstancetosoftwarepatch`
--

/*!50001 DROP VIEW IF EXISTS `view_lnksoftwareinstancetosoftwarepatch`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_lnksoftwareinstancetosoftwarepatch` AS select distinct `_lnksoftwareinstancetosoftwarepatch`.`id` AS `id`,`_lnksoftwareinstancetosoftwarepatch`.`softwarepatch_id` AS `softwarepatch_id`,`softwarepatch_softwarepatch_id_patch`.`name` AS `softwarepatch_name`,`_lnksoftwareinstancetosoftwarepatch`.`softwareinstance_id` AS `softwareinstance_id`,`softwareinstance_softwareinstance_id_functionalci`.`name` AS `softwareinstance_name`,cast(concat(coalesce(`_lnksoftwareinstancetosoftwarepatch`.`softwarepatch_id`,''),coalesce(' ',''),coalesce(`_lnksoftwareinstancetosoftwarepatch`.`softwareinstance_id`,'')) as char charset utf8) AS `friendlyname`,cast(concat(coalesce(`softwarepatch_softwarepatch_id_patch`.`name`,'')) as char charset utf8) AS `softwarepatch_id_friendlyname`,cast(concat(coalesce(`softwareinstance_softwareinstance_id_functionalci`.`name`,''),coalesce(' ',''),coalesce(`functionalci_system_id_functionalci`.`name`,'')) as char charset utf8) AS `softwareinstance_id_friendlyname`,`softwareinstance_softwareinstance_id_functionalci`.`finalclass` AS `softwareinstance_id_finalclass_recall`,coalesce((`softwareinstance_softwareinstance_id_softwareinstance`.`status` = 'inactive'),0) AS `softwareinstance_id_obsolescence_flag` from ((`lnksoftwareinstancetosoftwarepatch` `_lnksoftwareinstancetosoftwarepatch` join `patch` `softwarepatch_softwarepatch_id_patch` on((`_lnksoftwareinstancetosoftwarepatch`.`softwarepatch_id` = `softwarepatch_softwarepatch_id_patch`.`id`))) join (`functionalci` `softwareinstance_softwareinstance_id_functionalci` join (`softwareinstance` `softwareinstance_softwareinstance_id_softwareinstance` join `functionalci` `functionalci_system_id_functionalci` on((`softwareinstance_softwareinstance_id_softwareinstance`.`functionalci_id` = `functionalci_system_id_functionalci`.`id`))) on((`softwareinstance_softwareinstance_id_functionalci`.`id` = `softwareinstance_softwareinstance_id_softwareinstance`.`id`))) on((`_lnksoftwareinstancetosoftwarepatch`.`softwareinstance_id` = `softwareinstance_softwareinstance_id_functionalci`.`id`))) where (coalesce((`softwarepatch_softwarepatch_id_patch`.`finalclass` = 'SoftwarePatch'),1) and coalesce((`softwareinstance_softwareinstance_id_functionalci`.`finalclass` in ('Middleware','DBServer','WebServer','PCSoftware','OtherSoftware','SoftwareInstance')),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_lnksubnettovlan`
--

/*!50001 DROP VIEW IF EXISTS `view_lnksubnettovlan`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_lnksubnettovlan` AS select distinct `_lnksubnettovlan`.`id` AS `id`,`_lnksubnettovlan`.`subnet_id` AS `subnet_id`,`subnet_subnet_id_subnet`.`ip` AS `subnet_ip`,`subnet_subnet_id_subnet`.`subnet_name` AS `subnet_name`,`_lnksubnettovlan`.`vlan_id` AS `vlan_id`,`vlan_vlan_id_vlan`.`vlan_tag` AS `vlan_tag`,cast(concat(coalesce(`_lnksubnettovlan`.`subnet_id`,''),coalesce(' ',''),coalesce(`_lnksubnettovlan`.`vlan_id`,'')) as char charset utf8) AS `friendlyname`,cast(concat(coalesce(`subnet_subnet_id_subnet`.`ip`,''),coalesce(' ',''),coalesce(`subnet_subnet_id_subnet`.`ip_mask`,'')) as char charset utf8) AS `subnet_id_friendlyname`,cast(concat(coalesce(`vlan_vlan_id_vlan`.`vlan_tag`,'')) as char charset utf8) AS `vlan_id_friendlyname` from ((`lnksubnettovlan` `_lnksubnettovlan` join `subnet` `subnet_subnet_id_subnet` on((`_lnksubnettovlan`.`subnet_id` = `subnet_subnet_id_subnet`.`id`))) join `vlan` `vlan_vlan_id_vlan` on((`_lnksubnettovlan`.`vlan_id` = `vlan_vlan_id_vlan`.`id`))) where 1 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_lnkvirtualdevicetovolume`
--

/*!50001 DROP VIEW IF EXISTS `view_lnkvirtualdevicetovolume`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_lnkvirtualdevicetovolume` AS select distinct `_lnkvirtualdevicetovolume`.`id` AS `id`,`_lnkvirtualdevicetovolume`.`volume_id` AS `volume_id`,`logicalvolume_volume_id_logicalvolume`.`name` AS `volume_name`,`_lnkvirtualdevicetovolume`.`virtualdevice_id` AS `virtualdevice_id`,`virtualdevice_virtualdevice_id_functionalci`.`name` AS `virtualdevice_name`,`_lnkvirtualdevicetovolume`.`size_used` AS `size_used`,cast(concat(coalesce(`_lnkvirtualdevicetovolume`.`volume_id`,'')) as char charset utf8) AS `friendlyname`,cast(concat(coalesce(`storagesystem_storagesystem_id_functionalci`.`name`,''),coalesce(' ',''),coalesce(`logicalvolume_volume_id_logicalvolume`.`name`,'')) as char charset utf8) AS `volume_id_friendlyname`,coalesce(coalesce((`storagesystem_storagesystem_id_physicaldevice`.`status` = 'obsolete'),0),0) AS `volume_id_obsolescence_flag`,cast(concat(coalesce(`virtualdevice_virtualdevice_id_functionalci`.`name`,'')) as char charset utf8) AS `virtualdevice_id_friendlyname`,`virtualdevice_virtualdevice_id_functionalci`.`finalclass` AS `virtualdevice_id_finalclass_recall`,coalesce((`virtualdevice_virtualdevice_id_virtualdevice`.`status` = 'obsolete'),0) AS `virtualdevice_id_obsolescence_flag` from ((`lnkvirtualdevicetovolume` `_lnkvirtualdevicetovolume` join (`logicalvolume` `logicalvolume_volume_id_logicalvolume` join (`functionalci` `storagesystem_storagesystem_id_functionalci` join `physicaldevice` `storagesystem_storagesystem_id_physicaldevice` on((`storagesystem_storagesystem_id_functionalci`.`id` = `storagesystem_storagesystem_id_physicaldevice`.`id`))) on((`logicalvolume_volume_id_logicalvolume`.`storagesystem_id` = `storagesystem_storagesystem_id_functionalci`.`id`))) on((`_lnkvirtualdevicetovolume`.`volume_id` = `logicalvolume_volume_id_logicalvolume`.`id`))) join (`functionalci` `virtualdevice_virtualdevice_id_functionalci` join `virtualdevice` `virtualdevice_virtualdevice_id_virtualdevice` on((`virtualdevice_virtualdevice_id_functionalci`.`id` = `virtualdevice_virtualdevice_id_virtualdevice`.`id`))) on((`_lnkvirtualdevicetovolume`.`virtualdevice_id` = `virtualdevice_virtualdevice_id_functionalci`.`id`))) where (coalesce((`storagesystem_storagesystem_id_functionalci`.`finalclass` = 'StorageSystem'),1) and coalesce((`virtualdevice_virtualdevice_id_functionalci`.`finalclass` in ('VirtualHost','Hypervisor','Farm','VirtualMachine','VirtualDevice')),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_location`
--

/*!50001 DROP VIEW IF EXISTS `view_location`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_location` AS select distinct `_location`.`id` AS `id`,`_location`.`name` AS `name`,`_location`.`status` AS `status`,`_location`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `org_name`,`_location`.`address` AS `address`,`_location`.`postal_code` AS `postal_code`,`_location`.`city` AS `city`,`_location`.`country` AS `country`,cast(concat(coalesce(`_location`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce((`_location`.`status` = 'inactive'),0) AS `obsolescence_flag`,`_location`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag` from (`location` `_location` join `organization` `organization_org_id_organization` on((`_location`.`org_id` = `organization_org_id_organization`.`id`))) where 1 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_logicalinterface`
--

/*!50001 DROP VIEW IF EXISTS `view_logicalinterface`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_logicalinterface` AS select distinct `_networkinterface`.`id` AS `id`,`_networkinterface`.`name` AS `name`,`_ipinterface`.`ipaddress` AS `ipaddress`,`_ipinterface`.`macaddress` AS `macaddress`,`_ipinterface`.`comment` AS `comment`,`_ipinterface`.`ipgateway` AS `ipgateway`,`_ipinterface`.`ipmask` AS `ipmask`,`_ipinterface`.`speed` AS `speed`,`_logicalinterface`.`virtualmachine_id` AS `virtualmachine_id`,`virtualmachine_virtualmachine_id_functionalci`.`name` AS `virtualmachine_name`,`_networkinterface`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_networkinterface`.`name`,''),coalesce(' ',''),coalesce(`virtualmachine_virtualmachine_id_functionalci`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce(coalesce((`virtualmachine_virtualmachine_id_virtualdevice`.`status` = 'obsolete'),0),0) AS `obsolescence_flag`,`_networkinterface`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`virtualmachine_virtualmachine_id_functionalci`.`name`,'')) as char charset utf8) AS `virtualmachine_id_friendlyname`,coalesce((`virtualmachine_virtualmachine_id_virtualdevice`.`status` = 'obsolete'),0) AS `virtualmachine_id_obsolescence_flag` from ((`networkinterface` `_networkinterface` join `ipinterface` `_ipinterface` on((`_networkinterface`.`id` = `_ipinterface`.`id`))) join (`logicalinterface` `_logicalinterface` join (`functionalci` `virtualmachine_virtualmachine_id_functionalci` join `virtualdevice` `virtualmachine_virtualmachine_id_virtualdevice` on((`virtualmachine_virtualmachine_id_functionalci`.`id` = `virtualmachine_virtualmachine_id_virtualdevice`.`id`))) on((`_logicalinterface`.`virtualmachine_id` = `virtualmachine_virtualmachine_id_functionalci`.`id`))) on((`_networkinterface`.`id` = `_logicalinterface`.`id`))) where (coalesce((`_networkinterface`.`finalclass` = 'LogicalInterface'),1) and coalesce((`virtualmachine_virtualmachine_id_functionalci`.`finalclass` = 'VirtualMachine'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_logicalvolume`
--

/*!50001 DROP VIEW IF EXISTS `view_logicalvolume`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_logicalvolume` AS select distinct `_logicalvolume`.`id` AS `id`,`_logicalvolume`.`name` AS `name`,`_logicalvolume`.`lun_id` AS `lun_id`,`_logicalvolume`.`description` AS `description`,`_logicalvolume`.`raid_level` AS `raid_level`,`_logicalvolume`.`size` AS `size`,`_logicalvolume`.`storagesystem_id` AS `storagesystem_id`,`storagesystem_storagesystem_id_functionalci`.`name` AS `storagesystem_name`,cast(concat(coalesce(`storagesystem_storagesystem_id_functionalci`.`name`,''),coalesce(' ',''),coalesce(`_logicalvolume`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce(coalesce((`storagesystem_storagesystem_id_physicaldevice`.`status` = 'obsolete'),0),0) AS `obsolescence_flag`,`_logicalvolume`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`storagesystem_storagesystem_id_functionalci`.`name`,'')) as char charset utf8) AS `storagesystem_id_friendlyname`,coalesce((`storagesystem_storagesystem_id_physicaldevice`.`status` = 'obsolete'),0) AS `storagesystem_id_obsolescence_flag` from (`logicalvolume` `_logicalvolume` join (`functionalci` `storagesystem_storagesystem_id_functionalci` join `physicaldevice` `storagesystem_storagesystem_id_physicaldevice` on((`storagesystem_storagesystem_id_functionalci`.`id` = `storagesystem_storagesystem_id_physicaldevice`.`id`))) on((`_logicalvolume`.`storagesystem_id` = `storagesystem_storagesystem_id_functionalci`.`id`))) where coalesce((`storagesystem_storagesystem_id_functionalci`.`finalclass` = 'StorageSystem'),1) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_middleware`
--

/*!50001 DROP VIEW IF EXISTS `view_middleware`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_middleware` AS select distinct `_functionalci`.`id` AS `id`,`_functionalci`.`name` AS `name`,`_functionalci`.`description` AS `description`,`_functionalci`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `organization_name`,`_functionalci`.`business_criticity` AS `business_criticity`,`_functionalci`.`move2production` AS `move2production`,`_softwareinstance`.`functionalci_id` AS `system_id`,`functionalci_system_id_functionalci`.`name` AS `system_name`,`_softwareinstance`.`software_id` AS `software_id`,`software_software_id_software`.`name` AS `software_name`,`_softwareinstance`.`softwarelicence_id` AS `softwarelicence_id`,`softwarelicence_softwarelicence_id_licence`.`name` AS `softwarelicence_name`,`_softwareinstance`.`path` AS `path`,`_softwareinstance`.`status` AS `status`,`_functionalci`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_functionalci`.`name`,''),coalesce(' ',''),coalesce(`functionalci_system_id_functionalci`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce((`_softwareinstance`.`status` = 'inactive'),0) AS `obsolescence_flag`,`_functionalci`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,if((`functionalci_system_id_functionalci`.`finalclass` in ('Middleware','DBServer','WebServer','PCSoftware','OtherSoftware')),cast(concat(coalesce(`functionalci_system_id_functionalci`.`name`,''),coalesce(' ',''),coalesce(`functionalci_system_id1_functionalci`.`name`,'')) as char charset utf8),cast(concat(coalesce(`functionalci_system_id_functionalci`.`name`,'')) as char charset utf8)) AS `system_id_friendlyname`,`functionalci_system_id_functionalci`.`finalclass` AS `system_id_finalclass_recall`,if((`functionalci_system_id_functionalci`.`finalclass` = 'FunctionalCI'),coalesce(0,0),if((`functionalci_system_id_functionalci`.`finalclass` in ('Hypervisor','Farm','VirtualMachine')),coalesce((`functionalci_system_id_fn_virtualdevice_virtualdevice`.`status` = 'obsolete'),0),if((`functionalci_system_id_functionalci`.`finalclass` = 'WebApplication'),coalesce(coalesce((`webserver_webserver_id_softwareinstance`.`status` = 'inactive'),0),0),if((`functionalci_system_id_functionalci`.`finalclass` = 'DatabaseSchema'),coalesce(coalesce((`dbserver_dbserver_id_softwareinstance`.`status` = 'inactive'),0),0),if((`functionalci_system_id_functionalci`.`finalclass` = 'MiddlewareInstance'),coalesce(coalesce((`middleware_middleware_id_softwareinstance`.`status` = 'inactive'),0),0),if((`functionalci_system_id_functionalci`.`finalclass` in ('Middleware','DBServer','WebServer','PCSoftware','OtherSoftware')),coalesce((`functionalci_system_id_fn_softwareinstance_softwareinstance`.`status` = 'inactive'),0),if((`functionalci_system_id_functionalci`.`finalclass` = 'BusinessProcess'),coalesce((`functionalci_system_id_fn_businessprocess_businessprocess`.`status` = 'inactive'),0),if((`functionalci_system_id_functionalci`.`finalclass` = 'ApplicationSolution'),coalesce((`functionalci_system_id_fn_applicationsolution_applicationsolution`.`status` = 'inactive'),0),coalesce((`functionalci_system_id_fn_physicaldevice_physicaldevice`.`status` = 'obsolete'),0))))))))) AS `system_id_obsolescence_flag`,cast(concat(coalesce(`software_software_id_software`.`name`,''),coalesce(' ',''),coalesce(`software_software_id_software`.`version`,'')) as char charset utf8) AS `software_id_friendlyname`,cast(concat(coalesce(`softwarelicence_softwarelicence_id_licence`.`name`,'')) as char charset utf8) AS `softwarelicence_id_friendlyname`,coalesce(((`softwarelicence_softwarelicence_id_licence`.`perpetual` = 'no') and (isnull(`softwarelicence_softwarelicence_id_licence`.`end_date`) = 0) and (`softwarelicence_softwarelicence_id_licence`.`end_date` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00'))),0) AS `softwarelicence_id_obsolescence_flag` from ((`functionalci` `_functionalci` join `organization` `organization_org_id_organization` on((`_functionalci`.`org_id` = `organization_org_id_organization`.`id`))) join (((`softwareinstance` `_softwareinstance` join ((((((((`functionalci` `functionalci_system_id_functionalci` left join (`softwareinstance` `functionalci_system_id_fn_softwareinstance_softwareinstance` join `functionalci` `functionalci_system_id1_functionalci` on((`functionalci_system_id_fn_softwareinstance_softwareinstance`.`functionalci_id` = `functionalci_system_id1_functionalci`.`id`))) on((`functionalci_system_id_functionalci`.`id` = `functionalci_system_id_fn_softwareinstance_softwareinstance`.`id`))) left join `virtualdevice` `functionalci_system_id_fn_virtualdevice_virtualdevice` on((`functionalci_system_id_functionalci`.`id` = `functionalci_system_id_fn_virtualdevice_virtualdevice`.`id`))) left join (`webapplication` `functionalci_system_id_fn_webapplication_webapplication` join (`functionalci` `webserver_webserver_id_functionalci` join `softwareinstance` `webserver_webserver_id_softwareinstance` on((`webserver_webserver_id_functionalci`.`id` = `webserver_webserver_id_softwareinstance`.`id`))) on((`functionalci_system_id_fn_webapplication_webapplication`.`webserver_id` = `webserver_webserver_id_functionalci`.`id`))) on((`functionalci_system_id_functionalci`.`id` = `functionalci_system_id_fn_webapplication_webapplication`.`id`))) left join (`databaseschema` `functionalci_system_id_fn_databaseschema_databaseschema` join (`functionalci` `dbserver_dbserver_id_functionalci` join `softwareinstance` `dbserver_dbserver_id_softwareinstance` on((`dbserver_dbserver_id_functionalci`.`id` = `dbserver_dbserver_id_softwareinstance`.`id`))) on((`functionalci_system_id_fn_databaseschema_databaseschema`.`dbserver_id` = `dbserver_dbserver_id_functionalci`.`id`))) on((`functionalci_system_id_functionalci`.`id` = `functionalci_system_id_fn_databaseschema_databaseschema`.`id`))) left join (`middlewareinstance` `functionalci_system_id_fn_middlewareinstance_middlewareinstance` join (`functionalci` `middleware_middleware_id_functionalci` join `softwareinstance` `middleware_middleware_id_softwareinstance` on((`middleware_middleware_id_functionalci`.`id` = `middleware_middleware_id_softwareinstance`.`id`))) on((`functionalci_system_id_fn_middlewareinstance_middlewareinstance`.`middleware_id` = `middleware_middleware_id_functionalci`.`id`))) on((`functionalci_system_id_functionalci`.`id` = `functionalci_system_id_fn_middlewareinstance_middlewareinstance`.`id`))) left join `businessprocess` `functionalci_system_id_fn_businessprocess_businessprocess` on((`functionalci_system_id_functionalci`.`id` = `functionalci_system_id_fn_businessprocess_businessprocess`.`id`))) left join `applicationsolution` `functionalci_system_id_fn_applicationsolution_applicationsolution` on((`functionalci_system_id_functionalci`.`id` = `functionalci_system_id_fn_applicationsolution_applicationsolution`.`id`))) left join `physicaldevice` `functionalci_system_id_fn_physicaldevice_physicaldevice` on((`functionalci_system_id_functionalci`.`id` = `functionalci_system_id_fn_physicaldevice_physicaldevice`.`id`))) on((`_softwareinstance`.`functionalci_id` = `functionalci_system_id_functionalci`.`id`))) left join `software` `software_software_id_software` on((`_softwareinstance`.`software_id` = `software_software_id_software`.`id`))) left join `licence` `softwarelicence_softwarelicence_id_licence` on((`_softwareinstance`.`softwarelicence_id` = `softwarelicence_softwarelicence_id_licence`.`id`))) on((`_functionalci`.`id` = `_softwareinstance`.`id`))) where (coalesce((`_functionalci`.`finalclass` = 'Middleware'),1) and coalesce((`webserver_webserver_id_functionalci`.`finalclass` = 'WebServer'),1) and coalesce((`dbserver_dbserver_id_functionalci`.`finalclass` = 'DBServer'),1) and coalesce((`middleware_middleware_id_functionalci`.`finalclass` = 'Middleware'),1) and coalesce((`softwarelicence_softwarelicence_id_licence`.`finalclass` = 'SoftwareLicence'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_middlewareinstance`
--

/*!50001 DROP VIEW IF EXISTS `view_middlewareinstance`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_middlewareinstance` AS select distinct `_functionalci`.`id` AS `id`,`_functionalci`.`name` AS `name`,`_functionalci`.`description` AS `description`,`_functionalci`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `organization_name`,`_functionalci`.`business_criticity` AS `business_criticity`,`_functionalci`.`move2production` AS `move2production`,`_middlewareinstance`.`middleware_id` AS `middleware_id`,`middleware_middleware_id_functionalci`.`name` AS `middleware_name`,`_functionalci`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_functionalci`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce(coalesce((`middleware_middleware_id_softwareinstance`.`status` = 'inactive'),0),0) AS `obsolescence_flag`,`_functionalci`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,cast(concat(coalesce(`middleware_middleware_id_functionalci`.`name`,''),coalesce(' ',''),coalesce(`functionalci_system_id_functionalci`.`name`,'')) as char charset utf8) AS `middleware_id_friendlyname`,coalesce((`middleware_middleware_id_softwareinstance`.`status` = 'inactive'),0) AS `middleware_id_obsolescence_flag` from ((`functionalci` `_functionalci` join `organization` `organization_org_id_organization` on((`_functionalci`.`org_id` = `organization_org_id_organization`.`id`))) join (`middlewareinstance` `_middlewareinstance` join (`functionalci` `middleware_middleware_id_functionalci` join (`softwareinstance` `middleware_middleware_id_softwareinstance` join `functionalci` `functionalci_system_id_functionalci` on((`middleware_middleware_id_softwareinstance`.`functionalci_id` = `functionalci_system_id_functionalci`.`id`))) on((`middleware_middleware_id_functionalci`.`id` = `middleware_middleware_id_softwareinstance`.`id`))) on((`_middlewareinstance`.`middleware_id` = `middleware_middleware_id_functionalci`.`id`))) on((`_functionalci`.`id` = `_middlewareinstance`.`id`))) where (coalesce((`_functionalci`.`finalclass` = 'MiddlewareInstance'),1) and coalesce((`middleware_middleware_id_functionalci`.`finalclass` = 'Middleware'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_mobilephone`
--

/*!50001 DROP VIEW IF EXISTS `view_mobilephone`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_mobilephone` AS select distinct `_functionalci`.`id` AS `id`,`_functionalci`.`name` AS `name`,`_functionalci`.`description` AS `description`,`_functionalci`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `organization_name`,`_functionalci`.`business_criticity` AS `business_criticity`,`_functionalci`.`move2production` AS `move2production`,`_physicaldevice`.`serialnumber` AS `serialnumber`,`_physicaldevice`.`location_id` AS `location_id`,`location_location_id_location`.`name` AS `location_name`,`_physicaldevice`.`status` AS `status`,`_physicaldevice`.`brand_id` AS `brand_id`,`brand_brand_id_typology`.`name` AS `brand_name`,`_physicaldevice`.`model_id` AS `model_id`,`model_model_id_typology`.`name` AS `model_name`,`_physicaldevice`.`asset_number` AS `asset_number`,`_physicaldevice`.`purchase_date` AS `purchase_date`,`_physicaldevice`.`end_of_warranty` AS `end_of_warranty`,`_telephonyci`.`phonenumber` AS `phonenumber`,`_mobilephone`.`imei` AS `imei`,`_mobilephone`.`hw_pin` AS `hw_pin`,`_functionalci`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_functionalci`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce((`_physicaldevice`.`status` = 'obsolete'),0) AS `obsolescence_flag`,`_functionalci`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,cast(concat(coalesce(`location_location_id_location`.`name`,'')) as char charset utf8) AS `location_id_friendlyname`,coalesce((`location_location_id_location`.`status` = 'inactive'),0) AS `location_id_obsolescence_flag`,cast(concat(coalesce(`brand_brand_id_typology`.`name`,'')) as char charset utf8) AS `brand_id_friendlyname`,cast(concat(coalesce(`model_model_id_typology`.`name`,'')) as char charset utf8) AS `model_id_friendlyname` from ((((`functionalci` `_functionalci` join `organization` `organization_org_id_organization` on((`_functionalci`.`org_id` = `organization_org_id_organization`.`id`))) join (((`physicaldevice` `_physicaldevice` left join `location` `location_location_id_location` on((`_physicaldevice`.`location_id` = `location_location_id_location`.`id`))) left join `typology` `brand_brand_id_typology` on((`_physicaldevice`.`brand_id` = `brand_brand_id_typology`.`id`))) left join `typology` `model_model_id_typology` on((`_physicaldevice`.`model_id` = `model_model_id_typology`.`id`))) on((`_functionalci`.`id` = `_physicaldevice`.`id`))) join `telephonyci` `_telephonyci` on((`_functionalci`.`id` = `_telephonyci`.`id`))) join `mobilephone` `_mobilephone` on((`_functionalci`.`id` = `_mobilephone`.`id`))) where (coalesce((`_functionalci`.`finalclass` = 'MobilePhone'),1) and coalesce((`brand_brand_id_typology`.`finalclass` = 'Brand'),1) and coalesce((`model_model_id_typology`.`finalclass` = 'Model'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_model`
--

/*!50001 DROP VIEW IF EXISTS `view_model`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_model` AS select distinct `_typology`.`id` AS `id`,`_typology`.`name` AS `name`,`_model`.`brand_id` AS `brand_id`,`brand_brand_id_typology`.`name` AS `brand_name`,`_model`.`type` AS `type`,`_typology`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_typology`.`name`,'')) as char charset utf8) AS `friendlyname`,cast(concat(coalesce(`brand_brand_id_typology`.`name`,'')) as char charset utf8) AS `brand_id_friendlyname` from (`typology` `_typology` join (`model` `_model` join `typology` `brand_brand_id_typology` on((`_model`.`brand_id` = `brand_brand_id_typology`.`id`))) on((`_typology`.`id` = `_model`.`id`))) where (coalesce((`_typology`.`finalclass` = 'Model'),1) and coalesce((`brand_brand_id_typology`.`finalclass` = 'Brand'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_nas`
--

/*!50001 DROP VIEW IF EXISTS `view_nas`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_nas` AS select distinct `_functionalci`.`id` AS `id`,`_functionalci`.`name` AS `name`,`_functionalci`.`description` AS `description`,`_functionalci`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `organization_name`,`_functionalci`.`business_criticity` AS `business_criticity`,`_functionalci`.`move2production` AS `move2production`,`_physicaldevice`.`serialnumber` AS `serialnumber`,`_physicaldevice`.`location_id` AS `location_id`,`location_location_id_location`.`name` AS `location_name`,`_physicaldevice`.`status` AS `status`,`_physicaldevice`.`brand_id` AS `brand_id`,`brand_brand_id_typology`.`name` AS `brand_name`,`_physicaldevice`.`model_id` AS `model_id`,`model_model_id_typology`.`name` AS `model_name`,`_physicaldevice`.`asset_number` AS `asset_number`,`_physicaldevice`.`purchase_date` AS `purchase_date`,`_physicaldevice`.`end_of_warranty` AS `end_of_warranty`,`_datacenterdevice`.`rack_id` AS `rack_id`,`rack_rack_id_functionalci`.`name` AS `rack_name`,`_datacenterdevice`.`enclosure_id` AS `enclosure_id`,`enclosure_enclosure_id_functionalci`.`name` AS `enclosure_name`,`_datacenterdevice`.`nb_u` AS `nb_u`,`_datacenterdevice`.`managementip` AS `managementip`,`_datacenterdevice`.`powera_id` AS `powerA_id`,`powerconnection_powera_id_functionalci`.`name` AS `powerA_name`,`_datacenterdevice`.`powerB_id` AS `powerB_id`,`powerconnection_powerb_id_functionalci`.`name` AS `powerB_name`,`_datacenterdevice`.`redundancy` AS `redundancy`,`_functionalci`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_functionalci`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce((`_physicaldevice`.`status` = 'obsolete'),0) AS `obsolescence_flag`,`_functionalci`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,cast(concat(coalesce(`location_location_id_location`.`name`,'')) as char charset utf8) AS `location_id_friendlyname`,coalesce((`location_location_id_location`.`status` = 'inactive'),0) AS `location_id_obsolescence_flag`,cast(concat(coalesce(`brand_brand_id_typology`.`name`,'')) as char charset utf8) AS `brand_id_friendlyname`,cast(concat(coalesce(`model_model_id_typology`.`name`,'')) as char charset utf8) AS `model_id_friendlyname`,cast(concat(coalesce(`rack_rack_id_functionalci`.`name`,'')) as char charset utf8) AS `rack_id_friendlyname`,coalesce((`rack_rack_id_physicaldevice`.`status` = 'obsolete'),0) AS `rack_id_obsolescence_flag`,cast(concat(coalesce(`enclosure_enclosure_id_functionalci`.`name`,'')) as char charset utf8) AS `enclosure_id_friendlyname`,coalesce((`enclosure_enclosure_id_physicaldevice`.`status` = 'obsolete'),0) AS `enclosure_id_obsolescence_flag`,cast(concat(coalesce(`powerconnection_powera_id_functionalci`.`name`,'')) as char charset utf8) AS `powerA_id_friendlyname`,`powerconnection_powera_id_functionalci`.`finalclass` AS `powerA_id_finalclass_recall`,coalesce((`powerconnection_powera_id_physicaldevice`.`status` = 'obsolete'),0) AS `powerA_id_obsolescence_flag`,cast(concat(coalesce(`powerconnection_powerb_id_functionalci`.`name`,'')) as char charset utf8) AS `powerB_id_friendlyname`,`powerconnection_powerb_id_functionalci`.`finalclass` AS `powerB_id_finalclass_recall`,coalesce((`powerconnection_powerb_id_physicaldevice`.`status` = 'obsolete'),0) AS `powerB_id_obsolescence_flag` from (((`functionalci` `_functionalci` join `organization` `organization_org_id_organization` on((`_functionalci`.`org_id` = `organization_org_id_organization`.`id`))) join (((`physicaldevice` `_physicaldevice` left join `location` `location_location_id_location` on((`_physicaldevice`.`location_id` = `location_location_id_location`.`id`))) left join `typology` `brand_brand_id_typology` on((`_physicaldevice`.`brand_id` = `brand_brand_id_typology`.`id`))) left join `typology` `model_model_id_typology` on((`_physicaldevice`.`model_id` = `model_model_id_typology`.`id`))) on((`_functionalci`.`id` = `_physicaldevice`.`id`))) join ((((`datacenterdevice` `_datacenterdevice` left join (`functionalci` `rack_rack_id_functionalci` join `physicaldevice` `rack_rack_id_physicaldevice` on((`rack_rack_id_functionalci`.`id` = `rack_rack_id_physicaldevice`.`id`))) on((`_datacenterdevice`.`rack_id` = `rack_rack_id_functionalci`.`id`))) left join (`functionalci` `enclosure_enclosure_id_functionalci` join `physicaldevice` `enclosure_enclosure_id_physicaldevice` on((`enclosure_enclosure_id_functionalci`.`id` = `enclosure_enclosure_id_physicaldevice`.`id`))) on((`_datacenterdevice`.`enclosure_id` = `enclosure_enclosure_id_functionalci`.`id`))) left join (`functionalci` `powerconnection_powera_id_functionalci` join `physicaldevice` `powerconnection_powera_id_physicaldevice` on((`powerconnection_powera_id_functionalci`.`id` = `powerconnection_powera_id_physicaldevice`.`id`))) on((`_datacenterdevice`.`powera_id` = `powerconnection_powera_id_functionalci`.`id`))) left join (`functionalci` `powerconnection_powerb_id_functionalci` join `physicaldevice` `powerconnection_powerb_id_physicaldevice` on((`powerconnection_powerb_id_functionalci`.`id` = `powerconnection_powerb_id_physicaldevice`.`id`))) on((`_datacenterdevice`.`powerB_id` = `powerconnection_powerb_id_functionalci`.`id`))) on((`_functionalci`.`id` = `_datacenterdevice`.`id`))) where (coalesce((`_functionalci`.`finalclass` = 'NAS'),1) and coalesce((`brand_brand_id_typology`.`finalclass` = 'Brand'),1) and coalesce((`model_model_id_typology`.`finalclass` = 'Model'),1) and coalesce((`rack_rack_id_functionalci`.`finalclass` = 'Rack'),1) and coalesce((`enclosure_enclosure_id_functionalci`.`finalclass` = 'Enclosure'),1) and coalesce((`powerconnection_powera_id_functionalci`.`finalclass` in ('PowerSource','PDU','PowerConnection')),1) and coalesce((`powerconnection_powerb_id_functionalci`.`finalclass` in ('PowerSource','PDU','PowerConnection')),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_nasfilesystem`
--

/*!50001 DROP VIEW IF EXISTS `view_nasfilesystem`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_nasfilesystem` AS select distinct `_nasfilesystem`.`id` AS `id`,`_nasfilesystem`.`name` AS `name`,`_nasfilesystem`.`description` AS `description`,`_nasfilesystem`.`raid_level` AS `raid_level`,`_nasfilesystem`.`size` AS `size`,`_nasfilesystem`.`nas_id` AS `nas_id`,`nas_nas_id_functionalci`.`name` AS `nas_name`,cast(concat(coalesce(`_nasfilesystem`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce(coalesce((`nas_nas_id_physicaldevice`.`status` = 'obsolete'),0),0) AS `obsolescence_flag`,`_nasfilesystem`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`nas_nas_id_functionalci`.`name`,'')) as char charset utf8) AS `nas_id_friendlyname`,coalesce((`nas_nas_id_physicaldevice`.`status` = 'obsolete'),0) AS `nas_id_obsolescence_flag` from (`nasfilesystem` `_nasfilesystem` join (`functionalci` `nas_nas_id_functionalci` join `physicaldevice` `nas_nas_id_physicaldevice` on((`nas_nas_id_functionalci`.`id` = `nas_nas_id_physicaldevice`.`id`))) on((`_nasfilesystem`.`nas_id` = `nas_nas_id_functionalci`.`id`))) where coalesce((`nas_nas_id_functionalci`.`finalclass` = 'NAS'),1) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_networkdevice`
--

/*!50001 DROP VIEW IF EXISTS `view_networkdevice`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_networkdevice` AS select distinct `_functionalci`.`id` AS `id`,`_functionalci`.`name` AS `name`,`_functionalci`.`description` AS `description`,`_functionalci`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `organization_name`,`_functionalci`.`business_criticity` AS `business_criticity`,`_functionalci`.`move2production` AS `move2production`,`_physicaldevice`.`serialnumber` AS `serialnumber`,`_physicaldevice`.`location_id` AS `location_id`,`location_location_id_location`.`name` AS `location_name`,`_physicaldevice`.`status` AS `status`,`_physicaldevice`.`brand_id` AS `brand_id`,`brand_brand_id_typology`.`name` AS `brand_name`,`_physicaldevice`.`model_id` AS `model_id`,`model_model_id_typology`.`name` AS `model_name`,`_physicaldevice`.`asset_number` AS `asset_number`,`_physicaldevice`.`purchase_date` AS `purchase_date`,`_physicaldevice`.`end_of_warranty` AS `end_of_warranty`,`_datacenterdevice`.`rack_id` AS `rack_id`,`rack_rack_id_functionalci`.`name` AS `rack_name`,`_datacenterdevice`.`enclosure_id` AS `enclosure_id`,`enclosure_enclosure_id_functionalci`.`name` AS `enclosure_name`,`_datacenterdevice`.`nb_u` AS `nb_u`,`_datacenterdevice`.`managementip` AS `managementip`,`_datacenterdevice`.`powera_id` AS `powerA_id`,`powerconnection_powera_id_functionalci`.`name` AS `powerA_name`,`_datacenterdevice`.`powerB_id` AS `powerB_id`,`powerconnection_powerb_id_functionalci`.`name` AS `powerB_name`,`_datacenterdevice`.`redundancy` AS `redundancy`,`_networkdevice`.`networkdevicetype_id` AS `networkdevicetype_id`,`networkdevicetype_networkdevicetype_id_typology`.`name` AS `networkdevicetype_name`,`_networkdevice`.`iosversion_id` AS `iosversion_id`,`iosversion_iosversion_id_typology`.`name` AS `iosversion_name`,`_networkdevice`.`ram` AS `ram`,`_functionalci`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_functionalci`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce((`_physicaldevice`.`status` = 'obsolete'),0) AS `obsolescence_flag`,`_functionalci`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,cast(concat(coalesce(`location_location_id_location`.`name`,'')) as char charset utf8) AS `location_id_friendlyname`,coalesce((`location_location_id_location`.`status` = 'inactive'),0) AS `location_id_obsolescence_flag`,cast(concat(coalesce(`brand_brand_id_typology`.`name`,'')) as char charset utf8) AS `brand_id_friendlyname`,cast(concat(coalesce(`model_model_id_typology`.`name`,'')) as char charset utf8) AS `model_id_friendlyname`,cast(concat(coalesce(`rack_rack_id_functionalci`.`name`,'')) as char charset utf8) AS `rack_id_friendlyname`,coalesce((`rack_rack_id_physicaldevice`.`status` = 'obsolete'),0) AS `rack_id_obsolescence_flag`,cast(concat(coalesce(`enclosure_enclosure_id_functionalci`.`name`,'')) as char charset utf8) AS `enclosure_id_friendlyname`,coalesce((`enclosure_enclosure_id_physicaldevice`.`status` = 'obsolete'),0) AS `enclosure_id_obsolescence_flag`,cast(concat(coalesce(`powerconnection_powera_id_functionalci`.`name`,'')) as char charset utf8) AS `powerA_id_friendlyname`,`powerconnection_powera_id_functionalci`.`finalclass` AS `powerA_id_finalclass_recall`,coalesce((`powerconnection_powera_id_physicaldevice`.`status` = 'obsolete'),0) AS `powerA_id_obsolescence_flag`,cast(concat(coalesce(`powerconnection_powerb_id_functionalci`.`name`,'')) as char charset utf8) AS `powerB_id_friendlyname`,`powerconnection_powerb_id_functionalci`.`finalclass` AS `powerB_id_finalclass_recall`,coalesce((`powerconnection_powerb_id_physicaldevice`.`status` = 'obsolete'),0) AS `powerB_id_obsolescence_flag`,cast(concat(coalesce(`networkdevicetype_networkdevicetype_id_typology`.`name`,'')) as char charset utf8) AS `networkdevicetype_id_friendlyname`,cast(concat(coalesce(`brand_brand_id1_typology`.`name`,''),coalesce(' ',''),coalesce(`iosversion_iosversion_id_typology`.`name`,'')) as char charset utf8) AS `iosversion_id_friendlyname` from ((((`functionalci` `_functionalci` join `organization` `organization_org_id_organization` on((`_functionalci`.`org_id` = `organization_org_id_organization`.`id`))) join (((`physicaldevice` `_physicaldevice` left join `location` `location_location_id_location` on((`_physicaldevice`.`location_id` = `location_location_id_location`.`id`))) left join `typology` `brand_brand_id_typology` on((`_physicaldevice`.`brand_id` = `brand_brand_id_typology`.`id`))) left join `typology` `model_model_id_typology` on((`_physicaldevice`.`model_id` = `model_model_id_typology`.`id`))) on((`_functionalci`.`id` = `_physicaldevice`.`id`))) join ((((`datacenterdevice` `_datacenterdevice` left join (`functionalci` `rack_rack_id_functionalci` join `physicaldevice` `rack_rack_id_physicaldevice` on((`rack_rack_id_functionalci`.`id` = `rack_rack_id_physicaldevice`.`id`))) on((`_datacenterdevice`.`rack_id` = `rack_rack_id_functionalci`.`id`))) left join (`functionalci` `enclosure_enclosure_id_functionalci` join `physicaldevice` `enclosure_enclosure_id_physicaldevice` on((`enclosure_enclosure_id_functionalci`.`id` = `enclosure_enclosure_id_physicaldevice`.`id`))) on((`_datacenterdevice`.`enclosure_id` = `enclosure_enclosure_id_functionalci`.`id`))) left join (`functionalci` `powerconnection_powera_id_functionalci` join `physicaldevice` `powerconnection_powera_id_physicaldevice` on((`powerconnection_powera_id_functionalci`.`id` = `powerconnection_powera_id_physicaldevice`.`id`))) on((`_datacenterdevice`.`powera_id` = `powerconnection_powera_id_functionalci`.`id`))) left join (`functionalci` `powerconnection_powerb_id_functionalci` join `physicaldevice` `powerconnection_powerb_id_physicaldevice` on((`powerconnection_powerb_id_functionalci`.`id` = `powerconnection_powerb_id_physicaldevice`.`id`))) on((`_datacenterdevice`.`powerB_id` = `powerconnection_powerb_id_functionalci`.`id`))) on((`_functionalci`.`id` = `_datacenterdevice`.`id`))) join ((`networkdevice` `_networkdevice` join `typology` `networkdevicetype_networkdevicetype_id_typology` on((`_networkdevice`.`networkdevicetype_id` = `networkdevicetype_networkdevicetype_id_typology`.`id`))) left join (`typology` `iosversion_iosversion_id_typology` join (`iosversion` `iosversion_iosversion_id_iosversion` join `typology` `brand_brand_id1_typology` on((`iosversion_iosversion_id_iosversion`.`brand_id` = `brand_brand_id1_typology`.`id`))) on((`iosversion_iosversion_id_typology`.`id` = `iosversion_iosversion_id_iosversion`.`id`))) on((`_networkdevice`.`iosversion_id` = `iosversion_iosversion_id_typology`.`id`))) on((`_functionalci`.`id` = `_networkdevice`.`id`))) where (coalesce((`_functionalci`.`finalclass` = 'NetworkDevice'),1) and coalesce((`brand_brand_id_typology`.`finalclass` = 'Brand'),1) and coalesce((`model_model_id_typology`.`finalclass` = 'Model'),1) and coalesce((`rack_rack_id_functionalci`.`finalclass` = 'Rack'),1) and coalesce((`enclosure_enclosure_id_functionalci`.`finalclass` = 'Enclosure'),1) and coalesce((`powerconnection_powera_id_functionalci`.`finalclass` in ('PowerSource','PDU','PowerConnection')),1) and coalesce((`powerconnection_powerb_id_functionalci`.`finalclass` in ('PowerSource','PDU','PowerConnection')),1) and coalesce((`networkdevicetype_networkdevicetype_id_typology`.`finalclass` = 'NetworkDeviceType'),1) and coalesce((`iosversion_iosversion_id_typology`.`finalclass` = 'IOSVersion'),1) and coalesce((`brand_brand_id1_typology`.`finalclass` = 'Brand'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_networkdevicetype`
--

/*!50001 DROP VIEW IF EXISTS `view_networkdevicetype`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_networkdevicetype` AS select distinct `_typology`.`id` AS `id`,`_typology`.`name` AS `name`,`_typology`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_typology`.`name`,'')) as char charset utf8) AS `friendlyname` from `typology` `_typology` where coalesce((`_typology`.`finalclass` = 'NetworkDeviceType'),1) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_networkinterface`
--

/*!50001 DROP VIEW IF EXISTS `view_networkinterface`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_networkinterface` AS select distinct `_networkinterface`.`id` AS `id`,`_networkinterface`.`name` AS `name`,`_networkinterface`.`finalclass` AS `finalclass`,if((`_networkinterface`.`finalclass` = 'NetworkInterface'),cast(concat(coalesce(`_networkinterface`.`name`,'')) as char charset utf8),if((`_networkinterface`.`finalclass` = 'LogicalInterface'),cast(concat(coalesce(`_networkinterface`.`name`,''),coalesce(' ',''),coalesce(`virtualmachine_virtualmachine_id_functionalci`.`name`,'')) as char charset utf8),if((`_networkinterface`.`finalclass` = 'FiberChannelInterface'),cast(concat(coalesce(`_networkinterface`.`name`,''),coalesce(' ',''),coalesce(`datacenterdevice_datacenterdevice_id_functionalci`.`name`,'')) as char charset utf8),cast(concat(coalesce(`_networkinterface`.`name`,''),coalesce(' ',''),coalesce(`connectableci_connectableci_id_functionalci`.`name`,'')) as char charset utf8)))) AS `friendlyname`,if((`_networkinterface`.`finalclass` = 'NetworkInterface'),coalesce(0,0),if((`_networkinterface`.`finalclass` = 'LogicalInterface'),coalesce(coalesce((`virtualmachine_virtualmachine_id_virtualdevice`.`status` = 'obsolete'),0),0),if((`_networkinterface`.`finalclass` = 'FiberChannelInterface'),coalesce(coalesce((`datacenterdevice_datacenterdevice_id_physicaldevice`.`status` = 'obsolete'),0),0),coalesce(coalesce((`connectableci_connectableci_id_physicaldevice`.`status` = 'obsolete'),0),0)))) AS `obsolescence_flag`,`_networkinterface`.`obsolescence_date` AS `obsolescence_date` from (((`networkinterface` `_networkinterface` left join (`logicalinterface` `_fn_logicalinterface_logicalinterface` join (`functionalci` `virtualmachine_virtualmachine_id_functionalci` join `virtualdevice` `virtualmachine_virtualmachine_id_virtualdevice` on((`virtualmachine_virtualmachine_id_functionalci`.`id` = `virtualmachine_virtualmachine_id_virtualdevice`.`id`))) on((`_fn_logicalinterface_logicalinterface`.`virtualmachine_id` = `virtualmachine_virtualmachine_id_functionalci`.`id`))) on((`_networkinterface`.`id` = `_fn_logicalinterface_logicalinterface`.`id`))) left join (`fiberchannelinterface` `_fn_fiberchannelinterface_fiberchannelinterface` join (`functionalci` `datacenterdevice_datacenterdevice_id_functionalci` join `physicaldevice` `datacenterdevice_datacenterdevice_id_physicaldevice` on((`datacenterdevice_datacenterdevice_id_functionalci`.`id` = `datacenterdevice_datacenterdevice_id_physicaldevice`.`id`))) on((`_fn_fiberchannelinterface_fiberchannelinterface`.`datacenterdevice_id` = `datacenterdevice_datacenterdevice_id_functionalci`.`id`))) on((`_networkinterface`.`id` = `_fn_fiberchannelinterface_fiberchannelinterface`.`id`))) left join (`physicalinterface` `_fn_physicalinterface_physicalinterface` join (`functionalci` `connectableci_connectableci_id_functionalci` join `physicaldevice` `connectableci_connectableci_id_physicaldevice` on((`connectableci_connectableci_id_functionalci`.`id` = `connectableci_connectableci_id_physicaldevice`.`id`))) on((`_fn_physicalinterface_physicalinterface`.`connectableci_id` = `connectableci_connectableci_id_functionalci`.`id`))) on((`_networkinterface`.`id` = `_fn_physicalinterface_physicalinterface`.`id`))) where (coalesce((`virtualmachine_virtualmachine_id_functionalci`.`finalclass` = 'VirtualMachine'),1) and coalesce((`datacenterdevice_datacenterdevice_id_functionalci`.`finalclass` in ('NetworkDevice','Server','StorageSystem','SANSwitch','TapeLibrary','NAS','DatacenterDevice')),1) and coalesce((`connectableci_connectableci_id_functionalci`.`finalclass` in ('DatacenterDevice','NetworkDevice','Server','PC','Printer','StorageSystem','SANSwitch','TapeLibrary','NAS','ConnectableCI')),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_normalchange`
--

/*!50001 DROP VIEW IF EXISTS `view_normalchange`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_normalchange` AS select distinct `_ticket`.`id` AS `id`,`_ticket`.`operational_status` AS `operational_status`,`_ticket`.`ref` AS `ref`,`_ticket`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `org_name`,`_ticket`.`caller_id` AS `caller_id`,`person_caller_id_contact`.`name` AS `caller_name`,`_ticket`.`team_id` AS `team_id`,`team_team_id_contact`.`email` AS `team_name`,`_ticket`.`agent_id` AS `agent_id`,`person_agent_id_contact`.`name` AS `agent_name`,`_ticket`.`title` AS `title`,`_ticket`.`description` AS `description`,`_ticket`.`description_format` AS `description_format`,`_ticket`.`start_date` AS `start_date`,`_ticket`.`end_date` AS `end_date`,`_ticket`.`last_update` AS `last_update`,`_ticket`.`close_date` AS `close_date`,`_ticket`.`private_log` AS `private_log`,`_ticket`.`private_log_index` AS `private_log_index`,`_change`.`status` AS `status`,`_change`.`reason` AS `reason`,`_change`.`requestor_id` AS `requestor_id`,`person_requestor_id_contact`.`email` AS `requestor_email`,`_change`.`creation_date` AS `creation_date`,`_change`.`impact` AS `impact`,`_change`.`supervisor_group_id` AS `supervisor_group_id`,`team_supervisor_group_id_contact`.`name` AS `supervisor_group_name`,`_change`.`supervisor_id` AS `supervisor_id`,`person_supervisor_id_contact`.`email` AS `supervisor_email`,`_change`.`manager_group_id` AS `manager_group_id`,`team_manager_group_id_contact`.`name` AS `manager_group_name`,`_change`.`manager_id` AS `manager_id`,`person_manager_id_contact`.`email` AS `manager_email`,`_change`.`outage` AS `outage`,`_change`.`fallback` AS `fallback`,`_change`.`parent_id` AS `parent_id`,`change_parent_id_ticket`.`ref` AS `parent_name`,`_change_approved`.`approval_date` AS `approval_date`,`_change_approved`.`approval_comment` AS `approval_comment`,`_change_normal`.`acceptance_date` AS `acceptance_date`,`_change_normal`.`acceptance_comment` AS `acceptance_comment`,`_ticket`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_ticket`.`ref`,'')) as char charset utf8) AS `friendlyname`,coalesce(((`_ticket`.`operational_status` = 'closed') and ((`_ticket`.`close_date` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00')) or (isnull(`_ticket`.`close_date`) and (`_ticket`.`last_update` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00'))))),0) AS `obsolescence_flag`,`_ticket`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,cast(concat(coalesce(`person_caller_id_person`.`first_name`,''),coalesce(' ',''),coalesce(`person_caller_id_contact`.`name`,'')) as char charset utf8) AS `caller_id_friendlyname`,coalesce((`person_caller_id_contact`.`status` = 'inactive'),0) AS `caller_id_obsolescence_flag`,cast(concat(coalesce(`team_team_id_contact`.`name`,'')) as char charset utf8) AS `team_id_friendlyname`,coalesce((`team_team_id_contact`.`status` = 'inactive'),0) AS `team_id_obsolescence_flag`,cast(concat(coalesce(`person_agent_id_person`.`first_name`,''),coalesce(' ',''),coalesce(`person_agent_id_contact`.`name`,'')) as char charset utf8) AS `agent_id_friendlyname`,coalesce((`person_agent_id_contact`.`status` = 'inactive'),0) AS `agent_id_obsolescence_flag`,cast(concat(coalesce(`person_requestor_id_person`.`first_name`,''),coalesce(' ',''),coalesce(`person_requestor_id_contact`.`name`,'')) as char charset utf8) AS `requestor_id_friendlyname`,coalesce((`person_requestor_id_contact`.`status` = 'inactive'),0) AS `requestor_id_obsolescence_flag`,cast(concat(coalesce(`team_supervisor_group_id_contact`.`name`,'')) as char charset utf8) AS `supervisor_group_id_friendlyname`,coalesce((`team_supervisor_group_id_contact`.`status` = 'inactive'),0) AS `supervisor_group_id_obsolescence_flag`,cast(concat(coalesce(`person_supervisor_id_person`.`first_name`,''),coalesce(' ',''),coalesce(`person_supervisor_id_contact`.`name`,'')) as char charset utf8) AS `supervisor_id_friendlyname`,coalesce((`person_supervisor_id_contact`.`status` = 'inactive'),0) AS `supervisor_id_obsolescence_flag`,cast(concat(coalesce(`team_manager_group_id_contact`.`name`,'')) as char charset utf8) AS `manager_group_id_friendlyname`,coalesce((`team_manager_group_id_contact`.`status` = 'inactive'),0) AS `manager_group_id_obsolescence_flag`,cast(concat(coalesce(`person_manager_id_person`.`first_name`,''),coalesce(' ',''),coalesce(`person_manager_id_contact`.`name`,'')) as char charset utf8) AS `manager_id_friendlyname`,coalesce((`person_manager_id_contact`.`status` = 'inactive'),0) AS `manager_id_obsolescence_flag`,cast(concat(coalesce(`change_parent_id_ticket`.`ref`,'')) as char charset utf8) AS `parent_id_friendlyname`,`change_parent_id_ticket`.`finalclass` AS `parent_id_finalclass_recall`,coalesce(((`change_parent_id_ticket`.`operational_status` = 'closed') and ((`change_parent_id_ticket`.`close_date` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00')) or (isnull(`change_parent_id_ticket`.`close_date`) and (`change_parent_id_ticket`.`last_update` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00'))))),0) AS `parent_id_obsolescence_flag` from (((((((`ticket` `_ticket` join `organization` `organization_org_id_organization` on((`_ticket`.`org_id` = `organization_org_id_organization`.`id`))) left join (`contact` `person_caller_id_contact` join `person` `person_caller_id_person` on((`person_caller_id_contact`.`id` = `person_caller_id_person`.`id`))) on((`_ticket`.`caller_id` = `person_caller_id_contact`.`id`))) left join `contact` `team_team_id_contact` on((`_ticket`.`team_id` = `team_team_id_contact`.`id`))) left join (`contact` `person_agent_id_contact` join `person` `person_agent_id_person` on((`person_agent_id_contact`.`id` = `person_agent_id_person`.`id`))) on((`_ticket`.`agent_id` = `person_agent_id_contact`.`id`))) join ((((((`change` `_change` left join (`contact` `person_requestor_id_contact` join `person` `person_requestor_id_person` on((`person_requestor_id_contact`.`id` = `person_requestor_id_person`.`id`))) on((`_change`.`requestor_id` = `person_requestor_id_contact`.`id`))) left join `contact` `team_supervisor_group_id_contact` on((`_change`.`supervisor_group_id` = `team_supervisor_group_id_contact`.`id`))) left join (`contact` `person_supervisor_id_contact` join `person` `person_supervisor_id_person` on((`person_supervisor_id_contact`.`id` = `person_supervisor_id_person`.`id`))) on((`_change`.`supervisor_id` = `person_supervisor_id_contact`.`id`))) left join `contact` `team_manager_group_id_contact` on((`_change`.`manager_group_id` = `team_manager_group_id_contact`.`id`))) left join (`contact` `person_manager_id_contact` join `person` `person_manager_id_person` on((`person_manager_id_contact`.`id` = `person_manager_id_person`.`id`))) on((`_change`.`manager_id` = `person_manager_id_contact`.`id`))) left join `ticket` `change_parent_id_ticket` on((`_change`.`parent_id` = `change_parent_id_ticket`.`id`))) on((`_ticket`.`id` = `_change`.`id`))) join `change_approved` `_change_approved` on((`_ticket`.`id` = `_change_approved`.`id`))) join `change_normal` `_change_normal` on((`_ticket`.`id` = `_change_normal`.`id`))) where (coalesce((`person_caller_id_contact`.`finalclass` = 'Person'),1) and coalesce((`team_team_id_contact`.`finalclass` = 'Team'),1) and coalesce((`person_agent_id_contact`.`finalclass` = 'Person'),1) and coalesce((`_ticket`.`finalclass` = 'NormalChange'),1) and coalesce((`person_requestor_id_contact`.`finalclass` = 'Person'),1) and coalesce((`team_supervisor_group_id_contact`.`finalclass` = 'Team'),1) and coalesce((`person_supervisor_id_contact`.`finalclass` = 'Person'),1) and coalesce((`team_manager_group_id_contact`.`finalclass` = 'Team'),1) and coalesce((`person_manager_id_contact`.`finalclass` = 'Person'),1) and coalesce((`change_parent_id_ticket`.`finalclass` in ('RoutineChange','ApprovedChange','NormalChange','EmergencyChange','Change')),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_organization`
--

/*!50001 DROP VIEW IF EXISTS `view_organization`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_organization` AS select distinct `_organization`.`id` AS `id`,`_organization`.`name` AS `name`,`_organization`.`code` AS `code`,`_organization`.`status` AS `status`,`_organization`.`parent_id` AS `parent_id`,`organization_parent_id_organization`.`name` AS `parent_name`,`_organization`.`deliverymodel_id` AS `deliverymodel_id`,`deliverymodel_deliverymodel_id_deliverymodel`.`name` AS `deliverymodel_name`,cast(concat(coalesce(`_organization`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce((`_organization`.`status` = 'inactive'),0) AS `obsolescence_flag`,`_organization`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_parent_id_organization`.`name`,'')) as char charset utf8) AS `parent_id_friendlyname`,coalesce((`organization_parent_id_organization`.`status` = 'inactive'),0) AS `parent_id_obsolescence_flag`,cast(concat(coalesce(`deliverymodel_deliverymodel_id_deliverymodel`.`name`,'')) as char charset utf8) AS `deliverymodel_id_friendlyname` from ((`organization` `_organization` left join `organization` `organization_parent_id_organization` on((`_organization`.`parent_id` = `organization_parent_id_organization`.`id`))) left join `deliverymodel` `deliverymodel_deliverymodel_id_deliverymodel` on((`_organization`.`deliverymodel_id` = `deliverymodel_deliverymodel_id_deliverymodel`.`id`))) where 1 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_osfamily`
--

/*!50001 DROP VIEW IF EXISTS `view_osfamily`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_osfamily` AS select distinct `_typology`.`id` AS `id`,`_typology`.`name` AS `name`,`_typology`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_typology`.`name`,'')) as char charset utf8) AS `friendlyname` from `typology` `_typology` where coalesce((`_typology`.`finalclass` = 'OSFamily'),1) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_oslicence`
--

/*!50001 DROP VIEW IF EXISTS `view_oslicence`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_oslicence` AS select distinct `_licence`.`id` AS `id`,`_licence`.`name` AS `name`,`_licence`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `organization_name`,`_licence`.`usage_limit` AS `usage_limit`,`_licence`.`description` AS `description`,`_licence`.`start_date` AS `start_date`,`_licence`.`end_date` AS `end_date`,`_licence`.`licence_key` AS `licence_key`,`_licence`.`perpetual` AS `perpetual`,`_oslicence`.`osversion_id` AS `osversion_id`,`osversion_osversion_id_typology`.`name` AS `osversion_name`,`_licence`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_licence`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce(((`_licence`.`perpetual` = 'no') and (isnull(`_licence`.`end_date`) = 0) and (`_licence`.`end_date` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00'))),0) AS `obsolescence_flag`,`_licence`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,cast(concat(coalesce(`osversion_osversion_id_typology`.`name`,'')) as char charset utf8) AS `osversion_id_friendlyname` from ((`licence` `_licence` join `organization` `organization_org_id_organization` on((`_licence`.`org_id` = `organization_org_id_organization`.`id`))) join (`oslicence` `_oslicence` join `typology` `osversion_osversion_id_typology` on((`_oslicence`.`osversion_id` = `osversion_osversion_id_typology`.`id`))) on((`_licence`.`id` = `_oslicence`.`id`))) where (coalesce((`_licence`.`finalclass` = 'OSLicence'),1) and coalesce((`osversion_osversion_id_typology`.`finalclass` = 'OSVersion'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_ospatch`
--

/*!50001 DROP VIEW IF EXISTS `view_ospatch`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_ospatch` AS select distinct `_patch`.`id` AS `id`,`_patch`.`name` AS `name`,`_patch`.`description` AS `description`,`_ospatch`.`osversion_id` AS `osversion_id`,`osversion_osversion_id_typology`.`name` AS `osversion_name`,`_patch`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_patch`.`name`,'')) as char charset utf8) AS `friendlyname`,cast(concat(coalesce(`osversion_osversion_id_typology`.`name`,'')) as char charset utf8) AS `osversion_id_friendlyname` from (`patch` `_patch` join (`ospatch` `_ospatch` join `typology` `osversion_osversion_id_typology` on((`_ospatch`.`osversion_id` = `osversion_osversion_id_typology`.`id`))) on((`_patch`.`id` = `_ospatch`.`id`))) where (coalesce((`_patch`.`finalclass` = 'OSPatch'),1) and coalesce((`osversion_osversion_id_typology`.`finalclass` = 'OSVersion'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_osversion`
--

/*!50001 DROP VIEW IF EXISTS `view_osversion`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_osversion` AS select distinct `_typology`.`id` AS `id`,`_typology`.`name` AS `name`,`_osversion`.`osfamily_id` AS `osfamily_id`,`osfamily_osfamily_id_typology`.`name` AS `osfamily_name`,`_typology`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_typology`.`name`,'')) as char charset utf8) AS `friendlyname`,cast(concat(coalesce(`osfamily_osfamily_id_typology`.`name`,'')) as char charset utf8) AS `osfamily_id_friendlyname` from (`typology` `_typology` join (`osversion` `_osversion` join `typology` `osfamily_osfamily_id_typology` on((`_osversion`.`osfamily_id` = `osfamily_osfamily_id_typology`.`id`))) on((`_typology`.`id` = `_osversion`.`id`))) where (coalesce((`_typology`.`finalclass` = 'OSVersion'),1) and coalesce((`osfamily_osfamily_id_typology`.`finalclass` = 'OSFamily'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_othersoftware`
--

/*!50001 DROP VIEW IF EXISTS `view_othersoftware`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_othersoftware` AS select distinct `_functionalci`.`id` AS `id`,`_functionalci`.`name` AS `name`,`_functionalci`.`description` AS `description`,`_functionalci`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `organization_name`,`_functionalci`.`business_criticity` AS `business_criticity`,`_functionalci`.`move2production` AS `move2production`,`_softwareinstance`.`functionalci_id` AS `system_id`,`functionalci_system_id_functionalci`.`name` AS `system_name`,`_softwareinstance`.`software_id` AS `software_id`,`software_software_id_software`.`name` AS `software_name`,`_softwareinstance`.`softwarelicence_id` AS `softwarelicence_id`,`softwarelicence_softwarelicence_id_licence`.`name` AS `softwarelicence_name`,`_softwareinstance`.`path` AS `path`,`_softwareinstance`.`status` AS `status`,`_functionalci`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_functionalci`.`name`,''),coalesce(' ',''),coalesce(`functionalci_system_id_functionalci`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce((`_softwareinstance`.`status` = 'inactive'),0) AS `obsolescence_flag`,`_functionalci`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,if((`functionalci_system_id_functionalci`.`finalclass` in ('Middleware','DBServer','WebServer','PCSoftware','OtherSoftware')),cast(concat(coalesce(`functionalci_system_id_functionalci`.`name`,''),coalesce(' ',''),coalesce(`functionalci_system_id1_functionalci`.`name`,'')) as char charset utf8),cast(concat(coalesce(`functionalci_system_id_functionalci`.`name`,'')) as char charset utf8)) AS `system_id_friendlyname`,`functionalci_system_id_functionalci`.`finalclass` AS `system_id_finalclass_recall`,if((`functionalci_system_id_functionalci`.`finalclass` = 'FunctionalCI'),coalesce(0,0),if((`functionalci_system_id_functionalci`.`finalclass` in ('Hypervisor','Farm','VirtualMachine')),coalesce((`functionalci_system_id_fn_virtualdevice_virtualdevice`.`status` = 'obsolete'),0),if((`functionalci_system_id_functionalci`.`finalclass` = 'WebApplication'),coalesce(coalesce((`webserver_webserver_id_softwareinstance`.`status` = 'inactive'),0),0),if((`functionalci_system_id_functionalci`.`finalclass` = 'DatabaseSchema'),coalesce(coalesce((`dbserver_dbserver_id_softwareinstance`.`status` = 'inactive'),0),0),if((`functionalci_system_id_functionalci`.`finalclass` = 'MiddlewareInstance'),coalesce(coalesce((`middleware_middleware_id_softwareinstance`.`status` = 'inactive'),0),0),if((`functionalci_system_id_functionalci`.`finalclass` in ('Middleware','DBServer','WebServer','PCSoftware','OtherSoftware')),coalesce((`functionalci_system_id_fn_softwareinstance_softwareinstance`.`status` = 'inactive'),0),if((`functionalci_system_id_functionalci`.`finalclass` = 'BusinessProcess'),coalesce((`functionalci_system_id_fn_businessprocess_businessprocess`.`status` = 'inactive'),0),if((`functionalci_system_id_functionalci`.`finalclass` = 'ApplicationSolution'),coalesce((`functionalci_system_id_fn_applicationsolution_applicationsolution`.`status` = 'inactive'),0),coalesce((`functionalci_system_id_fn_physicaldevice_physicaldevice`.`status` = 'obsolete'),0))))))))) AS `system_id_obsolescence_flag`,cast(concat(coalesce(`software_software_id_software`.`name`,''),coalesce(' ',''),coalesce(`software_software_id_software`.`version`,'')) as char charset utf8) AS `software_id_friendlyname`,cast(concat(coalesce(`softwarelicence_softwarelicence_id_licence`.`name`,'')) as char charset utf8) AS `softwarelicence_id_friendlyname`,coalesce(((`softwarelicence_softwarelicence_id_licence`.`perpetual` = 'no') and (isnull(`softwarelicence_softwarelicence_id_licence`.`end_date`) = 0) and (`softwarelicence_softwarelicence_id_licence`.`end_date` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00'))),0) AS `softwarelicence_id_obsolescence_flag` from ((`functionalci` `_functionalci` join `organization` `organization_org_id_organization` on((`_functionalci`.`org_id` = `organization_org_id_organization`.`id`))) join (((`softwareinstance` `_softwareinstance` join ((((((((`functionalci` `functionalci_system_id_functionalci` left join (`softwareinstance` `functionalci_system_id_fn_softwareinstance_softwareinstance` join `functionalci` `functionalci_system_id1_functionalci` on((`functionalci_system_id_fn_softwareinstance_softwareinstance`.`functionalci_id` = `functionalci_system_id1_functionalci`.`id`))) on((`functionalci_system_id_functionalci`.`id` = `functionalci_system_id_fn_softwareinstance_softwareinstance`.`id`))) left join `virtualdevice` `functionalci_system_id_fn_virtualdevice_virtualdevice` on((`functionalci_system_id_functionalci`.`id` = `functionalci_system_id_fn_virtualdevice_virtualdevice`.`id`))) left join (`webapplication` `functionalci_system_id_fn_webapplication_webapplication` join (`functionalci` `webserver_webserver_id_functionalci` join `softwareinstance` `webserver_webserver_id_softwareinstance` on((`webserver_webserver_id_functionalci`.`id` = `webserver_webserver_id_softwareinstance`.`id`))) on((`functionalci_system_id_fn_webapplication_webapplication`.`webserver_id` = `webserver_webserver_id_functionalci`.`id`))) on((`functionalci_system_id_functionalci`.`id` = `functionalci_system_id_fn_webapplication_webapplication`.`id`))) left join (`databaseschema` `functionalci_system_id_fn_databaseschema_databaseschema` join (`functionalci` `dbserver_dbserver_id_functionalci` join `softwareinstance` `dbserver_dbserver_id_softwareinstance` on((`dbserver_dbserver_id_functionalci`.`id` = `dbserver_dbserver_id_softwareinstance`.`id`))) on((`functionalci_system_id_fn_databaseschema_databaseschema`.`dbserver_id` = `dbserver_dbserver_id_functionalci`.`id`))) on((`functionalci_system_id_functionalci`.`id` = `functionalci_system_id_fn_databaseschema_databaseschema`.`id`))) left join (`middlewareinstance` `functionalci_system_id_fn_middlewareinstance_middlewareinstance` join (`functionalci` `middleware_middleware_id_functionalci` join `softwareinstance` `middleware_middleware_id_softwareinstance` on((`middleware_middleware_id_functionalci`.`id` = `middleware_middleware_id_softwareinstance`.`id`))) on((`functionalci_system_id_fn_middlewareinstance_middlewareinstance`.`middleware_id` = `middleware_middleware_id_functionalci`.`id`))) on((`functionalci_system_id_functionalci`.`id` = `functionalci_system_id_fn_middlewareinstance_middlewareinstance`.`id`))) left join `businessprocess` `functionalci_system_id_fn_businessprocess_businessprocess` on((`functionalci_system_id_functionalci`.`id` = `functionalci_system_id_fn_businessprocess_businessprocess`.`id`))) left join `applicationsolution` `functionalci_system_id_fn_applicationsolution_applicationsolution` on((`functionalci_system_id_functionalci`.`id` = `functionalci_system_id_fn_applicationsolution_applicationsolution`.`id`))) left join `physicaldevice` `functionalci_system_id_fn_physicaldevice_physicaldevice` on((`functionalci_system_id_functionalci`.`id` = `functionalci_system_id_fn_physicaldevice_physicaldevice`.`id`))) on((`_softwareinstance`.`functionalci_id` = `functionalci_system_id_functionalci`.`id`))) left join `software` `software_software_id_software` on((`_softwareinstance`.`software_id` = `software_software_id_software`.`id`))) left join `licence` `softwarelicence_softwarelicence_id_licence` on((`_softwareinstance`.`softwarelicence_id` = `softwarelicence_softwarelicence_id_licence`.`id`))) on((`_functionalci`.`id` = `_softwareinstance`.`id`))) where (coalesce((`_functionalci`.`finalclass` = 'OtherSoftware'),1) and coalesce((`webserver_webserver_id_functionalci`.`finalclass` = 'WebServer'),1) and coalesce((`dbserver_dbserver_id_functionalci`.`finalclass` = 'DBServer'),1) and coalesce((`middleware_middleware_id_functionalci`.`finalclass` = 'Middleware'),1) and coalesce((`softwarelicence_softwarelicence_id_licence`.`finalclass` = 'SoftwareLicence'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_patch`
--

/*!50001 DROP VIEW IF EXISTS `view_patch`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_patch` AS select distinct `_patch`.`id` AS `id`,`_patch`.`name` AS `name`,`_patch`.`description` AS `description`,`_patch`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_patch`.`name`,'')) as char charset utf8) AS `friendlyname` from `patch` `_patch` where 1 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_pc`
--

/*!50001 DROP VIEW IF EXISTS `view_pc`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_pc` AS select distinct `_functionalci`.`id` AS `id`,`_functionalci`.`name` AS `name`,`_functionalci`.`description` AS `description`,`_functionalci`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `organization_name`,`_functionalci`.`business_criticity` AS `business_criticity`,`_functionalci`.`move2production` AS `move2production`,`_physicaldevice`.`serialnumber` AS `serialnumber`,`_physicaldevice`.`location_id` AS `location_id`,`location_location_id_location`.`name` AS `location_name`,`_physicaldevice`.`status` AS `status`,`_physicaldevice`.`brand_id` AS `brand_id`,`brand_brand_id_typology`.`name` AS `brand_name`,`_physicaldevice`.`model_id` AS `model_id`,`model_model_id_typology`.`name` AS `model_name`,`_physicaldevice`.`asset_number` AS `asset_number`,`_physicaldevice`.`purchase_date` AS `purchase_date`,`_physicaldevice`.`end_of_warranty` AS `end_of_warranty`,`_pc`.`osfamily_id` AS `osfamily_id`,`osfamily_osfamily_id_typology`.`name` AS `osfamily_name`,`_pc`.`osversion_id` AS `osversion_id`,`osversion_osversion_id_typology`.`name` AS `osversion_name`,`_pc`.`cpu` AS `cpu`,`_pc`.`ram` AS `ram`,`_pc`.`type` AS `type`,`_functionalci`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_functionalci`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce((`_physicaldevice`.`status` = 'obsolete'),0) AS `obsolescence_flag`,`_functionalci`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,cast(concat(coalesce(`location_location_id_location`.`name`,'')) as char charset utf8) AS `location_id_friendlyname`,coalesce((`location_location_id_location`.`status` = 'inactive'),0) AS `location_id_obsolescence_flag`,cast(concat(coalesce(`brand_brand_id_typology`.`name`,'')) as char charset utf8) AS `brand_id_friendlyname`,cast(concat(coalesce(`model_model_id_typology`.`name`,'')) as char charset utf8) AS `model_id_friendlyname`,cast(concat(coalesce(`osfamily_osfamily_id_typology`.`name`,'')) as char charset utf8) AS `osfamily_id_friendlyname`,cast(concat(coalesce(`osversion_osversion_id_typology`.`name`,'')) as char charset utf8) AS `osversion_id_friendlyname` from (((`functionalci` `_functionalci` join `organization` `organization_org_id_organization` on((`_functionalci`.`org_id` = `organization_org_id_organization`.`id`))) join (((`physicaldevice` `_physicaldevice` left join `location` `location_location_id_location` on((`_physicaldevice`.`location_id` = `location_location_id_location`.`id`))) left join `typology` `brand_brand_id_typology` on((`_physicaldevice`.`brand_id` = `brand_brand_id_typology`.`id`))) left join `typology` `model_model_id_typology` on((`_physicaldevice`.`model_id` = `model_model_id_typology`.`id`))) on((`_functionalci`.`id` = `_physicaldevice`.`id`))) join ((`pc` `_pc` left join `typology` `osfamily_osfamily_id_typology` on((`_pc`.`osfamily_id` = `osfamily_osfamily_id_typology`.`id`))) left join `typology` `osversion_osversion_id_typology` on((`_pc`.`osversion_id` = `osversion_osversion_id_typology`.`id`))) on((`_functionalci`.`id` = `_pc`.`id`))) where (coalesce((`_functionalci`.`finalclass` = 'PC'),1) and coalesce((`brand_brand_id_typology`.`finalclass` = 'Brand'),1) and coalesce((`model_model_id_typology`.`finalclass` = 'Model'),1) and coalesce((`osfamily_osfamily_id_typology`.`finalclass` = 'OSFamily'),1) and coalesce((`osversion_osversion_id_typology`.`finalclass` = 'OSVersion'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_pcsoftware`
--

/*!50001 DROP VIEW IF EXISTS `view_pcsoftware`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_pcsoftware` AS select distinct `_functionalci`.`id` AS `id`,`_functionalci`.`name` AS `name`,`_functionalci`.`description` AS `description`,`_functionalci`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `organization_name`,`_functionalci`.`business_criticity` AS `business_criticity`,`_functionalci`.`move2production` AS `move2production`,`_softwareinstance`.`functionalci_id` AS `system_id`,`functionalci_system_id_functionalci`.`name` AS `system_name`,`_softwareinstance`.`software_id` AS `software_id`,`software_software_id_software`.`name` AS `software_name`,`_softwareinstance`.`softwarelicence_id` AS `softwarelicence_id`,`softwarelicence_softwarelicence_id_licence`.`name` AS `softwarelicence_name`,`_softwareinstance`.`path` AS `path`,`_softwareinstance`.`status` AS `status`,`_functionalci`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_functionalci`.`name`,''),coalesce(' ',''),coalesce(`functionalci_system_id_functionalci`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce((`_softwareinstance`.`status` = 'inactive'),0) AS `obsolescence_flag`,`_functionalci`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,if((`functionalci_system_id_functionalci`.`finalclass` in ('Middleware','DBServer','WebServer','PCSoftware','OtherSoftware')),cast(concat(coalesce(`functionalci_system_id_functionalci`.`name`,''),coalesce(' ',''),coalesce(`functionalci_system_id1_functionalci`.`name`,'')) as char charset utf8),cast(concat(coalesce(`functionalci_system_id_functionalci`.`name`,'')) as char charset utf8)) AS `system_id_friendlyname`,`functionalci_system_id_functionalci`.`finalclass` AS `system_id_finalclass_recall`,if((`functionalci_system_id_functionalci`.`finalclass` = 'FunctionalCI'),coalesce(0,0),if((`functionalci_system_id_functionalci`.`finalclass` in ('Hypervisor','Farm','VirtualMachine')),coalesce((`functionalci_system_id_fn_virtualdevice_virtualdevice`.`status` = 'obsolete'),0),if((`functionalci_system_id_functionalci`.`finalclass` = 'WebApplication'),coalesce(coalesce((`webserver_webserver_id_softwareinstance`.`status` = 'inactive'),0),0),if((`functionalci_system_id_functionalci`.`finalclass` = 'DatabaseSchema'),coalesce(coalesce((`dbserver_dbserver_id_softwareinstance`.`status` = 'inactive'),0),0),if((`functionalci_system_id_functionalci`.`finalclass` = 'MiddlewareInstance'),coalesce(coalesce((`middleware_middleware_id_softwareinstance`.`status` = 'inactive'),0),0),if((`functionalci_system_id_functionalci`.`finalclass` in ('Middleware','DBServer','WebServer','PCSoftware','OtherSoftware')),coalesce((`functionalci_system_id_fn_softwareinstance_softwareinstance`.`status` = 'inactive'),0),if((`functionalci_system_id_functionalci`.`finalclass` = 'BusinessProcess'),coalesce((`functionalci_system_id_fn_businessprocess_businessprocess`.`status` = 'inactive'),0),if((`functionalci_system_id_functionalci`.`finalclass` = 'ApplicationSolution'),coalesce((`functionalci_system_id_fn_applicationsolution_applicationsolution`.`status` = 'inactive'),0),coalesce((`functionalci_system_id_fn_physicaldevice_physicaldevice`.`status` = 'obsolete'),0))))))))) AS `system_id_obsolescence_flag`,cast(concat(coalesce(`software_software_id_software`.`name`,''),coalesce(' ',''),coalesce(`software_software_id_software`.`version`,'')) as char charset utf8) AS `software_id_friendlyname`,cast(concat(coalesce(`softwarelicence_softwarelicence_id_licence`.`name`,'')) as char charset utf8) AS `softwarelicence_id_friendlyname`,coalesce(((`softwarelicence_softwarelicence_id_licence`.`perpetual` = 'no') and (isnull(`softwarelicence_softwarelicence_id_licence`.`end_date`) = 0) and (`softwarelicence_softwarelicence_id_licence`.`end_date` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00'))),0) AS `softwarelicence_id_obsolescence_flag` from ((`functionalci` `_functionalci` join `organization` `organization_org_id_organization` on((`_functionalci`.`org_id` = `organization_org_id_organization`.`id`))) join (((`softwareinstance` `_softwareinstance` join ((((((((`functionalci` `functionalci_system_id_functionalci` left join (`softwareinstance` `functionalci_system_id_fn_softwareinstance_softwareinstance` join `functionalci` `functionalci_system_id1_functionalci` on((`functionalci_system_id_fn_softwareinstance_softwareinstance`.`functionalci_id` = `functionalci_system_id1_functionalci`.`id`))) on((`functionalci_system_id_functionalci`.`id` = `functionalci_system_id_fn_softwareinstance_softwareinstance`.`id`))) left join `virtualdevice` `functionalci_system_id_fn_virtualdevice_virtualdevice` on((`functionalci_system_id_functionalci`.`id` = `functionalci_system_id_fn_virtualdevice_virtualdevice`.`id`))) left join (`webapplication` `functionalci_system_id_fn_webapplication_webapplication` join (`functionalci` `webserver_webserver_id_functionalci` join `softwareinstance` `webserver_webserver_id_softwareinstance` on((`webserver_webserver_id_functionalci`.`id` = `webserver_webserver_id_softwareinstance`.`id`))) on((`functionalci_system_id_fn_webapplication_webapplication`.`webserver_id` = `webserver_webserver_id_functionalci`.`id`))) on((`functionalci_system_id_functionalci`.`id` = `functionalci_system_id_fn_webapplication_webapplication`.`id`))) left join (`databaseschema` `functionalci_system_id_fn_databaseschema_databaseschema` join (`functionalci` `dbserver_dbserver_id_functionalci` join `softwareinstance` `dbserver_dbserver_id_softwareinstance` on((`dbserver_dbserver_id_functionalci`.`id` = `dbserver_dbserver_id_softwareinstance`.`id`))) on((`functionalci_system_id_fn_databaseschema_databaseschema`.`dbserver_id` = `dbserver_dbserver_id_functionalci`.`id`))) on((`functionalci_system_id_functionalci`.`id` = `functionalci_system_id_fn_databaseschema_databaseschema`.`id`))) left join (`middlewareinstance` `functionalci_system_id_fn_middlewareinstance_middlewareinstance` join (`functionalci` `middleware_middleware_id_functionalci` join `softwareinstance` `middleware_middleware_id_softwareinstance` on((`middleware_middleware_id_functionalci`.`id` = `middleware_middleware_id_softwareinstance`.`id`))) on((`functionalci_system_id_fn_middlewareinstance_middlewareinstance`.`middleware_id` = `middleware_middleware_id_functionalci`.`id`))) on((`functionalci_system_id_functionalci`.`id` = `functionalci_system_id_fn_middlewareinstance_middlewareinstance`.`id`))) left join `businessprocess` `functionalci_system_id_fn_businessprocess_businessprocess` on((`functionalci_system_id_functionalci`.`id` = `functionalci_system_id_fn_businessprocess_businessprocess`.`id`))) left join `applicationsolution` `functionalci_system_id_fn_applicationsolution_applicationsolution` on((`functionalci_system_id_functionalci`.`id` = `functionalci_system_id_fn_applicationsolution_applicationsolution`.`id`))) left join `physicaldevice` `functionalci_system_id_fn_physicaldevice_physicaldevice` on((`functionalci_system_id_functionalci`.`id` = `functionalci_system_id_fn_physicaldevice_physicaldevice`.`id`))) on((`_softwareinstance`.`functionalci_id` = `functionalci_system_id_functionalci`.`id`))) left join `software` `software_software_id_software` on((`_softwareinstance`.`software_id` = `software_software_id_software`.`id`))) left join `licence` `softwarelicence_softwarelicence_id_licence` on((`_softwareinstance`.`softwarelicence_id` = `softwarelicence_softwarelicence_id_licence`.`id`))) on((`_functionalci`.`id` = `_softwareinstance`.`id`))) where (coalesce((`_functionalci`.`finalclass` = 'PCSoftware'),1) and coalesce((`webserver_webserver_id_functionalci`.`finalclass` = 'WebServer'),1) and coalesce((`dbserver_dbserver_id_functionalci`.`finalclass` = 'DBServer'),1) and coalesce((`middleware_middleware_id_functionalci`.`finalclass` = 'Middleware'),1) and coalesce((`softwarelicence_softwarelicence_id_licence`.`finalclass` = 'SoftwareLicence'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_pdu`
--

/*!50001 DROP VIEW IF EXISTS `view_pdu`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_pdu` AS select distinct `_functionalci`.`id` AS `id`,`_functionalci`.`name` AS `name`,`_functionalci`.`description` AS `description`,`_functionalci`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `organization_name`,`_functionalci`.`business_criticity` AS `business_criticity`,`_functionalci`.`move2production` AS `move2production`,`_physicaldevice`.`serialnumber` AS `serialnumber`,`_physicaldevice`.`location_id` AS `location_id`,`location_location_id_location`.`name` AS `location_name`,`_physicaldevice`.`status` AS `status`,`_physicaldevice`.`brand_id` AS `brand_id`,`brand_brand_id_typology`.`name` AS `brand_name`,`_physicaldevice`.`model_id` AS `model_id`,`model_model_id_typology`.`name` AS `model_name`,`_physicaldevice`.`asset_number` AS `asset_number`,`_physicaldevice`.`purchase_date` AS `purchase_date`,`_physicaldevice`.`end_of_warranty` AS `end_of_warranty`,`_pdu`.`rack_id` AS `rack_id`,`rack_rack_id_functionalci`.`name` AS `rack_name`,`_pdu`.`powerstart_id` AS `powerstart_id`,`powerconnection_powerstart_id_functionalci`.`name` AS `powerstart_name`,`_functionalci`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_functionalci`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce((`_physicaldevice`.`status` = 'obsolete'),0) AS `obsolescence_flag`,`_functionalci`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,cast(concat(coalesce(`location_location_id_location`.`name`,'')) as char charset utf8) AS `location_id_friendlyname`,coalesce((`location_location_id_location`.`status` = 'inactive'),0) AS `location_id_obsolescence_flag`,cast(concat(coalesce(`brand_brand_id_typology`.`name`,'')) as char charset utf8) AS `brand_id_friendlyname`,cast(concat(coalesce(`model_model_id_typology`.`name`,'')) as char charset utf8) AS `model_id_friendlyname`,cast(concat(coalesce(`rack_rack_id_functionalci`.`name`,'')) as char charset utf8) AS `rack_id_friendlyname`,coalesce((`rack_rack_id_physicaldevice`.`status` = 'obsolete'),0) AS `rack_id_obsolescence_flag`,cast(concat(coalesce(`powerconnection_powerstart_id_functionalci`.`name`,'')) as char charset utf8) AS `powerstart_id_friendlyname`,`powerconnection_powerstart_id_functionalci`.`finalclass` AS `powerstart_id_finalclass_recall`,coalesce((`powerconnection_powerstart_id_physicaldevice`.`status` = 'obsolete'),0) AS `powerstart_id_obsolescence_flag` from (((`functionalci` `_functionalci` join `organization` `organization_org_id_organization` on((`_functionalci`.`org_id` = `organization_org_id_organization`.`id`))) join (((`physicaldevice` `_physicaldevice` left join `location` `location_location_id_location` on((`_physicaldevice`.`location_id` = `location_location_id_location`.`id`))) left join `typology` `brand_brand_id_typology` on((`_physicaldevice`.`brand_id` = `brand_brand_id_typology`.`id`))) left join `typology` `model_model_id_typology` on((`_physicaldevice`.`model_id` = `model_model_id_typology`.`id`))) on((`_functionalci`.`id` = `_physicaldevice`.`id`))) join ((`pdu` `_pdu` join (`functionalci` `rack_rack_id_functionalci` join `physicaldevice` `rack_rack_id_physicaldevice` on((`rack_rack_id_functionalci`.`id` = `rack_rack_id_physicaldevice`.`id`))) on((`_pdu`.`rack_id` = `rack_rack_id_functionalci`.`id`))) left join (`functionalci` `powerconnection_powerstart_id_functionalci` join `physicaldevice` `powerconnection_powerstart_id_physicaldevice` on((`powerconnection_powerstart_id_functionalci`.`id` = `powerconnection_powerstart_id_physicaldevice`.`id`))) on((`_pdu`.`powerstart_id` = `powerconnection_powerstart_id_functionalci`.`id`))) on((`_functionalci`.`id` = `_pdu`.`id`))) where (coalesce((`_functionalci`.`finalclass` = 'PDU'),1) and coalesce((`brand_brand_id_typology`.`finalclass` = 'Brand'),1) and coalesce((`model_model_id_typology`.`finalclass` = 'Model'),1) and coalesce((`rack_rack_id_functionalci`.`finalclass` = 'Rack'),1) and coalesce((`powerconnection_powerstart_id_functionalci`.`finalclass` in ('PowerSource','PDU','PowerConnection')),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_peripheral`
--

/*!50001 DROP VIEW IF EXISTS `view_peripheral`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_peripheral` AS select distinct `_functionalci`.`id` AS `id`,`_functionalci`.`name` AS `name`,`_functionalci`.`description` AS `description`,`_functionalci`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `organization_name`,`_functionalci`.`business_criticity` AS `business_criticity`,`_functionalci`.`move2production` AS `move2production`,`_physicaldevice`.`serialnumber` AS `serialnumber`,`_physicaldevice`.`location_id` AS `location_id`,`location_location_id_location`.`name` AS `location_name`,`_physicaldevice`.`status` AS `status`,`_physicaldevice`.`brand_id` AS `brand_id`,`brand_brand_id_typology`.`name` AS `brand_name`,`_physicaldevice`.`model_id` AS `model_id`,`model_model_id_typology`.`name` AS `model_name`,`_physicaldevice`.`asset_number` AS `asset_number`,`_physicaldevice`.`purchase_date` AS `purchase_date`,`_physicaldevice`.`end_of_warranty` AS `end_of_warranty`,`_functionalci`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_functionalci`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce((`_physicaldevice`.`status` = 'obsolete'),0) AS `obsolescence_flag`,`_functionalci`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,cast(concat(coalesce(`location_location_id_location`.`name`,'')) as char charset utf8) AS `location_id_friendlyname`,coalesce((`location_location_id_location`.`status` = 'inactive'),0) AS `location_id_obsolescence_flag`,cast(concat(coalesce(`brand_brand_id_typology`.`name`,'')) as char charset utf8) AS `brand_id_friendlyname`,cast(concat(coalesce(`model_model_id_typology`.`name`,'')) as char charset utf8) AS `model_id_friendlyname` from ((`functionalci` `_functionalci` join `organization` `organization_org_id_organization` on((`_functionalci`.`org_id` = `organization_org_id_organization`.`id`))) join (((`physicaldevice` `_physicaldevice` left join `location` `location_location_id_location` on((`_physicaldevice`.`location_id` = `location_location_id_location`.`id`))) left join `typology` `brand_brand_id_typology` on((`_physicaldevice`.`brand_id` = `brand_brand_id_typology`.`id`))) left join `typology` `model_model_id_typology` on((`_physicaldevice`.`model_id` = `model_model_id_typology`.`id`))) on((`_functionalci`.`id` = `_physicaldevice`.`id`))) where (coalesce((`_functionalci`.`finalclass` = 'Peripheral'),1) and coalesce((`brand_brand_id_typology`.`finalclass` = 'Brand'),1) and coalesce((`model_model_id_typology`.`finalclass` = 'Model'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_person`
--

/*!50001 DROP VIEW IF EXISTS `view_person`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_person` AS select distinct `_contact`.`id` AS `id`,`_contact`.`name` AS `name`,`_contact`.`status` AS `status`,`_contact`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `org_name`,`_contact`.`email` AS `email`,`_contact`.`phone` AS `phone`,`_contact`.`notify` AS `notify`,`_contact`.`function` AS `function`,`_person`.`picture_mimetype` AS `picture`,`_person`.`picture_data` AS `picture_data`,`_person`.`picture_filename` AS `picture_filename`,`_person`.`first_name` AS `first_name`,`_person`.`employee_number` AS `employee_number`,`_person`.`mobile_phone` AS `mobile_phone`,`_person`.`location_id` AS `location_id`,`location_location_id_location`.`name` AS `location_name`,`_person`.`manager_id` AS `manager_id`,`person_manager_id_contact`.`name` AS `manager_name`,`_contact`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_person`.`first_name`,''),coalesce(' ',''),coalesce(`_contact`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce((`_contact`.`status` = 'inactive'),0) AS `obsolescence_flag`,`_contact`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,cast(concat(coalesce(`location_location_id_location`.`name`,'')) as char charset utf8) AS `location_id_friendlyname`,coalesce((`location_location_id_location`.`status` = 'inactive'),0) AS `location_id_obsolescence_flag`,cast(concat(coalesce(`person_manager_id_person`.`first_name`,''),coalesce(' ',''),coalesce(`person_manager_id_contact`.`name`,'')) as char charset utf8) AS `manager_id_friendlyname`,coalesce((`person_manager_id_contact`.`status` = 'inactive'),0) AS `manager_id_obsolescence_flag` from ((`contact` `_contact` join `organization` `organization_org_id_organization` on((`_contact`.`org_id` = `organization_org_id_organization`.`id`))) join ((`person` `_person` left join `location` `location_location_id_location` on((`_person`.`location_id` = `location_location_id_location`.`id`))) left join (`contact` `person_manager_id_contact` join `person` `person_manager_id_person` on((`person_manager_id_contact`.`id` = `person_manager_id_person`.`id`))) on((`_person`.`manager_id` = `person_manager_id_contact`.`id`))) on((`_contact`.`id` = `_person`.`id`))) where (coalesce((`_contact`.`finalclass` = 'Person'),1) and coalesce((`person_manager_id_contact`.`finalclass` = 'Person'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_phone`
--

/*!50001 DROP VIEW IF EXISTS `view_phone`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_phone` AS select distinct `_functionalci`.`id` AS `id`,`_functionalci`.`name` AS `name`,`_functionalci`.`description` AS `description`,`_functionalci`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `organization_name`,`_functionalci`.`business_criticity` AS `business_criticity`,`_functionalci`.`move2production` AS `move2production`,`_physicaldevice`.`serialnumber` AS `serialnumber`,`_physicaldevice`.`location_id` AS `location_id`,`location_location_id_location`.`name` AS `location_name`,`_physicaldevice`.`status` AS `status`,`_physicaldevice`.`brand_id` AS `brand_id`,`brand_brand_id_typology`.`name` AS `brand_name`,`_physicaldevice`.`model_id` AS `model_id`,`model_model_id_typology`.`name` AS `model_name`,`_physicaldevice`.`asset_number` AS `asset_number`,`_physicaldevice`.`purchase_date` AS `purchase_date`,`_physicaldevice`.`end_of_warranty` AS `end_of_warranty`,`_telephonyci`.`phonenumber` AS `phonenumber`,`_functionalci`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_functionalci`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce((`_physicaldevice`.`status` = 'obsolete'),0) AS `obsolescence_flag`,`_functionalci`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,cast(concat(coalesce(`location_location_id_location`.`name`,'')) as char charset utf8) AS `location_id_friendlyname`,coalesce((`location_location_id_location`.`status` = 'inactive'),0) AS `location_id_obsolescence_flag`,cast(concat(coalesce(`brand_brand_id_typology`.`name`,'')) as char charset utf8) AS `brand_id_friendlyname`,cast(concat(coalesce(`model_model_id_typology`.`name`,'')) as char charset utf8) AS `model_id_friendlyname` from (((`functionalci` `_functionalci` join `organization` `organization_org_id_organization` on((`_functionalci`.`org_id` = `organization_org_id_organization`.`id`))) join (((`physicaldevice` `_physicaldevice` left join `location` `location_location_id_location` on((`_physicaldevice`.`location_id` = `location_location_id_location`.`id`))) left join `typology` `brand_brand_id_typology` on((`_physicaldevice`.`brand_id` = `brand_brand_id_typology`.`id`))) left join `typology` `model_model_id_typology` on((`_physicaldevice`.`model_id` = `model_model_id_typology`.`id`))) on((`_functionalci`.`id` = `_physicaldevice`.`id`))) join `telephonyci` `_telephonyci` on((`_functionalci`.`id` = `_telephonyci`.`id`))) where (coalesce((`_functionalci`.`finalclass` = 'Phone'),1) and coalesce((`brand_brand_id_typology`.`finalclass` = 'Brand'),1) and coalesce((`model_model_id_typology`.`finalclass` = 'Model'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_physicaldevice`
--

/*!50001 DROP VIEW IF EXISTS `view_physicaldevice`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_physicaldevice` AS select distinct `_functionalci`.`id` AS `id`,`_functionalci`.`name` AS `name`,`_functionalci`.`description` AS `description`,`_functionalci`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `organization_name`,`_functionalci`.`business_criticity` AS `business_criticity`,`_functionalci`.`move2production` AS `move2production`,`_physicaldevice`.`serialnumber` AS `serialnumber`,`_physicaldevice`.`location_id` AS `location_id`,`location_location_id_location`.`name` AS `location_name`,`_physicaldevice`.`status` AS `status`,`_physicaldevice`.`brand_id` AS `brand_id`,`brand_brand_id_typology`.`name` AS `brand_name`,`_physicaldevice`.`model_id` AS `model_id`,`model_model_id_typology`.`name` AS `model_name`,`_physicaldevice`.`asset_number` AS `asset_number`,`_physicaldevice`.`purchase_date` AS `purchase_date`,`_physicaldevice`.`end_of_warranty` AS `end_of_warranty`,`_functionalci`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_functionalci`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce((`_physicaldevice`.`status` = 'obsolete'),0) AS `obsolescence_flag`,`_functionalci`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,cast(concat(coalesce(`location_location_id_location`.`name`,'')) as char charset utf8) AS `location_id_friendlyname`,coalesce((`location_location_id_location`.`status` = 'inactive'),0) AS `location_id_obsolescence_flag`,cast(concat(coalesce(`brand_brand_id_typology`.`name`,'')) as char charset utf8) AS `brand_id_friendlyname`,cast(concat(coalesce(`model_model_id_typology`.`name`,'')) as char charset utf8) AS `model_id_friendlyname` from ((`functionalci` `_functionalci` join `organization` `organization_org_id_organization` on((`_functionalci`.`org_id` = `organization_org_id_organization`.`id`))) join (((`physicaldevice` `_physicaldevice` left join `location` `location_location_id_location` on((`_physicaldevice`.`location_id` = `location_location_id_location`.`id`))) left join `typology` `brand_brand_id_typology` on((`_physicaldevice`.`brand_id` = `brand_brand_id_typology`.`id`))) left join `typology` `model_model_id_typology` on((`_physicaldevice`.`model_id` = `model_model_id_typology`.`id`))) on((`_functionalci`.`id` = `_physicaldevice`.`id`))) where (coalesce((`_functionalci`.`finalclass` in ('ConnectableCI','DatacenterDevice','NetworkDevice','Server','Rack','Enclosure','PowerConnection','PowerSource','PDU','PC','Printer','TelephonyCI','Phone','MobilePhone','IPPhone','Tablet','Peripheral','StorageSystem','SANSwitch','TapeLibrary','NAS','PhysicalDevice')),1) and coalesce((`brand_brand_id_typology`.`finalclass` = 'Brand'),1) and coalesce((`model_model_id_typology`.`finalclass` = 'Model'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_physicalinterface`
--

/*!50001 DROP VIEW IF EXISTS `view_physicalinterface`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_physicalinterface` AS select distinct `_networkinterface`.`id` AS `id`,`_networkinterface`.`name` AS `name`,`_ipinterface`.`ipaddress` AS `ipaddress`,`_ipinterface`.`macaddress` AS `macaddress`,`_ipinterface`.`comment` AS `comment`,`_ipinterface`.`ipgateway` AS `ipgateway`,`_ipinterface`.`ipmask` AS `ipmask`,`_ipinterface`.`speed` AS `speed`,`_physicalinterface`.`connectableci_id` AS `connectableci_id`,`connectableci_connectableci_id_functionalci`.`name` AS `connectableci_name`,`_networkinterface`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_networkinterface`.`name`,''),coalesce(' ',''),coalesce(`connectableci_connectableci_id_functionalci`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce(coalesce((`connectableci_connectableci_id_physicaldevice`.`status` = 'obsolete'),0),0) AS `obsolescence_flag`,`_networkinterface`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`connectableci_connectableci_id_functionalci`.`name`,'')) as char charset utf8) AS `connectableci_id_friendlyname`,`connectableci_connectableci_id_functionalci`.`finalclass` AS `connectableci_id_finalclass_recall`,coalesce((`connectableci_connectableci_id_physicaldevice`.`status` = 'obsolete'),0) AS `connectableci_id_obsolescence_flag` from ((`networkinterface` `_networkinterface` join `ipinterface` `_ipinterface` on((`_networkinterface`.`id` = `_ipinterface`.`id`))) join (`physicalinterface` `_physicalinterface` join (`functionalci` `connectableci_connectableci_id_functionalci` join `physicaldevice` `connectableci_connectableci_id_physicaldevice` on((`connectableci_connectableci_id_functionalci`.`id` = `connectableci_connectableci_id_physicaldevice`.`id`))) on((`_physicalinterface`.`connectableci_id` = `connectableci_connectableci_id_functionalci`.`id`))) on((`_networkinterface`.`id` = `_physicalinterface`.`id`))) where (coalesce((`_networkinterface`.`finalclass` = 'PhysicalInterface'),1) and coalesce((`connectableci_connectableci_id_functionalci`.`finalclass` in ('DatacenterDevice','NetworkDevice','Server','PC','Printer','StorageSystem','SANSwitch','TapeLibrary','NAS','ConnectableCI')),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_powerconnection`
--

/*!50001 DROP VIEW IF EXISTS `view_powerconnection`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_powerconnection` AS select distinct `_functionalci`.`id` AS `id`,`_functionalci`.`name` AS `name`,`_functionalci`.`description` AS `description`,`_functionalci`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `organization_name`,`_functionalci`.`business_criticity` AS `business_criticity`,`_functionalci`.`move2production` AS `move2production`,`_physicaldevice`.`serialnumber` AS `serialnumber`,`_physicaldevice`.`location_id` AS `location_id`,`location_location_id_location`.`name` AS `location_name`,`_physicaldevice`.`status` AS `status`,`_physicaldevice`.`brand_id` AS `brand_id`,`brand_brand_id_typology`.`name` AS `brand_name`,`_physicaldevice`.`model_id` AS `model_id`,`model_model_id_typology`.`name` AS `model_name`,`_physicaldevice`.`asset_number` AS `asset_number`,`_physicaldevice`.`purchase_date` AS `purchase_date`,`_physicaldevice`.`end_of_warranty` AS `end_of_warranty`,`_functionalci`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_functionalci`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce((`_physicaldevice`.`status` = 'obsolete'),0) AS `obsolescence_flag`,`_functionalci`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,cast(concat(coalesce(`location_location_id_location`.`name`,'')) as char charset utf8) AS `location_id_friendlyname`,coalesce((`location_location_id_location`.`status` = 'inactive'),0) AS `location_id_obsolescence_flag`,cast(concat(coalesce(`brand_brand_id_typology`.`name`,'')) as char charset utf8) AS `brand_id_friendlyname`,cast(concat(coalesce(`model_model_id_typology`.`name`,'')) as char charset utf8) AS `model_id_friendlyname` from ((`functionalci` `_functionalci` join `organization` `organization_org_id_organization` on((`_functionalci`.`org_id` = `organization_org_id_organization`.`id`))) join (((`physicaldevice` `_physicaldevice` left join `location` `location_location_id_location` on((`_physicaldevice`.`location_id` = `location_location_id_location`.`id`))) left join `typology` `brand_brand_id_typology` on((`_physicaldevice`.`brand_id` = `brand_brand_id_typology`.`id`))) left join `typology` `model_model_id_typology` on((`_physicaldevice`.`model_id` = `model_model_id_typology`.`id`))) on((`_functionalci`.`id` = `_physicaldevice`.`id`))) where (coalesce((`_functionalci`.`finalclass` in ('PowerSource','PDU','PowerConnection')),1) and coalesce((`brand_brand_id_typology`.`finalclass` = 'Brand'),1) and coalesce((`model_model_id_typology`.`finalclass` = 'Model'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_powersource`
--

/*!50001 DROP VIEW IF EXISTS `view_powersource`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_powersource` AS select distinct `_functionalci`.`id` AS `id`,`_functionalci`.`name` AS `name`,`_functionalci`.`description` AS `description`,`_functionalci`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `organization_name`,`_functionalci`.`business_criticity` AS `business_criticity`,`_functionalci`.`move2production` AS `move2production`,`_physicaldevice`.`serialnumber` AS `serialnumber`,`_physicaldevice`.`location_id` AS `location_id`,`location_location_id_location`.`name` AS `location_name`,`_physicaldevice`.`status` AS `status`,`_physicaldevice`.`brand_id` AS `brand_id`,`brand_brand_id_typology`.`name` AS `brand_name`,`_physicaldevice`.`model_id` AS `model_id`,`model_model_id_typology`.`name` AS `model_name`,`_physicaldevice`.`asset_number` AS `asset_number`,`_physicaldevice`.`purchase_date` AS `purchase_date`,`_physicaldevice`.`end_of_warranty` AS `end_of_warranty`,`_functionalci`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_functionalci`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce((`_physicaldevice`.`status` = 'obsolete'),0) AS `obsolescence_flag`,`_functionalci`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,cast(concat(coalesce(`location_location_id_location`.`name`,'')) as char charset utf8) AS `location_id_friendlyname`,coalesce((`location_location_id_location`.`status` = 'inactive'),0) AS `location_id_obsolescence_flag`,cast(concat(coalesce(`brand_brand_id_typology`.`name`,'')) as char charset utf8) AS `brand_id_friendlyname`,cast(concat(coalesce(`model_model_id_typology`.`name`,'')) as char charset utf8) AS `model_id_friendlyname` from ((`functionalci` `_functionalci` join `organization` `organization_org_id_organization` on((`_functionalci`.`org_id` = `organization_org_id_organization`.`id`))) join (((`physicaldevice` `_physicaldevice` left join `location` `location_location_id_location` on((`_physicaldevice`.`location_id` = `location_location_id_location`.`id`))) left join `typology` `brand_brand_id_typology` on((`_physicaldevice`.`brand_id` = `brand_brand_id_typology`.`id`))) left join `typology` `model_model_id_typology` on((`_physicaldevice`.`model_id` = `model_model_id_typology`.`id`))) on((`_functionalci`.`id` = `_physicaldevice`.`id`))) where (coalesce((`_functionalci`.`finalclass` = 'PowerSource'),1) and coalesce((`brand_brand_id_typology`.`finalclass` = 'Brand'),1) and coalesce((`model_model_id_typology`.`finalclass` = 'Model'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_printer`
--

/*!50001 DROP VIEW IF EXISTS `view_printer`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_printer` AS select distinct `_functionalci`.`id` AS `id`,`_functionalci`.`name` AS `name`,`_functionalci`.`description` AS `description`,`_functionalci`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `organization_name`,`_functionalci`.`business_criticity` AS `business_criticity`,`_functionalci`.`move2production` AS `move2production`,`_physicaldevice`.`serialnumber` AS `serialnumber`,`_physicaldevice`.`location_id` AS `location_id`,`location_location_id_location`.`name` AS `location_name`,`_physicaldevice`.`status` AS `status`,`_physicaldevice`.`brand_id` AS `brand_id`,`brand_brand_id_typology`.`name` AS `brand_name`,`_physicaldevice`.`model_id` AS `model_id`,`model_model_id_typology`.`name` AS `model_name`,`_physicaldevice`.`asset_number` AS `asset_number`,`_physicaldevice`.`purchase_date` AS `purchase_date`,`_physicaldevice`.`end_of_warranty` AS `end_of_warranty`,`_functionalci`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_functionalci`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce((`_physicaldevice`.`status` = 'obsolete'),0) AS `obsolescence_flag`,`_functionalci`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,cast(concat(coalesce(`location_location_id_location`.`name`,'')) as char charset utf8) AS `location_id_friendlyname`,coalesce((`location_location_id_location`.`status` = 'inactive'),0) AS `location_id_obsolescence_flag`,cast(concat(coalesce(`brand_brand_id_typology`.`name`,'')) as char charset utf8) AS `brand_id_friendlyname`,cast(concat(coalesce(`model_model_id_typology`.`name`,'')) as char charset utf8) AS `model_id_friendlyname` from ((`functionalci` `_functionalci` join `organization` `organization_org_id_organization` on((`_functionalci`.`org_id` = `organization_org_id_organization`.`id`))) join (((`physicaldevice` `_physicaldevice` left join `location` `location_location_id_location` on((`_physicaldevice`.`location_id` = `location_location_id_location`.`id`))) left join `typology` `brand_brand_id_typology` on((`_physicaldevice`.`brand_id` = `brand_brand_id_typology`.`id`))) left join `typology` `model_model_id_typology` on((`_physicaldevice`.`model_id` = `model_model_id_typology`.`id`))) on((`_functionalci`.`id` = `_physicaldevice`.`id`))) where (coalesce((`_functionalci`.`finalclass` = 'Printer'),1) and coalesce((`brand_brand_id_typology`.`finalclass` = 'Brand'),1) and coalesce((`model_model_id_typology`.`finalclass` = 'Model'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_problem`
--

/*!50001 DROP VIEW IF EXISTS `view_problem`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_problem` AS select distinct `_ticket`.`id` AS `id`,`_ticket`.`operational_status` AS `operational_status`,`_ticket`.`ref` AS `ref`,`_ticket`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `org_name`,`_ticket`.`caller_id` AS `caller_id`,`person_caller_id_contact`.`name` AS `caller_name`,`_ticket`.`team_id` AS `team_id`,`team_team_id_contact`.`email` AS `team_name`,`_ticket`.`agent_id` AS `agent_id`,`person_agent_id_contact`.`name` AS `agent_name`,`_ticket`.`title` AS `title`,`_ticket`.`description` AS `description`,`_ticket`.`description_format` AS `description_format`,`_ticket`.`start_date` AS `start_date`,`_ticket`.`end_date` AS `end_date`,`_ticket`.`last_update` AS `last_update`,`_ticket`.`close_date` AS `close_date`,`_ticket`.`private_log` AS `private_log`,`_ticket`.`private_log_index` AS `private_log_index`,`_ticket_problem`.`status` AS `status`,`_ticket_problem`.`service_id` AS `service_id`,`service_service_id_service`.`name` AS `service_name`,`_ticket_problem`.`servicesubcategory_id` AS `servicesubcategory_id`,`servicesubcategory_servicesubcategory_id_servicesubcategory`.`name` AS `servicesubcategory_name`,`_ticket_problem`.`product` AS `product`,`_ticket_problem`.`impact` AS `impact`,`_ticket_problem`.`urgency` AS `urgency`,`_ticket_problem`.`priority` AS `priority`,`_ticket_problem`.`related_change_id` AS `related_change_id`,`change_related_change_id_ticket`.`ref` AS `related_change_ref`,`_ticket_problem`.`assignment_date` AS `assignment_date`,`_ticket_problem`.`resolution_date` AS `resolution_date`,`_ticket`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_ticket`.`ref`,'')) as char charset utf8) AS `friendlyname`,coalesce(((`_ticket`.`operational_status` = 'closed') and ((`_ticket`.`close_date` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00')) or (isnull(`_ticket`.`close_date`) and (`_ticket`.`last_update` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00'))))),0) AS `obsolescence_flag`,`_ticket`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,cast(concat(coalesce(`person_caller_id_person`.`first_name`,''),coalesce(' ',''),coalesce(`person_caller_id_contact`.`name`,'')) as char charset utf8) AS `caller_id_friendlyname`,coalesce((`person_caller_id_contact`.`status` = 'inactive'),0) AS `caller_id_obsolescence_flag`,cast(concat(coalesce(`team_team_id_contact`.`name`,'')) as char charset utf8) AS `team_id_friendlyname`,coalesce((`team_team_id_contact`.`status` = 'inactive'),0) AS `team_id_obsolescence_flag`,cast(concat(coalesce(`person_agent_id_person`.`first_name`,''),coalesce(' ',''),coalesce(`person_agent_id_contact`.`name`,'')) as char charset utf8) AS `agent_id_friendlyname`,coalesce((`person_agent_id_contact`.`status` = 'inactive'),0) AS `agent_id_obsolescence_flag`,cast(concat(coalesce(`service_service_id_service`.`name`,'')) as char charset utf8) AS `service_id_friendlyname`,cast(concat(coalesce(`servicesubcategory_servicesubcategory_id_servicesubcategory`.`name`,'')) as char charset utf8) AS `servicesubcategory_id_friendlyname`,cast(concat(coalesce(`change_related_change_id_ticket`.`ref`,'')) as char charset utf8) AS `related_change_id_friendlyname`,`change_related_change_id_ticket`.`finalclass` AS `related_change_id_finalclass_recall`,coalesce(((`change_related_change_id_ticket`.`operational_status` = 'closed') and ((`change_related_change_id_ticket`.`close_date` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00')) or (isnull(`change_related_change_id_ticket`.`close_date`) and (`change_related_change_id_ticket`.`last_update` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00'))))),0) AS `related_change_id_obsolescence_flag` from (((((`ticket` `_ticket` join `organization` `organization_org_id_organization` on((`_ticket`.`org_id` = `organization_org_id_organization`.`id`))) left join (`contact` `person_caller_id_contact` join `person` `person_caller_id_person` on((`person_caller_id_contact`.`id` = `person_caller_id_person`.`id`))) on((`_ticket`.`caller_id` = `person_caller_id_contact`.`id`))) left join `contact` `team_team_id_contact` on((`_ticket`.`team_id` = `team_team_id_contact`.`id`))) left join (`contact` `person_agent_id_contact` join `person` `person_agent_id_person` on((`person_agent_id_contact`.`id` = `person_agent_id_person`.`id`))) on((`_ticket`.`agent_id` = `person_agent_id_contact`.`id`))) join (((`ticket_problem` `_ticket_problem` left join `service` `service_service_id_service` on((`_ticket_problem`.`service_id` = `service_service_id_service`.`id`))) left join `servicesubcategory` `servicesubcategory_servicesubcategory_id_servicesubcategory` on((`_ticket_problem`.`servicesubcategory_id` = `servicesubcategory_servicesubcategory_id_servicesubcategory`.`id`))) left join `ticket` `change_related_change_id_ticket` on((`_ticket_problem`.`related_change_id` = `change_related_change_id_ticket`.`id`))) on((`_ticket`.`id` = `_ticket_problem`.`id`))) where (coalesce((`person_caller_id_contact`.`finalclass` = 'Person'),1) and coalesce((`team_team_id_contact`.`finalclass` = 'Team'),1) and coalesce((`person_agent_id_contact`.`finalclass` = 'Person'),1) and coalesce((`_ticket`.`finalclass` = 'Problem'),1) and coalesce((`change_related_change_id_ticket`.`finalclass` in ('RoutineChange','ApprovedChange','NormalChange','EmergencyChange','Change')),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_providercontract`
--

/*!50001 DROP VIEW IF EXISTS `view_providercontract`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_providercontract` AS select distinct `_contract`.`id` AS `id`,`_contract`.`name` AS `name`,`_contract`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `organization_name`,`_contract`.`description` AS `description`,`_contract`.`start_date` AS `start_date`,`_contract`.`end_date` AS `end_date`,`_contract`.`cost` AS `cost`,`_contract`.`cost_currency` AS `cost_currency`,`_contract`.`contracttype_id` AS `contracttype_id`,`contracttype_contracttype_id_typology`.`name` AS `contracttype_name`,`_contract`.`billing_frequency` AS `billing_frequency`,`_contract`.`cost_unit` AS `cost_unit`,`_contract`.`provider_id` AS `provider_id`,`organization_provider_id_organization`.`name` AS `provider_name`,`_contract`.`status` AS `status`,`_providercontract`.`sla` AS `sla`,`_providercontract`.`coverage` AS `coverage`,`_contract`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_contract`.`name`,'')) as char charset utf8) AS `friendlyname`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,cast(concat(coalesce(`contracttype_contracttype_id_typology`.`name`,'')) as char charset utf8) AS `contracttype_id_friendlyname`,cast(concat(coalesce(`organization_provider_id_organization`.`name`,'')) as char charset utf8) AS `provider_id_friendlyname`,coalesce((`organization_provider_id_organization`.`status` = 'inactive'),0) AS `provider_id_obsolescence_flag` from ((((`contract` `_contract` join `organization` `organization_org_id_organization` on((`_contract`.`org_id` = `organization_org_id_organization`.`id`))) left join `typology` `contracttype_contracttype_id_typology` on((`_contract`.`contracttype_id` = `contracttype_contracttype_id_typology`.`id`))) join `organization` `organization_provider_id_organization` on((`_contract`.`provider_id` = `organization_provider_id_organization`.`id`))) join `providercontract` `_providercontract` on((`_contract`.`id` = `_providercontract`.`id`))) where (coalesce((`contracttype_contracttype_id_typology`.`finalclass` = 'ContractType'),1) and coalesce((`_contract`.`finalclass` = 'ProviderContract'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_rack`
--

/*!50001 DROP VIEW IF EXISTS `view_rack`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_rack` AS select distinct `_functionalci`.`id` AS `id`,`_functionalci`.`name` AS `name`,`_functionalci`.`description` AS `description`,`_functionalci`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `organization_name`,`_functionalci`.`business_criticity` AS `business_criticity`,`_functionalci`.`move2production` AS `move2production`,`_physicaldevice`.`serialnumber` AS `serialnumber`,`_physicaldevice`.`location_id` AS `location_id`,`location_location_id_location`.`name` AS `location_name`,`_physicaldevice`.`status` AS `status`,`_physicaldevice`.`brand_id` AS `brand_id`,`brand_brand_id_typology`.`name` AS `brand_name`,`_physicaldevice`.`model_id` AS `model_id`,`model_model_id_typology`.`name` AS `model_name`,`_physicaldevice`.`asset_number` AS `asset_number`,`_physicaldevice`.`purchase_date` AS `purchase_date`,`_physicaldevice`.`end_of_warranty` AS `end_of_warranty`,`_rack`.`nb_u` AS `nb_u`,`_functionalci`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_functionalci`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce((`_physicaldevice`.`status` = 'obsolete'),0) AS `obsolescence_flag`,`_functionalci`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,cast(concat(coalesce(`location_location_id_location`.`name`,'')) as char charset utf8) AS `location_id_friendlyname`,coalesce((`location_location_id_location`.`status` = 'inactive'),0) AS `location_id_obsolescence_flag`,cast(concat(coalesce(`brand_brand_id_typology`.`name`,'')) as char charset utf8) AS `brand_id_friendlyname`,cast(concat(coalesce(`model_model_id_typology`.`name`,'')) as char charset utf8) AS `model_id_friendlyname` from (((`functionalci` `_functionalci` join `organization` `organization_org_id_organization` on((`_functionalci`.`org_id` = `organization_org_id_organization`.`id`))) join (((`physicaldevice` `_physicaldevice` left join `location` `location_location_id_location` on((`_physicaldevice`.`location_id` = `location_location_id_location`.`id`))) left join `typology` `brand_brand_id_typology` on((`_physicaldevice`.`brand_id` = `brand_brand_id_typology`.`id`))) left join `typology` `model_model_id_typology` on((`_physicaldevice`.`model_id` = `model_model_id_typology`.`id`))) on((`_functionalci`.`id` = `_physicaldevice`.`id`))) join `rack` `_rack` on((`_functionalci`.`id` = `_rack`.`id`))) where (coalesce((`_functionalci`.`finalclass` = 'Rack'),1) and coalesce((`brand_brand_id_typology`.`finalclass` = 'Brand'),1) and coalesce((`model_model_id_typology`.`finalclass` = 'Model'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_routinechange`
--

/*!50001 DROP VIEW IF EXISTS `view_routinechange`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_routinechange` AS select distinct `_ticket`.`id` AS `id`,`_ticket`.`operational_status` AS `operational_status`,`_ticket`.`ref` AS `ref`,`_ticket`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `org_name`,`_ticket`.`caller_id` AS `caller_id`,`person_caller_id_contact`.`name` AS `caller_name`,`_ticket`.`team_id` AS `team_id`,`team_team_id_contact`.`email` AS `team_name`,`_ticket`.`agent_id` AS `agent_id`,`person_agent_id_contact`.`name` AS `agent_name`,`_ticket`.`title` AS `title`,`_ticket`.`description` AS `description`,`_ticket`.`description_format` AS `description_format`,`_ticket`.`start_date` AS `start_date`,`_ticket`.`end_date` AS `end_date`,`_ticket`.`last_update` AS `last_update`,`_ticket`.`close_date` AS `close_date`,`_ticket`.`private_log` AS `private_log`,`_ticket`.`private_log_index` AS `private_log_index`,`_change`.`status` AS `status`,`_change`.`reason` AS `reason`,`_change`.`requestor_id` AS `requestor_id`,`person_requestor_id_contact`.`email` AS `requestor_email`,`_change`.`creation_date` AS `creation_date`,`_change`.`impact` AS `impact`,`_change`.`supervisor_group_id` AS `supervisor_group_id`,`team_supervisor_group_id_contact`.`name` AS `supervisor_group_name`,`_change`.`supervisor_id` AS `supervisor_id`,`person_supervisor_id_contact`.`email` AS `supervisor_email`,`_change`.`manager_group_id` AS `manager_group_id`,`team_manager_group_id_contact`.`name` AS `manager_group_name`,`_change`.`manager_id` AS `manager_id`,`person_manager_id_contact`.`email` AS `manager_email`,`_change`.`outage` AS `outage`,`_change`.`fallback` AS `fallback`,`_change`.`parent_id` AS `parent_id`,`change_parent_id_ticket`.`ref` AS `parent_name`,`_ticket`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_ticket`.`ref`,'')) as char charset utf8) AS `friendlyname`,coalesce(((`_ticket`.`operational_status` = 'closed') and ((`_ticket`.`close_date` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00')) or (isnull(`_ticket`.`close_date`) and (`_ticket`.`last_update` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00'))))),0) AS `obsolescence_flag`,`_ticket`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,cast(concat(coalesce(`person_caller_id_person`.`first_name`,''),coalesce(' ',''),coalesce(`person_caller_id_contact`.`name`,'')) as char charset utf8) AS `caller_id_friendlyname`,coalesce((`person_caller_id_contact`.`status` = 'inactive'),0) AS `caller_id_obsolescence_flag`,cast(concat(coalesce(`team_team_id_contact`.`name`,'')) as char charset utf8) AS `team_id_friendlyname`,coalesce((`team_team_id_contact`.`status` = 'inactive'),0) AS `team_id_obsolescence_flag`,cast(concat(coalesce(`person_agent_id_person`.`first_name`,''),coalesce(' ',''),coalesce(`person_agent_id_contact`.`name`,'')) as char charset utf8) AS `agent_id_friendlyname`,coalesce((`person_agent_id_contact`.`status` = 'inactive'),0) AS `agent_id_obsolescence_flag`,cast(concat(coalesce(`person_requestor_id_person`.`first_name`,''),coalesce(' ',''),coalesce(`person_requestor_id_contact`.`name`,'')) as char charset utf8) AS `requestor_id_friendlyname`,coalesce((`person_requestor_id_contact`.`status` = 'inactive'),0) AS `requestor_id_obsolescence_flag`,cast(concat(coalesce(`team_supervisor_group_id_contact`.`name`,'')) as char charset utf8) AS `supervisor_group_id_friendlyname`,coalesce((`team_supervisor_group_id_contact`.`status` = 'inactive'),0) AS `supervisor_group_id_obsolescence_flag`,cast(concat(coalesce(`person_supervisor_id_person`.`first_name`,''),coalesce(' ',''),coalesce(`person_supervisor_id_contact`.`name`,'')) as char charset utf8) AS `supervisor_id_friendlyname`,coalesce((`person_supervisor_id_contact`.`status` = 'inactive'),0) AS `supervisor_id_obsolescence_flag`,cast(concat(coalesce(`team_manager_group_id_contact`.`name`,'')) as char charset utf8) AS `manager_group_id_friendlyname`,coalesce((`team_manager_group_id_contact`.`status` = 'inactive'),0) AS `manager_group_id_obsolescence_flag`,cast(concat(coalesce(`person_manager_id_person`.`first_name`,''),coalesce(' ',''),coalesce(`person_manager_id_contact`.`name`,'')) as char charset utf8) AS `manager_id_friendlyname`,coalesce((`person_manager_id_contact`.`status` = 'inactive'),0) AS `manager_id_obsolescence_flag`,cast(concat(coalesce(`change_parent_id_ticket`.`ref`,'')) as char charset utf8) AS `parent_id_friendlyname`,`change_parent_id_ticket`.`finalclass` AS `parent_id_finalclass_recall`,coalesce(((`change_parent_id_ticket`.`operational_status` = 'closed') and ((`change_parent_id_ticket`.`close_date` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00')) or (isnull(`change_parent_id_ticket`.`close_date`) and (`change_parent_id_ticket`.`last_update` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00'))))),0) AS `parent_id_obsolescence_flag` from (((((`ticket` `_ticket` join `organization` `organization_org_id_organization` on((`_ticket`.`org_id` = `organization_org_id_organization`.`id`))) left join (`contact` `person_caller_id_contact` join `person` `person_caller_id_person` on((`person_caller_id_contact`.`id` = `person_caller_id_person`.`id`))) on((`_ticket`.`caller_id` = `person_caller_id_contact`.`id`))) left join `contact` `team_team_id_contact` on((`_ticket`.`team_id` = `team_team_id_contact`.`id`))) left join (`contact` `person_agent_id_contact` join `person` `person_agent_id_person` on((`person_agent_id_contact`.`id` = `person_agent_id_person`.`id`))) on((`_ticket`.`agent_id` = `person_agent_id_contact`.`id`))) join ((((((`change` `_change` left join (`contact` `person_requestor_id_contact` join `person` `person_requestor_id_person` on((`person_requestor_id_contact`.`id` = `person_requestor_id_person`.`id`))) on((`_change`.`requestor_id` = `person_requestor_id_contact`.`id`))) left join `contact` `team_supervisor_group_id_contact` on((`_change`.`supervisor_group_id` = `team_supervisor_group_id_contact`.`id`))) left join (`contact` `person_supervisor_id_contact` join `person` `person_supervisor_id_person` on((`person_supervisor_id_contact`.`id` = `person_supervisor_id_person`.`id`))) on((`_change`.`supervisor_id` = `person_supervisor_id_contact`.`id`))) left join `contact` `team_manager_group_id_contact` on((`_change`.`manager_group_id` = `team_manager_group_id_contact`.`id`))) left join (`contact` `person_manager_id_contact` join `person` `person_manager_id_person` on((`person_manager_id_contact`.`id` = `person_manager_id_person`.`id`))) on((`_change`.`manager_id` = `person_manager_id_contact`.`id`))) left join `ticket` `change_parent_id_ticket` on((`_change`.`parent_id` = `change_parent_id_ticket`.`id`))) on((`_ticket`.`id` = `_change`.`id`))) where (coalesce((`person_caller_id_contact`.`finalclass` = 'Person'),1) and coalesce((`team_team_id_contact`.`finalclass` = 'Team'),1) and coalesce((`person_agent_id_contact`.`finalclass` = 'Person'),1) and coalesce((`_ticket`.`finalclass` = 'RoutineChange'),1) and coalesce((`person_requestor_id_contact`.`finalclass` = 'Person'),1) and coalesce((`team_supervisor_group_id_contact`.`finalclass` = 'Team'),1) and coalesce((`person_supervisor_id_contact`.`finalclass` = 'Person'),1) and coalesce((`team_manager_group_id_contact`.`finalclass` = 'Team'),1) and coalesce((`person_manager_id_contact`.`finalclass` = 'Person'),1) and coalesce((`change_parent_id_ticket`.`finalclass` in ('RoutineChange','ApprovedChange','NormalChange','EmergencyChange','Change')),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_sanswitch`
--

/*!50001 DROP VIEW IF EXISTS `view_sanswitch`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_sanswitch` AS select distinct `_functionalci`.`id` AS `id`,`_functionalci`.`name` AS `name`,`_functionalci`.`description` AS `description`,`_functionalci`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `organization_name`,`_functionalci`.`business_criticity` AS `business_criticity`,`_functionalci`.`move2production` AS `move2production`,`_physicaldevice`.`serialnumber` AS `serialnumber`,`_physicaldevice`.`location_id` AS `location_id`,`location_location_id_location`.`name` AS `location_name`,`_physicaldevice`.`status` AS `status`,`_physicaldevice`.`brand_id` AS `brand_id`,`brand_brand_id_typology`.`name` AS `brand_name`,`_physicaldevice`.`model_id` AS `model_id`,`model_model_id_typology`.`name` AS `model_name`,`_physicaldevice`.`asset_number` AS `asset_number`,`_physicaldevice`.`purchase_date` AS `purchase_date`,`_physicaldevice`.`end_of_warranty` AS `end_of_warranty`,`_datacenterdevice`.`rack_id` AS `rack_id`,`rack_rack_id_functionalci`.`name` AS `rack_name`,`_datacenterdevice`.`enclosure_id` AS `enclosure_id`,`enclosure_enclosure_id_functionalci`.`name` AS `enclosure_name`,`_datacenterdevice`.`nb_u` AS `nb_u`,`_datacenterdevice`.`managementip` AS `managementip`,`_datacenterdevice`.`powera_id` AS `powerA_id`,`powerconnection_powera_id_functionalci`.`name` AS `powerA_name`,`_datacenterdevice`.`powerB_id` AS `powerB_id`,`powerconnection_powerb_id_functionalci`.`name` AS `powerB_name`,`_datacenterdevice`.`redundancy` AS `redundancy`,`_functionalci`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_functionalci`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce((`_physicaldevice`.`status` = 'obsolete'),0) AS `obsolescence_flag`,`_functionalci`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,cast(concat(coalesce(`location_location_id_location`.`name`,'')) as char charset utf8) AS `location_id_friendlyname`,coalesce((`location_location_id_location`.`status` = 'inactive'),0) AS `location_id_obsolescence_flag`,cast(concat(coalesce(`brand_brand_id_typology`.`name`,'')) as char charset utf8) AS `brand_id_friendlyname`,cast(concat(coalesce(`model_model_id_typology`.`name`,'')) as char charset utf8) AS `model_id_friendlyname`,cast(concat(coalesce(`rack_rack_id_functionalci`.`name`,'')) as char charset utf8) AS `rack_id_friendlyname`,coalesce((`rack_rack_id_physicaldevice`.`status` = 'obsolete'),0) AS `rack_id_obsolescence_flag`,cast(concat(coalesce(`enclosure_enclosure_id_functionalci`.`name`,'')) as char charset utf8) AS `enclosure_id_friendlyname`,coalesce((`enclosure_enclosure_id_physicaldevice`.`status` = 'obsolete'),0) AS `enclosure_id_obsolescence_flag`,cast(concat(coalesce(`powerconnection_powera_id_functionalci`.`name`,'')) as char charset utf8) AS `powerA_id_friendlyname`,`powerconnection_powera_id_functionalci`.`finalclass` AS `powerA_id_finalclass_recall`,coalesce((`powerconnection_powera_id_physicaldevice`.`status` = 'obsolete'),0) AS `powerA_id_obsolescence_flag`,cast(concat(coalesce(`powerconnection_powerb_id_functionalci`.`name`,'')) as char charset utf8) AS `powerB_id_friendlyname`,`powerconnection_powerb_id_functionalci`.`finalclass` AS `powerB_id_finalclass_recall`,coalesce((`powerconnection_powerb_id_physicaldevice`.`status` = 'obsolete'),0) AS `powerB_id_obsolescence_flag` from (((`functionalci` `_functionalci` join `organization` `organization_org_id_organization` on((`_functionalci`.`org_id` = `organization_org_id_organization`.`id`))) join (((`physicaldevice` `_physicaldevice` left join `location` `location_location_id_location` on((`_physicaldevice`.`location_id` = `location_location_id_location`.`id`))) left join `typology` `brand_brand_id_typology` on((`_physicaldevice`.`brand_id` = `brand_brand_id_typology`.`id`))) left join `typology` `model_model_id_typology` on((`_physicaldevice`.`model_id` = `model_model_id_typology`.`id`))) on((`_functionalci`.`id` = `_physicaldevice`.`id`))) join ((((`datacenterdevice` `_datacenterdevice` left join (`functionalci` `rack_rack_id_functionalci` join `physicaldevice` `rack_rack_id_physicaldevice` on((`rack_rack_id_functionalci`.`id` = `rack_rack_id_physicaldevice`.`id`))) on((`_datacenterdevice`.`rack_id` = `rack_rack_id_functionalci`.`id`))) left join (`functionalci` `enclosure_enclosure_id_functionalci` join `physicaldevice` `enclosure_enclosure_id_physicaldevice` on((`enclosure_enclosure_id_functionalci`.`id` = `enclosure_enclosure_id_physicaldevice`.`id`))) on((`_datacenterdevice`.`enclosure_id` = `enclosure_enclosure_id_functionalci`.`id`))) left join (`functionalci` `powerconnection_powera_id_functionalci` join `physicaldevice` `powerconnection_powera_id_physicaldevice` on((`powerconnection_powera_id_functionalci`.`id` = `powerconnection_powera_id_physicaldevice`.`id`))) on((`_datacenterdevice`.`powera_id` = `powerconnection_powera_id_functionalci`.`id`))) left join (`functionalci` `powerconnection_powerb_id_functionalci` join `physicaldevice` `powerconnection_powerb_id_physicaldevice` on((`powerconnection_powerb_id_functionalci`.`id` = `powerconnection_powerb_id_physicaldevice`.`id`))) on((`_datacenterdevice`.`powerB_id` = `powerconnection_powerb_id_functionalci`.`id`))) on((`_functionalci`.`id` = `_datacenterdevice`.`id`))) where (coalesce((`_functionalci`.`finalclass` = 'SANSwitch'),1) and coalesce((`brand_brand_id_typology`.`finalclass` = 'Brand'),1) and coalesce((`model_model_id_typology`.`finalclass` = 'Model'),1) and coalesce((`rack_rack_id_functionalci`.`finalclass` = 'Rack'),1) and coalesce((`enclosure_enclosure_id_functionalci`.`finalclass` = 'Enclosure'),1) and coalesce((`powerconnection_powera_id_functionalci`.`finalclass` in ('PowerSource','PDU','PowerConnection')),1) and coalesce((`powerconnection_powerb_id_functionalci`.`finalclass` in ('PowerSource','PDU','PowerConnection')),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_server`
--

/*!50001 DROP VIEW IF EXISTS `view_server`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_server` AS select distinct `_functionalci`.`id` AS `id`,`_functionalci`.`name` AS `name`,`_functionalci`.`description` AS `description`,`_functionalci`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `organization_name`,`_functionalci`.`business_criticity` AS `business_criticity`,`_functionalci`.`move2production` AS `move2production`,`_physicaldevice`.`serialnumber` AS `serialnumber`,`_physicaldevice`.`location_id` AS `location_id`,`location_location_id_location`.`name` AS `location_name`,`_physicaldevice`.`status` AS `status`,`_physicaldevice`.`brand_id` AS `brand_id`,`brand_brand_id_typology`.`name` AS `brand_name`,`_physicaldevice`.`model_id` AS `model_id`,`model_model_id_typology`.`name` AS `model_name`,`_physicaldevice`.`asset_number` AS `asset_number`,`_physicaldevice`.`purchase_date` AS `purchase_date`,`_physicaldevice`.`end_of_warranty` AS `end_of_warranty`,`_datacenterdevice`.`rack_id` AS `rack_id`,`rack_rack_id_functionalci`.`name` AS `rack_name`,`_datacenterdevice`.`enclosure_id` AS `enclosure_id`,`enclosure_enclosure_id_functionalci`.`name` AS `enclosure_name`,`_datacenterdevice`.`nb_u` AS `nb_u`,`_datacenterdevice`.`managementip` AS `managementip`,`_datacenterdevice`.`powera_id` AS `powerA_id`,`powerconnection_powera_id_functionalci`.`name` AS `powerA_name`,`_datacenterdevice`.`powerB_id` AS `powerB_id`,`powerconnection_powerb_id_functionalci`.`name` AS `powerB_name`,`_datacenterdevice`.`redundancy` AS `redundancy`,`_server`.`osfamily_id` AS `osfamily_id`,`osfamily_osfamily_id_typology`.`name` AS `osfamily_name`,`_server`.`osversion_id` AS `osversion_id`,`osversion_osversion_id_typology`.`name` AS `osversion_name`,`_server`.`oslicence_id` AS `oslicence_id`,`oslicence_oslicence_id_licence`.`name` AS `oslicence_name`,`_server`.`cpu` AS `cpu`,`_server`.`ram` AS `ram`,`_functionalci`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_functionalci`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce((`_physicaldevice`.`status` = 'obsolete'),0) AS `obsolescence_flag`,`_functionalci`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,cast(concat(coalesce(`location_location_id_location`.`name`,'')) as char charset utf8) AS `location_id_friendlyname`,coalesce((`location_location_id_location`.`status` = 'inactive'),0) AS `location_id_obsolescence_flag`,cast(concat(coalesce(`brand_brand_id_typology`.`name`,'')) as char charset utf8) AS `brand_id_friendlyname`,cast(concat(coalesce(`model_model_id_typology`.`name`,'')) as char charset utf8) AS `model_id_friendlyname`,cast(concat(coalesce(`rack_rack_id_functionalci`.`name`,'')) as char charset utf8) AS `rack_id_friendlyname`,coalesce((`rack_rack_id_physicaldevice`.`status` = 'obsolete'),0) AS `rack_id_obsolescence_flag`,cast(concat(coalesce(`enclosure_enclosure_id_functionalci`.`name`,'')) as char charset utf8) AS `enclosure_id_friendlyname`,coalesce((`enclosure_enclosure_id_physicaldevice`.`status` = 'obsolete'),0) AS `enclosure_id_obsolescence_flag`,cast(concat(coalesce(`powerconnection_powera_id_functionalci`.`name`,'')) as char charset utf8) AS `powerA_id_friendlyname`,`powerconnection_powera_id_functionalci`.`finalclass` AS `powerA_id_finalclass_recall`,coalesce((`powerconnection_powera_id_physicaldevice`.`status` = 'obsolete'),0) AS `powerA_id_obsolescence_flag`,cast(concat(coalesce(`powerconnection_powerb_id_functionalci`.`name`,'')) as char charset utf8) AS `powerB_id_friendlyname`,`powerconnection_powerb_id_functionalci`.`finalclass` AS `powerB_id_finalclass_recall`,coalesce((`powerconnection_powerb_id_physicaldevice`.`status` = 'obsolete'),0) AS `powerB_id_obsolescence_flag`,cast(concat(coalesce(`osfamily_osfamily_id_typology`.`name`,'')) as char charset utf8) AS `osfamily_id_friendlyname`,cast(concat(coalesce(`osversion_osversion_id_typology`.`name`,'')) as char charset utf8) AS `osversion_id_friendlyname`,cast(concat(coalesce(`oslicence_oslicence_id_licence`.`name`,'')) as char charset utf8) AS `oslicence_id_friendlyname`,coalesce(((`oslicence_oslicence_id_licence`.`perpetual` = 'no') and (isnull(`oslicence_oslicence_id_licence`.`end_date`) = 0) and (`oslicence_oslicence_id_licence`.`end_date` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00'))),0) AS `oslicence_id_obsolescence_flag` from ((((`functionalci` `_functionalci` join `organization` `organization_org_id_organization` on((`_functionalci`.`org_id` = `organization_org_id_organization`.`id`))) join (((`physicaldevice` `_physicaldevice` left join `location` `location_location_id_location` on((`_physicaldevice`.`location_id` = `location_location_id_location`.`id`))) left join `typology` `brand_brand_id_typology` on((`_physicaldevice`.`brand_id` = `brand_brand_id_typology`.`id`))) left join `typology` `model_model_id_typology` on((`_physicaldevice`.`model_id` = `model_model_id_typology`.`id`))) on((`_functionalci`.`id` = `_physicaldevice`.`id`))) join ((((`datacenterdevice` `_datacenterdevice` left join (`functionalci` `rack_rack_id_functionalci` join `physicaldevice` `rack_rack_id_physicaldevice` on((`rack_rack_id_functionalci`.`id` = `rack_rack_id_physicaldevice`.`id`))) on((`_datacenterdevice`.`rack_id` = `rack_rack_id_functionalci`.`id`))) left join (`functionalci` `enclosure_enclosure_id_functionalci` join `physicaldevice` `enclosure_enclosure_id_physicaldevice` on((`enclosure_enclosure_id_functionalci`.`id` = `enclosure_enclosure_id_physicaldevice`.`id`))) on((`_datacenterdevice`.`enclosure_id` = `enclosure_enclosure_id_functionalci`.`id`))) left join (`functionalci` `powerconnection_powera_id_functionalci` join `physicaldevice` `powerconnection_powera_id_physicaldevice` on((`powerconnection_powera_id_functionalci`.`id` = `powerconnection_powera_id_physicaldevice`.`id`))) on((`_datacenterdevice`.`powera_id` = `powerconnection_powera_id_functionalci`.`id`))) left join (`functionalci` `powerconnection_powerb_id_functionalci` join `physicaldevice` `powerconnection_powerb_id_physicaldevice` on((`powerconnection_powerb_id_functionalci`.`id` = `powerconnection_powerb_id_physicaldevice`.`id`))) on((`_datacenterdevice`.`powerB_id` = `powerconnection_powerb_id_functionalci`.`id`))) on((`_functionalci`.`id` = `_datacenterdevice`.`id`))) join (((`server` `_server` left join `typology` `osfamily_osfamily_id_typology` on((`_server`.`osfamily_id` = `osfamily_osfamily_id_typology`.`id`))) left join `typology` `osversion_osversion_id_typology` on((`_server`.`osversion_id` = `osversion_osversion_id_typology`.`id`))) left join `licence` `oslicence_oslicence_id_licence` on((`_server`.`oslicence_id` = `oslicence_oslicence_id_licence`.`id`))) on((`_functionalci`.`id` = `_server`.`id`))) where (coalesce((`_functionalci`.`finalclass` = 'Server'),1) and coalesce((`brand_brand_id_typology`.`finalclass` = 'Brand'),1) and coalesce((`model_model_id_typology`.`finalclass` = 'Model'),1) and coalesce((`rack_rack_id_functionalci`.`finalclass` = 'Rack'),1) and coalesce((`enclosure_enclosure_id_functionalci`.`finalclass` = 'Enclosure'),1) and coalesce((`powerconnection_powera_id_functionalci`.`finalclass` in ('PowerSource','PDU','PowerConnection')),1) and coalesce((`powerconnection_powerb_id_functionalci`.`finalclass` in ('PowerSource','PDU','PowerConnection')),1) and coalesce((`osfamily_osfamily_id_typology`.`finalclass` = 'OSFamily'),1) and coalesce((`osversion_osversion_id_typology`.`finalclass` = 'OSVersion'),1) and coalesce((`oslicence_oslicence_id_licence`.`finalclass` = 'OSLicence'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_service`
--

/*!50001 DROP VIEW IF EXISTS `view_service`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_service` AS select distinct `_service`.`id` AS `id`,`_service`.`name` AS `name`,`_service`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `organization_name`,`_service`.`servicefamily_id` AS `servicefamily_id`,`servicefamily_servicefamily_id_servicefamilly`.`name` AS `servicefamily_name`,`_service`.`description` AS `description`,`_service`.`status` AS `status`,`_service`.`icon_mimetype` AS `icon`,`_service`.`icon_data` AS `icon_data`,`_service`.`icon_filename` AS `icon_filename`,cast(concat(coalesce(`_service`.`name`,'')) as char charset utf8) AS `friendlyname`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,cast(concat(coalesce(`servicefamily_servicefamily_id_servicefamilly`.`name`,'')) as char charset utf8) AS `servicefamily_id_friendlyname` from ((`service` `_service` join `organization` `organization_org_id_organization` on((`_service`.`org_id` = `organization_org_id_organization`.`id`))) left join `servicefamilly` `servicefamily_servicefamily_id_servicefamilly` on((`_service`.`servicefamily_id` = `servicefamily_servicefamily_id_servicefamilly`.`id`))) where 1 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_servicefamily`
--

/*!50001 DROP VIEW IF EXISTS `view_servicefamily`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_servicefamily` AS select distinct `_servicefamilly`.`id` AS `id`,`_servicefamilly`.`name` AS `name`,`_servicefamilly`.`icon_mimetype` AS `icon`,`_servicefamilly`.`icon_data` AS `icon_data`,`_servicefamilly`.`icon_filename` AS `icon_filename`,cast(concat(coalesce(`_servicefamilly`.`name`,'')) as char charset utf8) AS `friendlyname` from `servicefamilly` `_servicefamilly` where 1 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_servicesubcategory`
--

/*!50001 DROP VIEW IF EXISTS `view_servicesubcategory`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_servicesubcategory` AS select distinct `_servicesubcategory`.`id` AS `id`,`_servicesubcategory`.`name` AS `name`,`_servicesubcategory`.`description` AS `description`,`_servicesubcategory`.`service_id` AS `service_id`,`service_service_id_service`.`org_id` AS `service_org_id`,`service_service_id_service`.`name` AS `service_name`,`organization_org_id_organization`.`name` AS `service_provider`,`_servicesubcategory`.`request_type` AS `request_type`,`_servicesubcategory`.`status` AS `status`,cast(concat(coalesce(`_servicesubcategory`.`name`,'')) as char charset utf8) AS `friendlyname`,cast(concat(coalesce(`service_service_id_service`.`name`,'')) as char charset utf8) AS `service_id_friendlyname`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `service_org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `service_org_id_obsolescence_flag` from (`servicesubcategory` `_servicesubcategory` join (`service` `service_service_id_service` join `organization` `organization_org_id_organization` on((`service_service_id_service`.`org_id` = `organization_org_id_organization`.`id`))) on((`_servicesubcategory`.`service_id` = `service_service_id_service`.`id`))) where 1 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_sla`
--

/*!50001 DROP VIEW IF EXISTS `view_sla`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_sla` AS select distinct `_sla`.`id` AS `id`,`_sla`.`name` AS `name`,`_sla`.`description` AS `description`,`_sla`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `organization_name`,cast(concat(coalesce(`_sla`.`name`,'')) as char charset utf8) AS `friendlyname`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag` from (`sla` `_sla` join `organization` `organization_org_id_organization` on((`_sla`.`org_id` = `organization_org_id_organization`.`id`))) where 1 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_slt`
--

/*!50001 DROP VIEW IF EXISTS `view_slt`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_slt` AS select distinct `_slt`.`id` AS `id`,`_slt`.`name` AS `name`,`_slt`.`priority` AS `priority`,`_slt`.`request_type` AS `request_type`,`_slt`.`metric` AS `metric`,`_slt`.`value` AS `value`,`_slt`.`unit` AS `unit`,cast(concat(coalesce(`_slt`.`name`,'')) as char charset utf8) AS `friendlyname` from `slt` `_slt` where 1 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_software`
--

/*!50001 DROP VIEW IF EXISTS `view_software`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_software` AS select distinct `_software`.`id` AS `id`,`_software`.`name` AS `name`,`_software`.`vendor` AS `vendor`,`_software`.`version` AS `version`,`_software`.`type` AS `type`,cast(concat(coalesce(`_software`.`name`,''),coalesce(' ',''),coalesce(`_software`.`version`,'')) as char charset utf8) AS `friendlyname` from `software` `_software` where 1 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_softwareinstance`
--

/*!50001 DROP VIEW IF EXISTS `view_softwareinstance`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_softwareinstance` AS select distinct `_functionalci`.`id` AS `id`,`_functionalci`.`name` AS `name`,`_functionalci`.`description` AS `description`,`_functionalci`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `organization_name`,`_functionalci`.`business_criticity` AS `business_criticity`,`_functionalci`.`move2production` AS `move2production`,`_softwareinstance`.`functionalci_id` AS `system_id`,`functionalci_system_id_functionalci`.`name` AS `system_name`,`_softwareinstance`.`software_id` AS `software_id`,`software_software_id_software`.`name` AS `software_name`,`_softwareinstance`.`softwarelicence_id` AS `softwarelicence_id`,`softwarelicence_softwarelicence_id_licence`.`name` AS `softwarelicence_name`,`_softwareinstance`.`path` AS `path`,`_softwareinstance`.`status` AS `status`,`_functionalci`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_functionalci`.`name`,''),coalesce(' ',''),coalesce(`functionalci_system_id_functionalci`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce((`_softwareinstance`.`status` = 'inactive'),0) AS `obsolescence_flag`,`_functionalci`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,if((`functionalci_system_id_functionalci`.`finalclass` in ('Middleware','DBServer','WebServer','PCSoftware','OtherSoftware')),cast(concat(coalesce(`functionalci_system_id_functionalci`.`name`,''),coalesce(' ',''),coalesce(`functionalci_system_id1_functionalci`.`name`,'')) as char charset utf8),cast(concat(coalesce(`functionalci_system_id_functionalci`.`name`,'')) as char charset utf8)) AS `system_id_friendlyname`,`functionalci_system_id_functionalci`.`finalclass` AS `system_id_finalclass_recall`,if((`functionalci_system_id_functionalci`.`finalclass` = 'FunctionalCI'),coalesce(0,0),if((`functionalci_system_id_functionalci`.`finalclass` in ('Hypervisor','Farm','VirtualMachine')),coalesce((`functionalci_system_id_fn_virtualdevice_virtualdevice`.`status` = 'obsolete'),0),if((`functionalci_system_id_functionalci`.`finalclass` = 'WebApplication'),coalesce(coalesce((`webserver_webserver_id_softwareinstance`.`status` = 'inactive'),0),0),if((`functionalci_system_id_functionalci`.`finalclass` = 'DatabaseSchema'),coalesce(coalesce((`dbserver_dbserver_id_softwareinstance`.`status` = 'inactive'),0),0),if((`functionalci_system_id_functionalci`.`finalclass` = 'MiddlewareInstance'),coalesce(coalesce((`middleware_middleware_id_softwareinstance`.`status` = 'inactive'),0),0),if((`functionalci_system_id_functionalci`.`finalclass` in ('Middleware','DBServer','WebServer','PCSoftware','OtherSoftware')),coalesce((`functionalci_system_id_fn_softwareinstance_softwareinstance`.`status` = 'inactive'),0),if((`functionalci_system_id_functionalci`.`finalclass` = 'BusinessProcess'),coalesce((`functionalci_system_id_fn_businessprocess_businessprocess`.`status` = 'inactive'),0),if((`functionalci_system_id_functionalci`.`finalclass` = 'ApplicationSolution'),coalesce((`functionalci_system_id_fn_applicationsolution_applicationsolution`.`status` = 'inactive'),0),coalesce((`functionalci_system_id_fn_physicaldevice_physicaldevice`.`status` = 'obsolete'),0))))))))) AS `system_id_obsolescence_flag`,cast(concat(coalesce(`software_software_id_software`.`name`,''),coalesce(' ',''),coalesce(`software_software_id_software`.`version`,'')) as char charset utf8) AS `software_id_friendlyname`,cast(concat(coalesce(`softwarelicence_softwarelicence_id_licence`.`name`,'')) as char charset utf8) AS `softwarelicence_id_friendlyname`,coalesce(((`softwarelicence_softwarelicence_id_licence`.`perpetual` = 'no') and (isnull(`softwarelicence_softwarelicence_id_licence`.`end_date`) = 0) and (`softwarelicence_softwarelicence_id_licence`.`end_date` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00'))),0) AS `softwarelicence_id_obsolescence_flag` from ((`functionalci` `_functionalci` join `organization` `organization_org_id_organization` on((`_functionalci`.`org_id` = `organization_org_id_organization`.`id`))) join (((`softwareinstance` `_softwareinstance` join ((((((((`functionalci` `functionalci_system_id_functionalci` left join (`softwareinstance` `functionalci_system_id_fn_softwareinstance_softwareinstance` join `functionalci` `functionalci_system_id1_functionalci` on((`functionalci_system_id_fn_softwareinstance_softwareinstance`.`functionalci_id` = `functionalci_system_id1_functionalci`.`id`))) on((`functionalci_system_id_functionalci`.`id` = `functionalci_system_id_fn_softwareinstance_softwareinstance`.`id`))) left join `virtualdevice` `functionalci_system_id_fn_virtualdevice_virtualdevice` on((`functionalci_system_id_functionalci`.`id` = `functionalci_system_id_fn_virtualdevice_virtualdevice`.`id`))) left join (`webapplication` `functionalci_system_id_fn_webapplication_webapplication` join (`functionalci` `webserver_webserver_id_functionalci` join `softwareinstance` `webserver_webserver_id_softwareinstance` on((`webserver_webserver_id_functionalci`.`id` = `webserver_webserver_id_softwareinstance`.`id`))) on((`functionalci_system_id_fn_webapplication_webapplication`.`webserver_id` = `webserver_webserver_id_functionalci`.`id`))) on((`functionalci_system_id_functionalci`.`id` = `functionalci_system_id_fn_webapplication_webapplication`.`id`))) left join (`databaseschema` `functionalci_system_id_fn_databaseschema_databaseschema` join (`functionalci` `dbserver_dbserver_id_functionalci` join `softwareinstance` `dbserver_dbserver_id_softwareinstance` on((`dbserver_dbserver_id_functionalci`.`id` = `dbserver_dbserver_id_softwareinstance`.`id`))) on((`functionalci_system_id_fn_databaseschema_databaseschema`.`dbserver_id` = `dbserver_dbserver_id_functionalci`.`id`))) on((`functionalci_system_id_functionalci`.`id` = `functionalci_system_id_fn_databaseschema_databaseschema`.`id`))) left join (`middlewareinstance` `functionalci_system_id_fn_middlewareinstance_middlewareinstance` join (`functionalci` `middleware_middleware_id_functionalci` join `softwareinstance` `middleware_middleware_id_softwareinstance` on((`middleware_middleware_id_functionalci`.`id` = `middleware_middleware_id_softwareinstance`.`id`))) on((`functionalci_system_id_fn_middlewareinstance_middlewareinstance`.`middleware_id` = `middleware_middleware_id_functionalci`.`id`))) on((`functionalci_system_id_functionalci`.`id` = `functionalci_system_id_fn_middlewareinstance_middlewareinstance`.`id`))) left join `businessprocess` `functionalci_system_id_fn_businessprocess_businessprocess` on((`functionalci_system_id_functionalci`.`id` = `functionalci_system_id_fn_businessprocess_businessprocess`.`id`))) left join `applicationsolution` `functionalci_system_id_fn_applicationsolution_applicationsolution` on((`functionalci_system_id_functionalci`.`id` = `functionalci_system_id_fn_applicationsolution_applicationsolution`.`id`))) left join `physicaldevice` `functionalci_system_id_fn_physicaldevice_physicaldevice` on((`functionalci_system_id_functionalci`.`id` = `functionalci_system_id_fn_physicaldevice_physicaldevice`.`id`))) on((`_softwareinstance`.`functionalci_id` = `functionalci_system_id_functionalci`.`id`))) left join `software` `software_software_id_software` on((`_softwareinstance`.`software_id` = `software_software_id_software`.`id`))) left join `licence` `softwarelicence_softwarelicence_id_licence` on((`_softwareinstance`.`softwarelicence_id` = `softwarelicence_softwarelicence_id_licence`.`id`))) on((`_functionalci`.`id` = `_softwareinstance`.`id`))) where (coalesce((`_functionalci`.`finalclass` in ('Middleware','DBServer','WebServer','PCSoftware','OtherSoftware','SoftwareInstance')),1) and coalesce((`webserver_webserver_id_functionalci`.`finalclass` = 'WebServer'),1) and coalesce((`dbserver_dbserver_id_functionalci`.`finalclass` = 'DBServer'),1) and coalesce((`middleware_middleware_id_functionalci`.`finalclass` = 'Middleware'),1) and coalesce((`softwarelicence_softwarelicence_id_licence`.`finalclass` = 'SoftwareLicence'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_softwarelicence`
--

/*!50001 DROP VIEW IF EXISTS `view_softwarelicence`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_softwarelicence` AS select distinct `_licence`.`id` AS `id`,`_licence`.`name` AS `name`,`_licence`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `organization_name`,`_licence`.`usage_limit` AS `usage_limit`,`_licence`.`description` AS `description`,`_licence`.`start_date` AS `start_date`,`_licence`.`end_date` AS `end_date`,`_licence`.`licence_key` AS `licence_key`,`_licence`.`perpetual` AS `perpetual`,`_softwarelicence`.`software_id` AS `software_id`,`software_software_id_software`.`name` AS `software_name`,`_licence`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_licence`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce(((`_licence`.`perpetual` = 'no') and (isnull(`_licence`.`end_date`) = 0) and (`_licence`.`end_date` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00'))),0) AS `obsolescence_flag`,`_licence`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,cast(concat(coalesce(`software_software_id_software`.`name`,''),coalesce(' ',''),coalesce(`software_software_id_software`.`version`,'')) as char charset utf8) AS `software_id_friendlyname` from ((`licence` `_licence` join `organization` `organization_org_id_organization` on((`_licence`.`org_id` = `organization_org_id_organization`.`id`))) join (`softwarelicence` `_softwarelicence` join `software` `software_software_id_software` on((`_softwarelicence`.`software_id` = `software_software_id_software`.`id`))) on((`_licence`.`id` = `_softwarelicence`.`id`))) where coalesce((`_licence`.`finalclass` = 'SoftwareLicence'),1) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_softwarepatch`
--

/*!50001 DROP VIEW IF EXISTS `view_softwarepatch`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_softwarepatch` AS select distinct `_patch`.`id` AS `id`,`_patch`.`name` AS `name`,`_patch`.`description` AS `description`,`_softwarepatch`.`software_id` AS `software_id`,`software_software_id_software`.`name` AS `software_name`,`_patch`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_patch`.`name`,'')) as char charset utf8) AS `friendlyname`,cast(concat(coalesce(`software_software_id_software`.`name`,''),coalesce(' ',''),coalesce(`software_software_id_software`.`version`,'')) as char charset utf8) AS `software_id_friendlyname` from (`patch` `_patch` join (`softwarepatch` `_softwarepatch` join `software` `software_software_id_software` on((`_softwarepatch`.`software_id` = `software_software_id_software`.`id`))) on((`_patch`.`id` = `_softwarepatch`.`id`))) where coalesce((`_patch`.`finalclass` = 'SoftwarePatch'),1) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_storagesystem`
--

/*!50001 DROP VIEW IF EXISTS `view_storagesystem`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_storagesystem` AS select distinct `_functionalci`.`id` AS `id`,`_functionalci`.`name` AS `name`,`_functionalci`.`description` AS `description`,`_functionalci`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `organization_name`,`_functionalci`.`business_criticity` AS `business_criticity`,`_functionalci`.`move2production` AS `move2production`,`_physicaldevice`.`serialnumber` AS `serialnumber`,`_physicaldevice`.`location_id` AS `location_id`,`location_location_id_location`.`name` AS `location_name`,`_physicaldevice`.`status` AS `status`,`_physicaldevice`.`brand_id` AS `brand_id`,`brand_brand_id_typology`.`name` AS `brand_name`,`_physicaldevice`.`model_id` AS `model_id`,`model_model_id_typology`.`name` AS `model_name`,`_physicaldevice`.`asset_number` AS `asset_number`,`_physicaldevice`.`purchase_date` AS `purchase_date`,`_physicaldevice`.`end_of_warranty` AS `end_of_warranty`,`_datacenterdevice`.`rack_id` AS `rack_id`,`rack_rack_id_functionalci`.`name` AS `rack_name`,`_datacenterdevice`.`enclosure_id` AS `enclosure_id`,`enclosure_enclosure_id_functionalci`.`name` AS `enclosure_name`,`_datacenterdevice`.`nb_u` AS `nb_u`,`_datacenterdevice`.`managementip` AS `managementip`,`_datacenterdevice`.`powera_id` AS `powerA_id`,`powerconnection_powera_id_functionalci`.`name` AS `powerA_name`,`_datacenterdevice`.`powerB_id` AS `powerB_id`,`powerconnection_powerb_id_functionalci`.`name` AS `powerB_name`,`_datacenterdevice`.`redundancy` AS `redundancy`,`_functionalci`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_functionalci`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce((`_physicaldevice`.`status` = 'obsolete'),0) AS `obsolescence_flag`,`_functionalci`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,cast(concat(coalesce(`location_location_id_location`.`name`,'')) as char charset utf8) AS `location_id_friendlyname`,coalesce((`location_location_id_location`.`status` = 'inactive'),0) AS `location_id_obsolescence_flag`,cast(concat(coalesce(`brand_brand_id_typology`.`name`,'')) as char charset utf8) AS `brand_id_friendlyname`,cast(concat(coalesce(`model_model_id_typology`.`name`,'')) as char charset utf8) AS `model_id_friendlyname`,cast(concat(coalesce(`rack_rack_id_functionalci`.`name`,'')) as char charset utf8) AS `rack_id_friendlyname`,coalesce((`rack_rack_id_physicaldevice`.`status` = 'obsolete'),0) AS `rack_id_obsolescence_flag`,cast(concat(coalesce(`enclosure_enclosure_id_functionalci`.`name`,'')) as char charset utf8) AS `enclosure_id_friendlyname`,coalesce((`enclosure_enclosure_id_physicaldevice`.`status` = 'obsolete'),0) AS `enclosure_id_obsolescence_flag`,cast(concat(coalesce(`powerconnection_powera_id_functionalci`.`name`,'')) as char charset utf8) AS `powerA_id_friendlyname`,`powerconnection_powera_id_functionalci`.`finalclass` AS `powerA_id_finalclass_recall`,coalesce((`powerconnection_powera_id_physicaldevice`.`status` = 'obsolete'),0) AS `powerA_id_obsolescence_flag`,cast(concat(coalesce(`powerconnection_powerb_id_functionalci`.`name`,'')) as char charset utf8) AS `powerB_id_friendlyname`,`powerconnection_powerb_id_functionalci`.`finalclass` AS `powerB_id_finalclass_recall`,coalesce((`powerconnection_powerb_id_physicaldevice`.`status` = 'obsolete'),0) AS `powerB_id_obsolescence_flag` from (((`functionalci` `_functionalci` join `organization` `organization_org_id_organization` on((`_functionalci`.`org_id` = `organization_org_id_organization`.`id`))) join (((`physicaldevice` `_physicaldevice` left join `location` `location_location_id_location` on((`_physicaldevice`.`location_id` = `location_location_id_location`.`id`))) left join `typology` `brand_brand_id_typology` on((`_physicaldevice`.`brand_id` = `brand_brand_id_typology`.`id`))) left join `typology` `model_model_id_typology` on((`_physicaldevice`.`model_id` = `model_model_id_typology`.`id`))) on((`_functionalci`.`id` = `_physicaldevice`.`id`))) join ((((`datacenterdevice` `_datacenterdevice` left join (`functionalci` `rack_rack_id_functionalci` join `physicaldevice` `rack_rack_id_physicaldevice` on((`rack_rack_id_functionalci`.`id` = `rack_rack_id_physicaldevice`.`id`))) on((`_datacenterdevice`.`rack_id` = `rack_rack_id_functionalci`.`id`))) left join (`functionalci` `enclosure_enclosure_id_functionalci` join `physicaldevice` `enclosure_enclosure_id_physicaldevice` on((`enclosure_enclosure_id_functionalci`.`id` = `enclosure_enclosure_id_physicaldevice`.`id`))) on((`_datacenterdevice`.`enclosure_id` = `enclosure_enclosure_id_functionalci`.`id`))) left join (`functionalci` `powerconnection_powera_id_functionalci` join `physicaldevice` `powerconnection_powera_id_physicaldevice` on((`powerconnection_powera_id_functionalci`.`id` = `powerconnection_powera_id_physicaldevice`.`id`))) on((`_datacenterdevice`.`powera_id` = `powerconnection_powera_id_functionalci`.`id`))) left join (`functionalci` `powerconnection_powerb_id_functionalci` join `physicaldevice` `powerconnection_powerb_id_physicaldevice` on((`powerconnection_powerb_id_functionalci`.`id` = `powerconnection_powerb_id_physicaldevice`.`id`))) on((`_datacenterdevice`.`powerB_id` = `powerconnection_powerb_id_functionalci`.`id`))) on((`_functionalci`.`id` = `_datacenterdevice`.`id`))) where (coalesce((`_functionalci`.`finalclass` = 'StorageSystem'),1) and coalesce((`brand_brand_id_typology`.`finalclass` = 'Brand'),1) and coalesce((`model_model_id_typology`.`finalclass` = 'Model'),1) and coalesce((`rack_rack_id_functionalci`.`finalclass` = 'Rack'),1) and coalesce((`enclosure_enclosure_id_functionalci`.`finalclass` = 'Enclosure'),1) and coalesce((`powerconnection_powera_id_functionalci`.`finalclass` in ('PowerSource','PDU','PowerConnection')),1) and coalesce((`powerconnection_powerb_id_functionalci`.`finalclass` in ('PowerSource','PDU','PowerConnection')),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_subnet`
--

/*!50001 DROP VIEW IF EXISTS `view_subnet`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_subnet` AS select distinct `_subnet`.`id` AS `id`,`_subnet`.`description` AS `description`,`_subnet`.`subnet_name` AS `subnet_name`,`_subnet`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `org_name`,`_subnet`.`ip` AS `ip`,`_subnet`.`ip_mask` AS `ip_mask`,cast(concat(coalesce(`_subnet`.`ip`,''),coalesce(' ',''),coalesce(`_subnet`.`ip_mask`,'')) as char charset utf8) AS `friendlyname`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag` from (`subnet` `_subnet` join `organization` `organization_org_id_organization` on((`_subnet`.`org_id` = `organization_org_id_organization`.`id`))) where 1 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_tablet`
--

/*!50001 DROP VIEW IF EXISTS `view_tablet`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_tablet` AS select distinct `_functionalci`.`id` AS `id`,`_functionalci`.`name` AS `name`,`_functionalci`.`description` AS `description`,`_functionalci`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `organization_name`,`_functionalci`.`business_criticity` AS `business_criticity`,`_functionalci`.`move2production` AS `move2production`,`_physicaldevice`.`serialnumber` AS `serialnumber`,`_physicaldevice`.`location_id` AS `location_id`,`location_location_id_location`.`name` AS `location_name`,`_physicaldevice`.`status` AS `status`,`_physicaldevice`.`brand_id` AS `brand_id`,`brand_brand_id_typology`.`name` AS `brand_name`,`_physicaldevice`.`model_id` AS `model_id`,`model_model_id_typology`.`name` AS `model_name`,`_physicaldevice`.`asset_number` AS `asset_number`,`_physicaldevice`.`purchase_date` AS `purchase_date`,`_physicaldevice`.`end_of_warranty` AS `end_of_warranty`,`_functionalci`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_functionalci`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce((`_physicaldevice`.`status` = 'obsolete'),0) AS `obsolescence_flag`,`_functionalci`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,cast(concat(coalesce(`location_location_id_location`.`name`,'')) as char charset utf8) AS `location_id_friendlyname`,coalesce((`location_location_id_location`.`status` = 'inactive'),0) AS `location_id_obsolescence_flag`,cast(concat(coalesce(`brand_brand_id_typology`.`name`,'')) as char charset utf8) AS `brand_id_friendlyname`,cast(concat(coalesce(`model_model_id_typology`.`name`,'')) as char charset utf8) AS `model_id_friendlyname` from ((`functionalci` `_functionalci` join `organization` `organization_org_id_organization` on((`_functionalci`.`org_id` = `organization_org_id_organization`.`id`))) join (((`physicaldevice` `_physicaldevice` left join `location` `location_location_id_location` on((`_physicaldevice`.`location_id` = `location_location_id_location`.`id`))) left join `typology` `brand_brand_id_typology` on((`_physicaldevice`.`brand_id` = `brand_brand_id_typology`.`id`))) left join `typology` `model_model_id_typology` on((`_physicaldevice`.`model_id` = `model_model_id_typology`.`id`))) on((`_functionalci`.`id` = `_physicaldevice`.`id`))) where (coalesce((`_functionalci`.`finalclass` = 'Tablet'),1) and coalesce((`brand_brand_id_typology`.`finalclass` = 'Brand'),1) and coalesce((`model_model_id_typology`.`finalclass` = 'Model'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_tape`
--

/*!50001 DROP VIEW IF EXISTS `view_tape`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_tape` AS select distinct `_tape`.`id` AS `id`,`_tape`.`name` AS `name`,`_tape`.`description` AS `description`,`_tape`.`size` AS `size`,`_tape`.`tapelibrary_id` AS `tapelibrary_id`,`tapelibrary_tapelibrary_id_functionalci`.`name` AS `tapelibrary_name`,cast(concat(coalesce(`_tape`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce(coalesce((`tapelibrary_tapelibrary_id_physicaldevice`.`status` = 'obsolete'),0),0) AS `obsolescence_flag`,`_tape`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`tapelibrary_tapelibrary_id_functionalci`.`name`,'')) as char charset utf8) AS `tapelibrary_id_friendlyname`,coalesce((`tapelibrary_tapelibrary_id_physicaldevice`.`status` = 'obsolete'),0) AS `tapelibrary_id_obsolescence_flag` from (`tape` `_tape` join (`functionalci` `tapelibrary_tapelibrary_id_functionalci` join `physicaldevice` `tapelibrary_tapelibrary_id_physicaldevice` on((`tapelibrary_tapelibrary_id_functionalci`.`id` = `tapelibrary_tapelibrary_id_physicaldevice`.`id`))) on((`_tape`.`tapelibrary_id` = `tapelibrary_tapelibrary_id_functionalci`.`id`))) where coalesce((`tapelibrary_tapelibrary_id_functionalci`.`finalclass` = 'TapeLibrary'),1) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_tapelibrary`
--

/*!50001 DROP VIEW IF EXISTS `view_tapelibrary`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_tapelibrary` AS select distinct `_functionalci`.`id` AS `id`,`_functionalci`.`name` AS `name`,`_functionalci`.`description` AS `description`,`_functionalci`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `organization_name`,`_functionalci`.`business_criticity` AS `business_criticity`,`_functionalci`.`move2production` AS `move2production`,`_physicaldevice`.`serialnumber` AS `serialnumber`,`_physicaldevice`.`location_id` AS `location_id`,`location_location_id_location`.`name` AS `location_name`,`_physicaldevice`.`status` AS `status`,`_physicaldevice`.`brand_id` AS `brand_id`,`brand_brand_id_typology`.`name` AS `brand_name`,`_physicaldevice`.`model_id` AS `model_id`,`model_model_id_typology`.`name` AS `model_name`,`_physicaldevice`.`asset_number` AS `asset_number`,`_physicaldevice`.`purchase_date` AS `purchase_date`,`_physicaldevice`.`end_of_warranty` AS `end_of_warranty`,`_datacenterdevice`.`rack_id` AS `rack_id`,`rack_rack_id_functionalci`.`name` AS `rack_name`,`_datacenterdevice`.`enclosure_id` AS `enclosure_id`,`enclosure_enclosure_id_functionalci`.`name` AS `enclosure_name`,`_datacenterdevice`.`nb_u` AS `nb_u`,`_datacenterdevice`.`managementip` AS `managementip`,`_datacenterdevice`.`powera_id` AS `powerA_id`,`powerconnection_powera_id_functionalci`.`name` AS `powerA_name`,`_datacenterdevice`.`powerB_id` AS `powerB_id`,`powerconnection_powerb_id_functionalci`.`name` AS `powerB_name`,`_datacenterdevice`.`redundancy` AS `redundancy`,`_functionalci`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_functionalci`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce((`_physicaldevice`.`status` = 'obsolete'),0) AS `obsolescence_flag`,`_functionalci`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,cast(concat(coalesce(`location_location_id_location`.`name`,'')) as char charset utf8) AS `location_id_friendlyname`,coalesce((`location_location_id_location`.`status` = 'inactive'),0) AS `location_id_obsolescence_flag`,cast(concat(coalesce(`brand_brand_id_typology`.`name`,'')) as char charset utf8) AS `brand_id_friendlyname`,cast(concat(coalesce(`model_model_id_typology`.`name`,'')) as char charset utf8) AS `model_id_friendlyname`,cast(concat(coalesce(`rack_rack_id_functionalci`.`name`,'')) as char charset utf8) AS `rack_id_friendlyname`,coalesce((`rack_rack_id_physicaldevice`.`status` = 'obsolete'),0) AS `rack_id_obsolescence_flag`,cast(concat(coalesce(`enclosure_enclosure_id_functionalci`.`name`,'')) as char charset utf8) AS `enclosure_id_friendlyname`,coalesce((`enclosure_enclosure_id_physicaldevice`.`status` = 'obsolete'),0) AS `enclosure_id_obsolescence_flag`,cast(concat(coalesce(`powerconnection_powera_id_functionalci`.`name`,'')) as char charset utf8) AS `powerA_id_friendlyname`,`powerconnection_powera_id_functionalci`.`finalclass` AS `powerA_id_finalclass_recall`,coalesce((`powerconnection_powera_id_physicaldevice`.`status` = 'obsolete'),0) AS `powerA_id_obsolescence_flag`,cast(concat(coalesce(`powerconnection_powerb_id_functionalci`.`name`,'')) as char charset utf8) AS `powerB_id_friendlyname`,`powerconnection_powerb_id_functionalci`.`finalclass` AS `powerB_id_finalclass_recall`,coalesce((`powerconnection_powerb_id_physicaldevice`.`status` = 'obsolete'),0) AS `powerB_id_obsolescence_flag` from (((`functionalci` `_functionalci` join `organization` `organization_org_id_organization` on((`_functionalci`.`org_id` = `organization_org_id_organization`.`id`))) join (((`physicaldevice` `_physicaldevice` left join `location` `location_location_id_location` on((`_physicaldevice`.`location_id` = `location_location_id_location`.`id`))) left join `typology` `brand_brand_id_typology` on((`_physicaldevice`.`brand_id` = `brand_brand_id_typology`.`id`))) left join `typology` `model_model_id_typology` on((`_physicaldevice`.`model_id` = `model_model_id_typology`.`id`))) on((`_functionalci`.`id` = `_physicaldevice`.`id`))) join ((((`datacenterdevice` `_datacenterdevice` left join (`functionalci` `rack_rack_id_functionalci` join `physicaldevice` `rack_rack_id_physicaldevice` on((`rack_rack_id_functionalci`.`id` = `rack_rack_id_physicaldevice`.`id`))) on((`_datacenterdevice`.`rack_id` = `rack_rack_id_functionalci`.`id`))) left join (`functionalci` `enclosure_enclosure_id_functionalci` join `physicaldevice` `enclosure_enclosure_id_physicaldevice` on((`enclosure_enclosure_id_functionalci`.`id` = `enclosure_enclosure_id_physicaldevice`.`id`))) on((`_datacenterdevice`.`enclosure_id` = `enclosure_enclosure_id_functionalci`.`id`))) left join (`functionalci` `powerconnection_powera_id_functionalci` join `physicaldevice` `powerconnection_powera_id_physicaldevice` on((`powerconnection_powera_id_functionalci`.`id` = `powerconnection_powera_id_physicaldevice`.`id`))) on((`_datacenterdevice`.`powera_id` = `powerconnection_powera_id_functionalci`.`id`))) left join (`functionalci` `powerconnection_powerb_id_functionalci` join `physicaldevice` `powerconnection_powerb_id_physicaldevice` on((`powerconnection_powerb_id_functionalci`.`id` = `powerconnection_powerb_id_physicaldevice`.`id`))) on((`_datacenterdevice`.`powerB_id` = `powerconnection_powerb_id_functionalci`.`id`))) on((`_functionalci`.`id` = `_datacenterdevice`.`id`))) where (coalesce((`_functionalci`.`finalclass` = 'TapeLibrary'),1) and coalesce((`brand_brand_id_typology`.`finalclass` = 'Brand'),1) and coalesce((`model_model_id_typology`.`finalclass` = 'Model'),1) and coalesce((`rack_rack_id_functionalci`.`finalclass` = 'Rack'),1) and coalesce((`enclosure_enclosure_id_functionalci`.`finalclass` = 'Enclosure'),1) and coalesce((`powerconnection_powera_id_functionalci`.`finalclass` in ('PowerSource','PDU','PowerConnection')),1) and coalesce((`powerconnection_powerb_id_functionalci`.`finalclass` in ('PowerSource','PDU','PowerConnection')),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_team`
--

/*!50001 DROP VIEW IF EXISTS `view_team`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_team` AS select distinct `_contact`.`id` AS `id`,`_contact`.`name` AS `name`,`_contact`.`status` AS `status`,`_contact`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `org_name`,`_contact`.`email` AS `email`,`_contact`.`phone` AS `phone`,`_contact`.`notify` AS `notify`,`_contact`.`function` AS `function`,`_contact`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_contact`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce((`_contact`.`status` = 'inactive'),0) AS `obsolescence_flag`,`_contact`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag` from (`contact` `_contact` join `organization` `organization_org_id_organization` on((`_contact`.`org_id` = `organization_org_id_organization`.`id`))) where coalesce((`_contact`.`finalclass` = 'Team'),1) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_telephonyci`
--

/*!50001 DROP VIEW IF EXISTS `view_telephonyci`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_telephonyci` AS select distinct `_functionalci`.`id` AS `id`,`_functionalci`.`name` AS `name`,`_functionalci`.`description` AS `description`,`_functionalci`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `organization_name`,`_functionalci`.`business_criticity` AS `business_criticity`,`_functionalci`.`move2production` AS `move2production`,`_physicaldevice`.`serialnumber` AS `serialnumber`,`_physicaldevice`.`location_id` AS `location_id`,`location_location_id_location`.`name` AS `location_name`,`_physicaldevice`.`status` AS `status`,`_physicaldevice`.`brand_id` AS `brand_id`,`brand_brand_id_typology`.`name` AS `brand_name`,`_physicaldevice`.`model_id` AS `model_id`,`model_model_id_typology`.`name` AS `model_name`,`_physicaldevice`.`asset_number` AS `asset_number`,`_physicaldevice`.`purchase_date` AS `purchase_date`,`_physicaldevice`.`end_of_warranty` AS `end_of_warranty`,`_telephonyci`.`phonenumber` AS `phonenumber`,`_functionalci`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_functionalci`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce((`_physicaldevice`.`status` = 'obsolete'),0) AS `obsolescence_flag`,`_functionalci`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,cast(concat(coalesce(`location_location_id_location`.`name`,'')) as char charset utf8) AS `location_id_friendlyname`,coalesce((`location_location_id_location`.`status` = 'inactive'),0) AS `location_id_obsolescence_flag`,cast(concat(coalesce(`brand_brand_id_typology`.`name`,'')) as char charset utf8) AS `brand_id_friendlyname`,cast(concat(coalesce(`model_model_id_typology`.`name`,'')) as char charset utf8) AS `model_id_friendlyname` from (((`functionalci` `_functionalci` join `organization` `organization_org_id_organization` on((`_functionalci`.`org_id` = `organization_org_id_organization`.`id`))) join (((`physicaldevice` `_physicaldevice` left join `location` `location_location_id_location` on((`_physicaldevice`.`location_id` = `location_location_id_location`.`id`))) left join `typology` `brand_brand_id_typology` on((`_physicaldevice`.`brand_id` = `brand_brand_id_typology`.`id`))) left join `typology` `model_model_id_typology` on((`_physicaldevice`.`model_id` = `model_model_id_typology`.`id`))) on((`_functionalci`.`id` = `_physicaldevice`.`id`))) join `telephonyci` `_telephonyci` on((`_functionalci`.`id` = `_telephonyci`.`id`))) where (coalesce((`_functionalci`.`finalclass` in ('Phone','MobilePhone','IPPhone','TelephonyCI')),1) and coalesce((`brand_brand_id_typology`.`finalclass` = 'Brand'),1) and coalesce((`model_model_id_typology`.`finalclass` = 'Model'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_ticket`
--

/*!50001 DROP VIEW IF EXISTS `view_ticket`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_ticket` AS select distinct `_ticket`.`id` AS `id`,`_ticket`.`operational_status` AS `operational_status`,`_ticket`.`ref` AS `ref`,`_ticket`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `org_name`,`_ticket`.`caller_id` AS `caller_id`,`person_caller_id_contact`.`name` AS `caller_name`,`_ticket`.`team_id` AS `team_id`,`team_team_id_contact`.`email` AS `team_name`,`_ticket`.`agent_id` AS `agent_id`,`person_agent_id_contact`.`name` AS `agent_name`,`_ticket`.`title` AS `title`,`_ticket`.`description` AS `description`,`_ticket`.`description_format` AS `description_format`,`_ticket`.`start_date` AS `start_date`,`_ticket`.`end_date` AS `end_date`,`_ticket`.`last_update` AS `last_update`,`_ticket`.`close_date` AS `close_date`,`_ticket`.`private_log` AS `private_log`,`_ticket`.`private_log_index` AS `private_log_index`,`_ticket`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_ticket`.`ref`,'')) as char charset utf8) AS `friendlyname`,coalesce(((`_ticket`.`operational_status` = 'closed') and ((`_ticket`.`close_date` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00')) or (isnull(`_ticket`.`close_date`) and (`_ticket`.`last_update` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00'))))),0) AS `obsolescence_flag`,`_ticket`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,cast(concat(coalesce(`person_caller_id_person`.`first_name`,''),coalesce(' ',''),coalesce(`person_caller_id_contact`.`name`,'')) as char charset utf8) AS `caller_id_friendlyname`,coalesce((`person_caller_id_contact`.`status` = 'inactive'),0) AS `caller_id_obsolescence_flag`,cast(concat(coalesce(`team_team_id_contact`.`name`,'')) as char charset utf8) AS `team_id_friendlyname`,coalesce((`team_team_id_contact`.`status` = 'inactive'),0) AS `team_id_obsolescence_flag`,cast(concat(coalesce(`person_agent_id_person`.`first_name`,''),coalesce(' ',''),coalesce(`person_agent_id_contact`.`name`,'')) as char charset utf8) AS `agent_id_friendlyname`,coalesce((`person_agent_id_contact`.`status` = 'inactive'),0) AS `agent_id_obsolescence_flag` from ((((`ticket` `_ticket` join `organization` `organization_org_id_organization` on((`_ticket`.`org_id` = `organization_org_id_organization`.`id`))) left join (`contact` `person_caller_id_contact` join `person` `person_caller_id_person` on((`person_caller_id_contact`.`id` = `person_caller_id_person`.`id`))) on((`_ticket`.`caller_id` = `person_caller_id_contact`.`id`))) left join `contact` `team_team_id_contact` on((`_ticket`.`team_id` = `team_team_id_contact`.`id`))) left join (`contact` `person_agent_id_contact` join `person` `person_agent_id_person` on((`person_agent_id_contact`.`id` = `person_agent_id_person`.`id`))) on((`_ticket`.`agent_id` = `person_agent_id_contact`.`id`))) where (coalesce((`person_caller_id_contact`.`finalclass` = 'Person'),1) and coalesce((`team_team_id_contact`.`finalclass` = 'Team'),1) and coalesce((`person_agent_id_contact`.`finalclass` = 'Person'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_typology`
--

/*!50001 DROP VIEW IF EXISTS `view_typology`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_typology` AS select distinct `_typology`.`id` AS `id`,`_typology`.`name` AS `name`,`_typology`.`finalclass` AS `finalclass`,if((`_typology`.`finalclass` = 'IOSVersion'),cast(concat(coalesce(`brand_brand_id_typology`.`name`,''),coalesce(' ',''),coalesce(`_typology`.`name`,'')) as char charset utf8),cast(concat(coalesce(`_typology`.`name`,'')) as char charset utf8)) AS `friendlyname` from (`typology` `_typology` left join (`iosversion` `_fn_iosversion_iosversion` join `typology` `brand_brand_id_typology` on((`_fn_iosversion_iosversion`.`brand_id` = `brand_brand_id_typology`.`id`))) on((`_typology`.`id` = `_fn_iosversion_iosversion`.`id`))) where coalesce((`brand_brand_id_typology`.`finalclass` = 'Brand'),1) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_userrequest`
--

/*!50001 DROP VIEW IF EXISTS `view_userrequest`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_userrequest` AS select distinct `_ticket`.`id` AS `id`,`_ticket`.`operational_status` AS `operational_status`,`_ticket`.`ref` AS `ref`,`_ticket`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `org_name`,`_ticket`.`caller_id` AS `caller_id`,`person_caller_id_contact`.`name` AS `caller_name`,`_ticket`.`team_id` AS `team_id`,`team_team_id_contact`.`email` AS `team_name`,`_ticket`.`agent_id` AS `agent_id`,`person_agent_id_contact`.`name` AS `agent_name`,`_ticket`.`title` AS `title`,`_ticket`.`description` AS `description`,`_ticket`.`description_format` AS `description_format`,`_ticket`.`start_date` AS `start_date`,`_ticket`.`end_date` AS `end_date`,`_ticket`.`last_update` AS `last_update`,`_ticket`.`close_date` AS `close_date`,`_ticket`.`private_log` AS `private_log`,`_ticket`.`private_log_index` AS `private_log_index`,`_ticket_request`.`status` AS `status`,`_ticket_request`.`request_type` AS `request_type`,`_ticket_request`.`impact` AS `impact`,`_ticket_request`.`priority` AS `priority`,`_ticket_request`.`urgency` AS `urgency`,`_ticket_request`.`origin` AS `origin`,`_ticket_request`.`approver_id` AS `approver_id`,`person_approver_id_contact`.`email` AS `approver_email`,`_ticket_request`.`service_id` AS `service_id`,`service_service_id_service`.`name` AS `service_name`,`_ticket_request`.`servicesubcategory_id` AS `servicesubcategory_id`,`servicesubcategory_servicesubcategory_id_servicesubcategory`.`name` AS `servicesubcategory_name`,`_ticket_request`.`escalation_flag` AS `escalation_flag`,`_ticket_request`.`escalation_reason` AS `escalation_reason`,`_ticket_request`.`assignment_date` AS `assignment_date`,`_ticket_request`.`resolution_date` AS `resolution_date`,`_ticket_request`.`last_pending_date` AS `last_pending_date`,`_ticket_request`.`cumulatedpending_timespent` AS `cumulatedpending`,`_ticket_request`.`cumulatedpending_started` AS `cumulatedpending_started`,`_ticket_request`.`cumulatedpending_laststart` AS `cumulatedpending_laststart`,`_ticket_request`.`cumulatedpending_stopped` AS `cumulatedpending_stopped`,`_ticket_request`.`tto_timespent` AS `tto`,`_ticket_request`.`tto_started` AS `tto_started`,`_ticket_request`.`tto_laststart` AS `tto_laststart`,`_ticket_request`.`tto_stopped` AS `tto_stopped`,`_ticket_request`.`tto_75_deadline` AS `tto_75_deadline`,`_ticket_request`.`tto_75_passed` AS `tto_75_passed`,`_ticket_request`.`tto_75_triggered` AS `tto_75_triggered`,`_ticket_request`.`tto_75_overrun` AS `tto_75_overrun`,`_ticket_request`.`tto_100_deadline` AS `tto_100_deadline`,`_ticket_request`.`tto_100_passed` AS `tto_100_passed`,`_ticket_request`.`tto_100_triggered` AS `tto_100_triggered`,`_ticket_request`.`tto_100_overrun` AS `tto_100_overrun`,`_ticket_request`.`ttr_timespent` AS `ttr`,`_ticket_request`.`ttr_started` AS `ttr_started`,`_ticket_request`.`ttr_laststart` AS `ttr_laststart`,`_ticket_request`.`ttr_stopped` AS `ttr_stopped`,`_ticket_request`.`ttr_75_deadline` AS `ttr_75_deadline`,`_ticket_request`.`ttr_75_passed` AS `ttr_75_passed`,`_ticket_request`.`ttr_75_triggered` AS `ttr_75_triggered`,`_ticket_request`.`ttr_75_overrun` AS `ttr_75_overrun`,`_ticket_request`.`ttr_100_deadline` AS `ttr_100_deadline`,`_ticket_request`.`ttr_100_passed` AS `ttr_100_passed`,`_ticket_request`.`ttr_100_triggered` AS `ttr_100_triggered`,`_ticket_request`.`ttr_100_overrun` AS `ttr_100_overrun`,`_ticket_request`.`tto_100_deadline` AS `tto_escalation_deadline`,`_ticket_request`.`tto_100_passed` AS `sla_tto_passed`,`_ticket_request`.`tto_100_overrun` AS `sla_tto_over`,`_ticket_request`.`ttr_100_deadline` AS `ttr_escalation_deadline`,`_ticket_request`.`ttr_100_passed` AS `sla_ttr_passed`,`_ticket_request`.`ttr_100_overrun` AS `sla_ttr_over`,`_ticket_request`.`time_spent` AS `time_spent`,`_ticket_request`.`resolution_code` AS `resolution_code`,`_ticket_request`.`solution` AS `solution`,`_ticket_request`.`pending_reason` AS `pending_reason`,`_ticket_request`.`parent_request_id` AS `parent_request_id`,`userrequest_parent_request_id_ticket`.`ref` AS `parent_request_ref`,`_ticket_request`.`parent_incident_id` AS `parent_incident_id`,`incident_parent_incident_id_ticket`.`ref` AS `parent_incident_ref`,`_ticket_request`.`parent_problem_id` AS `parent_problem_id`,`problem_parent_problem_id_ticket`.`ref` AS `parent_problem_ref`,`_ticket_request`.`parent_change_id` AS `parent_change_id`,`change_parent_change_id_ticket`.`ref` AS `parent_change_ref`,`_ticket_request`.`public_log` AS `public_log`,`_ticket_request`.`public_log_index` AS `public_log_index`,`_ticket_request`.`user_satisfaction` AS `user_satisfaction`,`_ticket_request`.`user_commment` AS `user_comment`,`_ticket`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_ticket`.`ref`,'')) as char charset utf8) AS `friendlyname`,coalesce(((`_ticket`.`operational_status` = 'closed') and ((`_ticket`.`close_date` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00')) or (isnull(`_ticket`.`close_date`) and (`_ticket`.`last_update` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00'))))),0) AS `obsolescence_flag`,`_ticket`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,cast(concat(coalesce(`person_caller_id_person`.`first_name`,''),coalesce(' ',''),coalesce(`person_caller_id_contact`.`name`,'')) as char charset utf8) AS `caller_id_friendlyname`,coalesce((`person_caller_id_contact`.`status` = 'inactive'),0) AS `caller_id_obsolescence_flag`,cast(concat(coalesce(`team_team_id_contact`.`name`,'')) as char charset utf8) AS `team_id_friendlyname`,coalesce((`team_team_id_contact`.`status` = 'inactive'),0) AS `team_id_obsolescence_flag`,cast(concat(coalesce(`person_agent_id_person`.`first_name`,''),coalesce(' ',''),coalesce(`person_agent_id_contact`.`name`,'')) as char charset utf8) AS `agent_id_friendlyname`,coalesce((`person_agent_id_contact`.`status` = 'inactive'),0) AS `agent_id_obsolescence_flag`,cast(concat(coalesce(`person_approver_id_person`.`first_name`,''),coalesce(' ',''),coalesce(`person_approver_id_contact`.`name`,'')) as char charset utf8) AS `approver_id_friendlyname`,coalesce((`person_approver_id_contact`.`status` = 'inactive'),0) AS `approver_id_obsolescence_flag`,cast(concat(coalesce(`service_service_id_service`.`name`,'')) as char charset utf8) AS `service_id_friendlyname`,cast(concat(coalesce(`servicesubcategory_servicesubcategory_id_servicesubcategory`.`name`,'')) as char charset utf8) AS `servicesubcategory_id_friendlyname`,cast(concat(coalesce(`userrequest_parent_request_id_ticket`.`ref`,'')) as char charset utf8) AS `parent_request_id_friendlyname`,coalesce(((`userrequest_parent_request_id_ticket`.`operational_status` = 'closed') and ((`userrequest_parent_request_id_ticket`.`close_date` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00')) or (isnull(`userrequest_parent_request_id_ticket`.`close_date`) and (`userrequest_parent_request_id_ticket`.`last_update` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00'))))),0) AS `parent_request_id_obsolescence_flag`,cast(concat(coalesce(`incident_parent_incident_id_ticket`.`ref`,'')) as char charset utf8) AS `parent_incident_id_friendlyname`,coalesce(((`incident_parent_incident_id_ticket`.`operational_status` = 'closed') and ((`incident_parent_incident_id_ticket`.`close_date` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00')) or (isnull(`incident_parent_incident_id_ticket`.`close_date`) and (`incident_parent_incident_id_ticket`.`last_update` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00'))))),0) AS `parent_incident_id_obsolescence_flag`,cast(concat(coalesce(`problem_parent_problem_id_ticket`.`ref`,'')) as char charset utf8) AS `parent_problem_id_friendlyname`,coalesce(((`problem_parent_problem_id_ticket`.`operational_status` = 'closed') and ((`problem_parent_problem_id_ticket`.`close_date` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00')) or (isnull(`problem_parent_problem_id_ticket`.`close_date`) and (`problem_parent_problem_id_ticket`.`last_update` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00'))))),0) AS `parent_problem_id_obsolescence_flag`,cast(concat(coalesce(`change_parent_change_id_ticket`.`ref`,'')) as char charset utf8) AS `parent_change_id_friendlyname`,`change_parent_change_id_ticket`.`finalclass` AS `parent_change_id_finalclass_recall`,coalesce(((`change_parent_change_id_ticket`.`operational_status` = 'closed') and ((`change_parent_change_id_ticket`.`close_date` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00')) or (isnull(`change_parent_change_id_ticket`.`close_date`) and (`change_parent_change_id_ticket`.`last_update` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00'))))),0) AS `parent_change_id_obsolescence_flag` from (((((`ticket` `_ticket` join `organization` `organization_org_id_organization` on((`_ticket`.`org_id` = `organization_org_id_organization`.`id`))) left join (`contact` `person_caller_id_contact` join `person` `person_caller_id_person` on((`person_caller_id_contact`.`id` = `person_caller_id_person`.`id`))) on((`_ticket`.`caller_id` = `person_caller_id_contact`.`id`))) left join `contact` `team_team_id_contact` on((`_ticket`.`team_id` = `team_team_id_contact`.`id`))) left join (`contact` `person_agent_id_contact` join `person` `person_agent_id_person` on((`person_agent_id_contact`.`id` = `person_agent_id_person`.`id`))) on((`_ticket`.`agent_id` = `person_agent_id_contact`.`id`))) join (((((((`ticket_request` `_ticket_request` left join (`contact` `person_approver_id_contact` join `person` `person_approver_id_person` on((`person_approver_id_contact`.`id` = `person_approver_id_person`.`id`))) on((`_ticket_request`.`approver_id` = `person_approver_id_contact`.`id`))) left join `service` `service_service_id_service` on((`_ticket_request`.`service_id` = `service_service_id_service`.`id`))) left join `servicesubcategory` `servicesubcategory_servicesubcategory_id_servicesubcategory` on((`_ticket_request`.`servicesubcategory_id` = `servicesubcategory_servicesubcategory_id_servicesubcategory`.`id`))) left join `ticket` `userrequest_parent_request_id_ticket` on((`_ticket_request`.`parent_request_id` = `userrequest_parent_request_id_ticket`.`id`))) left join `ticket` `incident_parent_incident_id_ticket` on((`_ticket_request`.`parent_incident_id` = `incident_parent_incident_id_ticket`.`id`))) left join `ticket` `problem_parent_problem_id_ticket` on((`_ticket_request`.`parent_problem_id` = `problem_parent_problem_id_ticket`.`id`))) left join `ticket` `change_parent_change_id_ticket` on((`_ticket_request`.`parent_change_id` = `change_parent_change_id_ticket`.`id`))) on((`_ticket`.`id` = `_ticket_request`.`id`))) where (coalesce((`person_caller_id_contact`.`finalclass` = 'Person'),1) and coalesce((`team_team_id_contact`.`finalclass` = 'Team'),1) and coalesce((`person_agent_id_contact`.`finalclass` = 'Person'),1) and coalesce((`_ticket`.`finalclass` = 'UserRequest'),1) and coalesce((`person_approver_id_contact`.`finalclass` = 'Person'),1) and coalesce((`userrequest_parent_request_id_ticket`.`finalclass` = 'UserRequest'),1) and coalesce((`incident_parent_incident_id_ticket`.`finalclass` = 'Incident'),1) and coalesce((`problem_parent_problem_id_ticket`.`finalclass` = 'Problem'),1) and coalesce((`change_parent_change_id_ticket`.`finalclass` in ('RoutineChange','ApprovedChange','NormalChange','EmergencyChange','Change')),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_virtualdevice`
--

/*!50001 DROP VIEW IF EXISTS `view_virtualdevice`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_virtualdevice` AS select distinct `_functionalci`.`id` AS `id`,`_functionalci`.`name` AS `name`,`_functionalci`.`description` AS `description`,`_functionalci`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `organization_name`,`_functionalci`.`business_criticity` AS `business_criticity`,`_functionalci`.`move2production` AS `move2production`,`_virtualdevice`.`status` AS `status`,`_functionalci`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_functionalci`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce((`_virtualdevice`.`status` = 'obsolete'),0) AS `obsolescence_flag`,`_functionalci`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag` from ((`functionalci` `_functionalci` join `organization` `organization_org_id_organization` on((`_functionalci`.`org_id` = `organization_org_id_organization`.`id`))) join `virtualdevice` `_virtualdevice` on((`_functionalci`.`id` = `_virtualdevice`.`id`))) where coalesce((`_functionalci`.`finalclass` in ('VirtualHost','Hypervisor','Farm','VirtualMachine','VirtualDevice')),1) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_virtualhost`
--

/*!50001 DROP VIEW IF EXISTS `view_virtualhost`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_virtualhost` AS select distinct `_functionalci`.`id` AS `id`,`_functionalci`.`name` AS `name`,`_functionalci`.`description` AS `description`,`_functionalci`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `organization_name`,`_functionalci`.`business_criticity` AS `business_criticity`,`_functionalci`.`move2production` AS `move2production`,`_virtualdevice`.`status` AS `status`,`_functionalci`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_functionalci`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce((`_virtualdevice`.`status` = 'obsolete'),0) AS `obsolescence_flag`,`_functionalci`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag` from ((`functionalci` `_functionalci` join `organization` `organization_org_id_organization` on((`_functionalci`.`org_id` = `organization_org_id_organization`.`id`))) join `virtualdevice` `_virtualdevice` on((`_functionalci`.`id` = `_virtualdevice`.`id`))) where coalesce((`_functionalci`.`finalclass` in ('Hypervisor','Farm','VirtualHost')),1) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_virtualmachine`
--

/*!50001 DROP VIEW IF EXISTS `view_virtualmachine`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_virtualmachine` AS select distinct `_functionalci`.`id` AS `id`,`_functionalci`.`name` AS `name`,`_functionalci`.`description` AS `description`,`_functionalci`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `organization_name`,`_functionalci`.`business_criticity` AS `business_criticity`,`_functionalci`.`move2production` AS `move2production`,`_virtualdevice`.`status` AS `status`,`_virtualmachine`.`virtualhost_id` AS `virtualhost_id`,`virtualhost_virtualhost_id_functionalci`.`name` AS `virtualhost_name`,`_virtualmachine`.`osfamily_id` AS `osfamily_id`,`osfamily_osfamily_id_typology`.`name` AS `osfamily_name`,`_virtualmachine`.`osversion_id` AS `osversion_id`,`osversion_osversion_id_typology`.`name` AS `osversion_name`,`_virtualmachine`.`oslicence_id` AS `oslicence_id`,`oslicence_oslicence_id_licence`.`name` AS `oslicence_name`,`_virtualmachine`.`cpu` AS `cpu`,`_virtualmachine`.`ram` AS `ram`,`_virtualmachine`.`managementip` AS `managementip`,`_functionalci`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_functionalci`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce((`_virtualdevice`.`status` = 'obsolete'),0) AS `obsolescence_flag`,`_functionalci`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,cast(concat(coalesce(`virtualhost_virtualhost_id_functionalci`.`name`,'')) as char charset utf8) AS `virtualhost_id_friendlyname`,`virtualhost_virtualhost_id_functionalci`.`finalclass` AS `virtualhost_id_finalclass_recall`,coalesce((`virtualhost_virtualhost_id_virtualdevice`.`status` = 'obsolete'),0) AS `virtualhost_id_obsolescence_flag`,cast(concat(coalesce(`osfamily_osfamily_id_typology`.`name`,'')) as char charset utf8) AS `osfamily_id_friendlyname`,cast(concat(coalesce(`osversion_osversion_id_typology`.`name`,'')) as char charset utf8) AS `osversion_id_friendlyname`,cast(concat(coalesce(`oslicence_oslicence_id_licence`.`name`,'')) as char charset utf8) AS `oslicence_id_friendlyname`,coalesce(((`oslicence_oslicence_id_licence`.`perpetual` = 'no') and (isnull(`oslicence_oslicence_id_licence`.`end_date`) = 0) and (`oslicence_oslicence_id_licence`.`end_date` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00'))),0) AS `oslicence_id_obsolescence_flag` from (((`functionalci` `_functionalci` join `organization` `organization_org_id_organization` on((`_functionalci`.`org_id` = `organization_org_id_organization`.`id`))) join `virtualdevice` `_virtualdevice` on((`_functionalci`.`id` = `_virtualdevice`.`id`))) join ((((`virtualmachine` `_virtualmachine` join (`functionalci` `virtualhost_virtualhost_id_functionalci` join `virtualdevice` `virtualhost_virtualhost_id_virtualdevice` on((`virtualhost_virtualhost_id_functionalci`.`id` = `virtualhost_virtualhost_id_virtualdevice`.`id`))) on((`_virtualmachine`.`virtualhost_id` = `virtualhost_virtualhost_id_functionalci`.`id`))) left join `typology` `osfamily_osfamily_id_typology` on((`_virtualmachine`.`osfamily_id` = `osfamily_osfamily_id_typology`.`id`))) left join `typology` `osversion_osversion_id_typology` on((`_virtualmachine`.`osversion_id` = `osversion_osversion_id_typology`.`id`))) left join `licence` `oslicence_oslicence_id_licence` on((`_virtualmachine`.`oslicence_id` = `oslicence_oslicence_id_licence`.`id`))) on((`_functionalci`.`id` = `_virtualmachine`.`id`))) where (coalesce((`_functionalci`.`finalclass` = 'VirtualMachine'),1) and coalesce((`virtualhost_virtualhost_id_functionalci`.`finalclass` in ('Hypervisor','Farm','VirtualHost')),1) and coalesce((`osfamily_osfamily_id_typology`.`finalclass` = 'OSFamily'),1) and coalesce((`osversion_osversion_id_typology`.`finalclass` = 'OSVersion'),1) and coalesce((`oslicence_oslicence_id_licence`.`finalclass` = 'OSLicence'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_vlan`
--

/*!50001 DROP VIEW IF EXISTS `view_vlan`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_vlan` AS select distinct `_vlan`.`id` AS `id`,`_vlan`.`vlan_tag` AS `vlan_tag`,`_vlan`.`description` AS `description`,`_vlan`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `org_name`,cast(concat(coalesce(`_vlan`.`vlan_tag`,'')) as char charset utf8) AS `friendlyname`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag` from (`vlan` `_vlan` join `organization` `organization_org_id_organization` on((`_vlan`.`org_id` = `organization_org_id_organization`.`id`))) where 1 */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_webapplication`
--

/*!50001 DROP VIEW IF EXISTS `view_webapplication`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_webapplication` AS select distinct `_functionalci`.`id` AS `id`,`_functionalci`.`name` AS `name`,`_functionalci`.`description` AS `description`,`_functionalci`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `organization_name`,`_functionalci`.`business_criticity` AS `business_criticity`,`_functionalci`.`move2production` AS `move2production`,`_webapplication`.`webserver_id` AS `webserver_id`,`webserver_webserver_id_functionalci`.`name` AS `webserver_name`,`_webapplication`.`url` AS `url`,`_functionalci`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_functionalci`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce(coalesce((`webserver_webserver_id_softwareinstance`.`status` = 'inactive'),0),0) AS `obsolescence_flag`,`_functionalci`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,cast(concat(coalesce(`webserver_webserver_id_functionalci`.`name`,''),coalesce(' ',''),coalesce(`functionalci_system_id_functionalci`.`name`,'')) as char charset utf8) AS `webserver_id_friendlyname`,coalesce((`webserver_webserver_id_softwareinstance`.`status` = 'inactive'),0) AS `webserver_id_obsolescence_flag` from ((`functionalci` `_functionalci` join `organization` `organization_org_id_organization` on((`_functionalci`.`org_id` = `organization_org_id_organization`.`id`))) join (`webapplication` `_webapplication` join (`functionalci` `webserver_webserver_id_functionalci` join (`softwareinstance` `webserver_webserver_id_softwareinstance` join `functionalci` `functionalci_system_id_functionalci` on((`webserver_webserver_id_softwareinstance`.`functionalci_id` = `functionalci_system_id_functionalci`.`id`))) on((`webserver_webserver_id_functionalci`.`id` = `webserver_webserver_id_softwareinstance`.`id`))) on((`_webapplication`.`webserver_id` = `webserver_webserver_id_functionalci`.`id`))) on((`_functionalci`.`id` = `_webapplication`.`id`))) where (coalesce((`_functionalci`.`finalclass` = 'WebApplication'),1) and coalesce((`webserver_webserver_id_functionalci`.`finalclass` = 'WebServer'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_webserver`
--

/*!50001 DROP VIEW IF EXISTS `view_webserver`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_webserver` AS select distinct `_functionalci`.`id` AS `id`,`_functionalci`.`name` AS `name`,`_functionalci`.`description` AS `description`,`_functionalci`.`org_id` AS `org_id`,`organization_org_id_organization`.`name` AS `organization_name`,`_functionalci`.`business_criticity` AS `business_criticity`,`_functionalci`.`move2production` AS `move2production`,`_softwareinstance`.`functionalci_id` AS `system_id`,`functionalci_system_id_functionalci`.`name` AS `system_name`,`_softwareinstance`.`software_id` AS `software_id`,`software_software_id_software`.`name` AS `software_name`,`_softwareinstance`.`softwarelicence_id` AS `softwarelicence_id`,`softwarelicence_softwarelicence_id_licence`.`name` AS `softwarelicence_name`,`_softwareinstance`.`path` AS `path`,`_softwareinstance`.`status` AS `status`,`_functionalci`.`finalclass` AS `finalclass`,cast(concat(coalesce(`_functionalci`.`name`,''),coalesce(' ',''),coalesce(`functionalci_system_id_functionalci`.`name`,'')) as char charset utf8) AS `friendlyname`,coalesce((`_softwareinstance`.`status` = 'inactive'),0) AS `obsolescence_flag`,`_functionalci`.`obsolescence_date` AS `obsolescence_date`,cast(concat(coalesce(`organization_org_id_organization`.`name`,'')) as char charset utf8) AS `org_id_friendlyname`,coalesce((`organization_org_id_organization`.`status` = 'inactive'),0) AS `org_id_obsolescence_flag`,if((`functionalci_system_id_functionalci`.`finalclass` in ('Middleware','DBServer','WebServer','PCSoftware','OtherSoftware')),cast(concat(coalesce(`functionalci_system_id_functionalci`.`name`,''),coalesce(' ',''),coalesce(`functionalci_system_id1_functionalci`.`name`,'')) as char charset utf8),cast(concat(coalesce(`functionalci_system_id_functionalci`.`name`,'')) as char charset utf8)) AS `system_id_friendlyname`,`functionalci_system_id_functionalci`.`finalclass` AS `system_id_finalclass_recall`,if((`functionalci_system_id_functionalci`.`finalclass` = 'FunctionalCI'),coalesce(0,0),if((`functionalci_system_id_functionalci`.`finalclass` in ('Hypervisor','Farm','VirtualMachine')),coalesce((`functionalci_system_id_fn_virtualdevice_virtualdevice`.`status` = 'obsolete'),0),if((`functionalci_system_id_functionalci`.`finalclass` = 'WebApplication'),coalesce(coalesce((`webserver_webserver_id_softwareinstance`.`status` = 'inactive'),0),0),if((`functionalci_system_id_functionalci`.`finalclass` = 'DatabaseSchema'),coalesce(coalesce((`dbserver_dbserver_id_softwareinstance`.`status` = 'inactive'),0),0),if((`functionalci_system_id_functionalci`.`finalclass` = 'MiddlewareInstance'),coalesce(coalesce((`middleware_middleware_id_softwareinstance`.`status` = 'inactive'),0),0),if((`functionalci_system_id_functionalci`.`finalclass` in ('Middleware','DBServer','WebServer','PCSoftware','OtherSoftware')),coalesce((`functionalci_system_id_fn_softwareinstance_softwareinstance`.`status` = 'inactive'),0),if((`functionalci_system_id_functionalci`.`finalclass` = 'BusinessProcess'),coalesce((`functionalci_system_id_fn_businessprocess_businessprocess`.`status` = 'inactive'),0),if((`functionalci_system_id_functionalci`.`finalclass` = 'ApplicationSolution'),coalesce((`functionalci_system_id_fn_applicationsolution_applicationsolution`.`status` = 'inactive'),0),coalesce((`functionalci_system_id_fn_physicaldevice_physicaldevice`.`status` = 'obsolete'),0))))))))) AS `system_id_obsolescence_flag`,cast(concat(coalesce(`software_software_id_software`.`name`,''),coalesce(' ',''),coalesce(`software_software_id_software`.`version`,'')) as char charset utf8) AS `software_id_friendlyname`,cast(concat(coalesce(`softwarelicence_softwarelicence_id_licence`.`name`,'')) as char charset utf8) AS `softwarelicence_id_friendlyname`,coalesce(((`softwarelicence_softwarelicence_id_licence`.`perpetual` = 'no') and (isnull(`softwarelicence_softwarelicence_id_licence`.`end_date`) = 0) and (`softwarelicence_softwarelicence_id_licence`.`end_date` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00'))),0) AS `softwarelicence_id_obsolescence_flag` from ((`functionalci` `_functionalci` join `organization` `organization_org_id_organization` on((`_functionalci`.`org_id` = `organization_org_id_organization`.`id`))) join (((`softwareinstance` `_softwareinstance` join ((((((((`functionalci` `functionalci_system_id_functionalci` left join (`softwareinstance` `functionalci_system_id_fn_softwareinstance_softwareinstance` join `functionalci` `functionalci_system_id1_functionalci` on((`functionalci_system_id_fn_softwareinstance_softwareinstance`.`functionalci_id` = `functionalci_system_id1_functionalci`.`id`))) on((`functionalci_system_id_functionalci`.`id` = `functionalci_system_id_fn_softwareinstance_softwareinstance`.`id`))) left join `virtualdevice` `functionalci_system_id_fn_virtualdevice_virtualdevice` on((`functionalci_system_id_functionalci`.`id` = `functionalci_system_id_fn_virtualdevice_virtualdevice`.`id`))) left join (`webapplication` `functionalci_system_id_fn_webapplication_webapplication` join (`functionalci` `webserver_webserver_id_functionalci` join `softwareinstance` `webserver_webserver_id_softwareinstance` on((`webserver_webserver_id_functionalci`.`id` = `webserver_webserver_id_softwareinstance`.`id`))) on((`functionalci_system_id_fn_webapplication_webapplication`.`webserver_id` = `webserver_webserver_id_functionalci`.`id`))) on((`functionalci_system_id_functionalci`.`id` = `functionalci_system_id_fn_webapplication_webapplication`.`id`))) left join (`databaseschema` `functionalci_system_id_fn_databaseschema_databaseschema` join (`functionalci` `dbserver_dbserver_id_functionalci` join `softwareinstance` `dbserver_dbserver_id_softwareinstance` on((`dbserver_dbserver_id_functionalci`.`id` = `dbserver_dbserver_id_softwareinstance`.`id`))) on((`functionalci_system_id_fn_databaseschema_databaseschema`.`dbserver_id` = `dbserver_dbserver_id_functionalci`.`id`))) on((`functionalci_system_id_functionalci`.`id` = `functionalci_system_id_fn_databaseschema_databaseschema`.`id`))) left join (`middlewareinstance` `functionalci_system_id_fn_middlewareinstance_middlewareinstance` join (`functionalci` `middleware_middleware_id_functionalci` join `softwareinstance` `middleware_middleware_id_softwareinstance` on((`middleware_middleware_id_functionalci`.`id` = `middleware_middleware_id_softwareinstance`.`id`))) on((`functionalci_system_id_fn_middlewareinstance_middlewareinstance`.`middleware_id` = `middleware_middleware_id_functionalci`.`id`))) on((`functionalci_system_id_functionalci`.`id` = `functionalci_system_id_fn_middlewareinstance_middlewareinstance`.`id`))) left join `businessprocess` `functionalci_system_id_fn_businessprocess_businessprocess` on((`functionalci_system_id_functionalci`.`id` = `functionalci_system_id_fn_businessprocess_businessprocess`.`id`))) left join `applicationsolution` `functionalci_system_id_fn_applicationsolution_applicationsolution` on((`functionalci_system_id_functionalci`.`id` = `functionalci_system_id_fn_applicationsolution_applicationsolution`.`id`))) left join `physicaldevice` `functionalci_system_id_fn_physicaldevice_physicaldevice` on((`functionalci_system_id_functionalci`.`id` = `functionalci_system_id_fn_physicaldevice_physicaldevice`.`id`))) on((`_softwareinstance`.`functionalci_id` = `functionalci_system_id_functionalci`.`id`))) left join `software` `software_software_id_software` on((`_softwareinstance`.`software_id` = `software_software_id_software`.`id`))) left join `licence` `softwarelicence_softwarelicence_id_licence` on((`_softwareinstance`.`softwarelicence_id` = `softwarelicence_softwarelicence_id_licence`.`id`))) on((`_functionalci`.`id` = `_softwareinstance`.`id`))) where (coalesce((`_functionalci`.`finalclass` = 'WebServer'),1) and coalesce((`webserver_webserver_id_functionalci`.`finalclass` = 'WebServer'),1) and coalesce((`dbserver_dbserver_id_functionalci`.`finalclass` = 'DBServer'),1) and coalesce((`middleware_middleware_id_functionalci`.`finalclass` = 'Middleware'),1) and coalesce((`softwarelicence_softwarelicence_id_licence`.`finalclass` = 'SoftwareLicence'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_workorder`
--

/*!50001 DROP VIEW IF EXISTS `view_workorder`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_workorder` AS select distinct `_workorder`.`id` AS `id`,`_workorder`.`name` AS `name`,`_workorder`.`status` AS `status`,`_workorder`.`description` AS `description`,`_workorder`.`ticket_id` AS `ticket_id`,`ticket_ticket_id_ticket`.`ref` AS `ticket_ref`,`_workorder`.`team_id` AS `team_id`,`team_team_id_contact`.`email` AS `team_name`,`_workorder`.`owner_id` AS `agent_id`,`person_agent_id_contact`.`email` AS `agent_email`,`_workorder`.`start_date` AS `start_date`,`_workorder`.`end_date` AS `end_date`,`_workorder`.`log` AS `log`,`_workorder`.`log_index` AS `log_index`,cast(concat(coalesce(`_workorder`.`name`,'')) as char charset utf8) AS `friendlyname`,cast(concat(coalesce(`ticket_ticket_id_ticket`.`ref`,'')) as char charset utf8) AS `ticket_id_friendlyname`,`ticket_ticket_id_ticket`.`finalclass` AS `ticket_id_finalclass_recall`,coalesce(((`ticket_ticket_id_ticket`.`operational_status` = 'closed') and ((`ticket_ticket_id_ticket`.`close_date` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00')) or (isnull(`ticket_ticket_id_ticket`.`close_date`) and (`ticket_ticket_id_ticket`.`last_update` < date_format((now() - interval 15 month),'%Y-%m-%d 00:00:00'))))),0) AS `ticket_id_obsolescence_flag`,cast(concat(coalesce(`team_team_id_contact`.`name`,'')) as char charset utf8) AS `team_id_friendlyname`,coalesce((`team_team_id_contact`.`status` = 'inactive'),0) AS `team_id_obsolescence_flag`,cast(concat(coalesce(`person_agent_id_person`.`first_name`,''),coalesce(' ',''),coalesce(`person_agent_id_contact`.`name`,'')) as char charset utf8) AS `agent_id_friendlyname`,coalesce((`person_agent_id_contact`.`status` = 'inactive'),0) AS `agent_id_obsolescence_flag` from (((`workorder` `_workorder` join `ticket` `ticket_ticket_id_ticket` on((`_workorder`.`ticket_id` = `ticket_ticket_id_ticket`.`id`))) join `contact` `team_team_id_contact` on((`_workorder`.`team_id` = `team_team_id_contact`.`id`))) left join (`contact` `person_agent_id_contact` join `person` `person_agent_id_person` on((`person_agent_id_contact`.`id` = `person_agent_id_person`.`id`))) on((`_workorder`.`owner_id` = `person_agent_id_contact`.`id`))) where (coalesce((`team_team_id_contact`.`finalclass` = 'Team'),1) and coalesce((`person_agent_id_contact`.`finalclass` = 'Person'),1)) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-04-28 12:02:25
