<div>
<?php

$backurl = admin_url('admin.php?page=issvclasslist');
iss_show_heading_with_backurl("Class Settings", $backurl);

$classid = $class = $args = $disabled = null;
if (isset($_GET['cid']) && !empty($_GET['cid']) && (intval($_GET['cid'])>0)) {
    $classid = iss_sanitize_input($_GET['cid']);
    if (null != $classid) {
        $args = '&cid=' . $classid;
    }
} 
if (null == $classid) {
    $disabled = ' disabled';
}
?>
 <div class="panel panel-warning">  
    <?php 
    if (ISS_PermissionService::class_assignment_write_access($classid)) { ?>
        <div class="panel-heading">
            <ul class="nav nav-tabs nav-justified">
                <li class="<?php if ($tab == 'edit') echo 'active'; ?>"><a href="<?php echo admin_url('admin.php?page=issvaddclass') . $args; ?>">Basic Settings</a></li>
                <li class="<?php if ($tab == 'scale') echo 'active';
                            echo $disabled; ?>"><a href="<?php echo admin_url('admin.php?page=issvaddclassscale') . $args; ?>">Grading Scale</a></li>
                <li class="<?php if ($tab == 'category') echo 'active';
                            echo $disabled; ?>"><a href="<?php echo admin_url('admin.php?page=issvaddclasscategory') . $args; ?>">Assignment Categories</a></li>
                <li class="<?php if ($tab == 'teacher') echo 'active';
                            echo $disabled; ?>"><a href="<?php echo admin_url('admin.php?page=issvaddclassteacher') . $args; ?>">Teachers </a></li> 
                <?php if (ISS_PermissionService::class_manage_access() && !empty($classid)) { ?>  
                     <li class="<?php if ($tab == 'delete') echo 'active';
                                echo $disabled; ?>"><a href="<?php echo admin_url('admin.php?page=issvdeleteclass') . $args; ?>">Delete </a></li> 
                <?php 
            } ?>
                    </ul>
        </div>
        <?php 
    }
    echo "<div class='panel-body'>";
    ?>