(function() {
    'use strict';

    angular
        .module('app')
        .controller('MainController', MainController);

    MainController.$inject = ['$scope', '$rootScope', 'Page', '$routeSegment'];

    function MainController($scope, $rootScope, Page, $routeSegment) {

        yima.init();

        $scope.$routeSegment = $routeSegment;
        $rootScope.isSidebarOpen = true;
        $scope.pages = {};

        $scope.openPanel = function () {
            $rootScope.isSidebarOpen = true;
        };

        $scope.closePanel = function () {
            $rootScope.isSidebarOpen = false;
        };

        Page.getAll(null).then(function (response) {
            $scope.pages = response.data.data;
        });

    }
}());