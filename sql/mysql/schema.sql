CREATE TABLE `ocopendata_archive_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) DEFAULT NULL,
  `class_identifier` varchar(100) DEFAULT NULL,
  `data_text` longtext,
  `url_alias_list` longtext,
  `node_id_list` longtext,
  `object_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `requested_time` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '0'  
  PRIMARY KEY (`id`),
  KEY `ocopendata_archive_item_type` (`type`),
  KEY `ocopendata_archive_item_class_identifier` (`class_identifier`),
  KEY `ocopendata_archive_item_requested_time` (`requested_time`),
  KEY `ocopendata_archive_item_user_id` (`user_id`),
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
