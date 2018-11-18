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
if (null == $class) {
    iss_show_heading("Class / Assignments  not found!");
    exit;
}
$backurl = admin_url('admin.php?page=issvclasslist');
iss_show_heading_with_backurl("Grade {$class->ISSGrade}  {$class->Subject} Progress", $backurl);

/// HEADER - END


if (ISS_PermissionService::class_assignment_write_access($cid)) {
    $result_set = ISS_ScoreService::GetClassAssignmentScores($cid);
?>
<div class="col-sm-11">
    <table class="table table-striped table-responsive table-bordered" id="iss_score_table">
        <thead>
            <tr><th class="col-sm-2">Assignment</th><th class="col-sm-1"><i class="fas fa-long-arrow-alt-right fa-2x iss_css_right"></th></i>              
                <?php  
                foreach ($result_set['Assignments'] as $row) {      
                    echo "<th>{$row['Title']}</th>";
                }
                echo "</tr><tr><th>Type</th><th><i class='fas fa-long-arrow-alt-right fa-2x iss_css_right'></i></th>";
                foreach ($result_set['Assignments'] as $row) { echo "<th>{$row['TypeName']}</th>";}
                echo "</tr><tr><th>Due Date</th><th><i class='fas fa-long-arrow-alt-right fa-2x iss_css_right'></i></th>";
                foreach ($result_set['Assignments'] as $row) { echo "<th>{$row['DueDate']}</th>";}
                echo "</tr><tr><th>Possible Points</th><th><i class='fas fa-long-arrow-alt-right fa-2x iss_css_right'></i></th>";
                foreach ($result_set['Assignments'] as $row) { echo "<th class='text-center'>{$row['PossiblePoints']}</th>";}
                ?>
            </tr>
            <tr>
                <th>Student</th>
                <th>Grade</th>
                <?php foreach ($result_set['Assignments'] as $row) { echo "<th></th>"; } ?>
            </tr>
        </thead>
        <tbody>
            <?php 
            foreach ($result_set['Students'] as $row) {
                $svid = $row['StudentViewID'];?>
            <tr> 
                <th><?php echo " {$row['StudentFirstName']} {$row['StudentLastName']}"; ?></th>
                <th><?php echo "{$row['Total']}% - {$row['Scale']}"; ?></th>
                <?php foreach ($result_set['Assignments'] as $aid =>$value) {   echo "<td>{$result_set['Scores'][$svid . $aid]}</td>";  } ?>
            </tr>
            <?php } ?>
            </tbody>
            </table>
            <?php } ?>
</div>
</div>

