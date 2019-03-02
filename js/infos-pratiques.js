jQuery(document).ready(function($) {
    // burger should be white
    $('#menu .burger').first().removeClass('black');

    // ====================================
    // SCROLLING ANIMATIONS
    // ====================================

    init_menu_scroll({
        section_selector: '#content .bg-title',
        menu_selector: '.types_infos_container',
        top_offset: $('.types_infos_container ul').height() + $('header').height(),
        //scroll_container: 'body',
    })

})