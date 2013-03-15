
(function( $ ) {

PnfGalleryLightbox = function() {
    var self = this;
	// internal options
    this._options = {};
    var lightbox = this._lightbox = {
        width : 400,
        height : 400,
        initialized : false,
        init : function() {
            if ( lightbox.initialized ) {
                return;
            }
            lightbox.initialized = true;
			
			$('body').append('<div class="pnf-gallery-lightbox-overlay"></div>');
			$('body').append('<div class="pnf-gallery-lightbox-box"></div>');
			
			$('body > .pnf-gallery-lightbox-box').append('<div class="pnf-gallery-lightbox-shadow"></div>');
			$('body > .pnf-gallery-lightbox-box').append('<div class="pnf-gallery-lightbox-content"></div>');
			$('body > .pnf-gallery-lightbox-box').append('<div class="pnf-gallery-lightbox-close">x</div>');
			$( 'body > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-close').bind( 'click', lightbox.hide );
        },
        hide: function() {
			$('body > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-content').html('');
            $('body > .pnf-gallery-lightbox-box').hide();
            $('body > .pnf-gallery-lightbox-overlay').hide();
        },
        show: function() {
			var type = self._options.type;
			var data = self._options.data;
			$('body > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-content').html('');
			var content = '';
			if (type == 'video') {
				var id = parseInt(Math.random()*10000, 10);
				content = '<video autoplay="true" controls="controls" class="pnfgallery_video" id="pnfgallery_video_' + id + '" width="550" height="335"><source src="/' + data + '" type="video/flv"></video>';
				lightbox.width = 550;
				lightbox.height = 335;
			} else {
				content = $(data).clone();
				if (data.attr('data-big')) {
					content = '<img src="' + data.attr('data-big') + '" title="' + data.attr('title') + '" alt="' + data.attr('alt') + '" />';
				}
			}
			$('body > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-content').append(content);
			lightbox.browser();
            $('body > .pnf-gallery-lightbox-overlay').show().css( 'visibility', 'visible' );
            $('body > .pnf-gallery-lightbox-box').show();
			lightbox.resize();
			if (type == 'video') {
				$('body > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-content .pnfgallery_video').each(function() {
					loadVideo($(this));
				});
			} else {
				$('body > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-content img').load(function() {
					lightbox.resize();
				});
			}
        },
		resize: function() {
			var type = self._options.type;
			var data = self._options.data;
			if (type == 'video') {
				var destWidth = $('body > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-content > video').attr('width');
				var destHeight = $('body > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-content > video').attr('height');
			} else {
				var destWidth = $('body > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-content > img').width();
				var destHeight = $('body > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-content > img').height();
			}
			
			if (destWidth && destHeight) {
				var to = {
						width: destWidth,
						height: destHeight,
						'margin-top': Math.ceil( destHeight / 2 ) *- 1,
						'margin-left': Math.ceil( destWidth / 2 ) *- 1
					};

				
				$('body > .pnf-gallery-lightbox-box').animate( to, {
					duration: 200
				});
			}
		},
		browser: function() {
			if (!$('body > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-browser').size()) {
				$('body > .pnf-gallery-lightbox-box').append('<div class="pnf-gallery-lightbox-browser"></div>');
				$('body > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-browser').append('<div class="pnf-gallery-lightbox-prev"><</div>');
				$('body > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-browser').append('<div class="pnf-gallery-lightbox-next">></div>');
			}
			var type = self._options.type;
			var data = self._options.data;
			
			$('body > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-browser').hide();
			$('body > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-browser > .pnf-gallery-lightbox-next').unbind();
			$('body > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-browser > .pnf-gallery-lightbox-prev').unbind();
			var next = data.next('img');
			var prev = data.prev('img');
			if (!next.size()) {
				if (data.parent('a').size()) {
					next = data.parent('a').next('a').children('img');
					prev = data.parent('a').prev('a').children('img');
				}
			}
			if (next.size()) {
				$('body > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-browser > .pnf-gallery-lightbox-next').show();
				$('body > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-browser > .pnf-gallery-lightbox-next').bind('click', function() {
					jQuery(this).pnfgallerylightbox({
						'data': next
					});
				});
			} else {
				$('body > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-browser > .pnf-gallery-lightbox-next').hide();
			}
			if (prev.size()) {
				$('body > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-browser > .pnf-gallery-lightbox-prev').show();
				$('body > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-browser > .pnf-gallery-lightbox-prev').bind('click', function() {
					jQuery(this).pnfgallerylightbox({
						'data': prev
					});
				});
			} else {
				$('body > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-browser > .pnf-gallery-lightbox-prev').hide();
			}
			$('body > .pnf-gallery-lightbox-box > .pnf-gallery-lightbox-browser').show();
		}
    };

    return this;
};

// end PnfGalleryLightbox constructor

PnfGalleryLightbox.prototype = {
    constructor: PnfGalleryLightbox,
    init: function( target, options ) {
		this._lightbox.init();
        return this;
    },
	run: function(target, options) {
		this._options.type = options.type;
		this._options.data = options.data;
		this._lightbox.show();
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

    return this.each(function() {
		var obj = new PnfGalleryLightbox();
        if ( !$.data(document.body, 'pnfgallerylightbox') ) {
			obj.init( this, options )
            $.data( document.body, 'pnfgallerylightbox',  true);
        }
		obj.run(this, options);
    });

};

}( jQuery ) );
