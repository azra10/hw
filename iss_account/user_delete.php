<?php 

$student = null;
$sid = null;
$uid = null;
if (isset($_GET['sid'])) {
    $sid = iss_sanitize_input($_GET['sid']);
}
if (isset($_GET['uid'])) {
    $uid = iss_sanitize_input($_GET['uid']);
}

if (!empty($uid)) {
    $student = ISS_StudentService::LoadByUserID($sid,$uid);
}

if (null != $student) {
    iss_show_heading("Remove Student User Map");
    $backurl = admin_url('users.php?page=issvactlist');
    echo "<a  href='{$backurl}'>Back to Student List</a>";
    echo "<br/><br/>Student: {$student->StudentFirstName} {$student->StudentLastName} <br/> Grade: {$student->ISSGrade} <br/> Email: {$student->UserEmail}";
   
    $result = ISS_StudentService::RemoveMapping($sid,$uid);

    if (1 == $result) {
         echo "<h4>Mapping Removed.</h4>";
        exit;
    }
}

echo "<h4>Error Removing Mapping.</h4>";
?>


