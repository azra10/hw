
<div>
<?php
if (isset($_GET['post']) || !empty($_GET['post'])) {
    $postid = iss_sanitize_input($_GET['post']);
} 
if (isset($_GET['cid']) || !empty($_GET['cid'])) {
    $classid = iss_sanitize_input($_GET['cid']);
}
$disabled = '';
if (null == $postid) {
    $disabled = ' disabled';
}
$args = "&post=" . $postid . "&cid=" . $classid;

$backurl = admin_url('admin.php?page=issvalist&cid=' . $classid);
iss_show_heading_with_backurl("Assignment", $backurl);

?>
 <div class="panel panel-warning">  
    <?php 
    if (ISS_PermissionService::class_assignment_write_access($classid)) { ?>
        <div class="panel-heading">
            <ul class="nav nav-tabs nav-justified">
                <li class="<?php if ($tab == 'edit') echo 'active'; ?>"><a href="<?php echo admin_url('admin.php?page=issvaadd') . $args; ?>">Edit</a></li> 
                <li class="<?php if ($tab == 'score') echo 'active'; echo $disabled; ?>"><a href="<?php echo admin_url('admin.php?page=issvascore') . $args; ?>">Grade</a></li>
                <li class="<?php if ($tab == 'email') echo 'active'; echo $disabled; ?>"><a href="<?php echo admin_url('admin.php?page=issvaemail') . $args; ?>">Email</a></li>
                <li class="<?php if ($tab == 'delete') echo 'active'; echo $disabled; ?>"><a href="<?php echo admin_url('admin.php?page=issvadelete') . $args; ?>">Delete </a></li> 
           </ul>
        </div>
        <?php 
    } 
    echo "<div class='panel-body'>";
    
