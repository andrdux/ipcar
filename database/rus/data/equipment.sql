USE `ipcar`;

LOCK TABLES `equipment` WRITE;
/*!40000 ALTER TABLE `equipment` DISABLE KEYS */;
INSERT INTO `equipment` VALUES (1,'ABS',0),(2,'Airbag д/водителя',1),(3,'Airbag д/пассажира',2),(4,'Airbag боковые',3),(5,'Airbag оконные',4),(6,'Break assist',5),(7,'CD-чейнджер',6),(8,'EBD',7),(9,'Handsfree',8),(10,'Авт. упр. светом',9),(11,'Антипробуксовочная система',10),(12,'Аудиоподготовка',11),(13,'Багажник на крыше',12),(14,'Блокировка заднего диф.',13),(15,'Бортовой компьютер',14),(16,'ГБО',15),(17,'ГУР',16),(18,'Датчик дождя',17),(19,'Д/о бензобака',18),(20,'Иммобилайзер',19),(21,'Катализатор',20),(22,'Климат-контроль',21),(23,'Кондиционер',22),(24,'Корректор фар',23),(25,'Круиз-контроль',24),(26,'Ксеноновые фары',25),(27,'Лебедка',26),(28,'Легкосплавные диски',27),(29,'Люк',28),(30,'Магнитола',29),(31,'Навигационная система',30),(32,'Обогрев зеркал',31),(33,'Обогрев сидений',32),(34,'Омыватель фар',33),(35,'Отделка под дерево',34),(36,'Парктроник',35),(37,'Подлокотник передний',36),(38,'Противотуманные фары',37),(39,'Разд. спинка задн. сидений',38),(40,'Регул. сид. вод. по высоте',39),(41,'Регул. сид. пасс. по высоте',40),(42,'Регулировка руля',41),(43,'Сигнализация',42),(44,'Сотовый телефон',43),(45,'Тонированные стекла',44),(46,'Фаркоп',45),(47,'Центральный замок',46),(48,'Электроантенна',47),(49,'Электропривод вод. сиденья',48),(50,'Электропривод пасс. сиденья',49),(51,'Электростекла',50),(52,'Электрозеркала',51);
/*!40000 ALTER TABLE `equipment` ENABLE KEYS */;
UNLOCK TABLES;