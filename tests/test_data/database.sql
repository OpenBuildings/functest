DROP TABLE IF EXISTS `table1`;
CREATE TABLE `table1` (
	`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, 
	`name` varchar(32) NOT NULL, 
	PRIMARY KEY (`id`), 
	UNIQUE KEY `uniq_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
