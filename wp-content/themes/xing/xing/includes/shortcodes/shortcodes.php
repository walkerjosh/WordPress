<?php
// Xing custom shortcodes
add_action( 'init', 'xing_add_shortcodes' );

function xing_add_shortcodes() {
	add_shortcode('full', 'full');
	add_shortcode('three_fourth', 'three_fourth');
	add_shortcode('three_fourth_last', 'three_fourth_last');
	add_shortcode('half', 'half');
	add_shortcode('half_last', 'half_last');
	add_shortcode('three_eighth', 'three_eighth');
	add_shortcode('three_eighth_last', 'three_eighth_last');
	add_shortcode('one_third', 'one_third');
	add_shortcode('one_third_last', 'one_third_last');
	add_shortcode('one_fourth', 'one_fourth');
	add_shortcode('one_fourth_last', 'one_fourth_last');
	add_shortcode('one_fifth', 'one_fifth');
	add_shortcode('one_fifth_last', 'one_fifth_last');
	add_shortcode('two_third', 'two_third');
	add_shortcode('two_third_last', 'two_third_last');
	add_shortcode('thumb_list', 'thumb_list');
	add_shortcode('post_list', 'post_list');
	add_shortcode('plain_list', 'plain_list');
	add_shortcode('tabs', 'tabs');
	add_shortcode('tab', 'tab');
	add_shortcode('toggle', 'toggle');
	add_shortcode('accordion', 'accordion');
	add_shortcode('acc_item', 'acc_item');
	add_shortcode('pullquote_left', 'pullquote_left');
	add_shortcode('pullquote_right', 'pullquote_right');
	add_shortcode('dropcap', 'dropcap');
	add_shortcode('box', 'box');
	add_shortcode('hr', 'hr');
	add_shortcode('dhr', 'dhr');
	add_shortcode('hr_3d', 'hr_3d');
	add_shortcode('hr_strip', 'hr_strip');
	add_shortcode('hr_dotted', 'hr_dotted');
	add_shortcode('btn', 'btn');
	add_shortcode('quote', 'quote');
	add_shortcode('slider', 'slider');
	add_shortcode('slide', 'slide');
	add_shortcode('slide_video', 'slide_video');
	add_shortcode('slide_text', 'slide_text');
	add_shortcode('carousel', 'carousel');
	add_shortcode('indicator', 'indicator');
}

function clean($pattern, $text){
	$searchfor = array('<p>'.$pattern, $pattern.'</p>', $pattern.'<br />', '<p></div>', '</div></p>', '<br /></div>', '<p><div', '</div><br />', "<br />\n</div>", "<br /><div", "<br />\n<div");
	$replacewith = array($pattern, $pattern, $pattern, '</div>', '</div>', '</div>', '<div', '</div>', '</div>', '<div', '<div');
	$out = str_replace($searchfor, $replacewith, $text);
	return $out;
}

function full( $atts, $content = null ) {
   extract( shortcode_atts( array(), $atts ) );
	$out = '<div class="full">'.do_shortcode($content).'</div>';
	return clean('<div class="full">', $out);
}

function three_fourth( $atts, $content = null ) {
   extract( shortcode_atts( array(), $atts ) );
	$out = '<div class="three_fourth">'.do_shortcode($content).'</div>';
	return clean('<div class="three_fourth">', $out);
}

function three_fourth_last( $atts, $content = null ) {
   extract( shortcode_atts( array(), $atts ) );
	$out = '<div class="three_fourth last">'.do_shortcode($content).'</div><div class="clearf"></div>';
	return clean('<div class="three_fourth last">', $out);
}

function half( $atts, $content = null ) {
   extract( shortcode_atts( array(), $atts ) );
	$out = '<div class="half">'.do_shortcode($content).'</div>';
	return clean('<div class="half">', $out);
}

function half_last( $atts, $content = null ) {
   extract( shortcode_atts( array(), $atts ) );
	$out = '<div class="half last">'.do_shortcode($content).'</div><div class="clearf"></div>';
	return clean('<div class="half last">', $out);
}

function three_eighth( $atts, $content = null ) {
   extract( shortcode_atts( array(), $atts ) );
	$out = '<div class="three_eighth">'.do_shortcode($content).'</div>';
	return clean('<div class="three_eighth">', $out);
}

function three_eighth_last( $atts, $content = null ) {
   extract( shortcode_atts( array(), $atts ) );
	$out = '<div class="three_eighth last">'.do_shortcode($content).'</div><div class="clearf"></div>';
	return clean('<div class="three_eighth last">', $out);
}

function one_third( $atts, $content = null ) {
   extract( shortcode_atts( array(), $atts ) );
	$out = '<div class="one_third">'.do_shortcode($content).'</div>';
	return clean('<div class="one_third">', $out);
}

