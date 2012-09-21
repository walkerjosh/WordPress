<?php
/* Display Social Networking Icons with Links. */

class xing_Social_Widget extends WP_Widget {
	function xing_Social_Widget() {
		$widget_ops = array( 'classname' => 'xing_social', 'description' => __( 'A social networking icons widget.', 'xing') );
		$this->WP_Widget( 'xing-social', __( 'Xing Social Icons', 'xing' ), $widget_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base);
		$twitter_url = $instance['twitter_url'];
		$twitter = isset($instance['twitter']) ? $instance['twitter'] : false;
		$facebook_url = $instance['facebook_url'];
		$facebook = isset($instance['facebook']) ? $instance['facebook'] : false;
		$in_url = $instance['in_url'];
		$in = isset($instance['in']) ? $instance['in'] : false;
		$gplus_url = $instance['gplus_url'];
		$gplus = isset($instance['gplus']) ? $instance['gplus'] : false;
		$dribble_url = $instance['dribble_url'];
		$dribble = isset($instance['dribble']) ? $instance['dribble'] : false;
		$forrst_url = $instance['forrst_url'];
		$forrst = isset($instance['forrst']) ? $instance['forrst'] : false;
		$flickr_url = $instance['flickr_url'];
		$flickr = isset($instance['flickr']) ? $instance['flickr'] : false;
		$deviant_url = $instance['deviant_url'];
		$deviant = isset($instance['deviant']) ? $instance['deviant'] : false;
		$vimeo_url = $instance['vimeo_url'];
		$vimeo = isset($instance['vimeo']) ? $instance['vimeo'] : false;
		$utube_url = $instance['utube_url'];
		$utube = isset($instance['utube']) ? $instance['utube'] : false;
		$pint_url = $instance['pint_url'];
		$pint = isset($instance['pint']) ? $instance['pint'] : false;
		$rss_url = !empty($instance['rss_url']) ? $instance['rss_url'] : get_bloginfo('rss2_url');
		$rss = isset($instance['rss']) ? $instance['rss'] : false;
		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;

		/* Start output */
		$output = '';
		?>
        <ul class="ss_social clearfix">
		<?php
		if ( $twitter )
			$output .= '<li><a href="'.$twitter_url.'" class="twitter" title="Twitter" target="_blank"></a></li>';
		if ( $facebook )
			$output .= '<li><a href="'.$facebook_url.'" class="facebook" title="Facebook" target="_blank"></a></li>';
		if ( $in )
			$output .= '<li><a href="'.$in_url.'" class="in" title="LinkedIn" target="_blank"></a></li>';
		if ( $gplus )
			$output .= '<li><a href="'.$gplus_url.'" class="gplus" title="Google Plus" target="_blank"></a></li>';
		if ( $dribble )
			$output .= '<li><a href="'.$dribble_url.'" class="dribble" title="Dribble" target="_blank"></a></li>';
		if ( $forrst )
			$output .= '<li><a href="'.$forrst_url.'" class="forrst" title="Forrst" target="_blank"></a></li>';
		if ( $flickr )
			$output .= '<li><a href="'.$flickr_url.'" class="flickr" title="Flickr" target="_blank"></a></li>';
		if ( $deviant )
			$output .= '<li><a href="'.$deviant_url.'" class="deviant" title="DeviantArt" target="_blank"></a></li>';
		if ( $vimeo )
			$output .= '<li><a href="'.$vimeo_url.'" class="vimeo" title="Vimeo" target="_blank"></a></li>';
		if ( $utube )
			$output .= '<li><a href="'.$utube_url.'" class="utube" title="YouTube" target="_blank"></a></li>';
		if ( $pint )
			$output .= '<li><a href="'.$pint_url.'" class="pint" title="Pinterest" target="_blank"></a></li>';
		if ( $rss )
			$output .= '<li><a href="'.$rss_url.'" class="rss" title="RSS" target="_blank"></a></li>';
		echo ( $output.'</ul>' );
		echo $after_widget;
	}

