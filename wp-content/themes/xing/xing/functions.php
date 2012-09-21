<?php
/* Theme Functions */

// Load widgets, options and include files
require_once('includes/cats-widget.php');
require_once('includes/recent-posts-widget.php');
require_once('includes/popular-posts-widget.php');
require_once('includes/minifolio-widget.php');
require_once('includes/flickr-widget.php');
require_once('includes/social-links-widget.php');
require_once('includes/twitter-widget.php');
require_once('includes/post-options.php');
require_once('includes/page-options.php');
require_once('includes/theme-admin-options.php');
require_once('includes/shortcodes/shortcodes.php');
require_once('includes/shortcodes/visual-shortcodes.php');
require_once('includes/breadcrumbs.php');
if (class_exists( 'woocommerce' )) {
	require_once('woocommerce/woocommerce-hooks.php');
}

// Set default content width
if ( !isset( $content_width ) )
	$content_width = 980;

// Add editor styles
add_editor_style();

// Add custom background
add_theme_support( 'custom-background', array(
	'default-color' => '',
	'default-image' => ''
) );

// Add default posts and comments RSS feed links to head
add_theme_support( 'automatic-feed-links' );

// Add Post Formats
add_theme_support( 'post-formats', array( 'audio', 'gallery', 'video' ) );

// Add support for post thumbnails
add_theme_support( 'post-thumbnails' );
set_post_thumbnail_size( 800, 800 );
if ( function_exists( 'add_image_size' ) ) {
	add_image_size( 'big', 9999, 9999);
	add_image_size( 'size_242', 242, 9999 ); // Blog Grid Style
	add_image_size( 'size_242_198', 242, 198, true ); // Blog List Style
	add_image_size( 'size_254_198', 254, 198, true ); // Portfolio Templates
	add_image_size( 'size_140_90', 140, 90, true ); // Related Posts
	add_image_size( 'size_90', 90, 999 ); // Recent Posts Widget
	add_image_size( 'size_90_90', 90, 90, true ); // MiniFolio Widget
}

// Make theme available for translation
load_theme_textdomain( 'xing', get_template_directory() . '/languages' );

// Add support for wp_nav_menu()
register_nav_menus( array(
	'primary' => __( 'Primary Menu', 'xing' ),
	'secondary' => __( 'Secondary Top Menu', 'xing' )
) );

