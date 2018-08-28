<?php
iss_write_log($_POST);
check_admin_referer('iss-email-teacher-form-page', '_wpnonce-iss-email-teacher-form-page');

if (isset($_POST['postid']) && !empty($_POST['postid'])) {
    $postid = $_POST['postid'];
}
if (isset($_POST['title']) && !empty($_POST['title'])) {
    $title = $_POST['title'];
} else {
    $errTitle = ' required';
}
if (isset($_POST['content']) && !empty($_POST['content'])) {
    $content = $_POST['content'];
} else {
    $errContent = ' required';
}
if (isset($_POST['duedate']) && !empty($_POST['duedate'])) {
    $duedate = $_POST['duedate'];
} else {
    $errDuedate = ' required';
}
if (isset($_POST['possiblepoints']) && !empty($_POST['possiblepoints'])) {
    $possiblepoints = $_POST['possiblepoints'];
} else {
    $errPossiblepoints = ' required';
}

if (!$errTitle) {

    // CREATE/UPDATE THE POST
    $postid = iss_create_post($postid, $category, $content, $title);

        // CREATE THE ATTACHEMNT
    $error = null;
    if (null != $postid) {
        iss_write_log($_FILES);
        if (!empty($_FILES)) {
            $file = $_FILES['file1'];
            if (!empty($file['name'])) {
                $error = upload_user_file($file, $postid);
            }
        }         
        // ADD/UPDATE ASSINGMENT TABLE
        iss_update_assignment($postid, $classid, $category, $possiblepoints, $duedate);
    }

    if (!$errTitle && !$errPossiblepoints && !$errDuedate) {

        iss_show_heading("Assigment Saved");
        echo "<div class='text-error'> {$error} </div>";
        echo "<table class='table'><tr>";
        echo "<td><a href='admin.php?page=issvaadd&post={$postid}&cid={$classid}' class='btn btn-primary'>Continue Editing</a></td>";
        echo "<td><a href='admin.php?page=issvaview&post={$postid}cid={$classid}' class='btn btn-primary'>View Assignment</a></td>";
        echo "<td><a href='admin.php?page=issvalist&cid={$classid}' class='btn btn-primary'>Finish Editing</a></td></tr></table>";

        exit;
    }

}

function upload_user_file($file = array(), $postid)
{

    require_once(ABSPATH . 'wp-admin/includes/admin.php');

    $file_return = wp_handle_upload($file, array('test_form' => false));

    iss_write_log($file_return);
    if (isset($file_return['error']) || isset($file_return['upload_error_handler'])) {
        iss_write_log("error uploading file: " . $file_return['error']);
        return $file_return['error'];
    } else {

        $filename = $file_return['file'];

        $attachment = array(
            'post_mime_type' => $file_return['type'],
            'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
            'post_content' => '',
            'post_status' => 'inherit',
            'guid' => $file_return['url'],
            'post_parent' => $postid
        );

        $attachment_id = wp_insert_attachment($attachment, $file_return['url']);

        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attachment_data = wp_generate_attachment_metadata($attachment_id, $filename);
        wp_update_attachment_metadata($attachment_id, $attachment_data);

        iss_write_log("uploaded file successfully");      
        return null;
    }
}

function iss_create_post($postid, $category, $content, $title)
{
    $post_arr = array(
        'post_content' => $content,
        'comment_status' => 'closed',
        'ping_status' => 'closed',
        'post_category' => $category,
        'post_title' => $title,
        'post_status' => 'publish',
        'post_name' => $category,
        'post_type' => 'iss_assignment'
    );
    if (!empty($postid)) $post_arr['ID'] = $postid;
        // Set the post ID so that we know the post was created successfully

    $postid = wp_insert_post($post_arr, true);
    iss_write_log("Post updated {$postid}");
    return $postid;

}
function iss_update_assignment($postid, $classid, $category, $possiblepoints, $duedate)
{
    $assingment = ISS_AssignmentService::LoadByID($postid);
    if (null != $assingment) {
        ISS_AssignmentService::Update($postid, $possiblepoints, $duedate);
    } else {
        ISS_AssignmentService::Add($postid, $classid, $category, $possiblepoints, $duedate);
        wp_set_post_terms($postid, $category);
    }
}
?>