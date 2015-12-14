-- MySQL dump 10.13  Distrib 5.5.29, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: escola
-- ------------------------------------------------------
-- Server version	5.5.29-0ubuntu0.12.10.1

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
-- Table structure for table `aluno_responsavel`
--

DROP TABLE IF EXISTS `aluno_responsavel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aluno_responsavel` (
  `id_aluno` int(11) NOT NULL,
  `id_responsavel` int(11) NOT NULL,
  `principal` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_aluno`,`id_responsavel`),
  KEY `id_responsavel` (`id_responsavel`),
  CONSTRAINT `aluno_responsavel_ibfk_1` FOREIGN KEY (`id_aluno`) REFERENCES `pessoa` (`id_pessoa`),
  CONSTRAINT `aluno_responsavel_ibfk_2` FOREIGN KEY (`id_responsavel`) REFERENCES `pessoa` (`id_pessoa`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `aluno_responsavel`
--

LOCK TABLES `aluno_responsavel` WRITE;
/*!40000 ALTER TABLE `aluno_responsavel` DISABLE KEYS */;
INSERT INTO `aluno_responsavel` VALUES (19,20,0),(19,22,0),(23,20,1),(24,20,1),(25,20,1),(27,22,0);
/*!40000 ALTER TABLE `aluno_responsavel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `curso`
--

DROP TABLE IF EXISTS `curso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `curso` (
  `id_curso` int(11) NOT NULL AUTO_INCREMENT,
  `id_escola` int(11) NOT NULL,
  `data_inclusao` datetime NOT NULL,
  `ultima_alteracao` datetime NOT NULL,
  `nome` varchar(30) NOT NULL,
  `cod_situacao` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_curso`),
  KEY `id_escola` (`id_escola`),
  CONSTRAINT `curso_ibfk_1` FOREIGN KEY (`id_escola`) REFERENCES `escola` (`id_escola`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `curso`
--

LOCK TABLES `curso` WRITE;
/*!40000 ALTER TABLE `curso` DISABLE KEYS */;
INSERT INTO `curso` VALUES (1,1,'2015-12-10 17:40:40','2015-12-14 10:15:30','4 ano',1),(3,1,'2015-12-10 17:54:58','2015-12-14 11:48:44','1 ano',1),(4,1,'2015-12-11 08:58:02','2015-12-14 10:15:12','2 ano',1),(5,1,'2015-12-11 09:05:53','2015-12-14 10:15:20','3 ano',1),(6,1,'2015-12-14 11:48:55','2015-12-14 11:48:55','5 ano',1);
/*!40000 ALTER TABLE `curso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `escola`
--

DROP TABLE IF EXISTS `escola`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `escola` (
  `id_escola` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `cod_situacao` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_escola`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `escola`
--

LOCK TABLES `escola` WRITE;
/*!40000 ALTER TABLE `escola` DISABLE KEYS */;
INSERT INTO `escola` VALUES (1,'Escola Bem Me Quer',1);
/*!40000 ALTER TABLE `escola` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `movimento`
--

DROP TABLE IF EXISTS `movimento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `movimento` (
  `id_movimento` int(11) NOT NULL AUTO_INCREMENT,
  `id_tipo` int(11) NOT NULL,
  `id_escola` int(11) NOT NULL,
  `id_pessoa` int(11) NOT NULL,
  `id_aluno` int(11) DEFAULT NULL,
  `tipo` char(1) NOT NULL DEFAULT 'c',
  `data_inclusao` datetime NOT NULL,
  `ultima_alteracao` datetime NOT NULL,
  `data_vencimento` datetime NOT NULL,
  `credito` double DEFAULT NULL,
  `debito` double DEFAULT NULL,
  `cod_situacao` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_movimento`),
  KEY `id_tipo` (`id_tipo`),
  KEY `id_escola` (`id_escola`),
  KEY `id_pessoa` (`id_pessoa`),
  KEY `id_aluno` (`id_aluno`),
  CONSTRAINT `movimento_ibfk_1` FOREIGN KEY (`id_tipo`) REFERENCES `movimento_tipo` (`id_tipo`),
  CONSTRAINT `movimento_ibfk_2` FOREIGN KEY (`id_escola`) REFERENCES `escola` (`id_escola`),
  CONSTRAINT `movimento_ibfk_3` FOREIGN KEY (`id_pessoa`) REFERENCES `pessoa` (`id_pessoa`),
  CONSTRAINT `movimento_ibfk_4` FOREIGN KEY (`id_aluno`) REFERENCES `pessoa` (`id_pessoa`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `movimento`
--

LOCK TABLES `movimento` WRITE;
/*!40000 ALTER TABLE `movimento` DISABLE KEYS */;
/*!40000 ALTER TABLE `movimento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `movimento_tipo`
--

DROP TABLE IF EXISTS `movimento_tipo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `movimento_tipo` (
  `id_tipo` int(11) NOT NULL AUTO_INCREMENT,
  `id_escola` int(11) NOT NULL,
  `nome` varchar(40) NOT NULL,
  `cod_situacao` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_tipo`),
  KEY `id_escola` (`id_escola`),
  CONSTRAINT `movimento_tipo_ibfk_1` FOREIGN KEY (`id_escola`) REFERENCES `escola` (`id_escola`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `movimento_tipo`
--

LOCK TABLES `movimento_tipo` WRITE;
/*!40000 ALTER TABLE `movimento_tipo` DISABLE KEYS */;
/*!40000 ALTER TABLE `movimento_tipo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pessoa`
--

DROP TABLE IF EXISTS `pessoa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pessoa` (
  `id_pessoa` int(11) NOT NULL AUTO_INCREMENT,
  `id_escola` int(11) NOT NULL,
  `id_filho` int(11) DEFAULT NULL,
  `id_turma` int(11) DEFAULT NULL,
  `tipo` char(1) NOT NULL DEFAULT 'a',
  `data_inclusao` datetime NOT NULL,
  `ultima_alteracao` datetime NOT NULL,
  `nome` varchar(50) NOT NULL,
  `data_nascimento` datetime DEFAULT NULL,
  `genero` char(1) DEFAULT NULL,
  `telefone1` varchar(15) DEFAULT NULL,
  `telefone2` varchar(15) DEFAULT NULL,
  `telefone3` varchar(15) DEFAULT NULL,
  `telefone4` varchar(15) DEFAULT NULL,
  `email1` varchar(160) DEFAULT NULL,
  `email2` varchar(160) DEFAULT NULL,
  `email3` varchar(160) DEFAULT NULL,
  `email4` varchar(160) DEFAULT NULL,
  `endereco` varchar(60) DEFAULT NULL,
  `complemento` varchar(30) DEFAULT NULL,
  `bairro` varchar(30) DEFAULT NULL,
  `cidade` varchar(30) DEFAULT NULL,
  `uf` char(2) DEFAULT NULL,
  `cod_situacao` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_pessoa`),
  KEY `id_escola` (`id_escola`),
  KEY `id_turma` (`id_turma`),
  KEY `id_filho` (`id_filho`),
  CONSTRAINT `pessoa_ibfk_1` FOREIGN KEY (`id_escola`) REFERENCES `escola` (`id_escola`),
  CONSTRAINT `pessoa_ibfk_2` FOREIGN KEY (`id_turma`) REFERENCES `turma` (`id_turma`),
  CONSTRAINT `pessoa_ibfk_3` FOREIGN KEY (`id_filho`) REFERENCES `pessoa` (`id_pessoa`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pessoa`
--

LOCK TABLES `pessoa` WRITE;
/*!40000 ALTER TABLE `pessoa` DISABLE KEYS */;
INSERT INTO `pessoa` VALUES (19,1,NULL,1,'a','2015-12-11 09:57:53','2015-12-13 01:37:20','Hiram Pessoa Carneiro','2008-05-13 00:00:00','m','6196064051','6133721141',NULL,NULL,NULL,NULL,NULL,NULL,'Rua Joaquim Rodrigues','Casa 4','Centro','Goias','GO',1),(20,1,NULL,4,'r','2015-12-13 01:25:04','2015-12-13 01:42:10','Rodrigo Landim Carneiro','1969-12-31 00:00:00','m','6196064051','6133721141',NULL,NULL,'rodrigo@emagine.com.br',NULL,NULL,NULL,'Rua Joaquim Rodrigues','Casa 4','Centro','Goias','GO',1),(22,1,NULL,NULL,'r','2015-12-13 11:19:35','2015-12-14 10:40:58','Renata Pessoa Landim','1969-12-31 00:00:00','f','6196064051','6133721141',NULL,NULL,'renata.landim@gmail.com',NULL,NULL,NULL,'Rua Joaquim Rodrigues','Casa 4','Centro','Goias','GO',1),(23,1,NULL,4,'a','2015-12-14 10:25:46','2015-12-14 10:26:37','Heitor Pessoa Carneiro','1969-12-31 00:00:00','m','6196064051','6133721141',NULL,NULL,NULL,NULL,NULL,NULL,'Rua Joaquim Rodrigues','Casa 4','Centro','Goias','GO',1),(24,1,NULL,2,'','2015-12-14 10:27:31','2015-12-14 10:27:31','dsadasd','0000-00-00 00:00:00',NULL,'6196064051','6133721141',NULL,NULL,NULL,NULL,NULL,NULL,'Rua Joaquim Rodrigues','Casa 4','Centro','Goias','GO',2),(25,1,NULL,2,'a','2015-12-14 10:32:06','2015-12-14 10:32:06','adasdasd','0000-00-00 00:00:00',NULL,'6196064051','6133721141',NULL,NULL,NULL,NULL,NULL,NULL,'Rua Joaquim Rodrigues','Casa 4','Centro','Goias','GO',2),(26,1,NULL,2,'a','2015-12-14 10:32:27','2015-12-14 10:32:27','asdasdas','0000-00-00 00:00:00','m',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2),(27,1,NULL,2,'a','2015-12-14 11:51:23','2015-12-14 11:51:23','fsdfsdfsd','0000-00-00 00:00:00',NULL,'6196064051','6133721141',NULL,NULL,NULL,NULL,NULL,NULL,'Rua Joaquim Rodrigues','Casa 4','Centro','Goias','GO',2);
/*!40000 ALTER TABLE `pessoa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `turma`
--

DROP TABLE IF EXISTS `turma`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `turma` (
  `id_turma` int(11) NOT NULL AUTO_INCREMENT,
  `id_curso` int(11) NOT NULL,
  `data_inclusao` datetime NOT NULL,
  `ultima_alteracao` datetime NOT NULL,
  `turno` char(1) NOT NULL DEFAULT 'm',
  `nome` varchar(30) NOT NULL,
  `cod_situacao` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_turma`),
  KEY `id_curso` (`id_curso`),
  CONSTRAINT `turma_ibfk_1` FOREIGN KEY (`id_curso`) REFERENCES `curso` (`id_curso`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `turma`
--

LOCK TABLES `turma` WRITE;
/*!40000 ALTER TABLE `turma` DISABLE KEYS */;
INSERT INTO `turma` VALUES (1,3,'2015-12-10 18:49:00','2015-12-14 10:16:09','v','1B',1),(2,3,'2015-12-10 18:55:53','2015-12-14 10:16:00','m','1A',1),(4,3,'2015-12-10 18:58:05','2015-12-14 10:16:16','v','1C',1),(5,4,'2015-12-14 11:49:10','2015-12-14 11:49:10','m','2A',1);
/*!40000 ALTER TABLE `turma` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `id_escola` int(11) NOT NULL,
  `cod_tipo` tinyint(4) NOT NULL,
  `data_inclusao` datetime NOT NULL,
  `ultima_alteracao` datetime NOT NULL,
  `nome` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha` varchar(30) NOT NULL,
  `cod_situacao` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_usuario`),
  KEY `id_escola` (`id_escola`),
  CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_escola`) REFERENCES `escola` (`id_escola`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (1,1,2,'2015-12-03 13:26:45','2015-12-03 17:23:18','Rodrigo Landim','rodrigo@emagine.com.br','pikpro6',1),(3,1,1,'2015-12-03 17:10:07','2015-12-03 17:32:50','Renata Pessoa','renata.landim@gmail.com','1234',1),(4,1,1,'2015-12-14 11:50:17','2015-12-14 11:50:52','Dejanir','dejanir@gmail.com','1234',1);
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-12-14 12:05:53
