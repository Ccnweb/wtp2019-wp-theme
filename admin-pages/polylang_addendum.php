<?php
include_once(get_template_directory() . '/lib/lib.php'); use wtp as wtp;
//include_once(get_template_directory() . '/lib/CcnHTMLObj.php');

add_action('admin_menu', 'wtp_admin_page');

function wtp_admin_page() {
    /* $page_title = 'Top Level Menu';
    $page_tab_title = $page_title;
    $required_capability = 'manage_options'; // capability required to access the menu
    $slug = 'wtp2019/wtp-admin-page.php';
    $position_in_leftbar = 6; // 5 = Posts */

    //add_menu_page( $page_tab_title, $page_title, $required_capability, $slug, 'wtp_admin_page_gen_content', 'dashicons-tickets', $position_in_leftbar  );
    //add_submenu_page('wtp-options-general.php', 'wpautop-control', 'wpautop control', 'manage_options', 'wpautop-control-menu', 'wpautop_control_options');
    add_submenu_page( 'mlang', 'WTP traductions', 'WTP traductions', 'manage_options', 'wtp/wtp-polylang.php', 'wtp_admin_page_gen_content' );

    
}

function wtp_admin_page_gen_content() {
    if (isset($_POST['target_language'])) {
        $prepare_language_result = wtp_prepare_translation($_POST['target_language']);
    }

    // available languages
    $available_languages = pll_languages_list();
    $dropdown = wtp\gen_html_dropdown($available_languages, ['id' => 'target_language', 'name' => 'target_language']);

    //available post_types
    $post_types = get_post_types([
        'public' => true,
    ]);

    $post_types_html = new CcnHtmlObj('ul');
    foreach ($post_types as $pt => $pt_label) {
        if (!pll_is_translated_post_type($pt)) continue; // don't show post types that are not managed by polylang translation
        $option = '<li><input type="checkbox" id="'.$pt.'" name="'.$pt.'" checked> <label for="'.$pt.'">'.$pt_label.'</label></li>';
        $post_types_html->append($option);
    }
    
    ?>
	<div class="wrap">
        <h1><?= esc_html(get_admin_page_title()); ?></h1>
        <form method="post">
        <p>
            <label for="target_language">En quelle langue souhaites-tu traduire le site ?</label>
            <?= $dropdown; ?>
        </p>
        <p>
            <p>Quels types de contenus veux-tu traduire ?</p>
            <?= $post_types_html->toString(); ?>
        </p>
        <button type="submit">Préparer le site pour la traduction</button>
        </form>
        <p>
            <?= $prepare_language_result; ?>
        </p>
    </div>
	<?php
}

function wtp_prepare_translation($lang) {
    $available_languages = pll_languages_list();
    echo "Available languages ".json_encode($available_languages);
    if (!in_array($lang, $available_languages)) return "Invalid language ".$lang;
    if ($lang == pll_default_language()) return "Il s'agit de la langue par défaut, impossible de traduire dans cette langue.";
    
    //available post_types
    $post_types = get_post_types([
        'public' => true,
    ]);

    foreach ($post_types as $pt => $pt_label) {
        if (isset($_POST[$pt]) && $_POST[$pt]) {

            // get all posts of this type in default language
            $query = new WP_Query(array(
                'post_type' => $pt,
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'lang' => pll_default_language()
            ));
            $posts = $query->posts;
            echo "Translating ".count($posts)." posts of type ".$pt."<br>\n";

            foreach ($posts as $post) {
                // check if a translation of the post exists already
                $postid_target_lang = pll_get_post($post->ID, $lang);
                if ($postid_target_lang) continue;
                
                // duplicate the post
                echo $post->ID." - ".$postid_target_lang."<br>";
                $new_post_id = duplicate_post_create_duplicate( $post, "publish");
                echo "new duplicate post of post ".$post->ID." = ".$new_post_id."<br>";

                // change the title by suffixing "_LANG"
                wp_update_post( [
                    'ID' => $new_post_id,
                    'post_title' => $post->post_title . '_' . strtoupper($lang),
                    'post_status' => 'publish'
                ] );

                // set the post language 
                pll_set_post_language($new_post_id, $lang);
                // set the linked translated parent post
                $translations = [];
                foreach ($available_languages as $tr_lang) {
                    $tr_postid = pll_get_post($post->ID, $tr_lang);
                    if ($tr_postid) $translations[$tr_lang] = $tr_postid;
                }
                $translations[$lang] = $new_post_id;
                echo "translations ".json_encode($translations)."<br>";
                pll_save_post_translations($translations);
            }
        }
    }

    return "SUCCÈS ! Le site a été préparé pour la traduction en ".$lang;

}



?>