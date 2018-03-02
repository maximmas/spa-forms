"use strict";

//console.log('Metoo');

jQuery(document).ready(function($) {

     $('#formRes button.tester').on('click', function(e){
        e.preventDefault();
        //let is_valid = formValidator();

        let is_valid = 1;

        if ( is_valid ){
            handlerBookingForm();            
        };

     });


    function formValidator() {

        let name_el = $( "input#input_name" ),
            email_el = $( "input#input_mail" ),
            name    = name_el.val(),
            email   = email_el.val();

        if ( !name || !email ) {
            if (!name) {
                name_el.removeClass('validation_ok').addClass('validation_error');
            } else name_el.removeClass('validation_error').addClass('validation_ok');
            if (!email) {
                email_el.removeClass('validation_ok').addClass('validation_error');
            } else email_el.removeClass('validation_error').addClass('validation_ok');
            return false;
        } else {
            name_el.add(email_el).removeClass('validation_error').addClass('validation_ok');
            return true;
        };
    };

    function handlerBookingForm() {
        let data = {
            action: 'booking',
            name: $( "input#input_name" ).val(),
            email: $( "input#input_mail" ).val(),
            date:$( "input#input_date" ).val(),
            time:$( "input#input_time" ).val(),
            message:$( "textarea.input_message" ).val(),
            people: ''
        };
        $.ajax({
            type: 'POST',
            url: window.AjaxHandler.ajaxurl,
            data: data,
            cache: false,
            dataType: 'text',
            //async: false,
            beforeSend: function (response) {

            },
            success: function (response) {
                var res = parseInt(response[0]);
                if (res) {
                    alert(res);

                } else {
                    alert('Error');
                }
            },
            error: function (response) {
                alert('Error2')
            }
        });
    };
});