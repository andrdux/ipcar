USE `ipcar`;

LOCK TABLES `kpp` WRITE;
/*!40000 ALTER TABLE `kpp` DISABLE KEYS */;
INSERT INTO `kpp` VALUES (1,0,'Механическая',0),(2,1,'Механическая 4-ступ',1),(3,1,'Механическая 5-ступ',2),(4,1,'Механическая 6-ступ',3),(5,0,'Автомат',4),(6,5,'Типтроник',5),(7,5,'Стептроник',6),(8,0,'Полуавтомат',7),(9,0,'Вариатор',8);
/*!40000 ALTER TABLE `kpp` ENABLE KEYS */;
UNLOCK TABLES;