(function() {
    'use strict';

    angular
        .module("app")
        .config(function($routeSegmentProvider, BASE_PATH) {

            $routeSegmentProvider.options.autoLoadTemplates = true;

            $routeSegmentProvider
                .when('/config', 'config')
                .segment('config', {
                    'default': true,
                    templateUrl: BASE_PATH + 'admin/ConfigGeneral',
                    controller: 'ConfigCtrl',
                    resolve: {
                        config: function(Config, $q) {

                            var deferred = $q.defer();
                            Config.get({section: 'general'}, function(successData) {
                                deferred.resolve(successData);
                            }, function(errorData) {
                                deferred.reject(errorData);
                            });

                            return deferred.promise;

                        }
                    },
                    untilResolved: {
                        templateUrl: BASE_PATH + 'admin/Loading'
                    },
                    resolveFailed: {
                        controller: 'ErrorCtrl'
                    }
                });

        });

}());