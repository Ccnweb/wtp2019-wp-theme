jQuery(document).ready(function($) {
    
    // initialize menu scrolling
    init_menu_scroll({
        section_selector: '.menu-scroll h2',
        menu_selector: '.semaine_container',
        scroll_container: 'window',
        top_offset: $('header').height(),
    })
    // on mobile, if we have too many items, we shorten the name
    if ($(window).width() < 640 && $('.semaine_container li').length > 4) $('.semaine_container li').each(function() {$(this).text($(this).text().substring(0, 3))})
    
    // transform <p> tags that start with a time (like 900 or 1430) in a nice way
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
    // Golden time : shows time in gold
    // ==================================
    $('.golden-time h3, .golden-time li, h3.golden-time, li.golden-time').each(function() {
        let texte = $(this).text();
        if (/^[0-9]{2,3}/gi.test(texte)) {
            let regex_res = /^([0-9]{3,4})\s+(.*)$/gi.exec(texte);
            if (regex_res && regex_res.length > 2) {
                let hours = regex_res[1].substr(0, regex_res[1].length-2);
                let minutes = regex_res[1].substr(regex_res[1].length-2);
                let horaire = `<span class="txt-gold">${hours}<sup>${minutes}</sup></span>`;
                $(this).html(horaire + ' ' + regex_res[2]);
            }
        }
    })


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
        if (top_offset - $(this).scrollTop() < 550) {
            animation_done = true;
            Typewriter($('h4.subtitle').eq(0), {
                _text: texte,
                complete: _ => $('ul.horaires').eq(0).animate({opacity: 1}, 300)
            })
        }
    })


})