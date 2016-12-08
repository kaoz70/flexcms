/**
 * @ngdoc service
 * @name app:Language
 *
 * @description
 *
 *
 * */
angular.module('app')
    .service('Selection', function($window, $rootScope, $mdDialog, BASE_PATH){

        var selection,
            scope,
            activeItems = [];

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

        this.onItemClick = function(node) {

            //Remove the selected state from any other item
            angular.forEach($rootScope.records, function(item) {
                item.selected = false;
            });

            node.selected = true;

            $window.location.assign('#/' + scope.section + '/edit/' + node.id);

        };

        this.addToActiveList = function (item) {
            item.selected = true;
            activeItems.push(item);
        };

        this.removeFromActiveList = function () {

            var item = activeItems.splice(-1,1)[0];

            if(item) {
                item.selected = false;
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

