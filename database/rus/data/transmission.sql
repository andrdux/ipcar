USE `ipcar`;

LOCK TABLES `transmission` WRITE;
/*!40000 ALTER TABLE `transmission` DISABLE KEYS */;
INSERT INTO `transmission` VALUES (1,'Передний',0),(2,'Задний',1),(3,'Полный',2);
/*!40000 ALTER TABLE `transmission` ENABLE KEYS */;
UNLOCK TABLES;
