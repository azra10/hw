
<div class="container">
<?php 
$cid = null;
$backurl = null;
if (isset($_GET['cid'])) {
    $cid = iss_sanitize_input($_GET['cid']);
    $backurl = admin_url('admin.php?page=issvalist') . "&cid={$cid}";
}
iss_show_heading_with_backurl("Assignment", $backurl);

if (isset($_GET['post'])) {
    $postid = iss_sanitize_input($_GET['post']);
}

// $postid is needed to include the view body
include(plugin_dir_path(__FILE__) . "/assignment_view_body.php");

?>
</div>





