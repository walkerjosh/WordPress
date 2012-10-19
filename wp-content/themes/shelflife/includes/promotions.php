<?php
/**
 * Promotions Component
 *
 * Display X recent promotions.
 *
 * @author Matty
 * @since 1.0.0
 * @package WooFramework
 * @subpackage Component
 */

$settings = array(
				'promotions_limit' => '10'
				);
					
$settings = woo_get_dynamic_values( $settings );

$args = array( 'post_type' => 'promotion', 'numberposts' => $settings['promotions_limit'] );
$promotions = get_posts( $args );

if ( count( $promotions ) > 0 ) {
?>
<section id="promo">
	<div class="flexslider">
		<ul class="slides">
		<?php
			foreach ( $promotions as $k => $post ) {
				setup_postdata( $post );
				
				$meta = get_post_custom( get_the_ID() );
		?>
		    <li>
		    	
		    	<?php
		    	
		    		$text = get_the_title( $post->ID );
		    		$url = get_permalink( $post->ID );
		    	
		    		if ( isset( $meta['_button_text'][0] ) && $meta['_button_text'][0] != '' ) {	
		    			$text = esc_attr( $meta['_button_text'][0] );
		    		}
		    		if ( isset( $meta['_button_url'][0] ) && $meta['_button_url'][0] != '' ) {
		    			$url = esc_url( $meta['_button_url'][0] );
		    		}
		    	?>
		    	
		    	<a href="<?php echo $url; ?>" title="<?php the_title_attribute(); ?>">
		    		<?php woo_image( 'width=150&link=img' ); ?>
		    	</a>
		    	
		    	<article id="promotion-<?php the_ID(); ?>">
					<h1><a href="<?php echo $url; ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
					<div class="excerpt"><?php the_excerpt(); ?></div>	
					<a class="button sale" href="<?php echo $url; ?>" title="<?php echo $text; ?>"><?php echo $text; ?></a>
		    	</article>
		    </li>
		<?php
			}
		?>
		</ul>
	</div>
</section>
<?php
}
?>