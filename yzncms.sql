/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : yzncms

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2017-05-12 16:03:04
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `yzn_action`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_action`;
CREATE TABLE `yzn_action` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` char(30) NOT NULL DEFAULT '' COMMENT '行为唯一标识',
  `title` char(80) NOT NULL DEFAULT '' COMMENT '行为说明',
  `remark` char(140) NOT NULL DEFAULT '' COMMENT '行为描述',
  `rule` text NOT NULL COMMENT '行为规则',
  `log` text NOT NULL COMMENT '日志规则',
  `type` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '类型',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='系统行为表';

-- ----------------------------
-- Records of yzn_action
-- ----------------------------
INSERT INTO `yzn_action` VALUES ('1', 'user_login', '用户登录', '积分+10，每天一次', 'table:member|field:score|condition:uid={$self} AND status>-1|rule:score+10|cycle:24|max:1;', '[user|get_username]在[time|time_format]登录了后台', '1', '1', '1387181220');
INSERT INTO `yzn_action` VALUES ('2', 'add_article', '发布文章', '积分+5，每天上限5次', 'table:member|field:score|condition:uid={$self}|rule:score+5|cycle:24|max:5', '', '2', '0', '1380173180');
INSERT INTO `yzn_action` VALUES ('3', 'review', '评论', '评论积分+1，无限制', 'table:member|field:score|condition:uid={$self}|rule:score+1', '', '2', '1', '1383285646');
INSERT INTO `yzn_action` VALUES ('4', 'add_document', '发表文档', '积分+10，每天上限1次', 'table:member|field:score|condition:uid={$self}|rule:score+10|cycle:24|max:1', '[user|get_username]在[time|time_format]发表了一篇文章。\n表[model]，记录编号[record]。', '1', '1', '1493877089');
INSERT INTO `yzn_action` VALUES ('5', 'add_document_topic', '发表讨论', '积分+5，每天上限10次', 'table:member|field:score|condition:uid={$self}|rule:score+5|cycle:24|max:10', '', '2', '0', '1383285551');
INSERT INTO `yzn_action` VALUES ('6', 'update_config', '更新配置', '新增或修改或删除配置', '', '', '1', '1', '1383294988');
INSERT INTO `yzn_action` VALUES ('7', 'update_model', '更新模型', '新增或修改模型', '', '', '1', '1', '1383295057');
INSERT INTO `yzn_action` VALUES ('8', 'update_attribute', '更新属性', '新增或更新或删除属性', '', '', '1', '1', '1383295963');
INSERT INTO `yzn_action` VALUES ('9', 'update_channel', '更新导航', '新增或修改或删除导航', '', '', '1', '1', '1383296301');
INSERT INTO `yzn_action` VALUES ('10', 'update_menu', '更新菜单', '新增或修改或删除菜单', '', '', '1', '1', '1383296392');
INSERT INTO `yzn_action` VALUES ('11', 'update_category', '更新分类', '新增或修改或删除分类', '', '', '1', '1', '1383296765');

-- ----------------------------
-- Table structure for `yzn_action_log`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_action_log`;
CREATE TABLE `yzn_action_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `action_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '行为id',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '执行用户id',
  `action_ip` bigint(20) NOT NULL COMMENT '执行行为者ip',
  `model` varchar(50) NOT NULL DEFAULT '' COMMENT '触发行为的表',
  `record_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '触发行为的数据id',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '日志备注',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '执行行为的时间',
  PRIMARY KEY (`id`),
  KEY `action_ip_ix` (`action_ip`),
  KEY `action_id_ix` (`action_id`),
  KEY `user_id_ix` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='行为日志表';

