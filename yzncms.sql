/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : yzncms

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2017-09-21 18:10:47
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
) ENGINE=MyISAM AUTO_INCREMENT=290 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='行为日志表';

-- ----------------------------
-- Records of yzn_action_log
-- ----------------------------
INSERT INTO `yzn_action_log` VALUES ('260', '1', '1', '2130706433', 'member', '1', 'admin在2017-09-07 15:23登录了后台', '1504769020');
INSERT INTO `yzn_action_log` VALUES ('259', '1', '1', '2130706433', 'member', '1', 'admin在2017-09-07 14:36登录了后台', '1504766202');
INSERT INTO `yzn_action_log` VALUES ('258', '1', '1', '2130706433', 'member', '1', 'admin在2017-09-07 13:14登录了后台', '1504761251');
INSERT INTO `yzn_action_log` VALUES ('286', '1', '1', '2130706433', 'member', '1', 'admin在2017-09-19 20:02登录了后台', '1505822545');
INSERT INTO `yzn_action_log` VALUES ('285', '1', '1', '2130706433', 'member', '1', 'admin在2017-09-19 19:15登录了后台', '1505819735');
INSERT INTO `yzn_action_log` VALUES ('284', '1', '1', '2130706433', 'member', '1', 'admin在2017-09-17 20:33登录了后台', '1505651635');
INSERT INTO `yzn_action_log` VALUES ('283', '1', '1', '2130706433', 'member', '1', 'admin在2017-09-17 11:31登录了后台', '1505619099');
INSERT INTO `yzn_action_log` VALUES ('196', '1', '1', '0', 'member', '1', 'admin在2017-08-18 19:57登录了后台', '1503057476');
INSERT INTO `yzn_action_log` VALUES ('197', '1', '1', '0', 'member', '1', 'admin在2017-08-19 10:32登录了后台', '1503109929');
INSERT INTO `yzn_action_log` VALUES ('198', '1', '1', '0', 'member', '1', 'admin在2017-08-19 17:51登录了后台', '1503136274');
INSERT INTO `yzn_action_log` VALUES ('199', '1', '1', '0', 'member', '1', 'admin在2017-08-20 12:22登录了后台', '1503202955');
INSERT INTO `yzn_action_log` VALUES ('200', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-21 11:35登录了后台', '1503286556');
INSERT INTO `yzn_action_log` VALUES ('201', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-21 12:31登录了后台', '1503289862');
INSERT INTO `yzn_action_log` VALUES ('202', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-21 12:49登录了后台', '1503290949');
INSERT INTO `yzn_action_log` VALUES ('203', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-21 13:12登录了后台', '1503292321');
INSERT INTO `yzn_action_log` VALUES ('204', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-21 13:30登录了后台', '1503293433');
INSERT INTO `yzn_action_log` VALUES ('205', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-21 17:35登录了后台', '1503308145');
INSERT INTO `yzn_action_log` VALUES ('206', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-22 08:07登录了后台', '1503360453');
INSERT INTO `yzn_action_log` VALUES ('207', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-22 12:34登录了后台', '1503376460');
INSERT INTO `yzn_action_log` VALUES ('208', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-22 15:29登录了后台', '1503386953');
INSERT INTO `yzn_action_log` VALUES ('209', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-23 10:24登录了后台', '1503455044');
INSERT INTO `yzn_action_log` VALUES ('210', '1', '1', '0', 'member', '1', 'admin在2017-08-25 18:59登录了后台', '1503658754');
INSERT INTO `yzn_action_log` VALUES ('211', '1', '1', '0', 'member', '1', 'admin在2017-08-25 21:10登录了后台', '1503666655');
INSERT INTO `yzn_action_log` VALUES ('212', '1', '1', '0', 'member', '1', 'admin在2017-08-26 10:57登录了后台', '1503716238');
INSERT INTO `yzn_action_log` VALUES ('213', '1', '1', '0', 'member', '1', 'admin在2017-08-26 14:34登录了后台', '1503729251');
INSERT INTO `yzn_action_log` VALUES ('214', '1', '1', '0', 'member', '1', 'admin在2017-08-26 20:42登录了后台', '1503751328');
INSERT INTO `yzn_action_log` VALUES ('215', '1', '1', '0', 'member', '1', 'admin在2017-08-27 11:43登录了后台', '1503805428');
INSERT INTO `yzn_action_log` VALUES ('216', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-28 08:20登录了后台', '1503879651');
INSERT INTO `yzn_action_log` VALUES ('217', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-28 08:24登录了后台', '1503879879');
INSERT INTO `yzn_action_log` VALUES ('218', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-28 08:43登录了后台', '1503880985');
INSERT INTO `yzn_action_log` VALUES ('219', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-28 09:38登录了后台', '1503884323');
INSERT INTO `yzn_action_log` VALUES ('220', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-28 17:37登录了后台', '1503913021');
INSERT INTO `yzn_action_log` VALUES ('221', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-29 11:32登录了后台', '1503977545');
INSERT INTO `yzn_action_log` VALUES ('222', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-29 12:31登录了后台', '1503981074');
INSERT INTO `yzn_action_log` VALUES ('233', '1', '1', '2130706433', 'member', '1', 'admin在2017-09-01 15:47登录了后台', '1504252033');
INSERT INTO `yzn_action_log` VALUES ('234', '1', '1', '0', 'member', '1', 'admin在2017-09-02 18:17登录了后台', '1504347443');
INSERT INTO `yzn_action_log` VALUES ('235', '1', '1', '0', 'member', '1', 'admin在2017-09-03 15:19登录了后台', '1504423140');
INSERT INTO `yzn_action_log` VALUES ('236', '1', '1', '0', 'member', '1', 'admin在2017-09-03 15:50登录了后台', '1504425031');
INSERT INTO `yzn_action_log` VALUES ('237', '1', '1', '0', 'member', '1', 'admin在2017-09-03 16:42登录了后台', '1504428132');
INSERT INTO `yzn_action_log` VALUES ('238', '1', '1', '0', 'member', '1', 'admin在2017-09-03 17:48登录了后台', '1504432092');
INSERT INTO `yzn_action_log` VALUES ('239', '1', '1', '2130706433', 'member', '1', 'admin在2017-09-03 18:10登录了后台', '1504433456');
INSERT INTO `yzn_action_log` VALUES ('240', '1', '1', '2130706433', 'member', '1', 'admin在2017-09-04 09:03登录了后台', '1504486988');
INSERT INTO `yzn_action_log` VALUES ('241', '1', '1', '2130706433', 'member', '1', 'admin在2017-09-04 09:14登录了后台', '1504487662');
INSERT INTO `yzn_action_log` VALUES ('242', '1', '1', '2130706433', 'member', '1', 'admin在2017-09-05 09:06登录了后台', '1504573600');
INSERT INTO `yzn_action_log` VALUES ('243', '1', '1', '2130706433', 'member', '1', 'admin在2017-09-05 11:01登录了后台', '1504580476');
INSERT INTO `yzn_action_log` VALUES ('244', '1', '1', '2130706433', 'member', '1', 'admin在2017-09-05 13:28登录了后台', '1504589303');
INSERT INTO `yzn_action_log` VALUES ('245', '1', '1', '2130706433', 'member', '1', 'admin在2017-09-05 15:06登录了后台', '1504595204');
INSERT INTO `yzn_action_log` VALUES ('256', '1', '1', '2130706433', 'member', '1', 'admin在2017-09-07 11:39登录了后台', '1504755592');
INSERT INTO `yzn_action_log` VALUES ('261', '1', '1', '2130706433', 'member', '1', 'admin在2017-09-07 16:55登录了后台', '1504774542');
INSERT INTO `yzn_action_log` VALUES ('262', '1', '1', '2130706433', 'member', '1', 'admin在2017-09-08 08:44登录了后台', '1504831462');
INSERT INTO `yzn_action_log` VALUES ('263', '1', '1', '2130706433', 'member', '1', 'admin在2017-09-08 10:43登录了后台', '1504838587');
INSERT INTO `yzn_action_log` VALUES ('264', '1', '1', '2130706433', 'member', '1', 'admin在2017-09-08 15:46登录了后台', '1504856764');
INSERT INTO `yzn_action_log` VALUES ('265', '1', '1', '2130706433', 'member', '1', 'admin在2017-09-08 17:04登录了后台', '1504861484');
INSERT INTO `yzn_action_log` VALUES ('266', '1', '1', '2130706433', 'member', '1', 'admin在2017-09-08 19:55登录了后台', '1504871752');
INSERT INTO `yzn_action_log` VALUES ('267', '1', '1', '2130706433', 'member', '1', 'admin在2017-09-09 10:49登录了后台', '1504925360');
INSERT INTO `yzn_action_log` VALUES ('268', '1', '1', '2130706433', 'member', '1', 'admin在2017-09-09 13:13登录了后台', '1504933980');
INSERT INTO `yzn_action_log` VALUES ('269', '1', '1', '2130706433', 'member', '1', 'admin在2017-09-09 18:00登录了后台', '1504951234');
INSERT INTO `yzn_action_log` VALUES ('270', '1', '1', '2130706433', 'member', '1', 'admin在2017-09-10 11:11登录了后台', '1505013111');
INSERT INTO `yzn_action_log` VALUES ('271', '1', '1', '2130706433', 'member', '1', 'admin在2017-09-10 15:36登录了后台', '1505028961');
INSERT INTO `yzn_action_log` VALUES ('272', '1', '1', '2130706433', 'member', '1', 'admin在2017-09-11 08:36登录了后台', '1505090212');
INSERT INTO `yzn_action_log` VALUES ('287', '1', '1', '2130706433', 'member', '1', 'admin在2017-09-20 15:14登录了后台', '1505891684');
INSERT INTO `yzn_action_log` VALUES ('288', '1', '1', '2130706433', 'member', '1', 'admin在2017-09-21 13:07登录了后台', '1505970458');
INSERT INTO `yzn_action_log` VALUES ('289', '1', '1', '2130706433', 'member', '1', 'admin在2017-09-21 17:44登录了后台', '1505987094');

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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='管理员表';

-- ----------------------------
-- Records of yzn_admin
-- ----------------------------
INSERT INTO `yzn_admin` VALUES ('1', 'admin', '4459f1e16266d94ab6436a6743c838d97e9dca1f', '1', 'Wo0bAa', '御宅男', '1505987094', '2130706433', '530765310@qq.com');
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
-- Table structure for `yzn_article`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_article`;
CREATE TABLE `yzn_article` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `title` varchar(160) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `style` char(24) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `thumb` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `keywords` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `description` mediumtext COLLATE utf8_unicode_ci,
  `url` char(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `listorder` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `sysadd` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `islink` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `username` char(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `inputtime` int(10) unsigned NOT NULL DEFAULT '0',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0',
  `posid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `prefix` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `tags` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `views` int(11) NOT NULL DEFAULT '0' COMMENT '点击总数',
  `yesterdayviews` int(11) NOT NULL DEFAULT '0' COMMENT '最日',
  `dayviews` int(10) NOT NULL DEFAULT '0' COMMENT '今日点击数',
  `weekviews` int(10) NOT NULL DEFAULT '0' COMMENT '本周访问数',
  `monthviews` int(10) NOT NULL DEFAULT '0' COMMENT '本月访问',
  `viewsupdatetime` int(10) NOT NULL DEFAULT '0' COMMENT '点击数更新时间',
  PRIMARY KEY (`id`),
  KEY `status` (`status`,`listorder`,`id`),
  KEY `listorder` (`catid`,`status`,`listorder`,`id`),
  KEY `catid` (`catid`,`weekviews`,`views`,`dayviews`,`monthviews`,`status`,`id`),
  KEY `thumb` (`thumb`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of yzn_article
-- ----------------------------
INSERT INTO `yzn_article` VALUES ('8', '16', '仿数字尾巴官网进度条jquery焦点图效果', '', 'http://www.yzncms.com/demo/file/2013/06/51c52a921a436.jpg', '进度条焦点图,时间轴焦点图,jquery焦点图,jquery插件', '仿数字尾巴官网进度条jquery焦点图效果 效果描述：jquery焦点图插件，带索引按钮，带进度条时间轴，支持自动切换，带入淡出的切换效果', '/index.php?a=shows&catid=16&id=8', '0', '99', '1', '0', 'admin', '1371875986', '1371875986', '0', '', 'jQuery', '0', '0', '0', '0', '0', '0');
INSERT INTO `yzn_article` VALUES ('9', '16', 'jquery前后图片预览焦点图效果', '', 'http://www.yzncms.com/demo/file/2013/06/51c52ad03bc3f.jpg', 'jquery焦点图,左右滚动幻灯片,前后图片预览焦点图,jquery插件', 'jquery前后图片预览焦点图效果 效果描述：jquery焦点图效果，鼠标经过左右两边半透明部分显示左右箭头，点击左右滚动切换，支持自动切换！', '/index.php?a=shows&catid=16&id=9', '0', '99', '1', '0', 'admin', '1371876048', '1371876048', '0', '', 'jQuery', '0', '0', '0', '0', '0', '0');
INSERT INTO `yzn_article` VALUES ('10', '16', 'jquery全屏左右预览焦点图代码', '', 'http://www.yzncms.com/demo/file/2013/06/51c52b189fa73.jpg', '全屏焦点图,jquery幻灯片,左右箭头焦点图,jquery焦点图', 'jquery全屏左右预览焦点图代码 效果描述：jquery全屏焦点图，带图片前后图片预览效果，鼠标经过左右两边半透明部分显示左右箭头，带索引按钮，支持自动切换！', '/index.php?a=shows&catid=16&id=10', '0', '99', '1', '0', 'admin', '1371876121', '1371876121', '0', '', 'jQuery', '0', '0', '0', '0', '0', '0');
INSERT INTO `yzn_article` VALUES ('11', '17', 'zTree 一款基于jquery的超强树形菜单', '', 'http://www.yzncms.com/demo/file/2013/06/51c52e859571e.jpg', 'zTree,树形菜单,树状菜单,jquery树形菜单', 'zTree 一款基于jquery的超强树形菜单，支持无限级别扩展，含API说明！下面是 v3.5.12 的修改记录：  * 【修改】由于 jquery 1.9 ...', '/index.php?a=shows&catid=17&id=11', '0', '99', '1', '0', 'admin', '1371876998', '1371876998', '0', '', 'zTree', '0', '0', '0', '0', '0', '0');
INSERT INTO `yzn_article` VALUES ('16', '10', '初学必看：html+css小总结', '', '', 'html+css小总结,html+css教程,div+css', '初学必看：html+css小总结经验教程！', '/index.php?a=shows&catid=10&id=16', '0', '99', '1', '0', 'admin', '1371881631', '1371881631', '0', '', '', '1', '0', '1', '1', '1', '1403154346');
INSERT INTO `yzn_article` VALUES ('17', '10', '针对IE版本的if表达式', '', '', '针对,版本,表达式,TML,注释,格式,HTML,做了,一些', 'TML 的注释格式是 ， IE 对HTML注释做了一些扩展，使之可以支持条件判断表达式，如何让静态HTML代码在不同IE版本显示不同内容？和编程...', '/index.php?a=shows&catid=10&id=17', '0', '99', '1', '0', 'admin', '1371881683', '1371881683', '0', '', '', '0', '0', '0', '0', '0', '0');
INSERT INTO `yzn_article` VALUES ('18', '10', '关于input标签的一些小知识和技巧', '', '', '关于,input,标签,些小,知识,技巧,分享,一些,常用,限制,input,', '分享一些常用限制input的方法，可能里面也有一些你需要用的，可能也有一些你值得学习的！1.取消按钮按下时的虚线框,在input里添加属性值 h...', '/index.php?a=shows&catid=10&id=18', '0', '99', '1', '0', 'admin', '1371881732', '1371881732', '0', '', '', '0', '0', '0', '0', '0', '0');
INSERT INTO `yzn_article` VALUES ('19', '10', '5种实现页面跳转到指定的地址的方法', '', '', 'JS实现页面跳转,html实现页面跳转,实现页面跳转的方法', '下面列了五个例子来详细说明，这几个例子的主要功能是：在5秒后，自动跳转到同目录下的hello.html（根据自己需要自行修改）文件。1) html...', '/index.php?a=shows&catid=10&id=19', '0', '99', '1', '0', 'admin', '1371881773', '1371881773', '0', '', '', '0', '0', '0', '0', '0', '0');
INSERT INTO `yzn_article` VALUES ('20', '10', 'html注释条件语句!--[if IE]...![endif]--使用详细介绍', '', '', 'html注释语句,条件注释,html教程,[if IE]', '代码如下: 用上面这段代码，只有使用IE时加载97zzw...', '/index.php?a=shows&catid=10&id=20', '0', '99', '1', '0', 'admin', '1371881819', '1371881819', '0', '', '', '0', '0', '0', '0', '0', '0');
INSERT INTO `yzn_article` VALUES ('25', '10', 'Nofollow标签是什么意思', '', 'http://www.yzncms.com/demo/file/2013/06/51c551df1213e.jpg', 'Nofollow,html标签,html教程,什么是Nofollow', 'nofollow 是一个HTML标签的属性值。这个标签的意义是告诉搜索引擎&quot;不要追踪此网页上的链接&quot;或&quot;不要追踪此特定链接。　　nofollow简介　　n...', '/index.php?a=shows&catid=10&id=25', '0', '99', '1', '0', 'admin', '1371886048', '1371886048', '0', '', '', '0', '0', '0', '0', '0', '0');
INSERT INTO `yzn_article` VALUES ('29', '10', '5种实现页面跳转到指定的地址的方法', '', '', 'JS实现页面跳转,html实现页面跳转,实现页面跳转的方法', '下面列了五个例子来详细说明，这几个例子的主要功能是：在5秒后，自动跳转到同目录下的hello.html（根据自己需要自行修改）文件。1) html...', '/index.php?a=shows&catid=10&id=29', '0', '99', '1', '0', 'admin', '1371907845', '1371907845', '0', '', '', '0', '0', '0', '0', '0', '0');

-- ----------------------------
-- Table structure for `yzn_article_data`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_article_data`;
CREATE TABLE `yzn_article_data` (
  `id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `content` mediumtext COLLATE utf8_unicode_ci,
  `paginationtype` tinyint(1) NOT NULL DEFAULT '0',
  `maxcharperpage` mediumint(6) NOT NULL DEFAULT '0',
  `template` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `paytype` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `allow_comment` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `relation` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `copyfrom` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of yzn_article_data
-- ----------------------------
INSERT INTO `yzn_article_data` VALUES ('8', '<p><span style=\"color:#666666;background-color:#ffffff;\">仿数字尾巴官网进度条jquery焦点图效果<br /><br />效果描述：jquery焦点图插件，带索引按钮，带进度条时间轴，支持自动切换，带入淡出的切换效果<br style=\"color:#666666;font-size:12px;line-height:18px;background-color:#ffffff;\" /><br style=\"color:#666666;font-size:12px;line-height:18px;background-color:#ffffff;\" /></span></p><div><p style=\"text-align:center\"><img src=\"http://www.lvyecms.com/demo/file/2013/06/51c52a921a436.jpg\" style=\"border-style:none;max-width:680px;\" /></p></div><div><br /></div><p><br /></p>', '2', '10000', '', '0', '1', '', '');
INSERT INTO `yzn_article_data` VALUES ('9', '<p>jquery前后图片预览焦点图效果<br /><br />效果描述：jquery焦点图效果，鼠标经过左右两边半透明部分显示左右箭头，点击左右滚动切换，支持自动切换！<br style=\"color:#666666;font-size:12px;line-height:18px;background-color:#ffffff;\" /><br style=\"color:#666666;font-size:12px;line-height:18px;background-color:#ffffff;\" /></p><div><p style=\"text-align:center\"><img src=\"http://www.lvyecms.com/demo/file/2013/06/51c52ad03bc3f.jpg\" style=\"border-style:none;max-width:680px;\" /></p></div><p><br /></p>', '2', '10000', '', '0', '1', '', '');
INSERT INTO `yzn_article_data` VALUES ('10', '<p>jquery全屏左右预览焦点图代码<br /><br />效果描述：jquery全屏焦点图，带图片前后图片预览效果，鼠标经过左右两边半透明部分显示左右箭头，带索引按钮，支持自动切换！<br style=\"color:#666666;font-size:12px;line-height:18px;background-color:#ffffff;\" /><br style=\"color:#666666;font-size:12px;line-height:18px;background-color:#ffffff;\" /></p><div><p style=\"text-align:center\"><img src=\"http://www.lvyecms.com/demo/file/2013/06/51c52b189fa73.jpg\" style=\"border-style:none;max-width:680px;\" /></p></div><p><br /></p>', '2', '10000', '', '0', '1', '', '');
INSERT INTO `yzn_article_data` VALUES ('11', '<p style=\"text-indent:2em;\">zTree 一款基于jquery的超强树形菜单，支持无限级别扩展，含API说明！<br /></p><p style=\"text-align:center\"><img src=\"http://www.lvyecms.com/demo/file/2013/06/51c52e859571e.jpg\" style=\"border-style:none;max-width:680px;width:650px;height:427px;\" /></p><p style=\"text-indent:2em;\">下面是 v3.5.12 的修改记录：</p><p style=\"text-indent:2em;\"> &nbsp; * 【修改】由于 jquery 1.9 中移除 event.srcElement 导致的 js 报错的bug。</p><p style=\"text-indent:2em;\"> &nbsp; * 【修改】在异步加载模式下，使用 moveNode 方法，且 moveType != &quot;inner&quot; 时，也会导致 targetNode 自动加载子节点的 bug</p><p style=\"text-indent:2em;\"> &nbsp; * 【修改】对已经显示的节点(nochecked=true)使用 showNodes 或 showNode 方法后，导致勾选框出现的bug。</p><p style=\"text-indent:2em;\"> &nbsp; * 【修改】对已经隐藏的节点(nochecked=false)使用 hideNodes 或 hideNode 方法后，导致勾选框消失的bug。</p><p style=\"text-indent:2em;\"> &nbsp; * 【修改】getNodesByParamFuzzy 支持 大小写模糊。</p><p style=\"text-indent:2em;\"> &nbsp; * 【修改】className 结构，提取 _consts.className.BUTTON / LEVEL / ICO_LOADING / SWITCH，便于快速修改 css 冲突。</p><p style=\"text-indent:2em;\"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;例如：与 WordPress 产生冲突后，直接修改 core 中的 &quot;button&quot; 和 &quot;level&quot; 即可。</p><p><br /></p>', '2', '10000', '', '0', '1', '', '');
INSERT INTO `yzn_article_data` VALUES ('16', '<p style=\"padding:0.5em 0px;color:#222222;font-size:14px;background-color:#ffffff;line-height:24px;margin-top:0px;margin-bottom:0px;\"><strong style=\"font-weight:bolder;\">1、块级元素</strong></p><p style=\"padding:0.5em 0px;color:#222222;font-size:14px;background-color:#ffffff;line-height:24px;margin-top:0px;margin-bottom:0px;\">&lt;div&gt; &lt;h1&gt; &lt;hr /&gt; &lt;p&gt; &lt;pre&gt; &lt;ol&gt; &lt;ul&gt; &lt;li&gt; &lt;dl&gt; &lt;dt&gt; &lt;dd&gt; &lt;table&gt; &lt;tr&gt; &lt;td&gt; &lt;colgroup&gt; &lt;col&gt; &lt;form&gt;等<br/>会换行，想在同一行显示需浮动或者display: inline</p><p style=\"padding:0.5em 0px;color:#222222;font-size:14px;background-color:#ffffff;line-height:24px;margin-top:0px;margin-bottom:0px;\"><strong style=\"font-weight:bolder;\">2、行内元素</strong></p><p style=\"padding:0.5em 0px;color:#222222;font-size:14px;background-color:#ffffff;line-height:24px;margin-top:0px;margin-bottom:0px;\">&lt;span&gt; &lt;var&gt; &lt;a&gt; &lt;em&gt; &lt;img&gt; &lt;b&gt; &lt;i&gt; &lt;sub&gt; &lt;textarea&gt; &lt;select&gt; &lt;strong&gt; &lt;br /&gt;等<br/>多个可以并排显示，默认设置宽度是不起作用的，需设置 display: inline-block或者block才行，或者加padding-left和padding-right。</p><p style=\"padding:0.5em 0px;color:#222222;font-size:14px;background-color:#ffffff;line-height:24px;margin-top:0px;margin-bottom:0px;\"><strong style=\"font-weight:bolder;\">3、常用符号</strong></p><p style=\"padding:0.5em 0px;color:#222222;font-size:14px;background-color:#ffffff;line-height:24px;margin-top:0px;margin-bottom:0px;\">双引号&quot;(&amp;quot;) &amp;(&amp;amp;) &gt;(&amp;gt;) &lt;(&amp;lt;) 空格(&amp;nbsp;) &nbsp;版权 (&amp;copy;) 注册商标 (&amp;reg;) 乘号 (&amp;times;)除号 (&amp;divide;)</p><p style=\"padding:0.5em 0px;color:#222222;font-size:14px;background-color:#ffffff;line-height:24px;margin-top:0px;margin-bottom:0px;\"><strong style=\"font-weight:bolder;\">4、选择器</strong></p><p style=\"padding:0.5em 0px;color:#222222;font-size:14px;background-color:#ffffff;line-height:24px;margin-top:0px;margin-bottom:0px;\">类选择器：&lt;div class=&quot;a&quot;&gt;&lt;/div&gt; &nbsp; &nbsp; css中: &nbsp;.a{…;}</p><p style=\"padding:0.5em 0px;color:#222222;font-size:14px;background-color:#ffffff;line-height:24px;margin-top:0px;margin-bottom:0px;\">ID选择器：&lt;div id=&quot;a&quot;&gt;&lt;/div&gt; &nbsp; &nbsp; &nbsp; &nbsp;css中: &nbsp;#a{…;}</p><p style=\"padding:0.5em 0px;color:#222222;font-size:14px;background-color:#ffffff;line-height:24px;margin-top:0px;margin-bottom:0px;\">后代选择器：&lt;div class=&quot;a&quot;&gt;<br/> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&lt;div class=&quot;b&quot;&gt;&lt;/div&gt;<br/> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&lt;/div&gt; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;css中： .a空格.b{…;}</p><p style=\"padding:0.5em 0px;color:#222222;font-size:14px;background-color:#ffffff;line-height:24px;margin-top:0px;margin-bottom:0px;\">群组选择器：&lt;div class=&quot;a&quot;&gt;&lt;/div&gt;</p><p style=\"padding:0.5em 0px;color:#222222;font-size:14px;background-color:#ffffff;line-height:24px;margin-top:0px;margin-bottom:0px;\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&lt;div class=&quot;b&quot;&gt;&lt;/div&gt; &nbsp; css中： &nbsp;.a,.b{…;}</p><p style=\"padding:0.5em 0px;color:#222222;font-size:14px;background-color:#ffffff;line-height:24px;margin-top:0px;margin-bottom:0px;\">标签选择器：&lt;a&gt;sds&lt;/a&gt; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;css中： a{…;}</p><p style=\"padding:0.5em 0px;color:#222222;font-size:14px;background-color:#ffffff;line-height:24px;margin-top:0px;margin-bottom:0px;\"><strong style=\"font-weight:bolder;\">5、css样式写法及优先级</strong><br/>外部样式表<strong style=\"font-weight:bolder;\">:</strong><br/>&lt;head&gt;&lt;link rel=&quot;stylesheet&quot; type=&quot;text/css&quot; href=&quot;mystyle.css&quot; /&gt;&lt;/head&gt;<br/>内部样式表<strong style=\"font-weight:bolder;\">:</strong><br/>&lt;head&gt;&lt;style type=&quot;text/css&quot;&gt; hr {color: sienna;} &lt;/style&gt;&lt;/head&gt;<br/>内联样式<strong style=\"font-weight:bolder;\">:</strong><br/>&lt;p style=&quot;color: sienna; margin-left: 20px&quot;&gt; This is a paragraph&lt;/p&gt;</p><p style=\"padding:0.5em 0px;color:#222222;font-size:14px;background-color:#ffffff;line-height:24px;margin-top:0px;margin-bottom:0px;\">样式的继承:<br/>子标记会继承父标记的样式</p><p style=\"padding:0.5em 0px;color:#222222;font-size:14px;background-color:#ffffff;line-height:24px;margin-top:0px;margin-bottom:0px;\">样式的优先级:<br/>内联样式&gt;内部样式&gt;外部样式<br/>浏览器内部也有一个默认的样式</p><p><br/></p>', '2', '10000', '', '0', '1', '', '');
INSERT INTO `yzn_article_data` VALUES ('17', '<p style=\"text-indent:2em;\">TML 的注释格式是 ， IE 对HTML注释做了一些扩展，使之可以支持条件判断表达式，如何让静态HTML代码在不同IE版本显示不同内容？<br /></p><p style=\"text-indent:2em;\">和编程语言类似，这里的表达式还支持大于(gt)、小于(lt)、 与或非 等操作符，条件注释是 IE5.0 以上版本所特有的一种对注释的扩展，其它浏览器不支持。<br /></p><p style=\"text-indent:2em;\">[if IE] 判断是否IE<br /></p><p style=\"text-indent:2em;\">[if IE 7] 判断是否是IE7<br /></p><p style=\"text-indent:2em;\">[if !IE] 判断是否不是IE<br /></p><p style=\"text-indent:2em;\">[if lt IE 5.5] 判断是否是IE5.5 以下版本。 (&lt;)<br /></p><p style=\"text-indent:2em;\">[if lte IE 6] 判断是否等于IE6 版本或者以下 (&lt;=)<br /></p><p style=\"text-indent:2em;\">[if gt IE 5] 判断是否IE5以上版本 （&gt; ）<br /></p><p style=\"text-indent:2em;\">[if gte IE 7] 判断是否 IE7 版本或者以上<br /></p><p style=\"text-indent:2em;\">[if !(IE 7)] 判断是否不是IE7<br /></p><p style=\"text-indent:2em;\">[if (gt IE 5)&amp;(lt IE 7)] 判断是否大于IE5， 小于IE7<br /></p><p style=\"text-indent:2em;\">[if (IE 6)|(IE 7)] 判断是否IE6 或者 IE7<br /></p><p style=\"text-indent:2em;\">下面是判断IE版本的综合示例代码：<br /></p><pre><p style=\"text-indent:2em;\">&lt;!--[if IE]&gt;\r\nYou are using Internet Explorer.\r\n&lt;![endif]--&gt;\r\n\r\n\r\n&lt;!--[if !IE]--&gt;\r\nYou are not using Internet Explorer.\r\n&lt;!--[endif]--&gt;\r\n\r\n\r\n&lt;!--[if IE 7]&gt;\r\nWelcome to Internet Explorer 7!\r\n&lt;![endif]--&gt;\r\n\r\n\r\n&lt;!--[if !(IE 7)]&gt;\r\nYou are not using version 7.\r\n&lt;![endif]--&gt;\r\n\r\n\r\n&lt;!--[if gte IE 7]&gt;\r\nYou are using IE 7 or greater.\r\n&lt;![endif]--&gt;\r\n\r\n\r\n&lt;!--[if (IE 5)]&gt;\r\nYou are using IE 5 (any version).\r\n&lt;![endif]--&gt;\r\n\r\n\r\n&lt;!--[if (gte IE 5.5)&amp;(lt IE 7)]&gt;\r\nYou are using IE 5.5 or IE 6.\r\n&lt;![endif]--&gt;\r\n\r\n\r\n&lt;!--[if lt IE 5.5]&gt;\r\nPlease upgrade your version of Internet Explorer.\r\n&lt;![endif]--&gt;\r\n\r\n</p></pre><p style=\"text-indent:2em;\">对于&lt;!–[if expression]&gt; HTML &lt;![endif]–&gt;，非 IE 浏览器会当作注释内容，不显示；对于 &lt;!–[if expression]–&gt; HTML &lt;!–[endif]–&gt;，非 IE 浏览器浏览器会当作普通代码段显示。<br /></p><pre><p style=\"text-indent:2em;\">&lt;!--[if expression]--&gt; HTML &lt;!--[endif]--&gt; &lt;!--[if expression]&gt; HTML &lt;![endif]--&gt;</p></pre><p><br /></p>', '2', '10000', '', '0', '1', '', '');
INSERT INTO `yzn_article_data` VALUES ('18', '<p style=\"text-indent:0em;\">分享一些常用限制input的方法，可能里面也有一些你需要用的，可能也有一些你值得学习的！</p><p style=\"text-indent:0em;\">1.取消按钮按下时的虚线框,在input里添加属性值 hideFocus 或者 HideFocus=true <br style=\"font-family:宋体;background-color:#ffffff;color:#666666;font-size:14px;line-height:24px;\" />&lt;input type=&quot;submit&quot; value=&quot;提交&quot; hidefocus=&quot;true&quot; /&gt;<br style=\"font-family:宋体;background-color:#ffffff;color:#666666;font-size:14px;line-height:24px;\" /><br style=\"font-family:宋体;background-color:#ffffff;color:#666666;font-size:14px;line-height:24px;\" />2.只读文本框内容,在input里添加属性值 readonly <br style=\"font-family:宋体;background-color:#ffffff;color:#666666;font-size:14px;line-height:24px;\" />&lt;input type=&quot;text&quot; readonly /&gt; <br style=\"font-family:宋体;background-color:#ffffff;color:#666666;font-size:14px;line-height:24px;\" /><br style=\"font-family:宋体;background-color:#ffffff;color:#666666;font-size:14px;line-height:24px;\" />3.防止退后清空的TEXT文档(可把style内容做做为类引用) <br style=\"font-family:宋体;background-color:#ffffff;color:#666666;font-size:14px;line-height:24px;\" />&lt;input type=&quot;text&quot; style=&quot;behavior:url(#default#savehistory);&quot; /&gt; <br style=\"font-family:宋体;background-color:#ffffff;color:#666666;font-size:14px;line-height:24px;\" /><br style=\"font-family:宋体;background-color:#ffffff;color:#666666;font-size:14px;line-height:24px;\" />4.ENTER键可以让光标移到下一个输入框 <br style=\"font-family:宋体;background-color:#ffffff;color:#666666;font-size:14px;line-height:24px;\" />&lt;input type=&quot;text&quot; onkeydown=&quot;if(event.keyCode==13)event.keyCode=9&quot; /&gt;<br style=\"font-family:宋体;background-color:#ffffff;color:#666666;font-size:14px;line-height:24px;\" /><br style=\"font-family:宋体;background-color:#ffffff;color:#666666;font-size:14px;line-height:24px;\" />5.只能为中文(有闪动) <br style=\"font-family:宋体;background-color:#ffffff;color:#666666;font-size:14px;line-height:24px;\" />&lt;input type=&quot;text&quot; onkeyup=&quot;value=value.replace(/[ -~]/g,&#39;&#39;)&quot; onkeydown=&quot;if(event.keyCode==13)event.keyCode=9&quot; /&gt;<br style=\"font-family:宋体;background-color:#ffffff;color:#666666;font-size:14px;line-height:24px;\" /><br style=\"font-family:宋体;background-color:#ffffff;color:#666666;font-size:14px;line-height:24px;\" />6.只能为数字(有闪动) <br style=\"font-family:宋体;background-color:#ffffff;color:#666666;font-size:14px;line-height:24px;\" />&lt;input type=&quot;text&quot; onkeyup=&quot;value=value.replace(/[^\\d]/g,&#39;&#39;) &quot; onbeforepaste=&quot;clipboardData.setData(&#39;text&#39;,clipboardData.getData(&#39;text&#39;).replace(/[^\\d]/g,&#39;&#39;))&quot; /&gt;<br style=\"font-family:宋体;background-color:#ffffff;color:#666666;font-size:14px;line-height:24px;\" /><br style=\"font-family:宋体;background-color:#ffffff;color:#666666;font-size:14px;line-height:24px;\" />7.只能为数字(无闪动) <br style=\"font-family:宋体;background-color:#ffffff;color:#666666;font-size:14px;line-height:24px;\" />&lt;input type=&quot;text&quot; style=&quot;ime-mode:disabled&quot; onkeydown=&quot;if(event.keyCode==13)event.keyCode=9&quot; onkeypress=&quot;if ((event.keyCode&lt;48 || event.keyCode&gt;57)) event.returnValue=false&quot; /&gt;<br style=\"font-family:宋体;background-color:#ffffff;color:#666666;font-size:14px;line-height:24px;\" /><br style=\"font-family:宋体;background-color:#ffffff;color:#666666;font-size:14px;line-height:24px;\" />8.只能输入英文和数字(有闪动) <br style=\"font-family:宋体;background-color:#ffffff;color:#666666;font-size:14px;line-height:24px;\" />&lt;input type=&quot;text&quot; onkeyup=&quot;value=value.replace(/[\\W]/g,&#39;&#39;)&quot; onbeforepaste=&quot;clipboardData.setData(&#39;text&#39;,clipboardData.getData(&#39;text&#39;).replace(/[^\\d]/g,&#39;&#39;))&quot; /&gt;<br style=\"font-family:宋体;background-color:#ffffff;color:#666666;font-size:14px;line-height:24px;\" /><br style=\"font-family:宋体;background-color:#ffffff;color:#666666;font-size:14px;line-height:24px;\" />9.屏蔽输入法 <br style=\"font-family:宋体;background-color:#ffffff;color:#666666;font-size:14px;line-height:24px;\" />&lt;input type=&quot;text&quot; name=&quot;url&quot; style=&quot;ime-mode:disabled&quot; onkeydown=&quot;if(event.keyCode==13)event.keyCode=9&quot; /&gt; <br style=\"font-family:宋体;background-color:#ffffff;color:#666666;font-size:14px;line-height:24px;\" /><br style=\"font-family:宋体;background-color:#ffffff;color:#666666;font-size:14px;line-height:24px;\" />10. 只能输入 数字，小数点，减号（-） 字符(无闪动) <br style=\"font-family:宋体;background-color:#ffffff;color:#666666;font-size:14px;line-height:24px;\" />&lt;input onkeypress=&quot;if (event.keyCode!=46 &amp;&amp; event.keyCode!=45 &amp;&amp; (event.keyCode&lt;48 || event.keyCode&gt;57)) event.returnValue=false&quot; /&gt; <br style=\"font-family:宋体;background-color:#ffffff;color:#666666;font-size:14px;line-height:24px;\" /><br style=\"font-family:宋体;background-color:#ffffff;color:#666666;font-size:14px;line-height:24px;\" />11. 只能输入两位小数，三位小数(有闪动) <br style=\"font-family:宋体;background-color:#ffffff;color:#666666;font-size:14px;line-height:24px;\" />&lt;input type=&quot;text&quot; maxlength=&quot;9&quot; onkeyup=&quot;if(value.match(/^\\d{3}$/))value=value.replace(value,parseInt(value/10)) ;value=value.replace(/\\.\\d*\\./g,&#39;.&#39;)&quot; onkeypress=&quot;if((event.keyCode&lt;48 || event.keyCode&gt;57) &amp;&amp; event.keyCode!=46 &amp;&amp; event.keyCode!=45 || value.match(/^\\d{3}$/) || /\\.\\d{3}$/.test(value)) {event.returnValue=false}&quot; /&gt;</p>', '2', '10000', '', '0', '1', '', '');
INSERT INTO `yzn_article_data` VALUES ('19', '<p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">下面列了五个例子来详细说明，这几个例子的主要功能是：在5秒后，自动跳转到同目录下的hello.html（根据自己需要自行修改）文件。<br /><br /><strong>1) html的实现</strong></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">&lt;head&gt; <br />&lt;meta http-equiv=&quot;refresh&quot; content=&quot;10&quot;&gt;<br />&lt;meta http-equiv=&quot;refresh&quot; content=&quot;5;url=&quot;http://www.97zzw.com&quot;&gt; &nbsp;<br />&lt;/head&gt;<br /><br />优点：简单<br /><br />缺点：Struts Tiles中无法使用</p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\"><strong>2) javascript的实现</strong></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">&lt;script language=&quot;javascript&quot; type=&quot;text/javascript&quot;&gt; &nbsp;<br />window.location.href=&#39;hello.html&#39;; &nbsp;<br />setTimeout(&quot;javascript:location.href=&#39;http://www.97zzw.com&#39;&quot;, 5000); &nbsp;<br />&lt;/script&gt;<br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">优点：灵活，可以结合更多的其他功能<br /><br />缺点：受到不同浏览器的影响</p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\"><br /><strong>3) 结合了倒数的javascript实现（IE）</strong><br /><br />&lt;span id=&quot;totalSecond&quot;&gt;5&lt;/span&gt; <br />&lt;script language=&quot;javascript&quot; type=&quot;text/javascript&quot;&gt; <br />var second = totalSecond.innerText; &nbsp;<br />setInterval(&quot;redirect()&quot;, 1000); &nbsp;<br />function redirect(){ &nbsp;<br />totalSecond.innerText=--second; &nbsp;<br />if(second&lt;0) location.href=&#39;http://www.97zzw.com&#39;;<br />} &nbsp;<br />&lt;/script&gt;<br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">优点：更人性化<br /><br />缺点：firefox不支持（firefox不支持span、div等的innerText属性）<br /><br /><strong>3&#39;) 结合了倒数的javascript实现（firefox）</strong><br /><br />&lt;script language=&quot;javascript&quot; type=&quot;text/javascript&quot;&gt;<br />var second = document.getElementById(&#39;totalSecond&#39;).textContent;<br />setInterval(&quot;redirect()&quot;, 1000);<br />function redirect() &nbsp; { &nbsp;<br />document.getElementById(&#39;totalSecond&#39;).textContent = --second; &nbsp;<br />if (second &lt; 0) location.href = &#39;http://www.97zzw.com&#39;; &nbsp;} <br />&lt;/script&gt;<br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\"><strong>4) 解决Firefox不支持innerText的问题</strong></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">&lt;span id=&quot;totalSecond&quot;&gt;5&lt;/span&gt; <br />&lt;script language=&quot;javascript&quot; type=&quot;text/javascript&quot;&gt; &nbsp;<br /> if(navigator.appName.indexOf(&quot;Explorer&quot;) &gt; -1){ &nbsp;<br />document.getElementById(&#39;totalSecond&#39;).innerText = &quot;my text innerText&quot;; <br /> } else{ &nbsp;<br /> document.getElementById(&#39;totalSecond&#39;).textContent = &quot;my text textContent&quot;; &nbsp;<br /> } &nbsp;<br /> &lt;/script&gt;<br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\"><strong>5) 整合3)和3&#39;)</strong></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">&lt;span id=&quot;totalSecond&quot;&gt;5&lt;/span&gt; &nbsp; <br />&lt;script language=&quot;javascript&quot; type=&quot;text/javascript&quot;&gt;<br />var second = document.getElementById(&#39;totalSecond&#39;).textContent; <br />if (navigator.appName.indexOf(&quot;Explorer&quot;) &gt; -1) &nbsp;{ &nbsp;<br /> second = document.getElementById(&#39;totalSecond&#39;).innerText;<br />} else { &nbsp;<br /> &nbsp; &nbsp;second = document.getElementById(&#39;totalSecond&#39;).textContent; &nbsp;<br /> } &nbsp; &nbsp; <br />setInterval(&quot;redirect()&quot;, 1000); &nbsp;<br />function redirect() { <br />if (second &lt; 0) { &nbsp;<br /> &nbsp; &nbsp;location.href = &#39;http://www.97zzw.com&#39;; &nbsp;<br /> } else { &nbsp;<br /> &nbsp; &nbsp; if (navigator.appName.indexOf(&quot;Explorer&quot;) &gt; -1) { &nbsp;<br /> &nbsp; &nbsp; &nbsp; &nbsp;document.getElementById(&#39;totalSecond&#39;).innerText = second--; &nbsp;<br /> &nbsp; &nbsp; } else { &nbsp;<br /> &nbsp; &nbsp; &nbsp; &nbsp; document.getElementById(&#39;totalSecond&#39;).textContent = second--; &nbsp;<br /> &nbsp; &nbsp; } &nbsp;<br /> &nbsp; } &nbsp;<br />} &nbsp;</p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">&lt;/script&gt;</p>', '2', '10000', '', '0', '1', '', '');
INSERT INTO `yzn_article_data` VALUES ('20', '<div>代码如下:<br /></div><div id=\"phpcode3\"><!--[if IE]><script type=\"text/javascript\" src=\"97zzw.js\"></script><![endif]--></div><p><br style=\"font-family:tahoma, geneva, sans-serif;font-size:14px;background-color:#ffffff;line-height:22px;\" />用上面这段代码，只有使用IE时加载97zzw.js。这点很重要，因为你什么都加载的话会浪费带宽，打开页面速度就会慢！如果专对IE6就改成if IE6。 <br style=\"font-family:tahoma, geneva, sans-serif;font-size:14px;background-color:#ffffff;line-height:22px;\" /><br style=\"font-family:tahoma, geneva, sans-serif;font-size:14px;background-color:#ffffff;line-height:22px;\" /></p><div>代码如下:</div><div id=\"phpcode4\"><br /><!--[if IE 6]><br /><script type=\"text/javascript\" src=\"resources/scripts/DD_belatedPNG_0.0.7a.js\"></script><br /><script type=\"text/javascript\"><br />DD_belatedPNG.fix(\'.png_bg, img, li\'); <br /></script><br /><![endif]--><br /></div><p><br style=\"font-family:tahoma, geneva, sans-serif;font-size:14px;background-color:#ffffff;line-height:22px;\" />或者解决ie不同版本布局问题，下面仅仅在ie7中加载97zzw.css <br style=\"font-family:tahoma, geneva, sans-serif;font-size:14px;background-color:#ffffff;line-height:22px;\" /><br style=\"font-family:tahoma, geneva, sans-serif;font-size:14px;background-color:#ffffff;line-height:22px;\" /></p><div>代码如下:</div><div id=\"phpcode5\"><br /><!--[if lte IE 7]><br /><link rel=\"stylesheet\" href=\"97zzw.css\" type=\"text/css\" media=\"screen\" /><br /><![endif]--></div><p><br /></p>', '2', '10000', '', '0', '1', '24', '');
INSERT INTO `yzn_article_data` VALUES ('25', '<p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">nofollow 是一个HTML标签的属性值。这个标签的意义是告诉<span style=\"color:#0066cc;border-bottom-width:1px;border-bottom-style:dotted;border-bottom-color:#0099ff;text-decoration:none;text-decoration:underline;\">搜索引擎</span>&quot;不要追踪此网页上的链接&quot;或&quot;不要追踪此特定链接。<br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\"><strong>　　nofollow简介</strong><br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">　　nofollow是一个HTML标签的属性值。它的出现为网站管理员提供了一种方式，即告诉搜索引擎&quot;不要追踪此网页上的链接&quot;或&quot;不要追踪此特定链接&quot;。<br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">　　这个标签的意义是告诉搜索引擎这个链接不是经过作者信任的，所以这个链接不是一个信任票。<br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">　　简单的说就是，如果A网页上有一个链接指向B网页，但A网页给这个链接加上了 rel=&quot;nofollow&quot; 标注，则搜索引擎不把A网页计算入B网页的反向链接。<br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">　　搜索引擎看到这个标签就可能减少或完全取消链接的权重传递。<br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\"><strong>　　nofollow 标签的发展</strong><br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">　　最初，&quot;Nofollow&quot;属性出现在网页级元标记中，用于告诉搜索引擎不要追踪(即抓取)网页上的任何出站链接。<br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">　　但是，在将&quot;Nofollow&quot;用于各个链接前，我们需要完成大量的工作(例如，将链接重定向至 robots.txt中拦截的网址)，以阻止蜘蛛追踪某网页上的各个链接。<br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">　　因此，我们创建了rel属性的&quot;Nofollow&quot;属性值。<br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">　　这可让网站管理员更精确地进行控制：无需告诉搜索引擎和漫游器不要追踪该网页的所有链接，只需轻松地告诉蜘蛛不要抓取某特定链接即可。</p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\"><strong>　　nofollow的原因与作用</strong><br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">　　我们都知道，反向链接即外链是搜索引擎给网站排名的一个重要因素。为了添加反向链接，SEO作弊者会在论坛和博客等大量发布带无关链接的内容。<br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">　　这些垃圾链接的存在给搜索引擎对网页质量的评估造成一定程度的麻烦，可以说nofollow是一个非常好的“垃圾链接防火墙”。<br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">　　正是百度、谷歌、Yahoo、MSN 为了应对垃圾链接(Spam)引入的一个属性，此属性目前应该被广泛采用。<br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">　　Nofollow标签的作用有两方面，简单的说，一是不给链接导入权重，降低此链接的权重，二是使添加nofollow的部分内容不参与网站排名，便于集中网站权重。<br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\"><strong>　　nofollow标签使用方法</strong><br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">　　nofollow标签通常有两种使用方法：<br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">　　1、将&quot;nofollow&quot;写在网页上的meta标签上，用来告诉搜索引擎不要抓取网页上的所有外部和包括内部链接。<br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">　　2、将&quot;nofollow&quot;放在超链接中，告诉搜索引擎不要抓取特定的链接。<br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">　　nofollow还有另外的一些写法：<br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">　　Meta robots标签必须放在&lt;head&gt;&lt;/head&gt;和之间，格式：<span class=\"webkit-html-tag\" style=\"font-family:monospace;font-size:medium;\">&lt;meta <span class=\"webkit-html-attribute-name\">name</span>=&quot;<span class=\"webkit-html-attribute-value\">robots</span>&quot; <span class=\"webkit-html-attribute-name\">content</span>=&quot;<span class=\"webkit-html-attribute-value\">index,nofollow</span>&quot; /&gt;</span><span style=\"font-family:monospace;color:#000000;font-size:medium;\"></span><br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">　　content中的值决定允许抓取的类型，必须同时包含两个值：<br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">　　是否允许索引(index)和是否跟踪链接(follow，也可以理解为是否允许沿着网页中的超级链接继续抓取)。<br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">　　共有4个参数可选，组成4个组合：<br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">　　1. index,follow：允许抓取本页，允许跟踪链接。<br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">　　2. index,nofollow：允许抓取本页，但禁止跟踪链接。<br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">　　3. noindex,follow：禁止抓取本页，但允许跟踪链接。<br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">　　4. noindex,nofllow：禁止抓取本页，同时禁止跟踪本页中的链接。<br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\"><strong>　　nofollow使用示例：</strong><br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">　　如某博客上有垃圾评论：<br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">　　以下操作，即进行了 nofollow：<br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\"><strong>　　nofollow标签检查</strong><br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">　　打开站长工具网站，在“SEO信息查询”中选择“友情链接检测”。赶快去查询下你的友情连接是否可靠吧!是否被人偷偷的添加了nofollow标签。<br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\"><strong>　　nofollow的拓展应用</strong><br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">　　你可以为站点的内部链接添加 nofollow。比如：联系我们、关于我们、隐私保护、公司简介、网站后台等的链接可以把它们nofollow 掉。<br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">　　这样做，首页的PR值就不会传输给他们。因为，他们有太多的PR值显然没用;<br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">　　另外网站上的一些广告，因为这些广告只是让其带来IP和流量，而不是让其加重搜索引擎的权重，所以也可以加上这个nofollow标签，这样不会影响其本来意愿。<br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\"><strong>　　什么是external nofollow</strong><br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">　　external nofollow是比nofollow更专业的写法，即明确指出链接为外部链接，爬虫可以略过。<br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\"><strong>　　搜索引擎的支持</strong><br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">　　以前谷歌、和必应Bing支持，现在百度通过百度站长俱乐部向外宣布百度也支持nofollow标签。youdao，soso也支持该标签，不支持的有Yahoo和sogou!<br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\"><strong>　　友链中的nofollow</strong><br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">　　看了以上关于nofollow标签的说明，相信各位网站建设者对于nofollow标签都已经有了一定的了解.<br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">　　这里要说的是，站长在交换友情链接的时候应当留意下与你交换链接的站点有没有在其所添加的友情链接上写上nofollow标签，如果在友链中写上了nofollow标签，它会阻止蜘蛛进行跟踪，同时也阻止了权重的传递。<br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">　　查询的方法也很简单，我们只要直接打开对方站点页面的源文件，查看其中nofollow的事情情况。<br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">　　当然也并不是一定不换，如果是一些流量大的站点可以换，虽然有该标签，但是这也可以为我们的站点带来一定的流量。<br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">　　就如同我们知道百度经验的外链有nofollow标签，但是百度经验的外链可以很好的为我们带来流量，我们也是可以适当的建设的。<br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">　　google支持的深度<br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">　　1、不可信赖的内容</p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">　　2、付费链接</p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">　　3、按优先级别进行抓取<br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">　　搜索引擎漫游器无法作为成员登录或注册您的网站，因此没有理由邀请 Googlebot 追踪“在此注册”或“登录”链接。对这些链接使用 nofollow，可让 Googlebot 抓取您希望编入 Google 索引的其他网页。<br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">　　不过，与主要依靠包含 nofollow 的链接区分抓取优先级相比，稳固的信息架构(直观的导航界面、用户友好和搜索引擎友好的网址等等)可能是更高效的资源。<br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">　　目前google对nofollow支持的深度已经相当完美，最近，百度对nofollow的优先级作用已经得到了证实。百度站长俱乐部里网友也就这一问题向百度提出疑问，经过官方的回答，也证实了这一点。<br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\"><strong>　　nofollow与external nofollow</strong><br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">　　external nofollow其实这个属性就是告诉搜索引擎不要跟踪这个链接，也就是这个链接很可能被视为一个垃圾链接，这也主要是应对 Spam而增设的一种属性。<br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">　　rel=“nofollow”属性是谷歌为了应对垃圾链接而引入的一个属性值，被各大搜索引擎引用!rel=“external nofollow”只是更相对于rel=“nofollow”参数更加规范一些而已!<br /></p><p style=\"padding:0px;color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;margin-top:0px;margin-bottom:0px;\">　　rel=“external nofollow”与rel=“nofollow”其功能就中文译文”不要读取” 及”外部链接不要读取”的意思!</p><p><br /></p>', '2', '10000', '', '0', '1', '', '');
INSERT INTO `yzn_article_data` VALUES ('29', '<p style=\"padding:0px;font-size:14px;line-height:25px;background-color:#ffffff;color:#222222;margin-top:0px;margin-bottom:0px;\">下面列了五个例子来详细说明，这几个例子的主要功能是：在5秒后，自动跳转到同目录下的hello.html（根据自己需要自行修改）文件。<br /><br /><strong>1) html的实现</strong></p><p style=\"padding:0px;font-size:14px;line-height:25px;background-color:#ffffff;color:#222222;margin-top:0px;margin-bottom:0px;\">&lt;head&gt; <br />&lt;meta http-equiv=&quot;refresh&quot; content=&quot;10&quot;&gt;<br />&lt;meta http-equiv=&quot;refresh&quot; content=&quot;5;url=&quot;http://www.<span style=\"color:#222222;font-size:14px;line-height:25px;background-color:#ffffff;\">abc3210</span>.com&quot;&gt; &nbsp;<br />&lt;/head&gt;<br /><br />优点：简单<br /><br />缺点：Struts Tiles中无法使用</p><p style=\"padding:0px;font-size:14px;line-height:25px;background-color:#ffffff;color:#222222;margin-top:0px;margin-bottom:0px;\"><br /></p><p style=\"padding:0px;font-size:14px;line-height:25px;background-color:#ffffff;color:#222222;margin-top:0px;margin-bottom:0px;\"><strong>2) javascript的实现</strong></p><p style=\"padding:0px;font-size:14px;line-height:25px;background-color:#ffffff;color:#222222;margin-top:0px;margin-bottom:0px;\">&lt;script language=&quot;javascript&quot; type=&quot;text/javascript&quot;&gt; &nbsp;<br />window.location.href=&#39;hello.html&#39;; &nbsp;<br />setTimeout(&quot;javascript:location.href=&#39;http://www.abc3210.com&#39;&quot;, 5000); &nbsp;<br />&lt;/script&gt;<br /></p><p style=\"padding:0px;font-size:14px;line-height:25px;background-color:#ffffff;color:#222222;margin-top:0px;margin-bottom:0px;\">优点：灵活，可以结合更多的其他功能<br /><br />缺点：受到不同浏览器的影响</p><p style=\"padding:0px;font-size:14px;line-height:25px;background-color:#ffffff;color:#222222;margin-top:0px;margin-bottom:0px;\"><br /><strong>3) 结合了倒数的javascript实现（IE）</strong><br /><br />&lt;span id=&quot;totalSecond&quot;&gt;5&lt;/span&gt; <br />&lt;script language=&quot;javascript&quot; type=&quot;text/javascript&quot;&gt; <br />var second = totalSecond.innerText; &nbsp;<br />setInterval(&quot;redirect()&quot;, 1000); &nbsp;<br />function redirect(){ &nbsp;<br />totalSecond.innerText=--second; &nbsp;<br />if(second&lt;0) location.href=&#39;http://www.abc3210.com&#39;;<br />} &nbsp;<br />&lt;/script&gt;<br /></p><p style=\"padding:0px;font-size:14px;line-height:25px;background-color:#ffffff;color:#222222;margin-top:0px;margin-bottom:0px;\">优点：更人性化<br /><br />缺点：firefox不支持（firefox不支持span、div等的innerText属性）<br /><br /><strong>3&#39;) 结合了倒数的javascript实现（firefox）</strong><br /><br />&lt;script language=&quot;javascript&quot; type=&quot;text/javascript&quot;&gt;<br />var second = document.getElementById(&#39;totalSecond&#39;).textContent;<br />setInterval(&quot;redirect()&quot;, 1000);<br />function redirect() &nbsp; { &nbsp;<br />document.getElementById(&#39;totalSecond&#39;).textContent = --second; &nbsp;<br />if (second &lt; 0) location.href = &#39;http://www.abc3210.com&#39;; &nbsp;} <br />&lt;/script&gt;<br /></p><p style=\"padding:0px;font-size:14px;line-height:25px;background-color:#ffffff;color:#222222;margin-top:0px;margin-bottom:0px;\"><strong>4) 解决Firefox不支持innerText的问题</strong></p><p style=\"padding:0px;font-size:14px;line-height:25px;background-color:#ffffff;color:#222222;margin-top:0px;margin-bottom:0px;\">&lt;span id=&quot;totalSecond&quot;&gt;5&lt;/span&gt; <br />&lt;script language=&quot;javascript&quot; type=&quot;text/javascript&quot;&gt; &nbsp;<br />if(navigator.appName.indexOf(&quot;Explorer&quot;) &gt; -1){ &nbsp;<br />document.getElementById(&#39;totalSecond&#39;).innerText = &quot;my text innerText&quot;; <br />} else{ &nbsp;<br />document.getElementById(&#39;totalSecond&#39;).textContent = &quot;my text textContent&quot;; &nbsp;<br />} &nbsp;<br />&lt;/script&gt;<br /></p><p style=\"padding:0px;font-size:14px;line-height:25px;background-color:#ffffff;color:#222222;margin-top:0px;margin-bottom:0px;\"><strong>5) 整合3)和3&#39;)</strong></p><p style=\"padding:0px;font-size:14px;line-height:25px;background-color:#ffffff;color:#222222;margin-top:0px;margin-bottom:0px;\">&lt;span id=&quot;totalSecond&quot;&gt;5&lt;/span&gt; &nbsp; <br />&lt;script language=&quot;javascript&quot; type=&quot;text/javascript&quot;&gt;<br />var second = document.getElementById(&#39;totalSecond&#39;).textContent; <br />if (navigator.appName.indexOf(&quot;Explorer&quot;) &gt; -1) &nbsp;{ &nbsp;<br />second = document.getElementById(&#39;totalSecond&#39;).innerText;<br />} else { &nbsp;<br /> &nbsp; second = document.getElementById(&#39;totalSecond&#39;).textContent; &nbsp;<br />} &nbsp; &nbsp; <br />setInterval(&quot;redirect()&quot;, 1000); &nbsp;<br />function redirect() { <br />if (second &lt; 0) { &nbsp;<br /> &nbsp; location.href = &#39;http://www.abc3210.com&#39;; &nbsp;<br />} else { &nbsp;<br /> &nbsp; &nbsp;if (navigator.appName.indexOf(&quot;Explorer&quot;) &gt; -1) { &nbsp;<br /> &nbsp; &nbsp; &nbsp; document.getElementById(&#39;totalSecond&#39;).innerText = second--; &nbsp;<br /> &nbsp; &nbsp;} else { &nbsp;<br /> &nbsp; &nbsp; &nbsp; &nbsp;document.getElementById(&#39;totalSecond&#39;).textContent = second--; &nbsp;<br /> &nbsp; &nbsp;} &nbsp;<br /> &nbsp;} &nbsp;<br />} &nbsp;</p><p style=\"padding:0px;font-size:14px;line-height:25px;background-color:#ffffff;color:#222222;margin-top:0px;margin-bottom:0px;\">&lt;/script&gt;</p>', '2', '10000', '', '0', '1', '', '');

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
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 COMMENT='规则表';

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
INSERT INTO `yzn_auth_rule` VALUES ('19', 'Admin', '2', 'Admin/index/index', '首页', '-1', '');
INSERT INTO `yzn_auth_rule` VALUES ('20', 'Admin', '1', 'Admin/Action/get_xml', '浏览操作日志', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('21', 'Admin', '1', 'Admin/Action/remove', '删除操作日志', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('22', 'Admin', '1', 'Admin/AuthManager/writeGroup', '编辑/创建权限组', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('23', 'Admin', '1', 'Admin/AuthManager/changeStatus', '权限组状态修改', '-1', '');
INSERT INTO `yzn_auth_rule` VALUES ('24', 'Admin', '1', 'Admin/AuthManager/access', '访问授权', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('25', 'Admin', '1', 'Admin/Menu/index', '后台菜单', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('26', 'Admin', '1', 'Admin/AuthManager/deleteGroup', '删除权限组', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('27', 'Content', '1', 'Content/Content/index', '管理内容', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('28', 'Content', '1', 'Content/Category/index', '栏目列表', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('29', 'Content', '2', 'Content/index/index', '内容', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('30', 'Content', '1', 'Content/Models/index', '模型管理', '1', '');

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
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='缓存列队表';

-- ----------------------------
-- Records of yzn_cache
-- ----------------------------
INSERT INTO `yzn_cache` VALUES ('1', 'Model', '模型列表', 'Content', 'Models', 'model_cache', '0');
INSERT INTO `yzn_cache` VALUES ('2', 'Category', '栏目索引', 'Content', 'Category', 'category_cache', '0');
INSERT INTO `yzn_cache` VALUES ('3', 'ModelField', '模型字段', 'Content', 'ModelField', 'model_field_cache', '0');
INSERT INTO `yzn_cache` VALUES ('4', 'Config', '网站配置', '', 'Configs', 'config_cache', '1');
INSERT INTO `yzn_cache` VALUES ('5', 'Module', '可用模块列表', '', 'Module', 'module_cache', '1');

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
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COMMENT='栏目表';

-- ----------------------------
-- Records of yzn_category
-- ----------------------------
INSERT INTO `yzn_category` VALUES ('1', 'content', '0', '1', '', '0', '0', '1', '1,10,11', '网页教程', '', '网页教程-网页设计基础教程DIV+CSS', '', 'jiaocheng', '/home/index/lists/catid/1', '0', 'a:6:{s:17:\"category_template\";s:13:\"category.html\";s:13:\"list_template\";s:9:\"list.html\";s:13:\"show_template\";s:9:\"show.html\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";}', '1', '1', '0', 'wangyejiaocheng');
INSERT INTO `yzn_category` VALUES ('4', 'content', '0', '1', '', '0', '0', '1', '4,16,17', '网页特效', '', '提供各种网页效果,让你的网页更炫丽', '', 'texiao', '/home/index/lists/catid/4', '0', 'a:6:{s:17:\"category_template\";s:13:\"category.html\";s:13:\"list_template\";s:9:\"list.html\";s:13:\"show_template\";s:9:\"show.html\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";}', '4', '1', '0', 'wangyetexiao');
INSERT INTO `yzn_category` VALUES ('10', 'content', '0', '1', '', '1', '0,1', '0', '10,30', 'HTML/XHTML', '', '', 'jiaocheng/', 'html', '/home/index/lists/catid/10', '0', 'a:14:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"list_template\";s:8:\"list.php\";s:13:\"show_template\";s:8:\"show.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:14:\"content_ishtml\";s:1:\"0\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";s:1:\"4\";}', '10', '1', '0', 'html/xhtml');
INSERT INTO `yzn_category` VALUES ('11', 'content', '0', '1', '', '1', '0,1', '0', '11', 'Dreamweaver', '', '', 'jiaocheng/', 'dw', '/home/index/lists/catid/11', '0', 'a:14:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"list_template\";s:8:\"list.php\";s:13:\"show_template\";s:8:\"show.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:14:\"content_ishtml\";s:1:\"0\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";s:1:\"4\";}', '11', '1', '0', 'dreamweaver');
INSERT INTO `yzn_category` VALUES ('16', 'content', '0', '1', '', '4', '0,4', '0', '16', 'JS幻灯片', '', '', 'texiao/', 'adjs', '/home/index/lists/catid/16', '0', 'a:14:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"list_template\";s:8:\"list.php\";s:13:\"show_template\";s:8:\"show.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:14:\"content_ishtml\";s:1:\"0\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";s:1:\"4\";}', '16', '1', '0', 'jshuandengpian');
INSERT INTO `yzn_category` VALUES ('17', 'content', '0', '1', '', '4', '0,4', '0', '17', '导航菜单', '', '', 'texiao/', 'nav', '/home/index/lists/catid/17', '0', 'a:14:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"list_template\";s:8:\"list.php\";s:13:\"show_template\";s:8:\"show.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:14:\"content_ishtml\";s:1:\"0\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";s:1:\"4\";}', '17', '1', '0', 'daohangcaidan');
INSERT INTO `yzn_category` VALUES ('18', 'content', '1', '0', '', '0', '0', '0', '18,19,20,21', '底部链接', '', '', '', 'bottom_nav', '/home/index/lists/catid/18', '0', 'a:4:{s:13:\"page_template\";s:9:\"page.html\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";}', '18', '0', '0', 'dibulianjie');
INSERT INTO `yzn_category` VALUES ('19', 'content', '1', '0', '', '18', '0,18', '0', '19', '关于我们', '', '', 'bottom_nav/', 'about', '/home/index/lists/catid/19', '0', 'a:4:{s:13:\"page_template\";s:9:\"page.html\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";}', '19', '1', '0', 'guanyuwomen');
INSERT INTO `yzn_category` VALUES ('20', 'content', '1', '0', '', '18', '0,18', '0', '20', '加入我们', '', '', 'bottom_nav/', 'join\n', '/home/index/lists/catid/20', '0', 'a:4:{s:13:\"page_template\";s:9:\"page.html\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";}', '20', '1', '0', 'jiaruwomen');
INSERT INTO `yzn_category` VALUES ('21', 'content', '1', '0', '', '18', '0,18', '0', '21', '联系我们', '', '', 'bottom_nav/', 'contact', '/home/index/lists/catid/21', '0', 'a:4:{s:13:\"page_template\";s:9:\"page.html\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";}', '21', '1', '0', 'lianxiwomen');

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
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='网站配置表';

-- ----------------------------
-- Records of yzn_config
-- ----------------------------
INSERT INTO `yzn_config` VALUES ('1', 'site_title', '网站标题', '1', 'Yzncms内容管理框架 - Powered by Yzncms', '1');
INSERT INTO `yzn_config` VALUES ('2', 'site_keyword', '网站关键字', '1', 'ThinkPHP,tp5.0,yzncms,内容管理系统', '2');
INSERT INTO `yzn_config` VALUES ('3', 'site_description', '网站描述', '1', 'Yzncms内容管理框架,一套简单，易用，面向开发者的内容管理框,采用TP5.0框架开发', '3');
INSERT INTO `yzn_config` VALUES ('4', 'site_name', '网站名称', '1', 'Yzncms内容管理框架', '0');
INSERT INTO `yzn_config` VALUES ('5', 'icp', 'icp', '2', '苏ICP备15017030', '0');
INSERT INTO `yzn_config` VALUES ('6', 'close', '关闭站点', '2', '0', '0');
INSERT INTO `yzn_config` VALUES ('7', 'mail_server', '邮件服务器', '1', 'smtp.163.com', '0');
INSERT INTO `yzn_config` VALUES ('8', 'mail_port', '邮件发送端口', '1', '25', '0');
INSERT INTO `yzn_config` VALUES ('9', 'mail_from', '发件人地址', '1', 'o0mcw_ken0o@163.com', '0');
INSERT INTO `yzn_config` VALUES ('10', 'mail_user', '邮箱用户名', '1', 'o0mcw_ken0o@163.com', '0');
INSERT INTO `yzn_config` VALUES ('11', 'mail_password', '邮箱密码', '1', '', '0');

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
-- Table structure for `yzn_links`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_links`;
CREATE TABLE `yzn_links` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '链接id',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '链接名称',
  `image` varchar(255) NOT NULL DEFAULT '' COMMENT '链接图片',
  `target` varchar(25) NOT NULL DEFAULT '' COMMENT '链接打开方式',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '链接描述',
  `visible` tinyint(1) NOT NULL COMMENT '链接是否可见',
  `rating` int(11) NOT NULL DEFAULT '0' COMMENT '链接等级',
  `updated` int(11) NOT NULL COMMENT '链接最后更新时间',
  `rss` varchar(255) NOT NULL DEFAULT '' COMMENT '链接RSS地址',
  `termsid` int(4) NOT NULL COMMENT '分类id',
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`),
  KEY `visible` (`visible`),
  KEY `termsid` (`termsid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='友情链接';

-- ----------------------------
-- Records of yzn_links
-- ----------------------------
INSERT INTO `yzn_links` VALUES ('1', 'https://git.oschina.net/ken678/YZNCMS', '御宅男工作室', '', '', '', '0', '0', '0', '', '0', '0');
INSERT INTO `yzn_links` VALUES ('2', 'https://hao.360.cn/?s0001', '360导航', '', '', '', '0', '0', '0', '', '0', '0');

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
) ENGINE=MyISAM AUTO_INCREMENT=53 DEFAULT CHARSET=utf8 COMMENT='后台菜单表';

-- ----------------------------
-- Records of yzn_menu
-- ----------------------------
INSERT INTO `yzn_menu` VALUES ('1', '设置', '', '0', 'Admin', 'Setting', 'index', '', '1', '', '0', '1');
INSERT INTO `yzn_menu` VALUES ('2', '内容', '', '0', 'Content', 'index', 'index', '', '1', '', '0', '2');
INSERT INTO `yzn_menu` VALUES ('5', '站点配置', '', '10', 'Admin', 'Config', 'index', '', '1', '', '0', '1');
INSERT INTO `yzn_menu` VALUES ('6', '管理员', 'icon iconfont icon-guanliyuan', '1', 'Admin', 'Manager', 'index', '', '1', '', '0', '1');
INSERT INTO `yzn_menu` VALUES ('9', '扩展配置', '', '5', 'Admin', 'Config', 'extend', '', '1', '', '0', '5');
INSERT INTO `yzn_menu` VALUES ('10', '设置', 'icon iconfont icon-zidongxiufu', '1', 'Admin', 'Setting', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('12', '管理员管理', '', '6', 'Admin', 'Manager', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('13', '添加管理员', '', '12', 'Admin', 'Manager', 'add', '', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('14', '编辑管理员', '', '12', 'Admin', 'Manager', 'edit', '', '0', '', '0', '1');
INSERT INTO `yzn_menu` VALUES ('15', '操作日志', '', '10', 'Admin', 'Action', 'actionlog', '', '1', '', '0', '10');
INSERT INTO `yzn_menu` VALUES ('16', '应用', 'icon iconfont icon-yingyong', '1', 'Admin', 'database', 'index', '', '1', '', '0', '3');
INSERT INTO `yzn_menu` VALUES ('17', '数据库备份', '', '16', 'Admin', 'database', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('18', '数据库恢复', '', '17', 'Admin', 'database', 'repair_list', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('19', '权限设置', '', '10', 'Admin', 'AuthManager', 'index', '', '1', '', '0', '3');
INSERT INTO `yzn_menu` VALUES ('20', '优化表', '', '17', 'Admin', 'database', 'optimize', '', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('21', '修复表', '', '17', 'Admin', 'database', 'repair', '', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('22', '下载表', '', '17', 'Admin', 'database', 'downfile', '', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('23', '删除表', '', '17', 'Admin', 'database', 'del', '', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('24', '还原表', '', '17', 'Admin', 'database', 'import', '', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('25', '删除管理员', '', '12', 'Admin', 'Manager', 'del', '', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('27', '浏览操作日志', '', '15', 'Admin', 'Action', 'get_xml', '', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('28', '删除操作日志', '', '15', 'Admin', 'Action', 'remove', '', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('29', '查看权限组', '', '19', 'Admin', 'AuthManager', 'index', '', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('31', '删除权限组', '', '19', 'Admin', 'AuthManager', 'deleteGroup', '', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('30', '编辑/创建权限组', '', '19', 'Admin', 'AuthManager', 'writeGroup', '', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('32', '访问授权', '', '19', 'Admin', 'AuthManager', 'access', '', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('33', '后台菜单', '', '10', 'Admin', 'Menu', 'index', '', '1', '', '0', '10');
INSERT INTO `yzn_menu` VALUES ('34', '内容管理', 'icon iconfont icon-neirongguanli', '2', 'Content', 'Content', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('35', '相关设置', 'icon iconfont icon-zidongxiufu', '2', 'Content', 'Category', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('36', '栏目列表', '', '35', 'Content', 'Category', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('37', '模型管理', '', '35', 'Content', 'Models', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('38', '管理内容', '', '34', 'Content', 'Content', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('39', '邮箱配置', '', '5', 'Admin', 'Config', 'mail', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('40', '模块', '', '0', 'Admin', 'Module', 'index', '', '1', '', '0', '3');
INSERT INTO `yzn_menu` VALUES ('41', '本地模块', 'icon iconfont icon-yingyong', '40', 'Admin', 'Module', 'local', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('42', '模块管理', '', '41', 'Admin', 'Module', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('43', '模块列表', 'icon iconfont icon-liebiao', '40', 'Admin', 'Module', 'list', '', '1', '', '0', '1');
INSERT INTO `yzn_menu` VALUES ('44', '友情链接', '', '43', 'Links', 'Links', 'index', '', '1', '友情链接！', '0', '0');
INSERT INTO `yzn_menu` VALUES ('45', '添加友情链接', '', '44', 'Links', 'Links', 'add', '', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('46', '链接编辑', '', '44', 'Links', 'Links', 'edit', '', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('47', '链接删除', '', '44', 'Links', 'Links', 'delete', '', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('48', '链接排序', '', '44', 'Links', 'Links', 'listorder', '', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('49', '分类管理', '', '44', 'Links', 'Links', 'terms', '', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('50', '分类新增', '', '44', 'Links', 'Links', 'addTerms', '', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('51', '分类修改', '', '44', 'Links', 'Links', 'termsedit', '', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('52', '分类删除', '', '44', 'Links', 'Links', 'termsdelete', '', '0', '', '0', '0');

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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='内容模型列表';

-- ----------------------------
-- Records of yzn_model
-- ----------------------------
INSERT INTO `yzn_model` VALUES ('1', '文章模型', '文章模型', 'article', '', '1403150253', '0', '1', '0', '', '', '', '', '', '0', '0');

-- ----------------------------
-- Table structure for `yzn_model_field`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_model_field`;
CREATE TABLE `yzn_model_field` (
  `fieldid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `modelid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '模型ID',
  `field` varchar(20) NOT NULL DEFAULT '' COMMENT '字段名',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '别名',
  `tips` text COMMENT '字段提示',
  `css` varchar(30) NOT NULL DEFAULT '' COMMENT '表单样式',
  `minlength` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最小值',
  `maxlength` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最大值',
  `pattern` varchar(255) NOT NULL DEFAULT '' COMMENT '数据校验正则',
  `errortips` varchar(255) NOT NULL DEFAULT '' COMMENT '数据校验未通过的提示信息',
  `formtype` varchar(20) NOT NULL DEFAULT '' COMMENT '字段类型',
  `setting` mediumtext,
  `formattribute` varchar(255) NOT NULL DEFAULT '',
  `unsetgroupids` varchar(255) NOT NULL DEFAULT '',
  `unsetroleids` varchar(255) NOT NULL DEFAULT '',
  `iscore` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否内部字段 1是',
  `issystem` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否系统字段 1 是',
  `isunique` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '值唯一',
  `isbase` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '作为基本信息',
  `issearch` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '作为搜索条件',
  `isadd` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '在前台投稿中显示',
  `isfulltext` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '作为全站搜索信息',
  `isposition` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否入库到推荐位',
  `listorder` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `disabled` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1 禁用 0启用',
  `isomnipotent` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `validate_type` varchar(25) NOT NULL DEFAULT '' COMMENT '验证方式',
  `validate_rule` varchar(255) NOT NULL DEFAULT '' COMMENT '验证规则',
  PRIMARY KEY (`fieldid`),
  KEY `modelid` (`modelid`,`disabled`),
  KEY `field` (`field`,`modelid`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COMMENT='模型字段列表';

-- ----------------------------
-- Records of yzn_model_field
-- ----------------------------
INSERT INTO `yzn_model_field` VALUES ('1', '1', 'status', '状态', '', '', '0', '2', '', '', 'box', 'a:8:{s:7:\"options\";s:0:\"\";s:9:\"fieldtype\";s:7:\"varchar\";s:5:\"width\";s:0:\"\";s:4:\"size\";s:0:\"\";s:12:\"defaultvalue\";s:0:\"\";s:10:\"outputtype\";s:1:\"0\";s:12:\"backstagefun\";s:0:\"\";s:8:\"frontfun\";s:0:\"\";}', '', '', '', '1', '1', '0', '1', '0', '0', '0', '0', '15', '0', '0', '', '');
INSERT INTO `yzn_model_field` VALUES ('2', '1', 'username', '用户名', '', '', '0', '20', '', '', 'text', 'a:5:{s:4:\"size\";s:0:\"\";s:12:\"defaultvalue\";s:0:\"\";s:10:\"ispassword\";s:1:\"0\";s:12:\"backstagefun\";s:0:\"\";s:8:\"frontfun\";s:0:\"\";}', '', '', '', '1', '1', '0', '1', '0', '0', '0', '0', '16', '0', '0', '', '');
INSERT INTO `yzn_model_field` VALUES ('3', '1', 'islink', '转向链接', '', '', '0', '0', '', '', 'islink', 'a:3:{s:4:\"size\";s:0:\"\";s:12:\"backstagefun\";s:0:\"\";s:8:\"frontfun\";s:0:\"\";}', '', '', '', '0', '1', '0', '0', '0', '1', '0', '0', '17', '0', '0', '', '');
INSERT INTO `yzn_model_field` VALUES ('4', '1', 'template', '内容页模板', '', '', '0', '30', '', '', 'template', 'a:2:{s:12:\"backstagefun\";s:0:\"\";s:8:\"frontfun\";s:0:\"\";}', '', '-99', '-99', '0', '0', '0', '0', '0', '0', '0', '0', '13', '0', '0', '', '');
INSERT INTO `yzn_model_field` VALUES ('5', '1', 'allow_comment', '允许评论', '', '', '0', '0', '', '', 'box', 'a:10:{s:7:\"options\";s:32:\"允许评论|1\n不允许评论|0\";s:7:\"boxtype\";s:5:\"radio\";s:9:\"fieldtype\";s:7:\"tinyint\";s:9:\"minnumber\";s:1:\"1\";s:5:\"width\";s:2:\"88\";s:4:\"size\";s:0:\"\";s:12:\"defaultvalue\";s:1:\"1\";s:10:\"outputtype\";s:1:\"1\";s:12:\"backstagefun\";s:0:\"\";s:8:\"frontfun\";s:0:\"\";}', '', '', '', '0', '0', '0', '0', '0', '0', '0', '0', '14', '0', '0', '', '');
INSERT INTO `yzn_model_field` VALUES ('6', '1', 'pages', '分页方式', '', '', '0', '0', '', '', 'pages', 'a:2:{s:12:\"backstagefun\";s:0:\"\";s:8:\"frontfun\";s:0:\"\";}', '', '-99', '-99', '0', '0', '0', '1', '0', '0', '0', '0', '9', '0', '0', '', '');
INSERT INTO `yzn_model_field` VALUES ('7', '1', 'inputtime', '真实发布时间', '', '', '0', '0', '', '', 'datetime', 'a:5:{s:9:\"fieldtype\";s:3:\"int\";s:6:\"format\";s:11:\"Y-m-d H:i:s\";s:11:\"defaulttype\";s:1:\"0\";s:12:\"backstagefun\";s:0:\"\";s:8:\"frontfun\";s:0:\"\";}', '', '', '', '1', '1', '0', '0', '0', '0', '0', '1', '11', '0', '0', '', '');
INSERT INTO `yzn_model_field` VALUES ('8', '1', 'posid', '推荐位', '', '', '0', '0', '', '', 'posid', 'a:4:{s:5:\"width\";s:3:\"125\";s:12:\"defaultvalue\";s:0:\"\";s:12:\"backstagefun\";s:0:\"\";s:8:\"frontfun\";s:0:\"\";}', '', '', '', '0', '1', '0', '1', '0', '0', '0', '1', '11', '0', '0', '', '');
INSERT INTO `yzn_model_field` VALUES ('9', '1', 'url', 'URL', '', '', '0', '100', '', '', 'text', 'a:5:{s:4:\"size\";s:0:\"\";s:12:\"defaultvalue\";s:0:\"\";s:10:\"ispassword\";s:1:\"0\";s:12:\"backstagefun\";s:0:\"\";s:8:\"frontfun\";s:0:\"\";}', '', '', '', '1', '1', '0', '1', '0', '0', '0', '1', '12', '0', '0', '', '');
INSERT INTO `yzn_model_field` VALUES ('10', '1', 'listorder', '排序', '', '', '0', '6', '', '', 'number', 'a:7:{s:9:\"minnumber\";s:0:\"\";s:9:\"maxnumber\";s:0:\"\";s:13:\"decimaldigits\";s:1:\"0\";s:4:\"size\";s:0:\"\";s:12:\"defaultvalue\";s:0:\"\";s:12:\"backstagefun\";s:0:\"\";s:8:\"frontfun\";s:0:\"\";}', '', '', '', '1', '1', '0', '1', '0', '0', '0', '0', '18', '0', '0', '', '');
INSERT INTO `yzn_model_field` VALUES ('11', '1', 'relation', '相关文章', '', '', '0', '255', '', '', 'omnipotent', 'a:4:{s:8:\"formtext\";s:464:\"<input type=\"hidden\" name=\"info[relation]\" id=\"relation\" value=\"{FIELD_VALUE}\" style=\"50\" >\n<ul class=\"list-dot\" id=\"relation_text\">\n</ul>\n<input type=\"button\" value=\"添加相关\" onClick=\"omnipotent(\'selectid\',GV.DIMAUB+\'index.php?a=public_relationlist&m=Content&g=Content&modelid={MODELID}\',\'添加相关文章\',1)\" class=\"btn\">\n<span class=\"edit_content\">\n  <input type=\"button\" value=\"显示已有\" onClick=\"show_relation({MODELID},{ID})\" class=\"btn\">\n</span>\";s:9:\"fieldtype\";s:7:\"varchar\";s:12:\"backstagefun\";s:0:\"\";s:8:\"frontfun\";s:0:\"\";}', '', '', '', '0', '0', '0', '0', '0', '0', '1', '0', '8', '0', '0', '', '');
INSERT INTO `yzn_model_field` VALUES ('12', '1', 'thumb', '缩略图', '', '', '0', '100', '', '', 'image', 'a:10:{s:5:\"width\";s:0:\"\";s:12:\"defaultvalue\";s:0:\"\";s:9:\"show_type\";s:1:\"1\";s:15:\"upload_allowext\";s:20:\"jpg|jpeg|gif|png|bmp\";s:9:\"watermark\";s:1:\"0\";s:13:\"isselectimage\";s:1:\"1\";s:12:\"images_width\";s:0:\"\";s:13:\"images_height\";s:0:\"\";s:12:\"backstagefun\";s:0:\"\";s:8:\"frontfun\";s:0:\"\";}', '', '', '', '0', '1', '0', '0', '0', '1', '0', '1', '7', '0', '0', '', '');
INSERT INTO `yzn_model_field` VALUES ('13', '1', 'catid', '栏目', '', '', '1', '6', '/^[0-9]{1,6}$/', '请选择栏目', 'catid', 'a:2:{s:12:\"backstagefun\";s:0:\"\";s:8:\"frontfun\";s:0:\"\";}', '', '-99', '-99', '0', '1', '0', '1', '1', '1', '0', '0', '1', '0', '0', '', '');
INSERT INTO `yzn_model_field` VALUES ('15', '1', 'title', '标题', '', 'inputtitle', '1', '80', '', '请输入标题', 'title', 'a:2:{s:12:\"backstagefun\";s:0:\"\";s:8:\"frontfun\";s:0:\"\";}', '', '', '', '0', '1', '0', '1', '1', '1', '1', '1', '3', '0', '0', '', '');
INSERT INTO `yzn_model_field` VALUES ('16', '1', 'keywords', '关键词', '多关之间用空格或者“,”隔开', '', '0', '40', '', '', 'keyword', 'a:2:{s:12:\"backstagefun\";s:0:\"\";s:8:\"frontfun\";s:0:\"\";}', '', '-99', '-99', '0', '1', '0', '1', '1', '1', '1', '0', '2', '0', '0', '', '');
INSERT INTO `yzn_model_field` VALUES ('17', '1', 'tags', 'TAGS', '多关之间用空格或者“,”隔开', '', '0', '0', '', '', 'tags', 'a:4:{s:12:\"backstagefun\";s:0:\"\";s:17:\"backstagefun_type\";s:1:\"1\";s:8:\"frontfun\";s:0:\"\";s:13:\"frontfun_type\";s:1:\"1\";}', '', '', '', '0', '1', '0', '1', '0', '0', '0', '0', '4', '0', '0', '', '');
INSERT INTO `yzn_model_field` VALUES ('18', '1', 'description', '摘要', '', '', '0', '255', '', '', 'textarea', 'a:7:{s:5:\"width\";s:2:\"99\";s:6:\"height\";s:2:\"46\";s:12:\"defaultvalue\";s:0:\"\";s:10:\"enablehtml\";s:1:\"0\";s:9:\"fieldtype\";s:10:\"mediumtext\";s:12:\"backstagefun\";s:0:\"\";s:8:\"frontfun\";s:0:\"\";}', '', '', '', '0', '1', '0', '1', '0', '1', '1', '1', '5', '0', '0', '', '');
INSERT INTO `yzn_model_field` VALUES ('19', '1', 'updatetime', '发布时间', '', '', '0', '0', '', '', 'datetime', 'a:5:{s:9:\"fieldtype\";s:3:\"int\";s:6:\"format\";s:11:\"Y-m-d H:i:s\";s:11:\"defaulttype\";s:1:\"0\";s:12:\"backstagefun\";s:0:\"\";s:8:\"frontfun\";s:0:\"\";}', '', '', '', '0', '1', '0', '0', '0', '0', '0', '0', '10', '0', '0', '', '');
INSERT INTO `yzn_model_field` VALUES ('20', '1', 'content', '内容', '<style type=\"text/css\">.content_attr{ border:1px solid #CCC; padding:5px 8px; background:#FFC; margin-top:6px}</style><div class=\"content_attr\"><label><input name=\"add_introduce\" type=\"checkbox\"  value=\"1\" checked>是否截取内容</label><input type=\"text\" name=\"introcude_length\" value=\"200\" size=\"3\">字符至内容摘要\n<label><input type=\'checkbox\' name=\'auto_thumb\' value=\"1\" checked>是否获取内容第</label><input type=\"text\" name=\"auto_thumb_no\" value=\"1\" size=\"2\" class=\"\">张图片作为标题图片\n</div>', '', '1', '999999', '', '内容不能为空', 'editor', 'a:7:{s:7:\"toolbar\";s:4:\"full\";s:12:\"defaultvalue\";s:0:\"\";s:15:\"enablesaveimage\";s:1:\"1\";s:6:\"height\";s:0:\"\";s:9:\"fieldtype\";s:10:\"mediumtext\";s:12:\"backstagefun\";s:0:\"\";s:8:\"frontfun\";s:0:\"\";}', '', '', '', '0', '0', '0', '1', '0', '1', '1', '0', '6', '0', '0', '', '');
INSERT INTO `yzn_model_field` VALUES ('21', '1', 'copyfrom', '来源', '', '', '0', '0', '', '', 'copyfrom', 'a:4:{s:12:\"defaultvalue\";s:0:\"\";s:5:\"width\";s:0:\"\";s:12:\"backstagefun\";s:0:\"\";s:8:\"frontfun\";s:0:\"\";}', '', '', '', '0', '0', '0', '1', '0', '1', '0', '0', '5', '0', '0', '', '');
INSERT INTO `yzn_model_field` VALUES ('24', '1', 'prefix', '自定义文件名', '', '', '0', '255', '', '', 'text', 'a:5:{s:4:\"size\";s:3:\"200\";s:12:\"defaultvalue\";s:0:\"\";s:10:\"ispassword\";s:1:\"0\";s:12:\"backstagefun\";s:0:\"\";s:8:\"frontfun\";s:0:\"\";}', '', '', '', '0', '1', '0', '0', '0', '0', '0', '0', '17', '0', '0', '', '');

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
INSERT INTO `yzn_module` VALUES ('links', '友情链接', '960c30f9b119fa6c39a4a31867441c82', '0', '1', '1.0.0', null, '1505651640', '1505651640', '0');

-- ----------------------------
-- Table structure for `yzn_page`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_page`;
CREATE TABLE `yzn_page` (
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '栏目ID',
  `title` varchar(160) NOT NULL DEFAULT '' COMMENT '标题',
  `style` varchar(24) NOT NULL DEFAULT '' COMMENT '样式',
  `keywords` varchar(40) NOT NULL DEFAULT '' COMMENT '关键字',
  `content` text COMMENT '内容',
  `template` varchar(30) NOT NULL DEFAULT '',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  KEY `catid` (`catid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='单页内容表';

-- ----------------------------
-- Records of yzn_page
-- ----------------------------

-- ----------------------------
-- Table structure for `yzn_terms`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_terms`;
CREATE TABLE `yzn_terms` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `parentid` smallint(5) NOT NULL DEFAULT '0' COMMENT '父ID',
  `name` varchar(200) NOT NULL DEFAULT '' COMMENT '分类名称',
  `module` varchar(200) NOT NULL DEFAULT '' COMMENT '所属模块',
  `setting` mediumtext COMMENT '相关配置信息',
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `module` (`module`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='分类表';

-- ----------------------------
-- Records of yzn_terms
-- ----------------------------
