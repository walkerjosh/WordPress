/* JS effects for theme options panel */

jQuery(document).ready(function(){
	jQuery(".tabbed").hide();
	jQuery("ul.tabs li:first").addClass("active");
	jQuery(".tabbed:first").show();
	jQuery("ul.tabs li").click(function() {
		jQuery("ul.tabs li").removeClass("active");
		jQuery(this).addClass("active");
		jQuery(".tabbed").hide();
		var currentTab = jQuery(this).find("a").attr("href");
		jQuery(currentTab).show();
		return false;
	});

}) // document.ready