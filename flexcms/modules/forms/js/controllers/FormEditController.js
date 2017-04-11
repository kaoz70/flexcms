/**
 * Created by Miguel on 11-Apr-17.
 */
angular.module('app')
    .controller('FormEditController', function ($scope, $rootScope, $routeParams, Form, Field, FieldService, WindowFactory, Selection, $mdDialog, languages) {
        const vm = this;
        let fieldCount = 0;

        // Add the panel window and get the current selected item form the list
        const selected = WindowFactory.add($scope);

        // We store the original data here in case the user closes the panel (cancel)
        const origData = angular.copy(selected);

        // Close the sidebar on this controller
        $rootScope.isSidebarOpen = false;

        vm.formData = selected;
        $scope.items = vm.formData.fields;

        // Base url
        vm.section = `forms/edit/${vm.formData.id}`;

        vm.selection = new Selection();
        vm.selection.setDeleteCallback((node) => {
            if (node.isNew) {
                const result = $.grep($scope.items, (e) => {
                    return e.id === node.id;
                });

                const index = $scope.items.indexOf(result[0]);
                $scope.items.splice(index, 1);
            } else {
                Field.delete({ id: node.id }, (response) => {
                    $scope.items = response.data;
                });
            }
        });

        vm.save = () => {
            // Check for a valid form
            vm.form.$setSubmitted();

            if (vm.form.$valid) {
                vm.formData.fields = $scope.items;

                Form.update({ id: vm.formData.id }, vm.formData, (response) => {
                    $scope.items = response.data.fields;
                    angular.copy(response.data, origData);
                });
            }
        };

        vm.saveAndClose = () => {
            // Check for a valid form
            vm.form.$setSubmitted();

            if (vm.form.$valid) {
                vm.formData.fields = $scope.items;

                Form.update({ id: vm.formData.id }, vm.formData, (response) => {
                    angular.copy(response.data, origData);
                    WindowFactory.back($scope);
                });
            }
        };

        /**
         * Handler used when the user clicks on the close panel button
         */
        vm.closeHandler = () => {
            // Reset the data
            angular.copy(origData, vm.formData);
        };

        vm.addField = () => {
            $scope.items.push(FieldService.newDummyField(languages.data, fieldCount));
            fieldCount += 1;
        };
    });
