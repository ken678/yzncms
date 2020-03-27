DROP TABLE IF EXISTS `yzn_badword`;
CREATE TABLE `yzn_badword` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `badword` char(20) NOT NULL COMMENT '目标词',
  `replaceword` char(20) NOT NULL DEFAULT '0' COMMENT '替换词',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `listorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;