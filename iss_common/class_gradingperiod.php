<?php
class ISS_GradingPeriod
{

    public $GradingPeriodID;
    public $RegistrationYear;
    public $GradingPeriod;
    public $StartDate;
    public $EndDate;
    public $created;
    public $updated;

    public static function GetTableFields() {
        return array (
                "GradingPeriodID",
                "RegistrationYear",
                "GradingPeriod",
                "StartDate",
                "EndDate" 
        );
    }
    public static function GetTableName()
    {
        global $wpdb;
        return $wpdb->prefix . "iss_grading_period";
    }
    public static function Errors()
    {
        $errors =  array();
        $errors['RegistrationYear'] = '';
        $errors['GradingPeriod'] = '';
        $errors['StartDate'] = '';
        $errors['EndDate'] = '';
        return $errors;
    }
    public static function Create(array $row)
    {
        $instance = new self();
        $instance->fill( $row );
        return $instance;
    }

    protected function fill(array $row)
    {
        // fill all properties from array
        if (is_array($row) && !empty($row)) {
            if (isset($row['GradingPeriodID'])) {
                $this->GradingPeriodID = $row['GradingPeriodID'];
            }
            if (isset($row['RegistrationYear'])) {
                $this->RegistrationYear = $row['RegistrationYear'];
            }
            if (isset($row['GradingPeriod'])) {
                $this->GradingPeriod = $row['GradingPeriod'];
            }
            if (isset($row['StartDate'])) {
                $this->StartDate = $row['StartDate'];
            }
            if (isset($row['EndDate'])) {
                $this->EndDate = $row['EndDate'];
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

class ISS_GradingPeriodService
{
    public static function error($message)
    {
        iss_write_log("Error ISS_GradingPeriodService::" . print_r($message, true));
    }
    public static function debug($message)
    {
        iss_write_log("Debug ISS_GradingPeriodService::" . print_r($message, true));
    }
    public static function GetRegistrationYears()
    {
        self::debug("GetRegistrationYears");
        $list = array();
         
        global $wpdb;
        $table =  ISS_GradingPeriod::GetTableName();
        $query = "SELECT distinct(RegistrationYear) FROM {$table} order by  RegistrationYear";
        $result_set = $wpdb->get_results ( $query, ARRAY_A );
        
        // if (null != $result_set){
        //     foreach($result_set as $regyear)
        //     { $list[] = $regyear['RegistrationYear']; }
        // }
        return $result_set;
    }
    public static function GetGradingPeriods()
    {
        self::debug("GetGradingPeriods");
        
        $list = array();
         
        global $wpdb;
        $table =  ISS_GradingPeriod::GetTableName();
        $query = "SELECT *  FROM {$table}";
        $result_set = $wpdb->get_results ( $query, ARRAY_A );
        
        foreach ($result_set as $obj) {
            try {
                 $list[] =  ISS_GradingPeriod::Create($obj);
            } catch (Throwable $ex) {
                self::error($ex->getMessage());
            }
        }
        return $list;
    }
    public static function LoadByID($id)
    {
        try {
            self::debug("LoadByID {$id}");
            global $wpdb;
            $table =  ISS_GradingPeriod::GetTableName();
            $query = "SELECT *  FROM {$table} where GradingPeriodID = {$id}";
            $row = $wpdb->get_row ( $query, ARRAY_A );
            if (null != $row){
                return ISS_GradingPeriod::Create( $row );
            }
        } catch (Throwable $ex) {
            self::error($ex->getMessage());
        }
        return null;
    }
    public static function Load(string $registrationyear, int $gradingperiod)
    {
        try {
            self::debug("Load {$registrationyear}  {$gradingperiod}");
            
            global $wpdb;
            $table =  ISS_GradingPeriod::GetTableName();
            $query = "SELECT *  FROM {$table} where RegistrationYear = '{$registrationyear}' and GradingPeriod = {$gradingperiod}";
            $row = $wpdb->get_row ( $query, ARRAY_A );
            if (null != $row)
            { return ISS_GradingPeriod::Create( $row ); }
        } catch (Throwable $ex) {
            self::error($ex->getMessage());
        }
        return null;
    }
    public static function DeleteByID($id)
    {
        try {
            self::debug("DeleteByID {$id}");
            $gradingperiod = self::LoadByID($id);
            //$count = ISS_ParentService::GetParentCount($gradingperiod->RegistrationYear);
            //if ($count == 0) {
                global $wpdb;
                $result = $wpdb->delete( ISS_GradingPeriod::GetTableName(), array( 'GradingPeriodID' => $id ), array( "%d" ) );
                if (1 == $result) {
                    return 1;
                }
            //}
        } catch (Throwable $ex) {
            self::error($ex->getMessage());
        }
        return 0;
    }
    public static function Delete(string $registrationyear, int $gradingperiod)
    {
        try {
            self::debug("Delete {$registrationyear}  {$gradingperiod}");
           // $count = ISS_ParentService::GetParentCount($registrationyear);
           // if ($count == 0) {
                global $wpdb;
                $result = $wpdb->delete ( ISS_GradingPeriod::GetTableName(),
                array( 'RegistrationYear' => $registrationyear, 'GradingPeriod' => $gradingperiod ),
                array( "%s", "%d" ) );
                
                if (1 == $result) {
                    return 1;
                }
          //  }
        } catch (Throwable $ex) {
            self::error($ex->getMessage());
        }
        return 0;
    }
    public static function isValid(array $row, array &$errors)
    {
        
        $displaynames = iss_field_displaynames ();
        $required_fields = array('GradingPeriod', 'RegistrationYear', 'StartDate', 'EndDate');
        foreach ($required_fields as $field) {
            if (! isset ( $row [$field] ) || empty ( $row [$field] )) {
                $errors [$field] = $displaynames [$field] . " is required.";
            } else {
                iss_field_valid($field, $row [$field], $errors, '');
            }
        }
        if (empty($errors)) {
            return true;
        }
        self::error('isValid false');
        self::error($errors);
        return false;
    }
    public static function AddWithDefaultDate(string $registrationyear, int $gradingperiod)
    {
        try {
            self::debug("AddWithDefaultDate {$registrationyear}  {$gradingperiod}");
            if (ISS_Validate::check_registrationyear_string($registrationyear) && 
                ISS_Validate::check_int_string($gradingperiod))
            {
                $row = self::Load($registrationyear, $gradingperiod);
                if (null != $row) 
                {
                    self::debug("Grading Period Exists");self::debug($row);
                    return $row;
                }
                $row = array();
                $row['RegistrationYear'] = $registrationyear;
                $row['GradingPeriod'] = $gradingperiod;
                $list = explode ( "-", $registrationyear );
                if (1 == $gradingperiod)
                {
                    $row['StartDate'] = $list[0] . '-06-15';
                    $row['EndDate'] =  $list[0] . '-12-31';
                } else {
                    $row['StartDate'] = $list[1] . '-01-01';
                    $row['EndDate'] =  $list[1] . '-06-14';
                } 
                $result = self::Add($row);
                if (1 == $result)
                {
                    $row = self::Load($registrationyear, $gradingperiod);               
                    if (null != $row) 
                    {
                        self::debug("Grading Period Added");self::debug($row);
                        return $row;
                    }
                }
            }
        } catch (Throwable $ex) {
            self::error($ex->getMessage());
        }
        return null;
    }
    /**
    *
    *  @param  array of values
    *  @return int 1 if successfully added the record or 0 for fail
    */
    public static function Add(array $row)
    {
        try {
            self::debug("Add"); self::debug($row);
            $errors = array();
            if (!self::isValid($row, $errors)) {
                self::error ( "Add Cannot insert grading period." );
                return 0;
            }

            $dsarray = array ();
            $typearray = array ();
            $changelog = array ();
            foreach (ISS_GradingPeriod::GetTableFields() as $field) {
                if (isset ( $row [$field] )) {
                    $dsarray [$field] = $row [$field];
                    $typearray [] = iss_field_type ( $field );
                    //$changelog [] = iss_create_changelog ( null, null, $field, $row [$field] );
                }
            }
            $dsarray ['created'] = current_time ( 'mysql' ); // date('d-m-Y H:i:s');
            $typearray [] = iss_field_type ( 'created' );
            
            self::debug ( $dsarray );

            $table =  ISS_GradingPeriod::GetTableName();
            global $wpdb;
            $result = $wpdb->insert ( $table, $dsarray, $typearray );
            if ($result == 1) {
                //iss_changelog_insert ( $table, $changelog );
                return 1;
            }
        } catch (Throwable $ex) {
            self::error($ex->getMessage());
        }
        return 0;
    }

    public static function  Update(array $row) {
        try {
            self::debug("Update"); self::debug($row);
            $errors = array();
            if (!self::isValid($row, $errors)) {
                self::error ( "Update Cannot update grading period." );
                return 0;
            }
            
            $id = $row['GradingPeriodID'];
            $table =  ISS_GradingPeriod::GetTableName();
            $query = "SELECT *  FROM {$table} where GradingPeriodID = {$id}";
            global $wpdb;
            $orig = $wpdb->get_row ( $query, ARRAY_A );
            if (null == $orig) {
                self::error ( "Update Original grading period not found {$id}." );
                return 0;
            }
            $update = false;
            $dsarray = array ();
            $typearray = array ();
            $result = 0;
            foreach ( ISS_GradingPeriod::GetTableFields() as $field ) {
                if (isset($row [$field] ) && (strcmp ($orig[$field], $row [$field] ) != 0)) {
                    $update = true;
                    $dsarray [$field] = $row [$field];
                    $typearray [] = iss_field_type ( $field );
                }
            }
            if ($update) {
                self::debug ( "grading period table update" );
                iss_write_log ( $dsarray );
                
                $result = $wpdb->update ( $table, $dsarray, array (
                        'GradingPeriodID' => $id 
                ), $typearray, array (
                        '%d' 
                ) );
                if (1 === $result) {
                    return 1;
                }
            }
           
        } catch ( Throwable $ex ) {
            self::error ( $ex->getMessage () );
        }
        return 0;
    }
}

?>