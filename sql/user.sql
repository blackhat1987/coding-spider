-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id号',
  `user_id` int(11) NOT NULL COMMENT '用户Id',
  `username` varchar(100) NOT NULL COMMENT '用户名',
  `birthday` varchar(20) DEFAULT NULL COMMENT '生日',
  `company` varchar(50) DEFAULT NULL COMMENT '公司',
  `created_at` varchar(30) NOT NULL DEFAULT '0' COMMENT '加入时间',
  `follows_count` int(11) NOT NULL DEFAULT '0',
  `fans_count` int(11) NOT NULL DEFAULT '0' COMMENT '粉丝数量',
  `global_key` varchar(50) NOT NULL COMMENT '个性后缀',
  `job` int(11) DEFAULT NULL COMMENT '工作',
  `last_logined_at` varchar(30) NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_activity_at` varchar(30) NOT NULL COMMENT '最后活动时间',
  `location` varchar(50) DEFAULT NULL COMMENT '地区',
  `name_pinyin` varchar(100) NOT NULL COMMENT '用户名拼音',
  `slogan` varchar(500) DEFAULT NULL COMMENT '一句话介绍',
  `sex` int(10) DEFAULT NULL,
  `tags` varchar(40) DEFAULT NULL COMMENT '标签Id字符串',
  `tags_str` varchar(200) DEFAULT NULL COMMENT '标签字符串',
  `tweets_count` int(11) NOT NULL DEFAULT '0' COMMENT '冒泡数',
  `updated_at` varchar(30) NOT NULL DEFAULT 0 COMMENT '最后更新时间',
  `status` int(11) DEFAULT NULL COMMENT '用户状态',
  `add_time` int(11) NOT NULL COMMENT '添加时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`) USING HASH
) ENGINE=InnoDB AUTO_INCREMENT=23583 DEFAULT CHARSET=utf8;