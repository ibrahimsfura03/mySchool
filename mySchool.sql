CREATE DATABASE  IF NOT EXISTS `myschool` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `myschool`;
-- MySQL dump 10.13  Distrib 8.0.40, for Win64 (x86_64)
--
-- Host: localhost    Database: myschool
-- ------------------------------------------------------
-- Server version	9.1.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin` (
  `admin_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(127) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `fname` varchar(127) COLLATE utf8mb4_general_ci NOT NULL,
  `lname` varchar(127) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'ibrahimsfura','$2y$10$TFyV000I6jNwmDYAt7x.R.iwtm27DSdIWYIYUIVDaTqaDgZ8yWAnC','Ibrahim','Suleiman'),(2,'hudu','$2y$10$K2rxSuBc98GlQ3UdNF9fBueUe7L9rF0eEz/a3VnO19IKnIxiNsEwy','Hudu','Nuhu'),(3,'umar','$2y$10$3e3AdvfpMM.h4v4ZUaXxQOmAA/7sVhVL4BL6WqIq9BbayUATmh702','umar','umar');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `class`
--

DROP TABLE IF EXISTS `class`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `class` (
  `class_id` int NOT NULL AUTO_INCREMENT,
  `department` int DEFAULT NULL,
  PRIMARY KEY (`class_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `class`
--

LOCK TABLES `class` WRITE;
/*!40000 ALTER TABLE `class` DISABLE KEYS */;
/*!40000 ALTER TABLE `class` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `departments` (
  `department_id` int NOT NULL AUTO_INCREMENT,
  `department` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `faculty` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`department_id`),
  UNIQUE KEY `department_id` (`department_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` VALUES (1,'Software Engineering','Engineering'),(2,'Computer Science','Computing'),(3,'Geography','Science'),(4,'Political Science','SMS'),(5,'Buss Admin','Business'),(6,'Civil Engineering','Engineering'),(7,'Islamic Dept','SMS');
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lecturer_classes`
--

DROP TABLE IF EXISTS `lecturer_classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lecturer_classes` (
  `lecturer_id` int NOT NULL,
  `class_id` int NOT NULL,
  PRIMARY KEY (`lecturer_id`,`class_id`),
  KEY `fk_lecturer_classes_dept` (`class_id`),
  CONSTRAINT `fk_lecturer_classes_dept` FOREIGN KEY (`class_id`) REFERENCES `departments` (`department_id`) ON DELETE CASCADE,
  CONSTRAINT `lecturer_classes_ibfk_1` FOREIGN KEY (`lecturer_id`) REFERENCES `lecturers` (`lecturer_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lecturer_classes`
--

LOCK TABLES `lecturer_classes` WRITE;
/*!40000 ALTER TABLE `lecturer_classes` DISABLE KEYS */;
INSERT INTO `lecturer_classes` VALUES (1,1),(5,2);
/*!40000 ALTER TABLE `lecturer_classes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lecturer_levels`
--

DROP TABLE IF EXISTS `lecturer_levels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lecturer_levels` (
  `id` int NOT NULL AUTO_INCREMENT,
  `lecturer_id` int NOT NULL,
  `level` enum('ONE','TWO','THREE','FOUR') COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `lecturer_id` (`lecturer_id`),
  CONSTRAINT `lecturer_levels_ibfk_1` FOREIGN KEY (`lecturer_id`) REFERENCES `lecturers` (`lecturer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lecturer_levels`
--

LOCK TABLES `lecturer_levels` WRITE;
/*!40000 ALTER TABLE `lecturer_levels` DISABLE KEYS */;
INSERT INTO `lecturer_levels` VALUES (1,1,'ONE'),(2,1,'THREE'),(23,5,'ONE'),(24,5,'TWO');
/*!40000 ALTER TABLE `lecturer_levels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lecturer_subjects`
--

DROP TABLE IF EXISTS `lecturer_subjects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lecturer_subjects` (
  `lecturer_id` int NOT NULL,
  `subject_id` int NOT NULL,
  PRIMARY KEY (`lecturer_id`,`subject_id`),
  KEY `subject_id` (`subject_id`),
  CONSTRAINT `lecturer_subjects_ibfk_1` FOREIGN KEY (`lecturer_id`) REFERENCES `lecturers` (`lecturer_id`) ON DELETE CASCADE,
  CONSTRAINT `lecturer_subjects_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lecturer_subjects`
--

LOCK TABLES `lecturer_subjects` WRITE;
/*!40000 ALTER TABLE `lecturer_subjects` DISABLE KEYS */;
INSERT INTO `lecturer_subjects` VALUES (1,1),(5,4);
/*!40000 ALTER TABLE `lecturer_subjects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lecturers`
--

DROP TABLE IF EXISTS `lecturers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lecturers` (
  `lecturer_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(127) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `class` varchar(31) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fname` varchar(127) COLLATE utf8mb4_general_ci NOT NULL,
  `lname` varchar(127) COLLATE utf8mb4_general_ci NOT NULL,
  `subject_id` int DEFAULT NULL,
  `address` varchar(31) COLLATE utf8mb4_general_ci NOT NULL,
  `employee_number` int NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `phone_number` varchar(31) COLLATE utf8mb4_general_ci NOT NULL,
  `qualification` varchar(127) COLLATE utf8mb4_general_ci NOT NULL,
  `gender` varchar(7) COLLATE utf8mb4_general_ci NOT NULL,
  `email_address` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `date_of_joined` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `department_id` int DEFAULT NULL,
  PRIMARY KEY (`lecturer_id`),
  UNIQUE KEY `username` (`username`),
  KEY `fk_lecturer_subject` (`subject_id`),
  CONSTRAINT `fk_lecturer_subject` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lecturers`
--

LOCK TABLES `lecturers` WRITE;
/*!40000 ALTER TABLE `lecturers` DISABLE KEYS */;
INSERT INTO `lecturers` VALUES (1,'ibrahimsfura','$2y$10$084/7r4eHnP4mOi.xxyebOruBoXQZEQ6oncRUib7LLeIstgeX0q02',NULL,'Ibrahim','Suleiman',NULL,'sardauna',1002,'2025-04-17','8033698607','Full stack software developer','Male','ibrahimsfura03@gmail.com','2025-05-06 11:13:29',NULL),(5,'umar','$2y$10$ZMOV8TS6eEvc35hEZ/CboeEz3W1p0fWOEna6eUuj31NHGhlw9iUde',NULL,'umar','umar',NULL,'sardauna',2,'2025-06-04','8033698607','Full stack software developer','Male','umar@umar.com','2025-06-22 21:38:57',NULL);
/*!40000 ALTER TABLE `lecturers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `section`
--

DROP TABLE IF EXISTS `section`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `section` (
  `section_id` int NOT NULL AUTO_INCREMENT,
  `section_name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `class_id` int NOT NULL,
  PRIMARY KEY (`section_id`),
  KEY `class_id` (`class_id`),
  CONSTRAINT `section_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `class` (`class_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `section`
--

LOCK TABLES `section` WRITE;
/*!40000 ALTER TABLE `section` DISABLE KEYS */;
/*!40000 ALTER TABLE `section` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `setting`
--

DROP TABLE IF EXISTS `setting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `setting` (
  `id` int NOT NULL AUTO_INCREMENT,
  `current_year` int NOT NULL,
  `current_semester` varchar(11) COLLATE utf8mb4_general_ci NOT NULL,
  `school_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `slogan` varchar(300) COLLATE utf8mb4_general_ci NOT NULL,
  `about` text COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `setting`
--

LOCK TABLES `setting` WRITE;
/*!40000 ALTER TABLE `setting` DISABLE KEYS */;
INSERT INTO `setting` VALUES (1,2025,'II','mySchool','Knowledge is light','Siuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuu');
/*!40000 ALTER TABLE `setting` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_score`
--

DROP TABLE IF EXISTS `student_score`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `student_score` (
  `id` int NOT NULL AUTO_INCREMENT,
  `semester` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `year` int NOT NULL,
  `student_id` int NOT NULL,
  `lecturer_id` int NOT NULL,
  `subject_id` int NOT NULL,
  `results` varchar(512) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_score`
--

LOCK TABLES `student_score` WRITE;
/*!40000 ALTER TABLE `student_score` DISABLE KEYS */;
/*!40000 ALTER TABLE `student_score` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_subject_scores`
--

DROP TABLE IF EXISTS `student_subject_scores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `student_subject_scores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `student_id` int NOT NULL,
  `subject_id` int NOT NULL,
  `first_ca` tinyint DEFAULT NULL,
  `second_ca` tinyint DEFAULT NULL,
  `exam` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ux_student_subject` (`student_id`,`subject_id`),
  KEY `subject_id` (`subject_id`),
  CONSTRAINT `student_subject_scores_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  CONSTRAINT `student_subject_scores_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_subject_scores`
--

LOCK TABLES `student_subject_scores` WRITE;
/*!40000 ALTER TABLE `student_subject_scores` DISABLE KEYS */;
INSERT INTO `student_subject_scores` VALUES (6,1,1,10,10,40,'2025-06-22 22:33:38','2025-06-22 22:33:38'),(8,4,4,10,11,60,'2025-06-22 23:13:57','2025-06-22 23:18:45'),(9,2,4,0,15,43,'2025-06-22 23:19:06','2025-06-22 23:19:06');
/*!40000 ALTER TABLE `student_subject_scores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `students` (
  `student_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(127) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `fname` varchar(127) COLLATE utf8mb4_general_ci NOT NULL,
  `lname` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `department_id` int DEFAULT NULL,
  `level` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cgpa` decimal(4,2) DEFAULT NULL,
  `address` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  `gender` varchar(7) COLLATE utf8mb4_general_ci NOT NULL,
  `email_address` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `date_of_birth` date NOT NULL,
  `date_of_joined` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `parent_fname` varchar(127) COLLATE utf8mb4_general_ci NOT NULL,
  `parent_lname` varchar(127) COLLATE utf8mb4_general_ci NOT NULL,
  `parent_phone_number` varchar(31) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`student_id`),
  UNIQUE KEY `username` (`username`),
  KEY `fk_department` (`department_id`),
  CONSTRAINT `fk_department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `students`
--

LOCK TABLES `students` WRITE;
/*!40000 ALTER TABLE `students` DISABLE KEYS */;
INSERT INTO `students` VALUES (1,'ibrahimsfura','$2y$10$AfXvpfo2bxF58k2E2JLQnuhdxotG0AnZ5iHJqtcbP1FYYvBEea4pC','Ibrahim Suleiman','Adamu Fura',1,'ONE',NULL,'Pantami plato junction, Gombe Gombe State Nigeria','Male','fura.motive@gmail.com','2025-05-29','2025-06-22 21:55:30','Ibrahim Suleiman','Adamu Fura','08033698607'),(2,'umar','$2y$10$TxZSc291olDd/St9pUKFG.D/eD2dOWYkaOCLQENvbUS3qA0QPgptO','umar','umar',2,'ONE',2.90,'sardauna','Male','umar@umar.com','2025-06-09','2025-06-22 22:15:41','umar','umar','8033698607'),(4,'musa','$2y$10$kmQENuVHu35pTLiUAC9BbOOgLn0Vi8euE9XuacVW2lkqoB/dhxsey','musa','musa',2,'TWO',NULL,'estate','Male','musa@musa.com','2025-06-04','2025-06-22 23:12:35','musa','musa','8033698607');
/*!40000 ALTER TABLE `students` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subjects`
--

DROP TABLE IF EXISTS `subjects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subjects` (
  `subject_id` int NOT NULL AUTO_INCREMENT,
  `subject` varchar(31) COLLATE utf8mb4_general_ci NOT NULL,
  `subject_code` varchar(31) COLLATE utf8mb4_general_ci NOT NULL,
  `department` int DEFAULT NULL,
  PRIMARY KEY (`subject_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subjects`
--

LOCK TABLES `subjects` WRITE;
/*!40000 ALTER TABLE `subjects` DISABLE KEYS */;
INSERT INTO `subjects` VALUES (1,'Software Testing','SEN3737',1),(2,'Tourism','SCS7273',3),(3,'Government','POL3728',4),(4,'Operating System','CSC3733',2),(5,'Pythomn','PY2828',2),(6,'Quran','GSP3238',7);
/*!40000 ALTER TABLE `subjects` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-23 21:59:24
