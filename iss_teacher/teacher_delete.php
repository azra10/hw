<?php 
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
    iss_show_heading("Class: {$class->Name} {$class->Teacher} - Email: {$class->UserEmail}");

    $result = ISS_ClassService::RemoveMapping($cid, $uid);

    if (1 == $result) {
        echo "<h4>User removed.</h4>";
        exit;
    }
}
echo "<h4>Error removing user.</h4>";
?>


