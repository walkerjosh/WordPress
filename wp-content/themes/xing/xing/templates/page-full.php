<?php
/*
Template Name: Page - Full Width
*/

get_header(); ?>
<div id="content" class="full-width" role="main" >
	<?php show_breadcrumbs();
    if (have_posts()) :
		while (have_posts()) : the_post(); ?>
			<?php the_content( __( 'More &rarr;', 'xing' ) );
            wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'xing' ), 'after' => '</div>' ) );
		endwhile;
    else : ?>
        <h2><?php _e( 'Not Found', 'xing' ); ?></h2>
        <p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'xing' ); ?></p>
    <?php endif; ?>
</div><!-- #content -->
</div><!-- #primary .wrap -->
</div><!-- #primary -->
<?php get_footer(); ?>