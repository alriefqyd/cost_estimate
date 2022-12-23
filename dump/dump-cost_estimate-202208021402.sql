-- MySQL dump 10.13  Distrib 5.5.62, for Win64 (AMD64)
--
-- Host: localhost    Database: project
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.24-MariaDB

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
-- Table structure for table `discipline_work_type`
--

DROP TABLE IF EXISTS `discipline_work_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `discipline_work_type` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `estimate_discipline_id` int(11) NOT NULL,
  `title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `discipline_work_type`
--

LOCK TABLES `discipline_work_type` WRITE;
/*!40000 ALTER TABLE `discipline_work_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `discipline_work_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee`
--

DROP TABLE IF EXISTS `employee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee`
--

LOCK TABLES `employee` WRITE;
/*!40000 ALTER TABLE `employee` DISABLE KEYS */;
/*!40000 ALTER TABLE `employee` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `equipment_tools`
--

DROP TABLE IF EXISTS `equipment_tools`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `equipment_tools` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `local_rate` decimal(15,2) DEFAULT NULL,
  `national_rate` decimal(15,2) DEFAULT NULL,
  `remark` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=640 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `equipment_tools`
--

LOCK TABLES `equipment_tools` WRITE;
/*!40000 ALTER TABLE `equipment_tools` DISABLE KEYS */;
INSERT INTO `equipment_tools` VALUES (535,1,'EQ010.001','Mini Bus Suzuki Elf, 15 seat',1,'day',575000.00,862500.00,'anasrul',NULL,'2017-06-13 17:00:00'),(536,1,'EQ010.002','Double Cabin LV, Include Fuel',1,'Month',13000000.00,NULL,NULL,NULL,'2017-09-26 03:42:12'),(537,1,'EQ010.003','LV - Double Cabin',1,'day',400000.00,600000.00,'Febrin',NULL,'2017-08-09 17:00:00'),(538,1,'EQ010.004','LV - Double Cabin',1,'mths',12000000.00,18000000.00,NULL,NULL,NULL),(539,1,'EQ010.005','LV Double Cabin 4WD',1,'Month',13000000.00,19500000.00,'SRD',NULL,'2017-11-27 17:00:00'),(540,2,'EQ020.001','NDT Tools',1,'day',1500000.00,2250000.00,'SRD',NULL,'2017-11-27 17:00:00'),(541,2,'EQ020.002','Survey Tools',1,'day',1500000.00,2250000.00,'SRD',NULL,'2017-11-27 17:00:00'),(542,2,'EQ020.003','Survey Equipment',1,'Hr',3000000.00,3000000.00,'Febrin',NULL,'2017-08-10 17:00:00'),(543,3,'EQ030.001','Crane, telescopic, 40T',1,'Hrs',2547000.00,3820500.00,NULL,NULL,NULL),(544,3,'EQ030.002','Crane, 20 Ton',1,'Hrs',1140073.41,1710110.11,NULL,NULL,'2017-03-21 17:00:00'),(545,3,'EQ030.003','Crane, 40 ton',1,'Hrs',1361474.66,2042211.98,NULL,NULL,'2017-03-21 17:00:00'),(546,3,'EQ030.004','Crane, 60 Ton',1,'Hrs',1533217.95,2299826.93,NULL,NULL,'2017-03-21 17:00:00'),(547,3,'EQ030.005','Crane, 90 Ton',1,'Hrs',1895126.04,2842689.06,NULL,NULL,'2017-03-21 17:00:00'),(548,3,'EQ030.006','Hyd. Crane, 110 Ton',1,'Hrs',2122477.09,3183715.63,NULL,NULL,'2017-03-21 17:00:00'),(549,3,'EQ030.007','Truck Crane, 150 Ton',1,'Hrs',2066754.91,3100132.37,NULL,NULL,'2017-03-21 17:00:00'),(550,3,'EQ030.008','Truck Crane, 165 Ton',1,'Hrs',2734620.07,4101930.11,NULL,NULL,'2017-03-21 17:00:00'),(551,3,'EQ030.009','Hyd. Crane, 12 Ton',1,'Hrs',861920.21,1292880.32,NULL,NULL,'2017-03-21 17:00:00'),(552,3,'EQ030.010','Hyd. Crane, 25 Ton',1,'Hrs',882172.42,1323258.63,NULL,NULL,'2017-03-21 17:00:00'),(553,3,'EQ030.011','Hyd. Crane, 33 Ton',1,'Hrs',917642.38,1376463.58,NULL,NULL,'2017-03-21 17:00:00'),(554,3,'EQ030.012','Hyd. Crane, 55 Ton',1,'Hrs',1280694.67,1921042.00,NULL,NULL,'2017-03-21 17:00:00'),(555,3,'EQ030.013','Grove GMK 5220 Cap 220T',1,'Hr',5300000.00,5300000.00,'Febrin',NULL,'2017-08-09 17:00:00'),(556,3,'EQ030.014','Grove GMK 5220 Cap 1500T',1,'Hr',4700000.00,4700000.00,'Febrin',NULL,'2017-08-10 17:00:00'),(557,3,'EQ030.015','Grove GMK 5220 Cap 50T',1,'Hr',3300000.00,3300000.00,'Febrin',NULL,'2017-08-11 17:00:00'),(558,4,'EQ040.001','Dump truck, 220PS, 6m3, 20T.',1,'Hrs',333300.00,499950.00,NULL,NULL,NULL),(559,4,'EQ040.002','Boom truck, flat bed, 3T',1,'Hrs',590000.00,885000.00,NULL,NULL,NULL),(560,4,'EQ040.003','Trailer, 20T Flat deck',1,'Hrs',590000.00,885000.00,NULL,NULL,NULL),(561,4,'EQ040.004','Forklift, 4,000 Lb.',1,'Hrs',333303.28,499954.91,NULL,NULL,'2017-03-21 17:00:00'),(562,4,'EQ040.005','Pickup Truck, 3/4 Ton',1,'Hrs',111558.77,167338.15,NULL,NULL,'2017-03-21 17:00:00'),(563,4,'EQ040.006','Pickup truck, 4 x 4, 3/4 ton',1,'Hrs',121856.50,182784.75,NULL,NULL,'2017-03-21 17:00:00'),(564,4,'EQ040.007','Boom truck 5 Ton',1,'hrs',350000.00,NULL,NULL,NULL,NULL),(565,4,'EQ040.008','Bucket truk/Line truck',1,'hrs',300000.00,NULL,NULL,NULL,NULL),(566,4,'EQ040.009','Boom Truck',1,'hrs',300000.00,NULL,NULL,NULL,NULL),(567,4,'EQ040.010','Excavator',1,'Hr',450000.00,NULL,NULL,NULL,NULL),(568,4,'EQ040.011','Boom truck',1,'Hr',430000.00,NULL,NULL,NULL,NULL),(569,4,'EQ040.012','Boom Truck 5 ton, include operator and rigger',1,'hrs',650000.00,NULL,NULL,NULL,NULL),(570,5,'EQ050.001','Backhoe loader 45-60HP, 0.5m3',1,'Hrs',201031.20,301546.80,NULL,NULL,NULL),(571,5,'EQ050.002','F.E. Loader, T.M., 2.25 C.Y.',1,'Hrs',789149.57,1183724.35,NULL,NULL,'2017-03-21 17:00:00'),(572,5,'EQ050.003','F.E. Loader, T.M., 2.5 C.Y.',1,'Hrs',1127029.61,1690544.42,NULL,NULL,'2017-03-21 17:00:00'),(573,5,'EQ050.004','Excavator 150HP, 1.15m3 ',1,'Hrs',586160.71,879241.07,NULL,NULL,NULL),(574,5,'EQ050.005','Excavator PC300',1,'Hrs',1030000.00,1545000.00,NULL,NULL,'2017-03-22 17:00:00'),(575,5,'EQ050.006','Excavator PC400',1,'Hrs',1150000.00,1725000.00,NULL,NULL,'2017-03-22 17:00:00'),(576,5,'EQ050.007','Bulldozer, 300HP',1,'Hrs',730446.43,1095669.64,NULL,NULL,NULL),(577,5,'EQ050.008','Motor Grader 100 HP',1,'Hrs',657401.79,NULL,NULL,NULL,NULL),(578,6,'EQ060.001','Vibratory roller compactor, 125HP',1,'Hrs',453490.00,680235.00,NULL,NULL,NULL),(579,6,'EQ060.002','Tandem Roller, 5 Ton',1,'Hrs',153665.05,230497.58,NULL,NULL,'2017-03-21 17:00:00'),(580,6,'EQ060.003','Tandem Roller, 10 Ton',1,'Hrs',256299.12,384448.68,NULL,NULL,'2017-03-21 17:00:00'),(581,6,'EQ060.004','Sheepsft. Roller, Towed',1,'Hrs',872790.04,1309185.06,NULL,NULL,'2017-03-21 17:00:00'),(582,6,'EQ060.005','Roller, Pneumatic Wheel, 12 Ton',1,'Hrs',345889.39,518834.09,NULL,NULL,'2017-03-21 17:00:00'),(583,6,'EQ060.006','Roller, Pneumatic Wheel, 25 Ton',1,'Hrs',589030.30,883545.45,NULL,NULL,'2017-03-21 17:00:00'),(584,6,'EQ060.007','Sheepsft. Roll., 130 H.P.',1,'Hrs',1136755.25,1705132.87,NULL,NULL,'2017-03-21 17:00:00'),(585,6,'EQ060.008','Vibratory Drum Roller',1,'Hrs',493146.97,739720.45,NULL,NULL,'2017-03-21 17:00:00'),(586,6,'EQ060.009','Tamper',1,'Hrs',25000.00,37500.00,NULL,NULL,NULL),(587,6,'EQ060.010','Hand stamper (Compaction)',1,'Hr',40000.00,NULL,NULL,NULL,NULL),(588,7,'EQ070.001','Portable air compressor, 60CFM ',1,'Hrs',300000.00,450000.00,NULL,NULL,NULL),(589,7,'EQ070.002','Pump',1,'Hrs',25000.00,37500.00,NULL,NULL,NULL),(590,7,'EQ070.003','Centr. Water Pump, 3\"',1,'Hrs',76660.90,114991.35,NULL,NULL,'2017-03-21 17:00:00'),(591,7,'EQ070.004','Centr. Water Pump, 6\"',1,'Hrs',310419.43,465629.14,NULL,NULL,'2017-03-21 17:00:00'),(592,7,'EQ070.005','Pump, submersible, 6\"D, 1590 GPM',1,'Hrs',277695.52,416543.28,NULL,NULL,'2017-03-21 17:00:00'),(593,7,'EQ070.006','Compressor set',1,'Hrs',300000.00,450000.00,NULL,NULL,NULL),(594,8,'EQ080.001','Mesin las 300A',1,'Hrs',88232.40,132348.60,NULL,NULL,NULL),(595,8,'EQ080.002','Trowel',1,'Hrs',300000.00,450000.00,NULL,NULL,NULL),(596,8,'EQ080.003','Wire brush tools',1,'Hrs',100000.00,150000.00,NULL,NULL,NULL),(597,8,'EQ080.004','Cutting Torch-Oxy Set',1,'Hrs',25744.33,38616.50,NULL,NULL,'2017-03-21 17:00:00'),(598,8,'EQ080.005','Electric Welding Mach.',1,'Hrs',115334.60,173001.91,NULL,NULL,'2017-03-21 17:00:00'),(599,8,'EQ080.006','Welding machine',1,'Hr',56250.00,NULL,NULL,NULL,NULL),(600,8,'EQ080.007','Grinding machine',1,'Hr',15000.00,NULL,NULL,NULL,NULL),(601,8,'EQ080.008','Welding machine',1,'Hr',56250.00,NULL,NULL,NULL,NULL),(602,8,'EQ080.009','Grinding machine',1,'Hr',15000.00,NULL,NULL,NULL,NULL),(603,8,'EQ080.010','Oxy cutting tools',1,'Hr',75000.00,NULL,NULL,NULL,NULL),(604,9,'EQ090.001','Generator set 60 kVA',1,'Hrs',280000.00,420000.00,NULL,NULL,NULL),(605,9,'EQ090.002','Generator, Diesel, 100 KW',1,'Hrs',252523.28,378784.93,NULL,NULL,'2017-03-21 17:00:00'),(606,10,'EQ100.001','Grouting Pump',1,'Hrs',308131.04,462196.56,NULL,NULL,'2017-03-21 17:00:00'),(607,10,'EQ100.002','Concrete Chipping Machine',1,'Hrs',235703.66,353555.48,NULL,NULL,'2017-03-21 17:00:00'),(608,10,'EQ100.003','Concrete Cutting Machine',1,'Hrs',71969.71,107954.56,NULL,NULL,'2017-03-21 17:00:00'),(609,10,'EQ100.004','Concrete Conveyer',1,'Hrs',218540.77,327811.15,NULL,NULL,'2017-03-21 17:00:00'),(610,10,'EQ100.005','Castable mixer, 10 CF',1,'Hrs',176205.65,264308.47,NULL,NULL,'2017-03-21 17:00:00'),(611,10,'EQ100.006','Concrete mixer, 10 CF',1,'Hrs',176205.65,264308.47,NULL,NULL,'2017-03-21 17:00:00'),(612,10,'EQ100.007','Cement Mixer, 2 C.Y.',1,'Hrs',231698.98,347548.47,NULL,NULL,'2017-03-21 17:00:00'),(613,10,'EQ100.008','Chipping Hammers',1,'Hrs',22883.85,34325.77,NULL,NULL,'2017-03-21 17:00:00'),(614,10,'EQ100.009','Concrete Vibrator',1,'Hrs',15000.00,22500.00,NULL,NULL,NULL),(615,12,'EQ120.001','Snay tools',1,'Hr',31250.00,NULL,NULL,NULL,NULL),(616,12,'EQ120.002','Kunci Pipa',1,'Hr',10000.00,NULL,NULL,NULL,NULL),(617,12,'EQ120.003','Fusion welding machine',1,'Hr',125000.00,NULL,NULL,NULL,NULL),(618,13,'EQ130.001','Wire brush',1,'Hr',25000.00,NULL,NULL,NULL,NULL),(619,13,'EQ130.002','Compressor',1,'Hr',68750.00,NULL,NULL,NULL,NULL),(620,13,'EQ130.003','Airless spray',1,'Hr',17750.00,NULL,NULL,NULL,NULL),(621,14,'EQ140.001','Pressure test equipment',1,'Hr',37500.00,NULL,NULL,NULL,NULL),(622,15,'EQ150.001','Portable toilet',1,'Hrs',299640.00,449460.00,NULL,NULL,NULL),(623,15,'EQ150.002','Water truck 5000L',1,'Hrs',176000.00,264000.00,NULL,NULL,NULL),(624,15,'EQ150.003','Scaffolding tube 4m',1,'Bay/Lift',297095.00,445642.50,NULL,NULL,NULL),(625,15,'EQ150.004','Scaffolding tube 10m',1,'Bay/Lift',575623.00,863434.50,NULL,NULL,NULL),(626,15,'EQ150.005','Hand tool - General',1,'Ea/day',50000.00,75000.00,NULL,NULL,NULL),(627,15,'EQ150.006','Hand Tools',1,'Hrs',18078.24,27117.36,NULL,NULL,'2017-03-21 17:00:00'),(628,15,'EQ150.007','Chain Block for Manual Lifting',1,'Hrs',18078.24,27117.36,NULL,NULL,'2017-03-21 17:00:00'),(629,15,'EQ150.008','Hand Tools',1,'Hr',15000.00,NULL,NULL,NULL,NULL),(630,15,'EQ150.009','Tangga A',1,'Hr',10000.00,NULL,NULL,NULL,NULL),(631,16,'EQ160.001','Asphalt Batch Plant Truck, 100Ton/Hr',1,'Hrs',630221.23,945331.84,NULL,NULL,'2017-03-21 17:00:00'),(632,16,'EQ160.002','Hyd. Jack with Rods',1,'Hrs',113046.22,169569.33,NULL,NULL,'2017-03-21 17:00:00'),(633,16,'EQ160.003','Sludge Incenerator',1,'Hr',3500000.00,NULL,'Satria - Truba Proposal',NULL,NULL),(634,16,'EQ160.004','Fuel oil pump',1,'Hr',700751.00,NULL,'Satria - Truba Proposal',NULL,NULL),(635,16,'EQ160.005','B3 Container + trailer truck for Sludge mobilization (From Sor - Final Dispossal)',1,'LS',1650000000.00,NULL,'Satria - Truba Proposal',NULL,NULL),(636,16,'EQ160.006','HPDE Welding Machine for geomembrane installation',1,'Hrs',75000.00,NULL,'Anasrul',NULL,NULL),(637,16,'4018102','Cadweld Mould Horizontal Tee \"TA\" 95 to 95 mm2',1,'set',1653000.00,NULL,NULL,NULL,NULL),(638,16,'4018103','Cadweld Mould Vertical Steel Surface \"VS\" 95 mm2',NULL,NULL,1653000.00,NULL,NULL,NULL,NULL),(639,16,'4018104','Cadweld Mould Vertical Cable to Ground Rod  \"GT\" 5/8\" to 95 mm2',NULL,NULL,1653000.00,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `equipment_tools` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `equipment_tools_category`
--

DROP TABLE IF EXISTS `equipment_tools_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `equipment_tools_category` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `equipment_tools_category`
--

LOCK TABLES `equipment_tools_category` WRITE;
/*!40000 ALTER TABLE `equipment_tools_category` DISABLE KEYS */;
INSERT INTO `equipment_tools_category` VALUES (1,'EQ010','Passenger Vehicle','2022-07-26 05:31:48','2022-07-26 05:31:48'),(2,'EQ021','Surveying Equipment','2022-07-26 05:31:48','2022-07-26 05:31:48'),(3,'EQ030',' Lifting and supporting Equipment','2022-07-26 05:31:48','2022-07-26 05:31:48'),(4,'EQ040',' Material Handling and conveying Equipment','2022-07-26 05:31:48','2022-07-26 05:31:48'),(5,'EQ050',' Ground excavation and Grading Equipment','2022-07-26 05:31:48','2022-07-26 05:31:48'),(6,'EQ060 ','Ground compaction Equipment','2022-07-26 05:31:48','2022-07-26 05:31:48'),(7,'EQ070 ','Pumps and compressor','2022-07-26 05:31:48','2022-07-26 05:31:48'),(8,'EQ080',' Steel Fabrication Tools','2022-07-26 05:31:48','2022-07-26 05:31:48'),(9,'EQ090',' Generator','2022-07-26 05:31:48','2022-07-26 05:31:48'),(10,'EQ100',' Concrete Work Tools','2022-07-26 05:31:48','2022-07-26 05:31:48'),(11,'EQ110','Cable wiring Installation tools','2022-07-26 05:31:48','2022-07-26 05:31:48'),(12,'EQ120 ','Piping and Plumbing Work tools','2022-07-26 05:31:48','2022-07-26 05:31:48'),(13,'EQ130','Painting Tools','2022-07-26 05:31:48','2022-07-26 05:31:48'),(14,'EQ140 ','Inspection Tools','2022-07-26 05:31:48','2022-07-26 05:31:48'),(15,'EQ150 ','Construction Aid','2022-07-26 05:31:48','2022-07-26 05:31:48'),(16,'EQ160','Special Vehicle or tools','2022-07-26 05:31:48','2022-07-26 05:31:48');
/*!40000 ALTER TABLE `equipment_tools_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estimate_all_discipline_project`
--

DROP TABLE IF EXISTS `estimate_all_discipline_project`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `estimate_all_discipline_project` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(10) unsigned NOT NULL,
  `estimate_discipline_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estimate_all_discipline_project`
--

LOCK TABLES `estimate_all_discipline_project` WRITE;
/*!40000 ALTER TABLE `estimate_all_discipline_project` DISABLE KEYS */;
/*!40000 ALTER TABLE `estimate_all_discipline_project` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estimate_all_disciplines`
--

DROP TABLE IF EXISTS `estimate_all_disciplines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `estimate_all_disciplines` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `work_scope` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `work_type_id` int(11) NOT NULL,
  `work_item_id` int(11) NOT NULL,
  `volume` decimal(5,4) NOT NULL,
  `labor_cost_total_rate` decimal(9,2) NOT NULL,
  `labor_unit_rate` decimal(9,2) NOT NULL,
  `tool_unit_rate` decimal(9,2) NOT NULL,
  `tool_unit_rate_total` decimal(9,2) NOT NULL,
  `material_unit_rate` decimal(9,2) NOT NULL,
  `material_unit_rate_total` decimal(9,2) NOT NULL,
  `total_work_cost` decimal(9,2) NOT NULL,
  `contigency` decimal(9,2) NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remark` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estimate_all_disciplines`
--

LOCK TABLES `estimate_all_disciplines` WRITE;
/*!40000 ALTER TABLE `estimate_all_disciplines` DISABLE KEYS */;
/*!40000 ALTER TABLE `estimate_all_disciplines` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
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
-- Table structure for table `man_powers`
--

DROP TABLE IF EXISTS `man_powers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `man_powers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `skill_level` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `basic_rate_month` decimal(20,2) NOT NULL,
  `basic_rate_hour` decimal(20,2) NOT NULL,
  `general_allowance` decimal(20,2) NOT NULL,
  `bpjs` decimal(20,2) NOT NULL,
  `bpjs_kesehatan` decimal(20,2) NOT NULL,
  `thr` decimal(20,2) NOT NULL,
  `public_holiday` decimal(20,2) NOT NULL,
  `leave` decimal(20,2) NOT NULL,
  `pesangon` decimal(20,2) NOT NULL,
  `asuransi` decimal(20,2) NOT NULL,
  `safety` decimal(20,2) NOT NULL,
  `total_benefit_hourly` decimal(20,2) NOT NULL,
  `overall_rate_hourly` decimal(20,2) NOT NULL,
  `factor_hourly` decimal(20,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=113 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `man_powers`
--

LOCK TABLES `man_powers` WRITE;
/*!40000 ALTER TABLE `man_powers` DISABLE KEYS */;
INSERT INTO `man_powers` VALUES (57,'SUPV','Skilled','Supervisor',4420244.43,25550.55,0.00,1977.61,1022.02,2128.36,0.00,0.00,4683.42,0.00,590.88,10402.29,35952.84,62917.46,NULL,NULL),(58,'SFTOF','Skilled','Safety Officer',3788780.94,21900.47,0.00,1695.10,876.02,1824.31,0.00,0.00,4014.36,0.00,590.88,9000.66,30901.13,54076.97,NULL,NULL),(59,'TECH1','Skilled','Technician ‐ 1',3788553.61,21899.15,0.00,1694.99,875.97,1824.20,0.00,0.00,4014.11,0.00,590.88,9000.15,30899.31,54073.79,NULL,NULL),(60,'ELECT1','Skilled','Electrician ‐ 1',3664016.38,21179.29,0.00,1639.28,847.17,1764.23,0.00,0.00,3882.16,0.00,590.88,8723.73,29903.01,52330.27,NULL,NULL),(61,'ELECT2','Semi Skilled','Electrician ‐ 2',3369059.79,19474.33,0.00,1507.31,778.97,1622.21,0.00,0.00,3569.65,0.00,590.88,8069.02,27543.36,48200.88,NULL,NULL),(62,'ELECT3','Semi Skilled','Electrician ‐ 3',3030405.92,17516.80,0.00,1355.80,700.67,1459.15,0.00,0.00,3210.83,0.00,590.88,7317.33,24834.13,43459.72,NULL,NULL),(63,'INST1','Skilled','Instrument ‐ 1',3788553.61,21899.15,0.00,1694.99,875.97,1824.20,0.00,0.00,4014.11,0.00,590.88,9000.15,30899.31,54073.79,NULL,NULL),(64,'INST2','Skilled','Instrument ‐ 2',3524185.11,20371.01,0.00,1576.72,814.84,1696.91,0.00,0.00,3734.01,0.00,590.88,8413.35,28784.36,50372.63,NULL,NULL),(65,'INST3','Semi Skilled','Instrument ‐ 3',3213934.47,18577.66,0.00,1437.91,743.11,1547.52,0.00,0.00,3405.28,0.00,590.88,7724.70,26302.36,46029.12,NULL,NULL),(66,'DRAFT1','Skilled','Draft man ‐ 1',3664016.38,21179.29,0.00,1639.28,847.17,1764.23,0.00,0.00,3882.16,0.00,590.88,8723.73,29903.01,52330.27,NULL,NULL),(67,'WELD2','Semi Skilled','Welder ‐ 2',3213934.47,18577.66,0.00,1437.91,743.11,1547.52,0.00,0.00,3405.28,0.00,590.88,7724.70,26302.36,46029.12,NULL,NULL),(68,'HEOPR2','Semi Skilled','Heavy Equipment Operator ‐ 2',3369059.79,19474.33,0.00,1507.31,778.97,1622.21,0.00,0.00,3569.65,0.00,590.88,8069.02,27543.36,48200.88,NULL,NULL),(69,'FABWELD1','Skilled','Fabrication Welder',3664016.38,21179.29,0.00,1639.28,847.17,1764.23,0.00,0.00,3882.16,0.00,590.88,8723.73,29903.01,52330.27,NULL,NULL),(70,'GENCTRD1','Skilled','General Const. Trade ‐ 1',3664016.38,21179.29,0.00,1639.28,847.17,1764.23,0.00,0.00,3882.16,0.00,590.88,8723.73,29903.01,52330.27,NULL,NULL),(71,'SURVASS1','Skilled','Survey Assistant ‐ 1',3664016.38,21179.29,0.00,1639.28,847.17,1764.23,0.00,0.00,3882.16,0.00,590.88,8723.73,29903.01,52330.27,NULL,NULL),(72,'TECH2','Skilled','Technician ‐ 2',3664016.38,21179.29,0.00,1639.28,847.17,1764.23,0.00,0.00,3882.16,0.00,590.88,8723.73,29903.01,52330.27,NULL,NULL),(73,'DRAFT1','Skilled','Draft man ‐ 1',3664016.38,21179.29,0.00,1639.28,847.17,1764.23,0.00,0.00,3882.16,0.00,590.88,8723.73,29903.01,52330.27,NULL,NULL),(74,'MSON1','Skilled','Mason ‐ 1',3664016.38,21179.29,0.00,1639.28,847.17,1764.23,0.00,0.00,3882.16,0.00,590.88,8723.73,29903.01,52330.27,NULL,NULL),(75,'PFITR1','Skilled','Pipe Fitter ‐ 1',3524185.11,20371.01,0.00,1576.72,814.84,1696.91,0.00,0.00,3734.01,0.00,590.88,8413.35,28784.36,50372.63,NULL,NULL),(76,'WELD1','Skilled','Welder ‐ 1',3524185.11,20371.01,0.00,1576.72,814.84,1696.91,0.00,0.00,3734.01,0.00,590.88,8413.35,28784.36,50372.63,NULL,NULL),(77,'MSON2','Skilled','Mason ‐ 2',3524185.11,20371.01,0.00,1576.72,814.84,1696.91,0.00,0.00,3734.01,0.00,590.88,8413.35,28784.36,50372.63,NULL,NULL),(78,'SCFINSP','Skilled','Scaffolder Inspector',3664016.38,21179.29,0.00,1639.28,847.17,1764.23,0.00,0.00,3882.16,0.00,590.88,8723.73,29903.01,52330.27,NULL,NULL),(79,'HEOPR1','Skilled','Heavy Equipment Operator ‐ 1',3524185.11,20371.01,0.00,1576.72,814.84,1696.91,0.00,0.00,3734.01,0.00,590.88,8413.35,28784.36,50372.63,NULL,NULL),(80,'SCFLDR','Semi Skilled','Scaffolder',3369059.79,19474.33,0.00,1507.31,778.97,1622.21,0.00,0.00,3569.65,0.00,590.88,8069.02,27543.36,48200.88,NULL,NULL),(81,'PLUMB1','Semi Skilled','Plumber ‐ 1',3369059.79,19474.33,0.00,1507.31,778.97,1622.21,0.00,0.00,3569.65,0.00,590.88,8069.02,27543.36,48200.88,NULL,NULL),(82,'GENCTRD2','Semi Skilled','General Const. Trade ‐ 2',3369059.79,19474.33,0.00,1507.31,778.97,1622.21,0.00,0.00,3569.65,0.00,590.88,8069.02,27543.36,48200.88,NULL,NULL),(83,'SURVASS2','Semi Skilled','Survey Assistant ‐ 2',3369059.79,19474.33,0.00,1507.31,778.97,1622.21,0.00,0.00,3569.65,0.00,590.88,8069.02,27543.36,48200.88,NULL,NULL),(84,'CARP1','Semi Skilled','Carpenter ‐ 1',3369059.79,19474.33,0.00,1507.31,778.97,1622.21,0.00,0.00,3569.65,0.00,590.88,8069.02,27543.36,48200.88,NULL,NULL),(85,'TECH3','Semi Skilled','Technician ‐ 3',3213934.47,18577.66,0.00,1437.91,743.11,1547.52,0.00,0.00,3405.28,0.00,590.88,7724.70,26302.36,46029.12,NULL,NULL),(86,'STORE2','Semi Skilled','Store man ‐ 2',3213934.47,18577.66,0.00,1437.91,743.11,1547.52,0.00,0.00,3405.28,0.00,590.88,7724.70,26302.36,46029.12,NULL,NULL),(87,'PLUMB2','Semi Skilled','Plumber ‐ 2',3213934.47,18577.66,0.00,1437.91,743.11,1547.52,0.00,0.00,3405.28,0.00,590.88,7724.70,26302.36,46029.12,NULL,NULL),(88,'DRAFT3','Semi Skilled','Draft man ‐ 3',3213934.47,18577.66,0.00,1437.91,743.11,1547.52,0.00,0.00,3405.28,0.00,590.88,7724.70,26302.36,46029.12,NULL,NULL),(89,'CARP2','Semi Skilled','Carpenter ‐ 2',3213934.47,18577.66,0.00,1437.91,743.11,1547.52,0.00,0.00,3405.28,0.00,590.88,7724.70,26302.36,46029.12,NULL,NULL),(90,'MSON3','Semi Skilled','Mason ‐ 3',3030405.92,17516.80,0.00,1355.80,700.67,1459.15,0.00,0.00,3210.83,0.00,590.88,7317.33,24834.13,43459.72,NULL,NULL),(91,'GENCTRD3','Semi Skilled','General Const. Trade ‐ 3',3030405.92,17516.80,0.00,1355.80,700.67,1459.15,0.00,0.00,3210.83,0.00,590.88,7317.33,24834.13,43459.72,NULL,NULL),(92,'TECH4','Semi Skilled','Technician ‐ 4',3030405.92,17516.80,0.00,1355.80,700.67,1459.15,0.00,0.00,3210.83,0.00,590.88,7317.33,24834.13,43459.72,NULL,NULL),(93,'SURVASS3','Semi Skilled','Survey Assistant ‐ 3',3030405.92,17516.80,0.00,1355.80,700.67,1459.15,0.00,0.00,3210.83,0.00,590.88,7317.33,24834.13,43459.72,NULL,NULL),(94,'WELD3','Semi Skilled','Welder ‐ 3',3030405.92,17516.80,0.00,1355.80,700.67,1459.15,0.00,0.00,3210.83,0.00,590.88,7317.33,24834.13,43459.72,NULL,NULL),(95,'HEOPR3','Semi Skilled','Heavy Equipment Operator ‐ 3',3030405.92,17516.80,0.00,1355.80,700.67,1459.15,0.00,0.00,3210.83,0.00,590.88,7317.33,24834.13,43459.72,NULL,NULL),(96,'LTOPR1','Semi Skilled','Light Truck Driver 1',3030405.92,17516.80,0.00,1355.80,700.67,1459.15,0.00,0.00,3210.83,0.00,590.88,7317.33,24834.13,43459.72,NULL,NULL),(97,'LVOPR1','Un Skilled','Light Vehicle Driver 1',2832675.76,16373.85,0.00,1267.34,654.95,1363.94,0.00,0.00,3001.33,0.00,590.88,6878.44,23252.29,40691.50,NULL,NULL),(98,'CARP3','Un Skilled','Carpenter ‐ 3',2832675.76,16373.85,0.00,1267.34,654.95,1363.94,0.00,0.00,3001.33,0.00,590.88,6878.44,23252.29,40691.50,NULL,NULL),(99,'HELP1','Un Skilled','Helper 1',2684105.03,15515.06,0.00,1200.87,620.60,1292.40,0.00,0.00,2843.91,0.00,590.88,6548.66,22063.72,38611.51,NULL,NULL),(100,'MGRENG','SKilled','Principal/Manager Engineer',33803558.17,195396.29,0.00,15123.67,7815.85,16276.51,0.00,0.00,35816.14,0.00,590.88,75623.06,271019.35,474283.85,NULL,NULL),(101,'PROM','SKilled','Project Manager',33803558.17,195396.29,0.00,15123.67,7815.85,16276.51,0.00,0.00,35816.14,0.00,590.88,75623.06,271019.35,474283.85,NULL,NULL),(102,'MGCONT','Skilled','Construction Manager',28647083.84,165590.08,0.00,12816.67,6623.60,13793.65,0.00,0.00,30352.66,0.00,590.88,64177.47,229767.55,402093.21,NULL,NULL),(103,'PENG','SKilled','Project Engineer',28647083.84,165590.08,0.00,12816.67,6623.60,13793.65,0.00,0.00,30352.66,0.00,590.88,64177.47,229767.55,402093.21,NULL,NULL),(104,'SNRENG','SKilled','Senior Engineer',28647083.84,165590.08,0.00,12816.67,6623.60,13793.65,0.00,0.00,30352.66,0.00,590.88,64177.47,229767.55,402093.21,NULL,NULL),(105,'ENG','SKilled','Engineer',23310687.78,134743.86,0.00,10429.17,5389.75,11224.16,0.00,0.00,24698.55,0.00,590.88,52332.52,187076.38,327383.67,NULL,NULL),(106,'JEN','SKilled','Jnr Engineer',18725656.67,108240.79,0.00,8377.84,4329.63,9016.46,0.00,0.00,19840.54,0.00,590.88,42155.34,150396.13,263193.23,NULL,NULL),(107,'DRAFTR','SKilled','Drafter/Designer',12856131.70,74312.90,0.00,5751.82,2972.52,6190.26,0.00,0.00,13621.55,0.00,590.88,29127.03,103439.93,181019.88,NULL,NULL),(108,'ADM1','SKilled','Administrator/Document Control',6384000.00,36901.73,0.00,2856.19,1476.07,3073.91,0.00,0.00,6764.09,0.00,590.88,14761.15,51662.88,90410.04,NULL,NULL),(109,'QCINPS','Skilled','QA/QC',18725656.67,108240.79,0.00,8377.84,4329.63,9016.46,0.00,0.00,19840.54,0.00,590.88,42155.34,150396.13,263193.23,NULL,NULL),(110,'FINS','Skilled','Field Inspector',15029743.49,86877.13,0.00,6724.29,3475.09,7236.86,0.00,0.00,15924.58,0.00,590.88,33951.70,120828.83,211450.45,NULL,NULL),(111,'PRJCONT','Skilled','Project Controller',12856129.97,74312.89,0.00,5751.82,2972.52,6190.26,0.00,0.00,13621.55,0.00,590.88,29127.03,103439.92,181019.86,NULL,NULL),(112,'PLCPRGR','Skilled','PLC Programmer',22500000.00,130057.80,0.00,10066.47,5202.31,10833.82,0.00,0.00,23839.60,0.00,590.88,50533.08,180590.88,316034.04,NULL,NULL);
/*!40000 ALTER TABLE `man_powers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `materials`
--

DROP TABLE IF EXISTS `materials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `materials` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rate` decimal(9,2) NOT NULL,
  `material_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remark` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `base_unit_of_measure` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `average_unit_price` decimal(9,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `materials_code_unique` (`code`),
  UNIQUE KEY `materials_material_number_unique` (`material_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `materials`
--

LOCK TABLES `materials` WRITE;
/*!40000 ALTER TABLE `materials` DISABLE KEYS */;
/*!40000 ALTER TABLE `materials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `materials_category`
--

DROP TABLE IF EXISTS `materials_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `materials_category` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `materials_category`
--

LOCK TABLES `materials_category` WRITE;
/*!40000 ALTER TABLE `materials_category` DISABLE KEYS */;
INSERT INTO `materials_category` VALUES (1,'M010  ','Concrete and mortar',NULL,NULL),(2,'M020  ','Reinforcing bar',NULL,NULL),(3,'M030  ','Timber, plywood and wall covering',NULL,NULL),(4,'M040  ','Ceiling and Wall finishing',NULL,NULL),(5,'M050  ','Floor Finishing',NULL,NULL),(6,'M060  ','Light steel and accessories',NULL,NULL),(7,'M070  ','Structural steel and fabrication',NULL,NULL),(8,'M080  ','Sand, rock, gravel and backfil materials',NULL,NULL),(9,'M090  ','Nail, glue and sealant',NULL,NULL),(10,'M100  ','Glass, window, door and accessories',NULL,NULL),(11,'M110  ','Roofing and purlin',NULL,NULL),(12,'M115  ','Fence and accessories',NULL,NULL),(13,'M120  ','Prefabricated Building',NULL,NULL),(14,'M125  ','Office Furniture',NULL,NULL),(15,'M130  ','Painting',NULL,NULL),(16,'M140  ','Steel pipe, duct and accessories',NULL,NULL),(17,'M150  ','HDPE Pipe and accessories',NULL,NULL),(18,'M160  ','Plastic Pipes and accessories',NULL,NULL),(19,'M170  ','Heating, ventilation and air conditioning',NULL,NULL),(20,'M175  ','Kitchen, Toilet, Washbasin and Acessories',NULL,NULL),(21,'M180  ','Fire Protection',NULL,NULL),(22,'M190  ','Mechanical fastener',NULL,NULL),(23,'M195  ','Lifting Equipment and Acessories',NULL,NULL),(24,'M200  ','Pump and accessories',NULL,NULL),(25,'M210  ','Valve and accessories',NULL,NULL),(26,'M212  ','Tank and Pressure Vessel and Acessories',NULL,NULL),(27,'M217  ','Conveyor and Acessories',NULL,NULL),(28,'M219  ','Other M/C Material',NULL,NULL),(29,'M220  ','Transformers',NULL,NULL),(30,'M230  ','MCC, Switchgear and VSD',NULL,NULL),(31,'M240  ','Cables and Conduit',NULL,NULL),(32,'M250  ','Cable Fitting',NULL,NULL),(33,'M260  ','Power panel',NULL,NULL),(34,'M270  ','Relay, CB and Contactor',NULL,NULL),(35,'M280  ','Junction box',NULL,NULL),(36,'M285  ','Terminal Block',NULL,NULL),(37,'M290  ','Receptacle, toggle switch',NULL,NULL),(38,'M300  ','Lighting',NULL,NULL),(39,'M310  ','Tape and insulation',NULL,NULL),(40,'M320  ','Fire Alarm system',NULL,NULL),(41,'M330  ','UPS, DC power supply, and Batteries',NULL,NULL),(42,'M340  ','PLC',NULL,NULL),(43,'M350  ','IT and Network',NULL,NULL),(44,'M360  ','Grounding',NULL,NULL),(45,'M370  ','Cable Tray',NULL,NULL),(46,'M380  ','Valve and Actuator',NULL,NULL),(47,'M390  ','Transmitter and Gauge',NULL,NULL),(48,'MTI068 ','MTI068',NULL,NULL),(49,'M400  ','Sensor and Switch',NULL,NULL),(50,'MTI071 ','MTI071',NULL,NULL),(51,'MTI073 ','MTI073',NULL,NULL),(52,'MTI069 ','MTI069',NULL,NULL),(53,'MTI076 ','MTI076',NULL,NULL),(54,'MTI077 ','MTI077',NULL,NULL),(55,'M410  ','Others  E/I',NULL,NULL),(56,'M420  ','FO and Accecories',NULL,NULL),(57,'M430  ','LAN & Accecories',NULL,NULL),(58,'M500  ','Perjalanan dan Akomodasi',NULL,NULL),(59,'M501  ','Lightning Protection',NULL,NULL),(60,'M502  ','Solar Power Supply',NULL,NULL);
/*!40000 ALTER TABLE `materials_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2014_10_12_200000_add_two_factor_columns_to_users_table',1),(4,'2019_08_19_000000_create_failed_jobs_table',1),(5,'2019_12_14_000001_create_personal_access_tokens_table',1),(6,'2022_05_27_015936_create_sessions_table',1),(7,'2022_05_27_021859_create_project_info_table',1),(8,'2022_05_27_045832_create_employee_table',1),(9,'2022_06_02_024736_create_estimate_all_disciplines_table',1),(10,'2022_06_02_042940_create_table_estimate_all_discipline_project',1),(11,'2022_06_02_052404_create_work_items_table',1),(12,'2022_06_02_064504_create_man_powers_table',1),(13,'2022_06_02_070911_create_materials_table',1),(14,'2022_06_02_072055_create_tools_table',1),(15,'2022_06_02_072618_create_work_item_type_table',1),(16,'2022_06_02_072913_create_settings_table',1),(17,'2022_06_03_084719_create_discipline_work_type_table',1),(18,'2022_07_26_011410_create_table_equipment_tools_category',1),(19,'2022_07_26_022951_create_equipment_tools_table',1),(20,'2022_08_02_015029_create_materials_category_table',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
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
-- Table structure for table `project_info`
--

DROP TABLE IF EXISTS `project_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project_info` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `project_title` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `sub_project_title` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `project_sponsor` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `project_manager` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `project_engineer` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `design_engineer_mechanical` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `design_engineer_civil` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `design_engineer_electrical` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `design_engineer_instrument` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_info`
--

LOCK TABLES `project_info` WRITE;
/*!40000 ALTER TABLE `project_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `project_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payload` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL,
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
INSERT INTO `sessions` VALUES ('AdtYFtwjz6HntJu9zF6V1li4AGCKfgEHqibdZJyP',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/103.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiY2ZTTEVpOUNQQ0VDVk8xYmJKU2lFMGJYU3V0SXFNdWo2Umd5NmR2QyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9sb2NhbGhvc3Q6ODA4MS9jb3N0LWVzdGltYXRlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjIxOiJwYXNzd29yZF9oYXNoX3NhbmN0dW0iO3M6NjA6IiQyeSQxMCRyUEs0ajNRbmRVcGJnVjhmd1pPb0d1TDN6THZWZjZ1MENUSzUxOWhaTC9PTWxXTE8xeVVWbSI7fQ==',1659332505),('nWvUExkqdP3iFUOEVErXFnwvVCpwzc2ptWI4tfBU',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/103.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiYVlPYnl4VUJlTURUclhRbmJPVUFFdkxiRGtsdXFBcUswdlI3OVFCQyI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjY1OiJodHRwOi8vbG9jYWxob3N0OjgwODEvY29zdC1lc3RpbWF0ZS8xMi9lc3RpbWF0ZS1kaXNjaXBsaW5lL2NyZWF0ZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==',1659333558),('Rtrk2kNx44Hqq6UOk5JkOWm8io4k5csanTR4tHMb',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/103.0.0.0 Safari/537.36','YToyOntzOjY6Il90b2tlbiI7czo0MDoiNHFEWTJxWmZpRDB3WEQzQ3QyUDQ4aWhGSVR1ZnN2ZGF5d1kxRFRCeiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1659401118),('WAvOGBpxmeuD81PFKbzug8xwdlEqDPDq4s3QfiJJ',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/103.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoid1N4QVk4a1E4Mk50NnR5eFRKdGFHSUtIRzNFMGRBNnFmdmxLcGFrUyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjIxOiJwYXNzd29yZF9oYXNoX3NhbmN0dW0iO3M6NjA6IiQyeSQxMCRyUEs0ajNRbmRVcGJnVjhmd1pPb0d1TDN6THZWZjZ1MENUSzUxOWhaTC9PTWxXTE8xeVVWbSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODA4MS9kYXNoYm9hcmQiO319',1659401225);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `setting_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `setting_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `setting_value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `setting_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tools`
--

DROP TABLE IF EXISTS `tools`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tools` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `local_rate` decimal(9,2) NOT NULL,
  `national_rate` decimal(9,2) NOT NULL,
  `remark` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tools_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tools`
--

LOCK TABLES `tools` WRITE;
/*!40000 ALTER TABLE `tools` DISABLE KEYS */;
/*!40000 ALTER TABLE `tools` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `two_factor_secret` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `two_factor_recovery_codes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_team_id` bigint(20) unsigned DEFAULT NULL,
  `profile_photo_path` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
INSERT INTO `users` VALUES (1,'Al Riefqy Dasmito','al@gmail.com',NULL,'$2y$10$rPK4j3QndUpbgV8fwZOoGuL3zLvVf6u0CTK519hZL/OMlWLO1yUVm',NULL,NULL,NULL,NULL,NULL,NULL,'2022-07-25 23:42:35','2022-07-25 23:42:35');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `work_item_type`
--

DROP TABLE IF EXISTS `work_item_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `work_item_type` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `work_item_type_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `work_item_type`
--

LOCK TABLES `work_item_type` WRITE;
/*!40000 ALTER TABLE `work_item_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `work_item_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `work_items`
--

DROP TABLE IF EXISTS `work_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `work_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `work_item_type_id` int(11) NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `volume` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total` decimal(20,2) NOT NULL,
  `created_by` int(11) NOT NULL,
  `reviewed_by` int(11) NOT NULL,
  `labor_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `tools_equipment_id` int(11) NOT NULL,
  `amount_material` decimal(20,2) NOT NULL,
  `amount_tools_equipment` decimal(20,2) NOT NULL,
  `amount_labor` decimal(20,2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `work_items_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `work_items`
--

LOCK TABLES `work_items` WRITE;
/*!40000 ALTER TABLE `work_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `work_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'project'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-08-02 14:02:44
