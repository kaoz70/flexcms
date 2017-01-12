/**
 * @ngdoc service
 * @name App:WindowFactory
 *
 * @description
 *
 *
 * */
angular.module('app')
    .factory('WindowFactory', function($routeSegment){

        var Service = {

            /**
             * Adds a window
             */
            add: function () {

                setTimeout(function () {
                    var panels = $(".panel");

                    $.each($routeSegment.chain, function (index) {
                        Service.stack(index, $(panels[index]), $routeSegment.chain.length);
                    });
                }, 50);

            },

            remove: function (scope) {

                $.each($routeSegment.chain, function (index) {
                    Service.stack(index, $($(".panel")[index]), $routeSegment.chain.length - 1);
                });

                $($(".panel")[$routeSegment.chain.length - 1])
                    .css('left', '100%')
                    .css('opacity', 0);

                //Deselect the selected item
                angular.forEach(scope.$parent.items, function (item) {
                    item.selected = false;
                });

            },

            /**
             * Creates the 3d "stack" effect for the windows
             * @param index
             * @param item
             * @param num_items
             */
            stack: function (index, item, num_items) {

                var amount = 30,
                    multiplier = amount - ((amount / num_items) * (index + 1)),
                    left = index * 15,
                    opacity = 1 - ((multiplier / 100) * 3),
                    z = multiplier * 8,
                    r = multiplier * 2;

                //3d effect
                item
                    .css('opacity', 1)
                    .css('-webkit-transform', 'translateZ(-' + z + 'px) rotateY(' + r + 'deg)')
                    .css('-moz-transform', 'translateZ(-' + z + 'px) rotateY(' + r + 'deg)')
                    .css('-o-transform', 'translateZ(-' + z + 'px) rotateY(' + r + 'deg)')
                    .css('transform', 'translateZ(-' + z + 'px) rotateY(' + r + 'deg)')
                    .css('-webkit-filter', 'brightness(' + opacity + ')')
                    .css('-moz-filter', 'brightness(' + opacity + ')')
                    .css('-o-filter', 'brightness(' + opacity + ')')
                    .css('filter', 'brightness(' + opacity + ')')
                    .css('left', left + '%');

            }


        };

        return Service;

    });
