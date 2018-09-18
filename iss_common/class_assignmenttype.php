<?php
class ISS_AssignmentType
{
    public $AssignmentTypeID;
    public $ClassID;
    public $TypeName;
    public $TypePercentage;
    public $ID;

    public static function GetTableName()
    {
        global $wpdb;
        return $wpdb->prefix . "iss_assignment_type";
    }
    public static function GetTableFields()
    {
        return array("ClassID", "AssignmentTypeID", "TypeName", "TypePercentage");
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
            if (isset($row['ID'])) {
                $this->ID = $row['ID'];
            }
            if (isset($row['ClassID'])) {
                $this->ClassID = $row['ClassID'];
            }
            if (isset($row['AssignmentTypeID'])) {
                $this->AssignmentTypeID = $row['AssignmentTypeID'];
            }
            if (isset($row['TypeName'])) {
                $this->TypeName = $row['TypeName'];
            }
            if (isset($row['TypePercentage'])) {
                $this->TypePercentage = $row['TypePercentage'];
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

class ISS_AssignmentTypeService
{
    public static function error($message)
    {
        iss_write_log("Error ISS_AssignmentTypeService::" . print_r($message, true));
    }
    public static function debug($message)
    {
        iss_write_log("Debug ISS_AssignmentTypeService::" . print_r($message, true));
    }
    public static function GetClassAssignmentTypes($cid)
    {
        self::debug("GetAssignmentTypes cid:" . $cid);
        $list = array();

        if (empty($cid)) {
            return $list;
        }

        $table = ISS_AssignmentType::GetTableName();

        global $wpdb;
        $query = $wpdb->prepare("SELECT * FROM {$table}   WHERE ClassID = %d ORDER BY AssignmentTypeID", $cid);
        $result_set = $wpdb->get_results($query, ARRAY_A);
        self::debug($result_set);
        foreach ($result_set as $obj) {
            $list[] = ISS_AssignmentType::Create($obj);
        }
        return $list;
    }
    public static function AddCategory($cid, $percentage, $name)
    {
        self::debug("AddCategory cid:" . $cid. ",percentage:" . $percentage . ",name:" . $name);
        if ((0 == $cid) || (0 > $percentage) || empty($name)) {
            return 0;
        }
        if (ISS_PermissionService::user_manage_access() || ISS_PermissionService::class_assignment_write_access($cid)) {
            global $wpdb;
            $table = ISS_AssignmentType::GetTableName();
            $result = $wpdb->insert($table, array('TypeName' => $name, 'ClassID' => $cid, 'TypePercentage' => $percentage), array("%s", "%d", "%d"));
            self::debug($result);
            if (1 == $result) {
                return 1;
            }
        }
        return 0;
       
    }
    public static function RemoveCategory($cid, $typeid)
    {
        self::debug("RemoveCategory cid:" . $cid . ",scalid:" . $typeid);
        if (ISS_PermissionService::user_manage_access() || ISS_PermissionService::class_assignment_write_access($cid)) {
            global $wpdb;
            $table = ISS_AssignmentType::GetTableName();
            $assignmenttable = ISS_Assignment::GetTableName();
            $result = $wpdb->update($assignmenttable, array('AssignmentTypeID' => null), array('AssignmentTypeID' => $typeid), array("%s"),  array("%d"));
            $result = $wpdb->delete($table, array('AssignmentTypeID' => $typeid), array("%d"));
            if (1 == $result) {
                return 1;
            }
        }
        return 0;
    }
}

?>