(function() {
    'use strict';

    angular
        .module("app")
        .config(function($routeSegmentProvider, BASE_PATH) {

            $routeSegmentProvider.options.autoLoadTemplates = true;

            $routeSegmentProvider
                .when('/language', 'language')
                .when('/language/edit/:id', 'language.edit')
                .when('/language/create', 'language.create')
                .segment('language', {
                    'default': true,
                    templateUrl: BASE_PATH + 'admin/List1',
                    controller: 'LanguageCtrl',
                    resolve: {
                        languages: function(Language, $q) {

                            var deferred = $q.defer();
                            Language.query(
                                function(successData) {
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
                })
                .within()
                    .segment('edit', {
                        templateUrl: BASE_PATH + 'admin/LanguageDetail',
                        controller: 'LanguageEditCtrl',
                        dependencies: ['id'],
                        resolve: {
                            language: function(Language, $q, $routeParams) {

                                var deferred = $q.defer();
                                Language.get({id: $routeParams.id},
                                    function(successData) {
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
                    })
                    .segment('create', {
                        templateUrl: BASE_PATH + 'admin/LanguageDetail',
                        controller: 'LanguageCreateCtrl'
                    })
            ;

        });

}());