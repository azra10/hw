
ALTER TABLE lh3i_iss_userstudentmap ADD LastLogin DATETIME NULL DEFAULT NULL AFTER Access;
ALTER TABLE lh3i_iss_userclassmap ADD LastLogin DATETIME NULL DEFAULT NULL AFTER Access;

change teachers permission FROM write to primary

lh3i_iss_userclassmap
    - Make USerID and ClassID primary key
    - Two Index UserID and ClassID
    - Delete UCMapID
lh3i_iss_userstudentmap
    - Make UserID and student ID primary key
    - Two Index UserID and StudentID
    - Delete USMAPID

	CREATE  VIEW lh3i_issv_classes AS 
    SELECT C.ClassID AS ClassID,C.RegistrationYear AS RegistrationYear,C.ISSGrade AS ISSGrade,C.Subject AS Subject,C.Status AS Status,U.ID AS UserID,U.user_email AS UserEmail,M.Access AS Access,U.display_name AS Teacher,M.LastLogin AS LastLogin 
    FROM lh3i_iss_class C left join lh3i_iss_userclassmap M on M.ClassID = C.ClassID left join lh3i_users U on M.UserID = U.ID
	
    DROP VIEW lh3i_issv_class_assignments;
    CREATE  VIEW lh3i_issv_class_assignments AS 
    SELECT d.ClassID AS ClassID,d.DueDate AS DueDate,d.Category AS Category,d.PossiblePoints AS PossiblePoints,d.AssignmentTypeID,
    c.ISSGrade AS ISSGrade,c.RegistrationYear AS RegistrationYear,c.Subject AS Subject,p.ID AS ID,
    p.post_author AS post_author,p.post_date AS post_date,p.post_content AS post_content,p.post_title AS post_title,
    p.post_status AS post_status,p.post_name AS post_name,p.guid AS guid,p.post_type AS post_type 
    FROM lh3i_iss_assignment d join lh3i_posts p join lh3i_iss_class c 
    WHERE d.ID = p.ID and c.ClassID = d.ClassID 
	
    
    
    CREATE  VIEW lh3i_issv_class_students AS 
    SELECT C.ClassID AS ClassID,S.StudentViewID AS StudentViewID,S.StudentID AS StudentID,S.StudentFirstName AS StudentFirstName,S.StudentLastName AS StudentLastName,S.StudentGender AS StudentGender 
    FROM lh3i_iss_class C join lh3i_iss_student S 
    WHERE S.ISSGrade = C.ISSGrade and S.StudentStatus = 'active' and C.Status = 'active' and S.RegistrationYear = C.RegistrationYear
	
    CREATE  VIEW lh3i_issv_student_accounts AS 
    SELECT S.StudentID AS StudentID,S.StudentViewID AS StudentViewID,S.RegistrationYear AS RegistrationYear,S.ParentID AS ParentID,S.FatherFirstName AS FatherFirstName,S.FatherLastName AS FatherLastName,S.FatherEmail AS FatherEmail,S.MotherFirstName AS MotherFirstName,S.MotherLastName AS MotherLastName,S.MotherEmail AS MotherEmail,S.StudentFirstName AS StudentFirstName,S.StudentLastName AS StudentLastName,S.StudentGender AS StudentGender,S.StudentEmail AS StudentEmail,S.ISSGrade AS ISSGrade,M.UserID AS UserID,U.user_email AS UserEmail,M.Access AS Access,U.user_nicename AS NiceName,M.LastLogin AS LastLogin,S.StudentStatus AS StudentStatus 
    FROM lh3i_iss_student S left join lh3i_iss_userstudentmap M on M.StudentID = S.StudentID left join lh3i_users U on U.ID = M.UserID
	
    CREATE  VIEW lh3i_issv_student_class_access AS 
    SELECT C.ClassID AS ClassID,S.RegistrationYear AS RegistrationYear,C.ISSGrade AS ISSGrade,C.Subject AS Subject,M.UserID AS UserID,M.Access AS Access 
    FROM lh3i_iss_student S join lh3i_iss_userstudentmap M join lh3i_iss_class C 
    WHERE M.StudentID = S.StudentID and C.ISSGrade = S.ISSGrade and C.Status = 'active' and S.StudentStatus = 'active' and C.RegistrationYear = S.RegistrationYear 
	
    CREATE  VIEW lh3i_issv_student_lastlogin AS 
    SELECT lh3i_iss_userstudentmap.StudentID AS StudentID,max(lh3i_iss_userstudentmap.LastLogin) AS LastLogin FROM lh3i_iss_userstudentmap 
    WHERE lh3i_iss_userstudentmap.LastLogin is not null group by lh3i_iss_userstudentmap.StudentID
	
    CREATE  VIEW lh3i_issv_teacher_class_access AS 
    SELECT C.ClassID AS ClassID,C.RegistrationYear AS RegistrationYear,C.ISSGrade AS ISSGrade,C.Subject AS Subject,M.UserID AS UserID,M.Access AS Access 
    FROM lh3i_iss_class C join lh3i_iss_userclassmap M 
    WHERE C.Status = 'active' and C.ClassID = M.ClassID
	
    CREATE  VIEW lh3i_issv_teacher_name AS 
    SELECT M.ClassID AS ClassID,M.UserID AS UserID,U.display_name AS Teacher,M.Access AS Access 
    FROM lh3i_iss_userclassmap M join lh3i_users U 
    WHERE U.ID = M.UserID
	

