SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET NAMES UTF8;

CREATE TABLE `users` (
  `login_id` varchar(64) NOT NULL,
  `login_pw` varchar(64) NOT NULL,
  `login_name` varchar(128) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `users` (`login_id`, `login_pw`, `login_name`) VALUES
('**secret**', '**secret**', '**secret**'),
('**secret**', '**secret**', '**secret**'),
('**secret**', '**secret**', '**secret**'),
('**secret**', '**secret**', '**secret**'),
('**secret**', '**secret**', '**secret**');