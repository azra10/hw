-- phpMyAdmin SQL Dump
-- version 2.8.0.1
-- http://www.phpmyadmin.net
-- 
-- Host: custsql-ipg101.eigbox.net
-- Generation Time: Nov 17, 2018 at 08:56 PM
-- Server version: 5.6.41
-- PHP Version: 4.4.9
-- 
-- Database: `issadmin_lh3i_cxopdckmhsrrlmvqxr`
-- 

-- 
-- Table structure for table `lh3i_iss_assignment`
-- 

CREATE TABLE IF NOT EXISTS `lh3i_iss_assignment` (
  `ID` bigint(20) unsigned NOT NULL,
  `PossiblePoints` int(10) DEFAULT '10',
  `DueDate` date NOT NULL,
  `Category` varchar(20) NOT NULL,
  `ClassID` bigint(20) DEFAULT '0',
  `AssignmentTypeID` int(11) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  KEY `iss_assignment_AssignmentTypeID_FK` (`AssignmentTypeID`),
  KEY `iss_assignment_ClassID_FK` (`ClassID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table structure for table `lh3i_iss_assignment_type`
-- 

CREATE TABLE IF NOT EXISTS `lh3i_iss_assignment_type` (
  `AssignmentTypeID` int(11) NOT NULL AUTO_INCREMENT,
  `ClassID` int(11) NOT NULL,
  `TypeName` varchar(100) NOT NULL,
  `TypePercentage` int(5) NOT NULL,
  PRIMARY KEY (`AssignmentTypeID`),
  KEY `iss_assignmenttype_ClassID_FK` (`ClassID`)
) ENGINE=InnoDB AUTO_INCREMENT=282 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `lh3i_iss_class`
-- 

CREATE TABLE IF NOT EXISTS `lh3i_iss_class` (
  `ClassID` int(11) NOT NULL,
  `RegistrationYear` varchar(10) NOT NULL,
  `ISSGrade` varchar(2) NOT NULL DEFAULT 'KG',
  `Subject` varchar(100) NOT NULL DEFAULT 'Islamic Studies',
  `Suffix` varchar(50) DEFAULT NULL,
  `Category` varchar(10) NOT NULL DEFAULT 'kgis',
  `Status` varchar(10) NOT NULL DEFAULT 'active',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ClassID`),
  KEY `iss_class_RegistrationYear_FK` (`RegistrationYear`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table structure for table `lh3i_iss_grading_period`
-- 

CREATE TABLE IF NOT EXISTS `lh3i_iss_grading_period` (
  `GradingPeriodID` int(11) NOT NULL AUTO_INCREMENT,
  `RegistrationYear` varchar(10) NOT NULL,
  `GradingPeriod` int(11) NOT NULL,
  `StartDate` date NOT NULL,
  `EndDate` date NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`GradingPeriodID`),
  UNIQUE KEY `RegistrationYear` (`RegistrationYear`,`GradingPeriod`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table structure for table `lh3i_iss_scale`
-- 

CREATE TABLE IF NOT EXISTS `lh3i_iss_scale` (
  `ScaleID` int(11) NOT NULL AUTO_INCREMENT,
  `ClassID` int(11) NOT NULL,
  `ScaleName` varchar(100) NOT NULL,
  `ScalePercentage` int(5) NOT NULL,
  PRIMARY KEY (`ScaleID`),
  KEY `iss_scale_ClassID_FK` (`ClassID`)
) ENGINE=InnoDB AUTO_INCREMENT=124 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `lh3i_iss_score`
-- 

CREATE TABLE IF NOT EXISTS `lh3i_iss_score` (
  `StudentViewID` int(11) NOT NULL,
  `AssignmentID` bigint(20) unsigned NOT NULL,
  `Score` float(5,2) NOT NULL DEFAULT '0',
  `Comment` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`StudentViewID`,`AssignmentID`),
  KEY `iss_score_AssignmentID_FK` (`AssignmentID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `lh3i_iss_student`
-- 

CREATE TABLE IF NOT EXISTS `lh3i_iss_student` (
  `StudentViewID` int(11) NOT NULL DEFAULT '0',
  `RegistrationYear` varchar(9) DEFAULT NULL,
  `ParentID` int(11) DEFAULT NULL,
  `FatherFirstName` varchar(100) DEFAULT NULL,
  `FatherLastName` varchar(100) DEFAULT NULL,
  `FatherEmail` varchar(100) DEFAULT NULL,
  `MotherFirstName` varchar(100) DEFAULT NULL,
  `MotherLastName` varchar(100) DEFAULT NULL,
  `MotherEmail` varchar(100) DEFAULT NULL,
  `StudentID` int(11) DEFAULT NULL,
  `StudentFirstName` varchar(35) DEFAULT NULL,
  `StudentLastName` varchar(35) DEFAULT NULL,
  `StudentGender` varchar(1) DEFAULT NULL,
  `StudentStatus` varchar(10) DEFAULT NULL,
  `StudentEmail` varchar(100) DEFAULT NULL,
  `ISSGrade` varchar(2) DEFAULT NULL,
  `SchoolEmail` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`StudentViewID`),
  KEY `iss_student_RegistrationYear_FK` (`RegistrationYear`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table structure for table `lh3i_iss_userclassmap`
-- 

CREATE TABLE IF NOT EXISTS `lh3i_iss_userclassmap` (
  `UserID` int(11) NOT NULL,
  `ClassID` int(11) NOT NULL,
  `Access` varchar(10) NOT NULL DEFAULT 'read',
  `LastLogin` datetime DEFAULT NULL,
  PRIMARY KEY (`UserID`,`ClassID`),
  KEY `iss_userclassmap_ClassID_FK` (`ClassID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table structure for table `lh3i_iss_userstudentmap`
-- 

CREATE TABLE IF NOT EXISTS `lh3i_iss_userstudentmap` (
  `UserID` bigint(20) NOT NULL,
  `StudentID` bigint(20) NOT NULL,
  `Access` varchar(10) NOT NULL DEFAULT 'read',
  `LastLogin` datetime DEFAULT NULL,
  PRIMARY KEY (`UserID`,`StudentID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table structure for table `lh3i_issv_class_assignments`
-- 

DROP TABLE IF EXISTS lh3i_issv_class_assignments;
CREATE ALGORITHM=UNDEFINED DEFINER=`clgtvldtvxadnshf`@`10.%` SQL SECURITY DEFINER VIEW `lh3i_issv_class_assignments` AS select `d`.`ClassID` AS `ClassID`,`d`.`DueDate` AS `DueDate`,`d`.`Category` AS `Category`,`d`.`PossiblePoints` AS `PossiblePoints`,`d`.`AssignmentTypeID` AS `AssignmentTypeID`,`c`.`ISSGrade` AS `ISSGrade`,`c`.`RegistrationYear` AS `RegistrationYear`,`c`.`Subject` AS `Subject`,`p`.`ID` AS `ID`,`p`.`post_author` AS `post_author`,`p`.`post_date` AS `post_date`,`p`.`post_content` AS `post_content`,`p`.`post_title` AS `post_title`,`p`.`post_status` AS `post_status`,`p`.`post_name` AS `post_name`,`p`.`guid` AS `guid`,`p`.`post_type` AS `post_type` from ((`lh3i_iss_assignment` `d` join `lh3i_posts` `p`) join `lh3i_iss_class` `c`) where ((`d`.`ID` = `p`.`ID`) and (`c`.`ClassID` = `d`.`ClassID`));

-- --------------------------------------------------------

-- 
-- Table structure for table `lh3i_issv_class_students`
-- 

DROP TABLE IF EXISTS lh3i_issv_class_students;
CREATE ALGORITHM=UNDEFINED DEFINER=`clgtvldtvxadnshf`@`10.%` SQL SECURITY DEFINER VIEW `lh3i_issv_class_students` AS select `C`.`ClassID` AS `ClassID`,`S`.`StudentViewID` AS `StudentViewID`,`S`.`StudentID` AS `StudentID`,`S`.`StudentFirstName` AS `StudentFirstName`,`S`.`StudentLastName` AS `StudentLastName`,`S`.`StudentGender` AS `StudentGender` from (`lh3i_iss_class` `C` join `lh3i_iss_student` `S`) where ((`S`.`ISSGrade` = `C`.`ISSGrade`) and (`S`.`StudentStatus` = 'active') and (`C`.`Status` = 'active') and (`S`.`RegistrationYear` = `C`.`RegistrationYear`));

-- --------------------------------------------------------

-- 
-- Table structure for table `lh3i_issv_classes`
-- 

DROP TABLE IF EXISTS lh3i_issv_classes;
CREATE ALGORITHM=UNDEFINED DEFINER=`clgtvldtvxadnshf`@`10.%` SQL SECURITY DEFINER VIEW `lh3i_issv_classes` AS select `C`.`ClassID` AS `ClassID`,`C`.`RegistrationYear` AS `RegistrationYear`,`C`.`ISSGrade` AS `ISSGrade`,`C`.`Subject` AS `Subject`,`C`.`Status` AS `Status`,`U`.`ID` AS `UserID`,`U`.`user_email` AS `UserEmail`,`M`.`Access` AS `Access`,`U`.`display_name` AS `Teacher`,`M`.`LastLogin` AS `LastLogin` from ((`lh3i_iss_class` `C` left join `lh3i_iss_userclassmap` `M` on((`M`.`ClassID` = `C`.`ClassID`))) left join `lh3i_users` `U` on((`M`.`UserID` = `U`.`ID`)));

-- --------------------------------------------------------

-- 
-- Table structure for table `lh3i_issv_student_accounts`
-- 

DROP TABLE IF EXISTS lh3i_issv_student_accounts;
CREATE ALGORITHM=UNDEFINED DEFINER=`clgtvldtvxadnshf`@`10.%` SQL SECURITY DEFINER VIEW `lh3i_issv_student_accounts` AS select `S`.`StudentID` AS `StudentID`,`S`.`StudentViewID` AS `StudentViewID`,`S`.`RegistrationYear` AS `RegistrationYear`,`S`.`ParentID` AS `ParentID`,`S`.`FatherFirstName` AS `FatherFirstName`,`S`.`FatherLastName` AS `FatherLastName`,`S`.`FatherEmail` AS `FatherEmail`,`S`.`MotherFirstName` AS `MotherFirstName`,`S`.`MotherLastName` AS `MotherLastName`,`S`.`MotherEmail` AS `MotherEmail`,`S`.`StudentFirstName` AS `StudentFirstName`,`S`.`StudentLastName` AS `StudentLastName`,`S`.`StudentGender` AS `StudentGender`,`S`.`StudentEmail` AS `StudentEmail`,`S`.`ISSGrade` AS `ISSGrade`,`M`.`UserID` AS `UserID`,`U`.`user_email` AS `UserEmail`,`M`.`Access` AS `Access`,`U`.`user_nicename` AS `NiceName`,`M`.`LastLogin` AS `LastLogin`,`S`.`StudentStatus` AS `StudentStatus` from ((`lh3i_iss_student` `S` left join `lh3i_iss_userstudentmap` `M` on((`M`.`StudentID` = `S`.`StudentID`))) left join `lh3i_users` `U` on((`U`.`ID` = `M`.`UserID`)));

-- --------------------------------------------------------

-- 
-- Table structure for table `lh3i_issv_student_class_access`
-- 

DROP TABLE IF EXISTS lh3i_issv_student_class_access;
CREATE ALGORITHM=UNDEFINED DEFINER=`clgtvldtvxadnshf`@`10.%` SQL SECURITY DEFINER VIEW `lh3i_issv_student_class_access` AS select `S`.`StudentViewID` AS `StudentViewID`,`S`.`StudentFirstName` AS `StudentFirstName`,`S`.`StudentLastName` AS `StudentLastName`,`C`.`ClassID` AS `ClassID`,`S`.`RegistrationYear` AS `RegistrationYear`,`C`.`ISSGrade` AS `ISSGrade`,`C`.`Subject` AS `Subject`,`C`.`Suffix` AS `Suffix`,`M`.`UserID` AS `UserID`,`M`.`Access` AS `Access` from ((`lh3i_iss_class` `C` join `lh3i_iss_student` `S` on(((`S`.`ISSGrade` = `C`.`ISSGrade`) and (`S`.`RegistrationYear` = `C`.`RegistrationYear`)))) join `lh3i_iss_userstudentmap` `M` on((`M`.`StudentID` = `S`.`StudentID`))) where ((`C`.`Status` = 'active') and (`S`.`StudentStatus` = 'active'));

-- --------------------------------------------------------

-- 
-- Table structure for table `lh3i_issv_student_lastlogin`
-- 

DROP TABLE IF EXISTS lh3i_issv_student_lastlogin;
CREATE ALGORITHM=UNDEFINED DEFINER=`clgtvldtvxadnshf`@`10.%` SQL SECURITY DEFINER VIEW `lh3i_issv_student_lastlogin` AS select `P`.`StudentID` AS `StudentID`,max(`P`.`LastLogin`) AS `LastLogin` from `lh3i_iss_userstudentmap` `P` where (`P`.`LastLogin` is not null) group by `P`.`StudentID`;

-- --------------------------------------------------------

-- 
-- Table structure for table `lh3i_issv_student_score_byassignmenttype`
-- 

DROP TABLE IF EXISTS lh3i_issv_student_score_byassignmenttype;
CREATE ALGORITHM=UNDEFINED DEFINER=`clgtvldtvxadnshf`@`10.%` SQL SECURITY DEFINER VIEW `lh3i_issv_student_score_byassignmenttype` AS select `T`.`ClassID` AS `ClassID`,`T`.`TypeName` AS `TypeName`,`G`.`StudentViewID` AS `StudentViewID`,max(`T`.`TypePercentage`) AS `TypePercentage`,ifnull(((((sum(`G`.`Score`) / sum(`A`.`PossiblePoints`)) * 100) * max(`T`.`TypePercentage`)) / 100),max(`T`.`TypePercentage`)) AS `TypeGrade` from ((`lh3i_iss_assignment_type` `T` left join `lh3i_iss_assignment` `A` on((`A`.`AssignmentTypeID` = `T`.`AssignmentTypeID`))) left join `lh3i_iss_score` `G` on((`G`.`AssignmentID` = `A`.`ID`))) group by `T`.`ClassID`,`T`.`TypeName`,`G`.`StudentViewID`;

-- --------------------------------------------------------

-- 
-- Table structure for table `lh3i_issv_student_scores`
-- 

DROP TABLE IF EXISTS lh3i_issv_student_scores;
CREATE ALGORITHM=UNDEFINED DEFINER=`clgtvldtvxadnshf`@`10.%` SQL SECURITY DEFINER VIEW `lh3i_issv_student_scores` AS select `S`.`StudentViewID` AS `StudentViewID`,`A`.`ID` AS `AssignmentID`,`A`.`AssignmentTypeID` AS `AssignmentTypeID`,`A`.`DueDate` AS `DueDate`,`A`.`PossiblePoints` AS `PossiblePoints`,`G`.`Score` AS `Score`,`G`.`Comment` AS `Comment`,`C`.`ClassID` AS `ClassID`,`P`.`post_title` AS `Title`,`S`.`StudentFirstName` AS `StudentFirstName`,`S`.`StudentLastName` AS `StudentLastName`,`S`.`ISSGrade` AS `ISSGrade`,`C`.`Subject` AS `Subject` from ((((`lh3i_iss_assignment` `A` join `lh3i_posts` `P` on((`A`.`ID` = `P`.`ID`))) join `lh3i_iss_class` `C` on((`C`.`ClassID` = `A`.`ClassID`))) join `lh3i_iss_student` `S` on(((`S`.`ISSGrade` = `C`.`ISSGrade`) and (`S`.`RegistrationYear` = `C`.`RegistrationYear`)))) left join `lh3i_iss_score` `G` on(((`G`.`AssignmentID` = `A`.`ID`) and (`G`.`StudentViewID` = `S`.`StudentViewID`)))) where (`S`.`StudentStatus` = 'active');

-- --------------------------------------------------------

-- 
-- Table structure for table `lh3i_issv_teacher_class_access`
-- 

DROP TABLE IF EXISTS lh3i_issv_teacher_class_access;
CREATE ALGORITHM=UNDEFINED DEFINER=`clgtvldtvxadnshf`@`10.%` SQL SECURITY DEFINER VIEW `lh3i_issv_teacher_class_access` AS select `C`.`ClassID` AS `ClassID`,`C`.`RegistrationYear` AS `RegistrationYear`,`C`.`ISSGrade` AS `ISSGrade`,`C`.`Subject` AS `Subject`,`C`.`Suffix` AS `Suffix`,`M`.`UserID` AS `UserID`,`M`.`Access` AS `Access` from (`lh3i_iss_class` `C` join `lh3i_iss_userclassmap` `M`) where ((`C`.`Status` = 'active') and (`C`.`ClassID` = `M`.`ClassID`));

-- --------------------------------------------------------

-- 
-- Table structure for table `lh3i_issv_teacher_name`
-- 

DROP TABLE IF EXISTS lh3i_issv_teacher_name;
CREATE ALGORITHM=UNDEFINED DEFINER=`clgtvldtvxadnshf`@`10.%` SQL SECURITY DEFINER VIEW `lh3i_issv_teacher_name` AS select `M`.`ClassID` AS `ClassID`,`M`.`UserID` AS `UserID`,`U`.`display_name` AS `Teacher`,`M`.`Access` AS `Access` from (`lh3i_iss_userclassmap` `M` join `lh3i_users` `U`) where (`U`.`ID` = `M`.`UserID`);

-- --------------------------------------------------------

-- 
-- Constraints for dumped tables
-- 

-- 
-- Constraints for table `lh3i_iss_assignment_type`
-- 
ALTER TABLE `lh3i_iss_assignment_type`
  ADD CONSTRAINT `AssignmentType-ClassId_FK` FOREIGN KEY (`ClassID`) REFERENCES `lh3i_iss_class` (`ClassID`);

-- 
-- Constraints for table `lh3i_iss_class`
-- 
ALTER TABLE `lh3i_iss_class`
  ADD CONSTRAINT `iss_class_Registration_Year_FK` FOREIGN KEY (`RegistrationYear`) REFERENCES `lh3i_iss_grading_period` (`RegistrationYear`);

-- 
-- Constraints for table `lh3i_iss_scale`
-- 
ALTER TABLE `lh3i_iss_scale`
  ADD CONSTRAINT `Scale-ClassID_FK` FOREIGN KEY (`ClassID`) REFERENCES `lh3i_iss_class` (`ClassID`);

-- 
-- Constraints for table `lh3i_iss_score`
-- 
ALTER TABLE `lh3i_iss_score`
  ADD CONSTRAINT `lh3i_iss_score_AssignmentID_FK` FOREIGN KEY (`AssignmentID`) REFERENCES `lh3i_iss_assignment` (`ID`),
  ADD CONSTRAINT `lh3i_iss_score_StdentViewID_FK` FOREIGN KEY (`StudentViewID`) REFERENCES `lh3i_iss_student` (`StudentViewID`);

-- 
-- Constraints for table `lh3i_iss_student`
-- 
ALTER TABLE `lh3i_iss_student`
  ADD CONSTRAINT `Student_RegistrationYear_FK` FOREIGN KEY (`RegistrationYear`) REFERENCES `lh3i_iss_grading_period` (`RegistrationYear`);

-- 
-- Constraints for table `lh3i_iss_userclassmap`
-- 
ALTER TABLE `lh3i_iss_userclassmap`
  ADD CONSTRAINT `ClassID_UserClassMap_FK` FOREIGN KEY (`ClassID`) REFERENCES `lh3i_iss_class` (`ClassID`);
