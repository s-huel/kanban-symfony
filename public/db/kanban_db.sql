# ************************************************************
# Sequel Ace SQL dump
# Version 20080
#
# https://sequel-ace.com/
# https://github.com/Sequel-Ace/Sequel-Ace
#
# Host: 127.0.0.1 (MySQL 9.2.0)
# Database: kanban_db
# Generation Time: 2025-05-19 13:21:15 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE='NO_AUTO_VALUE_ON_ZERO', SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

DROP DATABASE IF EXISTS `kanban_db`;

CREATE DATABASE IF NOT EXISTS `kanban_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;

USE `kanban_db`;

# Dump of table activity_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `activity_log`;

CREATE TABLE `activity_log` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `timestamp` datetime DEFAULT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entity_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `details` longtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `IDX_FD06F647A76ED395` (`user_id`),
  CONSTRAINT `FK_FD06F647A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `activity_log` WRITE;
/*!40000 ALTER TABLE `activity_log` DISABLE KEYS */;

INSERT INTO `activity_log` (`id`, `user_id`, `timestamp`, `action`, `entity_type`, `details`)
VALUES
	(1,1,'2025-04-02 17:06:50','Lane Added','Lane','Action performed'),
	(2,6,'2025-04-04 12:07:45','Task Added','Task','Action performed'),
	(3,6,'2025-04-04 12:07:56','Task Deleted','Task','Action performed'),
	(4,6,'2025-04-04 12:07:56','Task Deleted','Task','Action performed'),
	(5,6,'2025-04-04 12:35:27','Lane Deleted','Lane','Action performed'),
	(6,6,'2025-04-04 12:40:52','Lane Added','Lane','Action performed'),
	(7,6,'2025-04-04 12:40:56','Lane Deleted','Lane','Action performed'),
	(8,6,'2025-04-04 13:13:46','Updated Task','Task','Action performed'),
	(9,6,'2025-04-04 13:13:57','Lane Updated','Lane','Action performed'),
	(10,6,'2025-04-04 13:14:08','Lane Updated','Lane','Action performed'),
	(11,6,'2025-04-05 10:05:17','Updated Task','Task','Action performed'),
	(12,7,'2025-04-05 10:07:01','Updated Task','Task','Action performed'),
	(13,7,'2025-04-05 10:07:34','Updated Task','Task','Action performed'),
	(14,7,'2025-04-05 10:07:39','Updated Task','Task','Action performed'),
	(15,7,'2025-04-05 10:07:53','Lane Added','Lane','Action performed'),
	(16,7,'2025-04-05 10:08:00','Lane Deleted','Lane','Action performed'),
	(17,9,'2025-04-15 14:16:47','Updated Task','Task','Action performed'),
	(18,9,'2025-04-15 14:18:54','Updated Task','Task','Action performed'),
	(19,9,'2025-04-15 14:18:58','Updated Task','Task','Action performed'),
	(20,9,'2025-04-15 14:19:27','Lane Added','Lane','Action performed'),
	(21,9,'2025-04-15 14:19:56','Lane Added','Lane','Action performed'),
	(22,9,'2025-04-15 14:20:03','Lane Deleted','Lane','Action performed'),
	(23,9,'2025-04-15 14:20:06','Lane Deleted','Lane','Action performed'),
	(24,9,'2025-04-15 14:23:07','Updated Task','Task','Action performed'),
	(25,9,'2025-04-15 14:23:10','Updated Task','Task','Action performed'),
	(26,9,'2025-04-15 14:23:15','Updated Task','Task','Action performed'),
	(27,9,'2025-04-15 14:23:19','Updated Task','Task','Action performed'),
	(28,9,'2025-04-15 14:23:22','Updated Task','Task','Action performed'),
	(29,9,'2025-04-15 14:23:25','Updated Task','Task','Action performed'),
	(30,9,'2025-04-15 14:23:36','Updated Task','Task','Action performed'),
	(31,9,'2025-04-17 08:48:09','Updated Task','Task','Action performed'),
	(32,9,'2025-04-17 08:48:14','Updated Task','Task','Action performed'),
	(33,9,'2025-04-17 08:48:39','Updated Task','Task','Action performed'),
	(34,9,'2025-04-17 09:11:15','Lane Added','Lane','Action performed'),
	(35,9,'2025-04-17 09:11:23','Lane Updated','Lane','Action performed'),
	(36,9,'2025-04-17 09:11:30','Lane Deleted','Lane','Action performed'),
	(37,9,'2025-04-30 08:53:49','Updated Task','Task','Action performed'),
	(38,9,'2025-04-30 08:54:18','Updated Task','Task','Action performed'),
	(39,9,'2025-04-30 08:56:52','Lane Added','Lane','Action performed'),
	(40,9,'2025-04-30 08:57:20','Lane Deleted','Lane','Action performed'),
	(41,9,'2025-04-30 09:15:47','Updated Task','Task','Action performed'),
	(42,9,'2025-04-30 09:15:55','Updated Task','Task','Action performed'),
	(43,9,'2025-04-30 09:15:58','Updated Task','Task','Action performed'),
	(44,9,'2025-04-30 09:16:02','Updated Task','Task','Action performed'),
	(45,9,'2025-04-30 09:16:04','Updated Task','Task','Action performed'),
	(46,9,'2025-04-30 09:20:51','Lane Added','Lane','Action performed'),
	(47,9,'2025-04-30 09:20:58','Lane Updated','Lane','Action performed'),
	(48,9,'2025-04-30 09:21:03','Lane Updated','Lane','Action performed'),
	(49,9,'2025-04-30 09:21:06','Lane Deleted','Lane','Action performed'),
	(50,9,'2025-04-30 09:21:15','Lane Updated','Lane','Action performed'),
	(51,9,'2025-04-30 09:21:20','Updated Task','Task','Action performed'),
	(52,9,'2025-04-30 09:21:42','Lane Added','Lane','Action performed'),
	(53,9,'2025-04-30 09:21:44','Lane Updated','Lane','Action performed'),
	(54,9,'2025-04-30 09:22:20','Lane Updated','Lane','Action performed'),
	(55,9,'2025-04-30 09:22:41','Lane Deleted','Lane','Action performed'),
	(56,9,'2025-04-30 09:22:46','Lane Deleted','Lane','Action performed'),
	(57,9,'2025-04-30 09:24:52','Lane Updated','Lane','Action performed'),
	(58,9,'2025-04-30 09:25:00','Lane Updated','Lane','Action performed'),
	(59,9,'2025-05-02 14:51:32','Lane Added','Lane','Action performed'),
	(60,9,'2025-05-02 14:54:21','Lane Updated','Lane','Action performed'),
	(61,9,'2025-05-02 14:56:32','Lane Deleted','Lane','Action performed'),
	(62,9,'2025-05-06 11:34:23','Updated Task','Task','Action performed'),
	(63,9,'2025-05-06 11:34:27','Updated Task','Task','Action performed'),
	(64,9,'2025-05-06 14:13:44','Lane Updated','Lane','Action performed'),
	(65,9,'2025-05-06 14:13:49','Lane Updated','Lane','Action performed'),
	(66,9,'2025-05-18 18:34:41','Updated Task','Task','Action performed'),
	(67,9,'2025-05-18 19:04:12','Updated Task','Task','Action performed'),
	(68,9,'2025-05-18 19:04:19','Updated Task','Task','Action performed'),
	(69,9,'2025-05-18 19:04:25','Updated Task','Task','Action performed'),
	(70,9,'2025-05-18 19:04:29','Updated Task','Task','Action performed'),
	(71,9,'2025-05-18 19:04:31','Updated Task','Task','Action performed'),
	(72,9,'2025-05-18 19:08:07','Updated Task','Task','Action performed'),
	(73,9,'2025-05-18 19:08:09','Updated Task','Task','Action performed'),
	(74,9,'2025-05-18 19:08:15','Updated Task','Task','Action performed'),
	(75,9,'2025-05-18 19:49:20','Updated Task','Task','Action performed'),
	(76,9,'2025-05-18 19:49:44','Updated Task','Task','Action performed'),
	(77,9,'2025-05-19 12:43:22','Updated Task','Task','Action performed'),
	(78,9,'2025-05-19 12:58:39','Updated Task','Task','Action performed');

