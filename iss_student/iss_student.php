<?php
/*
 * Plugin Name: 80. ISS Student
 * 
 * Description: <strong>Depends: ISS Common, ISS Class, ISS Assignment, ISS Roles.</strong>   Lists assignments of a class based on the roles <strong>admin, board, secretary, teacher, parent, student and test </strong>.  On activation, adds a 'Students' link to the class list page.
 
 * Version: 1.0
 * Author: Azra Syed
 * 
 */

function iss_student_list_page()
{
    include(plugin_dir_path(__FILE__) . "/list_student.php");
}    

function iss_student_register_menu_page()
{
    //add_submenu_page( string $parent_slug, string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '' )
    $my_pages[] = add_submenu_page(null, 'Students', 'Students', 'read', 'issvstudentlist', 'iss_student_list_page');
 
    foreach ($my_pages as $my_page) {
        add_action('load-' . $my_page, 'iss_load_admin_custom_css');
    }
}
add_action('admin_menu', 'iss_student_register_menu_page');

?>