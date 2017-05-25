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
        const _mdPanel = $mdPanel;
        const position = _mdPanel.newPanelPosition()
            .absolute()
            .center();
        let panelRef;

        this.show = (parent) => {
            _mdPanel.open({
                template: '<div class="panel-loading-wrapper" layout-align="center center"><md-progress-circular class="md-accent" md-diameter="30"></md-progress-circular></div>',
                position: position,
                attachTo: parent,
                hasBackdrop: true,
                controller: (mdPanelRef) => {
                    panelRef = mdPanelRef;
                }
            });
        };

        this.hide = () => {
            panelRef.close();
        };
});
