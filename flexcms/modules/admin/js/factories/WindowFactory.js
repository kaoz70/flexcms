/**
 * @ngdoc service
 * @name App:WindowFactory
 *
 * @description
 *
 *
 * */
angular.module('app')
    .factory('WindowFactory', ($routeSegment, $filter, $routeParams, $window, $timeout, $location) => {
        return {

            /**
             * Adds a window
             *
             * @param $scope
             * @returns {*}
             */
            add($scope) {
                const Service = this;

                // Find the form by id in the parent list array
                const selected = $filter('filter')($scope.$parent.items, {
                    id: parseInt($routeParams.id, 10),
                }, true);

                $timeout(() => {
                    const panels = $('.panel');

                    angular.forEach($routeSegment.chain, (elem, index) => {
                        Service.stack(index, $(panels[index]), $routeSegment.chain.length);
                    });
                }, 50);

                if (selected !== undefined && selected[0] !== undefined) {
                    selected[0].selected = true;
                    return selected[0];
                }

                return null;
            },

            /**
             * Removes the "n" last windows
             *
             * @param amount
             * @param scope
             */
            remove(amount, scope) {
                const Service = this;
                const windowsToKeep = $routeSegment.chain.length - amount;
                let timeout = 0;
                // Get the last panels
                const panels = $('.panel').slice(-amount).get().reverse();

                angular.forEach($routeSegment.chain, (elem, index) => {
                    Service.stack(index, $($('.panel')[index]), windowsToKeep);
                });

                angular.forEach(panels, (panel) => {
                    Service.apply3D($(panel));

                    $timeout(() => {
                        $(panel)
                            .css('left', '100%')
                            .css('opacity', 0);
                    }, timeout);

                    timeout += 200;
                });

                // Deselect the selected item
                if (scope) {
                    angular.forEach(scope.$parent.items, (item) => {
                        item.selected = false;

                        // Second level deselection
                        if (item.items) {
                            angular.forEach(item.items, (i) => {
                                i.selected = false;
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
            removeFromIndex(index, url) {
                let timeout = 0;

                // Prevent Circular Dependency Injector error
                const delta = $routeSegment.chain.length - index;

                // Remove any child windows
                if (delta > 1) {
                    for (let i = 0; i < delta - 1; i++) {
                        this.remove(delta - 1);
                        timeout += 400;
                    }
                }

                // Go to the new URL
                $timeout(() => {
                    $window.location.assign(url);
                }, timeout);
            },

            /**
             * Removes the current window and goes back a route
             *
             * @param scope
             */
            back(scope) {
                this.remove(1, scope);

                $timeout(() => {
                    let url = '#/';
                    const nativeSegments = $location.$$path.split('/');
                    const segments = angular.copy($routeSegment.chain);

                    // Remove the last segment
                    segments.splice(segments.length - 1, 1);

                    // Create the segment url
                    angular.forEach(segments, (item) => {
                        url += `${item.name}/`;

                        if (item.params.dependencies !== undefined) {
                            angular.forEach(item.params.dependencies, (value) => {
                                url += `${$routeParams[value]}/`;
                            });
                        }
                    });

                    // In the case of "layout" there is no parent segment,
                    // we have to "simulate" this
                    if (url === '#/' && nativeSegments.length > 2) {
                        url += nativeSegments[1];
                    }

                    // Change the route once we have hidden the window
                    $window.location.assign(url);
                }, 400);
            },

            /**
             * Creates the 3d "stack" effect for the windows
             * @param index
             * @param item
             * @param numItems
             */
            stack(index, item, numItems) {
                const amount = 30;
                const multiplier = amount - ((amount / numItems) * (index + 1));
                const left = index * 15;
                const brightness = 1 - ((multiplier / 100) * 3);
                const z = multiplier * 8;
                const r = multiplier * 2;

                // 3d effect
                this.apply3D(item, brightness, z, r);
                item.css('left', `${left}%`);
            },

            /**
             * Applies the CSS for the 3d effect
             *
             * @param item
             * @param brightness
             * @param translate
             * @param rotate
             */
            apply3D(item, brightness = 1, translate = 0, rotate = 0) {
                // 3d effect
                item
                    .css('opacity', 1)
                    .css('-webkit-transform', `translateZ(-${translate}px) rotateY(${rotate}deg)`)
                    .css('-moz-transform', `translateZ(-${translate}px) rotateY(${rotate}deg)`)
                    .css('-o-transform', `translateZ(-${translate}px) rotateY(${rotate}deg)`)
                    .css('transform', `translateZ(-${translate}px) rotateY(${rotate}deg)`)
                    .css('-webkit-filter', `brightness(${brightness})`)
                    .css('-webkit-filter', `brightness(${brightness})`)
                    .css('-moz-filter', `brightness(${brightness})`)
                    .css('-o-filter', `brightness(${brightness})`)
                    .css('filter', `brightness(${brightness})`);
            },
        };
    });
