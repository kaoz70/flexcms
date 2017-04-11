/**
 * Created by Miguel on 11-Apr-17.
 */
angular.module('app')

    .controller('FormCreateController', function ($scope, $rootScope, $routeParams, Form, Field, FieldService, WindowFactory, Selection, $mdDialog, languages) {
        const vm = this;
        let fieldCount = 0;
        let isNew = true;

        // Close the sidebar on this controller
        $rootScope.isSidebarOpen = false;

        vm.formData = {
            fields: [],
        };
        vm.items = vm.formData.fields;

        WindowFactory.add($scope);

        // Base url
        vm.section = 'forms/create';

        vm.selection = new Selection();
        vm.selection.setDeleteCallback((node) => {
            const result = $.grep(vm.items, (e) => {
                return e.id === node;
            });

            const index = vm.items.indexOf(result[0]);
            vm.items.splice(index, 1);
        });

        vm.save = () => {
            // Check for a valid form
            vm.form.$setSubmitted();

            if (vm.form.$valid) {
                if (isNew) {
                    Form.save(vm.formData, (response) => {
                        vm.formData = response.data;
                        vm.items = response.data.fields;
                        $scope.$parent.items.push(vm.formData);
                        vm.formData.selected = true;
                        isNew = false;
                    });
                } else {
                    Form.update({ id: vm.formData.id }, vm.formData, (response) => {
                        vm.formData = response.data;
                        vm.items = response.data.fields;
                    });
                }
            }
        };

        vm.saveAndClose = () => {
            // Check for a valid form
            vm.form.$setSubmitted();

            if (vm.form.$valid) {
                if (isNew) {
                    Form.save(vm.formData, (response) => {
                        $scope.$parent.items.push(response.data);
                        WindowFactory.back($scope);
                    });
                } else {
                    Form.update({ id: vm.formData.id }, vm.formData, () => {
                        WindowFactory.back($scope);
                    });
                }
            }
        };

        vm.addField = () => {
            vm.items.push(FieldService.newDummyField(languages.data, fieldCount));
            fieldCount += 1;
        };
    });
