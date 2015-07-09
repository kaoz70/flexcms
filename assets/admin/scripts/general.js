/*global window,$,$$,system,Request,Scrollable,Cookie,CountDown,FileReader,document,alert,Element,FormValidator,Fx,Locale,console,Clientcide,StickyWin,Picker,Browser,Uploader,Tips,Tree,ImageManipulation,Sortables,Asset,Drag, plusplus: true, scripturl:false */
/**
 * Created by Miguel Suarez
 * Dejabu Agencia Multimedia 2012
 */

Clientcide.setAssetLocation(system.base_url + "assets/admin/clientcide");
Locale.use("es-ES");

var levelNum = 1,
    fadeFx,
    sortables,
    wysywygEditor = [],
    countdown,
    alertaLogin,
    g_time,
    g_uniqueNameCount,
    csrf_cookie,
    animation_time = 300;

function checkboxEnablerLanguage(clase) {
    "use strict";
    $$(clase).addEvent('click', function (event) {
        $$(clase).set('checked',  event.target.get('checked'));
    });
}

/**
 * Creates a custom alert window
 * @param title (string)
 * @param content (string)
 */
function createAlert(title, content) {
    "use strict";
    var win = new StickyWin({
        content : StickyWin.ui(title, content, {
            width : '400px',
            buttons : [{
                text : 'cerrar'
            }]
        })
    });
}

/**
 * Handles the errors from a request
 * @param xhr (XMLHttpRequest)
 * @param link (string)
 */
function failureRequestHandler(xhr, link) {
    "use strict";
    var error = '',
        title = 'Error';

    switch (xhr.status) {
        case 0:
            error = 'Se perdió la conexión al servidor, por favor inténtelo nuevamente en unos minutos.';
            break;
        case 404:
            error = 'Este recurso no existe: <p>' + link + '</p>';
            break;
        default:
            title = xhr.statusText;
            error = xhr.response;

            if (error === '') {
                title = 'Error';
                error = xhr.statusText;
            }
    }

    if (error !== '') {
        createAlert(title, error);
    }

}

/**
 * General search functionality for the backend
 * @type {{timer: null, submitButton: null, resultContainer: null, input: null, keyTimeout: number, initialSearchString: string, language: string, url: string, originalList: null, init: Function, clickListener: Function, blurListener: Function, keyupListener: Function, start: Function, generateResultBox: Function, hideResultBox: Function, generateResult: Function, generateNoResult: Function}}
 */
var search = {

    timer : null,
    submitButton : null,
    resultContainer : null,
    input : null,
    keyTimeout : 500,
    initialSearchString: '',
    language : '',
    url : '',
    originalList: null,

    /**
     * Initializes the search functionality
     * @param url (string) url of the query/result file
     * @param lang (string) language for the search content
     */
    init : function (url, lang) {
        "use strict";
        search.input = $$('.buscar input[name="searchString"]')[0];
        search.submitButton = $$('.buscar .searchButton')[0];

        search.input.addEvent('click', search.clickListener);
        search.input.addEvent('blur', search.blurListener);
        search.input.addEvent('keyup', search.keyupListener);
        search.submitButton.addEvent('click', search.hideResultBox);

        search.resultContainer = $$('.searchResults')[0];
        search.initialSearchString = $$('.buscar input[name="searchString"]')[0].get('value');
        search.originalList = search.resultContainer.getChildren();

        search.language = lang;
        search.url = url;

        search.input.focus();
        search.input.set('value', '');

    },

    /**
     * Handles the click event on the input
     * @param event
     */
    clickListener : function (event) {
        "use strict";
        var value = event.target.get('value');

        if (value === search.initialSearchString) {
            event.target.set('value', '');
        }

    },

    /**
     * Handles the blur event of the input
     * @param event
     */
    blurListener : function (event) {
        "use strict";
        var value = event.target.get('value');

        if (value === '') {
            event.target.set('value', search.initialSearchString);
            search.hideResultBox();
        }

    },

    /**
     * Handles the keyup event of the input
     * @param event
     */
    keyupListener : function (event) {
        "use strict";
        clearTimeout(search.timer);
        search.timer = setTimeout(function () {
            search.start(event.target.get('value'), event.target.get('data-page-id'));
        }, search.keyTimeout);
    },

    /**
     * Starts the search, sends the query to the server
     * @param value (string) query string
     */
    start : function (value, pageId) {
        "use strict";
        var jsonRequest = new Request.JSON({
            url : search.url + '/' + pageId,
            onRequest : function () {
                search.submitButton.addClass('loading');
                search.submitButton.removeClass('cancel');
            },
            onSuccess : function (result) {
                search.generateResultBox(result);
                search.submitButton.removeClass('loading');
                search.submitButton.addClass('cancel');
            },
            onFailure : function (xhr) {
                search.submitButton.removeClass('loading');
                search.submitButton.removeClass('cancel');
                failureRequestHandler(xhr, search.url);
            }
        }).get({
                'query' : value,
                'language' : search.language
            });

    },

    /**
     * Shows the result box with the contents
     * @param result
     */
    generateResultBox : function (result) {
        "use strict";

        if (result.length > 0) {
            search.generateResult(result);
        } else {
            search.generateNoResult();
        }

        search.resultContainer.fade(1);

    },

    /**
     * Hides the resutl box
     */
    hideResultBox : function () {
        "use strict";

        search.resultContainer.getElements('li li').each(function (item) {
            item.reveal();
        });

        if(search.resultContainer.getElements('li li').length === 0) {
            search.resultContainer.getElements('li').each(function (item) {
                item.reveal();
            });
        }

        search.submitButton.removeClass('loading');
        search.submitButton.removeClass('cancel');

        search.input.set('value', search.initialSearchString);
    },

    /**
     * Generates the result with each item, then it puts it into the result box
     * @param elements
     */
    generateResult : function (elements) {
        "use strict";

        var list = search.resultContainer.getElements('li.pagina li'),
            show = [],
            hide;

        if (list.length === 0 && search.resultContainer.getElements('li.pagina').length === 0) {
            list = search.resultContainer.getElements('li');
        }

        hide = list;

        elements.each(function (elem) {
            list.each(function (li, inx) {
                if (li.id === elem.id) {
                    show.push(li);
                    hide.splice(inx, 1);
                }
            });
        });

        show.each(function (item) {
            item.reveal();
        });

        list.each(function (item) {
            item.dissolve();
        });


    },

    /**
     * Handles the empty result (when the search didn't find anything)
     */
    generateNoResult : function () {
        "use strict";
        search.originalList.each(function (list) {
            list.getElements('a.nombre').each(function (a) {
                a.getParent('.listado').dissolve();
            });
        });

    }
};


function copyVideoIdListener(event, elem) {
    "use strict";
    var target = elem.getParent('form').getElement('#upload-fileName');
    target.value = elem.get('value');
}

/**
 * Initializes the DatePicker class
 */
function initDatePicker() {
    "use strict";
    Locale.use('es-Es');

    var picker = new Picker.Date($$('.fecha'), {
        timePicker : true,
        format : '%Y-%m-%d %H:%M:%S',
        positionOffset : {
            x : -5,
            y : 0
        },
        pickerClass : 'datepicker_dashboard',
        useFadeInOut : !Browser.ie
    });
}

/**
 * Initializes the DatePicker class
 */
function initDayPicker() {
    "use strict";
    Locale.use('es-Es');

    var picker = new Picker.Date($$('.fecha'), {
        timePicker : false,
        format : '%Y-%m-%d',
        positionOffset : {
            x : -5,
            y : 0
        },
        pickerClass : 'datepicker_dashboard',
        useFadeInOut : !Browser.ie
    });
}

/**
 * Initializes the TimePicker class
 */
function initTimePicker() {
    "use strict";
    Locale.use('es-Es');

    var picker = new Picker.Date($$('.fecha'), {
        timePicker : true,
        pickOnly: 'time',
        format : '%H:%M:%S',
        positionOffset : {
            x : -5,
            y : 0
        },
        pickerClass : 'datepicker_dashboard',
        useFadeInOut : !Browser.ie
    });
}

