<?php
/* Archives Template */

global $xng_archive_template, $xng_sb_pos;
get_header();
$content_id = ($xng_archive_template == 'grid_style') ? 'content-grid' : 'content'; ?>
    <div id="<?php echo $content_id; ?>"<?php if ( $xng_sb_pos == 'left' ) echo (' class="content-right"'); ?> role="main">
		<?php show_breadcrumbs();
        if (have_posts()) the_post();
		if(is_author()):
			if ( get_the_author_meta( 'description' ) ) : ?>
                <div class="entry clearfix">
                    <div id="author-avatar">
						<?php $dir = get_template_directory_uri();
                        $default_avatar = $dir . '/images/default_avatar.jpg';
                        echo get_avatar( get_the_author_meta( 'user_email' ), $size='80', $default = $default_avatar ); ?>
                    </div><!-- #author-avatar -->
                    <div id="author-description">
                        <h4 class="author vcard"><?php printf( __( 'About <span class="fn">%s</span>', 'xing' ), get_the_author() ); ?></h4>
                        <p><?php the_author_meta( 'description' ); ?></p>
                    </div><!-- #author-description -->
                </div><!-- .entry -->
		<?php endif; // has description
		endif; // is_author()
		rewind_posts();
		if( $xng_archive_template == 'list_style' )
			get_template_part( 'content-list' );
		else
			get_template_part( 'content' ); ?>
    </div><!-- #content -->
<?php get_sidebar(); ?>
</div><!-- #primary .wrap -->
</div><!-- #primary -->
<?php get_footer(); ?>