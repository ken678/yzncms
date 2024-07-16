/*
 YznCMS Install SQL
 Date: 2024-02-05 09:17:57
*/

SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for `yzn_admin`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_admin`;
CREATE TABLE `yzn_admin` (
  `id` smallint(3) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` varchar(20) DEFAULT NULL COMMENT '管理账号',
  `password` varchar(32) DEFAULT NULL COMMENT '管理密码',
  `roleid` tinyint(4) unsigned DEFAULT '0' COMMENT '规则ID',
  `encrypt` varchar(6) DEFAULT NULL COMMENT '加密因子',
  `nickname` varchar(16) NOT NULL COMMENT '昵称',
  `last_login_time` int(10) unsigned DEFAULT NULL COMMENT '最后登录时间',
  `login_failure` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '失败次数',
  `create_time` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
  `update_time` int(10) unsigned DEFAULT NULL COMMENT '更新时间',
  `last_login_ip` varchar(50) NOT NULL DEFAULT '' COMMENT '最后登录IP',
  `email` varchar(40) DEFAULT NULL COMMENT '电子邮箱',
  `mobile` varchar(11) DEFAULT '' COMMENT '手机号码',
  `token` varchar(60) NOT NULL DEFAULT '' COMMENT 'Session标识',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='管理员表';

-- ----------------------------
-- Records of yzn_admin
-- ----------------------------
INSERT INTO `yzn_admin` VALUES (1, 'admin', 'ed2c3e92d29cafe07867876e189172c4', 1, 'Wo0bAa', 'Admin', 1546940765,0,1546940765,1546940765, '127.0.0.1', 'admin@admin.com', '', '',1);

-- ----------------------------
-- Table structure for `yzn_adminlog`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_adminlog`;
CREATE TABLE `yzn_adminlog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '管理员ID',
  `username` varchar(30) DEFAULT '' COMMENT '管理员名字',
  `url` varchar(1500) DEFAULT '' COMMENT '操作页面',
  `title` varchar(100) DEFAULT '' COMMENT '日志标题',
  `content` longtext NOT NULL COMMENT '内容',
  `ip` varchar(50) DEFAULT '' COMMENT 'IP',
  `useragent` varchar(255) DEFAULT '' COMMENT 'User-Agent',
  `create_time` int(10) unsigned DEFAULT NULL COMMENT '操作时间',
  PRIMARY KEY (`id`),
  KEY `name` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='操作日志';

-- ----------------------------
-- Table structure for `yzn_attachment`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_attachment`;
CREATE TABLE `yzn_attachment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(50) DEFAULT '' COMMENT '类别',
  `admin_id` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '管理员id',
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '文件名',
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '文件路径',
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '缩略图路径',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '文件链接',
  `imagewidth` varchar(30) DEFAULT '' COMMENT '宽度',
  `imageheight` varchar(30) DEFAULT '' COMMENT '高度',
  `mime` varchar(100) NOT NULL DEFAULT '' COMMENT '文件mime类型',
  `ext` varchar(4) NOT NULL DEFAULT '' COMMENT '文件类型',
  `size` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `md5` varchar(32) NOT NULL DEFAULT '' COMMENT '文件md5',
  `sha1` varchar(40) NOT NULL DEFAULT '' COMMENT 'sha1 散列值',
  `driver` varchar(16) NOT NULL DEFAULT 'local' COMMENT '上传驱动',
  `create_time` int(10) unsigned DEFAULT NULL COMMENT '上传时间',
  `update_time` int(10) unsigned DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='附件表';

