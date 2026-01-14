<?php

/*
Plugin Name: SWPM Bulk Import Members From CSV
Version: 1.6
Plugin URI: https://simple-membership-plugin.com/simple-membership-bulk-import-member-data-csv-file/
Author: alexanderfoxc
Author URI: https://simple-membership-plugin.com/
Description: An addon for the simple membership plugin to bulk import members from CSV file.
*/

//Slug - swpmbmi

if ( ! defined( 'ABSPATH' ) ) {
    exit; //Exit if accessed directly
}

class SWMPBMI_main {

    function __construct() {
	if ( is_admin() ) {
	    include_once(plugin_dir_path( __FILE__ ) . 'views/admin-interface.php');
	    add_action( 'swpm_after_main_admin_menu', array( $this, 'add_submenu_item' ) );
	    add_action( 'wp_ajax_swpmbmi_process_file', array( $this, 'process_file' ) );
	    add_action( 'wp_ajax_nopriv_swpmbmi_process_file', array( $this, 'process_file' ) );
	}
    }

    static function _( $msg ) {
		return __( $msg, 'simple-membership' );
    }

    function add_submenu_item( $menu_parent_slug ) {
		add_submenu_page( $menu_parent_slug, __( "Member Bulk Import", 'simple-membership' ), __( "Member Bulk Import", 'simple-membership' ), 'manage_options', 'swpm-bulkmemberimport', array( $this, 'render_admin_interface' ) );
    }

    function render_admin_interface() {
	$admin_interface = new SWPMBMI_admin_interface();
	$admin_interface->render_admin_page();
    }

