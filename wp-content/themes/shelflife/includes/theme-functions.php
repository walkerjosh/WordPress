<?php

/*-----------------------------------------------------------------------------------

TABLE OF CONTENTS

- Make sure the homepage blog posts query has the correct posts limit. (WP 3.4)
- Exclude categories from displaying on the "Blog" page template.
- Exclude categories from displaying on the homepage.
- Register WP Menus
- Page navigation
- Post Meta
- Subscribe & Connect
- Comment Form Fields
- Comment Form Settings
- Archive Description
- WooPagination markup
- Thickbox Styles
- Custom Post Type - Promotion
- Custom Post Type - Mini-Features
- Custom Post Type - Change Title Placeholder Texts

-----------------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------------------*/
/* Make sure the homepage blog posts query has the correct posts limit. (WP 3.4) */
/*-----------------------------------------------------------------------------------*/

add_filter( 'pre_get_posts', 'woo_filter_homepage_blog_query' );

function woo_filter_homepage_blog_query ( $query ) {
	if ( $query->is_home() && $query->is_main_query() ) {
		$settings = array(
						'posts_limit' => get_option( 'posts_per_page' )
						);
						
		$settings = woo_get_dynamic_values( $settings );

		$query->set( 'posts_per_page', intval( $settings['posts_limit'] ) );
	}
	return $query;
} // End woo_filter_homepage_blog_query()

/*-----------------------------------------------------------------------------------*/
/* Exclude categories from displaying on the "Blog" page template.
/*-----------------------------------------------------------------------------------*/

// Exclude categories on the "Blog" page template.
add_filter( 'woo_blog_template_query_args', 'woo_exclude_categories_blogtemplate' );

function woo_exclude_categories_blogtemplate ( $args ) {

	if ( ! function_exists( 'woo_prepare_category_ids_from_option' ) ) { return $args; }

	$excluded_cats = array();

	// Process the category data and convert all categories to IDs.
	$excluded_cats = woo_prepare_category_ids_from_option( 'woo_exclude_cats_blog' );

	// Homepage logic.
	if ( count( $excluded_cats ) > 0 ) {

		// Setup the categories as a string, because "category__not_in" doesn't seem to work
		// when using query_posts().

		foreach ( $excluded_cats as $k => $v ) { $excluded_cats[$k] = '-' . $v; }
		$cats = join( ',', $excluded_cats );

		$args['cat'] = $cats;
	}

	return $args;

} // End woo_exclude_categories_blogtemplate()

/*-----------------------------------------------------------------------------------*/
/* Exclude categories from displaying on the homepage.
/*-----------------------------------------------------------------------------------*/

// Exclude categories on the homepage.
add_filter( 'pre_get_posts', 'woo_exclude_categories_homepage' );

function woo_exclude_categories_homepage ( $query ) {

	if ( ! function_exists( 'woo_prepare_category_ids_from_option' ) ) { return $query; }

	$excluded_cats = array();

	// Process the category data and convert all categories to IDs.
	$excluded_cats = woo_prepare_category_ids_from_option( 'woo_exclude_cats_home' );

	// Homepage logic.
	if ( is_home() && ( count( $excluded_cats ) > 0 ) ) {
		$query->set( 'category__not_in', $excluded_cats );
	}

	$query->parse_query();

	return $query;

} // End woo_exclude_categories_homepage()

/*-----------------------------------------------------------------------------------*/
/* Register WP Menus */
/*-----------------------------------------------------------------------------------*/
if ( function_exists( 'wp_nav_menu') ) {
	add_theme_support( 'nav-menus' );
	register_nav_menus( array( 'primary-menu' => __( 'Primary Menu', 'woothemes' ) ) );
	register_nav_menus( array( 'top-menu' => __( 'Top Menu', 'woothemes' ) ) );
}


