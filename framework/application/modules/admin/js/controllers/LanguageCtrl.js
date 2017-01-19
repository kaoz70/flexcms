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
    .controller('LanguageCtrl', function($scope, $rootScope, Language, LanguageService, $routeSegment, WindowFactory, Loading, Selection, languages){

        //Close the sidebar on this controller
        $rootScope.isSidebarOpen = false;

        //Window title
        $scope.title = "Idiomas";

        $scope.showDelete = true;
        $scope.showReorder = true;
        $scope.keepOne = 'keep-one';

        $scope.items = languages.data;

        //Menu
        $scope.menu = [
            {
                title: 'nuevo',
                icon: 'add',
                url:'language/create'
            }
        ];

        //Base url
        $scope.section = "language";

        $scope.selection = new Selection(function (node) {

            Language.delete({id: node.id}, function (response) {

                $scope.items = response.data;

                //Remove from the selected array
                $scope.selection.remove(node.id);

            });

        });

        WindowFactory.add($scope);

        $scope.treeOptions = {
            dropped: function () {
                LanguageService.reorder($scope.items);
            }
        };

    })

    .controller('LanguageEditCtrl', function($scope, $rootScope, Language, $routeSegment, WindowFactory, $filter, language){

        //Close the sidebar on this controller
        $rootScope.isSidebarOpen = false;

        WindowFactory.add($scope);

        //We store the original data here in case the user closes the panel (cancel)
        var origData = angular.copy(language.data);

        $scope.language = language.data;
        $scope.title = language.data.name;

        //Find the content by id in the records array
        var ct = $filter('filter')($scope.$parent.items, {
            id: parseInt(language.data.id, 10)
        }, true);

        //Change the name in the item list
        $scope.$watch('language.name', function(v){
            ct[0].name = v;
        });

        $scope.save = function () {
            Language.update({id: $scope.language.id}, $scope.language);
            WindowFactory.back($scope);
        };

        /**
         * Handler used when the user clicks on the close panel button
         */
        $scope.closeHandler = function () {
            //Reset the data
            angular.copy(origData, ct[0]);
        };

    })
    .controller('LanguageCreateCtrl', function($scope, $rootScope, $routeParams, Language, $routeSegment, WindowFactory){

        //Close the sidebar on this controller
        $rootScope.isSidebarOpen = false;

        WindowFactory.add($scope);

        $scope.save = function () {

            Language.save($scope.language, function (response) {
                $scope.$parent.items = response.data;
                WindowFactory.back($scope);
            });

        };

    })
;