-- ----------------------------
-- Records of yzn_action_log
-- ----------------------------
INSERT INTO `yzn_action_log` VALUES ('1', '1', '1', '0', 'member', '1', 'admin在2017-05-04 10:38登录了后台', '1493865527');
INSERT INTO `yzn_action_log` VALUES ('2', '9', '1', '0', 'channel', '3', 'admin在2017-05-04 10:38登录了后台', '1493865622');
INSERT INTO `yzn_action_log` VALUES ('3', '1', '1', '2130706433', 'member', '1', 'admin在2017-05-04 16:31登录了后台', '1493886660');
INSERT INTO `yzn_action_log` VALUES ('4', '1', '1', '2130706433', 'member', '1', 'admin在2017-05-04 17:53登录了后台', '1493891585');
INSERT INTO `yzn_action_log` VALUES ('5', '1', '1', '2130706433', 'member', '1', 'admin在2017-05-05 11:01登录了后台', '1493953314');
INSERT INTO `yzn_action_log` VALUES ('6', '1', '1', '2130706433', 'member', '1', 'admin在2017-05-05 11:12登录了后台', '1493953935');
INSERT INTO `yzn_action_log` VALUES ('7', '1', '1', '2130706433', 'member', '1', 'admin在2017-05-05 11:19登录了后台', '1493954350');
INSERT INTO `yzn_action_log` VALUES ('8', '1', '1', '2130706433', 'member', '1', 'admin在2017-05-05 11:19登录了后台', '1493954351');
INSERT INTO `yzn_action_log` VALUES ('9', '1', '1', '2130706433', 'member', '1', 'admin在2017-05-05 11:19登录了后台', '1493954352');
INSERT INTO `yzn_action_log` VALUES ('10', '1', '1', '2130706433', 'member', '1', 'admin在2017-05-05 11:19登录了后台', '1493954352');
INSERT INTO `yzn_action_log` VALUES ('11', '1', '1', '2130706433', 'member', '1', 'admin在2017-05-05 11:19登录了后台', '1493954352');
INSERT INTO `yzn_action_log` VALUES ('12', '1', '1', '2130706433', 'member', '1', 'admin在2017-05-05 11:19登录了后台', '1493954352');
INSERT INTO `yzn_action_log` VALUES ('13', '1', '1', '2130706433', 'member', '1', 'admin在2017-05-05 11:19登录了后台', '1493954352');
INSERT INTO `yzn_action_log` VALUES ('14', '1', '1', '2130706433', 'member', '1', 'admin在2017-05-05 11:19登录了后台', '1493954353');
INSERT INTO `yzn_action_log` VALUES ('15', '1', '1', '2130706433', 'member', '1', 'admin在2017-05-05 11:19登录了后台', '1493954353');
INSERT INTO `yzn_action_log` VALUES ('16', '1', '1', '2130706433', 'member', '1', 'admin在2017-05-05 11:19登录了后台', '1493954353');
INSERT INTO `yzn_action_log` VALUES ('17', '1', '1', '2130706433', 'member', '1', 'admin在2017-05-05 11:19登录了后台', '1493954353');
INSERT INTO `yzn_action_log` VALUES ('18', '1', '1', '2130706433', 'member', '1', 'admin在2017-05-05 11:19登录了后台', '1493954353');
INSERT INTO `yzn_action_log` VALUES ('19', '1', '1', '2130706433', 'member', '1', 'admin在2017-05-05 11:19登录了后台', '1493954354');
INSERT INTO `yzn_action_log` VALUES ('20', '1', '1', '2130706433', 'member', '1', 'admin在2017-05-05 11:19登录了后台', '1493954354');
INSERT INTO `yzn_action_log` VALUES ('21', '1', '1', '2130706433', 'member', '1', 'admin在2017-05-05 11:19登录了后台', '1493954354');
INSERT INTO `yzn_action_log` VALUES ('22', '1', '1', '2130706433', 'member', '1', 'admin在2017-05-05 11:19登录了后台', '1493954354');
INSERT INTO `yzn_action_log` VALUES ('23', '1', '1', '2130706433', 'member', '1', 'admin在2017-05-05 11:19登录了后台', '1493954354');
INSERT INTO `yzn_action_log` VALUES ('24', '1', '1', '2130706433', 'member', '1', 'admin在2017-05-05 11:19登录了后台', '1493954354');
INSERT INTO `yzn_action_log` VALUES ('25', '1', '1', '2130706433', 'member', '1', 'admin在2017-05-05 17:55登录了后台', '1493978111');

-- ----------------------------
-- Table structure for `yzn_admin`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_admin`;
CREATE TABLE `yzn_admin` (
  `userid` smallint(3) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` varchar(20) DEFAULT NULL COMMENT '管理账号',
  `password` varchar(40) DEFAULT NULL COMMENT '管理密码',
  `encrypt` varchar(6) DEFAULT NULL COMMENT '加密因子',
  `nickname` char(16) NOT NULL COMMENT '昵称',
  `last_login_time` int(10) unsigned DEFAULT '0' COMMENT '最后登录时间',
  `last_login_ip` bigint(20) unsigned DEFAULT '0' COMMENT '最后登录IP',
  `email` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`userid`),
  KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yzn_admin
-- ----------------------------
INSERT INTO `yzn_admin` VALUES ('1', 'admin', '29b2d14df82d7db68dc31faa9af3e7fee7499546', 'djvlfg', '御宅男', '1494574495', '2130706433', '530765310@qq.com');
INSERT INTO `yzn_admin` VALUES ('4', 'ken678', 'bde62a663282b37b72a5371bd1e6d51c72d6df39', 'fCScqr', '御宅男', '1493715593', '2130706433', '530765310@qq.com');

