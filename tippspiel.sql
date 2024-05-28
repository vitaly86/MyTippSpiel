-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: tippspiel
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events` (
  `eid` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `heid` int(6) unsigned NOT NULL,
  `ename` varchar(200) NOT NULL,
  PRIMARY KEY (`eid`),
  KEY `FK_HostEvents` (`heid`),
  CONSTRAINT `FK_HostEvents` FOREIGN KEY (`heid`) REFERENCES `hosts` (`hid`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` VALUES (1,1,'FIFA World Cup'),(2,1,'Wimbledon'),(3,2,'Basketball'),(4,2,'Pole vault'),(5,3,'Tour de France');
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `genautipps`
--

DROP TABLE IF EXISTS `genautipps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `genautipps` (
  `gntippid` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `stippid` int(6) unsigned NOT NULL,
  `utippid` int(6) unsigned NOT NULL,
  `tipptordiff` int(20) NOT NULL,
  `tippdatum` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`gntippid`),
  KEY `FK_SpieleTippsGenau` (`stippid`),
  KEY `FK_UserTippsGenau` (`utippid`),
  CONSTRAINT `FK_SpieleTippsGenau` FOREIGN KEY (`stippid`) REFERENCES `spiele` (`spid`) ON UPDATE CASCADE,
  CONSTRAINT `FK_UserTippsGenau` FOREIGN KEY (`utippid`) REFERENCES `users` (`userid`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `genautipps`
--

LOCK TABLES `genautipps` WRITE;
/*!40000 ALTER TABLE `genautipps` DISABLE KEYS */;
INSERT INTO `genautipps` VALUES (1,1,1,10,'2000-03-03 09:00:00'),(11,6,6,10,'2023-10-12 10:30:00'),(12,7,6,5,'2024-05-12 21:15:00'),(13,1,7,0,'2023-03-01 09:45:00'),(14,2,7,2,'2023-03-02 11:00:00'),(15,3,7,2,'2023-03-03 14:45:00'),(16,4,7,3,'2023-03-04 19:00:00'),(17,5,7,0,'2023-03-05 00:45:00'),(18,2,1,0,'2023-04-10 10:25:00'),(19,3,1,2,'2023-04-11 02:15:00'),(20,4,1,5,'2023-04-12 08:00:00'),(21,5,1,0,'2023-04-14 11:00:00'),(22,1,2,2,'2023-05-20 10:00:00'),(23,2,2,5,'2023-05-21 15:15:00'),(24,3,2,0,'2023-05-23 16:45:00'),(25,4,2,3,'2023-05-27 17:10:00'),(26,5,2,0,'2023-05-29 19:00:00'),(27,1,3,0,'2023-06-01 10:15:00'),(28,2,3,1,'2023-06-03 09:00:00'),(29,3,3,1,'2023-06-05 06:25:00'),(30,4,3,2,'2023-06-10 04:00:00'),(31,5,3,0,'2023-06-13 03:15:00');
/*!40000 ALTER TABLE `genautipps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hosts`
--

DROP TABLE IF EXISTS `hosts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hosts` (
  `hid` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `hfullname` varchar(30) NOT NULL,
  `hostname` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `passwort` varchar(100) NOT NULL,
  `foto` varchar(30) DEFAULT NULL,
  `tordiff` int(20) DEFAULT 10,
  `winnloss` int(20) DEFAULT 5,
  `equality` int(20) DEFAULT 15,
  PRIMARY KEY (`hid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hosts`
--

LOCK TABLES `hosts` WRITE;
/*!40000 ALTER TABLE `hosts` DISABLE KEYS */;
INSERT INTO `hosts` VALUES (1,'vitaly','vitaly86','vitaly@gmail.com','$2y$10$64NMwy08IkwTHg8OjZF1E.p1ZrLyvIEBcZgPIAPKteAxvIdmMsQhO','me.jpg',4,2,2),(2,'stefan','stefan90','stefan@gmail.com','$2y$10$.tjn2HH2UdPoHPyB5TVJVuSy8cj/4FWlhiMBsDXcm.qJ/JKLZGp.q','stefan.jpg',10,5,15),(3,'eugenis','marry2000','marry@gmail.com','$2y$10$4gHC2DKKWvSGZkMvi7TxjuOhmbfVA0h58j6ROCwU2FXn318Zsrh4S','marry.jpg',10,5,15);
/*!40000 ALTER TABLE `hosts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `results`
--