// Register Widgets and Sidebars
add_action( 'widgets_init', 'xing_widgets_init' );
if ( !function_exists( 'xing_widgets_init' ) ) :
	function xing_widgets_init() {
		register_widget('xing_Cat_Widget');
		register_widget('xing_Recent_Posts');
		register_widget('xing_Popular_Posts');
		register_widget('xing_Mini_Folio');
		register_widget('xing_Flickr_Widget');
		register_widget('xing_Social_Widget');
		register_widget('xing_Twitter_Widget');

		register_sidebar( array(
			'name' => __( 'Default Header Bar', 'xing' ),
			'id' => 'default-headerbar',
			'description' => __( 'Header Bar', 'xing' ),
			'before_widget' => '<aside id="%1$s" class="hwa_wrap %2$s">',
			'after_widget' => "</aside>",
			'before_title' => '<h3 class="hwa-title">',
			'after_title' => '</h3>',
		) );

		register_sidebar( array(
			'name' => __( 'Default Sidebar', 'xing' ),
			'id' => 'default-sidebar',
			'description' => __( 'Sidebar', 'xing' ),
			'before_widget' => '<aside id="%1$s" class="widgetwrap %2$s">',
			'after_widget' => "</aside>",
			'before_title' => '<h3 class="sb-title">',
			'after_title' => '</h3>',
		) );

		register_sidebar( array(
			'name' => __( 'Default Secondary Column 1', 'xing' ),
			'id' => 'secondary-column-1',
			'description' => __( 'Secondary Column', 'xing' ),
			'before_widget' => '<aside id="%1$s" class="widgetwrap %2$s">',
			'after_widget' => "</aside>",
			'before_title' => '<h3 class="sc-title">',
			'after_title' => '</h3>',
		) );

		register_sidebar( array(
			'name' => __( 'Default Secondary Column 2', 'xing' ),
			'id' => 'secondary-column-2',
			'description' => __( 'Secondary Column', 'xing' ),
			'before_widget' => '<aside id="%1$s" class="widgetwrap %2$s">',
			'after_widget' => "</aside>",
			'before_title' => '<h3 class="sc-title">',
			'after_title' => '</h3>',
		) );

		register_sidebar( array(
			'name' => __( 'Default Secondary Column 3', 'xing' ),
			'id' => 'secondary-column-3',
			'description' => __( 'Secondary Column', 'xing' ),
			'before_widget' => '<aside id="%1$s" class="widgetwrap %2$s">',
			'after_widget' => "</aside>",
			'before_title' => '<h3 class="sc-title">',
			'after_title' => '</h3>',
		) );

		register_sidebar( array(
			'name' => __( 'Default Secondary Column 4', 'xing' ),
			'id' => 'secondary-column-4',
			'description' => __( 'Secondary Column', 'xing' ),
			'before_widget' => '<aside id="%1$s" class="widgetwrap %2$s">',
			'after_widget' => "</aside>",
			'before_title' => '<h3 class="sc-title">',
			'after_title' => '</h3>',
		) );

		register_sidebar( array(
			'name' => __( 'Default Secondary Column 5', 'xing' ),
			'id' => 'secondary-column-5',
			'description' => __( 'Secondary Column', 'xing' ),
			'before_widget' => '<aside id="%1$s" class="widgetwrap %2$s">',
			'after_widget' => "</aside>",
			'before_title' => '<h3 class="sc-title">',
			'after_title' => '</h3>',
		) );

		// Register exclusive widget areas for each page
		$mypages = get_pages();
		foreach($mypages as $pp) {
			$page_opts = get_post_meta( $pp->ID, 'page_options', true );
			$sidebar_a = isset($page_opts['sidebar_a']) ? $page_opts['sidebar_a'] : '';
			$sidebar_h = isset($page_opts['sidebar_h']) ? $page_opts['sidebar_h'] : '';

			if ( $sidebar_h == 'true' ){
				register_sidebar( array(
					'name' => sprintf(__( '%1$s Header Bar', 'xing' ), $pp->post_title),
					'id' =>  $pp->ID.'-headerbar',
					'description' => 'Header Bar',
					'before_widget' => '<aside id="%1$s" class="hwa_wrap %2$s">',
					'after_widget' => "</aside>",
					'before_title' => '<h3 class="hwa-title">',
					'after_title' => '</h3>',
				) );
			};
			if ( $sidebar_a == 'true' ){
				register_sidebar( array(
					'name' => sprintf(__( '%1$s Sidebar', 'xing' ), $pp->post_title),
					'id' => $pp->ID.'-sidebar',
					'description' => 'Sidebar',
					'before_widget' => '<aside id="%1$s" class="widgetwrap %2$s">',
					'after_widget' => "</aside>",
					'before_title' => '<h3 class="sb-title">',
					'after_title' => '</h3>',
				) );
			}
		}
	}
endif;

