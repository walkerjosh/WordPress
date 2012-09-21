<?php
/* Attachmemnt Template */

global $xng_sb_pos, $xng_hide_post_meta;
get_header(); ?>
  <div id="content"<?php if ( $xng_sb_pos == 'left' ) echo (' class="content-right"'); ?> role="main">
	<?php show_breadcrumbs();
    if (have_posts()) :
		while (have_posts()) : the_post(); ?>
		<div id="post-<?php the_ID(); post_class(); ?>">
            <h1><?php the_title(); ?></h1>
            <?php if($xng_hide_post_meta != 'true') {?>
                <div class="entry-meta"><?php xing_attachment_meta(); ?></div>
            <?php } // Attachment Meta ?>
            <p class="small"><?php if ( wp_attachment_is_image() ) :
            $attachments = array_values( get_children( array( 'post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) ) );
            foreach ( $attachments as $k => $attachment ) {
            if ( $attachment->ID == $post->ID )
            break;
            }
            $k++;
            // If there is more than 1 image attachment in a gallery
            if ( count( $attachments ) > 1 ) {
				if ( isset( $attachments[ $k ] ) )
				// get the URL of the next image attachment
				$next_attachment_url = get_attachment_link( $attachments[ $k ]->ID );
				else
				// or get the URL of the first image attachment
				$next_attachment_url = get_attachment_link( $attachments[ 0 ]->ID );
            }
            else {
				// or, if there's only 1 image attachment, get the URL of the image
				$next_attachment_url = wp_get_attachment_url();
            }?>
            <p class="attachment"><a href="<?php echo $next_attachment_url; ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="attachment"><?php echo wp_get_attachment_image( $post->ID, array( 999, 999 ), true );?></a></p>
            <?php else : ?>
            <a href="<?php echo wp_get_attachment_url(); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="attachment"><?php echo basename( get_permalink() ); ?></a>
            <?php endif; ?>
            <div class="entry-caption"><?php if ( !empty( $post->post_excerpt ) ) the_excerpt(); ?></div>
            <?php the_content( __( 'Read more &rarr;', 'xing' ) ); ?>
		</div><!-- .entry -->
		<?php endwhile;
		else : ?>
            <h2><?php _e( 'Not Found', 'xing' ); ?></h2>
            <p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'xing' ); ?></p>
    <?php endif; ?>
    <div class="navigation">
        <div class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'xing' ) . '</span> Previous Post' ); ?></div>
        <div class="nav-next"><?php next_post_link( '%link', 'Next post<span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'xing' ) . '</span> Next Post' ); ?></div>
    </div><!-- .navigation -->
</div><!-- #content -->
<?php get_sidebar(); ?>
</div><!-- #primary .wrap -->
</div><!-- #primary -->
<?php get_footer(); ?>