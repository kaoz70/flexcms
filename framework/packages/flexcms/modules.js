/*global $ */

/**
 * Created by Miguel on 25/11/2014.
 */

var modules = {

    container : null,
    module : null,
    request : null,
    fade_speed: 400,

    init: function () {
        "use strict";
        $(".module").on("click", ".pagination a", modules.clickPaginationListener);
    },
    clickPaginationListener: function (event) {
        "use strict";

        event.preventDefault()

        //Don't call the ajax on the current item
        if($(this).parent().hasClass('current')) return;

        modules.container = $(this).closest('.content');
        modules.module = $(this).closest('.module');
        modules.height = modules.module.outerHeight();
        modules.delta_height = modules.height - modules.container.outerHeight();

        modules.module.css('height', modules.height);
        // console.log(modules.height);

        modules.module.addClass('loading');

        modules.container.fadeOut(modules.fade_speed, function () {
            modules.request = $.ajax({
                url: event.target.href
            })
                .done(function (data) {

                    modules.container
                        .html(data)
                        .fadeIn(modules.fade_speed);

                    var images = modules.container.find('img');

                    if(images.length > 0) {
                        imagesLoaded(images, function() {
                            modules.height = modules.container.find('.content').outerHeight() + modules.delta_height;
                            modules.module.css('height', modules.height);
                            modules.module.removeClass('loading');
                            modules.container.children().unwrap();
                        });
                    } else {
                        modules.height = modules.container.find('.content').outerHeight() + modules.delta_height;
                        modules.module.css('height', modules.height);
                        modules.module.removeClass('loading');
                        modules.container.children().unwrap();
                    }

                    /*modules.height = modules.container.find('.content').outerHeight() + modules.delta_height;
                    modules.module.css('height', modules.height);
                    modules.module.removeClass('loading');
                    modules.container.children().unwrap();*/

                })
                .fail(function () {
                    modules.container
                        .text('Lo sentimos, hubo un problema con la petición')
                        .fadeIn(modules.fade_speed);
                    modules.module.removeClass('loading');
                });
        });

    }

};

$(document).ready(function () {
    "use strict";
    modules.init();
});