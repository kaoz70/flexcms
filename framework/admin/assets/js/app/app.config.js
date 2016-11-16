(function() {
    'use strict';

    var app = angular.module('app');

    //Base path used to find templates
    app.constant('BASE_PATH', 'admin/view/show/');

    app.run(function($rootScope, $timeout, $route, $routeSegment) {

        $rootScope.isSidebarOpen = false;
        $rootScope.$routeSegment = $routeSegment;

        $rootScope.$on('$stateChangeSuccess', function(event, toState, toParams, fromState, fromParams) {
            $rootScope.hasFullContainer = toState.data.hasFullContainer;

            if (toState.ncyBreadcrumb && toState.ncyBreadcrumb.label) {
                $rootScope.pageTitle = toState.ncyBreadcrumb.label;
            }

        });

        $rootScope.$on('$viewContentLoaded',
            function(event, toState, toParams, fromState, fromParams) {
                //Init SlimScroll
                /*$timeout(function() {
                    $('.content-body, .panel-body').slimscroll({
                        touchScrollStep: yima.touchScrollSpeed,
                        height: $(window).height() - 125,
                        width: '100%',
                        alwaysVisible: true,
                        position: 'right',
                        size: '5px',
                        color: yima.primary
                    });
                }, 500);*/

            });

    });

    /*app.config(function($breadcrumbProvider) {
        $breadcrumbProvider.setOptions({
            templateUrl: 'framework/admin/templates/breadcrumb.html',
            includeAbstract: true,
        });
    });*/

}());