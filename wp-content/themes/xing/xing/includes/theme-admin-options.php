<?php
/* Xing Theme Options */

$themename = 'Xing';
$shortname = 'xng';
$options = array (
				array(	"type" => "wrap_start" ),

				array(	"type" => "tabs_start" ),

				array(	"name" => __( 'General', 'xing' ),
						"id" => $shortname."_general",
						"type" => "heading"),

				array(	"name" => __( 'Header', 'xing' ),
						"id" => $shortname."_header_area",
						"type" => "heading"),

				array(	"name" => __( 'Blog', 'xing' ),
						"id" => $shortname."_blog",
						"type" => "heading"),

				array(	"type" => "tabs_end" ),

				array(	"type" => "tabbed_start",
						"id" => $shortname."_general" ),

				array(	"name" => __( 'General Settings for the theme', 'xing' ),
						"type" => "subheading" ),

				array(	"name" => __( 'Layout Style:', 'xing' ),
						"desc" => __( 'Select a layout style for the theme.', 'xing' ),
						"id" => $shortname."_layout",
						"std" => "boxed",
						"type" => "select",
						"options" => array("boxed", "stretched")),

				array(	"name" => __( 'Color Scheme Variation:', 'xing' ),
						"desc" => __( 'Select a color scheme variation for the theme. This will be applied to the navigation bar, footer and some highlights.', 'xing' ),
						"id" => $shortname."_scheme",
						"std" => "default",
						"type" => "select",
						"options" => array("default", "blue", "cherry", "cyan", "green")),

				array(	"name" => __( 'Global Sidebar Placement', 'xing' ),
						"desc" => __( 'Select a global sidebar placement for blog, archives, author, single, etc.', 'xing' ),
						"id" => $shortname."_sb_pos",
						"std" => "right",
						"type" => "select",
						"options" => array("right", "left")),

				array(	"name" => __( 'Contact e-mail:', 'xing' ),
						"desc" => __( 'Enter the e-mail address to which mail should be received from contact page.', 'xing' ),
						"id" => $shortname."_email",
						"std" => "saagar_1982@yahoo.com",
						"type" => "text"),
						
				array(	"name" => __( 'Google map code for Contact Page:', 'xing' ),
						"desc" => __( 'Visit maps.google.com and copy your map location iFrame code. Paste it here. This will be shown on contact page template. Recommended dimensions for iframe <code>width="100%" height="320px"</code>', 'xing' ),
						"id" => $shortname."_google_map",
						"std" => "",
						"type" => "textarea"),
						
				array(	"name" => __( 'Mail Sent Message:', 'xing' ),
						"desc" => __( 'Enter a message that should be displayed when the mail is successfully sent.', 'xing' ),
						"id" => $shortname."_success_msg",
						"std" => __( '<h4>Thank You! Your message has been sent.</h4>', 'xing' ),
						"type" => "textarea"),												

				array(	"name" => __( 'Custom Footer Text (Left):', 'xing' ),
						"desc" => __( 'Enter custom text for left side of the footer. You can use <code>HTML</code> here.', 'xing' ),
						"id" => $shortname."_footer_left",
						"std" => "&copy; 2012 CompanyName. All rights reserved.",
						"type" => "textarea"),

				array(	"name" => __( 'Custom Footer Text (Right):', 'xing' ),
						"desc" => __( 'Enter custom text for right side of the footer. You can use <code>HTML</code> here.', 'xing' ),
						"id" => $shortname."_footer_right",
						"std" => "Some other credits here.",
						"type" => "textarea"),
						
				array(  "name" => __( 'Hide Breadcrumbs', 'xing' ),
						"desc" => __( 'Check to hide Breadcrumbs permanently.', 'xing' ),
						"id" => $shortname."_hide_crumbs",
						"type" => "checkbox",
						"std" => false),						

				array(  "name" => __( 'Hide Secondary Widget Area', 'xing' ),
						"desc" => __( 'Check to hide secondary widget area on archives, category, search, author etc. You can control individual setting for Pages and Posts inside their options panel.', 'xing' ),
						"id" => $shortname."_hide_secondary",
						"type" => "checkbox",
						"std" => false),

				array(  "name" => __( 'Disable responsive.css file', 'xing' ),
						"desc" => __( 'Check to disable responsive.css file. Located as <code>xing/responsive.css</code>', 'xing' ),
						"id" => $shortname."_disable_resp_css",
						"type" => "checkbox",
						"std" => false),
						
				array(  "name" => __( 'Disable user.css file', 'xing' ),
						"desc" => __( 'Check to disable user.css file. Located as <code>xing/user.css</code> This file can be used to add your custom styles without modifying actual style.css file.', 'xing' ),
						"id" => $shortname."_disable_user_css",
						"type" => "checkbox",
						"std" => false),						

				array(	"type" => "tabbed_end" ),

				array(	"type" => "tabbed_start",
						"id" => $shortname."_header_area" ),
						
				array(	"name" => __( 'Header Settings', 'xing' ),
						"type" => "subheading" ),						
						
				array(	"name" => __( 'Custom head markup:', 'xing' ),
						"desc" => __( 'Use this section for inserting any custom CSS or script markup inside head node of the site. For example, Google Analytics code, Google font CSS, or custom scripts.', 'xing' ),
						"id" => $shortname."_custom_head_code",
						"std" => "",
						"type" => "textarea"),						
						
				array(  "name" => __( 'Disable Top Navigation Bar ', 'xing' ),
						"desc" => __( 'Check to disable top navigation bar.', 'xing' ),
						"id" => $shortname."_top_bar_hide",
						"type" => "checkbox",
						"std" => false),
						
				array(	"name" => __( 'Top-right Callout Text:', 'xing' ),
						"desc" => __( 'Enter custom text that should appear on right side of top navigation bar.', 'xing' ),
						"id" => $shortname."_cb_top_text",
						"std" => "Avail up to 70% discounts with our <a href=\"#\"><b>Coupon</b></a> and <a href=\"#\"><b>Affiliate</b></a> programs",
						"type" => "textarea"),						

				array(  "name" => __( 'Disable Header Callout Bar ', 'xing' ),
						"desc" => __( 'Check to disable the Callout bar.', 'xing' ),
						"id" => $shortname."_cb_hide",
						"type" => "checkbox",
						"std" => false),

				array(	"name" => __( 'Header Callout Text:', 'xing' ),
						"desc" => __( 'Enter custom text that should appear on the Callout Bar.', 'xing' ),
						"id" => $shortname."_cb_text",
						"std" => "This optional callout text can be set inside Appearance > Xing Options > Header.",
						"type" => "textarea"),

				array(  "name" => __( 'Display Blog Name:', 'xing' ),
						"desc" => __( 'Check to display blog name and description in place of Logo.', 'xing' ),
						"id" => $shortname."_blog_name",
						"type" => "checkbox",
						"std" => false),

				array(	"name" => __( 'Custom Logo URL:', 'xing' ),
						"desc" => __( 'Enter full URL of your Logo image.', 'xing' ),
						"id" => $shortname."_logo",
						"std" => "",
						"type" => "text"),

				array(	"name" => __( 'Logo Alignment', 'xing' ),
						"desc" => __( 'Select an alignment for Logo. You can set margins inside style.css file.', 'xing' ),
						"id" => $shortname."_logo_align",
						"std" => "left",
						"type" => "select",
						"options" => array("left", "right")),
						
				array(	"type" => "tabbed_end" ),

				array(	"type" => "tabbed_start",
						"id" => $shortname."_blog" ),

				array(	"name" => __( 'Archive Settings', 'xing' ),
						"type" => "subheading" ),

				array(	"name" => __( 'Archives Template', 'xing' ),
						"desc" => __( 'Select a template for default blog and archives.', 'xing' ),
						"id" => $shortname."_archive_template",
						"std" => "grid_style",
						"type" => "select",
						"options" => array("grid_style", "list_style")),

				array(  "name" => __( 'Hide Post Meta', 'xing' ),
						"desc" => __( 'Check to hide post meta information on blog archives and single post.', 'xing' ),
						"id" => $shortname."_hide_post_meta",
						"type" => "checkbox",
						"std" => false),

				array(	"name" => __( 'Single Post Settings', 'xing' ),
						"type" => "subheading" ),

				array(  "name" => __( 'Show Author Bio:', 'xing' ),
						"desc" => __( 'Check to display Author bio on single posts.', 'xing' ),
						"id" => $shortname."_author",
						"type" => "checkbox",
						"std" => false),

				array(  "name" => __( 'Show related posts:', 'xing' ),
						"desc" => __( 'Check to display related posts on single posts.', 'xing' ),
						"id" => $shortname."_rp",
						"type" => "checkbox",
						"std" => false),

				array(	"name" => __( 'Related posts taxonomy:', 'xing' ),
						"desc" => __( 'Select a taxonomy for related posts.', 'xing' ),
						"id" => $shortname."_rp_taxonomy",
						"std" => "tags",
						"type" => "select",
						"options" => array("tags", "category")),

				array(	"name" => __( 'Related posts display style:', 'xing' ),
						"desc" => __( 'Select a display style for related posts.', 'xing' ),
						"id" => $shortname."_rp_style",
						"std" => "thumbnail",
						"type" => "select",
						"options" => array("thumbnail", "list")),

				array(  "name" => __( 'Hide Tag List', 'xing' ),
						"desc" => __( 'Check to hide tag list on Single Posts.', 'xing' ),
						"id" => $shortname."_hide_tags",
						"type" => "checkbox",
						"std" => false),

				array(	"name" => __( 'Social Sharing Button Settings', 'xing' ),
						"type" => "subheading" ),

				array(  "name" => __( 'Show Social Sharing Buttons:', 'xing' ),
						"desc" => __( 'Check to display social sharing on single posts.', 'xing' ),
						"id" => $shortname."_ss_sharing",
						"type" => "checkbox",
						"std" => false),

				array(	"name" => __( 'Social Sharing Heading', 'xing' ),
						"desc" => __( 'Enter a heading for sharing buttons. Example, Share this post.', 'xing' ),
						"id" => $shortname."_ss_sharing_heading",
						"std" => "",
						"type" => "text"),

				array(  "name" => __( 'Facebook:', 'xing' ),
						"desc" => __( 'Check to display Facebook Like button.', 'xing' ),
						"id" => $shortname."_ss_fb",
						"type" => "checkbox",
						"std" => false),

				array(  "name" => __( 'Twitter:', 'xing' ),
						"desc" => __( 'Check to display Twitter button.', 'xing' ),
						"id" => $shortname."_ss_tw",
						"type" => "checkbox",
						"std" => false),

				array(	"name" => __( 'Twitter Username (optional)', 'xing' ),
						"desc" => __( 'Write your twitter username without @', 'xing' ),
						"id" => $shortname."_ss_tw_usrname",
						"std" => "",
						"type" => "text"),

				array(  "name" => __( 'Google Plus:', 'xing' ),
						"desc" => __( 'Check to display Google Plus button.', 'xing' ),
						"id" => $shortname."_ss_gp",
						"type" => "checkbox",
						"std" => false),

				array(  "name" => __( 'Pinterest:', 'xing' ),
						"desc" => __( 'Check to display Pinterest button.', 'xing' ),
						"id" => $shortname."_ss_pint",
						"type" => "checkbox",
						"std" => false),

				array(  "name" => __( 'LinkedIn:', 'xing' ),
						"desc" => __( 'Check to display LinkedIn button.', 'xing' ),
						"id" => $shortname."_ss_ln",
						"type" => "checkbox",
						"std" => false),

				array(	"name" => __( 'Global Single Post Advertisements', 'xing' ),
						"type" => "subheading" ),

				array(	"name" => __( 'Advertisement above the post', 'xing' ),
						"desc" => __( 'Enter custom HTML or advertisement code that should appear above the post. Short codes are supported. The markup used here will apply to all posts globally.<br/>You can override or hide this ad on individual posts.', 'xing' ),
						"id" => $shortname."_ad_above",
						"std" => "",
						"type" => "textarea"),

				array(	"name" => __( 'Advertisement below the post', 'xing' ),
						"desc" => __( 'Enter custom HTML or advertisement code that should appear below the post. Short codes are supported. The markup used here will apply to all posts globally.<br/>You can override or hide this ad on individual posts.', 'xing' ),
						"id" => $shortname."_ad_below",
						"std" => "",
						"type" => "textarea"),

				array(	"type" => "tabbed_end" ),
				array(	"type" => "wrap_end" )
);

