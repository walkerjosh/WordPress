<?php

/**
 * WC_Advanced_Notifications_Admin class.
 */
class WC_Advanced_Notifications_Admin {
	
	private $editing;
	private $editing_id;
	
	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	function __construct() {
		// Admin menu
		add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
		
		// Meta
		add_action( 'woocommerce_product_options_general_product_data', array( &$this, 'write_panel' ) );
		add_action( 'woocommerce_process_product_meta', array( &$this, 'write_panel_save' ) );
	}

	/**
	 * admin_menu function.
	 *
	 * @access public
	 * @return void
	 */
	function admin_menu() {
		$page = add_submenu_page( 'woocommerce', __( 'Advanced Notifications', 'wc_adv_notifications' ), __( 'Notifications', 'wc_adv_notifications' ), 'manage_woocommerce', 'advanced-notifications', array( &$this, 'admin_screen' ) );

		add_action( 'admin_print_styles-'. $page, 'woocommerce_admin_css' );
		add_action( 'admin_print_styles-'. $page, array( &$this, 'admin_enqueue' ) );
	}

	/**
	 * admin_enqueue function.
	 *
	 * @access public
	 * @return void
	 */
	function admin_enqueue() {
		wp_enqueue_script( 'woocommerce_admin' );
		wp_enqueue_script( 'chosen' );
		wp_enqueue_style( 'notifications_css', plugins_url( 'assets/css/admin.css' , dirname( __FILE__ ) ) );
	}
	
