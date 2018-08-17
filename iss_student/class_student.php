<?php
class ISS_Student
{
    public $ParentID;
    public $StudentID;
    public $StudentEmail;
    public $StudentStatus;
    public $StudentFirstName;
    public $StudentLastName;
    public $StudentBirthDate;
    public $StudentGender;
    public $created;
    public $updated;   
    public $RegularSchoolGrade;
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

    public static function GetClassStudentViewName()
    {
        global $wpdb;
        return $wpdb->prefix . "iss_class_students";
    }

    public static function GetViewName()
    {
        global $wpdb;
        return $wpdb->prefix . "iss_students";
    }
     public static function GetTableName()
    {
        global $wpdb;
        return $wpdb->prefix . "iss_student";
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
            if (isset($row['RegularSchoolGrade'])) {
                $this->RegularSchoolGrade = $row['RegularSchoolGrade'];
            }
            if (isset($row['StudentBirthDate'])) {
                $this->StudentBirthDate = $row['StudentBirthDate'];
            }
            if (isset($row['StudentGender'])) {
                $this->StudentGender = $row['StudentGender'];
            }
            if (isset($row['StudentStatus'])) {
                $this->StudentStatus = $row['StudentStatus'];
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
            $query = $wpdb->prepare("SELECT *  FROM {$table} where StudentID = %d", $id);
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
            $table = ISS_Student::GetViewName();
            $query = "SELECT *  FROM {$table} where UserID = {$uid} and StudentID = {$sid}";
            $row = $wpdb->get_row($query, ARRAY_A);
            if (null != $row) {
                return ISS_Student::Create($row);
            }
        } catch (Throwable $ex) {
            self::error($ex->getMessage());
        }
        return null;
    }
    public static function RemoveMapping($sid, $uid){
        try {
            self::debug("RemoveMapping {$sid} UserID:{$uid}");
            global $wpdb;
            $table = ISS_UserStudentMap::GetTableName();
            $result = $wpdb->delete($table, array('UserID' => $uid, 'StudentID'=> $sid), array("%d", "%d"));
            if (1 == $result) {
                return 1;
            }
        } catch (Throwable $ex) {
            self::error($ex->getMessage());
        }
        return 0;
    }
    public static function AddMapping($sid, $uid){
        try {
            self::debug("AddMapping StudentId:{$sid} UserID:{$uid}");
            global $wpdb;
            $table = ISS_UserStudentMap::GetTableName();
            $result = $wpdb->insert($table, array('UserID' => $uid, 'StudentID'=>$sid), array("%d", "%d"));
            if (1 == $result) {
                return 1;
            }
        } catch (Throwable $ex) {
            self::error($ex->getMessage());
        }
        return 0;
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
        if (ISS_PermissionService::class_student_list_all_access($cid)) {
            $table = ISS_Student::GetClassStudentViewName();
            $query = "SELECT *  FROM {$table} WHERE  StudentStatus = 'active' and ClassID = $cid order by  StudentFirstName";
        } 
        else {
            $table = ISS_Student::GetClassStudentViewName();
            $table1 = ISS_UserStudentMap::GetTableName();
            $query = "SELECT *  FROM {$table} WHERE  StudentStatus = 'active' and ClassID = $cid and StudentID in " .
                " (SELECT StudentID FROM {$table1} WHERE UserID = {$userid} )  order by  StudentFirstName";
        }
        $result_set = $wpdb->get_results($query, ARRAY_A);
        foreach ($result_set as $obj) {
            try {
                $list[] = ISS_Student::Create($obj);
            } catch (Throwable $ex) {
                self::error($ex->getMessage());
            }
        }

        return $list;
    }
    /**
     * GetStudents function
     * @return  array of ISS_Student Objects
     */
    public static function GetStudentAccounts()
    {
        self::debug("GetStudentAccounts");
        $list = array();

        global $wpdb;
        $regyear = iss_registration_period();
       
        if (ISS_PermissionService::user_manage_access()) {
            $table = ISS_Student::GetViewName();
            $query = "SELECT  *  FROM {$table} WHERE  RegistrationYear = '{$regyear}' order by  StudentFirstName";

            $result_set = $wpdb->get_results($query, ARRAY_A);
            foreach ($result_set as $obj) {
                try {
                    $list[] = ISS_Student::Create($obj);
                } catch (Throwable $ex) {
                    self::error($ex->getMessage());
                }
            }          
                    
            return $list;

        }
        return null;
    }
}
?>