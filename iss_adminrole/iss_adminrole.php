<?php
/*
 * Plugin Name: 20. ISS Roles
 * Description: Create roles <strong>admin, board, secretary, teacher, parent, student and test </strong> on activation. No dependency on any other plug-in. On Activation wordpress admnistrator receives test, admin, secretary & board  capabilities. On Deactivation, capabilities and roles are removed. Users with capabilities/roles are not touched except wordpress admnistrator. wordpress admnistrator capabilities (test, admin, secretary & board) removed.
 * Version: 1.0.0
 * Author: Azra Syed
 * Text Domain: iss_adminpref
 */
 /*

 Roles (capabilities)
  - administrator (iss_admin, iss_secretary, iss_board, iss_test)
  - issadminrole  (iss_admin, iss_secretary, iss_board, iss_test)
  - isssecretaryrole (iss_secretary)
  - issboardrole (iss_board)
  - issteacherrole (iss_teacher, iss_parent)
  - issparentrole (iss_parent, iss_student)
  - issstudentrole (iss_student)
  - isstestrole (iss_test)  
 
 Capability
  - iss_test : be able to run test
 
 */

if (! function_exists ( 'iss_write_log' )) {
	function iss_write_log($log) {
		if (true === WP_DEBUG) {
			
			if (is_array ( $log ) || is_object ( $log )) {
				error_log ( get_current_user () . ' ' . print_r ( $log, true ) );
			} else {
				error_log ( get_current_user () . ' ' . $log );
			}
		}
	}
}
class ISS_AdminRolePlugin
{
    static function uninsatall()
    {
        $admrole = get_role ( 'administrator' );
        if (null != $admrole) {
            $admrole->remove_cap ( 'iss_admin' );
            $admrole->remove_cap ( 'iss_board' );
            $admrole->remove_cap ( 'iss_secretary' );
            $admrole->remove_cap ( 'iss_test' );
        }
        if (get_role ( 'issadminrole' )) {
            remove_role ( 'issadminrole' );
        }
        if (get_role ( 'isssecretaryrole' )) {
            remove_role ( 'isssecretaryrole' );
        }
        if (get_role ( 'issboardrole' )) {
            remove_role ( 'issboardrole' );
        }
        if (get_role ( 'issparentrole' )) {
            remove_role ( 'issparentrole' );
        }
        if (get_role ( 'issteacherrole' )) {
            remove_role ( 'issteacherrole' );
        }
        if (get_role ( 'issstudentrole' )) {
            remove_role ( 'issstudentrole' );
        }
        if (get_role ( 'isstestrole' )) {
            remove_role ( 'isstestrole' );
        }
        iss_write_log ( 'administrator role capability is_admin & iss_board removed' );
        global $wp_roles;
        if (! isset ( $wp_roles )) {
            $wp_roles = new WP_Roles ();
            iss_write_log ( $wp_roles );
        }
    }
    static function addrole($roleInternalName, $roleDisplayName, $capability)
    {
        $issrole = get_role ( $roleInternalName );
        if (null == $issrole) {
            $result = add_role ( $roleInternalName, $roleDisplayName, array (
                    'read' => true,
                    'level_0' => true,
                    $capability => true
            ) );
            iss_write_log ( $result );
            if (null != $result) {
                iss_write_log ( "{$roleInternalName} role with capability {$capability} created!" );
            }
        } else {
            iss_write_log ( "{$roleInternalName} role exists" );
        }
    }
    static function addcapability($roleInternalName, $capability)
    {
        $issrole = get_role ( $roleInternalName );
        $cap = null;
        if (null != $issrole) {
            if (isset ( $issrole->capabilities [$capability] )) {
                $cap = $issrole->capabilities [$capability];
            }
            if (null == $cap) {
                $issrole->add_cap ( $capability );
                iss_write_log ( "{$roleInternalName} role, capability {$capability} is added" );
            } else {
                iss_write_log ( "{$roleInternalName} role, capability {$capability} already exists" );
            }
        } else {
            iss_write_log ( "{$roleInternalName} role does not exists" );
        }
    }
    static function install()
    {
        global $wp_roles;
        if (! isset ( $wp_roles )) {
            $wp_roles = new WP_Roles ();
        }
                
        /// Test Role 
        forward_static_call_array ( array (
                'ISS_AdminRolePlugin', // class name
                'addrole'  // function name
        ), array (
                'isstestrole',  // role internal name
                'ISS Test Role', // role display name
                'iss_test'  // capability
        ) );
  
        /// Student Role 
        forward_static_call_array ( array (
                'ISS_AdminRolePlugin',
                'addrole'
        ), array (
                'issstudentrole',
                'ISS Student Role',
                'iss_student'
        ) );
      
        /// Parent Role 
        forward_static_call_array ( array (
                'ISS_AdminRolePlugin',
                'addrole'
        ), array (
                'issparentrole',
                'ISS Parent Role',
                'iss_parent'
        ) );

        // Parent Role is also a student
        forward_static_call_array ( array ( 'ISS_AdminRolePlugin', 'addcapability'  ), array ('issparentrole', 'iss_student') );

        /// Teacher Role 
        forward_static_call_array ( array (
                'ISS_AdminRolePlugin',
                'addrole'
        ), array (
                'issteacherrole',
                'ISS Teacher Role',
                'iss_teacher'
        ) );

        // Teacher Role is also a parent
        forward_static_call_array ( array ( 'ISS_AdminRolePlugin', 'addcapability' ), array ( 'issteacherrole', 'iss_parent' ) );
        forward_static_call_array ( array ( 'ISS_AdminRolePlugin', 'addcapability'  ), array (  'issteacherrole', 'edit_posts' ) );
        forward_static_call_array ( array ( 'ISS_AdminRolePlugin', 'addcapability'  ), array (  'issteacherrole', 'upload_files' ) );
        forward_static_call_array ( array ( 'ISS_AdminRolePlugin', 'addcapability'  ), array (  'issteacherrole', 'publish_posts' ) );
        forward_static_call_array ( array ( 'ISS_AdminRolePlugin', 'addcapability'  ), array (  'issteacherrole', 'delete_posts' ) );
        forward_static_call_array ( array ( 'ISS_AdminRolePlugin', 'addcapability'  ), array (  'issteacherrole', 'edit_published_posts' ) );
        forward_static_call_array ( array ( 'ISS_AdminRolePlugin', 'addcapability'  ), array (  'issteacherrole', 'delete_published_posts' ) );
        forward_static_call_array ( array ( 'ISS_AdminRolePlugin', 'addcapability'  ), array (  'issteacherrole', 'edit_others_posts' ) );
        forward_static_call_array ( array ( 'ISS_AdminRolePlugin', 'addcapability'  ), array (  'issteacherrole', 'delete_others_posts' ) );

        /// Board Role        
        forward_static_call_array ( array (
                'ISS_AdminRolePlugin',
                'addrole'
        ), array (
                'issboardrole',
                'ISS Board Role',
                'iss_board'
        ) );
 
        /// Secretary Role 
       forward_static_call_array ( array (
                'ISS_AdminRolePlugin',
                'addrole'
        ), array (
                'isssecretaryrole',
                'ISS Secretary Role',
                'iss_secretary'
        ) );

        // Secretary Role is also board member
        forward_static_call_array ( array ('ISS_AdminRolePlugin','addcapability'), array ('isssecretaryrole', 'iss_board') );

         // Admin Role
        forward_static_call_array ( array (
                'ISS_AdminRolePlugin',
                'addrole'
        ), array (
                'issadminrole', // internal role name
                'ISS Admin Role', // role dislay name
                'iss_admin'  // capability
        ) );

       // Admin Role is also board member & secretary
        forward_static_call_array ( array ( 'ISS_AdminRolePlugin', 'addcapability'  ), array ('issadminrole','iss_board') );
        forward_static_call_array ( array ( 'ISS_AdminRolePlugin', 'addcapability'  ), array ('issadminrole','iss_secretary') );

        // wordpress admnistrator has unittest, admin, secretary & board  capabilities
        forward_static_call_array ( array ( 'ISS_AdminRolePlugin', 'addcapability'  ), array ('administrator','iss_admin') );
        forward_static_call_array ( array ( 'ISS_AdminRolePlugin', 'addcapability'  ), array ('administrator','iss_secretary') );
        forward_static_call_array ( array ( 'ISS_AdminRolePlugin', 'addcapability'  ), array ('administrator','iss_board') );
        forward_static_call_array ( array ( 'ISS_AdminRolePlugin', 'addcapability'  ), array ('administrator','iss_test') );
    }
}
register_activation_hook ( __FILE__, array (
        'ISS_AdminRolePlugin',
        'install'
) );
register_deactivation_hook ( __FILE__, array (
        'ISS_AdminRolePlugin',
        'uninsatall'
) );