    function process_file() {
	$out		 = array();
	$out[ 'error' ]	 = false;
	if ( ! empty( $_FILES ) && isset( $_FILES[ 'swpmbmi-file' ] ) ) {
	    $filename	 = $_FILES[ 'swpmbmi-file' ][ 'tmp_name' ];
	    $csv		 = array_map( 'str_getcsv', file( $filename ) );
	    unlink( $filename );
	    if ( is_array( $csv ) and ! empty( $csv ) ) {
		$predefined	 = array( 'user_name', 'first_name'
		    , 'last_name', 'member_since'
		    , 'membership_level', 'account_state'
		    , 'last_accessed', 'last_accessed_from_ip'
		    , 'email', 'address_street', 'address_city'
		    , 'address_state', 'address_zipcode', 'country'
		    , 'gender', 'referrer', 'extra_info', 'reg_code'
		    , 'subscription_starts', 'txn_id', 'subscr_id'
		    , 'company_name', 'phone', 'more_membership_levels'
		    , 'initial_membership_level', 'home_page', 'notes'
		    , 'profile_image' );
		$required	 = array( 'user_name', 'email' ); //minimum required info to create a member
		$keys		 = array();
		//let's see which keys we have in CSV file
		foreach ( $csv[ 0 ] as $key => $value ) {
		    if ( in_array( trim( $value ), $predefined ) ) {
			$keys[ $key ] = trim( $value );
		    }
		}
		//unser CSV header row as it's no longer needed
		unset( $csv[ 0 ] );
		//let's check if we have minimum required keys to create a member
		$min_req = true;
		foreach ( $required as $req ) {
		    if ( ! in_array( $req, $keys ) ) {
			$min_req = false;
			break;
		    }
		}
		if ( $min_req ) {
		    $members = array();
		    foreach ( $csv as $line ) {
			$member = array();
			foreach ( $line as $key => $value ) {
			    if ( isset( $keys[ $key ] ) ) {
				$member[ $keys[ $key ] ] = trim( $value );
			    }
			}
			if ( ! empty( $member ) ) {
			    $members[] = $member;
			}
		    }
		    //let's process members and generate results table
		    $results_success = array();
		    $results_failed	 = array();
		    global $wpdb;
		    $query		 = "SELECT alias, id FROM " . $wpdb->prefix . "swpm_membership_tbl WHERE id != 1";
		    $levels		 = $wpdb->get_results( $query );
		    $free_level	 = SwpmSettings::get_instance()->get_value( 'free-membership-id' );
		    $i		 = 1;
		    foreach ( $members as $member ) {
			$i ++;
			if ( SwpmUtils::swpm_username_exists( $member[ 'user_name' ] ) ) {
			    //member with given username already exists
			    $member[ 'failure_reason' ]	 = __( "Member with this username already exists.", "simple-membership" );
			    $member[ 'line_num' ]		 = $i;
			    $results_failed[]		 = $member;
			    continue;
			}
			if ( empty( $member[ 'email' ] ) || ! filter_var( $member[ 'email' ], FILTER_VALIDATE_EMAIL ) ) {
			    // invalid email address
			    $member[ 'failure_reason' ]	 = __( "Invalid email address.", "simple-membership" );
			    $member[ 'line_num' ]		 = $i;
			    $results_failed[]		 = $member;
			    continue;
			} else if ( SwpmMemberUtils::get_user_by_email( $member[ 'email' ] ) ) {
			    //user with given email already exists
			    $member[ 'failure_reason' ]	 = __( "Member with this email already exists.", "simple-membership" );
			    $member[ 'line_num' ]		 = $i;
			    $results_failed[]		 = $member;
			    continue;
			}
			if ( isset( $member[ 'membership_level' ] ) && ! empty( $member[ 'membership_level' ] ) ) {
			    //check if membership level exists
			    $membership_level_match = false;
			    foreach ( $levels as $level ) {
				if ( intval( $level->id ) === intval( $member[ 'membership_level' ] ) ) {
				    $membership_level_match = true;
				    break;
				}
			    }
			    if ( ! $membership_level_match ) {
				//given membership level doesn't exists
				$member[ 'failure_reason' ]	 = sprintf( __( 'Membership level ID %s does not exists.', "simple-membership" ), esc_html( $member[ 'membership_level' ] ) );
				$member[ 'line_num' ]		 = $i;
				$results_failed[]		 = $member;
				continue;
			    } else {
				$member[ 'membership_level' ] = $level->id;
			    }
			} else {
			    //membership_level is empty or not set. Let's try to assign default level if it's set in SWPM settings
			    if ( ! empty( $free_level ) ) {
				$member[ 'membership_level' ] = $free_level;
			    } else {
				//no default level set
				$member[ 'failure_reason' ]	 = __( "Membership level not specified and no default level (Free Membership Level ID) set in the settings.", "simple-membership" );
				$member[ 'line_num' ]		 = $i;
				$results_failed[]		 = $member;
				continue;
			    }
			}
			if ( isset( $member[ 'gender' ] ) ) {
			    if ( ! in_array( strtolower( $member[ 'gender' ] ), array( 'male', 'female' ) ) ) {
				$member[ 'gender' ] = 'not specified';
			    }
			} else {
			    $member[ 'gender' ] = 'not specified';
			}
			if ( isset( $member[ 'account_state' ] ) ) {
			    if ( ! in_array( strtolower( $member[ 'account_state' ] ), array( 'active', 'inactive', 'expired', 'pending', 'unsubscribed' ) ) ) {
				$member[ 'account_state' ] = 'pending';
			    }
			} else {
			    $member[ 'account_state' ] = 'pending';
			}
			if ( isset( $member[ 'member_since' ] ) && !empty($member[ 'member_since' ]) ) {
			    $date = strtotime( $member[ 'member_since' ] );//Use the value from the CSV file
			    if ( $date === false ) {
				$date = date( "Y-m-d" );
			    }
			    $member[ 'member_since' ] = date( 'Y-m-d', $date );
			} else {
			    $member[ 'member_since' ] = date( 'Y-m-d' );//Use current date
			}

			if ( isset( $member[ 'subscription_starts' ] ) && !empty($member[ 'subscription_starts' ]) ) {
			    $date = $member[ 'subscription_starts' ];//Use the value from the CSV file
			} else {
			    $date = date( "Y-m-d" );//Use current date
			}
			$member[ 'subscription_starts' ] = $date;

			if ( isset( $member[ 'last_accessed' ] ) && !empty($member[ 'last_accessed' ]) ) {
			    $date = $member[ 'last_accessed' ];//Use the value from the CSV file
			} else {
			    $date = date( "Y-m-d H:i:s" );//Use current date
			}
			$member[ 'last_accessed' ] = $date;

                        $password_plain = '';
			//Lets check if a default password was set by the admin.
			if(isset($_REQUEST['swpmbmi-default-password']) && !empty($_REQUEST['swpmbmi-default-password'])){
			    //Use the default password.
			    $password_plain = $_REQUEST['swpmbmi-default-password'];
			} else {
			    //No default password so generate a random one (plain password).
			    $password_plain = wp_generate_password();//Plain password randomly generated by WP.
			}
                        $password_hashed = SwpmUtils::encrypt_password( trim( $password_plain ) );
                        $member[ 'password' ] = $password_plain;
			//End password check

			//let's add user to the table
			$results_success[] = $member;

			//prepare insert query
			$values = array();
			$q_str = '';
			foreach ( $member as $key => $value ) {
                            $q_str .= $key . ' = %s ,';

                            if ( $key == 'password') {
                                //We need to used the hashed password for inserting into members table.
                                $values[] = $password_hashed;
                            } else {
                                $values[] = $value;
                            }
			}
			$q_str			 = rtrim( $q_str, ' ,' );
			$q_str			 = "INSERT INTO " . $wpdb->prefix . "swpm_members_tbl SET " . $q_str;
			$q_str			 = $wpdb->prepare( $q_str, $values );
			$q_res			 = $wpdb->query( $q_str );
			$member[ 'member_id' ]	 = $wpdb->insert_id;

			if(empty($member['member_id'])){
			    //Member record insert failed.
			    SwpmLog::log_simple_debug('Bulk Import Addon Error - Member record insert failed for: '.$member['email'], false);
			    continue;
			}

			//Register user to wordpress (Crete corresponding WP User entry)
			$level_row = SwpmUtils::get_membership_level_row_by_id($member['membership_level']);
			$wp_user_info = array();
			$wp_user_info['user_nicename'] = implode('-', explode(' ', $member['user_name']));
			$wp_user_info['display_name'] = $member['user_name'];
			$wp_user_info['user_email'] = $member['email'];
			$wp_user_info['nickname'] = $member['user_name'];
			if (isset($member['first_name'])) {
			    $wp_user_info['first_name'] = $member['first_name'];
			}
			if (isset($member['last_name'])) {
			    $wp_user_info['last_name'] = $member['last_name'];
			}
			$wp_user_info['user_login'] = $member['user_name'];

			//This is plain password generated by WP. The WP user ceate() and update() functions take the plain password as input.
                        $wp_user_info['password'] = $member['password'];

                        $wp_user_info['role'] = $level_row->role;
			$wp_user_info['user_registered'] = date('Y-m-d H:i:s');
			SwpmUtils::create_wp_user($wp_user_info);
			//End register to wordpress

			//Send rego complete email if enabled
			if(isset($_REQUEST['swpmbmi-send-rego-complete-email'])){
			    //Lets send the rego complete email.
			    $this->send_rego_complete_email($member, $wp_user_info);
			} else {
			    //Send rego complete email disabled for this import.
			}
			//End of rego complete email.

			//This hook can be used by addons that could use new member creation information (like MailChimp addon etc)
			do_action( 'swpm_bulk_members_import_new_member_created', $member );
		    }
		    $out[ 'result' ] = SWPMBMI_admin_interface::render_results_table( $results_success, $results_failed );
		} else {
		    //not all required keys are present
		    $out[ 'error' ]		 = true;
		    $out[ 'err_msg' ]	 = __( 'One of the required columns not found in CSV: ' . $req, "simple-membership" );
		}
	    } else {
		//invalid or empty CSV file
		$out[ 'error' ]		 = true;
		$out[ 'err_msg' ]	 = __( 'Ivalid or empty CSV file.', "simple-membership" );
	    }
	} else {
	    //no file was posted
	    $out[ 'error' ]		 = true;
	    $out[ 'err_msg' ]	 = __( 'No file uploaded.', "simple-membership" );
	}
	wp_send_json( $out );
	die();
    }

