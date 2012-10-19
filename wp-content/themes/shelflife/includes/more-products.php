<?php
/**
 * More Products Component
 *
 * Display X more products.
 *
 * @author Matty
 * @since 1.0.0
 * @package WooFramework
 * @subpackage Component
 */

global $woocommerce;

$settings = array(
				'product_limit' => '10', 
				'featured_exclude' => 'true'
				);
					
$settings = woo_get_dynamic_values( $settings );

if ( $settings['product_limit'] > 0 ) {
?>
<section id="more" class="fix">

	<h1 class="section-heading"><?php _e( 'More Products', 'woothemes' ); ?></h1>

	<ul class="recent products fix">
<?php
$i = 0;
$args = array( 'post_type' => 'product', 'posts_per_page' => $settings['product_limit'] );

$args['meta_query'] = array();
$args['meta_query']['relation'] = 'AND';

// Exclude the featured products based on the theme option setting.
if ( $settings['featured_exclude'] == 'true' ) {
	$args['meta_query'][] = array( 'key' => '_featured', 'value' => 'no', 'compare' => '=' );
}

$args['meta_query'][] = array( 'key' => '_visibility', 'value' => array( 'visible', 'catalog' ), 'compare' => 'IN' );
$args['meta_query'][] = array( 'key' => '_stock_status', 'value' => array( 'outofstock' ), 'compare' => 'NOT IN' );

$loop = new WP_Query( $args );
while ( $loop->have_posts() ) : $loop->the_post(); $_product = &new WC_Product( $loop->post->ID ); ?>

	    	<li class="product <?php $i++; if( 4 == $i ) { $i = 0; echo 'last'; }?>">

	    		<div class="img-wrap">
	    			<?php woocommerce_show_product_sale_flash( $post, $_product ); ?>
	    			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
<?php
if ( has_post_thumbnail( $loop->post->ID ) ) {
	echo get_the_post_thumbnail( $loop->post->ID, 'shop_thumbnail' );
} else {
	echo '<img src="'. esc_url( $woocommerce->plugin_url() . '/assets/images/placeholder.png' ) . '" alt="Placeholder" width="' . esc_attr( $woocommerce->get_image_size( 'shop_catalog_image_width' ) ) . 'px" height="' . esc_attr( $woocommerce->get_image_size( 'shop_catalog_image_height' ) ) . 'px" />';
}
?>
	    			</a>
	    		</div>

	    		<div class="meta">

	    			<h3><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
	    			<span class="price"><?php echo $_product->get_price_html(); ?></span>

	    			<?php woocommerce_template_loop_add_to_cart( $loop->post, $_product ); ?>

	    		</div><!--/.meta-->

	    	</li>
	    <?php
	    	endwhile;
	    	wp_reset_postdata();
	    ?>

	</ul><!--/ul.recent-->
<?php
$shop_page_url = '';
$shop_page_id = get_option( 'woocommerce_shop_page_id' );

if ( $shop_page_id != '' ) {
	$shop_page_url = get_permalink( intval( $shop_page_id ) );
}

if ( $shop_page_url != '' ) {
?>
	<div class="more-products-link">
		<a class="button" href="<?php echo esc_url( $shop_page_url ); ?>" title="<?php esc_attr_e( 'More Products', 'woothemes' ); ?>"><?php _e( 'More Products', 'woothemes' ); ?></a>
	</div>
<?php
}
?>
</section>
<?php
}
?>