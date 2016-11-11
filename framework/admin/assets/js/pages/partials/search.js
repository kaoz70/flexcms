(function() {
    //------------------------------------------------------------------
    //[1. Close Search Bar]
    //--
    $('.sidebar.form .header-close').on('click', function() {
        $('.sidebar.form').addClass('collapsed');
    });

    //------------------------------------------------------------------
    //[2. Add Scroll]
    //--
    $('.searchbar .search-results').slimscroll({
        touchScrollStep: yima.touchScrollSpeed,
        height: $(window).height() - 125,
        position: 'right',
        size: '2px',
        color: yima.primary
    });
})();