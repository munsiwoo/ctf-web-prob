SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET NAMES UTF8;

CREATE TABLE IF NOT EXISTS `contacts` (
  `idx` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `contents` varchar(512) NOT NULL,
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `cooking` (
  `idx` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `contents` varchar(10000) NOT NULL,
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

INSERT INTO `cooking` (`idx`, `title`, `contents`) VALUES
(1, 'Welcome to cooking category', 'welcome to cooking category<br>articles related to cooking will be posted.'),
(2, 'Steak is very delicious XD', '<img src="http://www.omahasteaks.com/gifs/990x594/fi004.jpg" width="500" height="400"/>\r\ngood good good~');


CREATE TABLE IF NOT EXISTS `song` (
  `idx` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `contents` varchar(10000) NOT NULL,
  PRIMARY KEY (`idx`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

INSERT INTO `song` (`idx`, `title`, `contents`) VALUES
(1, 'Welcome to song category', 'Welcome to song category!'),
(2, 'I love Sam Smith songs. XD', 'Im Not The Only One is good, Stay with me is good.');

CREATE TABLE IF NOT EXISTS `users` (
  `token` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `users` (`token`, `username`, `password`) VALUES
('1694138c2ede438201a100d641307ec78b3f3c96', 'admin', '1e5915d79c1edc1064f2862b0bb1b77318a0e6d670a99335eb4bf6b3d2e9fea6');