var $s = jQuery.noConflict();
$s(document).ready(function(){

	// -- THUMBNAIL HOVER EFFECT --

	jQuery.fn.thumbHover2 = function(options) {
		$s(this).each(function()
		{
			var images = $s(this).find("img");
			if (images.length > 0)
			{
				var desc = $s(this).find('.port-details');
				$s(desc).css({opacity: 0, display:"block"});
				$s(this).hover(function()
					{
						$s(desc).stop().animate({"opacity": "1"}, 200);
					}, function() {
						$s(desc).stop().animate({"opacity": "0" }, 200);
					}
				);
			}
		});
		return this;
	 };

	 $s(".port-item").thumbHover2();

	// Clone filterable port items to get a second collection for Quicksand plugin
	var fp_clone = $s(".ss_filterable").clone();

	// Attempt to call Quicksand on every click event handler
	$s("#filter-nav a").click(function(e){

		$s("#filter-nav li").removeClass("current");

		// Get the class attribute value of the clicked link
		var filterClass = $s(this).parent().attr("class");

		if ( filterClass == "all" ) {
			var filteredPortfolio = fp_clone.find("li");
		} else {
			var filteredPortfolio = fp_clone.find("li." + filterClass + "");
		}

		// Call quicksand
		$s(".ss_filterable").quicksand( filteredPortfolio, {
			adjustHeight: 'dynamic', // auto, false, dynamic
			duration: 800,
			easing: 'easeInOutExpo',
			useScaling: false
		},
		function(){

			$s('a[data-rel]').each(function() {
			$s(this).attr('rel', $s(this).data('rel'));
			});

			$s("a[rel^='prettyPhoto[group1]']").prettyPhoto({
				animation_speed: 'fast',
				slideshow: 5000,
				autoplay_slideshow: false,
				opacity: 0.80,
				show_title: false,
				theme: 'pp_default', /* light_rounded / dark_rounded / light_square / dark_square / facebook */
				overlay_gallery: false,
				social_tools: false
			});

			 $s(".port-item").thumbHover2();

		});

		$s(this).parent().addClass("current");

		// Prevent the browser jump to the link anchor
		e.preventDefault();
	});
});