/**
 * @ngdoc service
 * @name app:Language
 *
 * @description
 *
 *
 * */
angular.module('app')
    .service('Selection', function($window, $rootScope){

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

});