-- ----------------------------
-- Table structure for `yzn_auth_group`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_auth_group`;
CREATE TABLE `yzn_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户组id,自增主键',
  `parentid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '父组别',
  `title` varchar(20) NOT NULL DEFAULT '' COMMENT '用户组中文名称',
  `description` varchar(80) NOT NULL DEFAULT '' COMMENT '描述信息',
  `rules` text NOT NULL COMMENT '用户组拥有的规则id，多个规则 , 隔开',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='权限组表';

-- ----------------------------
-- Records of yzn_auth_group
-- ----------------------------
INSERT INTO `yzn_auth_group` VALUES (1, 0, '超级管理员', '拥有所有权限', '*', 1);
INSERT INTO `yzn_auth_group` VALUES (2, 1, '编辑', '编辑', '', 1);

-- ----------------------------
-- Table structure for yzn_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `yzn_auth_rule`;
CREATE TABLE `yzn_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '规则id,自增主键',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `name` varchar(100) DEFAULT '' COMMENT '规则名称',
  `title` varchar(50) DEFAULT '' COMMENT '规则名称',
  `icon` varchar(50) DEFAULT '' COMMENT '图标',
  `url` varchar(255) DEFAULT '' COMMENT '规则URL',
  `condition` varchar(255) DEFAULT '' COMMENT '条件',
  `remark` varchar(255) DEFAULT '' COMMENT '备注',
  `ismenu` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否为菜单',
  `menutype` enum('_iframe','_blank','_layer') DEFAULT NULL COMMENT '菜单类型',
  `extend` varchar(255) DEFAULT '' COMMENT '扩展属性',
  `create_time` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
  `update_time` int(10) unsigned DEFAULT NULL COMMENT '更新时间',
  `listorder` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`) USING BTREE,
  KEY `parentid` (`parentid`),
  KEY `listorder` (`listorder`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='节点表';

-- ----------------------------
-- Records of yzn_auth_rule
-- ----------------------------
INSERT INTO `yzn_auth_rule` VALUES (1, 0, 'general', '常规管理', 'iconfont icon-settings-4-line', '', '', '', 1, NULL, '', 1691377129, 1691377129, 999, 1);
INSERT INTO `yzn_auth_rule` VALUES (2, 0, 'addon', '插件管理', 'iconfont icon-equalizer-line', '', '', '', 1, NULL, '', 1691377129, 1691377129, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (4, 1, 'general.profile', '个人资料', 'iconfont icon-user-line', '', '', '', 1, NULL, '', 1691377129, 1691377129, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (5, 4, 'general.profile/index', '查看', '', '', '', '', 0, NULL, '', 1691377129, 1691377129, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (6, 4, 'general.profile/update', '资料更新', '', '', '', '', 0, NULL, '', 1691377129, 1691377129, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (10, 1, 'general.config', '配置管理', 'iconfont icon-list-settings-line', '', '', '', 1, NULL, '', 1691377129, 1691377129, 999, 1);
INSERT INTO `yzn_auth_rule` VALUES (11, 1, 'general.config/setting', '网站设置', 'iconfont icon-settings-4-line', '', '', '', 1, NULL, '', 1691377129, 1691377129, 888, 1);
INSERT INTO `yzn_auth_rule` VALUES (12, 28, 'auth.rule', '菜单管理', 'iconfont icon-list-unordered', '', '', '', 1, NULL, '', 1691377129, 1691377129, 666, 1);
INSERT INTO `yzn_auth_rule` VALUES (13, 1, 'general.attachments', '附件管理', 'iconfont icon-attachment-line', '', '', '', 1, NULL, '', 1691377129, 1691377129, 666, 1);
INSERT INTO `yzn_auth_rule` VALUES (14, 13, 'general.attachments/index', '查看', '', '', '', '', 0, NULL, '', 1691377129, 1691377129, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (15, 13, 'general.attachments/del', '删除', '', '', '', '', 0, NULL, '', 1691377129, 1691377129, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (16, 13, 'general.attachments/getUrlFile', '图片本地化', '', '', '', '', 0, NULL, '', 1691377129, 1691377129, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (17, 13, 'general.attachments/select', '图片选择', '', '', '', '', 0, NULL, '', 1691377129, 1691377129, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (18, 12, 'auth.rule/index', '查看', '', '', '', '', 0, NULL, '', 1691377129, 1691377129, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (19, 12, 'auth.rule/add', '新增', '', '', '', '', 0, NULL, '', 1691377129, 1691377129, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (20, 12, 'auth.rule/edit', '编辑', '', '', '', '', 0, NULL, '', 1691377129, 1691377129, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (21, 12, 'auth.rule/del', '删除', '', '', '', '', 0, NULL, '', 1691377129, 1691377129, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (22, 12, 'auth.rule/multi', '批量更新', '', '', '', '', 0, NULL, '', 1691377129, 1691377129, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (23, 10, 'general.config/add', '新增', '', '', '', '', 0, NULL, '', 1691377129, 1691377129, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (24, 10, 'general.config/edit', '编辑', '', '', '', '', 0, NULL, '', 1691377129, 1691377129, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (25, 10, 'general.config/del', '删除', '', '', '', '', 0, NULL, '', 1691377129, 1691377129, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (26, 10, 'general.config/multi', '批量更新', '', '', '', '', 0, NULL, '', 1691377129, 1691377129, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (27, 10, 'general.config/index', '查看', '', '', '', '', 0, NULL, '', 1691377129, 1691377129, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (28, 0, 'auth', '权限管理', 'iconfont icon-user-settings-line', '', '', '', 1, NULL, '', 1691377129, 1691377129, 888, 1);
INSERT INTO `yzn_auth_rule` VALUES (29, 28, 'auth.manager', '管理员管理', 'iconfont icon-user-settings-line', '', '', '', 1, NULL, '', 1691377129, 1691377129, 999, 1);
INSERT INTO `yzn_auth_rule` VALUES (30, 29, 'auth.manager/index', '查看', '', '', '', '', 0, NULL, '', 1691377129, 1691377129, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (31, 29, 'auth.manager/edit', '编辑', '', '', '', '', 0, NULL, '', 1691377129, 1691377129, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (32, 29, 'auth.manager/del', '删除', '', '', '', '', 0, NULL, '', 1691377129, 1691377129, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (33, 29, 'auth.manager/add', '新增', '', '', '', '', 0, NULL, '', 1691377129, 1691377129, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (34, 28, 'auth.adminlog', '管理日志', 'iconfont icon-history', '', '', '', 1, NULL, '', 1691377129, 1691377129, 888, 1);
INSERT INTO `yzn_auth_rule` VALUES (35, 34, 'auth.adminlog/index', '查看', '', '', '', '', 0, NULL, '', 1691377129, 1691377129, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (36, 34, 'auth.adminlog/deletelog', '删除', '', '', '', '', 0, NULL, '', 1691377129, 1691377129, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (37, 34, 'auth.adminlog/detail', '详情', '', '', '', '', 0, NULL, '', 1691377129, 1691377129, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (38, 28, 'auth.group', '角色管理', 'iconfont icon-user-shared-2-line', '', '', '', 1, NULL, '', 1691377129, 1691377129, 777, 1);
INSERT INTO `yzn_auth_rule` VALUES (41, 38, 'auth.group/index', '查看', '', '', '', '', 0, NULL, '', 1691377129, 1691377129, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (42, 38, 'auth.group/add', '新增', '', '', '', '', 0, NULL, '', 1691377129, 1691377129, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (43, 38, 'auth.group/edit', '编辑', '', '', '', '', 0, NULL, '', 1691377129, 1691377129, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (44, 38, 'auth.group/del', '删除', '', '', '', '', 0, NULL, '', 1691377129, 1691377129, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (45, 2, 'addon/index', '查看', '', '', '', '', 0, NULL, '', 1691377129, 1691377129, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (46, 2, 'addon/config', '配置', '', '', '', '', 0, NULL, '', 1691377129, 1691377129, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (49, 2, 'addon/state', '禁用启用', '', '', '', '', 0, NULL, '', 1691377129, 1691377129, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (50, 0, 'user', '会员管理', 'iconfont icon-user-line', '', '', '', 1, NULL, '', 1714295576, 1714295576, 777, 1);
INSERT INTO `yzn_auth_rule` VALUES (51, 50, 'user.user', '会员管理', 'iconfont icon-user-line', '', '', '', 1, NULL, '', 1714295576, 1714295576, 99, 1);
INSERT INTO `yzn_auth_rule` VALUES (52, 51, 'user.user/index', '查看', '', '', '', '', 0, NULL, '', 1714295576, 1714295576, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (53, 51, 'user.user/add', '新增', '', '', '', '', 0, NULL, '', 1714295577, 1714295577, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (54, 51, 'user.user/edit', '编辑', '', '', '', '', 0, NULL, '', 1714295577, 1714295577, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (55, 51, 'user.user/del', '删除', '', '', '', '', 0, NULL, '', 1714295577, 1714295577, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (56, 51, 'user.user/pass', '审核', '', '', '', '', 0, NULL, '', 1714295577, 1714295577, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (57, 50, 'user.user/userverify', '审核会员', 'iconfont icon-user-star-line', '', '', '', 1, NULL, '', 1714295577, 1714295577, 98, 1);
INSERT INTO `yzn_auth_rule` VALUES (58, 50, 'user.group', '会员组管理', 'iconfont icon-user-settings-line', '', '', '', 1, NULL, '', 1714295577, 1714295577, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (59, 58, 'user.group/index', '查看', '', '', '', '', 0, NULL, '', 1714295577, 1714295577, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (60, 58, 'user.group/add', '新增', '', '', '', '', 0, NULL, '', 1714295577, 1714295577, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (61, 58, 'user.group/edit', '编辑', '', '', '', '', 0, NULL, '', 1714295577, 1714295577, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (62, 58, 'user.group/del', '删除', '', '', '', '', 0, NULL, '', 1714295577, 1714295577, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (63, 50, 'user.vip', 'VIP等级管理', 'iconfont icon-vip-line', '', '', '', 1, NULL, '', 1714295577, 1714295577, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (64, 63, 'user.vip/index', '查看', '', '', '', '', 0, NULL, '', 1714295577, 1714295577, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (65, 63, 'user.vip/add', '新增', '', '', '', '', 0, NULL, '', 1714295577, 1714295577, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (66, 63, 'user.vip/edit', '编辑', '', '', '', '', 0, NULL, '', 1714295577, 1714295577, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (67, 63, 'user.vip/del', '删除', '', '', '', '', 0, NULL, '', 1714295577, 1714295577, 0, 1);
INSERT INTO `yzn_auth_rule` VALUES (68, 63, 'user.vip/multi', '批量更新', '', '', '', '', 0, NULL, '', 1714295577, 1714295577, 0, 1);


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
  `create_time` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
  `update_time` int(10) unsigned DEFAULT NULL COMMENT '更新时间',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  `value` text NULL COMMENT '配置值',
  `visible` varchar(255) DEFAULT '' COMMENT '可见条件',
  `extend` varchar(255) DEFAULT '' COMMENT '扩展属性',
  `listorder` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`),
  KEY `type` (`type`),
  KEY `group` (`group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='网站配置';

-- ----------------------------
-- Records of yzn_config
-- ----------------------------
INSERT INTO `yzn_config` VALUES (1, 'name', 'text', '站点名称', 'base', '', '', 1551244923, 1551244971, 1, '我的网站', '', '',100);
INSERT INTO `yzn_config` VALUES (2, 'web_site_icp', 'text', '备案信息', 'base', '', '', 1551244923, 1551244971, 1, '', '', '',10);
INSERT INTO `yzn_config` VALUES (3, 'version', 'text', '版本', 'base', '', '如果静态资源有变动请重新配置该值', 1552435299, 1552436083, 1,'1.0.1', '', '', 9);
INSERT INTO `yzn_config` VALUES (4, 'cdnurl', 'text', '静态资源CDN',  'base', '','如果全站静态资源使用第三方云储存请配置该值', 1552435299, 1552436083, 1, '', '', '', 8);
INSERT INTO `yzn_config` VALUES (5, 'web_site_statistics', 'textarea', '站点代码', 'base', '', '', 1551244957, 1551244957, 1, '','', '', 5);
INSERT INTO `yzn_config` VALUES (6, 'config_group', 'array', '配置分组', 'system', '', '', 1494408414, 1494408414, 1, '{\"base\":\"基础\",\"system\":\"系统\",\"develop\":\"开发\"}','', '', 6);
INSERT INTO `yzn_config` VALUES (7, 'theme', 'text', '主题风格', 'system', '', '', 1541752781, 1541756888, 1, 'default', '', '',1);
INSERT INTO `yzn_config` VALUES (8, 'admin_forbid_ip', 'textarea', '后台禁止访问IP', 'system', '', '匹配IP段用\"*\"占位，如192.168.*.*，多个IP地址请用英文逗号\",\"分割', 1551244957, 1551244957, 1, '', '', '',2);
INSERT INTO `yzn_config` VALUES (9, 'upload_driver', 'radio', '上传驱动', 'system', 'local:本地', '图片或文件上传驱动', 1541752781, 1552436085, 1, 'local', '', '',0);
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
  PRIMARY KEY (`name`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='字段类型表';

-- ----------------------------
-- Records of yzn_field_type
-- ----------------------------
INSERT INTO `yzn_field_type` VALUES ('text', '输入框', 1, 'varchar(255) NOT NULL', 0, 1);
INSERT INTO `yzn_field_type` VALUES ('checkbox', '复选框', 2, 'varchar(32) NOT NULL', 1, 0);
INSERT INTO `yzn_field_type` VALUES ('textarea', '多行文本', 3, 'varchar(255) NOT NULL', 0, 1);
INSERT INTO `yzn_field_type` VALUES ('password', '密码', 4, 'varchar(255) NOT NULL', 0, 1);
INSERT INTO `yzn_field_type` VALUES ('radio', '单选按钮', 5, 'char(10) NOT NULL', 1, 0);
INSERT INTO `yzn_field_type` VALUES ('switch', '开关', 6, 'tinyint(2) UNSIGNED NOT NULL', 0, 0);
INSERT INTO `yzn_field_type` VALUES ('array', '数组', 7, 'varchar(512) NOT NULL', 0, 0);
INSERT INTO `yzn_field_type` VALUES ('select', '下拉框', 8, 'char(10) NOT NULL', 1, 0);
INSERT INTO `yzn_field_type` VALUES ('selects', '下拉框(多选)', 9, 'varchar(32) NOT NULL', 1, 0);
INSERT INTO `yzn_field_type` VALUES ('selectpage', '高级下拉框', 10, 'varchar(32) NOT NULL', 1, 0);
INSERT INTO `yzn_field_type` VALUES ('image', '单张图', 11, 'varchar(255) NOT NULL', 0, 0);
INSERT INTO `yzn_field_type` VALUES ('images', '多张图', 12, 'text NOT NULL', 0, 0);
INSERT INTO `yzn_field_type` VALUES ('tags', '标签', 13, 'varchar(255) NOT NULL', 0, 1);
INSERT INTO `yzn_field_type` VALUES ('number', '数字', 14, 'int(10) UNSIGNED NOT NULL', 0, 0);
INSERT INTO `yzn_field_type` VALUES ('datetime', '日期和时间', 15, 'int(10) UNSIGNED NOT NULL', 0, 0);
INSERT INTO `yzn_field_type` VALUES ('datetimerange', '日期和时间区间', 16, 'varchar(100) NOT NULL', 0, 0);
INSERT INTO `yzn_field_type` VALUES ('editor', '编辑器', 17, 'mediumtext NOT NULL', 0, 1);
INSERT INTO `yzn_field_type` VALUES ('markdown', 'markdown编辑器', 18, 'mediumtext NOT NULL', 0, 1);
INSERT INTO `yzn_field_type` VALUES ('files', '多文件', 19, 'text NOT NULL', 0, 0);
INSERT INTO `yzn_field_type` VALUES ('file', '单文件', 20, 'varchar(255) NOT NULL', 0, 0);
INSERT INTO `yzn_field_type` VALUES ('color', '颜色值', 21, 'varchar(7) NOT NULL', 0, 0);
INSERT INTO `yzn_field_type` VALUES ('city', '城市地区', 22, 'varchar(255) NOT NULL', 0, 0);
INSERT INTO `yzn_field_type` VALUES ('custom', '自定义', 23, 'text NOT NULL', 1, 0);

-- ----------------------------
-- Table structure for `yzn_model`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_model`;
CREATE TABLE `yzn_model` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `module` varchar(15) NOT NULL  DEFAULT '' COMMENT '所属模块',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '模型名称',
  `tablename` varchar(20) NOT NULL DEFAULT '' COMMENT '表名',
  `description` varchar(100) NOT NULL DEFAULT '' COMMENT '描述',
  `setting` text NOT NULL COMMENT '配置信息',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '模型类别：1-独立表，2-主附表',
  `create_time` int(10) unsigned DEFAULT NULL COMMENT '添加时间',
  `update_time` int(10) unsigned DEFAULT NULL COMMENT '更新时间',
  `listorder` tinyint(3) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='模型列表';

-- ----------------------------
-- Table structure for `yzn_model_field`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_model_field`;
CREATE TABLE `yzn_model_field` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `modelid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '模型ID',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '字段名',
  `title` varchar(30) NOT NULL DEFAULT '' COMMENT '别名',
  `remark` tinytext NULL COMMENT '字段提示',
  `pattern` varchar(255) NOT NULL DEFAULT '' COMMENT '数据校验正则',
  `errortips` varchar(255) NOT NULL DEFAULT '' COMMENT '数据校验未通过的提示信息',
  `type` varchar(20) NOT NULL DEFAULT '' COMMENT '字段类型',
  `extend` varchar(255) DEFAULT '' COMMENT '扩展信息',
  `setting` mediumtext NULL COMMENT '字段配置',
  `ifsystem` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否主表字段 1 是',
  `iscore` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否内部字段',
  `iffixed` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否固定不可修改',
  `ifrequire` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否必填',
  `ifsearch` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '作为搜索条件',
  `isadd` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '在投稿中显示',
  `create_time` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
  `update_time` int(10) unsigned DEFAULT NULL COMMENT '更新时间',
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `name` (`name`,`modelid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='模型字段列表';

-- ----------------------------
-- Table structure for `yzn_terms`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_terms`;
CREATE TABLE `yzn_terms` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `parentid` smallint(5) NOT NULL DEFAULT '0' COMMENT '父ID',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '分类名称',
  `module` varchar(15) NOT NULL DEFAULT '' COMMENT '所属模块',
  `setting` mediumtext NULL COMMENT '相关配置信息',
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `module` (`module`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='分类表';

-- ----------------------------
-- Table structure for `yzn_sms`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_sms`;
CREATE TABLE `yzn_sms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `event` varchar(30) NOT NULL DEFAULT '' COMMENT '事件',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '手机号',
  `code` varchar(10) NOT NULL DEFAULT '' COMMENT '验证码',
  `times` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '验证次数',
  `ip` varchar(50) NOT NULL DEFAULT '' COMMENT '操作IP',
  `create_time` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='短信验证码表';

-- ----------------------------
-- Table structure for `yzn_ems`
-- ----------------------------
DROP TABLE IF EXISTS `yzn_ems`;
CREATE TABLE `yzn_ems` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `event` varchar(30) NOT NULL DEFAULT '' COMMENT '事件',
  `email` varchar(100) NOT NULL DEFAULT '' COMMENT '邮箱',
  `code` varchar(10) NOT NULL DEFAULT '' COMMENT '验证码',
  `times` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '验证次数',
  `ip` varchar(50) NOT NULL DEFAULT '' COMMENT '操作IP',
  `create_time` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='邮箱验证码表';

-- ----------------------------
-- Table structure for yzn_user
-- ----------------------------
DROP TABLE IF EXISTS `yzn_user`;
CREATE TABLE `yzn_user`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '用户名',
  `nickname` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '昵称',
  `password` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '密码',
  `encrypt` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '加密因子',
  `point` mediumint(8) NOT NULL DEFAULT 0 COMMENT '用户积分',
  `amount` decimal(8, 2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT '钱金总额',
  `login` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '登录次数',
  `email` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '电子邮箱',
  `mobile` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '手机号',
  `gender` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '性别:0=未知,1=男,2=女',
  `birthday` date NULL DEFAULT NULL COMMENT '生日',
  `motto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '签名',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '头像',
  `group_id` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户组ID',
  `model_id` smallint(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户模型ID',
  `vip` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'VIP会员等级 0为非会员',
  `overduedate` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '过期时间',
  `reg_ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '注册IP',
  `reg_time` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '注册时间',
  `last_login_ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '最后登录IP',
  `last_login_time` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '最后登录时间',
  `ischeck_email` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否验证过邮箱',
  `ischeck_mobile` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否验证过手机',
  `token` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT 'Token',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '状态',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `username`(`username`) USING BTREE,
  INDEX `email`(`email`) USING BTREE,
  INDEX `mobile`(`mobile`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '会员表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of yzn_user
-- ----------------------------
INSERT INTO `yzn_user` VALUES (1, 'admin', 'admin', '', '', 0, 0.00, 1, 'admin@admin.com', '13400000000', 0, NULL, '', '', 2, 0, 0, NULL, '127.0.0.1', 1717844631, '127.0.0.1', 1717844644, 0, 0, '', 1);

-- ----------------------------
-- Table structure for yzn_user_amount_log
-- ----------------------------
DROP TABLE IF EXISTS `yzn_user_amount_log`;
CREATE TABLE `yzn_user_amount_log`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员ID',
  `amount` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '变更余额',
  `before` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '变更前余额',
  `after` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '变更后余额',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '备注',
  `create_time` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '会员余额变动表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for yzn_user_group
-- ----------------------------
DROP TABLE IF EXISTS `yzn_user_group`;
CREATE TABLE `yzn_user_group`  (
  `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '会员组id',
  `name` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '用户组名称',
  `issystem` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否是系统组',
  `starnum` tinyint(2) UNSIGNED NOT NULL COMMENT '会员组星星数',
  `point` smallint(6) UNSIGNED NOT NULL COMMENT '积分范围',
  `allowmessage` smallint(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT '许允发短消息数量',
  `allowvisit` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否允许访问',
  `allowpost` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否允许发稿',
  `allowpostverify` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否投稿不需审核',
  `allowsearch` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否允许搜索',
  `allowsendmessage` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '允许发送短消息',
  `allowpostnum` smallint(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT '每天允许发文章数',
  `allowattachment` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否允许上传附件',
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户组图标',
  `usernamecolor` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户名颜色',
  `description` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `listorder` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '状态',
  `expand` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '拓展',
  `create_time` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '会员组' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of yzn_user_group
-- ----------------------------
INSERT INTO `yzn_user_group` VALUES (1, '禁止访问', 1, 0, 0, 0, 1, 1, 0, 1, 0, 0, 0, '', '', '0', 10, 1, '', 1713774138);
INSERT INTO `yzn_user_group` VALUES (2, '新手上路', 1, 1, 50, 100, 1, 1, 0, 0, 1, 0, 0, '', '', '', 8, 1, '', 1713774138);
INSERT INTO `yzn_user_group` VALUES (4, '中级会员', 1, 3, 150, 500, 1, 1, 0, 1, 1, 0, 0, '', '', '', 6, 1, '', 1713774138);
INSERT INTO `yzn_user_group` VALUES (5, '高级会员', 1, 5, 300, 999, 1, 1, 0, 1, 1, 0, 0, '', '', '', 5, 1, '', 1713774138);
INSERT INTO `yzn_user_group` VALUES (6, '注册会员', 1, 2, 100, 150, 0, 1, 0, 0, 1, 0, 0, '', '', '', 7, 1, '', 1713774138);
INSERT INTO `yzn_user_group` VALUES (7, '邮件认证', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '#000000', '', 4, 1, '', 1713774138);
INSERT INTO `yzn_user_group` VALUES (8, '游客', 1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, '', '', '', 9, 1, '', 1713774138);

-- ----------------------------
-- Table structure for yzn_user_point_log
-- ----------------------------
DROP TABLE IF EXISTS `yzn_user_point_log`;
CREATE TABLE `yzn_user_point_log`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员ID',
  `point` int(10) NOT NULL DEFAULT 0 COMMENT '变更积分',
  `before` int(10) NOT NULL DEFAULT 0 COMMENT '变更前积分',
  `after` int(10) NOT NULL DEFAULT 0 COMMENT '变更后积分',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '备注',
  `create_time` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '会员积分变动表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of yzn_user_point_log
-- ----------------------------

-- ----------------------------
-- Table structure for yzn_user_token
-- ----------------------------
DROP TABLE IF EXISTS `yzn_user_token`;
CREATE TABLE `yzn_user_token`  (
  `token` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Token',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员ID',
  `create_time` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '创建时间',
  `expire_time` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '过期时间',
  PRIMARY KEY (`token`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '会员Token表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for yzn_user_vip
-- ----------------------------
DROP TABLE IF EXISTS `yzn_user_vip`;
CREATE TABLE `yzn_user_vip`  (
  `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '会员组id',
  `level` tinyint(1) UNSIGNED NULL DEFAULT 0 COMMENT 'vip等级',
  `title` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'VIP名称',
  `days` smallint(6) UNSIGNED NOT NULL COMMENT '天数',
  `amount` decimal(8, 2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT '价格',
  `equity` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '权益',
  `listorder` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  `create_time` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '创建时间',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '状态',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = 'VIP组' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of yzn_user_vip
-- ----------------------------
INSERT INTO `yzn_user_vip` VALUES (1, 1, '体验会员', 1, 1.00, '[{\"key\":\"VIP标识，彰显尊贵身份\",\"high\":\"2\"},{\"key\":\"海量资源，有效期内随心下载\",\"high\":\"1\"},{\"key\":\"无人工客服\",\"high\":\"2\"}]', 9, 1713754455, 1);
INSERT INTO `yzn_user_vip` VALUES (2, 2, '包月会员', 30, 20.00, '[{\"key\":\"VIP标识，彰显尊贵身份\",\"high\":\"2\"},{\"key\":\"海量资源，有效期内随心下载\",\"high\":\"1\"},{\"key\":\"5×8小时在线人工客服\",\"high\":\"2\"}]', 8, 1713755519, 1);
INSERT INTO `yzn_user_vip` VALUES (3, 3, '年卡会员', 365, 200.00, '[{\"key\":\"VIP标识，彰显尊贵身份\",\"high\":\"2\"},{\"key\":\"海量资源，有效期内随心下载\",\"high\":\"1\"},{\"key\":\"5×12小时在线人工客服\",\"high\":\"2\"}]', 7, 1713755820, 1);


SET FOREIGN_KEY_CHECKS = 1;