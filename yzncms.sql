/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : yzncms

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2017-06-20 09:52:05
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
) ENGINE=MyISAM AUTO_INCREMENT=122 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='行为日志表';

-- ----------------------------
-- Records of yzn_action_log
-- ----------------------------
INSERT INTO `yzn_action_log` VALUES ('35', '1', '1', '2130706433', 'member', '1', 'admin在2017-05-18 12:26登录了后台', '1495081597');
INSERT INTO `yzn_action_log` VALUES ('34', '1', '1', '2130706433', 'member', '1', 'admin在2017-05-18 08:06登录了后台', '1495065962');
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
INSERT INTO `yzn_action_log` VALUES ('32', '1', '1', '2130706433', 'member', '1', 'admin在2017-05-15 17:31登录了后台', '1494840674');
INSERT INTO `yzn_action_log` VALUES ('33', '1', '1', '2130706433', 'member', '1', 'admin在2017-05-17 16:56登录了后台', '1495011414');
INSERT INTO `yzn_action_log` VALUES ('36', '1', '1', '2130706433', 'member', '1', 'admin在2017-05-18 16:30登录了后台', '1495096205');
INSERT INTO `yzn_action_log` VALUES ('37', '1', '1', '2130706433', 'member', '1', 'admin在2017-05-19 08:46登录了后台', '1495154788');
INSERT INTO `yzn_action_log` VALUES ('38', '1', '1', '2130706433', 'member', '1', 'admin在2017-05-22 11:07登录了后台', '1495422456');
INSERT INTO `yzn_action_log` VALUES ('39', '1', '1', '2130706433', 'member', '1', 'admin在2017-05-22 14:13登录了后台', '1495433633');
INSERT INTO `yzn_action_log` VALUES ('40', '1', '1', '2130706433', 'member', '1', 'admin在2017-05-22 16:54登录了后台', '1495443254');
INSERT INTO `yzn_action_log` VALUES ('41', '1', '1', '2130706433', 'member', '1', 'admin在2017-05-23 08:44登录了后台', '1495500256');
INSERT INTO `yzn_action_log` VALUES ('42', '1', '1', '2130706433', 'member', '1', 'admin在2017-05-23 10:19登录了后台', '1495505986');
INSERT INTO `yzn_action_log` VALUES ('43', '1', '1', '2130706433', 'member', '1', 'admin在2017-05-23 10:50登录了后台', '1495507826');
INSERT INTO `yzn_action_log` VALUES ('44', '1', '1', '2130706433', 'member', '1', 'admin在2017-05-23 11:33登录了后台', '1495510432');
INSERT INTO `yzn_action_log` VALUES ('45', '1', '1', '2130706433', 'member', '1', 'admin在2017-05-23 17:28登录了后台', '1495531714');
INSERT INTO `yzn_action_log` VALUES ('46', '1', '1', '2130706433', 'member', '1', 'admin在2017-05-24 08:24登录了后台', '1495585442');
INSERT INTO `yzn_action_log` VALUES ('47', '1', '1', '2130706433', 'member', '1', 'admin在2017-05-24 17:32登录了后台', '1495618327');
INSERT INTO `yzn_action_log` VALUES ('48', '1', '1', '2130706433', 'member', '1', 'admin在2017-05-25 08:03登录了后台', '1495670624');
INSERT INTO `yzn_action_log` VALUES ('49', '1', '1', '2130706433', 'member', '1', 'admin在2017-05-26 11:04登录了后台', '1495767859');
INSERT INTO `yzn_action_log` VALUES ('50', '1', '1', '2130706433', 'member', '1', 'admin在2017-05-26 16:52登录了后台', '1495788759');
INSERT INTO `yzn_action_log` VALUES ('53', '1', '1', '2130706433', 'member', '1', 'admin在2017-05-27 09:06登录了后台', '1495847205');
INSERT INTO `yzn_action_log` VALUES ('55', '1', '1', '2130706433', 'member', '1', 'admin在2017-05-27 14:56登录了后台', '1495868189');
INSERT INTO `yzn_action_log` VALUES ('56', '1', '1', '2130706433', 'member', '1', 'admin在2017-05-27 17:36登录了后台', '1495877769');
INSERT INTO `yzn_action_log` VALUES ('57', '1', '1', '2130706433', 'member', '1', 'admin在2017-05-31 11:08登录了后台', '1496200085');
INSERT INTO `yzn_action_log` VALUES ('58', '1', '1', '2130706433', 'member', '1', 'admin在2017-05-31 11:33登录了后台', '1496201600');
INSERT INTO `yzn_action_log` VALUES ('59', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-01 11:41登录了后台', '1496288460');
INSERT INTO `yzn_action_log` VALUES ('60', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-01 13:37登录了后台', '1496295424');
INSERT INTO `yzn_action_log` VALUES ('61', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-02 17:17登录了后台', '1496395021');
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
INSERT INTO `yzn_admin` VALUES ('1', 'admin', '4459f1e16266d94ab6436a6743c838d97e9dca1f', '1', 'Wo0bAa', '御宅男', '1497923477', '2130706433', '530765310@qq.com');
INSERT INTO `yzn_admin` VALUES ('2', 'ken678', 'abbcdc6e46d13db19e5b7e64ebcf44e625407165', '2', 'ILHWqH', '御宅男', '1497431115', '2130706433', '530765310@qq.com');

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
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='权限组表';

-- ----------------------------
-- Records of yzn_auth_group
-- ----------------------------
INSERT INTO `yzn_auth_group` VALUES ('1', 'admin', '1', '超级管理员', '拥有所有权限', '1', '2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24');
INSERT INTO `yzn_auth_group` VALUES ('2', 'admin', '1', '测试用户', '部分低级权限', '1', '2,4,5,6,7,8,10,11,12,13,14,15,19,20');

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
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COMMENT='规则表';

-- ----------------------------
-- Records of yzn_auth_rule
-- ----------------------------
INSERT INTO `yzn_auth_rule` VALUES ('1', 'Admin', '1', 'Admin/Setting/index', '设置', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('2', 'Admin', '1', 'Admin/Manager/index', '管理员', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('3', 'Admin', '1', 'Admin/Manager/add', '添加管理员', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('4', 'Admin', '1', 'Admin/database/index', '应用', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('5', 'Admin', '1', 'Admin/database/repair_list', '数据库恢复', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('6', 'Admin', '2', 'Admin/Setting/index', '设置', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('7', 'Admin', '2', 'Admin/Content/index', '内容', '1', '');
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
INSERT INTO `yzn_auth_rule` VALUES ('23', 'Admin', '1', 'Admin/AuthManager/changeStatus', '权限组状态修改', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('24', 'Admin', '1', 'Admin/AuthManager/access', '访问授权', '1', '');

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
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COMMENT='后台菜单表';

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
INSERT INTO `yzn_menu` VALUES ('31', '权限组状态修改', '19', 'Admin', 'AuthManager', 'changeStatus', '', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('30', '编辑/创建权限组', '19', 'Admin', 'AuthManager', 'writeGroup', '', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('32', '访问授权', '19', 'Admin', 'AuthManager', 'access', '', '0', '', '0', '0');
