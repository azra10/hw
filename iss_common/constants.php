<?php
if (!function_exists('iss_required_fields'))
{
	function iss_required_fields() {
		
		return array("RegistrationYear");
	}
}
if (!function_exists('iss_field_displaynames')) {

	function iss_field_displaynames()
	{
		global $iss_field_displaynames_;
		if ($iss_field_displaynames_ == null)
			$iss_field_displaynames_ = array(
			"ParentID" => "ParentID",
			"ParentStatus" => "Parent Status",
			"ParentNew" => "Parent New",
			"RegistrationYear" => "Registration Period",
			"created" => "Create Date",
			"updated" => "Last Update Date",

			"FatherFirstName" => "Father First Name",
			"FatherLastName" => "Father Last Name",
			"FatherEmail" => "Father Email",
			"FatherWorkPhone" => "Father Work Phone",
			"FatherCellPhone" => "Father Cell Phone",
			"SchoolEmail" => "School Email",
			"FamilySchoolStartYear" => "Family School Start Year",
			"MotherFirstName" => "Mother First Name",
			"MotherLastName" => "Mother Last Name",
			"MotherEmail" => "Mother Email",
			"MotherWorkPhone" => "Mother Work Phone",
			"MotherCellPhone" => "Mother Cell Phone",

			"HomeStreetAddress" => "Home Street Address",
			"HomeCity" => "Home City",
			"HomeZip" => "Home Zip",
			"HomePhone" => "Home Phone",
			"MotherStreetAddress" => "Mother Street Address",
			"MotherCity" => "Mother City",
			"MotherZip" => "Mother Zip",
			"MotherHomePhone" => "Mother Home Phone",
			"ShareAddress" => "Share Address",
			"TakePicture" => "Take Picture",

			"EmergencyContactName1" => "Emergency Contact Name1",
			"EmergencyContactPhone1" => "Emergency Contact Phone 1",
			"EmergencyContactName2" => "Emergency Contact Name 2",
			"EmergencyContactPhone2" => "Emergency Contact Phone 2",

			"RegistrationComplete" => "Registration Complete",
			"RegistrationCode" => "Registration Code",
			"RegistrationExpiration" => "Registration Expiration Date",
			"SpecialNeedNote" => "Special Need Note",
			"Comments" => "Comments",
			"PaymentInstallment1" => "Payment Installment 1",
			"PaymentMethod1" => "Payment Method 1",
			"PaymentDate1" => "Payment Date 1",
			"TotalAmountDue" => "Total Amount Due",
			"FinancialAid" => "Financial Aid",
			"PaymentInstallment2" => "Payment Installment 2",
			"PaymentMethod2" => "Payment Method 2",
			"PaymentDate2" => "Payment Date 2",
			"PaidInFull" => "Paid In Full",

			"StudentID" => "StudentID",
			"StudentFirstName" => "Student First Name",
			"StudentLastName" => "Student Last Name",
			"RegularSchoolGrade" => "Regular School Grade",
			"ISSGrade" => "Islamic School Grade",
			"StudentStatus" => "Student Status",
			"StudentNew" => "Student New",
			"StudentEmail" => "Student Email",
			"StudentBirthDate" => "Student Birth Date",
			"StudentGender" => "Student Gender",
			"created" => "Create Date",
			"updated" => "Last Update Date",

			"GradingPeriodID" => "GradingPeriodID",
			"GradingPeriod" => "Grading Period",
			"StartDate" => "Start Date",
			"EndDate" => "End Date",

			"TeacherID" => "TeacherID",
			"Name" => "Name",
			"Email" => "Email",
			"Status" => "Status",

			"ClassID" => "ClassID",
			"Subject" => "Subject"

		);
		return $iss_field_displaynames_;
	}
}
if (!function_exists('iss_fields_lengths')) {

	function iss_fields_lengths()
	{
		global $iss_field_lengths_;
		if ($iss_field_lengths_ == null)
			$iss_field_lengths_ = array(
			"ParentID" => 11,
			"RegistrationYear" => 10,
			"ParentStatus" => 10,
			"ParentNew" => 3,
			"FatherFirstName" => 100,
			"FatherLastName" => 100,
			"FatherEmail" => 100,
			"FatherWorkPhone" => 20,
			"FatherCellPhone" => 20,
			"MotherFirstName" => 100,
			"MotherLastName" => 100,
			"MotherEmail" => 100,
			"MotherWorkPhone" => 20,
			"MotherCellPhone" => 20,
			"SchoolEmail" => 100,
			"FamilySchoolStartYear" => 10,

			"created" => 20,
			"updated" => 20,
			"HomeStreetAddress" => 200,
			"HomeCity" => 100,
			"HomeZip" => 5,
			"HomePhone" => 20,
			"MotherStreetAddress" => 200,
			"MotherCity" => 100,
			"MotherZip" => 5,
			"MotherHomePhone" => 20,
			"ShareAddress" => 5,
			"TakePicture" => 5,

			"EmergencyContactName1" => 100,
			"EmergencyContactPhone1" => 20,
			"EmergencyContactName2" => 100,
			"EmergencyContactPhone2" => 20,

			"RegistrationComplete" => 10,
			"RegistrationCode" => 100,
			"RegistrationExpiration" => 10,
			"PaymentInstallment1" => 10,
			"PaymentMethod1" => 20,
			"PaymentDate1" => 10,
			"PaymentDate2" => 10,
			"TotalAmountDue" => 9,
			"FinancialAid" => 3,
			"PaymentInstallment2" => 10,
			"PaymentMethod2" => 20,
			"PaymentDate2" => 10,
			"Comments" => 300,
			"PaidInFull" => "3",
			"SpecialNeedNote" => 300,

			"StudentID" => 11,
			"StudentFirstName" => 100,
			"StudentLastName" => 100,
			"RegularSchoolGrade" => 2,
			"ISSGrade" => 2,
			"StudentEmail" => 100,
			"StudentBirthDate" => 10,
			"StudentGender" => 1,
			"StudentStatus" => 10,
			"StudentNew" => 3,

			"GradingPeriodID" => 11,
			"GradingPeriod" => 11,
			"StartDate" => 10,
			"EndDate" => 10,

			"TeacherID" => 11,
			"Name" => 100,
			"Email" => 100,
			"Status" => 10,

			"ClassID" => 11,
			"Subject" => 2
		);
		return $iss_field_lengths_;
	}
}
if (!function_exists('iss_fields_types')) {

	function iss_fields_types()
	{
		global $iss_field_types_;
		if (null == $iss_field_types_)
			$iss_field_types_ = array(
			"ParentID" => "int",
			"RegistrationYear" => "registrationyear",
			"created" => "datetime",
			"updated" => "datetime",
			"ParentStatus" => "string",
			"ParentNew" => "string",
			"FatherFirstName" => "string",
			"FatherLastName" => "string",
			"FatherEmail" => "string",
			"FatherWorkPhone" => "string",
			"FatherCellPhone" => "string",
			"MotherFirstName" => "string",
			"MotherLastName" => "string",
			"MotherEmail" => "string",
			"MotherWorkPhone" => "string",
			"MotherCellPhone" => "string",
			"SchoolEmail" => "string",

			"HomeStreetAddress" => "string",
			"HomeCity" => "string",
			"HomeZip" => "int",
			"HomePhone" => "string",
			"MotherStreetAddress" => "string",
			"MotherCity" => "string",
			"MotherZip" => "int",
			"MotherHomePhone" => "string",
			"ShareAddress" => "string",
			"TakePicture" => "string",

			"EmergencyContactName1" => "string",
			"EmergencyContactPhone1" => "string",
			"EmergencyContactName2" => "string",
			"EmergencyContactPhone2" => "string",

			"RegistrationComplete" => "string",
			"RegistrationCode" => "string",
			"RegistrationExpiration" => "date",
			"SpecialNeedNote" => "text",
			"PaymentInstallment1" => "float",
			"PaymentMethod1" => "string",
			"PaymentDate1" => "date",
			"TotalAmountDue" => "float",
			"FinancialAid" => "string",
			"PaymentInstallment2" => "float",
			"PaymentMethod2" => "string",
			"PaymentDate2" => "date",
			"Comments" => "string",
			"FamilySchoolStartYear" => "string",
			"PaidInFull" => "string",

			"StudentID" => "int",
			"StudentFirstName" => "string",
			"StudentLastName" => "string",
			"RegularSchoolGrade" => "string",
			"ISSGrade" => "string",
			"StudentEmail" => "string",
			"StudentBirthDate" => "date",
			"StudentGender" => "string",
			"StudentStatus" => "string",
			"StudentNew" => "string",
			"ChangeSetID" => "string",

			"GradingPeriodID" => "int",
			"GradingPeriod" => "int",
			"StartDate" => "date",
			"EndDate" => "date",

			"TeacherID" => "int",
			"Name" => "string",
			"Email" => "string",
			"Status" => "string",

			"ClassID" => "string",
			"Subject" => "string"

		);
		return $iss_field_types_;
	}
}
?>