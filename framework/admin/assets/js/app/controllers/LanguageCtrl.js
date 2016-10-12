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
    .controller('LanguageCtrl', function($scope, $rootScope, Language, $routeSegment, WindowFactory){

        //Close the sidebar on this controller
        $rootScope.isSidebarOpen = false;

        //Window title
        $scope.title = "Idiomas";

        //Base url
        $scope.section = "language";

        WindowFactory.add();

        //Load the content
        Language.getAll().then(function (response) {
            $rootScope.records = response.data;
        });

    })

    .controller('LanguageEditCtrl', function($scope, $rootScope, $routeParams, Language, $routeSegment, WindowFactory){

        //Close the sidebar on this controller
        $rootScope.isSidebarOpen = false;

        WindowFactory.add();

        $scope.close_url = '#/language';

        Language.getOne($routeParams.id).then(function (response) {
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