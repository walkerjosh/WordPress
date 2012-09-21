// List Short Code

(function() {
     tinymce.create('tinymce.plugins.list', {
        init : function(ed, url) {
             ed.addButton('list', {
                title : 'Insert a stylish list',
                image : url+'/images/list.png',
                onclick : function() {
						var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
						W = W - 80;
						H = H - 84;
						tb_show( 'List Options', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=ss_list-form' );					                 }
             });
         },
         createControl : function(n, cm) {
             return null;
         },
     });
	tinymce.PluginManager.add('list', tinymce.plugins.list);
	jQuery(function(){
		var form = jQuery('<div id="ss_list-form"><table id="ss_list-table" class="form-table">\
						  <tr>\
						  <td><h3>Options for Style Lists</h3>\
						  </td>\
						  </tr>\
						  <tr>\
						  <th><label for="ss_list-liststyle">List Style Icon</label></th>\
						  <td><select name="align" id="ss_list-liststyle">\
						  <option value="0">Check Light</option>\
						  <option value="1">Check Dark</option>\
						  <option value="2">Disc Light</option>\
						  <option value="3">Disc Dark</option>\
						  <option value="4">Square Light</option>\
						  <option value="5">Square Dark</option>\
						  <option value="6">Star Light</option>\
						  <option value="7">Star Dark</option>\
						  <option value="8">Arrow Light</option>\
						  <option value="9">Arrow Dark</option>\
						  <option value="10">Plus Light</option>\
						  <option value="11">Plus Dark</option>\
						  </select><br /><br/>\
						  <small>Select a list style.</small></td>\
						  </tr>\
						  </table>\
						  <p class="submit">\
						  <input type="button" id="ss_list-submit" class="button-primary" value="Insert List" name="submit" />\
						  </p>\
						  </div>');
		var table = form.find('table');
		form.appendTo('body').hide();
		form.find('#ss_list-submit').click(function(){
			var shortcode = '';
			var liststyle = table.find('#ss_list-liststyle').val();
			shortcode = '<ul class="list list' + liststyle + '"><li>List item one</li><li>List item two</li><li>List item three</li><li>List item four</li></ul>';
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
			tb_remove();
		});
	});
 })();