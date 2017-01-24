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

        //Close the sidebar on this controller
        $rootScope.isSidebarOpen = false;

        //Window title
        $scope.title = "Im&aacute;genes";

        $scope.showDelete = true;
        $scope.showReorder = true;
        $scope.keepOne = '';

        $scope.items = images.data;
        $scope.menu = [
            {
                title: 'nuevo',
                icon: 'add',
                url: 'page/' + $routeParams.page_id + '/images/create'
            }
        ];

        //Base url
        $scope.section = "page/" + $routeParams.page_id + "/images";

        $scope.selection = new Selection(function (node) {

            ImageConfig.delete({image_id: node.id}, function (response) {

                $scope.items = response.data;

                //Remove from the selected array
                $scope.selection.remove(node.id);

            });

        });

        $scope.treeOptions = {
            dropped: function () {
                ImageConfig.update({page_id: $routeParams.page_id, method: 'reorder'}, $scope.items);
            }
        };

        WindowFactory.add($scope);

    })
    .controller('ImageConfigCreateCtrl', function($scope, $rootScope, Config, $routeParams, WindowFactory, ImageConfig){

        //Close the sidebar on this controller
        $rootScope.isSidebarOpen = false;

        $scope.image = {
            category_id: $routeParams.page_id,
            force_jpg: true,
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

        //Close the sidebar on this controller
        $rootScope.isSidebarOpen = false;

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