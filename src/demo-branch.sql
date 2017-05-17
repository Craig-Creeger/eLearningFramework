# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: localhost (MySQL 5.6.26)
# Database: course
# Generation Time: 2015-10-05 19:22:40 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table assignments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `assignments`;

CREATE TABLE `assignments` (
  `learnerId` int(11) unsigned NOT NULL,
  `courseId` int(11) unsigned NOT NULL,
  `score` decimal(3,2) DEFAULT NULL,
  `pass` tinyint(1) NOT NULL DEFAULT '0',
  `completionDate` datetime DEFAULT NULL,
  PRIMARY KEY (`learnerId`,`courseId`),
  KEY `courseId` (`courseId`),
  CONSTRAINT `assignments_ibfk_1` FOREIGN KEY (`learnerId`) REFERENCES `learners` (`learnerId`) ON DELETE CASCADE,
  CONSTRAINT `assignments_ibfk_2` FOREIGN KEY (`courseId`) REFERENCES `courses` (`courseId`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table courses
# ------------------------------------------------------------

DROP TABLE IF EXISTS `courses`;

CREATE TABLE `courses` (
  `courseId` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL,
  `courseName` varchar(60) NOT NULL DEFAULT '',
  `passingScore` decimal(3,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`courseId`),
  KEY `pid` (`pid`),
  CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`pid`) REFERENCES `promotions` (`pid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `courses` WRITE;
/*!40000 ALTER TABLE `courses` DISABLE KEYS */;

INSERT INTO `courses` (`courseId`, `pid`, `courseName`, `passingScore`)
VALUES
	(6,3,'About This Demo',0.80);

/*!40000 ALTER TABLE `courses` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table learners
# ------------------------------------------------------------

DROP TABLE IF EXISTS `learners`;

CREATE TABLE `learners` (
  `learnerId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL,
  `email` char(50) NOT NULL,
  `fullName` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`learnerId`),
  UNIQUE KEY `pid` (`pid`,`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table promotions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `promotions`;

CREATE TABLE `promotions` (
  `pid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `promotion` char(120) NOT NULL DEFAULT '',
  `buildDate` char(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `promotions` WRITE;
/*!40000 ALTER TABLE `promotions` DISABLE KEYS */;

INSERT INTO `promotions` (`pid`, `promotion`, `buildDate`)
VALUES
	(3,'Demo Course — Craig Creeger','Oct. 2015');

/*!40000 ALTER TABLE `promotions` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
