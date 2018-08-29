<?php
/*
 * Plugin Name: 60. ISS Email
 * 
 * Description: <strong>Depends: ISS Common, ISS Roles.</strong>   Send Email to users
 * Version: 1.0
 * Author: Azra Syed
 * 
 */

function iss_email_class_page()
{
    include(plugin_dir_path(__FILE__) . "/email_class.php");
}
function iss_email_student_page()
{
    include(plugin_dir_path(__FILE__) . "/email_student.php");
}
function iss_email_teacher_page()
{
    include(plugin_dir_path(__FILE__) . "/email_teacher.php");
}
function iss_email_admin_page()
{
    include(plugin_dir_path(__FILE__) . "/email_admin.php");
}
function iss_email_register_menu_page()
{
    //add_menu_page( string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '', string $icon_url = '', int $position = null )
    $my_pages[] = add_menu_page('Email Admin', 'Email Admin', 'read', 'issvemailadmin', 'iss_email_admin_page', 'dashicons-email', 6);
    $my_pages[] = add_submenu_page(null, 'Email Student', 'Email Student', 'read', 'issemailstudent', 'iss_email_student_page');
    $my_pages[] = add_submenu_page(null, 'Email Class', 'Email Class', 'read', 'issemailclass', 'iss_email_class_page');
    $my_pages[] = add_submenu_page(null, 'Email Teacher', 'Email Teacher', 'read', 'issemailteacher', 'iss_email_teacher_page');
  

    foreach ($my_pages as $my_page) {
        add_action('load-' . $my_page, 'iss_load_admin_custom_css');
    }
}
add_action('admin_menu', 'iss_email_register_menu_page');


?>