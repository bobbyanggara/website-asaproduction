// Smooth scroll blocking
document.addEventListener( 'DOMContentLoaded', function() {
	if ( 'onwheel' in document ) {
		window.onwheel = function( event ) {
			if( typeof( this.RDSmoothScroll ) !== undefined ) {
				try { window.removeEventListener( 'DOMMouseScroll', this.RDSmoothScroll.prototype.onWheel ); } catch( error ) {}
				event.stopPropagation();
			}
		};
	} else if ( 'onmousewheel' in document ) {
		window.onmousewheel= function( event ) {
			if( typeof( this.RDSmoothScroll ) !== undefined ) {
				try { window.removeEventListener( 'onmousewheel', this.RDSmoothScroll.prototype.onWheel ); } catch( error ) {}
				event.stopPropagation();
			}
		};
	}

	try { $('body').unmousewheel(); } catch( error ) {}
});

function include(scriptUrl) {
    document.write('<script src="' + scriptUrl + '"></script>');
}

function isIE() {
    var myNav = navigator.userAgent.toLowerCase();
    return (myNav.indexOf('msie') != -1) ? parseInt(myNav.split('msie')[1]) : false;
};

/* cookie.JS
 ========================================================*/
include('js/jquery.cookie.js');

/* Easing library
 ========================================================*/
include('js/jquery.easing.1.3.js');

/* ToTop
 ========================================================*/
;
(function ($) {
    var o = $('html');
    if (o.hasClass('desktop')) {
        include('js/jquery.ui.totop.js');

        $(document).ready(function () {
            $().UItoTop({easingType: 'easeOutQuart'});
        });
    }
})(jQuery);

/* EqualHeights
 ========================================================*/
;
(function ($) {
    var o = $('[data-equal-group]');
    if (o.length > 0) {
        include('js/jquery.equalheights.js');
    }
})(jQuery);

/* SMOOTH SCROLLIG
 ========================================================*/
;
(function ($) {
    var o = $('html');
    if (o.hasClass('desktop')) {
        include('js/jquery.mousewheel.min.js');
        include('js/jquery.simplr.smoothscroll.min.js');

        $(document).ready(function () {
            $.srSmoothscroll({
                step: 150,
                speed: 800
            });
        });
    }
})(jQuery);

/* Copyright Year
 ========================================================*/
var currentYear = (new Date).getFullYear();
$(document).ready(function () {
    $("#copyright-year").text((new Date).getFullYear());
});

/* WOW
 ========================================================*/
;
(function ($) {
    var o = $('html');

    if ((navigator.userAgent.toLowerCase().indexOf('msie') == -1 ) || (isIE() && isIE() > 9)) {
        if (o.hasClass('desktop')) {
            include('js/wow.js');

            $(document).ready(function () {
                new WOW().init();
            });
        }
    }
})(jQuery);

/* Unveil
 ========================================================*/
;
(function ($) {
    var o = $('.lazy-img img');

    if (o.length > 0) {
        include('js/jquery.unveil.js');

        $(document).ready(function () {
            $(o).unveil(0, function () {
                if (isIE() && isIE() < 9) {
                    $(this).load().addClass("lazy-loaded");
                } else {
                    $(this).load(function () {
                        $(this).addClass("lazy-loaded");
                    })
                }
            });
        });

        $(window).load(function () {
            $(window).trigger('lookup.unveil');
        });

    }
})(jQuery);

/* Orientation tablet fix
 ========================================================*/
$(function () {
    // IPad/IPhone
    var viewportmeta = document.querySelector && document.querySelector('meta[name="viewport"]'),
        ua = navigator.userAgent,

        gestureStart = function () {
            viewportmeta.content = "width=device-width, minimum-scale=0.25, maximum-scale=1.6, initial-scale=1.0";
        },

        scaleFix = function () {
            if (viewportmeta && /iPhone|iPad/.test(ua) && !/Opera Mini/.test(ua)) {
                viewportmeta.content = "width=device-width, minimum-scale=1.0, maximum-scale=1.0";
                document.addEventListener("gesturestart", gestureStart, false);
            }
        };

    scaleFix();
    // Menu Android
    if (window.orientation != undefined) {
        var regM = /ipod|ipad|iphone/gi,
            result = ua.match(regM);
        if (!result) {
            $('.sf-menus li').each(function () {
                if ($(">ul", this)[0]) {
                    $(">a", this).toggle(
                        function () {
                            return false;
                        },
                        function () {
                            window.location.href = $(this).attr("href");
                        }
                    );
                }
            })
        }
    }
});
var ua = navigator.userAgent.toLocaleLowerCase(),
    regV = /ipod|ipad|iphone/gi,
    result = ua.match(regV),
    userScale = "";
