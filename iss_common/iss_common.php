<?php
/*
* Plugin Name: 10. ISS Common 
* Description: This is a plugin for all common assets and functionality for other ISS series of plugins.
* Author: Azra Syed
* Version: 1.0
*/

require_once(plugin_dir_path(__FILE__) . "../iss_common/class_assignment.php");
require_once(plugin_dir_path(__FILE__) . "../iss_common/class_class.php");
require_once(plugin_dir_path(__FILE__) . "../iss_common/class_gradingperiod.php");
require_once(plugin_dir_path(__FILE__) . "../iss_common/class_permission.php");
require_once(plugin_dir_path(__FILE__) . "../iss_common/class_student.php");
require_once(plugin_dir_path(__FILE__) . "../iss_common/class_userclassmap.php");
require_once(plugin_dir_path(__FILE__) . "../iss_common/class_userstudentmap.php");
require_once(plugin_dir_path(__FILE__) . "../iss_common/constants.php");
require_once(plugin_dir_path(__FILE__) . "../iss_common/function_registration.php");
require_once(plugin_dir_path(__FILE__) . "../iss_common/function_validate.php");
require_once(plugin_dir_path(__FILE__) . "../iss_common/functions.php");




function my_custom_fonts() {
  echo '<style>
    .dashicons-welcome-write-blog { 
       
        color:skyblue;
    }
    .dashicons-controls-play {
       color:orange;
    }
    .dashicons-admin-users{
        color:#77dcd6;
    }
    .dashicons-admin-comments {
      color: #e2e438;
}
  </style>';
}
add_action('admin_head', 'my_custom_fonts');
?>