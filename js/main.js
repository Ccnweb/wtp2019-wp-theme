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
                'background': `center / cover no-repeat url(${$img_url})`,
            })
        })
    } else {
        $('[data-img-mobile]').each(function() {
            let img_url = $(this).attr('data-img-mobile');
            if (img_url.length < 3 && $(this).attr('data-img-desktop') != '') img_url = $(this).attr('data-img-desktop');
            $(this).css({
                'background': `center / cover no-repeat url(${img_url})`,
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


    // ============================================
    // Startup cleanup
    // ============================================
    clear_php_console_logs();
})


function Typewriter(el, opt = {}) {
    /**
     * Creates an animation effect on jQuery element el, as if someone was typing the text
     * 
     */

    if (!opt._i) opt._i = 1;
    if (!opt._text) {
        if (el.attr('data-text') && el.attr('data-text').length > 0) {
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

function init_squares_arrows() {
    // ==================================================
    //          initialize the list of squares 
    //    (like in horaires or programmation pages)
    // ==================================================
    let $ = jQuery;
    $('.carres_container .arrow_left').hide();
    $('.carres_container .arrow_right').hide();

    // set onscroll events
    $('.carres_container .carres_list').each(function() {
        let hidden_width = 50; $(this).find('.carre').each(function() {hidden_width += $(this).outerWidth()})
        let arrow_left = $(this).parent().find('.arrow_left');
        let arrow_right = $(this).parent().find('.arrow_right');

        let content_width = 0; $(this).find('.card').each(function() {content_width += $(this).width()});
        if (content_width <= jQuery(window).width()-5) return;

        arrow_right.show();
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

function get_wp_theme_option(opt_name, default_value = '') {
    /**
     * Retrieve Wordpress theme options and deals with wp instanciation problems
     */
    if (!wp || !wp.customize) {
        console.error('wp not initialized', opt_name, wp);
        return default_value;
    }
    return wp.customize.instance(opt_name).get();
}

function clear_php_console_logs() {
    /**
     * Deletes all script tags generated by lib\php_console_log
     */

    jQuery('script.php_log').remove();
}

function user_is_connected_with_rights() {
    try{ 
        let a = edit_mode;
        return edit_mode.available;
    } catch(e) {
        if(e.name == "ReferenceError") {
            return false;
        }
    }
}