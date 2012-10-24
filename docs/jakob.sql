-- Create database 
CREATE DATABASE `jakob_db`; 

-- Create tables 
CREATE TABLE `jakob_db`.`jakob__configuration` ( 
      `id` int(11) NOT NULL AUTO_INCREMENT, 
      `jobid` varchar(40) CHARACTER SET utf8 COLLATE utf8_danish_ci NOT NULL, 
      `name` text CHARACTER SET utf8 COLLATE utf8_danish_ci NOT NULL, 
      `targetsp` text CHARACTER SET utf8 COLLATE utf8_danish_ci NOT NULL, 
      `targetidp` text CHARACTER SET utf8 COLLATE utf8_danish_ci NOT NULL, 
      `configuration` text CHARACTER SET utf8 COLLATE utf8_danish_ci NOT NULL, 
      PRIMARY KEY (`id`), 
      KEY `jobid` (`jobid`) 
) ENGINE=MyISAM DEFAULT CHARSET=utf8; 

CREATE TABLE `jakob_db`.`jakob__consumer` ( 
      `consumerkey` varchar(20) COLLATE utf8_danish_ci NOT NULL, 
      `consumersecret` text COLLATE utf8_danish_ci NOT NULL, 
      `email` text COLLATE utf8_danish_ci NOT NULL, 
      PRIMARY KEY (`consumerkey`) 
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_danish_ci; 

-- Create users 
CREATE USER jakobadmin IDENTIFIED BY '1234'; 
CREATE USER jakobuser IDENTIFIED BY '1234'; 

-- Grant privileges 
GRANT SELECT, INSERT, UPDATE, DELETE ON jakob_db.* TO 'jakobadmin'; 
GRANT SELECT ON jakob_db.* TO 'jakobuser'; 

-- Flush privileges 
FLUSH PRIVILEGES;
