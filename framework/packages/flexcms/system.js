/*global search, modules, Fx, Request, Element, $, $$, $, ddsmoothmenu, console, system, window, document, clearTimeout, setTimeout, swfobject, console */

// Avoid `console` errors in browsers that lack a console.
(function () {
    "use strict";
    var method,
        noop = function () {},
        methods = [
            'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
            'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
            'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
            'timeStamp', 'trace', 'warn'
        ],
        length = methods.length,
        console = (window.console = window.console || {});

    while (length--) {
        method = methods[length];

        // Only stub undefined methods.
        if (!console[method]) {
            console[method] = noop;
        }
    }
}());

var catalogFilters = {

    pathName: '',
    cleanPathName : '',
    filterArr: [],

    init: function () {

        "use strict";

        catalogFilters.pathName = document.location.pathname;
        var pathSegments = catalogFilters.pathName.split('/');

        $('#module_filtros input').click(catalogFilters.clickFilterHandler);

        $.each($('#module_filtros input'), function (index, item) {
            if (pathSegments.pop() === $(item).attr('value')) {
                $(item).prop('checked', true);
            }
        });

    },

    clickFilterHandler: function () {

        "use strict";

        var value = $(this).attr('value'),
            resultObj = {},
            catArr = [],
            filterArr = [];

        $.each($('#module_filtros ul.campos'), function (index, item) {

            var checkObj = {},
                checkedArr = [];

            checkObj.id = parseInt($(item).attr('data-id'), 10);

            console.log($(item).find(':checked'));

            $.each($(item).find(':checked'), function (index, el) {
                checkedArr.push($(el).attr('value'));
            });

            checkObj.filters = checkedArr;

            filterArr.push(checkObj);

        });

        $.each($('#module_filtros ul#filtro_categorias'), function (index, item) {
            $.each($(item).find(':checked'), function (index, el) {
                catArr.push($(el).attr('value'));
            });
        });

        resultObj.campos = filterArr;
        resultObj.categorias = catArr;

        catalogFilters.request(resultObj);

    },

    request: function (filters) {
        "use strict";
        console.log(filters);

        var targetContainer = $('.main_content');

        targetContainer.addClass('loading');
        targetContainer.fadeOut(function () {
            $.ajax({
                url: system.base_url + 'search/productFilters',
                context: targetContainer,
                data: {
                    'language': system.lang,
                    'filters': JSON.stringify(filters),
                    'destacados' : $('.mod_filtros form').attr('data-destacados')
                }
            })
                .done(function (data) {
                    targetContainer.removeClass('loading');
                    targetContainer.html(data);
                    targetContainer.fadeIn();
                })
                .fail(function () {
                    targetContainer.removeClass('loading');
                    targetContainer.fadeIn();
                });
        });

    }
};

function initProductAccordionMenu(id) {
    "use strict";

    $('.category > li > a').click(function (e) {
        e.preventDefault();
    });

    $(id).accordion({
        container : false,
        head: "a",
        initShow : ".active"
    });

}

function validHandler(e) {
    "use strict";

    //Force check and see if its a "valid" event, because this was being executed on "submit" also (2 requests)
    if (e.type === 'valid') {

        if ($(e.currentTarget).hasClass('form_contacto')) {

            var modal = $('<div>Enviando, por favor espere...</div>')
                .attr('data-reveal', '')
                .addClass('small reveal-modal').appendTo('body')
                .foundation('reveal', 'open');

            $.post($(e.currentTarget).attr('action'), $(e.currentTarget).serialize())
                .done(function (data) {
                    var theData = data;

                    modal
                        .empty()
                        .html($(theData))
                        .append('<a class="close-reveal-modal">&#215;</a>');
                })
                .fail(function (data) {
                    modal
                        .empty()
                        .html('<h4>' + data.statusText + '</h4>')
                        .append('<a class="close-reveal-modal">&#215;</a>');
                });
        } else {
            $(e.currentTarget).submit();
        }

    }

}

function resetHandler(e) {
    "use strict";
    $(e.currentTarget).find('div.error').removeClass('error');
}

function videoClickListener(e, main) {
    "use strict";
    e.preventDefault();

    var id = $(e.currentTarget).attr('data-videoId'),
        ytplayer = document.getElementById(main);

    if (ytplayer) {
        ytplayer.loadVideoById(id);
    }

}

