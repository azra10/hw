<?php
/*
 * Plugin Name: 90. ISS Student Accounts
 * 
 * Description: <strong>Depends: ISS Common, ISS Class, ISS Assignment, ISS Roles.</strong>   Lists assignments of a class based on the roles <strong>admin, board, secretary, teacher, parent, student and test </strong>.  On activation, adds a 'Students' link to the class list page.
 
 * Version: 1.0
 * Author: Azra Syed
 * 
 */
require_once(plugin_dir_path(__FILE__) . "../iss_common/functions.php");
require_once(plugin_dir_path(__FILE__) . "../iss_student/class_student.php");

function iss_account_list_page()
{
    include(plugin_dir_path(__FILE__) . "/list_account.php");
}    
function iss_student_user_page()
{
    include(plugin_dir_path(__FILE__) . "/user_account.php");
} 
function iss_student_userdelete_page()
{
    include(plugin_dir_path(__FILE__) . "/user_delete.php");
}    
function iss_account_register_menu_page()
{
    //add_users_page( string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '' )
    $my_pages[] = add_users_page('Student Accounts', 'Students', 'iss_admin', 'issvactlist', 'iss_account_list_page');
    $my_pages[] = add_submenu_page(null, 'User Account', 'User Account', 'iss_admin', 'issvuser', 'iss_student_user_page');
    $my_pages[] = add_submenu_page(null, 'User Account Delete', 'User Account Delete', 'iss_admin', 'issvuserdelete', 'iss_student_userdelete_page');
 
    foreach ($my_pages as $my_page) {
        add_action('load-' . $my_page, 'iss_load_admin_custom_css');
    }
}
add_action('admin_menu', 'iss_account_register_menu_page');