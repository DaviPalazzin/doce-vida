CREATE TABLE `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `senha` varchar(100) NOT NULL,
  `reset_token` varchar(100) DEFAULT NULL,
  `reset_token_expira` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `usuarios` WRITE;

INSERT INTO `usuarios` VALUES 
(1,'admin',NULL,'admin',NULL,NULL),
(22,'teste','teste@ixeoxnph.mailosaur.net','$2y$10$MGHBj3NdoVaDz2U0QaxrZuAfiBN6.gs/hoYRegxaQ6Oy4PR0ODrpW',NULL,NULL),
(23,'davi',NULL,'$2y$10$JjtLP5x8CV9/DTEyHphlWubdIKLa7ePR7d2/9H7ABgPro9xf7.t2a',NULL,NULL);

UNLOCK TABLES;