// Theme Comments
if ( !function_exists( 'xing_comments' ) ) :
	function xing_comments($comment, $args, $depth) {
		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) :
			case 'pingback' :
			case 'trackback' :
			?>
                <li class="post pingback">
                <p><?php _e( 'Pingback:', 'xing' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'xing' ), '<span class="edit-link">', '</span>' ); ?></p>
                <?php
			break;
			default :
			?>
				<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
				<article id="comment-<?php comment_ID(); ?>" class="comment clearfix">
                    <div class="comment-author vcard author-avatar">
                        <?php
                        $avatar_size = 64;
                        if ( '0' != $comment->comment_parent )
                        $avatar_size = 48;
                        echo get_avatar( $comment, $avatar_size ); ?>
                    </div><!-- .comment-author .vcard -->
                    <div class="comment-content">
                        <div class="comment-meta">
                        <?php
                            printf( __( '%1$s on %2$s <span class="says">said: </span>', 'xing' ),
                            sprintf( '<span class="comment-author vcard"><span class="fn">%s</span></span>', get_comment_author_link() ),
                            sprintf( '<a href="%1$s"><time pubdate datetime="%2$s">%3$s</time></a>',
                            esc_url( get_comment_link( $comment->comment_ID ) ),
                            get_comment_time( 'c' ),
                            sprintf( __( '%1$s at %2$s', 'xing' ), get_comment_date(), get_comment_time() )
                            )
                            );
							edit_comment_link( __( 'Edit', 'xing' ), '<span class="edit-link">', '</span>' ); ?>
                        </div><!-- .comment-meta -->
                        <?php if ( $comment->comment_approved == '0' ) : ?>
                        <em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'xing' ); ?></em>
                        <br />
                        <?php endif;
                        comment_text(); ?>
                    </div><!-- .comment-content -->
                    <div class="reply">
                    <?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'xing' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
                    </div><!-- .reply -->
				</article><!-- #comment-<?php comment_ID(); ?> -->
				<?php
			break;
		endswitch;
	}
endif;

// Comment Form Fields Override
if ( !function_exists( 'comment_form_new_fields' ) ) :
	function comment_form_new_fields($fields) {
		$commenter = wp_get_current_commenter();
		$req = get_option( 'require_name_email' );
		$aria_req = ( $req ? " aria-required='true'" : '' );
		$fields =  array(
			'author' => '<p class="comment-form-author">' . '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /><label for="author">' . __( 'Name', 'xing' ) . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) . '</p>',
			'email'  => '<p class="comment-form-email"><input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /><label for="email">' . __( 'Email', 'xing' ) . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) . '</p>',
			'url'    => '<p class="comment-form-url"><input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /><label for="url">' . __( 'Website', 'xing' ) . '</label></p>',
		);
		return $fields;
	}
endif;
add_filter('comment_form_default_fields','comment_form_new_fields');

// Related Posts
if ( !function_exists( 'xing_related_posts' ) ) :
function xing_related_posts( $xng_rp_taxonomy, $xng_rp_style ) {
	global $post;
	$temp = (isset($post)) ? $post : '';
	if ( $xng_rp_taxonomy == 'tags' )
	{
		$tags = wp_get_post_tags($post->ID);
		if ($tags) {
			$tag_ids = array();
			foreach($tags as $individual_tag) $tag_ids[] = $individual_tag->term_id;
			$args=array(
				'tag__in' => $tag_ids,
				'post__not_in' => array($post->ID),
				'posts_per_page'=> 5,
				'orderby' => 'rand',
				'ignore_sticky_posts'=>1
			);
		} // end if tags
	} //end taxonomy tags
	else
	{
		$categories = get_the_category($post->ID);
		if ($categories) {
			$category_ids = array();
			foreach($categories as $individual_category) $category_ids[] = $individual_category->term_id;
			$args=array(
			'category__in' => $category_ids,
			'post__not_in' => array($post->ID),
			'posts_per_page'=> 5,
			'orderby' => 'rand',
			'ignore_sticky_posts'=>1
			);
		} // end if categories
	} // end taxonomy categories
		$new_query = new WP_Query($args);
		if($xng_rp_style == 'thumbnail')
			$list_class = 'related_posts';
		else
			$list_class = 'related_list';
		if( $new_query->have_posts() ) { ?>
			<div class="entry clearfix">
            <h4><?php _e( 'Related Posts', 'xing' ); ?></h4><ul class="<?php echo $list_class; ?> clearfix">
			<?php while( $new_query->have_posts() ) {
				$new_query->the_post();
				if ( has_post_thumbnail()) {
					$title = get_the_title();
					$img_src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'size_140_90');
					$img = $img_src[0];
				}
				else $img = ''; ?>
                <li><?php if( $xng_rp_style == 'thumbnail' ) { ?><a class="rp_thumb" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark"><img src="<?php echo $img; ?>" alt="<?php the_title(); ?>"/></a><h4><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark"><?php the_title(); ?></a></h4><?php }
				else { ?>
				<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark"><?php the_title(); ?></a></li>
			<?php }
			} // while have posts ?>
			</ul></div>
	<?php } // if have posts
	$post = $temp;
	wp_reset_query();
}
endif;

