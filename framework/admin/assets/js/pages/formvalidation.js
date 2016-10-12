var yimaPage = function() {
    var initilize = function() {
        $().ready(function() {
            $("#checkout-form").validate();

            $("#signupForm").validate({
                rules: {
                    firstname: "required",
                    lastname: "required",
                    username: {
                        required: true,
                        minlength: 2
                    },
                    password: {
                        required: true,
                        minlength: 5
                    },
                    confirm_password: {
                        required: true,
                        minlength: 5,
                        equalTo: "#password"
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    agree: "required"
                },
                messages: {
                    firstname: "Please enter your firstname",
                    lastname: "Please enter your lastname",
                    username: {
                        required: "Please enter a username",
                        minlength: "Your username must consist of at least 2 characters"
                    },
                    password: {
                        required: "Please provide a password",
                        minlength: "Your password must be at least 5 characters long"
                    },
                    confirm_password: {
                        required: "Please provide a password",
                        minlength: "Your password must be at least 5 characters long",
                        equalTo: "Please enter the same password as above"
                    },
                    email: "Please enter a valid email address",
                    agree: "Please accept our policy"
                }
            });

            $("#form1").validate({
                errorLabelContainer: $("#form1 div.validation-summary-errors")
            });

            var container = $('div.error-container');
            // validate the form when it is submitted
            var validator = $("#form2").validate({
                errorContainer: container,
                errorLabelContainer: $("ol", container),
                wrapper: 'li',
                messages: {
                    email: "Please enter your email",
                    phone: "Please enter your phone number",
                    address: "Please enter your valid address",
                    avatar: "Please Upload your Avatar",
                    agree: "Please Agree with the terms if you will",
                }
            });

            $(".cancel").click(function() {
                validator.resetForm();
            });
        });
    }

    return {
        init: initilize
    }
}();

jQuery(document).ready(function() {
    if (yima.isAngular() === false) {
        yimaPage.init();
    }
});