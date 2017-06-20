/**
 * @ngdoc service
 * @name app:Language
 *
 * @description
 *
 *
 * */
angular.module('app')
    .service('FieldService', function ($http, $routeParams, $filter) {

        this.getField = ($scope) => {
            const form = $scope.$parent.vm.formData;

            // Find the form by id in the parent list array
            const selected = $filter('filter')(form.fields, {
                id: parseInt($routeParams.field_id, 10),
            }, true);

            return selected[0];
        };

        this.newDummyField = (languages, fieldCount) => {
            const field = {
                id: Date.now(),
                required: false,
                label_enabled: false,
                enabled: true,
                input_id: 13,
                isNew: true,
                translations: [],
            };

            angular.forEach(languages, (value) => {
                field.translations.push({
                    language_id: value.id,
                    name: value.name,
                    data: {
                        name: `campo ${(fieldCount + 1)}`,
                        placeholder: '',
                    },
                });
            });

            return field;
        };
});