// Single Post Meta
if ( !function_exists( 'xing_post_meta' ) ) :
	function xing_post_meta() {
	printf( __( '<span class="sep">Posted on </span><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time></a><span class="by-author"> <span class="sep"> by </span> <span class="author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span></span> <span class="sep"> in </span>%8$s ', 'xing' ),
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'xing' ), get_the_author() ) ),
		get_the_author(),
		get_the_category_list( ', ' )
	);
	if ( comments_open() ) : ?>
			<span class="sep"><?php _e( ' with ', 'xing' ); ?></span>
			<span class="comments-link"><?php comments_popup_link( '<span class="leave-reply">' . __( '0 Comments', 'xing' ) . '</span>', __( '1 Comment', 'xing' ), __( '% Comments', 'xing' ) ); ?></span>
	<?php endif; // End if comments_open() ?>
    <?php edit_post_link( __( 'Edit', 'xing' ), '<span class="sep"> | </span><span class="edit-link">', '</span>' );
	}
endif;

// Masonry grid Post Meta
if ( !function_exists( 'xing_small_meta' ) ) :
	function xing_small_meta() {
	printf( __( '<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time></a>', 'xing' ),
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);
	if ( comments_open() ) : ?>
			<span class="sep"><?php _e( 'with', 'xing' );?></span>
			<span class="comments-link"><?php comments_popup_link( __( '0 Comments', 'xing' ), __( '1 Comment', 'xing' ), __( '% Comments', 'xing' ) ); ?></span>
	<?php endif; // End if comments_open()
	}
endif;

// Attachment Post Meta
if ( !function_exists( 'xing_attachment_meta' ) ) :
	function xing_attachment_meta() {
		printf( __( '<span>%1$s</span> by %2$s', 'xing' ), get_the_time(get_option('date_format')), sprintf( '<a href="%1$s" title="%2$s">%3$s</a>', get_author_posts_url( get_the_author_meta( 'ID' ) ), sprintf( esc_attr__( 'View all posts by %s', 'xing' ), get_the_author() ), get_the_author() )); edit_post_link( __( 'Edit', 'xing' ), ' &middot; ', '' );
		if ( wp_attachment_is_image() )
		{
			$metadata = wp_get_attachment_metadata();
			printf( __( ' &middot; Full size is %1$s pixels', 'xing' ),
				sprintf( '<a href="%1$s" title="%2$s">%3$s &times; %4$s</a>',
				wp_get_attachment_url(),
				esc_attr( __( 'Link to full-size image', 'xing' ) ),
					$metadata['width'],
					$metadata['height']
				)
			);
		}
	}
endif;

// Shorten Any Text
if ( !function_exists( 'short' ) ) :
	function short($text, $limit)
	{
		$chars_limit = $limit;
		$chars_text = strlen($text);
		$text = strip_tags($text);
		$text = $text." ";
		$text = substr($text,0,$chars_limit);
		$text = substr($text,0,strrpos($text,' '));
		if ($chars_text > $chars_limit)
		{
			$text = $text."...";
		}
		return $text;
	}
endif;

