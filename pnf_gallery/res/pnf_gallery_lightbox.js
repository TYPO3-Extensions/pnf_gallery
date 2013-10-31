
(function( $ ) {

PnfGalleryLightbox = function() {
    var self = this;
	// internal options
    this._options = {};
	this.isMobile = {
		Android: function() {
			return navigator.userAgent.match(/Android/i);
		},
		BlackBerry: function() {
			return navigator.userAgent.match(/BlackBerry/i);
		},
		iOS: function() {
			return navigator.userAgent.match(/iPhone|iPad|iPod/i);
		},
		Opera: function() {
			return navigator.userAgent.match(/Opera Mini/i);
		},
		Windows: function() {
			return navigator.userAgent.match(/IEMobile/i);
		},
		any: function() {
			return (self.isMobile.Android() || self.isMobile.BlackBerry() || self.isMobile.iOS() || self.isMobile.Opera() || self.isMobile.Windows());
		}
	};
    var lightbox = this._lightbox = {
        width : 400,
        height : 400,
        initialized : false,
		images : [],
		imageCurrent : 0,
		container : 'body',
		titleTag : 'title',
		descriptionTag: 'alt',
        init : function() {
            if ( lightbox.initialized ) {
                return;
            }
            lightbox.initialized = true;
			if (self._options.container)
				lightbox.container = self._options.container;
			if (self._options.titleTag)
				lightbox.titleTag = self._options.titleTag;
			if (self._options.descriptionTag)
				lightbox.descriptionTag = self._options.descriptionTag;
				
			$(lightbox.container).append('<div class="pnf-gallery-lightbox-overlay"></div>');
			$(lightbox.container).append('<div class="pnf-gallery-lightbox-box"></div>');
			
			$(lightbox.container + ' > .pnf-gallery-lightbox-box').append('<div class="pnf-gallery-lightbox-shadow"></div>');
			$(lightbox.container + ' > .pnf-gallery-lightbox-box').append('<div class="pnf-gallery-lightbox-content"></div>');
			$(lightbox.container + ' > .pnf-gallery-lightbox-box').append('<div class="pnf-gallery-lightbox-legend"></div>');
			$(lightbox.container + ' > .pnf-gallery-lightbox-box').append('<div class="pnf-gallery-lightbox-close">x</div>');
			$(lightbox.container + ' > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-close').bind( 'click', lightbox.hide );
			lightbox.initImages();
        },
		initImages: function() {
			var data = self._options.data;
			var index = 0;
			if (self._options.type != 'video') {
				$(data).children('a').each(function() {
					var img = $(this).children('img');
					if (img) {
						$(this).attr('data-index', index);
						$(this).click(function() {
							lightbox.imageCurrent = $(this).attr('data-index');
							lightbox.show();
							return false;
						});
						lightbox.images.push(img);
						index++;
						if (index > self._options.limit)
							$(this).hide();
					}
				});
				// View all button
				if (self._options.limit < lightbox.images.length && self._options.viewAllLabel) {
					var allHtml = '<div class="pnf-gallery-all"><span>' + self._options.viewAllLabel + '</span></div>';
					if (self._options.viewAllPos && self._options.viewAllPos < self._options.limit) {
						$(data).children('a:eq(' + (self._options.viewAllPos - 1) + ')').each(function() {
							$(this).before(allHtml);
						});
					} else {
						$(data).append(allHtml);
					}
					$(data).children('.pnf-gallery-all').bind('click', function() {
						lightbox.imageCurrent = 0;
						lightbox.show();
					});
				}
			}
		},
		reloadImages: function() {
			lightbox.images = [];
			lightbox.imageCurrent = 0;
			lightbox.initImages();
		},
        hide: function() {
			$(lightbox.container + ' > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-content').html('');
            $(lightbox.container + ' > .pnf-gallery-lightbox-box').hide();
            $(lightbox.container + ' > .pnf-gallery-lightbox-overlay').hide();
        },
        show: function() {
			var type = self._options.type;
			var data = self._options.data;
			$(lightbox.container + ' > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-content').html('');
			$(lightbox.container + ' > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-legend').html('');
			var content = '';
			var legend = '';
			if (type == 'video') {
				var id = parseInt(Math.random()*10000, 10);
				if( self.isMobile.any() )
					content = '<video autoplay="false" controls="controls" class="pnfgallery_video" id="pnfgallery_video_' + id + '" width="550" height="335"><source src="/' + data + '" type="video/flv"></video>';
				else
					content = '<video autoplay="true" controls="controls" class="pnfgallery_video" id="pnfgallery_video_' + id + '" width="550" height="335"><source src="/' + data + '" type="video/flv"></video>';
				lightbox.width = 550;
				lightbox.height = 335;
			} else {
				data = lightbox.images[lightbox.imageCurrent];
				content = $(data).clone();
				if (data.attr(lightbox.titleTag))
					legend += '<p class="title">' + data.attr(lightbox.titleTag) + '</p>';
				if (data.attr(lightbox.descriptionTag))
					legend += '<p class="description">' + data.attr(lightbox.descriptionTag) + '</p>';
				if (data.attr('data-big')) {
					content = '<img src="' + data.attr('data-big') + '" ' + lightbox.titleTag + '="' + data.attr(lightbox.titleTag) + '" alt="' + data.attr('alt') + '" />';
				}
			}
			$(lightbox.container + ' > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-content').append(content);
			$(lightbox.container + ' > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-legend').html(legend);
			lightbox.browser();
            $(lightbox.container + ' > .pnf-gallery-lightbox-overlay').show().css( 'visibility', 'visible' );
            $(lightbox.container + ' > .pnf-gallery-lightbox-box').show();
			if (type == 'video') {
				lightbox.resize();
				$(lightbox.container + ' > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-content .pnfgallery_video').each(function() {
					loadVideo($(this));
				});
			} else {
				$(lightbox.container + ' > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-content img').load(function() {
					lightbox.resize();
				});
			}
        },
		resize: function() {
			if (self._options.type == 'video') {
				var destWidth = $(lightbox.container + ' > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-content > video').attr('width');
				var destHeight = $(lightbox.container + ' > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-content > video').attr('height');
			} else {
				var destWidth = $(lightbox.container + ' > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-content > img').width();
				var destHeight = $(lightbox.container + ' > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-content > img').height();
				var lineHeight =  $(lightbox.container + ' > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-content').height();
				 $(lightbox.container + ' > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-content').css('line-height', lineHeight + 'px');
			}
			
			if (destWidth && destHeight) {
				var to = {
						width: destWidth,
						height: destHeight,
						'margin-top': Math.ceil( destHeight / 2 ) *- 1,
						'margin-left': Math.ceil( destWidth / 2 ) *- 1
					};

				
				$(lightbox.container + ' > .pnf-gallery-lightbox-box').animate( to, {
					duration: 200
				});
			}
		},
		browser: function() {
			if (!$(lightbox.container + ' > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-browser').size()) {
				$(lightbox.container + ' > .pnf-gallery-lightbox-box').append('<div class="pnf-gallery-lightbox-browser"></div>');
				$(lightbox.container + ' > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-browser').append('<div class="pnf-gallery-lightbox-counter"></div>');
				$(lightbox.container + ' > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-browser').append('<div class="pnf-gallery-lightbox-prev"></div>');
				$(lightbox.container + ' > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-browser').append('<div class="pnf-gallery-lightbox-next"></div>');
				lightbox.initKeydown();
			}
			$(lightbox.container + ' > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-browser').hide();
			$(lightbox.container + ' > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-browser > .pnf-gallery-lightbox-next').unbind();
			$(lightbox.container + ' > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-browser > .pnf-gallery-lightbox-prev').unbind();
			
			var data = self._options.data;
			
			if (self._options.type != 'video') {
				var countImages = lightbox.images.length;
				// Counter
				lightbox.imageCurrent = parseInt(lightbox.imageCurrent);
				var counterHtml = (lightbox.imageCurrent + 1) + '/' + countImages;
				$(lightbox.container + ' > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-browser > .pnf-gallery-lightbox-counter').html(counterHtml);
				// Next
				if (lightbox.imageCurrent < (countImages - 1)) {
					$(lightbox.container + ' > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-browser > .pnf-gallery-lightbox-next').show();
					$(lightbox.container + ' > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-browser > .pnf-gallery-lightbox-next').bind('click', function() {
						lightbox.imageCurrent++;
						lightbox.show();
					});
				} else {
					$(lightbox.container + ' > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-browser > .pnf-gallery-lightbox-next').hide();
				}
				// Prev
				if (lightbox.imageCurrent > 0) {
					$(lightbox.container + ' > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-browser > .pnf-gallery-lightbox-prev').show();
					$(lightbox.container + ' > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-browser > .pnf-gallery-lightbox-prev').bind('click', function() {
						lightbox.imageCurrent--;
						lightbox.show();
					});
				} else {
					$(lightbox.container + ' > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-browser > .pnf-gallery-lightbox-prev').hide();
				}
				$(lightbox.container + ' > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-browser').show();
			}
			
		},
		initKeydown: function() {
			$(function() {
				$(document).keydown(function(e) {
					switch(e.keyCode) {
						case 37:	//left
							if ($(lightbox.container + ' > .pnf-gallery-lightbox-box').size() 
								&& $(lightbox.container + ' > .pnf-gallery-lightbox-box').css('display') != 'none') {
								$(lightbox.container + ' > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-browser > .pnf-gallery-lightbox-prev').click();
							}
							return false;
							break;
						case 39:	//right
							if ($(lightbox.container + ' > .pnf-gallery-lightbox-box').size() 
								&& $(lightbox.container + ' > .pnf-gallery-lightbox-box').css('display') != 'none') {
								$(lightbox.container + ' > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-browser > .pnf-gallery-lightbox-next').click();
							}
							return false;
							break;
					}
				});
			});
		}
    };

    return this;
};

// end PnfGalleryLightbox constructor

PnfGalleryLightbox.prototype = {
    constructor: PnfGalleryLightbox,
    init: function( target, options ) {
		this._options = options;
		this._lightbox.init();
        return this;
    },
	run: function(target, options) {
		this._options = options;
		this._lightbox.show();
	},
	reload: function() {
		this._lightbox.reloadImages();
	}
};
// End of PnfGalleryLightbox prototype


// the plugin initializer
$.fn.pnfgallerylightbox = function( options ) {

    var selector = this.selector;

    // try domReady if element not found
    if ( !$(this).length ) {

        $(function() {
            $( selector ).pnfgallerylightbox( options );
        });
        return this;
    }
	var objArray = [];
    this.each(function() {
		var obj = new PnfGalleryLightbox();
        if ( !$.data(document.body, 'pnfgallerylightbox') ) {
			obj.init( this, options )
            $.data( document.body, 'pnfgallerylightbox',  true);
        }
		if (options.type == 'video')
			obj.run(this, options);
		objArray.push(obj);
    });
	return objArray;
};

}( jQuery ) );