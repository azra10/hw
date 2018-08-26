<?php
/*
 * Plugin Name: 60. ISS Email
 * 
 * Description: <strong>Depends: ISS Common, ISS Roles.</strong>   Send Email to users
 * Version: 1.0
 * Author: Azra Syed
 * 
 */

function iss_email_page()
{
    include(plugin_dir_path(__FILE__) . "/email_contact.php");
}
function iss_email_register_menu_page()
{
    //add_menu_page( string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '', string $icon_url = '', int $position = null )
    $my_pages[] = add_menu_page('Email Admin', 'Email Admin', 'read', 'issvemailadmin', 'iss_email_page', 'dashicons-id-alt', 6);
   

    foreach ($my_pages as $my_page) {
        add_action('load-' . $my_page, 'iss_load_admin_custom_css');
    }
}
add_action('admin_menu', 'iss_email_register_menu_page');


?>