function one_third_last( $atts, $content = null ) {
   extract( shortcode_atts( array(), $atts ) );
	$out = '<div class="one_third last">'.do_shortcode($content).'</div><div class="clearf"></div>';
	return clean('<div class="one_third last">', $out);
}

function one_fourth( $atts, $content = null ) {
   extract( shortcode_atts( array(), $atts ) );
	$out = '<div class="one_fourth">'.do_shortcode($content).'</div>';
	return clean('<div class="one_fourth">', $out);
}

function one_fourth_last( $atts, $content = null ) {
   extract( shortcode_atts( array(), $atts ) );
	$out = '<div class="one_fourth last">'.do_shortcode($content).'</div><div class="clearf"></div>';
	return clean('<div class="one_fourth last">', $out);
}

function one_fifth( $atts, $content = null ) {
   extract( shortcode_atts( array(), $atts ) );
	$out = '<div class="one_fifth">'.do_shortcode($content).'</div>';
	return clean('<div class="one_fifth">', $out);
}

function one_fifth_last( $atts, $content = null ) {
   extract( shortcode_atts( array(), $atts ) );
	$out = '<div class="one_fifth last">'.do_shortcode($content).'</div><div class="clearf"></div>';
	return clean('<div class="one_fifth last">', $out);
}

function two_third( $atts, $content = null ) {
   extract( shortcode_atts( array(), $atts ) );
	$out = '<div class="two_third">'.do_shortcode($content).'</div>';
	return clean('<div class="two_third">', $out);
}

function two_third_last( $atts, $content = null ) {
   extract( shortcode_atts( array(), $atts ) );
	$out = '<div class="two_third last">'.do_shortcode($content).'</div><div class="clearf"></div>';
	return clean('<div class="two_third last">', $out);
}

function tabs( $atts, $content = null ) {
   extract( shortcode_atts( array(), $atts ) );
	$out = '<div class="tabber">'.do_shortcode($content).'</div>';
	return clean('<div class="tabber">', $out);
}

function tab( $atts, $content = null ) {
   extract( shortcode_atts( array(
      'title' => 'mytab'
      ), $atts ) );
	$tab_id = 'tab-'.rand(2,20000);
	$out = '<div class="tabbed" id="'.$tab_id.'"><h4 class="tab_title">'.$title.'</h4>'.do_shortcode($content).'</div>';
	return clean('</h4>', $out);
}

function toggle( $atts, $content = null ) {
   extract( shortcode_atts( array(
      'title' => 'mytoggle'
      ), $atts ) );
	$out = '<h5 class="toggle"><span></span>'.$title.'</h5><div class="toggle_content">'.do_shortcode($content).'</div>';
	return clean('<div class="toggle_content">', $out);
}

function accordion( $atts, $content = null ) {
   extract( shortcode_atts( array(), $atts ) );
	$out = '<div class="accordion">'.do_shortcode($content).'</div>';
	$out = clean('<h5', $out);
	return clean('<div class="accordion">', $out);
}

function acc_item( $atts, $content = null ) {
   extract( shortcode_atts( array(
      'title' => 'myaccordion'
      ), $atts ) );
	$out = '<h5 class="handle">'.$title.'<span></span></h5><div class="acc_content"><div class="acc_inner">'.do_shortcode($content).'</div></div>';
	return clean('<div class="acc_inner">', $out);
}

function quote( $atts, $content = null ) {
   extract( shortcode_atts( array(), $atts ) );
	$out = '<div class="quote">'.do_shortcode($content).'</div>';
	return clean('<div class="quote">', $out);
}

function pullquote_left( $atts, $content = null ) {
   extract( shortcode_atts( array(), $atts ) );
	return '<span class="pqleft">'.do_shortcode($content).'</span>';
}

function pullquote_right( $atts, $content = null ) {
   extract( shortcode_atts( array(), $atts ) );
	return '<span class="pqright">'.do_shortcode($content).'</span>';
}

function dropcap( $atts, $content = null ) {
   extract( shortcode_atts( array(
      'style' => 'normal',
      ), $atts ) );
	$class = ($style == 'inverted') ? 'dropcap inverted' : 'dropcap';
	return '<span class="'.$class.'">'.do_shortcode($content).'</span>';
}

function box( $atts, $content = null ) {
   extract( shortcode_atts( array(
      'style' => '0',
      ), $atts ) );
	if( $style == '0' ) $class = 'box0';
	if( $style == '1' ) $class = 'box1';
	if( $style == '2' ) $class = 'box2';
	if( $style == '3' ) $class = 'box3';
	if( $style == '4' ) $class = 'box4';
	$out = '<div class="box '.$class.'">'.do_shortcode($content).'</div>';
	return clean('<div class="box '.$class.'">', $out);
}

