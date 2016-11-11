var yimaPage = function() {
    var initilize = function() {
        if (!Modernizr.mq('(max-width: 1600px)')) {
            $(".sidebar.form").load(yima.getAssetPath("Partials/Blank.html"));
            yima.toggleFormSidebar('Blank');
        }
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