/*-----------------------------------------------------------------------------------*/
/* Page navigation */
/*-----------------------------------------------------------------------------------*/
if (!function_exists( 'woo_pagenav')) {
	function woo_pagenav() {

		global $woo_options;

		// If the user has set the option to use simple paging links, display those. By default, display the pagination.
		if ( array_key_exists( 'woo_pagination_type', $woo_options ) && $woo_options[ 'woo_pagination_type' ] == 'simple' ) {
			if ( get_next_posts_link() || get_previous_posts_link() ) {
		?>
            <nav class="nav-entries fix">
                <?php next_posts_link( '<span class="nav-prev fl">'. __( '<span class="meta-nav">&larr;</span> Older posts', 'woothemes' ) . '</span>' ); ?>
                <?php previous_posts_link( '<span class="nav-next fr">'. __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'woothemes' ) . '</span>' ); ?>
            </nav>
		<?php
			}
		} else {
			woo_pagination();

		} // End IF Statement

	} // End woo_pagenav()
} // End IF Statement

/*-----------------------------------------------------------------------------------*/
/* WooTabs - Popular Posts */
/*-----------------------------------------------------------------------------------*/
if (!function_exists( 'woo_tabs_popular')) {
	function woo_tabs_popular( $posts = 5, $size = 45 ) {
		global $post;
		$popular = get_posts( 'caller_get_posts=1&orderby=comment_count&showposts='.$posts);
		foreach($popular as $post) :
			setup_postdata($post);
	?>
	<li class="fix">
		<?php if ($size <> 0) woo_image( 'height='.$size.'&width='.$size.'&class=thumbnail&single=true' ); ?>
		<a title="<?php the_title(); ?>" href="<?php the_permalink() ?>"><?php the_title(); ?></a>
		<span class="meta"><?php the_time( get_option( 'date_format' ) ); ?></span>
	</li>
	<?php endforeach;
	}
}


/*-----------------------------------------------------------------------------------*/
/* Post Meta */
/*-----------------------------------------------------------------------------------*/

if (!function_exists( 'woo_post_meta')) {
	function woo_post_meta( ) {
?>
<aside class="post-meta">
	<ul>
		<li class="post-date">
			<span class="small"><?php _e( 'Posted on', 'woothemes' ) ?></span>
			<?php the_time( get_option( 'date_format' ) ); ?>
		</li>
		<li class="post-author">
			<span class="small"><?php _e( 'by', 'woothemes' ) ?></span>
			<?php the_author_posts_link(); ?>
		</li>
		<li class="post-category">
			<span class="small"><?php _e( 'in', 'woothemes' ) ?></span>
			<?php the_category( ', ') ?>
		</li>
		<?php edit_post_link( __( '{ Edit }', 'woothemes' ), '<li class="edit">', '</li>' ); ?>
	</ul>
</aside>
<?php
	}
}


/*-----------------------------------------------------------------------------------*/
/* Subscribe / Connect */
/*-----------------------------------------------------------------------------------*/

