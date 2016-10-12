(function() {
    'use strict';

    angular
        .module('app')
        .controller('ProfileController', ProfileController);

    ProfileController.$inject = ['$window'];

    function ProfileController($window) {
        $window.initialize = initializeMap;

        function initializeMap() {
            if (typeof google.maps.LatLng == 'function') {
                yimaPage.init();
            }
        }

        initializeMap();
    }
}());