Remove post content short code

Date: 8/27

ALTER TABLE `lh3i_iss_student` ADD `SchoolEmail` VARCHAR(100)  NULL  AFTER `ISSGrade`;

Date: 9/8
CREATE TABLE `lh3i_iss_score` (
 `StudentViewID` int(11) NOT NULL,
 `AssignmentID` bigint(20) unsigned NOT NULL,
 `Score` int(5) NOT NULL DEFAULT '0',
 `Comment` varchar(200) DEFAULT NULL,
 PRIMARY KEY (`StudentViewID`,`AssignmentID`),
 KEY `lh3i_iss_score_AssignmentID_FK` (`AssignmentID`),
 CONSTRAINT `lh3i_iss_score_AssignmentID_FK` FOREIGN KEY (`AssignmentID`) REFERENCES `lh3i_iss_assignment` (`ID`),
 CONSTRAINT `lh3i_iss_score_StdentViewID_FK` FOREIGN KEY (`StudentViewID`) REFERENCES `lh3i_iss_student` (`StudentViewID`)
) 

DROP VIEW `lh3i_issv_student_scores`;

CREATE VIEW `lh3i_issv_student_scores` AS select `S`.`StudentViewID` AS `StudentViewID`,`A`.`ID` AS `AssignmentID`,
`A`.`AssignmentTypeID` AS `AssignmentTypeID`,`A`.`DueDate` AS `DueDate`,
`A`.`PossiblePoints` AS `PossiblePoints`,`G`.`Score` AS `Score`,`G`.`Comment` AS `Comment`,`C`.`ClassID` AS `ClassID`,
`P`.`post_title` AS `Title`,`S`.`StudentFirstName` AS `StudentFirstName`,`S`.`StudentLastName` AS `StudentLastName`,
`S`.`ISSGrade` AS `ISSGrade`,`C`.`Subject` AS `Subject` 
from `lh3i_iss_assignment` `A` 
join `lh3i_posts` `P` on `A`.`ID` = `P`.`ID`
join `lh3i_iss_class` `C` on `A`.`ClassID` = `C`.`ClassID` 
join `lh3i_iss_student` `S` on `C`.`ISSGrade` = `S`.`ISSGrade` 
left join `lh3i_iss_score` `G` 
on `G`.`AssignmentID` = `A`.`ID` and `G`.`StudentViewID` = `S`.`StudentViewID`
 where (`S`.`StudentStatus` = 'active')


CREATE TABLE `lh3i_iss_assignment_type` (
 `AssignmentTypeID` int(11) NOT NULL AUTO_INCREMENT,
 `ClassID` int(11) NOT NULL,
 `TypeName` varchar(100) NOT NULL,
 `TypePercentage` int(5) NOT NULL,
 PRIMARY KEY (`AssignmentTypeID`),
 KEY `AssignmentType-ClassId_FK` (`ClassID`),
 CONSTRAINT `AssignmentType-ClassId_FK` FOREIGN KEY (`ClassID`) REFERENCES `lh3i_iss_class` (`ClassID`)
) 

