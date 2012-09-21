// Slider Short Code

(function() {
     tinymce.create('tinymce.plugins.slider', {
        init : function(ed, url) {
             ed.addButton('slider', {
                title : 'Insert Slider',
                image : url+'/images/slider.png',
                onclick : function() {
						var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
						W = W - 80;
						H = H - 120;
						tb_show( 'Slider Options', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=ss_slider-form' );
                 }
             });
         },
         createControl : function(n, cm) {
             return null;
         },
     });
	tinymce.PluginManager.add('slider', tinymce.plugins.slider);
	jQuery(function(){
		var form = jQuery('<div id="ss_slider-form"><table id="ss_slider-table" class="form-table">\
			<tr>\
				<td><h3>Options for inserting a Slider</h3></td>\
			</tr>\
			<tr>\
				<th><label for="ss_slider-num">Number of slides to show</label></th>\
				<td><input type="text" id="ss_slider-num" name="num" value="3" /><br />\
				<small>Enter number of slides to show. Example: 4</small></td>\
			</tr>\
			<tr>\
				<th><label for="ss_slider-video">Include sample Vimeo slide?</label></th>\
				<td><select name="video" id="ss_slider-video">\
					<option value="yes">Yes</option>\
					<option value="no">No</option>\
					</select><br /><br/>\
				<small>Choose to insert a sample Vimeo video slide. You can duplicate the slide code to insert more videos. Replace video ID with the actual video ID on Vimeo.</small></td>\
			</tr>\
			<tr>\
				<th><label for="ss_slider-effect">Effect</label></th>\
				<td><select name="effect" id="ss_slider-effect">\
					<option value="fade">Fade</option>\
					<option value="slide">Slide</option>\
					</select><br /><br/>\
				<small>Select animation effect for slides.</small></td>\
			</tr>\
			<tr>\
				<th><label for="ss_slider-speed">Animation speed</label></th>\
				<td><input type="text" id="ss_slider-speed" name="speed" value="600" /><br />\
				<small>Enter an animation speed in milliseconds.</small></td>\
			</tr>\
			<tr>\
				<th><label for="ss_slider-timeout">Slides timeout</label></th>\
				<td><input type="text" id="ss_slider-timeout" name="timeout" value="4000" /><br />\
				<small>Enter a time in milliseconds, for how long a slide should stay.</small></td>\
			</tr>\
			<tr>\
				<th><label for="ss_slider-easing">Animation Easing</label></th>\
				<td><select name="c_type" id="ss_slider-easing">\
						<option value="swing">swing</option>\
						<option value="easeInQuad">easeInQuad</option>\
						<option value="easeOutQuad">easeOutQuad</option>\
						<option value="easeInOutQuad">easeInOutQuad</option>\
						<option value="easeInExpo">easeInExpo</option>\
						<option value="easeOutExpo">easeOutExpo</option>\
						<option value="easeInOutExpo">easeInOutExpo</option>\
					</select><br /><br/>\
				<small>Choose easing method for animation.</small></td>\
			</tr>\
			</table>\
		<p class="submit">\
			<input type="button" id="ss_slider-submit" class="button-primary" value="Insert Slider" name="submit" />\
		</p>\
		</div>');
		var table = form.find('table');
		form.appendTo('body').hide();
		form.find('#ss_slider-submit').click(function(){
			var options = {
				'effect' : 'fade',
				'easing' : 'swing',
				'speed' : '600',
				'timeout' : '4000'
				};
			var shortcode = '[slider';
			var inner = '';
			var num = table.find('#ss_slider-num').val();
			var video_check = table.find('#ss_slider-video').val();
			for( var index in options) {
				var value = table.find('#ss_slider-' + index).val();
				if ( value !== options[index] ) {
						shortcode += ' ' + index + '="' + value + '"';
				} // value
			}
			shortcode += ']<br/>';
			for( var i=1; i<= num; i++) {
				inner += '[slide]<br/><img src="wp-content/themes/xing/images/user/slide.jpg"/><br/>[slide_text]<h2>Slide Caption ' + i + '</h2>[/slide_text]<br/>[/slide]<br/>';
			}
			if(video_check == 'yes') {
				video_slide = '[slide_video src="39683393"]<br/>';
				shortcode += inner + video_slide + '[/slider]<br/>';
			}
			else
			 shortcode += inner + '[/slider]<br/>';
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
			tb_remove();
		});
	});
 })();