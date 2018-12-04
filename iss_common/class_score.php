<?php
class ISS_Score
{
    public $StudentViewID;
    public $AssignmentID;
    public $Score = '';
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
                 {$this->Score = (null != $row['Score'])? floatval($row['Score']): $row['Score'];}                           
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
            //self::debug($result_set);
            foreach ($result_set as $obj) {
                $list[] = ISS_Score::Create($obj);
            }
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
            $query = "SELECT StudentViewID, StudentFirstName, StudentLastName, AssignmentID, AssignmentTypeID, DueDate, PossiblePoints, Title, Score, Comment 
            FROM {$table} WHERE  StudentViewID = {$svid} AND ClassID = {$cid} AND AssignmentTypeID IS NOT NULL ORDER BY AssignmentTypeID, DueDate ";
            $result_set = $wpdb->get_results($query, ARRAY_A);
            //self::debug($result_set);
            foreach ($result_set as $obj) {
                $tid = $obj['AssignmentTypeID'];
                $obj['TypeName'] =$typelist[$tid]->TypeName;
                $obj['TypePercentage'] =$typelist[$tid]->TypePercentage;
                $list[] = ISS_Score::Create($obj);
            }

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
    public static function SaveClassScores($scores, $cid){
        self::debug("SaveClassScores");
        //self::debug($scores);
        global $wpdb;
        $failed = false;
        if (ISS_PermissionService::class_assignment_write_access($cid)) {
            $vtable = ISS_Score::GetViewName();
            $query = "SELECT AssignmentID, StudentViewID, Score, Comment  FROM {$vtable} WHERE ClassID = {$cid} ";
            $result_set = $wpdb->get_results($query, ARRAY_A);
            $failed = self::SaveScores($result_set, $scores);           
        }
        return $failed ? 0 : 1;
    }
    public static function SaveStudentScores($scores, $svid, $cid){
        self::debug("SaveStudentScores");
        //self::debug($scores);
        global $wpdb;
        $failed = false;
        if (ISS_PermissionService::class_assignment_write_access($cid)) {
            $vtable = ISS_Score::GetViewName();
            $query = "SELECT AssignmentID, StudentViewID, Score, Comment  FROM {$vtable} WHERE ClassID = {$cid} AND  StudentViewID = {$svid}";
            $result_set = $wpdb->get_results($query, ARRAY_A);
            $failed = self::SaveScores($result_set, $scores);   
        }
        return $failed ? 0 : 1;
    }
    public static function SaveAssignmentScores($scores, $postid, $cid)
    {
        self::debug("SaveAssignmentScores");
        //self::debug($scores);
        global $wpdb;
        $failed = false;
        if (ISS_PermissionService::class_assignment_write_access($cid)) {
            $vtable = ISS_Score::GetViewName();
            $query = "SELECT AssignmentID, StudentViewID, Score, Comment  FROM {$vtable} WHERE ClassID = {$cid} AND  AssignmentID = {$postid}";
            $result_set = $wpdb->get_results($query, ARRAY_A);
            $failed = self::SaveScores($result_set, $scores);   
        }
        return $failed ? 0 : 1;
    }

    public static function SaveScores($result_set, $scores)
    {
        global $wpdb;
        $table = ISS_Score::GetTableName();
        $failed = 0; 
        foreach ($result_set as $obj) 
        {
            $aid = $obj['AssignmentID'];
            $svid = $obj['StudentViewID'];           
            $dataArray= array(); 
            $typeArray = array();
            if (isset($scores["score" . $svid . '-' . $aid])) 
            {
                $score = $scores["score" . $svid . '-' . $aid];
                if ($score == "E") {   $score = -1; } else if ($score == "M") { $score = -2; } 
                $score = floatval($score);
                if ($score !=  $obj['Score'])
                {  
                    $dataArray['Score'] = $score;  
                    $typeArray[] = '%f';
                }           
            }
            
            if (isset($scores["comment" . $svid . '-' . $aid])) 
            {
                $comment = $scores["comment" . $svid . '-' . $aid];
                if ($comment !=  $obj['Comment'])
                {
                    $dataArray['Comment'] = $comment;
                    $typeArray[] = '%s';
                }
            }
             if (!empty($dataArray))
            {          
                if (!isset($dataArray['Score'])) 
                { 
                    $dataArray['Score'] = (null == $obj['Score']) ? 0: $obj['Score']; 
                    $typeArray[] = '%f'; 
                }
                if (!isset($dataArray['Comment'])) 
                { 
                    $dataArray['Comment'] = $obj['Comment']; 
                    $typeArray[] = '%s';
                }
                $dataArray['StudentViewID'] = $svid; $typeArray[] = '%d'; 
                $dataArray['AssignmentID'] = $aid; $typeArray[] = '%d';
                $result = $wpdb->replace($table, $dataArray, $typeArray);
                if (false === $result) 
                { $failed++; }
                ISS_ScoreService::MarkAssignmentGraded($aid);         
            }                       

        }          
        return $failed>0;
    }
    public static function MarkAssignmentGraded($aid)
    {
        self::debug("MarkAssignmentGraded: {$aid}");
        global $wpdb;
        $table = ISS_Assignment::GetTableName();      
        $wpdb->update($table, array('Graded'=> 1), array( 'ID' => $aid ), array('%d'), array('%d'));
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
        $query1 = "SELECT StudentViewID, AssignmentID, AssignmentTypeID, PossiblePoints, DueDate, Score, Comment, Title, StudentFirstName, StudentLastName FROM {$table1} WHERE ClassID = {$cid} AND AssignmentTypeID IS NOT NULL ORDER BY AssignmentTypeID, DueDate, StudentFirstName";
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
                $list['Scores'][$sid . '-' . $aid]['score'] = (null != $obj['Score'])?  floatval($obj['Score']): '';
                $list['Scores'][$sid . '-' . $aid]['comment'] = $obj['Comment'];
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
    public static function GetClassAssignmentTypeScores($cid)
    {
        self::debug("GetClassAssignmentTypeScores");
        $list = array();
            
        global $wpdb;
        if (ISS_PermissionService::class_assignment_write_access($cid)) {
            $typelist = ISS_AssignmentTypeService::GetClassAssignmentTypes($cid);
            
            $table2= ISS_Score::GetViewNameScoresByAssignmentType();
            $query1 = "SELECT StudentViewID, AssignmentTypeID, TypeGrade FROM {$table2} WHERE ClassID = {$cid} AND AssignmentTypeID IS NOT NULL ORDER BY StudentViewID";
            $query2 = "SELECT StudentViewID, SUM( TypeGrade) AS Total FROM {$table2} WHERE ClassID = {$cid} AND StudentViewID IS NOT NULL GROUP BY StudentViewID";
    
            $result_set = $wpdb->get_results($query1, ARRAY_A);   
            foreach ($result_set as $obj) {
                $sid = $obj['StudentViewID'];
                $atypeid = $obj['AssignmentTypeID'];             
                $list['Scores'][$sid][$atypeid] = floatval($obj['TypeGrade']);               
            }
                       
            $scale_list =ISS_ScaleService::GetClassScales($cid);            
            foreach ($scale_list as $obj) {
                $list['Scale']['AssignmentTypeId'] = $obj['AssignmentTypeID']; 
                $list['Scale']['TypeName'] = $obj['TypeName'];
                $list['Scale']['TypePercentage'] = floatval($obj['TypePercentage']);
            }
            $result_set = $wpdb->get_results($query2, ARRAY_A);   
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
}