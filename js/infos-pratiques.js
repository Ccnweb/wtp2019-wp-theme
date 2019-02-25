jQuery(document).ready(function($) {
    // burger should be white
    $('#menu .burger').first().removeClass('black');

    // ====================================
    // SCROLLING ANIMATIONS
    // ====================================

    // detect section positions
    let scrolling = false;
    let sections = [];
    $('.info_part').each(function() {
        sections.push({
            top: $(this).offset().top,
            height: $(this).height(),
        })
    })

    // set click event to scroll on specific section
    let top_offset = $('header').eq(0).height() + $('.types_infos_container').eq(0).height();
    $('.types_infos li').each(function(ind) {
        let curr_li = $(this);
        curr_li.click(function() {
            scrolling = true;
            $('.mobile_bar').animate({
                left: $(this).position().left+'px',
                width: $(this).width()+'px'
            }, 300, 'swing', _ => scrolling = false);
            $('html, body').animate({
                scrollTop: (sections[ind].top - top_offset)+'px',
            }, 300);
        })
    })

    // respond to on scroll event to change section
    let curr_section = 0;
    let w_height = $(window).height();
    jQuery('html, body').scroll(function() {
        if (scrolling) return;
        let start = $(this).scrollTop();
        let i = 0
        for (let section of sections) {
            let mem_section = curr_section
            if (start > section.top - w_height / 2 && start < section.top + section.height - w_height / 2 && curr_section != i) {
                curr_section = i
            }
            if (mem_section != curr_section) {
                scrolling = true;
                let li = $(`ul.types_infos > li:nth-child(${curr_section+1})`)
                $('.mobile_bar').animate({
                    left: li.position().left+'px',
                    width: li.width()+'px'
                }, 300, 'swing', _ => scrolling = false);
                break;
            }
            i++
        }
    })

})