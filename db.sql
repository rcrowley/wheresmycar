use car;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
	`id` int(11) unsigned NOT NULL auto_increment,
	`username` varchar(40) NOT NULL,
	`email` varchar(255) NOT NULL,
	`confirm_email` tinyint(4) unsigned NOT NULL DEFAULT 0,
	`sms` varchar(255) NOT NULL,
	`confirm_sms` tinyint(4) unsigned NOT NULL DEFAULT 0,
	`password` varchar(40) NOT NULL,
	`hash` varchar(40),
	`created` datetime NOT NULL,
	`address` varchar(255),
	`impossible` tinyint(4) unsigned NOT NULL DEFAULT 0,
	`sweep` varchar(255),
	`sweep_ts` int(11) unsigned NOT NULL DEFAULT 0,
	`inaccurate` tinyint(4) unsigned NOT NULL DEFAULT 0,
	`sent_email` tinyint(4) unsigned NOT NULL DEFAULT 0,
	`sent_sms` tinyint(4) unsigned NOT NULL DEFAULT 0,
	PRIMARY KEY (`id`),
	UNIQUE `username_key` (`username`, `password`),
	UNIQUE `email_key` (`email`),
	KEY `hash_key` (`id`, `hash`),
	KEY `address_key` (`address`),
	KEY `remind_email_key` (`confirm_email`, `sweep_ts`),
	KEY `remind_sms_key` (`confirm_sms`, `sweep_ts`)--,
--	KEY `gis_key` (`address`, `impossible`, `sweep`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
