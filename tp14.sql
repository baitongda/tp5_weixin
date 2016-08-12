/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50547
Source Host           : localhost:3306
Source Database       : tp14

Target Server Type    : MYSQL
Target Server Version : 50547
File Encoding         : 65001

Date: 2016-08-12 14:24:42
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for areply
-- ----------------------------
DROP TABLE IF EXISTS `areply`;
CREATE TABLE `areply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `keyword` varchar(60) NOT NULL COMMENT '关键词',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of areply
-- ----------------------------
INSERT INTO `areply` VALUES ('1', 'hello', '1469847935', '1469780583');

-- ----------------------------
-- Table structure for auth_group
-- ----------------------------
DROP TABLE IF EXISTS `auth_group`;
CREATE TABLE `auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(100) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `rules` varchar(2000) NOT NULL COMMENT '规则ID',
  `description` text COMMENT '描述',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of auth_group
-- ----------------------------
INSERT INTO `auth_group` VALUES ('1', '管理员', '1', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,28,30,31,37,38,39,40,41,42,43,44,45,46,47,52,53,54,55,56', '', '0', '1466780039');
INSERT INTO `auth_group` VALUES ('6', '初级管理员', '1', '6,1,37,38,43,39,40,41,42,44,47,45,46,52,55,54,53,56,2,13,3,4', '初级管理员', '1466910557', '1469962873');

-- ----------------------------
-- Table structure for auth_group_access
-- ----------------------------
DROP TABLE IF EXISTS `auth_group_access`;
CREATE TABLE `auth_group_access` (
  `uid` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of auth_group_access
-- ----------------------------
INSERT INTO `auth_group_access` VALUES ('1', '1');
INSERT INTO `auth_group_access` VALUES ('4', '6');

-- ----------------------------
-- Table structure for auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `auth_rule`;
CREATE TABLE `auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(80) NOT NULL DEFAULT '',
  `title` char(20) NOT NULL DEFAULT '',
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `condition` char(100) NOT NULL DEFAULT '',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL,
  `path` varchar(100) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  `is_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=57 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of auth_rule
-- ----------------------------
INSERT INTO `auth_rule` VALUES ('1', 'admin/main/index', '控制面板', '1', '1', '', '0', '0', '0', '0', '1', '1');
INSERT INTO `auth_rule` VALUES ('2', '', '系统', '1', '1', '', '0', '1466909995', '0', '0', '200', '1');
INSERT INTO `auth_rule` VALUES ('3', 'admin/auth_group/index', '角色管理', '1', '1', '', '0', '0', '2', '0-2', '1', '1');
INSERT INTO `auth_rule` VALUES ('4', 'admin/auth_rule/index', '权限列表', '1', '1', '', '0', '1466746258', '2', '0-2', '2', '1');
INSERT INTO `auth_rule` VALUES ('5', 'admin/auth_rule/add', '添加权限', '1', '1', '', '0', '1466686168', '4', '0-2-4', '1', '0');
INSERT INTO `auth_rule` VALUES ('6', 'admin/user/logout', '退出登录', '1', '1', '', '0', '0', '0', '0', '0', '0');
INSERT INTO `auth_rule` VALUES ('7', 'admin/auth_group/add', '添加角色', '1', '1', '', '0', '0', '3', '0-2-3', '0', '0');
INSERT INTO `auth_rule` VALUES ('8', 'admin/auth_group/edit', '编辑角色', '1', '1', '', '0', '0', '3', '0-2-3', '0', '0');
INSERT INTO `auth_rule` VALUES ('9', 'admin/auth_group/del', '删除角色', '1', '1', '', '0', '0', '3', '0-2-3', '0', '0');
INSERT INTO `auth_rule` VALUES ('10', 'admin/auth_rule/edit', '编辑权限', '1', '1', '', '0', '1466686416', '4', '0-2-4', '2', '0');
INSERT INTO `auth_rule` VALUES ('11', 'admin/user/changePwd', '修改密码', '1', '1', '', '1466688085', '1466688085', '0', '0', '0', '0');
INSERT INTO `auth_rule` VALUES ('12', 'admin/auth_group/resource', '资源管理', '1', '1', '', '1466688887', '1466688887', '3', '0-2-3', '0', '0');
INSERT INTO `auth_rule` VALUES ('13', 'admin/user/index', '用户管理', '1', '1', '', '1466778713', '1466778747', '2', '0-2', '0', '1');
INSERT INTO `auth_rule` VALUES ('14', 'admin/user/edit', '编辑用户', '1', '1', '', '1466779374', '1466779374', '13', '0-2-13', '0', '0');
INSERT INTO `auth_rule` VALUES ('15', 'admin/user/del', '删除用户', '1', '1', '', '1466779400', '1466779400', '13', '0-2-13', '0', '0');
INSERT INTO `auth_rule` VALUES ('16', 'admin/user/add', '添加用户', '1', '1', '', '1466780028', '1466780028', '13', '0-2-13', '0', '0');
INSERT INTO `auth_rule` VALUES ('17', 'admin/auth_rule/del', '删除权限', '1', '1', '', '1466911172', '1466911172', '4', '0-2-4', '0', '0');
INSERT INTO `auth_rule` VALUES ('46', 'admin/img/edit', '编辑图文回复', '1', '1', '', '1469860004', '1469860004', '44', '0-37-44', '0', '0');
INSERT INTO `auth_rule` VALUES ('45', 'admin/img/add', '添加图文回复', '1', '1', '', '1469848740', '1469848740', '44', '0-37-44', '0', '0');
INSERT INTO `auth_rule` VALUES ('44', 'admin/img/index', '图文回复', '1', '1', '', '1469848715', '1469848715', '37', '0-37', '3', '1');
INSERT INTO `auth_rule` VALUES ('43', 'admin/areply/set', '设置关注回复', '1', '1', '', '1469846640', '1469846640', '38', '0-37-38', '0', '0');
INSERT INTO `auth_rule` VALUES ('42', 'admin/text/del', '删除文本回复', '1', '1', '', '1469797962', '1469797962', '39', '0-37-39', '0', '0');
INSERT INTO `auth_rule` VALUES ('41', 'admin/text/edit', '编辑文本回复', '1', '1', '', '1469797940', '1469797940', '39', '0-37-39', '0', '0');
INSERT INTO `auth_rule` VALUES ('40', 'admin/text/add', '添加文本回复', '1', '1', '', '1469797906', '1469797919', '39', '0-37-39', '0', '0');
INSERT INTO `auth_rule` VALUES ('39', 'admin/text/index', '文本回复', '1', '1', '', '1469797613', '1469797613', '37', '0-37', '2', '1');
INSERT INTO `auth_rule` VALUES ('28', 'admin/clear/index', '清除缓存', '1', '1', '', '1467706434', '1467706434', '0', '0', '199', '1');
INSERT INTO `auth_rule` VALUES ('37', '', '基础功能', '1', '1', '', '1469778251', '1469778251', '0', '0', '2', '1');
INSERT INTO `auth_rule` VALUES ('31', 'admin/user/resetPwd', '重置密码', '1', '1', '', '1467942854', '1468997612', '0', '0', '0', '0');
INSERT INTO `auth_rule` VALUES ('54', 'admin/diymen/edit', '编辑菜单', '1', '1', '', '1469959336', '1469959343', '52', '0-37-52', '0', '0');
INSERT INTO `auth_rule` VALUES ('55', 'admin/diymen/del', '删除菜单', '1', '1', '', '1469959363', '1469959363', '52', '0-37-52', '0', '0');
INSERT INTO `auth_rule` VALUES ('47', 'admin/img/del', '删除图文回复', '1', '1', '', '1469860030', '1469860030', '44', '0-37-44', '0', '0');
INSERT INTO `auth_rule` VALUES ('38', 'admin/areply/index', '关注回复', '1', '1', '', '1469778312', '1469778312', '37', '0-37', '1', '1');
INSERT INTO `auth_rule` VALUES ('53', 'admin/diymen/add', '添加菜单', '1', '1', '', '1469959321', '1469959321', '52', '0-37-52', '0', '0');
INSERT INTO `auth_rule` VALUES ('52', 'admin/diymen/index', '自定义菜单', '1', '1', '', '1469949477', '1469950606', '37', '0-37', '4', '1');
INSERT INTO `auth_rule` VALUES ('56', 'admin/diymen/createMenu', '生成菜单', '1', '1', '', '1469961548', '1469961576', '52', '0-37-52', '0', '0');

-- ----------------------------
-- Table structure for diymen
-- ----------------------------
DROP TABLE IF EXISTS `diymen`;
CREATE TABLE `diymen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fid` int(11) NOT NULL COMMENT '父id',
  `keyword` varchar(30) DEFAULT '' COMMENT '关键词',
  `title` varchar(30) NOT NULL COMMENT '菜单名称',
  `url` varchar(255) DEFAULT '' COMMENT '跳转url',
  `is_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示，默认1，显示，0表示不显示',
  `sort` tinyint(3) NOT NULL COMMENT '排序',
  `path` varchar(100) NOT NULL COMMENT '路径',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='自定义菜单';

-- ----------------------------
-- Records of diymen
-- ----------------------------
INSERT INTO `diymen` VALUES ('2', '0', '', '百度', 'http://www.baidu.com', '1', '1', '0', '1469961105', '1469961105');
INSERT INTO `diymen` VALUES ('3', '2', '', '微信部落', 'http://wxbuluo.com', '1', '2', '0-2', '1469961123', '1469961123');

-- ----------------------------
-- Table structure for img
-- ----------------------------
DROP TABLE IF EXISTS `img`;
CREATE TABLE `img` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keyword` char(255) NOT NULL,
  `type` tinyint(1) NOT NULL COMMENT '关键词匹配类型',
  `text` text NOT NULL COMMENT '简介',
  `classid` int(11) NOT NULL DEFAULT '0',
  `pic` char(255) NOT NULL COMMENT '封面图片',
  `showpic` varchar(1) NOT NULL DEFAULT '0' COMMENT '图片是否显示封面，默认为0，不显示',
  `info` text NOT NULL COMMENT '图文详细内容',
  `url` char(255) DEFAULT NULL COMMENT '图文外链地址',
  `click` int(11) DEFAULT '0' COMMENT '点击量',
  `title` varchar(60) NOT NULL,
  `sorts` varchar(6) DEFAULT '0',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `classid` (`classid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of img
-- ----------------------------
INSERT INTO `img` VALUES ('2', '宁静的夏天', '2', '宁静的夏天', '0', '/uploads/image/20160730/20160730153101_34079.jpg', '1', '宁静的夏天', '', '0', '宁静的夏天', '1', '1469872191', '1469864563');

-- ----------------------------
-- Table structure for keyword
-- ----------------------------
DROP TABLE IF EXISTS `keyword`;
CREATE TABLE `keyword` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keyword` char(255) NOT NULL,
  `pid` int(11) NOT NULL,
  `module` varchar(15) NOT NULL,
  `type` varchar(1) NOT NULL DEFAULT '1',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of keyword
-- ----------------------------
INSERT INTO `keyword` VALUES ('5', '宁静的夏天', '2', 'img', '2', '1469872191', '1469864563');
INSERT INTO `keyword` VALUES ('7', 'hello', '2', 'text', '1', '1469945822', '1469945822');

-- ----------------------------
-- Table structure for tetris
-- ----------------------------
DROP TABLE IF EXISTS `tetris`;
CREATE TABLE `tetris` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `wxuid` int(11) unsigned NOT NULL COMMENT '微信用户ID',
  `score` int(11) NOT NULL COMMENT '分数',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `wxuid` (`wxuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tetris
-- ----------------------------

-- ----------------------------
-- Table structure for text
-- ----------------------------
DROP TABLE IF EXISTS `text`;
CREATE TABLE `text` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `keyword` char(255) NOT NULL,
  `text` text NOT NULL,
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of text
-- ----------------------------
INSERT INTO `text` VALUES ('2', 'hello', '感谢关注！', '1469945822', '1469945822');

-- ----------------------------
-- Table structure for ucenter_member
-- ----------------------------
DROP TABLE IF EXISTS `ucenter_member`;
CREATE TABLE `ucenter_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` char(16) NOT NULL COMMENT '用户名',
  `password` char(32) NOT NULL COMMENT '密码',
  `email` char(32) NOT NULL COMMENT '用户邮箱',
  `mobile` char(15) NOT NULL DEFAULT '' COMMENT '用户手机',
  `reg_ip` varchar(20) NOT NULL DEFAULT '0' COMMENT '注册IP',
  `last_login_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_login_ip` varchar(20) NOT NULL DEFAULT '0' COMMENT '最后登录IP',
  `status` tinyint(4) DEFAULT '0' COMMENT '用户状态',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Records of ucenter_member
-- ----------------------------
INSERT INTO `ucenter_member` VALUES ('1', 'admin', '779d005fa526b871d424fcab8140582f', '296720094@qq.com', '18053449656', '0', '1470981411', '2130706433', '1', '0', '1470981451');
INSERT INTO `ucenter_member` VALUES ('4', 'test', '779d005fa526b871d424fcab8140582f', '', '', '127.0.0.1', '1469946812', '2130706433', '1', '1467356831', '1469946812');

-- ----------------------------
-- Table structure for wxuser
-- ----------------------------
DROP TABLE IF EXISTS `wxuser`;
CREATE TABLE `wxuser` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `openid` varchar(30) NOT NULL COMMENT 'openid',
  `thumb` varchar(255) NOT NULL COMMENT '头像',
  `name` varchar(30) NOT NULL COMMENT '微信昵称',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `openid` (`openid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wxuser
-- ----------------------------