/*!40000 ALTER TABLE `activity_log` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table doctrine_migration_versions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `doctrine_migration_versions`;

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

LOCK TABLES `doctrine_migration_versions` WRITE;
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`)
VALUES
	('DoctrineMigrations\\Version20250327163526','2025-03-27 16:35:32',18),
	('DoctrineMigrations\\Version20250401141124','2025-04-01 14:11:36',109),
	('DoctrineMigrations\\Version20250402133419','2025-04-02 13:34:35',118),
	('DoctrineMigrations\\Version20250402140823','2025-04-02 14:08:30',114),
	('DoctrineMigrations\\Version20250414142303','2025-04-14 14:23:26',91),
	('DoctrineMigrations\\Version20250417114429','2025-04-17 11:46:59',104),
	('DoctrineMigrations\\Version20250429080521','2025-04-29 08:05:48',166),
	('DoctrineMigrations\\Version20250506092313','2025-05-06 10:17:02',96),
	('DoctrineMigrations\\Version20250506092644','2025-05-06 13:18:59',2),
	('DoctrineMigrations\\Version20250506093408','2025-05-06 13:20:11',3),
	('DoctrineMigrations\\Version20250506094640','2025-05-06 13:29:18',2),
	('DoctrineMigrations\\Version20250506095532','2025-05-06 13:30:29',8),
	('DoctrineMigrations\\Version20250506130607','2025-05-06 13:33:45',55),
	('DoctrineMigrations\\Version20250506131322','2025-05-06 13:40:15',66),
	('DoctrineMigrations\\Version20250506132512','2025-05-18 13:48:26',3),
	('DoctrineMigrations\\Version20250518134506','2025-05-18 13:48:26',15),
	('DoctrineMigrations\\Version20250518144016','2025-05-18 14:40:21',59);

/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table lane
# ------------------------------------------------------------

DROP TABLE IF EXISTS `lane`;

CREATE TABLE `lane` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `lane` WRITE;
/*!40000 ALTER TABLE `lane` DISABLE KEYS */;

