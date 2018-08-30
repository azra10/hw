<?php
class ISS_Student
{
    public $StudentViewID;
    public $ParentID;
    public $StudentID;
    public $StudentEmail;
    public $StudentStatus;
    public $StudentFirstName;
    public $StudentLastName;
    public $StudentGender;
    public $created;
    public $updated;
    public $ISSGrade;
    public $RegistrationYear;
    public $FatherFirstName;
    public $FatherLastName;
    public $FatherEmail;
    public $MotherFirstName;
    public $MotherLastName;
    public $MotherEmail;
    public $UserEmail;
    public $UserID;
    public $Access;
    public $LastLogin;
    public $SchoolEmail;

    public static function GetStudentAccountViewName()
    {
        global $wpdb;
        return $wpdb->prefix . "issv_student_accounts";
    }
    public static function GetTableName()
    {
        global $wpdb;
        return $wpdb->prefix . "iss_student";
    }
    public static function GetFieldsArray()
    {
        return array(
            "StudentViewID", "StudentID", "StudentFirstName", "StudentLastName", "StudentGender", "StudentStatus", "ISSGrade", "SchoolEmail",
            "ParentID", "FatherFirstName", "FatherLastName", "FatherEmail", "MotherFirstName", "MotherLastName", "MotherEmail", "RegistrationYear"
        );
    }
    public static function Create(array $row)
    {
        $instance = new self();
        $instance->fill($row);
        return $instance;
    }

	// TODO Add more fields to make the complete row
    public function fill(array $row)
    {
        // fill all properties from array
        if (is_array($row) && !empty($row)) {
            if (isset($row['ParentID'])) {
                $this->ParentID = $row['ParentID'];
            }
            if (isset($row['StudentID'])) {
                $this->StudentID = $row['StudentID'];
            }
            if (isset($row['StudentFirstName'])) {
                $this->StudentFirstName = $row['StudentFirstName'];
            }
            if (isset($row['StudentLastName'])) {
                $this->StudentLastName = $row['StudentLastName'];
            }
            if (isset($row['StudentEmail'])) {
                $this->StudentEmail = $row['StudentEmail'];
            }
            if (isset($row['FatherFirstName'])) {
                $this->FatherFirstName = $row['FatherFirstName'];
            }
            if (isset($row['FatherLastName'])) {
                $this->FatherLastName = $row['FatherLastName'];
            }
            if (isset($row['FatherEmail'])) {
                $this->FatherEmail = $row['FatherEmail'];
            }
            if (isset($row['MotherFirstName'])) {
                $this->MotherFirstName = $row['MotherFirstName'];
            }
            if (isset($row['MotherLastName'])) {
                $this->MotherLastName = $row['MotherLastName'];
            }
            if (isset($row['MotherEmail'])) {
                $this->MotherEmail = $row['MotherEmail'];
            }
            if (isset($row['SchoolEmail'])) {
                $this->SchoolEmail = $row['SchoolEmail'];
            }
            if (isset($row['UserEmail'])) {
                $this->UserEmail = $row['UserEmail'];
            }
            if (isset($row['UserID'])) {
                $this->UserID = $row['UserID'];
            }
            if (isset($row['Access'])) {
                $this->Access = $row['Access'];
            }
            if (isset($row['ISSGrade'])) {
                $this->ISSGrade = $row['ISSGrade'];
            }
            if (isset($row['StudentViewID'])) {
                $this->StudentViewID = $row['StudentViewID'];
            }
            if (isset($row['RegistrationYear'])) {
                $this->RegistrationYear = $row['RegistrationYear'];
            }
            if (isset($row['StudentGender'])) {
                $this->StudentGender = $row['StudentGender'];
            }
            if (isset($row['StudentStatus'])) {
                $this->StudentStatus = $row['StudentStatus'];
            }
            if (isset($row['LastLogin'])) {
                $this->LastLogin = $row['LastLogin'];
            }
            if (isset($row['created'])) {
                $this->created = $row['created'];
            }
            if (isset($row['updated'])) {
                $this->updated = $row['updated'];
            }
            return;
        }
        throw new Throwable("__construct input object is null/empty");
    }
}
class ISS_StudentService
{
    public static function error($message)
    {
        iss_write_log("Error ISS_StudentService::" . print_r($message, true));
    }

