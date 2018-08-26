<?php 
$result_set = ISS_ClassService::GetTeacherAccounts();

iss_show_heading("Teacher Accounts");

?>
<div>
    <div class="container">
        <table class="table table-striped table-responsive table-condensed" id="iss_student_table">
            <thead>
                <tr>                     
                    <th>ClassID</th>
                    <th> Class</th>
                    <th>Teacher</th>                    
                    <th>Account</th> 
                    <th>Last Login</th> 
                    <th>UserID</th> 
                    <th>Active</th>                  
                </tr>
            </thead>
            <tbody>
                <?php if (null != $result_set) {
                    foreach ($result_set as $row) { ?>
                <tr>
                <td> <?php echo $row->ClassID; ?>  </td>
                 <td><?php echo $row->Name; ?></td>
                 <td><?php echo $row->Teacher . "<br/>" .  $row->Access; ?></td>
                <td><?php echo $row->UserEmail;
                    if (ISS_PermissionService::user_manage_access()) {
                        echo "<br/><a href=\"admin.php?page=issvteacheraccount&cid={$row->ClassID}\">Add Mapping</a>";
                        if (!empty($row->UserID)) echo "<br/><a href=\"admin.php?page=issvteacherdelete&uid={$row->UserID}&cid={$row->ClassID}\">Remove Mapping</a>";
                    }
                    ?> </td>
                <td><?php echo $row->LastLogin; ?></td>
                <td><?php echo $row->UserID; ?> </td>
                <td> <?php echo $row->Status == 'inactive' ? 'No' : 'Yes'; ?> </td>
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
	    $('#iss_student_table').DataTable(
            { 
                "lengthChange": true,
                "pageLength": 25 ,
                "order": [[ 4, "desc" ], [1, "asc"]]
            });
    });
</script>