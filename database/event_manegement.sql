/*
SQLyog Community v13.2.1 (64 bit)
MySQL - 10.6.20-MariaDB : Database - event_management
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`event_management` /*!40100 DEFAULT CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci */;

USE `event_management`;

/*Table structure for table `attendees` */

DROP TABLE IF EXISTS `attendees`;

CREATE TABLE `attendees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `registration_time` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `event_id` (`event_id`),
  CONSTRAINT `attendees_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

/*Data for the table `attendees` */

insert  into `attendees`(`id`,`event_id`,`name`,`email`,`phone`,`registration_time`) values 
(1,1,'Hasad Price','hubanuru@mailinator.com','+1 (787) 124-1844','2025-02-01 10:28:07'),
(2,2,'Karen Pitts','pitomobyw@mailinator.com','+1 (414) 865-7927','2025-02-01 10:44:08');

/*Table structure for table `events` */

DROP TABLE IF EXISTS `events`;

CREATE TABLE `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `max_capacity` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `events_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

/*Data for the table `events` */

insert  into `events`(`id`,`name`,`description`,`date`,`time`,`max_capacity`,`created_by`,`created_at`) values 
(1,'Amir Roberts','Neque at hic elit u','1990-12-28','08:01:00',6,1,'2025-01-25 12:24:05'),
(2,'Zoe Cote','Culpa sunt nulla quo','2022-05-13','22:29:00',43,1,'2025-01-25 12:24:09'),
(3,'Colby Lewis','Sit occaecat Nam cup','1978-09-30','00:34:00',1,1,'2025-02-01 10:43:42'),
(4,'Colby Lewis','Sit occaecat Nam cup','1978-09-30','00:34:00',1,1,'2025-02-01 10:43:51'),
(5,'Colby Lewis','Sit occaecat Nam cup','1978-09-30','00:34:00',1,1,'2025-02-01 10:43:53'),
(6,'Craig Gomez','Sapiente debitis dol','2001-10-02','06:12:00',54,1,'2025-02-01 10:51:12'),
(7,'Austin Sears','In rerum nulla conse','2012-03-18','17:37:00',48,1,'2025-02-01 10:51:38');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`username`,`email`,`password`,`role`,`created_at`) values 
(1,'sabbir','ahmedsabbir401@gmail.com','$2y$10$kLC7Uks5W3uBVq/BMk/E4uGXq.Tvuap6598KyyMCGURTm2XqRe6ua','admin','2025-01-25 12:23:24');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
