/**
 * @ngdoc controller
 * @name App:LanguageCtrl
 *
 * @description
 *
 *
 * @requires $scope
 * */
angular.module('app')
    .controller('FieldEditController', function ($scope, $rootScope, WindowFactory, types, FieldService) {
        const vm = this;

        // Close the sidebar on this controller
        $rootScope.isSidebarOpen = false;

        // Check if we are accessing this controller after a window reload
        if ($scope.$parent.items.length === 0) {
            WindowFactory.back($scope);
            return;
        }

        vm.field = FieldService.getField($scope);
        vm.types = types.data;

        WindowFactory.add($scope);

        vm.save = () => {
            // Check for a valid form
            vm.form.$setSubmitted();

            if (vm.form.$valid) {
                WindowFactory.back($scope);
            }
        };
    });
