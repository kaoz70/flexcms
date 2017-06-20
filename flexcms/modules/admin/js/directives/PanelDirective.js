(function() {
    'use strict';

    angular
        .module('app')
        .directive('panelDispose', panelDispose)
        .directive('panelList', panelList)
        .directive('panelListGrouped', panelListGrouped);

    //Panel Dispose
    function panelDispose(WindowFactory) {
        return {
            restrict: 'E',
            scope: {
                closeCallback: '='
            },
            template: '<md-button class="md-icon-button tools-action" aria-label="Close"><md-icon>close</md-icon></md-button>',
            link: function(scope, el) {
                el.on('click', function() {

                    if(scope.closeCallback !== undefined) {
                        scope.closeCallback();
                    }

                    WindowFactory.back(scope.$parent);

                });
            }
        };
    }

    /**
     * We have to use this directive so that we can overcome the dynamic app-view-segment index issue
     * that's why we have: List1.php, List2.php, etc
     * @link https://github.com/artch/angular-route-segment/issues/26
     *
     * @param BASE_PATH
     * @returns {{restrict: string, templateUrl: string}}
     */
    function panelList(BASE_PATH) {
        return {
            restrict: 'E',
            templateUrl: BASE_PATH + 'admin/List'
        };
    }

    /**
     * We have to use this directive so that we can overcome the dynamic app-view-segment index issue
     * that's why we have: List1.php, List2.php, etc
     * @link https://github.com/artch/angular-route-segment/issues/26
     *
     * @param BASE_PATH
     * @returns {{restrict: string, templateUrl: string}}
     */
    function panelListGrouped(BASE_PATH) {
        return {
            restrict: 'E',
            templateUrl: BASE_PATH + 'admin/ListGrouped'
        };
    }

}());