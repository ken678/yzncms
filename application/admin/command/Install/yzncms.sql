/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : yzncms

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2019-02-27 13:23:24
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `yzn_admin`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_admin`;
CREATE TABLE `yzn_admin` (
  `id` smallint(3) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` varchar(20) DEFAULT NULL COMMENT '管理账号',
  `password` varchar(32) DEFAULT NULL COMMENT '管理密码',
  `roleid` tinyint(4) unsigned DEFAULT '0',
  `encrypt` varchar(6) DEFAULT NULL COMMENT '加密因子',
  `nickname` varchar(16) NOT NULL COMMENT '昵称',
  `last_login_time` int(10) unsigned DEFAULT '0' COMMENT '最后登录时间',
  `last_login_ip` varchar(50) NOT NULL DEFAULT '' COMMENT '最后登录IP',
  `email` varchar(40) DEFAULT NULL,
  `token` varchar(60) NOT NULL DEFAULT '' COMMENT 'Session标识',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='管理员表';

-- ----------------------------
-- Records of yzn_admin
-- ----------------------------
INSERT INTO `yzn_admin` VALUES (1, 'admin', '9724b5e6c56b95f5723009ef81961bfe', 1, 'Wo0bAa', '御宅男', 1546940765, '127.0.0.1', '530765310@qq.com', '',1);

-- ----------------------------
-- Table structure for `yzn_adminlog`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_adminlog`;
CREATE TABLE `yzn_adminlog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '日志ID',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  `uid` smallint(3) NOT NULL DEFAULT '0' COMMENT '操作者ID',
  `info` text NOT NULL COMMENT '说明',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(50) NOT NULL DEFAULT '' COMMENT '操作IP',
  `get` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='操作日志';

-- ----------------------------
-- Table structure for `yzn_attachment`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_attachment`;
CREATE TABLE `yzn_attachment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `aid` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '管理员id',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '文件名',
  `module` varchar(15) NOT NULL DEFAULT '' COMMENT '模块名，由哪个模块上传的',
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '文件路径',
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '缩略图路径',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '文件链接',
  `mime` varchar(100) NOT NULL DEFAULT '' COMMENT '文件mime类型',
  `ext` varchar(4) NOT NULL DEFAULT '' COMMENT '文件类型',
  `size` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `md5` varchar(32) NOT NULL DEFAULT '' COMMENT '文件md5',
  `sha1` varchar(40) NOT NULL DEFAULT '' COMMENT 'sha1 散列值',
  `driver` varchar(16) NOT NULL DEFAULT 'local' COMMENT '上传驱动',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上传时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `listorders` int(5) NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='附件表';

-- ----------------------------
-- Table structure for `yzn_auth_group`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_auth_group`;
CREATE TABLE `yzn_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户组id,自增主键',
  `parentid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '父组别',
  `module` varchar(20) NOT NULL COMMENT '用户组所属模块',
  `type` tinyint(4) NOT NULL COMMENT '组类型',
  `title` varchar(20) NOT NULL DEFAULT '' COMMENT '用户组中文名称',
  `description` varchar(80) NOT NULL DEFAULT '' COMMENT '描述信息',
  `rules` varchar(500) NOT NULL DEFAULT '' COMMENT '用户组拥有的规则id，多个规则 , 隔开',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='权限组表';

-- ----------------------------
-- Records of yzn_auth_group
-- ----------------------------
INSERT INTO `yzn_auth_group` VALUES (1, 0, 'admin', 1, '超级管理员', '拥有所有权限', '*', 1);
INSERT INTO `yzn_auth_group` VALUES (2, 1, 'admin', 1, '编辑', '编辑', '', 1);

-- ----------------------------
-- Table structure for yzn_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `yzn_auth_rule`;
CREATE TABLE `yzn_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '规则id,自增主键',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `name` varchar(100) DEFAULT '' COMMENT '规则名称',
  `title` varchar(50) DEFAULT '' COMMENT '规则名称',
  `icon` varchar(50) DEFAULT '' COMMENT '图标',
  `url` varchar(255) DEFAULT '' COMMENT '规则URL',
  `condition` varchar(255) DEFAULT '' COMMENT '条件',
  `remark` varchar(255) DEFAULT '' COMMENT '备注',
  `ismenu` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否为菜单',
  `menutype` enum('iframe','blank') DEFAULT NULL COMMENT '菜单类型',
  `extend` varchar(255) DEFAULT '' COMMENT '扩展属性',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `listorder` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`) USING BTREE,
  KEY `pid` (`pid`),
  KEY `listorder` (`listorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='节点表';

