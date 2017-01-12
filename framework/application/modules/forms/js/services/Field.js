/**
 * @ngdoc service
 * @name app:Language
 *
 * @description
 *
 *
 * */
angular.module('app')
    .service('Field', function($http){

        var urls = {
            insert: 'admin/form/field/insert/',
            types: 'admin/forms/field/getTypes'
        };

        this.getTypes = function() {
            return $http.get(urls.types);
        };

        this.newDummyField = function (languages, fieldCount) {

            var field = {
                id: fieldCount,
                required: false,
                enabled: true,
                type: 13,
                isNew: true,
                translations: []
            };


            angular.forEach(languages, function (value) {
                field.translations.push({
                    name: value.name,
                    translation: {
                        name: 'campo ' + (fieldCount + 1),
                        placeholder: ''
                    }
                });
            });

            return field;

        }

});

