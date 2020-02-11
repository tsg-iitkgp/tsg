(function($) {
    $(document).ready(function($) {

        if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {

            $("body").on("touchstart", ".mks_accordion_heading", function(e) {
                mks_accordion_handle($(this));
            });

            $("body").on("touchstart", ".mks_toggle_heading", function(e) {
                mks_toggle_handle($(this));
            });


            $("body").on("touchstart", ".mks_tabs_nav .mks_tab_nav_item", function(e) {
                mks_tab_handle($(this));
            });

        } else {

            $("body").on("click", ".mks_accordion_heading", function(e) {
                mks_accordion_handle($(this));
            });


            $("body").on("click", ".mks_toggle_heading", function(e) {
                mks_toggle_handle($(this));
            });


            $("body").on("click", ".mks_tabs_nav .mks_tab_nav_item", function(e) {
                mks_tab_handle($(this));
            });
        }

        /* Initialize tabs */
        $('.mks_tabs').each(function() {

            var tabs_nav = $(this).find('.mks_tabs_nav');

            $(this).find('.mks_tab_item').each(function() {
                tabs_nav.append('<div class="mks_tab_nav_item">' + $(this).find('.nav').html() + '</div>');
                $(this).find('.nav').remove();

            });

            $(this).find('.mks_tabs_nav').find('.mks_tab_nav_item:first').addClass('active');
            $(this).find('.mks_tab_item').hide();
            $(this).find('.mks_tab_item:first').show();
            $(this).show();

        });


    });

    function mks_accordion_handle($obj) {
        var toggle = $obj.parent('.mks_accordion_item');
        if (!toggle.hasClass('mks_accordion_active')) {
            toggle.parent('div').find('.mks_accordion_item').find('.mks_accordion_content:visible').slideUp("fast");
            toggle.parent('div').find('.mks_accordion_active').removeClass('mks_accordion_active');
            toggle.find('.mks_accordion_content').slideToggle("fast", function() {
                toggle.addClass('mks_accordion_active');
                if ((toggle.offset().top + 100) < $(window).scrollTop()) {
                    $('html, body').stop().animate({
                        scrollTop: (toggle.offset().top - 100)
                    }, '300');
                }
            });
        } else {
            toggle.parent('div').find('.mks_accordion_item').find('.mks_accordion_content:visible').slideUp("fast");
            toggle.parent('div').find('.mks_accordion_active').removeClass('mks_accordion_active');
        }
    }

    function mks_toggle_handle($obj) {
        var toggle = $obj.parent('.mks_toggle');
        toggle.find('.mks_toggle_content').slideToggle("fast", function() {
            toggle.toggleClass('mks_toggle_active');
        });
    }

    function mks_tab_handle($obj) {
        if ($obj.hasClass('active') == false) {

            tab_to_show = $obj.parent('.mks_tabs_nav').find('.mks_tab_nav_item').index($obj);

            $obj.parent('.mks_tabs_nav').parent('.mks_tabs').find('.mks_tab_item').hide();
            $obj.parent('.mks_tabs_nav').parent('.mks_tabs').find('.mks_tab_item').eq(tab_to_show).show();

            $obj.parent('.mks_tabs_nav').find('.mks_tab_nav_item').removeClass('active');
            $obj.addClass('active');

        }
    }

})(jQuery);