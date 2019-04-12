let wp_posts = {};
let original_texts = {};
let updating_post = {}
//let leaf_nodes;

let translation_mode_param = {
    'button_html': null
};


jQuery(document).ready(function() {
    toastr.info("Tu es connecté, j'ai désactivé certains effets visuels pour faciliter l'édition de la page");
})

let translation_mode_active = false;
function toggle_translation() {
    let target = jQuery('#activate_translation');

    if (translation_mode_active) {
        close_translation();
        target.removeClass('active');
        target.html(translation_mode_param.button_html);
        translation_mode_active = false;
    } else {
        target.addClass('active');
        init_translation();
        translation_mode_param.button_html = target.html();
        target.html('You can now start editing text in this page');
        translation_mode_active = true;
    }
}

function init_translation() {
    console.log('Activating translation mode...');
    jQuery('.edit_post_link').show(100);
    let activation_ongoing = false;

    //leaf_nodes = get_leaf_nodes();
    
    // select leaf nodes : all elements which don't have complex child nodes
    jQuery(document).click(async function(e) {
        let target = jQuery(e.target);
        if (!is_editable(target) || activation_ongoing) return;
        
        if (target.attr('contenteditable') == 'true') return;
        e.preventDefault();
        e.stopPropagation();
        
        let wp_posts_id;
        
        if (!target.attr('parent-post') || !wp_posts[target.attr('parent-post')]) {
            
            let parent_post_info = get_parent_post_info(target);
            if (!parent_post_info) return console.log('Could not find parent post of ', target);
            activation_ongoing = true;
            let parent_slug = parent_post_info.slug;
            let parent_post_type = parent_post_info.post_type;
            
            wp_posts_id = parent_post_type+"@"+parent_slug;
            original_texts[wp_posts_id] = target.html();

            if (!wp_posts[wp_posts_id]) {
                let bg_color = target.css("background-color");
                target.css({'background-color': 'rgba(0,0,0,0.2)'});
                let spinner = jQuery('<i class="edit_spinner_tmp fas fa-spinner fa-spin" style="position:relative;color:white"></i>');
                target.append(spinner);

                // retrieve the parent post content
                let res = await get_post_by_slug(parent_post_type, parent_slug);
                if (!res.success) {
                    activation_ongoing = false;
                    return console.error('Could not retrieve parent post content', res);
                }
                let parent_post = res.data;

                wp_posts[wp_posts_id] = parent_post;
                target.css({'background-color':bg_color});
                target.find('.edit_spinner_tmp').remove();
            }

            target.attr('parent-post', wp_posts_id);
            target.attr('contenteditable', true);
            
            activation_ongoing = false; // we release the translation init lock
        }

        target[0].removeEventListener('keypress', keypress_fun);
        target.addClass('has-edit-keypress-listener');
        target[0].addEventListener('keypress', keypress_fun);
    });

    console.log('%c Translation mode activated', 'color:green');
}

async function keypress_fun(e) {
    if(e.which != 13) return;
    e.preventDefault();
    e.stopPropagation();
    let target = jQuery(e.target);

    let wp_posts_id = target.attr('parent-post');
    let original_text = original_texts[wp_posts_id];
    
    let parent_post = wp_posts[wp_posts_id];
    if (updating_post[parent_post.ID]) return console.log("Please wait, post is already updating");
    updating_post[parent_post.ID] = true;
    console.log('updating post...', updating_post[parent_post.ID], parent_post);

    let new_text = target.html();
    if (new_text == original_text) {
        updating_post[parent_post.ID] = false;
        return console.log('nothing to update for the post '+parent_post.ID);
    }

    let new_data = {custom_fields: {}};

    // check if we have to update the title
    if (parent_post.post_title.includes(original_text)) {
        new_data.post_title = parent_post.post_title.replace(original_text, new_text);
    }

    // check if we have to update the content
    if (parent_post.post_content.includes(original_text)) {
        new_data.post_content = parent_post.post_content.replace(original_text, new_text);
    }

    // check if we have to update a custom field
    for (let meta_key in parent_post.custom_fields) {
        if (parent_post.custom_fields[meta_key].includes(original_text)) {
            new_data.custom_fields[meta_key] = parent_post.custom_fields[meta_key].replace(original_text, new_text);
            break;
        }
    }
    
    if (new_data.post_title || new_data.post_content || !isEmpty(new_data.custom_fields)) {
        console.log('updating post with new_data = ',new_data);
        let bg_color = target.css("background-color");
        target.css({'background-color': 'rgba(0,0,0,0.2)'});
        let spinner = jQuery('<i id="edit_spinner_tmp" class="fas fa-spinner fa-spin" style="position:relative;color:white"></i>');
        target.append(spinner);

        let new_parent_post = await update_post(parent_post.ID, new_data);
        
        wp_posts[wp_posts_id] = new_parent_post;
        original_texts[wp_posts_id] = new_text;
        target.css({'background-color':bg_color});
        target.find('#edit_spinner_tmp').remove();
        updating_post[parent_post.ID] = false;
    } else {
        updating_post[parent_post.ID] = false;
        target.text(original_text);
        return console.error('could not find matching text in parent post title and content', original_text, parent_post);
    }
    
}

