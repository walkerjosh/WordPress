<?php
/*
  Plugin Name: WooCommerce USPS
  Plugin URI: http://woothemes.com/woocommerce/
  Description: Flat Rate and Realtime shipping rates from USPS.
  Version: 2.1.3
  Author: Andy Zhang
  Author URI: http://hypnoticzoo.com/
  
  Copyright: © 2012-2014 Hypnotic Zoo.
  License: GNU General Public License v3.0
  License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */


 

add_action( 'plugins_loaded', 'woocommerce_usps_init', 0 );

function woocommerce_usps_init() {

	if ( ! class_exists( 'WC_Shipping_Method' ) )
		return;

	if ( ! class_exists( 'xmlparser' ) )
		require_once 'xmlparser.php';

	/**
	 * Required functions
	 * */
	if ( ! function_exists( 'is_woocommerce_active' ) )
		require_once 'woo-includes/woo-functions.php';

	/**
	 * Plugin updates
	 *
	 * */
	if ( is_admin() ) {
		$woo_plugin_updater_usps = new WooThemes_Plugin_Updater( __FILE__ );
		$woo_plugin_updater_usps->api_key = '407ed6f20adfa18f76afb9ddc30b9af7';
		$woo_plugin_updater_usps->init();
	}

	/**
	 * Localisation
	 */
	load_plugin_textdomain( 'wc_usps', false, dirname( plugin_basename( __FILE__ ) ) . '/' );

	/**
	 * First step is to make sure the configuration of woocommerce is correct.
	 * Raise warning if is not correct.
	 */
	add_action( 'admin_notices', 'woocommerce_usps_check' );

	function woocommerce_usps_check() {
		global $woocommerce;

		if ( get_option( 'woocommerce_currency' ) != "USD" ) {

			echo '<div class="error"><p>' . sprintf( __( 'USPS is activated, but the <a href="%s">currency</a> is not USD.', 'wc_usps' ), admin_url( 'admin.php?page=woocommerce&tab=general' ) ) . '</p></div>';

		}

		if ( $woocommerce->countries->get_base_country() != "US" && $woocommerce->countries->get_base_country() != "PR" && $woocommerce->countries->get_base_country() != "VI" ) {

			echo '<div class="error"><p>' . sprintf( __( 'USPS is activated, but the <a href="%s">base country/region</a> is not United States.', 'wc_usps' ), admin_url( 'admin.php?page=woocommerce&tab=general' ) ) . '</p></div>';

		}
	}

	/**
	 * Shipping method class
	 * */
	class WC_Shipping_USPS extends WC_Shipping_Method {

		var $url = "http://production.shippingapis.com/shippingapi.dll";

		var $services = array(
			"d0" 	=> "First-Class Mail Parcel",
			"d1" 	=> "Priority Mail",
			"d2" 	=> "Express Mail Hold for Pickup",
			"d3" 	=> "Express Mail PO to Address",
			"d4" 	=> "Parcel Post",
			"d5" 	=> "Bound Printed Matter",
			"d6" 	=> "Media Mail",
			"d7" 	=> "Library Mail",
			"d12" 	=> "First-Class Postcard Stamped",
			"d15" 	=> "First-Class Large Postcards",
			"d18" 	=> "Priority Mail Keys and IDs",
			"d19" 	=> "First-Class Keys and IDs",
			"d23" 	=> "Express Mail Sunday/Holiday",
			"i1" 	=> "Express Mail International",
			"i2" 	=> "Priority Mail International",
			"i4" 	=> "Global Express Guaranteed",
			"i5" 	=> "Global Express Guaranteed Document used",
			"i6" 	=> "Global Express Guaranteed Non-Document Rectangular",
			"i7" 	=> "Global Express Guaranteed Non-Document Non-Rectangular",
			"i12" 	=> "Global Express Guaranteed Envelope",
			"i13" 	=> "First Class Mail International Letters",
			"i14" 	=> "First Class Mail International Flats",
			"i15" 	=> "First Class Mail International Parcel",
			"i21" 	=> "International Postcards"
		);

		// Flat Rates don't need to come from the API because the costs are fixed
		var $flat_rates = array(

			// Express Mail
			"d13" 	=> array(
				'name' 	=> "Express Mail Flat Rate Envelope",
				'length' 	=> '12.5',
				'width' 	=> '9.5',
				'height' 	=> '0.5',
				'weight' 	=> '70',
				'cost'		=> '18.95'
			),
			/*"d25" 	=> array(
				'name'		=> "Express Mail Flat Rate Envelope (Sunday/Holiday)",
				'length' 	=> '12.5',
				'width' 	=> '9.5',
				'height' 	=> '0.5',
				'weight' 	=> '70',
				'cost'		=> '5.35'
			),
			"d27" 	=> array(
				'name'		=> "Express Mail Flat Rate Envelope (Hold for Pickup)",
				'length' 	=> '12.5',
				'width' 	=> '9.5',
				'height' 	=> '0.5',
				'weight' 	=> '70',
				'cost'		=> '5.35'
			),*/
			"d30" 	=> array(
				'name'		=> "Express Mail Legal Flat Rate Envelope",
				'length' 	=> '9.5',
				'width' 	=> '15',
				'height' 	=> '2',
				'weight' 	=> '70',
				'cost'		=> '18.95'
			),
			/*"d31" 	=> array(
				'name'		=> "Express Mail Legal Flat Rate Envelope (Hold For Pickup)",
				'length' 	=> '9.5',
				'width' 	=> '15',
				'height' 	=> '2',
				'weight' 	=> '70',
				'cost'		=> '5.35'
			),*/
			"d55" 	=> array(
				'name'		=> "Express Mail Flat Rate Boxes",
				'length' 	=> '11',
				'width' 	=> '8.5',
				'height' 	=> '5.5',
				'weight' 	=> '70',
				'cost'		=> '39.95'
			),
			/*"d56" 	=> array(
				'name'		=> "Express Mail Flat Rate Boxes (Hold For Pickup)",
				'length' 	=> '11',
				'width' 	=> '8.5',
				'height' 	=> '5.5',
				'weight' 	=> '70',
				'cost'		=> '5.35'
			),*/
			"d63" 	=> array(
				'name'		=> "Express Mail Padded Flat Rate Envelope",
				'length' 	=> '12.5',
				'width' 	=> '9.5',
				'height' 	=> '2',
				'weight' 	=> '70',
				'cost'		=> '18.95'
			),

			// Priority Mail
			"d16" 	=> array(
				'name'		=> "Priority Mail Flat Rate Envelope",
				'length' 	=> '12.5',
				'width' 	=> '9.5',
				'height' 	=> '0.5',
				'weight' 	=> '70',
				'cost'		=> '5.15'
			),
			"d17" 	=> array(
				'name'		=> "Priority Mail Flat Rate Medium Box",
				'length' 	=> '11.875',
				'width' 	=> '13.625',
				'height' 	=> '3.375',
				'weight' 	=> '70',
				'cost'		=> '11.35'
			),
			"d22" 	=> array(
				'name' 		=> "Priority Mail Flat Rate Large Box",
				'length' 	=> '12',	// Inch
				'width' 	=> '12',	// Inch
				'height' 	=> '5.5',	// Inch
				'weight' 	=> '70',	// Max weight in Pounds
				'cost'		=> '15.45'
			),
			"d28" 	=> array(
				'name'		=> "Priority Mail Flat Rate Small Box",
				'length' 	=> '5.375',
				'width' 	=> '8.625',
				'height' 	=> '1.625',
				'weight' 	=> '70',
				'cost'		=> '5.35'
			),
			"d29" 	=> array(
				'name' 		=> "Priority Mail Padded Flat Rate Envelope",
				'length' 	=> '12.5',
				'width' 	=> '9.5',
				'height' 	=> '2',
				'weight' 	=> '70',
				'cost'		=> '5.30'
			),
			"d38" 	=> array(
				'name' 		=> "Priority Mail Gift Card Flat Rate Envelope",
				'length' 	=> '10',
				'width' 	=> '7',
				'height' 	=> '0.5',
				'weight' 	=> '70',
				'cost'		=> '5.15'
			),
			"d40" 	=> array(
				'name' 		=> "Priority Mail Window Flat Rate Envelope",
				'length' 	=> '5',
				'width' 	=> '10',
				'height' 	=> '0.5',
				'weight' 	=> '70',
				'cost'		=> '5.15'
			),
			"d42" 	=> array(
				'name' 		=> "Priority Mail Small Flat Rate Envelope",
				'length' 	=> '6',
				'width' 	=> '10',
				'height' 	=> '0.5',
				'weight' 	=> '70',
				'cost'		=> '5.15'
			),
			"d44" 	=> array(
				'name' 		=> "Priority Mail Legal Flat Rate Envelope",
				'length' 	=> '9.5',
				'width' 	=> '15',
				'height' 	=> '2',
				'weight' 	=> '70',
				'cost'		=> '5.30'
			),

			// International Express Mail
			"i10" 	=> array(
				'name'		=> "Express Mail International Flat Rate Envelope",
				'length' 	=> '12.5',
				'width' 	=> '9.5',
				'height' 	=> '0.625',
				'weight' 	=> '70'
			),
			"i26" 	=> array(
				'name'		=> "Express Mail International Flat Rate Boxes",
				'length' 	=> '11',
				'width' 	=> '8.5',
				'height' 	=> '5.5',
				'weight' 	=> '20'
			),

			// International Priority Mail
			"i8" 	=> array(
				'name'		=> "Priority Mail Flat Rate Envelope (International)",
				'length' 	=> '12.5',
				'width' 	=> '9.5',
				'height' 	=> '0.625',
				'weight' 	=> '4',
				'cost2'		=> '12.95',
				'cost'		=> '16.95',	// Inter
			),
			"i16" 	=> array(
				'name'		=> "Priority Mail Flat Rate Small Box (International)",
				'length' 	=> '5.375',
				'width' 	=> '8.625',
				'height' 	=> '1.625',
				'weight' 	=> '4',
				'cost2'		=> '12.95', // Canada and Mexico
				'cost'		=> '16.95',	// Inter
			),
			"i9" 	=> array(
				'name'		=> "Priority Mail Flat Rate Medium Box (International)",
				'length' 	=> '11.875',
				'width' 	=> '13.625',
				'height' 	=> '3.375',
				"weight" 	=> '20',
				'cost2'		=> '32.95',
				'cost'		=> '47.95',	// Inter
			),
			"i11" 	=> array(
				'name' 		=> "Priority Mail Flat Rate Large Box (International)",
				'length' 	=> '12',	// Inch
				'width' 	=> '12',	// Inch
				'height' 	=> '5.5',	// Inch
				'weight' 	=> '20',
				'cost2'		=> '39.95',
				'cost'		=> '60.95',	// Inter
			),
			"i24" 	=> array(
				'name'		=> "Priority Mail DVD Flat Rate Box (International)",
				'length' 	=> '7.5625',	// Inch
				'width' 	=> '5.4375',	// Inch
				'height' 	=> '0.625',		// Inch
				'weight' 	=> '20',
				'cost2'		=> '12.95',
				'cost'		=> '16.95',	// Inter
			),
			"i25" 	=> array(
				'name'		=> "Priority Mail Large Video Flat Rate Box (International)",
				'length' 	=> '9.25',	// Inch
				'width' 	=> '6.25',	// Inch
				'height' 	=> '2',		// Inch
				'weight' 	=> '20',
				'cost2'		=> '12.95',
				'cost'		=> '16.95',	// Inter
			)

		);

		function __construct() {
			global $woocommerce;

			$this->id = 'usps';
			$this->method_title = __( 'USPS', 'wc_usps' );

			// Load the form fields.
			$this->init_form_fields();

			// Load the settings.
			$this->init_settings();

			$this->enabled 			= $this->settings['enabled'];
			$this->title 			= $this->settings['title'];
			$this->availability 	= $this->settings['availability'];
			$this->origin 			= $this->settings['origin'];
			$this->type 			= $this->settings['type'];
			$this->cheapest_rate_only = ! empty( $this->settings['cheapest_rate_only'] ) && $this->settings['cheapest_rate_only'] == 'yes' ? true : false;
			$this->fallback			= ! empty( $this->settings['fallback'] ) ? $this->settings['fallback'] : '';
			$this->enable_custom_box = ! empty( $this->settings['enable_custom_box'] ) && $this->settings['enable_custom_box'] == 'yes' ? true : false;
			$this->height 			= ! empty( $this->settings['box_height'] ) ? $this->settings['box_height'] : '';
			$this->length 			= ! empty( $this->settings['box_length'] ) ? $this->settings['box_length'] : '';
			$this->width 			= ! empty( $this->settings['box_width'] ) ? $this->settings['box_width'] : '';
			$this->weight			= ! empty( $this->settings['box_weight'] ) ? $this->settings['box_weight'] : '';
			$this->girth 			= $this->settings['box_girth'];
			$this->box 				= $this->box_varify( $this->length, $this->width, $this->height );

			$this->origin_country 	= $woocommerce->countries->get_base_country();

			$this->shipping_availability = $this->settings['shipping_availability'];

			if ( $this->shipping_availability == 'all' ) {
				$this->shipping_methods = array_keys( $this->services );
			} else {
				$this->shipping_methods = is_array( $this->settings['shipping_methods'] ) && sizeof( $this->settings['shipping_methods'] > 0 ) ? $this->settings['shipping_methods'] : array();
			}

			$this->enabled_flat_rates = is_array( $this->settings['flat_rates'] ) ? $this->settings['flat_rates'] : array();
			$this->countries 		= $this->settings['countries'];
			$this->tax_status 		= $this->settings['tax_status'];
			$this->fee 				= $this->settings['fee'];
			$this->user_id 			= "474HYPNO7276";
			$this->intl_shipping 	= false;
			$this->debug 			= $this->settings['debug'];

			// When a method has more than one cost/choice it will be in this array of titles/costs
			$this->shipping_type = array();

			add_action( 'woocommerce_update_options_shipping_usps', array( &$this, 'process_admin_options' ) );
			add_action( 'woocommerce_update_options_shipping_methods', array( &$this, 'process_admin_options' ) );
		}

		function box_varify( $length, $width, $height ) {
			if ( $width && $height && $length ) {
				return $width * $height * $length;
			}
			return false;
		}

		/**
		 * get_flat_rate_options function.
		 *
		 * @access public
		 * @return void
		 */
		function get_flat_rate_options() {
			$options = array();
			foreach ( $this->flat_rates as $key => $value ) {
				$options[ $key ] = $value['name'];
			}
			return $options;
		}

		/**
		 * Initialise Gateway Settings Form Fields
		 */
		function init_form_fields() {
			global $woocommerce;

			$this->form_fields = array(
				'enabled' => array(
					'title' => __( 'Enable/Disable', 'wc_usps' ),
					'type' => 'checkbox',
					'label' => __( 'Enable USPS', 'wc_usps' ),
					'default' => 'yes'
				),
				'debug' => array(
					'title' => __( 'Debug', 'wc_usps' ),
					'type' => 'checkbox',
					'label' => __( 'Enable debugging', 'wc_usps' ),
					'default' => 'no'
				),
				'title' => array(
					'title' => __( 'Method Title', 'wc_usps' ),
					'type' => 'text',
					'description' => __( 'This controls the title which the user sees during checkout.', 'wc_usps' ),
					'default' => __( 'USPS', 'wc_usps' )
				),
				'origin' => array(
					'title' => __( 'Origin Zipcode', 'wc_usps' ),
					'type' => 'text',
					'description' => __( 'Enter your origin zip code.', 'wc_usps' ),
					'default' => __( '', 'wc_usps' )
				),
				'tax_status' => array(
					'title' => __( 'Tax Status', 'wc_usps' ),
					'type' => 'select',
					'description' => '',
					'default' => 'taxable',
					'options' => array(
						'taxable' => __( 'Taxable', 'wc_usps' ),
						'none' => __( 'None', 'wc_usps' )
					)
				),
				'availability' => array(
					'title' => __( 'Availability', 'wc_usps' ),
					'type' => 'select',
					'default' => 'all',
					'class' => 'availability',
					'options' => array(
						'all' => __( 'All allowed countries', 'wc_usps' ),
						'specific' => __( 'Specific Countries', 'wc_usps' )
					)
				),
				'countries' => array(
					'title' => __( 'Specific Countries', 'wc_usps' ),
					'type' => 'multiselect',
					'class' => 'chosen_select',
					'css' => 'width: 600px; ',
					'default' => '',
					'options' => $woocommerce->countries->get_allowed_countries()
				),
				'fee' => array(
					'title' => __( 'Handling Fee', 'wc_usps' ),
					'type' => 'text',
					'description' => __( 'Fee excluding tax. Enter an amount, e.g. 2.50, or a percentage, e.g. 5%.', 'wc_usps' ),
					'default' => ''
				),
				'type' => array(
					'title' => __( 'Type', 'wc_usps' ),
					'type' => 'select',
					'description' => '',
					'default' => 'order',
					'options' => array(
						'per_order' => __( 'Per Order', 'wc_usps' ),
						'per_item' => __( 'Per Item', 'wc_usps' )
					)
				),
				'flat_rates' => array(
					'title' => __( 'Flat Rate Boxes', 'wc_usps' ),
					'type' => 'multiselect',
					'class' => 'chosen_select',
					'description' => '<br/>' . __( 'Use predefinded box sizes for Priority and Express Mail. See <a href="https://www.usps.com/ship/service-chart.htm?">https://www.usps.com/ship/service-chart.htm?</a>', 'wc_usps' ),
					'default' => '',
					'options' => $this->get_flat_rate_options(),
					'css' => 'width: 600px; '
				),
				'enable_custom_box' => array(
					'title' => __( 'Custom box size', 'wc_usps' ),
					'type' => 'checkbox',
					'label' => __( 'Enable custom box size', 'wc_usps' ),
					'description' => __( 'With per order shipping, enable a custom box size to define package dimensions of your own. USPS will fit as many items as it can in your box (using multiple boxes if neccessary) and then return all applicable rates.', 'wc_usps' ),
					'default' => 'no'
				),
				'box_height' => array(
					'title' => __( 'Custom Box Height', 'wc_usps' ),
					'type' => 'text',
					'description' => __( 'Custom box size in <strong>inches</strong>.', 'wc_usps' ),
					'default' => ''
				),
				'box_length' => array(
					'title' => __( 'Custom Box Length', 'wc_usps' ),
					'type' => 'text',
					'description' => __( 'Custom box size in <strong>inches</strong>.', 'wc_usps' ),
					'default' => ''
				),
				'box_width' => array(
					'title' => __( 'Custom Box Width', 'wc_usps' ),
					'type' => 'text',
					'description' => __( 'Custom box size in <strong>inches</strong>.', 'wc_usps' ),
					'default' => ''
				),
				'box_girth' => array(
					'title' => __( 'Custom Box Girth', 'wc_usps' ),
					'type' => 'text',
					'description' => __( 'If your box is not rectangle, you can define the girth to get a more accurate rate in <strong>inches</strong>.', 'wc_usps' ),
					'default' => '0'
				),
				'box_weight' => array(
					'title' => __( 'Box Weight', 'wc_usps' ),
					'type' => 'text',
					'description' => __( 'Custom maximum box weight in <strong>pounds</strong>.', 'wc_usps' ),
					'default' => '30'
				),
				'shipping_availability' => array(
					'title' => __( 'Other USPS Rates', 'wc_usps' ),
					'type' => 'select',
					'default' => 'all',
					'class' => 'availability',
					'description' => __( 'Additional calculated rates. If you are using per-order shipping you must enter a custom box size above.', 'wc_usps' ),
					'options' => array(
						'all' => __( 'Enable all rates', 'wc_usps' ),
						'specific' => __( 'Specific specific rates', 'wc_usps' )
					),
				),
				'shipping_methods' => array(
					'title' => __( 'Specific Rates', 'wc_usps' ),
					'type' => 'multiselect',
					'class' => 'chosen_select',
					'css' => 'width: 600px; ',
					'description' => '',
					'default' => '',
					'options' => $this->services
				),
				'cheapest_rate_only' => array(
					'title' => __( 'Show single option', 'wc_usps' ),
					'type' => 'checkbox',
					'label' => __( 'Show cheapest shipping option only', 'wc_usps' ),
					'description' => __( 'By default, USPS displays all rates to the customer for selection. Enable this to only show the cheapest.', 'wc_usps' ),
					'default' => 'no'
				),
				'fallback' => array(
					'title' => __( 'Fallback', 'wc_usps' ),
					'type' => 'text',
					'description' => __( 'If USPS returns no matching rates, offer this amount for shipping so that the user can still checkout. Leave blank to disable.', 'wc_usps' ),
					'default' => ''
				),
			);
		}

		/**
		 * Shipping method available condition:
		 * 1. Set to yes
		 * 2. Currency is USD
		 * 3. Origin country is US
		 * 4. Dest country is in the list
		 *
		 * @global type $woocommerce
		 * @return type
		 */
		function is_available() {
			global $woocommerce;

			if ( $this->enabled == "no" )
				return false;

			if ( get_option( 'woocommerce_currency' ) != "USD" )
				return false;

			if ( $this->origin_country != "US" && $this->origin_country != "PR" && $this->origin_country != "VI" )
				return false;

			if ( ! $this->origin )
				return false;

			$ship_to_countries = '';

			if ( $this->availability == 'specific' )
				$ship_to_countries = $this->countries;
			elseif ( get_option( 'woocommerce_allowed_countries' ) == 'specific' )
				$ship_to_countries = get_option( 'woocommerce_specific_allowed_countries' );

			if ( is_array( $ship_to_countries ) )
				if ( ! in_array( $woocommerce->customer->get_shipping_country(), $ship_to_countries ) )
					return false;

			return true;
		}

		/**
		 * Check if it's international shipping
		 */
		function is_international() {
			global $woocommerce;
			$customer = $woocommerce->customer;
			if ( $customer->get_shipping_country() == "US" || $customer->get_shipping_country() == "PR" || $customer->get_shipping_country() == "VI" ) {
				return false;
			}
			return true;
		}

		/**
		 * Get shipping quotes based on change of shipping address
		 *
		 * @method calculate_shipping
		 * @abstract setup shipping rate for each selected shipping option
		 */
		function calculate_shipping() {
			global $woocommerce;

			$response = $this->get_shipping_response();

			// Get allowed methods
			$allowed_methods = array_merge( $this->enabled_flat_rates, $this->shipping_methods );

			// Adding rates
			$rates_to_add = array();

			if ( is_array( $response ) ) {
				//setup multiple rates, calculate tax and handling fee
				foreach ( $response as $id => $rate ) {

					if ( in_array( ( $this->is_international() ? 'i' : 'd' ) . $id, $allowed_methods ) ) {
						if ( isset( $rate['rate'] ) ) {
							$response[$id]['rate'] = $rate['rate'] + $this->get_fee( $this->fee, $woocommerce->cart->cart_contents_total );

							if ( isset( $this->services[ ( $this->is_international() ? 'i' : 'd' ) . $id ] ) )
								$label = $this->services[ ( $this->is_international() ? 'i' : 'd' ) . $id ];
							elseif ( isset( $this->flat_rates[ ( $this->is_international() ? 'i' : 'd' ) . $id ] ) )
								$label = $this->flat_rates[ ( $this->is_international() ? 'i' : 'd' ) . $id ]['name'];

							$rates_to_add[] = array(
								"id" 		=> "usps_" . $id,
								"label" 	=> "USPS: " . $label,
								"cost" 		=> $response[$id]['rate'],
								"calc_tax" 	=> "per_order"
							);
						}
					}
				}
			}

			if ( sizeof( $rates_to_add ) == 0 && $woocommerce->customer->get_shipping_country() && $woocommerce->customer->get_shipping_postcode() ) {

				if ( $this->fallback > 0 ) {
					$this->add_rate( array(
						"id" 		=> "usps_fallback",
						"label" 	=> __( 'USPS Shipping', 'wc_usps' ),
						"cost" 		=> $this->fallback,
						"calc_tax" 	=> "per_order"
					) );
				}

			} elseif ( $this->cheapest_rate_only ) {

				$cheapest_cost = null;
				$cheapest = '';

				foreach ( $rates_to_add as $id => $rate ) {
					if ( is_null( $cheapest_cost ) ) {
						$cheapest = $id;
						$cheapest_cost = $rate['cost'];
					}

					if ( $rate['cost'] <= $cheapest_cost ) {
						$cheapest = $id;
						$cheapest_cost = $rate['cost'];
					}
				}
				
				$cheapest_rate[ $cheapest ] = $rates_to_add[ $cheapest ];
				
				$cheapest_rate[ $cheapest ]['label'] = __( 'USPS Shipping', 'wc_usps' );

				$this->add_rate( $cheapest_rate[ $cheapest ] );

			} else {

				foreach ( $rates_to_add as $id => $rate )
					$this->add_rate( $rate );

			}
		}

		/**
		 * post_request function.
		 *
		 * @access public
		 * @param mixed $request_queue
		 * @return void
		 */
		function post_request( $request_queue ) {
			global $woocommerce;
			$results = array();
			foreach ( $request_queue as $request ) {
				$response = wp_remote_post( $this->url, array(
						'method' => 'POST',
						'body' => $request,
						'timeout' => 70,
						'sslverify' => 0
					) );
				if ( !is_wp_error( $response ) ) {
					$results[] = $this->decode( $response['body'] );

					if ( $this->debug == 'yes' )
						$woocommerce->add_message( 'USPS response were returned: <br/><pre style="white-space: pre-wrap;">' . print_r( $results, true ) . '</pre>' );
				} else {

					if ( $this->debug == 'yes' )
						$woocommerce->add_message( 'USPS response: <br/><pre style="white-space: pre-wrap;">' . print_r( $response, true ) . '</pre>' );
				}
			}
			return $results;
		}

		/**
		 * request shipping rates for each request batch,
		 * process the response and return as one set of result
		 *
		 */
		function get_shipping_response() {
			global $woocommerce;

			// Get customer
			$customer = $woocommerce->customer;

			if ( sizeof( $woocommerce->cart->get_cart() ) == 0 || ! $customer->get_shipping_country() )
				return false;

			$product_bucket = false;

			//encoding the xml according to the destination
			$cart = $woocommerce->cart->get_cart();

			//deal with different packing type
			if ( $this->type == "per_item" ) {

				// If we are calculating per-item, flat rate boxes can be included in the main request
				foreach( $this->enabled_flat_rates as $id => $flat_rate ) {
					$this->services[ $id ] = $flat_rate['name'];
				}

				$product_bucket = $this->packing_perItem( $cart );

			} elseif ( $this->enable_custom_box && $this->weight && $this->box ) {

				// This requires a custom box
				$product_bucket = $this->packing_perOrder( $cart, $this->box, $this->length, $this->width, $this->height, $this->weight );

			}

			if ( $product_bucket ) {

				$request_queue = $this->encode( $product_bucket );
				$results = $this->post_request( $request_queue );

				// Add up the rates for each batch
				if ( sizeof( $results ) > 0 ) {
					$base_result = array_shift( $results );
					if ( is_array( $base_result ) ) {
						foreach ( $results as $key => $result ) {
							foreach ( $base_result as $id => $base ) {
								$base_result[$id]["rate"] += $result[$id]["rate"];
							}
						}
					}
				}

				# Take care first-class parcel as a special case
				if ( in_array('d0', $this->shipping_methods) ){
					$request_queue = $this->encode( $product_bucket, 'FIRST CLASS' );
					$results = $this->post_request( $request_queue );
					$first_class_id = 0;
					if ( sizeof( $results ) > 0 ) {
						$first_class = array_shift( $results );
						if ( is_array( $first_class ) ) {
							foreach ( $results as $key => $result ) {
								foreach ( $first_class as $id => $base ) {
									$first_class_id = $id;
									$first_class[$id]["rate"] += $result[$id]["rate"];
								}
							}
						}
						$base_result[$first_class_id] = $first_class[$first_class_id];
					}
				}

			}

			// Next, handle flat rates for per-order shipping
			if ( $this->type == "per_order" && ! empty( $this->enabled_flat_rates ) ) {

				$intel = $this->is_international() ? 'i' : 'd';

				foreach ( $this->enabled_flat_rates as $id ) {

					if ( ! $id )
						continue;

					$flat_rate = $this->flat_rates[ $id ];

					// Check the rate is international vs domestic
					if ( substr( $id, 0, 1 ) == $intel ) {

						$product_bucket = $this->packing_perOrder( $cart, $this->box_varify( $flat_rate['length'], $flat_rate['width'], $flat_rate['height'] ), $flat_rate['length'], $flat_rate['width'], $flat_rate['height'], $flat_rate['weight'] );

						$idnum = str_replace( array( 'i', 'd' ), '', $id );

						if ( isset( $flat_rate['cost'] ) && sizeof($product_bucket) ) {

							// Product bucket size dictates number of boxes needed

							if ( ( $customer->get_shipping_country() == 'CA' || $customer->get_shipping_country() == 'MX' ) && isset( $flat_rate['cost2'] ) )
								$base_result[ $idnum ]["rate"] = $flat_rate['cost2'] * sizeof( $product_bucket );
							else
								$base_result[ $idnum ]["rate"] = $flat_rate['cost'] * sizeof( $product_bucket );

						} else {

							// Use API
							$special_request_queue = $this->encode( $product_bucket );
							$results = $this->post_request( $special_request_queue );

							if ( sizeof( $results ) > 0 ) {

								$idnum = str_replace( array( 'i', 'd' ), '', $id );
								$special_base_result = array_shift( $results );

								// Only get the result we want
								if ( isset( $special_base_result[ $idnum ] ) )
									$base_result[ $idnum ]["rate"] = $special_base_result[ $idnum ]["rate"];
							}

						}

					}

				}

			}

			return ( isset( $base_result ) && count( $base_result ) ) ? $base_result : false;
		}

		/**
		 * Encode the request
		 */
		function encode( $product_bucket = array(), $service = "ALL" ) {
			global $woocommerce;

			$request = array();

			// Get customer
			$customer = $woocommerce->customer;

			//chunk request to 25 packages per batch
			$product_bucket = array_chunk( $product_bucket, 25 );

			if ( !$this->is_international() ) {
				foreach ( $product_bucket as $batch ) {
					$xml = 'API=RateV4&XML=<RateV4Request USERID="' . $this->user_id . '">';
					$package = '';
					foreach ( $batch as $id => $product ) {
						$weight 	= $this->unify_weight( $product['weight'] );
						$height 	= $this->unify_size( $product['height'] );
						$length 	= $this->unify_size( $product['length'] );
						$width 		= $this->unify_size( $product['width'] );
						$size 		= ( max( $height, $length, $width ) > 12 ) ? "LARGE" : "REGULAR";
						$girth 		= $product['girth'];

						$package 	.= '             <Package ID="' . $id . '">';
						$package 	.= '                 <Service>' . $service . '</Service>';
						if($service != 'ALL')
							$package 	.= '                 <FirstClassMailType>PARCEL</FirstClassMailType>';
						$package 	.= '                 <ZipOrigination>' . $this->origin . '</ZipOrigination>';
						$package 	.= '                 <ZipDestination>' . $customer->get_shipping_postcode() . '</ZipDestination>';
						$package 	.= '                 <Pounds>' . ( floor( $weight ) ) . '</Pounds>';
						$package 	.= '                 <Ounces>' . ( $weight - floor( $weight ) ) * 16 . '</Ounces>';
						$package 	.= '                 <Container/>';
						$package 	.= '                 <Size>' . $size . '</Size>';
						//extra information for large item
						if ( $size == "LARGE" ) {

							$package .= '                 <Width>' . $width . '</Width>';
							$package .= '                 <Length>' . $length . '</Length>';
							$package .= '                 <Height>' . $height . '</Height>';
							$package .= '                 <Girth>' . $girth . '</Girth>';
						}
						$package 	.= '                 <Machinable>true</Machinable>';
						$package 	.= '                 <ShipDate>' . date( "d-M-Y", time() + 172800 ) . '</ShipDate>';

						$package 	.= '             </Package>';
					}
					$xml .= $package;
					$xml .= '</RateV4Request>';
					$request[] = $xml;
				}
			} else {
				$this->intl_shipping = true;
				foreach ( $product_bucket as $batch ) {
					$xml = 'API=IntlRateV2&XML=<IntlRateV2Request USERID="' . $this->user_id . '">';
					$package = '';
					foreach ( $batch as $id => $product ) {
						$weight 	= $this->unify_weight( $product['weight'] );
						$height 	= $this->unify_size( $product['height'] );
						$length 	= $this->unify_size( $product['length'] );
						$width 		= $this->unify_size( $product['width'] );
						$size 		= ( max( $height, $length, $width ) > 12 ) ? "LARGE" : "REGULAR";
						$girth 		= $product['girth'];
						$country 	= $woocommerce->countries->get_allowed_countries();
						$shipping_country = $customer->get_shipping_country();

						$package .= '             <Package ID="' . $id . '">';
						$package .= '                 <Pounds>' . ( floor( $weight ) ) . '</Pounds>';
						$package .= '                 <Ounces>' . ( $weight - floor( $weight ) ) * 16 . '</Ounces>';
						$package .= '                 <Machinable>true</Machinable>';
						$package .= '                 <MailType>Package</MailType>';
						$package .= '                 <ValueOfContents>' . $product['value'] . '</ValueOfContents>';
						$package .= '                 <Country>' . strtoupper( $country[ $shipping_country ] ) . '</Country>';
						$package .= '                 <Container>RECTANGULAR</Container>';
						$package .= '                 <Size>' . $size . '</Size>';
						$package .= '                 <Width>' . $width . '</Width>';
						$package .= '                 <Length>' . $length . '</Length>';
						$package .= '                 <Height>' . $height . '</Height>';
						$package .= '                 <Girth>' . $girth . '</Girth>';
						$package .= '             </Package>';
					}
					$xml .= $package;
					$xml .= '</IntlRateV2Request>';
					$request[] = $xml;
				}
			}

			//echo '<pre style="white-space: pre-wrap;">';
			//var_dump( htmlspecialchars( implode( '', $request ) ) );
			//echo '</pre>';

			return $request;
		}

		/**
		 * Decode the result
		 */
		function decode( $response ) {
			$xmlParser = new xmlparser();
			$xml_response = $xmlParser->GetXMLTree( $response );
			$packages = array();
			$rates = array();
			if ( !$response ) {
				return false;
			}

			if ( !$this->intl_shipping ) {
				if ( isset( $xml_response['RATEV4RESPONSE'] ) && $xml_response['RATEV4RESPONSE'] ) {
					$packages = $xml_response['RATEV4RESPONSE'][0]['PACKAGE'];
					//each package may have different services, we only calculate the common services throughout all packages
					foreach ( $packages as $id => $package ) {
						if ( isset( $package['ERROR'] ) && $package['ERROR'] ) {
							return $package['ERROR'][0]['DESCRIPTION'][0]['VALUE'];
						}
						$services = $package['POSTAGE'];
						$package_rates = array();
						foreach ( $services as $service ) {
							$service_id = $service['ATTRIBUTES']['CLASSID'];
							$service_name = $service['MAILSERVICE'][0]['VALUE'];
							$service_rate = $service['RATE'][0]['VALUE'];
							$package_rates[$service_id]['name'] = $service_name;
							$package_rates[$service_id]['rate'] = ( isset( $rates[$service_id]['rate'] ) ) ? $rates[$service_id]['rate'] + $service_rate : $service_rate;
						}
						$rates = array_intersect_key( $package_rates, ( count( $rates ) ? $rates : $package_rates ) );
					}
				}
			} elseif ( $this->intl_shipping ) {
				if ( isset( $xml_response['INTLRATEV2RESPONSE'] ) && $xml_response['INTLRATEV2RESPONSE'] ) {
					$packages = $xml_response['INTLRATEV2RESPONSE'][0]['PACKAGE'];
					//each package may have different services, we only calculate the common services throughout all packages
					foreach ( $packages as $id => $package ) {
						if ( isset( $package['ERROR'] ) && $package['ERROR'] ) {
							return $package['ERROR'];
						}
						$services = $package['SERVICE'];
						$package_rates = array();
						foreach ( $services as $id => $service ) {
							$service_id = $service['ATTRIBUTES']['ID'];
							$service_name = $service['SVCDESCRIPTION'][0]['VALUE'];
							$service_name = str_replace( '*', '', $service_name );
							$service_rate = $service['POSTAGE'][0]['VALUE'];
							$package_rates[$service_id]['name'] = $service_name;
							$package_rates[$service_id]['rate'] = ( isset( $rates[$service_id]['rate'] ) ) ? $rates[$service_id]['rate'] + $service_rate : $service_rate;
						}
						$rates = array_intersect_key( $package_rates, ( count( $rates ) ? $rates : $package_rates ) );
					}
				}
			}

			return $rates;
		}

		/**
		 * packing order for per-order shipping. 70pound max weight by default
		 */
		function packing_perOrder( $cart, $box_size, $length, $width, $height, $max_weight = 70 ) {
			$product_bucket = array();
			$weights = array();
			$value = array();
			$volume = 0;
			$acc_weight = 0;
			$acc_price = 0;
			$box = 0;
			if ( $box_size ) {
				foreach ( $cart as $item_id => $values ) {

					$_product = $values['data'];

					//if any product can't put into the box, don't calculate the rate
					if( !$this->is_fit($_product, array($length, $width, $height)) )
						return $product_bucket;

					if ( $_product->exists() && $values['quantity'] > 0 ) {

						if ( ! $_product->is_virtual() ) {
							for ( $i = 0; $i < $values['quantity']; $i++ ) {
								$volume 	+= $this->product_volume( $_product->length, $_product->width, $_product->height );
								$weight 	= $_product->get_weight();
								if ( $volume <= $box_size && ( $acc_weight + $weight ) <= $max_weight ) {
									$acc_price 		+= $_product->get_price();
									$acc_weight 	+= $weight;
								} else {
									$value[$box] 	= $acc_price;
									$weights[$box] 	= $acc_weight;
									$acc_price 		= $_product->get_price();
									$acc_weight 	= $_product->get_weight();
									$volume 		= $this->product_volume( $_product->length, $_product->width, $_product->height );
									$box++;
								}
							}
							$value[$box] = $acc_price;
							$weights[$box] = $acc_weight;
						}
					}
				}

				//make sure no package weight exceed 20kgs or don't calculate the shipping
				if(empty($weights))
					return $product_bucket;

				$max_box_weight = woocommerce_get_weight( max( $weights ), 'lbs' );

				if ( $max_box_weight <= $max_weight ) {
					foreach ( $weights as $weight_id => $weight ) {
						$girth = ( $this->girth ) ? $this->girth : ( round( $height ) + round( $weight ) ) * 2;
						$product = array(
							"weight" 	=> $weight,
							"height" 	=> $height,
							"length" 	=> $length,
							"width" 	=> $width,
							"girth" 	=> $girth,
							"value" 	=> $value[ $weight_id ]
						);
						$product_bucket[$weight_id] = $product;
					}
				}
			}
			return $product_bucket;
		}

		/**
		 * encode order by item
		 */
		function packing_perItem( $cart ) {
			$product_bucket = array();
			if ( count( $cart ) ) {
				foreach ( $cart as $item_id => $item ) {

					$_product = $item['data'];
					$_product_girth = ( get_post_meta( $_product->id, '_girth', true ) ) ? get_post_meta( $_product->id, '_girth', true ) : ( round( $_product->height ) + round( $_product->width ) ) * 2;

					if ( $_product->exists() && $item['quantity'] > 0 ) {

						if ( !$_product->is_virtual() ) {
							$product = array(
								"weight" => $_product->get_weight() * $item['quantity'],
								"height" => $_product->height,
								"length" => $_product->length,
								"width" => $_product->width,
								"girth" => $_product_girth,
								"value" => $_product->get_price() * $item['quantity']
							);
							$product_bucket[$item_id] = $product;
						}
					}
				}
			}
			return $product_bucket;
		}

		public function is_fit($item, $box){
			sort($box);
			list($box_height, $box_width, $box_length) = $box;
			$item_dimension = array($item->width, $item->height, $item->length);
			sort($item_dimension);
			return woocommerce_get_dimension($item_dimension[0], 'in') <= $box_height 
			&& woocommerce_get_dimension($item_dimension[1], 'in') <= $box_width 
			&& woocommerce_get_dimension($item_dimension[2], 'in') <= $box_length;
		}

		/**
		 * Get product volume
		 *
		 * @param type    $width
		 * @param type    $height
		 * @param type    $length
		 * @return type
		 */
		private function product_volume( $width, $height, $length ) {
			$width = $this->unify_size( $width );
			$height = $this->unify_size( $height );
			$length = $this->unify_size( $length );
			return $width * $height * $length;
		}

		/**
		 * returns product weight in pound/ounce pair
		 */
		private function unify_weight( $weight ) {
			return woocommerce_get_weight( $weight, "lbs" );
		}

		/**
		 * returns product size in inch
		 */
		private function unify_size( $size ) {
			return woocommerce_get_dimension( $size, "in" );
		}

		/**
		 * Admin option for backend update
		 *
		 * @global type $woocommerce
		 */
		function admin_options() {
			global $woocommerce;
			?>
			<h3><?php _e( 'USPS', 'wc_usps' ); ?></h3>
			<p><?php _e( 'Get rates directly from the USPS shipping API', 'wc_usps' ); ?></p>
			<table class="form-table">
				<?php
					// Generate the HTML For the settings form.
					$this->generate_settings_html();
				?>
			</table><!--/.form-table-->
			<script type="text/javascript">

				jQuery('#woocommerce_usps_type').change(function(){

					if ( jQuery( this ).val() == 'per_item' ) {

						jQuery('#woocommerce_usps_box_height, #woocommerce_usps_box_width, #woocommerce_usps_box_girth, #woocommerce_usps_box_weight, #woocommerce_usps_box_length, #woocommerce_usps_enable_custom_box').closest('tr').hide();

						jQuery('#woocommerce_usps_enable_custom_box').removeAttr('checked').change();

					} else {

						jQuery('#woocommerce_usps_box_height, #woocommerce_usps_box_width, #woocommerce_usps_box_girth, #woocommerce_usps_box_weight, #woocommerce_usps_box_length, #woocommerce_usps_enable_custom_box').closest('tr').show();

						jQuery('#woocommerce_usps_enable_custom_box').change();

					}

				}).change();

				jQuery('#woocommerce_usps_enable_custom_box').change(function(){

					if ( jQuery( this ).is(':checked') ) {

						jQuery('#woocommerce_usps_box_height, #woocommerce_usps_box_width, #woocommerce_usps_box_girth, #woocommerce_usps_box_weight, #woocommerce_usps_box_length').closest('tr').show();

					} else {

						jQuery('#woocommerce_usps_box_height, #woocommerce_usps_box_width, #woocommerce_usps_box_girth, #woocommerce_usps_box_weight, #woocommerce_usps_box_length').closest('tr').hide();

					}

				}).change();

			</script>
			<?php
		}
	}

	/**
	 * Add usps to woo extension pool
	 */
	add_filter( 'woocommerce_shipping_methods', 'add_usps_method' );

	function add_usps_method( $methods ) {
		$methods[] = 'WC_Shipping_USPS';
		return $methods;
	}

	/**
	 * woocommerce_usps_process_product_girth_metabox function.
	 *
	 * @access public
	 * @return void
	 */
	add_action( 'woocommerce_product_options_dimensions', 'woocommerce_usps_product_girth', 10 );

	function woocommerce_usps_product_girth() {
		global $post, $thepostid, $woocommerce;
		$thepostid = $post->ID;

		if ( get_option( 'woocommerce_enable_dimensions', true ) !== 'no' ) {
	?>
	        <p class="form-field dimensions_field">
	            <label for="product_girth"><?php echo __( 'Girth', 'woocommerce' ); ?></label>
	            <input id="product_girth" placeholder="<?php _e( 'Girth', 'woocommerce' ); ?>" class="input-text sized" size="6" type="text" name="_girth" value="<?php echo get_post_meta( $thepostid, '_girth', true ); ?>" />
	            <span class="description"><?php _e( 'If product is not rectangle, you can define girth for shipping calculation purpose.', 'woocommerce' ); ?></span>
	        </p>
	        <?php
	        
		} else {
			echo '<input type="hidden" name="_girth" value="' . get_post_meta( $thepostid, '_girth', true ) . '" />';
		}
	}

	/**
	 * woocommerce_usps_process_product_girth function.
	 *
	 * @access public
	 * @param mixed $post_id
	 * @param mixed $post
	 * @return void
	 */
	add_action( 'woocommerce_process_product_meta', 'woocommerce_usps_process_product_girth_metabox', 1 );

	function woocommerce_usps_process_product_girth_metabox( $post_id ) {

		add_post_meta( $post_id, '_girth', '0', true );

		$is_virtual = ( isset( $_POST['_virtual'] ) ) ? 'yes' : 'no';

		if ( isset( $_POST['_girth'] ) && is_numeric( $_POST['_girth'] ) ) {

			if ( $is_virtual == 'no' )
				update_post_meta( $post_id, '_girth', stripslashes( $_POST['_girth'] ) );
			else
				update_post_meta( $post_id, '_girth', '' );

		}
	}

}