function mytheme_add_admin() {
	global $themename, $shortname, $options;
		if ( isset($_GET['page']) && ($_GET['page'] == basename(__FILE__)) ) {
		 if ( isset($_REQUEST['action']) && ('save' == $_REQUEST['action']) ) {
				foreach ($options as $value) {
				if( isset( $_REQUEST[ $value['id'] ] ) ) { update_option( $value['id'], $_REQUEST[ $value['id'] ]  ); } else { delete_option( $value['id'] ); } }
				header("Location:themes.php?page=theme-admin-options.php&saved=true");
				die;
		} else if( isset($_REQUEST['action']) && ('reset' == $_REQUEST['action'] )) {
			foreach ($options as $value) {
				delete_option( $value['id'] ); }
			header("Location:themes.php?page=theme-admin-options.php&reset=true");
			die;
		}
	}
	$hookname = add_theme_page($themename." Options", "".$themename." Options", 'edit_theme_options', basename(__FILE__), 'mytheme_admin');
	add_action('admin_print_scripts-'.$hookname, 'mytheme_admin_scripts');
}
function mytheme_admin_scripts(){
	global $themename, $shortname, $options;
	// Load admin styling files.
	$file_dir = get_template_directory_uri();
	wp_enqueue_style("theme-admin-css", $file_dir."/css/admin.css", false, "1.0", "all");
	wp_enqueue_script("theme-admin-js", $file_dir."/js/admin.js", false, "1.0");
}
function mytheme_admin() {
    global $themename, $shortname, $options;
    if ( isset($_REQUEST['saved']) && $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p>'.$themename.' settings saved.</p></div>';
    if ( isset($_REQUEST['reset']) && $_REQUEST['reset'] ) echo '<div id="message" class="updated fade"><p>'.$themename.' settings reset.</p></div>'; ?>
<div class="wrap">
<div id="icon-themes" class="icon32"></div>
    <h2><?php echo $themename; ?> settings</h2>
    <form method="post">
		<?php foreach ($options as $value) {
            switch ( $value['type'] ) {

                case "wrap_start": ?>
                <div class="ss_wrap">
                <?php break;

                case "wrap_end": ?>
                </div>
                <?php break;

                case "tabs_start": ?>
                <ul class="tabs">
                <?php break;

                case "tabs_end": ?>
                </ul>
                <?php break;

                case "tabbed_start": ?>
                <div class="tabbed" id="<?php echo $value['id']; ?>">
                <?php break;

                case "tabbed_end": ?>
                </div>
                <?php break;

                case "heading": ?>
                <li><a href="#<?php echo $value['id']; ?>"><?php echo $value['name']; ?></a></li>
                <?php break;

                case "subheading": ?>
                <div class="subheading"><?php echo $value['name']; ?></div>
                <?php break;

                case 'select': ?>
                <ul class="item_row">
                    <li class="left_col"><?php echo $value['name']; ?></li>
                    <li class="mid_col">
                        <select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
                            <?php foreach ($value['options'] as $option) { ?>
                            <option <?php if ( get_option( $value['id'] ) == $option) { echo ' selected="selected"'; } elseif ($option == $value['std']) { echo ' selected="selected"'; } ?>><?php echo $option; ?></option>
                            <?php } ?>
                        </select>
                    </li>
                    <li class="right_col">
                        <small><?php echo $value['desc']; ?></small>
                    </li>
                </ul>
                <?php break;

                case 'text':
                ?>
                <ul class="item_row">
                    <li class="left_col"><?php echo $value['name']; ?></li>
                    <li class="mid_col">
                        <input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_option( $value['id'] ) != "") { echo get_option( $value['id'] ); } else { echo $value['std']; } ?>" />
                    </li>
                    <li class="right_col">
                        <small><?php echo $value['desc']; ?></small>
                    </li>
                </ul>
                <?php break;

				case 'color_text':
                ?>
                <ul class="item_row">
                    <li class="left_col"><?php echo $value['name']; ?></li>
                    <li class="mid_col">
                        <input class="mycolor" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="text" value="<?php if ( get_option( $value['id'] ) != "") { echo get_option( $value['id'] ); } else { echo $value['std']; } ?>" />
                    <div id="pick_ico_<?php echo $value['id']; ?>" class="picker_ico">
                      <div></div>
                    </div>
                    </li>
                    <li class="right_col">
                        <small><?php echo $value['desc']; ?></small>
                    </li>
                </ul>

                <?php break;
                case 'textarea':
                ?>
                <ul class="item_row">
                    <li class="left_col"><?php echo $value['name']; ?></li>
                    <li class="mid_col">
                        <textarea class="code" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" cols="30" rows="6"><?php if ( get_option( $value['id'] ) != "") { echo stripslashes(get_option( $value['id'] )); } else { echo $value['std'];} ?></textarea>
                    </li>
                    <li class="right_col">
                        <small><?php echo $value['desc']; ?></small>
                    </li>
                </ul>
                <?php break;

                case "checkbox":
                ?>
                <ul class="item_row">
                    <li class="left_col"><?php echo $value['name']; ?></li>
                    <li class="mid_col">
                        <?php if(get_option($value['id'])){ $checked = "checked=\"checked\""; }else{ $checked = ""; } ?>
                        <input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="true" <?php echo $checked; ?> />
                    </li>
                    <li class="right_col">
                        <small><?php echo $value['desc']; ?></small>
                    </li>
                </ul>
                <?php break;
                }
            }
            ?>
            <p class="submit">
            <input name="save" type="submit" value="Save changes" />
            <input type="hidden" name="action" value="save" />
            </p>
    </form>
    <form method="post">
        <p class="submit">
        <input name="reset" type="submit" value="Reset all settings" />
        <input type="hidden" name="action" value="reset" />
        </p>
    </form>
</div>
<?php }
add_action('admin_menu', 'mytheme_add_admin'); ?>