/**
 * Checks if there is an error processing the JSON request
 * @param response (string)
 * @returns {boolean} if successful it return the processed JSON in an object, if it fails it returns false
 */
function responseErrorHandler(response) {
    "use strict";
    var responseObj = false;

    try {
        responseObj = JSON.decode(response);
    } catch (err) {
        createAlert('Error', response);
        responseObj = false;
    }

    return responseObj;
}

/**
 * Sends the image data with its coordinates to the server for image processing
 * @param url {string}
 * @param imagedata {string} coordinates created by the ImageManipulation Class
 * @param element {element}
 */
function requestImageManipulation(url, imagedata, element, result) {
    "use strict";
    var last_img = element.getElements('img')[element.getElements('img').length - 1],
        parent = last_img.getParent(),
        loader = new Element('div', {'class' : 'loader'}),
        request,
        dimensions = last_img.getSize(),
        loader_fx;

    loader_fx = new Fx.Tween(loader);

    request = new Request({
        url: url,
        noCache: true,
        data: {
            imagedata: JSON.encode(imagedata),
            filedata: result,
            csrf_test: csrf_cookie
        },
        onRequest: function () {
            loader.inject(parent);

            parent.setStyles({
                width: dimensions.x,
                height: dimensions.y
            });

            loader_fx.start('opacity', 1).chain(function () {
                last_img.destroy();
            });

        },
        onSuccess: function (responseText) {

            var response = responseErrorHandler(responseText),
                image;

            loader.fade(0);

            if (!response) {
                return;
            }

            if (response.message === 'success') {

                image =  new Element('img', {
                    src: system.base_url + response.path
                });

                image.addEventListener('load', function () {
                    image.inject(parent);
                    parent.setStyles({
                        width: '',
                        height: ''
                    });

                });

            } else {
                createAlert('Error', response.message);
            }

        },
        onFailure: function (xhr) {
            failureRequestHandler(xhr, url);
        }
    });

    request.send();
}

/**
 * Creates the ImageManipulation window
 * @param imageId
 * @param w
 * @param cropWidth
 * @param cropHeight
 * @param file
 * @param imageEl
 * @param imageManipulationMethod {string} URL to the image processing controller
 * @param edit {boolean} Are we going to edit an image or upload a new one to the server
 */
function createManipWindow(imageId, w, cropWidth, cropHeight, file, result, imageEl, imageManipulationMethod, edit) {

    "use strict";

    var imageMan,
        image,
        imageManWindow,
        coordEl = w.getParent('.contenido_col').getElement('.coord'),
        coords,
        name,
        margin = 40,
        reader = new FileReader();

    imageManWindow = new StickyWin.Modal({
        'id': 'imageManipWindow',
        zIndex: 500,
        maskOptions: {
            style: {
                'background-color': '#000000',
                'z-index': '10'
            }
        },
        content : StickyWin.ui('Modificar Im&aacute;gen', '', {
            width : $(document).getWidth() - (margin * 2),
            buttons : [{
                text : 'cancelar',
                onClick : function () {

                    if ($(imageId).get('value') === '') {
                        w.getElement('.list').empty();
                    }

                    if (w.getElements('li').length > 1) {
                        w.getElements('li')[1].destroy();
                        w.getElements('li')[0].reveal();
                    }

                }
            }, {
                text : 'ok',
                onClick : function () {

                    imageMan.applyButton.fireEvent('click');

                    var imagedata,
                        coord;

                    if (edit) {
                        file = {};
                        file.name = $(imageId).get('value');
                    }

                    imagedata = {
                        top : imageMan.top,
                        left : imageMan.left,
                        width : imageMan.width,
                        height : imageMan.height,
                        scale : imageMan.scale,
                        cropWidth : cropWidth,
                        cropHeight : cropHeight,
                        crop : true,
                        edit : edit,
                        name : file.name
                    };

                    if (!edit) {
                        name = file.name.split('.').pop();
                        $(imageId).set('value', name);

                    }

                    coord = {
                        top : imageMan.top,
                        left : imageMan.left,
                        width : imageMan.width,
                        height : imageMan.height,
                        scale : imageMan.scale
                    };

                    coordEl.set('value', JSON.encode(coord));

                    requestImageManipulation(imageManipulationMethod, imagedata, w, result);


                }
            }]
        })
    });

    imageManWindow.element.setStyle('top', margin);

    if (edit) {

        coords = JSON.decode(decodeURIComponent(coordEl.get('value')));
        imageEl.inject(imageManWindow.element.getElement('.body'));

        //Coords where not saved for some reason
        if (!coords) {

            createAlert('Error', 'No se pudo obtener las coordenadas anteriores de la imagen. Han sido reemplazadas por unas de defecto.');

            var size = imageEl.getSize();

            coords = {
                top: 0,
                left: 0,
                width: size.x,
                height: size.y,
                scale: 1
            }

        }

        imageMan = new ImageManipulation(imageEl, {
            cropWidth : cropWidth,
            cropHeight : cropHeight,
            wrapperWidth : $(document).getWidth() - (margin * 2) - 33,
            wrapperHeight : $(document).getHeight() - (margin + 150),
            top : coords.top,
            left : coords.left,
            width : coords.width,
            height : coords.height,
            scale : coords.scale
        });

    } else {

        reader.onload = function (e) {

            image =  new Element('img', {
                src: e.target.result
            });

            image.inject(imageManWindow.element.getElement('.body'));

            imageMan = new ImageManipulation(image, {
                cropWidth : cropWidth,
                cropHeight : cropHeight,
                wrapperWidth : $(document).getWidth() - (margin * 2) - 33,
                wrapperHeight : $(document).getHeight() - (margin + 150)
            });

        };

        reader.readAsDataURL(file);
    }


}

/**
 * Initializes the editor
 * @param textarea {string} Id of the textarea that we are going to add the Editor to
 */
function initEditor(textarea) {
    "use strict";

    setTimeout(function () {

        wysywygEditor.push(
            $(textarea).mooEditable({
                actions : 'bold italic underline strikethrough | forecolor | formatBlock justifyleft justifyright justifycenter justifyfull | insertunorderedlist insertorderedlist indent outdent | undo redo | createlink unlink | urlimage uploadimage | toggleview',
                extraCSS : 'body {background: #fff;}',
                externalCSS: system.base_url + '/packages/foundation/css/foundation.css',
            })
        );

    }, animation_time);

}

/**
 * Sends the position data to the server
 * @param link {string} URL to send the data to
 * @param data {object} Contains the ordered object of positions and ids
 */
function sortearElementos(link, data) {
    "use strict";
    var request = new Request({
        url : link,
        data: {
            posiciones: data.clean(),
            csrf_test: csrf_cookie
        },
        onFailure : function (xhr) {
            failureRequestHandler(xhr, link);
        }
    });

    request.send();
}

/**
 * Initializes the sortables
 * @param listado {element} Element that the Sortable Class is going to be attached to
 */
function initSortables(listado) {
    "use strict";
    sortables = new Sortables(listado, {
        revert : true,
        handle : '.mover',
        clone : true,
        opacity : 0,
        dragOptions : {
            container : listado
        },
        onStart : function (elem, clone) {
            clone.addClass('dragging');
        },
        onComplete : function () {

            var posiciones = [];

            this.list.getChildren('*:not(.dragging):not(.removed)').each(function (item) {
                posiciones.push(item.getProperty('id'));
            });

            posiciones = JSON.encode(posiciones.clean());

            sortearElementos(this.list.get('data-sort').toString(), posiciones);

        }
    });

    listado.store('sortable_instance', sortables);

}

function reload_page_column() {
    "use strict";

    var link = system.base_url + 'admin/page',
        request;

    request = new Request({
        url : link,
        data: {
            csrf_test: csrf_cookie
        },
        onSuccess: function (responseText) {

            $('pages').getElement('.contenido').destroy();

            var new_content = new Element('div', {
                class: 'contenido',
                html: responseText
            });

            new_content.inject($('pages'));

        },
        onFailure : function (xhr) {
            failureRequestHandler(xhr, link);
        }
    });

    //Set a delay here because of the Nested Sets delay in the DB
    setTimeout(function () {
        request.send();
    }, 500);

}

