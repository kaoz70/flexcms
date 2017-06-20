(function() {
    angular
        .module('app')
        .config(function ($routeSegmentProvider, BASE_PATH) {
            $routeSegmentProvider.options.autoLoadTemplates = true;

            $routeSegmentProvider
                .when('/config', 'config')
                .segment('config', {
                    default: true,
                    templateUrl: `${BASE_PATH}admin/ConfigGeneral`,
                    controller: 'ConfigController',
                    controllerAs: 'vm',
                    resolve: {
                        config(Config, ResourceResponse) {
                            return ResourceResponse.get(Config, { section: 'general' });
                        },
                    },
                    untilResolved: {
                        templateUrl: `${BASE_PATH}admin/Loading`,
                    },
                    resolveFailed: {
                        controller: 'ErrorCtrl',
                    },
                });
        });
}());
