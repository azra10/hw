<?php
/*
 * Plugin Name: 00. ISS Prepare
 * 
 * Description: Adds a page to prepare the site for testing
 * Version: 1.0
 * Author: Azra Syed
 * 
 */

class ISS_PreparationPlugin
{
    private $submit = "none";
    private $stamp;
    private $prefix;
   // tables
    private $iss_grading_period;
    private $iss_class;
    private $iss_student;
    private $iss_assignment_type;
    private $iss_userclassmap;
    private $iss_assignment;
    private $iss_score;
    private $iss_scale;
    private $iss_userstudentmap;
    private $iss_users;
    private $iss_posts;

  // views
    private $issv_classes;
    private $issv_class_assignments;
    private $issv_class_students;
    private $issv_student_accounts;
    private $issv_student_class_access;
    private $issv_student_lastlogin;
    private $issv_student_scores;
    private $issv_student_score_byassignmenttype;
    private $issv_teacher_class_access;
    private $issv_teacher_name;
   
	
	/* Start up */
    public function __construct()
    {
        $this->stamp = date('Ymd');
        global $wpdb;
        $this->prefix = $wpdb->prefix;
        $this->prefix = $this->stamp;
           // tables
        $this->iss_grading_period = $this->prefix . 'iss_grading_period';
        $this->iss_class = $this->prefix . 'iss_class';
        $this->iss_student = $this->prefix . 'iss_student';
        $this->iss_assignment_type = $this->prefix . 'iss_assignment_type';
        $this->iss_userclassmap = $this->prefix . 'iss_userclassmap';
        $this->iss_assignment = $this->prefix . 'iss_assignment';
        $this->iss_score = $this->prefix . 'iss_score';
        $this->iss_scale = $this->prefix . 'iss_scale';
        $this->iss_userstudentmap = $this->prefix . 'iss_userstudentmap';
        $this->iss_users = $wpdb->prefix . 'users';
        $this->iss_posts = $wpdb->prefix . 'posts';
    

          // views
        $this->issv_classes = $this->prefix . 'issv_classes';
        $this->issv_class_assignments = $this->prefix . 'issv_class_assignments';
        $this->issv_class_students = $this->prefix . 'issv_class_students';
        $this->issv_student_accounts = $this->prefix . 'issv_student_accounts';
        $this->issv_student_class_access = $this->prefix . 'issv_student_class_access';
        $this->issv_student_lastlogin = $this->prefix . 'issv_student_lastlogin';
        $this->issv_student_scores = $this->prefix . 'issv_student_scores';
        $this->issv_student_score_byassignmenttype = $this->prefix . 'issv_student_score_byassignmenttype';
        $this->issv_teacher_class_access = $this->prefix . 'issv_teacher_class_access';
        $this->issv_teacher_name = $this->prefix . 'issv_teacher_name';

        add_action('admin_menu', array($this, 'add_plugin_page'));
        add_action('init', array($this, 'add_plugin_page_action'));
		//add_action ( 'admin_enqueue_scripts', 'load_custom_issv_style' );
    }
    public function add_plugin_page()
    {
		// add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
        add_menu_page('Prepare', 'Prepare', 'manage_options', 'issvpreplist', array($this, 'prep_page'), 'dashicons-lightbulb', 99);
    }
    public function add_plugin_page_action()
    {
		// / IF FORM POST REQUEST
        if (isset($_POST['_wpnonce-iss-prep_cases'])) {
            check_admin_referer('iss-prep_cases', '_wpnonce-iss-prep_cases');

            if (isset($_POST['submit'])) {
                $this->submit = $_POST['submit'];
            }
        } // form post request
    }
    public function prep_page()
    {
        ?>

<div class="wrap">
	<h2><?php echo "Preparation Cases {$this->stamp}"; ?></h2>

    <?php
    if (isset($_GET['error'])) {
        echo '<div class="updated"><p><strong> Error happened.</strong></p></div>';
    }
    ?>
</div>


<form class="form" method="post" action="" enctype="multipart/form-data">
	<?php wp_nonce_field('iss-prep_cases', '_wpnonce-iss-prep_cases'); ?>
	<hr />

<?php

echo "<h3>Create Tables</h3>";
echo '<button type="submit" name="submit" class="button-primary"value="createtables">Create Tables</button>';
if ($this->submit === 'createtables') {
    $this->issv_sqlcreate_install();
}

echo '<h3>Delete Tables</h3>';
echo '<button type="submit" name="submit" class="button-primary"value="deletetables">Delete Tables</button>';
if ($this->submit === 'deletetables') {
    $this->issv_sqlcreate_uninstall();
}

echo '<h3>Insert Test Data</h3>';
echo '<button type="submit" name="submit" class="button-primary"value="insertdata">Insert Test Data</button>';
if ($this->submit === 'insertdata') {
    $this->issv_sqlinsert_install();
}


echo '<h3>Create Views</h3></td> </tr>';
echo '<button type="submit" name="submit" class="button-primary"value="viewcreate">Create Views</button>';
if ($this->submit === 'viewcreate') {
    $this->issv_sqlview_install();
}

echo '<h3>Delete Views</h3></td> </tr>';
echo '<button type="submit" name="submit" class="button-primary"value="viewdelete">Delete Views</button>';
if ($this->submit === 'viewdelete') {
    $this->issv_sqlview_uninstall();
}

echo ' <h3>Create Account</h3>';
echo '<button type="submit" name="submit" class="button-primary"value="accountcreate">Create Accounts</button>';
if ($this->submit === 'accountcreate') {
    $this->issv_sqlaccount_install();
}


echo ' <h3>Delete Account</h3>';
echo '<button type="submit" name="submit" class="button-primary"value="accountdelete">Delete Accounts</button>';
if ($this->submit === 'accountdelete') {
    $this->issv_sqlaccount_uninstall();
}

?>

	<h4 class="text-danger"> <?php echo 'Result: ' . $this->stamp; ?> </h4>

</form>	
<?php

} // prep_page


public function issv_sqlcreate_grading_period($charset_collate, $table_name)
{

    $sql = "CREATE TABLE $table_name ( 

        `GradingPeriodID` INT(11) NOT NULL AUTO_INCREMENT, 
        `RegistrationYear` VARCHAR(10) NOT NULL, 
        `GradingPeriod` INT(11) NOT NULL, 
        `StartDate` DATE NOT NULL, 
        `EndDate` DATE NOT NULL, 
        `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
        `updated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, 
        PRIMARY KEY(`GradingPeriodID`), 
        UNIQUE KEY `RegistrationYear{$this->stamp}` (`RegistrationYear`, `GradingPeriod`) 

	) $charset_collate;";