/**
 * Initializes the Tree Class
 * @param elem {element} Element that the Tree Class is going to be attached to
 */
function initTree(elem) {
    "use strict";
    var tree = new Tree(elem, {
        cloneOpacity: 1,
        indicatorOffset: 20,
        checkDrag : function (element) {
            return !element.hasClass('nodrag');
        },

        checkDrop : function (element) {
            //element.addClass('drop');
            return !element.hasClass('nodrop');
        },

        onChange : function () {

            var stree = tree.serialize(),
                posiciones = JSON.encode(stree),
                size = $(elem).getSize(),
                size_count,
                sizeFx;

            sortearElementos($(elem).get('data-sort').toString(), posiciones);

            sizeFx = new Fx.Morph($(elem).getParent('.columnas'), {
                transition : Fx.Transitions.Cubic.easeOut
            });

            sizeFx.start({
                'width' : size.x,
                'min-width' : size.x
            });

            function getSize(arr, depth) {

                arr.each(function (item) {
                    if (item.child) {
                        depth++;
                        depth = getSize(item.child, depth);
                    }
                });

                return depth;

            }

            size_count = getSize(stree, 1);

            if ($(elem).getProperty('id') === 'pagina_tree') {
                reload_page_column();
            }

        }

    });


}

/**
 * Handles the click event on the main menu
 */
function clickBotonMenu() {
    "use strict";
    $('pages').getElements('.enabled').removeClass('enabled');
    $$('#menu li a').removeClass('enabled');

    $$('#pages, #contenido').addClass('hide');

}

/**
 * Detect a parent element's width and set the input elements class to a no-float
 * this makes the child elements more readable in narrow widths
 * TODO: this would be better done with CSS element queries or something similar
 *
 * @param parent
 */
function detectInputWidth(parent) {
    parent.getElements('.input').each(function(el){
        if(el.getWidth() < 200) {
            el.addClass('no-float');
        } else {
            el.removeClass('no-float');
        }
    });
}

/**
 * Resizes the column
 * @param columna
 * @param columnaContenido
 * @param link
 * @param size
 */
function resizeColumnToContent(columna, columnaContenido, link, size) {

    "use strict";

    var margen = 0;

    $$('.columnas').each(function (elem) {
        margen = elem.getStyle('margin-right').toInt();
    });

    columna.set('styles', {
        'width' : size.x + margen,
        'min-width' : size.x + margen
    });

    setTimeout(function () {

        var scroll;

        columnaContenido.set('styles', {
            'opacity' : 1
        });

        link.removeClass('loading');

        new Fx.Scroll('contenido', {
            duration: 200,
            transition : Fx.Transitions.Cubic.easeOut
        }).toRight();

        scroll = new Scrollable(columna.getElement('.contenido_col'), {
            //autoHide: false
        });

        DynamicColorPicker.auto(".color-field", {
            pickerPath: system.base_url + 'assets/admin/scripts/colorpicker',
            autoLoadPath: system.base_url + 'assets/admin/scripts/colorpicker'
        });

        columna.store('scrollable', scroll);

        detectInputWidth(columna);

    }, animation_time);

    levelNum += 1;
}

function create_column(link, nivel) {
    "use strict";

    var columna,
        columnaContenido,
        request;

    //Creamos las columnas contenedoras, este tiene el loader
    columna = new Element('td#contenido_nivel' + nivel, {
        'class' : 'columnas shadow loading'
    });

    //console.log('asd');

    //Creamos el elemento donde se va a poner el resultado del AJAX
    columnaContenido = new Element('div#contenido' + nivel, {
        'class' : 'contenido'
    });

    request = new Request.HTML({
        url : link.getProperty('href'),
        update : columnaContenido,
        noCache : true,
        data: {
            csrf_test: csrf_cookie
        },
        onRequest : function () {

            columna.inject($('columnas'));
            columnaContenido.inject(columna);

            //Set a delay, because the next lines where not being applied correctly after the elements where added
            setTimeout(function () {

                columna.set('styles', {
                    /*'-webkit-transform': 'rotateY(0deg)',
                     '-moz-transform': 'rotateY(0deg)',
                     '-o-transform': 'rotateY(0deg)',
                     'transform': 'rotateY(0deg)',*/
                    /*'-webkit-transform': 'translateX(0px) scale(1, 1)',
                     '-moz-transform': 'translateX(0px) scale(1, 1)',
                     '-o-transform': 'translateX(0px) scale(1, 1)',
                     'transform': 'translateX(0px) scale(1, 1)',*/
                    /*'-webkit-transform': 'translateZ(0px)',
                     '-moz-transform': 'translateZ(0px)',
                     '-o-transform': 'translateZ(0px)',
                     'transform': 'translateZ(0px)',*/
                    '-webkit-transform': 'scale(1)',
                    '-moz-transform': 'scale(1)',
                    '-o-transform': 'scale(1)',
                    'transform': 'scale(1)',
                    'opacity': 1
                });

                new Fx.Scroll('contenido', {
                    duration: 200,
                    wait: false
                }).toRight();

            }, 50);

        },
        onSuccess : function (responseTree, responseElements, responseHTML, responseJavaScript) {

            var size;

            //console.log(link.getProperty('href'));

            //if (link.getProperty('href') !== 'http://localhost/web-flexcms-1.5.x/admin/articulos/modificarArticulo/22') {
            columna.removeClass('loading');

            if (columnaContenido.getChildren('.contenido_col').length > 0) {
                size = columnaContenido.getElement('.contenido_col').getSize();
            } else {

                if (!responseJavaScript) { //TODO this is not the best option
                    createAlert('Error', responseHTML);
                }

                columna.destroy();

                return;
            }

            resizeColumnToContent(columna, columnaContenido, link, size);
            //}



        },
        onFailure : function (xhr) {
            columna.removeClass('loading');
            failureRequestHandler(xhr, link.getProperty('href'));
            link.removeClass('loading');
            columna.destroy();
        }
    }).send();
}

function fade_columns(els, link, nivel) {

    "use strict";

    if (!els.length) {
        return;
    }

    var lastColumn,
        lastElContent;

    lastColumn = els.getLast();

    lastElContent = lastColumn.getElement('.contenido_col');

    if (lastElContent) {
        lastElContent.set('styles', {
            '-webkit-transition-timing-function' : 'ease-in',
            'transition-timing-function' : 'ease-in'
        });
    }

    lastColumn.set('styles', {
        '-webkit-transition-timing-function': 'ease-in',
        'transition-timing-function': 'ease-in',
        /*'-webkit-transform': 'rotateY(-13deg)',
         '-moz-transform': 'rotateY(-13deg)',
         '-o-transform': 'rotateY(-13deg)',
         'transform': 'rotateY(-13deg)',*/
        /*'-webkit-transform': 'translateX(150px) scale(1, 1)',
         '-moz-transform': 'translateX(150px) scale(1, 1)',
         '-o-transform': 'translateX(150px) scale(1, 1)',
         'transform': 'translateX(150px) scale(1, 1)',*/
        /*'-webkit-transform': 'translateZ(-300px)',
         '-moz-transform': 'translateZ(-300px)',
         '-o-transform': 'translateZ(-300px)',
         'transform': 'translateZ(-300px)',*/
        '-webkit-transform': 'scale(0.95)',
        '-moz-transform': 'scale(0.95)',
        '-o-transform': 'scale(0.95)',
        'transform': 'scale(0.95)',
        'opacity': 0
    });

    setTimeout(function () {
        var scrollable = lastColumn.retrieve('scrollable');
        if (scrollable) {
            scrollable.terminate();
        }

        els.erase(lastColumn);
        fade_columns(els, link, nivel);

        //Destroy the column when its animation has finished
        setTimeout(function () {
            lastColumn.destroy();
        }, animation_time);

        if (!els.length && link && !link.hasClass('cerrar') && !link.hasClass('guardar')) {
            setTimeout(function () {
                create_column(link, nivel);
            }, animation_time);
        }
    }, animation_time - 150);

}