if (!function_exists( 'woo_subscribe_connect')) {
	function woo_subscribe_connect($widget = 'false', $title = '', $form = '', $social = '') {

		//Setup default variables, overriding them if the "Theme Options" have been saved.
		$settings = array(
						'connect' => 'false', 
						'connect_title' => __('Subscribe' , 'woothemes'), 
						'connect_related' => 'true', 
						'connect_content' => __( 'Subscribe to our e-mail newsletter to receive updates.', 'woothemes' ),
						'connect_newsletter_id' => '', 
						'connect_mailchimp_list_url' => '',
						'feed_url' => '',
						'connect_rss' => '',
						'connect_twitter' => '',
						'connect_facebook' => '',
						'connect_youtube' => '',
						'connect_flickr' => '',
						'connect_linkedin' => '',
						'connect_delicious' => '',
						'connect_rss' => '',
						'connect_googleplus' => ''
						);
		$settings = woo_get_dynamic_values( $settings );

		// Setup title
		if ( $widget != 'true' )
			$title = $settings[ 'connect_title' ];

		// Setup related post (not in widget)
		$related_posts = '';
		if ( $settings[ 'connect_related' ] == "true" AND $widget != "true" )
			$related_posts = do_shortcode( '[related_posts limit="5"]' );

?>
	<?php if ( $settings[ 'connect' ] == "true" OR $widget == 'true' ) : ?>
	<aside id="connect" class="fix">
		<h3><?php if ( $title ) echo apply_filters( 'widget_title', $title ); else _e('Subscribe','woothemes'); ?></h3>

		<div <?php if ( $related_posts != '' ) echo 'class="col-left"'; ?>>
			<p><?php if ($settings[ 'connect_content' ] != '') echo stripslashes($settings[ 'connect_content' ]); ?></p>

			<?php if ( $settings[ 'connect_newsletter_id' ] != "" AND $form != 'on' ) : ?>
			<form class="newsletter-form<?php if ( $related_posts == '' ) echo ' fl'; ?>" action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open( 'http://feedburner.google.com/fb/a/mailverify?uri=<?php echo $settings[ 'connect_newsletter_id' ]; ?>', 'popupwindow', 'scrollbars=yes,width=550,height=520' );return true">
				<input class="email" type="text" name="email" value="<?php esc_attr_e( 'E-mail', 'woothemes' ); ?>" onfocus="if (this.value == '<?php _e( 'E-mail', 'woothemes' ); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e( 'E-mail', 'woothemes' ); ?>';}" />
				<input type="hidden" value="<?php echo $settings[ 'connect_newsletter_id' ]; ?>" name="uri"/>
				<input type="hidden" value="<?php bloginfo( 'name' ); ?>" name="title"/>
				<input type="hidden" name="loc" value="en_US"/>
				<input class="submit" type="submit" name="submit" value="<?php _e( 'Submit', 'woothemes' ); ?>" />
			</form>
			<?php endif; ?>

			<?php if ( $settings['connect_mailchimp_list_url'] != "" AND $form != 'on' AND $settings['connect_newsletter_id'] == "" ) : ?>
			<!-- Begin MailChimp Signup Form -->
			<div id="mc_embed_signup">
				<form class="newsletter-form<?php if ( $related_posts == '' ) echo ' fl'; ?>" action="<?php echo $settings['connect_mailchimp_list_url']; ?>" method="post" target="popupwindow" onsubmit="window.open('<?php echo $settings['connect_mailchimp_list_url']; ?>', 'popupwindow', 'scrollbars=yes,width=650,height=520');return true">
					<input type="text" name="EMAIL" class="required email" value="<?php _e('E-mail','woothemes'); ?>"  id="mce-EMAIL" onfocus="if (this.value == '<?php _e('E-mail','woothemes'); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e('E-mail','woothemes'); ?>';}">
					<input type="submit" value="<?php _e('Submit', 'woothemes'); ?>" name="subscribe" id="mc-embedded-subscribe" class="btn submit button">
				</form>
			</div>
			<!--End mc_embed_signup-->
			<?php endif; ?>

			<?php if ( $social != 'on' ) : ?>
			<div class="social<?php if ( $related_posts == '' AND $settings['connect_newsletter_id' ] != "" ) echo ' fr'; ?>">
		   		<?php if ( $settings['connect_rss' ] == "true" ) { ?>
		   		<a href="<?php if ( $settings['feed_url'] ) { echo esc_url( $settings['feed_url'] ); } else { echo get_bloginfo_rss('rss2_url'); } ?>" class="subscribe" title="RSS"></a>

		   		<?php } if ( $settings['connect_twitter' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $settings['connect_twitter'] ); ?>" class="twitter" title="Twitter"></a>

		   		<?php } if ( $settings['connect_facebook' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $settings['connect_facebook'] ); ?>" class="facebook" title="Facebook"></a>

		   		<?php } if ( $settings['connect_youtube' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $settings['connect_youtube'] ); ?>" class="youtube" title="YouTube"></a>

		   		<?php } if ( $settings['connect_flickr' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $settings['connect_flickr'] ); ?>" class="flickr" title="Flickr"></a>

		   		<?php } if ( $settings['connect_linkedin' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $settings['connect_linkedin'] ); ?>" class="linkedin" title="LinkedIn"></a>

		   		<?php } if ( $settings['connect_delicious' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $settings['connect_delicious'] ); ?>" class="delicious" title="Delicious"></a>

		   		<?php } if ( $settings['connect_googleplus' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $settings['connect_googleplus'] ); ?>" class="googleplus" title="Google+"></a>

				<?php } ?>
			</div>
			<?php endif; ?>

		</div><!-- col-left -->

		<?php if ( $settings['connect_related' ] == "true" AND $related_posts != '' ) : ?>
		<div class="related-posts col-right">
			<h4><?php _e( 'Related Posts:', 'woothemes' ); ?></h4>
			<?php echo $related_posts; ?>
		</div><!-- col-right -->
		<?php wp_reset_query(); endif; ?>

	</aside>
	<?php endif; ?>
<?php
	}
}

