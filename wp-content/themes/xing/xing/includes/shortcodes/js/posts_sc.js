// Posts Short Code

(function() {
     tinymce.create('tinymce.plugins.posts', {
        init : function(ed, url) {
             ed.addButton('posts', {
                title : 'Insert Posts',
                image : url+'/images/posts.png',
                onclick : function() {
						var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
						W = W - 80;
						H = H - 120;
						tb_show( 'Post Options', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=ss_posts-form' );
                 }
             });
         },
         createControl : function(n, cm) {
             return null;
         },
     });
	tinymce.PluginManager.add('posts', tinymce.plugins.posts);
	jQuery(function(){
		var form = jQuery('<div id="ss_posts-form"><table id="ss_posts-table" class="form-table">\
			<tr>\
				<td><h3>Options for inserting Posts</h3></td>\
			</tr>\
			<tr>\
				<th><label for="ss_posts_type">Select a method for posts insertion</label></th>\
				<td><select name="post_type" id="ss_posts_type">\
					<option value="post_list">Small Thumbnails left aligned</option>\
					<option value="plain_list">Vertical Plain List</option>\
					</select><br /><br/>\
				<small>Choose how do you wish to show posts.</small></td>\
			</tr>\
			<tr>\
				<th><label for="ss_posts-query_type">Select a Query type</label></th>\
				<td><select name="query_type" id="ss_posts-query_type">\
					<option value="category">Category</option>\
					<option value="posts">Selective Posts</option>\
					<option value="pages">Selective Pages</option>\
					</select><br /><br/>\
				<small>Choose how do you wish to query posts. i.e. from category, selective posts or selective pages.</small></td>\
			</tr>\
			<tr>\
				<th><label for="ss_posts-cats">Category ID for Posts</label></th>\
				<td><input type="text" id="ss_posts-cats" name="cats" value="" /><br />\
				<small>Enter a category ID, or IDs separated by comma, from which you wish to show posts. Example: 3,4,7</small></td>\
			</tr>\
			<tr>\
				<th><label for="ss_posts-posts">Selective Post IDs</label></th>\
				<td><input type="text" id="ss_posts-posts" name="posts" value="" /><br />\
				<small>Enter Post IDs of your selective posts, separated by comma. Example: 123,141,232</small></td>\
			</tr>\
			<tr>\
				<th><label for="ss_posts-pages">Selective Page IDs</label></th>\
				<td><input type="text" id="ss_posts-pages" name="pages" value="" /><br />\
				<small>Enter Page IDs of your selective pages, separated by comma. Example: 12,32,55</small></td>\
			</tr>\
			<tr>\
				<th><label for="ss_posts-num">Number of posts to show</label></th>\
				<td><input type="text" id="ss_posts-num" name="num" value="" /><br />\
				<small>Enter a number of posts to show. Example: 4</small></td>\
			</tr>\
			<tr>\
				<th><label for="ss_posts-offset">Posts offset</label></th>\
				<td><input type="text" id="ss_posts-offset" name="offset" value="0" /><br />\
				<small>Enter an offset for posts. i.e. how many posts should be skipped from the specified category or posts. Example: 3</small></td>\
			</tr>\
			<tr>\
				<th><label for="ss_posts-order">Order of appearance</label></th>\
				<td><select name="order" id="ss_posts-order">\
					<option value="desc">Descending</option>\
					<option value="asc">Ascending</option>\
					</select><br /><br/>\
				<small>Select an order of appearance for posts. Ascending or descending.</small></td>\
			</tr>\
			<tr>\
				<th><label for="ss_posts-orderby">Order posts by</label></th>\
				<td><select name="orderby" id="ss_posts-orderby">\
					<option value="date">Date</option>\
					<option value="title">Title</option>\
					<option value="rand">Random</option>\
					<option value="author">Author</option>\
					<option value="modified">Last Modified</option>\
					</select><br /><br/>\
				<small>Select how do you wish to sort posts. i.e. by date, title, random order, etc.</small></td>\
			</tr>\
			</table>\
		<p class="submit">\
			<input type="button" id="ss_posts-submit" class="button-primary" value="Insert Posts" name="submit" />\
		</p>\
		</div>');
		var table = form.find('table');
		form.appendTo('body').hide();
		form.find('#ss_posts-submit').click(function(){
			var options = {
				'query_type' : 'category',
				'cats' : '1',
				'posts' : '',
				'pages' : '',
				'order' : 'desc',
				'orderby' : 'date',
				'num' : '2',
				'offset' : '0'
				};
			var posts_type = table.find('#ss_posts_type').val();
			var q_type = table.find('#ss_posts-query_type').val();
			var shortcode = '['+ posts_type;
			for( var index in options) {
				var value = table.find('#ss_posts-' + index).val();
				if ( value !== options[index] ) {
					if(q_type == 'category' && index != 'posts' && index != 'pages')
						shortcode += ' ' + index + '="' + value + '"';
					if(q_type == 'posts' && index != 'cats' && index != 'pages')
						shortcode += ' ' + index + '="' + value + '"';
					if(q_type == 'pages' && index != 'cats' && index != 'posts')
						shortcode += ' ' + index + '="' + value + '"';
				} // value
			}
			shortcode += ']';
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
			tb_remove();
		});
	});
 })();