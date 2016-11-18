/**
 * @ngdoc service
 * @name app:Language
 *
 * @description
 *
 *
 * */
angular.module('app')
    .service('Response', function(){

        /**
         * Validate a correct response with no errors
         *
         * @param response
         * @returns {*}
         */
        this.validate = function(response) {

            try {

                //Is there any erro code present?
                if (response.error_code) {
                    throw response.message;
                }

                //Is it a valid JSON response?
                else if(response == undefined || typeof response == "string") {
                    throw "Hubo un problema con la petición";
                }

                return response;

            } catch (err) {
                $('#modal-danger')
                    .modal('show')
                    .find('.modal-body')
                    .html(err);
            }

            return false;

        };

        this.error = function (data, status) {
            $('#modal-danger')
                .modal('show')
                .find('.modal-body')
                .html('Al parecer hay un error de servidor<br />[Error: ' + status + ']');
        }

});

