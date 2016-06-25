USE `ipcar`;

LOCK TABLES `autostate` WRITE;
/*!40000 ALTER TABLE `autostate` DISABLE KEYS */;
INSERT INTO `autostate` VALUES (1,0,'Идеальное',0),(2,0,'Хорошее',1),(3,0,'Требует ремонта',2),(4,0,'После ДТП',3),(5,0,'На запчасти',4);
/*!40000 ALTER TABLE `autostate` ENABLE KEYS */;
UNLOCK TABLES;
