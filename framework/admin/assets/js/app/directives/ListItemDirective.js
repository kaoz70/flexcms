/**
 * @ngdoc directive
 * @name App:ListItemDirective
 *
 * @description
 *
 *
 * @restrict A
 * */
angular.module('app')
    .directive('listItemDelete', function () {
        return {
            restrict: 'E',
            template: '<i class="pe-7s-close"></i>',
            link: function(scope, el, attr) {
                el.on('click', function() {
                    //Variables
                    /*var disposeInterval = 300;

                    event.preventDefault();

                    //Get The Panel
                    var panel = el.parents(".panel").eq(0);

                    //Dispose Panel
                    panel.hide(disposeInterval, function() {
                        panel.remove();
                    });*/
                });
            }
        };
});