/**
 * Main function that creates the columns
 * @param event
 * @param link
 */
function crearColumna(event, link) {
    "use strict";
    /*
     * Splits the class into an array and checks to see what level it is in
     */
    var classArr = link.get('class').split(' '),
        nivel = 1,
        all_columns,
        current_column = $(event.target).getParents('.columnas'),
        next_columns;

    classArr.each(function (elem) {
        if (elem.contains('nivel')) {
            nivel = Number(elem.replace(/nivel/, ""));
        }
    });

    //Eliminamos la clase "enabled" de todos los elementos de la columna
    link.getParent('.contenido').getElements('.enabled').each(function (item) {
        $(item).removeClass('enabled');
    });

    //Añadimos la clase "enabled" solo para el elemento seleccionado
    if (link.getParent('.controls') !== null) {
        link.getParent('.controls').addClass('enabled');
    } else if (link.getParent('.listado') !== null) {
        link.getParent('.listado').addClass('enabled');
    } else {
        link.addClass('enabled loading');
    }

    if ($(current_column[0])) {
        next_columns = $(current_column[0]).getAllNext('.columnas');
    }

    //If its the main menu or if its the last column
    if (current_column.length === 0 || next_columns.length === 0) {

        //Main menu
        if (current_column.length === 0) {

            all_columns = $('columnas').getElements('.columnas');
            if (all_columns.length) {
                fade_columns($('columnas').getElements('.columnas'), link, nivel);
            } else {
                create_column(link, nivel);
            }

        } else {
            create_column(link, nivel);
        }

    }

    //Eliminamos cualquier ventana que este visible
    if ($(current_column[0]) && next_columns.length >= 1) {
        fade_columns(next_columns, link, nivel);
    }

}

/**
 * Function for the Duplicate Name Checker, it checkes if the Validator is valid and sends the request
 * @param validar
 * @param elem
 * @param request
 */
function sendRequest(validar, elem, request) {

    "use strict";

    if (validar.validate()) {

        if (elem.getParent().getElements('.pagina_nombre').get('text').toString() === 'Seleccionar Página') {
            createAlert('Error', 'Seleccione una página primero');
        } else {
            request.send();
        }

    }

}

/**
 * Executes when the name checker failed (duplicate name on server)
 * @param field
 * @param errorMessage
 * @param error
 */
function nameValidationFailed(field, errorMessage, error) {
    "use strict";
    field.removeClass('validation-passed');
    field.addClass('validation-failed');

    if (errorMessage && errorMessage.hasClass('valid-name-error')) {
        errorMessage.reveal();
    } else {
        error.inject(field, 'after');
        error.reveal();
    }
}

/**
 * Main function that checks if there´s a duplicate name on the server
 * @param field
 * @param fields
 * @param isBlur
 * @param validar
 * @param elem
 * @param request
 */
function sendUniqueNameRequest(field, fields, isBlur, validar, elem, request) {
    "use strict";

    var jsonRequest = new Request.JSON({
        url: system.base_url + 'admin/ajax/unique_name',
        data: {
            'nombre': field.get('value'),
            'seccion': field.get('data-seccion'),
            'columna': field.get('data-columna'),
            'id': field.get('data-id'),
            'columna_id': field.get('data-columna-id'),
            'csrf_test': csrf_cookie
        },
        onSuccess: function (unique) {

            var error,
                errorMessage = field.getNext();

            error = new Element('div', {
                style: 'display: none',
                'class': 'validation-advice valid-name-error',
                text: 'Error: Este nombre ya está tomado por otro recurso'
            });

            if (unique && !isBlur) {
                g_uniqueNameCount += unique;
            }

            if (isBlur) {

                if (unique) {

                    field.addClass('validation-passed');
                    field.removeClass('validation-failed');

                    if (errorMessage) {
                        errorMessage.dissolve();
                    }

                } else {
                    nameValidationFailed(field, errorMessage, error);
                }

            } else {

                if (fields.length === g_uniqueNameCount) {
                    error.dissolve();
                    sendRequest(validar, elem, request);
                } else if (!unique) {
                    nameValidationFailed(field, errorMessage, error);
                }

            }
        }
    });

    jsonRequest.send();
}

function clickBotonPaginas(event, elem) {
    "use strict";
    $('menu').getElements('.enabled').removeClass('enabled');
}

/**
 * Handles the "Close Column" button event
 * @param event
 * @param elem
 */
function clickBotonCancelar(event, elem) {
    "use strict";
    var scrollTo = $('contenido').getScroll().x,
        columnaAnterior,
        parent,
        eliminar,
        columns,
        scrollable;

    $$('.columnas').each(function (elem, index) {
        if (index === $$('.columnas').length - 1) {
            scrollTo -= elem.getStyle('width').toInt();
        }
    });

    new Fx.Scroll('contenido', {
        duration: 500,
        transition : Fx.Transitions.Cubic.easeOut,
        wait: false
    }).start(scrollTo, 0);

    //Coluna anterior
    columnaAnterior = elem.getParent('.columnas').getPrevious('.columnas');

    if (columnaAnterior !== null) {
        //Eliminamos la clase "enabled" de todos los elementos de la columna anterior
        columnaAnterior.getElements('.enabled').removeClass('enabled');
    } else {
        $('pages').getElements('.enabled').removeClass('enabled');
        $('menu').getElements('.enabled').removeClass('enabled');
        $$('#pages, #contenido').removeClass('hide');
    }

    parent = elem.getParent('.columnas');
    scrollable = parent.retrieve('scrollable');
    scrollable.terminate();

    eliminar = elem.get('data-delete');

    if (eliminar) {
        new Request.JSON({
            url : eliminar,
            data: {
                csrf_test: csrf_cookie
            },
            onRequest : function () {

            },
            onSuccess : function (response) {

            },
            onFailure : function (xhr) {
                failureRequestHandler(xhr, eliminar);
            }
        }).send();
    }

    //Remove all the next columns
    columns = parent.getAllNext();
    columns.unshift(parent);
    fade_columns(columns, elem, 0);

}

/**
 * Handles the "Save Resource" event
 * @param event
 * @param elem
 */
