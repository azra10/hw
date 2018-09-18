
<div>
<?php 
$classid = $backurl = null;
if (isset($_GET['cid'])) {
    $classid = iss_sanitize_input($_GET['cid']);
}
$backurl = admin_url('admin.php?page=issvalist') . "&cid={$classid}";
if (isset($_GET['svid'])) {
    $svid = iss_sanitize_input($_GET['svid']);
}
if (null != $svid) {
    $backurl = $backurl . "&svid={$svid}";
}
iss_show_heading_with_backurl("Assignment", $backurl);

if (isset($_GET['post'])) {
    $postid = iss_sanitize_input($_GET['post']);
}

// $postid is needed to include the view body
include(plugin_dir_path(__FILE__) . "/assignment_view_body.php");

?>
</div>