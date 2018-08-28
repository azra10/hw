
<div class="container">
<?php
$backurl = admin_url('/');
iss_show_heading_with_backurl("Contact Admin", $backurl);

$errName = $errEmail = $errMessage = null;

if (isset($_POST['_wpnonce-iss-email-account-form-page'])) {
    iss_write_log(($_POST));
    check_admin_referer('iss-email-account-form-page', '_wpnonce-iss-email-account-form-page');
 
		// Check if name has been entered
    if (!$_POST['name']) {
        $errName = 'Please enter your name';
    } else {
        $name = $_POST['name'];
    }
		
	// Check if email has been entered and is valid
    // if (!$_POST['email'] || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    //     $errEmail = 'Please enter a valid email address';
    // } else {
    //     $from = $_POST['email'];
    // }
		
		//Check if message has been entered
    if (!$_POST['message']) {
        $errMessage = 'Please enter your message';
    } else {
        $message = $_POST['message'];
    }
    $copy = $_POST['copy'];
    $to = 'IslamicSchoolOfSiliconValley@learnislam.org'; // TODO make configurable
    $subject = "Contact Form Admin From {$name}";
    $body = "Message: {$message}";
    $current_user = wp_get_current_user();
    $from = $current_user->user_email;

    if (!$errName && !$errEmail && !$errMessage) {
        $headers[] = 'Content-Type: text/html; charset=UTF-8';
        $headers[] = 'From: ' . $from;
        if (!empty($copy) && ($copy == 'Yes')) {
            $headers[] = 'Cc: ' . $from;
        }
        iss_write_log("To: {$to}");
        iss_write_log($headers);
        if (wp_mail($to, $subject, $body, $headers)) {
            $result = '<div class="alert alert-success">Thank You for contacting us!</div>';
        } else {
            $result = '<div class="alert alert-danger">Sorry there was an error sending your message. Please try again later</div>';
        }
        echo $result;
        exit;
    }
}

?>

<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
<?php wp_nonce_field('iss-email-account-form-page', '_wpnonce-iss-email-account-form-page') ?>
   
	<div class="form-group">
        <label class="col-sm-offset-1"> Please include student name and grade for a quick response.</label>
        </div>
	<div class="form-group">
		<label for="name" class="col-sm-1 control-label">Your Name</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" id="name" name="name" placeholder="Student Name and Grade" value="<?php echo isset($_POST['name']) ?htmlspecialchars($_POST['name']):''; ?>">
			<?php echo "<p class='text-danger'>$errName</p>"; ?>
		</div>
    </div>
	<!-- <div class="form-group">
		<label for="email" class="col-sm-1 control-label">Your Email</label>
		<div class="col-sm-10">
			<input type="email" class="form-control" id="email" name="email" placeholder="example@domain.com" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
			<?php //echo "<p class='text-danger'>$errEmail</p>"; ?>
		</div>
	</div> -->
	<div class="form-group">
		<label for="message" class="col-sm-1 control-label">Message</label>
		<div class="col-sm-10">
			<textarea class="form-control" rows="4" name="message"><?php echo isset($_POST['message'])? htmlspecialchars($_POST['message']):''; ?></textarea>
			<?php echo "<p class='text-danger'>$errMessage</p>"; ?>
		</div>
    </div>
    <div class="form-group">
		<div class="col-sm-10 col-sm-offset-1">
        <span class="control-label"> <input type="checkbox" value="Yes" name="copy" style="margin:0;">  Send me a copy</span>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-10 col-sm-offset-1">
			<input id="submit" name="submit" type="submit" value="Send" class="btn btn-primary">
		</div>
	</div>

</form> 
</div>