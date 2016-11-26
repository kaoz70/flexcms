/**
 * @ngdoc service
 * @name app:Language
 *
 * @description
 *
 *
 * */
angular.module('app')
    .service('Loading', function($mdPanel){

        var _mdPanel = $mdPanel;

        var position = _mdPanel.newPanelPosition()
            .absolute()
            .center();

        this.show = function(parent) {
            return _mdPanel.open({
                template: '<div class="panel-loading-wrapper" layout-align="center center"><md-progress-circular class="md-accent md-hue-1" md-diameter="30"></md-progress-circular></div>',
                position: position,
                attachTo: parent,
                hasBackdrop: true,
                fullscreen: true
            });
        };

        this.hide = function(panelRef) {
            panelRef.then(function (panel) {
                panel.close();
            });
        };

});

