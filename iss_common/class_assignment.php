<?php
class ISS_Assignment
{
    public $PostID;
    public $ClassID;
    public $PossiblePoints;
    public $DueDate;
    public $Category;
    public $ISSGrade;
    public $Title;
    public $Content;
    public $created;
    public $updated;
    public $Guid;
    public $AssignmentTypeID;
    public $AssignmentTypeName;
    public $AssignmentTypePercentage;
    public $Graded;

    public static function GetViewName()
    {
        global $wpdb;
        return $wpdb->prefix . "issv_class_assignments";
    }
    public static function GetTableName()
    {
        global $wpdb;
        return $wpdb->prefix . "iss_assignment";
    }
    public static function GetTableFields()
    {
        return array("ClassID", "Category", "Category", "ID", "DueDate", "PossiblePoints");
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
            if (isset($row['ISSGrade'])) {
                $this->ISSGrade = $row['ISSGrade'];
            }
            if (isset($row['Subject'])) {
                $this->Subject = $row['Subject'];
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
            if (isset($row['post_title'])) {
                $this->Title = $row['post_title'];
            }
            if (isset($row['guid'])) {
                $this->Guid = $row['post_title'];
            }
            if (isset($row['post_name'])) {
                $this->Name = $row['post_name'];
            }
            if (isset($row['post_content'])) {
                $this->Content = $row['post_content'];
            }
            if (isset($row['AssignmentTypeID'])) {
                $this->AssignmentTypeID = $row['AssignmentTypeID'];
            }
            if (isset($row['Graded'])) {
                $this->Graded = $row['Graded'];
            }
            if (isset($row['TypeName'])) {
                $this->AssignmentTypeName = $row['TypeName'];
            } else {
                $this->AssignmentTypeName = '(Not Graded)';
            }
            if (isset($row['TypePercentage'])) {
                $this->AssignmentTypePercentage = $row['TypePercentage'];
            } else {
                $this->AssignmentTypePercentage = 0;
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

    public static function LoadAttachmentsByID($id)
    {
        self::debug("LoadAttachmentsByID {$id}");
        global $wpdb;
        $table = $wpdb->prefix . 'posts';
           // SELECT * FROM local_posts where post_type = \'attachment\' and post_parent = 372"
        $query = $wpdb->prepare("SELECT ID, post_title, guid  FROM {$table} where  post_type = 'attachment' and post_parent = %d", $id);
        $result_set = $wpdb->get_results($query, ARRAY_A);

        return $result_set;
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
        $categories = ISS_AssignmentTypeService::GetClassAssignmentTypes($cid);

        global $wpdb;
        $table = ISS_Assignment::GetViewName();
        $userid = get_current_user_id();
        $query = "SELECT *  FROM {$table} WHERE   classid = $cid  order by  DueDate";

        $result_set = $wpdb->get_results($query, ARRAY_A);
        foreach ($result_set as $obj) {
            foreach ($categories as $cat) {
                if ($cat->AssignmentTypeID == $obj['AssignmentTypeID']) {
                    $obj['TypeName'] = $cat->TypeName;
                    $obj['TypePercentage'] = $cat->TypePercentage;
                }
            }
            $list[] = ISS_Assignment::Create($obj);
        }

        return $list;
    }
    public static function DeleteByPostID($postid)
    {
        try {
            self::debug("DeleteByPostID {$postid}");
            global $wpdb;
            $table = ISS_Assignment::GetTableName();
            $result = $wpdb->delete($table, array('ID' => $postid), array("%d"));
            if (0 <= $result) {
                $result1 = wp_delete_post($postid, true);
                if (false !== $result1) {
                    return 1;
                }
            }
        } catch (Throwable $ex) {
            self::error($ex->getMessage());
        }
        return 0;
    }

    public static function DeleteAttachmentByPostID($postid)
    {
        self::debug("DeleteAttachmentByPostID {$postid}");
        global $wpdb;
        $result = wp_delete_attachment($postid, true);
        if (false === $result) {
            return 0;
        }
        return 1;
    }
    public static function Add($postid, $classid, $category, $possiblepoints, $duedate, $assignmenttype)
    {
        if (null == $postid) return;
        self::debug("Add {$postid}");
        $dsarray = array();
        $dsarray['ID'] = $postid;
        $dsarray['ClassID'] = $classid;
        $dsarray['Category'] = $category;
        $dsarray['PossiblePoints'] = $possiblepoints;
        $dsarray['DueDate'] = $duedate;
        $dsarray['created'] = current_time('mysql'); // date('d-m-Y H:i:s');

        $typearray = array('%d', '%d', '%s', '%s', '%s', '%s');
        if (0 < $assignmenttype) {
            $dsarray['AssignmentTypeID'] = $assignmenttype;
            $typearray[] = '%d';
        }

        self::debug($dsarray);

        $table = ISS_Assignment::GetTableName();
        global $wpdb;
        $result = $wpdb->insert($table, $dsarray, $typearray);
        if (0 <= $result) {
            return 1;
        }
        return 0;
    }
    public static function Update($postid, $possiblepoints, $duedate, $assignmenttype)
    {
        if (null == $postid) return;
        self::debug("Update postid: {$postid}");
        global $wpdb;
        $table = ISS_Assignment::GetTableName();
        $dsarray = array();
        $dsarray["PossiblePoints"] = $possiblepoints;
        $dsarray["DueDate"] = $duedate;
           // $dsarray['AssignmentTypeID'] = $assignmenttype;
        $typearray = array('%s', '%s');

        if (0 < $assignmenttype) {
            $dsarray['AssignmentTypeID'] = $assignmenttype;
            $typearray[] = '%d';
        } else {
            $dsarray['AssignmentTypeID'] = null;
            $typearray[] = null;
        }
        self::debug($dsarray);
        $result = $wpdb->update($table, $dsarray, array(
            'ID' => $postid
        ), $typearray, array(
            '%d'
        ));
        if (0 <= $result) {
            return 1;
        }
        return 0;
    }
}