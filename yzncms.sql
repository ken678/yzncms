/*
Navicat MySQL Data Transfer

Source Server         : 本地链接
Source Server Version : 50547
Source Host           : localhost:3306
Source Database       : yzncms

Target Server Type    : MYSQL
Target Server Version : 50547
File Encoding         : 65001

Date: 2017-07-06 23:01:45
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
) ENGINE=MyISAM AUTO_INCREMENT=145 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='行为日志表';

-- ----------------------------
-- Records of yzn_action_log
-- ----------------------------
INSERT INTO `yzn_action_log` VALUES ('129', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-26 09:52登录了后台', '1498441977');
INSERT INTO `yzn_action_log` VALUES ('128', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-26 08:19登录了后台', '1498436342');
INSERT INTO `yzn_action_log` VALUES ('85', '1', '2', '2130706433', 'member', '2', 'ken678在2017-06-06 17:28登录了后台', '1496741283');
INSERT INTO `yzn_action_log` VALUES ('84', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-06 17:27登录了后台', '1496741261');
INSERT INTO `yzn_action_log` VALUES ('83', '1', '2', '2130706433', 'member', '2', 'ken678在2017-06-06 17:24登录了后台', '1496741048');
INSERT INTO `yzn_action_log` VALUES ('82', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-06 16:43登录了后台', '1496738624');
INSERT INTO `yzn_action_log` VALUES ('81', '1', '2', '2130706433', 'member', '2', 'ken678在2017-06-06 16:43登录了后台', '1496738605');
INSERT INTO `yzn_action_log` VALUES ('80', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-06 16:35登录了后台', '1496738120');
INSERT INTO `yzn_action_log` VALUES ('79', '1', '2', '2130706433', 'member', '2', 'ken678在2017-06-06 16:24登录了后台', '1496737497');
INSERT INTO `yzn_action_log` VALUES ('78', '1', '2', '2130706433', 'member', '2', 'ken678在2017-06-06 16:22登录了后台', '1496737339');
INSERT INTO `yzn_action_log` VALUES ('77', '1', '2', '2130706433', 'member', '2', 'ken678在2017-06-06 16:21登录了后台', '1496737318');
INSERT INTO `yzn_action_log` VALUES ('76', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-06 16:21登录了后台', '1496737265');
INSERT INTO `yzn_action_log` VALUES ('75', '1', '2', '2130706433', 'member', '2', 'ken678在2017-06-06 16:20登录了后台', '1496737243');
INSERT INTO `yzn_action_log` VALUES ('74', '1', '2', '2130706433', 'member', '2', 'ken678在2017-06-06 16:20登录了后台', '1496737208');
INSERT INTO `yzn_action_log` VALUES ('73', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-06 15:53登录了后台', '1496735596');
INSERT INTO `yzn_action_log` VALUES ('72', '1', '2', '2130706433', 'member', '2', 'ken678在2017-06-06 15:50登录了后台', '1496735436');
INSERT INTO `yzn_action_log` VALUES ('71', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-06 15:26登录了后台', '1496733983');
INSERT INTO `yzn_action_log` VALUES ('70', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-06 14:35登录了后台', '1496730903');
INSERT INTO `yzn_action_log` VALUES ('69', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-06 12:57登录了后台', '1496725042');
INSERT INTO `yzn_action_log` VALUES ('68', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-06 10:11登录了后台', '1496715109');
INSERT INTO `yzn_action_log` VALUES ('67', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-06 10:01登录了后台', '1496714464');
INSERT INTO `yzn_action_log` VALUES ('66', '1', '2', '2130706433', 'member', '2', 'ken678在2017-06-06 10:00登录了后台', '1496714402');
INSERT INTO `yzn_action_log` VALUES ('65', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-06 09:56登录了后台', '1496714179');
INSERT INTO `yzn_action_log` VALUES ('64', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-06 09:09登录了后台', '1496711353');
INSERT INTO `yzn_action_log` VALUES ('63', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-05 17:49登录了后台', '1496656198');
INSERT INTO `yzn_action_log` VALUES ('127', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-23 16:11登录了后台', '1498205480');
INSERT INTO `yzn_action_log` VALUES ('126', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-21 17:57登录了后台', '1498039079');
INSERT INTO `yzn_action_log` VALUES ('125', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-21 14:42登录了后台', '1498027366');
INSERT INTO `yzn_action_log` VALUES ('124', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-21 11:08登录了后台', '1498014516');
INSERT INTO `yzn_action_log` VALUES ('130', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-26 12:44登录了后台', '1498452253');
INSERT INTO `yzn_action_log` VALUES ('132', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-27 17:58登录了后台', '1498557531');
INSERT INTO `yzn_action_log` VALUES ('131', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-27 17:07登录了后台', '1498554473');
INSERT INTO `yzn_action_log` VALUES ('139', '1', '1', '2130706433', 'member', '1', 'admin在2017-07-05 13:35登录了后台', '1499232934');
INSERT INTO `yzn_action_log` VALUES ('138', '1', '1', '2130706433', 'member', '1', 'admin在2017-07-04 17:04登录了后台', '1499159048');
INSERT INTO `yzn_action_log` VALUES ('137', '1', '2', '2130706433', 'member', '2', 'ken678在2017-07-04 13:49登录了后台', '1499147342');
INSERT INTO `yzn_action_log` VALUES ('136', '1', '1', '2130706433', 'member', '1', 'admin在2017-07-04 11:47登录了后台', '1499140079');
INSERT INTO `yzn_action_log` VALUES ('135', '1', '2', '2130706433', 'member', '2', 'ken678在2017-07-04 10:02登录了后台', '1499133722');
INSERT INTO `yzn_action_log` VALUES ('62', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-05 17:00登录了后台', '1496653241');
INSERT INTO `yzn_action_log` VALUES ('89', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-06 17:33登录了后台', '1496741610');
INSERT INTO `yzn_action_log` VALUES ('87', '1', '2', '2130706433', 'member', '2', 'ken678在2017-06-06 17:30登录了后台', '1496741455');
INSERT INTO `yzn_action_log` VALUES ('88', '1', '2', '2130706433', 'member', '2', 'ken678在2017-06-06 17:31登录了后台', '1496741479');
INSERT INTO `yzn_action_log` VALUES ('91', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-07 08:25登录了后台', '1496795144');
INSERT INTO `yzn_action_log` VALUES ('92', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-07 16:24登录了后台', '1496823848');
INSERT INTO `yzn_action_log` VALUES ('93', '1', '2', '2130706433', 'member', '2', 'ken678在2017-06-07 16:32登录了后台', '1496824340');
INSERT INTO `yzn_action_log` VALUES ('94', '1', '2', '2130706433', 'member', '2', 'ken678在2017-06-07 16:32登录了后台', '1496824377');
INSERT INTO `yzn_action_log` VALUES ('95', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-07 16:34登录了后台', '1496824465');
INSERT INTO `yzn_action_log` VALUES ('96', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-07 16:36登录了后台', '1496824617');
INSERT INTO `yzn_action_log` VALUES ('97', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-07 16:37登录了后台', '1496824663');
INSERT INTO `yzn_action_log` VALUES ('98', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-07 17:19登录了后台', '1496827196');
INSERT INTO `yzn_action_log` VALUES ('99', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-07 17:29登录了后台', '1496827793');
INSERT INTO `yzn_action_log` VALUES ('100', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-08 08:29登录了后台', '1496881777');
INSERT INTO `yzn_action_log` VALUES ('101', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-08 08:36登录了后台', '1496882183');
INSERT INTO `yzn_action_log` VALUES ('102', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-09 15:54登录了后台', '1496994860');
INSERT INTO `yzn_action_log` VALUES ('103', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-12 08:21登录了后台', '1497226869');
INSERT INTO `yzn_action_log` VALUES ('104', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-12 16:44登录了后台', '1497257088');
INSERT INTO `yzn_action_log` VALUES ('105', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-12 17:41登录了后台', '1497260480');
INSERT INTO `yzn_action_log` VALUES ('106', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-13 10:57登录了后台', '1497322679');
INSERT INTO `yzn_action_log` VALUES ('107', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-13 11:06登录了后台', '1497323173');
INSERT INTO `yzn_action_log` VALUES ('108', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-13 16:42登录了后台', '1497343329');
INSERT INTO `yzn_action_log` VALUES ('109', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-14 16:29登录了后台', '1497428965');
INSERT INTO `yzn_action_log` VALUES ('110', '1', '2', '2130706433', 'member', '2', 'ken678在2017-06-14 16:57登录了后台', '1497430668');
INSERT INTO `yzn_action_log` VALUES ('111', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-14 16:59登录了后台', '1497430794');
INSERT INTO `yzn_action_log` VALUES ('112', '1', '2', '2130706433', 'member', '2', 'ken678在2017-06-14 17:05登录了后台', '1497431115');
INSERT INTO `yzn_action_log` VALUES ('113', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-14 17:05登录了后台', '1497431143');
INSERT INTO `yzn_action_log` VALUES ('114', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-16 16:13登录了后台', '1497600812');
INSERT INTO `yzn_action_log` VALUES ('115', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-19 16:43登录了后台', '1497861801');
INSERT INTO `yzn_action_log` VALUES ('116', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-19 17:17登录了后台', '1497863876');
INSERT INTO `yzn_action_log` VALUES ('117', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-20 08:48登录了后台', '1497919721');
INSERT INTO `yzn_action_log` VALUES ('118', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-20 09:19登录了后台', '1497921542');
INSERT INTO `yzn_action_log` VALUES ('119', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-20 09:21登录了后台', '1497921664');
INSERT INTO `yzn_action_log` VALUES ('120', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-20 09:34登录了后台', '1497922482');
INSERT INTO `yzn_action_log` VALUES ('121', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-20 09:51登录了后台', '1497923477');
INSERT INTO `yzn_action_log` VALUES ('122', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-20 09:58登录了后台', '1497923917');
INSERT INTO `yzn_action_log` VALUES ('123', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-20 17:41登录了后台', '1497951692');
INSERT INTO `yzn_action_log` VALUES ('133', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-30 17:16登录了后台', '1498814207');
INSERT INTO `yzn_action_log` VALUES ('134', '1', '1', '2130706433', 'member', '1', 'admin在2017-07-03 11:09登录了后台', '1499051379');
INSERT INTO `yzn_action_log` VALUES ('140', '1', '1', '2130706433', 'member', '1', 'admin在2017-07-05 16:34登录了后台', '1499243664');
INSERT INTO `yzn_action_log` VALUES ('141', '1', '1', '2130706433', 'member', '1', 'admin在2017-07-06 07:55登录了后台', '1499298911');
INSERT INTO `yzn_action_log` VALUES ('142', '1', '1', '2130706433', 'member', '1', 'admin在2017-07-06 09:07登录了后台', '1499303235');
INSERT INTO `yzn_action_log` VALUES ('143', '1', '1', '2130706433', 'member', '1', 'admin在2017-07-06 12:34登录了后台', '1499315668');
INSERT INTO `yzn_action_log` VALUES ('144', '1', '1', '0', 'member', '1', 'admin在2017-07-06 22:06登录了后台', '1499349992');

-- ----------------------------
-- Table structure for `yzn_admin`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_admin`;
CREATE TABLE `yzn_admin` (
  `userid` smallint(3) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` varchar(20) DEFAULT NULL COMMENT '管理账号',
  `password` varchar(40) DEFAULT NULL COMMENT '管理密码',
  `roleid` tinyint(4) unsigned DEFAULT '0',
  `encrypt` varchar(6) DEFAULT NULL COMMENT '加密因子',
  `nickname` char(16) NOT NULL COMMENT '昵称',
  `last_login_time` int(10) unsigned DEFAULT '0' COMMENT '最后登录时间',
  `last_login_ip` bigint(20) unsigned DEFAULT '0' COMMENT '最后登录IP',
  `email` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`userid`),
  KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='管理员表';

-- ----------------------------
-- Records of yzn_admin
-- ----------------------------
INSERT INTO `yzn_admin` VALUES ('1', 'admin', '4459f1e16266d94ab6436a6743c838d97e9dca1f', '1', 'Wo0bAa', '御宅男', '1499349992', '0', '530765310@qq.com');
INSERT INTO `yzn_admin` VALUES ('2', 'ken678', 'abbcdc6e46d13db19e5b7e64ebcf44e625407165', '2', 'ILHWqH', '御宅男', '1499147342', '2130706433', '530765310@qq.com');

-- ----------------------------
-- Table structure for `yzn_admin_panel`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_admin_panel`;
CREATE TABLE `yzn_admin_panel` (
  `menuid` mediumint(8) unsigned NOT NULL COMMENT '菜单ID',
  `userid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '管理员ID',
  `name` char(32) DEFAULT NULL COMMENT '菜单名称',
  `url` char(255) DEFAULT NULL COMMENT '菜单路径',
  UNIQUE KEY `userid` (`userid`,`menuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='管理员快捷菜单';

-- ----------------------------
-- Records of yzn_admin_panel
-- ----------------------------
INSERT INTO `yzn_admin_panel` VALUES ('19', '1', '权限设置', 'Admin/AuthManager/index');
INSERT INTO `yzn_admin_panel` VALUES ('17', '1', '数据库备份', 'Admin/database/index');

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
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='权限组表';

-- ----------------------------
-- Records of yzn_auth_group
-- ----------------------------
INSERT INTO `yzn_auth_group` VALUES ('1', 'admin', '1', '超级管理员', '拥有所有权限', '1', '2,3,4,5,6,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,24,25,26,28,29');
INSERT INTO `yzn_auth_group` VALUES ('2', 'admin', '1', '测试用户', '部分低级权限', '1', '2,4,5,6,8,10,11,12,13,14,15,19,20,28,29');

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
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COMMENT='规则表';

-- ----------------------------
-- Records of yzn_auth_rule
-- ----------------------------
INSERT INTO `yzn_auth_rule` VALUES ('1', 'Admin', '1', 'Admin/Setting/index', '设置', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('2', 'Admin', '1', 'Admin/Manager/index', '管理员', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('3', 'Admin', '1', 'Admin/Manager/add', '添加管理员', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('4', 'Admin', '1', 'Admin/database/index', '应用', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('5', 'Admin', '1', 'Admin/database/repair_list', '数据库恢复', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('6', 'Admin', '2', 'Admin/Setting/index', '设置', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('7', 'Admin', '2', 'Admin/Content/index', '内容', '-1', '');
INSERT INTO `yzn_auth_rule` VALUES ('8', 'Admin', '1', 'Admin/Config/index', '站点配置', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('9', 'Admin', '1', 'Admin/Manager/edit', '编辑管理员', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('10', 'Admin', '1', 'Admin/AuthManager/index', '权限设置', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('11', 'Admin', '1', 'Admin/Config/extend', '扩展配置', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('12', 'Admin', '1', 'Admin/Action/actionlog', '操作日志', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('13', 'Admin', '1', 'Admin/database/optimize', '优化表', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('14', 'Admin', '1', 'Admin/database/repair', '修复表', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('15', 'Admin', '1', 'Admin/database/downfile', '下载表', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('16', 'Admin', '1', 'Admin/database/del', '删除表', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('17', 'Admin', '1', 'Admin/database/import', '还原表', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('18', 'Admin', '1', 'Admin/Manager/del', '删除管理员', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('19', 'Admin', '2', 'Admin/index/index', '首页', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('20', 'Admin', '1', 'Admin/Action/get_xml', '浏览操作日志', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('21', 'Admin', '1', 'Admin/Action/remove', '删除操作日志', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('22', 'Admin', '1', 'Admin/AuthManager/writeGroup', '编辑/创建权限组', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('23', 'Admin', '1', 'Admin/AuthManager/changeStatus', '权限组状态修改', '-1', '');
INSERT INTO `yzn_auth_rule` VALUES ('24', 'Admin', '1', 'Admin/AuthManager/access', '访问授权', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('25', 'Admin', '1', 'Admin/Menu/index', '后台菜单', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('26', 'Admin', '1', 'Admin/AuthManager/deleteGroup', '删除权限组', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('27', 'Content', '1', 'Content/Content/index', '内容管理', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('28', 'Content', '1', 'Content/Category/index', '栏目列表', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('29', 'Content', '2', 'Content/index/index', '内容', '1', '');

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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='缓存列队表';

-- ----------------------------
-- Records of yzn_cache
-- ----------------------------
INSERT INTO `yzn_cache` VALUES ('1', 'Model', '模型列表', 'Content', 'Models', 'model_cache', '0');
INSERT INTO `yzn_cache` VALUES ('2', 'Category', '栏目索引', 'Content', 'Category', 'category_cache', '0');

-- ----------------------------
-- Table structure for `yzn_category`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_category`;
CREATE TABLE `yzn_category` (
  `catid` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '栏目ID',
  `module` varchar(15) NOT NULL DEFAULT '' COMMENT '所属模块',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '类别',
  `modelid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '模型ID',
  `domain` varchar(200) NOT NULL DEFAULT '' COMMENT '栏目绑定域名',
  `parentid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `arrparentid` varchar(255) NOT NULL DEFAULT '' COMMENT '所有父ID',
  `child` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否存在子栏目，1存在',
  `arrchildid` mediumtext COMMENT '所有子栏目ID',
  `catname` varchar(30) NOT NULL DEFAULT '' COMMENT '栏目名称',
  `image` varchar(100) NOT NULL DEFAULT '' COMMENT '栏目图片',
  `description` mediumtext COMMENT '栏目描述',
  `parentdir` varchar(100) NOT NULL DEFAULT '' COMMENT '父目录',
  `catdir` varchar(30) NOT NULL DEFAULT '' COMMENT '栏目目录',
  `url` varchar(100) NOT NULL DEFAULT '' COMMENT '链接地址',
  `hits` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '栏目点击数',
  `setting` mediumtext COMMENT '相关配置信息',
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `ismenu` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示',
  `sethtml` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否生成静态',
  `letter` varchar(30) NOT NULL DEFAULT '' COMMENT '栏目拼音',
  PRIMARY KEY (`catid`),
  KEY `module` (`module`,`parentid`,`listorder`,`catid`),
  KEY `siteid` (`type`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COMMENT='栏目表';

-- ----------------------------
-- Records of yzn_category
-- ----------------------------
INSERT INTO `yzn_category` VALUES ('1', 'content', '0', '1', '', '0', '0', '1', '1,10,11', '网页教程', '', '网页教程-网页设计基础教程DIV+CSS', '', 'jiaocheng', '', '0', 'a:12:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:12:\"category.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";N;}', '0', '1', '0', 'wangyejiaocheng');
INSERT INTO `yzn_category` VALUES ('2', 'content', '0', '1', '', '0', '0', '1', '2,12,13', '前端开发', '', '前端开发-学习最新前端开发技术', '', 'qianduan', '/index.php?a=lists&catid=2', '0', 'a:12:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:12:\"category.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";N;}', '0', '1', '0', 'qianduankaifa');
INSERT INTO `yzn_category` VALUES ('3', 'content', '0', '1', '', '0', '0', '1', '3,14,15', 'PS教程', '', 'PS教程-学习PS技巧,设计更好的页面', '', 'ps', '/index.php?a=lists&catid=3', '0', 'a:12:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:12:\"category.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";N;}', '0', '1', '0', 'psjiaocheng');
INSERT INTO `yzn_category` VALUES ('4', 'content', '0', '1', '', '0', '0', '1', '4,16,17', '网页特效', '', '提供各种网页效果,让你的网页更炫丽', '', 'texiao', '/index.php?a=lists&catid=4', '0', 'a:12:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:17:\"category_heng.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";N;}', '0', '1', '0', 'wangyetexiao');
INSERT INTO `yzn_category` VALUES ('5', 'content', '0', '3', '', '0', '0', '1', '5,18,19', '建站素材', '', '建站素材-提供网页设计必备素材', '', 'sucai', '/index.php?a=lists&catid=5', '0', 'a:12:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:17:\"category_heng.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";N;}', '0', '1', '0', 'jianzhansucai');
INSERT INTO `yzn_category` VALUES ('6', 'content', '0', '1', '', '0', '0', '1', '6,20,21', '网站运营', '', '各种运营知识分享,助站长一臂之力', '', 'yunying', '/index.php?a=lists&catid=6', '0', 'a:12:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:12:\"category.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";N;}', '0', '1', '0', 'wangzhanyunying');
INSERT INTO `yzn_category` VALUES ('7', 'content', '0', '1', '', '0', '0', '1', '7,22,23', '站长杂谈', '', '站长杂谈-了解互联网第一手资讯', '', 'zatan', '/index.php?a=lists&catid=7', '0', 'a:12:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:12:\"category.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";N;}', '0', '1', '0', 'zhanchangzatan');
INSERT INTO `yzn_category` VALUES ('8', 'content', '0', '1', '', '0', '0', '1', '8,24,25', '设计欣赏', '', '设计欣赏-寻找设计灵感提高设计水平', '', 'sheji', '/index.php?a=lists&catid=8', '0', 'a:12:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:17:\"category_heng.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";N;}', '0', '1', '0', 'shejixinshang');
INSERT INTO `yzn_category` VALUES ('9', 'content', '0', '2', '', '0', '0', '0', '9', '常用软件', '', '', '', 'software', '/index.php?a=lists&catid=9', '0', 'a:14:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"list_template\";s:17:\"list_software.php\";s:13:\"show_template\";s:17:\"show_download.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:14:\"content_ishtml\";s:1:\"0\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";s:1:\"4\";}', '0', '1', '0', 'changyongruanjian');
INSERT INTO `yzn_category` VALUES ('10', 'content', '0', '1', '', '1', '0,1', '0', '10', 'HTML/XHTML', '', '', 'jiaocheng/', 'html', '/index.php?a=lists&catid=10', '0', 'a:14:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"list_template\";s:8:\"list.php\";s:13:\"show_template\";s:8:\"show.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:14:\"content_ishtml\";s:1:\"0\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";s:1:\"4\";}', '0', '1', '0', 'html/xhtml');
INSERT INTO `yzn_category` VALUES ('11', 'content', '0', '1', '', '1', '0,1', '0', '11', 'Dreamweaver', '', '', 'jiaocheng/', 'dw', '/index.php?a=lists&catid=11', '0', 'a:14:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"list_template\";s:8:\"list.php\";s:13:\"show_template\";s:8:\"show.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:14:\"content_ishtml\";s:1:\"0\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";s:1:\"4\";}', '0', '1', '0', 'dreamweaver');
INSERT INTO `yzn_category` VALUES ('12', 'content', '0', '1', '', '2', '0,2', '0', '12', 'javascript教程', '', '', 'qianduan/', 'js', '/index.php?a=lists&catid=12', '0', 'a:14:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"list_template\";s:8:\"list.php\";s:13:\"show_template\";s:8:\"show.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:14:\"content_ishtml\";s:1:\"0\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";s:1:\"4\";}', '0', '1', '0', 'javascriptjiaocheng');
INSERT INTO `yzn_category` VALUES ('13', 'content', '0', '1', '', '2', '0,2', '0', '13', 'jquery教程', '', '', 'qianduan/', 'jq', '/index.php?a=lists&catid=13', '0', 'a:14:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"list_template\";s:8:\"list.php\";s:13:\"show_template\";s:8:\"show.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:14:\"content_ishtml\";s:1:\"0\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";s:1:\"4\";}', '0', '1', '0', 'jqueryjiaocheng');
INSERT INTO `yzn_category` VALUES ('14', 'content', '0', '1', '', '3', '0,3', '0', '14', 'PS网页制作', '', '', 'ps/', 'psweb', '/index.php?a=lists&catid=14', '0', 'a:14:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"list_template\";s:8:\"list.php\";s:13:\"show_template\";s:8:\"show.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:14:\"content_ishtml\";s:1:\"0\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";s:1:\"4\";}', '0', '1', '0', 'pswangyezhizuo');
INSERT INTO `yzn_category` VALUES ('15', 'content', '0', '1', '', '3', '0,3', '0', '15', 'PS按钮制作', '', '', 'ps/', 'psbtn', '/index.php?a=lists&catid=15', '0', 'a:14:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"list_template\";s:8:\"list.php\";s:13:\"show_template\";s:8:\"show.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:14:\"content_ishtml\";s:1:\"0\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";s:1:\"4\";}', '0', '1', '0', 'psanniuzhizuo');
INSERT INTO `yzn_category` VALUES ('16', 'content', '0', '1', '', '4', '0,4', '0', '16', 'JS幻灯片', '', '', 'texiao/', 'adjs', '/index.php?a=lists&catid=16', '0', 'a:14:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"list_template\";s:8:\"list.php\";s:13:\"show_template\";s:8:\"show.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:14:\"content_ishtml\";s:1:\"0\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";s:1:\"4\";}', '0', '1', '0', 'jshuandengpian');
INSERT INTO `yzn_category` VALUES ('17', 'content', '0', '1', '', '4', '0,4', '0', '17', '导航菜单', '', '', 'texiao/', 'nav', '/index.php?a=lists&catid=17', '0', 'a:14:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"list_template\";s:8:\"list.php\";s:13:\"show_template\";s:8:\"show.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:14:\"content_ishtml\";s:1:\"0\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";s:1:\"4\";}', '0', '1', '0', 'daohangcaidan');
INSERT INTO `yzn_category` VALUES ('18', 'content', '0', '3', '', '5', '0,5', '0', '18', 'PNG图标', '', '', 'sucai/', 'png', '/index.php?a=lists&catid=18', '0', 'a:14:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"list_template\";s:8:\"list.php\";s:13:\"show_template\";s:14:\"show_image.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:14:\"content_ishtml\";s:1:\"0\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";s:1:\"4\";}', '0', '1', '0', 'pngtubiao');
INSERT INTO `yzn_category` VALUES ('19', 'content', '0', '3', '', '5', '0,5', '0', '19', 'GIF小图标', '', '', 'sucai/', 'gif', '/index.php?a=lists&catid=19', '0', 'a:14:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"list_template\";s:8:\"list.php\";s:13:\"show_template\";s:14:\"show_image.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:14:\"content_ishtml\";s:1:\"0\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";s:1:\"4\";}', '0', '1', '0', 'gifxiaotubiao');
INSERT INTO `yzn_category` VALUES ('20', 'content', '0', '1', '', '6', '0,6', '0', '20', 'SEO网站优化', '', '', 'yunying/', 'seo', '/index.php?a=lists&catid=20', '0', 'a:14:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"list_template\";s:8:\"list.php\";s:13:\"show_template\";s:8:\"show.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:14:\"content_ishtml\";s:1:\"0\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";s:1:\"4\";}', '0', '1', '0', 'seowangzhanyouhua');
INSERT INTO `yzn_category` VALUES ('21', 'content', '0', '1', '', '6', '0,6', '0', '21', '网络营销', '', '', 'yunying/', 'yingxiao', '/index.php?a=lists&catid=21', '0', 'a:14:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"list_template\";s:8:\"list.php\";s:13:\"show_template\";s:8:\"show.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:14:\"content_ishtml\";s:1:\"0\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";s:1:\"4\";}', '0', '1', '0', 'wangluoyingxiao');
INSERT INTO `yzn_category` VALUES ('22', 'content', '0', '1', '', '7', '0,7', '0', '22', '互联网资讯', '', '', 'zatan/', 'zixun', '/index.php?a=lists&catid=22', '0', 'a:14:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"list_template\";s:8:\"list.php\";s:13:\"show_template\";s:8:\"show.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:14:\"content_ishtml\";s:1:\"0\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";s:1:\"4\";}', '0', '1', '0', 'hulianwangzixun');
INSERT INTO `yzn_category` VALUES ('23', 'content', '0', '1', '', '7', '0,7', '0', '23', '人物访谈', '', '', 'zatan/', 'fangtan', '/index.php?a=lists&catid=23', '0', 'a:14:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"list_template\";s:8:\"list.php\";s:13:\"show_template\";s:8:\"show.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:14:\"content_ishtml\";s:1:\"0\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";s:1:\"4\";}', '0', '1', '0', 'renwufangtan');
INSERT INTO `yzn_category` VALUES ('24', 'content', '0', '1', '', '8', '0,8', '0', '24', '酷站欣赏', '', '', 'sheji/', 'web', '/index.php?a=lists&catid=24', '0', 'a:14:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"list_template\";s:8:\"list.php\";s:13:\"show_template\";s:11:\"show_da.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:14:\"content_ishtml\";s:1:\"0\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";s:1:\"4\";}', '0', '1', '0', 'kuzhanxinshang');
INSERT INTO `yzn_category` VALUES ('25', 'content', '0', '1', '', '8', '0,8', '0', '25', '标志LOGO', '', '', 'sheji/', 'logo', '/index.php?a=lists&catid=25', '0', 'a:14:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"list_template\";s:8:\"list.php\";s:13:\"show_template\";s:11:\"show_da.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:14:\"content_ishtml\";s:1:\"0\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";s:1:\"4\";}', '0', '1', '0', 'biaozhilogo');

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
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='网站配置表';

-- ----------------------------
-- Records of yzn_config
-- ----------------------------
INSERT INTO `yzn_config` VALUES ('1', 'site_title', '网站标题', '1', 'Yzncms内容管理框架 - Powered by Yzncms', '1');
INSERT INTO `yzn_config` VALUES ('2', 'site_keyword', '网站关键字', '1', 'ThinkPHP,tp5.0,yzncms,内容管理系统', '2');
INSERT INTO `yzn_config` VALUES ('3', 'site_description', '网站描述', '1', 'Yzncms内容管理框架,一套简单，易用，面向开发者的内容管理框,采用TP5.0框架开发', '3');
INSERT INTO `yzn_config` VALUES ('4', 'site_name', '网站名称', '1', 'Yzncms内容管理框架', '0');
INSERT INTO `yzn_config` VALUES ('5', 'icp', 'icp', '2', '苏ICP备15017030', '0');
INSERT INTO `yzn_config` VALUES ('6', 'close', '关闭站点', '2', '1', '0');

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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='网站扩展配置表';

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
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=utf8 COMMENT='后台菜单表';

-- ----------------------------
-- Records of yzn_menu
-- ----------------------------
INSERT INTO `yzn_menu` VALUES ('1', '设置', '0', 'Admin', 'Setting', 'index', '', '1', '', '0', '1');
INSERT INTO `yzn_menu` VALUES ('2', '内容', '0', 'Content', 'index', 'index', '', '1', '', '0', '2');
INSERT INTO `yzn_menu` VALUES ('5', '站点配置', '10', 'Admin', 'Config', 'index', '', '1', '', '0', '1');
INSERT INTO `yzn_menu` VALUES ('6', '管理员', '1', 'Admin', 'Manager', 'index', '', '1', '', '0', '1');
INSERT INTO `yzn_menu` VALUES ('9', '扩展配置', '5', 'Admin', 'Config', 'extend', '', '1', '', '0', '5');
INSERT INTO `yzn_menu` VALUES ('10', '设置', '1', 'Admin', 'Setting', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('12', '管理员管理', '6', 'Admin', 'Manager', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('13', '添加管理员', '12', 'Admin', 'Manager', 'add', '', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('14', '编辑管理员', '12', 'Admin', 'Manager', 'edit', '', '0', '', '0', '1');
INSERT INTO `yzn_menu` VALUES ('15', '操作日志', '10', 'Admin', 'Action', 'actionlog', '', '1', '', '0', '10');
INSERT INTO `yzn_menu` VALUES ('16', '应用', '1', 'Admin', 'database', 'index', '', '1', '', '0', '3');
INSERT INTO `yzn_menu` VALUES ('17', '数据库备份', '16', 'Admin', 'database', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('18', '数据库恢复', '17', 'Admin', 'database', 'repair_list', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('19', '权限设置', '10', 'Admin', 'AuthManager', 'index', '', '1', '', '0', '3');
INSERT INTO `yzn_menu` VALUES ('20', '优化表', '17', 'Admin', 'database', 'optimize', '', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('21', '修复表', '17', 'Admin', 'database', 'repair', '', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('22', '下载表', '17', 'Admin', 'database', 'downfile', '', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('23', '删除表', '17', 'Admin', 'database', 'del', '', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('24', '还原表', '17', 'Admin', 'database', 'import', '', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('25', '删除管理员', '12', 'Admin', 'Manager', 'del', '', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('26', '首页', '0', 'Admin', 'index', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('27', '浏览操作日志', '15', 'Admin', 'Action', 'get_xml', '', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('28', '删除操作日志', '15', 'Admin', 'Action', 'remove', '', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('29', '查看权限组', '19', 'Admin', 'AuthManager', 'index', '', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('31', '删除权限组', '19', 'Admin', 'AuthManager', 'deleteGroup', '', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('30', '编辑/创建权限组', '19', 'Admin', 'AuthManager', 'writeGroup', '', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('32', '访问授权', '19', 'Admin', 'AuthManager', 'access', '', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('33', '后台菜单', '10', 'Admin', 'Menu', 'index', '', '1', '', '0', '10');
INSERT INTO `yzn_menu` VALUES ('34', '内容管理', '2', 'Content', 'Content', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('35', '相关设置', '2', 'Content', 'Category', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('36', '栏目列表', '35', 'Content', 'Category', 'index', '', '1', '', '0', '0');

-- ----------------------------
-- Table structure for `yzn_model`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_model`;
CREATE TABLE `yzn_model` (
  `modelid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(30) NOT NULL DEFAULT '' COMMENT '模型名称',
  `description` char(100) NOT NULL DEFAULT '' COMMENT '描述',
  `tablename` char(20) NOT NULL DEFAULT '' COMMENT '表名',
  `setting` text COMMENT '配置信息',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `items` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '信息数',
  `enablesearch` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启全站搜索',
  `disabled` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否禁用 1禁用',
  `default_style` char(30) NOT NULL DEFAULT '' COMMENT '风格',
  `category_template` char(30) NOT NULL DEFAULT '' COMMENT '栏目模板',
  `list_template` char(30) NOT NULL DEFAULT '' COMMENT '列表模板',
  `show_template` char(30) NOT NULL DEFAULT '' COMMENT '内容模板',
  `js_template` varchar(30) NOT NULL DEFAULT '' COMMENT 'JS模板',
  `sort` tinyint(3) NOT NULL DEFAULT '0' COMMENT '排序',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '模块标识',
  PRIMARY KEY (`modelid`),
  KEY `type` (`type`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='内容模型列表';

-- ----------------------------
-- Records of yzn_model
-- ----------------------------
INSERT INTO `yzn_model` VALUES ('1', '文章模型', '文章模型', 'article', '', '1403150253', '0', '1', '0', '', '', '', '', '', '0', '0');
INSERT INTO `yzn_model` VALUES ('2', '下载模型', '下载模型', 'download', '', '1403153866', '0', '1', '0', '', '', '', '', '', '0', '0');
INSERT INTO `yzn_model` VALUES ('3', '图片模型', '图片模型', 'photo', '', '1403153881', '0', '1', '0', '', '', '', '', '', '0', '0');