INSERT INTO `lane` (`id`, `title`)
VALUES
	(1,'To Do'),
	(2,'In Progress'),
	(3,'Done');

/*!40000 ALTER TABLE `lane` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table priority
# ------------------------------------------------------------

DROP TABLE IF EXISTS `priority`;

CREATE TABLE `priority` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(7) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_62A6DC272B36786B` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `priority` WRITE;
/*!40000 ALTER TABLE `priority` DISABLE KEYS */;

INSERT INTO `priority` (`id`, `title`, `color`)
VALUES
	(1,'Low','#02b0fa'),
	(3,'Medium','#fab802'),
	(4,'High','#fa1302');

/*!40000 ALTER TABLE `priority` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table tag
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tag`;

CREATE TABLE `tag` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `tag` WRITE;
/*!40000 ALTER TABLE `tag` DISABLE KEYS */;

INSERT INTO `tag` (`id`, `title`)
VALUES
	(1,'Backend'),
	(2,'Frontend'),
	(3,'Backend'),
	(4,'Frontend'),
	(5,'Frontend'),
	(6,'Documentatie'),
	(7,'Docu'),
	(8,'Fron'),
	(9,'Back'),
	(10,'Doc'),
	(11,'Bac'),
	(12,'Testing'),
	(13,'Setup');

