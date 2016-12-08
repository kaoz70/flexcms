(function() {
    'use strict';

    angular
        .module("app")
        .config(function($routeSegmentProvider, BASE_PATH) {

            $routeSegmentProvider.options.autoLoadTemplates = true;

            $routeSegmentProvider
                .when('/config', 'config')
                .segment('config', {
                    'default': true,
                    templateUrl: BASE_PATH + 'admin/ConfigGeneral',
                    controller: 'ConfigCtrl'
                });

        });

}());