// SS Social Sharing
if ( !function_exists( 'ss_sharing' ) ) :
	function ss_sharing() {
		$share_link = get_permalink();
		$share_title = get_the_title();
		$xng_ss_fb = get_option('xng_ss_fb');
		$xng_ss_tw = get_option('xng_ss_tw');
		$xng_ss_tw_usrname = get_option('xng_ss_tw_usrname');
		$xng_ss_gp = get_option('xng_ss_gp');
		$xng_ss_pint = get_option('xng_ss_pint');
		$xng_ss_ln = get_option('xng_ss_ln');
		$out = '';
		if( $xng_ss_fb == 'true' ) {
			$out .= '<div class="fb-like" data-href="'.$share_link.'" data-send="false" data-layout="button_count" data-width="100" data-show-faces="false" data-font="arial"></div>';
		}
		if( $xng_ss_tw == 'true' ) {
			if( !empty($xng_ss_tw_usrname) ) {
				$out .= '<div class="ss_sharing_btn"><a href="http://twitter.com/share" class="twitter-share-button" data-url="'.$share_link.'"  data-text="'.$share_title.'" data-via="'.$xng_ss_tw_usrname.'">Tweet</a></div>';
			}
			else {
				$out .= '<div class="ss_sharing_btn"><a href="http://twitter.com/share" class="twitter-share-button" data-url="'.$share_link.'"  data-text="'.$share_title.'">Tweet</a></div>';
			}
		}
		if( $xng_ss_gp == 'true' ) {
			$out .= '<div class="ss_sharing_btn"><g:plusone size="medium" href="'.$share_link.'"></g:plusone></div>';
		}
		if( $xng_ss_pint == 'true' ) {
			global $post;
			setup_postdata($post);
			$src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID),'', '' );
			if ( has_post_thumbnail($post->ID) ) {
				$image = $src[0];
			}
			else {
				$image = get_template_directory_uri()."/images/post_thumb.jpg";
			}
			$description = short(get_the_excerpt(), 140);
			$share_link = get_permalink();
			$out .= '<div class="ss_sharing_btn"><a href="http://pinterest.com/pin/create/button/?url='.$share_link.'&media='.$image.'&description='.$description.'" class="pin-it-button" count-layout="horizontal"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a></div>';
			wp_reset_postdata();
		}
		if( $xng_ss_ln == 'true' ) {
			$out .= '<div class="ss_sharing_btn"><script type="IN/Share" data-url="'.$share_link.'" data-counter="right"></script></div>';
		}
		echo $out;
	}
endif;

// Load facebook Script in footer
if ( !function_exists( 'ss_fb_script' ) ) :
	function ss_fb_script() {
		if( is_single() && get_option('xng_ss_sharing') == 'true' && get_option('xng_ss_fb') == 'true' ) { ?>
		<div id="fb-root"></div>
		<script>(function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s); js.id = id;
		js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
		fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>
	<?php }
	}
endif;
add_action('wp_footer', 'ss_fb_script');

// Add facebook Open Graph Meta tags
if ( !function_exists( 'add_facebook_open_graph_tags' ) ) :
	function add_facebook_open_graph_tags() {
		if( is_single() && get_option('xng_ss_sharing') == 'true' && get_option('xng_ss_fb') == 'true' ) {
			global $post;
			setup_postdata($post);
			$src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID),'', '' );
			if ( has_post_thumbnail($post->ID) ) {
				$image = $src[0];
			}
			else {
				$image = get_template_directory_uri()."/images/post_thumb.jpg";
			}
			?>
			<meta property="og:title" content="<?php the_title(); ?>"/>
			<meta property="og:type" content="article"/>
			<meta property="og:image" content="<?php echo $image; ?>"/>
			<meta property="og:url" content="<?php the_permalink(); ?>"/>
			<meta property="og:description" content="<?php echo short(get_the_excerpt(), 140); ?>"/>
			<meta property="og:site_name" content="<?php bloginfo('name'); ?>"/>
			<?php wp_reset_postdata();
		}
	}
endif;
add_action('wp_head', 'add_facebook_open_graph_tags', 99);

// Add Facebook language attributes inside html tag
if ( !function_exists( 'add_og_xml_ns' ) ) :
	function add_og_xml_ns($out) {
		if( is_single() && get_option('xng_ss_sharing') == 'true' && get_option('xng_ss_fb') == 'true' ) {
			return $out.' xmlns:og="http://ogp.me/ns#" xmlns:fb="https://www.facebook.com/2008/fbml" ';
		}
		else
			return $out;
	}
endif;
add_filter('language_attributes', 'add_og_xml_ns');

// Add arrow class to menus
class Arrow_Walker_Nav_Menu extends Walker_Nav_Menu {
    function display_element($element, &$children_elements, $max_depth, $depth=0, $args, &$output) {
        $id_field = $this->db_fields['id'];
        if (!empty($children_elements[$element->$id_field])) {
            $element->classes[] = 'arrow'; //enter any classname you like here!
        }
        Walker_Nav_Menu::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
    }
}

