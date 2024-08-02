(function ($) {
    wp.customize('theme_color', function (value) {
        value.bind(function (newval) {
            $('body').css('background-color', newval);
        });
    });
})(jQuery);