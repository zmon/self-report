-- MySQL dump 10.13  Distrib 8.0.19, for osx10.14 (x86_64)
--
-- Host: localhost    Database: opencity
-- ------------------------------------------------------
-- Server version	8.0.19

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
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  UNIQUE KEY `cache_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('kcdd_covid_19_symptom_tracker_cache356a192b7913b04c54574d18c28d46e6395428ab','i:1;',1591115995),('kcdd_covid_19_symptom_tracker_cache356a192b7913b04c54574d18c28d46e6395428ab:timer','i:1591115995;',1591115995);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
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
-- Table structure for table `histories`
--

DROP TABLE IF EXISTS `histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `histories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `historyable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `historyable_id` bigint unsigned NOT NULL,
  `action` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reason_for_change` varchar(600) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `old` json DEFAULT NULL,
  `new` json DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `histories_historyable_type_historyable_id_index` (`historyable_type`,`historyable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `histories`
--

LOCK TABLES `histories` WRITE;
/*!40000 ALTER TABLE `histories` DISABLE KEYS */;
INSERT INTO `histories` VALUES (1,'App\\User',1,'created',NULL,NULL,'[]',1,'2020-06-02 21:34:45','2020-06-02 21:34:45');
/*!40000 ALTER TABLE `histories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invites`
--

DROP TABLE IF EXISTS `invites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invites` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint NOT NULL DEFAULT '0',
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(42) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `expires_at` datetime NOT NULL,
  `token` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int DEFAULT '0',
  `modified_by` int DEFAULT '0',
  `purged_by` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invites_token_unique` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invites`
--

LOCK TABLES `invites` WRITE;
/*!40000 ALTER TABLE `invites` DISABLE KEYS */;
/*!40000 ALTER TABLE `invites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2016_06_01_000001_create_oauth_auth_codes_table',1),(4,'2016_06_01_000002_create_oauth_access_tokens_table',1),(5,'2016_06_01_000003_create_oauth_refresh_tokens_table',1),(6,'2016_06_01_000004_create_oauth_clients_table',1),(7,'2016_06_01_000005_create_oauth_personal_access_clients_table',1),(8,'2018_11_16_233502_create_invites_table',1),(9,'2019_05_12_144309_add_active_to_users',1),(10,'2019_06_02_033742_create_cache_table',1),(11,'2019_08_19_000000_create_failed_jobs_table',1),(12,'2019_08_22_031123_create_histories_table',1),(13,'2020_04_12_011655_create_permission_tables',1),(14,'2020_04_13_035351_update-roles',1),(15,'2020_04_19_043909_create_jobs_table',1),(16,'2020_04_19_044304_create_organizations_table',1),(17,'2020_04_19_162702_create_self_reports_table',1),(18,'2020_04_21_052333_create_preexisting_conditions_table',1),(19,'2020_04_21_052335_create_race_ethnicities_table',1),(20,'2020_04_21_052337_create_symptoms_table',1),(21,'2020_04_21_052339_create_preexisting_condition_self_report_table',1),(22,'2020_04_21_052341_create_race_ethnicity_self_report_table',1),(23,'2020_04_21_052343_create_self_report_symptom_table',1),(24,'2020_04_23_191933_add_orgnization_id_to_users_table',1),(25,'2020_04_27_052137_dd_organization_id_to_invites',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES (1,'App\\User',1);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_access_tokens`
--

DROP TABLE IF EXISTS `oauth_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `client_id` bigint unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_access_tokens_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_access_tokens`
--

LOCK TABLES `oauth_access_tokens` WRITE;
/*!40000 ALTER TABLE `oauth_access_tokens` DISABLE KEYS */;
INSERT INTO `oauth_access_tokens` VALUES ('bf5487b25b73445272cf197abb3ab60986ec10164bb071f42dd6040fdac7252f63531e19fb6c944d',1,1,'Hackerpair','[]',0,'2020-06-02 21:37:20','2020-06-02 21:37:20','2021-06-02 16:37:20');
/*!40000 ALTER TABLE `oauth_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_auth_codes`
--

DROP TABLE IF EXISTS `oauth_auth_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `client_id` bigint unsigned NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_auth_codes_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_auth_codes`
--

LOCK TABLES `oauth_auth_codes` WRITE;
/*!40000 ALTER TABLE `oauth_auth_codes` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_auth_codes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_clients`
--

DROP TABLE IF EXISTS `oauth_clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_clients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_clients_user_id_index` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_clients`
--

LOCK TABLES `oauth_clients` WRITE;
/*!40000 ALTER TABLE `oauth_clients` DISABLE KEYS */;
INSERT INTO `oauth_clients` VALUES (1,NULL,'KCDD COVID-19 Symptom Tracker Personal Access Client','1ReEtdVUWifvIPNVZrjNYycyOmb0gWHVSnCpgyU4','http://localhost',1,0,0,'2020-06-02 21:36:20','2020-06-02 21:36:20'),(2,NULL,'KCDD COVID-19 Symptom Tracker Password Grant Client','6xBjhRViGRzKTusTNgVCtiGiyEpvkxFKiaOid4tW','http://localhost',0,1,0,'2020-06-02 21:36:20','2020-06-02 21:36:20');
/*!40000 ALTER TABLE `oauth_clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_personal_access_clients`
--

DROP TABLE IF EXISTS `oauth_personal_access_clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_personal_access_clients`
--

LOCK TABLES `oauth_personal_access_clients` WRITE;
/*!40000 ALTER TABLE `oauth_personal_access_clients` DISABLE KEYS */;
INSERT INTO `oauth_personal_access_clients` VALUES (1,1,'2020-06-02 21:36:20','2020-06-02 21:36:20');
/*!40000 ALTER TABLE `oauth_personal_access_clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_refresh_tokens`
--

DROP TABLE IF EXISTS `oauth_refresh_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_refresh_tokens`
--

LOCK TABLES `oauth_refresh_tokens` WRITE;
/*!40000 ALTER TABLE `oauth_refresh_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_refresh_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `organizations`
--

DROP TABLE IF EXISTS `organizations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `organizations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alias` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `url_code` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `contact_name` varchar(42) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_1` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int NOT NULL DEFAULT '0',
  `modified_by` int NOT NULL DEFAULT '0',
  `purged_by` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `organizations_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `organizations`
--

LOCK TABLES `organizations` WRITE;
/*!40000 ALTER TABLE `organizations` DISABLE KEYS */;
/*!40000 ALTER TABLE `organizations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'invite index','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(2,'invite view','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(3,'invite export-pdf','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(4,'invite export-excel','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(5,'invite add','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(6,'invite edit','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(7,'invite delete','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(8,'organization index','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(9,'organization view','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(10,'organization export-pdf','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(11,'organization export-excel','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(12,'organization add','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(13,'organization edit','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(14,'preexisting_condition index','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(15,'preexisting_condition view','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(16,'preexisting_condition export-pdf','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(17,'preexisting_condition export-excel','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(18,'preexisting_condition add','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(19,'preexisting_condition edit','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(20,'preexisting_condition delete','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(21,'race_ethnicity index','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(22,'race_ethnicity view','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(23,'race_ethnicity export-pdf','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(24,'race_ethnicity export-excel','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(25,'race_ethnicity add','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(26,'race_ethnicity edit','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(27,'race_ethnicity delete','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(28,'self_report index','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(29,'self_report view','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(30,'self_report export-pdf','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(31,'self_report export-excel','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(32,'self_report add','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(33,'self_report edit','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(34,'self_report delete','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(35,'symptom index','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(36,'symptom view','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(37,'symptom export-pdf','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(38,'symptom export-excel','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(39,'symptom add','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(40,'symptom edit','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(41,'symptom delete','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(42,'user index','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(43,'user add','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(44,'user edit','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(45,'user view','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(46,'user delete','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(47,'user export-pdf','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(48,'user export-excel','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(49,'user_role index','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(50,'user_role view','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(51,'user_role export-pdf','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(52,'user_role export-excel','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(53,'user_role add','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(54,'user_role edit','web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(55,'user_role delete','web','2020-06-02 21:34:55','2020-06-02 21:34:55');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `preexisting_condition_self_report`
--

DROP TABLE IF EXISTS `preexisting_condition_self_report`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `preexisting_condition_self_report` (
  `self_report_id` bigint NOT NULL,
  `preexisting_condition_id` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `preexisting_condition_self_report`
--

LOCK TABLES `preexisting_condition_self_report` WRITE;
/*!40000 ALTER TABLE `preexisting_condition_self_report` DISABLE KEYS */;
/*!40000 ALTER TABLE `preexisting_condition_self_report` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `preexisting_conditions`
--

DROP TABLE IF EXISTS `preexisting_conditions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `preexisting_conditions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int NOT NULL DEFAULT '0',
  `modified_by` int NOT NULL DEFAULT '0',
  `purged_by` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `preexisting_conditions_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `preexisting_conditions`
--

LOCK TABLES `preexisting_conditions` WRITE;
/*!40000 ALTER TABLE `preexisting_conditions` DISABLE KEYS */;
/*!40000 ALTER TABLE `preexisting_conditions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `race_ethnicities`
--

DROP TABLE IF EXISTS `race_ethnicities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `race_ethnicities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int NOT NULL DEFAULT '0',
  `modified_by` int NOT NULL DEFAULT '0',
  `purged_by` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `race_ethnicities_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `race_ethnicities`
--

LOCK TABLES `race_ethnicities` WRITE;
/*!40000 ALTER TABLE `race_ethnicities` DISABLE KEYS */;
/*!40000 ALTER TABLE `race_ethnicities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `race_ethnicity_self_report`
--

DROP TABLE IF EXISTS `race_ethnicity_self_report`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `race_ethnicity_self_report` (
  `self_report_id` bigint NOT NULL,
  `race_ethnicity_id` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `race_ethnicity_self_report`
--

LOCK TABLES `race_ethnicity_self_report` WRITE;
/*!40000 ALTER TABLE `race_ethnicity_self_report` DISABLE KEYS */;
/*!40000 ALTER TABLE `race_ethnicity_self_report` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
INSERT INTO `role_has_permissions` VALUES (1,2),(2,2),(3,2),(4,2),(5,2),(6,2),(7,2),(8,2),(9,2),(10,2),(11,2),(12,2),(13,2),(14,2),(15,2),(16,2),(17,2),(21,2),(22,2),(23,2),(24,2),(28,2),(29,2),(30,2),(35,2),(36,2),(37,2),(38,2),(42,2),(43,2),(44,2),(45,2),(46,2),(47,2),(48,2),(49,2),(50,2),(51,2),(52,2),(53,2),(54,2),(55,2),(9,3),(28,3),(29,3),(30,3),(31,3);
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `can_assign` int NOT NULL DEFAULT '0',
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'super-admin',0,'web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(2,'Admin',1,'web','2020-06-02 21:34:55','2020-06-02 21:34:55'),(3,'Health Athority',0,'web','2020-06-02 21:34:55','2020-06-02 21:34:55');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `self_report_symptom`
--

DROP TABLE IF EXISTS `self_report_symptom`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `self_report_symptom` (
  `self_report_id` bigint NOT NULL,
  `symptom_id` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `self_report_symptom`
--

LOCK TABLES `self_report_symptom` WRITE;
/*!40000 ALTER TABLE `self_report_symptom` DISABLE KEYS */;
/*!40000 ALTER TABLE `self_report_symptom` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `self_reports`
--

DROP TABLE IF EXISTS `self_reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `self_reports` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint NOT NULL DEFAULT '0',
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `exposed` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `public_private_exposure` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `state` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `kscounty` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `mocounty` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `city_kcmo` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `zipcode` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `selfreport_or_other` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `whose_symptoms` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `sex` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `age` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `any_other_symptoms` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `symptom_severity` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `immunocompromised` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `symptom_start_date` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `followup_contact` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `preferred_contact_method` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `is_voicemail_ok` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `crowded_setting` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `anything_else` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `FormVersionId` int DEFAULT '0',
  `FormId` int DEFAULT '0',
  `FormVersionNumber` varchar(24) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `ExternalId` int DEFAULT '0',
  `ExternalStatus` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `ExternalRespondentId` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `SourceType` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `SourceElementId` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `DataConnectionId` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `CallCounter` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `county_calc` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `form_received_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int NOT NULL DEFAULT '0',
  `modified_by` int NOT NULL DEFAULT '0',
  `purged_by` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `self_reports`
--

LOCK TABLES `self_reports` WRITE;
/*!40000 ALTER TABLE `self_reports` DISABLE KEYS */;
/*!40000 ALTER TABLE `self_reports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `symptoms`
--

DROP TABLE IF EXISTS `symptoms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `symptoms` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int NOT NULL DEFAULT '0',
  `modified_by` int NOT NULL DEFAULT '0',
  `purged_by` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `symptoms_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `symptoms`
--

LOCK TABLES `symptoms` WRITE;
/*!40000 ALTER TABLE `symptoms` DISABLE KEYS */;
/*!40000 ALTER TABLE `symptoms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint NOT NULL DEFAULT '0',
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int DEFAULT '0',
  `modified_by` int DEFAULT '0',
  `purged_by` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,0,'Paul','paulb@savagesoft.com',1,NULL,'$2y$10$Qz7lfTqspCESbggJfLckDeOKjHKPc/Au6xNw.0uldOJ.x.b5Ec2o6',NULL,-1,0,0,'2020-06-02 21:34:45','2020-06-02 21:34:45');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-06-02 11:40:09
