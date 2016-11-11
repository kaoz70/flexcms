jQuery.validator.setDefaults({
    highlight: function (element, errorClass, validClass) {
        if (element.type === 'radio') {
            this.findByName(element.name).addClass(errorClass).removeClass(validClass);
        }

        else {
            $(element).closest('.form-group').removeClass('has-success has-feedback').addClass('has-error has-feedback');
            $(element).closest('.form-group').find('.form-control-feedback').remove();
            if (element.type !== 'checkbox')
                $(element).closest('.form-group').append('<i class="pe-7s-close form-control-feedback"></i>');
        }
        if (element.type === 'checkbox') {
            $(element).addClass('colored-danger').removeClass('colored-success');
        }
    },
    unhighlight: function (element, errorClass, validClass) {
        if (element.type === 'radio') {
            this.findByName(element.name).removeClass(errorClass).addClass(validClass);
        }
        else {
            $(element).closest('.form-group').removeClass('has-error has-feedback').addClass('has-success has-feedback');
            $(element).closest('.form-group').find('.form-control-feedback').remove();
            if (element.type !== 'checkbox')
                $(element).closest('.form-group').append('<i class="pe-7s-check form-control-feedback"></i>');
        }
        if (element.type === 'checkbox') {
            $(element).addClass('colored-success').removeClass('colored-danger');
        }
    },
    errorElement: 'span',
    errorClass: 'error help-block',
    errorPlacement: function (error, element) {
        if (element.parent('.input-group').length) {
            error.insertAfter(element.parent());
        }
        else if (element.parents('.checkbox').length) {
            error.insertAfter(element.parent().parent());
        }
        else {
            error.insertAfter(element);
        }
    }
});

$(function () {
    $("span.field-validation-valid, span.field-validation-error").addClass('help-block');
    $("div.form-group").has("span.field-validation-error").addClass('has-error');
    $("div.validation-summary-errors").has("li:visible").addClass("alert alert-block alert-danger");
});