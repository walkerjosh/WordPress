<?php
/* Single Post Format - Audio */

$post_opts = get_post_meta( $post->ID, 'post_options', true);
$pf_audio = !empty($post_opts['pf_audio']) ? $post_opts['pf_audio'] : 'http://www.jplayer.org/audio/ogg/Miaow-07-Bubble.ogg';
$pf_audio_type = isset($post_opts['pf_audio_type']) ? $post_opts['pf_audio_type'] : 'oga'; ?>
<div class="single-jp-wrap">
	<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery("#jp-<?php the_ID();?>").jPlayer({
				ready: function () {
					jQuery(this).jPlayer("setMedia", {
					<?php echo $pf_audio_type; ?>: "<?php echo $pf_audio; ?>"
					});
				},
				play: function() {
					jQuery(this).jPlayer("pauseOthers");
				},
				swfPath: "<?php echo get_template_directory_uri(); ?>/js",
				supplied: "<?php echo $pf_audio_type; ?>",
				cssSelectorAncestor: "#jp_container_<?php the_ID();?>",
				wmode: "window"
			});
		});
    </script>
    <div id="jp-<?php the_ID();?>" class="jp-jplayer"></div>
    <div id="jp_container_<?php the_ID();?>" class="jp-audio">
        <div class="jp-type-single">
            <div class="jp-gui jp-interface">
                <ul class="jp-controls">
                    <li><a href="javascript:;" class="jp-play" tabindex="1">play</a></li>
                    <li><a href="javascript:;" class="jp-pause" tabindex="1">pause</a></li>
                    <li><a href="javascript:;" class="jp-stop" tabindex="1">stop</a></li>
                    <li><a href="javascript:;" class="jp-mute" tabindex="1" title="mute">mute</a></li>
                    <li><a href="javascript:;" class="jp-unmute" tabindex="1" title="unmute">unmute</a></li>
                </ul>
                <div class="jp-progress">
                    <div class="jp-seek-bar">
                        <div class="jp-play-bar"></div>
                    </div>
                </div>
                <div class="jp-volume-bar">
                    <div class="jp-volume-bar-value"></div>
                </div>
                <div class="jp-time-holder">
                    <div class="jp-current-time"></div>
                    <div class="jp-duration"></div>
                </div>
            </div>
            <div class="jp-no-solution">
                <strong>Update Required</strong><br/>
                <small>To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.</small>
            </div>
        </div>
    </div>
    </div>