/*-----------------------------------------------------------------------------------*/
/* Comment Form Fields */
/*-----------------------------------------------------------------------------------*/

	add_filter( 'comment_form_default_fields', 'woo_comment_form_fields' );

	if ( ! function_exists( 'woo_comment_form_fields' ) ) {
		function woo_comment_form_fields ( $fields ) {

			$commenter = wp_get_current_commenter();

			$required_text = ' <span class="required">(' . __( 'Required', 'woothemes' ) . ')</span>';

			$req = get_option( 'require_name_email' );
			$aria_req = ( $req ? " aria-required='true'" : '' );
			$fields =  array(
				'author' => '<p class="comment-form-author">' .
							'<input id="author" class="txt" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' />' .
							'<label for="author">' . __( 'Name' ) . ( $req ? $required_text : '' ) . '</label> ' .
							'</p>',
				'email'  => '<p class="comment-form-email">' .
				            '<input id="email" class="txt" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' />' .
				            '<label for="email">' . __( 'Email' ) . ( $req ? $required_text : '' ) . '</label> ' .
				            '</p>',
				'url'    => '<p class="comment-form-url">' .
				            '<input id="url" class="txt" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" />' .
				            '<label for="url">' . __( 'Website' ) . '</label>' .
				            '</p>',
			);

			return $fields;

		} // End woo_comment_form_fields()
	}

/*-----------------------------------------------------------------------------------*/
/* Comment Form Settings */
/*-----------------------------------------------------------------------------------*/

	add_filter( 'comment_form_defaults', 'woo_comment_form_settings' );

	if ( ! function_exists( 'woo_comment_form_settings' ) ) {
		function woo_comment_form_settings ( $settings ) {

			$settings['comment_notes_before'] = '';
			$settings['comment_notes_after'] = '';
			$settings['label_submit'] = __( 'Submit Comment', 'woothemes' );
			$settings['cancel_reply_link'] = __( 'Click here to cancel reply.', 'woothemes' );

			return $settings;

		} // End woo_comment_form_settings()
	}

	/*-----------------------------------------------------------------------------------*/
	/* Misc back compat */
	/*-----------------------------------------------------------------------------------*/

	// array_fill_keys doesn't exist in PHP < 5.2
	// Can remove this after PHP <  5.2 support is dropped
	if ( !function_exists( 'array_fill_keys' ) ) {
		function array_fill_keys( $keys, $value ) {
			return array_combine( $keys, array_fill( 0, count( $keys ), $value ) );
		}
	}

/*-----------------------------------------------------------------------------------*/
/**
 * woo_archive_description()
 *
 * Display a description, if available, for the archive being viewed (category, tag, other taxonomy).
 *
 * @since V1.0.0
 * @uses do_atomic(), get_queried_object(), term_description()
 * @echo string
 * @filter woo_archive_description
 */

if ( ! function_exists( 'woo_archive_description' ) ) {
	function woo_archive_description ( $echo = true ) {
		do_action( 'woo_archive_description' );
		
		// Archive Description, if one is available.
		$term_obj = get_queried_object();
		$description = term_description( $term_obj->term_id, $term_obj->taxonomy );
		
		if ( $description != '' ) {
			// Allow child themes/plugins to filter here ( 1: text in DIV and paragraph, 2: term object )
			$description = apply_filters( 'woo_archive_description', '<div class="archive-description">' . $description . '</div><!--/.archive-description-->', $term_obj );
		}
		
		if ( $echo != true ) { return $description; }
		
		echo $description;
	} // End woo_archive_description()
}

