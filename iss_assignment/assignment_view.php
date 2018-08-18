<?php 
$post = null;
if (isset($_GET['postid'])) {
    $postid = iss_sanitize_input($_GET['postid']);
    if (!empty($postid)) {
        $post = ISS_AssignmentService::LoadByID($postid);
        iss_write_log($post);
    }
}
if (null != $post) {
    echo "<h3> {$post->Name} </h3>";
    echo $post->Content;
}
?> 