    public static function debug($message)
    {
        iss_write_log("Debug ISS_StudentService::" . print_r($message, true));
    }

    public static function GetStudentCount($registrationyear)
    {
        try {
            self::debug("GetStudentCount");
            if (!empty($registrationyear)) {
                $table = ISS_Student::GetTableName();
                global $wpdb;
                $query = $wpdb->prepare("SELECT count(StudentID) AS StudentCount FROM {$table} 
						WHERE RegistrationYear = %s and StudentStatus = %s", $registrationyear, 'active');
                $result_set = $wpdb->get_row($query, ARRAY_A);
                if ($result_set != null) {
                    return $result_set['ParentCount'];
                }
            }
        } catch (Exception $ex) {
            self::error($ex->getMessage());
        }
        return -1;
    }
    public static function LoadByID($id)
    {
        try {
            self::debug("LoadByID {$id}");
            global $wpdb;
            $table = ISS_Student::GetTableName();
            $regyear = iss_registration_period();

            $query = $wpdb->prepare("SELECT *  FROM {$table} where  RegistrationYear = '{$regyear}'  and StudentID = %d", $id);
            $row = $wpdb->get_row($query, ARRAY_A);
            if (null != $row) {
                return ISS_Student::Create($row);
            }
        } catch (Throwable $ex) {
            self::error($ex->getMessage());
        }
        return null;
    }
    public static function LoadByUserID($sid, $uid)
    {
        try {
            self::debug("LoadByID StudentId:{$sid} UserID:{$uid}");
            global $wpdb;
            $table = ISS_Student::GetStudentAccountViewName();
            $regyear = iss_registration_period();

            $query = "SELECT *  FROM {$table} where  RegistrationYear = '{$regyear}'  and UserID = {$uid} and StudentID = {$sid}";
            $row = $wpdb->get_row($query, ARRAY_A);
            if (null != $row) {
                return ISS_Student::Create($row);
            }
        } catch (Throwable $ex) {
            self::error($ex->getMessage());
        }
        return null;
    }
    public static function LoadByStudentViewID($id)
    {
        try {
            self::debug("LoadByStudentViewID {$id}");
            global $wpdb;
            $table = ISS_Student::GetTableName();
            $query = $wpdb->prepare("SELECT *  FROM {$table} where StudentViewID = %d", $id);
            $row = $wpdb->get_row($query, ARRAY_A);
            if (null != $row) {
                return ISS_Student::Create($row);
            }
        } catch (Throwable $ex) {
            self::error($ex->getMessage());
        }
        return null;
    }

    /**
     * GetClassStudents function
     *
     * @return array of ISS_Student Objects
     */
    public static function GetStudentsByClassID($cid)
    {
        self::debug("GetStudentsByClassID");
        $list = array();

        global $wpdb;
        $userid = get_current_user_id();
        $columns = "ClassID , StudentViewID, StudentFirstName , StudentLastName , StudentGender, LastLogin";
        $table = ISS_Class::GetClassStudentsViewName();

        if (ISS_PermissionService::is_user_parent_role() || ISS_PermissionService::is_user_student_role() || ISS_PermissionService::is_user_teacher_role()) {

            $accesstable = ISS_UserStudentMap::GetTableName();
        //SELECT * FROM `local_issv_class_students` S, local_iss_userstudentmap M where S.`StudentID` = M.StudentID  AND ClassID = 15 AND M.USerId = 7
            $query = "SELECT {$columns} FROM {$table} S , {$accesstable} M  "
                . "WHERE M.StudentID = S.StudentID AND ClassID = {$cid} AND UserID = {$userid}";

            self::debug("Student / Parent Access class students " . $userid . $query);
            $result_set = $wpdb->get_results($query, ARRAY_A);
            self::debug($result_set);

            foreach ($result_set as $obj) {
                $list[$obj['StudentViewID']] = ISS_Student::Create($obj);
            }
        }

        $accesstable = ISS_UserStudentMap::GetLastLoginTableName();
       
        // A parent can also be teacher / admin          
        if (ISS_ClassService::is_teacher_access($cid)) {

            // SELECT * FROM `local_issv_class_students` S LEFT JOIN local_iss_userstudentmap M ON S.`StudentID` = M.StudentID WHERE ClassID = 15
            $query = "SELECT {$columns} FROM {$table} S LEFT JOIN {$accesstable} M ON M.StudentID = S.StudentID WHERE  ClassID = {$cid}";
            self::debug("Teache Access class students " . $query);
            $result_set = $wpdb->get_results($query, ARRAY_A);
            self::debug($result_set);

            foreach ($result_set as $obj) {
                $list[$obj['StudentViewID']] = ISS_Student::Create($obj);
            }
        } else if (ISS_PermissionService::class_student_list_all_access($cid)) {

             // SELECT * FROM `local_issv_class_students` S LEFT JOIN local_iss_userstudentmap M ON S.`StudentID` = M.StudentID WHERE ClassID = 15
            $query = "SELECT {$columns} FROM {$table} S LEFT JOIN {$accesstable} M ON M.StudentID = S.StudentID WHERE  ClassID = {$cid}";
            self::debug("Teache / Admin Access class students " . $query);
            $result_set = $wpdb->get_results($query, ARRAY_A);
            self::debug($result_set);

            foreach ($result_set as $obj) {
                $list[$obj['StudentViewID']] = ISS_Student::Create($obj);
            }
        }
        return $list;
    }
    /**
     * GetStudents function
     * @return  array of ISS_Student Objects
     */
    public static function GetStudentAccounts($initial)
    {
        self::debug("GetStudentAccounts");
        $list = array();

        global $wpdb;
        $regyear = iss_registration_period();

        if (ISS_PermissionService::user_manage_access()) {
            $table = ISS_Student::GetStudentAccountViewName();
            $query = "SELECT  *  FROM {$table} WHERE  RegistrationYear = '{$regyear}' ";
            if (null == $initial) {
                $query .= " and StudentStatus = 'active' ";
            } else {
                if ('inactive' == $initial) {
                    $query .= " and StudentStatus = 'inactive' ";
                } else {
                    $query .= " and ISSGrade = '{$initial}' ";
                }
            }
            $query .= " order by  StudentFirstName ";
            self::debug($query);
            $result_set = $wpdb->get_results($query, ARRAY_A);
            foreach ($result_set as $obj) {
                $list[] = ISS_Student::Create($obj);
            }

            return $list;

        }
        return null;
    }
    /**
     * GetStudents function
     * @return  array of ISS_Student Objects
     */
    public static function GetStudentEmails($initial)
    {
        self::debug("GetStudentAccounts");
        $list = array();

        global $wpdb;
        $regyear = iss_registration_period();

        if (ISS_PermissionService::can_email_class()) {
            $table = ISS_Student::GetTableName();
            $query = "SELECT  StudentViewID, StudentEmail, FatherEmail, MotherEmail, SchoolEmail  FROM {$table} "
                . "WHERE  RegistrationYear = '{$regyear}'  and StudentStatus = 'active'  and ISSGrade = '{$initial}' ";

            self::debug($query);
            $result_set = $wpdb->get_results($query, ARRAY_A);
            foreach ($result_set as $obj) {
                $list[] = ISS_Student::Create($obj);
            }
            return $list;
        }
        return null;
    }
    public static function GetStudentAccountsByStudentID($svid)
    {
        self::debug("GetStudentAccountsByStudentViewID");
        $list = array();

        global $wpdb;
        if (ISS_PermissionService::user_manage_access()) {
            $table = ISS_Student::GetStudentAccountViewName();
            $query = "SELECT  *  FROM {$table} WHERE  StudentID = '{$svid}' ";

            $result_set = $wpdb->get_results($query, ARRAY_A);
            self::debug($result_set);
            foreach ($result_set as $obj) {
                $list[] = ISS_Student::Create($obj);
            }
            return $list;
        }
        return null;
    }

    /**
     * Function AddStudent
     * Insert parent record
     * 
     * @param
     *        	with minimum required fields (StudentViewID, StudentID, ParentID, RegistrationYear, StudentLastName, StudentFirstName, ISSGrade)
     * @return studentid
     *        
     */
    public static function AddStudent($sdata)
    {
        try {
            self::debug("AddStudent");
            iss_write_log($sdata);
            if (!isset($sdata['ParentID']) || empty($sdata['ParentID']) ||
                !isset($sdata['StudentID']) || empty($sdata['StudentID']) ||
                !isset($sdata['StudentViewID']) || empty($sdata['StudentViewID']) ||
                !isset($sdata['RegistrationYear']) || empty($sdata['RegistrationYear']) ||
                !isset($sdata['ISSGrade']) || empty($sdata['ISSGrade']) ||
                !isset($sdata['StudentLastName']) || empty($sdata['StudentLastName']) ||
                !isset($sdata['StudentFirstName']) || empty($sdata['StudentFirstName'])) {
                iss_write_log("Cannot insert student due to minimum required fields");
                return 0;
            }

            $table = ISS_Student::GetTableName();
            global $wpdb;

            $dsarray = array();
            $typearray = array();

            foreach (ISS_Student::GetFieldsArray() as $field) {
                if (isset($sdata[$field])) {
                    $dsarray[$field] = $sdata[$field];
                    $typearray[] = iss_field_type($field);
                }
            }
            //$dsarray['created'] = current_time('mysql'); // date('d-m-Y H:i:s');
            //$typearray[] = iss_field_type('created');

            iss_write_log($dsarray);
		
		// check again
            $query = "SELECT * FROM {$table} WHERE  StudentViewID = {$sdata['StudentViewID']} LIMIT 1";
            $row = $wpdb->get_row($query, ARRAY_A);
            if ($row != null) {
                iss_write_log('iss_student_insert skipped');
                return $sdata['StudentViewID'];
            }

            $result = $wpdb->insert($table, $dsarray, $typearray);
            if ($result == 1) {
                return $sdata['StudentViewID'];
            }
        } catch (Exception $ex) {
            iss_write_log("Error" . $ex . getMessage());
        }
        return 0;
    }


    /**
     * Function iss_student_update
     * Update student record
     * 
     * @param $sobejct with StudentViewID key required
     *        	
     *        	$changed fields to update 
     * @return 1 for success and 0 for no update
     *        
     */
    public static function UpdateStudent($changedfields, $sobject)
    {
        try {
            self::debug("UpdateStudent");
            iss_write_log($changedfields);
            iss_write_log($sobject);

            if (!isset($sobject->StudentViewID) || empty($sobject->StudentViewID)) {
                iss_write_log("Cannot update student due to minimum required fields");
                return 0;
            }

            $update = false;
            $dsarray = array();
            $typearray = array();

            foreach (ISS_Student::GetFieldsArray() as $field) {
                if (in_array($field, $changedfields)) {
                    $update = true;
                    $dsarray[$field] = $sobject->$field;
                    $typearray[] = iss_field_type($field);
                }
            }
            if ($update) {
                iss_write_log("student table update");
                iss_write_log($dsarray);
                $table = ISS_Student::GetTableName();
                global $wpdb;
                $result = $wpdb->update($table, $dsarray, array(
                    'StudentViewID' => $sobject->StudentViewID
                ), $typearray, array(
                    '%d'
                ));
                return $result;
            }

        } catch (Exception $ex) {
            iss_write_log("Error" . $ex . getMessage());
        }
        return 0;
    }

    public static function ChangeStatus($svid)
    {
        self::debug("UpdateStudent {$svid}");
        if (!isset($svid) || empty($svid)) {
            iss_write_log("Cannot update student due to minimum required fields");
            return 0;
        }
        $table = ISS_Student::GetTableName();
        global $wpdb;
        $query = "SELECT * FROM {$table} WHERE  StudentViewID = {$svid} LIMIT 1";
        $row = $wpdb->get_row($query, ARRAY_A);
        if ($row != null) {
            if ($row['StudentStatus'] == 'active') {
                $wpdb->update($table, array('StudentStatus' => 'inactive'), array('StudentViewID' => $svid), array("%s"), array('%d'));
                return 'inactive';
            } else {
                $wpdb->update($table, array('StudentStatus' => 'active'), array('StudentViewID' => $svid), array("%s"), array('%d'));
                return 'active';
            }
        }
        return '';
    }


    public static function CreateUserAccount($student, $email_address, $role, $password, $message, &$error)
    {
        if (null == username_exists($email_address)) {

        // Generate the password and create the user
            if (empty($password))
                $password = wp_generate_password(12, false);

            self::debug("CreateUserAccount SID:{$student->StudentID} U:{$email_address} P:{$password} Role:{$role}");
            $user_id = wp_create_user($email_address, $password, $email_address);
            self::debug($user_id);
            if (is_wp_error($user_id)) {
                $error = $user_id->get_error_message();
                return 1;
            }
            if (($role = 'issparentrole') && ($student->FatherEmail == $email_address)) {
                wp_update_user(array(
                    'ID' => $user_id,
                    'display_name' => $student->FatherFirstName . ' ' . $student->FatherLastName,
                    'nickname' => $student->FatherFirstName,
                    'first_name' => $student->FatherFirstName,
                    'last_name' => $student->FatherLastName,
                    'role' => $role
                ));
            } else if (($role = 'issparentrole') && ($student->MotherEmail == $email_address)) {
                wp_update_user(array(
                    'ID' => $user_id,
                    'display_name' => $student->MotherFirstName . ' ' . $student->MotherLastName,
                    'nickname' => $student->MotherFirstName,
                    'first_name' => $student->MotherFirstName,
                    'last_name' => $student->MotherLastName,
                    'role' => $role
                ));
            } else {
                wp_update_user(array(
                    'ID' => $user_id,
                    'display_name' => $student->StudentFirstName . ' ' .  $student->StudentLastName,
                    'nickname' => $student->StudentFirstName,
                    'first_name' => $student->StudentFirstName,
                    'last_name' => $student->StudentLastName,
                    'role' => $role
                ));
            }
            $result = ISS_UserStudentMapService::AddMapping($student->StudentID, $user_id, $role);
            if (1 == $result) {


                if (strpos($email_address, '@gmail.com') !== false) {

                    $message = $message . " Use button 'Continue with Google' to login with you google Username/Password.

OR ";

                } else {
                    $message = $message . " 
Please note that you can link a gmail account and login using gmail username/password (video in help section for instructions)

OR ";
                }

                $message = $message . "
Login with following username/password (please change password the first time you login)

Username: {$email_address}
Password: {$password}

School Admin";

                $headers[] = 'CC: IslamicSchoolOfSiliconValley@learnislam.org';
                iss_write_log('To:' . $email_address);
                iss_write_log($message);
                iss_write_log($headers);

                wp_mail($email_address, 'Welcome to ISSV Homework and Grading Site!', $message, $headers);
                return $result;
            }
        } else {
            $user = get_user_by('email', $email_address);
            self::debug($user);
            if (null != $user) {
                $user->set_role($role);
                return ISS_UserStudentMapService::AddMapping($student->StudentID, $user->ID, $role);
            }
        }
        return 0;
    }

}
?>