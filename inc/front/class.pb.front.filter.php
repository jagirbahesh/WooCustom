<?php
/**
 * PB_Front_Filter Class
 *
 * Handles the Frontend Filters.
 *
 * @package WordPress
 * @subpackage WooCustom
 * @since 1.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'PB_Front_Filter' ) ) {

	/**
	 *  The PB_Front_Filter Class
	 */
	class PB_Front_Filter {

		function __construct() {

			add_filter( 'woocommerce_add_cart_item_data', array( $this, 'filter__woocommerce_add_cart_item_data'), 10, 3 );
			add_filter( 'woocommerce_get_item_data',      array( $this, 'filter__woocommerce_get_item_data'), 10, 2 );

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
		 * [filter__woocommerce_add_cart_item_data - Show custom field data]
		 * @param [type] $cart_item_data [description]
		 * @param [type] $product_id     [description]
		 * @param [type] $variation_id   [description]
		 */
		function filter__woocommerce_add_cart_item_data( $cart_item_data, $product_id, $variation_id ) {
			if( isset( $_REQUEST['gift_text'] ) ) {
				$cart_item_data['gift_text'] = sanitize_text_field( $_REQUEST['gift_text'] );
			}
			return $cart_item_data;
		}

		/**
		 * [filter__woocommerce_get_item_data - Show custom field data]
		 * @param  [type] $item_data [description]
		 * @param  [type] $cart_item [description]
		 * @return [type]            [description]
		 */
		function filter__woocommerce_get_item_data( $item_data, $cart_item ) {

			if( array_key_exists('gift_text', $cart_item ) ) {
				$custom_details = $cart_item['gift_text'];
				if ( !empty( $custom_details ) ) {
					$item_data[] = array(
						'key'   => 'Message',
						'value' => $custom_details
					);
				}
			}

			return $item_data;
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
		PB()->front->filter = new PB_Front_Filter;
	} );
}