/*!40000 ALTER TABLE `tag` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table tag_task
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tag_task`;

CREATE TABLE `tag_task` (
  `tag_id` int NOT NULL,
  `task_id` int NOT NULL,
  `id` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `IDX_BC716493BAD26311` (`tag_id`),
  KEY `IDX_BC7164938DB60186` (`task_id`),
  CONSTRAINT `FK_BC7164938DB60186` FOREIGN KEY (`task_id`) REFERENCES `task` (`id`),
  CONSTRAINT `FK_BC716493BAD26311` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `tag_task` WRITE;
/*!40000 ALTER TABLE `tag_task` DISABLE KEYS */;

INSERT INTO `tag_task` (`tag_id`, `task_id`, `id`)
VALUES
	(1,3,4),
	(2,20,6),
	(6,4,7),
	(1,2,11),
	(12,13,17),
	(13,1,18),
	(1,20,19);

/*!40000 ALTER TABLE `tag_task` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table task
# ------------------------------------------------------------

DROP TABLE IF EXISTS `task`;

CREATE TABLE `task` (
  `id` int NOT NULL AUTO_INCREMENT,
  `lane_id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `priority_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_527EDB25A128F72F` (`lane_id`),
  KEY `IDX_527EDB25497B19F9` (`priority_id`),
  CONSTRAINT `FK_527EDB25497B19F9` FOREIGN KEY (`priority_id`) REFERENCES `priority` (`id`),
  CONSTRAINT `FK_527EDB25A128F72F` FOREIGN KEY (`lane_id`) REFERENCES `lane` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `task` WRITE;
/*!40000 ALTER TABLE `task` DISABLE KEYS */;

INSERT INTO `task` (`id`, `lane_id`, `title`, `priority_id`)
VALUES
	(1,3,'Maak een GIT repository aan',4),
	(2,1,'Setup database met twee tabellen',3),
	(3,2,'Begin met het schrijven van Symfony-code',1),
	(4,3,'Lees de projectvereisten',1),
	(13,1,'Test rapport uitschrijven',4),
	(20,2,'Twig',3);

/*!40000 ALTER TABLE `task` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;

INSERT INTO `user` (`id`, `email`, `password`, `roles`)
VALUES
	(1,'serena.huel1@gmail.com','$2y$13$3jIJDbB1B.VyB1yeW4ngKeNlsDt8dh01K2SsY48KTAarQ8gFh.SCG','[\"ROLE_USER\"]'),
	(3,'test@mail.com','$2y$13$OBTvZEbEclcJq8gyRTOBy.A8qJGzFegCcneIof95RL8.D6F0j.WC.','[\"ROLE_USER\"]'),
	(4,'new.user@example.com','$2y$13$FoGasj9owebm0PvrXlxBYOABrdhVOBtcIOmz6T9kcIx/Ks4tfj5B6','[]'),
	(5,'user@example.com','$2y$13$yNOVEVY5qKyaqjlT/xnpAeNHMt/YEdY9t16B44IgDAN8tboOBc462','[\"ROLE_USER\"]'),
	(6,'testmail@mail.com','$2y$13$j0sbNiNNsCqQ7QfIIYD8..5avN5eQ3vgdAexlycOWLftpenMn7Mfu','[\"ROLE_USER\"]'),
	(7,'appel@moes.nl','$2y$13$FU87hydfkjgJgsWdseaJzuGoZT2DISRMkIU4FS86dse50fzSHZAPu','[\"ROLE_USER\"]'),
	(8,'test@example.c','$2y$13$0ORLc4/jeOlfW9pdFLffWO5iEGilwsscH7.F3uYqewRoOxEiR8iO6','[\"ROLE_USER\"]'),
	(9,'test@example.com','$2y$13$BDnHDWxLF3nzk9LCPw9hL.WXz6kaZD1v.r4Id8cK02DnVjd5FgOkC','[\"ROLE_USER\"]');

/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
