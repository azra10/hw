
<?php
iss_write_log($_GET);
iss_write_log($_POST);

$tab = 'scale';
include(plugin_dir_path(__FILE__) . "/class_header.php");


if (isset($_POST['_wpnonce-iss-edit-class-form-page'])) { // POST
    check_admin_referer('iss-edit-class-form-page', '_wpnonce-iss-edit-class-form-page');

    if (isset($_POST['submit']) && ($_POST['submit'] == 'add') &&
        isset($_POST['percentage']) && is_numeric($_POST['percentage']) &&
        isset($_POST['classid']) && isset($_POST['scalename']) && !empty($_POST['scalename'])) {
        $result = ISS_ScaleService::AddScale($_POST['classid'], $_POST['percentage'], $_POST['scalename']);
        if (1 == $result) {
            echo '<div class="alert alert-success"><strong>Scale Saved.</strong></div>';
        } else {
            echo '<div class="alert alert-danger"><strong>Error Adding Scale.</strong></div>';
        }
    } else if (isset($_POST['submit']) && ($_POST['submit'] == 'remove') && isset($_POST['scaleid']) && isset($_POST['classid'])) {
        $result = ISS_ScaleService::RemoveScale($_POST['classid'], $_POST['scaleid']);
        if (1 == $result) {
            echo '<div class="alert alert-success"><strong>Scale Removed.</strong></div>';
        } else {
            echo '<div class="alert alert-danger"><strong>Error Removing Scale.</strong></div>';
        }
    } else {
        echo '<div class="alert alert-danger"><strong>Input Error.</strong></div>';
    }
}
if (null != $classid) {
        $scales = ISS_ScaleService::GetClassScales($classid);
?>
    <h4>Grading Scale</h4>    
    <p>Select how you would like System to assign grades to students in this class.</p>

    <div class="form-group">
        <table  border=1 >
            <thead>
                <tr>
                    <th style="background-color:#aecfda;padding:5px;">Score</th>
                    <th style="width:100px; background-color:#f1f0d5 !important; color:#000;padding:5px;">Grade</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if (null != $scales)
                    foreach ($scales as $scale) { ?>
                <tr>
                            
                        <td style="background-color:#aecfda;padding:5px;">  
                        <strong> >= </strong><input name="percentage" type="text" class="disabled" disabled value="<?php echo $scale->ScalePercentage; ?>") size="5"/>%              
                        </td>
                        <td style="background-color:#f1f0d5;padding:5px;" >
                        <input name="scalename" type="text" class="disabled" disabled value="<?php echo $scale->ScaleName; ?>"  size="50" />              
                        </td>
                        <td>
                        <form class="form" method="post" action="" enctype="multipart/form-data">
                            <?php wp_nonce_field('iss-edit-class-form-page', '_wpnonce-iss-edit-class-form-page'); ?>
                            <input type="hidden" id="classid" name="classid" value="<?php echo $classid; ?>" />        
                            <input name="scaleid" type="hidden" value="<?php echo $scale->ScaleID; ?>" />                
                            <button id="submit" name="submit" class="btn btn-primary" value="remove">Remove</button> 
                        </form>
                        </td>                      
                </tr> 
                    <?php 
                } ?>
                <tr>
                    <form class="form" method="post" action="" enctype="multipart/form-data">
                        <?php wp_nonce_field('iss-edit-class-form-page', '_wpnonce-iss-edit-class-form-page'); ?>
                        <input type="hidden" id="classid" name="classid" value="<?php echo $classid; ?>" />        
                                
                        <td style="background-color:#aecfda;padding:5px;">  
                        <strong> >= </strong><input name="percentage" type="text" value="") size="5"/>%              
                        </td>
                        <td style="background-color:#f1f0d5;padding:5px;" >
                        <input name="scalename" type="text" class="scoreinput" value=""  size="50" />              
                        </td>
                        <td>
                        <button id="submit" name="submit" class="btn btn-primary" value="add">Add</button> 
                        </td>
                    </form>
                </tr> 
            </tbody>
        </table>
    </div>
    <hr/>
    <div><strong>Example:</strong></div>
    <table border=1>
        <tr>
            <th>Score</th>
            <th>Grade</th>
            <th></th>
        </tr>
        <tr><td>>= 90%</td><td>A</td></tr>
        <tr><td>>= 70%</td><td>B</td></tr>
        <tr><td>>= 60%</td><td>C</td></tr>
        <tr><td>>= 50%</td><td>D</td></tr>
        <tr><td>>= 0%</td><td>F</td></tr>
    </table>

        <?php 
} ?>               
  
<?php 
include(plugin_dir_path(__FILE__) . "/class_footer.php");
?>