/*-----------------------------------------------------------------------------------*/
/* WooPagination Markup */
/*-----------------------------------------------------------------------------------*/

add_filter( 'woo_pagination_args', 'woo_pagination_html5_markup', 2 );

function woo_pagination_html5_markup ( $args ) {
	$args['before'] = '<nav class="pagination woo-pagination">';
	$args['after'] = '</nav>';
	
	return $args;
} // End woo_pagination_html5_markup()


/*-----------------------------------------------------------------------------------*/
/* Thickbox Styles */
/*-----------------------------------------------------------------------------------*/

function thickbox_style() {
    ?>
    <link rel="stylesheet" href="<?php echo site_url(); ?>/wp-includes/js/thickbox/thickbox.css" type="text/css" media="screen" />
    <script type="text/javascript">
    	var tb_pathToImage = "<?php echo site_url(); ?>/wp-includes/js/thickbox/loadingAnimation.gif";
    	var tb_closeImage = "<?php echo site_url(); ?>/wp-includes/js/thickbox/tb-close.png"
    </script>
    <?php
}

add_action('wp_head','thickbox_style');

/*-----------------------------------------------------------------------------------*/
/* Custom Post Type - Admin Stylesheet */
/*-----------------------------------------------------------------------------------*/

if ( is_admin() ) {
	add_action( 'admin_print_styles-edit.php', 'woo_enqueue_post_type_style', 10 );
	add_action( 'admin_print_styles-post.php', 'woo_enqueue_post_type_style', 10 );
	add_action( 'admin_print_styles-post-new.php', 'woo_enqueue_post_type_style', 10 );
}

/**
 * woo_enqueue_post_type_style function.
 * 
 * @access public
 * @return void
 */
if ( ! function_exists( 'woo_enqueue_post_type_style' ) ) {
	function woo_enqueue_post_type_style() {
		wp_register_style( 'woo-post-type', get_template_directory_uri() . '/includes/css/post-type-admin.css', '1.0.0' );
		wp_enqueue_style( 'woo-post-type' );
	} // End woo_enqueue_post_type_style()
}

/*-----------------------------------------------------------------------------------*/
/* Custom Post Type - Promotion */
/*-----------------------------------------------------------------------------------*/

add_action( 'init', 'woo_add_promotions', 10 );


/**
 * woo_add_promotions function.
 * 
 * @access public
 * @return void
 */
if ( ! function_exists( 'woo_add_promotions' ) ) {
	function woo_add_promotions() {
		$token = 'promotion';
		$singular = __( 'Promotion', 'woothemes' );
		$plural = __( 'Promotions', 'woothemes' );
		$rewrite = 'promotions';
		$supports = array( 'title', 'editor', 'excerpt', 'thumbnail' );
		
		if ( $rewrite == '' ) { $rewrite = $token; }
		
		$labels = array(
			'name' => _x( 'Promotions', 'post type general name', 'woothemes' ),
			'singular_name' => _x( 'Promotion', 'post type singular name', 'woothemes' ),
			'add_new' => _x( 'Add New', 'Promotion' ),
			'add_new_item' => sprintf( __( 'Add New %s', 'woothemes' ), $singular ),
			'edit_item' => sprintf( __( 'Edit %s', 'woothemes' ), $singular ),
			'new_item' => sprintf( __( 'New %s', 'woothemes' ), $singular ),
			'all_items' => sprintf( __( 'All %s', 'woothemes' ), $plural ),
			'view_item' => sprintf( __( 'View %s', 'woothemes' ), $singular ),
			'search_items' => sprintf( __( 'Search %a', 'woothemes' ), $plural ),
			'not_found' =>  sprintf( __( 'No %s Found', 'woothemes' ), $plural ),
			'not_found_in_trash' => sprintf( __( 'No %s Found In Trash', 'woothemes' ), $plural ),
			'parent_item_colon' => '',
			'menu_name' => $plural
	
		);
		$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => $rewrite, 'with_front' => true ),
			'capability_type' => 'post',
			'has_archive' => $rewrite,
			'hierarchical' => false,
			'menu_position' => null,
			'supports' => $supports, 
			'menu_position' => 5, 
			'menu_icon' => get_template_directory_uri() . '/includes/images/icon-' . $token . '-16.png'
		);
		register_post_type( $token, $args );
	} // End woo_add_promotions()
}

