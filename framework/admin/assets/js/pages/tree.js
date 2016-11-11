var yimaPage = function() {
    var initilize = function() {
        // initialize
        function myTreeInit() {
            var callLimit = 200;
            var callCount = 0;
            $('#myTree1').tree({
                dataSource: function(parentData, callback) {
                    // log("Opening branch data: ", parentData);

                    if (callCount >= callLimit) {
                        setTimeout(function() {
                            callback({
                                data: [
                                    {
                                        "name": "Sky and Water I (with custom icon)",
                                        "type": "item",
                                        "attr": {
                                            "id": "item1",
                                            "data-icon": "fa fa-check"
                                        }
                                    },
                                    {
                                        "name": "Waterfall",
                                        "type": "item",
                                        "attr": {
                                            "id": "item2"
                                        }
                                    },
                                    {
                                        "name": "Relativity (with custom icon)",
                                        "type": "item",
                                        "attr": {
                                            "id": "item3",
                                            "data-icon": "fa fa-picture-o"
                                        }
                                    },
                                    {
                                        "name": "Convex and Concave",
                                        "type": "item",
                                        "attr": {
                                            "id": "item4"
                                        }
                                    }
                                ]
                            });
                        }, 400);
                        return;
                    }

                    callCount++;

                    setTimeout(function() {
                        callback({
                            data: [
                                {
                                    "name": "Ascending and Descending",
                                    "type": "folder",
                                    "attr": {
                                        "id": "folder1",
                                        "cssClass": "example-tree-class"
                                    }
                                },
                                {
                                    "name": "Sky and Water I (with custom icon)",
                                    "type": "item",
                                    "attr": {
                                        "id": "item1",
                                        "data-icon": "glyphicon glyphicon-camera text-inverse"
                                    }
                                },
                                {
                                    "name": "Drawing Hands",
                                    "type": "folder",
                                    "attr": {
                                        "id": "folder2"
                                    }
                                },
                                {
                                    "name": "Waterfall",
                                    "type": "item",
                                    "attr": {
                                        "id": "item2"
                                    }
                                },
                                {
                                    "name": "Belvedere",
                                    "type": "folder",
                                    "attr": {
                                        "id": "folder3"
                                    }
                                },
                                {
                                    "name": "Relativity (with custom icon)",
                                    "type": "item",
                                    "attr": {
                                        "id": "item3",
                                        "data-icon": "fa fa-picture-o text-danger"
                                    }
                                },
                                {
                                    "name": "House of Stairs",
                                    "type": "folder",
                                    "attr": {
                                        "id": "folder4"
                                    }
                                },
                                {
                                    "name": "Convex and Concave",
                                    "type": "item",
                                    "attr": {
                                        "id": "item4"
                                    }
                                }
                            ]
                        });
                    }, 400);
                },
                folderSelect: false
            });
        }

        myTreeInit();

        $('#myTree2').tree({
            dataSource: function(parentData, callback) {

                setTimeout(function() {
                    callback({
                        data: [
                            {
                                "name": "Ascending and Descending",
                                "type": "folder",
                                "attr": {
                                    "id": "folder1"
                                }
                            },
                            {
                                "name": "Sky and Water I (with custom icon)",
                                "type": "item",
                                "attr": {
                                    "id": "item1",
                                    "data-icon": "glyphicon glyphicon-file"
                                }
                            },
                            {
                                "name": "Drawing Hands",
                                "type": "folder",
                                "attr": {
                                    "id": "folder2"
                                }
                            },
                            {
                                "name": "Waterfall",
                                "type": "item",
                                "attr": {
                                    "id": "item2"
                                }
                            },
                            {
                                "name": "Belvedere",
                                "type": "folder",
                                "attr": {
                                    "id": "folder3"
                                }
                            },
                            {
                                "name": "Relativity (with custom icon)",
                                "type": "item",
                                "attr": {
                                    "id": "item3",
                                    "data-icon": "glyphicon glyphicon-picture"
                                }
                            },
                            {
                                "name": "House of Stairs",
                                "type": "folder",
                                "attr": {
                                    "id": "folder4"
                                }
                            },
                            {
                                "name": "Convex and Concave",
                                "type": "item",
                                "attr": {
                                    "id": "item4"
                                }
                            }
                        ]
                    });
                }, 400);
            },
            cacheItems: true,
            folderSelect: true,
            multiSelect: true
        });

        // sample method buttons
        $('#btnTreeDestroy').click(function() {
            var $container = $('#myTree1').parent();
            var markup = $('#myTree1').tree('destroy');
            console.log(markup);
            $container.append(markup);
            myTreeInit();
        });

        $('#btnTreeDiscloseVisible').click(function() {
            $('#myTree1').tree('discloseVisible');
        });

        $('#btnTreeDiscloseAll').click(function() {
            $('#myTree1').one('exceededDisclosuresLimit.fu.tree', function() {
                $('#myTree1').data('keep-disclosing', false);
            });
            $('#myTree1').tree('discloseAll');
        });

        $('#btnTreeCloseAll').click(function() {
            $('#myTree1').tree('closeAll');
        });

        // events
        $('#myTree1').on('loaded.fu.tree', function(e) {
            console.log('#myTree1 Loaded');
        });
        $('#myTree1').on('selected.fu.tree', function(event, selected) {
            console.log('Selected Event: ', selected);
            console.log($('#myTree1').tree('selectedItems'));
        });
        $('#myTree1').on('deselected.fu.tree', function(e, selected) {
            log('Deselected Event: ', selected);
        });
        $('#myTree1').on('updated.fu.tree', function(event, selected) {
            console.log('Updated Event: ', selected);
            console.log($('#myTree1').tree('selectedItems'));
        });
        $('#myTree1').on('opened.fu.tree', function(event, parentData) {
            console.log('Opened Event, parent data: ', parentData);
        });
        $('#myTree1').on('closed.fu.tree', function(event, parentData) {
            console.log('Closed Event, parent data: ', parentData);
        });
        $('#myTree1').on('closedAll.fu.tree', function(event, data) {
            console.log('Closed All Event, this many reported closed: ', data.reportedClosed.length);
        });
        $('#myTree1').on('disclosedVisible.fu.tree', function(event, data) {
            console.log('Disclosed Visible, this many folders opened: ', data.reportedOpened.length);
        });
        $('#myTree1').on('exceededDisclosuresLimit.fu.tree', function(event, data) {
            console.log('Disclosed All failsafe exit occurred, this many recursions: ', data.disclosures);
        });
        $('#myTree1').on('disclosedAll.fu.tree', function(event, data) {
            console.log('Disclosed All, this many recursions: ', data.disclosures);
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