	/**
	 * write_panel function.
	 * 
	 * @access public
	 * @return void
	 */
	function write_panel() {
		global $wpdb, $post;
		
    	$notifications = $wpdb->get_results( "
			SELECT * FROM {$wpdb->prefix}advanced_notifications
		" );
					
		if ( ! $notifications )
			return;
		
		echo '<div class="options_group">';
		
		$triggers = $wpdb->get_col( "SELECT notification_id FROM {$wpdb->prefix}advanced_notification_triggers WHERE object_id = " . absint( $post->ID ) . " AND object_type = 'product';" );
    	?>
    	<p class="form-field">
	    	<label><?php _e( 'Notifications', 'wc_adv_notifications' ); ?></label>
	    	<select id="notification_recipients" name="notification_recipients[]" multiple="multiple" style="width:300px;" data-placeholder="<?php _e('Choose recipients for this product&hellip;', 'wc_table_rate'); ?>" class="chosen_select">
				<?php
					foreach ( $notifications as $notification ) {
						echo '<option value="' . $notification->notification_id . '" ' . selected( in_array( $notification->notification_id, $triggers ), true, false ) . '>' . $notification->recipient_name . '</option>';
					}
				?>
	        </select>
        </p>
        <?php
    	
    	echo '</div>';
    }
    
    /**
     * write_panel_save function.
     * 
     * @access public
     * @param mixed $post_id
     * @return void
     */
    function write_panel_save( $post_id ) {
		global $wpdb;
		
		$recipients = array( 0 );
		$triggers = array();
		
		// Get new	
    	if ( isset( $_POST['notification_recipients'] ) )
    		if ( is_array( $_POST['notification_recipients'] ) )
    			foreach ( $_POST['notification_recipients'] as $recipient ) {
    				$recipient = absint( $recipient );
	    			$recipients[] = $recipient;
	    			$triggers[] = "( {$recipient}, {$post_id}, 'product' )";
	    		}
		
		// Delete current triggers for this product
		$wpdb->query( "
			DELETE FROM {$wpdb->prefix}advanced_notification_triggers 
			WHERE object_id = " . absint( $post_id ) . "
			AND object_type = 'product'
			AND object_id NOT IN ( " . implode( ',', $recipients ) . " )
		" );
		
		// Save new	
		if ( sizeof( $triggers ) > 0 ) {
			$wpdb->query( "
				INSERT INTO {$wpdb->prefix}advanced_notification_triggers ( notification_id, object_id, object_type )
				VALUES " . implode( ',', $triggers ) . ";
			" );
		}
					
    }

	/**
	 * admin_screen function.
	 *
	 * @access public
	 * @return void
	 */
	function admin_screen() {
		global $wpdb;
		
		$admin = $this;
		
		if ( ! empty( $_GET['delete'] ) ) {
			
			check_admin_referer( 'delete_notification' );

			$delete = absint( $_GET['delete'] );

			$wpdb->query( "DELETE FROM {$wpdb->prefix}advanced_notifications WHERE notification_id = {$delete};" );
			$wpdb->query( "DELETE FROM {$wpdb->prefix}advanced_notification_triggers WHERE notification_id = {$delete};" );
			
			wp_redirect( admin_url( 'admin.php?page=advanced-notifications&deleted=true' ) );
			exit;
		
		} elseif ( ! empty( $_GET['add'] ) ) {
		
			if ( ! empty( $_POST['save_recipient'] ) ) {
			
				check_admin_referer( 'woocommerce_save_recipient' );

				$result = $this->add_recipient();
				
				if ( is_wp_error( $result ) ) {
					echo '<div class="error"><p>' . $result->get_error_message() . '</p></div>';
				} elseif ( $result ) {
					
					wp_redirect( admin_url( 'admin.php?page=advanced-notifications&success=true' ) );
					exit;
					
				}

			}

			include_once( 'includes/admin-screen-edit.php' );

		} elseif ( ! empty( $_GET['edit'] ) ) {
			
			$this->editing_id = absint( $_GET['edit'] );
			$this->editing = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}advanced_notifications WHERE notification_id = " . $this->editing_id . ";" );
		
			if ( ! empty( $_POST['save_recipient'] ) ) {
			
				check_admin_referer( 'woocommerce_save_recipient' );

				$result = $this->save_recipient();
				
				if ( is_wp_error( $result ) ) {
					echo '<div class="error"><p>' . $result->get_error_message() . '</p></div>';
				} elseif ( $result ) {
					
					wp_redirect( admin_url( 'admin.php?page=advanced-notifications&success=true' ) );
					exit;
					
				}

			}

			include_once( 'includes/admin-screen-edit.php' );

		} else {
		
			if ( ! empty( $_GET['success'] ) ) 
				echo '<div class="updated fade"><p>' . __( 'Notification saved successfully', 'wc_adv_notifications' ) . '</p></div>'; 
				
			if ( ! empty( $_GET['deleted'] ) ) 
				echo '<div class="updated fade"><p>' . __( 'Notification deleted successfully', 'wc_adv_notifications' ) . '</p></div>'; 

			if ( ! class_exists( 'WP_List_Table' ) )
				include_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
			include_once( 'class-wc-advanced-notifications-table.php' );
			include_once( 'includes/admin-screen.php' );

		}

	}
	
	
	/**
	 * field_value function.
	 * 
	 * @access public
	 * @param mixed $name
	 * @return void
	 */
	function field_value( $name ) {
		global $wpdb;
		
		$value = '';
		
		if ( isset( $this->editing->$name ) ) {
		
			$value = $this->editing->$name;
			
		} elseif ( $name == 'notification_triggers' ) {
			
			$value = $wpdb->get_col( "SELECT object_id FROM {$wpdb->prefix}advanced_notification_triggers WHERE notification_id = " . absint( $this->editing_id ) . ";" );

		}
		
		$value = maybe_unserialize( $value );
		
		if ( isset( $_POST[ $name ] ) )
			$value = $_POST[ $name ];
			
		if ( is_array( $value ) ) 
			$value = array_map( 'trim', array_map( 'esc_attr', array_map( 'stripslashes', $value ) ) );
		else
			$value = trim( esc_attr( stripslashes( $value ) ) );
			
		return $value;
	}

	/**
	 * add_recipient function.
	 *
	 * @access public
	 * @return void
	 */
	function add_recipient() {
		global $wpdb;

		$recipient_name 		= esc_attr( stripslashes( trim( $_POST['recipient_name'] ) ) );
		$recipient_email 		= esc_attr( stripslashes( trim( $_POST['recipient_email'] ) ) );
		$recipient_address 		= esc_attr( stripslashes( trim( $_POST['recipient_address'] ) ) );
		$recipient_phone 		= esc_attr( stripslashes( trim( $_POST['recipient_phone'] ) ) );
		$recipient_website 		= esc_attr( stripslashes( trim( $_POST['recipient_website'] ) ) );
		$notification_type 		= isset( $_POST['notification_type'] ) ? array_filter( array_map( 'esc_attr', array_map( 'trim', (array) $_POST['notification_type'] ) ) ) : array();
		$notification_plain_text= isset( $_POST['notification_plain_text'] ) ? 1 : 0;
		$notification_totals	= isset( $_POST['notification_totals'] ) ? 1 : 0;
		$notification_prices	= isset( $_POST['notification_prices'] ) ? 1 : 0;
		
		// Validate
		if ( empty( $recipient_name ) )
			return new WP_ERROR( 'input', __( 'Recipient name is a required field', 'wc_adv_notifications' ) );
			
		if ( empty( $recipient_email ) )
			return new WP_ERROR( 'input', __( 'Recipient email is a required field', 'wc_adv_notifications' ) );
			
		$recipient_emails = array_map( 'trim', explode( ',', $recipient_email ) );
		
		foreach ( $recipient_emails as $email )
			if ( ! is_email( $email ) )
				return new WP_Error( 'input', __( 'A recipient email is invalid:', 'wc_adv_notifications' ) . ' ' . $email );
		
		// Insert recipient
		$result = $wpdb->insert(
			"{$wpdb->prefix}advanced_notifications",
			array(
				'recipient_name' 			=> $recipient_name,
				'recipient_email' 			=> $recipient_email,
				'recipient_address' 		=> $recipient_address,
				'recipient_phone' 			=> $recipient_phone,
				'recipient_website' 		=> $recipient_website,
				'notification_plain_text' 	=> $notification_plain_text,
				'notification_type' 		=> serialize( $notification_type ),
				'notification_totals' 		=> $notification_totals,
				'notification_prices' 		=> $notification_prices
			),
			array(
				'%s', '%s', '%s', '%s', '%s', '%d', '%s', '%d', '%d'
			)
		);
		
		$notification_id = $wpdb->insert_id;
		
		if ( $result && $notification_id ) {
		
			$triggers = array();
		
			// Store triggers
			$posted_triggers = isset( $_POST['notification_triggers'] ) ? array_filter( array_map( 'esc_attr', array_map( 'trim', (array) $_POST['notification_triggers'] ) ) ) : array();
			
			foreach ( $posted_triggers as $trigger ) {
				if ( $trigger == 'all' ) {
			
					$triggers[] = "( {$notification_id}, 0, '' )";
					
				} else {
					$trigger = explode( ':', $trigger );
					
					$term 	= esc_attr( $trigger[0] );
					$id 	= absint( $trigger[1] );
			
					$triggers[] = "( {$notification_id}, {$id}, '{$term}' )";
				}
			}
			
			if ( sizeof( $triggers ) > 0 ) {
				$wpdb->query( "
					INSERT INTO {$wpdb->prefix}advanced_notification_triggers ( notification_id, object_id, object_type )
					VALUES " . implode( ',', $triggers ) . ";
				" );
			}

			return true;
		}

		return false;
	}
	
	/**
	 * save_recipient function.
	 * 
	 * @access public
	 * @return void
	 */
	function save_recipient() {
		global $wpdb;

		$recipient_name 		= esc_attr( stripslashes( trim( $_POST['recipient_name'] ) ) );
		$recipient_email 		= esc_attr( stripslashes( trim( $_POST['recipient_email'] ) ) );
		$recipient_address 		= esc_attr( stripslashes( trim( $_POST['recipient_address'] ) ) );
		$recipient_phone 		= esc_attr( stripslashes( trim( $_POST['recipient_phone'] ) ) );
		$recipient_website 		= esc_attr( stripslashes( trim( $_POST['recipient_website'] ) ) );
		$notification_type 		= isset( $_POST['notification_type'] ) ? array_filter( array_map( 'esc_attr', array_map( 'trim', (array) $_POST['notification_type'] ) ) ) : array();
		$notification_plain_text= isset( $_POST['notification_plain_text'] ) ? 1 : 0;
		$notification_totals	= isset( $_POST['notification_totals'] ) ? 1 : 0;
		$notification_prices	= isset( $_POST['notification_prices'] ) ? 1 : 0;
		
		// Validate
		if ( empty( $recipient_name ) )
			return new WP_ERROR( 'input', __( 'Recipient name is a required field', 'wc_adv_notifications' ) );
			
		if ( empty( $recipient_email ) )
			return new WP_ERROR( 'input', __( 'Recipient email is a required field', 'wc_adv_notifications' ) );
			
		$recipient_emails = array_map( 'trim', explode( ',', $recipient_email ) );
		
		foreach ( $recipient_emails as $email )
			if ( ! is_email( $email ) )
				return new WP_Error( 'input', __( 'A recipient email is invalid:', 'wc_adv_notifications' ) . ' ' . $email );
		
		// Insert recipient
		$wpdb->update(
			"{$wpdb->prefix}advanced_notifications",
			array(
				'recipient_name' 			=> $recipient_name,
				'recipient_email' 			=> $recipient_email,
				'recipient_address' 		=> $recipient_address,
				'recipient_phone' 			=> $recipient_phone,
				'recipient_website' 		=> $recipient_website,
				'notification_plain_text' 	=> $notification_plain_text,
				'notification_type' 		=> serialize( $notification_type ),
				'notification_totals' 		=> $notification_totals,
				'notification_prices' 		=> $notification_prices
			),
			array( 'notification_id' => absint( $this->editing_id ) ), 
			array(
				'%s', '%s', '%s', '%s', '%s', '%d', '%s', '%d', '%d'
			),
			array( '%d' ) 
		);
		
		// Delete old triggers
		$wpdb->query( "
			DELETE FROM {$wpdb->prefix}advanced_notification_triggers 
			WHERE notification_id = " . absint( $this->editing_id ) . ";
		" );
		
		$triggers = array();
	
		// Store triggers
		$posted_triggers = isset( $_POST['notification_triggers'] ) ? array_filter( array_map( 'esc_attr', array_map( 'trim', (array) $_POST['notification_triggers'] ) ) ) : array();
		
		foreach ( $posted_triggers as $trigger ) {
			if ( $trigger == 'all' ) {
			
				$triggers[] = "( " . absint( $this->editing_id ) . ", 0, '' )";
				
			} else {
				$trigger = explode( ':', $trigger );
				
				$term 	= esc_attr( $trigger[0] );
				$id 	= absint( $trigger[1] );
	
				$triggers[] = "( " . absint( $this->editing_id ) . ", {$id}, '{$term}' )";
			}
		}
		
		if ( sizeof( $triggers ) > 0 ) {
			$wpdb->query( "
				INSERT INTO {$wpdb->prefix}advanced_notification_triggers ( notification_id, object_id, object_type )
				VALUES " . implode( ',', $triggers ) . ";
			" );
		}

		return true;
	}

}