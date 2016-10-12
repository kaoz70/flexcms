(function() {
    'use strict';

    angular
        .module('app')
        .controller('MainController', MainController);

    MainController.$inject = ['$scope', '$rootScope', '$state'];

    function MainController($scope, $rootScope, $state) {
        yima.init();

        $scope.$state = $state;
        $rootScope.isSidebarOpen = true;

        $scope.openPanel = function () {
            $rootScope.isSidebarOpen = true;
        };

        $scope.closePanel = function () {
            $rootScope.isSidebarOpen = false;
        };

        
    }
}());