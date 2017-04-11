(function () {

    angular.module('app')

        // Base path used to find templates
        .constant('BASE_PATH', 'admin/view/module/')
        .constant('WIDGET_PATH', 'admin/view/widget/')

        .run(($rootScope, $timeout, $route, $routeSegment, $templateCache, $http, BASE_PATH) => {
            $rootScope.isSidebarOpen = false;
            $rootScope.$routeSegment = $routeSegment;

            $rootScope.primaryColor300 = 'default-primary-300';
            $rootScope.accentColor300 = 'default-accent-300';
            $rootScope.selectedItems = [];

            // Cache some templates so that the route segment provided doesn't
            // have problems detecting its segment index
            $http.get(`${BASE_PATH}admin/List`, { cache: $templateCache });
            $http.get(`${BASE_PATH}admin/List1`, { cache: $templateCache });
            $http.get(`${BASE_PATH}admin/List2`, { cache: $templateCache });
            $http.get(`${BASE_PATH}admin/ListGrouped`, { cache: $templateCache });
            $http.get(`${BASE_PATH}admin/ListGrouped1`, { cache: $templateCache });
            $http.get(`${BASE_PATH}admin/ListGrouped2`, { cache: $templateCache });
        })

        .config(($mdThemingProvider) => {
            const background = $mdThemingProvider.extendPalette('grey', {
                50: '243541',
                100: '0A1117',
                200: '0D151D',
                300: '0F1821',
                400: '121D27',
                500: '14212D',
                600: '172532',
                700: '1A2937',
                800: '31404e', // Card background
                900: '243541',
                A100: '111A22',
                A200: '18242F',
                A400: '243541', // Panel background
                A700: '2D3B49',
                contrastDefaultColor: 'light',
            });

            const warnRed = $mdThemingProvider.extendPalette('red', {
                500: 'CD4237',
            });

            $mdThemingProvider.definePalette('background', background);
            $mdThemingProvider.definePalette('warnRed', warnRed);

            $mdThemingProvider.theme('default')
                .primaryPalette('blue')
                .accentPalette('cyan')
                .backgroundPalette('background')
                .warnPalette('warnRed')
                .dark();
        })
        .config(($locationProvider, $httpProvider) => {
            $locationProvider.hashPrefix('');

            $httpProvider.interceptors.push(($q, Notification, Response, $injector) => {
                return {

                    // called if HTTP CODE = 2xx
                    response(response) {
                        try {
                            Response.validate(response);
                            Notification.show(response.data.type, response.data.message);
                        } catch (err) {
                            const Dialog = $injector.get('Dialog');
                            Dialog.invalidResponseError(err, response);
                        }

                        return response;
                    },

                    // called if HTTP CODE != 2xx
                    responseError(rejection) {
                        const Dialog = $injector.get('Dialog');
                        Dialog.responseError(rejection);

                        return $q.reject(rejection);
                    },
                };
            });
        });
}());
