jQuery(function ($) {
    $(document).on('totalpoll.after.ajax', function (e, data) {
        data.container.find('.totalpoll-choice-checkbox:checked').closest('.totalpoll-choice').addClass('totalpoll-choice-selected');
    });

    $(document).delegate('.totalpoll-view-vote .totalpoll-choice-checkbox', 'change', function () {
        var $check = $(this);
        var $choice = $check.closest('.totalpoll-choice');
        var $main = $check.closest('.totalpoll-poll-container');

        if ($check.prop('checked') === true) {

            if ($check.attr('type') == 'radio') {
                $main.find('.totalpoll-choice-checkbox').prop('checked', false);
                $main.find('.totalpoll-choice').removeClass('totalpoll-choice-selected');
            }
            $choice.addClass('totalpoll-choice-selected');
            $check.prop('checked', true);
        } else {
            $choice.removeClass('totalpoll-choice-selected');
            $check.prop('checked', false);
        }
    });
});