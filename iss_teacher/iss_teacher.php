<?php
/*
 * Plugin Name: 91. ISS Teacher Accounts
 * 
 * Description: <strong>Depends: ISS Common, ISS Class,  ISS Roles.</strong>   Lists assignments of a class based on the roles <strong>admin, board, secretary, teacher, parent, student and test </strong>.  On activation, adds a 'Students' link to the class list page.
 
 * Version: 1.0
 * Author: Azra Syed
 * 
 */

function iss_teacher_account_list_page()
{
    include(plugin_dir_path(__FILE__) . "/list_teacher.php");
}    
function iss_teacher_user_page()
{
    include(plugin_dir_path(__FILE__) . "/teacher_account.php");
} 
function iss_teacher_userdelete_page()
{
    include(plugin_dir_path(__FILE__) . "/teacher_delete.php");
}    
function iss_teacher_account_register_menu_page()
{
    //add_users_page( string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '' )
    $my_pages[] = add_menu_page('Teachers', 'Teachers', 'iss_admin', 'issvtlist', 'iss_teacher_account_list_page', 'dashicons-format-gallery', 5);
    $my_pages[] = add_submenu_page(null, 'Teacher Account', 'Teacher Account', 'iss_admin', 'issvteacheraccount', 'iss_teacher_user_page');
    $my_pages[] = add_submenu_page(null, 'Teacher Account Delete', 'Teacher Account Delete', 'iss_admin', 'issvteacherdelete', 'iss_teacher_userdelete_page');
 
    foreach ($my_pages as $my_page) {
        add_action('load-' . $my_page, 'iss_load_admin_custom_css');
    }
}
add_action('admin_menu', 'iss_teacher_account_register_menu_page');