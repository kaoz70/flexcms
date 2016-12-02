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
    .controller('LanguageCtrl', function($scope, $rootScope, Language, $routeSegment, WindowFactory, Loading, Selection){

        //Close the sidebar on this controller
        $rootScope.isSidebarOpen = false;

        //Window title
        $scope.title = "Idiomas";

        //Base url
        $scope.section = "language";

        $scope.deleteSelection = Selection.init($scope);
        $scope.toggleDeleteSelection = Selection.toggleSelection;
        $scope.onItemClick = Selection.onItemClick;

        WindowFactory.add();
        var panel = Loading.show();

        //Load the content
        Language.getAll().then(function (response) {
            $rootScope.records = response.data.items;
            $scope.menu = response.data.menu;
            Loading.hide(panel);
        });

    })

    .controller('LanguageEditCtrl', function($scope, $rootScope, $routeParams, Language, $routeSegment, WindowFactory, $filter, Loading, Selection){

        //Close the sidebar on this controller
        $rootScope.isSidebarOpen = false;

        WindowFactory.add();
        var panel = Loading.show();

        $scope.close_url = '#/language';

        Language.getOne($routeParams.id).then(function (response) {

            //Find the content by id in the records array
            var language = $filter('filter')($rootScope.records, {
                id: parseInt($routeParams.id, 10)
            }, true);

            $scope.language = language[0];
            Selection.addToActiveList($scope.language);
            $scope.title = response.data.name;

            Loading.hide(panel);

        });

        $scope.save = function () {
            Language.save($scope.language);
            WindowFactory.remove($scope);
        };

    })
;