# ************************************************************
# Sequel Ace SQL dump
# Version 20080
#
# https://sequel-ace.com/
# https://github.com/Sequel-Ace/Sequel-Ace
#
# Host: 127.0.0.1 (MySQL 9.2.0)
# Database: kanban_db
# Generation Time: 2025-04-05 10:40:17 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE='NO_AUTO_VALUE_ON_ZERO', SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP DATABASE IF EXISTS `kanban_db`;

CREATE DATABASE `kanban_db`;

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
	(16,7,'2025-04-05 10:08:00','Lane Deleted','Lane','Action performed');

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
	('DoctrineMigrations\\Version20250402140823','2025-04-02 14:08:30',114);

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


# Dump of table task
# ------------------------------------------------------------

DROP TABLE IF EXISTS `task`;

CREATE TABLE `task` (
  `id` int NOT NULL AUTO_INCREMENT,
  `lane_id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_527EDB25A128F72F` (`lane_id`),
  CONSTRAINT `FK_527EDB25A128F72F` FOREIGN KEY (`lane_id`) REFERENCES `lane` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `task` WRITE;
/*!40000 ALTER TABLE `task` DISABLE KEYS */;

INSERT INTO `task` (`id`, `lane_id`, `title`)
VALUES
	(1,2,'Maak een GIT repository aan'),
	(2,1,'Setup database met twee tabellen'),
	(3,1,'Begin met het schrijven van Symfony-code'),
	(4,1,'Lees de projectvereisten');

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
	(4,'user@example.com','$2y$13$yNOVEVY5qKyaqjlT/xnpAeNHMt/YEdY9t16B44IgDAN8tboOBc462','[\"ROLE_USER\"]'),
	(5,'testmail@mail.com','$2y$13$j0sbNiNNsCqQ7QfIIYD8..5avN5eQ3vgdAexlycOWLftpenMn7Mfu','[\"ROLE_USER\"]'),
	(6,'appel@moes.nl','$2y$13$FU87hydfkjgJgsWdseaJzuGoZT2DISRMkIU4FS86dse50fzSHZAPu','[\"ROLE_USER\"]');

/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
