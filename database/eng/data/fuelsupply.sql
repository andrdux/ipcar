USE `ipcar`;

LOCK TABLES `fuelsupply` WRITE;
/*!40000 ALTER TABLE `fuelsupply` DISABLE KEYS */;
INSERT INTO `fuelsupply` VALUES (1,0,'Carburetor',0),(2,1,'Monoinjection',1),(3,1,'Multi-Point Injection',2),(4,1,'Direct Injection',3),(5,0,'Diesel',4),(6,5,'Diesel vs Direct Injection',5);
/*!40000 ALTER TABLE `fuelsupply` ENABLE KEYS */;
UNLOCK TABLES;
