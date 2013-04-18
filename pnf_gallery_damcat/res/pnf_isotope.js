function pnfGalleryMasonry_damcat_init(ceid) {
	var $container = $('#masonry' + ceid + ' .imageContainer');
	pnfGalleryMasonry_initIsotope($container);
	pnfGalleryMasonry_initLegend(ceid);
	var lightbox = pnfGalleryMasonry_initLightbox(ceid);
	pnfGalleryMasonry_initFilter($container, ceid, lightbox);
	pnfGalleryMasonry_initScroll($container, ceid, lightbox);
}

function pnfGalleryMasonry_initFilter($container, ceid, lightbox) {
	$("#damcatfilter" + ceid + " a.delete_filter").click(function() {
		// View all
		$container.isotope({ filter: '*' });
		return false;
	});
	
	$("#damcatfilter" + ceid + " a[class^='damcat']").click(function() {
		// Add selected class (for style)
		if(jQuery(this).hasClass("selected")){
			jQuery(this).removeClass("selected");
		}
		else{
			jQuery("#damcatfilter" + ceid + " a").removeClass("selected");
			jQuery(this).addClass("selected");
		}
		
		// Get filters
		var categories = [];
		categories.push($(this).attr('class').replace(' selected',''));
		var sub = $(this).parent('li').children('div.sub');
		if (sub) {
			sub.find('li').each(function() {
				var cat = $(this).children("a[class^='damcat']");
				if (cat) {
					categories.push(cat.attr('class').replace(' selected',''));
				}
			});
		}
		var selector = '';
		for (i=0; i< categories.length; i++) {
			if (i > 0)
				selector += ", ";
			selector += "." + categories[i];
		}
		$container.isotope({ filter: selector });
		
		// Lightbox
		lightbox[0].reload();
		
		// Change name filter if subcategories
		var hasSub = false;
		var maxLoops = 10;
		var parentCat = $(this).parent('li');
		while(maxLoops > 0) {
			if (parentCat.hasClass('has_subcategories')) {
				hasSub = true;
				maxLoops = 0;
			} else {
				parentCat = parentCat.parent('li, ul, div.sub');
				if (!parentCat.size()) {
					maxLoops = 0;
				} else {
					maxLoops--;
				}
			}
		}
		if (hasSub) {
			var linkParent = parentCat.children('a');
			if (linkParent) {
				parentCat.addClass('subcategories_current');
				if (!parentCat.children('span.parentText').size())
					linkParent.after('<span class="parentText" style="display:none;">' + linkParent.html() + '</span>');
				linkParent.html($(this).html());
			}
		}
		
		// Memory selected filter for infinitescroll
		if(!$('.damcatfilterSel').html()) {
			var imgCont = $('#masonry' + ceid);
			imgCont.after('<span class="damcatfilterSel" style="display:none;">' + selector + '</span>');
		}else{
			$('.damcatfilterSel').html(selector);
		}
		
		return false;
	});
}
