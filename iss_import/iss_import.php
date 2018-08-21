<?php
/*
 * Plugin Name: 25. ISS Import Students Information From CSV
 * Plugin URI: http://learnislam.org/
 * Description: <strong>Depends: ISS Common, ISS Class, ISS Roles.</strong>  Import students data from a csv file. Existing records are updated if different.
 * Author: Azra Syed
 */
function iss_student_import_menu()
{
	add_menu_page('ImportStudents', 'Import Students', 'iss_admin', 'iss_import', 'iss_import_students', 'dashicons-upload', 8);
}

add_action("admin_menu", "iss_student_import_menu");
function iss_detect_delimiter($file)
{
	$handle = @fopen($file, "r");
	$sumComma = 0;
	$sumSemiColon = 0;
	$sumBar = 0;

	if ($handle) {
		while (($data = fgets($handle, 4096)) !== false) :
			$sumComma += substr_count($data, ",");
		$sumSemiColon += substr_count($data, ";");
		$sumBar += substr_count($data, "|");
		endwhile;
	}
	fclose($handle);

	if (($sumComma > $sumSemiColon) && ($sumComma > $sumBar))
		return ",";
	else if (($sumSemiColon > $sumComma) && ($sumSemiColon > $sumBar))
		return ";";
	else
		return "|";
}
function iss_string_conversion($string)
{
	if (!preg_match('%(?:
        [\xC2-\xDF][\x80-\xBF]        # non-overlong 2-byte
    |\xE0[\xA0-\xBF][\x80-\xBF]               # excluding overlongs
    |[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}      # straight 3-byte
    |\xED[\x80-\x9F][\x80-\xBF]               # excluding surrogates
    |\xF0[\x90-\xBF][\x80-\xBF]{2}    # planes 1-3
    |[\xF1-\xF3][\x80-\xBF]{3}                  # planes 4-15
    |\xF4[\x80-\x8F][\x80-\xBF]{2}    # plane 16
    )+%xs', $string)) {
		return utf8_encode($string);
	} else
		return $string;
}
function iss_import_students()
{
	$post_filtered = filter_input_array(INPUT_POST);

	if (!iss_current_user_can_admin()) {
		wp_die(__('You are not allowed to see this content.'));
		// $acui_action_url = admin_url('options-general.php?page=' . plugin_basename(__FILE__));
	} else if (isset($post_filtered['uploadfile']))
		iss_fileupload_process();
	else {
		?>
<div>
<div style="clear: both; width: 100%;">
	<h2>Import Students from CSV</h2>
</div>
<div style="float: left; width: 80%;">
	<form method="POST" enctype="multipart/form-data" action=""
		accept-charset="utf-8" onsubmit="return check();">
		<table class="form-table" style="width: 90%">
			<tbody>
				<tr valign="top">
					<th scope="row">Important notice</th>
					<td>
						<ul>
							<li>You can upload as many times as you want for a particular
								registration period.</li>
							<li>A successful run requires ParentID, StudentID and
								registration period in every row.</li>
							<li>If a ParentID or StudentID exists for a given registration
								period, data would be updated.</li>
							<li>Columns in the CSV file can be in any order, provided that
								they have correct headings names.</li>
						</ul>
					</td>
				</tr>
				<tr class="form-field form-required">
					<th scope="row"><label for="user_login">CSV file <span
							class="description">(required)</span></label></th>
					<td><input type="file" name="uploadfiles[]" id="uploadfiles"
						size="35" class="uploadfiles" /></td>
				</tr>
				<!--                        <tr><th scope="row">Columns Names</th>
        <td>ParentID,FatherFirstName,FatherLastName,FatherEmail,FatherWorkPhone,FatherCellPhone.HomeStreetAddress.HomeCity,HomeZip,HomePhone,
        FamilySchoolStartYear,SchoolEmail,MotherFirstName,MotherLastName,MotherStreetAddress,MotherCity,MotherZip,MotherEmail,MotherHomePhone,MotherWorkPhone,
        MotherCellPhone,EmergencyContactName1,EmergencyContactPhone1,EmergencyContactName2,EmergencyContactPhone2,SpecialNeed,SpecialNeedNote,
        ShareAddress,TakePicture,StudentID,StudentFirstName,StudentLastName,RegularSchoolGrade,ISSGrade,StudentPhone,StudentEmail,StudentBirthDate,StudentGender,PaymentInstallment1,PaymentMethod1,PaymentInstallment2,PaymentMethod2,Comments</td>
        </tr>-->
				<tr valign="top">
					<th scope="row">Example</th>
					<td>Download this <a
						href="<?php echo plugins_url() . "/iss_import/test.csv "; ?>">.csv
							file</a> for column names.
					</td>
				</tr>
			</tbody>
		</table>
		<input class="button-primary" type="submit" name="uploadfile"
			id="uploadfile_btn" value="Start importing" />
	</form>
</div>
</div>
<script type="text/javascript">
    function check() {
      if (document.getElementById("uploadfiles").value == "") {
        alert("Please choose a file");
        return false;
      }
    }
  </script>
<?php

}
}
function iss_fileupload_process()
{
	$uploadfiles = $_FILES['uploadfiles'];

	if (is_array($uploadfiles)) {

		foreach ($uploadfiles['name'] as $key => $value) {
			
			// look only for uploded files
			if ($uploadfiles['error'][$key] == 0) {
				$filetmp = $uploadfiles['tmp_name'][$key];
				
				// clean filename and extract extension
				$filename = $uploadfiles['name'][$key];
				
				// get file info
				// @fixme: wp checks the file extension....
				$filetype = wp_check_filetype(basename($filename), array(
					'csv' => 'text/csv'
				));
				$filetitle = preg_replace('/\.[^.]+$/', '', basename($filename));
				$filename = $filetitle . '.' . $filetype['ext'];
				$upload_dir = wp_upload_dir();

				if ($filetype['ext'] != "csv") {
					wp_die('File must be a CSV');
					return;
				}

				/**
				 * Check if the filename already exist in the directory and rename the
				 * file if necessary
				 */
				$i = 0;
				while (file_exists($upload_dir['path'] . '/' . $filename)) {
					$filename = $filetitle . '_' . $i . '.' . $filetype['ext'];
					$i++;
				}
				$filedest = $upload_dir['path'] . '/' . $filename;

				/**
				 * Check write permissions
				 */
				if (!is_writeable($upload_dir['path'])) {
					wp_die('Unable to write to directory. Is this directory writable by the server?');
					return;
				}

				/**
				 * Save temporary file to uploads dir
				 */
				if (!@move_uploaded_file($filetmp, $filedest)) {
					wp_die("Error, the file $filetmp could not moved to : $filedest ");
					continue;
				}

				$attachment = array(
					'post_mime_type' => $filetype['type'],
					'post_title' => $filetitle,
					'post_content' => '',
					'post_status' => 'inherit'
				);

				$attach_id = wp_insert_attachment($attachment, $filedest);
				require_once(ABSPATH . "wp-admin" . '/includes/image.php');
				$attach_data = wp_generate_attachment_metadata($attach_id, $filedest);
				wp_update_attachment_metadata($attach_id, $attach_data);

				iss_import_file($filedest);
			}
		}
	}
}
function iss_import_file($file)
{
	echo "<div class=\"wrap\"> <h2>Importing students</h2>";

	set_time_limit(0);
	global $wpdb;
	$headers = array();
	$data = array();

	echo "<h3>Ready to registers</h3>";
	echo "<p>First row represents the form of sheet</p>";
	$row = 0;

	ini_set('auto_detect_line_endings', true);

	$delimiter = iss_detect_delimiter($file);

	$manager = new SplFileObject($file);
	while ($data = $manager->fgetcsv($delimiter)) {
		if (empty($data) || empty($data[0])) {
			continue;
		}
		foreach ($data as $key => $value) {
			$data[$key] = trim($value);
		}

		for ($i = 0; $i < count($data); $i++) {
			$data[$i] = iss_string_conversion($data[$i]);
		}

		if ($row == 0) {
			foreach ($data as $element)
				$headers[] = $element;

			$columns = count($data);

			echo "<h3>Inserting and updating data</h3>";
			echo "<table border=\"1\"> <tr> <th>Row</th>";

			foreach ($headers as $element) echo "<th>" . $element . "</th>"; 
            echo "</tr>";				
			$row ++;

		} else {
			if (count ( $data ) != $columns) { // if number of columns is not the same that columns in header
				echo '<script>alert("Row number: ' . $row . ' has not the same columns than header, we are going to skip");</script>';
				continue;
			}
			
			for($i = 0; $i < $columns; $i ++) {
				
				if (in_array ( $headers [$i], ISS_Student::GetFieldsArray() )) {
					$sdata [$headers [$i]] = $data [$i];
				}
			}
			
			// echo '<br> date<br>';  var_dump($sdata);
			
			$studentviewid = iss_sanitize_input ( $sdata ["StudentViewID"] );
			
			if ((NULL == $studentviewid) || empty ( $studentviewid ) ) {
				echo "<span style=\"color:red;\"><br>ABORTED: Row: {$row} StudentViewID:{$studentviewid} </span>";
				break; // next record
			}
			
			echo "<tr><td colspan=\"{$columns}\" >";
			echo "<br>StudentViewID: {$studentviewid} ";
			
			$databaserow = ISS_StudentService::LoadByStudentViewID( $studentviewid );
			if (NULL == $databaserow) {
				echo "<span style=\"color:green;\"><br>RECORD ADDED  </span>" . ISS_StudentService::AddStudent( $sdata ); 
			} else {
				$presult =  iss_import_student_update ( $row, $databaserow, $sdata );
				if ($presult != 0)
					echo "<span style=\"color:orange;\"><br/>Update Parent Result {$presult} </span>"; // ISS TEST
			}
			
			echo "</td></tr>";
			flush ();
			
			 //if ($row == 1) return; // TEST WITH JUST FIRST RECORD
			
			$row ++;
		}
	}

	$pageurl = get_admin_url() . 'users.php?page=issvactlist';
	echo "</table> <br /> <p> Process finished you can go <a href=\"{$pageurl}\">here to see results</a></p>";

	// fclose($manager);
	ini_set ( 'auto_detect_line_endings', FALSE );
	echo "</div>"; 
}
function iss_import_student_update($rowid, $databaserow, $data)
{
	$changed = false;
	$errors = array();
	$changedfields = array();
	
	// / VALIDATE INPUT - start
	foreach (ISS_Student::GetFieldsArray() as $fieldname) { 
		$inputval = '';
		if (array_key_exists($fieldname, $data)) {
			$inputval = iss_sanitize_input($data[$fieldname]);
		}
				
		// TODO: FLOAT COMPARISON IS NOT WORKING
		if (!empty($inputval) && iss_field_valid($fieldname, $inputval, $errors, '') && !iss_match_values($fieldname, $inputval, $databaserow->$fieldname)) {
			echo "<br/>  Field: {$fieldname}   input: {$inputval} changed   new: {$databaserow->$fieldname}"; // ISS TEST

			$databaserow->$fieldname = $inputval;
			$changedfields[] = $fieldname;
			$changed = true;
		}
	}
	// / CONSOLIDATE ERRORS
	if (!empty($errors)) {
		$errorstring = '<br>ABORTED:Error Summay.';
		foreach ($errors as $field => $error)
			$errorstring = $errorstring . '<br/>' . $error;
		echo $errorstring; // ISS TEST
	} else	// / UPDATE DB start
	{
		if ($changed) {
			return ISS_StudentService::UpdateStudent( $changedfields, $databaserow );
		}
	}

	return 0;
}
function iss_match_values($fieldname, $value1, $value2)
{
	$fields_with_types = iss_fields_types();

	$type = $fields_with_types[$fieldname];
	if ($type == 'float') {
		$value11 = intval($value1);
		$value22 = intval($value2);
		//echo $value11 . ' ' . $value22;
		return ($value11 == $value22);
	}
		
	return (strcmp($value1, $value2) == 0);
}

?>