<?php
/*
  Plugin Name: WooCommerce FedEx
  Plugin URI: http://woothemes.com/woocommerce/
  Description: Fedex Shipping for WooCommerce
  Version: 2.3.1
  Author: Andy Zhang
  Author URI: http://hypnoticzoo.com

  Copyright: © 2009-2011 WooThemes.
  License: GNU General Public License v3.0
  License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */
add_action('plugins_loaded', 'woocommerce_fedex_wsdl_init', 0);

function woocommerce_fedex_wsdl_init() {

	if ( ! class_exists( 'woocommerce_shipping_method' ) )
		return;

	include_once( plugin_dir_path(__FILE__) . 'hypnoticzoo_utilities.php' );

	if ( ! class_exists( 'nusoap_base' ) )
		include_once(plugin_dir_path(__FILE__) . 'nusoap.php');
	
	/**
	 * Required functions
	 */
	if ( ! function_exists( 'is_woocommerce_active' ) ) require_once( 'woo-includes/woo-functions.php' );
	
	/**
	 * Plugin updates
	 */
	if ( is_admin() ) {
		$woo_plugin_updater_fedex = new WooThemes_Plugin_Updater( __FILE__ );
		$woo_plugin_updater_fedex->api_key = 'f8562dd1791cec7a3899dc079d60e1c0';
		$woo_plugin_updater_fedex->init();
	}	
	
	/**
	 * Currency checker
	 */
	if ( is_admin() ) 
		add_action('admin_notices', 'woocommerce_fedex_currency_check' );
	
	function woocommerce_fedex_currency_check() {
		global $woocommerce;
		
		if ( get_option('woocommerce_currency') != "USD" ) :

			echo '<div class="error"><p>' . sprintf(__('The <a href="%s">currency</a> is not USD; FedEx currently support only USD.', 'woothemes'), admin_url('admin.php?page=woocommerce&tab=general')) . '</p></div>';

		endif;

		if ( $woocommerce->countries->get_base_country() != "US" ) :

			echo '<div class="error"><p>' . sprintf(__('FedEx requires that the <a href="%s">base country/region</a> is set to the US.', 'woothemes'), admin_url('admin.php?page=woocommerce&tab=general')) . '</p></div>';

		endif;
		
	}

	/**
	 * Shipping method class
	 * */
	class WC_Shipping_Fedex_WSDL extends WC_Shipping_Method {

		var $wsdl_url = "";
		var $url = "https://gateway.fedex.com:443/web-services";
		var $test_url = "https://gatewaybeta.fedex.com:443/web-services";
		var $services = array(
		    'FEDEX_GROUND' => 'FedEx Ground - Commercial Address',
		    'GROUND_HOME_DELIVERY' => 'FedEx Ground - Home Address',
		    'FEDEX_EXPRESS_SAVER' => 'FedEx Express Saver',
		    'FEDEX_2_DAY' => 'FedEx 2Day',
		    'FEDEX_2_DAY_AM' => 'FedEx 2Day AM',
		    'STANDARD_OVERNIGHT' => 'FedEx Standard Overnight',
		    'PRIORITY_OVERNIGHT' => 'FedEx Priority Overnight',
		    'FIRST_OVERNIGHT' => 'FedEx First Overnight',
		    'INTERNATIONAL_ECONOMY' => 'FedEx International Economy',
		    'INTERNATIONAL_FIRST' => 'FedEx International First',
		    'INTERNATIONAL_PRIORITY' => 'FedEx International Priority',
		    'EUROPE_FIRST_INTERNTIONAL_PRIORITY' => 'FedEx Europe First International Priority',
		    'FEDEX_1_DAY_FREIGHT' => 'FedEx 1Day Freight',
		    'FEDEX_2_DAY_FREIGHT' => 'FedEx 2Day Freight',
		    'FEDEX_3_DAY_FREIGHT' => 'FedEx 3Day Freight',
		    'INTERNATIONAL_ECONOMY_FREIGHT' => 'FedEx Economy Freight',
		    'INTERNATIONAL_PRIORITY_FREIGHT' => 'FedEx Priority Freight',
		    'FEDEX_FREIGHT' => 'Fedex Freight',
		    'FEDEX_NATIONAL_FREIGHT' => 'FedEx National Freight',
		    'INTERNATIONAL_GROUND' => 'FedEx International Ground',
		    'SMART_POST' => 'FedEx Smart Post'
		);

		function __construct() {
			global $woocommerce;

			$this->id = 'fedex_wsdl';
			$this->method_title = __('FedEx', 'woothemes');

			// Load the form fields.
			$this->init_form_fields();

			// Load the settings.
			$this->init_settings();

			$this->enabled = $this->settings['enabled'];
			$this->debug = (!empty($this->settings['debug']) && $this->settings['debug'] == 'yes') ? true : false;
			$this->production = ( $this->settings['production'] == 'yes' ) ? true : false;
			$this->quick_quote = $this->settings['quick_quote'];
			if ( $this->quick_quote == 'yes' ) $this->production = false;
			$this->title = $this->settings['title'];
			$this->availability = $this->settings['availability'];
			$this->origin_post = $this->settings['origin'];
			$this->origin_country = $woocommerce->countries->get_base_country();
			$this->shipping_methods = $this->settings['shipping_methods'];
			$this->shipping_availability = $this->settings['shipping_availability'];
			$this->countries = $this->settings['countries'];
			$this->tax_status = $this->settings['tax_status'];

			// HZ dev test details
            $this->account = ($this->quick_quote == "yes") ? "510087020" : $this->settings['account'];
			$this->meter = ($this->quick_quote == "yes") ? "118547677" : $this->settings['meter'];
			$this->key = ($this->quick_quote == "yes") ? "xGtvBDJgQ6teXWXn" : $this->settings['key'];
			$this->password = ($this->quick_quote == "yes") ? "VPos8dUdshXXhDx99UCgzVRKV" : $this->settings['password'];
            
			$this->smartposthubid = $this->settings['smartposthubid'];
			$this->insure = $this->settings['insure'];
			$this->residential = ($this->settings['residential'] == "yes") ? true : false;
			$this->fee = $this->settings['fee'];
			$this->selected_shipping_type = (is_array(get_option("woocommerce_fedex_shipping_type", array()))) ? get_option("woocommerce_fedex_shipping_type", array()) : array();
			$this->weight_unit = (get_option("woocommerce_weight_unit") == "kg") ? "KGS" : "LBS";
			$this->rateservice_version = '10';
			
			add_action('woocommerce_update_options_shipping_methods', array(&$this, 'process_admin_options'));
			add_action('woocommerce_update_options_shipping_fedex_wsdl', array(&$this, 'process_admin_options'));
		}

		/**
		 * Initialise Gateway Settings Form Fields
		 */
		function init_form_fields() {
			global $woocommerce;

			$this->form_fields = array(
			    'enabled' => array(
				'title' => __('Enable/Disable', 'woothemes'),
				'type' => 'checkbox',
				'label' => __('Enable FedEx', 'woothemes'),
				'default' => 'yes'
			    ),
			    'debug' => array(
				'title' => __('Debug', 'woothemes'),
				'type' => 'checkbox',
				'label' => __('Enable debug mode', 'woothemes'),
				'description' => __('If you are not getting a rate, debug mode may give you more information.', 'woothemes'),
				'default' => 'no'
			    ),
			    'quick_quote' => array(
				'title' => __('Quick Quote', 'woothemes'),
				'type' => 'checkbox',
				'label' => __('Enable Quick Quote', 'woothemes'),
				'description' => __('If you don\'t have a FedEx account and developer details, use quick quote. NOTE: quick quote uses the FedEx Developer API and can occasionally go down for maintenance - we recommend that you obtain your own PRODUCTION key from FedEx if using this in a live environment.', 'woothemes'),
				'default' => 'yes'
			    ),
			    'production' => array(
				'title' => __('Production mode', 'woothemes'),
				'type' => 'checkbox',
				'label' => __('Enable production mode', 'woothemes'),
				'description' => __('Enable this if you are using a production key.', 'woothemes'),
				'default' => 'no'
			    ),
			    'title' => array(
				'title' => __('Method Title', 'woothemes'),
				'type' => 'text',
				'description' => __('This controls the title which the user sees during checkout.', 'woothemes'),
				'default' => __('FedEx', 'woothemes')
			    ),
			    'origin' => array(
				'title' => __('Origin Zip', 'woothemes'),
				'type' => 'text',
				'description' => __('Enter your origin zip code.', 'woothemes'),
				'default' => __('', 'woothemes')
			    ),
			    'tax_status' => array(
				'title' => __('Tax Status', 'woothemes'),
				'type' => 'select',
				'description' => '',
				'default' => 'taxable',
				'options' => array(
				    'taxable' => __('Taxable', 'woothemes'),
				    'none' => __('None', 'woothemes')
				)
			    ),
			    'fee' => array(
				'title' => __('Handling Fee', 'woothemes'),
				'type' => 'text',
				'description' => __('Fee excluding tax. Enter an amount, e.g. 2.50, or a percentage, e.g. 5%.', 'woothemes'),
				'default' => ''
			    ),
			    'account' => array(
				'title' => __('Account Number', 'woothemes'),
				'type' => 'text',
				'class' => 'cridential',
				'description' => __('Your FedEx account number.', 'woothemes'),
				'default' => ''
			    ),
			    'meter' => array(
				'title' => __('Meter Number', 'woothemes'),
				'type' => 'text',
				'class' => 'cridential',
				'description' => __('Your FedEx meter number.', 'woothemes'),
				'default' => ''
			    ),
			    'key' => array(
				'title' => __('Key', 'woothemes'),
				'type' => 'text',
				'class' => 'cridential',
				'description' => __('FedEx web services key.', 'woothemes'),
				'default' => ''
			    ),
			    'password' => array(
				'title' => __('Password', 'woothemes'),
				'type' => 'text',
				'class' => 'cridential',
				'description' => __('FedEx web services password.', 'woothemes'),
				'default' => ''
			    ),
			    'smartposthubid' => array(
				'title' => __('SmartPost HubID', 'woothemes'),
				'type' => 'text',
				'description' => __('Required for SmartPost.', 'woothemes'),
				'default' => ''
			    ),
			    'insure' => array(
				'title' => __('Insurance', 'woothemes'),
				'type' => 'checkbox',
				'description' => __('Rates include insurance.', 'woothemes'),
				'default' => ''
			    ),
			    'residential' => array(
				'title' => __('Residential delivery', 'woothemes'),
				'type' => 'checkbox',
				'description' => __('Deliver to residential address.', 'woothemes'),
				'default' => 'no'
			    ),
			    'shipping_availability' => array(
				'title' => __('Method availability', 'woothemes'),
				'type' => 'select',
				'default' => 'all',
				'class' => 'availability',
				'options' => array(
				    'all' => __('All Allowed Methods', 'woothemes'),
				    'specific' => __('Specific Methods', 'woothemes')
				)
			    ),
			    'shipping_methods' => array(
				'title' => __('Specific Methods', 'woothemes'),
				'type' => 'multiselect',
				'class' => 'chosen_select',
				'css' => 'width: 450px;',
				'default' => '',
				'options' => $this->services
			    ),
			    'availability' => array(
				'title' => __('Method availability', 'woothemes'),
				'type' => 'select',
				'default' => 'all',
				'class' => 'availability',
				'options' => array(
				    'all' => __('All Allowed Countries', 'woothemes'),
				    'specific' => __('Specific Countries', 'woothemes')
				)
			    ),
			    'countries' => array(
				'title' => __('Specific Countries', 'woothemes'),
				'type' => 'multiselect',
				'class' => 'chosen_select',
				'css' => 'width: 450px;',
				'default' => '',
				'options' => $woocommerce->countries->countries
			    )
			);
		}

		function calculate_shipping() {
			global $woocommerce;
			$customer = $woocommerce->customer;

			$this->shipping_total = 0;
			$weight = 0;
			$value = 0;
			$data = array();
			if (sizeof($woocommerce->cart->get_cart()) > 0 && ($customer->get_shipping_state()) || $customer->get_shipping_postcode()) {

				foreach ($woocommerce->cart->get_cart() as $item_id => $values) {

					$_product = $values['data'];

					if ($_product->exists() && $values['quantity'] > 0) {

						if (!$_product->is_virtual()) {

							$weight += $_product->get_weight() * $values['quantity'];
							$value += $_product->get_price() * $values['quantity'];
						}
					}
				}
				$data['weight'] = $weight;
				$data['value'] = $value;
				if ($weight) {
					$this->get_shipping_response($data);
				}
			}
		}

		/**
		 * Set shipping rates from cache or from FedEx API
		 * @global type $woocommerce
		 * @param type $data 
		 */
		function get_shipping_response($data = false) {
			global $woocommerce;

			$rates = array();
			$customer = $woocommerce->customer;
			$debug_response = array();

			$shipping_data = array(
			    'Pickup_Postcode' => $this->origin_post,
			    'Pickup_Country' => $this->origin_country,
			    'Destination_Postcode' => $customer->get_shipping_postcode(),
			    'State' => $customer->get_shipping_state(),
			    'Country' => $customer->get_shipping_country(),
			    'Weight' => $data['weight'],
			    'Value' => $data['value']
			);

			$data = $this->fedex_encode($shipping_data);			
			$result = $this->fedex_shipping($data);

			if ($result) {
				if (in_array($result->HighestSeverity, array('FAILURE', 'ERROR', 'WARNING'))) {
					if ($this->debug) {
						$debug_response[] = $result->HighestSeverity . " : " . $result->Notifications->Message;
						$woocommerce->clear_messages();
						$woocommerce->add_message('<p>FEDEX Response:</p><ul><li>' . implode('</li><li>', $debug_response) . '</li></ul>');
					}
				}
			}

			$RatedReply = &$result->RateReplyDetails;
			
			// Workaround for when an object is returned instead of array
			if ( is_object( $RatedReply ) && isset( $RatedReply->ServiceType ) )
				$RatedReply = array( $RatedReply );
			
			if ( ! is_array( $RatedReply ) )
				return false;
				
			foreach ($RatedReply as $quote) {
			
				if ( $this->shipping_availability !== 'all' && is_array( $this->shipping_methods ) && ! in_array($quote->ServiceType, $this->shipping_methods))
					continue;

				$name = $this->services[$quote->ServiceType];
				if (is_array($quote->RatedShipmentDetails)) {
					foreach ($quote->RatedShipmentDetails as $i => $d) {
						if ($d->ShipmentRateDetail->RateType == $quote->ActualRateType) {
							$details = &$quote->RatedShipmentDetails[$i];
							break;
						}
					}
				} else
					$details = &$quote->RatedShipmentDetails;
					
				if (!isset($details))
					continue;

				$amount = apply_filters('woocommerce_fedex_total', $details->ShipmentRateDetail->TotalNetCharge->Amount, $details);
				$amount += $this->get_fee($this->fee, $woocommerce->cart->cart_contents_total);

				$rate = array(
					'id' => 'FedEx:' . $quote->ServiceType,
					'label' => $name,
					'cost' => $amount
				);
				$rates[] = $rate;
				
			}

			// Add rates based on rates
			if ($rates && is_array($rates)) {
				foreach ($rates as $rate) {
					$this->add_rate($rate);
				}
			}
		}

		function fedex_encode($data = false) {
			$request = array();

			$request['WebAuthenticationDetail'] = array(
				'UserCredential' => array(
					'Key' => $this->key, 
					'Password' => $this->password
				)
			); 
			$request['ClientDetail'] = array(
			    'AccountNumber' => $this->account,
			    'MeterNumber' => $this->meter
			);
			$request['TransactionDetail'] = array( 'CustomerTransactionId' => '*** Rate Available Services Request from WooCommerce ***' );
			$request['Version'] = array(
			    'ServiceId' => 'crs',
			    'Major' => $this->rateservice_version,
			    'Intermediate' => '0',
			    'Minor' => '0'
			);
			$request['ReturnTransitAndCommit'] = true;
			$request['RequestedShipment']['DropoffType'] = 'REGULAR_PICKUP'; // valid values REGULAR_PICKUP, REQUEST_COURIER, ...
			$request['RequestedShipment']['ShipTimestamp'] = date('c');
			
			//$request['RequestedShipment']['ServiceType'] = $data['Shipping_Type']; // valid values STANDARD_OVERNIGHT, PRIORITY_OVERNIGHT, FEDEX_GROUND, ...
			
			if (is_array($this->shipping_methods) && in_array('SMART_POST', $this->shipping_methods) && !empty($this->smartposthubid)) {
				$request['RequestedShipment']['SmartPostDetail'] = array(
				    'Indicia' => 'PARCEL_SELECT',
				    'HubId' => $this->smartposthubid
				);
			}
			
			$request['RequestedShipment']['PackagingType'] = 'YOUR_PACKAGING'; // valid values FEDEX_BOX, FEDEX_PAK, FEDEX_TUBE, YOUR_PACKAGING, ...
			
			if ( $this->insure == 'yes' )
				$request['RequestedShipment']['TotalInsuredValue'] = array( 'Amount' => $data['Value'], 'Currency' => 'USD' );
			
			$request['RequestedShipment']['Shipper'] = array(
			    'Address' => array(
				'PostalCode' => $data['Pickup_Postcode'],
				'CountryCode' => $data['Pickup_Country']
			    )
			);
			$request['RequestedShipment']['Recipient'] = array(
			    'Address' => array(
				'Residential' => $this->residential,
				'PostalCode' => $data['Destination_Postcode'],
				'CountryCode' => $data['Country']
			    )
			);
			
			if (in_array($data['Country'], array('US', 'CA'))) {
				$request['RequestedShipment']['Recipient']['Address']['StateOrProvinceCode'] = $data['State'];
			}

			$request['RequestedShipment']['ShippingChargesPayment'] = array(
			    'PaymentType' => 'SENDER', // valid values RECIPIENT, SENDER and THIRD_PARTY
			    'Payor' => array(
				'AccountNumber' => $this->account,
				'CountryCode' => $data['Pickup_Country']
			    )
			);
			$request['RequestedShipment']['RateRequestTypes'] = 'LIST'; // LIST or ACCOUNT
			$request['RequestedShipment']['PackageCount'] = '1';
			$request['RequestedShipment']['PackageDetail'] = 'INDIVIDUAL_PACKAGES';
			$request['RequestedShipment']['RequestedPackageLineItems'] = array(
				'0' => array(
				    'SequenceNumber' => 1,
				    'GroupPackageCount' => 1,
				    'Weight' => array(
						'Value' => $data['Weight'],
						'Units' => 'LB'
				    )
			    )
			);
			
			//echo '<pre>';
			//var_dump($request);
			//echo '</pre>';
			
			return $request;
		}

		/**
		 * Shipping result from the end point
		 * @param type $data
		 * @param type $cache
		 * @return response 
		 */
		function fedex_shipping($data = false, $cache = false) {
			global $woocommerce;
			
			try {
				if ( class_exists( 'SoapClient' ) ) {
				
					ini_set("soap.wsdl_cache_enabled", "0");
					
					if ( ! $this->production ) {
						$client = new SoapClient( trailingslashit( plugin_dir_path(__FILE__) ) . 'test/RateService_v' . $this->rateservice_version. '.wsdl', array( 'trace' => 1 ) );
					} else {
						$client = new SoapClient( trailingslashit( plugin_dir_path(__FILE__) ) . 'production/RateService_v' . $this->rateservice_version. '.wsdl', array( 'trace' => 1 ) );
					}
					
					$response = $client->getRates( $data );
					
					//print "<pre>\n"; 
					//print "Request: \n".htmlspecialchars($client->__getLastRequest()) ."\n"; 
					//print "Response: \n".htmlspecialchars($client->__getLastResponse())."\n"; 
					//print "</pre>"; 
										
				} elseif (class_exists('nusoap_client')) {
					
					if ( ! $this->production ) {
						$client = new nusoap_client( trailingslashit( plugin_dir_path(__FILE__) ) . 'test/RateService_v' . $this->rateservice_version. '.wsdl' , 'wsdl');
					} else {
						$client = new nusoap_client( trailingslashit( plugin_dir_path(__FILE__) ) . 'production/RateService_v' . $this->rateservice_version. '.wsdl' , 'wsdl');
					}
					
					$response = $client->call('getRates', array('RateRequest' => $data));
					
					mkobject($response);
					
				} else {
					$woocommerce->add_error( __("FedEx Rates cannot be used because this server does not have SOAP support.", "woocommerce") );
				}
			} catch (Exception $e) {
				print_r($e);
				return false;
			}
			
			return $response;
		}

		function is_available() {
			global $woocommerce;

			if ($this->enabled == "no")
				return false;

			if (isset($woocommerce->cart->cart_contents_total) && isset($this->min_amount) && $this->min_amount && $this->min_amount > $woocommerce->cart->cart_contents_total)
				return false;

			if (get_option('woocommerce_currency') != "USD"):
				return false;
			endif;

			if ($this->origin_country != "US"):
				return false;
			endif;

			if (!$this->origin_post):
				return false;
			endif;

			$ship_to_countries = '';

			if ($this->availability == 'specific') :
				$ship_to_countries = $this->countries;
			else :
				if (get_option('woocommerce_allowed_countries') == 'specific') :
					$ship_to_countries = get_option('woocommerce_specific_allowed_countries');
				endif;
			endif;

			if (is_array($ship_to_countries)) :
				if (!in_array($woocommerce->customer->get_shipping_country(), $ship_to_countries))
					return false;
			endif;

			return true;
		}

		/**
		 * Admin Panel Options 
		 * - Options for bits like 'title' and availability on a country-by-country basis
		 *
		 * @since 1.0.0
		 */
		public function admin_options() {
			?>
			<h3><?php _e('FedEx', 'woothemes'); ?></h3>
			<p><?php _e('FedEx calculates shipping price base on FedEx standard.', 'woothemes'); ?></p>
			<table class="form-table">
				<?php
				// Generate the HTML For the settings form.
				$this->generate_settings_html();
				?>
			</table><!--/.form-table-->
			<script type="text/javascript">
				jQuery(window).load(function(){
					$j = jQuery;
					show_cridential();
					$j('#woocommerce_fedex_wsdl_quick_quote').change(function(){
						show_cridential();
					});
																																						
					function show_cridential(){
						var checked = $j('#woocommerce_fedex_wsdl_quick_quote').is(':checked');
						if(checked){
							$j('.cridential').parents('tr').hide();
						}else{
							$j('.cridential').parents('tr').show();
						}
					}
				});
			</script>
			<?php
		}

	}

	function add_fedex_method($methods) {
		$methods[] = 'WC_Shipping_Fedex_WSDL';
		return $methods;
	}

	add_filter('woocommerce_shipping_methods', 'add_fedex_method');
}

