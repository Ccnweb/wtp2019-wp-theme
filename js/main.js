jQuery(document).ready(function($) {

    // =============================================
    // load desktop images if needed
    // =============================================

    if ($(window).width() > 640) {
        $('[data-img-desktop]').each(function() {
            $(this).css({
                'background': "url('" + $(this).attr('data-img-desktop') + "')",
                'background-position': 'center',
                'background-size': 'cover',
                'background-repeat': 'no-repeat',
            })
        })
    }

    // =============================================
    // we animate the subscription button
    // =============================================

    $('#inscription').css({bottom: '50px'})
    setTimeout(_ => $('#inscription').css({bottom: '16px'}), 200)

    function onscroll() {
        $('#inscription').css({bottom: '32px'})
        setTimeout(_ => $('#inscription').css({bottom: '16px'}), 200)
    }
    //jQuery(window).scroll(onscroll)
    onMouseWheel(onscroll)
})


function Typewriter(el, opt = {}) {
    /**
     * Creates an animation effect on jQuery element el, as if someone was typing the text
     * 
     */

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
        el.html(opt._text.substring(0, opt._i) + '<span style="margin-left:3px;font-weight:bold">|</span>');
        opt._i++;
        Typewriter(el, opt)
    }, 30 + Math.random()*40);
}

function onMouseWheel(cbk) {
    /**
     * Calls a function when mousewheel
     */

    // IE9, Chrome, Safari, Opera
    window.addEventListener("mousewheel", cbk, false);
    // Firefox
    window.addEventListener("DOMMouseScroll", cbk, false);
}