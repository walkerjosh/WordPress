// -- Masonry Initialixe --
jQuery(window).load( function() {
	// -- Masonry Layout --
	jQuery('#mason_container').masonry({
		itemSelector : '.entry-grid',
		isAnimated: true,
		columnWidth: 1
	});
})