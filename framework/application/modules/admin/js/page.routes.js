(function() {
    'use strict';

    angular
        .module("app")
        .config(function($routeSegmentProvider, BASE_PATH) {

            $routeSegmentProvider.options.autoLoadTemplates = true;

            $routeSegmentProvider
                .when('/page/:page_id', 'page')
                .when('/page/:page_id/content/edit/:id', 'page.content')
                .segment('page', {
                    'default': true,
                    templateUrl: BASE_PATH + 'views/List.html',
                    controller: 'PageCtrl'
                })
                .within()
                    .segment('content', {
                        templateUrl: BASE_PATH + 'views/page/Detail.html',
                        controller: 'ContentEditCtrl',
                        dependencies: ['page_id', 'id']
                    });

        });

}());