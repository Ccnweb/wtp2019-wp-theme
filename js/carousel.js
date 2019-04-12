jQuery(document).ready(function($){

    /* let start_pos;
    let slider = $('.carousel');
    slider.on('mousedown touchstart', function(e) {
        e.preventDefault();
        e.stopPropagation();
        start_pos = e.pageX || e.originalEvent.touches[0].pageX;
        console.log('on', e, start_pos)
        $(document).on('mouseup touchend', onTouchEnd);
    })

    function onTouchEnd(e) {
        let end_pos = e.pageX || e.originalEvent.touches[0].pageX;
        console.log('off', e, end_pos, end_pos-start_pos)
        slider.animate({scrollLeft: slider.scrollLeft() - end_pos + start_pos })
        $(document).off('mouseup touchend', onTouchEnd);
    } */


    /* $('.carres_list').owlCarousel({
        dots: false,
        responsive: {
            0: {items:0},
            600: {items:10},
            1000: {items:20},
        }
    }); */
    $('.carousel').owlCarousel({
        loop:true,
        margin:0,
        autoplay: !user_is_connected_with_rights(),
        autoplayTimeout: 5000,
        responsiveClass:true,
        responsive:{
            0:{
                items:1,
                nav:true
            },
            600:{
                items:3,
                nav:false
            },
            1000:{
                items:3,
                nav:true
            }
        }
    })
});