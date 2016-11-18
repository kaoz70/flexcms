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

    .controller('ContentEditCtrl', function($scope, $rootScope, Content, $routeSegment, WindowFactory, $routeParams, $filter){

        //Close the sidebar on this controller
        $rootScope.isSidebarOpen = false;

        WindowFactory.add();

        $scope.close_url = "#/page/" + $routeParams.page_id;
        $scope.languages = [];
        $scope.content = {};

        Content.edit($routeParams.id).then(function (response) {

            //Find the content by id in the records array
            var content = $filter('filter')($rootScope.records, {id: parseInt($routeParams.id, 10)}, true);
            $scope.content = content[0];

            $scope.languages = response.data.data.translations;

            //Change the name in the item list
            $scope.$watch('languages["1"].translation.name', function(v){
                $scope.content.translation.name = v;
            });

            //Init the editor
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
        };

        $scope.save = function () {
            Content.save($scope).then(onSave);
        };

        $scope.saveAndClose = function () {
            Content.save($scope).then(onSave);
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

    .controller('ContentConfigCtrl', function($scope, $rootScope, Content, $routeSegment, WindowFactory, $routeParams){

        //Close the sidebar on this controller
        $rootScope.isSidebarOpen = false;

        WindowFactory.add();

        $scope.close_url = "#/page/" + $routeParams.page_id;
        $scope.languages = [];
        $scope.config = {};
        $scope.page = {};
        $scope.roles = [];
        $scope.list_views = [];
        $scope.detail_views = [];

        Content.getConfig($routeParams.widget_id).then(function (response) {
            $scope.config = response.data.data.config;
            $scope.page = response.data.data.content;
            $scope.roles = response.data.data.roles;
            $scope.languages = response.data.data.translations;
            $scope.list_views = response.data.data.list_views;
            $scope.detail_views = response.data.data.detail_views;
        });

        /**
         * Executed after a successful save
         * @param response
         */
        var onSave = function (response) {
            $rootScope.records = response.data.data;
        };

        $scope.save = function () {
            Content.setConfig($routeParams.widget_id, $scope).then(onSave);
        };

        $scope.saveAndClose = function () {
            Content.setConfig($routeParams.widget_id, $scope).then(onSave);
            WindowFactory.remove($scope);
        };

    })

;