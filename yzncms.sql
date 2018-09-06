/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : yzncms

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2018-09-06 11:51:56
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `uk_attachment`
-- ----------------------------
DROP TABLE IF EXISTS `uk_attachment`;
CREATE TABLE `uk_attachment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '文件名',
  `module` varchar(32) NOT NULL DEFAULT '' COMMENT '模块名，由哪个模块上传的',
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '文件路径',
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '缩略图路径',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '文件链接',
  `mime` varchar(64) NOT NULL DEFAULT '' COMMENT '文件mime类型',
  `ext` char(4) NOT NULL DEFAULT '' COMMENT '文件类型',
  `size` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `md5` char(32) NOT NULL DEFAULT '' COMMENT '文件md5',
  `sha1` char(40) NOT NULL DEFAULT '' COMMENT 'sha1 散列值',
  `driver` varchar(16) NOT NULL DEFAULT 'local' COMMENT '上传驱动',
  `download` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '下载次数',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上传时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `orders` int(5) NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=52 DEFAULT CHARSET=utf8 COMMENT='附件表';

-- ----------------------------
-- Records of uk_attachment
-- ----------------------------
INSERT INTO `uk_attachment` VALUES ('1', '1', 'demo1.jpg', 'admin', 'images/20170614/3301b085279f819c91906763f343273a.jpg', '', '', 'image/jpeg', 'jpg', '2170', '4bd3b079d4fd99d0bcfe4b51c441ce96', 'bca848acb3d828908b90d1e38a39dd570fd74a49', 'local', '0', '1497421718', '1497421718', '100', '1');
INSERT INTO `uk_attachment` VALUES ('2', '1', 'demo2.jpg', 'admin', 'images/20170715/1b18e5d089de85c470558331212b5a6b.png', '', '', 'image/jpeg', 'jpg', '6172', 'afb9f77547cb29396a96353ee6e27da3', '5b08b57a2d266443e9441cefb1b1039474fb0458', 'local', '0', '1500085487', '1500085487', '100', '1');
INSERT INTO `uk_attachment` VALUES ('3', '1', 'demo3.jpg', 'admin', 'images/20170715/6f51ffa8c3c7eacedef551e69616aeed.jpg', '', '', 'image/jpeg', 'jpg', '5388', '8909929348c8c297de64c5add9d05bac', '88f2f7eb982c3ea11fcc846092dcfce1636c63d6', 'local', '0', '1500101761', '1500101761', '100', '1');
INSERT INTO `uk_attachment` VALUES ('4', '1', 'demo4.png', 'admin', 'images/20170715/0e6bfb19c98b183142c9e53a3fa34487.png', '', '', 'image/png', 'png', '24363', '9f0458f97a240ec6e41de43d1ec11bc1', '5b31388a2447fb47fc5272474d04f16552300b1c', 'local', '0', '1500103834', '1500103834', '100', '1');
INSERT INTO `uk_attachment` VALUES ('5', '1', 'demo5.jpg', 'admin', 'images/20170715/bdb7aff8b8421ac471270ba5d84d7ad4.jpg', '', '', 'image/jpeg', 'jpg', '4397', '39f516b595fce54ff0a0be8e57ec12bc', '60e7bed775f10f0b27fe9776e79bd3994c52252d', 'local', '0', '1500104762', '1500104762', '100', '1');
INSERT INTO `uk_attachment` VALUES ('6', '1', 'demo6.jpg', 'admin', 'images/20170715/29011516a47f95ed7e8e50575aebdafc.jpg', '', '', 'image/jpeg', 'jpg', '6520', '4fce14c5dc68dd3e05ca939d988ed1a0', '292f643438a42fc8e804e33550ba8b5b8b1ad32c', 'local', '0', '1500106065', '1500106065', '100', '1');
INSERT INTO `uk_attachment` VALUES ('7', '1', 'demo7t.jpg', 'admin', 'images/20170715/d2a30e8107f5137ca99c7a52b1635421.jpg', '', '', 'image/jpeg', 'jpg', '4821', '6e825ae70a888145a20c9ef10aa61dfd', 'd45c8a5eeb03b56b7312e3e3b46689e7ef7f4967', 'local', '0', '1500106612', '1500106612', '100', '1');
INSERT INTO `uk_attachment` VALUES ('8', '1', 'demo8.JPG', 'admin', 'images/20170715/e672b350015bf23c1a86be88c194e8c3.JPG', '', '', 'image/jpeg', 'JPG', '7490', 'fe5b3ecc2997408bace8e9aa43b7e3b5', '854247f723f6bb6df11e043e4943de63cf421fc9', 'local', '0', '1500107464', '1500107464', '100', '1');
INSERT INTO `uk_attachment` VALUES ('9', '1', 'watermark.png', 'admin', 'images/20170716/85ab14f70024491b8d49a2192563e361.png', '', '', 'image/png', 'png', '2396', 'b4625444ee3c0554089c2ad625ebbcdc', 'a5eb0e9c97a4e36cedabca14775058a208b712f6', 'local', '0', '1500182195', '1500182195', '100', '1');
INSERT INTO `uk_attachment` VALUES ('10', '1', 'g1.jpg', 'admin', 'images/20170716/383e22b40d82c9efec6af8a6d948a896.jpg', 'images/20170716/thumb/383e22b40d82c9efec6af8a6d948a896.jpg', '', 'image/jpeg', 'jpg', '37297', '997457cac9a441e80ddfafecd9ae19a2', '5c704965df9c66628a0e07758c2ff923958c2128', 'local', '0', '1500182291', '1500182291', '100', '1');
INSERT INTO `uk_attachment` VALUES ('11', '1', 'g3.jpg', 'admin', 'images/20170716/2467d58feed579f18854119c268365d1.jpg', 'images/20170716/thumb/2467d58feed579f18854119c268365d1.jpg', '', 'image/jpeg', 'jpg', '88276', '4c63654b390e8b6101709351f7ff86a9', '539208f8d1441b3b6c656684ef18c10c7b6f26b7', 'local', '0', '1500182292', '1500182292', '100', '1');
INSERT INTO `uk_attachment` VALUES ('12', '1', 'g4.jpg', 'admin', 'images/20170716/af5110625ee67f1d4f37da1dc33f2a23.jpg', 'images/20170716/thumb/af5110625ee67f1d4f37da1dc33f2a23.jpg', '', 'image/jpeg', 'jpg', '98397', '4855506c81c6e7ee1a4788168f10bb3b', '101631470631fa1d6995d76d3b6cb7627c85536b', 'local', '0', '1500182292', '1500182292', '100', '1');
INSERT INTO `uk_attachment` VALUES ('13', '1', 'g2.jpg', 'admin', 'images/20170716/4257f7a9c674c9efff24f278521370a4.jpg', 'images/20170716/thumb/4257f7a9c674c9efff24f278521370a4.jpg', '', 'image/jpeg', 'jpg', '188886', '4ce43ffeaa0356cff407ddb84de2f7e8', 'aa4a6d34ada598743b466f9b1b9daafbc462c188', 'local', '0', '1500182292', '1500182292', '100', '1');
INSERT INTO `uk_attachment` VALUES ('14', '1', 'g12.jpeg', 'admin', 'images/20170716/5db1dee06042595f1bf20cf8a78ba8be.jpeg', 'images/20170716/thumb/5db1dee06042595f1bf20cf8a78ba8be.jpeg', '', 'image/jpeg', 'jpeg', '151640', 'c90142a84a8a0f655143f65f13549767', '9b99b61487c6fd7bcbfb3c5c7088517f8e60be4f', 'local', '0', '1500187809', '1500187809', '100', '1');
INSERT INTO `uk_attachment` VALUES ('15', '1', 'g11.jpeg', 'admin', 'images/20170716/9772eb6068193c68163e82c1c04e1fd4.jpeg', 'images/20170716/thumb/9772eb6068193c68163e82c1c04e1fd4.jpeg', '', 'image/jpeg', 'jpeg', '182597', '00bc4c6a2f07965274e38d0a4765b0fc', '6c5f05d80ed5ea955bb4289c3ae940f67299333c', 'local', '0', '1500187809', '1500187809', '100', '1');
INSERT INTO `uk_attachment` VALUES ('16', '1', 'g13.jpg', 'admin', 'images/20170716/555a802803c4baa99e8d8d12c97a7cca.jpg', 'images/20170716/thumb/555a802803c4baa99e8d8d12c97a7cca.jpg', '', 'image/jpeg', 'jpg', '100688', 'ed105dc56b67480753c4305213ece9d4', '493f8459da3c188bdd790e67a32e35d23d7d2450', 'local', '0', '1500187809', '1500187809', '100', '1');
INSERT INTO `uk_attachment` VALUES ('17', '1', 'g14.jpeg', 'admin', 'images/20170716/a610754f8b6d5d24ea40ae463c932d44.jpeg', 'images/20170716/thumb/a610754f8b6d5d24ea40ae463c932d44.jpeg', '', 'image/jpeg', 'jpeg', '78001', 'f9ee59c0ed86ac0c9fe0c9c8ec0c96c1', '09d97f7602da854a2bd1e3abbfea0c938c173b09', 'local', '0', '1500187809', '1500187809', '100', '1');
INSERT INTO `uk_attachment` VALUES ('18', '1', 'g22.jpg', 'admin', 'images/20170716/c4e251b96f30762f9cd80359f2458e4f.jpg', 'images/20170716/thumb/c4e251b96f30762f9cd80359f2458e4f.jpg', '', 'image/jpeg', 'jpg', '95273', '1c32d3b71a0ce69ecdd618d3deaa6a3e', '99310f6b76e6679a8b2c17a1cceeaff8490f5939', 'local', '0', '1500188953', '1500188953', '100', '1');
INSERT INTO `uk_attachment` VALUES ('19', '1', 'g23.jpg', 'admin', 'images/20170716/d2b986b271d0622a3f96c5211053254b.jpg', 'images/20170716/thumb/d2b986b271d0622a3f96c5211053254b.jpg', '', 'image/jpeg', 'jpg', '154456', '7df7de7259e55310c5857f423ae78a29', 'bc04a012e872b67e969d30369897eaf054bc7e16', 'local', '0', '1500188954', '1500188954', '100', '1');
INSERT INTO `uk_attachment` VALUES ('20', '1', 'g24.jpeg', 'admin', 'images/20170716/e2cb292e32e91758f802a78003924194.jpeg', 'images/20170716/thumb/e2cb292e32e91758f802a78003924194.jpeg', '', 'image/jpeg', 'jpeg', '117863', 'd74744c492edd17fb374fd09f30d3ec1', '17d3c6e86e06beb06371cf07b1df5e05f0de34e1', 'local', '0', '1500188954', '1500188954', '100', '1');
INSERT INTO `uk_attachment` VALUES ('21', '1', 'g21.jpg', 'admin', 'images/20170716/9fa9ad6c22dee3dbef1a9806a64d3081.jpg', 'images/20170716/thumb/9fa9ad6c22dee3dbef1a9806a64d3081.jpg', '', 'image/jpeg', 'jpg', '109602', 'f06b9b1bb0aeddbc7903db26cd1f0159', 'a7b2b7fb296cb9862d5eb9c7605922f4201d4efb', 'local', '0', '1500188954', '1500188954', '100', '1');
INSERT INTO `uk_attachment` VALUES ('22', '1', 'm2.jpg', 'admin', 'images/20170716/8b256640cef0b5b46b4c400faf5adc51.jpg', 'images/20170716/thumb/8b256640cef0b5b46b4c400faf5adc51.jpg', '', 'image/jpeg', 'jpg', '86709', '956f86876872d51d5bcad50791202e9d', 'c866b893c03af75de227307b2fb4d1d657407e9c', 'local', '0', '1500190272', '1500190272', '100', '1');
INSERT INTO `uk_attachment` VALUES ('23', '1', 'm1.jpg', 'admin', 'images/20170716/d1077afda0fdec8f2b89a29a9ed40f98.jpg', 'images/20170716/thumb/d1077afda0fdec8f2b89a29a9ed40f98.jpg', '', 'image/jpeg', 'jpg', '91997', 'bba6c384d420afae0ee6cd60f7a422f3', '9f2d10a6d098c1e0e4a213b93d0d721e5be4946a', 'local', '0', '1500190272', '1500190272', '100', '1');
INSERT INTO `uk_attachment` VALUES ('24', '1', 'm3.jpg', 'admin', 'images/20170716/7de372105b92ffd05e0b790c4984283d.jpg', 'images/20170716/thumb/7de372105b92ffd05e0b790c4984283d.jpg', '', 'image/jpeg', 'jpg', '38435', '6b2537f45c671d68e6a5d957f0f7e925', '01640a3cf98268b7633cd848b7966133724fb0ef', 'local', '0', '1500190273', '1500190273', '100', '1');
INSERT INTO `uk_attachment` VALUES ('25', '1', 'm4.jpg', 'admin', 'images/20170716/2338da35a64f8c4e999ef79cae27b96e.jpg', 'images/20170716/thumb/2338da35a64f8c4e999ef79cae27b96e.jpg', '', 'image/jpeg', 'jpg', '75118', 'd0879f046db399d9b696fbc46767ff3e', '7ad0838f827cac02c920fd9a23fdef8bda7018f2', 'local', '0', '1500190273', '1500190273', '100', '1');
INSERT INTO `uk_attachment` VALUES ('26', '1', 'm11.jpg', 'admin', 'images/20170716/b64deae22e7e42050d91af2a246d83da.jpg', 'images/20170716/thumb/b64deae22e7e42050d91af2a246d83da.jpg', '', 'image/jpeg', 'jpg', '128565', 'ab74dd3b1909a19b143bca13e7ba23ee', 'cae92f88de4f0b3edf4e8db2bb60448425ec447f', 'local', '0', '1500191099', '1500191099', '100', '1');
INSERT INTO `uk_attachment` VALUES ('27', '1', 'm33.jpeg', 'admin', 'images/20170716/c600b04e424d49715cc28055a8469da6.jpeg', 'images/20170716/thumb/c600b04e424d49715cc28055a8469da6.jpeg', '', 'image/jpeg', 'jpeg', '115133', '9ee0ae16dc5d7b3774083a22fdd30c71', '022fa3c71879eb18acab05965c8ae2291bc0958a', 'local', '0', '1500191099', '1500191099', '100', '1');
INSERT INTO `uk_attachment` VALUES ('28', '1', 'm44.jpg', 'admin', 'images/20170716/ffefd6b7bae7d84c792d1752e4a13af5.jpg', 'images/20170716/thumb/ffefd6b7bae7d84c792d1752e4a13af5.jpg', '', 'image/jpeg', 'jpg', '119906', '7a559e68d16e255e64143910d3f5e13e', '418ee49a9fbecc2af15ad7cc80bc21e6a1067ee7', 'local', '0', '1500191099', '1500191099', '100', '1');
INSERT INTO `uk_attachment` VALUES ('29', '1', 'm22.jpg', 'admin', 'images/20170716/62ba52ceb37a1c4f1544c99c42b38bd4.jpg', 'images/20170716/thumb/62ba52ceb37a1c4f1544c99c42b38bd4.jpg', '', 'image/jpeg', 'jpg', '96091', 'd69068ae28f37983e0559362cf0b857b', '22523727f533e256acbabdb34fee6121b6384a5d', 'local', '0', '1500191100', '1500191100', '100', '1');
INSERT INTO `uk_attachment` VALUES ('30', '1', 'mm.jpeg', 'admin', 'images/20170716/cd19ad73e71aaf5d80e8080cb058fb92.jpeg', 'images/20170716/thumb/cd19ad73e71aaf5d80e8080cb058fb92.jpeg', '', 'image/jpeg', 'jpeg', '92243', '1a56e536e2ef1380375b11da4b5c8566', '130a3a80d2b550dcb2dee633191b21cec54ff923', 'local', '0', '1500191748', '1500191748', '100', '1');
INSERT INTO `uk_attachment` VALUES ('31', '1', 'mm2.jpeg', 'admin', 'images/20170716/a8fc65126ed9e8d4e882cd10cc13913f.jpeg', 'images/20170716/thumb/a8fc65126ed9e8d4e882cd10cc13913f.jpeg', '', 'image/jpeg', 'jpeg', '106100', 'ac27cda0db960026ea0c63d71fcdba43', '58ab01a5c4c753d0a46fd96f0823c6a959dfd237', 'local', '0', '1500191748', '1500191748', '100', '1');
INSERT INTO `uk_attachment` VALUES ('32', '1', 'mm3.jpeg', 'admin', 'images/20170716/799ea8dd132cfe68d66fcb1627029b62.jpeg', 'images/20170716/thumb/799ea8dd132cfe68d66fcb1627029b62.jpeg', '', 'image/jpeg', 'jpeg', '131670', '2d7de305736d018a0daa0a7ce47812d4', 'dbb22bebb1b4ec6da2404c5dfe4837f3895b0da0', 'local', '0', '1500191748', '1500191748', '100', '1');
INSERT INTO `uk_attachment` VALUES ('33', '1', 'mm1.jpeg', 'admin', 'images/20170716/76760e64b7ec481d1a5e29433c94ca79.jpeg', 'images/20170716/thumb/76760e64b7ec481d1a5e29433c94ca79.jpeg', '', 'image/jpeg', 'jpeg', '118089', '653e9a1fbc7b33e1c113df714daeeba4', '0349e7ab4d60d0aee694d845f5bf514c6b4a659f', 'local', '0', '1500191749', '1500191749', '100', '1');
INSERT INTO `uk_attachment` VALUES ('34', '1', 'banner.jpg', 'admin', 'images/20170717/dd8e22b3eb3f590d0e7e2bddf500e25a.jpg', '', '', 'image/jpeg', 'jpg', '65591', 'b1266e9a918fb6aeb762a1613a236ac8', 'f949030143e7802ae437c962d3f6129f769e4485', 'local', '0', '1500262078', '1500262078', '100', '1');
INSERT INTO `uk_attachment` VALUES ('35', '1', 'banner2.jpg', 'admin', 'images/20170719/135493c869d5ab63ac746ae143681f19.jpg', '', '', 'image/jpeg', 'jpg', '61214', '9880a8adaca5832701cf4828563d1ed5', '1591c345a0515e71657a6503bb0f3e8ca6e45f5b', 'local', '0', '1500444067', '1500444067', '100', '1');
INSERT INTO `uk_attachment` VALUES ('49', '1', '苏州小巨人激光智能科技有限公司_02.jpg', 'admin', 'images/20180813/bcf1dfca43bf7e94cdbb41ca55df6ca7.jpg', '', '', 'image/jpeg', 'jpg', '1332', '25e027c1c4bacbd8b419d26d27e2ab74', '9aed95e2c013852d31c6669e6df44a1fbef2f38d', 'local', '0', '1534118800', '1534118800', '100', '1');
INSERT INTO `uk_attachment` VALUES ('37', '1', 'k1.jpg', 'admin', 'images/20170720/79a078c48b971888df3d00ce5c55c7e0.jpg', 'images/20170720/thumb/79a078c48b971888df3d00ce5c55c7e0.jpg', '', 'image/jpeg', 'jpg', '84189', 'cd903bc8243ba32c5e7355b1a56833d3', '202cd993d4132783181f28a64a52590e91a7755e', 'local', '0', '1500512615', '1500512615', '100', '1');
INSERT INTO `uk_attachment` VALUES ('38', '1', 'k3.jpg', 'admin', 'images/20170720/a697a4004d618b40104ea0f688a63d95.jpg', 'images/20170720/thumb/a697a4004d618b40104ea0f688a63d95.jpg', '', 'image/jpeg', 'jpg', '64166', '8b5db4861550913d954dd6a4edff8fc3', '876df8cfb0cab82b493997307942a5a633b1407d', 'local', '0', '1500512615', '1500512615', '100', '1');
INSERT INTO `uk_attachment` VALUES ('39', '1', 'k2.jpg', 'admin', 'images/20170720/0a2bf64e7384824a36bb6ac29f88167b.jpg', 'images/20170720/thumb/0a2bf64e7384824a36bb6ac29f88167b.jpg', '', 'image/jpeg', 'jpg', '78087', '0937423667ab997aeb1bab4bd3b64760', '51e510b5a4c2a2f7b64bd642a7c9d6f287099ebc', 'local', '0', '1500512616', '1500512616', '100', '1');
INSERT INTO `uk_attachment` VALUES ('40', '1', 'b1.jpg', 'admin', 'images/20170720/2839de163a59f042bb8b9af83a0f8ba9.jpg', 'images/20170720/thumb/2839de163a59f042bb8b9af83a0f8ba9.jpg', '', 'image/jpeg', 'jpg', '58731', 'c3df756e4070c8e495fd82dee1831269', '9f3bcbd23bf42dacf575e8a9fb1efb1b33d600b1', 'local', '0', '1500512798', '1500512798', '100', '1');
INSERT INTO `uk_attachment` VALUES ('41', '1', 'b4.jpg', 'admin', 'images/20170720/025560fbf406c4ad68b359e0eba38d8a.jpg', 'images/20170720/thumb/025560fbf406c4ad68b359e0eba38d8a.jpg', '', 'image/jpeg', 'jpg', '53973', '43b949752bca709675099c81bf7c7014', 'f6de6a5ab9dbb8dd19cdf3720a45e16578c7263f', 'local', '0', '1500512798', '1500512798', '100', '1');
INSERT INTO `uk_attachment` VALUES ('42', '1', 'b3.jpg', 'admin', 'images/20170720/63cba4a898e87bc3dd26c588f25ea3a0.jpg', 'images/20170720/thumb/63cba4a898e87bc3dd26c588f25ea3a0.jpg', '', 'image/jpeg', 'jpg', '54960', '9f7beccafbe455c8b6f9b751879651fa', 'd33bc590a325d60a9413f58fee1e2119e2400966', 'local', '0', '1500512799', '1500512799', '100', '1');
INSERT INTO `uk_attachment` VALUES ('43', '1', 'm1.jpg', 'admin', 'images/20170720/95e9f8c667163d1c0573f302926b7ad6.jpg', 'images/20170720/thumb/95e9f8c667163d1c0573f302926b7ad6.jpg', '', 'image/jpeg', 'jpg', '58717', 'e2f80622b564325706f89671e9b685b4', '1cba2b6e2186dc63c86d371894632a39d2594e44', 'local', '0', '1500512875', '1500512875', '100', '1');
INSERT INTO `uk_attachment` VALUES ('44', '1', 'm3.jpg', 'admin', 'images/20170720/2306fbb4261ec050ac71555e20327f5f.jpg', 'images/20170720/thumb/2306fbb4261ec050ac71555e20327f5f.jpg', '', 'image/jpeg', 'jpg', '51866', 'fcd00f1d43075fc0eb99b6fb7936fe27', '63c06337688245de3e90ffa10b5c203b90e6d9f2', 'local', '0', '1500512875', '1500512875', '100', '1');
INSERT INTO `uk_attachment` VALUES ('45', '1', 'm2.jpg', 'admin', 'images/20170720/12f46b62947e7e4c4b2f66348f4c5d59.jpg', 'images/20170720/thumb/12f46b62947e7e4c4b2f66348f4c5d59.jpg', '', 'image/jpeg', 'jpg', '59861', '45d77e88fc18dd38bf3f6abea9979e64', '115a2efbb2ceef79a9fe959b20eda8fa9ac5f3f1', 'local', '0', '1500512876', '1500512876', '100', '1');
INSERT INTO `uk_attachment` VALUES ('46', '1', 'f1.jpg', 'admin', 'images/20170720/5b47dbba06bf2bcf7183a955be99185b.jpg', 'images/20170720/thumb/5b47dbba06bf2bcf7183a955be99185b.jpg', '', 'image/jpeg', 'jpg', '153574', '8f1e5ee3b1e1f94ca3e3c26943de990a', '50f01c0adb401826507b0cc203a40d0173dea1a0', 'local', '0', '1500513400', '1500513400', '100', '1');
INSERT INTO `uk_attachment` VALUES ('47', '1', 'f3.jpg', 'admin', 'images/20170720/647bcdda58c135c7bb66359b2e52f9f6.jpg', 'images/20170720/thumb/647bcdda58c135c7bb66359b2e52f9f6.jpg', '', 'image/jpeg', 'jpg', '194826', '476b73a7cb0a42b7631a995af66755fd', 'c0df02dd3d99ef03df9d1121a79b386c724f88f4', 'local', '0', '1500513401', '1500513401', '100', '1');
INSERT INTO `uk_attachment` VALUES ('48', '1', 'f2.jpg', 'admin', 'images/20170720/6cf8f5108ec738d7c683d377d7209991.jpg', 'images/20170720/thumb/6cf8f5108ec738d7c683d377d7209991.jpg', '', 'image/jpeg', 'jpg', '166422', '27c20c845ddf161b4b4b857633c506ec', '3755ba0ceeba55a74c480185ab409349191b0ce3', 'local', '0', '1500513401', '1500513401', '100', '1');
INSERT INTO `uk_attachment` VALUES ('50', '1', '苏州小巨人激光智能科技有限公司_10.jpg', 'admin', 'images/20180813/d24e8317e2d073d409c71cadfbf05ccb.jpg', '', '', 'image/jpeg', 'jpg', '1435', '27d965ea851900bf61303af2f6b04380', '82af1082e58f8e37a7c6f325eaebc3d731af59b8', 'local', '0', '1534119275', '1534119275', '100', '1');
INSERT INTO `uk_attachment` VALUES ('51', '1', '苏州小巨人激光智能科技有限公司_04.jpg', 'admin', 'images/20180813/53a760b75fc0c56883cb0f2a2641f7c5.jpg', '', '', 'image/jpeg', 'jpg', '1364', '4edb263d7b074334b8524a6ae28fc2fe', 'bfb7e337f419cff156dcf978efff1733ea37293d', 'local', '0', '1534119276', '1534119276', '100', '1');

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
INSERT INTO `yzn_admin` VALUES ('1', 'admin', '9724b5e6c56b95f5723009ef81961bfe', '1', 'Wo0bAa', '御宅男', '1536204060', '2130706433', '530765310@qq.com', '1');
INSERT INTO `yzn_admin` VALUES ('2', 'ken678', '932e31f030b850a87702a86c0e16db16', '2', 'Sxq6dR', '御宅男', '1536205820', '2130706433', '530765310@qq.com', '1');

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
INSERT INTO `yzn_auth_group` VALUES ('1', 'admin', '1', '超级管理员', '拥有所有权限', '1', '146,149,149,150,151,152,152,153,147,148,154');
INSERT INTO `yzn_auth_group` VALUES ('2', 'admin', '1', '编辑', '编辑', '1', '146,149,149,150,151,152,152,153,147,148,154');

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
) ENGINE=MyISAM AUTO_INCREMENT=155 DEFAULT CHARSET=utf8 COMMENT='规则表';

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
INSERT INTO `yzn_auth_rule` VALUES ('154', 'admin', '2', 'admin/main/index', '首页', '1', '');

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
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of yzn_config
-- ----------------------------
INSERT INTO `yzn_config` VALUES ('1', 'web_site_status', 'switch', '站点开关', 'base', '', '站点关闭后前台将不能访问', '1494408414', '1494408414', '1', '1', '1');
INSERT INTO `yzn_config` VALUES ('2', 'web_site_title', 'text', '站点标题', 'base', '', '', '1494408414', '1494408414', '1', 'YznCMS网站管理系统', '2');
INSERT INTO `yzn_config` VALUES ('3', 'web_site_keywords', 'text', '站点关键词', 'base', '', '', '1494408414', '1494408414', '1', '', '3');
INSERT INTO `yzn_config` VALUES ('4', 'web_site_description', 'text', '站点描述', 'base', '', '', '1494408414', '1494408414', '1', '', '4');
INSERT INTO `yzn_config` VALUES ('5', 'web_site_logo', 'image', '站点LOGO', 'base', '', '', '1494408414', '1494408414', '1', null, '5');
INSERT INTO `yzn_config` VALUES ('6', 'web_site_icp', 'text', '备案信息', 'base', '', '', '1494408414', '1494408414', '1', '', '6');
INSERT INTO `yzn_config` VALUES ('7', 'web_site_statistics', 'textarea', '站点代码', 'base', '', '', '1494408414', '1494408414', '1', '', '7');
INSERT INTO `yzn_config` VALUES ('8', 'config_group', 'array', '配置分组', 'system', '', '', '1494408414', '1494408414', '1', 'base:基础\nsystem:系统\nupload:上传\ndevelop:开发', '0');

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
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COMMENT='后台菜单表';

-- ----------------------------
-- Records of yzn_menu
-- ----------------------------
INSERT INTO `yzn_menu` VALUES ('2', '设置', '', '0', 'admin', 'setting', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('3', '模块', '', '0', 'admin', 'module', 'index', '', '0', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('4', '扩展', '', '0', 'admin', 'addons', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('10', '系统配置', '', '2', 'admin', 'config', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('11', '配置管理', '', '10', 'admin', 'config', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('13', '网站设置', '', '10', 'admin', 'config', 'setting', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('14', '菜单管理', '', '10', 'admin', 'menu', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('15', '管理员设置', '', '2', 'admin', 'manager', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('16', '管理员管理', '', '15', 'admin', 'manager', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('17', '角色管理', '', '15', 'admin', 'authManager', 'index', '', '1', '', '0', '0');
INSERT INTO `yzn_menu` VALUES ('6', '首页', '', '0', 'admin', 'main', 'index', '', '0', '', '0', '0');
