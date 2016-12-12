(function() {
    'use strict';

    angular
        .module("app")
        .config(function($routeSegmentProvider, BASE_PATH) {

            $routeSegmentProvider.options.autoLoadTemplates = true;

            $routeSegmentProvider
                .when('/layout/:page_id', 'layout')
                .segment('layout', {
                    'default': true,
                    templateUrl: BASE_PATH + 'admin/Layout',
                    controller: 'LayoutCtrl',
                    dependencies: ['page_id']
                });

        });

}());