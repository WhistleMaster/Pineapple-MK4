SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

CREATE TABLE IF NOT EXISTS `PineappleStats_Attempts` (
  `Attempts_Login_ID` int(11) NOT NULL,
  `Attempts_Time` varchar(30) collate latin1_german1_ci NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `PineappleStats_Data` (
  `Data_ID` int(250) NOT NULL auto_increment,
  `Data_Timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `Pineapple_ID` int(11) NOT NULL,
  `Station_SSID` char(150) default NULL,
  `Station_MAC` char(150) NOT NULL,
  `Station_Signal` char(150) NOT NULL,
  `Station_Signal_Quality` char(150) NOT NULL,
  PRIMARY KEY  (`Data_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=554 ;

CREATE TABLE IF NOT EXISTS `PineappleStats_Login` (
  `Login_ID` int(11) NOT NULL auto_increment,
  `Login_Username` varchar(30) collate latin1_german1_ci NOT NULL,
  `Login_Email` varchar(50) collate latin1_german1_ci NOT NULL,
  `Login_Password` char(128) collate latin1_german1_ci NOT NULL,
  `Login_Salt` char(128) collate latin1_german1_ci NOT NULL,
  PRIMARY KEY  (`Login_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `PineappleStats_Pineapples` (
  `Pineapple_ID` int(250) NOT NULL auto_increment,
  `Pineapple_Number` char(150) NOT NULL,
  `Pineapple_Name` char(150) NOT NULL,
  `Pineapple_MAC` char(150) NOT NULL,
  `Pineapple_Latitude` char(150) NOT NULL,
  `Pineapple_Longitude` char(150) NOT NULL,
  `Pineapple_LastReport` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `Pineapple_IP` varchar(150) NOT NULL,
  PRIMARY KEY  (`Pineapple_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

CREATE TABLE IF NOT EXISTS `PineappleStats_Uptime` (
  `Uptime_ID` int(250) NOT NULL auto_increment,
  `Pineapple_ID` int(11) NOT NULL,
  `Pineapple_LastReport` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`Uptime_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

CREATE TABLE IF NOT EXISTS `PineappleStats_Tokens` (
  `Token_ID` int(250) NOT NULL auto_increment,
  `Token_Number` char(150) NOT NULL,
  PRIMARY KEY  (`Token_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

CREATE TABLE IF NOT EXISTS `PineappleStats_Stats` (
  `Stats_ID` int(250) NOT NULL auto_increment,
  `Stats_Timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `Pineapple_ID` int(11) NOT NULL,
  `Station_MAC` char(150) NOT NULL,
  `Station_X` int NOT NULL,
  PRIMARY KEY  (`Stats_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

CREATE VIEW `PineappleStats_Data_View` AS select `PineappleStats_Data`.`Data_ID` AS `Data_ID`,`PineappleStats_Data`.`Data_Timestamp` AS `Data_Timestamp`,`PineappleStats_Data`.`Pineapple_ID` AS `Pineapple_ID`,`PineappleStats_Data`.`Station_SSID` AS `Station_SSID`,`PineappleStats_Data`.`Station_MAC` AS `Station_MAC`,`PineappleStats_Data`.`Station_Signal` AS `Station_Signal`,`PineappleStats_Data`.`Station_Signal_Quality` AS `Station_Signal_Quality`,`PineappleStats_Pineapples`.`Pineapple_Name` AS `Pineapple_Name`,`PineappleStats_Pineapples`.`Pineapple_Latitude` AS `Pineapple_Latitude`,`PineappleStats_Pineapples`.`Pineapple_Longitude` AS `Pineapple_Longitude` from (`PineappleStats_Data` join `PineappleStats_Pineapples`) WHERE `PineappleStats_Data`.`Pineapple_ID` = `PineappleStats_Pineapples`.`Pineapple_ID`;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
