/**
 * @ngdoc service
 * @name App:Template
 *
 * @description
 *
 *
 * */
angular.module('app')
    .service('Template', function($http, $compile){

    this.load = function(template, scope) {
        return $http.get(template).then(function (response) {

            // Wrapping the template with some extra markup
            //modal.html('<div class="win">' + response.data + '</div>');

            //$('#windows').append(response);

            // The important part
            $compile(response)(scope);

            //console.log(this);

            //return this;
        });
    };

});

