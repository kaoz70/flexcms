/**
 * Created by Miguel on 10-Apr-17.
 */

angular.module('app')
    .controller('LanguageController', function ($scope, $rootScope, Language, WindowFactory, Selection, languages) {
        const vm = this;

        // Close the sidebar on this controller
        $rootScope.isSidebarOpen = false;

        // Window title
        vm.title = 'Idiomas';

        vm.showDelete = true;
        vm.showReorder = true;
        vm.keepOne = 'keep-one';

        $scope.items = languages.data;

        // Menu
        vm.menu = [
            {
                title: 'nuevo',
                icon: 'add',
                url: 'language/create',
            },
        ];

        // Base url
        vm.section = 'language';

        vm.selection = new Selection((node) => {
            Language.delete({ id: node.id }, (response) => {
                $scope.items = response.data;

                // Remove from the selected array
                $scope.selection.remove(node.id);
            });
        });

        WindowFactory.add($scope);

        vm.treeOptions = {
            dropped() {
                Language.update({ method: 'reorder' }, $scope.items);
            },
        };
    });
