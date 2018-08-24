
<div class="container">
<?php 
$cid = null;
$backurl = null;
if (isset($_GET['cid'])) {
    $cid = iss_sanitize_input($_GET['cid']);
    $backurl = admin_url('admin.php?page=issvalist') . "&cid={$cid}";
}
iss_show_heading_with_backurl("Assignment", $backurl);


$post = null;
if (isset($_GET['post'])) {
    $postid = iss_sanitize_input($_GET['post']);
    if (!empty($postid)) {
        $post = ISS_AssignmentService::LoadByID($postid);
        iss_write_log($post);
    }
}
if (null != $post) {
    echo "<h3> {$post->Title} </h3>";


    echo "<div class='row'>";
    echo "<div class='col-md-3'><strong>Class</strong>: Grade{$post->ISSGrade}-{$post->Subject}</div>";
    echo "<div class='col-md-3'><strong>Due Date</strong>: {$post->DueDate}</div>";
    echo "<div class='col-md-3'><strong>Possible Point:</strong> {$post->PossiblePoints}</div>";
    echo "<div class='col-md-3'>";
    ?>
    <script>
    var pfHeaderImgUrl = '';
    var pfHeaderTagline = '';
    var pfdisableClickToDel = 0;
    var pfHideImages = 0;
    var pfImageDisplayStyle = 'right';
    var pfDisablePDF = 0;
    var pfDisableEmail = 1;
    var pfDisablePrint = 0;
    var pfCustomCSS = '';
    var pfBtVersion='2';(function(){
        var js,pf;
        pf=document.createElement('script');pf.type='text/javascript';pf.src='//cdn.printfriendly.com/printfriendly.js';
        document.getElementsByTagName('head')[0].appendChild(pf)})();
        </script>
        <a href="https://www.printfriendly.com" style="color:#6D9F00;text-decoration:none;" class="printfriendly" 
        onclick="window.print();return false;" 
        title="Printer Friendly and PDF"><img style="border:none;-webkit-box-shadow:none;box-shadow:none;" 
        src="//cdn.printfriendly.com/buttons/printfriendly-pdf-button-nobg-md.png" alt="Print Friendly and PDF"/></a>
    
    </div>
    </div>

    <hr/>
    <div class ='row'>
    <div class='col-md-12'>
    <?php

    if (strncmp($post->Title, 'Participation', strlen('Participation')) === 0) { ?>
            <h3>Class Presence and Participation</h3> 
            <span>Class presence and participation points are given to encourage active class participation and discussion.</span>
        <?php 
    } else if (strncmp($post->Title, 'Attendance', strlen('Attendance')) === 0) {
        ?>
            <h3>Islamic School of Silicon Valley Guidelines and Regulations</h3>
            <h4>F. Attendance</h4>
            <span>Attendance Regular attendance is very important as it enables students to fully benefit from the Islamic School. Attending school every week ensures that each student keeps up with the teacher’s lesson plan and assignment schedule. It also instills the sense that Islamic education is at least as important as the other activities in his/her life, and therefore, should be taken seriously.
            </span>
            <ul>
                 <li>Student attendance is taken each Sunday in every grade for both the Quranic Studies and Islamic Studies class sessions.</li>
                 <li>For Leave of Absence, both the Quranic &amp; Islamic studies teachers should be notified. All the missed activities (for example homework, notes, etc) should be made up before returning to the class.</li>
                 <li>The attendance record of each student will be included on his/her final Report Card.</li>
                 <li>The names and telephone numbers of a student’s teacher will be made available to parents so they can provide proper notification about necessary absences.</li>
            </ul>
            <strong>Enforcement</strong>
            <span style='text-decoration: underline;'>3 Consecutive Un-Excused Absences or a large number of non-contiguous absences during the school year, could result in the student’s Suspension or Expulsion from the school.</span>           
            <?php

        } else {

            $content_post = get_post($postid);
            $content = $content_post->post_content;
            $content = apply_filters('the_content', $content);
            $content = str_replace(']]>', ']]&gt;', $content);
            echo $content;
        }
    }
    ?> 
    </div>
   </div>
</div>





