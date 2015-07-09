/*global $ /

/**
 * Created by Miguel on 26/11/2014.
 */

$(document).ready(function () {
    "use strict";

    var pedido = $('.pedido');

    pedido.find('.add')
        .click(function (ev) {

            ev.preventDefault();

            var form = $(this).closest('form'),
                el = $(this),
                current_text = $(this).text();

            $(this)
                .addClass('loading')
                .text('adding...');

            $.ajax({
                type: "POST",
                dataType: "json",
                url: form.attr('action'),
                data: form.serialize()
            })
                .done(function (data) {

                    $('#message').html(data.message);
                    $('#mini_cart').html(data.mini_cart);

                    el.text('Success!')
                        .removeClass('loading');

                    window.setTimeout(function () {
                        el.text(current_text);
                    }, 2000);

                })
                .fail(function () {

                    el.text('Error!')
                        .removeClass('loading');

                    window.setTimeout(function () {
                        el.text(current_text);
                    }, 2000);

                });

        });

    pedido.find('.delete')
        .click(function (e) {

            var el = $(this),
                form = $(this).closest('form'),
                current_text = $(this).text();

            e.preventDefault();

            $(this)
                .addClass('loading')
                .text('deleting...');

            $.ajax({
                type: "POST",
                dataType: "json",
                url: $(this).attr('href'),
                data: 'csrf_test=' + $.cookie('csrf_cookie')
            })
                .done(function (data) {
                    $('#message').html(data.message);
                    $('#mini_cart').html(data.mini_cart);
                    el.closest('tr').remove();
                })
                .fail(function () {

                    el.text('Error!')
                        .removeClass('loading');

                    window.setTimeout(function () {
                        el.text(current_text);
                    }, 2000);

                });

        });

    $('#copy_billing_details').on('change', function () {
        $('input[name^="checkout[billing]"]').each(function () {
            // Target textboxes only, no hidden fields
            if ($(this).attr('type') === 'text') {
                var name = $(this).attr('name').replace('billing', 'shipping'),
                    value = ($('#copy_billing_details').is(':checked')) ? $(this).val() : '';

                $('input[name="' + name + '"]').val(value);
            }
        });
    });

});