DROP TABLE IF EXISTS `results`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `results` (
  `rid` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `urid` int(6) unsigned NOT NULL,
  `evrid` int(6) unsigned NOT NULL,
  `score` int(100) NOT NULL,
  PRIMARY KEY (`rid`),
  KEY `FK_UserResults` (`urid`),
  KEY `FK_EventResults` (`evrid`),
  CONSTRAINT `FK_EventResults` FOREIGN KEY (`evrid`) REFERENCES `events` (`eid`) ON UPDATE CASCADE,
  CONSTRAINT `FK_UserResults` FOREIGN KEY (`urid`) REFERENCES `users` (`userid`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `results`
--

LOCK TABLES `results` WRITE;
/*!40000 ALTER TABLE `results` DISABLE KEYS */;
INSERT INTO `results` VALUES (1,1,1,1),(2,2,1,0),(3,3,1,3),(4,7,1,2),(5,1,3,0),(6,3,3,0),(7,6,3,20),(8,8,3,0);
/*!40000 ALTER TABLE `results` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `spiele`
--

DROP TABLE IF EXISTS `spiele`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `spiele` (
  `spid` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `espid` int(6) unsigned NOT NULL,
  `spielname` varchar(100) NOT NULL,
  `spieldatum` timestamp NOT NULL DEFAULT current_timestamp(),
  `teamAresult` int(20) DEFAULT NULL,
  `teamBresult` int(20) DEFAULT NULL,
  PRIMARY KEY (`spid`),
  KEY `FK_EventSpiele` (`espid`),
  CONSTRAINT `FK_EventSpiele` FOREIGN KEY (`espid`) REFERENCES `events` (`eid`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `spiele`
--

LOCK TABLES `spiele` WRITE;
/*!40000 ALTER TABLE `spiele` DISABLE KEYS */;
INSERT INTO `spiele` VALUES (1,1,'Group A: Ecuador vs. Qatar','2023-01-01 09:30:00',0,0),(2,1,'Group B: England vs. Iran','2023-10-01 11:40:00',2,5),(3,1,'Group A: Netherlands vs. Senegal','2023-01-20 07:45:00',5,2),(4,1,'Group C: Saudi Arabia vs. Argentina','2023-02-02 19:45:00',10,6),(5,1,'Group E: Japan vs. Germany','2023-02-05 02:00:00',1,4),(6,3,'Group D: France vs. Australia','2024-05-12 13:30:00',NULL,NULL),(7,3,'Group F: Belgium vs. Canada','2024-05-12 21:30:00',2,2),(8,5,'Pro Cycling Manager','2024-05-19 21:50:00',NULL,NULL),(9,1,'Group B: Moldawien Vs. Roumanie','2022-12-25 14:30:00',1,2),(10,3,'All-Star Game','2024-05-22 09:10:55',2,1),(11,3,'Half-Court Shot Challenge','2024-05-12 14:00:00',2,2),(12,3,'Slam Dunk Contest','2024-05-12 18:00:00',1,2),(13,3,'1-on-1 Tournament','2024-05-12 15:30:00',3,3),(14,3,'Charity Game','2024-05-12 16:45:00',2,1),(15,1,'Group A: France Vs. Italy','2023-05-22 09:00:00',4,2),(21,2,'Group B: Khina Vs. Japan','2023-05-09 11:50:00',NULL,NULL),(23,2,'Group B: Turkey Vs. Serbien','2023-07-01 21:45:00',NULL,NULL),(24,2,'Group B: USA Vs. Kanada','2023-05-20 10:00:00',NULL,NULL),(25,2,'Group B: Turkey Vs. Romania','2023-06-01 08:45:00',NULL,NULL);
/*!40000 ALTER TABLE `spiele` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tendenztipps`
--

DROP TABLE IF EXISTS `tendenztipps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tendenztipps` (
  `tdtippid` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `stippid` int(6) unsigned NOT NULL,
  `utippid` int(6) unsigned NOT NULL,
  `tippAteam` enum('sieg','niederlage','unentschieden') NOT NULL,
  `tippBteam` enum('sieg','niederlage','unentschieden') NOT NULL,
  `tippdatum` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`tdtippid`),
  KEY `FK_SpieleTippsTendenz` (`stippid`),
  KEY `FK_UserTippsTendenz` (`utippid`),
  CONSTRAINT `FK_SpieleTippsTendenz` FOREIGN KEY (`stippid`) REFERENCES `spiele` (`spid`) ON UPDATE CASCADE,
  CONSTRAINT `FK_UserTippsTendenz` FOREIGN KEY (`utippid`) REFERENCES `users` (`userid`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tendenztipps`
--

LOCK TABLES `tendenztipps` WRITE;
/*!40000 ALTER TABLE `tendenztipps` DISABLE KEYS */;
INSERT INTO `tendenztipps` VALUES (1,1,1,'niederlage','sieg','1986-10-24 01:30:00'),(2,6,6,'sieg','niederlage','2024-05-12 14:00:00'),(3,7,6,'unentschieden','unentschieden','2024-05-12 21:15:00'),(4,1,7,'unentschieden','unentschieden','2023-03-01 09:45:00'),(5,2,7,'sieg','niederlage','2023-03-02 11:00:00'),(6,3,7,'niederlage','sieg','2023-03-03 14:00:00'),(7,4,7,'sieg','niederlage','2023-03-04 19:00:00'),(8,5,7,'unentschieden','unentschieden','2023-03-05 00:00:00'),(9,2,1,'sieg','niederlage','2023-05-15 10:45:00'),(10,3,1,'sieg','niederlage','2023-04-11 02:15:00'),(11,4,1,'niederlage','sieg','2023-04-12 08:00:00'),(12,5,1,'unentschieden','unentschieden','2023-04-14 11:00:00'),(13,1,2,'niederlage','sieg','2023-05-20 10:00:00'),(14,2,2,'sieg','niederlage','2023-05-21 15:15:00'),(15,3,2,'unentschieden','unentschieden','2023-05-25 16:45:00'),(16,4,2,'niederlage','sieg','2023-05-27 17:30:00'),(17,5,2,'unentschieden','unentschieden','2023-05-29 19:00:00'),(18,1,3,'unentschieden','unentschieden','2023-06-01 10:15:00'),(19,2,3,'niederlage','sieg','2023-06-03 09:00:00'),(20,3,3,'niederlage','sieg','2023-06-05 06:25:00'),(21,4,3,'sieg','niederlage','2023-06-10 04:00:00'),(22,5,3,'unentschieden','unentschieden','2023-06-15 03:15:00');
/*!40000 ALTER TABLE `tendenztipps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `userid` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `useremail` varchar(50) NOT NULL,
  `upasswort` varchar(100) NOT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'stefan','stefan@gmail.com','$2y$10$B2/kqhdp66mprPgknIyEv.2sl4q5dIzYY.aYmyLqLSZojs7foD2zq'),(2,'tamara','tamara@gmail.com','$2y$10$RWu5xLwwxE7HCd2dH.yvue4jeOE31lOiagDuiyINubuGD237o/t0u'),(3,'dennis','dennis@gmail.com','$2y$10$wR3GnjHgfElgeNlRaAUpN.bWhCkVuT6pao9qX1CgFjgo4plG6qLiC'),(6,'andre michel','michel@gmail.com','$2y$10$bGDAipK2liOhxhlygtGd6eUrKlT2mBCnQFQbsTEtH2CgbfIOsRBAe'),(7,'antony','antony@gmail.com','$2y$10$t.L4qFJwWPk2FgrLwxYN1uUgRwrS5ojd/sbsZaU.oMlKeRpO4jcEy'),(8,'bob','bob@gmail.com','$2y$10$DY1P04QgJiVXeXaJgzmmqu6T08rKzD0h2RV//ULjOUU.RlssyRLMy');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usersevents`
--

DROP TABLE IF EXISTS `usersevents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usersevents` (
  `eventid` int(6) unsigned NOT NULL,
  `euserid` int(6) unsigned NOT NULL,
  PRIMARY KEY (`eventid`,`euserid`),
  KEY `FK_UsersEvents` (`euserid`),
  CONSTRAINT `FK_Events` FOREIGN KEY (`eventid`) REFERENCES `events` (`eid`) ON UPDATE CASCADE,
  CONSTRAINT `FK_UsersEvents` FOREIGN KEY (`euserid`) REFERENCES `users` (`userid`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usersevents`
--

LOCK TABLES `usersevents` WRITE;
/*!40000 ALTER TABLE `usersevents` DISABLE KEYS */;
INSERT INTO `usersevents` VALUES (1,1),(1,2),(1,3),(1,7),(3,1),(3,3),(3,6),(3,8);
/*!40000 ALTER TABLE `usersevents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usershosts`
--

DROP TABLE IF EXISTS `usershosts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usershosts` (
  `hostid` int(6) unsigned NOT NULL,
  `huserid` int(6) unsigned NOT NULL,
  PRIMARY KEY (`hostid`,`huserid`),
  KEY `FK_UsersHosts` (`huserid`),
  CONSTRAINT `FK_Hosts` FOREIGN KEY (`hostid`) REFERENCES `hosts` (`hid`) ON UPDATE CASCADE,
  CONSTRAINT `FK_UsersHosts` FOREIGN KEY (`huserid`) REFERENCES `users` (`userid`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usershosts`
--

LOCK TABLES `usershosts` WRITE;
/*!40000 ALTER TABLE `usershosts` DISABLE KEYS */;
INSERT INTO `usershosts` VALUES (1,1),(1,2),(1,3),(1,7),(2,1),(2,6),(2,8);
/*!40000 ALTER TABLE `usershosts` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-05-24  6:30:34
