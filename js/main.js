jQuery(document).ready(function($) {

    // =============================================
    // create dropdown effect for language menu
    // =============================================

    $('.menu_langues > ul > li')
        .mouseenter( function() { $(this).find('ul.sub-menu').show(200) })
        .mouseleave( function() { $(this).find('ul.sub-menu').hide(200) });

    // =============================================
    // load desktop images if needed
    // =============================================

    if ($(window).width() > 640) {
        $('[data-img-desktop]').each(function() {
            $img_url = $(this).attr('data-img-desktop');
            if ($img_url.length < 3 && $(this).attr('data-img-mobile') != '') $img_url = $(this).attr('data-img-mobile');
            $(this).css({
                'background': "url('" + $img_url + "')",
                'background-position': 'center',
                'background-size': 'cover',
                'background-repeat': 'no-repeat',
            })
        })
    } else {
        $('[data-img-mobile]').each(function() {
            $img_url = $(this).attr('data-img-mobile');
            console.log('got mobile url = ', $img_url, $(this).attr('data-img-desktop'))
            if ($img_url.length < 3 && $(this).attr('data-img-desktop') != '') $img_url = $(this).attr('data-img-desktop');
            console.log('finally', $img_url)
            $(this).css({
                'background': "url('" + $img_url + "')",
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

    function onscroll(delta) {
        if (delta == 0) return;
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
        if (el.attr('data-text').length > 0) {
            opt._text = el.attr('data-text')
        } else {
            opt._text = el.text();
            el.text('');
        }
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

    function new_cbk(e) {
        var e = window.event || e; // old IE support
        let delta = (e && (e.wheelDelta || e.detail)) ? Math.max(-1, Math.min(1, (e.wheelDelta || -e.detail))) : 0;
        cbk(delta)
    }

    // IE9, Chrome, Safari, Opera
    window.addEventListener("mousewheel", new_cbk, false);
    // Firefox
    window.addEventListener("DOMMouseScroll", new_cbk, false);

    // mobile
    let touch_start = 0;
    window.addEventListener('touchstart', e => {
        touch_start = e.changedTouches[0].pageY;
    }, false);
    window.addEventListener('touchend', e => {
        let delta = e.changedTouches[0].pageY - touch_start;
        cbk(delta)
    });
}