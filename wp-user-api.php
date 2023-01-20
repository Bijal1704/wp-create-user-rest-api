<?php
/**
 * Plugin Name: WP User API
 * Plugin URI: https://example.com/
 * Description:  This plugin handles Add user using REST API. 
 * Version: 1.0.0
 * Author: Bijal
 * Author URI: https://example.com/
 * Text Domain: wp-user-api
 * Domain Path: languages
 * 
 * @package WP User API
 * @category Core
 * @author Bijal
 */

/**
 * Define Some needed predefined variables
 * 
 * @package WP User API
 * @since 1.0.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if( !defined( 'WP_USER_API_DIR' ) ) {
	define( 'WP_USER_API_DIR', dirname( __FILE__ ) ); // plugin dir
}
if( !defined( 'WP_USER_API_TEXT_DOMAIN' ) ) { //check if variable is not defined previous then define it
	define( 'WP_USER_API_TEXT_DOMAIN','wp-user-api' ); //this is for multi language support in plugin
}
if( !defined( 'WP_USER_API_URL' ) ) {
	define( 'WP_USER_API_URL', plugin_dir_url( __FILE__ ) ); // plugin url
}
if( !defined( 'WP_USER_API_PLUGIN_BASENAME' ) ) {
	define( 'WP_USER_API_PLUGIN_BASENAME', basename( WP_USER_API_DIR ) ); //Plugin base name
}
if( !defined('WP_USER_API_CUSTOM_ROLE' ) ) {
    define( 'WP_USER_API_CUSTOM_ROLE', 'custom_role' ); // plugin url
}
if( !defined('WP_USER_API_META_PREFIX' ) ) {
    define( 'WP_USER_API_META_PREFIX', '_wuapi_' ); // plugin url
}

/**
 * Plugin Activation hook
 * 
 * This hook will call when plugin will activate
 * 
 * @package WP User API
 * @since 1.0.0
 */
register_activation_hook( __FILE__, 'wp_user_api_install' );

function wp_user_api_install() {
		
    $result = add_role(
        WP_USER_API_CUSTOM_ROLE,
        esc_html__( 'Custom Role', 'wp-user-api' ),
        array(
            'read'         => true,  // Allows a user to read
            'edit_posts'   => false, // Allows user to edit their own posts
            'delete_posts' => false, // Allows user to delete their own posts
        )
    );	
}


/**
 * Plugin Deactivation hook
 * 
 * This hook will call when plugin will deactivate
 * 
 * @package WP User API
 * @since 1.0.0
 */
register_deactivation_hook( __FILE__, 'wp_user_api_uninstall' );

function wp_user_api_uninstall() {
	// Delete data which is not required
}

/**
 * Includes Class Files
 * 
 * @package WP User API
 * @since 1.0.0
 */
global $wp_user_api_obj;

//includes WP_USER_API class
require_once( WP_USER_API_DIR . '/includes/class-wp-user-api.php');
$wp_user_api_obj = new WP_USER_API();
$wp_user_api_obj->add_hooks();