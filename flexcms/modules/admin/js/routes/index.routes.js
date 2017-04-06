(function() {
    'use strict';

    angular
        .module("app")
        .config(function($routeSegmentProvider) {

            $routeSegmentProvider
                .when('/', 'index')
                .segment('index', {
                    'default': true,
                    controller: 'IndexCtrl'
                });

        });

}());