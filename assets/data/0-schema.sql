CREATE TABLE `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `senha` varchar(100) NOT NULL,
  `reset_token` varchar(100) DEFAULT NULL,
  `reset_token_expira` datetime DEFAULT NULL,
  `delete_token` varchar(100) DEFAULT NULL,
  `delete_token_expira` datetime DEFAULT NULL,
  `email_verificado` tinyint(1) DEFAULT '0',
  `codigo_verificacao` varchar(6) DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `sexo` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `usuarios` VALUES 
(1,'admin',NULL,'admin',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL),
(22,'teste','teste@ixeoxnph.mailosaur.net','$2y$10$MGHBj3NdoVaDz2U0QaxrZuAfiBN6.gs/hoYRegxaQ6Oy4PR0ODrpW',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL),
(23,'davi',NULL,'$2y$10$JjtLP5x8CV9/DTEyHphlWubdIKLa7ePR7d2/9H7ABgPro9xf7.t2a',NULL,NULL,NULL,NULL,0,NULL,NULL,NULL),
(24,'Davi','davipalazzin14@gmail.com','$2y$10$mxymZEP2CByB59IRtEJPKuANg.crvNMp5A06UE9QGbiBwqR1taqz.',NULL,NULL,'427100','2025-06-17 23:51:10',1,NULL,'2006-06-30','feminino');
