<div class="container">
<?php
$backurl = admin_url('admin.php?page=issvclasslist');
iss_show_heading_with_backurl("Email Class", $backurl);

$cid = $class = null;;
$current_user = wp_get_current_user();
$accounts = array();

if (isset($_GET['cid'])) {
    $cid = iss_sanitize_input($_GET['cid']);
    if (!empty($cid)) {
        $class = ISS_ClassService::LoadTeacherAccountByClassID($cid);
    }
}
iss_write_log("Email Class " . $cid);

$from = $current_user->user_email;
$to = $subject = $message = null;
$errTo = $errSubject = $errMessage = null;

if (isset($_POST['_wpnonce-iss-email-class-form-page'])) {
    iss_write_log(($_POST));
    check_admin_referer('iss-email-class-form-page', '_wpnonce-iss-email-class-form-page');

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
    }
    if (isset($_POST['fatheremail']) && !empty($_POST['fatheremail']) && ($_POST['fatheremail'] == 'Yes')) {
        $to = $to . '2';
    }
    if (isset($_POST['motheremail']) && !empty($_POST['motheremail']) && ($_POST['motheremail'] == 'Yes')) {
        $to = $to . '3';
    }
    
    if (!$to) {
        $errTo = ' required';
    }

    if (!$errSubject && !$errMessage && !$errTo) {
        $headers[] = 'Content-Type: text/html; charset=UTF-8';

        $pos = strpos($from, "@");
        $rest = substr($from, 0, $pos);
        $headers[] = 'From: ' . $rest . '@learnislam.org';
        $toemail = $rest . '@learnislam.org';

        $accounts = ISS_StudentService::GetStudentEmails($class->ISSGrade);
        iss_write_log($accounts);
        echo '<div class="alert alert-success">Sending Email to following recipients:</div>';
        foreach($accounts as $account) {
            if ((strpos($to, '1') !== false ) && !empty($account->StudentEmail)) // Student Email
            { $headers[] = 'BCC:' . $account->StudentEmail; echo "<br/>{$account->StudentEmail}"; }
            
            if ((strpos($to, '2') !== false ) && !empty($account->SchoolEmail)) // School Email
            { $headers[] = 'BCC:' . $account->SchoolEmail;  echo "<br/>School: {$account->SchoolEmail}";}
           
            if ((strpos($to, '3') !== false ) && ($account->SchoolEmail != $account->FatherEmail) && !empty($account->FatherEmail))
            { $headers[] = 'BCC:' . $account->FatherEmail;  echo "<br/>Second: {$account->FatherEmail}";}
            else if ((strpos($to, '3') === 2 ) && ($account->SchoolEmail != $account->MotherEmail) && !empty($account->MotherEmail))
            { $headers[] = 'BCC:' . $account->MotherEmail;  echo "<br/>Second: {$account->MotherEmail}";}
        }
        iss_write_log('To: ' . $toemail);
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
<?php wp_nonce_field('iss-email-class-form-page', '_wpnonce-iss-email-class-form-page') ?>
   

	<div class="form-group">
		<div class="col-sm-10">
        <label for="name" class="control-label">From: <?php echo $from; ?> </label>
       </div>
    </div>
    <div class="form-group">
		<div class="col-sm-10">
        <label for="to" class="control-label">To: <?php echo "Grade{$class->ISSGrade} <span class='text-danger'>{$errTo} </span>"; ?></label>
        <table class="table">
		    <?php 
        
            echo "<tr><td><input type='checkbox' name='studentemail' value='Yes' checked>  <b>Student Email</b></td></tr>";
       
            echo "<tr><td><input type='checkbox' name='fatheremail' value='Yes' checked> <b>Preferred School Email</td></tr>";
       
            echo "<tr><td><input type='checkbox' name='motheremail' value='Yes' checked> <b>Parents' Second Email (Optional)</b> </td></tr>";       
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
			<input id="submit" name="submit" type="submit" value="Send" class="btn btn-primary">
		</div>
	</div>

</form> 
</div>