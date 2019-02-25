jQuery(document).ready(function($) {
    // burger should be white
    $('#menu .burger').first().removeClass('black');

    // scroll
    $('.types_infos li').each(function(ind) {
        let curr_li = $(this);
        curr_li.click(function() {
            $(this).animate({
                left: ind*25+"vw"
            }, 300)
        })
    })

})