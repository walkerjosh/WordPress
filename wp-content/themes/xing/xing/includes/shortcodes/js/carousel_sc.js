// Carousel Short Code

(function() {
     tinymce.create('tinymce.plugins.carousel', {
        init : function(ed, url) {
             ed.addButton('carousel', {
                title : 'Insert Product Carousel',
                image : url+'/images/carousel.png',
                onclick : function() {
						var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
						W = W - 80;
						H = H - 120;
						tb_show( 'Carousel Options', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=ss_carousel-form' );
                 }
             });
         },
         createControl : function(n, cm) {
             return null;
         },
     });
	tinymce.PluginManager.add('carousel', tinymce.plugins.carousel);
	jQuery(function(){
		var form = jQuery('<div id="ss_carousel-form"><table id="ss_carousel-table" class="form-table">\
			<tr>\
				<td><h3>Options for inserting a Product Carousel</h3></td>\
			</tr>\
			<tr>\
				<th><label for="ss_carousel-c_type">Product Types</label></th>\
				<td><select name="c_type" id="ss_carousel-c_type">\
					<option value="recent">Recent Products</option>\
					<option value="featured">Featured Products</option>\
					</select><br /><br/>\
				<small>Choose product types.</small></td>\
			</tr>\
			<tr>\
				<th><label for="ss_carousel-sb_check">Are you using sidebar on this page?</label></th>\
				<td><select name="sb_check" id="ss_carousel-sb_check">\
					<option value="yes">Yes</option>\
					<option value="no">No</option>\
					</select><br /><br/>\
				<small>Choose whether you are using a sidebar. Choose No if this is a full width page.</small></td>\
			</tr>\
			<tr>\
				<th><label for="ss_carousel-speed">Carousel Sliding Speed</label></th>\
				<td><input type="text" id="ss_carousel-speed" name="speed" value="600" /><br />\
				<small>Enter carousel speed in milliseconds.</small></td>\
			</tr>\
			<tr>\
				<th><label for="ss_carousel-easing">Animation Easing</label></th>\
				<td><select name="c_type" id="ss_carousel-easing">\
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
			<input type="button" id="ss_carousel-submit" class="button-primary" value="Insert Carousel" name="submit" />\
		</p>\
		</div>');
		var table = form.find('table');
		form.appendTo('body').hide();
		form.find('#ss_carousel-submit').click(function(){
			var options = {
				'speed' : '600',
				'easing' : 'swing'
				};
			var shortcode = '[carousel';
			var inner = '';
			var c_type = table.find('#ss_carousel-c_type').val();
			var sb_check = table.find('#ss_carousel-sb_check').val();
			var columns = (sb_check == 'no') ? '5' : '4';
			for( var index in options) {
				var value = table.find('#ss_carousel-' + index).val();
				if ( value !== options[index] ) {
						shortcode += ' ' + index + '="' + value + '"';
				}
			}
			shortcode += ']<br/>';
			if( c_type == 'recent' )
				inner = '[recent_products per_page="20" columns="' + columns + '" orderby="date" order="desc"]<br/>';
				else
				inner = '[featured_products per_page="20" columns="' + columns + '" orderby="date" order="desc"]<br/>';
			shortcode += inner + '[/carousel]<br/>';
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
			tb_remove();
		});
	});
 })();