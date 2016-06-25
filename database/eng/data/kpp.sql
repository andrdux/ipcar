USE `ipcar`;

LOCK TABLES `kpp` WRITE;
/*!40000 ALTER TABLE `kpp` DISABLE KEYS */;
INSERT INTO `kpp` VALUES (1,0,'Manual',0),(2,1,'Manual 4-steps',1),(3,1,'Manual 5-steps',2),(4,1,'Manual 6-steps',3),(5,0,'Automatic',4),(6,5,'Tiptronic',5),(7,5,'Steptronic',6),(8,0,'Semiautomatic',7),(9,0,'Variator',8);
/*!40000 ALTER TABLE `kpp` ENABLE KEYS */;
UNLOCK TABLES;
