<?php
/* Post Format - Standard */

$title = get_the_title();
$permalink = get_permalink();
if ( has_post_thumbnail()) {
	$img_src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'size_242_198' );
	$img = $img_src[0];
	$img_height = $img_src[2];
}
else $img = ''; ?>
<?php if ( $img ) { ?>
    <div class="entry-list-left">
    <?php echo( '<div class="entry-thumb" style="height:'.$img_height.'px"><a href="'.$permalink.'"><img src="'.$img.'" alt="'.$title.'"/></a></div>');?>
    </div><!-- .entry-list-left -->
<?php } ?>
<div class="entry-list-right<?php if( !$img ) echo(' no_image'); ?>">