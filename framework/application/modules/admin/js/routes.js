(function() {
    'use strict';

    angular
        .module("app")
        .config(function($routeSegmentProvider, BASE_PATH) {

            $routeSegmentProvider.options.autoLoadTemplates = true;

            $routeSegmentProvider
                .when('/language', 'language')
                .when('/language/edit/:id', 'language.edit')
                .segment('language', {
                    'default': true,
                    templateUrl: BASE_PATH + 'views/List.html',
                    controller: 'LanguageCtrl'
                })
                .within()
                    .segment('edit', {
                        templateUrl: BASE_PATH + 'views/language/Detail.html',
                        controller: 'LanguageEditCtrl',
                        dependencies: ['id']
                    });

        });

}());