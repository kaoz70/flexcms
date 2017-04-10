angular.module('app')

    .controller('LanguageCreateController', function ($scope, $rootScope, $routeParams, Language, $routeSegment, WindowFactory) {
        // Close the sidebar on this controller
        $rootScope.isSidebarOpen = false;

        WindowFactory.add($scope);

        $scope.save = () => {
            Language.save($scope.language, function (response) {
                $scope.$parent.items = response.data;
                WindowFactory.back($scope);
            });
        };
    });
