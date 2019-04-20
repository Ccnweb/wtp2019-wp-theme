function init_menu_scroll(opt) {

    let opt_default = {
        section_selector:   '.section',         // jquery selector for the sections
        menu_selector:      '.menu',            // jquery selector for the menu container (preferably not the ul element but a container of the ul element)
        menu_item_selector: 'li',               // jquery selector for menu items
        menu_class:         'the-menu',         // the menu css class to be added
        focus_style:        'underline',        // the style of the element that shows in wich section we are
        scroll_container:   'html, body',       // the element in which we want to scroll
        top_offset:         0,                  // an offset to apply when scrolling to element
    }
    opt = Object.assign(opt_default, opt);
    if (opt.scroll_container == 'window') opt.scroll_container = window;
    let scroll_el = (typeof opt.scroll_container == 'string') ? opt.scroll_container : 'html, body';

    let $ = jQuery;
    let scrolling = false;

    // detect section positions
    let sections = [];
    $(opt.section_selector).each(function() {
        console.log('got section', $(this).text())
        sections.push({
            top: $(this).offset().top - opt.top_offset,
            height: $(this).height(),
        })
    })
    for (let i = 0; i < sections.length-1; i++) {
        sections[i].height = sections[i+1].top - sections[i].top;
    }
    sections[sections.length-1].height = $(document).height() - sections[sections.length-1].top;

    // add ul/li elements if they don't exist
    if (opt.focus_style == 'underline' && $(opt.menu_selector + ' ul').length == 0) {
        let menu = $(`<ul class="${opt.menu_class}"></ul>`);
        $(opt.section_selector).each(function() {
            let titre = ($(this).attr('data-title')) ? $(this).attr('data-title') : $(this).text();
            menu.append(`<li>${titre}</li>`)
        })
        $(opt.menu_selector).prepend(menu)
    }
    if (opt.focus_style == 'underline' && $(opt.menu_selector + ' .underline_bar').length == 0) {
        $(opt.menu_selector).append(`<div class="underline_bar">
            <div class="mobile_bar"></div>
        </div>`)
    }
    // we underline the first element
    let li = $(opt.menu_selector + ' ' + opt.menu_item_selector);
    $('.mobile_bar').animate({
        left: li.eq(0).position().left+'px',
        width: li.eq(0).width()+'px'
    }, 300);

    // set click event to scroll on specific section
    $(opt.menu_selector + ' ' + opt.menu_item_selector).each(function(ind) {
        let curr_li = $(this);
        curr_li.click(function() {
            scrolling = true;

            // animate menu
            if (opt.focus_style == 'underline') {
                $('.mobile_bar').animate({
                    left: $(this).position().left+'px',
                    width: $(this).width()+'px'
                }, 300);
            }

            // animate scrolling
            $(scroll_el).animate({
                scrollTop: (sections[ind].top)+'px',
            }, 300, 'swing', _ => scrolling = false);
        })
    })

    // respond to on scroll event to change section
    let curr_section = 0;
    let w_height = $(window).height();
    let marge = w_height / 10;
    $(opt.scroll_container).scroll(function() {
        if (scrolling) return;
        let start = $(this).scrollTop();
        let i = 0
        for (let section of sections) {
            let mem_section = curr_section
            if (start > section.top - marge && start < section.top + section.height - marge && curr_section != i) {
                curr_section = i
            }
            if (mem_section != curr_section) {
                scrolling = true;
                let li = $(`${opt.menu_selector} ${opt.menu_item_selector}:nth-child(${curr_section+1})`)

                // animate menu
                if (opt.focus_style == 'underline') {
                    $('.mobile_bar').animate({
                        left: li.position().left+'px',
                        width: li.width()+'px'
                    }, 300, 'swing', _ => scrolling = false);
                } else {
                    scrolling = false;
                }
                break;
            }
            i++
        }
    })
}