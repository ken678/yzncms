CREATE TABLE `@lvyecms@@zhubiao@_data` (
  `id` mediumint(8) unsigned DEFAULT '0',
  `content` text,
  `paginationtype` tinyint(1) NOT NULL DEFAULT '0',
  `maxcharperpage` mediumint(6) NOT NULL DEFAULT '0',
  `template` varchar(30) NOT NULL DEFAULT '',
  `paytype` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `allow_comment` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `relation` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;