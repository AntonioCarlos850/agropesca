-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           10.4.14-MariaDB - mariadb.org binary distribution
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              11.2.0.6213
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Copiando estrutura para tabela agropesca.blg_image
CREATE TABLE IF NOT EXISTS `blg_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(50) DEFAULT NULL,
  `filename` varchar(50) DEFAULT NULL,
  `alt` varchar(50) DEFAULT NULL,
  `creation_date` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Copiando dados para a tabela agropesca.blg_image: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `blg_image` DISABLE KEYS */;
/*!40000 ALTER TABLE `blg_image` ENABLE KEYS */;

-- Copiando estrutura para tabela agropesca.blg_post
CREATE TABLE IF NOT EXISTS `blg_post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) DEFAULT NULL,
  `author_id` int(11) DEFAULT NULL,
  `image_id` int(11) DEFAULT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `active` tinyint(4) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `body` mediumtext DEFAULT NULL,
  `visits` int(11) DEFAULT 0,
  `creation_date` datetime NOT NULL DEFAULT current_timestamp(),
  `update_date` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `post_post_category` (`category_id`),
  KEY `post_user` (`author_id`) USING BTREE,
  KEY `post_image` (`image_id`),
  CONSTRAINT `post_image` FOREIGN KEY (`image_id`) REFERENCES `blg_image` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `post_post_category` FOREIGN KEY (`category_id`) REFERENCES `blg_post_category` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `post_user` FOREIGN KEY (`author_id`) REFERENCES `blg_user` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Copiando dados para a tabela agropesca.blg_post: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `blg_post` DISABLE KEYS */;
/*!40000 ALTER TABLE `blg_post` ENABLE KEYS */;

-- Copiando estrutura para tabela agropesca.blg_post_category
CREATE TABLE IF NOT EXISTS `blg_post_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image_uri` varchar(200) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `creation_date` datetime NOT NULL DEFAULT current_timestamp(),
  `update_date` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- Copiando dados para a tabela agropesca.blg_post_category: ~3 rows (aproximadamente)
/*!40000 ALTER TABLE `blg_post_category` DISABLE KEYS */;
INSERT INTO `blg_post_category` (`id`, `image_uri`, `name`, `creation_date`, `update_date`) VALUES
	(2, 'https://cdn-icons-png.flaticon.com/512/31/31649.png', 'Pesca', '2022-03-14 11:20:32', '2022-03-14 22:39:30'),
	(3, 'https://cdn-icons-png.flaticon.com/512/193/193720.png', 'Aquaponia', '2022-03-14 11:35:29', '2022-03-14 22:39:52'),
	(4, 'https://cdn-icons-png.flaticon.com/512/40/40486.png', 'Agricultura', '2022-03-14 11:35:29', '2022-03-14 22:40:52');
/*!40000 ALTER TABLE `blg_post_category` ENABLE KEYS */;

-- Copiando estrutura para tabela agropesca.blg_post_visit
CREATE TABLE IF NOT EXISTS `blg_post_visit` (
  `user_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `creation_date` datetime DEFAULT current_timestamp(),
  KEY `post_visit_user` (`user_id`),
  KEY `post_visit_post` (`post_id`),
  CONSTRAINT `post_visit_post` FOREIGN KEY (`post_id`) REFERENCES `blg_post` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `post_visit_user` FOREIGN KEY (`user_id`) REFERENCES `blg_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Copiando dados para a tabela agropesca.blg_post_visit: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `blg_post_visit` DISABLE KEYS */;
/*!40000 ALTER TABLE `blg_post_visit` ENABLE KEYS */;

-- Copiando estrutura para tabela agropesca.blg_user
CREATE TABLE IF NOT EXISTS `blg_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) NOT NULL DEFAULT 1,
  `image_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `password_salt` varchar(10) NOT NULL,
  `creation_date` datetime NOT NULL DEFAULT current_timestamp(),
  `update_date` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `user_user_type` (`type_id`),
  KEY `user_image` (`image_id`),
  CONSTRAINT `user_image` FOREIGN KEY (`image_id`) REFERENCES `blg_image` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `user_user_type` FOREIGN KEY (`type_id`) REFERENCES `blg_user_type` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Copiando dados para a tabela agropesca.blg_user: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `blg_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `blg_user` ENABLE KEYS */;

-- Copiando estrutura para tabela agropesca.blg_user_author
CREATE TABLE IF NOT EXISTS `blg_user_author` (
  `user_id` int(11) DEFAULT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `creation_date` datetime DEFAULT current_timestamp(),
  `update_date` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  UNIQUE KEY `slug` (`slug`),
  KEY `user_autor_user` (`user_id`),
  CONSTRAINT `user_autor_user` FOREIGN KEY (`user_id`) REFERENCES `blg_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Copiando dados para a tabela agropesca.blg_user_author: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `blg_user_author` DISABLE KEYS */;
/*!40000 ALTER TABLE `blg_user_author` ENABLE KEYS */;

-- Copiando estrutura para tabela agropesca.blg_user_type
CREATE TABLE IF NOT EXISTS `blg_user_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `creation_date` datetime DEFAULT current_timestamp(),
  `update_date` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

-- Copiando dados para a tabela agropesca.blg_user_type: ~3 rows (aproximadamente)
/*!40000 ALTER TABLE `blg_user_type` DISABLE KEYS */;
INSERT INTO `blg_user_type` (`id`, `name`, `creation_date`, `update_date`) VALUES
	(1, 'Leitor', '2022-01-26 04:55:48', '2022-03-13 00:32:30'),
	(2, 'Autor', '2022-01-26 04:55:59', '2022-03-13 00:32:50'),
	(3, 'Administrator', '2022-01-26 04:55:39', '2022-03-13 00:32:33');
/*!40000 ALTER TABLE `blg_user_type` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
