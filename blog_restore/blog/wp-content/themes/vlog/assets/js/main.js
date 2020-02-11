(function($) {

    "use strict";

    $(document).ready(function() {


        /* Logo check */

        vlog_logo_setup();

        /* Sticky header */

        if (vlog_js_settings.header_sticky) {

            var vlog_last_top;

            if ($('#wpadminbar').length && $('#wpadminbar').is(':visible')) {
                $('.vlog-sticky-header').css('top', $('#wpadminbar').height());
            }

            $(window).scroll(function() {

                var top = $(window).scrollTop();

                if (vlog_js_settings.header_sticky_up) {
                    if (vlog_last_top > top && top >= vlog_js_settings.header_sticky_offset) {
                        if (!$("body").hasClass('vlog-header-sticky-on')) {
                            $("body").addClass("vlog-sticky-header-on");
                        }
                    } else {
                        $("body").removeClass("vlog-sticky-header-on");
                        $('.vlog-sticky-header .vlog-action-search.active i').addClass('fv-search').removeClass('fv-close');
                        $('.vlog-sticky-header .vlog-actions-button').removeClass('active');

                    }
                } else {
                    if (top >= vlog_js_settings.header_sticky_offset) {
                        if (!$("body").hasClass('vlog-header-sticky-on')) {
                         $("body").addClass("vlog-sticky-header-on");
                        }
                    } else {
                        $("body").removeClass("vlog-sticky-header-on");
                        $('.vlog-sticky-header .vlog-action-search.active i').addClass('fv-search').removeClass('fv-close');
                        $('.vlog-sticky-header .vlog-actions-button').removeClass('active');

                    }
                }

                vlog_last_top = top;
            });
        }

        /* Top bar height check and admin bar fixes*/
      
        var vlog_admin_top_bar_height = 0;
        vlog_top_bar_check();        

        function vlog_top_bar_check() {
            if ($('#wpadminbar').length && $('#wpadminbar').is(':visible')) {
                vlog_admin_top_bar_height = $('#wpadminbar').height();

            }

            vlog_responsive_header();

        }

        function vlog_responsive_header() {

            if ($('.vlog-responsive-header').length) {

                $('.vlog-responsive-header').css('top', vlog_admin_top_bar_height);

            

                if (vlog_admin_top_bar_height > 0 && $('#wpadminbar').css('position') == 'absolute') {

                    if ($(window).scrollTop() <= vlog_admin_top_bar_height) {
                        $('.vlog-responsive-header').css('position', 'absolute');
                    } else {
                        $('.vlog-responsive-header').css('position', 'fixed').css('top', 0);
                    }

                }

            }
        }

        $(window).scroll(function() {

            vlog_responsive_header();

        });

        /* Responsive menu */

        $('#dl-menu').dlmenu({
            animationClasses: {
                classin: 'dl-animate-in-2',
                classout: 'dl-animate-out-2'
            }
        });

        /* Featured area sliders */

        $(".vlog-featured-slider").each(function() {

            $(this).owlCarousel({
                loop: true,
                autoHeight: false,
                autoWidth: false,
                items: 1,
                margin: 0,
                nav: true,
                animateOut: 'fadeOut',
                animateIn: 'fadeIn',
                center: false,
                fluidSpeed: 100,
                mouseDrag: false,
                autoplay: parseInt(vlog_js_settings.cover_autoplay) ? true : false,
                autoplayTimeout: parseInt(vlog_js_settings.cover_autoplay_time) * 1000,
                autoplayHoverPause: true,
                onChanged: function(elem) {

                    var current = this.$element.find('.owl-item.active');
                    var format_content = current.find('.vlog-format-content');

                    if (format_content !== undefined && format_content.children().length !== 0) {

                        var item_wrap = current.find('.vlog-featured-item');
                        var cover = item_wrap.find('.vlog-cover');

                        if (cover.attr('data-action') == 'audio' || cover.attr('data-action') == 'video') {

                            var cover_bg = current.find('.vlog-cover-bg');
                            var inplay = item_wrap.find('.vlog-format-inplay');

                            format_content.html('');
                            format_content.fadeOut(300);
                            inplay.fadeOut(300);
                            cover.fadeIn(300);
                            item_wrap.find('.vlog-f-hide').fadeIn(300);
                            cover_bg.removeAttr('style');


                        }
                    }

                },
                navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>']
            });
        });


        $('.vlog-featured-slider-4').owlCarousel({
            stagePadding: 200,
            loop: true,
            margin: 0,
            items: 1,
            center: true,
            nav: true,
            autoWidth: true,
            autoplay: parseInt(vlog_js_settings.cover_autoplay) ? true : false,
            autoplayTimeout: parseInt(vlog_js_settings.cover_autoplay_time) * 1000,
            autoplayHoverPause: true,
            navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
            responsive: {
                0: {
                    items: 1,
                    stagePadding: 200
                },
                600: {
                    items: 1,
                    stagePadding: 200
                },
                990: {
                    items: 1,
                    stagePadding: 200
                },
                1200: {
                    items: 1,
                    stagePadding: 250
                },
                1400: {
                    items: 1,
                    stagePadding: 300
                },
                1600: {
                    items: 1,
                    stagePadding: 350
                },
                1800: {
                    items: 1,
                    stagePadding: 768
                }
            }
        });

        /* Module slider */

        $(".vlog-slider").each(function() {
            var controls = $(this).closest('.vlog-module').find('.vlog-slider-controls');
            var module_columns = $(this).closest('.vlog-module').attr('data-col');
            var layout_columns = controls.attr('data-col');
            var slider_items = module_columns / layout_columns;
            var autoplay = parseInt(controls.attr('data-autoplay')) ? true : false;
            var autoplay_time = parseInt(controls.attr('data-autoplay-time')) * 1000;

            $(this).owlCarousel({
                rtl: (vlog_js_settings.rtl_mode === "true"),
                loop: true,
                autoHeight: false,
                autoWidth: false,
                items: slider_items,
                margin: 40,
                nav: true,
                center: false,
                fluidSpeed: 100,
                autoplay: autoplay,
                autoplayTimeout: autoplay_time,
                autoplayHoverPause: true,
                navContainer: controls,
                navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
                responsive: {
                    0: {
                        margin: 0,
                        items: (layout_columns <= 2) ? 2 : 1
                    },
                    1023: {
                        margin: 36,
                        items: slider_items
                    }
                }
            });
        });

        /* Widget slider */

        $(".vlog-widget-slider").each(function() {
            var $controls = $(this).closest('.widget').find('.vlog-slider-controls');

            $(this).owlCarousel({
                rtl: (vlog_js_settings.rtl_mode === "true"),
                loop: true,
                autoHeight: false,
                autoWidth: false,
                items: 1,
                nav: true,
                center: false,
                fluidSpeed: 100,
                margin: 0,
                navContainer: $controls,
                navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>']
            });
        });


        /* On window resize-events */

        $(window).resize(function() {
            vlog_sticky_sidebar();
            vlog_logo_setup();
            vlog_sidebar_switch();
        });


        /* Check if there is colored section below featured area */

        $(".vlog-featured-1").each(function() {
            var $featured = $(this);
            var $vlog_bg = $(this).next();
            var $vlog_bg_color = $vlog_bg.css('background-color');

            if($vlog_bg.hasClass('vlog-bg')){
              $featured.css('background-color', $vlog_bg_color);
            }
        });



        /* Fitvidjs functionality on single posts */

        vlog_fit_videos($('.vlog-single-content .entry-media, .entry-content-single'));


        /* Highlight area hovers */

        $(".vlog-featured .vlog-highlight .entry-title a,.vlog-featured .vlog-highlight .action-item,.vlog-active-hover .entry-title,.vlog-active-hover .action-item").hover(function() {
                $(this).siblings().stop().animate({
                    opacity: 0.4
                }, 150);
                $(this).parent().siblings().stop().animate({
                    opacity: 0.4
                }, 150);
                $(this).parent().parent().siblings().stop().animate({
                    opacity: 0.4
                }, 150);
            },
            function() {
                $(this).siblings().stop().animate({
                    opacity: 1
                }, 150);
                $(this).parent().siblings().stop().animate({
                    opacity: 1
                }, 150);
                $(this).parent().parent().siblings().stop().animate({
                    opacity: 1
                }, 150);
            });


        /* Header search */

        $('body').on('click', '.vlog-action-search span', function() {

            $(this).find('i').toggleClass('fv-close', 'fv-search');
            $(this).closest('.vlog-action-search').toggleClass('active');
            setTimeout(function() {
                $('.active input[type="text"]').focus()
            }, 150);

            if($('.vlog-responsive-header .vlog-watch-later').hasClass('active')){
                $('.vlog-responsive-header .vlog-watch-later').removeClass('active');
            }

        });

        $('body').on('click', '.vlog-responsive-header .vlog-watch-later span', function() {

            $(this).closest('.vlog-watch-later').toggleClass('active');
            $('.vlog-responsive-header .vlog-action-search').removeClass('active').find('i').removeClass('fv-close').addClass('fv-search');
            $('.vlog-responsive-header .vlog-watch-later.active .sub-menu').css('width', $(window).width()).css('height', $(window).height());


        });

        $(document).on('click', function(evt) {
            if (!$(evt.target).is('.vlog-responsive-header .vlog-action-search')) {
                if ($('.vlog-responsive-header').hasClass('vlog-res-open')) {
                    $(".vlog-responsive-header .dl-trigger").trigger("click");
                }

                $('.vlog-responsive-header .vlog-action-search.active .sub-menu').css('width', $(window).width());
            }
        });

        /* On images loaded events */

        $('body').imagesLoaded(function() {
            vlog_sticky_sidebar();
            vlog_sidebar_switch();

        });


        $('.vlog-cover-bg:first').imagesLoaded(function() {
            $('.vlog-cover').animate({
                opacity: 1
            }, 300);
        });


        /* Share buttons click */

        $('body').on('click', '.vlog-share-item', function(e) {
            e.preventDefault();
            var data = $(this).attr('data-url');
            vlog_social_share(data);
        });

        /* Load more button handler */

        var vlog_load_ajax_new_count = 0;

        $("body").on('click', '.vlog-load-more a', function(e) {
            e.preventDefault();
            var $link = $(this);
            var page_url = $link.attr("href");
            $link.addClass('vlog-loader-active');
            $('.vlog-loader').show();
            $("<div>").load(page_url, function() {
                var n = vlog_load_ajax_new_count.toString();
                var $wrap = $link.closest('.vlog-module').find('.vlog-posts');
                var $new = $(this).find('.vlog-module:last article').addClass('vlog-new-' + n);
                var $this_div = $(this);

                $new.imagesLoaded(function() {

                    $new.hide().appendTo($wrap).fadeIn(400);

                    if ($this_div.find('.vlog-load-more').length) {
                        $('.vlog-load-more').html($this_div.find('.vlog-load-more').html());
                        $('.vlog-loader').hide();
                        $link.removeClass('vlog-loader-active');
                    } else {
                        $('.vlog-load-more').fadeOut('fast').remove();
                    }

                    vlog_sticky_sidebar();

                    if (page_url != window.location) {
                        window.history.pushState({
                            path: page_url
                        }, '', page_url);
                    }

                    vlog_load_ajax_new_count++;

                    return false;
                });

            });

        });


        /* Infinite scroll handler */

        var vlog_infinite_allow = true;

        if ($('.vlog-infinite-scroll').length) {
            $(window).scroll(function() {
                if (vlog_infinite_allow && $('.vlog-infinite-scroll').length && ($(this).scrollTop() > ($('.vlog-infinite-scroll').offset().top) - $(this).height() - 200)) {
                    var $link = $('.vlog-infinite-scroll a');
                    var page_url = $link.attr("href");
                    if (page_url != undefined) {
                        vlog_infinite_allow = false;
                        $('.vlog-loader').show();
                        $("<div>").load(page_url, function() {
                            var n = vlog_load_ajax_new_count.toString();
                            var $wrap = $link.closest('.vlog-module').find('.vlog-posts');
                            var $new = $(this).find('.vlog-module:last article').addClass('vlog-new-' + n);
                            var $this_div = $(this);

                            $new.imagesLoaded(function() {

                                $new.hide().appendTo($wrap).fadeIn(400);

                                if ($this_div.find('.vlog-infinite-scroll').length) {
                                    $('.vlog-infinite-scroll').html($this_div.find('.vlog-infinite-scroll').html());
                                    $('.vlog-loader').hide();
                                    vlog_infinite_allow = true;
                                } else {
                                    $('.vlog-infinite-scroll').fadeOut('fast').remove();
                                }

                                vlog_sticky_sidebar();

                                if (page_url != window.location) {
                                    window.history.pushState({
                                        path: page_url
                                    }, '', page_url);
                                }

                                vlog_load_ajax_new_count++;

                                return false;
                            });

                        });
                    }
                }
            });
        }


        /* Cover format actions */

        $("body").on('click', '.vlog-cover', function(e) {
            e.preventDefault();
            var action = $(this).attr('data-action');
            var container = $(this).closest('.vlog-cover-bg').find('.vlog-format-content');
            var item_wrap = $(this).closest('.vlog-featured-item');
            var cover_bg = $(this).closest('.vlog-cover-bg');
            var inplay = item_wrap.find('.vlog-format-inplay');

            if (action == 'video') {

                var data = {
                    action: 'vlog_format_content',
                    format: 'video',
                    id: $(this).attr('data-id')
                };

                var opener = $(this);

                opener.fadeOut(300, function() {
                    container.append('<div class="vlog-format-loader"><div class="uil-ripple-css"><div></div><div></div></div></div>');
                    container.fadeIn(300);


                    inplay.find('.container').html('');
                    inplay.find('.container').append(item_wrap.find('.entry-header').clone()).append(item_wrap.find('.entry-actions').clone());


                    $.post(vlog_js_settings.ajax_url, data, function(response) {

                        container.find('.vlog-format-loader').remove();


                        container.append('<div class="vlog-popup-wrapper">' + response + '</div>');

                        vlog_fit_videos(container);

                        item_wrap.find('.vlog-f-hide').fadeOut(300);

                        //Try to force autoplay
                        $('body').addClass('vlog-in-play');

                        if (container.find('video').length) {
                            container.find('video').attr('autoplay', 'true');
                            container.find('video').mediaelementplayer();

                        } else if (container.find('iframe').length) {

                            var video = container.find('iframe');

                            if (video.attr('src').match(/\?/gi)) {
                                video.attr('src', video.attr('src') + '&autoplay=1');
                            } else {
                                video.attr('src', video.attr('src') + '?autoplay=1');
                            }
                        }

                        setTimeout(function() {

                            if ($(window).width() > 768) {
                                cover_bg.animate({
                                    height: cover_bg.get(0).scrollHeight
                                }, 300);
                            } else {
                                cover_bg.css('height', 'auto');
                                cover_bg.parent().css('height', 'auto');
                            }

                        }, 50);

                        inplay.slideDown(300);

                    });

                });



            }

            if (action == 'audio') {

                var data = {
                    action: 'vlog_format_content',
                    format: 'audio',
                    id: $(this).attr('data-id')
                };

                var opener = $(this);

                opener.fadeOut(300, function() {
                    container.append('<div class="vlog-format-loader"><div class="uil-ripple-css"><div></div><div></div></div></div>');
                    container.fadeIn(300);

                    item_wrap.find('.vlog-f-hide').fadeOut(300);
                    inplay.find('.container').append(item_wrap.find('.entry-header').clone()).append(item_wrap.find('.entry-actions').clone());

                    $.post(vlog_js_settings.ajax_url, data, function(response) {

                        //var $response = $($.parseHTML(response));
                        container.find('.vlog-format-loader').remove();
                        container.append(response);

                        setTimeout(function() {

                            cover_bg.animate({
                                height: cover_bg.get(0).scrollHeight
                            }, 300);


                        }, 50);

                        inplay.slideDown(300);
                        if (container.find('audio').length) {
                            container.find('audio').attr('autoplay', 'true');
                            container.find('audio').mediaelementplayer( /* Options */ );
                        }
                    });

                });



            }

            if (action == 'gallery') {

                var items = new Array();

                container.find('.gallery-item a').each(function() {
                    items.push({
                        src: $(this).attr('href')
                    });
                });


                $.magnificPopup.open({
                    items: items,
                    gallery: {
                        enabled: true
                    },
                    type: 'image',
                    removalDelay: 300,
                    mainClass: 'mfp-with-fade',
                    closeBtnInside: false,
                    closeMarkup: '<button title="%title%" type="button" class="mfp-close"><i class="fv fv-close"></i></button>',
                    callbacks: {
                        open: function() {
                            $.magnificPopup.instance.next = function() {
                                var self = this;
                                self.wrap.removeClass('mfp-image-loaded');
                                setTimeout(function() {
                                    $.magnificPopup.proto.next.call(self);
                                }, 120);
                            }
                            $.magnificPopup.instance.prev = function() {
                                var self = this;
                                self.wrap.removeClass('mfp-image-loaded');
                                setTimeout(function() {
                                    $.magnificPopup.proto.prev.call(self);
                                }, 120);
                            }
                        },
                        imageLoadComplete: function() {
                            var self = this;
                            setTimeout(function() {
                                self.wrap.addClass('mfp-image-loaded');
                            }, 16);
                        }
                    }
                });

            }

            if (action == 'image') {
                var image = $(this).attr('data-image');
                var link = $(this);
                $.magnificPopup.open({
                    items: {
                        src: image
                    },
                    type: 'image',
                    removalDelay: 300,
                    mainClass: 'mfp-with-fade',
                    closeBtnInside: false,
                    image: {
                        titleSrc: function(item) {
                            var $caption = link.parent().find('.wp-caption-text');
                            if ($caption !== undefined) {
                                return $caption.text();
                            }
                            return '';
                        }
                    },
                    closeMarkup: '<button title="%title%" type="button" class="mfp-close"><i class="fv fv-close"></i></button>',
                    callbacks: {
                        open: function() {
                            //overwrite default prev + next function. Add timeout for css3 crossfade animation
                            $.magnificPopup.instance.next = function() {
                                var self = this;
                                self.wrap.removeClass('mfp-image-loaded');
                                setTimeout(function() {
                                    $.magnificPopup.proto.next.call(self);
                                }, 120);
                            }
                            $.magnificPopup.instance.prev = function() {
                                var self = this;
                                self.wrap.removeClass('mfp-image-loaded');
                                setTimeout(function() {
                                    $.magnificPopup.proto.prev.call(self);
                                }, 120);
                            }
                        },
                        imageLoadComplete: function() {
                            var self = this;
                            setTimeout(function() {
                                self.wrap.addClass('mfp-image-loaded vlog-f-img');
                            }, 16);
                        }
                    }
                });
            }

        });


        /* Watch Later */

        $("body").on('click', '.action-item.watch-later', function(e) {
            e.preventDefault();

            var container = $('.vlog-watch-later');
            var counter = container.find('.vlog-watch-later-count');
            var posts = container.find('.vlog-menu-posts');
            var empty = container.find('.vlog-wl-empty');
            var what = $(this).attr('data-action');

            if (what == 'add') {

                $(this).find('i.fv').removeClass('fv-watch-later').addClass('fv-added');
                counter.text(parseInt(counter.first().text()) + 1);
                $(this).attr('data-action', 'remove');

            } else {

                $(this).find('i.fv').removeClass('fv-added').addClass('fv-watch-later');
                counter.text(parseInt(counter.first().text()) - 1);
                $(this).attr('data-action', 'add');
            }

            if (parseInt(counter.text()) > 0) {
                counter.fadeIn(300);
                empty.fadeOut(300);
            } else {
                counter.fadeOut(300);
                empty.fadeIn(300);
            }

            $(this).find('span').removeClass('hidden');
            $(this).find('span.' + what).addClass('hidden');

            var data = {
                action: 'vlog_watch_later',
                what: what,
                id: $(this).attr('data-id')
            };

            $.post(vlog_js_settings.ajax_url, data, function(response) {
                posts.html(response);
            });

        });

        $("body").on('click', '.vlog-remove-wl', function(e) {
            e.preventDefault();

            var container = $('.vlog-watch-later');
            var counter = container.find('.vlog-watch-later-count');
            var empty = container.find('.vlog-wl-empty');

            counter.text(parseInt(counter.first().text()) - 1);

            $(this).closest('.wl-post').fadeOut(300).remove();

            if (parseInt(counter.text()) == 0) {
                counter.fadeOut(300);
                empty.fadeIn(300);
            }

            var data = {
                action: 'vlog_watch_later',
                what: 'remove',
                id: $(this).attr('data-id')
            };

            $.post(vlog_js_settings.ajax_url, data, function(response) {
                //posts.html(response);
            });

        });

        if (vlog_js_settings.watch_later_ajax && $('.vlog-watch-later').length) {
            $.post(vlog_js_settings.ajax_url, {
                action: 'vlog_load_watch_later'
            }, function(response) {
                $('.vlog-watch-later').html(response);
            });
        }



        /* Cinema mode */

        var vlog_before_cinema_height;
        var vlog_before_cinema_width;

        $("body").on('click', '.action-item.cinema-mode', function(e) {
            e.preventDefault();

            var current_video = $(this).closest('.vlog-featured-item').find('.vlog-format-content');

            $(window).scrollTop(0);

            $('body').addClass('vlog-popup-on');
            current_video.addClass('vlog-popup');


            if ($('.vlog-featured-slider').length) {
                vlog_before_cinema_height = current_video.height();
                vlog_before_cinema_width = current_video.width();



                if ($(window).width() > 990) {
                    current_video.height($(window).height()).width($(window).width()).css('top', -$('.vlog-site-header').height()).css('marginTop', -$('.vlog-site-header').height() / 2);
                } else {
                    current_video.height($(window).height()).width($(window).width()).css('top', -50).css('marginTop', -$('.vlog-site-header').height() / 2);
                    $('.vlog-responsive-header').css('z-index', 0);
                }


                var current_slide = $('.vlog-popup').parent().parent().parent();
                current_slide.attr('data-width', current_slide.width()).height($(window).height()).width($(window).width());



                $('.vlog-header-wrapper').css('z-index', 0);

            }

            if ($('.vlog-single-content .vlog-format-content').length && $(window).width() < 1367) {
                vlog_before_cinema_height = current_video.height();
                vlog_before_cinema_width = current_video.width();
                current_video.height($(window).height()).width($(window).width());
            }

            if (current_video.is(':empty')) {
                current_video.closest('.vlog-cover-bg').find('.vlog-cover').click();
            }

            current_video.append('<a class="vlog-popup-close" href="javascript:void(0);"><i class="fv fv-close"></i></a>');
            if ($('.vlog-featured-slider').length) {
                $('.vlog-popup-close').css('top', $('.vlog-site-header').height() - 20);
            }



        });


        /* Close popup */

        $("body").on('click', '.vlog-popup-close', function(e) {

            var cover_bg = $(this).closest('.vlog-cover-bg');

            if ($('.vlog-featured-slider').length) {

                $(this).closest('.vlog-format-content').removeAttr('style');
                $('.vlog-header-wrapper').css('z-index', 10);
                var current_slide = $('.vlog-popup').parent().parent().parent();
                current_slide.removeAttr('style').css('width', current_slide.attr('data-width'));

            }

            if ($('.vlog-single-content .vlog-format-content').length && $(window).width() < 1367) {
                $(this).closest('.vlog-format-content').removeAttr('style');
            }

            $(this).closest('.vlog-format-content').removeClass('vlog-popup');

            $('body').removeClass('vlog-popup-on');
            $('.action-item, .entry-header').removeAttr('style');
            $(this).remove();

            setTimeout(function() {
                //cover_bg.removeAttr('style');  
                cover_bg.animate({
                    height: cover_bg.get(0).scrollHeight
                }, 300);

            }, 50);

            if ($(window).width() < 990) {
                $('.vlog-responsive-header').removeAttr('style');
            }


        });

        /* Close popup on Escape */

        $(document).keyup(function(ev) {
            if (ev.keyCode == 27 && $('body').hasClass('vlog-popup-on')) {

                $('.vlog-popup-close').click();

            }
        });


        /* Cover in play mode */

        if (vlog_js_settings.cover_inplay) {

            if ($('.vlog-cover-bg').length && $('.vlog-cover-bg').hasClass('video')) {

                vlog_fit_videos($('.vlog-format-content'));

                setTimeout(function() {

                    $('.vlog-cover-bg').animate({
                        height: $('.vlog-cover-bg').get(0).scrollHeight
                    }, 300);
                    $('.vlog-format-inplay').slideDown(300);

                }, 50);
            }


        }

        /* Reverse submenu ul if out of the screen */

        $('.vlog-main-nav li').hover(function(e) {
            if ($(this).closest('body').width() < $(document).width()) {

                $(this).find('ul').addClass('vlog-rev');
            }
        }, function() {
            $(this).find('ul').removeClass('vlog-rev');
        });

        /* Scroll to comments */

        $('body').on('click', '.vlog-single-cover .entry-actions a.comments, .vlog-single-cover .meta-comments a, .vlog-single-content .meta-comments a:first', function(e) {

            e.preventDefault();
            var target = this.hash,
                $target = $(target);


            $('html, body').stop().animate({
                'scrollTop': $target.offset().top
            }, 900, 'swing', function() {
                window.location.hash = target;
            });

        });


        /* Initialize gallery pop-up */

        vlog_popup_gallery($('.vlog-site-content'));

        /* Initialize image popup  */

        vlog_popup_image( $('.vlog-content') );


        /* Sticky sidebar */

        function vlog_sticky_sidebar() {
            if ($(window).width() >= 1024) {
                if ($('.vlog-sticky').length) {
                    $('.vlog-sidebar').each(function() {
                        var $section = $(this).closest('.vlog-section');
                        if ($section.find('.vlog-ignore-sticky-height').length) {
                            var section_height = $section.height() - $section.find('.vlog-ignore-sticky-height').height();
                        } else {
                            var section_height = $section.height();
                        }

                        $(this).css('min-height', section_height);
                    });
                }
            } else {
                $('.vlog-sidebar').each(function() {
                    $(this).css('height', 'auto');
                    $(this).css('min-height', '1px');
                });
            }
            $(".vlog-sticky").stick_in_parent({
                parent: ".vlog-sidebar",
                inner_scrolling: false,
                offset_top: 99
            });
            if ($(window).width() < 1024) {
                $(".vlog-sticky").trigger("sticky_kit:detach");
            }
        }

        /* Put sidebars below content in responsive mode */
        function vlog_sidebar_switch() {
            $('.vlog-sidebar-left').each(function() {
                if ($(window).width() < 992) {
                    console.log($(window).width());
                    $(this).insertAfter($(this).next());
                } else {
                    $(this).insertBefore($(this).prev());
                }
            });
        }


        /* Share popup function */

        function vlog_social_share(data) {
            window.open(data, "Share", 'height=500,width=760,top=' + ($(window).height() / 2 - 250) + ', left=' + ($(window).width() / 2 - 380) + 'resizable=0,toolbar=0,menubar=0,status=0,location=0,scrollbars=0');
        }


        /* Switch to retina logo */

        var vlog_retina_logo_done = false,
            vlog_retina_mini_logo_done = false;

        function vlog_logo_setup() {

            //Retina logo
            if (window.devicePixelRatio > 1) {

                if (vlog_js_settings.logo_retina && !vlog_retina_logo_done && $('.vlog-logo').length) {
                    $('.vlog-logo').imagesLoaded(function() {

                        $('.vlog-logo').each(function() {
                            if ($(this).is(':visible')) {
                                var width = $(this).width();
                                $(this).attr('src', vlog_js_settings.logo_retina).css('width', width + 'px');
                            }
                        });
                    });

                    vlog_retina_logo_done = true;
                }

                if (vlog_js_settings.logo_mini_retina && !vlog_retina_mini_logo_done && $('.vlog-logo-mini').length) {
                    $('.vlog-logo-mini').imagesLoaded(function() {
                        $('.vlog-logo-mini').each(function() {
                            if ($(this).is(':visible')) {
                                var width = $(this).width();
                                $(this).attr('src', vlog_js_settings.logo_mini_retina).css('width', width + 'px');
                            }
                        });
                    });

                    vlog_retina_mini_logo_done = true;
                }
            }
        }


        /* Pop-up gallery function */

        function vlog_popup_gallery(obj) {
            obj.find('.gallery').each(function() {
                $(this).find('.gallery-icon a').magnificPopup({
                    type: 'image',
                    gallery: {
                        enabled: true
                    },

                    image: {
                        titleSrc: function(item) {
                            var $caption = item.el.closest('.gallery-item').find('.gallery-caption');
                            if ($caption != 'undefined') {
                                return $caption.text();
                            }
                            return '';
                        }
                    },
                    removalDelay: 300,
                    mainClass: 'mfp-with-fade',
                    closeBtnInside: false,
                    closeMarkup: '<button title="%title%" type="button" class="mfp-close"><i class="fv fv-close"></i></button>',
                    callbacks: {
                        open: function() {
                            $.magnificPopup.instance.next = function() {
                                var self = this;
                                self.wrap.removeClass('mfp-image-loaded');
                                setTimeout(function() {
                                    $.magnificPopup.proto.next.call(self);
                                }, 120);
                            }
                            $.magnificPopup.instance.prev = function() {
                                var self = this;
                                self.wrap.removeClass('mfp-image-loaded');
                                setTimeout(function() {
                                    $.magnificPopup.proto.prev.call(self);
                                }, 120);
                            }
                        },
                        imageLoadComplete: function() {
                            var self = this;
                            setTimeout(function() {
                                self.wrap.addClass('mfp-image-loaded');
                            }, 16);
                        }
                    }
                });
            });
        }

        /* Popup image function */

        function vlog_popup_image(obj) {

            if (obj.find("a.vlog-popup-img").length) {

                var popupImg = obj.find("a.vlog-popup-img");

                popupImg.find('img').each(function() {
                    var $that = $(this);
                    if ($that.hasClass('alignright')) {
                        $that.removeClass('alignright').parent().addClass('alignright');
                    }
                    if ($that.hasClass('alignleft')) {
                        $that.removeClass('alignleft').parent().addClass('alignleft');
                    }
                });

                popupImg.magnificPopup({
                    type: 'image',
                    gallery: {
                        enabled: true
                    },
                    image: {
                        titleSrc: function(item) {
                            return item.el.closest('.wp-caption').find('figcaption').text();
                        }
                    },
                    removalDelay: 300,
                    mainClass: 'mfp-with-fade',
                    closeBtnInside: false,
                    closeMarkup: '<button title="%title%" type="button" class="mfp-close"><i class="fv fv-close"></i></button>',
                    callbacks: {
                        open: function() {
                            $.magnificPopup.instance.next = function() {
                                var self = this;
                                self.wrap.removeClass('mfp-image-loaded');
                                setTimeout(function() {
                                    $.magnificPopup.proto.next.call(self);
                                }, 120);
                            }
                            $.magnificPopup.instance.prev = function() {
                                var self = this;
                                self.wrap.removeClass('mfp-image-loaded');
                                setTimeout(function() {
                                    $.magnificPopup.proto.prev.call(self);
                                }, 120);
                            }
                        },
                        imageLoadComplete: function() {
                            var self = this;
                            setTimeout(function() {
                                self.wrap.addClass('mfp-image-loaded');
                            }, 16);
                        }
                    }
                });
            }

        }


        /* Fitvidjs function */
        function vlog_fit_videos(obj) {
            //obj.find('iframe').removeAttr('width').removeAttr('height');
            obj.fitVids({
                customSelector: "iframe[src^='https://www.dailymotion.com'], iframe[src^='https://player.twitch.tv'], iframe[src^='https://vine.co'], iframe[src^='https://videopress.com'], iframe[src^='https://www.facebook.com'],iframe[src^='//content.jwplatform.com'],iframe[src^='//fast.wistia.net'],iframe[src^='//www.vooplayer.com']"
            });
        }



    }); //document ready end


})(jQuery);