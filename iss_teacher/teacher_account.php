<?php 
$backurl = admin_url('users.php?page=issvtlist');

if (isset($_POST['_wpnonce-iss-user-account-form-page'])) {
    iss_show_heading_with_backurl("Map Teacher to Class ", $backurl);

    check_admin_referer('iss-user-account-form-page', '_wpnonce-iss-user-account-form-page');

    if (isset($_POST['classid']) && isset($_POST['userid'])) {
        $userid = $_POST['userid'];
        $classid = $_POST['classid'];
        $access = $_POST['access'];
        $result = ISS_UserClassMapService::AddMapping($classid, $userid, $access);

        if (1 == $result) {
            $user = new WP_User($userid);
            if (null != $user) {
                $user->add_role('issteacherrole');
                iss_write_log($user);
                iss_write_log("teacher role added to user ");
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

$class = null;
$cid = null;
$uid = null;
if (isset($_GET['cid'])) {
    $cid = iss_sanitize_input($_GET['cid']);
    if (!empty($cid)) {
        $class = ISS_ClassService::LoadByID($cid);
    }
}
// if (isset($_GET['uid'])) {
//     $uid = iss_sanitize_input($_GET['uid']);
// }

// if (!empty($uid)) {
//     $class = ISS_ClassService::LoadByUserID($cid, $uid);
//     if (null == $class) {
//         echo '<h4>Error Mapping User</h4>';
//         exit;
//     }
//     if ($class->ClassID == $cid) {
//         echo "<h4>User Already Mapped</h4>";
//         exit;
//     }
// }

    iss_show_heading_with_backurl("Map Teacher to Class {$class->Name} ", $backurl);

    ?> 

<div class="container">
<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
    <?php wp_nonce_field('iss-user-account-form-page', '_wpnonce-iss-user-account-form-page') ?>
    <div class="row">
        <div class="col-md-4">
        <label>Class ID:</label> <input type="text" id="classid" name="classid" class="form-control" required value="<?php echo $class->ClassID; ?>" />        
        <label>User ID: </label><input type="text" id="userid" name="userid"  class="form-control" reuired value="" />   
        <label>Access:</label> <select  id="access" name="access" class="form-control"  >   
        <option value="primary">Primary</opion>
        <option value="write">Write</opion>
        <option value="read">Read</option>        
        </select>  
        <br/>
        <button type="submit" name="submit" value="user" class="btn btn-primary form-control" ">Connect</button>
        </div>		     
    </div> 
</form>
</div>

