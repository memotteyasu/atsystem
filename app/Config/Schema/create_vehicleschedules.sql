CREATE TABLE `vehicleschedules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `companyvehicles_id` int(11) NOT NULL,
  `kaisyas_id` int(11) NOT NULL,
  `scheduledate` date NOT NULL,
  `schedulename` varchar(100) NOT NULL,
  `displaycolor` varchar(7) NOT NULL DEFAULT '#5bc0de',
  `note` varchar(255) DEFAULT NULL,
  `delflg` int(11) NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_vehicleschedules_company_date` (`kaisyas_id`, `scheduledate`),
  KEY `idx_vehicleschedules_vehicle_date` (`companyvehicles_id`, `scheduledate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
