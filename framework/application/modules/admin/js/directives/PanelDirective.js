(function() {
    'use strict';

    //Panel Dispose
    angular
        .module('app')
        .directive('panelDispose', panelDispose);

    function panelDispose(WindowFactory, $routeSegment, $routeParams, $window) {
        return {
            restrict: 'E',
            scope: {
                closeCallback: '='
            },
            template: '<md-button class="md-icon-button tools-action" aria-label="Close"><md-icon>close</md-icon></md-button>',
            link: function(scope, el) {
                el.on('click', function() {

                    WindowFactory.remove(scope);

                    scope.closeCallback();

                    setTimeout(function () {

                        var url = "#/",
                            segments = $routeSegment.chain;

                        //Remove the last segment
                        segments.splice(segments.length -1, 1);

                        //Create the segment url
                        angular.forEach(segments, function (item) {

                            url += item.name + "/";

                            if(item.params.dependencies !== undefined) {
                                angular.forEach(item.params.dependencies, function (value) {
                                    url += $routeParams[value] + "/";
                                });
                            }

                        });

                        //Change the route once we have hidden the window
                        $window.location.assign(url);

                    }, 400);

                });
            }
        };
    }

}());