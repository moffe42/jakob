SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE `jakob__configuration` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `jobid` varchar(40) CHARACTER SET utf8 COLLATE utf8_danish_ci NOT NULL,
      `name` text CHARACTER SET utf8 COLLATE utf8_danish_ci NOT NULL,
      `targetsp` text CHARACTER SET utf8 COLLATE utf8_danish_ci NOT NULL,
      `targetidp` text CHARACTER SET utf8 COLLATE utf8_danish_ci NOT NULL,
      `configuration` text CHARACTER SET utf8 COLLATE utf8_danish_ci NOT NULL,
      PRIMARY KEY (`id`),
      UNIQUE KEY `jobid` (`jobid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
