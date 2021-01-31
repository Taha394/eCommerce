$(function () {
    'use strict';

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

    // password show 
    var passField = $('.password');
    $('.show-pass').hover(function () {
        passField.attr('type', 'text');
    }, function () {
        passField.attr('type', 'password');
    })


    // confrimation message 
    $('.confirm').click(function () {
        return confirm('Are You sure?')
    })

    $('.cat h3').click(function () {
        $(this).next('.full-view').fadeToggle(200);
    });
    $('.option span').click(function () {
        $(this).addClass('active').siblings('span').removeClass('active');
        if ($(this).data('view') === 'full') {
            $('.cat .full-view').fadeIn(200);
        } else {
            $('.cat .full-view').fadeOut(200);
        }
    });

    // show delete button

    $('.child-link').hover(function () {
        $(this).find('.show-delete').fadeIn(400);
    }, function () {
        $(this).find('.show-delete').fadeOut(400);
    });
});