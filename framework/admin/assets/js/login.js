var app = angular.module('app', ['ngMaterial', 'angularSpinner']);
app.controller('login', function($scope, $http, $httpParamSerializer, $timeout) {

    $scope.spinnerOpts = {
        lines: 9 // The number of lines to draw
        , length: 3 // The length of each line
        , width: 2 // The line thickness
        , radius: 4 // The radius of the inner circle
        , scale: 1 // Scales overall size of the spinner
        , corners: 1 // Corner roundness (0..1)
        , color: '#fff' // #rgb or #rrggbb or array of colors
        , opacity: 0 // Opacity of the lines
        , rotate: 90 // The rotation offset
        , direction: 1 // 1: clockwise, -1: counterclockwise
        , speed: 1 // Rounds per second
        , trail: 60 // Afterglow percentage
        , fps: 20 // Frames per second when using setTimeout() as a fallback for CSS
        , zIndex: 2e9 // The z-index (defaults to 2000000000)
        , className: 'spinner' // The CSS class to assign to the spinner
        , shadow: false // Whether to render a shadow
        , hwaccel: false // Whether to use hardware acceleration
        , position: 'absolute' // Element positioning
    }

    $scope.user = {};
    $scope.flip = false;
    $scope.resetPassword = function () {
        $scope.flip = true;
    };

    function init() {
        $scope.buttonDissabled = false;
        $scope.buttonClass = "";
        $scope.showSpinner = false;
        $scope.showIcon = false;
        $scope.icon = "";
        $scope.message = "LOGIN";
    }

    init();

    $scope.login = function () {

        $scope.showSpinner = true;
        $scope.buttonDissabled = true;
        $scope.showIcon = false;
        $scope.message = "";
        $scope.buttonClass = "green-bg";

        $http({
            method: 'POST',
            url: system.base_url + 'admin/validate',
            data: $httpParamSerializer($scope.user),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(function (response) {

            $scope.buttonDissabled = false;
            $scope.showSpinner = false;
            $scope.showIcon = true;

            if(response.data.error) {
                $scope.buttonClass = "red-bg";
                $scope.message = "ERROR: " + response.data.message;
                $scope.icon = "error_outline";

                $timeout(function() {
                    init();
                }, 3000);

            } else {
                $scope.buttonClass = "";
                $scope.message = "SUCCESS: redirecting";
                $scope.icon = "check";
                window.location.reload(true);
            }

        });
    }

}).config(function($mdThemingProvider) {

    // Extend the red theme with a different color and make the contrast color black instead of white.
    // For example: raised button text will be black instead of white.
    var blueMap = $mdThemingProvider.extendPalette('blue', {
        '500': '#148FDA'
    });

    // Register the new color palette map with the name <code>neonRed</code>
    $mdThemingProvider.definePalette('flexblue', blueMap);

    // Use that theme for the primary intentions
    $mdThemingProvider.theme('default')
        .primaryPalette('flexblue');

});