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
    .controller('WidgetCtrl', function($scope, Widget){

        $scope.widget = {};
        $scope.getWidget = function (id) {

            //Get the widget data
            Widget.get(id).then(function (response) {
                $scope.widget = response.data.widget;
            });

        };

    })

;