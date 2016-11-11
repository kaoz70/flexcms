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

        $scope.close_url = "#/page/" + $routeParams.page_id;
        $scope.translations = [];

        Content.edit($routeParams.id).then(function (response) {

            $rootScope.records[$routeParams.id] = response.data.data.content;
            $scope.content = response.data.data.content;
            $scope.translations = response.data.data.translations;

            $scope.tinymceOptions = {
                plugins: 'link image code',
                toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | code'
            };

        });

        $scope.save = function () {
            Content.save($rootScope.records[$routeParams.id], $scope.translations);
        };

        $scope.saveAndClose = function () {
            Content.save($rootScope.records[$routeParams.id], $scope.translations);
            WindowFactory.remove($scope);
        };

    })

;