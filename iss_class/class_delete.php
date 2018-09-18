<?php

$error = null;
if (isset($_POST['_wpnonce-iss-delete-class-form-page'])) {

    check_admin_referer('iss-delete-class-form-page', '_wpnonce-iss-delete-class-form-page');

    if (!isset($_POST['ClassID']) || empty($_POST['ClassID']) || (intval($_POST['ClassID']) == 0)) {
        echo '<div class="alert-danger alert"><strong>Unknown class.</strong></div>';
        exit;
    }
    $classid = iss_sanitize_input($_POST['ClassID']);

    // TODO take care of dependency error (blank page)
    $result = ISS_ClassService::DeleteByID($classid);

    if ($result > 0) {
        $backurl = admin_url('admin.php?page=issvclasslist');
        iss_show_heading_with_backurl("Delete Class ", $backurl);
        echo '<div class="alert-danger alert"><strong>Class Deleted.</strong></div>';
        exit;
    } else {
        $error = '<div class="alert alert-success"><strong>Unable to delete Class. </strong></div>
            <div>Check dependencies (Categories, Grading Scale, Teachers and Assignments).</div>';
        exit;
    }
} 

$tab = 'delete';
include(plugin_dir_path(__FILE__) . "/class_header.php");
echo "<h4>Delete Class</h4>"; 
echo $error;
$class = ISS_ClassService::LoadByID($classid);

if ($class == null) {
    echo '<div class="alert-danger alert"><strong>Unknown record.</strong></div>';
    exit;
}
?>

<form class="form" method="post" action="" enctype="multipart/form-data">
    <?php wp_nonce_field('iss-delete-class-form-page', '_wpnonce-iss-delete-class-form-page') ?>
    <input type="hidden" id="ClassID" name="ClassID" value="<?php echo $classid; ?>" />    
	<div class="form-group">
 		<h5>ISSGrade: <?php echo $class->ISSGrade; ?></h5>
        <h5>Subject: <?php echo $class->Subject; ?></h5>
        <h5>Suffix: <?php echo $class->Suffix; ?></h5>
		<h5>Status: <?php echo $class->Status; ?></h5>
        <input type="checkbox" id="agreeyes" name="agreeyes"> 
			<strong>Are you sure to delete this class record?</strong>
         <br/> <br/>
    </div>
    <div class="form-group agree">
           <button type="submit" name="submit" value="delete" class="btn btn-primary deletebutton">Delete</button>			
    </div>
</form>
</div>

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
