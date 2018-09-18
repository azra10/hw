
<?php
iss_write_log($_GET);
iss_write_log($_POST);

$tab = 'teacher';
include(plugin_dir_path(__FILE__) . "/class_header.php");

if (isset($_POST['_wpnonce-iss-edit-class-form-page'])) { // POST
    check_admin_referer('iss-edit-class-form-page', '_wpnonce-iss-edit-class-form-page');

    if (isset($_POST['submit']) && ($_POST['submit']=='add') && isset($_POST['userid']) && isset($_POST['classid']) && isset($_POST['access'])) {
        $result = ISS_UserClassMapService::AddMapping($_POST['classid'], $_POST['userid'], $_POST['access']);
        if (1 == $result) {
            echo '<div class="alert alert-success"><strong>Teacher Added.</strong></div>';
        } else {
            echo '<div class="alert alert-danger"><strong>Error Adding Teacher.</strong></div>';
        }
    } else if (isset($_POST['submit']) && ($_POST['submit']=='remove') && isset($_POST['userid']) && isset($_POST['classid'])) {
        $result = ISS_UserClassMapService::RemoveMapping($_POST['classid'], $_POST['userid']);
        if (1 == $result) {
            echo '<div class="alert alert-success"><strong>Teacher Removed.</strong></div>';
        } else {
            echo '<div class="alert alert-danger"><strong>Error Removing Teacher.</strong></div>';
        }
    }  else {
        echo '<div class="alert alert-danger"><strong>Input Error.</strong></div>';
    }
}
if (null != $classid) {
    $availableteachers = ISS_ClassService::GetAllTeachers();
    $currentteachers = ISS_ClassService::GetClassTeachers($classid);
?>
    <h4>Class Teachers</h4>             
    <div class="form-group">
        <table  border=1 >
            <thead>
                <tr>
                    <th style="width:250px;background-color:#aecfda !important; color:#000;padding:5px;">Teacher </th>
                    <th style="width:250px;padding:5px;">Permission</th>
                    <th>
                </tr>
            </thead>
            <tbody>
                <?php if (null != $currentteachers)
                    foreach ($currentteachers as $teacher) { ?>
                <tr>
                        <td style="background-color:#aecfda;padding:5px;"> <?php echo $teacher->Teacher; ?> </td>                             
                        <td style="background-color:#f1f0d5;padding:5px;" ><?php echo $teacher->Access; ?></td>
                        <td style="width:250px;padding:5px;">  
                        <?php if (ISS_PermissionService::user_manage_access()) {
                            if (!empty($teacher->UserID)) { ?>
                                <form class="form" method="post" action="" enctype="multipart/form-data">
                                    <?php wp_nonce_field('iss-edit-class-form-page', '_wpnonce-iss-edit-class-form-page'); ?>
                                    <input type="hidden" id="classid" name="classid" value="<?php echo $classid; ?>" />        
                                    <input type="hidden" id="user" name="userid" value="<?php echo $teacher->UserID; ?>" />   
                                    <button id="submit" name="submit" class="btn btn-primary" value="remove">Remove</button> 
                                </form>
                            <?php }
                        } ?>
                        </td>  
                        </form>             
                </tr> 
                    <?php 
                } ?>
                    <tr>
                        <form class="form" method="post" action="" enctype="multipart/form-data">
                            <?php wp_nonce_field('iss-edit-class-form-page', '_wpnonce-iss-edit-class-form-page'); ?>
                            <input type="hidden" id="classid" name="classid" value="<?php echo $classid; ?>" />        

                            <td style="background-color:#aecfda; color:#FFFFFF;padding:5px;"> 
                            <select  id="userid" name="userid" class="form-control"  >               
                            <?php if ((null != $currentteachers) && (null != $availableteachers))
                                foreach ($availableteachers as $teacher) {
                                if (($teacher->ClassID != $classid) && (!empty($teacher->UserID)) && !isset($currentteachers[$teacher->UserID])) {
                                    echo "<option value='{$teacher->UserID}'>{$teacher->Teacher}</option>";
                                }
                            } ?>
                            </td>                             
                            <td style="background-color:#f1f0d5;padding:5px;" >
                            <select  id="access" name="access" class="form-control"  >   
                                <option value="primary">Primary</option>
                                <option value="write">Write</option>
                                <option value="read">Read</option>        
                            </select>  
                            
                            </td>
                            <td style="width:250px;padding:5px;"><button id="submit" name="submit" class="btn btn-primary" value="add">Add</button>   </td>      
                        </form>
                    </tr> 
                
            </tbody>
        </table>
    </div>

    <?php 
} 

include(plugin_dir_path(__FILE__) . "/class_footer.php");
?>               
  