function clickBotonGuardar(event, elem) {
    "use strict";
    //Columna anterior
    var columnaAnterior = elem.getParent('.columnas').getPrevious('.columnas'),
        columnaParent = elem.getParent('.contenido'),
        form = columnaParent.getElement('.form'),
        scrollTo = $('contenido').getScroll().x,
        validar,
        name,
        request,
        tables = elem.getParent('.contenido').getElements('.table_editor'),
        fields = form.getElements('.unique-name');

    //Check if there is a table field in products
    Array.each(tables, function (item) {
        var table = item.getElement('.tableGrid'),
            tableId = table.get('id'),
            input = item.getElement('.tableGridInput'),
            html;

        Array.each(table.getElements('.delete_column, .delete_row'), function (item_del) {
            item_del.destroy();
        });

        html = '<table id="' + tableId + '" class="tableGrid">' + table.get('html') + '</table>';

        input.set('value', html);

    });

    if (wysywygEditor.length > 0) {
        wysywygEditor.each(function (item) {
            item.saveContent();
        });
    }

    wysywygEditor = [];

    validar = new FormValidator.Inline(form, {
        evaluateOnSubmit : false
    });

    validar.add('password-strong', {
        errorMsg: 'La contraseña no es lo suficientemente fuerte, por favor intente mezclando letras y números',
        test: function (field) {

            var valid = true;

            if (field.get('value') !== '') {
                valid =  (field.get('value').test(/[^0-9a-bA-B\s]/gi));
            }

            return valid;
        }
    });

    validar.add('password-same', {
        errorMsg: 'Las contraseñas no coinciden',
        test: function () {

            var valid = false,
                userPass = $('userPass1').get('value');

            if (userPass === $('userPass2').get('value') || userPass === '') {
                valid = true;
            }

            return valid;

        }
    });

    request = new Request.JSON({
        url : elem.getProperty('href'),
        data : form,
        onProgress : function (event, xhr) {

        },
        onRequest : function () {

            elem.set('style', 'background-color: #2FC1FB');
            elem.set('html', 'guardando...');

        },
        onSuccess : function (data) {

            var enabledElem,
                elemHtml,
                catHtml,
                listEl,
                catEl,
                elemName,
                unsubscribe,
                parentList,
                previousCatList,
                pagina = columnaParent.getElements('.pagina_nombre'),
                nombre = columnaParent.getElement('.name'),
                appendClass = '',
                addDataId = '',
                columnas,
                myFx,
                list_el_class = 'listado drag';

            elem.set('style', 'background-color: #2FC1FB');
            elem.set('html', 'el recurso fue guardado');

            if (columnaAnterior) {
                enabledElem =  columnaAnterior.getElements('.enabled');
            }

            if (elem.hasClass('usuarios')) {
                elemName = columnaParent.getElement('input[name="first_name"]').get('value') + " " + columnaParent.getElement('input[name="last_name"]').get('value');
            } else {
                if (nombre) {
                    elemName = nombre.get('value');
                }
            }

            //Create New elem //TODO fix this mess
            if (elem.hasClass('no_sort') && !elem.hasClass('video')) {
                elemHtml = '<a class="nombre modificar ' + elem.get('data-level') + '" href="' + system.base_url + 'admin/' + elem.get('data-edit-url') + data.new_id + '"><span>' + elemName + '</span></a>' +
                '<a href="' + system.base_url + 'admin/' + elem.get('data-delete-url') + data.new_id + '" class="eliminar">eliminar</a>';
            } else if (elem.hasClass('video')) {

                if (elem.hasClass('categoria')) {
                    appendClass = ' categoria';
                    addDataId = elem.get('data-id');
                }

                list_el_class = 'video drag';

                var video_id = columnaParent.getElement('input[name="fileName"]').get('value');

                elemHtml = '<a class="modificar details ' + elem.get('data-level') + '" href="' + system.base_url + 'admin/' + elem.get('data-edit-url') + data.new_id + '">' +
                '<img height="64" src="http://img.youtube.com/vi/' + video_id + '/1.jpg" />' +
                '<div class="nombre"><span>' + elemName + '</span></div>' +
                '</a>' +
                '<a href="' + system.base_url + 'admin/' + elem.get('data-delete-url') + data.new_id + '" data-id="' + addDataId + '" class="eliminar' + appendClass + '">eliminar</a>';

            } else {

                if (elem.hasClass('categoria')) {
                    appendClass = ' categoria';
                    addDataId = elem.get('data-id');
                }

                elemHtml = '<div class="mover">mover</div>' +
                '<a class="nombre modificar ' + elem.get('data-level') + '" href="' + system.base_url + 'admin/' + elem.get('data-edit-url') + data.new_id + '"><span>' + elemName + '</span></a>' +
                '<a href="' + system.base_url + 'admin/' + elem.get('data-delete-url') + data.new_id + '" data-id="' + addDataId + '" class="eliminar' + appendClass + '">eliminar</a>';
            }

            if (elem.hasClass('categoria') && elem.hasClass('nuevo')) {

                catHtml = '<h3 class="header">Categoría: ' + elemName + '</h3>' +
                '<ul id="list_' + elem.get('data-id') + '" class="sorteable content" data-sort="' + system.base_url + 'admin/' + elem.get('data-reorder') + data.new_id + '">' +
                '</ul>';

                catEl = new Element('li', {
                    'id': data.new_id,
                    'class': 'pagina field',
                    'style': 'display: none',
                    'html': catHtml
                });

                previousCatList = elem.getParent('.columnas').getPrevious('.columnas').getPrevious('.columnas').getElement('.contenido_col');

                catEl.inject(previousCatList);
                catEl.reveal();

                myFx = new Fx.Scroll(previousCatList).toElement(catEl, 'y');

            }

            if (elem.hasClass('mailchimp')) {
                elemHtml = '<a class="nombre modificar ' + elem.get('nivel') + '" href="' + system.base_url + 'admin/' + elem.get('modificar') + '/' + responseHTML.trim() + '"><span>' + elemName + '</span></a>' +
                '<a href="' + system.base_url + 'admin/' + elem.get('eliminar') + '/' + responseHTML.trim() + '" data-id="' + addDataId + '" class="eliminar' + appendClass + '">eliminar</a>' +
                '<a href="' + system.base_url + 'admin/' + elem.get('data-unsubscribe-url') + responseHTML.new_id + '" class="unsubscribe">desuscribir</a>';
            }

            if (elem.hasClass('mailchimp-campaign')) {
                elemHtml = '<a class="nombre modificar ' + elem.get('nivel') + '" href="' + system.base_url + 'admin/' + elem.get('modificar') + '/' + responseHTML.trim() + '"><span>' + elemName + '</span></a>' +
                '<a href="' + system.base_url + 'admin/' + elem.get('eliminar') + '/' + responseHTML.trim() + '" data-id="' + addDataId + '" class="eliminar' + appendClass + '">eliminar</a>' +
                '<a href="' + system.base_url + 'admin/' + elem.get('data-send-url') + responseHTML.new_id + '" class="nivel3 mailing_send">enviar</a>';
            }

            if (elem.hasClass('tree')) {

                list_el_class = 'treedrag';

                elemHtml = '<div class="controls">' +
                '<div class="mover">mover</div>' +
                '<a class="nombre modificar ' + addDataId + ' ' + elem.get('data-level') + '" href="' + system.base_url + 'admin/' + elem.get('data-edit-url') + data.new_id + '"><span>' + elemName + '</span></a>' +
                '<a href="' + system.base_url + 'admin/' + elem.get('data-delete-url') + data.new_id + '" class="eliminar">eliminar</a>' +
                '</div>';
            }

            listEl = new Element('li', {
                'id': data.new_id,
                'class': list_el_class,
                'style': 'display: none',
                'html': elemHtml
            });

            if (columnaAnterior !== null && elem.hasClass('nuevo')) {

                //Contact Person
                if (elem.hasClass('contacto_persona')) {
                    parentList = columnaAnterior.getElement('#persona');
                } else if (elem.hasClass('contacto_form')) { //Form Elements
                    parentList = columnaAnterior.getElement('#list_contacto');
                } else if (elem.hasClass('contacto_direccion')) { //Form Elements
                    parentList = columnaAnterior.getElement('#list_direccion');
                } else if (elem.hasClass('selectbox')) { //Users, products and images
                    parentList = columnaAnterior.getElement('#list_' + columnaParent.getElement('.selectbox :selected').get('value'));
                } else if (elem.hasClass('grouped_selectbox')) { //Publicidad
                    parentList = columnaAnterior.getElement('#list_' + columnaParent.getElement('.selectbox :selected').getParent().get('data-pagina'));
                } else if (elem.hasClass('mailchimp')) { //Users, products and images
                    parentList = columnaAnterior.getElement('#subscribed');
                } else { //Rest of the lists
                    if (pagina.length > 0) { //Multiple lists
                        parentList = columnaAnterior.getElement('#list_' + pagina.get('id'));
                    } else { //One list
                        parentList = columnaAnterior.getElement('ul');
                    }
                }

                listEl.inject(parentList);
                listEl.reveal();

                new Fx.Scroll(columnaAnterior.getElement('.contenido_col')).toElement(listEl, 'y');

            }

            if (enabledElem && enabledElem[0].getElement('.nombre')) {
                enabledElem[0].getElement('.nombre span').set('text', elemName);
            }

            if (elem.hasClass('categoria')) {
                elem.getParent('.columnas').getPrevious('.columnas').getPrevious('.columnas').getElement('#list_' + elem.get('data-id')).getPrevious().set('text', 'Categoría: ' + elemName);
            }

            //Eliminamos la clase "enabled" de todos los elementos de la columna anterior
            if (columnaAnterior !== null) {
                enabledElem.removeClass('enabled');
            }

            fadeFx = new Fx.Tween(elem.getParent('.columnas'), {
                property : 'opacity'
            });

            fadeFx.start(0).chain(function () {
                if (elem.getParent('.columnas')) {
                    elem.getParent('.columnas').retrieve('scrollable').terminate();
                    elem.getParent('.columnas').destroy();
                }
                levelNum -= 1;
            });

            //resizeColumn(columnaAnterior);

            initSortables($$('ul.sorteable'));

            //Remove all the next columns
            columnas = columnaParent.getParent().getAllNext();
            columnas.unshift(columnaParent);
            fade_columns(columnas, elem, 0);

            $$('.columnas').each(function (elem, index) {
                if (index === $$('.columnas').length - 1) {
                    scrollTo -= elem.getStyle('width').toInt();
                }
            });

            new Fx.Scroll('contenido', {
                duration: 500,
                transition : Fx.Transitions.Cubic.easeOut,
                wait: false
            }).start(scrollTo, 0);

            if (elem.hasClass('page')) {
                reload_page_column();
            }

        },
        onFailure : function (xhr) {
            failureRequestHandler(xhr, elem.getProperty('href'));
            elem.set('style', 'background-color: #ff0000');
            elem.set('html', 'el recurso no pudo ser creado');
        }
    });

    fields = form.getElements('.unique-name');

    if (fields.length > 0) {

        g_uniqueNameCount = 0;

        fields.each(function (field) {
            var jsonRequest = new Request.JSON({
                url: system.base_url + 'admin/ajax/unique_name',
                onSuccess: function () {
                    if (field.value !== '') {
                        sendUniqueNameRequest(field, fields, false, validar, elem, request);
                    }
                }
            }).post({
                    'nombre': field.get('value'),
                    'seccion': field.get('data-seccion'),
                    'columna': field.get('data-columna'),
                    'id': field.get('data-id'),
                    'columna_id': field.get('data-columna-id'),
                    'csrf_test': csrf_cookie
                });
        });
    } else {
        sendRequest(validar, elem, request);
    }

}

