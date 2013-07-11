/**
  Dump from database aligned to config.json in lmvc-base's root.

  Import this into your database to be able to use the DatabasePrincipal.
 */

DROP TABLE IF EXISTS `Groups`;

CREATE TABLE `Groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `Groups` WRITE;

INSERT INTO `Groups` (`id`, `group_name`)
VALUES
	(1,'Admin'),
	(2,'User');

UNLOCK TABLES;

DROP TABLE IF EXISTS `Roles`;

CREATE TABLE `Roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `Roles` WRITE;
/*!40000 ALTER TABLE `Roles` DISABLE KEYS */;

INSERT INTO `Roles` (`id`, `role_name`)
VALUES
	(1,'Read'),
	(2,'Edit'),
	(3,'Delete');

UNLOCK TABLES;

DROP TABLE IF EXISTS `User_to_Groups`;

CREATE TABLE `User_to_Groups` (
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `User_to_Groups` WRITE;

INSERT INTO `User_to_Groups` (`user_id`, `group_id`)
VALUES
	(1,2),
	(2,1);

UNLOCK TABLES;

DROP TABLE IF EXISTS `User_to_Roles`;

CREATE TABLE `User_to_Roles` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `User_to_Roles` WRITE;

INSERT INTO `User_to_Roles` (`user_id`, `role_id`)
VALUES
	(1,2),
	(2,1),
	(2,2),
	(2,3);

UNLOCK TABLES;

DROP TABLE IF EXISTS `Users`;

CREATE TABLE `Users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT 'NOT NULL',
  `fullname` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `mobile` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `Users` WRITE;

INSERT INTO `Users` (`id`, `username`, `fullname`, `email`, `phone`, `mobile`, `password`)
VALUES
	(1,'ckoch','Christian Koch','christian.koch@scandio.de','+49 89 244 124-44','+49 172 852 22 25','e790da6e1157cb9a11063ac32431b8a820662059'),
	(2,'admin','Administrator','info@scandio.de','+49 89 244 124-0',NULL,'d033e22ae348aeb5660fc2140aec35850c4da997');

UNLOCK TABLES;
