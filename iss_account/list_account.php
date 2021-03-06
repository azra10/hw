<div class="container">
<?php 
$initial = null;
if (isset($_GET['initial'])) {
    $initial = iss_sanitize_input($_GET['initial']);
}
$result_set = ISS_StudentService::GetStudentAccounts($initial);

iss_show_heading_with_regyear("Student Accounts");

?>

<nav aria-label="Page navigation">
	<ul class="pagination">
        <?php
        echo "<li class=\"page-item ";
        if (null == $initial) {
            echo "  active\"";
        }
        echo "\"><a class=\"page-link\" href=\"users.php?page=issvuserlist\">All</a></li>";

        $letters = ISS_Class::GetClassList();

        $item = "";

        foreach ($letters as $letter => $value) {
            echo "<li class=\"page-item ";
            if ($letter == $initial) {
                echo " active\"";
            }
            echo "\"><a class=\"page-link\" href=\"users.php?page=issvuserlist&initial={$letter}\">{$value}</a></li>";
        }
        echo "<li class=\"page-item ";
        if ('InActive' == $initial) {
            echo "  active\"";
        }
        echo "\"><a class=\"page-link\" href=\"users.php?page=issvuserlist&initial=inactive\">Archived</a></li>";

        ?>
    </ul>
</nav>
<div>
<table class="table table-striped table-responsive table-condensed" id="iss_student_table">
            <thead>
                <tr>                     
                    <th>StudentID</th>
                    <th>ISSGrade</th>
                    <th> Names</th>
                     <th> Emails</th> 
                    <th>Account</th> 
                    <th>UserID</th> 
                    <th>Last Login</th> 
                </tr>
            </thead>
            <tbody>
                <?php if (null != $result_set) {
                    foreach ($result_set as $row) { ?>
                <tr>
                <td> <?php echo $row->StudentID; ?> </td>
                <td> <?php echo $row->ISSGrade; ?> </td>
               <td> 
                   <?php echo " {$row->StudentFirstName} {$row->StudentLastName} (Student)  <br/>" ?>
                    <?php echo "{$row->FatherFirstName} {$row->FatherLastName}  (Father) <br/>"; ?>
                    <?php echo "{$row->MotherFirstName} {$row->MotherLastName} (Mother) "; ?>
                </td>
               
                <td> <?php echo "Student: {$row->StudentEmail} <br/>Father: {$row->FatherEmail} <br/>Mother: {$row->MotherEmail}"; ?> </td>  
                <td><?php echo $row->UserEmail;
                    if (ISS_PermissionService::user_manage_access()) {
                        echo "<br/><a href='admin.php?page=issvusercreate&svid={$row->StudentViewID}'>Add New Account</a>";
                        $archive = ($row->StudentStatus == 'inactive') ? "UnArchive" : "Archive";
                        echo "<br/><a href=\"admin.php?page=issvarchiveuser&svid={$row->StudentViewID}\">{$archive}</a>";
                        echo "<br/><a href=\"admin.php?page=issvadduser&sid={$row->StudentID}\">Add Mapping</a>";
                        if (!empty($row->UserID)) {
                            echo "<br/><a href=\"admin.php?page=issvremoveuser&uid={$row->UserID}&sid={$row->StudentID}\">Remove Mapping</a>";
                        }
                    } ?> </td>
                <td><?php echo $row->UserID; ?> </td>
                <td><?php echo $row->LastLogin; ?> </td>
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
	    $('#iss_student_table').DataTable({ "lengthChange": true,"pageLength": 25});
   });
</script>