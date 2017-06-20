angular
    .module('app')
    .config(($routeSegmentProvider, BASE_PATH) => {
        $routeSegmentProvider.options.autoLoadTemplates = true;

        $routeSegmentProvider
            .when('/layout', 'layout_index')
            .when('/layout/edit/:id', 'layout_edit')
            .when('/layout/create', 'layout_create')
            .segment('layout_index', {
                default: true,
                controller: 'LayoutIndexController',
            })
            .segment('layout_edit', {
                templateUrl: `${BASE_PATH}admin/Layout`,
                controller: 'LayoutEditController',
                controllerAs: 'vm',
                dependencies: ['id'],
                resolve: {
                    layout(LayoutResource, ResourceResponse, $routeParams) {
                        return ResourceResponse.get(LayoutResource, { id: $routeParams.id });
                    },
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
            .segment('layout_create', {
                templateUrl: `${BASE_PATH}admin/Layout`,
                controller: 'LayoutCreateController',
                controllerAs: 'vm',
                resolve: {
                    layout(LayoutResource, ResourceResponse) {
                        return ResourceResponse.get(LayoutResource, { id: 'new' });
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
