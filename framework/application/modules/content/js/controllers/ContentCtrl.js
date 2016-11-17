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
        $scope.languages = [];

        Content.edit($routeParams.id).then(function (response) {

            $rootScope.records[$routeParams.id] = response.data.data.content;
            $scope.content = response.data.data.content;
            $scope.languages = response.data.data.translations;

            $scope.tinymceOptions = {
                plugins: 'link image code',
                toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | code'
            };

        });

        /**
         * Executed after a successful save
         * @param response
         */
        var onSave = function (response) {
            $scope.content = response.data.data.content;
            $scope.languages = response.data.data.translations;
            $rootScope.records[$scope.content.id] = $scope.content;
        };

        $scope.save = function () {
            Content.save($rootScope.records[$routeParams.id], $scope.languages).then(onSave);
        };

        $scope.saveAndClose = function () {
            Content.save($rootScope.records[$routeParams.id], $scope.languages).then(onSave);
            WindowFactory.remove($scope);
        };

    })

    .controller('ContentCreateCtrl', function($scope, $rootScope, Content, $routeSegment, WindowFactory, $routeParams, Language){

        //Close the sidebar on this controller
        $rootScope.isSidebarOpen = false;

        WindowFactory.add();

        $scope.close_url = "#/page/" + $routeParams.page_id;
        $scope.languages = [];
        $scope.content = {
            id: 0,
            enabled: true,
            important: false,
            publication_start: null,
            publication_end: null,
            css_class: null,
            category_id: $routeParams.page_id
        };

        Language.getAll().then(function (response) {
            $scope.languages = response.data;
            $scope.tinymceOptions = {
                plugins: 'link image code',
                toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | code'
            };
        });

        /**
         * Executed after a successful save
         * @param response
         */
        var onSave = function (response) {
            $scope.content = response.data.data.content;
            $scope.languages = response.data.data.translations;
            $rootScope.records[$scope.content.id] = $scope.content;
        };

        $scope.save = function () {
            Content.save($scope.content, $scope.languages).then(onSave);
        };

        $scope.saveAndClose = function () {
            Content.save($scope.content, $scope.languages).then(onSave);
            WindowFactory.remove($scope);
        };

    })

;