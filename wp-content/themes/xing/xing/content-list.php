<?php
/* Main content loop - List Style */

global $xng_hide_post_meta;
if ( !have_posts() ) : ?>
    <div id="post-0" <?php post_class(); ?>>
        <h2><?php _e( 'Not Found', 'xing' ); ?></h2>
        <p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'xing' ); ?></p>
        <?php get_search_form(); ?>
    </div><!-- #post-0 -->
<?php endif;
while ( have_posts() ) : the_post(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class('list_style entry clearfix'); ?>>
		<?php get_template_part( 'formats/list-format', get_post_format() ); ?>
        <h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
        <?php if($xng_hide_post_meta != 'true') { ?>
        <aside id="meta-<?php the_ID();?>" class="entry-meta"><?php xing_post_meta(); ?></aside>
        <?php } // Hide post meta
        the_excerpt(); ?>
        <p><a class="more-link" href="<?php the_permalink(); ?>" title="<?php _e( 'Read full post', 'xing'); ?>"><?php _e( 'Read More', 'xing' ); ?></a></p>
        </div><!-- .entry-list-right -->
    </article><!-- #post-<?php the_ID(); ?> -->
<?php endwhile; // End the loop
if ( $wp_query->max_num_pages > 1 ) : ?>
	<?php if ( function_exists( 'wp_pagenavi' ) ) wp_pagenavi();
    else { ?>
    <div class="navigation">
        <div class="nav-previous"><?php next_posts_link( __( '&larr; Older Posts', 'xing' ) ) ?></div>
        <div class="nav-next"><?php previous_posts_link( __( 'Newer Posts &rarr;', 'xing' ) ) ?></div>
    </div> <!-- .navigation -->
    <?php }
endif; ?>