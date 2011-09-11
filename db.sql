CREATE TABLE IF NOT EXISTS `packages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `package` varchar(50) NOT NULL,
  `version` varchar(20) NOT NULL,
  `dname` varchar(50) NOT NULL,
  `desc` text NOT NULL,
  `arch` varchar(10) DEFAULT NULL,
  `link` varchar(255) NOT NULL,
  `md5` varchar(255) NOT NULL,
  `icon` blob,
  `size` bigint(20) NOT NULL,
  `qinst` tinyint(1) NOT NULL,
  `depsers` varchar(255) DEFAULT NULL,
  `deppkgs` varchar(255) DEFAULT NULL,
  `start` tinyint(1) NOT NULL,
  `maintainer` varchar(60) NOT NULL,
  `changelog` text,
  `beta` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `package` (`package`,`version`,`arch`,`beta`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `package_descriptions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `package_id` bigint(20) unsigned NOT NULL,
  `language` char(3) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `description` (`package_id`,`language`),
  KEY `package_id` (`package_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

ALTER TABLE `package_descriptions`
  ADD CONSTRAINT `package_descriptions_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