-- ----------------------------
-- Table structure for `yzn_cache`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_cache`;
CREATE TABLE `yzn_cache` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `key` varchar(100) NOT NULL DEFAULT '' COMMENT '缓存KEY值',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '名称',
  `module` varchar(20) NOT NULL DEFAULT '' COMMENT '模块名称',
  `model` varchar(30) NOT NULL DEFAULT '' COMMENT '模型名称',
  `action` varchar(30) NOT NULL DEFAULT '' COMMENT '方法名',
  `system` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否系统',
  PRIMARY KEY (`id`),
  KEY `ckey` (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='缓存列队表';

-- ----------------------------
-- Records of yzn_cache
-- ----------------------------
INSERT INTO `yzn_cache` VALUES (1, 'Model', '模型列表', 'admin', 'Models', 'model_cache', 1);
INSERT INTO `yzn_cache` VALUES (2, 'ModelField', '模型字段', 'admin', 'ModelField', 'model_field_cache', 1);

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
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  `value` text NULL COMMENT '配置值',
  `visible` varchar(255) DEFAULT '' COMMENT '可见条件',
  `listorder` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`),
  KEY `type` (`type`),
  KEY `group` (`group`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='网站配置';

-- ----------------------------
-- Records of yzn_config
-- ----------------------------
INSERT INTO `yzn_config` VALUES (1, 'web_site_icp', 'text', '备案信息', 'base', '', '', 1551244923, 1551244971, 1, '', '',1);
INSERT INTO `yzn_config` VALUES (2, 'web_site_statistics', 'textarea', '站点代码', 'base', '', '', 1551244957, 1551244957, 1, '','', 100);
INSERT INTO `yzn_config` VALUES (3, 'config_group', 'array', '配置分组', 'system', '', '', 1494408414, 1494408414, 1, '{\"base\":\"基础\",\"system\":\"系统\",\"upload\":\"上传\",\"develop\":\"开发\"}','', 0);
INSERT INTO `yzn_config` VALUES (4, 'theme', 'text', '主题风格', 'system', '', '', 1541752781, 1541756888, 1, 'default', '',1);
INSERT INTO `yzn_config` VALUES (5, 'admin_forbid_ip', 'textarea', '后台禁止访问IP', 'system', '', '匹配IP段用\"*\"占位，如192.168.*.*，多个IP地址请用英文逗号\",\"分割', 1551244957, 1551244957, 1, '', '',2);
INSERT INTO `yzn_config` VALUES (6, 'upload_image_size', 'text', '图片上传大小限制', 'upload', '', '0为不限制大小，单位：kb', 1540457656, 1552436075, 1, '0', '',2);
INSERT INTO `yzn_config` VALUES (7, 'upload_image_ext', 'text', '允许上传图片后缀', 'upload', '', '多个后缀用逗号隔开，不填写则不限制类型', 1540457657, 1552436074, 1, 'gif,jpg,jpeg,bmp,png', '',1);
INSERT INTO `yzn_config` VALUES (8, 'upload_file_size', 'text', '文件上传大小限制', 'upload', '', '0为不限制大小，单位：kb', 1540457658, 1552436078, 1, '0', '',3);
INSERT INTO `yzn_config` VALUES (9, 'upload_file_ext', 'text', '允许上传文件后缀', 'upload', '', '多个后缀用逗号隔开，不填写则不限制类型', 1540457659, 1552436080, 1, 'doc,docx,xls,xlsx,ppt,pptx,pdf,wps,txt,rar,zip,gz,bz2,7z', '',4);
INSERT INTO `yzn_config` VALUES (10, 'upload_driver', 'radio', '上传驱动', 'upload', 'local:本地', '图片或文件上传驱动', 1541752781, 1552436085, 1, 'local', '',9);
INSERT INTO `yzn_config` VALUES (11, 'upload_thumb_water', 'switch', '添加水印', 'upload', '', '', 1552435063, 1552436080, 1, '0', '',5);
INSERT INTO `yzn_config` VALUES (12, 'upload_thumb_water_pic', 'image', '水印图片', 'upload', '', '只有开启水印功能才生效', 1552435183, 1552436081, 1, '', '',6);
INSERT INTO `yzn_config` VALUES (13, 'upload_thumb_water_position', 'radio', '水印位置', 'upload', '1:左上角\r\n2:上居中\r\n3:右上角\r\n4:左居中\r\n5:居中\r\n6:右居中\r\n7:左下角\r\n8:下居中\r\n9:右下角', '只有开启水印功能才生效', 1552435257, 1552436082, 1, '9', '',7);
INSERT INTO `yzn_config` VALUES (14, 'upload_thumb_water_alpha', 'text', '水印透明度', 'upload', '', '请输入0~100之间的数字，数字越小，透明度越高', 1552435299, 1552436083, 1, '50', '',8);

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
  PRIMARY KEY (`name`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='字段类型表';

-- ----------------------------
-- Records of yzn_field_type
-- ----------------------------
INSERT INTO `yzn_field_type` VALUES ('text', '输入框', 1, 'varchar(255) NOT NULL', 0, 1);
INSERT INTO `yzn_field_type` VALUES ('checkbox', '复选框', 2, 'varchar(32) NOT NULL', 1, 0);
INSERT INTO `yzn_field_type` VALUES ('textarea', '多行文本', 3, 'varchar(255) NOT NULL', 0, 1);
INSERT INTO `yzn_field_type` VALUES ('password', '密码', 4, 'varchar(255) NOT NULL', 0, 1);
INSERT INTO `yzn_field_type` VALUES ('radio', '单选按钮', 5, 'char(10) NOT NULL', 1, 0);
INSERT INTO `yzn_field_type` VALUES ('switch', '开关', 6, 'tinyint(2) UNSIGNED NOT NULL', 0, 0);
INSERT INTO `yzn_field_type` VALUES ('array', '数组', 7, 'varchar(512) NOT NULL', 0, 0);
INSERT INTO `yzn_field_type` VALUES ('select', '下拉框', 8, 'char(10) NOT NULL', 1, 0);
INSERT INTO `yzn_field_type` VALUES ('selects', '下拉框(多选)', 9, 'varchar(32) NOT NULL', 1, 0);
INSERT INTO `yzn_field_type` VALUES ('selectpage', '高级下拉框', 10, 'varchar(32) NOT NULL', 1, 0);
INSERT INTO `yzn_field_type` VALUES ('image', '单张图', 11, 'varchar(255) NOT NULL', 0, 0);
INSERT INTO `yzn_field_type` VALUES ('images', '多张图', 12, 'text NOT NULL', 0, 0);
INSERT INTO `yzn_field_type` VALUES ('tags', '标签', 13, 'varchar(255) NOT NULL', 0, 1);
INSERT INTO `yzn_field_type` VALUES ('number', '数字', 14, 'int(10) UNSIGNED NOT NULL', 0, 0);
INSERT INTO `yzn_field_type` VALUES ('datetime', '日期和时间', 15, 'int(10) UNSIGNED NOT NULL', 0, 0);
INSERT INTO `yzn_field_type` VALUES ('Ueditor', '百度编辑器', 16, 'mediumtext NOT NULL', 0, 1);
INSERT INTO `yzn_field_type` VALUES ('markdown', 'markdown编辑器', 17, 'mediumtext NOT NULL', 0, 1);
INSERT INTO `yzn_field_type` VALUES ('files', '多文件', 18, 'text NOT NULL', 0, 0);
INSERT INTO `yzn_field_type` VALUES ('file', '单文件', 19, 'varchar(255) NOT NULL', 0, 0);
INSERT INTO `yzn_field_type` VALUES ('color', '颜色值', 20, 'varchar(7) NOT NULL', 0, 0);
INSERT INTO `yzn_field_type` VALUES ('city', '城市地区', 21, 'varchar(255) NOT NULL', 0, 0);
INSERT INTO `yzn_field_type` VALUES ('custom', '自定义', 22, 'text NOT NULL', 1, 0);

-- ----------------------------
-- Records of yzn_menu
-- ----------------------------
#INSERT INTO `yzn_menu` VALUES (3, '设置', 'icon-setup', 0, 'admin', 'setting', 'index','', '', 1, '', 0, 0);
#INSERT INTO `yzn_menu` VALUES (5, '扩展', 'icon-equalizer-line', 0, 'addons', 'addons', 'index1','', '', 1, '', 0, 10);
#INSERT INTO `yzn_menu` VALUES (8, '个人资料', '', 10, 'admin', 'profile', 'index','', '', 0, '', 0, 0);
#INSERT INTO `yzn_menu` VALUES (9, '资料更新', '', 10, 'admin', 'profile', 'update','', '', 0, '', 0, 0);
#INSERT INTO `yzn_menu` VALUES (10, '系统配置', 'icon-zidongxiufu', 3, 'admin', 'config', 'index1','', '', 1, '', 0, 0);
#INSERT INTO `yzn_menu` VALUES (11, '配置管理', 'icon-apartment', 10, 'admin', 'config', 'index','', '', 1, '', 0, 0);
INSERT INTO `yzn_menu` VALUES (12, '删除日志', '', 20, 'admin', 'adminlog', 'deletelog','', '', 1, '', 0, 0);
#INSERT INTO `yzn_menu` VALUES (13, '网站设置', 'icon-setup', 10, 'admin', 'config', 'setting','', '', 1, '', 0, 0);
#INSERT INTO `yzn_menu` VALUES (14, '菜单管理', 'icon-other', 10, 'admin', 'menu', 'index','', '', 1, '', 0, 0);
#INSERT INTO `yzn_menu` VALUES (15, '权限管理', 'icon-user-settings-line', 3, 'admin', 'manager', 'index1','', '', 1, '', 0, 0);
#INSERT INTO `yzn_menu` VALUES (16, '管理员管理', 'icon-user-settings-line', 15, 'admin', 'manager', 'index','', '', 1, '', 0, 0);
INSERT INTO `yzn_menu` VALUES (17, '角色管理', 'icon-user-shared-2-line', 15, 'admin', 'auth_manager', 'index','', '', 1, '', 0, 0);
INSERT INTO `yzn_menu` VALUES (18, '添加管理员', '', 16, 'admin', 'manager', 'add','', '', 1, '', 0, 0);
INSERT INTO `yzn_menu` VALUES (19, '编辑管理员', '', 16, 'admin', 'manager', 'edit','', '', 1, '', 0, 0);
INSERT INTO `yzn_menu` VALUES (20, '管理日志', 'icon-history', 15, 'admin', 'adminlog', 'index','', '', 1, '', 0, 0);
INSERT INTO `yzn_menu` VALUES (21, '删除管理员', '', 16, 'admin', 'manager', 'del','', '', 1, '', 0, 0);
INSERT INTO `yzn_menu` VALUES (22, '添加角色', '', 17, 'admin', 'auth_manager', 'add', '','', 1, '', 0, 0);
#INSERT INTO `yzn_menu` VALUES (23, '附件管理', 'icon-accessory', 10, 'admin', 'attachments', 'index','', '', 1, '', 0, 1);
#INSERT INTO `yzn_menu` VALUES (24, '新增配置', '', 11, 'admin', 'config', 'add','', '', 1, '', 0, 1);
#INSERT INTO `yzn_menu` VALUES (25, '编辑配置', '', 11, 'admin', 'config', 'edit','', '', 1, '', 0, 2);
#INSERT INTO `yzn_menu` VALUES (26, '删除配置', '', 11, 'admin', 'config', 'del', '','', 1, '', 0, 3);
#INSERT INTO `yzn_menu` VALUES (27, '批量更新', '', 11, 'admin', 'config', 'multi','', '', 1, '', 0, 0);

#INSERT INTO `yzn_menu` VALUES (28, '新增菜单', '', 14, 'admin', 'menu', 'add','', '', 1, '', 0, 0);
#INSERT INTO `yzn_menu` VALUES (29, '编辑菜单', '', 14, 'admin', 'menu', 'edit','', '', 1, '', 0, 0);
#INSERT INTO `yzn_menu` VALUES (30, '删除菜单', '', 14, 'admin', 'menu', 'del','', '', 1, '', 0, 0);
#INSERT INTO `yzn_menu` VALUES (31, '批量更新', '', 14, 'admin', 'menu', 'multi','', '', 1, '', 0, 0);

#INSERT INTO `yzn_menu` VALUES (32, '附件上传', '', 23, 'admin', 'ajax', 'upload','', '', 1, '', 0, 0);
#INSERT INTO `yzn_menu` VALUES (33, '附件删除', '', 23, 'admin', 'attachments', 'del','', '', 1, '', 0, 0);
#INSERT INTO `yzn_menu` VALUES (34, '编辑器附件', '', 23, 'admin', 'ueditor', 'run','', '', 0, '', 0, 0);
#INSERT INTO `yzn_menu` VALUES (35, '图片列表', '', 23, 'admin', 'attachments', 'showFileLis','', '', 0, '', 0, 0);
#INSERT INTO `yzn_menu` VALUES (36, '图片本地化', '', 23, 'admin', 'attachments', 'getUrlFile','', '', 0, '', 0, 0);
#INSERT INTO `yzn_menu` VALUES (37, '图片选择', '', 23, 'admin', 'attachments', 'select','', '', 0, '', 0, 0);
INSERT INTO `yzn_menu` VALUES (38, '插件扩展', 'icon-equalizer-line', 5, 'admin', 'addons', 'index2','', '', 1, '', 0, 0);
INSERT INTO `yzn_menu` VALUES (39, '插件管理', 'icon-apartment', 38, 'admin', 'addons', 'index','', '', 1, '', 0, 0);
INSERT INTO `yzn_menu` VALUES (41, '插件后台列表', 'icon-liebiaosousuo', 5, 'admin', 'addons', 'addonadmin','', '', 0, '', 0, 0);
INSERT INTO `yzn_menu` VALUES (48, '编辑角色', '', 17, 'admin', 'auth_manager', 'edit','', '', 1, '', 0, 0);
INSERT INTO `yzn_menu` VALUES (49, '删除角色', '', 17, 'admin', 'auth_manager', 'del','', '', 1, '', 0, 0);
INSERT INTO `yzn_menu` VALUES (50, '访问授权', '', 17, 'admin', 'auth_manager', 'access', '','', 1, '', 0, 0);
INSERT INTO `yzn_menu` VALUES (51, '角色授权', '', 17, 'admin', 'auth_manager', 'writeGroup','', '', 1, '', 0, 0);
INSERT INTO `yzn_menu` VALUES (55, '插件设置', '', 39, 'admin', 'addons', 'config','', '', 1, '', 0, 0);
INSERT INTO `yzn_menu` VALUES (56, '插件安装', '', 39, 'admin', 'addons', 'install','', '', 1, '', 0, 0);
INSERT INTO `yzn_menu` VALUES (57, '插件卸载', '', 39, 'admin', 'addons', 'uninstall','', '', 1, '', 0, 0);
INSERT INTO `yzn_menu` VALUES (58, '插件状态', '', 39, 'admin', 'addons', 'state','', '', 1, '', 0, 0);
INSERT INTO `yzn_menu` VALUES (59, '本地安装', '', 39, 'admin', 'addons', 'local','', '', 1, '', 0, 0);



INSERT INTO `yzn_auth_rule` VALUES (10, 3, 'config/index', '配置管理', 'iconfont icon-apartment', '', '', '', 1, NULL, '', 1491635035, 1491635035, 999, 1);
INSERT INTO `yzn_auth_rule` VALUES (4, 3, 'profile', '个人资料', 'iconfont icon-user-line', '', '', '', 1, NULL, '', 1491635035, 1491635035, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (6, 4, 'profile/update', '资料更新', '', '', '', '', 0, NULL, '', 1491635035, 1491635035, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (5, 4, 'profile/index', '查看', '', '', '', '', 0, NULL, '', 1491635035, 1491635035, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (3, 1, 'config', '系统配置', 'iconfont icon-zidongxiufu', '', '', '', 1, NULL, '', 1491635035, 1491635035, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (1, 0, 'setting', '设置', 'iconfont icon-setup', '', '', '', 1, NULL, '', 1491635035, 1491635035, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (2, 0, 'addons', '扩展', 'iconfont icon-equalizer-line', '', '', '', 1, NULL, '', 1491635035, 1491635035, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (11, 3, 'config/setting', '网站设置', 'iconfont icon-setup', '', '', '', 1, NULL, '', 1491635035, 1491635035, 888, 1);
INSERT INTO `yzn_auth_rule` VALUES (12, 3, 'rule', '菜单管理', 'iconfont icon-other', '', '', '', 1, NULL, '', 1491635035, 1491635035, 777, 1);

-- ----------------------------
-- Table structure for `yzn_model`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_model`;
CREATE TABLE `yzn_model` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `module` varchar(15) NOT NULL  DEFAULT '' COMMENT '所属模块',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '模型名称',
  `tablename` varchar(20) NOT NULL DEFAULT '' COMMENT '表名',
  `description` varchar(100) NOT NULL DEFAULT '' COMMENT '描述',
  `setting` text NOT NULL COMMENT '配置信息',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '模型类别：1-独立表，2-主附表',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `listorders` tinyint(3) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='模型列表';

-- ----------------------------
-- Table structure for `yzn_model_field`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_model_field`;
CREATE TABLE `yzn_model_field` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `modelid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '模型ID',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '字段名',
  `title` varchar(30) NOT NULL DEFAULT '' COMMENT '别名',
  `remark` tinytext NULL COMMENT '字段提示',
  `pattern` varchar(255) NOT NULL DEFAULT '' COMMENT '数据校验正则',
  `errortips` varchar(255) NOT NULL DEFAULT '' COMMENT '数据校验未通过的提示信息',
  `type` varchar(20) NOT NULL DEFAULT '' COMMENT '字段类型',
  `setting` mediumtext NULL COMMENT '字段配置',
  `ifsystem` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否主表字段 1 是',
  `iscore` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否内部字段',
  `iffixed` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否固定不可修改',
  `ifrequire` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否必填',
  `ifsearch` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '作为搜索条件',
  `isadd` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '在投稿中显示',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `name` (`name`,`modelid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='模型字段列表';

-- ----------------------------
-- Table structure for `yzn_terms`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_terms`;
CREATE TABLE `yzn_terms` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `parentid` smallint(5) NOT NULL DEFAULT '0' COMMENT '父ID',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '分类名称',
  `module` varchar(15) NOT NULL DEFAULT '' COMMENT '所属模块',
  `setting` mediumtext NULL COMMENT '相关配置信息',
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `module` (`module`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='分类表';

-- ----------------------------
-- Table structure for `yzn_sms`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_sms`;
CREATE TABLE `yzn_sms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `event` varchar(30) NOT NULL DEFAULT '' COMMENT '事件',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '手机号',
  `code` varchar(10) NOT NULL DEFAULT '' COMMENT '验证码',
  `times` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '验证次数',
  `ip` varchar(50) NOT NULL DEFAULT '' COMMENT '操作IP',
  `create_time` int(10) unsigned DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='短信验证码表';

-- ----------------------------
-- Table structure for `yzn_ems`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_ems`;
CREATE TABLE `yzn_ems` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `event` varchar(30) NOT NULL DEFAULT '' COMMENT '事件',
  `email` varchar(100) NOT NULL DEFAULT '' COMMENT '邮箱',
  `code` varchar(10) NOT NULL DEFAULT '' COMMENT '验证码',
  `times` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '验证次数',
  `ip` varchar(50) NOT NULL DEFAULT '' COMMENT '操作IP',
  `create_time` int(10) unsigned DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='邮箱验证码表';