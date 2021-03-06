jQuery(document).ready(function($) {

	// -- Tabber --

	$('.tabber').each(function(){
		var widgets = $(this).find('div.tabbed');
		var titleList = '<ul class="ss_tabs">';
		for (i=0; i<widgets.length; i++)
		{
			var widgetTitle = $(widgets[i]).children('h4.tab_title').text();
			$(widgets[i]).children('h4.tab_title').hide();
			var listItem = '<li><a href="#' +$(widgets[i]).attr("id")+ '">' +widgetTitle+ '</a></li>';
			titleList += listItem;
		};
		titleList += '</ul>';
		$(widgets[0]).before(titleList);
		$(this).tabs();
		//$(this).tabs({fx:{ height: 'toggle', opacity: 'toggle', duration: 300 }});
	});

	// -- Accordion --

	$('.accordion').accordion({ header: 'h5.handle', collapsible: true });
})