    function send_rego_complete_email($member, $wp_user_info){
        $settings = SwpmSettings::get_instance();
        $subject = $settings->get_value('reg-complete-mail-subject');
        $body = $settings->get_value('reg-complete-mail-body');
        $from_address = $settings->get_value('email-from');
        $headers = 'From: ' . $from_address . "\r\n";

	//Set additional args
	$additional_args = array('password' => $member['password']);
        $member_id = $member['member_id'];

	//Apply merge tags
        $body = SwpmMiscUtils::replace_dynamic_tags($body, $member_id, $additional_args);//Do the standard merge var replacement.

        //Send notification email to the member
        $subject = apply_filters('swpm_email_registration_complete_subject',$subject);
        $body = apply_filters('swpm_email_registration_complete_body',$body);

	$email = $member['email'];

        //Check if HTML emails are enabled in settings of the core plugin.
        $html_enabled = $settings->get_value( 'email-enable-html' );
        if ( ! empty( $html_enabled ) ) {
            $headers   .= "Content-Type: text/html; charset=UTF-8\r\n";
            $body = nl2br( $body );
        }

        wp_mail(trim($email), $subject, $body, $headers);
        SwpmLog::log_simple_debug('Bulk Import Addon - Member registration complete email sent to: '.$email.'. From email address value used: '.$from_address, true);
    }

}

$SWPMBMI_main = new SWMPBMI_main();

//Add settings link in plugins listing page
function swmp_import_csv_add_settings_link( $links, $file ) {
	if ( $file == plugin_basename( __FILE__ ) ) {
		$settings_link = '<a href="admin.php?page=swpm-bulkmemberimport">Settings</a>';
		array_unshift( $links, $settings_link );
	}
	return $links;
}
add_filter( 'plugin_action_links', 'swmp_import_csv_add_settings_link', 10, 2 );
