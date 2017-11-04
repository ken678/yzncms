/*
Navicat MySQL Data Transfer

Source Server         : 本地链接
Source Server Version : 50547
Source Host           : localhost:3306
Source Database       : yzncms

Target Server Type    : MYSQL
Target Server Version : 50547
File Encoding         : 65001

Date: 2017-10-28 17:18:33
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
INSERT INTO `yzn_action` VALUES ('1', 'user_login', '用户登录', '积分+10，每天一次', 'table:member|field:score|condition:uid={$self} AND status>-1|rule:score+10|cycle:24|max:1;', '[user]在[time|time_format]登录了后台', '1', '1', '1387181220');
INSERT INTO `yzn_action` VALUES ('2', 'add_article', '发布文章', '积分+5，每天上限5次', 'table:member|field:score|condition:uid={$self}|rule:score+5|cycle:24|max:5', '', '2', '0', '1380173180');
INSERT INTO `yzn_action` VALUES ('3', 'review', '评论', '评论积分+1，无限制', 'table:member|field:score|condition:uid={$self}|rule:score+1', '', '2', '1', '1383285646');
INSERT INTO `yzn_action` VALUES ('4', 'add_document', '发表文档', '积分+10，每天上限1次', 'table:member|field:score|condition:uid={$self}|rule:score+10|cycle:24|max:1', '[user]在[time|time_format]发表了一篇文章。\n表[model]，记录编号[record]。', '1', '1', '1493877089');
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
) ENGINE=MyISAM AUTO_INCREMENT=320 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='行为日志表';

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
INSERT INTO `yzn_action_log` VALUES ('301', '1', '1', '2130706433', 'member', '1', 'admin在2017-10-04 17:00登录了后台', '1507107607');
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
INSERT INTO `yzn_action_log` VALUES ('290', '1', '1', '2130706433', 'member', '1', 'admin在2017-09-21 19:16登录了后台', '1505992604');
INSERT INTO `yzn_action_log` VALUES ('291', '1', '1', '2130706433', 'member', '1', 'admin在2017-09-22 19:49登录了后台', '1506080965');
INSERT INTO `yzn_action_log` VALUES ('292', '1', '1', '2130706433', 'member', '1', 'admin在2017-09-23 11:35登录了后台', '1506137737');
INSERT INTO `yzn_action_log` VALUES ('293', '1', '1', '2130706433', 'member', '1', 'admin在2017-09-24 17:18登录了后台', '1506244687');
INSERT INTO `yzn_action_log` VALUES ('294', '1', '1', '2130706433', 'member', '1', 'admin在2017-09-26 21:18登录了后台', '1506431907');
INSERT INTO `yzn_action_log` VALUES ('295', '1', '1', '2130706433', 'member', '1', 'admin在2017-09-27 20:19登录了后台', '1506514780');
INSERT INTO `yzn_action_log` VALUES ('296', '1', '1', '2130706433', 'member', '1', 'admin在2017-10-02 10:12登录了后台', '1506910354');
INSERT INTO `yzn_action_log` VALUES ('297', '1', '1', '0', 'member', '1', 'admin在2017-10-02 11:43登录了后台', '1506915795');
INSERT INTO `yzn_action_log` VALUES ('298', '1', '1', '2130706433', 'member', '1', 'admin在2017-10-02 18:45登录了后台', '1506941143');
INSERT INTO `yzn_action_log` VALUES ('299', '1', '1', '2130706433', 'member', '1', 'admin在2017-10-03 17:27登录了后台', '1507022869');
INSERT INTO `yzn_action_log` VALUES ('300', '1', '1', '2130706433', 'member', '1', 'admin在2017-10-04 11:49登录了后台', '1507088992');
INSERT INTO `yzn_action_log` VALUES ('302', '1', '1', '2130706433', 'member', '1', 'admin在2017-10-05 10:23登录了后台', '1507170205');
INSERT INTO `yzn_action_log` VALUES ('303', '1', '1', '2130706433', 'member', '1', 'admin在2017-10-05 18:09登录了后台', '1507198154');
INSERT INTO `yzn_action_log` VALUES ('304', '1', '1', '2130706433', 'member', '1', 'admin在2017-10-06 10:14登录了后台', '1507256046');
INSERT INTO `yzn_action_log` VALUES ('305', '1', '1', '2130706433', 'member', '1', 'admin在2017-10-06 17:11登录了后台', '1507281105');
INSERT INTO `yzn_action_log` VALUES ('306', '1', '1', '2130706433', 'member', '1', 'admin在2017-10-07 19:30登录了后台', '1507375826');
INSERT INTO `yzn_action_log` VALUES ('307', '1', '1', '2130706433', 'member', '1', 'admin在2017-10-13 18:52登录了后台', '1507891938');
INSERT INTO `yzn_action_log` VALUES ('308', '1', '1', '2130706433', 'member', '1', 'admin在2017-10-14 11:30登录了后台', '1507951840');
INSERT INTO `yzn_action_log` VALUES ('309', '1', '1', '2130706433', 'member', '1', 'admin在2017-10-14 13:54登录了后台', '1507960460');
INSERT INTO `yzn_action_log` VALUES ('310', '1', '1', '2130706433', 'member', '1', 'admin在2017-10-14 17:06登录了后台', '1507971984');
INSERT INTO `yzn_action_log` VALUES ('311', '1', '1', '2130706433', 'member', '1', 'admin在2017-10-14 18:37登录了后台', '1507977452');
INSERT INTO `yzn_action_log` VALUES ('312', '1', '1', '2130706433', 'member', '1', 'admin在2017-10-15 10:52登录了后台', '1508035979');
INSERT INTO `yzn_action_log` VALUES ('313', '1', '1', '2130706433', 'member', '1', 'admin在2017-10-15 20:24登录了后台', '1508070293');
INSERT INTO `yzn_action_log` VALUES ('314', '1', '1', '2130706433', 'member', '1', 'admin在2017-10-20 19:18登录了后台', '1508498297');
INSERT INTO `yzn_action_log` VALUES ('315', '1', '1', '2130706433', 'member', '1', 'admin在2017-10-21 11:39登录了后台', '1508557186');
INSERT INTO `yzn_action_log` VALUES ('316', '1', '1', '2130706433', 'member', '1', 'admin在2017-10-21 20:45登录了后台', '1508589942');
INSERT INTO `yzn_action_log` VALUES ('317', '1', '1', '2130706433', 'member', '1', 'admin在2017-10-21 23:10登录了后台', '1508598648');
INSERT INTO `yzn_action_log` VALUES ('318', '1', '1', '2130706433', 'member', '1', 'admin在2017-10-22 21:24登录了后台', '1508678667');
INSERT INTO `yzn_action_log` VALUES ('319', '1', '1', '2130706433', 'member', '1', 'admin在2017-10-28 14:09登录了后台', '1509170997');

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
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='插件表';

-- ----------------------------
-- Records of yzn_addons
-- ----------------------------
INSERT INTO `yzn_addons` VALUES ('10', 'returntop', '返回顶部', '回到顶部美化，随机或指定显示，100款样式，每天一种换，天天都用新样式', '1', '{\"random\":\"0\",\"current\":\"94\"}', '御宅男', '1.0.0', '1508592233', '0');

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
INSERT INTO `yzn_admin` VALUES ('1', 'admin', '4459f1e16266d94ab6436a6743c838d97e9dca1f', '1', 'Wo0bAa', '御宅男', '1509170997', '2130706433', '530765310@qq.com');
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
  `typeid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `style` varchar(24) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `thumb` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `keywords` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `tags` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `posid` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `listorder` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `sysadd` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `islink` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `username` char(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `inputtime` int(10) unsigned NOT NULL DEFAULT '0',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status` (`status`,`listorder`,`id`),
  KEY `listorder` (`catid`,`status`,`listorder`,`id`),
  KEY `catid` (`catid`,`status`,`id`),
  KEY `thumb` (`thumb`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of yzn_article
-- ----------------------------
INSERT INTO `yzn_article` VALUES ('1', '2', '0', '为了排名你的网站设置正确吗？', '', '', '', '', '你的网站设置正确吗?1. 域名设置 (Domain Name System - DNS)一个设置正确的网站, 一般输入 ccxcn.com 或 www.itccx.com 都可以到达网站, 但是北京有部份网站, 要输入 www.itccx.com 才能找到网址. 如果你的网站有这样的情况, 你可利用你的网站控制台略为改动你的 DNS 置定:i. 为 ccxcn.com 加入 A record 指向你的网站IP 地址ii. 为 www 加入 cname record 指向 ccxcn.com那便完成设定了.', '0', '/home/index/shows/catid/2/id/1', '0', '99', '1', '0', '', '1506251727', '1506251727');
INSERT INTO `yzn_article` VALUES ('2', '2', '0', '响应式网站和手机网站的差别。', '', '', '', '', '手机版网站是保持原本的电脑版网站不变、另外做一个专门给手机用的小尺寸网站，网站程式会自动导入电脑版或手机版网页。以苹果日报为例● 电脑版网站：网址为「http://www.ccxcn.com/」● 手机版网站：网址为「为「http://m.ccxcn.com/」在电脑上：因此手机版网站必须用手机才能看出原本的样貌，在电脑上就会「走钟」。没有经过精心规划的手机版网站，很常出现以下问题：1. 自动导向导至错误页面2. 与电脑版网站视觉不同，品牌没有...', '0', '/home/index/shows/catid/2/id/2', '0', '99', '1', '0', '', '1506252160', '1506252160');
INSERT INTO `yzn_article` VALUES ('3', '2', '0', '网站首页首页的欢迎词及副标的注意点。', '', '', '', '', '当您涉及建设网站的工作时，您自然知道 自己需要在网站上提供些什么，也知道这些东西有多棒，但是请记住，对别人来说 这可未必理所当然。只有极少数人会因为看到网站上每次都出 现相同的解释文字而离开一除非这些文字 占了整整半页。想想看：即使您知道 JAMA是什么，您会觉得在logo标志底下看至字说明「Journal of the American Medical Association」很惹人厌吗？您很容易以为无法立即了解您网站的人，都不是网站的诉求对...', '0', '/home/index/shows/catid/2/id/3', '0', '99', '1', '0', '', '1506252201', '1506252201');
INSERT INTO `yzn_article` VALUES ('4', '2', '0', '论建设网站中网站面包屑的用处及原理。', '', '', '', '', '当我在网站阶层结构中感觉迷路时，网站的导航可以告诉我身在何处，作法就像是购物中心或者国家公园地图上的「你在这里」标示一样。在网站上，通常是将我的所在位置于导航列、导航清单或者页面选单上标示出来。「你在这里」标示最常见的错误，就是标示无法引起注意。标示必须能够引起注意；如果无法引起注意，那就会丧失其视觉线索上的价值，成为网站上的干扰。为了让标示能够引起注意，可以同时套用一种以上的视觉差异效果一例如，...', '0', '/home/index/shows/catid/2/id/4', '0', '99', '1', '0', '', '1506252238', '1506252238');
INSERT INTO `yzn_article` VALUES ('5', '2', '0', '如何加快网站建设的进度？', '', '', '', '', '在网站建设中我们都会对每一个用户的网站规划一下建站的进度管理，并把每一个建站的进程分解成不同的阶段，然后把每一个建站阶段所需要的时间都列出来，并写入网站建设的协议中，以便让用户明白我们在建站的整个进度中各个阶段都是在做什么。通常来说，我们规划网站建设进度的时候，主要考虑三方面的因素：第一是用户对网站建设要求的急切程度;第二是要根据网站的复杂程度及其难度;第三就是我们技术人员的人手分配因素。这三个因素...', '1', '/home/index/shows/catid/2/id/5', '0', '99', '1', '0', '', '1506252329', '1506252329');
INSERT INTO `yzn_article` VALUES ('6', '3', '0', '网站建设必备 搜索引擎收录提交入口一览', '', '', '', '', '索引擎收录提交入口一览网站建设完成之后，第一件事情就是向各大搜索引擎提交新网站。搜索引擎提交包括提交给搜索引擎爬虫和提交给分类目录。提交给搜索引擎爬虫的目的是让搜索引擎将网站收录到索引数据库。 检验网站是否被搜索引擎收录的办法是直接在搜索引擎中搜索网址，查看能否找到网站结果，也可以通过输入命令site:yoursite获得具体的页面收录数量。中文网站提交给搜索引擎爬虫和分类目录Google：http://www.Google.com/in...', '0', '/home/index/shows/catid/3/id/6', '0', '99', '1', '0', '', '1506252499', '1506252499');
INSERT INTO `yzn_article` VALUES ('7', '3', '0', '站群对于seo优化的先天优势是毋庸置疑', '', '', '', '', '我们先来了解一下什么是站群，所谓站群就是一个网站的集合，站群特点是信息共享、分级管理、单点登录等，我们可以看到有很多国内和国际的大型的网站都用了站群的形式来运营它们的网站。比如太平洋站群系统，新浪站群系统，最简单的站群就是以顶级域名为核心，根据业务和发展制定相关的二级域名，二级域在高度集中在以顶级域为核心的网页上，我们称之为首页。站群有很多种，有通过正规手段做的，也有通过非正规手段做的，比如说泛站...', '0', '/home/index/shows/catid/3/id/7', '0', '99', '1', '0', '', '1506252532', '1506252532');
INSERT INTO `yzn_article` VALUES ('8', '3', '0', '网络营销需要花多少钱？', '', '', '', '', '这是个网络的时代，中国更是个微博或者微信之岛（对，你出去外面看到人家吃饭划手机，十个有八个都是在上微博或者微信）。微博或者微信很好用，只需要一块钱就可以推播你的广告/产品。当然，一块钱能推播的，顶多只有几十人，效益自然不大。但这一块钱告诉我们的，是：最小可以用1元开始做。这点差别很大。早年网络营销的主媒体是电视、广播、报章杂志，这些广告要有效，动不动就是五万十万。尔后网络兴起，想要在入口网站刊个首页...', '0', '/home/index/shows/catid/3/id/8', '0', '99', '1', '0', '', '1506252561', '1506252561');
INSERT INTO `yzn_article` VALUES ('9', '3', '0', '交换友情链接可以达到什么效果？', '', '', '', '', '参考1：参考PR值一般我们会在交换链路时看到对方，有多少流量，实际上流量并不重要，这是重要的PR值，只有站点的高PR值才能有效地提高站点链路的链路价值。参考2：选择链接站的类别在交换链接时需要有选择的交流，只有那些与网站类型相同，内容类似的网站是最好的选择。这有利于搜索引擎和包括。参考三：注重质量友情链条不是更好，只有有效的链接是有用的，所以一定要好。如果一个网站的PR达到了5，但是网站有数百个链接，...', '0', '/home/index/shows/catid/3/id/9', '0', '99', '1', '0', '', '1506252580', '1506252580');
INSERT INTO `yzn_article` VALUES ('10', '3', '0', '关于网站排名的那些事！', '', '', '', '', '那么影响权重的因素有哪些？1，域名类型一般而言，gov和edu类型的域名权重自身就比较高，因为这样的站点属于政府高校一般不会成为垃圾站点，不会是草根个人小站。其次，com、net、org的域名权重相对较高，一些有国家和地区特点的域名后缀建议不要选。从用户习惯来看，com已成为首选。最后域名的注册年龄越久，搜索引擎给予的信任越高。2，网站架构网站架构不仅要迎合搜索引擎，更要符合用户体验的要求。优质的网站架构应是扁平式。...', '0', '/home/index/shows/catid/3/id/10', '0', '99', '1', '0', '', '1506252617', '1506252617');
INSERT INTO `yzn_article` VALUES ('11', '4', '0', '我的网站只有独立的IP地址，没有域名需要办理网上备案手续吗？', '', '', '', '', '我的网站只有独立的IP地址，没有域名需要办理网上备案手续吗？需要。无论您的网站是通过域名方式访问或是通过IP地址的方式访问，只要在中华人民共和国境内提供非经营性互联网信息服务都要办理备案手续。如果一个备案单位同时具有两个网站，可以将两个网站分别备案在两个相同的主体下吗？不可以，如果两个网站的备案主体都是同一个备案单位，那么只能将两个网站备案在同一个主体下。', '0', '/home/index/shows/catid/4/id/11', '0', '99', '1', '0', '', '1506252688', '1506252688');
INSERT INTO `yzn_article` VALUES ('12', '4', '0', '网站涉及哪些信息内容应办理前置审批手续？', '', '', '', '', '根据《互联网信息服务管理办法》（国务院292号令）第5条等有关规定，拟从事新闻、出版、教育、医疗保健、药品和医疗器械、文化、广播电影电视节目等互联网信息服务，依照法律、行政法规以及国家有关规定应经有关主管部门审核同意的，在履行备案手续时，还应向其住所所在地省通信管理局提交相关主管部门审核同意的文件。', '0', '/home/index/shows/catid/4/id/12', '0', '99', '1', '0', '', '1506252719', '1506252719');
INSERT INTO `yzn_article` VALUES ('13', '4', '0', '在新、旧备案系统中网站备案的流程是否有差别？', '', '', '', '', '升级后的网站备案管理系统实现了工业和信息化部、各通信管理局、接入服务企业三级备案管理服务模式。在原网站备案管理系统的服务功能基础上，增加了通信管理局级和接入服务企业级网站备案管理系统。网站主办者仅需向接入服务企业提交备案申请，接入服务企业核验后将备案信息提交至通信管理局备案系统，通信管理局进行审核，审核通过后生成备案号发给网站主办者和接入服务企业。', '0', '/home/index/shows/catid/4/id/13', '0', '99', '1', '0', '', '1506252735', '1506252735');
INSERT INTO `yzn_article` VALUES ('14', '4', '0', '网站ICP备案密码忘记了怎么办', '', '', '', '', '方法一：您可以通过备案系统找回。登陆www.miitbeian.gov.cn，在右下角有“找回备案密码”按钮，选择主体所在省，在跳出的网页中，输入“备案/许可证号、证件类型、证件号码”，输入完成后点提交。如果信息填写正确，系统会向您当年注册的E-mail发送新备案密码。方法二：如果您的备案信息是接入商代为备案的，您可以联系代为备案的接入商告诉您如何找回备案密码。方法三：您也可以通过联系备案号发放地通信管理局，并按要求提供相...', '0', '/home/index/shows/catid/4/id/14', '0', '99', '1', '0', '', '1506252754', '1506252754');
INSERT INTO `yzn_article` VALUES ('15', '4', '0', '什么是经营性的互联网信息服务？什么是非经营性互联网信息服务？', '', '', '', '', '根据《国务院互联网信息服务管理办法》（国务院292号令）的第三条规定，“经营性互联网信息服务”是指通过互联网向上网用户有偿提供信息或者网页制作等服务活动。“非经营性互联网信息服务”是指通过互联网向上网用户无偿提供具有公开性、共享性信息的服务活动。', '0', '/home/index/shows/catid/4/id/15', '0', '99', '1', '0', '', '1506252770', '1506252770');

-- ----------------------------
-- Table structure for `yzn_article_data`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_article_data`;
CREATE TABLE `yzn_article_data` (
  `id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `content` text COLLATE utf8_unicode_ci,
  `paginationtype` tinyint(1) NOT NULL DEFAULT '0',
  `maxcharperpage` mediumint(6) NOT NULL DEFAULT '0',
  `template` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `paytype` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `allow_comment` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `relation` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of yzn_article_data
-- ----------------------------
INSERT INTO `yzn_article_data` VALUES ('1', '<p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\">你的网站设置正确吗?</p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\">1. 域名设置 (Domain Name System - DNS)</p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\">一个设置正确的网站, 一般输入 ccxcn.com 或 www.itccx.com 都可以到达网站, 但是北京有部份网站, 要输入 www.itccx.com 才能找到网址. 如果你的网站有这样的情况, 你可利用你的网站控制台略为改动你的 DNS 置定:</p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\">i. 为 ccxcn.com 加入 A record 指向你的网站IP 地址</p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\">ii. 为 www 加入 cname record 指向 ccxcn.com</p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\">那便完成设定了.</p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\">2. 找不到网页的处理</p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\">有时你的网站访客或会打错页名, 或因网站内容改变被连接到不存在的网页, 如果没有正确设定, 访客会看到404 错误找不到网页, 或被引导至一些搜寻器, 那么, 你有可能损失了一位访客了, 最理想的做法会是告?你的访客找不到网页, 并引领他到你的网站地图, 让他们寻找所需资料.</p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\">要知道你的网站对404 错误的处理, 你只要到你的网站打一个不存在的页名, 例如: www.ccxcn.com/nopage</p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\">这个设定要改动 .htaccess , 下节再详谈.</p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\">3. Metatag 的设定</p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\">Metatag 是一些给搜寻器看的资料, 如 keyword 及 description 等, 就是用来告诉搜寻器你的网页是关于什么内容的重要资料, 它是搜寻器用来决定你的网站是否有搜寻者所需之内容的其中一项资料.</p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\">要知道你有没有设定keyword 及 description 等 Metatags, 你可在你的网页空白位置按滑鼠右键, 再选 view source 查看在&lt; /head&gt; 之间有没有及等设定. 要从搜寻器中找到你的存在, 那就快点改动一下吧!</p><p><br/></p>', '0', '0', '', '0', '1', '');
INSERT INTO `yzn_article_data` VALUES ('2', '<p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">手机版网站是保持原本的电脑版网站不变、另外做一个专门给手机用的小尺寸网站，网站程式会自动导入电脑版或手机版网页。&nbsp;</p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">以苹果日报为例</p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">● 电脑版网站：网址为「http://www.ccxcn.com/」</p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">● 手机版网站：网址为「为「<a href=\"http://m.ccxcn.com/%E3%80%8D\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out;\">http://m.ccxcn.com/」</a><br/><br/></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">在电脑上：</p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">因此手机版网站必须用手机才能看出原本的样貌，在电脑上就会「走钟」。</p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">没有经过精心规划的手机版网站，很常出现以下问题：</p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">1. 自动导向导至错误页面</p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">2. 与电脑版网站视觉不同，品牌没有一致性</p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">3. 网址不相同、与电脑版动线不同，使用者不易寻找资料</p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">4. 维护资料必须花两倍人工<br/><br/></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">在响应式网站崛起后，手机版网站仍有一定市场，适合使用手机版网站的客户包括：</p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">1. 网站资讯量很大，需要删去不必要的元件才能便利阅读的平台式网站，如百度</p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">2. 适合点击多次才能浏览所需资料、分类多层的大型资料网站，如MOMO购物网</p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">3. 只针对手机用户的活动网站</p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">4. 电脑版、手机版网站的浏览重点、阅读动线不同，需要针对手机用户另外设计不同的网站浏览动线</p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">5. 电脑版有特殊原因要保留Flash动画、横式卷轴等效果</p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\">&nbsp;</p><p><span style=\"color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; background-color: rgb(255, 255, 255);\">有9成以上的网站，像旅宿观光、形象购物、企业型录、学校系所机关的网站都不需要考量上述因素，他们遇到的问题是「没有充沛的人力及预算」来同时建置及营运电脑版跟手机版两个网站，因此对于大部份的网站来说，响应式网站仍是首选。</span></p>', '0', '0', '', '0', '1', '');
INSERT INTO `yzn_article_data` VALUES ('3', '<p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\">当您涉及<a href=\"http://www.ccxcn.com/\" target=\"_blank\" title=\"网站建设公司-北京传诚信ccxcn.com\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out;\"><strong style=\"box-sizing: border-box; color: rgb(0, 0, 0);\">建设网站</strong></a>的工作时，您自然知道 自己需要在网站上提供些什么，也知道这些东西有多棒，但是请记住，对别人来说 这可未必理所当然。</p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\">只有极少数人会因为看到网站上每次都出 现相同的解释文字而离开一除非这些文字 占了整整半页。想想看：即使您知道 JAMA是什么，您会觉得在logo标志底下看至字说明「Journal of the American Medical Association」很惹人厌吗？</p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\">您很容易以为无法立即了解您网站的人，都不是网站的诉求对象，这并非事实。测试网站时，仍不时会听到「这到底是什么？我经常使用，但仍搞不清楚。」</p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\">即使大家看了您的电视、广播与印刷品广吿，等到他们上了网站时，还会记得当 初引起他们兴趣的是什么吗？</p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\">如果网站很复杂或很新奇，那首页上放个 明显的「第一次到本站？」连结会很有帮 助。但这无助于在一眼之间传达出网站愿 景，因为大部分的人在自己尝试猜测一而 且失败之前，都不会去点这个连结。等到 他们想到要去点的时候，想必已经是绝望困惑°</p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\">首页上的每一样东西，都可以让我们更暸解网站究竟是什么。但网页上有两个地方，我们会期望能看到关于网站本身的说明。<br/><br/></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><strong style=\"box-sizing: border-box; color: rgb(0, 0, 0);\">网站首页的副标。</strong></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\">对于房产来说，最有价值的部分是建筑名称旁边的空间。当我们看 到与名称放在一起的词句时，我们就知道这是副标，所以会将其解读成整 个网站的说明。</p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\">欢迎词是网站的简要说明，显示在首页上不用卷动即可看见的明显区域。</p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><br/></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><strong style=\"box-sizing: border-box; color: rgb(0, 0, 0);\">网站首页的欢迎词。</strong></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\">重点在于，并非人人都会使用这两项元素一甚至有些人根本不会注意到。大 部分使用者可能会从首页上的整体内容猜测网站。但如果他们猜不到，您仍 应在首页的某个地方让他们能够找出答案。</p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\">另外还有第三个可能性：您可以使用页面上方、网站名称右边的整块区域达 此目的。但是如果这样做，您务必让视觉效果明确表达’让访客知道这里是 网站名称的补充说明，不是广告栏位，因为使用者预期在这个地方看到广告，容易忽略掉。</p><p><br/></p>', '0', '0', '', '0', '1', '');
INSERT INTO `yzn_article_data` VALUES ('4', '<p style=\"box-sizing: border-box; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 28px;\"><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\"><span style=\"box-sizing: border-box;\">当我在网站阶层结构中感觉迷路时，网站的导航可以告诉我身在何处，作法就像</span>&nbsp;是购物中心或者国家公园地图上的「你在这里」标示一样。</span></p><p style=\"box-sizing: border-box; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 28px;\"><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\"><span style=\"box-sizing: border-box;\">在网站上，通常是将我的所在位置于导航列、导航清单或者页面选单上标示</span>&nbsp;出来。</span></p><p style=\"box-sizing: border-box; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 28px;\"><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\"><br/></span></p><p style=\"box-sizing: border-box; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 28px;\"><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\"><span style=\"box-sizing: border-box;\">「你在这里」标示最常见的错误，就是标示无法引起注意。标示必须能够引起</span>&nbsp;注意；如果无法引起注意，那就会丧失其视觉线索上的价值，成为网站上的&nbsp;干扰。为了让标示能够引起注意，可以同时套用一种以上的视觉差异效果一&nbsp;例如，使用不同的颜色与粗体字。</span></p><p style=\"box-sizing: border-box; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 28px;\"><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\"><br/></span></p><p style=\"box-sizing: border-box; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 28px;\"><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\"><span style=\"box-sizing: border-box;\">无法引起注意的视觉效果，是个极常见的问题。设计师喜欢用细微的喑示，</span>&nbsp;因为这种微不足道的手法，正是精致设计的特征之一。但是网站使者通常都&nbsp;很赶时间，所以他们经常会忽略这些细微的效果。</span></p><p style=\"box-sizing: border-box; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 28px;\"><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\"><br/></span></p><p style=\"box-sizing: border-box; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 28px;\"><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\">一般来说，如果您是个</span><a href=\"http://www.ccxcn.com/\" target=\"_blank\" title=\"网站设计\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out;\"><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\">网站</span><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\"><span style=\"box-sizing: border-box;\">设计</span></span></a><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\"><span style=\"box-sizing: border-box;\">师，而您认为某个视觉效果巳经够显著了，那您</span>&nbsp;最好还是将它做得加倍明显。</span></p><p style=\"box-sizing: border-box; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 28px;\"><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\"><br/></span></p><p style=\"box-sizing: border-box; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 28px;\"><strong style=\"box-sizing: border-box; color: rgb(0, 0, 0);\"><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\">网站面包屑</span></strong></p><p style=\"box-sizing: border-box; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 28px;\"><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\">标示一样，</span><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\">网站面包屑</span><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\"><span style=\"box-sizing: border-box;\">会显示出您所在位置。（有时候甚会加</span>&nbsp;上「你在这里」字样。）</span></p><p style=\"box-sizing: border-box; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 28px;\"><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\">之所以会叫做「</span><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\">网站面包屑</span><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\"><span style=\"box-sizing: border-box;\">」，因为他们就像汉斯扔在森林中，为自己和妹妹指引</span>&nbsp;回家的路”。</span></p><p style=\"box-sizing: border-box; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 28px;\"><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\"><span style=\"box-sizing: border-box;\">在原始的故事中，两兄妹的继母要他们的父亲将兄妹带去森林抛弃，以免因</span>&nbsp;<span style=\"box-sizing: border-box;\">为粮食缺乏而让大家一起饿死。两兄妹一路丢下鹅卵石作为路标，藉以回到</span>&nbsp;<span style=\"box-sizing: border-box;\">家中。但是下一次汉斯就只能用比较不显眼的</span></span><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\">网站面包屑</span><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\">，而</span><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\">网站面包屑</span><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\"><span style=\"box-sizing: border-box;\">被鸟吃光之</span>&nbsp;<span style=\"box-sizing: border-box;\">后，两兄妹就找不到回家的路了。这个故事虽然后来演变成食人恶行、非法</span><span style=\"box-sizing: border-box;\">侵占以及自焚等悲剧，但基本上这个故事主要在描述迷路的感觉有多糟糕。</span></span></p><p style=\"box-sizing: border-box; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 28px;\"><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\"><span style=\"box-sizing: border-box;\"><br/></span></span></p><p style=\"box-sizing: border-box; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 28px;\"><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\">「你在这里」标示会告诉您身在网站阶层结构中的何处，而</span><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\">网站面包屑</span><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\"><span style=\"box-sizing: border-box;\">只会告诉您</span>&nbsp;从目前位置回到首页的路。（前者告诉您在整体结构中您的所在位置，后者 告诉您要怎样回到顶层一就像是地图与路线图的差异。路线图比较简单易 用，但是完整的地图可以告诉您更多资讯。）</span></p><p style=\"box-sizing: border-box; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 28px;\"><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\">您可能会认为，书签比较接近童话中的</span><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\">网站面包屑</span><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\"><span style=\"box-sizing: border-box;\">，因为我们在行走时随手洒</span>&nbsp;下，也许将来需要一步一步往回走。或者您也会说，巳造访连结（连结会改&nbsp;变颜色，表示您先前曾经点选过）比较像</span><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\">网站面包屑</span><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\"><span style=\"box-sizing: border-box;\">，因为他们会标示出我们曾</span>&nbsp;走过的路线，如果我们不尽快回头逛逛，浏览器（就像小鸟一样）会将这些&nbsp;标记吃光光。</span></p><p style=\"box-sizing: border-box; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 28px;\"><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\"><br/></span></p><p style=\"box-sizing: border-box; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 28px;\"><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\">长久以来，「</span><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\">网站面包屑</span><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\"><span style=\"box-sizing: border-box;\">」一直不被广泛使整有在资料库庞大、阶层结构极深的</span>&nbsp;网站才会用到。</span></p><p style=\"box-sizing: border-box; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 28px;\"><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\">在这些网站上，「</span><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\">网站面包屑</span><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\"><span style=\"box-sizing: border-box;\">」可以让使用者知道自己在整体架构中身处何处</span>&nbsp;又让其下的子站台可以保留其独立一且经常不相容的导览系统。</span></p><p style=\"box-sizing: border-box; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 28px;\"><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\">但是近年来，越来越多的网站开始使用</span><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\">网站面包屑</span><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\">&nbsp;览列并用。</span></p><p style=\"box-sizing: border-box; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 28px;\"><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\">对大部分网站而言，我不认为「</span><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\">网站面包屑</span><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\">」应该单独当作导览系统。「</span><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\">网站面包屑</span><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\"><span style=\"box-sizing: border-box;\">」</span>&nbsp;不适合用来作显示阶层结构至少前两层的替代品，因为「</span><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\">网站面包屑</span><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\"><span style=\"box-sizing: border-box;\">」所显示的</span>&nbsp;资讯并不足。您可以看到大致状况，但是能看到的部分有限。这并不是说您&nbsp;无法善用「</span><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\">网站面包屑</span><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\">」达到功能，而是「</span><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\">网站面包屑</span><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\">」并不适合表现大部分网站。</span></p><p style=\"box-sizing: border-box; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 28px;\"><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\"><br/></span></p><p style=\"box-sizing: border-box; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 28px;\"><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\">别误会。如果应用得宜，「</span><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\">网站面包屑</span><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\"><span style=\"box-sizing: border-box;\">」本身可以自</span>.圆其说，不但占用空间小， 而且提供了一种便利、一致的方式，让您做到两种经常需要完成的工作：回到上一层，或者回到首页。</span></p><p style=\"box-sizing: border-box; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 28px;\"><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\"><br/></span></p><p style=\"box-sizing: border-box; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 28px;\"><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\">只是我认为「</span><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\">网站面包屑</span><span style=\"box-sizing: border-box; font-family: 微软雅黑; letter-spacing: 0px; font-size: 16px;\"><span style=\"box-sizing: border-box;\">」在当作网站减肥套餐的一部份、营造出完善的导览系</span>&nbsp;统时效果最好，尤其在阶层结构较深的大型站台上，或者您需要将多个子站&nbsp;台整合时</span></p><p><br/></p>', '0', '0', '', '0', '1', '');
INSERT INTO `yzn_article_data` VALUES ('5', '<p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><span style=\"box-sizing: border-box;\">在<a href=\"http://www.ccxcn.com/knowledge/20140509/091858.shtml\" target=\"_blank\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out;\">网站建设</a>中我们都会对每一个用户的网站规划一下建站的进度管理，并把每一个建站的进程分解成不同的阶段，然后把每一个建站阶段所需要的时间都列出来，并写入<a href=\"https://www.ccxcn.com/\" target=\"_blank\" title=\"网站建设\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out;\">网站建设</a>的协议中，以便让用户明白我们在建站的整个进度中各个阶段都是在做什么。&nbsp;</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><span style=\"box-sizing: border-box;\">通常来说，我们规划<a href=\"https://www.ccxcn.com/\" target=\"_blank\" title=\"网站建设\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out;\">网站建设</a>进度的时候，主要考虑三方面的因素：</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><span style=\"box-sizing: border-box;\">第一是用户对<a href=\"http://www.ccxcn.com/\" target=\"_blank\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out;\">网站建设</a>要求的急切程度;</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><span style=\"box-sizing: border-box;\">第二是要根据网站的复杂程度及其难度</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><span style=\"box-sizing: border-box;\">;第三就是我们技术人员的人手分配因素。</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><span style=\"box-sizing: border-box;\">这三个因素我们会综合考量、平衡取舍，最后给出一个明确的<a href=\"https://www.ccxcn.com/\" target=\"_blank\" title=\"网站建设\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out;\">网站建设</a>进度流程。</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><span style=\"box-sizing: border-box;\">对于用户要求的急切程度这一因素，我们会放在考虑因素的首位。也就是我们会尽最大努力满足用户的要求，如果用户要求的比较急，那么我们在保证<a href=\"https://www.ccxcn.com/\" target=\"_blank\" title=\"网站建设\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out;\">网站建设</a>质量的情况下，会尽力加班加点，尽最快速度完工，以最短的工期交付给用户合乎要求的、保质保量的网站。如果用户要求的不是太急，那么我们就会有较宽松的时间来进行<a href=\"https://www.ccxcn.com/design\" target=\"_blank\" title=\"网站设计\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out;\">网站设计</a>和策划和，在工期结束时完整地交付给用户。</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><span style=\"box-sizing: border-box;\">另外网站的复杂程度和难度也是我们必须考虑的。一个比较简单的网站，并不需要太多的时间，通常我们都会很快地完成，不然给用户等太久的。但是如果一个较为复杂、难度较大的网站，那将不得不花费更多的时间。关于这一点，我们也需要用户加以理解。毕竟，<a href=\"https://www.ccxcn.com/\" target=\"_blank\" title=\"网站建设\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out;\">网站建设</a>必须要保证质量的，不合格的网站交付给用户，用户也没法用。虽然我们会尽力往前赶时间，但是保质保量才是第一位的，因此我们只有在做好<a href=\"https://www.ccxcn.com/\" target=\"_blank\" title=\"网站建设\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out;\">网站建设</a>的同时，尽力往前赶时间。&nbsp;</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><span style=\"box-sizing: border-box;\">还有一个因素我们也会纳入建站进度的规划，那就是我们策划和技术人员的人手分配。假如我们的策划和技术人员比较繁忙，日常工作比较多，那么我们也会合理的规划您的<a href=\"https://www.ccxcn.com/\" target=\"_blank\" title=\"网站建设\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out;\">网站建设</a>进度。如果我们所有的技术人员都在为其他用户建设网站，那么我们无法抽出过多的人手为您的建站，这一点还请您不要误解，毕竟有个先来后到，我们的技术人员会在其他网站完工后，立即着手为您建站的。当然如果其他的网站不是太急切，而您的网站又比较急，那么我们也会考虑首先为您完成<a href=\"https://www.ccxcn.com/\" target=\"_blank\" title=\"网站建设\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out;\">网站建设</a>的。</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><span style=\"box-sizing: border-box;\">同时要注意以下几点：</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><span style=\"box-sizing: border-box;\">第一，选择一个好的域名，简单易记，最好选用‘.com’、‘.cn’等常见的后缀作为结尾，最重要的是这个域名没有被降权、没有被惩罚过的历史记录。</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><span style=\"box-sizing: border-box;\">第二，选择一个好的空间，主要目标客户在哪里，就选择哪里的服务器；同IP网站数要少，5个以内最好，而且要支持URL重写，多线路链接。&nbsp;</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><br/></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><br/></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><span style=\"box-sizing: border-box;\">第三，选择一个好的程序，尽可能程序能独立控制每个页面，一般不要用网上很多开源的程序，最好是建站公司自己开发的程序。</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><span style=\"box-sizing: border-box;\">在<a href=\"http://www.ccxcn.com/\" target=\"_blank\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out;\">建网站</a>的时候，相比设计动态页面，设计静态页面在操作方面要繁琐很多。在你没有特别要求的情况下，建站公司都会图方便而选择将页面设计成动态的。现在你要将动态页面处理成静态的，还需要网站的空间和程序支持URL重写，如果不支持，是无法实现动态URL伪静态化。这也是为什么我说在建站初期就要考虑URL的原因。</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><span style=\"box-sizing: border-box;\">总的来说，我们对于<a href=\"https://www.ccxcn.com/\" target=\"_blank\" title=\"网站建设\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out;\">网站建设</a>的进度规划，前提还是以用户为中心，在保证网站质量和要求的情况下，尽最大努力为用户缩短建站工期，不让用户等太久。</span></p><p><br/></p>', '0', '0', '', '0', '1', '');
INSERT INTO `yzn_article_data` VALUES ('6', '<p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\"><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">索引擎收录提交入口一览&nbsp;&nbsp;&nbsp;<br/></span></p><p><span style=\"box-sizing: border-box; color: rgb(51, 51, 51); font-size: 14px; background-color: rgb(255, 255, 255); font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">&nbsp;&nbsp;&nbsp;</span><a href=\"http://www.ccxcn.com/\" style=\"box-sizing: border-box; background-color: rgb(255, 255, 255); color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out; font-size: 14px; white-space: normal; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">网站建设</a><span style=\"box-sizing: border-box; color: rgb(51, 51, 51); font-size: 14px; background-color: rgb(255, 255, 255); font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">完成之后，第一件事情就是向各大搜索引擎提交新网站。</span><a href=\"http://www.baidu.com/\" style=\"box-sizing: border-box; background-color: rgb(255, 255, 255); color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out; font-size: 14px; white-space: normal; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">搜索引擎</a><span style=\"box-sizing: border-box; color: rgb(51, 51, 51); font-size: 14px; background-color: rgb(255, 255, 255); font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">提交包括提交给搜索引擎爬虫和提交给分类目录。提交给搜索引擎爬虫的目的是让搜索引擎将网站收录到索引数据库。<br/><br/>&nbsp;&nbsp; 检验网站是否被搜索引擎收录的办法是直接在搜索引擎中搜索网址，查看能否找到网站结果，也可以通过输入命令site:yoursite获得具体的页面收录数量。</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\"><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">中文网站提交给搜索引擎爬虫和分类目录<br/><br/>Google：</span><a href=\"http://www.google.com/intl/zh-CN/add_url.html\" target=\"_blank\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">http://www.Google.com/intl/zh-CN/add_url.html</a><br/><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">百度：</span><a href=\"http://www.baidu.com/search/url_submit.htm\" target=\"_blank\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">http://www.Baidu.com/search/url_submit.htm</a><br/><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">一搜：</span><a href=\"http://www.yisou.com/search_submit.html?source=yisou_www_hp\" target=\"_blank\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">http://www.yisou.com/search_submit.html?source=yisou_www_hp</a><br/><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">中搜：</span><a href=\"http://service.chinasearch.com.cn/NetSearch/pageurlrecord/frontpageurl.jsp\" target=\"_blank\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">http://service.chinasearch.com.cn/NetSearch/pageurlrecord/frontpageurl.jsp</a><br/><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">搜狐：</span><a href=\"http://db.sohu.com/regurl/regform.asp?Step=REGFORM&;class\" target=\"_blank\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">http://db.sohu.com/regurl/regform.asp?Step=REGFORM&amp;;class</a><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">= 　<br/>雅虎中国分类目录：</span><a href=\"http://cn.yahoo.com/docs/info/suggest.html\" target=\"_blank\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">http://cn.Yahoo.com/docs/info/suggest.html</a></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\"><a href=\"http://ccxcn.com/\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">网站建设</a><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">必备 索引擎收录提交入口一览&nbsp;&nbsp;&nbsp;<br/>英文网站提交给搜索引擎爬虫和分类目录<br/>Google：</span><a href=\"http://www.google.com/addurl/?continue=/addurl\" target=\"_blank\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">http://www.google.com/addurl/?continue=/addurl</a><br/><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">MSN：</span><a href=\"http://search.msn.com/docs/submit.aspx?FORM=WSDD2\" target=\"_blank\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">http://search.msn.com/docs/submit.aspx?FORM=WSDD2</a><br/><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">Yahoo：</span><a href=\"http://search.yahoo.com/info/submit.html\" target=\"_blank\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">http://search.yahoo.com/info/submit.html</a><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">&nbsp;（需要注册）<br/>Yahoo分类目录：</span><a href=\"http://searchmarketing.yahoo.com/dirsb/index.php\" target=\"_blank\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">http://searchmarketing.yahoo.com/dirsb/index.php</a><br/><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">Domz分类目录：</span><a href=\"http://www.dmoz.org/\" target=\"_blank\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">http://www.dmoz.org</a><br/><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">Jayde分类目录：</span><a href=\"http://dir.jayde.com/cgi-bin/submit.cgi\" target=\"_blank\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">http://dir.jayde.com/cgi-bin/submit.cgi</a><br/><br/><a href=\"http://ccxcn.com/\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">网站建设</a><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">必备 索引擎收录提交入口一览&nbsp;&nbsp;&nbsp;<br/>更多搜索引擎收录提交入口</span></p><p><span style=\"box-sizing: border-box; color: rgb(51, 51, 51); font-size: 14px; background-color: rgb(255, 255, 255); font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">Google免费登录入口百度免费登录入口雅虎免费登录入口一搜登陆入口新浪免费登录入口搜狐免费登录入口MSN登陆入口商情168搜索引擎免费登陆入口中国搜索同盟免费登录入口千度免费登录入口天网免费登录入口赛迪免费登录入口网易免费登录入口深圳网狐登陆入口6128搜索引擎免费登陆入口慧聪行业免费登录入口中华网免费登录入口北极星免费登录入口alltheweb免费登录入口中国白垩纪网搜索引擎登陆搜豹免费登录入口焦点网免费登录入口亦凡信息娱乐网络－亦凡搜索协通免费登录入口搜索引擎协通网登录入口法律网免费登录入口银河免费登录入口酷亿免费登录入口网络资源-久久网络温州信息港免费登录入口中国假日免费登录入口y4免费登录入口建设免费登录入口数字永嘉免费登录入口天下免费登录入口维华免费登录入口分享链接资源登录入口尤里卡免费登录入口爱艳儿免费登录入口山西互连网免费登录入口旅游云南友情链接登陆阳光免费登录入口孙悟空免费登录入口yahoo</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\"><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\"><a href=\"https://www.ccxcn.com/\" target=\"_blank\" title=\"网站建设\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out;\">网站建设</a>必备 索引擎收录提交入口一览&nbsp;&nbsp;&nbsp;<br/>全球各大搜索搜索引擎网站登录入口<br/></span><a href=\"http://www.google.com/intl/zh-CN/add_url.html\" target=\"_blank\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">Google网站登录</a><br/><a href=\"http://misc.yahoo.com.cn/search_submit.html\" target=\"_blank\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">yahoo 网站登录</a><br/><a href=\"http://pages.alexa.com/help/webmasters\" target=\"_blank\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">Alexa 网站登录</a><br/><a href=\"http://www.dmoz.com/World/Chinese_Simplified\" target=\"_blank\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">Dmoz 网站登录</a><br/><a href=\"http://search.msn.com/docs/submit.aspx?FORM=WSDD2\" target=\"_blank\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">msn 网站登录</a><br/><a href=\"http://www.altavista.com/addurl/default\" target=\"_blank\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">altavista 网站登录</a><br/><a href=\"http://www.alltheweb.com/help/webmaster/submit_site\" target=\"_blank\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">alltheweb 网站登录</a><br/><a href=\"http://www.accoona.com/public/submit_website.jsp\" target=\"_blank\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">accoona 网站登录</a><br/><a href=\"http://onebigdirectory.com/cgi-bin/SiteSubmitter/sitesubmitter.cgi\" target=\"_blank\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">onebigdirectory.com 搜索引擎批量提交</a><br/><a href=\"http://www.add-url-free.com/freesubmitter.htm\" target=\"_blank\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">add-url-free.com 搜索引擎批量提交</a><br/><a href=\"http://www.chainer.com/big5/submit/addurl.htm\" target=\"_blank\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">chainer.com 搜索引擎批量提交</a><br/><a href=\"http://www.freewebsubmission.com/\" target=\"_blank\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">freewebsubmission.com 搜索引擎批量提交</a><br/><a href=\"http://go.com/\" target=\"_blank\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">go.com&nbsp;&nbsp; 网站登录</a><br/><a href=\"http://www.exactseek.com/add.html\" target=\"_blank\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">exactseek&nbsp; 网站登录</a><br/><a href=\"http://search.looksmart.com/\" target=\"_blank\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">looksmart 网站登录</a><br/><a href=\"http://www.surfgopher.com/addurl.htm\" target=\"_blank\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">surfgopher&nbsp; 网站登录</a><br/><a href=\"http://www.beautycare.com/beautyseek/htdocs/no_cat.html\" target=\"_blank\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">beautycare&nbsp; 网站登录</a><br/><a href=\"http://www.isla-mujeres.net/addurl.htm\" target=\"_blank\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">islamujerse&nbsp; 网站登录</a><br/><a href=\"http://www.scrubtheweb.com/\" target=\"_blank\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">scrub the web 网站登录</a><br/><a href=\"http://www.syrialive.net/company_info/addulr.htm\" target=\"_blank\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">syrian links&nbsp; 网站登录</a><br/><a href=\"http://www.nationaldirectory.com/addurl/\" target=\"_blank\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">national directory&nbsp; 网站登录</a></p><p><br/></p>', '0', '0', '', '0', '1', '');
INSERT INTO `yzn_article_data` VALUES ('7', '<p><span style=\"color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; background-color: rgb(255, 255, 255);\">我们先来了解一下什么是站群，所谓站群就是一个网站的集合，站群特点是信息共享、分级管理、单点登录等，我们可以看到有很多国内和国际的大型的网站都用了站群的形式来运营它们的网站。</span><br/><br/><span style=\"color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; background-color: rgb(255, 255, 255);\">比如太平洋站群系统，新浪站群系统，最简单的站群就是以顶级域名为核心，根据业务和发展制定相关的二级域名，二级域在高度集中在以顶级域为核心的网页上，我们称之为首页。</span><br/><br/><span style=\"color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; background-color: rgb(255, 255, 255);\">站群有很多种，有通过正规手段做的，也有通过非正规手段做的，比如说泛站群，这样的站群只会在网络上生产垃圾内容，对于整个互联网环境是不利的。</span><br/><br/><span style=\"color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; background-color: rgb(255, 255, 255);\">站群与普通网站的区别一、</span><br/><br/><span style=\"color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; background-color: rgb(255, 255, 255);\">普通网站一般只有一个二级域，比如说www.****.com &lt;http://www.****.com&gt;等，在这个二级域的基础上拓展出不同的栏目域，比如www.***.com/a &lt;http://www.***.com/a&gt; 等形式得存在。</span><br/><br/><span style=\"color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; background-color: rgb(255, 255, 255);\">而站群的话，一般一个以www 为首页的聚合页面上，规则的部署不同的二级域，以上所说的是形式之一，当然还有其他的部署形式。</span><br/><br/><span style=\"color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; background-color: rgb(255, 255, 255);\">站群与普通网站的区别二、</span><br/><br/><span style=\"color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; background-color: rgb(255, 255, 255);\">大型企业一般都需要站群去部署网站系统，对于在全国或者全球都有相关业务并在线下都有实体的时候，站群就是最好的选择，站群可以将地域性业务很清晰的分类，明细每个区域的竞争规则，方便管理。而普通网站以栏目的形式去区分集团地域性的服务的时候，就心有余而力不足了。</span><br/><br/><span style=\"color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; background-color: rgb(255, 255, 255);\">站群对于seo优化的先天优势是毋庸置疑的，它可以将每个子域中的权重高度集中起来，对于搜索引擎来说每个二级域都是独立的，所以又可以做独立的优化，而互相影响较小。普通网站相对于站群运维成本可以控制的很小。这一点要跟企业的投入陈本相结合。</span><br/><br/><span style=\"color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; background-color: rgb(255, 255, 255);\">做站群还要根据现在的资源来确定，如果资源稀缺不建议去做，因为运维成本比较高，虽然可以集中权重，但如果做的不好的话也可分散权重。</span></p>', '0', '0', '', '0', '1', '');
INSERT INTO `yzn_article_data` VALUES ('8', '<p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><span style=\"box-sizing: border-box;\">这是个</span><span style=\"box-sizing: border-box;\">网络</span><span style=\"box-sizing: border-box;\">的时代，</span><span style=\"box-sizing: border-box;\">中国</span><span style=\"box-sizing: border-box;\">更是个</span><span style=\"box-sizing: border-box;\">微博或者微信</span><span style=\"box-sizing: border-box;\">之岛（对，你出去外面看到人家吃饭划手机，十个有八个都是在上</span><span style=\"box-sizing: border-box;\">微博或者微信</span><span style=\"box-sizing: border-box;\">）。</span><span style=\"box-sizing: border-box;\">微博或者微信</span><span style=\"box-sizing: border-box;\">很好用，只需要一块钱就可以推播你的广告/产品。</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><span style=\"box-sizing: border-box;\">当然，一块钱能推播的，顶多只有几十人，效益自然不大。</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><span style=\"box-sizing: border-box;\">但这一块钱告诉我们的，是：最小可以用1元开始做。</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><span style=\"box-sizing: border-box;\">这点差别很大。</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><span style=\"box-sizing: border-box;\">早年</span><span style=\"box-sizing: border-box;\">网络营销</span><span style=\"box-sizing: border-box;\">的主媒体是电视、广播、报章杂志，这些广告要有效，动不动就是五万十万。</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><span style=\"box-sizing: border-box;\">尔后</span><span style=\"box-sizing: border-box;\">网络</span><span style=\"box-sizing: border-box;\">兴起，想要在入口网站刊个首页广告也是五万十万。</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><span style=\"box-sizing: border-box;\">很多小型商家或是新创的小品牌，很难吞的下去。</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><span style=\"box-sizing: border-box;\">于是就有人想出&quot;关键字广告&quot;这招，告诉你小钱也可以做。</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><span style=\"box-sizing: border-box;\">结果大家就被骗了，关键字只要竞争激烈，点一次你的广告要花几十块。</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><span style=\"box-sizing: border-box;\">最机车的是这广告还只有几十个字！</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><span style=\"box-sizing: border-box;\">弄到最后各家厂商打得你死我活，关键字每天要喷个几千块，说白点就是&quot;砸钱互殴&quot;，赚到的，仍旧只有关键字业者。</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><span style=\"box-sizing: border-box;\">微博或者微信</span><span style=\"box-sizing: border-box;\">在这部分提供了一个&quot;相对比较好&quot;的曝光平台。</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><span style=\"box-sizing: border-box;\">我们可以从基本的粉丝页，100粉、200粉的开始做，不用管别人有多竞争，一步一步走，用优质的产品跟内容，经营专属于自己的品牌。</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><span style=\"box-sizing: border-box;\">每次推广的贴文可以制作一张精美的图片，加上些许的文字，不用跟别人竞价砸钱互殴，扎扎实实的从基础做起累积自己的品牌形象与支持群众。</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><span style=\"box-sizing: border-box;\">那你会问： 费用是怎么计算？</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><span style=\"box-sizing: border-box;\">有趣的就来了，这部分可以免费。</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><span style=\"box-sizing: border-box;\">粉丝的来源是自己</span><span style=\"box-sizing: border-box;\">微博或者微信</span><span style=\"box-sizing: border-box;\">的好友，如果你在</span><span style=\"box-sizing: border-box;\">微博或者微信</span><span style=\"box-sizing: border-box;\">上有个 100 好友，那么就邀请他们成为你的粉丝，朋友做生意相挺一下，第100粉很快就达成了。</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><span style=\"box-sizing: border-box;\">像是现实做生意，好东西也是先跟亲朋好友介绍一样。</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><span style=\"box-sizing: border-box;\">再来开始拓展粉丝群，每个粉丝都是几块钱就可以换到，就这样跟着你的品牌慢慢累积、200、500、1000、2000....直到你觉得</span><span style=\"box-sizing: border-box;\">中国</span><span style=\"box-sizing: border-box;\">在手机</span><span style=\"box-sizing: border-box;\">上已经没有更多的潜在客户为止。</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><span style=\"box-sizing: border-box;\">而费用也会是从 1000、2000、5000、10000 ，随着你的品牌上升而上升。</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><span style=\"box-sizing: border-box;\">因此就一个品牌的发展过程而言，我们认为</span><span style=\"box-sizing: border-box;\">微博或者微信</span><span style=\"box-sizing: border-box;\">会是一个不可或缺的工具。</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><span style=\"box-sizing: border-box;\">如果你现在的</span><a href=\"http://www.ccxcn.com/services\" target=\"_blank\" title=\"网站建设公司是北京传诚信\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out;\"><strong style=\"box-sizing: border-box; color: rgb(0, 0, 0);\"><span style=\"box-sizing: border-box;\">网站建设</span></strong></a><span style=\"box-sizing: border-box;\">公司没办法帮你经营</span><span style=\"box-sizing: border-box;\">微博或者微信</span><span style=\"box-sizing: border-box;\">，那我会诚挚的建议你</span><span style=\"box-sizing: border-box;\">要考虑考虑了</span><span style=\"box-sizing: border-box;\">，</span><span style=\"box-sizing: border-box;\">或者就是你自己为了省钱，没有给对方一定的费用了</span><span style=\"box-sizing: border-box;\">。</span></p><p><br/></p>', '0', '0', '', '0', '1', '');
INSERT INTO `yzn_article_data` VALUES ('9', '<p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\"><strong style=\"box-sizing: border-box; font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; color: rgb(0, 0, 0);\">参考1：参考PR值</strong></span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">一般我们会在交换链路时看到对方，有多少流量，实际上流量并不重要，这是重要的PR值，只有站点的高PR值才能有效地提高站点链路的链路价值。</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\"><strong style=\"box-sizing: border-box; font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; color: rgb(0, 0, 0);\">参考2：选择链接站的类别</strong></span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">在交换链接时需要有选择的交流，只有那些与网站类型相同，内容类似的网站是最好的选择。这有利于搜索引擎和包括。</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\"><strong style=\"box-sizing: border-box; font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; color: rgb(0, 0, 0);\">参考三：注重质量</strong></span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">友情链条不是更好，只有有效的链接是有用的，所以一定要好。如果一个网站的PR达到了5，但是网站有数百个链接，那么交换没有什么意义。</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\"><strong style=\"box-sizing: border-box; font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; color: rgb(0, 0, 0);\">参考四：研究搜索引擎中的链接数和反向链接数</strong></span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">如果一个网站的PR可以达到4甚至5或更多，但他的包括或反向链路的数量真的很小，那么我们必须考虑该网站是否有PR劫持作弊手段高PR。</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\"><strong style=\"box-sizing: border-box; font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; color: rgb(0, 0, 0);\">参考5：不要避免站点可能会与正确的交换</strong></span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">现在我们都知道，如果你的链接是百度下降了，那么你的网站很可能牵连。 因此，当我们交换链接时，你需要看看百度中包含的网站数量，如果包含的数量只有1，它很可能是向右。 另外，看看另一边的主页在百度快照的日期，如果快照的日期超过一周甚至更长，那么这个网站可能是百度右下。</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 2em;\"><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">链接到文本链接尽可能显示，应尽量避免图片链接，图片通常不被搜索引擎识别，图片由alt标签标识。如果你必须做图像链接，那么你必须添加alt标签。在交换链接时，你需要做很多准备工作，比如需要查询搜索引擎的对方号码和反向链接的数量，你需要查询对方的网站PR值，权重，需要查询其他网站主页百度快照日期等。</span></p><p><br/></p>', '0', '0', '', '0', '1', '');
INSERT INTO `yzn_article_data` VALUES ('10', '<p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\"><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">那么影响权重的因素有哪些？</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\"><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">1，域名类型</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\"><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">一般而言，gov和edu类型的域名权重自身就比较高，因为这样的站点属于政府高校一般不会成为垃圾站点，不会是草根个人小站。其次，com、net、org的域名权重相对较高，一些有国家和地区特点的域名后缀建议不要选。从用户习惯来看，com已成为首选。最后域名的注册年龄越久，搜索引擎给予的信任越高。</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\"><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">2，网站架构</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\"><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">网站架构不仅要迎合搜索引擎，更要符合用户体验的要求。优质的网站架构应是扁平式。即主页&gt;栏目页&gt;内容页，同时站点还应包含TAG标签，留言评论、文章搜索等。</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\"><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">3，导入链接</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\"><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">所谓的导入链接即外链。外链的质量和数量应认真做好。通常，我们要坚持每天稳定的增加外链，在数量和质量上，优先考虑质量。所以平时我们应注意多收集一些能做外链的优质博客、论坛，以备不时之需。</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\"><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">4，网站内容</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\"><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">虽说“天下文章一大抄，看你会抄不会抄”。仍建议站长能练就一副好笔杆。若能坚持给网站创造出优质的原创内容，事实上就是给搜索引擎注入新鲜的血液。实在是不能原创的时候，一定要伪原创下。切忌原封不动的复制粘贴，尤其是机器采集后进行所谓的自动伪原创，更有甚者前后两段抄堆叠关键词或者所谓的伪原创。其实段落语句不通，用户体验差，网站跳出率高，同样也不会有好的权重值。</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\"><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">5，收录数量</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\"><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">坚持更新站点，合理调整链接，增加站点页面的收录数量。虽说收录数量与权重之间不是绝对关系。你见过同类型同行业的网站收录数量仅有几十的权重高于几万的情况吗?</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\"><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">6，更新频率</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\"><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">坚持有规律的更新网站，除了会获得及时的网页快照外，还能增加搜索引擎的信任度，一个更新频繁的网站比那些僵尸网站的权重肯定会高很多。同时，应注意更新时间和更新数量，避免之前积累的权重值慢慢流失。</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\"><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">7，内容页</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\"><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">内页是否和首页以及网站主题有较为紧密的联系，内页之间的衔接、关键字的布局，以及内页是否具有专业性，权威度如何。</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\"><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">8，网站服务器</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\"><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">服务器稳定是关键，如果说搜索引擎抓取页面时网站空间无法访问，特别是新站，网站服务器不稳定可能导致搜索引擎不收录网站。其次是网站页面的打开速度，这些服务器因素对权重都有影响。</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\"><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\"><br/></span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\"><strong style=\"box-sizing: border-box; color: rgb(0, 0, 0);\"><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">所以如果想排名而且稳定必须做到<br/><br/></span></strong></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\"><em style=\"box-sizing: border-box;\"><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">网站内容和内链构造</span></em></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\"><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">网站内容</span><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">是一个网站的血肉，也是让搜索引擎爬虫喜欢的“食物”，网站有了丰富的内容和新鲜的血液，生命力才会更强。不管做什么类型的网站，内容始终是一个难题，但是也只有内容是留住爬虫的有力武器，而且内容必须是原创程度很高的。互联网本身就是用户产生内容的一本大教科书，每个用户都可以为你的网站贡献内容，关键看你如何去引导用户，这种用户自发产生的内容才是原创度最好的，而且会让</span><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">搜索引擎</span><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">对你的网站更信任，网站权重自然提升很快。<br/><br/></span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\"><em style=\"box-sizing: border-box;\"><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">提高用户的访问</span></em></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\"><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">就是大家平时所说的外链。这个无疑是被个人站长应用到最大化的地步。假设搜索引擎的技术足够成熟，我想它最想去除掉的外链，应该就是站长自己发的外链了，因为这个对于权重来说，没丝毫作用。真正能判定权重的是用户自主传播的、还有大网站推荐的之类的。现 在由于各种原因，互动类型的很多外链都无法算入内。但是，这个方式以及逐渐成为网民分享链接的一个主要方式，所以在将来，肯定也会是一个权重评判的重要标准。<br/><br/></span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\"><em style=\"box-sizing: border-box;\"><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">前期需要充分利用好</span><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">软文推广</span><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">。</span></em></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\"><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">网站刚刚成立(建立)之初，网站在搜索引擎的权重是非常低的，虽然有很好的内容和网站构架也不足于与建立多时的网站抗衡。充分利用好软文推广可以加速网站权重的积累。<br/><br/></span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\"><em style=\"box-sizing: border-box;\"><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">重视中前期的内容更新数量和质量。</span></em></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\"><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">很多站长都有这样的毛病，尤其是那些自认为技术高超的人，只要受点小小的挫折就容易放弃。经过长期的实践发现</span><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">网站运营</span><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">到了中前期时网站就容易进入难以突破的情况，这个时间很多站长就按耐不住要</span><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">改弦更张</span><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">，这个时候笔者再次告戒这些站长们重视中前期的内容更新数量和质量，网站就很快取得突破。<br/><br/></span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255);\"><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;;\">简单讲，需要不间断的更新网站，要原创的，做好网站的内链，外链，友链。专人专管。选用稳定的服务器。网站必定会有一个好的排名！</span></p><p><br/></p>', '0', '0', '', '0', '1', '');
INSERT INTO `yzn_article_data` VALUES ('11', '<p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 37px;\"><span style=\"font-family: 微软雅黑, &quot;Microsoft YaHei&quot;; font-size: 14px;\"><strong style=\"box-sizing: border-box; color: rgb(0, 0, 0);\"><span style=\"font-family: 微软雅黑, &quot;Microsoft YaHei&quot;; box-sizing: border-box;\"><span style=\"font-family: 微软雅黑, &quot;Microsoft YaHei&quot;; box-sizing: border-box;\">我的网站只有独立的</span>IP地址，没有域名需要办理网上备案手续吗？</span></strong></span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 37px;\"><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;; font-size: 14px;\">需要。无论您的网站是通过域名方式访问或是通过IP地址的方式访问，只要在中华人民共和国境内提供非经营性互联网信息服务都要办理备案手续。</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 37px;\"><span style=\"font-family: 微软雅黑, &quot;Microsoft YaHei&quot;; font-size: 14px;\"><strong style=\"box-sizing: border-box; color: rgb(0, 0, 0);\"><span style=\"box-sizing: border-box; font-size: 19px; font-family: 仿宋_GB2312;\">如果一个备案单位同时具有两个网站，可以将两个网站分别备案在两个相同的主体下吗？</span></strong></span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 37px;\"><span style=\"box-sizing: border-box; font-family: 微软雅黑, &quot;Microsoft YaHei&quot;; font-size: 14px;\">不可以，如果两个网站的备案主体都是同一个备案单位，那么只能将两个网站备案在同一个主体下。</span></p><p><br/></p>', '0', '0', '', '0', '1', '');
INSERT INTO `yzn_article_data` VALUES ('12', '<p><span style=\"box-sizing: border-box; color: rgb(51, 51, 51); font-family: 仿宋_GB2312; font-size: 19px; text-indent: 37px; background-color: rgb(255, 255, 255);\">根据《互联网信息服务管理办法》（国务院</span><span style=\"color: rgb(51, 51, 51); font-family: 仿宋_GB2312; font-size: 19px; text-indent: 37px; background-color: rgb(255, 255, 255);\">292号令）第5条等有关规定，拟从事新闻、出版、教育、医疗保健、药品和医疗器械、文化、广播电影电视节目等互联网信息服务，依照法律、行政法规以及国家有关规定应经有关主管部门审核同意的，在履行备案手续时，还应向其住所所在地省通信管理局提交相关主管部门审核同意的文件。</span></p>', '0', '0', '', '0', '1', '');
INSERT INTO `yzn_article_data` VALUES ('13', '<p><span style=\"box-sizing: border-box; color: rgb(51, 51, 51); text-indent: 37px; background-color: rgb(255, 255, 255); font-family: 仿宋_GB2312; font-size: 19px;\"><span style=\"box-sizing: border-box;\">升级后的网站备案管理系统实现了工业和信息化部、各通信管理局、接入服务企业三级备案管理服务模式。在原网站备案</span></span><span style=\"box-sizing: border-box; color: rgb(51, 51, 51); text-indent: 37px; background-color: rgb(255, 255, 255); font-family: 仿宋_GB2312; font-size: 19px;\"><span style=\"box-sizing: border-box;\">管理</span></span><span style=\"box-sizing: border-box; color: rgb(51, 51, 51); text-indent: 37px; background-color: rgb(255, 255, 255); font-family: 仿宋_GB2312; font-size: 19px;\"><span style=\"box-sizing: border-box;\">系统的</span></span><span style=\"box-sizing: border-box; color: rgb(51, 51, 51); text-indent: 37px; background-color: rgb(255, 255, 255); font-family: 仿宋_GB2312; font-size: 19px;\"><span style=\"box-sizing: border-box;\">服务功能</span></span><span style=\"box-sizing: border-box; color: rgb(51, 51, 51); text-indent: 37px; background-color: rgb(255, 255, 255); font-family: 仿宋_GB2312; font-size: 19px;\"><span style=\"box-sizing: border-box;\">基础上，增加了通信管理局级和接入服务企业级网站备案</span></span><span style=\"box-sizing: border-box; color: rgb(51, 51, 51); text-indent: 37px; background-color: rgb(255, 255, 255); font-family: 仿宋_GB2312; font-size: 19px;\"><span style=\"box-sizing: border-box;\">管理</span></span><span style=\"box-sizing: border-box; color: rgb(51, 51, 51); text-indent: 37px; background-color: rgb(255, 255, 255); font-family: 仿宋_GB2312; font-size: 19px;\"><span style=\"box-sizing: border-box;\">系统。网站主办者仅需向接入服务企业提交备案申请，接入服务企业核验后将备案信息提交至通信管理局备案系统，通信管理局进行审核，审核通过后生成备案号发给网站主办者和接入服务企业。</span></span></p>', '0', '0', '', '0', '1', '');
INSERT INTO `yzn_article_data` VALUES ('14', '<p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 37px;\"><span style=\"box-sizing: border-box; font-family: 仿宋_GB2312; font-size: 19px;\"><span style=\"box-sizing: border-box;\">方法一：您可以通过备案系统找回。登陆</span></span><a href=\"http://www.miitbeian.gov.cn/\" style=\"box-sizing: border-box; background-color: transparent; color: rgb(51, 51, 51); text-decoration: none; transition-property: all; transition-duration: 0.3s; transition-timing-function: ease-out;\"><span style=\"box-sizing: border-box; font-family: 仿宋_GB2312; font-size: 19px;\">www.miitbeian.gov.cn</span></a><span style=\"box-sizing: border-box; font-family: 仿宋_GB2312; font-size: 19px;\"><span style=\"box-sizing: border-box;\">，在右下角有</span>“找回备案密码”按钮，选择主体所在省，在跳出的网页中，输入“备案/许可证号、证件类型、证件号码”，输入完成后点提交。如果信息填写正确，系统会向您当年注册的E-mail发送新备案密码。</span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 37px;\"><span style=\"box-sizing: border-box; font-family: 仿宋_GB2312; font-size: 19px;\"><span style=\"box-sizing: border-box;\">方法二：如果您的备案信息是接入商代为备案的，您可以联系代为备案的接入商告诉您如何找回备案密码。</span></span></p><p style=\"box-sizing: border-box; margin-top: 0px; margin-bottom: 10px; color: rgb(51, 51, 51); font-family: Roboto, &quot;PingFang SC&quot;, 微软雅黑; font-size: 14px; white-space: normal; background-color: rgb(255, 255, 255); text-indent: 37px;\"><span style=\"box-sizing: border-box; font-family: 仿宋_GB2312; font-size: 19px;\"><span style=\"box-sizing: border-box;\">方法三：您也可以通过联系备案号发放地通信管理局，并按要求提供相应的证明材料后，取回备案密码。</span></span></p><p><br/></p>', '0', '0', '', '0', '1', '');
INSERT INTO `yzn_article_data` VALUES ('15', '<p><span style=\"box-sizing: border-box; color: rgb(51, 51, 51); font-family: 仿宋_GB2312; font-size: 19px; text-indent: 37px; background-color: rgb(255, 255, 255);\">根据《国务院互联网信息服务管理办法》（国务院</span><span style=\"color: rgb(51, 51, 51); font-family: 仿宋_GB2312; font-size: 19px; text-indent: 37px; background-color: rgb(255, 255, 255);\">292号令）的第三条规定，“经营性互联网信息服务”是指通过互联网向上网用户有偿提供信息或者网页制作等服务活动。“非经营性互联网信息服务”是指通过互联网向上网用户无偿提供具有公开性、共享性信息的服务活动。</span></p>', '0', '0', '', '0', '1', '');

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
) ENGINE=MyISAM AUTO_INCREMENT=58 DEFAULT CHARSET=utf8 COMMENT='规则表';

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
INSERT INTO `yzn_auth_rule` VALUES ('31', 'Admin', '1', 'Admin/Config/mail', '邮箱配置', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('32', 'Admin', '1', 'Admin/Module/local', '本地模块', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('33', 'Admin', '1', 'Admin/Module/index', '模块管理', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('34', 'Links', '1', 'Links/Links/index', '友情链接', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('35', 'Links', '1', 'Links/Links/add', '添加友情链接', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('36', 'Links', '1', 'Links/Links/edit', '链接编辑', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('37', 'Links', '1', 'Links/Links/delete', '链接删除', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('38', 'Links', '1', 'Links/Links/listorder', '链接排序', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('39', 'Links', '1', 'Links/Links/terms', '分类管理', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('40', 'Links', '1', 'Links/Links/addTerms', '分类新增', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('41', 'Links', '1', 'Links/Links/termsedit', '分类修改', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('42', 'Links', '1', 'Links/Links/termsdelete', '分类删除', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('43', 'Content', '1', 'Content/Position/index', '推荐位管理', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('44', 'Formguide', '1', 'Formguide/Formguide/index', '表单管理', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('45', 'Formguide', '1', 'Formguide/Formguide/add', '添加表单', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('46', 'Formguide', '1', 'Formguide/Formguide/edit', '编辑', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('47', 'Formguide', '1', 'Formguide/Formguide/delete', '删除', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('48', 'Formguide', '1', 'Formguide/Formguide/status', '禁用', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('49', 'Formguide', '1', 'Formguide/Info/index', '信息列表', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('50', 'Formguide', '1', 'Formguide/Info/delete', '信息删除', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('51', 'Formguide', '1', 'Formguide/Field/index', '管理字段', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('52', 'Formguide', '1', 'Formguide/Field/add', '添加字段', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('53', 'Formguide', '1', 'Formguide/Field/edit', '编辑字段', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('54', 'Formguide', '1', 'Formguide/Field/delete', '删除字段', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('55', 'Admin', '1', 'Admin/Module/list', '模块列表', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('56', 'Admin', '2', 'Admin/Module/index', '模块', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('57', 'Admin', '1', 'Admin/Cache/index', '缓存更新', '1', '');

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
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='缓存列队表';

-- ----------------------------
-- Records of yzn_cache
-- ----------------------------
INSERT INTO `yzn_cache` VALUES ('1', 'Model', '模型列表', 'Content', 'Models', 'model_cache', '0');
INSERT INTO `yzn_cache` VALUES ('2', 'Category', '栏目索引', 'Content', 'Category', 'category_cache', '0');
INSERT INTO `yzn_cache` VALUES ('3', 'ModelField', '模型字段', 'Content', 'ModelField', 'model_field_cache', '0');
INSERT INTO `yzn_cache` VALUES ('4', 'Config', '网站配置', '', 'Configs', 'config_cache', '1');
INSERT INTO `yzn_cache` VALUES ('5', 'Module', '可用模块列表', '', 'Module', 'module_cache', '1');
INSERT INTO `yzn_cache` VALUES ('6', 'Model_form', '自定义表单模型', 'formguide', 'Formguide', 'formguide_cache', '0');
INSERT INTO `yzn_cache` VALUES ('7', 'Member_Config', '会员配置', 'Member', 'Member', 'member_cache', '0');
INSERT INTO `yzn_cache` VALUES ('8', 'Member_group', '会员组', 'Member', 'MemberGroup', 'membergroup_cache', '0');
INSERT INTO `yzn_cache` VALUES ('9', 'Model_Member', '会员模型', 'Member', 'Member', 'member_model_cahce', '0');
INSERT INTO `yzn_cache` VALUES ('10', 'Addons', '插件列表', 'Addons', 'Addons', 'addons_cache', '0');
INSERT INTO `yzn_cache` VALUES ('11', 'Hooks', '钩子列表', 'Admin', 'Hooks', 'hook_cache', '0');

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
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='栏目表';

-- ----------------------------
-- Records of yzn_category
-- ----------------------------
INSERT INTO `yzn_category` VALUES ('1', 'content', '0', '1', '', '0', '0', '1', '1,2,3,4', '新闻资讯', '', '', '', 'news', '/home/index/lists/catid/1', '0', 'a:6:{s:17:\"category_template\";s:13:\"category.html\";s:13:\"list_template\";s:9:\"list.html\";s:13:\"show_template\";s:9:\"show.html\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";}', '1', '1', '0', 'xinwenzixun');
INSERT INTO `yzn_category` VALUES ('2', 'content', '0', '1', '', '1', '0,1', '0', '2', '网站知识', '', '', 'news/', 'knowledge', '/home/index/lists/catid/2', '0', 'a:6:{s:17:\"category_template\";s:13:\"category.html\";s:13:\"list_template\";s:9:\"list.html\";s:13:\"show_template\";s:9:\"show.html\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";}', '2', '1', '0', 'wangzhanzhishi');
INSERT INTO `yzn_category` VALUES ('3', 'content', '0', '1', '', '1', '0,1', '0', '3', '网络营销', '', '', 'news/', 'marketing', '/home/index/lists/catid/3', '0', 'a:6:{s:17:\"category_template\";s:13:\"category.html\";s:13:\"list_template\";s:9:\"list.html\";s:13:\"show_template\";s:9:\"show.html\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";}', '3', '1', '0', 'wangluoyingxiao');
INSERT INTO `yzn_category` VALUES ('4', 'content', '0', '1', '', '1', '0,1', '0', '4', '备案知识', '', '', 'news/', 'record', '/home/index/lists/catid/4', '0', 'a:6:{s:17:\"category_template\";s:13:\"category.html\";s:13:\"list_template\";s:9:\"list.html\";s:13:\"show_template\";s:9:\"show.html\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";}', '4', '1', '0', 'beianzhishi');
INSERT INTO `yzn_category` VALUES ('5', 'content', '1', '0', '', '0', '0', '1', '5,6,7', '底部导航', '', '', '', 'bottom_nav', '/home/index/lists/catid/5', '0', 'a:4:{s:13:\"page_template\";s:9:\"page.html\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";}', '5', '0', '0', 'dibudaohang');
INSERT INTO `yzn_category` VALUES ('6', 'content', '1', '0', '', '5', '0,5', '0', '6', '关于我们', '', '', 'bottom_nav/', 'about', '/home/index/lists/catid/6', '0', 'a:4:{s:13:\"page_template\";s:9:\"page.html\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";}', '6', '1', '0', 'guanyuwomen');
INSERT INTO `yzn_category` VALUES ('7', 'content', '1', '0', '', '5', '0,5', '0', '7', '联系我们', '', '', 'bottom_nav/', 'contact', '/home/index/lists/catid/7', '0', 'a:4:{s:13:\"page_template\";s:9:\"page.html\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";}', '7', '1', '0', 'lianxiwomen');
INSERT INTO `yzn_category` VALUES ('8', 'content', '1', '0', '', '0', '0', '0', '8', '表单模块', '', '', '', 'message', '/home/index/lists/catid/8', '0', 'a:4:{s:13:\"page_template\";s:17:\"page_message.html\";s:10:\"meta_title\";s:0:\"\";s:13:\"meta_keywords\";s:0:\"\";s:16:\"meta_description\";s:0:\"\";}', '8', '1', '0', 'biaodanmokuai');

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
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='网站配置表';

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
INSERT INTO `yzn_config` VALUES ('12', 'hooks_type', '钩子的类型', '1', '1:视图\r\n2:控制器', '0');

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
-- Table structure for `yzn_form_message`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_form_message`;
CREATE TABLE `yzn_form_message` (
  `dataid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `userid` mediumint(8) unsigned NOT NULL,
  `username` varchar(20) NOT NULL,
  `datetime` int(10) unsigned NOT NULL,
  `ip` char(15) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `phone` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `message_content` mediumtext,
  PRIMARY KEY (`dataid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of yzn_form_message
-- ----------------------------

-- ----------------------------
-- Table structure for `yzn_hits`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_hits`;
CREATE TABLE `yzn_hits` (
  `hitsid` char(30) NOT NULL,
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `views` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '总数',
  `yesterdayviews` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '昨日',
  `dayviews` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '今日',
  `weekviews` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '本周访问',
  `monthviews` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '本月访问',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后更新时间',
  PRIMARY KEY (`hitsid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='访问统计';

-- ----------------------------
-- Records of yzn_hits
-- ----------------------------
INSERT INTO `yzn_hits` VALUES ('c-1-1', '2', '72', '2', '4', '70', '70', '1507197896');
INSERT INTO `yzn_hits` VALUES ('c-1-2', '2', '2', '1', '1', '2', '2', '1507107131');
INSERT INTO `yzn_hits` VALUES ('c-1-3', '2', '26', '1', '6', '26', '26', '1507213335');
INSERT INTO `yzn_hits` VALUES ('c-1-4', '2', '1', '0', '1', '1', '1', '1507027592');
INSERT INTO `yzn_hits` VALUES ('c-1-5', '2', '5', '0', '3', '5', '5', '1507177340');
INSERT INTO `yzn_hits` VALUES ('c-1-6', '3', '39', '3', '1', '1', '39', '1507956106');
INSERT INTO `yzn_hits` VALUES ('c-1-7', '3', '23', '1', '1', '23', '23', '1507380791');
INSERT INTO `yzn_hits` VALUES ('c-1-8', '3', '9', '6', '3', '9', '9', '1507029586');
INSERT INTO `yzn_hits` VALUES ('c-1-9', '3', '4', '2', '2', '4', '4', '1507029399');
INSERT INTO `yzn_hits` VALUES ('c-1-10', '3', '0', '0', '0', '0', '0', '1506917580');
INSERT INTO `yzn_hits` VALUES ('c-1-11', '4', '6', '3', '3', '6', '6', '1507024777');
INSERT INTO `yzn_hits` VALUES ('c-1-12', '4', '0', '0', '0', '0', '0', '1506917580');
INSERT INTO `yzn_hits` VALUES ('c-1-13', '4', '3', '1', '1', '3', '3', '1507213350');
INSERT INTO `yzn_hits` VALUES ('c-1-14', '4', '11', '0', '11', '11', '11', '1507026231');
INSERT INTO `yzn_hits` VALUES ('c-1-15', '4', '9', '0', '9', '9', '9', '1507026233');

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
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yzn_hooks
-- ----------------------------
INSERT INTO `yzn_hooks` VALUES ('1', 'pageHeader', '页面header钩子，一般用于加载插件CSS文件和代码', '1', '0', '');
INSERT INTO `yzn_hooks` VALUES ('2', 'pageFooter', '页面footer钩子，一般用于加载插件JS文件和JS代码', '1', '1509174020', 'returntop');
INSERT INTO `yzn_hooks` VALUES ('3', 'app_begin', '应用开始', '2', '1384481614', '');

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
-- Table structure for `yzn_member`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_member`;
CREATE TABLE `yzn_member` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `nickname` char(16) NOT NULL DEFAULT '' COMMENT '昵称',
  `sex` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '性别',
  `birthday` date NOT NULL DEFAULT '0000-00-00' COMMENT '生日',
  `qq` char(10) NOT NULL DEFAULT '' COMMENT 'qq号',
  `point` mediumint(8) NOT NULL DEFAULT '0' COMMENT '用户积分',
  `amount` decimal(8,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '钱金总额',
  `login` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录次数',
  `groupid` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '用户组ID',
  `modelid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '用户模型ID',
  `reg_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '注册IP',
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `last_login_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '最后登录IP',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '会员状态',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='会员表';

-- ----------------------------
-- Records of yzn_member
-- ----------------------------
INSERT INTO `yzn_member` VALUES ('1', '御宅男', '0', '0000-00-00', '', '100', '0.00', '0', '2', '3', '0', '0', '0', '0', '1');

-- ----------------------------
-- Table structure for `yzn_member_detail`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_member_detail`;
CREATE TABLE `yzn_member_detail` (
  `userid` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of yzn_member_detail
-- ----------------------------

-- ----------------------------
-- Table structure for `yzn_member_group`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_member_group`;
CREATE TABLE `yzn_member_group` (
  `groupid` tinyint(3) unsigned NOT NULL AUTO_INCREMENT COMMENT '会员组id',
  `name` char(15) NOT NULL COMMENT '用户组名称',
  `issystem` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否是系统组',
  `starnum` tinyint(2) unsigned NOT NULL COMMENT '会员组星星数',
  `point` smallint(6) unsigned NOT NULL COMMENT '积分范围',
  `allowmessage` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '许允发短消息数量',
  `allowvisit` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否允许访问',
  `allowpost` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否允许发稿',
  `allowpostverify` tinyint(1) unsigned NOT NULL COMMENT '是否投稿不需审核',
  `allowsearch` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否允许搜索',
  `allowupgrade` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否允许自主升级',
  `allowsendmessage` tinyint(1) unsigned NOT NULL COMMENT '允许发送短消息',
  `allowpostnum` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '每天允许发文章数',
  `allowattachment` tinyint(1) NOT NULL COMMENT '是否允许上传附件',
  `price_y` decimal(8,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '包年价格',
  `price_m` decimal(8,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '包月价格',
  `price_d` decimal(8,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '包天价格',
  `icon` char(30) NOT NULL COMMENT '用户组图标',
  `usernamecolor` char(7) NOT NULL COMMENT '用户名颜色',
  `description` char(100) NOT NULL COMMENT '描述',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `disabled` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否禁用',
  PRIMARY KEY (`groupid`),
  KEY `disabled` (`disabled`),
  KEY `listorder` (`sort`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yzn_member_group
-- ----------------------------
INSERT INTO `yzn_member_group` VALUES ('8', '游客', '1', '0', '0', '0', '0', '0', '0', '1', '0', '0', '0', '0', '0.00', '0.00', '0.00', '', '', '', '0', '0');
INSERT INTO `yzn_member_group` VALUES ('2', '新手上路', '1', '1', '50', '100', '1', '1', '0', '0', '0', '1', '0', '0', '50.00', '10.00', '1.00', '', '', '', '2', '0');
INSERT INTO `yzn_member_group` VALUES ('6', '注册会员', '1', '2', '100', '150', '0', '1', '0', '0', '1', '1', '0', '0', '300.00', '30.00', '1.00', '', '', '', '6', '0');
INSERT INTO `yzn_member_group` VALUES ('4', '中级会员', '1', '3', '150', '500', '1', '1', '0', '1', '1', '1', '0', '0', '500.00', '60.00', '1.00', '', '', '', '4', '0');
INSERT INTO `yzn_member_group` VALUES ('5', '高级会员', '1', '5', '300', '999', '1', '1', '0', '1', '1', '1', '0', '0', '360.00', '90.00', '5.00', '', '', '', '5', '0');
INSERT INTO `yzn_member_group` VALUES ('1', '禁止访问', '1', '0', '0', '0', '1', '1', '0', '1', '0', '0', '0', '0', '0.00', '0.00', '0.00', '', '', '0', '0', '0');
INSERT INTO `yzn_member_group` VALUES ('7', '邮件认证', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0.00', '0.00', '0.00', 'images/group/vip.jpg', '#000000', '', '7', '0');

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
) ENGINE=MyISAM AUTO_INCREMENT=72 DEFAULT CHARSET=utf8 COMMENT='后台菜单表';

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
INSERT INTO `yzn_menu` VALUES ('53', '推荐位管理', '', '35', 'Content', 'Position', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('54', '表单管理', '', '43', 'Formguide', 'Formguide', 'index', '', '1', '自定义表单管理！', '0', '0');
INSERT INTO `yzn_menu` VALUES ('55', '添加表单', '', '54', 'Formguide', 'Formguide', 'add', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('56', '编辑', '', '54', 'Formguide', 'Formguide', 'edit', '', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('57', '删除', '', '54', 'Formguide', 'Formguide', 'delete', '', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('58', '禁用', '', '54', 'Formguide', 'Formguide', 'status', '', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('59', '信息列表', '', '54', 'Formguide', 'Info', 'index', '', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('60', '信息删除', '', '59', 'Formguide', 'Info', 'delete', '', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('61', '管理字段', '', '54', 'Formguide', 'Field', 'index', '', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('62', '添加字段', '', '61', 'Formguide', 'Field', 'add', '', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('63', '编辑字段', '', '61', 'Formguide', 'Field', 'edit', '', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('64', '删除字段', '', '61', 'Formguide', 'Field', 'delete', '', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('65', '缓存更新', '', '10', 'Admin', 'Cache', 'index', '', '1', '', '0', '100');
INSERT INTO `yzn_menu` VALUES ('3', '用户', '', '0', 'Member', 'index', 'index', '', '1', '', '0', '3');
INSERT INTO `yzn_menu` VALUES ('4', '会员管理', 'icon iconfont icon-yonghu', '3', 'Member', 'index', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('7', '会员管理', '', '4', 'Member', 'member', 'manage', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('11', '会员模型', 'icon iconfont icon-guanliyuan', '3', 'Member', 'Model', 'index', '', '1', '', '0', '1');
INSERT INTO `yzn_menu` VALUES ('66', '模型管理', '', '11', 'Member', 'Model', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('67', '会员组', 'icon iconfont icon-chengyuan', '3', 'Member', 'Group', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('68', '会员组管理', '', '67', 'Member', 'Group', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('8', '扩展', '', '0', 'Addons', 'Addons', 'index', '', '1', '', '0', '5');
INSERT INTO `yzn_menu` VALUES ('69', '插件拓展', 'icon iconfont icon-chajian', '8', 'Addons', 'Addons', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('70', '插件管理', '', '69', 'Addons', 'Addons', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('71', '行为管理', '', '69', 'Addons', 'Addons', 'hooks', '', '1', '', '0', '0');

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
INSERT INTO `yzn_model` VALUES ('1', '文章模型', '文章模型', 'article', '', '1507284550', '0', '1', '0', '', '', '', '', '', '0', '0');
INSERT INTO `yzn_model` VALUES ('2', '用户留言表单', '用户留言表单', 'form_message', 'a:11:{s:7:\"forward\";s:0:\"\";s:10:\"enabletime\";s:1:\"0\";s:8:\"sendmail\";s:1:\"0\";s:16:\"allowmultisubmit\";s:1:\"0\";s:10:\"allowunreg\";s:1:\"0\";s:8:\"isverify\";s:1:\"1\";s:8:\"interval\";s:0:\"\";s:13:\"show_template\";s:9:\"show.html\";s:16:\"show_js_template\";s:12:\"js_show.html\";s:9:\"starttime\";b:0;s:7:\"endtime\";b:0;}', '1507380061', '0', '1', '0', '', '', '', '', '', '0', '3');
INSERT INTO `yzn_model` VALUES ('3', '普通会员', '普通会员', 'member_detail', null, '1507984836', '0', '1', '0', '', '', '', '', '', '0', '2');

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
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COMMENT='模型字段列表';

-- ----------------------------
-- Records of yzn_model_field
-- ----------------------------
INSERT INTO `yzn_model_field` VALUES ('1', '1', 'status', '状态', '', '', '0', '2', '', '', 'box', '', '', '', '', '1', '1', '0', '1', '0', '0', '0', '0', '15', '0', '0', '', '');
INSERT INTO `yzn_model_field` VALUES ('2', '1', 'username', '用户名', '', '', '0', '20', '', '', 'text', '', '', '', '', '1', '1', '0', '1', '0', '0', '0', '0', '16', '0', '0', '', '');
INSERT INTO `yzn_model_field` VALUES ('3', '1', 'islink', '转向链接', '', '', '0', '0', '', '', 'islink', '', '', '', '', '0', '1', '0', '0', '0', '1', '0', '0', '17', '0', '0', '', '');
INSERT INTO `yzn_model_field` VALUES ('4', '1', 'template', '内容页模板', '', '', '0', '30', '', '', 'template', 'a:2:{s:4:\"size\";s:0:\"\";s:12:\"defaultvalue\";s:0:\"\";}', '', '-99', '-99', '0', '0', '0', '0', '0', '0', '0', '0', '13', '0', '0', '', '');
INSERT INTO `yzn_model_field` VALUES ('5', '1', 'allow_comment', '允许评论', '', '', '0', '0', '', '', 'box', 'a:9:{s:7:\"options\";s:33:\"允许评论|1\r\n不允许评论|0\";s:7:\"boxtype\";s:5:\"radio\";s:9:\"fieldtype\";s:7:\"tinyint\";s:9:\"minnumber\";s:1:\"1\";s:5:\"width\";s:2:\"88\";s:4:\"size\";s:0:\"\";s:12:\"defaultvalue\";s:1:\"1\";s:10:\"outputtype\";s:1:\"1\";s:10:\"filtertype\";s:1:\"0\";}', '', '', '', '0', '0', '0', '0', '0', '0', '0', '0', '14', '0', '0', '', '');
INSERT INTO `yzn_model_field` VALUES ('6', '1', 'pages', '分页方式', '', '', '0', '0', '', '', 'pages', '', '', '-99', '-99', '0', '0', '0', '1', '0', '0', '0', '0', '9', '0', '0', '', '');
INSERT INTO `yzn_model_field` VALUES ('7', '1', 'inputtime', '真实发布时间', '', '', '0', '0', '', '', 'datetime', 'a:3:{s:9:\"fieldtype\";s:3:\"int\";s:6:\"format\";s:11:\"Y-m-d H:i:s\";s:11:\"defaulttype\";s:1:\"0\";}', '', '', '', '1', '1', '0', '0', '0', '0', '0', '1', '11', '0', '0', '', '');
INSERT INTO `yzn_model_field` VALUES ('8', '1', 'posid', '推荐位', '', '', '0', '0', '', '', 'posid', 'a:4:{s:5:\"width\";s:3:\"125\";s:12:\"defaultvalue\";s:0:\"\";s:12:\"backstagefun\";s:0:\"\";s:8:\"frontfun\";s:0:\"\";}', '', '', '', '0', '1', '0', '1', '0', '0', '0', '1', '11', '0', '0', '', '');
INSERT INTO `yzn_model_field` VALUES ('9', '1', 'url', 'URL', '', '', '0', '100', '', '', 'text', '', '', '', '', '1', '1', '0', '1', '0', '0', '0', '1', '12', '0', '0', '', '');
INSERT INTO `yzn_model_field` VALUES ('10', '1', 'listorder', '排序', '', '', '0', '6', '', '', 'number', '', '', '', '', '1', '1', '0', '1', '0', '0', '0', '0', '18', '0', '0', '', '');
INSERT INTO `yzn_model_field` VALUES ('11', '1', 'relation', '相关文章', '', '', '0', '255', '', '', 'omnipotent', 'a:4:{s:8:\"formtext\";s:464:\"<input type=\"hidden\" name=\"info[relation]\" id=\"relation\" value=\"{FIELD_VALUE}\" style=\"50\" >\n<ul class=\"list-dot\" id=\"relation_text\">\n</ul>\n<input type=\"button\" value=\"添加相关\" onClick=\"omnipotent(\'selectid\',GV.DIMAUB+\'index.php?a=public_relationlist&m=Content&g=Content&modelid={MODELID}\',\'添加相关文章\',1)\" class=\"btn\">\n<span class=\"edit_content\">\n  <input type=\"button\" value=\"显示已有\" onClick=\"show_relation({MODELID},{ID})\" class=\"btn\">\n</span>\";s:9:\"fieldtype\";s:7:\"varchar\";s:12:\"backstagefun\";s:0:\"\";s:8:\"frontfun\";s:0:\"\";}', '', '', '', '0', '0', '0', '0', '0', '0', '1', '0', '8', '0', '0', '', '');
INSERT INTO `yzn_model_field` VALUES ('12', '1', 'thumb', '缩略图', '', '', '0', '100', '', '', 'image', 'a:9:{s:4:\"size\";s:2:\"50\";s:12:\"defaultvalue\";s:0:\"\";s:9:\"show_type\";s:1:\"1\";s:14:\"upload_maxsize\";s:4:\"1024\";s:15:\"upload_allowext\";s:20:\"jpg|jpeg|gif|png|bmp\";s:9:\"watermark\";s:1:\"0\";s:13:\"isselectimage\";s:1:\"1\";s:12:\"images_width\";s:0:\"\";s:13:\"images_height\";s:0:\"\";}', '', '', '', '0', '1', '0', '0', '0', '1', '0', '1', '7', '0', '0', '', '');
INSERT INTO `yzn_model_field` VALUES ('13', '1', 'catid', '栏目', '', '', '1', '6', '/^[0-9]{1,6}$/', '请选择栏目', 'catid', 'a:1:{s:12:\"defaultvalue\";s:0:\"\";}', '', '-99', '-99', '0', '1', '0', '1', '1', '1', '0', '0', '1', '0', '0', '', '');
INSERT INTO `yzn_model_field` VALUES ('14', '1', 'typeid', '类别', '', '', '0', '0', '', '', 'typeid', 'a:2:{s:9:\"minnumber\";s:0:\"\";s:12:\"defaultvalue\";s:0:\"\";}', '', '', '', '1', '1', '0', '1', '1', '1', '0', '0', '2', '0', '0', '', '');
INSERT INTO `yzn_model_field` VALUES ('15', '1', 'title', '标题', '', 'inputtitle', '1', '80', '', '请输入标题', 'title', '', '', '', '', '0', '1', '0', '1', '1', '1', '1', '1', '3', '0', '0', '', '');
INSERT INTO `yzn_model_field` VALUES ('16', '1', 'keywords', '关键词', '多关键词之间用空格隔开', '', '0', '40', '', '', 'keyword', 'a:2:{s:4:\"size\";s:3:\"100\";s:12:\"defaultvalue\";s:0:\"\";}', '', '-99', '-99', '0', '1', '0', '1', '1', '1', '1', '0', '4', '0', '0', '', '');
INSERT INTO `yzn_model_field` VALUES ('17', '1', 'tags', 'TAGS', '多关之间用空格或者“,”隔开', '', '0', '0', '', '', 'tags', 'a:4:{s:12:\"backstagefun\";s:0:\"\";s:17:\"backstagefun_type\";s:1:\"1\";s:8:\"frontfun\";s:0:\"\";s:13:\"frontfun_type\";s:1:\"1\";}', '', '', '', '0', '1', '0', '1', '0', '0', '0', '0', '4', '0', '0', '', '');
INSERT INTO `yzn_model_field` VALUES ('18', '1', 'description', '摘要', '', '', '0', '255', '', '', 'textarea', 'a:4:{s:5:\"width\";s:2:\"98\";s:6:\"height\";s:2:\"46\";s:12:\"defaultvalue\";s:0:\"\";s:10:\"enablehtml\";s:1:\"0\";}', '', '', '', '0', '1', '0', '1', '0', '1', '1', '1', '5', '0', '0', '', '');
INSERT INTO `yzn_model_field` VALUES ('19', '1', 'updatetime', '发布时间', '', '', '0', '0', '', '', 'datetime', 'a:3:{s:9:\"fieldtype\";s:3:\"int\";s:6:\"format\";s:11:\"Y-m-d H:i:s\";s:11:\"defaulttype\";s:1:\"0\";}', '', '', '', '0', '1', '0', '0', '0', '0', '0', '0', '10', '0', '0', '', '');
INSERT INTO `yzn_model_field` VALUES ('20', '1', 'content', '内容', '<style type=\"text/css\">.content_attr{ border:1px solid #CCC; padding:5px 8px; background:#FFC; margin-top:6px}</style><div class=\"content_attr\"><label><input name=\"add_introduce\" type=\"checkbox\"  value=\"1\" checked>是否截取内容</label><input type=\"text\" name=\"introcude_length\" value=\"200\" size=\"3\">字符至内容摘要\r\n<label><input type=\'checkbox\' name=\'auto_thumb\' value=\"1\" checked>是否获取内容第</label><input type=\"text\" name=\"auto_thumb_no\" value=\"1\" size=\"2\" class=\"\">张图片作为标题图片\r\n</div>', '', '1', '999999', '', '内容不能为空', 'editor', 'a:6:{s:7:\"toolbar\";s:4:\"full\";s:12:\"defaultvalue\";s:0:\"\";s:13:\"enablekeylink\";s:1:\"1\";s:10:\"replacenum\";s:1:\"2\";s:9:\"link_mode\";s:1:\"0\";s:15:\"enablesaveimage\";s:1:\"1\";}', '', '', '', '0', '0', '0', '1', '0', '1', '1', '0', '6', '0', '0', '', '');
INSERT INTO `yzn_model_field` VALUES ('22', '2', 'name', '姓名', '', '', '0', '0', '', '', 'text', 'N;', '', '', '', '0', '1', '0', '0', '0', '1', '0', '0', '0', '0', '0', '', '');
INSERT INTO `yzn_model_field` VALUES ('23', '2', 'phone', '手机号码', '', '', '0', '0', '', '', 'text', 'N;', '', '', '', '0', '1', '0', '0', '0', '1', '0', '0', '0', '0', '0', '', '');
INSERT INTO `yzn_model_field` VALUES ('24', '2', 'email', '邮箱', '', '', '0', '0', '', '', 'text', 'N;', '', '', '', '0', '1', '0', '0', '0', '1', '0', '0', '0', '0', '0', '', '');
INSERT INTO `yzn_model_field` VALUES ('25', '2', 'message_content', '留言内容', '', '', '0', '0', '', '', 'textarea', 'N;', '', '', '', '0', '1', '0', '0', '0', '1', '0', '0', '0', '0', '0', '', '');

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
INSERT INTO `yzn_module` VALUES ('links', '友情链接', '960c30f9b119fa6c39a4a31867441c82', '0', '1', '1.0.0', '', '1505651640', '1505651640', '0');
INSERT INTO `yzn_module` VALUES ('formguide', '表单', 'b19cc279ed484c13c96c2f7142e2f437', '0', '1', '1.0.0', '', '1507204730', '1507204730', '0');

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
INSERT INTO `yzn_page` VALUES ('6', '关于我们', '', '', '<p style=\"margin-top: 0px; margin-bottom: 15px; padding: 0px; white-space: normal; box-sizing: inherit; line-height: 25px; word-break: break-word; color: rgb(64, 72, 91); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Helvetica, Arial, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Liberation Sans&quot;, &quot;PingFang SC&quot;, &quot;Microsoft YaHei&quot;, &quot;Hiragino Sans GB&quot;, &quot;Wenquanyi Micro Hei&quot;, &quot;WenQuanYi Zen Hei&quot;, &quot;ST Heiti&quot;, SimHei, &quot;WenQuanYi Zen Hei Sharp&quot;, sans-serif; font-size: 15px; background-color: rgb(255, 255, 255);\">[项目介绍]</p><pre style=\"margin-top: 0px; margin-bottom: 15px; padding: 6px 10px; font-family: Menlo, &quot;Liberation Mono&quot;, Consolas, &quot;DejaVu Sans Mono&quot;, &quot;Ubuntu Mono&quot;, &quot;Courier New&quot;, &quot;andale mono&quot;, &quot;lucida console&quot;, monospace; color: rgb(51, 51, 51); background-color: rgb(255, 255, 255); box-sizing: inherit; overflow: auto; border: 1px solid rgb(238, 238, 238); font-size: 13px; line-height: 19px; border-radius: 3px;\">Yzncms(又名御宅男cms)是完全开源的项目，基于ThinkPHP5.011最新版,框架易于功能扩展，代码维护，方便二次开发&nbsp;&nbsp;\r\n帮助开发者简单高效降低二次开发成本，满足专注业务深度开发的需求。</pre><p style=\"margin-top: 0px; margin-bottom: 15px; padding: 0px; white-space: normal; box-sizing: inherit; line-height: 25px; word-break: break-word; color: rgb(64, 72, 91); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Helvetica, Arial, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Liberation Sans&quot;, &quot;PingFang SC&quot;, &quot;Microsoft YaHei&quot;, &quot;Hiragino Sans GB&quot;, &quot;Wenquanyi Micro Hei&quot;, &quot;WenQuanYi Zen Hei&quot;, &quot;ST Heiti&quot;, SimHei, &quot;WenQuanYi Zen Hei Sharp&quot;, sans-serif; font-size: 15px; background-color: rgb(255, 255, 255);\">[功能介绍]</p><pre style=\"margin-top: 0px; margin-bottom: 15px; padding: 6px 10px; font-family: Menlo, &quot;Liberation Mono&quot;, Consolas, &quot;DejaVu Sans Mono&quot;, &quot;Ubuntu Mono&quot;, &quot;Courier New&quot;, &quot;andale mono&quot;, &quot;lucida console&quot;, monospace; color: rgb(51, 51, 51); background-color: rgb(255, 255, 255); box-sizing: inherit; overflow: auto; border: 1px solid rgb(238, 238, 238); font-size: 13px; line-height: 19px; border-radius: 3px;\">核心版本的YZNCMS会完成如下功能&nbsp;具体请下载体验\r\nAUTH权限：用户权限\r\n数据库管理：备份导入下载数据库等功能\r\n网站设置：设置网站基本设置邮箱设置等&nbsp;可用自定义设置几种字段\r\n自定义模型：可用创建下载模型，文章模型里面字段都可以自定义&nbsp;如编辑器，多图片，多附件...十几种字段选择\r\n*模块安装：如友情链接，自定义表单，论坛，商城,会员模块等\r\n*插件安装：如返回顶部，留言系统插件等</pre><p><br/></p>', '', '0');
INSERT INTO `yzn_page` VALUES ('7', '联系我们', '', '', '<p>联系我们<br/></p>', '', '0');

-- ----------------------------
-- Table structure for `yzn_position`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_position`;
CREATE TABLE `yzn_position` (
  `posid` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '推荐位id',
  `modelid` char(30) NOT NULL DEFAULT '' COMMENT '模型id',
  `catid` varchar(255) NOT NULL DEFAULT '' COMMENT '栏目id',
  `name` char(30) NOT NULL DEFAULT '' COMMENT '推荐位名称',
  `maxnum` smallint(5) NOT NULL DEFAULT '20' COMMENT '最大存储数据量',
  `extention` char(100) DEFAULT NULL,
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`posid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='推荐位';

-- ----------------------------
-- Records of yzn_position
-- ----------------------------
INSERT INTO `yzn_position` VALUES ('1', '1', '2,3,4', '热门文章推荐', '10', '', '0');

-- ----------------------------
-- Table structure for `yzn_position_data`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_position_data`;
CREATE TABLE `yzn_position_data` (
  `id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '栏目ID',
  `posid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '推荐位ID',
  `module` char(20) DEFAULT NULL COMMENT '模型',
  `modelid` smallint(6) unsigned DEFAULT '0' COMMENT '模型ID',
  `thumb` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否有缩略图',
  `data` mediumtext COMMENT '数据信息',
  `listorder` mediumint(8) DEFAULT '0' COMMENT '排序',
  `expiration` int(10) NOT NULL,
  `extention` char(30) DEFAULT NULL,
  `synedit` tinyint(1) DEFAULT '0' COMMENT '是否同步编辑',
  KEY `posid` (`posid`),
  KEY `listorder` (`listorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='推荐位数据表';

-- ----------------------------
-- Records of yzn_position_data
-- ----------------------------
INSERT INTO `yzn_position_data` VALUES ('5', '2', '1', 'content', '1', '0', 'a:6:{s:5:\"title\";s:36:\"如何加快网站建设的进度？\";s:11:\"description\";s:602:\"在网站建设中我们都会对每一个用户的网站规划一下建站的进度管理，并把每一个建站的进程分解成不同的阶段，然后把每一个建站阶段所需要的时间都列出来，并写入网站建设的协议中，以便让用户明白我们在建站的整个进度中各个阶段都是在做什么。通常来说，我们规划网站建设进度的时候，主要考虑三方面的因素：第一是用户对网站建设要求的急切程度;第二是要根据网站的复杂程度及其难度;第三就是我们技术人员的人手分配因素。这三个因素...\";s:9:\"inputtime\";i:1506252329;s:5:\"posid\";a:2:{i:0;s:2:\"-1\";i:1;s:1:\"1\";}s:3:\"url\";s:30:\"/home/index/shows/catid/2/id/5\";s:5:\"style\";s:0:\"\";}', '5', '0', '', '0');

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

-- ----------------------------
-- Table structure for `yzn_ucenter_member`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_ucenter_member`;
CREATE TABLE `yzn_ucenter_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` char(16) NOT NULL COMMENT '用户名',
  `password` char(32) NOT NULL COMMENT '密码',
  `email` char(32) NOT NULL COMMENT '用户邮箱',
  `mobile` char(15) NOT NULL COMMENT '用户手机',
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `reg_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '注册IP',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_login_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '最后登录IP',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) DEFAULT '0' COMMENT '用户状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Records of yzn_ucenter_member
-- ----------------------------
INSERT INTO `yzn_ucenter_member` VALUES ('1', 'ken678', 'e5e764feb83f21f7c578c6741dd148b0', '530765310@qq.com', '13402503587', '1508501243', '2130706433', '0', '0', '1508501243', '1');
