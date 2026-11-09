CREATE TABLE `employeeschedules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `users_id` int(11) DEFAULT NULL,
  `kaisyas_id` int(11) DEFAULT NULL,
  `scheduledate` date NOT NULL,
  `schedulename` varchar(100) NOT NULL,
  `displaycolor` varchar(7) NOT NULL DEFAULT '#5bc0de',
  `note` varchar(255) DEFAULT NULL,
  `delflg` int(11) NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created_user` int(11) DEFAULT NULL,
  `modified_user` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_employeeschedules_user_date` (`users_id`, `scheduledate`),
  KEY `idx_employeeschedules_company_date` (`kaisyas_id`, `scheduledate`),
  KEY `idx_employeeschedules_date` (`scheduledate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
