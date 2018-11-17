<?php
/*
 * Plugin Name: 01. ISS SQL Insert (test data)
 * 
 * Description:   Insert test date into table structure in database.
 * Version: 1.0
 * Author: Azra Syed
 * 
 */

//require_once(ABSPATH . 'wp-admin/includes/upgrade.php');


function issv_sqlinsert_install()
{
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    $iss_grading_period = $wpdb->prefix . 'iss_grading_period';
    $iss_class = $wpdb->prefix . 'iss_class';
    $iss_student = $wpdb->prefix . 'iss_student';
    $iss_assignment_type = $wpdb->prefix . 'iss_assignment_type';
    $iss_userclassmap = $wpdb->prefix . 'iss_userclassmap';
    $iss_assignment = $wpdb->prefix . 'iss_assignment';
    $iss_score = $wpdb->prefix . 'iss_score';
    $iss_scale = $wpdb->prefix . 'iss_scale';
    $iss_userstudentmap = $wpdb->prefix . 'iss_userstudentmap';

    $wpdb->insert($iss_grading_period, array('GradingPeriodID' => '1', 'RegistrationYear' => '2016-2017', 'GradingPeriod' => '1', 'StartDate' => '2016-08-15', 'EndDate' => '2016-12-31', 'created' => '2018-08-12 22:19:28', 'updated' => '2018-08-12 22:21:56'));
    $wpdb->insert($iss_grading_period, array('GradingPeriodID' => '2', 'RegistrationYear' => '2016-2017', 'GradingPeriod' => '2', 'StartDate' => '2017-01-01', 'EndDate' => '2017-05-31', 'created' => '2018-08-12 22:19:28', 'updated' => '2018-08-12 22:19:28'));
    $wpdb->insert($iss_grading_period, array('GradingPeriodID' => '5', 'RegistrationYear' => '2017-2018', 'GradingPeriod' => '1', 'StartDate' => '2017-08-15', 'EndDate' => '2017-12-31', 'created' => '2018-08-12 22:23:41', 'updated' => '2018-08-12 22:23:41'));
    $wpdb->insert($iss_grading_period, array('GradingPeriodID' => '6', 'RegistrationYear' => '2017-2018', 'GradingPeriod' => '2', 'StartDate' => '2018-01-01', 'EndDate' => '2018-05-31', 'created' => '2018-08-12 22:23:41', 'updated' => '2018-08-12 22:23:41'));
    $wpdb->insert($iss_grading_period, array('GradingPeriodID' => '7', 'RegistrationYear' => '2018-2019', 'GradingPeriod' => '1', 'StartDate' => '2018-08-15', 'EndDate' => '2018-12-31', 'created' => '2018-08-12 22:24:04', 'updated' => '2018-08-12 22:24:04'));
    $wpdb->insert($iss_grading_period, array('GradingPeriodID' => '8', 'RegistrationYear' => '2018-2019', 'GradingPeriod' => '2', 'StartDate' => '2019-01-01', 'EndDate' => '2019-05-31', 'created' => '2018-08-12 22:24:04', 'updated' => '2018-08-12 22:24:04'));

    $wpdb->insert($iss_class, array('ClassID' => '1', 'RegistrationYear' => '2018-2019', 'ISSGrade' => 'KG', 'Subject' => 'Islamic Studies', 'Suffix' => 'Sem1', 'Category' => 'kgis', 'Status' => 'active', 'created' => '2018-09-11 23:52:11', 'updated' => '2018-09-11 23:54:14'));
    $wpdb->insert($iss_class, array('ClassID' => '2', 'RegistrationYear' => '2018-2019', 'ISSGrade' => 'KG', 'Subject' => 'Quranic Studies', 'Suffix' => 'Sem1', 'Category' => 'kgqs', 'Status' => 'active', 'created' => '2018-09-11 23:52:11', 'updated' => '2018-09-11 23:54:14'));
    $wpdb->insert($iss_class, array('ClassID' => '3', 'RegistrationYear' => '2018-2019', 'ISSGrade' => '1', 'Subject' => 'Islamic Studies', 'Suffix' => 'Sem1', 'Category' => 'g1is', 'Status' => 'active', 'created' => '2018-09-11 23:52:11', 'updated' => '2018-09-18 00:37:11'));
    $wpdb->insert($iss_class, array('ClassID' => '4', 'RegistrationYear' => '2018-2019', 'ISSGrade' => '1', 'Subject' => 'Quranic Studies', 'Suffix' => 'Sem1', 'Category' => 'g1qs', 'Status' => 'active', 'created' => '2018-09-11 23:52:11', 'updated' => '2018-09-11 23:54:14'));
    $wpdb->insert($iss_class, array('ClassID' => '5', 'RegistrationYear' => '2018-2019', 'ISSGrade' => '2', 'Subject' => 'Islamic Studies', 'Suffix' => 'Sem1', 'Category' => 'g2is', 'Status' => 'active', 'created' => '2018-09-11 23:52:11', 'updated' => '2018-09-11 23:54:14'));
    $wpdb->insert($iss_class, array('ClassID' => '6', 'RegistrationYear' => '2018-2019', 'ISSGrade' => '2', 'Subject' => 'Quranic Studies', 'Suffix' => 'Sem1', 'Category' => 'g2qs', 'Status' => 'active', 'created' => '2018-09-11 23:52:11', 'updated' => '2018-09-11 23:54:14'));
    $wpdb->insert($iss_class, array('ClassID' => '7', 'RegistrationYear' => '2018-2019', 'ISSGrade' => '3', 'Subject' => 'Islamic Studies', 'Suffix' => 'Sem1', 'Category' => 'g3is', 'Status' => 'active', 'created' => '2018-09-11 23:52:11', 'updated' => '2018-09-11 23:54:14'));
    $wpdb->insert($iss_class, array('ClassID' => '8', 'RegistrationYear' => '2018-2019', 'ISSGrade' => '3', 'Subject' => 'Quranic Studies', 'Suffix' => 'Sem1', 'Category' => 'g3qs', 'Status' => 'active', 'created' => '2018-09-11 23:52:11', 'updated' => '2018-09-11 23:54:14'));
    $wpdb->insert($iss_class, array('ClassID' => '9', 'RegistrationYear' => '2018-2019', 'ISSGrade' => '4', 'Subject' => 'Islamic Studies', 'Suffix' => 'Sem1', 'Category' => 'g4is', 'Status' => 'active', 'created' => '2018-09-11 23:52:11', 'updated' => '2018-09-11 23:54:14'));
    $wpdb->insert($iss_class, array('ClassID' => '10', 'RegistrationYear' => '2018-2019', 'ISSGrade' => '4', 'Subject' => 'Quranic Studies', 'Suffix' => 'Sem1', 'Category' => 'g4qs', 'Status' => 'active', 'created' => '2018-09-11 23:52:11', 'updated' => '2018-09-11 23:54:14'));
    $wpdb->insert($iss_class, array('ClassID' => '11', 'RegistrationYear' => '2018-2019', 'ISSGrade' => '5', 'Subject' => 'Islamic Studies', 'Suffix' => 'Sem1', 'Category' => 'g5is', 'Status' => 'active', 'created' => '2018-09-11 23:52:11', 'updated' => '2018-09-11 23:54:14'));
    $wpdb->insert($iss_class, array('ClassID' => '12', 'RegistrationYear' => '2018-2019', 'ISSGrade' => '5', 'Subject' => 'Quranic Studies', 'Suffix' => 'Sem1', 'Category' => 'g5qs', 'Status' => 'active', 'created' => '2018-09-11 23:52:11', 'updated' => '2018-09-11 23:54:14'));
    $wpdb->insert($iss_class, array('ClassID' => '13', 'RegistrationYear' => '2018-2019', 'ISSGrade' => '6', 'Subject' => 'Islamic Studies', 'Suffix' => 'Sem1', 'Category' => 'g6is', 'Status' => 'active', 'created' => '2018-09-11 23:52:11', 'updated' => '2018-09-11 23:54:14'));
    $wpdb->insert($iss_class, array('ClassID' => '14', 'RegistrationYear' => '2018-2019', 'ISSGrade' => '6', 'Subject' => 'Quranic Studies', 'Suffix' => 'Sem1', 'Category' => 'g6qs', 'Status' => 'active', 'created' => '2018-09-11 23:52:11', 'updated' => '2018-09-11 23:54:14'));
    $wpdb->insert($iss_class, array('ClassID' => '15', 'RegistrationYear' => '2018-2019', 'ISSGrade' => '7', 'Subject' => 'Islamic Studies', 'Suffix' => 'Sem1', 'Category' => 'g7is', 'Status' => 'active', 'created' => '2018-09-11 23:52:11', 'updated' => '2018-09-11 23:54:14'));
    $wpdb->insert($iss_class, array('ClassID' => '16', 'RegistrationYear' => '2018-2019', 'ISSGrade' => '7', 'Subject' => 'Quranic Studies', 'Suffix' => 'Sem1', 'Category' => 'g7qs', 'Status' => 'active', 'created' => '2018-09-11 23:52:11', 'updated' => '2018-09-11 23:54:14'));
    $wpdb->insert($iss_class, array('ClassID' => '17', 'RegistrationYear' => '2018-2019', 'ISSGrade' => '8', 'Subject' => 'Islamic Studies', 'Suffix' => 'Sem1', 'Category' => 'g8is', 'Status' => 'active', 'created' => '2018-09-11 23:52:11', 'updated' => '2018-09-11 23:54:14'));
    $wpdb->insert($iss_class, array('ClassID' => '18', 'RegistrationYear' => '2018-2019', 'ISSGrade' => '8', 'Subject' => 'Quranic Studies', 'Suffix' => 'Sem1', 'Category' => 'g8qs', 'Status' => 'active', 'created' => '2018-09-11 23:52:11', 'updated' => '2018-09-11 23:54:14'));
    $wpdb->insert($iss_class, array('ClassID' => '19', 'RegistrationYear' => '2018-2019', 'ISSGrade' => 'YB', 'Subject' => 'Islamic Studies', 'Suffix' => 'Sem1', 'Category' => 'ybis', 'Status' => 'active', 'created' => '2018-09-11 23:52:11', 'updated' => '2018-09-11 23:54:14'));
    $wpdb->insert($iss_class, array('ClassID' => '20', 'RegistrationYear' => '2018-2019', 'ISSGrade' => 'YB', 'Subject' => 'Quranic Studies', 'Suffix' => 'Sem1', 'Category' => 'ybqs', 'Status' => 'active', 'created' => '2018-09-11 23:52:11', 'updated' => '2018-09-11 23:54:14'));
    $wpdb->insert($iss_class, array('ClassID' => '21', 'RegistrationYear' => '2018-2019', 'ISSGrade' => 'YG', 'Subject' => 'Islamic Studies', 'Suffix' => 'Sem1', 'Category' => 'ygis', 'Status' => 'active', 'created' => '2018-09-11 23:52:11', 'updated' => '2018-09-11 23:54:14'));
    $wpdb->insert($iss_class, array('ClassID' => '22', 'RegistrationYear' => '2018-2019', 'ISSGrade' => 'YG', 'Subject' => 'Quranic Studies', 'Suffix' => 'Sem1', 'Category' => 'ygqs', 'Status' => 'active', 'created' => '2018-09-11 23:52:11', 'updated' => '2018-09-11 23:54:14'));
  
    // Grade 1
    $wpdb->insert($iss_student, array('StudentViewID' => '529', 'RegistrationYear' => '2018-2019', 'ParentID' => '126', 'FatherFirstName' => 'Basim', 'FatherLastName' => 'Abu Hamid', 'FatherEmail' => '', 'MotherFirstName' => 'Fitan', 'MotherLastName' => 'Khalil', 'MotherEmail' => 'a@gmail.com', 'StudentID' => '1280', 'StudentFirstName' => 'Zachariah', 'StudentLastName' => 'Abu Hamid', 'StudentGender' => 'M', 'StudentStatus' => 'active', 'StudentEmail' => '', 'ISSGrade' => '1', 'SchoolEmail' => 'a@gmail.com'));
    $wpdb->insert($iss_student, array('StudentViewID' => '546', 'RegistrationYear' => '2018-2019', 'ParentID' => '156', 'FatherFirstName' => 'Omar', 'FatherLastName' => 'Alnaggar', 'FatherEmail' => 'o@gmail.com', 'MotherFirstName' => 'Rasha', 'MotherLastName' => 'Elsayed', 'MotherEmail' => 'b.as@gmail.com', 'StudentID' => '1314', 'StudentFirstName' => 'Ali', 'StudentLastName' => 'Alnaggar', 'StudentGender' => 'M', 'StudentStatus' => 'active', 'StudentEmail' => '', 'ISSGrade' => '1', 'SchoolEmail' => 'b@gmail.com'));
    $wpdb->insert($iss_student, array('StudentViewID' => '549', 'RegistrationYear' => '2018-2019', 'ParentID' => '149', 'FatherFirstName' => 'Naveed', 'FatherLastName' => 'Anwar', 'FatherEmail' => 'f@cantab.net', 'MotherFirstName' => 'Rabia', 'MotherLastName' => 'Bajwa', 'MotherEmail' => 'c@gmail.com', 'StudentID' => '1300', 'StudentFirstName' => 'Zayd', 'StudentLastName' => 'Anwar', 'StudentGender' => 'M', 'StudentStatus' => 'active', 'StudentEmail' => '', 'ISSGrade' => '1', 'SchoolEmail' => 'c@gmail.com'));
    $wpdb->insert($iss_student, array('StudentViewID' => '571', 'RegistrationYear' => '2018-2019', 'ParentID' => '26', 'FatherFirstName' => 'Haseeb', 'FatherLastName' => 'Budhani', 'FatherEmail' => '', 'MotherFirstName' => 'Haina', 'MotherLastName' => 'Karim', 'MotherEmail' => 'd.@gmail.com', 'StudentID' => '1288', 'StudentFirstName' => 'Rafay', 'StudentLastName' => 'Budhani', 'StudentGender' => 'M', 'StudentStatus' => 'active', 'StudentEmail' => '', 'ISSGrade' => '1', 'SchoolEmail' => 'd@gmail.com'));
    $wpdb->insert($iss_student, array('StudentViewID' => '592', 'RegistrationYear' => '2018-2019', 'ParentID' => '141', 'FatherFirstName' => 'Omair', 'FatherLastName' => 'Farooqui', 'FatherEmail' => 's@yahoo.com', 'MotherFirstName' => 'Amina', 'MotherLastName' => 'Anwar', 'MotherEmail' => 'e@gmail.com', 'StudentID' => '1290', 'StudentFirstName' => 'Hala', 'StudentLastName' => 'Farooqui', 'StudentGender' => 'F', 'StudentStatus' => 'active', 'StudentEmail' => '', 'ISSGrade' => '1', 'SchoolEmail' => 'e@yahoo.com'));
    // Grade KG
    $wpdb->insert($iss_student, array('StudentViewID' => '746', 'RegistrationYear' => '2018-2019', 'ParentID' => '41', 'FatherFirstName' => 'Rehan', 'FatherLastName' => 'Hameed', 'FatherEmail' => 'd@gmail.com', 'MotherFirstName' => 'Mehjabeen', 'MotherLastName' => 'Awan', 'MotherEmail' => 'f@yahoo.com', 'StudentID' => '1351', 'StudentFirstName' => 'Zunaira', 'StudentLastName' => 'Rehan', 'StudentGender' => 'F', 'StudentStatus' => 'active', 'StudentEmail' => '', 'ISSGrade' => 'KG', 'SchoolEmail' => 'f@yahoo.com'));
    $wpdb->insert($iss_student, array('StudentViewID' => '748', 'RegistrationYear' => '2018-2019', 'ParentID' => '28', 'FatherFirstName' => 'Arshad', 'FatherLastName' => 'Siddiqi', 'FatherEmail' => 'e@gmail.com', 'MotherFirstName' => 'Sabah', 'MotherLastName' => 'Siddiqi', 'MotherEmail' => 'g@gmail.com', 'StudentID' => '1353', 'StudentFirstName' => 'Alia', 'StudentLastName' => 'Siddiqi', 'StudentGender' => 'M', 'StudentStatus' => 'active', 'StudentEmail' => '', 'ISSGrade' => 'KG', 'SchoolEmail' => 'g@gmail.com'));
    $wpdb->insert($iss_student, array('StudentViewID' => '750', 'RegistrationYear' => '2018-2019', 'ParentID' => '127', 'FatherFirstName' => 'Omar', 'FatherLastName' => 'Siddiqui', 'FatherEmail' => 'f@gmail.com', 'MotherFirstName' => 'Zeenat', 'MotherLastName' => 'Khan', 'MotherEmail' => 'h@gmail.com', 'StudentID' => '1355', 'StudentFirstName' => 'Maryam', 'StudentLastName' => 'Siddiqui', 'StudentGender' => 'F', 'StudentStatus' => 'active', 'StudentEmail' => '', 'ISSGrade' => 'KG', 'SchoolEmail' => 'h@gmail.com'));
    $wpdb->insert($iss_student, array('StudentViewID' => '752', 'RegistrationYear' => '2018-2019', 'ParentID' => '63', 'FatherFirstName' => 'Asif', 'FatherLastName' => 'Hassan', 'FatherEmail' => 'g@hotmail.com', 'MotherFirstName' => 'Amina', 'MotherLastName' => 'Khan', 'MotherEmail' => 'i@yahoo.com', 'StudentID' => '1357', 'StudentFirstName' => 'Arshad', 'StudentLastName' => 'Hassan', 'StudentGender' => 'F', 'StudentStatus' => 'active', 'StudentEmail' => null, 'ISSGrade' => 'KG', 'SchoolEmail' => 'i@yahoo.com'));
    $wpdb->insert($iss_student, array('StudentViewID' => '753', 'RegistrationYear' => '2018-2019', 'ParentID' => '64', 'FatherFirstName' => 'Kashif', 'FatherLastName' => 'Hassan', 'FatherEmail' => 'j@gmail.com', 'MotherFirstName' => 'Faiqa', 'MotherLastName' => 'Hassan', 'MotherEmail' => 'j@gmail.com', 'StudentID' => '1358', 'StudentFirstName' => 'Taha', 'StudentLastName' => 'Hassan', 'StudentGender' => 'M', 'StudentStatus' => 'active', 'StudentEmail' => null, 'ISSGrade' => 'KG', 'SchoolEmail' => 'j@gmail.com'));


}
register_activation_hook(__FILE__, 'issv_sqlinsert_install');

?>