(function() {
    'use strict';

    angular
        .module('app')
        .controller('WidgetsPanelController', WidgetsPanelController);

    WidgetsPanelController.$inject = ['$window'];

    function WidgetsPanelController($window) {
        $window.initialize = initializeMap;

        function initializeMap() {
            if (typeof google.maps.LatLng == 'function') {
                yimaPage.init();
            }
        }

        initializeMap();
    }
}());