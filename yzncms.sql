/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : yzncms

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2018-11-29 14:21:02
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
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COMMENT='插件表';

-- ----------------------------
-- Records of yzn_addons
-- ----------------------------
INSERT INTO `yzn_addons` VALUES ('16', 'returntop', '返回顶部', '回到顶部美化，随机或指定显示，100款样式，每天一种换，天天都用新样式', '1', '{\"random\":\"0\",\"current\":\"1\"}', '御宅男', '1.0.0', '0', '0');

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
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='管理员表';

-- ----------------------------
-- Records of yzn_admin
-- ----------------------------
INSERT INTO `yzn_admin` VALUES ('1', 'admin', '9724b5e6c56b95f5723009ef81961bfe', '1', 'Wo0bAa', '御宅男', '1543470979', '2130706433', '530765310@qq.com', '1');
INSERT INTO `yzn_admin` VALUES ('2', 'ken678', '932e31f030b850a87702a86c0e16db16', '4', 'Sxq6dR', '御宅男', '1542781151', '2130706433', '530765310@qq.com', '1');

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
) ENGINE=MyISAM AUTO_INCREMENT=452 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yzn_adminlog
-- ----------------------------
INSERT INTO `yzn_adminlog` VALUES ('218', '1', '1', '提示语:文件删除成功~', '1540255800', '2130706433', '/attachment/attachments/delete.html?id=82');
INSERT INTO `yzn_adminlog` VALUES ('217', '0', '1', '提示语:文件数据库记录已不存在~', '1540255738', '2130706433', '/attachment/attachments/delete.html?id=85');
INSERT INTO `yzn_adminlog` VALUES ('216', '0', '1', '提示语:测试', '1540255475', '2130706433', '/attachment/attachments/delete.html?id=85');
INSERT INTO `yzn_adminlog` VALUES ('215', '0', '1', '提示语:测试', '1540255469', '2130706433', '/attachment/attachments/delete.html?id=85');
INSERT INTO `yzn_adminlog` VALUES ('213', '0', '0', null, '1540255385', '0', '');
INSERT INTO `yzn_adminlog` VALUES ('214', '0', '1', '提示语:测试', '1540255399', '2130706433', '/attachment/attachments/delete.html?id=85');
INSERT INTO `yzn_adminlog` VALUES ('212', '1', '1', '提示语:恭喜您，登陆成功', '1540252896', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('211', '0', '0', '提示语:请先登陆', '1540252887', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('210', '1', '1', '提示语:恭喜您，登陆成功', '1540203404', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('209', '0', '0', '提示语:请先登陆', '1540203399', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('423', '0', '2', '提示语:未授权访问!', '1542781169', '2130706433', '/admin/adminlog/deletelog.html');
INSERT INTO `yzn_adminlog` VALUES ('420', '1', '0', '提示语:注销成功！', '1542781143', '2130706433', '/admin/index/logout.html');
INSERT INTO `yzn_adminlog` VALUES ('421', '1', '2', '提示语:恭喜您，登陆成功', '1542781151', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('422', '0', '2', '提示语:未授权访问!', '1542781157', '2130706433', '/attachment/ueditor/run.html?action=config&&noCache=1542781157366');
INSERT INTO `yzn_adminlog` VALUES ('429', '0', '1', '提示语:该页面不存在！', '1542869351', '2130706433', '/addons/addons/hooks/menuid/34.html');
INSERT INTO `yzn_adminlog` VALUES ('428', '0', '1', '提示语:该页面不存在！', '1542869342', '2130706433', '/addons/addons/hooks/menuid/34.html');
INSERT INTO `yzn_adminlog` VALUES ('427', '1', '1', '提示语:恭喜您，登陆成功', '1542869084', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('426', '0', '0', '提示语:请先登陆', '1542869079', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('425', '1', '1', '提示语:恭喜您，登陆成功', '1542868987', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('424', '0', '0', '提示语:请先登陆', '1542868981', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('418', '1', '1', '提示语:恭喜您，登陆成功', '1542780457', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('417', '0', '0', '提示语:请先登陆', '1542780450', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('416', '0', '2', '提示语:未授权访问!', '1542155338', '2130706433', '/admin/manager/add.html');
INSERT INTO `yzn_adminlog` VALUES ('415', '0', '2', '提示语:未授权访问!', '1542155335', '2130706433', '/admin/auth_manager/access.html?title=%E8%B6%85%E7%BA%A7%E7%AE%A1%E7%90%86%E5%91%98&group_id=1');
INSERT INTO `yzn_adminlog` VALUES ('414', '1', '2', '提示语:文件删除成功~', '1542155323', '2130706433', '/attachment/attachments/delete.html');
INSERT INTO `yzn_adminlog` VALUES ('413', '0', '2', '提示语:菜单名称不能为空', '1542155314', '2130706433', '/admin/menu/add.html');
INSERT INTO `yzn_adminlog` VALUES ('412', '0', '2', '提示语:未授权访问!', '1542155303', '2130706433', '/attachment/ueditor/run.html?action=config&&noCache=1542155303255');
INSERT INTO `yzn_adminlog` VALUES ('411', '1', '2', '提示语:恭喜您，登陆成功', '1542155297', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('410', '1', '0', '提示语:注销成功！', '1542155290', '2130706433', '/admin/index/logout.html');
INSERT INTO `yzn_adminlog` VALUES ('409', '1', '1', '提示语:操作成功!', '1542155287', '2130706433', '/admin/auth_manager/writegroup.html');
INSERT INTO `yzn_adminlog` VALUES ('408', '1', '1', '提示语:操作成功!', '1542155274', '2130706433', '/admin/auth_manager/writegroup.html');
INSERT INTO `yzn_adminlog` VALUES ('407', '1', '1', '提示语:恭喜您，登陆成功', '1542155230', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('406', '1', '0', '提示语:注销成功！', '1542155222', '2130706433', '/admin/index/logout.html');
INSERT INTO `yzn_adminlog` VALUES ('405', '0', '2', '提示语:未授权访问!', '1542155147', '2130706433', '/attachment/attachments/upload/dir/images/module/admin.html');
INSERT INTO `yzn_adminlog` VALUES ('404', '0', '2', '提示语:未授权访问!', '1542155074', '2130706433', '/attachment/attachments/upload/dir/images/module/admin.html');
INSERT INTO `yzn_adminlog` VALUES ('403', '0', '2', '提示语:未授权访问!', '1542155010', '2130706433', '/attachment/attachments/upload/dir/images/module/admin.html');
INSERT INTO `yzn_adminlog` VALUES ('402', '0', '2', '提示语:未授权访问!', '1542154999', '2130706433', '/attachment/attachments/upload/dir/images/module/admin.html');
INSERT INTO `yzn_adminlog` VALUES ('401', '0', '2', '提示语:未授权访问!', '1542154973', '2130706433', '/admin/adminlog/deletelog.html');
INSERT INTO `yzn_adminlog` VALUES ('400', '0', '2', '提示语:未授权访问!', '1542154859', '2130706433', '/admin/menu/edit.html?id=1');
INSERT INTO `yzn_adminlog` VALUES ('399', '0', '2', '提示语:未授权访问!', '1542154755', '2130706433', '/admin/menu/add.html');
INSERT INTO `yzn_adminlog` VALUES ('398', '0', '2', '提示语:未授权访问!', '1542154753', '2130706433', '/attachment/ueditor/run.html?action=config&&noCache=1542154752951');
INSERT INTO `yzn_adminlog` VALUES ('397', '1', '2', '提示语:恭喜您，登陆成功', '1542154746', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('396', '0', '0', '提示语:请先登陆', '1542154736', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('395', '0', '2', '提示语:未授权访问!', '1542068974', '2130706433', '/admin/menu/edit.html?id=1');
INSERT INTO `yzn_adminlog` VALUES ('394', '0', '2', '提示语:未授权访问!', '1542068971', '2130706433', '/admin/menu/add.html?parentid=1');
INSERT INTO `yzn_adminlog` VALUES ('393', '0', '2', '提示语:未授权访问!', '1542068968', '2130706433', '/admin/menu/add.html');
INSERT INTO `yzn_adminlog` VALUES ('392', '0', '2', '提示语:未授权访问!', '1542068966', '2130706433', '/attachment/ueditor/run.html?action=config&&noCache=1542068966030');
INSERT INTO `yzn_adminlog` VALUES ('391', '1', '2', '提示语:设置更新成功', '1542068964', '2130706433', '/admin/config/setting/group/base.html');
INSERT INTO `yzn_adminlog` VALUES ('390', '0', '2', '提示语:未授权访问!', '1542068958', '2130706433', '/attachment/ueditor/run.html?action=config&&noCache=1542068958232');
INSERT INTO `yzn_adminlog` VALUES ('389', '1', '2', '提示语:配置编辑成功~', '1542068954', '2130706433', '/admin/config/edit/id/1.html');
INSERT INTO `yzn_adminlog` VALUES ('388', '1', '2', '提示语:恭喜您，登陆成功', '1542068941', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('387', '1', '0', '提示语:注销成功！', '1542068933', '2130706433', '/admin/index/logout.html');
INSERT INTO `yzn_adminlog` VALUES ('386', '1', '1', '提示语:操作成功!', '1542068931', '2130706433', '/admin/auth_manager/writegroup.html');
INSERT INTO `yzn_adminlog` VALUES ('385', '1', '1', '提示语:恭喜您，登陆成功', '1542068921', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('384', '1', '0', '提示语:注销成功！', '1542068915', '2130706433', '/admin/index/logout.html');
INSERT INTO `yzn_adminlog` VALUES ('383', '0', '2', '提示语:未授权访问!', '1542068912', '2130706433', '/admin/config/edit.html?id=1');
INSERT INTO `yzn_adminlog` VALUES ('382', '0', '2', '提示语:未授权访问!', '1542068910', '2130706433', '/admin/config/add.html');
INSERT INTO `yzn_adminlog` VALUES ('381', '0', '2', '提示语:未授权访问!', '1542068904', '2130706433', '/attachment/ueditor/run.html?action=config&&noCache=1542068904364');
INSERT INTO `yzn_adminlog` VALUES ('380', '1', '2', '提示语:恭喜您，登陆成功', '1542068900', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('379', '1', '0', '提示语:注销成功！', '1542068893', '2130706433', '/admin/index/logout.html');
INSERT INTO `yzn_adminlog` VALUES ('378', '1', '1', '提示语:恭喜您，登陆成功', '1542068582', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('377', '0', '0', '提示语:请先登陆', '1542068575', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('376', '0', '2', '提示语:未授权访问!', '1542067951', '2130706433', '/attachment/attachments/delete.html');
INSERT INTO `yzn_adminlog` VALUES ('375', '0', '2', '提示语:未授权访问!', '1542067942', '2130706433', '/attachment/ueditor/run.html?action=config&&noCache=1542067942745');
INSERT INTO `yzn_adminlog` VALUES ('374', '0', '2', '提示语:未授权访问!', '1542067941', '2130706433', '/admin/config/add.html');
INSERT INTO `yzn_adminlog` VALUES ('373', '0', '2', '提示语:未授权访问!', '1542067938', '2130706433', '/admin/adminlog/deletelog.html');
INSERT INTO `yzn_adminlog` VALUES ('372', '0', '2', '提示语:未授权访问!', '1542067931', '2130706433', '/admin/auth_manager/access.html?title=%E7%BC%96%E8%BE%91&group_id=4');
INSERT INTO `yzn_adminlog` VALUES ('371', '1', '2', '提示语:恭喜您，登陆成功', '1542067924', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('370', '1', '0', '提示语:注销成功！', '1542067917', '2130706433', '/admin/index/logout.html');
INSERT INTO `yzn_adminlog` VALUES ('369', '1', '1', '提示语:操作成功!', '1542067911', '2130706433', '/admin/auth_manager/writegroup.html');
INSERT INTO `yzn_adminlog` VALUES ('368', '1', '1', '提示语:恭喜您，登陆成功', '1542067850', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('367', '1', '0', '提示语:注销成功！', '1542067845', '2130706433', '/admin/index/logout.html');
INSERT INTO `yzn_adminlog` VALUES ('366', '0', '2', '提示语:未授权访问!', '1542067838', '2130706433', '/admin/manager/add.html');
INSERT INTO `yzn_adminlog` VALUES ('365', '0', '2', '提示语:未授权访问!', '1542067824', '2130706433', '/attachment/attachments/index/menuid/23.html');
INSERT INTO `yzn_adminlog` VALUES ('364', '0', '2', '提示语:未授权访问!', '1542067822', '2130706433', '/attachment/ueditor/run.html?action=config&&noCache=1542067822165');
INSERT INTO `yzn_adminlog` VALUES ('363', '1', '2', '提示语:恭喜您，登陆成功', '1542067817', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('362', '0', '0', '提示语:密码错误！', '1542067814', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('361', '0', '0', '提示语:请先登陆', '1542067805', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('360', '1', '1', '提示语:设置更新成功', '1542015553', '2130706433', '/admin/config/setting/group/upload.html');
INSERT INTO `yzn_adminlog` VALUES ('359', '1', '1', '提示语:设置更新成功', '1542015509', '2130706433', '/admin/config/setting/group/upload.html');
INSERT INTO `yzn_adminlog` VALUES ('358', '1', '1', '提示语:恭喜您，登陆成功', '1542015284', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('357', '0', '0', '提示语:请先登陆', '1542015278', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('355', '0', '0', '提示语:请先登陆', '1542008175', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('356', '1', '1', '提示语:恭喜您，登陆成功', '1542008181', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('354', '1', '1', '提示语:设置更新成功', '1541981959', '2130706433', '/admin/config/setting/group/upload.html');
INSERT INTO `yzn_adminlog` VALUES ('353', '1', '1', '提示语:设置更新成功', '1541981861', '2130706433', '/admin/config/setting/group/upload.html');
INSERT INTO `yzn_adminlog` VALUES ('352', '1', '1', '提示语:恭喜您，登陆成功', '1541981490', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('351', '0', '0', '提示语:请先登陆', '1541981484', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('350', '1', '1', '提示语:配置编辑成功~', '1541756888', '2130706433', '/admin/config/edit/id/151.html');
INSERT INTO `yzn_adminlog` VALUES ('349', '1', '1', '提示语:配置编辑成功~', '1541756875', '2130706433', '/admin/config/edit/id/14.html');
INSERT INTO `yzn_adminlog` VALUES ('348', '1', '1', '提示语:配置编辑成功~', '1541756856', '2130706433', '/admin/config/edit/id/13.html');
INSERT INTO `yzn_adminlog` VALUES ('347', '1', '1', '提示语:配置编辑成功~', '1541756842', '2130706433', '/admin/config/edit/id/12.html');
INSERT INTO `yzn_adminlog` VALUES ('346', '1', '1', '提示语:恭喜您，登陆成功', '1541756806', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('345', '0', '0', '提示语:请先登陆', '1541756800', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('344', '1', '1', '提示语:配置编辑成功~', '1541755158', '2130706433', '/admin/config/edit/id/151.html');
INSERT INTO `yzn_adminlog` VALUES ('343', '1', '1', '提示语:配置编辑成功~', '1541755151', '2130706433', '/admin/config/edit/id/151.html');
INSERT INTO `yzn_adminlog` VALUES ('342', '1', '1', '提示语:配置编辑成功~', '1541755146', '2130706433', '/admin/config/edit/id/151.html');
INSERT INTO `yzn_adminlog` VALUES ('341', '1', '1', '提示语:设置更新成功', '1541755061', '2130706433', '/admin/config/setting/group/upload.html');
INSERT INTO `yzn_adminlog` VALUES ('340', '1', '1', '提示语:设置更新成功', '1541755051', '2130706433', '/admin/config/setting/group/upload.html');
INSERT INTO `yzn_adminlog` VALUES ('339', '1', '1', '提示语:配置编辑成功~', '1541755045', '2130706433', '/admin/config/edit/id/151.html');
INSERT INTO `yzn_adminlog` VALUES ('338', '1', '1', '提示语:配置添加成功~', '1541752781', '2130706433', '/admin/config/add.html');
INSERT INTO `yzn_adminlog` VALUES ('337', '0', '1', '提示语:配置标题只能是汉字、字母和数字', '1541752773', '2130706433', '/admin/config/add.html');
INSERT INTO `yzn_adminlog` VALUES ('336', '1', '1', '提示语:配置添加成功~', '1541752393', '2130706433', '/admin/config/add.html');
INSERT INTO `yzn_adminlog` VALUES ('335', '1', '1', '提示语:配置添加成功~', '1541752356', '2130706433', '/admin/config/add.html');
INSERT INTO `yzn_adminlog` VALUES ('333', '0', '0', '提示语:请先登陆', '1541752231', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('334', '1', '1', '提示语:恭喜您，登陆成功', '1541752238', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('332', '1', '1', '提示语:文件删除成功~', '1541722099', '2130706433', '/attachment/attachments/delete.html');
INSERT INTO `yzn_adminlog` VALUES ('331', '1', '1', '提示语:删除日志成功！', '1541722081', '2130706433', '/admin/adminlog/deletelog.html');
INSERT INTO `yzn_adminlog` VALUES ('419', '1', '1', '提示语:删除日志成功！', '1542780639', '2130706433', '/admin/adminlog/deletelog.html');
INSERT INTO `yzn_adminlog` VALUES ('219', '0', '0', '提示语:请先登陆', '1540259868', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('220', '0', '0', '提示语:密码错误！', '1540259875', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('221', '1', '1', '提示语:恭喜您，登陆成功', '1540259877', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('222', '0', '1', '提示语:没有勾选需要删除的文件~', '1540260613', '2130706433', '/attachment/attachments/delete.html');
INSERT INTO `yzn_adminlog` VALUES ('223', '0', '1', '提示语:没有勾选需要删除的文件~', '1540260694', '2130706433', '/attachment/attachments/delete.html');
INSERT INTO `yzn_adminlog` VALUES ('224', '0', '1', '提示语:没有勾选需要删除的文件~', '1540260701', '2130706433', '/attachment/attachments/delete.html');
INSERT INTO `yzn_adminlog` VALUES ('225', '1', '1', '提示语:文件删除成功~', '1540260751', '2130706433', '/attachment/attachments/delete.html');
INSERT INTO `yzn_adminlog` VALUES ('226', '1', '1', '提示语:文件删除成功~', '1540260756', '2130706433', '/attachment/attachments/delete.html');
INSERT INTO `yzn_adminlog` VALUES ('227', '1', '1', '提示语:文件删除成功~', '1540260782', '2130706433', '/attachment/attachments/delete.html');
INSERT INTO `yzn_adminlog` VALUES ('228', '1', '1', '提示语:文件删除成功~', '1540260788', '2130706433', '/attachment/attachments/delete.html');
INSERT INTO `yzn_adminlog` VALUES ('229', '0', '0', '提示语:请先登陆', '1540261141', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('230', '1', '1', '提示语:恭喜您，登陆成功', '1540261146', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('231', '1', '1', '提示语:文件删除成功~', '1540261195', '2130706433', '/attachment/attachments/delete.html?id=86');
INSERT INTO `yzn_adminlog` VALUES ('232', '0', '0', '提示语:请先登陆', '1540263113', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('233', '1', '1', '提示语:恭喜您，登陆成功', '1540263118', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('234', '1', '1', '提示语:文件删除成功~', '1540263191', '2130706433', '/attachment/attachments/delete.html');
INSERT INTO `yzn_adminlog` VALUES ('235', '1', '1', '提示语:文件删除成功~', '1540263817', '2130706433', '/attachment/attachments/delete.html');
INSERT INTO `yzn_adminlog` VALUES ('236', '1', '1', '提示语:删除日志成功！', '1540265182', '2130706433', '/admin/adminlog/deletelog.html');
INSERT INTO `yzn_adminlog` VALUES ('237', '1', '1', '提示语:删除日志成功！', '1540265205', '2130706433', '/admin/adminlog/deletelog.html');
INSERT INTO `yzn_adminlog` VALUES ('238', '1', '1', '提示语:删除日志成功！', '1540265221', '2130706433', '/admin/adminlog/deletelog.html');
INSERT INTO `yzn_adminlog` VALUES ('239', '0', '0', '提示语:请先登陆', '1540265505', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('240', '1', '1', '提示语:恭喜您，登陆成功', '1540265510', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('241', '0', '1', '提示语:该页面不存在！', '1540266004', '2130706433', '/attachment/attachments/check.html');
INSERT INTO `yzn_adminlog` VALUES ('242', '1', '1', '提示语:文件删除成功~', '1540270296', '2130706433', '/attachment/attachments/delete.html');
INSERT INTO `yzn_adminlog` VALUES ('243', '1', '1', '提示语:文件删除成功~', '1540270308', '2130706433', '/attachment/attachments/delete.html');
INSERT INTO `yzn_adminlog` VALUES ('244', '0', '0', '提示语:请先登陆', '1540270607', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('245', '1', '1', '提示语:恭喜您，登陆成功', '1540270612', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('246', '1', '1', '提示语:配置添加成功~', '1540270653', '2130706433', '/admin/config/add.html');
INSERT INTO `yzn_adminlog` VALUES ('247', '0', '0', '提示语:请先登陆', '1540271141', '2130706433', '/admin');
INSERT INTO `yzn_adminlog` VALUES ('248', '1', '1', '提示语:恭喜您，登陆成功', '1540271147', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('249', '1', '1', '提示语:文件删除成功~', '1540271154', '2130706433', '/attachment/attachments/delete.html');
INSERT INTO `yzn_adminlog` VALUES ('250', '1', '1', '提示语:文件删除成功~', '1540271158', '2130706433', '/attachment/attachments/delete.html');
INSERT INTO `yzn_adminlog` VALUES ('251', '0', '0', '提示语:请先登陆', '1540271867', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('252', '1', '1', '提示语:恭喜您，登陆成功', '1540271872', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('253', '0', '1', '提示语:该页面不存在！', '1540271886', '2130706433', '/admin/config/delete.html');
INSERT INTO `yzn_adminlog` VALUES ('254', '0', '1', '提示语:该页面不存在！', '1540271896', '2130706433', '/admin/config/delete.html');
INSERT INTO `yzn_adminlog` VALUES ('255', '0', '1', '提示语:该页面不存在！', '1540271915', '2130706433', '/admin/config/delete.html');
INSERT INTO `yzn_adminlog` VALUES ('256', '1', '1', '提示语:删除成功', '1540271964', '2130706433', '/admin/config/del.html');
INSERT INTO `yzn_adminlog` VALUES ('257', '1', '1', '提示语:文件删除成功~', '1540271999', '2130706433', '/attachment/attachments/delete.html');
INSERT INTO `yzn_adminlog` VALUES ('258', '0', '0', '提示语:请先登陆', '1540339872', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('259', '1', '1', '提示语:恭喜您，登陆成功', '1540339878', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('260', '1', '1', '提示语:设置更新成功', '1540339988', '2130706433', '/admin/config/setting/group/base.html');
INSERT INTO `yzn_adminlog` VALUES ('261', '1', '1', '提示语:设置更新成功', '1540340036', '2130706433', '/admin/config/setting/group/base.html');
INSERT INTO `yzn_adminlog` VALUES ('262', '1', '1', '提示语:设置更新成功', '1540340064', '2130706433', '/admin/config/setting/group/base.html');
INSERT INTO `yzn_adminlog` VALUES ('263', '1', '1', '提示语:设置更新成功', '1540340194', '2130706433', '/admin/config/setting/group/base.html');
INSERT INTO `yzn_adminlog` VALUES ('264', '0', '0', '提示语:请先登陆', '1540341504', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('265', '1', '1', '提示语:恭喜您，登陆成功', '1540341508', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('266', '1', '1', '提示语:配置添加成功~', '1540341793', '2130706433', '/admin/config/add.html');
INSERT INTO `yzn_adminlog` VALUES ('267', '1', '1', '提示语:设置更新成功', '1540341806', '2130706433', '/admin/config/setting/group/base.html');
INSERT INTO `yzn_adminlog` VALUES ('268', '1', '1', '提示语:设置更新成功', '1540341823', '2130706433', '/admin/config/setting/group/base.html');
INSERT INTO `yzn_adminlog` VALUES ('269', '1', '1', '提示语:设置更新成功', '1540341836', '2130706433', '/admin/config/setting/group/base.html');
INSERT INTO `yzn_adminlog` VALUES ('270', '1', '1', '提示语:设置更新成功', '1540341900', '2130706433', '/admin/config/setting/group/base.html');
INSERT INTO `yzn_adminlog` VALUES ('271', '1', '1', '提示语:设置更新成功', '1540342002', '2130706433', '/admin/config/setting/group/base.html');
INSERT INTO `yzn_adminlog` VALUES ('272', '1', '1', '提示语:设置更新成功', '1540342014', '2130706433', '/admin/config/setting/group/base.html');
INSERT INTO `yzn_adminlog` VALUES ('273', '0', '0', '提示语:请先登陆', '1540356649', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('274', '1', '1', '提示语:恭喜您，登陆成功', '1540356655', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('275', '1', '1', '提示语:设置更新成功', '1540356674', '2130706433', '/admin/config/setting/group/base.html');
INSERT INTO `yzn_adminlog` VALUES ('276', '1', '1', '提示语:设置更新成功', '1540356735', '2130706433', '/admin/config/setting/group/base.html');
INSERT INTO `yzn_adminlog` VALUES ('277', '1', '1', '提示语:设置更新成功', '1540356780', '2130706433', '/admin/config/setting/group/base.html');
INSERT INTO `yzn_adminlog` VALUES ('278', '1', '1', '提示语:设置更新成功', '1540356790', '2130706433', '/admin/config/setting/group/base.html');
INSERT INTO `yzn_adminlog` VALUES ('279', '1', '1', '提示语:设置更新成功', '1540356796', '2130706433', '/admin/config/setting/group/base.html');
INSERT INTO `yzn_adminlog` VALUES ('280', '1', '1', '提示语:设置更新成功', '1540356805', '2130706433', '/admin/config/setting/group/base.html');
INSERT INTO `yzn_adminlog` VALUES ('281', '1', '1', '提示语:设置更新成功', '1540356828', '2130706433', '/admin/config/setting/group/base.html');
INSERT INTO `yzn_adminlog` VALUES ('282', '1', '1', '提示语:设置更新成功', '1540356834', '2130706433', '/admin/config/setting/group/base.html');
INSERT INTO `yzn_adminlog` VALUES ('283', '1', '1', '提示语:设置更新成功', '1540356840', '2130706433', '/admin/config/setting/group/base.html');
INSERT INTO `yzn_adminlog` VALUES ('284', '1', '1', '提示语:设置更新成功', '1540356861', '2130706433', '/admin/config/setting/group/base.html');
INSERT INTO `yzn_adminlog` VALUES ('285', '1', '1', '提示语:设置更新成功', '1540356895', '2130706433', '/admin/config/setting/group/base.html');
INSERT INTO `yzn_adminlog` VALUES ('286', '1', '1', '提示语:设置更新成功', '1540356909', '2130706433', '/admin/config/setting/group/base.html');
INSERT INTO `yzn_adminlog` VALUES ('287', '1', '1', '提示语:设置更新成功', '1540356915', '2130706433', '/admin/config/setting/group/base.html');
INSERT INTO `yzn_adminlog` VALUES ('288', '1', '1', '提示语:设置更新成功', '1540356923', '2130706433', '/admin/config/setting/group/base.html');
INSERT INTO `yzn_adminlog` VALUES ('289', '1', '1', '提示语:设置更新成功', '1540356931', '2130706433', '/admin/config/setting/group/base.html');
INSERT INTO `yzn_adminlog` VALUES ('290', '1', '1', '提示语:设置更新成功', '1540356992', '2130706433', '/admin/config/setting/group/base.html');
INSERT INTO `yzn_adminlog` VALUES ('291', '1', '1', '提示语:设置更新成功', '1540356999', '2130706433', '/admin/config/setting/group/base.html');
INSERT INTO `yzn_adminlog` VALUES ('292', '1', '1', '提示语:设置更新成功', '1540357003', '2130706433', '/admin/config/setting/group/base.html');
INSERT INTO `yzn_adminlog` VALUES ('293', '1', '1', '提示语:设置更新成功', '1540357010', '2130706433', '/admin/config/setting/group/base.html');
INSERT INTO `yzn_adminlog` VALUES ('294', '0', '0', '提示语:请先登陆', '1540362700', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('295', '0', '0', '提示语:密码错误！', '1540362713', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('296', '1', '1', '提示语:恭喜您，登陆成功', '1540362716', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('297', '1', '1', '提示语:设置更新成功', '1540362921', '2130706433', '/admin/config/setting/group/base.html');
INSERT INTO `yzn_adminlog` VALUES ('298', '1', '1', '提示语:设置更新成功', '1540362975', '2130706433', '/admin/config/setting/group/base.html');
INSERT INTO `yzn_adminlog` VALUES ('299', '1', '1', '提示语:设置更新成功', '1540363709', '2130706433', '/admin/config/setting/group/base.html');
INSERT INTO `yzn_adminlog` VALUES ('300', '1', '1', '提示语:设置更新成功', '1540363834', '2130706433', '/admin/config/setting/group/base.html');
INSERT INTO `yzn_adminlog` VALUES ('301', '0', '0', '提示语:请先登陆', '1540425533', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('302', '1', '1', '提示语:恭喜您，登陆成功', '1540425540', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('303', '1', '1', '提示语:设置更新成功', '1540425611', '2130706433', '/admin/config/setting/group/base.html');
INSERT INTO `yzn_adminlog` VALUES ('304', '1', '1', '提示语:设置更新成功', '1540426859', '2130706433', '/admin/config/setting/group/base.html');
INSERT INTO `yzn_adminlog` VALUES ('305', '0', '0', '提示语:请先登陆', '1540437003', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('306', '1', '1', '提示语:恭喜您，登陆成功', '1540437008', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('307', '1', '1', '提示语:设置更新成功', '1540437020', '2130706433', '/admin/config/setting/group/base.html');
INSERT INTO `yzn_adminlog` VALUES ('308', '1', '1', '提示语:文件删除成功~', '1540437034', '2130706433', '/attachment/attachments/delete.html');
INSERT INTO `yzn_adminlog` VALUES ('309', '0', '0', '提示语:请先登陆', '1540456566', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('310', '1', '1', '提示语:恭喜您，登陆成功', '1540456572', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('311', '0', '0', '提示语:请先登陆', '1540457580', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('312', '1', '1', '提示语:恭喜您，登陆成功', '1540457588', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('313', '1', '1', '提示语:配置添加成功~', '1540457656', '2130706433', '/admin/config/add.html');
INSERT INTO `yzn_adminlog` VALUES ('314', '1', '1', '提示语:配置添加成功~', '1540457767', '2130706433', '/admin/config/add.html');
INSERT INTO `yzn_adminlog` VALUES ('315', '1', '1', '提示语:配置编辑成功~', '1540457794', '2130706433', '/admin/config/edit/id/13.html');
INSERT INTO `yzn_adminlog` VALUES ('316', '1', '1', '提示语:配置编辑成功~', '1540457800', '2130706433', '/admin/config/edit/id/12.html');
INSERT INTO `yzn_adminlog` VALUES ('317', '0', '0', '提示语:请先登陆', '1540458672', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('318', '1', '1', '提示语:恭喜您，登陆成功', '1540458679', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('319', '0', '0', '提示语:请先登陆', '1540545956', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('320', '1', '1', '提示语:恭喜您，登陆成功', '1540545962', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('321', '0', '0', '提示语:请先登陆', '1540974595', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('322', '1', '1', '提示语:恭喜您，登陆成功', '1540974601', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('323', '1', '1', '提示语:设置更新成功', '1540974766', '2130706433', '/admin/config/setting/group/upload.html');
INSERT INTO `yzn_adminlog` VALUES ('324', '1', '1', '提示语:设置更新成功', '1540975099', '2130706433', '/admin/config/setting/group/upload.html');
INSERT INTO `yzn_adminlog` VALUES ('325', '1', '1', '提示语:设置更新成功', '1540975114', '2130706433', '/admin/config/setting/group/upload.html');
INSERT INTO `yzn_adminlog` VALUES ('326', '1', '1', '提示语:设置更新成功', '1540975124', '2130706433', '/admin/config/setting/group/upload.html');
INSERT INTO `yzn_adminlog` VALUES ('327', '1', '1', '提示语:设置更新成功', '1540976002', '2130706433', '/admin/config/setting/group/upload.html');
INSERT INTO `yzn_adminlog` VALUES ('328', '1', '1', '提示语:设置更新成功', '1540976343', '2130706433', '/admin/config/setting/group/upload.html');
INSERT INTO `yzn_adminlog` VALUES ('329', '0', '0', '提示语:请先登陆', '1541721953', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('330', '1', '1', '提示语:恭喜您，登陆成功', '1541721959', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('430', '0', '0', '提示语:请先登陆', '1543237075', '2130706433', '/admin');
INSERT INTO `yzn_adminlog` VALUES ('431', '1', '1', '提示语:恭喜您，登陆成功', '1543237083', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('432', '0', '0', '提示语:请先登陆', '1543391624', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('433', '1', '1', '提示语:恭喜您，登陆成功', '1543391629', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('434', '1', '1', '提示语:设置更新成功', '1543391643', '2130706433', '/admin/config/setting/group/base.html');
INSERT INTO `yzn_adminlog` VALUES ('435', '1', '1', '提示语:文件删除成功~', '1543391649', '2130706433', '/attachment/attachments/delete.html');
INSERT INTO `yzn_adminlog` VALUES ('436', '0', '0', '提示语:请先登陆', '1543394600', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('437', '1', '1', '提示语:恭喜您，登陆成功', '1543394606', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('438', '0', '0', '提示语:请先登陆', '1543398908', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('439', '1', '1', '提示语:恭喜您，登陆成功', '1543398913', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('440', '0', '0', '提示语:请先登陆', '1543451944', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('441', '1', '1', '提示语:恭喜您，登陆成功', '1543451949', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('442', '1', '1', '提示语:设置更新成功', '1543452393', '2130706433', '/admin/config/setting/group/base.html');
INSERT INTO `yzn_adminlog` VALUES ('443', '1', '1', '提示语:文件删除成功~', '1543452402', '2130706433', '/attachment/attachments/delete.html');
INSERT INTO `yzn_adminlog` VALUES ('444', '0', '0', '提示语:请先登陆', '1543465031', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('445', '1', '1', '提示语:恭喜您，登陆成功', '1543465039', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('446', '0', '0', '提示语:请先登陆', '1543465329', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('447', '1', '1', '提示语:恭喜您，登陆成功', '1543465335', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('448', '0', '0', '提示语:请先登陆', '1543470974', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('449', '1', '1', '提示语:恭喜您，登陆成功', '1543470979', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('450', '1', '1', '提示语:插件安装成功！', '1543472266', '2130706433', '/addons/addons/install.html');
INSERT INTO `yzn_adminlog` VALUES ('451', '1', '1', '提示语:插件安装成功！', '1543472414', '2130706433', '/addons/addons/install.html');

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
) ENGINE=MyISAM AUTO_INCREMENT=236 DEFAULT CHARSET=utf8 COMMENT='附件表';

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
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='权限组表';

-- ----------------------------
-- Records of yzn_auth_group
-- ----------------------------
INSERT INTO `yzn_auth_group` VALUES ('1', 'admin', '1', '超级管理员', '拥有所有权限', '1', '154,155,146,149,149,158,159,160,150,151,161,162,163,157,164,165,152,152,153,156,147,148');
INSERT INTO `yzn_auth_group` VALUES ('4', 'admin', '1', '编辑', '编辑', '1', '154,155,146,149,149,158,159,160,150,151,161,162,163,157,164,165,152,152,153,156,147,148');

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
) ENGINE=MyISAM AUTO_INCREMENT=166 DEFAULT CHARSET=utf8 COMMENT='规则表';

-- ----------------------------
-- Records of yzn_auth_rule
-- ----------------------------
INSERT INTO `yzn_auth_rule` VALUES ('146', 'admin', '2', 'admin/setting/index', '设置', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('147', 'admin', '2', 'admin/module/index', '模块', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('148', 'admin', '2', 'admin/addons/index', '扩展', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('149', 'admin', '1', 'admin/config/index', '配置管理', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('150', 'admin', '1', 'admin/config/setting', '网站设置', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('151', 'admin', '1', 'admin/menu/index', '菜单管理', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('152', 'admin', '1', 'admin/manager/index', '管理员管理', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('153', 'admin', '1', 'admin/authManager/index', '角色管理', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('154', 'admin', '2', 'admin/index/index', '首页', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('155', 'admin', '2', 'admin/main/index', '控制面板', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('156', 'admin', '1', 'admin/adminlog/index', '管理日志', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('157', 'attachment', '1', 'attachment/attachments/index', '附件管理', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('158', 'admin', '1', 'admin/config/add', '新增配置', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('159', 'admin', '1', 'admin/config/edit', '编辑配置', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('160', 'admin', '1', 'admin/config/del', '删除配置', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('161', 'admin', '1', 'admin/menu/add', '新增菜单', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('162', 'admin', '1', 'admin/menu/edit', '编辑菜单', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('163', 'admin', '1', 'admin/menu/delete', '删除菜单', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('164', 'attachment', '1', 'attachment/attachments/upload', '附件上传', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('165', 'attachment', '1', 'attachment/attachments/delete', '附件删除', '1', '');

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
INSERT INTO `yzn_cache` VALUES ('1', 'Config', '网站配置', 'admin', 'Config', 'config_cache', '1');
INSERT INTO `yzn_cache` VALUES ('2', 'Menu', '后台菜单', 'admin', 'Menu', 'menu_cache', '0');

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
) ENGINE=MyISAM AUTO_INCREMENT=152 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yzn_config
-- ----------------------------
INSERT INTO `yzn_config` VALUES ('1', 'web_site_status', 'switch', '站点开关', 'base', '', '站点关闭后前台将不能访问', '1494408414', '1542068954', '1', '1', '1');
INSERT INTO `yzn_config` VALUES ('2', 'web_site_title', 'text', '站点标题', 'base', '', '', '1494408414', '1494408414', '1', 'YznCMS网站管理系统', '2');
INSERT INTO `yzn_config` VALUES ('3', 'web_site_keywords', 'text', '站点关键词', 'base', '', '', '1494408414', '1494408414', '1', 'YznCMS,网站管理系统', '3');
INSERT INTO `yzn_config` VALUES ('4', 'web_site_description', 'text', '站点描述', 'base', '', '', '1494408414', '1494408414', '1', '', '4');
INSERT INTO `yzn_config` VALUES ('5', 'web_site_logo', 'image', '站点LOGO', 'base', '', '', '1494408414', '1494408414', '1', '233', '5');
INSERT INTO `yzn_config` VALUES ('6', 'web_site_icp', 'text', '备案信息', 'base', '', '', '1494408414', '1494408414', '1', '', '6');
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
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yzn_hooks
-- ----------------------------

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
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=utf8 COMMENT='后台菜单表';

-- ----------------------------
-- Records of yzn_menu
-- ----------------------------
INSERT INTO `yzn_menu` VALUES ('3', '设置', 'icon-shezhi', '0', 'admin', 'setting', 'index', '', '1', '', '0', '2');
INSERT INTO `yzn_menu` VALUES ('4', '模块', '', '0', 'admin', 'module', 'index', '', '0', '', '0', '3');
INSERT INTO `yzn_menu` VALUES ('5', '扩展', 'icon-yingyong', '0', 'addons', 'addons', 'index', '', '1', '', '0', '4');
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
INSERT INTO `yzn_menu` VALUES ('32', '插件扩展', '', '5', 'addons', 'addons', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('33', '插件管理', '', '32', 'addons', 'addons', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('34', '行为管理', '', '32', 'addons', 'addons', 'hooks', '', '1', '', '0', '0');
