<?php 

$backurl = null;   $cid = null;
if (isset($_GET['cid'])) {
    $cid = iss_sanitize_input($_GET['cid']);       
    $backurl = admin_url('admin.php?page=issvalist') . "&cid={$cid}";
}

iss_show_heading_with_backurl("Delete Assignment ", $backurl);

$post = null;
if (isset($_GET['post'])) {
    $postid = iss_sanitize_input($_GET['post']);
    if (!empty($postid)) {
        $result = ISS_AssignmentService::DeleteByPostID($postid);
       
        if ($result == 1) {
            echo "<h4>Assignment Deleted</h4>";
        }
     
        exit;

    }
}
echo "<h3> Error deleting the assignment, please <a  href=\"admin.php?page=email-admin\">Email Admin</a>.</h3>";

?>