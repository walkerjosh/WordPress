<?php
/**
 * Subscribe to Newsletter Widget
 * 
 * @package		WooCommerce
 * @category	Widgets
 * @author		WooThemes
 */
class WooCommerce_Widget_Subscibe_to_Newsletter extends WP_Widget {

	/** Variables to setup the widget. */
	var $woo_widget_cssclass;
	var $woo_widget_description;
	var $woo_widget_idbase;
	var $woo_widget_name;
	
	/** constructor */
	function WooCommerce_Widget_Subscibe_to_Newsletter() {
	
		/* Widget variable settings. */
		$this->woo_widget_cssclass = 'widget_subscribe_to_newsletter';
		$this->woo_widget_description = __( 'Allow users to subscribe to your MailChimp or Campaign Monitor lists.', 'wc_subscribe_to_newsletter' );
		$this->woo_widget_idbase = 'woocommerce_subscribe_to_newsletter';
		$this->woo_widget_name = __('WooCommerce Subscribe to Newsletter', 'wc_subscribe_to_newsletter' );
		
		/* Widget settings. */
		$widget_ops = array( 'classname' => $this->woo_widget_cssclass, 'description' => $this->woo_widget_description );
		
		/* Create the widget. */
		$this->WP_Widget('woocommerce_subscribe_to_newsletter', $this->woo_widget_name, $widget_ops);
	}

	/** @see WP_Widget */
	function widget( $args, $instance ) {
		extract($args);

		$title   = $instance['title'];
		$listid  = $instance['list'];
		$title   = apply_filters('widget_title', $title, $instance, $this->id_base);
		
		echo $before_widget;
		
		if ($title) echo $before_title . $title . $after_title;
		
		?>
		<form method="post" id="subscribeform" action="#subscribeform">
			<?php
				if (isset($_POST['newsletter_email'])) :
					global $woocommerce_subscribe_to_newsletter;
					$email = woocommerce_clean( $_POST['newsletter_email'] );
					if (!is_email($email)) :
						echo '<div class="woocommerce_error">'.__('Please enter a valid email address.', 'wc_subscribe_to_newsletter').'</div>';
					else :
						$woocommerce_subscribe_to_newsletter->subscribe_to_newsletter('', '', $email,$listid);
						echo '<div class="woocommerce_message">'.__('Thanks for subscribing.', 'wc_subscribe_to_newsletter').'</div>';
					endif;
				endif;
			?>
			<div>
				<label class="screen-reader-text hidden" for="s"><?php _e('Email Address:', 'wc_subscribe_to_newsletter'); ?></label>
				<input type="text" name="newsletter_email" id="newsletter_email" placeholder="<?php _e('Your email address', 'wc_subscribe_to_newsletter'); ?>" />
				<input type="submit" id="newsletter_subscribe" value="<?php _e('Subscribe', 'wc_subscribe_to_newsletter'); ?>" />
			</div>
		</form>
		<?php
		
		echo $after_widget;
	}

	/** @see WP_Widget->update */
	function update( $new_instance, $old_instance ) {
		$instance['title'] = strip_tags(stripslashes($new_instance['title']));
		$instance['list']  = strip_tags(stripslashes($new_instance['list']));
		return $instance;
	}

	/** @see WP_Widget->form */
	function form( $instance ) {
		global $wpdb;
		
		if (!class_exists('MCAPI')) :
			include_once('mailchimp/MCAPI.class.php');
		endif;
		if (!class_exists('CS_REST_Wrapper_Base')) :
			include_once('campaignmonitor/csrest_general.php');
			include_once('campaignmonitor/csrest_clients.php');
		endif;
		
		?>
			<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'wc_subscribe_to_newsletter') ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" value="<?php if (isset ( $instance['title'])) {echo esc_attr( $instance['title'] );} else {echo __('Newsletter', 'wc_subscribe_to_newsletter');} ?>" /></p>
			<p><label for="<?php echo $this->get_field_id('list'); ?>"><?php _e('List:', 'woothemes') ?></label>
<?php
				if ( get_option('woocommerce_newsletter_service') == 'mailchimp' && get_option('woocommerce_mailchimp_api_key') ) :
					
					if ( !$lists = get_transient('woocommerce_mailchimp_lists') ) :

						$lists = array( '' => __('Select a list...', 'wc_subscribe_to_newsletter') );
						
						$mailchimp = new MCAPI( get_option('woocommerce_mailchimp_api_key') );
						$retval = $mailchimp->lists();
						if ($mailchimp->errorCode) :
							echo '<div class="error"><p>'.sprintf(__('Unable to load lists() from MailChimp: (%s) %s', 'wc_subscribe_to_newsletter'), $mailchimp->errorCode, $mailchimp->errorMessage).'</p></div>';
						else :
							foreach ($retval['data'] as $list) :
								$lists[$list['id']] = $list['name'];
							endforeach;
							
							set_transient('woocommerce_mailchimp_lists', $lists, 60*60*1);

						endif;

					endif;
				elseif ( get_option('woocommerce_newsletter_service') == 'cmonitor' && get_option('woocommerce_cmonitor_api_key') ) :
					
					if ( !$lists = get_transient('woocommerce_cmonitor_lists') ) :
					
						$lists = array( '' => __('Select a list...', 'wc_subscribe_to_newsletter') );
						
						// Get clients
						$wrap = new CS_REST_General( get_option('woocommerce_cmonitor_api_key') );
						$result = $wrap->get_clients();
						if($result->was_successful()) :
							if (is_array($result->response)) :
								foreach ($result->response as $client) :
									
									$cmonitor = new CS_REST_Clients( $client->ClientID, get_option('woocommerce_cmonitor_api_key'));
									$list_result = $cmonitor->get_lists();
									if($list_result->was_successful()) :
									    if (is_array($list_result->response)) :
									    	foreach ($list_result->response as $list) :
									    		$lists[$list->ListID] = $list->Name . ' ('.$client->Name.')';
									    	endforeach;
									    endif;
									endif;
	
								endforeach;
								
								set_transient('woocommerce_cmonitor_lists', $lists, 60*60*1);
								
							endif;
						else :
							echo '<div class="error"><p>'.__('Unable to load data from Campaign Monitor - check your API key.', 'wc_subscribe_to_newsletter').'</p></div>';
						endif;
					
					endif;

				else :
					$lists = array( '' => __('Save your API key to see lists.', 'wc_subscribe_to_newsletter') );
				endif;
				
				echo '<select id="' . esc_attr( $this->get_field_id('list') ) .'" name="' . esc_attr( $this->get_field_name('list') ) .'" class="widefat">';				
				if ( $lists ) :					

					foreach ( $lists as $key=>$value ) :
						echo '<option value="' .$key. '" ' .($key == $instance['list'] ? 'selected="selected"' : ''). '>' .$value. '</option>';
					endforeach ;
					
				endif;
				echo '</select>';				
				echo '<small>'.__('Choose a list to subscribe newsletter suscribers to or leave blank to use the list in your setting panel.', 'woothemes').'</small>';
?>
			</p>
		<?php
	}
} // WooCommerce_Widget_Subscibe_to_Newsletter