/**
 * @ngdoc service
 * @name App:WindowFactory
 *
 * @description
 *
 *
 * */
angular.module('app')
    .factory('WindowFactory', function($routeSegment, $filter, $routeParams, $window){

        var Service = {

            /**
             * Adds a window
             *
             * @param $scope
             * @returns {*}
             */
            add: function ($scope) {

                setTimeout(function () {
                    var panels = $(".panel");

                    $.each($routeSegment.chain, function (index) {
                        Service.stack(index, $(panels[index]), $routeSegment.chain.length);
                    });
                }, 50);

                //Find the form by id in the parent list array
                var selected = $filter('filter')($scope.$parent.items, {
                    id: parseInt($routeParams.id, 10)
                }, true);

                if(selected !== undefined && selected[0] !== undefined) {
                    selected[0].selected = true;
                    return selected[0];
                }

            },

            /**
             * Removes the "n" last windows
             *
             * @param amount
             * @param scope
             */
            remove: function (amount, scope) {

                var windowsToKeep = $routeSegment.chain.length - amount,
                    timeout = 0;

                $.each($routeSegment.chain, function (index) {
                    Service.stack(index, $($(".panel")[index]), windowsToKeep);
                });

                //Get the last panels
                var panels = $(".panel").slice(-amount).get().reverse();

                angular.forEach(panels, function (panel) {

                    $(panel)
                        .css('opacity', 1)
                        .css('-webkit-transform', 'translateZ(0) rotateY(0)')
                        .css('-moz-transform', 'translateZ(0) rotateY(0)')
                        .css('-o-transform', 'translateZ(0) rotateY(0)')
                        .css('transform', 'translateZ(0) rotateY(0)')
                        .css('-webkit-filter', 'brightness(1)')
                        .css('-moz-filter', 'brightness(1)')
                        .css('-o-filter', 'brightness(1)')
                        .css('filter', 'brightness(1)');

                    setTimeout(function () {

                        $(panel)
                            .css('left', '100%')
                            .css('opacity', 0);

                    }, timeout);

                    timeout += 200;

                });

                //Deselect the selected item
                if(scope) {
                    angular.forEach(scope.$parent.items, function (item) {
                        item.selected = false;

                        //Second level deselection
                        if(item.items) {
                            angular.forEach(item.items, function (item) {
                                item.selected = false;
                            });
                        }

                    });
                }

            },

            /**
             * Removes all panel windows that are after the passed index
             *
             * @param index
             * @param url
             */
            removeFromIndex: function (index, url) {

                var timeout = 0;

                //Prevent Circular Dependency Injector error
                var delta = $routeSegment.chain.length - index;

                //Remove any child windows
                if(delta > 1) {

                    for (var i = 0; i < delta - 1; i++) {
                        this.remove(delta - 1);
                        timeout += 400;
                    }

                }

                //Go to the new URL
                setTimeout(function () {
                    $window.location.assign(url);
                }, timeout);

            },

            /**
             * Removes the current window and goes back a route
             *
             * @param scope
             */
            back: function (scope) {

                this.remove(1, scope);

                setTimeout(function () {

                    var url = "#/",
                        segments = angular.copy($routeSegment.chain);

                    //Remove the last segment
                    segments.splice(segments.length -1, 1);

                    //Create the segment url
                    angular.forEach(segments, function (item) {

                        url += item.name + "/";

                        if(item.params.dependencies !== undefined) {
                            angular.forEach(item.params.dependencies, function (value) {
                                url += $routeParams[value] + "/";
                            });
                        }

                    });

                    //Change the route once we have hidden the window
                    $window.location.assign(url);

                }, 400);

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
