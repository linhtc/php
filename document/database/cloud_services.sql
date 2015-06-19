-- MySQL dump 10.13  Distrib 5.6.24, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: cloud_services
-- ------------------------------------------------------
-- Server version	5.6.24-0ubuntu2

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
-- Table structure for table `sc_customer`
--

DROP TABLE IF EXISTS `sc_customer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sc_customer`
--

LOCK TABLES `sc_customer` WRITE;
/*!40000 ALTER TABLE `sc_customer` DISABLE KEYS */;
INSERT INTO `sc_customer` VALUES (1,'Home',NULL,NULL,NULL,NULL,NULL,NULL,1,0),(2,'Cus1',NULL,NULL,NULL,NULL,NULL,NULL,1,0);
/*!40000 ALTER TABLE `sc_customer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sc_group`
--

DROP TABLE IF EXISTS `sc_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sc_group`
--

LOCK TABLES `sc_group` WRITE;
/*!40000 ALTER TABLE `sc_group` DISABLE KEYS */;
INSERT INTO `sc_group` VALUES (1,'Admin',1,0,1,'',NULL,NULL,NULL,NULL,0),(2,'Boss',0,0,2,'{\"1\":{\"view\":1},\"2\":{\"view\":1},\"3\":{\"view\":1},\"4\":{\"view\":1},\"5\":{\"view\":1},\"6\":{\"view\":1},\"7\":{\"view\":1},\"8\":{\"view\":1},\"9\":{\"view\":1},\"10\":{\"view\":1}}',NULL,NULL,NULL,NULL,0),(3,'Cashier',0,2,2,'{\"1\":{\"view\":1},\"2\":{\"view\":1},\"3\":{\"view\":1},\"4\":{\"view\":1},\"5\":{\"view\":1},\"6\":{\"view\":1}}',NULL,NULL,NULL,NULL,0);
/*!40000 ALTER TABLE `sc_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sc_menu`
--

DROP TABLE IF EXISTS `sc_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sc_menu`
--

LOCK TABLES `sc_menu` WRITE;
/*!40000 ALTER TABLE `sc_menu` DISABLE KEYS */;
INSERT INTO `sc_menu` VALUES (1,'eCommerce',0,'#',NULL,NULL,'view,add,edit,delete,import,export',1,'icon-basket',NULL,NULL,NULL,NULL,0),(2,'Dashboard',1,'AdminBundle\\Controller\\DashboardController','sc_admin_dashboard','/admin/dashboard.html','view',1,'icon-folder',NULL,NULL,NULL,NULL,0),(3,'Orders',1,'AdminBundle\\Controller\\DashboardController','sc_admin_dashboard','/admin/order.html','view,add,edit,delete,import,export',2,'icon-folder',NULL,NULL,NULL,NULL,0),(4,'Products',1,'AdminBundle\\Controller\\DashboardController','sc_admin_dashboard','/admin/product.html','view,add,edit,delete,import,export',3,'icon-folder',NULL,NULL,NULL,NULL,0),(5,'Multi Level Menu',0,'#',NULL,NULL,'view,add,edit,delete,import,export',2,'icon-folder',NULL,NULL,NULL,NULL,0),(6,'Sample Link 1',5,'#',NULL,NULL,'view,add,edit,delete,import,export',1,'icon-folder',NULL,NULL,NULL,NULL,0),(7,'Link 1',6,'#',NULL,NULL,'view,add,edit,delete,import,export',1,'icon-folder',NULL,NULL,NULL,NULL,0),(8,'Child 1',7,'AdminBundle\\Controller\\DashboardController','sc_admin_dashboard','/admin/child1.html','view,add,edit,delete,import,export',1,'icon-folder',NULL,NULL,NULL,NULL,0),(9,'Child 2',7,'AdminBundle\\Controller\\DashboardController','sc_admin_dashboard','/admin/child2.html','view,add,edit,delete,import,export',2,'icon-folder',NULL,NULL,NULL,NULL,0),(10,'Link 2',6,'AdminBundle\\Controller\\DashboardController','sc_admin_dashboard','/admin/link2.html','view,add,edit,delete,import,export',2,'icon-folder',NULL,NULL,NULL,NULL,0),(11,'Link 3',7,'#',NULL,NULL,'view,add,edit,delete,import,export',3,'icon-folder',NULL,NULL,NULL,NULL,0),(12,'Link 3.1',11,'#',NULL,NULL,'view,add,edit,delete,import,export',1,'icon-folder',NULL,NULL,NULL,NULL,0),(13,'Link 3.1.1',12,'AdminBundle\\Controller\\DashboardController','sc_admin_dashboard','/admin/link31.html','view,add,edit,delete,import,export',1,'icon-folder',NULL,NULL,NULL,NULL,0),(14,'Management',0,'#',NULL,NULL,'view,add,edit,delete,import,export',4,'icon-folder',NULL,NULL,NULL,NULL,0),(15,'General',14,'#',NULL,NULL,'view,add,edit,delete,import,export',1,'icon-folder',NULL,NULL,NULL,NULL,0),(16,'Customer',15,'AdminBundle\\Controller\\CustomerController','sc_admin_customer','/admin/customer.html','view,add,edit,delete',1,'icon-folder',NULL,NULL,NULL,NULL,0),(17,'Menu',15,'AdminBundle\\Controller\\MenuController','sc_admin_menu','/admin/menu.html','view,add,edit,delete',2,'icon-folder',NULL,NULL,NULL,NULL,0),(18,'Config',14,'#',NULL,NULL,'view,add,edit,delete,import,export',2,'icon-folder',NULL,NULL,NULL,NULL,0),(19,'Themes',18,'AdminBundle\\Controller\\DashboardController','sc_admin_dashboard','/admin/config.html','view,add,edit,delete,import,export',1,'icon-folder',NULL,NULL,NULL,NULL,0);
/*!40000 ALTER TABLE `sc_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sc_user`
--

DROP TABLE IF EXISTS `sc_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sc_user`
--

LOCK TABLES `sc_user` WRITE;
/*!40000 ALTER TABLE `sc_user` DISABLE KEYS */;
INSERT INTO `sc_user` VALUES (1,'root','051181fa3bf066ee4e1034013fa4940f','Administrator','admin@smartcafe.com','Ho Chi Minh City','+84123',236,NULL,1,NULL,NULL,NULL,NULL,0),(2,'boss','1afb1d07cd2b3201aae3e28a18916b3b','Boss','boss@boss.com','HCMC','+841234',236,NULL,2,NULL,NULL,NULL,NULL,0);
/*!40000 ALTER TABLE `sc_user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-06-19 16:04:30
