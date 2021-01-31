$(function () {
    'use strict';
    // switch between login/signup
    $('.login-page h1 span').click(function () {
        $(this).addClass('selceted').siblings().removeClass('selceted');
        $('.login-page form').hide();
        $('.' + $(this).data('class')).show();

    });

    // Calls the selectBoxIt method on your HTML select box
    $("select").selectBoxIt({

        autoWidth: false
    });

    $('[placeholder]').focus(function () {
        $(this).attr('data-text', $(this).attr('placeholder'));
        $(this).attr('placeholder', '');
    })
        .blur(function () {
            $(this).attr('placeholder', $(this).attr('data-text'));
        });

    // add astresik
    $('input').each(function () {
        if ($(this).attr('required') === 'required') {
            $(this).after('<span class="asterisk">*</span>');
        }
    });

    $('.live').keyup(function () {
        $($(this).data('class')).text($(this).val());
    });


});