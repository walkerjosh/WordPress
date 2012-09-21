<?php
/* Header Template */

global $xng_top_bar_hide, $xng_cb_top_text, $xng_scheme, $xng_cb_hide, $xng_cb_text, $xng_layout, $xng_logo_align, $xng_blog_name, $xng_logo;
$dir = get_template_directory_uri(); ?>
<!DOCTYPE html>
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?> class="no-js">
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?> class="no-js">
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?> class="no-js">
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php global $page, $paged; wp_title( '|', true, 'right' ); bloginfo( 'name' ); $site_description = get_bloginfo( 'description', 'display' ); if ( $site_description && ( is_home() || is_front_page() ) ) echo " | $site_description"; if ( $paged >= 2 || $page >= 2 ) echo ' | ' . sprintf( __( 'Page %s', 'xing' ), max( $paged, $page ) ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php if ( is_singular() && get_option( 'thread_comments' ) )
	wp_enqueue_script( 'comment-reply' );
wp_head(); ?>
</head>
<?php /* Add custom body class depending upon the layout style */
$new_body_class  = ( $xng_layout == 'stretched' ) ? 'is-stretched' : '';
$new_body_class .= ( $xng_top_bar_hide != 'true' ) ? ' no-border' : ''; ?>
<body <?php body_class($new_body_class); ?>>
<?php if($xng_top_bar_hide != 'true') { ?>
    <nav id="top-menu" class="ss_nav_top">
        <div class="wrap clearfix">
        <?php wp_nav_menu( array( 'container' => false, 'menu_class' => 'nav2', 'theme_location' => 'secondary', 'fallback_cb' => 'menu_reminder', 'walker' => new Arrow_Walker_Nav_Menu ) ); ?>
        <div id="callout-top" role="complementary"><?php echo stripslashes($xng_cb_top_text); ?></div><!-- #callout-top -->
        </div><!-- #top-menu .wrap -->
    </nav><!-- #top-menu-->
	<?php } // Top bar hide
	if( $xng_layout != 'stretched' ) { ?>
    <div id="container">
    <?php } ?>
        <div id="utility">
            <div class="wrap clearfix">
			<?php if($xng_cb_hide != 'true') { ?>
                <div id="callout"><?php echo stripslashes($xng_cb_text); ?></div><!-- #callout -->
            <?php }
            if (class_exists( 'woocommerce' ))
                get_template_part('woocommerce/account-bar'); ?>
            </div><!-- #utlity .wrap -->
        </div><!-- #utility -->
        <div id="header">
            <div class="wrap clearfix">
                <div class="brand<?php if( $xng_logo_align == 'right' ) echo( ' right' ); ?>" role="banner">
					<?php if ( $xng_blog_name == 'true' ){ ?>
                    <hgroup>
                        <h1 id="site-title"><span><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></span></h1>
                        <h2 id="site-description"><?php bloginfo( 'description' ); ?></h2>
                    </hgroup>
                    <?php }
                    else { ?>
                    <h1><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><img src="<?php if ( $xng_logo != '' ) echo $xng_logo; else echo ( $dir.'/images/logo.png' ); ?>" alt="<?php bloginfo('name'); ?>" /></a></h1>
                    <?php } ?>
                </div><!-- .brand -->
                <?php get_template_part('includes/header-widget-area'); ?>
            </div><!-- #header .wrap -->
        </div><!-- #header -->
        <nav id="access" class="ss_nav clearfix" role="navigation">
            <div class="wrap clearfix">
            <?php wp_nav_menu( array( 'container' => false, 'menu_class' => 'nav1', 'theme_location' => 'primary', 'fallback_cb' => 'menu_reminder', 'walker' => new Arrow_Walker_Nav_Menu ) ); ?>
            </div><!-- #access .wrap -->
        </nav><!-- #access -->
        <div id="primary">
        <div class="wrap clearfix">