<?php
/**
 * PB_Admin_Action Class
 *
 * Handles the admin functionality.
 *
 * @package WordPress
 * @package WooCustom
 * @since 1.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'PB_Admin_Action' ) ) {

	/**
	 *  The PB_Admin_Action Class
	 */
	class PB_Admin_Action {

		function __construct()  {

			add_action( 'admin_init',                         array( $this, 'action__admin_init' ) );
			add_action( 'manage_product_posts_custom_column', array( $this, 'action__manage_product_posts_custom_column' ), 10, 2 );

		}

		/*
		   ###     ######  ######## ####  #######  ##    ##  ######
		  ## ##   ##    ##    ##     ##  ##     ## ###   ## ##    ##
		 ##   ##  ##          ##     ##  ##     ## ####  ## ##
		##     ## ##          ##     ##  ##     ## ## ## ##  ######
		######### ##          ##     ##  ##     ## ##  ####       ##
		##     ## ##    ##    ##     ##  ##     ## ##   ### ##    ##
		##     ##  ######     ##    ####  #######  ##    ##  ######
		*/

		/**
		 * [action__admin_init - Register admin min js and admin min css]
		 * @return [type] [description]
		 */
		function action__admin_init() {
			wp_register_script( PB_PREFIX . '_admin_js', PB_URL . 'assets/js/admin.min.js', array( 'jquery-core' ), PB_VERSION );
			 wp_register_style( PB_PREFIX . '_admin_css', PB_URL . 'assets/css/admin.min.css', array(), PB_VERSION );
		}


		/**
		 * [action__manage_product_posts_custom_column description]
		 * @param  [type] $column_name [description]
		 * @param  [type] $post_id     [description]
		 * @return [type]              [description]
		 */
		function action__manage_product_posts_custom_column( $column_name, $post_id ) {

			if( $column_name  == 'order_count' ) {

				$units_sold = get_post_meta( $post_id, 'total_sales', true );
				if ( !empty( $units_sold ) ) {
					echo $units_sold;
				} else {
					echo '';
				}
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


	}

	add_action( 'plugins_loaded', function() {
		PB()->admin->action = new PB_Admin_Action;
	} );
}
