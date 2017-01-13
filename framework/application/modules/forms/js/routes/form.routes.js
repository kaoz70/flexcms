(function() {
    'use strict';

    angular
        .module("app")
        .config(function($routeSegmentProvider, BASE_PATH) {

            $routeSegmentProvider.options.autoLoadTemplates = true;

            $routeSegmentProvider
                .when('/forms', 'forms')
                .when('/forms/edit/:id', 'forms.edit')
                .when('/forms/edit/:id/field/edit/:field_id', 'forms.edit.edit')
                .when('/forms/create', 'forms.create')
                .when('/forms/create/field/edit/:field_id', 'forms.create.edit')
                .segment('forms', {
                    'default': true,
                    templateUrl: BASE_PATH + 'admin/List',
                    controller: 'FormCtrl',
                    resolve: {
                        response: function(Form, $q) {

                            var deferred = $q.defer();
                            Form.query(
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
                        templateUrl: BASE_PATH + 'forms/FormDetail',
                        controller: 'FormEditCtrl',
                        dependencies: ['id'],
                        resolve: {
                            languages: function(Language, $routeParams, $q) {

                                var deferred = $q.defer();
                                Language.query(
                                    function(successData) {
                                        deferred.resolve(successData);
                                    }, function(errorData) {
                                        deferred.reject(errorData);
                                    });
                                return deferred.promise;

                            },
                            form: function(Form, $routeParams, $q) {

                                var deferred = $q.defer();
                                Form.get({
                                        id: $routeParams.id
                                    },
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
                            templateUrl: BASE_PATH + 'forms/FieldDetail',
                            controller: 'FieldEditCtrl',
                            dependencies: ['id', 'field_id'],
                            resolve: {
                                types: function(Field, $q) {

                                    var deferred = $q.defer();

                                    Field.getTypes().then(function(successData) {
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
                        .up()
                    .segment('create', {
                        templateUrl: BASE_PATH + 'forms/FormDetail',
                        controller: 'FormCreateCtrl',
                        resolveFailed: {
                            controller: 'ErrorCtrl'
                        }
                    })
                    .within()
                        .segment('edit', {
                            templateUrl: BASE_PATH + 'forms/FieldDetail',
                            controller: 'FieldEditCtrl',
                            dependencies: ['field_id'],
                            resolve: {
                                types: function(Field, $q) {

                                    var deferred = $q.defer();

                                    Field.getTypes().then(function(successData) {
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
            ;

        });

}());