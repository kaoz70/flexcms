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
             * Removes the current window and goes back a route
             *
             * @param scope
             */
            back: function (scope) {

                this.remove(scope);

                setTimeout(function () {

                    var url = "#/",
                        segments = $routeSegment.chain;

                    //Remove the last segment
                    segments.splice(segments.length -1, 1);

                    //Create the segment url
                    angular.forEach(segments, function (item) {

                        url += item.name + "/";

                        //Add the route parameters
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
