(function($) {
    $(document).on('facetwp-refresh', function() {
    if (FWP.loaded) {
        $('.facetwp-template').prepend('<div class="loading-loader"><div class="facet-loader"><img src="https://koopoonline.com/wp-content/uploads/2018/05/TastyGaseousGiraffe.gif" class="loader-img"/></div></div>');
    }
    var hi = $('.facetwp-template').height();
    $('.loading-loader').height(hi);
});
})(jQuery);