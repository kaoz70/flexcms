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

    .controller('LayoutIndexController', function($rootScope) {

        //Open the sidebar on this controller
        $rootScope.isSidebarOpen = true;

    })

    .controller('LayoutController', function($rootScope, LayoutService, $routeSegment, WindowFactory, $routeParams, layout) {

        //Open the sidebar on this controller
        $rootScope.isSidebarOpen = true;

        var vm = this;

        //Base url
        vm.section = 'layout/' + $routeParams.page_id;
        vm.query = '';
        vm.selected = [];
        vm.device = 'large';

        vm.languages = layout.page.translations;
        vm.page = layout.page.content;
        vm.pages = layout.pages;
        vm.roles = layout.roles;
        vm.rows = layout.rows;
        vm.widgets = layout.widgets;

        vm.layoutService = LayoutService;

        vm.$watch('rows', function(rows) {
            vm.modelAsJson = angular.toJson(rows, true);
        }, true);

        WindowFactory.add(vm);

    })

    .controller('LayoutCreateController', function($rootScope, LayoutService, $routeSegment, WindowFactory, layout) {

        //Open the sidebar on this controller
        $rootScope.isSidebarOpen = true;
        console.log(3);

        var vm = this;

        //Base url
        vm.section = 'layout/create';
        vm.query = '';
        vm.selected = [];
        vm.device = 'large';

        vm.languages = layout.page.translations;
        vm.page = {};
        vm.pages = layout.pages;
        vm.roles = layout.roles;
        vm.rows = layout.rows;
        vm.widgets = layout.widgets;

        vm.layoutService = LayoutService;

        vm.$watch('rows', function(rows) {
            vm.modelAsJson = angular.toJson(rows, true);
        }, true);

        WindowFactory.add(vm);

    })

;
