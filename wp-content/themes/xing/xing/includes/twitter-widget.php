<?php
/* Display tweets from your twitter username */

class xing_Twitter_Widget extends WP_Widget {
	function xing_Twitter_Widget() {
		$widget_ops = array( 'classname' => 'xing_twitter', 'description' => __( 'Display your recent tweets with this custom widget.', 'xing' ) );
		$this->WP_Widget( 'xing-twitter', __( 'Xing Twitter Widget', 'xing' ), $widget_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base);
		$user_ID = $instance['user_ID'];
		if ( !$number = (int) $instance['number'] )
			$number = 1;
		else if ( $number < 1 )
			$number = 1;
		$text = $instance['text'];

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;

		/* Start output */
		?>
        <div id="twitter_wrapper">
        <?php
		echo $text; ?>
		<ul id="twitter_update_list"><li></li></ul>
		<script type="text/javascript" src="http://twitter.com/javascripts/blogger.js"></script>
		<script type="text/javascript" src="http://twitter.com/statuses/user_timeline/<?php echo $user_ID; ?>.json?callback=twitterCallback2&count=<?php echo $number; ?>"></script>
        </div>
	<?php	/* End output */
		echo $after_widget;
	}

	/* Update the widget settings. */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['user_ID'] = strip_tags( $new_instance['user_ID'] );
		$instance['number'] = (int) $new_instance['number'];
		if ( current_user_can('unfiltered_html') )
			$instance['text'] =  $new_instance['text'];
		else
			$instance['text'] = stripslashes( $new_instance['text'] );
		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'user_ID' => 'xconsau', 'text' => '' ) );
		if ( !isset($instance['number']) || !$number = (int) $instance['number'] )
		$number = 1;
		$title = esc_attr( $instance['title'] );
		$user_ID = esc_attr( $instance['user_ID'] );
		$text = format_to_edit($instance['text']);
		?>

        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'xing' ); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p><label for="<?php echo $this->get_field_id('user_ID'); ?>"><?php _e( 'Twitter Username:', 'xing' ); ?></label> <input type="text" value="<?php echo $user_ID; ?>" name="<?php echo $this->get_field_name('user_ID'); ?>" id="<?php echo $this->get_field_id('user_ID'); ?>" class="widefat" /><br />
        <small><?php _e( 'Your twitter username. Eg: <em>johndoe</em>', 'xing' ); ?></small>
        </p>
		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e( 'Number of tweets to show:', 'xing' ); ?></label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" />
		</p>
		<p><label for="<?php echo $this->get_field_id('text'); ?>"><?php _e( 'Text to appear before tweets:', 'xing' ); ?></label>
        <textarea class="widefat" rows="5" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea><br />
		<small><?php _e( 'You can use basic HTML here.', 'xing' ); ?></small>
		</p>
	<?php
	}
}?>