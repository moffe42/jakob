SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

CREATE TABLE `jakob__configuration` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `jobid` varchar(40) CHARACTER SET utf8 COLLATE utf8_danish_ci NOT NULL,
      `name` text CHARACTER SET utf8 COLLATE utf8_danish_ci NOT NULL,
      `targetsp` text CHARACTER SET utf8 COLLATE utf8_danish_ci NOT NULL,
      `targetidp` text CHARACTER SET utf8 COLLATE utf8_danish_ci NOT NULL,
      `configuration` text CHARACTER SET utf8 COLLATE utf8_danish_ci NOT NULL,
      PRIMARY KEY (`id`),
      KEY `jobid` (`jobid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `jakob__consumer` (
      `consumerkey` varchar(20) COLLATE utf8_danish_ci NOT NULL,
      `consumersecret` text COLLATE utf8_danish_ci NOT NULL,
      `email` text COLLATE utf8_danish_ci NOT NULL,
      PRIMARY KEY (`consumerkey`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_danish_ci;
