/*
SQLyog Ultimate v10.42 
MySQL - 5.6.24-0ubuntu2 : Database - cloud_services
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`cloud_services` /*!40100 DEFAULT CHARACTER SET latin1 */;

/*Table structure for table `cs_config` */

DROP TABLE IF EXISTS `cs_config`;

CREATE TABLE `cs_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `theme_style` varchar(20) DEFAULT NULL,
  `theme_color` varchar(20) DEFAULT NULL,
  `project` varchar(20) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `cs_config` */

insert  into `cs_config`(`id`,`theme_style`,`theme_color`,`project`,`deleted`) values (1,'layout','smart_cafe_blue','smart_cafe',0),(2,'layout4','light','smart_cafe',0),(3,'layout','darkblue','smart_cafe',0),(4,'layout','blue','homeland',0);

/*Table structure for table `cs_config_user` */

DROP TABLE IF EXISTS `cs_config_user`;

CREATE TABLE `cs_config_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `config_id` int(11) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `cs_config_user` */

insert  into `cs_config_user`(`id`,`username`,`config_id`,`deleted`) values (1,'root',2,0),(2,'root',4,0);

/*Table structure for table `sc_customer` */

DROP TABLE IF EXISTS `sc_customer`;

CREATE TABLE `sc_customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `time_created` datetime DEFAULT NULL,
  `user_created` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `time_modified` datetime DEFAULT NULL,
  `user_modified` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` tinyint(1) DEFAULT '1',
  `deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `sc_customer` */

insert  into `sc_customer`(`id`,`customer_name`,`address`,`phone`,`time_created`,`user_created`,`time_modified`,`user_modified`,`type`,`deleted`) values (1,'Home','Address 1','123',NULL,NULL,NULL,NULL,1,0),(2,'Cus1','Add1','Phone1',NULL,NULL,NULL,NULL,1,0),(3,'Cus2','Add2','Phone2',NULL,NULL,NULL,NULL,1,0),(4,'Home2','Address 1','123',NULL,NULL,NULL,NULL,1,1),(5,'Home3','Address 1','123',NULL,NULL,NULL,NULL,1,1),(6,'Home1','Address 1','123',NULL,NULL,NULL,NULL,1,1),(7,'Cus3','Add3','Phone3',NULL,NULL,NULL,NULL,1,0),(8,'Cus4','Add4','Phone4',NULL,NULL,NULL,NULL,1,0),(9,'Cus5','Add4','Phone4',NULL,NULL,NULL,NULL,1,0),(10,'Cus6','Add4','Phone4',NULL,NULL,NULL,NULL,1,0),(11,'Cus7','Add4','Phone4',NULL,NULL,NULL,NULL,1,0),(12,'Cus8','Add4','Phone4',NULL,NULL,NULL,NULL,1,0),(13,'Cus9','Add4','Phone4',NULL,NULL,NULL,NULL,1,0),(14,'Cus10','Add4','Phone4',NULL,NULL,NULL,NULL,1,0),(15,'Cus11','Add4','Phone4',NULL,NULL,NULL,NULL,1,0),(16,'Cus12','Add4','Phone4',NULL,NULL,NULL,NULL,1,0),(17,'Cus13','Add4','Phone4',NULL,NULL,NULL,NULL,1,0),(18,'Cus14','Add4','Phone4',NULL,NULL,NULL,NULL,1,0);

/*Table structure for table `sc_group` */

DROP TABLE IF EXISTS `sc_group`;

CREATE TABLE `sc_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `admin` tinyint(1) DEFAULT '0',
  `parent_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `permission` text COLLATE utf8_unicode_ci,
  `time_created` datetime DEFAULT NULL,
  `user_created` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `time_modified` datetime DEFAULT NULL,
  `user_modified` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `sc_group` */

insert  into `sc_group`(`id`,`group_name`,`admin`,`parent_id`,`customer_id`,`permission`,`time_created`,`user_created`,`time_modified`,`user_modified`,`deleted`) values (1,'Admin',1,0,1,'{\"16\":{\"view\":1,\"add\":1,\"edit\":1,\"delete\":1,\"export\":1},\"2\":{\"view\":1},\"3\":{\"view\":1},\"4\":{\"view\":1},\"5\":{\"view\":1},\"6\":{\"view\":1},\"7\":{\"view\":1},\"8\":{\"view\":1},\"9\":{\"view\":1},\"10\":{\"view\":1}}',NULL,NULL,NULL,NULL,0),(2,'Boss',0,0,2,'{\"16\":{\"view\":1,\"add\":1,\"edit\":1,\"delete\":1,\"export\":1},\"21\":{\"view\":1},\"24\":{\"view\":1},\"14\":{\"view\":1},\"20\":{\"view\":1},\"23\":{\"view\":1},\"15\":{\"view\":1}}',NULL,NULL,NULL,NULL,0),(3,'Cashier',0,2,2,'{\"1\":{\"view\":1},\"2\":{\"view\":1},\"3\":{\"view\":1},\"4\":{\"view\":1},\"5\":{\"view\":1},\"6\":{\"view\":1}}',NULL,NULL,NULL,NULL,0);

/*Table structure for table `sc_menu` */

DROP TABLE IF EXISTS `sc_menu`;

CREATE TABLE `sc_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `route` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `uri` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `params` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'view,add,edit,delete,import,export',
  `ordering` int(11) DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'icon-folder',
  `time_created` datetime DEFAULT NULL,
  `user_created` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `time_modified` datetime DEFAULT NULL,
  `user_modified` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `sc_menu` */