function btn( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'link' => '',
		'color' => '',
		'size' => '',
		'target' => '_self',
	), $atts ) );
	$color_class = ( $color == '' ) ? '' : $color;
	$btn_class = ( $color == '' ) ? 'btn' : 'btn2';
	$size_class = ( ( $size != '' ) ) ? $size : '';
	if($target == '_blank') {
		return '<a href="'.$link.'" class="'.$btn_class.' '.$color_class.' '.$size_class.'" target="_blank">'.do_shortcode($content).'</a>';
	}
	else
	{
		return '<a href="'.$link.'" class="'.$btn_class.' '.$color_class.' '.$size_class.'">'.do_shortcode($content).'</a>';
	}
}

function post_list( $atts ) {
	extract( shortcode_atts( array(
		'query_type' => 'category',
		'cats' => '1',
		'posts' => '',
		'pages' => '',
		'order' => 'desc',
		'orderby' => 'date',
		'num' => '2',
		'offset' => '0',
	), $atts ) );
	$sq = '';
	if($query_type == 'posts') {
		$sq = new WP_Query(array('posts_per_page' => $num, 'post_status' => 'publish', 'order' => $order, 'orderby' => $orderby, 'ignore_sticky_posts' => 1, 'post__in' => explode(',', $posts), 'offset' => $offset));
	}
	elseif($query_type == 'pages') {
		$sq = new WP_Query(array('post_type' => 'page', 'posts_per_page' => $num, 'post_status' => 'publish', 'order' => $order, 'orderby' => $orderby, 'ignore_sticky_posts' => 1, 'post__in' => explode(',', $pages), 'offset' => $offset));
	}
	else {
		$sq = new WP_Query(array('posts_per_page' => $num, 'post_status' => 'publish', 'order' => $order, 'orderby' => $orderby, 'ignore_sticky_posts' => 1, 'cat' => $cats, 'offset' => $offset));
	}
	if ($sq->have_posts()) :
		$out = '<ul class="post_list">';
		while ($sq->have_posts()) : $sq->the_post();
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
			$format = '<li><a class="pl_thumb" href="%3$s" rel="bookmark" title="%4$s"><img src="%2$s" alt="%4$s"/></a><div class="pl_title"><h4><a href="%3$s" rel="bookmark" title="%4$s">%4$s</a></h4><span class="list_meta">%5$s</span></div></li>';
			$out .= sprintf ($format, $bloginfo, $thumbnail, $permalink, $title, $time);
		endwhile;
		$out .= '</ul>';
		return clean('<ul class="post_list">', $out);
	endif;
	wp_reset_postdata(); // Restore global post data stomped by the_post().
}

function plain_list( $atts ) {
	extract( shortcode_atts( array(
		'query_type' => 'category',
		'cats' => '1',
		'posts' => '',
		'pages' => '',
		'order' => 'desc',
		'orderby' => 'date',
		'num' => '2',
		'offset' => '0',
	), $atts ) );
	$sq = '';
	if($query_type == 'posts') {
		$sq = new WP_Query(array('posts_per_page' => $num, 'post_status' => 'publish', 'order' => $order, 'orderby' => $orderby, 'ignore_sticky_posts' => 1, 'post__in' => explode(',', $posts), 'offset' => $offset));
	}
	elseif($query_type == 'pages') {
		$sq = new WP_Query(array('post_type' => 'page', 'posts_per_page' => $num, 'post_status' => 'publish', 'order' => $order, 'orderby' => $orderby, 'ignore_sticky_posts' => 1, 'post__in' => explode(',', $pages), 'offset' => $offset));
	}
	else {
		$sq = new WP_Query(array('posts_per_page' => $num, 'post_status' => 'publish', 'order' => $order, 'orderby' => $orderby, 'ignore_sticky_posts' => 1, 'cat' => $cats, 'offset' => $offset));
	}
	if ($sq->have_posts()) :
		$out = '<ul class="plain_list">';
		while ($sq->have_posts()) : $sq->the_post();
			$time = get_the_time(get_option('date_format'));
			$permalink = get_permalink();
			$title = get_the_title();
			$bloginfo = get_template_directory_uri();
			if ( has_post_thumbnail()) {
				$img_src = wp_get_attachment_image_src( get_post_thumbnail_id($GLOBALS['post']->ID), '');
				$thumbnail = $img_src[0];
			}
			else $thumbnail = '';
			$default_thumb = $bloginfo.'/images/post_thumb.jpg';
			$thumbnail = ( $thumbnail == '' ) ? $default_thumb : $thumbnail;
			$format = '<li><a href="%1$s" rel="bookmark" title="%2$s">%2$s</a></li>';
			$out .= sprintf ($format, $permalink, $title );
		endwhile;
		$out .= '</ul>';
		return clean('<ul class="plain_list">', $out);
	endif;
	wp_reset_postdata(); // Restore global post data stomped by the_post().
}

