DROP TABLE IF EXISTS `yzn_collection_node`;
CREATE TABLE `yzn_collection_node` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '采集节点',
  `lastdate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后采集时间',
  `sourcecharset` varchar(8) NOT NULL COMMENT '采集点字符集',
  `sourcetype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '网址类型:1序列网址,2单页',
  `urlpage` text NOT NULL COMMENT '采集地址',
  `url_contain` char(50) NOT NULL DEFAULT '' COMMENT '网址中必须包含',
  `url_except` char(50) NOT NULL DEFAULT '' COMMENT '网址中不能包含',
  `url_rule1` char(50) NOT NULL DEFAULT '' COMMENT '内容选择器规则',
  `url_rule2` char(50) NOT NULL DEFAULT '' COMMENT '内容属性规则',
  `url_rule3` char(50) NOT NULL DEFAULT '' COMMENT '内容过滤器规则',
  `pagesize_start` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '页码开始',
  `pagesize_end` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '页码结束',
  `par_num` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '每次增加数',
  `down_attachment` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否下载图片',
  `watermark` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '图片加水印',
  `coll_order` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '导入顺序',
  `customize_config` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='采集任务表';

DROP TABLE IF EXISTS `yzn_collection_content`;
CREATE TABLE `yzn_collection_content` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0:未采集,1:已采集,2:已导入',
  `url` char(255) NOT NULL DEFAULT '',
  `title` char(100) NOT NULL DEFAULT '',
  `data` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `nid` (`nid`),
  KEY `status` (`status`),
  KEY `url` (`url`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='采集内容表';

DROP TABLE IF EXISTS `yzn_collection_program`;
CREATE TABLE `yzn_collection_program` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nid` int(10) unsigned NOT NULL DEFAULT '0',
  `modelid` mediumint(6) unsigned NOT NULL DEFAULT '0',
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `config` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `nid` (`nid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='采集方案表';