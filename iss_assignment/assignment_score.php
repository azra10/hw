<?php 
$tab = 'score';
include(plugin_dir_path(__FILE__) . "/assignment_header.php");
echo "<h4>Enter Assignment Scores</h4>"; 
$post = $scores = null;
if (!empty($postid)) {
    $post = ISS_AssignmentService::LoadByID($postid);
    if (null != $post) {
        $scores = ISS_ScoreService::LoadScoreByAssignmentID($postid, $post->ClassID);
    }
}

if ((null == $scores) || (null == $post)) {
    echo 'Error entering scores for assignment.';
    exit();
}

if (isset($_POST['_wpnonce-iss-score_student-form-page'])) {
    
    check_admin_referer('iss-score_student-form-page', '_wpnonce-iss-score_student-form-page');
    if(isset($_POST['submit']) && ($_POST['submit']== 'delete')){
        if (1 == ISS_ScoreService::DeleteScores($postid, $classid)) {
            echo "<div class='alert alert-success' role='alert'> Scores Deleted. </div>";
            $scores = ISS_ScoreService::LoadScoreByAssignmentID($postid, $post->ClassID);
        } else {
            echo "<div class='alert alert-danger' role='alert'>Error Deleting Scores. </div>";
        }
    }
    else
        {
            foreach ($scores as $student) {
            if (isset($_POST["score" . $student->StudentViewID])) {
                $comment = $_POST["comment" . $student->StudentViewID];
                if (!empty($comment)) {
                    $student->Comment = $comment;
                }
                $score = $_POST["score" . $student->StudentViewID];
                if (is_numeric($score)) {
                    $student->Score = $score;
                } else if ($score == "E") { // Excused
                    $student->Score = -1;
                } else if ($score == "M") { // Missing
                    $student->Score = -2;
                } else {
                    $student->Score = 0;
                }
            }
        }
        if (1 == ISS_ScoreService::SaveScores($scores, $postid, $classid)) {
            echo "<div class='alert alert-success' role='alert'> Scores Saved. </div>";
        } else {
            echo "<div class='alert alert-danger' role='alert'>Error Saving Scores. </div>";
        }
    }
}


echo "<div class='row'>";
echo "<div class='col-md-3'><strong>Assingment</strong>: {$post->Title} </div>";
echo "<div class='col-md-3'><strong>Class</strong>: Grade{$post->ISSGrade}-{$post->Subject}</div>";
echo "<div class='col-md-2'><strong>Due Date</strong>: {$post->DueDate}</div>";
echo "<div class='col-md-2'><strong>Possible Point:</strong> {$post->PossiblePoints}</div>";
echo "</div><hr/>";
?>
<form id="scoreform" class="form-horizontal" method="post" action="" enctype="multipart/form-data">
    <?php wp_nonce_field('iss-score_student-form-page', '_wpnonce-iss-score_student-form-page') ?>

    <div class="form-group">
        <div class="col-sm-10">
            <strong>Enter each student's assignment score, E for excused, or M for missing. You can add a comment for each student score. Use tab key to jump boxes.</strong>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-10">
            <table id="scoretable" border=1 >
                <thead>
                    <tr>
                        <th style="width:200px;padding:5px;scoretabl">Student</th>
                        <th style="width:125px; background-color:#aecfda !important; color:#000;padding:5px;scoretabl">Score </br><a href="" id="copyfirsttoall">Copy First to All</></th>
                        <th style="width:100px; background-color:#cee0e6 !important; color:#000;padding:5px;scoretabl">Comment (size:100)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($scores as $student) {
                        if ($student->Score == '-1') {
                            $score = 'E';
                        } else if ($student->Score == '-2') {
                            $score = 'M';
                        } else {
                            $score = $student->Score;
                        } ?>
                    <tr>
                        <td>
                        <span style="width:200px;padding:5px;"><?php echo $student->StudentFirstName . ' ' . $student->StudentLastName; ?></span> 
                        </td>
                        <td style="background-color:#aecfda; color:#FFFFFF;padding:5px;">
                        <input name="score<?php echo $student->StudentViewID; ?>" type="text" class="scoreinput" value="<?php echo $score; ?>"  size="10" />
                        </td>
                        <td style="background-color:#cee0e6;padding:5px;" >
                        <input name="comment<?php echo $student->StudentViewID; ?>" type="text" value="<?php $student->Comment; ?>") size="100"/>                   
                    </td>
                        
                    </tr> 
                        <?php 
                    } ?>
                </tbody>
            </table>
        </div>
    </div>       
    <div class="form-group panel-footer">
        <div class="col-sm-5">
            <button type="submit" name="submit" value="save" class="btn btn-primary form-control" >Save Assignment Scores</button>		 
        </div>
        <div class="col-sm-5">
            <button type="submit" name="submit" value="delete" class="btn btn-danger form-control" >Delete Assignment Scores</button>		 
        </div>
    </div>

</form> 

<?php include(plugin_dir_path(__FILE__) . "/assignment_footer.php"); ?>
<script>
$(document).ready(function() {
    $("#scoretable").find('input:text')[0].focus();
    $("#copyfirsttoall").click(function(event){
        event.preventDefault();
        var firstvalue = $('#scoretable').find('*').filter(':input:visible:first').val();      
        $("input.scoreinput").each(function() {
            $(this).val(firstvalue);
        });
    });
});
</script>