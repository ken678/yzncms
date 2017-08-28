/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : yzncms

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2017-08-28 13:29:10
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
) ENGINE=MyISAM AUTO_INCREMENT=220 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='行为日志表';

-- ----------------------------
-- Records of yzn_action_log
-- ----------------------------
INSERT INTO `yzn_action_log` VALUES ('153', '1', '1', '2130706433', 'member', '1', 'admin在2017-07-22 15:57登录了后台', '1500710221');
INSERT INTO `yzn_action_log` VALUES ('154', '1', '1', '0', 'member', '1', 'admin在2017-07-23 10:46登录了后台', '1500778019');
INSERT INTO `yzn_action_log` VALUES ('155', '1', '1', '0', 'member', '1', 'admin在2017-07-29 11:31登录了后台', '1501299069');
INSERT INTO `yzn_action_log` VALUES ('156', '1', '1', '0', 'member', '1', 'admin在2017-07-29 18:36登录了后台', '1501324593');
INSERT INTO `yzn_action_log` VALUES ('157', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-03 12:58登录了后台', '1501736316');
INSERT INTO `yzn_action_log` VALUES ('158', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-03 13:20登录了后台', '1501737638');
INSERT INTO `yzn_action_log` VALUES ('159', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-03 17:01登录了后台', '1501750876');
INSERT INTO `yzn_action_log` VALUES ('160', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-07 15:53登录了后台', '1502092421');
INSERT INTO `yzn_action_log` VALUES ('161', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-07 16:02登录了后台', '1502092963');
INSERT INTO `yzn_action_log` VALUES ('162', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-07 16:05登录了后台', '1502093153');
INSERT INTO `yzn_action_log` VALUES ('163', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-07 16:23登录了后台', '1502094198');
INSERT INTO `yzn_action_log` VALUES ('164', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-07 18:19登录了后台', '1502101165');
INSERT INTO `yzn_action_log` VALUES ('165', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-08 10:37登录了后台', '1502159872');
INSERT INTO `yzn_action_log` VALUES ('166', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-08 13:14登录了后台', '1502169297');
INSERT INTO `yzn_action_log` VALUES ('167', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-08 14:36登录了后台', '1502174161');
INSERT INTO `yzn_action_log` VALUES ('168', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-08 15:26登录了后台', '1502177191');
INSERT INTO `yzn_action_log` VALUES ('169', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-09 09:16登录了后台', '1502241393');
INSERT INTO `yzn_action_log` VALUES ('170', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-09 09:16登录了后台', '1502241418');
INSERT INTO `yzn_action_log` VALUES ('171', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-09 10:02登录了后台', '1502244133');
INSERT INTO `yzn_action_log` VALUES ('172', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-09 10:30登录了后台', '1502245815');
INSERT INTO `yzn_action_log` VALUES ('173', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-09 11:36登录了后台', '1502249794');
INSERT INTO `yzn_action_log` VALUES ('174', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-09 17:06登录了后台', '1502269573');
INSERT INTO `yzn_action_log` VALUES ('175', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-09 17:31登录了后台', '1502271105');
INSERT INTO `yzn_action_log` VALUES ('176', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-09 18:15登录了后台', '1502273740');
INSERT INTO `yzn_action_log` VALUES ('177', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-10 16:46登录了后台', '1502354803');
INSERT INTO `yzn_action_log` VALUES ('178', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-10 17:37登录了后台', '1502357828');
INSERT INTO `yzn_action_log` VALUES ('179', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-11 13:34登录了后台', '1502429679');
INSERT INTO `yzn_action_log` VALUES ('180', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-11 14:06登录了后台', '1502431566');
INSERT INTO `yzn_action_log` VALUES ('181', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-11 15:24登录了后台', '1502436254');
INSERT INTO `yzn_action_log` VALUES ('182', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-11 15:40登录了后台', '1502437257');
INSERT INTO `yzn_action_log` VALUES ('183', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-11 17:45登录了后台', '1502444713');
INSERT INTO `yzn_action_log` VALUES ('184', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-14 08:17登录了后台', '1502669839');
INSERT INTO `yzn_action_log` VALUES ('185', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-14 11:18登录了后台', '1502680715');
INSERT INTO `yzn_action_log` VALUES ('186', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-14 12:56登录了后台', '1502686605');
INSERT INTO `yzn_action_log` VALUES ('187', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-14 15:28登录了后台', '1502695721');
INSERT INTO `yzn_action_log` VALUES ('188', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-14 17:37登录了后台', '1502703452');
INSERT INTO `yzn_action_log` VALUES ('189', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-14 17:52登录了后台', '1502704342');
INSERT INTO `yzn_action_log` VALUES ('190', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-14 18:00登录了后台', '1502704831');
INSERT INTO `yzn_action_log` VALUES ('191', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-14 18:21登录了后台', '1502706083');
INSERT INTO `yzn_action_log` VALUES ('192', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-15 08:15登录了后台', '1502756132');
INSERT INTO `yzn_action_log` VALUES ('193', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-15 09:17登录了后台', '1502759868');
INSERT INTO `yzn_action_log` VALUES ('194', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-15 10:43登录了后台', '1502765018');
INSERT INTO `yzn_action_log` VALUES ('195', '1', '1', '2130706433', 'member', '1', 'admin在2017-08-15 12:58登录了后台', '1502773115');
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
INSERT INTO `yzn_admin` VALUES ('1', 'admin', '4459f1e16266d94ab6436a6743c838d97e9dca1f', '1', 'Wo0bAa', '御宅男', '1503884324', '2130706433', '530765310@qq.com');
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
) ENGINE=MyISAM AUTO_INCREMENT=57 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of yzn_article
-- ----------------------------
INSERT INTO `yzn_article` VALUES ('1', '21', '阿里封杀电商导购原因：分流淘宝 易被对手拉拢', '', 'http://www.yzncms.com/demo/file/2013/06/51c51df81df14.jpg', '电商,阿里巴巴,淘宝网,美丽说,马云', '写在前面的话：当阿里巴巴买百度关键字，封杀美丽说、蘑菇街之时，谁还曾记得，彼时，阿里还曾将美丽说、蘑菇街当作心头好。是什么原因使阿...', '/index.php?a=shows&catid=21&id=1', '0', '99', '1', '0', 'admin', '1371872761', '1371872761', '1', '', '马云', '0', '0', '0', '0', '0', '0');
INSERT INTO `yzn_article` VALUES ('2', '21', '传网店将收5%营业税 淘宝卖家一年或缴税400亿', '', 'http://www.yzncms.com/demo/file/2013/06/51c51ef3aaeca.jpg', '网店缴税,淘宝开店缴税,网店将收5%营业税', '山雨欲来风满楼。 5月30日，业界传出电商网店将被征收5%营业税的消息，在电商界犹如扔下了一颗原子弹。 日前，《每日经济新闻》记者就此事先后向国家工商总局市场司司长刘红亮、财政部财政科学研究所所长贾康、财政部财政科学研究所副所长刘尚希等求证，但或许基于话题', '/index.php?a=shows&catid=21&id=2', '0', '99', '1', '0', 'admin', '1371873012', '1371873012', '1', '', '淘宝', '0', '0', '0', '0', '0', '0');
INSERT INTO `yzn_article` VALUES ('3', '21', '马云：菜鸟让我们对社会、未来有敬畏之心', '', 'http://www.yzncms.com/demo/file/2013/06/51c51f2eefb83.jpg', '马云,菜鸟,电子商务', '新浪科技讯 5月28日上午消息，由阿里巴巴集团牵头的物流项目中国智能骨干网（简称CSN）今日在深圳正式启动。马云在会上表示，这次出来并不是所谓的复出，而是为了实现四五年前的想法，只是今天选择了这个秀，今天的出台，是我们一代人的理想、梦想。 马云同时解释了为', '/index.php?a=shows&catid=21&id=3', '0', '99', '1', '0', 'admin', '1371873071', '1371873071', '1', '', '马云', '0', '0', '0', '0', '0', '0');
INSERT INTO `yzn_article` VALUES ('4', '22', '为什么新浪微博广告屡遭骂名而豆瓣广告收获好评？', '', 'http://www.yzncms.com/demo/file/2013/06/51c52009459ad.jpg', '什么,新浪,微博,广告,屡遭,骂名,豆瓣,获好评,橘生,淮南,则为', '橘生淮南则为橘，生于淮北则为枳，这句话在社交网络的广告上同样适用。打开新浪微博和豆瓣，你会看到两种风格完全不同的广告，下面随便拿出一些对比。 前者为新浪微博上的推广广告，后者为豆瓣上的硬广推广。二者的差别？我想不用多做解释了吧。看了二者的广告，你可能', '/index.php?a=shows&catid=22&id=4', '0', '99', '1', '0', 'admin', '1371873289', '1371873289', '1', '', '新浪微博', '0', '0', '0', '0', '0', '0');
INSERT INTO `yzn_article` VALUES ('5', '22', '大众点评网遭“黑”手：网页跳转至天猫', '', 'http://www.yzncms.com/demo/file/2013/06/51c52313391d9.jpg', '大众点评,大众点评被黑客攻击,网页跳转至天猫', '昨天凌晨，很多大众点评网的用户登录网站时发现无法打开网页，取而代之的是一个写着QQ号的弹窗，随后跳转至天猫页面，导致一些在点评网团购付款成功的用户订单延误。众多用户纷纷吐槽此次网站被黑事件，而QQ号指向的黑客helen则否认是其所为。', '/index.php?a=shows&catid=22&id=5', '0', '99', '1', '0', 'admin', '1371874073', '1371874073', '1', '', '大众点评', '0', '0', '0', '0', '0', '0');
INSERT INTO `yzn_article` VALUES ('6', '22', '狗狗搜索宣布关站 版权问题难解', '', 'http://www.yzncms.com/demo/file/2013/06/51c5236ccba0e.jpg', '狗狗搜索,宣布关站,版权问题', '【搜狐IT消息】（小蕊）5月2日消息，狗狗搜索今日宣布暂停gougou.com网站服务，具体原因未知，版权问题恐怕依旧是难解之痛。 狗狗搜索是提供影视剧、电子书、软件、音乐下载的搜索引擎，2004年由李学凌创办，曾获得雷军100万人民币投资，2007年被卖给迅雷。', '/index.php?a=shows&catid=22&id=6', '0', '99', '1', '0', 'admin', '1371874160', '1371874160', '1', '', '狗狗搜索 迅雷', '0', '0', '0', '0', '0', '0');
INSERT INTO `yzn_article` VALUES ('7', '22', '反击易迅 京东欲推一日四送', '', 'http://www.yzncms.com/demo/file/2013/06/51c523a819ded.jpg', '易迅 京东 一日四送', '一日四送 极速达一日四送服务，将实现三个小时商品送达，比之前的211限时达快出两倍 一日三送 早上订单在下午2时之前送达，中午订单在晚上6时之前送达，晚间订单在晚上10时之前送达 继价格战后，一对电商 冤家对头京东和易迅又将竞争的焦点对准到了物流速度上。', '/index.php?a=shows&catid=22&id=7', '0', '99', '1', '0', 'admin', '1371874216', '1371874216', '1', '', '易迅 京东', '0', '0', '0', '0', '0', '0');
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
INSERT INTO `yzn_article` VALUES ('21', '11', 'DreamWeaver文件保存时提示＂发生共享违例＂问题分析及解决方法', '', 'http://www.yzncms.com/demo/file/2013/06/51c5419d2489e.png', '文件保存,共享违例,dreamweaver', '问题产生描述：DreamWeaver文件保存时，提示这样的问题&quot;发生共享违例&quot;，具体如图：修改HTML文件后，就是保存不了，一保存就提示&quot;路径+时发...', '/index.php?a=shows&catid=11&id=21', '0', '99', '1', '0', 'admin', '1371881885', '1371881885', '0', '', 'dreamweaver', '0', '0', '0', '0', '0', '0');
INSERT INTO `yzn_article` VALUES ('22', '11', 'DreamWeaver中如何批量删除超级链接', '', 'http://www.yzncms.com/demo/file/2013/06/51c541d5c23ae.jpg', 'DreamWeaver,如何,批量,删除,超级链接,问题,提', '问题提出：在线问答网友提问，请问专家DreamWeaver 8中如何批量删除超级链接?   电脑软硬件应用网回答：解决办法，依次打开DreamWeaver...', '/index.php?a=shows&catid=11&id=22', '0', '99', '1', '0', 'admin', '1371881942', '1371881942', '0', '', 'DreamWeaver', '0', '0', '0', '0', '0', '0');
INSERT INTO `yzn_article` VALUES ('23', '12', '分享前端开发JavaScript中常用的使用小技巧语句', '', 'http://www.yzncms.com/demo/file/2013/06/51c543b70c703.jpg', 'JavaScript语句,js技巧,前端开发,js教程,js常用语句', '前面我们分享过前端小技巧和JavaScript刷新页面及框架，里面也时不时的提到JavaScript。收集了一些JavaScript小技巧脚本代码。JavaScript是...', '/index.php?a=shows&catid=12&id=23', '0', '99', '1', '0', 'admin', '1371882423', '1371882423', '0', '', '', '0', '0', '0', '0', '0', '0');
INSERT INTO `yzn_article` VALUES ('24', '14', 'PS结合AI制作钢铁侠面具模型教程', '', 'http://www.yzncms.com/demo/file/2013/06/51c5445ee39c5.jpg', 'PS教程,PS打造钢铁侠面具,PS结合AI教程,ps实例', '教程虽然用到AI，不过绘制的仅是线稿及简单的色块，没有这款软件的完全可以在PS中完成。面具构造并不复杂，不过质感部分刻画比较麻烦，想省...', '/index.php?a=shows&catid=14&id=24', '0', '99', '1', '0', 'admin', '1371882593', '1371882593', '0', '', '', '2', '0', '1', '1', '1', '1435332393');
INSERT INTO `yzn_article` VALUES ('29', '10', '5种实现页面跳转到指定的地址的方法', '', '', 'JS实现页面跳转,html实现页面跳转,实现页面跳转的方法', '下面列了五个例子来详细说明，这几个例子的主要功能是：在5秒后，自动跳转到同目录下的hello.html（根据自己需要自行修改）文件。1) html...', '/index.php?a=shows&catid=10&id=29', '0', '99', '1', '0', 'admin', '1371907845', '1371907845', '0', '', '', '0', '0', '0', '0', '0', '0');
INSERT INTO `yzn_article` VALUES ('34', '24', 'Digital Atelier创意网站开发工作室网站欣赏', '', 'http://www.yzncms.com/demo/file/2013/06/51c5b06e7462d.jpg', 'Digital Atelier,jquery酷站,国外酷站,交互设计酷站', 'Digital studio focused on web, mobile and facebook development', '/index.php?a=shows&catid=24&id=34', '0', '99', '1', '0', 'admin', '1371910261', '1371910261', '0', '', '', '1', '0', '1', '1', '1', '1435332417');

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
INSERT INTO `yzn_article_data` VALUES ('1', '<p style=\"text-align:center\"><img border=\"0\" class=\"flag_bigP\" src=\"http://www.lvyecms.com/demo/file/2013/06/51c51df81df14.jpg\" style=\"border:1px solid #999999;max-width:680px;display:inline-block;\" /></p><p style=\"text-indent:2em;\">写在前面的话：当阿里巴巴买百度关键字，封杀美丽说、蘑菇街之时，谁还曾记得，彼时，阿里还曾将美丽说、蘑菇街当作心头好。是什么原因使阿里巴巴与他们反目？中间又隐藏了哪些腥风血雨抑或诡秘不为人知的纠纷？</p><p style=\"text-indent:2em;\">本文作者铁手观点如下：1，美丽说们最初小，而且能为淘宝带来交易量，所以被淘宝扶植。2，后来，他们长大了，开始分流淘宝用户和商家，淘宝难控制。3，更重要的是，美丽说们被反淘宝的百度、腾讯拉拢，特别是腾讯入股美丽说。4，美丽说们每月花数百万在新浪微博营销，阿里入股新浪微博也可以打击这些已变节的电商导购网站。5，当然，美丽说和蘑菇街也在自救，但能不能自救成功很难讲。（以上，by林丰蕾）</p><p style=\"text-indent:2em;\">文/铁手（搜狐IT独家特约作者）</p><p style=\"text-indent:2em;\">电商导购的末日是否已经来临？</p><p style=\"text-indent:2em;\">阿里巴巴入股新浪微博不到一个月内，与百度重修于好，并对导购网站蘑菇街、美丽说进行封杀、打压。</p><p style=\"text-indent:2em;\">据了解，阿里巴巴日前与百度达成一项2亿元的框架合作协议，通过百度搜索“男装”、“女装”等商品品类名称后，可以看到淘宝的推广链接，这是阿里巴巴自2008年停止投放百度广告后双方第一次恢复正常商业合作。除了推广链接外，在百度搜索蘑菇街或美丽说的关键词时，第一条也显示出淘宝的关键词推广。而在百度与阿里巴巴达成合作之前，蘑菇街与美丽说从百度获得了大量流量。</p><p style=\"text-indent:2em;\">此前业内也曾传出消息称，2012年中，马云曾在内部下达指示：一、阿里不能继续扶持蘑菇街、美丽说壮大；二、应该多做异业合作，少做同业合作。马云在内部的这一表达被外界解读为阿里巴巴对电商导购类网站的态度产生变化，由过去的扶持改为打压。不过由于电商导购类网站当时仍为淘宝带来巨大流量，因此打压的态势也主要通过私下运作，阿里巴巴方面甚至官方否认打压一说。</p><p style=\"text-indent:2em;\">不过，随着阿里巴巴入股新浪微博的成功，电商导购类网站对阿里巴巴的负面作用已经越发明显，凭借新浪微博的巨大流量，阿里巴巴方面已经可以不再依靠电商导购类网站，因此下重手也在情理之中。</p><p style=\"text-indent:2em;\">令阿里巴巴对蘑菇街、美丽说由扶持转变为打压的主要原因是，早期这类网站为淘宝带来了大量交易额和流量，淘宝推出淘宝客功能之后，两家凭借自身拥有的海量用户，刺激了淘宝的交易量。彼时淘宝高层认为蘑菇街、美丽说这种产品是淘宝的有益补充，淘宝自身不具备社交属性，而蘑菇街、美丽说则弥补了淘宝的这一缺陷。</p><p style=\"text-indent:2em;\">最火热的时候，蘑菇街、美丽说高层与淘宝高层几乎每周都有见面讨论未来的发展方向。不过这种好景没有持续太长时间。</p><p style=\"text-indent:2em;\">随着蘑菇街、美丽说的发展壮大，开始成为不依附于淘宝的第二股电商力量，并分流了淘宝的大量用户与商家，这导致马云对此产生警惕。而且除VC机构外，百度、腾讯曾经多次与蘑菇街、美丽说洽谈入股事宜。得知这些消息后的马云才于2012年中告诫高层需要打压此类网站。</p><p style=\"text-indent:2em;\">另一方面，脱离淘宝掌控的蘑菇街、美丽说也成为互联网上各种反淘宝力量拉拢的对象，例如美丽说就在D轮融资中引入了腾讯投资。</p><p style=\"text-indent:2em;\">阿里巴巴的思路是，自己不善于社交产品，电商导购类又需要依附自己，因此是淘宝的补充产品。但两家壮大之后反而养虎为患，因此又不得不打压这类网站并寻找新的社交突破口，而新浪微博正是这个时候开始被阿里巴巴所看重。</p><p style=\"text-indent:2em;\">未经证实的数据显示，新浪微博是蘑菇街、美丽说类网站两个主要用户来源中的一个，蘑菇街每月对新浪微博的投入在数十万至数百万不等，美丽说更是曾经投掷千万级广告费用在新浪微博的营销上来获取新用户。</p><p style=\"text-indent:2em;\">对看到这一情形的阿里巴巴，入股新浪微博既可以弥补自身社交软肋，又可以打击已变节的电商导购网站。</p><p style=\"text-indent:2em;\">当然，随着阿里巴巴态度的变化，电商导购类网站也并非坐以待毙，蘑菇街目前正在筹备转型，并计划自建商城。这一计划被媒体曝光后，蘑菇街为了掩人耳目，以这是团购项目引起了外界误解为由作为回复。但据我们了解，蘑菇街筹建商城的计划已经经过多次讨论，这家导购网站今年制定的重要KPI之一就是摆脱淘宝能够获得独立的生存空间。</p><p style=\"text-indent:2em;\">另一方面，美丽说虽然在业务转型上比蘑菇街迟缓，但该公司在D轮融资中获得了来自腾讯的巨额融资，通过捆绑腾讯，美丽说也计划脱离淘宝争取独立生存空间。另有信息显示，美丽说2011年已经邀请百度销售总监杜郭伟加盟担任销售副总裁，组建独立销售团队销售广告，或许是美丽说对抗淘宝打压的破解之道。</p><p><br /></p>', '2', '10000', '', '0', '1', '', '');
INSERT INTO `yzn_article_data` VALUES ('2', '<p style=\"text-align:center\"><img border=\"0\" src=\"http://www.lvyecms.com/demo/file/2013/06/51c51ef3aaeca.jpg\" style=\"border:1px solid #999999;max-width:680px;display:inline-block;\" /></p><p style=\"text-indent:2em;\">山雨欲来风满楼。</p><p style=\"text-indent:2em;\">5月30日，业界传出电商网店将被征收5%营业税的消息，在电商界犹如扔下了一颗原子弹。</p><p style=\"text-indent:2em;\">日前，《每日经济新闻》记者就此事先后向国家工商总局市场司司长刘红亮、财政部财政科学研究所所长贾康、财政部财政科学研究所副所长刘尚希等求证，但或许基于话题本身的敏感性，上述人士基本以“不知情”回应。</p><p style=\"text-indent:2em;\">如果传闻为真，阿里巴巴生态中的C2C系将可能成为征税重点区。</p><p style=\"text-indent:2em;\">年内开征可能性不大</p><p style=\"text-indent:2em;\">今年“两会”期间，苏宁云商董事长张近东以及湖南步步高商业连锁董事长王填呼吁对网店交易实施征税。</p><p style=\"text-indent:2em;\">张近东对此解释说，由于电商发展速度太快，相应的配套措施未能跟上，直接导致年交易量中约有一半游离于法律之外。同时，电子商务行业非注册经营、非税销售、假冒产品充斥……等现象的出现，给行业带来不公平的竞争环境，不利于行业健康持续发展。建议工商、质检等相关部门加大产品质量监管、保护知识产权、合理征税等方面的管理。</p><p style=\"text-indent:2em;\">一时间，网店征税话题在电商界，甚至社会上掀起了数场舌战。</p><p style=\"text-indent:2em;\">据相关媒体援引消息人士言论称，国内网店征税一事目前进入倒计时，相关部委已开始商讨具体收税措施，年内有可能正式向网店征收5%营业税。</p><p style=\"text-indent:2em;\">此前，有媒体披露，在今年3月于北京召开的一次秘密会议上，商务部和财政部向有电商投资经验的投资机构征询征收税款的具体操作方法。</p><p style=\"text-indent:2em;\">种种信号似乎都在暗示着网店征税进程势不可挡。</p><p style=\"text-indent:2em;\">近日，《每日经济新闻》记者就此事先后向国家工商总局市场司司长刘红亮、财政部财政科学研究所所长贾康、财政部财政科学研究所副所长刘尚希等求证，但或许基于话题本身的敏感性，上述相关人士基本以“不知情”作为回应。</p><p style=\"text-indent:2em;\">对此现象，有业内人士向记者分析称，这些人士闭口不谈此事，可能主要基于两个原因：一是征税事宜仍处于讨论中，目前在政府未公布此事之前，提前泄露消息不妥；另一个原因可能在于，网店征税事宜，在讨论中仍存在不同声音，具体的相关事宜仍有变数，因此也不方便评论。</p><p style=\"text-indent:2em;\">4月1日正式施行的 《网络发票管理办法》，曾被认为是向电商征税的前兆。</p><p style=\"text-indent:2em;\">4月15日，发改委、财政部等13个部门联合发布的 《关于进一步促进电子商务健康快速发展有关工作的通知》中也再次提到了电商征税。</p><p style=\"text-indent:2em;\">电子商务行业独立分析师李成东向记者表示，网店征税已经是一股不可阻挡的潮流，区别只是时间早一点或晚一点的问题。</p><p style=\"text-indent:2em;\">不过李成东指出，“估计国家不会在年内实施相关的政策，因为对网店征税，仍需要进行一段时间的调查、研究，听取社会意见，同时还需要试行、反馈等流程，待实验成熟后再全面推广。”</p><p style=\"text-indent:2em;\">一位在互联网上从事与税务行业相关工作的业内人士向 《每日经济新闻》记者表示，如果国家真的对网店征税，可能建立一个专门的网络税收监控中心，将税收综合征管系统、内部发票管理系统与网络交易平台对接，通过获得网店经营者真实的网上交易数据，可以实现对每笔交易都有据可查，从而将税款应收尽收。</p><p style=\"text-indent:2em;\">对B2C网站将是明显利好</p><p style=\"text-indent:2em;\">目前，我国电商网站中，主流的B2C网站如天猫、京东等，均为正常纳税平台。目前所提到的电商征税，主要对象是以淘宝上为数众多的中小C2C卖家为代表的群体。</p><p style=\"text-indent:2em;\">对此，业界出现了两种不同的观点，观点的背后主要是基于各自的利益考量。</p><p style=\"text-indent:2em;\">以线下传统零售卖家为主要业务的群体，基本赞同网店征税事宜，比如苏宁等。而以线上电商为主的电商公司，则持保留意见或不赞同此举。</p><p style=\"text-indent:2em;\">而另外一种观点认为，现在就对网店征税有点操之过急，目前应该“放水养鱼”，在大部分中小网店都具备了一定风险抵御能力后，再向他们征税。此外，也有人从社会就业的角度反对向网店征税。这种观点认为，大量的网店目前解决了相当一部分人的就业，在目前中国就业形势不乐观的背景下，向网店征税可能导致不少网店倒闭，从而减少这个领域对社会就业的消化能力。</p><p style=\"text-indent:2em;\">赞同者认为，如果长时间只对实体征税，而不对网店征税，可能最终导致实体店的大量倒闭。</p><p style=\"text-indent:2em;\">如果征税事宜落实，中国C2C领域的老大淘宝可能会成为重点区。</p><p style=\"text-indent:2em;\">数据显示，2012年淘宝、天猫实现交易额突破万亿，其中天猫成交额大致为2000亿元，保守估计淘宝交易额约为8000亿元左右。如果不计算减免征税的额度而粗略计算，以5％的额度征税，淘宝上的商家一年下来将因此增加近400亿元的税收成本。</p><p style=\"text-indent:2em;\">上述从事与税务行业相关工作的业内人士指出，如果国家对网店征税，会给淘宝及中小卖家带来一定的经营风险。“首先就是一些货源渠道不正规的店铺，如果要征税的话，他们首先得出具相关的发票，而一些店主很难做到这点；此外，目前还不明晰的是，对网店征税是否将追溯到第一笔订单产生的时间点，如果那样，很多网店赚的钱都不够交税。而一旦开始施行这一政策，对于苏宁等B2C网站而言，将是明显的利好”。</p><p><br /></p>', '2', '10000', '', '0', '1', '', '');
INSERT INTO `yzn_article_data` VALUES ('3', '<p style=\"text-align:center\"><img alt=\"0e08e28aace06cc1364967483672abc9\" border=\"0\" src=\"http://www.lvyecms.com/demo/file/2013/06/51c51f2eefb83.jpg\" style=\"border:1px solid #999999;max-width:680px;display:inline-block;\" /></p><p style=\"text-indent:2em;\">新浪科技讯 5月28日上午消息，由阿里巴巴集团牵头的物流项目“中国智能骨干网”（简称CSN）今日在深圳正式启动。马云在会上表示，这次出来并不是所谓的复出，而是为了实现四五年前的想法，只是今天选择了这个秀，今天的出台，是我们一代人的理想、梦想。</p><p style=\"text-indent:2em;\">马云同时解释了为什么取名“菜鸟网络”，“为什么取菜鸟的名字？我刚刚做互联网的时候，很多人说我是一只菜鸟，但是正因为我们这批菜鸟，马化腾、李彦宏，所有这些菜鸟今天变成不一样的鸟，今天700万淘宝卖家，中国无数小的卖家，所有在网上做电子商务的都是菜鸟，只有菜鸟才能飞向千家万户。笨鸟先飞，飞了半天还是笨鸟，而菜鸟还有机会变成好 鸟。我们取这个名字不断提醒自己我们要为对社会有敬畏之心，对未来有敬畏之心，我们希望自己成为一只勤奋、努力、不断学习、对未来有敬畏、对昨天有感恩的鸟。”</p><p style=\"text-indent:2em;\">以下为马云演讲全文：</p><p style=\"text-indent:2em;\">我本来以为当了董事长以后就不需要再出来，但是没想到当董事长最主要职责就是出台和走秀。但是今天这个走秀是我期待了很多年的，这是一个我们阿里巴巴集团思考了4、5年一直希望建立的事，筹划了很久，但是很遗憾这个是在我不当CEO后才正式把这么大一个项目落地。</p><p style=\"text-indent:2em;\">4、5年来我们一直在思考一个问题，我们到底能为中国物流做什么？其实国家在整个物流建设上投入了几十万亿，但是效益并不是很高，随着电子商务高速发展，我们必须在中国提升整个国家社会的效益，生产制造、小企业，如何能够帮所有的货达天下、货运天下。</p><p style=\"text-indent:2em;\">今天中国物流高速发展离不开我们在座的三通一达，顺丰以及不在的所有物流公司的发展，我记得去年淘宝双十一节做了191亿，这是一个奇迹，但是更大奇迹居然在一天内运出7800万件包裹，这个数字背后是无数人把自己全家老小发动起来，把这些货送出去，没有让中国物流搞瘫。</p><p style=\"text-indent:2em;\">但是长此以往中国未来怎么解决这个问题，因为现在中国每天2500万包裹左右，十年后预计每年2亿包，今天中国的物流体系没有办法支撑未来2亿。所以我们有一个大胆设想通过建设中国智能骨干网，让全中国2000个城市在任何一个地方能够24小时，只要你上网购物，24小时货一定送到你们家，这是一个伟大的理想。</p><p style=\"text-indent:2em;\">四年来我们一直不敢下手，这个理想太大，没有人干过，甚至想都不敢想，我们觉得这是一个国家项目。我们去考察了日本、欧洲、美国，今天在欧洲、美国、日本都没有这样的先例，日本的物流发展非常好，美国物流发展也非常好，但是他们基于IT。</p><p style=\"text-indent:2em;\">在中国基于互联网、基于电子商务，如何把国家已经投下的基础设施能够发挥作用，如何能够把今天全中国100多万快递人员能够支撑未来再增加的1000万快递人员。从小的来讲，我希望更多的快递人员有尊严，这是一个很好的工作，我希望中国的物流、中国的效率，大家知道中国整个GDP中18%来自物流。但是发达国家在12%，这6个点如果降下来对整个国家经济效益是非常高。</p><p style=\"text-indent:2em;\">这是一个理想主义项目，这个项目我们不是一年两年做出来，而是准备花十年时间，淘宝花了十年，支付宝花了9年，阿里巴巴花了14年，任何一个有理想色彩的公司必须花十年才能做下来。所以我特别高兴。</p><p style=\"text-indent:2em;\">我跟快递公司交流过程中，这张网络起来，我们不会抢快递公司的生意，阿里巴巴永远不会做快递，因为我们没有这个能力，我们相信中国有很多快递公司做快递可以做得比我们好。但是这个物流网起来可能会影响所有快递公司以后的商业模式，以前我们认为对的东西可能不对了，因为它完全基于互联网思考。</p><p style=\"text-indent:2em;\">所以这是我们希望做的，是人们没有做过的。直到今天为止到底是什么产品、方向怎么样、模式怎么样，一直争论不休，但是我们不争论的是这是一个理想主义项目，不仅仅对我们有影响，最主要对社会有影响，对十年以后消费有巨大影响。这是我的看法。</p><p style=\"text-indent:2em;\">为什么取菜鸟的名字？我刚刚做互联网的时候，很多人说我是一只菜鸟，但是正因为我们这批菜鸟，马化腾、李彦宏，所有这些菜鸟今天变成不一样的鸟，今天700万淘宝卖家，中国无数小的卖家，所有在网上做电子商务的都是菜鸟，只有菜鸟才能飞向千家万户。笨鸟先飞，飞了半天还是笨鸟，而菜鸟还有机会变成好鸟。我们取这个名字不断提醒自己我们要为对社会有敬畏之心，对未来有敬畏之心，我们希望自己成为一只勤奋、努力、不断学习、对未来有敬畏、对昨天有感恩的鸟。</p><p style=\"text-indent:2em;\">我们希望通过1000、2000亿的投资能够翘动几十万亿中国已经有的基础设施，能够把国家基础设施发挥出效应。让我们高速公路、机场、码头充分利用，承担起本来应该有的责任。昨天在股东会上讲也许十年里我们失败，谁都不能保证你一定不失败。但是万一被我们搞成了，我觉得今生无悔，终于作为民营企业参与国家基础设施的投资和建设。这是划时代意义。一直以来基础设施是国有企业做，今天我们这些民营企业联合起来希望为这个时代、为这个社会做，今天我们也看到很多国有企业，很多大型企业，像中国人寿、中信，很多金融机构对我们展示出了巨大信心和支持，在这儿表示衷心感谢，后面加入的企业会越来越多，因为这不是为一家企业做，这是为一个时代做。</p><p style=\"text-indent:2em;\">再次谢谢大家，我还是做董事长，网上说我复出了，做社会化物流不是今天的想法，而是四五年前的想法，只是今天选择了这个秀、今天的出台，这是我们一代人的理想、梦想，谢谢大家！</p><p><br /></p>', '2', '10000', '', '0', '1', '', '');
INSERT INTO `yzn_article_data` VALUES ('4', '<p style=\"text-indent:2em;\">橘生淮南则为橘，生于淮北则为枳，这句话在社交网络的广告上同样适用。打开新浪微博和豆瓣，你会看到两种风格完全不同的广告，下面随便拿出一些对比。</p><p><br/></p><p><img border=\"0\" height=\"427\" src=\"http://www.lvyecms.com/demo/file/2013/06/51c52009459ad.jpg\" width=\"520\" style=\"border:1px solid #999999;max-width:680px;display:block;margin-left:auto;margin-right:auto;\" /></p><p style=\"text-indent:2em;\">前者为新浪微博上的推广广告，后者为豆瓣上的硬广推广。二者的差别？我想不用多做解释了吧。看了二者的广告，你可能会顿生感慨：在新浪微博这个粗暴发展的地方，连广告也是粗暴的，而在豆瓣这个文艺的地方，连广告也是文艺的。</p><p style=\"text-indent:2em;\">作为一个公关人，虽然我对新浪微博的广告持理解态度，但偶尔也会对它的推广感到反感，比如上面那个淘宝广告，这广告真的是对我——一名男士的精准投放吗？与新浪微博广告相比，豆瓣的广告并不惹人反感，相反，有时候还很讨人喜欢。这不只是我一个人的体验，很多身边经常上豆瓣的人，也多表示没有受到豆瓣广告的什么干扰。</p><p style=\"text-indent:2em;\">那么为什么豆瓣的广告很少有人否定，而新浪微博广告却一直背负骂名呢？</p><p style=\"text-indent:2em;\">粗暴的新浪微博广告与精准的豆瓣广告</p><p style=\"text-indent:2em;\">新浪微博的主要广告类别有微博顶部广告、底部广告、侧栏广告等形式，其中最粗暴的是微博推广（包括粉丝通和橱窗硬广广告），如果说顶部、底部和侧栏广告还类似于电视剧开播前的广告的话，那微博推广就像是直接在电视剧中插播广告了，话说现在电视剧都不在播放途中插播广告了，而微博却反其道而行之，这种“粗暴插播”的广告形式，严重影响了用户体验，遭来骂名也是必然的。</p><p style=\"text-indent:2em;\">豆瓣的广告与微博差别显著，它的主要类别有豆瓣读书右侧的电商购买网站（如亚马逊、当当等），豆瓣电影关联的在线选座购票链接（其中有各大电影院的电影票网上购买），豆瓣同城中的活动购票链接（包括演唱会、沙龙等），以及豆瓣电台中的广播广告和各个页面的硬广推广等。</p><p style=\"text-indent:2em;\">这其中前三种（豆瓣图书、电影、同城中的购买链接）广告形式，丝毫不会影响用户的体验，相反，它们通过这种形式，使想购买产品的人快捷方便地找到产品的购买链接并完成购买，有效地提升了用户的体验。</p><p style=\"text-indent:2em;\">豆瓣电台中的广播广告是相对比较硬的广告，它类似于收音机中的插播广告。但有三点决定用户并不会对这种广告耿耿于怀。第一是用户在收听豆瓣电台的30分钟内不会听到广告，这基本上是可以让部分用户接受的时间。第二是付费用户（每月10元）可以收听它的高音质+无广告版。第三是豆瓣电台是个优秀的产品，它推荐音乐的精准度是非常高的，以听点广告的代价，换来豆瓣电台精准的音乐推荐服务，这对大多数人来说是一个划算的买卖。</p><p style=\"text-indent:2em;\">豆瓣的硬广推广可能是所有广告形式中最硬的，相对来说也是对用户影响最大的。但它与新浪微博硬广不同的地方有两点。第一是广告统一置于页面的右上侧，这使得豆瓣的页面看起来至少是整洁的。第二是广告的整体风格清新、文艺，总体符合豆瓣的调性，基本上毫无违和感。看了豆瓣广告，再看新浪微博广告，会让人感觉是从设计精致的商场来到了卖地摊货的地下通道。豆瓣硬广的这两个特点使其并没有受到用户的大面积反感，相反不少用户还对这些广告称赞有加。</p><p style=\"text-indent:2em;\">新浪微博广告与豆瓣广告对比</p><p style=\"text-indent:2em;\">名称形式广告位特点</p><p style=\"text-indent:2em;\">新浪微博广告页面硬广+微博推广多处粗暴展示+插入</p><p style=\"text-indent:2em;\">豆瓣广告页面硬广+购买链接+电台硬广统一置于右侧温和展示+需要时提供</p><p style=\"text-indent:2em;\">通过以上分析可以发现豆瓣广告与新浪微博广告最大的差别有两个，其一为二者属性不同，豆瓣广告是在需要时提供，而新浪微博广告是强行提供，有人可能要说在大数据时代，精准广告推送是未来的趋势，但不管未来怎样，现在新浪微博的广告完全谈不上精准；其二为二者的视觉效果不同，豆瓣广告形式整洁、有序、文艺，符合豆瓣调性，而新浪微博广告杂乱、无序，不一定符合新浪微博调性。</p><p style=\"text-indent:2em;\">所以总体来说，新浪微博是在用传统广告的思维做广告，而豆瓣是在用社会化广告的思维做广告（关于此点可以参看之前的文章《像做产品一样做广告》。</p><p style=\"text-indent:2em;\">新浪微博广告应将步伐放慢一点</p><p style=\"text-indent:2em;\">吴军曾在《浪潮之巅》中提出一个适用互联网公司的基因决定论，豆瓣广告之所以与新浪微博广告差别明显，本质上也是因为二者基因不同，豆瓣是一家拥有文艺范的慢公司，而新浪是一家拥有媒体属性的快公司。</p><p style=\"text-indent:2em;\">豆瓣这一拥有8年多历史的公司，<span style=\"color:#222222;font-size:14px;line-height:26px;text-indent:28px;background-color:#ffffff;\"></span>仅仅在3年前才推出了展示类广告，从这不难看出豆瓣对用户体验的极端重视，它不会像新浪微博那样因为急于盈利而在许多方面忽视用户的体验。广告门在对于豆瓣的一篇报道中写道：豆瓣对广告客户有着坚持——要用豆瓣的语言与用户交流。</p><p style=\"text-indent:2em;\">慢基因决定了豆瓣对于广告客户的苛求，决定了豆瓣的广告没有偏离豆瓣轨迹且没有影响用户体验的结果。</p><p style=\"text-indent:2em;\">而快基因使新浪微博行走的步伐一直很快，但有时行走的太快容易忘掉出发时的初衷。新浪或许应该停下来思考一些问题，比如：为什么自己的广告被无数人骂，而豆瓣的广告却没人骂？</p><p><br /><br /></p>', '2', '10000', '', '0', '1', '', '');
INSERT INTO `yzn_article_data` VALUES ('5', '<p style=\"text-align:center\"><img alt=\"大众点评网遭“黑”手：helen否认与其有关\" src=\"http://www.lvyecms.com/demo/file/2013/06/51c523191dbf4.jpg\" style=\"font-family:arial, verdana, sans-serif;border:0px;max-width:680px;width:409px;height:173px;\" /></p><p style=\"text-align:center\"><img alt=\"大众点评网遭“黑”手：helen否认与其有关\" src=\"http://www.lvyecms.com/demo/file/2013/06/51c5231984627.jpg\" style=\"border:0px;max-width:680px;\" /></p><p style=\"text-indent:2em;\">昨天凌晨，很多大众点评网的用户登录网站时发现无法打开网页，取而代之的是一个写着QQ号的弹窗，随后跳转至“天猫”页面，导致一些在点评网团购付款成功的用户订单延误。众多用户纷纷吐槽此次网站被黑事件，而QQ号指向的黑客helen则否认是其所为。</p><p style=\"text-indent:2em;\">用户上网卡壳</p><p style=\"text-indent:2em;\">“大概晚上11点40多，我打开大众点评网，但是浏览器马上就报错了，弹出了一个对话框上面写着四字：‘凛冬将至’。网页上还有署名‘Helen’的黑客留下了一个QQ号。”大众点评网的用户王先生称自己看见后一时有点蒙，不知道是什么情况。“我上网一搜，原来大家都在说大众点评上不去的事情，有些人跳转完直接出现网址导航网站。”</p><p style=\"text-indent:2em;\">使用手机客户端的用户也同样遭遇了应用瘫痪的悲剧。“我手机上的大众点评网应用打开之后一直在内容载入中，登录也反复失败，我用手机号找回密码后收到的短信是乱码。”用户孙女士称自己本以为是手机出了问题，打开了其他应用程序才知道是大众点评网的问题。</p><p style=\"text-indent:2em;\">直到昨天清晨7点多，大众点评网站和手机客户端才恢复正常使用。</p><p style=\"text-indent:2em;\">网友订单延误</p><p style=\"text-indent:2em;\">由于网页的突然瘫痪，许多用户在提交订单付款后却无法再次确定付款成功和领取认证码。直到昨天清晨网页恢复正常后，用户再次登录成功却发现订单上没有付款成功的提示。“交了钱却没有给商户提供的验证码，在订单上也写着未付款成功，这是怎么回事？”王先生称自己的网银短信提示扣款成功，的确已经付款。</p><p style=\"text-indent:2em;\">“我给大众点评网的客服打电话，40分钟没有打进去，一直是线路忙，等待。”王先生无奈下使用了俗称“呼死你”的电话软件，却一直没有能打通热线电话。直到昨天上午9点半左右，大众点评客服官方微博称：“热线繁忙，电话排队中。烦请各位小点友耐心等待，如有问题可以私信给@大众点评网客服。”记者尝试拨打客服热线发现，直到中午11点多，客服热线基本恢复正常。</p><p style=\"text-indent:2em;\">helen否认与其有关</p><p style=\"text-indent:2em;\">对于网站的瘫痪，大众点评官方微博在昨天凌晨称：“因域名注册商出现系统漏洞，导致大众点评域名指向出现错误，这不会对用户造成任何信息安全方面的影响，广大用户可以放心。目前我们正努力联系域名注册商尽快修复他们的系统漏洞，以便尽快恢复大众点评的服务。对给用户造成的不便，我们深表歉意！”</p><p style=\"text-indent:2em;\">记者访问了网页中留下QQ号的黑客helen的空间，却发现他在昨天发布了一条声明，称自己并不是制造此次事故的黑客。“本人再次申明一点，在圈内我的名气很大，哥确实黑了国内不少安全小组的网站，也得罪了非常多的人，但是哥在国内只弄黑客站，基本不去碰其他网站的。哥专注国外网络安全已多年，所以很少在去关注国内网络安全。那些诬陷我的人，小心了。”记者随后联系helen，但其一直未予回应。</p><p><br /></p>', '2', '10000', '', '0', '1', '', '');
INSERT INTO `yzn_article_data` VALUES ('6', '<p style=\"text-align:center\"><img border=\"0\" height=\"262\" src=\"http://www.lvyecms.com/demo/file/2013/06/51c52370829ae.png\" width=\"600\" style=\"border:1px solid #999999;max-width:680px;display:inline-block;\" /></p><p style=\"text-indent:2em;\">【搜狐IT消息】（小蕊）5月2日消息，狗狗搜索今日宣布暂停gougou.com网站服务，具体原因未知，版权问题恐怕依旧是难解之痛。</p><p style=\"text-indent:2em;\">狗狗搜索是提供影视剧、电子书、软件、音乐下载的搜索引擎，2004年由李学凌创办，曾获得雷军100万人民币投资，2007年被卖给迅雷。</p><p style=\"text-indent:2em;\">狗狗搜索给迅雷带来了不小的版权麻烦，2010年迅雷筹划上市前期，狗狗搜索被迅雷以1万元卖给非关联第三方。</p><p style=\"text-indent:2em;\">狗狗搜索曾经多次关站，又多次重开。2011年4月，文化部联合广告总局下令狗狗搜索下令关闭整改；2011年11月，狗狗搜索公告因系统调整暂停网站服务；2011年12月初恢复访问之后，当月底又再次宣布停止服务。</p><p style=\"text-indent:2em;\">狗狗搜索官方并未对本次关站的原因做出说明，未来是否会再次重开也尚不清楚。</p><p><br /></p>', '2', '10000', '', '0', '1', '', '');
INSERT INTO `yzn_article_data` VALUES ('7', '<p style=\"text-align:center\"><img alt=\"京东反击易迅欲推一日四送 易迅回应：欢迎竞争\" src=\"http://www.lvyecms.com/demo/file/2013/06/51c523a819ded.jpg\" style=\"border:0px;max-width:680px;\" /></p><p style=\"text-indent:2em;\">一日四送</p><p style=\"text-indent:2em;\">“极速达”一日四送服务，将实现三个小时商品送达，比之前的“211限时达”快出两倍</p><p style=\"text-indent:2em;\">一日三送</p><p style=\"text-indent:2em;\">早上订单在下午2时之前送达，中午订单在晚上6时之前送达，晚间订单在晚上10时之前送达</p><p style=\"text-indent:2em;\">继价格战后，一对电商 “冤家对头”——京东和易迅又将竞争的焦点对准到了物流速度上。继去年12月易迅大举杀入京东大本营北京，并将“一日三送”服务扩展到北京市场后，昨日又传出消息称，京东近期正在紧锣密鼓地筹备“极速达”的一日四送服务，号称将实现三个小时商品送达，比之前的“211限时达”快出两倍。此举被业内认为是针对易迅的直接挑战。</p><p style=\"text-indent:2em;\">易迅网负责人表示，“电商的物流不仅要快速，更关键的还在于稳定和准时，我们期待和更多的企业一起不断提升面向消费者的服务，易迅非常欢迎竞争。”</p><p style=\"text-indent:2em;\">事件</p><p style=\"text-indent:2em;\">京东欲在京推“一日四送”</p><p style=\"text-indent:2em;\">配送速度一直是电商差异化竞争的焦点，从最早的三日送达，到隔日达，再到京东的“211”，以及目前易迅所保持的一日三送，电商巨头们在物流速度上进行的是内功的比拼。</p><p style=\"text-indent:2em;\">去年底，易迅高调宣布杀入京东大本营北京市场，一时间北京地铁公交上“在北京，谁比易迅更快”的广告铺天盖地，短期内带动了易迅在北京订单量的快速上扬，此举也使得京东颇为紧张，甚至在年初的内部会议上提出了“防易迅”的说法。</p><p style=\"text-indent:2em;\">京东公关媒介总监康健透露，目前“一日四送”服务只在北京试运行，即商品3小时送到。不过，对于该“一日四送”服务是否覆盖所有品类以及扩张到其他的城市，他表示由于是试运行，在品类和范围肯定有一定的限制，具体相关细节信息将会在下周公布。最后他还表示，目前试运行的品类包括有电脑类的，在北京可以到达一日四送。</p><p style=\"text-indent:2em;\">据悉，京东“极速达”配送服务已在北京地区开始试运行。之前京东依靠自有物流网络已经在一些城市先后推出211限时达、隔日达、晚间配送等服务升级措施，不过京东并没有说明在北京市场推出的一日四送服务，是否涵盖所有品类，以及是否会扩展到其他地区。</p><p style=\"text-indent:2em;\">回应</p><p style=\"text-indent:2em;\">易迅称欢迎直面竞争</p><p style=\"text-indent:2em;\">对于京东挑起的速度竞争，此前一直在国内电商物流领域领先的易迅网反应却比较平淡。</p><p style=\"text-indent:2em;\">其相关负责人在接受记者采访时表示，“电商的物流不仅要快速，更关键的还在于稳定和准时，我们期待更多的企业一起不断提升面向消费者的服务，易迅非常欢迎竞争。”</p><p style=\"text-indent:2em;\">此次京东和易迅在物流上的竞争并非首次。2005年易迅在上海推出了次日达服务，后来在京东进军上海市场后，易迅又先后将配送升级为“当日送达”，“一日两送”；在2009年京东也在上海跟进“一日两送”后，2010年易迅又果断地将配送升级为“一日三送”。2011年京东在上海也升级为“一日三送”。不过在试行一段时间后，京东取消“一日三送”服务，回归“一日两送”。</p><p style=\"text-indent:2em;\">解读</p><p style=\"text-indent:2em;\">或是针对竞争对手的炒作</p><p style=\"text-indent:2em;\">在自己大本营经营多年的京东能否通过“一日四送”来完成对易迅配送速度上的赶超？对此不少电商和物流行业专家表示了怀疑。</p><p style=\"text-indent:2em;\">中国电子商务协会物流专家、汉森世纪供应链总经理黄刚称，“京东‘一日四送’很可能是个噱头。”在他看来，易迅的一日三送已经是电商客户对于配送速度的最高需求，除非针对个别对配送速度特殊需求的商品。</p><p style=\"text-indent:2em;\">从易迅的“一日三送”来看，早上11点前的订单，过客服审单、财务出单、分区捡货，库房总拣，二次分拣到打包约需要1小时，而货车从仓库到市内配送点约需要1个小时，分单和送到快递员手中又需要40分钟的时间，这样最快的订单配送约需要3个小时的时间。而整个配送过程的不可控因素也很多，比如说仓库要离市中心足够近，即便如此道路堵塞也会影响到从仓库到配送点和从配送员到订户的速度，显然对于仓库分散而且离市区普遍较远的京东是无法具备这样的速度的。</p><p style=\"text-indent:2em;\">此外，京东目前的“211”一日两送，与其装车量、分拨量和送货量三个数据正好匹配，如果要满足“一日四送”，这些成本将大幅增加，这或是正在追求盈利的京东不愿意增加的成本。有业内人士指出，京东这次尝试的“一日四送”更多是针对竞争对手的一次炒作。 沈婷婷天府早报记者龚琼</p><p><br /></p>', '2', '10000', '', '0', '1', '', '');
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
INSERT INTO `yzn_article_data` VALUES ('34', '<p style=\"text-align:center;\"><a href=\"http://www.lvyecms.com/demo/file/2013/06/51c5b06e7462d.jpg\" title=\"点击查看原图\" target=\"_blank\"><img src=\"http://www.lvyecms.com/demo/file/2013/06/51c5b06e7462d.jpg\" id=\"bigimg\" alt=\"点击查看原图\" style=\"border:1px solid #eeeeee;background-color:#fafafa;padding:5px;margin:10px;display:inline-block;max-width:850px;\" /></a><a href=\"http://www.lvyecms.com/demo/file/2013/06/51c5b071827da.jpg\" title=\"点击查看原图\" target=\"_blank\"><img src=\"http://www.lvyecms.com/demo/file/2013/06/51c5b071827da.jpg\" id=\"bigimg\" alt=\"点击查看原图\" style=\"border:1px solid #eeeeee;background-color:#fafafa;padding:5px;margin:10px;display:inline-block;max-width:850px;\" /></a><a href=\"http://www.lvyecms.com/demo/file/2013/06/51c5b0736d7c1.jpg\" title=\"点击查看原图\" target=\"_blank\"><img src=\"http://www.lvyecms.com/demo/file/2013/06/51c5b0736d7c1.jpg\" id=\"bigimg\" alt=\"点击查看原图\" style=\"border:1px solid #eeeeee;background-color:#fafafa;padding:5px;margin:10px;display:inline-block;max-width:850px;\" /></a></p>', '2', '10000', '', '0', '1', '', '');

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
INSERT INTO `yzn_auth_rule` VALUES ('19', 'Admin', '2', 'Admin/index/index', '首页', '1', '');
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
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='缓存列队表';

-- ----------------------------
-- Records of yzn_cache
-- ----------------------------
INSERT INTO `yzn_cache` VALUES ('1', 'Model', '模型列表', 'Content', 'Models', 'model_cache', '0');
INSERT INTO `yzn_cache` VALUES ('2', 'Category', '栏目索引', 'Content', 'Category', 'category_cache', '0');
INSERT INTO `yzn_cache` VALUES ('3', 'ModelField', '模型字段', 'Content', 'ModelField', 'model_field_cache', '0');
INSERT INTO `yzn_cache` VALUES ('4', 'Config', '网站配置', '', 'Configs', 'config_cache', '1');

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
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=utf8 COMMENT='栏目表';

-- ----------------------------
-- Records of yzn_category
-- ----------------------------
INSERT INTO `yzn_category` VALUES ('1', 'content', '0', '1', '', '0', '0', '1', '1,10,11', '网页教程', '', '网页教程-网页设计基础教程DIV+CSS', '', 'jiaocheng', '/index.php?a=lists&catid=1', '0', 'a:12:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:12:\"category.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";N;}', '1', '1', '0', 'wangyejiaocheng');
INSERT INTO `yzn_category` VALUES ('2', 'content', '0', '1', '', '0', '0', '1', '2,12,13', '前端开发', '', '前端开发-学习最新前端开发技术', '', 'qianduan', '/index.php?a=lists&catid=2', '0', 'a:12:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:12:\"category.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";N;}', '2', '1', '0', 'qianduankaifa');
INSERT INTO `yzn_category` VALUES ('3', 'content', '0', '1', '', '0', '0', '1', '3,14,15', 'PS教程', '', 'PS教程-学习PS技巧,设计更好的页面', '', 'ps', '/index.php?a=lists&catid=3', '0', 'a:12:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:12:\"category.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";N;}', '3', '1', '0', 'psjiaocheng');
INSERT INTO `yzn_category` VALUES ('4', 'content', '0', '1', '', '0', '0', '1', '4,16,17', '网页特效', '', '提供各种网页效果,让你的网页更炫丽', '', 'texiao', '/index.php?a=lists&catid=4', '0', 'a:12:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:17:\"category_heng.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";N;}', '4', '1', '0', 'wangyetexiao');
INSERT INTO `yzn_category` VALUES ('6', 'content', '0', '1', '', '0', '0', '1', '6,20,21', '网站运营', '', '各种运营知识分享,助站长一臂之力', '', 'yunying', '/index.php?a=lists&catid=6', '0', 'a:12:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:12:\"category.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";N;}', '6', '1', '0', 'wangzhanyunying');
INSERT INTO `yzn_category` VALUES ('7', 'content', '0', '1', '', '0', '0', '1', '7,22,23', '站长杂谈', '', '站长杂谈-了解互联网第一手资讯', '', 'zatan', '/index.php?a=lists&catid=7', '0', 'a:12:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:12:\"category.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";N;}', '7', '1', '0', 'zhanchangzatan');
INSERT INTO `yzn_category` VALUES ('8', 'content', '0', '1', '', '0', '0', '1', '8,24,25', '设计欣赏', '', '设计欣赏-寻找设计灵感提高设计水平', '', 'sheji', '/index.php?a=lists&catid=8', '0', 'a:12:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:17:\"category_heng.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";N;}', '8', '1', '0', 'shejixinshang');
INSERT INTO `yzn_category` VALUES ('10', 'content', '0', '1', '', '1', '0,1', '0', '10,30', 'HTML/XHTML', '', '', 'jiaocheng/', 'html', '/index.php?a=lists&catid=10', '0', 'a:14:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"list_template\";s:8:\"list.php\";s:13:\"show_template\";s:8:\"show.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:14:\"content_ishtml\";s:1:\"0\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";s:1:\"4\";}', '10', '1', '0', 'html/xhtml');
INSERT INTO `yzn_category` VALUES ('11', 'content', '0', '1', '', '1', '0,1', '0', '11', 'Dreamweaver', '', '', 'jiaocheng/', 'dw', '/index.php?a=lists&catid=11', '0', 'a:14:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"list_template\";s:8:\"list.php\";s:13:\"show_template\";s:8:\"show.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:14:\"content_ishtml\";s:1:\"0\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";s:1:\"4\";}', '11', '1', '0', 'dreamweaver');
INSERT INTO `yzn_category` VALUES ('12', 'content', '0', '1', '', '2', '0,2', '0', '12', 'javascript教程', '', '', 'qianduan/', 'js', '/index.php?a=lists&catid=12', '0', 'a:14:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"list_template\";s:8:\"list.php\";s:13:\"show_template\";s:8:\"show.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:14:\"content_ishtml\";s:1:\"0\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";s:1:\"4\";}', '12', '1', '0', 'javascriptjiaocheng');
INSERT INTO `yzn_category` VALUES ('13', 'content', '0', '1', '', '2', '0,2', '0', '13', 'jquery教程', '', '', 'qianduan/', 'jq', '/index.php?a=lists&catid=13', '0', 'a:14:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"list_template\";s:8:\"list.php\";s:13:\"show_template\";s:8:\"show.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:14:\"content_ishtml\";s:1:\"0\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";s:1:\"4\";}', '13', '1', '0', 'jqueryjiaocheng');
INSERT INTO `yzn_category` VALUES ('14', 'content', '0', '1', '', '3', '0,3', '0', '14', 'PS网页制作', '', '', 'ps/', 'psweb', '/index.php?a=lists&catid=14', '0', 'a:14:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"list_template\";s:8:\"list.php\";s:13:\"show_template\";s:8:\"show.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:14:\"content_ishtml\";s:1:\"0\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";s:1:\"4\";}', '14', '1', '0', 'pswangyezhizuo');
INSERT INTO `yzn_category` VALUES ('15', 'content', '0', '1', '', '3', '0,3', '0', '15', 'PS按钮制作', '', '', 'ps/', 'psbtn', '/index.php?a=lists&catid=15', '0', 'a:14:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"list_template\";s:8:\"list.php\";s:13:\"show_template\";s:8:\"show.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:14:\"content_ishtml\";s:1:\"0\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";s:1:\"4\";}', '15', '1', '0', 'psanniuzhizuo');
INSERT INTO `yzn_category` VALUES ('16', 'content', '0', '1', '', '4', '0,4', '0', '16', 'JS幻灯片', '', '', 'texiao/', 'adjs', '/index.php?a=lists&catid=16', '0', 'a:14:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"list_template\";s:8:\"list.php\";s:13:\"show_template\";s:8:\"show.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:14:\"content_ishtml\";s:1:\"0\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";s:1:\"4\";}', '16', '1', '0', 'jshuandengpian');
INSERT INTO `yzn_category` VALUES ('17', 'content', '0', '1', '', '4', '0,4', '0', '17', '导航菜单', '', '', 'texiao/', 'nav', '/index.php?a=lists&catid=17', '0', 'a:14:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"list_template\";s:8:\"list.php\";s:13:\"show_template\";s:8:\"show.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:14:\"content_ishtml\";s:1:\"0\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";s:1:\"4\";}', '17', '1', '0', 'daohangcaidan');
INSERT INTO `yzn_category` VALUES ('20', 'content', '0', '1', '', '6', '0,6', '0', '20', 'SEO网站优化', '', '', 'yunying/', 'seo', '/index.php?a=lists&catid=20', '0', 'a:14:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"list_template\";s:8:\"list.php\";s:13:\"show_template\";s:8:\"show.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:14:\"content_ishtml\";s:1:\"0\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";s:1:\"4\";}', '20', '1', '0', 'seowangzhanyouhua');
INSERT INTO `yzn_category` VALUES ('21', 'content', '0', '1', '', '6', '0,6', '0', '21', '网络营销', '', '', 'yunying/', 'yingxiao', '/index.php?a=lists&catid=21', '0', 'a:14:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"list_template\";s:8:\"list.php\";s:13:\"show_template\";s:8:\"show.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:14:\"content_ishtml\";s:1:\"0\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";s:1:\"4\";}', '21', '1', '0', 'wangluoyingxiao');
INSERT INTO `yzn_category` VALUES ('22', 'content', '0', '1', '', '7', '0,7', '0', '22', '互联网资讯', '', '', 'zatan/', 'zixun', '/index.php?a=lists&catid=22', '0', 'a:14:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"list_template\";s:8:\"list.php\";s:13:\"show_template\";s:8:\"show.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:14:\"content_ishtml\";s:1:\"0\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";s:1:\"4\";}', '22', '1', '0', 'hulianwangzixun');
INSERT INTO `yzn_category` VALUES ('23', 'content', '0', '1', '', '7', '0,7', '0', '23', '人物访谈', '', '', 'zatan/', 'fangtan', '/index.php?a=lists&catid=23', '0', 'a:14:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"list_template\";s:8:\"list.php\";s:13:\"show_template\";s:8:\"show.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:14:\"content_ishtml\";s:1:\"0\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";s:1:\"4\";}', '23', '1', '0', 'renwufangtan');
INSERT INTO `yzn_category` VALUES ('24', 'content', '0', '1', '', '8', '0,8', '0', '24', '酷站欣赏', '', '', 'sheji/', 'web', '/index.php?a=lists&catid=24', '0', 'a:14:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"list_template\";s:8:\"list.php\";s:13:\"show_template\";s:11:\"show_da.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:14:\"content_ishtml\";s:1:\"0\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";s:1:\"4\";}', '24', '1', '0', 'kuzhanxinshang');
INSERT INTO `yzn_category` VALUES ('25', 'content', '0', '1', '', '8', '0,8', '0', '25', '标志LOGO', '', '', 'sheji/', 'logo', '/index.php?a=lists&catid=25', '0', 'a:14:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"list_template\";s:8:\"list.php\";s:13:\"show_template\";s:11:\"show_da.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:14:\"content_ishtml\";s:1:\"0\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";s:1:\"4\";}', '25', '1', '0', 'biaozhilogo');

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
) ENGINE=MyISAM AUTO_INCREMENT=41 DEFAULT CHARSET=utf8 COMMENT='后台菜单表';

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
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8 COMMENT='模型字段列表';

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
INSERT INTO `yzn_model_field` VALUES ('24', '1', 'prefix', '自定义文件名', '', '', '0', '255', '', '', 'text', 'a:5:{s:4:\"size\";s:3:\"180\";s:12:\"defaultvalue\";s:0:\"\";s:10:\"ispassword\";s:1:\"0\";s:12:\"backstagefun\";s:0:\"\";s:8:\"frontfun\";s:0:\"\";}', '', '', '', '0', '1', '0', '0', '0', '0', '0', '0', '17', '0', '0', '', '');
