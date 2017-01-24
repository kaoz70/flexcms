(function() {
    'use strict';

    angular
        .module("app")
        .config(function($routeSegmentProvider, BASE_PATH) {

            $routeSegmentProvider.options.autoLoadTemplates = true;

            $routeSegmentProvider
                .when('/page', 'page_index')
                .when('/page/:page_id', 'page')
                .when('/page/:page_id/content/edit/:id', 'page.content')
                .when('/page/:page_id/content/create', 'page.create')
                .when('/page/:page_id/config/:widget_id', 'page.config')
                .when('/page/:page_id/images', 'page.images')
                .when('/page/:page_id/images/create', 'page.images.create')
                .when('/page/:page_id/images/edit/:image_id', 'page.images.edit')
                .segment('page_index', {
                    'default': true,
                    controller: 'PageIndexCtrl'
                })
                .segment('page', {
                    templateUrl: BASE_PATH + 'admin/List1',
                    controller: 'PageCtrl',
                    resolve: {
                        page: function(Page, $routeParams, $q) {

                            var deferred = $q.defer();
                            Page.get({id: $routeParams.page_id},
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
                    },
                    dependencies: ['page_id']
                })
                .within()
                    .segment('content', {
                        templateUrl: BASE_PATH + 'content/Detail',
                        controller: 'ContentEditCtrl',
                        dependencies: ['page_id', 'id'],
                        resolve: {
                            content: function(Content, $routeParams, $q) {

                                var deferred = $q.defer();
                                Content.get({id: $routeParams.id},
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
                        templateUrl: BASE_PATH + 'content/Detail',
                        controller: 'ContentCreateCtrl',
                        dependencies: ['page_id', 'id'],
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
                    .segment('config', {
                        templateUrl: BASE_PATH + 'content/Config',
                        controller: 'PageConfigCtrl',
                        dependencies: ['page_id', 'widget_id'],
                        resolve: {
                            config: function(ContentConfig, $q, $routeParams) {

                                var deferred = $q.defer();
                                ContentConfig.get({id: $routeParams.widget_id}, function(successData) {
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
                    .segment('images', {
                        templateUrl: BASE_PATH + 'admin/List2',
                        controller: 'ImageConfigCtrl',
                        resolve: {
                            images: function(ImageConfig, $q, $routeParams) {

                                var deferred = $q.defer();
                                ImageConfig.query({page_id: $routeParams.page_id}, function(successData) {
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
                        .segment('create', {
                            templateUrl: BASE_PATH + 'admin/ImageConfigDetail',
                            controller: 'ImageConfigCreateCtrl',
                            untilResolved: {
                                templateUrl: BASE_PATH + 'admin/Loading'
                            },
                            resolveFailed: {
                                controller: 'ErrorCtrl'
                            }
                        })
                        .segment('edit', {
                            templateUrl: BASE_PATH + 'admin/ImageConfigDetail',
                            controller: 'ImageConfigEditCtrl',
                            resolve: {
                                image: function(ImageConfig, $q, $routeParams) {

                                    var deferred = $q.defer();
                                    ImageConfig.get({
                                        page_id: $routeParams.page_id,
                                        image_id: $routeParams.image_id
                                    }, function(successData) {
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