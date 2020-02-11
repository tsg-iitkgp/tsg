var mks_shortcode;
var mks_shortcode_content;
var mks_shortcode_modal_obj;
(function() {

    tinymce.create('tinymce.plugins.mks_shortcodes', {
        init: function(ed, url) {
            ed.addButton('mks_shortcodes_button', {
                title: 'Meks Shortcodes',
                image: url.substring(0, url.length - 3) + "/img/shortcodes-button.png",
                onclick: function() {

                    mks_shortcode = ed.selection;
                    mks_shortcode_content = ed.selection.getContent();

                    var shortcodes_loaded = jQuery("#mks_shortcodes_holder").length;

                    if (shortcodes_loaded) {
                        mks_shortcode_modal(mks_shortcode_modal_obj, 'Meks Shortcodes');

                    } else {

                        jQuery("body").append('<div id="mks_shortcodes_holder" style="display: none;"><div id="mks_shortcodes"></div></div>');

                        jQuery.get('admin-ajax.php?action=mks_generate_shortcodes_ui', function(data) {
                            mks_shortcode_modal_obj = jQuery('#mks_shortcodes').html(data);
                            mks_shortcode_modal( mks_shortcode_modal_obj, 'Meks Shortcodes');
                        });
                    }
                }
            });
        },
        createControl: function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('mks_shortcodes', tinymce.plugins.mks_shortcodes);

    /* Open modal */

    function mks_shortcode_modal(obj, title) {

        obj.dialog({
            'dialogClass': 'wp-dialog',
            'appendTo': false,
            'modal': true,
            'autoOpen': false,
            'closeOnEscape': true,
            'draggable': false,
            'resizable': false,
            'width': 800,
            'height': jQuery(window).height() - 60,
            'title': title,
            'close': function(event, ui) {
                jQuery('body').removeClass('modal-open');
            },
            'buttons': []
        });

        obj.dialog('open');

        jQuery('body').addClass('modal-open');
    }

})();