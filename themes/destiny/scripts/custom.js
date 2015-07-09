$(function() {
    "use strict";

    $(".gallery").bxSlider({
        pager: false,
        slideWidth: 800,
        minSlides: 1,
        maxSlides: 1,
        slideMargin: 50,
        adaptiveHeight: true
    });

    $(".form_contacto")
        .on("reset", function() {
            $(this).data("formValidation").resetForm();
        })
        .on("success.form.fv", function() {

            var modal = $(this).find(".modal");

            modal.modal();

            $.post($(this).attr("action"), $(this).serialize())
                .done(function(data) {
                    modal.find(".ajax-response")
                        .empty()
                        .html($(data));
                })
                .fail(function(data) {
                    modal.find(".ajax-response")
                        .empty()
                        .html("<h4>" + data.statusText + "</h4>");
                });

        })
        .on("submit", function(e) {
            e.preventDefault();
        })
        .formValidation();

});
