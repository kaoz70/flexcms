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

        $scope.selection = new Selection(function (node) {

            Form.delete({id: node.id}, function (response) {

                $scope.items = response.data;

                //Remove from the selected array
                $scope.selection.remove(node.id);

            });

        });

        WindowFactory.add($scope);

    })

    .controller('FormEditCtrl', function($scope, $rootScope, $routeParams, Form, Field, FieldService, WindowFactory, Selection, $mdDialog, languages){

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

        $scope.selection = new Selection(function (node) {

            if(node.isNew) {

                var result = $.grep($scope.items, function(e){
                    return e.id === node.id;
                });

                var index = $scope.items.indexOf(result[0]);
                $scope.items.splice(index, 1);

            } else {
                Field.delete({id: node.id}, function (response) {
                    $scope.items = response.data;
                });
            }

        });

        $scope.save = function () {

            //Check for a valid form
            $scope.form.$setSubmitted();

            if($scope.form.$valid) {

                $scope.formData.fields = $scope.items;

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

                $scope.formData.fields = $scope.items;

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

        $scope.addField = function () {
            $scope.items.push(FieldService.newDummyField(languages.data, fieldCount));
            fieldCount++;
        };

    })

    .controller('FormCreateCtrl', function($scope, $rootScope, $routeParams, Form, Field, FieldService, WindowFactory, Selection, $mdDialog, languages){

        var fieldCount = 0,
            isNew = true;

        //Close the sidebar on this controller
        $rootScope.isSidebarOpen = false;

        $scope.formData = {
            fields: []
        };
        $scope.items = $scope.formData.fields;

        WindowFactory.add($scope);

        //Base url
        $scope.section = "forms/create";

        $scope.selection = new Selection(function (node) {

            var result = $.grep($scope.items, function(e){
                return e.id === node;
            });

            var index = $scope.items.indexOf(result[0]);
            $scope.items.splice(index, 1);

        });

        $scope.save = function () {

            //Check for a valid form
            $scope.form.$setSubmitted();

            if($scope.form.$valid) {

                if(isNew) {
                    Form.save($scope.formData, function(response) {
                        $scope.formData = response.data;
                        $scope.items = response.data.fields;
                        $scope.$parent.items.push($scope.formData);
                        $scope.formData.selected = true;
                        isNew = false;
                    });
                } else {
                    Form.update({id: $scope.formData.id}, $scope.formData, function(response) {
                        $scope.formData = response.data;
                        $scope.items = response.data.fields;
                    });
                }

            }

        };

        $scope.saveAndClose = function () {

            //Check for a valid form
            $scope.form.$setSubmitted();

            if($scope.form.$valid) {

                if(isNew) {
                    Form.save($scope.formData, function() {
                        WindowFactory.back($scope);
                        $scope.$parent.items.push($scope.formData);
                    });
                } else {
                    Form.update({id: $scope.formData.id}, $scope.formData, function() {
                        WindowFactory.back($scope);
                    });
                }

            }

        };

        $scope.addField = function () {
            $scope.items.push(FieldService.newDummyField(languages.data, fieldCount));
            fieldCount++;
        };

    })
    .controller('FieldEditCtrl', function($scope, $rootScope, $routeParams, Form, $routeSegment, WindowFactory, $filter, Loading, Language, Selection, Field, types, FieldService){

        //Close the sidebar on this controller
        $rootScope.isSidebarOpen = false;

        //Check if we are accessing this controller after a window reload
        if($scope.$parent.items.length === 0) {
            WindowFactory.back($scope);
            return;
        }

        $scope.field = FieldService.getField($scope);
        $scope.types = types.data;

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