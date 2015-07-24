/*global Sortables,$,$$, system */

var moduleManager = {

    sort_index: undefined,
    sortableRows: new Sortables('#rows', {
        clone: true,
        constrain: true,
        revert: false,
        opacity: 0,
        handle: '.move_row',
        onStart: function (elem, clone) {
            "use strict";
            clone.setStyle('z-index', 10000);
            clone.addClass('dragging');
            moduleManager.sort_index = elem.getParent().getChildren('li.row').indexOf(elem) - 1;
        },
        onComplete: function (elem) {
            "use strict";
            var target_index = elem.getParent().getChildren('li.row').indexOf(elem);
            moduleManager.sortRows(moduleManager.sort_index, target_index, elem.getElement('table').get('data-page-id'));
        }
    }),

    sort_modules_origin_list: undefined,
    sortableModules: new Sortables('.modules', {
        clone: true,
        revert: true,
        opacity: 0,
        handle: '.move_module',
        onStart: function (elem, clone) {
            "use strict";
            moduleManager.sort_modules_origin_list = elem.getParent();
            clone.setStyle('z-index', 10000);
            clone.addClass('dragging');
            $$('.modules').addClass('drop');
        },
        onComplete: function (elem) {
            "use strict";
            $$('.modules').removeClass('drop');
            moduleManager.sortModules(elem, moduleManager.sort_modules_origin_list);
            detectInputWidth(elem.getParent());
        }
    }),

    init : function () {
        "use strict";

        $('module_manager').addEvents({
            'click:relay(#add_row .rows)': moduleManager.addRowTypeListener,
            'click:relay(.add_module)': moduleManager.moduleCreateWindowHandler,
            'click:relay(.remove_row)': moduleManager.removeRowListener,
            'click:relay(.remove_module)': moduleManager.removeModuleListener,
            'change:relay(.module select)': moduleManager.moduleInfoChangeListener,
            'change:relay(.module .banner_select)': moduleManager.showBannerViews,
            'change:relay(.module .banner_view_select)': moduleManager.selectBannerViews,
            'input:relay(.module input, .module textarea)': moduleManager.moduleInfoChangeListener,
            'click:relay(.module input[type="checkbox"])': moduleManager.moduleInfoChangeListener,
            'click:relay(.save_module)': moduleManager.updateModule,
            'click:relay(#copiar_estructura)': moduleManager.copyStructureClickListener,
            'click:relay(.content_column .click)': moduleManager.showHideSourceOrdering
        });

        $$('#rows > li').each(function (item) {
            moduleManager.sortableRows.addItems(item);
        });

        $$('.modules').each(function(item) {
            moduleManager.sortableModules.addLists(item);
        });

        $$('.save_module').dissolve();

    },

    showHideSourceOrdering: function (event, elem) {
        if(elem.hasClass('hidden')) {
            elem.addClass('shown');
            elem.removeClass('hidden');
            elem.getNext().reveal();
        } else {
            elem.addClass('hidden');
            elem.removeClass('shown');
            elem.getNext().dissolve();
        }
    },

    showBannerViews: function (event, elem) {
        var banner = elem.getElement(':selected').get('data-type');

        elem.getParent('.content').getElements('.banner_view_select').each(function(item){
            if(banner === item.getProperty('data-type')) {
                item.setStyle('display', 'inline');
                elem.getParent('.content').getElement('.banner_view_hidden').setProperty('value', item.getElement(':selected').get('value'));
            } else {
                item.setStyle('display', 'none');
            }
        });

        elem.getParent('.module').getElement('.save_module').reveal();

    },

    selectBannerViews: function (event, elem) {
        elem.getParent('.content').getElement('.banner_view_hidden').setProperty('value', elem.getElement(':selected').get('value'));
        elem.getParent('.module').getElement('.save_module').reveal();
    },

    moduleInfoChangeListener: function (event, elem) {

        "use strict";

        var module = elem.getParent('.module'),
            contentModule = elem.getParent('.mod_content'),
            parent = module.getElement('#currentContent'),
            clone;

        //If its the content module
        if (contentModule != undefined && elem.getElement(':selected') != undefined) {

            switch (elem.getElement(':selected').get('value').toInt()) {
                case 7: //Redirect
                    parent.empty();
                    clone = module.getElement('#h_pagina').clone();
                    clone.getElement('select').set('name', 'paramentro2');
                    clone.inject(parent);
                    break;

                case 5: //Publicacion
                    parent.empty();
                    clone = module.getElement('#h_listado').clone();
                    clone.getElement('input[type="checkbox"]').set('name', 'paramentro2');
                    clone.inject(parent);
                    break;

                case 4: //Catalogo
                case 6: //Galeria
                    parent.empty();
                    clone = module.getElement('#h_categorias').clone();
                    clone.getElement('input[type="checkbox"]').set('name', 'paramentro2');
                    clone.inject(parent);
                    break;

                default: //Hidden - Ninguno

                    if (elem.get('name') === 'parametro2') {
                        if(elem.getElement(':selected').get('value').toInt() === 0) {
                            elem.getParent().getElement('label[for="parametro3"]').setStyle('display', 'inline');
                            elem.getParent().getElement('input[name="parametro3"]').setStyle('display', 'inline');
                        } else {
                            elem.getParent().getElement('label[for="parametro3"]').setStyle('display', 'none');
                            elem.getParent().getElement('input[name="parametro3"]').setStyle('display', 'none');
                        }
                    } else if (elem.get('name') === 'parametro1') {
                        parent.empty();
                        clone = module.getElement('#h_default').clone();
                        clone.getElement('input[type="hidden"]').set('name', 'parametro2');
                        clone.inject(parent);
                    }
                    break;
            }

            module.getElement('.save_module').reveal();

        }


        elem.getParent('.module').getElement('.save_module').reveal();
    },

    copyStructureClickListener: function (event, clickedElem) {

        var to_page = clickedElem.getParent().getElement('select :selected').get('value');

        if(!to_page.toInt()) {

            new StickyWin({
                content : StickyWin.ui('Error', 'Escoja la página de donde quiere copiar la estructura', {
                    width : '500px',
                    buttons : [{
                        text : 'cerrar'
                    }]
                })
            });

            return;
        }

        new Request.JSON({
            url : system.baseUrl + 'admin/structure/copy',
            noCache : true,
            data : {
                from_page: clickedElem.getParent().getElement('select :selected').get('value'),
                to_page: clickedElem.get('data-pagina-id'),
                csrf_test: csrf_cookie
            },
            onSuccess : function(responseJSON) {

                if (responseJSON.code === 1) {
                    clickBotonCancelar(event, clickedElem);
                } else {

                    new StickyWin({
                        content : StickyWin.ui('Error', responseJSON.message, {
                            width : '500px',
                            buttons : [{
                                text : 'cerrar'
                            }]
                        })
                    });

                }

            },
            onFailure : function(xhr) {
                failureRequestHandler(xhr, system.baseUrl + 'admin/structure/copy');
            }
        }).send();

    },

    updateModule : function (event, clickedElem) {

        var module = clickedElem.getParent('.module'),
            moduleObj,
            parametro1,
            parametro2,
            parametro3,
            parametro4,
            habilitado,
            vista,
            html = [];

        if(module.getElement('*[name="parametro1"]').getElement(':selected') != undefined) //If select box
            parametro1 =  module.getElement('*[name="parametro1"]').getElement(':selected').get('value');
        else if (module.getElement('*[name="parametro1"][type="checkbox"]') != undefined) //If checkbox
            parametro1 =  module.getElement('*[name="parametro1"]').get('checked');
        else
            parametro1 =  module.getElement('*[name="parametro1"]').get('value'); //Textxbox

        if (module.getElement('*[name="parametro2"]')) {
            if (module.getElement('*[name="parametro2"]').getElement(':selected')) {//If select box
                parametro2 =  module.getElement('*[name="parametro2"]').getElement(':selected').get('value');
            } else if (module.getElement('*[name="parametro2"][type="checkbox"]')) {//If checkbox
                parametro2 =  module.getElement('*[name="parametro2"]').get('checked');

            } else { //Textbox
                parametro2 =  module.getElement('*[name="parametro2"]').get('value');
            }
        }

        if(module.getElement('*[name="parametro3"]').getElement(':selected') != undefined) //If select box
            parametro3 =  module.getElement('*[name="parametro3"]').getElement(':selected').get('value');
        else if (module.getElement('*[name="parametro3"][type="checkbox"]') != undefined) //If checkbox
            parametro3 =  module.getElement('*[name="parametro3"]').get('checked');
        else
            parametro3 =  module.getElement('*[name="parametro3"]').get('value'); //Textxbox

        if(module.getElement('*[name="parametro4"]'))
            parametro4 =  module.getElement('*[name="parametro4"]').get('value');

        if(module.getElement('*[name="moduloVista"]').getElement(':selected') != undefined)//Select
            vista = module.getElement('*[name="moduloVista"]').getElement(':selected').get('value');
        else
            vista = module.getElement('*[name="moduloVista"]').get('value'); //Textxbox

        if(!vista){

        }

        var traducciones = [];

        module.getElements('.nombre_modulo').each(function(item){

            var traduccion = {
                diminutivo : item.get('rel'),
                valor: item.get('value')
            };

            traducciones.push(traduccion);
        });

        module.getElements('textarea.modulo_html').each(function(item){
            var obj = {};
            obj.idioma = item.get('name').replace('_html', '');
            obj.valor = item.get('value');
            html.push(obj);
        });

        habilitado = module.getElement('[name="habilitado"]').checked;

        moduleObj = {
            id : module.get('id').replace(/mod_/, ""),
            name : traducciones,
            param1 : parametro1,
            param2 : parametro2,
            param3 : parametro3,
            param4 : parametro4,
            habilitado : habilitado,
            html : html,
            showTitle : module.getElement('*[name="moduloMostrarTitulo"]').get('checked'),
            clase : module.getElement('*[name="moduloClase"]').get('value'),
            paginacion : module.getElement('*[name="moduloVerPaginacion"]').get('checked'),
            vista: vista
        };

        new Request({
            url: system.baseUrl + 'admin/modulos/updateModule',
            data : {
                moduleData: JSON.encode(moduleObj),
                csrf_test: csrf_cookie
            },
            onRequest: function(){

            },
            onSuccess: function(){
                clickedElem.dissolve();
            },
            onFailure: function(xhr){
                failureRequestHandler(xhr, system.baseUrl + 'admin/modulos/updateModule');
            }
        }).send();

    },

    addRowTypeListener: function(event, clickedElem){
        var id =clickedElem.get('id').replace(/row_/, "");
        moduleManager.createRowElements(id);

    },

    createRowElements: function(id) {

        var parentElem = $('rows');
        var pageId = $('paginaId').get('value');

        new Request.HTML({
            url : system.baseUrl + 'admin/modulos/addRow/' + pageId + '/' + id,
            noCache : true,
            data : {
                csrf_test: csrf_cookie
            },
            onRequest : function() {

            },
            onSuccess : function(responseTree, responseElements, responseHTML) {

                var row = new Element('li', {
                    'class': 'row',
                    html: ' <div class="move_row"></div>',
                    'style': 'opacity: 0'
                });

                var proxy = new Element('div').adopt(this.response.elements);
                var id = proxy.getElement('table').getProperty('id');

                id = id.replace('row_', '');

                var row_controls = new Element('div', {
                    class: 'row_controls',
                    html: '<div class="input check">' +
                        '<input id="fila_' + id + '_expanded" type="checkbox" name="fila[' + id + '][expandida]" value="1"><label for="fila_' + id + '_expanded">Expandida:</label>' +
                        '</div><div class="input small">' +
                        '<label>Clase:</label>' +
                        '<input type="text" name="fila[' + id + '][class]" value=""></div>'
                });

                var removeRowButton = new Element('div', {
                    'class': 'remove_row',
                    html: 'X'
                });

                row_controls.inject(row);

                removeRowButton.inject(row);

                var rowContent = new Element('div', {
                    'html': responseHTML
                });

                rowContent.inject(row);

                row.fade(1);
                row.inject($('rows'));

                moduleManager.sortableRows.addItems(row);

                //Create the module sortable containers
                row.getElements('.modules').each(function(item) {
                    moduleManager.sortableModules.addLists(item);
                });

                myFx = new Fx.Scroll(parentElem.getParent('.contenido_col')).toElement(rowContent, 'y');

            },
            onFailure : function(xhr) {
                failureRequestHandler(xhr, system.baseUrl + 'admin/modulos/addRow/' + pageId + '/' + id);
            }
        }).send();

    },

    createModule: function(data) {

        var module = new Element('li', {
            'class': 'module',
            'style': 'opacity: 0',
            id: 'mod_' + data.id
        });

        var moveModuleButton = new Element('div', {
            'class': 'move_module'
        });

        var moduleContent = new Element('div', {
            'class': 'content',
            html: data.html
        });

        var removeModuleButton = new Element('div', {
            'class': 'remove_module',
            html: 'X',
            events: {
                click: moduleManager.removeModuleListener
            }
        });

        moveModuleButton.inject(module);
        removeModuleButton.inject(module);
        moduleContent.inject(module);

        moduleManager.sortableModules.addItems(module);

        moduleManager.updateModule(null, moduleContent.getElement('.save_module'));

        return module;

    },

    removeRowListener: function(event) {
        var parent = event.target.getParent(),
            row_index = parent.getParent().getChildren('li.row').indexOf(parent),
            url = system.baseUrl + 'admin/modulos/removeRow/' + parent.getElement('table').get('data-page-id') + '/' + row_index;

        new Request.HTML({
            url : url,
            noCache : true,
            data : {
                csrf_test: csrf_cookie
            },
            onSuccess : function() {
                var fade = new Fx.Tween(parent, {
                    duration: 'short',
                    transition: 'cubic:out',
                    property: 'opacity'
                });

                fade.start(0).chain(function() {
                    parent.destroy();

                    //Reset all rows indexes
                    $$('#rows > li').each(function(row, index){
                        row.getElements('input').each(function(input){
                            var old_name = input.getProperty('name'),
                                new_name = old_name.replace(/fila\[\d+]/, 'fila[' + index + ']');
                            input.setProperty('name', new_name);
                        });
                    });

                });

            },
            onFailure : function(xhr) {
                failureRequestHandler(xhr, url);
            }
        }).send();

    },

    removeModuleListener: function(event) {

        var elem = event.target,
            parent = elem.getParent(),
            row_id = parent.getParent('#rows').getChildren('li.row').indexOf(parent.getParent('.row')),
            column_id = elem.getParent('tr').getChildren('td').indexOf(elem.getParent('td')),
            page_id = $('paginaId').get('value');

        new Request({
            url: system.baseUrl + 'admin/modulos/removeModule/' + page_id + '/' + row_id + '/' + column_id,
            data : {
                moduleId: parent.get('id').replace(/mod_/, ""),
                csrf_test: csrf_cookie
            },
            onRequest: function(){

            },
            onSuccess: function(){
                var fade = new Fx.Tween(parent, {
                    duration: 'short',
                    transition: 'cubic:out',
                    property: 'opacity'
                });

                fade.start(0).chain(function() {
                    parent.destroy();
                });
            },
            onFailure: function(xhr){
                failureRequestHandler(xhr, system.baseUrl + 'admin/modulos/removeModule');
            }
        }).send();



    },

    moduleCreateWindowHandler: function (event, elem)
    {
        var id = 1,
            index = elem.getParent('tr').getChildren('td').indexOf(elem.getParent('td')),
            parent = elem.getParent('tbody').getElement('.module_row').getChildren()[index].getChildren('.modules')[0];

        var request = new Request({
            url: system.baseUrl + 'admin/modulos',
            data : {
                csrf_test: csrf_cookie
            },
            noCache: true,
            onRequest: function(){
                console.log('request URL');
            },
            onSuccess: function(responseText, responseXML){

                new StickyWin({
                    content : StickyWin.ui('Nuevo Módulo', responseText, {
                        width : '700px',
                        buttons : [{
                            text : 'seleccionar',
                            onClick : function() {
                                moduleManager.getModuleType(id, parent, elem);
                            }
                        }, {
                            text : 'cancelar'
                        }]
                    })
                });

                $$('#modulos li').addEvent('click', function(event){

                    $$('#modulos li').removeClass('active');

                    event.target.addClass('active');

                    id = event.target.get('data-id');

                });

            },
            onFailure: function(xhr){
                failureRequestHandler(xhr, system.baseUrl + 'admin/modulos');
            }
        });

        request.send();



    },

    getModuleType : function (id, parent, elem)
    {

        var row_id = parent.getParent('#rows').getChildren('li.row').indexOf(parent.getParent('.row')),
            column_id = elem.getParent('tr').getChildren('td').indexOf(elem.getParent('td')),
            page_id = $('paginaId').get('value');

        switch(Number(id))
        {
            //Publicaciones
            case 1:
                moduleManager.getModuleRequest(system.baseUrl + 'admin/modulos/publicaciones/' + page_id + '/' + row_id + '/' + column_id, parent);
                break;

            //Catalogo Categoria
            case 2:
                moduleManager.getModuleRequest(system.baseUrl + 'admin/modulos/catalogoCategoria/' + page_id + '/' + row_id + '/' + column_id, parent);
                break;

            //Html
            case 3:
                moduleManager.getModuleRequest(system.baseUrl + 'admin/modulos/html/' + page_id + '/' + row_id + '/' + column_id, parent);
                break;

            //Twitter
            case 4:
                moduleManager.getModuleRequest(system.baseUrl + 'admin/modulos/twitter/' + page_id + '/' + row_id + '/' + column_id, parent);
                break;

            //Facebook
            case 5:
                moduleManager.getModuleRequest(system.baseUrl + 'admin/modulos/facebook/' + page_id + '/' + row_id + '/' + column_id, parent);
                break;

            //Hit Counter
            case 6:


                break;

            //Producto
            case 7:


                break;

            //Contenido
            case 8:
                moduleManager.getModuleRequest(system.baseUrl + 'admin/modulos/content/' + page_id + '/' + row_id + '/' + column_id, parent);
                break;

            //Banner
            case 9:
                moduleManager.getModuleRequest(system.baseUrl + 'admin/modulos/banner/' + page_id + '/' + row_id + '/' + column_id, parent);
                break;

            //Productos Destacados
            case 10:
                moduleManager.getModuleRequest(system.baseUrl + 'admin/modulos/catalogoProductosDestacados/' + page_id + '/' + row_id + '/' + column_id, parent);
                break;

            //Menu del Catalogo
            case 11:
                moduleManager.getModuleRequest(system.baseUrl + 'admin/modulos/catalogoMenu/' + page_id + '/' + row_id + '/' + column_id, parent);
                break;

            //Titulo de la pagina
            case 12:
                moduleManager.getModuleRequest(system.baseUrl + 'admin/modulos/titulo/' + page_id + '/' + row_id + '/' + column_id, parent);
                break;

            //Preguntas Frecuentes
            case 13:
                moduleManager.getModuleRequest(system.baseUrl + 'admin/modulos/faq/' + page_id + '/' + row_id + '/' + column_id, parent);
                break;

            //Enlaces
            case 14:
                moduleManager.getModuleRequest(system.baseUrl + 'admin/modulos/enlaces/' + page_id + '/' + row_id + '/' + column_id, parent);
                break;

            //Galeria
            case 15:
                moduleManager.getModuleRequest(system.baseUrl + 'admin/modulos/galeria/' + page_id + '/' + row_id + '/' + column_id, parent);
                break;

            //Mapa
            case 16:
                moduleManager.getModuleRequest(system.baseUrl + 'admin/modulos/mapa/' + page_id + '/' + row_id + '/' + column_id, parent);
                break;

            //Catalogo Filtros
            case 17:
                moduleManager.getModuleRequest(system.baseUrl + 'admin/modulos/catalogoFiltros/' + page_id + '/' + row_id + '/' + column_id, parent);
                break;

            //Menu
            case 18:
                moduleManager.getModuleRequest(system.baseUrl + 'admin/modulos/menu/' + page_id + '/' + row_id + '/' + column_id, parent);
                break;

            //Producto al Azar
            case 19:
                moduleManager.getModuleRequest(system.baseUrl + 'admin/modulos/catalogoProductoAzar/' + page_id + '/' + row_id + '/' + column_id, parent);
                break;

            //Contacto - Formulario
            case 20:
                moduleManager.getModuleRequest(system.baseUrl + 'admin/modulos/contacto/' + page_id + '/' + row_id + '/' + column_id, parent);
                break;

            //Articulo
            case 21:
                moduleManager.getModuleRequest(system.baseUrl + 'admin/modulos/articulo/' + page_id + '/' + row_id + '/' + column_id, parent);
                break;

            //Servicios
            case 22:
                moduleManager.getModuleRequest(system.baseUrl + 'admin/modulos/servicios/' + page_id + '/' + row_id + '/' + column_id, parent);
                break;

            //Breadcrumbs
            case 23:
                moduleManager.getModuleRequest(system.baseUrl + 'admin/modulos/breadcrumbs/' + page_id + '/' + row_id + '/' + column_id, parent);
                break;

            //Direcciones
            case 24:
                moduleManager.getModuleRequest(system.baseUrl + 'admin/modulos/direcciones/' + page_id + '/' + row_id + '/' + column_id, parent);
                break;

            //Publicidad
            case 25:
                moduleManager.getModuleRequest(system.baseUrl + 'admin/modulos/publicidad/' + page_id + '/' + row_id + '/' + column_id, parent);
                break;

            //Producto Destacado al Azar
            case 26:
                moduleManager.getModuleRequest(system.baseUrl + 'admin/modulos/catalogoProductosDestacadosAzar/' + page_id + '/' + row_id + '/' + column_id, parent);
                break;

            //Servicios destacados
            case 27:
                moduleManager.getModuleRequest(system.baseUrl + 'admin/modulos/serviciosDestacados/' + page_id + '/' + row_id + '/' + column_id, parent);
                break;

            //Html
            default:

        }
    },

    getModuleRequest : function (url, parent)
    {
        new Request.JSON({
            url : url,
            data : {
                csrf_test: csrf_cookie
            },
            noCache : true,
            onRequest : function() {

            },
            onSuccess : function(data) {
                var module = moduleManager.createModule(data);

                module.inject(parent);
                module.getElement('h3').removeProperty('id');
                module.set('id', 'mod_' + data.id);

                module.fade(1);

                myFx = new Fx.Scroll(module.getParent('.contenido_col')).toElement(module, 'y');

            },
            onFailure : function(xhr) {
                failureRequestHandler(xhr, url);
            }
        }).send();
    },

    sortModules : function(elem, orig_list){

        var target_list = elem.getParent(),
            orig = {
                row_id : orig_list.getParent('table').get('data-row-id'),
                column_id : orig_list.getParent('tr').getChildren('td').indexOf(orig_list.getParent('td')),
                ids : []
            },
            target = {
                row_id : elem.getParent('table').get('data-row-id'),
                column_id : elem.getParent('tr').getChildren('td').indexOf(elem.getParent('td')),
                ids : []
            },
            page_id = elem.getParent('table').get('data-page-id');

        Array.each(orig_list.getChildren(), function (item) {
            if(!item.hasClass('dragging')) {
                orig.ids.push(item.get('id').replace(/mod_/, "").toInt());
            }
        });

        Array.each(target_list.getChildren(), function (item) {
            if(!item.hasClass('dragging')) {
                target.ids.push(item.get('id').replace(/mod_/, "").toInt());
            }
        });

        new Request({
            url: system.baseUrl + 'admin/modulos/sortModules/' + page_id,
            data : {
                orig: JSON.encode(orig),
                target: JSON.encode(target),
                csrf_test: csrf_cookie
            },
            onRequest: function(){
                $('rows').fade(0.5);
            },
            onSuccess: function(responseText, responseXML){
                $('rows').fade(1);
            },
            onFailure: function(xhr){
                $('rows').fade(1);
                failureRequestHandler(xhr, system.baseUrl + 'admin/modulos/sortModules');
            }
        }).send();

    },

    sortRows : function (from_index, to_index, page_id){

        var rows = new Array();

        /*$$('#rows > li').each(function(row, index){
            //rows.push(row.getElement('table').get('id').replace(/row_/, ""));
            rows.push(row.getParent().getChildren('li.row').indexOf(row));
        });*/

        new Request({
            url: system.baseUrl + 'admin/modulos/sortRows/' + page_id,
            data : {
                from_index: from_index,
                to_index: to_index,
                csrf_test: csrf_cookie
            },
            onRequest: function(){
                $('rows').fade(0.5);
            },
            onSuccess: function(responseText, responseXML){
                $('rows').fade(1);
            },
            onFailure: function(){
                $('rows').fade(1);
                failureRequestHandler(xhr, system.baseUrl + 'admin/modulos/sortRows');
            }
        }).send();

    },

    thisindex: function(elem, parentTag) {
        var children = elem.getParent(parentTag).getChildren();
        var elem_index = null;

        children.each(function(item, index) {
            if (item == elem.getParent()) elem_index = index;
        });

        return elem_index;

    }

}