<?php
/*
  Plugin Name: WooCommerce - Gravity Forms Product Add-Ons
  Plugin URI: http://woothemes.com/woocommerce
  Description: Allows you to use Gravity Forms on individual WooCommerce products. Requires the Gravity Forms plugin to work.
  Version: 1.3.8
  Author: Lucas Stark
  Author URI: http://lucasstark.com
  Requires at least: 3.1
  Tested up to: 3.3

  Copyright: © 2009-2011 Lucas Stark.
  License: GNU General Public License v3.0
  License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

/**
 * Required functions
 * */
if (!function_exists('is_woocommerce_active'))
    require_once( 'woo-includes/woo-functions.php' );

/**
 * Plugin updates
 * */
if (is_admin()) {
    $woo_plugin_updater_gravity_product_addons = new WooThemes_Plugin_Updater(__FILE__);
    $woo_plugin_updater_gravity_product_addons->api_key = 'FD71550D4FCA2DFE9F8CF0767401781F';
    $woo_plugin_updater_gravity_product_addons->init();
}

if (is_woocommerce_active() && in_array('gravityforms/gravityforms.php', apply_filters('active_plugins', get_option('active_plugins')))) {

    load_plugin_textdomain('wc_gf_addons', null, dirname(plugin_basename(__FILE__)) . '/languages');

    if (defined('DOING_AJAX'))
        include 'gravityforms-product-addons-ajax.php';

    class woocommerce_gravityforms {

        var $settings;
        var $edits = array();

        public function __construct() {
            wp_get_referer();
            // Add script to the footer to override the default add to cart buttons throuhgout woocommerce
            add_action('wp_footer', array(&$this, 'on_wp_footer'));

            // Enqueue Gravity Forms Scripts
            add_action('wp_enqueue_scripts', array(&$this, 'woocommerce_gravityform_enqueue_scripts'), 10);

            // Addon display
            add_action('woocommerce_before_add_to_cart_button', array(&$this, 'woocommerce_gravityform'), 10);

            // Filters for price display
            add_filter('woocommerce_grouped_price_html', array(&$this, 'get_price_html'), 10, 2);

            add_filter('woocommerce_variation_price_html', array(&$this, 'get_price_html'), 10, 2);
            add_filter('woocommerce_variation_sale_price_html', array(&$this, 'get_free_price_html'), 10, 2);

            add_filter('woocommerce_variable_price_html', array(&$this, 'get_price_html'), 10, 2);
            add_filter('woocommerce_variable_sale_price_html', array(&$this, 'get_price_html'), 10, 2);
            add_filter('woocommerce_variable_empty_price_html', array(&$this, 'get_price_html'), 10, 2);
            add_filter('woocommerce_variable_free_sale_price_html', array(&$this, 'get_free_price_html'), 10, 2);
            add_filter('woocommerce_variable_free_price_html', array(&$this, 'get_free_price_html'), 10, 2);

            add_filter('woocommerce_sale_price_html', array(&$this, 'get_price_html'), 10, 2);
            add_filter('woocommerce_price_html', array(&$this, 'get_price_html'), 10, 2);
            add_filter('woocommerce_empty_price_html', array(&$this, 'get_price_html'), 10, 2);

            add_filter('woocommerce_free_sale_price_html', array(&$this, 'get_free_price_html'), 10, 2);
            add_filter('woocommerce_free_price_html', array(&$this, 'get_free_price_html'), 10, 2);

            // Filters for cart actions
            add_filter('woocommerce_add_cart_item_data', array(&$this, 'add_cart_item_data'), 10, 2);
            add_filter('woocommerce_get_cart_item_from_session', array(&$this, 'get_cart_item_from_session'), 10, 2);
            add_filter('woocommerce_get_item_data', array(&$this, 'get_item_data'), 10, 2);
            add_filter('woocommerce_add_cart_item', array(&$this, 'add_cart_item'), 10, 1);
            add_action('woocommerce_order_item_meta', array(&$this, 'order_item_meta'), 10, 2);

            add_filter('woocommerce_add_to_cart_validation', array(&$this, 'add_to_cart_validation'), 99, 3);

            // Write Panel
            add_action('add_meta_boxes', array(&$this, 'add_meta_box'));
            add_action('woocommerce_process_product_meta', array(&$this, 'process_meta_box'), 1, 2);
        }

        //Fix up any add to cart button that has a gravity form assoicated with the product. 
        function on_wp_footer() {
            global $wpdb;
            $product_ids = $wpdb->get_col($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_gravity_form_data'"));
            if (is_array($product_ids)) {
                $product_ids = array_flip($product_ids);
                foreach ($product_ids as $k => $v) {
                    $product_ids[$k] = get_permalink($k);
                }
            }
            ?>
            <script type="text/javascript">
                jQuery(document).ready(function($) {
                    var gravityform_products = <?php echo json_encode($product_ids); ?>;
                    var label = "<?php echo apply_filters('gravityforms_add_to_cart_text', apply_filters('variable_add_to_cart_text', __('Select options', 'wc_gf_addons'))); ?>";
                    $('.add_to_cart_button').each(function(){
                        if ($(this).data('product_id') in gravityform_products){
                            $(this).text(label);
                            $(this).click(function(event) {
                                                                                                                                                                                                                                                                                                                                                                        
                                event.preventDefault();
                                var product_id = $(this).data('product_id');
                                                                                                                                                                                                                                                                                                                                                                        
                                window.location = gravityform_products[product_id];
                                return false;
                            });
                                                                                                                                                                                                                                                                                                                                                                    
                        }
                    });
                });
            </script>
            <?php
        }

        /* ----------------------------------------------------------------------------------- */
        /* Write Panel */
        /* ----------------------------------------------------------------------------------- */

        function add_meta_box() {
            global $post;
            add_meta_box('woocommerce-gravityforms-meta', __('Gravity Forms Product Add-Ons', 'wc_gf_addons'), array(&$this, 'meta_box'), 'product', 'normal', 'default');
        }

        function meta_box($post) {
            ?>
            <style>
                #woocommerce-gravityforms-meta .inside{padding:0;margin:0;}
            </style>

            <div class="woocommerce_gravityforms panel-wrap product_data woocommerce">
                <ul class="gravityforms_data_tabs tabs wc-tabs">

                    <li class="active"><a href="#gravityforms_data"><?php _e('General', 'wc_gravityforms'); ?></a></li>

                    <li><a href="#price_labels_data"><?php _e('Price Labels', 'wc_gravityforms'); ?></a></li>

                    <li><a href="#total_labels_data"><?php _e('Total Calculations', 'wc_gravityforms'); ?></a></li>

                </ul>

                <div id="gravityforms_data" class="panel woocommerce_options_panel">

                    <?php
                    $gravity_form_data = get_post_meta($post->ID, '_gravity_form_data', true);

                    $gravityform = NULL;
                    if (is_array($gravity_form_data) && isset($gravity_form_data['id']) && is_numeric($gravity_form_data['id'])) {

                        $form_meta = RGFormsModel::get_form_meta($gravity_form_data['id']);

                        if (!empty($form_meta)) {
                            $gravityform = RGFormsModel::get_form($gravity_form_data['id']);
                        }
                    }
                    ?>
                    <div class="options_group">
                        <p class="form-field">
                            <label for="gravityform-id"><?php _e('Choose Form', 'wc_gravityforms'); ?></label>
                            <?php
                            echo '<select id="gravityform-id" name="gravityform-id"><option value="">' . __('None', 'wc_gf_addons') . '</option>';
                            foreach (RGFormsModel::get_forms() as $form) {
                                echo '<option ' . selected($form->id, $gravity_form_data['id']) . ' value="' . sanitize_title($form->id) . '">' . wptexturize($form->title) . '</option>';
                            }
                            echo '</select>';
                            ?>
                        </p>

                        <?php
                        woocommerce_wp_checkbox(array(
                            'id' => 'gravityform-display_title',
                            'label' => __('Display Title', 'wc_gravityforms'),
                            'value' => isset($gravity_form_data['display_title']) && $gravity_form_data['display_title'] ? 'yes' : ''));

                        woocommerce_wp_checkbox(array(
                            'id' => 'gravityform-display_description',
                            'label' => __('Display Description', 'wc_gravityforms'),
                            'value' => isset($gravity_form_data['display_description']) && $gravity_form_data['display_description'] ? 'yes' : ''));
                        ?>
                    </div>

                    <div class="options_group" style="padding: 0 9px;">
                        <?php if (!empty($gravityform) && is_object($gravityform)) : ?>
                            <h4><a href="<?php printf('%s/admin.php?page=gf_edit_forms&id=%d', get_admin_url(), $gravityform->id) ?>" class="edit_gravityform">Edit <?php echo $gravityform->title; ?> Gravity Form</a></h4>
            <?php endif; ?>
                    </div>
                </div>

                <div id="price_labels_data" class="panel woocommerce_options_panel">
                    <div class="options_group">
                        <?php
                        woocommerce_wp_checkbox(array(
                            'id' => 'gravityform-disable_woocommerce_price',
                            'label' => __('Remove WooCommerce Price?', 'wc_gravityforms'),
                            'value' => isset($gravity_form_data['disable_woocommerce_price']) ? $gravity_form_data['disable_woocommerce_price'] : ''));

                        woocommerce_wp_text_input(array('id' => 'gravityform-price-before', 'label' => __('Price Before', 'wc_gravityforms'),
                            'value' => isset($gravity_form_data['price_before']) ? $gravity_form_data['price_before'] : '',
                            'placeholder' => __('Base Price:', 'wc_gravityforms'), 'description' => __('Enter text you would like printed before the price of the product.', 'wc_gravityforms')));

                        woocommerce_wp_text_input(array('id' => 'gravityform-price-after', 'label' => __('Price After', 'wc_gravityforms'),
                            'value' => isset($gravity_form_data['price_after']) ? $gravity_form_data['price_after'] : '',
                            'placeholder' => __('', 'wc_gravityforms'), 'description' => __('Enter text you would like printed after the price of the product.', 'wc_gravityforms')));
                        ?>
                    </div>
                </div>
                <div id="total_labels_data" class="panel woocommerce_options_panel">
                    <?php
                    echo '<div class="options_group">';
                    woocommerce_wp_checkbox(array(
                        'id' => 'gravityform-disable_calculations',
                        'label' => __('Disable Calculations?', 'wc_gravityforms'),
                        'value' => isset($gravity_form_data['disable_calculations']) ? $gravity_form_data['disable_calculations'] : ''));
                    echo '</div><div class="options_group">';
                    woocommerce_wp_checkbox(array(
                        'id' => 'gravityform-disable_label_subtotal',
                        'label' => __('Disable Subtotal?', 'wc_gravityforms'),
                        'value' => isset($gravity_form_data['disable_label_subtotal']) ? $gravity_form_data['disable_label_subtotal'] : ''));

                    woocommerce_wp_text_input(array('id' => 'gravityform-label_subtotal', 'label' => __('Subtotal Label', 'wc_gravityforms'),
                        'value' => isset($gravity_form_data['label_subtotal']) && !empty($gravity_form_data['label_subtotal']) ? $gravity_form_data['label_subtotal'] : 'Subtotal',
                        'placeholder' => __('Subtotal', 'wc_gravityforms'), 'description' => __('Enter "Subtotal" label to display on for single products.', 'wc_gravityforms')));
                    echo '</div><div class="options_group">';
                    woocommerce_wp_checkbox(array(
                        'id' => 'gravityform-disable_label_options',
                        'label' => __('Disable Options Label?', 'wc_gravityforms'),
                        'value' => isset($gravity_form_data['disable_label_options']) ? $gravity_form_data['disable_label_options'] : ''));

                    woocommerce_wp_text_input(array('id' => 'gravityform-label_options', 'label' => __('Options Label', 'wc_gravityforms'),
                        'value' => isset($gravity_form_data['label_options']) && !empty($gravity_form_data['label_options']) ? $gravity_form_data['label_options'] : 'Options',
                        'placeholder' => __('Options', 'wc_gravityforms'), 'description' => __('Enter the "Options" label to display for single products.', 'wc_gravityforms')));
                    echo '</div><div class="options_group">';
                    woocommerce_wp_checkbox(array(
                        'id' => 'gravityform-disable_label_total',
                        'label' => __('Disable Total Label?', 'wc_gravityforms'),
                        'value' => isset($gravity_form_data['disable_label_total']) ? $gravity_form_data['disable_label_total'] : ''));

                    woocommerce_wp_text_input(array('id' => 'gravityform-label_total', 'label' => __('Total Label', 'wc_gravityforms'),
                        'value' => isset($gravity_form_data['label_total']) && !empty($gravity_form_data['label_total']) ? $gravity_form_data['label_total'] : 'Total',
                        'placeholder' => __('Total', 'wc_gravityforms'), 'description' => __('Enter the "Total" label to display for single products.', 'wc_gravityforms')));
                    echo '</div>';
                    ?>
                </div>
            </div>
            <?php
        }

        function process_meta_box($post_id, $post) {
            global $woocommerce_errors;


            // Save gravity form as serialised array
            if (isset($_POST['gravityform-id']) && !empty($_POST['gravityform-id'])) {
                
                $product = new WC_Product($id);
                if ( $product->product_type != 'variable' && empty($product->price) && ($product->price != '0' || $product->price != '0.00')) {
                    $woocommerce_errors[] = __('You must set a price for the product before the gravity form will be visible.  Set the price to 0 if you are performing all price calculations with the attached Gravity Form.', 'woocommerce');
                }
                
                $gravity_form_data = array(
                    'id' => $_POST['gravityform-id'],
                    'display_title' => isset($_POST['gravityform-display_title']) ? true : false,
                    'display_description' => isset($_POST['gravityform-display_description']) ? true : false,
                    'disable_woocommerce_price' => isset($_POST['gravityform-disable_woocommerce_price']) ? 'yes' : 'no',
                    'price_before' => $_POST['gravityform-price-before'],
                    'price_after' => $_POST['gravityform-price-after'],
                    'disable_calculations' => isset($_POST['gravityform-disable_calculations']) ? 'yes' : 'no',
                    'disable_label_subtotal' => isset($_POST['gravityform-disable_label_subtotal']) ? 'yes' : 'no',
                    'disable_label_options' => isset($_POST['gravityform-disable_label_options']) ? 'yes' : 'no',
                    'disable_label_total' => isset($_POST['gravityform-disable_label_total']) ? 'yes' : 'no',
                    'label_subtotal' => $_POST['gravityform-label_subtotal'],
                    'label_options' => $_POST['gravityform-label_options'],
                    'label_total' => $_POST['gravityform-label_total']
                );
                update_post_meta($post_id, '_gravity_form_data', $gravity_form_data);
            } else {
                delete_post_meta($post_id, '_gravity_form_data');
            }
        }

        /* ----------------------------------------------------------------------------------- */
        /* Product Form Functions */
        /* ----------------------------------------------------------------------------------- */

        function woocommerce_gravityform() {
            global $post, $woocommerce;

            include_once( 'gravityforms-product-addons-form.php' );

            $gravity_form_data = get_post_meta($post->ID, '_gravity_form_data', true);

            if (is_array($gravity_form_data) && $gravity_form_data['id']) {
                $_product = new WC_Product($post->ID);

                $product_form = new woocommerce_gravityforms_product_form($gravity_form_data['id'], $post->ID);
                $product_form->get_form($gravity_form_data);

                $add_to_cart_value = '';
                if ($_product->is_type('variable')) :
                    $add_to_cart_value = 'variation';
                elseif ($_product->has_child()) :
                    $add_to_cart_value = 'group';
                else :
                    $add_to_cart_value = $_product->id;
                endif;

                $woocommerce->nonce_field('add_to_cart');
                echo '<input type="hidden" name="add-to-cart" value="' . $add_to_cart_value . '" />';
            }
            echo '<div class="clear"></div>';
        }

        function woocommerce_gravityform_enqueue_scripts() {
            global $post;

            if (is_product()) {
                $gravity_form_data = get_post_meta($post->ID, '_gravity_form_data', true);
                if ($gravity_form_data && is_array($gravity_form_data)) {
                    gravity_form_enqueue_scripts($gravity_form_data['id'], false);
                }
            }
        }

        function get_price_html($html, $_product) {
            $gravity_form_data = get_post_meta($_product->id, '_gravity_form_data', true);
            if ($gravity_form_data && is_array($gravity_form_data)) {

                if (isset($gravity_form_data['disable_woocommerce_price']) && $gravity_form_data['disable_woocommerce_price'] == 'yes') {
                    $html = '';
                }

                if (isset($gravity_form_data['price_before'])) {
                    $html = '<span class="woocommerce-price-before">' . $gravity_form_data['price_before'] . ' </span>' . $html;
                }

                if (isset($gravity_form_data['price_after'])) {
                    $html .= '<span class="woocommerce-price-after"> ' . $gravity_form_data['price_after'] . '</span>';
                }
            }
            return $html;
        }

        function get_free_price_html($html, $_product) {
            $gravity_form_data = get_post_meta($_product->id, '_gravity_form_data', true);
            if ($gravity_form_data && is_array($gravity_form_data)) {

                if (isset($gravity_form_data['price_before'])) {
                    $html = '<span class="woocommerce-price-before">' . $gravity_form_data['price_before'] . ' </span>';
                }

                if (isset($gravity_form_data['price_after'])) {
                    $html .= '<span class="woocommerce-price-after"> ' . $gravity_form_data['price_after'] . '</span>';
                }
            }
            return $html;
        }

        function get_formatted_price($price) {
            return woocommerce_price($price);
        }

        function disable_notifications($disabled, $form, $lead) {
            return true;
        }

        function add_to_cart_validation($valid, $product_id, $quantity) {
            global $woocommerce;

            // Check if we need a gravity form!
            $gravity_form_data = get_post_meta($product_id, '_gravity_form_data', true);

            if (is_array($gravity_form_data) && $gravity_form_data['id'] && empty($_POST['gform_form_id']))
                return false;

            if (isset($_POST['gform_form_id']) && is_numeric($_POST['gform_form_id'])) {
                $form_id = $_POST['gform_form_id'];

                //Gravity forms generates errors and warnings.  To prevent these from conflicting with other things, we are going to disable warnings and errors.
                error_reporting(0);
                //MUST disable notifications manually. 
                add_filter('gform_disable_user_notification_' . $form_id, array(&$this, 'disable_notifications'), 10, 3);
                add_filter('gform_disable_admin_notification_' . $form_id, array(&$this, 'disable_notifications'), 10, 3);

                require_once(GFCommon::get_base_path() . "/form_display.php");
                GFFormDisplay::process_form($form_id);

                if (!GFFormDisplay::$submission[$form_id]['is_valid']) {
                    return false;
                }

                if (GFFormDisplay::$submission[$form_id]['page_number'] != 0) {
                    return false;
                }
            }
            return $valid;
        }

        //When the item is being added to the cart. 
        function add_cart_item_data($cart_item_meta, $product_id) {
            global $woocommerce;
            $gravity_form_data = get_post_meta($product_id, '_gravity_form_data', true);
            $cart_item_meta['_gravity_form_data'] = $gravity_form_data;

            if ($gravity_form_data && is_array($gravity_form_data) &&
                    isset($gravity_form_data['id']) && intval($gravity_form_data['id']) > 0) {

                $form_id = $gravity_form_data['id'];
                $form_meta = RGFormsModel::get_form_meta($form_id);

                //Gravity forms generates errors and warnings.  To prevent these from conflicting with other things, we are going to disable warnings and errors.
                error_reporting(0);

                //MUST disable notifications manually. 
                add_filter('gform_disable_user_notification_' . $form_id, array(&$this, 'disable_notifications'), 10, 3);
                add_filter('gform_disable_admin_notification_' . $form_id, array(&$this, 'disable_notifications'), 10, 3);
                if (empty($form_meta)) {
                    return $cart_item_meta;
                }

                require_once(GFCommon::get_base_path() . "/form_display.php");
                GFFormDisplay::process_form($form_id);

                $lead = GFFormDisplay::$submission[$form_id]['lead'];

                $cart_item_meta['_gravity_form_lead'] = array();

                foreach ($form_meta['fields'] as $field) {
                    if (isset($field['displayOnly']) && $field['displayOnly']) {
                        continue;
                    }

                    $value = RGFormsModel::get_lead_field_value($lead, $field);

                    if (isset($field['inputs']) && is_array($field['inputs'])) {
                        foreach ($field['inputs'] as $input) {
                            $cart_item_meta['_gravity_form_lead'][strval($input['id'])] = $value[strval($input['id'])];
                        }
                    } else {
                        $cart_item_meta['_gravity_form_lead'][strval($field['id'])] = $value;
                    }
                }

                //RGFormsModel::delete_lead($lead['id']);
                if (GFFormDisplay::$submission[$form_id]['is_valid']) {
                    add_filter('add_to_cart_redirect', array(&$this, 'get_redirect_url'), 99);
                    if (get_option('woocommerce_cart_redirect_after_add') == 'yes') {
                        $_SERVER['REQUEST_URI'] = get_site_url();
                    }
                }
            }

            return $cart_item_meta;
        }

        function get_cart_item_from_session($cart_item, $values) {

            if (isset($values['_gravity_form_data'])) {
                $cart_item['_gravity_form_data'] = $values['_gravity_form_data'];
            }

            if (isset($values['_gravity_form_lead'])) {
                $cart_item['_gravity_form_lead'] = $values['_gravity_form_lead'];
            }

            if (isset($cart_item['_gravity_form_lead']) && isset($cart_item['_gravity_form_data'])) {
                $this->add_cart_item($cart_item);
            }

            return $cart_item;
        }

        function get_item_data($other_data, $cart_item) {
            if (isset($cart_item['_gravity_form_lead']) && isset($cart_item['_gravity_form_data'])) {
                //Gravity forms generates errors and warnings.  To prevent these from conflicting with other things, we are going to disable warnings and errors.
                error_reporting(0);

                $gravity_form_data = $cart_item['_gravity_form_data'];
                $form_meta = RGFormsModel::get_form_meta($gravity_form_data['id']);

                if (!empty($form_meta)) {

                    $lead = $cart_item['_gravity_form_lead'];
                    $lead['id'] = -1;

                    foreach ($form_meta['fields'] as $field) {

                        if ($field['type'] == 'product' || $field['inputType'] == 'hiddenproduct' || $field['type'] == 'total' || (isset($field['displayOnly']) && $field['displayOnly'])) {
                            continue;
                        }

                        $value = RGFormsModel::get_lead_field_value($lead, $field);
                        $arr_var = (is_array($value)) ? implode('', $value) : '-';

                        if (!empty($value) && !empty($arr_var)) {
                            $display_value = GFCommon::get_lead_field_display($field, $value, isset($lead["currency"]) ? $lead["currency"] : false );
                            $price_adjustement = false;
                            $display_value = apply_filters("gform_entry_field_value", $display_value, $field, $lead, $form_meta);
                            $display_title = GFCommon::get_label($field);
                            $other_data[] = array('name' => $display_title, 'value' => $display_value);
                        }
                    }
                }
            }

            return $other_data;
        }

        //Helper function, used when an item is added to the cart as well as when an item is restored from session. 
        function add_cart_item($cart_item) {
            global $woocommerce;

            // Adjust price if required based on the gravity form data
            if (isset($cart_item['_gravity_form_lead']) && isset($cart_item['_gravity_form_data'])) {
                //Gravity forms generates errors and warnings.  To prevent these from conflicting with other things, we are going to disable warnings and errors.
                error_reporting(0);

                $gravity_form_data = $cart_item['_gravity_form_data'];
                $form_meta = RGFormsModel::get_form_meta($gravity_form_data['id']);

                if (empty($form_meta)) {
                    $_product = $cart_item['data'];
                    $woocommerce->add_error($_product->get_title() . __(' is invalid.  Please remove and try readding to the cart', 'wc_gf_addons'));
                    return $cart_item;
                }

                $lead = $cart_item['_gravity_form_lead'];

                $products = array();
                $total = 0;

                $lead['id'] = -1;

                $products = $this->get_product_fields($form_meta, $lead);
                if (!empty($products["products"])) {

                    foreach ($products["products"] as $product) {
                        $price = GFCommon::to_number($product["price"]);
                        if (is_array(rgar($product, "options"))) {
                            $count = sizeof($product["options"]);
                            $index = 1;
                            foreach ($product["options"] as $option) {
                                $price += GFCommon::to_number($option["price"]);
                                $class = $index == $count ? " class='lastitem'" : "";
                                $index++;
                            }
                        }
                        $subtotal = floatval($product["quantity"]) * $price;
                        $total += $subtotal;
                    }

                    $total += floatval($products["shipping"]["price"]);
                }

                $cart_item['data']->adjust_price($total);
            }


            return $cart_item;
        }

        function order_item_meta($item_meta, $cart_item) {
            if (isset($cart_item['_gravity_form_lead']) && isset($cart_item['_gravity_form_data'])) {
                //Gravity forms generates errors and warnings.  To prevent these from conflicting with other things, we are going to disable warnings and errors.
                error_reporting(0);

                $gravity_form_data = $cart_item['_gravity_form_data'];
                $form_meta = RGFormsModel::get_form_meta($gravity_form_data['id']);

                if (!empty($form_meta)) {
                    $lead = $cart_item['_gravity_form_lead'];
                    foreach ($form_meta['fields'] as $field) {

                        if ((isset($field['inputType']) && $field['inputType'] == 'hiddenproduct') || (isset($field['displayOnly']) && $field['displayOnly'])) {
                            continue;
                        }

                        $value = RGFormsModel::get_lead_field_value($lead, $field);
                        $arr_var = (is_array($value)) ? implode('', $value) : '-';

                        if (!empty($value) && !empty($arr_var)) {
                            try {
                                $display_value = GFCommon::get_lead_field_display($field, $value, isset($lead["currency"]) ? $lead["currency"] : false );
                                $price_adjustement = false;
                                $display_value = apply_filters("gform_entry_field_value", $display_value, $field, $lead, $form_meta);
                                $display_title = GFCommon::get_label($field);
                                $item_meta->add($display_title, $display_value);
                            } catch (Exception $e) {
                                
                            }
                        }
                    }
                }
            }
        }

        public function get_product_fields($form, $lead, $use_choice_text = false, $use_admin_label = false) {
            $products = array();


            foreach ($form["fields"] as $field) {
                $id = $field["id"];
                $lead_value = RGFormsModel::get_lead_field_value($lead, $field);

                $quantity_field = GFCommon::get_product_fields_by_type($form, array("quantity"), $id);
                $quantity = sizeof($quantity_field) > 0 ? RGFormsModel::get_lead_field_value($lead, $quantity_field[0]) : 1;

                switch ($field["type"]) {

                    case "product" :
                        //if single product, get values from the multiple inputs
                        if (is_array($lead_value)) {
                            $product_quantity = sizeof($quantity_field) == 0 && !rgar($field, "disableQuantity") ? rgget($id . ".3", $lead_value) : $quantity;
                            if (empty($product_quantity))
                                continue;

                            if (!rgget($id, $products))
                                $products[$id] = array();

                            $products[$id]["name"] = $use_admin_label && !rgempty("adminLabel", $field) ? $field["adminLabel"] : $lead_value[$id . ".1"];
                            $products[$id]["price"] = $lead_value[$id . ".2"];
                            $products[$id]["quantity"] = $product_quantity;
                        }
                        else if (!empty($lead_value)) {

                            if (empty($quantity))
                                continue;

                            if (!rgar($products, $id))
                                $products[$id] = array();

                            if ($field["inputType"] == "price") {
                                $name = $field["label"];
                                $price = $lead_value;
                            } else {
                                list($name, $price) = explode("|", $lead_value);
                            }

                            $products[$id]["name"] = !$use_choice_text ? $name : RGFormsModel::get_choice_text($field, $name);
                            $products[$id]["price"] = $price;
                            $products[$id]["quantity"] = $quantity;
                            $products[$id]["options"] = array();
                        }

                        if (isset($products[$id])) {
                            $options = GFCommon::get_product_fields_by_type($form, array("option"), $id);
                            foreach ($options as $option) {
                                $option_value = RGFormsModel::get_lead_field_value($lead, $option);
                                $option_label = empty($option["adminLabel"]) ? $option["label"] : $option["adminLabel"];
                                if (is_array($option_value)) {
                                    foreach ($option_value as $value) {
                                        $option_info = GFCommon::get_option_info($value, $option, $use_choice_text);
                                        if (!empty($option_info))
                                            $products[$id]["options"][] = array("field_label" => rgar($option, "label"), "option_name" => rgar($option_info, "name"), "option_label" => $option_label . ": " . rgar($option_info, "name"), "price" => rgar($option_info, "price"));
                                    }
                                }
                                else if (!empty($option_value)) {
                                    $option_info = GFCommon::get_option_info($option_value, $option, $use_choice_text);
                                    $products[$id]["options"][] = array("field_label" => rgar($option, "label"), "option_name" => rgar($option_info, "name"), "option_label" => $option_label . ": " . rgar($option_info, "name"), "price" => rgar($option_info, "price"));
                                }
                            }
                        }
                        break;
                }
            }

            $shipping_field = GFCommon::get_fields_by_type($form, array("shipping"));
            $shipping_price = $shipping_name = "";

            if (!empty($shipping_field)) {
                $shipping_price = RGFormsModel::get_lead_field_value($lead, $shipping_field[0]);
                $shipping_name = $shipping_field[0]["label"];
                if ($shipping_field[0]["inputType"] != "singleshipping") {
                    list($shipping_method, $shipping_price) = explode("|", $shipping_price);
                    $shipping_name = $shipping_field[0]["label"] . " ($shipping_method)";
                }
            }
            $shipping_price = GFCommon::to_number($shipping_price);
            $product_info = array("products" => $products, "shipping" => array("name" => $shipping_name, "price" => $shipping_price));
            $product_info = apply_filters("gform_product_info_{$form["id"]}", apply_filters("gform_product_info", $product_info, $form, $lead), $form, $lead);
            return $product_info;
        }

        function get_redirect_url($url) {
            global $woocommerce;

            if (!empty($url)) {
                return $url;
            } elseif (get_option('woocommerce_cart_redirect_after_add') == 'yes' && $woocommerce->error_count() == 0) {
                $url = $woocommerce->cart->get_cart_url();
            } else {
                $ref = false;
                if (!empty($_REQUEST['_wp_http_referer']))
                    $ref = $_REQUEST['_wp_http_referer'];
                else if (!empty($_SERVER['HTTP_REFERER']))
                    $ref = $_SERVER['HTTP_REFERER'];

                $url = $ref ? $ref : $url;
            }

            return $url;
        }

    }

    $woocommerce_gravityforms = new woocommerce_gravityforms();
}