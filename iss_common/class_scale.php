<?php
class ISS_Scale
{
    public $ScaleID;
    public $ClassID;
    public $ScaleName;
    public $ScalePercentage;
    public $ID;

    public static function GetTableName()
    {
        global $wpdb;
        return $wpdb->prefix . "iss_scale";
    }
    public static function GetTableFields()
    {
        return array("ClassID", "ScaleID", "ScaleName", "ScalePercentage");
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
            if (isset($row['ScaleID'])) {
                $this->ScaleID = $row['ScaleID'];
            }
            if (isset($row['ScaleName'])) {
                $this->ScaleName = $row['ScaleName'];
            }
            if (isset($row['ScalePercentage'])) {
                $this->ScalePercentage = $row['ScalePercentage'];
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

class ISS_ScaleService
{
    public static function error($message)
    {
        iss_write_log("Error ISS_ScaleService::" . print_r($message, true));
    }
    public static function debug($message)
    {
        iss_write_log("Debug ISS_ScaleService::" . print_r($message, true));
    }
    public static function GetClassScales($cid)
    {
        self::debug("GetClassScales cid:" . $cid);
        $list = array();

        if (empty($cid)) {
            return $list;
        }

        $table = ISS_Scale::GetTableName();

        global $wpdb;
        $query = $wpdb->prepare("SELECT * FROM {$table}   WHERE ClassID = %d ORDER BY ScalePercentage desc", $cid);
        $result_set = $wpdb->get_results($query, ARRAY_A);
        //self::debug($result_set);
        foreach ($result_set as $obj) {
            $list[] = ISS_Scale::Create($obj);
        }
        return $list;
    }
    public static function AddScale($cid, $percentage, $name)
    {
        self::debug("AddScale cid:" . $cid. ",percentage:" . $percentage . ",name:" . $name);
        if ((0 == $cid) || (0 > $percentage) || empty($name)) {
            return 0;
        }
        if (ISS_PermissionService::user_manage_access() || ISS_PermissionService::class_assignment_write_access($cid)) {
            global $wpdb;
            $table = ISS_Scale::GetTableName();
            $result = $wpdb->insert($table, array('ScaleName' => $name, 'ClassID' => $cid, 'ScalePercentage' => $percentage), array("%s", "%d", "%d"));
            self::debug($result);
            if (1 == $result) {
                return 1;
            }
        }
        return 0;
       
    }
    public static function RemoveScale($cid, $scaleid)
    {
        self::debug("RemoveScale cid:" . $cid . ",scalid:" . $scaleid);
        if (ISS_PermissionService::user_manage_access() || ISS_PermissionService::class_assignment_write_access($cid)) {

            global $wpdb;
            $table = ISS_Scale::GetTableName();
            $result = $wpdb->delete($table, array('ScaleID' => $scaleid), array("%d"));
            if (1 == $result) {
                return 1;
            }
        }
        return 0;
    }
}

?>