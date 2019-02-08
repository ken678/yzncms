DROP TABLE IF EXISTS `yzn_article`;
CREATE TABLE `yzn_article` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '文档ID',
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '栏目ID',
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '标题',
  `keywords` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'SEO关键词',
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'SEO描述',
  `posid` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '推荐位',
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '100' COMMENT '排序',
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

DROP TABLE IF EXISTS `yzn_picture`;
CREATE TABLE `yzn_picture` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '文档ID',
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '栏目ID',
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '标题',
  `keywords` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'SEO关键词',
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'SEO描述',
  `posid` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '推荐位',
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '100' COMMENT '排序',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `hits` mediumint(8) unsigned DEFAULT '0' COMMENT '点击量',
  `inputtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='图片模型模型表';

DROP TABLE IF EXISTS `yzn_picture_data`;
CREATE TABLE `yzn_picture_data` (
  `did` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `content` text COLLATE utf8_unicode_ci COMMENT '内容',
  PRIMARY KEY (`did`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='图片模型模型表';

DROP TABLE IF EXISTS `yzn_product`;
CREATE TABLE `yzn_product` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '文档ID',
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '栏目ID',
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '标题',
  `keywords` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'SEO关键词',
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'SEO描述',
  `posid` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '推荐位',
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '100' COMMENT '排序',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `hits` mediumint(8) unsigned DEFAULT '0' COMMENT '点击量',
  `inputtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='产品模型模型表';

