<?php 

iss_show_heading("Delete Assignment");

$backurl = admin_url('admin.php?page=issvalist');

$cid = null;
if (isset($_GET['cid'])) {
    $cid = iss_sanitize_input($_GET['cid']);       
    echo "<a  href='{$backurl}&cid={$cid}'>Back to Class Assignments</a>";
}

$post = null;
if (isset($_GET['postid'])) {
    $postid = iss_sanitize_input($_GET['postid']);
    if (!empty($postid)) {
        $result = ISS_AssignmentService::DeleteByPostID($postid);
       
        if ($result == 1) {
            echo "<h4>Assignment Deleted</h4>";
        }
     
        exit;

    }
}
echo "<h3> Error deleting the post, please <a  href=\"admin.php?page=email-admin\">Email Admin</a>.</h3>";

?>