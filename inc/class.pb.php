<?php
/**
 * PB Class
 *
 * Handles the plugin functionality.
 *
 * @package WordPress
 * @package WooCustom
 * @since 1.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


if ( !class_exists( 'PB' ) ) {

	/**
	 * The main PB class
	 */
	class PB {

		private static $_instance = null;

		var $admin = null,
		    $front = null;

		public static function instance() {

			if ( is_null( self::$_instance ) )
				self::$_instance = new self();

			return self::$_instance;
		}

		function __construct() {

			add_action( 'admin_init', array( $this, 'action__admin_init' ) );
			add_action( 'plugins_loaded', array( $this, 'action__plugins_loaded' ), 1 );

		}

		/**
		 * [action__admin_init - Check WooCommerce plugin is activated or not]
		 * @return int
		 */
		function action__admin_init() {
			if ( !class_exists('WooCommerce') ) {
				// is this plugin active?
				if ( is_plugin_active( PB_PLUGIN_BASENAME ) ) {
					// deactivate the plugin
					deactivate_plugins( PB_PLUGIN_BASENAME );
					// unset activation notice
					unset( $_GET[ 'activate' ] );
					// display notice
					add_action( 'admin_notices', array( $this, 'action__admin_notices' ) );
				}
			}
		}

		/**
		 * [action__plugins_loaded description]
		 * @return [type] [description]
		 */
		function action__plugins_loaded() {

			global $wp_version;

			# Set filter for plugin's languages directory
			$pb_lang_dir = dirname( PB_PLUGIN_BASENAME ) . '/languages/';
			$pb_lang_dir = apply_filters( 'PB_languages_directory', $pb_lang_dir );

			# Traditional WordPress plugin locale filter.
			$get_locale = get_locale();

			if ( $wp_version >= 4.7 ) {
				$get_locale = get_user_locale();
			}

			# Traditional WordPress plugin locale filter
			$locale = apply_filters( 'plugin_locale',  $get_locale, 'woocustom' );
			$mofile = sprintf( '%1$s-%2$s.mo', 'woocustom', $locale );

			# Setup paths to current locale file
			$mofile_global = WP_LANG_DIR . '/plugins/' . basename( PB_DIR ) . '/' . $mofile;

			if ( file_exists( $mofile_global ) ) {
				# Look in global /wp-content/languages/plugin-name folder
				load_textdomain( 'woocustom', $mofile_global );
			} else {
				# Load the default language files
				load_plugin_textdomain( 'woocustom', false, $pb_lang_dir );
			}
		}

		/*
		######## ##     ## ##    ##  ######  ######## ####  #######  ##    ##  ######
		##       ##     ## ###   ## ##    ##    ##     ##  ##     ## ###   ## ##    ##
		##       ##     ## ####  ## ##          ##     ##  ##     ## ####  ## ##
		######   ##     ## ## ## ## ##          ##     ##  ##     ## ## ## ##  ######
		##       ##     ## ##  #### ##          ##     ##  ##     ## ##  ####       ##
		##       ##     ## ##   ### ##    ##    ##     ##  ##     ## ##   ### ##    ##
		##        #######  ##    ##  ######     ##    ####  #######  ##    ##  ######
		*/

		/**
		 * [action__admin_notices - admin notice show if WooCommerce plugin is not active]
		 * @return HTML
		 */
		function action__admin_notices() {
			if ( !class_exists('WooCommerce') ) {
				echo '<div class="error notice is-dismissible">';
				echo sprintf( __('<p><strong>%s</strong> recommends the following plugin to use.</p>', 'woocustom'), 'WooCustom' );
				echo sprintf( __('<p><strong><a href="%s" target="_blank">%s</a> </strong></p>', 'woocustom'), 'https://wordpress.org/plugins/woocommerce/', 'WooCommerce' );
				echo '</div>';
			}
		}

	}
}

function PB() {
	return PB::instance();
}

PB();
