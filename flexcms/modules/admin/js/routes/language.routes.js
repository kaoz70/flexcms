angular
    .module('app')
    .config(($routeSegmentProvider, BASE_PATH) => {
        $routeSegmentProvider.options.autoLoadTemplates = true;

        $routeSegmentProvider
            .when('/language', 'language')
            .when('/language/edit/:id', 'language.edit')
            .when('/language/create', 'language.create')
            .segment('language', {
                default: true,
                templateUrl: `${BASE_PATH}admin/List1`,
                controller: 'LanguageController',
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
                    templateUrl: `${BASE_PATH}admin/LanguageDetail`,
                    controller: 'LanguageEditController',
                    controllerAs: 'vm',
                    dependencies: ['id'],
                    resolve: {
                        language(Language, ResourceResponse, $routeParams) {
                            return ResourceResponse.get(Language, { id: $routeParams.id });
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
                    templateUrl: `${BASE_PATH}admin/LanguageDetail`,
                    controller: 'LanguageCreateController',
                    controllerAs: 'vm',
                });
    });
