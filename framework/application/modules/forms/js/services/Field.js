/**
 * @ngdoc service
 * @name app:Language
 *
 * @description
 *
 *
 * */
angular.module('app')
    .service('FieldService', function($http, $routeParams, $filter){

        this.getField = function ($scope) {

            var form = $scope.$parent.formData;

            //Find the form by id in the parent list array
            var selected = $filter('filter')(form.fields, {
                id: parseInt($routeParams.field_id, 10)
            }, true);

            return selected[0];

        };

        this.newDummyField = function (languages, fieldCount) {

            var field = {
                id: Date.now(),
                required: false,
                label_enabled: false,
                enabled: true,
                input_id: 13,
                isNew: true,
                translations: []
            };


            angular.forEach(languages, function (value) {
                field.translations.push({
                    language_id: value.id,
                    name: value.name,
                    data: {
                        name: 'campo ' + (fieldCount + 1),
                        placeholder: ''
                    }
                });
            });

            return field;

        };

});

