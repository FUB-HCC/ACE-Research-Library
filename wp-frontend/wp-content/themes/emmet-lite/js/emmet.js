/*
 * center menu 
 */
(function ($) {
    "use strict";

    function menu_align() {
        var headerWrap = $('.site-header');
        var navWrap = $('.navbar');
        var logoWrap = $('.site-logo');
        var containerWrap = $('.container');
        var classToAdd = 'header-align-center';
        if (headerWrap.hasClass(classToAdd))
        {
            headerWrap.removeClass(classToAdd);
        }
        var logoWidth = logoWrap.outerWidth();
        var menuWidth = navWrap.outerWidth();
        var containerWidth = containerWrap.width();
        if (menuWidth + logoWidth > containerWidth) {
            headerWrap.addClass(classToAdd);
        } else {
            if (headerWrap.hasClass(classToAdd))
            {
                headerWrap.removeClass(classToAdd);
            }
        }

    }
    function ifraimeResize() {
        $('iframe').each(function () {
            var parentWidth = $(this).parent().width();
            var thisWidth = $(this).attr('width');
            var thisHeight = $(this).attr('height');
            $(this).css('width', parentWidth);
            var newHeight = thisHeight * parentWidth / thisWidth;
            $(this).css('height', newHeight);
        });
    }
    function flexsliderInit() {
        if ($('.gallery.flexslider').length) {
            $('.gallery.flexslider').each(function () {
                $(this).flexslider({
                    animation: "slide",
                    controlNav: false,
                    prevText: "",
                    nextText: "",
                    slideshow: false,
                    animationLoop: false,
                    minItems: 1,
                    maxItems: 1,
                    itemMargin: 0,
                    smoothHeight: false,
                    start: function () {
                        if ($('.masonry-blog').length) {
                            var container = $('.masonry-blog');
                            container.masonry('layout');
                        }
                    }
                });
            });
        }

    }
    function animateAppear(el) {
        el.addClass('anVisible').addClass(el.attr("data-animation"));
        setTimeout(function () {
            el.removeClass('animated').removeClass(el.attr("data-animation")).removeClass('anHidden').removeClass('anVisible');
        }, 2000);
    }


    $(document).ready(function () {     

        if ($('#wpadminbar').length) {
            $('.site-header').addClass('wpadminbar-show');
        }

        /*
         * Superfish menu
         */
        var example = $('#main-menu').superfish({
            onBeforeShow: function () {
                $(this).removeClass('toleft');
                if ($(this).parent().offset()) {
                    if (($(this).parent().offset().left + $(this).parent().width() - $(window).width() + 170) > 0) {
                        $(this).addClass('toleft');
                    }
                }
            }
        });
        /*
         * Back to top
         */
        $('body').on('click', '.toTop', function (e) {
            e.preventDefault();
            var mode = (window.opera) ? ((document.compatMode == "CSS1Compat") ? $('html') : $('body')) : $('html,body');
            mode.animate({
                scrollTop: 0
            }, 800);
            return false;
        });
        /*
         * style select 
         */
        $("select").each(function () {
            if ($(this).parent('.select-wrapper').length === 0) {
                $(this).wrap("<div class='select-wrapper'></div>");
            }
        });
        

        $('#main-menu .current').removeClass('current');
        $('#main-menu a[href$="' + window.location.hash + '"]').parent('li').addClass('current');
        $('body').on('click', '.main-header a[href*="#"]:not([href="#"])', function () {
            var addTo = 0;
            if ($('.site-header').attr('data-sticky-menu') === 'on' && $(window).width() > 767) {
                if ($('.site-header').hasClass('fixed')) {
                    addTo = $('.site-header').outerHeight();
                } else {
                    addTo = $('.site-header').outerHeight() + $('.site-header').outerHeight();
                }
            }
            var headerHeight = 0;
            var hash = this.hash;
            var idName = hash.substring(1);
            var alink = this;
            if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
                var target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                if (target.length) {
                    $('html,body').animate({
                        scrollTop: target.offset().top - headerHeight - addTo
                    }, 1200, function () {
                        $('#main-menu .current').removeClass('current');
                        $('#main-menu a[href$="' + idName + '"]').parent('li').addClass('current');
                    });
                    return false;
                }
            }
        });
        ifraimeResize();

        var container = $('.masonry-blog');
        var top = 0;
        if ($('.site-header').length) {
            top = $('.site-header').offset().top;
        }

        $(window).load(function () {
            menu_align();
            ifraimeResize();
            flexsliderInit();
            if ($.isFunction($.fn.masonry)) {
                container.masonry({
                    itemSelector: '.post',
                    columnWidth: function (containerWidth) {
                        return containerWidth / 3;
                    },
                    animationOptions: {
                        duration: 400
                    },
                    isRTL: $('body').is('.rtl')
                });
                container.infinitescroll({
                    navSelector: ".navigation",
                    nextSelector: ".navigation a:last-child",
                    itemSelector: ".masonry-blog .post",
                    loading: {
                        finishedMsg: '',
                        img: (template_directory_uri.url + '/images/loader.svg'),
                        msgText: ''
                    }
                }, function (newElements) {
                    var newElems = $(newElements).addClass('masonry-hidden');
                    $(newElems).imagesLoaded(function () {
                        container.masonry('appended', $(newElems), true);
                        ifraimeResize();
                        flexsliderInit();
                        setTimeout(function () {
                            $(newElems).removeClass('masonry-hidden');
                        }, 500);

                    });
                });
            }
            $('.animated').appear();

            $(document.body).on('appear', '.animated', function (e, $affected) {
                if (!$(this).hasClass('animation-active')) {
                    animateAppear($(this));
                }
            });
            $('.animated:appeared').each(function () {
                $(this).addClass('animation-active');
                animateAppear($(this));
            });


        });
        $(window).scroll(function () {
            /*
             * Stycky menu
             */
            if ($('.site-header').attr('data-sticky-menu') === 'on') {
                var y = $(this).scrollTop();
                if (y >= top) {
                    $('.site-header').addClass('fixed');
                } else {
                    $('.site-header').removeClass('fixed');
                }
            }
            var addTo = 0;
            if ($('.site-header').attr('data-sticky-menu') === 'on' && $(window).width() > 767) {
                if ($('.site-header').hasClass('fixed')) {
                    addTo = $('.site-header').outerHeight();
                } else {
                    addTo = $('.site-header').outerHeight() + $('.site-header').outerHeight();
                }
            }
            var theme_scrollTop = $(window).scrollTop();
            var headerHeight = $('.site-header').outerHeight()
            var isInOneSection = 'no';
            $("section").each(function () {
                var thisID = '#' + jQuery(this).attr('id');
                var theme_offset = jQuery(this).offset().top;
                var thisHeight = jQuery(this).outerHeight();
                var thisBegin = theme_offset - headerHeight;
                var thisEnd = theme_offset + thisHeight - headerHeight - addTo;
                if (theme_scrollTop >= thisBegin && theme_scrollTop <= thisEnd) {
                    isInOneSection = 'yes';
                    $('#main-menu .current').removeClass('current');

                    $('#main-menu a[href$="' + thisID + '"]').parent('li').addClass('current');
                    return false;
                }
                if (isInOneSection == 'no') {
                    $('#main-menu .current').removeClass('current');
                }
            });
        });
        $(window).resize(function () {
            menu_align();
            ifraimeResize();
        });
    });
})(jQuery);