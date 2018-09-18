
<?php
iss_write_log($_GET);
iss_write_log($_POST);

$tab = 'edit';
include(plugin_dir_path(__FILE__) . "/class_header.php");  

    
if (!function_exists('iss_show_error')) {
    function iss_show_error($errors, $field)
    {
        if ((null != $errors) && !empty($errors) && isset($errors[$field])) {
            echo $errors[$field];
        }
    }
}
$errors = array();
if (isset($_POST['_wpnonce-iss-edit-class-form-page'])) { // POST
    check_admin_referer('iss-edit-class-form-page', '_wpnonce-iss-edit-class-form-page');
    if (ISS_ClassService::isValid($_POST['FormArray'], $errors)) {
        if (null == $classid) { // ADD Class
            $result = ISS_ClassService::Add($_POST['FormArray']);
            if ($result > 0) {
                echo '<div class="alert alert-success"><strong>Class Saved.</strong></div>';
            } else {
                echo '<div class="alert alert-danger"><strong>Error Creating Class.</strong></div>';
            }
            exit;
        } else { // Update Class
            $result = ISS_ClassService::Update($_POST['FormArray']);
            if ($result > 0) {
                echo '<div class="alert alert-success"><strong>Class Updated.</strong></div>';
            }
            $class = ISS_ClassService::LoadByID($classid);
        }
    } else {
        // else populate value and show errors
        $class = ISS_Class::Create($_POST['FormArray']);
    }
} else {  // GET

    if (null != $classid) { 
        $class = ISS_ClassService::LoadByID($classid);
    } else {
        // Add New Class
        $class = new ISS_Class();
        $class->RegistrationYear = iss_registration_period();
        $disabled = ' disabled';
    }
}

// PAGE SPECIFIC
$classlist = ISS_Class::GetClassList();
?>


<h4>Basic Settings</h4>
<form class="form" method="post" action="" enctype="multipart/form-data">
    <?php wp_nonce_field('iss-edit-class-form-page', '_wpnonce-iss-edit-class-form-page'); ?>
    <input type="hidden" id="ClassID" name="FormArray[ClassID]" value="<?php echo $class->ClassID; ?>" />        
    
    <div>

        <!-- Registration Year -->
        <div class="form-group">
            <label class="control-label" for="RegistrationYear">Registration Year</label>
            <div class="input-group col-md-6">
                <select id="RegistrationYear" name="FormArray[RegistrationYear]" class="form-control">
                <?php echo "<option value=\"{$class->RegistrationYear}\">{$class->RegistrationYear}</option>"; ?>
                </select>
            </div>
        </div>

        <!-- ISSGrade-->
        <div class="form-group">
        <label class="control-label" for="ISSGrade">ISS Grade</label>  
            <div class="input-group col-md-6">
            <select id="ISSGrade" name="FormArray[ISSGrade]" class="form-control">
                <?php
                foreach ($classlist as $key => $value) {
                    $selected = ($class->ISSGrade == $key) ? 'selected' : '';
                    echo "<option value=\"{$key}\" {$selected} >{$value}</option>";
                }
                ?>
            </select>
            </div>
        </div>

        <!-- Subject-->
        <div class="form-group">
        <label class="control-label" for="Subject">Subject</label>  
        <div> 
            <label class="radio-inline" for="Subject1">
            <input class="form-check-input" type="radio" name="FormArray[Subject]" id="Subject1" value="Islamic Studies" 
                <?php echo ($class->Subject == 'Islamic Studies') ? 'checked' : ''; ?> /> Islamic Studies
            </label>
            <label class="radio-inline" for="Subject2">
            <input class="form-check-input" type="radio" name="FormArray[Subject]" id="Subject2" value="Quranic Studies" 
                <?php echo ($class->Subject == 'Quranic Studies') ? 'checked' : ''; ?> /> Quranic Studies
            </label>
        </div>  
        </div>

        <!-- Suffix-->
        <div class="form-group">
        <label class="control-label" for="Suffix">Name Suffix</label>    
            <div class="input-group col-md-6">
            <input id="Suffix" name="FormArray[Suffix]" class="form-control "  
            placeholder="Class Name Suffix (optional)" type="text" maxlength="20" value="<?php echo $class->Suffix; ?>" >
            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
            </div>  
            <p class="text-danger"><?php iss_show_error($errors, 'Suffix'); ?></p>  
        </div>

        <!-- Status  TODO another way to inactive and active-->
        <!-- <div class="form-group">
        <label class="control-label" for="radios">Status</label>
        <div> 
            <label class="radio-inline" for="Status1">
            <input class="form-check-input" type="radio" name="FormArray[Status]" id="Status1" value="active" 
            //echo ($class->Status != 'inactive') ? 'checked' : ''; 
            > Active       
            </label> 
            <label class="radio-inline" for="Status2">
            <input class="form-check-input" type="radio" name="FormArray[Status]" id="Status2" value="inactive" 
             //echo ($class->Status == 'inactive') ? 'checked' : '';   
             > Inactive
            </label> 
        </div>
        </div> -->

        <!-- Button -->
        <div class="form-group">
            <div class="col-md-4">
                <button id="submit" name="submit" class="btn btn-primary" value="general">Save Basic Settings</button>
            </div>  
            
        </div>
    </div>              
</form> 
<?php 
include(plugin_dir_path(__FILE__) . "/class_footer.php");  
?>


  