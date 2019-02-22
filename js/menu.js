let burgerHadClassBlack = false; // variable de travail pour voir si le burger doit être noir ou blanc après le close
let burger;

var $ = jQuery.noConflict();

$(document).ready(function() {

    $('#menu').click(function() {   
        burger = $('#menu .burger').first();

        if (burger.hasClass('open')) closeMenu();
        else openMenu();
    })

})

function openMenu() {
    burgerHadClassBlack = burger.hasClass('black')
    burger.addClass('open').removeClass('black')
    $('#menu_content').addClass('open')
}

function closeMenu() {
    if (burgerHadClassBlack) burger.addClass('black')
    burger.removeClass('open')
    $('#menu_content').removeClass('open')
}

// helper to move in a lib.js
function scrollDown(element_id) {
    $([document.documentElement, document.body]).animate({
        scrollTop: $("#" + element_id).offset().top
    }, 2000);
}