/*global Sortables,$,$$, system, Request, Fx, StickyWin, failureRequestHandler */

var moduleManager = {

    sortIndex: undefined,
    sortableRows: new Sortables("#rows", {
        clone: true,
        constrain: true,
        revert: false,
        opacity: 0,
        handle: ".move_row",
        onStart: function(elem, clone) {
            "use strict";
            clone.setStyle("z-index", 10000);
            clone.addClass("dragging");
            moduleManager.sortIndex = elem.getParent().getChildren("li.row").indexOf(elem) - 1;
        },
        onComplete: function(elem) {
            "use strict";
            var targetIndex = elem.getParent().getChildren("li.row").indexOf(elem);
            moduleManager.sortRows(moduleManager.sortIndex, targetIndex, elem.getElement("table").get("data-page-id"));
        }
    }),

    sortModulesOriginList: undefined,
    sortableModules: new Sortables(".modules", {
        clone: true,
        revert: true,
        opacity: 0,
        handle: ".move_module",
        onStart: function(elem, clone) {
            "use strict";
            moduleManager.sortModulesOriginList = elem.getParent();
            clone.setStyle("z-index", 10000);
            clone.addClass("dragging");
            $$(".modules").addClass("drop");
        },
        onComplete: function(elem) {
            "use strict";
            $$(".modules").removeClass("drop");
            moduleManager.sortModules(elem, moduleManager.sortModulesOriginList);
            detectInputWidth(elem.getParent());
        }
    }),

    init: function() {
        "use strict";

        $("module_manager").addEvents({
            "click:relay(#add_row .rows)": moduleManager.addRowTypeListener,
            "click:relay(.add_module)": moduleManager.moduleCreateWindowHandler,
            "click:relay(.remove_row)": moduleManager.removeRowListener,
            "click:relay(.remove_module)": moduleManager.removeModuleListener,
            "change:relay(.module select)": moduleManager.moduleInfoChangeListener,
            "change:relay(.module .banner_select)": moduleManager.showBannerViews,
            "change:relay(.module .banner_view_select)": moduleManager.selectBannerViews,
            "input:relay(.module input, .module textarea)": moduleManager.moduleInfoChangeListener,
            "click:relay(.module input[type='checkbox'])": moduleManager.moduleInfoChangeListener,
            "click:relay(.save_module)": moduleManager.updateModule,
            "click:relay(#copiar_estructura)": moduleManager.copyStructureClickListener,
            "click:relay(.content_column .click)": moduleManager.showHideSourceOrdering
        });

        $$("#rows > li").each(function(item) {
            moduleManager.sortableRows.addItems(item);
        });

        $$(".modules").each(function(item) {
            moduleManager.sortableModules.addLists(item);
        });

        $$(".save_module").dissolve();

    },

    showHideSourceOrdering: function(event, elem) {
        if(elem.hasClass("hidden")) {
            elem.addClass("shown");
            elem.removeClass("hidden");
            elem.getNext().reveal();
        } else {
            elem.addClass("hidden");
            elem.removeClass("shown");
            elem.getNext().dissolve();
        }
    },

    showBannerViews: function(event, elem) {
        var banner = elem.getElement(":selected").get("data-type");

        elem.getParent(".content").getElements(".banner_view_select").each(function(item) {
            if (banner === item.getProperty("data-type")) {
                item.setStyle("display", "inline");
                elem.getParent(".content").getElement(".banner_view_hidden").setProperty("value", item.getElement(":selected").get("value"));
            } else {
                item.setStyle("display", "none");
            }
        });

        elem.getParent(".module").getElement(".save_module").reveal();

    },

    selectBannerViews: function (event, elem) {
        elem.getParent(".content").getElement(".banner_view_hidden").setProperty("value", elem.getElement(":selected").get("value"));
        elem.getParent(".module").getElement(".save_module").reveal();
    },

    moduleInfoChangeListener: function (event, elem) {

        "use strict";

        var module = elem.getParent(".module"),
            contentModule = elem.getParent(".mod_content"),
            parent = module.getElement("#currentContent"),
            clone;

        //If its the content module
        if (contentModule != undefined && elem.getElement(":selected") != undefined) {

            switch (elem.getElement(":selected").get("value").toInt()) {
                case 7: //Redirect
                    parent.empty();
                    clone = module.getElement("#h_pagina").clone();
                    clone.getElement("select").set("name", "paramentro2");
                    clone.inject(parent);
                    break;

                case 5: //Publicacion
                    parent.empty();
                    clone = module.getElement("#h_listado").clone();
                    clone.getElement("input[type='checkbox']").set("name", "paramentro2");
                    clone.inject(parent);
                    break;

                case 4: //Catalogo
                case 6: //Galeria
                    parent.empty();
                    clone = module.getElement("#h_categorias").clone();
                    clone.getElement("input[type='checkbox']").set("name", "paramentro2");
                    clone.inject(parent);
                    break;

                default: //Hidden - Ninguno

                    if (elem.get("name") === "parametro2") {
                        if(elem.getElement(":selected").get("value").toInt() === 0) {
                            elem.getParent().getElement("label[for='parametro3']").setStyle("display", "inline");
                            elem.getParent().getElement("input[name='parametro3']").setStyle("display", "inline");
                        } else {
                            elem.getParent().getElement("label[for='parametro3']").setStyle("display", "none");
                            elem.getParent().getElement("input[name='parametro3']").setStyle("display", "none");
                        }
                    } else if (elem.get("name") === "parametro1") {
                        parent.empty();
                        clone = module.getElement("#h_default").clone();
                        clone.getElement("input[type='hidden']").set("name", "parametro2");
                        clone.inject(parent);
                    }
                    break;
            }

            module.getElement(".save_module").reveal();

        }


        elem.getParent(".module").getElement(".save_module").reveal();
    },

    copyStructureClickListener: function (event, clickedElem) {

        var to_page = clickedElem.getParent().getElement("select :selected").get("value");

        if(!to_page.toInt()) {

            new StickyWin({
                content : StickyWin.ui("Error", "Escoja la página de donde quiere copiar la estructura", {
                    width : "500px",
                    buttons : [{
                        text : "cerrar"
                    }]
                })
            });

            return;
        }

        new Request.JSON({
            url : system.baseUrl + "admin/structure/copy",
            noCache : true,
            data : {
                from_page: clickedElem.getParent().getElement("select :selected").get("value"),
                to_page: clickedElem.get("data-pagina-id"),
                csrf_test: csrf_cookie
            },
            onSuccess : function(responseJSON) {

                if (responseJSON.code === 1) {
                    clickBotonCancelar(event, clickedElem);
                } else {

                    new StickyWin({
                        content : StickyWin.ui("Error", responseJSON.message, {
                            width : "500px",
                            buttons : [{
                                text : "cerrar"
                            }]
                        })
                    });

                }

            },
            onFailure : function(xhr) {
                failureRequestHandler(xhr, system.baseUrl + "admin/structure/copy");
            }
        }).send();

    },

    updateModule : function (event, clickedElem) {

        var form = clickedElem.getParent("form"),
            moduleId = clickedElem.get("data-id"),
            url = system.baseUrl + "admin/widget/update/" + moduleId;

        new Request({
            url: url,
            data : form,
            onRequest: function(){

            },
            onSuccess: function(){
                clickedElem.dissolve();
            },
            onFailure: function(xhr){
                failureRequestHandler(xhr, url);
            }
        }).send();

    },

    addRowTypeListener: function(event, clickedElem){
        var id =clickedElem.get("id").replace(/row_/, "");
        moduleManager.createRowElements(id);

    },

    createRowElements: function(id) {

        var parentElem = $("rows");
        var pageId = $("id").get("value");

        new Request.HTML({
            url : system.baseUrl + "admin/structure/addRow/" + pageId + "/" + id,
            noCache : true,
            data : {
                csrf_test: csrf_cookie
            },
            onRequest : function() {

            },
            onSuccess : function(responseTree, responseElements, responseHTML) {

                var row = new Element("li", {
                    "class": "row",
                    html: " <div class='move_row'></div>",
                    "style": "opacity: 0"
                });

                var proxy = new Element("div").adopt(this.response.elements);
                var id = proxy.getElement("table").getProperty("id");

                id = id.replace("row_", "");

                var row_controls = new Element("div", {
                    class: "row_controls",
                    html: '<div class="input check">' +
                        '<input id="fila_' + id + '_expanded" type="checkbox" name="fila[' + id + '][expandida]" value="1"><label for="fila_' + id + '_expanded">Expandida:</label>' +
                        '</div><div class="input small">' +
                        '<label>Clase:</label>' +
                        '<input type="text" name="fila[' + id + '][class]" value=""></div>'
                });

                var removeRowButton = new Element("div", {
                    "class": "remove_row",
                    html: "X"
                });

                row_controls.inject(row);

                removeRowButton.inject(row);

                var rowContent = new Element("div", {
                    "html": responseHTML
                });

                rowContent.inject(row);

                row.fade(1);
                row.inject($("rows"));

                moduleManager.sortableRows.addItems(row);

                //Create the module sortable containers
                row.getElements(".modules").each(function(item) {
                    moduleManager.sortableModules.addLists(item);
                });

                myFx = new Fx.Scroll(parentElem.getParent(".contenido_col")).toElement(rowContent, "y");

            },
            onFailure : function(xhr) {
                failureRequestHandler(xhr, system.baseUrl + "admin/modulos/addRow/" + pageId + "/" + id);
            }
        }).send();

    },

    createModule: function(data) {

        var module = new Element("li", {
            "class": "module",
            "style": "opacity: 0",
            id: "mod_" + data.id
        });

        var moveModuleButton = new Element("div", {
            "class": "move_module"
        });

        var moduleContent = new Element("div", {
            "class": "content",
            html: data.html
        });

        var removeModuleButton = new Element("div", {
            "class": "remove_module",
            html: "X",
            events: {
                click: moduleManager.removeModuleListener
            }
        });

        moveModuleButton.inject(module);
        removeModuleButton.inject(module);
        moduleContent.inject(module);

        moduleManager.sortableModules.addItems(module);

        moduleManager.updateModule(null, moduleContent.getElement(".save_module"));

        return module;

    },

    removeRowListener: function(event) {
        var parent = event.target.getParent(),
            row_index = parent.getParent().getChildren("li.row").indexOf(parent),
            url = system.baseUrl + "admin/structure/removeRow/" + parent.getElement("table").get("data-page-id") + "/" + row_index;

        new Request.HTML({
            url : url,
            noCache : true,
            data : {
                csrf_test: csrf_cookie
            },
            onSuccess : function() {
                var fade = new Fx.Tween(parent, {
                    duration: "short",
                    transition: "cubic:out",
                    property: "opacity"
                });

                fade.start(0).chain(function() {
                    parent.destroy();

                    //Reset all rows indexes
                    $$("#rows > li").each(function(row, index){
                        row.getElements("input").each(function(input){
                            var old_name = input.getProperty("name"),
                                new_name = old_name.replace(/fila\[\d+]/, "fila[" + index + "]");
                            input.setProperty("name", new_name);
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
        "use strict";

        var elem = event.target,
            parent = elem.getParent(),
            rowId = parent.getParent("#rows").getChildren("li.row").indexOf(parent.getParent(".row")),
            columnId = elem.getParent("tr").getChildren("td").indexOf(elem.getParent("td")),
            pageId = $("id").get("value"),
            moduleId = elem.get("data-id"),
            fade = new Fx.Tween(parent, {
                duration: "short",
                transition: "cubic:out",
                property: "opacity"
            });

        new Request({
            url: system.baseUrl + "admin/widget/delete/" + pageId + "/" + rowId + "/" + columnId + "/" + moduleId,
            onSuccess: function() {
                fade.start(0).chain(function() {
                    parent.destroy();
                });
            },
            onFailure: function(xhr) {
                failureRequestHandler(xhr, system.baseUrl + "admin/modulos/removeModule");
            }
        }).send();

    },

    moduleCreateWindowHandler: function(event, elem) {
        var id = "Content",
            index = elem.getParent("tr").getChildren("td").indexOf(elem.getParent("td")),
            parent = elem.getParent("tbody").getElement(".module_row").getChildren()[index].getChildren(".modules")[0];

        var request = new Request({
            url: system.baseUrl + "admin/widget",
            data : {
                csrf_test: csrf_cookie
            },
            noCache: true,
            onSuccess: function(responseText, responseXML){

                new StickyWin({
                    content : StickyWin.ui("Nuevo Widget", responseText, {
                        width : "700px",
                        buttons : [{
                            text : "seleccionar",
                            onClick : function() {
                                moduleManager.getModuleType(id, parent, elem);
                            }
                        }, {
                            text : "cancelar"
                        }]
                    })
                });

                $$("#widgets > div").addEvent("click", function(ev) {

                    $$("#widgets > div").removeClass("active");

                    ev.target.addClass("active");

                    id = ev.target.get("data-class");

                });

            },
            onFailure: function(xhr){
                failureRequestHandler(xhr, system.baseUrl + "admin/modulos");
            }
        });

        request.send();



    },

    getModuleType : function(type, parent, elem) {
        "use strict";

        var row_id = parent.getParent("#rows").getChildren("li.row").indexOf(parent.getParent(".row")),
            column_id = elem.getParent("tr").getChildren("td").indexOf(elem.getParent("td")),
            page_id = $("id").get("value");

        moduleManager.getModuleRequest(system.baseUrl + "admin/widget/add/" + type + "/" + page_id + "/" + row_id + "/" + column_id, parent);

    },

    getModuleRequest : function (url, parent) {
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
                module.getElement("h3").removeProperty("id");
                module.set("id", "mod_" + data.id);

                module.fade(1);

                myFx = new Fx.Scroll(module.getParent(".contenido_col")).toElement(module, "y");

            },
            onFailure : function(xhr) {
                failureRequestHandler(xhr, url);
            }
        }).send();
    },

    sortModules : function(elem, orig_list){

        var target_list = elem.getParent(),
            orig = {
                row_id : orig_list.getParent("table").get("data-row-id"),
                column_id : orig_list.getParent("tr").getChildren("td").indexOf(orig_list.getParent("td")),
                ids : []
            },
            target = {
                row_id : elem.getParent("table").get("data-row-id"),
                column_id : elem.getParent("tr").getChildren("td").indexOf(elem.getParent("td")),
                ids : []
            },
            page_id = elem.getParent("table").get("data-page-id");

        Array.each(orig_list.getChildren(), function (item) {
            if(!item.hasClass("dragging")) {
                orig.ids.push(item.get("id").replace(/mod_/, "").toInt());
            }
        });

        Array.each(target_list.getChildren(), function (item) {
            if(!item.hasClass("dragging")) {
                target.ids.push(item.get("id").replace(/mod_/, "").toInt());
            }
        });

        new Request({
            url: system.baseUrl + "admin/widget/sort/" + page_id,
            data : {
                orig: JSON.encode(orig),
                target: JSON.encode(target),
                csrf_test: csrf_cookie
            },
            onRequest: function(){
                $("rows").fade(0.5);
            },
            onSuccess: function(responseText, responseXML){
                $("rows").fade(1);
            },
            onFailure: function(xhr){
                $("rows").fade(1);
                failureRequestHandler(xhr, system.baseUrl + "admin/modulos/sortModules");
            }
        }).send();

    },

    sortRows : function (from_index, to_index, page_id){

        var rows = new Array();

        /*$$("#rows > li").each(function(row, index){
            //rows.push(row.getElement("table").get("id").replace(/row_/, ""));
            rows.push(row.getParent().getChildren("li.row").indexOf(row));
        });*/

        new Request({
            url: system.baseUrl + "admin/structure/sortRows/" + page_id,
            data : {
                from_index: from_index,
                to_index: to_index,
                csrf_test: csrf_cookie
            },
            onRequest: function(){
                $("rows").fade(0.5);
            },
            onSuccess: function(responseText, responseXML){
                $("rows").fade(1);
            },
            onFailure: function(){
                $("rows").fade(1);
                failureRequestHandler(xhr, system.baseUrl + "admin/structure/sortRows");
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