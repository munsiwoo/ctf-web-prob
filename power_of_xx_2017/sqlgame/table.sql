SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET NAMES UTF8;

CREATE TABLE IF NOT EXISTS `users` (
  `usercode` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;