	/* Update the widget settings. */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['twitter_url'] = strip_tags( $new_instance['twitter_url'] );
		$instance['twitter'] = isset($new_instance['twitter']) ? true : false;
		$instance['facebook_url'] = strip_tags( $new_instance['facebook_url'] );
		$instance['facebook'] = isset($new_instance['facebook']) ? true : false;
		$instance['in_url'] = strip_tags( $new_instance['in_url'] );
		$instance['in'] = isset($new_instance['in']) ? true : false;
		$instance['gplus_url'] = strip_tags( $new_instance['gplus_url'] );
		$instance['gplus'] = isset($new_instance['gplus']) ? true : false;
		$instance['dribble_url'] = strip_tags( $new_instance['dribble_url'] );
		$instance['dribble'] = isset($new_instance['dribble']) ? true : false;
		$instance['forrst_url'] = strip_tags( $new_instance['forrst_url'] );
		$instance['forrst'] = isset($new_instance['forrst']) ? true : false;
		$instance['flickr_url'] = strip_tags( $new_instance['flickr_url'] );
		$instance['flickr'] = isset($new_instance['flickr']) ? true : false;
		$instance['deviant_url'] = strip_tags( $new_instance['deviant_url'] );
		$instance['deviant'] = isset($new_instance['deviant']) ? true : false;
		$instance['vimeo_url'] = strip_tags( $new_instance['vimeo_url'] );
		$instance['vimeo'] = isset($new_instance['vimeo']) ? true : false;
		$instance['utube_url'] = strip_tags( $new_instance['utube_url'] );
		$instance['utube'] = isset($new_instance['utube']) ? true : false;
		$instance['pint_url'] = strip_tags( $new_instance['pint_url'] );
		$instance['pint'] = isset($new_instance['pint']) ? true : false;
		$instance['rss_url'] = strip_tags( $new_instance['rss_url'] );
		$instance['rss'] = isset($new_instance['rss']) ? true : false;

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'twitter' => false, 'twitter_url' => '', 'facebook' => false, 'facebook_url' => '', 'in' => false, 'in_url' => '', 'gplus' => false, 'gplus_url' => '', 'dribble' => false, 'dribble_url' => '', 'forrst' => false, 'forrst_url' => '', 'flickr' => false, 'flickr_url' => '', 'deviant' => false, 'deviant_url' => '', 'vimeo' => false, 'vimeo_url' => '', 'utube' => false, 'utube_url' => '', 'pint' => false, 'pint_url' => '', 'rss' => false, 'rss_url' => '' ) );
		$title = esc_attr( $instance['title'] );
		$twitter_url = esc_attr( $instance['twitter_url'] );
		$facebook_url = esc_attr( $instance['facebook_url'] );
		$in_url = esc_attr( $instance['in_url'] );
		$gplus_url = esc_attr( $instance['gplus_url'] );
		$dribble_url = esc_attr( $instance['dribble_url'] );
		$forrst_url = esc_attr( $instance['forrst_url'] );
		$flickr_url = esc_attr( $instance['flickr_url'] );
		$deviant_url = esc_attr( $instance['deviant_url'] );
		$vimeo_url = esc_attr( $instance['vimeo_url'] );
		$utube_url = esc_attr( $instance['utube_url'] );
		$pint_url = esc_attr( $instance['pint_url'] );
		$rss_url = esc_attr( $instance['rss_url'] );
		?>

        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'xing' ); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('twitter'); ?>"><?php _e( 'Twitter', 'xing' ); ?></label>
            <input class="checkbox" type="checkbox" <?php checked($instance['twitter'], true) ?> id="<?php echo $this->get_field_id('twitter'); ?>" name="<?php echo $this->get_field_name('twitter'); ?>" /><br />
            <input type="text" value="<?php echo $twitter_url; ?>" name="<?php echo $this->get_field_name('twitter_url'); ?>" id="<?php echo $this->get_field_id('twitter_url'); ?>" class="widefat" />
            <br />
            <small><?php _e( 'Full URL of Twitter profile', 'xing' ); ?>
            </small>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('facebook'); ?>"><?php _e( 'Facebook', 'xing' ); ?></label>
            <input class="checkbox" type="checkbox" <?php checked($instance['facebook'], true) ?> id="<?php echo $this->get_field_id('facebook'); ?>" name="<?php echo $this->get_field_name('facebook'); ?>" /><br />
            <input type="text" value="<?php echo $facebook_url; ?>" name="<?php echo $this->get_field_name('facebook_url'); ?>" id="<?php echo $this->get_field_id('facebook_url'); ?>" class="widefat" />
            <br />
            <small><?php _e( 'Full URL of Facebook profile', 'xing' ); ?>
            </small>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('in'); ?>"><?php _e( 'LinkedIn', 'xing' ); ?></label>
            <input class="checkbox" type="checkbox" <?php checked($instance['in'], true) ?> id="<?php echo $this->get_field_id('in'); ?>" name="<?php echo $this->get_field_name('in'); ?>" /><br />
            <input type="text" value="<?php echo $in_url; ?>" name="<?php echo $this->get_field_name('in_url'); ?>" id="<?php echo $this->get_field_id('in_url'); ?>" class="widefat" />
            <br />
            <small><?php _e( 'Full URL to LinkedIn Profile', 'xing' ); ?></small>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('gplus'); ?>"><?php _e( 'Google+', 'xing' ); ?></label>
            <input class="checkbox" type="checkbox" <?php checked($instance['gplus'], true) ?> id="<?php echo $this->get_field_id('gplus'); ?>" name="<?php echo $this->get_field_name('gplus'); ?>" /><br />
            <input type="text" value="<?php echo $gplus_url; ?>" name="<?php echo $this->get_field_name('gplus_url'); ?>" id="<?php echo $this->get_field_id('gplus_url'); ?>" class="widefat" />
            <br />
            <small><?php _e( 'Full URL to Google+ Profile', 'xing' ); ?></small>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('dribble'); ?>"><?php _e( 'Dribble', 'xing' ); ?></label>
            <input class="checkbox" type="checkbox" <?php checked($instance['dribble'], true) ?> id="<?php echo $this->get_field_id('dribble'); ?>" name="<?php echo $this->get_field_name('dribble'); ?>" /><br />
            <input type="text" value="<?php echo $dribble_url; ?>" name="<?php echo $this->get_field_name('dribble_url'); ?>" id="<?php echo $this->get_field_id('dribble_url'); ?>" class="widefat" />
            <br />
            <small><?php _e( 'Full URL to Dribble', 'xing' ); ?>
            </small>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('forrst'); ?>"><?php _e( 'Forrst', 'xing' ); ?></label>
            <input class="checkbox" type="checkbox" <?php checked($instance['forrst'], true) ?> id="<?php echo $this->get_field_id('forrst'); ?>" name="<?php echo $this->get_field_name('forrst'); ?>" /><br />
            <input type="text" value="<?php echo $forrst_url; ?>" name="<?php echo $this->get_field_name('forrst_url'); ?>" id="<?php echo $this->get_field_id('forrst_url'); ?>" class="widefat" />
            <br />
            <small><?php _e( 'Full URL to Forrst Profile', 'xing' ); ?>
            </small>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('flickr'); ?>"><?php _e( 'Flickr', 'xing' ); ?></label>
            <input class="checkbox" type="checkbox" <?php checked($instance['flickr'], true) ?> id="<?php echo $this->get_field_id('flickr'); ?>" name="<?php echo $this->get_field_name('flickr'); ?>" /><br />
            <input type="text" value="<?php echo $flickr_url; ?>" name="<?php echo $this->get_field_name('flickr_url'); ?>" id="<?php echo $this->get_field_id('flickr_url'); ?>" class="widefat" />
            <br />
            <small><?php _e( 'Full URL of Flickr Photostream', 'xing' ); ?>
            </small>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('deviant'); ?>"><?php _e( 'DeviantArt', 'xing' ); ?></label>
            <input class="checkbox" type="checkbox" <?php checked($instance['deviant'], true) ?> id="<?php echo $this->get_field_id('deviant'); ?>" name="<?php echo $this->get_field_name('deviant'); ?>" /><br />
            <input type="text" value="<?php echo $deviant_url; ?>" name="<?php echo $this->get_field_name('deviant_url'); ?>" id="<?php echo $this->get_field_id('deviant_url'); ?>" class="widefat" />
            <br />
            <small><?php _e( 'Full URL to DeviantArt Profile', 'xing' ); ?>
            </small>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('vimeo'); ?>"><?php _e( 'Vimeo', 'xing' ); ?></label>
            <input class="checkbox" type="checkbox" <?php checked($instance['vimeo'], true) ?> id="<?php echo $this->get_field_id('vimeo'); ?>" name="<?php echo $this->get_field_name('vimeo'); ?>" /><br />
            <input type="text" value="<?php echo $vimeo_url; ?>" name="<?php echo $this->get_field_name('vimeo_url'); ?>" id="<?php echo $this->get_field_id('vimeo_url'); ?>" class="widefat" />
            <br />
            <small><?php _e( 'Full URL to Vimeo Profile', 'xing' ); ?>
            </small>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('utube'); ?>"><?php _e( 'YouTube', 'xing' ); ?></label>
            <input class="checkbox" type="checkbox" <?php checked($instance['utube'], true) ?> id="<?php echo $this->get_field_id('utube'); ?>" name="<?php echo $this->get_field_name('utube'); ?>" /><br />
            <input type="text" value="<?php echo $utube_url; ?>" name="<?php echo $this->get_field_name('utube_url'); ?>" id="<?php echo $this->get_field_id('utube_url'); ?>" class="widefat" />
            <br />
            <small><?php _e( 'Full URL to YouTube Profile', 'xing' ); ?>
            </small>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('pint'); ?>"><?php _e( 'Pinterest', 'xing' ); ?></label>
            <input class="checkbox" type="checkbox" <?php checked($instance['pint'], true) ?> id="<?php echo $this->get_field_id('pint'); ?>" name="<?php echo $this->get_field_name('pint'); ?>" /><br />
            <input type="text" value="<?php echo $pint_url; ?>" name="<?php echo $this->get_field_name('pint_url'); ?>" id="<?php echo $this->get_field_id('pint_url'); ?>" class="widefat" />
            <br />
            <small><?php _e( 'Full URL to Pinterest', 'xing' ); ?>
            </small>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('rss'); ?>"><?php _e( 'RSS', 'xing' ); ?></label>
            <input class="checkbox" type="checkbox" <?php checked($instance['rss'], true) ?> id="<?php echo $this->get_field_id('rss'); ?>" name="<?php echo $this->get_field_name('rss'); ?>" />
            <input type="text" value="<?php echo $rss_url; ?>" name="<?php echo $this->get_field_name('rss_url'); ?>" id="<?php echo $this->get_field_id('rss_url'); ?>" class="widefat" />
            <br />
            <small><?php _e( 'Optional RSS URL. If left blank, default rss2 URL will be used.', 'xing' ); ?>
            </small>
        </p>
	<?php
	}
}?>