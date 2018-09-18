<div>
<?php 
   
/// HEADER - BEGING

$cid = $svid = $class = $student = null;
$result_set = array();
if (isset($_GET['cid'])) {
    $cid = iss_sanitize_input($_GET['cid']);
}
if (!empty($cid)) {
    $class = ISS_ClassService::LoadByID($cid);
}
if (isset($_GET['svid'])) {
    $svid = iss_sanitize_input($_GET['svid']);
}
if (null == $class) {
    iss_show_heading("Class / Assignments  not found!");
    exit;
}
$backurl = admin_url('admin.php?page=issvclasslist');
iss_show_heading_with_backurl("Grade {$class->ISSGrade}  {$class->Subject} Assignments / Tests", $backurl);

/// HEADER - END

/// STUDENT ACCESS -BEGIN
if (!empty($svid)) {

    $student = ISS_StudentService::LoadByStudentViewID($svid);
    iss_write_log($student);

    if (null == $student) {
        echo "<div class='alert alert-danger' role='alert'>Student Assignments not found! </div>";
        exit;
    }
    echo "<h4 class='alert alert-info'> {$student->StudentFirstName} {$student->StudentLastName} </h4>";
    $result_set = ISS_ScoreService::GetStudentAssignmentScores($cid, $student->StudentID, $svid);
    ?>
    <table class="table table-striped table-responsive table-condensed" id="iss_assignment_table">
        <thead>
            <tr>      
                <th>Title</th>
                <th>Category</th>
                <th>Due Date</th>
                <th>Score</th>   
                <th>Comment</th>                 
            </tr>
        </thead>
        <tbody>
            <?php foreach ($result_set as $row) { ?>
            <tr> 
                <td>                            
                    <a href="admin.php?page=issvaview&post=<?php echo "{$row->AssignmentID}&cid={$cid}&svid={$svid}"; ?>" > <i class="fas fa-atom"></i> <?php echo $row->Title; ?></a>
                </td>
                <td> <?php echo $row->AssignmentTypeName; ?> </td>
                <td> <?php echo $row->DueDate; ?> </td>
                <td> <?php echo $row->Score . "  /  " . $row->PossiblePoints; ?> </td>  
                <td> <?php echo $row->Comment; ?> </td>  
            </tr>
                <?php 
            } ?>
        </tbody>
    </table>
<?php

}
/// STUDENT ACCESS - END

/// TEACHER ACCESS - BEGING

else if (ISS_PermissionService::class_assignment_write_access($cid)) {
    echo "<a class='btn btn-success' href='admin.php?page=issvaadd&cid={$cid}'> <i class='fas fa-atom'></i> Add New</a>";
    $result_set = ISS_AssignmentService::GetAssignments($cid);
    ?>
        <table class="table table-striped table-responsive table-condensed" id="iss_assignment_table">
            <thead>
                <tr>      
                    <th>Title</th>
                    <th>Category</th>
                    <th>Due Date</th>
                    <th>Possible Points</th>   
                    <th></th>                               
                </tr>
            </thead>
            <tbody>
                <?php foreach ($result_set as $row) { ?>
                <tr> 
                    <td>             
                        <a href="admin.php?page=issvaadd&post=<?php echo "{$row->PostID}&cid={$cid}"; ?>"><i class="fas fa-atom"></i> <?php echo $row->Title; ?></a>                                                             
                    </td>                   
                    <td><?php echo $row->AssignmentTypeName; ?></td>
                    <td> <?php echo $row->DueDate; ?> </td>
                    <td> <?php echo $row->PossiblePoints; ?> </td>  
                    <td>  
                        <a href="admin.php?page=issvascore&post=<?php echo "{$row->PostID}&cid={$cid}"; ?>"  class='text-info'
                                <span style="padding-left: 10px; white-space: nowrap;"><i class="fas fa-user-check"></i> Grade</span></a>               
                        <a href="admin.php?page=issvaemail&post=<?php echo "{$row->PostID}&cid={$cid}"; ?>" class='text-success'>
                            <span style="padding-left: 10px; white-space: nowrap;"><i class='fas fa-envelope '></i> Email</span></a>
                        <a href="admin.php?page=issvadelete&post=<?php echo "{$row->PostID}&cid={$cid}"; ?>"  class='text-danger'>
                            <span style="padding-left: 20px; white-space: nowrap;"><i class="fas fa-trash-alt"></i> Delete</span></a>               
                    </td>
                </tr>
                    <?php 
                } // end of result set ?>
            </tbody>
        </table>
    <?php

} 
/// TEACHER ACCESS - END

/// BOARD  ACCESS - BEGING 

else if (ISS_PermissionService::assignmentent_list_all_access()) {
    if (ISS_PermissionService::user_manage_access()) {
        echo "<a class='btn btn-success' href='admin.php?page=issvaadd&cid={$cid}'> <i class='fas fa-atom'></i> Add New</a>";
    }
    $result_set = ISS_AssignmentService::GetAssignments($cid);
    ?>
        <table class="table table-striped table-responsive table-condensed" id="iss_assignment_table">
            <thead>
                <tr>      
                    <th>Title</th>
                    <th>Category</th>
                     <th>Due Date</th>
                    <th>Possible Points</th>                                  
                </tr>
            </thead>
            <tbody>
                <?php foreach ($result_set as $row) { ?>
                <tr> 
                    <td>             
                        <a href="admin.php?page=issvaview&post=<?php echo "{$row->PostID}&cid={$cid}"; ?>"><i class="fas fa-atom"></i> <?php echo $row->Title; ?></a>                                                             
                    </td>                   
                    <td><?php echo $row->AssignmentTypeName; ?></td>
                    <td> <?php echo $row->DueDate; ?> </td>
                    <td> <?php echo $row->PossiblePoints; ?> </td>  
                </tr>
                    <?php 
                } // end of result set ?>
            </tbody>
        </table>

    <?php

}  
/// BOARD  ACCESS - END

?>
    
</div>
       
<script>
     $(document).ready(function(){
        $('#iss_assignment_table').DataTable(
            { "pageLength": 50, "order": [[2, 'asc']],
                "columns": [ 
                    null,
                    null,
                    null,
                    null,
                    { "orderable": false }
                ],
                "lengthChange": false,
            });
    });
</script>