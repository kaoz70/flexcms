/**
 * @ngdoc service
 * @name app:Language
 *
 * @description
 *
 *
 * */
angular.module('app')
    .factory('Selection', function ($window, $rootScope, $mdDialog, Dialog, BASE_PATH, WindowFactory, $document) {
        function Selection() {
            let selection = [];
            let deleteCallback;

            // toggle selection for a given item by id
            const toggleSelection = (node) => {
                const idx = selection.indexOf(node);

                // is currently selected
                if (idx !== -1) {
                    selection.splice(idx, 1);
                } else { // is newly selected
                    selection.push(node);
                }
            };

            this.remove = (node) => {
                const idx = selection.indexOf(node);
                selection.splice(idx, 1);
            };

            this.toggle = (node) => {
                toggleSelection(node);
            };

            this.getLength = () => {
                return selection.length;
            };

            this.getItems = () => {
                return selection;
            };

            this.setDeleteCallback = (callback) => {
                deleteCallback = callback;
            };

            /**
             * Show a dialog and if successful input execute the callback
             *
             * @param ev
             */
            this.delete = (ev) => {
                // TODO: move all this to use the Dialog service:
                // Dialog.delete();

                function DialogController($scope) {

                    if (selection.length > 1) {
                        $scope.message = `¿Est&aacute; seguro de que desea eliminar estos ${selection.length} elementos?`;
                    } else {
                        $scope.message = '¿Est&aacute; seguro de que desea eliminar este elemento?';
                    }

                    $scope.cancel = () => {
                        $mdDialog.hide();
                    };

                    $scope.delete = () => {
                        angular.forEach(selection, (node) => {
                            deleteCallback(node);
                        });

                        selection = [];
                        $mdDialog.hide();
                    };

                }

                $mdDialog.show({
                    templateUrl: `${BASE_PATH}admin/dialogs/DeleteDialog`,
                    parent: angular.element($document[0].body),
                    targetEvent: ev,
                    controller: DialogController,
                    clickOutsideToClose: true,
                });
            };

            /**
             * Handles the click event for a list item
             *
             * @param node
             * @param nodes
             * @param url
             */
            this.onItemClick = (node, nodes, url, $event) => {
                if (node.selected) {
                    return;
                }

                // Remove the selected state from any other item
                angular.forEach(nodes, (item) => {
                    item.selected = false;
                });

                // Set the current node as selected
                node.selected = true;

                // Get the current element parent panel index so that we
                // know if there are child windows
                const currentPanelIndex = $('[app-view-segment]').index($($event.target).closest('[app-view-segment]'));
                WindowFactory.removeFromIndex(currentPanelIndex, url);
            };

            this.addToActiveList = (item) => {
                if (item) {
                    item.selected = true;
                }
            };
        }

        return Selection;
    });
