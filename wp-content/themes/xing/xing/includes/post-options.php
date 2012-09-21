<?php
/* Post options */

$post_opts_key = "post_options";
$meta_boxes = array(
	"info1" => array( "type" => "heading",
		"description" => __( 'Sidebar Options', 'xing' ) ),

	"sb_usage" => array(
		"id" => "sb_usage",
		"title" => __( 'Available widget areas for Sidebar', 'xing' ),
		"type" => "custom_select_a",
		"description" => __( 'Select a widget area to use on Sidebar', 'xing' ) ),

	"hwa_usage" => array(
		"id" => "hwa_usage",
		"title" => __( 'Available widget areas for Header Section', 'xing' ),
		"type" => "custom_select_h",
		"description" => __( 'Select a widget area to use on Header Section', 'xing' ) ),

	"hr1" => array(	"type" => "hr"),
	"info2" => array( "type" => "heading",
		"description" => __( 'Post Format Options', 'xing' ) ),

	"pf_video" => array(
		"id" => "pf_video",
		"title" => __( 'Video URL', 'xing' ),
		"type" => "text",
		"description" => __( 'URL of video for Video Post Format. Example: http://vimeo.com/41369274', 'xing' ) ),

	"pf_audio" => array(
		"id" => "pf_audio",
		"title" => __( 'Audio URL', 'xing' ),
		"type" => "text",
		"description" => __( 'URL of audio for Audio Post Format. Allowed extensions are mp3, mp4, ogg. Example: http://mysite.com/track1.mp3', 'xing' ) ),

	"pf_audio_type" => array(
		"id" => "pf_audio_type",
		"title" => __( 'Select an Audio Format', 'xing' ),
		"std" => "oga",
		"type" => "select",
		"options" => array("oga", "mp3", "m4a"),
		"description" => __( 'Select appropriate format for the supplied audio URL.', 'xing' ) ),

	"hr2" => array(	"type" => "hr"),

	"info3" => array( "type" => "heading",
		"description" => __( 'Single Post Options', 'xing' ) ),

	"hide_meta" => array(
		"id" => "hide_meta",
		"title" => __( 'Check to hide post meta on single post view.', 'xing' ),
		"type" => "checkbox"),

		"hide_secondary" => array(
		"id" => "hide_secondary",
		"title" => __( 'Check to hide secondary widget area on this post.', 'walk' ),
		"type" => "checkbox"),

	"hr3" => array(	"type" => "hr"),
	"info4" => array( "type" => "heading",
		"description" => __( 'Advertisement Settings for this post', 'xing' ) ),

	"ad_above" => array(
		"id" => "ad_above",
		"title" => __( 'Custom markup before the post', 'xing' ),
		"std" => '',
		"type" => "textarea",
		"description" => __( 'Enter an HTML markup or advertisement code that should appear above the post. (Short codes are supported).', 'xing' ) ),

	"ad_below" => array(
		"id" => "ad_below",
		"title" => __( 'Custom markup after the post', 'xing' ),
		"std" => '',
		"type" => "textarea",
		"description" => __( 'Enter an HTML markup or advertisement code that should appear after the post, below related posts section. (Short codes are supported).', 'xing' ) ),

	"ad_above_check" => array(
		"id" => "ad_above_check",
		"title" => __( 'Check to hide advertisement above this post.', 'xing' ),
		"type" => "checkbox"),

	"ad_below_check" => array(
		"id" => "ad_below_check",
		"title" => __( 'Check to hide advertisement below this post.', 'xing' ),
		"type" => "checkbox"),

);

function create_meta_box() {
	global $post_opts_key;
	if( function_exists( 'add_meta_box' ) ) {
		add_meta_box( 'new-meta-boxes', 'Post Options', 'display_meta_box', 'post', 'normal', 'high' );
	}
}

