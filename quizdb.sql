-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 03, 2021 at 06:36 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `quizdb`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `getResultsBelowForty` ()  BEGIN
		SELECT student.`Name`, quizz.`QuizName`, takesstudent.`TakesID`, takesstudent.`Score`
		FROM students, takesstudent, quiz
		WHERE quiz.`ID` = takesstudent.`QuizID` AND student.`ID` = takesstudent.`StudentID` AND Score < 40;
	END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `QuizID` int(11) NOT NULL,
  `QuestionNumber` int(11) NOT NULL,
  `Question` varchar(255) NOT NULL,
  `AnswerOne` varchar(255) NOT NULL,
  `AnswerTwo` varchar(255) NOT NULL,
  `AnswerThree` varchar(255) NOT NULL,
  `AnswerFour` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `quiz`
--

CREATE TABLE `quiz` (
  `ID` int(11) NOT NULL,
  `QuizName` varchar(255) NOT NULL,
  `StaffID` int(11) NOT NULL,
  `Availability` varchar(255) NOT NULL,
  `TimeLimit` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Triggers `quiz`
--
DELIMITER $$
CREATE TRIGGER `before_quiz_delete` AFTER DELETE ON `quiz` FOR EACH ROW BEGIN
		INSERT INTO delete_quiz_trigger
		SET
      		StaffID = OLD.StaffID,
     	 	QuizID = OLD.ID,
     	 	DateCurrent = curdate(),
     	 	TimeCurrent = curtime();
	END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `Email` varchar(255) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `takesstaff`
--

CREATE TABLE `takesstaff` (
  `TakesID` int(11) NOT NULL,
  `StaffEmail` varchar(255) NOT NULL,
  `QuizID` int(11) NOT NULL,
  `Score` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `takesstudent`
--

CREATE TABLE `takesstudent` (
  `TakesID` int(11) NOT NULL,
  `StudentID` int(11) NOT NULL,
  `QuizID` int(11) NOT NULL,
  `Score` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`QuizID`,`QuestionNumber`);

--
-- Indexes for table `quiz`
--
ALTER TABLE `quiz`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `staff_ibfk_1` (`StaffID`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `takesstaff`
--
ALTER TABLE `takesstaff`
  ADD PRIMARY KEY (`TakesID`),
  ADD KEY `staffid_ibfk_3` (`StaffEmail`),
  ADD KEY `quizid_ibfk_3` (`QuizID`);

--
-- Indexes for table `takesstudent`
--
ALTER TABLE `takesstudent`
  ADD PRIMARY KEY (`TakesID`),
  ADD KEY `student_ibfk_1` (`StudentID`),
  ADD KEY `quizi_ibfk_1` (`QuizID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `quiz`
--
ALTER TABLE `quiz`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `takesstaff`
--
ALTER TABLE `takesstaff`
  MODIFY `TakesID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `takesstudent`
--
ALTER TABLE `takesstudent`
  MODIFY `TakesID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `quiz_ibfk_2` FOREIGN KEY (`QuizID`) REFERENCES `quiz` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `quiz`
--
ALTER TABLE `quiz`
  ADD CONSTRAINT `staff_ibfk_1` FOREIGN KEY (`StaffID`) REFERENCES `staff` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `takesstaff`
--
ALTER TABLE `takesstaff`
  ADD CONSTRAINT `quizid_ibfk_3` FOREIGN KEY (`QuizID`) REFERENCES `quiz` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `staffid_ibfk_3` FOREIGN KEY (`StaffEmail`) REFERENCES `staff` (`Email`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `takesstudent`
--
ALTER TABLE `takesstudent`
  ADD CONSTRAINT `quizi_ibfk_1` FOREIGN KEY (`QuizID`) REFERENCES `quiz` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`StudentID`) REFERENCES `student` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
