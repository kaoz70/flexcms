angular.module('app').directive('perfectScrollbar',
    ['$parse', '$window', function ($parse, $window, $timeout) {

        // Ps options to test against when creating options{}
        const psOptions = [
            'wheelSpeed',
            'wheelPropagation',
            'minScrollbarLength',
            'useBothWheelAxes',
            'useKeyboard',
            'suppressScrollX',
            'suppressScrollY',
            'scrollXMarginOffset',
            'scrollYMarginOffset',
            'includePadding',
        ];

        return {
            transclude: true,
            template: '<div><div ng-transclude></div></div>',
            replace: false,
            link($scope, $elem, $attr) {
                if ($attr.perfectScrollbar === 'false') {
                    return;
                }
                const el = $elem[0];
                const jqWindow = angular.element($window);
                const options = {};

                // search Ps lib options passed as attrs to wrapper
                for (let i = 0, l = psOptions.length; i < l; i++) {
                    const opt = psOptions[i];
                    if (typeof $attr[opt] !== 'undefined') {
                        options[opt] = $parse($attr[opt])();
                    }
                }

                $scope.$evalAsync(() => {
                    Ps.initialize(el, options);
                    const onScrollHandler = $parse($attr.onScroll);
                    $elem.on('scroll', () => {
                        const scrollTop = el.scrollTop;
                        const scrollHeight = el.scrollHeight - el.clientHeight;
                        $scope.$apply(() => {
                            onScrollHandler($scope, {
                                scrollTop,
                                scrollHeight,
                            });
                        });
                    });
                });

                function update(event) {
                    $scope.$evalAsync(() => {
                        if ($attr.scrollDown === 'true' && event !== 'mouseenter') {
                            $timeout(() => {
                                el.scrollTop = el.scrollHeight;
                            }, 100);
                        }
                        Ps.update(el);
                    });
                }

                // This is necessary when you don't watch anything with the scrollbar
                $elem.bind('mouseenter', () => {
                    update('mouseenter');
                });

                // Possible future improvement - check the type here and use
                // the appropriate watch for non-arrays
                if ($attr.refreshOnChange) {
                    $scope.$watchCollection($attr.refreshOnChange, () => {
                        update();
                    });
                }

                // update scrollbar once window is resized
                if ($attr.refreshOnResize) {
                    jqWindow.on('resize', update);
                }

                $elem.bind('$destroy', () => {
                    jqWindow.off('resize', update);
                    Ps.destroy(el);
                });
            },
        };
    }]);