function get_parent_post_info(target) {
    /**
     * @param   target jquery element
     * @return  {post_type: 'post', slug: 'le-festival'}
     */

    // find parent post
    let parent_post_id = target.closest(`[data-post-id^="post__"]`); //[id^="post__"]
    if (parent_post_id.length < 1) return console.error("Could not find parent post");

    // find parent post slug
    let parent = new RegExp(`^post__([^@]+)@(.+)$`).exec(parent_post_id.attr('data-post-id'));
    if (parent.length < 3) return console.error('Could not find parent post slug ', 'post_type = '+post_type, "parent_slug result = ", parent);
    
    parent_post_type = parent[1];
    parent_slug = parent[2];
    return {post_type: parent_post_type, slug: parent_slug};
}

function is_editable(jel) {
    if (jel.attr('wtp-editable') == "true") return true;
    if (jel.attr('wtp-editable') == "false" || jel.hasClass('edit_post_link')) return false;
    if (jel.html() == '') return false;
    if (jel.children().length > 3) return false; // too many child nodes
    if (jel.find(':has(div,ul,li,section,main,table,tr,p,button,label)').length > 0) return false;
    return true;
}

function close_translation() {
    jQuery('.edit_post_link').hide(100);
    jQuery('[contenteditable]').attr('contenteditable', false)
    let elements = document.getElementsByClassName('has-edit-keypress-listener')
    for (let i = elements.length-1; i >= 0; i--) {
        elements.item(i).removeEventListener('keypress', keypress_fun);
        elements.item(i).classList.remove('has-edit-keypress-listener');
    };
    jQuery(document).off('click');
    updating_post = {};
    wp_posts = {};

}

async function get_post_by_slug(post_type, slug) {
    /**
     * Retrieves the post in a special wtp format : array with following keys :
     * - ID
     * - post_title
     * - post_content (raw, not rendered)
     * For more info you should look at "rest endpoints/get_raw_post.php"
     * 
     */

    //return fetch('/wp-json/wp/v2/posts?slug='+slug).then(response => response.json());
    return fetch(`/wp-json/wtp/v1/post/${post_type}/${slug}`, {
        credentials: 'include',
        headers: new Headers({
            'X-WP-Nonce': translationAjaxData.nonce,
        }),
    }).then(response => response.json());
}

function update_post(id, data) {
    data._wpnonce = translationAjaxData.nonce;
    return new Promise((resolve, reject) => {
        jQuery.ajax({
            method: 'POST',
            //url: '/wp-json/wp/v2/posts/'+id,
            url: '/wp-json/wtp/v1/post_raw_post/'+id,
            dataType: "json",
            data,
            success: (data) => {
                wp_posts[data.slug] = data;
                console.log('success saving post', data);
                resolve(data);
            },
            error: (data) => {
                console.log('error saving post', data);
                reject(data);
            },
        });
    })
}

function html_to_wtp_markdown(html_str) {
    return html_str.replace(/\<br\>/g, '§');
}

function isEmpty(obj) {
    // tells is an object is empty or not
    for(var key in obj) {
        if(obj.hasOwnProperty(key))
            return false;
    }
    return true;
}