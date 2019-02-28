jQuery(document).ready(function($) {

    // initialise menu items
    let items = [];
    $('#content h2').each(function() {items.push($(this).text())})
    $('.semaine').append('<li>' + items.join('</li><li>') + '</li>')
    // if we have too many items, we shorten the name
    if ($('.semaine li').length > 4) $('.semmaine li').each(function() {$(this).text($(this).text().substring(0, 3))})

    // initialize menu scrolling
    init_menu_scroll({
        section_selector: '#content h2',
        menu_selector: '.semaine_container',
        scroll_container: 'body',
    })
    
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
            } else if ($(this).scrollLeft() + $(this).width() >= hidden_width) {
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
        carre_list.animate( { scrollLeft: carre_list.scrollLeft() + 50}, 300);
    })
    $('.carres_container .arrow_left').click(function() {
        let carre_list = $(this).closest('.carres_container').find('.carres_list');
        console.log('scrollLeft', carre_list.length, carre_list.scrollLeft());
        carre_list.animate( { scrollLeft: carre_list.scrollLeft() - 50}, 300);
    })

})