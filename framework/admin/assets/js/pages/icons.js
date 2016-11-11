var yimaPage = function() {
    var initilize = function() {
        if (!Modernizr.mq('(max-width: 1600px)')) {
            $(".sidebar.form").load(yima.getAssetPath("Partials/SearchIcons.html"));
            yima.toggleFormSidebar('Search-Icon');
        }

        $('#action-search a').unbind('click').click(function() {
            $(".sidebar.form").load(yima.getAssetPath("Partials/SearchIcons.html"));
            yima.toggleFormSidebar('Search-Icon');
            $('#action-stretch-menu').removeClass('open');
        });
    }

    return {
        init: initilize
    }
}();

jQuery(document).ready(function() {
    if (yima.isAngular() === false) {
        yimaPage.init();
    }
});