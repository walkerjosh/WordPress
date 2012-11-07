<?php
/*
Plugin Name: WooCommerce Subscribe to Newsletter
Plugin URI: http://woocommerce.com
Description: Allow users to subscribe to your newsletter via the checkout page and via a sidebar widget. Supports MailChimp and Campaign Monitor. Go to WooCommerce > Settings > Newsletter to configure the plugin.
Version: 1.2
Author: WooThemes
Author URI: http://woothemes.com
Requires at least: 3.1
Tested up to: 3.2

	Copyright: © 2009-2011 WooThemes.
	License: GNU General Public License v3.0
	License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

/**
 * Required functions
 **/
if ( ! function_exists( 'is_woocommerce_active' ) ) require_once( 'woo-includes/woo-functions.php' );

/**
 * Plugin updates
 **/
if (is_admin()) {
	$woo_plugin_updater_newsletter_subscription = new WooThemes_Plugin_Updater( __FILE__ );
	$woo_plugin_updater_newsletter_subscription->api_key = 'b550814b2885a95d33f045f44ba8b488';
	$woo_plugin_updater_newsletter_subscription->init();
}
		
if (is_woocommerce_active()) {
	
	/**
	 * Localisation
	 **/
	load_plugin_textdomain('wc_subscribe_to_newsletter', false, dirname( plugin_basename( __FILE__ ) ) . '/');
	
	/**
	 * Include widget class
	 **/
	include_once( 'newsletter_widget.php' );
	
	/**
	 * woocommerce_subscribe_to_newsletter class
	 **/
	if (!class_exists('woocommerce_subscribe_to_newsletter')) {
	 
		class woocommerce_subscribe_to_newsletter {
					
			var $settings_tabs;
			var $current_tab;
			var $fields = array();
			var $service;
			var $mailchimp_api_key;
			var $mailchimp_list;
			var $cmonitor_api_key;
			var $cmonitor_list;
			var $checkbox_status;
			var $checkbox_label;
	
			public function __construct() {
				
				$this->current_tab = ( isset($_GET['tab'] ) ) ? $_GET['tab'] : 'general';
		
				$this->settings_tabs = array(
					'newsletter' => __( 'Newsletter', 'wc_subscribe_to_newsletter' )
				);
				
				// Load in the new settings tabs.
				add_action( 'woocommerce_settings_tabs', array( &$this, 'add_tab' ), 10 );
				
				// Run these actions when generating the settings tabs.
				foreach ( $this->settings_tabs as $name => $label ) {
					add_action( 'woocommerce_settings_tabs_' . $name, array( &$this, 'settings_tab_action' ), 10 );
					add_action( 'woocommerce_update_options_' . $name, array( &$this, 'save_settings' ), 10 );
				}
									
				// Add the settings fields to each tab.
				add_action( 'woocommerce_newsletter_settings', array( &$this, 'add_settings_fields' ), 10 );
				
				// Dashboard stats
				add_action('wp_dashboard_setup', array( &$this, 'init_dashboard' ) );
								
				// Frontend				
				add_action('woocommerce_after_checkout_billing_form', array(&$this, 'newsletter_field'), 5);
				add_action('woocommerce_ppe_checkout_order_review', array(&$this, 'newsletter_field'), 5);
				add_action('register_form', array(&$this, 'newsletter_field'), 5);
				add_action('woocommerce_checkout_order_processed', array(&$this, 'process_newsletter_field'), 5, 2);
				add_action('woocommerce_ppe_do_payaction', array(&$this, 'process_ppe_newsletter_field'), 5, 1);
				add_action('register_post', array(&$this, 'process_register_form'), 5, 3 );
				
				// Options
				add_option('woocommerce_newsletter_label', 'Subscribe to our newsletter?');
				add_option('woocommerce_newsletter_checkbox_status', 'unchecked');
				
				// Widget
				add_action('widgets_init', array(&$this, 'init_widget'));
				
				// Get settings
				$this->service = get_option('woocommerce_newsletter_service');
				
				$this->checkbox_status = get_option('woocommerce_newsletter_checkbox_status');
				$this->checkbox_label = get_option('woocommerce_newsletter_label');
				
				$this->mailchimp_api_key = get_option('woocommerce_mailchimp_api_key');
				$this->mailchimp_list = get_option('woocommerce_mailchimp_list');
				
				$this->cmonitor_api_key = get_option('woocommerce_cmonitor_api_key');
				$this->cmonitor_list = get_option('woocommerce_cmonitor_list');
				
		    } 

			/*-----------------------------------------------------------------------------------*/
			/* Widgets */
			/*-----------------------------------------------------------------------------------*/ 
			
			function init_widget() {
				register_widget('WooCommerce_Widget_Subscibe_to_Newsletter');
			}
			
			/*-----------------------------------------------------------------------------------*/
			/* Dashboard */
			/*-----------------------------------------------------------------------------------*/ 
			
			function init_dashboard() {
				if ( current_user_can( 'manage_woocommerce' ) ) {
					wp_add_dashboard_widget('woocommmerce_dashboard_subscribers', __('Newsletter subscribers', 'wc_subscribe_to_newsletter'), array(&$this, 'stats'));
				}
			}
			
			function stats() {
				switch ($this->service) :
					case "mailchimp" :
						if ($this->mailchimp_api_key && $this->mailchimp_list) :
							
							if ( !$stats = get_transient('woocommerce_mailchimp_stats') ) :

								if (!class_exists('MCAPI')) include_once('mailchimp/MCAPI.class.php');
								
								$api = new MCAPI( $this->mailchimp_api_key );
	
								$retval = $api->lists();
								
								if ($api->errorCode) :
									echo '<div class="error inline"><p>'.__('Unable to load stats from MailChimp', 'wc_subscribe_to_newsletter').'</p></div>';
								else :
								
									foreach ($retval['data'] as $list) :
										
										if ($list['id']!==$this->mailchimp_list) continue;
										
										$stats  = '<ul class="woocommerce_stats">';
										$stats .= '<li><strong>'.$list['stats']['member_count'].'</strong> '.__('Total subscribers', 'wc_subscribe_to_newsletter').'</li>';
										$stats .= '<li><strong>'.$list['stats']['unsubscribe_count'].'</strong> '.__('Unsubscribes', 'wc_subscribe_to_newsletter').'</li>';
										$stats .= '<li><strong>'.$list['stats']['member_count_since_send'].'</strong> '.__('Subscribers since last newsletter', 'wc_subscribe_to_newsletter').'</li>';
										$stats .= '<li><strong>'.$list['stats']['unsubscribe_count_since_send'].'</strong> '.__('Unsubscribes since last newsletter', 'wc_subscribe_to_newsletter').'</li>';
										$stats .= '</ul>';
										echo $stats;
	
										break;
										
									endforeach;
									
									set_transient('woocommerce_mailchimp_stats', $stats, 60*60*1);
								
								endif;
							
							else :
								echo $stats;
							endif;
							
						endif;
					break;
					case "cmonitor" :
						if ($this->cmonitor_api_key && $this->cmonitor_list) :
							
							if ( !$stats = get_transient('woocommerce_cmonitor_stats') ) :
							
								if (!class_exists('CS_REST_Wrapper_Base')) include_once('campaignmonitor/csrest_lists.php');
								
								$api = new CS_REST_Lists( $this->cmonitor_list, $this->cmonitor_api_key );
	
								$result = $api->get_stats();
								
								if ($result->was_successful()) :
									
									$stats  = '<ul class="woocommerce_stats">';
									$stats .= '<li><strong>'.$result->response->TotalActiveSubscribers.'</strong> '.__('Total subscribers', 'wc_subscribe_to_newsletter').'</li>';
									$stats .= '<li><strong>'.$result->response->NewActiveSubscribersToday.'</strong> '.__('Subscribers today', 'wc_subscribe_to_newsletter').'</li>';
									$stats .= '<li><strong>'.$result->response->NewActiveSubscribersThisMonth.'</strong> '.__('Subscribers this month', 'wc_subscribe_to_newsletter').'</li>';
									$stats .= '<li><strong>'.$result->response->UnsubscribesThisMonth.'</strong> '.__('Unsubscribes this month', 'wc_subscribe_to_newsletter').'</li>';
									$stats .= '</ul>';
									echo $stats;
									
									set_transient('woocommerce_cmonitor_stats', $stats, 60*60*1);
									
								else :
									echo '<div class="error inline"><p>'.__('Unable to load stats from Campaign Monitor', 'wc_subscribe_to_newsletter').'</p></div>';
								endif;
							
							else :
								echo $stats;
							endif;
							
						endif;
					break;
				endswitch;
			}
			
	        /*-----------------------------------------------------------------------------------*/
			/* Admin Tabs */
			/*-----------------------------------------------------------------------------------*/ 
			
			function add_tab() {
				foreach ( $this->settings_tabs as $name => $label ) :
					$class = 'nav-tab';
					if( $this->current_tab == $name ) $class .= ' nav-tab-active';
					echo '<a href="' . admin_url( 'admin.php?page=woocommerce&tab=' . $name ) . '" class="' . $class . '">' . $label . '</a>';
				endforeach;
			}			
			
			/**
			 * settings_tab_action()
			 *
			 * Do this when viewing our custom settings tab(s). One function for all tabs.
			 */
			function settings_tab_action() {
				global $woocommerce_settings;
				
				// Determine the current tab in effect.
				$current_tab = $this->get_tab_in_view( current_filter(), 'woocommerce_settings_tabs_' );
				
				// Hook onto this from another function to keep things clean.
				do_action( 'woocommerce_newsletter_settings' );
				
				// Display settings for this tab (make sure to add the settings to the tab).
				woocommerce_admin_fields( $woocommerce_settings[$current_tab] );
			}

			/**
			 * add_settings_fields()
			 *
			 * Add settings fields for each tab.
			 */
			function add_settings_fields() {
				global $woocommerce_settings;
				
				// Load the prepared form fields.
				$this->init_form_fields();
				
				if ( is_array( $this->fields ) ) :
					foreach ( $this->fields as $k => $v ) :
						$woocommerce_settings[$k] = $v;
					endforeach;
				endif;
			}

			/**
			 * get_tab_in_view()
			 *
			 * Get the tab current in view/processing.
			 */
			function get_tab_in_view ( $current_filter, $filter_base ) {
				return str_replace( $filter_base, '', $current_filter );
			}

			/**
			 * init_form_fields()
			 *
			 * Prepare form fields to be used in the various tabs.
			 */
			function init_form_fields() {
				// Include classes
				if (!class_exists('MCAPI')) :
					include_once('mailchimp/MCAPI.class.php');
				endif;
				if (!class_exists('CS_REST_Wrapper_Base')) :
					include_once('campaignmonitor/csrest_general.php');
					include_once('campaignmonitor/csrest_clients.php');
				endif;
								
				// Get mailchimp lists
				if ($this->mailchimp_api_key) :
					
					if ( !$mailchimp_lists = get_transient('woocommerce_mailchimp_lists') ) :

						$mailchimp_lists = array( '' => __('Select a list...', 'wc_subscribe_to_newsletter') );
						
						$mailchimp = new MCAPI( $this->mailchimp_api_key );
						$retval = $mailchimp->lists();
						if ($mailchimp->errorCode) :
							echo '<div class="error"><p>'.sprintf(__('Unable to load lists() from MailChimp: (%s) %s', 'wc_subscribe_to_newsletter'), $mailchimp->errorCode, $mailchimp->errorMessage).'</p></div>';
						else :
							foreach ($retval['data'] as $list) :
								$mailchimp_lists[$list['id']] = $list['name'];
							endforeach;
							
							if (sizeof($mailchimp_lists)>1) set_transient('woocommerce_mailchimp_lists', $mailchimp_lists, 60*60*1);
						endif;

					endif;
				else :
					$mailchimp_lists = array( '' => __('Save your API key to see lists.', 'wc_subscribe_to_newsletter') );
				endif;
				
				// Get Campaign Monitor lists
				if ($this->cmonitor_api_key) :
					
					if ( !$cmonitor_lists = get_transient('woocommerce_cmonitor_lists') ) :
					
						$cmonitor_lists = array( '' => __('Select a list...', 'wc_subscribe_to_newsletter') );
						
						// Get clients
						$wrap = new CS_REST_General( $this->cmonitor_api_key );
						$result = $wrap->get_clients();
						if($result->was_successful()) :
							if (is_array($result->response)) :
								foreach ($result->response as $client) :
									
									$cmonitor = new CS_REST_Clients( $client->ClientID, $this->cmonitor_api_key);
									$list_result = $cmonitor->get_lists();
									if($list_result->was_successful()) :
									    if (is_array($list_result->response)) :
									    	foreach ($list_result->response as $list) :
									    		$cmonitor_lists[$list->ListID] = $list->Name . ' ('.$client->Name.')';
									    	endforeach;
									    endif;
									endif;
	
								endforeach;
								
								if (sizeof($cmonitor_lists)>1) set_transient('woocommerce_cmonitor_lists', $cmonitor_lists, 60*60*1);
							endif;
						else :
							echo '<div class="error"><p>'.__('Unable to load data from Campaign Monitor - check your API key.', 'wc_subscribe_to_newsletter').'</p></div>';
						endif;
					
					endif;
				else :
					$cmonitor_lists = array( '' => __('Save your API key to see lists.', 'wc_subscribe_to_newsletter') );
				endif;

				// Define settings			
				$this->fields['newsletter'] = apply_filters('woocommerce_newsletter_settings_fields', array(
					
					array(	'name' => __( 'Newsletter Configuration', 'wc_subscribe_to_newsletter' ), 'type' => 'title','desc' => '', 'id' => 'newsletter' ),
					
					array(  
						'name' => __( 'Service provider', 'wc_subscribe_to_newsletter' ),
						'desc' 		=> __( 'Choose which service is handling your subscribers.', 'wc_subscribe_to_newsletter' ),
						'tip' 		=> '',
						'id' 		=> 'woocommerce_newsletter_service',
						'css' 		=> '',
						'std' 		=> 'mailchimp',
						'type' 		=> 'select',
						'options'	=> array( 'mailchimp' => 'MailChimp', 'cmonitor' => 'Campaign Monitor' )
					),
					
					array(  
						'name' => __( 'Default checkbox status', 'wc_subscribe_to_newsletter' ),
						'desc' 		=> __( 'The default state of the subscribe checkbox. Be aware some countries have laws against using opt-out checkboxes.', 'wc_subscribe_to_newsletter' ),
						'tip' 		=> '',
						'id' 		=> 'woocommerce_newsletter_checkbox_status',
						'css' 		=> '',
						'std' 		=> '',
						'std' 		=> 'unchecked',
						'type' 		=> 'select',
						'options'	=> array( 'checked' => __('Checked', 'wc_subscribe_to_newsletter'), 'unchecked' => __('Un-checked', 'wc_subscribe_to_newsletter') )
					),
					
					array(  
						'name' => __( 'Subscribe checkbox label', 'wc_subscribe_to_newsletter' ),
						'desc' 		=> __( 'The text you want to display next to the "subscribe to newsletter" checkboxes.', 'wc_subscribe_to_newsletter' ),
						'tip' 		=> '',
						'id' 		=> 'woocommerce_newsletter_label',
						'css' 		=> '',
						'std' 		=> '',
						'type' 		=> 'text',
					),
					
					array( 'type' => 'sectionend', 'id' => 'newsletter' ),
					
					array(	'name' => __( 'MailChimp settings', 'wc_subscribe_to_newsletter' ), 'type' => 'title','desc' => __('You only need to complete this section if using <a href="http://mailchimp.com">MailChimp</a> for your newsletter.', 'wc_subscribe_to_newsletter'), 'id' => 'mailchimp' ),
					
					array(  
						'name' => __( 'API Key', 'wc_subscribe_to_newsletter' ),
						'desc' 		=> __( 'You can obtain your API key by <a href="https://us2.admin.mailchimp.com/account/api/">logging in to your MailChimp account</a>.', 'wc_subscribe_to_newsletter' ),
						'tip' 		=> '',
						'id' 		=> 'woocommerce_mailchimp_api_key',
						'css' 		=> '',
						'std' 		=> '',
						'type' 		=> 'text',
					),
					
					array(  
						'name' => __( 'List', 'wc_subscribe_to_newsletter' ),
						'desc' 		=> __( 'Choose a list customers can subscribe to (you must save your API key first).', 'wc_subscribe_to_newsletter' ),
						'tip' 		=> '',
						'id' 		=> 'woocommerce_mailchimp_list',
						'css' 		=> '',
						'std' 		=> '',
						'type' 		=> 'select',
						'options'	=> $mailchimp_lists
					),
					
					array( 'type' => 'sectionend', 'id' => 'mailchimp' ),
					
					array(	'name' => __( 'Campaign Monitor settings', 'wc_subscribe_to_newsletter' ), 'type' => 'title','desc' => __('You only need to complete this section if using <a href="http://www.campaignmonitor.com/">Campaign Monitor</a> for your newsletter.', 'wc_subscribe_to_newsletter'), 'id' => 'cmonitor' ),
					
					array(  
						'name' => __( 'API Key', 'wc_subscribe_to_newsletter' ),
						'desc' 		=> __( 'You can obtain your API key by logging in to your Campaign Monitor account.', 'wc_subscribe_to_newsletter' ),
						'tip' 		=> '',
						'id' 		=> 'woocommerce_cmonitor_api_key',
						'css' 		=> '',
						'std' 		=> '',
						'type' 		=> 'text',
					),
					
					array(  
						'name' => __( 'List', 'wc_subscribe_to_newsletter' ),
						'desc' 		=> __( 'Choose a list customers can subscribe to (you must save your API key first).', 'wc_subscribe_to_newsletter' ),
						'tip' 		=> '',
						'id' 		=> 'woocommerce_cmonitor_list',
						'css' 		=> '',
						'std' 		=> '',
						'type' 		=> 'select',
						'options'	=> $cmonitor_lists
					),
					
					array( 'type' => 'sectionend', 'id' => 'cmonitor' ),
									
				)); // End newsletter settings
			} 

			/**
			 * save_settings()
			 *
			 * Save settings in a single field in the database for each tab's fields (one field per tab).
			 */
			function save_settings() {
				global $woocommerce_settings;
				
				// Make sure our settings fields are recognised.
				$this->add_settings_fields();
				
				$current_tab = $this->get_tab_in_view( current_filter(), 'woocommerce_update_options_' );
				woocommerce_update_options( $woocommerce_settings[$current_tab] );
			}

	        /*-----------------------------------------------------------------------------------*/
			/* Frontend */
			/*-----------------------------------------------------------------------------------*/ 
			
			function newsletter_field( $woocommerce_checkout ) {
				
				$value = ($this->checkbox_status=='checked') ? 1 : 0;
				
				woocommerce_form_field( 'subscribe_to_newsletter', array( 
					'type' 			=> 'checkbox', 
					'class'			=> array('form-row-wide'),
					'label' 		=> $this->checkbox_label
					), $value);
				
				echo '<div class="clear"></div>';
			} 
			
			function process_newsletter_field( $order_id, $posted ) {
				
				if (!isset($_POST['subscribe_to_newsletter'])) return; // They don't want to subscribe
				
				$this->subscribe_to_newsletter( $posted['billing_first_name'], $posted['billing_last_name'], $posted['billing_email']);
				
			}
			
			function process_ppe_newsletter_field( $order ) {
				
				if ( ! isset( $_REQUEST['subscribe_to_newsletter'] ) ) return; // They don't want to subscribe
				
				$this->subscribe_to_newsletter( '', '', $order->billing_email );
				
				$order->add_order_note( __( 'User subscribed to newsletter via PayPal Express return page.', 'wc_subscribe_to_newsletter' ) );
			}
			
			function process_register_form( $sanitized_user_login, $user_email, $reg_errors ) {
				
				if ( ! isset( $_REQUEST['subscribe_to_newsletter'] ) ) return; // They don't want to subscribe
				
				$this->subscribe_to_newsletter( '', '', $user_email );
				
			}
			
			function subscribe_to_newsletter( $first_name, $last_name, $email, $listid='false' ) {

				if (!$email) return; // Email is required
				
				switch ($this->service) :
					case "mailchimp" :
						if ($this->mailchimp_api_key && $this->mailchimp_list) :
							
							// ADDED
							if ( $listid=='false' ) :
								$listid = $this->mailchimp_list;
							endif;
							// ADDED
							
							if (!class_exists('MCAPI')) include_once('mailchimp/MCAPI.class.php');
							
							$api = new MCAPI( $this->mailchimp_api_key );

							$vars = array( 'FNAME' => $first_name, 'LNAME'=> $last_name );

							$retval = $api->listSubscribe( $listid, $email, $vars );
							
							if ( $api->errorCode && $api->errorCode != 214 ) :
								// Email admin
								wp_mail(get_option('admin_email'), __('Email subscription failed (Mailchimp)', 'wc_subscribe_to_newsletter'), '('.$api->errorCode.') '.$api->errorMessage);
							endif;
							
						endif;
					break;
					case "cmonitor" :
						if ($this->cmonitor_api_key && $this->cmonitor_list) :
							
							// ADDED
							if ( $listid=='false' ) :
								$listid = $this->cmonitor_list;
							endif;
							// ADDED
							
							if (!class_exists('CS_REST_Wrapper_Base')) include_once('campaignmonitor/csrest_subscribers.php');
							
							$api = new CS_REST_Subscribers( $listid, $this->cmonitor_api_key );
							
							$name = '';
							
							if ($first_name && $last_name) $name = $first_name . ' ' . $last_name;
							
							$result = $api->add(array(
							    'EmailAddress' 	=> $email,
							    'Name' 			=> $name,
							    'Resubscribe' 	=> true
							));
							
							if (!$result->was_successful()) :
								// Email admin
								wp_mail(get_option('admin_email'), __('Email subscription failed (Campaign Monitor)', 'wc_subscribe_to_newsletter'), '('.$result->http_status_code.') '.print_r($result->response, true));
							endif;
							
						endif;
					break;
				endswitch;
			}
		}
		
		global $woocommerce_subscribe_to_newsletter;
		
		$woocommerce_subscribe_to_newsletter = new woocommerce_subscribe_to_newsletter();
	}
}