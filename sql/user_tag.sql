-- ----------------------------
-- Table structure for user_tag
-- ----------------------------
DROP TABLE IF EXISTS `user_tag`;
CREATE TABLE `user_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id号',
  `name` varchar(50) NOT NULL COMMENT '标签名',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8;