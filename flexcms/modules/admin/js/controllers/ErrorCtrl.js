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
    .controller('ErrorCtrl', function ($scope, Dialog, error) {
        Dialog.responseError(error);
    });
