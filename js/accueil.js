// ========================================
//       GENERER LES FLASH WORDS
// ========================================



let flash_words = []
async function flash_words_load() {
    
    // on récupère l'ID de la catégorie de l'article "flash words"
    let categories = await fetch('/wp-json/wp/v2/categories').then(response => response.json());
    let flash_category_id = categories.find(cat => cat.name == 'flash-words').id;
    
    // on récupère tous les articles de cette catégorie (il n'y en a qu'un)
    let flash_articles = await fetch('/wp-json/wp/v2/posts?categories=' + flash_category_id).then(response => response.json());
    
    // dans l'html de l'article, on récupère tous les textes dans les <p> et on les met dans l'array flash_words
    let html = $('<div>' + flash_articles[0].content.rendered + '</div>').find('p')
    html.each(function() {flash_words.push($(this).text())})
    
    // on initialise l'animation des flash words
    let i = 0;
    let flash_words_delay = get_wp_theme_option('flashing_words_speed', 200); // ms
    console.log('flash speed', flash_words_delay);
    setInterval(_ => {
        $('#intro_magic_words').text(flash_words[i]);
        i = (i+1) % flash_words.length;
    }, flash_words_delay)
}


// ========================================
//       GENERER LES TEMOIGNAGES
// ========================================

/* function fine_tune_temoignages() {
    let descriptions = jQuery('.carousel .post_type__temoignages p:nth-child(2)');
    descriptions.each(function() {
        $(this).html("<span>" + $(this).html() + "</span>")
    })
} */

//let temoignages_data = [];

/* async function temoignages_load_data() {
    // on récupère tous les témoignages (uniquement le texte et l'image, pas tout le html)
    temoignages_json = await fetch('/wp-json/wp/v2/temoignages').then(response => response.json());
    for (let temoignage of temoignages_json) {
        let html = $('<div>' + temoignage.content.rendered + '</div>');
        let img = html.find('img').attr('src');
        let texte = html.find('p').html();
        let img_ordi = (temoignage.meta['_wtpmediaupload_img_metakey']) ? temoignage.meta['_wtpmediaupload_img_metakey'][0] : '';
        temoignages_data.push({img, texte, img_ordi});
    }
    $('#temoignages').find('.img_content').removeClass('bg-arrows');
}
function temoignages_animate() {
    let i = 0;
    console.log('initialising témoignage animation');
    let img_field = ($(window).width() > 700) ? 'img_ordi': 'img'; 

    function show_temoignage() {
        console.log('show temoignage');
        let martyr = temoignages_data[i];
        if (!img_field || !martyr || !martyr[img_field]) return;
        let img_url = (martyr[img_field] != '') ? martyr[img_field]: martyr.img;
        let root = $('.temoignages').eq(0);
        root.html(`<div class="img_content bg-arrows" style="background-size: cover;"></div>
        <div class="golden_box">
            <span class="quote"><i class="fas fa-spinner fa-spin"></i></span>
        </div>`)
        root.fadeOut('slow', function() {
            root.find('.img_content').css({
                'background': `url('${img_url}')`, 
                'background-size': 'cover',
                'background-position': 'center'
            })
            root.find('.quote').html(martyr.texte);
            root.fadeIn('slow');
        })
        i = (i+1) % temoignages_data.length;
    }

    show_temoignage();
    let temoignages_duree_vizu = get_wp_theme_option('temoignages_duration', 6000); // ms
    setInterval(show_temoignage, temoignages_duree_vizu);
} */

// ========================================
//       GENERER LES CARRES
// ========================================

let flipcarres_animations = [];
let carres_data = [];

async function carres_load_data() {
    carres_data = [];
    let lang = document.documentElement.lang.substr(0, 2);
    console.log('lang', lang);
    carres_data = await fetch('/wp-json/wtp/v1/carres/' + lang).then(response => response.json());
    return carres_data;
    /* for (let carre of carres_json) {
        carres_data.push({
            titres: [carre['meta']['_wtpcarre_adj_metakey'][0], carre['title']['rendered']],
            descr: carre['meta']['_wtpcarre_descr_metakey'][0],
            link: carre['meta']['_wtpcarre_pagelink_metakey'][0],
            img: (carre['_links']['wp:featuredmedia']) ? carre['_links']['wp:featuredmedia'][0]['href']: ''
        });
    } */
    // on récupère les vraies url des images
    /* let plist = []
    for (let carre of carres_data) {
        if (carre['img'] !== '') plist.push(fetch(carre['img']).then(response => response.json()));
        else plist.push(Promise.resolve(''))
    }
    return Promise.all(plist).then(arr_img => {
        for (let i = 0; i < arr_img.length; i++) {
            carres_data[i].img = (arr_img[i]['guid']) ? arr_img[i]['guid'].rendered: '';
        }
    }) */
}

