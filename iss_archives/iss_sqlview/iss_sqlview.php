<?php
/*
 * Plugin Name: 02. ISS SQL Create/Drop Views and Accounts
 * 
 * Description:   Creates table structure in database.  (testparent1 (2 students), testparent2 (1 student), teststudent1, testteacher1 (kg class), testteacher2 (teacher and parent) with Password1)
 * Version: 1.0
 * Author: Azra Syed
 * 
 */


function issv_sqlview_install()
{
    global $wpdb;
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

    $sql = "CREATE VIEW $issv_class_assignments AS select `d`.`ClassID` AS `ClassID`,`d`.`DueDate` AS `DueDate`,`d`.`Category` AS `Category`,`d`.`PossiblePoints` AS `PossiblePoints`,`d`.`AssignmentTypeID` AS `AssignmentTypeID`,`c`.`ISSGrade` AS `ISSGrade`,`c`.`RegistrationYear` AS `RegistrationYear`,`c`.`Subject` AS `Subject`,`p`.`ID` AS `ID`,`p`.`post_author` AS `post_author`,`p`.`post_date` AS `post_date`,`p`.`post_content` AS `post_content`,`p`.`post_title` AS `post_title`,`p`.`post_status` AS `post_status`,`p`.`post_name` AS `post_name`,`p`.`guid` AS `guid`,`p`.`post_type` AS `post_type` 
    from (($iss_assignment `d` join $iss_posts `p`) join $iss_class `c`) where ((`d`.`ID` = `p`.`ID`) and (`c`.`ClassID` = `d`.`ClassID`));";
    $wpdb->query($sql);
    $sql = "CREATE VIEW $issv_class_students AS select `C`.`ClassID` AS `ClassID`,`S`.`StudentViewID` AS `StudentViewID`,`S`.`StudentID` AS `StudentID`,`S`.`StudentFirstName` AS `StudentFirstName`,`S`.`StudentLastName` AS `StudentLastName`,`S`.`StudentGender` AS `StudentGender` 
    from ($iss_class `C` join $iss_student `S`) where ((`S`.`ISSGrade` = `C`.`ISSGrade`) and (`S`.`StudentStatus` = 'active') and (`C`.`Status` = 'active') and (`S`.`RegistrationYear` = `C`.`RegistrationYear`));";
    $wpdb->query($sql);
    $sql = "CREATE  VIEW $issv_classes AS select `C`.`ClassID` AS `ClassID`,`C`.`RegistrationYear` AS `RegistrationYear`,`C`.`ISSGrade` AS `ISSGrade`,`C`.`Subject` AS `Subject`,`C`.`Status` AS `Status`,`U`.`ID` AS `UserID`,`U`.`user_email` AS `UserEmail`,`M`.`Access` AS `Access`,`U`.`display_name` AS `Teacher`,`M`.`LastLogin` AS `LastLogin` 
    from (($iss_class `C` left join $iss_userclassmap `M` on((`M`.`ClassID` = `C`.`ClassID`))) left join $iss_users `U` on((`M`.`UserID` = `U`.`ID`)));";
    $wpdb->query($sql);   
    $sql = "CREATE VIEW $issv_student_accounts AS select `S`.`StudentID` AS `StudentID`,`S`.`StudentViewID` AS `StudentViewID`,`S`.`RegistrationYear` AS `RegistrationYear`,`S`.`ParentID` AS `ParentID`,`S`.`FatherFirstName` AS `FatherFirstName`,`S`.`FatherLastName` AS `FatherLastName`,`S`.`FatherEmail` AS `FatherEmail`,`S`.`MotherFirstName` AS `MotherFirstName`,`S`.`MotherLastName` AS `MotherLastName`,`S`.`MotherEmail` AS `MotherEmail`,`S`.`StudentFirstName` AS `StudentFirstName`,`S`.`StudentLastName` AS `StudentLastName`,`S`.`StudentGender` AS `StudentGender`,`S`.`StudentEmail` AS `StudentEmail`,`S`.`ISSGrade` AS `ISSGrade`,`M`.`UserID` AS `UserID`,`U`.`user_email` AS `UserEmail`,`M`.`Access` AS `Access`,`U`.`user_nicename` AS `NiceName`,`M`.`LastLogin` AS `LastLogin`,`S`.`StudentStatus` AS `StudentStatus` 
    from (($iss_student `S` left join $iss_userstudentmap `M` on((`M`.`StudentID` = `S`.`StudentID`))) left join $iss_users `U` on((`U`.`ID` = `M`.`UserID`)));";
    $wpdb->query($sql); 
    $sql = "CREATE VIEW $issv_student_class_access AS select `S`.`StudentViewID` AS `StudentViewID`,`S`.`StudentFirstName` AS `StudentFirstName`,`S`.`StudentLastName` AS `StudentLastName`,`C`.`ClassID` AS `ClassID`,`S`.`RegistrationYear` AS `RegistrationYear`,`C`.`ISSGrade` AS `ISSGrade`,`C`.`Subject` AS `Subject`,`C`.`Suffix` AS `Suffix`,`M`.`UserID` AS `UserID`,`M`.`Access` AS `Access` 
    from (($iss_class `C` join $iss_student `S` on(((`S`.`ISSGrade` = `C`.`ISSGrade`) and (`S`.`RegistrationYear` = `C`.`RegistrationYear`)))) join $iss_userstudentmap `M` on((`M`.`StudentID` = `S`.`StudentID`))) where ((`C`.`Status` = 'active') and (`S`.`StudentStatus` = 'active'));";
    $wpdb->query($sql); 
    $sql = "CREATE VIEW $issv_student_lastlogin AS select `P`.`StudentID` AS `StudentID`,max(`P`.`LastLogin`) AS `LastLogin` 
    from $iss_userstudentmap `P` where (`P`.`LastLogin` is not null) group by `P`.`StudentID`;";
    $wpdb->query($sql); 
    $sql = "CREATE VIEW $issv_student_score_byassignmenttype AS select `T`.`ClassID` AS `ClassID`,`T`.`AssignmentTypeID`,`T`.`TypeName` AS `TypeName`,`G`.`StudentViewID` AS `StudentViewID`,max(`T`.`TypePercentage`) AS `TypePercentage`,ifnull(((((sum(`G`.`Score`) / sum(`A`.`PossiblePoints`)) * 100) * max(`T`.`TypePercentage`)) / 100),max(`T`.`TypePercentage`)) AS `TypeGrade` 
    from (($iss_assignment_type `T` left join $iss_assignment `A` on((`A`.`AssignmentTypeID` = `T`.`AssignmentTypeID`))) left join $iss_score `G` on((`G`.`AssignmentID` = `A`.`ID`))) group by `T`.`ClassID`,`T`.`AssignmentTypeID`,`T`.`TypeName`,`G`.`StudentViewID`;";
    $wpdb->query($sql);
    $sql = "CREATE VIEW $issv_student_scores AS select `S`.`StudentViewID` AS `StudentViewID`,`A`.`ID` AS `AssignmentID`,`A`.`AssignmentTypeID` AS `AssignmentTypeID`,`A`.`DueDate` AS `DueDate`,`A`.`PossiblePoints` AS `PossiblePoints`,`G`.`Score` AS `Score`,`G`.`Comment` AS `Comment`,`C`.`ClassID` AS `ClassID`,`P`.`post_title` AS `Title`,`S`.`StudentFirstName` AS `StudentFirstName`,`S`.`StudentLastName` AS `StudentLastName`,`S`.`ISSGrade` AS `ISSGrade`,`C`.`Subject` AS `Subject` 
    from (((($iss_assignment `A` join $iss_posts `P` on((`A`.`ID` = `P`.`ID`))) join $iss_class `C` on((`C`.`ClassID` = `A`.`ClassID`))) join $iss_student `S` on(((`S`.`ISSGrade` = `C`.`ISSGrade`) and (`S`.`RegistrationYear` = `C`.`RegistrationYear`)))) left join $iss_score `G` on(((`G`.`AssignmentID` = `A`.`ID`) and (`G`.`StudentViewID` = `S`.`StudentViewID`)))) where (`S`.`StudentStatus` = 'active');";
    $wpdb->query($sql);
    $sql = "CREATE VIEW $issv_teacher_class_access AS select `C`.`ClassID` AS `ClassID`,`C`.`RegistrationYear` AS `RegistrationYear`,`C`.`ISSGrade` AS `ISSGrade`,`C`.`Subject` AS `Subject`,`C`.`Suffix` AS `Suffix`,`M`.`UserID` AS `UserID`,`M`.`Access` AS `Access` 
    from ($iss_class `C` join $iss_userclassmap `M`) where ((`C`.`Status` = 'active') and (`C`.`ClassID` = `M`.`ClassID`));";
    $wpdb->query($sql);
    $sql = "CREATE VIEW $issv_teacher_name AS select `M`.`ClassID` AS `ClassID`,`M`.`UserID` AS `UserID`,`U`.`display_name` AS `Teacher`,`M`.`Access` AS `Access` 
    from ($iss_userclassmap `M` join $iss_users `U`) where (`U`.`ID` = `M`.`UserID`);";
    $wpdb->query($sql);

//  issstudentrole
    $user_id = username_exists( 'testparent1' );
    if ( !$user_id and email_exists('parent1@testmail.com') == false ) 
    {    
        $role = 'issparentrole';
        $user_id = wp_create_user('testparent1', 'Password1', 'parent1@testmail.com');
        wp_update_user(array('ID' => $user_id, 'role' => $role, 'display_name' => 'parent1', 'nickname' => 'parent1', 'first_name' => 'parent1', 'last_name' => 'parent1'));      
        $wpdb->insert($iss_userstudentmap, array('UserID' => $user_id, 'StudentID' => 1280), array("%d", "%d"));
        $wpdb->insert($iss_userstudentmap, array('UserID' => $user_id, 'StudentID' => 1351), array("%d", "%d"));   
    }
    $user_id = username_exists( 'testparent2' );
    if ( !$user_id and email_exists('parent2@testmail.com') == false ) 
    {
        $user_id = wp_create_user('testparent2', 'Password1', 'parent2@testmail.com');
        wp_update_user(array('ID' => $user_id, 'role' => $role, 'display_name' => 'parent2', 'nickname' => 'parent2', 'first_name' => 'parent2', 'last_name' => 'parent2'));      
        $wpdb->insert($iss_userstudentmap, array('UserID' => $user_id, 'StudentID' => 1314), array("%d", "%d"));   
    }
    $user_id = username_exists( 'teststudent1' );
    if ( !$user_id and email_exists('student1@testmail.com') == false ) 
    {
        $role = 'issstudentrole';
        $user_id = wp_create_user('teststudent1', 'Password1', 'student1@testmail.com');
        wp_update_user(array('ID' => $user_id, 'role' => $role, 'display_name' => 'student1', 'nickname' => 'student1', 'first_name' => 'student1', 'last_name' => 'student1'));      
        $wpdb->insert($iss_userstudentmap, array('UserID' => $user_id, 'StudentID' => 1280), array("%d", "%d"));
    }
    $user_id = username_exists( 'testteacher1' );
    if ( !$user_id and email_exists('teacher1@testmail.com') == false ) 
    {
        $role = 'issteacherrole';
        $user_id = wp_create_user('testteacher1', 'Password1', 'teacher1@testmail.com');
        wp_update_user(array('ID' => $user_id, 'role' => $role, 'display_name' => 'teacher1', 'nickname' => 'teacher1', 'first_name' => 'teacher1', 'last_name' => 'teacher1'));        
        $wpdb->insert($iss_userclassmap, array('UserID' => $user_id, 'ClassID' => 1, 'Access' => 'primary'), array("%d", "%d", "%s"));
    }
    $user_id = username_exists( 'testteacher2' );
    if ( !$user_id and email_exists('teacher2@testmail.com') == false ) 
    {
        $role = 'issteacherrole';
        $user_id = wp_create_user('testteacher2', 'Password1', 'teacher2@testmail.com');
        wp_update_user(array('ID' => $user_id, 'role' => $role, 'display_name' => 'teacher2', 'nickname' => 'teacher2', 'first_name' => 'teacher2', 'last_name' => 'teacher2'));        
        $wpdb->insert($iss_userclassmap, array('UserID' => $user_id, 'ClassID' => 3, 'Access' => 'primary'), array("%d", "%d", "%s"));
        $wpdb->insert($iss_userstudentmap, array('UserID' => $user_id, 'StudentID' => 1314), array("%d", "%d"));   
    }
}
function issv_sqlview_uninstall() {
    global $wpdb;
    $prefix = $wpdb->prefix;
    
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
   
    $wpdb->query("DROP VIEW $issv_classes");
    $wpdb->query("DROP VIEW $issv_class_students");
    $wpdb->query("DROP VIEW $issv_class_assignments");
    $wpdb->query("DROP VIEW $issv_student_accounts");
    $wpdb->query("DROP VIEW $issv_student_class_access");
    $wpdb->query("DROP VIEW $issv_student_lastlogin");
    $wpdb->query("DROP VIEW $issv_student_score_byassignmenttype");
    $wpdb->query("DROP VIEW $issv_student_scores");
    $wpdb->query("DROP VIEW $issv_teacher_class_access");
    $wpdb->query("DROP VIEW $issv_teacher_name");

    $the_user = get_user_by('login', 'testparent1');  $uid = $the_user->ID; wp_delete_user($uid);
   // $wpdb->delete($iss_userstudentmap, array('UserID' => $uid, 'StudentID' => 1280), array("%d", "%d"));
   //$wpdb->delete($iss_userstudentmap, array('UserID' => $uid, 'StudentID' => 1351), array("%d", "%d"));
 
    $the_user = get_user_by('login', 'testparent2');  $uid = $the_user->ID; wp_delete_user($uid);
    //$wpdb->delete($iss_userstudentmap, array('UserID' => $uid, 'StudentID' => 1314), array("%d", "%d"));
    
    $the_user = get_user_by('login', 'teststudent1');  $uid = $the_user->ID; wp_delete_user($uid);
    //$wpdb->delete($iss_userstudentmap, array('UserID' => $uid, 'StudentID' => 1280), array("%d", "%d"));
    
    $the_user = get_user_by('login', 'testteacher1');  $uid = $the_user->ID; wp_delete_user($uid);
    //$wpdb->delete($iss_userclassmap, array('UserID' => $uid, 'ClassID' => 1), array("%d", "%d"));
   
    $the_user = get_user_by('login', 'testteacher2');  $uid = $the_user->ID; wp_delete_user($uid);
    //$wpdb->delete($iss_userclassmap, array('UserID' => $uid, 'ClassID' => 3), array("%d", "%d"));
    //$wpdb->delete($iss_userstudentmap, array('UserID' => $uid, 'StudentID' => 1314), array("%d", "%d"));
   
}
register_activation_hook(__FILE__, 'issv_sqlview_install');
register_deactivation_hook(__FILE__, 'issv_sqlview_uninstall');

?>