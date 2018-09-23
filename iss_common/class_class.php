<?php
class ISS_Class
{
    public $GradingPeriodID;
    public $ClassID;
    public $GradingPeriod;
    public $RegistrationYear;
    public $Name;
    public $ISSGrade = 'KG';
    public $Subject = 'Islamic Studies';
    public $Category;
    public $Suffix;
    public $Status = 'active';
    public $created;
    public $updated;
    public $Teacher;
    public $Access;
    public $UserID;
    public $UserEmail;
    public $LastLogin;
    public $StudentViewID;
    public $StudentName;
    public $StudentTotalScore = 100;

    public static function Errors($errors)
    {
        if (null == $errors) {
            $errors = array();
        }
        if (!isset($errors['RegistrationYear'])) {
            $errors['RegistrationYear'] = '';
        }
        if (!isset($errors['GradingPeriod'])) {
            $errors['GradingPeriod'] = '';
        }
        if (!isset($errors['ISSGrade'])) {
            $errors['ISSGrade'] = '';
        }
        if (!isset($errors['Subject'])) {
            $errors['Subject'] = '';
        }

        if (!isset($errors['Status'])) {
            $errors['Status'] = '';
        }
        return $errors;
    }
    public static function GetTableFields()
    {
        return array("ClassID", "RegistrationYear", "Suffix", "Category", "ISSGrade", "Subject", "Status");
    }
    public static function GetTableName()
    {
        global $wpdb;
        return $wpdb->prefix . "iss_class";
    }
    public static function GetTeacherClassAccessViewName()
    {
        global $wpdb;
        return $wpdb->prefix . "issv_teacher_class_access";
    }
    public static function GetTeacherNameViewName()
    {
        global $wpdb;
        return $wpdb->prefix . "issv_teacher_name";
    }

    public static function GetStudentClassAccessViewName()
    {
        global $wpdb;
        return $wpdb->prefix . "issv_student_class_access";
    }
    public static function GetClassStudentsViewName()
    {
        global $wpdb;
        return $wpdb->prefix . "issv_class_students";
    }
    public static function GetViewName()
    {
        global $wpdb;
        return $wpdb->prefix . "issv_classes";
    }
    public static function GetSubjectList()
    {
        $isssubjectlist = array(
            'IS' => 'Islamic Studies',
            'QS' => 'Quranic Studies'
        );
        return $isssubjectlist;
    }
    public static function GetClassList()
    {
        $issclasslist = array(
            'kg' => 'Kindergarten',
            '1' => 'Grade 1',
            '2' => 'Grade 2',
            '3' => 'Grade 3',
            '4' => 'Grade 4',
            '5' => 'Grade 5',
            '6' => 'Grade 6',
            '7' => 'Grade 7',
            '8' => 'Grade 8',
            'yb' => 'Youth Boys',
            'yg' => 'Youth Girls',
            'xx' => 'Unknown'
        );
        return $issclasslist;
    }
    public static function Create(array $row)
    {
        $instance = new self();
        $instance->fill($row);
        return $instance;
    }

