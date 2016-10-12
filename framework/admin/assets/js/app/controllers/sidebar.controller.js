(function() {
    'use strict';

    angular
        .module('app')
        .controller('SidebarController', SidebarController);

    SidebarController.$inject = ['$scope', '$state'];

    function SidebarController($scope, $state) {
        $scope.$on('$includeContentLoaded', function() {
            yima.sidebarInit();
        });
    }
}());