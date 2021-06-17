CREATE TABLE IF NOT EXISTS `yzn_links` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '链接id',
  `linktype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0:文字链接,1:logo链接',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '链接名称',
  `image` varchar(255) NOT NULL DEFAULT '' COMMENT '链接图片',
  `target` varchar(25) NOT NULL DEFAULT '' COMMENT '链接打开方式',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '链接描述',
  `inputtime` int(11) NOT NULL COMMENT '添加时间',
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `termsid` smallint(4) NOT NULL COMMENT '分类id',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0未通过,1正常,2未审核',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='友情链接';
