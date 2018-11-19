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

if (ISS_PermissionService::class_assignment_write_access($cid)) 
{
    $result_set = ISS_ScoreService::GetClassAssignmentScores($cid);
    if (!isset($result_set['Assignments']) || !isset($result_set['Students'])) 
    {
        echo 'Scores not available for class.';
        exit();
    }
    ?>
<div>
<a href="admin.php?page=issveditclassprogress&cid=<?php echo $cid; ?>" class="btn btn-lg btn-warning">Edit Progress</a>
</div>
<div>
    <table class="table table-striped table-responsive table-bordered" id="iss_score_table">
        <thead>
        <tr><th class="col-sm-2"><i class="fas fa-long-arrow-alt-right fa-2x iss_css_right"></i>Assignment</th> <th></th>             
                        <?php  
                        foreach ($result_set['Assignments'] as $row) {  echo "<th class='text-center'>{$row['Title']}</th>";}
                        echo "</tr><tr><th><i class='fas fa-long-arrow-alt-right fa-2x iss_css_right'></i>Type</th><th/>";
                        foreach ($result_set['Assignments'] as $row) { echo "<th class='text-center'>{$row['TypeName']}</th>";}
                        echo "</tr><tr><th><i class='fas fa-long-arrow-alt-right fa-2x iss_css_right'></i>Due Date</th><th/>";
                        foreach ($result_set['Assignments'] as $row) { echo "<th class='text-center'>{$row['DueDate']}</th>";}
                        echo "</tr><tr><th><i class='fas fa-long-arrow-alt-right fa-2x iss_css_right'></i>Possible Points</th><th/>";
                        foreach ($result_set['Assignments'] as $row) { echo "<th class='text-center'>{$row['PossiblePoints']}</th>";}
                        ?>
                    </tr>
            <tr>
                <th>Student</th>
                <th class='text-center'>Grade</th>
                <?php foreach ($result_set['Assignments'] as $row) {
                    echo "<th></th>";
                } ?>
            </tr>
        </thead>
        <tbody>
            <?php 
            foreach ($result_set['Students'] as $row) {
                $svid = $row['StudentViewID']; ?>
            <tr> 
                <th><a href='admin.php?page=issveditstudentprogress&svid=<?php echo "{$svid}&cid={$cid}"; ?>'> <i class="fas fa-user iss_css_user "></i><?php echo " {$row['StudentFirstName']} {$row['StudentLastName']}"; ?> </a></th>
                <th class='text-center'><?php if (isset($row['Total'])) echo "{$row['Total']}%" ; if (isset($row['Scale'])) echo "  {$row['Scale']}"; ?></th>
                <?php foreach ($result_set['Assignments'] as $aid => $value) {
                     $score = $result_set['Scores'][$svid . '-' . $aid];
                        if ($score == '-1') {
                            $score = 'E';
                        } else if ($score == '-2') {
                            $score = 'M';
                        }
                    echo "<td class='text-center'>{$score}</td>";
                } ?>
            </tr>
                <?php } ?>
        </tbody>
    </table>           
</div>
        <?php } ?>
</div>
<script>
    $(document).ready(function(){
        $('#iss_score_table').DataTable( 
            { 
                "pageLength": 50, 
                "paging": false, 
            }); 
        });
</script>
