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
                <?php foreach ($result_set as $row) { ?>
                <tr>
                <td><?php echo $row->Name; ?></td>
                <td> <?php echo $row->Teacher; ?> </td>
               <td>
                <?php if (is_student_plugin_active()) { ?> 
                    <a href="admin.php?page=issvstudentlist&cid=<?php echo $row->ClassID; ?>">
                        <span style="padding-left: 10px; white-space: nowrap;"> <i class="fas fa-users "></i> Students </span>
                    </a>
                <?php 
            } ?> 
                    <?php if (is_assignment_plugin_active()) { ?>              
                        <a href="admin.php?page=issvalist&cid=<?php echo $row->ClassID; ?>">
                            <span style="padding-left: 10px; white-space: nowrap;"> <i class="fas fa-pen "></i> Assignments </span>
                        </a>                   
                    <?php 
                } ?>
                    </td>
                    </tr>
                    <?php 
                } ?>
       
            </tbody>
        </table>
        <?php 
    } else { ?>
You do not have access to any class at this time. Please <a alt="here" href="admin.php?page=issvemailadmin">click</a> to request access.  
<?php 
} ?>
    </div>
</div>
<script>
     $(document).ready(function(){
	    $('#iss_class_table').DataTable({ 
            "lengthChange": false,
            "pageLength": 25}, ,
                "order": [[ 0, "asc" ]]);
    });
</script>