if (!result) {
    userScale = ",user-scalable=0"
}
document.write('<meta name="viewport" content="width=device-width,initial-scale=1.0' + userScale + '">')

/* Subscribe Form
========================================================*/
;(function ($) {
    var o = $('#subscribe-form');
    if (o.length > 0) {
        include('js/sForm.js');
    }
})(jQuery);

/* Booking Form
 ========================================================*/
;
(function ($) {
    var o = $('#bookingForm');
    if (o.length > 0) {
        include('js/booking/booking.js');
        include('js/booking/jquery-ui-1.10.3.custom.min.js');
        include('js/booking/jquery.fancyform.js');
        include('js/booking/jquery.placeholder.js');
        include('js/booking/regula.js');
        $(document).ready(function () {
            o.bookingForm({
                ownerEmail: '#'
            });
        });
    }
})(jQuery);

/* FancyBox
 ========================================================*/
;
(function ($) {
    var o = $('.thumb');
    if (o.length > 0) {
        include('js/jquery.fancybox.js');
        include('js/jquery.fancybox-media.js');
        $(document).ready(function () {
            o.fancybox();
        });
    }
})(jQuery);

/* VIDE
 ========================================================*/
;
(function ($) {
    var o = $('.vide');
    if (o.length > 0) {
        include('js/jquery.vide.js');


    }
})(jQuery);

/* Owl Carousel
 ========================================================*/
;
(function ($) {
    var o = $('.owl-carousel');
    if (o.length > 0) {
        include('js/owl.carousel.min.js');
        $(document).ready(function () {
            o.each(function () {
                var $this = $(this);
                // Testimoni: samakan iramanya dengan marquee logo klien
                // (1.8 detik per item — lihat $moveFraction/$rightDuration di
                // resources/views/home.blade.php, bagian partner-marquee).
                var timeout = $this.hasClass('testimonial-carousel') ? 1800 : 2500;

                // Testimoni > 3: pager ringkas (tombol < > + "01 --- 08") menggantikan titik
                // per-testimoni yang sebelumnya bisa belasan dan memanjang. Markup pager ada
                // di home.blade.php (hanya dirender kalau testimoni > 3); kalau tidak ada,
                // carousel memakai titik bawaan (maksimal 3 titik).
                var $pager = $this.hasClass('testimonial-carousel') ? $this.siblings('.testimonial-pager') : $();
                var compact = $pager.length > 0;
                if (compact) {
                    var total = $this.children().length;   // dihitung SEBELUM Owl menambah klon
                    var pad = function (n) { return (n < 10 ? '0' : '') + n; };
                    $this.on('initialized.owl.carousel changed.owl.carousel', function (e) {
                        var owl = e.relatedTarget;
                        if (!owl || typeof owl.relative !== 'function') return;
                        var idx;
                        try { idx = owl.relative(owl.current()); } catch (err) { return; }
                        if (typeof idx !== 'number' || isNaN(idx)) return;
                        idx = ((idx % total) + total) % total;
                        $pager.find('.tp-current').text(pad(idx + 1));
                        $pager.find('.tp-bar').css('width', ((idx + 1) / total * 100) + '%');
                    });
                    $pager.on('click', '.tp-prev', function () {
                        var owl = $this.data('owlCarousel'); if (owl) owl.prev();
                    });
                    $pager.on('click', '.tp-next', function () {
                        var owl = $this.data('owlCarousel'); if (owl) owl.next();
                    });
                }

                $this.owlCarousel({
                    margin: 30,
                    autoplay: true,
                    autoplayTimeout: timeout,
                    autoplaySpeed: 300,
                    loop: true,
                    center: true,
                    smartSpeed: 300,
                    dots: !compact,
                    dotsEach: 1,
                    nav: false,
                    responsive: {
                        0: { items: 1 },
                        768: { items: 2 },
                        992: { items: 3, stagePadding: 60 }
                    }
                });
            });
        });
    }
})(jQuery);

/* Parallax
 =============================================*/
;(function ($) {
    include('js/jquery.rd-parallax.js');
})(jQuery);