function build_carres() {
    if ($(window).width() < 600) return build_carres_mobile();
    build_carres_desktop()
}

function build_carres_desktop() {
    console.log('carres desktop')
    let carres_colors = ['black', 'gold', 'white'];
    let root_carres = $('.carres_propositions').eq(0);
    root_carres.html('');
    for (let i = 0; i < carres_data.length && i < 6; i++) {
        let data = carres_data[i];
        let link = (data.link && data.link != 'none') ? '/' + data.link : '';
        let carre_img = $(`<div class="carre" style="background: url('${data.img}');background-size: cover;background-position:center;"></div>`);
        let carre_txt = $(`<div class="carre arrow ${carres_colors[i % carres_colors.length]}">
                <div class="ligne1"><a href="${link}">${data.titres[0]}</a></div>
                <div class="ligne2">${data.titres[1]}</div>
                <div class="detail">${data.descr}</div>
            </div>`);
        root_carres.append(carre_img);
        root_carres.append(carre_txt);
    }
}

function build_carres_mobile() {
    let carres_colors = ['black', 'gold', 'white'];
    let root_carres = $('.carres_propositions').eq(0);
    root_carres.html('');
    for (let i = 0; i < carres_data.length-1; i+=2) {
        let data_front = carres_data[i];
        let data_back = carres_data[i+1];
        let face_image1 =   `<div class="carre" style="background: url('${data_front.img}');background-size: cover;background-position:center;"></div>`
        let face_texte1 =   `<div class="ligne1"><a href="${data_back.link}">${data_back.titres[0]}</a></div>
                            <div class="ligne2">${data_back.titres[1]}</div>
                            <div class="detail">${data_back.descr}</div>`
        let face_image2 =   `<div class="carre" style="background: url('${data_back.img}');background-size: cover;background-position:center;"></div>`
        let face_texte2 =   `<div class="ligne1">${data_front.titres[0]}</div>
                            <div class="ligne2">${data_front.titres[1]}</div>
                            <div class="detail">${data_front.descr}</div>`

        let carre = $(  `<div class="carre flip-container">
                            <div class="flipper trlong">
                                <div class="front ${(i % 4 != 0) ? 'carre arrow ' + carres_colors[ i % carres_colors.length ]: ''}">
                                    ${(i % 4 == 0) ? face_image1: face_texte1}
                                </div>
                                <div class="back ${(i % 4 == 0) ? 'carre arrow ' + carres_colors[ i % carres_colors.length ]: ''}">
                                    ${(i % 4 == 0) ? face_texte1: face_image1}
                                </div>
                            </div>
                        </div>
                        <div class="carre flip-container">
                            <div class="flipper trlong">
                                <div class="front ${(i % 4 == 0) ? 'carre arrow ' + carres_colors[ i % carres_colors.length ]: ''}">
                                    ${(i % 4 == 0) ? face_texte2: face_image2}
                                </div>
                                <div class="back ${(i % 4 != 0) ? 'carre arrow ' + carres_colors[ i % carres_colors.length ]: ''}">
                                    ${(i % 4 == 0) ? face_image2: face_texte2}
                                </div>
                            </div>
                        </div>`);
        root_carres.append(carre)
    }

    // on anime les carrés construits
    animateFlipCarres()
}

function build_face_texte(data) {
    return `<div class="back carre arrow ${carres_colors[ i % carres_colors.length ]}">
        <div class="ligne1">${data.titres[0]}</div>
        <div class="ligne2">${data.titres[1]}</div>
        <div class="detail">${data.descr}</div>
    </div>`
}

