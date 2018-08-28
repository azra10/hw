<div class="container">
<?php
$cid = null;
if (isset($_GET['cid'])) {
    $cid = iss_sanitize_input($_GET['cid']);
}
$backurl = admin_url('admin.php?page=issvstudentlist&cid=') . $cid;
iss_show_heading_with_backurl("Email Student", $backurl);
iss_write_log("Email Student");

$current_user = wp_get_current_user();
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
iss_write_log($student);
$from = $current_user->user_email;
$to = $subject = $message = null;
$errTo = $errSubject = $errMessage = null;

if (isset($_POST['_wpnonce-iss-email-teacher-form-page'])) {
    iss_write_log(($_POST));
    check_admin_referer('iss-email-teacher-form-page', '_wpnonce-iss-email-teacher-form-page');

    if (isset($_POST['subject']) && !empty($_POST['subject'])) {
        $subject = $_POST['subject'];
    } else {
        $errSubject = ' required';
    }
    if (isset($_POST['message']) && !empty($_POST['message'])) {
        $message = $_POST['message'];
    } else {
        $errMessage = ' required';
    }
    if (isset($_POST['copy']) && !empty($_POST['copy'])) {
        $copy = $_POST['copy'];
    }
    $to = null;
    $toemail = array();
    if (isset($_POST['studentemail']) && !empty($_POST['studentemail']) && ($_POST['studentemail'] == 'Yes')) {
        $to = '1';
        $toemail[] = $student->StudentEmail;
    }
    if (isset($_POST['fatheremail']) && !empty($_POST['fatheremail']) && ($_POST['fatheremail'] == 'Yes')) {
        $to = $to . '2';
        $toemail[] = $student->FatherEmail;
    }
    if (isset($_POST['motheremail']) && !empty($_POST['motheremail']) && ($_POST['motheremail'] == 'Yes')) {
        $to = $to . '3';
        $toemail[] = $student->MotherEmail;
    }

    if (!$to) {
        $errTo = ' required';
    }
	
    // If there are no errors, send the email
    if (!$errSubject && !$errMessage && !$errTo) {
        $headers[] = 'Content-Type: text/html; charset=UTF-8';

        $pos = strpos($from, "@");
        $rest = substr($from, 0, $pos);
        $headers[] = 'From: ' . $rest . '@learnislam.org';

        if (!empty($copy) && ($copy == 'Yes'))
            $headers[] = 'Cc: ' . $from;

        iss_write_log($toemail);
        iss_write_log($headers);

        if (wp_mail($toemail, $subject, $message, $headers)) {
            echo '<div class="alert alert-success">Email sent!</div>';
        } else {
            echo '<div class="alert alert-danger">Sorry there was an error sending your message. Please try again later</div>';
        }
        exit;
    }
}

?>

<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
<?php wp_nonce_field('iss-email-teacher-form-page', '_wpnonce-iss-email-teacher-form-page') ?>
   

	<div class="form-group">
		<div class="col-sm-10">
        <label for="name" class="control-label">From: <?php echo $from; ?> </label>
       </div>
    </div>
    <div class="form-group">
		<div class="col-sm-10">
        <label for="to" class="control-label">To: <?php echo "<span class='text-danger'>$errTo</span>"; ?></label>
        <table class="table">
		    <?php 
        if (!empty($student->StudentEmail)) {
            echo "<tr><td><input type='checkbox' name='studentemail' value='Yes' checked>  <b>Student:</b> {$student->StudentEmail}</td></tr>";
        }
        if (!empty($student->FatherEmail)) {
            echo "<tr><td><input type='checkbox' name='fatheremail' value='Yes' checked> <b>Father:</b>  {$student->FatherEmail}</td></tr>";
        }
        if (!empty($student->MotherEmail)) {
            echo "<tr><td><input type='checkbox' name='motheremail' value='Yes' checked> <b>Mother:</b>  {$student->MotherEmail}</td></tr>";
        }
        ?></table>
		</div>
    </div>
    <div class="form-group">
		<div class="col-sm-10">
		<label for="subejct" class="control-label">Subject: <?php echo "<span class='text-danger'>$errSubject</span>"; ?></label> 
			<input type="text" required class="form-control" id="subject" name="subject" placeholder="Subject" value="<?php echo $subject ?>">
			
		</div>
    </div>
	<div class="form-group">
		<div class="col-sm-10">
            <label for="message" class="control-label"><?php echo "<span class='text-danger'>$errMessage</span>"; ?></label>		     
            <?php wp_editor($message, 'message', array('media_buttons' => false, 'textarea_rows' => 5)); ?>
 		</div>
    </div>
    <div class="form-group">
		<div class="col-sm-10 col-">
        <span class="control-label"> <input type="checkbox" value="Yes" id="copy" name="copy" style="margin:0;">  Send me a copy</span>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-10 col-">
			<input id="submit" name="submit" type="submit" value="Send" class="btn btn-primary">
		</div>
	</div>

</form> 
</div>