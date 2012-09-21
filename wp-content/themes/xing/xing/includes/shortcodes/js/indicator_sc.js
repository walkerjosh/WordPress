// Indicator Short Code

(function() {
     tinymce.create('tinymce.plugins.indicator', {
        init : function(ed, url) {
             ed.addButton('indicator', {
                title : 'Insert an Indicator',
                image : url+'/images/indicator.png',
                onclick : function() {
						var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
						W = W - 80;
						H = H - 84;
						tb_show( 'Indicator Options', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=ss_indicator-form' );
                 }
             });
         },
         createControl : function(n, cm) {
             return null;
         },
     });
	tinymce.PluginManager.add('indicator', tinymce.plugins.indicator);
	jQuery(function(){
		var form = jQuery('<div id="ss_indicator-form"><table id="ss_indicator-table" class="form-table">\
			<tr>\
				<td><h3>Options for inserting an Indicator</h3></td>\
			</tr>\
			<tr>\
				<th><label for="ss_indicator-label">Indicator Label</label></th>\
				<td><input type="text" id="ss_indicator-label" name="text" value="" /><br />\
				<small>Enter a label for indicator.</small></td>\
			</tr>\
			<tr>\
				<th><label for="ss_indicator-bg">Background Color</label></th>\
				<td><input type="text" id="ss_indicator-bg" name="link" value="#ffcc00" /><br />\
				<small>Enter a background color value. Example: #003366</small></td>\
			</tr>\
			<tr>\
				<th><label for="ss_indicator-value">Indicator value</label></th>\
				<td><input type="text" id="ss_indicator-value" name="link" value="" /><br />\
				<small>Enter a value for indicator. Allowed values are 0 to 100.</small></td>\
			</tr>\
			</table>\
		<p class="submit">\
			<input type="indicator" id="ss_indicator-submit" class="button-primary" value="Insert Indicator" name="submit" />\
		</p>\
		</div>');
		var table = form.find('table');
		form.appendTo('body').hide();
		form.find('#ss_indicator-submit').click(function(){
			var options = {
				'label' : '',
				'bg' : '#ffcc00',
				'value' : ''
				};
			var shortcode = '[indicator';
			for( var index in options) {
				var value = table.find('#ss_indicator-' + index).val();
				if ( value !== options[index] )
					shortcode += ' ' + index + '="' + value + '"';
			}
			shortcode += ']';
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
			tb_remove();
		});
	});
 })();