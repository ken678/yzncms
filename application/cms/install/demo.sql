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

INSERT INTO `yzn_category` VALUES ('2', '公司简介', 'Introduction', '1', '0', '1', '0,1', '0', '2', '0', '', '', 'a:7:{s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:13:\"category.html\";s:13:\"list_template\";s:9:\"list.html\";s:13:\"show_template\";s:9:\"show.html\";s:13:\"page_template\";s:9:\"page.html\";}', '1', '1');
INSERT INTO `yzn_category` VALUES ('3', '企业文化', 'culture', '1', '0', '1', '0,1', '0', '3', '0', '', '', 'a:7:{s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:13:\"category.html\";s:13:\"list_template\";s:9:\"list.html\";s:13:\"show_template\";s:9:\"show.html\";s:13:\"page_template\";s:9:\"page.html\";}', '2', '1');
INSERT INTO `yzn_category` VALUES ('4', '公司荣誉', 'honor', '2', '2', '1', '0,1', '0', '4', '0', '', '', 'a:7:{s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:21:\"category_picture.html\";s:13:\"list_template\";s:17:\"list_picture.html\";s:13:\"show_template\";s:17:\"show_picture.html\";s:13:\"page_template\";s:9:\"page.html\";}', '3', '1');
INSERT INTO `yzn_category` VALUES ('5', '案例展示', 'case', '2', '2', '0', '0', '1', '5,15,16,17', '0', '', '', 'a:7:{s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:21:\"category_picture.html\";s:13:\"list_template\";s:17:\"list_picture.html\";s:13:\"show_template\";s:17:\"show_picture.html\";s:13:\"page_template\";s:9:\"page.html\";}', '2', '1');
INSERT INTO `yzn_category` VALUES ('6', '新闻中心', 'news', '2', '1', '0', '0', '1', '6,9,10,14', '0', '', '', 'a:7:{s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:13:\"category.html\";s:13:\"list_template\";s:9:\"list.html\";s:13:\"show_template\";s:9:\"show.html\";s:13:\"page_template\";s:9:\"page.html\";}', '3', '1');
INSERT INTO `yzn_category` VALUES ('8', '联系我们', 'contact', '1', '0', '0', '0', '0', '8', '0', '', '', 'a:7:{s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:13:\"category.html\";s:13:\"list_template\";s:9:\"list.html\";s:13:\"show_template\";s:9:\"show.html\";s:13:\"page_template\";s:9:\"page.html\";}', '4', '1');
INSERT INTO `yzn_category` VALUES ('9', '网络营销', 'marketing', '2', '1', '6', '0,6', '0', '9', '0', '', '', 'a:7:{s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:13:\"category.html\";s:13:\"list_template\";s:9:\"list.html\";s:13:\"show_template\";s:9:\"show.html\";s:13:\"page_template\";s:9:\"page.html\";}', '1', '1');
INSERT INTO `yzn_category` VALUES ('10', '网站知识', 'knowledge', '2', '1', '6', '0,6', '0', '10', '0', '', '', 'a:7:{s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:13:\"category.html\";s:13:\"list_template\";s:9:\"list.html\";s:13:\"show_template\";s:9:\"show.html\";s:13:\"page_template\";s:9:\"page.html\";}', '2', '1');
INSERT INTO `yzn_category` VALUES ('14', '备案知识', 'record', '2', '1', '6', '0,6', '0', '14', '0', '', '', 'a:7:{s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:13:\"category.html\";s:13:\"list_template\";s:9:\"list.html\";s:13:\"show_template\";s:9:\"show.html\";s:13:\"page_template\";s:9:\"page.html\";}', '3', '1');
INSERT INTO `yzn_category` VALUES ('15', '企业网站', 'enterprise', '2', '2', '5', '0,5', '0', '15', '0', '', '', 'a:7:{s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:13:\"category.html\";s:13:\"list_template\";s:9:\"list.html\";s:13:\"show_template\";s:9:\"show.html\";s:13:\"page_template\";s:9:\"page.html\";}', '1', '1');
INSERT INTO `yzn_category` VALUES ('16', '响应式网站', 'responsive', '2', '2', '5', '0,5', '0', '16', '0', '', '', 'a:7:{s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:13:\"category.html\";s:13:\"list_template\";s:9:\"list.html\";s:13:\"show_template\";s:9:\"show.html\";s:13:\"page_template\";s:9:\"page.html\";}', '2', '1');
INSERT INTO `yzn_category` VALUES ('17', '手机网站', 'mobile', '2', '2', '5', '0,5', '0', '17', '0', '', '', 'a:7:{s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:13:\"category.html\";s:13:\"list_template\";s:9:\"list.html\";s:13:\"show_template\";s:9:\"show.html\";s:13:\"page_template\";s:9:\"page.html\";}', '3', '1');
INSERT INTO `yzn_category` VALUES ('1', '关于我们', 'about', '3', '0', '0', '0', '1', '1,2,3,4', '0', '', 'cms/index/lists?catid=2', 'a:7:{s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:13:\"category.html\";s:13:\"list_template\";s:9:\"list.html\";s:13:\"show_template\";s:9:\"show.html\";s:13:\"page_template\";s:9:\"page.html\";}', '1', '1');


