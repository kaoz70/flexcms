angular.module('app')

    .controller('LanguageCreateController', function ($scope, $rootScope, $routeParams, Language, $routeSegment, WindowFactory) {
        const vm = this;

        vm.language = {};

        // Close the sidebar on this controller
        $rootScope.isSidebarOpen = false;

        WindowFactory.add($scope);

        vm.save = () => {
            Language.save(vm.language, (response) => {
                $scope.$parent.items = response.data;
                WindowFactory.back($scope);
            });
        };
    });