/*-----------------------------------------------------------------------------------*/
/* Custom Post Type - Mini-Features */
/*-----------------------------------------------------------------------------------*/

add_action( 'init', 'woo_add_infoboxes', 10 );


/**
 * woo_add_infoboxes function.
 * 
 * @access public
 * @return void
 */
if ( ! function_exists( 'woo_add_infoboxes' ) ) { 
	function woo_add_infoboxes() {
		$token = 'infobox';
		$singular = __( 'Mini Feature', 'woothemes' );
		$plural = __( 'Mini Features', 'woothemes' );
		$rewrite = 'features';
		$supports = array( 'title', 'editor', 'thumbnail' );
		
		if ( $rewrite == '' ) { $rewrite = $token; }
		
		$labels = array(
			'name' => _x( 'Mini Features', 'post type general name', 'woothemes' ),
			'singular_name' => _x( 'Mini Feature', 'post type singular name', 'woothemes' ),
			'add_new' => _x( 'Add New', 'Mini Feature' ),
			'add_new_item' => sprintf( __( 'Add New %s', 'woothemes' ), $singular ),
			'edit_item' => sprintf( __( 'Edit %s', 'woothemes' ), $singular ),
			'new_item' => sprintf( __( 'New %s', 'woothemes' ), $singular ),
			'all_items' => sprintf( __( 'All %s', 'woothemes' ), $plural ),
			'view_item' => sprintf( __( 'View %s', 'woothemes' ), $singular ),
			'search_items' => sprintf( __( 'Search %a', 'woothemes' ), $plural ),
			'not_found' =>  sprintf( __( 'No %s Found', 'woothemes' ), $plural ),
			'not_found_in_trash' => sprintf( __( 'No %s Found In Trash', 'woothemes' ), $plural ),
			'parent_item_colon' => '',
			'menu_name' => $plural
	
		);
		$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => false,
			'show_ui' => true,
			'show_in_menu' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => $rewrite, 'with_front' => true ),
			'capability_type' => 'post',
			'has_archive' => $rewrite,
			'hierarchical' => false,
			'menu_position' => null,
			'supports' => $supports, 
			'menu_position' => 5, 
			'menu_icon' => get_template_directory_uri() . '/includes/images/icon-' . $token . '-16.png'
		);
		register_post_type( $token, $args );
	} // End woo_add_infoboxes()
}

/*-----------------------------------------------------------------------------------*/
/* Custom Post Type - Change Title Placeholder Texts */
/*-----------------------------------------------------------------------------------*/

if ( is_admin() ) {
	add_filter( 'enter_title_here', 'woo_filter_title_placeholder_text', 10 );
}

/**
 * woo_filter_title_placeholder_text function.
 * 
 * @access public
 * @param string $title
 * @return string $title
 */
if ( ! function_exists( 'woo_filter_title_placeholder_text' ) ) {
	function woo_filter_title_placeholder_text ( $title ) {
		if ( get_post_type() == 'promotion' ) {
			$title = __( 'Enter promotion title here', 'woothemes' );
		}
		
		if ( get_post_type() == 'infobox' ) {
			$title = __( 'Enter mini-feature title here', 'woothemes' );
		}
		
		return $title;
	} // End woo_filter_title_placeholder_text()
}

/*-----------------------------------------------------------------------------------*/
/* END */
/*-----------------------------------------------------------------------------------*/
?>