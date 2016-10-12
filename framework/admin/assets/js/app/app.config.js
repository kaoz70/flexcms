(function() {
    'use strict';

    var app = angular.module('app');
    app.run(function($rootScope, $timeout) {

        $rootScope.isSidebarOpen = false;

        $rootScope.$on('$stateChangeSuccess', function(event, toState, toParams, fromState, fromParams) {
            $rootScope.hasFullContainer = toState.data.hasFullContainer;

            if (toState.ncyBreadcrumb && toState.ncyBreadcrumb.label) {
                $rootScope.pageTitle = toState.ncyBreadcrumb.label;
            }


        });

        $rootScope.$on('$viewContentLoaded',
            function(event, toState, toParams, fromState, fromParams) {
                //Init SlimScroll
                $timeout(function() {
                    $('.content-body').slimscroll({
                        touchScrollStep: yima.touchScrollSpeed,
                        height: $(window).height() - 125,
                        width: '100%',
                        position: 'right',
                        size: '3px',
                        color: yima.primary
                    });
                }, 500);

            });

    });

    app.config(function($breadcrumbProvider) {
        $breadcrumbProvider.setOptions({
            templateUrl: 'framework/admin/templates/breadcrumb.html',
            includeAbstract: true,
        });
    });
}());