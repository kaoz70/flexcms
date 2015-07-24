/*global Fx, Request, Element, $, $$, $, ddsmoothmenu, console, system, window, document, clearTimeout, setTimeout, swfobject, console */

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

var search = {

    timer : null,
    submitButton : null,
    resultContainer : null,
    input : null,
    keyTimeout : 500,
    slideSpeed: 400,
    initialSearchString: '',
    language : '',
    url : '',

    init : function (main_el) {

        "use strict";

        search.input = main_el.find('input[type="text"]');

        search.input.click(search.clickListener);
        search.input.blur(search.blurListener);
        search.input.keyup(search.keyupListener);
        search.submitButton = main_el.find('input[type="submit"]');
        search.resultContainer = main_el.find('.searchResult');

        search.submitButton.click(search.hideResultBox);

        $('html').click(search.hideResultBox);

        main_el.click(function (event) {
            event.stopPropagation();
        });

        search.initialSearchString = search.input.val();

        search.language = system.lang;
        search.url = system.baseUrl;
    },

    clickListener : function (event) {

        "use strict";

        var element = $(event.target),
            value = element.val();

        if (value === search.initialSearchString) {
            element.val('');
        }

    },

    blurListener : function (event) {

        "use strict";

        var element = $(event.target),
            value = element.val();

        if (value === '') {
            element.val(search.initialSearchString);
            search.hideResultBox();
        }

    },

    keyupListener : function (event) {

        "use strict";

        clearTimeout(search.timer);
        search.timer = setTimeout(function () {
            search.start($(event.target).val());
        }, search.keyTimeout);
    },

    start : function (value) {

        "use strict";

        search.submitButton.addClass('loading');

        var jsonRequest = $.ajax({
            url: search.url + 'search',
            dataType: "json",
            data: {
                'query' : value,
                'language' : search.language
            }
        })
            .done(function (result) {
                search.submitButton.removeClass('loading');
                search.submitButton.addClass('cancel');
                search.generateResultBox(result);
            });

    },

    generateResultBox : function (result) {

        "use strict";

        search.resultContainer.empty();

        if (result.articulos.length > 0) {
            search.generateArticulos(result.articulos);
        }

        if (result.faq.length > 0) {
            search.generateFAQ(result.faq);
        }

        if (result.productos.length > 0) {
            search.generateProductos(result.productos);
        }

        if (result.publicaciones.length > 0) {
            search.generatePublicaciones(result.publicaciones);
        }

        if (result.descargas.length > 0) {
            search.generateDescargas(result.descargas);
        }

        if (result.articulos.length === 0 && result.faq.length === 0 && result.productos.length === 0 && result.publicaciones.length === 0) {
            search.generateNoResult(result.mensaje);
        }

        search.resultContainer.slideDown(search.slideSpeed);

    },

    hideResultBox : function () {

        "use strict";

        search.resultContainer.slideUp(search.slideSpeed);
        search.submitButton.removeClass('loading');
        search.submitButton.removeClass('cancel');

        search.input.val(search.initialSearchString);
    },

    /*
     * Articulos
     */
    generateArticulos : function (elements) {

        "use strict";

        var elemContainer = $('<div></div>')
            .addClass('resultSet articulos')
            .appendTo(search.resultContainer);

        $('<h4>Artículos</h4>')
            .appendTo(elemContainer);

        $.each(elements, function (index, elem) {
            $('<a href="' + search.url + search.language + '/' + elem.paginaNombreURL + '">' + elem.articuloTitulo + '</a>')
                .appendTo(search.resultContainer);
        });

    },

    /*
     * FAQ
     */
    generateFAQ : function (elements) {

        "use strict";

        var elemContainer = $('<div></div>')
            .addClass('resultSet faq')
            .appendTo(search.resultContainer);

        $('<h4>Preguntas Frecuentes</h4>')
            .appendTo(elemContainer);

        $.each(elements, function (index, elem) {

            $('<a href="' + search.url + search.language + '/' + elem.pagina + '#respuesta_' + elem.id + '">' + elem.pregunta + '</a>')
                .appendTo(elemContainer);
        });
    },

    /*
     * Productos
     */
    generateProductos : function (elements) {

        "use strict";

        var elemContainer = $('<div></div>')
            .addClass('resultSet productos')
            .appendTo(search.resultContainer);

        $.each(elements, function (index, elem) {
            var contItem,
                link = search.url + search.language + '/' + elem.pagina + '/' + elem.categoriaUrl + '/' + elem.productoUrl;

            contItem = $('<div><a href="' + link + '">' + elem.nombre + '</a></div>');

            if (elem.extension !== '') {

                contItem.prepend($('<a><img src="' + search.url + 'assets/public/images/catalog/prod_' + elem.id + '_search.' + elem.extension + '"/></a>').attr('href', link));

            }

            contItem.appendTo(elemContainer);

        });

    },
    /*
     * Publicaciones
     */
    generatePublicaciones : function (elements) {

        "use strict";

        var elemContainer = $('<div></div>')
            .addClass('resultSet publicaciones')
            .appendTo(search.resultContainer);

        $('<h4>Noticias / Comunicados</h4>')
            .appendTo(elemContainer);

        $.each(elements, function (index, pagina) {

            var contItem,
                image,
                contPagina;

            contPagina = $('<a class="paginaNombre" href="' + pagina.paginaNombreURL + '">' + pagina.paginaNombre + '</a>')
                .appendTo(elemContainer);

            $.each(pagina.publicaciones, function (idx, elem) {

                contItem = $('<a href="' + search.url + search.language + '/' + pagina.paginaNombreURL + '/' + elem.publicacionId + '">' + elem.publicacionNombre + '</a>')
                    .appendTo(contPagina);

                if (elem.publicacionImagen !== '') {

                    $('<img />')
                        .attr('src', search.url + 'assets/public/images/noticias/noticia_' + elem.publicacionId + '_search.' + elem.publicacionImagen)
                        .before(contItem);

                }

            });

        });
    },

    /*
     * Gallery
     */
    generateDescargas : function (elements) {

        "use strict";

        var elemContainer = $('<div></div>')
            .addClass('resultSet galeria')
            .appendTo(search.resultContainer);

        $('<h4>Galeria</h4>')
            .appendTo(elemContainer);

        $.each(elements, function (index, elem) {

            var contItem;

            contItem = $('<a href="' + search.url + search.language + '/' + elem.descargaUrl + '/' + elem.descargaId + '">' + elem.descargaNombre + '</a>')
                .appendTo(elemContainer);

            if (elem.publicacionImagen !== '') {

                $('<img />')
                    .attr('src', search.url + 'assets/public/images/noticias/noticia_' + elem.descargaId + '_search.' + elem.descargaArchivo)
                    .before(contItem);

            }

        });
    },

    /*
     * Ningun resultado
     */
    generateNoResult : function (message) {

        "use strict";

        $('<div>' + message + '</div>')
            .addClass('resultSet none')
            .appendTo(search.resultContainer);

    }
};

