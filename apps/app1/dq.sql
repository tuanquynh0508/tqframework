/*
SQLyog Ultimate v11.11 (64 bit)
MySQL - 5.6.16 : Database - tqframework
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`tqframework` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `tqframework`;

/*Table structure for table `tbl_category` */

DROP TABLE IF EXISTS `tbl_category`;

CREATE TABLE `tbl_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

/*Data for the table `tbl_category` */

insert  into `tbl_category`(`id`,`title`,`created`,`modified`) values (1,'Danh muc 1','2014-09-08 22:32:04','2014-09-12 08:12:39'),(2,'Danh muc 2','2014-09-08 22:32:20','2014-09-11 17:55:37'),(16,'Laptop','2014-09-11 17:19:57','2014-09-11 17:19:57'),(19,'xxxxx','2014-09-12 08:12:53','2014-09-12 08:12:53');

/*Table structure for table `tbl_example` */

DROP TABLE IF EXISTS `tbl_example`;

CREATE TABLE `tbl_example` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `category_id` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT 'Title',
  `description` text CHARACTER SET utf8 COMMENT 'Description',
  `file` varchar(255) DEFAULT NULL COMMENT 'File',
  `is_public` tinyint(1) DEFAULT '1' COMMENT 'Status',
  `created` datetime DEFAULT NULL COMMENT 'Created',
  `modified` datetime DEFAULT NULL COMMENT 'Modified',
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `tbl_example_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `tbl_category` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=ujis;

/*Data for the table `tbl_example` */

insert  into `tbl_example`(`id`,`category_id`,`title`,`description`,`file`,`is_public`,`created`,`modified`) values (1,1,'Post 1',NULL,NULL,1,'2014-07-24 17:47:32','2014-07-24 17:47:32'),(2,1,'Post 2',NULL,NULL,1,'2014-07-24 17:47:32','2014-07-24 17:47:32'),(3,1,'Post 3',NULL,NULL,1,'2014-07-24 17:47:32','2014-07-24 17:47:32'),(4,1,'Post 4',NULL,NULL,1,'2014-07-24 17:47:32','2014-07-24 17:47:32'),(5,1,'Post 5',NULL,NULL,1,'2014-07-24 17:47:32','2014-07-24 17:47:32'),(6,1,'Post 6',NULL,NULL,1,'2014-07-24 17:47:32','2014-07-24 17:47:32'),(7,1,'Post 7',NULL,NULL,1,'2014-07-24 17:47:32','2014-07-24 17:47:32'),(8,1,'Post 8',NULL,NULL,1,'2014-07-24 17:47:32','2014-07-24 17:47:32'),(9,1,'Post 9',NULL,NULL,1,'2014-07-24 17:47:32','2014-07-24 17:47:32'),(10,1,'Tieu de moi 10',NULL,NULL,1,'2014-07-24 17:47:32','2014-09-09 17:44:02'),(11,2,'Example 1','Description Example 1',NULL,1,'2014-08-26 18:40:54','2014-08-26 18:40:54'),(12,1,'Bài viết mới 12','','',0,'2014-09-09 17:50:47','2014-09-09 17:50:47'),(13,1,'Bài viết mới 13','','',0,'2014-09-09 17:51:56','2014-09-09 17:51:56'),(14,1,'Bài viết mới 13 A','','',0,'2014-09-09 17:51:56','2014-09-09 17:51:56'),(15,1,'Bài viết mới 14','','',0,'2014-09-09 17:53:08','2014-09-09 17:53:08'),(16,1,'Bài viết mới 15','','',0,'2014-09-09 17:59:13','2014-09-09 17:59:13'),(17,1,'Bài viết mới 16 A',NULL,NULL,NULL,'2014-09-09 18:03:05','2014-09-09 18:03:05');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
