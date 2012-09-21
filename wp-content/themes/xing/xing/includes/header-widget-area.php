<?php
/* Header Widget Area */

global $xng_logo_align;
if ( is_page() ) {
	$page_opts = get_post_meta( $posts[0]->ID, 'page_options', true );
	$hwa_usage = (isset($page_opts['hwa_usage'])) ? $page_opts['hwa_usage'] : 'default-headerbar';
}
elseif ( is_single() ) {
	$post_opts = get_post_meta( $posts[0]->ID, 'post_options', true );
	$hwa_usage = (isset($post_opts['hwa_usage'])) ? $post_opts['hwa_usage'] : 'default-headerbar';
}
if ( is_page() || is_single() ) {
	if ( is_active_sidebar( $hwa_usage )) : ?>
        <div class="header-widget-area<?php if( $xng_logo_align == 'right' ) echo( ' left' );?>">
        <?php dynamic_sidebar( $hwa_usage ); ?>
        </div><!-- .header-widget-area -->
	<?php endif;
}
else
{
	if ( is_active_sidebar( 'default-headerbar' ) ) : ?>
        <div class="header-widget-area<?php if( $xng_logo_align == 'right' ) echo( ' left' );?>">
		<?php dynamic_sidebar( 'default-headerbar' ); ?>
        </div><!-- .header-widget-area -->
	<?php endif;
} ?>