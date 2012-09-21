<?php
/*
Template Name: Portfolio - 5 col wide
A five columnar portfolio on full width */

get_header(); ?>
<div id="content" class="full-width" role="main">
	<?php show_breadcrumbs();
    if (is_page() ) {
		$page_opts = get_post_meta( $posts[0]->ID, 'page_options', true );
		$category = empty($page_opts['category']) ? '1' : $page_opts['category'];
		$post_per_page = empty($page_opts['post_per_page']) ? '15' : $page_opts['post_per_page'];
		if( have_posts() ):
			while (have_posts()) : the_post();
				the_content();
			endwhile;
		endif;
    } //if is_page
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
		$temp = $wp_query;  // assign orginal query to temp variable for later use
		$wp_query = new WP_Query($args);
		if( $wp_query->have_posts() ) :?>
            <ul class="port col5-wide clearfix">
            <?php while ($wp_query->have_posts()) : $wp_query->the_post();
                    if ( has_post_thumbnail()) {
                        $img_src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'size_254_198');
                        $big_src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'big');
                        $img = $img_src[0];
                        $big = $big_src[0];
                    }
                    else $img = '';
                    $title = get_the_title();
                    $permalink = get_permalink();?>
                    <li class="port-item non_filtered" style="z-index:<?php echo $count; ?>">
                        <div class="port-details">
                            <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
                            <span class="port-meta">
                            <a class="image-link" data-rel="prettyPhoto[group1]" href="<?php echo $big; ?>"><?php _e( 'View Image', 'xing' ); ?></a><a class="perma-link" href="<?php echo $permalink; ?>"><?php _e( 'Permalink', 'xing' ); ?></a>
                            </span><!-- .port-meta -->
                        </div><!-- .port-details -->
                        <?php if ($img) echo '<img src="'.$img.'" alt="'.$title.'" title="'.$title.'"/>'; ?>
                    </li>
                <?php endwhile; ?>
            </ul><!-- .port .col5-wide -->
            <?php if ( $wp_query->max_num_pages > 1 ) :
            if ( function_exists( 'wp_pagenavi' ) ) wp_pagenavi();
				else { ?>
				<div class="navigation">
                    <div class="nav-previous"><?php next_posts_link( __( '&larr; Older Posts', 'xing' ) ) ?></div>
                    <div class="nav-next"><?php previous_posts_link( __( 'Newer Posts &rarr;', 'xing' ) ) ?></div>
				</div><!-- .navigation -->
				<?php }
            endif;
		else : ?>
            <h2><?php _e( 'Not Found', 'xing' ); ?></h2>
            <p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'xing' ); ?></p>
            <?php get_search_form();
		endif;
		$wp_query = $temp;  //reset back to original query
    }  // if category ?>
</div><!-- #content -->
</div><!-- #primary .wrap -->
</div><!-- #primary -->
<?php get_footer(); ?>