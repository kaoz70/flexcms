(function() {
    'use strict';

    angular
        .module("app")
        .config(function($routeSegmentProvider, BASE_PATH) {

            $routeSegmentProvider.options.autoLoadTemplates = true;

            $routeSegmentProvider
                .when('/language', 'language')
                .when('/language/edit/:id', 'language.edit')
                .when('/language/create', 'language.create')
                .segment('language', {
                    'default': true,
                    templateUrl: BASE_PATH + 'admin/List',
                    controller: 'LanguageCtrl'
                })
                .within()
                    .segment('edit', {
                        templateUrl: BASE_PATH + 'admin/LanguageDetail',
                        controller: 'LanguageEditCtrl',
                        dependencies: ['id']
                    })
                    .segment('create', {
                        templateUrl: BASE_PATH + 'admin/LanguageDetail',
                        controller: 'LanguageCreateCtrl'
                    })
            ;

        });

}());