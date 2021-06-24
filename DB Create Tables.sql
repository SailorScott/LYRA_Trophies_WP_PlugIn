-- Adminer 4.2.6-dev MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';

CREATE DATABASE `local` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `local`;

DROP VIEW IF EXISTS `lyra_boatname2boatids`;
CREATE TABLE `lyra_boatname2boatids` (`boatname` varchar(35), `boatid` smallint(4), `FirstWin` smallint(4));


DROP VIEW IF EXISTS `lyra_winners`;
CREATE TABLE `lyra_winners` (`regattaYear` smallint(4), `BoatName` varchar(35), `TrophyNameShort` varchar(50), `AwardedFor` varchar(100), `BoatID` smallint(4), `TrophyID` smallint(2));


DROP TABLE IF EXISTS `wp_lyra_boat`;
CREATE TABLE `wp_lyra_boat` (
  `BoatID` smallint(4) NOT NULL AUTO_INCREMENT,
  `BoatName` varchar(35) DEFAULT NULL,
  `Skipper` varchar(100) DEFAULT NULL,
  `BoatType` varchar(50) DEFAULT NULL,
  `HomeClub` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`BoatID`)
) ENGINE=MyISAM AUTO_INCREMENT=719 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `wp_lyra_regattas`;
CREATE TABLE `wp_lyra_regattas` (
  `regattaYear` smallint(4) DEFAULT NULL,
  `HostClubs` varchar(100) DEFAULT NULL,
  `Description` varchar(2000) DEFAULT NULL,
  `LinksToArchive` varchar(200) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `wp_lyra_trophies`;
CREATE TABLE `wp_lyra_trophies` (
  `TrophyID` smallint(2) NOT NULL AUTO_INCREMENT,
  `TrophyNameShort` varchar(50) DEFAULT NULL,
  `TrophyColumnLabel` varchar(20) DEFAULT NULL,
  `TrophyNameDetails` varchar(100) DEFAULT NULL,
  `DeedOfGift` varchar(100) DEFAULT NULL,
  `RaceDetails` varchar(100) DEFAULT NULL,
  `TrophyPageDisplayOrder` int(11) DEFAULT NULL,
  `ReggattaDisplayOrder` int(11) DEFAULT NULL,
  `LongDescription` varchar(1000) DEFAULT NULL,
  `PictureLink` varchar(200) DEFAULT NULL,
  `YearFirstAwarded` int(11) DEFAULT NULL,
  `YearRetired` int(11) DEFAULT NULL,
  PRIMARY KEY (`TrophyID`)
) ENGINE=MyISAM AUTO_INCREMENT=88 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `wp_lyra_trophywinners`;
CREATE TABLE `wp_lyra_trophywinners` (
  `regattaYear` smallint(4) DEFAULT NULL,
  `TrophyID` smallint(2) DEFAULT NULL,
  `BoatID` smallint(4) DEFAULT NULL,
  `AwardedFor` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `wp_lyra_trophywinners_notboats`;
CREATE TABLE `wp_lyra_trophywinners_notboats` (
  `regattaYear` int(4) NOT NULL,
  `TrophyID` int(2) NOT NULL,
  `Winner` varchar(35) NOT NULL,
  `AwardedFor` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `lyra_boatname2boatids`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `lyra_boatname2boatids` AS select `lyra_winners`.`BoatName` AS `boatname`,`lyra_winners`.`BoatID` AS `boatid`,min(`lyra_winners`.`regattaYear`) AS `FirstWin` from `lyra_winners` group by `lyra_winners`.`BoatName`,`lyra_winners`.`BoatID`;

DROP TABLE IF EXISTS `lyra_winners`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `lyra_winners` AS select `TW`.`regattaYear` AS `regattaYear`,`B`.`BoatName` AS `BoatName`,`T`.`TrophyNameShort` AS `TrophyNameShort`,`TW`.`AwardedFor` AS `AwardedFor`,`TW`.`BoatID` AS `BoatID`,`TW`.`TrophyID` AS `TrophyID` from ((`wp_lyra_trophywinners` `TW` join `wp_lyra_boat` `B` on((`TW`.`BoatID` = `B`.`BoatID`))) join `wp_lyra_trophies` `T` on((`TW`.`TrophyID` = `T`.`TrophyID`)));

-- 2021-06-24 01:41:39
