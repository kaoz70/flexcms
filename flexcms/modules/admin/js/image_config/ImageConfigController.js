/**
 * Created by Miguel on 19-Jan-17.
 */

/**
 * @ngdoc controller
 * @name App:ImageConfigCtrl
 *
 * @description
 *
 *
 * @requires $scope
 * */
angular.module('app')
    .controller('ImageConfigController', function ($scope, $rootScope, Config, WindowFactory, images, ImageConfig, $routeParams, Selection) {
        // Window title
        $scope.title = 'Im&aacute;genes';

        $scope.showDelete = true;
        $scope.showReorder = true;
        $scope.keepOne = '';

        $scope.items = images.data;
        $scope.menu = [];

        // Base url
        $scope.section = `page/${$routeParams.page_id}/images`;

        $scope.selection = new Selection();
        $scope.selection.setDeleteCallback((node) => {
            ImageConfig.delete({ image_id: node.id }, (response) => {
                $scope.items = response.data;

                // Remove from the selected array
                $scope.selection.remove(node.id);
            });
        });

        $scope.treeOptions = {
            dropped(ev) {
                const section_id = ev.source.nodeScope.$modelValue.image_section_id;
                ImageConfig.update({ page_id: section_id, method: 'reorder' }, $scope.items);
            },
        };

        WindowFactory.add($scope);
    });
