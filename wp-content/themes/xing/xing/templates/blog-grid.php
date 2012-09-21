<?php
/*
Template Name: Blog - Masonry Grid Style
*/
global $xng_sb_pos, $xng_hide_post_meta;
get_header(); ?>
<div id="content-grid"<?php if ( $xng_sb_pos == 'left' ) echo (' class="content-right"'); ?> role="main">
	<?php show_breadcrumbs();
    if (is_page() ) {
		$page_opts = get_post_meta( $posts[0]->ID, 'page_options', true );
		$category = !empty($page_opts['category']) ? $page_opts['category'] : '1';
		$post_per_page = !empty($page_opts['post_per_page']) ? $page_opts['post_per_page'] : '10'; ?>
        <div class="content-grid-inner">
		<?php if( have_posts() ):
			while (have_posts()) : the_post();
				the_content();
            endwhile;
		endif; ?>
        </div><!-- .content-grid-inner -->
    <?php }
    if ($category) {
		if ( get_query_var('paged') ) {
			$paged = get_query_var('paged');
		}
		elseif ( get_query_var('page') ) {
			$paged = get_query_var('page');
		}
		else {
			$paged = 1;
		}
		$args=array(
			'cat' => $category,
			'orderby' => 'date',
			'order' => 'desc',
			'paged' => $paged,
			'posts_per_page' => $post_per_page,
			'ignore_sticky_posts' => 1
		);
		$temp = $wp_query;  // Assign orginal query to temp variable for later use
		$wp_query = new WP_Query($args);
		if( have_posts() ): ?>
		<div id="mason_container" class="clearfix">
			<?php while ($wp_query->have_posts()) : $wp_query->the_post(); ?>
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
		<?php
		if ( $wp_query->max_num_pages > 1 ) :?>
			<?php if ( function_exists( 'wp_pagenavi' ) ) wp_pagenavi();
            else { ?>
            <div class="navigation">
                <div class="nav-previous"><?php next_posts_link( __( '&larr; Older Posts', 'xing' ) ) ?></div>
                <div class="nav-next"><?php previous_posts_link( __( 'Newer Posts &rarr;', 'xing' ) ) ?></div>
            </div><!-- .navigation -->
            <?php }
		endif;
		else : ?>
            <h2 class="entry-title"><?php _e( 'Not Found', 'xing' ); ?></h2>
            <p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'xing' ); ?></p>
            <?php get_search_form();
		endif;
		$wp_query = $temp;  //reset back to original query
    }  // if category ?>
</div><!-- #content -->
<?php get_sidebar(); ?>
</div><!-- #primary .wrap -->
</div><!-- #primary -->
<?php get_footer(); ?>