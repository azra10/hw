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
if (!empty($svid)) {
    $student = ISS_StudentService::LoadByStudentViewID($svid);
}
$backurl = admin_url('admin.php?page=issvclasslist');
iss_show_heading_with_backurl("Student Progress - {$student->StudentFirstName} {$student->StudentLastName}  ", $backurl);
echo "<h4>Class: Grade{$class->ISSGrade}-{$class->Subject}</h4>";
echo "<hr/>";
/// HEADER - END

$scores = null;
if (!empty($svid)) {   
        $scores = ISS_ScoreService::LoadScoreByStudentID($svid, $cid);
}

if ((null == $scores) || (null == $student)) {
    echo 'Scores not available for student.';
    exit();
}

if (isset($_POST['_wpnonce-iss-score_student-form-page'])) {
    
    check_admin_referer('iss-score_student-form-page', '_wpnonce-iss-score_student-form-page');

    foreach ($scores as $assignment) {
        if (isset($_POST["score" . $assignment->AssignmentID])) {
            $comment = $_POST["comment" . $assignment->AssignmentID];
            if (!empty($comment)) {
                $assignment->Comment = $comment;
            }
            $score = $_POST["score" . $assignment->AssignmentID];
            if (is_numeric($score)) {
                $assignment->Score = $score;
            } else if ($score == "E") { // Excused
                $assignment->Score = -1;
            } else if ($score == "M") { // Missing
                $assignment->Score = -2;
            } else {
                $assignment->Score = 0;
            }
        }
    }
    if (1 == ISS_ScoreService::SaveStudentScores($scores, $svid, $cid)) {
        echo "<div class='alert alert-success' role='alert'> Scores Saved. </div>";           
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error Saving Scores. </div>";
    }
    
}
?>
<form id="scoreform" class="form-horizontal" method="post" action="" enctype="multipart/form-data">
    <?php wp_nonce_field('iss-score_student-form-page', '_wpnonce-iss-score_student-form-page') ?>

    <div class="form-group panel-footer">
        <div class="col-sm-6">
            <button type="submit" name="submit" value="save" class="btn btn-warning form-control" >Save Assignment Scores</button>		 
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-10">
            <strong>Enter each student's assignment score, E for excused, or M for missing. You can add a comment for each student score. Use tab key to jump boxes.</strong>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-10">
        <table class="table table-striped table-responsive table-bordered" id="iss_score_table">
                <thead>
                    <tr>
                        <th style="width:200px;padding:5px;scoretabl">Assignment</th>
                        <th>Category</th>
                        <th>Due Date</th>
                        <th>Possible Points</th>
                        <th style="width:125px; background-color:#aecfda !important; color:#000;padding:5px;scoretabl">Score </th>
                        <th style="width:100px; background-color:#cee0e6 !important; color:#000;padding:5px;scoretabl">Comment (size:100)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($scores as $assignment) {
                        if ($assignment->Score == '-1') {
                            $score = 'E';
                        } else if ($assignment->Score == '-2') {
                            $score = 'M';
                        } else {
                            $score = $assignment->Score;
                        } ?>
                    <tr>
                        <td>
                        <span style="width:200px;padding:5px;"><?php echo $assignment->Title; ?></span> 
                        </td>
                        <td><?php echo "{$assignment->AssignmentTypeName} - {$assignment->AssignmentTypePercentage}%"; ?></td>
                        <td><?php echo $assignment->DueDate; ?></td>
                        <td><?php echo $assignment->PossiblePoints; ?></td>
                        <td style="background-color:#aecfda; color:#FFFFFF;padding:5px;">
                        <input name="score<?php echo $assignment->AssignmentID; ?>" type="text" class="scoreinput" value="<?php echo $score; ?>"  size="10" />
                        </td>
                        <td style="background-color:#cee0e6;padding:5px;" >
                        <input name="comment<?php echo $assignment->AssignmentID; ?>" type="text" value="<?php echo $assignment->Comment; ?>") size="100"/>                   
                    </td>
                        
                    </tr> 
                        <?php 
                    } ?>
                </tbody>
            </table>
        </div>
    </div>       
    <div class="form-group panel-footer">
        <div class="col-sm-6">
            <button type="submit" name="submit" value="save" class="btn btn-warning form-control" >Save Assignment Scores</button>		 
        </div>
    </div>

</form> 
