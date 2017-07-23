/*
Navicat MySQL Data Transfer

Source Server         : 本地链接
Source Server Version : 50547
Source Host           : localhost:3306
Source Database       : yzncms

Target Server Type    : MYSQL
Target Server Version : 50547
File Encoding         : 65001

Date: 2017-07-23 14:32:10
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
) ENGINE=MyISAM AUTO_INCREMENT=155 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='行为日志表';

-- ----------------------------
-- Records of yzn_action_log
-- ----------------------------
INSERT INTO `yzn_action_log` VALUES ('129', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-26 09:52登录了后台', '1498441977');
INSERT INTO `yzn_action_log` VALUES ('128', '1', '1', '2130706433', 'member', '1', 'admin在2017-06-26 08:19登录了后台', '1498436342');
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
INSERT INTO `yzn_action_log` VALUES ('149', '1', '1', '0', 'member', '1', 'admin在2017-07-15 11:33登录了后台', '1500089606');
INSERT INTO `yzn_action_log` VALUES ('148', '1', '1', '0', 'member', '1', 'admin在2017-07-09 18:39登录了后台', '1499596773');
INSERT INTO `yzn_action_log` VALUES ('152', '1', '1', '0', 'member', '1', 'admin在2017-07-16 11:25登录了后台', '1500175515');
INSERT INTO `yzn_action_log` VALUES ('151', '1', '1', '0', 'member', '1', 'admin在2017-07-15 21:03登录了后台', '1500123837');
INSERT INTO `yzn_action_log` VALUES ('150', '1', '1', '0', 'member', '1', 'admin在2017-07-15 20:58登录了后台', '1500123499');
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
INSERT INTO `yzn_action_log` VALUES ('145', '1', '1', '2130706433', 'member', '1', 'admin在2017-07-07 22:52登录了后台', '1499439158');
INSERT INTO `yzn_action_log` VALUES ('146', '1', '1', '2130706433', 'member', '1', 'admin在2017-07-07 22:57登录了后台', '1499439475');
INSERT INTO `yzn_action_log` VALUES ('147', '1', '1', '0', 'member', '1', 'admin在2017-07-08 17:26登录了后台', '1499506011');
INSERT INTO `yzn_action_log` VALUES ('153', '1', '1', '2130706433', 'member', '1', 'admin在2017-07-22 15:57登录了后台', '1500710221');
INSERT INTO `yzn_action_log` VALUES ('154', '1', '1', '0', 'member', '1', 'admin在2017-07-23 10:46登录了后台', '1500778019');

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
INSERT INTO `yzn_admin` VALUES ('1', 'admin', '4459f1e16266d94ab6436a6743c838d97e9dca1f', '1', 'Wo0bAa', '御宅男', '1500778019', '0', '530765310@qq.com');
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
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
INSERT INTO `yzn_article` VALUES ('30', '10', 'alt属性和title属性的区别与介绍', '', '', 'alt,属性,title,性的,区别,介绍,alt,属性', 'alt属性    为不能显示图像、窗体或applets的用户代理（UA），alt属性用来指定替换文字。替换文字的语言由lang属性指定。来源：How to...', '/index.php?a=shows&catid=10&id=30', '0', '99', '1', '0', 'admin', '1371907945', '1371907945', '0', '', '', '0', '0', '0', '0', '0', '0');
INSERT INTO `yzn_article` VALUES ('34', '24', 'Digital Atelier创意网站开发工作室网站欣赏', '', 'http://www.yzncms.com/demo/file/2013/06/51c5b06e7462d.jpg', 'Digital Atelier,jquery酷站,国外酷站,交互设计酷站', 'Digital studio focused on web, mobile and facebook development', '/index.php?a=shows&catid=24&id=34', '0', '99', '1', '0', 'admin', '1371910261', '1371910261', '0', '', '', '1', '0', '1', '1', '1', '1435332417');

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
INSERT INTO `yzn_auth_rule` VALUES ('27', 'Content', '1', 'Content/Content/index', '内容管理', '1', '');
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
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=utf8 COMMENT='栏目表';

-- ----------------------------
-- Records of yzn_category
-- ----------------------------
INSERT INTO `yzn_category` VALUES ('1', 'content', '0', '1', '', '0', '0', '1', '1,10,11', '网页教程', '', '网页教程-网页设计基础教程DIV+CSS', '', 'jiaocheng', '/index.php?a=lists&catid=1', '0', 'a:12:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:12:\"category.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";N;}', '1', '1', '0', 'wangyejiaocheng');
INSERT INTO `yzn_category` VALUES ('2', 'content', '0', '1', '', '0', '0', '1', '2,12,13', '前端开发', '', '前端开发-学习最新前端开发技术', '', 'qianduan', '/index.php?a=lists&catid=2', '0', 'a:12:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:12:\"category.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";N;}', '2', '1', '0', 'qianduankaifa');
INSERT INTO `yzn_category` VALUES ('3', 'content', '0', '1', '', '0', '0', '1', '3,14,15', 'PS教程', '', 'PS教程-学习PS技巧,设计更好的页面', '', 'ps', '/index.php?a=lists&catid=3', '0', 'a:12:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:12:\"category.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";N;}', '3', '1', '0', 'psjiaocheng');
INSERT INTO `yzn_category` VALUES ('4', 'content', '0', '1', '', '0', '0', '1', '4,16,17', '网页特效', '', '提供各种网页效果,让你的网页更炫丽', '', 'texiao', '/index.php?a=lists&catid=4', '0', 'a:12:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:17:\"category_heng.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";N;}', '4', '1', '0', 'wangyetexiao');
INSERT INTO `yzn_category` VALUES ('5', 'content', '0', '3', '', '0', '0', '1', '5,18,19', '建站素材', '', '建站素材-提供网页设计必备素材', '', 'sucai', '/index.php?a=lists&catid=5', '0', 'a:12:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:17:\"category_heng.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";N;}', '5', '1', '0', 'jianzhansucai');
INSERT INTO `yzn_category` VALUES ('6', 'content', '0', '1', '', '0', '0', '1', '6,20,21', '网站运营', '', '各种运营知识分享,助站长一臂之力', '', 'yunying', '/index.php?a=lists&catid=6', '0', 'a:12:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:12:\"category.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";N;}', '6', '1', '0', 'wangzhanyunying');
INSERT INTO `yzn_category` VALUES ('7', 'content', '0', '1', '', '0', '0', '1', '7,22,23', '站长杂谈', '', '站长杂谈-了解互联网第一手资讯', '', 'zatan', '/index.php?a=lists&catid=7', '0', 'a:12:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:12:\"category.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";N;}', '7', '1', '0', 'zhanchangzatan');
INSERT INTO `yzn_category` VALUES ('8', 'content', '0', '1', '', '0', '0', '1', '8,24,25', '设计欣赏', '', '设计欣赏-寻找设计灵感提高设计水平', '', 'sheji', '/index.php?a=lists&catid=8', '0', 'a:12:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:17:\"category_template\";s:17:\"category_heng.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";N;}', '8', '1', '0', 'shejixinshang');
INSERT INTO `yzn_category` VALUES ('9', 'content', '0', '2', '', '0', '0', '0', '9', '常用软件', '', '', '', 'software', '/index.php?a=lists&catid=9', '0', 'a:14:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"list_template\";s:17:\"list_software.php\";s:13:\"show_template\";s:17:\"show_download.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:14:\"content_ishtml\";s:1:\"0\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";s:1:\"4\";}', '9', '1', '0', 'changyongruanjian');
INSERT INTO `yzn_category` VALUES ('10', 'content', '0', '1', '', '1', '0,1', '0', '10,30', 'HTML/XHTML', '', '', 'jiaocheng/', 'html', '/index.php?a=lists&catid=10', '0', 'a:14:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"list_template\";s:8:\"list.php\";s:13:\"show_template\";s:8:\"show.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:14:\"content_ishtml\";s:1:\"0\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";s:1:\"4\";}', '10', '1', '0', 'html/xhtml');
INSERT INTO `yzn_category` VALUES ('11', 'content', '0', '1', '', '1', '0,1', '0', '11', 'Dreamweaver', '', '', 'jiaocheng/', 'dw', '/index.php?a=lists&catid=11', '0', 'a:14:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"list_template\";s:8:\"list.php\";s:13:\"show_template\";s:8:\"show.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:14:\"content_ishtml\";s:1:\"0\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";s:1:\"4\";}', '11', '1', '0', 'dreamweaver');
INSERT INTO `yzn_category` VALUES ('12', 'content', '0', '1', '', '2', '0,2', '0', '12', 'javascript教程', '', '', 'qianduan/', 'js', '/index.php?a=lists&catid=12', '0', 'a:14:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"list_template\";s:8:\"list.php\";s:13:\"show_template\";s:8:\"show.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:14:\"content_ishtml\";s:1:\"0\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";s:1:\"4\";}', '12', '1', '0', 'javascriptjiaocheng');
INSERT INTO `yzn_category` VALUES ('13', 'content', '0', '1', '', '2', '0,2', '0', '13', 'jquery教程', '', '', 'qianduan/', 'jq', '/index.php?a=lists&catid=13', '0', 'a:14:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"list_template\";s:8:\"list.php\";s:13:\"show_template\";s:8:\"show.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:14:\"content_ishtml\";s:1:\"0\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";s:1:\"4\";}', '13', '1', '0', 'jqueryjiaocheng');
INSERT INTO `yzn_category` VALUES ('14', 'content', '0', '1', '', '3', '0,3', '0', '14', 'PS网页制作', '', '', 'ps/', 'psweb', '/index.php?a=lists&catid=14', '0', 'a:14:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"list_template\";s:8:\"list.php\";s:13:\"show_template\";s:8:\"show.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:14:\"content_ishtml\";s:1:\"0\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";s:1:\"4\";}', '14', '1', '0', 'pswangyezhizuo');
INSERT INTO `yzn_category` VALUES ('15', 'content', '0', '1', '', '3', '0,3', '0', '15', 'PS按钮制作', '', '', 'ps/', 'psbtn', '/index.php?a=lists&catid=15', '0', 'a:14:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"list_template\";s:8:\"list.php\";s:13:\"show_template\";s:8:\"show.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:14:\"content_ishtml\";s:1:\"0\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";s:1:\"4\";}', '15', '1', '0', 'psanniuzhizuo');
INSERT INTO `yzn_category` VALUES ('16', 'content', '0', '1', '', '4', '0,4', '0', '16', 'JS幻灯片', '', '', 'texiao/', 'adjs', '/index.php?a=lists&catid=16', '0', 'a:14:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"list_template\";s:8:\"list.php\";s:13:\"show_template\";s:8:\"show.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:14:\"content_ishtml\";s:1:\"0\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";s:1:\"4\";}', '16', '1', '0', 'jshuandengpian');
INSERT INTO `yzn_category` VALUES ('17', 'content', '0', '1', '', '4', '0,4', '0', '17', '导航菜单', '', '', 'texiao/', 'nav', '/index.php?a=lists&catid=17', '0', 'a:14:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"list_template\";s:8:\"list.php\";s:13:\"show_template\";s:8:\"show.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:14:\"content_ishtml\";s:1:\"0\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";s:1:\"4\";}', '17', '1', '0', 'daohangcaidan');
INSERT INTO `yzn_category` VALUES ('18', 'content', '0', '3', '', '5', '0,5', '0', '18', 'PNG图标', '', '', 'sucai/', 'png', '/index.php?a=lists&catid=18', '0', 'a:14:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"list_template\";s:8:\"list.php\";s:13:\"show_template\";s:14:\"show_image.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:14:\"content_ishtml\";s:1:\"0\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";s:1:\"4\";}', '18', '1', '0', 'pngtubiao');
INSERT INTO `yzn_category` VALUES ('19', 'content', '0', '3', '', '5', '0,5', '0', '19', 'GIF小图标', '', '', 'sucai/', 'gif', '/index.php?a=lists&catid=19', '0', 'a:14:{s:6:\"seturl\";s:0:\"\";s:12:\"generatehtml\";s:1:\"1\";s:12:\"generatelish\";s:1:\"0\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";s:13:\"list_template\";s:8:\"list.php\";s:13:\"show_template\";s:14:\"show_image.php\";s:19:\"list_customtemplate\";s:0:\"\";s:6:\"ishtml\";s:1:\"0\";s:9:\"repagenum\";s:2:\"10\";s:14:\"content_ishtml\";s:1:\"0\";s:15:\"category_ruleid\";s:1:\"1\";s:11:\"show_ruleid\";s:1:\"4\";}', '19', '1', '0', 'gifxiaotubiao');
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
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=utf8 COMMENT='后台菜单表';

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
INSERT INTO `yzn_menu` VALUES ('37', '模型管理', '35', 'Content', 'Models', 'index', '', '1', '', '0', '0');

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
  PRIMARY KEY (`fieldid`),
  KEY `modelid` (`modelid`,`disabled`),
  KEY `field` (`field`,`modelid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='模型字段列表';

-- ----------------------------
-- Records of yzn_model_field
-- ----------------------------