// Menu Notifier when no menu is configured
if ( !function_exists( 'menu_reminder' ) ) :
function menu_reminder() {
	_e( '<span class="menu_notifier">Navigation Menu not configured yet. Please configure it inside WordPress <strong>Appearance > Menus</strong></span>', 'xing' );
}
endif;

// Enable short codes inside Widgets
add_filter( 'widget_text', 'shortcode_unautop');
add_filter( 'widget_text', 'do_shortcode');

// Allow HTML in category and term descriptions
foreach ( array( 'pre_term_description' ) as $filter ) {
    remove_filter( $filter, 'wp_filter_kses' );
}
foreach ( array( 'term_description' ) as $filter ) {
    remove_filter( $filter, 'wp_kses_data' );
}

// Load scripts required by the theme
if( !is_admin() ) {
add_action('wp_enqueue_scripts', 'ss_scripts');
}
if ( !function_exists( 'ss_scripts' ) ) :
	function ss_scripts() {
		global $xng_archive_template, $xng_scheme, $xng_disable_resp_css, $xng_disable_user_css;
		wp_enqueue_script('jquery');
		wp_enqueue_script('tabber', get_template_directory_uri().'/js/tabs.js', array('jquery-ui-core', 'jquery-ui-tabs', 'jquery-ui-accordion'), '', true);
		wp_enqueue_script('jq-pretty-photo', get_template_directory_uri().'/js/jquery.prettyPhoto.js', '', '', true);
		wp_enqueue_script('jq-carousel', get_template_directory_uri().'/js/jquery.jcarousel.min.js', '', '', true);
		wp_enqueue_script('jq-easing', get_template_directory_uri().'/js/jquery.easing.min.js', '', '', true);
		wp_enqueue_script('jq-hover-intent', get_template_directory_uri().'/js/jquery.hoverIntent.minified.js', '', '', true);
		wp_enqueue_script('jq-froogaloop', get_template_directory_uri().'/js/froogaloop2.min.js', '', '', true);
		wp_enqueue_script('jq-flex-slider', get_template_directory_uri().'/js/jquery.flexslider-min.js', '', '', true);

		// Only for filterable portfolio templates
		if( is_page_template('templates/port3-sb-filterable.php') || is_page_template('templates/port4-filterable.php') || is_page_template('templates/port4-sb-filterable.php') || is_page_template('templates/port5-filterable.php') ){
			wp_register_script( 'jq-quicksand', get_template_directory_uri().'/js/jquery.quicksand.js', '', '', true);
			wp_enqueue_script('jq-filterable', get_template_directory_uri().'/js/filterable.js', array('jq-quicksand'), '', true);
		}

		// Only for masonry grid templates
		if( is_page_template('templates/blog-grid.php') || ( ( $xng_archive_template == 'grid_style' ) && ( is_home() || is_archive() ) ) ){
			wp_register_script( 'jq-masonry', get_template_directory_uri()."/js/jquery.masonry.min.js", '', '', true);
			wp_enqueue_script('jq-mason-init', get_template_directory_uri().'/js/mason_init.js', array('jq-masonry'), '', true);
		}

		// Only for contact page
		if( is_page_template('templates/page-contact.php') ){
			wp_enqueue_script('jq-validate', get_template_directory_uri().'/js/jquery.validate.pack.js', '', '', true);
			wp_enqueue_script('contact-form', get_template_directory_uri().'/js/form_.js', '', '', true);
		}

		// Load social sharing scripts in footer
		if( is_single() && get_option('xng_ss_sharing') == 'true' ){
			if( get_option('xng_ss_tw') == 'true' )
				wp_enqueue_script('twitter_share_script', 'http://platform.twitter.com/widgets.js', '', '', true);
			if( get_option('xng_ss_gp') == 'true' )
				wp_enqueue_script('google_plus_script', 'http://apis.google.com/js/plusone.js', '', '', true);
			if( get_option('xng_ss_pint') == 'true' )
				wp_enqueue_script('pinterest_script', '//assets.pinterest.com/js/pinit.js', '', '', true);
			if( get_option('xng_ss_ln') == 'true' )
				wp_enqueue_script('linkedin_script', 'http://platform.linkedin.com/in.js', '', '', true);
		}

		// Load jPlayer Scripts
		if( is_page_template('templates/blog-grid.php') || is_page_template('templates/blog-list.php') || is_home() || is_archive() || ( is_single() && 'audio' == get_post_format()) ){
			wp_register_script( 'jq-jplayer', get_template_directory_uri()."/js/jquery.jplayer.min.js", '', '', true);
			wp_enqueue_script( 'jq-jplayer' );
			wp_register_style( 'jplayer', get_template_directory_uri() . '/css/jplayer_skin/jp_skin.css', '', '', 'all' );
			wp_enqueue_style( 'jplayer' );
		}

		// Miscellaneous
		wp_enqueue_script('custom', get_template_directory_uri().'/js/custom.js', '', '', true);
		wp_register_style( 'prettyphoto', get_template_directory_uri() . '/css/prettyPhoto.css', '', '', 'all' );
		wp_enqueue_style( 'prettyphoto' );
		if (class_exists( 'woocommerce' )) {
			wp_register_style( 'woo-custom', get_template_directory_uri() . '/woocommerce/woocommerce-custom.css', '', '', 'all' );
			wp_enqueue_style( 'woo-custom' );
		}
		if (class_exists('WP_eCommerce')) {
			wp_register_style( 'wpec-custom', get_template_directory_uri() . '/wpec-custom.css', '', '', 'all' );
			wp_enqueue_style( 'wpec-custom' );
		}
		if ( $xng_scheme != '' && $xng_scheme != 'default' ) {
			$scheme_url = get_template_directory_uri().'/css/schemes/'.$xng_scheme.'.css';
			wp_register_style( $xng_scheme.'-color-scheme', $scheme_url, '', '', 'all' );
			wp_enqueue_style( $xng_scheme.'-color-scheme' );
		}
		if ( $xng_disable_resp_css != 'true' ) {
			wp_register_style( 'xing-responsive', get_template_directory_uri() . '/responsive.css', '', '', 'all' );
			wp_enqueue_style( 'xing-responsive' );
		}
		if ( $xng_disable_user_css != 'true' ) {
			wp_register_style( 'xing-user', get_template_directory_uri() . '/user.css', '', '', 'all' );
			wp_enqueue_style( 'xing-user' );
		}
	}
