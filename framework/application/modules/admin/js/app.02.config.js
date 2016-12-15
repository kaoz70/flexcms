(function() {
    'use strict';

    var app = angular.module('app');

    //Base path used to find templates
    app.constant('BASE_PATH', 'admin/view/show/');

    app.run(function($rootScope, $timeout, $route, $routeSegment) {

        $rootScope.isSidebarOpen = false;
        $rootScope.$routeSegment = $routeSegment;

        $rootScope.primaryColor300 = 'default-primary-300';
        $rootScope.accentColor300 = 'default-accent-300';
        $rootScope.selectedItems = [];

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

    app.config(function($mdThemingProvider) {

        $mdThemingProvider.theme('default')
            .primaryPalette('blue')
            //.secondaryPalette('teal')
            .accentPalette('teal')
            .warnPalette('red')
            .dark();

        console.log($mdThemingProvider);

        $mdThemingProvider.theme('dark-blue').backgroundPalette('blue').dark();
        $mdThemingProvider.theme('dark-green').backgroundPalette('green');

        $mdThemingProvider.theme('docs-dark', 'default')
            .primaryPalette('yellow')
            .dark();

    });

}());