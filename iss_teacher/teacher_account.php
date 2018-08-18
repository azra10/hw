<?php 
$backurl = admin_url('users.php?page=issvtlist');

if (isset($_POST['_wpnonce-iss-user-account-form-page'])) {
    iss_show_heading("Map Teacher to Class ");

    check_admin_referer('iss-user-account-form-page', '_wpnonce-iss-user-account-form-page');

    if (isset($_POST['classid']) && isset($_POST['userid'])) {
        $userid = $_POST['userid'];
        $classid = $_POST['classid'];
        $access = $_POST['access'];
        $result = ISS_ClassService::AddMapping($classid, $userid, $access);

        if (1 == $result) {
            $user = new WP_User($userid);
            if (null != $user) {
                $user->set_role('issteacherrole');
                iss_write_log($user);
                iss_write_log("teacher role added to user ");
                iss_write_log($user->roles);
            }
            echo "<a  href='{$backurl}'>Back to Teachers List</a>";
            echo "<h4>Account Mapped</h4>";
            exit;
        } else {
            echo "<a  href='{$backurl}'>Back to Teachers List</a>";
            echo "<h4>Error Mapping Account.</h4>";
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
        echo '<h4>Error Mapping User</h4>';
        exit;
    }
    if ($class->ClassID == $cid) {
        echo "<h4>User Already Mapped</h4>";
        exit;
    }
}

iss_show_heading("Map Teacher to Class {$class->Name} ");
echo "<a  href='{$backurl}'>Back to Teachers List</a>";

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

