/**
 * Created by Miguel on 11-Apr-17.
 */
angular.module('app')
    .controller('FormController', function($scope, $rootScope, Form, $routeSegment, WindowFactory, Loading, Selection, $mdDialog, forms) {
        const vm = this;

        // Close the sidebar on this controller
        $rootScope.isSidebarOpen = false;

        // Window title
        vm.title = 'Formularios';

        vm.showDelete = true;
        vm.showReorder = false;
        vm.keepOne = '';

        $scope.items = forms.data;
        vm.menu = [
            {
                title: 'nuevo',
                icon: 'add',
                url: 'forms/create',
            },
        ];

        // Base url
        vm.section = 'forms';

        vm.selection = new Selection();
        vm.selection.setDeleteCallback((node) => {
            Form.delete({ id: node.id }, (response) => {
                $scope.items = response.data;

                // Remove from the selected array
                vm.selection.remove(node.id);
            });
        });

        WindowFactory.add($scope);
    });
