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
    exit();
}

?>

    <div class="form-group">
        <div class="col-sm-10">
            <strong>Student's assignment score, E for excused, or M for missing.</strong>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-10">
        <table class="table table-striped table-responsive table-bordered" id="iss_score_table">
                <thead>
                    <tr>
                        <th style="width:200px;padding:5px;scoretabl">Assignment</th>
                        <th>Category</th>
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
                        <td><?php echo $assignment->PossiblePoints; ?></td>
                        <td style="background-color:#aecfda; color:#FFFFFF;padding:5px;">
                        <span><?php echo $score; ?></span>
                        </td>
                        <td style="background-color:#cee0e6;padding:5px;" >
                        <span><?php echo $assignment->Comment; ?>") </span>                   
                    </td>
                        
                    </tr> 
                        <?php 
                    } ?>
                </tbody>
            </table>
        </div>
    </div>       
