(function() {
    angular
        .module('app')
        .config(($routeSegmentProvider, BASE_PATH) => {
            $routeSegmentProvider.options.autoLoadTemplates = true;

            $routeSegmentProvider
                .when('/forms', 'forms')
                .when('/forms/edit/:id', 'forms.edit')
                .when('/forms/edit/:id/field/edit/:field_id', 'forms.edit.edit')
                .when('/forms/create', 'forms.create')
                .when('/forms/create/field/edit/:field_id', 'forms.create.edit')
                .segment('forms', {
                    default: true,
                    templateUrl: `${BASE_PATH}admin/List1`,
                    controller: 'FormController',
                    controllerAs: 'vm',
                    resolve: {
                        forms(Form, ResourceResponse) {
                            return ResourceResponse.query(Form);
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
                    .segment('edit', {
                        templateUrl: `${BASE_PATH}forms/FormDetail`,
                        controller: 'FormEditController',
                        controllerAs: 'vm',
                        dependencies: ['id'],
                        resolve: {
                            languages(Language, ResourceResponse) {
                                return ResourceResponse.query(Language);
                            },
                            form(Form, $routeParams, ResourceResponse) {
                                return ResourceResponse.get(Form, { id: $routeParams.id });
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
                        .segment('edit', {
                            templateUrl: `${BASE_PATH}forms/FieldDetail`,
                            controller: 'FieldEditController',
                            controllerAs: 'vm',
                            dependencies: ['id', 'field_id'],
                            resolve: {
                                types(Field, ResourceResponse) {
                                    return ResourceResponse.query(Field);
                                },
                            },
                            untilResolved: {
                                templateUrl: `${BASE_PATH}admin/Loading`,
                            },
                            resolveFailed: {
                                controller: 'ErrorCtrl',
                            },
                        })
                        .up()
                    .segment('create', {
                        templateUrl: `${BASE_PATH}forms/FormDetail`,
                        controller: 'FormCreateController',
                        controllerAs: 'vm',
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
                    .within()
                        .segment('edit', {
                            templateUrl: `${BASE_PATH}forms/FieldDetail`,
                            controller: 'FieldEditController',
                            controllerAs: 'vm',
                            dependencies: ['field_id'],
                            resolve: {
                                types(Field, ResourceResponse) {
                                    return ResourceResponse.query(Field);
                                },
                            },
                            untilResolved: {
                                templateUrl: `${BASE_PATH}admin/Loading`,
                            },
                            resolveFailed: {
                                controller: 'ErrorCtrl',
                            },
                        })
            ;
        });
}());
