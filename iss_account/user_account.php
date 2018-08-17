<?php 

if (isset($_POST['_wpnonce-iss-user-account-form-page'])) {
    check_admin_referer('iss-user-account-form-page', '_wpnonce-iss-user-account-form-page');
   
    if (isset($_POST['studentid']) && isset($_POST['userid'])) {

        $result = ISS_StudentService::AddMapping($_POST['studentid'], $_POST['userid']);
       
        if (1 == $result) {
            $user = new WP_User($uid); 
            if (null != $user){
                $user->set_role('iss_student');
                iss_write_log("student role added to user " . $userid);
                iss_write_log($user->roles);           
            }
            echo "<div class=\"container text-primary\"><p><strong>Account Mapped.</strong></p></div>";
            exit;
        } else {
            echo "<div class=\"container text-danger\"><p><strong>Error Mapping Account.</strong></p></div>";
            echo $result->get_error_message();
            exit;
        }
    }
}

$student = null;
$sid = null;
$uid = null;
if (isset($_GET['sid'])) {
    $sid = iss_sanitize_input($_GET['sid']);
}
if (isset($_GET['uid'])) {
    $uid = iss_sanitize_input($_GET['uid']);
}
if (!empty($sid)) {
    $student = ISS_StudentService::LoadByID($sid);
}
if (!empty($uid)) {
    $student = ISS_StudentService::LoadByUserID($sid,$uid);
    if (null == $student) {
        echo 'Error mapping user';
        exit;
    }
    if ($student->StudentID == $sid) {
        echo "<div class=\"container \">User already mapped</div>";
        exit;
    }
}

iss_show_heading("Student: Account {$student->StudentFirstName} {$student->StudentLastName} - Grade: {$student->ISSGrade} ");

?> 

<div class="container">
<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
    <?php wp_nonce_field('iss-user-account-form-page', '_wpnonce-iss-user-account-form-page') ?>
    <div class="row">
        Student ID: <input type="text" id="studentid" name="studentid" required value="<?php echo $student->StudentID; ?>" />          
        User ID: <input type="text" id="userid" name="userid" reuired value="<?php echo $student->UserID; ?>" />          
        <button type="submit" name="submit" value="user" class="btn btn-primary ">Connect</button>		     
    </div> 
</form>
</div>
<?php 
