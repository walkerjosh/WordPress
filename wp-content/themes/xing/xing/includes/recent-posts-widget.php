<?php
/* List the most recent posts from the categories you mention */

class xing_Recent_Posts extends WP_Widget {
	function xing_Recent_Posts() {
		$widget_ops = array( 'classname' => 'xing_recent_entries', 'description' => __( 'List recent posts with thumbnails from custom categories.', 'xing' ) );
		$this->WP_Widget('xing-recent-posts', __( 'Xing Recent Posts', 'xing' ), $widget_ops);
		$this->alt_option_name = 'xing_recent_entries';
		add_action( 'save_post', array(&$this, 'flush_widget_cache') );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
	}

	function widget($args, $instance) {
		$cache = wp_cache_get('widget_recent_posts', 'widget');
		$hide_thumb= isset($instance['hide_thumb']) ? $instance['hide_thumb'] : false;
		if ( !is_array($cache) )
			$cache = array();
		if ( isset($cache[$args['widget_id']]) ) {
			echo $cache[$args['widget_id']];
			return;
		}
		ob_start();
		extract($args);
		$cats = empty( $instance['cats'] ) ? '' : $instance['cats'];
		$offset = empty( $instance['offset'] ) ? '0' : $instance['offset'];
		$title = apply_filters('widget_title', empty($instance['title']) ? __( 'Recent Posts', 'xing' ) : $instance['title']);
		if ( !$number = (int) $instance['number'] )
			$number = 10;
		else if ( $number < 1 )
			$number = 1;
		else if ( $number > 15 )
			$number = 15;
		$r = new WP_Query(array('showposts' => $number, 'nopaging' => 0, 'post_status' => 'publish', 'ignore_sticky_posts' => 1, 'cat' => $cats, 'offset' => $offset));
		if ($r->have_posts()) :
			$output = '';
			echo $before_widget;
            if ( $title ) echo $before_title . $title . $after_title;
			$list_class = ( $hide_thumb == false ) ? 'thumb_list' : 'normal_list';
			?>
            <ul class="<?php echo $list_class; ?>">
				<?php  while ($r->have_posts()) : $r->the_post();
					$time = get_the_time(get_option('date_format'));
					$permalink = get_permalink();
					$title = get_the_title();
					$bloginfo = get_template_directory_uri();
					if ( has_post_thumbnail()) {
						$img_src = wp_get_attachment_image_src( get_post_thumbnail_id($GLOBALS['post']->ID), 'size_90');
						$thumbnail = $img_src[0];
					}
					else $thumbnail = '';
					$default_thumb = $bloginfo.'/images/post_thumb.jpg';
					$thumbnail = ( $thumbnail == '' ) ? $default_thumb : $thumbnail;
					if( $hide_thumb == false ) {
						$thumblink = sprintf('<div class="cp_thumb"><a href="%1$s" rel="bookmark" title="%2$s"><img src="%4$s" alt="%2$s"/></a></div>',$permalink, $title, $bloginfo, $thumbnail);
						}
						else $thumblink = '';
					$format = '<li>%1$s<div class="cp_title"><h4><a href="%2$s" rel="bookmark" title="%3$s">%3$s</a></h4><span class="list_meta">%4$s</span></div></li>';
					$output.= sprintf( $format, $thumblink, $permalink, $title, $time );
                endwhile;
                $output .= '</ul>';
				echo $output;
            echo $after_widget; ?>
            <?php wp_reset_query();  // Restore global post data stomped by the_post().
		endif;
		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_add('widget_recent_posts', $cache, 'widget');
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$instance['cats'] = strip_tags( $new_instance['cats'] );
		$instance['offset'] = strip_tags( $new_instance['offset'] );
		$instance['hide_thumb'] = isset($new_instance['hide_thumb']) ? true : false;
		$this->flush_widget_cache();
		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['xing_recent_entries']) )
		delete_option('xing_recent_entries');
		return $instance;
	}

	function flush_widget_cache() {
		wp_cache_delete('widget_recent_posts', 'widget');
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'hide_thumb' => false, 'cats' => '', 'offset' => '0') );
		$title = esc_attr( $instance['title'] );
		$cats = esc_attr( $instance['cats'] );
		$offset = esc_attr( $instance['offset'] );
		if ( !isset($instance['number']) || !$number = (int) $instance['number'] )
			$number = 5; ?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'xing' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e( 'Number of posts to show:', 'xing' ); ?></label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /><br />
		<small><?php _e( '(at most 15)', 'xing' ); ?></small>
        </p>
		<p><label for="<?php echo $this->get_field_id('offset'); ?>"><?php _e( 'Posts offset', 'xing' ); ?></label>
		<input id="<?php echo $this->get_field_id('offset'); ?>" name="<?php echo $this->get_field_name('offset'); ?>" type="text" value="<?php echo $offset; ?>" size="3" /><br />
		<small><?php _e( 'Provide an offset number to which you wish to skip the posts.', 'xing' ); ?></small>
        </p>
		<p><label for="<?php echo $this->get_field_id('cats'); ?>"><?php _e( 'Cat IDs to exclude or include:', 'xing' ); ?></label>
		<input type="text" value="<?php echo $cats; ?>" name="<?php echo $this->get_field_name('cats'); ?>" id="<?php echo $this->get_field_id('cats'); ?>" class="widefat" />
		<br />
		<small><?php _e( 'Category IDs, separated by commas. Eg: 3,6,7 to include. Or -3,-6,-7 to exclude.', 'xing' ); ?></small>
		</p>
        <p><label for="<?php echo $this->get_field_id( 'hide_thumb' ); ?>"><?php _e( 'Hide Thumbnails?', 'xing' ); ?></label>
        <input class="checkbox" type="checkbox" <?php checked($instance['hide_thumb'], true) ?> id="<?php echo $this->get_field_id('hide_thumb'); ?>" name="<?php echo $this->get_field_name( 'hide_thumb' ); ?>" /><br />
        <small><?php _e( 'If unchecked, it will show post thumbnails.', 'xing' ); ?></small>
        </p>
	<?php }
}?>