(function () {
    angular
        .module('app')
        .config(($routeSegmentProvider) => {
            $routeSegmentProvider
                .when('/', 'index')
                .segment('index', {
                    default: true,
                    controller: 'IndexController',
                });
        });
}());
