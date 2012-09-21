<?php
/*
Template Name: Blog - List Style
*/
global $xng_sb_pos, $xng_hide_post_meta;
get_header(); ?>
<div id="content"<?php if ( $xng_sb_pos == 'left' ) echo (' class="content-right"'); ?> role="main">
	<?php show_breadcrumbs();
    if (is_page() ) {
		$page_opts = get_post_meta( $posts[0]->ID, 'page_options', true );
		$category = !empty($page_opts['category']) ? $page_opts['category'] : '1';
		$post_per_page = !empty($page_opts['post_per_page']) ? $page_opts['post_per_page'] : '10';
		if( have_posts() ):
			while (have_posts()) : the_post();
				the_content();
			endwhile;
		endif;
    }
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
		if( have_posts() ) :
			while ($wp_query->have_posts()) : $wp_query->the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('list_style entry clearfix'); ?>>
					<?php get_template_part( 'formats/list-format', get_post_format() ); ?>
                    <h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
                    <?php if($xng_hide_post_meta != 'true') {?>
                    <aside id="meta-<?php the_ID();?>" class="entry-meta"><?php xing_post_meta(); ?></aside>
                    <?php } // Globally hide post meta
                    the_excerpt(); ?>
                    <p><a class="more-link" href="<?php the_permalink(); ?>" title="<?php _e( 'Read full post', 'xing'); ?>"><?php _e( 'Read More', 'xing' ); ?></a></p>
                    </div><!-- .entry-list-right -->
                </article><!-- #post-<?php the_ID(); ?> -->
			<?php endwhile; // End the loop
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