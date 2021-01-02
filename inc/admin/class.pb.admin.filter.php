<?php
/**
 * PB_Admin_Filter Class
 *
 * Handles the admin functionality.
 *
 * @package WordPress
 * @subpackage WooCustom
 * @since 1.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'PB_Admin_Filter' ) ) {

	/**
	 *  The PB_Admin_Filter Class
	 */
	class PB_Admin_Filter {

		function __construct() {

			add_filter( 'manage_edit-product_columns', array( $this,'filter__manage_edit_product_columns' ), 20 );

		}

		/*
		######## #### ##       ######## ######## ########   ######
		##        ##  ##          ##    ##       ##     ## ##    ##
		##        ##  ##          ##    ##       ##     ## ##
		######    ##  ##          ##    ######   ########   ######
		##        ##  ##          ##    ##       ##   ##         ##
		##        ##  ##          ##    ##       ##    ##  ##    ##
		##       #### ########    ##    ######## ##     ##  ######
		*/


		/**
		 * [filter__manage_edit_product_columns - Add custom columns]
		 * @param  [type] $columns_array [description]
		 * @return [type]                [description]
		 */
		function filter__manage_edit_product_columns( $columns_array ){
			# I want to display Order Count column just after the product name column
			return array_slice( $columns_array, 0, 3, true )
			+ array( 'order_count' => 'Order Count' )
			+ array_slice( $columns_array, 3, NULL, true );
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
		PB()->admin->filter = new PB_Admin_Filter;
	} );
}
