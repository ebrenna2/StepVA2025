-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 10, 2025 at 04:53 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `stepvadb`
--

-- --------------------------------------------------------


-- --------------------------------------------------------

--
-- Table structure for table `dbpersons`
--


CREATE TABLE `dbpersons` (
  `id` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` text DEFAULT NULL,
  `venue` text DEFAULT NULL,
  `first_name` text NOT NULL,
  `last_name` text DEFAULT NULL,
  `street_address` text DEFAULT NULL,
  `city` text DEFAULT NULL,
  `state` varchar(2) DEFAULT NULL,
  `zip_code` text DEFAULT NULL,
  `phone1` varchar(12) NOT NULL,
  `phone1type` text DEFAULT NULL,
  `emergency_contact_phone` varchar(12) DEFAULT NULL,
  `emergency_contact_phone_type` text DEFAULT NULL,
  `birthday` text DEFAULT NULL,
  `email` text DEFAULT NULL,
  `emergency_contact_first_name` text NOT NULL,
  `contact_num` varchar(12) NOT NULL,
  `emergency_contact_relation` text NOT NULL,
  `contact_method` text DEFAULT NULL,
  `type` text DEFAULT NULL,
  `status` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `password` text DEFAULT NULL,
  `profile_pic` text NOT NULL,
  `gender` varchar(6) NOT NULL,
  `tshirt_size` text NOT NULL,
  `how_you_heard_of_stepva` text NOT NULL,
  `sensory_sensitivities` text NOT NULL,
  `disability_accomodation_needs` text NOT NULL,
  `school_affiliation` text NOT NULL,
  `race` text NOT NULL,
  `preferred_feedback_method` text NOT NULL,
  `hobbies` text NOT NULL,
  `professional_experience` text NOT NULL,
  `archived` tinyint(1) NOT NULL,
  `emergency_contact_last_name` text NOT NULL,
  `photo_release` text NOT NULL,
  `photo_release_notes` text NOT NULL,
  `training_complete` int(11) NOT NULL DEFAULT 0,
  `training_date` text NOT NULL,
  `orientation_complete` int(11) NOT NULL DEFAULT 0,
  `orientation_date` text NOT NULL,
  `background_complete` int(11) NOT NULL DEFAULT 0,
  `background_date` text NOT NULL,
  `skills` text DEFAULT NULL,
  `networks` text DEFAULT NULL,
  `contributions` text DEFAULT NULL,
  `familyid` int(11) DEFAULT NULL,
  `profile_feature` varchar(50) DEFAULT NULL,
  `identification_preference` varchar(100) DEFAULT NULL,
  `headshot_publish` varchar(10) DEFAULT NULL,
  `likeness_usage` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `dbevents`
--
CREATE TABLE `dbevents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `date` char(10) NOT NULL,
  `startTime` char(5) NOT NULL,
  `endTime` char(5) NOT NULL,
  `description` text NOT NULL,
  `capacity` int(11) NOT NULL,
  `completed` text NOT NULL,
  `event_type` text NOT NULL,
  `restricted_signup` tinyint(1) NOT NULL,
  `location` text DEFAULT NULL,
  `restricted_volunteers` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `dbeventmedia`
--

CREATE TABLE `dbeventmedia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eventID` int(11) NOT NULL,
  `file_name` text NOT NULL,
  `type` text NOT NULL,
  `file_format` text NOT NULL,
  `description` text NOT NULL,
  `altername_name` text NOT NULL,
  `time_created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `FKeventID2` (`eventID`),
  CONSTRAINT `FKeventID2` FOREIGN KEY (`eventID`) REFERENCES `dbevents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



--
-- Table structure for table `dbeventpersons`
--

CREATE TABLE `dbeventpersons` (
  `eventID` int(11) NOT NULL,
  `userID` varchar(256) NOT NULL,
  `position` text NOT NULL,
  `notes` text NOT NULL,
  KEY `FKeventID` (`eventID`),
  KEY `FKpersonID` (`userID`),
  CONSTRAINT `FKeventID` FOREIGN KEY (`eventID`) REFERENCES `dbevents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FKpersonID` FOREIGN KEY (`userID`) REFERENCES `dbpersons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dbeventpersons`
--

/* INSERT INTO `dbeventpersons` (`eventID`, `userID`, `position`, `notes`) VALUES
(30, 'test', 'v', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials: '),
(31, 'test', 'v', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials: '),
(29, 'someInfo2', 'v', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials: '),
(28, 'username', 'v', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials: '),
(28, 'lucy', 'v', ''),
(29, 'fredastaire', 'v', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials: '),
(64, 'test', 'v', 'Skills: no | Dietary restrictions: no | Disabilities: no | Materials: no'),
(36, 'username', 'v', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials: '),
(71, 'test', 'v', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials: '),
(72, 'test', 'v', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials: '),
(64, 'fredastaire', 'v', 'Skills: Dance | Dietary restrictions:  | Disabilities:  | Materials: Cookies'),
(71, 'fredastaire', 'v', ''),
(71, 'lucy', 'v', ''),
(75, 'lucy', 'v', ''),
(64, 'vmsroot', 'v', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials: '),
(36, 'fredastaire', 'v', 'Skills: Tap dancer (films include Top Hat and Shall We Dance) | Dietary restrictions:  | Disabilities:  | Materials: Tap shoes'),
(73, 'vmsroot', 'v', 'Skills: Baking | Dietary restrictions:  | Disabilities:  | Materials: Marmalade and toast'),
(36, 'morgan', 'v', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials: '),
(36, 'charles', 'v', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials: '),
(64, 'charles', 'v', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials: '),
(79, 'charles', 'v', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials: '),
(70, 'lucy', 'v', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials: Marmalade and toast'),
(70, 'vmsroot', 'v', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials:'),
(78, 'charles', 'v', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials:'),
(79, 'testuser', 'v', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials: '),
(82, 'testuser', 'v', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials: '),
(83, 'testuser', 'v', 'Skills: No | Dietary restrictions: No | Disabilities: No | Materials: No'),
(97, 'morgan', 'v', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials: '),
(81, 'charles', 'v', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials: '),
(73, 'fredastaire', 'v', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials: Cookies'),
(101, 'stepvainc@gmail.com', 'v', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials:'),
(101, 'StrawberryJade', 'v', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials:'),
(101, 'SaraDowd', 'v', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials:'),
(101, 'ebrenna2', 'v', 'Skills:  | Dietary restrictions:  | Disabilities:  | Materials:'),
(100, 'vmsroot', 'v', 'Skills: None | Dietary restrictions: None | Disabilities: None | Materials: None');

*/
-- --------------------------------------------------------




--
-- Dumping data for table `dbevents`
--
/*
INSERT INTO `dbevents` (`id`, `name`, `date`, `startTime`, `endTime`, `description`, `capacity`, `completed`, `event_type`, `restricted_signup`, `location`, `restricted_volunteers`) VALUES
(28, 'Thanksgiving Feast', '2024-11-28', '', '23:59', 'There is so much to give thanks for!', 0, 'no', '', 1, NULL, 0),
(29, 'Tuesdays with the Troubadours', '2024-11-26', '', '23:59', 'The Troubadours are live!', 0, 'no', '', 0, NULL, 0),
(30, 'Hogwarts Festival', '2024-11-23', '', '23:59', 'Hello', 0, 'no', '', 0, 'Hogwarts', 0),
(31, 'Sing Together!', '2024-11-23', '', '23:59', 'aoeiaoei', 0, 'no', '', 0, NULL, 0),
(36, 'Daytime Yoga', '2024-12-17', '11:00', '12:00', 'Weekly event', 25, 'no', '', 0, 'STEP VA Studio', 0),
(47, 'Finding Nemo Jr Audition Track', '2025-01-02', '18:00', '20:00', '(ages 10+)', 30, 'no', '', 0, 'Riverside First Church of God', 0),
(64, 'Christmas Cookie Party', '2024-12-14', '16:00', '18:00', 'Bring your favorite cookie recipe to share!', 0, 'yes', '', 0, NULL, 0),
(69, 'Finding Nemo Jr: Tech Classes', '2025-01-07', '18:00', '20:00', 'ages 13+', 12, 'no', '', 1, 'STEP VA Studio: 3328 Bourbon Street', 0),
(70, 'Rolex Paris Masters', '2024-12-25', '13:00', '15:00', 'Testing testing 123', 99, 'no', '', 1, 'Location', 0),
(71, 'Christmas Party', '2024-12-22', '18:00', '21:00', 'Welcome!', 100, 'no', '', 1, 'Palace', 0),
(72, 'Awesome Event 2024', '2024-12-05', '13:00', '17:00', 'This is the most awesome event of 2024!', 50, 'no', '', 0, 'UMW', 0),
(73, 'Finding Nemo Jr.', '2025-07-19', '17:00', '18:30', 'Come and see the children perform!', 100, 'no', '', 1, 'Step VA headquarters', 0),
(75, 'The Spotsylvanians', '2024-12-14', '19:00', '21:00', 'The Spotsylvanians Present A Hometown Holiday', 100, 'no', '', 1, 'Riverbend High School 12301 Spotswood Furnace Road Fredericksburg', 0),
(76, 'Fred&#039;s Piano Recital', '2024-12-14', '18:00', '21:00', 'Come hear Fred play piano!', 99, 'no', '', 0, 'StepVA Headquarters', 0),
(78, 'Costume making', '2024-12-23', '12:00', '17:00', 'Costume making', 10, 'no', '', 1, 'location', 0),
(79, 'Singing lessons', '2024-12-22', '12:00', '17:00', 'Singing Lessons', 10, 'no', '', 0, 'Location', 0),
(80, 'Arts and Crafts', '2024-12-11', '12:00', '17:00', 'Awesome!', 10, 'no', '', 0, 'location', 0),
(81, 'Dancing lessons', '2024-12-18', '12:00', '17:00', 'dancing lessons!', 10, 'no', '', 0, 'location', 0),
(82, 'Tree Ceremony', '2024-12-10', '21:30', '22:00', 'Come light a Christmas Tree downtown!', 2, 'no', '', 0, '', 0),
(83, 'Step VA Presentation', '2024-12-11', '09:00', '13:00', 'TADA!!!', 4, 'no', '', 0, 'Tada', 0),
(87, 'Christmas Tree Lighting', '2024-12-14', '07:00', '18:00', 'We&#039;re going to light the Christmas tree!', 99, 'no', '', 0, 'Stafford Square', 0),
(89, 'Tuesdays with the Troubadours: Christmas with the Troubadours', '2024-12-17', '09:00', '12:00', 'The Troubadours are live! Come celebrate Christmas!', 99, 'no', '', 0, 'Virtual Online', 0),
(92, 'CandyLand Game Night', '2024-12-12', '07:00', '12:00', 'Let&#039;s play CandyLand!', 15, 'no', '', 0, 'STEP VA Studio: 3328 Bourbon Street', 0),
(94, 'Gary_Young_Resume_Policy.pdf', '2024-12-26', '00:00', '00:01', 'ABC', 8, 'no', '', 0, '', 0),
(97, 'Test', '2024-12-12', '12:00', '13:00', 'Test', 1, 'no', '', 0, 'Test', 0),
(98, 'Rolex Paris Masters', '2024-12-11', '15:42', '18:00', 'Testing testing 123', 4, 'no', '', 0, 'Location', 0),
(99, 'Christmas Wrapping Event', '2025-02-05', '15:45', '18:00', 'hot', 3, 'no', '', 0, 'Location', 0),
(100, 'Finding Nemo', '2025-02-15', '17:30', '20:00', 'approved volunteers', 15, 'no', '', 1, '', 0),
(101, 'Finding Nemo Rehearsal', '2025-01-23', '17:30', '20:00', 'rehearsal', 20, 'no', '', 1, 'Riverside First Church of God 3461 Fall Hill Ave. Fredericksburg 22401', 0),
(103, 'Finding Nemo Rehearsal', '2025-01-30', '17:30', '20:00', 'rehearsal', 20, 'no', '', 1, 'Riverside First Church of God 3461 Fall Hill Ave. Fredericksburg 22401', 0),
(104, 'Finding Nemo Rehearsal', '2025-02-06', '17:30', '20:00', 'rehearsal', 20, 'no', '', 1, 'Riverside First Church of God 3461 Fall Hill Ave. Fredericksburg 22401', 0),
(105, 'Finding Nemo Rehearsal', '2025-03-27', '17:30', '20:00', 'rehearsal', 20, 'no', '', 1, 'Riverside First Church of God 3461 Fall Hill Ave. Fredericksburg 22401', 0),
(106, 'Finding Nemo Rehearsal', '2025-03-20', '17:30', '20:00', 'rehearsal', 20, 'no', '', 1, 'Riverside First Church of God 3461 Fall Hill Ave. Fredericksburg 22401', 0),
(107, 'visa', '2025-02-02', '12:00', '13:00', 'dqdwec', 60, 'no', '', 1, 'vwvw', 0),
(108, 'jninjn', '2025-02-13', '12:00', '13:00', 'ijnoi', 1, 'no', '', 1, 'noin', 0),
(109, 'Test', '2025-02-13', '13:00', '14:30', 'This is a test event', 99, 'no', '', 0, 'Fredericksburg, VA', 0);

*/
-- 

--
-- Table structure for table `dbmessages`
--

CREATE TABLE `dbmessages` (
  `id` int(11) NOT NULL,
  `senderID` varchar(256) NOT NULL,
  `recipientID` varchar(256) NOT NULL,
  `title` varchar(256) NOT NULL,
  `body` text NOT NULL,
  `time` varchar(16) NOT NULL,
  `wasRead` tinyint(1) NOT NULL DEFAULT 0,
  `prioritylevel` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dbmessages`
--

INSERT INTO `dbmessages` (`id`, `senderID`, `recipientID`, `title`, `body`, `time`, `wasRead`, `prioritylevel`) VALUES
(0, 'vmsroot', 'username', 'Your restricted event sign-up has sent to an admin.', 'Your request to sign up for restricted event notification test will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-02-10:19', 0, 0),
(0, 'vmsroot', 'vmsroot', 'username requested to sign up for a restricted event', 'username requested to sign up for restricted event notification test. Please review.', '2024-12-02-10:19', 1, 0),
(0, 'vmsroot', 'username', 'Your sign-up has been approved.', 'Thank you for signing up for notification testing (unrestricted)!', '2024-12-02-10:19', 0, 0),
(0, 'vmsroot', 'fredastaire', 'Your restricted event sign-up has sent to an admin.', 'Your request to sign up for restricted event notification test will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-02-11:15', 0, 0),
(0, 'vmsroot', 'vmsroot', 'fredastaire requested to sign up for a restricted event', 'fredastaire requested to sign up for restricted event notification test. Please review.', '2024-12-02-11:15', 1, 0),
(0, 'vmsroot', 'someInfo2', 'Your restricted event sign-up has sent to an admin.', 'Your request to sign up for notification testing (restricted) will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-02-16:06', 0, 0),
(0, 'vmsroot', 'vmsroot', 'someInfo2 requested to sign up for a restricted event', 'someInfo2 requested to sign up for notification testing (restricted). Please review.', '2024-12-02-16:06', 1, 0),
(0, 'vmsroot', 'fredastaire', 'Your restricted event sign-up has sent to an admin.', 'Your request to sign up for DO NOT DELETE will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-02-19:22', 0, 0),
(0, 'vmsroot', 'vmsroot', 'fredastaire requested to sign up for a restricted event', 'fredastaire requested to sign up for DO NOT DELETE. Please review.', '2024-12-02-19:22', 1, 0),
(0, 'vmsroot', 'morgan', 'Your restricted event sign-up has sent to an admin.', 'Your request to sign up for Rolex Paris Masters will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-02-20:31', 0, 0),
(0, 'vmsroot', 'vmsroot', 'morgan requested to sign up for a restricted event', 'morgan requested to sign up for Rolex Paris Masters. Please review.', '2024-12-02-20:31', 1, 0),
(0, 'vmsroot', 'fredastaire', 'Your restricted event sign-up has sent to an admin.', 'Your request to sign up for restricted event notification test will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-02-21:01', 0, 0),
(0, 'vmsroot', 'vmsroot', 'fredastaire requested to sign up for a restricted event', 'fredastaire requested to sign up for restricted event notification test. Please review.', '2024-12-02-21:01', 1, 0),
(0, 'vmsroot', 'fredastaire', 'Your restricted event sign-up has sent to an admin.', 'Your request to sign up for Christmas Party will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-02-21:05', 0, 0),
(0, 'vmsroot', 'vmsroot', 'fredastaire requested to sign up for a restricted event', 'fredastaire requested to sign up for Christmas Party. Please review.', '2024-12-02-21:05', 1, 0),
(0, 'vmsroot', 'morgan', 'Your sign-up has been approved.', 'Thank you for signing up for Christmas Cookie Party!', '2024-12-02-21:09', 0, 0),
(0, 'vmsroot', 'vmsroot', 'Your sign-up has been approved.', 'Thank you for signing up for Christmas Cookie Party!', '2024-12-02-21:11', 1, 0),
(0, 'vmsroot', 'test', 'Your restricted event sign-up has sent to an admin.', 'Your request to sign up for notification testing (restricted) will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-04-02:08', 0, 0),
(0, 'vmsroot', 'vmsroot', 'test requested to sign up for a restricted event', 'test requested to sign up for notification testing (restricted). Please review.', '2024-12-04-02:08', 1, 0),
(0, 'vmsroot', 'test', 'Your restricted event sign-up has sent to an admin.', 'Your request to sign up for notification testing (restricted) will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-04-02:13', 0, 0),
(0, 'vmsroot', 'vmsroot', 'test requested to sign up for a restricted event', 'test requested to sign up for notification testing (restricted). Please review.', '2024-12-04-02:13', 1, 0),
(0, 'vmsroot', 'test', 'Your restricted event sign-up has sent to an admin.', 'Your request to sign up for notification testing (restricted) will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-04-02:18', 0, 0),
(0, 'vmsroot', 'vmsroot', 'test requested to sign up for a restricted event', 'test requested to sign up for notification testing (restricted). Please review.', '2024-12-04-02:18', 1, 0),
(0, 'vmsroot', 'test', 'Your sign-up has been approved.', 'Thank you for signing up for notification testing (restricted)!', '2024-12-04-02:40', 0, 0),
(0, 'vmsroot', 'test', 'Your restricted event sign-up has sent to an admin.', 'Your request to sign up for notification testing (restricted) will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-04-02:42', 0, 0),
(0, 'vmsroot', 'vmsroot', 'test requested to sign up for a restricted event', 'test requested to sign up for notification testing (restricted). Please review.', '2024-12-04-02:42', 1, 0),
(0, 'vmsroot', 'test', 'Your restricted event sign-up has sent to an admin.', 'Your request to sign up for notification testing (restricted) will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-04-02:44', 0, 0),
(0, 'vmsroot', 'vmsroot', 'test requested to sign up for a restricted event', 'test requested to sign up for notification testing (restricted). Please review.', '2024-12-04-02:44', 1, 0),
(0, 'vmsroot', 'test', 'Your restricted event sign-up has sent to an admin.', 'Your request to sign up for notification testing (restricted) will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-04-02:46', 0, 0),
(0, 'vmsroot', 'vmsroot', 'test requested to sign up for a restricted event', 'test requested to sign up for notification testing (restricted). Please review.', '2024-12-04-02:46', 1, 0),
(0, 'vmsroot', 'test', 'Your restricted event sign-up has sent to an admin.', 'Your request to sign up for notification testing (restricted) will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-04-02:54', 0, 0),
(0, 'vmsroot', 'vmsroot', 'test requested to sign up for a restricted event', 'test requested to sign up for notification testing (restricted). Please review.', '2024-12-04-02:54', 1, 0),
(0, 'vmsroot', 'username', 'Your restricted event sign-up has sent to an admin.', 'Your request to sign up for restricted event notification test will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-04-03:04', 0, 0),
(0, 'vmsroot', 'vmsroot', 'username requested to sign up for a restricted event', 'username requested to sign up for restricted event notification test. Please review.', '2024-12-04-03:04', 1, 0),
(0, 'vmsroot', 'username', 'Your sign-up has been approved.', 'Thank you for signing up for Test!', '2024-12-04-03:05', 0, 0),
(0, 'vmsroot', 'username', 'Your restricted event sign-up has sent to an admin.', 'Your request to sign up for Christmas Party will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-04-03:05', 0, 0),
(0, 'vmsroot', 'vmsroot', 'username requested to sign up for a restricted event', 'username requested to sign up for Christmas Party. Please review.', '2024-12-04-03:05', 1, 0),
(0, 'vmsroot', 'test', 'Your sign-up has been approved.', 'Thank you for signing up for Christmas Party!', '2024-12-04-03:08', 0, 0),
(0, 'vmsroot', 'test', 'Your restricted event sign-up has sent to an admin.', 'Your request to sign up for restricted event notification test will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-04-03:09', 0, 0),
(0, 'vmsroot', 'vmsroot', 'test requested to sign up for a restricted event', 'test requested to sign up for restricted event notification test. Please review.', '2024-12-04-03:09', 1, 0),
(0, 'vmsroot', 'test', 'Your restricted event sign-up has sent to an admin.', 'Your request to sign up for DO NOT DELETE will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-04-03:09', 0, 0),
(0, 'vmsroot', 'vmsroot', 'test requested to sign up for a restricted event', 'test requested to sign up for DO NOT DELETE. Please review.', '2024-12-04-03:09', 1, 0),
(0, 'vmsroot', 'test', 'Your restricted event sign-up has sent to an admin.', 'Your request to sign up for notification testing (restricted) will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-04-03:15', 0, 0),
(0, 'vmsroot', 'vmsroot', 'test requested to sign up for a restricted event', 'test requested to sign up for notification testing (restricted). Please review.', '2024-12-04-03:15', 1, 0),
(0, 'vmsroot', 'test', 'Your sign-up has been approved.', 'Thank you for signing up for s!', '2024-12-04-03:20', 0, 0),
(0, 'vmsroot', 'test', 'Your sign-up has been approved.', 'Thank you for signing up for s!', '2024-12-04-03:23', 0, 0),
(0, 'vmsroot', 'test', 'Your sign-up has been approved.', 'Thank you for signing up for s!', '2024-12-04-03:41', 0, 0),
(0, 'vmsroot', 'someInfo2', 'Your sign-up has been approved.', 'Thank you for signing up for s!', '2024-12-04-05:16', 0, 0),
(0, 'vmsroot', 'someInfo2', 'Your sign-up has been approved.', 'Thank you for signing up for s!', '2024-12-04-05:17', 0, 0),
(0, 'vmsroot', 'someInfo2', 'Your sign-up has been approved.', 'Thank you for signing up for s!', '2024-12-04-05:17', 0, 0),
(0, 'vmsroot', 'someInfo2', 'Your restricted event sign-up has sent to an admin.', 'Your request to sign up for AHHHH will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-04-09:16', 0, 0),
(0, 'vmsroot', 'vmsroot', 'someInfo2 requested to sign up for a restricted event', 'someInfo2 requested to sign up for AHHHH. Please review.', '2024-12-04-09:16', 1, 0),
(0, 'vmsroot', 'someInfo2', 'Your sign-up has been approved.', 'Thank you for signing up for s!', '2024-12-04-09:22', 0, 0),
(0, 'vmsroot', 'lucy', 'Your sign-up has been approved.', 'Thank you for signing up for restricted event notification test!', '2024-12-04-11:26', 0, 0),
(0, 'vmsroot', 'vmsroot', 'username cancelled their sign up for Christmas Party', 'username cancelled their sign up for Christmas Party', '2024-12-04-13:38', 1, 0),
(0, 'vmsroot', 'vmsroot', 'username cancelled their sign up for restricted event notification test', 'username cancelled their sign up for restricted event notification test', '2024-12-04-13:38', 1, 0),
(0, 'vmsroot', 'vmsroot', 'Your sign-up has been approved.', 'Thank you for signing up for Awesome Event 2024!', '2024-12-05-14:12', 1, 0),
(0, 'vmsroot', 'vmsroot', 'Your sign-up has been approved.', 'Thank you for signing up for Christmas Cookie Party!', '2024-12-05-18:26', 1, 0),
(0, 'vmsroot', 'fredastaire', 'Your restricted event sign-up has sent to an admin.', 'Your request to sign up for Christmas Party will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-05-18:27', 0, 0),
(0, 'vmsroot', 'fredastaire', 'Your restricted event sign-up has sent to an admin.', 'Your request to sign up for Christmas Party will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-05-18:30', 0, 0),
(0, 'vmsroot', 'lucy', 'Your restricted event sign-up has sent to an admin.', 'Your request to sign up for Christmas Party will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-05-18:57', 0, 0),
(0, 'vmsroot', 'username', 'Your restricted event sign-up has sent to an admin.', 'Your request to sign up for cool event will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-07-12:31', 0, 0),
(0, 'vmsroot', 'lucy', 'Your restricted event sign-up has sent to an admin.', 'Your request to sign up for The Spotsylvanians will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-09-13:28', 0, 0),
(0, 'vmsroot', 'vmsroot', 'Your restricted event sign-up has sent to an admin.', 'Your request to sign up for notification testing (restricted) will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-09-16:57', 1, 0),
(0, 'vmsroot', 'vmsroot', 'Your restricted event sign-up has sent to an admin.', 'Your request to sign up for restricted event notification test will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-09-16:59', 1, 0),
(0, 'vmsroot', 'vmsroot', 'Your restricted event sign-up has sent to an admin.', 'Your request to sign up for AHHHH will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-09-17:22', 1, 0),
(0, 'vmsroot', 'vmsroot', 'Your restricted event sign-up has sent to an admin.', 'Your request to sign up for cool event will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-09-17:37', 1, 0),
(0, 'vmsroot', 'fredastaire', 'Your restricted event sign-up has sent to an admin.', 'Your request to sign up for notification testing (restricted) will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-09-18:56', 0, 0),
(0, 'vmsroot', 'vmsroot', 'Your restricted event sign-up has sent to an admin.', 'Your request to sign up for DO NOT DELETE will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-09-18:58', 1, 0),
(0, 'vmsroot', 'vmsroot', 'Your sign-up has been approved.', 'Thank you for signing up for Christmas Cookie Party!', '2024-12-09-18:59', 1, 0),
(0, 'vmsroot', 'vmsroot', 'Your sign-up has been approved.', 'Thank you for signing up for s!', '2024-12-09-19:01', 1, 0),
(0, 'vmsroot', 'vmsroot', 'Your sign-up has been approved.', 'Thank you for signing up for restricted event notification test!', '2024-12-09-19:12', 1, 0),
(0, 'vmsroot', 'fredastaire', 'Your restricted event sign-up has sent to an admin.', 'Your request to sign up for restricted event notification test will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-09-19:13', 0, 0),
(0, 'vmsroot', 'fredastaire', 'Your sign-up has been approved.', 'Thank you for signing up for restricted event notification test!', '2024-12-09-19:18', 0, 0),
(0, 'vmsroot', 'fredastaire', 'Your restricted event sign-up has sent to an admin.', 'Your request to sign up for notification testing (restricted) will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-09-19:19', 0, 0),
(0, 'vmsroot', 'fredastaire', 'Your restricted event sign-up has sent to an admin.', 'Your request to sign up for notification testing (restricted) will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-09-19:37', 0, 0),
(0, 'vmsroot', 'test', 'Your sign-up has been approved.', 'Thank you for signing up for notification testing (unrestricted)!', '2024-12-09-19:39', 0, 0),
(0, 'vmsroot', 'fredastaire', 'Your sign-up has been approved.', 'Thank you for signing up for notification testing (restricted)!', '2024-12-09-19:42', 0, 0),
(0, 'vmsroot', 'fredastaire', 'Your restricted event sign-up has sent to an admin.', 'Your request to sign up for restricted event notification test will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-09-19:42', 0, 0),
(0, 'vmsroot', 'vmsroot', 'Your sign-up has been approved.', 'Thank you for signing up for restricted event notification test!', '2024-12-09-19:46', 1, 0),
(0, 'vmsroot', 'vmsroot', 'Your sign-up has been approved.', 'Thank you for signing up for AHHHH!', '2024-12-09-19:47', 1, 0),
(0, 'vmsroot', 'fredastaire', 'Your sign-up has been approved.', 'Thank you for signing up for Test!', '2024-12-09-19:47', 0, 0),
(0, 'vmsroot', 'fredastaire', 'Your restricted event sign-up has sent to an admin.', 'Your request to sign up for Finding Nemo Jr. will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-09-19:48', 0, 0),
(0, 'vmsroot', 'vmsroot', 'Your sign-up has been approved.', 'Thank you for signing up for Finding Nemo Jr.!', '2024-12-09-19:49', 1, 0),
(0, 'vmsroot', 'someInfo2', 'Your restricted event sign-up has sent to an admin.', 'Your request to sign up for AHHHH will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-09-20:30', 0, 0),
(0, 'vmsroot', 'morgan', 'Your restricted event sign-up has sent to an admin.', 'Your request to sign up for New Years Day Party will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-09-20:31', 0, 0),
(0, 'vmsroot', 'vmsroot', 'Your restricted event signup has been approved', 'You are now signed up for Array. Congratulations!', '2024-12-09-20:41', 1, 0),
(0, 'vmsroot', 'testuser', 'Your restricted event sign-up has sent to an admin.', 'Your request to sign up for New Years Day Party will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-09-20:42', 0, 0),
(0, 'vmsroot', 'vmsroot', 'Your sign-up has been approved.', 'Thank you for signing up for New Years Day Party!', '2024-12-09-20:42', 1, 0),
(0, 'vmsroot', 'testuser', 'Your restricted event signup has been approved', 'You are now signed up for Array. Congratulations!', '2024-12-09-20:43', 0, 0),
(0, 'vmsroot', 'lucy', 'Your restricted event sign-up has sent to an admin.', 'Your request to sign up for New Years Day Party will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-09-20:43', 0, 0),
(0, 'vmsroot', 'vmsroot', 'morgan cancelled their sign up for New Years Day Party', 'morgan cancelled their sign up for New Years Day Party', '2024-12-09-20:44', 1, 0),
(0, 'vmsroot', 'testuser', 'Your sign-up has been approved.', 'Thank you for signing up for New Years Day Party!', '2024-12-09-20:45', 0, 0),
(0, 'vmsroot', 'vmsroot', 'testuser cancelled their sign up for New Years Day Party', 'testuser cancelled their sign up for New Years Day Party', '2024-12-09-20:46', 1, 0),
(0, 'vmsroot', 'testuser', 'Your sign-up has been approved.', 'Thank you for signing up for Christmas Party!', '2024-12-09-20:46', 0, 0),
(0, 'vmsroot', 'vmsroot', 'testuser cancelled their sign up for Christmas Party', 'testuser cancelled their sign up for Christmas Party', '2024-12-09-20:46', 1, 0),
(0, 'vmsroot', 'vmsroot', 'testuser cancelled their sign up for Monday Morning', 'testuser cancelled their sign up for Monday Morning', '2024-12-09-20:46', 1, 0),
(0, 'vmsroot', 'testuser', 'Your sign-up has been approved.', 'Thank you for signing up for New Years Day Party!', '2024-12-09-20:47', 0, 0),
(0, 'vmsroot', 'morgan', 'Your sign-up has been approved.', 'Thank you for signing up for Daytime Yoga!', '2024-12-09-20:47', 0, 0),
(0, 'vmsroot', 'vmsroot', 'morgan cancelled their sign up for Christmas Cookie Party', 'morgan cancelled their sign up for Christmas Cookie Party', '2024-12-09-20:47', 1, 0),
(0, 'vmsroot', 'vmsroot', 'Your sign-up has been approved.', 'Thank you for signing up for Christmas Party!', '2024-12-09-20:49', 1, 0),
(0, 'vmsroot', 'lucy', 'Your restricted event sign-up has sent to an admin.', 'Your request to sign up for Rolex Paris Masters will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-09-20:51', 0, 0),
(0, 'vmsroot', 'vmsroot', 'Your sign-up has been approved.', 'Thank you for signing up for Rolex Paris Masters!', '2024-12-09-21:03', 1, 0),
(0, 'vmsroot', 'vmsroot', 'Your restricted event sign-up has sent to an admin.', 'Your request to sign up for Rolex Paris Masters will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-09-21:05', 1, 0),
(0, 'vmsroot', 'vmsroot', 'Your sign-up has been approved.', 'Thank you for signing up for Rolex Paris Masters!', '2024-12-09-21:05', 1, 0),
(0, 'vmsroot', 'vmsroot', 'Your sign-up has been approved.', 'Thank you for signing up for Rolex Paris Masters!', '2024-12-09-21:06', 1, 0),
(0, 'vmsroot', 'vmsroot', 'Your restricted event sign-up has sent to an admin.', 'Your request to sign up for Rolex Paris Masters will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-09-21:10', 1, 0),
(0, 'vmsroot', 'charles', 'Your sign-up has been approved.', 'Thank you for signing up for Daytime Yoga!', '2024-12-10-13:35', 1, 0),
(0, 'vmsroot', 'charles', 'Your sign-up has been approved.', 'Thank you for signing up for Finding Nemo Jr Audition Track!', '2024-12-10-13:36', 1, 0),
(0, 'vmsroot', 'charles', 'Your sign-up has been approved.', 'Thank you for signing up for Christmas Cookie Party!', '2024-12-10-13:36', 1, 0),
(0, 'vmsroot', 'vmsroot', 'Your sign-up has been approved.', 'Thank you for signing up for Rolex Paris Masters!', '2024-12-10-17:11', 1, 0),
(0, 'vmsroot', 'vmsroot', 'Your restricted event sign-up has sent to an admin.', 'Your request to sign up for Rolex Paris Masters will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-10-17:40', 1, 0),
(0, 'vmsroot', 'charles', 'Your sign-up has been approved.', 'Thank you for signing up for Arts and Crafts!', '2024-12-10-18:45', 1, 0),
(0, 'vmsroot', 'charles', 'You are now signed up for Singing lessons!', 'Thank you for signing up for Singing lessons!', '2024-12-10-18:52', 1, 0),
(0, 'vmsroot', 'charles', 'Your request to sign up for Costume making has been sent to an admin.', 'Your request to sign up for Costume making will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-10-19:16', 1, 0),
(0, 'vmsroot', 'vmsroot', 'charles cancelled their sign up for Finding Nemo Jr Audition Track', 'charles cancelled their sign up for Finding Nemo Jr Audition Track', '2024-12-10-19:33', 1, 0),
(0, 'vmsroot', 'charles', 'Your request to sign up for The Spotsylvanians has been sent to an admin.', 'Your request to sign up for The Spotsylvanians will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-10-19:57', 1, 0),
(0, 'vmsroot', 'charles', 'You are now signed up for Dancing lessons!', 'Thank you for signing up for Dancing lessons!', '2024-12-10-20:03', 1, 0),
(0, 'vmsroot', 'vmsroot', 'charles cancelled their sign up for Dancing lessons', 'charles cancelled their sign up for Dancing lessons', '2024-12-10-20:04', 1, 0),
(0, 'vmsroot', 'charles', 'Your request to sign up for Finding Nemo Jr: Tech Classes has been sent to an admin.', 'Your request to sign up for Finding Nemo Jr: Tech Classes will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-10-20:06', 1, 0),
(0, 'vmsroot', 'charles', 'Your request to sign up for Rolex Paris Masters has been sent to an admin.', 'Your request to sign up for Rolex Paris Masters will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-10-20:15', 1, 0),
(0, 'vmsroot', 'vmsroot', 'charles cancelled their sign up for Arts and Crafts', 'charles cancelled their sign up for Arts and Crafts', '2024-12-10-20:20', 1, 0),
(0, 'vmsroot', 'charles', 'Your sign up for Arts and Crafts has been cancelled.', 'charles cancelled their sign up for Arts and Crafts', '2024-12-10-20:20', 1, 0),
(0, 'vmsroot', 'testuser', 'You are now signed up for Christmas Party!', 'Thank you for signing up for Christmas Party!', '2024-12-10-20:58', 0, 0),
(0, 'vmsroot', 'testuser', 'Your request to sign up for Christmas Party has been sent to an admin.', 'Your request to sign up for Christmas Party will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-10-21:07', 0, 0),
(0, 'vmsroot', 'vmsroot', 'testuser cancelled their sign up for New Years Day Party', 'testuser cancelled their sign up for New Years Day Party', '2024-12-10-21:08', 1, 0),
(0, 'vmsroot', 'testuser', 'Your sign up for New Years Day Party has been cancelled.', 'testuser cancelled their sign up for New Years Day Party', '2024-12-10-21:08', 0, 0),
(0, 'vmsroot', 'testuser', 'You are now signed up for Singing lessons!', 'Thank you for signing up for Singing lessons!', '2024-12-10-21:08', 0, 0),
(0, 'vmsroot', 'vmsroot', 'Your request to sign up for New Years Day Party has been sent to an admin.', 'Your request to sign up for New Years Day Party will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-10-21:10', 1, 0),
(0, 'vmsroot', 'testuser', 'You are now signed up for Tree Ceremony!', 'Thank you for signing up for Tree Ceremony!', '2024-12-10-21:29', 0, 0),
(0, 'vmsroot', 'testuser', 'You are now signed up for Step VA Presentation!', 'Thank you for signing up for Step VA Presentation!', '2024-12-11-03:22', 0, 0),
(0, 'vmsroot', 'testuser', 'Your request to sign up for Christmas Party has been sent to an admin.', 'Your request to sign up for Christmas Party will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-11-03:36', 0, 0),
(0, 'vmsroot', 'vmsroot', 'Your request to sign up for Christmas Party has been sent to an admin.', 'Your request to sign up for Christmas Party will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-11-03:41', 1, 0),
(0, 'vmsroot', 'testuser', 'Your restricted event signup has been approved', 'You are now signed up for Christmas Party. Congratulations!', '2024-12-11-03:41', 0, 0),
(0, 'vmsroot', 'vmsroot', 'Your request to sign up for Christmas Party has been sent to an admin.', 'Your request to sign up for Christmas Party will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-11-03:41', 1, 0),
(0, 'vmsroot', 'testuser', 'Your sign-up for Christmas Party has been denied.', 'Your sign up for Christmas Party has been denied.', '2024-12-11-03:43', 0, 0),
(0, 'vmsroot', 'testuser', 'Your request to sign up for Christmas Party has been sent to an admin.', 'Your request to sign up for Christmas Party will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-11-03:43', 0, 0),
(0, 'vmsroot', 'morgan', 'You are now signed up for Test!', 'Thank you for signing up for Test!', '2024-12-11-15:41', 0, 0),
(0, 'vmsroot', 'charles', 'You are now signed up for Dancing lessons!', 'Thank you for signing up for Dancing lessons!', '2024-12-11-16:26', 1, 0),
(0, 'vmsroot', 'charles', 'Your request to sign up for Christmas Party has been sent to an admin.', 'Your request to sign up for Christmas Party will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2024-12-11-16:26', 1, 0),
(0, 'vmsroot', 'polack@umw.edu', 'You are now signed up for Gary_Young_Resume_NSWCDD.pdf!', 'Thank you for signing up for Gary_Young_Resume_NSWCDD.pdf!', '2025-01-14-13:34', 1, 0),
(0, 'vmsroot', 'stepvainc@gmail.com', 'Your request to sign up for Finding Nemo Rehearsal has been sent to an admin.', 'Your request to sign up for Finding Nemo Rehearsal will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2025-01-20-16:17', 1, 0),
(0, 'vmsroot', 'stepvainc@gmail.com', 'Your restricted event signup has been approved', 'You are now signed up for Finding Nemo Rehearsal. Congratulations!', '2025-01-20-16:21', 0, 0),
(0, 'vmsroot', 'SaraDowd', 'Your request to sign up for Finding Nemo Rehearsal has been sent to an admin.', 'Your request to sign up for Finding Nemo Rehearsal will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2025-01-20-18:58', 1, 0),
(0, 'vmsroot', 'StrawberryJade', 'Your request to sign up for Finding Nemo Rehearsal has been sent to an admin.', 'Your request to sign up for Finding Nemo Rehearsal will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2025-01-21-12:11', 1, 0),
(0, 'vmsroot', 'ebrenna2', 'Your request to sign up for Finding Nemo Rehearsal has been sent to an admin.', 'Your request to sign up for Finding Nemo Rehearsal will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2025-02-03-12:19', 1, 0),
(0, 'vmsroot', 'vmsroot', 'Your request to sign up for Finding Nemo has been sent to an admin.', 'Your request to sign up for Finding Nemo will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2025-02-07-15:39', 1, 0),
(0, 'vmsroot', 'vmsroot', 'Your request to sign up for jninjn has been sent to an admin.', 'Your request to sign up for jninjn will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2025-02-07-16:05', 0, 0),
(0, 'vmsroot', 'vmsroot', 'Your request to sign up for Finding Nemo Rehearsal has been sent to an admin.', 'Your request to sign up for Finding Nemo Rehearsal will be reviewed by an admin shortly. You will get another notification when you get approved or denied.', '2025-02-07-16:06', 0, 0);

-- 

--
-- Table structure for table `dbpendingsignups`
--

CREATE TABLE `dbpendingsignups` (
  `username` varchar(25) NOT NULL,
  `eventname` varchar(100) NOT NULL,
  `role` varchar(5) NOT NULL,
  `notes` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dbpendingsignups`
--

INSERT INTO `dbpendingsignups` (`username`, `eventname`, `role`, `notes`) VALUES
('vmsroot', '108', 'v', 'Skills: non | Dietary restrictions: ojnjo | Disabilities: jonoj | Materials: knock'),
('vmsroot', '101', 'v', 'Skills: rvwav | Dietary restrictions: varv | Disabilities: var | Materials: arv');

-- 

--
-- Table structure for table `dbpersonhours`
--

CREATE TABLE `dbpersonhours` (
  `personID` varchar(256) NOT NULL,
  `eventID` int(11) NOT NULL,
  `start_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `end_time` timestamp NULL DEFAULT NULL,
  KEY `FkpersonID2` (`personID`),
  KEY `FKeventID3` (`eventID`),
  CONSTRAINT `FKeventID3` FOREIGN KEY (`eventID`) REFERENCES `dbevents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FkpersonID2` FOREIGN KEY (`personID`) REFERENCES `dbpersons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dbpersonhours`
--
/*
INSERT INTO `dbpersonhours` (`personID`, `eventID`, `start_time`, `end_time`) VALUES
('test', 31, '2024-11-23 22:00:00', '2024-11-23 23:00:00'),
('test', 30, '2024-11-23 23:00:00', '2024-11-24 00:30:00'),
('test', 30, '2024-11-23 20:05:00', '2024-11-23 20:10:00'),
('test', 72, '2024-12-05 19:20:00', '2024-12-05 20:40:00'),
('test', 72, '2024-12-05 20:21:07', '2024-12-05 20:56:24'),
('test', 72, '2024-12-05 21:00:00', '2024-12-05 22:30:00'),
('test', 31, '2024-11-23 23:30:00', '2024-11-24 01:00:00'),
('testuser', 82, '2024-12-11 03:29:00', '2024-12-11 04:00:00'),
('testuser', 83, '2024-12-11 15:30:00', '2024-12-11 16:00:00'),
('testuser', 83, '2024-12-11 22:05:43', '2024-12-11 22:05:46'),
('testuser', 83, '2024-12-11 22:16:44', '2024-12-11 22:16:49'),
('testuser', 83, '2024-12-11 22:48:00', '2024-12-11 23:00:00');

*/
-- 

-- Table Structure for 'dbvideos'
CREATE TABLE dbvideos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    url VARCHAR(2083) NOT NULL,
    title VARCHAR(255) NOT NULL,
    synopsis TEXT,
    type INT
);






--
-- Dumping data for table `dbpersons`
--

INSERT INTO `dbpersons` (`id`, `start_date`, `venue`, `first_name`, `last_name`, `street_address`, `city`, `state`, `zip_code`, `phone1`, `phone1type`, `emergency_contact_phone`, `emergency_contact_phone_type`, 
`birthday`, `email`, `emergency_contact_first_name`, `contact_num`, `emergency_contact_relation`, `contact_method`, `type`, `status`, `notes`, `password`, `profile_pic`, `gender`, `tshirt_size`, 
`how_you_heard_of_stepva`, `sensory_sensitivities`, `disability_accomodation_needs`, `school_affiliation`, `race`, `preferred_feedback_method`, `hobbies`, `professional_experience`, `archived`, 
`emergency_contact_last_name`, `photo_release`, `photo_release_notes`, `training_complete`, `training_date`, `orientation_complete`, `orientation_date`, `background_complete`, `background_date`, `skills`, 
`networks`, `contributions`, `familyid`, `profile_feature`, `identification_preference`, `headshot_publish`, `likeness_usage`) VALUES
('vmsroot', 'N/A', 'portland', 'vmsroot', '', 'N/A', 'N/A', 'VA', 'N/A', '', 'N/A', 'N/A', 'N/A', 'N/A', 'vmsroot', 'N/A', 'N/A', 'N/A', 'N/A', '', 'N/A', 'N/A', 
'$2y$10$.3p8xvmUqmxNztEzMJQRBesLDwdiRU3xnt/HOcJtsglwsbUk88VTO', '', '', '', '', '', '', '', '', '', '', '', 0, '', '', '', 0, '', 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0,'1');
/* ('aaa', '2024-12-09', 'n/a', 'aaa', 'aaa', 'a', 'a', 'VA', '33333', '1231231233', 'cellphone', '2342342344', 'cellphone', '2001-07-07', 'a@a.a', 'a', 'n/a', 'a', 'n/a', 'volunteer', 'Active', 'n/a', '$2y$10$5s8HaYX98.3Wlsr7hpiVH.UGtxt0YqSGCrcWFsV5McmiN9wjUZNBu', 'n/a', 'gender', 'xs', '', 'sensory_sensitivities', '', 'a', 'race', 'No preference', '', '', 0, 'a', 'Restricted', 'a', 0, '', 0, '', 0, ''),
--('Abby Floyd', '2025-01-09', 'n/a', 'Abigail', 'Floyd', '5425 Rainwood Dr', 'Fredericksburg', 'VA', '22407', '5403226396', 'cellphone', '5404290350', 'cellphone', '2008-03-08', 'amfloyd14@icloud.com', 'Melissa', 'n/a', 'Mother', 'n/a', 'v', 'Active', 'n/a', '$2y$10$OreonRBUiYTXffapXDSzN.lt04IVsOrOiQ27UsT1XBZj5IctrXetG', 'n/a', 'gender', 'xl', '', 'sensory_sensitivities', '', 'Courtland Highschool', 'race', 'no-preference', '', '', 0, 'Floyd', 'Not Restricted', 'N/A', 1, '2022-06-11', 1, '2022-06-11', 1, '2022-06-11'),
--('AbbyGriff', '2025-01-09', 'n/a', 'Abby', 'Griffin', '5417, Holley Oak Lane', 'Fredericksburg', 'VA', '22407', '5406612606', 'cellphone', '5408348825', 'cellphone', '2010-09-01', 'abigailfgriff@gmail.com', 'Pam', 'n/a', 'Mother', 'n/a', 'volunteer', 'Active', 'n/a', '$2y$10$c901gH6WuN9DgxmwodzOKeOgpdu87OlTTXEvj2/Ejt/SIIZpSc9si', 'n/a', 'gender', 's', 'My sister (Sarah Garner)', 'sensory_sensitivities', 'None', 'Homeschooled', 'race', 'text', 'I know how to run a mic table and can assist with a sound board, I have been in 15 musicals, I am a babysitter, and I know how to spike a stage', 'Yes; I volunteer as a Sunday school assistant.', 0, 'Griffin', 'Not Restricted', 'None', 1, '', 1, '', 1, ''),
--('addison_fiore', '2025-01-09', 'n/a', 'Addison', 'Fiore', '10804 Cinnamon Teal Drive', 'Spotsylvania', 'VA', '22553', '5403720591', 'cellphone', '5409031781', 'cellphone', '2007-11-06', 'addisonfiore8@gmail.com', 'Jennifer', 'n/a', 'Mother', 'n/a', 'volunteer', 'Active', 'n/a', '$2y$10$0QK4ippSoaT/e2VZ3NaG7OcqFJDps2uFz22.1NjJ.iuD7sbuyLUYW', 'n/a', 'gender', 'm', '', 'sensory_sensitivities', '', 'Riverbend Highschool', 'race', 'text', '', '', 0, 'Fiore', 'Restricted', 'N/A', 1, '2023-07-11', 1, '2023-07-17', 1, '2023-06-07'),
--('Amwages13', '2025-01-13', 'n/a', 'Lexi', 'Wages', '3 Rodeo Ct', 'Fredericksburg', 'VA', '22407', '5403599086', 'cellphone', '5402875446', 'cellphone', '2011-11-18', 'leximariewages@gmail.com', 'Laurie', 'n/a', 'Mother', 'n/a', 'volunteer', 'Active', 'n/a', '$2y$10$PD0TAmsArH3AF79GeiPKuu/t3b2KwoKnixcU2OvDqA/6IjMxEsra.', 'n/a', 'gender', 's', 'Sister', 'sensory_sensitivities', 'No', 'Battlefield Middle', 'race', 'text', 'Crotchet', '', 0, 'Wages', 'Not Restricted', 'N/a', 1, '2024-12-27', 1, '2024-12-27', 0, ''),
--('ascrivani3', '2025-01-09', 'n/a', 'Amelia', 'Scrivani', '12565 Spotswood Furnace Rd', 'Fredericksburg', 'VA', '22407', '7185141845', 'cellphone', '7183546470', 'cellphone', '2004-08-09', 'ascrivani3@gmail.com', 'Michele', 'n/a', 'Mother', 'n/a', 'volunteer', 'Active', 'n/a', '$2y$10$RfIm4IswPvtW656I6h2cR.czd4uCKldaSsKB5zCiw8642lfimAFj.', 'n/a', 'gender', 'm', 'My brother participates in various Step VA activities', 'sensory_sensitivities', '', 'N/A', 'race', 'text', '', 'I have spent many years volunteering with my old elementary school’s annual chorus performance, every year they put on a musical.', 0, 'Scrivani', 'Not Restricted', 'N/A', 0, '', 0, '', 0, ''),
--('brianna@gmail.com', '2024-01-22', 'portland', 'Brianna', 'Wahl', '212 Latham Road', 'Mineola', 'VA', '11501', '1234567890', 'cellphone', '', '', '2004-04-04', 'brianna@gmail.com', 'Mom', '1234567890', 'Mother', 'text', 'admin', 'Active', '', '$2y$10$jNbMmZwq.1r/5/oy61IRkOSX4PY6sxpYEdWfu9tLRZA6m1NgsxD6m', '', 'Female', '', '', '', '', '', '', '', '', '', 0, '', '', '', 0, '', 0, '', 0, ''),
--('bum@gmail.com', '2024-01-24', 'portland', 'bum', 'bum', '1345 Strattford St.', 'Mineola', 'VA', '22401', '1234567890', 'home', '', '', '1111-11-11', 'bum@gmail.com', 'Mom', '1234567890', 'Mom', 'text', 'admin', 'Active', '', '$2y$10$Ps8FnZXT7d4uiU/R5YFnRecIRbRakyVtbXP9TVqp7vVpuB3yTXFIO', '', 'Male', '', '', '', '', '', '', '', '', '', 0, '', '', '', 0, '', 0, '', 0, ''),
--('charles', '2024-12-10', 'n/a', 'Charles', 'Wilt', 'Street Address', 'Fredericksburg', 'VA', '22405', '1231231234', 'cellphone', '1231231234', 'cellphone', '2004-06-15', 'charles@gmail.com', 'Michael', 'n/a', 'Father', 'n/a', 'volunteer', 'Active', 'n/a', '$2y$10$BhurBvyEs9hTa7sIeZEqHeuE0aAXSGX/CcwMw7y/4dHt0ztL1MjMO', 'n/a', 'gender', 's', '', 'sensory_sensitivities', '', 'N/A', 'race', 'No preference', '', '', 0, 'Wilt', 'Not Restricted', 'N/A', 0, '', 0, '', 0, ''),
--('christitowle', '2025-01-09', 'n/a', 'Christi', 'Towle', '201 New Hope Church Road', 'Fredericksburg', 'VA', '22405', '8044415060', 'cellphone', '8044415060', 'cellphone', '1972-12-11', 'towlefamily@yahoo.com', 'Jason', 'n/a', 'Spouse', 'n/a', 'volunteer', 'Active', 'n/a', '$2y$10$QKAHn4eKKL8qgF2gDGjNiektJolio9YlhMByUyLXE.GfJVSygOAli', 'n/a', 'gender', 'm', 'A friend', 'sensory_sensitivities', 'No', 'N/A', 'race', 'No preference', 'gardening, crafts', 'Yes', 0, 'Towle', 'Not Restricted', 'N/A', 0, '', 1, '', 1, ''),
--('derp', '2024-12-09', 'n/a', 'a', 'a', 'a', 'a', 'VA', '12323', '1231231234', 'home', '1231231234', 'home', '2024-12-04', 'awe@gmail.com', 'a', 'n/a', 'a', 'n/a', 'volunteer', 'Active', 'n/a', '$2y$10$gYsD0Y1dwlDK4SZH59VGuOlqu78JwLrSXViebTkX0KxMOQgWIR7c.', 'n/a', 'gender', 's', '', 'sensory_sensitivities', '', 'a', 'race', 'No preference', '', '', 0, 'a', 'Restricted', 'a', 0, '', 0, '', 0, ''),
--('ebrenna2', '2025-02-03', 'n/a', 'Emma', 'Brennan', '212 Willis St', 'Fredericksburg', 'VA', '22401', '7035099647', 'cellphone', '7032965189', 'cellphone', '2002-09-23', 'em.brenn@yahoo.com', 'Laura', 'n/a', 'Mother', 'n/a', 'volunteer', 'Active', 'n/a', '$2y$10$3hIpyIl79iyRTb.edM.uEuV8aT9t5v4juO3vXmpcIYULPokjpftKK', 'n/a', 'gender', 'm', '', 'sensory_sensitivities', '', 'UMW', 'race', 'No preference', '', '', 0, 'Brennan', 'Not Restricted', 'N/A', 1, '2025-02-03', 1, '2025-02-03', 1, '2025-02-03'),
--('fredastaire', '2024-11-18', 'n/a', 'Fred', 'Astaire', '11 Dance Avenue', 'Stafford', 'VA', '12345', '1234567890', 'cellphone', '2222222221', 'cellphone', '1899-05-10', 'fredastaire@myemail.com', 'Fred Jr.', 'n/a', 'Son', 'n/a', 'v', 'Active', 'n/a', '$2y$10$VUZObvA3Cy69ykkohegJYevjJU3DFlZbgmfTSPzee7TMPRSMMg9fG', 'n/a', 'gender', 'm', 'A little bird told me.', 'sensory_sensitivities', 'No', 'N/A', 'race', 'email', 'Dance!', 'Yes', 0, 'Astaire', 'Not Restricted', 'N/A', 0, '', 0, '', 0, ''),
--('jake_demo', '2024-11-24', 'n/a', 'Jake', 'Furman', '1234 Street', 'Fredericksburg', 'VA', '10001', '5555555555', 'cellphone', '4444444444', 'cellphone', '2002-01-31', 'jake@gmail.com', 'Mom', 'n/a', 'Mom', 'n/a', 'volunteer', 'Active', 'n/a', '$2y$10$UNhNSABqTedXO.fWLy7eduhDVsdNp9GbkdnkR05oyjZnYPe9XjExu', 'n/a', 'gender', 'l', '', 'sensory_sensitivities', '', 'UMW', 'race', '', '', '', 0, 'Furman', 'Not Restricted', 'no', 0, '', 0, '', 0, ''),
--('lucy', '2024-11-25', 'n/a', 'Lucy', 'Pevensie', '10 London Avenue', 'Stafford', 'VA', '12345', '1234567890', 'home', '0987654321', 'cellphone', '1901-01-01', 'lucy@narnia.com', 'Peter', 'n/a', 'Brother', 'n/a', 'volunteer', 'Active', 'n/a', '$2y$10$VQ5Za13gNAn/DoAJzwG.j.zMhL1YBjOpsJclMJqfSF1XKxMp2eS9S', 'n/a', 'gender', 's', 'Aslan', 'sensory_sensitivities', 'No', 'N/A', 'race', 'email', 'Arts and crafts', 'Yes', 0, 'Pevensie', 'Restricted', 'N/A', 0, '', 0, '', 0, ''),
--('mom@gmail.com', '2024-01-22', 'portland', 'Lorraine', 'Egan', '212 Latham Road', 'Mineola', 'NY', '11501', '5167423832', 'home', '', '', '1910-10-10', 'mom@gmail.com', 'Mom', '5167423832', 'Dead', 'phone', 'admin', 'Active', '', '$2y$10$of1CkoNXZwyhAMS5GQ.aYuAW1SHptF6z31ONahnF2qK4Y/W9Ty2h2', '', 'Male', '', '', '', '', '', '', '', '', '', 0, '', '', '', 0, '', 0, '', 0, ''),
--('morgan', '2024-12-02', 'n/a', 'Morgan', 'Harper', '123 Street', 'Fredericksburg', 'VA', '10001', '5555555555', 'cellphone', '5555555555', 'cellphone', '2003-11-24', 'morgan@gmail.com', 'Christine', 'n/a', 'Mom', 'n/a', 'v', 'Active', 'n/a', '$2y$10$klnM0EjO78i3ifPFMU3YQe6YXq14W3RpmSUsP1.IP0f6wVE6ExYoe', 'n/a', 'gender', 'm', '', 'sensory_sensitivities', '', 'USC', 'race', 'email', '', 'Yessss', 0, 'Harper', 'Not Restricted', 'N/A', 1, '2024-12-04', 1, '2024-12-03', 1, '2024-12-02'),
--('oliver@gmail.com', '2024-01-22', 'portland', 'Oliver', 'Wahl', '1345 Strattford St.', 'Fredericksburg', 'VA', '22401', '1234567890', 'home', '', '', '2011-11-11', 'oliver@gmail.com', 'Mom', '1234567890', 'Mother', 'text', 'admin', 'Active', '', '$2y$10$tgIjMkXhPzdmgGhUgbfPRuXLJVZHLiC0pWQQwOYKx8p8H8XY3eHw6', '', 'Other', '', '', '', '', '', '', '', '', '', 0, '', '', '', 0, '', 0, '', 0, ''),
--('peter@gmail.com', '2024-01-22', 'portland', 'Peter', 'Polack', '1345 Strattford St.', 'Mineola', 'VA', '12345', '1234567890', 'cellphone', '', '', '1968-09-09', 'peter@gmail.com', 'Mom', '1234567890', 'Mom', 'email', 'admin', 'Active', '', '$2y$10$j5xJ6GWaBhnb45aktS.kruk05u./TsAhEoCI3VRlNs0SRGrIqz.B6', '', 'Male', '', '', '', '', '', '', '', '', '', 0, '', '', '', 0, '', 0, '', 0, ''),
--('polack@um.edu', '2024-01-22', 'portland', 'Jennifer', 'Polack', '15 Wallace Farms Lane', 'Fredericksburg', 'VA', '22406', '1234567890', 'cellphone', '', '', '1970-05-01', 'polack@um.edu', 'Mom', '1234567890', 'Mom', 'email', 'admin', 'Active', '', '$2y$10$mp18j4WqhlQo7MTeS/9kt.i08n7nbt0YMuRoAxtAy52BlinqPUE4C', '', 'Female', '', '', '', '', '', '', '', '', '', 0, '', '', '', 0, '', 0, '', 0, ''),
--('polack@umw.edu', '2025-01-09', 'n/a', 'Jennifer', 'ADMIN', '15 Wallace Farms Lane', 'Fredericksburg', 'VA', '22406', '5402959700', 'cellphone', '5402959700', 'cellphone', '1970-05-01', 'polack@umw.edu', 'Jennifer', 'n/a', 'Me', 'n/a', 'volunteer', 'Active', 'n/a', '$2y$10$CxMpQDWPyURnla9pb8FvveQSRrMBU7.zAB.ZbdHwK1P/suPuwcy0O', 'n/a', 'gender', 'l', '', 'sensory_sensitivities', '', 'UMW', 'race', 'No preference', '', '', 0, 'Polack', 'Restricted', 'NA', 0, '', 0, '', 0, ''),
--('s', '2024-11-18', 'n/a', 'a', 'hj', 'f', 'f', 'VA', '12333', '1231231234', 'home', '1231231234', 'cellphone', '2000-12-20', 'jf@gmail.com', 'e', 'n/a', 'e', 'n/a', 'volunteer', 'Active', 'n/a', '$2y$10$7ml7KedmERvRYpflzuMAHOsXdssqzMo5FidkOtekjj9R6u1OdTXqy', 'n/a', 'gender', 's', '', 'sensory_sensitivities', '', 's', 'race', '', '', '', 0, 'e', 'Restricted', 's', 0, '', 0, '', 0, ''),
--('SaraDowd', '2025-01-13', 'n/a', 'Sara', 'Dowd', '11 Barlow House Court', 'Stafford', 'VA', '22554', '8582549611', 'cellphone', '8582548568', 'cellphone', '1978-01-23', 'sarazonadowd@gmail.com', 'Daniel', 'n/a', 'Spouse', 'n/a', 'volunteer', 'Active', 'n/a', '$2y$10$BQ4n2HGpgxfaFnBf0HexveU8I0ppdINNXvhZdynQyOiaitOnP6dw6', 'n/a', 'gender', 'm', '', 'sensory_sensitivities', '', 'n/a', 'race', 'email', '', '', 0, 'Dowd', 'Not Restricted', 'n/a', 1, '', 1, '', 1, ''),
--('someInfo2', '2024-11-24', 'n/a', 'nah', 'whywouldi', '0', '0', 'VA', '00000', '0000000000', 'work', '0000000000', 'work', '0001-01-01', '0@0.o', 'nah', 'n/a', 'a', 'n/a', 'volunteer', 'Active', 'n/a', '$2y$10$umJj4HXb5tpj79rc4Vj8hu5wCK7BSjMEDdtX7sfdGjMqjt5.ap3bq', 'n/a', 'gender', 'm', '', 'sensory_sensitivities', '', 'why would i be', 'race', '', '', '', 0, 'whywouldi', 'Not Restricted', 'a', 0, '', 0, '', 0, ''),
--('stepvainc@gmail.com', '2024-12-17', 'n/a', 'Jan', 'Monroe', '12419 Toll House Rd.', 'SPOTSYLVANIA', 'VA', '22551', '7575351963', 'cellphone', '7575351967', 'cellphone', '2000-12-09', 'stepvainc@gmail.com', 'Mike', 'n/a', 'spouse', 'n/a', 'volunteer', 'Active', 'n/a', '$2y$10$mOC.B5elQp8HZhdkNhR/V.jjNBwcsTzQdjG14Aia1jP.8XkNSWj0u', 'n/a', 'gender', 's', '', 'sensory_sensitivities', 'no', 'N/a', 'race', 'text', 'yoga, music', 'yes', 0, 'Monroe', 'Not Restricted', 'n/a', 1, '2013-10-13', 1, '2013-10-13', 1, ''),
--('StrawberryJade', '2025-01-09', 'n/a', 'Jade', 'Sergi', '2449 Harpoon Drive', 'Stafford', 'VA', '22554', '5406994590', 'cellphone', '3014120327', 'cellphone', '2005-04-21', 'jtsergi42@gmail.com', 'Carol', 'n/a', 'Mother', 'n/a', 'volunteer', 'Active', 'n/a', '$2y$10$Q3bVGR6B6E4Ibz7k9vryMO5mFJvtkLO418iADpEonhKdt7vzb5R22', 'n/a', 'gender', 'm', '', 'sensory_sensitivities', '', 'N/A', 'race', 'text', '', '', 0, 'Yeh', 'Not Restricted', 'N/A', 1, '2024-11-20', 1, '2019-05-30', 1, '2023-05-29'),
--('test', '2024-11-20', 'n/a', 'Harry', 'Potter', '123 Apple St.', 'Fredericksburg', 'VA', '22003', '1231231234', 'cellphone', '4324324321', 'home', '2000-07-07', 'test@gmail.com', 'Ron', 'n/a', 'Friend', 'n/a', 'v', 'Active', 'n/a', '$2y$10$3qNoA1RwCbO9/1eHev.T0OCdhfBwaS9cmzGVFdCD4CFqmyEPjgkbm', 'n/a', 'gender', 'm', 'I didn&amp;#039;t', 'sensory_sensitivities', 'aoei', 'Hogwarts', 'race', 'text', 'aoei', 'aoei', 0, 'Weasley', 'Not Restricted', ':0', 0, '', 0, '', 0, ''),
--('testuser', '2024-11-25', 'n/a', 'Jane', 'Doe', '123 Main Street', 'Fredericksburg', 'VA', '22401', '5555555555', 'cellphone', '6666666666', 'cellphone', '1999-01-01', 'test@mail.com', 'Ron', 'n/a', 'Friend', 'n/a', 'v', 'Active', 'n/a', '$2y$10$kfaLBXEYKBmdzaO6x7KtBuQeXu5o8Wof2MaR5vwJ44aoqPwMsgkIa', 'n/a', 'gender', 'l', 'A little birdie told me', 'sensory_sensitivities', 'Nah', 'N/A', 'race', 'text', 'Being Rad', 'Nah', 0, 'Swanson', 'Not Restricted', 'N/A', 0, '', 0, '', 0, ''),
--('testuser2', '2024-12-11', 'n/a', 'Jane', 'Doe', '123 Main Street', 'Fredericksburg', 'VA', '22401', '5555555555', 'cellphone', '6666666666', 'cellphone', '2003-08-26', 'test2@mail.com', 'Ron', 'n/a', 'Father', 'n/a', 'volunteer', 'Active', 'n/a', '$2y$10$mqnpfidPOnnZB1TKDrUa2.cWEcfrOcfslsxdaDG2o6ouWnqTCnFzK', 'n/a', 'gender', 'l', 'Test', 'sensory_sensitivities', 'NO', 'No', 'race', 'No preference', 'No', 'No', 0, 'Swanson', 'Not Restricted', 'NO', 1, '2024-12-10', 0, '', 0, ''),
--('Thall1', '2025-01-09', 'n/a', 'Teresa', 'Hall', '119 Garfield Avenue', 'Colonial Beach', 'VA', '22443', '5405977489', 'cellphone', '5407608128', 'cellphone', '1969-05-07', 'tlomeara3@gmail.com', 'Tim', 'n/a', 'Spouse', 'n/a', 'volunteer', 'Active', 'n/a', '$2y$10$.Er980h41trC1NRObt4U5.S8YKbORWhwC2.44tHoLLBnmSmMa8hKK', 'n/a', 'gender', 'm', '', 'sensory_sensitivities', '', 'N/A', 'race', 'text', '', '', 0, 'Hall', 'Not Restricted', 'N/A', 1, '', 0, '', 0, ''),
--('tom@gmail.com', '2024-01-22', 'portland', 'tom', 'tom', '1345 Strattford St.', 'Mineola', 'NY', '12345', '1234567890', 'home', '', '', '1920-02-02', 'tom@gmail.com', 'Dad', '9876543210', 'Father', 'phone', 'admin', 'Active', '', '$2y$10$1Zcj7n/prdkNxZjxTK1zUOF7391byZvsXkJcN8S8aZL57sz/OfxP.', '', 'Male', '', '', '', '', '', '', '', '', '', 0, '', '', '', 0, '', 0, '', 0, ''),
--('username', '2024-11-18', 'n/a', 'cool', 'cool', 'weqwe', 'qewq', 'VA', '12312', '1231231234', 'home', '1231232134', 'cellphone', '2024-11-01', 'cool@gmail.com', 'cool', 'n/a', 'cool', 'n/a', 'v', 'Inactive', 'n/a', '$2y$10$yz35FMhhRl.hVIyjhvASJeQ.sp0lc7hzQQJJRBHH00spfTOlQJ4Cy', 'n/a', 'gender', 'm', '', 'sensory_sensitivities', '', 'w', 'race', 'email', '', '', 0, 'cool', 'Not Restricted', 'super awesome', 0, '', 0, '', 0, ''),
--('usernameusername', '2024-12-09', 'n/a', 'Noah', 'Stafford', 'My address', 'City', 'VA', '22405', '1231231234', 'cellphone', '1231231234', 'cellphone', '2024-12-01', 'email@gmail.com', 'Contact', 'n/a', 'Mom', 'n/a', 'volunteer', 'Active', 'n/a', '$2y$10$uGbO0uD4CFwN0ewoqGG8T.9PT.1F8pOBSJVOKXkvNSlRSjAANMhOK', 'n/a', 'gender', 'xs', '', 'sensory_sensitivities', '', 'N/A', 'race', 'No preference', '', '', 0, 'Lastname', 'Not Restricted', 'N/A', 0, '', 0, '', 0, ''),
--('work', '2024-12-10', 'n/a', 'a', 'a', 'a', 'a', 'VA', '11111', '1231231234', 'cellphone', '1231231234', 'cellphone', '2024-12-04', 'a@gmail.com', 'a', 'n/a', 'a', 'n/a', 'volunteer', 'Active', 'n/a', '$2y$10$ChUa935f6QE8RtHI4p2vX.WtCeoVPPYQhgVNFfgnvLPo0mVJGXbCi', 'n/a', 'gender', 's', '', 'sensory_sensitivities', '', 'a', 'race', 'No preference', '', '', 0, 'a', 'Restricted', 'a', 0, '', 0, '', 0, '');
*/

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

/* This sets up the connections for family member stuff in the database */

CREATE TABLE dbfamilyleader (
    familyid INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    FOREIGN KEY (username) REFERENCES dbpersons(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE dbfamilymember (
    username VARCHAR(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    familyid INT NOT NULL,
    FOREIGN KEY (username) REFERENCES dbpersons(id) ON DELETE CASCADE,
    FOREIGN KEY (familyid) REFERENCES dbfamilyleader(familyid) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `dbattendance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `person_id` varchar(50) NOT NULL,
  `event_id` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `attendance` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;