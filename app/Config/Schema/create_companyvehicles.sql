CREATE TABLE `companyvehicles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kaisyas_id` int(11) NOT NULL,
  `vehiclename` varchar(100) NOT NULL,
  `plateno` varchar(100) NOT NULL,
  `displayno` int(11) NOT NULL DEFAULT 0,
  `branchmasters_id` int(11) NOT NULL,
  `delflg` int(11) NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_companyvehicles_company_display` (`kaisyas_id`, `delflg`, `displayno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