    echo "<br/><br/>CREATE TABLE  {$table_name}  <br/>{$sql}";
    $result = dbDelta($sql);
    if ($result == false) echo "<br/> ===<span style='color:red;font-size:large;'>Failure</span>";
    else echo "<br/> ===<span style='color:green;font-size:large;'>Success</span><br/>";
    print_r($result);
}
public function issv_sqlcreate_class($charset_collate, $table_name, $depends)
{
    $stamp = current_time('mysql');
    $sql = "CREATE TABLE $table_name ( 

        `ClassID` int(11) NOT NULL,
        `RegistrationYear` varchar(10) NOT NULL,
        `ISSGrade` varchar(2) NOT NULL DEFAULT 'KG',
        `Subject` varchar(100) NOT NULL DEFAULT 'Islamic Studies',
        `Suffix` varchar(50) DEFAULT NULL,
        `Category` varchar(10) NOT NULL DEFAULT 'kgis',
        `Status` varchar(10) NOT NULL DEFAULT 'active',
        `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`ClassID`),
        KEY `iss_class_RegistrationYear_FK{$this->stamp}` (`RegistrationYear`),
        CONSTRAINT `RegistrationYear_Class_FK{$this->stamp}` FOREIGN KEY (`RegistrationYear`) REFERENCES $depends (`RegistrationYear`)
 
    ) $charset_collate;";
    echo "<br/><br/>CREATE TABLE  {$table_name}  <br/>{$sql}";
    $result = dbDelta($sql);
    if ($result == false) echo "<br/> ===<span style='color:red;font-size:large;'>Failure</span>";
    else echo "<br/> ===<span style='color:green;font-size:large;'>Success</span><br/>";
    print_r($result);
}
public function issv_sqlcreate_student($charset_collate, $table_name, $depends)
{

    $sql = "CREATE TABLE $table_name ( 

        `StudentViewID` int(11) NOT NULL DEFAULT '0',
        `RegistrationYear` varchar(9) DEFAULT NULL,
        `ParentID` int(11) DEFAULT NULL,
        `FatherFirstName` varchar(100) DEFAULT NULL,
        `FatherLastName` varchar(100) DEFAULT NULL,
        `FatherEmail` varchar(100) DEFAULT NULL,
        `MotherFirstName` varchar(100) DEFAULT NULL,
        `MotherLastName` varchar(100) DEFAULT NULL,
        `MotherEmail` varchar(100) DEFAULT NULL,
        `StudentID` int(11) DEFAULT NULL,
        `StudentFirstName` varchar(35) DEFAULT NULL,
        `StudentLastName` varchar(35) DEFAULT NULL,
        `StudentGender` varchar(1) DEFAULT NULL,
        `StudentStatus` varchar(10) DEFAULT NULL,
        `StudentEmail` varchar(100) DEFAULT NULL,
        `ISSGrade` varchar(2) DEFAULT NULL,
        `SchoolEmail` varchar(100) DEFAULT NULL,
        PRIMARY KEY (`StudentViewID`),
        KEY `iss_student_RegistrationYear_FK{$this->stamp}` (`RegistrationYear`),
        CONSTRAINT `RegistrationYear_Student_FK{$this->stamp}` FOREIGN KEY (`RegistrationYear`) REFERENCES $depends (`RegistrationYear`)

    ) $charset_collate;";
    echo "<br/><br/>CREATE TABLE  {$table_name}  <br/>{$sql}";
    $result = dbDelta($sql);
    if ($result == false) echo "<br/> ===<span style='color:red;font-size:large;'>Failure</span>";
    else echo "<br/> ===<span style='color:green;font-size:large;'>Success</span><br/>";
    print_r($result);
}
public function issv_sqlcreate_assignment_type($charset_collate, $table_name, $depends)
{

    $sql = "CREATE TABLE $table_name ( 

        `AssignmentTypeID` int(11) NOT NULL AUTO_INCREMENT,
        `ClassID` int(11) NOT NULL,
        `TypeName` varchar(100) NOT NULL,
        `TypePercentage` int(5) NOT NULL,
        PRIMARY KEY (`AssignmentTypeID`),
        KEY `iss_assignmenttype_ClassID_FK{$this->stamp}` (`ClassID`),
        CONSTRAINT `ClassID_AssignmentType_FK{$this->stamp}` FOREIGN KEY (`ClassID`) REFERENCES $depends (`ClassID`)

    ) $charset_collate;";
    echo "<br/><br/>CREATE TABLE  {$table_name}  <br/>{$sql}";
    $result = dbDelta($sql);
    if ($result == false) echo "<br/> ===<span style='color:red;font-size:large;'>Failure</span>";
    else echo "<br/> ===<span style='color:green;font-size:large;'>Success</span><br/>";
    print_r($result);
}
public function issv_sqlcreate_userclassmap($charset_collate, $table_name, $depends)
{

    $sql = "CREATE TABLE $table_name ( 

        `UserID` int(11) NOT NULL,
        `ClassID` int(11) NOT NULL,
        `Access` varchar(10) NOT NULL DEFAULT 'read',
        `LastLogin` datetime DEFAULT NULL,
        PRIMARY KEY (`UserID`,`ClassID`),
        KEY `iss_userclassmap_ClassID_FK{$this->stamp}` (`ClassID`),
        CONSTRAINT `ClassID_UserClassMap_FK{$this->stamp}` FOREIGN KEY (`ClassID`) REFERENCES $depends (`ClassID`)

    ) $charset_collate;";
    echo "<br/><br/>CREATE TABLE  {$table_name}  <br/>{$sql}";
    $result = dbDelta($sql);
    if ($result == false) echo "<br/> ===<span style='color:red;font-size:large;'>Failure</span>";
    else echo "<br/> ===<span style='color:green;font-size:large;'>Success</span><br/>";
    print_r($result);
}
public function issv_sqlcreate_scale($charset_collate, $table_name, $depends)
{

    $sql = "CREATE TABLE $table_name ( 

        `ScaleID` int(11) NOT NULL AUTO_INCREMENT,
        `ClassID` int(11) NOT NULL,
        `ScaleName` varchar(100) NOT NULL,
        `ScalePercentage` int(5) NOT NULL,
        PRIMARY KEY (`ScaleID`),
        KEY `iss_scale_ClassID_FK` (`ClassID`),
        CONSTRAINT `ClassID_Scale_FK{$this->stamp}` FOREIGN KEY (`ClassID`) REFERENCES $depends (`ClassID`)

    ) $charset_collate;";
    echo "<br/><br/>CREATE TABLE  {$table_name}  <br/>{$sql}";
    $result = dbDelta($sql);
    if ($result == false) echo "<br/> ===<span style='color:red;font-size:large;'>Failure</span>";
    else echo "<br/> ===<span style='color:green;font-size:large;'>Success</span><br/>";
    print_r($result);
}
public function issv_sqlcreate_assignment($charset_collate, $table_name, $depends, $depends1)
{

    $sql = "CREATE TABLE $table_name ( 

        `ID` bigint(20) unsigned NOT NULL,
        `PossiblePoints` int(10) DEFAULT '10',
        `DueDate` date NOT NULL,
        `Category` varchar(20) NOT NULL,
        `ClassID` bigint(20) DEFAULT '0',
        `AssignmentTypeID` int(11) DEFAULT NULL,
        `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`ID`),
        KEY `iss_assignment_ClassID_FK{$this->stamp}` (`ClassID`),
        KEY `iss_assignment_AssignmentTypeID_FK{$this->stamp}` (`AssignmentTypeID`)
        ,CONSTRAINT `Assignment_AssignmentTypeID_FK{$this->stamp}` FOREIGN KEY (`AssignmentTypeID`) REFERENCES $depends1 (`AssignmentTypeID`)
        
 
    ) $charset_collate;";
    echo "<br/><br/>CREATE TABLE  {$table_name}  <br/>{$sql}";
    $result = dbDelta($sql);
    if ($result == false) echo "<br/> ===<span style='color:red;font-size:large;'>Failure</span>";
    else echo "<br/> ===<span style='color:green;font-size:large;'>Success</span><br/>";
    print_r($result);     
        //,CONSTRAINT `Assignment_ClassID_FK{$this->stamp}` FOREIGN KEY (`ClassID`) REFERENCES $depends (`ClassID`)
}
public function issv_sqlcreate_score($charset_collate, $table_name, $depends, $depends1)
{

    $sql = "CREATE TABLE $table_name ( 

        `StudentViewID` int(11) NOT NULL,
        `AssignmentID` bigint(20) unsigned NOT NULL,
        `Score` float(5,2) NOT NULL DEFAULT '0',
        `Comment` varchar(200) DEFAULT NULL,
        PRIMARY KEY (`StudentViewID`,`AssignmentID`),
        KEY `iss_score_StdentViewID_FK` (`StudentViewID`),
        KEY `iss_score_AssignmentID_FK` (`AssignmentID`),
        CONSTRAINT `AssignmentID_Score_FK{$this->stamp}` FOREIGN KEY (`AssignmentID`) REFERENCES $depends1 (`ID`),
        CONSTRAINT `StdentViewID_Score_FK{$this->stamp}` FOREIGN KEY (`StudentViewID`) REFERENCES $depends (`StudentViewID`)
    ) $charset_collate;";
    echo "<br/><br/>CREATE TABLE  {$table_name}  <br/>{$sql}";
    $result = dbDelta($sql);
    if ($result == false) echo "<br/> ===<span style='color:red;font-size:large;'>Failure</span>";
    else echo "<br/> ===<span style='color:green;font-size:large;'>Success</span><br/>";
    print_r($result);
}
public function issv_sqlcreate_userstudentmap($charset_collate, $table_name)
{

    $sql = "CREATE TABLE $table_name ( 

        `UserID` bigint(20) NOT NULL,
        `StudentID` bigint(20) NOT NULL,
        `Access` varchar(10) NOT NULL DEFAULT 'read',
        `LastLogin` datetime DEFAULT NULL,
        PRIMARY KEY (`UserID`,`StudentID`)

    ) $charset_collate;";
    echo "<br/><br/>CREATE TABLE  {$table_name}  <br/>{$sql}";
    $result = dbDelta($sql);
    if ($result == false) echo "<br/> ===<span style='color:red;font-size:large;'>Failure</span>";
    else echo "<br/> ===<span style='color:green;font-size:large;'>Success</span><br/>";
    print_r($result);
}
public function issv_sqlcreate_install()
{
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    $this->issv_sqlcreate_grading_period($charset_collate, $this->iss_grading_period);
    $this->issv_sqlcreate_class($charset_collate, $this->iss_class, $this->iss_grading_period); //  depends on  grading_period
    $this->issv_sqlcreate_student($charset_collate, $this->iss_student, $this->iss_grading_period); //  depends on grading_period
    $this->issv_sqlcreate_assignment_type($charset_collate, $this->iss_assignment_type, $this->iss_class); // depends on class
    $this->issv_sqlcreate_userclassmap($charset_collate, $this->iss_userclassmap, $this->iss_class); // depends on class
    $this->issv_sqlcreate_scale($charset_collate, $this->iss_scale, $this->iss_class); // depends on class
    $this->issv_sqlcreate_assignment($charset_collate, $this->iss_assignment, $this->iss_class, $this->iss_assignment_type); //  depends on class & assignment_type
    $this->issv_sqlcreate_score($charset_collate, $this->iss_score, $this->iss_student, $this->iss_assignment); //  depends on student & assignment
    $this->issv_sqlcreate_userstudentmap($charset_collate, $this->iss_userstudentmap);
}
public function issv_sqlcreate_uninstall()
{
    global $wpdb;

    echo "<br/><br/>DROP TABLE  {$this->iss_userstudentmap}";
    $result = $wpdb->query("DROP TABLE  {$this->iss_userstudentmap}");
    if ($result) echo "<br/> ===<span style='color:green;font-size:large;'>Success</span>";
    else echo "<br/> ===<span style='color:red;font-size:large;'>Failure</span>";
    echo "<br/><br/>DROP TABLE  {$this->iss_scale}";
    $result = $wpdb->query("DROP TABLE {$this->iss_scale}");
    if ($result) echo "<br/> ===<span style='color:green;font-size:large;'>Success</span>";
    else echo "<br/> ===<span style='color:red;font-size:large;'>Failure</span>";
    echo "<br/><br/>DROP TABLE  {$this->iss_score}";
    $result = $wpdb->query("DROP TABLE {$this->iss_score}");
    if ($result) echo "<br/> ===<span style='color:green;font-size:large;'>Success</span>";
    else echo "<br/> ===<span style='color:red;font-size:large;'>Failure</span>";
    echo "<br/><br/>DROP TABLE  {$this->iss_assignment}";
    $result = $wpdb->query("DROP TABLE {$this->iss_assignment}");
    if ($result) echo "<br/> ===<span style='color:green;font-size:large;'>Success</span>";
    else echo "<br/> ===<span style='color:red;font-size:large;'>Failure</span>";
    echo "<br/><br/>DROP TABLE  {$this->iss_userclassmap}";
    $result = $wpdb->query("DROP TABLE {$this->iss_userclassmap}");
    if ($result) echo "<br/> ===<span style='color:green;font-size:large;'>Success</span>";
    else echo "<br/> ===<span style='color:red;font-size:large;'>Failure</span>";
    echo "<br/><br/>DROP TABLE  {$this->iss_assignment_type}";
    $result = $wpdb->query("DROP TABLE {$this->iss_assignment_type}");
    if ($result) echo "<br/> ===<span style='color:green;font-size:large;'>Success</span>";
    else echo "<br/> ===<span style='color:red;font-size:large;'>Failure</span>";
    echo "<br/><br/>DROP TABLE  {$this->iss_student}";
    $result = $wpdb->query("DROP TABLE {$this->iss_student}");
    if ($result) echo "<br/> ===<span style='color:green;font-size:large;'>Success</span>";
    else echo "<br/> ===<span style='color:red;font-size:large;'>Failure</span>";
    echo "<br/><br/>DROP TABLE  {$this->iss_class}";
    $result = $wpdb->query("DROP TABLE {$this->iss_class}");
    if ($result) echo "<br/> ===<span style='color:green;font-size:large;'>Success</span>";
    else echo "<br/> ===<span style='color:red;font-size:large;'>Failure</span>";
    echo "<br/><br/>DROP TABLE  {$this->iss_grading_period}";
    $result = $wpdb->query("DROP TABLE {$this->iss_grading_period}");
    if ($result) echo "<br/> ===<span style='color:green;font-size:large;'>Success</span>";
    else echo "<br/> ===<span style='color:red;font-size:large;'>Failure</span>";
}