/**
 * Show the import details
 * @param elem
 * @param response
 */
function import_success(elem, response) {
    "use strict";

    var columns = [],
        content,
        adds = '',
        updates = '',
        errors = '',
        parent  = elem.getParent('.columnas'),
        previous = parent.getPrevious();

    columns.push(previous);
    columns.push(parent);

    fade_columns(columns, $('mailing_list_button'), 5);

    response.adds.each(function (item) {
        adds += '<li>' + item.email + '</li>';
    });

    response.updates.each(function (item) {
        updates += '<li>' + item.email + '</li>';
    });

    response.errors.each(function (item) {
        errors += '<li>' + item.error + '</li>';
    });

    content = '<div><p>Resultado:</p>' +
    '<div class="em_title">A&ntilde;adidos: ' + response.add_count + '</div>' +
    '<ul class="added emails">' + adds + '</ul>' +
    '<div class="em_title">Actualizados: ' + response.update_count + '</div>' +
    '<ul class="updated emails">' + updates + '</ul>' +
    '<div class="em_title">Errores: ' + response.error_count + '</div>' +
    '<ul class="errors emails">' + errors + '</ul></div>';

    var win = new StickyWin({
        content : StickyWin.ui('Alerta', content, {
            width : '600px',
            buttons : [{
                text : 'cerrar'
            }]
        })
    });

}

/**
 * Handles the "Save Resource" event
 * @param event
 * @param elem
 */
function clickBotonImportar(event, elem) {
    "use strict";

    var request,
        content;

    request = new Request.JSON({
        url : elem.getProperty('href'),
        data : elem.getParent('.contenido').getElement('form'),
        onRequest : function () {
            elem.set({
                'style': 'background-color: #2FC1FB',
                'text': 'importando...'
            });
        },
        onSuccess : function (response) {

            if(!response.error_code) {
                import_success(elem, response);
            } else {

                content = '<p>' + response.message + '</p>' +
                '<ul class="emails">';

                response.error_emails.each(function (item) {
                    content += '<li>' + item + '</li>';
                });

                content += '</ul><p>Desea continuar importando el resto de correos?</p>';

                var win = new StickyWin({
                    content : StickyWin.ui('Alerta', content, {
                        width : '600px',
                        buttons : [
                            {
                                text : 'cerrar',
                                onClick : function () {
                                    elem.set('text', 'importar');
                                }
                            },
                            {
                                text: 'importar',
                                onClick : function () {

                                    var url = system.base_url + 'admin/mailing/continue_import/' + response.list_id;

                                    request = new Request.JSON({
                                        url : url,
                                        data : {
                                            temp_path: response.temp_path,
                                            csrf_test: csrf_cookie
                                        },
                                        onRequest : function () {
                                            elem.set({
                                                'style': 'background-color: #2FC1FB',
                                                'text': 'continuando...'
                                            });
                                        },
                                        onSuccess : function (response) {

                                            if(!response.error_code) {
                                                import_success(elem, response);
                                            } else {
                                                createAlert('Error', response);
                                            }

                                        },
                                        onFailure : function (xhr) {
                                            failureRequestHandler(xhr, url);
                                            elem.set('style', 'background-color: #ff0000');
                                            elem.set('html', 'el recurso no pudo ser importado');
                                        }
                                    });

                                    request.send();

                                }
                            }
                        ]
                    })
                });

            }

        },
        onFailure : function (xhr) {
            failureRequestHandler(xhr, elem.getProperty('href'));
            elem.set('style', 'background-color: #ff0000');
            elem.set('html', 'el recurso no pudo ser importado');
        }
    });

    request.send();

}

function clickBotonEnviar(event, elem) {

    "use strict";

    var win,
        content;

    content = '<p>Est&aacute; a punto de enviar esta campa&ntilde;a a:</p>' +
    '<div><strong>' + elem.getProperty('data-list') + '</strong></div>' +
    '<div><strong>' + elem.getProperty('data-subscribers') + ' suscriptores</strong></div>';

    win = new StickyWin({
        content : StickyWin.ui('Alerta', content, {
            width : '600px',
            buttons : [
                {
                    text : 'cancelar'
                },
                {
                    text: 'enviar ahora',
                    onClick : function () {

                        var url = elem.getProperty('href'),
                            request;

                        request = new Request.JSON({
                            url : url,
                            data : {
                                csrf_test: csrf_cookie
                            },
                            onSuccess : function (response) {

                                if (!response.error_code) {

                                    var parent = elem.getParent('.columnas');

                                    fade_columns([parent]);

                                    parent
                                        .getPrevious()
                                        .getElement('.enabled')
                                        .removeClass('enabled')
                                        .getElement('.mailing_send')
                                        .destroy();
                                } else {
                                    createAlert('Error', response);
                                }

                            },
                            onFailure : function (xhr) {
                                failureRequestHandler(xhr, url);
                                elem.set('style', 'background-color: #ff0000');
                                elem.set('html', 'no se pudo enviar la campa&ntilde;a');
                            }
                        });

                        request.send();

                    }
                }
            ]
        })
    });

}

/**
 * Handles the "Delete resource" event
 * @param event
 * @param elem
 */
