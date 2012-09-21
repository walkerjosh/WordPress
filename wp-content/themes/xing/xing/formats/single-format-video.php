<?php
/* Post Format - Video */

$post_opts = get_post_meta( $post->ID, 'post_options', true);
$pf_video = !empty($post_opts['pf_video']) ? $post_opts['pf_video'] : '';?>
<?php if ( $pf_video ) { ?>
<div class="single-video-wrap embed_wrap">
<?php global $wp_embed;
$post_embed = $wp_embed->run_shortcode('[embed width="764"]'.$pf_video.'[/embed]');
echo $post_embed; ?>
</div><!-- .single-video-wrap -->
<?php }
else { _e( '<span class="no-video">No video URL found.</span>', 'xing' ); }?>