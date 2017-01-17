/**
 * @ngdoc service
 * @name app:Language
 *
 * @description
 *
 *
 * */
angular.module('app')
    .service('Selection', function($window, $rootScope, $mdDialog, BASE_PATH, WindowFactory){

        var selection,
            scope;

        this.init = function($scope) {
            selection = [];
            scope = $scope;
            return selection;
        };

        // toggle selection for a given item by id
        this.toggleSelection = function(id) {

            var idx = selection.indexOf(id);

            // is currently selected
            if (idx > -1) {
                selection.splice(idx, 1);
            }

            // is newly selected
            else {
                selection.push(id);
            }
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

        /**
         * Show a dialog and if successful input execute the callback
         *
         * @param ev
         * @param callback
         */
        this.delete = function (ev, callback) {

            function DialogController($scope, $mdDialog) {

                if(selection.length > 1) {
                    $scope.message = '¿Est&aacute; seguro de que desea eliminar estos ' + selection.length + ' elementos?';
                } else {
                    $scope.message = '¿Est&aacute; seguro de que desea eliminar este elemento?';
                }

                $scope.cancel = function() {
                    $mdDialog.hide();
                };

                $scope.delete = callback;

            }

            $mdDialog.show({
                templateUrl: BASE_PATH + 'admin/dialogs/DeleteDialog',
                parent: angular.element(document.body),
                targetEvent: ev,
                controller: DialogController,
                clickOutsideToClose:true
            });

        }

});

