CREATE TABLE IF NOT EXISTS `__PREFIX__pay_account` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '交易ID',
  `trade_sn` varchar(50) NOT NULL COMMENT '订单ID',
  `uid` int(11) unsigned NOT NULL COMMENT '用户ID',
  `username` varchar(16) NOT NULL COMMENT '用户名',
  `money` varchar(8) NOT NULL COMMENT '价格',
  `addtime` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `paytime` int(10) NOT NULL DEFAULT '0' COMMENT '支付时间',
  `usernote` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `pay_type` enum('offline','recharge','selfincome','online') NOT NULL DEFAULT 'recharge' COMMENT '支付类型',
  `payment` varchar(90) NOT NULL COMMENT '支付方式名称',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1金钱or2点数',
  `ip` varchar(15) NOT NULL DEFAULT '' COMMENT '操作IP',
  `status` enum('succ','failed','error','progress','timeout','cancel','waitting','unpay') NOT NULL DEFAULT 'unpay' COMMENT '状态',
  `adminnote` varchar(20) NOT NULL DEFAULT '' COMMENT '管理员记录',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `uid` (`uid`),
  KEY `trade_sn` (`trade_sn`,`money`,`status`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='交易数据表';

CREATE TABLE IF NOT EXISTS `__PREFIX__pay_spend` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '消费ID',
  `creat_at` varchar(20) NOT NULL COMMENT '消费流水号',
  `uid` int(11) unsigned NOT NULL COMMENT '用户ID',
  `username` varchar(16) NOT NULL COMMENT '用户名',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1金钱or2点数',
  `money` varchar(8) NOT NULL COMMENT '价格',
  `msg` varchar(30) NOT NULL COMMENT '类型说明',
  `addtime` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `ip` varchar(15) NOT NULL DEFAULT '' COMMENT '操作IP',
  `remarks` varchar(255) NOT NULL COMMENT '备注说明',
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `creat_at` (`creat_at`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='消费记录表';