ALTER TABLE `lh3i_iss_class` ADD `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `Status`;
ALTER TABLE `lh3i_iss_class` ADD `updated` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `created`;
ALTER TABLE `lh3i_iss_class` ADD `Suffix` VARCHAR(50) NULL DEFAULT NULL AFTER `Subject`;


ALTER TABLE `lh3i_iss_assignment` ADD `AssignmentTypeID` INT(11) NULL DEFAULT NULL AFTER `ClassID`;
ALTER TABLE `lh3i_iss_assignment` ADD CONSTRAINT `Assignment_TypeID_FK` FOREIGN KEY (`AssignmentTypeID`) 
REFERENCES `lh3i_iss_assignment_type`(`AssignmentTypeID`) ON DELETE RESTRICT ON UPDATE RESTRICT;



INSERT INTO `lh3i_iss_assignment_type`( `ClassID`, `TypeName`, `TypePercentage`) 
SELECT ClassID, 'Attendance', 0 FROM `lh3i_iss_class`  ORDER BY ClassID

INSERT INTO `lh3i_iss_assignment_type`( `ClassID`, `TypeName`, `TypePercentage`) 
SELECT ClassID, 'Participation', 0 FROM `lh3i_iss_class`  ORDER BY ClassID

INSERT INTO `lh3i_iss_assignment_type`( `ClassID`, `TypeName`, `TypePercentage`) 
SELECT ClassID, 'Homework', 100 FROM `lh3i_iss_class`  ORDER BY ClassID


--UPDATE `lh3i_iss_assignment` A SET `AssignmentTypeID` = (SELECT Max(AssignmentTypeID) FROM lh3i_iss_assignment_type T WHERE A.ClassID = T.ClassID and T.TypeName = '(Not Graded)')

CREATE TABLE `lh3i_iss_scale` (
 `ScaleID` int(11) NOT NULL AUTO_INCREMENT,
 `ClassID` int(11) NOT NULL,
 `ScaleName` varchar(100) NOT NULL,
 `ScalePercentage` int(5) NOT NULL,
 PRIMARY KEY (`ScaleID`),
 KEY `Scale-ClassID_FK` (`ClassID`),
 CONSTRAINT `Scale-ClassID_FK` FOREIGN KEY (`ClassID`) REFERENCES `lh3i_iss_class` (`ClassID`)
) 

DROP View lh3i_issv_student_class_access;
CREATE View lh3i_issv_student_class_access AS select `S`.`StudentViewID` AS `StudentViewID`,`S`.`StudentFirstName` AS `StudentFirstName`,`S`.`StudentLastName` AS `StudentLastName`,`C`.`ClassID` AS `ClassID`,`S`.`RegistrationYear` AS `RegistrationYear`,`C`.`ISSGrade` AS `ISSGrade`,`C`.`Subject` AS `Subject`,C.Suffix,`M`.`UserID` AS `UserID`,`M`.`Access` AS `Access` from ((`lh3i_iss_student` `S` join `lh3i_iss_userstudentmap` `M`) join `lh3i_iss_class` `C`) where ((`M`.`StudentID` = `S`.`StudentID`) and (`C`.`ISSGrade` = `S`.`ISSGrade`) and (`C`.`Status` = 'active') and (`S`.`StudentStatus` = 'active') and (`C`.`RegistrationYear` = '2018-2019') and (`S`.`RegistrationYear` = '2018-2019'))

DROP View lh3i_issv_teacher_class_access;
CREATE  `lh3i_issv_teacher_class_access` AS select `C`.`ClassID` AS `ClassID`,`C`.`RegistrationYear` AS `RegistrationYear`,`C`.`ISSGrade` AS `ISSGrade`,`C`.`Subject` AS `Subject`,`C`.`Suffix` AS `Suffix`,`M`.`UserID` AS `UserID`,`M`.`Access` AS `Access` from (`lh3i_iss_class` `C` join `lh3i_iss_userclassmap` `M`) where ((`C`.`Status` = 'active') and (`C`.`ClassID` = `M`.`ClassID`))


INSERT INTO `lh3i_iss_scale` (`ClassID`, `ScaleName`, `ScalePercentage`) 
VALUES 
( 1, 'A', '90'), 
( 1, 'B', '80'),
( 1, 'C', '60'),
( 1, 'D', '50'),
( 1, 'F', '0')
;

INSERT INTO `lh3i_iss_assignment_type` (`ClassID`, `TypeName`, `TypePercentage`) 
VALUES 
( 1, 'Homework', '40'), 
( 1, 'Quizzes', '40'), 
( 1, 'Participation', '5'),
( 1, 'Attendance', 10)
,
( 1, 'Behavior', 5);



/*	
Example of how to calculate a student's grade with category weighting:
Class categories:

Homework: 10%
Classwork: 25%
Quizzes: 25%
Tests & Projects: 40%
 
Step 1: Get the percentage of the scores in EACH category total score/ total possible points * 100. (Let's say the category percentages are Homework=90%, Classwork=85%, Quizzes=85%, Tests & Projects=80%.)

Step 2: Convert each weight percent to a decimal (such as 10%=.10, 25%=.25, etP.) weight/100

Step 3: Multiply the individual category percentages by the category weight decimal.

Homework= 90 * .10 = 9
Classwork = 85 * .25 = 21.25
Quizzes = 85 * .25 = 21.25
Tests & Projects = 80 * .40 = 32
Step 4: Add those values together for the grade in the class.

9 + 21.25 + 21.25 + 32 = 83.5
*/
