<?php 
iss_show_heading_with_regyear("Classses");
$result_set = ISS_ClassService::GetClasses();
?>
<div>
    <div class="container">
    <?php if (null != $result_set) { ?>
    <table class="table table-striped table-responsive table-condensed" id="iss_class_table">
        <thead>
            <tr>      
                <th>Name</th>
                <th>Teacher</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php $currentuser= wp_get_current_user();
            foreach ($result_set as $row) { ?>
        <tr>
            <td><?php echo $row->Name; ?></td>
            <td> <?php echo $row->Teacher; ?> </td>
            <td>
            <?php if (is_student_plugin_active()) { ?> 
                        <a href="admin.php?page=issvstudentlist&cid=<?php echo $row->ClassID; ?>">
                            <span style="padding-left: 10px; white-space: nowrap;"> <i class="fas fa-users "></i> Students </span>
                        </a>
                <?php 
            }
            if (is_assignment_plugin_active()) { ?>              
                <a href="admin.php?page=issvalist&cid=<?php echo $row->ClassID; ?>">
                    <span style="padding-left: 10px; white-space: nowrap;"> <i class="fas fa-pen "></i> Assignments </span>
                </a>                   
                <?php 
            }

            if (is_email_plugin_active()) { 
                $teacher = (strpos( $row->Teacher, $currentuser->display_name) !==false);
                if (ISS_PermissionService::can_email_class() && $teacher) { ?>              
                    <a href="admin.php?page=issemailclass&cid=<?php echo $row->ClassID; ?>">
                        <span style="padding-left: 10px; white-space: nowrap;"> <i class="fas fa-envelope "></i> Email Class </span>
                    </a>                   
                    <?php 
                } else               
                if (ISS_PermissionService::can_email_teacher()) { ?>
                    <a href="admin.php?page=issemailteacher&cid=<?php echo $row->ClassID; ?>">
                        <span style="padding-left: 10px; white-space: nowrap;"> <i class="fas fa-envelope "></i> Email Teacher </span>
                    </a>  
                    <?php 
                }
            } ?>
    
            </td>
        </tr>
        <?php 
    } ?>
    
            </tbody>
    </table>
        <?php 
    } else {
        $url = admin_url("admin.php?page=issvemailadmin");
        echo "You do not have access to any class at this time. Please <a href='{$url}'>CLICK HERE</a> to request access. ";
    } ?>
    </div>
</div>
<script>
     $(document).ready(function(){
	    $('#iss_class_table').DataTable({ 
            "lengthChange": false,
            "pageLength": 25, 
            "order": [[ 0, "asc" ]]});
    });
</script>