function display_meta_box() {
	global $post, $meta_boxes, $post_opts_key; ?>
	<div class="form-wrap">
		<?php wp_nonce_field( plugin_basename( __FILE__ ), $post_opts_key . '_wpnonce', false, true );
        foreach($meta_boxes as $meta_box) {
			$data = get_post_meta($post->ID, $post_opts_key, true);
			if( $meta_box[ 'type' ] == "heading" ) {
				echo ('<h4 style="font-size:16px; font-weight:bold; margin-bottom:12px">'.$meta_box[ 'description' ].'</h4>');
			}
			elseif ( $meta_box[ 'type' ] == "text" ) {?>
                <div>
                    <label for="<?php echo $meta_box[ 'id' ]; ?>"><?php echo $meta_box[ 'title' ]; ?></label>
                    <input style="width:100%" type="text" name="<?php if (isset($meta_box['id'])) echo $meta_box['id']; ?>" id="<?php if (isset($meta_box['id'])) echo $meta_box[ 'id' ]; ?>" value="<?php if (isset($data[ $meta_box[ 'id' ] ])) echo htmlspecialchars( $data[ $meta_box[ 'id' ] ] ); ?>" />
                    <p><?php if(isset($meta_box[ 'description' ])) echo $meta_box[ 'description' ]; ?></p>
                </div>
			<?php }
			elseif ( $meta_box[ 'type' ] == "textarea" ) {?>
                <div>
                    <label for="<?php echo $meta_box[ 'id' ]; ?>"><?php echo $meta_box[ 'title' ]; ?></label>
                    <textarea class="code" style="width:100%" name="<?php if( isset($meta_box['id']) ) echo $meta_box[ 'id' ]; ?>" cols="40" rows="6"><?php if( isset($data[ $meta_box['id'] ]) ) echo stripslashes( $data[ $meta_box[ 'id' ] ] ); else { if(isset($meta_box[ 'std' ])) echo stripslashes( $meta_box[ 'std' ] ); } ?></textarea>
                    <p><?php if(isset($meta_box[ 'description' ])) echo $meta_box[ 'description' ]; ?></p>
                </div>
			<?php }
            elseif ( $meta_box[ 'type' ] == "select" ) {?>
                <div>
                    <label for="<?php echo $meta_box[ 'id' ]; ?>"><?php echo $meta_box[ 'title' ]; ?></label>
                    <select style="width:100%" name="<?php echo $meta_box['id']; ?>" id="<?php echo $meta_box['id']; ?>">
                    <?php foreach ($meta_box['options'] as $option) { ?>
                    <option <?php if ( isset($data[ $meta_box[ 'id' ] ]) && ( $data[ $meta_box[ 'id' ] ] == $option ) ) { echo ' selected="selected"'; } elseif ($option == $meta_box['std']) { echo ' selected="selected"'; } ?>><?php echo $option; ?></option>
                    <?php } ?>
                    </select>
                    <p><?php if(isset($meta_box[ 'description' ])) echo $meta_box[ 'description' ]; ?></p>
                </div>
            <?php }
            elseif ( $meta_box[ 'type' ] == "custom_select_a" ) {?>
                <div>
                    <label for="<?php echo $meta_box[ 'id' ]; ?>"><?php echo $meta_box[ 'title' ]; ?></label>
                    <select style="width:100%" name="<?php echo $meta_box['id']; ?>" id="<?php echo $meta_box['id']; ?>">
                    <?php
                    global $wp_registered_sidebars;
                    $current_sidebars = $wp_registered_sidebars;
                    if(is_array($current_sidebars) && !empty($current_sidebars)){
                        foreach($current_sidebars as $sidebar){
                            if( $sidebar['description'] == "Sidebar"  ) { ?>
                            <option <?php if ( isset($data[ $meta_box[ 'id' ] ]) && ( $data[ $meta_box[ 'id' ] ] == $sidebar['id'] ) ) { echo ' selected="selected"'; } ?> value="<?php echo $sidebar['id']; ?>"><?php echo $sidebar['name']; ?></option><?php } //sec
                        }
                    }?>
                    </select>
                    <p><?php if(isset($meta_box[ 'description' ])) echo $meta_box[ 'description' ]; ?></p>
                </div>
            <?php }
            elseif ( $meta_box[ 'type' ] == "custom_select_h" ) {?>
                <div>
                    <label for="<?php echo $meta_box[ 'id' ]; ?>"><?php echo $meta_box[ 'title' ]; ?></label>
                    <select style="width:100%" name="<?php echo $meta_box['id']; ?>" id="<?php echo $meta_box['id']; ?>">
                    <?php
                    global $wp_registered_sidebars;
                    $current_sidebars = $wp_registered_sidebars;
                    if(is_array($current_sidebars) && !empty($current_sidebars)){
                        foreach($current_sidebars as $sidebar){
                            if( $sidebar['description'] == "Header Bar" ) { ?>
                            <option <?php if ( isset($data[ $meta_box[ 'id' ] ]) && ( $data[ $meta_box[ 'id' ] ] == $sidebar['id'] ) ) { echo ' selected="selected"'; } ?> value="<?php echo $sidebar['id']; ?>"><?php echo $sidebar['name']; ?></option><?php } //sec
                        }
                    }?>
                    </select>
                    <p><?php if(isset($meta_box[ 'description' ])) echo $meta_box[ 'description' ]; ?></p>
                </div>
            <?php }
			elseif ($meta_box[ 'type' ] == "checkbox" ) {?>
                <div>
					<?php if(isset($data[$meta_box['id']]) && $data[$meta_box['id']]){ $checked = "checked=\"checked\""; }else{ $checked = ""; } ?>
                    <input style="float:left; width:20px" type="checkbox" name="<?php echo $meta_box['id']; ?>" id="<?php echo $meta_box['id']; ?>" value="true" <?php echo $checked; ?> />
                    <label for="<?php echo $meta_box[ 'id' ]; ?>"><?php echo $meta_box[ 'title' ]; ?></label>
                    <p><?php if(isset($meta_box[ 'description' ])) echo $meta_box[ 'description' ]; ?></p>
                </div>
			<?php }
			elseif( $meta_box[ 'type' ] == "hr" ) {
			echo ('<div style="border-bottom:1px solid #ccc; margin:30px 0px 12px"></div>');
			}
        } ?>
	</div>
	<?php }

function save_post_options( $post_id ) {
	global $post, $meta_boxes, $post_opts_key;

	foreach( $meta_boxes as $meta_box ) {
		if(isset ($meta_box['id']) && isset($_POST[ $meta_box['id'] ]))
		$data[ $meta_box['id'] ] = $_POST[ $meta_box['id'] ];
	}

	if (isset($_POST[ $post_opts_key . '_wpnonce' ]))
		if ( !wp_verify_nonce( $_POST[ $post_opts_key . '_wpnonce' ], plugin_basename(__FILE__) ) )
			return $post_id;

	if ( !current_user_can( 'edit_post', $post_id ))
		return $post_id;

	if(isset($data))
		update_post_meta( $post_id, $post_opts_key, $data );
}

add_action( 'admin_menu', 'create_meta_box' );
add_action( 'save_post', 'save_post_options' ); ?>