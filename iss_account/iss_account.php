<?php
/*
 * Plugin Name: 90. ISS Student Accounts
 * 
 * Description: <strong>Depends: ISS Common, ISS Class, ISS Assignment, ISS Roles.</strong>   Lists assignments of a class based on the roles <strong>admin, board, secretary, teacher, parent, student and test </strong>.  On activation, adds a 'Students' link to the class list page.
 
 * Version: 1.0
 * Author: Azra Syed
 * 
 */

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
function iss_student_userstatus_page()
{
    include(plugin_dir_path(__FILE__) . "/user_status.php");
}
function iss_student_usercreate_page()
{
    include(plugin_dir_path(__FILE__) . "/user_create.php");
}
function iss_account_register_menu_page()
{
    //add_users_page( string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '' )
    //add_menu_page( string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '', string $icon_url = '', int $position = null )
    $my_pages[] = add_menu_page('Students', 'Students', 'iss_admin', 'issvuserlist', 'iss_account_list_page', 'dashicons-groups', 4);
    $my_pages[] = add_submenu_page(null, 'User Mapping', 'User Mapping', 'iss_admin', 'issvadduser', 'iss_student_user_page');
    $my_pages[] = add_submenu_page(null, 'User Delete', 'User Delete', 'iss_admin', 'issvremoveuser', 'iss_student_userdelete_page');
    $my_pages[] = add_submenu_page(null, 'User Status', 'User Status', 'iss_admin', 'issvarchiveuser', 'iss_student_userstatus_page');
    $my_pages[] = add_submenu_page(null, 'User Create', 'User Create', 'iss_admin', 'issvusercreate', 'iss_student_usercreate_page');


    foreach ($my_pages as $my_page) {
        add_action('load-' . $my_page, 'iss_load_admin_custom_css');
    }
}
add_action('admin_menu', 'iss_account_register_menu_page');

function iss_user_last_login( $user_login, $user ) {
    update_user_meta( $user->ID, 'last_login', time() );
    ISS_UserStudentMapService::UpdateLasLogin($user_login, $user->ID);
    ISS_UserClassMapService::UpdateLasLogin($user_login, $user->ID);
}
add_action( 'wp_login', 'iss_user_last_login', 10, 2 );

function iss_remove_user_login( $user_id ) { 
    iss_write_log('iss_remove_user_login' . $user_id);
    iss_write_log($user_id);
    ISS_UserStudentMapService::RemoveUserMappings($user_id);
    ISS_UserClassMapService::RemoveUserMappings($user_id);
}
add_action( 'delete_user', 'iss_remove_user_login' );

?>