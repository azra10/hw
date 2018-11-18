<?php
/*
 * Plugin Name: 60. ISS Class
 * 
 * Description: <strong>Depends: ISS Common, ISS Roles.</strong>   Lists classes based on the roles <strong>admin, board, secretary, teacher, parent, student and test </strong>.  On activation, adds a 'Classes' node to the navigations.
 * Version: 1.0
 * Author: Azra Syed
 * 
 */

function iss_class_list_page()
{
    include(plugin_dir_path(__FILE__) . "/list_class.php");
}

function new_class_page() {
	include (plugin_dir_path( __FILE__ ) . "/class_add.php");
}
function scale_class_page() {
	include (plugin_dir_path( __FILE__ ) . "/class_gradingscale.php");
}
function category_class_page() {
	include (plugin_dir_path( __FILE__ ) . "/class_categories.php");
}function teacher_class_page() {
	include (plugin_dir_path( __FILE__ ) . "/class_teachers.php");
}
function delete_class_page() {
	include (plugin_dir_path( __FILE__ ) . "/class_delete.php");
}
function class_progress_page()
{
    include(plugin_dir_path(__FILE__) . "/class_progress.php");
}
function editclass_progress_page()
{
    include(plugin_dir_path(__FILE__) . "/class_progress_edit.php");
}
function iss_class_register_menu_page()
{
    //add_menu_page( string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '', string $icon_url = '', int $position = null )
    $my_pages[] = add_menu_page('Classes', 'Classes', 'read', 'issvclasslist', 'iss_class_list_page', 'dashicons-id-alt', 3);
    $my_pages[] = add_submenu_page(null, 'Class Progress', 'Class Progress', 'iss_teacher', 'issveditclassprogress', 'editclass_progress_page');
    $my_pages[] = add_submenu_page(null, 'Class Progress', 'Class Progress', 'iss_teacher', 'issvclassprogress', 'class_progress_page');
    $my_pages [] = add_submenu_page ( null, 'Add Class', 'Add Class', 'iss_teacher', 'issvaddclass', 'new_class_page' );
	$my_pages [] = add_submenu_page ( null, 'Class Scale', 'Class Scale', 'iss_teacher', 'issvaddclassscale', 'scale_class_page' );
	$my_pages [] = add_submenu_page ( null, 'Class Category', 'Class Category', 'iss_teacher', 'issvaddclasscategory', 'category_class_page' );
	$my_pages [] = add_submenu_page ( null, 'Class Teacher', 'Class Teacher', 'iss_teacher', 'issvaddclassteacher', 'teacher_class_page' );
	$my_pages [] = add_submenu_page ( null, 'Delete Class', 'Delete Class', 'iss_teacher', 'issvdeleteclass', 'delete_class_page' );

    foreach ($my_pages as $my_page) {
        add_action('load-' . $my_page, 'iss_load_admin_custom_css');
    }
}
add_action('admin_menu', 'iss_class_register_menu_page');


?>