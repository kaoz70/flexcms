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
    .controller('ImageConfigCtrl', function($scope, $rootScope, Config, WindowFactory, images, ImageConfig, $routeParams, Selection){

        //Window title
        $scope.title = "Im&aacute;genes";

        $scope.showDelete = true;
        $scope.showReorder = true;
        $scope.keepOne = '';

        $scope.items = images.data;
        $scope.menu = [];

        //Base url
        $scope.section = "page/" + $routeParams.page_id + "/images";

        $scope.selection = new Selection();
        $scope.selection.setDeleteCallback(function (node) {

            ImageConfig.delete({image_id: node.id}, function (response) {

                $scope.items = response.data;

                //Remove from the selected array
                $scope.selection.remove(node.id);

            });

        });

        $scope.treeOptions = {
            dropped: function (ev) {
                var section_id = ev.source.nodeScope.$modelValue.image_section_id;
                ImageConfig.update({page_id: section_id, method: 'reorder'}, $scope.items);
            }
        };

        WindowFactory.add($scope);

    })
    .controller('ImageConfigCreateCtrl', function($scope, $rootScope, Config, $routeParams, WindowFactory, ImageConfig){

        $scope.image = {
            image_section_id: $routeParams.section_id,
            force_jpg: true,
            optimize_original: true,
            quality: 80,
            watermark_alpha: 50
        };

        $scope.watermark_data = {};

        WindowFactory.add($scope);

        $scope.saveAndClose = function () {

            //Check for a valid form
            $scope.form.$setSubmitted();

            var data = {
                image: $scope.image,
                file: $scope.watermark_data
            };

            if($scope.form.$valid) {
                ImageConfig.save(data, function (response) {
                    $scope.$parent.items = response.data;
                    WindowFactory.back($scope);
                });
            }

        };

    })
    .controller('ImageConfigEditCtrl', function($scope, $rootScope, Config, $routeParams, WindowFactory, ImageConfig, image){

        $scope.image = image.data.image;
        $scope.watermark_data = image.data.watermark;

        WindowFactory.add($scope);

        $scope.saveAndClose = function () {

            //Check for a valid form
            $scope.form.$setSubmitted();

            var data = {
                image: $scope.image,
                file: $scope.watermark_data
            };

            if($scope.form.$valid) {
                ImageConfig.update({
                    image_id: $scope.image.id
                },data, function (response) {
                    $scope.$parent.items = response.data;
                    WindowFactory.back($scope);
                });
            }

        };

    })

;