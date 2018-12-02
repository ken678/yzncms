/*
Navicat MySQL Data Transfer

Source Server         : 本地链接
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : yzncms

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2018-12-02 13:10:03
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
) ENGINE=MyISAM AUTO_INCREMENT=47 DEFAULT CHARSET=utf8 COMMENT='插件表';

-- ----------------------------
-- Records of yzn_addons
-- ----------------------------
INSERT INTO `yzn_addons` VALUES ('46', 'returntop', '返回顶部', '回到顶部美化，随机或指定显示，100款样式，每天一种换，天天都用新样式', '1', '{\"random\":\"0\",\"current\":\"1\"}', '御宅男', '1.0.0', '0', '0');

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
INSERT INTO `yzn_admin` VALUES ('1', 'admin', '9724b5e6c56b95f5723009ef81961bfe', '1', 'Wo0bAa', '御宅男', '1543722619', '2130706433', '530765310@qq.com', '1');
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
) ENGINE=MyISAM AUTO_INCREMENT=1560 DEFAULT CHARSET=utf8;

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
INSERT INTO `yzn_adminlog` VALUES ('452', '1', '1', '提示语:插件卸载成功！', '1543580328', '2130706433', '/addons/addons/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('453', '1', '1', '提示语:插件安装成功！', '1543580332', '2130706433', '/addons/addons/install.html');
INSERT INTO `yzn_adminlog` VALUES ('454', '1', '1', '提示语:插件卸载成功！', '1543582082', '2130706433', '/addons/addons/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('455', '1', '1', '提示语:插件安装成功！', '1543582087', '2130706433', '/addons/addons/install.html');
INSERT INTO `yzn_adminlog` VALUES ('456', '1', '1', '提示语:禁用成功', '1543582304', '2130706433', '/addons/addons/disable.html');
INSERT INTO `yzn_adminlog` VALUES ('457', '1', '1', '提示语:启用成功', '1543582309', '2130706433', '/addons/addons/enable.html');
INSERT INTO `yzn_adminlog` VALUES ('458', '1', '1', '提示语:禁用成功', '1543582317', '2130706433', '/addons/addons/disable.html');
INSERT INTO `yzn_adminlog` VALUES ('459', '1', '1', '提示语:启用成功', '1543582320', '2130706433', '/addons/addons/enable.html');
INSERT INTO `yzn_adminlog` VALUES ('460', '0', '1', '提示语:该页面不存在！', '1543582505', '2130706433', '/addons/addons/config.html');
INSERT INTO `yzn_adminlog` VALUES ('461', '0', '0', '提示语:请先登陆', '1543664390', '2130706433', '/admin');
INSERT INTO `yzn_adminlog` VALUES ('462', '1', '1', '提示语:恭喜您，登陆成功', '1543664394', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('463', '1', '1', '提示语:禁用成功', '1543664642', '2130706433', '/addons/addons/disable.html');
INSERT INTO `yzn_adminlog` VALUES ('464', '1', '1', '提示语:启用成功', '1543664645', '2130706433', '/addons/addons/enable.html');
INSERT INTO `yzn_adminlog` VALUES ('465', '0', '1', '提示语:该页面不存在！', '1543664953', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/01.png');
INSERT INTO `yzn_adminlog` VALUES ('466', '0', '1', '提示语:该页面不存在！', '1543664953', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/030.png');
INSERT INTO `yzn_adminlog` VALUES ('467', '0', '1', '提示语:该页面不存在！', '1543664954', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/017.png');
INSERT INTO `yzn_adminlog` VALUES ('468', '0', '1', '提示语:该页面不存在！', '1543664954', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/023.gif');
INSERT INTO `yzn_adminlog` VALUES ('469', '0', '1', '提示语:该页面不存在！', '1543664954', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/026.png');
INSERT INTO `yzn_adminlog` VALUES ('470', '0', '1', '提示语:该页面不存在！', '1543664954', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/027.gif');
INSERT INTO `yzn_adminlog` VALUES ('471', '0', '1', '提示语:该页面不存在！', '1543664954', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/032.png');
INSERT INTO `yzn_adminlog` VALUES ('472', '0', '1', '提示语:该页面不存在！', '1543664954', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/02.png');
INSERT INTO `yzn_adminlog` VALUES ('473', '0', '1', '提示语:该页面不存在！', '1543664955', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/03.gif');
INSERT INTO `yzn_adminlog` VALUES ('474', '0', '1', '提示语:该页面不存在！', '1543664955', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/05.png');
INSERT INTO `yzn_adminlog` VALUES ('475', '0', '1', '提示语:该页面不存在！', '1543664955', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/04.png');
INSERT INTO `yzn_adminlog` VALUES ('476', '0', '1', '提示语:该页面不存在！', '1543664955', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/029.png');
INSERT INTO `yzn_adminlog` VALUES ('477', '0', '1', '提示语:该页面不存在！', '1543664955', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/06.png');
INSERT INTO `yzn_adminlog` VALUES ('478', '0', '1', '提示语:该页面不存在！', '1543664955', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/020.png');
INSERT INTO `yzn_adminlog` VALUES ('479', '0', '1', '提示语:该页面不存在！', '1543664955', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/09.gif');
INSERT INTO `yzn_adminlog` VALUES ('480', '0', '1', '提示语:该页面不存在！', '1543664955', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/07.gif');
INSERT INTO `yzn_adminlog` VALUES ('481', '0', '1', '提示语:该页面不存在！', '1543664955', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/031.png');
INSERT INTO `yzn_adminlog` VALUES ('482', '0', '1', '提示语:该页面不存在！', '1543664955', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/021.png');
INSERT INTO `yzn_adminlog` VALUES ('483', '0', '1', '提示语:该页面不存在！', '1543664955', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/018.png');
INSERT INTO `yzn_adminlog` VALUES ('484', '0', '1', '提示语:该页面不存在！', '1543664955', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/019.gif');
INSERT INTO `yzn_adminlog` VALUES ('485', '0', '1', '提示语:该页面不存在！', '1543664955', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/024.png');
INSERT INTO `yzn_adminlog` VALUES ('486', '0', '1', '提示语:该页面不存在！', '1543664955', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/011.jpg');
INSERT INTO `yzn_adminlog` VALUES ('487', '0', '1', '提示语:该页面不存在！', '1543664955', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/028.gif');
INSERT INTO `yzn_adminlog` VALUES ('488', '0', '1', '提示语:该页面不存在！', '1543664955', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/013.png');
INSERT INTO `yzn_adminlog` VALUES ('489', '0', '1', '提示语:该页面不存在！', '1543664955', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/015.png');
INSERT INTO `yzn_adminlog` VALUES ('490', '0', '1', '提示语:该页面不存在！', '1543664955', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/022.png');
INSERT INTO `yzn_adminlog` VALUES ('491', '0', '1', '提示语:该页面不存在！', '1543664955', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/035.png');
INSERT INTO `yzn_adminlog` VALUES ('492', '0', '1', '提示语:该页面不存在！', '1543664955', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/038.gif');
INSERT INTO `yzn_adminlog` VALUES ('493', '0', '1', '提示语:该页面不存在！', '1543664955', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/012.gif');
INSERT INTO `yzn_adminlog` VALUES ('494', '0', '1', '提示语:该页面不存在！', '1543664955', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/033.png');
INSERT INTO `yzn_adminlog` VALUES ('495', '0', '1', '提示语:该页面不存在！', '1543664955', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/036.gif');
INSERT INTO `yzn_adminlog` VALUES ('496', '0', '1', '提示语:该页面不存在！', '1543664955', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/040.png');
INSERT INTO `yzn_adminlog` VALUES ('497', '0', '1', '提示语:该页面不存在！', '1543664955', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/037.png');
INSERT INTO `yzn_adminlog` VALUES ('498', '0', '1', '提示语:该页面不存在！', '1543664955', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/034.png');
INSERT INTO `yzn_adminlog` VALUES ('499', '0', '1', '提示语:该页面不存在！', '1543664955', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/014.jpg');
INSERT INTO `yzn_adminlog` VALUES ('500', '0', '1', '提示语:该页面不存在！', '1543664955', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/010.png');
INSERT INTO `yzn_adminlog` VALUES ('501', '0', '1', '提示语:该页面不存在！', '1543664955', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/039.png');
INSERT INTO `yzn_adminlog` VALUES ('502', '0', '1', '提示语:该页面不存在！', '1543664955', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/025.gif');
INSERT INTO `yzn_adminlog` VALUES ('503', '0', '1', '提示语:该页面不存在！', '1543664955', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/041.png');
INSERT INTO `yzn_adminlog` VALUES ('504', '0', '1', '提示语:该页面不存在！', '1543664955', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/047.png');
INSERT INTO `yzn_adminlog` VALUES ('505', '0', '1', '提示语:该页面不存在！', '1543664955', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/045.png');
INSERT INTO `yzn_adminlog` VALUES ('506', '0', '1', '提示语:该页面不存在！', '1543664955', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/046.gif');
INSERT INTO `yzn_adminlog` VALUES ('507', '0', '1', '提示语:该页面不存在！', '1543664955', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/042.png');
INSERT INTO `yzn_adminlog` VALUES ('508', '0', '1', '提示语:该页面不存在！', '1543664956', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/043.png');
INSERT INTO `yzn_adminlog` VALUES ('509', '0', '1', '提示语:该页面不存在！', '1543664956', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/044.png');
INSERT INTO `yzn_adminlog` VALUES ('510', '0', '1', '提示语:该页面不存在！', '1543664956', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/048.gif');
INSERT INTO `yzn_adminlog` VALUES ('511', '0', '1', '提示语:该页面不存在！', '1543664956', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/049.gif');
INSERT INTO `yzn_adminlog` VALUES ('512', '0', '1', '提示语:该页面不存在！', '1543664956', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/016.png');
INSERT INTO `yzn_adminlog` VALUES ('513', '0', '1', '提示语:该页面不存在！', '1543664956', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/050.gif');
INSERT INTO `yzn_adminlog` VALUES ('514', '0', '1', '提示语:该页面不存在！', '1543664956', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/051.gif');
INSERT INTO `yzn_adminlog` VALUES ('515', '0', '1', '提示语:该页面不存在！', '1543664956', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/052.gif');
INSERT INTO `yzn_adminlog` VALUES ('516', '0', '1', '提示语:该页面不存在！', '1543664956', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/053.gif');
INSERT INTO `yzn_adminlog` VALUES ('517', '0', '1', '提示语:该页面不存在！', '1543664956', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/054.gif');
INSERT INTO `yzn_adminlog` VALUES ('518', '0', '1', '提示语:该页面不存在！', '1543664956', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/055.gif');
INSERT INTO `yzn_adminlog` VALUES ('519', '0', '1', '提示语:该页面不存在！', '1543664956', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/056.gif');
INSERT INTO `yzn_adminlog` VALUES ('520', '0', '1', '提示语:该页面不存在！', '1543664956', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/057.gif');
INSERT INTO `yzn_adminlog` VALUES ('521', '0', '1', '提示语:该页面不存在！', '1543664956', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/073.gif');
INSERT INTO `yzn_adminlog` VALUES ('522', '0', '1', '提示语:该页面不存在！', '1543664956', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/085.png');
INSERT INTO `yzn_adminlog` VALUES ('523', '0', '1', '提示语:该页面不存在！', '1543664956', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/078.gif');
INSERT INTO `yzn_adminlog` VALUES ('524', '0', '1', '提示语:该页面不存在！', '1543664956', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/079.gif');
INSERT INTO `yzn_adminlog` VALUES ('525', '0', '1', '提示语:该页面不存在！', '1543664956', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/068.png');
INSERT INTO `yzn_adminlog` VALUES ('526', '0', '1', '提示语:该页面不存在！', '1543664956', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/082.png');
INSERT INTO `yzn_adminlog` VALUES ('527', '0', '1', '提示语:该页面不存在！', '1543664956', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/08.gif');
INSERT INTO `yzn_adminlog` VALUES ('528', '0', '1', '提示语:该页面不存在！', '1543664956', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/069.png');
INSERT INTO `yzn_adminlog` VALUES ('529', '0', '1', '提示语:该页面不存在！', '1543664956', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/088.gif');
INSERT INTO `yzn_adminlog` VALUES ('530', '0', '1', '提示语:该页面不存在！', '1543664956', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/097.gif');
INSERT INTO `yzn_adminlog` VALUES ('531', '0', '1', '提示语:该页面不存在！', '1543664956', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/091.png');
INSERT INTO `yzn_adminlog` VALUES ('532', '0', '1', '提示语:该页面不存在！', '1543664956', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/076.png');
INSERT INTO `yzn_adminlog` VALUES ('533', '0', '1', '提示语:该页面不存在！', '1543664956', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/066.png');
INSERT INTO `yzn_adminlog` VALUES ('534', '0', '1', '提示语:该页面不存在！', '1543664956', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/074.png');
INSERT INTO `yzn_adminlog` VALUES ('535', '0', '1', '提示语:该页面不存在！', '1543664956', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/077.gif');
INSERT INTO `yzn_adminlog` VALUES ('536', '0', '1', '提示语:该页面不存在！', '1543664956', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/096.gif');
INSERT INTO `yzn_adminlog` VALUES ('537', '0', '1', '提示语:该页面不存在！', '1543664956', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/063.gif');
INSERT INTO `yzn_adminlog` VALUES ('538', '0', '1', '提示语:该页面不存在！', '1543664956', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/058.gif');
INSERT INTO `yzn_adminlog` VALUES ('539', '0', '1', '提示语:该页面不存在！', '1543664956', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/059.gif');
INSERT INTO `yzn_adminlog` VALUES ('540', '0', '1', '提示语:该页面不存在！', '1543664956', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/067.gif');
INSERT INTO `yzn_adminlog` VALUES ('541', '0', '1', '提示语:该页面不存在！', '1543664956', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/089.gif');
INSERT INTO `yzn_adminlog` VALUES ('542', '0', '1', '提示语:该页面不存在！', '1543664957', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/080.gif');
INSERT INTO `yzn_adminlog` VALUES ('543', '0', '1', '提示语:该页面不存在！', '1543664957', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/099.png');
INSERT INTO `yzn_adminlog` VALUES ('544', '0', '1', '提示语:该页面不存在！', '1543664957', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/087.png');
INSERT INTO `yzn_adminlog` VALUES ('545', '0', '1', '提示语:该页面不存在！', '1543664957', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/098.png');
INSERT INTO `yzn_adminlog` VALUES ('546', '0', '1', '提示语:该页面不存在！', '1543664957', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/093.gif');
INSERT INTO `yzn_adminlog` VALUES ('547', '0', '1', '提示语:该页面不存在！', '1543664957', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/092.gif');
INSERT INTO `yzn_adminlog` VALUES ('548', '0', '1', '提示语:该页面不存在！', '1543664957', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/075.jpg');
INSERT INTO `yzn_adminlog` VALUES ('549', '0', '1', '提示语:该页面不存在！', '1543664957', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/071.gif');
INSERT INTO `yzn_adminlog` VALUES ('550', '0', '1', '提示语:该页面不存在！', '1543664957', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/070.png');
INSERT INTO `yzn_adminlog` VALUES ('551', '0', '1', '提示语:该页面不存在！', '1543664957', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/072.gif');
INSERT INTO `yzn_adminlog` VALUES ('552', '0', '1', '提示语:该页面不存在！', '1543664957', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/094.gif');
INSERT INTO `yzn_adminlog` VALUES ('553', '0', '1', '提示语:该页面不存在！', '1543664957', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/065.png');
INSERT INTO `yzn_adminlog` VALUES ('554', '0', '1', '提示语:该页面不存在！', '1543664957', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/084.jpg');
INSERT INTO `yzn_adminlog` VALUES ('555', '0', '1', '提示语:该页面不存在！', '1543664957', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/061.gif');
INSERT INTO `yzn_adminlog` VALUES ('556', '0', '1', '提示语:该页面不存在！', '1543664957', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/062.gif');
INSERT INTO `yzn_adminlog` VALUES ('557', '0', '1', '提示语:该页面不存在！', '1543664957', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/086.gif');
INSERT INTO `yzn_adminlog` VALUES ('558', '0', '1', '提示语:该页面不存在！', '1543664957', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/095.gif');
INSERT INTO `yzn_adminlog` VALUES ('559', '0', '1', '提示语:该页面不存在！', '1543664957', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/081.jpg');
INSERT INTO `yzn_adminlog` VALUES ('560', '0', '1', '提示语:该页面不存在！', '1543664957', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/090.png');
INSERT INTO `yzn_adminlog` VALUES ('561', '0', '1', '提示语:该页面不存在！', '1543664957', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/083.gif');
INSERT INTO `yzn_adminlog` VALUES ('562', '0', '1', '提示语:该页面不存在！', '1543664957', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/064.gif');
INSERT INTO `yzn_adminlog` VALUES ('563', '0', '1', '提示语:该页面不存在！', '1543664957', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/060.gif');
INSERT INTO `yzn_adminlog` VALUES ('564', '0', '1', '提示语:该页面不存在！', '1543665041', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/04.png');
INSERT INTO `yzn_adminlog` VALUES ('565', '0', '1', '提示语:该页面不存在！', '1543665041', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/01.png');
INSERT INTO `yzn_adminlog` VALUES ('566', '0', '1', '提示语:该页面不存在！', '1543665041', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/03.gif');
INSERT INTO `yzn_adminlog` VALUES ('567', '0', '1', '提示语:该页面不存在！', '1543665041', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/06.png');
INSERT INTO `yzn_adminlog` VALUES ('568', '0', '1', '提示语:该页面不存在！', '1543665041', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/02.png');
INSERT INTO `yzn_adminlog` VALUES ('569', '0', '1', '提示语:该页面不存在！', '1543665041', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/05.png');
INSERT INTO `yzn_adminlog` VALUES ('570', '0', '1', '提示语:该页面不存在！', '1543665041', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/074.png');
INSERT INTO `yzn_adminlog` VALUES ('571', '0', '1', '提示语:该页面不存在！', '1543665041', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/062.gif');
INSERT INTO `yzn_adminlog` VALUES ('572', '0', '1', '提示语:该页面不存在！', '1543665041', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/080.gif');
INSERT INTO `yzn_adminlog` VALUES ('573', '0', '1', '提示语:该页面不存在！', '1543665041', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/073.gif');
INSERT INTO `yzn_adminlog` VALUES ('574', '0', '1', '提示语:该页面不存在！', '1543665041', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/078.gif');
INSERT INTO `yzn_adminlog` VALUES ('575', '0', '1', '提示语:该页面不存在！', '1543665041', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/079.gif');
INSERT INTO `yzn_adminlog` VALUES ('576', '0', '1', '提示语:该页面不存在！', '1543665041', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/068.png');
INSERT INTO `yzn_adminlog` VALUES ('577', '0', '1', '提示语:该页面不存在！', '1543665041', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/075.jpg');
INSERT INTO `yzn_adminlog` VALUES ('578', '0', '1', '提示语:该页面不存在！', '1543665041', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/071.gif');
INSERT INTO `yzn_adminlog` VALUES ('579', '0', '1', '提示语:该页面不存在！', '1543665041', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/070.png');
INSERT INTO `yzn_adminlog` VALUES ('580', '0', '1', '提示语:该页面不存在！', '1543665041', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/069.png');
INSERT INTO `yzn_adminlog` VALUES ('581', '0', '1', '提示语:该页面不存在！', '1543665041', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/050.gif');
INSERT INTO `yzn_adminlog` VALUES ('582', '0', '1', '提示语:该页面不存在！', '1543665041', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/076.png');
INSERT INTO `yzn_adminlog` VALUES ('583', '0', '1', '提示语:该页面不存在！', '1543665041', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/066.png');
INSERT INTO `yzn_adminlog` VALUES ('584', '0', '1', '提示语:该页面不存在！', '1543665041', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/065.png');
INSERT INTO `yzn_adminlog` VALUES ('585', '0', '1', '提示语:该页面不存在！', '1543665041', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/038.gif');
INSERT INTO `yzn_adminlog` VALUES ('586', '0', '1', '提示语:该页面不存在！', '1543665041', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/051.gif');
INSERT INTO `yzn_adminlog` VALUES ('587', '0', '1', '提示语:该页面不存在！', '1543665041', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/061.gif');
INSERT INTO `yzn_adminlog` VALUES ('588', '0', '1', '提示语:该页面不存在！', '1543665041', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/054.gif');
INSERT INTO `yzn_adminlog` VALUES ('589', '0', '1', '提示语:该页面不存在！', '1543665041', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/041.png');
INSERT INTO `yzn_adminlog` VALUES ('590', '0', '1', '提示语:该页面不存在！', '1543665041', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/081.jpg');
INSERT INTO `yzn_adminlog` VALUES ('591', '0', '1', '提示语:该页面不存在！', '1543665041', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/057.gif');
INSERT INTO `yzn_adminlog` VALUES ('592', '0', '1', '提示语:该页面不存在！', '1543665041', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/077.gif');
INSERT INTO `yzn_adminlog` VALUES ('593', '0', '1', '提示语:该页面不存在！', '1543665041', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/056.gif');
INSERT INTO `yzn_adminlog` VALUES ('594', '0', '1', '提示语:该页面不存在！', '1543665041', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/055.gif');
INSERT INTO `yzn_adminlog` VALUES ('595', '0', '1', '提示语:该页面不存在！', '1543665042', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/058.gif');
INSERT INTO `yzn_adminlog` VALUES ('596', '0', '1', '提示语:该页面不存在！', '1543665042', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/063.gif');
INSERT INTO `yzn_adminlog` VALUES ('597', '0', '1', '提示语:该页面不存在！', '1543665042', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/042.png');
INSERT INTO `yzn_adminlog` VALUES ('598', '0', '1', '提示语:该页面不存在！', '1543665042', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/059.gif');
INSERT INTO `yzn_adminlog` VALUES ('599', '0', '1', '提示语:该页面不存在！', '1543665042', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/052.gif');
INSERT INTO `yzn_adminlog` VALUES ('600', '0', '1', '提示语:该页面不存在！', '1543665042', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/064.gif');
INSERT INTO `yzn_adminlog` VALUES ('601', '0', '1', '提示语:该页面不存在！', '1543665042', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/060.gif');
INSERT INTO `yzn_adminlog` VALUES ('602', '0', '1', '提示语:该页面不存在！', '1543665042', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/053.gif');
INSERT INTO `yzn_adminlog` VALUES ('603', '0', '1', '提示语:该页面不存在！', '1543665042', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/067.gif');
INSERT INTO `yzn_adminlog` VALUES ('604', '0', '1', '提示语:该页面不存在！', '1543665042', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/085.png');
INSERT INTO `yzn_adminlog` VALUES ('605', '0', '1', '提示语:该页面不存在！', '1543665042', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/089.gif');
INSERT INTO `yzn_adminlog` VALUES ('606', '0', '1', '提示语:该页面不存在！', '1543665042', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/087.png');
INSERT INTO `yzn_adminlog` VALUES ('607', '0', '1', '提示语:该页面不存在！', '1543665042', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/082.png');
INSERT INTO `yzn_adminlog` VALUES ('608', '0', '1', '提示语:该页面不存在！', '1543665042', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/090.png');
INSERT INTO `yzn_adminlog` VALUES ('609', '0', '1', '提示语:该页面不存在！', '1543665042', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/088.gif');
INSERT INTO `yzn_adminlog` VALUES ('610', '0', '1', '提示语:该页面不存在！', '1543665042', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/084.jpg');
INSERT INTO `yzn_adminlog` VALUES ('611', '0', '1', '提示语:该页面不存在！', '1543665042', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/086.gif');
INSERT INTO `yzn_adminlog` VALUES ('612', '0', '1', '提示语:该页面不存在！', '1543665042', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/083.gif');
INSERT INTO `yzn_adminlog` VALUES ('613', '0', '1', '提示语:该页面不存在！', '1543665042', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/072.gif');
INSERT INTO `yzn_adminlog` VALUES ('614', '0', '1', '提示语:该页面不存在！', '1543665042', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/099.png');
INSERT INTO `yzn_adminlog` VALUES ('615', '0', '1', '提示语:该页面不存在！', '1543665042', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/098.png');
INSERT INTO `yzn_adminlog` VALUES ('616', '0', '1', '提示语:该页面不存在！', '1543665042', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/093.gif');
INSERT INTO `yzn_adminlog` VALUES ('617', '0', '1', '提示语:该页面不存在！', '1543665042', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/092.gif');
INSERT INTO `yzn_adminlog` VALUES ('618', '0', '1', '提示语:该页面不存在！', '1543665042', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/097.gif');
INSERT INTO `yzn_adminlog` VALUES ('619', '0', '1', '提示语:该页面不存在！', '1543665042', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/091.png');
INSERT INTO `yzn_adminlog` VALUES ('620', '0', '1', '提示语:该页面不存在！', '1543665042', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/094.gif');
INSERT INTO `yzn_adminlog` VALUES ('621', '0', '1', '提示语:该页面不存在！', '1543665042', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/095.gif');
INSERT INTO `yzn_adminlog` VALUES ('622', '0', '1', '提示语:该页面不存在！', '1543665042', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/096.gif');
INSERT INTO `yzn_adminlog` VALUES ('623', '0', '1', '提示语:该页面不存在！', '1543665042', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/07.gif');
INSERT INTO `yzn_adminlog` VALUES ('624', '0', '1', '提示语:该页面不存在！', '1543665042', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/08.gif');
INSERT INTO `yzn_adminlog` VALUES ('625', '0', '1', '提示语:该页面不存在！', '1543665042', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/09.gif');
INSERT INTO `yzn_adminlog` VALUES ('626', '0', '1', '提示语:该页面不存在！', '1543665042', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/010.png');
INSERT INTO `yzn_adminlog` VALUES ('627', '0', '1', '提示语:该页面不存在！', '1543665042', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/011.jpg');
INSERT INTO `yzn_adminlog` VALUES ('628', '0', '1', '提示语:该页面不存在！', '1543665042', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/012.gif');
INSERT INTO `yzn_adminlog` VALUES ('629', '0', '1', '提示语:该页面不存在！', '1543665043', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/013.png');
INSERT INTO `yzn_adminlog` VALUES ('630', '0', '1', '提示语:该页面不存在！', '1543665043', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/028.gif');
INSERT INTO `yzn_adminlog` VALUES ('631', '0', '1', '提示语:该页面不存在！', '1543665043', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/017.png');
INSERT INTO `yzn_adminlog` VALUES ('632', '0', '1', '提示语:该页面不存在！', '1543665043', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/033.png');
INSERT INTO `yzn_adminlog` VALUES ('633', '0', '1', '提示语:该页面不存在！', '1543665043', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/031.png');
INSERT INTO `yzn_adminlog` VALUES ('634', '0', '1', '提示语:该页面不存在！', '1543665043', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/018.png');
INSERT INTO `yzn_adminlog` VALUES ('635', '0', '1', '提示语:该页面不存在！', '1543665043', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/030.png');
INSERT INTO `yzn_adminlog` VALUES ('636', '0', '1', '提示语:该页面不存在！', '1543665043', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/023.gif');
INSERT INTO `yzn_adminlog` VALUES ('637', '0', '1', '提示语:该页面不存在！', '1543665043', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/026.png');
INSERT INTO `yzn_adminlog` VALUES ('638', '0', '1', '提示语:该页面不存在！', '1543665043', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/035.png');
INSERT INTO `yzn_adminlog` VALUES ('639', '0', '1', '提示语:该页面不存在！', '1543665043', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/027.gif');
INSERT INTO `yzn_adminlog` VALUES ('640', '0', '1', '提示语:该页面不存在！', '1543665043', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/020.png');
INSERT INTO `yzn_adminlog` VALUES ('641', '0', '1', '提示语:该页面不存在！', '1543665043', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/016.png');
INSERT INTO `yzn_adminlog` VALUES ('642', '0', '1', '提示语:该页面不存在！', '1543665043', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/045.png');
INSERT INTO `yzn_adminlog` VALUES ('643', '0', '1', '提示语:该页面不存在！', '1543665043', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/034.png');
INSERT INTO `yzn_adminlog` VALUES ('644', '0', '1', '提示语:该页面不存在！', '1543665043', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/048.gif');
INSERT INTO `yzn_adminlog` VALUES ('645', '0', '1', '提示语:该页面不存在！', '1543665043', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/022.png');
INSERT INTO `yzn_adminlog` VALUES ('646', '0', '1', '提示语:该页面不存在！', '1543665043', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/043.png');
INSERT INTO `yzn_adminlog` VALUES ('647', '0', '1', '提示语:该页面不存在！', '1543665043', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/044.png');
INSERT INTO `yzn_adminlog` VALUES ('648', '0', '1', '提示语:该页面不存在！', '1543665043', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/049.gif');
INSERT INTO `yzn_adminlog` VALUES ('649', '0', '1', '提示语:该页面不存在！', '1543665043', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/032.png');
INSERT INTO `yzn_adminlog` VALUES ('650', '0', '1', '提示语:该页面不存在！', '1543665043', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/015.png');
INSERT INTO `yzn_adminlog` VALUES ('651', '0', '1', '提示语:该页面不存在！', '1543665043', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/029.png');
INSERT INTO `yzn_adminlog` VALUES ('652', '0', '1', '提示语:该页面不存在！', '1543665043', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/036.gif');
INSERT INTO `yzn_adminlog` VALUES ('653', '0', '1', '提示语:该页面不存在！', '1543665043', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/046.gif');
INSERT INTO `yzn_adminlog` VALUES ('654', '0', '1', '提示语:该页面不存在！', '1543665043', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/040.png');
INSERT INTO `yzn_adminlog` VALUES ('655', '0', '1', '提示语:该页面不存在！', '1543665043', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/021.png');
INSERT INTO `yzn_adminlog` VALUES ('656', '0', '1', '提示语:该页面不存在！', '1543665043', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/037.png');
INSERT INTO `yzn_adminlog` VALUES ('657', '0', '1', '提示语:该页面不存在！', '1543665043', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/019.gif');
INSERT INTO `yzn_adminlog` VALUES ('658', '0', '1', '提示语:该页面不存在！', '1543665043', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/024.png');
INSERT INTO `yzn_adminlog` VALUES ('659', '0', '1', '提示语:该页面不存在！', '1543665043', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/014.jpg');
INSERT INTO `yzn_adminlog` VALUES ('660', '0', '1', '提示语:该页面不存在！', '1543665043', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/039.png');
INSERT INTO `yzn_adminlog` VALUES ('661', '0', '1', '提示语:该页面不存在！', '1543665044', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/025.gif');
INSERT INTO `yzn_adminlog` VALUES ('662', '0', '1', '提示语:该页面不存在！', '1543665044', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/047.png');
INSERT INTO `yzn_adminlog` VALUES ('663', '0', '1', '提示语:该页面不存在！', '1543665174', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/01.png');
INSERT INTO `yzn_adminlog` VALUES ('664', '0', '1', '提示语:该页面不存在！', '1543665174', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/05.png');
INSERT INTO `yzn_adminlog` VALUES ('665', '0', '1', '提示语:该页面不存在！', '1543665174', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/06.png');
INSERT INTO `yzn_adminlog` VALUES ('666', '0', '1', '提示语:该页面不存在！', '1543665174', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/04.png');
INSERT INTO `yzn_adminlog` VALUES ('667', '0', '1', '提示语:该页面不存在！', '1543665174', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/03.gif');
INSERT INTO `yzn_adminlog` VALUES ('668', '0', '1', '提示语:该页面不存在！', '1543665174', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/02.png');
INSERT INTO `yzn_adminlog` VALUES ('669', '0', '1', '提示语:该页面不存在！', '1543665174', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/09.gif');
INSERT INTO `yzn_adminlog` VALUES ('670', '0', '1', '提示语:该页面不存在！', '1543665174', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/07.gif');
INSERT INTO `yzn_adminlog` VALUES ('671', '0', '1', '提示语:该页面不存在！', '1543665174', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/012.gif');
INSERT INTO `yzn_adminlog` VALUES ('672', '0', '1', '提示语:该页面不存在！', '1543665174', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/011.jpg');
INSERT INTO `yzn_adminlog` VALUES ('673', '0', '1', '提示语:该页面不存在！', '1543665174', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/010.png');
INSERT INTO `yzn_adminlog` VALUES ('674', '0', '1', '提示语:该页面不存在！', '1543665174', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/08.gif');
INSERT INTO `yzn_adminlog` VALUES ('675', '0', '1', '提示语:该页面不存在！', '1543665174', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/013.png');
INSERT INTO `yzn_adminlog` VALUES ('676', '0', '1', '提示语:该页面不存在！', '1543665174', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/014.jpg');
INSERT INTO `yzn_adminlog` VALUES ('677', '0', '1', '提示语:该页面不存在！', '1543665174', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/015.png');
INSERT INTO `yzn_adminlog` VALUES ('678', '0', '1', '提示语:该页面不存在！', '1543665174', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/016.png');
INSERT INTO `yzn_adminlog` VALUES ('679', '0', '1', '提示语:该页面不存在！', '1543665174', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/017.png');
INSERT INTO `yzn_adminlog` VALUES ('680', '0', '1', '提示语:该页面不存在！', '1543665174', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/018.png');
INSERT INTO `yzn_adminlog` VALUES ('681', '0', '1', '提示语:该页面不存在！', '1543665174', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/019.gif');
INSERT INTO `yzn_adminlog` VALUES ('682', '0', '1', '提示语:该页面不存在！', '1543665174', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/020.png');
INSERT INTO `yzn_adminlog` VALUES ('683', '0', '1', '提示语:该页面不存在！', '1543665174', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/021.png');
INSERT INTO `yzn_adminlog` VALUES ('684', '0', '1', '提示语:该页面不存在！', '1543665174', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/022.png');
INSERT INTO `yzn_adminlog` VALUES ('685', '0', '1', '提示语:该页面不存在！', '1543665175', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/023.gif');
INSERT INTO `yzn_adminlog` VALUES ('686', '0', '1', '提示语:该页面不存在！', '1543665175', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/024.png');
INSERT INTO `yzn_adminlog` VALUES ('687', '0', '1', '提示语:该页面不存在！', '1543665175', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/025.gif');
INSERT INTO `yzn_adminlog` VALUES ('688', '0', '1', '提示语:该页面不存在！', '1543665175', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/026.png');
INSERT INTO `yzn_adminlog` VALUES ('689', '0', '1', '提示语:该页面不存在！', '1543665175', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/027.gif');
INSERT INTO `yzn_adminlog` VALUES ('690', '0', '1', '提示语:该页面不存在！', '1543665175', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/028.gif');
INSERT INTO `yzn_adminlog` VALUES ('691', '0', '1', '提示语:该页面不存在！', '1543665175', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/029.png');
INSERT INTO `yzn_adminlog` VALUES ('692', '0', '1', '提示语:该页面不存在！', '1543665175', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/030.png');
INSERT INTO `yzn_adminlog` VALUES ('693', '0', '1', '提示语:该页面不存在！', '1543665175', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/031.png');
INSERT INTO `yzn_adminlog` VALUES ('694', '0', '1', '提示语:该页面不存在！', '1543665175', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/032.png');
INSERT INTO `yzn_adminlog` VALUES ('695', '0', '1', '提示语:该页面不存在！', '1543665175', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/033.png');
INSERT INTO `yzn_adminlog` VALUES ('696', '0', '1', '提示语:该页面不存在！', '1543665175', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/034.png');
INSERT INTO `yzn_adminlog` VALUES ('697', '0', '1', '提示语:该页面不存在！', '1543665175', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/035.png');
INSERT INTO `yzn_adminlog` VALUES ('698', '0', '1', '提示语:该页面不存在！', '1543665175', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/036.gif');
INSERT INTO `yzn_adminlog` VALUES ('699', '0', '1', '提示语:该页面不存在！', '1543665175', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/037.png');
INSERT INTO `yzn_adminlog` VALUES ('700', '0', '1', '提示语:该页面不存在！', '1543665175', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/038.gif');
INSERT INTO `yzn_adminlog` VALUES ('701', '0', '1', '提示语:该页面不存在！', '1543665175', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/039.png');
INSERT INTO `yzn_adminlog` VALUES ('702', '0', '1', '提示语:该页面不存在！', '1543665175', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/040.png');
INSERT INTO `yzn_adminlog` VALUES ('703', '0', '1', '提示语:该页面不存在！', '1543665175', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/041.png');
INSERT INTO `yzn_adminlog` VALUES ('704', '0', '1', '提示语:该页面不存在！', '1543665175', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/042.png');
INSERT INTO `yzn_adminlog` VALUES ('705', '0', '1', '提示语:该页面不存在！', '1543665175', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/043.png');
INSERT INTO `yzn_adminlog` VALUES ('706', '0', '1', '提示语:该页面不存在！', '1543665175', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/044.png');
INSERT INTO `yzn_adminlog` VALUES ('707', '0', '1', '提示语:该页面不存在！', '1543665175', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/045.png');
INSERT INTO `yzn_adminlog` VALUES ('708', '0', '1', '提示语:该页面不存在！', '1543665175', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/046.gif');
INSERT INTO `yzn_adminlog` VALUES ('709', '0', '1', '提示语:该页面不存在！', '1543665175', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/047.png');
INSERT INTO `yzn_adminlog` VALUES ('710', '0', '1', '提示语:该页面不存在！', '1543665175', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/048.gif');
INSERT INTO `yzn_adminlog` VALUES ('711', '0', '1', '提示语:该页面不存在！', '1543665175', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/049.gif');
INSERT INTO `yzn_adminlog` VALUES ('712', '0', '1', '提示语:该页面不存在！', '1543665175', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/050.gif');
INSERT INTO `yzn_adminlog` VALUES ('713', '0', '1', '提示语:该页面不存在！', '1543665175', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/051.gif');
INSERT INTO `yzn_adminlog` VALUES ('714', '0', '1', '提示语:该页面不存在！', '1543665175', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/052.gif');
INSERT INTO `yzn_adminlog` VALUES ('715', '0', '1', '提示语:该页面不存在！', '1543665175', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/053.gif');
INSERT INTO `yzn_adminlog` VALUES ('716', '0', '1', '提示语:该页面不存在！', '1543665175', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/054.gif');
INSERT INTO `yzn_adminlog` VALUES ('717', '0', '1', '提示语:该页面不存在！', '1543665175', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/055.gif');
INSERT INTO `yzn_adminlog` VALUES ('718', '0', '1', '提示语:该页面不存在！', '1543665176', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/056.gif');
INSERT INTO `yzn_adminlog` VALUES ('719', '0', '1', '提示语:该页面不存在！', '1543665176', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/057.gif');
INSERT INTO `yzn_adminlog` VALUES ('720', '0', '1', '提示语:该页面不存在！', '1543665176', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/058.gif');
INSERT INTO `yzn_adminlog` VALUES ('721', '0', '1', '提示语:该页面不存在！', '1543665176', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/059.gif');
INSERT INTO `yzn_adminlog` VALUES ('722', '0', '1', '提示语:该页面不存在！', '1543665176', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/060.gif');
INSERT INTO `yzn_adminlog` VALUES ('723', '0', '1', '提示语:该页面不存在！', '1543665176', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/061.gif');
INSERT INTO `yzn_adminlog` VALUES ('724', '0', '1', '提示语:该页面不存在！', '1543665176', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/062.gif');
INSERT INTO `yzn_adminlog` VALUES ('725', '0', '1', '提示语:该页面不存在！', '1543665176', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/063.gif');
INSERT INTO `yzn_adminlog` VALUES ('726', '0', '1', '提示语:该页面不存在！', '1543665176', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/064.gif');
INSERT INTO `yzn_adminlog` VALUES ('727', '0', '1', '提示语:该页面不存在！', '1543665176', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/065.png');
INSERT INTO `yzn_adminlog` VALUES ('728', '0', '1', '提示语:该页面不存在！', '1543665176', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/066.png');
INSERT INTO `yzn_adminlog` VALUES ('729', '0', '1', '提示语:该页面不存在！', '1543665176', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/067.gif');
INSERT INTO `yzn_adminlog` VALUES ('730', '0', '1', '提示语:该页面不存在！', '1543665176', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/068.png');
INSERT INTO `yzn_adminlog` VALUES ('731', '0', '1', '提示语:该页面不存在！', '1543665176', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/069.png');
INSERT INTO `yzn_adminlog` VALUES ('732', '0', '1', '提示语:该页面不存在！', '1543665176', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/070.png');
INSERT INTO `yzn_adminlog` VALUES ('733', '0', '1', '提示语:该页面不存在！', '1543665176', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/071.gif');
INSERT INTO `yzn_adminlog` VALUES ('734', '0', '1', '提示语:该页面不存在！', '1543665176', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/072.gif');
INSERT INTO `yzn_adminlog` VALUES ('735', '0', '1', '提示语:该页面不存在！', '1543665176', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/073.gif');
INSERT INTO `yzn_adminlog` VALUES ('736', '0', '1', '提示语:该页面不存在！', '1543665176', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/074.png');
INSERT INTO `yzn_adminlog` VALUES ('737', '0', '1', '提示语:该页面不存在！', '1543665176', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/075.jpg');
INSERT INTO `yzn_adminlog` VALUES ('738', '0', '1', '提示语:该页面不存在！', '1543665176', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/076.png');
INSERT INTO `yzn_adminlog` VALUES ('739', '0', '1', '提示语:该页面不存在！', '1543665176', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/077.gif');
INSERT INTO `yzn_adminlog` VALUES ('740', '0', '1', '提示语:该页面不存在！', '1543665176', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/078.gif');
INSERT INTO `yzn_adminlog` VALUES ('741', '0', '1', '提示语:该页面不存在！', '1543665176', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/079.gif');
INSERT INTO `yzn_adminlog` VALUES ('742', '0', '1', '提示语:该页面不存在！', '1543665176', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/080.gif');
INSERT INTO `yzn_adminlog` VALUES ('743', '0', '1', '提示语:该页面不存在！', '1543665176', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/081.jpg');
INSERT INTO `yzn_adminlog` VALUES ('744', '0', '1', '提示语:该页面不存在！', '1543665176', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/082.png');
INSERT INTO `yzn_adminlog` VALUES ('745', '0', '1', '提示语:该页面不存在！', '1543665176', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/083.gif');
INSERT INTO `yzn_adminlog` VALUES ('746', '0', '1', '提示语:该页面不存在！', '1543665176', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/084.jpg');
INSERT INTO `yzn_adminlog` VALUES ('747', '0', '1', '提示语:该页面不存在！', '1543665176', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/085.png');
INSERT INTO `yzn_adminlog` VALUES ('748', '0', '1', '提示语:该页面不存在！', '1543665176', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/086.gif');
INSERT INTO `yzn_adminlog` VALUES ('749', '0', '1', '提示语:该页面不存在！', '1543665176', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/087.png');
INSERT INTO `yzn_adminlog` VALUES ('750', '0', '1', '提示语:该页面不存在！', '1543665176', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/088.gif');
INSERT INTO `yzn_adminlog` VALUES ('751', '0', '1', '提示语:该页面不存在！', '1543665177', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/089.gif');
INSERT INTO `yzn_adminlog` VALUES ('752', '0', '1', '提示语:该页面不存在！', '1543665177', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/090.png');
INSERT INTO `yzn_adminlog` VALUES ('753', '0', '1', '提示语:该页面不存在！', '1543665177', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/091.png');
INSERT INTO `yzn_adminlog` VALUES ('754', '0', '1', '提示语:该页面不存在！', '1543665177', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/092.gif');
INSERT INTO `yzn_adminlog` VALUES ('755', '0', '1', '提示语:该页面不存在！', '1543665177', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/093.gif');
INSERT INTO `yzn_adminlog` VALUES ('756', '0', '1', '提示语:该页面不存在！', '1543665177', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/094.gif');
INSERT INTO `yzn_adminlog` VALUES ('757', '0', '1', '提示语:该页面不存在！', '1543665177', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/095.gif');
INSERT INTO `yzn_adminlog` VALUES ('758', '0', '1', '提示语:该页面不存在！', '1543665177', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/096.gif');
INSERT INTO `yzn_adminlog` VALUES ('759', '0', '1', '提示语:该页面不存在！', '1543665177', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/097.gif');
INSERT INTO `yzn_adminlog` VALUES ('760', '0', '1', '提示语:该页面不存在！', '1543665177', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/098.png');
INSERT INTO `yzn_adminlog` VALUES ('761', '0', '1', '提示语:该页面不存在！', '1543665177', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/099.png');
INSERT INTO `yzn_adminlog` VALUES ('762', '0', '1', '提示语:该页面不存在！', '1543665184', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/01.png');
INSERT INTO `yzn_adminlog` VALUES ('763', '0', '1', '提示语:该页面不存在！', '1543665184', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/04.png');
INSERT INTO `yzn_adminlog` VALUES ('764', '0', '1', '提示语:该页面不存在！', '1543665184', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/06.png');
INSERT INTO `yzn_adminlog` VALUES ('765', '0', '1', '提示语:该页面不存在！', '1543665184', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/05.png');
INSERT INTO `yzn_adminlog` VALUES ('766', '0', '1', '提示语:该页面不存在！', '1543665184', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/02.png');
INSERT INTO `yzn_adminlog` VALUES ('767', '0', '1', '提示语:该页面不存在！', '1543665184', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/03.gif');
INSERT INTO `yzn_adminlog` VALUES ('768', '0', '1', '提示语:该页面不存在！', '1543665184', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/012.gif');
INSERT INTO `yzn_adminlog` VALUES ('769', '0', '1', '提示语:该页面不存在！', '1543665184', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/09.gif');
INSERT INTO `yzn_adminlog` VALUES ('770', '0', '1', '提示语:该页面不存在！', '1543665184', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/07.gif');
INSERT INTO `yzn_adminlog` VALUES ('771', '0', '1', '提示语:该页面不存在！', '1543665184', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/011.jpg');
INSERT INTO `yzn_adminlog` VALUES ('772', '0', '1', '提示语:该页面不存在！', '1543665184', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/010.png');
INSERT INTO `yzn_adminlog` VALUES ('773', '0', '1', '提示语:该页面不存在！', '1543665184', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/08.gif');
INSERT INTO `yzn_adminlog` VALUES ('774', '0', '1', '提示语:该页面不存在！', '1543665184', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/013.png');
INSERT INTO `yzn_adminlog` VALUES ('775', '0', '1', '提示语:该页面不存在！', '1543665184', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/014.jpg');
INSERT INTO `yzn_adminlog` VALUES ('776', '0', '1', '提示语:该页面不存在！', '1543665184', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/015.png');
INSERT INTO `yzn_adminlog` VALUES ('777', '0', '1', '提示语:该页面不存在！', '1543665184', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/016.png');
INSERT INTO `yzn_adminlog` VALUES ('778', '0', '1', '提示语:该页面不存在！', '1543665184', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/017.png');
INSERT INTO `yzn_adminlog` VALUES ('779', '0', '1', '提示语:该页面不存在！', '1543665184', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/018.png');
INSERT INTO `yzn_adminlog` VALUES ('780', '0', '1', '提示语:该页面不存在！', '1543665184', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/019.gif');
INSERT INTO `yzn_adminlog` VALUES ('781', '0', '1', '提示语:该页面不存在！', '1543665184', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/020.png');
INSERT INTO `yzn_adminlog` VALUES ('782', '0', '1', '提示语:该页面不存在！', '1543665184', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/021.png');
INSERT INTO `yzn_adminlog` VALUES ('783', '0', '1', '提示语:该页面不存在！', '1543665184', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/022.png');
INSERT INTO `yzn_adminlog` VALUES ('784', '0', '1', '提示语:该页面不存在！', '1543665184', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/023.gif');
INSERT INTO `yzn_adminlog` VALUES ('785', '0', '1', '提示语:该页面不存在！', '1543665184', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/024.png');
INSERT INTO `yzn_adminlog` VALUES ('786', '0', '1', '提示语:该页面不存在！', '1543665184', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/025.gif');
INSERT INTO `yzn_adminlog` VALUES ('787', '0', '1', '提示语:该页面不存在！', '1543665184', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/026.png');
INSERT INTO `yzn_adminlog` VALUES ('788', '0', '1', '提示语:该页面不存在！', '1543665184', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/027.gif');
INSERT INTO `yzn_adminlog` VALUES ('789', '0', '1', '提示语:该页面不存在！', '1543665184', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/028.gif');
INSERT INTO `yzn_adminlog` VALUES ('790', '0', '1', '提示语:该页面不存在！', '1543665185', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/029.png');
INSERT INTO `yzn_adminlog` VALUES ('791', '0', '1', '提示语:该页面不存在！', '1543665185', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/030.png');
INSERT INTO `yzn_adminlog` VALUES ('792', '0', '1', '提示语:该页面不存在！', '1543665185', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/031.png');
INSERT INTO `yzn_adminlog` VALUES ('793', '0', '1', '提示语:该页面不存在！', '1543665185', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/032.png');
INSERT INTO `yzn_adminlog` VALUES ('794', '0', '1', '提示语:该页面不存在！', '1543665185', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/033.png');
INSERT INTO `yzn_adminlog` VALUES ('795', '0', '1', '提示语:该页面不存在！', '1543665185', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/034.png');
INSERT INTO `yzn_adminlog` VALUES ('796', '0', '1', '提示语:该页面不存在！', '1543665185', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/035.png');
INSERT INTO `yzn_adminlog` VALUES ('797', '0', '1', '提示语:该页面不存在！', '1543665185', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/036.gif');
INSERT INTO `yzn_adminlog` VALUES ('798', '0', '1', '提示语:该页面不存在！', '1543665185', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/037.png');
INSERT INTO `yzn_adminlog` VALUES ('799', '0', '1', '提示语:该页面不存在！', '1543665185', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/038.gif');
INSERT INTO `yzn_adminlog` VALUES ('800', '0', '1', '提示语:该页面不存在！', '1543665185', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/039.png');
INSERT INTO `yzn_adminlog` VALUES ('801', '0', '1', '提示语:该页面不存在！', '1543665185', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/040.png');
INSERT INTO `yzn_adminlog` VALUES ('802', '0', '1', '提示语:该页面不存在！', '1543665185', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/041.png');
INSERT INTO `yzn_adminlog` VALUES ('803', '0', '1', '提示语:该页面不存在！', '1543665185', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/042.png');
INSERT INTO `yzn_adminlog` VALUES ('804', '0', '1', '提示语:该页面不存在！', '1543665185', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/043.png');
INSERT INTO `yzn_adminlog` VALUES ('805', '0', '1', '提示语:该页面不存在！', '1543665185', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/044.png');
INSERT INTO `yzn_adminlog` VALUES ('806', '0', '1', '提示语:该页面不存在！', '1543665185', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/045.png');
INSERT INTO `yzn_adminlog` VALUES ('807', '0', '1', '提示语:该页面不存在！', '1543665185', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/046.gif');
INSERT INTO `yzn_adminlog` VALUES ('808', '0', '1', '提示语:该页面不存在！', '1543665185', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/047.png');
INSERT INTO `yzn_adminlog` VALUES ('809', '0', '1', '提示语:该页面不存在！', '1543665185', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/048.gif');
INSERT INTO `yzn_adminlog` VALUES ('810', '0', '1', '提示语:该页面不存在！', '1543665185', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/049.gif');
INSERT INTO `yzn_adminlog` VALUES ('811', '0', '1', '提示语:该页面不存在！', '1543665185', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/050.gif');
INSERT INTO `yzn_adminlog` VALUES ('812', '0', '1', '提示语:该页面不存在！', '1543665185', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/051.gif');
INSERT INTO `yzn_adminlog` VALUES ('813', '0', '1', '提示语:该页面不存在！', '1543665185', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/052.gif');
INSERT INTO `yzn_adminlog` VALUES ('814', '0', '1', '提示语:该页面不存在！', '1543665185', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/053.gif');
INSERT INTO `yzn_adminlog` VALUES ('815', '0', '1', '提示语:该页面不存在！', '1543665185', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/054.gif');
INSERT INTO `yzn_adminlog` VALUES ('816', '0', '1', '提示语:该页面不存在！', '1543665185', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/055.gif');
INSERT INTO `yzn_adminlog` VALUES ('817', '0', '1', '提示语:该页面不存在！', '1543665185', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/056.gif');
INSERT INTO `yzn_adminlog` VALUES ('818', '0', '1', '提示语:该页面不存在！', '1543665185', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/057.gif');
INSERT INTO `yzn_adminlog` VALUES ('819', '0', '1', '提示语:该页面不存在！', '1543665185', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/058.gif');
INSERT INTO `yzn_adminlog` VALUES ('820', '0', '1', '提示语:该页面不存在！', '1543665185', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/059.gif');
INSERT INTO `yzn_adminlog` VALUES ('821', '0', '1', '提示语:该页面不存在！', '1543665185', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/060.gif');
INSERT INTO `yzn_adminlog` VALUES ('822', '0', '1', '提示语:该页面不存在！', '1543665186', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/061.gif');
INSERT INTO `yzn_adminlog` VALUES ('823', '0', '1', '提示语:该页面不存在！', '1543665186', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/062.gif');
INSERT INTO `yzn_adminlog` VALUES ('824', '0', '1', '提示语:该页面不存在！', '1543665186', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/063.gif');
INSERT INTO `yzn_adminlog` VALUES ('825', '0', '1', '提示语:该页面不存在！', '1543665186', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/064.gif');
INSERT INTO `yzn_adminlog` VALUES ('826', '0', '1', '提示语:该页面不存在！', '1543665186', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/065.png');
INSERT INTO `yzn_adminlog` VALUES ('827', '0', '1', '提示语:该页面不存在！', '1543665186', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/066.png');
INSERT INTO `yzn_adminlog` VALUES ('828', '0', '1', '提示语:该页面不存在！', '1543665186', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/067.gif');
INSERT INTO `yzn_adminlog` VALUES ('829', '0', '1', '提示语:该页面不存在！', '1543665186', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/068.png');
INSERT INTO `yzn_adminlog` VALUES ('830', '0', '1', '提示语:该页面不存在！', '1543665186', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/069.png');
INSERT INTO `yzn_adminlog` VALUES ('831', '0', '1', '提示语:该页面不存在！', '1543665186', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/070.png');
INSERT INTO `yzn_adminlog` VALUES ('832', '0', '1', '提示语:该页面不存在！', '1543665186', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/071.gif');
INSERT INTO `yzn_adminlog` VALUES ('833', '0', '1', '提示语:该页面不存在！', '1543665186', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/072.gif');
INSERT INTO `yzn_adminlog` VALUES ('834', '0', '1', '提示语:该页面不存在！', '1543665186', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/073.gif');
INSERT INTO `yzn_adminlog` VALUES ('835', '0', '1', '提示语:该页面不存在！', '1543665186', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/074.png');
INSERT INTO `yzn_adminlog` VALUES ('836', '0', '1', '提示语:该页面不存在！', '1543665186', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/075.jpg');
INSERT INTO `yzn_adminlog` VALUES ('837', '0', '1', '提示语:该页面不存在！', '1543665186', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/076.png');
INSERT INTO `yzn_adminlog` VALUES ('838', '0', '1', '提示语:该页面不存在！', '1543665186', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/077.gif');
INSERT INTO `yzn_adminlog` VALUES ('839', '0', '1', '提示语:该页面不存在！', '1543665186', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/078.gif');
INSERT INTO `yzn_adminlog` VALUES ('840', '0', '1', '提示语:该页面不存在！', '1543665186', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/079.gif');
INSERT INTO `yzn_adminlog` VALUES ('841', '0', '1', '提示语:该页面不存在！', '1543665186', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/080.gif');
INSERT INTO `yzn_adminlog` VALUES ('842', '0', '1', '提示语:该页面不存在！', '1543665186', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/081.jpg');
INSERT INTO `yzn_adminlog` VALUES ('843', '0', '1', '提示语:该页面不存在！', '1543665186', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/082.png');
INSERT INTO `yzn_adminlog` VALUES ('844', '0', '1', '提示语:该页面不存在！', '1543665186', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/083.gif');
INSERT INTO `yzn_adminlog` VALUES ('845', '0', '1', '提示语:该页面不存在！', '1543665186', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/084.jpg');
INSERT INTO `yzn_adminlog` VALUES ('846', '0', '1', '提示语:该页面不存在！', '1543665186', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/085.png');
INSERT INTO `yzn_adminlog` VALUES ('847', '0', '1', '提示语:该页面不存在！', '1543665186', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/086.gif');
INSERT INTO `yzn_adminlog` VALUES ('848', '0', '1', '提示语:该页面不存在！', '1543665186', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/087.png');
INSERT INTO `yzn_adminlog` VALUES ('849', '0', '1', '提示语:该页面不存在！', '1543665186', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/088.gif');
INSERT INTO `yzn_adminlog` VALUES ('850', '0', '1', '提示语:该页面不存在！', '1543665186', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/090.png');
INSERT INTO `yzn_adminlog` VALUES ('851', '0', '1', '提示语:该页面不存在！', '1543665186', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/091.png');
INSERT INTO `yzn_adminlog` VALUES ('852', '0', '1', '提示语:该页面不存在！', '1543665186', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/092.gif');
INSERT INTO `yzn_adminlog` VALUES ('853', '0', '1', '提示语:该页面不存在！', '1543665186', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/093.gif');
INSERT INTO `yzn_adminlog` VALUES ('854', '0', '1', '提示语:该页面不存在！', '1543665186', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/094.gif');
INSERT INTO `yzn_adminlog` VALUES ('855', '0', '1', '提示语:该页面不存在！', '1543665187', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/095.gif');
INSERT INTO `yzn_adminlog` VALUES ('856', '0', '1', '提示语:该页面不存在！', '1543665187', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/096.gif');
INSERT INTO `yzn_adminlog` VALUES ('857', '0', '1', '提示语:该页面不存在！', '1543665187', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/097.gif');
INSERT INTO `yzn_adminlog` VALUES ('858', '0', '1', '提示语:该页面不存在！', '1543665187', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/098.png');
INSERT INTO `yzn_adminlog` VALUES ('859', '0', '1', '提示语:该页面不存在！', '1543665187', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/099.png');
INSERT INTO `yzn_adminlog` VALUES ('860', '0', '1', '提示语:该页面不存在！', '1543665187', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/089.gif');
INSERT INTO `yzn_adminlog` VALUES ('861', '0', '1', '提示语:该页面不存在！', '1543665244', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/06.png');
INSERT INTO `yzn_adminlog` VALUES ('862', '0', '1', '提示语:该页面不存在！', '1543665244', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/04.png');
INSERT INTO `yzn_adminlog` VALUES ('863', '0', '1', '提示语:该页面不存在！', '1543665244', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/03.gif');
INSERT INTO `yzn_adminlog` VALUES ('864', '0', '1', '提示语:该页面不存在！', '1543665244', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/01.png');
INSERT INTO `yzn_adminlog` VALUES ('865', '0', '1', '提示语:该页面不存在！', '1543665244', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/05.png');
INSERT INTO `yzn_adminlog` VALUES ('866', '0', '1', '提示语:该页面不存在！', '1543665244', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/02.png');
INSERT INTO `yzn_adminlog` VALUES ('867', '0', '1', '提示语:该页面不存在！', '1543665244', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/026.png');
INSERT INTO `yzn_adminlog` VALUES ('868', '0', '1', '提示语:该页面不存在！', '1543665244', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/027.gif');
INSERT INTO `yzn_adminlog` VALUES ('869', '0', '1', '提示语:该页面不存在！', '1543665244', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/032.png');
INSERT INTO `yzn_adminlog` VALUES ('870', '0', '1', '提示语:该页面不存在！', '1543665244', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/015.png');
INSERT INTO `yzn_adminlog` VALUES ('871', '0', '1', '提示语:该页面不存在！', '1543665244', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/029.png');
INSERT INTO `yzn_adminlog` VALUES ('872', '0', '1', '提示语:该页面不存在！', '1543665244', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/016.png');
INSERT INTO `yzn_adminlog` VALUES ('873', '0', '1', '提示语:该页面不存在！', '1543665244', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/031.png');
INSERT INTO `yzn_adminlog` VALUES ('874', '0', '1', '提示语:该页面不存在！', '1543665244', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/021.png');
INSERT INTO `yzn_adminlog` VALUES ('875', '0', '1', '提示语:该页面不存在！', '1543665244', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/019.gif');
INSERT INTO `yzn_adminlog` VALUES ('876', '0', '1', '提示语:该页面不存在！', '1543665244', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/028.gif');
INSERT INTO `yzn_adminlog` VALUES ('877', '0', '1', '提示语:该页面不存在！', '1543665244', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/014.jpg');
INSERT INTO `yzn_adminlog` VALUES ('878', '0', '1', '提示语:该页面不存在！', '1543665244', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/010.png');
INSERT INTO `yzn_adminlog` VALUES ('879', '0', '1', '提示语:该页面不存在！', '1543665244', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/08.gif');
INSERT INTO `yzn_adminlog` VALUES ('880', '0', '1', '提示语:该页面不存在！', '1543665244', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/025.gif');
INSERT INTO `yzn_adminlog` VALUES ('881', '0', '1', '提示语:该页面不存在！', '1543665244', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/041.png');
INSERT INTO `yzn_adminlog` VALUES ('882', '0', '1', '提示语:该页面不存在！', '1543665244', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/023.gif');
INSERT INTO `yzn_adminlog` VALUES ('883', '0', '1', '提示语:该页面不存在！', '1543665244', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/012.gif');
INSERT INTO `yzn_adminlog` VALUES ('884', '0', '1', '提示语:该页面不存在！', '1543665244', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/033.png');
INSERT INTO `yzn_adminlog` VALUES ('885', '0', '1', '提示语:该页面不存在！', '1543665244', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/09.gif');
INSERT INTO `yzn_adminlog` VALUES ('886', '0', '1', '提示语:该页面不存在！', '1543665244', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/020.png');
INSERT INTO `yzn_adminlog` VALUES ('887', '0', '1', '提示语:该页面不存在！', '1543665244', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/045.png');
INSERT INTO `yzn_adminlog` VALUES ('888', '0', '1', '提示语:该页面不存在！', '1543665244', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/07.gif');
INSERT INTO `yzn_adminlog` VALUES ('889', '0', '1', '提示语:该页面不存在！', '1543665244', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/036.gif');
INSERT INTO `yzn_adminlog` VALUES ('890', '0', '1', '提示语:该页面不存在！', '1543665244', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/046.gif');
INSERT INTO `yzn_adminlog` VALUES ('891', '0', '1', '提示语:该页面不存在！', '1543665244', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/040.png');
INSERT INTO `yzn_adminlog` VALUES ('892', '0', '1', '提示语:该页面不存在！', '1543665244', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/037.png');
INSERT INTO `yzn_adminlog` VALUES ('893', '0', '1', '提示语:该页面不存在！', '1543665244', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/018.png');
INSERT INTO `yzn_adminlog` VALUES ('894', '0', '1', '提示语:该页面不存在！', '1543665244', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/034.png');
INSERT INTO `yzn_adminlog` VALUES ('895', '0', '1', '提示语:该页面不存在！', '1543665244', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/048.gif');
INSERT INTO `yzn_adminlog` VALUES ('896', '0', '1', '提示语:该页面不存在！', '1543665244', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/024.png');
INSERT INTO `yzn_adminlog` VALUES ('897', '0', '1', '提示语:该页面不存在！', '1543665244', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/011.jpg');
INSERT INTO `yzn_adminlog` VALUES ('898', '0', '1', '提示语:该页面不存在！', '1543665245', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/013.png');
INSERT INTO `yzn_adminlog` VALUES ('899', '0', '1', '提示语:该页面不存在！', '1543665245', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/042.png');
INSERT INTO `yzn_adminlog` VALUES ('900', '0', '1', '提示语:该页面不存在！', '1543665245', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/043.png');
INSERT INTO `yzn_adminlog` VALUES ('901', '0', '1', '提示语:该页面不存在！', '1543665245', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/049.gif');
INSERT INTO `yzn_adminlog` VALUES ('902', '0', '1', '提示语:该页面不存在！', '1543665245', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/017.png');
INSERT INTO `yzn_adminlog` VALUES ('903', '0', '1', '提示语:该页面不存在！', '1543665245', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/030.png');
INSERT INTO `yzn_adminlog` VALUES ('904', '0', '1', '提示语:该页面不存在！', '1543665245', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/038.gif');
INSERT INTO `yzn_adminlog` VALUES ('905', '0', '1', '提示语:该页面不存在！', '1543665245', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/047.png');
INSERT INTO `yzn_adminlog` VALUES ('906', '0', '1', '提示语:该页面不存在！', '1543665245', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/022.png');
INSERT INTO `yzn_adminlog` VALUES ('907', '0', '1', '提示语:该页面不存在！', '1543665245', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/044.png');
INSERT INTO `yzn_adminlog` VALUES ('908', '0', '1', '提示语:该页面不存在！', '1543665245', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/039.png');
INSERT INTO `yzn_adminlog` VALUES ('909', '0', '1', '提示语:该页面不存在！', '1543665245', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/050.gif');
INSERT INTO `yzn_adminlog` VALUES ('910', '0', '1', '提示语:该页面不存在！', '1543665245', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/051.gif');
INSERT INTO `yzn_adminlog` VALUES ('911', '0', '1', '提示语:该页面不存在！', '1543665245', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/052.gif');
INSERT INTO `yzn_adminlog` VALUES ('912', '0', '1', '提示语:该页面不存在！', '1543665245', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/053.gif');
INSERT INTO `yzn_adminlog` VALUES ('913', '0', '1', '提示语:该页面不存在！', '1543665245', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/054.gif');
INSERT INTO `yzn_adminlog` VALUES ('914', '0', '1', '提示语:该页面不存在！', '1543665245', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/055.gif');
INSERT INTO `yzn_adminlog` VALUES ('915', '0', '1', '提示语:该页面不存在！', '1543665245', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/056.gif');
INSERT INTO `yzn_adminlog` VALUES ('916', '0', '1', '提示语:该页面不存在！', '1543665245', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/057.gif');
INSERT INTO `yzn_adminlog` VALUES ('917', '0', '1', '提示语:该页面不存在！', '1543665245', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/058.gif');
INSERT INTO `yzn_adminlog` VALUES ('918', '0', '1', '提示语:该页面不存在！', '1543665245', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/059.gif');
INSERT INTO `yzn_adminlog` VALUES ('919', '0', '1', '提示语:该页面不存在！', '1543665245', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/060.gif');
INSERT INTO `yzn_adminlog` VALUES ('920', '0', '1', '提示语:该页面不存在！', '1543665245', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/061.gif');
INSERT INTO `yzn_adminlog` VALUES ('921', '0', '1', '提示语:该页面不存在！', '1543665245', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/062.gif');
INSERT INTO `yzn_adminlog` VALUES ('922', '0', '1', '提示语:该页面不存在！', '1543665245', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/063.gif');
INSERT INTO `yzn_adminlog` VALUES ('923', '0', '1', '提示语:该页面不存在！', '1543665245', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/064.gif');
INSERT INTO `yzn_adminlog` VALUES ('924', '0', '1', '提示语:该页面不存在！', '1543665245', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/065.png');
INSERT INTO `yzn_adminlog` VALUES ('925', '0', '1', '提示语:该页面不存在！', '1543665245', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/066.png');
INSERT INTO `yzn_adminlog` VALUES ('926', '0', '1', '提示语:该页面不存在！', '1543665245', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/067.gif');
INSERT INTO `yzn_adminlog` VALUES ('927', '0', '1', '提示语:该页面不存在！', '1543665245', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/068.png');
INSERT INTO `yzn_adminlog` VALUES ('928', '0', '1', '提示语:该页面不存在！', '1543665245', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/069.png');
INSERT INTO `yzn_adminlog` VALUES ('929', '0', '1', '提示语:该页面不存在！', '1543665246', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/070.png');
INSERT INTO `yzn_adminlog` VALUES ('930', '0', '1', '提示语:该页面不存在！', '1543665246', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/071.gif');
INSERT INTO `yzn_adminlog` VALUES ('931', '0', '1', '提示语:该页面不存在！', '1543665246', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/072.gif');
INSERT INTO `yzn_adminlog` VALUES ('932', '0', '1', '提示语:该页面不存在！', '1543665246', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/073.gif');
INSERT INTO `yzn_adminlog` VALUES ('933', '0', '1', '提示语:该页面不存在！', '1543665246', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/074.png');
INSERT INTO `yzn_adminlog` VALUES ('934', '0', '1', '提示语:该页面不存在！', '1543665246', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/035.png');
INSERT INTO `yzn_adminlog` VALUES ('935', '0', '1', '提示语:该页面不存在！', '1543665246', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/075.jpg');
INSERT INTO `yzn_adminlog` VALUES ('936', '0', '1', '提示语:该页面不存在！', '1543665246', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/076.png');
INSERT INTO `yzn_adminlog` VALUES ('937', '0', '1', '提示语:该页面不存在！', '1543665246', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/077.gif');
INSERT INTO `yzn_adminlog` VALUES ('938', '0', '1', '提示语:该页面不存在！', '1543665246', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/078.gif');
INSERT INTO `yzn_adminlog` VALUES ('939', '0', '1', '提示语:该页面不存在！', '1543665246', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/079.gif');
INSERT INTO `yzn_adminlog` VALUES ('940', '0', '1', '提示语:该页面不存在！', '1543665246', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/080.gif');
INSERT INTO `yzn_adminlog` VALUES ('941', '0', '1', '提示语:该页面不存在！', '1543665246', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/081.jpg');
INSERT INTO `yzn_adminlog` VALUES ('942', '0', '1', '提示语:该页面不存在！', '1543665246', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/082.png');
INSERT INTO `yzn_adminlog` VALUES ('943', '0', '1', '提示语:该页面不存在！', '1543665246', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/083.gif');
INSERT INTO `yzn_adminlog` VALUES ('944', '0', '1', '提示语:该页面不存在！', '1543665246', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/084.jpg');
INSERT INTO `yzn_adminlog` VALUES ('945', '0', '1', '提示语:该页面不存在！', '1543665246', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/085.png');
INSERT INTO `yzn_adminlog` VALUES ('946', '0', '1', '提示语:该页面不存在！', '1543665246', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/086.gif');
INSERT INTO `yzn_adminlog` VALUES ('947', '0', '1', '提示语:该页面不存在！', '1543665246', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/087.png');
INSERT INTO `yzn_adminlog` VALUES ('948', '0', '1', '提示语:该页面不存在！', '1543665246', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/088.gif');
INSERT INTO `yzn_adminlog` VALUES ('949', '0', '1', '提示语:该页面不存在！', '1543665246', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/089.gif');
INSERT INTO `yzn_adminlog` VALUES ('950', '0', '1', '提示语:该页面不存在！', '1543665246', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/090.png');
INSERT INTO `yzn_adminlog` VALUES ('951', '0', '1', '提示语:该页面不存在！', '1543665246', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/091.png');
INSERT INTO `yzn_adminlog` VALUES ('952', '0', '1', '提示语:该页面不存在！', '1543665246', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/092.gif');
INSERT INTO `yzn_adminlog` VALUES ('953', '0', '1', '提示语:该页面不存在！', '1543665246', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/093.gif');
INSERT INTO `yzn_adminlog` VALUES ('954', '0', '1', '提示语:该页面不存在！', '1543665246', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/094.gif');
INSERT INTO `yzn_adminlog` VALUES ('955', '0', '1', '提示语:该页面不存在！', '1543665246', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/095.gif');
INSERT INTO `yzn_adminlog` VALUES ('956', '0', '1', '提示语:该页面不存在！', '1543665246', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/097.gif');
INSERT INTO `yzn_adminlog` VALUES ('957', '0', '1', '提示语:该页面不存在！', '1543665246', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/098.png');
INSERT INTO `yzn_adminlog` VALUES ('958', '0', '1', '提示语:该页面不存在！', '1543665246', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/099.png');
INSERT INTO `yzn_adminlog` VALUES ('959', '0', '1', '提示语:该页面不存在！', '1543665247', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/096.gif');
INSERT INTO `yzn_adminlog` VALUES ('960', '0', '1', '提示语:该页面不存在！', '1543665491', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/01.png');
INSERT INTO `yzn_adminlog` VALUES ('961', '0', '1', '提示语:该页面不存在！', '1543665491', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/023.gif');
INSERT INTO `yzn_adminlog` VALUES ('962', '0', '1', '提示语:该页面不存在！', '1543665491', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/017.png');
INSERT INTO `yzn_adminlog` VALUES ('963', '0', '1', '提示语:该页面不存在！', '1543665491', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/038.gif');
INSERT INTO `yzn_adminlog` VALUES ('964', '0', '1', '提示语:该页面不存在！', '1543665491', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/033.png');
INSERT INTO `yzn_adminlog` VALUES ('965', '0', '1', '提示语:该页面不存在！', '1543665491', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/034.png');
INSERT INTO `yzn_adminlog` VALUES ('966', '0', '1', '提示语:该页面不存在！', '1543665491', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/030.png');
INSERT INTO `yzn_adminlog` VALUES ('967', '0', '1', '提示语:该页面不存在！', '1543665491', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/041.png');
INSERT INTO `yzn_adminlog` VALUES ('968', '0', '1', '提示语:该页面不存在！', '1543665491', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/026.png');
INSERT INTO `yzn_adminlog` VALUES ('969', '0', '1', '提示语:该页面不存在！', '1543665491', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/035.png');
INSERT INTO `yzn_adminlog` VALUES ('970', '0', '1', '提示语:该页面不存在！', '1543665491', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/012.gif');
INSERT INTO `yzn_adminlog` VALUES ('971', '0', '1', '提示语:该页面不存在！', '1543665491', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/027.gif');
INSERT INTO `yzn_adminlog` VALUES ('972', '0', '1', '提示语:该页面不存在！', '1543665491', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/015.png');
INSERT INTO `yzn_adminlog` VALUES ('973', '0', '1', '提示语:该页面不存在！', '1543665491', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/09.gif');
INSERT INTO `yzn_adminlog` VALUES ('974', '0', '1', '提示语:该页面不存在！', '1543665492', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/016.png');
INSERT INTO `yzn_adminlog` VALUES ('975', '0', '1', '提示语:该页面不存在！', '1543665492', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/02.png');
INSERT INTO `yzn_adminlog` VALUES ('976', '0', '1', '提示语:该页面不存在！', '1543665492', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/04.png');
INSERT INTO `yzn_adminlog` VALUES ('977', '0', '1', '提示语:该页面不存在！', '1543665492', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/06.png');
INSERT INTO `yzn_adminlog` VALUES ('978', '0', '1', '提示语:该页面不存在！', '1543665492', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/020.png');
INSERT INTO `yzn_adminlog` VALUES ('979', '0', '1', '提示语:该页面不存在！', '1543665492', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/05.png');
INSERT INTO `yzn_adminlog` VALUES ('980', '0', '1', '提示语:该页面不存在！', '1543665492', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/03.gif');
INSERT INTO `yzn_adminlog` VALUES ('981', '0', '1', '提示语:该页面不存在！', '1543665492', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/029.png');
INSERT INTO `yzn_adminlog` VALUES ('982', '0', '1', '提示语:该页面不存在！', '1543665492', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/047.png');
INSERT INTO `yzn_adminlog` VALUES ('983', '0', '1', '提示语:该页面不存在！', '1543665492', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/045.png');
INSERT INTO `yzn_adminlog` VALUES ('984', '0', '1', '提示语:该页面不存在！', '1543665492', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/07.gif');
INSERT INTO `yzn_adminlog` VALUES ('985', '0', '1', '提示语:该页面不存在！', '1543665492', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/036.gif');
INSERT INTO `yzn_adminlog` VALUES ('986', '0', '1', '提示语:该页面不存在！', '1543665492', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/031.png');
INSERT INTO `yzn_adminlog` VALUES ('987', '0', '1', '提示语:该页面不存在！', '1543665492', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/046.gif');
INSERT INTO `yzn_adminlog` VALUES ('988', '0', '1', '提示语:该页面不存在！', '1543665492', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/040.png');
INSERT INTO `yzn_adminlog` VALUES ('989', '0', '1', '提示语:该页面不存在！', '1543665492', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/021.png');
INSERT INTO `yzn_adminlog` VALUES ('990', '0', '1', '提示语:该页面不存在！', '1543665492', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/037.png');
INSERT INTO `yzn_adminlog` VALUES ('991', '0', '1', '提示语:该页面不存在！', '1543665492', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/018.png');
INSERT INTO `yzn_adminlog` VALUES ('992', '0', '1', '提示语:该页面不存在！', '1543665492', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/019.gif');
INSERT INTO `yzn_adminlog` VALUES ('993', '0', '1', '提示语:该页面不存在！', '1543665492', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/048.gif');
INSERT INTO `yzn_adminlog` VALUES ('994', '0', '1', '提示语:该页面不存在！', '1543665492', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/024.png');
INSERT INTO `yzn_adminlog` VALUES ('995', '0', '1', '提示语:该页面不存在！', '1543665492', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/011.jpg');
INSERT INTO `yzn_adminlog` VALUES ('996', '0', '1', '提示语:该页面不存在！', '1543665492', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/028.gif');
INSERT INTO `yzn_adminlog` VALUES ('997', '0', '1', '提示语:该页面不存在！', '1543665492', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/014.jpg');
INSERT INTO `yzn_adminlog` VALUES ('998', '0', '1', '提示语:该页面不存在！', '1543665492', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/013.png');
INSERT INTO `yzn_adminlog` VALUES ('999', '0', '1', '提示语:该页面不存在！', '1543665492', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/010.png');
INSERT INTO `yzn_adminlog` VALUES ('1000', '0', '1', '提示语:该页面不存在！', '1543665492', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/042.png');
INSERT INTO `yzn_adminlog` VALUES ('1001', '0', '1', '提示语:该页面不存在！', '1543665492', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/08.gif');
INSERT INTO `yzn_adminlog` VALUES ('1002', '0', '1', '提示语:该页面不存在！', '1543665492', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/022.png');
INSERT INTO `yzn_adminlog` VALUES ('1003', '0', '1', '提示语:该页面不存在！', '1543665492', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/043.png');
INSERT INTO `yzn_adminlog` VALUES ('1004', '0', '1', '提示语:该页面不存在！', '1543665492', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/044.png');
INSERT INTO `yzn_adminlog` VALUES ('1005', '0', '1', '提示语:该页面不存在！', '1543665492', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/039.png');
INSERT INTO `yzn_adminlog` VALUES ('1006', '0', '1', '提示语:该页面不存在！', '1543665493', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/025.gif');
INSERT INTO `yzn_adminlog` VALUES ('1007', '0', '1', '提示语:该页面不存在！', '1543665493', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/049.gif');
INSERT INTO `yzn_adminlog` VALUES ('1008', '0', '1', '提示语:该页面不存在！', '1543665493', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/050.gif');
INSERT INTO `yzn_adminlog` VALUES ('1009', '0', '1', '提示语:该页面不存在！', '1543665493', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/051.gif');
INSERT INTO `yzn_adminlog` VALUES ('1010', '0', '1', '提示语:该页面不存在！', '1543665494', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/032.png');
INSERT INTO `yzn_adminlog` VALUES ('1011', '0', '1', '提示语:该页面不存在！', '1543665513', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/01.png');
INSERT INTO `yzn_adminlog` VALUES ('1012', '0', '1', '提示语:该页面不存在！', '1543665513', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/05.png');
INSERT INTO `yzn_adminlog` VALUES ('1013', '0', '1', '提示语:该页面不存在！', '1543665513', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/03.gif');
INSERT INTO `yzn_adminlog` VALUES ('1014', '0', '1', '提示语:该页面不存在！', '1543665513', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/02.png');
INSERT INTO `yzn_adminlog` VALUES ('1015', '0', '1', '提示语:该页面不存在！', '1543665513', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/04.png');
INSERT INTO `yzn_adminlog` VALUES ('1016', '0', '1', '提示语:该页面不存在！', '1543665513', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/06.png');
INSERT INTO `yzn_adminlog` VALUES ('1017', '0', '1', '提示语:该页面不存在！', '1543665513', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/033.png');
INSERT INTO `yzn_adminlog` VALUES ('1018', '0', '1', '提示语:该页面不存在！', '1543665513', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/015.png');
INSERT INTO `yzn_adminlog` VALUES ('1019', '0', '1', '提示语:该页面不存在！', '1543665513', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/036.gif');
INSERT INTO `yzn_adminlog` VALUES ('1020', '0', '1', '提示语:该页面不存在！', '1543665513', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/031.png');
INSERT INTO `yzn_adminlog` VALUES ('1021', '0', '1', '提示语:该页面不存在！', '1543665513', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/019.gif');
INSERT INTO `yzn_adminlog` VALUES ('1022', '0', '1', '提示语:该页面不存在！', '1543665513', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/044.png');
INSERT INTO `yzn_adminlog` VALUES ('1023', '0', '1', '提示语:该页面不存在！', '1543665514', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/039.png');
INSERT INTO `yzn_adminlog` VALUES ('1024', '0', '1', '提示语:该页面不存在！', '1543665514', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/017.png');
INSERT INTO `yzn_adminlog` VALUES ('1025', '0', '1', '提示语:该页面不存在！', '1543665514', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/030.png');
INSERT INTO `yzn_adminlog` VALUES ('1026', '0', '1', '提示语:该页面不存在！', '1543665514', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/041.png');
INSERT INTO `yzn_adminlog` VALUES ('1027', '0', '1', '提示语:该页面不存在！', '1543665514', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/023.gif');
INSERT INTO `yzn_adminlog` VALUES ('1028', '0', '1', '提示语:该页面不存在！', '1543665514', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/026.png');
INSERT INTO `yzn_adminlog` VALUES ('1029', '0', '1', '提示语:该页面不存在！', '1543665514', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/035.png');
INSERT INTO `yzn_adminlog` VALUES ('1030', '0', '1', '提示语:该页面不存在！', '1543665514', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/038.gif');
INSERT INTO `yzn_adminlog` VALUES ('1031', '0', '1', '提示语:该页面不存在！', '1543665514', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/012.gif');
INSERT INTO `yzn_adminlog` VALUES ('1032', '0', '1', '提示语:该页面不存在！', '1543665514', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/027.gif');
INSERT INTO `yzn_adminlog` VALUES ('1033', '0', '1', '提示语:该页面不存在！', '1543665514', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/032.png');
INSERT INTO `yzn_adminlog` VALUES ('1034', '0', '1', '提示语:该页面不存在！', '1543665514', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/029.png');
INSERT INTO `yzn_adminlog` VALUES ('1035', '0', '1', '提示语:该页面不存在！', '1543665514', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/016.png');
INSERT INTO `yzn_adminlog` VALUES ('1036', '0', '1', '提示语:该页面不存在！', '1543665514', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/09.gif');
INSERT INTO `yzn_adminlog` VALUES ('1037', '0', '1', '提示语:该页面不存在！', '1543665514', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/020.png');
INSERT INTO `yzn_adminlog` VALUES ('1038', '0', '1', '提示语:该页面不存在！', '1543665514', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/047.png');
INSERT INTO `yzn_adminlog` VALUES ('1039', '0', '1', '提示语:该页面不存在！', '1543665514', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/045.png');
INSERT INTO `yzn_adminlog` VALUES ('1040', '0', '1', '提示语:该页面不存在！', '1543665514', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/07.gif');
INSERT INTO `yzn_adminlog` VALUES ('1041', '0', '1', '提示语:该页面不存在！', '1543665514', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/046.gif');
INSERT INTO `yzn_adminlog` VALUES ('1042', '0', '1', '提示语:该页面不存在！', '1543665514', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/040.png');
INSERT INTO `yzn_adminlog` VALUES ('1043', '0', '1', '提示语:该页面不存在！', '1543665514', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/021.png');
INSERT INTO `yzn_adminlog` VALUES ('1044', '0', '1', '提示语:该页面不存在！', '1543665514', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/037.png');
INSERT INTO `yzn_adminlog` VALUES ('1045', '0', '1', '提示语:该页面不存在！', '1543665514', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/018.png');
INSERT INTO `yzn_adminlog` VALUES ('1046', '0', '1', '提示语:该页面不存在！', '1543665514', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/034.png');
INSERT INTO `yzn_adminlog` VALUES ('1047', '0', '1', '提示语:该页面不存在！', '1543665514', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/048.gif');
INSERT INTO `yzn_adminlog` VALUES ('1048', '0', '1', '提示语:该页面不存在！', '1543665514', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/024.png');
INSERT INTO `yzn_adminlog` VALUES ('1049', '0', '1', '提示语:该页面不存在！', '1543665514', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/011.jpg');
INSERT INTO `yzn_adminlog` VALUES ('1050', '0', '1', '提示语:该页面不存在！', '1543665514', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/028.gif');
INSERT INTO `yzn_adminlog` VALUES ('1051', '0', '1', '提示语:该页面不存在！', '1543665514', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/014.jpg');
INSERT INTO `yzn_adminlog` VALUES ('1052', '0', '1', '提示语:该页面不存在！', '1543665514', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/013.png');
INSERT INTO `yzn_adminlog` VALUES ('1053', '0', '1', '提示语:该页面不存在！', '1543665514', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/010.png');
INSERT INTO `yzn_adminlog` VALUES ('1054', '0', '1', '提示语:该页面不存在！', '1543665514', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/042.png');
INSERT INTO `yzn_adminlog` VALUES ('1055', '0', '1', '提示语:该页面不存在！', '1543665514', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/08.gif');
INSERT INTO `yzn_adminlog` VALUES ('1056', '0', '1', '提示语:该页面不存在！', '1543665514', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/022.png');
INSERT INTO `yzn_adminlog` VALUES ('1057', '0', '1', '提示语:该页面不存在！', '1543665514', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/043.png');
INSERT INTO `yzn_adminlog` VALUES ('1058', '0', '1', '提示语:该页面不存在！', '1543665514', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/025.gif');
INSERT INTO `yzn_adminlog` VALUES ('1059', '0', '1', '提示语:该页面不存在！', '1543665514', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/049.gif');
INSERT INTO `yzn_adminlog` VALUES ('1060', '0', '1', '提示语:该页面不存在！', '1543665515', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/050.gif');
INSERT INTO `yzn_adminlog` VALUES ('1061', '0', '1', '提示语:该页面不存在！', '1543665515', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/051.gif');
INSERT INTO `yzn_adminlog` VALUES ('1062', '0', '1', '提示语:该页面不存在！', '1543665515', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/052.gif');
INSERT INTO `yzn_adminlog` VALUES ('1063', '0', '1', '提示语:该页面不存在！', '1543665515', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/053.gif');
INSERT INTO `yzn_adminlog` VALUES ('1064', '0', '1', '提示语:该页面不存在！', '1543665515', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/054.gif');
INSERT INTO `yzn_adminlog` VALUES ('1065', '0', '1', '提示语:该页面不存在！', '1543665515', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/055.gif');
INSERT INTO `yzn_adminlog` VALUES ('1066', '0', '1', '提示语:该页面不存在！', '1543665515', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/056.gif');
INSERT INTO `yzn_adminlog` VALUES ('1067', '0', '1', '提示语:该页面不存在！', '1543665515', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/058.gif');
INSERT INTO `yzn_adminlog` VALUES ('1068', '0', '1', '提示语:该页面不存在！', '1543665515', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/059.gif');
INSERT INTO `yzn_adminlog` VALUES ('1069', '0', '1', '提示语:该页面不存在！', '1543665515', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/060.gif');
INSERT INTO `yzn_adminlog` VALUES ('1070', '0', '1', '提示语:该页面不存在！', '1543665515', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/061.gif');
INSERT INTO `yzn_adminlog` VALUES ('1071', '0', '1', '提示语:该页面不存在！', '1543665515', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/062.gif');
INSERT INTO `yzn_adminlog` VALUES ('1072', '0', '1', '提示语:该页面不存在！', '1543665515', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/063.gif');
INSERT INTO `yzn_adminlog` VALUES ('1073', '0', '1', '提示语:该页面不存在！', '1543665515', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/064.gif');
INSERT INTO `yzn_adminlog` VALUES ('1074', '0', '1', '提示语:该页面不存在！', '1543665515', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/065.png');
INSERT INTO `yzn_adminlog` VALUES ('1075', '0', '1', '提示语:该页面不存在！', '1543665515', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/066.png');
INSERT INTO `yzn_adminlog` VALUES ('1076', '0', '1', '提示语:该页面不存在！', '1543665515', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/067.gif');
INSERT INTO `yzn_adminlog` VALUES ('1077', '0', '1', '提示语:该页面不存在！', '1543665515', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/068.png');
INSERT INTO `yzn_adminlog` VALUES ('1078', '0', '1', '提示语:该页面不存在！', '1543665515', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/069.png');
INSERT INTO `yzn_adminlog` VALUES ('1079', '0', '1', '提示语:该页面不存在！', '1543665515', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/070.png');
INSERT INTO `yzn_adminlog` VALUES ('1080', '0', '1', '提示语:该页面不存在！', '1543665515', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/071.gif');
INSERT INTO `yzn_adminlog` VALUES ('1081', '0', '1', '提示语:该页面不存在！', '1543665515', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/072.gif');
INSERT INTO `yzn_adminlog` VALUES ('1082', '0', '1', '提示语:该页面不存在！', '1543665515', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/073.gif');
INSERT INTO `yzn_adminlog` VALUES ('1083', '0', '1', '提示语:该页面不存在！', '1543665515', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/074.png');
INSERT INTO `yzn_adminlog` VALUES ('1084', '0', '1', '提示语:该页面不存在！', '1543665515', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/075.jpg');
INSERT INTO `yzn_adminlog` VALUES ('1085', '0', '1', '提示语:该页面不存在！', '1543665515', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/076.png');
INSERT INTO `yzn_adminlog` VALUES ('1086', '0', '1', '提示语:该页面不存在！', '1543665515', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/077.gif');
INSERT INTO `yzn_adminlog` VALUES ('1087', '0', '1', '提示语:该页面不存在！', '1543665515', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/078.gif');
INSERT INTO `yzn_adminlog` VALUES ('1088', '0', '1', '提示语:该页面不存在！', '1543665515', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/079.gif');
INSERT INTO `yzn_adminlog` VALUES ('1089', '0', '1', '提示语:该页面不存在！', '1543665515', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/080.gif');
INSERT INTO `yzn_adminlog` VALUES ('1090', '0', '1', '提示语:该页面不存在！', '1543665515', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/081.jpg');
INSERT INTO `yzn_adminlog` VALUES ('1091', '0', '1', '提示语:该页面不存在！', '1543665515', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/082.png');
INSERT INTO `yzn_adminlog` VALUES ('1092', '0', '1', '提示语:该页面不存在！', '1543665515', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/083.gif');
INSERT INTO `yzn_adminlog` VALUES ('1093', '0', '1', '提示语:该页面不存在！', '1543665515', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/084.jpg');
INSERT INTO `yzn_adminlog` VALUES ('1094', '0', '1', '提示语:该页面不存在！', '1543665515', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/085.png');
INSERT INTO `yzn_adminlog` VALUES ('1095', '0', '1', '提示语:该页面不存在！', '1543665515', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/086.gif');
INSERT INTO `yzn_adminlog` VALUES ('1096', '0', '1', '提示语:该页面不存在！', '1543665516', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/087.png');
INSERT INTO `yzn_adminlog` VALUES ('1097', '0', '1', '提示语:该页面不存在！', '1543665516', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/088.gif');
INSERT INTO `yzn_adminlog` VALUES ('1098', '0', '1', '提示语:该页面不存在！', '1543665516', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/089.gif');
INSERT INTO `yzn_adminlog` VALUES ('1099', '0', '1', '提示语:该页面不存在！', '1543665516', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/090.png');
INSERT INTO `yzn_adminlog` VALUES ('1100', '0', '1', '提示语:该页面不存在！', '1543665516', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/091.png');
INSERT INTO `yzn_adminlog` VALUES ('1101', '0', '1', '提示语:该页面不存在！', '1543665516', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/092.gif');
INSERT INTO `yzn_adminlog` VALUES ('1102', '0', '1', '提示语:该页面不存在！', '1543665516', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/057.gif');
INSERT INTO `yzn_adminlog` VALUES ('1103', '0', '1', '提示语:该页面不存在！', '1543665516', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/093.gif');
INSERT INTO `yzn_adminlog` VALUES ('1104', '0', '1', '提示语:该页面不存在！', '1543665516', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/094.gif');
INSERT INTO `yzn_adminlog` VALUES ('1105', '0', '1', '提示语:该页面不存在！', '1543665516', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/095.gif');
INSERT INTO `yzn_adminlog` VALUES ('1106', '0', '1', '提示语:该页面不存在！', '1543665516', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/096.gif');
INSERT INTO `yzn_adminlog` VALUES ('1107', '0', '1', '提示语:该页面不存在！', '1543665516', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/097.gif');
INSERT INTO `yzn_adminlog` VALUES ('1108', '0', '1', '提示语:该页面不存在！', '1543665516', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/098.png');
INSERT INTO `yzn_adminlog` VALUES ('1109', '0', '1', '提示语:该页面不存在！', '1543665516', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/images/099.png');
INSERT INTO `yzn_adminlog` VALUES ('1110', '0', '0', '提示语:请先登陆', '1543666719', '0', '/yzncms/public/index.php/admin');
INSERT INTO `yzn_adminlog` VALUES ('1111', '1', '1', '提示语:恭喜您，登陆成功', '1543668002', '0', '/yzncms/index.php/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('1112', '0', '1', '提示语:该页面不存在！', '1543668163', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/04.png');
INSERT INTO `yzn_adminlog` VALUES ('1113', '0', '1', '提示语:该页面不存在！', '1543668163', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/017.png');
INSERT INTO `yzn_adminlog` VALUES ('1114', '0', '1', '提示语:该页面不存在！', '1543668163', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/023.gif');
INSERT INTO `yzn_adminlog` VALUES ('1115', '0', '1', '提示语:该页面不存在！', '1543668163', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/026.png');
INSERT INTO `yzn_adminlog` VALUES ('1116', '0', '1', '提示语:该页面不存在！', '1543668163', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/012.gif');
INSERT INTO `yzn_adminlog` VALUES ('1117', '0', '1', '提示语:该页面不存在！', '1543668163', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/015.png');
INSERT INTO `yzn_adminlog` VALUES ('1118', '0', '1', '提示语:该页面不存在！', '1543668163', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/016.png');
INSERT INTO `yzn_adminlog` VALUES ('1119', '0', '1', '提示语:该页面不存在！', '1543668163', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/020.png');
INSERT INTO `yzn_adminlog` VALUES ('1120', '0', '1', '提示语:该页面不存在！', '1543668163', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/09.gif');
INSERT INTO `yzn_adminlog` VALUES ('1121', '0', '1', '提示语:该页面不存在！', '1543668163', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/07.gif');
INSERT INTO `yzn_adminlog` VALUES ('1122', '0', '1', '提示语:该页面不存在！', '1543668163', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/021.png');
INSERT INTO `yzn_adminlog` VALUES ('1123', '0', '1', '提示语:该页面不存在！', '1543668164', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/018.png');
INSERT INTO `yzn_adminlog` VALUES ('1124', '0', '1', '提示语:该页面不存在！', '1543668164', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/019.gif');
INSERT INTO `yzn_adminlog` VALUES ('1125', '0', '1', '提示语:该页面不存在！', '1543668164', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/024.png');
INSERT INTO `yzn_adminlog` VALUES ('1126', '0', '1', '提示语:该页面不存在！', '1543668164', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/011.jpg');
INSERT INTO `yzn_adminlog` VALUES ('1127', '0', '1', '提示语:该页面不存在！', '1543668164', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/05.png');
INSERT INTO `yzn_adminlog` VALUES ('1128', '0', '1', '提示语:该页面不存在！', '1543668164', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/06.png');
INSERT INTO `yzn_adminlog` VALUES ('1129', '0', '1', '提示语:该页面不存在！', '1543668164', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/01.png');
INSERT INTO `yzn_adminlog` VALUES ('1130', '0', '1', '提示语:该页面不存在！', '1543668164', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/014.jpg');
INSERT INTO `yzn_adminlog` VALUES ('1131', '0', '1', '提示语:该页面不存在！', '1543668164', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/02.png');
INSERT INTO `yzn_adminlog` VALUES ('1132', '0', '1', '提示语:该页面不存在！', '1543668164', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/03.gif');
INSERT INTO `yzn_adminlog` VALUES ('1133', '0', '1', '提示语:该页面不存在！', '1543668164', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/013.png');
INSERT INTO `yzn_adminlog` VALUES ('1134', '0', '1', '提示语:该页面不存在！', '1543668164', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/010.png');
INSERT INTO `yzn_adminlog` VALUES ('1135', '0', '1', '提示语:该页面不存在！', '1543668164', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/08.gif');
INSERT INTO `yzn_adminlog` VALUES ('1136', '0', '1', '提示语:该页面不存在！', '1543668164', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/022.png');
INSERT INTO `yzn_adminlog` VALUES ('1137', '0', '1', '提示语:该页面不存在！', '1543668164', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/025.gif');
INSERT INTO `yzn_adminlog` VALUES ('1138', '0', '1', '提示语:该页面不存在！', '1543668164', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/030.png');
INSERT INTO `yzn_adminlog` VALUES ('1139', '0', '1', '提示语:该页面不存在！', '1543668164', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/041.png');
INSERT INTO `yzn_adminlog` VALUES ('1140', '0', '1', '提示语:该页面不存在！', '1543668164', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/035.png');
INSERT INTO `yzn_adminlog` VALUES ('1141', '0', '1', '提示语:该页面不存在！', '1543668164', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/038.gif');
INSERT INTO `yzn_adminlog` VALUES ('1142', '0', '1', '提示语:该页面不存在！', '1543668164', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/033.png');
INSERT INTO `yzn_adminlog` VALUES ('1143', '0', '1', '提示语:该页面不存在！', '1543668164', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/027.gif');
INSERT INTO `yzn_adminlog` VALUES ('1144', '0', '1', '提示语:该页面不存在！', '1543668164', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/032.png');
INSERT INTO `yzn_adminlog` VALUES ('1145', '0', '1', '提示语:该页面不存在！', '1543668164', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/029.png');
INSERT INTO `yzn_adminlog` VALUES ('1146', '0', '1', '提示语:该页面不存在！', '1543668164', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/047.png');
INSERT INTO `yzn_adminlog` VALUES ('1147', '0', '1', '提示语:该页面不存在！', '1543668164', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/045.png');
INSERT INTO `yzn_adminlog` VALUES ('1148', '0', '1', '提示语:该页面不存在！', '1543668164', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/036.gif');
INSERT INTO `yzn_adminlog` VALUES ('1149', '0', '1', '提示语:该页面不存在！', '1543668164', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/031.png');
INSERT INTO `yzn_adminlog` VALUES ('1150', '0', '1', '提示语:该页面不存在！', '1543668164', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/046.gif');
INSERT INTO `yzn_adminlog` VALUES ('1151', '0', '1', '提示语:该页面不存在！', '1543668165', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/040.png');
INSERT INTO `yzn_adminlog` VALUES ('1152', '0', '1', '提示语:该页面不存在！', '1543668165', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/037.png');
INSERT INTO `yzn_adminlog` VALUES ('1153', '0', '1', '提示语:该页面不存在！', '1543668165', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/034.png');
INSERT INTO `yzn_adminlog` VALUES ('1154', '0', '1', '提示语:该页面不存在！', '1543668165', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/048.gif');
INSERT INTO `yzn_adminlog` VALUES ('1155', '0', '1', '提示语:该页面不存在！', '1543668165', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/028.gif');
INSERT INTO `yzn_adminlog` VALUES ('1156', '0', '1', '提示语:该页面不存在！', '1543668165', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/042.png');
INSERT INTO `yzn_adminlog` VALUES ('1157', '0', '1', '提示语:该页面不存在！', '1543668165', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/043.png');
INSERT INTO `yzn_adminlog` VALUES ('1158', '0', '1', '提示语:该页面不存在！', '1543668165', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/044.png');
INSERT INTO `yzn_adminlog` VALUES ('1159', '0', '1', '提示语:该页面不存在！', '1543668165', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/039.png');
INSERT INTO `yzn_adminlog` VALUES ('1160', '0', '1', '提示语:该页面不存在！', '1543668165', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/049.gif');
INSERT INTO `yzn_adminlog` VALUES ('1161', '0', '1', '提示语:该页面不存在！', '1543668165', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/050.gif');
INSERT INTO `yzn_adminlog` VALUES ('1162', '0', '1', '提示语:该页面不存在！', '1543668165', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/051.gif');
INSERT INTO `yzn_adminlog` VALUES ('1163', '0', '1', '提示语:该页面不存在！', '1543668165', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/052.gif');
INSERT INTO `yzn_adminlog` VALUES ('1164', '0', '1', '提示语:该页面不存在！', '1543668165', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/053.gif');
INSERT INTO `yzn_adminlog` VALUES ('1165', '0', '1', '提示语:该页面不存在！', '1543668165', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/054.gif');
INSERT INTO `yzn_adminlog` VALUES ('1166', '0', '1', '提示语:该页面不存在！', '1543668165', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/055.gif');
INSERT INTO `yzn_adminlog` VALUES ('1167', '0', '1', '提示语:该页面不存在！', '1543668165', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/056.gif');
INSERT INTO `yzn_adminlog` VALUES ('1168', '0', '1', '提示语:该页面不存在！', '1543668165', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/057.gif');
INSERT INTO `yzn_adminlog` VALUES ('1169', '0', '1', '提示语:该页面不存在！', '1543668165', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/058.gif');
INSERT INTO `yzn_adminlog` VALUES ('1170', '0', '1', '提示语:该页面不存在！', '1543668165', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/059.gif');
INSERT INTO `yzn_adminlog` VALUES ('1171', '0', '1', '提示语:该页面不存在！', '1543668165', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/061.gif');
INSERT INTO `yzn_adminlog` VALUES ('1172', '0', '1', '提示语:该页面不存在！', '1543668165', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/062.gif');
INSERT INTO `yzn_adminlog` VALUES ('1173', '0', '1', '提示语:该页面不存在！', '1543668165', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/063.gif');
INSERT INTO `yzn_adminlog` VALUES ('1174', '0', '1', '提示语:该页面不存在！', '1543668165', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/064.gif');
INSERT INTO `yzn_adminlog` VALUES ('1175', '0', '1', '提示语:该页面不存在！', '1543668165', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/065.png');
INSERT INTO `yzn_adminlog` VALUES ('1176', '0', '1', '提示语:该页面不存在！', '1543668165', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/066.png');
INSERT INTO `yzn_adminlog` VALUES ('1177', '0', '1', '提示语:该页面不存在！', '1543668165', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/067.gif');
INSERT INTO `yzn_adminlog` VALUES ('1178', '0', '1', '提示语:该页面不存在！', '1543668165', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/068.png');
INSERT INTO `yzn_adminlog` VALUES ('1179', '0', '1', '提示语:该页面不存在！', '1543668165', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/069.png');
INSERT INTO `yzn_adminlog` VALUES ('1180', '0', '1', '提示语:该页面不存在！', '1543668165', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/070.png');
INSERT INTO `yzn_adminlog` VALUES ('1181', '0', '1', '提示语:该页面不存在！', '1543668165', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/071.gif');
INSERT INTO `yzn_adminlog` VALUES ('1182', '0', '1', '提示语:该页面不存在！', '1543668165', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/072.gif');
INSERT INTO `yzn_adminlog` VALUES ('1183', '0', '1', '提示语:该页面不存在！', '1543668165', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/073.gif');
INSERT INTO `yzn_adminlog` VALUES ('1184', '0', '1', '提示语:该页面不存在！', '1543668166', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/074.png');
INSERT INTO `yzn_adminlog` VALUES ('1185', '0', '1', '提示语:该页面不存在！', '1543668166', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/075.jpg');
INSERT INTO `yzn_adminlog` VALUES ('1186', '0', '1', '提示语:该页面不存在！', '1543668166', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/076.png');
INSERT INTO `yzn_adminlog` VALUES ('1187', '0', '1', '提示语:该页面不存在！', '1543668166', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/077.gif');
INSERT INTO `yzn_adminlog` VALUES ('1188', '0', '1', '提示语:该页面不存在！', '1543668166', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/078.gif');
INSERT INTO `yzn_adminlog` VALUES ('1189', '0', '1', '提示语:该页面不存在！', '1543668166', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/079.gif');
INSERT INTO `yzn_adminlog` VALUES ('1190', '0', '1', '提示语:该页面不存在！', '1543668166', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/080.gif');
INSERT INTO `yzn_adminlog` VALUES ('1191', '0', '1', '提示语:该页面不存在！', '1543668166', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/081.jpg');
INSERT INTO `yzn_adminlog` VALUES ('1192', '0', '1', '提示语:该页面不存在！', '1543668166', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/082.png');
INSERT INTO `yzn_adminlog` VALUES ('1193', '0', '1', '提示语:该页面不存在！', '1543668166', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/083.gif');
INSERT INTO `yzn_adminlog` VALUES ('1194', '0', '1', '提示语:该页面不存在！', '1543668166', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/084.jpg');
INSERT INTO `yzn_adminlog` VALUES ('1195', '0', '1', '提示语:该页面不存在！', '1543668166', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/085.png');
INSERT INTO `yzn_adminlog` VALUES ('1196', '0', '1', '提示语:该页面不存在！', '1543668166', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/086.gif');
INSERT INTO `yzn_adminlog` VALUES ('1197', '0', '1', '提示语:该页面不存在！', '1543668166', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/087.png');
INSERT INTO `yzn_adminlog` VALUES ('1198', '0', '1', '提示语:该页面不存在！', '1543668166', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/088.gif');
INSERT INTO `yzn_adminlog` VALUES ('1199', '0', '1', '提示语:该页面不存在！', '1543668166', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/089.gif');
INSERT INTO `yzn_adminlog` VALUES ('1200', '0', '1', '提示语:该页面不存在！', '1543668166', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/090.png');
INSERT INTO `yzn_adminlog` VALUES ('1201', '0', '1', '提示语:该页面不存在！', '1543668166', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/091.png');
INSERT INTO `yzn_adminlog` VALUES ('1202', '0', '1', '提示语:该页面不存在！', '1543668166', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/092.gif');
INSERT INTO `yzn_adminlog` VALUES ('1203', '0', '1', '提示语:该页面不存在！', '1543668166', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/060.gif');
INSERT INTO `yzn_adminlog` VALUES ('1204', '0', '1', '提示语:该页面不存在！', '1543668166', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/093.gif');
INSERT INTO `yzn_adminlog` VALUES ('1205', '0', '1', '提示语:该页面不存在！', '1543668166', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/094.gif');
INSERT INTO `yzn_adminlog` VALUES ('1206', '0', '1', '提示语:该页面不存在！', '1543668166', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/095.gif');
INSERT INTO `yzn_adminlog` VALUES ('1207', '0', '1', '提示语:该页面不存在！', '1543668166', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/096.gif');
INSERT INTO `yzn_adminlog` VALUES ('1208', '0', '1', '提示语:该页面不存在！', '1543668166', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/097.gif');
INSERT INTO `yzn_adminlog` VALUES ('1209', '0', '1', '提示语:该页面不存在！', '1543668166', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/098.png');
INSERT INTO `yzn_adminlog` VALUES ('1210', '0', '1', '提示语:该页面不存在！', '1543668166', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/099.png');
INSERT INTO `yzn_adminlog` VALUES ('1211', '0', '0', '提示语:请先登陆', '1543669900', '2130706433', '/admin');
INSERT INTO `yzn_adminlog` VALUES ('1212', '1', '1', '提示语:恭喜您，登陆成功', '1543669905', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('1213', '0', '1', '提示语:该页面不存在！', '1543669910', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/04.png');
INSERT INTO `yzn_adminlog` VALUES ('1214', '0', '1', '提示语:该页面不存在！', '1543669910', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/045.png');
INSERT INTO `yzn_adminlog` VALUES ('1215', '0', '1', '提示语:该页面不存在！', '1543669910', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/017.png');
INSERT INTO `yzn_adminlog` VALUES ('1216', '0', '1', '提示语:该页面不存在！', '1543669910', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/041.png');
INSERT INTO `yzn_adminlog` VALUES ('1217', '0', '1', '提示语:该页面不存在！', '1543669910', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/023.gif');
INSERT INTO `yzn_adminlog` VALUES ('1218', '0', '1', '提示语:该页面不存在！', '1543669910', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/026.png');
INSERT INTO `yzn_adminlog` VALUES ('1219', '0', '1', '提示语:该页面不存在！', '1543669910', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/038.gif');
INSERT INTO `yzn_adminlog` VALUES ('1220', '0', '1', '提示语:该页面不存在！', '1543669910', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/033.png');
INSERT INTO `yzn_adminlog` VALUES ('1221', '0', '1', '提示语:该页面不存在！', '1543669911', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/027.gif');
INSERT INTO `yzn_adminlog` VALUES ('1222', '0', '1', '提示语:该页面不存在！', '1543669911', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/032.png');
INSERT INTO `yzn_adminlog` VALUES ('1223', '0', '1', '提示语:该页面不存在！', '1543669911', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/015.png');
INSERT INTO `yzn_adminlog` VALUES ('1224', '0', '1', '提示语:该页面不存在！', '1543669911', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/016.png');
INSERT INTO `yzn_adminlog` VALUES ('1225', '0', '1', '提示语:该页面不存在！', '1543669911', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/09.gif');
INSERT INTO `yzn_adminlog` VALUES ('1226', '0', '1', '提示语:该页面不存在！', '1543669911', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/029.png');
INSERT INTO `yzn_adminlog` VALUES ('1227', '0', '1', '提示语:该页面不存在！', '1543669911', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/06.png');
INSERT INTO `yzn_adminlog` VALUES ('1228', '0', '1', '提示语:该页面不存在！', '1543669911', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/01.png');
INSERT INTO `yzn_adminlog` VALUES ('1229', '0', '1', '提示语:该页面不存在！', '1543669911', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/07.gif');
INSERT INTO `yzn_adminlog` VALUES ('1230', '0', '1', '提示语:该页面不存在！', '1543669911', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/03.gif');
INSERT INTO `yzn_adminlog` VALUES ('1231', '0', '1', '提示语:该页面不存在！', '1543669911', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/02.png');
INSERT INTO `yzn_adminlog` VALUES ('1232', '0', '1', '提示语:该页面不存在！', '1543669911', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/05.png');
INSERT INTO `yzn_adminlog` VALUES ('1233', '0', '1', '提示语:该页面不存在！', '1543669911', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/036.gif');
INSERT INTO `yzn_adminlog` VALUES ('1234', '0', '1', '提示语:该页面不存在！', '1543669911', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/031.png');
INSERT INTO `yzn_adminlog` VALUES ('1235', '0', '1', '提示语:该页面不存在！', '1543669911', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/040.png');
INSERT INTO `yzn_adminlog` VALUES ('1236', '0', '1', '提示语:该页面不存在！', '1543669911', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/021.png');
INSERT INTO `yzn_adminlog` VALUES ('1237', '0', '1', '提示语:该页面不存在！', '1543669911', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/037.png');
INSERT INTO `yzn_adminlog` VALUES ('1238', '0', '1', '提示语:该页面不存在！', '1543669911', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/034.png');
INSERT INTO `yzn_adminlog` VALUES ('1239', '0', '1', '提示语:该页面不存在！', '1543669911', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/019.gif');
INSERT INTO `yzn_adminlog` VALUES ('1240', '0', '1', '提示语:该页面不存在！', '1543669911', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/048.gif');
INSERT INTO `yzn_adminlog` VALUES ('1241', '0', '1', '提示语:该页面不存在！', '1543669911', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/024.png');
INSERT INTO `yzn_adminlog` VALUES ('1242', '0', '1', '提示语:该页面不存在！', '1543669911', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/028.gif');
INSERT INTO `yzn_adminlog` VALUES ('1243', '0', '1', '提示语:该页面不存在！', '1543669912', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/013.png');
INSERT INTO `yzn_adminlog` VALUES ('1244', '0', '1', '提示语:该页面不存在！', '1543669912', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/010.png');
INSERT INTO `yzn_adminlog` VALUES ('1245', '0', '1', '提示语:该页面不存在！', '1543669912', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/042.png');
INSERT INTO `yzn_adminlog` VALUES ('1246', '0', '1', '提示语:该页面不存在！', '1543669912', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/08.gif');
INSERT INTO `yzn_adminlog` VALUES ('1247', '0', '1', '提示语:该页面不存在！', '1543669912', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/022.png');
INSERT INTO `yzn_adminlog` VALUES ('1248', '0', '1', '提示语:该页面不存在！', '1543669912', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/039.png');
INSERT INTO `yzn_adminlog` VALUES ('1249', '0', '1', '提示语:该页面不存在！', '1543669912', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/049.gif');
INSERT INTO `yzn_adminlog` VALUES ('1250', '0', '1', '提示语:该页面不存在！', '1543669912', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/030.png');
INSERT INTO `yzn_adminlog` VALUES ('1251', '0', '1', '提示语:该页面不存在！', '1543669912', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/035.png');
INSERT INTO `yzn_adminlog` VALUES ('1252', '0', '1', '提示语:该页面不存在！', '1543669912', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/012.gif');
INSERT INTO `yzn_adminlog` VALUES ('1253', '0', '1', '提示语:该页面不存在！', '1543669912', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/020.png');
INSERT INTO `yzn_adminlog` VALUES ('1254', '0', '1', '提示语:该页面不存在！', '1543669912', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/047.png');
INSERT INTO `yzn_adminlog` VALUES ('1255', '0', '1', '提示语:该页面不存在！', '1543669912', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/046.gif');
INSERT INTO `yzn_adminlog` VALUES ('1256', '0', '1', '提示语:该页面不存在！', '1543669912', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/018.png');
INSERT INTO `yzn_adminlog` VALUES ('1257', '0', '1', '提示语:该页面不存在！', '1543669912', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/011.jpg');
INSERT INTO `yzn_adminlog` VALUES ('1258', '0', '1', '提示语:该页面不存在！', '1543669912', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/014.jpg');
INSERT INTO `yzn_adminlog` VALUES ('1259', '0', '1', '提示语:该页面不存在！', '1543669912', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/043.png');
INSERT INTO `yzn_adminlog` VALUES ('1260', '0', '1', '提示语:该页面不存在！', '1543669912', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/044.png');
INSERT INTO `yzn_adminlog` VALUES ('1261', '0', '1', '提示语:该页面不存在！', '1543669912', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/025.gif');
INSERT INTO `yzn_adminlog` VALUES ('1262', '0', '1', '提示语:该页面不存在！', '1543669912', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/050.gif');
INSERT INTO `yzn_adminlog` VALUES ('1263', '0', '1', '提示语:该页面不存在！', '1543669912', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/051.gif');
INSERT INTO `yzn_adminlog` VALUES ('1264', '0', '1', '提示语:该页面不存在！', '1543669912', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/052.gif');
INSERT INTO `yzn_adminlog` VALUES ('1265', '0', '1', '提示语:该页面不存在！', '1543669912', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/053.gif');
INSERT INTO `yzn_adminlog` VALUES ('1266', '0', '1', '提示语:该页面不存在！', '1543669912', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/054.gif');
INSERT INTO `yzn_adminlog` VALUES ('1267', '0', '1', '提示语:该页面不存在！', '1543669912', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/055.gif');
INSERT INTO `yzn_adminlog` VALUES ('1268', '0', '1', '提示语:该页面不存在！', '1543669912', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/056.gif');
INSERT INTO `yzn_adminlog` VALUES ('1269', '0', '1', '提示语:该页面不存在！', '1543669912', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/057.gif');
INSERT INTO `yzn_adminlog` VALUES ('1270', '0', '1', '提示语:该页面不存在！', '1543669912', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/058.gif');
INSERT INTO `yzn_adminlog` VALUES ('1271', '0', '1', '提示语:该页面不存在！', '1543669912', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/059.gif');
INSERT INTO `yzn_adminlog` VALUES ('1272', '0', '1', '提示语:该页面不存在！', '1543669912', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/060.gif');
INSERT INTO `yzn_adminlog` VALUES ('1273', '0', '1', '提示语:该页面不存在！', '1543669912', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/061.gif');
INSERT INTO `yzn_adminlog` VALUES ('1274', '0', '1', '提示语:该页面不存在！', '1543669912', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/063.gif');
INSERT INTO `yzn_adminlog` VALUES ('1275', '0', '1', '提示语:该页面不存在！', '1543669913', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/064.gif');
INSERT INTO `yzn_adminlog` VALUES ('1276', '0', '1', '提示语:该页面不存在！', '1543669913', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/065.png');
INSERT INTO `yzn_adminlog` VALUES ('1277', '0', '1', '提示语:该页面不存在！', '1543669913', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/066.png');
INSERT INTO `yzn_adminlog` VALUES ('1278', '0', '1', '提示语:该页面不存在！', '1543669913', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/067.gif');
INSERT INTO `yzn_adminlog` VALUES ('1279', '0', '1', '提示语:该页面不存在！', '1543669913', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/068.png');
INSERT INTO `yzn_adminlog` VALUES ('1280', '0', '1', '提示语:该页面不存在！', '1543669913', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/069.png');
INSERT INTO `yzn_adminlog` VALUES ('1281', '0', '1', '提示语:该页面不存在！', '1543669913', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/070.png');
INSERT INTO `yzn_adminlog` VALUES ('1282', '0', '1', '提示语:该页面不存在！', '1543669913', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/071.gif');
INSERT INTO `yzn_adminlog` VALUES ('1283', '0', '1', '提示语:该页面不存在！', '1543669913', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/072.gif');
INSERT INTO `yzn_adminlog` VALUES ('1284', '0', '1', '提示语:该页面不存在！', '1543669913', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/073.gif');
INSERT INTO `yzn_adminlog` VALUES ('1285', '0', '1', '提示语:该页面不存在！', '1543669913', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/074.png');
INSERT INTO `yzn_adminlog` VALUES ('1286', '0', '1', '提示语:该页面不存在！', '1543669913', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/075.jpg');
INSERT INTO `yzn_adminlog` VALUES ('1287', '0', '1', '提示语:该页面不存在！', '1543669913', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/076.png');
INSERT INTO `yzn_adminlog` VALUES ('1288', '0', '1', '提示语:该页面不存在！', '1543669913', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/077.gif');
INSERT INTO `yzn_adminlog` VALUES ('1289', '0', '1', '提示语:该页面不存在！', '1543669913', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/078.gif');
INSERT INTO `yzn_adminlog` VALUES ('1290', '0', '1', '提示语:该页面不存在！', '1543669913', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/079.gif');
INSERT INTO `yzn_adminlog` VALUES ('1291', '0', '1', '提示语:该页面不存在！', '1543669913', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/080.gif');
INSERT INTO `yzn_adminlog` VALUES ('1292', '0', '1', '提示语:该页面不存在！', '1543669913', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/081.jpg');
INSERT INTO `yzn_adminlog` VALUES ('1293', '0', '1', '提示语:该页面不存在！', '1543669913', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/082.png');
INSERT INTO `yzn_adminlog` VALUES ('1294', '0', '1', '提示语:该页面不存在！', '1543669913', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/083.gif');
INSERT INTO `yzn_adminlog` VALUES ('1295', '0', '1', '提示语:该页面不存在！', '1543669913', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/084.jpg');
INSERT INTO `yzn_adminlog` VALUES ('1296', '0', '1', '提示语:该页面不存在！', '1543669913', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/085.png');
INSERT INTO `yzn_adminlog` VALUES ('1297', '0', '1', '提示语:该页面不存在！', '1543669913', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/086.gif');
INSERT INTO `yzn_adminlog` VALUES ('1298', '0', '1', '提示语:该页面不存在！', '1543669913', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/087.png');
INSERT INTO `yzn_adminlog` VALUES ('1299', '0', '1', '提示语:该页面不存在！', '1543669913', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/088.gif');
INSERT INTO `yzn_adminlog` VALUES ('1300', '0', '1', '提示语:该页面不存在！', '1543669913', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/089.gif');
INSERT INTO `yzn_adminlog` VALUES ('1301', '0', '1', '提示语:该页面不存在！', '1543669913', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/090.png');
INSERT INTO `yzn_adminlog` VALUES ('1302', '0', '1', '提示语:该页面不存在！', '1543669913', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/091.png');
INSERT INTO `yzn_adminlog` VALUES ('1303', '0', '1', '提示语:该页面不存在！', '1543669913', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/092.gif');
INSERT INTO `yzn_adminlog` VALUES ('1304', '0', '1', '提示语:该页面不存在！', '1543669913', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/093.gif');
INSERT INTO `yzn_adminlog` VALUES ('1305', '0', '1', '提示语:该页面不存在！', '1543669913', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/062.gif');
INSERT INTO `yzn_adminlog` VALUES ('1306', '0', '1', '提示语:该页面不存在！', '1543669913', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/094.gif');
INSERT INTO `yzn_adminlog` VALUES ('1307', '0', '1', '提示语:该页面不存在！', '1543669914', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/095.gif');
INSERT INTO `yzn_adminlog` VALUES ('1308', '0', '1', '提示语:该页面不存在！', '1543669914', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/096.gif');
INSERT INTO `yzn_adminlog` VALUES ('1309', '0', '1', '提示语:该页面不存在！', '1543669914', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/097.gif');
INSERT INTO `yzn_adminlog` VALUES ('1310', '0', '1', '提示语:该页面不存在！', '1543669914', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/098.png');
INSERT INTO `yzn_adminlog` VALUES ('1311', '0', '1', '提示语:该页面不存在！', '1543669914', '2130706433', '/addons/addons/__ROOT__addons/returntop/public/images/099.png');
INSERT INTO `yzn_adminlog` VALUES ('1312', '0', '1', '提示语:该页面不存在！', '1543670018', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/04.png');
INSERT INTO `yzn_adminlog` VALUES ('1313', '0', '1', '提示语:该页面不存在！', '1543670018', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/01.png');
INSERT INTO `yzn_adminlog` VALUES ('1314', '0', '1', '提示语:该页面不存在！', '1543670018', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/02.png');
INSERT INTO `yzn_adminlog` VALUES ('1315', '0', '1', '提示语:该页面不存在！', '1543670019', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/05.png');
INSERT INTO `yzn_adminlog` VALUES ('1316', '0', '1', '提示语:该页面不存在！', '1543670019', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/06.png');
INSERT INTO `yzn_adminlog` VALUES ('1317', '0', '1', '提示语:该页面不存在！', '1543670019', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/03.gif');
INSERT INTO `yzn_adminlog` VALUES ('1318', '0', '1', '提示语:该页面不存在！', '1543670019', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/017.png');
INSERT INTO `yzn_adminlog` VALUES ('1319', '0', '1', '提示语:该页面不存在！', '1543670019', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/030.png');
INSERT INTO `yzn_adminlog` VALUES ('1320', '0', '1', '提示语:该页面不存在！', '1543670019', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/041.png');
INSERT INTO `yzn_adminlog` VALUES ('1321', '0', '1', '提示语:该页面不存在！', '1543670019', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/023.gif');
INSERT INTO `yzn_adminlog` VALUES ('1322', '0', '1', '提示语:该页面不存在！', '1543670019', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/026.png');
INSERT INTO `yzn_adminlog` VALUES ('1323', '0', '1', '提示语:该页面不存在！', '1543670019', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/038.gif');
INSERT INTO `yzn_adminlog` VALUES ('1324', '0', '1', '提示语:该页面不存在！', '1543670019', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/033.png');
INSERT INTO `yzn_adminlog` VALUES ('1325', '0', '1', '提示语:该页面不存在！', '1543670019', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/027.gif');
INSERT INTO `yzn_adminlog` VALUES ('1326', '0', '1', '提示语:该页面不存在！', '1543670019', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/032.png');
INSERT INTO `yzn_adminlog` VALUES ('1327', '0', '1', '提示语:该页面不存在！', '1543670019', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/015.png');
INSERT INTO `yzn_adminlog` VALUES ('1328', '0', '1', '提示语:该页面不存在！', '1543670019', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/036.gif');
INSERT INTO `yzn_adminlog` VALUES ('1329', '0', '1', '提示语:该页面不存在！', '1543670019', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/031.png');
INSERT INTO `yzn_adminlog` VALUES ('1330', '0', '1', '提示语:该页面不存在！', '1543670019', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/018.png');
INSERT INTO `yzn_adminlog` VALUES ('1331', '0', '1', '提示语:该页面不存在！', '1543670019', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/019.gif');
INSERT INTO `yzn_adminlog` VALUES ('1332', '0', '1', '提示语:该页面不存在！', '1543670019', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/011.jpg');
INSERT INTO `yzn_adminlog` VALUES ('1333', '0', '1', '提示语:该页面不存在！', '1543670019', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/028.gif');
INSERT INTO `yzn_adminlog` VALUES ('1334', '0', '1', '提示语:该页面不存在！', '1543670019', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/013.png');
INSERT INTO `yzn_adminlog` VALUES ('1335', '0', '1', '提示语:该页面不存在！', '1543670019', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/042.png');
INSERT INTO `yzn_adminlog` VALUES ('1336', '0', '1', '提示语:该页面不存在！', '1543670019', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/025.gif');
INSERT INTO `yzn_adminlog` VALUES ('1337', '0', '1', '提示语:该页面不存在！', '1543670019', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/035.png');
INSERT INTO `yzn_adminlog` VALUES ('1338', '0', '1', '提示语:该页面不存在！', '1543670019', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/012.gif');
INSERT INTO `yzn_adminlog` VALUES ('1339', '0', '1', '提示语:该页面不存在！', '1543670019', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/020.png');
INSERT INTO `yzn_adminlog` VALUES ('1340', '0', '1', '提示语:该页面不存在！', '1543670019', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/016.png');
INSERT INTO `yzn_adminlog` VALUES ('1341', '0', '1', '提示语:该页面不存在！', '1543670019', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/029.png');
INSERT INTO `yzn_adminlog` VALUES ('1342', '0', '1', '提示语:该页面不存在！', '1543670019', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/09.gif');
INSERT INTO `yzn_adminlog` VALUES ('1343', '0', '1', '提示语:该页面不存在！', '1543670019', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/047.png');
INSERT INTO `yzn_adminlog` VALUES ('1344', '0', '1', '提示语:该页面不存在！', '1543670019', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/045.png');
INSERT INTO `yzn_adminlog` VALUES ('1345', '0', '1', '提示语:该页面不存在！', '1543670019', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/07.gif');
INSERT INTO `yzn_adminlog` VALUES ('1346', '0', '1', '提示语:该页面不存在！', '1543670019', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/046.gif');
INSERT INTO `yzn_adminlog` VALUES ('1347', '0', '1', '提示语:该页面不存在！', '1543670019', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/040.png');
INSERT INTO `yzn_adminlog` VALUES ('1348', '0', '1', '提示语:该页面不存在！', '1543670019', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/021.png');
INSERT INTO `yzn_adminlog` VALUES ('1349', '0', '1', '提示语:该页面不存在！', '1543670020', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/037.png');
INSERT INTO `yzn_adminlog` VALUES ('1350', '0', '1', '提示语:该页面不存在！', '1543670020', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/034.png');
INSERT INTO `yzn_adminlog` VALUES ('1351', '0', '1', '提示语:该页面不存在！', '1543670020', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/048.gif');
INSERT INTO `yzn_adminlog` VALUES ('1352', '0', '1', '提示语:该页面不存在！', '1543670020', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/024.png');
INSERT INTO `yzn_adminlog` VALUES ('1353', '0', '1', '提示语:该页面不存在！', '1543670020', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/014.jpg');
INSERT INTO `yzn_adminlog` VALUES ('1354', '0', '1', '提示语:该页面不存在！', '1543670020', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/010.png');
INSERT INTO `yzn_adminlog` VALUES ('1355', '0', '1', '提示语:该页面不存在！', '1543670020', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/08.gif');
INSERT INTO `yzn_adminlog` VALUES ('1356', '0', '1', '提示语:该页面不存在！', '1543670020', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/022.png');
INSERT INTO `yzn_adminlog` VALUES ('1357', '0', '1', '提示语:该页面不存在！', '1543670020', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/043.png');
INSERT INTO `yzn_adminlog` VALUES ('1358', '0', '1', '提示语:该页面不存在！', '1543670020', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/044.png');
INSERT INTO `yzn_adminlog` VALUES ('1359', '0', '1', '提示语:该页面不存在！', '1543670020', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/039.png');
INSERT INTO `yzn_adminlog` VALUES ('1360', '0', '1', '提示语:该页面不存在！', '1543670020', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/049.gif');
INSERT INTO `yzn_adminlog` VALUES ('1361', '0', '1', '提示语:该页面不存在！', '1543670020', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/050.gif');
INSERT INTO `yzn_adminlog` VALUES ('1362', '0', '1', '提示语:该页面不存在！', '1543670020', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/051.gif');
INSERT INTO `yzn_adminlog` VALUES ('1363', '0', '1', '提示语:该页面不存在！', '1543670020', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/052.gif');
INSERT INTO `yzn_adminlog` VALUES ('1364', '0', '1', '提示语:该页面不存在！', '1543670020', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/053.gif');
INSERT INTO `yzn_adminlog` VALUES ('1365', '0', '1', '提示语:该页面不存在！', '1543670020', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/054.gif');
INSERT INTO `yzn_adminlog` VALUES ('1366', '0', '1', '提示语:该页面不存在！', '1543670020', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/055.gif');
INSERT INTO `yzn_adminlog` VALUES ('1367', '0', '1', '提示语:该页面不存在！', '1543670020', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/056.gif');
INSERT INTO `yzn_adminlog` VALUES ('1368', '0', '1', '提示语:该页面不存在！', '1543670020', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/057.gif');
INSERT INTO `yzn_adminlog` VALUES ('1369', '0', '1', '提示语:该页面不存在！', '1543670020', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/058.gif');
INSERT INTO `yzn_adminlog` VALUES ('1370', '0', '1', '提示语:该页面不存在！', '1543670020', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/059.gif');
INSERT INTO `yzn_adminlog` VALUES ('1371', '0', '1', '提示语:该页面不存在！', '1543670020', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/060.gif');
INSERT INTO `yzn_adminlog` VALUES ('1372', '0', '1', '提示语:该页面不存在！', '1543670020', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/061.gif');
INSERT INTO `yzn_adminlog` VALUES ('1373', '0', '1', '提示语:该页面不存在！', '1543670020', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/062.gif');
INSERT INTO `yzn_adminlog` VALUES ('1374', '0', '1', '提示语:该页面不存在！', '1543670020', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/063.gif');
INSERT INTO `yzn_adminlog` VALUES ('1375', '0', '1', '提示语:该页面不存在！', '1543670020', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/064.gif');
INSERT INTO `yzn_adminlog` VALUES ('1376', '0', '1', '提示语:该页面不存在！', '1543670020', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/065.png');
INSERT INTO `yzn_adminlog` VALUES ('1377', '0', '1', '提示语:该页面不存在！', '1543670020', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/066.png');
INSERT INTO `yzn_adminlog` VALUES ('1378', '0', '1', '提示语:该页面不存在！', '1543670020', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/067.gif');
INSERT INTO `yzn_adminlog` VALUES ('1379', '0', '1', '提示语:该页面不存在！', '1543670020', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/068.png');
INSERT INTO `yzn_adminlog` VALUES ('1380', '0', '1', '提示语:该页面不存在！', '1543670021', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/069.png');
INSERT INTO `yzn_adminlog` VALUES ('1381', '0', '1', '提示语:该页面不存在！', '1543670021', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/070.png');
INSERT INTO `yzn_adminlog` VALUES ('1382', '0', '1', '提示语:该页面不存在！', '1543670021', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/071.gif');
INSERT INTO `yzn_adminlog` VALUES ('1383', '0', '1', '提示语:该页面不存在！', '1543670021', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/073.gif');
INSERT INTO `yzn_adminlog` VALUES ('1384', '0', '1', '提示语:该页面不存在！', '1543670021', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/074.png');
INSERT INTO `yzn_adminlog` VALUES ('1385', '0', '1', '提示语:该页面不存在！', '1543670021', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/075.jpg');
INSERT INTO `yzn_adminlog` VALUES ('1386', '0', '1', '提示语:该页面不存在！', '1543670021', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/076.png');
INSERT INTO `yzn_adminlog` VALUES ('1387', '0', '1', '提示语:该页面不存在！', '1543670021', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/077.gif');
INSERT INTO `yzn_adminlog` VALUES ('1388', '0', '1', '提示语:该页面不存在！', '1543670021', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/078.gif');
INSERT INTO `yzn_adminlog` VALUES ('1389', '0', '1', '提示语:该页面不存在！', '1543670021', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/079.gif');
INSERT INTO `yzn_adminlog` VALUES ('1390', '0', '1', '提示语:该页面不存在！', '1543670021', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/080.gif');
INSERT INTO `yzn_adminlog` VALUES ('1391', '0', '1', '提示语:该页面不存在！', '1543670021', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/081.jpg');
INSERT INTO `yzn_adminlog` VALUES ('1392', '0', '1', '提示语:该页面不存在！', '1543670021', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/082.png');
INSERT INTO `yzn_adminlog` VALUES ('1393', '0', '1', '提示语:该页面不存在！', '1543670021', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/083.gif');
INSERT INTO `yzn_adminlog` VALUES ('1394', '0', '1', '提示语:该页面不存在！', '1543670021', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/084.jpg');
INSERT INTO `yzn_adminlog` VALUES ('1395', '0', '1', '提示语:该页面不存在！', '1543670021', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/085.png');
INSERT INTO `yzn_adminlog` VALUES ('1396', '0', '1', '提示语:该页面不存在！', '1543670021', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/086.gif');
INSERT INTO `yzn_adminlog` VALUES ('1397', '0', '1', '提示语:该页面不存在！', '1543670021', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/087.png');
INSERT INTO `yzn_adminlog` VALUES ('1398', '0', '1', '提示语:该页面不存在！', '1543670021', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/088.gif');
INSERT INTO `yzn_adminlog` VALUES ('1399', '0', '1', '提示语:该页面不存在！', '1543670021', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/089.gif');
INSERT INTO `yzn_adminlog` VALUES ('1400', '0', '1', '提示语:该页面不存在！', '1543670021', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/090.png');
INSERT INTO `yzn_adminlog` VALUES ('1401', '0', '1', '提示语:该页面不存在！', '1543670021', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/091.png');
INSERT INTO `yzn_adminlog` VALUES ('1402', '0', '1', '提示语:该页面不存在！', '1543670021', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/092.gif');
INSERT INTO `yzn_adminlog` VALUES ('1403', '0', '1', '提示语:该页面不存在！', '1543670021', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/093.gif');
INSERT INTO `yzn_adminlog` VALUES ('1404', '0', '1', '提示语:该页面不存在！', '1543670021', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/094.gif');
INSERT INTO `yzn_adminlog` VALUES ('1405', '0', '1', '提示语:该页面不存在！', '1543670021', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/095.gif');
INSERT INTO `yzn_adminlog` VALUES ('1406', '0', '1', '提示语:该页面不存在！', '1543670021', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/096.gif');
INSERT INTO `yzn_adminlog` VALUES ('1407', '0', '1', '提示语:该页面不存在！', '1543670021', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/097.gif');
INSERT INTO `yzn_adminlog` VALUES ('1408', '0', '1', '提示语:该页面不存在！', '1543670021', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/098.png');
INSERT INTO `yzn_adminlog` VALUES ('1409', '0', '1', '提示语:该页面不存在！', '1543670021', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/099.png');
INSERT INTO `yzn_adminlog` VALUES ('1410', '0', '1', '提示语:该页面不存在！', '1543670021', '2130706433', '/addons/addons/ADDON_PATH/addons/returntop/public/images/072.gif');
INSERT INTO `yzn_adminlog` VALUES ('1411', '1', '1', '提示语:插件卸载成功！', '1543672440', '2130706433', '/addons/addons/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('1412', '1', '1', '提示语:插件安装成功！', '1543673512', '2130706433', '/addons/addons/install.html');
INSERT INTO `yzn_adminlog` VALUES ('1413', '1', '1', '提示语:插件卸载成功！', '1543673545', '2130706433', '/addons/addons/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('1414', '1', '1', '提示语:插件安装成功！', '1543673550', '2130706433', '/addons/addons/install.html');
INSERT INTO `yzn_adminlog` VALUES ('1415', '1', '1', '提示语:插件卸载成功！', '1543673651', '2130706433', '/addons/addons/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('1416', '1', '1', '提示语:插件卸载成功！', '1543673728', '2130706433', '/addons/addons/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('1417', '1', '1', '提示语:插件卸载成功！', '1543673802', '2130706433', '/addons/addons/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('1418', '1', '1', '提示语:插件卸载成功！', '1543673953', '2130706433', '/addons/addons/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('1419', '1', '1', '提示语:插件卸载成功！', '1543673995', '2130706433', '/addons/addons/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('1420', '0', '1', '提示语:该插件已经安装，无需重复安装！', '1543674155', '2130706433', '/addons/addons/install.html');
INSERT INTO `yzn_adminlog` VALUES ('1421', '1', '1', '提示语:插件卸载成功！', '1543674161', '2130706433', '/addons/addons/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('1422', '1', '1', '提示语:插件卸载成功！', '1543674427', '2130706433', '/addons/addons/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('1423', '1', '1', '提示语:插件卸载成功！', '1543674461', '2130706433', '/addons/addons/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('1424', '1', '1', '提示语:插件卸载成功！', '1543674537', '2130706433', '/addons/addons/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('1425', '1', '1', '提示语:插件卸载成功！', '1543674633', '2130706433', '/addons/addons/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('1426', '1', '1', '提示语:插件卸载成功！', '1543674705', '2130706433', '/addons/addons/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('1427', '1', '1', '提示语:插件卸载成功！', '1543674828', '2130706433', '/addons/addons/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('1428', '1', '1', '提示语:插件卸载成功！', '1543674889', '2130706433', '/addons/addons/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('1429', '1', '1', '提示语:插件安装成功！', '1543674893', '2130706433', '/addons/addons/install.html');
INSERT INTO `yzn_adminlog` VALUES ('1430', '1', '1', '提示语:插件卸载成功！', '1543674954', '2130706433', '/addons/addons/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('1431', '1', '1', '提示语:插件安装成功！', '1543674959', '2130706433', '/addons/addons/install.html');
INSERT INTO `yzn_adminlog` VALUES ('1432', '1', '1', '提示语:插件卸载成功！', '1543674999', '2130706433', '/addons/addons/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('1433', '1', '1', '提示语:插件卸载成功！', '1543675064', '2130706433', '/addons/addons/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('1434', '1', '1', '提示语:插件卸载成功！', '1543675078', '2130706433', '/addons/addons/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('1435', '1', '1', '提示语:插件安装成功！', '1543675203', '2130706433', '/addons/addons/install.html');
INSERT INTO `yzn_adminlog` VALUES ('1436', '1', '1', '提示语:插件卸载成功！', '1543675740', '2130706433', '/addons/addons/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('1437', '1', '1', '提示语:插件安装成功！', '1543675771', '2130706433', '/addons/addons/install.html');
INSERT INTO `yzn_adminlog` VALUES ('1438', '1', '1', '提示语:插件卸载成功！', '1543675794', '2130706433', '/addons/addons/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('1439', '1', '1', '提示语:插件安装成功！', '1543675999', '2130706433', '/addons/addons/install.html');
INSERT INTO `yzn_adminlog` VALUES ('1440', '1', '1', '提示语:插件卸载成功！', '1543676073', '2130706433', '/addons/addons/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('1441', '1', '1', '提示语:插件安装成功！', '1543676669', '2130706433', '/addons/addons/install.html');
INSERT INTO `yzn_adminlog` VALUES ('1442', '1', '1', '提示语:插件卸载成功！', '1543676678', '2130706433', '/addons/addons/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('1443', '1', '1', '提示语:插件安装成功！', '1543676692', '2130706433', '/addons/addons/install.html');
INSERT INTO `yzn_adminlog` VALUES ('1444', '1', '1', '提示语:插件卸载成功！', '1543676697', '2130706433', '/addons/addons/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('1445', '1', '1', '提示语:清理缓存', '1543676874', '2130706433', '/admin/index/cache.html?type=all&_=1543674491667');
INSERT INTO `yzn_adminlog` VALUES ('1446', '1', '1', '提示语:插件安装成功！', '1543676923', '2130706433', '/addons/addons/install.html');
INSERT INTO `yzn_adminlog` VALUES ('1447', '0', '1', '提示语:该页面不存在！', '1543676926', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/05.png');
INSERT INTO `yzn_adminlog` VALUES ('1448', '0', '1', '提示语:该页面不存在！', '1543676926', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/015.png');
INSERT INTO `yzn_adminlog` VALUES ('1449', '0', '1', '提示语:该页面不存在！', '1543676926', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/024.png');
INSERT INTO `yzn_adminlog` VALUES ('1450', '0', '1', '提示语:该页面不存在！', '1543676926', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/030.png');
INSERT INTO `yzn_adminlog` VALUES ('1451', '0', '1', '提示语:该页面不存在！', '1543676926', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/017.png');
INSERT INTO `yzn_adminlog` VALUES ('1452', '0', '1', '提示语:该页面不存在！', '1543676926', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/023.gif');
INSERT INTO `yzn_adminlog` VALUES ('1453', '0', '1', '提示语:该页面不存在！', '1543676926', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/026.png');
INSERT INTO `yzn_adminlog` VALUES ('1454', '0', '1', '提示语:该页面不存在！', '1543676926', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/012.gif');
INSERT INTO `yzn_adminlog` VALUES ('1455', '0', '1', '提示语:该页面不存在！', '1543676926', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/033.png');
INSERT INTO `yzn_adminlog` VALUES ('1456', '0', '1', '提示语:该页面不存在！', '1543676927', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/027.gif');
INSERT INTO `yzn_adminlog` VALUES ('1457', '0', '1', '提示语:该页面不存在！', '1543676927', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/032.png');
INSERT INTO `yzn_adminlog` VALUES ('1458', '0', '1', '提示语:该页面不存在！', '1543676927', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/09.gif');
INSERT INTO `yzn_adminlog` VALUES ('1459', '0', '1', '提示语:该页面不存在！', '1543676927', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/020.png');
INSERT INTO `yzn_adminlog` VALUES ('1460', '0', '1', '提示语:该页面不存在！', '1543676927', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/016.png');
INSERT INTO `yzn_adminlog` VALUES ('1461', '0', '1', '提示语:该页面不存在！', '1543676927', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/06.png');
INSERT INTO `yzn_adminlog` VALUES ('1462', '0', '1', '提示语:该页面不存在！', '1543676927', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/04.png');
INSERT INTO `yzn_adminlog` VALUES ('1463', '0', '1', '提示语:该页面不存在！', '1543676927', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/01.png');
INSERT INTO `yzn_adminlog` VALUES ('1464', '0', '1', '提示语:该页面不存在！', '1543676927', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/07.gif');
INSERT INTO `yzn_adminlog` VALUES ('1465', '0', '1', '提示语:该页面不存在！', '1543676927', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/03.gif');
INSERT INTO `yzn_adminlog` VALUES ('1466', '0', '1', '提示语:该页面不存在！', '1543676927', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/02.png');
INSERT INTO `yzn_adminlog` VALUES ('1467', '0', '1', '提示语:该页面不存在！', '1543676927', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/031.png');
INSERT INTO `yzn_adminlog` VALUES ('1468', '0', '1', '提示语:该页面不存在！', '1543676927', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/021.png');
INSERT INTO `yzn_adminlog` VALUES ('1469', '0', '1', '提示语:该页面不存在！', '1543676927', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/018.png');
INSERT INTO `yzn_adminlog` VALUES ('1470', '0', '1', '提示语:该页面不存在！', '1543676927', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/034.png');
INSERT INTO `yzn_adminlog` VALUES ('1471', '0', '1', '提示语:该页面不存在！', '1543676927', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/019.gif');
INSERT INTO `yzn_adminlog` VALUES ('1472', '0', '1', '提示语:该页面不存在！', '1543676927', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/011.jpg');
INSERT INTO `yzn_adminlog` VALUES ('1473', '0', '1', '提示语:该页面不存在！', '1543676927', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/028.gif');
INSERT INTO `yzn_adminlog` VALUES ('1474', '0', '1', '提示语:该页面不存在！', '1543676927', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/014.jpg');
INSERT INTO `yzn_adminlog` VALUES ('1475', '0', '1', '提示语:该页面不存在！', '1543676927', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/013.png');
INSERT INTO `yzn_adminlog` VALUES ('1476', '0', '1', '提示语:该页面不存在！', '1543676927', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/010.png');
INSERT INTO `yzn_adminlog` VALUES ('1477', '0', '1', '提示语:该页面不存在！', '1543676927', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/08.gif');
INSERT INTO `yzn_adminlog` VALUES ('1478', '0', '1', '提示语:该页面不存在！', '1543676927', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/022.png');
INSERT INTO `yzn_adminlog` VALUES ('1479', '0', '1', '提示语:该页面不存在！', '1543676927', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/025.gif');
INSERT INTO `yzn_adminlog` VALUES ('1480', '0', '1', '提示语:该页面不存在！', '1543676928', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/035.png');
INSERT INTO `yzn_adminlog` VALUES ('1481', '0', '1', '提示语:该页面不存在！', '1543676928', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/041.png');
INSERT INTO `yzn_adminlog` VALUES ('1482', '0', '1', '提示语:该页面不存在！', '1543676928', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/038.gif');
INSERT INTO `yzn_adminlog` VALUES ('1483', '0', '1', '提示语:该页面不存在！', '1543676928', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/047.png');
INSERT INTO `yzn_adminlog` VALUES ('1484', '0', '1', '提示语:该页面不存在！', '1543676928', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/045.png');
INSERT INTO `yzn_adminlog` VALUES ('1485', '0', '1', '提示语:该页面不存在！', '1543676928', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/036.gif');
INSERT INTO `yzn_adminlog` VALUES ('1486', '0', '1', '提示语:该页面不存在！', '1543676928', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/046.gif');
INSERT INTO `yzn_adminlog` VALUES ('1487', '0', '1', '提示语:该页面不存在！', '1543676928', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/040.png');
INSERT INTO `yzn_adminlog` VALUES ('1488', '0', '1', '提示语:该页面不存在！', '1543676928', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/037.png');
INSERT INTO `yzn_adminlog` VALUES ('1489', '0', '1', '提示语:该页面不存在！', '1543676928', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/048.gif');
INSERT INTO `yzn_adminlog` VALUES ('1490', '0', '1', '提示语:该页面不存在！', '1543676928', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/042.png');
INSERT INTO `yzn_adminlog` VALUES ('1491', '0', '1', '提示语:该页面不存在！', '1543676928', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/043.png');
INSERT INTO `yzn_adminlog` VALUES ('1492', '0', '1', '提示语:该页面不存在！', '1543676928', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/044.png');
INSERT INTO `yzn_adminlog` VALUES ('1493', '0', '1', '提示语:该页面不存在！', '1543676928', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/039.png');
INSERT INTO `yzn_adminlog` VALUES ('1494', '0', '1', '提示语:该页面不存在！', '1543676928', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/049.gif');
INSERT INTO `yzn_adminlog` VALUES ('1495', '0', '1', '提示语:该页面不存在！', '1543676928', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/050.gif');
INSERT INTO `yzn_adminlog` VALUES ('1496', '0', '1', '提示语:该页面不存在！', '1543676928', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/051.gif');
INSERT INTO `yzn_adminlog` VALUES ('1497', '0', '1', '提示语:该页面不存在！', '1543676928', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/029.png');
INSERT INTO `yzn_adminlog` VALUES ('1498', '0', '1', '提示语:该页面不存在！', '1543676928', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/052.gif');
INSERT INTO `yzn_adminlog` VALUES ('1499', '0', '1', '提示语:该页面不存在！', '1543676928', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/054.gif');
INSERT INTO `yzn_adminlog` VALUES ('1500', '0', '1', '提示语:该页面不存在！', '1543676928', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/055.gif');
INSERT INTO `yzn_adminlog` VALUES ('1501', '0', '1', '提示语:该页面不存在！', '1543676928', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/056.gif');
INSERT INTO `yzn_adminlog` VALUES ('1502', '0', '1', '提示语:该页面不存在！', '1543676928', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/057.gif');
INSERT INTO `yzn_adminlog` VALUES ('1503', '0', '1', '提示语:该页面不存在！', '1543676928', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/058.gif');
INSERT INTO `yzn_adminlog` VALUES ('1504', '0', '1', '提示语:该页面不存在！', '1543676928', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/059.gif');
INSERT INTO `yzn_adminlog` VALUES ('1505', '0', '1', '提示语:该页面不存在！', '1543676928', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/060.gif');
INSERT INTO `yzn_adminlog` VALUES ('1506', '0', '1', '提示语:该页面不存在！', '1543676928', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/061.gif');
INSERT INTO `yzn_adminlog` VALUES ('1507', '0', '1', '提示语:该页面不存在！', '1543676928', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/062.gif');
INSERT INTO `yzn_adminlog` VALUES ('1508', '0', '1', '提示语:该页面不存在！', '1543676928', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/063.gif');
INSERT INTO `yzn_adminlog` VALUES ('1509', '0', '1', '提示语:该页面不存在！', '1543676928', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/064.gif');
INSERT INTO `yzn_adminlog` VALUES ('1510', '0', '1', '提示语:该页面不存在！', '1543676928', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/065.png');
INSERT INTO `yzn_adminlog` VALUES ('1511', '0', '1', '提示语:该页面不存在！', '1543676928', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/066.png');
INSERT INTO `yzn_adminlog` VALUES ('1512', '0', '1', '提示语:该页面不存在！', '1543676928', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/067.gif');
INSERT INTO `yzn_adminlog` VALUES ('1513', '0', '1', '提示语:该页面不存在！', '1543676928', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/068.png');
INSERT INTO `yzn_adminlog` VALUES ('1514', '0', '1', '提示语:该页面不存在！', '1543676928', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/069.png');
INSERT INTO `yzn_adminlog` VALUES ('1515', '0', '1', '提示语:该页面不存在！', '1543676929', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/070.png');
INSERT INTO `yzn_adminlog` VALUES ('1516', '0', '1', '提示语:该页面不存在！', '1543676929', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/071.gif');
INSERT INTO `yzn_adminlog` VALUES ('1517', '0', '1', '提示语:该页面不存在！', '1543676929', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/072.gif');
INSERT INTO `yzn_adminlog` VALUES ('1518', '0', '1', '提示语:该页面不存在！', '1543676929', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/073.gif');
INSERT INTO `yzn_adminlog` VALUES ('1519', '0', '1', '提示语:该页面不存在！', '1543676929', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/074.png');
INSERT INTO `yzn_adminlog` VALUES ('1520', '0', '1', '提示语:该页面不存在！', '1543676929', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/075.jpg');
INSERT INTO `yzn_adminlog` VALUES ('1521', '0', '1', '提示语:该页面不存在！', '1543676929', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/076.png');
INSERT INTO `yzn_adminlog` VALUES ('1522', '0', '1', '提示语:该页面不存在！', '1543676929', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/077.gif');
INSERT INTO `yzn_adminlog` VALUES ('1523', '0', '1', '提示语:该页面不存在！', '1543676929', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/078.gif');
INSERT INTO `yzn_adminlog` VALUES ('1524', '0', '1', '提示语:该页面不存在！', '1543676929', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/079.gif');
INSERT INTO `yzn_adminlog` VALUES ('1525', '0', '1', '提示语:该页面不存在！', '1543676929', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/080.gif');
INSERT INTO `yzn_adminlog` VALUES ('1526', '0', '1', '提示语:该页面不存在！', '1543676929', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/081.jpg');
INSERT INTO `yzn_adminlog` VALUES ('1527', '0', '1', '提示语:该页面不存在！', '1543676929', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/082.png');
INSERT INTO `yzn_adminlog` VALUES ('1528', '0', '1', '提示语:该页面不存在！', '1543676929', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/083.gif');
INSERT INTO `yzn_adminlog` VALUES ('1529', '0', '1', '提示语:该页面不存在！', '1543676929', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/084.jpg');
INSERT INTO `yzn_adminlog` VALUES ('1530', '0', '1', '提示语:该页面不存在！', '1543676929', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/085.png');
INSERT INTO `yzn_adminlog` VALUES ('1531', '0', '1', '提示语:该页面不存在！', '1543676929', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/086.gif');
INSERT INTO `yzn_adminlog` VALUES ('1532', '0', '1', '提示语:该页面不存在！', '1543676929', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/053.gif');
INSERT INTO `yzn_adminlog` VALUES ('1533', '0', '1', '提示语:该页面不存在！', '1543676929', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/087.png');
INSERT INTO `yzn_adminlog` VALUES ('1534', '0', '1', '提示语:该页面不存在！', '1543676929', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/088.gif');
INSERT INTO `yzn_adminlog` VALUES ('1535', '0', '1', '提示语:该页面不存在！', '1543676929', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/089.gif');
INSERT INTO `yzn_adminlog` VALUES ('1536', '0', '1', '提示语:该页面不存在！', '1543676929', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/090.png');
INSERT INTO `yzn_adminlog` VALUES ('1537', '0', '1', '提示语:该页面不存在！', '1543676929', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/091.png');
INSERT INTO `yzn_adminlog` VALUES ('1538', '0', '1', '提示语:该页面不存在！', '1543676929', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/092.gif');
INSERT INTO `yzn_adminlog` VALUES ('1539', '0', '1', '提示语:该页面不存在！', '1543676929', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/093.gif');
INSERT INTO `yzn_adminlog` VALUES ('1540', '0', '1', '提示语:该页面不存在！', '1543676929', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/094.gif');
INSERT INTO `yzn_adminlog` VALUES ('1541', '0', '1', '提示语:该页面不存在！', '1543676929', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/095.gif');
INSERT INTO `yzn_adminlog` VALUES ('1542', '0', '1', '提示语:该页面不存在！', '1543676929', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/096.gif');
INSERT INTO `yzn_adminlog` VALUES ('1543', '0', '1', '提示语:该页面不存在！', '1543676929', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/097.gif');
INSERT INTO `yzn_adminlog` VALUES ('1544', '0', '1', '提示语:该页面不存在！', '1543676929', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/098.png');
INSERT INTO `yzn_adminlog` VALUES ('1545', '0', '1', '提示语:该页面不存在！', '1543676929', '2130706433', '/addons/addons/__ADDON__/returntop/public/images/099.png');
INSERT INTO `yzn_adminlog` VALUES ('1546', '1', '1', '提示语:插件卸载成功！', '1543677082', '2130706433', '/addons/addons/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('1547', '1', '1', '提示语:插件安装成功！', '1543677124', '2130706433', '/addons/addons/install.html');
INSERT INTO `yzn_adminlog` VALUES ('1548', '0', '0', '提示语:请先登陆', '1543722614', '2130706433', '/admin');
INSERT INTO `yzn_adminlog` VALUES ('1549', '1', '1', '提示语:恭喜您，登陆成功', '1543722619', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('1550', '0', '1', '提示语:请选择需要卸载的插件！', '1543724230', '2130706433', '/addons/addons/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('1551', '0', '1', '提示语:该插件不存在！', '1543724346', '2130706433', '/addons/addons/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('1552', '1', '1', '提示语:插件安装成功！', '1543724416', '2130706433', '/addons/addons/install.html');
INSERT INTO `yzn_adminlog` VALUES ('1553', '0', '1', '提示语:该页面不存在！', '1543726679', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/css/01.css');
INSERT INTO `yzn_adminlog` VALUES ('1554', '0', '1', '提示语:该页面不存在！', '1543726680', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/scrolltopcontrol.js');
INSERT INTO `yzn_adminlog` VALUES ('1555', '0', '1', '提示语:该页面不存在！', '1543726680', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/css/01.css');
INSERT INTO `yzn_adminlog` VALUES ('1556', '0', '1', '提示语:该页面不存在！', '1543726681', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/scrolltopcontrol.js');
INSERT INTO `yzn_adminlog` VALUES ('1557', '0', '1', '提示语:该页面不存在！', '1543726700', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/css/01.css');
INSERT INTO `yzn_adminlog` VALUES ('1558', '0', '1', '提示语:该页面不存在！', '1543726701', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/scrolltopcontrol.js');
INSERT INTO `yzn_adminlog` VALUES ('1559', '0', '1', '提示语:该页面不存在！', '1543726704', '2130706433', '/addons/addons/__ROOT__/addons/returntop/public/css/01.css');

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
) ENGINE=MyISAM AUTO_INCREMENT=237 DEFAULT CHARSET=utf8 COMMENT='附件表';

-- ----------------------------
-- Records of yzn_attachment
-- ----------------------------
INSERT INTO `yzn_attachment` VALUES ('236', '1', 'logo.jpg', 'admin', 'images/20181201/0dddfa2f74563b4ff4610fd20db718e2.jpg', '', '', 'image/jpeg', 'jpg', '54747', 'ee0a66601f627ef32b181c7ea909c0f3', '03217498aa7276eb1eb2b58ce545ec73afd47570', 'local', '1543677412', '1543677412', '100', '1');

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
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yzn_hooks
-- ----------------------------
INSERT INTO `yzn_hooks` VALUES ('5', 'pageHeader', '页面header钩子，一般用于加载插件CSS文件和代码', '1', '1509174020', '');
INSERT INTO `yzn_hooks` VALUES ('6', 'pageFooter', '页面footer钩子，一般用于加载插件JS文件和JS代码', '1', '1509174020', 'returntop');

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