// SLIDE CARRES DORES : animer flip carres
function animateFlipCarres() {
    $('.flip-container').each(function(ind) {
        setTimeout( _ => $(this).toggleClass('flipped'), 100);
    });
    if (flipcarres_animations.length) return;
    flipcarres_animations.push("done")
    console.log('animating carrés')
    
    $('.flip-container').each(function(ind) {
        setTimeout(_ => {
            flipcarres_animations.push(setInterval(_ => $(this).toggleClass('flipped'), 6000))
        }, ind * 700 - (ind % 2) * 300 + Math.round(50 * Math.random()))
    })
}

// ========================================
//             SETUP MODALS
// ========================================

function setupModals() {
    let close_btn = $('<i class="fas fa-times close"></i>');
    close_btn.click(function() {
        $('.modal').hide(200);
    })
    $('.modal').append(close_btn)

    $('*[data-modal-target]').click(function() {
        console.log('click button modal', $('#' + $(this).attr('data-modal-target')).length)
        $('#' + $(this).attr('data-modal-target')).show(200);
    })
}




jQuery(document).ready(function(){

    // on met ou non la video en background
    /* console.log('WIDTH', $(window).width(), $(document).width())
    let carre_size = $(window).width()/2+'px';
    $('.carre').css({width: carre_size, height: carre_size}) */

    // ========================================
    //       INITIALIZE CONTENT
    // ========================================
    console.log('wp', wp.customize);
    flash_words_load();
    //temoignages_load_data().then(_ => console.log('temoignages loaded ! Grazie Signore !', temoignages_data)).catch(e => console.log('ERROR loading témoignages', e));
    setupModals();

    // typewriter effect
    let lefestival_animated = false;
    let lefestival_el = $('.section:nth-child(2) .subtitle').eq(0);
    let lefestival_texte = lefestival_el.text(); lefestival_el.text('');
    $('.section:nth-child(2) .wp-block-button').css({opacity: 0});
    function typewriter_lefestival() {
        if (lefestival_animated) return;
        lefestival_animated = true;
        Typewriter(lefestival_el, {
            _text: lefestival_texte,
            complete: _ => $('.section:nth-child(2) .wp-block-button').animate({opacity: 1}, 500)
        })
    }

    // ========================================
    //       INITIALIZE SLIDE SCROLL
    // ========================================
    initArianePoints('.section', {
        scroll_container: 'window',
        on_section_change: dotsBW,
        auto_scroll: true,
    })

     // gère l'affichage des points de navigation verticaux (blancs sur fond noir et noirs sur fond blanc)
     function dotsBW(ind) {
        $('#menu .burger').first().removeClass('black')
        if (ind == 0 || ind == 2) return $('ul.ariane_points').removeClass('black');
        if (ind == 1 || ind == 4) { // texte intro
            $('#menu .burger').first().addClass('black')
        }
        if (ind != 5) $('.ariane_points').addClass('black');
        if (ind == 5) $('.ariane_points').removeClass('black');

        // animations typewriter
        if (ind == 1) typewriter_lefestival()
     }

    // SLIDE 1 : animation du logo next step
    setTimeout(_ => $('.svg_border').addClass('animate'), 700)
    setTimeout(_ => {
        $('#intro_logo').addClass('animate');
        $('#next_step').addClass('animate')
    }, 1800)

    // SLIDE CARRES : CHARGER LA DATA ET CONSTRUIRE LE HTML
    carres_load_data().then(_ => console.log("carres_data", carres_data)).then(build_carres)

    // SLIDE TEMOIGNAGES ANIMATION
    //temoignages_animate()

});


// ========================================
//       CLICK INSCRIPTION
// ========================================

let inscription_state = 0;
function inscription() {
    console.log('click inscription current state = ', inscription_state)
    if (inscription_state == 0 && $(this).width() < 100) {
        inscription_state == 1
        $(this).width(200)
        $(this).addClass('txt-black')
    } else {
        console.log('go to inscription')
        $(this).width(20)
        $(this).removeClass('txt-black')
        // it's the job of popup maker extension to bring the popup inscription
        PUM.open(280)
    }
}

// SLIDE 1 : open/close inscription button
/* function openInscriptionButton() {
    $('#inscription').addClass('openwidth');
    setTimeout(_ => $('#inscription').addClass('openheight'), 500)
} */