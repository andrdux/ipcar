USE `ipcar`;

LOCK TABLES `fuel` WRITE;
/*!40000 ALTER TABLE `fuel` DISABLE KEYS */;
INSERT INTO `fuel` VALUES (1,0,0,'Petrol',0),(2,9,0,'Methane gas',2),(3,9,0,'Propane-butane gas',3),(4,1,9,'Gas / Petrol',4),(5,0,0,'Diesel',5),(6,0,0,'Hybrid',6),(7,0,0,'Hydrogen',7),(8,0,0,'Electro',8),(9,0,0,'Gas',1);
/*!40000 ALTER TABLE `fuel` ENABLE KEYS */;
UNLOCK TABLES;
