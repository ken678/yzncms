/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : yzncms

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2018-12-21 17:33:54
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
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='插件表';

-- ----------------------------
-- Records of yzn_addons
-- ----------------------------
INSERT INTO `yzn_addons` VALUES ('3', 'database', '数据库备份', '简单的数据库备份', '1', '{\"path\":\"\\/Data\\/\",\"part\":\"20971520\",\"compress\":\"1\",\"level\":\"9\"}', '御宅男', '1.0.0', '1545289617', '1');

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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='管理员表';

-- ----------------------------
-- Records of yzn_admin
-- ----------------------------
INSERT INTO `yzn_admin` VALUES ('1', 'admin', '9724b5e6c56b95f5723009ef81961bfe', '1', 'Wo0bAa', '御宅男', '1545379574', '2130706433', '530765310@qq.com', '1');
INSERT INTO `yzn_admin` VALUES ('2', 'ken678', '932e31f030b850a87702a86c0e16db16', '2', 'Sxq6dR', '御宅男', '1542781151', '2130706433', '530765310@qq.com', '1');

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
) ENGINE=MyISAM AUTO_INCREMENT=169 DEFAULT CHARSET=utf8 COMMENT='操作日志';

-- ----------------------------
-- Records of yzn_adminlog
-- ----------------------------
INSERT INTO `yzn_adminlog` VALUES ('1', '0', '1', '提示语:该插件没有安装！', '1544437101', '2130706433', '/addons/database/export/isadmin/1.html');
INSERT INTO `yzn_adminlog` VALUES ('2', '1', '1', '提示语:插件安装成功！', '1544437112', '2130706433', '/addons/addons/install.html');
INSERT INTO `yzn_adminlog` VALUES ('3', '1', '1', '提示语:初始化成功！', '1544437123', '2130706433', '/addons/database/export/isadmin/1.html');
INSERT INTO `yzn_adminlog` VALUES ('4', '1', '1', '提示语:备份完成！', '1544437123', '2130706433', '/addons/database/export/isadmin/1.html?id=0&start=0');
INSERT INTO `yzn_adminlog` VALUES ('5', '1', '1', '提示语:备份完成！', '1544437123', '2130706433', '/addons/database/export/isadmin/1.html?id=1&start=0');
INSERT INTO `yzn_adminlog` VALUES ('6', '1', '1', '提示语:还原完成！', '1544440068', '2130706433', '/addons/database/import/isadmin/1.html?time=1544437123&part=1&start=0');
INSERT INTO `yzn_adminlog` VALUES ('7', '0', '0', '提示语:请先登陆', '1544687308', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('8', '1', '1', '提示语:恭喜您，登陆成功', '1544687314', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('9', '0', '0', '提示语:请先登陆', '1544692873', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('10', '1', '1', '提示语:恭喜您，登陆成功', '1544692878', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('11', '0', '0', '提示语:请先登陆', '1544758724', '2130706433', '/admin');
INSERT INTO `yzn_adminlog` VALUES ('12', '1', '1', '提示语:恭喜您，登陆成功', '1544758960', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('13', '1', '1', '提示语:修改成功！', '1544759111', '2130706433', '/admin/manager/edit.html');
INSERT INTO `yzn_adminlog` VALUES ('14', '0', '0', '提示语:请先登陆', '1544778093', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('15', '1', '1', '提示语:恭喜您，登陆成功', '1544778098', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('16', '0', '0', '提示语:请先登陆', '1544782855', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('17', '1', '1', '提示语:恭喜您，登陆成功', '1544782860', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('18', '1', '1', '提示语:文件删除成功~', '1544782974', '2130706433', '/attachment/attachments/delete.html');
INSERT INTO `yzn_adminlog` VALUES ('19', '0', '0', '提示语:请先登陆', '1545041697', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('20', '1', '1', '提示语:恭喜您，登陆成功', '1545041702', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('21', '0', '1', '提示语:禁止对超级管理员执行该操作！', '1545041714', '2130706433', '/admin/manager/del.html');
INSERT INTO `yzn_adminlog` VALUES ('22', '0', '1', '提示语:超级管理员角色不能被删除!', '1545041718', '2130706433', '/admin/auth_manager/deletegroup.html');
INSERT INTO `yzn_adminlog` VALUES ('23', '1', '1', '提示语:编辑成功！', '1545042700', '2130706433', '/admin/menu/edit.html');
INSERT INTO `yzn_adminlog` VALUES ('24', '0', '0', '提示语:请先登陆', '1545043026', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('25', '1', '1', '提示语:恭喜您，登陆成功', '1545043031', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('26', '1', '1', '提示语:插件卸载成功！', '1545043447', '2130706433', '/addons/addons/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('27', '1', '1', '提示语:插件安装成功！', '1545043783', '2130706433', '/addons/addons/install.html');
INSERT INTO `yzn_adminlog` VALUES ('28', '1', '1', '提示语:初始化成功！', '1545043805', '2130706433', '/addons/database/export/isadmin/1.html');
INSERT INTO `yzn_adminlog` VALUES ('29', '1', '1', '提示语:备份完成！', '1545043806', '2130706433', '/addons/database/export/isadmin/1.html?id=0&start=0');
INSERT INTO `yzn_adminlog` VALUES ('30', '1', '1', '提示语:备份完成！', '1545043806', '2130706433', '/addons/database/export/isadmin/1.html?id=1&start=0');
INSERT INTO `yzn_adminlog` VALUES ('31', '1', '1', '提示语:备份完成！', '1545043806', '2130706433', '/addons/database/export/isadmin/1.html?id=2&start=0');
INSERT INTO `yzn_adminlog` VALUES ('32', '1', '1', '提示语:备份完成！', '1545043806', '2130706433', '/addons/database/export/isadmin/1.html?id=3&start=0');
INSERT INTO `yzn_adminlog` VALUES ('33', '1', '1', '提示语:备份完成！', '1545043806', '2130706433', '/addons/database/export/isadmin/1.html?id=4&start=0');
INSERT INTO `yzn_adminlog` VALUES ('34', '1', '1', '提示语:备份完成！', '1545043806', '2130706433', '/addons/database/export/isadmin/1.html?id=5&start=0');
INSERT INTO `yzn_adminlog` VALUES ('35', '1', '1', '提示语:备份完成！', '1545043806', '2130706433', '/addons/database/export/isadmin/1.html?id=6&start=0');
INSERT INTO `yzn_adminlog` VALUES ('36', '1', '1', '提示语:备份完成！', '1545043806', '2130706433', '/addons/database/export/isadmin/1.html?id=7&start=0');
INSERT INTO `yzn_adminlog` VALUES ('37', '1', '1', '提示语:备份完成！', '1545043806', '2130706433', '/addons/database/export/isadmin/1.html?id=8&start=0');
INSERT INTO `yzn_adminlog` VALUES ('38', '1', '1', '提示语:备份完成！', '1545043807', '2130706433', '/addons/database/export/isadmin/1.html?id=9&start=0');
INSERT INTO `yzn_adminlog` VALUES ('39', '1', '1', '提示语:备份完成！', '1545043807', '2130706433', '/addons/database/export/isadmin/1.html?id=10&start=0');
INSERT INTO `yzn_adminlog` VALUES ('40', '1', '1', '提示语:备份完成！', '1545043807', '2130706433', '/addons/database/export/isadmin/1.html?id=11&start=0');
INSERT INTO `yzn_adminlog` VALUES ('41', '0', '0', '提示语:请先登陆', '1545091677', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('42', '1', '1', '提示语:恭喜您，登陆成功', '1545091683', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('43', '0', '0', '提示语:请先登陆', '1545092719', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('44', '1', '1', '提示语:恭喜您，登陆成功', '1545092725', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('45', '0', '0', '提示语:请先登陆', '1545093573', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('46', '1', '1', '提示语:恭喜您，登陆成功', '1545093578', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('47', '0', '0', '提示语:请先登陆', '1545109110', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('48', '1', '1', '提示语:恭喜您，登陆成功', '1545109117', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('49', '0', '0', '提示语:请先登陆', '1545117618', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('50', '1', '1', '提示语:恭喜您，登陆成功', '1545117624', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('51', '0', '0', '提示语:请先登陆', '1545187714', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('52', '1', '1', '提示语:恭喜您，登陆成功', '1545187720', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('53', '0', '0', '提示语:请先登陆', '1545202837', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('54', '1', '1', '提示语:恭喜您，登陆成功', '1545202844', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('55', '1', '0', '提示语:注销成功！', '1545202939', '2130706433', '/admin/index/logout.html');
INSERT INTO `yzn_adminlog` VALUES ('56', '1', '0', '提示语:注销成功！', '1545202940', '2130706433', '/admin/index/logout.html');
INSERT INTO `yzn_adminlog` VALUES ('57', '1', '0', '提示语:注销成功！', '1545202941', '2130706433', '/admin/index/logout.html');
INSERT INTO `yzn_adminlog` VALUES ('58', '1', '0', '提示语:注销成功！', '1545202942', '2130706433', '/admin/index/logout.html');
INSERT INTO `yzn_adminlog` VALUES ('59', '1', '0', '提示语:注销成功！', '1545202942', '2130706433', '/admin/index/logout.html');
INSERT INTO `yzn_adminlog` VALUES ('60', '1', '1', '提示语:恭喜您，登陆成功', '1545203031', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('61', '1', '1', '提示语:清理缓存', '1545205194', '2130706433', '/admin/index/cache.html?type=all&_=1545203033180');
INSERT INTO `yzn_adminlog` VALUES ('62', '0', '1', '提示语:请选择需要安装的模块！', '1545205202', '2130706433', '/admin/module/install.html');
INSERT INTO `yzn_adminlog` VALUES ('63', '1', '1', '提示语:删除日志成功！', '1545205727', '2130706433', '/admin/adminlog/deletelog.html');
INSERT INTO `yzn_adminlog` VALUES ('64', '1', '1', '提示语:清理缓存', '1545211053', '2130706433', '/admin/index/cache.html?type=all&_=1545203033181');
INSERT INTO `yzn_adminlog` VALUES ('65', '1', '1', '提示语:清理缓存', '1545212149', '2130706433', '/admin/index/cache.html?type=all&_=1545203033182');
INSERT INTO `yzn_adminlog` VALUES ('66', '1', '1', '提示语:清理缓存', '1545212787', '2130706433', '/admin/index/cache.html?type=all&_=1545203033183');
INSERT INTO `yzn_adminlog` VALUES ('67', '1', '1', '提示语:模块安装成功！', '1545212911', '2130706433', '/admin/module/install.html');
INSERT INTO `yzn_adminlog` VALUES ('68', '0', '0', '提示语:请先登陆', '1545213186', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('69', '1', '1', '提示语:恭喜您，登陆成功', '1545213192', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('70', '1', '1', '提示语:模块卸载成功，请及时更新缓存！', '1545213197', '2130706433', '/admin/module/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('71', '1', '1', '提示语:清理缓存', '1545213516', '2130706433', '/admin/index/cache.html?type=all&_=1545213193978');
INSERT INTO `yzn_adminlog` VALUES ('72', '1', '1', '提示语:模块卸载成功，请及时更新缓存！', '1545213636', '2130706433', '/admin/module/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('73', '1', '1', '提示语:模块卸载成功，请及时更新缓存！', '1545213713', '2130706433', '/admin/module/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('74', '1', '1', '提示语:插件卸载成功！', '1545213830', '2130706433', '/addons/addons/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('75', '1', '1', '提示语:模块卸载成功，请及时更新缓存！', '1545214185', '2130706433', '/admin/module/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('76', '1', '1', '提示语:模块卸载成功，请及时更新缓存！', '1545214435', '2130706433', '/admin/module/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('77', '1', '1', '提示语:模块卸载成功，请及时更新缓存！', '1545214571', '2130706433', '/admin/module/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('78', '1', '1', '提示语:模块卸载成功，请及时更新缓存！', '1545214639', '2130706433', '/admin/module/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('79', '1', '1', '提示语:模块卸载成功，请及时更新缓存！', '1545214744', '2130706433', '/admin/module/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('80', '1', '1', '提示语:模块安装成功！', '1545214750', '2130706433', '/admin/module/install.html');
INSERT INTO `yzn_adminlog` VALUES ('81', '1', '1', '提示语:模块卸载成功，请及时更新缓存！', '1545214809', '2130706433', '/admin/module/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('82', '0', '0', '提示语:请先登陆', '1545283549', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('83', '1', '1', '提示语:恭喜您，登陆成功', '1545283554', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('84', '1', '1', '提示语:模块安装成功！', '1545283739', '2130706433', '/admin/module/install.html');
INSERT INTO `yzn_adminlog` VALUES ('85', '1', '1', '提示语:模块卸载成功，请及时更新缓存！', '1545283768', '2130706433', '/admin/module/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('86', '0', '1', '提示语:该页面不存在！', '1545283889', '2130706433', '/addons/addons/addonadmin/menuid/35.html');
INSERT INTO `yzn_adminlog` VALUES ('87', '0', '1', '提示语:该页面不存在！', '1545283903', '2130706433', '/addons/addons/addonadmin/menuid/35.html');
INSERT INTO `yzn_adminlog` VALUES ('88', '0', '1', '提示语:该页面不存在！', '1545283908', '2130706433', '/addons/addons/addonadmin/menuid/35.html');
INSERT INTO `yzn_adminlog` VALUES ('89', '0', '1', '提示语:该页面不存在！', '1545283933', '2130706433', '/addons/addons/addonadmin/menuid/35.html');
INSERT INTO `yzn_adminlog` VALUES ('90', '1', '1', '提示语:模块安装成功！', '1545284668', '2130706433', '/admin/module/install.html');
INSERT INTO `yzn_adminlog` VALUES ('91', '1', '1', '提示语:模块卸载成功，请及时更新缓存！', '1545284939', '2130706433', '/admin/module/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('92', '1', '1', '提示语:模块安装成功！', '1545284957', '2130706433', '/admin/module/install.html');
INSERT INTO `yzn_adminlog` VALUES ('93', '1', '1', '提示语:模块卸载成功，请及时更新缓存！', '1545284976', '2130706433', '/admin/module/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('94', '1', '1', '提示语:模块安装成功！', '1545284985', '2130706433', '/admin/module/install.html');
INSERT INTO `yzn_adminlog` VALUES ('95', '1', '1', '提示语:模块卸载成功，请及时更新缓存！', '1545285075', '2130706433', '/admin/module/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('96', '1', '1', '提示语:模块安装成功！', '1545285081', '2130706433', '/admin/module/install.html');
INSERT INTO `yzn_adminlog` VALUES ('97', '1', '1', '提示语:模块卸载成功，请及时更新缓存！', '1545285222', '2130706433', '/admin/module/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('98', '1', '1', '提示语:模块安装成功！', '1545286225', '2130706433', '/admin/module/install.html');
INSERT INTO `yzn_adminlog` VALUES ('99', '1', '1', '提示语:模块卸载成功，请及时更新缓存！', '1545286250', '2130706433', '/admin/module/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('100', '0', '0', '提示语:请先登陆', '1545287845', '2130706433', '/admin');
INSERT INTO `yzn_adminlog` VALUES ('101', '1', '1', '提示语:恭喜您，登陆成功', '1545287855', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('102', '1', '1', '提示语:模块安装成功！', '1545287866', '2130706433', '/admin/module/install.html');
INSERT INTO `yzn_adminlog` VALUES ('103', '0', '1', '提示语:该页面不存在！', '1545287979', '2130706433', '/cms/cms/index/menuid/53.html');
INSERT INTO `yzn_adminlog` VALUES ('104', '1', '1', '提示语:模块卸载成功，请及时更新缓存！', '1545288536', '2130706433', '/admin/module/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('105', '1', '1', '提示语:模块安装成功！', '1545289303', '2130706433', '/admin/module/install.html');
INSERT INTO `yzn_adminlog` VALUES ('106', '1', '1', '提示语:模块卸载成功，请及时更新缓存！', '1545289319', '2130706433', '/admin/module/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('107', '0', '0', '提示语:请先登陆', '1545289602', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('108', '1', '1', '提示语:恭喜您，登陆成功', '1545289609', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('109', '1', '1', '提示语:插件安装成功！', '1545289617', '2130706433', '/addons/addons/install.html');
INSERT INTO `yzn_adminlog` VALUES ('110', '1', '1', '提示语:初始化成功！', '1545289629', '2130706433', '/addons/database/export/isadmin/1.html');
INSERT INTO `yzn_adminlog` VALUES ('111', '1', '1', '提示语:备份完成！', '1545289629', '2130706433', '/addons/database/export/isadmin/1.html?id=0&start=0');
INSERT INTO `yzn_adminlog` VALUES ('112', '1', '1', '提示语:备份完成！', '1545289629', '2130706433', '/addons/database/export/isadmin/1.html?id=1&start=0');
INSERT INTO `yzn_adminlog` VALUES ('113', '1', '1', '提示语:备份完成！', '1545289629', '2130706433', '/addons/database/export/isadmin/1.html?id=2&start=0');
INSERT INTO `yzn_adminlog` VALUES ('114', '1', '1', '提示语:备份完成！', '1545289629', '2130706433', '/addons/database/export/isadmin/1.html?id=3&start=0');
INSERT INTO `yzn_adminlog` VALUES ('115', '1', '1', '提示语:备份完成！', '1545289630', '2130706433', '/addons/database/export/isadmin/1.html?id=4&start=0');
INSERT INTO `yzn_adminlog` VALUES ('116', '1', '1', '提示语:备份完成！', '1545289630', '2130706433', '/addons/database/export/isadmin/1.html?id=5&start=0');
INSERT INTO `yzn_adminlog` VALUES ('117', '1', '1', '提示语:备份完成！', '1545289630', '2130706433', '/addons/database/export/isadmin/1.html?id=6&start=0');
INSERT INTO `yzn_adminlog` VALUES ('118', '1', '1', '提示语:备份完成！', '1545289630', '2130706433', '/addons/database/export/isadmin/1.html?id=7&start=0');
INSERT INTO `yzn_adminlog` VALUES ('119', '1', '1', '提示语:备份完成！', '1545289630', '2130706433', '/addons/database/export/isadmin/1.html?id=8&start=0');
INSERT INTO `yzn_adminlog` VALUES ('120', '1', '1', '提示语:备份完成！', '1545289630', '2130706433', '/addons/database/export/isadmin/1.html?id=9&start=0');
INSERT INTO `yzn_adminlog` VALUES ('121', '1', '1', '提示语:备份完成！', '1545289630', '2130706433', '/addons/database/export/isadmin/1.html?id=10&start=0');
INSERT INTO `yzn_adminlog` VALUES ('122', '1', '1', '提示语:备份完成！', '1545289630', '2130706433', '/addons/database/export/isadmin/1.html?id=11&start=0');
INSERT INTO `yzn_adminlog` VALUES ('123', '0', '0', '提示语:请先登陆', '1545352397', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('124', '1', '1', '提示语:恭喜您，登陆成功', '1545352404', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('125', '0', '0', '提示语:请先登陆', '1545362823', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('126', '1', '1', '提示语:恭喜您，登陆成功', '1545362829', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('127', '1', '1', '提示语:模块安装成功！', '1545362838', '2130706433', '/admin/module/install.html');
INSERT INTO `yzn_adminlog` VALUES ('128', '0', '0', '提示语:请先登陆', '1545368014', '2130706433', '/admin/index/index.html');
INSERT INTO `yzn_adminlog` VALUES ('129', '1', '1', '提示语:恭喜您，登陆成功', '1545368022', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('130', '0', '0', '提示语:请先登陆', '1545369643', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('131', '1', '1', '提示语:恭喜您，登陆成功', '1545369649', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('132', '1', '1', '提示语:模块卸载成功，请及时更新缓存！', '1545369656', '2130706433', '/admin/module/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('133', '1', '1', '提示语:模块安装成功！', '1545369682', '2130706433', '/admin/module/install.html');
INSERT INTO `yzn_adminlog` VALUES ('134', '1', '1', '提示语:模块卸载成功，请及时更新缓存！', '1545370859', '2130706433', '/admin/module/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('135', '0', '1', '提示语:模块已经安装，无法重复安装！', '1545370880', '2130706433', '/admin/module/install.html');
INSERT INTO `yzn_adminlog` VALUES ('136', '1', '1', '提示语:模块卸载成功，请及时更新缓存！', '1545370890', '2130706433', '/admin/module/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('137', '1', '1', '提示语:模块卸载成功，请及时更新缓存！', '1545370981', '2130706433', '/admin/module/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('138', '1', '1', '提示语:模块安装成功！', '1545370985', '2130706433', '/admin/module/install.html');
INSERT INTO `yzn_adminlog` VALUES ('139', '1', '1', '提示语:模块卸载成功，请及时更新缓存！', '1545371248', '2130706433', '/admin/module/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('140', '1', '1', '提示语:模块安装成功！', '1545371329', '2130706433', '/admin/module/install.html');
INSERT INTO `yzn_adminlog` VALUES ('141', '1', '1', '提示语:模块卸载成功，请及时更新缓存！', '1545371406', '2130706433', '/admin/module/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('142', '1', '1', '提示语:模块安装成功！', '1545371416', '2130706433', '/admin/module/install.html');
INSERT INTO `yzn_adminlog` VALUES ('143', '0', '1', '提示语:数据表创建失败！', '1545371872', '2130706433', '/cms/models/add.html');
INSERT INTO `yzn_adminlog` VALUES ('144', '0', '1', '提示语:数据表创建失败！', '1545371902', '2130706433', '/cms/models/add.html');
INSERT INTO `yzn_adminlog` VALUES ('145', '0', '1', '提示语:数据表创建失败！', '1545371944', '2130706433', '/cms/models/add.html');
INSERT INTO `yzn_adminlog` VALUES ('146', '1', '1', '提示语:添加模型成功！', '1545371952', '2130706433', '/cms/models/add.html');
INSERT INTO `yzn_adminlog` VALUES ('147', '0', '0', '提示语:请先登陆', '1545372831', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('148', '1', '1', '提示语:恭喜您，登陆成功', '1545372836', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('149', '0', '1', '提示语:数据表创建失败！', '1545373279', '2130706433', '/cms/models/add.html');
INSERT INTO `yzn_adminlog` VALUES ('150', '0', '1', '提示语:模型名称不得为空', '1545373541', '2130706433', '/cms/models/add.html');
INSERT INTO `yzn_adminlog` VALUES ('151', '0', '1', '提示语:表键名不得为空', '1545373551', '2130706433', '/cms/models/add.html');
INSERT INTO `yzn_adminlog` VALUES ('152', '1', '1', '提示语:添加模型成功！', '1545373569', '2130706433', '/cms/models/add.html');
INSERT INTO `yzn_adminlog` VALUES ('153', '0', '1', '提示语:表键名只支持字母', '1545373589', '2130706433', '/cms/models/add.html');
INSERT INTO `yzn_adminlog` VALUES ('154', '0', '1', '提示语:表键名已存在', '1545373751', '2130706433', '/cms/models/add.html');
INSERT INTO `yzn_adminlog` VALUES ('155', '0', '1', '提示语:模型名称不得为空', '1545374627', '2130706433', '/cms/models/add.html');
INSERT INTO `yzn_adminlog` VALUES ('156', '0', '1', '提示语:表键名长度错误', '1545374633', '2130706433', '/cms/models/add.html');
INSERT INTO `yzn_adminlog` VALUES ('157', '0', '1', '提示语:表键名只支持字母', '1545374703', '2130706433', '/cms/models/add.html');
INSERT INTO `yzn_adminlog` VALUES ('158', '1', '1', '提示语:添加模型成功！', '1545374709', '2130706433', '/cms/models/add.html');
INSERT INTO `yzn_adminlog` VALUES ('159', '0', '1', '提示语:模型名称不得为空', '1545374820', '2130706433', '/cms/models/add.html');
INSERT INTO `yzn_adminlog` VALUES ('160', '0', '1', '提示语:模型类型类型错误', '1545374870', '2130706433', '/cms/models/add.html');
INSERT INTO `yzn_adminlog` VALUES ('161', '0', '0', '提示语:请先登陆', '1545379565', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('162', '1', '1', '提示语:恭喜您，登陆成功', '1545379574', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('163', '1', '1', '提示语:模块卸载成功，请及时更新缓存！', '1545384130', '2130706433', '/admin/module/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('164', '1', '1', '提示语:模块卸载成功，请及时更新缓存！', '1545384588', '2130706433', '/admin/module/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('165', '1', '1', '提示语:模块卸载成功，请及时更新缓存！', '1545384777', '2130706433', '/admin/module/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('166', '1', '1', '提示语:模块安装成功！', '1545384780', '2130706433', '/admin/module/install.html');
INSERT INTO `yzn_adminlog` VALUES ('167', '1', '1', '提示语:模块卸载成功，请及时更新缓存！', '1545384792', '2130706433', '/admin/module/uninstall.html');
INSERT INTO `yzn_adminlog` VALUES ('168', '1', '1', '提示语:模块安装成功！', '1545384801', '2130706433', '/admin/module/install.html');

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
) ENGINE=MyISAM AUTO_INCREMENT=240 DEFAULT CHARSET=utf8 COMMENT='附件表';

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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='权限组表';

-- ----------------------------
-- Records of yzn_auth_group
-- ----------------------------
INSERT INTO `yzn_auth_group` VALUES ('1', 'admin', '1', '超级管理员', '拥有所有权限', '1', '154,155,146,149,149,158,159,160,150,151,161,162,163,157,164,165,152,152,153,156,147,148');
INSERT INTO `yzn_auth_group` VALUES ('2', 'admin', '1', '编辑', '编辑', '1', '154,155,146,149,149,158,159,160,150,151,161,162,163,157,164,165,152,152,153,156,147,148');

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
) ENGINE=MyISAM AUTO_INCREMENT=178 DEFAULT CHARSET=utf8 COMMENT='规则表';

-- ----------------------------
-- Records of yzn_auth_rule
-- ----------------------------
INSERT INTO `yzn_auth_rule` VALUES ('146', 'admin', '2', 'admin/setting/index', '设置', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('147', 'admin', '2', 'admin/module/index', '模块', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('148', 'admin', '2', 'admin/addons/index', '扩展', '-1', '');
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
INSERT INTO `yzn_auth_rule` VALUES ('166', 'addons', '1', 'addons/addons/index', '插件管理', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('167', 'addons', '1', 'addons/addons/hooks', '行为管理', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('168', 'addons', '1', 'addons/addons/addonadmin', '插件后台列表', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('169', 'addons', '1', 'addons/database/index', '数据库备份', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('170', 'addons', '1', 'addons/database/restore', '备份还原', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('171', 'addons', '1', 'addons/database/del', '删除备份', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('172', 'addons', '1', 'addons/database/repair', '修复表', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('173', 'addons', '1', 'addons/database/optimize', '优化表', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('174', 'addons', '1', 'addons/database/import', '还原表', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('175', 'addons', '1', 'addons/database/export', '备份数据库', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('176', 'addons', '1', 'addons/database/download', '备份数据库下载', '1', '');
INSERT INTO `yzn_auth_rule` VALUES ('177', 'addons', '2', 'addons/addons/index', '扩展', '1', '');

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
INSERT INTO `yzn_cache` VALUES ('1', 'Config', '网站配置', 'admin', 'Config', 'config_cache', '1');
INSERT INTO `yzn_cache` VALUES ('2', 'Menu', '后台菜单', 'admin', 'Menu', 'menu_cache', '1');
INSERT INTO `yzn_cache` VALUES ('3', 'Module', '可用模块列表', 'admin', 'Module', 'module_cache', '1');
INSERT INTO `yzn_cache` VALUES ('5', 'Model', '模型列表', 'cms', 'Models', 'model_cache', '0');

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='栏目表';

-- ----------------------------
-- Records of yzn_category
-- ----------------------------

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
) ENGINE=MyISAM AUTO_INCREMENT=152 DEFAULT CHARSET=utf8 COMMENT='网站配置';

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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='插件钩子';

-- ----------------------------
-- Records of yzn_hooks
-- ----------------------------
INSERT INTO `yzn_hooks` VALUES ('1', 'pageHeader', '页面header钩子，一般用于加载插件CSS文件和代码', '1', '1509174020', '');
INSERT INTO `yzn_hooks` VALUES ('2', 'pageFooter', '页面footer钩子，一般用于加载插件JS文件和JS代码', '1', '1509174020', '');

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
) ENGINE=MyISAM AUTO_INCREMENT=131 DEFAULT CHARSET=utf8 COMMENT='后台菜单表';

-- ----------------------------
-- Records of yzn_menu
-- ----------------------------
INSERT INTO `yzn_menu` VALUES ('3', '设置', 'icon-shezhi', '0', 'admin', 'setting', 'index', '', '1', '', '0', '2');
INSERT INTO `yzn_menu` VALUES ('4', '模块', 'icon-yingyong', '0', 'admin', 'module', 'index', '', '1', '', '0', '9');
INSERT INTO `yzn_menu` VALUES ('5', '扩展', 'icon-module', '0', 'addons', 'addons', 'index', '', '1', '', '0', '10');
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
INSERT INTO `yzn_menu` VALUES ('32', '插件扩展', 'icon-module', '5', 'addons', 'addons', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('33', '插件管理', 'icon-plugins-', '32', 'addons', 'addons', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('34', '行为管理', 'icon-hangweifenxi', '32', 'addons', 'addons', 'hooks', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('35', '插件后台列表', 'icon-016', '5', 'addons', 'addons', 'addonadmin', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('36', '本地模块', 'icon-yingyong', '4', 'admin', 'module', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('37', '模块管理', 'icon-mokuaishezhi', '36', 'admin', 'module', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('38', '模块后台列表', 'icon-016', '4', 'admin', 'module', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('66', '修复表', '', '63', 'addons', 'database', 'repair', 'isadmin=1', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('65', '删除备份', '', '63', 'addons', 'database', 'del', 'isadmin=1', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('64', '备份还原', '', '63', 'addons', 'database', 'restore', 'isadmin=1', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('63', '数据库备份', '', '35', 'addons', 'database', 'index', 'isadmin=1', '1', '数据库备份插件管理后台！', '0', '0');
INSERT INTO `yzn_menu` VALUES ('67', '优化表', '', '63', 'addons', 'database', 'optimize', 'isadmin=1', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('68', '还原表', '', '63', 'addons', 'database', 'import', 'isadmin=1', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('69', '备份数据库', '', '63', 'addons', 'database', 'export', 'isadmin=1', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('70', '备份数据库下载', '', '63', 'addons', 'database', 'download', 'isadmin=1', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('130', '禁用模型', '', '126', 'cms', 'models', 'disabled', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('129', '删除模型', '', '126', 'cms', 'models', 'delete', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('128', '修改模型', '', '126', 'cms', 'models', 'edit', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('127', '添加模型', '', '126', 'cms', 'models', 'add', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('126', '模型管理', '', '124', 'cms', 'models', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('125', '栏目列表', '', '124', 'cms', 'category', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('124', '相关设置', '', '121', 'cms', 'category', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('123', '管理内容', '', '122', 'cms', 'cms', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('122', '内容管理', '', '121', 'cms', 'cms', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('121', '内容', 'iconfont icon-article', '0', 'cms', 'index', 'index', '', '1', '', '0', '3');

-- ----------------------------
-- Table structure for `yzn_model`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_model`;
CREATE TABLE `yzn_model` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(30) NOT NULL DEFAULT '' COMMENT '模型名称',
  `description` char(100) NOT NULL DEFAULT '' COMMENT '描述',
  `tablename` char(20) NOT NULL DEFAULT '' COMMENT '表名',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '模型类别：1-独立表，2-主附表',
  `setting` text COMMENT '配置信息',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `items` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '信息数',
  `enablesearch` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启全站搜索',
  `disabled` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否禁用 1禁用',
  `listorders` tinyint(3) NOT NULL DEFAULT '0' COMMENT '排序',
  `mark` tinyint(1) NOT NULL DEFAULT '0' COMMENT '模块标识',
  PRIMARY KEY (`id`),
  KEY `type` (`mark`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='内容模型列表';

-- ----------------------------
-- Records of yzn_model
-- ----------------------------

-- ----------------------------
-- Table structure for `yzn_model_field`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_model_field`;
CREATE TABLE `yzn_model_field` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `modelid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '模型ID',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '字段名',
  `title` varchar(30) NOT NULL DEFAULT '' COMMENT '别名',
  `tips` text COMMENT '字段提示',
  `css` varchar(30) NOT NULL DEFAULT '' COMMENT '表单样式',
  `pattern` varchar(255) NOT NULL DEFAULT '' COMMENT '数据校验正则',
  `errortips` varchar(255) NOT NULL DEFAULT '' COMMENT '数据校验未通过的提示信息',
  `formtype` varchar(20) NOT NULL DEFAULT '' COMMENT '字段类型',
  `define` varchar(128) NOT NULL COMMENT '字段定义',
  `setting` mediumtext COMMENT '额外设置',
  `iscore` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否内部字段 1是',
  `issystem` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否系统字段 1 是',
  `isunique` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '值唯一',
  `isbase` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '作为基本信息',
  `issearch` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '作为搜索条件',
  `isadd` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '在前台投稿中显示',
  `isfulltext` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '作为全站搜索信息',
  `isposition` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否入库到推荐位',
  `listorder` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `disabled` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1 禁用 0启用',
  PRIMARY KEY (`id`),
  KEY `name` (`name`,`modelid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='模型字段列表';

-- ----------------------------
-- Records of yzn_model_field
-- ----------------------------

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
INSERT INTO `yzn_module` VALUES ('cms', 'cms模块', 'b19cc279ed484c13c96c2f7142e2f437', '0', '1', '1.0.0', null, '1545384801', '1545384801', '0');
