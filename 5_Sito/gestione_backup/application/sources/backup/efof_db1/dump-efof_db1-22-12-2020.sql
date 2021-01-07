-- mysqldump-php https://github.com/ifsnop/mysqldump-php
--
-- Host: efof.myd.infomaniak.com	Database: efof_db1
-- ------------------------------------------------------
-- Server version 	5.6.50-log
-- Date: Tue, 22 Dec 2020 23:07:00 +0100

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
-- Current Database: `efof_db1`
--

/*!40000 DROP DATABASE IF EXISTS `efof_db1`*/;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `efof_db1` /*!40100 DEFAULT CHARACTER SET utf8  COLLATE utf8_general_ci */;

USE `efof_db1`;

--
-- Table structure for table `db_link`
--

DROP TABLE IF EXISTS `db_link`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `db_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dbname` varchar(60) DEFAULT NULL,
  `dbhost` varchar(60) DEFAULT NULL,
  `dbuser` varchar(60) DEFAULT NULL,
  `dbpass` varchar(256) DEFAULT NULL,
  `dbport` varchar(4) DEFAULT NULL,
  `backupFrequency` int(11) NOT NULL DEFAULT '0',
  `lastBackupTime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dbname` (`dbname`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `db_link`
--

LOCK TABLES `db_link` WRITE;
/*!40000 ALTER TABLE `db_link` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `db_link` VALUES (1,'efof_db1','efof.myd.infomaniak.com','efof_i17caspie','$2y$10$5hBJSB0DbG5XrbSxVVTn/erv2ASMcibI1rPDxYDyM3BP3UnTy5WtK','3306',1,'0000-00-00 00:00:00'),(2,'efof_db4','efof.myd.infomaniak.com','efof_i17caspie1','$2y$10$BQErUcOQSzSA6hlyOE34g.bATuE8b4cTGeL.tI/hrYY0Je/Bp2iya','3306',2,'0000-00-00 00:00:00');
/*!40000 ALTER TABLE `db_link` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `db_link` with 2 row(s)
--

--
-- Table structure for table `utenti`
--

DROP TABLE IF EXISTS `utenti`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `utenti` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `cognome` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `pswd` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `statusLogin` tinyint(1) NOT NULL DEFAULT '-1',
  `tipo` enum('amministratore','responsabile') DEFAULT NULL,
  `immagineProfilo` varchar(20) NOT NULL DEFAULT 'profilo.png',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `utenti`
--

LOCK TABLES `utenti` WRITE;
/*!40000 ALTER TABLE `utenti` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `utenti` VALUES (1,'Fabrizio','Valsangiacomo','','$2y$10$lI.3S4KkgdGR3.Oqd3n9WO78boFfZt1xlnPeqSwkZuY/SI4Jw7Pi6','fabrizio.valsangiacomo@edu.ti.ch',-1,'amministratore','profilo.png'),(2,'Lorenzo','Piazza','','$2y$10$gWIsWkP1qodzZsy3cwO3aOyxDqUVvR3vzHN6kBI4jjAiQTePqwyCC','lorenzo.piazza@samtrevano.ch',-1,'amministratore','profilo.png'),(3,'Pierpaolo','Casati','pierpaolo.casati','$2y$10$u9If8H4ADex4ONqM7JNSJOGLhqwwvOQYJKci9M2LZZ24rFqojl8Je','pierpaolo.casati@samtrevano.ch',0,'amministratore','6.png'),(4,'Fabrizio','Valsangiacomo','','$2y$10$e1VV/Y202kBJkoYk8U/Q/Oxtz6Eq6OYMo5zZzDLuO3xVhQLpcc6Ea','fabrizio.valsangiacomo@gmail.com',-1,'responsabile','profilo.png'),(5,'Pierpaolo','Casati','pier.cas','$2y$10$1oI9oI/oBn0RNyzy0dbrG.xcpZwoWsIgfAFBx8Hh0X3Rb8IcTIC9W','pierpaolo.casati@bluewin.ch',0,'responsabile','profilo.png'),(6,'Julian','Sprugasci','','$2y$10$wIj11.qREvM7q4K2ZNdHVe5QIF1aHb0eEVik3MYHKfQrnAUv4a0Ce','julian.sprugasci@samtrevano.ch',-1,'amministratore','profilo.png'),(7,'Pierpaolo','Casati','','$2y$10$IjaGzdL.IOuOivSjEpFrFuuqy3Rxn0k4zRulKfsS.puukpOK1PNVS','roccia2504@gmail.com',-1,'responsabile','profilo.png');
/*!40000 ALTER TABLE `utenti` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `utenti` with 7 row(s)
--

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on: Tue, 22 Dec 2020 23:07:00 +0100