    protected function fill(array $row)
    {
        // fill all properties from array
        if (is_array($row) && !empty($row)) {
            if (isset($row['ClassID'])) {
                $this->ClassID = $row['ClassID'];
            }
            if (isset($row['GradingPeriodID'])) {
                $this->GradingPeriodID = $row['GradingPeriodID'];
            }
            if (isset($row['RegistrationYear'])) {
                $this->RegistrationYear = $row['RegistrationYear'];
            }
            if (isset($row['GradingPeriod'])) {
                $this->GradingPeriod = $row['GradingPeriod'];
            }
            if (isset($row['ISSGrade']) && isset($row['Subject'])) {
                $this->Name = 'Grade' . $row['ISSGrade'] . '-' . $row['Subject'];
            }
            if (isset($row['Suffix'])) {
                $this->Name = $this->Name . '-' . $row['Suffix'];
            }
            if (isset($row['ISSGrade'])) {
                $this->ISSGrade = $row['ISSGrade'];
            }
            if (isset($row['Teacher'])) {
                $this->Teacher = $row['Teacher'];
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
            if (isset($row['Status'])) {
                $this->Status = $row['Status'];
            }
            if (isset($row['Subject'])) {
                $this->Subject = $row['Subject'];
            }
            if (isset($row['Category'])) {
                $this->Category = $row['Category'];
            }
            if (isset($row['Suffix'])) {
                $this->Suffix = $row['Suffix'];
            }
            if (isset($row['LastLogin'])) {
                $this->LastLogin = $row['LastLogin'];
            }
            if (isset($row['StudentFirstName']) && isset($row['StudentLastName'])) {
                $this->StudentName = $row['StudentFirstName'] . ' ' . $row['StudentLastName'];
            }
            if (isset($row['StudentTotalScore'])) {
                $this->StudentTotalScore = $row['StudentTotalScore'];
            }
            if (isset($row['StudentViewID'])) {
                $this->StudentViewID = $row['StudentViewID'];
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

class ISS_ClassService
{
    public static function error($message)
    {
        iss_write_log("Error ISS_ClassService::" . print_r($message, true));
    }
    public static function debug($message)
    {
        iss_write_log("Debug ISS_ClassService::" . print_r($message, true));
    }
    
    public static function is_teacher_access($cid)
    {
        self::debug("ISS_ClassService::is_teacher_access {$cid}");
        if (ISS_PermissionService::is_user_teacher_role()) {
            $userid = get_current_user_id();
            $accesstable = ISS_Class::GetTeacherClassAccessViewName($cid);
            $query = "SELECT ClassID FROM {$accesstable} WHERE UserID = {$userid} AND ClassID = {$cid}";
            global $wpdb;
            $result_set = $wpdb->get_results($query, ARRAY_A);
            foreach ($result_set as $obj) {
                return true;
            }
        }
        return false;
    }
    public static function is_student_access($cid)
    {
        self::debug("ISS_ClassService::is_student_access {$cid}");
        if (ISS_PermissionService::is_user_parent_role() ||
            ISS_PermissionService::is_user_student_role() ||
            ISS_PermissionService::is_user_teacher_role()) {

            $userid = get_current_user_id();
            $accesstable = ISS_Class::GetStudentClassAccessViewName($cid);
            $query = "SELECT ClassID FROM {$accesstable} WHERE UserID = {$userid} AND ClassID = {$cid}";
            global $wpdb;
            $result_set = $wpdb->get_results($query, ARRAY_A);
            foreach ($result_set as $obj) {
                return true;
            }
        }
        return false;
    }
    /**
     * GetClasss function
     *
     * @return array of ISS_Class Objects
     */
    public static function GetAllClasses()
    {
        self::debug("GetAllClasses");
        $list = array();
        if (ISS_PermissionService::class_list_all_access()) {
            $regyear = iss_registration_period();
            if (empty($regyear)) {
                return $list;
            }
            global $wpdb;
            $itable = ISS_Class::GetTableName();
            $columns = "ClassID, ISSGrade, Subject, Suffix";
            $query = "SELECT {$columns}  FROM {$itable} WHERE Status = 'active' AND RegistrationYear = '{$regyear}' order by ISSGrade, Subject";
            $result_set = $wpdb->get_results($query, ARRAY_A);
            foreach ($result_set as $obj) {
                $list[$obj['ClassID']] = ISS_Class::Create($obj);
            }
        }
        self::PopulatePrimaryTeacher($list);
        return $list;
    }

    public static function GetTeacherClasses()
    {
        self::debug("GetTeacherClasses");
        $list = array();
        if (ISS_PermissionService::class_list_teacher_access()) {
            $regyear = iss_registration_period();
            if (empty($regyear)) {
                return $list;
            }
            global $wpdb;
            $userid = get_current_user_id();
            $columns = "ClassID, ISSGrade, Subject, Suffix";


            $ttable = ISS_Class::GetTeacherClassAccessViewName();
            $query = "SELECT {$columns} FROM {$ttable}  WHERE UserID = {$userid} AND RegistrationYear = '{$regyear}' order by ISSGrade, Subject";
            $result_set = $wpdb->get_results($query, ARRAY_A);
            self::debug("Teacher Access Classes " . $userid);
            self::debug($result_set);

            foreach ($result_set as $obj) {
                $list[$obj['ClassID']] = ISS_Class::Create($obj);
            }
        }
        self::PopulatePrimaryTeacher($list);
        return $list;
        return $list;
    }
    /**
     * GetClasss function
     *
     * @return array of ISS_Class Objects
     */
    public static function GetStudentClasses()
    {
        self::debug("GetStudentClasses");
        $list = array();
        if (ISS_PermissionService::class_list_student_access()) {
            $regyear = iss_registration_period();
            if (empty($regyear)) {
                return $list;
            }
            global $wpdb;
            $userid = get_current_user_id();
            $columns = "StudentViewID, StudentFirstName, StudentLastName, ClassID, ISSGrade, Subject, Suffix";

            $stable = ISS_Class::GetStudentClassAccessViewName();
            $squery = "SELECT {$columns} FROM {$stable} WHERE UserID = {$userid} AND RegistrationYear = '{$regyear}' order by ISSGrade, Subject";
               // . "Group By {$columns}";

            $result_set = $wpdb->get_results($squery, ARRAY_A);
            self::debug("Student Access classes" . $userid);
            self::debug($result_set);

            foreach ($result_set as $obj) {
                $list[] = ISS_Class::Create($obj);
            }
        }
        self::PopulatePrimaryTeacher($list);
        return $list;
    }
    public static function PopulatePrimaryTeacher($list)
    {
        self::debug("PopulatePrimaryTeacher");
        global $wpdb;

        $ptable = ISS_Class::GetTeacherNameViewName();
        $pquery = "SELECT ClassID, Teacher FROM {$ptable} WHERE Access = 'primary'";

        $result_set = $wpdb->get_results($pquery, ARRAY_A);
        self::debug("Teacher Names");
        self::debug($result_set);

        foreach ($result_set as $obj) {
            if (isset($obj['Teacher']) && isset($obj['ClassID'])) {
                $teacher = $obj['Teacher'];
                $cid = $obj['ClassID'];
                foreach ($list as $class) {
                    if ($class->ClassID == $cid) {
                        $class->Teacher = $teacher;
                    }
                }
            }
        }
    }

    /**  MANAGE CLASS  - BEGING **/

    public static function LoadByID($id)
    {
        self::debug("ISS_ClassService::LoadByID {$id}");
        global $wpdb;
        $table = ISS_Class::GetTableName();
        $query = "SELECT *  FROM {$table} where ClassID = {$id}";
        $row = $wpdb->get_row($query, ARRAY_A);
        if (null != $row) {
            //iss_write_log($row);
            return ISS_Class::Create($row);
        }
        return null;
    }

    public static function LoadByUserID($cid, $uid)
    {
        if (ISS_PermissionService::user_manage_access()) {
            self::debug("ISS_ClassService::LoadByUserID ClassId:{$cid} UserID:{$uid}");
            global $wpdb;
            $table = ISS_Class::GetViewName();
            $query = "SELECT *  FROM {$table} where UserID = {$uid} and ClassID = {$cid}";
            $row = $wpdb->get_row($query, ARRAY_A);
            if (null != $row) {
                return ISS_Class::Create($row);
            }
        }
        return null;
    }

    public static function DeleteByID($id)
    {
        try {
            self::debug("ISS_ClassService::DeleteByID {$id}");
            global $wpdb;
            $result = $wpdb->delete(ISS_Class::GetTableName(), array('ClassID' => $id), array("%d"));
            if (false !== $result) {
                return 1;
            }
        } catch (Throwable $ex) {
            self::error($ex->getMessage());
        }
        return 0;
    }

    public static function isValid(array $row, array &$errors)
    {
        $displaynames = iss_field_displaynames();
        $required_fields = array('RegistrationYear', 'ISSGrade', 'Subject');//, 'Status');
        
        foreach ($required_fields as $field) {
            if (!isset($row[$field]) || empty($row[$field])) {
                $errors[$field] = $displaynames[$field] . " is required.";
            } else {
                iss_field_valid($field, $row[$field], $errors, '');
            }
        }
        if (empty($errors)) {
            return true;
        }
        self::error('isValid false');
        self::error($errors);
        return false;
    }

    /**
     *
     *  @param  array of values
     *  @return int 1 if successfully added the record or 0 for fail
     */
    public static function Add($row)
    {
        try {
            self::debug("Add");
            self::debug($row);
            $errors = array();
            if (!self::isValid($row, $errors)) {
                self::error("Add Cannot insert class.");
                return 0;
            }

            $grade = (($row['ISSGrade'] == 'KG') || ($row['ISSGrade'] == 'YG') || ($row['ISSGrade'] == 'YB')) ? $row['ISSGrade'] : 'g' . $row['ISSGrade'];
            $row['Category'] = ($row['Subject'] == 'Islamic Studies') ? $grade . 'is' : $grade . 'qs';

            global $wpdb;
            $table = ISS_Class::GetTableName();
            if (null != $row['ClassID']) {
                $query = "SELECT *  FROM {$table} where ClassID = {$row['ClassID']}";
                $orig = $wpdb->get_row($query, ARRAY_A);
                if (null != $orig) {
                    return self::Update($row);
                }
            }
            $dsarray = array();
            $typearray = array();
            foreach (ISS_Class::GetTableFields() as $field) {
                if (isset($row[$field])) {
                    $dsarray[$field] = $row[$field];
                    $typearray[] = iss_field_type($field);
                }
            }

            $dsarray['created'] = current_time('mysql'); // date('d-m-Y H:i:s');
            $typearray[] = iss_field_type('created');

            self::debug($dsarray);
            $result = $wpdb->insert($table, $dsarray, $typearray);
            if ($result == 1) {
                return 1;
            }
        } catch (Throwable $ex) {
            self::error($ex->getMessage());
        }
        return 0;
    }

    public static function Update(array $row)
    {
        try {
            self::debug("Update");
            self::debug($row);
            $errors = array();
            if (!self::isValid($row, $errors)) {
                self::error("Update Cannot update class.");
                return 0;
            }

            $id = $row['ClassID'];
            $table = ISS_Class::GetTableName();
            $query = "SELECT *  FROM {$table} where ClassID = {$id}";
            global $wpdb;
            $orig = $wpdb->get_row($query, ARRAY_A);
            if (null == $orig) {
                self::error("Update Original class not found {$id}.");
                return 0;
            }

            $update = false;
            $dsarray = array();
            $typearray = array();
            $result = 0;
            foreach (ISS_Class::GetTableFields() as $field) {
                if (isset($row[$field]) && (strcmp($orig[$field], $row[$field]) != 0)) {
                    $update = true;
                    $dsarray[$field] = $row[$field];
                    $typearray[] = iss_field_type($field);
                }
            }

            if ($update) {
                self::debug("class table update");
                iss_write_log($dsarray);

                $result = $wpdb->update($table, $dsarray, array(
                    'ClassID' => $id
                ), $typearray, array(
                    '%d'
                ));
                if (1 === $result) {
                    return 1;
                }
            }
        } catch (Throwable $ex) {
            self::error($ex->getMessage());
        }
        return 0;
    }

    /**  MANAGE CLASS  - END **/


    /**  TEACHER RELATED - BEGIN **/

    /**
     * GetTeacherAccounts  manage function
     * @return  array of ISS_Classß Objects
     */
    public static function GetTeacherAccounts()
    {
        if (ISS_PermissionService::user_manage_access()) {
            self::debug("GetTeacherAccounts");
            $list = null;

            global $wpdb;
            $regyear = iss_registration_period();

            $table = ISS_Class::GetViewName();
            $query = "SELECT  *  FROM {$table} WHERE  RegistrationYear = '{$regyear}' order by  ClassID";

            $result_set = $wpdb->get_results($query, ARRAY_A);
            foreach ($result_set as $obj) {
                try {
                    $list[] = ISS_Class::Create($obj);
                } catch (Throwable $ex) {
                    self::error($ex->getMessage());
                }
            }
        }
        return $list;
    }

    public static function GetAllTeachers()
    {
        self::debug("GetAllTeachers");
        $list = null;

        global $wpdb;
        $regyear = iss_registration_period();

        $user_query = new WP_User_Query(array('role' => 'issteacherrole'));
        $userlist = $user_query->get_results();
        if (!empty($userlist)) {
            foreach ($user_query->get_results() as $user) {
                $list[$user->ID] = ISS_Class::Create(array('Teacher' => $user->display_name, 'UserID' => $user->ID));
            }
        }
        self::debug($list);
        return $list;
    }
    public static function GetClassTeachers($cid)
    {
        self::debug("GetClassTeachers:" . $cid);
        $list = null;
        global $wpdb;
        $table = ISS_Class::GetViewName();
        $query = "SELECT  *  FROM {$table} WHERE  ClassID = {$cid} order by Teacher";
        $result_set = $wpdb->get_results($query, ARRAY_A);
        self::debug($result_set);
        // there should be more than one
        foreach ($result_set as $obj) {
            $list[$obj['UserID']] = ISS_Class::Create($obj);
        }
        return $list;
    }
    public static function LoadPrimaryTeacherAccountByClassID($cid)
    {
        self::debug("LoadPrimaryTeacherAccountByClassID:" . $cid);
        $list = null;
        global $wpdb;
        $table = ISS_Class::GetViewName();
        $query = "SELECT  *  FROM {$table} WHERE  ClassID = '{$cid}' AND Access = 'primary'";
        $result_set = $wpdb->get_results($query, ARRAY_A);

        // there should be more than one
        foreach ($result_set as $obj) {
            return ISS_Class::Create($obj);
        }
        return $list;
    }

}
  /**  TEACHER RELATED - END **/
?>