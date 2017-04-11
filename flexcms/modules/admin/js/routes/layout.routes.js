angular
    .module('app')
    .config(($routeSegmentProvider, BASE_PATH) => {
        $routeSegmentProvider.options.autoLoadTemplates = true;

        $routeSegmentProvider
            .when('/layout', 'layout')
            .when('/layout/edit/:id', 'layout.edit')
            .when('/layout/create', 'layout.create')
            .segment('layout', {
                default: true,
                controller: 'LayoutIndexController',
            })
            .within()
            .segment('edit', {
                templateUrl: `${BASE_PATH}admin/Layout`,
                controller: 'LayoutController',
                dependencies: ['id'],
                resolve: {
                    layout(Layout, ResourceResponse, $routeParams) {
                        return ResourceResponse.get(Layout, { id: $routeParams.id });
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
                templateUrl: `${BASE_PATH}admin/Layout`,
                controller: 'LayoutCreateController',
                resolve: {
                    layout(Layout, ResourceResponse) {
                        return ResourceResponse.query(Layout);
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
