(function() {
    'use strict';

    //Panel Dispose
    angular
        .module('app')
        .directive('panelDispose', panelDispose);

    function panelDispose(WindowFactory) {
        return {
            restrict: 'E',
            scope: {
                closeCallback: '='
            },
            template: '<md-button class="md-icon-button tools-action" aria-label="Close"><md-icon>close</md-icon></md-button>',
            link: function(scope, el) {
                el.on('click', function() {

                    if(scope.closeCallback !== undefined) {
                        scope.closeCallback();
                    }

                    WindowFactory.back(scope.$parent);

                });
            }
        };
    }

}());