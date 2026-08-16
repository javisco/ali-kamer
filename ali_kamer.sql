-- MySQL dump 10.13  Distrib 8.0.46, for Linux (x86_64)
--
-- Host: localhost    Database: ali_kamer
-- ------------------------------------------------------
-- Server version	8.0.46-0ubuntu0.24.04.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin_logs`
--

DROP TABLE IF EXISTS `admin_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` bigint unsigned NOT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `target_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target_id` bigint unsigned DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `admin_logs_target_type_target_id_index` (`target_type`,`target_id`),
  KEY `admin_logs_admin_id_index` (`admin_id`),
  CONSTRAINT `admin_logs_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_logs`
--

LOCK TABLES `admin_logs` WRITE;
/*!40000 ALTER TABLE `admin_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `agencies`
--

DROP TABLE IF EXISTS `agencies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `agencies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `agencies_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `agencies`
--

LOCK TABLES `agencies` WRITE;
/*!40000 ALTER TABLE `agencies` DISABLE KEYS */;
INSERT INTO `agencies` VALUES (1,'General','general','699001122','contact@general.cm',1,'2026-08-16 14:37:29','2026-08-16 14:37:29'),(2,'Global Voyage','global-voyage','677334455','contact@globalvoyage.cm',1,'2026-08-16 14:37:30','2026-08-16 14:37:30');
/*!40000 ALTER TABLE `agencies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `agency_cities`
--

DROP TABLE IF EXISTS `agency_cities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `agency_cities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `agency_id` bigint unsigned NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `agency_cities_agency_id_city_unique` (`agency_id`,`city`),
  CONSTRAINT `agency_cities_agency_id_foreign` FOREIGN KEY (`agency_id`) REFERENCES `agencies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `agency_cities`
--

LOCK TABLES `agency_cities` WRITE;
/*!40000 ALTER TABLE `agency_cities` DISABLE KEYS */;
INSERT INTO `agency_cities` VALUES (1,1,'Douala',1,'2026-08-16 14:37:29','2026-08-16 14:37:29'),(2,1,'Yaoundé',1,'2026-08-16 14:37:29','2026-08-16 14:37:29'),(3,1,'Bafoussam',1,'2026-08-16 14:37:29','2026-08-16 14:37:29'),(4,1,'Dschang',1,'2026-08-16 14:37:29','2026-08-16 14:37:29'),(5,2,'Douala',1,'2026-08-16 14:37:30','2026-08-16 14:37:30'),(6,2,'Yaoundé',1,'2026-08-16 14:37:30','2026-08-16 14:37:30'),(7,2,'Bamenda',1,'2026-08-16 14:37:30','2026-08-16 14:37:30'),(8,2,'Kribi',1,'2026-08-16 14:37:30','2026-08-16 14:37:30');
/*!40000 ALTER TABLE `agency_cities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `agency_counters`
--

DROP TABLE IF EXISTS `agency_counters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `agency_counters` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `agency_id` bigint unsigned NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `district` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `landmark` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `agency_counters_agency_id_city_index` (`agency_id`,`city`),
  CONSTRAINT `agency_counters_agency_id_foreign` FOREIGN KEY (`agency_id`) REFERENCES `agencies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `agency_counters`
--

