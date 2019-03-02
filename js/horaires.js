jQuery(document).ready(function($) {

    // initialize navigation ariane points
    /* initArianePoints('.slide', {
        scroll_container: 'window',
        auto_scroll: [0,1],
    })
    $('ul.ariane_points').addClass('black'); */
    
    // initialize menu scrolling
    init_menu_scroll({
        section_selector: '#content h2',
        menu_selector: '.semaine_container',
        scroll_container: 'window',
        top_offset: $('header').height(),
    })
    // on mobile, if we have too many items, we shorten the name
    if ($(window).width() < 640 && $('.semaine_container li').length > 4) $('.semaine_container li').each(function() {$(this).text($(this).text().substring(0, 3))})
    
    // transform <p> tags that begin with a time in a nice way
    $('p').each(function() {
        let texte = $(this).text();
        if (res = /^([0-9]{3,4})\s+(.*)$/.exec(texte)) {
            let h = res[1].substring(0, res[1].length -2);
            let m = res[1].substring(res[1].length -2);
            $(this).addClass('ligne_horaire');
            $(this).html(`<span class="heure">
                ${h}<sup>${m}</sup>
            </span>${res[2]}`)
        }
    })

    // on desktop, we show arrows in the square list
    if ($(window).width() > 640) init_squares_arrows();


    // ==================================
    // journee type animation
    // ==================================
    let top_offset = $('ul.horaires').eq(0).offset().top;
    let texte = $('h4.subtitle').eq(0).text();
    $('h4.subtitle').eq(0).text('');
    $('ul.horaires').eq(0).css({opacity: 0});

    let animation_done = false;
    jQuery(window).scroll(function() {
        if (animation_done) return;
        if (top_offset - $(this).scrollTop() < 450) {
            animation_done = true;
            Typewriter($('h4.subtitle').eq(0), {
                _text: texte,
                complete: _ => $('ul.horaires').eq(0).animate({opacity: 1}, 300)
            })
        }
    })

})

function Typewriter(el, opt = {}) {
    if (!opt._i) opt._i = 1;
    if (!opt._text) {
        opt._text = el.text();
        el.text('');
    } else if (opt._text.length <= opt._i-1) {
        el.html(opt._text);
        if (opt.complete && typeof opt.complete == 'function') opt.complete();
        return;
    }

    setTimeout(_ => {
        el.html(opt._text.substring(0, opt._i) + '<span style="margin-left:2px;font-weight:bold">|</span>');
        opt._i++;
        Typewriter(el, opt)
    }, 30 + Math.random()*40);
}


function init_squares_arrows() {
    // ==================================================
    // initialize the list of squares
    // ==================================================
    $('.carres_container .arrow_left').hide();

    // set onscroll events
    $('.carres_container .carres_list').each(function() {
        let hidden_width = 50; $(this).find('.carre').each(function() {hidden_width += $(this).outerWidth()})
        let arrow_left = $(this).parent().find('.arrow_left');
        let arrow_right = $(this).parent().find('.arrow_right');
        $(this).scroll(function() {
            if ($(this).scrollLeft() == 0) {
                arrow_left.hide();
                arrow_right.show();
            } else if ($(this).scrollLeft() + $(this).width() >= hidden_width) {
                arrow_left.show();
                arrow_right.hide();
            } else {
                arrow_left.show();
                arrow_right.show();
            }
        })
    })

    // set on click events
    $('.carres_container .arrow_right').click(function() {
        let carre_list = $(this).closest('.carres_container').find('.carres_list');
        console.log('scrollRight', carre_list.length, carre_list.scrollLeft());
        carre_list.animate( { scrollLeft: carre_list.scrollLeft() + 150}, 300);
    })
    $('.carres_container .arrow_left').click(function() {
        let carre_list = $(this).closest('.carres_container').find('.carres_list');
        console.log('scrollLeft', carre_list.length, carre_list.scrollLeft());
        carre_list.animate( { scrollLeft: carre_list.scrollLeft() - 150}, 300);
    })
}