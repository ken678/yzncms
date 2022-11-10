CREATE TABLE IF NOT EXISTS `__PREFIX__article` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '文档ID',
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '栏目ID',
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '标题',
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '缩略图',
  `flag` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '属性',
  `keywords` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'SEO关键词',
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'SEO描述',
  `tags` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'Tags标签',
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '100' COMMENT '排序',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `username` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `sysadd` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否后台添加',
  `hits` mediumint(8) unsigned DEFAULT '0' COMMENT '点击量',
  `inputtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `url` varchar(100) NOT NULL DEFAULT '' COMMENT '链接地址',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `status` (`catid`,`status`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='文章模型模型表';

CREATE TABLE IF NOT EXISTS `__PREFIX__article_data` (
  `did` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `content` mediumtext COLLATE utf8_unicode_ci COMMENT '内容',
  PRIMARY KEY (`did`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='文章模型模型表';

CREATE TABLE IF NOT EXISTS `__PREFIX__picture` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '文档ID',
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '栏目ID',
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '标题',
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '缩略图',
  `flag` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '属性',
  `keywords` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'SEO关键词',
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'SEO描述',
  `tags` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'Tags标签',
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '100' COMMENT '排序',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `username` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `sysadd` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否后台添加',
  `hits` mediumint(8) unsigned DEFAULT '0' COMMENT '点击量',
  `inputtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `url` varchar(100) NOT NULL DEFAULT '' COMMENT '链接地址',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `status` (`catid`,`status`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='图片模型模型表';

CREATE TABLE IF NOT EXISTS `__PREFIX__picture_data` (
  `did` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `content` mediumtext COLLATE utf8_unicode_ci COMMENT '内容',
  PRIMARY KEY (`did`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='图片模型模型表';

CREATE TABLE IF NOT EXISTS `__PREFIX__download` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '文档ID',
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '栏目ID',
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '标题',
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '缩略图',
  `flag` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '属性',
  `keywords` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'SEO关键词',
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'SEO描述',
  `tags` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'Tags标签',
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '100' COMMENT '排序',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `username` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户名',
  `sysadd` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否后台添加',
  `hits` mediumint(8) unsigned DEFAULT '0' COMMENT '点击量',
  `inputtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `url` varchar(100) NOT NULL DEFAULT '' COMMENT '链接地址',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `status` (`catid`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='下载模型模型表';

CREATE TABLE IF NOT EXISTS `__PREFIX__download_data` (
  `did` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `content` mediumtext COLLATE utf8_unicode_ci COMMENT '内容',
  PRIMARY KEY (`did`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='下载模型模型表';

CREATE TABLE IF NOT EXISTS `__PREFIX__product` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '文档ID',
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '栏目ID',
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '标题',
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '缩略图',
  `flag` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '属性',
  `keywords` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'SEO关键词',
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'SEO描述',
  `tags` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'Tags标签',
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '100' COMMENT '排序',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `username` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `sysadd` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否后台添加',
  `hits` mediumint(8) unsigned DEFAULT '0' COMMENT '点击量',
  `inputtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `url` varchar(100) NOT NULL DEFAULT '' COMMENT '链接地址',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  `type` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '类型',
  `trade` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '行业',
  `price` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '价格',
  PRIMARY KEY (`id`),
  KEY `status` (`catid`,`status`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='产品模型模型表';

CREATE TABLE IF NOT EXISTS `__PREFIX__product_data` (
  `did` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `content` mediumtext COLLATE utf8_unicode_ci COMMENT '内容',
  PRIMARY KEY (`did`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='产品模型模型表';

INSERT INTO `__PREFIX__category` VALUES (2, '公司简介', 'Introduction', 1, 0, 1, '0,1', '2', 0, '', '', '', '', 0, 'a:4:{s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"page_template\";s:9:\"page.html\";}', 1, 1);
INSERT INTO `__PREFIX__category` VALUES (3, '企业文化', 'culture', 1, 0, 1, '0,1', '3', 0, '', '', '', '', 0, 'a:4:{s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"page_template\";s:9:\"page.html\";}', 2, 1);
INSERT INTO `__PREFIX__category` VALUES (4, '公司荣誉', 'honor', 2, 2, 1, '0,1', '4', 0, '', '', '', '', 5, 'a:7:{s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:21:\"category_picture.html\";s:13:\"list_template\";s:17:\"list_picture.html\";s:13:\"show_template\";s:17:\"show_picture.html\";s:13:\"page_template\";s:9:\"page.html\";}', 3, 1);
INSERT INTO `__PREFIX__category` VALUES (5, '案例展示', 'case', 2, 3, 0, '0', '5', 0, '', '', '', '', 9, 'a:7:{s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:18:\"category_case.html\";s:13:\"list_template\";s:14:\"list_case.html\";s:13:\"show_template\";s:17:\"show_picture.html\";s:13:\"page_template\";s:9:\"page.html\";}', 2, 1);
INSERT INTO `__PREFIX__category` VALUES (6, '新闻中心', 'news', 2, 1, 0, '0', '6,9,10,14', 1, '', '', '', '', 0, 'a:7:{s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:13:\"category.html\";s:13:\"list_template\";s:9:\"list.html\";s:13:\"show_template\";s:9:\"show.html\";s:13:\"page_template\";s:9:\"page.html\";}', 1, 1);
INSERT INTO `__PREFIX__category` VALUES (8, '联系我们', 'contact', 1, 0, 0, '0', '8,18,19', 1, '', '', '', 'cms/index/lists?catid=18', 0, 'a:4:{s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"page_template\";s:9:\"page.html\";}', 0, 1);
INSERT INTO `__PREFIX__category` VALUES (9, '网络营销', 'marketing', 2, 1, 6, '0,6', '9', 0, '', '', '', '', 2, 'a:6:{s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:13:\"category.html\";s:13:\"list_template\";s:9:\"list.html\";s:13:\"show_template\";s:9:\"show.html\";}', 1, 1);
INSERT INTO `__PREFIX__category` VALUES (10, '网站知识', 'knowledge', 2, 1, 6, '0,6', '10', 0, '', '', '', '', 2, 'a:7:{s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:13:\"category.html\";s:13:\"list_template\";s:9:\"list.html\";s:13:\"show_template\";s:9:\"show.html\";s:13:\"page_template\";s:9:\"page.html\";}', 2, 1);
INSERT INTO `__PREFIX__category` VALUES (14, '备案知识', 'record', 2, 1, 6, '0,6', '14', 0, '', '', '', '', 2, 'a:7:{s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:13:\"category.html\";s:13:\"list_template\";s:9:\"list.html\";s:13:\"show_template\";s:9:\"show.html\";s:13:\"page_template\";s:9:\"page.html\";}', 3, 1);
INSERT INTO `__PREFIX__category` VALUES (1, '关于我们', 'about', 1, 0, 0, '0', '1,2,3,4', 1, '', '', '', 'cms/index/lists?catid=2', 0, 'a:4:{s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"page_template\";s:9:\"page.html\";}', 3, 1);
INSERT INTO `__PREFIX__category` VALUES (18, '联系方式', 'fangshi', 1, 0, 8, '0,8', '18', 0, '', '', '', '', 0, 'a:4:{s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"page_template\";s:9:\"page.html\";}', 0, 1);

INSERT INTO `__PREFIX__model` VALUES (1, 'cms', '文章模型', 'article', '文章模型', 'a:3:{s:17:\"category_template\";s:13:\"category.html\";s:13:\"list_template\";s:9:\"list.html\";s:13:\"show_template\";s:9:\"show.html\";}', 2, 1546574975, 1566893866, 0, 1);
INSERT INTO `__PREFIX__model` VALUES (2, 'cms', '图片模型', 'picture', '图片模型', 'a:3:{s:17:\"category_template\";s:21:\"category_picture.html\";s:13:\"list_template\";s:17:\"list_picture.html\";s:13:\"show_template\";s:17:\"show_picture.html\";}', 2, 1548754193, 1566896531, 0, 1);
INSERT INTO `__PREFIX__model` VALUES (3, 'cms', '产品模型', 'product', '产品模型', 'a:3:{s:17:\"category_template\";s:21:\"category_picture.html\";s:13:\"list_template\";s:17:\"list_picture.html\";s:13:\"show_template\";s:17:\"show_picture.html\";}', 2, 1549165800, 1566894329, 0, 1);
INSERT INTO `__PREFIX__model` VALUES (4, 'cms', '下载模型', 'download', '下载模型', 'a:3:{s:17:\"category_template\";s:13:\"category.html\";s:13:\"list_template\";s:9:\"list.html\";s:13:\"show_template\";s:9:\"show.html\";}', 2, 1549624988, 1566894292, 0, 1);

INSERT INTO `__PREFIX__model_field` VALUES (76, 1, 'url', '链接地址', '有值时生效，内部链接格式:模块/控制器/操作?参数=参数值&...，外部链接则必须http://开头', '', '', 'text', 'a:3:{s:6:\"define\";s:32:\"varchar(100) NOT NULL DEFAULT \'\'\";s:7:\"options\";s:0:\"\";s:5:\"value\";s:0:\"\";}', 1, 0, 1, 0, 0, 0, 1608023551, 1608023551, 255, 1);
INSERT INTO `__PREFIX__model_field` VALUES (77, 2, 'url', '链接地址', '有值时生效，内部链接格式:模块/控制器/操作?参数=参数值&...，外部链接则必须http://开头', '', '', 'text', 'a:3:{s:6:\"define\";s:32:\"varchar(100) NOT NULL DEFAULT \'\'\";s:7:\"options\";s:0:\"\";s:5:\"value\";s:0:\"\";}', 1, 0, 1, 0, 0, 0, 1608023551, 1608023551, 255, 1);
INSERT INTO `__PREFIX__model_field` VALUES (78, 3, 'url', '链接地址', '有值时生效，内部链接格式:模块/控制器/操作?参数=参数值&...，外部链接则必须http://开头', '', '', 'text', 'a:3:{s:6:\"define\";s:32:\"varchar(100) NOT NULL DEFAULT \'\'\";s:7:\"options\";s:0:\"\";s:5:\"value\";s:0:\"\";}', 1, 0, 1, 0, 0, 0, 1608023551, 1608023551, 255, 1);
INSERT INTO `__PREFIX__model_field` VALUES (79, 4, 'url', '链接地址', '有值时生效，内部链接格式:模块/控制器/操作?参数=参数值&...，外部链接则必须http://开头', '', '', 'text', 'a:3:{s:6:\"define\";s:32:\"varchar(100) NOT NULL DEFAULT \'\'\";s:7:\"options\";s:0:\"\";s:5:\"value\";s:0:\"\";}', 1, 0, 1, 0, 0, 0, 1608023551, 1608023551, 255, 1);
INSERT INTO `__PREFIX__model_field` VALUES (74, 3, 'thumb', '缩略图', '', '', '', 'image', 'a:3:{s:6:\"define\";s:13:\"text NOT NULL\";s:7:\"options\";s:0:\"\";s:5:\"value\";s:0:\"\";}', 1, 0, 0, 0, 0, 1, 1565948216, 1565948216, 275, 1);
INSERT INTO `__PREFIX__model_field` VALUES (75, 4, 'thumb', '缩略图', '', '', '', 'image', 'a:3:{s:6:\"define\";s:13:\"text NOT NULL\";s:7:\"options\";s:0:\"\";s:5:\"value\";s:0:\"\";}', 1, 0, 0, 0, 0, 1, 1565948216, 1565948216, 275, 1);
INSERT INTO `__PREFIX__model_field` VALUES (73, 2, 'thumb', '缩略图', '', '', '', 'image', 'a:3:{s:6:\"define\";s:13:\"text NOT NULL\";s:7:\"options\";s:0:\"\";s:5:\"value\";s:0:\"\";}', 1, 0, 0, 0, 0, 1, 1565948216, 1565948216, 275, 1);
INSERT INTO `__PREFIX__model_field` VALUES (70, 4, 'did', '附表文档id', '', '', '', 'hidden', '', 0, 1, 1, 0, 0, 0, 1549624988, 1549624988, 100, 1);
INSERT INTO `__PREFIX__model_field` VALUES (71, 4, 'content', '内容', '', '', '', 'Ueditor', 'a:3:{s:6:\"define\";s:13:\"text NOT NULL\";s:7:\"options\";s:0:\"\";s:5:\"value\";s:0:\"\";}', 0, 0, 0, 0, 0, 1, 1549624988, 1549624988, 270, 1);
INSERT INTO `__PREFIX__model_field` VALUES (72, 1, 'thumb', '缩略图', '', '', '', 'image', 'a:3:{s:6:\"define\";s:13:\"text NOT NULL\";s:7:\"options\";s:0:\"\";s:5:\"value\";s:0:\"\";}', 1, 0, 0, 0, 0, 1, 1565948216, 1565948216, 275, 1);
INSERT INTO `__PREFIX__model_field` VALUES (69, 4, 'hits', '点击量', '', '', '', 'number', 'a:3:{s:6:\"define\";s:42:\"mediumint(8) UNSIGNED NOT NULL DEFAULT \'0\'\";s:7:\"options\";s:0:\"\";s:5:\"value\";s:1:\"0\";}', 1, 0, 1, 0, 0, 0, 1549624988, 1549624988, 265, 1);
INSERT INTO `__PREFIX__model_field` VALUES (68, 4, 'updatetime', '更新时间', '', '', '', 'datetime', 'a:3:{s:6:\"define\";s:37:\"int(10) UNSIGNED NOT NULL DEFAULT \'0\'\";s:7:\"options\";s:0:\"\";s:5:\"value\";s:0:\"\";}', 1, 1, 1, 0, 0, 0, 1549624988, 1549624988, 245, 1);
INSERT INTO `__PREFIX__model_field` VALUES (66, 4, 'status', '状态', '', '', '', 'radio', 'a:3:{s:6:\"define\";s:40:\"tinyint(2) UNSIGNED NOT NULL DEFAULT \'0\'\";s:7:\"options\";s:21:\"0:待审核\r\n1:通过\";s:5:\"value\";s:1:\"1\";}', 1, 0, 1, 0, 0, 0, 1549624988, 1549624988, 240, 1);
INSERT INTO `__PREFIX__model_field` VALUES (67, 4, 'inputtime', '创建时间', '', '', '', 'datetime', 'a:3:{s:6:\"define\";s:37:\"int(10) UNSIGNED NOT NULL DEFAULT \'0\'\";s:7:\"options\";s:0:\"\";s:5:\"value\";s:0:\"\";}', 1, 0, 1, 0, 0, 0, 1549624988, 1549624988, 250, 1);
INSERT INTO `__PREFIX__model_field` VALUES (62, 4, 'uid', '用户id', '', '', '', 'number', NULL, 1, 1, 1, 0, 0, 0, 1549624988, 1549624988, 100, 1);
INSERT INTO `__PREFIX__model_field` VALUES (63, 4, 'username', '用户名', '', '', '', 'text', NULL, 1, 1, 1, 0, 0, 0, 1558767044, 1558767044, 100, 1);
INSERT INTO `__PREFIX__model_field` VALUES (64, 4, 'sysadd', '是否后台添加', '', '', '', 'number', NULL, 1, 1, 1, 0, 0, 0, 1558767044, 1558767044, 100, 1);
INSERT INTO `__PREFIX__model_field` VALUES (65, 4, 'listorder', '排序', '', '', '', 'number', 'a:3:{s:6:\"define\";s:40:\"tinyint(3) UNSIGNED NOT NULL DEFAULT \'0\'\";s:7:\"options\";s:0:\"\";s:5:\"value\";s:3:\"100\";}', 1, 0, 1, 0, 0, 0, 1549624988, 1549624988, 260, 1);
INSERT INTO `__PREFIX__model_field` VALUES (60, 4, 'description', 'SEO摘要', '如不填写，则自动截取附表中编辑器的200字符', '', '', 'textarea', 'a:3:{s:6:\"define\";s:32:\"varchar(255) NOT NULL DEFAULT \'\'\";s:7:\"options\";s:0:\"\";s:5:\"value\";s:0:\"\";}', 1, 0, 0, 0, 0, 1, 1549624988, 1549624988, 285, 1);
INSERT INTO `__PREFIX__model_field` VALUES (61, 4, 'tags', 'Tags标签', '关键词用回车确认', '', '', 'tags', 'a:3:{s:6:\"define\";s:32:\"varchar(255) NOT NULL DEFAULT \'\'\";s:7:\"options\";s:0:\"\";s:5:\"value\";s:0:\"\";}', 1, 0, 0, 0, 0, 0, 1546574975, 1546574975, 280, 1);
INSERT INTO `__PREFIX__model_field` VALUES (59, 4, 'keywords', 'SEO关键词', '关键词用回车确认', '', '', 'tags', 'a:3:{s:6:\"define\";s:32:\"varchar(255) NOT NULL DEFAULT \'\'\";s:7:\"options\";s:0:\"\";s:5:\"value\";s:0:\"\";}', 1, 0, 0, 0, 0, 1, 1549624988, 1549624988, 290, 1);
INSERT INTO `__PREFIX__model_field` VALUES (58, 4, 'flag', '属性', '', '', '', 'checkbox', 'a:3:{s:6:\"define\";s:31:\"varchar(32) NOT NULL DEFAULT \'\'\";s:7:\"options\";s:76:\"1:置顶[1]\r\n2:头条[2]\r\n3:特荐[3]\r\n4:推荐[4]\r\n5:热点[5]\r\n6:幻灯[6]\";s:5:\"value\";s:0:\"\";}', 1, 0, 1, 0, 0, 0, 1551846870, 1551846870, 295, 1);
INSERT INTO `__PREFIX__model_field` VALUES (55, 4, 'id', '文档id', '', '', '', 'hidden', '', 1, 0, 1, 0, 0, 1, 1549624988, 1549624988, 100, 1);
INSERT INTO `__PREFIX__model_field` VALUES (56, 4, 'catid', '栏目id', '', '', '', 'hidden', '', 1, 0, 1, 0, 0, 1, 1549624988, 1549624988, 100, 1);
INSERT INTO `__PREFIX__model_field` VALUES (57, 4, 'title', '标题', '', '', '', 'text', 'a:3:{s:6:\"define\";s:32:\"varchar(255) NOT NULL DEFAULT \'\'\";s:7:\"options\";s:0:\"\";s:5:\"value\";s:0:\"\";}', 1, 0, 1, 1, 1, 1, 1549624988, 1549624988, 300, 1);
INSERT INTO `__PREFIX__model_field` VALUES (54, 3, 'price', '价格', '', '', '', 'radio', 'a:4:{s:6:\"define\";s:40:\"tinyint(2) UNSIGNED NOT NULL DEFAULT \'0\'\";s:7:\"options\";s:42:\"1:≤2500\r\n2:≤5000\r\n3:≤8000\r\n4:≥1万\";s:10:\"filtertype\";s:1:\"1\";s:5:\"value\";s:0:\"\";}', 1, 0, 0, 1, 0, 1, 1552372433, 1552372433, 0, 1);
INSERT INTO `__PREFIX__model_field` VALUES (53, 3, 'trade', '行业', '', '', '', 'radio', 'a:4:{s:6:\"define\";s:40:\"tinyint(2) UNSIGNED NOT NULL DEFAULT \'0\'\";s:7:\"options\";s:78:\"1:机械设备\r\n2:车辆物流\r\n3:地产建筑装修\r\n4:教育培训\r\n5:其他\";s:10:\"filtertype\";s:1:\"1\";s:5:\"value\";s:0:\"\";}', 1, 0, 0, 1, 0, 1, 1552372387, 1552372387, 0, 1);
INSERT INTO `__PREFIX__model_field` VALUES (50, 3, 'did', '附表文档id', '', '', '', 'hidden', '', 0, 1, 1, 0, 0, 0, 1549165800, 1549165800, 100, 1);
INSERT INTO `__PREFIX__model_field` VALUES (51, 3, 'content', '内容', '', '', '', 'Ueditor', 'a:3:{s:6:\"define\";s:13:\"text NOT NULL\";s:7:\"options\";s:0:\"\";s:5:\"value\";s:0:\"\";}', 0, 0, 0, 0, 0, 1, 1549165800, 1549165800, 270, 1);
INSERT INTO `__PREFIX__model_field` VALUES (52, 3, 'type', '类型', '', '', '', 'radio', 'a:4:{s:6:\"define\";s:40:\"tinyint(2) UNSIGNED NOT NULL DEFAULT \'0\'\";s:7:\"options\";s:91:\"1:营销网站\r\n2:电商网站\r\n3:响应式网站\r\n4:手机网站\r\n5:外贸网站\r\n6:其他\";s:10:\"filtertype\";s:1:\"1\";s:5:\"value\";s:0:\"\";}', 1, 0, 0, 1, 0, 1, 1552368369, 1552372294, 0, 1);
INSERT INTO `__PREFIX__model_field` VALUES (48, 3, 'updatetime', '更新时间', '', '', '', 'datetime', 'a:3:{s:6:\"define\";s:37:\"int(10) UNSIGNED NOT NULL DEFAULT \'0\'\";s:7:\"options\";s:0:\"\";s:5:\"value\";s:0:\"\";}', 1, 1, 1, 0, 0, 0, 1549165800, 1549165800, 245, 1);
INSERT INTO `__PREFIX__model_field` VALUES (49, 3, 'hits', '点击量', '', '', '', 'number', 'a:3:{s:6:\"define\";s:42:\"mediumint(8) UNSIGNED NOT NULL DEFAULT \'0\'\";s:7:\"options\";s:0:\"\";s:5:\"value\";s:1:\"0\";}', 1, 0, 1, 0, 0, 0, 1549165800, 1549165800, 265, 1);
INSERT INTO `__PREFIX__model_field` VALUES (47, 3, 'inputtime', '创建时间', '', '', '', 'datetime', 'a:3:{s:6:\"define\";s:37:\"int(10) UNSIGNED NOT NULL DEFAULT \'0\'\";s:7:\"options\";s:0:\"\";s:5:\"value\";s:0:\"\";}', 1, 0, 1, 0, 0, 0, 1549165800, 1549165800, 250, 1);
INSERT INTO `__PREFIX__model_field` VALUES (44, 3, 'sysadd', '是否后台添加', '', '', '', 'number', NULL, 1, 1, 1, 0, 0, 0, 1558767044, 1558767044, 100, 1);
INSERT INTO `__PREFIX__model_field` VALUES (45, 3, 'listorder', '排序', '', '', '', 'number', 'a:3:{s:6:\"define\";s:40:\"tinyint(3) UNSIGNED NOT NULL DEFAULT \'0\'\";s:7:\"options\";s:0:\"\";s:5:\"value\";s:3:\"100\";}', 1, 0, 1, 0, 0, 0, 1549165800, 1549165800, 260, 1);
INSERT INTO `__PREFIX__model_field` VALUES (46, 3, 'status', '状态', '', '', '', 'radio', 'a:3:{s:6:\"define\";s:40:\"tinyint(2) UNSIGNED NOT NULL DEFAULT \'0\'\";s:7:\"options\";s:21:\"0:待审核\r\n1:通过\";s:5:\"value\";s:1:\"1\";}', 1, 0, 1, 0, 0, 0, 1549165800, 1549165800, 240, 1);
INSERT INTO `__PREFIX__model_field` VALUES (42, 3, 'uid', '用户id', '', '', '', 'number', NULL, 1, 1, 1, 0, 0, 0, 1549165800, 1549165800, 100, 1);
INSERT INTO `__PREFIX__model_field` VALUES (43, 3, 'username', '用户名', '', '', '', 'text', NULL, 1, 1, 1, 0, 0, 0, 1558767044, 1558767044, 100, 1);
INSERT INTO `__PREFIX__model_field` VALUES (41, 3, 'tags', 'Tags标签', '关键词用回车确认', '', '', 'tags', 'a:3:{s:6:\"define\";s:32:\"varchar(255) NOT NULL DEFAULT \'\'\";s:7:\"options\";s:0:\"\";s:5:\"value\";s:0:\"\";}', 1, 0, 0, 0, 0, 0, 1546574975, 1546574975, 280, 1);
INSERT INTO `__PREFIX__model_field` VALUES (40, 3, 'description', 'SEO摘要', '如不填写，则自动截取附表中编辑器的200字符', '', '', 'textarea', 'a:3:{s:6:\"define\";s:32:\"varchar(255) NOT NULL DEFAULT \'\'\";s:7:\"options\";s:0:\"\";s:5:\"value\";s:0:\"\";}', 1, 0, 0, 0, 0, 1, 1549165800, 1549165800, 285, 1);
INSERT INTO `__PREFIX__model_field` VALUES (39, 3, 'keywords', 'SEO关键词', '关键词用回车确认', '', '', 'tags', 'a:3:{s:6:\"define\";s:32:\"varchar(255) NOT NULL DEFAULT \'\'\";s:7:\"options\";s:0:\"\";s:5:\"value\";s:0:\"\";}', 1, 0, 0, 0, 0, 1, 1549165800, 1549165800, 290, 1);
INSERT INTO `__PREFIX__model_field` VALUES (36, 3, 'catid', '栏目id', '', '', '', 'hidden', '', 1, 0, 1, 0, 0, 1, 1549165800, 1549165800, 100, 1);
INSERT INTO `__PREFIX__model_field` VALUES (37, 3, 'title', '标题', '', '', '', 'text', 'a:3:{s:6:\"define\";s:32:\"varchar(255) NOT NULL DEFAULT \'\'\";s:7:\"options\";s:0:\"\";s:5:\"value\";s:0:\"\";}', 1, 0, 1, 1, 1, 1, 1549165800, 1549165800, 300, 1);
INSERT INTO `__PREFIX__model_field` VALUES (38, 3, 'flag', '属性', '', '', '', 'checkbox', 'a:3:{s:6:\"define\";s:31:\"varchar(32) NOT NULL DEFAULT \'\'\";s:7:\"options\";s:76:\"1:置顶[1]\r\n2:头条[2]\r\n3:特荐[3]\r\n4:推荐[4]\r\n5:热点[5]\r\n6:幻灯[6]\";s:5:\"value\";s:0:\"\";}', 1, 0, 1, 0, 0, 0, 1551846870, 1551846870, 295, 1);
INSERT INTO `__PREFIX__model_field` VALUES (33, 2, 'did', '附表文档id', '', '', '', 'hidden', '', 0, 1, 1, 0, 0, 0, 1548754192, 1548754192, 100, 1);
INSERT INTO `__PREFIX__model_field` VALUES (34, 2, 'content', '内容', '', '', '', 'Ueditor', 'a:3:{s:6:\"define\";s:13:\"text NOT NULL\";s:7:\"options\";s:0:\"\";s:5:\"value\";s:0:\"\";}', 0, 0, 0, 0, 0, 1, 1548754192, 1548754192, 270, 1);
INSERT INTO `__PREFIX__model_field` VALUES (35, 3, 'id', '文档id', '', '', '', 'hidden', '', 1, 0, 1, 0, 0, 1, 1549165800, 1549165800, 100, 1);
INSERT INTO `__PREFIX__model_field` VALUES (32, 2, 'hits', '点击量', '', '', '', 'number', 'a:3:{s:6:\"define\";s:42:\"mediumint(8) UNSIGNED NOT NULL DEFAULT \'0\'\";s:7:\"options\";s:0:\"\";s:5:\"value\";s:1:\"0\";}', 1, 0, 1, 0, 0, 0, 1548754192, 1548754192, 265, 1);
INSERT INTO `__PREFIX__model_field` VALUES (31, 2, 'updatetime', '更新时间', '', '', '', 'datetime', 'a:3:{s:6:\"define\";s:37:\"int(10) UNSIGNED NOT NULL DEFAULT \'0\'\";s:7:\"options\";s:0:\"\";s:5:\"value\";s:0:\"\";}', 1, 1, 1, 0, 0, 0, 1548754192, 1548754192, 245, 1);
INSERT INTO `__PREFIX__model_field` VALUES (30, 2, 'inputtime', '创建时间', '', '', '', 'datetime', 'a:3:{s:6:\"define\";s:37:\"int(10) UNSIGNED NOT NULL DEFAULT \'0\'\";s:7:\"options\";s:0:\"\";s:5:\"value\";s:0:\"\";}', 1, 0, 1, 0, 0, 0, 1548754192, 1548754192, 250, 1);
INSERT INTO `__PREFIX__model_field` VALUES (29, 2, 'status', '状态', '', '', '', 'radio', 'a:3:{s:6:\"define\";s:40:\"tinyint(2) UNSIGNED NOT NULL DEFAULT \'0\'\";s:7:\"options\";s:21:\"0:待审核\r\n1:通过\";s:5:\"value\";s:1:\"1\";}', 1, 0, 1, 0, 0, 0, 1548754192, 1548754192, 240, 1);
INSERT INTO `__PREFIX__model_field` VALUES (27, 2, 'sysadd', '是否后台添加', '', '', '', 'number', NULL, 1, 1, 1, 0, 0, 0, 1558767044, 1558767044, 100, 1);
INSERT INTO `__PREFIX__model_field` VALUES (28, 2, 'listorder', '排序', '', '', '', 'number', 'a:3:{s:6:\"define\";s:40:\"tinyint(3) UNSIGNED NOT NULL DEFAULT \'0\'\";s:7:\"options\";s:0:\"\";s:5:\"value\";s:3:\"100\";}', 1, 0, 1, 0, 0, 0, 1548754192, 1548754192, 260, 1);
INSERT INTO `__PREFIX__model_field` VALUES (24, 2, 'tags', 'Tags标签', '关键词用回车确认', '', '', 'tags', 'a:3:{s:6:\"define\";s:32:\"varchar(255) NOT NULL DEFAULT \'\'\";s:7:\"options\";s:0:\"\";s:5:\"value\";s:0:\"\";}', 1, 0, 0, 0, 0, 0, 1546574975, 1546574975, 280, 1);
INSERT INTO `__PREFIX__model_field` VALUES (25, 2, 'uid', '用户id', '', '', '', 'number', NULL, 1, 1, 1, 0, 0, 0, 1548754192, 1548754192, 100, 1);
INSERT INTO `__PREFIX__model_field` VALUES (26, 2, 'username', '用户名', '', '', '', 'text', NULL, 1, 1, 1, 0, 0, 0, 1558767044, 1558767044, 100, 1);
INSERT INTO `__PREFIX__model_field` VALUES (23, 2, 'description', 'SEO摘要', '如不填写，则自动截取附表中编辑器的200字符', '', '', 'textarea', 'a:3:{s:6:\"define\";s:32:\"varchar(255) NOT NULL DEFAULT \'\'\";s:7:\"options\";s:0:\"\";s:5:\"value\";s:0:\"\";}', 1, 0, 0, 0, 0, 1, 1548754192, 1548754192, 285, 1);
INSERT INTO `__PREFIX__model_field` VALUES (22, 2, 'keywords', 'SEO关键词', '关键词用回车确认', '', '', 'tags', 'a:3:{s:6:\"define\";s:32:\"varchar(255) NOT NULL DEFAULT \'\'\";s:7:\"options\";s:0:\"\";s:5:\"value\";s:0:\"\";}', 1, 0, 0, 0, 0, 1, 1548754192, 1548754192, 290, 1);
INSERT INTO `__PREFIX__model_field` VALUES (20, 2, 'title', '标题', '', '', '', 'text', 'a:3:{s:6:\"define\";s:32:\"varchar(255) NOT NULL DEFAULT \'\'\";s:7:\"options\";s:0:\"\";s:5:\"value\";s:0:\"\";}', 1, 0, 1, 1, 1, 1, 1548754192, 1548754192, 300, 1);
INSERT INTO `__PREFIX__model_field` VALUES (21, 2, 'flag', '属性', '', '', '', 'checkbox', 'a:3:{s:6:\"define\";s:31:\"varchar(32) NOT NULL DEFAULT \'\'\";s:7:\"options\";s:76:\"1:置顶[1]\r\n2:头条[2]\r\n3:特荐[3]\r\n4:推荐[4]\r\n5:热点[5]\r\n6:幻灯[6]\";s:5:\"value\";s:0:\"\";}', 1, 0, 1, 0, 0, 0, 1551846870, 1551846870, 295, 1);
INSERT INTO `__PREFIX__model_field` VALUES (6, 1, 'description', 'SEO摘要', '如不填写，则自动截取附表中编辑器的200字符', '', '', 'textarea', 'a:3:{s:6:\"define\";s:32:\"varchar(255) NOT NULL DEFAULT \'\'\";s:7:\"options\";s:0:\"\";s:5:\"value\";s:0:\"\";}', 1, 0, 0, 0, 0, 1, 1546574975, 1546574975, 285, 1);
INSERT INTO `__PREFIX__model_field` VALUES (7, 1, 'tags', 'Tags标签', '关键词用回车确认', '', '', 'tags', 'a:3:{s:6:\"define\";s:32:\"varchar(255) NOT NULL DEFAULT \'\'\";s:7:\"options\";s:0:\"\";s:5:\"value\";s:0:\"\";}', 1, 0, 0, 0, 0, 0, 1546574975, 1546574975, 280, 1);
INSERT INTO `__PREFIX__model_field` VALUES (8, 1, 'uid', '用户id', '', '', '', 'number', NULL, 1, 1, 1, 0, 0, 0, 1546574975, 1546574975, 100, 1);
INSERT INTO `__PREFIX__model_field` VALUES (9, 1, 'username', '用户名', '', '', '', 'text', NULL, 1, 1, 1, 0, 0, 0, 1558767044, 1558767044, 100, 1);
INSERT INTO `__PREFIX__model_field` VALUES (10, 1, 'sysadd', '是否后台添加', '', '', '', 'number', NULL, 1, 1, 1, 0, 0, 0, 1558767044, 1558767044, 100, 1);
INSERT INTO `__PREFIX__model_field` VALUES (11, 1, 'listorder', '排序', '', '', '', 'number', 'a:3:{s:6:\"define\";s:40:\"tinyint(3) UNSIGNED NOT NULL DEFAULT \'0\'\";s:7:\"options\";s:0:\"\";s:5:\"value\";s:3:\"100\";}', 1, 0, 1, 0, 0, 0, 1546574975, 1546574975, 260, 1);
INSERT INTO `__PREFIX__model_field` VALUES (12, 1, 'status', '状态', '', '', '', 'radio', 'a:3:{s:6:\"define\";s:40:\"tinyint(2) UNSIGNED NOT NULL DEFAULT \'0\'\";s:7:\"options\";s:21:\"0:待审核\r\n1:通过\";s:5:\"value\";s:1:\"1\";}', 1, 0, 1, 0, 0, 0, 1546574975, 1546574975, 240, 1);
INSERT INTO `__PREFIX__model_field` VALUES (13, 1, 'inputtime', '创建时间', '', '', '', 'datetime', 'a:3:{s:6:\"define\";s:37:\"int(10) UNSIGNED NOT NULL DEFAULT \'0\'\";s:7:\"options\";s:0:\"\";s:5:\"value\";s:0:\"\";}', 1, 0, 1, 0, 0, 0, 1546574975, 1546574975, 250, 1);
INSERT INTO `__PREFIX__model_field` VALUES (14, 1, 'updatetime', '更新时间', '', '', '', 'datetime', 'a:3:{s:6:\"define\";s:37:\"int(10) UNSIGNED NOT NULL DEFAULT \'0\'\";s:7:\"options\";s:0:\"\";s:5:\"value\";s:0:\"\";}', 1, 1, 1, 0, 0, 0, 1546574975, 1546574975, 245, 1);
INSERT INTO `__PREFIX__model_field` VALUES (15, 1, 'hits', '点击量', '', '', '', 'number', 'a:3:{s:6:\"define\";s:42:\"mediumint(8) UNSIGNED NOT NULL DEFAULT \'0\'\";s:7:\"options\";s:0:\"\";s:5:\"value\";s:1:\"0\";}', 1, 0, 1, 0, 0, 0, 1546574975, 1546574975, 265, 1);
INSERT INTO `__PREFIX__model_field` VALUES (16, 1, 'did', '附表文档id', '', '', '', 'hidden', '', 0, 1, 1, 0, 0, 0, 1546574975, 1546574975, 100, 1);
INSERT INTO `__PREFIX__model_field` VALUES (17, 1, 'content', '内容', '', '', '', 'Ueditor', 'a:3:{s:6:\"define\";s:13:\"text NOT NULL\";s:7:\"options\";s:0:\"\";s:5:\"value\";s:0:\"\";}', 0, 0, 0, 0, 0, 1, 1546574975, 1546574975, 270, 1);
INSERT INTO `__PREFIX__model_field` VALUES (18, 2, 'id', '文档id', '', '', '', 'hidden', '', 1, 0, 1, 0, 0, 1, 1548754192, 1548754192, 100, 1);
INSERT INTO `__PREFIX__model_field` VALUES (19, 2, 'catid', '栏目id', '', '', '', 'hidden', '', 1, 0, 1, 0, 0, 1, 1548754192, 1548754192, 100, 1);
INSERT INTO `__PREFIX__model_field` VALUES (1, 1, 'id', '文档id', '', '', '', 'hidden', '', 1, 0, 1, 0, 0, 1, 1546574975, 1546574975, 100, 1);
INSERT INTO `__PREFIX__model_field` VALUES (2, 1, 'catid', '栏目id', '', '', '', 'hidden', '', 1, 0, 1, 0, 0, 1, 1546574975, 1546574975, 100, 1);
INSERT INTO `__PREFIX__model_field` VALUES (3, 1, 'title', '标题', '', '', '', 'text', 'a:3:{s:6:\"define\";s:32:\"varchar(255) NOT NULL DEFAULT \'\'\";s:7:\"options\";s:0:\"\";s:5:\"value\";s:0:\"\";}', 1, 0, 1, 1, 1, 1, 1546574975, 1546574975, 300, 1);
INSERT INTO `__PREFIX__model_field` VALUES (4, 1, 'flag', '属性', '', '', '', 'checkbox', 'a:3:{s:6:\"define\";s:31:\"varchar(32) NOT NULL DEFAULT \'\'\";s:7:\"options\";s:76:\"1:置顶[1]\r\n2:头条[2]\r\n3:特荐[3]\r\n4:推荐[4]\r\n5:热点[5]\r\n6:幻灯[6]\";s:5:\"value\";s:0:\"\";}', 1, 0, 1, 0, 0, 0, 1551846870, 1551846870, 295, 1);
INSERT INTO `__PREFIX__model_field` VALUES (5, 1, 'keywords', 'SEO关键词', '关键词用回车确认', '', '', 'tags', 'a:3:{s:6:\"define\";s:32:\"varchar(255) NOT NULL DEFAULT \'\'\";s:7:\"options\";s:0:\"\";s:5:\"value\";s:0:\"\";}', 1, 0, 0, 0, 0, 1, 1546574975, 1546574975, 290, 1);

INSERT INTO `__PREFIX__article` VALUES (1, 9, '让客户留住更长时间访问你的网站', '', '', '', '什么能让您的客户“一见钟情”？除了网站的界面，没有其他因素。网站的界面是非常重要的因素之一。因为这是客户访问网站时的第一印象。那时，您需要为客户提供一个吸引人且引人注目的界面。要做到这一点非常容易，你只需要有一个合理布局的界面，整洁不要分散读者的注意力。在与网站互动时，客户可以轻松搜索他们需要学习的信息。此外，您还可以使用一些额外的注释来使界面更加美观：首先，效果的最大效果用于避免分散用户的注意力。这些效果甚至会使网站更重，并且加载速度更慢。其次，您可以创建更多可用空间并消除不重要的信息，从而使关键消息更', '', 100, 1, 'admin', 1, 0, 1550188136, 1550476672,'', 1);
INSERT INTO `__PREFIX__article` VALUES (2, 9, '移动网站需要吸引哪些观众并将其转化为客户', '', '', '', '在移动设备上设计网站以吸引观众并使他们成为他们的客户并不容易。移动网站是否只有两个友好元素，下载速度是否足够快？使用移动设备访问网站的人是那些时间很少的人，所以他们总是希望事情快速而正确。', '', 100, 1, 'admin', 1, 0, 1550202861, 1550450153, '',1);
INSERT INTO `__PREFIX__article` VALUES (3, 14, '空壳网站是什么？如何避免成为空壳网站？空壳网站怎么处理？', '', '', '', '一、备案数据，包括：主体信息、网站信息、接入信息。\r\n（1）主体信息是指，网站主办者（网站开办者）的注册信息。\r\n（2）网站信息是指，网站主办者开办的（一个或多个）网站的注册信息。\r\n（3）接入信息是指，网站主办者（每个）网站的数据存放的虚拟空间的接入信息。', '', 100, 1, 'admin', 1, 0, 1550448808, 1550476816,'', 1);
INSERT INTO `__PREFIX__article` VALUES (4, 14, '单位或网站涉及金融类关键词，办理网站备案注意事项', '', '', '', '1.根据《国务院办公厅关于印发互联网金融风险专项整治工作实施方案的通知（国办发〔2016〕21号）》要求，公司注册名称或经营范围中使用“交易所”、“交易中心”、“金融”、“资产管理”、“理财”、“基金”、“基金管理”、“投资管理（包括投资）”、“财富管理”、“股权投资基金”、“网贷”、“网络借贷”、“P2P”、“股权众筹”、“互联网保险”、“支付”、“信托”等字样的企业，在做网站备案业务办理时，需提供金融管理部门的专项审批文件。', '', 100, 1, 'admin', 1, 0, 1550449235, 1550449733, '',1);
INSERT INTO `__PREFIX__article` VALUES (5, 10, '个人建设网站有哪些步骤？', '', '', '', '虽然互联网上付费提供网站建设和网站制作服务的公司或者个人有很多，都是为企业或者个人提供网站建设和网页设计服务的，但是对于那些刚刚走出校门或者刚刚参加工作的朋友来说，如果想通过互联网创业，想要做一个自己的网站，但是又没有明确的经营理念，只是想要尝试一下互联网创业，这时候大部分人都会选择自己建网站，一方面是为了能够节省较高的网站建设费用，另一方面也可以简单的学习一些网站建设或网站制作的一些基本知识，那么自己建网站到底应该如何入手呢今天小编就跟大家写一篇自己建网站的全攻略，希望能够帮助那些想要自己建网站的朋友有', '', 100, 1, 'admin', 1, 0, 1550449817, 1550476910, '',1);
INSERT INTO `__PREFIX__article` VALUES (6, 10, '企业建设手机网站注意的事项？', '', '', '', ' 虽然很多企业都专门弄起了APP软件，不过从综合层面来说，还是网站更加靠谱一些，因为网站比制作APP成本要低廉很多，而且受传统思维习惯的影响，大部分的会主动寻找相关内容的人来说，他们还是更加习惯利用搜索引擎去进行寻找。并且这一群人在社会上面也拥有一定的社会经验以及地位，像是销售人员、采购人员等等，如果他们不再办公室，正好在上班途中或者是出差途中的话，肯定是需要使用手机来搜索某些信息的，所以从实用性角度来看的话，反倒是企业网站比APP更好一些，那么，企业建设手机网站的时候要注意什么?', '', 100, 1, 'admin', 1, 0, 1550450424, 1550476700, '',1);

INSERT INTO `__PREFIX__article_data` VALUES (1, '&lt;p&gt;&lt;strong&gt;通过网站让客户“一见钟情”&lt;/strong&gt;&lt;/p&gt;&lt;p&gt;什么能让您的客户“一见钟情”？除了网站的界面，没有其他因素。网站的界面是非常重要的因素之一。因为这是客户访问网站时的第一印象。那时，您需要为客户提供一个吸引人且引人注目的界面。要做到这一点非常容易，你只需要有一个合理布局的界面，整洁不要分散读者的注意力。在与网站互动时，客户可以轻松搜索他们需要学习的信息。此外，您还可以使用一些额外的注释来使界面更加美观：首先，效果的最大效果用于避免分散用户的注意力。这些效果甚至会使网站更重，并且加载速度更慢。其次，您可以创建更多可用空间并消除不重要的信息，从而使关键消息更容易，更快地到达客户。&amp;nbsp;&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;&lt;strong&gt;内容不仅要有回报，还应该精美呈现&lt;/strong&gt;&lt;/p&gt;&lt;p&gt;对客户有用的内容是让客户保持更长时间的首要因素之一。但是，不仅昂贵的信息足够，内容的呈现和格式是您的网站有更长的时间留在客户的技巧。您可以设计一个白色背景的网站，以便所有信息变得更加突出。绝对不要使用色彩鲜艳的花朵和图案的深色背景，因为它可能使读者难以获取信息。此外，字体用法和段落间距同样重要。选择的字体不应该太挑剔，时尚，但应该是简单，易于看到的字体和显示专业性。附加，线条之间应该是合理的距离，内容布局的段落更加开放。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;&lt;strong&gt;优化网站以与所有设备兼容&lt;/strong&gt;&lt;/p&gt;&lt;p&gt;通常情况下，企业只能优化显示在计算机或笔记本电脑上，但往往会忽略各种其他重要设备，如智能手机或平板电脑等。但是，用户数量的情况随着移动设备的访问越来越多，新网站可确保与设备（包括移动设备）的兼容性。&amp;nbsp;&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;&lt;strong&gt;适当地浏览网站中的信息&lt;/strong&gt;&lt;/p&gt;&lt;p&gt;如果您通过主页给客户留下了深刻印象，客户一直渴望了解您的业务。为了使此过程更好地运行，您需要确保子页面的所有链接与前面提到的链接标题的内容一致。\r\n您还可以为关键位置的内容创建重音，以提高点击率。而且您也不要忘记确保您没有基本错误，例如链接到损坏的页面，丢失图像甚至丢失链接。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;&lt;strong&gt;定期更新网站内容&lt;/strong&gt;&lt;/p&gt;&lt;p&gt;内容不需要质量，但在网站上也需要有数量。这里的金额并不意味着猖獗的金额，而是每天都是偶数。如果客户返回您的网站但仍然是旧内容，则您可能会失去客户，因为客户不想返回网站更新旧内容。\r\n有了这些提示，您需要立即更新缺少的元素以完成网站并留住客户。做好这些事情后，您会很快注意到您网站的跳出率大&lt;/p&gt;');
INSERT INTO `__PREFIX__article_data` VALUES (2, '&lt;p&gt;在移动设备上设计网站以吸引观众并使他们成为他们的客户并不容易。移动网站是否只有两个友好元素，下载速度是否足够快？&lt;br/&gt;&lt;/p&gt;&lt;p&gt;使用移动设备访问网站的人是那些时间很少的人，所以他们总是希望事情快速而正确。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;&lt;strong&gt;1：并非计算机上显示的所有内容都需要显示在移动设备上&lt;/strong&gt;&lt;/p&gt;&lt;p&gt;很容易看出微小的移动屏幕不能像计算机那样宽，因此需要选择出现在移动屏幕上的网站内容。重要内容，您需要将它们推上去，以便它们可以显示在移动屏幕上&lt;/p&gt;&lt;p&gt;还需要选择移动屏幕上显示的网站内容&lt;br/&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;&lt;strong&gt;2：网站下载速度&lt;/strong&gt;&lt;/p&gt;&lt;p&gt;专业网站设计师应该始终关注的一件事是下载网站的速度。根据一项研究，谷歌正在研究，53％的用户将离开一个网站，如果下载需要超过3秒。提高网站的下载速度有时只是为了删除图像，减少图像的大小是网站可以更快下载。但是，有时原因比我们想象的更复杂，例如，原因来自网站代码，或者可能是因为您的网站开发的内容远远超过原始网站，而且当前主机不再响应了。&lt;/p&gt;&lt;p&gt;所以你需要找出你的网站下载速度慢的原因以及在哪里立即修复它。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;&lt;strong&gt;3：添加通话按钮&lt;/strong&gt;&lt;/p&gt;&lt;p&gt;对于移动用户，尤其是移动用户访问网站，他们说话的时间非常重要。因此，在查看产品信息之后，他们会立即打电话询问产品。但你确定他们会耐心等待撤回并找到你的电话号码吗？绝对不是。现在，移动屏幕上始终可用的简单呼叫按钮是可以立即按下客户想要呼叫的最全面的解决方案。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;&lt;strong&gt;4：集成返回顶部按钮&lt;/strong&gt;&lt;/p&gt;&lt;p&gt;对于网站，菜单始终是一个重要的导航栏，可帮助用户导航到网站内的子页面。对于某些网站，当用户向下滚动并阅读下面的内容时，此菜单栏将始终显示在屏幕上。但是，其他一些网站没有。因此，当用户在网站底部附近阅读时，想要查看菜单，他们必须进行大量冲浪，现在，返回顶部按钮将非常有效并让用户感到舒适。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;&lt;strong&gt;5：网站上的菜单&lt;/strong&gt;&lt;/p&gt;&lt;p&gt;与网站显示在计算机上的不同，手机上显示的网站菜单将减少到一行。当您想要查看时，用户将单击以显示子菜单。菜单图标现在是3个图块，但不是每个人都知道图标是菜单，因此如果您想让它更容易理解，您可以立即编写菜单字母。&lt;/p&gt;');
INSERT INTO `__PREFIX__article_data` VALUES (6, '&lt;p&gt;&amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp;虽然很多企业都专门弄起了APP软件，不过从综合层面来说，还是网站更加靠谱一些，因为网站比制作APP成本要低廉很多，而且受传统思维习惯的影响，大部分的会主动寻找相关内容的人来说，他们还是更加习惯利用搜索引擎去进行寻找。并且这一群人在社会上面也拥有一定的社会经验以及地位，像是销售人员、采购人员等等，如果他们不再办公室，正好在上班途中或者是出差途中的话，肯定是需要使用手机来搜索某些信息的，所以从实用性角度来看的话，反倒是企业网站比APP更好一些，那么，企业建设手机网站的时候要注意什么?&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;&lt;strong&gt;&amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp;第一、图片设计&lt;/strong&gt;&lt;/p&gt;&lt;p&gt;  虽然当前的手机企业网站在建设的时候都是弄成的响应式网站，它可以按照上网具体设备的不同，而自动调整成符合当前屏幕大小的格式，但是只要稍微留心一些，就会发现，就算是那些超级大型的网站，他们在图片处理方面都已经十分谨慎了，还是会出现因为图片出现一些问题，因为只要出现图片就会消耗流量，另外如果图片太多的话，也会导致企业网站页面的加载速度变得非常慢，导致用户体验严重受到影响，因此在不是特别有必要的情况之下，最好还是少使用图片比较合适。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;  &lt;strong&gt;第二、页面简洁&lt;/strong&gt;&lt;/p&gt;&lt;p&gt;  既然是建设手机企业网站，那在设计的时候建议还是弄得简单一些更合适，不需要像电脑PC端的网站一样弄很多的内容，因为手机本身的屏幕就要比PC端小很多，如果手机企业网站建设的时候设计很多的内容，会导致人们浏览起来变得比较困难的，特别是用内容作为主导倾向的网站，建设成简洁的形式，更加容易让网友找到自己需要的信息&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;  &lt;strong&gt;第三、断点功能&lt;/strong&gt;&lt;/p&gt;&lt;p&gt;  对于移动网站的断点设置，在CSS模式样式中，都支持断点功能设置，而传统PC端网站就缺少这个功能设置，所以经常会出现网站显示不合理，大量乱码等现象。但是，网站断点功能并非就保证网站访问流畅，对于断点技术的研究，还在进一步探讨中，比如说，在移动设备显示不错的网站，可是反过来用PC端却显示紊乱，在特别注重移动端网站的时候，也要注意到传统网站的感受，只要这样全兼容的设计，才符合未来网站的发展方向。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;');
INSERT INTO `__PREFIX__article_data` VALUES (3, '&lt;p&gt;一、备案数据，包括：主体信息、网站信息、接入信息。&lt;/p&gt;&lt;p&gt;（1）主体信息是指，网站主办者（网站开办者）的注册信息。&lt;/p&gt;&lt;p&gt;（2）网站信息是指，网站主办者开办的（一个或多个）网站的注册信息。&lt;/p&gt;&lt;p&gt;（3）接入信息是指，网站主办者（每个）网站的数据存放的虚拟空间的接入信息。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;二、空壳类备案数据，包括:空壳主体和空壳网站。&lt;/p&gt;&lt;p&gt;（1）空壳主体是指，在工业和信息化部备案系统中，网站主办者的历史备案信息只存在主体信息，没有网站信息和接入信息。&lt;/p&gt;&lt;p&gt;（2）空壳网站是指，在工业和信息化部备案系统中，网站主办者的历史备案信息中含有主体信息和网站信息，但（一个或多个网站）没有接入信息（即网站有备案号，但由于网站实际使用空间IP地址变更，之前空间接入商已将网站的备案信息取消接入，同时网站主办者并没有在新的空间接入商办理备案信息转接入）。&lt;/p&gt;&lt;p&gt;&amp;nbsp; &amp;nbsp; &amp;nbsp; 通俗来讲，空壳网站是指，用户域名绑定IP发生变更（主要是更换了不同空间接入商IP），但备案信息没有及时变更，因此就变成了空壳网站。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;三、空壳类备案数据处理方式。&lt;/p&gt;&lt;p&gt;（1）若网站主办者存在空壳主体信息，则唯一解决方式：&lt;/p&gt;&lt;p&gt;&amp;nbsp; &amp;nbsp; &amp;nbsp; 需网站主办者携带相关证件到网站实际的接入商重新办理备案。已被注销（收回）的备案号及备案信息无法恢复。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;四、如何避免成为空壳类数据。&lt;/p&gt;&lt;p&gt;（1）不可随意变更域名绑定IP（若需变更请及时联系网站实际使用的空间接入商）；&lt;/p&gt;&lt;p&gt;以此避免因未及时变更网站备案接入信息，而成为空壳类备案数据，从而被当地省通信管理局注销（收回）备案号。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;五、办理网站备案真实性核验，请网站负责人携带以下材料：&lt;/p&gt;&lt;p&gt;（1）本人身份证原件&lt;/p&gt;&lt;p&gt;（2）单位有效证件（含年检页）原件&lt;/p&gt;&lt;p&gt;（3）企业法人身份证原件&lt;/p&gt;&lt;p&gt;（4）单位公章&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;');
INSERT INTO `__PREFIX__article_data` VALUES (4, '&lt;ol class=&quot; list-paddingleft-2&quot; style=&quot;list-style-type: decimal;&quot;&gt;&lt;li&gt;&lt;p&gt;根据《国务院办公厅关于印发互联网金融风险专项整治工作实施方案的通知（国办发〔2016〕21号）》要求，公司注册名称或经营范围中使用“交易所”、“交易中心”、“金融”、“资产管理”、“理财”、“基金”、“基金管理”、“投资管理（包括投资）”、“财富管理”、“股权投资基金”、“网贷”、“网络借贷”、“P2P”、“股权众筹”、“互联网保险”、“支付”、“信托”等字样的企业，在做网站备案业务办理时，需提供金融管理部门的专项审批文件。&lt;/p&gt;&lt;/li&gt;&lt;/ol&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;2.无相关金融许可的不允许接入。若网站内容确实和金融活动无关的，需要用户更改公司注册名称或经营范围，否则不予备案。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;3.对于上述存量网站，备案中心将会不定期进行核查，一旦发现违规从事金融活动，将直接予以注销备案号处置。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;4.（仅供参考）涉及金融类业务相关许可证办理部门：&lt;/p&gt;&lt;p&gt;&amp;nbsp; ① p2p网站需要金融办和银监会两家一起发的许可证；&lt;/p&gt;&lt;p&gt;&amp;nbsp; ② 股票、公募基金是证监会发的证；&lt;/p&gt;&lt;p&gt;&amp;nbsp; ③ 私募基金是证券协会的备案（股权投资指的就是私募基金）；&lt;/p&gt;&lt;p&gt;&amp;nbsp; ④ 小额贷款是银监会发证；&lt;/p&gt;&lt;p&gt;&amp;nbsp; ⑤ 第三方支付是人民银行发证；&lt;/p&gt;&lt;p&gt;&amp;nbsp; ⑥ 保险是保监会发证；&lt;/p&gt;&lt;p&gt;&amp;nbsp; ⑦ 金融机构发证比如银行什么的都是银监会；&lt;/p&gt;&lt;p&gt;&amp;nbsp; ⑧ 证券公司发证都是证监会；&lt;/p&gt;&lt;p&gt;&amp;nbsp; ⑨ 信托公司是银监会；&lt;/p&gt;');
INSERT INTO `__PREFIX__article_data` VALUES (5, '&lt;p&gt;&amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp;虽然互联网上付费提供网站建设和网站制作服务的公司或者个人有很多，都是为企业或者个人提供网站建设和网页设计服务的，但是对于那些刚刚走出校门或者刚刚参加工作的朋友来说，如果想通过互联网创业，想要做一个自己的网站，但是又没有明确的经营理念，只是想要尝试一下互联网创业，这时候大部分人都会选择自己建网站，一方面是为了能够节省较高的网站建设费用，另一方面也可以简单的学习一些网站建设或网站制作的一些基本知识，那么自己建网站到底应该如何入手呢今天小编就跟大家写一篇自己建网站的全攻略，希望能够帮助那些想要自己建网站的朋友有一个系统的认识和了解。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;&lt;strong&gt;1、了解基础的脚本语言&lt;/strong&gt;&lt;/p&gt;&lt;p&gt;  无论你是打算用网络上分享出来的免费源代码做网站还是用自助建站系统来建网站，首先应该学习和了解的就是网站前台脚本语言，网站前台脚本语言主要是html/js/css这三种，其中html是客户端网页源代码的主要语言，js脚本语言用来实现各种网页特效，css脚本语言用来实现网站的各种布局及网页色调的调整。&lt;/p&gt;&lt;p&gt;  相对于php、java等编程语言来说，脚本语言更加容易记忆和学习，所以一般想要接触网站建设的朋友都应该首先学习和认识上边说道的三种脚本语言。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;&lt;strong&gt;2、免费的源码程序&lt;/strong&gt;&lt;/p&gt;&lt;p&gt;  对于刚学网站建设的朋友来说，自己建网站肯定不可能上来就自己写一套强大的网站CMS系统，这几乎是不可能的，而且也是不现实的，毕竟一套功能强大的网站管理系统往往都是很多人开发测试很久才能完成的，依靠一个人的力量快速的完全一套建站系统显然难度很大，所以这就需要借助网络上已经分享出来的免费网站源码程序来快速完成自己建网站的目的。&lt;/p&gt;&lt;p&gt;  自建网站虽然除了使用免费的源码程序还可以通过选择一些免费的自助建站平台来完成，但是小编这里推荐大家使用免费的源码程序来做网站，这样一方面是保证网站最终的控制权在自己手里，另一方面也有助于更好的提升自己的网站建设的认识和熟悉，如果你使用自助建站平台，永远都不可能明白网站开发的基本框架设计，但是你通过研究别人分享出来的免费源码就可以很好的掌握整个程序的框架结构和页面设计方面的一些知识，能够更好的提升自己的专业技能。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;&lt;strong&gt;3、服务器及域名&lt;/strong&gt;&lt;/p&gt;&lt;p&gt;  通过学习第一步骤的那些前台脚本语言，然后按照第二步骤说的去下载和研究别人的源码程序，相信很快你就可以自己建网站了，但是建立好的网站如果只是在本地运行，那只有你自己可以访问和看到，如何才能让网络上的所有人都看到自己做的网站呢，这就涉及到了网站的发布，网站发布就需要使用服务器和域名，这时候就需要我们去接触服务器和域名。&lt;/p&gt;&lt;p&gt;  虽然说大部分的新手自己建网站都不希望花费太高的成本，但是服务器和域名的成本是每一个做网站的人都要承担的，而且一个稳定的服务器直接影响到你网站将来的打开速度、网站性能及搜索引擎收录情况，所以建议新手们在购买服务器的时候还是要选择性价比比较高的服务器。&lt;/p&gt;&lt;p&gt;  哦，最后还得补充一下，要想自己建网站，除了上边说道的这些都必须要学习和了解之外，还有两个重要的软件需要下载安装和学习怎么使用，一个是Dreamweaver(简称DW)另一个就是Photoshop(简称PS)这两款软件即使你不打算自己开发设计，你就是研究和修改别人的源码程序也一样需要用到，比如你在步骤二中需要修改别人的网页代码，肯定需要用DW打开网页文件来编辑，需要修改别人程序的中图标或者图片就肯定需要使用PS来作图，所以这两款软件也是自己建网站过程中必须要学习使用的。&lt;/p&gt;');

INSERT INTO `__PREFIX__picture` VALUES (1, 4, 'ISO9001证书', '', '', '', '', '', 100, 1, 'admin', 1, 0, 1550552511, 1550554247, '',1);
INSERT INTO `__PREFIX__picture` VALUES (2, 4, 'ISO14001证书', '', '', '', '', '', 100, 1, 'admin', 1, 0, 1550554284, 1550554288, '',1);
INSERT INTO `__PREFIX__picture` VALUES (3, 4, 'OHSAS18001证书', '', '', '', '', '', 100, 1, 'admin', 1, 0, 1550554298, 1550554301,'', 1);
INSERT INTO `__PREFIX__picture` VALUES (4, 4, '企业信用等级评价', '', '', '', '', '', 100, 1, 'admin', 1, 0, 1550554307, 1550554309,'', 1);
INSERT INTO `__PREFIX__picture` VALUES (5, 4, '企业荣誉证书', '', '', '', '', '', 100, 1, 'admin', 1, 0, 1550554314, 1550554316, '',1);

INSERT INTO `__PREFIX__product` VALUES (1, 5, '苏州欧泊**机电进出口有限公司', '', '', '', '苏州欧伯**机电进出口有限公司成立于2014年，是德国HUK/DOPAG/METER MIX定量注脂、打胶产品正式授权的代理商，另经销德国CAPTRON、NORELEM等众多国外知名品牌，可为客户提供从技术咨询、产品销售、技术支持到售后服务的全程服务。公司自成立以来，一直致力于为客户提供德国及欧美地区原产的各类工业备品、备件，并确保100%原厂全新正品。', '注脂,打胶', 100, 1, 'admin', 1, 0, 1552365964, 1553067407,'', 1, 4, 1, 1);
INSERT INTO `__PREFIX__product` VALUES (4, 5, '南通红*居餐饮管理有限公司', '', '4', '', '南通红*居餐饮管理有限公司是一家具有自己的厨师团队，具备丰富的经验，专业承包及管理企事业机关单位、学校、医院、大型工业园区、工厂，建筑工地、写字楼的食堂及营养配餐等后勤项目的大型餐饮承包企业，在上海、江苏、浙江、都有设立公司营业部。团队有500余人，并可为各企业提供专业厨师团队、厨工，勤杂工，免费厨房餐饮管理;餐饮服务，保洁服务，家庭服务；停车场管理服务；劳务派遣经营；绿化维护，食品经营;婚庆礼仪服务;会...', '餐饮管理,婚庆礼仪,劳务派遣', 100, 1, 'admin', 1, 0, 1552366803, 1553067319,'', 1, 3, 5, 3);
INSERT INTO `__PREFIX__product` VALUES (2, 5, '海安华**仓储有限公司', '', '', '', '海安华**仓储有限公司主要以海安物流、仓储为主。 公司秉承“诚信经营、服务至上”的核心价值观；先进的物流理念和丰富的物流操作经验，为不同客户量身定做及提供专业物流方案和优质、高效的物流服务，从而帮客户降低成本，提升市场竞争力。　　公司已和众多知名企业携手合作，共创辉煌；承运范围涵盖了化工、机械、建材、纺织、电子电器、食品、制药、高科技产品等各行各业。', '物流服务', 100, 1, 'admin', 1, 0, 1552366267, 1553067377,'', 1, 5, 2, 3);
INSERT INTO `__PREFIX__product` VALUES (3, 5, '苏州领*线教育科技有限公司', '', '4', '', '苏州领*线教育科技有限公司是一家专注于高品质少儿培训和智能课程开发、提供精品化与专业化相结合的少儿教育科技机构。我们多年来，始终致力于将我们的课堂打造成为孩子快乐成长的乐园与成功的起点，并且成为江苏校外教育的品牌。我们已在苏州市区、常熟、张家港、吴江、昆山、太仓、常州、无锡、杭州、江西九江、内蒙古通辽、山东淄博等地成功开办了教学基地。', '校外教育,智能课程', 100, 1, 'admin', 1, 0, 1552366358, 1553067339, '',1, 3, 4, 4);
INSERT INTO `__PREFIX__product` VALUES (5, 5, '苏州威*莱斯升降机械设备有限公司', '', '4', '', '苏州威*莱斯升降机械设备有限公司是专业提供各类升降平台出租租赁服务的厂家，自公司创立以来，经过长期对液压升降机、升降平台、高空作业车的研制和探索，积累了丰富的专业经验。凭借丰富的专业技术及不断开拓创新的精神，致力于产品的开发和创新，以自身专业特长，创造高品质的产品。', '机械设备,升降机,租赁服务', 100, 1, 'admin', 1, 0, 1552366872, 1553067296, '',1, 3, 1, 2);
INSERT INTO `__PREFIX__product` VALUES (6, 5, '苏州非*搬运包装有限公司', '', '4', '', '苏州非*搬运安装有限公司于2016年7月在苏州工业园区这块沸腾的热土上重组合并挂牌成立。随着经济的发展,，公司秉承创新、创优、与时共进的原则把原有四家搬运公司重组洗牌、强强联手。作为一家专业承接各类精密机器设备搬运、装卸、安装定位工程的大企业，公司装备齐全、技术力量雄厚。施工人员均受过国家安全生产监督部门正规的专业培训，持有相应的资格证书，对各种精密机器设备的搬运、安装工程有着丰富的工作经验。施工车辆都是...', '精密机器,专业培训,资格证书', 100, 1, 'admin', 1, 0, 1552366920, 1553067267,'', 1, 1, 5, 1);
INSERT INTO `__PREFIX__product` VALUES (7, 5, '上海朗**业科技有限公司', '', '', '', '上海朗**业科技有限公司，成立于2016年，致力于进口国外先进的工业设备、仪器和耗材。团队成员均由长期从事科学研究、仪器销售与服务人士组成，有着丰富的售前和售后服务经验，可为国内科研、工业客户提供一站式解决方案。“朗助工业，喜翼科技！”一直以来都是团队的奋斗方向, 多年的经营与市场反馈赢得了市场客户的信赖。', '', 100, 1, 'admin', 1, 0, 1589007525, 1589007618, '',1, 3, 4, 4);
INSERT INTO `__PREFIX__product` VALUES (8, 5, '盐城万**力资源有限公司', '', '', '', '公司以严守合同，诚信服务与客户为本企业的基本操守，坚持“真诚  高效 共赢”的文化理念，先后在城市周边、射阳、大丰、阜宁、徐州、昆山等地设办事处，依托强大、广泛的社会资源和一支团结奋进、具有良好亲和力以及丰富人力资源服务实践和管理经验的团队', '', 100, 1, 'admin', 1, 0, 1589007678, 1589007750,'', 1, 3, 4, 2);
INSERT INTO `__PREFIX__product` VALUES (9, 5, '江苏森**设集团有限公司', '', '', '', '江苏森**设集团有限公司是按照中华人民共和国公司法，于2006 年9 月5 日经过企业改制重组，在江苏省盐城市工商行政管理局亭湖分局注册成立，其性质为有限责任公司，注册资金10088万元。', '', 100, 1, 'admin', 1, 0, 1589007934, 1589008001,'', 1, 1, 1, 2);

INSERT INTO `__PREFIX__product_data` VALUES (1, '&lt;p&gt;苏州欧伯**机电进出口有限公司成立于2014年，是德国HUK/DOPAG/METER MIX定量注脂、打胶产品正式授权的代理商，另经销德国CAPTRON、NORELEM等众多国外知名品牌，可为客户提供从技术咨询、产品销售、技术支持到售后服务的全程服务。公司自成立以来，一直致力于为客户提供德国及欧美地区原产的各类工业备品、备件，并确保100%原厂全新正品。&lt;/p&gt;');
INSERT INTO `__PREFIX__product_data` VALUES (4, '&lt;p&gt;南通红*居餐饮管理有限公司是一家具有自己的厨师团队，具备丰富的经验，专业承包及管理企事业机关单位、学校、医院、大型工业园区、工厂，建筑工地、写字楼的食堂及营养配餐等后勤项目的大型餐饮承包企业，在上海、江苏、浙江、都有设立公司营业部。团队有500余人，并可为各企业提供专业厨师团队、厨工，勤杂工，免费厨房餐饮管理;餐饮服务，保洁服务，家庭服务；停车场管理服务；劳务派遣经营；绿化维护，食品经营;婚庆礼仪服务;会务服务;展览展示服务;厨房设备及用品、餐具、办公用品的销售、餐厅规划设计，厨具设计、出售、安装等服务&lt;/p&gt;');
INSERT INTO `__PREFIX__product_data` VALUES (2, '&lt;p&gt;海安华**仓储有限公司主要以海安物流、仓储为主。 公司秉承“诚信经营、服务至上”的核心价值观；先进的物流理念和丰富的物流操作经验，为不同客户量身定做及提供专业物流方案和优质、高效的物流服务，从而帮客户降低成本，提升市场竞争力。\r\n　　公司已和众多知名企业携手合作，共创辉煌；承运范围涵盖了化工、机械、建材、纺织、电子电器、食品、制药、高科技产品等各行各业。&lt;/p&gt;');
INSERT INTO `__PREFIX__product_data` VALUES (3, '&lt;p&gt;苏州领*线教育科技有限公司是一家专注于高品质少儿培训和智能课程开发、提供精品化与专业化相结合的少儿教育科技机构。我们多年来，始终致力于将我们的课堂打造成为孩子快乐成长的乐园与成功的起点，并且成为江苏校外教育的品牌。我们已在苏州市区、常熟、张家港、吴江、昆山、太仓、常州、无锡、杭州、江西九江、内蒙古通辽、山东淄博等地成功开办了教学基地。&lt;/p&gt;');
INSERT INTO `__PREFIX__product_data` VALUES (5, '&lt;p&gt;苏州威*莱斯升降机械设备有限公司是专业提供各类升降平台出租租赁服务的厂家，自公司创立以来，经过长期对液压升降机、升降平台、高空作业车的研制和探索，积累了丰富的专业经验。凭借丰富的专业技术及不断开拓创新的精神，致力于产品的开发和创新，以自身专业特长，创造高品质的产品。&lt;/p&gt;');
INSERT INTO `__PREFIX__product_data` VALUES (6, '&lt;p&gt;苏州非*搬运安装有限公司于2016年7月在苏州工业园区这块沸腾的热土上重组合并挂牌成立。随着经济的发展,，公司秉承创新、创优、与时共进的原则把原有四家搬运公司重组洗牌、强强联手。作为一家专业承接各类精密机器设备搬运、装卸、安装定位工程的大企业，公司装备齐全、技术力量雄厚。施工人员均受过国家安全生产监督部门正规的专业培训，持有相应的资格证书，对各种精密机器设备的搬运、安装工程有着丰富的工作经验。施工车辆都是定期由特种设备监督局定期安检的，搬运工具都是领先的。长期与多家财险、寿险公司合作，保证客户利益的最大化，员工利益的最大保障。&lt;/p&gt;');
INSERT INTO `__PREFIX__product_data` VALUES (7, '&lt;p&gt;上海朗**业科技有限公司，成立于2016年，致力于进口国外先进的工业设备、仪器和耗材。团队成员均由长期从事科学研究、仪器销售与服务人士组成，有着丰富的售前和售后服务经验，可为国内科研、工业客户提供一站式解决方案。\r\n\r\n“朗助工业，喜翼科技！”一直以来都是团队的奋斗方向, 多年的经营与市场反馈赢得了市场客户的信赖。\r\n&amp;nbsp;&lt;/p&gt;&lt;p&gt;旗下代理合作涵盖了Mettler Toledo, Brand, EIMINC, Leica, CBS, Astero, Thermofisher, UnionMicronClean, Technoflex, SPC, &amp;nbsp;Oxypharm, FRITSCH, Copley等众多一线品牌产品。&lt;/p&gt;');
INSERT INTO `__PREFIX__product_data` VALUES (8, '&lt;p&gt;公司以严守合同，诚信服务与客户为本企业的基本操守，坚持“真诚&amp;nbsp; 高效 共赢”的文化理念，先后在城市周边、射阳、大丰、阜宁、徐州、昆山等地设办事处，依托强大、广泛的社会资源和一支团结奋进、具有良好亲和力以及丰富人力资源服务实践和管理经验的团队，在企业人力资源业务外包服务领域、劳务派遣服务以及劳务输出、劳动事务代理、劳动政策咨询等服务领域形成了独特资源和业务优势，积累了丰富的人力资源业务服务经验，专注于将“道万**力”打招成江苏人力资源及其管理领域的专业服务品牌。&lt;/p&gt;');
INSERT INTO `__PREFIX__product_data` VALUES (9, '&lt;p&gt;&amp;nbsp; 江苏森**设集团有限公司是按照中华人民共和国公司法，于2006 年9 月5 日经过企业改制重组，在江苏省盐城市工商行政管理局亭湖分局注册成立，其性质为有限责任公司，注册资金10088万元。&lt;/p&gt;&lt;p&gt;&amp;nbsp; 公司是自主经营、独立核算、自负盈亏、具有独立法人资格的经济实体，主要从事高空维修、高空安装、高空建筑、高空防腐、高空清洗、高空加固、特种防腐、 地下堵漏以及铁塔防护等方面的高空作业。&lt;/p&gt;&lt;p&gt;&amp;nbsp; 公司设有工程部、施工部、设备部、财务部和法务部，是集工程方案设计、工程施工和质量安全管理等为一体的综合性高空作业工程公司。目前，公司下辖六个分公司，22个项目部。公司本部设有党总支、工会、办公室(兼投标办)、质量安全部、施工技术部、材料设备部。人事教育部和计划财务部，共有员工1086人，具有专业技术人员166人，其中高级工程师12人，项目经理22人。拥有大中型机械设备68台(件)，企业固定资产目前己达亿。年施工产值2亿。&lt;/p&gt;');

INSERT INTO `__PREFIX__page` VALUES (2, '关于我们', '', '', '<p>&nbsp; &nbsp; xxx网络科技股份有限公司是一家集策略咨询、创意创新、视觉设计、技术研发、内容制造、营销推广为一体的综合型数字化创新服务企业，其利用公司持续积累的核心技术和互联网思维，提供以互联网、移动互联网为核心的网络技术服务和互动整合营销服务，为传统企业实现“互联网+”升级提供整套解决方案。公司定位于中大型企业为核心客户群，可充分满足这一群体相比中小企业更为丰富、高端、多元的互联网数字综合需求。</p><p><br/></p><p>&nbsp; &nbsp; xxx网络科技股份有限公司作为一家互联网数字服务综合商，其主营业务包括移动互联网应用开发服务、数字互动整合营销服务、互联网网站建设综合服务和电子商务综合服务。</p><p><br/></p><p>&nbsp; &nbsp; xxx网络科技股份有限公司秉承实现全网价值营销的理念，通过实现互联网与移动互联网的精准数字营销和用户数据分析，日益深入到客户互联网技术建设及运维营销的方方面面，在帮助客户形成自身互联网运作体系的同时，有效对接BAT(百度，阿里，腾讯)等平台即百度搜索、阿里电商、腾讯微信，通过平台的推广来推进互联网综合服务，实现企业、用户、平台三者完美对接，并形成高效互动的枢纽，在帮助客户获取互联网高附加价值的同时获得自身的不断成长和壮大。</p>','', 0, 0);
INSERT INTO `__PREFIX__page` VALUES (3, '企业文化', '', '', '<p>【愿景】</p><ul class=\" list-paddingleft-2\" style=\"list-style-type: disc;\"><li><p>不断倾听和满足用户需求，引导并超越用户需求，赢得用户尊敬</p></li><li><p>通过提升品牌形象，使员工具有高度企业荣誉感，赢得员工尊敬&nbsp;</p></li><li><p>推动互联网行业的健康发展，与合作伙伴共成长，赢得行业尊敬</p></li><li><p>注重企业责任，用心服务，关爱社会、回馈社会，赢得社会尊敬</p></li></ul><p><br/></p><p>【使命】</p><ul class=\" list-paddingleft-2\" style=\"list-style-type: disc;\"><li><p>使产品和服务像水和电融入人们的生活，为人们带来便捷和愉悦</p></li><li><p>关注不同地域、群体，并针对不同对象提供差异化的产品和服务</p></li><li><p>打造开放共赢平台，与合作伙伴共同营造健康的互联网生态环境</p></li></ul><p><br/></p><p>【管理理念】</p><ul class=\" list-paddingleft-2\" style=\"list-style-type: disc;\"><li><p>为员工提供良好的工作环境和激励机制&nbsp;</p></li><li><p>完善员工培养体系和职业发展通道，使员工与企业同步成长</p></li><li><p>充分尊重和信任员工，不断引导和鼓励，使其获得成就的喜悦</p></li></ul>','', 0, 0);
INSERT INTO `__PREFIX__page` VALUES (18, '联系我们', '', '', '<p>手　机：158-88888888</p><p>传　真：0512-88888888</p><p>邮　编：215000</p><p>邮　箱：admin@admin.com</p><p>地　址：江苏省苏州市吴中区某某工业园一区</p><p><br/></p><p><img width=\"530\" height=\"340\" src=\"http://api.map.baidu.com/staticimage?center=116.404,39.915&zoom=10&width=530&height=340&markers=116.404,39.915\"/></p>', '', 0, 0);

INSERT INTO `__PREFIX__tags` VALUES (1, '精密机器', '', '', '', 1, 0, 1553067267, 1553067267, 0);
INSERT INTO `__PREFIX__tags` VALUES (2, '专业培训', '', '', '', 1, 0, 1553067267, 1553067267, 0);
INSERT INTO `__PREFIX__tags` VALUES (3, '资格证书', '', '', '', 1, 0, 1553067267, 1553067267, 0);
INSERT INTO `__PREFIX__tags` VALUES (4, '机械设备', '', '', '', 1, 0, 1553067296, 1553067296, 0);
INSERT INTO `__PREFIX__tags` VALUES (5, '升降机', '', '', '', 1, 0, 1553067296, 1553067296, 0);
INSERT INTO `__PREFIX__tags` VALUES (6, '租赁服务', '', '', '', 1, 0, 1553067296, 1553067296, 0);
INSERT INTO `__PREFIX__tags` VALUES (7, '餐饮管理', '', '', '', 1, 0, 1553067319, 1553067319, 0);
INSERT INTO `__PREFIX__tags` VALUES (8, '婚庆礼仪', '', '', '', 1, 0, 1553067319, 1553067319, 0);
INSERT INTO `__PREFIX__tags` VALUES (9, '劳务派遣', '', '', '', 1, 0, 1553067319, 1553067319, 0);
INSERT INTO `__PREFIX__tags` VALUES (10, '校外教育', '', '', '', 1, 0, 1553067339, 1553067339, 0);
INSERT INTO `__PREFIX__tags` VALUES (11, '智能课程', '', '', '', 1, 0, 1553067339, 1553067339, 0);
INSERT INTO `__PREFIX__tags` VALUES (12, '物流服务', '', '', '', 1, 0, 1553067377, 1553067377, 0);
INSERT INTO `__PREFIX__tags` VALUES (13, '注脂', '', '', '', 1, 0, 1553067408, 1553067408, 0);
INSERT INTO `__PREFIX__tags` VALUES (14, '打胶', '', '', '', 1, 0, 1553067408, 1553067408, 0);

INSERT INTO `__PREFIX__tags_content` VALUES ('精密机器', 3, 6, 5, 1553067267);
INSERT INTO `__PREFIX__tags_content` VALUES ('专业培训', 3, 6, 5, 1553067267);
INSERT INTO `__PREFIX__tags_content` VALUES ('资格证书', 3, 6, 5, 1553067267);
INSERT INTO `__PREFIX__tags_content` VALUES ('机械设备', 3, 5, 5, 1553067296);
INSERT INTO `__PREFIX__tags_content` VALUES ('升降机', 3, 5, 5, 1553067296);
INSERT INTO `__PREFIX__tags_content` VALUES ('租赁服务', 3, 5, 5, 1553067296);
INSERT INTO `__PREFIX__tags_content` VALUES ('餐饮管理', 3, 4, 5, 1553067319);
INSERT INTO `__PREFIX__tags_content` VALUES ('婚庆礼仪', 3, 4, 5, 1553067319);
INSERT INTO `__PREFIX__tags_content` VALUES ('劳务派遣', 3, 4, 5, 1553067319);
INSERT INTO `__PREFIX__tags_content` VALUES ('校外教育', 3, 3, 5, 1553067339);
INSERT INTO `__PREFIX__tags_content` VALUES ('智能课程', 3, 3, 5, 1553067339);
INSERT INTO `__PREFIX__tags_content` VALUES ('物流服务', 3, 2, 5, 1553067377);
INSERT INTO `__PREFIX__tags_content` VALUES ('注脂', 3, 1, 5, 1553067408);
INSERT INTO `__PREFIX__tags_content` VALUES ('打胶', 3, 1, 5, 1553067408);