LOCK TABLES `agency_counters` WRITE;
/*!40000 ALTER TABLE `agency_counters` DISABLE KEYS */;
INSERT INTO `agency_counters` VALUES (1,1,'Douala','Akwa','Face Ancien Dalip','699001123',1,'2026-08-16 14:37:29','2026-08-16 14:37:29'),(2,1,'Yaoundé','Mvan','Près du carrefour Mvan','699001124',1,'2026-08-16 14:37:29','2026-08-16 14:37:29'),(3,1,'Bafoussam','Marché B','Gare routière','699001125',1,'2026-08-16 14:37:30','2026-08-16 14:37:30'),(4,2,'Douala','Bessengue','À côté de la gare Camrail','677334456',1,'2026-08-16 14:37:30','2026-08-16 14:37:30'),(5,2,'Yaoundé','Biyem-Assi','Carrefour Acacia','677334457',1,'2026-08-16 14:37:30','2026-08-16 14:37:30'),(6,2,'Kribi','Centre-ville','Près de la poste','677334458',1,'2026-08-16 14:37:30','2026-08-16 14:37:30');
/*!40000 ALTER TABLE `agency_counters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blacklist`
--

DROP TABLE IF EXISTS `blacklist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blacklist` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cni_hash` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_momo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `blacklist_created_by_foreign` (`created_by`),
  KEY `blacklist_cni_hash_index` (`cni_hash`),
  KEY `blacklist_phone_momo_index` (`phone_momo`),
  KEY `blacklist_phone_number_index` (`phone_number`),
  CONSTRAINT `blacklist_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blacklist`
--

LOCK TABLES `blacklist` WRITE;
/*!40000 ALTER TABLE `blacklist` DISABLE KEYS */;
/*!40000 ALTER TABLE `blacklist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` smallint unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`),
  KEY `categories_parent_id_foreign` (`parent_id`),
  CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,NULL,'Textile & Mode','textile-mode',NULL,1,0,'2026-08-16 14:37:25','2026-08-16 14:37:25'),(2,1,'Vêtements homme','vetements-homme',NULL,1,0,'2026-08-16 14:37:25','2026-08-16 14:37:25'),(3,1,'Vêtements femme','vetements-femme',NULL,1,0,'2026-08-16 14:37:25','2026-08-16 14:37:25'),(4,1,'Chaussures','chaussures',NULL,1,0,'2026-08-16 14:37:25','2026-08-16 14:37:25'),(5,1,'Accessoires','accessoires',NULL,1,0,'2026-08-16 14:37:25','2026-08-16 14:37:25'),(6,NULL,'Électronique','electronique',NULL,1,0,'2026-08-16 14:37:25','2026-08-16 14:37:25'),(7,6,'Téléphones','telephones',NULL,1,0,'2026-08-16 14:37:25','2026-08-16 14:37:25'),(8,6,'Ordinateurs','ordinateurs',NULL,1,0,'2026-08-16 14:37:25','2026-08-16 14:37:25'),(9,6,'Accessoires tech','accessoires-tech',NULL,1,0,'2026-08-16 14:37:25','2026-08-16 14:37:25'),(10,6,'TV & Audio','tv-audio',NULL,1,0,'2026-08-16 14:37:25','2026-08-16 14:37:25'),(11,NULL,'Alimentation','alimentation',NULL,1,0,'2026-08-16 14:37:25','2026-08-16 14:37:25'),(12,11,'Épicerie','epicerie',NULL,1,0,'2026-08-16 14:37:25','2026-08-16 14:37:25'),(13,11,'Boissons','boissons',NULL,1,0,'2026-08-16 14:37:25','2026-08-16 14:37:25'),(14,11,'Produits locaux','produits-locaux',NULL,1,0,'2026-08-16 14:37:25','2026-08-16 14:37:25'),(15,NULL,'Beauté & Cosmétiques','beaute-cosmetiques',NULL,1,0,'2026-08-16 14:37:25','2026-08-16 14:37:25'),(16,15,'Soins visage','soins-visage',NULL,1,0,'2026-08-16 14:37:25','2026-08-16 14:37:25'),(17,15,'Soins cheveux','soins-cheveux',NULL,1,0,'2026-08-16 14:37:25','2026-08-16 14:37:25'),(18,15,'Parfums','parfums',NULL,1,0,'2026-08-16 14:37:25','2026-08-16 14:37:25'),(19,NULL,'Maison & Décoration','maison-decoration',NULL,1,0,'2026-08-16 14:37:25','2026-08-16 14:37:25'),(20,19,'Meubles','meubles',NULL,1,0,'2026-08-16 14:37:25','2026-08-16 14:37:25'),(21,19,'Cuisine','cuisine',NULL,1,0,'2026-08-16 14:37:25','2026-08-16 14:37:25'),(22,19,'Décoration','decoration',NULL,1,0,'2026-08-16 14:37:25','2026-08-16 14:37:25'),(23,NULL,'Sport & Loisirs','sport-loisirs',NULL,1,0,'2026-08-16 14:37:25','2026-08-16 14:37:25'),(24,23,'Sport','sport',NULL,1,0,'2026-08-16 14:37:25','2026-08-16 14:37:25'),(25,23,'Jeux','jeux',NULL,1,0,'2026-08-16 14:37:25','2026-08-16 14:37:25'),(26,23,'Musique','musique',NULL,1,0,'2026-08-16 14:37:25','2026-08-16 14:37:25'),(27,NULL,'Auto & Moto','auto-moto',NULL,1,0,'2026-08-16 14:37:25','2026-08-16 14:37:25'),(28,27,'Pièces auto','pieces-auto',NULL,1,0,'2026-08-16 14:37:25','2026-08-16 14:37:25'),(29,27,'Accessoires moto','accessoires-moto',NULL,1,0,'2026-08-16 14:37:25','2026-08-16 14:37:25'),(30,NULL,'Autre','autre',NULL,1,0,'2026-08-16 14:37:25','2026-08-16 14:37:25');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `conversations`
--

DROP TABLE IF EXISTS `conversations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `conversations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `buyer_id` bigint unsigned NOT NULL,
  `shop_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned DEFAULT NULL,
  `last_message_at` timestamp NULL DEFAULT NULL,
  `is_archived` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `conversations_buyer_id_shop_id_unique` (`buyer_id`,`shop_id`),
  KEY `conversations_shop_id_foreign` (`shop_id`),
  KEY `conversations_product_id_foreign` (`product_id`),
  KEY `conversations_last_message_at_index` (`last_message_at`),
  CONSTRAINT `conversations_buyer_id_foreign` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `conversations_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
  CONSTRAINT `conversations_shop_id_foreign` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `conversations`
--

LOCK TABLES `conversations` WRITE;
/*!40000 ALTER TABLE `conversations` DISABLE KEYS */;
/*!40000 ALTER TABLE `conversations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dispute_evidences`
--

DROP TABLE IF EXISTS `dispute_evidences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dispute_evidences` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `dispute_id` bigint unsigned NOT NULL,
  `submitted_by` bigint unsigned NOT NULL,
  `type` enum('photo','video','document','text') COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dispute_evidences_dispute_id_foreign` (`dispute_id`),
  KEY `dispute_evidences_submitted_by_foreign` (`submitted_by`),
  CONSTRAINT `dispute_evidences_dispute_id_foreign` FOREIGN KEY (`dispute_id`) REFERENCES `disputes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `dispute_evidences_submitted_by_foreign` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dispute_evidences`
--

LOCK TABLES `dispute_evidences` WRITE;
/*!40000 ALTER TABLE `dispute_evidences` DISABLE KEYS */;
/*!40000 ALTER TABLE `dispute_evidences` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `disputes`
--

DROP TABLE IF EXISTS `disputes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `disputes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `initiator_id` bigint unsigned NOT NULL,
  `type` enum('not_received','not_conform','damaged','incorrect','incomplete','empty_package','seller_unresponsive') COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('open','seller_replied','under_review','resolved','closed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `resolution` enum('refund_buyer','pay_seller','partial_refund','return_required','buyer_bad_faith') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `resolution_amount` int unsigned DEFAULT NULL,
  `resolution_note` text COLLATE utf8mb4_unicode_ci,
  `resolver_id` bigint unsigned DEFAULT NULL,
  `seller_reply_deadline` timestamp NULL DEFAULT NULL,
  `resolved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `disputes_initiator_id_foreign` (`initiator_id`),
  KEY `disputes_resolver_id_foreign` (`resolver_id`),
  KEY `disputes_status_index` (`status`),
  KEY `disputes_order_id_index` (`order_id`),
  CONSTRAINT `disputes_initiator_id_foreign` FOREIGN KEY (`initiator_id`) REFERENCES `users` (`id`),
  CONSTRAINT `disputes_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `disputes_resolver_id_foreign` FOREIGN KEY (`resolver_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `disputes`
--

LOCK TABLES `disputes` WRITE;
/*!40000 ALTER TABLE `disputes` DISABLE KEYS */;
/*!40000 ALTER TABLE `disputes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kyc_documents`
--

DROP TABLE IF EXISTS `kyc_documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kyc_documents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `cni_front_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cni_back_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `selfie_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rccm_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `momo_name_check` tinyint(1) NOT NULL DEFAULT '0',
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `reviewer_id` bigint unsigned DEFAULT NULL,
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kyc_documents_user_id_foreign` (`user_id`),
  KEY `kyc_documents_reviewer_id_foreign` (`reviewer_id`),
  KEY `kyc_documents_status_index` (`status`),
  CONSTRAINT `kyc_documents_reviewer_id_foreign` FOREIGN KEY (`reviewer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `kyc_documents_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kyc_documents`
--

LOCK TABLES `kyc_documents` WRITE;
/*!40000 ALTER TABLE `kyc_documents` DISABLE KEYS */;
INSERT INTO `kyc_documents` VALUES (1,2,'okay','rien','rien du tout','noting',0,'approved',NULL,NULL,NULL,'2026-08-16 14:37:25','2026-08-16 14:37:25'),(2,3,'okay','rien','rien du tout','noting',0,'approved',NULL,NULL,NULL,'2026-08-16 14:37:25','2026-08-16 14:37:25');
/*!40000 ALTER TABLE `kyc_documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `conversation_id` bigint unsigned NOT NULL,
  `sender_id` bigint unsigned NOT NULL,
  `type` enum('text','image','pdf','carte_produit') COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` text COLLATE utf8mb4_unicode_ci,
  `attachment_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachment_size` int unsigned DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `read_at` timestamp NULL DEFAULT NULL,
  `sent_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `messages_conversation_id_sent_at_index` (`conversation_id`,`sent_at`),
  KEY `messages_sender_id_is_read_index` (`sender_id`,`is_read`),
  CONSTRAINT `messages_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_07_15_102212_create_agencies_table',1),(5,'2026_07_15_102939_create_shops_table',1),(6,'2026_07_15_105533_kyc_documents',1),(7,'2026_07_15_105733_blacklist',1),(8,'2026_07_15_105836_categories',1),(9,'2026_07_15_105922_create_products_table',1),(10,'2026_07_15_110328_create_orders_table',1),(11,'2026_07_15_110716_order_items',1),(12,'2026_07_15_110813_provider_transactions',1),(13,'2026_07_15_110900_wallet_transactions',1),(14,'2026_07_15_111133_disputes',1),(15,'2026_07_15_111333_conversations',1),(16,'2026_07_15_111421_messages',1),(17,'2026_07_15_111450_notifications',1),(18,'2026_07_15_111601_reviews',1),(19,'2026_07_15_111634_admin_logs',1),(20,'2026_07_15_111656_tutorials',1),(21,'2026_07_22_083629_product_image',1),(22,'2026_07_25_112406_agency_counters',1),(23,'2026_07_25_112532_secretary_counters',1),(24,'2026_07_26_084157_order_payments',1),(25,'2026_07_26_084244_order_shipments',1),(26,'2026_08_04_102845_dispute_evidences',1),(27,'2026_08_10_043644_create_personal_access_tokens_table',1),(28,'2026_08_13_224846_create_agency_cities_table',1),(29,'2026_08_16_065958_add_transport_payment_reference_to_order_shipments',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `channel` enum('whatsapp','sms','push','email') COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` json DEFAULT NULL,
  `status` enum('pending','sent','delivered','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `retry_count` tinyint unsigned NOT NULL DEFAULT '0',
  `error_message` text COLLATE utf8mb4_unicode_ci,
  `sent_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_user_id_status_index` (`user_id`,`status`),
  KEY `notifications_type_index` (`type`),
  CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `product_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int unsigned NOT NULL,
  `unit_price` int unsigned NOT NULL,
  `subtotal` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (1,1,4,'Casque Audio Bluetooth Réduction de Bruit',1,19,19,'2026-08-16 14:39:49','2026-08-16 14:39:49'),(2,2,25,'Manette de Jeu PC/Android Bluetooth',1,22,22,'2026-08-16 14:40:35','2026-08-16 14:40:35'),(3,3,9,'Clé USB 128Go USB 3.0 Haute Vitesse',1,25,25,'2026-08-16 15:21:42','2026-08-16 15:21:42'),(4,4,13,'Souris Sans Fil Ergonomique',1,22,22,'2026-08-16 15:22:32','2026-08-16 15:22:32');
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_payments`
--

DROP TABLE IF EXISTS `order_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `method` enum('campay','notchpay','virtuel_card') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'campay',
  `status` enum('pending','processing','succeeded','failed','refunded') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `provider_reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `idempotency_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payer_phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payer_operator` enum('mtn','orange') COLLATE utf8mb4_unicode_ci NOT NULL,
  `manual_transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `manual_validated_by` bigint unsigned DEFAULT NULL,
  `manual_validated_at` timestamp NULL DEFAULT NULL,
  `provider_response` json DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_payments_order_id_unique` (`order_id`),
  UNIQUE KEY `order_payments_idempotency_key_unique` (`idempotency_key`),
  KEY `order_payments_manual_validated_by_foreign` (`manual_validated_by`),
  KEY `order_payments_status_index` (`status`),
  CONSTRAINT `order_payments_manual_validated_by_foreign` FOREIGN KEY (`manual_validated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `order_payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_payments`
--

LOCK TABLES `order_payments` WRITE;
/*!40000 ALTER TABLE `order_payments` DISABLE KEYS */;
INSERT INTO `order_payments` VALUES (1,1,'campay','succeeded','0c7630ef-2f38-478c-9922-e914982f57ee','57c5fcd5-7fcb-416a-b93c-aa63274d0cdc','677777777','mtn',NULL,NULL,NULL,'{\"operator\": \"MTN\", \"reference\": \"0c7630ef-2f38-478c-9922-e914982f57ee\", \"ussd_code\": \"*126#\"}','2026-08-16 14:40:28','2026-08-16 14:39:50','2026-08-16 14:40:28'),(2,2,'campay','succeeded','2bad630b-9a65-414e-9fdb-9fbc516c2855','ac7a7d36-1d86-4c21-a407-a7d87908f5a7','677777777','mtn',NULL,NULL,NULL,'{\"operator\": \"MTN\", \"reference\": \"2bad630b-9a65-414e-9fdb-9fbc516c2855\", \"ussd_code\": \"*126#\"}','2026-08-16 14:41:11','2026-08-16 14:40:35','2026-08-16 14:41:11'),(3,3,'campay','pending',NULL,'43021114-3f01-4a64-a84e-6f441230dc2c','677777777','mtn',NULL,NULL,NULL,NULL,NULL,'2026-08-16 15:21:42','2026-08-16 15:21:44'),(4,4,'campay','succeeded','f20c2af9-cb82-4940-b50a-c96808f5457d','a952e2d5-7f18-4ed7-bfb4-b86dbcfe63b4','677777777','mtn',NULL,NULL,NULL,'{\"operator\": \"MTN\", \"reference\": \"f20c2af9-cb82-4940-b50a-c96808f5457d\", \"ussd_code\": \"*126#\"}','2026-08-16 15:23:03','2026-08-16 15:22:32','2026-08-16 15:23:03');
/*!40000 ALTER TABLE `order_payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_shipments`
--

DROP TABLE IF EXISTS `order_shipments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_shipments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `type` enum('interurban','local') COLLATE utf8mb4_unicode_ci NOT NULL,
  `agency_id` bigint unsigned DEFAULT NULL,
  `shipping_included` tinyint(1) NOT NULL,
  `transport_fee` int unsigned NOT NULL DEFAULT '0',
  `transport_fee_paid` tinyint(1) NOT NULL DEFAULT '0',
  `transport_fee_paid_at` timestamp NULL DEFAULT NULL,
  `transport_payment_reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `origin_counter_id` bigint unsigned DEFAULT NULL,
  `destination_counter_id` bigint unsigned DEFAULT NULL,
  `registered_by` bigint unsigned DEFAULT NULL,
  `validated_by` bigint unsigned DEFAULT NULL,
  `local_carrier_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `local_carrier_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recipient_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `recipient_phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `destination_city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `registered_at` timestamp NULL DEFAULT NULL,
  `shipped_at` timestamp NULL DEFAULT NULL,
  `departed_at` timestamp NULL DEFAULT NULL,
  `arrived_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_shipments_order_id_unique` (`order_id`),
  KEY `order_shipments_agency_id_foreign` (`agency_id`),
  KEY `order_shipments_origin_counter_id_foreign` (`origin_counter_id`),
  KEY `order_shipments_destination_counter_id_foreign` (`destination_counter_id`),
  KEY `order_shipments_registered_by_foreign` (`registered_by`),
  KEY `order_shipments_validated_by_foreign` (`validated_by`),
  CONSTRAINT `order_shipments_agency_id_foreign` FOREIGN KEY (`agency_id`) REFERENCES `agencies` (`id`) ON DELETE SET NULL,
  CONSTRAINT `order_shipments_destination_counter_id_foreign` FOREIGN KEY (`destination_counter_id`) REFERENCES `agency_counters` (`id`) ON DELETE SET NULL,
  CONSTRAINT `order_shipments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_shipments_origin_counter_id_foreign` FOREIGN KEY (`origin_counter_id`) REFERENCES `agency_counters` (`id`) ON DELETE SET NULL,
  CONSTRAINT `order_shipments_registered_by_foreign` FOREIGN KEY (`registered_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `order_shipments_validated_by_foreign` FOREIGN KEY (`validated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_shipments`
--

LOCK TABLES `order_shipments` WRITE;
/*!40000 ALTER TABLE `order_shipments` DISABLE KEYS */;
INSERT INTO `order_shipments` VALUES (1,1,'interurban',1,1,24,0,NULL,NULL,2,3,7,8,NULL,NULL,'Acheteur Eric','+237678538747','Bafoussam','2026-08-16 14:43:05',NULL,NULL,'2026-08-16 15:11:21','2026-08-16 14:39:49','2026-08-16 15:11:21'),(2,2,'interurban',1,0,56,0,NULL,NULL,2,3,7,8,NULL,NULL,'Acheteur Eric','+237678538747','Bafoussam','2026-08-16 15:08:57',NULL,NULL,'2026-08-16 15:15:04','2026-08-16 14:40:35','2026-08-16 15:15:04'),(3,3,'interurban',NULL,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Acheteur Eric','+237678538747','Bafoussam',NULL,NULL,NULL,NULL,'2026-08-16 15:21:42','2026-08-16 15:21:42'),(4,4,'interurban',1,0,22,0,NULL,NULL,1,3,6,8,NULL,NULL,'Acheteur Eric','+237678538747','Bafoussam','2026-08-16 15:24:55',NULL,NULL,'2026-08-16 15:25:46','2026-08-16 15:22:32','2026-08-16 15:25:46');
/*!40000 ALTER TABLE `order_shipments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `buyer_id` bigint unsigned NOT NULL,
  `shop_id` bigint unsigned NOT NULL,
  `status` enum('pending','awaiting_payment','paid','preparing','registered_origin','in_transit','arrived_destination','awaiting_buyer_confirmation','completed','auto_completed','disputed','cancelled','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `subtotal` int unsigned NOT NULL,
  `shipping_fee` int unsigned NOT NULL DEFAULT '0',
  `protection_fee` int unsigned NOT NULL,
  `gateway_fee` int unsigned NOT NULL,
  `total_amount` int unsigned NOT NULL,
  `platform_commission` int unsigned NOT NULL,
  `agency_commission` int unsigned NOT NULL,
  `gateway_payout_fee` int unsigned NOT NULL,
  `net_amount` int unsigned NOT NULL,
  `financial_snapshot` json NOT NULL,
  `deposit_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otp_code` varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otp_expires_at` timestamp NULL DEFAULT NULL,
  `otp_used_at` timestamp NULL DEFAULT NULL,
  `timer_deadline` timestamp NULL DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `preparing_at` timestamp NULL DEFAULT NULL,
  `shipped_at` timestamp NULL DEFAULT NULL,
  `arrived_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `buyer_note` text COLLATE utf8mb4_unicode_ci,
  `cancellation_reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_reference_unique` (`reference`),
  UNIQUE KEY `orders_deposit_code_unique` (`deposit_code`),
  KEY `orders_status_index` (`status`),
  KEY `orders_buyer_id_index` (`buyer_id`),
  KEY `orders_shop_id_index` (`shop_id`),
  KEY `orders_timer_deadline_index` (`timer_deadline`),
  CONSTRAINT `orders_buyer_id_foreign` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`),
  CONSTRAINT `orders_shop_id_foreign` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,'ALK-2026-00001',4,1,'completed',19,0,0,0,19,1,0,0,18,'{\"agency_rate\": 0.01, \"payout_rate\": 0.01, \"gateway_rate\": 0.02, \"calculated_at\": \"2026-08-16T15:39:49.965168Z\", \"commission_rate\": 0.05, \"protection_rate\": 0.02}','FVHT5CLE','199455','2026-08-21 15:11:21','2026-08-16 15:12:00','2026-08-19 15:11:21','2026-08-16 14:40:28','2026-08-16 14:42:21','2026-08-16 14:43:05','2026-08-16 15:11:21','2026-08-16 15:12:00',NULL,NULL,NULL,'2026-08-16 14:39:49','2026-08-16 15:12:00'),(2,'ALK-2026-00002',4,1,'awaiting_buyer_confirmation',22,0,0,0,22,1,0,0,21,'{\"agency_rate\": 0.01, \"payout_rate\": 0.01, \"gateway_rate\": 0.02, \"calculated_at\": \"2026-08-16T15:40:35.389584Z\", \"commission_rate\": 0.05, \"protection_rate\": 0.02}','3BPZMSMK',NULL,NULL,NULL,'2026-08-19 15:15:04','2026-08-16 14:41:11','2026-08-16 14:41:34','2026-08-16 15:08:57','2026-08-16 15:15:04',NULL,NULL,NULL,NULL,'2026-08-16 14:40:35','2026-08-16 15:15:04'),(3,'ALK-2026-00003',4,1,'awaiting_payment',25,0,1,1,27,1,0,0,24,'{\"agency_rate\": 0.01, \"payout_rate\": 0.01, \"gateway_rate\": 0.02, \"calculated_at\": \"2026-08-16T16:21:42.255539Z\", \"commission_rate\": 0.05, \"protection_rate\": 0.02}','2UPVXZP9',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-08-16 15:21:42','2026-08-16 15:21:42'),(4,'ALK-2026-00004',4,1,'awaiting_buyer_confirmation',22,0,0,0,22,1,0,0,21,'{\"agency_rate\": 0.01, \"payout_rate\": 0.01, \"gateway_rate\": 0.02, \"calculated_at\": \"2026-08-16T16:22:32.114327Z\", \"commission_rate\": 0.05, \"protection_rate\": 0.02}','JSWYXLKC',NULL,NULL,NULL,'2026-08-19 15:25:46','2026-08-16 15:23:03','2026-08-16 15:24:07','2026-08-16 15:24:55','2026-08-16 15:25:46',NULL,NULL,NULL,NULL,'2026-08-16 15:22:32','2026-08-16 15:25:46');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_images`
--

DROP TABLE IF EXISTS `product_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_images` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` tinyint unsigned NOT NULL DEFAULT '1',
  `is_primary` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_images_product_id_position_index` (`product_id`,`position`),
  CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=251 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_images`
--

LOCK TABLES `product_images` WRITE;
/*!40000 ALTER TABLE `product_images` DISABLE KEYS */;
INSERT INTO `product_images` VALUES (1,1,'products/pindd/1761674973291--1937294720.jpg',1,1,'2026-08-16 14:37:26','2026-08-16 14:37:26'),(2,1,'products/pindd/1761675385762-1718998399.jpg',2,0,'2026-08-16 14:37:26','2026-08-16 14:37:26'),(3,1,'products/pindd/1761675396408-1233538176.jpg',3,0,'2026-08-16 14:37:26','2026-08-16 14:37:26'),(4,1,'products/pindd/1761675400274-2121784472.jpg',4,0,'2026-08-16 14:37:26','2026-08-16 14:37:26'),(5,1,'products/pindd/1761675402561-194787087.jpg',5,0,'2026-08-16 14:37:26','2026-08-16 14:37:26'),(6,2,'products/pindd/1761675495530-1297055262.jpg',1,1,'2026-08-16 14:37:26','2026-08-16 14:37:26'),(7,2,'products/pindd/1761675498460--476427690.jpg',2,0,'2026-08-16 14:37:26','2026-08-16 14:37:26'),(8,2,'products/pindd/1761675500701--848288306.jpg',3,0,'2026-08-16 14:37:26','2026-08-16 14:37:26'),(9,2,'products/pindd/1761675503321-231791038.jpg',4,0,'2026-08-16 14:37:26','2026-08-16 14:37:26'),(10,2,'products/pindd/1761675505783--878004536.jpg',5,0,'2026-08-16 14:37:26','2026-08-16 14:37:26'),(11,3,'products/pindd/1761675796199-1709741343.jpg',1,1,'2026-08-16 14:37:26','2026-08-16 14:37:26'),(12,3,'products/pindd/1761675872138-697897029.jpg',2,0,'2026-08-16 14:37:26','2026-08-16 14:37:26'),(13,3,'products/pindd/1761675875466-1256031668.jpg',3,0,'2026-08-16 14:37:26','2026-08-16 14:37:26'),(14,3,'products/pindd/1761676392781-1960218351.jpg',4,0,'2026-08-16 14:37:26','2026-08-16 14:37:26'),(15,3,'products/pindd/1761676398737--1749523544.jpg',5,0,'2026-08-16 14:37:26','2026-08-16 14:37:26'),(16,4,'products/pindd/1761676602824-2020837771.jpg',1,1,'2026-08-16 14:37:26','2026-08-16 14:37:26'),(17,4,'products/pindd/1761676615003--39540856.jpg',2,0,'2026-08-16 14:37:26','2026-08-16 14:37:26'),(18,4,'products/pindd/1761677464985--326239923.jpg',3,0,'2026-08-16 14:37:26','2026-08-16 14:37:26'),(19,4,'products/pindd/1761677493082-1623087263.jpg',4,0,'2026-08-16 14:37:26','2026-08-16 14:37:26'),(20,4,'products/pindd/1761677495965-2099910753.jpg',5,0,'2026-08-16 14:37:26','2026-08-16 14:37:26'),(21,5,'products/pindd/1761677524365-1292391622.jpg',1,1,'2026-08-16 14:37:26','2026-08-16 14:37:26'),(22,5,'products/pindd/1761677542401--193982635.jpg',2,0,'2026-08-16 14:37:26','2026-08-16 14:37:26'),(23,5,'products/pindd/1761677630187-728564864.jpg',3,0,'2026-08-16 14:37:26','2026-08-16 14:37:26'),(24,5,'products/pindd/1761677632057-1941320247.jpg',4,0,'2026-08-16 14:37:26','2026-08-16 14:37:26'),(25,5,'products/pindd/1761677722746-2138784314.jpg',5,0,'2026-08-16 14:37:26','2026-08-16 14:37:26'),(26,6,'products/pindd/1761678520083-2124319035.jpg',1,1,'2026-08-16 14:37:26','2026-08-16 14:37:26'),(27,6,'products/pindd/1761678523149-1079696122.jpg',2,0,'2026-08-16 14:37:26','2026-08-16 14:37:26'),(28,6,'products/pindd/1761678528122-1959013034.jpg',3,0,'2026-08-16 14:37:26','2026-08-16 14:37:26'),(29,6,'products/pindd/1761678545433--1262219067.jpg',4,0,'2026-08-16 14:37:26','2026-08-16 14:37:26'),(30,6,'products/pindd/1761678652482--1486318964.jpg',5,0,'2026-08-16 14:37:26','2026-08-16 14:37:26'),(31,7,'products/pindd/1761678655730--581113671.jpg',1,1,'2026-08-16 14:37:26','2026-08-16 14:37:26'),(32,7,'products/pindd/1761678666678--975238001.jpg',2,0,'2026-08-16 14:37:26','2026-08-16 14:37:26'),(33,7,'products/pindd/1761678668578-1162045124.jpg',3,0,'2026-08-16 14:37:26','2026-08-16 14:37:26'),(34,7,'products/pindd/1761678686818-1228662848.jpg',4,0,'2026-08-16 14:37:26','2026-08-16 14:37:26'),(35,7,'products/pindd/1761678876027-527420947.jpg',5,0,'2026-08-16 14:37:26','2026-08-16 14:37:26'),(36,8,'products/pindd/1761678895597--746069552.jpg',1,1,'2026-08-16 14:37:26','2026-08-16 14:37:26'),(37,8,'products/pindd/1761678901220-748797658.jpg',2,0,'2026-08-16 14:37:26','2026-08-16 14:37:26'),(38,8,'products/pindd/1761679124115-1655869658.jpg',3,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(39,8,'products/pindd/1761679127047--1029125324.jpg',4,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(40,8,'products/pindd/1761679133050-642667443.jpg',5,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(41,9,'products/pindd/1761679137131--1551930548.jpg',1,1,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(42,9,'products/pindd/1761679140962--847708597.jpg',2,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(43,9,'products/pindd/1761760113875--238639492.jpg',3,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(44,9,'products/pindd/1761760117988-953703074.jpg',4,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(45,9,'products/pindd/1761760123622--453464099.jpg',5,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(46,10,'products/pindd/1761760238339-565368763.jpg',1,1,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(47,10,'products/pindd/1761761268652--185970522.jpg',2,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(48,10,'products/pindd/1761761298310--773766576.jpg',3,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(49,10,'products/pindd/1761761424345--680011780.jpg',4,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(50,10,'products/pindd/1761761556617--706109770.jpg',5,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(51,11,'products/pindd/1761762646857--822385524.jpg',1,1,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(52,11,'products/pindd/1761762960141-1565801094.jpg',2,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(53,11,'products/pindd/1761763050411-618798205.jpg',3,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(54,11,'products/pindd/1761763481913--326052454.jpg',4,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(55,11,'products/pindd/1761789970891-1313993683.jpg',5,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(56,12,'products/pindd/1762942429980-382103555.jpg',1,1,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(57,12,'products/pindd/1762942436615-93130907.jpg',2,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(58,12,'products/pindd/1762976101413-179380578.jpg',3,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(59,12,'products/pindd/1762976107686--1649009157.jpg',4,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(60,12,'products/pindd/1762976111547--432249337.jpg',5,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(61,13,'products/pindd/1763173798840-654262573.jpg',1,1,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(62,13,'products/pindd/1763173801138-1249161285.jpg',2,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(63,13,'products/pindd/1763173813005-1482933769.jpg',3,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(64,13,'products/pindd/1763173940617--207486598.jpg',4,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(65,13,'products/pindd/1763173942632-39284232.jpg',5,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(66,14,'products/pindd/1763291259960-1157456782.jpg',1,1,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(67,14,'products/pindd/1766839602763--1250798692.jpg',2,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(68,14,'products/pindd/1766839610374--140548590.jpg',3,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(69,14,'products/pindd/1766839733006--73356220.jpg',4,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(70,14,'products/pindd/1766840059093--49968274.jpg',5,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(71,15,'products/pindd/1766840062602--1229214506.jpg',1,1,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(72,15,'products/pindd/1766840158449-1197316951.jpg',2,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(73,15,'products/pindd/1766840447667--1290143348.jpg',3,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(74,15,'products/pindd/1766840489348-1314319711.jpg',4,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(75,15,'products/pindd/1766840491459--1113159133.jpg',5,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(76,16,'products/pindd/1766840565738--579925880.jpg',1,1,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(77,16,'products/pindd/1766840583148--1330671419.jpg',2,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(78,16,'products/pindd/1766840598044--1781063055.jpg',3,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(79,16,'products/pindd/1766840601227-2129833945.jpg',4,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(80,16,'products/pindd/1766840619692-681161132.jpg',5,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(81,17,'products/pindd/1766840633586--1080835811.jpg',1,1,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(82,17,'products/pindd/1766840636121--732877533.jpg',2,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(83,17,'products/pindd/1766840639528--724147491.jpg',3,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(84,17,'products/pindd/1766840754924-2037951992.jpg',4,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(85,17,'products/pindd/1766840781090-1252338797.jpg',5,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(86,18,'products/pindd/1766840800426-43537248.jpg',1,1,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(87,18,'products/pindd/1766840812160--266300313.jpg',2,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(88,18,'products/pindd/1766840831572--4697118.jpg',3,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(89,18,'products/pindd/1766840836917--624059950.jpg',4,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(90,18,'products/pindd/1766840867943--437086634.jpg',5,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(91,19,'products/pindd/1766840868626-290238093.jpg',1,1,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(92,19,'products/pindd/1766840869060-1081753851.jpg',2,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(93,19,'products/pindd/1766840869791-407256010.jpg',3,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(94,19,'products/pindd/1766840870509-600630183.jpg',4,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(95,19,'products/pindd/1766840870543-922672487.jpg',5,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(96,20,'products/pindd/1766840871219-449780065.jpg',1,1,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(97,20,'products/pindd/1766840871828--1206672860.jpg',2,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(98,20,'products/pindd/1766840872515--1163655172.jpg',3,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(99,20,'products/pindd/1766840873256--1763972233.jpg',4,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(100,20,'products/pindd/1766840873872--957771871.jpg',5,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(101,21,'products/pindd/1766840873886--1720721786.jpg',1,1,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(102,21,'products/pindd/1766840873901--1775358645.jpg',2,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(103,21,'products/pindd/1766840873918-780922394.jpg',3,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(104,21,'products/pindd/1766840873940--1375160373.jpg',4,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(105,21,'products/pindd/1766840873955-555493898.jpg',5,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(106,22,'products/pindd/1766840873970-1301536979.jpg',1,1,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(107,22,'products/pindd/1766840873989-992983642.jpg',2,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(108,22,'products/pindd/1766840874504-1789184829.jpg',3,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(109,22,'products/pindd/1766840874770--749519354.jpg',4,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(110,22,'products/pindd/1766840875708-125360342.jpg',5,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(111,23,'products/pindd/1766840875723-720575299.jpg',1,1,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(112,23,'products/pindd/1766840875743--311150354.jpg',2,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(113,23,'products/pindd/1766840875767-1369915426.jpg',3,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(114,23,'products/pindd/1766840876144-1555222181.jpg',4,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(115,23,'products/pindd/1766840876538-206305212.jpg',5,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(116,24,'products/pindd/1766840877570-2056757749.jpg',1,1,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(117,24,'products/pindd/1766840878339-824866884.jpg',2,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(118,24,'products/pindd/1766840897168-2112861937.jpg',3,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(119,24,'products/pindd/1766840899491--1523079837.jpg',4,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(120,24,'products/pindd/1766840960248--1868291907.jpg',5,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(121,25,'products/pindd/1766840984462--1925496265.jpg',1,1,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(122,25,'products/pindd/1766840986271--1821237924.jpg',2,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(123,25,'products/pindd/1766841013951-1218764169.jpg',3,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(124,25,'products/pindd/1766841017096--253736964.jpg',4,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(125,25,'products/pindd/1766841031384--20307504.jpg',5,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(126,26,'products/pindd/1766841075593-1475020556.jpg',1,1,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(127,26,'products/pindd/1766841094635-1918221831.jpg',2,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(128,26,'products/pindd/1766841103099-762673689.jpg',3,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(129,26,'products/pindd/1766841122136--1705524923.jpg',4,0,'2026-08-16 14:37:27','2026-08-16 14:37:27'),(130,26,'products/pindd/1766841123966-1809188161.jpg',5,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(131,27,'products/pindd/1766841141140-1661040061.jpg',1,1,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(132,27,'products/pindd/1766841143589-1821965201.jpg',2,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(133,27,'products/pindd/1766841158049--1743159113.jpg',3,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(134,27,'products/pindd/1766841166590-1389171901.jpg',4,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(135,27,'products/pindd/1766841168871--846311614.jpg',5,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(136,28,'products/pindd/1766841183813-886482803.jpg',1,1,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(137,28,'products/pindd/1766841185442-261043655.jpg',2,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(138,28,'products/pindd/1766841186381-963398489.jpg',3,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(139,28,'products/pindd/1766841189010--196745367.jpg',4,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(140,28,'products/pindd/1766841189687-1292124357.jpg',5,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(141,29,'products/pindd/1766841189714-1380126208.jpg',1,1,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(142,29,'products/pindd/1766841189745-1066618452.jpg',2,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(143,29,'products/pindd/1766841291939-59128701.jpg',3,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(144,29,'products/pindd/1766841294310-1988027316.jpg',4,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(145,29,'products/pindd/1766841308105--1301313919.jpg',5,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(146,30,'products/pindd/1766841312112--298312168.jpg',1,1,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(147,30,'products/pindd/1766841325648-644288418.jpg',2,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(148,30,'products/pindd/1766841334755--458611701.jpg',3,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(149,30,'products/pindd/1766841354733-1182435457.jpg',4,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(150,30,'products/pindd/1766841356672-294086836.jpg',5,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(151,31,'products/pindd/1766841374553-1013186752.jpg',1,1,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(152,31,'products/pindd/1766841377614--1894220595.jpg',2,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(153,31,'products/pindd/1766841399023-1116038576.jpg',3,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(154,31,'products/pindd/1766841410848-972082633.jpg',4,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(155,31,'products/pindd/1766841426947-271068374.jpg',5,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(156,32,'products/pindd/1766841466587--887260291.jpg',1,1,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(157,32,'products/pindd/1766841481039--1562999186.jpg',2,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(158,32,'products/pindd/1766841489893-673402933.jpg',3,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(159,32,'products/pindd/1766841511981--757016138.jpg',4,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(160,32,'products/pindd/1766841514627--1116919487.jpg',5,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(161,33,'products/pindd/1766841644593-1913383125.jpg',1,1,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(162,33,'products/pindd/1766841665190--120308356.jpg',2,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(163,33,'products/pindd/1766841677925-195966252.jpg',3,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(164,33,'products/pindd/1766841815741-379185041.jpg',4,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(165,33,'products/pindd/1766841825029--354001887.jpg',5,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(166,34,'products/pindd/1766888797307--1085607164.jpg',1,1,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(167,34,'products/pindd/1766888799639-1913879266.jpg',2,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(168,34,'products/pindd/1766888837000--102838417.jpg',3,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(169,34,'products/pindd/1766888850032-103240795.jpg',4,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(170,34,'products/pindd/1766888853933--210237058.jpg',5,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(171,35,'products/pindd/1766888899836-1046453310.jpg',1,1,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(172,35,'products/pindd/1766888902147-210861670.jpg',2,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(173,35,'products/pindd/1766888925489-505239822.jpg',3,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(174,35,'products/pindd/1766888969130-406624257.jpg',4,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(175,35,'products/pindd/1766888973186-80948201.jpg',5,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(176,36,'products/pindd/1766889002685-1608330923.jpg',1,1,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(177,36,'products/pindd/1766889053221--1134202857.jpg',2,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(178,36,'products/pindd/1766906000083--1464822759.jpg',3,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(179,36,'products/pindd/1766906179559--2085284143.jpg',4,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(180,36,'products/pindd/1766906186365-1656667877.jpg',5,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(181,37,'products/pindd/1766906189549--432542617.jpg',1,1,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(182,37,'products/pindd/1766906210797--1529174330.jpg',2,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(183,37,'products/pindd/1766906213240-198843793.jpg',3,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(184,37,'products/pindd/1766906390025-988113107.jpg',4,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(185,37,'products/pindd/1766906393006--180596844.jpg',5,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(186,38,'products/pindd/1766906395324-1549393022.jpg',1,1,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(187,38,'products/pindd/1766906427314--1124998569.jpg',2,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(188,38,'products/pindd/1766906429458-1309781850.jpg',3,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(189,38,'products/pindd/1766906443464--647549169.jpg',4,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(190,38,'products/pindd/1766906445377--1278465692.jpg',5,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(191,39,'products/pindd/1766906456596-2116800018.jpg',1,1,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(192,39,'products/pindd/1766906459112--868864974.jpg',2,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(193,39,'products/pindd/1766906487790--1389373337.jpg',3,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(194,39,'products/pindd/1766976482971--1304993324.jpg',4,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(195,39,'products/pindd/1766976488837-726254022.jpg',5,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(196,40,'products/pindd/1766976491803-466519726.jpg',1,1,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(197,40,'products/pindd/1766976850981-1341705447.jpg',2,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(198,40,'products/pindd/1766976864465-168934582.jpg',3,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(199,40,'products/pindd/1766976903738-1476467102.jpg',4,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(200,40,'products/pindd/1766976946200-42319820.jpg',5,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(201,41,'products/pindd/1766976950861--1504483337.jpg',1,1,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(202,41,'products/pindd/1766976972867-1562973177.jpg',2,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(203,41,'products/pindd/1766976977737--79277248.jpg',3,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(204,41,'products/pindd/1766976980098-1037845837.jpg',4,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(205,41,'products/pindd/1766976982654-1153793941.jpg',5,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(206,42,'products/pindd/1766977030466-379707779.jpg',1,1,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(207,42,'products/pindd/1766977034873-987599162.jpg',2,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(208,42,'products/pindd/1766977041066-1835187244.jpg',3,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(209,42,'products/pindd/1766977068566--1258785430.jpg',4,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(210,42,'products/pindd/1766977075608--1499580153.jpg',5,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(211,43,'products/pindd/1766977192172--551336814.jpg',1,1,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(212,43,'products/pindd/1766977195983-344847414.jpg',2,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(213,43,'products/pindd/1766977312304-33402053.jpg',3,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(214,43,'products/pindd/1766977330481--2092754182.jpg',4,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(215,43,'products/pindd/1766977831641--1641473190.jpg',5,0,'2026-08-16 14:37:28','2026-08-16 14:37:28'),(216,44,'products/pindd/1766977833993--1173183414.jpg',1,1,'2026-08-16 14:37:29','2026-08-16 14:37:29'),(217,44,'products/pindd/1766977864366-1521162339.jpg',2,0,'2026-08-16 14:37:29','2026-08-16 14:37:29'),(218,44,'products/pindd/1767017710199-1919824764.jpg',3,0,'2026-08-16 14:37:29','2026-08-16 14:37:29'),(219,44,'products/pindd/1767023835650-780802770.jpg',4,0,'2026-08-16 14:37:29','2026-08-16 14:37:29'),(220,44,'products/pindd/1767097454823-1713028598.jpg',5,0,'2026-08-16 14:37:29','2026-08-16 14:37:29'),(221,45,'products/pindd/1767097457529-650356156.jpg',1,1,'2026-08-16 14:37:29','2026-08-16 14:37:29'),(222,45,'products/pindd/1767097459406-1646236339.jpg',2,0,'2026-08-16 14:37:29','2026-08-16 14:37:29'),(223,45,'products/pindd/1767097466790-1942116356.jpg',3,0,'2026-08-16 14:37:29','2026-08-16 14:37:29'),(224,45,'products/pindd/1767097594086-1113521958.jpg',4,0,'2026-08-16 14:37:29','2026-08-16 14:37:29'),(225,45,'products/pindd/1767097601124-1034569703.jpg',5,0,'2026-08-16 14:37:29','2026-08-16 14:37:29'),(226,46,'products/pindd/1767097616763-425046186.jpg',1,1,'2026-08-16 14:37:29','2026-08-16 14:37:29'),(227,46,'products/pindd/1767097750215-1644979643.jpg',2,0,'2026-08-16 14:37:29','2026-08-16 14:37:29'),(228,46,'products/pindd/1767097753537--1402160835.jpg',3,0,'2026-08-16 14:37:29','2026-08-16 14:37:29'),(229,46,'products/pindd/1767097755992-1068820836.jpg',4,0,'2026-08-16 14:37:29','2026-08-16 14:37:29'),(230,46,'products/pindd/1767097758013-1957310611.jpg',5,0,'2026-08-16 14:37:29','2026-08-16 14:37:29'),(231,47,'products/pindd/1767097762321--904720242.jpg',1,1,'2026-08-16 14:37:29','2026-08-16 14:37:29'),(232,47,'products/pindd/1767097769926--1050316325.jpg',2,0,'2026-08-16 14:37:29','2026-08-16 14:37:29'),(233,47,'products/pindd/1767097772615--359517910.jpg',3,0,'2026-08-16 14:37:29','2026-08-16 14:37:29'),(234,47,'products/pindd/1767097797094--1482164389.jpg',4,0,'2026-08-16 14:37:29','2026-08-16 14:37:29'),(235,47,'products/pindd/1767097879978--2021971018.jpg',5,0,'2026-08-16 14:37:29','2026-08-16 14:37:29'),(236,48,'products/pindd/1767097913203--460718775.jpg',1,1,'2026-08-16 14:37:29','2026-08-16 14:37:29'),(237,48,'products/pindd/1767097918081-107682308.jpg',2,0,'2026-08-16 14:37:29','2026-08-16 14:37:29'),(238,48,'products/pindd/1767097946729-1559298156.jpg',3,0,'2026-08-16 14:37:29','2026-08-16 14:37:29'),(239,48,'products/pindd/1767097949360-1971981613.jpg',4,0,'2026-08-16 14:37:29','2026-08-16 14:37:29'),(240,48,'products/pindd/1767097953282--1893212750.jpg',5,0,'2026-08-16 14:37:29','2026-08-16 14:37:29'),(241,49,'products/pindd/1767097961368-856553683.jpg',1,1,'2026-08-16 14:37:29','2026-08-16 14:37:29'),(242,49,'products/pindd/1767097963238--588975555.jpg',2,0,'2026-08-16 14:37:29','2026-08-16 14:37:29'),(243,49,'products/pindd/1767098129923--918732387.jpg',3,0,'2026-08-16 14:37:29','2026-08-16 14:37:29'),(244,49,'products/pindd/1767098132930-1641407994.jpg',4,0,'2026-08-16 14:37:29','2026-08-16 14:37:29'),(245,49,'products/pindd/1767098404932--288208783.jpg',5,0,'2026-08-16 14:37:29','2026-08-16 14:37:29'),(246,50,'products/pindd/1767098498414--894790727.jpg',1,1,'2026-08-16 14:37:29','2026-08-16 14:37:29'),(247,50,'products/pindd/1767098511342-14358215.jpg',2,0,'2026-08-16 14:37:29','2026-08-16 14:37:29'),(248,50,'products/pindd/1767098521767-1132920922.jpg',3,0,'2026-08-16 14:37:29','2026-08-16 14:37:29'),(249,50,'products/pindd/1767098524283--905444311.jpg',4,0,'2026-08-16 14:37:29','2026-08-16 14:37:29'),(250,50,'products/pindd/1767098549033--279746328.jpg',5,0,'2026-08-16 14:37:29','2026-08-16 14:37:29');
/*!40000 ALTER TABLE `product_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` bigint unsigned NOT NULL,
  `category_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int unsigned NOT NULL,
  `old_price` int unsigned DEFAULT NULL,
  `stock` int unsigned NOT NULL DEFAULT '0',
  `stock_reserved` int unsigned NOT NULL DEFAULT '0',
  `min_quantity` smallint unsigned NOT NULL DEFAULT '1',
  `shipping_included` tinyint(1) NOT NULL DEFAULT '0',
  `shipping_threshold_qty` smallint unsigned DEFAULT NULL,
  `specifications` json DEFAULT NULL,
  `status` enum('hidden','visible','sold_out','banned') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'hidden',
  `views_count` int unsigned NOT NULL DEFAULT '0',
  `orders_count` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `products_status_city_index` (`status`,`city`),
  KEY `products_shop_id_status_index` (`shop_id`,`status`),
  KEY `products_category_id_status_index` (`category_id`,`status`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `products_shop_id_foreign` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,1,1,'Smartphone Samsung Galaxy A54 128Go','Produit High-Tech garanti d\'excellente qualité. Smartphone Samsung Galaxy A54 128Go est idéal pour une utilisation quotidienne professionnelle ou personnelle. Livré rapidement dans tout le pays avec emballage sécurisé.','Douala',19,44,13,0,1,1,2,'\"{\\\"Marque\\\":\\\"G\\\\u00e9n\\\\u00e9rique\\\\/Original\\\",\\\"Garantie\\\":\\\"6 Mois\\\",\\\"\\\\u00c9tat\\\":\\\"Neuf\\\"}\"','visible',115,2,'2026-08-16 14:37:25','2026-08-16 14:37:25',NULL),(2,1,1,'iPhone 13 Pro Max 256Go Reconditionné','Produit High-Tech garanti d\'excellente qualité. iPhone 13 Pro Max 256Go Reconditionné est idéal pour une utilisation quotidienne professionnelle ou personnelle. Livré rapidement dans tout le pays avec emballage sécurisé.','Douala',13,34,7,0,1,1,4,'\"{\\\"Marque\\\":\\\"G\\\\u00e9n\\\\u00e9rique\\\\/Original\\\",\\\"Garantie\\\":\\\"6 Mois\\\",\\\"\\\\u00c9tat\\\":\\\"Neuf\\\"}\"','visible',497,28,'2026-08-16 14:37:25','2026-08-16 14:37:25',NULL),(3,1,1,'Écouteurs Sans Fil Bluetooth Pro','Produit High-Tech garanti d\'excellente qualité. Écouteurs Sans Fil Bluetooth Pro est idéal pour une utilisation quotidienne professionnelle ou personnelle. Livré rapidement dans tout le pays avec emballage sécurisé.','Douala',14,NULL,8,0,1,0,4,'\"{\\\"Marque\\\":\\\"G\\\\u00e9n\\\\u00e9rique\\\\/Original\\\",\\\"Garantie\\\":\\\"6 Mois\\\",\\\"\\\\u00c9tat\\\":\\\"Neuf\\\"}\"','visible',186,4,'2026-08-16 14:37:25','2026-08-16 14:37:25',NULL),(4,1,1,'Casque Audio Bluetooth Réduction de Bruit','Produit High-Tech garanti d\'excellente qualité. Casque Audio Bluetooth Réduction de Bruit est idéal pour une utilisation quotidienne professionnelle ou personnelle. Livré rapidement dans tout le pays avec emballage sécurisé.','Douala',19,NULL,50,1,1,1,4,'\"{\\\"Marque\\\":\\\"G\\\\u00e9n\\\\u00e9rique\\\\/Original\\\",\\\"Garantie\\\":\\\"6 Mois\\\",\\\"\\\\u00c9tat\\\":\\\"Neuf\\\"}\"','visible',188,30,'2026-08-16 14:37:26','2026-08-16 14:39:50',NULL),(5,1,1,'Montre Connectée Sport Waterproof','Produit High-Tech garanti d\'excellente qualité. Montre Connectée Sport Waterproof est idéal pour une utilisation quotidienne professionnelle ou personnelle. Livré rapidement dans tout le pays avec emballage sécurisé.','Douala',22,29,39,0,1,1,4,'\"{\\\"Marque\\\":\\\"G\\\\u00e9n\\\\u00e9rique\\\\/Original\\\",\\\"Garantie\\\":\\\"6 Mois\\\",\\\"\\\\u00c9tat\\\":\\\"Neuf\\\"}\"','visible',105,13,'2026-08-16 14:37:26','2026-08-16 14:37:26',NULL),(6,1,1,'Ordinateur Portatif HP Core i5 16GB RAM','Produit High-Tech garanti d\'excellente qualité. Ordinateur Portatif HP Core i5 16GB RAM est idéal pour une utilisation quotidienne professionnelle ou personnelle. Livré rapidement dans tout le pays avec emballage sécurisé.','Douala',11,34,28,0,1,1,4,'\"{\\\"Marque\\\":\\\"G\\\\u00e9n\\\\u00e9rique\\\\/Original\\\",\\\"Garantie\\\":\\\"6 Mois\\\",\\\"\\\\u00c9tat\\\":\\\"Neuf\\\"}\"','visible',395,28,'2026-08-16 14:37:26','2026-08-16 14:37:26',NULL),(7,1,1,'MacBook Air M1 256GB SSD','Produit High-Tech garanti d\'excellente qualité. MacBook Air M1 256GB SSD est idéal pour une utilisation quotidienne professionnelle ou personnelle. Livré rapidement dans tout le pays avec emballage sécurisé.','Douala',30,36,10,0,1,0,5,'\"{\\\"Marque\\\":\\\"G\\\\u00e9n\\\\u00e9rique\\\\/Original\\\",\\\"Garantie\\\":\\\"6 Mois\\\",\\\"\\\\u00c9tat\\\":\\\"Neuf\\\"}\"','visible',397,25,'2026-08-16 14:37:26','2026-08-16 14:37:26',NULL),(8,1,1,'Tablette Tactile Android 10 pouces','Produit High-Tech garanti d\'excellente qualité. Tablette Tactile Android 10 pouces est idéal pour une utilisation quotidienne professionnelle ou personnelle. Livré rapidement dans tout le pays avec emballage sécurisé.','Douala',14,23,25,0,1,1,4,'\"{\\\"Marque\\\":\\\"G\\\\u00e9n\\\\u00e9rique\\\\/Original\\\",\\\"Garantie\\\":\\\"6 Mois\\\",\\\"\\\\u00c9tat\\\":\\\"Neuf\\\"}\"','visible',40,5,'2026-08-16 14:37:26','2026-08-16 14:37:26',NULL),(9,1,1,'Clé USB 128Go USB 3.0 Haute Vitesse','Produit High-Tech garanti d\'excellente qualité. Clé USB 128Go USB 3.0 Haute Vitesse est idéal pour une utilisation quotidienne professionnelle ou personnelle. Livré rapidement dans tout le pays avec emballage sécurisé.','Douala',25,NULL,9,1,1,0,3,'\"{\\\"Marque\\\":\\\"G\\\\u00e9n\\\\u00e9rique\\\\/Original\\\",\\\"Garantie\\\":\\\"6 Mois\\\",\\\"\\\\u00c9tat\\\":\\\"Neuf\\\"}\"','visible',24,2,'2026-08-16 14:37:26','2026-08-16 15:21:42',NULL),(10,1,1,'Disque Dur Externe 1To Toshiba','Produit High-Tech garanti d\'excellente qualité. Disque Dur Externe 1To Toshiba est idéal pour une utilisation quotidienne professionnelle ou personnelle. Livré rapidement dans tout le pays avec emballage sécurisé.','Douala',19,40,29,0,1,1,4,'\"{\\\"Marque\\\":\\\"G\\\\u00e9n\\\\u00e9rique\\\\/Original\\\",\\\"Garantie\\\":\\\"6 Mois\\\",\\\"\\\\u00c9tat\\\":\\\"Neuf\\\"}\"','visible',216,21,'2026-08-16 14:37:26','2026-08-16 14:37:26',NULL),(11,1,1,'PowerBank 20000mAh Charge Rapide','Produit High-Tech garanti d\'excellente qualité. PowerBank 20000mAh Charge Rapide est idéal pour une utilisation quotidienne professionnelle ou personnelle. Livré rapidement dans tout le pays avec emballage sécurisé.','Douala',21,NULL,14,0,1,1,3,'\"{\\\"Marque\\\":\\\"G\\\\u00e9n\\\\u00e9rique\\\\/Original\\\",\\\"Garantie\\\":\\\"6 Mois\\\",\\\"\\\\u00c9tat\\\":\\\"Neuf\\\"}\"','visible',377,12,'2026-08-16 14:37:26','2026-08-16 14:37:26',NULL),(12,1,1,'Chargeur Rapide Type-C 65W','Produit High-Tech garanti d\'excellente qualité. Chargeur Rapide Type-C 65W est idéal pour une utilisation quotidienne professionnelle ou personnelle. Livré rapidement dans tout le pays avec emballage sécurisé.','Douala',30,NULL,12,0,1,1,5,'\"{\\\"Marque\\\":\\\"G\\\\u00e9n\\\\u00e9rique\\\\/Original\\\",\\\"Garantie\\\":\\\"6 Mois\\\",\\\"\\\\u00c9tat\\\":\\\"Neuf\\\"}\"','visible',84,10,'2026-08-16 14:37:26','2026-08-16 14:37:26',NULL),(13,1,1,'Souris Sans Fil Ergonomique','Produit High-Tech garanti d\'excellente qualité. Souris Sans Fil Ergonomique est idéal pour une utilisation quotidienne professionnelle ou personnelle. Livré rapidement dans tout le pays avec emballage sécurisé.','Douala',22,NULL,9,1,1,0,4,'\"{\\\"Marque\\\":\\\"G\\\\u00e9n\\\\u00e9rique\\\\/Original\\\",\\\"Garantie\\\":\\\"6 Mois\\\",\\\"\\\\u00c9tat\\\":\\\"Neuf\\\"}\"','visible',61,17,'2026-08-16 14:37:26','2026-08-16 15:22:32',NULL),(14,1,1,'Clavier Mécanique Gamer RGB','Produit High-Tech garanti d\'excellente qualité. Clavier Mécanique Gamer RGB est idéal pour une utilisation quotidienne professionnelle ou personnelle. Livré rapidement dans tout le pays avec emballage sécurisé.','Douala',29,NULL,24,0,1,1,2,'\"{\\\"Marque\\\":\\\"G\\\\u00e9n\\\\u00e9rique\\\\/Original\\\",\\\"Garantie\\\":\\\"6 Mois\\\",\\\"\\\\u00c9tat\\\":\\\"Neuf\\\"}\"','visible',313,2,'2026-08-16 14:37:26','2026-08-16 14:37:26',NULL),(15,1,1,'Écran PC 24 pouces Full HD','Produit High-Tech garanti d\'excellente qualité. Écran PC 24 pouces Full HD est idéal pour une utilisation quotidienne professionnelle ou personnelle. Livré rapidement dans tout le pays avec emballage sécurisé.','Douala',26,NULL,33,0,1,1,2,'\"{\\\"Marque\\\":\\\"G\\\\u00e9n\\\\u00e9rique\\\\/Original\\\",\\\"Garantie\\\":\\\"6 Mois\\\",\\\"\\\\u00c9tat\\\":\\\"Neuf\\\"}\"','visible',438,0,'2026-08-16 14:37:26','2026-08-16 14:37:26',NULL),(16,1,1,'Enceinte Bluetooth Portable Waterproof','Produit High-Tech garanti d\'excellente qualité. Enceinte Bluetooth Portable Waterproof est idéal pour une utilisation quotidienne professionnelle ou personnelle. Livré rapidement dans tout le pays avec emballage sécurisé.','Douala',16,28,38,0,1,0,5,'\"{\\\"Marque\\\":\\\"G\\\\u00e9n\\\\u00e9rique\\\\/Original\\\",\\\"Garantie\\\":\\\"6 Mois\\\",\\\"\\\\u00c9tat\\\":\\\"Neuf\\\"}\"','visible',212,3,'2026-08-16 14:37:26','2026-08-16 14:37:26',NULL),(17,1,1,'Caméra de Surveillance WiFi 1080p','Produit High-Tech garanti d\'excellente qualité. Caméra de Surveillance WiFi 1080p est idéal pour une utilisation quotidienne professionnelle ou personnelle. Livré rapidement dans tout le pays avec emballage sécurisé.','Douala',10,33,47,0,1,1,4,'\"{\\\"Marque\\\":\\\"G\\\\u00e9n\\\\u00e9rique\\\\/Original\\\",\\\"Garantie\\\":\\\"6 Mois\\\",\\\"\\\\u00c9tat\\\":\\\"Neuf\\\"}\"','visible',134,21,'2026-08-16 14:37:26','2026-08-16 14:37:26',NULL),(18,1,1,'Routeur WiFi 4G Carte SIM','Produit High-Tech garanti d\'excellente qualité. Routeur WiFi 4G Carte SIM est idéal pour une utilisation quotidienne professionnelle ou personnelle. Livré rapidement dans tout le pays avec emballage sécurisé.','Douala',17,NULL,36,0,1,0,5,'\"{\\\"Marque\\\":\\\"G\\\\u00e9n\\\\u00e9rique\\\\/Original\\\",\\\"Garantie\\\":\\\"6 Mois\\\",\\\"\\\\u00c9tat\\\":\\\"Neuf\\\"}\"','visible',203,14,'2026-08-16 14:37:26','2026-08-16 14:37:26',NULL),(19,1,1,'Support Téléphone Portable pour Voiture','Produit High-Tech garanti d\'excellente qualité. Support Téléphone Portable pour Voiture est idéal pour une utilisation quotidienne professionnelle ou personnelle. Livré rapidement dans tout le pays avec emballage sécurisé.','Douala',16,NULL,33,0,1,1,5,'\"{\\\"Marque\\\":\\\"G\\\\u00e9n\\\\u00e9rique\\\\/Original\\\",\\\"Garantie\\\":\\\"6 Mois\\\",\\\"\\\\u00c9tat\\\":\\\"Neuf\\\"}\"','visible',488,20,'2026-08-16 14:37:26','2026-08-16 14:37:26',NULL),(20,1,1,'Câble de Charge Magnétique 3-en-1','Produit High-Tech garanti d\'excellente qualité. Câble de Charge Magnétique 3-en-1 est idéal pour une utilisation quotidienne professionnelle ou personnelle. Livré rapidement dans tout le pays avec emballage sécurisé.','Douala',30,43,40,0,1,0,5,'\"{\\\"Marque\\\":\\\"G\\\\u00e9n\\\\u00e9rique\\\\/Original\\\",\\\"Garantie\\\":\\\"6 Mois\\\",\\\"\\\\u00c9tat\\\":\\\"Neuf\\\"}\"','visible',470,17,'2026-08-16 14:37:26','2026-08-16 14:37:26',NULL),(21,1,1,'Trépied Ring Light avec Télécommande','Produit High-Tech garanti d\'excellente qualité. Trépied Ring Light avec Télécommande est idéal pour une utilisation quotidienne professionnelle ou personnelle. Livré rapidement dans tout le pays avec emballage sécurisé.','Douala',16,27,38,0,1,0,3,'\"{\\\"Marque\\\":\\\"G\\\\u00e9n\\\\u00e9rique\\\\/Original\\\",\\\"Garantie\\\":\\\"6 Mois\\\",\\\"\\\\u00c9tat\\\":\\\"Neuf\\\"}\"','visible',160,14,'2026-08-16 14:37:26','2026-08-16 14:37:26',NULL),(22,1,1,'Carte Mémoire Micro SD 64Go Class 10','Produit High-Tech garanti d\'excellente qualité. Carte Mémoire Micro SD 64Go Class 10 est idéal pour une utilisation quotidienne professionnelle ou personnelle. Livré rapidement dans tout le pays avec emballage sécurisé.','Douala',23,NULL,50,0,1,0,5,'\"{\\\"Marque\\\":\\\"G\\\\u00e9n\\\\u00e9rique\\\\/Original\\\",\\\"Garantie\\\":\\\"6 Mois\\\",\\\"\\\\u00c9tat\\\":\\\"Neuf\\\"}\"','visible',386,9,'2026-08-16 14:37:26','2026-08-16 14:37:26',NULL),(23,1,1,'Convertisseur HDMI vers VGA','Produit High-Tech garanti d\'excellente qualité. Convertisseur HDMI vers VGA est idéal pour une utilisation quotidienne professionnelle ou personnelle. Livré rapidement dans tout le pays avec emballage sécurisé.','Douala',25,35,46,0,1,1,5,'\"{\\\"Marque\\\":\\\"G\\\\u00e9n\\\\u00e9rique\\\\/Original\\\",\\\"Garantie\\\":\\\"6 Mois\\\",\\\"\\\\u00c9tat\\\":\\\"Neuf\\\"}\"','visible',337,19,'2026-08-16 14:37:26','2026-08-16 14:37:26',NULL),(24,1,1,'Pochette de Protection MacBook 13\"','Produit High-Tech garanti d\'excellente qualité. Pochette de Protection MacBook 13\" est idéal pour une utilisation quotidienne professionnelle ou personnelle. Livré rapidement dans tout le pays avec emballage sécurisé.','Douala',14,20,35,0,1,1,4,'\"{\\\"Marque\\\":\\\"G\\\\u00e9n\\\\u00e9rique\\\\/Original\\\",\\\"Garantie\\\":\\\"6 Mois\\\",\\\"\\\\u00c9tat\\\":\\\"Neuf\\\"}\"','visible',493,24,'2026-08-16 14:37:26','2026-08-16 14:37:26',NULL),(25,1,1,'Manette de Jeu PC/Android Bluetooth','Produit High-Tech garanti d\'excellente qualité. Manette de Jeu PC/Android Bluetooth est idéal pour une utilisation quotidienne professionnelle ou personnelle. Livré rapidement dans tout le pays avec emballage sécurisé.','Douala',22,47,37,1,1,0,3,'\"{\\\"Marque\\\":\\\"G\\\\u00e9n\\\\u00e9rique\\\\/Original\\\",\\\"Garantie\\\":\\\"6 Mois\\\",\\\"\\\\u00c9tat\\\":\\\"Neuf\\\"}\"','visible',219,16,'2026-08-16 14:37:26','2026-08-16 14:40:35',NULL),(26,2,2,'T-shirt Homme Coton Qualité Supérieure','Découvrez notre superbe T-shirt Homme Coton Qualité Supérieure. Cet article a été conçu avec soin pour offrir un confort maximal et un style impeccable en toute occasion. Disponible en plusieurs tailles.','Yaoundé',52,57,86,0,1,0,3,'\"{\\\"Taille\\\":\\\"S, M, L, XL\\\",\\\"Mati\\\\u00e8re\\\":\\\"Coton \\\\/ Cuir\\\",\\\"Origine\\\":\\\"Importation\\\"}\"','visible',50,1,'2026-08-16 14:37:26','2026-08-16 14:37:26',NULL),(27,2,2,'Chemise Homme Manches Longues Slim Fit','Découvrez notre superbe Chemise Homme Manches Longues Slim Fit. Cet article a été conçu avec soin pour offrir un confort maximal et un style impeccable en toute occasion. Disponible en plusieurs tailles.','Yaoundé',95,101,93,0,1,0,3,'\"{\\\"Taille\\\":\\\"S, M, L, XL\\\",\\\"Mati\\\\u00e8re\\\":\\\"Coton \\\\/ Cuir\\\",\\\"Origine\\\":\\\"Importation\\\"}\"','visible',30,3,'2026-08-16 14:37:26','2026-08-16 14:37:26',NULL),(28,2,2,'Jean Homme Original Coupe Droite','Découvrez notre superbe Jean Homme Original Coupe Droite. Cet article a été conçu avec soin pour offrir un confort maximal et un style impeccable en toute occasion. Disponible en plusieurs tailles.','Yaoundé',37,NULL,36,0,1,0,3,'\"{\\\"Taille\\\":\\\"S, M, L, XL\\\",\\\"Mati\\\\u00e8re\\\":\\\"Coton \\\\/ Cuir\\\",\\\"Origine\\\":\\\"Importation\\\"}\"','visible',187,9,'2026-08-16 14:37:26','2026-08-16 14:37:26',NULL),(29,2,2,'Robe de Soirée Élégante Africaine','Découvrez notre superbe Robe de Soirée Élégante Africaine. Cet article a été conçu avec soin pour offrir un confort maximal et un style impeccable en toute occasion. Disponible en plusieurs tailles.','Yaoundé',35,NULL,71,0,1,0,3,'\"{\\\"Taille\\\":\\\"S, M, L, XL\\\",\\\"Mati\\\\u00e8re\\\":\\\"Coton \\\\/ Cuir\\\",\\\"Origine\\\":\\\"Importation\\\"}\"','visible',164,13,'2026-08-16 14:37:26','2026-08-16 14:37:26',NULL),(30,2,2,'Ensemble Bazin Riche Brodé 3 Pièces','Découvrez notre superbe Ensemble Bazin Riche Brodé 3 Pièces. Cet article a été conçu avec soin pour offrir un confort maximal et un style impeccable en toute occasion. Disponible en plusieurs tailles.','Yaoundé',13,NULL,33,0,1,0,3,'\"{\\\"Taille\\\":\\\"S, M, L, XL\\\",\\\"Mati\\\\u00e8re\\\":\\\"Coton \\\\/ Cuir\\\",\\\"Origine\\\":\\\"Importation\\\"}\"','visible',255,14,'2026-08-16 14:37:26','2026-08-16 14:37:26',NULL),(31,2,2,'Chaussures en Cuir Homme Véritable','Découvrez notre superbe Chaussures en Cuir Homme Véritable. Cet article a été conçu avec soin pour offrir un confort maximal et un style impeccable en toute occasion. Disponible en plusieurs tailles.','Yaoundé',60,73,95,0,1,0,3,'\"{\\\"Taille\\\":\\\"S, M, L, XL\\\",\\\"Mati\\\\u00e8re\\\":\\\"Coton \\\\/ Cuir\\\",\\\"Origine\\\":\\\"Importation\\\"}\"','visible',219,9,'2026-08-16 14:37:26','2026-08-16 14:37:26',NULL),(32,2,2,'Baskets Sneakers Style Urbain','Découvrez notre superbe Baskets Sneakers Style Urbain. Cet article a été conçu avec soin pour offrir un confort maximal et un style impeccable en toute occasion. Disponible en plusieurs tailles.','Yaoundé',100,114,69,0,1,0,3,'\"{\\\"Taille\\\":\\\"S, M, L, XL\\\",\\\"Mati\\\\u00e8re\\\":\\\"Coton \\\\/ Cuir\\\",\\\"Origine\\\":\\\"Importation\\\"}\"','visible',153,0,'2026-08-16 14:37:26','2026-08-16 14:37:26',NULL),(33,2,2,'Sac à Main Femme Cuir Synthétique','Découvrez notre superbe Sac à Main Femme Cuir Synthétique. Cet article a été conçu avec soin pour offrir un confort maximal et un style impeccable en toute occasion. Disponible en plusieurs tailles.','Yaoundé',13,26,43,0,1,0,3,'\"{\\\"Taille\\\":\\\"S, M, L, XL\\\",\\\"Mati\\\\u00e8re\\\":\\\"Coton \\\\/ Cuir\\\",\\\"Origine\\\":\\\"Importation\\\"}\"','visible',45,14,'2026-08-16 14:37:26','2026-08-16 14:37:26',NULL),(34,2,2,'Pochette de Soirée Dorée','Découvrez notre superbe Pochette de Soirée Dorée. Cet article a été conçu avec soin pour offrir un confort maximal et un style impeccable en toute occasion. Disponible en plusieurs tailles.','Yaoundé',39,NULL,66,0,1,0,3,'\"{\\\"Taille\\\":\\\"S, M, L, XL\\\",\\\"Mati\\\\u00e8re\\\":\\\"Coton \\\\/ Cuir\\\",\\\"Origine\\\":\\\"Importation\\\"}\"','visible',90,1,'2026-08-16 14:37:26','2026-08-16 14:37:26',NULL),(35,2,2,'Polo Homme Sport Respirant','Découvrez notre superbe Polo Homme Sport Respirant. Cet article a été conçu avec soin pour offrir un confort maximal et un style impeccable en toute occasion. Disponible en plusieurs tailles.','Yaoundé',25,45,91,0,1,0,3,'\"{\\\"Taille\\\":\\\"S, M, L, XL\\\",\\\"Mati\\\\u00e8re\\\":\\\"Coton \\\\/ Cuir\\\",\\\"Origine\\\":\\\"Importation\\\"}\"','visible',83,12,'2026-08-16 14:37:26','2026-08-16 14:37:26',NULL),(36,2,2,'Veste Blazer Homme Chic','Découvrez notre superbe Veste Blazer Homme Chic. Cet article a été conçu avec soin pour offrir un confort maximal et un style impeccable en toute occasion. Disponible en plusieurs tailles.','Yaoundé',68,83,25,0,1,0,3,'\"{\\\"Taille\\\":\\\"S, M, L, XL\\\",\\\"Mati\\\\u00e8re\\\":\\\"Coton \\\\/ Cuir\\\",\\\"Origine\\\":\\\"Importation\\\"}\"','visible',170,19,'2026-08-16 14:37:26','2026-08-16 14:37:26',NULL),(37,2,2,'Jupe Longue Plissée Tendance','Découvrez notre superbe Jupe Longue Plissée Tendance. Cet article a été conçu avec soin pour offrir un confort maximal et un style impeccable en toute occasion. Disponible en plusieurs tailles.','Yaoundé',11,NULL,90,0,1,0,3,'\"{\\\"Taille\\\":\\\"S, M, L, XL\\\",\\\"Mati\\\\u00e8re\\\":\\\"Coton \\\\/ Cuir\\\",\\\"Origine\\\":\\\"Importation\\\"}\"','visible',172,19,'2026-08-16 14:37:26','2026-08-16 14:37:26',NULL),(38,2,2,'Pantalon Chino Homme Beige','Découvrez notre superbe Pantalon Chino Homme Beige. Cet article a été conçu avec soin pour offrir un confort maximal et un style impeccable en toute occasion. Disponible en plusieurs tailles.','Yaoundé',63,NULL,24,0,1,0,3,'\"{\\\"Taille\\\":\\\"S, M, L, XL\\\",\\\"Mati\\\\u00e8re\\\":\\\"Coton \\\\/ Cuir\\\",\\\"Origine\\\":\\\"Importation\\\"}\"','visible',254,3,'2026-08-16 14:37:26','2026-08-16 14:37:26',NULL),(39,2,2,'Ceinture Homme Cuir Noir Boucle Automatique','Découvrez notre superbe Ceinture Homme Cuir Noir Boucle Automatique. Cet article a été conçu avec soin pour offrir un confort maximal et un style impeccable en toute occasion. Disponible en plusieurs tailles.','Yaoundé',19,NULL,51,0,1,0,3,'\"{\\\"Taille\\\":\\\"S, M, L, XL\\\",\\\"Mati\\\\u00e8re\\\":\\\"Coton \\\\/ Cuir\\\",\\\"Origine\\\":\\\"Importation\\\"}\"','visible',263,4,'2026-08-16 14:37:26','2026-08-16 14:37:26',NULL),(40,2,2,'Montre Homme Bracelet en Acier','Découvrez notre superbe Montre Homme Bracelet en Acier. Cet article a été conçu avec soin pour offrir un confort maximal et un style impeccable en toute occasion. Disponible en plusieurs tailles.','Yaoundé',99,NULL,56,0,1,0,3,'\"{\\\"Taille\\\":\\\"S, M, L, XL\\\",\\\"Mati\\\\u00e8re\\\":\\\"Coton \\\\/ Cuir\\\",\\\"Origine\\\":\\\"Importation\\\"}\"','visible',212,12,'2026-08-16 14:37:26','2026-08-16 14:37:26',NULL),(41,2,2,'Lunettes de Soleil Polarisées Homme/Femme','Découvrez notre superbe Lunettes de Soleil Polarisées Homme/Femme. Cet article a été conçu avec soin pour offrir un confort maximal et un style impeccable en toute occasion. Disponible en plusieurs tailles.','Yaoundé',98,NULL,24,0,1,0,3,'\"{\\\"Taille\\\":\\\"S, M, L, XL\\\",\\\"Mati\\\\u00e8re\\\":\\\"Coton \\\\/ Cuir\\\",\\\"Origine\\\":\\\"Importation\\\"}\"','visible',216,4,'2026-08-16 14:37:26','2026-08-16 14:37:26',NULL),(42,2,2,'Chapeau Fedora Style Vintage','Découvrez notre superbe Chapeau Fedora Style Vintage. Cet article a été conçu avec soin pour offrir un confort maximal et un style impeccable en toute occasion. Disponible en plusieurs tailles.','Yaoundé',94,112,16,0,1,0,3,'\"{\\\"Taille\\\":\\\"S, M, L, XL\\\",\\\"Mati\\\\u00e8re\\\":\\\"Coton \\\\/ Cuir\\\",\\\"Origine\\\":\\\"Importation\\\"}\"','visible',106,17,'2026-08-16 14:37:26','2026-08-16 14:37:26',NULL),(43,2,2,'Sandales Cuir Homme Confort','Découvrez notre superbe Sandales Cuir Homme Confort. Cet article a été conçu avec soin pour offrir un confort maximal et un style impeccable en toute occasion. Disponible en plusieurs tailles.','Yaoundé',28,NULL,85,0,1,0,3,'\"{\\\"Taille\\\":\\\"S, M, L, XL\\\",\\\"Mati\\\\u00e8re\\\":\\\"Coton \\\\/ Cuir\\\",\\\"Origine\\\":\\\"Importation\\\"}\"','visible',231,19,'2026-08-16 14:37:26','2026-08-16 14:37:26',NULL),(44,2,2,'Escarpins Femme Talons Hauts 8cm','Découvrez notre superbe Escarpins Femme Talons Hauts 8cm. Cet article a été conçu avec soin pour offrir un confort maximal et un style impeccable en toute occasion. Disponible en plusieurs tailles.','Yaoundé',96,NULL,59,0,1,0,3,'\"{\\\"Taille\\\":\\\"S, M, L, XL\\\",\\\"Mati\\\\u00e8re\\\":\\\"Coton \\\\/ Cuir\\\",\\\"Origine\\\":\\\"Importation\\\"}\"','visible',258,6,'2026-08-16 14:37:26','2026-08-16 14:37:26',NULL),(45,2,2,'Ensemble Sport Survêtement Homme','Découvrez notre superbe Ensemble Sport Survêtement Homme. Cet article a été conçu avec soin pour offrir un confort maximal et un style impeccable en toute occasion. Disponible en plusieurs tailles.','Yaoundé',73,NULL,98,0,1,0,3,'\"{\\\"Taille\\\":\\\"S, M, L, XL\\\",\\\"Mati\\\\u00e8re\\\":\\\"Coton \\\\/ Cuir\\\",\\\"Origine\\\":\\\"Importation\\\"}\"','visible',139,4,'2026-08-16 14:37:26','2026-08-16 14:37:26',NULL),(46,2,2,'Pyjama Coton Doux 2 Pièces','Découvrez notre superbe Pyjama Coton Doux 2 Pièces. Cet article a été conçu avec soin pour offrir un confort maximal et un style impeccable en toute occasion. Disponible en plusieurs tailles.','Yaoundé',43,50,15,0,1,0,3,'\"{\\\"Taille\\\":\\\"S, M, L, XL\\\",\\\"Mati\\\\u00e8re\\\":\\\"Coton \\\\/ Cuir\\\",\\\"Origine\\\":\\\"Importation\\\"}\"','visible',126,20,'2026-08-16 14:37:26','2026-08-16 14:37:26',NULL),(47,2,2,'Sac à Dos Voyage/Ordi 15 pouces','Découvrez notre superbe Sac à Dos Voyage/Ordi 15 pouces. Cet article a été conçu avec soin pour offrir un confort maximal et un style impeccable en toute occasion. Disponible en plusieurs tailles.','Yaoundé',99,NULL,74,0,1,0,3,'\"{\\\"Taille\\\":\\\"S, M, L, XL\\\",\\\"Mati\\\\u00e8re\\\":\\\"Coton \\\\/ Cuir\\\",\\\"Origine\\\":\\\"Importation\\\"}\"','visible',62,17,'2026-08-16 14:37:26','2026-08-16 14:37:26',NULL),(48,2,2,'Portefeuille Cuir Compact Homme','Découvrez notre superbe Portefeuille Cuir Compact Homme. Cet article a été conçu avec soin pour offrir un confort maximal et un style impeccable en toute occasion. Disponible en plusieurs tailles.','Yaoundé',39,NULL,55,0,1,0,3,'\"{\\\"Taille\\\":\\\"S, M, L, XL\\\",\\\"Mati\\\\u00e8re\\\":\\\"Coton \\\\/ Cuir\\\",\\\"Origine\\\":\\\"Importation\\\"}\"','visible',109,0,'2026-08-16 14:37:26','2026-08-16 14:37:26',NULL),(49,2,2,'Casquette Style Baseball Réglable','Découvrez notre superbe Casquette Style Baseball Réglable. Cet article a été conçu avec soin pour offrir un confort maximal et un style impeccable en toute occasion. Disponible en plusieurs tailles.','Yaoundé',28,45,84,0,1,0,3,'\"{\\\"Taille\\\":\\\"S, M, L, XL\\\",\\\"Mati\\\\u00e8re\\\":\\\"Coton \\\\/ Cuir\\\",\\\"Origine\\\":\\\"Importation\\\"}\"','visible',88,9,'2026-08-16 14:37:26','2026-08-16 14:37:26',NULL),(50,2,2,'Écharpe / Foulard en Soie Imprimé','Découvrez notre superbe Écharpe / Foulard en Soie Imprimé. Cet article a été conçu avec soin pour offrir un confort maximal et un style impeccable en toute occasion. Disponible en plusieurs tailles.','Yaoundé',28,NULL,10,0,1,0,3,'\"{\\\"Taille\\\":\\\"S, M, L, XL\\\",\\\"Mati\\\\u00e8re\\\":\\\"Coton \\\\/ Cuir\\\",\\\"Origine\\\":\\\"Importation\\\"}\"','visible',107,1,'2026-08-16 14:37:26','2026-08-16 14:37:26',NULL);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `provider_transactions`
--

DROP TABLE IF EXISTS `provider_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `provider_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reference_uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `operator` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` int unsigned NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'processing',
  `raw_provider_response` json DEFAULT NULL,
  `order_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `provider_transactions_reference_uuid_unique` (`reference_uuid`),
  KEY `provider_transactions_order_id_foreign` (`order_id`),
  CONSTRAINT `provider_transactions_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `provider_transactions`
--

LOCK TABLES `provider_transactions` WRITE;
/*!40000 ALTER TABLE `provider_transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `provider_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reviews` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `reviewer_id` bigint unsigned NOT NULL,
  `reviewee_type` enum('shop','buyer') COLLATE utf8mb4_unicode_ci NOT NULL,
  `reviewee_id` bigint unsigned NOT NULL,
  `rating` tinyint unsigned NOT NULL,
  `body` text COLLATE utf8mb4_unicode_ci,
  `is_verified` tinyint(1) NOT NULL DEFAULT '1',
  `is_flagged` tinyint(1) NOT NULL DEFAULT '0',
  `is_contested` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reviews_order_id_reviewer_id_reviewee_type_unique` (`order_id`,`reviewer_id`,`reviewee_type`),
  KEY `reviews_reviewer_id_foreign` (`reviewer_id`),
  KEY `reviews_reviewee_type_reviewee_id_index` (`reviewee_type`,`reviewee_id`),
  CONSTRAINT `reviews_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_reviewer_id_foreign` FOREIGN KEY (`reviewer_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `secretary_counters`
--

DROP TABLE IF EXISTS `secretary_counters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `secretary_counters` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `agency_counter_id` bigint unsigned NOT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `secretary_counters_user_id_agency_counter_id_unique` (`user_id`,`agency_counter_id`),
  KEY `secretary_counters_agency_counter_id_foreign` (`agency_counter_id`),
  CONSTRAINT `secretary_counters_agency_counter_id_foreign` FOREIGN KEY (`agency_counter_id`) REFERENCES `agency_counters` (`id`) ON DELETE CASCADE,
  CONSTRAINT `secretary_counters_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `secretary_counters`
--

LOCK TABLES `secretary_counters` WRITE;
/*!40000 ALTER TABLE `secretary_counters` DISABLE KEYS */;
INSERT INTO `secretary_counters` VALUES (1,6,1,1,'2026-08-16 14:37:29','2026-08-16 14:37:29'),(2,7,2,1,'2026-08-16 14:37:30','2026-08-16 14:37:30'),(3,8,3,1,'2026-08-16 14:37:30','2026-08-16 14:37:30'),(4,9,4,1,'2026-08-16 14:37:30','2026-08-16 14:37:30'),(5,10,5,1,'2026-08-16 14:37:30','2026-08-16 14:37:30'),(6,11,6,1,'2026-08-16 14:37:31','2026-08-16 14:37:31');
/*!40000 ALTER TABLE `secretary_counters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('0FIVyu1nKvT0WjiyizImjGgJZqaTc9Ff3MGPJASq',NULL,'157.245.250.149','python-requests/2.32.3','YToyOntzOjY6Il90b2tlbiI7czo0MDoiZ0FVNVV0V1pETnA2a1BRU2VDS2p3VzY5a2ZLcm0wU2oyMGZWQ3dMWCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1786894871),('9NJcUv7i1S7vlVBHHnsxc4lAklPYc78SGpOLI4FQ',NULL,'157.245.250.149','python-requests/2.32.3','YToyOntzOjY6Il90b2tlbiI7czo0MDoiaGZRaWtLMWU0NmR2eXVSR3lRZ2NORnAwS0xVVGFoSW9Od3hhRVQyeCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1786897383),('FXGVIqujiKpbojU2goBhfTMvIo5yGXSTMHCnp78m',8,'129.0.80.253','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiT3gwZFNYdnZIME1mS3o2NmNaTVNPZE9aSm00Zkh0SFllQk1Zb3lEYiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTc6Imh0dHBzOi8vb3ZlcnJhdGUtc25hcmwtc2Fuay5uZ3Jvay1mcmVlLmRldi9hZ2VuY2UvcmVtaXNlcyI7czo1OiJyb3V0ZSI7czoyMzoic2VjcmV0YXJ5LmhhbmRvdmVyLnBhZ2UiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo4O30=',1786897830),('OhFHpqpkb7h2QymtJzmC2GmZG54rxYKzbRTaripQ',4,'129.0.80.253','Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:153.0) Gecko/20100101 Firefox/153.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZ2tCWFhkNWhUSHlqQWNnaUZSVDhuVlU3eXlXTUhFellLdUhEZVFLeiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NjE6Imh0dHBzOi8vb3ZlcnJhdGUtc25hcmwtc2Fuay5uZ3Jvay1mcmVlLmRldi9wYWllbWVudC80L2F0dGVudGUiO3M6NToicm91dGUiO3M6MjE6ImJ1eWVyLnBheW1lbnQud2FpdGluZyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjQ7fQ==',1786897984),('r6P4NMEK4Ij65zPGl4dG0mD8KKSf5mPODZzlsbrp',NULL,'157.245.250.149','python-requests/2.32.3','YToyOntzOjY6Il90b2tlbiI7czo0MDoicDVrT1lWdG9KSXdnalZtc0ZHMGtWUVFjMUJWR1M1RGx3OTVKM1M4ViI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1786897719),('vJhpDw22hxKC5crVddwpjCAfcrAzHj5n9v21spDq',NULL,'157.245.250.149','python-requests/2.32.3','YToyOntzOjY6Il90b2tlbiI7czo0MDoidzVYM0tDUERZajJoNnhEendncTJlWkJoaW01eWpaakNkU3R4ZGRyVyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1786894828),('zXvk54jnRpYUV211QutbAtKGDrd1cbHyXsATW6xU',NULL,'157.245.250.149','python-requests/2.32.3','YToyOntzOjY6Il90b2tlbiI7czo0MDoiakJFZVNLTWlHSUsxM0dXQ3BEQmM4a0xzZTFRWkdXN2VwSElwYlZ6YiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1786897601);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shops`
--

DROP TABLE IF EXISTS `shops`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shops` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','active','suspended','rejected','banned') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `shops_slug_unique` (`slug`),
  KEY `shops_user_id_foreign` (`user_id`),
  KEY `shops_status_index` (`status`),
  CONSTRAINT `shops_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shops`
--

LOCK TABLES `shops` WRITE;
/*!40000 ALTER TABLE `shops` DISABLE KEYS */;
INSERT INTO `shops` VALUES (1,2,'Kamer Tech Store','kamer-tech-store','Douala','+237690000001','Akwa, Rue Joyeuse','Vente de matériel informatique et accessoires high-tech.',NULL,'active','2026-08-16 14:37:25','2026-08-16 14:37:25','2026-08-16 14:37:25'),(2,3,'Fashion Kamer','fashion-kamer','Yaoundé','+237670000002','Bastos, Avenue des Palmiers','Boutique de vêtements modernes et accessoires de mode.',NULL,'active','2026-08-16 14:37:25','2026-08-16 14:37:25','2026-08-16 14:37:25');
/*!40000 ALTER TABLE `shops` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tutorials`
--

DROP TABLE IF EXISTS `tutorials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tutorials` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('video','text') COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_target` enum('buyer','seller') COLLATE utf8mb4_unicode_ci NOT NULL,
  `content_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_index` int NOT NULL DEFAULT '0',
  `is_published` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tutorials_role_target_is_published_index` (`role_target`,`is_published`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tutorials`
--

LOCK TABLES `tutorials` WRITE;
/*!40000 ALTER TABLE `tutorials` DISABLE KEYS */;
/*!40000 ALTER TABLE `tutorials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `phone_verified_at` timestamp NULL DEFAULT NULL,
  `agency_id` bigint unsigned DEFAULT NULL,
  `role` enum('buyer','seller','secretary','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'buyer',
  `status` enum('candidate','active','suspended','banned') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `phone_momo` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `momo_operator` enum('mtn','orange') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wallet_pending` bigint unsigned NOT NULL DEFAULT '0',
  `wallet_available` bigint unsigned NOT NULL DEFAULT '0',
  `trust_score` tinyint unsigned NOT NULL DEFAULT '100',
  `dispute_count` smallint unsigned NOT NULL DEFAULT '0',
  `abuse_count` smallint unsigned NOT NULL DEFAULT '0',
  `prepayment_required` tinyint(1) NOT NULL DEFAULT '0',
  `purchase_restricted` tinyint(1) NOT NULL DEFAULT '0',
  `referral_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_phone_unique` (`phone`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_referral_code_unique` (`referral_code`),
  KEY `users_role_index` (`role`),
  KEY `users_status_index` (`status`),
  KEY `users_agency_id_foreign` (`agency_id`),
  CONSTRAINT `users_agency_id_foreign` FOREIGN KEY (`agency_id`) REFERENCES `agencies` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin System','admin@test.com','+237673538767','$2y$12$SdVtoc1hkqpodaE0tasaEeiW5opckG4EimOgKb7jkEoWTMntnCFny','2026-08-16 14:37:25',NULL,NULL,'admin','active',NULL,NULL,0,0,100,0,0,0,0,NULL,NULL,'2026-08-16 14:37:25','2026-08-16 14:37:25',NULL),(2,'Vendeur Paul','vendeur1@test.com','+237676538747','$2y$12$SdVtoc1hkqpodaE0tasaEeiW5opckG4EimOgKb7jkEoWTMntnCFny','2026-08-16 14:37:25',NULL,NULL,'seller','active',NULL,NULL,60,0,100,0,0,0,0,NULL,NULL,'2026-08-16 14:37:25','2026-08-16 15:23:03',NULL),(3,'Vendeur Marie','vendeur2@test.com','+237673538746','$2y$12$SdVtoc1hkqpodaE0tasaEeiW5opckG4EimOgKb7jkEoWTMntnCFny','2026-08-16 14:37:25',NULL,NULL,'seller','active',NULL,NULL,0,0,100,0,0,0,0,NULL,NULL,'2026-08-16 14:37:25','2026-08-16 14:37:25',NULL),(4,'Acheteur Eric','acheteur1@test.com','+237678538747','$2y$12$SdVtoc1hkqpodaE0tasaEeiW5opckG4EimOgKb7jkEoWTMntnCFny','2026-08-16 14:37:25',NULL,NULL,'buyer','active',NULL,NULL,0,0,100,0,0,0,0,NULL,NULL,'2026-08-16 14:37:25','2026-08-16 14:37:25',NULL),(5,'Acheteur Alice','acheteur2@test.com','+237674538747','$2y$12$SdVtoc1hkqpodaE0tasaEeiW5opckG4EimOgKb7jkEoWTMntnCFny','2026-08-16 14:37:25',NULL,NULL,'buyer','active',NULL,NULL,0,0,100,0,0,0,0,NULL,NULL,'2026-08-16 14:37:25','2026-08-16 14:37:25',NULL),(6,'Secrétaire General - Douala','secretaire1@test.com','600000001','$2y$12$I7BiEMxSDmyUHa3kVzgSkuq9Vz16XNFY081kUdn1aVNCfDmMk3D8S','2026-08-16 14:37:29',NULL,NULL,'secretary','active',NULL,NULL,0,0,100,0,0,0,0,NULL,NULL,'2026-08-16 14:37:29','2026-08-16 14:37:29',NULL),(7,'Secrétaire General - Yaoundé','secretaire2@test.com','600000002','$2y$12$AMKwrQMAA0MVXpBCF/d63Op10aCQOfRRsnXK925TOj1lra7uWnSPm','2026-08-16 14:37:29',NULL,NULL,'secretary','active',NULL,NULL,0,0,100,0,0,0,0,NULL,NULL,'2026-08-16 14:37:29','2026-08-16 14:37:29',NULL),(8,'Secrétaire General - Bafoussam','secretaire3@test.com','600000003','$2y$12$XYnudwD5BJPOz3tDY4hqSOzKhllIoKNnUeAXEoCzLsZsHFEjIvNOu','2026-08-16 14:37:30',NULL,NULL,'secretary','active',NULL,NULL,0,0,100,0,0,0,0,NULL,NULL,'2026-08-16 14:37:30','2026-08-16 14:37:30',NULL),(9,'Secrétaire Global Voyage - Douala','secretaire4@test.com','600000004','$2y$12$x7N8IAsQ.wEKf1WNExD0GOqvtv2XmvkvegLPVYDRY38Z6uVWsSQ3G','2026-08-16 14:37:30',NULL,NULL,'secretary','active',NULL,NULL,0,0,100,0,0,0,0,NULL,NULL,'2026-08-16 14:37:30','2026-08-16 14:37:30',NULL),(10,'Secrétaire Global Voyage - Yaoundé','secretaire5@test.com','600000005','$2y$12$eunQ0UyJkDl2hBXRoPnMKe7EAJAxT4vRSxEnbHLFTxx/xV6fC6SV6','2026-08-16 14:37:30',NULL,NULL,'secretary','active',NULL,NULL,0,0,100,0,0,0,0,NULL,NULL,'2026-08-16 14:37:30','2026-08-16 14:37:30',NULL),(11,'Secrétaire Global Voyage - Kribi','secretaire6@test.com','600000006','$2y$12$l.VBHeMQ64rTltHKFdNa5.h9c1nTOzgEKiA1hfUf8NQbzJN.UdxF2','2026-08-16 14:37:31',NULL,NULL,'secretary','active',NULL,NULL,0,0,100,0,0,0,0,NULL,NULL,'2026-08-16 14:37:31','2026-08-16 14:37:31',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wallet_transactions`
--

DROP TABLE IF EXISTS `wallet_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wallet_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `type` enum('credit_escrow','debit_escrow','credit_available','debit_withdrawal','debit_commission','credit_refund','credit_secretary','debit_transport_fee','credit_transport_fee') COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` int unsigned NOT NULL,
  `balance_after` bigint unsigned NOT NULL,
  `ref_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ref_id` bigint unsigned DEFAULT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `wallet_transactions_user_id_index` (`user_id`),
  KEY `wallet_transactions_ref_type_ref_id_index` (`ref_type`,`ref_id`),
  CONSTRAINT `wallet_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wallet_transactions`
--

LOCK TABLES `wallet_transactions` WRITE;
/*!40000 ALTER TABLE `wallet_transactions` DISABLE KEYS */;
INSERT INTO `wallet_transactions` VALUES (1,2,'credit_escrow',18,18,'order',1,'Séquestre commande ALK-2026-00001','2026-08-16 14:40:28','2026-08-16 14:40:28'),(2,2,'credit_escrow',21,39,'order',2,'Séquestre commande ALK-2026-00002','2026-08-16 14:41:11','2026-08-16 14:41:11'),(3,2,'credit_escrow',21,60,'order',4,'Séquestre commande ALK-2026-00004','2026-08-16 15:23:03','2026-08-16 15:23:03');
/*!40000 ALTER TABLE `wallet_transactions` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-08-16 17:33:07
