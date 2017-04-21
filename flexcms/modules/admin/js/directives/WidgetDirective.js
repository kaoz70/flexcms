/**
 * @ngdoc directive
 * @name app:widget
 *
 * @description
 *
 *
 * @restrict A
 * */
angular.module('app')
    .directive('widget', function (WIDGET_PATH, WidgetResource, $compile, $templateRequest) {
        return {
            restrict: 'E',
            scope: {
                widgetId: '=',
                list: '=',
                listIndex: '=',
            },
            link(scope, element) {

                scope.widget = {};

                let origWidget;
                const setData = (response) => {
                    scope.widget = response.data.widget;
                    scope.languages = response.data.languages;

                    origWidget = scope.widget;

                    // Replace the widgetId with the real widgets id
                    //scope.widgetId = response.data.widget.id;
                    scope.list[scope.listIndex] = response.data.widget.id;

                    // Load the widget's template
                    $templateRequest(WIDGET_PATH + response.data.view_url).then((html) => {
                        const template = angular.element(html);
                        element.append(template);
                        $compile(template)(scope);
                    });
                };

                if (typeof scope.widgetId === 'object') {
                    // In this case widgetId is the widget type data {}
                    WidgetResource.save(scope.widgetId, (response) => {
                        setData(response);
                    });
                } else {
                    // Get the widget's data
                    WidgetResource.get({ id: scope.widgetId }, (response) => {
                        setData(response);
                    });
                }

                /*scope.$watchCollection(scope.widget, () => {
                    console.log(4);
                });*/

            },
        };
    });