function clickBotonEliminar(event, elem) {
    "use strict";
    var contenido = $(elem).getParent('.contenido'),
        parentColumna = $(elem).getParent('.columnas'),
        nombre = $(elem).getPrevious().get('text'),
        alerta,
        previousCatListItem,
        enabled = elem.getSiblings()[0].hasClass('enabled');

    parentColumna = parentColumna.get('id').replace("contenido_nivel", '');

    fadeFx = new Fx.Tween(contenido, {
        property : 'opacity'
    });

    if (enabled) {
        createAlert('Error', 'No puede eliminar el recurso que esta editando este momento');
    } else {
        alerta = new StickyWin({
            content : StickyWin.ui('Alerta', 'Esta seguro que desea eliminar el recurso "' + nombre + '"?', {
                width : '400px',
                buttons : [{
                    text : 'eliminar',
                    onClick : function () {
                        //recargarColumna(contenido, $(elem).getProperty('href'));
                        var request = new Request.JSON({
                            url : $(elem).getProperty('href'),
                            data: {
                                csrf_test: csrf_cookie
                            },
                            onProgress : function (event, xhr) {

                            },
                            onRequest : function () {

                            },
                            onSuccess : function (response) {

                                if ( ! response.error_code) {

                                    var remove_elem,
                                        dissolve,
                                        error = '';

                                    if ($(elem).getParent('li')) {
                                        $(elem).getParent('li').addClass('removed');
                                        remove_elem = $(elem).getParent('li');
                                    } else if ($(elem).getParent('.treedrag')) {
                                        remove_elem = $(elem).getParent('.treedrag');
                                    }

                                    if (elem.hasClass('categoria')) {

                                        previousCatListItem = elem.getParent('.columnas')
                                            .getPrevious('.columnas')
                                            .getElement('#list_' + elem.get('data-id'))
                                            .getParent();

                                        remove_elem = previousCatListItem;

                                        var myFx = new Fx.Scroll(elem.getParent('.columnas')
                                            .getPrevious('.columnas')
                                            .getElement('.contenido_col'))
                                            .toElement(previousCatListItem, 'y');
                                    }

                                    dissolve = new Fx.Morph(remove_elem);

                                    dissolve.start({
                                        'height': 0,
                                        'opacity': 0
                                    }).chain(function () {
                                        remove_elem.destroy();
                                    });

                                    if (elem.getParent('#pagina_tree')) {
                                        reload_page_column();
                                    }

                                } else {
                                    error = '<p>' + response.message + '</p>';
                                    error += '<p>' + response.error_message + '</p>';
                                    createAlert('Error', error);
                                }
                            },
                            onFailure : function (xhr) {
                                failureRequestHandler(xhr, $(elem).getProperty('href'));
                            }
                        });

                        request.send();
                    }
                }, {
                    text : 'cancelar'
                }]
            })
        });
    }

}


/**
 * Handles the "Unsubscribe" event
 * @param event
 * @param elem
 */
function clickBotonDesuscribir(event, elem) {
    "use strict";
    var contenido = $(elem).getParent('.contenido'),
        parentColumna = $(elem).getParent('.columnas'),
        nombre = $(elem).getPrevious('.nombre').get('text'),
        alerta,
        previousCatListItem,
        enabled = elem.getSiblings()[0].hasClass('enabled');

    parentColumna = parentColumna.get('id').replace("contenido_nivel", '');

    fadeFx = new Fx.Tween(contenido, {
        property : 'opacity'
    });

    if (enabled) {
        createAlert('Error', 'No puede desuscribir el recurso que esta editando este momento');
    } else {
        alerta = new StickyWin({
            content : StickyWin.ui('Alerta', 'Esta seguro que desea desuscribir el el correo "' + nombre + '"?', {
                width : '400px',
                buttons : [{
                    text : 'desuscribir',
                    onClick : function () {

                        var request = new Request({
                            url : $(elem).getProperty('href'),
                            data: {
                                csrf_test: csrf_cookie
                            },
                            onProgress : function () {

                            },
                            onRequest : function () {

                            },
                            onSuccess : function (response) {

                                if (!response.error_code) {

                                    var remove_elem,
                                        dissolve;

                                    if ($(elem).getParent('li')) {
                                        $(elem).getParent('li').addClass('removed');
                                        remove_elem = $(elem).getParent('li');
                                    }

                                    dissolve = new Fx.Morph(remove_elem);

                                    dissolve.start({
                                        'height': 0,
                                        'opacity': 0
                                    }).chain(function () {
                                        remove_elem.inject($('unsubscribed'));
                                        dissolve.start({
                                            'height': 29,
                                            'opacity': 1
                                        });
                                    });

                                } else {
                                    createAlert('Error', response);
                                }
                            },
                            onFailure : function (xhr) {
                                failureRequestHandler(xhr, $(elem).getProperty('href'));
                            }
                        });

                        request.send();
                    }
                }, {
                    text : 'cancelar'
                }]
            })
        });
    }

}

/**
 * Gets all the subscribers from a list
 * @param event
 * @param elem
 */
function clickBotonGetSubscribers(event, elem) {
    "use strict";

    var alerta,
        arr,
        content;

    new Request.JSON({
        url: elem.getProperty('href'),
        onSuccess: function (response) {

            if(!response.error_code) {

                content = '<p>Se enviar&aacute; a los siguientes destinatarios:</p>' +
                '<ul class="emails">';

                arr = Object.keys(response.subscribed).map(function(k) { return response.subscribed[k] })

                arr.each(function (item) {
                    content += '<li>' + item['Email Address'] + ' ' + item['First Name'] + ' ' + item['Last Name'] + '</li>';
                });

                content += '</ul>';

                alerta = new StickyWin({
                    content : StickyWin.ui('Info', content, {
                        width : '400px',
                        buttons : [{
                            text : 'cerrar'
                        }]
                    })
                });

            }


        }
    }).post({
            csrf_test: csrf_cookie
        });

}

/**
 * Handles the "Select resource" event, ex: Select page
 * @param event
 * @param elem
 */
function clickBotonSeleccionar(event, elem) {
    "use strict";
    var parent = elem.getParent('.contenido'),
        parentColumn = elem.getParent('.columnas'),
        prevColumn = parentColumn.getPrevious(),
        link,
        mapaImagen = $('mapaImagen'),
        mapaContent = $('mapaContent');

    parent.getElements('.enabled').removeClass('enabled');
    prevColumn.getElements('.enabled').removeClass('enabled');
    elem.addClass('enabled');

    fadeFx = new Fx.Tween(parentColumn, {
        property : 'opacity',
        duration : 200
    });

    fadeFx.start(0).chain(function () {
        parentColumn.retrieve('scrollable').terminate();
        parentColumn.destroy();
    });

    //Cambiamos el valor del input hidden por el valor del ID de la pagina
    $$('.pagina_seleccion').set('value', elem.get('id'));
    //Cambiamos el nombre del boton
    $$('.pagina_nombre').set('text', elem.get('text'));
    $$('.pagina_nombre').set('id', elem.get('id'));
    //Cambiamos el link del boton para que aparezca seleccinado en la ventana de seleccion
    $$('.pagina_nombre').get('href').toString().replace(/[0-9]/g, '');
    //$$('.pagina_nombre').set('href', link + elem.get('id'));

    /*
     * EDITOR DE MAPAS
     */

    if (elem.hasClass('mapa')) {

        if (mapaImagen) {
            mapaImagen.destroy();
        }

        new Request.JSON({
            url: system.base_url + 'admin/mapas/ajax_obtenerDatosMapa',
            onSuccess: function (mapa) {

                var mapaImagen = Asset.image(system.base_url + 'assets/public/images/mapas/mapa_' + elem.get('id') + '.' + mapa.mapaImagen, {
                    id: 'mapaImagen',
                    onLoad: function () {

                        mapaImagen.inject(mapaContent);
                        var imageSize = mapaImagen.getComputedSize(),
                            columnSize = prevColumn.getComputedSize(),
                            sizeFx = new Fx.Morph(prevColumn, {
                                transition : Fx.Transitions.Cubic.easeOut
                            }),
                            nombre = $('mapaUbicacionNombre').get('value'),
                            myDrag;

                        mapaContent.getParent('.contenido_col').setStyle('width', '');

                        sizeFx.start({
                            'width' : columnSize.width + imageSize.width,
                            'min-width' : columnSize.width + imageSize.width
                        }).chain(function () {
                            $('mapa').fade(1);
                        });

                        if (nombre === '') {
                            nombre = 'Ubicacion';
                        }

                        $('ubicacion').set('text', nombre);

                        myDrag = new Drag.Move('ubicacion', {
                            container : mapaContent,
                            onDrag: function (el) {

                                var position = el.getPosition(mapaContent);

                                $('mapaUbicacionX').set('value', position.x);
                                $('mapaUbicacionY').set('value', position.y);

                            }
                        });

                        $(document.body).addEvent('keyup:relay(input#mapaUbicacionX, input#mapaUbicacionY)', function () {
                            $('ubicacion').setPosition({
                                x: $('mapaUbicacionX').get('value'),
                                y: $('mapaUbicacionY').get('value')
                            });
                        });

                        $(document.body).addEvent('keyup:relay(input#mapaUbicacionNombre)', function (event, elem) {

                            var nombre = elem.get('value').trim();

                            if (nombre === '') {
                                nombre = 'Ubicacion';
                            }

                            $('ubicacion').set('text', nombre);

                        });

                    }
                });

            }
        }).post({
                'id': elem.get('id'),
                csrf_test: csrf_cookie
            });
    }

}

