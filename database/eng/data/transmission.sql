USE `ipcar`;

LOCK TABLES `transmission` WRITE;
/*!40000 ALTER TABLE `transmission` DISABLE KEYS */;
INSERT INTO `transmission` VALUES (1,'Front-Wheel Drive',0),(2,'Rear-Wheel Drive',1),(3,'Four-Wheel Drive',2);
/*!40000 ALTER TABLE `transmission` ENABLE KEYS */;
UNLOCK TABLES;