insert  into `sc_menu`(`id`,`menu_name`,`parent_id`,`route`,`url`,`uri`,`params`,`ordering`,`icon`,`time_created`,`user_created`,`time_modified`,`user_modified`,`deleted`) values (14,'Management',0,'#',NULL,NULL,'view,add,edit,delete,import,export',2,'icon-folder',NULL,NULL,NULL,NULL,0),(15,'General',14,'#',NULL,NULL,'view,add,edit,delete,import,export',1,'icon-folder',NULL,NULL,NULL,NULL,0),(16,'Customer',14,'SmartCafe\\AdminBundle\\Controller\\CustomerController','sc_admin_customer','/smart-cafe/admin/customer.html','view,add,edit,delete',1,'icon-folder',NULL,NULL,NULL,NULL,0),(17,'Product',15,'SmartCafe\\AdminBundle\\Controller\\ProductController','sc_admin_product','/smart-cafe/admin/product.html','view,add,edit,delete',2,'icon-folder',NULL,NULL,NULL,NULL,0),(18,'Config',14,'#',NULL,NULL,'view,add,edit,delete,import,export',2,'icon-folder',NULL,NULL,NULL,NULL,0),(20,'Dashboard',0,'#',NULL,NULL,'view',1,'icon-home',NULL,NULL,NULL,NULL,0),(21,'Real time',20,'SmartCafe\\AdminBundle\\Controller\\DashboardController','sc_admin_dashboard','/smart-cafe/admin/dashboard.html','view,add,edit,delete,import,export',NULL,'icon-folder',NULL,NULL,NULL,NULL,0),(22,'Orders',20,'SmartCafe\\AdminBundle\\Controller\\DashboardController','sc_admin_order','/smart-cafe/admin/order.html','view,add,edit,delete,import,export',NULL,'icon-folder',NULL,NULL,NULL,NULL,0),(23,'Other',15,'#',NULL,NULL,'view,add,edit,delete,import,export',2,'icon-folder',NULL,NULL,NULL,NULL,0),(24,'Theme',23,'SmartCafe\\AdminBundle\\Controller\\ThemeController','sc_admin_theme','/smart-cafe/admin/theme.html','view,add,edit,delete,import,export',1,'icon-folder',NULL,NULL,NULL,NULL,0);

/*Table structure for table `sc_user` */

DROP TABLE IF EXISTS `sc_user`;

CREATE TABLE `sc_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fullname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` int(11) DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `time_created` datetime DEFAULT NULL,
  `user_created` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `time_modified` datetime DEFAULT NULL,
  `user_modified` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `sc_user` */

insert  into `sc_user`(`id`,`username`,`password`,`fullname`,`email`,`address`,`phone`,`country`,`avatar`,`group_id`,`time_created`,`user_created`,`time_modified`,`user_modified`,`deleted`) values (1,'root','051181fa3bf066ee4e1034013fa4940f','Administrator','admin@smartcafe.com','Ho Chi Minh City','+84123',236,NULL,1,NULL,NULL,NULL,NULL,0),(2,'boss','1afb1d07cd2b3201aae3e28a18916b3b','Boss','boss@boss.com','HCMC','+841234',236,NULL,2,NULL,NULL,NULL,NULL,0),(3,'test','8399bc113c4097b7391406efd329e01e','Tester','test@test.com','Ho Chi Minh City',NULL,236,NULL,2,NULL,NULL,NULL,NULL,0);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
