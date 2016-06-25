USE `ipcar`;

INSERT INTO `carcase` VALUES (1,0,'Sedan (4 doors)',0),(2,0,'Hatchback 4/5 doors',1),(3,0,'Hatchback 2/3 doors',2),(4,0,'Coupe (2 doors)',3),(5,0,'Convertible / Roadster',4),(6,0,'Touring',5),(7,0,'Minivan',6),(8,0,'SUV / Crossover',7),(9,0,'Pickup',8),(10,0,'Passenger Van',9),(11,0,'Cargo-Passenger Van',10),(12,0,'Truck / Pickup',11),(13,0,'Passenger Minibus',12),(14,0,'Passenger-Cargo Minibus',13),(15,0,'Cargo Minibus',14),(16,0,'Bus (over 18 seats)',15),(17,0,'Chassis',16),(18,0,'Tipper',17),(19,0,'Tractor',18),(20,0,'Special',19),(21,0,'Trailer',20),(22,0,'Motorcycle',21),(23,0,'Air Transport',22),(24,0,'Water Transport',23);
LOCK TABLES `carcase` WRITE;
/*!40000 ALTER TABLE `carcase` DISABLE KEYS */;
/*!40000 ALTER TABLE `carcase` ENABLE KEYS */;
UNLOCK TABLES;
