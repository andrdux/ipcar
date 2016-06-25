USE `ipcar`;

LOCK TABLES `fuel` WRITE;
/*!40000 ALTER TABLE `fuel` DISABLE KEYS */;
INSERT INTO `fuel` VALUES (1,0,0,'Бензин',0),(2,9,0,'Газ метан',2),(3,9,0,'Газ пропан-бутан',3),(4,1,9,'Газ / бензин',4),(5,0,0,'Дизель',5),(6,0,0,'Гибрид',6),(7,0,0,'Водород',7),(8,0,0,'Электро',8),(9,0,0,'Газ',1);
/*!40000 ALTER TABLE `fuel` ENABLE KEYS */;
UNLOCK TABLES;
