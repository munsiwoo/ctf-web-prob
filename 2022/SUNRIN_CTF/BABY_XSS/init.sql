SET NAMES utf8mb4;

CREATE TABLE `urls` (
  `no` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(4096) DEFAULT NULL,
  `is_read` int(1) DEFAULT 0,
  PRIMARY KEY (`no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
