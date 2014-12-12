/**
* Nautica Bar - 2014
*	Author: Matija Buriša
*/
-- Generation Time: Dec 12, 2014 at 12:51 PM


SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `nauticabar`
--

-- --------------------------------------------------------

--
-- Table structure for table `contact_settings`
--

CREATE TABLE IF NOT EXISTS `contact_settings` (
  `address` varchar(100) NOT NULL DEFAULT '',
  `telephone` varchar(100) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cover_photos`
--

CREATE TABLE IF NOT EXISTS `cover_photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) NOT NULL DEFAULT '',
  `file_location` varchar(255) NOT NULL DEFAULT '',
  `file_size` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `dateTime` varchar(20) NOT NULL,
  `description` text NOT NULL,
  `description_en` text NOT NULL,
  `active` enum('yes','no') NOT NULL DEFAULT 'yes',
  `added` varchar(25) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `events_name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `events_cover`
--

CREATE TABLE IF NOT EXISTS `events_cover` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `event_id` int(10) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_location` varchar(255) NOT NULL,
  `file_size` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cover_name` (`file_name`),
  KEY `event_id` (`event_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `events_gallery`
--

CREATE TABLE IF NOT EXISTS `events_gallery` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `event_id` int(10) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_location` varchar(255) NOT NULL,
  `file_size` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `image_name` (`file_name`),
  KEY `event_id` (`event_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=33 ;

-- --------------------------------------------------------

--
-- Table structure for table `failed_logins`
--

CREATE TABLE IF NOT EXISTS `failed_logins` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(50) NOT NULL DEFAULT '',
  `counter` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `ip` varchar(60) NOT NULL,
  `lastOnline` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_unique` (`username`,`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Constraints for dumped tables
--

--
-- Constraints for table `events_cover`
--
ALTER TABLE `events_cover`
  ADD CONSTRAINT `events_cover_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`);

--
-- Constraints for table `events_gallery`
--
ALTER TABLE `events_gallery`
  ADD CONSTRAINT `events_gallery_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
