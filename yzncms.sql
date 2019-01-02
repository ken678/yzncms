/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : yzncms

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2019-01-02 18:03:38
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `yzn_addons`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_addons`;
CREATE TABLE `yzn_addons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(40) NOT NULL COMMENT '插件名或标识',
  `title` varchar(20) NOT NULL DEFAULT '' COMMENT '中文名',
  `description` text COMMENT '插件描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `config` text COMMENT '配置',
  `author` varchar(40) DEFAULT '' COMMENT '作者',
  `version` varchar(20) DEFAULT '' COMMENT '版本号',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '安装时间',
  `has_adminlist` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否有后台列表',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='插件表';

-- ----------------------------
-- Records of yzn_addons
-- ----------------------------

-- ----------------------------
-- Table structure for `yzn_admin`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_admin`;
CREATE TABLE `yzn_admin` (
  `userid` smallint(3) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` varchar(20) DEFAULT NULL COMMENT '管理账号',
  `password` varchar(32) DEFAULT NULL COMMENT '管理密码',
  `roleid` tinyint(4) unsigned DEFAULT '0',
  `encrypt` varchar(6) DEFAULT NULL COMMENT '加密因子',
  `nickname` char(16) NOT NULL COMMENT '昵称',
  `last_login_time` int(10) unsigned DEFAULT '0' COMMENT '最后登录时间',
  `last_login_ip` bigint(20) unsigned DEFAULT '0' COMMENT '最后登录IP',
  `email` varchar(40) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '会员状态',
  PRIMARY KEY (`userid`),
  KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='管理员表';

-- ----------------------------
-- Records of yzn_admin
-- ----------------------------
INSERT INTO `yzn_admin` VALUES ('1', 'admin', '9724b5e6c56b95f5723009ef81961bfe', '1', 'Wo0bAa', '御宅男', '1546417569', '2130706433', '530765310@qq.com', '1');
INSERT INTO `yzn_admin` VALUES ('2', 'ken678', '932e31f030b850a87702a86c0e16db16', '2', 'Sxq6dR', '御宅男', '1542781151', '2130706433', '530765310@qq.com', '1');

