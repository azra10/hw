
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
    echo "<h3> {$post->Name} </h3>";


    echo "<div class='row'>";
    echo "<div class='col-md-3'><strong>Class</strong>: Grade{$post->ISSGrade}-{$post->Subject}</div>";
    echo "<div class='col-md-3'><strong>Due Date</strong>: {$post->DueDate}</div>";
    echo "<div class='col-md-3'><strong>Possible Point: {$post->PossiblePoints}</div>";
    echo "<div class='col-md-3'>";
    ?>
    <script>
    var pfHeaderImgUrl = '';
    var pfHeaderTagline = '';
    var pfdisableClickToDel = 0;
    var pfHideImages = 0;
    var pfImageDisplayStyle = 'right';
    var pfDisablePDF = 0;
    var pfDisableEmail = 0;
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
        src="//cdn.printfriendly.com/buttons/printfriendly-pdf-email-button-md.png" alt="Print Friendly and PDF"/></a>
    
        <?php
        echo "</div></div>";

        echo "<hr/><div class ='row'><div class='col-md-12'>";
        $shortcodetext = '[post-content id=' . $postid . ']';
        echo do_shortcode($shortcodetext);
        echo "</div></div>";
    }
    ?> 
</div>





