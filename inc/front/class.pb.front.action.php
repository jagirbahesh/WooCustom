<?php
/**
 * PB_Front_Action Class
 *
 * Handles the Frontend Actions.
 *
 * @package WordPress
 * @subpackage WooCustom
 * @since 1.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'PB_Front_Action' ) ){

	/**
	 *  The PB_Front_Action Class
	 */
	class PB_Front_Action {

		function __construct()  {

			add_action( 'wp_enqueue_scripts',                          array( $this, 'action__wp_enqueue_scripts' ) );
			add_action( 'woocommerce_thankyou',                        array( $this, 'action__woocommerce_thankyou' ), 10, 1 );
			add_action( 'woocommerce_before_add_to_cart_button',       array( $this, 'action__woocommerce_before_add_to_cart_button' ) );
			add_action( 'woocommerce_checkout_create_order_line_item', array( $this, 'action__woocommerce_checkout_create_order_line_item' ), 10, 4 );

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
		 * [action__wp_enqueue_scripts - enqueue script in front side]
		 * @return [type] [description]
		 */
		function action__wp_enqueue_scripts() {
			wp_enqueue_script( PB_PREFIX . '_front_js', PB_URL . 'assets/js/front.min.js', array( 'jquery-core' ), PB_VERSION );
		}

		/**
		 * [action__woocommerce_thankyou - Sent coupon code if product is virtual]
		 * @param  int $order_id
		 * @return [type]
		 */
		function action__woocommerce_thankyou( $order_id ) {

			if( ! $order_id ) return;

			# Get order
			$order = wc_get_order( $order_id );

			# get order items = each product in the order
			$items = $order->get_items();

			# Set variable
			$found = false;

			foreach ( $items as $item ) {
				# Get product id
				$product = wc_get_product( $item['product_id'] );

				# Is virtual
				if ( !empty( $item['variation_id'] ) ) {
					$virtual_product = get_post_meta( $item['variation_id'], '_virtual', true );
				} else {
					$virtual_product = $product->is_virtual();
				}

				if( $virtual_product ) {
					$found = true;
					break;
				}

			}

			# if found virtual sent mail
			if( $found ) {

				# Genrate six digit code
				$length = 6;
				$code   = ( strtoupper( substr( md5(time()), 0, $length ) ) );

				# Get admin email
				$admin_email = get_option( 'admin_email' );

				# Some data
				$percent       = 25;
				$discount_type = 'percent';
				$description   = __( 'Given 25% discount on next purchase for specific user', 'woocustom' );

				$coupon = array(
					'post_title'   => $code,
					'post_content' => '',
					'post_excerpt' => $description,
					'post_status'  => 'publish',
					'post_type'    => 'shop_coupon'
				);

				$new_coupon_id = wp_insert_post( $coupon );

				# update meta
				update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
				update_post_meta( $new_coupon_id, 'coupon_amount', $percent );
				update_post_meta( $new_coupon_id, 'individual_use', 'yes' );
				update_post_meta( $new_coupon_id, 'usage_limit_per_user', '1' );
				update_post_meta( $new_coupon_id, 'apply_before_tax', 'yes' );
				update_post_meta( $new_coupon_id, 'customer_email', $admin_email );

				$to      = $order->get_billing_email();
				$subject = __( 'Recevied coupon code for next purchase', 'woocustom' );
				$body    = __( 'Hurrah! Now you can use <strong>' . $code . '</strong> coupon on next purchase.<br> Note: You can use only one time.', 'woocustom' );
				$headers = array('Content-Type: text/html; charset=UTF-8');
				wp_mail( $to, $subject, $body, $headers );
			}

		}

		/**
		 * [action__woocommerce_before_add_to_cart_button - Add textarea in product page]
		 */
		function action__woocommerce_before_add_to_cart_button() {

			global $product;

			if ( in_array( 30, $product->get_category_ids() ) ) {
				ob_start();

				echo '<div class="gift-custom-message">
					<label for="gift_message">' . __( 'Message:', 'woocustom' ) . '</label>
					<textarea id="gift_message" name="gift_text" rows="4" cols="50"></textarea>
				</div>';

				$content = ob_get_contents();
				ob_end_flush();

				return $content;
			}
		}

		/**
		 * [action__woocommerce_checkout_create_order_line_item - Add meta data in checkout create line]
		 * @param  [type] $item          [description]
		 * @param  [type] $cart_item_key [description]
		 * @param  [type] $values        [description]
		 * @param  [type] $order         [description]
		 * @return [type]                [description]
		 */
		function action__woocommerce_checkout_create_order_line_item( $item, $cart_item_key, $values, $order ) {
			if( array_key_exists( 'gift_text', $values ) ) {
				$item->add_meta_data( '_gift_text', $values['gift_text'] );
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
		PB()->front->action = new PB_Front_Action;
	} );
}
