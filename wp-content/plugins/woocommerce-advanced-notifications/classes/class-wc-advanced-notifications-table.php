<?php
/**
 * WC_Advanced_Notifications_Table class.
 *
 * @extends WP_List_Table
 */
class WC_Advanced_Notifications_Table extends WP_List_Table {

    /**
     * __construct function.
     */
    function __construct(){
        parent::__construct( array(
            'singular'  => 'email',
            'plural'    => 'emails',
            'ajax'      => false
        ) );
    }

    /**
     * column_default function.
     *
     * @access public
     * @param mixed $item
     * @param mixed $column_name
     * @return void
     */
    function column_default( $item, $column_name ) {
    	global $woocommerce, $wpdb;
    	
        switch( $column_name ) {
            case 'recipient_name' :
            	$return = '<strong>' . $item->recipient_name . '</strong>';
            	
            	$formatted_mails = array();
        		$emails = array_map( 'trim', explode( ',', $item->recipient_email ) );
        		foreach ( $emails as $email )
        			$formatted_mails[] = '<a href="mailto:' . $email . '">' . $email . '</a>';
        		$return .= ' <' . implode( ', ', $formatted_mails ) . '>';
            	
            	/*$return .= '
            	<div class="row-actions">
            		<span class="edit"><a href="' . admin_url( 'admin.php?page=advanced-notifications&edit=' . $item->notification_id ) . '">' . __( 'Edit', 'wc_adv_notifications' ) . '</a> | </span>
            		<span class="trash"><a class="submitdelete" href="' . wp_nonce_url( admin_url( 'admin.php?page=advanced-notifications&delete=' . $item->notification_id ), 'delete_notification' ) . '">' . __( 'Delete', 'wc_adv_notifications' ) . '</a></span>
            	</div>';*/
            	
            	return wpautop( $return );
            break;
            case 'recipient_details' :
                $return = $item->recipient_website ? '<p><a href="' . $item->recipient_website . '" rel="nofollow">' . $item->recipient_website . '</a></p>' : '';
                $return .= $item->recipient_phone ? '<p>' . $item->recipient_phone . '</p>' : '';
                $return .= $item->recipient_address ? '<p>' . nl2br( $item->recipient_address ) . '</p>' : '';
                return $return ? $return : '-';
            break;
            case 'notification_type' :
            	$formatted_types = array();
            	$types = maybe_unserialize( $item->notification_type );
            	foreach ( $types as $type ) {
            		switch ( $type ) {
	            		case 'low_stock' :
	            			$formatted_types[] = __( 'Low stock', 'wc_adv_notifications' );
	            		break;
	            		case 'out_of_stock' :
	            			$formatted_types[] = __( 'Out of stock', 'wc_adv_notifications' );
	            		break;
	            		case 'backorders' :
	            			$formatted_types[] = __( 'Backorders', 'wc_adv_notifications' );
	            		break;
	            		case 'purchases' :
	            			$formatted_types[] = __( 'Purchases', 'wc_adv_notifications' );
	            		break;
            		}
            	}
            	return sizeof( $formatted_types ) ? '<p>' . implode( ', ', $formatted_types ) . '</p>' : '-';
            break;
            case 'notification_plain_text' :
            case 'notification_prices' :
            case 'notification_totals' :
            	if ( $item->$column_name == 1 )
            		return '<img src="' . $woocommerce->plugin_url() . '/assets/images/icons/complete.png" alt="yes" width="14" />';
            	else
            		return '-';
            break;
            case 'notification_sent_count' :
            	return sprintf( _n( "1 sent", "%s sent", $item->notification_sent_count, 'wc_adv_notifications' ), $item->notification_sent_count );
            break;
            case 'notification_triggers' :
            	
            	$return 	= '';
            	$categories = array();
            	$classes 	= array();
            	$products	= array();
            	
            	$triggers = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}advanced_notification_triggers WHERE notification_id = " . absint( $item->notification_id ) . ";" );
            	
            	foreach ( $triggers as $trigger ) {
            	
            		if ( '0' === $trigger->object_id )
            			return '<strong> ' . __( 'All Purchases', 'wc_adv_notifications' ) . '</strong>';
	            	            		
	            	switch ( $trigger->object_type ) {
		            	case 'product_cat' :
		            		$term = get_term( $trigger->object_id, $trigger->object_type );
	            	
		            		if ( ! $term ) continue;

		            		$categories[] = $term->name;
		            	break;
		            	case 'product_shipping_class' :
		            		$term = get_term( $trigger->object_id, $trigger->object_type );
	            	
		            		if ( ! $term ) continue;
		            		
		            		$classes[] = $term->name;
		            	break;
		            	case 'product' :
		            		$products[] = '<a href="' . admin_url( 'post.php?post=' . $trigger->object_id . '&action=edit' ) . '">' . get_the_title( $trigger->object_id ) . '</a>';
		            	break;
	            	}
            	}
            	
            	if ( sizeof( $categories ) > 0 )
            		$return .= '<p><strong>' . __( 'Categories:', 'wc_adv_notifications' ) . '</strong> ' . implode( ', ', $categories ) . '</p>';
            		
            	if ( sizeof( $classes ) > 0 )
            		$return .= '<p><strong>' . __( 'Classes:', 'wc_adv_notifications' ) . '</strong> ' . implode( ', ', $classes ) . '</p>';
            		
            	if ( sizeof( $products ) > 0 )
            		$return .= '<p><strong>' . __( 'Products:', 'wc_adv_notifications' ) . '</strong> ' . implode( ', ', $products ) . '</p>';
            	
            	if ( ! $return )
            		$return = '-';
            	
            	return $return;            	
            break;
            case 'actions' :
            	return '<p>
            		<a class="button tips" data-tip="' . __( 'Edit', 'wc_adv_notifications' ) . '" href="' . admin_url( 'admin.php?page=advanced-notifications&edit=' . $item->notification_id ) . '"><img src="' . plugins_url( 'assets/images/edit.png' , dirname( __FILE__ ) ) . '" alt="' . __( 'Edit', 'wc_adv_notifications' ) . '" width="14"></a> <a class="button tips submitdelete" data-tip="' . __( 'Delete', 'wc_adv_notifications' ) . '" href="' . wp_nonce_url( admin_url( 'admin.php?page=advanced-notifications&delete=' . $item->notification_id ), 'delete_notification' ) . '"><img src="' . plugins_url( 'assets/images/delete.png' , dirname( __FILE__ ) ) . '" alt="' . __( 'Delete', 'wc_adv_notifications' ) . '" width="14"></a>
            	</p>';
            break;
        }
	}

    /**
     * column_cb function.
     *
     * @access public
     * @param mixed $item
     * @return void
     */
    function column_cb( $item ){
        return sprintf(
            '<input type="checkbox" name="notification_id[]" value="%s" />',
            /*$2%s*/ $item->notification_id
        );
    }

    /**
     * get_columns function.
     *
     * @access public
     * @return void
     */
    function get_columns(){
        $columns = array(
            'cb'        		=> '<input type="checkbox" />',
            'recipient_name'    => __( 'Recipient', 'wc_adv_notifications' ),
            //'recipient_email'   => __( 'Email', 'wc_adv_notifications' ),
            //'recipient_details'	=> __( 'Details', 'wc_adv_notifications' ),
            'notification_type' => __( 'Notification Types', 'wc_adv_notifications' ),
            'notification_triggers' => __( 'Triggers', 'wc_adv_notifications' ),
            'notification_plain_text' => __( 'Plain text?', 'wc_adv_notifications' ),
            'notification_prices' => __( 'Prices?', 'wc_adv_notifications' ),
            'notification_totals' => __( 'Totals?', 'wc_adv_notifications' ),
            'notification_sent_count' => __( 'Sent count', 'wc_adv_notifications' ),
            'actions' => __( 'Actions', 'wc_adv_notifications' ),
        );
        return $columns;
    }

     /**
     * Get bulk actions
     */
    function get_bulk_actions() {
        $actions = array(
            'delete'    => __( 'Delete', 'wc_adv_notifications' )
        );
        return $actions;
    }

    /**
     * Process bulk actions
     */
    function process_bulk_action() {
	    global $wpdb;
	    
	    if ( ! $this->current_action() )
	    	return;

	    $emails = array_map( 'intval', $_POST['notification_id'] );

	    if ( $emails ) {

	        if ( 'delete' === $this->current_action() ) {

	           foreach ( $emails as $email_id ) {
		           
		           $email_id = absint( $email_id );

		           $wpdb->query( "DELETE FROM {$wpdb->prefix}advanced_notifications WHERE notification_id = {$email_id};" );
		           $wpdb->query( "DELETE FROM {$wpdb->prefix}advanced_notification_triggers WHERE notification_id = {$email_id};" );

		           
		       }

	        }

	        echo '<div class="updated"><p>' . __( 'Notifications updated', 'wc_adv_notifications' ) . '</p></div>';
        }
    }


    /**
     * prepare_items function.
     *
     * @access public
     * @return void
     */
    function prepare_items() {
        global $wpdb;

        /**
         * Init column headers
         */
        $this->_column_headers = array( $this->get_columns(), array(), array() );

        /**
         * Process bulk actions
         */
        $this->process_bulk_action();

        /**
         * Get emails
         */
        $count = $wpdb->get_var( "SELECT COUNT(notification_id) FROM {$wpdb->prefix}advanced_notifications;" );

        $this->items = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}advanced_notifications LIMIT " . ( 25 * ( $this->get_pagenum() - 1 ) ) . ", 25;" );

		/**
		 * Handle pagination
		 */
		$this->set_pagination_args( array(
            'total_items' => $count,
            'per_page'    => 25,
            'total_pages' => ceil( $count / 25 )
        ) );
    }

}