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

    var paginateOptions = {
        body: '[data-tp-paginate-body]',
        next: '[data-tp-paginate-next]',
        buttons: '[data-tp-paginate-button]',
        previous: '[data-tp-paginate-previous]',
        last: '[data-tp-paginate-last]',
        first: '[data-tp-paginate-first]',
        count: '[data-tp-paginate-count]',
        action: '[data-tp-paginate-action]',
    };

    var chartsOptions = {
        dataAttr: 'data-tp-chart-data',
        typeAttr: 'data-tp-chart-type',
        chartOptions: {
            PieChart: {
                pieHole: 0.5,
                legend: {position: 'labeled'},
                chartArea: {width: '90%', height: '90%'},
                pieSliceText: 'none',
                vAxis: {
                    format: 'short',
                },
                hAxis: {
                    format: 'short',
                }
            },
            LineChart: {
                pointSize: 6,
                curveType: 'none',
                legend: {position: 'none'},
                chartArea: {width: '90%', height: '70%'},
                vAxis: {
                    format: 'short',
                    baselineColor: '#aaaaaa'
                },
                hAxis: {
                    format: 'short',
                }
            },
            BarChart: {
                sStacked: true,
                legend: {position: 'top', maxLines: 3},
                vAxis: {
                    format: 'short',
                },
                hAxis: {
                    format: 'short',
                }
            }
        }
    };

    var statisticsOptions = {
        action: '[data-tp-statistics-action]',
        progressBar: '[data-tp-statistics-progress-bar]',
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

    this.paginate = function () {
        jQuery('[data-tp-paginate]').each(function () {
            if (jQuery(this).data('paginate_instance') === undefined) {
                jQuery(this).data('paginate_instance', new TotalPoll.API.Paginate(this, paginateOptions));
            }
        });
    };

    this.statistics = function () {
        jQuery('[data-tp-statistics]').each(function () {
            if (jQuery(this).data('progress_instance') === undefined) {
                jQuery(this).data('progress_instance', new TotalPoll.API.Statistics(this, statisticsOptions));
            }
        });
    };

    this.charts = function () {
        jQuery('[data-tp-chart-canvas]').each(function () {
            jQuery(this).data('chart_instance', new TotalPoll.API.Chart(this, chartsOptions));
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

    this.reset = function () {
        jQuery('[data-tp-reset-votes]').on('click', function () {
            if (confirm(TotalPoll.i18n.sure)) {
                jQuery('[data-tp-containable-votes]').val(0);
                jQuery('input[name="totalpoll[settings][limitations][unique_id]"]').prop('checked', true);
            }
        })
    };

    this.bulk = function () {
        var $container = jQuery('[data-tp-bulk-container]');
        var $button = jQuery('[data-tp-insert-bulk]');
        var $choices = jQuery('[data-tp-insert-bulk-choices]');
        var $import = jQuery('[data-tp-insert-bulk-import]');

        $button.on('click', function () {
            $container.show();
        });

        $import.on('click', function () {
            $container.hide();
            var choices = $choices.val().split("\n");
            var container = jQuery('[data-tp-choices]').data('container_instance');
            var choice;
            jQuery.each(choices, function (key, value) {
                choice = container.insert(container.templates['choices-text']);
                choice.$content.find('input[name*="label"]').attr('value', value).trigger('change');
            });
            $choices.val('');
        });

    };

    this.templates = function () {
        jQuery('[data-tp-templates]').on('keydown mousedown', function (e) {
            e.target.lastSelectedIndex = e.target.selectedIndex;
        }).on('change', function (e) {
            if (confirm(TotalPoll.i18n.change_template)) {
                jQuery('#publish').click();
            } else {
                e.target.selectedIndex = e.target.lastSelectedIndex;
                e.target.checked = false;
            }
        });
    };

    this.warnings = function () {
        var interval = setInterval(
            function () {
                if (jQuery('input').length >= TotalPoll.Info.max_input_vars) {
                    alert(TotalPoll.i18n.max_inputs);
                    window.open('http://support.misqtech.com/totalpoll-pro/choices-and-options-not-being-saved/');
                    clearInterval(interval);
                }
            },
            1000 * 30
        );
    };

    this.thumbnailExtractor = function () {
        var YoutubeParse = function (url) {
            var regExp = /.*(?:youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=)([^#\&\?]*).*/;
            var match = url.match(regExp);
            return (match && match[1].length == 11) ? 'https://img.youtube.com/vi/' + match[1] + '/0.jpg' : false;
        };
        jQuery(document).on('paste', '[data-tp-extractor]', function (e) {
            var $this = jQuery(this);
            setTimeout(function () {
                var value = $this.val();
                if (YoutubeParse(value) && confirm(TotalPoll.i18n.extract_thumbnail)) {
                    jQuery($this.attr('data-tp-extractor')).val(YoutubeParse(value));
                }
            }, 1);
        });
    };

    this.containers();
    this.tabs();
    this.toggleables();
    this.paginate();
    this.statistics();
    this.fields();
    this.templates();
    this.reset();
    this.bulk();
    this.warnings();
    this.thumbnailExtractor();

    if (window['google']['charts'] !== undefined) {
        google.charts.load('current', {packages: ['corechart']});
        google.charts.setOnLoadCallback(this.charts);
    }

    return this;
};

jQuery(function () {
    TotalPoll.readyCallback = new TotalPoll.onReady();

    jQuery(window).on('resize', function () {
        TotalPoll.readyCallback.charts();
    });
});