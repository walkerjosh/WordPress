// Layout Column Short Codes

(function() {
     tinymce.create('tinymce.plugins.columns', {
        init : function(ed, url) {
             ed.addButton('columns', {
                title : 'Insert layout columns',
                image : url+'/images/layout.png',
                onclick : function() {
						var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
						W = W - 80;
						H = H - 84;
						tb_show( 'Layout Column Options', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=ss_columns-form' );
                 }
             });
         },
         createControl : function(n, cm) {
             return null;
         },
     });
	tinymce.PluginManager.add('columns', tinymce.plugins.columns);
	jQuery(function(){
		var form = jQuery('<div id="ss_columns-form"><table id="ss_columns-table" class="form-table">\
			<tr>\
				<th><label for="ss_columns-coltype">Available column sets:</label></th>\
				<td><select name="coltype" id="ss_columns-coltype">\
					<option value="scf1">1 : 1</option>\
					<option value="scf2">1/2 : 1/2</option>\
					<option value="scf3">1/2 : 1/4 : 1/4</option>\
					<option value="scf4">1/4 : 1/4 : 1/2</option>\
					<option value="scf5">1/4 : 1/2 : 1/4</option>\
					<option value="scf6">1/4 : 1/4 : 1/4 : 1/4</option>\
					<option value="scf7">3/4 : 1/4</option>\
					<option value="scf8">1/4 : 3/4</option>\
					<option value="scf9">3/8 : 3/8 : 1/4</option>\
					<option value="scf10">1/4 : 3/8 : 3/8</option>\
					<option value="scf11">2/3 : 1/3</option>\
					<option value="scf12">1/3 : 2/3</option>\
					<option value="scf13">1/3 : 1/3 : 1/3</option>\
					<option value="scf14">1/5 : 1/5 : 1/5 : 1/5 : 1/5</option>\
					</select><br /><br/>\
					<small>Select a column set to insert into page.</small></td>\
			</tr>\
			</table>\
		<p class="submit">\
			<input type="button" id="ss_columns-submit" class="button-primary" value="Insert Columns" name="submit" />\
		</p>\
		</div>');
		var table = form.find('table');
		form.appendTo('body').hide();
		var dummy = '<br/><br/>Insert your content here. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus leo ante, consectetur sit amet vulputate vel, dapibus sit amet lectus.<br/><br/>';
		var nl = '<br/><br/>';
		form.find('#ss_columns-submit').click(function(){
			var shortcode = '';
			var coltype = table.find('#ss_columns-coltype').val();
			switch(coltype) {
				case 'scf1':
				shortcode = '[full]' + dummy + '[/full]';
				break;

				case 'scf2':
				shortcode = '[half]' + dummy + '[/half]' + nl + '[half_last]' + dummy + '[/half_last]';
				break;

				case 'scf3':
				shortcode = '[half]' + dummy + '[/half]' + nl + '[one_fourth]' + dummy + '[/one_fourth]' + nl + '[one_fourth_last]' + dummy + '[/one_fourth_last]';
				break;

				case 'scf4':
				shortcode = '[one_fourth]' + dummy + '[/one_fourth]' + nl + '[one_fourth]' + dummy + '[/one_fourth]' + nl + '[half_last]' + dummy + '[/half_last]';
				break;

				case 'scf5':
				shortcode = '[one_fourth]' + dummy + '[/one_fourth]' + nl + '[half]' + dummy + '[/half]' + nl + '[one_fourth_last]' + dummy + '[/one_fourth_last]';
				break;

				case 'scf6':
				shortcode = '[one_fourth]' + dummy + '[/one_fourth]' + nl + '[one_fourth]' + dummy + '[/one_fourth]' + nl + '[one_fourth]' + dummy + '[/one_fourth]' + nl + '[one_fourth_last]' + dummy + '[/one_fourth_last]';
				break;

				case 'scf7':
				shortcode = '[three_fourth]' + dummy + '[/three_fourth]' + nl + '[one_fourth_last]' + dummy + '[/one_fourth_last]';
				break;

				case 'scf8':
				shortcode = '[one_fourth]' + dummy + '[/one_fourth]' + nl + '[three_fourth_last]' + dummy + '[/three_fourth_last]';
				break;

				case 'scf9':
				shortcode = '[three_eighth]' + dummy + '[/three_eighth]' + nl + '[three_eighth]' + dummy + '[/three_eighth]' + nl + '[one_fourth_last]' + dummy + '[/one_fourth_last]';
				break;

				case 'scf10':
				shortcode = '[one_fourth]' + dummy + '[/one_fourth]' + nl + '[three_eighth]' + dummy + '[/three_eighth]' + nl + '[three_eighth_last]' + dummy + '[/three_eighth_last]';
				break;

				case 'scf11':
				shortcode = '[two_third]' + dummy + '[/two_third]' + nl + '[one_third_last]' + dummy + '[/one_third_last]';
				break;

				case 'scf12':
				shortcode = '[one_third]' + dummy + '[/one_third]' + nl + '[two_third_last]' + dummy + '[/two_third_last]';
				break;

				case 'scf13':
				shortcode = '[one_third]' + dummy + '[/one_third]' + nl + '[one_third]' + dummy + '[/one_third]' + nl + '[one_third_last]' + dummy + '[/one_third_last]';
				break;

				case 'scf14':
				shortcode = '[one_fifth]' + dummy + '[/one_fifth]' + nl + '[one_fifth]' + dummy + '[/one_fifth]' + nl + '[one_fifth]' + dummy + '[/one_fifth]' + nl + '[one_fifth]' + dummy + '[/one_fifth]' + nl + '[one_fifth_last]' + dummy + '[/one_fifth_last]';
				break;
			}
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
			tb_remove();
		});
	});
 })();