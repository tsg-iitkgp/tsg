(function($) {

    $(document).ready(function($) {


        /* Image select option */

        $('body').on('click', 'img.vlog-img-select', function(e) {
            e.preventDefault();
            var this_img = $(this);
            this_img.closest('ul').find('img.vlog-img-select').removeClass('selected');
            this_img.addClass('selected');
            this_img.closest('ul').find('input').removeAttr('checked');
            this_img.closest('li').find('input').attr('checked', 'checked');

        });

      
        
        /* Layout toggle */

        vlog_toggle_category_layout();

        $("body").on("click", "input.layout-type", function(e) {
            vlog_toggle_category_layout();
        });

        /* Sidebar toggle */

        vlog_toggle_category_sidebar();

        $("body").on("click", "input.layout-sidebar", function(e) {
            vlog_toggle_category_sidebar();
        });


        function vlog_toggle_category_layout() {
            var layout_type = $('input.layout-type:checked').val();

            if (layout_type == 'custom') {
                $('.vlog-layout-opt').show();
            } else {
                $('.vlog-layout-opt').hide();
            }

        }

        function vlog_toggle_category_sidebar() {
            var layout_type = $('input.layout-sidebar:checked').val();

            if (layout_type == 'custom') {
                $('.vlog-sidebar-opt').show();
            } else {
                $('.vlog-sidebar-opt').hide();
            }

        }

    });

})(jQuery);