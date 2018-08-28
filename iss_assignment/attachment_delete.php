
<?php 
$backurl = $post  = $classid = $backid = null;
if (isset($_GET['backid'])) {
    $backid = iss_sanitize_input($_GET['backid']);
}
if (isset($_GET['cid'])) {
    $classid = iss_sanitize_input($_GET['cid']);
}

if (isset($_GET['post'])) {
    $postid = iss_sanitize_input($_GET['post']);
    if (!empty($postid)) {

        $result = ISS_AssignmentService::DeleteAttachmentByPostID($postid);
        if ($result == 1) {
            iss_show_heading("Attachment Deleted");
            echo "<table class='table'><tr><td><a href='admin.php?page=issvaadd&post={$backid}&cid={$classid}' class='btn btn-primary'>Continue Editing</a></td>";
            echo "<td><a href='admin.php?page=issvalist&cid={$classid}' class='btn btn-primary'>Finish Editing</a></td></tr></table>";
            exit;
        }
    }
}
$backurl = admin_url('admin.php?page=issvaadd') . "&cid={$classid}&post={$backid}";
iss_show_heading_with_backurl("Delete Attachment ", $backurl);
echo "<h3> Error deleting the attachment, please <a  href=\"admin.php?page=email-admin\">Email Admin</a>.</h3>";

?>