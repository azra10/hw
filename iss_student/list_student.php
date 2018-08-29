<div>
    <div class="container">
<?php 
$result_set = null;
$cid = null;
if (isset($_GET['cid'])) {
    $cid = iss_sanitize_input($_GET['cid']);
    if (!empty($cid)) {
        $result_set = ISS_StudentService::GetStudentsByClassID($cid);
    }
}

$class = ISS_ClassService::LoadByID($cid);
$backurl = admin_url('admin.php?page=issvclasslist');
iss_show_heading_with_backurl("Grade {$class->ISSGrade}  {$class->Subject}  Students ", $backurl);

?>
    <table class="table table-striped table-responsive table-condensed" id="iss_student_table">
        <thead>
            <tr>      
                <th>Name</th>
                <th>Gender</th>
                <th>Last Login</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php if (null != $result_set) {
                foreach ($result_set as $row) { ?>
            <tr>
            <td><?php echo "{$row->StudentFirstName}  {$row->StudentLastName}"; ?></td>
            <td> <?php echo $row->StudentGender; ?> </td>
            <td>  <?php echo $row->LastLogin; ?></td>    
            <td> 
                <?php if (ISS_PermissionService::can_email_student()) { ?>              
                    <a href="admin.php?page=issemailstudent&svid=<?php echo $row->StudentViewID; ?>&cid=<?php echo $cid; ?>">
                        <span style="padding-left: 10px; white-space: nowrap;"> <i class="fas fa-envelope iss_css_email"></i> Email Student </span>
                    </a>                   
                    <?php 
                } ?>
            </td>          
            </tr>
                    <?php 
                }
            } ?>
        </tbody>
    </table>
    </div>
</div>
<script>
     $(document).ready(function(){
	    $('#iss_student_table').DataTable({ 
            "lengthChange": false,
            "pageLength": 30, 
            "order": [[ 0, "asc" ]]});
    });
</script>