public function issv_sqlinsert_install()
{
    global $wpdb;

    $wpdb->insert($this->iss_grading_period, array('GradingPeriodID' => '1', 'RegistrationYear' => '2016-2017', 'GradingPeriod' => '1', 'StartDate' => '2016-08-15', 'EndDate' => '2016-12-31', 'created' => '2018-08-12 22:19:28', 'updated' => '2018-08-12 22:21:56'));
    $wpdb->insert($this->iss_grading_period, array('GradingPeriodID' => '2', 'RegistrationYear' => '2016-2017', 'GradingPeriod' => '2', 'StartDate' => '2017-01-01', 'EndDate' => '2017-05-31', 'created' => '2018-08-12 22:19:28', 'updated' => '2018-08-12 22:19:28'));
    $wpdb->insert($this->iss_grading_period, array('GradingPeriodID' => '5', 'RegistrationYear' => '2017-2018', 'GradingPeriod' => '1', 'StartDate' => '2017-08-15', 'EndDate' => '2017-12-31', 'created' => '2018-08-12 22:23:41', 'updated' => '2018-08-12 22:23:41'));
    $wpdb->insert($this->iss_grading_period, array('GradingPeriodID' => '6', 'RegistrationYear' => '2017-2018', 'GradingPeriod' => '2', 'StartDate' => '2018-01-01', 'EndDate' => '2018-05-31', 'created' => '2018-08-12 22:23:41', 'updated' => '2018-08-12 22:23:41'));
    $wpdb->insert($this->iss_grading_period, array('GradingPeriodID' => '7', 'RegistrationYear' => '2018-2019', 'GradingPeriod' => '1', 'StartDate' => '2018-08-15', 'EndDate' => '2018-12-31', 'created' => '2018-08-12 22:24:04', 'updated' => '2018-08-12 22:24:04'));
    $wpdb->insert($this->iss_grading_period, array('GradingPeriodID' => '8', 'RegistrationYear' => '2018-2019', 'GradingPeriod' => '2', 'StartDate' => '2019-01-01', 'EndDate' => '2019-05-31', 'created' => '2018-08-12 22:24:04', 'updated' => '2018-08-12 22:24:04'));

    $wpdb->insert($this->iss_class, array('ClassID' => '1', 'RegistrationYear' => '2018-2019', 'ISSGrade' => 'KG', 'Subject' => 'Islamic Studies', 'Suffix' => 'Sem1', 'Category' => 'kgis', 'Status' => 'active', 'created' => '2018-09-11 23:52:11', 'updated' => '2018-09-11 23:54:14'));
    $wpdb->insert($this->iss_class, array('ClassID' => '2', 'RegistrationYear' => '2018-2019', 'ISSGrade' => 'KG', 'Subject' => 'Quranic Studies', 'Suffix' => 'Sem1', 'Category' => 'kgqs', 'Status' => 'active', 'created' => '2018-09-11 23:52:11', 'updated' => '2018-09-11 23:54:14'));
    $wpdb->insert($this->iss_class, array('ClassID' => '3', 'RegistrationYear' => '2018-2019', 'ISSGrade' => '1', 'Subject' => 'Islamic Studies', 'Suffix' => 'Sem1', 'Category' => 'g1is', 'Status' => 'active', 'created' => '2018-09-11 23:52:11', 'updated' => '2018-09-18 00:37:11'));
    $wpdb->insert($this->iss_class, array('ClassID' => '4', 'RegistrationYear' => '2018-2019', 'ISSGrade' => '1', 'Subject' => 'Quranic Studies', 'Suffix' => 'Sem1', 'Category' => 'g1qs', 'Status' => 'active', 'created' => '2018-09-11 23:52:11', 'updated' => '2018-09-11 23:54:14'));
    $wpdb->insert($this->iss_class, array('ClassID' => '5', 'RegistrationYear' => '2018-2019', 'ISSGrade' => '2', 'Subject' => 'Islamic Studies', 'Suffix' => 'Sem1', 'Category' => 'g2is', 'Status' => 'active', 'created' => '2018-09-11 23:52:11', 'updated' => '2018-09-11 23:54:14'));
    $wpdb->insert($this->iss_class, array('ClassID' => '6', 'RegistrationYear' => '2018-2019', 'ISSGrade' => '2', 'Subject' => 'Quranic Studies', 'Suffix' => 'Sem1', 'Category' => 'g2qs', 'Status' => 'active', 'created' => '2018-09-11 23:52:11', 'updated' => '2018-09-11 23:54:14'));
    $wpdb->insert($this->iss_class, array('ClassID' => '7', 'RegistrationYear' => '2018-2019', 'ISSGrade' => '3', 'Subject' => 'Islamic Studies', 'Suffix' => 'Sem1', 'Category' => 'g3is', 'Status' => 'active', 'created' => '2018-09-11 23:52:11', 'updated' => '2018-09-11 23:54:14'));
    $wpdb->insert($this->iss_class, array('ClassID' => '8', 'RegistrationYear' => '2018-2019', 'ISSGrade' => '3', 'Subject' => 'Quranic Studies', 'Suffix' => 'Sem1', 'Category' => 'g3qs', 'Status' => 'active', 'created' => '2018-09-11 23:52:11', 'updated' => '2018-09-11 23:54:14'));
    $wpdb->insert($this->iss_class, array('ClassID' => '9', 'RegistrationYear' => '2018-2019', 'ISSGrade' => '4', 'Subject' => 'Islamic Studies', 'Suffix' => 'Sem1', 'Category' => 'g4is', 'Status' => 'active', 'created' => '2018-09-11 23:52:11', 'updated' => '2018-09-11 23:54:14'));
    $wpdb->insert($this->iss_class, array('ClassID' => '10', 'RegistrationYear' => '2018-2019', 'ISSGrade' => '4', 'Subject' => 'Quranic Studies', 'Suffix' => 'Sem1', 'Category' => 'g4qs', 'Status' => 'active', 'created' => '2018-09-11 23:52:11', 'updated' => '2018-09-11 23:54:14'));
    $wpdb->insert($this->iss_class, array('ClassID' => '11', 'RegistrationYear' => '2018-2019', 'ISSGrade' => '5', 'Subject' => 'Islamic Studies', 'Suffix' => 'Sem1', 'Category' => 'g5is', 'Status' => 'active', 'created' => '2018-09-11 23:52:11', 'updated' => '2018-09-11 23:54:14'));
    $wpdb->insert($this->iss_class, array('ClassID' => '12', 'RegistrationYear' => '2018-2019', 'ISSGrade' => '5', 'Subject' => 'Quranic Studies', 'Suffix' => 'Sem1', 'Category' => 'g5qs', 'Status' => 'active', 'created' => '2018-09-11 23:52:11', 'updated' => '2018-09-11 23:54:14'));
    $wpdb->insert($this->iss_class, array('ClassID' => '13', 'RegistrationYear' => '2018-2019', 'ISSGrade' => '6', 'Subject' => 'Islamic Studies', 'Suffix' => 'Sem1', 'Category' => 'g6is', 'Status' => 'active', 'created' => '2018-09-11 23:52:11', 'updated' => '2018-09-11 23:54:14'));
    $wpdb->insert($this->iss_class, array('ClassID' => '14', 'RegistrationYear' => '2018-2019', 'ISSGrade' => '6', 'Subject' => 'Quranic Studies', 'Suffix' => 'Sem1', 'Category' => 'g6qs', 'Status' => 'active', 'created' => '2018-09-11 23:52:11', 'updated' => '2018-09-11 23:54:14'));
    $wpdb->insert($this->iss_class, array('ClassID' => '15', 'RegistrationYear' => '2018-2019', 'ISSGrade' => '7', 'Subject' => 'Islamic Studies', 'Suffix' => 'Sem1', 'Category' => 'g7is', 'Status' => 'active', 'created' => '2018-09-11 23:52:11', 'updated' => '2018-09-11 23:54:14'));
    $wpdb->insert($this->iss_class, array('ClassID' => '16', 'RegistrationYear' => '2018-2019', 'ISSGrade' => '7', 'Subject' => 'Quranic Studies', 'Suffix' => 'Sem1', 'Category' => 'g7qs', 'Status' => 'active', 'created' => '2018-09-11 23:52:11', 'updated' => '2018-09-11 23:54:14'));
    $wpdb->insert($this->iss_class, array('ClassID' => '17', 'RegistrationYear' => '2018-2019', 'ISSGrade' => '8', 'Subject' => 'Islamic Studies', 'Suffix' => 'Sem1', 'Category' => 'g8is', 'Status' => 'active', 'created' => '2018-09-11 23:52:11', 'updated' => '2018-09-11 23:54:14'));
    $wpdb->insert($this->iss_class, array('ClassID' => '18', 'RegistrationYear' => '2018-2019', 'ISSGrade' => '8', 'Subject' => 'Quranic Studies', 'Suffix' => 'Sem1', 'Category' => 'g8qs', 'Status' => 'active', 'created' => '2018-09-11 23:52:11', 'updated' => '2018-09-11 23:54:14'));
    $wpdb->insert($this->iss_class, array('ClassID' => '19', 'RegistrationYear' => '2018-2019', 'ISSGrade' => 'YB', 'Subject' => 'Islamic Studies', 'Suffix' => 'Sem1', 'Category' => 'ybis', 'Status' => 'active', 'created' => '2018-09-11 23:52:11', 'updated' => '2018-09-11 23:54:14'));
    $wpdb->insert($this->iss_class, array('ClassID' => '20', 'RegistrationYear' => '2018-2019', 'ISSGrade' => 'YB', 'Subject' => 'Quranic Studies', 'Suffix' => 'Sem1', 'Category' => 'ybqs', 'Status' => 'active', 'created' => '2018-09-11 23:52:11', 'updated' => '2018-09-11 23:54:14'));
    $wpdb->insert($this->iss_class, array('ClassID' => '21', 'RegistrationYear' => '2018-2019', 'ISSGrade' => 'YG', 'Subject' => 'Islamic Studies', 'Suffix' => 'Sem1', 'Category' => 'ygis', 'Status' => 'active', 'created' => '2018-09-11 23:52:11', 'updated' => '2018-09-11 23:54:14'));
    $wpdb->insert($this->iss_class, array('ClassID' => '22', 'RegistrationYear' => '2018-2019', 'ISSGrade' => 'YG', 'Subject' => 'Quranic Studies', 'Suffix' => 'Sem1', 'Category' => 'ygqs', 'Status' => 'active', 'created' => '2018-09-11 23:52:11', 'updated' => '2018-09-11 23:54:14'));
  
    // Grade 1
    $wpdb->insert($this->iss_student, array('StudentViewID' => '529', 'RegistrationYear' => '2018-2019', 'ParentID' => '126', 'FatherFirstName' => 'Basim', 'FatherLastName' => 'Abu Hamid', 'FatherEmail' => '', 'MotherFirstName' => 'Fitan', 'MotherLastName' => 'Khalil', 'MotherEmail' => 'a@gmail.com', 'StudentID' => '1280', 'StudentFirstName' => 'Zachariah', 'StudentLastName' => 'Abu Hamid', 'StudentGender' => 'M', 'StudentStatus' => 'active', 'StudentEmail' => '', 'ISSGrade' => '1', 'SchoolEmail' => 'a@gmail.com'));
    $wpdb->insert($this->iss_student, array('StudentViewID' => '546', 'RegistrationYear' => '2018-2019', 'ParentID' => '156', 'FatherFirstName' => 'Omar', 'FatherLastName' => 'Alnaggar', 'FatherEmail' => 'o@gmail.com', 'MotherFirstName' => 'Rasha', 'MotherLastName' => 'Elsayed', 'MotherEmail' => 'b.as@gmail.com', 'StudentID' => '1314', 'StudentFirstName' => 'Ali', 'StudentLastName' => 'Alnaggar', 'StudentGender' => 'M', 'StudentStatus' => 'active', 'StudentEmail' => '', 'ISSGrade' => '1', 'SchoolEmail' => 'b@gmail.com'));
    $wpdb->insert($this->iss_student, array('StudentViewID' => '549', 'RegistrationYear' => '2018-2019', 'ParentID' => '149', 'FatherFirstName' => 'Naveed', 'FatherLastName' => 'Anwar', 'FatherEmail' => 'f@cantab.net', 'MotherFirstName' => 'Rabia', 'MotherLastName' => 'Bajwa', 'MotherEmail' => 'c@gmail.com', 'StudentID' => '1300', 'StudentFirstName' => 'Zayd', 'StudentLastName' => 'Anwar', 'StudentGender' => 'M', 'StudentStatus' => 'active', 'StudentEmail' => '', 'ISSGrade' => '1', 'SchoolEmail' => 'c@gmail.com'));
    $wpdb->insert($this->iss_student, array('StudentViewID' => '571', 'RegistrationYear' => '2018-2019', 'ParentID' => '26', 'FatherFirstName' => 'Haseeb', 'FatherLastName' => 'Budhani', 'FatherEmail' => '', 'MotherFirstName' => 'Haina', 'MotherLastName' => 'Karim', 'MotherEmail' => 'd.@gmail.com', 'StudentID' => '1288', 'StudentFirstName' => 'Rafay', 'StudentLastName' => 'Budhani', 'StudentGender' => 'M', 'StudentStatus' => 'active', 'StudentEmail' => '', 'ISSGrade' => '1', 'SchoolEmail' => 'd@gmail.com'));
    $wpdb->insert($this->iss_student, array('StudentViewID' => '592', 'RegistrationYear' => '2018-2019', 'ParentID' => '141', 'FatherFirstName' => 'Omair', 'FatherLastName' => 'Farooqui', 'FatherEmail' => 's@yahoo.com', 'MotherFirstName' => 'Amina', 'MotherLastName' => 'Anwar', 'MotherEmail' => 'e@gmail.com', 'StudentID' => '1290', 'StudentFirstName' => 'Hala', 'StudentLastName' => 'Farooqui', 'StudentGender' => 'F', 'StudentStatus' => 'active', 'StudentEmail' => '', 'ISSGrade' => '1', 'SchoolEmail' => 'e@yahoo.com'));
    // Grade KG
    $wpdb->insert($this->iss_student, array('StudentViewID' => '746', 'RegistrationYear' => '2018-2019', 'ParentID' => '41', 'FatherFirstName' => 'Rehan', 'FatherLastName' => 'Hameed', 'FatherEmail' => 'd@gmail.com', 'MotherFirstName' => 'Mehjabeen', 'MotherLastName' => 'Awan', 'MotherEmail' => 'f@yahoo.com', 'StudentID' => '1351', 'StudentFirstName' => 'Zunaira', 'StudentLastName' => 'Rehan', 'StudentGender' => 'F', 'StudentStatus' => 'active', 'StudentEmail' => '', 'ISSGrade' => 'KG', 'SchoolEmail' => 'f@yahoo.com'));
    $wpdb->insert($this->iss_student, array('StudentViewID' => '748', 'RegistrationYear' => '2018-2019', 'ParentID' => '28', 'FatherFirstName' => 'Arshad', 'FatherLastName' => 'Siddiqi', 'FatherEmail' => 'e@gmail.com', 'MotherFirstName' => 'Sabah', 'MotherLastName' => 'Siddiqi', 'MotherEmail' => 'g@gmail.com', 'StudentID' => '1353', 'StudentFirstName' => 'Alia', 'StudentLastName' => 'Siddiqi', 'StudentGender' => 'M', 'StudentStatus' => 'active', 'StudentEmail' => '', 'ISSGrade' => 'KG', 'SchoolEmail' => 'g@gmail.com'));
    $wpdb->insert($this->iss_student, array('StudentViewID' => '750', 'RegistrationYear' => '2018-2019', 'ParentID' => '127', 'FatherFirstName' => 'Omar', 'FatherLastName' => 'Siddiqui', 'FatherEmail' => 'f@gmail.com', 'MotherFirstName' => 'Zeenat', 'MotherLastName' => 'Khan', 'MotherEmail' => 'h@gmail.com', 'StudentID' => '1355', 'StudentFirstName' => 'Maryam', 'StudentLastName' => 'Siddiqui', 'StudentGender' => 'F', 'StudentStatus' => 'active', 'StudentEmail' => '', 'ISSGrade' => 'KG', 'SchoolEmail' => 'h@gmail.com'));
    $wpdb->insert($this->iss_student, array('StudentViewID' => '752', 'RegistrationYear' => '2018-2019', 'ParentID' => '63', 'FatherFirstName' => 'Asif', 'FatherLastName' => 'Hassan', 'FatherEmail' => 'g@hotmail.com', 'MotherFirstName' => 'Amina', 'MotherLastName' => 'Khan', 'MotherEmail' => 'i@yahoo.com', 'StudentID' => '1357', 'StudentFirstName' => 'Arshad', 'StudentLastName' => 'Hassan', 'StudentGender' => 'F', 'StudentStatus' => 'active', 'StudentEmail' => null, 'ISSGrade' => 'KG', 'SchoolEmail' => 'i@yahoo.com'));
    $wpdb->insert($this->iss_student, array('StudentViewID' => '753', 'RegistrationYear' => '2018-2019', 'ParentID' => '64', 'FatherFirstName' => 'Kashif', 'FatherLastName' => 'Hassan', 'FatherEmail' => 'j@gmail.com', 'MotherFirstName' => 'Faiqa', 'MotherLastName' => 'Hassan', 'MotherEmail' => 'j@gmail.com', 'StudentID' => '1358', 'StudentFirstName' => 'Taha', 'StudentLastName' => 'Hassan', 'StudentGender' => 'M', 'StudentStatus' => 'active', 'StudentEmail' => null, 'ISSGrade' => 'KG', 'SchoolEmail' => 'j@gmail.com'));

}