DROP TABLE IF EXISTS `yzn_product_data`;
CREATE TABLE `yzn_product_data` (
  `did` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `content` text COLLATE utf8_unicode_ci COMMENT '内容',
  PRIMARY KEY (`did`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='产品模型模型表';

INSERT INTO `yzn_category` VALUES ('1', '关于我们', 'about', '1', '0', '0', '0', '1', '1,2,3,4', '0', '', '', 'a:7:{s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:13:\"category.html\";s:13:\"list_template\";s:9:\"list.html\";s:13:\"show_template\";s:9:\"show.html\";s:13:\"page_template\";s:9:\"page.html\";}', '100', '1');
INSERT INTO `yzn_category` VALUES ('2', '公司简介', 'introduction', '1', '0', '1', '0,1', '0', '2', '0', '', '', 'a:7:{s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:13:\"category.html\";s:13:\"list_template\";s:9:\"list.html\";s:13:\"show_template\";s:9:\"show.html\";s:13:\"page_template\";s:9:\"page.html\";}', '100', '1');
INSERT INTO `yzn_category` VALUES ('3', '企业文化', 'culture', '1', '0', '1', '0,1', '0', '3', '0', '', '', 'a:7:{s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:13:\"category.html\";s:13:\"list_template\";s:9:\"list.html\";s:13:\"show_template\";s:9:\"show.html\";s:13:\"page_template\";s:9:\"page.html\";}', '100', '1');
INSERT INTO `yzn_category` VALUES ('4', '公司荣誉', 'honor', '2', '2', '1', '0,1', '0', '4', '0', '', '', 'a:7:{s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:13:\"category.html\";s:13:\"list_template\";s:9:\"list.html\";s:13:\"show_template\";s:9:\"show.html\";s:13:\"page_template\";s:9:\"page.html\";}', '100', '1');
INSERT INTO `yzn_category` VALUES ('5', '案例展示', 'case', '2', '2', '0', '0', '1', '5,15,16,17', '0', '', '', 'a:7:{s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:21:\"category_picture.html\";s:13:\"list_template\";s:17:\"list_picture.html\";s:13:\"show_template\";s:17:\"show_picture.html\";s:13:\"page_template\";s:9:\"page.html\";}', '100', '1');
INSERT INTO `yzn_category` VALUES ('6', '新闻中心', 'news', '2', '1', '0', '0', '1', '6,9,10,14', '0', '', '', 'a:7:{s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:13:\"category.html\";s:13:\"list_template\";s:9:\"list.html\";s:13:\"show_template\";s:9:\"show.html\";s:13:\"page_template\";s:9:\"page.html\";}', '100', '1');
INSERT INTO `yzn_category` VALUES ('8', '联系我们', 'contact', '1', '0', '0', '0', '0', '8', '0', '', '', 'a:7:{s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:13:\"category.html\";s:13:\"list_template\";s:9:\"list.html\";s:13:\"show_template\";s:9:\"show.html\";s:13:\"page_template\";s:9:\"page.html\";}', '100', '1');
INSERT INTO `yzn_category` VALUES ('9', '网络营销', 'marketing', '2', '1', '6', '0,6', '0', '9', '0', '', '', 'a:7:{s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:13:\"category.html\";s:13:\"list_template\";s:9:\"list.html\";s:13:\"show_template\";s:9:\"show.html\";s:13:\"page_template\";s:9:\"page.html\";}', '100', '1');
INSERT INTO `yzn_category` VALUES ('10', '网站知识', 'knowledge', '2', '1', '6', '0,6', '0', '10', '0', '', '', 'a:7:{s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:13:\"category.html\";s:13:\"list_template\";s:9:\"list.html\";s:13:\"show_template\";s:9:\"show.html\";s:13:\"page_template\";s:9:\"page.html\";}', '100', '1');
INSERT INTO `yzn_category` VALUES ('14', '备案知识', 'record', '2', '1', '6', '0,6', '0', '14', '0', '', '', 'a:7:{s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:13:\"category.html\";s:13:\"list_template\";s:9:\"list.html\";s:13:\"show_template\";s:9:\"show.html\";s:13:\"page_template\";s:9:\"page.html\";}', '100', '1');
INSERT INTO `yzn_category` VALUES ('15', '企业网站', 'enterprise', '2', '2', '5', '0,5', '0', '15', '0', '', '', 'a:7:{s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:13:\"category.html\";s:13:\"list_template\";s:9:\"list.html\";s:13:\"show_template\";s:9:\"show.html\";s:13:\"page_template\";s:9:\"page.html\";}', '100', '1');
INSERT INTO `yzn_category` VALUES ('16', '响应式网站', 'responsive', '2', '2', '5', '0,5', '0', '16', '0', '', '', 'a:7:{s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:13:\"category.html\";s:13:\"list_template\";s:9:\"list.html\";s:13:\"show_template\";s:9:\"show.html\";s:13:\"page_template\";s:9:\"page.html\";}', '100', '1');
INSERT INTO `yzn_category` VALUES ('17', '手机网站', 'mobile', '2', '2', '5', '0,5', '0', '17', '0', '', '', 'a:7:{s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:13:\"category.html\";s:13:\"list_template\";s:9:\"list.html\";s:13:\"show_template\";s:9:\"show.html\";s:13:\"page_template\";s:9:\"page.html\";}', '100', '1');


INSERT INTO `yzn_model` VALUES ('1', '文章模型', 'article', '文章模型', '2', '1546574975', '1546574975', '0', '0', '1');
INSERT INTO `yzn_model` VALUES ('2', '图片模型', 'picture', '图片模型', '2', '1548754193', '1548754193', '0', '0', '1');
INSERT INTO `yzn_model` VALUES ('3', '产品模型', 'product', '产品模型', '2', '1549165800', '1549165800', '0', '0', '1');


INSERT INTO `yzn_position` VALUES ('1', '0', '0', '首页幻灯片', '1500272764', '1500519504', '1');
INSERT INTO `yzn_position` VALUES ('2', '0', '0', '首页文字推荐', '1500272764', '1500519504', '2');
INSERT INTO `yzn_position` VALUES ('3', '0', '0', '首页图片推荐', '1500272764', '1500519504', '3');

INSERT INTO `yzn_model_field` VALUES ('1', '1', 'id', '文档id', '', 'hidden', 'mediumint(8) UNSIGNED', '', '', '', '1', '1', '1', '0', '0', '1546574975', '1546574975', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('2', '1', 'catid', '栏目id', '', 'hidden', 'smallint(5) unsigned', '', '', '', '1', '1', '1', '0', '0', '1546574975', '1546574975', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('3', '1', 'title', '标题', '', 'text', 'varchar(255)', '', '', '', '1', '1', '0', '1', '1', '1546574975', '1546574975', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('4', '1', 'keywords', 'SEO关键词', '', 'text', 'varchar(255)', '', '', '{\"string\":{\"table\":\"tag\",\"key\":\"title\",\"delimiter\":\",\",\"where\":\"\",\"limit\":\"6\",\"order\":\"[rand]\"}}', '1', '1', '0', '0', '0', '1546574975', '1546574975', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('5', '1', 'description', 'SEO摘要', '', 'textarea', 'varchar(255)', '', '', '', '1', '1', '0', '0', '0', '1546574975', '1546574975', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('6', '1', 'uid', '用户id', '', 'number', 'mediumint(8) UNSIGNED', '', '1', '', '1', '0', '1', '0', '0', '1546574975', '1546574975', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('7', '1', 'posid', '推荐位', '', 'checkbox', 'tinyint(3) UNSIGNED', '', '', '', '1', '0', '1', '0', '0', '1546574975', '1546574975', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('8', '1', 'listorder', '排序', '', 'number', 'tinyint(3) UNSIGNED', '', '100', '', '1', '1', '1', '0', '0', '1546574975', '1546574975', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('9', '1', 'status', '状态', '', 'radio', 'tinyint(1)', '0:禁用\r\n1:启用', '1', '', '1', '1', '1', '0', '0', '1546574975', '1546574975', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('10', '1', 'inputtime', '创建时间', '', 'datetime', 'int(11) UNSIGNED', '', '0', '', '1', '1', '1', '0', '0', '1546574975', '1546574975', '200', '1');
INSERT INTO `yzn_model_field` VALUES ('11', '1', 'updatetime', '更新时间', '', 'datetime', 'int(11) UNSIGNED', '', '0', '', '1', '0', '1', '0', '0', '1546574975', '1546574975', '200', '1');
INSERT INTO `yzn_model_field` VALUES ('12', '1', 'hits', '点击量', '', 'number', 'mediumint(8) UNSIGNED', '', '0', '', '1', '1', '1', '0', '0', '1546574975', '1546574975', '200', '1');
INSERT INTO `yzn_model_field` VALUES ('13', '1', 'did', '附表文档id', '', 'hidden', 'mediumint(8) UNSIGNED', '', '', '', '0', '0', '1', '0', '0', '1546574975', '1546574975', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('14', '1', 'content', '内容', '', 'Ueditor', 'text', '', '', '', '0', '1', '0', '0', '0', '1546574975', '1546574975', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('15', '2', 'id', '文档id', '', 'hidden', 'mediumint(8) UNSIGNED', '', '', '', '1', '1', '1', '0', '0', '1548754192', '1548754192', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('16', '2', 'catid', '栏目id', '', 'hidden', 'smallint(5) unsigned', '', '', '', '1', '1', '1', '0', '0', '1548754192', '1548754192', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('17', '2', 'title', '标题', '', 'text', 'varchar(255)', '', '', '', '1', '1', '0', '1', '1', '1548754192', '1548754192', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('18', '2', 'keywords', 'SEO关键词', '', 'text', 'varchar(255)', '', '', '{\"string\":{\"table\":\"tag\",\"key\":\"title\",\"delimiter\":\",\",\"where\":\"\",\"limit\":\"6\",\"order\":\"[rand]\"}}', '1', '1', '0', '0', '0', '1548754192', '1548754192', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('19', '2', 'description', 'SEO摘要', '', 'textarea', 'varchar(255)', '', '', '', '1', '1', '0', '0', '0', '1548754192', '1548754192', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('20', '2', 'uid', '用户id', '', 'number', 'mediumint(8) UNSIGNED', '', '1', '', '1', '0', '1', '0', '0', '1548754192', '1548754192', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('21', '2', 'posid', '推荐位', '', 'checkbox', 'tinyint(3) UNSIGNED', '', '', '', '1', '0', '1', '0', '0', '1548754192', '1548754192', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('22', '2', 'listorder', '排序', '', 'number', 'smallint(5) UNSIGNED', '', '100', '', '1', '1', '1', '0', '0', '1548754192', '1548754192', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('23', '2', 'status', '状态', '', 'radio', 'tinyint(1)', '0:禁用\r\n1:启用', '1', '', '1', '1', '1', '0', '0', '1548754192', '1548754192', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('24', '2', 'inputtime', '创建时间', '', 'datetime', 'int(11) UNSIGNED', '', '0', '', '1', '1', '1', '0', '0', '1548754192', '1548754192', '200', '1');
INSERT INTO `yzn_model_field` VALUES ('25', '2', 'updatetime', '更新时间', '', 'datetime', 'int(11) UNSIGNED', '', '0', '', '1', '0', '1', '0', '0', '1548754192', '1548754192', '200', '1');
INSERT INTO `yzn_model_field` VALUES ('26', '2', 'hits', '点击量', '', 'number', 'mediumint(8) UNSIGNED', '', '0', '', '1', '1', '1', '0', '0', '1548754192', '1548754192', '200', '1');
INSERT INTO `yzn_model_field` VALUES ('27', '2', 'did', '附表文档id', '', 'hidden', 'mediumint(8) UNSIGNED', '', '', '', '0', '0', '1', '0', '0', '1548754192', '1548754192', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('28', '2', 'content', '内容', '', 'Ueditor', 'text', '', '', '', '0', '1', '0', '0', '0', '1548754192', '1548754192', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('29', '3', 'id', '文档id', '', 'hidden', 'mediumint(8) UNSIGNED', '', '', '', '1', '1', '1', '0', '0', '1549165800', '1549165800', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('30', '3', 'catid', '栏目id', '', 'hidden', 'smallint(5) unsigned', '', '', '', '1', '1', '1', '0', '0', '1549165800', '1549165800', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('31', '3', 'title', '标题', '', 'text', 'varchar(255)', '', '', '', '1', '1', '0', '1', '1', '1549165800', '1549165800', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('32', '3', 'keywords', 'SEO关键词', '', 'text', 'varchar(255)', '', '', '{\"string\":{\"table\":\"tag\",\"key\":\"title\",\"delimiter\":\",\",\"where\":\"\",\"limit\":\"6\",\"order\":\"[rand]\"}}', '1', '1', '0', '0', '0', '1549165800', '1549165800', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('33', '3', 'description', 'SEO摘要', '', 'textarea', 'varchar(255)', '', '', '', '1', '1', '0', '0', '0', '1549165800', '1549165800', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('34', '3', 'uid', '用户id', '', 'number', 'mediumint(8) UNSIGNED', '', '1', '', '1', '0', '1', '0', '0', '1549165800', '1549165800', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('35', '3', 'posid', '推荐位', '', 'checkbox', 'tinyint(3) UNSIGNED', '', '', '', '1', '0', '1', '0', '0', '1549165800', '1549165800', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('36', '3', 'listorder', '排序', '', 'number', 'smallint(5) UNSIGNED', '', '100', '', '1', '1', '1', '0', '0', '1549165800', '1549165800', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('37', '3', 'status', '状态', '', 'radio', 'tinyint(1)', '0:禁用\r\n1:启用', '1', '', '1', '1', '1', '0', '0', '1549165800', '1549165800', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('38', '3', 'inputtime', '创建时间', '', 'datetime', 'int(11) UNSIGNED', '', '0', '', '1', '1', '1', '0', '0', '1549165800', '1549165800', '200', '1');
INSERT INTO `yzn_model_field` VALUES ('39', '3', 'updatetime', '更新时间', '', 'datetime', 'int(11) UNSIGNED', '', '0', '', '1', '0', '1', '0', '0', '1549165800', '1549165800', '200', '1');
INSERT INTO `yzn_model_field` VALUES ('40', '3', 'hits', '点击量', '', 'number', 'mediumint(8) UNSIGNED', '', '0', '', '1', '1', '1', '0', '0', '1549165800', '1549165800', '200', '1');
INSERT INTO `yzn_model_field` VALUES ('41', '3', 'did', '附表文档id', '', 'hidden', 'mediumint(8) UNSIGNED', '', '', '', '0', '0', '1', '0', '0', '1549165800', '1549165800', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('42', '3', 'content', '内容', '', 'Ueditor', 'text', '', '', '', '0', '1', '0', '0', '0', '1549165800', '1549165800', '100', '1');