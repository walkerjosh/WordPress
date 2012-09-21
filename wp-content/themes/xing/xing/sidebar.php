<?php
/* Sidebar template. */

global $xng_sb_pos;
if ( is_page() ) {
	$page_opts = get_post_meta( $posts[0]->ID, 'page_options', true );
	$sb_usage = (isset($page_opts['sb_usage'])) ? $page_opts['sb_usage'] : 'default-sidebar';
}
elseif ( is_single() ) {
	$post_opts = get_post_meta( $posts[0]->ID, 'post_options', true );
	$sb_usage = (isset($post_opts['sb_usage'])) ? $post_opts['sb_usage'] : 'default-sidebar';
} ?>
<div id="sidebar"<?php if ( $xng_sb_pos == 'left' ) echo(' class="sb-left"'); ?> role="complementary" >
<?php if ( is_page() || is_single() ) {
		if ( is_active_sidebar( $sb_usage )) :
			dynamic_sidebar( $sb_usage );
		else: ?>
            <div class="sb_notifier">
            <?php _e( '<h4>Sidebar not configured yet.</h4><p>You can place widgets by navigating to WordPress Appearance > Widgets.</p>', 'xing' );?>
            </div>
		<?php endif;
    }
    else
    {
		if ( is_active_sidebar( 'default-sidebar' ) ) :
			dynamic_sidebar( 'default-sidebar' );
		else: ?>
            <div class="sb_notifier">
            <?php _e('<p>Sidebar not configured yet. You can place widgets by navigating to WordPress Appearance > Widgets.</p>', 'xing');?>
            </div>
	<?php endif;
    } ?>
</div><!-- #sidebar -->