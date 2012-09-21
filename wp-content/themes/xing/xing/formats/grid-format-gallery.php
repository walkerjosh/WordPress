<?php
/* Post Format - Gallery */

$first_image = get_children( array( 'post_parent' => $post->ID, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC', 'numberposts' => 1 ) );
if ( $first_image ) :
	foreach($first_image as $image) {
		$img_src = wp_get_attachment_image_src( $image->ID, 'size_242' );
		$slider_height = $img_src[2] ;
	}
endif;
$images = get_children( array( 'post_parent' => $post->ID, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC', 'numberposts' => 999 ) ); ?>
<?php if ($images): ?>
	<script type="text/javascript">
		jQuery(window).load(function(){
			jQuery('#slider-<?php the_ID();?>').flexslider({
				animation: 'slide',
				easing: 'easeInOutExpo',
				animationSpeed: 400,
				slideshowSpeed: 4000,
				selector: '.slides > .slide',
				pauseOnAction: true,
				pausePlay: false,
				useCSS: false,
				smoothHeight: false
			});
		});
    </script>
    <div class="slider" id="slider-<?php the_ID();?>" style="height:<?php echo $slider_height.'px'; ?>"><ul class="slides">
    <?php foreach($images as $image) {
		$img_src = wp_get_attachment_image_src( $image->ID, 'size_242' );
		echo '<li class="slide"><img src="'.$img_src[0].'" alt="'.get_the_title().'"/></li>';
    }?>
    </ul></div><!-- .slider -->
<?php endif; ?>