public function issv_sqlview_install()
{
    global $wpdb;

    echo "<br/><br/>CREATE VIEW {$this->issv_class_assignments}";
    $sql = "CREATE VIEW $this->issv_class_assignments AS select `d`.`ClassID` AS `ClassID`,`d`.`DueDate` AS `DueDate`,`d`.`Category` AS `Category`,`d`.`PossiblePoints` AS `PossiblePoints`,`d`.`AssignmentTypeID` AS `AssignmentTypeID`,`c`.`ISSGrade` AS `ISSGrade`,`c`.`RegistrationYear` AS `RegistrationYear`,`c`.`Subject` AS `Subject`,`p`.`ID` AS `ID`,`p`.`post_author` AS `post_author`,`p`.`post_date` AS `post_date`,`p`.`post_content` AS `post_content`,`p`.`post_title` AS `post_title`,`p`.`post_status` AS `post_status`,`p`.`post_name` AS `post_name`,`p`.`guid` AS `guid`,`p`.`post_type` AS `post_type` 
        from (($this->iss_assignment `d` join $this->iss_posts `p`) join $this->iss_class `c`) where ((`d`.`ID` = `p`.`ID`) and (`c`.`ClassID` = `d`.`ClassID`));";
    $result = $wpdb->query($sql);
    if ($result) echo "<br/> ===<span style='color:green;font-size:large;'>Success</span>";
    else echo "<br/> ===<span style='color:red;font-size:large;'>Failure</span>";

    echo "<br/><br/>CREATE VIEW {$this->issv_class_students}";
    $sql = "CREATE VIEW $this->issv_class_students AS select `C`.`ClassID` AS `ClassID`,`S`.`StudentViewID` AS `StudentViewID`,`S`.`StudentID` AS `StudentID`,`S`.`StudentFirstName` AS `StudentFirstName`,`S`.`StudentLastName` AS `StudentLastName`,`S`.`StudentGender` AS `StudentGender` 
        from ($this->iss_class `C` join $this->iss_student `S`) where ((`S`.`ISSGrade` = `C`.`ISSGrade`) and (`S`.`StudentStatus` = 'active') and (`C`.`Status` = 'active') and (`S`.`RegistrationYear` = `C`.`RegistrationYear`));";
    $result = $wpdb->query($sql);
    if ($result) echo "<br/> ===<span style='color:green;font-size:large;'>Success</span>";
    else echo "<br/> ===<span style='color:red;font-size:large;'>Failure</span>";
    echo "<br/><br/>CREATE VIEW {$this->issv_classes}";
    $sql = "CREATE  VIEW $this->issv_classes AS select `C`.`ClassID` AS `ClassID`,`C`.`RegistrationYear` AS `RegistrationYear`,`C`.`ISSGrade` AS `ISSGrade`,`C`.`Subject` AS `Subject`,`C`.`Status` AS `Status`,`U`.`ID` AS `UserID`,`U`.`user_email` AS `UserEmail`,`M`.`Access` AS `Access`,`U`.`display_name` AS `Teacher`,`M`.`LastLogin` AS `LastLogin` 
        from (($this->iss_class `C` left join $this->iss_userclassmap `M` on((`M`.`ClassID` = `C`.`ClassID`))) left join $this->iss_users `U` on((`M`.`UserID` = `U`.`ID`)));";
    $result = $wpdb->query($sql);
    if ($result) echo "<br/> ===<span style='color:green;font-size:large;'>Success</span>";
    else echo "<br/> ===<span style='color:red;font-size:large;'>Failure</span>";
    echo "<br/><br/>CREATE VIEW {$this->issv_student_accounts}";
    $sql = "CREATE VIEW $this->issv_student_accounts AS select `S`.`StudentID` AS `StudentID`,`S`.`StudentViewID` AS `StudentViewID`,`S`.`RegistrationYear` AS `RegistrationYear`,`S`.`ParentID` AS `ParentID`,`S`.`FatherFirstName` AS `FatherFirstName`,`S`.`FatherLastName` AS `FatherLastName`,`S`.`FatherEmail` AS `FatherEmail`,`S`.`MotherFirstName` AS `MotherFirstName`,`S`.`MotherLastName` AS `MotherLastName`,`S`.`MotherEmail` AS `MotherEmail`,`S`.`StudentFirstName` AS `StudentFirstName`,`S`.`StudentLastName` AS `StudentLastName`,`S`.`StudentGender` AS `StudentGender`,`S`.`StudentEmail` AS `StudentEmail`,`S`.`ISSGrade` AS `ISSGrade`,`M`.`UserID` AS `UserID`,`U`.`user_email` AS `UserEmail`,`M`.`Access` AS `Access`,`U`.`user_nicename` AS `NiceName`,`M`.`LastLogin` AS `LastLogin`,`S`.`StudentStatus` AS `StudentStatus` 
        from (($this->iss_student `S` left join $this->iss_userstudentmap `M` on((`M`.`StudentID` = `S`.`StudentID`))) left join $this->iss_users `U` on((`U`.`ID` = `M`.`UserID`)));";
    $result = $wpdb->query($sql);
    if ($result) echo "<br/> ===<span style='color:green;font-size:large;'>Success</span>";
    else echo "<br/> ===<span style='color:red;font-size:large;'>Failure</span>";
    echo "<br/><br/>CREATE VIEW {$this->issv_student_class_access}";
    $sql = "CREATE VIEW $this->issv_student_class_access AS select `S`.`StudentViewID` AS `StudentViewID`,`S`.`StudentFirstName` AS `StudentFirstName`,`S`.`StudentLastName` AS `StudentLastName`,`C`.`ClassID` AS `ClassID`,`S`.`RegistrationYear` AS `RegistrationYear`,`C`.`ISSGrade` AS `ISSGrade`,`C`.`Subject` AS `Subject`,`C`.`Suffix` AS `Suffix`,`M`.`UserID` AS `UserID`,`M`.`Access` AS `Access` 
        from (($this->iss_class `C` join $this->iss_student `S` on(((`S`.`ISSGrade` = `C`.`ISSGrade`) and (`S`.`RegistrationYear` = `C`.`RegistrationYear`)))) join $this->iss_userstudentmap `M` on((`M`.`StudentID` = `S`.`StudentID`))) where ((`C`.`Status` = 'active') and (`S`.`StudentStatus` = 'active'));";
    $result = $wpdb->query($sql);
    if ($result) echo "<br/> ===<span style='color:green;font-size:large;'>Success</span>";
    else echo "<br/> ===<span style='color:red;font-size:large;'>Failure</span>";
    echo "<br/><br/>CREATE VIEW {$this->issv_student_lastlogin}";
    $sql = "CREATE VIEW $this->issv_student_lastlogin AS select `P`.`StudentID` AS `StudentID`,max(`P`.`LastLogin`) AS `LastLogin` 
        from $this->iss_userstudentmap `P` where (`P`.`LastLogin` is not null) group by `P`.`StudentID`;";
    $result = $wpdb->query($sql);
    if ($result) echo "<br/> ===<span style='color:green;font-size:large;'>Success</span>";
    else echo "<br/> ===<span style='color:red;font-size:large;'>Failure</span>";
    echo "<br/><br/>CREATE VIEW {$this->issv_student_score_byassignmenttype}";
    $sql = "CREATE VIEW $this->issv_student_score_byassignmenttype AS select `T`.`ClassID` AS `ClassID`,`T`.`AssignmentTypeID`,`T`.`TypeName` AS `TypeName`,`G`.`StudentViewID` AS `StudentViewID`,max(`T`.`TypePercentage`) AS `TypePercentage`,ifnull(((((sum(`G`.`Score`) / sum(`A`.`PossiblePoints`)) * 100) * max(`T`.`TypePercentage`)) / 100),max(`T`.`TypePercentage`)) AS `TypeGrade` 
        from (($this->iss_assignment_type `T` left join $this->iss_assignment `A` on((`A`.`AssignmentTypeID` = `T`.`AssignmentTypeID`))) left join $this->iss_score `G` on((`G`.`AssignmentID` = `A`.`ID`))) group by `T`.`ClassID`,`T`.`AssignmentTypeID`,`T`.`TypeName`,`G`.`StudentViewID`;";
    $result = $wpdb->query($sql);
    if ($result) echo "<br/> ===<span style='color:green;font-size:large;'>Success</span>";
    else echo "<br/> ===<span style='color:red;font-size:large;'>Failure</span>";
    echo "<br/><br/>CREATE VIEW {$this->issv_student_scores}";
    $sql = "CREATE VIEW $this->issv_student_scores AS select `S`.`StudentViewID` AS `StudentViewID`,`A`.`ID` AS `AssignmentID`,`A`.`AssignmentTypeID` AS `AssignmentTypeID`,`A`.`DueDate` AS `DueDate`,`A`.`PossiblePoints` AS `PossiblePoints`,`G`.`Score` AS `Score`,`G`.`Comment` AS `Comment`,`C`.`ClassID` AS `ClassID`,`P`.`post_title` AS `Title`,`S`.`StudentFirstName` AS `StudentFirstName`,`S`.`StudentLastName` AS `StudentLastName`,`S`.`ISSGrade` AS `ISSGrade`,`C`.`Subject` AS `Subject` 
        from (((($this->iss_assignment `A` join $this->iss_posts `P` on((`A`.`ID` = `P`.`ID`))) join $this->iss_class `C` on((`C`.`ClassID` = `A`.`ClassID`))) join $this->iss_student `S` on(((`S`.`ISSGrade` = `C`.`ISSGrade`) and (`S`.`RegistrationYear` = `C`.`RegistrationYear`)))) left join $this->iss_score `G` on(((`G`.`AssignmentID` = `A`.`ID`) and (`G`.`StudentViewID` = `S`.`StudentViewID`)))) where (`S`.`StudentStatus` = 'active');";
    $result = $wpdb->query($sql);
    if ($result) echo "<br/> ===<span style='color:green;font-size:large;'>Success</span>";
    else echo "<br/> ===<span style='color:red;font-size:large;'>Failure</span>";
    echo "<br/><br/>CREATE VIEW {$this->issv_teacher_class_access}";
    $sql = "CREATE VIEW $this->issv_teacher_class_access AS select `C`.`ClassID` AS `ClassID`,`C`.`RegistrationYear` AS `RegistrationYear`,`C`.`ISSGrade` AS `ISSGrade`,`C`.`Subject` AS `Subject`,`C`.`Suffix` AS `Suffix`,`M`.`UserID` AS `UserID`,`M`.`Access` AS `Access` 
        from ($this->iss_class `C` join $this->iss_userclassmap `M`) where ((`C`.`Status` = 'active') and (`C`.`ClassID` = `M`.`ClassID`));";
    $result = $wpdb->query($sql);
    if ($result) echo "<br/> ===<span style='color:green;font-size:large;'>Success</span>";
    else echo "<br/> ===<span style='color:red;font-size:large;'>Failure</span>";
    echo "<br/><br/>CREATE VIEW {$this->issv_teacher_name}";
    $sql = "CREATE VIEW $this->issv_teacher_name AS select `M`.`ClassID` AS `ClassID`,`M`.`UserID` AS `UserID`,`U`.`display_name` AS `Teacher`,`M`.`Access` AS `Access` 
        from ($this->iss_userclassmap `M` join $this->iss_users `U`) where (`U`.`ID` = `M`.`UserID`);";
    $result = $wpdb->query($sql);
    if ($result) echo "<br/> ===<span style='color:green;font-size:large;'>Success</span>";
    else echo "<br/> ===<span style='color:red;font-size:large;'>Failure</span>";

}
public function issv_sqlview_uninstall()
{
    global $wpdb;

    echo "<br/><br/>DROP VIEW {$this->issv_classes}";
    $result = $wpdb->query("DROP VIEW $this->issv_classes");
    if ($result) echo "<br/> ===<span style='color:green;font-size:large;'>Success</span>";
    else echo "<br/> ===<span style='color:red;font-size:large;'>Failure</span>";
    echo "<br/><br/>DROP VIEW {$this->issv_class_students}";
    $result = $wpdb->query("DROP VIEW $this->issv_class_students");
    if ($result) echo "<br/> ===<span style='color:green;font-size:large;'>Success</span>";
    else echo "<br/> ===<span style='color:red;font-size:large;'>Failure</span>";
    echo "<br/><br/>DROP VIEW {$this->issv_class_assignments}";
    $result = $wpdb->query("DROP VIEW $this->issv_class_assignments");
    if ($result) echo "<br/> ===<span style='color:green;font-size:large;'>Success</span>";
    else echo "<br/> ===<span style='color:red;font-size:large;'>Failure</span>";
    echo "<br/><br/>DROP VIEW {$this->issv_student_accounts}";
    $result = $wpdb->query("DROP VIEW $this->issv_student_accounts");
    if ($result) echo "<br/> ===<span style='color:green;font-size:large;'>Success</span>";
    else echo "<br/> ===<span style='color:red;font-size:large;'>Failure</span>";
    echo "<br/><br/>DROP VIEW {$this->issv_student_class_access}";
    $result = $wpdb->query("DROP VIEW $this->issv_student_class_access");
    if ($result) echo "<br/> ===<span style='color:green;font-size:large;'>Success</span>";
    else echo "<br/> ===<span style='color:red;font-size:large;'>Failure</span>";
    echo "<br/><br/>DROP VIEW {$this->issv_student_lastlogin}";
    $result = $wpdb->query("DROP VIEW $this->issv_student_lastlogin");
    if ($result) echo "<br/> ===<span style='color:green;font-size:large;'>Success</span>";
    else echo "<br/> ===<span style='color:red;font-size:large;'>Failure</span>";
    echo "<br/><br/>DROP VIEW {$this->issv_student_score_byassignmenttype}";
    $result = $wpdb->query("DROP VIEW $this->issv_student_score_byassignmenttype");
    if ($result) echo "<br/> ===<span style='color:green;font-size:large;'>Success</span>";
    else echo "<br/> ===<span style='color:red;font-size:large;'>Failure</span>";
    echo "<br/><br/>DROP VIEW {$this->issv_student_scores}";
    $result = $wpdb->query("DROP VIEW $this->issv_student_scores");
    if ($result) echo "<br/> ===<span style='color:green;font-size:large;'>Success</span>";
    else echo "<br/> ===<span style='color:red;font-size:large;'>Failure</span>";
    echo "<br/><br/>DROP VIEW {$this->issv_teacher_class_access}";
    $result = $wpdb->query("DROP VIEW $this->issv_teacher_class_access");
    if ($result) echo "<br/> ===<span style='color:green;font-size:large;'>Success</span>";
    else echo "<br/> ===<span style='color:red;font-size:large;'>Failure</span>";
    echo "<br/><br/>DROP VIEW {$this->issv_teacher_name}";
    $result = $wpdb->query("DROP VIEW $this->issv_teacher_name");
    if ($result) echo "<br/> ===<span style='color:green;font-size:large;'>Success</span>";
    else echo "<br/> ===<span style='color:red;font-size:large;'>Failure</span>";

}
public function issv_create_user($username, $role, $useremail)
{
    echo "<br/><br/>Create User: {$username}  Role: {$role}";
    $user_id = username_exists($username);
    if ($user_id) echo "<br/> <span style='color:red;font-size:large;'>===User exists </span> ";
    if (!$user_id and email_exists($useremail) == false) {
        $user_id = wp_create_user($username, 'Password1', $useremail);
        if ($user_id) echo "<br/> ===<span style='color:green;font-size:large;'>Success Create</span> ";
        else print_r($user_id);
        $user_id = wp_update_user(array('ID' => $user_id, 'role' => $role, 'display_name' => $username, 'nickname' => $username, 'first_name' => $username, 'last_name' => $username));
        if ($user_id) echo "<br/> ===<span style='color:green;font-size:large;'>Success Updated</span> ";
        else print_r($user_id);
    }
    return $user_id;
}
public function issv_sqlaccount_install()
{

    global $wpdb;
    $username = 'testparent1';
    $role = 'issparentrole';
    $useremail = 'parent1@testmail.com';
    $user_id = $this->issv_create_user($username, $role, $useremail);
    if ($user_id) {

        $wpdb->insert($this->iss_userstudentmap, array('UserID' => $user_id, 'StudentID' => 1280), array("%d", "%d"));
        $wpdb->insert($this->iss_userstudentmap, array('UserID' => $user_id, 'StudentID' => 1351), array("%d", "%d"));
    }

    $username = 'testparent2';
    $role = 'issparentrole';
    $useremail = 'parent2@testmail.com';
    $user_id = $this->issv_create_user($username, $role, $useremail);
    if ($user_id) {
        $wpdb->insert($this->iss_userstudentmap, array('UserID' => $user_id, 'StudentID' => 1314), array("%d", "%d"));
    }

    $username = 'teststudent1';
    $useremail = 'student1@testmail.com';
    $role = 'issstudentrole';
    $user_id = $this->issv_create_user($username, $role, $useremail);
    if ($user_id) {
        $wpdb->insert($this->iss_userstudentmap, array('UserID' => $user_id, 'StudentID' => 1280), array("%d", "%d"));
    }

    $username = 'testteacher1';
    $useremail = 'teacher1@testmail.com';
    $role = 'issteacherrole';
    $user_id = $this->issv_create_user($username, $role, $useremail);
    if ($user_id) {
        $wpdb->insert($this->iss_userclassmap, array('UserID' => $user_id, 'ClassID' => 1, 'Access' => 'primary'), array("%d", "%d", "%s"));
    }

    $username = 'testteacher2';
    $useremail = 'teacher2@testmail.com';
    $role = 'issteacherrole';
    $user_id = $this->issv_create_user($username, $role, $useremail);
    if ($user_id) {
        $wpdb->insert($this->iss_userclassmap, array('UserID' => $user_id, 'ClassID' => 3, 'Access' => 'primary'), array("%d", "%d", "%s"));
        $wpdb->insert($this->iss_userstudentmap, array('UserID' => $user_id, 'StudentID' => 1314), array("%d", "%d"));
    }

    $username = 'testboard1';
    $useremail = 'testboard1@testmail.com';
    $role = 'issboardrole';
    $user_id = $this->issv_create_user($username, $role, $useremail);

}
public function issv_delete_user($username)
{
    echo "<br/><br/> Delete User: {$username}";
    $the_user = get_user_by('login', $username);
    if ($the_user == false) {
        echo "<br/> ===Failuer<br/>";
    } else {
        echo "<br/> ===<span style='color:green;font-size:large;'>Success</span><br/>";
        print_r($the_user);
        $uid = $the_user->ID;
        wp_delete_user($uid);
    }
}
public function issv_sqlaccount_uninstall()
{
    $username = 'testparent1';
    $this->issv_delete_user($username);

    $username = 'testparent2';
    $this->issv_delete_user($username);

    $username = 'teststudent1';
    $this->issv_delete_user($username);

    $username = 'testteacher1';
    $this->issv_delete_user($username);

    $username = 'testteacher2';
    $this->issv_delete_user($username);

    $username = 'testboard1';
    $this->issv_delete_user($username);
}
} // class

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
$my_test_page = new ISS_PreparationPlugin();
?>