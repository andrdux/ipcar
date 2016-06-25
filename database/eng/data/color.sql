USE `ipcar`;

LOCK TABLES `color` WRITE;
/*!40000 ALTER TABLE `color` DISABLE KEYS */;
INSERT INTO `color` VALUES (1,'Beige',0),(2,'Beige Metallic',1),(3,'White',2),(4,'White Metallic',3),(5,'Sky Blue',4),(6,'Sky Blue Metallic',5),(7,'Yellow',6),(8,'Yellow Metallic',7),(9,'Green',8),(10,'Green Metallic',9),(11,'Brown',10),(12,'Brown Metallic',11),(13,'Red',12),(14,'Red Metallic',13),(15,'Orange',14),(16,'Orange Metallic',15),(17,'Purple',16),(18,'Purple Metallic',17),(19,'Silver',18),(20,'Silver Metallic',19),(21,'Gray',20),(22,'Gray Metallic',21),(23,'Blue',22),(24,'Blue Metallic',23),(25,'Violet',24),(26,'Violet Metallic',25),(27,'Black',26),(28,'Black Metallic',27);
/*!40000 ALTER TABLE `color` ENABLE KEYS */;
UNLOCK TABLES;
