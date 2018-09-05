<div class="container">
<?php
$errTitle = $errContent = $errDuedate = $errPossiblepoints = null;
$assignment = $class = null;

if (isset($_GET['post']) || !empty($_GET['post'])) {
    $postid = iss_sanitize_input($_GET['post']);
    $assignment = ISS_AssignmentService::LoadByID($postid);
    if (null != $assignment) {
        iss_write_log($assignment);
        $classid = $assignment->ClassID;
        $category = $assignment->Category;
        $duedate = $assignment->DueDate;
        $possiblepoints = $assignment->PossiblePoints;
        $postid = $assignment->PostID;
        $title = $assignment->Title;
        $content = $assignment->Content;
        $attachments = ISS_AssignmentService::LoadAttachmentsByID($postid);
    }
} else {
    if (isset($_GET['cid'])) {
        $classid = iss_sanitize_input($_GET['cid']);
        $class = ISS_ClassService::LoadByID($classid);
        if (null != $class) {
            $classid = $class->ClassID;
            $category = $class->Category;
            $duedate = date("Y-m-d");
            $possiblepoints = 10;
            $title = null;
            $content = null;
            $attachments = null;
            $postid = null;
        }
    }
}

if ((null == $assignment) && (null == $class)) {
    $backurl = admin_url('admin.php?page=issvclasslist');
    iss_show_heading_with_backurl("Assignment", $backurl);
    echo "<h3> Class / Assingment not found.</h3>";
    exit;
}

if (isset($_POST['_wpnonce-iss-email-teacher-form-page'])) {
    include(plugin_dir_path(__FILE__) . "/assignment_post.php");
}

$backurl = admin_url('admin.php?page=issvalist&cid=' . $classid);
iss_show_heading_with_backurl("Assignment", $backurl);
?>

<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
<?php wp_nonce_field('iss-email-teacher-form-page', '_wpnonce-iss-email-teacher-form-page') ?>
   
    <div>
        <input type="hidden" name="category" class="" value="<?php echo $category; ?>">
        <input type="hidden" name="classid" class="" value="<?php echo $classid; ?>">
        <input type="hidden" name="postid" class="" value="<?php echo $postid; ?>">
    </div>
    <div class="form-group">
		<div class="col-sm-10">
		<label for="subejct" class="control-label">Title: <?php echo "<span class='text-danger'>$errTitle</span>"; ?></label> 
			<input type="text" required class="form-control" id="title" name="title" placeholder="Title" value="<?php echo $title ?>">			
		</div>
    </div>
     <div class="form-group">
		<div class="col-sm-10">
        <label for="to" class="control-label">Possible Points: <?php echo "<span class='text-danger'>$errPossiblepoints</span>"; ?></label>
        <input type="text" id="possiblepoints" name="possiblepoints" class="form-control" required value="<?php echo $possiblepoints; ?>">
          </div>
    </div>             
    <div class="form-group">
		<div class="col-sm-10">
        <label for="to" class="control-label">Due Date: <?php echo "<span class='text-danger'>$errDuedate</span>"; ?></label>
        <input type="text" id="duedate" name="duedate" class="my-custom-datepicker-field form-control" required value="<?php echo $duedate; ?>">
        </div>
    </div>     
    <div class="form-group">
     	<div class="col-sm-10">
            <label for="message" class="control-label"><?php echo "<span class='text-danger'>$errContent</span>"; ?></label>		     
            <?php wp_editor($content, 'message', array('media_buttons' => false, 'textarea_rows' => 5)); ?>
 		</div>
    </div> 
    <hr/>
    <div class="form-group">
		<div class="col-sm-10">
            <?php if (null != $attachments) {
                echo "<label>Attachments:</label>";
                echo "<table class='table table-striped' border=1>";
                foreach ($attachments as $row) {
                    echo "<tr><td>";
                    echo "<a href='admin.php?page=issvdeleteattachment&post={$row['ID']}&cid={$classid}&backid={$postid}' class='text-danger btn btn-danger btn-sm'>
                    <i class='fas fa-trash-alt'></i> Delete</a>   <a href='{$row['guid']}'>{$row['post_title']}</a> ";
                    echo "</td></tr>";
                }
                echo "</table>";
            }
            ?>       
        </div>
	</div>
    <div class="form-group">
		<div class="col-sm-10" >   
        <div class="text-info"> (Choose file, click on 'Save Assignment' to upload the file!) </div>
       <label class="control-label">  <input type="file" id="file1" name="file1"/> </label>
        </div>
    </div>
    <hr/>
	<div class="form-group">
		<div class="col-sm-5">
			<input id="submit" name="submit" type="submit" value="Save Assignment" class="btn btn-primary" />
        </div>
        <div class="col-sm-5 pull-right">
        <?php if (ISS_PermissionService::class_assignment_write_access($classid) && !empty($postid)) { ?>              
            <a href="admin.php?page=issvadelete&post=<?php echo "{$postid}&cid={$classid}"; ?>" class="btn btn-danger"
            onclick="return confirm('Are you sure you want to delete this assignment?')">
                <span style="padding-left: 10px; white-space: nowrap;"> <i class="fas fa-trash-alt"></i> Delete Assignment</span>
            </a>               
            <?php     
        } ?>
        </div>
	</div>
</form> 
</div>
<script>
    jQuery(document).ready(function($){
        $('.my-custom-datepicker-field').datepicker({
            dateFormat: 'yy-mm-dd', //maybe you want something like this
            showButtonPanel: true
        });
    });
</script>


