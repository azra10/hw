<?php 
$backurl = admin_url('admin.php?page=issvclasslist');

$result_set = null;
$cid = null;
if (isset($_GET['cid'])) {
    $cid = iss_sanitize_input($_GET['cid']);
    if (!empty($cid)) {
        $result_set = ISS_AssignmentService::GetAssignments($cid);
    } else {
        iss_show_heading("Sorry cannot find the class/assignments");
        exit;
    }
} else {
    iss_show_heading("Sorry Class / Assignments  not found!");
    exit;
}

$class = ISS_ClassService::LoadByID($cid);
iss_show_heading_with_backurl("Grade {$class->ISSGrade}  {$class->Subject} Assignments", $backurl);
if (ISS_PermissionService::class_assignment_write_access($cid))
    echo "<a class='btn btn-success' href='admin.php?page=issvaadd&cid={$cid}'>Add New</a>";
?>
<div>
    <div class="container">
        <table class="table table-striped table-responsive table-condensed" id="iss_assignment_table">
            <thead>
                <tr>      
                     <th>Title</th>
                      <th>Due Date</th>
                    <th>Possible Points</th>                    
                </tr>
            </thead>
            <tbody>
                <?php foreach ($result_set as $row) { ?>
                <tr> <td>             
                <?php if (ISS_PermissionService::class_assignment_write_access($cid)) { ?>              
                    <a href="admin.php?page=issvaadd&post=<?php echo "{$row->PostID}&cid={$cid}"; ?>"><?php echo $row->Title; ?></a>                 
                    <?php
                } else { ?>
                    <a href="admin.php?page=issvaview&post=<?php echo "{$row->PostID}&cid={$cid}"; ?>" > <?php echo $row->Title; ?></a>
                    <?php 
                } ?>
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
	    $('#iss_assignment_table').DataTable({ "lengthChange": false,"pageLength": 25, "order": [[1, 'desc']]});
    });
</script>