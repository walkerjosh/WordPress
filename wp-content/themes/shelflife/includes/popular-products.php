<?php
/**
 * Popular Products Component
 *
 * Display X recent popular products.
 *
 * @author Matty
 * @since 1.0.0
 * @package WooFramework
 * @subpackage Component
 */

global $woocommerce;

$settings = array(
				'popular_enable' => 'true', 
				'popular_limit' => 12, 
				'popular_pergroup' => 6
				);
					
$settings = woo_get_dynamic_values( $settings );

?>

<h1 class="section-heading"><?php _e( 'Popular Products', 'woothemes' ); ?></h1>

<section id="popular">
	
	<div class="flexslider">

<?php
add_filter( 'posts_clauses', 'shelflife_order_by_rating_post_clauses' );

$args = array( 'posts_per_page' => $settings['popular_limit'], 'post_status' => 'publish', 'post_type' => 'product' );

$args['meta_query'] = array();
$args['meta_query']['relation'] = 'AND';
$args['meta_query'][] = array( 'key' => '_visibility', 'value' => array( 'visible', 'catalog' ), 'compare' => 'IN' );
$args['meta_query'][] = array( 'key' => '_stock_status', 'value' => array( 'outofstock' ), 'compare' => 'NOT IN' );

$top_rated_posts = new WP_Query( $args );
$count = 0;

if ( $top_rated_posts->have_posts() ) {
	echo '<ul class="products slides">' . "\n";
	echo '<li>' . "\n";
	while ( $top_rated_posts->have_posts() ) {
		$top_rated_posts->the_post();
		$_product = &new WC_Product( $top_rated_posts->post->ID );
		$count++;
?>
		<div class="product">
    		<a href="<?php echo esc_url( get_permalink( $top_rated_posts->post->ID ) ); ?>" title="<?php echo esc_attr( $top_rated_posts->post->post_title ? $top_rated_posts->post->post_title : $top_rated_posts->post->ID ); ?>">
    		<?php echo $_product->get_image(); ?>

			<a class="price" href="<?php echo get_permalink( $loop->post->ID ); ?>" title="<?php the_title_attribute(); ?>"><?php echo $_product->get_price_html(); ?></a>

    		</a>
    	</div>
<?php
		if ( ( $count % $settings['popular_pergroup'] == 0 ) && ( $count < $top_rated_posts->post_count ) ) {
			echo '</li><li>' . "\n";
		}
	}
	echo '</li>' . "\n";
	echo '</ul>' . "\n";
}

wp_reset_query();
remove_filter( 'posts_clauses', 'shelflife_order_by_rating_post_clauses' );
?>
</div>
</section>