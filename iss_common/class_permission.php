<?php
function iss_current_user_can_admin()
{
    return current_user_can('iss_admin');
}
function iss_current_user_on_board()
{
    return current_user_can('iss_board');
}
function iss_current_user_can_editparent()
{
    return current_user_can('iss_secretary');
}
function iss_current_user_can_runtest()
{
    return current_user_can('iss_test');
}
function iss_current_user_can_teach()
{
    return current_user_can('iss_teacher');
}

class ISS_UserClassMap
{
    public $UCMapID;
    public $ClassID;
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
        return $wpdb->prefix . "iss_userclassmap";
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
class ISS_PermissionService
{

    public static function error($message)
    {
        iss_write_log("Error ISS_PermissionService::" . print_r($message, true));
    }
    public static function debug($message)
    {
        iss_write_log("Debug ISS_PermissionService::" . print_r($message, true));
    }
    public static function student_list_all_access()
    {
        return self::class_list_all_access();
    }
    public static function user_manage_access()
    {
        return current_user_can('iss_admin');
    }
    public static function class_list_all_access()
    {
        return current_user_can('iss_admin') || current_user_can('iss_board') || current_user_can('iss_secretary');
    }
    public static function class_student_list_all_access($cid = null)
    {
        if (current_user_can('iss_admin') || current_user_can('iss_board') || current_user_can('iss_secretary')) {
            return true;
        }
        if (current_user_can('iss_teacher')) {
            if (null == $cid) {
                return true;
            }
            $obj = self::LoadByClassID($cid);
            if (null != $obj) {
                return (($obj->Access === 'read') || ($obj->Access === 'write'));
            }
        }
        return false;
    }
    public static function class_assignment_write_access($cid)
    {
        if (current_user_can('iss_admin')) return true;
        if (current_user_can('iss_teacher')) {

            $obj = self::LoadByClassID($cid);
            if (null != $obj) {
                return ($obj->Access === 'write');
            }
        }
        return false;
    }

    public static function LoadByClassID($cid)
    {
        try {
            self::debug("LoadByClassID {$cid}");
            global $wpdb;
            $table = ISS_UserClassMap::GetTableName();
            $userid = get_current_user_id();
            $query = "SELECT *  FROM {$table} where ClassID = {$cid} and UserID = {$userid}";
            global $wpdb;
            $row = $wpdb->get_row($query, ARRAY_A);
            if (null != $row) {
                return ISS_UserClassMap::Create($row);
            }
        } catch (Throwable $ex) {
            self::error($ex->getMessage());
        }
        return null;
    }
}

?>
