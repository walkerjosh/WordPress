<?php
/* Footer Template */

global $xng_hide_secondary, $xng_footer_left, $xng_footer_right, $xng_layout;
if ( is_active_sidebar( 'secondary-column-1' ) || is_active_sidebar( 'secondary-column-2' ) || is_active_sidebar( 'secondary-column-3' ) || is_active_sidebar( 'secondary-column-4' ) || is_active_sidebar( 'secondary-column-5' ) ) {
	if ( is_page() ) {
		$page_opts = get_post_meta( $posts[0]->ID, 'page_options', true );
		$hide_secondary = isset($page_opts[ 'hide_secondary' ]) ? $page_opts[ 'hide_secondary' ] : '';
	} // is page
	elseif ( is_single() ) {
		$post_opts = get_post_meta( $posts[0]->ID, 'post_options', true );
		$hide_secondary = isset($post_opts[ 'hide_secondary' ]) ? $post_opts[ 'hide_secondary' ] : '';
	} // is single
	else {
		$hide_secondary = $xng_hide_secondary;
	}
	if ( $hide_secondary != 'true' ): ?>
	<div id="secondary" role="complementary">
        <div class="wrap clearfix">
            <div class="one_fifth">
            <?php
            if ( is_active_sidebar( 'secondary-column-1' ) )
				dynamic_sidebar( 'secondary-column-1' );
            ?>
            </div><!-- .one_fifth -->
            <div class="one_fifth">
            <?php
            if ( is_active_sidebar( 'secondary-column-2' ) )
				dynamic_sidebar( 'secondary-column-2' );
            ?>
            </div><!-- .one_fifth -->
            <div class="one_fifth">
            <?php
            if ( is_active_sidebar( 'secondary-column-3' ) )
				dynamic_sidebar( 'secondary-column-3' );
            ?>
            </div><!-- .one_fifth -->
            <div class="one_fifth">
            <?php
            if ( is_active_sidebar( 'secondary-column-4' ) )
				dynamic_sidebar( 'secondary-column-4' );
            ?>
            </div><!-- .one_fifth -->
            <div class="one_fifth last">
            <?php
            if ( is_active_sidebar( 'secondary-column-5' ) )
				dynamic_sidebar( 'secondary-column-5' );
            ?>
            </div><!-- .one_fifth_last -->
        </div><!-- #secondary .wrap -->
	</div><!-- #secondary -->
	<?php endif; //show secondary
} // If widget areas are active ?>
<div id="footer" role="contentinfo">
    <div class="wrap clearfix">
        <div class="notes_left"><?php echo stripslashes($xng_footer_left); ?></div><!-- .notes_left -->
        <div class="notes_right"><?php echo stripslashes($xng_footer_right); ?></div><!-- .notes_right -->
    </div><!-- #footer wrap -->
</div><!-- #footer -->
<?php if( $xng_layout != 'stretched' ) {
/* Close containers if not stretched layout */ ?>
</div> <!-- #container -->
<?php } // Not stretched ?>
<div class="top_btn"><a href="#" title="<?php _e( 'Scroll to top', 'xing' ); ?>"></a></div><!-- .top_btn -->
<?php wp_footer(); ?>
</body>
</html>