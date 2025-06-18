
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



CREATE TABLE `sessoes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `token_sessao` varchar(64) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `pais` varchar(100) DEFAULT NULL,
  `estado` varchar(100) DEFAULT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `dispositivo` varchar(100) DEFAULT NULL,
  `sistema_operacional` varchar(100) DEFAULT NULL,
  `navegador` varchar(100) DEFAULT NULL,
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_ultimo_acesso` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `esta_atual` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `token_sessao` (`token_sessao`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `sessoes_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
