<?php
/*
Template Name: Portfolio Filterable - 3 col with SB
A three columnar filterable portfolio with sidebar */

global $xng_sb_pos;
get_header(); ?>
<div id="content"<?php if ( $xng_sb_pos == 'left' ) echo (' class="content-right"'); ?> role="main">
	<?php show_breadcrumbs();
    if (is_page() ) {
		$page_opts = get_post_meta( $posts[0]->ID, 'page_options', true );
		$category = empty($page_opts['category']) ? '1' : $page_opts['category'];
		if( have_posts() ):
			while (have_posts()) : the_post();
				the_content();
			endwhile;
		endif;
    } //if is_page
    $cat_ids = explode(',', $category);
    $count = count($cat_ids);
    if ( $count > 0 ){ ?>
        <ul id="filter-nav"><li class="filter-nav-label"><?php _e( 'Filter by', 'xing' ); ?></li><li class="current all"><a href="#"><?php _e( 'All', 'xing' ); ?></a></li>
        <?php foreach ( $cat_ids as $cat_id ) {
			$cat_name = get_the_category_by_ID($cat_id);
			$cat_slug = strtolower($cat_name);
			$cat_slug = str_replace(' ', '-', $cat_slug);
			if(get_category($cat_id) -> category_count > 0) {
				echo '<li class="'.$cat_slug.'"><a href="#" rel="'.$cat_slug.'">'.$cat_name.'</a></li>';
			}
        } // foreach
    } // count
    echo "</ul>";
    if ($category) {
		$args=array(
			'cat' => $category,
			'orderby' => 'date',
			'order' => 'desc',
			'posts_per_page' => -1,
			'ignore_sticky_posts' => 1
		);
		$temp = $wp_query;  // assign orginal query to temp variable for later use
		$wp_query = new WP_Query($args);
		if( $wp_query->have_posts() ) :
			$count = 1; ?>
			<ul class="port ss_filterable col3 clearfix">
			<?php while ($wp_query->have_posts()) : $wp_query->the_post();
					$post_cats = wp_get_post_categories( $post->ID);
					$cats = array();
					foreach($post_cats as $c){
						$cat_name = get_the_category_by_ID($c);
						$cats[] = $cat_name;
					}
					$cats = str_replace(' ', '-', $cats);
					$tax = join( " ", $cats );

					if ( has_post_thumbnail()) {
						$img_src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'size_254_198');
						$big_src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'big');
						$img = $img_src[0];
						$big = $big_src[0];
					}
					else $img = '';
					$title = get_the_title();
					$permalink = get_permalink();?>
					<li class="port-item <?php echo strtolower($tax); ?>" data-id="<?php echo 'port-id-'.$count; ?>" style="z-index:<?php echo $count; ?>">
                        <div class="port-details">
                            <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
                            <span class="port-meta">
                            <a class="image-link" data-rel="prettyPhoto[group1]" href="<?php echo $big; ?>"><?php _e( 'View Image', 'xing' ); ?></a><a class="perma-link" href="<?php echo $permalink; ?>"><?php _e( 'Permalink', 'xing' ); ?></a>
                            </span><!-- .port-meta -->
                        </div><!-- .port-details -->
                        <?php if ($img) echo '<img src="'.$img.'" alt="'.$title.'" title="'.$title.'"/>'; ?>
					</li>
					<?php $count++;
                endwhile; ?>
			</ul><!-- .port .ss_filterable .col3 -->
		<?php else : ?>
            <h2><?php _e( 'Not Found', 'xing' ); ?></h2>
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