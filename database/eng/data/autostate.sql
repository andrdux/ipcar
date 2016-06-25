USE `ipcar`;

LOCK TABLES `autostate` WRITE;
/*!40000 ALTER TABLE `autostate` DISABLE KEYS */;
INSERT INTO `autostate` VALUES (1,0,'Ideal',0),(2,0,'Good',1),(3,0,'Need Repair',2),(4,0,'After an Accident',3),(5,0,'For Parts',4);
/*!40000 ALTER TABLE `autostate` ENABLE KEYS */;
UNLOCK TABLES;
