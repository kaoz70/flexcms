/**
 * @ngdoc service
 * @name app:Language
 *
 * @description
 *
 *
 * */
angular.module('app')
    .factory('Selection', function($window, $rootScope, $mdDialog, BASE_PATH, WindowFactory){

        function Selection(onDeleteCallback) {

            var selection = [];

            // toggle selection for a given item by id
            var toggleSelection = function(node) {

                var idx = selection.indexOf(node);

                // is currently selected
                if (idx !== -1) {
                    selection.splice(idx, 1);
                }

                // is newly selected
                else {
                    selection.push(node);
                }

            };

            this.remove = function (node) {
                var idx = selection.indexOf(node);
                selection.splice(idx, 1);
            };

            this.toggle = function (node) {
                toggleSelection(node);
            };

            this.getLength = function () {
                return selection.length;
            };

            this.getItems = function () {
                return selection;
            };

            /**
             * Show a dialog and if successful input execute the callback
             *
             * @param ev
             */
            this.delete = function (ev) {

                function DialogController($scope, $mdDialog) {

                    if(selection.length > 1) {
                        $scope.message = '¿Est&aacute; seguro de que desea eliminar estos ' + selection.length + ' elementos?';
                    } else {
                        $scope.message = '¿Est&aacute; seguro de que desea eliminar este elemento?';
                    }

                    $scope.cancel = function() {
                        $mdDialog.hide();
                    };

                    $scope.delete = function () {

                        angular.forEach(selection, function (node) {
                            onDeleteCallback(node);
                        });

                        selection = [];
                        $mdDialog.hide();

                    };

                }

                $mdDialog.show({
                    templateUrl: BASE_PATH + 'admin/dialogs/DeleteDialog',
                    parent: angular.element(document.body),
                    targetEvent: ev,
                    controller: DialogController,
                    clickOutsideToClose:true
                });

            };

            /**
             * Handles the click event for a list item
             *
             * @param node
             * @param nodes
             * @param url
             */
            this.onItemClick = function(node, nodes, url, $event) {

                if(node.selected) {
                    return;
                }

                //Remove the selected state from any other item
                angular.forEach(nodes, function(item) {
                    item.selected = false;
                });

                //Set the current node as selected
                node.selected = true;

                //Get the current element parent panel index so that we know if there are child windows
                var currentPanelIndex = $('[app-view-segment]').index($($event.target).closest('[app-view-segment]'));
                WindowFactory.removeFromIndex(currentPanelIndex, url);

            };

            this.addToActiveList = function (item) {
                if(item) {
                    item.selected = true;
                }
            };

        }

        return Selection;

});

