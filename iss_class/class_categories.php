<?php
iss_write_log($_GET);
iss_write_log($_POST);

$tab = 'category';
include(plugin_dir_path(__FILE__) . "/class_header.php");


if (isset($_POST['_wpnonce-iss-edit-class-form-page'])) { // POST
    check_admin_referer('iss-edit-class-form-page', '_wpnonce-iss-edit-class-form-page');

    if (isset($_POST['submit']) && ($_POST['submit'] == 'add') &&
        isset($_POST['percentage']) && is_numeric($_POST['percentage']) &&
        isset($_POST['classid']) && isset($_POST['typename']) && !empty($_POST['typename'])) {
        $result = ISS_AssignmentTypeService::AddCategory($_POST['classid'], $_POST['percentage'], $_POST['typename']);
        if (1 == $result) {
            echo '<div class="alert alert-success"><strong>Category Saved.</strong></div>';
        } else {
            echo '<div class="alert alert-danger"><strong>Error Adding Category.</strong></div>';
        }
    } else if (isset($_POST['submit']) && ($_POST['submit'] == 'remove') && isset($_POST['typeid']) && isset($_POST['classid'])) {
        $result = ISS_AssignmentTypeService::RemoveCategory($_POST['classid'], $_POST['typeid']);
        if (1 == $result) {
            echo '<div class="alert alert-success"><strong>Category Removed.</strong></div>';
        } else {
            echo '<div class="alert alert-danger"><strong>Error Removing Category.</strong></div>';
        }
    } else {
        echo '<div class="alert alert-danger"><strong>Input Error.</strong></div>';
    }
}
if (null != $classid) {
    $categories = ISS_AssignmentTypeService::GetClassAssignmentTypes($classid);
?>
    <h4>Assignment Categories</h4>  
    <p>Enter categories for your assignments, for example: Tests, Quizzes, and Homework. </p>
    <p>Each category will be worth X percentage of students' overall grades. </p>
    <p class="text-danger"><i>Remove Note: When a category is removed associated assignments will get reassinged to 'Not Graded' Category. </i> </p>

    <div class="form-group">
        <table  border=1 >
            <thead>
                <tr>
                    <th style="width:125px; background-color:#aecfda !important; color:#000;padding:5px;">Category </th>
                    <th style="width:125px; background-color:#f1f0d5 !important; color:#000;padding:5px;">Weighted Percentage</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category) { ?>
                <tr>
                    <td style="background-color:#aecfda; color:#FFFFFF;padding:5px;"> 
                        <input name="TypeName" type="text" class="disabled" disabled value="<?php echo $category->TypeName; ?>"  size="50" />
                    </td>
                    <td style="background-color:#f1f0d5;padding:5px;" >
                    <input name="TypePercentage" type="text" class="disabled" disabled value="<?php echo $category->TypePercentage; ?>") size="5"/>%                   
                    </td>  
                    <td>
                        <form class="form" method="post" action="" enctype="multipart/form-data">
                            <?php wp_nonce_field('iss-edit-class-form-page', '_wpnonce-iss-edit-class-form-page'); ?>
                            <input name="typeid" type="hidden" value="<?php echo $category->AssignmentTypeID; ?>" />  
                            <input name="classid" type="hidden" value="<?php echo $classid; ?>" />        
                            <button id="submit" name="submit" class="btn btn-primary" value="remove">Remove</button> 
                        </form>
                        </td>               
                </tr> 
                    <?php 
                } ?>
                <tr>
                    <form class="form" method="post" action="" enctype="multipart/form-data">
                        <?php wp_nonce_field('iss-edit-class-form-page', '_wpnonce-iss-edit-class-form-page'); ?>
                        <input name="classid" type="hidden" value="<?php echo $classid; ?>" />        
                        
                        <td style="background-color:#aecfda;"> 
                        <input name="typename" type="text"  value=""  size="50" />
                        </td>
                        <td style="background-color:#f1f0d5;" >
                        <input name="percentage" type="text"  value="") size="5"/>%                   
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
            <th>Category</th>
            <th>Weighted Percentage</th>               
        </tr>
        <!-- <tr><td>(Not Graded)</td><td>0%</td></tr> -->
        <tr><td>Attendance</td><td>10%</td></tr>
        <tr><td>Participation</td><td>10%</td></tr>
        <tr><td>Homework</td><td>40%</td></tr>
        <tr><td>Test</td><td>40%</td></tr>
        <tr><td>Informational</td><td>0%</td></tr>
    </table>

    <?php 
}
include(plugin_dir_path(__FILE__) . "/class_footer.php");
?>    


