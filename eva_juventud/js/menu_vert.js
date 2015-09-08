(function($) {
    var $window = $(window),
        $html = $('#menu-bar');

    $window.resize(function resize() {
        if ($window.width() < 768) {
           return $html.removeClass('nav-stacked');
        }
        $html.addClass('nav-stacked');
    }).trigger('resize');
})(jQuery);
