function openCard(el) {
    $('.card_title').removeClass('open')
    $('.card_descr').hide(200)
    el.addClass('open')
    el.find('.card_descr').show(200)
}

function closeCard(el) {
    el.removeClass('open')
    el.find('.card_descr').hide(200)
}


function scrollDownPorgrammation(el_id) {
    $([document.documentElement, document.body]).animate({
        scrollTop: $('body').scrollTop() + $('#' + el_id).offset().top - $(window).height() * 0.1
    }, 700);
}

function scrollH(id, n) {
    let el = $('#' + id)
    console.log("scrollh", el.scrollLeft(), n)
    el.animate({
        scrollLeft: el.scrollLeft() + n
    }, 400);
}

function unique(arr) {
    let new_arr = []; for (let el of arr) {if (!new_arr.includes(el)) new_arr.push(el)}; return new_arr
}

function flatten(arr) {
    let new_arr = [];
    for (let el of arr) {
        if (Array.isArray(el)) new_arr = new_arr.concat(flatten(el));
        else new_arr.push(el)
    }
    return new_arr
}





let header_state = 'none';
let scroll_pos_test;

$(document).ready(function() {
    $('#menu .burger').removeClass('black').addClass('gold');

    // click event on cards
    $('.card_title').click(function(e) {
        e.stopPropagation();
        if ($(this).hasClass('open')) return closeCard($(this));
        openCard($(this));
    })

    // 
    header_state = 'none';
    scroll_pos_test = $('#content').offset().top;

    // on desktop, we show arrows in the square list
    if ($(window).width() > 640) init_squares_arrows();
    
})

/* $('body').scroll(function() {
    let y_scroll_pos = $(this).scrollTop();

    if (y_scroll_pos > scroll_pos_test && header_state != 'black') {
        $('header').css({background: 'black'})
        header_state = 'black'
    } else if (y_scroll_pos <= scroll_pos_test && header_state != 'none') {
        $('header').css({background: 'none'})
        header_state = 'transparent'
    }
}); */