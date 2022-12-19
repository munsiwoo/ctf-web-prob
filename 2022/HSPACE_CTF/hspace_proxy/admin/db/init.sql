SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET NAMES utf8mb4;

CREATE TABLE `admin` (
  `username` VARCHAR(32) NOT NULL,
  `password` VARCHAR(32) NOT NULL,
  `token` VARCHAR(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `notice` (
  `notice_id` VARCHAR(36) NOT NULL,
  `title` VARCHAR(32) NOT NULL,
  `contents` TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `admin` VALUES ('admin', '[CENSORED]', '[CENSORED]');
INSERT INTO `notice` VALUES
    ('cf14a064-f06b-4cfc-95d4-3acca4dd945d', 'Welcome to hpsace proxy.', "Hspace proxy is free online proxy service,\nBut it's still a beta version."),
    ('5acf8929-990f-49a5-90a0-cd00c9179670', 'How do I use this service?', "It's very simple to use.\nEnter the url and click the connect button."),
    ('5162d7a3-31da-4dc0-87d6-2be3f3ca47bf', 'Launch bug bounty program!', 'We have launched bug bounty!\nPlease find vulnerabilities in our service :)');
