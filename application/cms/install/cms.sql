DROP TABLE IF EXISTS `yzn_category`;
CREATE TABLE `yzn_category` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '栏目ID',
  `catname` varchar(30) NOT NULL DEFAULT '' COMMENT '栏目名称',
  `catdir` varchar(30) NOT NULL DEFAULT '' COMMENT '唯一标识',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '类别',
  `modelid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '模型ID',
  `parentid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `arrparentid` varchar(255) NOT NULL DEFAULT '' COMMENT '所有父ID',
  `child` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否存在子栏目，1存在',
  `arrchildid` mediumtext COMMENT '所有子栏目ID',
  `image` varchar(100) NOT NULL DEFAULT '' COMMENT '栏目图片',
  `description` mediumtext COMMENT '栏目描述',
  `url` varchar(100) NOT NULL DEFAULT '' COMMENT '链接地址',
  `setting` mediumtext COMMENT '相关配置信息',
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `letter` varchar(30) NOT NULL DEFAULT '' COMMENT '栏目拼音',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='栏目表';

DROP TABLE IF EXISTS `yzn_model`;
CREATE TABLE `yzn_model` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(30) NOT NULL DEFAULT '' COMMENT '模型名称',
  `tablename` char(20) NOT NULL DEFAULT '' COMMENT '表名',
  `description` char(100) NOT NULL DEFAULT '' COMMENT '描述',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '模型类别：1-独立表，2-主附表',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `ifsub` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否允许投稿',
  `listorders` tinyint(3) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否禁用 1禁用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='内容模型列表';


INSERT INTO `yzn_model` VALUES ('1', '文章模型', 'article', '文章模型', '2', '1545799009', '1545799009', '0', '0', '1');

DROP TABLE IF EXISTS `yzn_model_field`;
CREATE TABLE `yzn_model_field` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `modelid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '模型ID',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '字段名',
  `title` varchar(30) NOT NULL DEFAULT '' COMMENT '别名',
  `tips` tinytext NOT NULL COMMENT '字段提示',
  `formtype` varchar(20) NOT NULL DEFAULT '' COMMENT '字段类型',
  `define` varchar(128) NOT NULL COMMENT '字段定义',
  `setting` tinytext NOT NULL COMMENT '额外设置',
  `value` tinytext NOT NULL COMMENT '默认值',
  `jsonrule` tinytext NOT NULL COMMENT '关联规则',
  `ifsystem` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否主表字段 1 是',
  `ifeditable` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否可以编辑',
  `iffixed` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否固定不可修改',
  `ifrequire` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否必填',
  `ifsearch` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '作为搜索条件',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `listorder` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1 禁用 0启用',
  PRIMARY KEY (`id`),
  KEY `name` (`name`,`modelid`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='模型字段列表';

INSERT INTO `yzn_model_field` VALUES ('1', '1', 'id', '文档id', '', 'hidden', 'mediumint(8) UNSIGNED', '', '', '', '1', '1', '1', '0', '0', '1545799008', '1545799008', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('2', '1', 'title', '标题', '', 'text', 'varchar(255)', '', '', '', '1', '1', '0', '1', '1', '1545799008', '1545799008', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('3', '1', 'keywords', 'SEO关键词', '', 'tags', 'varchar(255)', '', '', '{\"string\":{\"table\":\"tag\",\"key\":\"title\",\"delimiter\":\",\",\"where\":\"\",\"limit\":\"6\",\"order\":\"[rand]\"}}', '1', '1', '0', '0', '0', '1545799008', '1545799008', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('4', '1', 'description', 'SEO摘要', '', 'number', 'varchar(255)', '', '', '', '1', '0', '0', '0', '0', '1545799008', '1545799008', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('5', '1', 'uid', '用户id', '', 'number', 'mediumint(8) UNSIGNED', '', '1', '', '1', '0', '1', '0', '0', '1545799008', '1545799008', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('6', '1', 'posid', '推荐位', '', 'number', 'tinyint(3) tinyint(3)', '', '', '', '1', '0', '1', '0', '0', '1545799008', '1545799008', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('7', '1', 'listorder', '排序', '', 'number', 'tinyint(3) UNSIGNED', '', '100', '', '1', '1', '1', '0', '0', '1545799008', '1545799008', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('8', '1', 'status', '状态', '', 'radio', 'tinyint(1)', '0:禁用\r\n1:启用', '1', '', '1', '1', '1', '0', '0', '1545799008', '1545799008', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('9', '1', 'inputtime', '创建时间', '', 'datetime', 'int(11) UNSIGNED', '', '0', '', '1', '1', '1', '0', '0', '1545799008', '1545799008', '200', '1');
INSERT INTO `yzn_model_field` VALUES ('10', '1', 'updatetime', '更新时间', '', 'datetime', 'int(11) UNSIGNED', '', '0', '', '1', '0', '1', '0', '0', '1545799008', '1545799008', '200', '1');
INSERT INTO `yzn_model_field` VALUES ('11', '1', 'hits', '点击量', '', 'number', 'mediumint(8) UNSIGNED', '', '0', '', '1', '1', '1', '0', '0', '1545799008', '1545799008', '200', '1');
INSERT INTO `yzn_model_field` VALUES ('12', '1', 'did', '附表文档id', '', 'hidden', 'mediumint(8) UNSIGNED', '', '', '', '0', '0', '1', '0', '0', '1545799008', '1545799008', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('13', '1', 'content', '内容', '', 'Ueditor', 'text', '', '', '', '0', '1', '0', '0', '0', '1545799008', '1545799008', '100', '1');

DROP TABLE IF EXISTS `yzn_article`;
CREATE TABLE `yzn_article` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '文档ID',
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '标题',
  `keywords` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'SEO关键词',
  `tags` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'SEO描述',
  `posid` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '推荐位',
  `listorder` tinyint(3) unsigned NOT NULL DEFAULT '100' COMMENT '排序',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `hits` mediumint(8) unsigned DEFAULT '0' COMMENT '点击量',
  `inputtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='文章模型模型表';

DROP TABLE IF EXISTS `yzn_article_data`;
CREATE TABLE `yzn_article_data` (
  `did` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `content` text COLLATE utf8_unicode_ci COMMENT '内容',
  PRIMARY KEY (`did`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='文章模型模型表';