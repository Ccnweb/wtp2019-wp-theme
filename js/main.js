jQuery(document).ready(function($) {

    console.log('window', $(window).width())
    if ($(window).width() > 640) {
        $('[data-img-desktop]').each(function() {
            console.log('in', $(this).attr('data-img-desktop'))
            $(this).css({
                'background': "url('" + $(this).attr('data-img-desktop') + "')",
                'background-position': 'center',
                'background-size': 'cover'
            })
        })
    }
})