endif;

// Add HTML5 JS for old browsers
add_action( 'wp_head', 'html5_js');
if (!function_exists('html5_js')):
function html5_js() { ?>
<!--[if lt IE 9]>
<script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]--><?php }
endif;

// Add Custom Markup inside head section
add_action( 'wp_head', 'custom_head_code');
if (!function_exists('custom_head_code')):
function custom_head_code() {
global $xng_custom_head_code;
if( $xng_custom_head_code != '' ) echo stripslashes($xng_custom_head_code);
}
endif;

// Add span tag to post count of categories and archives widget
function cats_widget_postcount_filter ($out) {
	$out = str_replace(' (', '<span class="count">(', $out);
	$out = str_replace(')', ')</span>', $out);
	return $out;
}
add_filter('wp_list_categories','cats_widget_postcount_filter');

function archives_widget_postcount_filter($out) {
	$out = str_replace('&nbsp;(', '<span class="count">(', $out);
	$out = str_replace(')', ')</span>', $out);
	return $out;
}
add_filter('get_archives_link', 'archives_widget_postcount_filter');

// Make theme options variables available for use
function load_theme_vars() {
	global $options;
	foreach ($options as $value) {
		if(isset($value['id']) && isset ($value['std'])) {
			global $$value['id'];
			if (get_option( $value['id'] ) === FALSE) {
				$$value['id'] = $value['std']; } else { $$value['id'] = get_option( $value['id'] );
			}
		}
	}
}
add_action( 'init','load_theme_vars' );?>