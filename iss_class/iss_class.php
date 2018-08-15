<?php
/*
 * Plugin Name: 60. ISS Class
 * 
 * Description: <strong>Depends: ISS Common, ISS Roles.</strong>   Lists classes based on the roles <strong>admin, board, secretary, teacher, parent, student and test </strong>.  On activation, adds a 'Classes' node to the navigations.
 * Version: 1.0
 * Author: Azra Syed
 * 
 */

require_once(plugin_dir_path(__FILE__) . "../iss_common/functions.php");
require_once(plugin_dir_path(__FILE__) . "/class_class.php");
require_once(plugin_dir_path(__FILE__) . "../iss_common/class_permission.php");

function iss_class_list_page()
{
    include(plugin_dir_path(__FILE__) . "/list_class.php");
}

/*
function new_class_page() {
	include (plugin_dir_path( __FILE__ ) . "/new_class.php");
}
function edit_class_page() {
	include (plugin_dir_path( __FILE__ ) . "/edit_class.php");
}
function delete_class_page() {
	include (plugin_dir_path( __FILE__ ) . "/delete_class.php");
}
 */
function iss_class_register_menu_page()
{
    //add_menu_page( string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '', string $icon_url = '', int $position = null )
    $my_pages[] = add_menu_page('Classes', 'Classes', 'read', 'issvclist', 'iss_class_list_page', 'dashicons-id-alt', 3);
    //$my_pages[] = add_submenu_page(null, 'Class Students', 'Class Students', 'iss_admin', 'class_student', 'class_student_page');
    //$my_pages[] = add_submenu_page(null, 'Class Assignments', 'Class Assignments', 'iss_admin', 'class_assignment', 'class_assignment_page');
    //$my_pages [] = add_submenu_page ( null, 'Add Class', 'Add Teacher', 'iss_admin', 'new_class', 'new_class_page' );
	//$my_pages [] = add_submenu_page ( null, 'Edit Class', 'Edit Teacher', 'iss_admin', 'edit_class', 'edit_class_page' );
	//$my_pages [] = add_submenu_page ( null, 'Delete Class', 'Delete Teacher', 'iss_admin', 'delete_class', 'delete_class_page' );

    foreach ($my_pages as $my_page) {
        add_action('load-' . $my_page, 'iss_load_admin_custom_css');
    }
}
add_action('admin_menu', 'iss_class_register_menu_page');


?>