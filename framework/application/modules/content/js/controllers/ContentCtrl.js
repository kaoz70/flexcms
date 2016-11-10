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

    .controller('ContentEditCtrl', function($scope, $rootScope, Content, $routeSegment, WindowFactory, $routeParams){

        //Close the sidebar on this controller
        $rootScope.isSidebarOpen = false;

        WindowFactory.add();

        console.log($routeSegment);

        $scope.close_url = "#/page/" + $routeParams.page_id;

        Content.edit($routeParams.id).then(function (response) {
            $rootScope.records[$routeParams.id] = response.data;
            $scope.language = response.data;
            $scope.title = response.data.name;
        });

        $scope.save = function () {
            Language.save($rootScope.records[$routeParams.id]);
        };

        $scope.saveAndClose = function () {
            Language.save($rootScope.records[$routeParams.id]);
            WindowFactory.remove($scope);
        };

    })

;