/**
 * Attaches the sortables to the admin sections, this lets us drag & drop the visible sections for the client
 */
function seccionesAdmin() {
    "use strict";
    var sortable = new Sortables('.content.secciones', {
        opacity: 0,
        clone: true,
        handle: '.content.secciones .mover',
        revert: true,
        onStart: function (elem, clone) {
            clone.setStyle('z-index', 10000);
            clone.addClass('dragging');
            $$('.content.secciones').addClass('drop');
        },
        onComplete: function () {
            $$('.content.secciones').removeClass('drop');

            var ids = [];

            sortable.serialize(2, function (element) {
                if (!element.hasClass('dragging')) {
                    if (element.getParent('#seccionesAsignadas') !== null) {
                        ids.push(element.id);
                    }

                }

            });

            $('seccionesAdmin').set('value',  JSON.encode(ids));

        }
    });



}

/**
 * Request the login views if the user has been logged out
 * @param request
 */
function requestLoginView(request) {
    "use strict";
    request = new Request.HTML({
        url : system.base_url + 'login/form',
        data: {
            csrf_test: csrf_cookie
        },
        onSuccess : function (responseTree, responseElements, responseHTML) {
            alertaLogin = new StickyWin({
                content : responseHTML
            });
        },
        onFailure : function (xhr) {
            failureRequestHandler(xhr, 'login/login_form');
        }
    });

    request.send();
}

/**
 * Shows the login window
 * @param request
 */
function createLoginWindow(request) {
    "use strict";
    var alerta = new StickyWin({
        content : StickyWin.ui('Su sesión ha expirado', 'Desea volver a entrar?', {
            width : '400px',
            buttons : [{
                text : 'aceptar',
                onClick : function () {
                    requestLoginView(request);
                }
            }, {
                text : 'cancelar',
                onClick : function () {
                    window.location = "login";
                }
            }]
        })
    });
}

/**
 * Creates the session timeout tooltip (Currently disabled)
 * @param time
 */
function initLoginCoundown(time) {
    "use strict";

    g_time = time;

    var div = $('counter'),
        myEffect;

    countdown = new CountDown({
        date: new Date(new Date().getTime() + (time * 1000)),
        frequency: 100,
        onChange: function (counter) {

            var text = '<div>Su sesión expira en:</div><div class="tiempo">';

            if (counter.days === 0 && counter.hours === 0 && counter.minutes < 5) {
                myEffect.start({
                    'opacity': 1,
                    'margin-bottom': 9
                });
            }

            if (counter.days > 0) {
                text = counter.days + 'd ';
            }

            text += (counter.hours >= 10 ? '' : '0') + counter.hours + ':';
            text += (counter.minutes >= 10 ? '' : '0') + counter.minutes + ':';
            text += (counter.second >= 10 ? '' : '0') + counter.second + '</div>';

            div.set('html', text);
        },
        //complete
        onComplete: function () {

            div.set('text', 'Su sesión ha expirado!');

            var request;

            (function () {

                request = new Request.JSON({
                    url : 'login/logged_in',
                    data: {
                        csrf_test: csrf_cookie
                    },
                    onProgress : function (event, xhr) {

                    },
                    onRequest : function () {

                    },
                    onSuccess : function (response) {

                        if (!response) {
                            createLoginWindow(request);
                        }

                    },
                    onFailure : function (xhr) {
                        failureRequestHandler(xhr, 'login/logged_in');
                    }
                }).send();

            }).delay(1000);


        }
    });

    myEffect = new Fx.Morph(div, {
        duration: 200,
        link: 'cancel',
        transition: Fx.Transitions.Sine.easeOut
    });

    $('username').addEvents({
        mouseover: function () {
            myEffect.start({
                'opacity': 1,
                'margin-bottom': 9
            });
        },
        mouseout: function () {
            myEffect.start({
                'opacity': 0,
                'margin-bottom': 30
            });
        }
    });

}

function showBannerConfig(event, item) {
    var banner = item.getProperty('value');
    $$('.banner_config').each(function(item){
        if(item.getProperty('data-type') === banner) {
            item.setStyle('display', 'block');
        } else {
            item.setStyle('display', 'none');
        }
    });
}

function showPages(){
    fade_columns($('columnas').getElements('.columnas'), '', 0);
    $$('#pages, #contenido').removeClass('hide');
    $$('#menu a').removeClass('enabled');
}

/**
 * Starts all the events and functions
 */
window.addEvent('domready', function () {
    "use strict";

    var menu = $('menu'),
        scroll;

    //Desactivar los links
    $(document.body).addEvent('click:relay(a)', function (event, clicked) {
        if (!clicked.hasClass('external')) {
            event.preventDefault();
        }

    });

    $$('#menu li a').addEvent('click', clickBotonMenu);

    $(document.body).addEvents({

        'click:relay(a.cerrar)': clickBotonCancelar,
        'click:relay(a.guardar)': clickBotonGuardar,
        'click:relay(a.mailchimp_send)': clickBotonEnviar,
        'click:relay(a.importar)': clickBotonImportar,
        'click:relay(a.eliminar)': clickBotonEliminar,
        'click:relay(a.unsubscribe)': clickBotonDesuscribir,
        'click:relay(a.get_subscribers)': clickBotonGetSubscribers,
        'click:relay(#pages a)': clickBotonPaginas,
        'click:relay(#pages .show)': showPages,
        'change:relay(#bannerType)': showBannerConfig,
        'keyup:relay(#videoId)': copyVideoIdListener,
        'click:relay(a.seleccionar)': clickBotonSeleccionar,
        'click:relay(a.nivel1, a.nivel2, a.nivel3, a.nivel4, a.nivel5, a.nivel6, a.nivel7, a.nivel8)': crearColumna,

        'keyup:relay(input#paginaNombre)': function (event, elem) {
            elem.value = $('paginaNombreMenu').value;
        },

        'blur:relay(.unique-name)': function (event, elem) {

            var errorMessage = elem.getNext();

            if (elem.value !== '') {
                sendUniqueNameRequest(elem, null, true, null, null, null);
            } else if (errorMessage && errorMessage.hasClass('valid-name-error')) {
                errorMessage.dissolve();
            }

        },

        'click:relay(#login input[type="submit"])': function (event, elem) {
            event.preventDefault();

            var request,
                formData = elem.getParent('form');

            request = new Request.HTML({
                url : elem.getParent('form').get('action'),
                onProgress : function (event, xhr) {

                },
                onRequest : function () {

                },
                onSuccess : function (responseTree, responseElements, responseHTML) {
                    if (responseTree[0].innerText !== 'Sesión iniciada con éxito') {
                        alertaLogin.setContent(responseHTML);
                    } else {

                        initLoginCoundown(g_time);

                        alertaLogin.destroy();
                        createAlert('Mensaje', responseHTML);
                    }

                },
                onFailure : function (xhr) {
                    failureRequestHandler(xhr, elem.getParent('form').get('action'));
                }
            });

            request.post(formData);
        }
    });

    scroll = new Scrollable(menu, {
        //autoHide: false
    });

    menu.store('scrollable', scroll);

    csrf_cookie = Cookie.read('csrf_cookie');

    var myTips = new Tips('.tooltip');

});