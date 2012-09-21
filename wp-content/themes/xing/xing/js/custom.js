// -- CUSTOM FUNCTIONS AND EFFECTS --

// -- NO CONFLICT MODE --
var $s = jQuery.noConflict();

$s(function(){

$s('html').removeClass('no-js').addClass('js-enabled');

	// -- IMAGE PRELOADER --

	target = $s(".slide, .port-item, .entry-thumb, ul.products > li.product");
	images = target.find("img");
	counter = 0;
	i=0;
	loaded = [];
	nextDelay = 0;
	images.each(function(){
		if( $s(this).parent().length == 0 )
			$s(this).wrap("<span class='preload' />");
		else
			$s(this).parent().addClass("preload");
		loaded[i++] = false;
	});
	images = $s.makeArray(images);

	timer = setInterval(function() {
		if( counter >= loaded.length )
		{
			clearInterval(timer);
			return;
		}
		for( i=0; i<images.length; i++ )
		{
			if( images[i].complete )
			{
				if( loaded[i] == false )
				{
					loaded[i] = true;
					counter++;
					nextDelay = nextDelay + 100;
				}
				$s(images[i]).css("visibility","visible").delay(nextDelay).animate({opacity:1}, 300,
				function(){
					$s(this).parent().removeClass("preload");
				});
			}
			else
			{
				$s(images[i]).css( {"visibility":"hidden", opacity:0} );
			}
		}
	}, 100 );

});


// -- DOCUMENT.READY --
$s(document).ready(function(){

	// -- NAVIGATION MENU --

	$s('.nav1 ul, .nav2 ul').css({display: "none"});
	function showMenu(){
		$s(this).find('ul:first').css({visibility: "visible",display: "none"}).fadeIn(300);
	};
	function hideMenu(){
		$s(this).find('ul:first').css({visibility: "visible",display: "none"});
	};
	var config = {
		over: showMenu,
		timeout: 500,
		out: hideMenu
	};
	$s(".nav1 li, .nav2 li").hoverIntent( config );

	// -- FIX FOR OLD BROWSERS --
	$s('.nav1 ul li:last-child > a, .nav2 ul li:last-child > a').css({border: "none"});


	// -- RESPONSIVE PRIMARY MENU --

	$s("<select />").appendTo(".ss_nav .wrap");

	$s("<option />", {
	   "selected": "selected",
	   "value"   : "",
	   "text"    : "Jump to..."
	}).appendTo(".ss_nav .wrap select");

	$s(".nav1 a").each(function() {
		var depth = $s(this).parents('ul').length - 1;
		str = $s(this).text();
		indent = new Array(++depth).join('-- ');
		 var el = $s(this);
		 $s("<option />", {
			 "value"   : el.attr("href"),
			 "text"    : indent+str
		 }).appendTo(".ss_nav .wrap select");
	});

	$s(".ss_nav .wrap select").change(function() {
	  window.location = $s(this).find("option:selected").val();
	});

	// -- RESPONSIVE SECONDARY(TOP) MENU

	$s("<select />").appendTo(".ss_nav_top .wrap");

	$s("<option />", {
	   "selected": "selected",
	   "value"   : "",
	   "text"    : "Jump to..."
	}).appendTo(".ss_nav_top .wrap select");

	$s(".nav2 a").each(function() {
		var depth = $s(this).parents('ul').length - 1;
		str = $s(this).text();
		indent = new Array(++depth).join('-- ');
		 var el = $s(this);
		 $s("<option />", {
			 "value"   : el.attr("href"),
			 "text"    : indent+str
		 }).appendTo(".ss_nav_top .wrap select");
	});

	$s(".ss_nav_top .wrap select").change(function() {
	  window.location = $s(this).find("option:selected").val();
	});


	// -- TOGGLE --

	$s('h5.toggle').click(function() {
		$s(this).next().slideToggle(300);
		$s(this).toggleClass("activetoggle");
		return false;
	}).next().hide();


	// Change the HTML5 data-rel attribute to rel

	$s('a[data-rel]').each(function() {
		$s(this).attr('rel', $s(this).data('rel'));
	});


	// -- PRETTYPHOTO --

	$s("a[rel^='prettyPhoto[group1]'], a[rel^='prettyPhoto[group2]'], a[rel^='prettyPhoto[inline]']").prettyPhoto({
		animation_speed: 'fast',
		slideshow: 5000,
		autoplay_slideshow: false,
		opacity: 0.80,
		show_title: false,
		theme: 'pp_default', /* light_rounded / dark_rounded / light_square / dark_square / facebook */
		overlay_gallery: false,
		social_tools: false
	});


	// -- THUMBNAIL HOVER EFFECT --

	jQuery.fn.thumbHover = function() {
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
	 $s(".port > li.non_filtered").thumbHover();


	// -- SCROLL TO TOP BUTTON --

	$s('.top_btn').hide();
	$s(window).scroll(function () {
		if( $s(this).scrollTop() > 100 ) {
			$s('.top_btn').fadeIn(300);
		}
		else {
			$s('.top_btn').fadeOut(300);
		}
	});

	$s('.top_btn a').click(function(){
		$s('html, body').animate({scrollTop:0}, 500 );
		return false;
	});


	// -- BOX CLOSE BUTTON --

	$s(".box").each(function(){
		$s(this).append('<span class="hide_box"></span>');
			$s(this).find('.hide_box').click(function(){
			$s(this).parent().hide();
		});
	});
}); // END DOCUMENT.READY