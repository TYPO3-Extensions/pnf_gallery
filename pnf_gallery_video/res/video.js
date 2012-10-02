jQuery(document).ready(function(){
	jQuery('.pnfgallery_video').each(function() {
		loadVideo(jQuery(this));
	});
	
	jQuery('.csc-textpic-image a[rel=pnf_gallery_video_link]').click(function() {
		jQuery(this).pnfgallerylightbox({
			'type': 'video',
			'data': jQuery(this).attr('href')
		});
		return false;
	});
});

function loadVideo(element) {
	var id = element.attr('id');
	if (id) {
		jwplayer(id).setup({
			skin: '/typo3conf/ext/pnf_gallery_video/res/player/pnf_blue.zip',
			modes: [
				{ type: 'html5' },
				{ type: 'flash', src: '/typo3conf/ext/pnf_gallery_video/res/player/mediaplayer-5.9/player.swf' }
			]
	  });
	}
}