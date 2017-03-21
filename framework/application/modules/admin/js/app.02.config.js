(function() {
    'use strict';

    var app = angular.module('app');

    //Base path used to find templates
    app.constant('BASE_PATH', 'admin/view/module/');
    app.constant('WIDGET_PATH', 'admin/view/widget/');

    app.run(function($rootScope, $timeout, $route, $routeSegment, $templateCache, $http, BASE_PATH) {

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

        //Cache some templates so that the route segment provided doesn't have problems detecting its segment index
        $http.get(BASE_PATH + 'admin/List', { cache: $templateCache });
        $http.get(BASE_PATH + 'admin/List1', { cache: $templateCache });
        $http.get(BASE_PATH + 'admin/List2', { cache: $templateCache });
        $http.get(BASE_PATH + 'admin/ListGrouped', { cache: $templateCache });
        $http.get(BASE_PATH + 'admin/ListGrouped1', { cache: $templateCache });
        $http.get(BASE_PATH + 'admin/ListGrouped2', { cache: $templateCache });

    });

    app.config(function($mdThemingProvider, $locationProvider, $httpProvider) {

        $mdThemingProvider.theme('default')
            .primaryPalette('blue')
            //.secondaryPalette('teal')
            //.accentPalette('blue-grey')
            .accentPalette('pink')
            .warnPalette('red')
            .dark();

        var background = $mdThemingProvider.extendPalette('grey', {
            '50': '243541',
            '100': '0A1117',
            '200': '0D151D',
            '300': '0F1821',
            '400': '121D27',
            '500': '14212D',
            '600': '172532',
            '700': '1A2937',
            '800': '1D2D3C',
            '900': '243541',
            'A100': '31424e',
            'A200': 'ff5252',
            'A400': 'ff1744',
            'A700': 'd50000',
            'contrastDefaultColor': 'light'
        });
        $mdThemingProvider.definePalette('background', background);
        $mdThemingProvider.theme('default')
            .backgroundPalette('background');


        /*

        //console.log($mdThemingProvider);

        $mdThemingProvider.theme('dark-blue').backgroundPalette('blue').dark();
        $mdThemingProvider.theme('dark-green').backgroundPalette('green');*/

        /*$mdThemingProvider.theme('docs-dark', 'default')
            .primaryPalette('yellow')
            .dark();*/

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