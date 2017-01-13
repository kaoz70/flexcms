(function() {
    'use strict';

    var app = angular.module('app');

    //Base path used to find templates
    app.constant('BASE_PATH', 'admin/view/module/');
    app.constant('WIDGET_PATH', 'admin/view/widget/');

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

    app.config(function($mdThemingProvider, $locationProvider, $httpProvider) {

        $mdThemingProvider.theme('default')
            .primaryPalette('blue')
            //.secondaryPalette('teal')
            .accentPalette('teal')
            .warnPalette('red')
            .dark();

        //console.log($mdThemingProvider);

        $mdThemingProvider.theme('dark-blue').backgroundPalette('blue').dark();
        $mdThemingProvider.theme('dark-green').backgroundPalette('green');

        $mdThemingProvider.theme('docs-dark', 'default')
            .primaryPalette('yellow')
            .dark();

        $locationProvider.hashPrefix('');

        $httpProvider.interceptors.push(function($q, Notification, Response, $injector, BASE_PATH) {
            return {

                // called if HTTP CODE = 2xx
                'response': function(response) {

                    try {
                        Response.validate(response);
                        Notification.show(response.data.type, response.data.message);
                    } catch (err) {

                        var $mdDialog = $injector.get('$mdDialog');

                        $mdDialog.show({
                            templateUrl: BASE_PATH + 'admin/dialogs/ErrorDialog',
                            parent: angular.element(document.body),
                            controller: function ($scope) {
                                $scope.message = err;
                                $scope.detail = response.data.data.message;
                                $scope.showNotificationButton = response.data.notify;
                                $scope.close = function () {
                                    $mdDialog.hide();
                                };
                                $scope.notify = function () {
                                    Response.notify(response.data);
                                };
                            },
                            clickOutsideToClose:true
                        });

                    }

                    return response;

                },

                // called if HTTP CODE != 2xx
                'responseError': function(rejection) {

                    console.log(rejection);

                    var $mdDialog = $injector.get('$mdDialog');

                    $mdDialog.show({
                        templateUrl: BASE_PATH + 'admin/dialogs/ErrorDialog',
                        parent: angular.element(document.body),
                        controller: function ($scope) {
                            $scope.message = rejection.statusText;
                            $scope.status = rejection.status;
                            $scope.showNotificationButton = true;
                            $scope.close = function () {
                                $mdDialog.hide();
                            };
                            $scope.notify = function () {
                                Response.notify(rejection);
                            };
                        },
                        clickOutsideToClose:true
                    });

                    return $q.reject(rejection);

                }
            };
        });

    });

}());