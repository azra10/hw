<?php
/*
 * Plugin Name: 01. ISS Short Codes
 * 
 * Description: <strong>Depends: ISS Common, ISS Class,  ISS Roles.</strong>   Lists assignments of a class based on the roles <strong>admin, board, secretary, teacher, parent, student and test </strong>.  On activation, adds a 'Students' link to the class list page.
 
 * Version: 1.0
 * Author: Azra Syed
 * 
 */
function iss_attendance_policy_shortcode()
{
?>
    <h3>Islamic School of Silicon Valley Guidelines and Regulations</h3>
    <h4>F. Attendance</h4>
    Attendance Regular attendance is very important as it enables students to fully benefit from the Islamic School. Attending school every week ensures that each student keeps up with the teacher’s lesson plan and assignment schedule. It also instills the sense that Islamic education is at least as important as the other activities in his/her life, and therefore, should be taken seriously.
    <ul>
         <li>Student attendance is taken each Sunday in every grade for both the Quranic Studies and Islamic Studies class sessions.</li>
         <li>For Leave of Absence, both the Quranic &amp; Islamic studies teachers should be notified. All the missed activities (for example homework, notes, etc) should be made up before returning to the class.</li>
         <li>The attendance record of each student will be included on his/her final Report Card.</li>
         <li>The names and telephone numbers of a student’s teacher will be made available to parents so they can provide proper notification about necessary absences.</li>
    </ul>
    <strong>Enforcement</strong>
    
    <span style="text-decoration: underline;">3 Consecutive Un-Excused Absences or a large number of non-contiguous absences during the school year, could result in the student’s Suspension or Expulsion from the school.</span> 
    
<?php
}
add_shortcode('iss_attendance_policy', 'iss_attendance_policy_shortcode');

function iss_participation_policy_shortcode()
{
?>
    <b><i>Class Presence and Participation</i></b>.  Class presence and participation points are given to encourage active class participation and discussion.
<?php
}
add_shortcode('iss_participation_policy', 'iss_participation_policy_shortcode');
?>