-- ----------------------------
-- Table structure for `yzn_adminlog`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_adminlog`;
CREATE TABLE `yzn_adminlog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '日志ID',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `uid` smallint(3) NOT NULL DEFAULT '0' COMMENT '操作者ID',
  `info` text COMMENT '说明',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` bigint(20) unsigned NOT NULL DEFAULT '0',
  `get` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='操作日志';

-- ----------------------------
-- Records of yzn_adminlog
-- ----------------------------
INSERT INTO `yzn_adminlog` VALUES ('1', '1', '1', '提示语:清理缓存', '1546423363', '2130706433', '/admin/index/cache.html?type=all&_=1546423359412');

-- ----------------------------
-- Table structure for `yzn_article`
-- ----------------------------
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

-- ----------------------------
-- Records of yzn_article
-- ----------------------------

-- ----------------------------
-- Table structure for `yzn_article_data`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_article_data`;
CREATE TABLE `yzn_article_data` (
  `did` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `content` text COLLATE utf8_unicode_ci COMMENT '内容',
  PRIMARY KEY (`did`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='文章模型模型表';

-- ----------------------------
-- Records of yzn_article_data
-- ----------------------------

-- ----------------------------
-- Table structure for `yzn_attachment`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_attachment`;
CREATE TABLE `yzn_attachment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `name` char(50) NOT NULL DEFAULT '' COMMENT '文件名',
  `module` char(15) NOT NULL DEFAULT '' COMMENT '模块名，由哪个模块上传的',
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '文件路径',
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '缩略图路径',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '文件链接',
  `mime` varchar(64) NOT NULL DEFAULT '' COMMENT '文件mime类型',
  `ext` char(4) NOT NULL DEFAULT '' COMMENT '文件类型',
  `size` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `md5` char(32) NOT NULL DEFAULT '' COMMENT '文件md5',
  `sha1` char(40) NOT NULL DEFAULT '' COMMENT 'sha1 散列值',
  `driver` varchar(16) NOT NULL DEFAULT 'local' COMMENT '上传驱动',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上传时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `listorders` int(5) NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='附件表';

-- ----------------------------
-- Records of yzn_attachment
-- ----------------------------

-- ----------------------------
-- Table structure for `yzn_auth_group`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_auth_group`;
CREATE TABLE `yzn_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户组id,自增主键',
  `module` varchar(20) NOT NULL COMMENT '用户组所属模块',
  `type` tinyint(4) NOT NULL COMMENT '组类型',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '用户组中文名称',
  `description` varchar(80) NOT NULL DEFAULT '' COMMENT '描述信息',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户组状态：为1正常，为0禁用,-1为删除',
  `rules` varchar(500) NOT NULL DEFAULT '' COMMENT '用户组拥有的规则id，多个规则 , 隔开',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='权限组表';

-- ----------------------------
-- Records of yzn_auth_group
-- ----------------------------
INSERT INTO `yzn_auth_group` VALUES ('1', 'admin', '1', '超级管理员', '拥有所有权限', '1', '154,155,146,149,149,158,159,160,150,151,161,162,163,157,164,165,152,152,153,156,147,148');
INSERT INTO `yzn_auth_group` VALUES ('2', 'admin', '1', '编辑', '编辑', '1', '179,195,198,180,180,197,199,200,181,182,186,187,188,196,189,190,183,183,184,185,201,201,201,201,202,202,202,192,193');

-- ----------------------------
-- Table structure for `yzn_auth_rule`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_auth_rule`;
CREATE TABLE `yzn_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '规则id,自增主键',
  `module` varchar(20) NOT NULL COMMENT '规则所属module',
  `type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1-url;2-主菜单',
  `name` char(80) NOT NULL DEFAULT '' COMMENT '规则唯一英文标识',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '规则中文描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否有效(0:无效,1:有效)',
  `condition` varchar(300) NOT NULL DEFAULT '' COMMENT '规则附加条件',
  PRIMARY KEY (`id`),
  KEY `module` (`module`,`status`,`type`)
) ENGINE=MyISAM AUTO_INCREMENT=222 DEFAULT CHARSET=utf8 COMMENT='规则表';

-- ----------------------------
-- Records of yzn_auth_rule
-- ----------------------------
INSERT INTO `yzn_auth_rule` VALUES ('179', 'admin', '2', 'admin/index/index', '首页', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('180', 'admin', '1', 'admin/config/index', '配置管理', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('181', 'admin', '1', 'admin/config/setting', '网站设置', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('182', 'admin', '1', 'admin/menu/index', '菜单管理', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('183', 'admin', '1', 'admin/manager/index', '管理员管理', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('184', 'admin', '1', 'admin/authManager/index', '角色管理', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('185', 'admin', '1', 'admin/adminlog/index', '管理日志', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('186', 'admin', '1', 'admin/menu/add', '新增菜单', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('187', 'admin', '1', 'admin/menu/edit', '编辑菜单', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('188', 'admin', '1', 'admin/menu/delete', '删除菜单', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('189', 'attachment', '1', 'attachment/attachments/upload', '附件上传', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('190', 'attachment', '1', 'attachment/attachments/delete', '附件删除', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('191', 'addons', '1', 'addons/addons/index', '插件管理', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('192', 'addons', '1', 'addons/addons/hooks', '行为管理', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('193', 'addons', '1', 'addons/addons/addonadmin', '插件后台列表', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('194', 'admin', '1', 'admin/module/index', '模块后台列表', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('195', 'admin', '2', 'admin/main/index', '控制面板', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('196', 'attachment', '1', 'attachment/attachments/index', '附件管理', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('197', 'admin', '1', 'admin/config/add', '新增配置', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('198', 'admin', '2', 'admin/setting/index', '设置', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('199', 'admin', '1', 'admin/config/edit', '编辑配置', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('200', 'admin', '1', 'admin/config/del', '删除配置', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('201', 'admin', '2', 'admin/module/index', '模块', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('202', 'addons', '2', 'addons/addons/index', '扩展', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('211', 'cms', '1', 'cms/cms/index', '管理内容', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('212', 'cms', '1', 'cms/category/index', '栏目列表', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('213', 'cms', '1', 'cms/category/add', '添加栏目', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('214', 'cms', '1', 'cms/category/edit', '编辑栏目', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('215', 'cms', '1', 'cms/models/index', '模型管理', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('216', 'cms', '1', 'cms/field/index', '字段管理', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('217', 'cms', '1', 'cms/models/add', '添加模型', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('218', 'cms', '1', 'cms/models/edit', '修改模型', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('219', 'cms', '1', 'cms/models/delete', '删除模型', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('220', 'cms', '1', 'cms/models/setstate', '设置模型状态', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('221', 'cms', '2', 'cms/index/index', '内容', '1', '');

-- ----------------------------
-- Table structure for `yzn_cache`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_cache`;
CREATE TABLE `yzn_cache` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `key` char(100) NOT NULL DEFAULT '' COMMENT '缓存KEY值',
  `name` char(100) NOT NULL DEFAULT '' COMMENT '名称',
  `module` char(20) NOT NULL DEFAULT '' COMMENT '模块名称',
  `model` char(30) NOT NULL DEFAULT '' COMMENT '模型名称',
  `action` char(30) NOT NULL DEFAULT '' COMMENT '方法名',
  `system` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否系统',
  PRIMARY KEY (`id`),
  KEY `ckey` (`key`)
) ENGINE=MyISAM AUTO_INCREMENT=63 DEFAULT CHARSET=utf8 COMMENT='缓存列队表';

-- ----------------------------
-- Records of yzn_cache
-- ----------------------------
INSERT INTO `yzn_cache` VALUES ('1', 'Config', '网站配置', 'admin', 'Config', 'config_cache', '1');
INSERT INTO `yzn_cache` VALUES ('2', 'Menu', '后台菜单', 'admin', 'Menu', 'menu_cache', '1');
INSERT INTO `yzn_cache` VALUES ('3', 'Module', '可用模块列表', 'admin', 'Module', 'module_cache', '1');
INSERT INTO `yzn_cache` VALUES ('62', 'ModelField', '模型字段', 'cms', 'ModelField', 'model_field_cache', '0');
INSERT INTO `yzn_cache` VALUES ('61', 'Category', '栏目索引', 'cms', 'Category', 'category_cache', '0');
INSERT INTO `yzn_cache` VALUES ('60', 'Model', '模型列表', 'cms', 'Models', 'model_cache', '0');

-- ----------------------------
-- Table structure for `yzn_category`
-- ----------------------------
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
  `arrchildid` mediumtext NOT NULL COMMENT '所有子栏目ID',
  `image` mediumint(8) unsigned NOT NULL COMMENT '栏目图片',
  `description` mediumtext NOT NULL COMMENT '栏目描述',
  `url` varchar(100) NOT NULL DEFAULT '' COMMENT '链接地址',
  `setting` mediumtext NOT NULL COMMENT '相关配置信息',
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='栏目表';

-- ----------------------------
-- Records of yzn_category
-- ----------------------------

-- ----------------------------
-- Table structure for `yzn_config`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_config`;
CREATE TABLE `yzn_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '配置名称',
  `type` varchar(32) NOT NULL DEFAULT '' COMMENT '配置类型',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '配置说明',
  `group` varchar(32) NOT NULL DEFAULT '' COMMENT '配置分组',
  `options` varchar(255) NOT NULL DEFAULT '' COMMENT '配置项',
  `remark` varchar(100) NOT NULL DEFAULT '' COMMENT '配置说明',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `value` text COMMENT '配置值',
  `listorder` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`),
  KEY `type` (`type`),
  KEY `group` (`group`)
) ENGINE=MyISAM AUTO_INCREMENT=152 DEFAULT CHARSET=utf8 COMMENT='网站配置';

-- ----------------------------
-- Records of yzn_config
-- ----------------------------
INSERT INTO `yzn_config` VALUES ('1', 'web_site_status', 'switch', '站点开关', 'base', '', '站点关闭后前台将不能访问', '1494408414', '1546054397', '1', '1', '1');
INSERT INTO `yzn_config` VALUES ('2', 'web_site_title', 'text', '站点标题', 'base', '', '', '1494408414', '1494408414', '1', 'YznCMS网站管理系统', '2');
INSERT INTO `yzn_config` VALUES ('3', 'web_site_keywords', 'text', '站点关键词', 'base', '', '', '1494408414', '1494408414', '1', 'YznCMS,网站管理系统', '3');
INSERT INTO `yzn_config` VALUES ('4', 'web_site_description', 'text', '站点描述', 'base', '', '', '1494408414', '1494408414', '1', '', '4');
INSERT INTO `yzn_config` VALUES ('5', 'web_site_logo', 'image', '站点LOGO', 'base', '', '', '1494408414', '1494408414', '1', '233', '5');
INSERT INTO `yzn_config` VALUES ('6', 'web_site_icp', 'text', '备案信息', 'base', '', '', '1494408414', '1546054403', '1', '', '6');
INSERT INTO `yzn_config` VALUES ('7', 'web_site_statistics', 'textarea', '站点代码', 'base', '', '', '1494408414', '1494408414', '1', '', '7');
INSERT INTO `yzn_config` VALUES ('8', 'config_group', 'array', '配置分组', 'system', '', '', '1494408414', '1494408414', '1', 'base:基础\nsystem:系统\nupload:上传\ndevelop:开发', '0');
INSERT INTO `yzn_config` VALUES ('9', 'ueditor', 'Ueditor', '第三方代码', 'base', '', '', '1538212563', '1538212563', '1', '', '100');
INSERT INTO `yzn_config` VALUES ('11', 'pics', 'images', 'pics', 'base', '', '', '1540341793', '1540341793', '1', '234,235', '100');
INSERT INTO `yzn_config` VALUES ('14', 'upload_file_size', 'text', '文件上传大小限制', 'upload', '', '0为不限制大小，单位：kb', '1540457658', '1541756875', '1', '0', '99');
INSERT INTO `yzn_config` VALUES ('12', 'upload_image_size', 'text', '图片上传大小限制', 'upload', '', '0为不限制大小，单位：kb', '1540457656', '1541756842', '1', '0', '97');
INSERT INTO `yzn_config` VALUES ('15', 'upload_file_ext', 'text', '允许上传的文件后缀', 'upload', '', '多个后缀用逗号隔开，不填写则不限制类型', '1540457659', '1540457800', '1', 'doc,docx,xls,xlsx,ppt,pptx,pdf,wps,txt,rar,zip,gz,bz2,7z', '100');
INSERT INTO `yzn_config` VALUES ('13', 'upload_image_ext', 'text', '允许上传的图片后缀', 'upload', '', '多个后缀用逗号隔开，不填写则不限制类型', '1540457657', '1541756856', '1', 'gif,jpg,jpeg,bmp,png', '98');
INSERT INTO `yzn_config` VALUES ('151', 'upload_driver', 'radio', '上传驱动', 'upload', 'local:本地', '图片或文件上传驱动', '1541752781', '1541756888', '1', 'local', '101');

-- ----------------------------
-- Table structure for `yzn_field_type`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_field_type`;
CREATE TABLE `yzn_field_type` (
  `name` varchar(32) NOT NULL COMMENT '字段类型',
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '中文类型名',
  `listorder` int(4) NOT NULL DEFAULT '0' COMMENT '排序',
  `default_define` varchar(128) NOT NULL DEFAULT '' COMMENT '默认定义',
  `ifoption` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要设置选项',
  `ifstring` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否自由字符',
  `vrule` varchar(256) NOT NULL DEFAULT '' COMMENT '验证规则',
  PRIMARY KEY (`name`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='字段类型表';

-- ----------------------------
-- Records of yzn_field_type
-- ----------------------------
INSERT INTO `yzn_field_type` VALUES ('text', '输入框', '1', 'varchar(128) NOT NULL DEFAULT \'\'', '0', '1', '');
INSERT INTO `yzn_field_type` VALUES ('checkbox', '复选框', '2', 'varchar(32) NOT NULL DEFAULT \'\'', '1', '0', 'isChsAlphaNum');
INSERT INTO `yzn_field_type` VALUES ('textarea', '多行文本', '3', 'varchar(3000) NOT NULL DEFAULT \'\'', '0', '1', '');
INSERT INTO `yzn_field_type` VALUES ('radio', '单选按钮', '4', 'varchar(32) NOT NULL DEFAULT \'\'', '1', '0', 'isChsAlphaNum');
INSERT INTO `yzn_field_type` VALUES ('switch', '开关', '5', 'tinyint(2) UNSIGNED NOT NULL DEFAULT \'0\'', '0', '0', 'isBool');
INSERT INTO `yzn_field_type` VALUES ('array', '数组', '6', 'varchar(512) NOT NULL DEFAULT \'\'', '0', '0', '');
INSERT INTO `yzn_field_type` VALUES ('select', '下拉框', '7', 'varchar(64) NOT NULL DEFAULT \'\'', '1', '0', 'isChsAlphaNum');
INSERT INTO `yzn_field_type` VALUES ('image', '单张图', '8', 'int(5) UNSIGNED NOT NULL DEFAULT \'0\'', '0', '0', 'isNumber');
INSERT INTO `yzn_field_type` VALUES ('tags', '标签', '10', 'varchar(256) NOT NULL DEFAULT \'\'', '0', '1', '');
INSERT INTO `yzn_field_type` VALUES ('number', '数字', '11', 'int(10) UNSIGNED NOT NULL DEFAULT \'0\'', '0', '0', 'isNumber');
INSERT INTO `yzn_field_type` VALUES ('datetime', '日期和时间', '12', 'int(11) UNSIGNED NOT NULL DEFAULT \'0\'', '0', '0', '');
INSERT INTO `yzn_field_type` VALUES ('Ueditor', '百度编辑器', '13', 'text NOT NULL', '0', '1', '');
INSERT INTO `yzn_field_type` VALUES ('images', '多张图', '9', 'varchar(256) NOT NULL DEFAULT \'\'', '0', '0', '');
INSERT INTO `yzn_field_type` VALUES ('color', '颜色值', '16', 'varchar(7) NOT NULL DEFAULT \'\'', '0', '0', '');
INSERT INTO `yzn_field_type` VALUES ('files', '多文件', '15', 'varchar(256) NOT NULL DEFAULT \'\'', '0', '0', '');
INSERT INTO `yzn_field_type` VALUES ('summernote', '简洁编辑器', '14', 'text NOT NULL', '0', '1', '');

-- ----------------------------
-- Table structure for `yzn_hooks`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_hooks`;
CREATE TABLE `yzn_hooks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(40) NOT NULL DEFAULT '' COMMENT '钩子名称',
  `description` text NOT NULL COMMENT '描述',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '类型',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `addons` varchar(255) NOT NULL DEFAULT '' COMMENT '钩子挂载的插件 ''，''分割',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='插件钩子';

-- ----------------------------
-- Records of yzn_hooks
-- ----------------------------
INSERT INTO `yzn_hooks` VALUES ('1', 'pageHeader', '页面header钩子，一般用于加载插件CSS文件和代码', '1', '1509174020', '');
INSERT INTO `yzn_hooks` VALUES ('2', 'pageFooter', '页面footer钩子，一般用于加载插件JS文件和JS代码', '1', '1509174020', '');

-- ----------------------------
-- Table structure for `yzn_menu`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_menu`;
CREATE TABLE `yzn_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '菜单ID',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `icon` varchar(64) NOT NULL DEFAULT '' COMMENT '图标',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `app` char(20) NOT NULL DEFAULT '' COMMENT '应用标识',
  `controller` char(20) NOT NULL DEFAULT '' COMMENT '控制器标识',
  `action` char(20) NOT NULL DEFAULT '' COMMENT '方法标识',
  `parameter` char(255) NOT NULL DEFAULT '' COMMENT '附加参数',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `tip` varchar(255) NOT NULL DEFAULT '' COMMENT '提示',
  `is_dev` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开发者可见',
  `listorder` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '排序ID',
  PRIMARY KEY (`id`),
  KEY `pid` (`parentid`)
) ENGINE=MyISAM AUTO_INCREMENT=414 DEFAULT CHARSET=utf8 COMMENT='后台菜单表';

-- ----------------------------
-- Records of yzn_menu
-- ----------------------------
INSERT INTO `yzn_menu` VALUES ('3', '设置', 'icon-shezhi', '0', 'admin', 'setting', 'index', '', '1', '', '0', '2');
INSERT INTO `yzn_menu` VALUES ('4', '模块', 'icon-yingyong', '0', 'admin', 'module', 'index', '', '1', '', '0', '9');
INSERT INTO `yzn_menu` VALUES ('5', '扩展', 'icon-module', '0', 'addons', 'addons', 'index', '', '1', '', '0', '10');
INSERT INTO `yzn_menu` VALUES ('10', '系统配置', 'icon-zidongxiufu', '3', 'admin', 'config', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('11', '配置管理', 'icon-peizhi', '10', 'admin', 'config', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('13', '网站设置', 'icon-shezhi', '10', 'admin', 'config', 'setting', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('14', '菜单管理', 'icon-liebiao', '10', 'admin', 'menu', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('15', '权限管理', 'icon-guanliyuan', '3', 'admin', 'manager', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('20', '管理日志', 'icon-rizhi', '15', 'admin', 'adminlog', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('16', '管理员管理', 'icon-guanliyuan', '15', 'admin', 'manager', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('17', '角色管理', 'icon-chengyuan', '15', 'admin', 'authManager', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('1', '首页', '', '0', 'admin', 'index', 'index', '', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('2', '控制面板', '', '0', 'admin', 'main', 'index', '', '0', '', '0', '1');
INSERT INTO `yzn_menu` VALUES ('23', '附件管理', 'icon-fujian', '10', 'attachment', 'attachments', 'index', '', '1', '', '0', '1');
INSERT INTO `yzn_menu` VALUES ('24', '新增配置', '', '11', 'admin', 'config', 'add', '', '1', '', '0', '1');
INSERT INTO `yzn_menu` VALUES ('25', '编辑配置', '', '11', 'admin', 'config', 'edit', '', '1', '', '0', '2');
INSERT INTO `yzn_menu` VALUES ('26', '删除配置', '', '11', 'admin', 'config', 'del', '', '1', '', '0', '3');
INSERT INTO `yzn_menu` VALUES ('27', '新增菜单', '', '14', 'admin', 'menu', 'add', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('28', '编辑菜单', '', '14', 'admin', 'menu', 'edit', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('29', '删除菜单', '', '14', 'admin', 'menu', 'delete', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('30', '附件上传', '', '23', 'attachment', 'attachments', 'upload', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('31', '附件删除', '', '23', 'attachment', 'attachments', 'delete', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('32', '插件扩展', 'icon-module', '5', 'addons', 'addons', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('33', '插件管理', 'icon-plugins-', '32', 'addons', 'addons', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('34', '行为管理', 'icon-hangweifenxi', '32', 'addons', 'addons', 'hooks', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('35', '插件后台列表', 'icon-016', '5', 'addons', 'addons', 'addonadmin', '', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('36', '本地模块', 'icon-yingyong', '4', 'admin', 'module', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('37', '模块管理', 'icon-mokuaishezhi', '36', 'admin', 'module', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('38', '模块后台列表', 'icon-016', '4', 'admin', 'module', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('413', '设置模型状态', '', '408', 'cms', 'models', 'setstate', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('412', '删除模型', '', '408', 'cms', 'models', 'delete', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('411', '修改模型', '', '408', 'cms', 'models', 'edit', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('410', '添加模型', '', '408', 'cms', 'models', 'add', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('409', '字段管理', '', '408', 'cms', 'field', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('408', '模型管理', 'icon-moxing', '404', 'cms', 'models', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('407', '编辑栏目', '', '405', 'cms', 'category', 'edit', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('406', '添加栏目', '', '405', 'cms', 'category', 'add', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('405', '栏目列表', 'icon-liebiao', '404', 'cms', 'category', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('404', '相关设置', 'icon-zidongxiufu', '401', 'cms', 'category', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('403', '管理内容', 'icon-neirongguanli', '402', 'cms', 'cms', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('402', '内容管理', 'icon-neirongguanli', '401', 'cms', 'cms', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('401', '内容', 'icon-article', '0', 'cms', 'index', 'index', '', '1', '', '0', '3');

-- ----------------------------
-- Table structure for `yzn_model`
-- ----------------------------
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

-- ----------------------------
-- Records of yzn_model
-- ----------------------------
INSERT INTO `yzn_model` VALUES ('1', '文章模型', 'article', '文章模型', '2', '1545799009', '1545799009', '0', '0', '1');

-- ----------------------------
-- Table structure for `yzn_model_field`
-- ----------------------------
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

-- ----------------------------
-- Records of yzn_model_field
-- ----------------------------
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

-- ----------------------------
-- Table structure for `yzn_module`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_module`;
CREATE TABLE `yzn_module` (
  `module` varchar(15) NOT NULL COMMENT '模块',
  `modulename` varchar(20) NOT NULL DEFAULT '' COMMENT '模块名称',
  `sign` varchar(255) NOT NULL DEFAULT '' COMMENT '签名',
  `iscore` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '内置模块',
  `disabled` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否可用',
  `version` varchar(50) NOT NULL DEFAULT '' COMMENT '版本',
  `setting` mediumtext COMMENT '设置信息',
  `installtime` int(10) NOT NULL DEFAULT '0' COMMENT '安装时间',
  `updatetime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `listorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`module`),
  KEY `sign` (`sign`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='已安装模块列表';

-- ----------------------------
-- Records of yzn_module
-- ----------------------------
INSERT INTO `yzn_module` VALUES ('cms', 'cms模块', 'b19cc279ed484c13c96c2f7142e2f437', '0', '1', '1.0.0', null, '1546417580', '1546417580', '0');
