jQuery(function ($) {

    var ButtonFactory = function (label, callback, cssClass) {
        var template = '<button class="button %cssClass%" style="margin-right: 10px;">%label%</button>'
            .replace('%label%', label)
            .replace('%cssClass%', cssClass || '');

        var button = $(template);
        button.on('click', callback);

        return button;
    };

    var PointerSettingsFactory = function (args) {
        var template = '<h3>%title%</h3><p>%body%</p>'
            .replace('%title%', args.title || '')
            .replace('%body%', args.body || '');

        return $.extend(
            true,
            {
                content: template,
                position: {
                    edge: 'left',
                    align: 'left'
                }
            },
            args
        );
    };

    var Pointers = function (args) {

        var _this = this;
        this.pointers = $();
        this.type = args.type || 'global';
        this.i18n = args.i18n || {
                done: 'Done',
                next: 'Next',
                previous: 'Previous',
            };
        this.current = false;

        if (args.items) {
            $.each(
                args.items,
                function (selector, args) {
                    var instance = $(selector)
                        .pointer(PointerSettingsFactory(args))
                        .data('wpPointer');

                    _this.pointers.push(instance);
                }
            );
        }

        this.next = function () {
            this.open(this.current + 1);
        };

        this.previous = function () {
            this.open(this.current - 1);
        };

        this.start = function () {
            this.open(0);
        };

        this.open = function (index) {
            if (this.pointers[this.current]) {
                this.pointers[this.current].close();
            }

            if (this.pointers[index]) {
                this.current = parseInt(index);

                var instance = this.pointers.get(this.current);
                instance.open();

                var position = instance.element.offset().top - instance.element.outerHeight() - 100;
                var buttons = instance.content.find('.wp-pointer-buttons');

                buttons.find('.close').on('click', $.proxy(this.dismiss, this));
                $(document.body).animate({scrollTop: position});

                if (this.current !== 0) {
                    buttons.append(ButtonFactory(this.i18n.previous, $.proxy(this.previous, this)));
                }

                if (this.current < this.pointers.length - 1) {
                    buttons.append(ButtonFactory(this.i18n.next, $.proxy(this.next, this), 'button-primary'));
                }

                if (this.current === this.pointers.length - 1) {
                    buttons.append(ButtonFactory(this.i18n.done, $.proxy(this.dismiss, this), 'button-primary'));
                }
            }
        };

        this.dismiss = function () {
            this.pointers[this.current].close();
            jQuery.get(ajaxurl, {action: 'totalpoll_hide_pointers', type: this.type});
        };

    };

    if (window.totalpollPointers) {
        var pointers = new Pointers(window.totalpollPointers);
        pointers.start();
    }

});