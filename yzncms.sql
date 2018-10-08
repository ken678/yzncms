/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : yzncms

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2018-10-08 13:15:14
*/

SET FOREIGN_KEY_CHECKS=0;

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
INSERT INTO `yzn_admin` VALUES ('1', 'admin', '9724b5e6c56b95f5723009ef81961bfe', '1', 'Wo0bAa', '御宅男', '1538972203', '2130706433', '530765310@qq.com', '1');
INSERT INTO `yzn_admin` VALUES ('2', 'ken678', '932e31f030b850a87702a86c0e16db16', '4', 'Sxq6dR', '御宅男', '1538036501', '2130706433', '530765310@qq.com', '1');

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
) ENGINE=MyISAM AUTO_INCREMENT=166 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yzn_adminlog
-- ----------------------------
INSERT INTO `yzn_adminlog` VALUES ('1', '1', '1', '提示语:删除日志成功！', '1537413945', '2130706433', '/admin/adminlog/deletelog.html');
INSERT INTO `yzn_adminlog` VALUES ('2', '1', '1', '提示语:操作成功!', '1537413960', '2130706433', '/admin/auth_manager/writegroup.html');
INSERT INTO `yzn_adminlog` VALUES ('3', '0', '1', '提示语:该页面不存在！', '1537419887', '2130706433', '/admin/menu/edit.html5');
INSERT INTO `yzn_adminlog` VALUES ('4', '0', '1', '提示语:该页面不存在！', '1537419890', '2130706433', '/admin/menu/edit.html5');
INSERT INTO `yzn_adminlog` VALUES ('5', '0', '1', '提示语:该页面不存在！', '1537419891', '2130706433', '/admin/menu/edit.html5');
INSERT INTO `yzn_adminlog` VALUES ('6', '1', '1', '提示语:编辑成功！', '1537419929', '2130706433', '/admin/menu/edit.html');
INSERT INTO `yzn_adminlog` VALUES ('7', '1', '1', '提示语:编辑成功！', '1537419955', '2130706433', '/admin/menu/edit.html');
INSERT INTO `yzn_adminlog` VALUES ('8', '0', '0', '提示语:请先登陆', '1537438192', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('9', '0', '0', '提示语:请先登陆', '1537438665', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('10', '0', '0', '提示语:请先登陆', '1537438878', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('11', '0', '0', '提示语:请先登陆', '1537438901', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('12', '0', '0', '提示语:请先登陆', '1537489380', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('13', '1', '1', '提示语:恭喜您，登陆成功', '1537489386', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('14', '0', '0', '提示语:请先登陆', '1537515198', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('15', '1', '1', '提示语:恭喜您，登陆成功', '1537515202', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('16', '1', '1', '提示语:删除日志成功！', '1537515295', '2130706433', '/admin/adminlog/deletelog.html');
INSERT INTO `yzn_adminlog` VALUES ('17', '0', '0', '提示语:请先登陆', '1537852961', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('18', '1', '1', '提示语:恭喜您，登陆成功', '1537852968', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('19', '0', '0', '提示语:请先登陆', '1537921953', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('20', '1', '1', '提示语:恭喜您，登陆成功', '1537921959', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('21', '0', '0', '提示语:请先登陆', '1537950507', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('22', '1', '1', '提示语:恭喜您，登陆成功', '1537950513', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('23', '1', '1', '提示语:删除日志成功！', '1537950521', '2130706433', '/admin/adminlog/deletelog.html');
INSERT INTO `yzn_adminlog` VALUES ('24', '0', '1', '提示语:该页面不存在！', '1537951249', '2130706433', '/admin/menu/listorder.html');
INSERT INTO `yzn_adminlog` VALUES ('25', '0', '1', '提示语:该页面不存在！', '1537951305', '2130706433', '/admin/menu/listorder.html');
INSERT INTO `yzn_adminlog` VALUES ('26', '0', '1', '提示语:该页面不存在！', '1537951307', '2130706433', '/admin/menu/listorder.html');
INSERT INTO `yzn_adminlog` VALUES ('27', '0', '1', '提示语:该页面不存在！', '1537951313', '2130706433', '/admin/menu/listorder.html');
INSERT INTO `yzn_adminlog` VALUES ('28', '1', '1', '提示语:菜单排序成功！', '1537951638', '2130706433', '/admin/menu/listorder.html');
INSERT INTO `yzn_adminlog` VALUES ('29', '1', '1', '提示语:菜单排序成功！', '1537951661', '2130706433', '/admin/menu/listorder.html');
INSERT INTO `yzn_adminlog` VALUES ('30', '1', '1', '提示语:删除菜单成功！', '1537951666', '2130706433', '/admin/menu/delete.html');
INSERT INTO `yzn_adminlog` VALUES ('31', '1', '1', '提示语:删除菜单成功！', '1537951670', '2130706433', '/admin/menu/delete.html');
INSERT INTO `yzn_adminlog` VALUES ('32', '1', '1', '提示语:菜单排序成功！', '1537951700', '2130706433', '/admin/menu/listorder.html');
INSERT INTO `yzn_adminlog` VALUES ('33', '1', '1', '提示语:菜单排序成功！', '1537951705', '2130706433', '/admin/menu/listorder.html');
INSERT INTO `yzn_adminlog` VALUES ('34', '1', '1', '提示语:菜单排序成功！', '1537951710', '2130706433', '/admin/menu/listorder.html');
INSERT INTO `yzn_adminlog` VALUES ('35', '1', '1', '提示语:菜单排序成功！', '1537951712', '2130706433', '/admin/menu/listorder.html');
INSERT INTO `yzn_adminlog` VALUES ('36', '1', '1', '提示语:菜单排序成功！', '1537951716', '2130706433', '/admin/menu/listorder.html');
INSERT INTO `yzn_adminlog` VALUES ('37', '1', '1', '提示语:菜单排序成功！', '1537951717', '2130706433', '/admin/menu/listorder.html');
INSERT INTO `yzn_adminlog` VALUES ('38', '1', '1', '提示语:菜单排序成功！', '1537951718', '2130706433', '/admin/menu/listorder.html');
INSERT INTO `yzn_adminlog` VALUES ('39', '1', '1', '提示语:菜单排序成功！', '1537951721', '2130706433', '/admin/menu/listorder.html');
INSERT INTO `yzn_adminlog` VALUES ('40', '1', '1', '提示语:菜单排序成功！', '1537951722', '2130706433', '/admin/menu/listorder.html');
INSERT INTO `yzn_adminlog` VALUES ('41', '1', '1', '提示语:菜单排序成功！', '1537951732', '2130706433', '/admin/menu/listorder.html');
INSERT INTO `yzn_adminlog` VALUES ('42', '1', '1', '提示语:菜单排序成功！', '1537951733', '2130706433', '/admin/menu/listorder.html');
INSERT INTO `yzn_adminlog` VALUES ('43', '1', '1', '提示语:菜单排序成功！', '1537951736', '2130706433', '/admin/menu/listorder.html');
INSERT INTO `yzn_adminlog` VALUES ('44', '1', '1', '提示语:菜单排序成功！', '1537951744', '2130706433', '/admin/menu/listorder.html');
INSERT INTO `yzn_adminlog` VALUES ('45', '1', '1', '提示语:菜单排序成功！', '1537951745', '2130706433', '/admin/menu/listorder.html');
INSERT INTO `yzn_adminlog` VALUES ('46', '1', '1', '提示语:菜单排序成功！', '1537951760', '2130706433', '/admin/menu/listorder.html');
INSERT INTO `yzn_adminlog` VALUES ('47', '1', '1', '提示语:菜单排序成功！', '1537951761', '2130706433', '/admin/menu/listorder.html');
INSERT INTO `yzn_adminlog` VALUES ('48', '0', '0', '提示语:请先登陆', '1537953210', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('49', '1', '1', '提示语:恭喜您，登陆成功', '1537953216', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('50', '0', '1', '提示语:更新成功', '1537955757', '2130706433', '/admin/config/setstate.html');
INSERT INTO `yzn_adminlog` VALUES ('51', '0', '1', '提示语:更新成功', '1537955765', '2130706433', '/admin/config/setstate.html');
INSERT INTO `yzn_adminlog` VALUES ('52', '0', '1', '提示语:更新成功', '1537955767', '2130706433', '/admin/config/setstate.html');
INSERT INTO `yzn_adminlog` VALUES ('53', '0', '1', '提示语:更新成功', '1537955772', '2130706433', '/admin/config/setstate.html');
INSERT INTO `yzn_adminlog` VALUES ('54', '0', '1', '提示语:更新成功', '1537955780', '2130706433', '/admin/config/setstate.html');
INSERT INTO `yzn_adminlog` VALUES ('55', '0', '1', '提示语:更新成功', '1537955822', '2130706433', '/admin/config/setstate.html');
INSERT INTO `yzn_adminlog` VALUES ('56', '0', '1', '提示语:更新成功', '1537955823', '2130706433', '/admin/config/setstate.html');
INSERT INTO `yzn_adminlog` VALUES ('57', '0', '1', '提示语:更新成功', '1537955943', '2130706433', '/admin/config/setstate.html');
INSERT INTO `yzn_adminlog` VALUES ('58', '0', '1', '提示语:更新成功', '1537955947', '2130706433', '/admin/config/setstate.html');
INSERT INTO `yzn_adminlog` VALUES ('59', '0', '1', '提示语:更新成功', '1537955947', '2130706433', '/admin/config/setstate.html');
INSERT INTO `yzn_adminlog` VALUES ('60', '0', '1', '提示语:更新成功', '1537955948', '2130706433', '/admin/config/setstate.html');
INSERT INTO `yzn_adminlog` VALUES ('61', '0', '1', '提示语:更新成功', '1537955948', '2130706433', '/admin/config/setstate.html');
INSERT INTO `yzn_adminlog` VALUES ('62', '0', '1', '提示语:更新成功', '1537955949', '2130706433', '/admin/config/setstate.html');
INSERT INTO `yzn_adminlog` VALUES ('63', '0', '1', '提示语:更新成功', '1537955949', '2130706433', '/admin/config/setstate.html');
INSERT INTO `yzn_adminlog` VALUES ('64', '0', '1', '提示语:更新成功', '1537955952', '2130706433', '/admin/config/setstate.html');
INSERT INTO `yzn_adminlog` VALUES ('65', '0', '1', '提示语:更新成功', '1537955952', '2130706433', '/admin/config/setstate.html');
INSERT INTO `yzn_adminlog` VALUES ('66', '0', '1', '提示语:更新成功', '1537955953', '2130706433', '/admin/config/setstate.html');
INSERT INTO `yzn_adminlog` VALUES ('67', '0', '1', '提示语:更新成功', '1537955953', '2130706433', '/admin/config/setstate.html');
INSERT INTO `yzn_adminlog` VALUES ('68', '0', '1', '提示语:更新成功', '1537955954', '2130706433', '/admin/config/setstate.html');
INSERT INTO `yzn_adminlog` VALUES ('69', '0', '1', '提示语:更新成功', '1537955954', '2130706433', '/admin/config/setstate.html');
INSERT INTO `yzn_adminlog` VALUES ('70', '0', '1', '提示语:更新成功', '1537955955', '2130706433', '/admin/config/setstate.html');
INSERT INTO `yzn_adminlog` VALUES ('71', '0', '1', '提示语:配置分组不能为空', '1537956711', '2130706433', '/admin/config/add.html');
INSERT INTO `yzn_adminlog` VALUES ('72', '0', '1', '提示语:配置分组不能为空', '1537956719', '2130706433', '/admin/config/add.html');
INSERT INTO `yzn_adminlog` VALUES ('73', '0', '1', '提示语:配置分组不能为空', '1537956746', '2130706433', '/admin/config/add.html');
INSERT INTO `yzn_adminlog` VALUES ('74', '0', '1', '提示语:配置分组不能为空', '1537956805', '2130706433', '/admin/config/add.html');
INSERT INTO `yzn_adminlog` VALUES ('75', '0', '1', '提示语:更新成功', '1537957024', '2130706433', '/admin/config/setstate.html');
INSERT INTO `yzn_adminlog` VALUES ('76', '0', '1', '提示语:更新成功', '1537957025', '2130706433', '/admin/config/setstate.html');
INSERT INTO `yzn_adminlog` VALUES ('77', '1', '1', '提示语:配置更新成功~', '1537957098', '2130706433', '/admin/config/setstate.html');
INSERT INTO `yzn_adminlog` VALUES ('78', '1', '1', '提示语:配置更新成功~', '1537957099', '2130706433', '/admin/config/setstate.html');
INSERT INTO `yzn_adminlog` VALUES ('79', '0', '1', '提示语:配置分组不能为空', '1537957447', '2130706433', '/admin/config/add.html');
INSERT INTO `yzn_adminlog` VALUES ('80', '1', '1', '提示语:设置更新成功', '1537957478', '2130706433', '/admin/config/setting/group/base.html');
INSERT INTO `yzn_adminlog` VALUES ('81', '1', '1', '提示语:设置更新成功', '1537957484', '2130706433', '/admin/config/setting/group/base.html');
INSERT INTO `yzn_adminlog` VALUES ('82', '1', '1', '提示语:设置更新成功', '1537957492', '2130706433', '/admin/config/setting/group/base.html');
INSERT INTO `yzn_adminlog` VALUES ('83', '1', '1', '提示语:删除日志成功！', '1537957518', '2130706433', '/admin/adminlog/deletelog.html');
INSERT INTO `yzn_adminlog` VALUES ('84', '0', '0', '提示语:请先登陆', '1538015668', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('85', '1', '1', '提示语:恭喜您，登陆成功', '1538015673', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('86', '0', '1', '提示语:超级管理员角色不能被删除!', '1538016227', '2130706433', '/admin/auth_manager/deletegroup.html');
INSERT INTO `yzn_adminlog` VALUES ('87', '1', '1', '提示语:操作成功!', '1538017090', '2130706433', '/admin/auth_manager/writegroup.html');
INSERT INTO `yzn_adminlog` VALUES ('88', '1', '1', '提示语:操作成功!', '1538017097', '2130706433', '/admin/auth_manager/writegroup.html');
INSERT INTO `yzn_adminlog` VALUES ('89', '0', '0', '提示语:请先登陆', '1538036109', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('90', '1', '1', '提示语:恭喜您，登陆成功', '1538036114', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('91', '1', '1', '提示语:删除日志成功！', '1538036126', '2130706433', '/admin/adminlog/deletelog.html');
INSERT INTO `yzn_adminlog` VALUES ('92', '0', '1', '提示语:禁止对超级管理员执行该操作！', '1538036130', '2130706433', '/admin/manager/del/id/1.html');
INSERT INTO `yzn_adminlog` VALUES ('93', '1', '1', '提示语:修改成功！', '1538036136', '2130706433', '/admin/manager/edit.html');
INSERT INTO `yzn_adminlog` VALUES ('94', '0', '0', '提示语:请先登陆', '1538036464', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('95', '1', '1', '提示语:恭喜您，登陆成功', '1538036469', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('96', '1', '0', '提示语:注销成功！', '1538036488', '2130706433', '/admin/index/logout.html');
INSERT INTO `yzn_adminlog` VALUES ('97', '0', '0', '提示语:密码错误！', '1538036495', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('98', '0', '0', '提示语:密码错误！', '1538036498', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('99', '1', '2', '提示语:恭喜您，登陆成功', '1538036501', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('100', '0', '0', '提示语:请先登陆', '1538038549', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('101', '1', '1', '提示语:恭喜您，登陆成功', '1538038554', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('102', '0', '1', '提示语:该信息不存在！', '1538039356', '2130706433', '/admin/manager/edit.html?id=undefined');
INSERT INTO `yzn_adminlog` VALUES ('103', '0', '0', '提示语:请先登陆', '1538041871', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('104', '1', '1', '提示语:恭喜您，登陆成功', '1538041878', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('105', '0', '0', '提示语:请先登陆', '1538100996', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('106', '1', '1', '提示语:恭喜您，登陆成功', '1538101001', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('107', '0', '0', '提示语:请先登陆', '1538112751', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('108', '1', '1', '提示语:恭喜您，登陆成功', '1538112756', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('109', '0', '0', '提示语:请先登陆', '1538123469', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('110', '1', '1', '提示语:恭喜您，登陆成功', '1538123474', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('111', '1', '1', '提示语:设置更新成功', '1538125180', '2130706433', '/admin/config/setting/group/base.html');
INSERT INTO `yzn_adminlog` VALUES ('112', '1', '1', '提示语:设置更新成功', '1538125184', '2130706433', '/admin/config/setting/group/base.html');
INSERT INTO `yzn_adminlog` VALUES ('113', '0', '1', '提示语:该页面不存在！', '1538128163', '2130706433', '/admin/index/clear.html?type=all&_=1538128148020');
INSERT INTO `yzn_adminlog` VALUES ('114', '0', '1', '提示语:该页面不存在！', '1538128182', '2130706433', '/admin/index/clear.html?type=all&_=1538128180930');
INSERT INTO `yzn_adminlog` VALUES ('115', '0', '1', '提示语:该页面不存在！', '1538128199', '2130706433', '/admin/index/clear.html?type=all&_=1538128195578');
INSERT INTO `yzn_adminlog` VALUES ('116', '0', '1', '提示语:该页面不存在！', '1538128506', '2130706433', '/admin/index/clear.html?type=all&_=1538128505339');
INSERT INTO `yzn_adminlog` VALUES ('117', '0', '1', '提示语:该页面不存在！', '1538128509', '2130706433', '/admin/index/clear.html?type=data&_=1538128505340');
INSERT INTO `yzn_adminlog` VALUES ('118', '0', '1', '提示语:该页面不存在！', '1538128515', '2130706433', '/admin/index/clear.html?type=data&_=1538128505341');
INSERT INTO `yzn_adminlog` VALUES ('119', '0', '1', '提示语:该页面不存在！', '1538128531', '2130706433', '/admin/index/clear.html?type=all&_=1538128505342');
INSERT INTO `yzn_adminlog` VALUES ('120', '0', '1', '提示语:该页面不存在！', '1538128843', '2130706433', '/admin/index/clear.html?type=all&_=1538128801004');
INSERT INTO `yzn_adminlog` VALUES ('121', '0', '0', '提示语:请先登陆', '1538179894', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('122', '1', '1', '提示语:恭喜您，登陆成功', '1538179899', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('123', '1', '1', '提示语:', '1538180175', '2130706433', '/admin/index/cache.html?type=all&_=1538179901088');
INSERT INTO `yzn_adminlog` VALUES ('124', '1', '1', '提示语:', '1538180179', '2130706433', '/admin/index/cache.html');
INSERT INTO `yzn_adminlog` VALUES ('125', '1', '1', '提示语:', '1538180191', '2130706433', '/admin/index/cache.html?type=all&_=1538179901089');
INSERT INTO `yzn_adminlog` VALUES ('126', '1', '1', '提示语:', '1538180207', '2130706433', '/admin/index/cache.html?type=template&_=1538179901090');
INSERT INTO `yzn_adminlog` VALUES ('127', '1', '1', '提示语:', '1538181726', '2130706433', '/admin/index/cache.html?type=all&_=1538180211658');
INSERT INTO `yzn_adminlog` VALUES ('128', '1', '1', '提示语:', '1538181753', '2130706433', '/admin/index/cache.html?type=template&_=1538180211659');
INSERT INTO `yzn_adminlog` VALUES ('129', '1', '1', '提示语:', '1538181995', '2130706433', '/admin/index/cache.html?type=all&_=1538180211660');
INSERT INTO `yzn_adminlog` VALUES ('130', '1', '1', '提示语:', '1538182022', '2130706433', '/admin/index/cache.html?type=all&_=1538180211661');
INSERT INTO `yzn_adminlog` VALUES ('131', '1', '1', '提示语:', '1538182053', '2130706433', '/admin/index/cache.html?type=all&_=1538180211662');
INSERT INTO `yzn_adminlog` VALUES ('132', '1', '1', '提示语:', '1538182818', '2130706433', '/admin/index/cache.html?type=all&_=1538182816516');
INSERT INTO `yzn_adminlog` VALUES ('133', '0', '0', '提示语:请先登陆', '1538184845', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('134', '1', '1', '提示语:恭喜您，登陆成功', '1538184851', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('135', '1', '1', '提示语:', '1538184984', '2130706433', '/admin/index/cache.html?type=all&_=1538184853968');
INSERT INTO `yzn_adminlog` VALUES ('136', '0', '0', '提示语:请先登陆', '1538190768', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('137', '1', '1', '提示语:恭喜您，登陆成功', '1538190774', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('138', '1', '1', '提示语:删除日志成功！', '1538191001', '2130706433', '/admin/adminlog/deletelog.html');
INSERT INTO `yzn_adminlog` VALUES ('139', '1', '1', '提示语:删除日志成功！', '1538191009', '2130706433', '/admin/adminlog/deletelog.html');
INSERT INTO `yzn_adminlog` VALUES ('140', '1', '1', '提示语:删除日志成功！', '1538191550', '2130706433', '/admin/adminlog/deletelog.html');
INSERT INTO `yzn_adminlog` VALUES ('141', '1', '1', '提示语:删除日志成功！', '1538191556', '2130706433', '/admin/adminlog/deletelog.html');
INSERT INTO `yzn_adminlog` VALUES ('142', '1', '1', '提示语:设置更新成功', '1538191609', '2130706433', '/admin/config/setting/group/base.html');
INSERT INTO `yzn_adminlog` VALUES ('143', '1', '1', '提示语:设置更新成功', '1538191702', '2130706433', '/admin/config/setting/group/base.html');
INSERT INTO `yzn_adminlog` VALUES ('144', '1', '1', '提示语:设置更新成功', '1538191824', '2130706433', '/admin/config/setting/group/base.html');
INSERT INTO `yzn_adminlog` VALUES ('145', '0', '0', '提示语:请先登陆', '1538212437', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('146', '1', '1', '提示语:恭喜您，登陆成功', '1538212443', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('147', '1', '1', '提示语:配置添加成功~', '1538212563', '2130706433', '/admin/config/add.html');
INSERT INTO `yzn_adminlog` VALUES ('148', '0', '0', '提示语:请先登陆', '1538267823', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('149', '1', '1', '提示语:恭喜您，登陆成功', '1538267829', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('150', '0', '0', '提示语:请先登陆', '1538287398', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('151', '1', '1', '提示语:恭喜您，登陆成功', '1538287403', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('152', '0', '0', '提示语:请先登陆', '1538297790', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('153', '1', '1', '提示语:恭喜您，登陆成功', '1538297831', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('154', '0', '0', '提示语:请先登陆', '1538957873', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('155', '1', '1', '提示语:恭喜您，登陆成功', '1538957879', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('156', '1', '1', '提示语:删除日志成功！', '1538957897', '2130706433', '/admin/adminlog/deletelog.html');
INSERT INTO `yzn_adminlog` VALUES ('157', '1', '1', '提示语:删除日志成功！', '1538957903', '2130706433', '/admin/adminlog/deletelog.html');
INSERT INTO `yzn_adminlog` VALUES ('158', '0', '0', '提示语:请先登陆', '1538972199', '2130706433', '/admin/');
INSERT INTO `yzn_adminlog` VALUES ('159', '1', '1', '提示语:恭喜您，登陆成功', '1538972203', '2130706433', '/admin/index/login.html');
INSERT INTO `yzn_adminlog` VALUES ('160', '1', '1', '提示语:设置更新成功', '1538972337', '2130706433', '/admin/config/setting/group/base.html');
INSERT INTO `yzn_adminlog` VALUES ('161', '1', '1', '提示语:设置更新成功', '1538972446', '2130706433', '/admin/config/setting/group/base.html');
INSERT INTO `yzn_adminlog` VALUES ('162', '1', '1', '提示语:', '1538974055', '2130706433', '/admin/index/cache.html?type=all&_=1538974033013');
INSERT INTO `yzn_adminlog` VALUES ('163', '1', '1', '提示语:设置更新成功', '1538974064', '2130706433', '/admin/config/setting/group/base.html');
INSERT INTO `yzn_adminlog` VALUES ('164', '1', '1', '提示语:', '1538974129', '2130706433', '/admin/index/cache.html?type=all&_=1538974057560');
INSERT INTO `yzn_adminlog` VALUES ('165', '1', '1', '提示语:', '1538975266', '2130706433', '/admin/index/cache.html?type=all&_=1538975263302');

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
) ENGINE=MyISAM AUTO_INCREMENT=52 DEFAULT CHARSET=utf8 COMMENT='附件表';

-- ----------------------------
-- Records of yzn_attachment
-- ----------------------------
INSERT INTO `yzn_attachment` VALUES ('50', '1', 'ico07.png', '', 'images/20181008/1844fb19801c94518296cf5b3543b5f1.png', '', '', 'image/png', 'png', '1218', 'f539a23c5b781b6a65c444c5ab12210d', 'dd2c921643a6a5b7bda8fc3955e2dee802054bd4', 'local', '0', '0', '100', '1');
INSERT INTO `yzn_attachment` VALUES ('51', '1', 'logo.jpg', '', 'images/20181008/57784850181fd366b30e19969cf305dd.jpg', '', '', 'image/jpeg', 'jpg', '26199', '34658a4f12fbce0cc7183d108416c934', '99b8eb819c9d1cca902478002dadb361c30cb12d', 'local', '0', '0', '100', '1');

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
INSERT INTO `yzn_auth_group` VALUES ('1', 'admin', '1', '超级管理员', '拥有所有权限', '1', '154,155,146,149,149,150,151,152,152,153,156,147,148');
INSERT INTO `yzn_auth_group` VALUES ('4', 'admin', '1', '编辑', '编辑', '1', '154,155,146,149,149,150,151,152,152,153,156,147,148');

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
) ENGINE=MyISAM AUTO_INCREMENT=157 DEFAULT CHARSET=utf8 COMMENT='规则表';

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
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yzn_config
-- ----------------------------
INSERT INTO `yzn_config` VALUES ('1', 'web_site_status', 'switch', '站点开关', 'base', '', '站点关闭后前台将不能访问', '1494408414', '1537178042', '1', '1', '1');
INSERT INTO `yzn_config` VALUES ('2', 'web_site_title', 'text', '站点标题', 'base', '', '', '1494408414', '1494408414', '1', 'YznCMS网站管理系统', '2');
INSERT INTO `yzn_config` VALUES ('3', 'web_site_keywords', 'text', '站点关键词', 'base', '', '', '1494408414', '1494408414', '1', 'YznCMS,网站管理系统', '3');
INSERT INTO `yzn_config` VALUES ('4', 'web_site_description', 'text', '站点描述', 'base', '', '', '1494408414', '1494408414', '1', '', '4');
INSERT INTO `yzn_config` VALUES ('5', 'web_site_logo', 'image', '站点LOGO', 'base', '', '', '1494408414', '1494408414', '1', null, '5');
INSERT INTO `yzn_config` VALUES ('6', 'web_site_icp', 'text', '备案信息', 'base', '', '', '1494408414', '1494408414', '1', '', '6');
INSERT INTO `yzn_config` VALUES ('7', 'web_site_statistics', 'textarea', '站点代码', 'base', '', '', '1494408414', '1494408414', '1', '', '7');
INSERT INTO `yzn_config` VALUES ('8', 'config_group', 'array', '配置分组', 'system', '', '', '1494408414', '1494408414', '1', 'base:基础\nsystem:系统\nupload:上传\ndevelop:开发', '0');
INSERT INTO `yzn_config` VALUES ('9', 'ueditor', 'Ueditor', '第三方代码', 'base', '', '', '1538212563', '1538212563', '1', '&lt;p&gt;1111111&lt;/p&gt;', '100');

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
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COMMENT='后台菜单表';

-- ----------------------------
-- Records of yzn_menu
-- ----------------------------
INSERT INTO `yzn_menu` VALUES ('3', '设置', 'icon-shezhi', '0', 'admin', 'setting', 'index', '', '1', '', '0', '2');
INSERT INTO `yzn_menu` VALUES ('4', '模块', '', '0', 'admin', 'module', 'index', '', '0', '', '0', '3');
INSERT INTO `yzn_menu` VALUES ('5', '扩展', 'icon-yingyong', '0', 'admin', 'addons', 'index', '', '1', '', '0', '4');
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
