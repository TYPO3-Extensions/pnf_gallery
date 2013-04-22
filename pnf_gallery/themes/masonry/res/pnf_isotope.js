function pnfGalleryMasonry_init(ceid) {
	var $container = $('#masonry' + ceid + ' .imageContainer');
	pnfGalleryMasonry_initIsotope($container);
	pnfGalleryMasonry_initLegend(ceid);
	var lightbox = pnfGalleryMasonry_initLightbox(ceid);
	pnfGalleryMasonry_initScroll($container, ceid, lightbox);
}
function pnfGalleryMasonry_initIsotope($container) {
	// Masonry effect
	$container.isotope({
		itemSelector : '.item'
	});
	// Sortby random => not compatibility with lightbox order
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

function pnfGalleryMasonry_initScroll($container, ceid, lightbox) {
	$container.infinitescroll({
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
			// $container.isotope( 'appended', $newElems, true );  => insert keep filter
			$container.isotope( 'insert', $newElems, true ); 
			pnfGalleryMasonry_initLegend(ceid);
			lightbox[0].reload();
	});
}