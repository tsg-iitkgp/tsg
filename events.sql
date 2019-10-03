-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 03, 2019 at 09:41 AM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tsg`
--

-- --------------------------------------------------------

--
-- Table structure for table `tsg_events`
--

CREATE TABLE `tsg_events` (
  `organisation` varchar(125) NOT NULL,
  `description` varchar(125) NOT NULL,
  `link_event` varchar(300) NOT NULL,
  `time_event` varchar(125) NOT NULL,
  `venue_event` varchar(125) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tsg_events`
--

INSERT INTO `tsg_events` (`organisation`, `description`, `link_event`, `time_event`, `venue_event`) VALUES
('encore', 'nukkad', 'https://www.google.com/', '9am', 'vikramshila'),
('kshitij', 'kshitij main event', 'https://www.google.com/', '9am', 'tsg');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
