<?php 
$tab = 'email';
include(plugin_dir_path(__FILE__) . "/assignment_header.php");
echo "<h4>Email Assignment</h4>";

$class = $post = $accounts = null;
$current_user = wp_get_current_user();
$accounts = array();

if (!empty($classid)) {
    $class = ISS_ClassService::LoadByID($classid);
    if (null != $class) {
        $accounts = ISS_StudentService::GetStudentEmails($class->ISSGrade, $classid);
    }
}

if (!empty($postid)) {
    $post = ISS_AssignmentService::LoadByID($postid);
    $attachments = ISS_AssignmentService::LoadAttachmentsByID($postid);
    }

if ((null == $class) || (null == $accounts) || (null == $post)) {
    echo 'Error sending assignment email.';
    exit();
}

$from = $current_user->user_email;
$to = $subject = $message = $errTo = $errSubject = $errMessage = null;

if (isset($_POST['_wpnonce-iss-email-class-form-page'])) {
    iss_write_log(($_POST));
    check_admin_referer('iss-email-class-form-page', '_wpnonce-iss-email-class-form-page');

    if (isset($_POST['subject']) && !empty($_POST['subject'])) {
        $subject = $_POST['subject'];
    } else {
        $errSubject = 'subject required';
    }
    if (isset($_POST['message']) && !empty($_POST['message'])) {
        $message = $_POST['message'];
    } else {
        $errMessage = 'message required';
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
        $fromemail = $rest . '@learnislam.org';
        $headers[] = 'From: ' . $fromemail;
        $toemail = $fromemail;

        echo "<div>From: {$fromemail}</div><div>To: {$toemail}</div><div>Bcc:</div>";
        echo '<table class="table-striped table-responsive table-condensed" border=1>';
        echo '<tr><th>Student</th><th>Student Email</th><th>School Email</th><th>Second Email</th></tr>';

        $uniqueemails = array();
        foreach ($accounts as $account) {
            echo "<tr><td>{$account->StudentFirstName} {$account->StudentLastName}";
            echo "</td><td>";
            if ((strpos($to, '1') !== false) && !empty($account->StudentEmail)) {
                $uniqueemails[$account->StudentEmail] = $account->StudentEmail;
                echo $account->StudentEmail;
            }
            echo '</td><td>';
            if ((strpos($to, '2') !== false) && !empty($account->SchoolEmail)) {
                $uniqueemails[$account->SchoolEmail] = $account->SchoolEmail;
                echo $account->SchoolEmail;
            }
            echo '</td><td>';
            if ((strpos($to, '3') !== false) && !empty($account->HomeEmail)) {
                $uniqueemails[$account->HomeEmail] = $account->HomeEmail;
                echo $account->HomeEmail;
            }
            echo '</td></tr>';
        }
        $uniqueemailsize = sizeof($uniqueemails);
        echo "<div> Unique Emails: {$uniqueemailsize}</div>";
        foreach ($uniqueemails as $uniqueemail) {
            $headers[] = 'BCC:' . $uniqueemail;
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
} else {
    $subject = "Assignment Grade{$post->ISSGrade}-{$post->Subject}, Due Date: {$post->DueDate}";
    $message = "<h3> {$post->Title} </h3>";
    $message = $message . "<div><strong>Class:</strong> Grade{$post->ISSGrade}-{$post->Subject}</div>";
    $message = $message . "<div><strong>Possible Point:</strong> {$post->PossiblePoints}</div>";
    $message = $message . "<div><strong>Due Date:</strong> {$post->DueDate}</div><hr/>";

    $content_post = get_post($postid);
    $content = $content_post->post_content;
    $content = apply_filters('the_content', $content);
    $content = str_replace(']]>', ']]&gt;', $content);
    $message = $message . $content;

    if (null != $attachments) {
        foreach ($attachments as $row) {
            $message = $message . "<div>  {$row['post_title']} <br/> {$row['guid']}  </div>";
        }
    }
}
?>

<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
    <?php wp_nonce_field('iss-email-class-form-page', '_wpnonce-iss-email-class-form-page') ?>
   
   
    <div class="form-group">
        <div class="col-sm-5">
            <label for="name" class="control-label">From: <?php echo $from; ?> </label>
        </div>
    
        <div class="col-sm-5">
            <label for="name" class="control-label">To: <?php echo $from; ?> </label>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-10">
            <label for="to" class="control-label">BCC: <?php echo "Grade{$class->ISSGrade}"; ?></label>
            
            <table class="table-striped table-responsive table-condensed" border=1>      
                <?php 
                echo "<tr><th><span class='text-danger'>{$errTo} </span></th>";
                echo "<th><input type='checkbox' name='studentemail' value='Yes' checked> Student Emails</th>";
                echo "<th><input type='checkbox' name='fatheremail' value='Yes' checked> Preferred School Emails</th>";
                echo "<th><input type='checkbox' name='motheremail' value='Yes' checked> Parents' Second Emails</th></tr>";

                foreach ($accounts as $account) {
                    $studentemails = $fathermemails = $motheremails = null;
                    echo "<tr class='more less'>";
                    echo "<td>{$account->StudentFirstName} {$account->StudentLastName}";
                    echo "</td><td>";
                    if (!empty($account->StudentEmail)) {
                        echo $account->StudentEmail;
                    }
                    echo "</td><td>";
                    if (!empty($account->SchoolEmail)) {
                        echo $account->SchoolEmail;
                    }
                    echo "</td><td>";
                    if (!empty($account->HomeEmail)) {
                        echo $account->HomeEmail;
                    }
                    echo "</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-10">
            <a class="morelink btn btn-info btn-sm">Show Emails...</a>
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
            <?php wp_editor($message, 'message', array('media_buttons' => false, 'textarea_rows' => 15)); ?>
        </div>
    </div>
    <div class="form-group panel-footer">
        <div class="col-sm-10 col-">
            <input id="submit" name="submit" type="submit" value="Send Email" class="btn btn-primary">
        </div>
    </div>
    
</form> 
<?php include(plugin_dir_path(__FILE__) . "/assignment_footer.php");  ?>
<style>
.less {
    display: none;
}
</style>
<script type="text/javascript">

    $(document).ready(function () {
        var moretext = "Show Emails...";
        var lesstext = "Hide Emails...";

        $(".morelink").click(function () {
            if ($('.more').hasClass("less")) {
                $('.more').removeClass("less");
                $(this).html(lesstext);
            } else {
                $('.more').addClass("less");
                $(this).html(moretext);
            }
            return false;
        });
    });
</script>