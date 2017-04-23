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
    .directive('widget', function (WIDGET_PATH, WidgetResource, $compile, $templateRequest, Loading) {
        return {
            restrict: 'E',
            scope: {
                widgetId: '=',
                list: '=',
                listIndex: '=',
            },
            link(scope, element) {

                scope.widget = {};
                scope.changed = false;

                let origWidget;
                const setData = (response) => {
                    scope.widget = response.data.widget;
                    scope.languages = response.data.languages;

                    //Save the original widget so that we can compare any changes
                    origWidget = angular.copy(scope.widget);

                    // Replace the widgetId with the real widgets id
                    //scope.widgetId = response.data.widget.id;
                    scope.list[scope.listIndex] = response.data.widget.id;

                    // Load the widget's template
                    $templateRequest(WIDGET_PATH + response.data.view_url).then((html) => {
                        const template = angular.element(html);
                        element.append(template);
                        $compile(template)(scope);

                        scope.$watch('widget', () => {
                            if(angular.equals(origWidget, scope.widget)) {
                                scope.changed = false;
                            } else {
                                scope.changed = true;
                            }
                        }, true);

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

                scope.save = () => {
                    Loading.show(element[0].children[0]);
                    WidgetResource.update(scope.widget, (response) => {
                        Loading.hide();
                        scope.widget = response.data.widget;
                        origWidget = angular.copy(scope.widget);
                    });
                }
            },
        };
    });
