SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET NAMES UTF8;

CREATE TABLE IF NOT EXISTS `users` (
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `xslt` (
  `xsl` varchar(100) NOT NULL,
  `path` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `xslt` (`xsl`, `path`) VALUES
('red', './801f7201346b43f8ee8390a1ef20ddcd/red.xsl'),
('orange', './801f7201346b43f8ee8390a1ef20ddcd/orange.xsl'),
('green', './801f7201346b43f8ee8390a1ef20ddcd/green.xsl');