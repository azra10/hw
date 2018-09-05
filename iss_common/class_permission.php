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
    public static function can_email_teacher()
    {
        return current_user_can('iss_admin') || current_user_can('iss_secretary') || current_user_can('iss_student') || current_user_can('iss_parent');
    }
    public static function can_email_student()
    {
        return current_user_can('iss_admin') || current_user_can('iss_secretary') || current_user_can('iss_teacher');
    }
    public static function can_email_class()
    {
        return current_user_can('iss_admin') || current_user_can('iss_secretary') || current_user_can('iss_teacher');
    }
    public static function can_email_school()
    {
        return current_user_can('iss_admin') || current_user_can('iss_secretary') || current_user_can('iss_board');
    }
    public static function is_user_teacher_role()
    {
        return current_user_can('iss_teacher');
    }
    public static function is_user_parent_role()
    {
        return current_user_can('iss_parent');
    }
    public static function is_user_student_role()
    {
        return current_user_can('iss_student');
    }
    public static function class_student_list_all_access($cid = null)
    {
        if (current_user_can('iss_admin') || current_user_can('iss_board') || current_user_can('iss_secretary')) {
            return true;
        }
        return false;
    }
    public static function class_email_access($cid)
    {
        if (null == $cid) {
            return false;
        }
        return self::class_assignment_write_access($cid);
    }
    public static function class_assignment_write_access($cid)
    {
        if (null == $cid) {
            return false;
        }
        if (current_user_can('iss_admin') || current_user_can('iss_secretary')) return true;
        if (current_user_can('iss_teacher')) {

            $obj = self::LoadByClassID($cid);
            self::debug($obj);
            if (null != $obj) {
                return (($obj->Access === 'write') || ($obj->Access === 'primary'));
            }
        }
        return false;
    }

    public static function LoadByClassID($cid)
    {
        if (null == $cid) {
            return false;
        }
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
        return null;
    }
}

?>
