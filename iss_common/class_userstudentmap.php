<?php
class ISS_UserStudentMap
{
    public $USMapID;
    public $StudentD;
    public $GradingPeriod;
    public $RegistrationYear;
    public $ISSGrade;
    public $Subject;
    public $Category;
    public $Status;
    public $UserID;
    public $Access;
    public $created;
    public $updated;

    public static function GetTableName()
    {
        global $wpdb;
        return $wpdb->prefix . "iss_userstudentmap";
    }
    public static function GetLastLoginTableName()
    {
        global $wpdb;
        return $wpdb->prefix . "issv_student_lastlogin";
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

            if (isset($row['UCMapID'])) {
                $this->UCMapID = $row['UCMapID'];
            }
            if (isset($row['ClassID'])) {
                $this->ClassID = $row['ClassID'];
            }
            if (isset($row['RegistrationYear'])) {
                $this->RegistrationYear = $row['RegistrationYear'];
            }
            if (isset($row['ISSGrade'])) {
                $this->ISSGrade = $row['ISSGrade'];
            }
            if (isset($row['Subject'])) {
                $this->Subject = $row['Subject'];
            }
            if (isset($row['ISSGrade'])) {
                $this->ISSGrade = $row['ISSGrade'];
            }
            if (isset($row['Category'])) {
                $this->Category = $row['Category'];
            }
            if (isset($row['Status'])) {
                $this->Status = $row['Status'];
            }
            if (isset($row['UserID'])) {
                $this->UserID = $row['UserID'];
            }
            if (isset($row['Access'])) {
                $this->Access = $row['Access'];
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
class ISS_UserStudentMapService
{
    public static function error($message)
    {
        iss_write_log("Error ISS_UserStudentMapService::" . print_r($message, true));
    }

    public static function debug($message)
    {
        iss_write_log("Debug ISS_StudentService::" . print_r($message, true));
    }

    public static function UpdateLasLogin($user_login, $user_id)
    {
        self::debug("UpdateLasLogin username:{$user_login} userid:{$user_id}");
        $table = ISS_UserStudentMap::GetTableName();
        global $wpdb;
        $time = current_time('mysql');
        $result = $wpdb->update($table, array('LastLogin' => $time), array('UserID' => $user_id), array("%s"), array('%d'));
        if ($result) {
            self::debug("Updated LasLogin {$user_id}  {$time}");
        }

    }
    public static function RemoveMapping($sid, $uid)
    {
        self::debug("RemoveMapping {$sid} UserID:{$uid}");
        global $wpdb;
        $table = ISS_UserStudentMap::GetTableName();
        $result = $wpdb->delete($table, array('UserID' => $uid, 'StudentID' => $sid), array("%d", "%d"));
        if (1 == $result) {
            return 1;
        }

        return 0;
    }
    public static function AddMapping($sid, $uid)
    {
        self::debug("AddMapping StudentId:{$sid} UserID:{$uid}");
        if ((0 == $sid) || (0 == $uid)) {
            return 0;
        }
        if (ISS_PermissionService::user_manage_access()) {
            global $wpdb;
            $table = ISS_UserStudentMap::GetTableName();
            $query = "SELECT *  FROM {$table} where  UserID = {$uid} and StudentID = {$sid}";
            $row = $wpdb->get_row($query, ARRAY_A);
            if (null != $row) {
                self::debug('Mapping already exists');
                return 0;
            }
            $result = $wpdb->insert($table, array('UserID' => $uid, 'StudentID' => $sid), array("%d", "%d"));
            if (1 == $result) {
                return 1;
            }
        }
        return 0;
    }
    public static function RemoveUserMappings($uid)
    {
        if (ISS_PermissionService::user_manage_access()) {
            self::debug(" RemoveUserMappings  UserID:{$uid}");
            global $wpdb;
            $table = ISS_UserStudentMap::GetTableName();
            $result = $wpdb->delete($table, array('UserID' => $uid), array("%d"));
            if (1 == $result) {
                return 1;
            }
        }
        return 0;
    }
}
?>