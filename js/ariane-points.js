// ========================================
// Points d'Ariane verticaux pour la navigation (à déplacer dans un autre fichier)
// ========================================

function initArianePoints(section_selector = '.section', options = {}) {
    /**
     * @param string section_selector   Le sélecteur jquery pour sélectionner toutes les parties qui doivent avoir un point d'Ariane
     * 
     * ## SOMMAIRE
     * 0. Préparation des fonctions utiles pour la suite
     * 1. On récupère tous les éléments HTML correspondants à des sections (1 section = 1 point d'ariane)
     * 2. On ajoute les points d'ariane HTML au document
     * 3. On initialise la librairie qui affiche les tooltips sur chaque point d'ariane
     * 4. On contrôle le défilement pour changer de point d'ariane actif au bon moment
     * 5. We add a function to manage auto scroll if enabled
     */

    let DEBUG = false;
    function log(...args) {if (DEBUG) console.log(...args)}

    let default_options = {
        scroll_speed: 400,   // vitesse de scroll quand on clique sur un point d'Ariane
        point_style: 'fa-circle', // le style des points d'Ariane (peut être aussi une fonction ou un array d'éléments html)
        on_section_change: function(index) {}, // la fonction à appeler lorsqu'on change de point (en argument les indexes des sections impliquées)
        tooltips: '', // fonction ou liste des tooltips à afficher ou attribut HTML associé au @selection_selector pour récupérer le texte du tooltip
        auto_scroll: false, // animated auto scroll that stops only perfectly at a specific slide
        scroll_container: 'html, body',
    }
    options = Object.assign(default_options, options);
    if (options.scroll_container == 'window') options.scroll_container = window;
    let scroll_el = (typeof options.scroll_container == 'string') ? options.scroll_container : 'html, body';

    // == 0. == on prépare les fonctions qui nous aideront

    let scrolling = false; // the lock to acquire in order to scroll
    let mem_scroll_pos = jQuery(options.scroll_container).scrollTop(); // memory of the last scroll position

    // récupère le style de points d'Ariane à utiliser pour chaque slide
    function getPointStyle(section_obj, n) {
        if (typeof options.point_style == 'string') {
            if (/^fa-/gi.test(options.point_style)) return `<i class="fas ${options.point_style}"></i>`;
            else return options.point_style;
        } else if (typeof options.point_style == 'function') {
            return options.point_style(section_obj, n);
        } else {
            return options.point_style[n % options.point_style.length];
        }
    }
    // récupère le texte des tooltips
    function getTooltip(section_obj, n) {
        if (!options.tooltips) return '';
        if (typeof options.tooltips == 'function') return options.tooltips(section_obj, n);
        if (Array.isArray(options.tooltips)) return options.tooltips[n % options.tooltips.length];
        if (typeof options.tooltips == 'string') return jQuery(section_selector).eq(n).attr(options.tooltips);
        return '';
    }

    // == 1. == on récupère toutes les sections
    let my_sections = [];
    jQuery(section_selector).each(function() {
        my_sections.push({
            id: jQuery(this).attr('id'),
            top: jQuery(this).offset().top,
            height: jQuery(this).height(),
        })
    })
    // TODO sort my_sections by top

    // TODO clean all this code !
    //if (options.auto_scroll === true) return ariane_auto_scroll(my_sections, options);

    // == 2. == on ajoute les points d'Ariane au document
    let ariane_points = jQuery(`<ul class="ariane_points"></ul>`);
    let i = 0;
    for (let section of my_sections) {
        let tooltip_text = getTooltip(section, i);
        let point = jQuery(`<li data-toggle="tooltip" data-placement="left" title="${tooltip_text}"></li>`)
        point.append(getPointStyle(section, i))
        let k = i;
        point.click(function(e) {
            e.preventDefault(); e.stopPropagation();
            scrolling = true;
            log('clicking to go to ', k, section.top)
            jQuery(scroll_el).stop(true).animate( { scrollTop: section.top }, options.scroll_speed, 'swing', function() {
                mem_scroll_pos = jQuery(options.scroll_container).scrollTop();
                scrolling = false;
                log('click released scroll')
                onscroll()
                options.on_section_change(k)
            });
        });
        ariane_points.append(point)
        i++
    }
    jQuery('body').append(ariane_points)

    // == 3. == on (re)-initialise popperjs pour les tooltips
    if (jQuery('[data-toggle="tooltip"]').tooltip) jQuery('[data-toggle="tooltip"]').tooltip()

    // we call the on_change function
    let curr_section = 0;
    let init_start = jQuery(options.scroll_container).scrollTop();
    get_curr_section(init_start)
    options.on_section_change(curr_section)


    // == 4. == on contrôle le défilement pour changer le status des points au bon moment

    function get_curr_section(start) {
        let j = 0
        for (let section of my_sections) {
            let mem_section = curr_section;
            if (section.top < start+10 && start < section.top + section.height*0.5 && curr_section != j) {
                log('%c get_curr_section new section1 : '+j, 'color:#ddd;font-style:italic')
                curr_section = j
            } else if (curr_section < my_sections.length-1 && start < section.top + section.height + 10 && start > section.top + section.height*0.5 && curr_section != j+1) {
                log('%c get_curr_section new section2 : '+(j+1).toString(), 'color:#ddd;font-style:italic')
                curr_section = j+1
            }
            if (mem_section != curr_section) {
                log('get_curr_section changing point to ', curr_section)
                jQuery('ul.ariane_points > li').removeClass('active');
                jQuery(`ul.ariane_points > li:nth-child(${curr_section+1})`).addClass('active');
                options.on_section_change(curr_section)
                break;
            }
            j++
        }
    }

    function onscroll(delta) {
        if (scrolling) return;
        scrolling = true;

        let start = jQuery(options.scroll_container).scrollTop();
        log('onscroll ongoing... start=', start, "currsection="+curr_section, my_sections)
        
        get_curr_section(start)

        if (options.auto_scroll === true || options.auto_scroll.length) auto_scroll_control(delta);
        else {
            setTimeout(_ => scrolling = false, 300);
            log('onscroll released')
        }
    }
    add_scroll_listener(onscroll)

    // == 5. == we control here the auto scroll
    if (options.auto_scroll === true) jQuery(scroll_el).css('overflow-y', 'hidden');

    function auto_scroll_control(delta) {
        // we set a lock while we are guiding the scroll
        //if (scrolling) return;
        scrolling = true;
        
        // we detect scrolling direction up or down
        let start = jQuery(options.scroll_container).scrollTop();
        log('%c AS ongoing start='+start, 'color: blue;font-weight:bold')
        let section;

        if ((start > mem_scroll_pos+5 || delta < 0) && curr_section < my_sections.length-1) { // DOWN
            // if options.auto_scroll is an array of slide indexes, we should auto scroll only for these slides
            log('AS: down to ', curr_section+1)
            if (typeof options.auto_scroll != 'object' || options.auto_scroll.includes(curr_section)) {
                section = my_sections[curr_section+1];                
            } else {
                mem_scroll_pos = jQuery(options.scroll_container).scrollTop();
                scrolling = false;
            }

        } else if ((start < mem_scroll_pos || delta > 0) && curr_section > 0) { // UP
            // if options.auto_scroll is an array of slide indexes, we should auto scroll only for these slides
            log('AS: up to ', curr_section-1)
            if (typeof options.auto_scroll != 'object' || options.auto_scroll.includes(curr_section)) {
                section = my_sections[curr_section-1];
            } else {
                mem_scroll_pos = jQuery(options.scroll_container).scrollTop();
                scrolling = false;
            }

        } else {
            log('AS : nothing to do, curr_section=', curr_section)
            mem_scroll_pos = jQuery(options.scroll_container).scrollTop();
            return scrolling = false;
        }

        // we scroll automatically to the appropriate slide and release the lock
        if (!section) return log('null section', start, mem_scroll_pos, curr_section);
        log('AS: animating to ', section.top)
        jQuery(scroll_el).stop(true).animate( { scrollTop: section.top }, options.scroll_speed, 'swing', function() {
            //mem_scroll_pos = jQuery(options.scroll_container).scrollTop();
            //get_curr_section(mem_scroll_pos)
            //setTimeout(_ => {
                mem_scroll_pos = jQuery(options.scroll_container).scrollTop();
                log('%c AS: animating released, memory='+mem_scroll_pos, 'color: blue');
                get_curr_section(mem_scroll_pos)
                scrolling = false
            //}, 200);
        });
    }
    //if (options.auto_scroll === true || options.auto_scroll.length) jQuery(options.scroll_container).scroll(auto_scroll_control);
}

function add_scroll_listener(cbk) {
    function new_cbk(e) {
        var e = window.event || e; // old IE support
        e.preventDefault();
        let delta = (e && (e.wheelDelta || e.detail)) ? Math.max(-1, Math.min(1, (e.wheelDelta || -e.detail))) : 0;
        cbk(delta)
    }
    //jQuery(options.scroll_container).scroll(onscroll);

    // IE9, Chrome, Safari, Opera
    window.addEventListener("mousewheel", new_cbk, false);

    // Firefox
    window.addEventListener("DOMMouseScroll", e => new_cbk, false);

    // mobile
    let touch_start = 0;
    window.addEventListener('touchstart', e => {
        console.log('touchstart')
        touch_start = e.changedTouches[0].pageY;
    }, false);
    window.addEventListener('touchend', e => {
        console.log('touchend')
        let delta = e.changedTouches[0].pageY - touch_start;
        cbk(delta)
    });
}