-- ----------------------------
-- Table structure for `yzn_config`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_config`;
CREATE TABLE `yzn_config` (
  `id` smallint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '配置名称',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '配置说明',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置分组',
  `value` text NOT NULL COMMENT '配置值',
  `sort` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yzn_config
-- ----------------------------
INSERT INTO `yzn_config` VALUES ('1', 'site_title', '网站标题', '1', 'Yzncms内容管理框架 - Powered by Yzncms', '1');
INSERT INTO `yzn_config` VALUES ('2', 'site_keyword', '网站关键字', '1', 'ThinkPHP,tp5.0,yzncms,内容管理系统', '2');
INSERT INTO `yzn_config` VALUES ('3', 'site_description', '网站描述', '1', 'Yzncms内容管理框架,一套简单，易用，面向开发者的内容管理框,采用TP5.0框架开发', '3');
INSERT INTO `yzn_config` VALUES ('4', 'site_name', '网站名称', '1', 'Yzncms内容管理框架', '0');
INSERT INTO `yzn_config` VALUES ('5', 'icp', 'icp', '2', '苏ICP备15017030', '0');
INSERT INTO `yzn_config` VALUES ('6', 'close', '关闭站点', '2', '0', '0');

-- ----------------------------
-- Table structure for `yzn_config_field`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_config_field`;
CREATE TABLE `yzn_config_field` (
  `fid` smallint(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `fieldname` varchar(30) NOT NULL DEFAULT '' COMMENT '字段名',
  `type` varchar(10) NOT NULL DEFAULT '' COMMENT '配置表单类型',
  `setting` mediumtext COMMENT '其他设置',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`fid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yzn_config_field
-- ----------------------------
INSERT INTO `yzn_config_field` VALUES ('1', 'icp', 'input', 'a:4:{s:5:\"title\";s:3:\"icp\";s:4:\"tips\";s:9:\"备案号\";s:5:\"style\";s:0:\"\";s:6:\"option\";s:24:\"选项名称1|选项值1\";}', '1492738742');
INSERT INTO `yzn_config_field` VALUES ('2', 'close', 'select', 'a:4:{s:5:\"title\";s:12:\"关闭站点\";s:4:\"tips\";s:0:\"\";s:5:\"style\";s:0:\"\";s:6:\"option\";a:2:{i:0;a:2:{s:5:\"title\";s:6:\"关闭\";s:5:\"value\";s:2:\"0\r\";}i:1;a:2:{s:5:\"title\";s:6:\"开启\";s:5:\"value\";s:1:\"1\";}}}', '1492741857');

-- ----------------------------
-- Table structure for `yzn_menu`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_menu`;
CREATE TABLE `yzn_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '菜单ID',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `app` char(20) NOT NULL DEFAULT '' COMMENT '应用标识',
  `controller` char(20) NOT NULL DEFAULT '' COMMENT '控制器标识',
  `action` char(20) NOT NULL DEFAULT '' COMMENT '方法标识',
  `parameter` char(255) NOT NULL DEFAULT '' COMMENT '附加参数',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `tip` varchar(255) NOT NULL DEFAULT '' COMMENT '提示',
  `is_dev` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开发者可见',
  `listorder` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '排序ID',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yzn_menu
-- ----------------------------
INSERT INTO `yzn_menu` VALUES ('1', '设置', '0', 'Admin', 'Setting', 'index', '', '1', '', '0', '1');
INSERT INTO `yzn_menu` VALUES ('2', '内容', '0', 'Admin', 'Content', 'index', '', '1', '', '0', '1');
INSERT INTO `yzn_menu` VALUES ('5', '站点配置', '10', 'Admin', 'Config', 'index', '', '1', '', '0', '1');
INSERT INTO `yzn_menu` VALUES ('6', '管理员', '1', 'Admin', 'Manager', 'index', '', '1', '', '0', '1');
INSERT INTO `yzn_menu` VALUES ('9', '扩展配置', '5', 'Admin', 'Config', 'extend', '', '1', '', '0', '5');
INSERT INTO `yzn_menu` VALUES ('10', '设置', '1', 'Admin', 'Setting', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('12', '管理员管理', '6', 'Admin', 'Manager', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('13', '添加管理员', '12', 'Admin', 'Manager', 'add', '', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('14', '编辑管理员', '12', 'Admin', 'Manager', 'edit', '', '0', '', '0', '1');
INSERT INTO `yzn_menu` VALUES ('15', '操作日志', '10', 'Admin', 'Action', 'actionlog', '', '1', '', '0', '1');
