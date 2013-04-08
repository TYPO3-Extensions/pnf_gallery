function pnfGalleryMasonry_damcat_init(ceid) {
	pnfGalleryMasonry_initMasonry(ceid);
	pnfGalleryMasonry_initLegend(ceid);
	var lightbox = pnfGalleryMasonry_initLightbox(ceid);
	pnfGalleryMasonry_initFilter(ceid, lightbox);
	pnfGalleryMasonry_initScroll(ceid);
}

function pnfGalleryMasonry_initFilter(ceid, lightbox) {
	// Filter
	$("#damcatfilter" + ceid + " a.delete_filter").click(function() {
		var imagesAll = $('#masonry' + ceid + ' .imageContainer div').removeClass('itemHide').removeClass('item').addClass('item').show();
		// Reload
		$('#masonry' + ceid + ' .imageContainer').masonry('reload');
		lightbox[0].reload();
		
		// Subcategories
		$('#damcatfilter' + ceid + ' ul li.has_subcategories').each(function() {
			if ($(this).children('a').size() && $(this).children('span.parentText').size()) {
				$(this).children('a').html($(this).children('span.parentText').html());
				$(this).children('span.parentText').remove();
			}
		});
		return false;
	});
	$("#damcatfilter" + ceid + " a[class^='damcat']").click(function() {
		var categories = [];
		categories.push($(this).attr('class'));
		var sub = $(this).parent('li').children('ul');
		if (sub) {
			sub.children('li').each(function() {
				var cat = $(this).children("a[class^='damcat']");
				if (cat) {
					categories.push(cat.attr('class'));
				}
			});
		}
		var selector = '';
		for (i=0; i< categories.length; i++) {
			if (i > 0)
				selector += ", ";
			selector += "#masonry" + ceid + " .imageContainer .itemHide[class*='" + categories[i] + "']"
		}
		var imagesAll = $('#masonry' + ceid + ' .imageContainer div').removeClass('item').addClass('itemHide');
		// Images to view
		// var images = $("#masonry###CEID### .imageContainer .itemHide[class*='" + $(this).attr('class') + "']");
		var images = $(selector);
		images.removeClass('itemHide').addClass('item');
		//  Images to Hide
		var imagesHidden = $('#masonry' + ceid + ' .imageContainer .itemHide');
		// Effect
		imagesHidden.hide();
		images.show();
		
		// Reload
		$('#masonry' + ceid + ' .imageContainer').masonry('reload');
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
				parentCat = parentCat.parent('li, ul');
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
		
		return false;
	});
}	
	
