<?php
// Add Shortcode Buttons in Visual Editor
add_action('init', 'add_buttons');

// Initialization Function
function add_buttons() {
    if ( current_user_can('edit_posts') &&  current_user_can('edit_pages') )
    {
	  add_filter('mce_external_plugins', 'add_plugins');
      add_filter('mce_buttons_3', 'register_buttons');
	}
 }

// Register Buttons
function register_buttons($buttons) {
	array_push($buttons, "slider");
	array_push($buttons, "carousel");
	array_push($buttons, "columns");
	array_push($buttons, "posts");
	array_push($buttons, "tabs");
	array_push($buttons, "accordions");
	array_push($buttons, "toggle");
	array_push($buttons, "quote");
	array_push($buttons, "pqleft");
	array_push($buttons, "pqright");
	array_push($buttons, "dropcap");
	array_push($buttons, "idropcap");
	array_push($buttons, "box");
	array_push($buttons, "list");
	array_push($buttons, "hr");
	array_push($buttons, "btn");
	array_push($buttons, "indicator");
	array_push($buttons, "logo_grid");
    return $buttons;
}

// Register TinyMCE Plugin
function add_plugins($plugin_array) {
	$plugin_array['slider'] = get_template_directory_uri().'/includes/shortcodes/js/slider_sc.js';
	$plugin_array['carousel'] = get_template_directory_uri().'/includes/shortcodes/js/carousel_sc.js';
	$plugin_array['columns'] = get_template_directory_uri().'/includes/shortcodes/js/columns.js';
	$plugin_array['posts'] = get_template_directory_uri().'/includes/shortcodes/js/posts_sc.js';
	$plugin_array['tabs'] = get_template_directory_uri().'/includes/shortcodes/js/tabs_sc.js';
	$plugin_array['accordions'] = get_template_directory_uri().'/includes/shortcodes/js/accordions_sc.js';
	$plugin_array['toggle'] = get_template_directory_uri().'/includes/shortcodes/js/toggle_sc.js';
	$plugin_array['quote'] = get_template_directory_uri().'/includes/shortcodes/js/quote_sc.js';
	$plugin_array['pqleft'] = get_template_directory_uri().'/includes/shortcodes/js/pqleft_sc.js';
	$plugin_array['pqright'] = get_template_directory_uri().'/includes/shortcodes/js/pqright_sc.js';
	$plugin_array['dropcap'] = get_template_directory_uri().'/includes/shortcodes/js/dropcap_sc.js';
	$plugin_array['idropcap'] = get_template_directory_uri().'/includes/shortcodes/js/idropcap_sc.js';
	$plugin_array['box'] = get_template_directory_uri().'/includes/shortcodes/js/box_sc.js';
	$plugin_array['list'] = get_template_directory_uri().'/includes/shortcodes/js/list_sc.js';
	$plugin_array['hr'] = get_template_directory_uri().'/includes/shortcodes/js/hr_sc.js';
	$plugin_array['btn'] = get_template_directory_uri().'/includes/shortcodes/js/btn_sc.js';
	$plugin_array['indicator'] = get_template_directory_uri().'/includes/shortcodes/js/indicator_sc.js';
	$plugin_array['logo_grid'] = get_template_directory_uri().'/includes/shortcodes/js/logo_grid_sc.js';
    return $plugin_array;
 } ?>