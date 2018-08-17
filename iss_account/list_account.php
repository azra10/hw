<?php 
$result_set = ISS_StudentService::GetStudentAccounts();

iss_show_heading("Student Accounts");

?>
<div>
    <div class="container">
        <table class="table table-striped table-responsive table-condensed" id="iss_student_table">
            <thead>
                <tr>                     
                    <th>StudentID</th>
                    <th> Name</th>
                    <th> Emails</th> 
                    <th>Account</th> 
                    <th>Access</th> 
                    <th>UserID</th> 
                    <th>Status</th>
                    <th>Gender</th>
                    <th>ISSGrade</th>
                     <th>Father Name</th>
                     <th>Mother Name</th>
                   
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if (null != $result_set) {
                    foreach ($result_set as $row) { ?>
                <tr>
                <td> <?php 
                echo $row->StudentID;
                if (ISS_PermissionService::user_manage_access())
                        echo "<br/><a href=\"admin.php?page=issvuser&sid={$row->StudentID}\">Add</a>";
                  ?> </td>
                <td> <?php echo " {$row->StudentFirstName} {$row->StudentLastName} "; ?></td>
                <td> <?php echo "Student: {$row->StudentEmail} <br/>Father: {$row->FatherEmail} <br/>Mother: {$row->MotherEmail}"; ?> </td>  
                <td><?php  echo $row->UserEmail;  
                if (ISS_PermissionService::user_manage_access()) {
                    if (!empty($row->UserID)) echo "<br/><a href=\"admin.php?page=issvuserdelete&uid={$row->UserID}&sid={$row->StudentID}\">Remove</a>";
                 } ?> </td>
                <td><?php echo $row->Access; ?></td>
                <td><?php  echo $row->UserID; ?> </td>
                <td> <?php echo $row->StudentStatus == 'inactive' ? 'No' : 'Yes'; ?> </td>
                <td> <?php echo $row->StudentGender; ?> </td>  
                <td> <?php echo $row->ISSGrade; ?> </td>
                <td><?php echo "{$row->FatherFirstName} {$row->FatherLastName}"; ?></td>
                 
                <td><?php echo "{$row->MotherFirstName} {$row->MotherLastName}"; ?></td>
               
                <td>
                
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
	    $('#iss_student_table').DataTable({ "lengthChange": false,"pageLength": 25});
    });
</script>