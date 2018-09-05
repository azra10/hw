<div class="container">
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
    iss_show_heading_with_backurl("Grade {$class->ISSGrade}  {$class->Subject} Assignments / Tests", $backurl);
    if (ISS_PermissionService::class_assignment_write_access($cid))
        echo "<a class='btn btn-success' href='admin.php?page=issvaadd&cid={$cid}'> <i class='fas fa-atom'></i> Add New</a>";
    ?>

    <table class="table table-striped table-responsive table-condensed" id="iss_assignment_table">
        <thead>
            <tr>      
                <th>Title</th>
                <th>Due Date</th>
                <th>Possible Points</th>   
                <th/>                 
            </tr>
        </thead>
        <tbody>
            <?php foreach ($result_set as $row) { ?>
            <tr> <td>             
                <?php if (ISS_PermissionService::class_assignment_write_access($cid)) { ?>              
                    <a href="admin.php?page=issvaadd&post=<?php echo "{$row->PostID}&cid={$cid}"; ?>">
                    <span style="padding-left: 10px; white-space: nowrap;"> <i class="fas fa-atom "></i> <?php echo $row->Title; ?></span></a>                                                   
                <?php

            } else { ?>
                    <a href="admin.php?page=issvaview&post=<?php echo "{$row->PostID}&cid={$cid}"; ?>" > <?php echo $row->Title; ?></a>
                    <?php 
                } ?>
                </td>
                
                <td> <?php echo $row->DueDate; ?> </td>
                <td> <?php echo $row->PossiblePoints; ?> </td>     
                
                <?php if (ISS_PermissionService::class_assignment_write_access($cid)) { ?>              
               <td>  
                <a href="admin.php?page=issvadelete&post=<?php echo "{$row->PostID}&cid={$cid}"; ?>"  class='text-danger'
                    onclick="return confirm('Are you sure you want to delete this assignment?')"> <i class="fas fa-trash-alt"></i> Delete</span>
                    </a>               
                    <a href="admin.php?page=issvaemail&post=<?php echo "{$row->PostID}&cid={$cid}"; ?>" class='text-success'>
                    <span style="padding-left: 10px; white-space: nowrap;"><i class='fas fa-envelope '></i> Email</a></span>
                    </td>
                    <?php 
                }
                ?>
            </tr>
                <?php 
            } ?>
        </tbody>
    </table>
</div>

<script>
     $(document).ready(function(){
        $('#iss_assignment_table').DataTable(
            { "lengthChange": false,"pageLength": 25, "order": [[1, 'desc']],
                "columns": [ 
                    null,
                    null,
                    null,
                    { "orderable": false }
                ]
            });
    });
</script>