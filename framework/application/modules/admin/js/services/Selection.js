/**
 * @ngdoc service
 * @name app:Language
 *
 * @description
 *
 *
 * */
angular.module('app')
    .service('Selection', function($window, $rootScope, $mdDialog, BASE_PATH, $routeSegment, $injector){

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
        this.onItemClick = function(node, nodes, url, $event, $scope) {

            //Remove the selected state from any other item
            angular.forEach(nodes, function(item) {
                item.selected = false;
            });

            console.log($scope);

            //Set the current node as selected
            node.selected = true;

            //Get the current element parent panel index so that we know if there are child windows
            var currentPanelIndex = $('[app-view-segment]').index($($event.target).closest('[app-view-segment]'));
            var timeout = 0;

            //Prevent Circular Dependency Injector error
            var WindowFactory = $injector.get('WindowFactory');
            var delta = $routeSegment.chain.length - currentPanelIndex;

            //Remove any child windows
            /*if(delta > 1) {

                for (var i = 0; i < delta - 1; i++) {
                    WindowFactory.remove($scope);
                    timeout += 400;
                }

            }*/

            //Go to the new URL
            setTimeout(function () {
                $window.location.assign(url);
            }, timeout);

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

