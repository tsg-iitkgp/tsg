jQuery(function ($) {
    $('.totalpoll-poll-container').addClass('dom-ready');

    /**
     * AJAX
     */
    $(document).delegate('.totalpoll-poll-container [name^="totalpoll[action]"]', 'click', function (e) {
        e.preventDefault();
        var $this = $(this);
        var $container = $this.closest('.totalpoll-poll-container');

        var fields = $container.find('form').serializeArray();
        fields.push({name: $this.attr('name'), value: $this.val()});
        fields.push({name: 'action', value: TotalPoll.AJAX_ACTION || 'tp_action'});

        var transitionType = $container.data('transition-type') || 'fade';

        if (transitionType == 'none') {
            $container.hide();
        } else if (transitionType == 'fade') {
            $container.fadeTo('slow', 0.5).css(
                {
                    'pointer-events': 'none',
                    'min-height': $container.outerHeight()
                }
            );
        } else if (transitionType == 'slide') {
            $container.slideUp();
        }


        $.ajax({
            url: TotalPoll.AJAX || this.action,
            type: 'POST',
            data: fields,
            success: function (content) {
                var $content = $(content).hide();
                var scrollToTop = $container.offset().top;
                $container.after($content).fadeOut(function () {

                    if (transitionType == 'none') {
                        $content.show();
                    } else if (transitionType == 'fade') {
                        $content.fadeIn();
                    } else if (transitionType == 'slide') {
                        $content.slideDown();
                    }

                    $content.addClass('dom-ready');
                    $(this).remove();

                    if ($container.is('[data-scroll-back]')) {
                        $(document.body).animate({scrollTop: scrollToTop - 64});
                    }

                    $(document).trigger('totalpoll.after.ajax', [
                        {
                            button: $this,
                            container: $content,
                        },
                        fields
                    ]);
                });
            }
        });

    });

    /**
     * One-click vote
     */
    $(document).delegate('.totalpoll-poll-container[data-oneclick-vote] [name="totalpoll[choices][]"]', 'click change', function (e) {
        var $this = $(this);
        var $container = $this.closest('.totalpoll-poll-container');
        $container.find('[name^="totalpoll[action]"][value="vote"]').click();
    });

    /**
     * Maximum selection
     */
    $(document).delegate('.totalpoll-poll-container input[name="totalpoll[choices][]"]', 'change', function (e) {
        e.preventDefault();
        var $this = $(this);
        var $poll = $this.closest('.totalpoll-poll-container');
        var $checkboxes = $poll.find('input[name="totalpoll[choices][]"]');
        var $checked = $checkboxes.filter(':checked');
        var $unchecked = $checkboxes.not(':checked');
        var maxSelection = $poll.attr('data-max-selection');

        if (maxSelection > 1 && $checked.length >= maxSelection) {
            $unchecked.attr('disabled', '');
        } else {
            $checkboxes.removeAttr('disabled');
        }

        $checkboxes.each(function () {
            $(this).closest('[data-tp-choice]').toggleClass('checked', this.checked);
        });

    });

    /**
     * reCaptcha
     */
    window.reCaptchaReady = function () {
        $('[data-tp-captcha]').each(function () {
            grecaptcha.render(this, {
                sitekey: TotalPoll.settings.limitations.captcha.sitekey,
            });
        });
    };

    if (TotalPoll.settings.limitations.captcha.enabled) {
        $(document).on('totalpoll.after.ajax', function () {
            window.reCaptchaReady();
        });
    }

    /**
     * Sharing
     */
    if (TotalPoll.settings.sharing.enabled) {
        var shareConfig = {
            description: TotalPoll.settings.sharing.expression,
            networks: {}
        };

        var shareNetworks = ['googlePlus', 'twitter', 'facebook', 'pinterest', 'reddit', 'linkedin', 'whatsapp', 'email'];
        $.each(shareNetworks, function (index, network) {
            shareConfig.networks[network] = {
                enabled: TotalPoll.settings.sharing.networks.indexOf(network) != -1
            }
        });

        // Initialization helper
        var shareInit = function () {
            if (window.ShareButton) {
                new ShareButton(shareConfig);
            }
        };

        // Event listener: Reinitialize share after receiving ajax response
        $(document).on('totalpoll.after.ajax', shareInit);

        // Initialize
        shareInit();
    }

    /**
     * Asynchronous loading
     */
    if (window['TotalPollAsync']) {
        $.each(TotalPollAsync, function (key, poll) {
            var $container = $('#' + poll.container);
            $container.load(
                TotalPoll.AJAX,
                {
                    action: TotalPoll.AJAX_ACTION || 'tp_action',
                    totalpoll: {
                        id: poll.id,
                        action: 'load'
                    }
                },
                function () {
                    $(document).trigger('totalpoll.after.ajax', [
                        {
                            button: $(),
                            container: $container.find('.totalpoll-poll-container'),
                        }
                    ]);
                }
            );
        });
    }

});