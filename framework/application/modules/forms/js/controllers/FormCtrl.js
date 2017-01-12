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

        WindowFactory.add();

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

    .controller('FormEditCtrl', function($scope, $rootScope, $routeParams, Form, Field, WindowFactory, $filter, Selection, $mdDialog, languages){

        var fieldCount = 0;

        //Close the sidebar on this controller
        $rootScope.isSidebarOpen = false;

        //Find the form by id in the parent list array
        var selected = $filter('filter')($scope.$parent.items, {
            id: parseInt($routeParams.id, 10)
        }, true);

        //We store the original data here in case the user closes the panel
        var origData = angular.copy(selected[0]);

        $scope.formData = selected[0];
        $scope.formData.selected = true;
        $scope.fields = $scope.formData.fields;

        //Base url
        $scope.section = "forms/edit/" + $scope.formData.id;

        WindowFactory.add();

        $scope.deleteSelection = Selection.init($scope);
        $scope.toggleDeleteSelection = Selection.toggleSelection;
        $scope.onItemClick = Selection.onItemClick;

        var saveHandler = function (response, close) {

            //Create the form fields
            Form.insertFields($scope.items , response.data.data.form).then(function () {

                //Save the fields back int the form
                $scope.formData.fields = $scope.fields;

                if(close) {
                    WindowFactory.remove($scope);
                }

            });

        };

        $scope.save = function () {

            //Check for a valid form
            $scope.form.$setSubmitted();

            if($scope.form.$valid) {
                Form.save($scope.formData, function (response) {
                    saveHandler(response, false)
                });
            }

        };

        $scope.saveAndClose = function () {

            //Check for a valid form
            $scope.form.$setSubmitted();

            if($scope.form.$valid) {
                Form.save($scope.formData, function (response) {
                    saveHandler(response, true)
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
            $scope.fields.push(Field.newDummyField(languages.data, fieldCount));
            fieldCount++;
        };

    })

    .controller('FormCreateCtrl', function($scope, $rootScope, $routeParams, Form, $routeSegment, WindowFactory, $filter, Loading, Selection, $mdDialog, Language, Field){

        var fieldCount = 0;

        //Close the sidebar on this controller
        $rootScope.isSidebarOpen = false;

        $scope.form = {};
        $scope.fields = [];

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

        /**
         * Handler used when the user clicks on the close panel button
         */
        $scope.closeHandler = function () {
            //Reset the data
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
            $scope.fields.push(Field.newDummyField($scope.languages, fieldCount));
            fieldCount++;
        };

    })
    .controller('FieldEditCtrl', function($scope, $rootScope, $routeParams, Form, $routeSegment, WindowFactory, $filter, Loading, Language, Selection, Field, types){

        //Close the sidebar on this controller
        $rootScope.isSidebarOpen = false;

        var form = $scope.$parent.formData;

        //Check if we are accessing this controller after a window reload
        /*if(list === undefined || (list.length - 1) < $routeParams.field_id) {
            WindowFactory.remove($scope);
            return;
        }*/

        $scope.field = form.fields[$routeParams.field_id];
        $scope.types = types.data;

        Selection.addToActiveList($scope.field);

        WindowFactory.add();

        $scope.save = function () {

            //Check for a valid form
            $scope.form.$setSubmitted();

            if($scope.form.$valid) {
                WindowFactory.remove($scope);
            }

        };

    })
;