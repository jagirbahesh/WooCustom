<?php
/**
 * Plugin Name: WooCustom
 * Plugin URL: https://wordpress.org/plugin-url/
 * Description: Plugin description
 * Version: 1.0
 * Author: Jagir Bahesh
 * Author URI: https://jagirbaheshwp.wordpress.com/
 * Developer: Jagirbahesh
 * Developer E-Mail: bahesh.jagir@gmail.com
 * Text Domain: woocustom
 * Domain Path: /languages
 *
 * Copyright: © 2009-2020 Plugin jagir bahesh.
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Basic plugin definitions
 *
 * @package WooCustom
 * @since 1.0
 */

if ( !defined( 'PB_VERSION' ) ) {
	define( 'PB_VERSION', '1.0' ); // Version of plugin
}

if ( !defined( 'PB_FILE' ) ) {
	define( 'PB_FILE', __FILE__ ); // Plugin File
}

if ( !defined( 'PB_DIR' ) ) {
	define( 'PB_DIR', dirname( __FILE__ ) ); // Plugin dir
}

if ( !defined( 'PB_URL' ) ) {
	define( 'PB_URL', plugin_dir_url( __FILE__ ) ); // Plugin url
}

if ( !defined( 'PB_PLUGIN_BASENAME' ) ) {
	define( 'PB_PLUGIN_BASENAME', plugin_basename( __FILE__ ) ); // Plugin base name
}

if ( !defined( 'PB_META_PREFIX' ) ) {
	define( 'PB_META_PREFIX', 'pb_' ); // Plugin metabox prefix
}

if ( !defined( 'PB_PREFIX' ) ) {
	define( 'PB_PREFIX', 'pb' ); // Plugin prefix
}

/**
 * Initialize the main class
 */
if ( !function_exists( 'PB' ) ) {

	if ( is_admin() ) {
		require_once( PB_DIR . '/inc/admin/class.' . PB_PREFIX . '.admin.php' );
		require_once( PB_DIR . '/inc/admin/class.' . PB_PREFIX . '.admin.action.php' );
		require_once( PB_DIR . '/inc/admin/class.' . PB_PREFIX . '.admin.filter.php' );
	} else {
		require_once( PB_DIR . '/inc/front/class.' . PB_PREFIX . '.front.php' );
		require_once( PB_DIR . '/inc/front/class.' . PB_PREFIX . '.front.action.php' );
		require_once( PB_DIR . '/inc/front/class.' . PB_PREFIX . '.front.filter.php' );
	}

	# Initialize all the things.
	require_once( PB_DIR . '/inc/class.' . PB_PREFIX . '.php' );
}
