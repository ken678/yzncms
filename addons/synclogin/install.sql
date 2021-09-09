CREATE TABLE IF NOT EXISTS `__PREFIX__sync_login` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`uid` int(11) unsigned NOT NULL COMMENT '用户ID',
    `unionid` varchar(100) DEFAULT '' COMMENT '第三方UNIONID',
	`openid` varchar(100) DEFAULT '' COMMENT '第三方OPENID',
    `openname` varchar(100) DEFAULT '' COMMENT '第三方会员昵称',
	`type` varchar(255) NOT NULL,
	`access_token` varchar(255) NOT NULL,
    `create_time` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
    `update_time` int(10) unsigned DEFAULT NULL COMMENT '更新时间',
    `logint_time` int(10) unsigned DEFAULT NULL COMMENT '登录时间',
    `expire_time` int(10) unsigned DEFAULT NULL COMMENT '过期时间',
	`status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态描述',
    PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COMMENT='第三方登录表';