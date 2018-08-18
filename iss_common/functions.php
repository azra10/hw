<?php
require_once(plugin_dir_path(__FILE__) . "../iss_common/class_gradingperiod.php");
require_once(plugin_dir_path(__FILE__) . "../iss_common/class_permission.php");
require_once(plugin_dir_path(__FILE__) . "../iss_common/constants.php");
require_once(plugin_dir_path(__FILE__) . "../iss_common/function_registration.php");
require_once(plugin_dir_path(__FILE__) . "../iss_common/function_validate.php");

if (!function_exists('iss_write_log')) {
    function iss_write_log($log)
    {
        if (true === WP_DEBUG) {

            if (is_array($log) || is_object($log)) {
                error_log(get_current_user() . ' ' . print_r($log, true));
            } else {
                error_log(get_current_user() . ' ' . $log);
            }
        }
    }
}
if (!function_exists('iss_sanitize_input')) {

    function iss_sanitize_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}
if (!function_exists('load_custom_iss_style')) {
    // Add custom CSS to plugin pages
    function load_custom_iss_style()
    {
        define('ISS_COMMON_URL', plugin_dir_url(__FILE__));
        wp_register_script('custom_iss_form_script', ISS_COMMON_URL . '/js/iss_form.js');
        wp_enqueue_script('custom_iss_form_script');

        wp_register_script('jquery1', '//code.jquery.com/jquery-3.3.1.js', array('jquery'), true);
        wp_enqueue_script('jquery1'); // enqueue datepicker from WP
        wp_register_script('datatables', '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js', array('jquery'), true);
        wp_enqueue_script('datatables');
       
       wp_register_script('datatables_bootstrap', '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js', array('jquery'), true);
       wp_enqueue_script('datatables_bootstrap');

       wp_register_style('bootstrap_style', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');
       wp_enqueue_style('bootstrap_style');
     
       wp_register_style('datatables_style', '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css');
       wp_enqueue_style('datatables_style');

       wp_register_style('fontawesome_style', '//use.fontawesome.com/releases/v5.2.0/css/all.css');
       wp_enqueue_style('fontawesome_style');

    }
}

function iss_datepicker_enqueue()
{
    wp_enqueue_script('jquery-ui-datepicker'); // enqueue datepicker from WP
    wp_enqueue_style('jquery-ui-style', '//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.css', true);
}
if (!function_exists('iss_load_admin_custom_css')) {

    function iss_load_admin_custom_css()
    {
        add_action('admin_enqueue_scripts', 'load_custom_iss_style');
    }
}

if (!function_exists('iss_show_heading')) {
    function iss_show_heading($message, $url = null)
    {
        $regyear = iss_registration_period();
        //echo "<form><input class=\"btn btn-primary\" type=\"button\" value=\"Back\" onclick=\"history.back()\"></form>";
        if (!empty($message) && !empty($url)) {
            echo "<h3>{$message} ({$regyear}) <a href=\"{$url}\" class=\"btn btn-primary\"> Add New</a></h3>";
        } elseif (!empty($message)) {
            echo "<h3>{$message} ({$regyear}) </h3>";
        }
    }
}
if (!function_exists('is_student_plugin_active')) {
    function is_student_plugin_active()
    {
        return (is_plugin_active("iss_student/iss_student.php"));
    }
}
if (!function_exists('is_assignment_plugin_active')) {
    function is_assignment_plugin_active()
    {
        return (is_plugin_active("iss_assignment/iss_assignment.php"));
    }
}

function iss_remove_dashboard_widgets()
{
    if (!current_user_can('administrator')) {
        remove_meta_box('dashboard_right_now', 'dashboard', 'normal');   // Right Now
        remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal'); // Recent Comments
        remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');  // Incoming Links
        remove_meta_box('dashboard_plugins', 'dashboard', 'normal');   // Plugins
        remove_meta_box('dashboard_quick_press', 'dashboard', 'side');  // Quick Press
        remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side');  // Recent Drafts
        remove_meta_box('dashboard_primary', 'dashboard', 'side');   // WordPress blog
        remove_meta_box('dashboard_secondary', 'dashboard', 'side');   // Other WordPress News
        remove_meta_box('dashboard_activity', 'dashboard', 'normal');
        remove_action('welcome_panel', 'wp_welcome_panel'); //'Welcome' panel
    }
}
function iss_remove_meta_boxes()
{
    if (!current_user_can('administrator')) {

        remove_meta_box('linktargetdiv', 'link', 'normal');
        remove_meta_box('linkxfndiv', 'link', 'normal');
        remove_meta_box('linkadvanceddiv', 'link', 'normal');
        remove_meta_box('postexcerpt', 'post', 'normal');
        remove_meta_box('trackbacksdiv', 'post', 'normal');
        remove_meta_box('postcustom', 'post', 'normal');
        remove_meta_box('commentstatusdiv', 'post', 'normal');
        remove_meta_box('commentsdiv', 'post', 'normal');
        remove_meta_box('revisionsdiv', 'post', 'normal');
        remove_meta_box('authordiv', 'post', 'normal');
        remove_meta_box('sqpt-meta-tags', 'post', 'normal');
        remove_meta_box('slugdiv', 'post', 'normal'); // Slug metabox
        remove_meta_box('postimagediv', 'post', 'normal'); //Featured image metabox
        remove_meta_box('formatdiv', 'post', 'normal'); // Formats metabox
        remove_meta_box('categorydiv', 'post', 'normal'); // Formats metabox
        remove_meta_box('tagsdiv-post_tag', 'post', 'normal'); // Tags metabox
    }
}
function iss_remove_menu_items()
{
    if (!current_user_can('administrator')) {
        //remove_menu_page( 'index.php' );                  //Dashboard
        //remove_menu_page( 'jetpack' );                    //Jetpack* 
        //remove_menu_page( 'options-general.php' );        //Settings
        //remove_menu_page( 'upload.php' );                 //Media
        //remove_menu_page( 'themes.php' );                 //Appearance
        //remove_menu_page( 'plugins.php' );                //Plugins
        // remove_menu_page( 'users.php' );                  //Users
        remove_menu_page('edit.php');                   //Posts
        remove_menu_page('edit.php?post_type=page');    //Pages
        remove_menu_page('edit.php?post_type=iss_assignment');    //Pages
        remove_menu_page('edit-comments.php');          //Comments
        remove_menu_page('tools.php');                  //Tools
        remove_menu_page('gutenberg');    //Pages
    }
}
?>
