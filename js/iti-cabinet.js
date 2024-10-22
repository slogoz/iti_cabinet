(function ($) {
    $(document).ready(function () {
        $('.switch-wrapper input[type=checkbox]').switchButton({
            off_label: 'мужской',
            on_label: 'женский',
            width: 52,
            height: 22,
            button_width: 27
        });
    });
})(jQuery);
