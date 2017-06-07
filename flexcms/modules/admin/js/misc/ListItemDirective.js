/**
 * @ngdoc directive
 * @name App:ListItemDirective
 *
 * @description
 *
 *
 * @restrict A
 * */
angular.module('app')
    .directive('listItemDelete', function (Content, $rootScope, $filter, $mdDialog, BASE_PATH) {
        return {
            restrict: 'E',
            template: '<md-button class="md-icon-button"><md-icon>close</md-icon></md-button>',
            scope: {
                item: '='
            },
            link: function(scope, el, attr) {


            }
        };
});
