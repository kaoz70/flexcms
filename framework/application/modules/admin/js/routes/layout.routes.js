(function() {
    'use strict';

    angular
        .module("app")
        .config(function($routeSegmentProvider, BASE_PATH) {

            $routeSegmentProvider.options.autoLoadTemplates = true;

            $routeSegmentProvider
                .when('/layout', 'layout_index')
                .when('/layout/:page_id', 'layout')
                .segment('layout_index', {
                    'default': true,
                    controller: 'LayoutIndexCtrl'
                })
                .segment('layout', {
                    templateUrl: BASE_PATH + 'admin/Layout',
                    controller: 'LayoutCtrl',
                    dependencies: ['page_id']
                });

        });

}());