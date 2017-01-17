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
    .controller('FormCtrl', function($scope, $rootScope, Form, $routeSegment, WindowFactory, Loading, Selection, $mdDialog, response){

        //Close the sidebar on this controller
        $rootScope.isSidebarOpen = false;

        //Reset the scopeList
        $rootScope.scopeList = [];

        //Window title
        $scope.title = "Formularios";

        $scope.showDelete = true;
        $scope.showReorder = false;
        $scope.keepOne = '';

        $scope.items = response.data;
        $scope.menu = [
            {
                title: 'nuevo',
                icon: 'add',
                url: 'forms/create'
            }
        ];

        //Base url
        $scope.section = "forms";

        $scope.deleteSelection = Selection.init($scope);
        $scope.toggleDeleteSelection = Selection.toggleSelection;
        $scope.onItemClick = Selection.onItemClick;

        WindowFactory.add($scope);

        $scope.delete = function (ev) {

            Selection.delete(ev, function() {

                Form.delete($scope.deleteSelection).then(function (response) {

                    if(response.data.success) {
                        $scope.items = response.data.data;
                        $scope.deleteSelection = [];
                    }

                    $mdDialog.hide();

                });

            });

        }

    })

    .controller('FormEditCtrl', function($scope, $rootScope, $routeParams, Form, Field, WindowFactory, Selection, $mdDialog, languages, $window, $routeSegment){

        var fieldCount = 0;

        //Close the sidebar on this controller
        $rootScope.isSidebarOpen = false;

        //Add the panel window and get the current selected item form the list
        var selected = WindowFactory.add($scope);

        //We store the original data here in case the user closes the panel (cancel)
        var origData = angular.copy(selected);

        $scope.formData = selected;
        $scope.items = $scope.formData.fields;

        //Base url
        $scope.section = "forms/edit/" + $scope.formData.id;

        $scope.deleteSelection = Selection.init($scope);
        $scope.toggleDeleteSelection = Selection.toggleSelection;
        $scope.onItemClick = Selection.onItemClick;

        $scope.save = function () {

            //Check for a valid form
            $scope.form.$setSubmitted();

            if($scope.form.$valid) {
                Form.update({id: $scope.formData.id}, $scope.formData, function(response) {
                    $scope.items = response.data.fields;
                    angular.copy(response.data, origData);
                });
            }

        };

        $scope.saveAndClose = function () {

            //Check for a valid form
            $scope.form.$setSubmitted();

            if($scope.form.$valid) {
                Form.update({id: $scope.formData.id}, $scope.formData, function(response) {
                    angular.copy(response.data, origData);
                    WindowFactory.back($scope);
                });
            }

        };

        /**
         * Handler used when the user clicks on the close panel button
         */
        $scope.closeHandler = function () {
            //Reset the data
            angular.copy(origData, $scope.formData);
        };

        $scope.delete = function (ev) {

            Selection.delete(ev, function() {

                angular.forEach($scope.deleteSelection, function(value) {
                    $scope.items.splice(value, 1)
                });

                $scope.deleteSelection = [];
                $mdDialog.hide();

            });

        };

        $scope.addField = function () {
            $scope.items.push(Field.newDummyField(languages.data, fieldCount));
            fieldCount++;
        };

    })

    .controller('FormCreateCtrl', function($scope, $rootScope, $routeParams, Form, $routeSegment, WindowFactory, $filter, Loading, Selection, $mdDialog, Language, Field){

        var fieldCount = 0;

        //Close the sidebar on this controller
        $rootScope.isSidebarOpen = false;

        $scope.form = {};
        $scope.items = [];

        WindowFactory.add($scope);

        //Base url
        $scope.section = "forms/create";

        $scope.deleteSelection = Selection.init($scope);
        $scope.toggleDeleteSelection = Selection.toggleSelection;
        $scope.onItemClick = Selection.onItemClick;

        var saveHandler = function (response, close) {

            $rootScope.scopeList[0].items = response.data.data.list;

            //Create the form fields
            Form.insertFields($scope.items , response.data.data.form).then(function () {
                if(close) {
                    WindowFactory.remove($scope);
                }
            });

        };

        $scope.save = function () {

            //Check for a valid form
            $scope.form.$setSubmitted();

            if($scope.form.$valid) {
                Form.insert($scope.form).then(function (response) {
                    saveHandler(response, false)
                });
            }

        };
        $scope.saveAndClose = function () {

            //Check for a valid form
            $scope.form.$setSubmitted();

            if($scope.form.$valid) {
                Form.insert($scope.form).then(function (response) {
                    saveHandler(response, true)
                });
            }

        };

        $scope.delete = function (ev) {

            Selection.delete(ev, function() {

                angular.forEach($scope.deleteSelection, function(value) {
                    $scope.items.splice(value, 1)
                });

                $scope.deleteSelection = [];
                $mdDialog.hide();

            });

        };

        $scope.addField = function () {
            $scope.items.push(Field.newDummyField($scope.languages, fieldCount));
            fieldCount++;
        };

    })
    .controller('FieldEditCtrl', function($scope, $rootScope, $routeParams, Form, $routeSegment, WindowFactory, $filter, Loading, Language, Selection, Field, types){

        //Close the sidebar on this controller
        $rootScope.isSidebarOpen = false;

        var form = $scope.$parent.formData;

        //Check if we are accessing this controller after a window reload
        if($scope.$parent.items.length === 0) {
            WindowFactory.back($scope);
            return;
        }

        //Find the form by id in the parent list array
        var selected = $filter('filter')(form.fields, {
            id: parseInt($routeParams.field_id, 10)
        }, true);

        $scope.field = selected[0];
        $scope.types = types.data.data;

        WindowFactory.add($scope);

        $scope.save = function () {

            //Check for a valid form
            $scope.form.$setSubmitted();

            if($scope.form.$valid) {
                WindowFactory.back($scope);
            }

        };

    })
;