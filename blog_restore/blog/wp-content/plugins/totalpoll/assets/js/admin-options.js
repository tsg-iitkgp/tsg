TotalPoll.onReady = function () {
    TotalPoll.Editor.pollId = jQuery('#post_ID').val();

    var containerOptions = {
        items: {
            container: '[data-tp-containables]',
            sortableAttr: 'data-tp-sortable',
            item: {
                idAttr: 'data-tp-containable',
                container: '[data-tp-containable]',
                content: '.totalpoll-containable-content',
                toggleClass: 'active',
                handle: '[data-tp-containable-handle]',
                remove: '[data-tp-containable-remove]',
                preview: '[data-tp-containable-preview]',
                previewSource: '[data-tp-containable-preview-field]',
                upload: {
                    button: '[data-tp-containable-upload]',
                    typeAttr: 'data-tp-containable-upload-type',
                    idAttr: 'data-tp-containable-upload-field-id',
                    labelAttr: 'data-tp-containable-upload-field-label',
                    sizesAttr: 'data-tp-containable-upload-field-sizes',
                    fullAttr: 'data-tp-containable-upload-field-full',
                    thumbnailAttr: 'data-tp-containable-upload-field-thumbnail',
                }
            },
            sortable: {
                axis: 'y',
                items: '[data-tp-containable]',
                handle: '[data-tp-containable-handle]',
                cancel: 'input, button',
                helper: 'original',
            },
        },
        buttons: '[data-tp-containables-insert]',
        templates: {
            item: '[data-tp-containable-template]',
            dataNameAttr: 'tp-containable-template'
        }
    };
    var tabsOptions = {
        tabAttr: 'data-tp-tab',
        tabContentAttr: 'data-tp-tab-content',
    };
    var toggleablesOptions = {
        toggleAttr: 'data-tp-toggle',
        toggleableAttr: 'data-tp-toggleable',
    };


    this.containers = function () {
        jQuery('[data-tp-container]').each(function () {
            if (jQuery(this).data('container_instance') === undefined) {
                jQuery(this).data('container_instance', new TotalPoll.API.Container(this, containerOptions));
            }
        });
    };

    this.tabs = function () {
        jQuery('[data-tp-tabs]').each(function () {
            if (jQuery(this).data('tabs_instance') === undefined) {
                jQuery(this).data('tabs_instance', new TotalPoll.API.Tabs(this, tabsOptions));
            }
        });
    };

    this.toggleables = function () {
        jQuery('[data-tp-toggleables]').each(function () {
            if (jQuery(this).data('toggleables_instance') === undefined) {
                jQuery(this).data('toggleables_instance', new TotalPoll.API.Toggleables(this, toggleablesOptions));
            }
        });
    };

    this.fields = function () {
        jQuery('[data-tp-field-color]').wpColorPicker();
        jQuery('[data-tp-field-date]').datetimepicker({
            mask: true,
            format: 'm/d/Y H:i',
            validateOnBlur: false
        });
    };

    this.templates = function () {
        jQuery('[data-tp-templates]').on('keydown mousedown', function (e) {
            e.target.lastSelectedIndex = e.target.selectedIndex;
        }).on('change', function (e) {
            if (confirm(TotalPoll.i18n.change_template)) {
                jQuery('#totalpoll-options form').submit();
            } else {
                e.target.selectedIndex = e.target.lastSelectedIndex;
                e.target.checked = false;
            }
        });
    };

    this.containers();
    this.tabs();
    this.toggleables();
    this.fields();
    this.templates();

    return this;
};

jQuery(function () {
    TotalPoll.readyCallback = new TotalPoll.onReady();
});