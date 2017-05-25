(function () {
    angular
        .module('app')
        .config(function ($routeSegmentProvider, BASE_PATH) {
            $routeSegmentProvider.options.autoLoadTemplates = true;

            $routeSegmentProvider
                .when('/page', 'page_index')
                .when('/page/:page_id', 'page')
                .when('/page/:page_id/content/edit/:id', 'page.content')
                .when('/page/:page_id/content/create', 'page.create')
                .when('/page/:page_id/config/:widget_id', 'page.config')
                .when('/page/:page_id/images', 'page.images')
                .when('/page/:page_id/images/:section_id/create', 'page.images.create')
                .when('/page/:page_id/images/edit/:image_id', 'page.images.edit')
                .segment('page_index', {
                    default: true,
                    controller: 'PageIndexController',
                })
                .segment('page', {
                    templateUrl: `${BASE_PATH}admin/List1`,
                    controller: 'PageController',
                    controllerAs: 'vm',
                    resolve: {
                        page(Page, $routeParams, ResourceResponse) {
                            return ResourceResponse.get(Page, { id: $routeParams.page_id });
                        },
                    },
                    untilResolved: {
                        templateUrl: `${BASE_PATH}admin/Loading`,
                    },
                    resolveFailed: {
                        controller: 'ErrorCtrl',
                    },
                    dependencies: ['page_id'],
                })
                .within()
                .segment('content', {
                    templateUrl: `${BASE_PATH}content/Detail`,
                    controller: 'ContentEditController',
                    controllerAs: 'vm',
                    dependencies: ['page_id', 'id'],
                    resolve: {
                        content(Content, $routeParams, ResourceResponse) {
                            return ResourceResponse.get(Content, { id: $routeParams.id });
                        },
                    },
                    untilResolved: {
                        templateUrl: `${BASE_PATH}admin/Loading`,
                    },
                    resolveFailed: {
                        controller: 'ErrorCtrl',
                    },
                })
                .segment('create', {
                    templateUrl: `${BASE_PATH}content/Detail`,
                    controller: 'ContentCreateController',
                    controllerAs: 'vm',
                    dependencies: ['page_id', 'id'],
                    resolve: {
                        languages(Language, ResourceResponse) {
                            return ResourceResponse.query(Language);
                        },
                    },
                    untilResolved: {
                        templateUrl: `${BASE_PATH}admin/Loading`,
                    },
                    resolveFailed: {
                        controller: 'ErrorCtrl',
                    },
                })
                .segment('config', {
                    templateUrl: `${BASE_PATH}content/Config`,
                    controller: 'PageConfigController',
                    dependencies: ['page_id', 'widget_id'],
                    resolve: {
                        config(ContentConfig, ResourceResponse, $routeParams) {
                            return ResourceResponse.get(ContentConfig, { id: $routeParams.widget_id });
                        },
                    },
                    untilResolved: {
                        templateUrl: `${BASE_PATH}admin/Loading`,
                    },
                    resolveFailed: {
                        controller: 'ErrorCtrl',
                    },
                })
                .segment('images', {
                    templateUrl: `${BASE_PATH}admin/ListGrouped2`,
                    controller: 'ImageConfigController',
                    resolve: {
                        images(ImageConfig, $q, $routeParams) {
                            const deferred = $q.defer();
                            ImageConfig.query({ page_id: $routeParams.page_id }, (successData) => {
                                deferred.resolve(successData);
                            }, (errorData) => {
                                deferred.reject(errorData);
                            });

                            return deferred.promise;
                        },
                    },
                    untilResolved: {
                        templateUrl: `${BASE_PATH}admin/Loading`,
                    },
                    resolveFailed: {
                        controller: 'ErrorCtrl',
                    },
                })
                .within()
                .segment('create', {
                    templateUrl: `${BASE_PATH}admin/ImageConfigDetail`,
                    controller: 'ImageConfigCreateController',
                    untilResolved: {
                        templateUrl: `${BASE_PATH}admin/Loading`,
                    },
                    resolveFailed: {
                        controller: 'ErrorCtrl',
                    },
                })
                .segment('edit', {
                    templateUrl: `${BASE_PATH}admin/ImageConfigDetail`,
                    controller: 'ImageConfigEditCtrl',
                    resolve: {
                        image(ImageConfig, ResourceResponse, $routeParams) {
                            return ResourceResponse.get(ImageConfig, {
                                page_id: $routeParams.page_id,
                                image_id: $routeParams.image_id,
                            });
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
