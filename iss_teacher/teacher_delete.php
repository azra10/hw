<?php 

$backurl = admin_url('users.php?page=issvtlist');
iss_show_heading_with_backurl("Remove Teacher Map to Class",$backurl);

$class = null;
$cid = null;
$uid = null;
if (isset($_GET['cid'])) {
    $cid = iss_sanitize_input($_GET['cid']);
}
if (isset($_GET['uid'])) {
    $uid = iss_sanitize_input($_GET['uid']);
}

if (!empty($uid)) {
    $class = ISS_ClassService::LoadByUserID($cid, $uid);
}
    

if (null != $class) {
     echo "<br/><br/>  Teacher: {$class->Teacher} <br/> Email: {$class->UserEmail} <br/> Class {$class->Name}";
 
    $result = ISS_ClassService::RemoveMapping($cid, $uid);

    if (1 == $result) {
        $userobj = new WP_User($uid);
        if (null != $userobj) {
            $userobj->set_role('subscriber');
            iss_write_log("teacher role added to user " . $uid);
            iss_write_log($userobj->roles);
        }

        echo "<h4>Mapping Removed.</h4>";
        exit;
    }
}

echo "<h4>Error Removing Mapping.</h4>";

?>