function initVideoCarrousel(firstVideo, items, main) {
    "use strict";

    var params = {
        allowScriptAccess: "always",
        wmode:"transparent"
    };

    swfobject.embedSWF("http://www.youtube.com/v/" + firstVideo + "?version=3&amp;hl=es_ES&amp;rel=0&amp;enablejsapi=1",
        main, "576", "324", "8", null, null, params, null);

    items.click(function (ev) {
        videoClickListener(ev, main);
    });

}

$(document).ready(function () {
    "use strict";

    var pedido = $('.pedido'),
        scrollEl;

    catalogFilters.init();

    $(document).foundation();

    $('.mapa_ubicaciones li').click(function () {

        var id = $(this).attr('data-id'),
            slides_parent = $(this)
                .parent()
                .parent()
                .find('.mapa_slides');

        slides_parent
            .children()
            .fadeOut();

        slides_parent
            .find('[data-id="' + id + '"]')
            .fadeIn();

        $(this)
            .parent()
            .find('li')
            .removeClass('selected');

        $(this).addClass('selected');

    });

    $('.thumbs.imagenes').magnificPopup({
        delegate: 'a',
        type: 'image',
        preload: [3, 3],
        // Delay in milliseconds before popup is removed
        removalDelay: 300,
        // Class that is added to popup wrapper and background
        // make it unique to apply your CSS animations just to this exact popup
        mainClass: 'mfp-fade',
        zoom: {
            enabled: true, // By default it's false, so don't forget to enable it

            duration: 300, // duration of the effect, in milliseconds
            easing: 'ease-in-out', // CSS transition easing function

            // The "opener" function should return the element from which popup will be zoomed in
            // and to which popup will be scaled down
            // By defailt it looks for an image tag:
            opener: function (openerElement) {
                // openerElement is the element on which popup was initialized, in this case its <a> tag
                // you don't need to add "opener" option if this code matches your needs, it's defailt one.
                return openerElement.is('img') ? openerElement : openerElement.find('img');
            }
        },
        gallery: {
            enabled: true
        }
    });

    $('.videos ul.thumbs').bxSlider({
        minSlides: 3,
        maxSlides: 4,
        slideWidth: 120,
        slideMargin: 10
    });

    /*
    Create To-Top button
     */
    scrollEl = $('<div id="totop"><span class="flecha">&#x25B2;</span></div>').appendTo($(document.body));

    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            scrollEl.fadeIn();
        } else {
            scrollEl.fadeOut();
        }
    });

    scrollEl.click(function () {
        $("html, body").animate({scrollTop: 0}, 600, 'easeInOutCubic');
        return false;
    });

    $('[data-abide]')
        .on('submit', function (e) {
            if ($(e.currentTarget).hasClass('form_contacto')) {
                e.preventDefault();
            }
        })
        .on('valid', validHandler)
        .on('reset', resetHandler);

    /**
     * Load pop-up pages with ajax
     */
    $(document.body).on('click', '.popup a, a.popup', function (e) {

        e.preventDefault();

        var csrf = $.cookie('csrf_cookie'),
            modal;

        //Create the modal window
        modal = $('<div>Cargando, por favor espere...</div>')
            .attr('data-reveal', '')
            .addClass('small reveal-modal').appendTo('body')
            .foundation('reveal', 'open');

        $.post($(this).attr('href'), {csrf_test: csrf})
            .done(function (data) {

                var form,
                    html = $(data);

                //Append the request HTML to the modal window
                modal
                    .empty()
                    .html($('<div>').html(html))
                    .append('<a class="close-reveal-modal">&#215;</a>');

                //$(document).foundation('reveal', 'close');

                //Initialize the form validation if there is a form element
                form = modal.find('form[data-abide]');
                if (form) {

                    $(document).foundation('abide', 'events');

                    form
                        .on('submit', function (e) {
                            e.preventDefault();
                        })
                        .on('valid', validHandler)
                        .on('reset', resetHandler);
                }

            });
    });

    //Show the popup banner if it wasn't seen and if it exists
    if (system.hasOwnProperty('popup_banner') && !$.cookie('popup_banner_' + system.popup_banner.id)) {
        //Create the modal window
        var popup = $(system.popup_banner.html)
            .addClass('small reveal-modal')
            .appendTo('body')
            .foundation('reveal', 'open');
        popup.append('<a class="close-reveal-modal">&#215;</a>');
        //Store the cookie so that we don't show the popup again
        $.cookie('popup_banner_' + system.popup_banner.id, true);
    }

});
