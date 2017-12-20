-- --------------------------------------------------------
--
-- 表的结构 `yzn_announce`
--
DROP TABLE IF EXISTS `yzn_announce`;
CREATE TABLE IF NOT EXISTS `yzn_announce` (
  `aid` smallint(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '公告ID',
  `title` char(80) NOT NULL COMMENT '公告标题',
  `content` text NOT NULL COMMENT '公告内容',
  `starttime` date NOT NULL DEFAULT '0000-00-00' COMMENT '有效起始时间',
  `endtime` date NOT NULL DEFAULT '0000-00-00' COMMENT '有效结束时间',
  `username` varchar(40) NOT NULL COMMENT '添加者',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `hits` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '浏览次数',
  `passed` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否通过1：通过；0：为通过',
  `style` char(15) NOT NULL COMMENT '样式',
  PRIMARY KEY (`aid`),
  KEY `siteid` (`passed`,`endtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;