INSERT INTO `yzn_position` VALUES ('1', '0', '0', '首页幻灯片', '1500272764', '1500519504', '1');
INSERT INTO `yzn_position` VALUES ('2', '0', '0', '首页文字推荐', '1500272764', '1500519504', '2');
INSERT INTO `yzn_position` VALUES ('3', '0', '0', '首页图片推荐', '1500272764', '1500519504', '3');


INSERT INTO `yzn_model` VALUES ('1', '文章模型', 'article', '文章模型', '2', '1546574975', '1546574975', '0', '0', '1');
INSERT INTO `yzn_model` VALUES ('2', '图片模型', 'picture', '图片模型', '2', '1548754193', '1548754193', '0', '0', '1');
INSERT INTO `yzn_model` VALUES ('3', '产品模型', 'product', '产品模型', '2', '1549165800', '1549165800', '0', '0', '1');
INSERT INTO `yzn_model` VALUES ('4', '下载模型', 'download', '下载模型', '2', '1549624988', '1549624988', '0', '0', '1');
INSERT INTO `yzn_model` VALUES ('5', '留言本', 'guestbook', '留言本', '1', '1549625011', '1549625011', '1', '0', '1');


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
INSERT INTO `yzn_model_field` VALUES ('43', '4', 'id', '文档id', '', 'hidden', 'mediumint(8) UNSIGNED', '', '', '', '1', '1', '1', '0', '0', '1549624988', '1549624988', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('44', '4', 'catid', '栏目id', '', 'hidden', 'smallint(5) unsigned', '', '', '', '1', '1', '1', '0', '0', '1549624988', '1549624988', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('45', '4', 'title', '标题', '', 'text', 'varchar(255)', '', '', '', '1', '1', '0', '1', '1', '1549624988', '1549624988', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('46', '4', 'keywords', 'SEO关键词', '', 'text', 'varchar(255)', '', '', '{\"string\":{\"table\":\"tag\",\"key\":\"title\",\"delimiter\":\",\",\"where\":\"\",\"limit\":\"6\",\"order\":\"[rand]\"}}', '1', '1', '0', '0', '0', '1549624988', '1549624988', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('47', '4', 'description', 'SEO摘要', '', 'textarea', 'varchar(255)', '', '', '', '1', '1', '0', '0', '0', '1549624988', '1549624988', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('48', '4', 'uid', '用户id', '', 'number', 'mediumint(8) UNSIGNED', '', '1', '', '1', '0', '1', '0', '0', '1549624988', '1549624988', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('49', '4', 'posid', '推荐位', '', 'checkbox', 'tinyint(3) UNSIGNED', '', '', '', '1', '0', '1', '0', '0', '1549624988', '1549624988', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('50', '4', 'listorder', '排序', '', 'number', 'smallint(5) UNSIGNED', '', '100', '', '1', '1', '1', '0', '0', '1549624988', '1549624988', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('51', '4', 'status', '状态', '', 'radio', 'tinyint(1)', '0:禁用\r\n1:启用', '1', '', '1', '1', '1', '0', '0', '1549624988', '1549624988', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('52', '4', 'inputtime', '创建时间', '', 'datetime', 'int(11) UNSIGNED', '', '0', '', '1', '1', '1', '0', '0', '1549624988', '1549624988', '200', '1');
INSERT INTO `yzn_model_field` VALUES ('53', '4', 'updatetime', '更新时间', '', 'datetime', 'int(11) UNSIGNED', '', '0', '', '1', '0', '1', '0', '0', '1549624988', '1549624988', '200', '1');
INSERT INTO `yzn_model_field` VALUES ('54', '4', 'hits', '点击量', '', 'number', 'mediumint(8) UNSIGNED', '', '0', '', '1', '1', '1', '0', '0', '1549624988', '1549624988', '200', '1');
INSERT INTO `yzn_model_field` VALUES ('55', '4', 'did', '附表文档id', '', 'hidden', 'mediumint(8) UNSIGNED', '', '', '', '0', '0', '1', '0', '0', '1549624988', '1549624988', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('56', '4', 'content', '内容', '', 'Ueditor', 'text', '', '', '', '0', '1', '0', '0', '0', '1549624988', '1549624988', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('57', '5', 'id', '文档id', '', 'hidden', 'mediumint(8) UNSIGNED', '', '', '', '1', '1', '1', '0', '0', '1549625011', '1549625011', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('58', '5', 'catid', '栏目id', '', 'hidden', 'smallint(5) unsigned', '', '', '', '1', '1', '1', '0', '0', '1549625011', '1549625011', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('59', '5', 'title', '标题', '', 'text', 'varchar(255)', '', '', '', '1', '1', '0', '1', '1', '1549625011', '1549625011', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('60', '5', 'keywords', 'SEO关键词', '', 'text', 'varchar(255)', '', '', '{\"string\":{\"table\":\"tag\",\"key\":\"title\",\"delimiter\":\",\",\"where\":\"\",\"limit\":\"6\",\"order\":\"[rand]\"}}', '1', '1', '0', '0', '0', '1549625011', '1549625011', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('61', '5', 'description', 'SEO摘要', '', 'textarea', 'varchar(255)', '', '', '', '1', '1', '0', '0', '0', '1549625011', '1549625011', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('62', '5', 'uid', '用户id', '', 'number', 'mediumint(8) UNSIGNED', '', '1', '', '1', '0', '1', '0', '0', '1549625011', '1549625011', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('63', '5', 'posid', '推荐位', '', 'checkbox', 'tinyint(3) UNSIGNED', '', '', '', '1', '0', '1', '0', '0', '1549625011', '1549625011', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('64', '5', 'listorder', '排序', '', 'number', 'smallint(5) UNSIGNED', '', '100', '', '1', '1', '1', '0', '0', '1549625011', '1549625011', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('65', '5', 'status', '状态', '', 'radio', 'tinyint(1)', '0:禁用\r\n1:启用', '1', '', '1', '1', '1', '0', '0', '1549625011', '1549625011', '100', '1');
INSERT INTO `yzn_model_field` VALUES ('66', '5', 'inputtime', '创建时间', '', 'datetime', 'int(11) UNSIGNED', '', '0', '', '1', '1', '1', '0', '0', '1549625011', '1549625011', '200', '1');
INSERT INTO `yzn_model_field` VALUES ('67', '5', 'updatetime', '更新时间', '', 'datetime', 'int(11) UNSIGNED', '', '0', '', '1', '0', '1', '0', '0', '1549625011', '1549625011', '200', '1');
INSERT INTO `yzn_model_field` VALUES ('68', '5', 'hits', '点击量', '', 'number', 'mediumint(8) UNSIGNED', '', '0', '', '1', '1', '1', '0', '0', '1549625011', '1549625011', '200', '1');


INSERT INTO `yzn_article` VALUES ('1', '9', '让客户留住更长时间访问你的网站', '', '什么能让您的客户“一见钟情”？除了网站的界面，没有其他因素。网站的界面是非常重要的因素之一。因为这是客户访问网站时的第一印象。那时，您需要为客户提供一个吸引人且引人注目的界面。要做到这一点非常容易，你只需要有一个合理布局的界面，整洁不要分散读者的注意力。在与网站互动时，客户可以轻松搜索他们需要学习的信息。此外，您还可以使用一些额外的注释来使界面更加美观：首先，效果的最大效果用于避免分散用户的注意力。这些效果甚至会使网站更重，并且加载速度更慢。其次，您可以创建更多可用空间并消除不重要的信息，从而使关键消息更', '0', '100', '0', '0', '1550188136', '1550189862', '1');
INSERT INTO `yzn_article` VALUES ('2', '9', '移动网站需要吸引哪些观众并将其转化为客户', '', '在移动设备上设计网站以吸引观众并使他们成为他们的客户并不容易。移动网站是否只有两个友好元素，下载速度是否足够快？使用移动设备访问网站的人是那些时间很少的人，所以他们总是希望事情快速而正确。', '0', '100', '0', '0', '1550202861', '1550203878', '1');

INSERT INTO `yzn_article_data` VALUES ('1', '&lt;p&gt;&lt;strong&gt;通过网站让客户“一见钟情”&lt;/strong&gt;&lt;/p&gt;&lt;p&gt;什么能让您的客户“一见钟情”？除了网站的界面，没有其他因素。网站的界面是非常重要的因素之一。因为这是客户访问网站时的第一印象。那时，您需要为客户提供一个吸引人且引人注目的界面。要做到这一点非常容易，你只需要有一个合理布局的界面，整洁不要分散读者的注意力。在与网站互动时，客户可以轻松搜索他们需要学习的信息。此外，您还可以使用一些额外的注释来使界面更加美观：首先，效果的最大效果用于避免分散用户的注意力。这些效果甚至会使网站更重，并且加载速度更慢。其次，您可以创建更多可用空间并消除不重要的信息，从而使关键消息更容易，更快地到达客户。&amp;nbsp;&lt;/p&gt;&lt;p&gt;&lt;strong&gt;内容不仅要有回报，还应该精美呈现&lt;/strong&gt;&lt;/p&gt;&lt;p&gt;对客户有用的内容是让客户保持更长时间的首要因素之一。但是，不仅昂贵的信息足够，内容的呈现和格式是您的网站有更长的时间留在客户的技巧。您可以设计一个白色背景的网站，以便所有信息变得更加突出。绝对不要使用色彩鲜艳的花朵和图案的深色背景，因为它可能使读者难以获取信息。此外，字体用法和段落间距同样重要。选择的字体不应该太挑剔，时尚，但应该是简单，易于看到的字体和显示专业性。附加，线条之间应该是合理的距离，内容布局的段落更加开放。&lt;/p&gt;&lt;p&gt;&lt;strong&gt;优化网站以与所有设备兼容&lt;/strong&gt;&lt;/p&gt;&lt;p&gt;通常情况下，企业只能优化显示在计算机或笔记本电脑上，但往往会忽略各种其他重要设备，如智能手机或平板电脑等。但是，用户数量的情况随着移动设备的访问越来越多，新网站可确保与设备（包括移动设备）的兼容性。&amp;nbsp;&lt;/p&gt;&lt;p&gt;&lt;strong&gt;适当地浏览网站中的信息&lt;/strong&gt;&lt;/p&gt;&lt;p&gt;如果您通过主页给客户留下了深刻印象，客户一直渴望了解您的业务。为了使此过程更好地运行，您需要确保子页面的所有链接与前面提到的链接标题的内容一致。\r\n您还可以为关键位置的内容创建重音，以提高点击率。而且您也不要忘记确保您没有基本错误，例如链接到损坏的页面，丢失图像甚至丢失链接。&lt;/p&gt;&lt;p&gt;&lt;strong&gt;定期更新网站内容&lt;/strong&gt;&lt;/p&gt;&lt;p&gt;内容不需要质量，但在网站上也需要有数量。这里的金额并不意味着猖獗的金额，而是每天都是偶数。如果客户返回您的网站但仍然是旧内容，则您可能会失去客户，因为客户不想返回网站更新旧内容。\r\n有了这些提示，您需要立即更新缺少的元素以完成网站并留住客户。做好这些事情后，您会很快注意到您网站的跳出率大&lt;/p&gt;&lt;p&gt;&lt;em&gt;本文转载自传诚信&lt;/em&gt;&lt;/p&gt;');
INSERT INTO `yzn_article_data` VALUES ('2', '&lt;p&gt;在移动设备上设计网站以吸引观众并使他们成为他们的客户并不容易。移动网站是否只有两个友好元素，下载速度是否足够快？&lt;br/&gt;&lt;/p&gt;&lt;p&gt;使用移动设备访问网站的人是那些时间很少的人，所以他们总是希望事情快速而正确。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;&lt;strong&gt;1：并非计算机上显示的所有内容都需要显示在移动设备上&lt;/strong&gt;&lt;/p&gt;&lt;p&gt;很容易看出微小的移动屏幕不能像计算机那样宽，因此需要选择出现在移动屏幕上的网站内容。重要内容，您需要将它们推上去，以便它们可以显示在移动屏幕上&lt;/p&gt;&lt;p&gt;还需要选择移动屏幕上显示的网站内容&lt;br/&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;&lt;strong&gt;2：网站下载速度&lt;/strong&gt;&lt;/p&gt;&lt;p&gt;专业网站设计师应该始终关注的一件事是下载网站的速度。根据一项研究，谷歌正在研究，53％的用户将离开一个网站，如果下载需要超过3秒。提高网站的下载速度有时只是为了删除图像，减少图像的大小是网站可以更快下载。但是，有时原因比我们想象的更复杂，例如，原因来自网站代码，或者可能是因为您的网站开发的内容远远超过原始网站，而且当前主机不再响应了。&lt;/p&gt;&lt;p&gt;所以你需要找出你的网站下载速度慢的原因以及在哪里立即修复它。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;&lt;strong&gt;3：添加通话按钮&lt;/strong&gt;&lt;/p&gt;&lt;p&gt;对于移动用户，尤其是移动用户访问网站，他们说话的时间非常重要。因此，在查看产品信息之后，他们会立即打电话询问产品。但你确定他们会耐心等待撤回并找到你的电话号码吗？绝对不是。现在，移动屏幕上始终可用的简单呼叫按钮是可以立即按下客户想要呼叫的最全面的解决方案。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;&lt;strong&gt;4：集成返回顶部按钮&lt;/strong&gt;&lt;/p&gt;&lt;p&gt;对于网站，菜单始终是一个重要的导航栏，可帮助用户导航到网站内的子页面。对于某些网站，当用户向下滚动并阅读下面的内容时，此菜单栏将始终显示在屏幕上。但是，其他一些网站没有。因此，当用户在网站底部附近阅读时，想要查看菜单，他们必须进行大量冲浪，现在，返回顶部按钮将非常有效并让用户感到舒适。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;&lt;strong&gt;5：网站上的菜单&lt;/strong&gt;&lt;/p&gt;&lt;p&gt;与网站显示在计算机上的不同，手机上显示的网站菜单将减少到一行。当您想要查看时，用户将单击以显示子菜单。菜单图标现在是3个图块，但不是每个人都知道图标是菜单，因此如果您想让它更容易理解，您可以立即编写菜单字母。&lt;/p&gt;&lt;p&gt;&lt;em style=&quot;white-space: normal;&quot;&gt;本文转载自传诚信&lt;/em&gt;&lt;/p&gt;');


INSERT INTO `yzn_page` VALUES ('2', '关于我们', '', '', '<p>&nbsp; &nbsp; xxx网络科技股份有限公司是一家集策略咨询、创意创新、视觉设计、技术研发、内容制造、营销推广为一体的综合型数字化创新服务企业，其利用公司持续积累的核心技术和互联网思维，提供以互联网、移动互联网为核心的网络技术服务和互动整合营销服务，为传统企业实现“互联网+”升级提供整套解决方案。公司定位于中大型企业为核心客户群，可充分满足这一群体相比中小企业更为丰富、高端、多元的互联网数字综合需求。</p><p><br/></p><p>&nbsp; &nbsp; xxx网络科技股份有限公司作为一家互联网数字服务综合商，其主营业务包括移动互联网应用开发服务、数字互动整合营销服务、互联网网站建设综合服务和电子商务综合服务。</p><p><br/></p><p>&nbsp; &nbsp; xxx网络科技股份有限公司秉承实现全网价值营销的理念，通过实现互联网与移动互联网的精准数字营销和用户数据分析，日益深入到客户互联网技术建设及运维营销的方方面面，在帮助客户形成自身互联网运作体系的同时，有效对接BAT(百度，阿里，腾讯)等平台即百度搜索、阿里电商、腾讯微信，通过平台的推广来推进互联网综合服务，实现企业、用户、平台三者完美对接，并形成高效互动的枢纽，在帮助客户获取互联网高附加价值的同时获得自身的不断成长和壮大。</p>', '0', '0');
INSERT INTO `yzn_page` VALUES ('3', '企业文化', '', '', '<p>【愿景】</p><ul class=\" list-paddingleft-2\" style=\"list-style-type: disc;\"><li><p>不断倾听和满足用户需求，引导并超越用户需求，赢得用户尊敬</p></li><li><p>通过提升品牌形象，使员工具有高度企业荣誉感，赢得员工尊敬&nbsp;</p></li><li><p>推动互联网行业的健康发展，与合作伙伴共成长，赢得行业尊敬</p></li><li><p>注重企业责任，用心服务，关爱社会、回馈社会，赢得社会尊敬</p></li></ul><p><br/></p><p>【使命】</p><ul class=\" list-paddingleft-2\" style=\"list-style-type: disc;\"><li><p>使产品和服务像水和电融入人们的生活，为人们带来便捷和愉悦</p></li><li><p>关注不同地域、群体，并针对不同对象提供差异化的产品和服务</p></li><li><p>打造开放共赢平台，与合作伙伴共同营造健康的互联网生态环境</p></li></ul><p><br/></p><p>【管理理念】</p><ul class=\" list-paddingleft-2\" style=\"list-style-type: disc;\"><li><p>为员工提供良好的工作环境和激励机制&nbsp;</p></li><li><p>完善员工培养体系和职业发展通道，使员工与企业同步成长</p></li><li><p>充分尊重和信任员工，不断引导和鼓励，使其获得成就的喜悦</p></li></ul>', '0', '0');
