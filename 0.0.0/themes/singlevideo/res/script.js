function changeImageAjax(cEid, index, id) {
	var ret = tx_pnfgalleryvideo_singlevideoprocessChangeElement(cEid, index, id); 
	return !ret;
}

function loadVideoAjax(id) {
	jQuery('#' + id + ' .pnfgallery_video').each(function() {
		loadVideo(jQuery(this));
	});
}