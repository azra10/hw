<?php
class ISS_Score
{
    public $StudentViewID;
    public $AssignmentID;
    public $Score = 0;
    public $StudentFirstName;
    public $StudentLastName;
    public $ISSGrade;
    public $ClassID;
    public $Title;
    public $PossiblePoints;
    public $DueDate;
    public $Subject;
    public $Comment;
    public $AssignmentTypeID;
    public $AssignmentTypeName;
    public $AssignmentTypePercentage;
    
    public static function GetTableFields()
    {
        return array("StudentViewID", "AssignmentID", "Score");
    }
    public static function GetTableName()
    {
        global $wpdb;
        return $wpdb->prefix . "iss_score";
    }
    public static function GetViewName()
    {
        global $wpdb;
        return $wpdb->prefix . "issv_student_scores";
    }
    public static function GetViewNameScoresByAssignmentType()
    {
        global $wpdb;
        return $wpdb->prefix . "issv_student_score_byassignmenttype";
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
            if (isset($row['StudentViewID'])) {
                $this->StudentViewID = $row['StudentViewID'];
            }
            if (isset($row['AssignmentID'])) {
                $this->AssignmentID = $row['AssignmentID'];
            }
            if (isset($row['Score'])) {
                $this->Score = $row['Score'];
                if ($this->Score == -1) { $this->Score = 'E';}
                else if ($this->Score == -2) { $this->Score = 'M';}                             
            }
            if (isset($row['Comment'])) {
                $this->Comment = $row['Comment'];
            }

            if (isset($row['StudentFirstName'])) {
                $this->StudentFirstName = $row['StudentFirstName'];
            }
            if (isset($row['StudentLastName'])) {
                $this->StudentLastName = $row['StudentLastName'];
            }
            if (isset($row['ClassID'])) {
                $this->ClassID = $row['ClassID'];
            }
            if (isset($row['Subject'])) {
                $this->Subject = $row['Subject'];
            }
            if (isset($row['Title'])) {
                $this->Title = $row['Title'];
            }
            if (isset($row['PossiblePoints'])) {
                $this->PossiblePoints = $row['PossiblePoints'];
            }
            if (isset($row['DueDate'])) {
                $this->DueDate = $row['DueDate'];
            }
            if (isset($row['ISSGrade'])) {
                $this->ISSGrade = $row['ISSGrade'];
            }
            if (isset($row['AssignmentTypeID'])) {
                $this->AssignmentTypeID = $row['AssignmentTypeID'];
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

class ISS_ScoreService
{
    public static function error($message)
    {
        iss_write_log("Error ISS_ScoreService::" . print_r($message, true));
    }
    public static function debug($message)
    {
        iss_write_log("Debug ISS_ScoreService::" . print_r($message, true));
    }
    public static function LoadScoreByAssignmentID($postid, $cid)
    {
        self::debug("LoadScoreByAssignmentID: " . $postid);
        $list = array();
 
        global $wpdb;

        if (ISS_PermissionService::class_assignment_write_access($cid)) {
            $table = ISS_Score::GetViewName();
            $query = "SELECT StudentViewID, StudentFirstName, StudentLastName, AssignmentID, Score, Comment FROM {$table} WHERE  AssignmentID = {$postid} ORDER BY StudentFirstName ";
            $result_set = $wpdb->get_results($query, ARRAY_A);
            foreach ($result_set as $obj) {
                $list[] = ISS_Score::Create($obj);
            }
            iss_write_log($list);
 
            return $list;
        }
        return null;
    }
    public static function LoadScoreByStudentID($svid, $cid)
    {
        self::debug("LoadScoreByStudentID: {$svid} Class: {$cid}");
        $list = array();
 
        global $wpdb;

        if (ISS_PermissionService::class_assignment_write_access($cid)) {
            $typelist = ISS_AssignmentTypeService::GetClassAssignmentTypes($cid);
            
            $table = ISS_Score::GetViewName();
            $query = "SELECT StudentViewID, StudentFirstName, StudentLastName, AssignmentID, AssignmentTypeID, PossiblePoints, Title, Score, Comment 
            FROM {$table} WHERE  StudentViewID = {$svid} AND ClassID = {$cid} AND AssignmentTypeID IS NOT NULL ORDER BY AssignmentTypeID ";
            $result_set = $wpdb->get_results($query, ARRAY_A);
            self::debug($result_set);
            foreach ($result_set as $obj) {
                $tid = $obj['AssignmentTypeID'];
                $obj['TypeName'] =$typelist[$tid]->TypeName;
                $obj['TypePercentage'] =$typelist[$tid]->TypePercentage;
                $list[] = ISS_Score::Create($obj);
            }
            iss_write_log($list);
 
            return $list;
        }
        return null;
    }
    public static function DeleteScores($postid, $cid)
    {
        self::debug("DeleteScores");
       
        global $wpdb;
        if (ISS_PermissionService::class_assignment_write_access($cid)) {
            $table = ISS_Score::GetTableName();

            $result = $wpdb->delete($table, array('AssignmentID' => $postid), array("%d"));
            self::debug($result);
            if (0 <= $result) {
                return 1;
            }
        }
        return 0;
    }
    public static function SaveScores($scores, $postid, $cid)
    {
        self::debug("SaveScores");
        self::debug($scores);

        global $wpdb;
        if (ISS_PermissionService::class_assignment_write_access($cid)) {
            $table = ISS_Score::GetTableName();

            $list = array();
            $query = "SELECT StudentViewID  FROM {$table} WHERE  AssignmentID = {$postid}";
            $result_set = $wpdb->get_results($query, ARRAY_A);
            foreach ($result_set as $obj) {
                $sid = $obj['StudentViewID'];
                $list[$sid] = $sid;
            }
            $failed = false;
            foreach ($scores as $student) {
                if (isset($list[$student->StudentViewID])) {
                    // Update
                    $result = $wpdb->update(
                        $table,
                        array('Score' => $student->Score, 'Comment' => $student->Comment),
                        array('StudentViewID' => $student->StudentViewID, 'AssignmentID' => $student->AssignmentID),
                        array('%d', '%s'),
                        array('%d', '%d')
                    );
                } else {
                    // Insert
                    $result = $wpdb->insert(
                        $table,
                        array('StudentViewID' => $student->StudentViewID, 'AssignmentID' => $student->AssignmentID, 'Score' => $student->Score, 'Comment' => $student->Comment),
                        array('%d', '%d', '%d', '%s')
                    );
                }
                if (false === $result) {
                    $failed = true;
                }
            }
            return $failed ? 0 : 1;
        }
        return 0;
    }
    public static function GetStudentAssignmentScores($cid, $sid, $svid)
    {
        self::debug("GetStudentAssignmentScores");
        $list = array();
        $categories = ISS_AssignmentTypeService::GetClassAssignmentTypes($cid);
 
        global $wpdb;
        if (ISS_PermissionService::student_socre_access($sid)) {
            $table = ISS_Score::GetViewName();
            $query = "SELECT AssignmentID,Title,Score,PossiblePoints, DueDate, Comment, AssignmentTypeID FROM {$table} WHERE   classid = $cid  and StudentViewID = {$svid} order by  DueDate";

            $result_set = $wpdb->get_results($query, ARRAY_A);
            //self::debug($result_set);
            foreach ($result_set as $obj) {
                foreach($categories as $cat) { 
                    if ($cat->AssignmentTypeID == $obj['AssignmentTypeID']) {
                        $obj['TypeName'] = $cat->TypeName;
                        $obj['TypePercentage'] = $cat->TypePercentage;
                    }
                }
                $list[] = ISS_Score::Create($obj);
            }

            return $list;
        }
        return null;
    }
    public static function GetClassAssignmentScores($cid)
    {
        self::debug("GetClassAssignmentScores");
        $list = array();
        $table1 = ISS_Score::GetViewName();
        $query1 = "SELECT StudentViewID, AssignmentID, AssignmentTypeID, PossiblePoints, DueDate, Score, Title, StudentFirstName, StudentLastName FROM {$table1} WHERE ClassID = {$cid} AND AssignmentTypeID IS NOT NULL ORDER BY AssignmentTypeID, DueDate, StudentFirstName";
        $table2= ISS_Score::GetViewNameScoresByAssignmentType();
        $query2 = "SELECT StudentViewID, SUM( TypeGrade) AS Total FROM {$table2} WHERE ClassID = {$cid} AND StudentViewID IS NOT NULL GROUP BY StudentViewID";

        global $wpdb;
        if (ISS_PermissionService::class_assignment_write_access($cid)) {
            $typelist = ISS_AssignmentTypeService::GetClassAssignmentTypes($cid);
            
            $result_set = $wpdb->get_results($query1, ARRAY_A);   
            foreach ($result_set as $obj) {
                $sid = $obj['StudentViewID'];
                $aid = $obj['AssignmentID'];
                $atypeid = $obj['AssignmentTypeID'];
                $list['Assignments'][$aid] = array('AssignmentID'=>$obj['AssignmentID'], 'Title'=> $obj['Title'], 'DueDate'=>$obj['DueDate'], 'PossiblePoints'=>$obj['PossiblePoints']);
                $list['Assignments'][$aid]['TypeName'] = isset( $typelist[$atypeid])? $typelist[$atypeid]->TypeName: '(Not Graded)';
                $list['Students'][$sid] = array('StudentViewID'=>$obj['StudentViewID'], 'StudentFirstName'=> $obj['StudentFirstName'], 'StudentLastName'=>$obj['StudentLastName']);
                $list['Scores'][$sid . $aid] = $obj['Score'];
            }
            $result_set = $wpdb->get_results($query2, ARRAY_A);   
                 
            $scale_list =ISS_ScaleService::GetClassScales($cid);
            foreach ($result_set as $obj) {
                $sid = $obj['StudentViewID'];
                if (isset($list['Students'][$sid]))
                {
                    $list['Students'][$sid]['Total'] = isset($obj['Total'])? ceil($obj['Total']):0;
                    $list['Students'][$sid]['Scale'] = self::GetScale($scale_list,$obj['Total']);
                }
            }
        
        return $list;
        }
        return null;
    }
    public static function GetScale($scale_list,$total)
    {
        foreach($scale_list as $obj) {
            if ($total >= $obj->ScalePercentage) return $obj->ScaleName;
        }
        return '';
    }
}