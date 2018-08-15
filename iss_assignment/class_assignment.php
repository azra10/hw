<?php
class ISS_Assignment
{
    public $PostID;
    public $ClassID;
    public $PossiblePoints;
    public $DueDate;
    public $Category;
    public $ISSGrade;
    public $Name;
    public $Content;
    public $created;
    public $updated;
		
    public static function GetViewName()
    {
        global $wpdb;
        return $wpdb->prefix . "iss_class_assignments";
    }
    public static function GetTableName()
    {
        global $wpdb;
        return $wpdb->prefix . "iss_assignment";
    }
    public static function GetTableFields()
    {
        return array("ClassID", "Category", "Category","ID", "DueDate", "PossiblePoints");
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
                $this->PostID = $row['ID'];
            }
            if (isset($row['ClassID'])) {
                $this->ClassID = $row['ClassID'];
            }
            if (isset($row['PossiblePoints'])) {
                $this->PossiblePoints = $row['PossiblePoints'];
            }
            if (isset($row['DueDate'])) {
                $this->DueDate = $row['DueDate'];
            }
            if (isset($row['Category'])) {
                $this->Category = $row['Category'];
            }
            
            if (isset($row['post_name'])) {
                $this->Name = $row['post_name'];
            }  
            if (isset($row['post_content'])) {
                $this->Content = $row['post_content'];
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
class ISS_AssignmentService
{
    public static function error($message)
    {
        iss_write_log("Error ISS_AssignmentService::" . print_r($message, true));
    }

    public static function debug($message)
    {
        iss_write_log("Debug ISS_AssignmentService::" . print_r($message, true));
    }

    public static function GetAssignmentCount($cid)
    {
        try {
            self::debug("GetAssignmentCount");
            if (!empty($cid)) {
                $table = ISS_Assignment::GetTableName();
                global $wpdb;
                $query = $wpdb->prepare("SELECT count(ClassID) AS AssignmentCount FROM {$table} 
						WHERE ClassID = %d ", $cid);
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
            $table = ISS_Assignment::GetViewName();
            $query = $wpdb->prepare("SELECT *  FROM {$table} where ID = %d", $id);
            $row = $wpdb->get_row($query, ARRAY_A);
            if (null != $row) {
                return ISS_Assignment::Create($row);
            }
        } catch (Throwable $ex) {
            self::error($ex->getMessage());
        }
        return null;
    }
    /**
     * GetAssignments function
     *
     * @return array of ISS_Assignment Objects
     */
    public static function GetAssignments($cid)
    {
        self::debug("GetAssignments");
            $list = array();

            global $wpdb;
            $table = ISS_Assignment::GetViewName();
            $userid = get_current_user_id();
            $query = "SELECT *  FROM {$table} WHERE   classid = $cid  order by  DueDate";
           
            $result_set = $wpdb->get_results($query, ARRAY_A);
            foreach ($result_set as $obj) {
                try {
                    $list[] = ISS_Assignment::Create($obj);
                } catch (Throwable $ex) {
                    self::error($ex->getMessage());
                }
            }
        
        return $list;
    }
    public static function Add($postid, $classid, $category, $possiblepoints, $duedate)
    {
        try {
            if (null == $postid) return;
            self::debug("Add {$postid}");
           $dsarray = array();
            $dsarray['ID'] = $postid;
            $dsarray['ClassID'] = $classid;
            $dsarray['Category'] = $category;
            $dsarray['PossiblePoints'] = $possiblepoints;
            $dsarray['DueDate'] = $duedate;
            $dsarray['created'] = current_time('mysql'); // date('d-m-Y H:i:s');
            
            $typearray = array('%d', '%d', '%s','%s', '%s', '%s');          

            self::debug($dsarray);

            $table = ISS_Assignment::GetTableName();
            global $wpdb;
            $result = $wpdb->insert($table, $dsarray, $typearray);
            if ($result == 1) {
                return 1;
            }
            
        } catch (Throwable $ex) {
            self::error($ex->getMessage());
        }
        return 0;
    }
    public static function Update($postid, $possiblepoints, $duedate)
    {
        try {
            if (null == $postid) return;
            self::debug("Update {$postid}");
            global $wpdb;
            $table = ISS_Assignment::GetTableName();
            $dsarray = array();
            $dsarray["PossiblePoints"] = $possiblepoints;
            $dsarray["DueDate"] = $duedate;
            $typearray = array( '%s', '%s');
            $result = $wpdb->update($table, $dsarray, array(
                'ID' => $postid
            ), $typearray, array(
                '%d'
            ));
            if (1 === $result) {
                return 1;
            }
            
        } catch (Throwable $ex) {
            self::error($ex->getMessage());
        }
        return 0;
    }
}