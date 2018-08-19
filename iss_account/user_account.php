<?php 
$backurl = admin_url('users.php?page=issvactlist');

if (isset($_POST['_wpnonce-iss-user-account-form-page'])) {
    iss_show_heading_with_backurl("Map User to Student ",$backurl);

    check_admin_referer('iss-user-account-form-page', '_wpnonce-iss-user-account-form-page');

    if (isset($_POST['studentid']) && isset($_POST['userid'])) {
        $userid = $_POST['userid'];
        $studentid = $_POST['studentid'];
        $result = ISS_StudentService::AddMapping($studentid, $userid);

        if (1 == $result) {
            $user = new WP_User($userid);
            if (null != $user) {
                $user->set_role('issstudentrole');
                iss_write_log($user);
                iss_write_log("student role added to user ");
                iss_write_log($user->roles);
            }
             echo "<h4>Account Mapped</h4>";
            exit;
        } else {
            echo "<h4>Error Mapping Account.</h4>";
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
    $student = ISS_StudentService::LoadByUserID($sid, $uid);
    if (null == $student) {
        echo '<h4>Error Mapping User</h4>';
        exit;
    }
    if ($student->StudentID == $sid) {
        echo "<h4>User already mapped</h4>";
        exit;
    }
}

iss_show_heading_with_backurl("Map User to Student  {$student->StudentFirstName} {$student->StudentLastName} - Grade: {$student->ISSGrade} ",$backurl);
?> 

<div class="container">
<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
    <?php wp_nonce_field('iss-user-account-form-page', '_wpnonce-iss-user-account-form-page') ?>
    <div class="row">
    <div class="col-md-4">
        <label>Student ID:</label> <input type="text" id="studentid" name="studentid" class="form-control" required value="<?php echo $student->StudentID; ?>" />          
        <label>User ID:</label> <input type="text" id="userid" name="userid" class="form-control" reuired value="<?php echo $student->UserID; ?>" />          
        <br/>
        <button type="submit" name="submit" value="user" class="btn btn-primary form-control" >Connect</button>		     
    </div> 
</form>
</div>
<?php 
