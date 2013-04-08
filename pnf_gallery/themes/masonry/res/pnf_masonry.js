function pnfGalleryMasonry_init(ceid) {
	pnfGalleryMasonry_initMasonry(ceid);
	pnfGalleryMasonry_initLegend(ceid);
	var lightbox = pnfGalleryMasonry_initLightbox(ceid);
	pnfGalleryMasonry_initScroll(ceid);
}
 function pnfGalleryMasonry_initMasonry(ceid) {
	// Masonry effect
	 $('#masonry' + ceid + ' .imageContainer').masonry({
		itemSelector : '.item',
		 isAnimated: true
	});
 }
 function pnfGalleryMasonry_initLegend(ceid) {
	//$('#c###CEID###').parent().parent().css('overflow', 'hidden');
		
	// Legend
	$('#masonry' + ceid + ' .item .legend').hide();
	$('#masonry' + ceid + ' .item').hover(function() {
		$(this).children('.legend').show();
	}, function() {
		$(this).children('.legend').hide();
	});
}
function pnfGalleryMasonry_initLightbox(ceid) {
	var lightbox = $('#masonry' + ceid).pnfgallerylightbox({
		'data': '#masonry' + ceid + ' .imageContainer .item',
		'container': '#pnf_gallery' + ceid
	});	
	return lightbox;
}
function pnfGalleryMasonry_initScroll(ceid) {
	$('#masonry' + ceid).infinitescroll({
			navSelector  : '#pnf_gallery' + ceid + ' .nav',    // selector for the paged navigation 
			nextSelector : '#pnf_gallery' + ceid + ' .nav a',  // selector for the NEXT link (to page 2)
			itemSelector : '.item',     // selector for all items you'll retrieve
			loading: {
				finishedMsg: 'No more pages to load.',
				img: 'http://i.imgur.com/6RMhx.gif'
			},
			debug        : false
		},
		function( newElements ) {
			 var $newElems = $( newElements );
			$('#masonry' + ceid + ' .imageContainer').masonry( 'appended', $newElems, true ); 
	});
}