<div class="container">
<?php 
 $backurl = admin_url('users.php?page=issvuserlist');
 iss_show_heading_with_backurl("Remove Student User Map", $backurl);

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
    echo "<br/><br/>Student: {$student->StudentFirstName} {$student->StudentLastName} <br/> Grade: {$student->ISSGrade} <br/> Email: {$student->UserEmail}";
   
    $result = ISS_UserStudentMapService::RemoveMapping($sid,$uid);

    if (1 == $result) {
         echo "<h4>Mapping Removed (Delete the user for Users options).</h4>";
        exit;
    }
}

echo "<h4>Error Removing Mapping.</h4>";
?>
</div>

