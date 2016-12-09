(function() {
    'use strict';

    angular
        .module("app")
        .config(function($routeSegmentProvider, BASE_PATH) {

            $routeSegmentProvider.options.autoLoadTemplates = true;

            $routeSegmentProvider
                .when('/page/:page_id', 'page')
                .when('/page/:page_id/content/edit/:id', 'page.content')
                .when('/page/:page_id/content/create', 'page.create')
                .when('/page/:page_id/config/:widget_id', 'page.config')
                .segment('page', {
                    'default': true,
                    templateUrl: BASE_PATH + 'admin/List',
                    controller: 'PageCtrl',
                    dependencies: ['page_id']
                })
                .within()
                    .segment('content', {
                        templateUrl: BASE_PATH + 'content/Detail',
                        controller: 'ContentEditCtrl',
                        dependencies: ['page_id', 'id']
                    })
                    .segment('create', {
                        templateUrl: BASE_PATH + 'content/Detail',
                        controller: 'ContentCreateCtrl',
                        dependencies: ['page_id', 'id']
                    })
                    .segment('config', {
                        templateUrl: BASE_PATH + 'content/Config',
                        controller: 'PageConfigCtrl',
                        dependencies: ['page_id']
                    });

        });

}());