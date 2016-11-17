/**
 * @ngdoc service
 * @name app:Language
 *
 * @description
 *
 *
 * */
angular.module('app')
    .service('Response', function(Notification){

        /**
         * Validate a correct response with no errors
         * @param response
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

                //Show a success notification
                Notification.show('success', response.message);

            } catch (err) {
                $('#modal-danger')
                    .modal('show')
                    .find('.modal-body')
                    .html(err);
            }

        };



});

