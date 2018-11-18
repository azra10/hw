<div>
   <?php 
    iss_show_heading_with_regyear("Classses");
    $zeroresult = true;
    if (ISS_PermissionService::class_manage_access()) {
        echo "<a class='btn btn-success' href='admin.php?page=issvaddclass'> <i class='fas fa-id-card'></i> Add New</a>";
    }
    // ALL CLASS ACCESS -  BEGIN

    if (ISS_PermissionService::class_list_all_access()) { // all classes access (iss_admin, iss_board, iss_secretoary)

        $result_set = ISS_ClassService::GetAllClasses();

        if (null != $result_set) {
            $zeroresult = false; ?>
        
        <table class="table table-striped table-responsive table-condensed" id="iss_class_table">
        <thead>    
            <tr>  
                <th>Name</th>   
                <th>Primary Teacher</th>    
                <th></th>  
            </tr>   
        </thead>
        <tbody>
            <?php foreach ($result_set as $row) { ?>
            <tr>
                <td><a href="admin.php?page=issvalist&cid=<?php echo $row->ClassID; ?>"> 
                    <i class='fas fa-id-card iss_css_class'></i>  <?php echo $row->Name; ?></a>
                </td>
                <td> <?php echo $row->Teacher; ?> </td>
                <td>
                    <a href="admin.php?page=issvstudentlist&cid=<?php echo $row->ClassID; ?>">
                        <span style="padding-left: 20px; white-space: nowrap;"> <i class="fas fa-users iss_css_user "></i> Students </span>
                    </a>
                    <?php if (ISS_PermissionService::class_manage_access()) { ?>
                    <a href="admin.php?page=issemailclass&cid=<?php echo $row->ClassID; ?>">
                            <span style="padding-left: 20px; white-space: nowrap;"> <i class="fas fa-envelope iss_css_email"></i> Email Class </span>
                    </a>
                    <a href="admin.php?page=issemailteacher&cid=<?php echo $row->ClassID; ?>">
                                    <span style="padding-left: 20px; white-space: nowrap;"> <i class="fas fa-envelope"></i> Email Teacher </span>
                    </a>
                    <a href="admin.php?page=issvaddclass&cid=<?php echo $row->ClassID; ?>"> 
                            <span style='padding-left: 20px; white-space: nowrap;''><i class='fas fa-cog iss_css_setting'></i> Settings</span>
                    </a>
                    <a href="admin.php?page=issvclassprogress&cid=<?php echo $row->ClassID; ?>"> 
                        <span style="padding-left: 10px; white-space: nowrap;"> <i class="fas fa-battery-half iss_css_progress"></i> Progress </span>
                    </a>
                        <?php
                    } //else {  TODO Add a view for the board }
                    ?>
                 </td>
            </tr>
                <?php 
            } // result set not null ?>
        </tbody>
        </table>
        
            <?php 
        }
    }  // all classes access
 
    // ALL CLASS ACCESS -  END

    else { // not all classes
       
        // TEACHER CLASS ACCESS - BEGIN

        if (ISS_PermissionService::class_list_teacher_access()) {
            $result_set = ISS_ClassService::GetTeacherClasses();
            if (null != $result_set) {
                $zeroresult = false; ?>
            <h4>Teacher Classes</h4>       
            <table class="table table-striped table-responsive table-condensed" id="iss_class_table">
            <thead>    
                <tr>  
                    <th>Name</th>   
                    <th>Primary Teacher</th>
                    <th></th>  
                </tr>   
            </thead>
            <tbody>
            <?php foreach ($result_set as $row) { ?>
                <tr>
                <td><a href="admin.php?page=issvalist&cid=<?php echo $row->ClassID; ?>"> 
                    <i class='fas fa-id-card iss_css_class'></i>  <?php echo $row->Name; ?></a></td>
                <td> <?php echo $row->Teacher; ?> </td>
                <td>
                    <a href="admin.php?page=issvstudentlist&cid=<?php echo $row->ClassID; ?>">
                        <span style="padding-left: 10px; white-space: nowrap;"> <i class="fas fa-users iss_css_user "></i> Students </span>
                    </a>
                    <a href="admin.php?page=issemailclass&cid=<?php echo $row->ClassID; ?>">
                        <span style="padding-left: 10px; white-space: nowrap;"> <i class="fas fa-envelope iss_css_email"></i> Email Class </span>
                    </a> 
                    <a href="admin.php?page=issvaddclass&cid=<?php echo $row->ClassID; ?>"> 
                        <span style="padding-left: 10px; white-space: nowrap;"> <i class="fas fa-cog iss_css_setting"></i> Settings </span>
                    </a>
                    <a href="admin.php?page=issvclassprogress&cid=<?php echo $row->ClassID; ?>"> 
                        <span style="padding-left: 10px; white-space: nowrap;"> <i class="fas fa-battery-half iss_css_progress"></i> Progress </span>
                    </a>
                 </td>
                </tr>
                <?php 
            } ?>
            </tbody>
            </table>

                <?php 
            } // result set not null
        } // teacher classes

         // TEACHER CLASS ACCESS - END
   
         // STUDENT CLASS ACCESS - BEGING

        if (ISS_PermissionService::class_list_student_access()) { // student/parent classes
            $result_set = ISS_ClassService::GetStudentClasses();
            if (null != $result_set) {
                $zeroresult = false; ?>
            <h4>Student Classes</h4>       
            <table class="table table-striped table-responsive table-condensed" id="iss_class_table">
                <thead>    
                    <tr> 
                        <th>Class</th>   
                        <th>Primary Teacher</th>   
                        <th>Student</th>    
                        <th>Score</th>  
                        <th></th>
                    </tr>   
                </thead>
                <tbody>
                <?php foreach ($result_set as $row) { ?>
                    <tr>               
                    <td><a href="admin.php?page=issvalist&cid=<?php echo "{$row->ClassID}&svid={$row->StudentViewID}"; ?>"> 
                        <i class='fas fa-id-card iss_css_class'></i>  <?php echo $row->Name; ?></a></td>
                    <td> <?php echo $row->Teacher; ?> </td>
                    <td><?php echo $row->StudentName; ?> </td>
                    <td><?php echo $row->StudentTotalScore . '%'; ?> </td>
                    <td>
                        <a href="admin.php?page=issemailteacher&cid=<?php echo $row->ClassID; ?>">
                                <span style="padding-left: 10px; white-space: nowrap;"> <i class="fas fa-envelope iss_css_email"></i> Email Teacher </span>
                        </a>
                </td>
                </tr>
                    <?php 
                } ?>
                </tbody>
            </table>

                <?php 
            }  // result set not null
        } // student/parent classes

        // STUDENT CLASS ACCESS - END

    } // not all classes ?> 
</div>
