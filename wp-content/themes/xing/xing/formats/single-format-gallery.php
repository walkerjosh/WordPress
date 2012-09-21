<?php
/* Post Format - Gallery */

$images = get_children( array( 'post_parent' => $post->ID, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC', 'numberposts' => 999 ) ); ?>
<?php if ($images): ?>
<ul id="gallery-<?php the_ID();?>" class="gallery-single clearfix">
	<?php
    foreach($images as $image) {
		$img_src = wp_get_attachment_image_src( $image->ID, 'size_242_198' );
		$img_big = wp_get_attachment_image_src( $image->ID, 'big' );
		echo '<li><a data-rel="prettyPhoto[group1]" href="'.$img_big[0].'"><img src="'.$img_src[0].'" alt="'.get_the_title().'"/></a></li>';
    }?>
</ul><!-- #gallery-<?php the_ID();?> -->
<?php endif; ?>