function hr() {
	return '<div class="hr"></div>';
}

function dhr() {
	return '<div class="double_hr"></div>';
}

function hr_3d() {
	return '<div class="hr_3d"></div>';
}

function hr_strip() {
	return '<div class="hr_strip"></div>';
}

function hr_dotted() {
	return '<div class="hr_dotted"></div>';
}

function indicator( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'label' => 'Label here',
		'bg' => '#ffcc00',
		'value' => '75',
	), $atts ) );
	if($value < 0)
		$value = 0;
	elseif($value > 100)
		$value = 100;
return '<div class="p_bar"><div class="p_label">'.$label.'</div><div class="p_indicator"><div class="p_active" style="width:'.$value.'%; background:'.$bg.'"></div></div><div class="p_value">'.$value.'%</div></div>';
}

function carousel( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'speed' => '600',
		'easing' => 'swing'
	), $atts ));
	$carousel_id = 'carousel-'.rand(5,400000);
	$out = "<script type=\"text/javascript\">jQuery(document).ready(function(){ jQuery('#".$carousel_id." ul.products').jcarousel({ easing:'".$easing."', animation:".$speed.", scroll:1 });})</script>";
	$out .= "<div id=\"".$carousel_id."\" class=\"ss_carousel\">".do_shortcode($content)."</div>";
	return clean('class="ss_carousel">', $out);
}

function slider( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'effect' => 'fade',
		'easing' => 'swing',
		'speed' => '600',
		'timeout' => '4000'
	), $atts ));
	$slider_id = 'slider-'.rand(2,400000);
$out = "<script type=\"text/javascript\">
		jQuery(window).load(function(){
			var vimeoPlayers = jQuery('#".$slider_id."').find('iframe');
			jQuery(vimeoPlayers).each(function(){
				Froogaloop(this).addEvent('ready', ready);
			});
			function ready(player_id) {
				Froogaloop(player_id).addEvent('play', function(data) {
					jQuery('#".$slider_id."').flexslider(\"pause\");
				});
				Froogaloop(player_id).addEvent('pause', function(data) {
					jQuery('#".$slider_id."').flexslider(\"play\");
				});
			}
			jQuery('#".$slider_id."').flexslider({
				animation:\"".$effect."\",
				easing:\"".$easing."\",
				animationSpeed:".$speed.",
				slideshowSpeed:".$timeout.",
				selector:\".slides > .slide\",
				pauseOnAction:true,
				smoothHeight:true,
				directionNav:false,
				useCSS:false,
				start: function(slider) {
					jQuery(slider).removeClass('flex-loading');
					var animateSlide = slider.slides.eq(slider.currentSlide);
					var description = jQuery(animateSlide).find('.caption').html();
					if(description) {
						jQuery('.flex-caption > .flex-caption-inner').html(description).parent().animate({bottom:'0px', left: '0px'},".$speed.",'".$easing."');
					}
				},
				before: function(slider) {
					if (slider.slides.eq(slider.currentSlide).find('iframe').length !== 0)
						Froogaloop( slider.slides.eq(slider.currentSlide).find('iframe').attr('id') ).api('pause');
					var heightShift = 0 - jQuery('.flex-caption').height() - 20;
					jQuery('.flex-caption > .flex-caption-inner').parent().animate({bottom:heightShift, left: '0px'},".$speed.",'".$easing."');
				},
				after: function(slider) {
					var animateSlide = slider.slides.eq(slider.currentSlide);
					var description = jQuery(animateSlide).find('.caption').html();
					if(description) {
						jQuery('.flex-caption > .flex-caption-inner').html(description).parent().animate({bottom:'0px', left: '0px'},".$speed.",'".$easing."');
					}
				}
			});
		})
	</script>";
	$out .= "<div class=\"flexslider flex-loading\" id=\"".$slider_id."\"><div class=\"slides\">".do_shortcode($content)."</div><div class=\"flex-caption\"><div class=\"flex-caption-inner\"></div></div></div>";
	return clean('<div class="slides">', $out);
}

function slide( $atts, $content = null ) {
	extract( shortcode_atts( array(), $atts ) );
	$out = '<div class="slide">'.do_shortcode($content).'</div>';
	return clean('<div class="slide">', $out);
}
function slide_video( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'src' => ''
		), $atts ));
	if($src != '') {
	$player_id = 'player_'.rand(10,400000);
	$out = '<div class="slide"><div class="embed_wrap"><iframe id="'.$player_id.'" src="http://player.vimeo.com/video/'.$src.'?api=1&player_id='.$player_id.'"></iframe></div></div>';
	}
	else $out = '';
	return $out;
}
function slide_text( $atts, $content = null ) {
	extract( shortcode_atts( array(), $atts ) );
	$out = '<div class="caption">'.do_shortcode($content).'</div>';
	return clean('<div class="caption">', $out);
}?>