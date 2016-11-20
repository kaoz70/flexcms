(function() {
    'use strict';

    angular
        .module('app')
        .controller('MainController', MainController);

    MainController.$inject = ['$scope', '$rootScope', '$state', 'Page'];

    function MainController($scope, $rootScope, $state, Page) {

        yima.init();

        $scope.$state = $state;
        $rootScope.isSidebarOpen = true;
        $scope.pages = {};

        $scope.openPanel = function () {
            $rootScope.isSidebarOpen = true;
        };

        $scope.closePanel = function () {
            $rootScope.isSidebarOpen = false;
        };

        Page.getAll(1).then(function (response) {
            $scope.pages = response.data.data;
        });
        
    }
}());