<?php
/*
Plugin Name: WooCommerce Inspire Commerce Gateway
Plugin URI: http://woocommerce.com
Description: Woo has been using Inspire Commerce on WooThemes.com and have been so happy with the gateway, that they are happy to recommend it to all Woo uses.  <a href="http://www.inspirecommerce.com/woocommerce/">Click here to get paid like the pros</a>.
Version: 1.1
Author: Inspire Commerce
Author URI: http://www.inspirecommerce.com/woocommerce/

	Copyright: © 2009-2011 WooThemes.
	License: GNU General Public License v3.0
	License URI: http://www.gnu.org/licenses/gpl-3.0.html
	
*/

add_action('plugins_loaded', 'woocommerce_inspire_commerce_init', 0);

function woocommerce_inspire_commerce_init() {

	if (!class_exists('woocommerce_payment_gateway')) return;

	define('INSPIRE_DIR', WP_PLUGIN_DIR . "/" . plugin_basename( dirname(__FILE__) ) . '/');

	/**
	 * Inspire Commerce Gateway Class
	 **/
	class woocommerce_inspire extends woocommerce_payment_gateway {
	
		public function __construct() { 
	
	        $this->id			= 'inspire';
	        $this->icon 		= WP_PLUGIN_URL . "/" . plugin_basename( dirname(__FILE__)) . '/images/cards.png';
	        $this->has_fields 	= false;
				
			// Load the form fields
			$this->init_form_fields();
			
			// Load the settings.
			$this->init_settings();

			// Get setting values
			$this->enabled 		= $this->settings['enabled'];
			$this->title 		= $this->settings['title'];
			$this->description	= $this->settings['description'];
			$this->apilogin		= $this->settings['apilogin'];
			$this->transkey		= $this->settings['transkey'];
			$this->testmode		= $this->settings['testmode'];
			$this->salemethod	= $this->settings['salemethod'];
			$this->gatewayurl	= $this->settings['gatewayurl'];
			$this->debugon		= $this->settings['debugon'];
			$this->debugrecipient = $this->settings['debugrecipient'];
			$this->cvv			= $this->settings['cvv'];
			$this->cardtypes	= $this->settings['cardtypes'];

			// Hooks
			add_action('woocommerce_receipt_inspire', array(&$this, 'receipt_page'));
			add_action('admin_notices', array(&$this,'inspire_commerce_ssl_check'));
			add_action('woocommerce_update_options_payment_gateways', array(&$this, 'process_admin_options'));
	    } 
	

		/**
	 	* Check if SSL is enabled and notify the user
	 	**/
		function inspire_commerce_ssl_check() {
		     
		     if (get_option('woocommerce_force_ssl_checkout')=='no' && $this->enabled=='yes') :
		     
		     	echo '<div class="error"><p>'.sprintf(__('Inspire Commerce is enabled and the <a href="%s">force SSL option</a> is disabled; your checkout is not secure! Please enable SSL and ensure your server has a valid SSL certificate.', 'woothemes'), admin_url('admin.php?page=settings')).'</p></div>';
		     
		     endif;
		}
		
		
		/**
	     * Initialize Gateway Settings Form Fields
	     */
	    function init_form_fields() {
	    
	    	$this->form_fields = array(
				'enabled' => array(
								'title' => __( 'Enable/Disable', 'woothemes' ), 
								'label' => __( 'Enable Inspire Commerce', 'woothemes' ), 
								'type' => 'checkbox', 
								'description' => '', 
								'default' => 'no'
							), 
				'title' => array(
								'title' => __( 'Title', 'woothemes' ), 
								'type' => 'text', 
								'description' => __( 'This controls the title which the user sees during checkout.', 'woothemes' ), 
								'default' => __( 'Credit Card (Inspire Commerce)', 'woothemes' )
							), 
				'description' => array(
								'title' => __( 'Description', 'woothemes' ), 
								'type' => 'textarea', 
								'description' => __( 'This controls the description which the user sees during checkout.', 'woothemes' ), 
								'default' => 'Pay with your credit card via Inspire Commerce.'
							),  
				'apilogin' => array(
								'title' => __( 'Username', 'woothemes' ), 
								'type' => 'text', 
								'description' => __( 'This is the API username generated within the Inspire Commerce gateway.', 'woothemes' ), 
								'default' => ''
							), 
				'transkey' => array(
								'title' => __( 'Password', 'woothemes' ), 
								'type' => 'text', 
								'description' => __( 'This is the API user password generated within the Inspire Commerce gateway.', 'woothemes' ), 
								'default' => ''
							),
				'salemethod' => array(
								'title' => __( 'Sale Method', 'woothemes' ), 
								'type' => 'select', 
								'description' => __( 'Select which sale method to use. Authorize Only will authorize the customers card for the purchase amount only.  Authorize &amp; Capture will authorize the customer\'s card and collect funds.', 'woothemes' ), 
								'options' => array('AUTH_CAPTURE'=>'Authorize &amp; Capture','AUTH_ONLY'=>'Authorize Only'),
								'default' => ''
							),
				'gatewayurl' => array(
								'title' => __( 'Gateway URL', 'woothemes' ), 
								'type' => 'invisible', 
								'description' => __( 'URL for Inspire Commerce gateway processor.', 'woothemes' ), 
								'default' => 'https://secure.inspiregateway.net/gateway/transact.dll'
							),
				'cardtypes'	=> array(
								'title' => __( 'Accepted Cards', 'woothemes' ), 
								'type' => 'multiselect', 
								'description' => __( 'Select which card types to accept.', 'woothemes' ), 
								'default' => '',
								'options' => array(
									'MasterCard'	=> 'MasterCard', 
									'Visa'			=> 'Visa',
									'Discover'		=> 'Discover',
									'American Express' => 'American Express'
									),
							),		
				'cvv' => array(
								'title' => __( 'CVV', 'woothemes' ), 
								'label' => __( 'Require customer to enter credit card CVV code', 'woothemes' ), 
								'type' => 'checkbox', 
								'description' => __( '', 'woothemes' ), 
								'default' => 'yes'
							),
				'testmode' => array(
								'title' => __( 'Inspire Commerce Test Mode', 'woothemes' ), 
								'label' => __( 'Enable Test Mode', 'woothemes' ), 
								'type' => 'checkbox', 
								'description' => __( 'Place the payment gateway in development mode.', 'woothemes' ), 
								'default' => 'no'
							), 
				'debugon' => array(
								'title' => __( 'Debugging', 'woothemes' ), 
								'label' => __( 'Enable debug emails', 'woothemes' ), 
								'type' => 'checkbox', 
								'description' => __( 'Receive emails containing the data sent to and from Inspire Commerce. Only works in <strong>Test Mode</strong>.', 'woothemes' ), 
								'default' => 'no'
							),
				'debugrecipient' => array(
								'title' => __( 'Debugging Email', 'woothemes' ), 
								'type' => 'text', 
								'description' => __( 'Who should receive the debugging emails.', 'woothemes' ), 
								'default' =>  get_option('admin_email')
							),
				);
	    }
		
		
		/**
		 * Admin Panel Options 
		 * - Options for bits like 'title' and availability on a country-by-country basis
		 **/
		public function admin_options() {
			?>
			<h3><?php _e('Inspire Commerce','woothemes'); ?></h3>	    	
	    	<p><?php _e( 'Woo has been using Inspire Commerce on WooThemes.com for all credit card processing, and are so happy with the gateway, that they are recommending it to all US based Woo uses.  <a href="http://www.inspirecommerce.com/woocommerce/">Click here to get paid like the pros</a>.<br /><br />Inspire Commerce works by adding credit card fields on the checkout page, and then sending the details to Inspire Commerce for verification.', 'woothemes' ); ?></p>
	    	<table class="form-table">
	    		<?php $this->generate_settings_html(); ?>
			</table><!--/.form-table-->    	
	    	<?php
	    }
	    	    
	    
	    /**
		 * Payment fields for Inspire Commerce.
		 **/
	    function payment_fields() {
			?>
			<?php if ($this->testmode=='yes') : ?><p><?php _e('TEST MODE ENABLED', 'woothemes'); ?></p><?php endif; ?>
			<?php if ($this->description) : ?><p><?php echo $this->description; ?></p><?php endif; ?>
			<fieldset>

			<p class="form-row form-row-first">
				<label for="ccnum"><?php echo __("Credit Card number", 'woocommerce') ?> <span class="required">*</span></label>
				<input type="text" class="input-text" id="ccnum" name="ccnum" />
			</p>
	
			<p class="form-row form-row-last">
				<label for="cardtype"><?php echo __("Card type", 'woocommerce') ?> <span class="required">*</span></label>
				<select name="cardtype" id="cardtype" class="woocommerce-select">
					<?php 
				        foreach($this->cardtypes as $type) :
					        ?>
					        <option value="<?php echo $type ?>"><?php _e($type, 'woocommerce'); ?></option>
				            <?php
			            endforeach;
					?>
				</select>
			</p>
		
			<div class="clear"></div>

			<p class="form-row form-row-first">
				<label for="cc-expire-month"><?php echo __("Expiration date", 'woocommerce') ?> <span class="required">*</span></label>
				<select name="expmonth" id="expmonth" class="woocommerce-select woocommerce-cc-month">
					<option value=""><?php _e('Month', 'woocommerce') ?></option>
					<?php
						$months = array();
						for ($i = 1; $i <= 12; $i++) {
						    $timestamp = mktime(0, 0, 0, $i, 1);
						    $months[date('n', $timestamp)] = date('F', $timestamp);
						}
						foreach ($months as $num => $name) {
				            printf('<option value="%u">%s</option>', $num, $name);
				        }
					?>
				</select>
				<select name="expyear" id="expyear" class="woocommerce-select woocommerce-cc-year">
					<option value=""><?php _e('Year', 'woocommerce') ?></option>
					<?php
						$years = array();
						for ($i = date('y'); $i <= date('y') + 15; $i++) {
						    printf('<option value="20%u">20%u</option>', $i, $i);
						}
					?>
				</select>
			</p>
			<?php if ($this->cvv == 'yes') { ?>
		
			<p class="form-row form-row-last">
				<label for="cvv"><?php _e("Card security code", 'woocommerce') ?> <span class="required">*</span></label>
				<input type="text" class="input-text" id="cvv" name="cvv" maxlength="4" style="width:45px" />
				<span class="help"><?php _e('3 or 4 digits usually found on the signature strip.', 'woocommerce') ?></span>
			</p>
			<?php } ?>
			
			<div class="clear"></div>
		</fieldset>
			

			<?php
	    
	    }
	
	
		/**
		 * Process the payment and return the result
		 **/
	
		function process_payment( $order_id ) {
			global $woocommerce;

			$order = &new woocommerce_order( $order_id );
					
			$testmode = ($this->testmode == 'yes') ? 'TRUE' : 'FALSE';
			
			// ************************************************ 
			// Create request
			
				$inspire_request = array (
					"x_tran_key" 		=> $this->transkey, 
					"x_login" 			=> $this->apilogin,
					"x_amount" 			=> $order->order_total,
					"x_card_num" 		=> $_POST['ccnum'],
					"x_card_code" 		=> $_POST['cvv'],
					"x_exp_date" 		=> $_POST['expmonth'] . "-" . $_POST['expyear'],
					"x_type" 			=> $this->salemethod,
					"x_version" 		=> "3.1",
					"x_delim_data" 		=> "TRUE",
					"x_relay_response" 	=> "FALSE",
					"x_method" 			=> "CC",
					"x_first_name" 		=> $order->billing_first_name,
					"x_last_name" 		=> $order->billing_last_name,
					"x_address" 		=> $order->billing_address_1,
					"x_city" 			=> $order->billing_city,
					"x_state" 			=> $order->billing_state,
					"x_zip" 			=> $order->billing_postcode,
					"x_country" 		=> $order->billing_country,
					"x_phone" 			=> $order->billing_phone,
					"x_cust_id" 		=> $order->user_id,
					"x_customer_ip" 	=> $_SERVER['REMOTE_ADDR'],
					"x_invoice_num" 	=> $order->id,
					"x_test_request" 	=> $testmode,
					"x_delim_char" 		=> '|',
					"x_encap_char" 		=> '',
				);
				
				$this->send_debugging_email( "URL: " . $this->gatewayurl . "\n\nSENDING REQUEST:" . print_r($inspire_request,true));
	
			
			// ************************************************ 
			// Send request
			
				error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
				foreach($inspire_request AS $key => $val){
					$post .= urlencode($key) . "=" . urlencode($val) . "&";
				}
				$post = substr($post, 0, -1);
	
	
				
				$url=$this->gatewayurl;
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt ($ch, CURLOPT_POST, 1);
				curl_setopt ($ch, CURLOPT_POSTFIELDS, $post);
				curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
				$content = curl_exec ($ch);
				curl_close ($ch);
	
				// prep response
				foreach ( preg_split("/\r?\n/", $content) as $line ) {
					if (preg_match("/^1|2|3\|/", $line)) {
						$data = explode("|", $line);
					}
				}
			
				// store response
				$response['response_code'] = $data[0];
				$response['response_sub_code'] = $data[1];
				$response['response_reason_code'] = $data[2];
				$response['response_reason_text'] = $data[3];
				$response['approval_code'] = $data[4];
				$response['avs_code'] = $data[5];
				$response['transaction_id'] = $data[6];
				$response['invoice_number_echo'] = $data[7];
				$response['description_echo'] = $data[8];
				$response['amount_echo'] = $data[9];
				$response['method_echo'] = $data[10];
				$response['transaction_type_echo'] = $data[11];
				$response['customer_id_echo'] = $data[12];
				$response['first_name_echo'] = $data[13];
				$response['last_name_echo'] = $data[14];
				$response['company_echo'] = $data[15];
				$response['billing_address_echo'] = $data[16];
				$response['city_echo'] = $data[17];
				$response['state_echo'] = $data[18];
				$response['zip_echo'] = $data[19];
				$response['country_echo'] = $data[20];
				$response['phone_echo'] = $data[21];
				$response['fax_echo'] = $data[22];
				$response['email_echo'] = $data[23];
				$response['ship_first_name_echo'] = $data[24];
				$response['ship_last_name_echo'] = $data[25];
				$response['ship_company_echo'] = $data[26];
				$response['ship_billing_address_echo'] = $data[27];
				$response['ship_city_echo'] = $data[28];
				$response['ship_state_echo'] = $data[29];
				$response['ship_zip_echo'] = $data[30];
				$response['ship_country_echo'] = $data[31];
				$response['tax_echo'] = $data[32];
				$response['duty_echo'] = $data[33];
				$response['freight_echo'] = $data[34];
				$response['tax_exempt_echo'] = $data[35];
				$response['po_number_echo'] = $data[36];
			
				$response['md5_hash'] = $data[37];
				$response['cvv_response_code'] = $data[38];
				$response['cavv_response_code'] = $data[39];
	
				$this->send_debugging_email( "RESPONSE RAW: " . $content . "\n\nRESPONSE:" . print_r($response,true));	
			
			// ************************************************ 
			// Retreive response
	
	
	
				if ($response['response_code'] == 1) {
					// Successful payment
	
					$order->add_order_note( __('Inspire Commerce payment completed', 'woocommerce') . ' (Transaction ID: ' . $response['transaction_id'] . ')' );
					$order->payment_complete();
		
					$woocommerce->cart->empty_cart();

					// Empty awaiting payment session
					unset($_SESSION['order_awaiting_payment']);

						
					// Return thank you redirect
					return array(
						'result' 	=> 'success',
						'redirect'	=> add_query_arg('key', $order->order_key, add_query_arg('order', $order_id, get_permalink(get_option('woocommerce_thanks_page_id'))))
					);
	
				} else {
					
					$this->send_debugging_email( "Inspire Commerce ERROR:\nresponse_code:" . $response['response_code'] . "\nresponse_reason_text:" .$response['response_reason_text'] );
				
					$cancelNote = __('Inspire Commerce payment failed', 'woocommerce') . ' (Response Code: ' . $response['response_code'] . '). ' . __('Payment wast rejected due to an error', 'woocommerce') . ': "' . $response['response_reason_text'] . '". ';
		
					$order->add_order_note( $cancelNote );
					
					$woocommerce->add_error(__('Payment error', 'woocommerce') . ': ' . $response['response_reason_text'] . '');

				}
	
		}
		

		/**
		Validate payment form fields
		**/
		
		public function validate_fields() {
			global $woocommerce;

			$cardType = $this->get_post('card_type');
			$cardNumber = $this->get_post('ccnum');
			$cardCSC = $this->get_post('cvv');
			$cardExpirationMonth = $this->get_post('expmonth');
			$cardExpirationYear = $this->get_post('expyear');
		
			if ($this->cvv=='yes'){
				//check security code
				if(!ctype_digit($cardCSC)) {
					$woocommerce->add_error(__('Card security code is invalid (only digits are allowed)', 'woocommerce'));
					return false;
				}
		
				if((strlen($cardCSC) != 3 && in_array($cardType, array('Visa', 'MasterCard', 'Discover'))) || (strlen($cardCSC) != 4 && $cardType == 'American Express')) {
					$woocommerce->add_error(__('Card security code is invalid (wrong length)', 'woocommerce'));
					return false;
				}
			}
	
			//check expiration data
			$currentYear = date('Y');
			
			if(!ctype_digit($cardExpirationMonth) || !ctype_digit($cardExpirationYear) ||
				 $cardExpirationMonth > 12 ||
				 $cardExpirationMonth < 1 ||
				 $cardExpirationYear < $currentYear ||
				 $cardExpirationYear > $currentYear + 20
			) {
				$woocommerce->add_error(__('Card expiration date is invalid', 'woocommerce'));
				return false;
			}
	
			//check card number
			$cardNumber = str_replace(array(' ', '-'), '', $cardNumber);
	
			if(empty($cardNumber) || !ctype_digit($cardNumber)) {
				$woocommerce->add_error(__('Card number is invalid', 'woocommerce'));
				return false;
			}
	
			return true;
		}


		/**
		 * receipt_page
		 **/
		function receipt_page( $order ) {
			
			echo '<p>'.__('Thank you for your order.', 'woocommerce').'</p>';
			
		}
		
		/**
		 * Get post data if set
		 **/
		private function get_post($name) {
			if(isset($_POST[$name])) {
				return $_POST[$name];
			}
			return NULL;
		}

		/**
		 * Send debugging email
		 **/
		function send_debugging_email( $debug ) {
			
			if ($this->debugon!='yes') return; // Debug must be enabled
			if ($this->testmode!='yes') return; // Test mode required
			if (!$this->debugrecipient) return; // Recipient needed
			
			// Send the email
			wp_mail( $this->debugrecipient, __('Inspire Commerce Debug', 'woothemes'), $debug );
			
		} 

	}


	/**
	 * Add the gateway to woocommerce
	 **/
	function add_inspire_commerce_gateway( $methods ) {
		$methods[] = 'woocommerce_inspire'; return $methods;
	}
	
	add_filter('woocommerce_payment_gateways', 'add_inspire_commerce_gateway' );
	
}