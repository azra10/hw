<?php 
$tab = 'score';
include(plugin_dir_path(__FILE__) . "/assignment_header.php");
echo "<h4>Enter Assignment Scores</h4>"; 
$post = $scores = null;
if (!empty($postid)) {
    $post = ISS_AssignmentService::LoadByID($postid);
    if (null == $post) {
        echo 'Error entering scores for assignment.';
        exit();
    }
}
if (isset($_POST['_wpnonce-iss-score_student-form-page'])) {
    
    check_admin_referer('iss-score_student-form-page', '_wpnonce-iss-score_student-form-page');
    if(isset($_POST['submit']) && ($_POST['submit']== 'delete')){
        if (1 == ISS_ScoreService::DeleteScores($postid, $classid)) {
            echo "<div class='alert alert-success' role='alert'> Scores Deleted. </div>";
        } else {
            echo "<div class='alert alert-danger' role='alert'>Error Deleting Scores. </div>";
        }
    }
    else
    {           
            if (1 == ISS_ScoreService::SaveAssignmentScores($_POST, $postid, $classid)) 
            {
                echo "<div class='alert alert-success' role='alert'> Scores Saved. </div>";  
            } else {
                echo "<div class='alert alert-danger' role='alert'>Error Saving Scores. </div>";
            }
    }
}
$scores = ISS_ScoreService::LoadScoreByAssignmentID($postid, $classid);
if (null == $scores) {
    echo 'Error entering scores for assignment.';
    exit();
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
                        $score = $student->Score;
                        if ($score == '-1') { $score = 'E'; } else if ($score == '-2') {  $score = 'M'; } 
                        $style = (empty($score))? 'background-color:#FFE4B5;padding:2px' : '';                 
                        ?>
                    <tr>
                        <td>
                        <span style="width:200px;padding:5px;"><?php echo $student->StudentFirstName . ' ' . $student->StudentLastName; ?></span> 
                        </td>
                        <td style="background-color:#aecfda; color:#FFFFFF;padding:5px;" class="text-center">
                        <input name="score<?php echo $student->StudentViewID . '-' . $postid; ?>" type="text" class="scoreinput text-center" style="<?php echo $style; ?>" value="<?php echo $score; ?>"  size="10" />
                        </td>
                        <td style="background-color:#cee0e6;padding:5px;" >
                        <input name="comment<?php echo $student->StudentViewID . '-' . $postid; ?>" type="text" value="<?php echo $student->Comment; ?>") size="100"/>                   
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