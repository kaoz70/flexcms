(function() {
    'use strict';

    angular
        .module("app")
        .config(function($routeSegmentProvider, BASE_PATH) {

            $routeSegmentProvider.options.autoLoadTemplates = true;

            $routeSegmentProvider
                .when('/auth', 'auth')
                .when('/user/edit/:id', 'language.edit')
                .when('/user/create', 'language.create')
                .segment('auth', {
                    'default': true,
                    templateUrl: BASE_PATH + 'admin/ListGrouped1',
                    controller: 'AuthCtrl',
                    resolve: {
                        users: function(User, ResourceResponse) {
                            return ResourceResponse.query(User);
                        }
                    },
                    untilResolved: {
                        templateUrl: BASE_PATH + 'admin/Loading'
                    },
                    resolveFailed: {
                        controller: 'ErrorCtrl'
                    }
                })
                .segment('user', {

                })
                .within()
                    .segment('edit', {
                        templateUrl: BASE_PATH + 'auth/UserDetail',
                        controller: 'LanguageEditCtrl',
                        dependencies: ['id'],
                        resolve: {
                            user: function(Language, ResourceResponse, $routeParams) {
                                return ResourceResponse.get(Language, {id: $routeParams.id});
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
                        templateUrl: BASE_PATH + 'auth/UserDetail',
                        controller: 'LanguageCreateCtrl'
                    })
            ;

        });

}());