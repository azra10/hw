<div class="container">
<?php 

$backurl = admin_url('users.php?page=issvuserlist');
iss_show_heading_with_backurl("Create Student User Account", $backurl);

$student = null;
$svid = null;

if (isset($_GET['svid'])) {
    $svid = iss_sanitize_input($_GET['svid']);

    $student = ISS_StudentService::LoadByStudentViewID($svid);
    if (null == $student) {
        echo "<h3> Student not found.</h3>";
        exit;
    }
}
if (isset($_POST['_wpnonce-iss-user-account-form-page'])) {
    iss_write_log(($_POST));
    check_admin_referer('iss-user-account-form-page', '_wpnonce-iss-user-account-form-page');

    if (isset($_POST['studentid']) && isset($_POST['email']) && isset($_POST['role'])) {

        $studentid = $_POST['studentid'];
        $email_address = $_POST['email'];
        $role = $_POST['role'];
        $password = $_POST['password'];
        $role = $_POST['role'];
        $isscustomeditor = $_POST['isscustomeditor'];

        $error = null;
        $result = ISS_StudentService::CreateUserAccount($student, $email_address, $role, $password, $isscustomeditor, $error);

        if (1 == $result) {

            echo "<h4>Account Created (Mapping added, check the Students options)</h4>";
        } else {
            echo "<h4>Error Creating Account. {$error}</h4>";
            exit;
        }
        exit;
    } else {
        echo "<h4>Error Creating Account. {$error}</h4>";
        exit;
    }
}


$password = wp_generate_password(12, false);
$accounts = ISS_StudentService::GetStudentAccountsByStudentID($student->StudentID);
$content = "Salam,
Login to check homework and assessment at https://learnislam.org/engrade";
?>

<div><strong>Student: </strong>: <?php echo "{$student->StudentFirstName} {$student->StudentLastName} <b>{$student->StudentEmail}</b>"; ?><br/></div>
<div><strong>Father: </strong>: <?php echo "{$student->FatherFirstName} {$student->FatherLastName} <b>{$student->FatherEmail}</b>"; ?><br/></div>
<div><strong>Student: </strong>: <?php echo "{$student->MotherFirstName} {$student->MotherLastName} <b>{$student->MotherEmail}</b>"; ?><br/></div>

 <hr/>
<h3>Existing Linked Accounts</h3>
<table class="table table-striped table-responsive table-condensed" id="iss_student_table" border=1>
    <thead>
        <tr>                     
            <th>Account</th> 
            <th>UserID</th> 
            <th>Last Login</th> 
        </tr>
    </thead>
    <tbody>
            <?php if (null != $accounts) {
                foreach ($accounts as $row) {
                    if (!empty($row->UserID)) { ?>
                <tr>              
                <td>
                    <?php echo $row->UserEmail;
                    if (ISS_PermissionService::user_manage_access() && !empty($row->StudentID)) {
                        echo "<br/><a href=\"admin.php?page=issvremoveuser&uid={$row->UserID}&sid={$row->StudentID}\">Remove Map</a>";
                    } ?> </td>
                <td><?php echo $row->UserID; ?> </td>
                <td><?php echo $row->LastLogin; ?> </td>
                 </tr>
                 <?php

            }
        }
    } ?>
    </tbody>
</table>

<h3>Add New Account</h3>
<div class="container">
    <form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
        <?php wp_nonce_field('iss-user-account-form-page', '_wpnonce-iss-user-account-form-page') ?>
        <div class="row">
        <div class="col-md-4">
            <input type="hidden" id="studentid" name="studentid" value="<?php echo $student->StudentID; ?>" />          
            <label class="control-label">Email Address:</label> <input type="text" id="email" name="email" class="form-control" reuired value="<?php $email_address ?>" />          
            <label class="control-label">Password:</label> <input type="text" id="password" name="password" class="form-control" reuired value="<?php echo $password; ?>" />          
            <label class="control-label">Access:</label> <select  id="role" name="role" class="form-control"  >   
            <option value="issstudentrole">ISS Student Role</opion>
            <option value="issparentrole">ISS Parent Role</opion>           
            </select> 
            <?php
            $editor_id = 'isscustomeditor';
            $settings = array('media_buttons' => false);
            wp_editor($content, $editor_id, $settings);
            ?>
            <br/>
            <button type="submit" name="submit" value="user" class="btn btn-primary form-control" >Create Account</button>		     
        </div> 
        </div>
    </form>
</div>
</div>