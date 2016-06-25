USE `ipcar`;

LOCK TABLES `fuelsupply` WRITE;
/*!40000 ALTER TABLE `fuelsupply` DISABLE KEYS */;
INSERT INTO `fuelsupply` VALUES (1,0,'Карбюратор',0),(2,1,'Моновпрыск',1),(3,1,'Распределенный впрыск',2),(4,1,'Непосредственный впрыск',3),(5,0,'Дизель',4),(6,5,'Дизель с непосредственным впрыском',5);
/*!40000 ALTER TABLE `fuelsupply` ENABLE KEYS */;
UNLOCK TABLES;
