SET NAMES UTF8;

CREATE TABLE IF NOT EXISTS `board` (
  `no` varchar(100),
  `title` varchar(100),
  `content` varchar(100),
  `username` varchar(100),
  PRIMARY KEY(`no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `users` (
  `nickname` varchar(100),
  `username` varchar(100),
  `password` varchar(100)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
