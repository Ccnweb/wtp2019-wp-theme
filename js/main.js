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

    jQuery('html, body').scroll(function() {
        $('#inscription').css({bottom: '30px'})
        setTimeout(_ => $('#inscription').css({bottom: '16px'}), 200)
        /* TweenMax.to("#inscription", 0.1, {
            y:-50, 
            ease:Bounce.easeOut, 
            yoyoEase:Power0.easeOut,  
            repeat:1, 
            repeatDelay:0,
            onComplete: _ => animating_inscription_button = false,
        }); */
    })
})