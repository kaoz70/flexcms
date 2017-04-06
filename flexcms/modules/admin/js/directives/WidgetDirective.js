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
    .directive('widget', function (WIDGET_PATH, Widget, $compile, $templateRequest) {

        return {
            restrict: 'E',
            scope: {
                widgetId: '='
            },
            link: function(scope, element) {

                //Get the widget's data
                Widget.get(scope.widgetId).then(function (response) {

                    scope.widget = response.data.data.widget;
                    scope.languages = response.data.data.languages;

                    //Load the widget's template
                    $templateRequest(WIDGET_PATH + response.data.data.view_url).then(function(html){
                        var template = angular.element(html);
                        element.append(template);
                        $compile(template)(scope);
                    });

                });

            }

        };
});
