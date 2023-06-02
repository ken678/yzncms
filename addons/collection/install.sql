CREATE TABLE IF NOT EXISTS `__PREFIX__collection_node` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '采集节点',
  `lastdate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后采集时间',
  `sourcecharset` varchar(8) NOT NULL COMMENT '采集点字符集',
  `sourcetype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '网址类型:1序列网址,2单页',
  `urlpage` text NOT NULL COMMENT '采集地址',
  `url_contain` varchar(50) NOT NULL DEFAULT '' COMMENT '网址中必须包含',
  `url_except` varchar(50) NOT NULL DEFAULT '' COMMENT '网址中不能包含',
  `url_range` varchar(50) NOT NULL DEFAULT '' COMMENT '列表url切片选择器',
  `pagesize_start` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '页码开始',
  `pagesize_end` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '页码结束',
  `par_num` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '每次增加数',
  `down_attachment` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否下载图片',
  `watermark` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '图片加水印',
  `coll_order` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '导入顺序',
  `list_config` text NOT NULL COMMENT '列表采集规则',
  `content_config` text NOT NULL COMMENT '内容采集规则',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='采集任务表';

CREATE TABLE IF NOT EXISTS `__PREFIX__collection_content` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0:未采集,1:已采集,2:已导入',
  `url` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(100) NOT NULL DEFAULT '',
  `data` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `nid` (`nid`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='采集内容表';

CREATE TABLE IF NOT EXISTS `__PREFIX__collection_program` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL DEFAULT '',
  `nid` int(10) unsigned NOT NULL DEFAULT '0',
  `modelid` mediumint(6) unsigned NOT NULL DEFAULT '0',
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `config` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `nid` (`nid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='采集方案表';

INSERT INTO `__PREFIX__collection_node` VALUES (1, 'DCloud 插件市场【仅供学习】', 1685684240, 'utf-8', 1, 'https://ext.dcloud.net.cn/?page=(*)', '', '', '.plugin-list li', 1, 1, 1, 0, 0, 0, '[{\"title\":\"\\u94fe\\u63a5\",\"name\":\"url\",\"selector\":\"h2 a\",\"attr\":\"href\",\"value\":\"\",\"filter\":\"\"},{\"title\":\"\\u6807\\u9898\",\"name\":\"title\",\"selector\":\"h2 a\",\"attr\":\"text\",\"value\":\"\",\"filter\":\"-.badge\"},{\"title\":\"\\u7f29\\u7565\\u56fe\",\"name\":\"thumb\",\"selector\":\".preview-img img\",\"attr\":\"src\",\"value\":\"\",\"filter\":\"\"}]', '[{\"title\":\"\\u5185\\u5bb9\",\"name\":\"content\",\"selector\":\".overview\",\"attr\":\"html\",\"value\":\"\",\"filter\":\"\"}]');
