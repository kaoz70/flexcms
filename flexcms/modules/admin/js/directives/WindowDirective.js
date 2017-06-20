/**
 * @ngdoc directive
 * @name app:WindowDirective
 *
 * @description
 *
 *
 * @restrict A
 * */
angular.module('app')
    .directive("window", function($compile){
        return {
            restrict: 'A',
            replace: true,
            //transclude: true,
            link: function(scope, element, attrs){
                scope.count++;
                var compiled = $compile('<div class="panel panel-primary"><div class="panel-heading"><h3 class="panel-title">{{title}}<a class="anchorjs-link" href="#panel-title"><span class="anchorjs-icon"></span></a></h3><div class="panel-tools"><panel-dispose></panel-dispose></div></div><div class="panel-body"><div app-view-segment="' + attrs.appViewSegment + '"></div></div></div>');
                angular.element(element).html("").append(compiled(scope));
            }
        };
    })
    .directive( 'showData', function ( $compile ) {
        return {
            scope: true,
            link: function ( scope, element, attrs ) {
                var el;

                attrs.$observe( 'template', function ( tpl ) {
                    if ( angular.isDefined( tpl ) ) {
                        // compile the provided template against the current scope
                        el = $compile( tpl )( scope );

                        // stupid way of emptying the element
                        element.html("");

                        // add the template content
                        element.append( el );
                    }
                });
            }
        };
    });

