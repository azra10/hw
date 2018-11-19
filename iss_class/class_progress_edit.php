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
$backurl = admin_url('admin.php?page=issvclassprogress&cid=') . $cid;
iss_show_heading_with_backurl("Grade {$class->ISSGrade}  {$class->Subject} Progress", $backurl);

/// HEADER - END


if (ISS_PermissionService::class_assignment_write_access($cid)) {
    $result_set = ISS_ScoreService::GetClassAssignmentScores($cid);
    if (!isset($result_set['Assignments']) || !isset($result_set['Students'])) 
    {  
        echo 'Scores not available for class.'; 
        exit(); 
    } 
    if (isset($_POST['_wpnonce-iss-score_students-form-page'])) {
    
        check_admin_referer('iss-score_students-form-page', '_wpnonce-iss-score_students-form-page');
        $scores = array();
        foreach ($result_set['Students'] as $row) {
            $svid = $row['StudentViewID'];
            foreach ($result_set['Assignments'] as $aid =>$value)
                        
            if (isset($_POST["score" . $svid . '-' . $aid])) {
                
                $score = $_POST["score" . $svid . '-' . $aid];
                if (is_numeric($score)) {
                    $score = $score;
                } else 
                if ($score == "E") { // Excused
                    $score = -1;
                } else if ($score == "M") { // Missing
                    $score = -2;
                } 
                if (isset($score)) {
                    $scores[$svid . '-' . $aid] = $score;
                    $result_set['Scores'][$svid . '-' . $aid] = $score;
                }
            }
        }
        
        if (1 == ISS_ScoreService::SaveClassScores($scores, $cid)) {
            echo "<div class='alert alert-success' role='alert'> Scores Saved. </div>";           
        } else {
            echo "<div class='alert alert-danger' role='alert'>Error Saving Scores. </div>";
        }
        
    }   
?>

<form id="scoreform" class="form-horizontal" method="post" action="" enctype="multipart/form-data">
    <?php wp_nonce_field('iss-score_students-form-page', '_wpnonce-iss-score_students-form-page') ?>

   <div class="form-group panel-footer">
        <div class="col-sm-6">
            <button type="submit" name="submit" value="save" class="btn btn-warning form-control" >Save  Scores</button>		 
        </div>
    </div>

    <!-- <div class="form-group">
        <div class="col-sm-10">
            <strong>Enter each student's assignment score, E for excused, or M for missing. You can add a comment for each student score. Use tab key to jump boxes.</strong>
        </div>
    </div> -->
    <div class="form-group">
        <div class="col-sm-11">
            <table class="table table-striped table-responsive table-bordered" id="iss_score_table">
                <thead>
                    <tr><th class="col-sm-2"><i class="fas fa-long-arrow-alt-right fa-2x iss_css_right"></i>Assignment</th>              
                        <?php  
                        foreach ($result_set['Assignments'] as $row) {  echo "<th class='text-center'>{$row['Title']}</th>";}
                        echo "</tr><tr><th><i class='fas fa-long-arrow-alt-right fa-2x iss_css_right'></i>Type</th>";
                        foreach ($result_set['Assignments'] as $row) { echo "<th class='text-center'>{$row['TypeName']}</th>";}
                        echo "</tr><tr><th><i class='fas fa-long-arrow-alt-right fa-2x iss_css_right'></i>Due Date</th>";
                        foreach ($result_set['Assignments'] as $row) { echo "<th class='text-center'>{$row['DueDate']}</th>";}
                        echo "</tr><tr><th><i class='fas fa-long-arrow-alt-right fa-2x iss_css_right'></i>Possible Points</th>";
                        foreach ($result_set['Assignments'] as $row) { echo "<th class='text-center'>{$row['PossiblePoints']}</th>";}
                        ?>
                    </tr>
                    <tr>
                        <th>Student</th>
                        
                        <?php foreach ($result_set['Assignments'] as $row) { echo "<th></th>"; } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    foreach ($result_set['Students'] as $row) {
                        $svid = $row['StudentViewID'];?>
                    <tr> 
                        <th><?php echo " {$row['StudentFirstName']} {$row['StudentLastName']}"; ?></th>
                        
                        <?php foreach ($result_set['Assignments'] as $aid =>$value) 
                        {   
                            $score = $result_set['Scores'][$svid . '-' . $aid];
                            if ($score == '-1') {
                                $score = 'E';
                            } else if ($score == '-2') {
                                $score = 'M';
                            }
                            echo "<td class='text-center'><input name='score{$svid}-{$aid}' type='text' class='scoreinput' value='{$score}'  size='10' /></td>";
                            
                        } ?>
                    </tr>
                    <?php } ?>
                    </tbody>
                    </table>
                   
        </div>
    </div>     
    <div class="form-group panel-footer">
        <div class="col-sm-6">
            <button type="submit" name="submit" value="save" class="btn btn-warning form-control" >Save  Scores</button>		 
        </div>
    </div>
</form> 
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