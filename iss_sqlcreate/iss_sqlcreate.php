<?php
/*
 * Plugin Name: 00. ISS SQL Create Tables
 * 
 * Description:   Creates table structure in database.
 * Version: 1.0
 * Author: Azra Syed
 * 
 */

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

function issv_sqlcreate_grading_period($charset_collate, $table_name)
{

    $sql = "CREATE TABLE IF NOT EXISTS $table_name ( 

        `GradingPeriodID` INT(11) NOT NULL AUTO_INCREMENT, 
        `RegistrationYear` VARCHAR(10) NOT NULL, 
        `GradingPeriod` INT(11) NOT NULL, 
        `StartDate` DATE NOT NULL, 
        `EndDate` DATE NOT NULL, 
        `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
        `updated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, 
        PRIMARY KEY(`GradingPeriodID`), 
        UNIQUE KEY `RegistrationYear`(`RegistrationYear`, `GradingPeriod`) 

	) $charset_collate;";

    dbDelta($sql);
}
function issv_sqlcreate_class($charset_collate, $table_name, $depends)
{

    $sql = "CREATE TABLE IF NOT EXISTS $table_name ( 

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
        KEY `iss_class_RegistrationYear_FK` (`RegistrationYear`),
        CONSTRAINT `RegistrationYear_Class_FK` FOREIGN KEY (`RegistrationYear`) REFERENCES $depends (`RegistrationYear`)
 
    ) $charset_collate;";
    dbDelta($sql);
}
function issv_sqlcreate_student($charset_collate, $table_name, $depends)
{

    $sql = "CREATE TABLE IF NOT EXISTS $table_name ( 

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
        KEY `iss_student_RegistrationYear_FK` (`RegistrationYear`),
        CONSTRAINT `RegistrationYear_Student_FK` FOREIGN KEY (`RegistrationYear`) REFERENCES $depends (`RegistrationYear`)

    ) $charset_collate;";
    dbDelta($sql);
}
function issv_sqlcreate_assignment_type($charset_collate, $table_name, $depends)
{

    $sql = "CREATE TABLE IF NOT EXISTS $table_name ( 

        `AssignmentTypeID` int(11) NOT NULL AUTO_INCREMENT,
        `ClassID` int(11) NOT NULL,
        `TypeName` varchar(100) NOT NULL,
        `TypePercentage` int(5) NOT NULL,
        PRIMARY KEY (`AssignmentTypeID`),
        KEY `iss_assignmenttype_ClassID_FK` (`ClassID`),
        CONSTRAINT `ClassID_AssignmentType_FK` FOREIGN KEY (`ClassID`) REFERENCES $depends (`ClassID`)

    ) $charset_collate;";
    dbDelta($sql);
}
function issv_sqlcreate_userclassmap($charset_collate, $table_name, $depends)
{

    $sql = "CREATE TABLE IF NOT EXISTS $table_name ( 

        `UserID` int(11) NOT NULL,
        `ClassID` int(11) NOT NULL,
        `Access` varchar(10) NOT NULL DEFAULT 'read',
        `LastLogin` datetime DEFAULT NULL,
        PRIMARY KEY (`UserID`,`ClassID`),
        KEY `iss_userclassmap_ClassID_FK` (`ClassID`),
        CONSTRAINT `ClassID_UserClassMap_FK` FOREIGN KEY (`ClassID`) REFERENCES $depends (`ClassID`)

    ) $charset_collate;";
    dbDelta($sql);
}
function issv_sqlcreate_scale($charset_collate, $table_name, $depends)
{

    $sql = "CREATE TABLE IF NOT EXISTS $table_name ( 

        `ScaleID` int(11) NOT NULL AUTO_INCREMENT,
        `ClassID` int(11) NOT NULL,
        `ScaleName` varchar(100) NOT NULL,
        `ScalePercentage` int(5) NOT NULL,
        PRIMARY KEY (`ScaleID`),
        KEY `iss_scale_ClassID_FK` (`ClassID`),
        CONSTRAINT `ClassID_Scale_FK` FOREIGN KEY (`ClassID`) REFERENCES $depends (`ClassID`)

    ) $charset_collate;";
    dbDelta($sql);
}
function issv_sqlcreate_assignment($charset_collate, $table_name, $depends, $depends1)
{

    $sql = "CREATE TABLE IF NOT EXISTS $table_name ( 

        `ID` bigint(20) unsigned NOT NULL,
        `PossiblePoints` int(10) DEFAULT '10',
        `DueDate` date NOT NULL,
        `Category` varchar(20) NOT NULL,
        `ClassID` bigint(20) DEFAULT '0',
        `AssignmentTypeID` int(11) DEFAULT NULL,
        `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`ID`),
        KEY `iss_assignment_ClassID_FK` (`ClassID`),
        KEY `iss_assignment_AssignmentTypeID_FK` (`AssignmentTypeID`)
    ) $charset_collate;";
    dbDelta($sql);
     
    // CONSTRAINT `AssignmentTypeID_Assignments_FK` FOREIGN KEY (`AssignmentTypeID`) REFERENCES $depends1 (`AssignmentTypeID`)
    // CONSTRAINT `ClassID_Assignments_FK` FOREIGN KEY (`ClassID`) REFERENCES $depends (`ClassID`)
    
    // $sql  = "ALTER TABLE $table_name  ADD CONSTRAINT `ClassID_Assignments_FK` FOREIGN KEY (`ClassID`) REFERENCES $depends (`ClassID`);";
    // dbDelta( $sql );  

}
function issv_sqlcreate_score($charset_collate, $table_name, $depends, $depends1)
{

    $sql = "CREATE TABLE IF NOT EXISTS $table_name ( 

        `StudentViewID` int(11) NOT NULL,
        `AssignmentID` bigint(20) unsigned NOT NULL,
        `Score` float(5,2) NOT NULL DEFAULT '0',
        `Comment` varchar(200) DEFAULT NULL,
        PRIMARY KEY (`StudentViewID`,`AssignmentID`),
        KEY `iss_score_StdentViewID_FK` (`StudentViewID`),
        KEY `iss_score_AssignmentID_FK` (`AssignmentID`),
        CONSTRAINT `AssignmentID_Score_FK` FOREIGN KEY (`AssignmentID`) REFERENCES $depends1 (`ID`),
        CONSTRAINT `StdentViewID_Score_FK` FOREIGN KEY (`StudentViewID`) REFERENCES $depends (`StudentViewID`)
    ) $charset_collate;";
    dbDelta($sql);
}
function issv_sqlcreate_userstudentmap($charset_collate, $table_name)
{

    $sql = "CREATE TABLE IF NOT EXISTS $table_name ( 

        `UserID` bigint(20) NOT NULL,
        `StudentID` bigint(20) NOT NULL,
        `Access` varchar(10) NOT NULL DEFAULT 'read',
        `LastLogin` datetime DEFAULT NULL,
        PRIMARY KEY (`UserID`,`StudentID`)

    ) $charset_collate;";
    dbDelta($sql);
}
function issv_sqlcreate_install()
{
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $prefix = $wpdb->prefix;
    
    // tables
    $iss_grading_period = $prefix . 'iss_grading_period';
    $iss_class = $prefix . 'iss_class';
    $iss_student = $prefix . 'iss_student';
    $iss_assignment_type = $prefix . 'iss_assignment_type';
    $iss_userclassmap = $prefix . 'iss_userclassmap';
    $iss_assignment = $prefix . 'iss_assignment';
    $iss_score = $prefix . 'iss_score';
    $iss_scale = $prefix . 'iss_scale';
    $iss_userstudentmap = $prefix . 'iss_userstudentmap';
    $iss_users = $wpdb->prefix . 'users';
    $iss_posts = $wpdb->prefix . 'posts';
    // views
    $issv_classes = $prefix . 'issv_classes';
    $issv_class_assignments = $prefix . 'issv_class_assignments';
    $issv_class_students = $prefix . 'issv_class_students';
    $issv_student_accounts = $prefix . 'issv_student_accounts';
    $issv_student_class_access = $prefix . 'issv_student_class_access';
    $issv_student_lastlogin = $prefix . 'issv_student_lastlogin';
    $issv_student_scores = $prefix . 'issv_student_scores';
    $issv_student_score_byassignmenttype = $prefix . 'issv_student_score_byassignmenttype';
    $issv_teacher_class_access = $prefix . 'issv_teacher_class_access';
    $issv_teacher_name = $prefix . 'issv_teacher_name';

    issv_sqlcreate_grading_period($charset_collate, $iss_grading_period);
    issv_sqlcreate_class($charset_collate, $iss_class, $iss_grading_period); //  depends on  grading_period
    issv_sqlcreate_student($charset_collate, $iss_student, $iss_grading_period); //  depends on grading_period
    issv_sqlcreate_assignment_type($charset_collate, $iss_assignment_type, $iss_class); // depends on class
    issv_sqlcreate_userclassmap($charset_collate, $iss_userclassmap, $iss_class); // depends on class
    issv_sqlcreate_scale($charset_collate, $iss_scale, $iss_class); // depends on class
    issv_sqlcreate_assignment($charset_collate, $iss_assignment, $iss_class, $iss_assignment_type); //  depends on class & assignment_type
    issv_sqlcreate_score($charset_collate, $iss_score, $iss_student, $iss_assignment); //  depends on student & assignment
    issv_sqlcreate_userstudentmap($charset_collate, $iss_userstudentmap);
 }

register_activation_hook(__FILE__, 'issv_sqlcreate_install');

?>