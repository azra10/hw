<?php 

$error = null;
if (isset($_POST['_wpnonce-iss-delete-class-form-page'])) {

    check_admin_referer('iss-delete-class-form-page', '_wpnonce-iss-delete-class-form-page');
    if (!isset($_POST['PostID']) || empty($_POST['PostID']) || (intval($_POST['PostID']) == 0)) {
        echo '<div class="alert-danger alert"><strong>Unknown Assignment.</strong></div>';
        exit;
    }
    $postid = iss_sanitize_input($_POST['PostID']);
    $classid = iss_sanitize_input($_POST['ClassID']);
    DeleteScores($postid, $classid);
    $result = ISS_AssignmentService::DeleteByPostID($postid);
    
   
    if ($result > 0) {
        $backurl = admin_url('admin.php?page=issvclasslist');
        iss_show_heading_with_backurl("Delete Assignment ", $backurl);
        echo '<div class="alert-success alert"><strong>Assignment Deleted.</strong></div>';
        exit;
    } else {
       $error = '<div class="alert alert-danger"><strong>Unable to delete assingment. Check dependencies (Assignments Scores).</strong></div>';
    }
} 

$tab = 'delete';
include(plugin_dir_path(__FILE__) . "/assignment_header.php");
echo "<h4>Delete Assignment</h4>"; 
echo $error;
$post = ISS_AssignmentService::LoadByID($postid);

echo "<div class='row'>";
echo "<div class='col-md-3'><strong>Assingment</strong>: {$post->Title} </div>";
echo "<div class='col-md-3'><strong>Class</strong>: Grade{$post->ISSGrade}-{$post->Subject}</div>";
echo "<div class='col-md-2'><strong>Due Date</strong>: {$post->DueDate}</div>";
echo "<div class='col-md-2'><strong>Possible Point:</strong> {$post->PossiblePoints}</div>";
echo "</div><hr/>";
?>
    
    <form class="form" method="post" action="" enctype="multipart/form-data">
        <?php wp_nonce_field('iss-delete-class-form-page', '_wpnonce-iss-delete-class-form-page') ?>
        <input type="hidden" id="ClassID" name="ClassID" value="<?php echo $classid; ?>" />    
        <input type="hidden" id="ClassID" name="PostID" value="<?php echo $postid; ?>" />    
        <div class="form-group">
             
            <input type="checkbox" id="agreeyes" name="agreeyes"> 
                <strong>Are you sure to delete this assingment and associated scores (if any)?</strong>
             <br/> <br/>
        </div>
        <div class="form-group agree">
               <button type="submit" name="submit" value="delete" class="btn btn-primary deletebutton">Delete</button>			
        </div>
    </form>
</div>
    
<?php 

include(plugin_dir_path(__FILE__) . "/assignment_footer.php");
?>

<script>
    $(document).ready(function() {
        $('button.deletebutton').prop('disabled', true);
            $('#agreeyes').click(function() {
            if (!this.checked)
                $('button.deletebutton').prop('disabled', true);
            else
                $('button.deletebutton').prop('disabled', false);

        });
    });
</script>

