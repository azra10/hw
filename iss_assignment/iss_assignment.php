<?php
/*
 * Plugin Name: 70. ISS Assignment
 * 
 * Description: <strong>Depends: ISS Common, ISS Class, ISS Roles.</strong>   Lists assignments of a class based on the roles <strong>admin, board, secretary, teacher, parent, student and test </strong>.  On activation, adds a 'Assignment' link to the class list page.
 
 * Version: 1.0
 * Author: Azra Syed
 * 
 */

require_once(plugin_dir_path(__FILE__) . "../iss_common/functions.php");
require_once(plugin_dir_path(__FILE__) . "/class_assignment.php");

function iss_assignment_list_page()
{
    include(plugin_dir_path(__FILE__) . "/list_assignment.php");
}
function iss_assignment_view_page()
{
    include(plugin_dir_path(__FILE__) . "/assignment_view.php");
}
function iss_assignment_delete_page()
{
    include(plugin_dir_path(__FILE__) . "/assignment_delete.php");
}
function iss_assignment_register_menu_page()
{
    //add_submenu_page( string $parent_slug, string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '' )

    $my_pages[] = add_submenu_page(null, 'Assignments', 'Assignments', 'read', 'issvalist', 'iss_assignment_list_page');
    $my_pages[] = add_submenu_page(null, 'View Assignment', 'View Assignments', 'read', 'issvaview', 'iss_assignment_view_page');
    $my_pages[] = add_submenu_page(null, 'Delete Assignment', 'Delete Assignments', 'read', 'issvadelete', 'iss_assignment_delete_page');
 
    foreach ($my_pages as $my_page) {
        add_action('load-' . $my_page, 'iss_load_admin_custom_css');
    }
}
add_action('admin_menu', 'iss_assignment_register_menu_page');
/*
add_action('admin_menu', 'iss_remove_menu_items');
add_action('admin_menu', 'iss_remove_meta_boxes');
add_action('wp_dashboard_setup', 'iss_remove_dashboard_widgets');
*/

function iss_assignment_cpt()
{
    register_post_type('iss_assignment', array(
        'labels' => array(
            'name' => 'Assignments',
            'singular_name' => 'Assignment',
            'add_new_item' => 'Add Assignment',
            'edit_item' => 'Edit Assignment',
            'view_item' => 'View Assignment',
        ),
        'description' => 'Assignment / Test / Attendance / Participation',
        'public' => true,
        'menu_position' => 4,
       // 'public' => false,
        'show_ui' => true,
        'map_meta_cap' => true,
        'supports' => array('title', 'editor')
    ));
}
add_action('init', 'iss_assignment_cpt'); //register a new post type

function iss_hide_new_post()
{
     // hide 'Add New' in the edit post page
     if (!current_user_can('iss_admin'))
   { echo '<style> a.page-title-action {display:none;} </style>';}
}
add_action('admin_menu', 'iss_hide_new_post');

add_action('admin_enqueue_scripts', 'iss_datepicker_enqueue');

require plugin_dir_path( __FILE__ ) . 'iss_assignment_metaboxes.php';


// Initialize metaboxes
$iss_post_type_metaboxes = new ISS_Assignment_Post_Type_Metaboxes;
$iss_post_type_metaboxes->init();
?>