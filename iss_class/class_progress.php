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
        <tr>
            <th>Assignment<br/>Type<br/>Due Date <br/> Possible Points<br/><i class="fas fa-long-arrow-alt-right fa-2x iss_css_right"></i></th> <th></th>             
                    <?php  
                    foreach ($result_set['Assignments'] as $row) {  
                        echo "<td class='text-center'>";
                        echo "<a href='" .  admin_url('admin.php?page=issvascore') . "&post={$row['AssignmentID']}&cid={$cid}'><i class='fas fa-atom'></i> {$row['Title']}</a>";
                        echo "<br/>{$row['TypeName']}<br/>{$row['DueDate']}<br/>{$row['PossiblePoints']}</td>";
                    }?>
            <th>Assignment<br/>Type<br/>Due Date <br/> Possible Points<br/><i class='fas fa-long-arrow-alt-left fa-2x iss_css_right'></i></th>                     
        </tr>
        <tr>
            <th>Student</th>
            <th class='text-center'>Grade</th>
            <?php foreach ($result_set['Assignments'] as $row) { echo "<th></th>"; } ?>
            <th>Student</th>
        </tr>
        </thead>
        <tbody>
            <?php 
            foreach ($result_set['Students'] as $row) {
                $svid = $row['StudentViewID']; ?>
            <tr> 
                <td><a href='admin.php?page=issveditstudentprogress&svid=<?php echo "{$svid}&cid={$cid}"; ?>'> <i class="fas fa-user iss_css_user "></i><?php echo " {$row['StudentFirstName']} {$row['StudentLastName']}"; ?> </a></td>
                <td class='text-center'><?php if (isset($row['Total'])) echo "{$row['Total']}%" ; if (isset($row['Scale'])) echo "  {$row['Scale']}"; ?></td>
                <?php foreach ($result_set['Assignments'] as $aid => $value) {
                     $score = $result_set['Scores'][$svid . '-' . $aid]['score'];
                     if ($score == '-1') { $score = 'E'; } else if ($score == '-2') {  $score = 'M'; }                      
                    echo "<td class='text-center'>{$score}</td>";
                } ?>
                 <td><a href='admin.php?page=issveditstudentprogress&svid=<?php echo "{$svid}&cid={$cid}"; ?>'> <i class="fas fa-user iss_css_user "></i><?php echo " {$row['StudentFirstName']} {$row['StudentLastName']}"; ?> </a></td>
            </tr>
                <?php } ?>
        </tbody>
    </table>           
</div>
</div>
<script>
    $(document).ready(function(){
        $('#iss_score_table').DataTable( 
            { 
                "pageLength": 50, 
                "paging": false, 
                fixedHeader: {
                    header: true,
                    footer: true
                }
            }); 
        });
</script>
