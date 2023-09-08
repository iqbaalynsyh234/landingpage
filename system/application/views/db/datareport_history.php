CREATE TABLE IF NOT EXISTS `%s` (
  `id` int(11) NOT NULL auto_increment,
  `gps_id` int(11) default NULL,
  `gps_name` varchar(32) default NULL,
  `gps_host` varchar(32) default NULL,
  `gps_type` varchar(10) default NULL,
  `gps_utc_coord` int(11) default NULL COMMENT 'in UTC (coordinated universal time zone). UTC used be known as GMT',
  `gps_status` char(1) default NULL COMMENT 'Status A = Valid, V = Invalid',
  `gps_latitude` varchar(16) default NULL COMMENT 'latitude of the GPS position fix',
  `gps_ns` char(1) default NULL COMMENT 'N/S Indicator S N = North, S = South',
  `gps_longitude` varchar(16) default NULL COMMENT 'longitude of the GPS position fix',
  `gps_ew` char(1) default NULL COMMENT 'E/W Indicator E E = East, W = West',
  `gps_speed` float default NULL COMMENT 'Speed over ground in Knots',
  `gps_course` float default NULL COMMENT 'Course over ground 0.00 Degrees',
  `gps_utc_date` int(11) default NULL COMMENT 'UTC Date 211200 DDMMYY',
  `gps_mvd` float default NULL COMMENT 'Magnetic variation Degrees',
  `gps_mv` char(1) default NULL COMMENT 'mv',
  `gps_cs` varchar(100) default NULL COMMENT 'Checksum *25',
  `gps_msg_ori` varchar(1024) default NULL,
  `gps_time` timestamp NULL default NULL,
  `gps_latitude_real` double default NULL,
  `gps_longitude_real` double default NULL,
  `gps_odometer` double default NULL,
  `gps_workhour` int(11) default NULL,
  `gps_info_id` int(11) default NULL,
  `gps_info_device` varchar(100) DEFAULT NULL,
  `gps_info_hdop` varchar(4) DEFAULT NULL,
  `gps_info_io_port` varchar(10) DEFAULT NULL,
  `gps_info_distance` float DEFAULT NULL,
  `gps_info_alarm_data` varchar(10) DEFAULT NULL,
  `gps_info_ad_input` varchar(10) DEFAULT NULL,
  `gps_info_utc_coord` int(11) DEFAULT NULL,
  `gps_info_utc_date` int(11) DEFAULT NULL,
  `gps_info_alarm_alert` varchar(4) DEFAULT NULL,
  `gps_info_time` timestamp NULL DEFAULT NULL,
  `gps_info_status` int(11) DEFAULT '0' COMMENT '0=new;1=alarm proccessed',
  `gps_info_gps` int(11) DEFAULT NULL,
  PRIMARY KEY  (`gps_id`),
  KEY `NewIndex3` (`gps_name`,`gps_host`),
  KEY `NewIndex1` (`gps_utc_coord`),
  KEY `NewIndex4` (`gps_utc_date`),
  KEY `gps_park_index` (`gps_name`,`gps_host`,`gps_time`),
  KEY `gps_info_time` (`gps_info_time`),
  KEY `NewIndex6` (`gps_info_alarm_alert`,`gps_info_status`),
  KEY `gps_info_workhour_index` (`gps_info_time`,`gps_info_device`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1