CREATE TABLE IF NOT EXISTS `__PREFIX__sync_login` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`uid` int(11) NOT NULL,
	`type_uid` varchar(255) NOT NULL,
	`type` varchar(255) NOT NULL,
	`oauth_token` varchar(255) NOT NULL,
	`oauth_token_secret` varchar(255) NOT NULL,
	`status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态描述',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COMMENT='第三方登录表';