<div class="container">
<?php
$backurl = admin_url('admin.php?page=issvclasslist');
iss_show_heading_with_backurl("Email Teacher", $backurl);

$current_user = wp_get_current_user();
$class = null;
$cid = null;
if (isset($_GET['cid'])) {
    $userid = get_current_user_id();
    $cid = iss_sanitize_input($_GET['cid']);
    iss_write_log('Email Teacher ClassID: ' . $cid . ' userid:' . $userid);
    $class = ISS_ClassService::LoadPrimaryTeacherAccountByClassID($cid);

    if ((null == $class) || !isset($class->UserEmail)) {
        echo "<h3> Class/Teacher not found.</h3>";
        exit;
    }
}
iss_write_log($class);
$from = $current_user->user_email;
$toemail = $class->UserEmail;
$subject = $message = null;
$errSubject = $errMessage = null;

if (isset($_POST['_wpnonce-iss-email-teacher-form-page'])) {
    iss_write_log(($_POST));
    check_admin_referer('iss-email-teacher-form-page', '_wpnonce-iss-email-teacher-form-page');

    if (isset($_POST['subject']) && !empty($_POST['subject'])) {
        $subject = $_POST['subject'];
    } else {
        $errSubject = 'subject is required';
    }
    if (isset($_POST['message']) && !empty($_POST['message'])) {
        $message = $_POST['message'];
    } else {
        $errMessage = ' message is required';
    }
    if (isset($_POST['copy']) && !empty($_POST['copy'])) {
        $copy = $_POST['copy'];
    }
    
	
    // If there are no errors, send the email
    if (!$errSubject && !$errMessage) {
        $headers[] = 'Content-Type: text/html; charset=UTF-8';
        // $pos = strpos($to, "@");
        // $toemail = substr($to, 0, $pos) . '@learnislam.org';
        $headers[] = 'From: ' . $from;

        if (!empty($copy) && ($copy == 'Yes'))
            $headers[] = 'Cc: ' . $from;

        iss_write_log('To:' . $toemail);
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
        <label for="to" class="control-label">To: <?php echo "{$toemail} <span class='text-success'> ($class->Name)</span>"; ?></label>
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