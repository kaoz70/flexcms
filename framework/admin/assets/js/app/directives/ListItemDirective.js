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
    .directive('listItemDelete', function (Content, $rootScope, $filter) {
        return {
            restrict: 'E',
            template: '<md-icon>close</md-icon>',
            scope: {
                item: '='
            },
            link: function(scope, el, attr) {
                el.on('click', function() {

                    var modal = $('#modal-warning')
                        .modal('show')
                        .find('.modal-body')
                        .html('¿Est&aacute; seguro de que desea eliminar este recurso?');

                    //We remove the event and add it again so that it does'nt get executed multiple times
                    // (we are binding the event on every click)
                    angular.element($("[data-ok]")).unbind('click').bind('click', function() {
                        Content.delete(scope.item.id).then(function () {

                            //Remove the element
                            var content = $filter('filter')($rootScope.records, {id: parseInt(scope.item.id, 10)}, true);
                            $rootScope.records.splice($rootScope.records.indexOf(content), 1);

                        });
                    });

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
