<?php
/* Post Format - Standard */

$title = get_the_title();
$permalink = get_permalink();
if ( has_post_thumbnail()) {
	$img_src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'size_242' );
	$img = $img_src[0];
	$img_height = $img_src[2];
}
else $img = '';
if ( $img ) { echo( '<div class="entry-thumb" style="height:'.$img_height.'px"><a href="'.$permalink.'"><img src="'.$img.'" alt="'.$title.'"/></a></div>');}?>