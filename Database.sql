
ALTER TABLE lh3i_iss_userstudentmap ADD LastLogin DATETIME NULL DEFAULT NULL AFTER Access;
ALTER TABLE lh3i_iss_userclassmap ADD LastLogin DATETIME NULL DEFAULT NULL AFTER Access;

change teachers permission FROM write to primary

local_iss_userclassmap
    - Make USerID and ClassID primary key
    - Two Index UserID and ClassID
    - Delete UCMapID
local_iss_userstudentmap
    - Make UserID and student ID primary key
    - Two Index UserID and StudentID
    - Delete USMAPID

	CREATE  VIEW lh3i_issv_classes AS 
    SELECT C.ClassID AS ClassID,C.RegistrationYear AS RegistrationYear,C.ISSGrade AS ISSGrade,C.Subject AS Subject,C.Status AS Status,U.ID AS UserID,U.user_email AS UserEmail,M.Access AS Access,U.display_name AS Teacher,M.LastLogin AS LastLogin 
    FROM lh3i_iss_class C left join lh3i_iss_userclassmap M on M.ClassID = C.ClassID left join lh3i_users U on M.UserID = U.ID
	
    CREATE  VIEW lh3i_issv_class_assignments AS 
    SELECT d.ClassID AS ClassID,d.DueDate AS DueDate,d.Category AS Category,d.PossiblePoints AS PossiblePoints,c.ISSGrade AS ISSGrade,c.RegistrationYear AS RegistrationYear,c.Subject AS Subject,p.ID AS ID,p.post_author AS post_author,p.post_date AS post_date,p.post_content AS post_content,p.post_title AS post_title,p.post_status AS post_status,p.post_name AS post_name,p.guid AS guid,p.post_type AS post_type 
    FROM lh3i_iss_assignment d join lh3i_posts p join lh3i_iss_class c 
    WHERE d.ID = p.ID and c.ClassID = d.ClassID
	
    CREATE  VIEW lh3i_issv_class_students AS 
    SELECT C.ClassID AS ClassID,S.StudentViewID AS StudentViewID,S.StudentID AS StudentID,S.StudentFirstName AS StudentFirstName,S.StudentLastName AS StudentLastName,S.StudentGender AS StudentGender 
    FROM lh3i_iss_class C join lh3i_iss_student S 
    WHERE S.ISSGrade = C.ISSGrade and S.StudentStatus = 'active' and C.Status = 'active' and S.RegistrationYear = C.RegistrationYear
	
    CREATE  VIEW lh3i_issv_student_accounts AS 
    SELECT S.StudentID AS StudentID,S.StudentViewID AS StudentViewID,S.RegistrationYear AS RegistrationYear,S.ParentID AS ParentID,S.FatherFirstName AS FatherFirstName,S.FatherLastName AS FatherLastName,S.FatherEmail AS FatherEmail,S.MotherFirstName AS MotherFirstName,S.MotherLastName AS MotherLastName,S.MotherEmail AS MotherEmail,S.StudentFirstName AS StudentFirstName,S.StudentLastName AS StudentLastName,S.StudentGender AS StudentGender,S.StudentEmail AS StudentEmail,S.ISSGrade AS ISSGrade,M.UserID AS UserID,U.user_email AS UserEmail,M.Access AS Access,U.user_nicename AS NiceName,M.LastLogin AS LastLogin,S.StudentStatus AS StudentStatus 
    FROM lh3i_iss_student S left join lh3i_iss_userstudentmap M on M.StudentID = S.StudentID left join lh3i_users U on U.ID = M.UserID
	
    CREATE  VIEW lh3i_issv_student_class_access AS 
    SELECT C.ClassID AS ClassID,S.RegistrationYear AS RegistrationYear,C.ISSGrade AS ISSGrade,C.Subject AS Subject,M.UserID AS UserID,M.Access AS Access 
    FROM lh3i_iss_student S join lh3i_iss_userstudentmap M join lh3i_iss_class C 
    WHERE M.StudentID = S.StudentID and C.ISSGrade = S.ISSGrade and C.Status = 'active' and S.StudentStatus = 'active' and C.RegistrationYear = S.RegistrationYear 
	
    CREATE  VIEW lh3i_issv_student_lastlogin AS 
    SELECT lh3i_iss_userstudentmap.StudentID AS StudentID,max(lh3i_iss_userstudentmap.LastLogin) AS LastLogin FROM lh3i_iss_userstudentmap 
    WHERE lh3i_iss_userstudentmap.LastLogin is not null group by lh3i_iss_userstudentmap.StudentID
	
    CREATE  VIEW lh3i_issv_teacher_class_access AS 
    SELECT C.ClassID AS ClassID,C.RegistrationYear AS RegistrationYear,C.ISSGrade AS ISSGrade,C.Subject AS Subject,M.UserID AS UserID,M.Access AS Access 
    FROM lh3i_iss_class C join lh3i_iss_userclassmap M 
    WHERE C.Status = 'active' and C.ClassID = M.ClassID
	
    CREATE  VIEW lh3i_issv_teacher_name AS 
    SELECT M.ClassID AS ClassID,M.UserID AS UserID,U.display_name AS Teacher,M.Access AS Access 
    FROM lh3i_iss_userclassmap M join lh3i_users U 
    WHERE U.ID = M.UserID
	

Remove post content short code

Date: 8/27

ALTER TABLE `local_iss_student` ADD `SchoolEmail` VARCHAR(100)  NULL  AFTER `ISSGrade`;

