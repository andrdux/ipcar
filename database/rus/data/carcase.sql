USE `ipcar`;

LOCK TABLES `carcase` WRITE;
/*!40000 ALTER TABLE `carcase` DISABLE KEYS */;
INSERT INTO `carcase` VALUES (1,0,'Седан (4 двери)',0),(2,0,'Хетчбэк 4/5 дверей',1),(3,0,'Хетчбэк 2/3 двери',2),(4,0,'Купе (2 двери)',3),(5,0,'Кабриолет / Родстер',4),(6,0,'Универсал',5),(7,0,'Минивэн',6),(8,0,'Внедорожник / Кроссовер',7),(9,0,'Внедорожник-пикап',8),(10,0,'Фургон пассажир',9),(11,0,'Фургон груз-пасс.',10),(12,0,'Фургон / пикап грузовой',11),(13,0,'Микроавтобус пассажир',12),(14,0,'Микроавтобус груз-пасс.',13),(15,0,'Микроавтобус грузовой',14),(16,0,'Автобус (более 18 мест)',15),(17,0,'Шасси',16),(18,0,'Самосвал',17),(19,0,'Тягач',18),(20,0,'Спецтехника',19),(21,0,'Прицеп',20),(22,0,'Мото-транспорт',21),(23,0,'Авиа-транспорт',22),(24,0,'Водный-транспорт',23);
/*!40000 ALTER TABLE `carcase` ENABLE KEYS */;
UNLOCK TABLES;
