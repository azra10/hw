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
    iss_show_heading("Student: Account {$student->StudentFirstName} {$student->StudentLastName} - Grade: {$student->ISSGrade} - Email: {$student->UserEmail}");

    $result = ISS_StudentService::RemoveMapping($sid,$uid);

    if (1 == $result) {
        echo "<h4>User removed.</h4>";
        exit;
    }
}
echo "<h4>Error removing user.</h4>";
?>


