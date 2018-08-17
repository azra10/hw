<?php 

if (isset($_POST['_wpnonce-iss-user-account-form-page'])) {
    check_admin_referer('iss-user-account-form-page', '_wpnonce-iss-user-account-form-page');
   
    if (isset($_POST['classid']) && isset($_POST['userid'])) {

        $result = ISS_ClassService::AddMapping($_POST['classid'], $_POST['userid'],$_POST['access']);
       
        if (1 == $result) {
            echo "<div class=\"container text-primary\"><p><strong>Account Mapped.</strong></p></div>";
            exit;
        } else {
            echo "<div class=\"container text-danger\"><p><strong>Error Mapping Account.</strong></p></div>";
             exit;
        }
    }
}

$class = null;
$cid = null;
$uid = null;
if (isset($_GET['cid'])) {
    $cid = iss_sanitize_input($_GET['cid']);
}
if (isset($_GET['uid'])) {
    $uid = iss_sanitize_input($_GET['uid']);
}
if (!empty($cid)) {
    $class = ISS_ClassService::LoadByID($cid);
}
if (!empty($uid)) {
    $class = ISS_ClassService::LoadByUserID($cid, $uid);
    if (null == $class) {
        echo 'Error mapping user';
        exit;
    }
    if ($class->ClassID == $cid) {
        echo "<div class=\"container \">User already mapped</div>";
        exit;
    }
}

iss_show_heading("Class: {$class->Name} {$class->Teacher}  ");

?> 

<div class="container">
<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
    <?php wp_nonce_field('iss-user-account-form-page', '_wpnonce-iss-user-account-form-page') ?>
    <div class="row">
        Class ID: <input type="text" id="classid" name="classid" required value="<?php echo $class->ClassID; ?>" />          
        User ID: <input type="text" id="userid" name="userid" reuired value="<?php echo $class->UserID; ?>" />   
        Access: <select  id="access" name="access" >
        <option>read</option>
        <option>write</opion>
        </select>
        <button type="submit" name="submit" value="user" class="btn btn-primary ">Connect</button>		     
    </div> 
</form>
</div>

