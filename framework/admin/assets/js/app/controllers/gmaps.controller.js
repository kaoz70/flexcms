(function() {
    'use strict';

    angular
        .module('app')
        .controller('GMapsController', GMapsController);

    GMapsController.$inject = ['$window'];

    function GMapsController($window) {
        $window.initialize = initializeMap;

        function initializeMap() {
            if (typeof google.maps.LatLng == 'function') {
                yimaPage.init();
            }
        }

        initializeMap();
    }
}());