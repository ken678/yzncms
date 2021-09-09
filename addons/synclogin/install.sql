CREATE TABLE IF NOT EXISTS `__PREFIX__sync_login` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`uid` int(11) unsigned NOT NULL COMMENT '用户ID',
    `unionid` varchar(50) DEFAULT '' COMMENT '第三方UNIONID',
	`openid` varchar(50) DEFAULT '' COMMENT '第三方OPENID',
    `openname` varchar(50) DEFAULT '' COMMENT '第三方会员昵称',
	`type` varchar(15) NOT NULL COMMENT '第三方英文名称',
	`access_token` varchar(100) NOT NULL,
    `create_time` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
    `update_time` int(10) unsigned DEFAULT NULL COMMENT '更新时间',
    `login_time` int(10) unsigned DEFAULT NULL COMMENT '登录时间',
    `expire_time` int(10) unsigned DEFAULT NULL COMMENT '过期时间',
	`status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态描述',
    PRIMARY KEY (`id`),
    UNIQUE KEY `type` (`type`,`openid`),
    KEY `uid` (`uid`,`type`),
    KEY `unionid` (`type`,`unionid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COMMENT='第三方登录表';