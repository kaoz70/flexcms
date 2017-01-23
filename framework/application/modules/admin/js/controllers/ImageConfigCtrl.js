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
    .controller('ImageConfigCtrl', function($scope, $rootScope, Config, $routeSegment, WindowFactory, images, ImageConfig, $routeParams, Selection){

        //Close the sidebar on this controller
        $rootScope.isSidebarOpen = false;

        //Window title
        $scope.title = "Im&aacute;genes";

        $scope.showDelete = true;
        $scope.showReorder = false;
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
        $scope.section = "forms";

        $scope.selection = new Selection(function (node) {

            ImageConfig.delete({id: node.id}, function (response) {

                $scope.items = response.data;

                //Remove from the selected array
                $scope.selection.remove(node.id);

            });

        });

        WindowFactory.add($scope);

    })
    .controller('ImageConfigCreateCtrl', function($scope, $rootScope, Config, $routeSegment, WindowFactory, ImageConfig){

        //Close the sidebar on this controller
        $rootScope.isSidebarOpen = false;

        $scope.image = {
            force_jpg: true,
            quality: 80,
            watermark_alpha: 50
        };

        $scope.watermark_data = {};

        WindowFactory.add($scope);

        $scope.saveAndClose = function () {

            //Check for a valid form
            $scope.form.$setSubmitted();

            if($scope.form.$valid) {
                ImageConfig.save($scope, function (response) {
                    $scope.$parent.items = response.data;
                    WindowFactory.back($scope);
                });
            }

        };

    })

;