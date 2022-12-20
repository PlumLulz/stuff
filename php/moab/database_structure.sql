-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 14, 2018 at 06:38 AM
-- Server version: 5.1.37
-- PHP Version: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `http_botnet`
--

-- --------------------------------------------------------

--
-- Table structure for table `administrators`
--

CREATE TABLE IF NOT EXISTS `administrators` (
  `userid` varchar(999) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `administrators`
--

INSERT INTO `administrators` (`userid`) VALUES
('2');

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE IF NOT EXISTS `chat_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` varchar(9999) NOT NULL,
  `username` varchar(9999) NOT NULL,
  `message` varchar(9999) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `date`, `username`, `message`) VALUES
(1, 'NOTICE', 'Plum2', 'The chat box has been cleared!');

-- --------------------------------------------------------

--
-- Table structure for table `email_verify`
--

CREATE TABLE IF NOT EXISTS `email_verify` (
  `userid` varchar(999) NOT NULL,
  `hash` varchar(999) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `email_verify`
--


-- --------------------------------------------------------

--
-- Table structure for table `online_zombies`
--

CREATE TABLE IF NOT EXISTS `online_zombies` (
  `unique_id` varchar(999) NOT NULL,
  `victim_id` varchar(999) NOT NULL,
  `ipaddress` varchar(999) NOT NULL,
  `user_agent` varchar(999) NOT NULL,
  `timestamp` varchar(999) NOT NULL,
  `pc_name` varchar(999) NOT NULL,
  `location` varchar(999) NOT NULL,
  `os` varchar(999) NOT NULL,
  `online_status` varchar(999) NOT NULL,
  `last_handshake` varchar(9999) NOT NULL,
  `referer` varchar(9999) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `online_zombies`
--

INSERT INTO `online_zombies` (`unique_id`, `victim_id`, `ipaddress`, `user_agent`, `timestamp`, `pc_name`, `location`, `os`, `online_status`, `last_handshake`, `referer`) VALUES
('KmQya9s7XKTNubJdWS4ESBGQcBc9x87V', 'NPbalgAmD6F5iPIPNphiGFm5l5vGT0KV', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.13; rv:57.0) Gecko/20100101 Firefox/57.0', '2018-01-14 06:29:48', 'localhost', 'Unknown', 'Mac OS X', '0', '2018-01-14 06:33:17', 'http://localhost/moab/zomb.php');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `userid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(999) NOT NULL,
  `email` varchar(999) NOT NULL,
  `ip` varchar(999) NOT NULL,
  `password` varchar(999) NOT NULL,
  `salt` varchar(999) NOT NULL,
  `unique_id` varchar(999) NOT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userid`, `username`, `email`, `ip`, `password`, `salt`, `unique_id`) VALUES
(1, 'Plum', 'sads', 'sadas', 'a4e8c734c0386a699599cfb61dc18061', 'zZnyCZGYocW9I7Ec8jddQJDbtP9Vo8', 'dasdasd'),
(2, 'Plum2', 'lol@lol.com', '127.0.0.1', '245af17b691267ef0543345b08e36d363b2424a8', '92lThyDhDZTeugR9vGQF1ZMCFNLTIwsScKEQm4XM', 'KmQya9s7XKTNubJdWS4ESBGQcBc9x87V');

-- --------------------------------------------------------

--
-- Table structure for table `zombie_commands`
--

CREATE TABLE IF NOT EXISTS `zombie_commands` (
  `uid` varchar(9999) NOT NULL,
  `zid` varchar(9999) NOT NULL,
  `js` varchar(9999) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `zombie_commands`
--


-- --------------------------------------------------------

--
-- Table structure for table `zombie_logs`
--

CREATE TABLE IF NOT EXISTS `zombie_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(9999) NOT NULL,
  `zid` varchar(9999) NOT NULL,
  `command` varchar(9999) NOT NULL,
  `response` longtext NOT NULL,
  `timestamp` varchar(9999) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `zombie_logs`
--

