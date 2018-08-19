<?php 
$result_set = null; $cid = null;
if (isset($_GET['cid'])) {
    $cid = iss_sanitize_input($_GET['cid']);
    if (!empty($cid)) {
        $result_set = ISS_AssignmentService::GetAssignments($cid);
    } else {
        iss_show_heading( "Sorry cannot find the class/assignments");
        exit;
    }
} else {
    iss_show_heading( "Sorry Class / Assignments  not found!");
    exit;
}

$class = ISS_ClassService::LoadByID($cid);
iss_show_heading("Grade {$class->ISSGrade}  {$class->Subject}  Assignments ", 
    ISS_PermissionService::class_assignment_write_access($cid) ? 
    "post-new.php?post_type=iss_assignment&cat={$class->Category}&cid={$cid}" : null);
?>
<div>
    <div class="container">
        <table class="table table-striped table-responsive table-condensed" id="iss_assignment_table">
            <thead>
                <tr>      
                     <th>Name</th>
                     <th>Due Date</th>
                    <th>Possible Points</th> 
                </tr>
            </thead>
            <tbody>
                <?php foreach ($result_set as $row) { ?>
                <tr>
                <td><?php echo $row->Name; ?>

                <!-- <a target="_blank" href="<?php echo get_permalink($row->PostID); ?>"> -->
                <a href="admin.php?page=issvaview&post=<?php echo "{$row->PostID}&cid={$cid}"; ?>" > 
                        <span style="padding-left: 10px; white-space: nowrap;"> <i class="fas fa-eye "></i> View </span>
                    </a>
               
                <?php if (ISS_PermissionService::class_assignment_write_access($cid)) { ?>              
                    <a href="post.php?post=<?php echo $row->PostID;?>&action=edit">
                        <span style="padding-left: 10px; white-space: nowrap;"> <i class="fas fa-pen "></i> Edit </span>
                    </a>                 
                <?php } ?>

                 <?php if (ISS_PermissionService::class_assignment_write_access($cid)) { ?>              
                    <a href="admin.php?page=issvadelete&post=<?php echo "{$row->PostID}&cid={$cid}"; ?>"
                    onclick="return confirm('Are you sure you want to delete this assignment?';">
                        <span style="padding-left: 10px; white-space: nowrap;"> <i class="fas fa-trash-alt "></i> Delete </span>
                    </a>               
                <?php } ?>

                </td>
                <td> <?php echo $row->DueDate; ?> </td>
                <td> <?php echo $row->PossiblePoints; ?> </td> 
                </tr>
                <?php 
            } ?>
            </tbody>
        </table>
    </div>
</div>
<script>
     $(document).ready(function(){
	    $('#iss_assignment_table').DataTable({ "lengthChange": false,"pageLength": 25});
    });
</script>