<?php
/* Main content loop - Grid Style */

global $xng_hide_post_meta;
if ( !have_posts() ) : ?>
    <div id="post-0" <?php post_class(); ?>>
        <h2><?php _e( 'Not Found', 'xing' ); ?></h2>
        <p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'xing' ); ?></p>
        <?php get_search_form(); ?>
    </div><!-- #post-0 -->
<?php endif; ?>
<div id="mason_container" class="clearfix">
	<?php while ( have_posts() ) : the_post(); ?>
    <article id="post-<?php the_ID();?>" <?php post_class('entry-grid'); ?>>
		<?php get_template_part( 'formats/grid-format', get_post_format() ); ?>
        <div class="entry-content">
            <h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
            <p><?php echo short(get_the_excerpt(), 160); ?></p>
            <?php if( $xng_hide_post_meta != 'true' ) {?>
            <aside id="meta-<?php the_ID();?>" class="entry-meta"><?php xing_small_meta(); ?></aside>
            <?php } ?>
        </div><!-- .entry-content -->
    </article><!-- #post-<?php the_ID();?> -->
    <?php endwhile; ?>
</div><!-- #mason_container -->
<?php if ( $wp_query->max_num_pages > 1 ) :
	if ( function_exists( 'wp_pagenavi' ) ) wp_pagenavi();
    else { ?>
        <div class="navigation">
            <div class="nav-previous"><?php next_posts_link( __( '&larr; Older Posts', 'xing' ) ) ?></div>
            <div class="nav-next"><?php previous_posts_link( __( 'Newer Posts &rarr;', 'xing' ) ) ?></div>
        </div><!-- .navigation -->
    <?php }
endif; ?>