<div class="container">
<?php 
 $backurl = admin_url('users.php?page=issvuserlist');
 iss_show_heading_with_backurl("Student Status Change", $backurl);

$student = null;
$svid = null;

if (isset($_GET['svid'])) {
    $svid = iss_sanitize_input($_GET['svid']);
}

if (!empty($svid)) {
    $student = ISS_StudentService::LoadByStudentViewID($svid);
}

if (null != $student) {
    echo "<br/><br/>Student: {$student->StudentFirstName} {$student->StudentLastName} <br/> Grade: {$student->ISSGrade} <br/> ";
   
    $result = ISS_StudentService::ChangeStatus($svid);

    if (!empty($result)) {
         echo "<h4>Student Status Change to  {$result}.</h4>";
        exit;
    }
}

echo "<h4>Error Changing Status.</h4>";
?>
</div>