var modules = {

    container : null,
    module : null,
    request : null,
    fade_speed: 400,

    init: function () {
        "use strict";
        $("body").on("click", ".module .pagination a", modules.clickPaginationListener);
    },
    clickPaginationListener: function (event) {
        "use strict";

        event.preventDefault();

        modules.container = $(this).closest('.content');
        modules.module = $(this).closest('.module');
        modules.height = modules.module.height();

        modules.module.addClass('loading');

        modules.container.fadeOut(modules.fade_speed, function () {
            modules.request = $.ajax({
                url: event.target.href
            })
                .done(function (data) {

                    modules.container
                        .html(data)
                        .fadeIn(modules.fade_speed);

                    modules.height = modules.container.find('.content').height();

                    modules.module.removeClass('loading');

                    modules.container.children().unwrap();

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
                url: system.baseUrl + 'search/productFilters',
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

function initProductAccordionMenu() {
    "use strict";

    var activeCat = $(".mod_catalogoMenu .category.active")[0],
        currentSubmenu = $(activeCat).parent().closest('ul')[0],
        startIndex,
        lastSubmenu,
        index;

    $(".mod_catalogoMenu").find('li').each(function () {
        var children = $(this).children('.dropdown');

        if (children.length > 1) {
            //Check if there's a product list and a catalog list next to each other we wrap it
            children.wrapAll('<div></div>');
        } else if (children.length === 0) {
            //Check if its catalog 'leaf' and add class
            $(this).parent().addClass('no-children');
        }

    });

    //Remove the class from the parent list
    $('.menu_catalogo').removeClass('no-children');

    $(".mod_catalogoMenu ul:not(.productos):not(.no-children)").accordion({
        heightStyle: "content",
        header: '> li.has-dropdown > :first-child,> :not(li):even',
        collapsible: true,
        active: false
    });

    if ($(currentSubmenu).data("ui-accordion")) {

        startIndex = $(currentSubmenu)
            .children()
            .index($(activeCat));

        $(currentSubmenu).accordion("option", "active", startIndex);

    }

    while (currentSubmenu) {

        lastSubmenu = currentSubmenu;

        currentSubmenu = $(lastSubmenu)
            .parent()
            .parent()
            .closest('ul')[0];

        index = $(currentSubmenu)
            .children()
            .index($(lastSubmenu).parent().parent());

        $(currentSubmenu).accordion("option", "active", index);

    }
}

function validHandler(e) {
    "use strict";

    if ($(e.currentTarget).hasClass('form_contacto')) {

        var modal = $('<div>Enviando, por favor espere...</div>')
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

    var params = { allowScriptAccess: "always" };
    swfobject.embedSWF("http://www.youtube.com/v/" + firstVideo + "?version=3&amp;hl=es_ES&amp;rel=0&amp;enablejsapi=1",
        main, "576", "324", "8", null, null, params, null);

    items.click(function (ev) {
        videoClickListener(ev, main);
    });

}

function changeStateInputs(geonames, state) {
    "use strict";

    var selects = $('select.state');

    selects.empty();

    $.each(geonames, function () {
        var selected = '';
        if (state && state.geonameId === this.geonameId) {
            selected = 'selected="selected"';
        }
        selects.append('<option ' + selected + ' value="' + this.geonameId + '">' + this.toponymName + '</option>');
    });

    selects.trigger('change', true);
}

function changeCountryInputs(hierarchy) {
    "use strict";

    var region = hierarchy[0], //canton
        state = hierarchy[1], //provincia o estado
        country = hierarchy[2];

    $('[data-country-code="' + country.countryCode + '"]')
        .attr('selected', 'selected')
        .trigger('change', true);

    //GetStates / Provinces
    $.getJSON('http://www.geonames.org/childrenJSON?geonameId=' + country.countryId, {
        type: 'JSON'
    }, function (result) {
        changeStateInputs(result.geonames, state);
    });

}

$(document).ready(function () {
    "use strict";

    var pedido = $('.pedido'),
        scrollEl;

    modules.init();
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
    scrollEl = $('<div id="totop"><span class="flecha">&#x25B2;</span><span>SUBIR</span></div>').appendTo($(document.body));

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
    $('.popup a, a.popup').click(function (e) {

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

                //console.log(html.find('script'));
                //return;
                //TODO find out why safe_url() codeigniter scripts are affecting the append

                //Append the request HTML to the modal window
                modal
                    .empty()
                    .html(html)
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

    pedido.find('.add')
        .click(function (ev) {

            ev.preventDefault();

            var form = $(this).closest('form'),
                el = $(this),
                current_text = $(this).text();

            $(this)
                .addClass('loading')
                .text('adding...');

            $.ajax({
                type: "POST",
                dataType: "json",
                url: form.attr('action'),
                data: form.serialize()
            })
                .done(function (data) {

                    $('#message').html(data.message);
                    $('#mini_cart').html(data.mini_cart);

                    el.text('Success!')
                        .removeClass('loading');

                    window.setTimeout(function () {
                        el.text(current_text);
                    }, 2000);

                })
                .fail(function () {

                    el.text('Error!')
                        .removeClass('loading');

                    window.setTimeout(function () {
                        el.text(current_text);
                    }, 2000);

                });

        });

    pedido.find('.delete')
        .click(function (e) {

            var el = $(this),
                form = $(this).closest('form'),
                current_text = $(this).text();

            e.preventDefault();

            $(this)
                .addClass('loading')
                .text('deleting...');

            $.ajax({
                type: "POST",
                dataType: "json",
                url: $(this).attr('href'),
                data: 'csrf_test=' + $.cookie('csrf_cookie')
            })
                .done(function (data) {
                    $('#message').html(data.message);
                    $('#mini_cart').html(data.mini_cart);
                    el.closest('tr').remove();
                })
                .fail(function () {

                    el.text('Error!')
                        .removeClass('loading');

                    window.setTimeout(function () {
                        el.text(current_text);
                    }, 2000);

                });

        });

    $('#copy_billing_details').on('change', function () {
        $('input[name^="checkout[billing]"]').each(function () {
            // Target textboxes only, no hidden fields
            if ($(this).attr('type') === 'text') {
                var name = $(this).attr('name').replace('billing', 'shipping'),
                    value = ($('#copy_billing_details').is(':checked')) ? $(this).val() : '';

                $('input[name="' + name + '"]').val(value);
            }
        });
    });

    //Get the country
    if ($('.country').length > 0) {

        $('.country').change(function (e, no_change) {

            if (no_change !== true) {
                var country_id = $(this).find(':selected').val();
                $.getJSON("http://www.geonames.org/childrenJSON", {
                    geonameId: country_id,
                    type: 'JSON'
                }, function (result) {
                    changeStateInputs(result.geonames, false);
                });
            }

        });

        //Get country list
        $.getJSON(system.baseUrl + 'ajax/countries', {
            type: 'JSON'
        }, function (geonames) {

            //Fill all country select boxes with the new info
            var selects = $('select.country');
            selects.empty();
            $.each(geonames, function () {
                selects.append('<option data-country-code="' + this.countryCode + '" value="' + this.geonameId + '">' + this.countryName + '</option>');
            });

            //Get the users location
            $.getJSON(system.baseUrl + 'ajax/location', {
                type: 'JSON'
            }, function (result) {
                changeCountryInputs(result);
            });

        });


    }

    //Update
    /*pedido.find('input[type="text"]')
        .keyup(function () {

            var form = $(this).closest('form'),
                quantity = parseInt($(this).attr('value'), 10), //force integer
                el = $(this);

            if ($(this).attr('value') !== '') {

                //If not a number reset to 1
                if (isNaN(quantity) && $(this).attr('value') !== '') {
                    quantity = 1;
                }

                $(this)
                    .addClass('loading')
                    .attr('value', quantity);

                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: system.baseUrl + 'ajax/cart/update',
                    data: form.serialize()
                })
                    .done(function (data) {

                        console.log(data);

                        var total = 0;

                        el.removeClass('loading');

                        $.each($('tr.pedido'), function (index, item) {

                            var cant = parseInt($(item).find('input.cantidad').attr('value'), 10),
                                precio = parseInt($(item).find('[data-type="precio"]').text(), 10),
                                subtotal_el = $(item).find('[data-type="subtotal"]'),
                                subtotal = cant * precio;

                            if (isNaN(subtotal)) {
                                subtotal = 0;
                            }

                            subtotal_el.text(subtotal.toFixed(2));
                            total += subtotal;

                        });

                        $('#total')
                            .find('[data-total]')
                            .text(total.toFixed(2));

                    });
            }
        });*/

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
