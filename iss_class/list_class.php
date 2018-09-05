<div class="container">
   <?php 
    iss_show_heading_with_regyear("Classses");
    $result_set = ISS_ClassService::GetClasses();
    ?>
<div>
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
            <?php $currentuser = wp_get_current_user();
            foreach ($result_set as $row) { ?>
        <tr>
            <td><a href="admin.php?page=issvalist&cid=<?php echo $row->ClassID; ?>"> 
                <i class='fas fa-id-card iss_css_class'></i>  <?php echo $row->Name; ?></a></td>
            <td> <?php echo $row->Teacher; ?> </td>
            <td>
            <?php if (is_student_plugin_active()) { ?> 
                        <a href="admin.php?page=issvstudentlist&cid=<?php echo $row->ClassID; ?>">
                            <span style="padding-left: 10px; white-space: nowrap;"> <i class="fas fa-users iss_css_user "></i> Students </span>
                        </a>
                <?php 
            }

            if (is_email_plugin_active()) { 
                // any other teacher role (read/write) or teacher as parent should not be be able to email class.
                $primaryteacher = (strpos($row->Teacher, $currentuser->display_name) !== false);
                if (ISS_PermissionService::can_email_class() && $primaryteacher) { ?>              
                    <a href="admin.php?page=issemailclass&cid=<?php echo $row->ClassID; ?>">
                        <span style="padding-left: 10px; white-space: nowrap;"> <i class="fas fa-envelope iss_css_email"></i> Email Class </span>
                    </a>                   
                    <?php 
                } else
                    if (ISS_PermissionService::can_email_teacher()) { ?>
                    <a href="admin.php?page=issemailteacher&cid=<?php echo $row->ClassID; ?>">
                        <span style="padding-left: 10px; white-space: nowrap;"> <i class="fas fa-envelope iss_css_email"></i> Email Teacher </span>
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
            "order": [[ 0, "asc" ]],
            "columns": [ 
                    null,
                    null,
                    { "orderable": false }
                ]
            });
    });
</script>