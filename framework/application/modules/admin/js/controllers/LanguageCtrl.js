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
    .controller('LanguageCtrl', function($scope, $rootScope, Language, $routeSegment, WindowFactory, Loading, Selection, $mdDialog){

        //Close the sidebar on this controller
        $rootScope.isSidebarOpen = false;

        //Window title
        $scope.title = "Idiomas";

        $scope.showDelete = true;
        $scope.showReorder = true;
        $scope.keepOne = 'keep-one';

        //Base url
        $scope.section = "language";

        $scope.deleteSelection = Selection.init($scope);
        $scope.toggleDeleteSelection = Selection.toggleSelection;
        $scope.onItemClick = Selection.onItemClick;

        WindowFactory.add();
        var panel = Loading.show();

        //Load the content
        Language.getAll().then(function (response) {
            $rootScope.records = response.data.data.items;
            $scope.menu = [
                {
                    title: 'nuevo',
                    icon: 'add',
                    url:'language/create'
                }
            ];
            Loading.hide(panel);
        });

        $scope.treeOptions = {
            dropped: function (scope, modelData, sourceIndex) {
                Language.setOrder($rootScope.records);
            }
        };

        $scope.delete = function (ev) {

            Selection.delete(ev, function() {

                Language.delete($scope.deleteSelection).then(function (response) {

                    if(response.data.success) {

                        $rootScope.records = response.data.data;
                        $scope.deleteSelection = [];
                    }

                    $mdDialog.hide();

                });

            });

        }

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
    .controller('LanguageCreateCtrl', function($scope, $rootScope, $routeParams, Language, $routeSegment, WindowFactory, $filter, Loading, Selection){

        //Close the sidebar on this controller
        $rootScope.isSidebarOpen = false;

        WindowFactory.add();

        $scope.save = function () {

            Language.insert($scope.language).then(function (response) {
                $rootScope.records = response.data.data;
                WindowFactory.remove($scope);
            });

        };

    })
;